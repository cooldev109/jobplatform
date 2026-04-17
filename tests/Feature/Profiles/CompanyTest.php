<?php

namespace Tests\Feature\Profiles;

use App\Domains\Companies\Models\Company;
use App\Domains\Users\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CompanyTest extends TestCase
{
    use RefreshDatabase;

    private function companyPayload(array $overrides = []): array
    {
        return array_merge([
            'nome_empresa' => 'Fazenda Nova',
            'nome_responsavel' => 'João Silva',
            'documento' => '11.222.333/0001-81', // valid CNPJ
            'email' => 'contato@fazendanova.com',
            'telefone' => '+55 11 99999-0000',
            'cidade' => 'Ribeirão Preto',
            'estado' => 'SP',
            'segmento' => 'Agronegócio',
            'descricao_curta' => 'Produtora de grãos.',
        ], $overrides);
    }

    public function test_company_owner_can_create_company(): void
    {
        $user = User::factory()->companyOwner()->create();
        $token = $user->createToken('t')->plainTextToken;

        $this->withHeader('Authorization', "Bearer $token")
            ->postJson('/api/companies', $this->companyPayload())
            ->assertCreated()
            ->assertJsonPath('data.nome_empresa', 'Fazenda Nova');

        $this->assertDatabaseHas('companies', [
            'owner_user_id' => $user->id,
            'documento' => '11222333000181', // stored normalized
        ]);
    }

    public function test_candidate_cannot_create_company(): void
    {
        $user = User::factory()->candidate()->create();
        $token = $user->createToken('t')->plainTextToken;

        $this->withHeader('Authorization', "Bearer $token")
            ->postJson('/api/companies', $this->companyPayload())
            ->assertForbidden();
    }

    public function test_invalid_document_rejected(): void
    {
        $user = User::factory()->companyOwner()->create();
        $token = $user->createToken('t')->plainTextToken;

        $this->withHeader('Authorization', "Bearer $token")
            ->postJson('/api/companies', $this->companyPayload(['documento' => '00000000000']))
            ->assertUnprocessable()
            ->assertJsonValidationErrors('documento');
    }

    public function test_user_cannot_view_another_users_company(): void
    {
        $owner = User::factory()->companyOwner()->create();
        $other = User::factory()->companyOwner()->create();
        $otherToken = $other->createToken('t')->plainTextToken;

        $company = Company::factory()->for($owner, 'owner')->create();

        $this->withHeader('Authorization', "Bearer $otherToken")
            ->getJson("/api/companies/{$company->id}")
            ->assertForbidden();
    }

    public function test_owner_can_update_company(): void
    {
        $user = User::factory()->companyOwner()->create();
        $token = $user->createToken('t')->plainTextToken;
        $company = Company::factory()->for($user, 'owner')->create();

        $this->withHeader('Authorization', "Bearer $token")
            ->putJson("/api/companies/{$company->id}", ['nome_empresa' => 'Fazenda Renovada'])
            ->assertOk()
            ->assertJsonPath('data.nome_empresa', 'Fazenda Renovada');
    }

    public function test_index_returns_only_own_companies(): void
    {
        $user = User::factory()->companyOwner()->create();
        $other = User::factory()->companyOwner()->create();
        Company::factory()->for($user, 'owner')->count(2)->create();
        Company::factory()->for($other, 'owner')->count(3)->create();

        $token = $user->createToken('t')->plainTextToken;

        $this->withHeader('Authorization', "Bearer $token")
            ->getJson('/api/companies')
            ->assertOk()
            ->assertJsonCount(2, 'data');
    }
}
