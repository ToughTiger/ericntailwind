<div
    x-data="{
        title: @entangle('title'),
        prompt: @entangle('prompt'),
        primary_keyword: @entangle('primary_keyword'),
        language: @entangle('language'),
        target_words: @entangle('target_words'),
        brand_voice: @entangle('brand_voice'),
        target_audience: @entangle('target_audience'),
        include_faq: @entangle('include_faq'),
        include_howto: @entangle('include_howto'),
        include_tldr: @entangle('include_tldr'),
        competitorUrls: @entangle('competitorUrls'),
        loading: @entangle('loading'),
        error: @entangle('error'),
        out_title: @entangle('out_title'),
        out_keywords: @entangle('out_keywords'),
        out_meta: @entangle('out_meta'),
        out_featured: @entangle('out_featured'),
        out_content_md: @entangle('out_content_md'),
        copy(txt){ navigator.clipboard.writeText(txt || ''); },
    }"
    class="space-y-4"
>
    <!-- Inputs row -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
        <div>
            <label class="block text-sm font-medium mb-1">Topic / Title</label>
            <input type="text" x-model="title"  class="w-full rounded-md border border-gray-300 bg-white text-gray-900
       dark:border-gray-600 dark:bg-gray-800 dark:text-white px-3 py-2" placeholder="e.g., What is CTMS in Clinical Trial?" />
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Primary Keyword</label>
            <input type="text" x-model="primary_keyword" class="w-full rounded-md border border-gray-300 bg-white text-gray-900
       dark:border-gray-600 dark:bg-gray-800 dark:text-white px-3 py-2" placeholder="CTMS, EDC, IWRS" />
        </div>
        <div class="md:col-span-2">
            <label class="block text-sm font-medium mb-1">Brief / Notes</label>
            <textarea class="w-full rounded-md border border-gray-300 bg-white text-gray-900
       dark:border-gray-600 dark:bg-gray-800 dark:text-white px-3 py-2" x-model="prompt" rows="4" placeholder="Goal, audience, must-cover points, CTA..."></textarea>
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Brand Voice</label>
            <input type="text" x-model="brand_voice" class="w-full rounded-md border border-gray-300 bg-white text-gray-900
       dark:border-gray-600 dark:bg-gray-800 dark:text-white px-3 py-2" placeholder="expert, friendly, no fluff" />
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Audience</label>
            <input type="text" x-model="target_audience" class="dark:border-gray-600 dark:bg-gray-800 dark:text-white px-3 py-2" placeholder="Indian SMB owners evaluating CRMs" />
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Language</label>
            <select class="w-full rounded-md border border-gray-300 bg-white text-gray-900
       dark:border-gray-600 dark:bg-gray-800 dark:text-white px-3 py-2" x-model="language">
                <option value="en">English</option>
                <option value="hi">Hindi</option>
                <option value="mr">Marathi</option>
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Target Words</label>
            <input type="number" x-model.number="target_words" min="600" max="4000" class="w-full rounded-md border border-gray-300 bg-white text-gray-900
       dark:border-gray-600 dark:bg-gray-800 dark:text-white px-3 py-2" />
        </div>
    </div>

    <!-- Competitor URLs -->
    <div class="space-y-2">
        <div class="text-sm font-medium">Competitor URLs (3–5)</div>
        <template x-for="(u, i) in competitorUrls" :key="i">
            <div class="flex gap-2">
                <input class="flex-1 rounded-md border border-gray-300 bg-white text-gray-900
       dark:border-gray-600 dark:bg-gray-800 dark:text-white px-3 py-2" type="text" x-model="competitorUrls[i]" placeholder="https://example.com/article" />
                <button type="button" class="px-3 py-2 rounded bg-red-600 text-white text-sm" x-on:click="$wire.removeCompetitorUrl(i)">Remove</button>
            </div>
        </template>
        <button type="button" class="px-3 py-2 rounded dark:bg-gray-800 text-sm" x-on:click="$wire.addCompetitorUrl()">+ Add URL</button>
    </div>

    <!-- Toggles + Generate -->
    <div class="flex flex-wrap items-center gap-3">
        <label class="flex items-center gap-2"><input type="checkbox" x-model="include_faq"> <span>Include FAQ</span></label>
        <label class="flex items-center gap-2"><input type="checkbox" x-model="include_howto"> <span>Include HowTo</span></label>
        <label class="flex items-center gap-2"><input type="checkbox" x-model="include_tldr"> <span>Include TL;DR</span></label>

        <button type="button" class="ml-auto px-4 py-2 border border-gray-300 bg-white text-gray-900
       dark:border-gray-600 dark:bg-gray-800 dark:text-white" x-bind:disabled="loading" x-on:click="$wire.generate()">
            <span x-show="!loading">Generate</span>
            <span x-show="loading">Generating…</span>
        </button>
    </div>

    <!-- Preview Panel -->
    <template x-if="out_title || out_content_md">
        <div class="rounded-lg border bg-white/70 p-4 space-y-3 dark:border-gray-600 dark:bg-gray-800 dark:text-white" >
            <div class="flex items-center justify-between">
                <div class="font-semibold">AI Draft (review & copy)</div>
                <button type="button" class="px-3 py-2 rounded bg-emerald-600 text-white text-sm dark:border-gray-600 dark:bg-gray-800 dark:text-white" x-on:click="$wire.applyToFilament()">Use in form</button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                <div>
                    <div class="text-xs text-gray-500 mb-1">Title</div>
                    <div class="flex gap-2">
                        <input class="w-full rounded border p-2 dark:border-gray-600 dark:bg-gray-800 dark:text-white" type="text" x-model="out_title">
                        <button type="button" class="px-2 py-1 rounded bg-gray-200 text-xs dark:border-gray-600 dark:bg-gray-800 dark:text-white" x-on:click="copy(out_title)">Copy</button>
                    </div>
                </div>

                <div>
                    <div class="text-xs text-gray-500 mb-1 dark:border-gray-600 dark:bg-gray-800 dark:text-white">Keywords (CSV)</div>
                    <div class="flex gap-2">
                        <input class="w-full rounded border p-2 dark:border-gray-600 dark:bg-gray-800 dark:text-white" type="text" x-model="out_keywords">
                        <button type="button" class="px-2 py-1 rounded bg-gray-200 text-xs dark:border-gray-600 dark:bg-gray-800 dark:text-white" x-on:click="copy(out_keywords)">Copy</button>
                    </div>
                </div>

                <div class="md:col-span-2">
                    <div class="text-xs text-gray-500 mb-1">Meta Description</div>
                    <div class="flex gap-2">
                        <textarea class="w-full rounded border p-2 dark:border-gray-600 dark:bg-gray-800 dark:text-white" rows="2" x-model="out_meta"></textarea>
                        <button type="button" class="px-2 py-1 rounded bg-gray-200 text-xs dark:border-gray-600 dark:bg-gray-800 dark:text-white" x-on:click="copy(out_meta)">Copy</button>
                    </div>
                </div>

                <div class="md:col-span-2" x-show="out_featured">
                    <div class="text-xs text-gray-500 mb-1 dark:border-gray-600 dark:bg-gray-800 dark:text-white">Featured Snippet</div>
                    <div class="flex gap-2">
                        <textarea class="w-full rounded border p-2 dark:border-gray-600 dark:bg-gray-800 dark:text-white" rows="2" x-model="out_featured"></textarea>
                        <button type="button" class="px-2 py-1 rounded bg-gray-200 text-xs dark:border-gray-600 dark:bg-gray-800 dark:text-white" x-on:click="copy(out_featured)">Copy</button>
                    </div>
                </div>

                <div class="md:col-span-2">
                    <div class="text-xs text-gray-500 mb-1 dark:border-gray-600 dark:bg-gray-800 dark:text-white">Content (Markdown)</div>
                    <div class="flex gap-2">
                        <textarea class="w-full rounded border p-2 font-mono text-sm dark:border-gray-600 dark:bg-gray-800 dark:text-white" rows="12" x-model="out_content_md"></textarea>
                        <button type="button" class="px-2 py-1 rounded bg-gray-200 text-xs dark:border-gray-600 dark:bg-gray-800 dark:text-white" x-on:click="copy(out_content_md)">Copy</button>
                    </div>
                </div>
            </div>
        </div>
    </template>

    <!-- Fill Filament inputs when user confirms -->
    <script>
        window.addEventListener('ai-post-generated', (e) => {
            const d = e.detail || {};
            const setVal = (name, val) => {
                const el = document.querySelector(`[name="${name}"]`);
                if (!el) return;
                el.value = val ?? '';
                el.dispatchEvent(new Event('input', { bubbles: true }));
                el.dispatchEvent(new Event('change', { bubbles: true }));
            };
            setVal('title', d.title);
            setVal('keywords', d.keywords);
            setVal('meta_description', d.meta_description);
            setVal('content', d.content);
            setVal('featured_snippet', d.featured_snippet);
        });
    </script>

    <!-- Error -->
    <template x-if="error">
        <div class="text-red-600" x-text="error"></div>
    </template>
</div>
