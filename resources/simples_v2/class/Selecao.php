<?php
require_once(__DIR__."/System.php");


$nome = (!empty($_POST['nome'])) ? $_POST['nome'] : '';
$descricao = (!empty($_POST['descricao'])) ? $_POST['descricao'] : '';
$setor = (!empty($_POST['setor'])) ? $_POST['setor'] : '';
$cargo = (!empty($_POST['cargo'])) ? $_POST['cargo'] : '';
$nvagas = (!empty($_POST['nvagas'])) ? $_POST['nvagas'] : '';
$netapas = (!empty($_POST['netapas'])) ? $_POST['netapas'] : '';
$id_vaga = (!empty($_POST['id_vaga'])) ? $_POST['id_vaga'] : null;

if (!empty($_POST['inserir'])){
    inserir($nome, $descricao, $setor, $cargo, $nvagas, $netapas, $id_vaga);

} else if (!empty($_POST['inserir_avaliador'])){
    
    $id_selecao = (!empty($_POST['id_selecao'])) ? $_POST['id_selecao'] : '';
    $etapa = (!empty($_POST['etapa'])) ? $_POST['etapa'] : '';
    $avaliador = (!empty($_POST['avaliador'])) ? $_POST['avaliador'] : '';

    $check = DBRead('', 'tb_selecao_etapa_avaliador', "WHERE id_selecao_etapa = $etapa AND id_usuario_avaliador = $avaliador");

    if ($check) {
        $alert = ('Esta pessoa já consta como avaliador desta etapa!','d');
		header("location: /api/iframe?token=$request->token&view=selecao-avaliador-form&idselecao=$id_selecao");
		exit;

    } else {
        inserir_avaliador($id_selecao, $etapa, $avaliador);
    }
}

function inserir($nome, $descricao, $setor, $cargo, $nvagas, $netapas, $id_vaga)
{
    if ($descricao != '' && $setor != '' && $cargo != '' && $nvagas != '' && $netapas != '' && $nome !='') {        

        if ($id_vaga != null) {
            $vaga = DBRead('', 'tb_vaga', "WHERE id_vaga = $id_vaga");

            if ($vaga[0]['id_cargo'] != $cargo) {
                $alert = ('Carga da seleção não condiz com o carga da vaga selecionada!','d');
                header("location: /api/iframe?token=$request->token&view=selecao-form");
                exit;
            }
        }

        $data = getdatahora();

        $dados = array(
            'data' => $data,
            'id_setor' => $setor,
            'id_cargo' => $cargo,
            'descricao' => $descricao,
            'n_vagas' => $nvagas,
            'n_etapas' => $netapas,
            'status' => 3,
            'nome' => $nome,
            'id_vaga' => $id_vaga
        );

        $insertID = DBCreate('', 'tb_selecao', $dados, true);
        registraLog('Inserção de seleção rh.', 'i', 'tb_selecao', $insertID, "data: $data | id_setor: $setor | id_cargo: $cargo | descricao: $descricao | n_vagas: $nvagas | n_etapas: $netapas | status: 1 | nome: $nome | id_vaga: $id_vaga");

		header("location: /api/iframe?token=$request->token&view=selecao-etapa-form&netapas=".$netapas."&idselecao=".$insertID."");
        exit;
        
    } else {
        $alert = ('Não foi possivel criar item!','d');
		header("location: /api/iframe?token=$request->token&view=selecao-form");
		exit;
    }
}

function inserir_avaliador($id_selecao, $etapa, $avaliador)
{
    if ($id_selecao != '' && $etapa != '' && $avaliador != '') {

        $dados = array(
            'id_selecao_etapa' => $etapa,
            'id_usuario_avaliador' => $avaliador
        );

        $insertID = DBCreate('', 'tb_selecao_etapa_avaliador', $dados, true);
        registraLog('Inserção de avaliador etapa seleção.', 'i', 'tb_selecao_etapa_avaliador', $insertID, "id_selecao_etapa: $etapa | id_usuario_avaliador: $avaliador");

		header("location: /api/iframe?token=$request->token&view=selecao-informacoes&idselecao=$id_selecao");
        exit;

    } else {
        $alert = ('Não foi possivel inserir o avaliador!','d');
		header("location: /api/iframe?token=$request->token&view=selecao-avaliador-form&idselecao=$id_selecao");
		exit;
    }
}
