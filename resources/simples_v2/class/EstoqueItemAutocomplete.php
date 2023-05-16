<?php
require_once(__DIR__."/System.php");

// Recebe os parâmetros enviados via GET
$acao = (isset($_GET['acao'])) ? $_GET['acao'] : '';
$parametros = (isset($_GET['parametros'])) ? $_GET['parametros'] : '';
$nome = addslashes($parametros['nome']);
$id = addslashes($parametros['id']);

// Verifica se foi solicitado uma consulta para o autocomplete
if($acao == 'autocomplete'){
	$dados = DBRead('', 'tb_estoque_item', "WHERE nome LIKE '%$nome%' ORDER BY nome ASC");
	$json = json_encode($dados);
	echo $json;

} else if ($acao == 'consulta'){ // Verifica se foi solicitado uma consulta para preencher os campos do formulário

	$dados = DBRead('', 'tb_estoque_item', "WHERE id_estoque_item = '".$id."' ");

	//ANTES D MATHEUS MUDAR
	//$dados = DBRead('', 'tb_pessoa', "WHERE id_pessoa = '$id'");
	$json = json_encode($dados);
	echo $json;

}