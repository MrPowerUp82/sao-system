<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use App\Services\CoinService;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Columns\TextColumn;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Notifications\Notification;
use App\Core\Filament\Traits\HasTranslateResource;

class UserResource extends Resource
{
    use HasTranslateResource;
    protected static ?string $model = User::class;
    protected static ?string $navigationIcon = 'heroicon-o-user';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informações Básicas')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nome')
                            ->required(),
                        Forms\Components\TextInput::make('email')
                            ->label('Email')
                            ->email()
                            ->required(),
                        Forms\Components\TextInput::make('password')
                            ->label('Senha')
                            ->password()
                            ->revealable()
                            ->dehydrateStateUsing(fn($state) => filled($state) ? bcrypt($state) : null)
                            ->dehydrated(fn($state) => filled($state)),
                    ]),
                Forms\Components\Section::make('⚔️ RPG Stats')
                    ->schema([
                        Forms\Components\Grid::make(4)
                            ->schema([
                                Forms\Components\TextInput::make('player_name')
                                    ->label('Player Name'),
                                Forms\Components\TextInput::make('level')
                                    ->label('Level')
                                    ->numeric()
                                    ->disabled(),
                                Forms\Components\TextInput::make('xp')
                                    ->label('XP')
                                    ->numeric()
                                    ->disabled(),
                                Forms\Components\TextInput::make('col')
                                    ->label('🪙 Col')
                                    ->numeric()
                                    ->disabled()
                                    ->helperText('Use as ações na tabela para alterar.'),
                            ]),
                    ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\TextEntry::make('name'),
                Infolists\Components\TextEntry::make('email'),
                Infolists\Components\TextEntry::make('player_name')
                    ->label('Player Name'),
                Infolists\Components\TextEntry::make('level')
                    ->label('Level'),
                Infolists\Components\TextEntry::make('xp')
                    ->label('XP'),
                Infolists\Components\TextEntry::make('col')
                    ->label('🪙 Col'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nome')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('player_name')
                    ->label('Player')
                    ->placeholder('—')
                    ->searchable(),
                Tables\Columns\TextColumn::make('level')
                    ->label('LV')
                    ->sortable()
                    ->alignCenter(),
                Tables\Columns\TextColumn::make('col')
                    ->label('🪙 Col')
                    ->sortable()
                    ->numeric()
                    ->color('warning')
                    ->weight('bold'),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('addCol')
                    ->label('+ Col')
                    ->icon('heroicon-o-plus-circle')
                    ->color('success')
                    ->form([
                        Forms\Components\TextInput::make('amount')
                            ->label('Quantidade de Col')
                            ->numeric()
                            ->required()
                            ->minValue(1)
                            ->prefix('🪙'),
                        Forms\Components\TextInput::make('reason')
                            ->label('Motivo')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Ex: Evento de Natal, Correção...'),
                    ])
                    ->action(function (User $record, array $data): void {
                        CoinService::awardCol(
                            $record,
                            (int) $data['amount'],
                            'admin_grant',
                            $data['reason']
                        );

                        Notification::make()
                            ->title('✅ ' . $data['amount'] . ' Col adicionado a ' . $record->getDisplayName())
                            ->success()
                            ->send();
                    })
                    ->requiresConfirmation()
                    ->modalHeading('Adicionar Col')
                    ->modalDescription(fn(User $record) => 'Saldo atual: 🪙 ' . number_format($record->col, 0, ',', '.'))
                    ->modalSubmitActionLabel('Adicionar'),

                Tables\Actions\Action::make('removeCol')
                    ->label('- Col')
                    ->icon('heroicon-o-minus-circle')
                    ->color('danger')
                    ->form([
                        Forms\Components\TextInput::make('amount')
                            ->label('Quantidade de Col')
                            ->numeric()
                            ->required()
                            ->minValue(1)
                            ->prefix('🪙'),
                        Forms\Components\TextInput::make('reason')
                            ->label('Motivo')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Ex: Penalidade, Estorno...'),
                    ])
                    ->action(function (User $record, array $data): void {
                        try {
                            CoinService::spendCol(
                                $record,
                                (int) $data['amount'],
                                'admin_remove',
                                $data['reason']
                            );

                            Notification::make()
                                ->title('⚠️ ' . $data['amount'] . ' Col removido de ' . $record->getDisplayName())
                                ->warning()
                                ->send();
                        } catch (\Exception $e) {
                            Notification::make()
                                ->title('Erro')
                                ->body($e->getMessage())
                                ->danger()
                                ->send();
                        }
                    })
                    ->requiresConfirmation()
                    ->modalHeading('Remover Col')
                    ->modalDescription(fn(User $record) => 'Saldo atual: 🪙 ' . number_format($record->col, 0, ',', '.'))
                    ->modalSubmitActionLabel('Remover'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'view' => Pages\ViewUser::route('/{record}'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}

