<?php
require_once(__DIR__."/System.php");


$nome = (!empty($_POST['nome'])) ? $_POST['nome'] : '';

if (!empty($_POST['inserir'])) {
    
    $dados = DBRead('', 'tb_resposta', "WHERE BINARY nome = '".addslashes($nome)."'");
    if (!$dados) {
        inserir($nome);
    } else {        
        $alert = ('Item já existe na base de dados!');
        $alert_type = 'w';        header("location: /api/iframe?token=$request->token&view=opcao-resposta-form");
        exit;
    }

} else if (!empty($_POST['alterar'])) {
    $id = (int)$_POST['alterar'];
   
    $dados = DBRead('', 'tb_resposta', "WHERE BINARY nome = '".addslashes($nome)."' AND id_resposta != '$id'");
    if (!$dados) {
        alterar($id, $nome);
    } else {
        $alert = ('Item já existe na base de dados!');
        $alert_type = 'w';        header("location: /api/iframe?token=$request->token&view=reposta-form&alterar=$id");
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
    $insertID = DBCreate('', 'tb_resposta', $dados, true);
    registraLog('Inserção de resposta.','i','tb_resposta',$insertID,"nome: $nome");
    $alert = ('Item inserido com sucesso!');
    $alert_type = 's';    header("location: /api/iframe?token=$request->token&view=opcao-resposta-busca");

 }else{

        $alert = ('Não foi possível inserir o item!');
        $alert_type = 'w';                header("location: /api/iframe?token=$request->token&view=opcao-resposta-form");

}
    
    exit;

}

function alterar($id, $nome){

if($nome != "" && $nome){

    $dados = array(
        'nome' => $nome
    );
    DBUpdate('', 'tb_resposta', $dados, "id_resposta = $id");
    registraLog('Alteração de resposta.','a','tb_resposta',$id,"nome: $nome");
    $alert = ('Item alterado com sucesso!');
    $alert_type = 's';    header("location: /api/iframe?token=$request->token&view=opcao-resposta-busca");
  
}else{

        $alert = 'Não foi possível alterar o item!' ;
        $alert_type = 'w';                header("location: /api/iframe?token=$request->token&view=opcao-resposta-form&alterar=$id");
        
}
    
    exit;

}

function excluir($id){
    $query = "DELETE FROM tb_resposta WHERE id_resposta = $id";
    $link = DBConnect('');
    $result = @mysqli_query($link, $query);
    DBClose($link);
    registraLog('Exclusão de resposta.','e','tb_resposta',$id,'');
    if(!$result){
$alert = ('Erro ao excluir item!');
        $alert_type = 'd';    }else{
        $alert = ('Item excluído com sucesso!');
    $alert_type = 's';    }
    header("location: /api/iframe?token=$request->token&view=opcao-resposta-busca");
    exit;

}

?>
