<?php
require_once(__DIR__."/System.php");

$cod_servico = $_POST['cod_servico'];
$id_plano = $_POST['id_plano'];
$pagina = $_POST['pagina'];

if($pagina == 'negocio-form'){
    if(!$cod_servico || $cod_servico == 'N/D'){
        echo  '<option value="0">N/D</option>';
    }else {
        $dados = DBRead('','tb_plano', "WHERE cod_servico = '$cod_servico' ORDER BY nome ASC");
        if($dados) {
            foreach ($dados as $conteudo) {
                $id = $conteudo['id_plano'];
                $plano = $conteudo['nome'];
                if($id == $id_plano){
                    $selected = 'selected';
                }else{
                    $selected = '';
                }
                echo "<option value='".$id."' ".$selected.">".$plano."</option>";
            }
        }
    }

}else if($pagina == 'relatorio-leads'){
    
    if(!$cod_servico || $cod_servico == 'N/D'){
        echo  '<option value=""></option>';
    }else {
        $dados = DBRead('','tb_plano', "WHERE cod_servico = '$cod_servico' ORDER BY nome ASC");
        if($dados) {
            echo "<option value=''></option>";
            foreach ($dados as $conteudo) {
                $id = $conteudo['id_plano'];
                $plano = $conteudo['nome'];
                if($id == $id_plano){
                    $selected = 'selected';
                }else{
                    $selected = '';
                }
                echo "<option value='".$id."' ".$selected.">".$plano."</option>";
            }
        }
    }
}else{
    if(!$cod_servico){
        echo  '<option value="">'.htmlentities('Selecione um serviço!').'</option>';
    }else {
        $dados = DBRead('','tb_plano', "WHERE cod_servico = '$cod_servico' ORDER BY nome ASC");
        if($dados) {
            foreach ($dados as $conteudo) {
                $id = $conteudo['id_plano'];
                $plano = $conteudo['nome'];
                if($id == $id_plano){
                    $selected = 'selected';
                }else{
                    $selected = '';
                }
                echo "<option value='".$id."' ".$selected.">".$plano."</option>";
            }
        }
    }
}


?>