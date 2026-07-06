<?php

namespace App\Services;

use App\Models\User;
use Carbon\CarbonInterface;
use Laravel\Cashier\Subscription;

class SubscriptionService
{
    /**
     * Concede uma assinatura cortesia (sem cobrança via Stripe), ex.: staff/testes.
     *
     * @param  CarbonInterface|null  $endsAt  Validade; null = sem prazo definido.
     */
    public static function grantComplimentary(User $user, ?CarbonInterface $endsAt = null): Subscription
    {
        // Garante leitura fresca: o Cashier cacheia a relação subscriptions na instância
        $user->unsetRelation('subscriptions');

        if ($user->subscribed('default')) {
            throw new \RuntimeException("{$user->getDisplayName()} já possui uma assinatura ativa.");
        }

        $subscription = $user->subscriptions()->create([
            'type' => 'default',
            'stripe_id' => 'sub_comp_' . uniqid(),
            'stripe_status' => 'active',
            'stripe_price' => 'comp',
            'quantity' => 1,
            'trial_ends_at' => null,
            'ends_at' => $endsAt,
        ]);

        $user->unsetRelation('subscriptions');

        return $subscription;
    }

    /**
     * Assinatura local (cortesia ou simulação de sandbox), sem contraparte real no Stripe.
     */
    public static function isLocal(Subscription $subscription): bool
    {
        return str_starts_with($subscription->stripe_id, 'sub_mock_')
            || str_starts_with($subscription->stripe_id, 'sub_comp_')
            || empty(config('cashier.secret'));
    }
}
