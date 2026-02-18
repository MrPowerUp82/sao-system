<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CoinTransactionResource\Pages;
use App\Models\CoinTransaction;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class CoinTransactionResource extends Resource
{
    protected static ?string $model = CoinTransaction::class;
    protected static ?string $navigationIcon = 'heroicon-o-banknotes';
    protected static ?string $navigationGroup = '💰 Economia';
    protected static ?string $navigationLabel = 'Histórico de Col';
    protected static ?string $modelLabel = 'Transação de Col';
    protected static ?string $pluralModelLabel = 'Transações de Col';
    protected static ?int $navigationSort = 2;

    public static function canCreate(): bool
    {
        return false;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Data')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Jogador')
                    ->searchable(),
                Tables\Columns\TextColumn::make('type')
                    ->label('Tipo')
                    ->badge()
                    ->formatStateUsing(fn(string $state) => match ($state) {
                        'mission_reward' => '🎯 Missão',
                        'purchase' => '🛒 Compra',
                        'admin_grant' => '👑 Admin',
                        'admin_remove' => '⚠️ Admin Remove',
                        'daily_login' => '📅 Daily',
                        default => $state,
                    })
                    ->color(fn(string $state) => match ($state) {
                        'mission_reward' => 'success',
                        'purchase' => 'info',
                        'admin_grant' => 'warning',
                        'admin_remove' => 'danger',
                        'daily_login' => 'primary',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('amount')
                    ->label('Valor')
                    ->prefix('🪙 ')
                    ->formatStateUsing(fn(int $state) => ($state > 0 ? '+' : '') . number_format($state, 0, ',', '.'))
                    ->color(fn(int $state) => $state > 0 ? 'success' : 'danger')
                    ->weight('bold')
                    ->sortable(),
                Tables\Columns\TextColumn::make('description')
                    ->label('Descrição')
                    ->limit(50)
                    ->tooltip(fn($record) => $record->description),
                Tables\Columns\TextColumn::make('reference_id')
                    ->label('Ref. ID')
                    ->placeholder('—')
                    ->alignCenter(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->label('Tipo')
                    ->options([
                        'mission_reward' => '🎯 Missão',
                        'purchase' => '🛒 Compra',
                        'admin_grant' => '👑 Admin Grant',
                        'admin_remove' => '⚠️ Admin Remove',
                        'daily_login' => '📅 Daily Login',
                    ]),
                Tables\Filters\SelectFilter::make('user_id')
                    ->label('Jogador')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload(),
            ])
            ->actions([])
            ->bulkActions([]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCoinTransactions::route('/'),
        ];
    }
}
