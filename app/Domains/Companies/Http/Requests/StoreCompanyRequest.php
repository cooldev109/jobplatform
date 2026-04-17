<?php

namespace App\Domains\Companies\Http\Requests;

use App\Support\BrazilianStates;
use App\Support\DocumentValidator;
use App\Support\Rules\ValidDocument;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCompanyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && $this->user()->isCompanyOwner();
    }

    public function rules(): array
    {
        return [
            'nome_empresa' => ['required', 'string', 'max:255'],
            'nome_responsavel' => ['required', 'string', 'max:255'],
            'documento' => [
                'required',
                'string',
                new ValidDocument,
                Rule::unique('companies', 'documento')->whereNull('deleted_at'),
            ],
            'email' => ['required', 'string', 'email:rfc', 'max:255'],
            'telefone' => ['required', 'string', 'max:32'],
            'cidade' => ['required', 'string', 'max:120'],
            'estado' => ['required', 'string', 'size:2', Rule::in(BrazilianStates::ALL)],
            'segmento' => ['required', 'string', 'max:120'],
            'descricao_curta' => ['nullable', 'string', 'max:2000'],
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->filled('documento')) {
            $this->merge(['documento' => DocumentValidator::normalize($this->input('documento'))]);
        }
    }
}
