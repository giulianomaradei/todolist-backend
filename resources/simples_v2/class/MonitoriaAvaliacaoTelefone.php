<?php
require_once(__DIR__."/System.php");


$nome_contato = (!empty($_POST['nome_contato'])) ? $_POST['nome_contato'] : '';
$id_subarea_problema = (!empty($_POST['id_subarea_problema'])) ? $_POST['id_subarea_problema'] : '';
$quesitos = (!empty($_POST['quesitos'])) ? $_POST['quesitos'] : '';
$pontos = (!empty($_POST['pontos'])) ? $_POST['pontos'] : '';
$id_ligacao = (!empty($_POST['id_ligacao'])) ? $_POST['id_ligacao'] : '';
$id_erro = (!empty($_POST['id_erro'])) ? $_POST['id_erro'] : NULL;
$nota = (!empty($_POST['nota'])) ? $_POST['nota'] : NULL;
$data_audio = (!empty($_POST['data_audio'])) ? $_POST['data_audio'] : '';
$id_usuario_analista = (!empty($_POST['id_usuario_analista'])) ? $_POST['id_usuario_analista'] : '';
$id_usuario_atendente = (!empty($_POST['id_usuario_atendente'])) ? $_POST['id_usuario_atendente'] : '';
$duracao_audio = (!empty($_POST['tempo_atendimento'])) ? $_POST['tempo_atendimento'] : '';
$obs_avaliacao = (!empty($_POST['obs_avaliacao'])) ? $_POST['obs_avaliacao'] : '';
$data_referencia = (!empty($_POST['data_referencia'])) ? $_POST['data_referencia'] : '';
$data_monitoria = getDataHora();
$id_contrato_plano_pessoa = (!empty($_POST['id_contrato_plano_pessoa'])) ? $_POST['id_contrato_plano_pessoa'] : '';
$duracao_avaliacao = (!empty($_POST['duracao_avaliacao'])) ? $_POST['duracao_avaliacao'] : '';
$id_monitoria_mes = (!empty($_POST['id_monitoria_mes'])) ? $_POST['id_monitoria_mes'] : '';
$atendimento_encantador = (!empty($_POST['atendimento_encantador'])) ? $_POST['atendimento_encantador'] : 0;
$cliente_irritado = (!empty($_POST['cliente_irritado'])) ? $_POST['cliente_irritado'] : 0;

if(!empty($_POST['inserir'])) {
    
    inserir($id_ligacao, $id_usuario_analista, $id_usuario_atendente, $data_referencia, $data_monitoria, $data_audio, $nome_contato, $id_subarea_problema, $id_contrato_plano_pessoa, $id_erro, $duracao_audio, $nota, $obs_avaliacao, $quesitos, $pontos, $duracao_avaliacao, $id_monitoria_mes, $atendimento_encantador, $cliente_irritado);
}

