<?php

namespace Santosh\FilamentAiTools\Http\Livewire;

use Filament\Forms\Components\Livewire;
use Filament\Forms\Components\Component;
use Livewire\Component as LivewireComponent;
use OpenAI\Laravel\Facades\OpenAI;
class ContentGenerator extends LivewireComponent
{
    public $topic = '';
    public $output = '';

    public $targetField; 
    public $loading = false;

     public function mount($targetField = 'content'): void
    {
        // Get the bound field from Filament automatically
        $this->targetField = $targetField;

    }

    public function generate()
    {
         $this->validate(['topic' => 'required|min:3']);
         $this->loading = true;
       try {
        $response = OpenAI::chat()->create([
            'model' => config('filament-ai-tools.default_model'),
            'messages' =>[
                  [
                    'role' => 'user', 
                    'content' => "Write a blog about: {$this->topic}"
                ],
            ],
                'temperature' => config('filament-ai-tools.default_temperature'),
                'max_tokens' => config('filament-ai-tools.max_tokens'),
                        
        ]);

         $this->output = $response->choices[0]->message->content ?? 'No output';
         $this->dispatch('content-generated', [
            'content'=> $this->output, 
            'targetField' => $this->targetField
        ]);

    } catch (\Exception $e) {
        // Handle errors, if any
        $this->output = "Error generating content: " . $e->getMessage();
    }finally {
            $this->loading = false;
        }
        
    }
    public function useContent()
    {
        
        $this->dispatch('use-content', 
            content: $this->output,
            field: $this->targetField // The target field name
        );
    }


    public function render()
    {
        return view('filament-ai-tools::livewire.content-generator');

    }

}
