<?php
session_start();

// Trava para aceitar apenas requisições POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../views/login.php');
    exit();
}

require_once '../config/db.php';

// Captura e sanitização básica dos inputs
$email = trim($_POST['email'] ?? '');
$senha = $_POST['senha'] ?? '';

// Validação de campos obrigatórios
if (empty($email) || empty($senha)) {
    $_SESSION['erro_login'] = "Ops, Email ou Senha inválidos.";
    header("Location: ../views/login.php");
    exit();
}

try {
    // Consulta para buscar o usuário pelo e-mail
    $stmt = $pdo->prepare("SELECT id, nome, senha FROM usuarios WHERE email = :email");
    $stmt->execute([':email' => $email]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    // Validação de credenciais com hash seguro
    if ($usuario && password_verify($senha, $usuario['senha'])) {
        // Previne Session Fixation
        session_regenerate_id(true);

        $_SESSION['usuario_id'] = $usuario['id'];
        $_SESSION['usuario_nome'] = $usuario['nome'];

        header("Location: ../views/dashboard.php");
        exit();
    } else {
        $_SESSION['erro_login'] = "Email ou senha incorretos.";
        header("Location: ../views/login.php");
        exit();
    }
} catch (PDOException $e) {
    $_SESSION['erro_login'] = "Erro interno ao processar a autenticação.";
    header("Location: ../views/login.php");
    exit();
}