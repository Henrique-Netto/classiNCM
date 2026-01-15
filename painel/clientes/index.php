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
    <title>Painel - Clientes</title>
    <style>
        table { border-collapse: collapse; width: 90%; margin: 30px auto; }
        th, td { border: 1px solid #ccc; padding: 8px; }
        th { background: #f3f4f6; }
        a { text-decoration: none; }
    </style>
</head>
<body>

<h2 style="text-align:center">Clientes</h2>

<table>
    <tr>
        <th>Razão Social</th>
        <th>CNPJ</th>
        <th>Uploads</th>
        <th>Ações</th>
    </tr>

    <?php while ($c = $clientes->fetch_assoc()): ?>
    <tr>
        <td><?= htmlspecialchars($c['razao_social']) ?></td>
        <td><?= $c['cnpj'] ?></td>
        <td><?= $c['total_uploads'] ?></td>
        <td>
            <a href="detalhe.php?id=<?= $c['id'] ?>">
                Ver uploads
            </a>
        </td>
    </tr>
    <?php endwhile; ?>
</table>

</body>
</html>
