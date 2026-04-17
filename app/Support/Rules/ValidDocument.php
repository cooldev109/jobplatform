<?php

namespace App\Support\Rules;

use App\Support\DocumentValidator;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidDocument implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! is_string($value) || ! DocumentValidator::isValid($value)) {
            $fail('O :attribute informado não é um CPF ou CNPJ válido.');
        }
    }
}
