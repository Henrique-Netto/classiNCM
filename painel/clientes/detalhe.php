<?php
$titulo = 'Detalhes do Cliente';

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../layout/header.php';
require_once __DIR__ . '/../../layout/sidebar.php';

$conn = Database::connect();
$clienteId = (int) ($_GET['id'] ?? 0);

$cliente = $conn->query("
    SELECT * 
    FROM clientes 
    WHERE id = $clienteId
")->fetch_assoc();

$uploads = $conn->query("
    SELECT 
        u.id,
        u.nome_original,
        u.data_upload,
        COUNT(p.id) AS total_produtos
    FROM uploads u
    LEFT JOIN produtos p ON p.upload_id = u.id
    WHERE u.cliente_id = $clienteId
    GROUP BY u.id
    ORDER BY u.data_upload DESC
");
?>

<div class="main-content">
    <div class="container-fluid mt-4">

        <!-- CABEÇALHO CLIENTE -->
        <div class="row mb-4">
            <div class="col">

                <div class="card shadow-sm">
                    <div class="card-body">
                        <h4 class="mb-1">
                            👤 <?= htmlspecialchars($cliente['razao_social'] ?? 'Cliente') ?>
                        </h4>
                        <p class="text-muted mb-0">
                            CNPJ: <?= htmlspecialchars($cliente['cnpj'] ?? '-') ?>
                        </p>
                    </div>
                </div>

            </div>
        </div>

        <!-- TABELA UPLOADS -->
        <div class="row">
            <div class="col">

                <div class="card shadow-sm">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">
                            📂 Histórico de Uploads
                        </h5>
                    </div>

                    <div class="card-body p-0">

                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Arquivo</th>
                                        <th>Data</th>
                                        <th class="text-center">Produtos</th>
                                        <th class="text-center">Ações</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    <?php while ($u = $uploads->fetch_assoc()): ?>
                                        <tr>
                                            <td>
                                                <strong>
                                                    <?= htmlspecialchars($u['nome_original']) ?>
                                                </strong>
                                            </td>

                                            <td>
                                                <?= date('d/m/Y H:i', strtotime($u['data_upload'])) ?>
                                            </td>

                                            <td class="text-center">
                                                <span class="badge bg-secondary">
                                                    <?= $u['total_produtos'] ?>
                                                </span>
                                            </td>

                                            <td class="text-center">
                                                <a href="<?= BASE_URL ?>/painel/uploads/produtos.php?id=<?= $u['id'] ?>"
                                                   class="btn btn-outline-primary btn-sm">
                                                    📦 Ver produtos
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>

                                    <?php if ($uploads->num_rows === 0): ?>
                                        <tr>
                                            <td colspan="4" class="text-center text-muted py-4">
                                                Nenhum upload encontrado para este cliente.
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
                ClassiNCM • Detalhes do cliente
            </div>
        </div>

    </div>
</div>

<?php require_once __DIR__ . '/../../layout/footer.php'; ?>
