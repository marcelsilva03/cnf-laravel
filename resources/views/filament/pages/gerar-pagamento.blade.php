<x-filament-panels::page>
    <form wire:submit="gerar">
        {{ $this->form }}
        
        <div class="mt-6 flex justify-end">
            @foreach($this->getFormActions() as $action)
                {{ $action }}
            @endforeach
        </div>
    </form>

    <x-filament-actions::modals />
</x-filament-panels::page> 