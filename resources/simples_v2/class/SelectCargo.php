<?php
require_once(__DIR__."/System.php");

$setor = $_POST['setor'];

if(!$setor){
    echo  '<option value="">'.htmlentities('Selecione um setor antes!').'</option>';
}else {
    echo  '<option value="">'.htmlentities('Selecione').'</option>';
    $dados = DBRead('','tb_cargo', "WHERE id_setor = $setor ORDER BY descricao ASC");
    if($dados) {
        foreach ($dados as $conteudo) {
            $id = $conteudo['id_cargo'];
            $cargo = $conteudo['descricao'];
            echo "<option value='$id'>$cargo</option>";
        }
    }
}
?>