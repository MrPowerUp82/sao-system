<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SubscriptionResource\Pages;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Laravel\Cashier\Subscription;

class SubscriptionResource extends Resource
{
    protected static ?string $model = Subscription::class;

    protected static ?string $navigationIcon = 'heroicon-o-receipt-percent';

    protected static ?string $navigationLabel = 'Assinaturas';

    protected static ?string $modelLabel = 'Assinatura';

    protected static ?string $pluralModelLabel = 'Assinaturas';

    public static function getNavigationBadge(): ?string
    {
        return (string) static::activeQuery()->count();
    }

    public static function getNavigationBadgeTooltip(): ?string
    {
        return 'Assinaturas ativas';
    }

    protected static function activeQuery()
    {
        return Subscription::query()
            ->whereIn('stripe_status', ['active', 'trialing'])
            ->where(function ($query) {
                $query->whereNull('ends_at')->orWhere('ends_at', '>', now());
            });
    }

    /**
     * Assinaturas sem contraparte real no Stripe (simulação ou cortesia).
     */
    protected static function isMock(Subscription $subscription): bool
    {
        return \App\Services\SubscriptionService::isLocal($subscription);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Jogador')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.email')
                    ->label('E-mail')
                    ->searchable(),
                Tables\Columns\TextColumn::make('stripe_status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'active', 'trialing' => 'success',
                        'past_due', 'incomplete', 'incomplete_expired', 'unpaid' => 'warning',
                        'canceled' => 'danger',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('origin')
                    ->label('Origem')
                    ->badge()
                    ->state(fn (Subscription $record): string => match (true) {
                        str_starts_with($record->stripe_id, 'sub_comp_') => 'Cortesia',
                        str_starts_with($record->stripe_id, 'sub_mock_') => 'Simulada',
                        default => 'Stripe',
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'Cortesia' => 'info',
                        'Simulada' => 'warning',
                        default => 'success',
                    }),
                Tables\Columns\TextColumn::make('stripe_price')
                    ->label('Price ID')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Assinou Em')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
                Tables\Columns\TextColumn::make('ends_at')
                    ->label('Encerra Em')
                    ->dateTime('d/m/Y H:i')
                    ->placeholder('—')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('stripe_status')
                    ->label('Status')
                    ->options([
                        'active' => 'Ativa',
                        'trialing' => 'Em Teste',
                        'past_due' => 'Pagamento Atrasado',
                        'canceled' => 'Cancelada',
                        'unpaid' => 'Não Paga',
                    ]),
            ])
            ->actions([
                Tables\Actions\Action::make('cancel')
                    ->label('Cancelar')
                    ->icon('heroicon-o-x-circle')
                    ->color('warning')
                    ->requiresConfirmation()
                    ->modalHeading('Cancelar assinatura')
                    ->modalDescription('O jogador mantém o acesso até o fim do período já pago. No Stripe, a cobrança recorrente é interrompida.')
                    ->visible(fn (Subscription $record): bool => $record->active() && ! $record->onGracePeriod())
                    ->action(function (Subscription $record): void {
                        if (static::isMock($record)) {
                            $record->update(['ends_at' => now()->addMonth()]);
                        } else {
                            $record->cancel();
                        }

                        Notification::make()
                            ->title('Assinatura cancelada')
                            ->body('O acesso permanece até o fim do período vigente.')
                            ->success()
                            ->send();
                    }),
                Tables\Actions\Action::make('cancelNow')
                    ->label('Cancelar Agora')
                    ->icon('heroicon-o-no-symbol')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalHeading('Cancelar imediatamente')
                    ->modalDescription('O jogador perde o acesso na hora, sem reembolso automático. Reembolsos devem ser feitos no dashboard do Stripe.')
                    ->visible(fn (Subscription $record): bool => ! $record->canceled())
                    ->action(function (Subscription $record): void {
                        if (static::isMock($record)) {
                            $record->update(['stripe_status' => 'canceled', 'ends_at' => now()]);
                        } else {
                            $record->cancelNow();
                        }

                        Notification::make()
                            ->title('Assinatura encerrada imediatamente')
                            ->success()
                            ->send();
                    }),
                Tables\Actions\Action::make('resume')
                    ->label('Retomar')
                    ->icon('heroicon-o-arrow-path')
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible(fn (Subscription $record): bool => $record->onGracePeriod())
                    ->action(function (Subscription $record): void {
                        if (static::isMock($record)) {
                            $record->update(['ends_at' => null]);
                        } else {
                            $record->resume();
                        }

                        Notification::make()
                            ->title('Assinatura retomada')
                            ->success()
                            ->send();
                    }),
                Tables\Actions\Action::make('stripe')
                    ->label('Ver no Stripe')
                    ->icon('heroicon-o-arrow-top-right-on-square')
                    ->url(fn (Subscription $record): string => 'https://dashboard.stripe.com/subscriptions/' . $record->stripe_id, shouldOpenInNewTab: true)
                    ->visible(fn (Subscription $record): bool => ! str_starts_with($record->stripe_id, 'sub_mock_')
                        && ! str_starts_with($record->stripe_id, 'sub_comp_')),
            ])
            ->bulkActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSubscriptions::route('/'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }
}
