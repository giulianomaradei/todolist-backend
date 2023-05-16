<?php
require_once "System.php";

// Recebe os parâmetros enviados via GET
$acao = (isset($_GET['acao'])) ? $_GET['acao'] : '';
$parametros = (isset($_GET['parametros'])) ? $_GET['parametros'] : '';
$nome = addslashes($parametros['nome']);
$id = addslashes($parametros['id']);

if($parametros['atributo'] && $parametros['atributo'] != 'qualquer'){

	if ($parametros['atributo'] == 'nenhum') {
		$atributo = 'AND candidato = 0 AND cliente = 0 AND fornecedor = 0 AND funcionario = 0 AND prospeccao = 0';

	} else if ($parametros['atributo'] == 'fornecedor_funcionario') {
		$atributo = 'AND (fornecedor = 1 OR funcionario = 1)';

	}else if ($parametros['atributo'] == 'cliente_fornecedor_funcionario') {
		$atributo = 'AND (cliente = 1 OR fornecedor = 1 OR funcionario = 1)';

	} else {
		$atributo = 'AND '.$parametros['atributo'].' = 1';
	}
}else{
	$atributo = '';
}

// Verifica se foi solicitado uma consulta para o autocomplete
if($acao == 'autocomplete'){
	$dados = DBRead('', 'tb_pessoa', "WHERE status = '1' AND (nome LIKE '%$nome%' OR razao_social LIKE '%$nome%' OR cpf_cnpj LIKE '%$nome%') $atributo ORDER BY nome ASC");
	$json = json_encode($dados);
	echo $json;

} else if ($acao == 'consulta'){ // Verifica se foi solicitado uma consulta para preencher os campos do formulário

	$dados = DBRead('', 'tb_pessoa a', "INNER JOIN tb_cidade b ON a.id_cidade = b.id_cidade  WHERE a.id_pessoa = '".$id."' ", "a.*, b.nome AS 'nome_cidade' ");

	//ANTES D MATHEUS MUDAR
	//$dados = DBRead('', 'tb_pessoa', "WHERE id_pessoa = '$id'");
	$json = json_encode($dados);
	echo $json;

} else if ($acao = 'consulta_candidato') {

	$dados = DBRead('', 'tb_pessoa', "WHERE status = '1' AND (nome LIKE '%$nome%' OR razao_social LIKE '%$nome%' OR cpf_cnpj LIKE '%$nome%') AND (SELECT COUNT(*) FROM tb_usuario_rh WHERE id_pessoa_usuario = id_pessoa) > 0 ORDER BY nome ASC");

	$json = json_encode($dados);
	echo $json;
}