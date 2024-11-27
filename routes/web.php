<?php

use App\Http\Controllers\ProjetoController;
use App\Http\Controllers\TarefaController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
});

//UserController
Route::post('/registo', [UserController::class, 'registo']);
Route::post('/logout', [UserController::class, 'logout']);
Route::post('/login', [UserController::class, 'login']);

//ProjetosController
Route::post('/criarProjeto', [ProjetoController::class, 'criarProjeto']);
Route::post('/criarProjeto', [ProjetoController::class, 'salvarProjto']);
Route::post('/listarProjetos', [ProjetoController::class, 'listarProjetos']);
Route::post('/editar/{projeto}', [ProjetoController::class, 'editarProjeto']);
Route::post('/editar/{projeto}', [ProjetoController::class, 'atualizarProjeto']);
Route::post('/deletar/{projeto}', [ProjetoController::class, 'deletarProjeto']);

//TarefasController
Route::post('/projetos/{projeto}/tarefas/criar', [TarefaController::class, 'criarTarefa']);
Route::get('/projetos/{projeto}/tarefas', [TarefaController::class, 'listarTarefas']);
Route::get('/tarefas/{tarefa}/editar', [TarefaController::class, 'editarTarefa']);
Route::post('/tarefas/{tarefa}/atualizar', [TarefaController::class, 'atualizarTarefa']);
Route::post('/tarefas/{tarefa}/deletar', [TarefaController::class, 'deletarTarefa']);



