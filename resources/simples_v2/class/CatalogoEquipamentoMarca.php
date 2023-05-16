<?php
require_once(__DIR__."/System.php");
$nome = (!empty($_POST['nome'])) ? $_POST['nome'] : '';

if (!empty($_POST['inserir'])) {
    $nome = strtoupper(trim($nome));
    $dados = DBRead('', 'tb_catalogo_equipamento_marca', "WHERE BINARY nome = '".addslashes($modelo)."' ");
    if (!$dados) {
        inserir($nome);
    } else {        
        $alert = ('Item já existe na base de dados!');
        $alert_type = 'w';        header("location: /api/iframe?token=$request->token&view=catalogo-equipamento-marca-form");
        exit;
    }

} else if (!empty($_POST['alterar'])) {
    $id = (int)$_POST['alterar'];
    $dados_marca = DBRead('', 'tb_catalogo_equipamento', "WHERE id_catalogo_equipamento_marca = '$id' ");
    if(!$dados_marca){
        
        $nome = strtoupper(trim($nome));
        $dados = DBRead('', 'tb_catalogo_equipamento_marca', "WHERE BINARY nome = '".addslashes($nome)."' AND id_catalogo_equipamento_marca != '$id' ");
        if (!$dados) {
            alterar($id, $nome);
        } else {
            $alert = ('Item já existe na base de dados!');
        $alert_type = 'w';            header("location: /api/iframe?token=$request->token&view=catalogo-equipamento-marca-form&alterar=$id");
            exit;
        }

    }else{
        $alert = ('A marca está em utilização no catálogo de equipamentos!','w');
        header("location: /api/iframe?token=$request->token&view=catalogo-equipamento-marca-busca");
        exit;
    }

} else if (isset($_GET['excluir'])) {

    $id = (int)$_GET['excluir'];
    $dados_marca = DBRead('', 'tb_catalogo_equipamento', "WHERE id_catalogo_equipamento_marca = '$id' ");

    if(!$dados_marca){
        excluir($id);
    }else{
        $alert = ('A marca está em utilização no catálogo de equipamentos!','w');
        header("location: /api/iframe?token=$request->token&view=catalogo-equipamento-marca-busca");
        exit;
    }

}else if (isset($_GET['desativar'])) {

    $id = (int)$_GET['desativar'];
    desativar($id);

}else if (isset($_GET['ativar'])) {

    $id = (int)$_GET['ativar'];
    ativar($id);

}else{
    header("location: ../adm.php");
    exit;
}

function inserir($nome){
    $dados = array(
        'nome' => $nome
    );

    $insertID = DBCreate('', 'tb_catalogo_equipamento_marca', $dados, true);
    registraLog('Inserção de nova catalogo de equipamento - marca.','i','tb_catalogo_equipamento_marca',$insertID,"nome: $nome");
    $alert = ('Item inserido com sucesso!');
    $alert_type = 's';    header("location: /api/iframe?token=$request->token&view=catalogo-equipamento-marca-busca");
    exit;
}

function alterar($id, $nome){

    $dados = array(
        'nome' => $nome
    );

    DBUpdate('', 'tb_catalogo_equipamento_marca', $dados, "id_catalogo_equipamento_marca = $id");
    registraLog('Alteração de catalogo de equipamento - marca.','a','tb_catalogo_equipamento_marca',$id,"nome: $nome");
    $alert = ('Item alterado com sucesso!');
    $alert_type = 's';    header("location: /api/iframe?token=$request->token&view=catalogo-equipamento-marca-busca");
    exit;
}

function excluir($id){
    
    $query = "DELETE FROM tb_catalogo_equipamento_marca WHERE id_catalogo_equipamento_marca = $id";
    $link = DBConnect('');
    $result = @mysqli_query($link, $query);
    DBClose($link);
    registraLog('Exclusão de catalogo de equipamento - marca.','e','tb_catalogo_equipamento_marca',$id,'');
    if(!$result){
$alert = ('Erro ao excluir item!');
        $alert_type = 'd';    }else{
        $alert = ('Item excluído com sucesso!');
    $alert_type = 's';    }
    header("location: /api/iframe?token=$request->token&view=catalogo-equipamento-marca-busca");
    exit;
}

function desativar($id){

    $dados = array(
        'status' => '0',
    );
    DBUpdate('', 'tb_catalogo_equipamento_marca',$dados,"id_catalogo_equipamento_marca = $id");
    registraLog('Desativação de catalogo de equipamentos - marca.','e','tb_usuario',$id,'');
    $alert = ('Marca desativada com sucesso!','s');
    header("location: /api/iframe?token=$request->token&view=catalogo-equipamento-marca-busca");
    exit;
}
function ativar($id){

    $dados = array(
        'status' => '1'
    );
    DBUpdate('', 'tb_catalogo_equipamento_marca',$dados,"id_catalogo_equipamento_marca = $id");
    registraLog('Ativação de catalogo de equipamentos - marca.','e','tb_usuario',$id,'');
    $alert = ('Marca ativada com sucesso!','s');
    header("location: /api/iframe?token=$request->token&view=catalogo-equipamento-marca-busca");
    exit;
}


?>