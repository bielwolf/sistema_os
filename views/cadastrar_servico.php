<?php
session_start();

// Trava de segurança para impedir acesso direto à página sem login
if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit();
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

    <!-- Exibição de mensagens flash da sessão -->
    <?php if (isset($_SESSION['erro'])): ?>
        <p style="color: red;"><?= htmlspecialchars($_SESSION['erro']); ?></p>
        <?php unset($_SESSION['erro']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['sucesso'])): ?>
        <p style="color: green;"><?= htmlspecialchars($_SESSION['sucesso']); ?></p>
        <?php unset($_SESSION['sucesso']); ?>
    <?php elseif (isset($_SESSION['success'])): ?>
        <p style="color: green;"><?= htmlspecialchars($_SESSION['success']); ?></p>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <form action="../actions/fazer_cadastro_servico.php" method="POST">
        <div>
            <label for="descricao">Descrição:</label>
            <textarea name="descricao" id="descricao" placeholder="Escreva a descrição do serviço..." required></textarea>
        </div>
        <div>
            <label for="valor">Valor (R$):</label>
            <input type="number" id="valor" name="valor" step="0.01" min="0.01" placeholder="0.00" required>
        </div>
        <button type="submit">Salvar Serviço</button>

        <a href="dashboard.php">Voltar</a>
    </form>
</body>
</html>