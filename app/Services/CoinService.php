<?php

namespace App\Services;

use App\Models\CoinTransaction;
use App\Models\ShopItem;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class CoinService
{
    const COL_REGISTER_TRADE = 5;
    const COL_FIXED_PAYMENT = 15;
    const COL_FLOOR_CLEARED = 50;
    const COL_DAILY_LOGIN = 10;

    public static function awardCol(User $user, int $amount, string $type = 'mission_reward', string $description = ''): void
    {
        DB::transaction(function () use ($user, $amount, $type, $description) {
            $user->col += $amount;
            $user->save();

            CoinTransaction::create([
                'user_id' => $user->id,
                'amount' => $amount,
                'type' => $type,
                'description' => $description,
            ]);
        });
    }

    public static function spendCol(User $user, int $amount, string $type = 'purchase', string $description = '', ?int $referenceId = null): void
    {
        if ($user->col < $amount) {
            throw new \Exception('Col insuficiente! Você precisa de ' . $amount . ' Col, mas só tem ' . $user->col . '.');
        }

        DB::transaction(function () use ($user, $amount, $type, $description, $referenceId) {
            $user->col -= $amount;
            $user->save();

            CoinTransaction::create([
                'user_id' => $user->id,
                'amount' => -$amount,
                'type' => $type,
                'description' => $description,
                'reference_id' => $referenceId,
            ]);
        });
    }

    public static function purchaseItem(User $user, ShopItem $item): void
    {
        if (!$item->isAvailable()) {
            throw new \Exception('Este item não está disponível no momento.');
        }

        if ($user->col < $item->price) {
            throw new \Exception('Col insuficiente! Você precisa de ' . $item->price . ' Col, mas só tem ' . $user->col . '.');
        }

        DB::transaction(function () use ($user, $item) {
            self::spendCol(
                $user,
                $item->price,
                'purchase',
                'Comprou: ' . $item->name,
                $item->id
            );

            if ($item->stock !== null) {
                $item->decrement('stock');
            }

            // Create inventory item for the user
            \App\Models\InventoryItem::create([
                'user_id' => $user->id,
                'name' => $item->name,
                'slot' => $item->category === 'consumable' ? 'consumable' : 'accessory',
                'rarity' => $item->rarity,
                'value' => 0.00,
                'icon' => $item->icon ?: '📦',
                'description' => $item->description,
                'attributes' => [
                    'category' => $item->category,
                    'price' => $item->price,
                ],
                'equipped' => false,
            ]);
        });
    }
}
