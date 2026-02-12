<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GeneralizedTransitionResource\Pages;
use App\Filament\Resources\GeneralizedTransitionResource\RelationManagers;
use App\Models\GeneralizedTransition;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Set;
use Filament\Forms\Get;
use Filament\Notifications\Notification;
use Carbon\Carbon;
use App\Core\Filament\Traits\HasTranslateResource;
use Illuminate\Database\Query\Builder as QueryBuilder;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use Filament\Tables\Actions\BulkAction;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Filament\Forms\Components\FileUpload;


class GeneralizedTransitionResource extends Resource
{
    use HasTranslateResource;
    protected static ?string $model = GeneralizedTransition::class;

    protected static ?string $navigationIcon = 'heroicon-o-wallet';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label(static::translateForm("name"))
                    ->placeholder(static::translateFormPlaceholder("name"))
                    ->columnSpan([
                        'default' => 12,
                        'sm' => 6,
                        'md' => 6,
                        'lg' => 4,
                        'xl' => 4,
                    ])
                    ->required(),
                Forms\Components\Select::make('input')
                    ->label(static::translateForm("input"))
                    ->placeholder(static::translateFormPlaceholder("input"))
                    ->required()
                    ->columnSpan([
                        'default' => 12,
                        'sm' => 6,
                        'md' => 6,
                        'lg' => 4,
                        'xl' => 4,
                    ])
                    ->options([
                        0 => "Saída",
                        1 => "Entrada",
                    ]),
                Forms\Components\Select::make('type')
                    ->label(static::translateForm("type"))
                    ->placeholder(static::translateFormPlaceholder("type"))
                    ->required()
                    ->columnSpan([
                        'default' => 12,
                        'sm' => 6,
                        'md' => 6,
                        'lg' => 4,
                        'xl' => 4,
                    ])
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
                Forms\Components\Grid::make([
                    'default' => 12,
                    'sm' => 12,
                    'md' => 12,
                    'lg' => 12,
                    'xl' => 12,
                    '2xl' => 12,
                ])
                    ->schema(fn(Get $get): array => match ($get('type')) {
                        "v" => [
                            Forms\Components\TextInput::make('total_value')
                                ->label(static::translateForm("total_value"))
                                ->placeholder(static::translateFormPlaceholder("total_value"))
                                ->columnSpan([
                                    'default' => 12,
                                    'sm' => 6,
                                    'md' => 6,
                                    'lg' => 4,
                                    'xl' => 4,
                                ])
                                ->required()
                                ->prefix('R$')
                                ->numeric()
                                ->inputMode('decimal'),
                            Forms\Components\DatePicker::make('start_date')
                                ->label(static::translateForm("start_date"))
                                ->placeholder(static::translateFormPlaceholder("start_date"))
                                ->columnSpan([
                                    'default' => 12,
                                    'sm' => 6,
                                    'md' => 6,
                                    'lg' => 4,
                                    'xl' => 4,
                                ])
                                ->required(),
                            Forms\Components\ToggleButtons::make('fix')
                                ->label(static::translateForm("fix"))
                                ->columnSpan([
                                    'default' => 12,
                                    'sm' => 12,
                                    'md' => 4,
                                    'lg' => 4,
                                    'xl' => 4,
                                ])
                                ->default(0)
                                ->boolean()
                                ->grouped(),

                            Forms\Components\TagsInput::make('tags')
                                ->suggestions([
                                    'Cartão',
                                    'Boleto',
                                    'Pix'
                                ])
                                ->label(static::translateForm("tags"))
                                ->placeholder(static::translateFormPlaceholder("tags"))
                                ->columnSpan([
                                    'default' => 12,
                                    'sm' => 12,
                                    'md' => 12,
                                    'lg' => 12,
                                    'xl' => 12,
                                ]),
                        ],
                        "p" => [
                            Forms\Components\DatePicker::make('start_date')
                                ->label(static::translateForm("start_date"))
                                ->placeholder(static::translateFormPlaceholder("start_date"))
                                ->columnSpan([
                                    'default' => 12,
                                    'sm' => 12,
                                    'md' => 6,
                                    'lg' => 6,
                                    'xl' => 6,
                                ])
                                ->required(),
                            Forms\Components\DatePicker::make('end_date')
                                ->label(static::translateForm("end_date"))
                                ->placeholder(static::translateFormPlaceholder("end_date"))
                                ->columnSpan([
                                    'default' => 12,
                                    'sm' => 12,
                                    'md' => 6,
                                    'lg' => 6,
                                    'xl' => 6,
                                ])
                                ->required(),
                            Forms\Components\TextInput::make('installment_amount')
                                ->label(static::translateForm("installment_amount"))
                                ->placeholder(static::translateFormPlaceholder("installment_amount"))
                                ->columnSpan([
                                    'default' => 12,
                                    'sm' => 12,
                                    'md' => 12,
                                    'lg' => 12,
                                    'xl' => 12,
                                ])
                                ->required()
                                ->hintAction(
                                    Action::make('autoGetEndDate')
                                        ->label('Preencher data de término automaticamente')
                                        ->action(function (Get $get, Set $set, $state) {
                                                if ($get('start_date') && $state) {
                                                    $end_date = Carbon::parse($get('start_date'))->addMonths($state - 1)->toDateString();
                                                    $set('end_date', $end_date);
                                                    Notification::make('success')
                                                    ->title('Sucesso')
                                                    ->success()
                                                    ->body('Valor da parcela calculado com sucesso.')
                                                    ->send();
                                                } else {
                                                    Notification::make('error')
                                                    ->title('Erro')
                                                    ->danger()
                                                    ->body('Preencha o valor total e a quantidade de parcelas.')
                                                    ->send();
                                                }
                                            })
                                )
                                ->numeric(),
                            Forms\Components\TextInput::make('total_value')
                                ->label(static::translateForm("total_value"))
                                ->placeholder(static::translateFormPlaceholder("total_value"))
                                ->columnSpan([
                                    'default' => 12,
                                    'sm' => 6,
                                    'md' => 6,
                                    'lg' => 6,
                                    'xl' => 6,
                                ])
                                ->required()
                                ->prefix('R$')
                                ->numeric()
                                ->hintAction(
                                    Action::make('autoGetTotalValue')
                                        ->label('Calcular Total?')
                                        ->action(function (Get $get, Set $set, $state) {
                                                if ($get('installment_amount') && $get('installment_value')) {
                                                    $state = $get('installment_amount') * $get('installment_value');
                                                    $set('total_value', $state);
                                                    Notification::make('success')
                                                    ->title('Sucesso')
                                                    ->success()
                                                    ->body('Valor total calculado com sucesso.')
                                                    ->send();
                                                } else if ($get('installment_value') && $get('start_date') && $get('end_date')) {
                                                    $start_date = Carbon::parse($get('start_date'));
                                                    $end_date = Carbon::parse($get('end_date'));
                                                    $months_difference = $start_date->diffInMonths($end_date) - 1;
                                                    $state = $get('installment_value') * $months_difference;
                                                    $set('total_value', $state);
                                                    Notification::make('success')
                                                    ->title('Sucesso')
                                                    ->success()
                                                    ->body('Valor total calculado com sucesso.')
                                                    ->send();
                                                } else {
                                                    Notification::make('error')
                                                    ->title('Erro')
                                                    ->danger()
                                                    ->body('Preencha o valor da parcela e a quantidade de parcelas.')
                                                    ->send();
                                                }
                                            })
                                )
                                ->inputMode('decimal'),
                            Forms\Components\TextInput::make('installment_value')
                                ->label(static::translateForm("installment_value"))
                                ->placeholder(static::translateFormPlaceholder("installment_value"))
                                ->columnSpan([
                                    'default' => 12,
                                    'sm' => 12,
                                    'md' => 6,
                                    'lg' => 6,
                                    'xl' => 6,
                                ])
                                ->required()
                                ->prefix('R$')
                                ->numeric()
                                ->hintAction(
                                    Action::make('autoGet')
                                        ->label('Calcular sem juros?')
                                        ->action(function (Get $get, Set $set, $state) {
                                                if ($get('total_value') && $get('installment_amount')) {
                                                    $state = round($get('total_value') / $get('installment_amount'), 2);
                                                    $set('installment_value', $state);
                                                    Notification::make('success')
                                                    ->title('Sucesso')
                                                    ->success()
                                                    ->body('Valor da parcela calculado com sucesso.')
                                                    ->send();
                                                } else if ($get('total_value') && $get('start_date') && $get('end_date')) {
                                                    $start_date = Carbon::parse($get('start_date'));
                                                    $end_date = Carbon::parse($get('end_date'));
                                                    $months_difference = $start_date->diffInMonths($end_date) - 1;
                                                    $state = round($get('total_value') / $months_difference, 2);
                                                    $set('installment_value', $state);
                                                    Notification::make('success')
                                                    ->title('Sucesso')
                                                    ->success()
                                                    ->body('Valor da parcela calculado com sucesso.')
                                                    ->send();
                                                } else {
                                                    Notification::make('error')
                                                    ->title('Erro')
                                                    ->danger()
                                                    ->body('Preencha o valor total e a quantidade de parcelas.')
                                                    ->send();
                                                }
                                            })
                                )
                                ->inputMode('decimal'),
                            Forms\Components\TagsInput::make('tags')
                                ->suggestions([
                                    'Cartão',
                                    'Boleto',
                                    'Pix'
                                ])
                                ->label(static::translateForm("tags"))
                                ->placeholder(static::translateFormPlaceholder("tags"))
                                ->columnSpan([
                                    'default' => 12,
                                    'sm' => 12,
                                    'md' => 12,
                                    'lg' => 12,
                                    'xl' => 12,
                                ]),
                        ],
                        default => [],
                    })
                    ->columnSpan(12)
                    ->key('dynamicTypeFields'),
                Forms\Components\RichEditor::make('description')
                    ->label(static::translateForm("description"))
                    ->placeholder(static::translateFormPlaceholder("description"))
                    ->columnSpan(12)

            ])->columns([
                    'default' => 12,
                    'sm' => 12,
                    'md' => 12,
                    'lg' => 12,
                    'xl' => 12,
                    '2xl' => 12,
                ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function (Builder $query) {
                $query->where('user_id', auth()->id());
            })
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(static::translateColumnLabel("name"))
                    ->wrap()
                    ->searchable()
                    ->description(fn(GeneralizedTransition $record): string => strip_tags($record->description) ?? 'Sem descrição'),
                Tables\Columns\TextColumn::make('input')
                    ->label(static::translateColumnLabel("input"))
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->sortable()
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        '0' => 'Saída',
                        '1' => 'Entrada',
                    })
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        '0' => 'danger',
                        '1' => 'success',
                    }),
                Tables\Columns\TextColumn::make('fix')
                    ->label(static::translateColumnLabel("fix"))
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->sortable()
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        '0' => 'Não',
                        '1' => 'Sim',
                    })
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        '0' => 'danger',
                        '1' => 'success',
                    }),
                Tables\Columns\TextColumn::make('type')
                    ->label(static::translateColumnLabel("type"))
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->sortable()
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'v' => 'À vista',
                        'p' => 'Parcelado',
                    })
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'v' => 'gray',
                        'p' => 'info',
                    }),
                Tables\Columns\TextColumn::make('total_value')
                    ->label(static::translateColumnLabel("total_value"))
                    ->numeric(decimalPlaces: 2, locale: 'pt')
                    ->money('BRL')
                    ->summarize(Tables\Columns\Summarizers\Sum::make()
                        ->query(fn(QueryBuilder $query) => $query->where('type', 'v'))
                        ->money('BRL'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('installment_value')
                    ->label(static::translateColumnLabel("installment_value"))
                    ->numeric(decimalPlaces: 2, locale: 'pt')
                    ->money('BRL')
                    ->summarize(Tables\Columns\Summarizers\Sum::make()->money('BRL'))
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
                Tables\Columns\TextColumn::make('tags')
                    ->badge()
                    ->separator(',')
                    ->label(static::translateColumnLabel("tags"))
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('start_date')
                    ->label(static::translateColumnLabel("start_date"))
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
            ])
            ->filters([
                Tables\Filters\Filter::make('date')->form([
                    Forms\Components\DatePicker::make('start_date')
                        ->label(static::translateColumnLabel("start_date")),
                ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['start_date'],
                                fn(Builder $query, $date): Builder => $query->whereDate('start_date', '>=', $date),
                            );
                    }),
                Tables\Filters\SelectFilter::make('input')
                    ->label(static::translateColumnLabel("input"))
                    ->options([
                        '0' => 'Saída',
                        '1' => 'Entrada',
                    ]),
                Tables\Filters\SelectFilter::make('fix')
                    ->label(static::translateColumnLabel("fix"))
                    ->options([
                        '0' => 'Não',
                        '1' => 'Sim',
                    ]),
                Tables\Filters\SelectFilter::make('type')
                    ->label(static::translateColumnLabel("type"))
                    ->options([
                        'v' => 'À vista',
                        'p' => 'Parcelado',
                    ]),
                Tables\Filters\SelectFilter::make('tags')
                    ->label(static::translateForm("tags"))
                    ->placeholder(static::translateFormPlaceholder("tags"))
                    ->options(function () {
                        $data = [];
                        foreach (GeneralizedTransition::all()->pluck('tags') as $item) {
                            if (is_array($item)) {
                                foreach ($item as $value) {
                                    $data[$value] = $value;
                                }
                            }
                        }
                        return array_unique($data);
                    })
                    ->query(function (Builder $query, array $data): Builder {
                        return !$data['value'] ? $query : $query->whereJsonContains('tags', $data['value']);

                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    ExportBulkAction::make(),
                    BulkAction::make('exportJson')
                        ->label('Exportar JSON')
                        ->icon('heroicon-o-arrow-down-tray')
                        ->action(function (Collection $records) {
                            $data = $records->map(function ($record) {
                                return [
                                    'name' => $record->name,
                                    'input' => $record->input,
                                    'type' => $record->type,
                                    'total_value' => $record->total_value,
                                    'installment_value' => $record->installment_value,
                                    'installment_amount' => $record->installment_amount,
                                    'start_date' => $record->start_date,
                                    'end_date' => $record->end_date,
                                    'fix' => $record->fix,
                                    'tags' => $record->tags,
                                    'description' => $record->description,
                                ];
                            })->toArray();

                            $filename = 'transacoes_' . now()->format('Y-m-d_His') . '.json';
                            $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
                            
                            return response()->streamDownload(function () use ($json) {
                                echo $json;
                            }, $filename, [
                                'Content-Type' => 'application/json',
                            ]);
                        })
                        ->deselectRecordsAfterCompletion(),
                ]),
            ])
            ->headerActions([
                Tables\Actions\Action::make('importJson')
                    ->label('Importar JSON')
                    ->icon('heroicon-o-arrow-up-tray')
                    ->form([
                        FileUpload::make('file')
                            ->label('Arquivo JSON')
                            ->acceptedFileTypes(['application/json', 'text/plain'])
                            ->required()
                            ->maxSize(10240)
                            ->disk('local'),
                    ])
                    ->action(function (array $data) {
                        try {
                            $filePath = $data['file'];
                            $file = Storage::disk('local')->get($filePath);
                            $records = json_decode($file, true);

                            if (json_last_error() !== JSON_ERROR_NONE) {
                                Notification::make()
                                    ->title('Erro')
                                    ->danger()
                                    ->body('Arquivo JSON inválido: ' . json_last_error_msg())
                                    ->send();
                                Storage::disk('local')->delete($filePath);
                                return;
                            }

                            if (!is_array($records)) {
                                Notification::make()
                                    ->title('Erro')
                                    ->danger()
                                    ->body('O arquivo JSON deve conter um array de registros.')
                                    ->send();
                                Storage::disk('local')->delete($filePath);
                                return;
                            }
                        } catch (\Exception $e) {
                            Notification::make()
                                ->title('Erro ao ler arquivo')
                                ->danger()
                                ->body($e->getMessage())
                                ->send();
                            return;
                        }

                        $imported = 0;
                        $errors = 0;
                        $errorMessages = [];

                        foreach ($records as $index => $record) {
                            try {
                                // Converter datas do formato ISO para Y-m-d
                                $startDate = null;
                                if (isset($record['start_date']) && $record['start_date']) {
                                    $startDate = Carbon::parse($record['start_date'])->format('Y-m-d');
                                }
                                
                                $endDate = null;
                                if (isset($record['end_date']) && $record['end_date']) {
                                    $endDate = Carbon::parse($record['end_date'])->format('Y-m-d');
                                }

                                GeneralizedTransition::create([
                                    'user_id' => auth()->id(),
                                    'name' => $record['name'] ?? null,
                                    'input' => $record['input'] ?? null,
                                    'type' => $record['type'] ?? null,
                                    'total_value' => $record['total_value'] ?? null,
                                    'installment_value' => $record['installment_value'] ?? null,
                                    'installment_amount' => $record['installment_amount'] ?? null,
                                    'start_date' => $startDate,
                                    'end_date' => $endDate,
                                    'fix' => $record['fix'] ?? 0,
                                    'tags' => $record['tags'] ?? null,
                                    'description' => $record['description'] ?? null,
                                ]);
                                $imported++;
                            } catch (\Exception $e) {
                                $errors++;
                                $errorMessages[] = "Linha " . ($index + 1) . ": " . $e->getMessage();
                            }
                        }

                        // Limpar arquivo temporário
                        Storage::disk('local')->delete($data['file']);

                        $message = "$imported registro(s) importado(s) com sucesso.";
                        if ($errors > 0) {
                            $message .= " $errors erro(s).";
                            if (count($errorMessages) > 0) {
                                $message .= " Detalhes: " . implode('; ', array_slice($errorMessages, 0, 3));
                            }
                        }

                        $notification = Notification::make()
                            ->title('Importação concluída')
                            ->body($message);

                        if ($errors === 0) {
                            $notification->success();
                        } elseif ($imported > 0) {
                            $notification->warning();
                        } else {
                            $notification->danger();
                        }

                        $notification->send();
                    }),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListGeneralizedTransitions::route('/'),
            'create' => Pages\CreateGeneralizedTransition::route('/create'),
            'edit' => Pages\EditGeneralizedTransition::route('/{record}/edit'),
        ];
    }
}
