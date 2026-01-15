<?php
require_once '../config/database.php';
$conn = Database::connect();

// cards
$clientes  = $conn->query("SELECT COUNT(*) total FROM clientes")->fetch_assoc()['total'];
$uploads   = $conn->query("SELECT COUNT(*) total FROM uploads")->fetch_assoc()['total'];
$produtos  = $conn->query("SELECT COUNT(*) total FROM produtos")->fetch_assoc()['total'];
$riscoAlto = $conn->query("SELECT COUNT(*) total FROM produtos WHERE risco='alto'")->fetch_assoc()['total'];

// últimos uploads
$ultimos = $conn->query("
    SELECT 
        u.id,
        u.data_upload,
        c.nome_fantasia,
        COUNT(p.id) total_produtos,
        SUM(p.risco='alto') risco_alto
    FROM uploads u
    JOIN clientes c ON c.id = u.cliente_id
    LEFT JOIN produtos p ON p.upload_id = u.id
    GROUP BY u.id
    ORDER BY u.data_upload DESC
    LIMIT 10
");
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title>Painel do Contador | ClassiNCM</title>

<!-- Bootstrap -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- CSS próprio -->
<link rel="stylesheet" href="assets/css/dashboard.css">
</head>
<body>

<div class="container-fluid mt-4">

    <!-- TÍTULO -->
    <div class="row mb-4">
        <div class="col">
            <h3>📊 Dashboard do Contador</h3>
            <p class="text-muted">
                Visão geral dos clientes, importações e riscos fiscais identificados.
            </p>
        </div>
    </div>

    <!-- CARDS KPI -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card kpi">
                <div class="card-body">
                    <span class="label">Clientes</span>
                    <strong><?= $clientes ?></strong>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card kpi">
                <div class="card-body">
                    <span class="label">Uploads</span>
                    <strong><?= $uploads ?></strong>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card kpi">
                <div class="card-body">
                    <span class="label">Produtos</span>
                    <strong><?= $produtos ?></strong>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card kpi risco">
                <div class="card-body">
                    <span class="label">Risco Alto</span>
                    <strong><?= $riscoAlto ?></strong>
                </div>
            </div>
        </div>
    </div>

    <!-- ÚLTIMOS UPLOADS -->
    <div class="row">
        <div class="col">

            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        ⏱ Últimos Uploads
                    </h5>
                </div>

                <div class="card-body p-0">

                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Cliente</th>
                                    <th>Data</th>
                                    <th class="text-center">Produtos</th>
                                    <th class="text-center">Risco Alto</th>
                                    <th class="text-center">Ação</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($u = $ultimos->fetch_assoc()): ?>
                                    <tr class="<?= $u['risco_alto'] > 0 ? 'linha-alerta' : '' ?>">
                                        <td>
                                            <strong><?= htmlspecialchars($u['nome_fantasia']) ?></strong>
                                        </td>
                                        <td>
                                            <?= date('d/m/Y H:i', strtotime($u['data_upload'])) ?>
                                        </td>
                                        <td class="text-center">
                                            <?= $u['total_produtos'] ?>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge <?= $u['risco_alto'] > 0 ? 'bg-danger' : 'bg-success' ?>">
                                                <?= $u['risco_alto'] ?>
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <a href="uploads/produtos.php?id=<?= $u['id'] ?>"
                                               class="btn btn-sm btn-outline-primary">
                                                🔍 Ver produtos
                                            </a>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>

                                <?php if ($ultimos->num_rows == 0): ?>
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-4">
                                            Nenhum upload encontrado.
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>

        </div>
    </div>

    <!-- RODAPÉ -->
    <div class="row mt-4">
        <div class="col text-center text-muted small">
            ClassiNCM • Painel gerencial
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/dashboard.js"></script>

</body>
</html>
