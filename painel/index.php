<?php
require_once '../config/database.php';
$conn = Database::connect();

// cards
$clientes = $conn->query("SELECT COUNT(*) total FROM clientes")->fetch_assoc()['total'];
$uploads  = $conn->query("SELECT COUNT(*) total FROM uploads")->fetch_assoc()['total'];
$produtos = $conn->query("SELECT COUNT(*) total FROM produtos")->fetch_assoc()['total'];
$riscoAlto = $conn->query("SELECT COUNT(*) total FROM produtos WHERE risco='alto'")->fetch_assoc()['total'];

// últimos uploads
$ultimos = $conn->query("
    SELECT 
        u.id,
        u.data_upload,
        c.nome_fantasia,
        COUNT(p.id) total_produtos,
        SUM(p.risco='alto') risco_alto
    FROM uploads u
    JOIN clientes c ON c.id = u.cliente_id
    LEFT JOIN produtos p ON p.upload_id = u.id
    GROUP BY u.id
    ORDER BY u.data_upload DESC
    LIMIT 10
");
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title>Painel do Contador - ClassiNCM</title>
<style>
body { font-family: Arial; background:#f9fafb; }
.cards { display:flex; gap:20px; width:90%; margin:20px auto; }
.card { background:#fff; padding:20px; border-radius:8px; width:25%; box-shadow:0 0 5px #ddd; }
.card strong { font-size:26px; display:block; }

table { width:90%; margin:20px auto; border-collapse:collapse; background:#fff; }
th,td { padding:10px; border:1px solid #ddd; }
th { background:#f3f4f6; }
.alerta { background:#fee2e2; }
.btn { padding:6px 10px; background:#2563eb; color:#fff; text-decoration:none; border-radius:4px; }
</style>
</head>
<body>

<h2 style="text-align:center">📊 Dashboard do Contador</h2>

<div class="cards">
    <div class="card">Clientes<strong><?= $clientes ?></strong></div>
    <div class="card">Uploads<strong><?= $uploads ?></strong></div>
    <div class="card">Produtos<strong><?= $produtos ?></strong></div>
    <div class="card">Risco Alto<strong><?= $riscoAlto ?></strong></div>
</div>

<h3 style="width:90%; margin:20px auto">Últimos Uploads</h3>

<table>
<tr>
    <th>Cliente</th>
    <th>Data</th>
    <th>Produtos</th>
    <th>Risco Alto</th>
    <th>Ação</th>
</tr>

<?php while ($u = $ultimos->fetch_assoc()): ?>
<tr class="<?= $u['risco_alto'] > 0 ? 'alerta' : '' ?>">
    <td><?= $u['nome_fantasia'] ?></td>
    <td><?= date('d/m/Y H:i', strtotime($u['data_upload'])) ?></td>
    <td><?= $u['total_produtos'] ?></td>
    <td><?= $u['risco_alto'] ?></td>
    <td>
        <a class="btn" href="uploads/produtos.php?id=<?= $u['id'] ?>">
            Ver produtos
        </a>
    </td>
</tr>
<?php endwhile; ?>
</table>

</body>
</html>
