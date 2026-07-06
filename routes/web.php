<?php

use Illuminate\Support\Facades\Route;
use Filament\Facades\Filament;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Player\PlayerDashboardController;
use App\Http\Controllers\Player\TradeLogController;
use App\Http\Controllers\Player\FloorMapController;
use App\Http\Controllers\Player\InventoryController;
use App\Http\Controllers\Player\GuildController;
use App\Http\Controllers\Player\YuiController;
use App\Http\Controllers\Player\ShopController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

use App\Http\Controllers\Player\AuthController;

Route::middleware(['guest', \App\Http\Middleware\HandleInertiaRequests::class])->group(function () {
    Route::get('/login', [AuthController::class, 'create'])->name('login');
    Route::post('/login', [AuthController::class, 'store']);
    Route::get('/register', [AuthController::class, 'createRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'storeRegister']);

    // Redefinição de senha
    Route::get('/forgot-password', [\App\Http\Controllers\Player\PasswordResetController::class, 'requestForm'])->name('password.request');
    Route::post('/forgot-password', [\App\Http\Controllers\Player\PasswordResetController::class, 'sendLink'])->middleware('throttle:6,1')->name('password.email');
    Route::get('/reset-password/{token}', [\App\Http\Controllers\Player\PasswordResetController::class, 'resetForm'])->name('password.reset');
    Route::post('/reset-password', [\App\Http\Controllers\Player\PasswordResetController::class, 'reset'])->middleware('throttle:6,1')->name('password.update');
});

Route::post('/logout', [AuthController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

Route::get('/', function () {
    try {
        $plan = \App\Models\Plan::where('is_active', true)->first();
    } catch (\Throwable $e) {
        $plan = null;
    }
    return view('landing', compact('plan'));
})->name('landing');

Route::get('/admin/profile', function () {
    return redirect(route('filament.admin.resources.users.view', auth()->user()->id));
})->middleware(['auth'])->name('profile');

/*
|--------------------------------------------------------------------------
| Player HUD Routes (Inertia.js + React)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', \App\Http\Middleware\HandleInertiaRequests::class])
    ->prefix('player')
    ->group(function () {
        // Rotas de Faturamento/Planos (Não exigem assinatura ativa, apenas autenticação)
        Route::get('/subscription/pricing', [\App\Http\Controllers\Player\SubscriptionController::class, 'pricing'])->name('player.subscription.pricing');
        Route::post('/subscription/checkout', [\App\Http\Controllers\Player\SubscriptionController::class, 'checkout'])->name('player.subscription.checkout');
        Route::get('/subscription/checkout-success', [\App\Http\Controllers\Player\SubscriptionController::class, 'success'])->name('player.subscription.success');
        Route::post('/subscription/portal', [\App\Http\Controllers\Player\SubscriptionController::class, 'portal'])->name('player.subscription.portal');

        // Rotas de Verificação de E-mail (Exigem apenas autenticação)
        Route::get('/email/verify', [AuthController::class, 'verifyNotice'])->name('verification.notice');
        Route::get('/email/verify/{id}/{hash}', [AuthController::class, 'verifyEmail'])->middleware(['signed', 'throttle:6,1'])->name('verification.verify');
        Route::post('/email/verification-notification', [AuthController::class, 'verifySend'])->middleware('throttle:6,1')->name('verification.send');

        // Rotas Protegidas por Assinatura (Paywall) e E-mail Verificado
        Route::middleware(['subscribed', 'verified'])->group(function () {
            Route::get('/', [PlayerDashboardController::class, 'index'])->name('player.dashboard');
            Route::get('/trade-log', [TradeLogController::class, 'index'])->name('player.trade-log');
            Route::post('/trade', [TradeLogController::class, 'store'])->name('player.trade.store');
            Route::put('/trade/{trade}', [TradeLogController::class, 'update'])->name('player.trade.update');
            Route::delete('/trade/{trade}', [TradeLogController::class, 'destroy'])->name('player.trade.destroy');
            Route::get('/floor-map', [FloorMapController::class, 'index'])->name('player.floor-map');
            Route::post('/floor', [FloorMapController::class, 'store'])->name('player.floor.store');
            Route::put('/floor/{goal}', [FloorMapController::class, 'update'])->name('player.floor.update');
            Route::delete('/floor/{goal}', [FloorMapController::class, 'destroy'])->name('player.floor.destroy');
            Route::get('/inventory', [InventoryController::class, 'index'])->name('player.inventory');
            Route::post('/inventory', [InventoryController::class, 'store'])->name('player.inventory.store');
            Route::put('/inventory/{item}', [InventoryController::class, 'update'])->name('player.inventory.update');
            Route::post('/inventory/{item}/consume', [InventoryController::class, 'consume'])->name('player.inventory.consume');
            Route::delete('/inventory/{item}', [InventoryController::class, 'destroy'])->name('player.inventory.destroy');
            Route::get('/guild', [GuildController::class, 'index'])->name('player.guild');
            Route::post('/guild', [GuildController::class, 'store'])->name('player.guild.store');
            Route::post('/guild/join', [GuildController::class, 'join'])->name('player.guild.join');
            Route::delete('/guild/{guild}/leave', [GuildController::class, 'leave'])->name('player.guild.leave');
            Route::delete('/guild/{guild}', [GuildController::class, 'destroy'])->name('player.guild.destroy');
            Route::get('/guild/{guild}/messages', [GuildController::class, 'messages'])->name('player.guild.messages');
            Route::post('/guild/{guild}/messages', [GuildController::class, 'sendMessage'])->name('player.guild.send_message');

            // Bestiary Routes
            Route::get('/bestiary', [\App\Http\Controllers\Player\BestiaryController::class, 'index'])->name('player.bestiary');

            // Y.U.I. Routes
            Route::post('/yui/chat', [YuiController::class, 'sendMessage'])->name('player.yui.chat');
            Route::get('/yui/status', [YuiController::class, 'getStatus'])->name('player.yui.status');

            // Shop Routes
            Route::get('/shop', [ShopController::class, 'index'])->name('player.shop');
            Route::post('/shop/{shopItem}/purchase', [ShopController::class, 'purchase'])->name('player.shop.purchase');
        });
    });