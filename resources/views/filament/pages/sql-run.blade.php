<x-filament-panels::page>
    {{ $this->createAction }}

    <div class="mt-2">
        {{ $this->table }}
    </div>

    <form wire:submit="create">
        {{ $this->form }}

        <button type="submit"
            class="bg-primary-500 mt-2 hover:bg-primary-600 mb-4 text-white font-semibold py-2 px-4 rounded-lg other-btn">
            Executar
        </button>
    </form>

    {{-- @script
        <script>
            $wire.on('updatePlugin', (event) => {
                // console.log(event)
                if (!event[1]) {                    
                    window.editor.setValue(event[0]);
                }                
            });
        </script>
    @endscript --}}

    <x-filament-actions::modals />
</x-filament-panels::page>