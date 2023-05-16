<?php
require_once(__DIR__."/System.php");

$id_atendimento = (isset($_GET['id_atendimento'])) ? $_GET['id_atendimento'] : '';
$contato = (isset($_GET['contato'])) ? $_GET['contato'] : '';
$fone1 = (isset($_GET['fone1'])) ? preg_replace("/[^0-9]/", "", $_GET['fone1']) : '';
$fone2 = (isset($_GET['fone2'])) ? preg_replace("/[^0-9]/", "", $_GET['fone2']) : '';
$assinante = (isset($_GET['assinante'])) ? $_GET['assinante'] : '';
$cpf_cnpj = (isset($_GET['cpf_cnpj'])) ? preg_replace("/[^0-9]/", "", $_GET['cpf_cnpj']) : '';
$dado_adicional = (isset($_GET['dado_adicional'])) ? $_GET['dado_adicional'] : '';

$dados = array(
	'contato' => $contato,
	'fone1' => $fone1,
	'fone2' => $fone2,
	'assinante' => $assinante,
	'cpf_cnpj' => $cpf_cnpj,
	'dado_adicional' => $dado_adicional
);
DBUpdate('', 'tb_atendimento', $dados, "id_atendimento = '$id_atendimento'");

registraLog('Atualizacao de contato no atendimento.','a','tb_atendimento',$id_atendimento,"contato: $contato | fone1: $fone1 | fone2: $fone2 | assinante: $assinante | cpf_cnpj: $cpf_cnpj | dado_adicional: $dado_adicional");

$json = json_encode($dados);
echo $json;
?>