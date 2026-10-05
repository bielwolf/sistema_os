<?php

session_start();

// Trava de segurança para impedir acesso direto à página sem login
if(!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit();
}

// Exibir mensagem de erro se houver campo vazio ou valor inválido
if (isset($_SESSION['erro'])) {
    echo '<p style="color: red;">' . htmlspecialchars($_SESSION['erro']) . '</p>';

    // Limpar a mensagem de erro após exibi-la
    unset($_SESSION['erro']);
}

// Exibir mensagem de sucesso se o serviço foi cadastrado com sucesso
if (isset($_SESSION['success'])) {
    echo '<p style="color: green;">' . htmlspecialchars($_SESSION['success']) . '</p>';

    unset($_SESSION['success']);
}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Serviços - Sistema OS</title>
</head>
<body>
    <h1>Cadastro de Serviços</h1>
    <form action="../actions/fazer_cadastro_servicos.php" method="POST">
        <div>
            <label for="descricao">Descrição:</label>
            <textarea name="descricao" id="descricao" placeholder="Escreva a descrição do serviço..."></textarea>
        </div>
        <div>
            <label for="valor">Valor:</label>
            <input type="number" id="valor" step="0.01" name="valor" min="0.01" placeholder="0.00">
        </div>
        <button type="submit">Salvar Serviço</button>

        <a href="dashboard.php">Voltar</a>
    </form>
</body>
</html>





