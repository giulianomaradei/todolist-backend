<?php
require_once "System.php";

// Recebe os parâmetros enviados via GET
$acao = (isset($_GET['acao'])) ? $_GET['acao'] : '';
$parametros = (isset($_GET['parametros'])) ? $_GET['parametros'] : '';
$nome = addslashes($parametros['nome']);
$id = addslashes($parametros['id']);

if($parametros['atributo'] && $parametros['atributo'] != 'qualquer'){
	if($parametros['atributo'] == 'nenhum'){
		$atributo = 'AND a.candidato = 0 AND a.cliente = 0 AND a.fornecedor = 0 AND a.funcionario = 0 AND a.prospeccao = 0';
	}else{
		$atributo = 'AND a.'.$parametros['atributo'].' = 1';
	}
}else{
	$atributo = '';
}

// Verifica se foi solicitado uma consulta para o autocomplete
if ($acao == 'autocomplete') {
	$dados = DBRead('', 'tb_pessoa a', "INNER JOIN tb_usuario b ON a.id_pessoa = b.id_pessoa WHERE a.status = '1' AND b.status != 2 AND (a.nome LIKE '%$nome%' OR a.razao_social LIKE '%$nome%' OR a.cpf_cnpj LIKE '%$nome%') $atributo ORDER BY a.nome ASC");

	$json = json_encode($dados);
	echo $json;
}

// Verifica se foi solicitado uma consulta para preencher os campos do formulário
if ($acao == 'consulta') {
	$dados = DBRead('', 'tb_pessoa a', "INNER JOIN tb_usuario b ON a.id_pessoa = b.id_pessoa WHERE a.id_pessoa = '$id'", "a.*, b.id_usuario");
	$json = json_encode($dados);
	echo $json;
}

if ($acao == 'funcionarioautocomplete') {
	$dados = DBRead('', 'tb_pessoa a', "INNER JOIN tb_usuario b ON a.id_pessoa = b.id_pessoa WHERE (a.nome LIKE '%$nome%' OR a.razao_social LIKE '%$nome%' OR a.cpf_cnpj LIKE '%$nome%') ORDER BY a.nome ASC");
	
	$json = json_encode($dados);
	echo $json;
}

