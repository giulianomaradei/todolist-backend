<?php
require_once(__DIR__."/System.php");


$id_atendimento = (!empty($_POST['id_atendimento'])) ? $_POST['id_atendimento'] : '';
$quesitos = (!empty($_POST['quesitos'])) ? $_POST['quesitos'] : '';
$pontos = (!empty($_POST['pontos'])) ? $_POST['pontos'] : '';
$id_erro = (!empty($_POST['id_erro'])) ? $_POST['id_erro'] : NULL;
$id_usuario_analista = (!empty($_POST['id_usuario_analista'])) ? $_POST['id_usuario_analista'] : '';
$id_usuario_atendente = (!empty($_POST['id_usuario_atendente'])) ? $_POST['id_usuario_atendente'] : '';
$obs_avaliacao = (!empty($_POST['obs_avaliacao'])) ? $_POST['obs_avaliacao'] : '';
$data_referencia = (!empty($_POST['data_referencia'])) ? $_POST['data_referencia'] : '';
$data_monitoria = getDataHora();
$duracao_avaliacao = (!empty($_POST['duracao_avaliacao'])) ? $_POST['duracao_avaliacao'] : '';
$id_monitoria_mes = (!empty($_POST['id_monitoria_mes'])) ? $_POST['id_monitoria_mes'] : '';
$atendimento_encantador = (!empty($_POST['atendimento_encantador'])) ? $_POST['atendimento_encantador'] : 0;
$cliente_irritado = (!empty($_POST['cliente_irritado'])) ? $_POST['cliente_irritado'] : 0;

if(!empty($_POST['inserir'])) {
    
    inserir($id_atendimento, $id_usuario_analista, $id_usuario_atendente, $data_referencia, $data_monitoria, $id_erro, $obs_avaliacao, $quesitos, $pontos, $duracao_avaliacao, $id_monitoria_mes, $atendimento_encantador, $cliente_irritado);
}

function inserir($id_atendimento, $id_usuario_analista, $id_usuario_atendente, $data_referencia, $data_monitoria, $id_erro, $obs_avaliacao, $quesitos, $pontos, $duracao_avaliacao, $id_monitoria_mes, $atendimento_encantador, $cliente_irritado){
    
    if($id_atendimento != '' && $id_usuario_analista != '' && $id_usuario_atendente != '' && $data_referencia != '' && $data_monitoria != '' && $quesitos !='' && $pontos !='' && $duracao_avaliacao != '' && $id_monitoria_mes != ''){
        $link = DBConnect('');
        DBBegin($link);

        $n = sizeof($quesitos);

        $total_pontos = 0;
        for($i=0; $i<$n; $i++){
            $total_pontos += $pontos[$i];
        }

        $dados = array(
            'id_atendimento' => $id_atendimento,
            'id_usuario_analista' => $id_usuario_analista,
            'id_usuario_atendente' => $id_usuario_atendente,
            'data_referencia' => $data_referencia,
            'data_monitoria' => $data_monitoria,
            'id_erro' => $id_erro,
            'obs_avaliacao' => $obs_avaliacao,
            'total_pontos' => $total_pontos, 
            'duracao_avaliacao' => $duracao_avaliacao,
            'id_monitoria_mes' => $id_monitoria_mes,
            'elogio' => $atendimento_encantador, 
            'irritado' => $cliente_irritado
        );

        $insertID = DBCreateTransaction($link, 'tb_monitoria_avaliacao_texto', $dados, true);
        registraLogTransaction($link, 'Inserção de avaliação da monitoria texto.','i','tb_monitoria_avaliacao_texto',$insertID,"id_atendimento: $id_atendimento | id_usuario_analista: $id_usuario_analista | id_usuario_atendente: $id_usuario_atendente | data_referencia: $data_referencia | data_monitoria: $data_monitoria | id_erro: $id_erro | obs_avaliacao: $obs_avaliacao | total_pontos: $total_pontos | duracao_avaliacao: $duracao_avaliacao | id_monitoria_mes: $id_monitoria_mes | elogio: $atendimento_encantador | irritado: $cliente_irritado");

        $n = sizeof($quesitos);

        for($i=0; $i<$n; $i++){
            $id_quesito_mes = $quesitos[$i];
            $pontuacao = $pontos[$i];

            $dados_quesitos = array(
                'id_monitoria_avaliacao_texto' => $insertID,
                'id_monitoria_mes_quesito' => $id_quesito_mes,
                'pontos' => $pontuacao
            );

            $insertID2 = DBCreateTransaction($link, 'tb_monitoria_avaliacao_texto_mes', $dados_quesitos, true);
            registraLogTransaction($link, 'Inserção de avaliação de texto mês.','i','tb_monitoria_avaliacao_texto_mes',$insertID2,"id_monitoria_avaliacao_texto: $insertID | id_monitoria_mes_quesito: $id_quesito_mes | pontos:  $pontuacao");
        }

        calcula_resultado($link, $id_usuario_atendente, $data_referencia, $id_monitoria_mes);

        DBCommit($link);

        $alert = ('Avaliação da monitoria via texto inserida com sucesso!','s');
	    header("location: /api/iframe?token=$request->token&view=monitoria-avaliacao-busca&id_monitoria_mes=$id_monitoria_mes");
        exit; 

    }else{
        $alert = ('Não foi possível salvar a avaliação!','w');
	    header("location: /api/iframe?token=$request->token&view=monitoria-avaliacao-texto-form");
   	    exit;
    }
}

function calcula_resultado($link, $id_usuario_atendente, $data_referencia, $id_monitoria_mes){

    $dados_aval_texto = DBReadTransaction($link, 'tb_monitoria_avaliacao_texto', "WHERE id_monitoria_mes = $id_monitoria_mes AND id_usuario_atendente = '$id_usuario_atendente' AND considerar = 1", 'SUM(total_pontos) AS total_pontos, COUNT(*) AS cont_textos');

    $dados_monitoria_mes = DBRead($link, 'tb_monitoria_mes', "WHERE id_monitoria_mes = $id_monitoria_mes", 'soma_total_pontos_quesitos');

    $total_pontos = $dados_aval_texto[0]['total_pontos'];
    $cont_textos = $dados_aval_texto[0]['cont_textos'];
    $soma_total_pontos_quesitos = $dados_monitoria_mes[0]['soma_total_pontos_quesitos'];

    $media = $total_pontos / $cont_textos;

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