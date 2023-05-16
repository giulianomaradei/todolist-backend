<?php
require_once(__DIR__."/System.php");

$estado = $_POST['estado'];

if(!$estado){
    echo  '<option value="">'.htmlentities('Selecione uma UF antes!').'</option>';
}else {
    $dados = DBRead('','tb_cidade', "WHERE id_estado = $estado ORDER BY nome ASC");
    if($dados) {
        foreach ($dados as $conteudo) {
            $id = $conteudo['id_cidade'];
            $cidade = $conteudo['nome'];
            echo "<option value='$id'>$cidade</option>";
        }
    }
}
?>