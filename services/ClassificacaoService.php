<?php
class ClassificacaoService
{
    public static function classificar($ncm)
    {
        $ncm = preg_replace('/\D/', '', $ncm);

        if (strlen($ncm) < 2) {
            return [
                'capitulo' => null,
                'categoria' => 'Inválido',
                'ibs' => 0,
                'cbs' => 0,
                'obs' => 'NCM inválido'
            ];
        }

        $capitulo = substr($ncm, 0, 2);

        switch ($capitulo) {
            case '02':
                return [
                    'capitulo' => $capitulo,
                    'categoria' => 'Alimento essencial',
                    'ibs' => 0.08,
                    'cbs' => 0.04,
                    'obs' => 'Alíquota reduzida'
                ];

            case '22':
            case '24':
                return [
                    'capitulo' => $capitulo,
                    'categoria' => 'Produto seletivo',
                    'ibs' => 0.12,
                    'cbs' => 0.08,
                    'obs' => 'Incidência de IS'
                ];

            default:
                return [
                    'capitulo' => $capitulo,
                    'categoria' => 'Tributação padrão',
                    'ibs' => 0.10,
                    'cbs' => 0.06,
                    'obs' => 'Regra geral'
                ];
        }
    }
}
