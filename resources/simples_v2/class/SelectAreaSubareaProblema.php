<?php
require_once(__DIR__."/System.php");

$area_problema = $_POST['area_problema'];

if(!$area_problema){
    echo  '<option value="">'.htmlentities('Selecione uma área do problema antes!').'</option>';
}else {
    if($area_problema == 'nao_identificada'){
        echo "<option value=''>Atendimento Incompleto</option>";
    }

    $dados = DBRead('','tb_subarea_problema', "WHERE id_area_problema = $area_problema ORDER BY descricao ASC");
    if($dados) {
        echo  '<option value="">Todos</option>';
        foreach ($dados as $conteudo) {
            $id = $conteudo['id_subarea_problema'];
            $descricao = $conteudo['descricao'];
            echo "<option value='$id'>$descricao</option>";
        }
    }

}
?>