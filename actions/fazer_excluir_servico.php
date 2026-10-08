<?php

session_start();

require_once '../config/db.php';

// Trava de segurança: Garante que apenas usuários autenticados acessem a action
if (!isset($_SESSION['usuario_id'])) {
    header('Location: ../views/login.php');
    exit();
}

$id = $_GET['id'] ?? null;
$usuario_id = $_SESSION['usuario_id'];

// Valida se o ID foi informado e se é um número válido
if (!empty($id) && is_numeric($id)) {

    try {
        // Remove o serviço garantindo o escopo de segurança
        $stmt = $pdo->prepare('DELETE FROM servicos WHERE id = :id AND usuario_id = :usuario_id AND status_do_servico = "Pendente"');
        $stmt->execute([
            ':id' => $id,
            ':usuario_id' => $usuario_id,
        ]);

        $stmt->rowCount() > 0 ? $_SESSION['success'] = 'Serviço excluído com sucesso!' 
        : $_SESSION['error'] = 'Erro ao tentar excluir o serviço.';

        header('Location: ../views/dashboard.php');
        exit();

    } catch (PDOException $e) {
        // Trata falhas de bancos de dados sem export detalhes sensíveis 
        $_SESSION['erro'] = 'Erro ao tentar apagar com sucesso.';
        header('Location: ../views/dashboard.php');
        exit(); 
    }
} else {
    // Redireciona caso o ID fornecido na URL seja inválido
    $_SESSION['erro'] = 'ID de serviço inválido.';
    header('Location: ../views/dashboard.php');
    exit();
} 

?>