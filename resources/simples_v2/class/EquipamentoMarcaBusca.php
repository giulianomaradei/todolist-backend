<?php
require_once(__DIR__."/System.php");

// Recebe os parâmetros enviados via GET
$acao = (isset($_GET['acao'])) ? $_GET['acao'] : '';
$parametros = (isset($_GET['parametros'])) ? $_GET['parametros'] : '';
$id_marca = addslashes($parametros['id_marca']);

$dados_catalogo_equipamento = DBRead('', 'tb_catalogo_equipamento', "WHERE id_catalogo_equipamento_marca = '".$id_marca."' ");
if($dados_catalogo_equipamento){
	foreach ($dados_catalogo_equipamento as $conteudo) {
        $select_equipamento_marca .= "<option value='".$conteudo['id_catalogo_equipamento']."'>".$conteudo['modelo']."</option>";
	}
}else{
	$select_equipamento_marca .= "<option value=''>Esta marca não possui modelo! Selecione outra marca!</option>";
}
	
	$json = json_encode($select_equipamento_marca);
	echo $json;
