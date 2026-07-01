<?php

namespace App\Http\Controllers\Player;

use App\Http\Controllers\Controller;
use App\Models\InventoryItem;
use App\Services\CoinService;
use App\Services\XpService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BlacksmithController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $items = InventoryItem::where('user_id', $user->id)
            ->whereIn('slot', ['weapon', 'armor'])
            ->get()
            ->map(fn($item) => [
                'id' => $item->id,
                'name' => $item->name,
                'refined_name' => $item->refined_name,
                'slot' => $item->slot,
                'slot_label' => $item->getSlotLabel(),
                'rarity' => $item->rarity,
                'rarity_color' => $item->getRarityColor(),
                'value' => (float) $item->value,
                'icon' => $item->icon,
                'image_url' => $item->image_url,
                'description' => $item->description,
                'equipped' => (bool) $item->equipped,
                'refinement_level' => (int) $item->refinement_level,
            ]);

        return Inertia::render('Blacksmith', [
            'items' => $items,
            'col' => (int) $user->col,
        ]);
    }

    public function refine(Request $request, InventoryItem $item)
    {
        $user = auth()->user();

        if ($item->user_id !== $user->id) {
            abort(403);
        }

        if (!in_array($item->slot, ['weapon', 'armor'])) {
            return redirect()->back()->with('error', 'Apenas armas e armaduras podem ser refinadas!');
        }

        if ($item->refinement_level >= 10) {
            return redirect()->back()->with('error', 'Este item já atingiu o nível máximo (+10)!');
        }

        // Base cost by rarity
        $baseCosts = [
            'common' => 30,
            'uncommon' => 60,
            'rare' => 120,
            'epic' => 250,
            'legendary' => 500,
        ];
        $baseCost = $baseCosts[$item->rarity] ?? 30;
        $cost = $baseCost * ($item->refinement_level + 1);

        if ($user->col < $cost) {
            return redirect()->back()->with('error', 'Col insuficiente! Você precisa de ' . $cost . ' Col, mas só tem ' . $user->col . '.');
        }

        // Rates
        $rates = [
            0 => 100, // +0 -> +1: 100%
            1 => 100, // +1 -> +2: 100%
            2 => 100, // +2 -> +3: 100%
            3 => 85,  // +3 -> +4: 85%
            4 => 70,  // +4 -> +5: 70%
            5 => 55,  // +5 -> +6: 55%
            6 => 40,  // +6 -> +7: 40%
            7 => 25,  // +7 -> +8: 25%
            8 => 15,  // +8 -> +9: 15%
            9 => 8,   // +9 -> +10: 8%
        ];
        $successRate = $rates[$item->refinement_level] ?? 100;
        $roll = rand(1, 100);
        $success = $roll <= $successRate;

        // Spend Col
        CoinService::spendCol(
            $user,
            $cost,
            'purchase',
            'Refino: ' . $item->name . ' para +' . ($item->refinement_level + 1)
        );

        if ($success) {
            $item->increment('refinement_level');
            $xpBonus = 30 * ($item->refinement_level);
            XpService::awardXp($user, $xpBonus, 'item_added');

            return redirect()->back()->with('success', 'SUCCESS! ' . $item->name . ' aprimorado com sucesso para +' . $item->refinement_level . '! +' . $xpBonus . ' XP');
        } else {
            $oldLevel = $item->refinement_level;
            if ($item->refinement_level >= 4) {
                $item->decrement('refinement_level');
                return redirect()->back()->with('warning', 'FAILED! O refinamento de ' . $item->name . ' falhou e o nível decaiu de +' . $oldLevel . ' para +' . $item->refinement_level . '!');
            } else {
                return redirect()->back()->with('warning', 'FAILED! O refinamento de ' . $item->name . ' falhou, mas o item não sofreu perdas e manteve o nível +' . $oldLevel . '.');
            }
        }
    }
}
