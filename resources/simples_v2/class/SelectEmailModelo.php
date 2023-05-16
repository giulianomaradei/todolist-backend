<?php
require_once "System.php";


$id_email_modelo = (isset($_POST['id_email_modelo'])) ? $_POST['id_email_modelo'] : '';

$dados_retorno = array();

$dados = DBRead('', 'tb_email_modelo', "WHERE id_email_modelo = '".$id_email_modelo."' ");

	$dados_retorno['assunto'] = $dados[0]['assunto'];
	$dados_retorno['descricao'] = $dados[0]['descricao'];

echo json_encode($dados_retorno);

?>