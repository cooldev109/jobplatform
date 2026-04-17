<?php

namespace Database\Seeders;

use App\Domains\Users\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()->candidate()->create([
            'name' => 'Candidato Demo',
            'email' => 'candidato@vagasagro.test',
        ]);

        User::factory()->companyOwner()->create([
            'name' => 'Empresa Demo',
            'email' => 'empresa@vagasagro.test',
        ]);
    }
}
