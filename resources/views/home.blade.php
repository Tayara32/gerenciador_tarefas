<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    @auth
    <!--Mostrará algo se o utilizador estiver logado-->
    <h1>Bem-vindo, {{ auth()->user()->name }}!</h1>

    <div style="border: 3px solid rgb(129, 126, 126); padding: 20px;">
        <h2>Dashboard</h2>

        <form action="/dashboard"  method="GET">
            @csrf
            <!-- Número total de projetos -->
            @isset($numeroProjetos)
            <p><strong>Total de Projetos:</strong> {{ $numeroProjetos }}</p>
            @endisset
            <!-- Quantidade de tarefas por status -->
            <h3>Tarefas por Status:</h3>
            <ul>
                @isset($tarefasPorStatus)
                @foreach ($tarefasPorStatus as $status => $quantidade)
                    <li><strong>{{ ucfirst($status) }}:</strong> {{ $quantidade }} tarefa(s)</li>
                @endforeach
                @endisset
            </ul>
            <!-- Quantidade de tarefas por tag -->
            <h3>Tarefas por Tag:</h3>
            <ul>
                @isset($tarefasPorTags)
                @foreach ($tarefasPorTags as $tag => $quantidade)
                    <li><strong>{{ $tag }}:</strong> {{ $quantidade }} tarefa(s)</li>
                @endforeach
                @endisset
            </ul>
        </form>
    </div>
    
    <form action="/criarProjeto" method="GET">
        @csrf
        <button type="submit">Novo Projeto</button>
    </form> 
    <div style="border: 3px solid rgb(129, 126, 126); ">
        <h2>Seus Projetos</h2>

      
        <form action="/criarProjeto" method="POST">

            @if($projetos->isEmpty())
                <p>Você ainda não tem projetos. Crie um novo projeto para começar!</p>
            @endif
            @foreach ($projetos as $projeto)
            <div style="background-color: rgb(197, 183, 183); padding: 10px; margin:10px;">
                <h3>{{$projeto['nome']}} </h3>
                {{$projeto['descricao']}}
                <p><a href="/editar/{{$projeto->id}}">Editar</a></p>
                <p><a href="/detalhe/{{$projeto->id}}">Detalhes</a></p>
                <form action="/deletarProjeto/{{$projeto->id}}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button>Deletar</button>
                </form>
            </div>
            @endforeach
        </form>
    </div>

    <form action="/logout" method="POST">
        @csrf
        <button>Log out</button>
    </form>

    @else
    <div style="border: 3px solid black; ">
        <h2>Registo</h2>
        <form action="{{url('registo')}}" method="POST">
            @csrf
            <input name="name" type="text" placeholder="nome">
            <input name="email" type="text" placeholder="email">
            <input name="password" type="password" placeholder="password">
            <button>Registar</button>
        </form>
        @if($errors->any())
            <h4>{{$errors->first()}}</h4>
        @endif
    </div>
    <div style="border: 3px solid black; ">
        <h2>Login</h2>
        <form action="/login" method="POST">
            @csrf
            <input name="loginemail" type="text" placeholder="email">
            <input name="loginpassword" type="password" placeholder="password">
            <button>Log in</button>
        </form>
    </div>
        
    @endauth

    
</body>
</html>