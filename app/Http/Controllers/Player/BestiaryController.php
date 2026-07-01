<?php

namespace App\Http\Controllers\Player;

use App\Http\Controllers\Controller;
use App\Models\Monster;
use Inertia\Inertia;

class BestiaryController extends Controller
{
    public function index()
    {
        $monsters = Monster::orderByRaw("
            CASE category
                WHEN 'boss' THEN 1
                WHEN 'master' THEN 2
                WHEN 'elite' THEN 3
                WHEN 'intermediate' THEN 4
                WHEN 'common' THEN 5
                ELSE 6
            END
        ")
            ->orderBy('floor_min')
            ->orderBy('name')
            ->get()
            ->map(fn($m) => [
                'id' => $m->id,
                'name' => $m->name,
                'category' => $m->category,
                'category_label' => $m->getCategoryLabel(),
                'category_color' => $m->getCategoryColor(),
                'floor_min' => $m->floor_min,
                'floor_max' => $m->floor_max,
                'specific_floor' => $m->specific_floor,
                'description' => $m->description,
                'image_url' => $m->image_url,
                'icon' => $m->icon,
                'xp_reward' => $m->xp_reward,
            ]);

        return Inertia::render('Bestiary', [
            'monsters' => $monsters,
        ]);
    }
}
