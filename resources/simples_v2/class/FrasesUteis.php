<?php
require_once(__DIR__."/System.php");

$texto = (!empty($_POST['texto'])) ? $_POST['texto'] : '';

if (!empty($_POST['inserir'])) {
    
    $dados = DBRead('', 'tb_frase_util', "WHERE BINARY texto = '".addslashes($texto)."'");
    if (!$dados) {
       inserir($texto);
    } else {        
        $alert = ('Item já existe na base de dados!');
        $alert_type = 'w';        header("location: /api/iframe?token=$request->token&view=frases-uteis-form");
        exit;
    }

} else if (!empty($_POST['alterar'])) {
    $id = (int)$_POST['alterar'];
   
    $dados = DBRead('', 'tb_frase_util', "WHERE BINARY texto = '".addslashes($texto)."' AND id_frase_util != '$id'");
    if (!$dados) {
        alterar($id, $texto);
    } else {
        $alert = ('Item já existe na base de dados!');
        $alert_type = 'w';        header("location: /api/iframe?token=$request->token&view=frases-uteis-form&alterar=$id");
        exit;
    }

} else if (isset($_GET['excluir'])) {

    $id = (int)$_GET['excluir'];
    excluir($id);

}else{
    header("location: ../adm.php");
    exit;
}

function inserir($texto){

    if($texto != ""){
        $dados = array(
            'texto' => $texto
        );
        $insertID = DBCreate('', 'tb_frase_util', $dados, true);
        registraLog('Inserção de frase.','i','tb_frase_util',$insertID,"texto: $texto");
        $alert = ('Item inserido com sucesso!');
    $alert_type = 's';        header("location: /api/iframe?token=$request->token&view=frases-uteis");
     }else{
        $alert = ('Não foi possível inserir o item!');
        $alert_type = 'w';                header("location: /api/iframe?token=$request->token&view=frases-uteis-form");
    }
    exit;

}

function alterar($id, $texto){

    if($texto != "" && $texto){
        $dados = array(
            'texto' => $texto
        );
        DBUpdate('', 'tb_frase_util', $dados, "id_frase_util = $id");
        registraLog('Alteração de frase.','a','tb_frase_util',$id,"texto: $texto");
        $alert = ('Item alterado com sucesso!');
    $alert_type = 's';        header("location: /api/iframe?token=$request->token&view=frases-uteis");
    }else{

        $alert = 'Não foi possível alterar o item!' ;
        $alert_type = 'w';                header("location: /api/iframe?token=$request->token&view=frases-uteis-form&alterar=$id");
    }
    exit;
}

function excluir($id){
    
    $query = "DELETE FROM tb_frase_util WHERE id_frase_util = $id";
    $link = DBConnect('');
    $result = @mysqli_query($link, $query);
    DBClose($link);
    registraLog('Exclusão de frase.','e','tb_frase_util',$id,'');
    if(!$result){
$alert = ('Erro ao excluir item!');
        $alert_type = 'd';    }else{
        $alert = ('Item excluído com sucesso!');
    $alert_type = 's';    }
    header("location: /api/iframe?token=$request->token&view=frases-uteis");
    exit;
}

?>
