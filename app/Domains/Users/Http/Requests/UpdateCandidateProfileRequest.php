<?php

namespace App\Domains\Users\Http\Requests;

use App\Support\BrazilianStates;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCandidateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && $this->user()->isCandidate();
    }

    public function rules(): array
    {
        return [
            'nome_completo' => ['required', 'string', 'max:255'],
            'telefone' => ['required', 'string', 'max:32'],
            'cidade' => ['required', 'string', 'max:120'],
            'estado' => ['required', 'string', 'size:2', Rule::in(BrazilianStates::ALL)],
            'area_atuacao' => ['required', 'string', 'max:120'],
            'descricao_breve' => ['nullable', 'string', 'max:2000'],
        ];
    }
}
