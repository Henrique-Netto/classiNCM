<?php
require_once '../../config/database.php';

$conn = Database::connect();

$uploadId  = $_GET['id'] ?? 0;
$categoria = $_GET['categoria'] ?? '';
$risco     = $_GET['risco'] ?? '';

// filtros
$filtros = "WHERE p.upload_id = $uploadId";

if ($categoria !== '') {
    $filtros .= " AND p.categoria = '" . $conn->real_escape_string($categoria) . "'";
}

if ($risco !== '') {
    $filtros .= " AND p.risco = '" . $conn->real_escape_string($risco) . "'";
}

// resumo
$resumo = $conn->query("
    SELECT 
        COUNT(*) AS total,
        SUM(risco = 'alto')  AS alto,
        SUM(risco = 'medio') AS medio,
        SUM(risco = 'baixo') AS baixo
    FROM produtos
    WHERE upload_id = $uploadId
")->fetch_assoc();

// produtos
$produtos = $conn->query("
    SELECT 
        p.codigo,
        p.descricao,
        p.ncm,
        p.preco,
        p.capitulo,
        p.categoria,
        p.ibs,
        p.cbs,
        p.risco,
        p.observacao
    FROM produtos p
    $filtros
    ORDER BY 
        CASE p.risco 
            WHEN 'alto' THEN 1
            WHEN 'medio' THEN 2
            ELSE 3
        END,
        p.descricao
");
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Produtos do Upload | ClassiNCM</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- CSS próprio -->
    <link rel="stylesheet" href="assets/css/produtos.css">
</head>
<body>

<div class="container-fluid mt-4">

    <!-- TÍTULO -->
    <div class="row mb-3">
        <div class="col">
            <h3>📦 Produtos Importados</h3>
            <p class="text-muted">
                Visualização detalhada dos produtos classificados neste upload.
            </p>
        </div>
    </div>

    <!-- RESUMO -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card resumo total">
                <div class="card-body">
                    <h6>Total de produtos</h6>
                    <h3><?= $resumo['total'] ?></h3>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card resumo alto">
                <div class="card-body">
                    <h6>Risco alto</h6>
                    <h3><?= $resumo['alto'] ?></h3>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card resumo medio">
                <div class="card-body">
                    <h6>Risco médio</h6>
                    <h3><?= $resumo['medio'] ?></h3>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card resumo baixo">
                <div class="card-body">
                    <h6>Risco baixo</h6>
                    <h3><?= $resumo['baixo'] ?></h3>
                </div>
            </div>
        </div>
    </div>

    <!-- FILTROS -->
    <form method="get" class="row g-2 align-items-end mb-4">
        <input type="hidden" name="id" value="<?= $uploadId ?>">

        <div class="col-md-4">
            <label class="form-label">Categoria</label>
            <select name="categoria" class="form-select">
                <option value="">Todas</option>
                <option value="Alimento essencial" <?= $categoria == 'Alimento essencial' ? 'selected' : '' ?>>Alimento essencial</option>
                <option value="Produto seletivo" <?= $categoria == 'Produto seletivo' ? 'selected' : '' ?>>Produto seletivo</option>
                <option value="Outros" <?= $categoria == 'Outros' ? 'selected' : '' ?>>Outros</option>
            </select>
        </div>

        <div class="col-md-3">
            <label class="form-label">Risco</label>
            <select name="risco" class="form-select">
                <option value="">Todos</option>
                <option value="alto" <?= $risco == 'alto' ? 'selected' : '' ?>>Alto</option>
                <option value="medio" <?= $risco == 'medio' ? 'selected' : '' ?>>Médio</option>
                <option value="baixo" <?= $risco == 'baixo' ? 'selected' : '' ?>>Baixo</option>
            </select>
        </div>

        <div class="col-md-2">
            <button class="btn btn-primary w-100">
                🔍 Filtrar
            </button>
        </div>
    </form>

    <!-- TABELA -->
    <div class="card shadow-sm">
        <div class="card-body p-0">

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Código</th>
                            <th>Descrição</th>
                            <th>NCM</th>
                            <th class="text-end">Preço</th>
                            <th>Capítulo</th>
                            <th>Categoria</th>
                            <th class="text-end">IBS</th>
                            <th class="text-end">CBS</th>
                            <th>Risco</th>
                            <th>Observação</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($p = $produtos->fetch_assoc()): ?>
                            <tr class="linha-<?= $p['risco'] ?>">
                                <td><?= $p['codigo'] ?></td>
                                <td><?= htmlspecialchars($p['descricao']) ?></td>
                                <td>
                                    <span class="badge bg-primary">
                                        <?= $p['ncm'] ?>
                                    </span>
                                </td>
                                <td class="text-end">
                                    R$ <?= number_format($p['preco'], 2, ',', '.') ?>
                                </td>
                                <td><?= $p['capitulo'] ?></td>
                                <td><?= $p['categoria'] ?></td>
                                <td class="text-end">
                                    <?= number_format($p['ibs'] * 100, 2, ',', '.') ?>%
                                </td>
                                <td class="text-end">
                                    <?= number_format($p['cbs'] * 100, 2, ',', '.') ?>%
                                </td>
                                <td>
                                    <span class="badge risco <?= $p['risco'] ?>">
                                        <?= ucfirst($p['risco']) ?>
                                    </span>
                                </td>
                                <td class="text-muted small">
                                    <?= htmlspecialchars($p['observacao']) ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>

                        <?php if ($produtos->num_rows == 0): ?>
                            <tr>
                                <td colspan="10" class="text-center text-muted py-4">
                                    Nenhum produto encontrado para os filtros selecionados.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/produtos.js"></script>

</body>
</html>
