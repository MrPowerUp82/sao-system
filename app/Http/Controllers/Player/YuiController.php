<?php

namespace App\Http\Controllers\Player;

use App\Http\Controllers\Controller;
use App\Services\YuiService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class YuiController extends Controller
{
    protected $yui;

    public function __construct(YuiService $yui)
    {
        $this->yui = $yui;
    }

    public function sendMessage(Request $request)
    {
        $validated = $request->validate([
            'message' => 'required|string|max:500',
            'history' => 'nullable|array',
            'history.*.role' => 'required|in:user,yui',
            'history.*.text' => 'required|string',
        ]);

        $history = $validated['history'] ?? [];
        $reply = $this->yui->chat($validated['message'], auth()->user(), $history);

        return response()->json(['reply' => $reply]);
    }

    public function completeQuest(Request $request)
    {
        // TODO: Implement real quest completion logic (for now just award XP)
        // In future: validate quest ID and conditions

        $user = auth()->user();
        $xp = 500; // Fixed reward for MVP

        // This would call XpService to add XP
        // For now, just return success

        return response()->json(['message' => 'Quest completed!', 'reward' => $xp]);
    }

    public function getStatus()
    {
        $user = auth()->user();

        return response()->json([
            'alerts' => $this->yui->analyze($user),
            'active_quest' => $this->yui->generateQuest($user),
        ]);
    }
}
