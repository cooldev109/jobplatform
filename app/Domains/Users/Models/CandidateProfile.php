<?php

namespace App\Domains\Users\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CandidateProfile extends Model
{
    protected $fillable = [
        'user_id',
        'nome_completo',
        'telefone',
        'cidade',
        'estado',
        'area_atuacao',
        'descricao_breve',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
