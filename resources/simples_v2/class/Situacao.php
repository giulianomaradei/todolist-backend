<?php
require_once(__DIR__."/System.php");


$nome = (!empty($_POST['nome'])) ? $_POST['nome'] : '';

if (!empty($_POST['inserir'])) {
    
    $dados = DBRead('', 'tb_situacao', "WHERE BINARY nome = '".addslashes($nome)."'");
    if (!$dados) {
        inserir($nome);
    } else {        
        $alert = ('Item já existe na base de dados!');
        $alert_type = 'w';        header("location: /api/iframe?token=$request->token&view=situacao-form");
        exit;
    }

} else if (!empty($_POST['alterar'])) {
    $id = (int)$_POST['alterar'];
   
    $dados = DBRead('', 'tb_situacao', "WHERE BINARY nome = '".addslashes($nome)."' AND id_situacao != '$id'");
    if (!$dados) {
        alterar($id, $nome);
    } else {
        $alert = ('Item já existe na base de dados!');
        $alert_type = 'w';        header("location: /api/iframe?token=$request->token&view=situacao-form&alterar=$id");
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
    $insertID = DBCreate('', 'tb_situacao', $dados, true);
    registraLog('Inserção de Situação.','i','tb_situacao',$insertID,"nome: $nome");
    $alert = ('Item inserido com sucesso!');
    $alert_type = 's';    header("location: /api/iframe?token=$request->token&view=situacao-busca");

}else{

        $alert = ('Não foi possível inserir o item!');
        $alert_type = 'w';                header("location: /api/iframe?token=$request->token&view=situacao-form");

}
    
    exit;


}

function alterar($id, $nome){

if($nome != "" && $nome){

    $dados = array(
        'nome' => $nome
    );
    DBUpdate('', 'tb_situacao', $dados, "id_situacao = $id");
    registraLog('Alteração de situação.','a','tb_situacao',$id,"nome: $nome");
    $alert = ('Item alterado com sucesso!');
    $alert_type = 's';    header("location: /api/iframe?token=$request->token&view=situacao-busca");
 
}else{

    $alert = 'Não foi possível alterar o item!' ;
    header("location: /api/iframe?token=$request->token&view=situacao-form&alterar=$id");
    
}
    
    exit;

}

function excluir($id){
    $query = "DELETE FROM tb_situacao WHERE id_situacao = $id";
    $link = DBConnect('');
    $result = @mysqli_query($link, $query);
    DBClose($link);
    registraLog('Exclusão da situação.','e','tb_situacao',$id,'');
    if(!$result){
$alert = ('Erro ao excluir item!');
        $alert_type = 'd';    }else{
        $alert = ('Item excluído com sucesso!');
    $alert_type = 's';    }
    header("location: /api/iframe?token=$request->token&view=situacao-busca");
    exit;

}

?>
