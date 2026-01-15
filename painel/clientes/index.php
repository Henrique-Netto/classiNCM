<?php
$titulo = 'Clientes';

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../layout/header.php';
require_once __DIR__ . '/../../layout/sidebar.php';

$conn = Database::connect();

$clientes = $conn->query("
    SELECT 
        c.id,
        c.razao_social,
        c.cnpj,
        COUNT(u.id) AS total_uploads
    FROM clientes c
    LEFT JOIN uploads u ON u.cliente_id = c.id
    GROUP BY c.id
    ORDER BY c.razao_social
");
?>

<div class="content">
    <div class="container-fluid mt-4">

        <!-- TÍTULO -->
        <div class="row mb-3">
            <div class="col">
                <h3 class="page-title">
                    👥 Clientes
                </h3>
                <p class="text-muted">
                    Lista de clientes cadastrados e total de importações realizadas.
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
                                                <a href="<?= BASE_URL ?>/painel/clientes/detalhe.php?id=<?= $c['id'] ?>"
                                                   class="btn btn-outline-primary btn-sm">
                                                    🔍 Ver uploads
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>

                                    <?php if ($clientes->num_rows === 0): ?>
                                        <tr>
                                            <td colspan="4" class="text-center text-muted py-4">
                                                Nenhum cliente cadastrado.
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
                ClassiNCM • Painel de clientes
            </div>
        </div>

    </div>
</div>

<?php require_once __DIR__ . '/../../layout/footer.php'; ?>
