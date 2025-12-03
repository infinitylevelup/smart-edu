<?php
// app/Services/GamificationService.php
namespace App\Services;

use App\Models\StudentGamificationProfile;
use App\Models\XpLog;
use App\Models\User;
use Carbon\Carbon;

class GamificationService
{
    /**
     * Award XP to student and update level + streak.
     */
    public function awardXp(User $student, string $sourceType, ?int $sourceId, int $amount): StudentGamificationProfile
    {
        $profile = StudentGamificationProfile::firstOrCreate(
            ['student_id' => $student->id],
            ['total_xp' => 0, 'level' => 1]
        );

        // Log XP
        XpLog::create([
            'student_id' => $student->id,
            'source_type' => $sourceType,
            'source_id' => $sourceId,
            'xp_amount' => $amount,
        ]);

        // Add XP
        $profile->total_xp += $amount;

        // Update streak
        $this->updateStreak($profile);

        // Recalculate level
        $profile->level = $this->calculateLevel($profile->total_xp);

        $profile->save();

        return $profile;
    }

    /**
     * Simple level formula:
     * level up every 200 XP (you can tune later).
     */
    public function calculateLevel(int $xp): int
    {
        return max(1, (int) floor($xp / 200) + 1);
    }

    /**
     * Update streak based on last activity date.
     */
    public function updateStreak(StudentGamificationProfile $profile): void
    {
        $now = Carbon::now();
        $last = $profile->last_activity_at;

        if (!$last) {
            $profile->current_streak = 1;
        } else {
            $diffDays = $last->startOfDay()->diffInDays($now->startOfDay());

            if ($diffDays === 0) {
                // same day → streak unchanged
            } elseif ($diffDays === 1) {
                // consecutive day
                $profile->current_streak += 1;
            } else {
                // streak broken
                $profile->current_streak = 1;
            }
        }

        if ($profile->current_streak > $profile->longest_streak) {
            $profile->longest_streak = $profile->current_streak;
        }

        $profile->last_activity_at = $now;
    }
}
