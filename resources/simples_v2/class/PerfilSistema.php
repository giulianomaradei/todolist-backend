<?php
require_once(__DIR__."/System.php");


$nome = (!empty($_POST['nome'])) ? $_POST['nome'] : '';
$permissoes = (!empty($_POST['permissoes'])) ? $_POST['permissoes'] : '';
$perfis_vinculados = (!empty($_POST['perfis_vinculados'])) ? $_POST['perfis_vinculados'] : '';
$status = (!empty($_POST['status'])) ? $_POST['status'] : 0;
$id_perfil_sistema_superior = (!empty($_POST['id_perfil_sistema_superior'])) ? $_POST['id_perfil_sistema_superior'] : 0;

if (!empty($_POST['inserir'])) {

    $dados = DBRead('', 'tb_perfil_sistema', "WHERE BINARY nome = '".addslashes($nome)."'");
    if (!$dados) {
        inserir($nome, $permissoes, $status, $id_perfil_sistema_superior, $perfis_vinculados);
    } else {
        $alert = ('Item já existe na base de dados!');
        $alert_type = 'w';        header("location: /api/iframe?token=$request->token&view=perfil-sistema-form");
        exit;
    }

} else if (!empty($_POST['alterar'])) {
   
    $id = (int)$_POST['alterar'];
    
        $dados = DBRead('', 'tb_perfil_sistema', "WHERE BINARY nome = '".addslashes($nome)."' AND id_perfil_sistema != '$id'");
        if (!$dados) {
            alterar($id, $nome, $permissoes, $status, $id_perfil_sistema_superior, $perfis_vinculados);
        } else {
            $alert = ('Item já existe na base de dados!');
        $alert_type = 'w';            header("location: /api/iframe?token=$request->token&view=perfil-sistema-form&alterar=$id");
            exit;
        }
   
} else if (isset($_GET['excluir'])) {

    $id = (int)$_GET['excluir'];
    excluir($id);

}else{
    header("location: ../adm.php");
    exit;
}

function inserir($nome, $permissoes, $status, $id_perfil_sistema_superior, $perfis_vinculados){
    
    $dados = array(
        'nome' => $nome,
        'status' => $status,
        'id_perfil_sistema_superior' => $id_perfil_sistema_superior
    );

    $perfil = DBCreate('', 'tb_perfil_sistema', $dados, true);
    registraLog('Inserção de perfil do sistema.','i','tb_perfil_sistema',$perfil,"nome: $nome | status: $status | id_perfil_sistema_superior: $id_perfil_sistema_superior");

    foreach ($permissoes as $id_pagina_sistema){
        
        $dados = array(
            'id_perfil_sistema' => $perfil,
            'id_pagina_sistema' => $id_pagina_sistema,
        );

        $pagina_sistema_perfil = DBCreate('', 'tb_pagina_sistema_perfil', $dados, true);
        registraLog('Inserção de permissao de página.','i','tb_pagina_sistema_perfil',$pagina_sistema_perfil,"id_perfil_sistema: $perfil | id_pagina_sistema: $id_pagina_sistema");
    }

    if ($perfis_vinculados != '') {
        foreach ($perfis_vinculados as $id_perfil_vinculado){
            
            $dados = array(
                'id_perfil_sistema' => $perfil,
                'id_perfil_sistema_vinculado' => $id_perfil_vinculado,
            );

            $pagina_sistema_vinculado = DBCreate('', 'tb_perfil_sistema_vinculo', $dados, true);
            registraLog('Inserção de perfil sistema vinculado.','i','tb_perfil_sistema_vinculo',$pagina_sistema_vinculado,"id_perfil_sistema: $perfil | id_perfil_sistema_vinculado: $pagina_sistema_vinculado");
        }
    }

    $alert = ('Item inserido com sucesso!');
    $alert_type = 's';    header("location: /api/iframe?token=$request->token&view=perfil-sistema-busca");
    exit;
}

