<?php

session_start();

// Trava de segurança para impedir acesso direto à página sem login
if (!isset($_SESSION['usuario_id'])) {
    header('Location: ../views/login.php');
    exit();
}

// Verifica se o método de requisição é POST, caso contrário redireciona para a página de cadastro
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../views/cadastrar_servico.php');
    exit();
}

require_once '../config/db.php';

// Recebe e limpa os dados do formulário
$descricao = trim($_POST['descricao'] ?? '');
$valor = $_POST['valor'] ?? '';
$usuario_id = $_SESSION['usuario_id'];

// Validação dos campos recebidos
if (empty($descricao) || !is_numeric($valor) ||$valor <= 0 || empty($usuario_id)) {
    $_SESSION['erro'] = "Preencha a descrição e um valor válido para o serviço.";
    header("Location: ../views/cadastrar_servico.php");
    exit();
} 

// Inserção do serviço no banco de dados
try {
    $stmt = $pdo->prepare(
        "INSERT INTO servicos (descricao, valor, status_do_servico, usuario_id) VALUES(:descricao, :valor, 'Pendente', :usuario_id)");
    $stmt->execute([
        ':descricao' => $descricao,
        ':valor' => $valor,
        ':usuario_id' => $usuario_id,
    ]);

    $_SESSION['success'] = "Serviço cadastrado com sucesso!";
    header("Location: ../views/dashboard.php");
    exit();

} catch (PDOException $e) {
    // Em caso de erro, redireciona para a página de cadastro com a mensagem de erro
    $_SESSION['erro'] = "Erro ao cadastrar o serviço no banco de dados." ;
    header("Location: ../views/cadastrar_servico.php");
    exit();
}

?>