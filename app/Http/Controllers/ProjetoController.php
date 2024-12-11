<?php

namespace App\Http\Controllers;

use App\Models\Projeto;
use App\Models\Tag;
use App\Models\Tarefa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; 

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
        
        $query = $user->usersProjetos()->join('tarefas', 'tarefas.projeto_id', '=', 'projetos.id')
        ->leftJoin('tarefa_tag', 'tarefa_tag.tarefa_id', '=', 'tarefas.id') 
        ->leftJoin('tags', 'tags.id', '=', 'tarefa_tag.tag_id')
        ->groupBy('projetos.id', 'projetos.nome', 'projetos.descricao', 'tags.nome', 'tarefas.status', 'tarefas.prazo', 'tarefas.nome')
        ->select([
            'projetos.id as projeto_id',
            'projetos.nome as projeto_nome',
            'projetos.descricao as projeto_descricao',
            'tags.nome as tag_nome',
            'tarefas.nome as tarefa_nome',
            'tarefas.prazo as prazo',
            'tarefas.status as status_nome',
            DB::raw('COUNT(tarefas.id) as tarefas')
        ]);
    

    $resultado = $query->get();

  
    $projetosAgrupados = $resultado->groupBy('projeto_id');

    $numeroProjetos = $projetosAgrupados->count();

  
    $tarefasAgrupadas = [];

    foreach ($projetosAgrupados as $projetoId => $dadosProjeto) {
        $projeto = $dadosProjeto->first(); 
        $tarefasAgrupadas[$projetoId] = [
            'projeto' => $projeto,
            'tarefas' => []
        ];

       
        foreach ($dadosProjeto as $tarefa) {
            $statusNome = $tarefa->status_nome; 
            $tagNome = $tarefa->tag_nome;
            $prazo = $tarefa->prazo;

            if (!isset($tarefasAgrupadas[$projetoId]['tarefas'][$statusNome])) {
                $tarefasAgrupadas[$projetoId]['tarefas'][$statusNome] = [];
            }

            if (!isset($tarefasAgrupadas[$projetoId]['tarefas'][$statusNome][$tagNome])) {
                $tarefasAgrupadas[$projetoId]['tarefas'][$statusNome][$tagNome] = [];
            }

            if (!isset($tarefasAgrupadas[$projetoId]['tarefas'][$statusNome][$tagNome][$prazo])) {
                $tarefasAgrupadas[$projetoId]['tarefas'][$statusNome][$tagNome][$prazo] = [];
            }
            $tarefasAgrupadas[$projetoId]['tarefas'][$statusNome][$tagNome][$prazo][] = $tarefa;
        }
    }

    return view('home', [
        'projetosAgrupados' => $tarefasAgrupadas,
        'numeroProjetos' => $numeroProjetos, 
        'projetos' => $user->usersProjetos
    ]);

        
      
    }
}

