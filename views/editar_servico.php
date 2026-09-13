<?php
session_start();

require_once('../config/db.php');

// Trava de segurança: usuário logado
if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit();
}

$usuario_id = $_SESSION['usuario_id'];
$id = $_GET['id'] ?? null;

// Valida se o ID é numérico e foi enviado
if(empty($id) || !is_numeric($id)) {
    $_SESSION['erro'] = 'Serviço inválido';
    header('Location: dashboard.php');
    exit();
}

try {
    // Busca o serviço garantido a propriedade do usuário logado
    $stmt = $pdo->prepare('SELECT id, descricao, valor FROM servicos WHERE id = :id AND usuario_id = :usuario_id');
    $stmt->execute([
        ':id' => $id,
        ':usuario_id' => $usuario_id
    ]);

    $servico = $stmt->fetch(PDO::FETCH_ASSOC);


    // Se o serviço não existe ou pertece a outro usuário
    if(!$servico) {
        $_SESSION['erro'] = 'Serviço não encontrado ou você não tem permissão para editá-lo.';
        header('Location: dashboard.php');
        exit();
    }

    } catch (PDOException $e) {
        $_SESSION['erro'] = 'Erro ao carregar dados do serviço';
        header('Location: dashboard.php');
        exit();
    }

    // Mensagens flash de erro da sessão
    if (isset($_SESSION['erro'])) {
        echo '<p style="color: red; ">' . htmlspecialchars($_SESSION['erro']) . '</p>';
        unset($_SESSION['erro']);
    }
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Serviço - Sistema OS</title>
</head>
<body>
    <h1>Editar Serviço</h1>

    <form action="../actions/fazer_editar_servico.php" method="POST">
        <input type="hidden" name="id" value="<?= htmlspecialchars($servico['id']); ?>">

        <div>
            <label for="descricao">Descrição:</label>
            <textarea name="descricao" id="descricao" required><?= htmlspecialchars($servico['descricao']); ?></textarea>
        </div>

        <div>
            <label for="valor">Valor:</label>
            <input type="number" id="valor" name="valor" step="0.01" min="0.01" value="<?= htmlspecialchars($servico['valor']); ?>" required>
        </div>

        <button type="submit">Salvar Alterações</button>
        <a href="dashboard.php">Cancelar</a>
    </form>
</body>
</html>