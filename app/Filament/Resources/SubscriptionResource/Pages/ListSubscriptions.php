<?php

namespace App\Filament\Resources\SubscriptionResource\Pages;

use App\Filament\Resources\SubscriptionResource;
use App\Models\User;
use App\Services\SubscriptionService;
use Filament\Actions;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Carbon;

class ListSubscriptions extends ListRecords
{
    protected static string $resource = SubscriptionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('grant')
                ->label('Conceder Assinatura')
                ->icon('heroicon-o-gift')
                ->color('info')
                ->modalHeading('Conceder assinatura cortesia')
                ->modalDescription('Libera o acesso completo sem cobrança — ideal para staff e testes. Não cria nada no Stripe.')
                ->form([
                    Forms\Components\Select::make('user_id')
                        ->label('Jogador')
                        ->options(
                            User::query()
                                ->whereDoesntHave('subscriptions', function ($query) {
                                    $query->whereIn('stripe_status', ['active', 'trialing'])
                                        ->where(function ($q) {
                                            $q->whereNull('ends_at')->orWhere('ends_at', '>', now());
                                        });
                                })
                                ->orderBy('name')
                                ->pluck('name', 'id')
                        )
                        ->searchable()
                        ->required()
                        ->helperText('Apenas jogadores sem assinatura ativa aparecem na lista.'),
                    Forms\Components\DatePicker::make('ends_at')
                        ->label('Válida até (opcional)')
                        ->minDate(now()->addDay())
                        ->helperText('Deixe em branco para acesso sem prazo. Com data, o acesso expira automaticamente.'),
                ])
                ->action(function (array $data): void {
                    $user = User::findOrFail($data['user_id']);

                    try {
                        SubscriptionService::grantComplimentary(
                            $user,
                            filled($data['ends_at']) ? Carbon::parse($data['ends_at'])->endOfDay() : null
                        );

                        Notification::make()
                            ->title('Assinatura cortesia concedida')
                            ->body($user->getDisplayName() . ' agora tem acesso completo ao sistema.')
                            ->success()
                            ->send();
                    } catch (\RuntimeException $e) {
                        Notification::make()
                            ->title('Não foi possível conceder')
                            ->body($e->getMessage())
                            ->danger()
                            ->send();
                    }
                }),
        ];
    }
}
