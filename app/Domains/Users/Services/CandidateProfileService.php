<?php

namespace App\Domains\Users\Services;

use App\Domains\Users\Models\CandidateProfile;
use App\Domains\Users\Models\User;

class CandidateProfileService
{
    public function getForUser(User $user): ?CandidateProfile
    {
        return $user->candidateProfile;
    }

    public function upsert(User $user, array $data): CandidateProfile
    {
        $profile = $user->candidateProfile()->updateOrCreate(
            ['user_id' => $user->id],
            $data,
        );

        return $profile->fresh();
    }
}
