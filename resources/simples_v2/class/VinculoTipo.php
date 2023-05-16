<?php
require_once(__DIR__."/System.php");


$nome = (!empty($_POST['nome'])) ? $_POST['nome'] : '';
$exibe_painel = (!empty($_POST['exibe_painel'])) ? $_POST['exibe_painel'] : 0;
$descricao = (!empty($_POST['descricao'])) ? $_POST['descricao'] : '';

if (!empty($_POST['inserir'])) {
    
    $dados = DBRead('', 'tb_vinculo_tipo', "WHERE BINARY nome = '".addslashes($nome)."'");
    if (!$dados) {
        inserir($nome, $exibe_painel, $descricao);
    } else {        
        $alert = ('Item já existe na base de dados!');
        $alert_type = 'w';        header("location: /api/iframe?token=$request->token&view=vinculo-tipo-form");
        exit;
    }

} else if (!empty($_POST['alterar'])) {
    $id = (int)$_POST['alterar'];
   
    $dados = DBRead('', 'tb_vinculo_tipo', "WHERE BINARY nome = '".addslashes($nome)."' AND id_vinculo_tipo != '$id'");
    if (!$dados) {
        alterar($id, $nome, $exibe_painel, $descricao);
    } else {
        $alert = ('Item já existe na base de dados!');
        $alert_type = 'w';        header("location: /api/iframe?token=$request->token&view=vinculo-tipo-form&alterar=$id");
        exit;
    }

} else if (isset($_GET['excluir'])) {

    $id = (int)$_GET['excluir'];
    excluir($id);

}else{
    header("location: ../adm.php");
    exit;
}

function inserir($nome, $exibe_painel, $descricao){

    $dados = array(
        'nome' => $nome,
        'exibe_painel' => $exibe_painel,
        'descricao' => $descricao
    );
    $isertID = DBCreate('', 'tb_vinculo_tipo', $dados, true);
    registraLog('Inserção de tipo de vínculo.','i','tb_vinculo_tipo',$isertID,"nome: $nome | exibe_painel: $exibe_painel | descricao: $descricao");
    $alert = ('Item inserido com sucesso!');
    $alert_type = 's';    header("location: /api/iframe?token=$request->token&view=vinculo-tipo-busca");
    exit;

}

function alterar($id, $nome, $exibe_painel, $descricao){

    $dados = array(
        'nome' => $nome,
        'exibe_painel' => $exibe_painel,
        'descricao' => $descricao
    );
    DBUpdate('', 'tb_vinculo_tipo', $dados, "id_vinculo_tipo = $id");
    registraLog('Alteração de tipo de vínculo.','a','tb_vinculo_tipo',$id,"nome: $nome | exibe_painel: $exibe_painel | descricao: $descricao");
    $alert = ('Item alterado com sucesso!');
    $alert_type = 's';    header("location: /api/iframe?token=$request->token&view=vinculo-tipo-busca");
    exit;

}

function excluir($id){
    $query = "DELETE FROM tb_vinculo_tipo WHERE id_vinculo_tipo = $id";
    $link = DBConnect('');
    $result = @mysqli_query($link, $query);
    DBClose($link);
    registraLog('Exclusão de tipo de vínculo.','e','tb_vinculo_tipo',$id,'');
    if(!$result){
$alert = ('Erro ao excluir item!');
        $alert_type = 'd';    }else{
        $alert = ('Item excluído com sucesso!');
    $alert_type = 's';    }    
    header("location: /api/iframe?token=$request->token&view=vinculo-tipo-busca");
    exit;

}

?>
