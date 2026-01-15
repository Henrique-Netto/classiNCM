<?php
require_once __DIR__ . '/../config/database.php';

class UploadController
{
    public static function salvarArquivo($file, $clienteId)
    {
        if (!$clienteId) {
            throw new Exception("Cliente não informado");
        }

        $pasta = __DIR__ . '/../uploads/';
        if (!is_dir($pasta)) {
            mkdir($pasta, 0777, true);
        }

        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        if (!in_array($ext, ['xls','xlsx','csv'])) {
            throw new Exception("Formato inválido");
        }

        $nomeUnico = uniqid() . '.' . $ext;
        $destino = $pasta . $nomeUnico;

        if (!move_uploaded_file($file['tmp_name'], $destino)) {
            throw new Exception("Erro ao salvar arquivo");
        }

        // salva upload no banco
        $conn = Database::connect();
        $stmt = $conn->prepare("
            INSERT INTO uploads (cliente_id, nome_arquivo, nome_original)
            VALUES (?, ?, ?)
        ");
        $stmt->bind_param(
            "iss",
            $clienteId,
            $nomeUnico,
            $file['name']
        );
        $stmt->execute();

        // retorna dados importantes
        return [
            'upload_id' => $stmt->insert_id,
            'caminho'   => $destino
        ];
    }
}
