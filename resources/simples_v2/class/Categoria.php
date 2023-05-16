<?php
require_once(__DIR__."/System.php");

$nome = (!empty($_POST['nome'])) ? $_POST['nome'] : '';
$exibe_topico = (!empty($_POST['exibe_topico'])) ? $_POST['exibe_topico'] : '2';
$exibe_chamado = (!empty($_POST['exibe_chamado'])) ? $_POST['exibe_chamado'] : '2';
$exibe_alerta = (!empty($_POST['exibe_alerta'])) ? $_POST['exibe_alerta'] : '2';

if (!empty($_POST['inserir'])) {
    
    $dados = DBRead('', 'tb_categoria', "WHERE BINARY nome = '".addslashes($nome)."'");
    if (!$dados) {
        inserir($nome, $exibe_topico, $exibe_chamado, $exibe_alerta);
    } else {        
        $alert = ('Item já existe na base de dados!');
        $alert_type = 'w';        header("location: /api/iframe?token=$request->token&view=categoria-form");
        exit;
    }

} else if (!empty($_POST['alterar'])) {
    $id = (int)$_POST['alterar'];
   
    $dados = DBRead('', 'tb_categoria', "WHERE BINARY nome = '".addslashes($nome)."' AND id_categoria != '$id'");

    if (!$dados) {
        alterar($id, $nome, $exibe_topico, $exibe_chamado, $exibe_alerta);
    } else {
        $alert = ('Item já existe na base de dados!');
        $alert_type = 'w';        header("location: /api/iframe?token=$request->token&view=categoria-form&alterar=$id");
        exit;
    }

} else if (isset($_GET['excluir'])) {

    $id = (int)$_GET['excluir'];
    excluir($id);

}else{
    header("location: ../adm.php");
    exit;
}

function inserir($nome, $exibe_topico, $exibe_chamado, $exibe_alerta){
    $dados = array(
        'nome' => $nome,
        'exibe_topico' => $exibe_topico,
        'exibe_chamado' =>  $exibe_chamado,
        'exibe_alerta' =>  $exibe_alerta
    );

    if($nome && $nome != ""){
        $insertID = DBCreate('', 'tb_categoria', $dados, true);
        registraLog('Inserção de nova categoria.','i','tb_categoria',$insertID,"nome: $nome | exibe_topico: $exibe_topico | exibe_chamado: $exibe_chamado | exibe_alerta: $exibe_alerta");
        $alert = ('Item inserido com sucesso!');
    $alert_type = 's';        header("location: /api/iframe?token=$request->token&view=categoria-busca");
    }else{
        $alert = ('Não foi possível inserir o item!');
        $alert_type = 'w';                header("location: /api/iframe?token=$request->token&view=categoria-form");
    }
    exit;
}

function alterar($id, $nome, $exibe_topico, $exibe_chamado, $exibe_alerta){

    $dados = array(
        'nome' => $nome,
        'exibe_topico' => $exibe_topico,
        'exibe_chamado' =>  $exibe_chamado,
        'exibe_alerta' =>  $exibe_alerta
    );

    if($nome && $nome != ""){
        DBUpdate('', 'tb_categoria', $dados, "id_categoria = $id");
        registraLog('Alteração de categoria.','a','tb_categoria',$id,"nome: $nome | exibe_topico: $exibe_topico | exibe_chamado: $exibe_chamado | exibe_alerta: $exibe_alerta");
        $alert = ('Item alterado com sucesso!');
    $alert_type = 's';        header("location: /api/iframe?token=$request->token&view=categoria-busca");
    }else{
        $alert = 'Não foi possível alterar o item!' ;
        $alert_type = 'w';                header("location: /api/iframe?token=$request->token&view=categoria-form&alterar=$id");
    }
    
    exit;
}

function excluir($id){
    $query = "DELETE FROM tb_categoria WHERE id_categoria = $id";
    $link = DBConnect('');
    $result = @mysqli_query($link, $query);
    DBClose($link);
    registraLog('Exclusão de categoria.','e','tb_categoria',$id,'');
    if(!$result){
$alert = ('Erro ao excluir item!');
        $alert_type = 'd';    }else{
        $alert = ('Item excluído com sucesso!');
    $alert_type = 's';    }
    header("location: /api/iframe?token=$request->token&view=categoria-busca");
    exit;
}

?>