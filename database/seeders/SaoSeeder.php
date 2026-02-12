<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\GeneralizedTransition;
use App\Models\FinancialGoal;
use App\Services\XpService;
use Carbon\Carbon;

class SaoSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Kirito User
        $user = User::firstOrCreate(
            ['email' => 'kirito@sao.test'],
            [
                'name' => 'Kazuto Kirigaya',
                'password' => bcrypt('password'), // password
                'player_name' => 'Kirito',
                'level' => 10,
                'xp' => 10500, // Level 10 approx
                'avatar_url' => null,
            ]
        );

        $now = Carbon::now();

        // 2. Create Initial Transactions (Loot & Damage)
        // Income (Loot)
        GeneralizedTransition::firstOrCreate([
            'user_id' => $user->id,
            'name' => 'Quest Reward: S-Class Ingredient',
            'input' => 1,
            'type' => 'v',
            'total_value' => 5000.00,
            'start_date' => $now->copy()->subDays(2),
            'tags' => ['Quest', 'Loot'],
        ]);

        GeneralizedTransition::firstOrCreate([
            'user_id' => $user->id,
            'name' => 'Salary: Knights of the Blood',
            'input' => 1,
            'type' => 'v',
            'total_value' => 12000.00,
            'start_date' => $now->copy()->startOfMonth()->addDays(5),
            'tags' => ['Salário', 'Guilda'],
        ]);

        // Expense (Damage)
        GeneralizedTransition::firstOrCreate([
            'user_id' => $user->id,
            'name' => 'Repair: Elucidator',
            'input' => 0,
            'type' => 'v',
            'total_value' => 450.00,
            'start_date' => $now->copy()->subDays(1),
            'tags' => ['Equipamento', 'Manutenção'],
        ]);

        GeneralizedTransition::firstOrCreate([
            'user_id' => $user->id,
            'name' => 'Inn Rental (Month)',
            'input' => 0,
            'type' => 'p', // Recurring/Installment simulated
            'total_value' => 1200.00, // Total for a period maybe? Or just one entry
            'installment_value' => 1200.00,
            'start_date' => $now->copy()->startOfMonth(),
            'end_date' => $now->copy()->addMonths(1),
            'fix' => 1,
            'tags' => ['Aluguel', 'Moradia'],
        ]);

        // 3. Create Floors (Goals)
        FinancialGoal::firstOrCreate([
            'user_id' => $user->id,
            'name' => 'Buy House on Floor 22',
            'target_amount' => 150000.00,
            'current_amount' => 45000.00,
            'floor_number' => 22,
            'icon' => '🏡',
            'status' => 'active',
        ]);

        FinancialGoal::firstOrCreate([
            'user_id' => $user->id,
            'name' => 'Upgrade Dark Repulser',
            'target_amount' => 5000.00,
            'current_amount' => 5000.00, // Cleared
            'floor_number' => 48,
            'icon' => '⚔️',
            'status' => 'cleared',
        ]);

        FinancialGoal::firstOrCreate([
            'user_id' => $user->id,
            'name' => 'Emergency Potions Stock',
            'target_amount' => 2000.00,
            'current_amount' => 500.00,
            'floor_number' => 1,
            'icon' => '🧪',
            'status' => 'active',
        ]);

        // 4. Create Inventory Items
        \App\Models\InventoryItem::firstOrCreate([
            'user_id' => $user->id,
            'name' => 'Elucidator Black Card',
            'slot' => 'weapon',
            'rarity' => 'legendary',
            'value' => 15000.00,
            'icon' => '⚔️',
            'description' => 'Cartão principal com cashback e milhas',
            'attributes' => ['banco' => 'Nubank', 'limite' => 'R$ 15.000', 'bandeira' => 'Mastercard'],
            'equipped' => true,
        ]);

        \App\Models\InventoryItem::firstOrCreate([
            'user_id' => $user->id,
            'name' => 'Dark Repulser Card',
            'slot' => 'weapon',
            'rarity' => 'epic',
            'value' => 8000.00,
            'icon' => '🗡️',
            'description' => 'Cartão secundário para compras online',
            'attributes' => ['banco' => 'Inter', 'limite' => 'R$ 8.000', 'bandeira' => 'Visa'],
            'equipped' => true,
        ]);

        \App\Models\InventoryItem::firstOrCreate([
            'user_id' => $user->id,
            'name' => 'NerveGear Savings',
            'slot' => 'accessory',
            'rarity' => 'rare',
            'value' => 25000.00,
            'icon' => '💍',
            'description' => 'Conta poupança principal',
            'attributes' => ['banco' => 'Nubank', 'tipo' => 'Poupança'],
            'equipped' => true,
        ]);

        \App\Models\InventoryItem::firstOrCreate([
            'user_id' => $user->id,
            'name' => 'Health Potion Sub',
            'slot' => 'consumable',
            'rarity' => 'uncommon',
            'value' => 45.90,
            'icon' => '🧪',
            'description' => 'Netflix mensal',
            'attributes' => ['tipo' => 'Streaming', 'ciclo' => 'Mensal'],
            'equipped' => true,
        ]);

        \App\Models\InventoryItem::firstOrCreate([
            'user_id' => $user->id,
            'name' => 'Col Crystal Reserve',
            'slot' => 'material',
            'rarity' => 'legendary',
            'value' => 50000.00,
            'icon' => '💎',
            'description' => 'Bitcoin HODL',
            'attributes' => ['exchange' => 'Binance', 'moeda' => 'BTC'],
            'equipped' => true,
        ]);

        // 5. Create Guild
        $guild = \App\Models\Guild::firstOrCreate(
            ['name' => 'Knights of the Blood'],
            [
                'icon' => '🛡️',
                'description' => 'A guild lendária de Aincrad. Controle financeiro como espada.',
                'master_id' => $user->id,
            ]
        );

        if ($guild->members()->count() === 0) {
            $guild->members()->attach($user->id, ['role' => 'master']);
        }
    }
}
