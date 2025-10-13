<x-filament-panels::page>
    <form wire:submit="save">
        {{ $this->form }}

        <div class="flex justify-start gap-2 mt-6">
            <x-filament::button type="submit">
                {{ __('main-info.actions.save') }}
            </x-filament::button>
        </div>
    </form>

    <x-filament-actions::modals />
</x-filament-panels::page>
