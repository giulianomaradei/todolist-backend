<?php
require_once "System.php";

$mes = (isset($_POST['mes'])) ? $_POST['mes'] : '';
$atendente = (isset($_POST['atendente'])) ? $_POST['atendente'] : '';
$canal_atendimento = (isset($_POST['canal_atendimento'])) ? $_POST['canal_atendimento'] : '';

if ($mes == 'atual') {

    $dataprimeirodia = new DateTime(getDataHora());
    $dataprimeirodia->modify('first day of this month');
    $mes_atual = $dataprimeirodia->format("m");

    $data_referencia = $dataprimeirodia->format("Y-m-d");

    if ($canal_atendimento == 1) {
        $datas_audios = DBRead('', 'tb_monitoria_avaliacao_audio' , "WHERE data_referencia = '$data_referencia' AND id_usuario_atendente = '$atendente' AND considerar = 1 GROUP BY data_audio ORDER BY data_audio ASC", 'data_audio');

        $array_datas = array();
        if($datas_audios){
            foreach ($datas_audios as $conteudo) {
                $mes_audio = new DateTime($conteudo['data_audio']);
                $mes_audio = $mes_audio->format("m");

                if ($mes_atual == $mes_audio) {
                    $data_audio_avaliado = converteDataHora($conteudo['data_audio']);
                    $dia_avaliado = substr($data_audio_avaliado,0,2);
                    array_push($array_datas, $dia_avaliado);
                }
            }
        }

        echo json_encode($array_datas);

    } else if ($canal_atendimento == 2) {
        $datas_atendimentos = DBRead('', 'tb_monitoria_avaliacao_texto a' , "INNER JOIN tb_atendimento b ON a.id_Atendimento = b.id_atendimento WHERE data_referencia = '$data_referencia' AND id_usuario_atendente = '$atendente' AND considerar = 1 GROUP BY b.data_inicio ORDER BY b.data_inicio ASC", 'b.data_inicio');

        $array_datas = array();
        if($datas_atendimentos){
            foreach ($datas_atendimentos as $conteudo) {
                $mes_texto = new DateTime($conteudo['data_inicio']);
                $mes_texto = $mes_texto->format("m");

                if ($mes_atual == $mes_texto) { 
                    $data_atendimento_avaliado = converteDataHora($conteudo['data_inicio']);
                    $dia_avaliado = substr($data_atendimento_avaliado,0,2);
                    array_push($array_datas, $dia_avaliado);
                }
            }
        }

        echo json_encode($array_datas);
    }

} else if ($mes == 'anterior') {

    $dataprimeirodia = new DateTime(getDataHora());
    $dataprimeirodia->modify('first day of last month');
    $mes_anterior = $dataprimeirodia->format("m");

    $data_referencia = $dataprimeirodia->format("Y-m-d");

    if ($canal_atendimento == 1) {
        $datas_audios = DBRead('', 'tb_monitoria_avaliacao_audio' , "WHERE data_referencia = '$data_referencia' AND id_usuario_atendente = '$atendente' AND considerar = 1 GROUP BY data_audio ORDER BY data_audio ASC", 'data_audio');

        $array_datas = array();
        if($datas_audios){
            foreach ($datas_audios as $conteudo) {
                $mes_audio = new DateTime($conteudo['data_audio']);
                $mes_audio = $mes_audio->format("m");
    
                if ($mes_anterior == $mes_audio) {
                    $data_audio_avaliado = converteDataHora($conteudo['data_audio']);
                    $dia_avaliado = substr($data_audio_avaliado,0,2);
                    array_push($array_datas, $dia_avaliado);
                }
            }
        }

        echo json_encode($array_datas);

    } else if ($canal_atendimento == 2) {
        $datas_atendimentos = DBRead('', 'tb_monitoria_avaliacao_texto a' , "INNER JOIN tb_atendimento b ON a.id_Atendimento = b.id_atendimento WHERE data_referencia = '$data_referencia' AND id_usuario_atendente = '$atendente' AND considerar = 1 GROUP BY b.data_inicio ORDER BY b.data_inicio ASC", 'b.data_inicio');

        $array_datas = array();
        if($datas_atendimentos){
            foreach ($datas_atendimentos as $conteudo) {
                $mes_texto = new DateTime($conteudo['data_inicio']);
                $mes_texto = $mes_texto->format("m");

                if ($mes_anterior == $mes_texto) { 
                    $data_atendimento_avaliado = converteDataHora($conteudo['data_inicio']);
                    $dia_avaliado = substr($data_atendimento_avaliado,0,2);
                    array_push($array_datas, $dia_avaliado);
                }
            }
        }

        echo json_encode($array_datas);
    }
}
