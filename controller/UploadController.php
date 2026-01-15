<?php
class UploadController
{
    public static function salvarArquivo($file)
    {
        $pasta = __DIR__ . '/../uploads/';
        if (!is_dir($pasta)) {
            mkdir($pasta, 0777, true);
        }

        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        if (!in_array($ext, ['xls','xlsx','csv'])) {
            throw new Exception("Formato inválido");
        }

        $nome = uniqid() . '.' . $ext;
        $destino = $pasta . $nome;

        if (!move_uploaded_file($file['tmp_name'], $destino)) {
            throw new Exception("Erro ao salvar arquivo");
        }

        return $destino;
    }
}
