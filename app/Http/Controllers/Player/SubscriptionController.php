<?php

namespace App\Http\Controllers\Player;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

use App\Models\Plan;

class SubscriptionController extends Controller
{
    /**
     * Exibe a página de pricing/planos.
     */
    public function pricing(Request $request)
    {
        $user = $request->user();

        // Se já for inscrito (ou se for Kirito), redireciona direto para o HUD
        if ($user->email === 'kirito@sao.test' || $user->hasRole('super_admin') || $user->subscribed('default') || $user->subscribed('pro')) {
            return redirect()->route('player.dashboard');
        }

        // Busca o plano ativo do banco
        $plan = Plan::where('is_active', true)->first();

        return Inertia::render('Auth/Subscription/Pricing', [
            'stripeKey' => config('cashier.key'),
            'envMode' => $this->stripeConfigured() ? 'stripe_live' : 'sandbox_mock',
            'plan' => $plan ? [
                'name' => $plan->name,
                'price' => $plan->price,
                'features' => $plan->features,
            ] : null,
        ]);
    }

    /**
     * Inicia o fluxo de checkout da assinatura.
     */
    public function checkout(Request $request)
    {
        $user = $request->user();

        // Carrega o plano ativo do banco de dados
        $plan = Plan::where('is_active', true)->first();
        $priceId = $plan ? $plan->stripe_price_id : config('services.stripe.price_id');

        if (! $this->stripeConfigured()) {
            // Em produção, nunca conceder assinatura simulada: é falha de configuração
            if (app()->environment('production')) {
                report(new \RuntimeException('Stripe não configurado (STRIPE_SECRET ausente) com aplicação em produção.'));

                return redirect()->route('player.subscription.pricing')
                    ->with('error', 'O sistema de pagamentos está temporariamente indisponível. Tente novamente em instantes.');
            }

            // FALLBACK DE DESENVOLVIMENTO: sem chaves do Stripe, simulamos o sucesso local
            $user->subscriptions()->create([
                'type' => 'default',
                'stripe_id' => 'sub_mock_' . uniqid(),
                'stripe_status' => 'active',
                'stripe_price' => $priceId,
                'quantity' => 1,
                'trial_ends_at' => null,
                'ends_at' => null,
            ]);

            return redirect()->route('player.subscription.success')
                ->with('success', 'Assinatura simulada com sucesso (Modo de Sandbox Ativo)!');
        }

        // Fluxo real via Stripe Checkout
        return $user->newSubscription('default', $priceId)
            ->checkout([
                'success_url' => route('player.subscription.success') . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('player.subscription.pricing'),
            ]);
    }

    /**
     * Tela de sucesso pós-checkout.
     */
    public function success(Request $request)
    {
        return Inertia::render('Auth/Subscription/Success', [
            'successMessage' => 'Link Start! Sua assinatura de Aincrad foi ativada com sucesso. Divirta-se!'
        ]);
    }

    /**
     * Redireciona o usuário para o Stripe Customer Portal para gerenciar a assinatura.
     */
    public function portal(Request $request)
    {
        $user = $request->user();

        // Se for assinatura simulada
        if (! $this->stripeConfigured()) {
            return back()->with('error', 'O portal financeiro do Stripe não está disponível no modo de simulação Sandbox.');
        }

        return $user->redirectToBillingPortal(
            route('player.dashboard')
        );
    }

    /**
     * Indica se as credenciais do Stripe estão configuradas.
     */
    protected function stripeConfigured(): bool
    {
        return ! empty(config('cashier.secret'));
    }
}
