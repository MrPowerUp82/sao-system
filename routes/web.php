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

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

use App\Http\Controllers\Player\AuthController;

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'create'])->name('login');
    Route::post('/login', [AuthController::class, 'store']);
    Route::get('/register', [AuthController::class, 'createRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'storeRegister']);
});

Route::post('/logout', [AuthController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

Route::get('/', function () {
    return view('landing');
})->name('landing');

Route::get('/login/email', function (Request $request) {
    if (!$request->query('email')) {
        return response()->json([
            'message' => 'O e-mail não foi informado'
        ], 422);
    }
    $user = User::where('email', $request->query('email'))->first();
    Filament::auth()->loginUsingId($user->id);
    if (Route::has($request->query('route'))) {
        return redirect()->route($request->query('route'));
    }
    return redirect('/admin')->with('success', 'Login realizado com sucesso');
})
    ->middleware(['auth'])
    ->name('force.login.email');

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
        Route::delete('/inventory/{item}', [InventoryController::class, 'destroy'])->name('player.inventory.destroy');
        Route::get('/guild', [GuildController::class, 'index'])->name('player.guild');
        Route::post('/guild', [GuildController::class, 'store'])->name('player.guild.store');
        Route::post('/guild/join', [GuildController::class, 'join'])->name('player.guild.join');
        Route::delete('/guild/{guild}/leave', [GuildController::class, 'leave'])->name('player.guild.leave');
        Route::delete('/guild/{guild}', [GuildController::class, 'destroy'])->name('player.guild.destroy');

        // Y.U.I. Routes
        Route::post('/yui/chat', [YuiController::class, 'sendMessage'])->name('player.yui.chat');
        Route::get('/yui/status', [YuiController::class, 'getStatus'])->name('player.yui.status');
    });