<?php
require_once(__DIR__."/System.php");


$nota = (!empty($_POST['nota'])) ? $_POST['nota'] : null;
$parecer = (!empty($_POST['parecer'])) ? $_POST['parecer'] : null;
$troca_status = (!empty($_POST['troca_status'])) ? $_POST['troca_status'] : '';
$avanca_etapa = (!empty($_POST['avanca_etapa'])) ? $_POST['avanca_etapa'] : '';
$id_selecao_etapa = (!empty($_POST['id_selecao_etapa'])) ? $_POST['id_selecao_etapa'] : '';
$id_selecao_candidato = (!empty($_POST['id_selecao_candidato'])) ? $_POST['id_selecao_candidato'] : '';
$id_selecao = (!empty($_POST['id_selecao'])) ? $_POST['id_selecao'] : '';
$id_candidato = (!empty($_POST['id_candidato'])) ? $_POST['id_candidato'] : '';
$id_usuario = $_SESSION['id_usuario'];


if (!empty($_POST['inserir'])){

    $verifica_avaliador = DBRead('', 'tb_selecao_etapa_avaliador', "WHERE id_selecao_etapa = $id_selecao_etapa AND id_usuario_avaliador = $id_usuario");

    if ($verifica_avaliador) {

        $id_selecao_etapa_avaliador = $verifica_avaliador[0]['id_selecao_etapa_avaliador'];

        inserir($troca_status, $avanca_etapa, $nota, $parecer, $id_selecao_etapa_avaliador, $id_selecao_candidato, $id_selecao, $id_candidato);
    } else {
       $alert = ('Item criado com sucesso!','s');
        header("location: /api/iframe?token=$request->token&view=selecao-informacoes&idselecao=$id_selecao");
    }
} else if (!empty($_POST['alterar'])){

    $id_avaliador_candidato_alterar = (!empty($_POST['id_avaliador_candidato_alterar'])) ? $_POST['id_avaliador_candidato_alterar'] : '';

    alterar($id_selecao, $id_candidato, $id_avaliador_candidato_alterar, $nota, $parecer);
}

function inserir($troca_status, $avanca_etapa, $nota, $parecer, $id_selecao_etapa_avaliador, $id_selecao_candidato, $id_selecao, $id_candidato){

    if ($id_selecao_candidato != '' && $id_selecao_etapa_avaliador != '') {

        $link = DBConnect('');
        DBBegin($link);
        
        if ($nota != '' || $parecer != '') {

            $data = getDataHora();

            $dados = array(
                'id_selecao_etapa_avaliador' => $id_selecao_etapa_avaliador,
                'nota' => $nota,
                'parecer' => $parecer,
                'id_selecao_candidato' => $id_selecao_candidato,
                'data_avaliacao' => $data
            );

            $insertID = DBCreateTransaction($link, 'tb_selecao_avaliador_candidato', $dados, true);
            
            registraLogTransaction($link, 'Inserção de avaliação candidato.', 'i', '', $insertID, "id_selecao_etapa_avaliador: $id_selecao_etapa_avaliador | nota: $nota | parecer: $parecer | id_selecao_candidato: $id_selecao_candidato | data_avaliacao: $data");
        
        }

        if ($troca_status != '') {

            $dados_status = array(
                'status' => $troca_status
            );
            
            $insertID = DBUpdateTransaction($link, 'tb_selecao_candidato', $dados_status, "id_selecao_candidato = $id_selecao_candidato");
            registraLogTransaction($link, 'Alteração de status candidato.', 'a', 'tb_selecao_candidato', $id_selecao_candidato, "status: $troca_status");
        }

        if ($avanca_etapa == 1) {

            $verifica_etapa = DBReadTransaction($link, 'tb_selecao_candidato', "WHERE id_selecao_candidato = $id_selecao_candidato");

            $etapa = $verifica_etapa[0]['etapa'] + 1;

            $dados_etapa = array(
                'etapa' => $etapa,
                'status' => 1
            );
            
            $insertID = DBUpdateTransaction($link, 'tb_selecao_candidato', $dados_etapa, "id_selecao_candidato = $id_selecao_candidato");
            registraLogTransaction($link, 'Alteração de etapa candidato.', 'a', 'tb_selecao_candidato', $id_selecao_candidato, "etapa: $etapa | status: 1");
        }

        DBCommit($link);

        $alert = ('Item criado com sucesso!','s');
        header("location: /api/iframe?token=$request->token&view=selecao-informacoes&idselecao=$id_selecao");

    } else {
        $alert = ('Não foi possivel criar item!','d');
        header("location: /api/iframe?token=$request->token&view=selecao-avaliar-form&idselecao=$id_selecao&idcandidato=$id_candidato");
    }
    
}

function alterar($id_selecao, $id_candidato, $id_avaliador_candidato_alterar, $nota, $parecer){
    
    if ($nota != '' || $parecer != '') {

        $data = getDataHora();

        $dados = array(
            'nota' => $nota,
            'parecer' => $parecer,
            'data_avaliacao' => $data
        );

        DBUpdate('', 'tb_selecao_avaliador_candidato', $dados, "id_selecao_avaliador_candidato = $id_avaliador_candidato_alterar");
        
        registraLog('Inserção de avaliação candidato.', 'a', '', $id_avaliador_candidato_alterar, "nota: $nota | parecer: $parecer | data_avaliacao: $data");

        $alert = ('Alteração feita com sucesso!','s');
        header("location: /api/iframe?token=$request->token&view=selecao-avaliar-form&idselecao=$id_selecao&idcandidato=$id_candidato");   
    }
}


