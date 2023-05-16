<?php
require_once(__DIR__."/System.php");

$pergunta = (!empty($_POST['pergunta'])) ? $_POST['pergunta'] : '';
$resposta = (!empty($_POST['resposta'])) ? $_POST['resposta'] : '';
$id_faq_categoria = (!empty($_POST['id_faq_categoria'])) ? $_POST['id_faq_categoria'] : '';

if (!empty($_POST['inserir'])) {    
    
    inserir($pergunta, $resposta, $id_faq_categoria);

} else if (!empty($_POST['alterar'])) {
    $id = (int)$_POST['alterar'];
    alterar($id, $pergunta, $resposta, $id_faq_categoria);

} else if (isset($_GET['excluir'])) {

    $id = (int)$_GET['excluir'];
    excluir($id);

} else{
    header("location: ../adm.php");
    exit;
}

function inserir($pergunta, $resposta, $id_faq_categoria){

    $dados = array(        
        'pergunta' => $pergunta,
        'resposta' => $resposta,
        'id_faq_categoria' => $id_faq_categoria
    );
    $insertID = DBCreate('', 'tb_faq', $dados, true);
    registraLog('Inserção de novo FAQ.','i','tb_faq',$insertID,"pergunta: $pergunta | resposta: $resposta | id_faq_categoria: $id_faq_categoria");
    $alert = ('Item inserido com sucesso!');
    $alert_type = 's';    header("location: /api/iframe?token=$request->token&view=faq-busca");    
    exit;
}

function alterar($id, $pergunta, $resposta, $id_faq_categoria){

    $dados = array(        
        'pergunta' => $pergunta,
        'resposta' => $resposta,
        'id_faq_categoria' => $id_faq_categoria
    );
    DBUpdate('', 'tb_faq', $dados, "id_faq = $id");
    registraLog('Alteração de faq.','i','tb_faq',$id,"pergunta: $pergunta | resposta: $resposta | id_faq_categoria: $id_faq_categoria");
    $alert = ('Item alterado com sucesso!');
    $alert_type = 's';    header("location: /api/iframe?token=$request->token&view=faq-busca");
    exit;
}

function excluir($id){
    DBDelete('','tb_faq',"id_faq = '$id'");
    registraLog('Exclusão de faq.','e','tb_faq',$id,'');
    $alert = ('Item excluído com sucesso!');
    $alert_type = 's';    header("location: /api/iframe?token=$request->token&view=faq-busca");
    exit;
}
?>