<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../services/ClassificacaoService.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

class ExcelController
{
    public static function lerPlanilha($caminho)
    {
        $spreadsheet = IOFactory::load($caminho);
        $sheet = $spreadsheet->getActiveSheet();

        $dados = [];

        foreach ($sheet->getRowIterator(2) as $row) {
            $cells = $row->getCellIterator();
            $cells->setIterateOnlyExistingCells(false);

            $linha = [];
            foreach ($cells as $cell) {
                $linha[] = $cell->getValue();
            }

            // classifica NCM
            $classificacao = ClassificacaoService::classificar($linha[2]);

            // define risco
            $risco = 'baixo';
            if ($classificacao['categoria'] === 'Produto seletivo') {
                $risco = 'alto';
            } elseif ($classificacao['categoria'] === 'Alimento essencial') {
                $risco = 'medio';
            }

            $dados[] = [
                'codigo'    => $linha[0],
                'descricao' => $linha[1],
                'ncm'       => $linha[2],
                'preco'     => $linha[3],
                'capitulo'  => $classificacao['capitulo'],
                'categoria' => $classificacao['categoria'],
                'ibs'       => $classificacao['ibs'],
                'cbs'       => $classificacao['cbs'],
                'risco'     => $risco,
                'obs'       => $classificacao['obs']
            ];
        }

        return $dados;
    }
}
