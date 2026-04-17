<?php

namespace App\Domains\Auth\Services;

use App\Domains\Users\Models\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    public function register(array $data): array
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
            'user_type' => $data['user_type'],
        ]);

        $token = $user->createToken('auth')->plainTextToken;

        return ['user' => $user, 'token' => $token];
    }

    public function login(string $email, string $password): array
    {
        $user = User::where('email', $email)->first();

        if (! $user || ! Hash::check($password, $user->password)) {
            throw new AuthenticationException('Credenciais inválidas.');
        }

        $token = $user->createToken('auth')->plainTextToken;

        return ['user' => $user, 'token' => $token];
    }

    public function logout(User $user): void
    {
        $user->currentAccessToken()?->delete();
    }
}
