<?php
require_once(__DIR__."/System.php");

$id_tipo_cliente = (isset($_POST['id_tipo_cliente'])) ? $_POST['id_tipo_cliente'] : '';

$dados = DBRead('','tb_tipo_cliente');
    
if($dados){
    echo "<option></option>";
    foreach($dados as $conteudo){
        $id = $conteudo['id_tipo_cliente'];
        $descricao = $conteudo['descricao'];
        echo "<option value='$id'>$descricao</option>";
    }
}
?>