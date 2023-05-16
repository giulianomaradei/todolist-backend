<?php
require_once "System.php";


$id_topico_visualizado = (isset($_GET['id_topico'])) ? $_GET['id_topico'] : '';

$dados_lido = DBRead('', 'tb_topico_visualizado', "WHERE id_topico_visualizado = '$id_topico_visualizado'");

$data_lido = getDataHora();

$dados['lido'] = 1;
$dados['data'] = $data_lido;

if($dados_lido){
	$dados_lido = array(
	    'data_lido' => $data_lido
	);
	DBUpdate('', 'tb_topico_visualizado', $dados_lido, "id_topico_visualizado = '$id_topico_visualizado'");

	registraLog('Atualizacao de topico visualizado.','a','tb_topico_visualizado',$id_topico_visualizado,"data_lido: $data_lido");
}

$json = json_encode($dados);
echo $json;
?>