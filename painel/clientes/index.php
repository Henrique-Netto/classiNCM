<?php
require_once '../../config/database.php';

$conn = Database::connect();
$clientes = $conn->query("
    SELECT c.id, c.razao_social, c.cnpj,
           COUNT(u.id) AS total_uploads
    FROM clientes c
    LEFT JOIN uploads u ON u.cliente_id = c.id
    GROUP BY c.id
    ORDER BY c.razao_social
");
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Painel | Clientes - ClassiNCM</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- CSS próprio -->
    <link rel="stylesheet" href="assets/css/clientes.css">
</head>
<body>

<div class="container-fluid mt-4">

    <!-- TÍTULO -->
    <div class="row mb-3">
        <div class="col">
            <h3 class="page-title">
                👥 Clientes
            </h3>
            <p class="text-muted">
                Lista de clientes cadastrados e quantidade de importações realizadas.
            </p>
        </div>
    </div>

    <!-- CARD TABELA -->
    <div class="row">
        <div class="col">

            <div class="card shadow-sm">
                <div class="card-body p-0">

                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Razão Social</th>
                                    <th>CNPJ</th>
                                    <th class="text-center">Uploads</th>
                                    <th class="text-center">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($c = $clientes->fetch_assoc()): ?>
                                    <tr>
                                        <td>
                                            <strong>
                                                <?= htmlspecialchars($c['razao_social']) ?>
                                            </strong>
                                        </td>
                                        <td class="text-muted">
                                            <?= htmlspecialchars($c['cnpj']) ?>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-primary">
                                                <?= $c['total_uploads'] ?>
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <a href="detalhe.php?id=<?= $c['id'] ?>"
                                               class="btn btn-outline-primary btn-sm">
                                                🔍 Ver uploads
                                            </a>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
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
            ClassiNCM • Painel de clientes
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/clientes.js"></script>

</body>
</html>
