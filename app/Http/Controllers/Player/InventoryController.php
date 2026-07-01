<?php

namespace App\Http\Controllers\Player;

use App\Http\Controllers\Controller;
use App\Models\InventoryItem;
use App\Services\XpService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class InventoryController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        $query = InventoryItem::where('user_id', $user->id);

        if ($request->filled('slot')) {
            $query->where('slot', $request->input('slot'));
        }

        $items = $query->orderByRaw("
            CASE rarity
                WHEN 'legendary' THEN 1
                WHEN 'epic' THEN 2
                WHEN 'rare' THEN 3
                WHEN 'uncommon' THEN 4
                WHEN 'common' THEN 5
                ELSE 6
            END
        ")
            ->orderBy('equipped', 'desc')
            ->get()
            ->map(fn($item) => [
                'id' => $item->id,
                'name' => $item->name,
                'slot' => $item->slot,
                'slot_label' => $item->getSlotLabel(),
                'rarity' => $item->rarity,
                'rarity_color' => $item->getRarityColor(),
                'value' => (float) $item->value,
                'icon' => $item->icon,
                'image_url' => $item->image_url,
                'description' => $item->description,
                'attributes' => $item->attributes,
                'equipped' => $item->equipped,
            ]);

        $totalValue = $items->sum('value');

        return Inertia::render('Inventory', [
            'items' => $items,
            'total_value' => round($totalValue, 2),
            'filters' => $request->only(['slot']),
            'slot_options' => InventoryItem::SLOT_LABELS,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slot' => 'required|in:weapon,armor,accessory,consumable,material',
            'rarity' => 'required|in:common,uncommon,rare,epic,legendary',
            'value' => 'required|numeric|min:0',
            'icon' => 'nullable|string|max:10',
            'description' => 'nullable|string|max:500',
            'attributes' => 'nullable|array',
        ]);

        $validated['user_id'] = auth()->id();
        $validated['equipped'] = true;
        $validated['icon'] = $validated['icon'] ?: InventoryItem::SLOT_ICONS[$validated['slot']] ?? '📦';

        InventoryItem::create($validated);
        XpService::awardXp(auth()->user(), XpService::XP_REGISTER_TRADE, 'item_added');

        return redirect()->back()->with('success', 'Item adquirido! +' . XpService::XP_REGISTER_TRADE . ' XP');
    }

    public function update(Request $request, InventoryItem $item)
    {
        if ($item->user_id !== auth()->id())
            abort(403);

        $validated = $request->validate([
            'name' => 'nullable|string|max:255',
            'value' => 'nullable|numeric|min:0',
            'description' => 'nullable|string|max:500',
            'equipped' => 'nullable|boolean',
            'attributes' => 'nullable|array',
        ]);

        if (isset($validated['equipped']) && $validated['equipped']) {
            if ($item->slot === 'accessory') {
                if (str_contains($item->name, 'Título:')) {
                    InventoryItem::where('user_id', auth()->id())
                        ->where('slot', 'accessory')
                        ->where('name', 'like', 'Título:%')
                        ->where('id', '!=', $item->id)
                        ->update(['equipped' => false]);
                } elseif (str_contains($item->name, 'Avatar')) {
                    InventoryItem::where('user_id', auth()->id())
                        ->where('slot', 'accessory')
                        ->where('name', 'like', '%Avatar%')
                        ->where('id', '!=', $item->id)
                        ->update(['equipped' => false]);
                }
            }
        }

        $item->update(array_filter($validated, fn($v) => $v !== null));

        return redirect()->back()->with('success', 'Item atualizado!');
    }

    public function consume(Request $request, InventoryItem $item)
    {
        if ($item->user_id !== auth()->id())
            abort(403);

        if ($item->slot !== 'consumable') {
            return redirect()->back()->with('error', 'Este item não pode ser consumido.');
        }

        $user = auth()->user();

        if (str_contains($item->name, 'XP Boost')) {
            XpService::awardXp($user, 150, 'potion_use');
            $item->delete();
            return redirect()->back()->with('success', '🧪 Potion consumida: +150 XP!');
        }

        if (str_contains($item->name, 'Health Crystal')) {
            \App\Services\CoinService::awardCol($user, 50, 'mission_reward', 'Health Crystal Use');
            $item->delete();
            return redirect()->back()->with('success', '💎 Health Crystal consumido: +50 Col recuperados!');
        }

        $item->delete();
        return redirect()->back()->with('success', 'Item consumido.');
    }

    public function destroy(InventoryItem $item)
    {
        if ($item->user_id !== auth()->id())
            abort(403);

        $item->delete();

        return redirect()->back()->with('success', 'Item descartado.');
    }
}
