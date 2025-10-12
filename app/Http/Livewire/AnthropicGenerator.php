<?php

namespace App\Http\Livewire;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class AnthropicGenerator extends Component
{
    // ===== User inputs =====
    public string $title = '';               // Topic / desired title (optional)
    public string $prompt = '';              // Extra notes: goal, audience, must-cover, CTA
    public string $primary_keyword = '';     // Main keyword/intent
    public string $language = 'en';          // Output language code
    public int $target_words = 1400;         // Soft target words
    public array $competitorUrls = [];       // Up to 3-5 URLs

    // Stylistic / structure controls
    public string $brand_voice = '';         // e.g., "expert, friendly, no fluff"
    public string $target_audience = '';     // e.g., "Indian SMB owners evaluating CRM tools"
    public bool $include_faq = true;
    public bool $include_howto = false;
    public bool $include_tldr = true;

    // ===== Runtime =====
    public bool $loading = false;
    public ?string $error = null;

    // ===== Generated outputs (preview & apply) =====
    public ?string $out_title = null;
    public ?string $out_keywords = null;     // CSV
    public ?string $out_meta = null;         // meta description (<=160)
    public ?string $out_featured = null;     // 40–60 word short answer
    public ?string $out_content_md = null;   // full post markdown

    protected $listeners = [
        'setTitle' => 'setTitle',
        'setKeywords' => 'setPrimaryKeyword',
    ];

    // ---------- Lifecycle ----------
    public function mount(): void
    {
        if (empty($this->competitorUrls)) {
            $this->competitorUrls = ['', '']; // two empty slots by default
        }
    }

    // ---------- Simple setters ----------
    public function setTitle($title): void
    {
        $this->title = (string) $title;
    }

    public function setPrimaryKeyword($kw): void
    {
        $this->primary_keyword = (string) $kw;
    }

    public function addCompetitorUrl(): void
    {
        $this->competitorUrls[] = '';
    }

    public function removeCompetitorUrl($i): void
    {
        if (isset($this->competitorUrls[$i])) {
            array_splice($this->competitorUrls, $i, 1);
        }
    }

    protected function resetOutput(): void
    {
        $this->out_title = null;
        $this->out_keywords = null;
        $this->out_meta = null;
        $this->out_featured = null;
        $this->out_content_md = null;
    }

    // ---------- Competitor fetching ----------
    protected function analyzeCompetitors(): array
    {
        $results = [];
        $headers = [
            'User-Agent' => 'Mozilla/5.0 (compatible; ContentPlannerBot/1.0; +https://example.com/bot)',
            'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8'
        ];

        foreach ($this->competitorUrls as $url) {
            $url = trim($url);
            if ($url === '') continue;
            if (!preg_match('#^https?://#i', $url)) {
                $url = 'https://' . $url;
            }

            try {
                $resp = Http::withHeaders($headers)->timeout(20)->get($url);
                if ($resp->failed()) {
                    $results[] = ['url' => $url, 'title' => null, 'meta' => null, 'snippet' => null, 'error' => 'fetch_failed'];
                    continue;
                }

                $html = $resp->body();

                $title = null;
                if (preg_match('#<title>(.*?)</title>#si', $html, $m)) {
                    $title = trim(strip_tags($m[1]));
                }

                $meta = null;
                if (preg_match('/<meta\s+name=["\']description["\']\s+content=["\'](.*?)["\']/si', $html, $m)) {
                    $meta = trim($m[1]);
                } elseif (preg_match('/<meta\s+property=["\']og:description["\']\s+content=["\'](.*?)["\']/si', $html, $m)) {
                    $meta = trim($m[1]);
                }

                $text = preg_replace('#<script.*?>.*?</script>#si', ' ', $html);
                $text = preg_replace('#<style.*?>.*?</style>#si', ' ', $text);
                $text = strip_tags($text);
                $text = preg_replace('/\s+/', ' ', $text);
                $snippet = mb_substr($text, 0, 4000);

                $results[] = ['url' => $url, 'title' => $title, 'meta' => $meta, 'snippet' => $snippet];
            } catch (\Throwable $e) {
                Log::warning("Competitor fetch exception for {$url}", ['err' => $e->getMessage()]);
                $results[] = ['url' => $url, 'title' => null, 'meta' => null, 'snippet' => null, 'error' => 'exception'];
            }
        }

        return $results;
    }

