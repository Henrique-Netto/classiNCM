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

    // lê e classifica planilha
    $produtos = ExcelController::lerPlanilha($caminho);

    // salva produtos
    foreach ($produtos as $produto) {
        ProdutoModel::inserir($uploadId, $produto);
    }

    echo "<h2>Importação concluída com sucesso!</h2>";
    echo "<p>Total de produtos importados: <strong>" . count($produtos) . "</strong></p>";

} catch (Exception $e) {
    die("Erro: " . $e->getMessage());
}


?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Leitura de Planilha - ClassiNCM</title>
    <style>
        table {
            border-collapse: collapse;
            width: 90%;
            margin: 30px auto;
        }

        th,
        td {
            border: 1px solid #ccc;
            padding: 8px;
        }

        th {
            background: #f3f4f6;
        }
    </style>
</head>

<body>

    <h2 style="text-align:center">Produtos Importados</h2>

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
            <th>Observação</th>
        </tr>

        <?php foreach ($produtos as $p): ?>
            <tr>
                <td><?= htmlspecialchars($p['codigo']) ?></td>
                <td><?= htmlspecialchars($p['descricao']) ?></td>
                <td><?= htmlspecialchars($p['ncm']) ?></td>
                <td><?= number_format($p['preco'], 2, ',', '.') ?></td>

                <td><?= $p['capitulo'] ?></td>
                <td><?= $p['categoria'] ?></td>
                <td><?= number_format($p['ibs'] * 100, 2, ',', '.') ?>%</td>
                <td><?= number_format($p['cbs'] * 100, 2, ',', '.') ?>%</td>
                <td><?= $p['obs'] ?></td>

            </tr>
        <?php endforeach; ?>
    </table>

</body>

</html>