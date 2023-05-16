<?php
require_once(__DIR__."/System.php");

dd('chegou');

$nome = (!empty($_POST['nome'])) ? $_POST['nome'] : '';
$fixo = (!empty($_POST['fixo'])) ? $_POST['fixo'] : 0;
$dia = (!empty($_POST['dia'])) ? $_POST['dia'] : '';
$mes = (!empty($_POST['nome'])) ? $_POST['mes'] : '';
$data = $mes.'-'.$dia;
$tipo = (!empty($_POST['tipo'])) ? $_POST['tipo'] : '';
$id_cidade = (!empty($_POST['cidade'])) ? $_POST['cidade'] : '';
$id_estado = (!empty($_POST['estado'])) ? $_POST['estado'] : '';

$token = $request->token;

if (!empty($_POST['inserir'])) {

    inserir($token, $nome, $fixo, $data, $tipo, $id_cidade, $id_estado);

} else if (!empty($_POST['alterar'])) {
    $id = (int)$_POST['alterar'];

    alterar($token, $id, $nome, $fixo, $data, $tipo, $id_cidade, $id_estado);

} else if (isset($_GET['excluir'])) {

    $id = (int)$_GET['excluir'];
    excluir($token, $id);

} else{
    header("location: /api/iframe?token=".$token."&view=feriado-busca");
    exit;
}

function inserir($token, $nome, $fixo, $data, $tipo, $id_cidade, $id_estado){
    if($tipo == 'Nacional'){
        $id_cidade = NULL;
        $id_estado = NULL;
    }else if($tipo == 'Estadual'){
        $id_cidade = NULL;
    }
    $dados = array(
        'nome' => $nome,
        'fixo' => $fixo,
        'data' => $data,
        'tipo' => $tipo,
        'id_cidade' => $id_cidade,
        'id_estado' => $id_estado
    );
    $insertID = DBCreate('', 'tb_feriado', $dados, true);
    registraLog('Inserção de novo feriado.','i','tb_feriado',$insertID,"nome: $nome | fixo: $fixo | data: $data | tipo: $tipo | id_cidade: $id_cidade | id_estado: $id_estado");
    $alert = ('Item inserido com sucesso!');
    $alert_type = 's';
    header("location: /api/iframe?token=".$token."&view=feriado-busca");
    exit;
}

function alterar($token, $id, $nome, $fixo, $data, $tipo, $id_cidade, $id_estado){
    if($tipo == 'Nacional'){
        $id_cidade = NULL;
        $id_estado = NULL;
    }else if($tipo == 'Estadual'){
        $id_cidade = NULL;
    }
    $dados = array(
        'nome' => $nome,
        'fixo' => $fixo,
        'data' => $data,
        'tipo' => $tipo,
        'id_cidade' => $id_cidade,
        'id_estado' => $id_estado
    );
    DBUpdate('', 'tb_feriado', $dados, "id_feriado = $id");
    registraLog('Alteração de feriado.','i','tb_feriado',$id,"nome: $nome | fixo: $fixo | data: $data | tipo: $tipo | id_cidade: $id_cidade | id_estado: $id_estado");
    $alert = ('Item alterado com sucesso!');
    $alert_type = 's';

    header("location: /api/iframe?token=".$token."&view=feriado-busca");
    exit;
}

function excluir($token,$id){
    DBDelete('','tb_feriado',"id_feriado = '$id'");
    registraLog('Exclusão de feriado.','e','tb_feriado',$id,'');
    $alert = ('Item excluído com sucesso!');
    $alert_type = 's';
    header("location: /api/iframe?token=".$token."&view=feriado-busca");
    exit;
}
?>