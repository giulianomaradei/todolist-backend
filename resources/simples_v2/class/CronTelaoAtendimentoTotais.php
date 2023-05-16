<?php
require_once(__DIR__ . "/System.php");

$data_hoje = explode('-',getDataHora('data'));
// $data_hoje = explode('-', '2021-04-03');

if ($data_hoje[2] <= 5) {
    $data = new DateTime(getDataHora('data'));
    $data->modify('first day of last month');
    $data_de = $data->format('Y-m-d');
    $data->modify('last day of this month');
    $data_ate = $data->format('Y-m-d');

} else { 
    $data = new DateTime(getDataHora('data'));
    $data->modify('first day of this month');
    $data_de = $data->format('Y-m-d');
    //$data->modify('last day of this month');
    //$data_ate = $data->format('Y-m-d');

    $data = new DateTime(getDataHora('data'));
    $data->modify('-1 day');
    $data_ate = $data->format("Y-m-d");
}

$tipo = 'totais_ontem';
totais($tipo, $data_de, $data_ate);

$tipo = 'totais_mes';
totais($tipo, $data_de, $data_ate);

function totais($tipo, $data_de, $data_ate){

    $fila = "'callATENDIMENTOvip','callATENDIMENTOalta','callATENDIMENTOnormal','callATENDIMENTObaixa','callATENDIMENTOnotificacaoparada', 'callATENDIMENTOvipEXT','callATENDIMENTOaltaEXT','callATENDIMENTOnormalEXT','callATENDIMENTObaixaEXT','callATENDIMENTOnotificacaoparadaEXT','callATENDIMENTOvipEXP','callATENDIMENTOaltaEXP','callATENDIMENTOnormalEXP','callATENDIMENTObaixaEXP','callATENDIMENTOnotificacaoparadaEXP'";

    $qtd_atendidas = 0;
    $qtd_atendidas_30 = 0;
    $qtd_atendidas_60 = 0;
    $qtd_atendidas_90 = 0;
    $qtd_atendidas_180 = 0;
    $qtd_atendidas_maior_180 = 0;
    $qtd_perdidas = 0;
    $qtd_perdidas_30 = 0;
    $qtd_perdidas_60 = 0;
    $qtd_perdidas_90 = 0;
    $qtd_perdidas_180 = 0;
    $qtd_perdidas_maior_180 = 0;
    $soma_notas = 0;
    $qtd_notas = 0;
    $soma_ta_atendidas = 0;
    $soma_te_atendidas = 0;
    $soma_te_perdidas = 0;
    
    // $filtro_atendidas = " AND a.queuename IN ('callATENDIMENTOvip','callATENDIMENTOalta','callATENDIMENTOnormal','callATENDIMENTObaixa','callATENDIMENTOnotificacaoparada', 'callATENDIMENTOvipEXT','callATENDIMENTOaltaEXT','callATENDIMENTOnormalEXT','callATENDIMENTObaixaEXT','callATENDIMENTOnotificacaoparadaEXT', 'callATENDIMENTOvipEXP','callATENDIMENTOaltaEXP','callATENDIMENTOnormalEXP','callATENDIMENTObaixaEXP','callATENDIMENTOnotificacaoparadaEXP') AND (c.data2 >= 30 OR c.data4 >= 30)";
    $filtro_atendidas = " AND (c.data2 >= 30 OR c.data4 >= 30)";

    // $filtro_perdidas = " AND a.queuename IN ('callATENDIMENTOvip','callATENDIMENTOalta','callATENDIMENTOnormal','callATENDIMENTObaixa','callATENDIMENTOnotificacaoparada', 'callATENDIMENTOvipEXT','callATENDIMENTOaltaEXT','callATENDIMENTOnormalEXT','callATENDIMENTObaixaEXT','callATENDIMENTOnotificacaoparadaEXT', 'callATENDIMENTOvipEXP','callATENDIMENTOaltaEXP','callATENDIMENTOnormalEXP','callATENDIMENTObaixaEXP','callATENDIMENTOnotificacaoparadaEXP') AND (SELECT SUM(c.data3) FROM queue_log c WHERE c.callid = a.callid AND (c.event = 'ABANDON' OR c.event = 'EXITWITHTIMEOUT')) >= 5";
    $filtro_perdidas = " AND (SELECT SUM(c.data3) FROM queue_log c WHERE c.callid = a.callid AND (c.event = 'ABANDON' OR c.event = 'EXITWITHTIMEOUT')) >= 5";

    if ($tipo == 'totais_hoje') {
        $filtro_atendidas .=  " AND b.time >= '".getDataHora('data')." 00:00:00'";
        $filtro_perdidas .= " AND a.time >= '".getDataHora('data')." 00:00:00'";

    } else if ($tipo == 'totais_ontem') {
        $data_ontem = date('Y-m-d', strtotime("-1 days",strtotime(getDataHora('data'))));
        $filtro_atendidas .=  " AND b.time >= '".$data_ontem." 00:00:00' AND b.time <= '".$data_ontem." 23:59:59'";
        $filtro_perdidas .= " AND a.time >= '".$data_ontem." 00:00:00' AND a.time <= '".$data_ontem." 23:59:59'";

    } else {
        $filtro_atendidas .=  " AND b.time >= '".$data_de." 00:00:00' AND b.time <= '".$data_ate." 23:59:59'";
        $filtro_perdidas .= " AND a.time >= '".$data_de." 00:00:00' AND a.time <= '".$data_ate." 23:59:59'";
    }

    $dados_atendidas = DBRead('snep','queue_log a',"INNER JOIN queue_log b ON (b.callid = a.callid AND b.event = 'CONNECT') INNER JOIN queue_log c ON (c.callid = a.callid AND (c.event = 'COMPLETEAGENT' OR c.event = 'COMPLETECALLER' OR c.event = 'BLINDTRANSFER' OR c.event = 'ATTENDEDTRANSFER')) LEFT JOIN pesquisa d ON a.callid = d.UNIQUEID WHERE a.event = 'ENTERQUEUE' $filtro_atendidas GROUP BY a.callid ORDER BY a.callid", "a.queuename AS 'enterqueue_queuename', a.callid AS 'enterqueue_callid', c.event AS 'finalizacao_event', c.data1 AS 'finalizacao_data1', c.data2 AS 'finalizacao_data2', c.data3 AS 'finalizacao_data3', c.data4 AS 'finalizacao_data4', d.nota AS 'nota', (SELECT SUM(data3) FROM queue_log e WHERE e.callid = a.callid AND e.event = 'EXITWITHTIMEOUT') AS 'tempo_espera_timeout', c.queuename AS ultimafila");

    if($dados_atendidas){
        foreach ($dados_atendidas as $conteudo_atendidas) {
            if(preg_match("/'".$conteudo_atendidas['ultimafila']."'/i", $fila) || !$fila){

                $te_entrada = 'n';                              
                if ($conteudo_atendidas['finalizacao_event']=='COMPLETEAGENT' || $conteudo_atendidas['finalizacao_event']=='COMPLETECALLER'){
                    $soma_ta_atendidas +=  $conteudo_atendidas['finalizacao_data2'];
                    $soma_te_atendidas += $conteudo_atendidas['finalizacao_data1'];
                    $te_entrada = $conteudo_atendidas['finalizacao_data1'];     

                } else if (($conteudo_atendidas['finalizacao_event']=='BLINDTRANSFER' || $conteudo_atendidas['finalizacao_event']=='ATTENDEDTRANSFER') && $conteudo_atendidas['finalizacao_data1'] != 'LINK'){
                    $soma_ta_atendidas +=  $conteudo_atendidas['finalizacao_data4'];
                    $soma_te_atendidas += $conteudo_atendidas['finalizacao_data3'];
                    $te_entrada = $conteudo_atendidas['finalizacao_data3'];
                }
                if($conteudo_atendidas['tempo_espera_timeout'] && $conteudo_atendidas['tempo_espera_timeout'] > 0){
                    $soma_te_atendidas += $conteudo_atendidas['tempo_espera_timeout'];
                    $te_entrada += $conteudo_atendidas['tempo_espera_timeout'];
                }
                if($te_entrada != 'n'){
                    if($te_entrada <= 30){
                        $qtd_atendidas_30++;
                    }else if($te_entrada <= 60){
                        $qtd_atendidas_60++;
                    }else if($te_entrada <= 90){
                        $qtd_atendidas_90++;
                    }else if($te_entrada <= 180){
                        $qtd_atendidas_180++;
                    }else if($te_entrada > 180){
                        $qtd_atendidas_maior_180++;
                    }
                }
                if($conteudo_atendidas['nota']){
                    $soma_notas += $conteudo_atendidas['nota'];
                    $qtd_notas++;   
                }           
                $qtd_atendidas++;
            }
        }
    }

    // echo "INNER JOIN queue_log b ON (b.callid = a.callid AND (b.event = 'ABANDON' OR b.event = 'EXITWITHTIMEOUT')) WHERE a.event = 'ENTERQUEUE' AND a.callid NOT IN (SELECT callid FROM queue_log c WHERE c.callid = a.callid AND c.event = 'CONNECT') $filtro_perdidas GROUP BY a.callid ORDER BY a.callid";
    //$dados_perdidas = DBRead('snep','queue_log a',"INNER JOIN queue_log b ON (b.callid = a.callid AND (b.event = 'ABANDON' OR b.event = 'EXITWITHTIMEOUT')) WHERE a.event = 'ENTERQUEUE' AND a.callid NOT IN (SELECT callid FROM queue_log c WHERE c.callid = a.callid AND c.event = 'CONNECT') $filtro_perdidas ORDER BY a.callid", "a.queuename AS 'enterqueue_queuename', a.callid AS 'enterqueue_callid', a.data2 AS 'enterqueue_data2', (SELECT SUM(f.data3) FROM queue_log f WHERE f.callid = a.callid AND (f.event = 'ABANDON' OR f.event = 'EXITWITHTIMEOUT')) AS 'finalizacao_data3', a.time, a.id");

    $dados_perdidas = DBRead('snep','queue_log a',"INNER JOIN queue_log b ON (b.callid = a.callid AND (b.event = 'ABANDON' OR b.event = 'EXITWITHTIMEOUT')) WHERE a.event = 'ENTERQUEUE' AND a.callid NOT IN (SELECT callid FROM queue_log c WHERE c.callid = a.callid AND c.event = 'CONNECT') $filtro_perdidas GROUP BY a.callid ORDER BY a.callid", "a.queuename AS 'enterqueue_queuename', a.callid AS 'enterqueue_callid', a.data2 AS 'enterqueue_data2', (SELECT SUM(f.data3) FROM queue_log f WHERE f.callid = a.callid AND (f.event = 'ABANDON' OR f.event = 'EXITWITHTIMEOUT')) AS 'finalizacao_data3', b.queuename AS ultimafila, a.time, a.id");
    

    if($dados_perdidas){
        foreach ($dados_perdidas as $conteudo_perdidas) {    
            if(preg_match("/'".$conteudo_perdidas['ultimafila']."'/i", $fila) || !$fila){
                if($conteudo_perdidas['finalizacao_data3'] <= 30){
                    $qtd_perdidas_30++;
                }else if($conteudo_perdidas['finalizacao_data3'] <= 60){
                    $qtd_perdidas_60++;
                }else if($conteudo_perdidas['finalizacao_data3'] <= 90){
                    $qtd_perdidas_90++;
                }else if($conteudo_perdidas['finalizacao_data3'] <= 180){
                    $qtd_perdidas_180++;
                }else if($conteudo_perdidas['finalizacao_data3'] > 180){
                    $qtd_perdidas_maior_180++;
                }
                $qtd_perdidas++;            
                $soma_te_perdidas += $conteudo_perdidas['finalizacao_data3'];
            }
        }
    }

    /* $tempo_medio_espera = gmdate("H:i:s", $soma_te_atendidas/($qtd_atendidas == 0 ? 1 : $qtd_atendidas));
    $tempo_medio_perdidas =  gmdate("H:i:s", $soma_te_perdidas/($qtd_perdidas == 0 ? 1 : $qtd_perdidas));
    $tempo_medio_atendimento = gmdate("H:i:s", $soma_ta_atendidas/($qtd_atendidas == 0 ? 1 : $qtd_atendidas));
    $nota_media = sprintf("%01.2f", round($soma_notas/($qtd_notas == 0 ? 1 : $qtd_notas), 2));
    $status_porcentagem_ligacao_nota = sprintf("%01.2f", round($qtd_notas*100/($qtd_atendidas == 0 ? 1 : $qtd_atendidas), 2)); */

    $tempo_medio_espera = $soma_te_atendidas/($qtd_atendidas == 0 ? 1 : $qtd_atendidas);
    $tempo_medio_perdidas = $soma_te_perdidas/($qtd_perdidas == 0 ? 1 : $qtd_perdidas);
    $tempo_medio_atendimento = $soma_ta_atendidas/($qtd_atendidas == 0 ? 1 : $qtd_atendidas);
    $nota_media = $soma_notas/($qtd_notas == 0 ? 1 : $qtd_notas);
    $status_porcentagem_ligacao_nota = $qtd_notas*100/($qtd_atendidas == 0 ? 1 : $qtd_atendidas);

    $data_atualizacao = getDataHora();

    $dados = array(
        'atendidas' => $qtd_atendidas,
        'perdidas' => $qtd_perdidas,
        'tempo_medio_espera' => $tempo_medio_espera,
        'tempo_medio_perdidas' => $tempo_medio_perdidas,
        'tempo_medio_atendimento' => $tempo_medio_atendimento,
        'nota_media' => $nota_media,
        'status_porcentagem_ligacao_nota' => $status_porcentagem_ligacao_nota,
        'data_de_atualizacao' => $data_atualizacao
    );

     if ($tipo == 'totais_ontem') {

        DBDelete('', 'tb_telao_total_ontem');

        $insertID = DBCreate('', 'tb_telao_total_ontem', $dados, true);
        registraLog('Inserção de telao total ontem.','i','tb_telao_total_ontem', $insertID,"atendidas: $qtd_atendidas | perdidas: $qtd_perdidas | tempo_medio_espera: $tempo_medio_espera| tempo_medio_perdidas: $tempo_medio_perdidas | tempo_medio_atendimento: $tempo_medio_atendimento | nota_media: $nota_media | status_porcentagem_ligacao_nota: $status_porcentagem_ligacao_nota | data_atualizacao: $data_atualizacao");

    } else if ($tipo == 'totais_mes') {

        DBDelete('', 'tb_telao_total_mes');

        $insertID = DBCreate('', 'tb_telao_total_mes', $dados, true);
        registraLog('Inserção de telao total mes.','i','tb_telao_total_mes', $insertID,"atendidas: $qtd_atendidas | perdidas: $qtd_perdidas | tempo_medio_espera: $tempo_medio_espera| tempo_medio_perdidas: $tempo_medio_perdidas | tempo_medio_atendimento: $tempo_medio_atendimento | nota_media: $nota_media | status_porcentagem_ligacao_nota: $status_porcentagem_ligacao_nota | data_atualizacao: $data_atualizacao");
    }
}