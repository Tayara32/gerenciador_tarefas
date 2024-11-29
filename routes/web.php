<?php

use App\Http\Controllers\ProjetoController;
use App\Http\Controllers\TarefaController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {

    $projetos = [];

    if(auth()->check()){
        $projetos = auth()->user()->usersProjetos()->latest()->get(); //perspectiva do user
    }
    
    return view('home', ['projetos' => $projetos]);
   
});

//UserController
Route::post('/registo', [UserController::class, 'registo']);
Route::post('/logout', [UserController::class, 'logout']);
Route::post('/login', [UserController::class, 'login']);

//ProjetosController
Route::get('/criarProjeto', [ProjetoController::class, 'criarProjeto']);
Route::post('/criarProjeto', [ProjetoController::class, 'salvarProjeto']);
Route::get('/editar/{projeto}', [ProjetoController::class, 'editarProjeto']);
Route::get('/detalhe/{projeto}', [ProjetoController::class, 'detalheProjeto']);
Route::put('/editar/{projeto}', [ProjetoController::class, 'atualizarProjeto']);
Route::delete('/deletar/{projeto}', [ProjetoController::class, 'deletarProjeto']);

//TarefasController
Route::post('/projetos/{projeto}/tarefas/criar', [TarefaController::class, 'criarTarefa']);
Route::put('/tarefas/{tarefa}/editar', [TarefaController::class, 'editarTarefa']);
Route::delete('/tarefas/{tarefa}/deletar', [TarefaController::class, 'deletarTarefa']);
Route::post('/tarefas/{tarefa}/tags/adicionar', [TarefaController::class, 'adicionarTag']);



