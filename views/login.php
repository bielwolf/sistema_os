<?php
session_start();
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

    <?php if (isset($_SESSION['erro_login'])): ?>
        <p style="color: red;"><?php echo $_SESSION['erro_login']; ?></p>
        <?php unset($_SESSION['erro_login']); ?>
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
    
</body>
</html>
