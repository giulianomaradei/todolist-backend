<?php
require_once(__DIR__."/System.php");

$id_usuario = (!empty($_POST['id_usuario'])) ? $_POST['id_usuario'] : '';
$data_inicio = (!empty($_POST['data_inicio'])) ? $_POST['data_inicio'] : '';
$data_fim = (!empty($_POST['data_fim'])) ? $_POST['data_fim'] : null;
$formato = (!empty($_POST['formato'])) ? $_POST['formato'] : '';
$motivo = (!empty($_POST['motivo'])) ? $_POST['motivo'] : '';
$demissao = (!empty($_POST['demissao'])) ? $_POST['demissao'] : null;
$escolaridade = (!empty($_POST['escolaridade'])) ? $_POST['escolaridade'] : '';
$id_funcionario = (!empty($_POST['id_funcionario'])) ? $_POST['id_funcionario'] : '';
$id_funcionario_periodo = (!empty($_POST['id_funcionario_periodo'])) ? $_POST['id_funcionario_periodo'] : '';

if (!empty($_POST['inserir'])) {
    
    $dados = DBRead('', 'tb_funcionario', "WHERE id_usuario = $id_usuario");
    if (!$dados) {
        inserir($id_usuario, $data_inicio, $formato, $escolaridade);

    } else {        
        $alert = ('Funcionário já cadastrado!','w');
        header("location: /api/iframe?token=$request->token&view=funcionario-busca");
        exit;
    }
} else if (!empty($_POST['inserir_periodo'])) {
    inserir_periodo($id_funcionario, $data_inicio, $formato, $escolaridade);

} else if (!empty($_POST['alterar_periodo'])) {
    alterar_periodo($id_funcionario, $id_funcionario_periodo, $data_inicio, $data_fim, $formato, $motivo, $escolaridade, $demissao);

} else if (!empty($_GET['excluir_periodo'])) {
    $id_funcionario_periodo = (int) $_GET['excluir_periodo'];
    $id_funcionario = (int) $_GET['id_funcionario'];

    excluir_periodo($id_funcionario, $id_funcionario_periodo);
}

function inserir($id_usuario, $data_inicio, $formato, $escolaridade) 
{
    if ($id_usuario != '') { 
        $dados = array(
            'id_usuario' => $id_usuario,
        );
        
        $insertID = DBCreate('', 'tb_funcionario', $dados, true);
        registraLog('Inserção de novo funcionario.','i','tb_alerta',$insertID,"id_usuario: $id_usuario");

        inserir_periodo($insertID, $data_inicio, $formato, $escolaridade);

    } else {
        $alert = ('Não foi possível cadastrar o funcionário!','d');
        header("location: /api/iframe?token=$request->token&view=funcionario-busca");
    }
}

function inserir_periodo($id_funcionario, $data_inicio, $formato, $escolaridade) 
{
    if ($id_funcionario !='' && $data_inicio !='' && $formato !='' && $escolaridade != '') { 
        
        $check = DBRead('', 'tb_funcionario_periodo', "WHERE id_funcionario = $id_funcionario ORDER BY id_funcionario_periodo DESC LIMIT 1");

        if ($check[0]['data_fim'] > converteData($data_inicio)) {
            $alert = ('Não foi possível cadastrar o período do funcionário! Período deve iniciar após a data fim da última admissão!','d');
            header("location: /api/iframe?token=$request->token&view=funcionario-informacoes&id_funcionario=$id_funcionario");

        } else {

            $dados = array(
                'id_funcionario' => $id_funcionario,
                'data_inicio' => converteData($data_inicio),
                'formato' => $formato,
                'escolaridade' => $escolaridade
            );
            
            $insertID = DBCreate('', 'tb_funcionario_periodo', $dados, true);
            registraLog('Inserção de novo periodo funcionario.','i','tb_funcionario_periodo',$insertID,"id_funcionario: $id_funcionario |data_inicio: $data_inicio | formato: $formato | escolaridade: $escolaridade");

            $alert = ('Período cadastrado com sucesso!','s');
            header("location: /api/iframe?token=$request->token&view=funcionario-busca");
        }

    } else {
        $alert = ('Não foi possível cadastrar o período do funcionário!','d');
        header("location: /api/iframe?token=$request->token&view=funcionario-busca");
    }
}

function alterar_periodo($id_funcionario, $id_funcionario_periodo, $data_inicio, $data_fim, $formato, $motivo, $escolaridade, $demissao)
{
    if ($id_funcionario !='' && $id_funcionario_periodo !='' && $data_inicio !='' && $formato !='' && $escolaridade != '') { 

        if ($data_fim != null) {
            $data_fim = converteData($data_fim);
        }
        
        $dados = array(
            'data_inicio' => converteData($data_inicio),
            'data_fim' => $data_fim,
            'formato' => $formato,
            'motivo' => $motivo,
            'escolaridade' => $escolaridade,
            'demissao' => $demissao
        );
        
        DBUpdate('', 'tb_funcionario_periodo', $dados, "id_funcionario_periodo = $id_funcionario_periodo");
        registraLog('Alteração deperiodo funcionario.','a','tb_funcionario_periodo',$id_funcionario_periodo,"data_inicio: $data_inicio | data_fim: $data_fim | formato: $formato | motivo: $motivo | escolaridade: $escolaridade | demissao: $demissao");

        $alert = ('Período alterado com sucesso!','s');
        header("location: /api/iframe?token=$request->token&view=funcionario-informacoes&id_funcionario=$id_funcionario");

    } else {
        $alert = ('Não foi possível alterar o período do funcionário!','d');
        header("location: /api/iframe?token=$request->token&view=funcionario-informacoes&id_funcionario=$id_funcionario");
    }
}

function excluir_periodo($id_funcionario, $id_funcionario_periodo)
{   
    if ($id_funcionario != '' && $id_funcionario_periodo != '') {
        
        DBDelete('', 'tb_funcionario_periodo', "id_funcionario_periodo = '$id_funcionario_periodo'");
        registraLog('Exclusão de periodo funcionario.','e','tb_funcionario_periodo',$id_funcionario_periodo,"id_funcionario: $id_funcionario");

        $alert = ('Período excluído com sucesso!','s');
        header("location: /api/iframe?token=$request->token&view=funcionario-informacoes&id_funcionario=$id_funcionario");

    } else {
        $alert = ('Não foi possível excluir o período do funcionário!','d');
        header("location: /api/iframe?token=$request->token&view=funcionario-informacoes&id_funcionario=$id_funcionario");
    }
}