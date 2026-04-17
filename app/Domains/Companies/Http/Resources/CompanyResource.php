<?php

namespace App\Domains\Companies\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CompanyResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'owner_user_id' => $this->owner_user_id,
            'nome_empresa' => $this->nome_empresa,
            'nome_responsavel' => $this->nome_responsavel,
            'documento' => $this->documento,
            'documento_formatado' => $this->formatDocument(),
            'email' => $this->email,
            'telefone' => $this->telefone,
            'cidade' => $this->cidade,
            'estado' => $this->estado,
            'segmento' => $this->segmento,
            'descricao_curta' => $this->descricao_curta,
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }

    private function formatDocument(): string
    {
        $d = (string) $this->documento;
        if (strlen($d) === 11) {
            return substr($d, 0, 3) . '.' . substr($d, 3, 3) . '.' . substr($d, 6, 3) . '-' . substr($d, 9, 2);
        }
        if (strlen($d) === 14) {
            return substr($d, 0, 2) . '.' . substr($d, 2, 3) . '.' . substr($d, 5, 3) . '/' . substr($d, 8, 4) . '-' . substr($d, 12, 2);
        }
        return $d;
    }
}
