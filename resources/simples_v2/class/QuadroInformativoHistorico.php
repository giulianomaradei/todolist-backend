<?php
require_once(__DIR__."/System.php");

function inserirHistorico($id_contrato_plano_pessoa, $tipo_acao, $legenda_acao, $dados, $id_quadro_informativo_modulo, $tipo = 1) {

    if ($id_contrato_plano_pessoa !='' && $tipo_acao !='' && $legenda_acao !='' && $dados !='' && $id_quadro_informativo_modulo !='') {

        $data_hora = getDataHora();

        $dados = array(
            'id_usuario' => $_SESSION['id_usuario'],
            'id_contrato_plano_pessoa' => $id_contrato_plano_pessoa,
            'data_hora' => $data_hora,
            'tipo' => $tipo,
            'tipo_acao' => $tipo_acao,
            'dados' => $dados,
            'legenda_acao' => $legenda_acao,
            'id_quadro_informativo_modulo' => $id_quadro_informativo_modulo
        );

        $insertID = DBCreate('', 'tb_quadro_informativo_historico', $dados, true);
    } 
}