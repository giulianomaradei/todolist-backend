<?php
require_once(__DIR__."/System.php");


$id_otrs = (!empty($_POST['id_otrs'])) ? $_POST['id_otrs'] : '';
$id_responsavel = (!empty($_POST['id_responsavel'])) ? $_POST['id_responsavel'] : 0;
$id_contrato_plano_pessoa  = (!empty($_POST['id_contrato_plano_pessoa'])) ? $_POST['id_contrato_plano_pessoa'] : '';


if($id_otrs && $id_otrs != ''){
    $valida_otrs = "OR id_otrs ='$id_otrs'";
}else{
    $valida_otrs = "";
}

if(!empty($_POST['inserir'])){

    $verificacao_contrato = DBRead('', 'tb_parametro_redes_contrato', "WHERE id_contrato_plano_pessoa = '".$id_contrato_plano_pessoa."' $valida_otrs");

    if(!$verificacao_contrato){
        inserir($id_contrato_plano_pessoa, $id_otrs, $id_responsavel);
    }else{
        $alert = ('Item já existe na base de dados!');
        $alert_type = 'w';        header("location: /api/iframe?token=$request->token&view=parametro-redes-form");
        exit;
    }

}else if(!empty($_POST['alterar'])){
    $id = (int)$_POST['alterar'];

    $verificacao_contrato = DBRead('', 'tb_parametro_redes_contrato', "WHERE id_parametro_redes_contrato != '".$id."' AND (id_contrato_plano_pessoa = '".$id_contrato_plano_pessoa."' $valida_otrs)");

    if(!$verificacao_contrato){
        alterar($id, $id_contrato_plano_pessoa, $id_otrs, $id_responsavel);
    }else{
        $alert = ('Item já existe na base de dados!', 'w');
        header("location: /api/iframe?token=$request->token&view=parametro-redes-form&alterar=$id");
        exit;
    }
    
}else if(isset($_GET['excluir'])){
    $id = (int)$_GET['excluir'];
    excluir($id);

}else{
    header("location: ../adm.php");
    exit;
}

function inserir($id_contrato_plano_pessoa, $id_otrs, $id_responsavel){

    $dados = array(        
        'id_contrato_plano_pessoa' => $id_contrato_plano_pessoa,
        'id_otrs' => $id_otrs,
        'id_responsavel' => $id_responsavel
    );

    $insertID = DBCreate('', 'tb_parametro_redes_contrato', $dados, true);
    registraLog('Inserção de novo parâmetro de redes.','i','tb_parametro_redes_contrato', $insertID, "id_contrato_plano_pessoa: $id_contrato_plano_pessoa | id_otrs: $id_otrs | id_responsavel: $id_responsavel");
    
    $alert = ('Item inserido com sucesso!', 's');

    header("location: /api/iframe?token=$request->token&view=parametro-redes-busca");
    exit;
}

function alterar($id, $id_contrato_plano_pessoa, $id_otrs, $id_responsavel){

    $dados = array(        
        'id_contrato_plano_pessoa' => $id_contrato_plano_pessoa,
        'id_otrs' => $id_otrs,
        'id_responsavel' => $id_responsavel
    );

    DBUpdate('', 'tb_parametro_redes_contrato', $dados, "id_parametro_redes_contrato = $id");

    registraLog('Alteração de parâmetro de redes.','a','tb_parametro_redes_contrato', $id, "id_contrato_plano_pessoa: $id_contrato_plano_pessoa | id_otrs: $id_otrs | id_responsavel: $id_responsavel");
    $alert = ('Item alterado com sucesso!', 's');

    header("location: /api/iframe?token=$request->token&view=parametro-redes-busca");
    exit;
}

function excluir($id){
    $query = "DELETE FROM tb_parametro_redes_contrato WHERE id_parametro_redes_contrato = $id";
    $link = DBConnect('');
    $result = @mysqli_query($link, $query);
    DBClose($link);
    registraLog('Exclusão de parâmetro de redes.','e','tb_parametro_redes_contrato',$id,'');
    if(!$result){
$alert = ('Erro ao excluir item!');
        $alert_type = 'd';    }else{
        $alert = ('Item excluído com sucesso!');
    $alert_type = 's';    }
    header("location: /api/iframe?token=$request->token&view=parametro-redes-busca");   
    exit;
}

?>