    // ---------- Front-matter parser ----------
    /**
     * Expected model output:
     *
     * ---
     * Title: ...
     * Keywords: kw1, kw2, ...
     * MetaDescription: ...
     * FeaturedSnippet: ...
     * ---
     * # <Title>
     * <markdown body...>
     */
    protected function parseFrontMatter(string $raw): array
    {
        $raw = trim($raw);

        if (!preg_match('/^---\s*(.*?)\s*---\s*(.*)$/s', $raw, $m)) {
            // No front matter found; treat whole as body.
            return [
                'title'    => null,
                'keywords' => null,
                'meta'     => null,
                'featured' => null,
                'content'  => $raw,
            ];
        }

        $fm   = trim($m[1]);
        $body = trim($m[2]);

        $grab = function (string $key) use ($fm) {
            $re = '/^' . preg_quote($key, '/') . '\s*:\s*(.+)$/mi';
            if (preg_match($re, $fm, $mm)) {
                return trim($mm[1]);
            }
            return null;
        };

        $title    = $grab('Title');
        $keywords = $grab('Keywords');
        $meta     = $grab('MetaDescription');
        $featured = $grab('FeaturedSnippet');

        return [
            'title'    => $title,
            'keywords' => $keywords,
            'meta'     => $meta,
            'featured' => $featured,
            'content'  => $body,
        ];
    }

    // ---------- Prompt builder (no JSON, returns front-matter + markdown) ----------
    protected function buildInstruction(array $competitors): string
    {
        // Build competitor summary block
        $parts = [];
        foreach ($competitors as $c) {
            if (!empty($c['error'])) {
                $parts[] = "URL: {$c['url']} (fetch failed).";
                continue;
            }
            $parts[] = "URL: {$c['url']}\nTitle: " . ($c['title'] ?? 'N/A') .
                "\nMeta: " . ($c['meta'] ?? 'N/A') .
                "\nSnippet: " . mb_substr($c['snippet'] ?? '', 0, 600);
        }
        $compSummary = $parts ? implode("\n\n---\n\n", $parts) : 'No competitor URLs provided or none could be fetched.';

        // Controls
        $language       = $this->language;
        $brandVoice     = $this->brand_voice ?: 'expert yet friendly';
        $targetAudience = $this->target_audience ?: 'general web audience';
        $targetWords    = max(600, min(4000, $this->target_words));
        $includeFaq     = $this->include_faq ? 'yes' : 'no';
        $includeHowto   = $this->include_howto ? 'yes' : 'no';
        $includeTldr    = $this->include_tldr ? 'yes' : 'no';

        $userBrief = implode("\n", array_filter([
            $this->title ? "Title: {$this->title}" : null,
            $this->primary_keyword ? "Primary keyword: {$this->primary_keyword}" : null,
            $this->prompt ? "Notes/brief: {$this->prompt}" : null,
        ]));

        return <<<PROMPT
You are an expert SEO + AEO editor. Create a human-sounding, original blog post that can rank and satisfy user intent.

LANGUAGE: {$language}
VOICE: {$brandVoice}
AUDIENCE: {$targetAudience}
TARGET LENGTH: {$targetWords} words
INCLUDE FAQ: {$includeFaq}
INCLUDE TL;DR: {$includeTldr}
INCLUDE HOWTO: {$includeHowto}

USER BRIEF:
{$userBrief}

COMPETITOR PAGES (for gap/originality; do not copy):
{$compSummary}

REQUIREMENTS:
- Write like a human (varied sentence length, natural idioms, concrete examples).
- Strong H1, scannable H2/H3s, helpful tables/lists where useful.
- Provide a 40–60 word featured snippet answer to the primary intent.
- Suggest 12–20 mixed keywords (head + long-tail + entities) in a single CSV line.
- Meta description <=160 chars, compelling & natural.
- If FAQ='yes', include 4–6 helpful Q&As.
- If HOWTO='yes' and relevant, add a concise step-by-step section.
- Do NOT output JSON.

RETURN FORMAT (EXACTLY this Markdown layout):
---
Title: <final post title>
Keywords: kw1, kw2, kw3, ...
MetaDescription: <<=160 chars>
FeaturedSnippet: <40–60 words>
---
# <same Title as above>

(TL;DR if requested)

(Full article in Markdown, with H2/H3, bullets, examples, internal anchors, and links to sources when useful.)
PROMPT;
    }

