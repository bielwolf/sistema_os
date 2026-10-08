<?php
session_start();

if (isset($_SESSION['usuario_id'])) {
    header('Location: dashboard.php');
    exit();
}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistema OS</title>
</head>
<body>

    <h2>Acesso ao sistema</h2>

    <!-- Exibição de mensagens de erro de login -->
    <?php if (isset($_SESSION['erro_login'])): ?>
        <p style="color: red;"><?= htmlspecialchars($_SESSION['erro_login']); ?></p>
        <?php unset($_SESSION['erro_login']); ?>
    <?php endif; ?> 

    <!-- Exibição de mensagens de sucesso de cadastro -->
    <?php if (isset($_SESSION['success'])): ?>
        <p style="color: green;"><?= htmlspecialchars($_SESSION['success']); ?></p>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <main>
        <form action="../actions/fazer_login.php" method="POST" >
            <div>
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>
    
            <div>
                <label for="senha">Senha:</label>
                <input type="password" id="senha" name="senha" required>
            </div>
    
            <button type="submit">Login</button>
        </form>
    </main>

    <br>
    
</body>
</html>
