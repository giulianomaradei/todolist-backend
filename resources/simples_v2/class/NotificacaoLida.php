<?php
require_once "System.php";


$id_notificacao_alteracao = ((int)$_GET['id_notificacao_alteracao']) ? $_GET['id_notificacao_alteracao'] : '';
$id_usuario = (isset($_GET['id_usuario'])) ? $_GET['id_usuario'] : '';
$lido = (isset($_GET['lido'])) ? $_GET['lido'] : '';
$flag = 0;

if($lido == 'lido'){

	$dados = array(
		'id_notificacao_alteracao' => $id_notificacao_alteracao,
		'id_usuario' => $id_usuario,
	);

	$insertId = DBCreate('', 'tb_notificacao_alteracao_lida', $dados, true);
	registraLog('Inserção de notificacao lida.','i','tb_notificacao_alteracao_lida',$insertId,"id_notificacao_alteracao: $id_notificacao_alteracao | id_usuario: $id_usuario");
}else{


	$notificacoes = DBRead('', 'tb_notificacao_alteracao');
	
	foreach($notificacoes as $notificacao){

		$lidoPorMim = DBRead('', 'tb_notificacao_alteracao_lida', "WHERE id_usuario = '$id_usuario' AND id_notificacao_alteracao = '".$notificacao['id_notificacao_alteracao']."'");

		if($lidoPorMim == false){

			$dados = array(
				'id_notificacao_alteracao' => $notificacao['id_notificacao_alteracao'],
				'id_usuario' => $id_usuario,
			);

			$insertId = DBCreate('', 'tb_notificacao_alteracao_lida', $dados, true);
					
			registraLog('Inserção de notificacao lida.','i','tb_notificacao_alteracao_lida',$insertId,"id_notificacao_alteracao: $id_notificacao_alteracao | id_usuario: $id_usuario");
		}
	}
}

$json = json_encode($flag);
echo $json;
?>