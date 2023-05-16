<?php
require_once(__DIR__."/System.php");


$nome  = (!empty($_POST['nome'])) ? $_POST['nome'] : '';

if(!empty($_POST['inserir'])){
    $dados = DBRead('', 'tb_patrimonio_localizacao', "WHERE nome = '".$nome."' ");

    if( $nome != "" && $nome && $nome != $dados[0]['nome'] ){
        inserir($nome);
    }else{
        $alert = ('Não foi possível inserir o item!');
        $alert_type = 'w';                header("location: /api/iframe?token=$request->token&view=patrimonio-localizacao-form"); 
    }

}else if(!empty($_POST['alterar'])){
    $id = (int)$_POST['alterar'];
    $dados = DBRead('', 'tb_patrimonio_localizacao', "WHERE nome = '".$nome."' ");
    if( $nome != "" && $nome && $nome != $dados[0]['nome'] ){
        alterar($id, $nome);
    }else{
        $alert = 'Não foi possível alterar o item!' ;
        $alert_type = 'w';                header("location: /api/iframe?token=$request->token&view=patrimonio-localizacao-form&alterar=$id"); 
    }

}else if(isset($_GET['excluir'])){

    $id = (int)$_GET['excluir'];
    excluir($id);

}else{
    header("location: ../adm.php");
    exit;
}

function inserir($nome){
    
    $dados = array(
        'nome' => $nome,
        'status' => 1
    );
   
    $insertID = DBCreate('', 'tb_patrimonio_localizacao', $dados, true);
    registraLog('Inserção de nova localizacao de patrimônio.','i','tb_patrimonio_localizacao',$insertID,"nome: $nome | status: 1");

    $alert = ('Item inserido com sucesso!');
    $alert_type = 's';    header("location: /api/iframe?token=$request->token&view=patrimonio-localizacao-busca");
    
    exit;
}

function alterar($id, $nome){
    
    $dados = array(
        'nome' => $nome
    );

    DBUpdate('', 'tb_patrimonio_localizacao', $dados, "id_patrimonio_localizacao = $id");
    registraLog('Alteração de localizacao de patrimônio.','a','tb_patrimonio_localizacao',$id,"nome: $nome");
    $alert = ('Item alterado com sucesso!');
    $alert_type = 's';    header("location: /api/iframe?token=$request->token&view=patrimonio-localizacao-busca");

    exit;
}

function excluir($id){
    
    $dados = array(
        'status' => 2
    );

    DBUpdate('', 'tb_patrimonio_localizacao', $dados, "id_patrimonio_localizacao = $id");
    registraLog('Exclusão de localizacao de patrimônio.','a','tb_patrimonio_localizacao',$id,"status: 2");
    $alert = ('Item excluído com sucesso!');
    $alert_type = 's';    header("location: /api/iframe?token=$request->token&view=patrimonio-localizacao-busca");

    exit;
}

?>