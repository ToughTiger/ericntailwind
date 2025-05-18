window.addEventListener('load', () => {
document.addEventListener('livewire:init', () => {
    Livewire.on('use-content', (data) => {
        console.log('Event received:', data);
        alert(JSON.stringify(data)); // Simple visible confirmation
        // Try multiple approaches to ensure it works
        const editor = document.querySelector(`[wire\\:model="data.${data.field}"]`);
        const proseMirror = document.querySelector(`[wire\\:model="data.${data.field}"] + .ProseMirror`);
        // 1. Update the hidden textarea (for Livewire)
        const textarea = document.querySelector(`[wire\\:model="data.${data.field}"]`);
        // Approach 1: Standard input/textarea
        if (editor) {
            editor.value = data.content;
            editor.dispatchEvent(new Event('input', { bubbles: true }));
        }

        // Approach 2: Filament's ProseMirror editor
        if (proseMirror) {
            // Method 1: Direct content update
            proseMirror.innerHTML = data.content;

            // Method 2: Trigger editor update
            const event = new CustomEvent('update', {
                detail: {
                    markdown: data.content,
                    html: data.content
                }
            });
            proseMirror.dispatchEvent(event);
        }

        // Method 3: Force Livewire update
        Livewire.find(editor.closest('[wire\\:id]').getAttribute('wire:id'))
            .set(`data.${data.field}`, data.content);
    });
});
});