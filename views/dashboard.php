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
$stmtServicos = $pdo->prepare("SELECT s.id, s.descricao, s.status_do_servico, s.valor, u.nome as usuario_nome FROM servicos s JOIN usuarios u ON s.usuario_id = u.id WHERE s.usuario_id = :usuario_id");
$stmtServicos->execute([':usuario_id' => $usuario_id]);
$servicos = $stmtServicos->fetchAll(PDO::FETCH_ASSOC);

// Consulta para calcular o valor total dos serviços do usuário logado 
$stmtValor = $pdo->prepare('SELECT SUM(s.valor) as total FROM servicos s JOIN usuarios u ON s.usuario_id = u.id WHERE s.usuario_id = :usuario_id');
$stmtValor->execute([':usuario_id' => $usuario_id]);
$total = $stmtValor->fetch(PDO::FETCH_ASSOC);
$valorTotal = $total['total'] ?? 0;

// Consulta para buscar os serviços pendentes do usuário logado
$stmtPendentes = $pdo->prepare('SELECT * FROM servicos s JOIN usuarios u ON s.usuario_id = u.id WHERE s.usuario_id = :usuario_id AND s.status_do_servico = "Pendente"');
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

    <h1>Bem-vindo, <?= htmlspecialchars($_SESSION['usuario_nome']); ?>!</h1>
    <p>Você está logado no sistema.</p>

    <main>
        <div>
            <h2>Conteúdo do Dashboard</h2>
            <p>Este é o conteúdo principal da página do dashboard.</p>

            <p>Data atual: <?= $dataAtual; ?></p>

            <h3>Serviços do usuário</h3>
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
                                    <a href="editar_servico.php?id=<?= $servico['id']; ?>">Alterar</a>
                                    <a href="../actions/fazer_excluir_servico.php?id=<?= $servico['id']; ?>"
                                        onclick="return confirm('Tem certeza?');">Excluir</a>
                                    <a href="cadastrar_servico.php">Adicionar novo serviço</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>Nenhum serviço encontrado.</p>
            <?php endif; ?>
    </main>

    <a href="../actions/fazer_logout.php">Sair</a>
    </div>
</body>

</html>