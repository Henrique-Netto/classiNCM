<?php
require_once __DIR__ . '/../config/database.php';

class ProdutoModel
{
    public static function inserir($uploadId, $produto)
    {
        $conn = Database::connect();

        $stmt = $conn->prepare("
            INSERT INTO produtos (
                upload_id,
                codigo,
                descricao,
                ncm,
                preco,
                capitulo,
                categoria,
                ibs,
                cbs,
                risco,
                observacao
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");

        $stmt->bind_param(
            "isssdsdddss",
            $uploadId,
            $produto['codigo'],
            $produto['descricao'],
            $produto['ncm'],
            $produto['preco'],
            $produto['capitulo'],
            $produto['categoria'],
            $produto['ibs'],
            $produto['cbs'],
            $produto['risco'],
            $produto['obs']
        );

        $stmt->execute();
    }
}
