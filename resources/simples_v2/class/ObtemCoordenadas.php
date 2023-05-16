<?php
require_once "System.php";

// Recebe os parâmetros enviados via GET
$acao = (isset($_GET['acao'])) ? $_GET['acao'] : '';
$parametros = (isset($_GET['parametros'])) ? $_GET['parametros'] : '';

$id = addslashes($parametros['id']);

//Verifica se foi solicitado uma consulta para o autocomplete
if($acao == 'obtemCoordenada'){
	$dados = DBRead('', 'tb_localizacao', "WHERE id_localizacao_contrato = $id");
	$json = json_encode($dados);
	echo $json;
}