<?php

namespace Tests\Feature\Profiles;

use App\Domains\Users\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CandidateProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_candidate_can_upsert_profile(): void
    {
        $user = User::factory()->candidate()->create();
        $token = $user->createToken('t')->plainTextToken;

        $payload = [
            'nome_completo' => 'Ana Silva',
            'telefone' => '+55 11 91234-5678',
            'cidade' => 'Campinas',
            'estado' => 'SP',
            'area_atuacao' => 'Agronomia',
            'descricao_breve' => 'Engenheira agrônoma com 5 anos de experiência.',
        ];

        $this->withHeader('Authorization', "Bearer $token")
            ->putJson('/api/candidate/profile', $payload)
            ->assertOk()
            ->assertJsonPath('data.nome_completo', 'Ana Silva');

        $this->assertDatabaseHas('candidate_profiles', ['user_id' => $user->id, 'cidade' => 'Campinas']);

        // Second upsert updates, doesn't duplicate
        $this->withHeader('Authorization', "Bearer $token")
            ->putJson('/api/candidate/profile', array_merge($payload, ['cidade' => 'São Paulo']))
            ->assertOk();

        $this->assertDatabaseCount('candidate_profiles', 1);
        $this->assertDatabaseHas('candidate_profiles', ['user_id' => $user->id, 'cidade' => 'São Paulo']);
    }

    public function test_company_owner_cannot_create_candidate_profile(): void
    {
        $user = User::factory()->companyOwner()->create();
        $token = $user->createToken('t')->plainTextToken;

        $this->withHeader('Authorization', "Bearer $token")
            ->putJson('/api/candidate/profile', [
                'nome_completo' => 'X', 'telefone' => '1', 'cidade' => 'Y',
                'estado' => 'SP', 'area_atuacao' => 'Z',
            ])
            ->assertForbidden();
    }

    public function test_profile_requires_valid_state(): void
    {
        $user = User::factory()->candidate()->create();
        $token = $user->createToken('t')->plainTextToken;

        $this->withHeader('Authorization', "Bearer $token")
            ->putJson('/api/candidate/profile', [
                'nome_completo' => 'A', 'telefone' => '1', 'cidade' => 'B',
                'estado' => 'XX', 'area_atuacao' => 'C',
            ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors('estado');
    }

    public function test_candidate_can_view_profile_when_empty(): void
    {
        $user = User::factory()->candidate()->create();
        $token = $user->createToken('t')->plainTextToken;

        $this->withHeader('Authorization', "Bearer $token")
            ->getJson('/api/candidate/profile')
            ->assertOk()
            ->assertJson(['profile' => null]);
    }
}
