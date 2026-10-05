<?php
session_start();

// Trava de segurança: Redireciona caso não esteja logado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

require_once '../config/db.php';

$usuario_id = $_SESSION['usuario_id'];

// 1. Consulta dos serviços do usuário
$stmtServicos = $pdo->prepare("
    SELECT s.id, s.descricao, s.status_do_servico, s.valor, u.nome as usuario_nome 
    FROM servicos s 
    JOIN usuarios u ON s.usuario_id = u.id 
    WHERE s.usuario_id = :usuario_id
");
$stmtServicos->execute([':usuario_id' => $usuario_id]);
$servicos = $stmtServicos->fetchAll(PDO::FETCH_ASSOC);

// 2. Consulta para valor total acumular na visão do dashboard
$stmtValor = $pdo->prepare('SELECT SUM(s.valor) as total FROM servicos WHERE usuario_id = :usuario_id');
$stmtValor->execute([':usuario_id' => $usuario_id]);
$total = $stmtValor->fetch(PDO::FETCH_ASSOC);
$valorTotal = $total['total'] ?? 0;

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

    <h1>Bem-vindo, <?= htmlspecialchars($_SESSION['usuario_nome'] ?? 'Usuário'); ?>!</h1>
    <p>Data atual: <?= $dataAtual; ?></p>

    <!-- Exibição de Mensagens de Sessão -->
    <?php if (isset($_SESSION['sucesso'])): ?>
        <p style="color: green;"><?= $_SESSION['sucesso']; unset($_SESSION['sucesso']); ?></p>
    <?php endif; ?>

    <?php if (isset($_SESSION['erro'])): ?>
        <p style="color: red;"><?= $_SESSION['erro']; unset($_SESSION['erro']); ?></p>
    <?php endif; ?>

    <main>
        <div>
            <a href="cadastrar_servico.php">Adicionar novo serviço</a>
            <br><br>

            <p><strong>Valor Total Acumulado:</strong> R$ <?= number_format($valorTotal, 2, ",", "."); ?></p>

            <h3>Serviços cadastrados</h3>

            <?php if (!empty($servicos)): ?>
                <table border="1" cellpadding="8" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Usuário</th>
                            <th>Descrição</th>
                            <th>Valor</th>
                            <th>Status</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($servicos as $servico): ?>
                            <tr>
                                <td><?= htmlspecialchars($servico['id']); ?></td>
                                <td><?= htmlspecialchars($servico['usuario_nome']); ?></td>
                                <td><?= htmlspecialchars($servico['descricao']); ?></td>
                                <td>R$ <?= number_format($servico['valor'], 2, ",", "."); ?></td>
                                <td><?= htmlspecialchars($servico['status_do_servico']); ?></td>
                                <td>
                                    <?php if ($servico['status_do_servico'] === 'Pendente'): ?> 
                                        <a href="editar_servico.php?id=<?= $servico['id']; ?>">Alterar</a> |
                                        <a href="../actions/fazer_excluir_servico.php?id=<?= $servico['id']; ?>" onclick="return confirm('Tem certeza?');">Excluir</a> |
                                        <a href="../actions/fazer_finalizar_servico.php?id=<?= $servico['id']; ?>">Finalizar</a>
                                    <?php else: ?>
                                        <span>Sem ações disponíveis</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>Nenhum serviço encontrado.</p>
            <?php endif; ?>
        </div>
    </main>

    <br>
    <a href="../actions/fazer_logout.php">Sair</a>

</body>
</html>