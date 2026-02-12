<?php

namespace App\Http\Controllers\Player;

use App\Http\Controllers\Controller;
use App\Models\GeneralizedTransition;
use App\Services\XpService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TradeLogController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        $query = GeneralizedTransition::where('user_id', $user->id)
            ->orderBy('start_date', 'desc')
            ->orderBy('created_at', 'desc');

        if ($request->filled('input')) {
            $query->where('input', $request->input('input'));
        }
        if ($request->filled('type')) {
            $query->where('type', $request->input('type'));
        }
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->input('search') . '%');
        }

        $trades = $query->paginate(20)->through(fn($t) => [
            'id' => $t->id,
            'name' => $t->name,
            'input' => (int) $t->input,
            'type' => $t->type,
            'total_value' => (float) $t->total_value,
            'installment_value' => $t->installment_value ? (float) $t->installment_value : null,
            'installment_amount' => $t->installment_amount ? (int) $t->installment_amount : null,
            'fix' => (int) $t->fix,
            'start_date' => $t->start_date?->format('Y-m-d'),
            'end_date' => $t->end_date?->format('Y-m-d'),
            'tags' => $t->tags,
            'description' => strip_tags($t->description ?? ''),
            'created_at' => $t->created_at->format('d/m/Y'),
        ]);

        return Inertia::render('TradeLog', [
            'trades' => $trades,
            'filters' => $request->only(['input', 'type', 'search']),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'input' => 'required|in:0,1',
            'type' => 'required|in:v,p',
            'total_value' => 'required|numeric|min:0',
            'installment_value' => 'nullable|numeric|min:0',
            'installment_amount' => 'nullable|integer|min:1',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'fix' => 'boolean',
            'tags' => 'nullable|array',
            'description' => 'nullable|string',
        ]);

        $validated['user_id'] = auth()->id();
        $validated['fix'] = $validated['fix'] ?? 0;

        GeneralizedTransition::create($validated);

        // Award XP
        $xpAmount = XpService::XP_REGISTER_TRADE;
        if ($validated['fix']) {
            $xpAmount += XpService::XP_FIXED_PAYMENT;
        }
        XpService::awardXp(auth()->user(), $xpAmount, 'trade_registered');

        return redirect()->back()->with('success', 'Trade registrado com sucesso! +' . $xpAmount . ' XP');
    }

    public function update(Request $request, GeneralizedTransition $trade)
    {
        if ($trade->user_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'input' => 'required|in:0,1',
            'type' => 'required|in:v,p',
            'total_value' => 'required|numeric|min:0',
            'installment_value' => 'nullable|numeric|min:0',
            'installment_amount' => 'nullable|integer|min:1',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'fix' => 'boolean',
            'tags' => 'nullable|array',
            'description' => 'nullable|string',
        ]);

        $trade->update($validated);

        return redirect()->back()->with('success', 'Trade atualizado!');
    }

    public function destroy(GeneralizedTransition $trade)
    {
        if ($trade->user_id !== auth()->id()) {
            abort(403);
        }

        $trade->delete();

        return redirect()->back()->with('success', 'Trade removido.');
    }
}
