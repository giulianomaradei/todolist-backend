<?php
require_once "System.php";


$id_topico = (isset($_GET['id_topico'])) ? $_GET['id_topico'] : '';
$id_usuario = $_SESSION['id_usuario'];

$dados_like = DBRead('', 'tb_likes', "WHERE id_topico = '$id_topico' AND id_usuario = '".$_SESSION['id_usuario']."'");
if($dados_like){
    $dados['like'] = 0;
    $id_like = $dados_like[0]['id_likes'];
    DBDelete('', 'tb_likes', "id_likes = '".$id_like."'");
    registraLog('Exclusao de like.','e','tb_likes',$id_like,"id_topico: $id_topico | id_usuario: $id_usuario");
}else{
    $dados['like'] = 1;
    $dados_like = array(
    	'id_topico' => $id_topico,
    	'id_usuario' => $id_usuario
    );
    $insertId = DBCreate('', 'tb_likes', $dados_like, true);
    registraLog('Inserção de like.','i','tb_likes',$insertId,"id_topico: $id_topico | id_usuario: $id_usuario");
}

$dados_like = DBRead('', 'tb_likes', "WHERE id_topico = '$id_topico'", "COUNT(*) AS 'total'");
$dados['total'] = $dados_like[0]['total'];

$json = json_encode($dados);
echo $json;

?>