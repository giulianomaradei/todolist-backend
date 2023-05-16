<?php
require_once "System.php";

// Recebe os parâmetros enviados via GET
$acao = (isset($_GET['acao'])) ? $_GET['acao'] : '';
$parametros = (isset($_GET['parametros'])) ? $_GET['parametros'] : '';
$nome = addslashes($parametros['nome']);
$id = addslashes($parametros['id']);

// Verifica se foi solicitado uma consulta para o autocomplete
if ($acao == 'autocomplete') {
	$dados = DBRead('', 'tb_texto_os', "WHERE nome LIKE '%$nome%' ORDER BY nome ASC");
	$json = json_encode($dados);
	echo $json;
}

// Verifica se foi solicitado uma consulta para preencher os campos do formulário
if ($acao == 'consulta') {
	$dados = DBRead('', 'tb_texto_os', "WHERE id_texto_os = '$id'");
	$json = json_encode($dados);
	echo $json;
}