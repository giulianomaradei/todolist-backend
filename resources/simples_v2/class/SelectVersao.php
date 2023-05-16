<?php
require_once(__DIR__."/System.php");

$id_plano = $_POST['id_plano'];
$versao_atual = $_POST['versao_atual'];

if(!$id_plano){
    echo  '<option value="">'.htmlentities('Selecione um plano!').'</option>';
}else {
    // $dados = DBRead('','tb_plano_procedimento_historico', "WHERE id_plano = '".$id_plano."' AND versao NOT LIKE '%p%' GROUP BY versao ORDER BY versao DESC LIMIT 1", "versao");
    $dados = DBRead('','tb_plano', "WHERE id_plano = '".$id_plano."' GROUP BY versao ORDER BY versao DESC LIMIT 1", "versao");

    $dados_personalizado = DBRead('','tb_plano_procedimento_historico', "WHERE id_plano = '".$id_plano."' AND versao = '".$versao_atual."' AND versao LIKE '%p%' GROUP BY versao ORDER BY versao DESC", "versao");
 
    if($dados_personalizado){
        echo $dados_personalizado[0]['versao'];
    }else if($dados) {
        echo $dados[0]['versao'];
    }else{
        echo '';
    }
}

?>