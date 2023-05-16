<?php
require_once(__DIR__."/System.php");

$id_contrato_plano_pessoa = (isset($_GET['id_contrato_plano_pessoa'])) ? $_GET['id_contrato_plano_pessoa'] : '';
$dados = DBRead('', 'tb_arvore_contrato', "WHERE id_contrato_plano_pessoa = '$id_contrato_plano_pessoa'");
$json = json_encode($dados);
echo $json;

?>