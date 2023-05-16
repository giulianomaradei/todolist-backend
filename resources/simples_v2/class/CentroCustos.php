<?php
require_once(__DIR__."/System.php");

$nome  = (!empty($_POST['nome'])) ? $_POST['nome'] : '';
$id_usuario_responsavel  = (!empty($_POST['id_usuario_responsavel'])) ? $_POST['id_usuario_responsavel'] : '';
$status  = (!empty($_POST['status'])) ? $_POST['status'] : '0';

if(!empty($_POST['inserir'])){
    if(($nome != "" && $nome) && ($id_usuario_responsavel != "" && $id_usuario_responsavel)){
        inserir($nome, $id_usuario_responsavel, $status);
    }else{
        $alert = ('Não foi possível inserir o item!');
        $alert_type = 'w';                header("location: /api/iframe?token=$request->token&view=centro-custos-form"); 
    }
}else if(!empty($_POST['alterar'])){

    $id = (int)$_POST['alterar'];
    if(($id_usuario_responsavel != "" && $id_usuario_responsavel) && ($id != "" && $id)){
        alterar($id, $id_usuario_responsavel, $status);
    }else{
        $alert = 'Não foi possível alterar o item!' ;
        $alert_type = 'w';                header("location: /api/iframe?token=$request->token&view=centro-custos-form&alterar=$id"); 
    }
}else if(isset($_GET['excluir'])){
    
    $id = (int)$_GET['excluir'];
    if($id != "" && $id){
        excluir($id);
    }else{
        $alert = ('Não foi possível excluir o item!','d');
        header("location: /api/iframe?token=$request->token&view=centro-custos-form&alterar=$id"); 
    }

}else if(isset($_GET['ativar'])){

    $id = (int)$_GET['ativar'];
    ativar($id);

}else if(isset($_GET['desativar'])){

    $id = (int)$_GET['desativar'];
    desativar($id);

}else{
    header("location: ../adm.php");
    exit;
}

function inserir($nome, $id_usuario_responsavel, $status){
    
    /*echo "nome - ".$nome."<hr>";
    echo "id_usuario_responsavel - ".$id_usuario_responsavel."<hr>";
    echo "status - ".$status."<hr>";*/
    
    $dados = array(
        'nome' => $nome,
        'id_usuario_responsavel' => $id_usuario_responsavel,
        'status' => $status
    );
   
    $insertID = DBCreate('', 'tb_centro_custos', $dados, true);
    registraLog('Inserção de novo centro de custos.','i','tb_centro_custos',$insertID,"nome: $nome | id_usuario_responsavel: $id_usuario_responsavel | status: $status");

    $alert = ('Item inserido com sucesso!');
    $alert_type = 's';    header("location: /api/iframe?token=$request->token&view=centro-custos-busca");
    
    exit;
}

function alterar($id, $id_usuario_responsavel, $status){
    $dados = array(
        'id_usuario_responsavel' => $id_usuario_responsavel,
        'status' => $status
    );

    DBUpdate('', 'tb_centro_custos', $dados, "id_centro_custos = $id");
    registraLog('Alteração de centro de custos.','a','tb_centro_custos',$id," id_usuario_responsavel: $id_usuario_responsavel | status: $status");
    $alert = ('Item alterado com sucesso!');
    $alert_type = 's';    header("location: /api/iframe?token=$request->token&view=centro-custos-busca");

    exit;
}

function excluir($id){
    $query = "DELETE FROM tb_centro_custos WHERE id_centro_custos = ".$id;
    $link = DBConnect('');
    $result = @mysqli_query($link, $query);
    DBClose($link);
    registraLog('Exclusão de centro de custos.', 'e', 'tb_centro_custos', $id, '');
    if(!$result){
        $alert = ('Erro ao excluir item, o mesmo está em uso!', 'd');
        header("location: /api/iframe?token=$request->token&view=centro-custos-form&alterar=".$id);
    }else{
        $alert = ('Item excluído com sucesso!', 's');
        header("location: /api/iframe?token=$request->token&view=centro-custos-busca");
    }
    exit;
}

function ativar($id){
    $dados = array(
        'status' => '1'
    );

    DBUpdate('', 'tb_centro_custos', $dados, "id_centro_custos = $id");
    registraLog('Alteração de staus do centro de custos.','a','tb_centro_custos',$id,"status: 1");
    $alert = ('Item ativado com sucesso!','s');
    header("location: /api/iframe?token=$request->token&view=centro-custos-busca");

    exit;
}

function desativar($id){
    $dados = array(
        'status' => '0'
    );

    DBUpdate('', 'tb_centro_custos', $dados, "id_centro_custos = $id");
    registraLog('Alteração de status do centro de custos.','a','tb_centro_custos',$id,"status: 0");
    $alert = ('Item desativado com sucesso!','s');
    header("location: /api/iframe?token=$request->token&view=centro-custos-busca");

    exit;
}

?>