function inserir($id_ligacao, $id_usuario_analista, $id_usuario_atendente, $data_referencia, $data_monitoria, $data_audio, $nome_contato, $id_subarea_problema, $id_contrato_plano_pessoa, $id_erro, $duracao_audio, $nota, $obs_avaliacao, $quesitos, $pontos, $duracao_avaliacao, $id_monitoria_mes, $atendimento_encantador, $cliente_irritado){
    
    if($id_ligacao != '' && $id_usuario_analista != '' && $id_usuario_atendente != '' && $data_referencia != '' && $data_monitoria != '' && $data_audio != '' && $nome_contato != '' && $id_subarea_problema != '' && $id_contrato_plano_pessoa != '' && $duracao_audio != '' && $quesitos !='' && $pontos !='' && $duracao_avaliacao != '' && $id_monitoria_mes != ''){

        $data_audio = converteDataHora($data_audio);

        $link = DBConnect('');
        DBBegin($link);

        $n = sizeof($quesitos);

        $total_pontos = 0;
        for($i=0; $i<$n; $i++){
            $total_pontos += $pontos[$i];
        }

        $dados = array(
            'id_ligacao' => $id_ligacao,
            'id_usuario_analista' => $id_usuario_analista,
            'id_usuario_atendente' => $id_usuario_atendente,
            'data_referencia' => $data_referencia,
            'data_monitoria' => $data_monitoria,
            'data_audio' => $data_audio,
            'nome_contato' => $nome_contato,
            'id_subarea_problema' => $id_subarea_problema,
            'id_contrato_plano_pessoa' => $id_contrato_plano_pessoa,
            'id_erro' => $id_erro,
            'duracao_audio' => $duracao_audio,
            'nota' => $nota,
            'obs_avaliacao' => $obs_avaliacao,
            'total_pontos' => $total_pontos, 
            'duracao_avaliacao' => $duracao_avaliacao,
            'id_monitoria_mes' => $id_monitoria_mes,
            'elogio' => $atendimento_encantador, 
            'irritado' => $cliente_irritado
        );

        $insertID = DBCreateTransaction($link, 'tb_monitoria_avaliacao_audio', $dados, true);
        registraLogTransaction($link, 'Inserção de avaliação da monitoria audio.','i','tb_monitoria_avaliacao_audio',$insertID,"id_ligacao: $id_ligacao | id_usuario_analista: $id_usuario_analista | id_usuario_atendente: $id_usuario_atendente | data_referencia: $data_referencia | data_monitoria: $data_monitoria | data_audio: $data_audio | nome_contato: $nome_contato | id_subarea_problema: $id_subarea_problema | id_contrato_plano_pessoa: $id_contrato_plano_pessoa | id_erro: $id_erro  | duracao_audio: $duracao_audio  | nota: $nota | obs_avaliacao: $obs_avaliacao | total_pontos: $total_pontos | duracao_avaliacao: $duracao_avaliacao | id_monitoria_mes: $id_monitoria_mes | elogio: $atendimento_encantador | irritado: $cliente_irritado");

        $n = sizeof($quesitos);

        for($i=0; $i<$n; $i++){
            //echo 'id_quesito: '.$quesitos[$i].' -> pontos: '.$pontos[$i].'<br>';
            $id_quesito_mes = $quesitos[$i];
            $pontuacao = $pontos[$i];

            $dados_quesitos = array(
                'id_monitoria_avaliacao_audio' => $insertID,
                'id_monitoria_mes_quesito' => $id_quesito_mes,
                'pontos' => $pontuacao
            );

            $insertID2 = DBCreateTransaction($link, 'tb_monitoria_avaliacao_audio_mes', $dados_quesitos, true);
            registraLogTransaction($link, 'Inserção de avaliação de audio mês.','i','tb_monitoria_avaliacao_audio_mes',$insertID2,"id_monitoria_avaliacao_audio: $insertID | id_monitoria_mes_quesito: $id_quesito_mes | pontos:  $pontuacao");
        }

        calcula_resultado($link, $id_usuario_atendente, $data_referencia, $id_monitoria_mes);

        DBCommit($link);

        $alert = ('Avaliação da monitoria via telefone inserida com sucesso!','s');
	    header("location: /api/iframe?token=$request->token&view=monitoria-avaliacao-busca&id_monitoria_mes=$id_monitoria_mes");
        exit; 

    }else{
        $alert = ('Não foi possível salvar a avaliação!','w');
	    header("location: /api/iframe?token=$request->token&view=monitoria-avaliacao-busca&id_monitoria_mes=$id_monitoria_mes");
   	    exit;
    }
}

function calcula_resultado($link, $id_usuario_atendente, $data_referencia, $id_monitoria_mes){

    $dados_aval_audios = DBReadTransaction($link, 'tb_monitoria_avaliacao_audio', "WHERE id_monitoria_mes = $id_monitoria_mes AND id_usuario_atendente = '$id_usuario_atendente' AND considerar = 1", 'SUM(total_pontos) AS total_pontos, COUNT(*) AS cont_audios');

    $dados_monitoria_mes = DBRead($link, 'tb_monitoria_mes', "WHERE id_monitoria_mes = $id_monitoria_mes", 'soma_total_pontos_quesitos');

    $total_pontos = $dados_aval_audios[0]['total_pontos'];
    $cont_audios = $dados_aval_audios[0]['cont_audios'];
    $soma_total_pontos_quesitos = $dados_monitoria_mes[0]['soma_total_pontos_quesitos'];

    $media = $total_pontos / $cont_audios;

    $resultado = ($media*100) / $soma_total_pontos_quesitos;
    $resultado = number_format($resultado, 2, '.', ' ');

    $verifica = DBReadTransaction($link, 'tb_monitoria_resultado', "WHERE id_monitoria_mes = $id_monitoria_mes AND id_usuario = '$id_usuario_atendente' ");

    if($verifica){

        $id_monitoria_resultado = $verifica[0]['id_monitoria_resultado'];

        $dados = array(
            'resultado' => $resultado,
        );

        DBUpdateTransaction($link,'tb_monitoria_resultado', $dados, "id_monitoria_resultado = $id_monitoria_resultado");
        registraLogTransaction($link, 'Alteração no resultado monitoria.', 'a', 'tb_monitoria_resultado', $id_monitoria_resultado, "resultado: $resultado");
    }else{

        $dados = array(
            'data_referencia' => $data_referencia,
            'resultado' => $resultado,
            'id_usuario' => $id_usuario_atendente,
            'id_monitoria_mes' => $id_monitoria_mes
        );

        $insertID = DBCreateTransaction($link, 'tb_monitoria_resultado', $dados, true);
        registraLogTransaction($link, 'Inserção de monitoria resultado.','i','tb_monitoria_resultado',$insertID,"data_referencia: $data_referencia | resultado: $resultado | id_usuario:  $id_usuario_atendente | id_monitoria_mes: $id_monitoria_mes");
    }
}

?>