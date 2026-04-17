<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('candidate_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained('users')->cascadeOnDelete();
            $table->string('nome_completo');
            $table->string('telefone', 32);
            $table->string('cidade', 120);
            $table->string('estado', 2);
            $table->string('area_atuacao', 120);
            $table->text('descricao_breve')->nullable();
            $table->timestamps();

            $table->index(['estado', 'cidade']);
            $table->index('area_atuacao');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('candidate_profiles');
    }
};
