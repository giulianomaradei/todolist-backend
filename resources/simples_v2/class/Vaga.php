<?php
require_once(__DIR__."/System.php");


$cargo = (!empty($_POST['cargo'])) ? $_POST['cargo'] : '';
$tipo = (!empty($_POST['tipo'])) ? $_POST['tipo'] : '';
$data_inicio = (!empty($_POST['data_inicio'])) ? $_POST['data_inicio'] : '';
$data_fim = (!empty($_POST['data_fim'])) ? $_POST['data_fim'] : '';
$descricao = (!empty($_POST['descricao'])) ? $_POST['descricao'] : '';

if (!empty($_POST['inserir'])) {

    inserir($cargo, $tipo, $data_inicio, $data_fim, $descricao);

} else if (!empty($_POST['alterar'])) {

    $id_vaga = (int) $_POST['alterar'];

    alterar($id_vaga, $cargo, $tipo, $data_inicio, $data_fim, $descricao);

} else if (!empty($_GET['excluir'])) {

    $id_vaga = (int) $_GET['excluir'];

    excluir($id_vaga);

} else if (!empty($_POST['clonar'])) {

    $id_vaga = (int) $_POST['clonar'];

    inserir($cargo, $tipo, $data_inicio, $data_fim, $descricao);
        
} else {
    header("location: ../adm.php");
    exit;
}

function inserir($cargo, $tipo, $data_inicio, $data_fim, $descricao)
{
    if ($cargo != '' && $tipo !='' && $data_inicio != '' && $data_fim !='' && $descricao !='') {

        $data_inicio = converteData($data_inicio);
        $data_fim = converteData($data_fim);

        $dados = array(
            'id_cargo' => $cargo,
            'descricao' => $descricao,
            'tipo' => $tipo,
            'data_inicio' => $data_inicio,
            'data_fim' => $data_fim,
        );

        $insertID = DBCreate('', 'tb_vaga', $dados, true);
        registraLog('Inserção de vaga.', 'i', 'tb_vaga', $insertID, "id_cargo: $cargo | descricao: $descricao | tipo: $tipo | data_inicio: $data_inicio | data_fim: $data_fim");

        $alert = ('Vaga inserida com sucesso!', 's');
        header("location: /api/iframe?token=$request->token&view=vaga-busca");
    } else {
        $alert = ('Não foi possível inserir a vaga!', 'w');
        header("location: /api/iframe?token=$request->token&view=vaga-busca");
    }
}

function alterar($id_vaga, $cargo, $tipo, $data_inicio, $data_fim, $descricao)
{
    if ($id_vaga !='' && $cargo != '' && $tipo !='' && $data_inicio != '' && $data_fim !='' && $descricao !='') {

        $data_inicio = converteData($data_inicio);
        $data_fim = converteData($data_fim);

        $dados = array(
            'id_cargo' => $cargo,
            'descricao' => $descricao,
            'tipo' => $tipo,
            'data_inicio' => $data_inicio,
            'data_fim' => $data_fim,
        );

        DBUpdate('', 'tb_vaga', $dados, "id_vaga = $id_vaga");
        registraLog('Alteracao de vaga.', 'a', 'tb_vaga', $id_vaga, "id_cargo: $cargo | descricao: $descricao | tipo: $tipo | data_inicio: $data_inicio | data_fim: $data_fim");

        $alert = ('Vaga alterada com sucesso!', 's');
        header("location: /api/iframe?token=$request->token&view=vaga-form&alterar=$id_vaga");
    } else {
        $alert = ('Não foi possível alterar a vaga!', 'w');
        header("location: /api/iframe?token=$request->token&view=vaga-form&alterar=$id_vaga");
    }

}

function excluir($id_vaga)
{   
    if ($id_vaga != '') {
        
        $dados = array(
            'status' => 2
        );

        DBUpdate('', 'tb_vaga', $dados, "id_vaga = $id_vaga");
        registraLog('Alteração de status da vaga', 'a', 'tb_vaga', $id_vaga, "status: 2");

        $alert = ('Vaga excluída com sucesso!', 's');
        header("location: /api/iframe?token=$request->token&view=vaga-busca");
    } else {
        $alert = ('Não foi possível excluir a vaga!', 'd');
        header("location: /api/iframe?token=$request->token&view=vaga-busca");
    }
    
}
