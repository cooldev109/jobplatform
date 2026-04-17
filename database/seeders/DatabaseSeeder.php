<?php

namespace Database\Seeders;

use App\Domains\Users\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $accounts = [
            ['name' => 'Candidato Demo', 'email' => 'candidato@vagasagro.test', 'type' => User::TYPE_CANDIDATE],
            ['name' => 'Empresa Demo',   'email' => 'empresa@vagasagro.test',   'type' => User::TYPE_COMPANY_OWNER],
        ];

        foreach ($accounts as $acc) {
            User::updateOrCreate(
                ['email' => $acc['email']],
                [
                    'name' => $acc['name'],
                    'password' => '123',
                    'user_type' => $acc['type'],
                    'email_verified_at' => now(),
                ],
            );
        }

        $this->command->info('Seeded test accounts (password = 123):');
        foreach ($accounts as $acc) {
            $this->command->info("  {$acc['type']}: {$acc['email']}");
        }
    }
}
