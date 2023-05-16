<?php
require_once(__DIR__."/System.php");


$nome_view = (!empty($_POST['nome_view'])) ? $_POST['nome_view'] : '';
$nome_pagina = (!empty($_POST['nome_pagina'])) ? $_POST['nome_pagina'] : '0';
$menu = (!empty($_POST['menu'])) ? $_POST['menu'] : '';

if (!empty($_POST['inserir'])) {
    
    $dados = DBRead('', 'tb_pagina_sistema', "WHERE BINARY nome_view = '".addslashes($nome_view)."'");
    if (!$dados) {
        inserir($nome_view, $nome_pagina, $menu);
    } else {        
        $alert = ('Item já existe na base de dados!');
        $alert_type = 'w';        header("location: /api/iframe?token=$request->token&view=pagina-sistema-form");
        exit;
    }

} else if (!empty($_POST['alterar'])) {
    $id = (int)$_POST['alterar'];   
   
    $dados = DBRead('', 'tb_pagina_sistema', "WHERE BINARY nome_view = '".addslashes($nome_view)."' AND id_pagina_sistema != '$id'");
    if (!$dados) {
        alterar($id, $nome_view, $nome_pagina, $menu);
    } else {
        $alert = ('Item já existe na base de dados!');
        $alert_type = 'w';        header("location: /api/iframe?token=$request->token&view=pagina-sistema-form&alterar=$id");
        exit;
    }

} else if (isset($_GET['excluir'])) {

    $id = (int)$_GET['excluir'];
    excluir($id);

}else{
    header("location: ../adm.php");
    exit;
}

function inserir($nome_view, $nome_pagina, $menu){

    $dados = array(
        'nome_view' => $nome_view,
        'nome_pagina' => $nome_pagina,
        'menu' => $menu
    );
    $isertID = DBCreate('', 'tb_pagina_sistema', $dados, true);
    registraLog('Inserção de página do sistema.','i','tb_pagina_sistema',$isertID,"nome_view: $nome_view | nome_pagina: $nome_pagina | menu: $menu");
    $alert = ('Item inserido com sucesso!');
    $alert_type = 's';    header("location: /api/iframe?token=$request->token&view=pagina-sistema-busca");
    exit;

}

function alterar($id, $nome_view, $nome_pagina, $menu){

    $dados = array(
        'nome_view' => $nome_view,
        'nome_pagina' => $nome_pagina,
        'menu' => $menu
    );
    DBUpdate('', 'tb_pagina_sistema', $dados, "id_pagina_sistema = $id");
    registraLog('Alteração de página do sistema.','a','tb_pagina_sistema',$id,"nome_view: $nome_view | nome_pagina: $nome_pagina | menu: $menu");
    $alert = ('Item alterado com sucesso!');
    $alert_type = 's';    header("location: /api/iframe?token=$request->token&view=pagina-sistema-busca");
    exit;

}

function excluir($id){
    $query = "DELETE FROM tb_pagina_sistema WHERE id_pagina_sistema = $id";
    $link = DBConnect('');
    $result = @mysqli_query($link, $query);
    DBClose($link);
    registraLog('Exclusão de página do sistema.','e','tb_pagina_sistema',$id,'');
    if(!$result){
$alert = ('Erro ao excluir item!');
        $alert_type = 'd';    }else{
        $alert = ('Item excluído com sucesso!');
    $alert_type = 's';    }    
    header("location: /api/iframe?token=$request->token&view=pagina-sistema-busca");
    exit;

}

?>
