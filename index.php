<?php
require_once 'config/database.php';

$conn = Database::connect();
$clientes = $conn->query("SELECT id, razao_social FROM clientes ORDER BY razao_social");
?>

<head>
    <meta charset="UTF-8">
    <title>ClassiNCM - Upload</title>
    <style>
        body {
            background: #f4f6f8;
            font-family: Arial, sans-serif;
        }
        .box {
            width: 400px;
            margin: 100px auto;
            background: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,.1);
        }
        h2 {
            text-align: center;
        }
        input[type=file] {
            width: 100%;
            margin: 15px 0;
        }
        button {
            width: 100%;
            padding: 10px;
            background: #1d4ed8;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .msg {
            text-align: center;
            margin-top: 15px;
            color: green;
        }
    </style>
</head>

<div class="box">
    <h2>Importar Planilha</h2>

    <form method="post" enctype="multipart/form-data" action="ler_excel.php"  target="_blank">
        <label>Cliente</label><br>
        <select name="cliente_id" required>
            <option value="">Selecione o cliente</option>
            <?php while ($c = $clientes->fetch_assoc()): ?>
                <option value="<?= $c['id'] ?>">
                    <?= htmlspecialchars($c['razao_social']) ?>
                </option>
            <?php endwhile; ?>
        </select>
        <br><br>

        <label>Planilha</label><br>
        <input type="file" name="planilha" required>

        <br><br>
        <button type="submit">Importar</button>
    </form>
</div>
