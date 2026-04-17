<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('owner_user_id')->constrained('users')->restrictOnDelete();
            $table->string('nome_empresa');
            $table->string('nome_responsavel');
            $table->string('documento', 14)->unique();
            $table->string('email');
            $table->string('telefone', 32);
            $table->string('cidade', 120);
            $table->string('estado', 2);
            $table->string('segmento', 120);
            $table->text('descricao_curta')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('owner_user_id');
            $table->index(['estado', 'cidade']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
