<?php
session_start();

require_once "../config/db.php";

// 1. Trava de segurança para usuários autenticados
if (!isset($_SESSION["usuario_id"])) {
    header("Location: ../views/login.php");
    exit();
}

$id = $_GET["id"] ?? null;
$usuario_id = $_SESSION["usuario_id"];

// 2. Validação da entrada
if (!empty($id) && is_numeric($id)) {
    try {
        // 3. Update com trava de segurança de dono e status atual
        $stmt = $pdo->prepare("UPDATE servicos 
                               SET status_do_servico = 'Finalizado' 
                               WHERE id = :id 
                                 AND usuario_id = :usuario_id 
                                 AND status_do_servico = 'Pendente'");

        $stmt->execute([
            ':id' => $id,
            ':usuario_id' => $usuario_id,
        ]);

        // 4. Verificação de impacto no banco
        if ($stmt->rowCount() > 0) {
            $_SESSION['sucesso'] = "Serviço finalizado com sucesso!";
        } else {
            $_SESSION['erro'] = "Não foi possível finalizar o serviço (registro não encontrado ou já finalizado).";
        }
    } catch (PDOException $e) {
        $_SESSION['erro'] = "Erro de banco de dados ao tentar finalizar o serviço.";
    }
} else {
    $_SESSION['erro'] = "Identificador de serviço inválido.";
}

// 5. Redirecionamento unificado
header("Location: ../views/dashboard.php");
exit();
