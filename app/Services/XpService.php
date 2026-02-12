<?php

namespace App\Services;

use App\Models\User;

class XpService
{
    const XP_REGISTER_TRADE = 10;
    const XP_FIXED_PAYMENT = 25;
    const XP_FLOOR_CLEARED = 100;

    public static function levelFormula(int $xp): int
    {
        return max(1, (int) floor(sqrt($xp / 100)));
    }

    public static function xpForLevel(int $level): int
    {
        return $level * $level * 100;
    }

    public static function xpToNextLevel(int $xp): array
    {
        $currentLevel = self::levelFormula($xp);
        $nextLevel = $currentLevel + 1;
        $xpForCurrent = self::xpForLevel($currentLevel);
        $xpForNext = self::xpForLevel($nextLevel);
        $progress = $xpForNext > $xpForCurrent
            ? (($xp - $xpForCurrent) / ($xpForNext - $xpForCurrent)) * 100
            : 100;

        return [
            'current_level' => $currentLevel,
            'current_xp' => $xp,
            'xp_for_next' => $xpForNext,
            'xp_remaining' => max(0, $xpForNext - $xp),
            'progress' => round(min(100, max(0, $progress)), 1),
        ];
    }

    public static function awardXp(User $user, int $amount, string $reason = ''): void
    {
        $user->xp += $amount;
        $user->level = self::levelFormula($user->xp);
        $user->save();
    }
}
