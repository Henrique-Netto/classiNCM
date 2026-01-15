<?php
require_once 'config/database.php';
require_once 'controller/UploadController.php';
require_once 'controller/ExcelController.php';
require_once 'model/ProdutoModel.php';

try {
    if (!isset($_POST['cliente_id'])) {
        throw new Exception("Cliente não informado");
    }

    if (!isset($_FILES['planilha'])) {
        throw new Exception("Planilha não enviada");
    }

    // salva upload
    $upload = UploadController::salvarArquivo(
        $_FILES['planilha'],
        $_POST['cliente_id']
    );

    $uploadId = $upload['upload_id'];
    $caminho  = $upload['caminho'];

    // lê planilha
    $produtos = ExcelController::lerPlanilha($caminho);

    // salva produtos
    foreach ($produtos as $produto) {
        ProdutoModel::inserir($uploadId, $produto);
    }

} catch (Exception $e) {
    die("Erro: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Importação Concluída | ClassiNCM</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- CSS próprio -->
    <link rel="stylesheet" href="assets/css/leitura.css">
</head>

<body>

<div class="container-fluid mt-4">

    <!-- RESUMO -->
    <div class="row justify-content-center">
        <div class="col-xl-10">

            <div class="alert alert-success shadow-sm">
                <h4 class="alert-heading mb-2">
                    ✅ Importação concluída com sucesso
                </h4>
                <p class="mb-1">
                    A planilha foi processada e os produtos foram classificados automaticamente.
                </p>
                <hr>
                <p class="mb-0">
                    <strong>Total de produtos importados:</strong>
                    <?= count($produtos) ?>
                </p>
            </div>

        </div>
    </div>

    <!-- TABELA -->
    <div class="row justify-content-center mt-3">
        <div class="col-xl-10">

            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        📦 Produtos Importados
                    </h5>
                </div>

                <div class="card-body p-0">

                    <div class="table-responsive">
                        <table class="table table-striped table-hover align-middle mb-0">
                            <thead class="table-secondary">
                                <tr>
                                    <th>Código</th>
                                    <th>Descrição</th>
                                    <th>NCM</th>
                                    <th class="text-end">Preço</th>
                                    <th>Capítulo</th>
                                    <th>Categoria</th>
                                    <th class="text-end">IBS</th>
                                    <th class="text-end">CBS</th>
                                    <th>Observação</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($produtos as $p): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($p['codigo']) ?></td>
                                        <td><?= htmlspecialchars($p['descricao']) ?></td>
                                        <td>
                                            <span class="badge bg-primary">
                                                <?= htmlspecialchars($p['ncm']) ?>
                                            </span>
                                        </td>
                                        <td class="text-end">
                                            R$ <?= number_format($p['preco'], 2, ',', '.') ?>
                                        </td>
                                        <td><?= htmlspecialchars($p['capitulo']) ?></td>
                                        <td><?= htmlspecialchars($p['categoria']) ?></td>
                                        <td class="text-end">
                                            <?= number_format($p['ibs'] * 100, 2, ',', '.') ?>%
                                        </td>
                                        <td class="text-end">
                                            <?= number_format($p['cbs'] * 100, 2, ',', '.') ?>%
                                        </td>
                                        <td class="text-muted small">
                                            <?= htmlspecialchars($p['obs']) ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
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
            ClassiNCM • Resultado da importação
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/leitura.js"></script>

</body>
</html>
