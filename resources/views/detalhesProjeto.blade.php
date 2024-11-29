<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <h2>{{ $projeto->nome }}</h2>
    <p>{{ $projeto->descricao }}</p>
    <a href="/">Voltar para Projetos</a>

    @if (\Session::has('task_deleted_message'))
        <div class="alert alert-success"><ul><li>{!! \Session::get('task_deleted_message') !!}</li></ul></div>
    @endif

  

    <h2>Tarefas</h2>
    @if($tarefas->isEmpty())
    <p>Não há tarefas para este projeto.</p>
@else
@foreach ($tarefas as $tarefa)
    <div>
        <h3>{{ $tarefa->nome }}</h3>
        <p>{{ $tarefa->descricao }}</p>
        <p>Status: {{ $tarefa->status }}</p>
        <p>Prazo: {{ $tarefa->prazo }}</p>
        
        <form action="/tarefas/{{ $tarefa->id }}/editar" method="POST">
            @csrf
            @method('PUT')
            <input type="text" name="nome" value="{{ $tarefa->nome }}" required>
            <textarea name="descricao" required>{{ $tarefa->descricao }}</textarea>
            <select name="status">
                <option value="pendente" {{ $tarefa->status === 'pendente' ? 'selected' : '' }}>Pendente</option>
                <option value="em andamento" {{ $tarefa->status === 'em andamento' ? 'selected' : '' }}>Em Andamento</option>
                <option value="concluida" {{ $tarefa->status === 'concluida' ? 'selected' : '' }}>Concluída</option>
            </select>
            <input type="date" name="prazo" value="{{ $tarefa->prazo }}" >
            <button type="submit">Salvar Alterações</button>
        </form>
       <form action="/tarefas/{{$tarefa->id}}" method="POST">
        @csrf
        @method('DELETE')
            <button type="submit">Deletar</button>
       </form>
    </div>
@endforeach
@endif
    
</body>
</html>