


    <div class="space-y-4">
        <x-filament-forms::field-wrapper
            label="AI Content Generator"
            helper-text="Enter a topic to generate content">
            <div class="flex gap-2">
                <input
                    type="text"
                    wire:model="topic"
                    placeholder="Enter topic..."
                    class="flex-1 filament-forms-text-input">
                <x-filament::button
                    wire:click="generate"
                    color="primary"
                    icon="heroicon-o-sparkles">
                    Generate
                </x-filament::button>
            </div>
        </x-filament-forms::field-wrapper>

        @if($output)
        <div class="prose max-w-none border rounded-lg p-4 bg-gray-50">
            {!! Str::markdown($output) !!}
            <div class="mt-4">
                <x-filament::button
                    wire:click="useContent"
                    icon="heroicon-o-document-check">
                    Use This Content
                </x-filament::button>
            </div>
        </div>
        @endif
    </div>

