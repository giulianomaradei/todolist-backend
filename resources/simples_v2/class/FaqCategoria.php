<?php
require_once(__DIR__."/System.php");

$nome = (!empty($_POST['nome'])) ? $_POST['nome'] : '';

if (!empty($_POST['inserir'])) {
    
    $dados = DBRead('', 'tb_faq_categoria', "WHERE BINARY nome = '".addslashes($nome)."'");
    if (!$dados) {
        inserir($nome, $request);
    } else {        
        $alert = ('Item já existe na base de dados!');
        $alert_type = 'w';        
        header("location: /api/iframe?token=".$request->token."&view=faq-categoria-form&alert=$alert&alert_type=$alert_type");
        exit;
    }

} else if (!empty($_POST['alterar'])) {
    $id = (int)$_POST['alterar'];
   
    $dados = DBRead('', 'tb_faq_categoria', "WHERE BINARY nome = '".addslashes($nome)."' AND id_faq_categoria != '$id'");

    if (!$dados) {
        alterar($id, $nome, $request);
    } else {
        $alert = ('Item já existe na base de dados!');
        $alert_type = 'w';        
        header("location: /api/iframe?token=".$request->token."&view=faq-categoria-form&alterar=$id&alert=$alert&alert_type=$alert_type");
        exit;
    }

} else if (isset($_GET['excluir'])) {

    $id = (int)$_GET['excluir'];
    excluir($id, $request);

}else{
    header("location: /api/iframe?token=".$request->token."&view=faq-categiria=form&alert=$alert&alert_type=$alert_type");
    exit;
}

function inserir($nome, $request){
    $dados = array(
        'nome' => $nome
    );

    if($nome && $nome != ""){
        $insertID = DBCreate('', 'tb_faq_categoria', $dados, true);
        registraLog('Inserção de nova categoria no FAQ.','i','tb_faq_categoria',$insertID,"nome: $nome");
        $alert = ('Item inserido com sucesso!');
        $alert_type = 's';        
        header("location: /api/iframe?token=".$request->token."&view=faq-categoria-busca&alert=$alert&alert_type=$alert_type");
    }else{
        $alert = ('Não foi possível inserir o item!');
        $alert_type = 'w';        

        header("location: /api/iframe?token=".$request->token."&view=faq-categoria-form&alert=$alert&alert_type=$alert_type");
    }
    exit;
}

function alterar($id, $nome, $request){

    $dados = array(
        'nome' => $nome
    );

    if($nome && $nome != ""){
        DBUpdate('', 'tb_faq_categoria', $dados, "id_faq_categoria = $id");
        registraLog('Alteração de categoria no FAQ.','a','tb_faq_categoria',$id,"nome: $nome");
        $alert = ('Item alterado com sucesso!');
        $alert_type = 's';        
        header("location: /api/iframe?token=".$request->token."&view=faq-categoria-busca&alert=$alert&alert_type=$alert_type");
    }else{
        $alert = 'Não foi possível alterar o item!' ;
        $alert_type = 'w';        
        header("location: /api/iframe?token=".$request->token."&view=faq-categoria-form&alterar=$id&alert=$alert&alert_type=$alert_type");
    }
    
    exit;
}

function excluir($id, $request){
    $query = "DELETE FROM tb_faq_categoria WHERE id_faq_categoria = $id";
    $link = DBConnect('');
    $result = @mysqli_query($link, $query);
    DBClose($link);
    registraLog('Exclusão de categoria no FAQ.','e','tb_faq_categoria',$id,'');
    if(!$result){
        $alert = ('Erro ao excluir item!');
        $alert_type = 'd';        
    }else{
        $alert = ('Item excluído com sucesso!');
        $alert_type = 's';    }
    header("location: /api/iframe?token=".$request->token."&view=faq-categoria-busca&alert=$alert&alert_type=$alert_type");
    exit;
}

?>