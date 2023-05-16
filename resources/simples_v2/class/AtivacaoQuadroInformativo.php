<?php
require_once(__DIR__."/System.php");

$idContrato = $_POST['id_contrato_plano_pessoa'];

$dados = DBRead('', 'tb_sistema_gestao_contrato', "WHERE id_contrato_plano_pessoa = $idContrato");

$id = $dados[0]['id_sistema_gestao_contrato'];

if($dados){
	header("location: /api/iframe?token=$request->token&view=sistema-gestao-form&alterar=$id&ativacao=1&id_contrato=$idContrato");
	exit;
}else{
	header("location: /api/iframe?token=$request->token&view=sistema-gestao-form&ativacao=1&id_contrato=$idContrato");
	exit;
}