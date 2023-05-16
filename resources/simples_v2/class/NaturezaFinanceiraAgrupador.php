<?php
require_once(__DIR__."/System.php");


$nome  = (!empty($_POST['nome'])) ? $_POST['nome'] : '';
$tipo  = (!empty($_POST['tipo'])) ? $_POST['tipo'] : '';
$status  = (!empty($_POST['status'])) ? $_POST['status'] : '0';

if(!empty($_POST['inserir'])){
    if(($nome != "" && $nome) && ($tipo != "" && $tipo)){
        inserir($nome, $tipo, $status);
    }else{
        $alert = ('Não foi possível inserir o item!');
        $alert_type = 'w';                header("location: /api/iframe?token=$request->token&view=natureza-financeira-agrupador-form"); 
    }

}else if(!empty($_POST['alterar'])){

    $id = (int)$_POST['alterar'];
    if($id != "" && $id){
        alterar($id, $status);
    }else{
        $alert = 'Não foi possível alterar o item!' ;
        $alert_type = 'w';                header("location: /api/iframe?token=$request->token&view=natureza-financeira-agrupador-form&alterar=$id"); 
    }

}else if(isset($_GET['ativar'])){

    $id = (int)$_GET['ativar'];
    ativar($id);

}else if(isset($_GET['desativar'])){

    $id = (int)$_GET['desativar'];
    desativar($id);

}else if(isset($_GET['excluir'])){
    
    $id = (int)$_GET['excluir'];
    if($id != "" && $id){
        excluir($id);
    }else{
        $alert = ('Não foi possível excluir o item!','d');
        header("location: /api/iframe?token=$request->token&view=natureza-financeira-agrupador-form&alterar=$id"); 
    }

}else{
    header("location: ../adm.php");
    exit;

}

function inserir($nome, $tipo, $status){
    
    $dados = array(
        'nome' => $nome,
        'tipo' => $tipo,
        'status' => $status
    );
   
    $insertID = DBCreate('', 'tb_natureza_financeira_agrupador', $dados, true);
    registraLog('Inserção de novo agrupador natureza financeira.','i','tb_natureza_financeira_agrupador',$insertID,"nome: $nome | tipo: $tipo | status: $status");

    $alert = ('Item inserido com sucesso!');
    $alert_type = 's';    header("location: /api/iframe?token=$request->token&view=natureza-financeira-agrupador-busca");
    
    exit;
}

function alterar($id, $status){
    $dados = array(
        'status' => $status
    );

    DBUpdate('', 'tb_natureza_financeira_agrupador', $dados, "id_natureza_financeira_agrupador = $id");
    registraLog('Alteração de agrupador natureza financeira.','a','tb_natureza_financeira_agrupador',$id,"status: $status");
    $alert = ('Item alterado com sucesso!');
    $alert_type = 's';    header("location: /api/iframe?token=$request->token&view=natureza-financeira-agrupador-busca");

    exit;
}

function ativar($id){
    $dados = array(
        'status' => '1'
    );

    DBUpdate('', 'tb_natureza_financeira_agrupador', $dados, "id_natureza_financeira_agrupador = $id");
    registraLog('Alteração de agrupador natureza financeira.','a','tb_natureza_financeira_agrupador',$id,"status: 1");
    $alert = ('Item ativado com sucesso!','s');
    header("location: /api/iframe?token=$request->token&view=natureza-financeira-agrupador-busca");

    exit;
}

function desativar($id){
    $dados = array(
        'status' => '0'
    );

    DBUpdate('', 'tb_natureza_financeira_agrupador', $dados, "id_natureza_financeira_agrupador = $id");
    registraLog('Alteração de agrupador natureza financeira.','a','tb_natureza_financeira_agrupador',$id,"status: 0");
    $alert = ('Item desativado com sucesso!','s');
    header("location: /api/iframe?token=$request->token&view=natureza-financeira-agrupador-busca");

    exit;
}

function excluir($id){
    $query = "DELETE FROM tb_natureza_financeira_agrupador WHERE id_natureza_financeira_agrupador = ".$id;
    $link = DBConnect('');
    $result = @mysqli_query($link, $query);
    DBClose($link);
    registraLog('Exclusão de agrupador de natureza de financeira.', 'e', 'tb_natureza_financeira_agrupador', $id, '');
    if(!$result){
        $alert = ('Erro ao excluir item, o mesmo está em uso!', 'd');
        header("location: /api/iframe?token=$request->token&view=natureza-financeira-agrupador-form&alterar=".$id);
    }else{
        $alert = ('Item excluído com sucesso!', 's');
        header("location: /api/iframe?token=$request->token&view=natureza-financeira-agrupador-busca");
    }
    exit;
}

?>