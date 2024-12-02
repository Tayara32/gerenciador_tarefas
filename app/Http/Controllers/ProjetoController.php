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
        if(auth()->user()->id !== $projeto['user_id']){
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
        
        $projeto = Projeto::findOrFail($id);

       
        if ($projeto->user_id != auth()->id()) {
            return redirect('/');
        }

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

    public function dashboard(Projeto $projeto)
    {
        if(auth()->user()->id !== $projeto['user_id']){
            return redirect('/');
        }
        // Pegar os projetos do usuário autenticado
        $projetos = auth()->user()->projetos;

        // Calcular o número total de projetos
        $numeroProjetos = $projetos->count();
        dd($numeroProjetos);

        // Calcular a quantidade de tarefas por status
        $tarefasPorStatus = [];
        $tarefasPorTags = [];

        foreach ($projetos as $projeto) {
            foreach ($projeto->tarefas as $tarefa) {
                // Agrupar tarefas por status
                if (!isset($tarefasPorStatus[$tarefa->status])) {
                    $tarefasPorStatus[$tarefa->status] = 0;
                }
                $tarefasPorStatus[$tarefa->status]++;

                // Agrupar tarefas por tags
                foreach ($tarefa->tags as $tag) {
                    if (!isset($tarefasPorTags[$tag->nome])) {
                        $tarefasPorTags[$tag->nome] = 0;
                    }
                    $tarefasPorTags[$tag->nome]++;
                }
            }
        }

        // Retornar a view com os dados do dashboard
        return view('home', [
            'projetos' => $projetos,
            'numeroProjetos' => $numeroProjetos,
            'tarefasPorStatus' => $tarefasPorStatus,
            'tarefasPorTags' => $tarefasPorTags,
        ]);

    }
}

