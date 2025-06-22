<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Primeiro alteramos a tabela files (se necessário)
        Schema::table('files', function (Blueprint $table) {
            // Adiciona colunas que faltam (se aplicável)
            if (!Schema::hasColumn('files', 'user')) {
                $table->unsignedBigInteger('user')->after('size');
            }
            
            // Ou outras alterações necessárias
        });

        // 2. Depois criamos a nova tabela tests
        Schema::create('tests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('file_id');
            $table->integer('score')->nullable();
            $table->timestamps();
            
            $table->foreign('file_id')
                  ->references('id')
                  ->on('files')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tests');
        
        // Reverte alterações na tabela files (se necessário)
        Schema::table('files', function (Blueprint $table) {
            $table->dropColumn('user'); // Apenas se você adicionou nesta migration
        });
    }
};