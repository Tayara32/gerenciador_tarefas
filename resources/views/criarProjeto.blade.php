<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>

    <h1>Criar Projeto</h1>
    <form action="/criarProjeto" method="POST">
    @csrf
    <input type="text" name="nome" placeholder="Nome do projeto" >
    <textarea name="descricao" placeholder="Descrição do projeto" ></textarea>
    <button>Criar</button>


    </form>
    
</body>
</html>