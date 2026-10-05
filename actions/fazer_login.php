<?php
session_start();

require_once '../config/db.php';

// Processar o Login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']) ?? '';
    $senha = $_POST['senha']     ?? '';

    // Consulta para verificar se o usuário existe no banco de dados
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = :email");
    $stmt->execute([':email' => $email]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuario && password_verify($senha, $usuario['senha'])) {
        // Login bem-sucedido

        $_SESSION['usuario_id'] = $usuario['id'];
        $_SESSION['usuario_nome'] = $usuario['nome'];

        header("Location: ../views/dashboard.php");
        exit();

    } else {
        // Login falhou
        $_SESSION['erro_login'] = "Email ou senha incorretos.";
        header("Location: ../views/login.php");
        exit();
    }


}