function alterar($id, $nome, $permissoes, $status, $id_perfil_sistema_superior, $perfis_vinculados){

    /*$perfil_superior = DBRead('', 'tb_perfil_sistema', "WHERE id_perfil_sistema_superior = $id");

    if($perfil_superior){

        $perfis = '';
        foreach($perfil_superior as $key => $perfil){
            
            $perfis .= $perfil_superior[$key]['nome'].', ';
        }

        //Verifica se o perfil que está tentando alterar status do superior imediato de algum outro perfil
        $alert = ('Este perfil corresponde a um superior imediato de outro perfil! Retire deste perfil(s) '.$perfis.' e tente novamente.','d');
        header("location: /api/iframe?token=$request->token&view=perfil-sistema-busca");
        exit;
    }else{*/

        $dados = array(
            'nome' => $nome,
            'status' => $status,
            'id_perfil_sistema_superior' => $id_perfil_sistema_superior
        );

        DBUpdate('', 'tb_perfil_sistema', $dados, "id_perfil_sistema = '$id'");
        registraLog('Alteração de perfil do sistema.','a','tb_perfil_sistema',$id,"nome: $nome | status: $status | id_perfil_sistema_superior: $id_perfil_sistema_superior");

        DBDelete('','tb_pagina_sistema_perfil',"id_perfil_sistema = '$id'"); 

        foreach ($permissoes as $id_pagina_sistema) {
            $dados = array(
                'id_perfil_sistema' => $id,
                'id_pagina_sistema' => $id_pagina_sistema,
            );

            $pagina_sistema_perfil = DBCreate('', 'tb_pagina_sistema_perfil', $dados, true);
            registraLog('Inserção de permissao de página.','i','tb_pagina_sistema_perfil',$pagina_sistema_perfil,"id_perfil_sistema: $id | id_pagina_sistema: $id_pagina_sistema");
        }

        DBDelete('','tb_perfil_sistema_vinculo',"id_perfil_sistema = '$id'"); 
        if ($perfis_vinculados != '') {

            foreach ($perfis_vinculados as $id_perfil_vinculado){
                
                $dados = array(
                    'id_perfil_sistema' => $id,
                    'id_perfil_sistema_vinculado' => $id_perfil_vinculado,
                );
    
                $pagina_sistema_vinculado = DBCreate('', 'tb_perfil_sistema_vinculo', $dados, true);
                registraLog('Inserção de perfil sistema vinculado.','i','tb_perfil_sistema_vinculo',$pagina_sistema_vinculado,"id_perfil_sistema: $$id | id_perfil_sistema_vinculado: $pagina_sistema_vinculado");
            }
        }

        $alert = ('Item alterado com sucesso!');
    $alert_type = 's';        header("location: /api/iframe?token=$request->token&view=perfil-sistema-busca");
        exit;
    //}
}

function excluir($id){

    $perfil_superior = DBRead('', 'tb_perfil_sistema', "WHERE id_perfil_sistema_superior = $id");

    if($perfil_superior){

        $perfis = '';
        foreach($perfil_superior as $key => $perfil){
            
            $perfis .= $perfil_superior[$key]['nome'].', ';
        }

        //Verifica se o perfil que está tentando excluir é superior imediato de algum outro perfil
        $alert = ('Este perfil corresponde a um superior imediato de outro perfil! Retire deste perfil(s) '.$perfis.' e tente novamente.','d');
        header("location: /api/iframe?token=$request->token&view=perfil-sistema-busca");
        exit;
    }else{
        $query = "DELETE FROM tb_perfil_sistema WHERE id_perfil_sistema = $id";
        $link = DBConnect('');
        $result = @mysqli_query($link, $query);
        DBClose($link);
        registraLog('Exclusão de perfil do sistema.','e','tb_perfil_sistema',$id,'');
        if(!$result){
            $alert = ('Erro ao excluir item!');
            $alert_type = 'd';        }else{
            $alert = ('Item excluído com sucesso!');
    $alert_type = 's';        } 
        header("location: /api/iframe?token=$request->token&view=perfil-sistema-busca");
        exit;
    }
}

?>
