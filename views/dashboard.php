<?php
session_start();

// Trava de segurança: Verifica, e se o usuário não está logado redireciona para o login
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

require_once '../config/db.php';

// Consulta para buscar os serviços do usuário logado
$usuario_id = $_SESSION['usuario_id'];
$stmtServicos = $pdo->prepare("SELECT * FROM servicos WHERE usuario_id = :usuario_id");
$stmtServicos->execute([':usuario_id' => $usuario_id]);
$servicos = $stmtServicos->fetchAll(PDO::FETCH_ASSOC);

// Consulta para calcular o valor total dos serviços do usuário logado 
$stmtValor = $pdo->prepare('SELECT SUM(valor) as total FROM servicos WHERE usuario_id = :usuario_id');
$stmtValor->execute([':usuario_id' => $usuario_id]);
$total = $stmtValor->fetch(PDO::FETCH_ASSOC);
$valorTotal = $total['total'] ?? 0;

// Consulta para buscar os serviços pendentes do usuário logado
$stmtPendentes = $pdo->prepare('SELECT * FROM servicos WHERE usuario_id = :usuario_id AND status_do_servico = "Pendente"');
$stmtPendentes->execute([':usuario_id' => $usuario_id]);
$pendentes = $stmtPendentes->fetchAll(PDO::FETCH_ASSOC);

// Exibe a data atual
$dataAtual = date('d/m/Y');

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Sistema OS</title>
</head>
<body>

    <h1>Bem-vindo,  <?= htmlspecialchars($_SESSION['usuario_nome']); ?>!</h1>
    <p>Você está logado no sistema.</p>

    <main>
        <div>
            <h2>Conteúdo do Dashboard</h2>
            <p>Este é o conteúdo principal da página do dashboard.</p>

            <p>Data atual: <?= $dataAtual; ?></p>

            <h3>Serviços do usuário</h3>
            <?php if (!empty($servicos)): ?>
                <ul>
                    <?php foreach ($servicos as $servico): ?>
                        <li><?= htmlspecialchars($servico['descricao']); ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>Nenhum serviço encontrado.</p>
            <?php endif; ?>

            <h3>Total dos serviços</h3>
            <p>R$ <?= number_format($valorTotal, 2, ',', '.') ?></p>
            
            <?php if (!empty($pendentes)): ?>
                <p><?= count($pendentes) ?> serviços pendentes.</p>
            <?php endif; ?>
        </div>
    </main>
    
    <a href="../actions/fazer_logout.php">Sair</a>
</body>
</html>