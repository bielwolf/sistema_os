<?php
session_start();

require_once('../config/db.php');


// Trava de segurança: apenas usuários logados e requisições POST
if (!isset($_SESSION['usuario_id'])) {
    header('Location: ../views/login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../views/cadastrar_servico.php');
    exit();
}

// Recebe os dados sanitizado
$id = $_POST['id'] ?? null;
$descricao = trim($_POST['descricao'] ?? '');
$valor = $_POST['valor'] ?? '';
$usuario_id = $_SESSION['usuario_id'];

// Validação dos campos obrigatórios
if (empty($id) || !is_numeric($id) || empty($descricao) || !is_numeric($valor) && $valor <= 0) {
    $_SESSION['erro'] = 'Preencha todos os campos com valores válidos.';
    header('Location: ../views/editar_servico.php?id' . urlencode($id));
    exit();

}
    
try{
    // Executa a atualização garantindo a propriedade do registro
    $stmt = $pdo->prepare('UPDATE servicos SET descricao = :descricao, valor = :valor WHERE id = :id AND usuario_id = :usuario_id');
    $stmt-> execute([
        ':descricao' => $descricao,
        ':valor' => $valor,
        ':id' => $id,
        ':usuario_Id' => $usuario_id,
    ]);

    $_SESSION['sucesso'] = 'Serviço atualizado com sucesso! ';
    header('Location: ../views/dashboard.php');
    exit();

} catch (PDOException $e) {
    $_SESSION['erro'] = 'Erro ao atualizar o serviço no banco de dados.';
    header('Location: ../views/editar_servico.php?id=' . urlencode($id));
    exit();
}


?>