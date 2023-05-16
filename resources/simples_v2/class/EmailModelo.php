<?php
require_once(__DIR__."/System.php");

$titulo = (!empty($_POST['titulo'])) ? $_POST['titulo'] : '';
$assunto = (!empty($_POST['assunto'])) ? $_POST['assunto'] : '';
$descricao = (!empty($_POST['descricao'])) ? $_POST['descricao'] : '';
$status = (!empty($_POST['status'])) ? $_POST['status'] : 0;

if (!empty($_POST['inserir'])) {
    
    $dados = DBRead('', 'tb_email_modelo', "WHERE BINARY titulo = '".addslashes($titulo)."'");
    if (!$dados) {
        inserir($titulo, $assunto, $descricao, $status);
    } else {        
        $alert = ('Item já existe na base de dados!');
        $alert_type = 'w';        header("location: /api/iframe?token=$request->token&view=email-modelo-form");
        exit;
    }

} else if (!empty($_POST['alterar'])) {
    $id = (int)$_POST['alterar'];
   
    $dados = DBRead('', 'tb_email_modelo', "WHERE BINARY titulo = '".addslashes($titulo)."' AND id_email_modelo != '$id'");
    if (!$dados) {
        alterar($id, $titulo, $assunto, $descricao, $status);
    } else {
        $alert = ('Item já existe na base de dados!');
        $alert_type = 'w';        header("location: /api/iframe?token=$request->token&view=email-modelo-form&alterar=$id");
        exit;
    }

} else if (isset($_GET['excluir'])) {

    $id = (int)$_GET['excluir'];
    excluir($id);

}else{
    header("location: ../adm.php");
    exit;
}

function inserir($titulo, $assunto, $descricao, $status){

    $dados = array(
        'titulo' => $titulo,
        'assunto' => $assunto,
        'descricao' => $descricao,
        'status' => $status
    );
    $isertID = DBCreate('', 'tb_email_modelo', $dados, true);
    registraLog('Inserção de modelo de e-mail.','i','tb_email_modelo',$isertID,"titulo: $titulo | assunto: $assunto | descricao: $descricao | status: $status");
    $alert = ('Item inserido com sucesso!');
    $alert_type = 's';    header("location: /api/iframe?token=$request->token&view=email-modelo-busca");
    exit;

}

function alterar($id, $titulo, $assunto, $descricao, $status){

    $dados = array(
        'titulo' => $titulo,
        'assunto' => $assunto,
        'descricao' => $descricao,
        'status' => $status
    );
    DBUpdate('', 'tb_email_modelo', $dados, "id_email_modelo = $id");
    registraLog('Alteração de modelo de e-mail.','a','tb_email_modelo',$id,"titulo: $titulo | assunto: $assunto | descricao: $descricao | status: $status");
    $alert = ('Item alterado com sucesso!');
    $alert_type = 's';    header("location: /api/iframe?token=$request->token&view=email-modelo-busca");
    exit;

}

function excluir($id){
    $query = "DELETE FROM tb_email_modelo WHERE id_email_modelo = $id";
    $link = DBConnect('');
    $result = @mysqli_query($link, $query);
    DBClose($link);
    registraLog('Exclusão de modelo de e-mail.','e','tb_email_modelo',$id,'');
    if(!$result){
$alert = ('Erro ao excluir item!');
        $alert_type = 'd';    }else{
        $alert = ('Item excluído com sucesso!');
    $alert_type = 's';    }    
    header("location: /api/iframe?token=$request->token&view=email-modelo-busca");
    exit;

}

?>
