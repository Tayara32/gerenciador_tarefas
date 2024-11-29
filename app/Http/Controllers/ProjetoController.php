<?php

namespace App\Http\Controllers;

use App\Models\Projeto;
use App\Models\Tag;
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
        
        return view('detalhesProjeto', compact('projeto', 'tarefas'));
    }

    public function deletarProjeto(Projeto $projeto){
        if(auth()->user()->id === $projeto['user_id']){
            $projeto->delete();
        }
        return redirect('/');
    }
}

