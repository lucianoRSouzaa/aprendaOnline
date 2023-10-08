<!DOCTYPE html>
<html>
<head>
    <title>Confirmação de Cadastro</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .email-container {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0px 0px 10px rgba(0,0,0,0.1);
        }
        .email-container h1 {
            color: #444;
        }
        .email-container p {
            color: #666;
        }
        .email-container a {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #3498db;
            color: #fff;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <h1>Bem-vindo à nossa plataforma!</h1>
        <p>Estamos muito felizes em te receber. Por favor, clique no link abaixo para confirmar seu endereço de e-mail:</p>
        <a href="{{ route('verification.verify', $token) }}">Confirmar meu e-mail</a>
    </div>
</body>
</html>
