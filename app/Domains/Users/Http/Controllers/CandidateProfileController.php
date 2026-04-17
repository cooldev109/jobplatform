<?php

namespace App\Domains\Users\Http\Controllers;

use App\Domains\Users\Http\Requests\UpdateCandidateProfileRequest;
use App\Domains\Users\Http\Resources\CandidateProfileResource;
use App\Domains\Users\Services\CandidateProfileService;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CandidateProfileController extends Controller
{
    public function __construct(private readonly CandidateProfileService $profiles)
    {
    }

    public function show(Request $request): JsonResponse
    {
        abort_unless($request->user()->isCandidate(), 403);

        $profile = $this->profiles->getForUser($request->user());

        return response()->json([
            'profile' => $profile ? new CandidateProfileResource($profile) : null,
        ]);
    }

    public function update(UpdateCandidateProfileRequest $request): CandidateProfileResource
    {
        $profile = $this->profiles->upsert($request->user(), $request->validated());

        return new CandidateProfileResource($profile);
    }
}
