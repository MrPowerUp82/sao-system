<?php

use Illuminate\Support\Facades\Route;
use Filament\Facades\Filament;
use App\Models\User;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/',function () {
    return redirect('admin');
});

Route::get('/landing', function () {
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