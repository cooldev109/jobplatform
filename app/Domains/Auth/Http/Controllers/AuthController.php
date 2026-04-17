<?php

namespace App\Domains\Auth\Http\Controllers;

use App\Domains\Auth\Http\Requests\LoginRequest;
use App\Domains\Auth\Http\Requests\RegisterRequest;
use App\Domains\Auth\Http\Resources\UserResource;
use App\Domains\Auth\Services\AuthService;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct(private readonly AuthService $auth)
    {
    }

    public function register(RegisterRequest $request): JsonResponse
    {
        $result = $this->auth->register($request->validated());

        return response()->json([
            'user' => new UserResource($result['user']),
            'token' => $result['token'],
        ], 201);
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $result = $this->auth->login(
            $request->validated('email'),
            $request->validated('password'),
        );

        return response()->json([
            'user' => new UserResource($result['user']),
            'token' => $result['token'],
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $this->auth->logout($request);

        return response()->json(['message' => 'Logout realizado.']);
    }

    public function me(Request $request): UserResource
    {
        return new UserResource($request->user());
    }
}
