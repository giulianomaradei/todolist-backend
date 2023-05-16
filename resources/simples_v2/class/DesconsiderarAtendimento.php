<?php
require_once(__DIR__."/System.php");

// Recebe os parâmetros enviados via GET
$acao = (isset($_GET['acao'])) ? $_GET['acao'] : '';
$parametros = (isset($_GET['parametros'])) ? $_GET['parametros'] : '';
$id_atendimento = addslashes($parametros['id_atendimento']);
$id_usuario = addslashes($parametros['id_usuario']);


if ($acao == 'faturar') {
	
    $dados_faturar = array(
        'desconsiderar' => '0'
    );

    DBUpdate('', 'tb_atendimento', $dados_faturar, "id_atendimento = '".$id_atendimento."' ");
    registraLog('Alteração para faturar.','a','tb_atendimento',$id_atendimento,"desconsiderar: 0");
    
    DBDelete('', 'tb_desconsiderar', "id_atendimento = '$id_atendimento'");

    $dados_verificacao = DBRead('','tb_atendimento',"WHERE id_atendimento = '".$id_atendimento."' ", "desconsiderar");    
	$json = json_encode($dados_verificacao);
	echo $json;

}else if($acao == 'nao_faturar'){
    $dados_faturar = array(
        'desconsiderar' => '1'
    );

    DBUpdate('', 'tb_atendimento', $dados_faturar, "id_atendimento = '".$id_atendimento."' ");
    registraLog('Alteração para faturar.','a','tb_atendimento',$id_atendimento,"desconsiderar: 1");

    $data_hora = getDataHora();
    $dados_tb_desconsiderar = array(
        'id_usuario' => $id_usuario,
        'data_hora' => $data_hora,
        'id_atendimento' => $id_atendimento,
    );

    $id_desconsiderar = DBCreate('', 'tb_desconsiderar', $dados_tb_desconsiderar, true);
    registraLog('Inserção usuario de desconsiderar.','i','tb_desconsiderar',$id_desconsiderar,"id_usuario: $id_usuario | data_hora: $data_hora | id_atendimento: $id_atendimento ");

    $dados_verificacao = DBRead('','tb_atendimento',"WHERE id_atendimento = '".$id_atendimento."' ", "desconsiderar");    
	$json = json_encode($dados_verificacao);
	echo $json;
}