<?php
session_start();

// Trava de segurança: Redireciona caso não esteja logado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

require_once '../config/db.php';

$usuario_id = $_SESSION['usuario_id'];

// Captura filtros de status e busca
$filtroStatus = $_GET['status'] ?? null;
$filtroBusca = trim($_GET['busca'] ?? '');

// 1. Construção dinâmica da consulta SQL dos serviços do usuário
$sql = "
    SELECT s.id, s.descricao, s.status_do_servico, s.valor, u.nome as usuario_nome 
    FROM servicos s 
    JOIN usuarios u ON s.usuario_id = u.id 
    WHERE s.usuario_id = :usuario_id
";

$paramsServicos = [':usuario_id' => $usuario_id];

// Aplicação do filtro de Status
if (!empty($filtroStatus)) {
    $sql .= " AND s.status_do_servico = :status_do_servico";
    $paramsServicos[':status_do_servico'] = $filtroStatus;
}

// Aplicação do filtro de Busca
if (!empty($filtroBusca)) {
    $sql .= " AND s.descricao LIKE :busca";
    $paramsServicos[':busca'] = '%' . $filtroBusca . '%';
}

$stmtServicos = $pdo->prepare($sql);
$stmtServicos->execute($paramsServicos);
$servicos = $stmtServicos->fetchAll(PDO::FETCH_ASSOC);


// 2. Consulta para valor total acumulado dos serviços finalizados
$stmtValor = $pdo->prepare("SELECT SUM(s.valor) as total FROM servicos WHERE usuario_id = :usuario_id AND status_do_servico = 'Finalizado'");
$stmtValor->execute([':usuario_id' => $usuario_id]);
$total = $stmtValor->fetch(PDO::FETCH_ASSOC);

$valorTotal = (float) ($total['total'] ?? 0);
$valorComissao = $valorTotal * 0.10;

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

    <!-- Exibição de Mensagens de Sessão (Suporta 'sucesso' e 'success') -->
    <?php if (isset($_SESSION['sucesso'])): ?>
        <p style="color: green;"><?= htmlspecialchars($_SESSION['sucesso']); unset($_SESSION['sucesso']); ?></p>
    <?php elseif (isset($_SESSION['success'])): ?>
        <p style="color: green;"><?= htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?></p>
    <?php endif; ?>

    <?php if (isset($_SESSION['erro'])): ?>
        <p style="color: red;"><?= htmlspecialchars($_SESSION['erro']); unset($_SESSION['erro']); ?></p>
    <?php endif; ?>

    <main>
        <div>
            <a href="cadastrar_servico.php">Adicionar novo serviço</a>
            <br><br>

            <p><strong>Valor Total Acumulado:</strong> R$ <?= number_format($valorTotal, 2, ",", "."); ?></p>
            <p><strong>Comissão (10%):</strong> R$ <?= number_format($valorComissao, 2, ",", "."); ?></p>
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
                            <th>Comissão</th>
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
                                        <a href="editar_servico.php?id=<?= urlencode($servico['id']); ?>">Alterar</a> |
                                        <a href="../actions/fazer_excluir_servico.php?id=<?= urlencode($servico['id']); ?>" onclick="return confirm('Tem certeza que deseja excluir?');">Excluir</a> |
                                        <a href="../actions/fazer_finalizar_servico.php?id=<?= urlencode($servico['id']); ?>">Finalizar</a>
                                    <?php else: ?>
                                        <span>Sem ações disponíveis</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($servico['status_do_servico'] === 'Finalizado'): ?>
                                        R$ <?= number_format($servico['valor'] * 0.10, 2, ",", "."); ?>
                                    <?php else: ?>
                                        <span>Pendente</span>
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