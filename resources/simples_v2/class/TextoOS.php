<?php
require_once(__DIR__."/System.php");


$nome = (!empty($_POST['nome'])) ? $_POST['nome'] : '';

if (!empty($_POST['inserir'])) {
    
    $dados = DBRead('', 'tb_texto_os', "WHERE BINARY nome = '".addslashes($nome)."'");
    if (!$dados) {
        inserir($nome);
    } else {        
        $alert = ('Item já existe na base de dados!');
        $alert_type = 'w';        header("location: /api/iframe?token=$request->token&view=texto-os-form");
        exit;
    }

} else if (!empty($_POST['alterar'])) {
    $id = (int)$_POST['alterar'];
   
    $dados = DBRead('', 'tb_texto_os', "WHERE BINARY nome = '".addslashes($nome)."' AND id_texto_os != '$id'");
    if (!$dados) {
        alterar($id, $nome);
    } else {
        $alert = ('Item já existe na base de dados!');
        $alert_type = 'w';        header("location: /api/iframe?token=$request->token&view=texto-os-form&alterar=$id");
        exit;
    }

} else if (isset($_GET['excluir'])) {

    $id = (int)$_GET['excluir'];
    excluir($id);

}else{
    header("location: ../adm.php");
    exit;
}

function inserir($nome){

if($nome != ""){

    $dados = array(
        'nome' => $nome
    );
    $insertID = DBCreate('', 'tb_texto_os', $dados, true);
    registraLog('Inserção de texto de OS.','i','tb_texto_os',$insertID,"nome: $nome");
    $alert = ('Item inserido com sucesso!');
    $alert_type = 's';    header("location: /api/iframe?token=$request->token&view=texto-os-busca");

}else{

    $alert = ('Não foi possível inserir o item!','w');
    header("location: /api/iframe?token=$request->token&view=texto-os-form");
    
}
    
    exit;

}

function alterar($id, $nome){

if($nome != ""){

    $dados = array(
        'nome' => $nome
    );
    DBUpdate('', 'tb_texto_os', $dados, "id_texto_os = $id");
    registraLog('Alteração de texto de OS.','a','tb_texto_os',$id,"nome: $nome");
    $alert = ('Item alterado com sucesso!');
    $alert_type = 's';    header("location: /api/iframe?token=$request->token&view=texto-os-busca");

}else{

    $alert = 'Não foi possível alterar o item!' ;
    header("location: /api/iframe?token=$request->token&view=texto-os-form&alterar=$id");
    
}
    
    exit;

}

function excluir($id){
    $query = "DELETE FROM tb_texto_os WHERE id_texto_os = $id";
    $link = DBConnect('');
    $result = @mysqli_query($link, $query);
    DBClose($link);
    registraLog('Exclusão de texto de OS.','e','tb_texto_os',$id,'');
    if(!$result){
$alert = ('Erro ao excluir item!');
        $alert_type = 'd';    }else{
        $alert = ('Item excluído com sucesso!');
    $alert_type = 's';    }
    header("location: /api/iframe?token=$request->token&view=texto-os-busca");
    exit;

}

?>
