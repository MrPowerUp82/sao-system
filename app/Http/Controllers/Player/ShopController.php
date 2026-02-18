<?php

namespace App\Http\Controllers\Player;

use App\Http\Controllers\Controller;
use App\Models\ShopItem;
use App\Services\CoinService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        $query = ShopItem::where('is_active', true);

        if ($request->filled('category')) {
            $query->where('category', $request->input('category'));
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
            ->orderBy('price')
            ->get()
            ->map(fn($item) => [
                'id' => $item->id,
                'name' => $item->name,
                'description' => $item->description,
                'category' => $item->category,
                'category_label' => $item->getCategoryLabel(),
                'price' => $item->price,
                'icon' => $item->icon,
                'image_url' => $item->image_url,
                'rarity' => $item->rarity,
                'rarity_color' => $item->getRarityColor(),
                'stock' => $item->stock,
                'available' => $item->isAvailable(),
            ]);

        return Inertia::render('Shop', [
            'items' => $items,
            'player_col' => $user->col ?? 0,
            'filters' => $request->only(['category']),
            'category_options' => ShopItem::CATEGORY_LABELS,
        ]);
    }

    public function purchase(Request $request, ShopItem $shopItem)
    {
        $user = auth()->user();

        try {
            CoinService::purchaseItem($user, $shopItem);
            return redirect()->back()->with('success', '✅ Item comprado: ' . $shopItem->name . '! (-' . $shopItem->price . ' Col)');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
