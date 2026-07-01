<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RequireSubscription
{
    /**
     * Intercepta requisições e exige que o jogador possua uma assinatura ativa.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // Se não estiver logado, o middleware 'auth' já trata.
        if (!$user) {
            return $next($request);
        }

        // Kirito é o testador lendário e tem acesso livre
        if ($user->email === 'kirito@sao.test') {
            return $next($request);
        }

        // Se o usuário tem perfil super_admin, ele pode gerenciar o Filament admin livremente
        if ($user->hasRole('super_admin')) {
            return $next($request);
        }

        // Verifica se possui assinatura ativa ('default' ou 'pro')
        $isSubscribed = $user->subscribed('default') || $user->subscribed('pro');

        if (!$isSubscribed) {
            // Se for requisição Inertia ou AJAX, retorna redirect Inertia
            if ($request->expectsJson() || $request->header('X-Inertia')) {
                return response()->json([
                    'message' => 'Assinatura ativa requerida. Link Start!',
                    'redirect' => route('player.subscription.pricing')
                ], 403)->header('X-Inertia-Location', route('player.subscription.pricing'));
            }

            return redirect()->route('player.subscription.pricing');
        }

        return $next($request);
    }
}
