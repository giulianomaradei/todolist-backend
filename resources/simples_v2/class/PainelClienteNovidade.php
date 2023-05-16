<?php
require_once(__DIR__."/System.php");


$descricao = (!empty($_POST['descricao'])) ? $_POST['descricao'] : '';
$data = (!empty($_POST['data'])) ? $_POST['data'] : '';
$data = converteData($data);

if (!empty($_POST['inserir'])) {

    inserir($descricao, $data);

}else if (!empty($_POST['alterar'])) {

    $id = (int)$_POST['alterar'];
    alterar($id, $descricao, $data);

}else if (!empty($_GET['excluir'])) {

    $id = (int)$_GET['excluir'];

    excluir($id);
}

function inserir($descricao, $data){

    if($descricao != ''){

        $dados = array(
            'data' => $data,
            'descricao' => $descricao
        );

        $isertID = DBCreate('', 'tb_painel_novidades', $dados, true);
        registraLog('Inserção de novidade no painel do cliente.','i','tb_painel_novidades',$isertID,"data: $data | descricao: $descricao");
        $alert = ('Item inserido com sucesso!');
    $alert_type = 's';        header("location: /api/iframe?token=$request->token&view=painel-cliente-novidade-busca");

    }else{
        $alert = ('Não foi possível alterar!','w');
        header("location: /api/iframe?token=$request->token&view=painel-cliente-novidade-form");
    }
}

function alterar($id, $descricao, $data){

    if($id != '' && $descricao !=''){

        $dados = array(
            'data' => $data,
            'descricao' => $descricao
        );

        DBUpdate('','tb_painel_novidades', $dados, "id_painel_novidades = $id");
        registraLog('Alteração na novidade do painel.', 'a', 'tb_painel_novidades', $id, "descricao: $descricao");
        $alert = ('Item inserido com sucesso!');
    $alert_type = 's';        header("location: /api/iframe?token=$request->token&view=painel-cliente-novidade-busca");

    }else{
        $alert = ('Não foi possível alterar!','w');
        header("location: /api/iframe?token=$request->token&view=painel-cliente-novidade-form&alterar=$id");
    }

}

function excluir($id){

    if($id != ''){

        $dados = array(
            'status' => '2',
        );

        DBUpdate('', 'tb_painel_novidades', $dados, "id_painel_novidades = '$id'");
        registraLog('Exclusão de novidade do painel.', 'e', 'tb_painel_novidades', $id, '');
        $alert = ('Item excluído com sucesso!', 's');
        header("location: /api/iframe?token=$request->token&view=painel-cliente-novidade-busca");

    }else{
        $alert = ('Não foi possível excluir!','w');
        header("location: /api/iframe?token=$request->token&view=painel-cliente-novidade-busca");
    }

}
?>