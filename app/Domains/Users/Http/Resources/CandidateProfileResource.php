<?php

namespace App\Domains\Users\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CandidateProfileResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'nome_completo' => $this->nome_completo,
            'telefone' => $this->telefone,
            'cidade' => $this->cidade,
            'estado' => $this->estado,
            'area_atuacao' => $this->area_atuacao,
            'descricao_breve' => $this->descricao_breve,
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
