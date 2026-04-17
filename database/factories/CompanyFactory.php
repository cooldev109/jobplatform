<?php

namespace Database\Factories;

use App\Domains\Companies\Models\Company;
use App\Domains\Users\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Company>
 */
class CompanyFactory extends Factory
{
    protected $model = Company::class;

    public function definition(): array
    {
        // Generate a valid CNPJ
        $digits = [];
        for ($i = 0; $i < 12; $i++) {
            $digits[] = random_int(0, 9);
        }
        $digits = $this->appendCnpjCheckDigits($digits);

        return [
            'owner_user_id' => User::factory()->companyOwner(),
            'nome_empresa' => $this->faker->company(),
            'nome_responsavel' => $this->faker->name(),
            'documento' => implode('', $digits),
            'email' => $this->faker->unique()->safeEmail(),
            'telefone' => $this->faker->phoneNumber(),
            'cidade' => $this->faker->city(),
            'estado' => $this->faker->randomElement(['SP', 'RJ', 'MG', 'RS', 'PR', 'BA']),
            'segmento' => 'Agronegócio',
            'descricao_curta' => $this->faker->sentence(),
        ];
    }

    private function appendCnpjCheckDigits(array $digits): array
    {
        $calc = function (array $digits, int $length): int {
            $weights = $length === 12
                ? [5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2]
                : [6, 5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2];

            $sum = 0;
            for ($i = 0; $i < $length; $i++) {
                $sum += $digits[$i] * $weights[$i];
            }
            $mod = $sum % 11;
            return $mod < 2 ? 0 : 11 - $mod;
        };

        $digits[12] = $calc($digits, 12);
        $digits[13] = $calc($digits, 13);

        return $digits;
    }
}
