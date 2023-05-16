<?php
require_once "System.php";


$id_horario = (isset($_GET['id_horario'])) ? $_GET['id_horario'] : '';

$data_lido = getDataHora();

$dados['data'] = $data_lido;

	$dados_lido = array(
	    'data_lido' => $data_lido
	);
	DBUpdate('', 'tb_horarios_escala', $dados_lido, "id_horarios_escala = '$id_horario'");

	registraLog('Atualizacao de horario visualizado.','a','tb_horarios_escala',$id_horario,"data_lido: $data_lido");

$json = json_encode($dados);
echo $json;
?>