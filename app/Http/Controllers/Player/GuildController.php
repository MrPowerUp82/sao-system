<?php

namespace App\Http\Controllers\Player;

use App\Http\Controllers\Controller;
use App\Models\Guild;
use App\Models\GuildMessage;
use App\Services\XpService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class GuildController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Get user's guilds
        $guilds = $user->guilds()
            ->with(['master:id,name,player_name', 'members:id,name,player_name,level,xp,avatar_url'])
            ->get()
            ->map(fn($guild) => [
                'id' => $guild->id,
                'name' => $guild->name,
                'icon' => $guild->icon,
                'description' => $guild->description,
                'invite_code' => $guild->invite_code,
                'master' => [
                    'id' => $guild->master->id,
                    'name' => $guild->master->player_name ?? $guild->master->name,
                ],
                'is_master' => $guild->master_id === $user->id,
                'role' => $guild->pivot->role,
                'member_count' => $guild->members->count(),
                'total_xp' => $guild->members->sum('xp'),
                'avg_level' => round($guild->members->avg('level'), 1),
                'members' => $guild->members->map(fn($m) => [
                    'id' => $m->id,
                    'name' => $m->player_name ?? $m->name,
                    'level' => $m->level ?? 1,
                    'xp' => $m->xp ?? 0,
                    'role' => $m->pivot->role,
                    'avatar' => $m->avatar_url,
                ]),
            ]);

        return Inertia::render('Guild', [
            'guilds' => $guilds,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:30',
            'icon' => 'nullable|string|max:10',
            'description' => 'nullable|string|max:200',
        ]);

        $user = auth()->user();

        // Max 3 guilds per user
        if ($user->guilds()->count() >= 3) {
            return redirect()->back()->with('error', 'Limite de 3 guilds atingido!');
        }

        $guild = Guild::create([
            'name' => $validated['name'],
            'icon' => $validated['icon'] ?: '⚔️',
            'description' => $validated['description'] ?? null,
            'master_id' => $user->id,
        ]);

        // Add master as member
        $guild->members()->attach($user->id, ['role' => 'master']);

        XpService::awardXp($user, 50, 'guild_created');

        return redirect()->back()->with('success', 'Guild criada! +50 XP. Código: ' . $guild->invite_code);
    }

    public function join(Request $request)
    {
        $validated = $request->validate([
            'invite_code' => 'required|string|size:8',
        ]);

        $user = auth()->user();
        $guild = Guild::where('invite_code', strtoupper($validated['invite_code']))->first();

        if (!$guild) {
            return redirect()->back()->with('error', 'Código inválido!');
        }

        if ($guild->members()->where('user_id', $user->id)->exists()) {
            return redirect()->back()->with('error', 'Você já está nessa guild!');
        }

        if ($guild->getMemberCount() >= 10) {
            return redirect()->back()->with('error', 'Guild lotada! (máx 10 membros)');
        }

        $guild->members()->attach($user->id, ['role' => 'member']);

        XpService::awardXp($user, 25, 'guild_joined');

        return redirect()->back()->with('success', "Entrou na guild \"{$guild->name}\"! +25 XP");
    }

    public function leave(Guild $guild)
    {
        $user = auth()->user();

        if ($guild->master_id === $user->id) {
            // Master leaving = dissolve guild
            $guild->members()->detach();
            $guild->delete();
            return redirect()->back()->with('success', 'Guild dissolvida.');
        }

        $guild->members()->detach($user->id);

        return redirect()->back()->with('success', "Saiu da guild \"{$guild->name}\".");
    }

    public function destroy(Guild $guild)
    {
        if ($guild->master_id !== auth()->id())
            abort(403);

        $guild->members()->detach();
        $guild->delete();

        return redirect()->back()->with('success', 'Guild encerrada.');
    }

    public function messages(Guild $guild)
    {
        $user = auth()->user();

        // Check if user is a member of this guild
        if (!$guild->members()->where('user_id', $user->id)->exists()) {
            abort(403, 'Você não faz parte desta guild.');
        }

        $messages = $guild->messages()
            ->with('user:id,name,player_name,avatar_url')
            ->latest()
            ->limit(50)
            ->get()
            ->reverse()
            ->values()
            ->map(function ($msg) use ($guild) {
                $member = $guild->members->firstWhere('id', $msg->user_id);
                $role = $member ? $member->pivot->role : 'member';
                return [
                    'id' => $msg->id,
                    'message' => $msg->message,
                    'created_at' => $msg->created_at->toISOString(),
                    'user' => [
                        'id' => $msg->user->id,
                        'name' => $msg->user->player_name ?? $msg->user->name,
                        'avatar' => $msg->user->avatar_url,
                        'role' => $role,
                    ]
                ];
            });

        return response()->json($messages);
    }

    public function sendMessage(Request $request, Guild $guild)
    {
        $user = auth()->user();

        // Check if user is a member of this guild
        if (!$guild->members()->where('user_id', $user->id)->exists()) {
            abort(403, 'Você não faz parte desta guild.');
        }

        $validated = $request->validate([
            'message' => 'required|string|max:500',
        ]);

        $msg = GuildMessage::create([
            'guild_id' => $guild->id,
            'user_id' => $user->id,
            'message' => $validated['message'],
        ]);

        // Load relations and pivot role for response
        $msg->load('user:id,name,player_name,avatar_url');
        $member = $guild->members->firstWhere('id', $user->id);
        $role = $member ? $member->pivot->role : 'member';

        return response()->json([
            'id' => $msg->id,
            'message' => $msg->message,
            'created_at' => $msg->created_at->toISOString(),
            'user' => [
                'id' => $msg->user->id,
                'name' => $msg->user->player_name ?? $msg->user->name,
                'avatar' => $msg->user->avatar_url,
                'role' => $role,
            ]
        ]);
    }
}
