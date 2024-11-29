<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('projetos', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->longText('descricao');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('tags', function (Blueprint $table) {
            $table->id();
            $table->string('nome')->unique(); 
            $table->timestamps();
        });


        Schema::create('tarefas', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->longText('descricao');
            $table->date('prazo')->nullable();
            $table->enum('status', ['pendente', 'concluida', 'em andamento'])->default('pendente');
            $table->unsignedBigInteger('tag_id')->nullable();
            $table->unsignedBigInteger('projeto_id');
            $table->foreign('projeto_id')->references('id')->on('projetos');
            $table->foreign('tag_id')->references('id')->on('tags'); 
            $table->timestamps();
        });
    }

    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tarefas');
        Schema::dropIfExists('tags');
        Schema::dropIfExists('projetos');
    }
};
