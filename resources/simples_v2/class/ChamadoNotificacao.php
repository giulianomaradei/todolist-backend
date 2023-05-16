<?php
require_once(__DIR__."/System.php");

$id_chamado = ((int)$_POST['id_chamado']) ? $_POST['id_chamado'] : '';
$id_usuario = $_SESSION['id_usuario'];

$dados_notificacao = DBRead('', 'tb_chamado_ignora', "WHERE id_chamado = '$id_chamado' AND id_usuario = '$id_usuario'");

if($dados_notificacao){

	DBDelete('', 'tb_chamado_ignora', "id_chamado = '$id_chamado' AND id_usuario = '$id_usuario'");
	registraLog('exclusão de notificação ignora.','e','tb_chamado_ignora', $dados_notificacao[0]['id_chamado_ignora'], '');

	$teste = 'true';

	echo json_encode($teste);
}else{

	$dados = array(
	    'id_usuario' => $id_usuario,
	    'id_chamado' => $id_chamado
	);
	$insertID = DBCreate('', 'tb_chamado_ignora', $dados, true);
	registraLog('Inserção de notificação ignora.', 'i', 'tb_chamado_ignora', $insertID, "id_usuario: $id_usuario | id_chamado: $id_chamado");
	
	$teste = 'false';

	echo json_encode($teste);
}

?>