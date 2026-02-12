<?php

namespace App\Http\Controllers\Player;

use App\Http\Controllers\Controller;
use App\Models\FinancialGoal;
use App\Services\XpService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class FloorMapController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $floors = FinancialGoal::where('user_id', $user->id)
            ->orderBy('floor_number')
            ->get()
            ->map(fn($g) => [
                'id' => $g->id,
                'name' => $g->name,
                'target_amount' => (float) $g->target_amount,
                'current_amount' => (float) $g->current_amount,
                'floor_number' => $g->floor_number,
                'icon' => $g->icon,
                'status' => $g->status,
                'progress' => $g->getProgressPercentage(),
            ]);

        return Inertia::render('FloorMap', [
            'floors' => $floors,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'target_amount' => 'required|numeric|min:1',
            'icon' => 'nullable|string|max:10',
        ]);

        $user = auth()->user();
        $maxFloor = FinancialGoal::where('user_id', $user->id)->max('floor_number') ?? 0;

        FinancialGoal::create([
            'user_id' => $user->id,
            'name' => $validated['name'],
            'target_amount' => $validated['target_amount'],
            'current_amount' => 0,
            'floor_number' => $maxFloor + 1,
            'icon' => $validated['icon'] ?? '🏰',
            'status' => 'active',
        ]);

        return redirect()->back()->with('success', 'Novo andar desbloqueado em Aincrad!');
    }

    public function update(Request $request, FinancialGoal $goal)
    {
        if ($goal->user_id !== auth()->id())
            abort(403);

        $validated = $request->validate([
            'current_amount' => 'nullable|numeric|min:0',
            'name' => 'nullable|string|max:255',
            'icon' => 'nullable|string|max:10',
        ]);

        $goal->update($validated);

        // Check if floor is now cleared
        if ($goal->current_amount >= $goal->target_amount && $goal->status !== 'cleared') {
            $goal->update(['status' => 'cleared']);
            XpService::awardXp(auth()->user(), XpService::XP_FLOOR_CLEARED, 'floor_cleared');

            return redirect()->back()->with('success', '🏆 FLOOR CLEARED! +' . XpService::XP_FLOOR_CLEARED . ' XP!');
        }

        return redirect()->back()->with('success', 'Progresso atualizado!');
    }

    public function destroy(FinancialGoal $goal)
    {
        if ($goal->user_id !== auth()->id())
            abort(403);

        $goal->delete();

        return redirect()->back()->with('success', 'Andar removido.');
    }
}
