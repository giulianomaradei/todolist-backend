<?php
require_once(__DIR__."/System.php");

$acao = (isset($_GET['acao'])) ? $_GET['acao'] : '';
$parametros = (isset($_GET['parametros'])) ? $_GET['parametros'] : '';
$id_contrato_plano_pessoa = addslashes($parametros['id_contrato_plano_pessoa']);
$id_arvore = addslashes($parametros['id_arvore']);

echo $acao."<hr>";
echo $id_contrato_plano_pessoa."<hr>";
echo $id_arvore."<hr>";

if($acao == 'criacao'){
    $dados = array(
        'id_contrato_plano_pessoa' => $id_contrato_plano_pessoa,
        'id_arvore' => $id_arvore,
        'exibe_texto_os' => 1
    );
    
    DBCreate('', 'tb_arvore_contrato', $dados, true);

}else if($acao == 'exclusao'){

    DBDelete('','tb_arvore_contrato',"id_arvore = '$id_arvore' AND id_contrato_plano_pessoa = '".$id_contrato_plano_pessoa."'"); 

}else if($acao == 'exibe_texto_sim'){
    $dados = DBRead('', 'tb_arvore_contrato', "WHERE id_arvore = '".$id_arvore."' AND id_contrato_plano_pessoa = '".$id_contrato_plano_pessoa."' ");
    if($dados){
        $dados_atualiza = array(
            'exibe_texto_os' => 1
        );
        DBUpdate('', 'tb_arvore_contrato', $dados_atualiza, "id_arvore = '".$id_arvore."' AND id_contrato_plano_pessoa = '".$id_contrato_plano_pessoa."'");

    }else{
        $dados_atualiza = array(
            'id_contrato_plano_pessoa' => $id_contrato_plano_pessoa,
            'id_arvore' => $id_arvore,
            'exibe_texto_os' => 1
        );
        
        DBCreate('', 'tb_arvore_contrato', $dados_atualiza, true);
    }

}else if($acao == 'exibe_texto_nao'){
    $dados = DBRead('', 'tb_arvore_contrato', "WHERE id_arvore = '".$id_arvore."' AND id_contrato_plano_pessoa = '".$id_contrato_plano_pessoa."' ");
    if($dados){
        $dados_atualiza = array(
            'exibe_texto_os' => '0'
        );
        DBUpdate('', 'tb_arvore_contrato', $dados_atualiza, "id_arvore = '".$id_arvore."' AND id_contrato_plano_pessoa = '".$id_contrato_plano_pessoa."' ");

    }
}

?>