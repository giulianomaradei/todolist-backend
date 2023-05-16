<?php
require_once(__DIR__."/System.php");

// Recebe os parâmetros enviados via GET
$acao = (isset($_GET['acao'])) ? $_GET['acao'] : '';
$parametros = (isset($_GET['parametros'])) ? $_GET['parametros'] : '';
$id = addslashes($parametros['id_pessoa']);


// Verifica se foi solicitado uma consulta para o autocomplete
$dados = DBRead('', 'tb_pessoa', "WHERE id_pessoa = '$id'", "b.id_pessoa, b.logradouro, b.numero, b.cep");
$json = json_encode($dados);
echo $json;

// Verifica se foi solicitado uma consulta para preencher os campos do formulário
if ($acao == 'consulta') {
	$dados = DBRead('', 'tb_pessoa', "WHERE id_pessoa = '$id'");
	$json = json_encode($dados);
	echo $json;
}