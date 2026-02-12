<x-filament-panels::page>

    <div>
        @if (count($this->temporaryTransitions) > 0)
            <div class="mb-4 p-4 bg-warning-50 dark:bg-warning-900/20 border border-warning-200 dark:border-warning-800 rounded-lg">
                <h3 class="text-lg font-semibold text-warning-800 dark:text-warning-200 mb-2">
                    Transações Temporárias ({{ count($this->temporaryTransitions) }})
                </h3>
                <div class="space-y-2">
                    @foreach ($this->temporaryTransitions as $index => $temp)
                        <div class="flex items-center justify-between p-3 bg-white dark:bg-gray-800 rounded border border-gray-200 dark:border-gray-700">
                            <div class="flex-1">
                                <div class="flex items-center gap-2">
                                    <span class="font-semibold">{{ $temp['name'] }}</span>
                                    <span class="text-xs px-2 py-1 rounded {{ $temp['input'] == 1 ? 'bg-success-100 text-success-800' : 'bg-danger-100 text-danger-800' }}">
                                        {{ $temp['input'] == 1 ? 'Entrada' : 'Saída' }}
                                    </span>
                                    <span class="text-xs px-2 py-1 rounded bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200">
                                        {{ $temp['type'] == 'v' ? 'À vista' : 'Parcelado' }}
                                    </span>
                                </div>
                                <div class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                    R$ {{ number_format($temp['total_value'] ?? 0, 2, ',', '.') }}
                                    @if ($temp['type'] == 'p' && isset($temp['installment_amount']))
                                        - {{ $temp['installment_amount'] }}x de R$ {{ number_format($temp['installment_value'] ?? 0, 2, ',', '.') }}
                                    @endif
                                </div>
                            </div>
                            <button 
                                wire:click="removeTemporary({{ $index }})" 
                                class="text-danger-600 hover:text-danger-800"
                                type="button">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <form wire:submit="create">
            {{ $this->form }}

            <button type="submit"
                class="bg-primary-500 mt-2 hover:bg-primary-600 mb-4 text-white font-semibold py-2 px-4 rounded-lg other-btn">
                Executar
            </button>
        </form>

        <x-filament-actions::modals />
    </div>

    <div>
        {{-- @if (data_get($this->data, 'start_date') && data_get($this->data, 'end_date')) --}}
            @livewire(\App\Filament\Widgets\ContractChart::class, [
                'start_date' => data_get($this->data, 'start_date'),
                'end_date' => data_get($this->data, 'end_date'),
                'temporaryTransitions' => $this->temporaryTransitions,
            ])
            <br>
            @livewire(\App\Filament\Widgets\InputOverview::class, [
                'start_date' => data_get($this->data, 'start_date'),
                'end_date' => data_get($this->data, 'end_date'),
                'temporaryTransitions' => $this->temporaryTransitions,
            ])
        {{-- @endif --}}
    </div>

</x-filament-panels::page>
