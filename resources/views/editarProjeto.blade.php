<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <h1>Editar Projeto: {{ $projeto->nome }}</h1>

    <a href="/">Voltar para Projetos</a>

    <form action="/editar/{{$projeto->id}}" method="POST">
        @csrf
        @method('PUT')
        <input type="text" name="nome" value="{{$projeto->nome}}">
        <textarea name="descricao">{{$projeto->descricao}}</textarea>
        <button type="submit">Salvar Alterações</button>
    </form>

    <h2>Criar Nova Tarefa</h2>
    <form action="/projetos/{{ $projeto->id }}/tarefas/criar" method="POST">
        @csrf
        <input type="text" name="nome" placeholder="Título" required>
        <textarea name="descricao" placeholder="Descrição"></textarea>
        <label for="status">Status:</label>
        <select name="status">
            <option value="">Selecione um status</option>
            <option value="pendente">Pendente</option>
            <option value="concluida">Concluída</option>
            <option value="em andamento">Em andamento</option>
        </select>
        <label for="prazo">Prazo:</label>
        <input type="date" name="prazo" required>
        <button type="submit">Criar Tarefa</button>
    </form>

</body>
</html>

