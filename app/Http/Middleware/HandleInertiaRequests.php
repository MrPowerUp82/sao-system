<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    protected $rootView = 'player';

    public function share(Request $request): array
    {
        return array_merge(parent::share($request), [
            'auth' => [
                'user' => $request->user() ? [
                    'id' => $request->user()->id,
                    'name' => $request->user()->name,
                    'email' => $request->user()->email,
                    'player_name' => $request->user()->player_name ?? $request->user()->name,
                    'level' => $request->user()->level ?? 1,
                    'xp' => $request->user()->xp ?? 0,
                    'avatar_url' => $request->user()->avatar_url,
                    'col' => $request->user()->col ?? 0,
                    'equipped_title' => $request->user()->equipped_title,
                    'equipped_avatar' => $request->user()->equipped_avatar,
                    'subscribed' => $request->user()->email === 'kirito@sao.test' || $request->user()->hasRole('super_admin') || ($request->user()->subscribed('default') || $request->user()->subscribed('pro')),
                ] : null,
            ],
            'flash' => [
                'success' => fn() => $request->session()->get('success'),
                'error' => fn() => $request->session()->get('error'),
            ],
        ]);
    }
}
