{{-- resources/views/filament/auth/login.blade.php --}}
<x-filament-panels::page.simple class="fi-auth-page">
    <x-slot name="heading">
        {{ __('Login') }}
    </x-slot>

    {{-- Filament login form --}}
    {{ $this->form }}

    {{-- Filament default actions (Login button etc.) --}}
    <x-filament-panels::form.actions
        :actions="$this->getFormActions()"
        :full-width="$this->hasFullWidthFormActions()"
        class="mt-4"
    />

    {{-- Add our Forgot Password link --}}
    <div class="mt-4 text-right">
        <a
            href="{{ route('password.request') }}"
            class="text-sm text-primary-600 hover:underline"
        >
            Forgot your password?
        </a>
    </div>
</x-filament-panels::page.simple>
