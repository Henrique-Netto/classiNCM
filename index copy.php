<?php
// conexão simples
$conn = new mysqli("localhost", "root", "", "classincm");
if ($conn->connect_error) {
    die("Erro de conexão");
}

$msg = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_FILES['planilha']['name'])) {

        $pasta = "uploads/";
        if (!is_dir($pasta)) {
            mkdir($pasta, 0777, true);
        }

        $nomeOriginal = $_FILES['planilha']['name'];
        $ext = pathinfo($nomeOriginal, PATHINFO_EXTENSION);

        if (!in_array($ext, ['xls','xlsx','csv'])) {
            $msg = "Formato inválido. Envie Excel ou CSV.";
        } else {

            $nomeArquivo = uniqid() . "." . $ext;
            $destino = $pasta . $nomeArquivo;

            if (move_uploaded_file($_FILES['planilha']['tmp_name'], $destino)) {

                $stmt = $conn->prepare("INSERT INTO uploads (nome_arquivo, nome_original) VALUES (?,?)");
                $stmt->bind_param("ss", $nomeArquivo, $nomeOriginal);
                $stmt->execute();

                $msg = "Arquivo enviado com sucesso!";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
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
<body>

<div class="box">
    <h2>ClassiNCM</h2>
    <p>Envie a planilha de produtos do cliente</p>

    <form method="post" enctype="multipart/form-data" action="ler_excel.php" target="_blank">
        <input type="file" name="planilha" required>
        <button type="submit">Enviar Planilha</button>
    </form>
</div>


</body>
</html>
