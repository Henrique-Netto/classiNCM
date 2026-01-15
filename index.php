<?php
require_once 'config/database.php';

$conn = Database::connect();
$clientes = $conn->query("SELECT id, razao_social FROM clientes ORDER BY razao_social");
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>ClassiNCM | Importação de Planilhas</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <!-- CSS próprio -->
    <link rel="stylesheet" href="assets/css/index.css">
</head>
<body>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-lg-6 col-md-8">

            <div class="card shadow-sm">
                <div class="card-body">

                    <h3 class="card-title text-center mb-3">
                        📊 Importação de Planilha NCM
                    </h3>

                    <p class="text-muted text-center">
                        Esta tela permite importar uma planilha contendo produtos para
                        <strong>classificação automática de NCM</strong>,
                        vinculando os dados a um cliente específico.
                    </p>

                    <hr>

                    <form method="post" enctype="multipart/form-data"
                          action="ler_excel.php" target="_blank">

                        <!-- Cliente -->
                        <div class="mb-3">
                            <label class="form-label">
                                Cliente
                            </label>
                            <select name="cliente_id" class="form-select select2" required>
                                <option value="">Digite ou selecione o cliente</option>
                                <?php while ($c = $clientes->fetch_assoc()): ?>
                                    <option value="<?= $c['id'] ?>">
                                        <?= htmlspecialchars($c['razao_social']) ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                            <small class="form-text text-muted">
                                Selecione o cliente ao qual a planilha será vinculada.
                            </small>
                        </div>

                        <!-- Planilha -->
                        <div class="mb-4">
                            <label class="form-label">
                                Planilha Excel
                            </label>
                            <input type="file"
                                   name="planilha"
                                   class="form-control"
                                   accept=".xls,.xlsx"
                                   required>
                            <small class="form-text text-muted">
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

<!-- JS -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- Select2 -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<!-- JS próprio -->
<script src="assets/js/index.js"></script>

</body>
</html>
