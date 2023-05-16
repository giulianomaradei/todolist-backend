<?php
require_once(__DIR__."/System.php");

$data_de = converteDataHora($_POST['data_de']);
$data_ate = converteDataHora($_POST['data_ate']);
$id_categoria = (isset($_POST['id_categoria'])) ? $_POST['id_categoria'] : '';

if($data_de){
	$data_de = "AND a.data_criacao >= '$data_de 00:00:00'";
}
if($data_ate){
	$data_ate = "AND a.data_criacao <= '$data_ate 23:59:59'";
}
if($id_categoria != ""){
	$id_categoria = "AND b.id_categoria = $id_categoria";
}else{
    $id_categoria = "";
}

if(!$data_de && !$data_ate){
    echo  '<option value="">'.htmlentities('Preencha pelo menos uma data!').'</option>';
}else {
	//data_criacao é do tipo datetime
	$dados = DBRead('','tb_topico a', "INNER JOIN tb_categoria b ON a.id_categoria = b.id_categoria WHERE 1 $data_de $data_ate $id_categoria AND a.status != 2 AND a.titulo IS NOT NULL");

    $valor = "<option value=''>Todos</option>";
    if($dados){
        foreach($dados as $conteudo){
            $id = $conteudo['id_topico'];
            $titulo = $conteudo['titulo'];
            $valor = $valor."<option value='$id'>$titulo</option>";
        }
    }
    echo $valor;
}
?>