<?php
require_once(__DIR__."/System.php");

$nome  = (!empty($_POST['nome'])) ? $_POST['nome'] : '';

if(!empty($_POST['inserir'])){
    $dados = DBRead('', 'tb_estoque_localizacao', "WHERE nome = '".$nome."' ");

    if( $nome != "" && $nome && $nome != $dados[0]['nome'] ){
        inserir($nome);
    }else{
        $alert = ('Não foi possível inserir o item!');
        $alert_type = 'w';                header("location: /api/iframe?token=$request->token&view=estoque-localizacao-form"); 
    }

}else if(!empty($_POST['alterar'])){
    $id = (int)$_POST['alterar'];
    $dados = DBRead('', 'tb_estoque_localizacao', "WHERE nome = '".$nome."' ");
    if( $nome != "" && $nome && $nome != $dados[0]['nome'] ){
        alterar($id, $nome);
    }else{
        $alert = 'Não foi possível alterar o item!' ;
        $alert_type = 'w';                header("location: /api/iframe?token=$request->token&view=estoque-localizacao-form&alterar=$id"); 
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
   
    $insertID = DBCreate('', 'tb_estoque_localizacao', $dados, true);
    registraLog('Inserção de nova localizacao de patrimônio.','i','tb_estoque_localizacao',$insertID,"nome: $nome | status: 1");

    $alert = ('Item inserido com sucesso!');
    $alert_type = 's';    header("location: /api/iframe?token=$request->token&view=estoque-localizacao-busca");
    
    exit;
}

function alterar($id, $nome){
    
    $dados = array(
        'nome' => $nome
    );

    DBUpdate('', 'tb_estoque_localizacao', $dados, "id_estoque_localizacao = $id");
    registraLog('Alteração de localizacao de patrimônio.','a','tb_estoque_localizacao',$id,"nome: $nome");
    $alert = ('Item alterado com sucesso!');
    $alert_type = 's';    header("location: /api/iframe?token=$request->token&view=estoque-localizacao-busca");

    exit;
}

function excluir($id){
    
    $dados = array(
        'status' => 2
    );

    DBUpdate('', 'tb_estoque_localizacao', $dados, "id_estoque_localizacao = $id");
    registraLog('Exclusão de localizacao de patrimônio.','a','tb_estoque_localizacao',$id,"status: 2");
    $alert = ('Item excluído com sucesso!');
    $alert_type = 's';    header("location: /api/iframe?token=$request->token&view=estoque-localizacao-busca");

    exit;
}

?>