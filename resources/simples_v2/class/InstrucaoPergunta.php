<?php
require_once(__DIR__."/System.php");

$nome = (!empty($_POST['nome'])) ? $_POST['nome'] : '';

if (!empty($_POST['inserir'])) {
    
    $dados = DBRead('', 'tb_pergunta', "WHERE BINARY nome = '".addslashes($nome)."'");
    if (!$dados) {
        inserir($nome);
    } else {        
        $alert = ('Item já existe na base de dados!');
        $alert_type = 'w';        header("location: /api/iframe?token=$request->token&view=instrucao-pergunta-form");
        exit;
    }

} else if (!empty($_POST['alterar'])) {
    $id = (int)$_POST['alterar'];
   
    $dados = DBRead('', 'tb_pergunta', "WHERE BINARY nome = '".addslashes($nome)."' AND id_pergunta != '$id'");
    if (!$dados) {
        alterar($id, $nome);
    } else {
        $alert = ('Item já existe na base de dados!');
        $alert_type = 'w';        header("location: /api/iframe?token=$request->token&view=instrucao-pergunta-form&alterar=$id");
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
    $insertID = DBCreate('', 'tb_pergunta', $dados, true);
    registraLog('Inserção de pergunta.','i','tb_pergunta',$insertID,"nome: $nome");
    $alert = ('Item inserido com sucesso!');
    $alert_type = 's';    header("location: /api/iframe?token=$request->token&view=instrucao-pergunta-busca");

}else{

        $alert = ('Não foi possível inserir o item!');
        $alert_type = 'w';                header("location: /api/iframe?token=$request->token&view=instrucao-pergunta-form");

}
    
    exit;

}

function alterar($id, $nome){

if($nome != "" && $nome){

    $dados = array(
        'nome' => $nome
    );
    DBUpdate('', 'tb_pergunta', $dados, "id_pergunta = $id");
    registraLog('Alteração de pergunta.','a','tb_pergunta',$id,"nome: $nome");
    $alert = ('Item alterado com sucesso!');
    $alert_type = 's';    header("location: /api/iframe?token=$request->token&view=instrucao-pergunta-busca");

}else{

        $alert = 'Não foi possível alterar o item!' ;
        $alert_type = 'w';                header("location: /api/iframe?token=$request->token&view=instrucao-pergunta-form&alterar=$id");
}
    
    exit;

}

function excluir($id){
    $query = "DELETE FROM tb_pergunta WHERE id_pergunta = $id";
    $link = DBConnect('');
    $result = @mysqli_query($link, $query);
    DBClose($link);
    registraLog('Exclusão de pergunta.','e','tb_pergunta',$id,'');
    if(!$result){
$alert = ('Erro ao excluir item!');
        $alert_type = 'd';    }else{
        $alert = ('Item excluído com sucesso!');
    $alert_type = 's';    }
    header("location: /api/iframe?token=$request->token&view=instrucao-pergunta-busca");
    exit;

}

?>
