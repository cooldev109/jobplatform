<?php

namespace App\Domains\Companies\Http\Requests;

use App\Support\BrazilianStates;
use App\Support\DocumentValidator;
use App\Support\Rules\ValidDocument;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCompanyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $companyId = $this->route('company')?->id;

        return [
            'nome_empresa' => ['sometimes', 'required', 'string', 'max:255'],
            'nome_responsavel' => ['sometimes', 'required', 'string', 'max:255'],
            'documento' => [
                'sometimes', 'required', 'string',
                new ValidDocument,
                Rule::unique('companies', 'documento')->ignore($companyId)->whereNull('deleted_at'),
            ],
            'email' => ['sometimes', 'required', 'string', 'email:rfc', 'max:255'],
            'telefone' => ['sometimes', 'required', 'string', 'max:32'],
            'cidade' => ['sometimes', 'required', 'string', 'max:120'],
            'estado' => ['sometimes', 'required', 'string', 'size:2', Rule::in(BrazilianStates::ALL)],
            'segmento' => ['sometimes', 'required', 'string', 'max:120'],
            'descricao_curta' => ['sometimes', 'nullable', 'string', 'max:2000'],
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->filled('documento')) {
            $this->merge(['documento' => DocumentValidator::normalize($this->input('documento'))]);
        }
    }
}
