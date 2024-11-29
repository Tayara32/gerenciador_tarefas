<?php

namespace App\Http\Controllers;

use App\Models\Projeto;
use App\Models\Tarefa;
use Illuminate\Container\Attributes\Tag;
use Illuminate\Http\Request;

class TarefaController extends Controller
{
    public function criarTarefa(Request $request, Projeto $projeto){
        $dados = $request->validate([
            'nome' => 'required',
            'descricao' => 'required',
            'prazo' => 'required',
            'tag_id' => 'nullable|exists:tags,id',
        ]);
    
        $dados['projeto_id'] = $projeto->id;
    
        $tarefa = Tarefa::create($dados);
    
        return redirect()->route('detalheProjeto', ['projeto' => $projeto->id]);
                      
    }
    public function editarTarefa(int $tarefa, Request $request){
        $dados = $request->validate([
            'nome' => 'required|string',
            'descricao' => 'nullable|string',
            'status' => 'required|in:pendente,em andamento,concluida',
            'prazo' => 'required',
            'tag_id' => 'nullable|exists:tags,id',
        ]);
        
    
        $task = Tarefa::findOrFail($tarefa);
        foreach($dados as $k => $v)
            $task->{$k} = $v;
        
            $task->save();
    
        return redirect()->route('detalheProjeto', ['projeto' => $task->projeto_id]);

       
    }
    public function adicionarTag(Request $request, Tarefa $tarefa)
    {
        $tag = Tag::firstOrCreate(['nome' => $request->input('tag_nome')]);

        $tarefa->tags()->attach($tag);

        return back()->with('success', 'Tag adicionada com sucesso!');
    }



    public function deletarTarefa(int $tarefa){
       
        $tarefa = Tarefa::findOrFail($tarefa);
        $tarefa->delete();
       
        return back()->with('task_deleted_message', 'Tarefa apagada com sucesso!');
    }
}
