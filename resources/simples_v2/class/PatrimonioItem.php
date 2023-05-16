<?php
require_once(__DIR__."/System.php");


$descricao  = (!empty($_POST['descricao'])) ? $_POST['descricao'] : '';

if(!empty($_POST['inserir'])){
    $dados = DBRead('', 'tb_patrimonio_item', "WHERE descricao = '".$descricao."' ");

    if( $descricao != "" && $descricao && $descricao != $dados[0]['descricao'] ){
        inserir($descricao);
    }else{
        $alert = ('Não foi possível inserir o item!');
        $alert_type = 'w';                header("location: /api/iframe?token=$request->token&view=patrimonio-item-form"); 
    }

}else if(!empty($_POST['alterar'])){
    $id = (int)$_POST['alterar'];
    $dados = DBRead('', 'tb_patrimonio_item', "WHERE descricao = '".$descricao."' ");
    if( $descricao != "" && $descricao && $descricao != $dados[0]['descricao'] ){
        alterar($id, $descricao);
    }else{
        $alert = 'Não foi possível alterar o item!' ;
        $alert_type = 'w';                header("location: /api/iframe?token=$request->token&view=patrimonio-item-form&alterar=$id"); 
    }

}else if(isset($_GET['excluir'])){

    $id = (int)$_GET['excluir'];
    excluir($id);

}else{
    header("location: ../adm.php");
    exit;
}

function inserir($descricao){
    
    $dados = array(
        'descricao' => $descricao,
        'status' => 1
    );
   
    $insertID = DBCreate('', 'tb_patrimonio_item', $dados, true);
    registraLog('Inserção de novo item de patrimônio.','i','tb_patrimonio_item',$insertID,"descricao: $descricao | status: 1");

    $alert = ('Item inserido com sucesso!');
    $alert_type = 's';    header("location: /api/iframe?token=$request->token&view=patrimonio-item-busca");
    
    exit;
}

function alterar($id, $descricao){
    
    $dados = array(
        'descricao' => $descricao
    );

    DBUpdate('', 'tb_patrimonio_item', $dados, "id_patrimonio_item = $id");
    registraLog('Alteração de item de patrimônio.','a','tb_patrimonio_item',$id,"descricao: $descricao");
    $alert = ('Item alterado com sucesso!');
    $alert_type = 's';    header("location: /api/iframe?token=$request->token&view=patrimonio-item-busca");

    exit;
}

function excluir($id){
    
    $dados = array(
        'status' => 2
    );

    DBUpdate('', 'tb_patrimonio_item', $dados, "id_patrimonio_item = $id");
    registraLog('Exclusão de item de patrimônio.','a','tb_patrimonio_item',$id,"status: 2");
    $alert = ('Item excluído com sucesso!');
    $alert_type = 's';    header("location: /api/iframe?token=$request->token&view=patrimonio-item-busca");

    exit;
}

?>