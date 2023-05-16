<?php

require_once "System.php";


if (isset($_POST['excluir'])) {

    $excluirConversao = (!empty($_POST['excluirConversao'])) ? $_POST['excluirConversao'] : '';

	if ($excluirConversao) {
		excluir($excluirConversao);

	} else {
		$alert = ('Não há item(ns) para excluir!', 'w');
		header("location: /api/iframe?token=$request->token&view=lead-conversao-busca");
		exit;
	}
    
}

function excluir($excluirConversao) {

	$dados = array(
		'status' => 0
	);

	foreach ($excluirConversao as $id) {
		DBUpdate('', 'tb_rd_conversao', $dados, "id_rd_conversao = '$id'");
		registraLog('Exclusão de lead RD conversão.', 'e', 'tb_rd_conversao', $id, 'status: 0');
	}

	/* foreach ($excluirConversao as $id) {
		DBDelete('', 'tb_rd_conversao', "id_rd_conversao = '$id'");
		registraLog('Exclusão de lead RD conversão.', 'e', 'tb_rd_conversao', $id, '');
	} */
	
	$alert = ('Item(ns) excluído(s) com sucesso!', 's');
	header("location: /api/iframe?token=$request->token&view=lead-conversao-busca");
	exit;
}

