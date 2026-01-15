<?php
$titulo = 'Importar Planilha';

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/layout/header.php';
require_once __DIR__ . '/layout/sidebar.php';

$conn = Database::connect();
$clientes = $conn->query("
    SELECT id, razao_social 
    FROM clientes 
    ORDER BY razao_social
");
?>

<div class="content">
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-lg-6 col-md-8">

                <div class="card shadow-sm">
                    <div class="card-body">

                        <h3 class="card-title text-center mb-3">
                            📊 Importação de Planilha NCM
                        </h3>

                        <p class="text-muted text-center">
                            Importe uma planilha contendo produtos para
                            <strong>classificação automática de NCM</strong>,
                            vinculando os dados a um cliente.
                        </p>

                        <hr>

                        <form method="post"
                              enctype="multipart/form-data"
                              action="<?= BASE_URL ?>/painel/ler_excel.php">

                            <!-- Cliente -->
                            <div class="mb-3">
                                <label class="form-label">Cliente</label>
                                <select name="cliente_id" class="form-select select2" required>
                                    <option value="">Selecione o cliente</option>
                                    <?php while ($c = $clientes->fetch_assoc()): ?>
                                        <option value="<?= $c['id'] ?>">
                                            <?= htmlspecialchars($c['razao_social']) ?>
                                        </option>
                                    <?php endwhile; ?>
                                </select>
                                <small class="text-muted">
                                    Cliente ao qual a planilha será vinculada.
                                </small>
                            </div>

                            <!-- Planilha -->
                            <div class="mb-4">
                                <label class="form-label">Planilha Excel</label>
                                <input type="file"
                                       name="planilha"
                                       class="form-control"
                                       accept=".xls,.xlsx"
                                       required>
                                <small class="text-muted">
                                    Formatos aceitos: .xls ou .xlsx
                                </small>
                            </div>

                            <!-- Botão -->
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    🚀 Importar Planilha
                                </button>
                            </div>

                        </form>

                    </div>
                </div>

                <p class="text-center text-muted mt-3 small">
                    ClassiNCM • Sistema de apoio à classificação fiscal
                </p>

            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/layout/footer.php'; ?>
