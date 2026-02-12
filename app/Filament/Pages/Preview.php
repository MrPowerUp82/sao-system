<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Contracts\HasForms;
use App\Models\GeneralizedTransition;
use Filament\Notifications\Notification;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Set;
use Filament\Forms\Get;
use Carbon\Carbon;
use Filament\Actions\Action as PageAction;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\Repeater;

class Preview extends Page implements HasForms, HasActions
{
    use InteractsWithForms;
    use InteractsWithActions;
    
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static string $view = 'filament.pages.preview';
     protected static ?string $navigationLabel = 'Simulação';
       protected static ?string $navigationGroup = 'Financeiro';
    
    public ?array $data = [
        'start_date' => null,
        'end_date' => null,
    ];

    public array $temporaryTransitions = [];

    public function mount(): void
    {
        $this->form->fill(request()->all());
        $this->loadTemporaryTransitions();
    }

    public function loadTemporaryTransitions(): void
    {
        $this->temporaryTransitions = session('temporary_transitions', []);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\DatePicker::make('start_date')
                    ->label('Data de início')
                    ->required()
                    ->default(now()),
                Forms\Components\DatePicker::make('end_date')
                    ->label('Data de término')
                    ->required()
            ])
            ->statePath('data');
    }
    
    protected function getHeaderActions(): array
    {
        return [
            PageAction::make('addTemporary')
                ->label('Adicionar Transação Temporária')
                ->icon('heroicon-o-plus-circle')
                ->color('success')
                ->modalWidth('5xl')
                ->form([
                    Forms\Components\TextInput::make('name')
                        ->label('Nome')
                        ->required()
                        ->columnSpan(4),
                    Forms\Components\Select::make('input')
                        ->label('Tipo')
                        ->required()
                        ->columnSpan(4)
                        ->options([
                            0 => "Saída",
                            1 => "Entrada",
                        ]),
                    Forms\Components\Select::make('type')
                        ->label('Forma')
                        ->required()
                        ->columnSpan(4)
                        ->options([
                            "v" => "À vista",
                            "p" => "Parcelado",
                        ])
                        ->live()
                        ->afterStateUpdated(fn(Forms\Components\Select $component) => $component
                            ->getContainer()
                            ->getComponent('dynamicTypeFields')
                            ->getChildComponentContainer()
                            ->fill()),
                    Forms\Components\Grid::make(12)
                        ->schema(fn(Get $get): array => match ($get('type')) {
                            "v" => [
                                Forms\Components\TextInput::make('total_value')
                                    ->label('Valor Total')
                                    ->columnSpan(6)
                                    ->required()
                                    ->prefix('R$')
                                    ->numeric()
                                    ->inputMode('decimal'),
                                Forms\Components\DatePicker::make('start_date')
                                    ->label('Data')
                                    ->columnSpan(6)
                                    ->required(),
                                Forms\Components\ToggleButtons::make('fix')
                                    ->label('Fixo?')
                                    ->columnSpan(12)
                                    ->default(0)
                                    ->boolean()
                                    ->grouped(),
                            ],
                            "p" => [
                                Forms\Components\DatePicker::make('start_date')
                                    ->label('Data de início')
                                    ->columnSpan(6)
                                    ->required(),
                                Forms\Components\DatePicker::make('end_date')
                                    ->label('Data de término')
                                    ->columnSpan(6)
                                    ->required(),
                                Forms\Components\TextInput::make('installment_amount')
                                    ->label('Quantidade de parcelas')
                                    ->columnSpan(12)
                                    ->required()
                                    ->numeric(),
                                Forms\Components\TextInput::make('total_value')
                                    ->label('Valor Total')
                                    ->columnSpan(6)
                                    ->required()
                                    ->prefix('R$')
                                    ->numeric()
                                    ->inputMode('decimal'),
                                Forms\Components\TextInput::make('installment_value')
                                    ->label('Valor da parcela')
                                    ->columnSpan(6)
                                    ->required()
                                    ->prefix('R$')
                                    ->numeric()
                                    ->hintAction(
                                        Action::make('autoGet')
                                            ->label('Calcular sem juros?')
                                            ->action(function (Get $get, Set $set) {
                                                if ($get('total_value') && $get('installment_amount')) {
                                                    $value = round($get('total_value') / $get('installment_amount'), 2);
                                                    $set('installment_value', $value);
                                                }
                                            })
                                    )
                                    ->inputMode('decimal'),
                            ],
                            default => [],
                        })
                        ->columnSpan(12)
                        ->key('dynamicTypeFields'),
                    Forms\Components\TagsInput::make('tags')
                        ->suggestions(['Cartão', 'Boleto', 'Pix', 'Simulação'])
                        ->label('Tags')
                        ->columnSpan(12),
                    Forms\Components\Textarea::make('description')
                        ->label('Descrição')
                        ->columnSpan(12)
                        ->rows(3),
                ])
                ->action(function (array $data) {
                    $temporaryTransitions = session('temporary_transitions', []);
                    $data['id'] = 'temp_' . uniqid();
                    $data['is_temporary'] = true;
                    $temporaryTransitions[] = $data;
                    session(['temporary_transitions' => $temporaryTransitions]);
                    $this->loadTemporaryTransitions();
                    
                    Notification::make()
                        ->title('Transação temporária adicionada')
                        ->success()
                        ->body('A transação foi adicionada à simulação.')
                        ->send();
                }),
            PageAction::make('clearTemporary')
                ->label('Limpar Temporárias')
                ->icon('heroicon-o-trash')
                ->color('danger')
                ->requiresConfirmation()
                ->action(function () {
                    session()->forget('temporary_transitions');
                    $this->temporaryTransitions = [];
                    
                    Notification::make()
                        ->title('Transações temporárias removidas')
                        ->success()
                        ->send();
                })
                ->visible(fn() => count($this->temporaryTransitions) > 0),
            PageAction::make('saveTemporary')
                ->label('Salvar Temporárias')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->requiresConfirmation()
                ->modalDescription('Deseja salvar todas as transações temporárias como registros permanentes?')
                ->action(function () {
                    $saved = 0;
                    foreach ($this->temporaryTransitions as $temp) {
                        try {
                            GeneralizedTransition::create([
                                'user_id' => auth()->id(),
                                'name' => $temp['name'],
                                'input' => $temp['input'],
                                'type' => $temp['type'],
                                'total_value' => $temp['total_value'] ?? null,
                                'installment_value' => $temp['installment_value'] ?? null,
                                'installment_amount' => $temp['installment_amount'] ?? null,
                                'start_date' => $temp['start_date'] ?? null,
                                'end_date' => $temp['end_date'] ?? null,
                                'fix' => $temp['fix'] ?? 0,
                                'tags' => $temp['tags'] ?? null,
                                'description' => $temp['description'] ?? null,
                            ]);
                            $saved++;
                        } catch (\Exception $e) {
                            // Log error
                        }
                    }
                    
                    session()->forget('temporary_transitions');
                    $this->temporaryTransitions = [];
                    
                    Notification::make()
                        ->title('Transações salvas')
                        ->success()
                        ->body("$saved transação(ões) salva(s) com sucesso.")
                        ->send();
                })
                ->visible(fn() => count($this->temporaryTransitions) > 0),
        ];
    }
    
    public function removeTemporary(int $index): void
    {
        $temporaryTransitions = session('temporary_transitions', []);
        unset($temporaryTransitions[$index]);
        $temporaryTransitions = array_values($temporaryTransitions);
        session(['temporary_transitions' => $temporaryTransitions]);
        $this->loadTemporaryTransitions();
        
        Notification::make()
            ->title('Transação removida')
            ->success()
            ->send();
    }
    
    public function create()
    {
        return redirect()->route($this->getRouteName(), $this->form->getState());
    }
}
