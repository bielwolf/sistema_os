<?php
session_start();

// Trava de segurança: Verifica, e se o usuário não está logado redireciona para o login
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Sistema OS</title>
</head>
<body>

    <h1>Bem-vindo,  <?= htmlspecialchars($_SESSION['usuario_nome']); ?>!</h1>
    <p>Você está logado no sistema.</p>
    
    <a href="../actions/fazer_logout.php">Sair</a>
</body>
</html>