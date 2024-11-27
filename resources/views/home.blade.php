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
    <p>Bem-vindo, {{ auth()->user()->name }}!</p>
  



    <form action="/criarProjeto" method="POST">
        @csrf
        <button>Novo Projeto</button>
    </form> 


    
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