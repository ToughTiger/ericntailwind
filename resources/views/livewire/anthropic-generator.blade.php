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

        <button type="button" class="ml-auto px-4 py-2 rounded-lg font-semibold transition-all duration-200
            dark:border-gray-600" 
            x-bind:class="loading ? 'bg-blue-400 text-white cursor-not-allowed' : 'bg-blue-600 hover:bg-blue-700 text-white'"
            x-bind:disabled="loading" 
            x-on:click="$wire.generate()">
            <span x-show="!loading" class="flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
                Generate Post
            </span>
            <span x-show="loading" class="flex items-center gap-2">
                <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Generating...
            </span>
        </button>
    </div>
    
    <!-- Loading Progress Indicator -->
    <template x-if="loading">
        <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
            <div class="flex items-center gap-3">
                <svg class="animate-spin h-6 w-6 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <div class="flex-1">
                    <div class="font-semibold text-blue-900 dark:text-blue-100">Generating your content...</div>
                    <div class="text-sm text-blue-700 dark:text-blue-300 mt-1">
                        This may take 30-60 seconds. Analyzing competitors and crafting your post.
                    </div>
                </div>
            </div>
            <div class="mt-3 bg-blue-200 dark:bg-blue-800 rounded-full h-2 overflow-hidden">
                <div class="bg-blue-600 h-full animate-pulse" style="width: 100%"></div>
            </div>
        </div>
    </template>

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
        // Log when component loads
        console.log('Anthropic Generator Component Loaded');
        
        window.addEventListener('ai-post-generated', (e) => {
            console.log('AI Post Generated Event:', e.detail);
            const d = e.detail || {};
            const setVal = (name, val) => {
                const el = document.querySelector(`[name="${name}"]`);
                if (!el) {
                    console.warn(`Field not found: ${name}`);
                    return;
                }
                el.value = val ?? '';
                el.dispatchEvent(new Event('input', { bubbles: true }));
                el.dispatchEvent(new Event('change', { bubbles: true }));
                console.log(`Set ${name}:`, val?.substring(0, 50) + '...');
            };
            setVal('title', d.title);
            setVal('keywords', d.keywords);
            setVal('meta_description', d.meta_description);
            setVal('content', d.content);
            setVal('featured_snippet', d.featured_snippet);
        });
        
        // Debug: Log loading state changes
        document.addEventListener('livewire:load', function () {
            Livewire.hook('message.processed', (message, component) => {
                if (component.el.querySelector('[x-data]')) {
                    console.log('Livewire message processed');
                }
            });
        });
    </script>

    <!-- Error -->
    <template x-if="error">
        <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4">
            <div class="flex items-start gap-3">
                <svg class="w-6 h-6 text-red-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div class="flex-1">
                    <div class="font-semibold text-red-900 dark:text-red-100">Generation Failed</div>
                    <div class="text-sm text-red-700 dark:text-red-300 mt-1" x-text="error"></div>
                    <div class="mt-2 text-xs text-red-600 dark:text-red-400">
                        💡 Tip: Check your browser console and Laravel logs (storage/logs/laravel.log) for more details.
                    </div>
                </div>
            </div>
        </div>
    </template>
</div>
