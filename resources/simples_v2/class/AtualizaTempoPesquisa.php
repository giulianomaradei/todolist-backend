<?php
require_once(__DIR__."/System.php");

// Recebe os parâmetros enviados via GET
//$acao = (isset($_GET['acao'])) ? $_GET['acao'] : '';
$parametros = (isset($_POST['parametros'])) ? $_POST['parametros'] : '';
$id_contatos_pesquisa = $parametros['id_contatos_pesquisa'];
$id_usuario = $parametros['id_usuario'];

$contatos = DBRead('', 'tb_data_contato_pesquisa', "WHERE id_usuario = '$id_usuario' AND id_contatos_pesquisa = '$id_contatos_pesquisa'");

echo $contatos;

if(!$contatos){
	$dados = array(
		'data_atualizacao' => getDataHora(),
		'id_usuario' => $id_usuario,
		'id_contatos_pesquisa' => $id_contatos_pesquisa
	);

	DBCreate('', 'tb_data_contato_pesquisa', $dados);
}else{

	$dados = array(
		'data_atualizacao' => getDataHora(),
		'id_usuario' => $id_usuario,
		'id_contatos_pesquisa' => $id_contatos_pesquisa
	);

	DBUpdate('', 'tb_data_contato_pesquisa', $dados, "id_contatos_pesquisa = $id_contatos_pesquisa AND id_usuario = $id_usuario");
}