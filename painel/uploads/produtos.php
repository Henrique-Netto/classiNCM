<?php
require_once '../../config/database.php';

$conn = Database::connect();

$uploadId = $_GET['id'] ?? 0;
$categoria = $_GET['categoria'] ?? '';
$risco = $_GET['risco'] ?? '';

// monta filtros
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
        SUM(risco = 'alto') AS alto
    FROM produtos p
    WHERE p.upload_id = $uploadId
")->fetch_assoc();

// lista produtos
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
    ORDER BY p.risco DESC, p.descricao
");
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Produtos do Upload</title>
    <style>
        body { font-family: Arial; }
        .resumo { width: 90%; margin: 20px auto; display: flex; gap: 20px; }
        .card { padding: 15px; background: #f3f4f6; border-radius: 6px; }
        form { width: 90%; margin: 20px auto; display: flex; gap: 10px; }
        table { border-collapse: collapse; width: 90%; margin: 20px auto; }
        th, td { border: 1px solid #ccc; padding: 8px; }
        th { background: #e5e7eb; }
        .alto { background: #fee2e2; }
        .medio { background: #fef3c7; }
        .baixo { background: #dcfce7; }
    </style>
</head>
<body>

<h2 style="text-align:center">Produtos Importados</h2>

<div class="resumo">
    <div class="card">Total de produtos: <strong><?= $resumo['total'] ?></strong></div>
    <div class="card">Risco alto: <strong><?= $resumo['alto'] ?></strong></div>
</div>

<form method="get">
    <input type="hidden" name="id" value="<?= $uploadId ?>">

    <select name="categoria">
        <option value="">Todas categorias</option>
        <option value="Alimento essencial">Alimento essencial</option>
        <option value="Produto seletivo">Produto seletivo</option>
        <option value="Outros">Outros</option>
    </select>

    <select name="risco">
        <option value="">Todos riscos</option>
        <option value="alto">Alto</option>
        <option value="medio">Médio</option>
        <option value="baixo">Baixo</option>
    </select>

    <button type="submit">Filtrar</button>
</form>

<table>
    <tr>
        <th>Código</th>
        <th>Descrição</th>
        <th>NCM</th>
        <th>Preço</th>
        <th>Capítulo</th>
        <th>Categoria</th>
        <th>IBS</th>
        <th>CBS</th>
        <th>Risco</th>
        <th>Obs</th>
    </tr>

    <?php while ($p = $produtos->fetch_assoc()): ?>
        <tr class="<?= $p['risco'] ?>">
            <td><?= $p['codigo'] ?></td>
            <td><?= htmlspecialchars($p['descricao']) ?></td>
            <td><?= $p['ncm'] ?></td>
            <td><?= number_format($p['preco'], 2, ',', '.') ?></td>
            <td><?= $p['capitulo'] ?></td>
            <td><?= $p['categoria'] ?></td>
            <td><?= number_format($p['ibs'] * 100, 2, ',', '.') ?>%</td>
            <td><?= number_format($p['cbs'] * 100, 2, ',', '.') ?>%</td>
            <td><?= ucfirst($p['risco']) ?></td>
            <td><?= $p['observacao'] ?></td>
        </tr>
    <?php endwhile; ?>
</table>

</body>
</html>