    // ---------- Main generation ----------
    public function generateWithCompetitors(): void
    {
        $this->error = null;
        $this->loading = true;
        $this->resetOutput();

        try {
            $apiKey = trim(config('services.anthropic.key') ?? '');
            $model  = config('services.anthropic.model', 'claude-3-5-sonnet-20240620');
            $base   = rtrim(config('services.anthropic.base_uri', 'https://api.anthropic.com/v1'), '/');

            if ($apiKey === '') {
                throw new \Exception('Anthropic API key not configured.');
            }

            // 1) Competitors
            $competitorData = $this->analyzeCompetitors();

            // 2) Prompt
            $instruction = $this->buildInstruction($competitorData);

            // 3) API call
            $payload = [
                'model' => $model,
                'max_tokens' => 4000,
                'messages' => [
                    ['role' => 'user', 'content' => $instruction],
                ],
            ];

            $response = Http::withHeaders([
                'x-api-key' => $apiKey,
                'anthropic-version' => '2023-06-01',
                'accept' => 'application/json',
                'content-type' => 'application/json',
            ])->timeout(120)->post("{$base}/messages", $payload);

            if ($response->failed()) {
                Log::error('Anthropic API error', ['status' => $response->status(), 'body' => $response->body()]);
                throw new \Exception('Anthropic API returned an error: ' . $response->status());
            }

            $jsonResp = $response->json();

            // Anthropic returns content as array of blocks [{type:'text', text:'...'}]
            $raw = '';
            if (isset($jsonResp['content']) && is_array($jsonResp['content'])) {
                foreach ($jsonResp['content'] as $block) {
                    if (($block['type'] ?? '') === 'text' && isset($block['text'])) {
                        $raw .= $block['text'] . "\n";
                    }
                }
            } elseif (isset($jsonResp['content']) && is_string($jsonResp['content'])) {
                $raw = $jsonResp['content'];
            } elseif (isset($jsonResp['text'])) {
                $raw = $jsonResp['text'];
            } else {
                $raw = json_encode($jsonResp);
            }

            // 4) Parse front-matter + body
            $parsed = $this->parseFrontMatter($raw);

            $this->out_title       = $parsed['title'] ?: ($this->title ?: null);
            $this->out_keywords    = $parsed['keywords'] ?: null;
            $this->out_meta        = $parsed['meta'] ?: null;
            $this->out_featured    = $parsed['featured'] ?: null;
            $this->out_content_md  = $parsed['content'] ?: null;

        } catch (\Throwable $e) {
            $this->error = $e->getMessage();
            $this->resetOutput();
        } finally {
            $this->loading = false;
        }
    }

    public function generate(): void
    {
        $this->generateWithCompetitors();
    }

    // ---------- Apply to Filament form on user click ----------
    public function applyToFilament(): void
    {
        $this->dispatchBrowserEvent('ai-post-generated', [
            'title'            => $this->out_title,
            'keywords'         => $this->out_keywords,
            'meta_description' => $this->out_meta,
            'featured_snippet' => $this->out_featured,
            'content'          => $this->out_content_md,
        ]);
    }

    public function render()
    {
        return view('livewire.anthropic-generator');
    }
}
