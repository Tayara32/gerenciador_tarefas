<?php

namespace App\Http\Controllers;

use App\Models\Projeto;
use App\Models\Tag;
use App\Models\Tarefa;
use Illuminate\Http\Request;

class ProjetoController extends Controller
{
      
    public function criarProjeto( ){
        
        return view('criarProjeto');
       
    }
    public function salvarProjeto(Request $request){
        $dados = $request->validate([
            'nome' => 'required',
            'descricao' => 'required',
        ]);

        $dados['nome'] = strip_tags($dados['nome']);
        $dados['descricao'] = strip_tags($dados['descricao']);
        $dados['user_id'] = auth()->user()->id;
        $projetos = Projeto::create($dados);
        auth()->user()->$projetos; 

        return redirect('/');


    }


    public function editarProjeto(Projeto $projeto){
        if(auth()->user()->id !== $projeto['user_id']){
            return redirect('/');
        }

        $tarefas = $projeto->projetoTarefas;
        $tags = Tag::all();

        return view('editarProjeto', compact('projeto', 'tarefas', 'tags'));
    }

    public function atualizarProjeto(Projeto $projeto, Request $request){
        if (!$projeto->isOwnedBy(auth()->user())) {
            return redirect('/');
        }
        

        $dados = $request->validate([
            'nome' => 'required',
            'descricao' => 'required',
        ]);

        $dados['nome'] = strip_tags($dados['nome']);
        $dados['descricao'] = strip_tags($dados['descricao']);

        
        $projeto->update($dados);

        return redirect('/');
    }

    
    public function detalheProjeto($id){
        
        $projeto = auth()->user()->usersProjetos()->findOrFail($id);
        

        $tarefas = $projeto->projetoTarefas; 
        $tags = Tag::all();
        
        return view('detalhesProjeto', compact('projeto', 'tarefas', 'tags'));
    }

    public function deletarProjeto(Projeto $projeto){
        if(auth()->user()->id === $projeto['user_id']){
            $projeto->delete();
        }
        return redirect('/');
    }

    public function dashboard()
    {
        
        $user = auth()->user();
        $projetos = $user->usersProjetos()->get() ?? collect();
    
        // Calcular o número total de projetos
        $numeroProjetos = $projetos->count();

        $tarefasAgrupadas = [];

        foreach ($projetos as $projeto) {
            foreach ($projeto->projetoTarefas as $tarefa) {
                // Agrupar tarefas por projeto
                if (!isset($tarefasAgrupadas[$projeto->id])) {
                    $tarefasAgrupadas[$projeto->id] = [
                        'projeto' => $projeto,
                        'tarefas' => [],
                    ];
                }
    
                // Agrupar tarefas por status
                if (!isset($tarefasAgrupadas[$projeto->id]['tarefas'][$tarefa->status])) {
                    $tarefasAgrupadas[$projeto->id]['tarefas'][$tarefa->status] = [];
                }
    
               
                $tarefasAgrupadas[$projeto->id]['tarefas'][$tarefa->status][] = $tarefa;
    
              
                foreach ($tarefa->tags as $tag) {
                    if (!isset($tarefasAgrupadas[$projeto->id]['tarefas'][$tarefa->status]['tags'][$tag->nome])) {
                        $tarefasAgrupadas[$projeto->id]['tarefas'][$tarefa->status]['tags'][$tag->nome] = [];
                    }
                    $tarefasAgrupadas[$projeto->id]['tarefas'][$tarefa->status]['tags'][$tag->nome][] = $tarefa;
                }
    
                // Agrupar tarefas por prazo
                $prazo = $tarefa->prazo; 
                if (!isset($tarefasAgrupadas[$projeto->id]['tarefas'][$tarefa->status]['prazo'][$prazo])) {
                    $tarefasAgrupadas[$projeto->id]['tarefas'][$tarefa->status]['prazo'][$prazo] = [];
                }
                $tarefasAgrupadas[$projeto->id]['tarefas'][$tarefa->status]['prazo'][$prazo][] = $tarefa;
            }
        }
    
        
        return view('home', [
            'projetos' => $projetos,
            'numeroProjetos' => $numeroProjetos,
            'tarefasAgrupadas' => $tarefasAgrupadas,
        ]);

    }
}

