<?php
require_once '../../config/database.php';

$conn = Database::connect();
$clienteId = $_GET['id'] ?? 0;

$cliente = $conn->query("
    SELECT * FROM clientes WHERE id = $clienteId
")->fetch_assoc();

$uploads = $conn->query("
    SELECT u.id, u.nome_original, u.data_upload,
           COUNT(p.id) AS total_produtos
    FROM uploads u
    LEFT JOIN produtos p ON p.upload_id = u.id
    WHERE u.cliente_id = $clienteId
    GROUP BY u.id
    ORDER BY u.data_upload DESC
");
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Uploads do Cliente</title>
</head>
<body>

<h2><?= $cliente['razao_social'] ?></h2>

<table>
    <tr>
        <th>Arquivo</th>
        <th>Data</th>
        <th>Produtos</th>
        <th>Ações</th>
    </tr>

    <?php while ($u = $uploads->fetch_assoc()): ?>
    <tr>
        <td><?= $u['nome_original'] ?></td>
        <td><?= date('d/m/Y H:i', strtotime($u['data_upload'])) ?></td>
        <td><?= $u['total_produtos'] ?></td>
        <td>
            <a href="../uploads/produtos.php?id=<?= $u['id'] ?>">
                Ver produtos
            </a>
        </td>
    </tr>
    <?php endwhile; ?>
</table>

</body>
</html>
