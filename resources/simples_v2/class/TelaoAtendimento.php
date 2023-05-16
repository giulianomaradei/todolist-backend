<?php
require_once(__DIR__ . "/System.php");
$tipo  = (!empty($_POST['tipo'])) ? $_POST['tipo'] : '';
$data_hoje = explode('-',getDataHora('data'));
// $data_hoje = explode('-', '2021-04-03');

if($data_hoje[2] <= 5){
    $data = new DateTime(getDataHora('data'));
    $data->modify('first day of last month');
    $data_de = $data->format('Y-m-d');
    $data->modify('last day of this month');
    $data_ate = $data->format('Y-m-d');

}else{
    $data = new DateTime(getDataHora('data'));
    $data->modify('first day of this month');
    $data_de = $data->format('Y-m-d');
    $data->modify('last day of this month');
    $data_ate = $data->format('Y-m-d');
}

$meses = array(
    "01" => "JANEIRO",
    "02" => "FEVEREIRO",
    "03" => "MARÇO",
    "04" => "ABRIL",
    "05" => "MAIO",
    "06" => "JUNHO",
    "07" => "JULHO",
    "08" => "AGOSTO",
    "09" => "SETEMBRO",
    "10" => "OUTUBRO",
    "11" => "NOVEMBRO",
    "12" => "DEZEMBRO",
);
$data_mes = explode('-',$data_de);
$mes = $meses[$data_mes[1]];

if($tipo == 'totais_hoje' || $tipo == 'totais_ontem' || $tipo == 'totais_mes'){
    totais($tipo, $mes, $data_hoje);

}elseif($tipo == 'metas'){
    metas($data_ate, $mes);

}elseif($tipo == 'pesquisas'){
    pesquisas();

}elseif($tipo == 'atendimentos_pendentes'){
    atendimentos_pendentes();

}elseif($tipo == 'solicitacoes_ajuda'){
    solicitacoes_ajuda();

}elseif($tipo == 'solicitacoes_ajuda_audio'){
    solicitacoes_ajuda_audio();

}elseif($tipo == 'resultado_metas'){
    resultado_metas($mes);

}elseif($tipo == 'resultado_metas_mes'){
    resultado_metas_mes($mes);

}elseif($tipo == 'alerta_painel'){
    alerta_painel();

}elseif($tipo == 'alerta_painel_audio'){
    alerta_painel_audio();

}elseif($tipo == 'contador_ajuda_alerta'){
    contador_ajuda_alerta();

}

function totais($tipo, $mes){

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
        $titulo_panel = '<span style="font-weight: 300;">Totais de </span>HOJE';
        $filtro_atendidas .=  " AND b.time >= '".getDataHora('data')." 00:00:00'";
        $filtro_perdidas .= " AND a.time >= '".getDataHora('data')." 00:00:00'";

        //Foi removido GROUP BY b.id
        //$dados_atendidas = DBRead('snep','queue_log a',"INNER JOIN queue_log b ON (b.callid = a.callid AND b.event = 'CONNECT') INNER JOIN queue_log c ON (c.callid = a.callid AND (c.event = 'COMPLETEAGENT' OR c.event = 'COMPLETECALLER' OR c.event = 'BLINDTRANSFER' OR c.event = 'ATTENDEDTRANSFER')) LEFT JOIN pesquisa d ON a.callid = d.UNIQUEID WHERE a.event = 'ENTERQUEUE' $filtro_atendidas ORDER BY a.callid", "a.queuename AS 'enterqueue_queuename', a.callid AS 'enterqueue_callid', c.event AS 'finalizacao_event', c.data1 AS 'finalizacao_data1', c.data2 AS 'finalizacao_data2', c.data3 AS 'finalizacao_data3', c.data4 AS 'finalizacao_data4', d.nota AS 'nota', (SELECT SUM(data3) FROM queue_log e WHERE e.callid = a.callid AND e.event = 'EXITWITHTIMEOUT') AS 'tempo_espera_timeout'");

        $dados_atendidas = DBRead('snep','queue_log a',"INNER JOIN queue_log b ON (b.callid = a.callid AND b.event = 'CONNECT') INNER JOIN queue_log c ON (c.callid = a.callid AND (c.event = 'COMPLETEAGENT' OR c.event = 'COMPLETECALLER' OR c.event = 'BLINDTRANSFER' OR c.event = 'ATTENDEDTRANSFER')) LEFT JOIN pesquisa d ON a.callid = d.UNIQUEID WHERE a.event = 'ENTERQUEUE' $filtro_atendidas GROUP BY a.callid ORDER BY a.callid", "a.queuename AS 'enterqueue_queuename', a.callid AS 'enterqueue_callid', c.event AS 'finalizacao_event', c.data1 AS 'finalizacao_data1', c.data2 AS 'finalizacao_data2', c.data3 AS 'finalizacao_data3', c.data4 AS 'finalizacao_data4', c.queuename AS ultimafila, d.nota AS 'nota', (SELECT SUM(data3) FROM queue_log e WHERE e.callid = a.callid AND e.event = 'EXITWITHTIMEOUT') AS 'tempo_espera_timeout'");

        if ($dados_atendidas) {
            foreach ($dados_atendidas as $conteudo_atendidas) {
                if(preg_match("/'".$conteudo_atendidas['ultimafila']."'/i", $fila) || !$fila){

                    $te_entrada = 'n';                              
                    if($conteudo_atendidas['finalizacao_event']=='COMPLETEAGENT' || $conteudo_atendidas['finalizacao_event']=='COMPLETECALLER'){
                        $soma_ta_atendidas +=  $conteudo_atendidas['finalizacao_data2'];
                        $soma_te_atendidas += $conteudo_atendidas['finalizacao_data1'];
                        $te_entrada = $conteudo_atendidas['finalizacao_data1'];             
                    }elseif(($conteudo_atendidas['finalizacao_event']=='BLINDTRANSFER' || $conteudo_atendidas['finalizacao_event']=='ATTENDEDTRANSFER') && $conteudo_atendidas['finalizacao_data1'] != 'LINK'){
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
        //Foi removido GROUP BY a.callid
        //$dados_perdidas = DBRead('snep','queue_log a',"INNER JOIN queue_log b ON (b.callid = a.callid AND (b.event = 'ABANDON' OR b.event = 'EXITWITHTIMEOUT')) WHERE a.event = 'ENTERQUEUE' AND a.callid NOT IN (SELECT callid FROM queue_log c WHERE c.callid = a.callid AND c.event = 'CONNECT') $filtro_perdidas ORDER BY a.callid", "a.queuename AS 'enterqueue_queuename', a.callid AS 'enterqueue_callid', a.data2 AS 'enterqueue_data2', (SELECT SUM(f.data3) FROM queue_log f WHERE f.callid = a.callid AND (f.event = 'ABANDON' OR f.event = 'EXITWITHTIMEOUT')) AS 'finalizacao_data3', a.time, a.id");

        $dados_perdidas = DBRead('snep','queue_log a',"INNER JOIN queue_log b ON (b.callid = a.callid AND (b.event = 'ABANDON' OR b.event = 'EXITWITHTIMEOUT')) WHERE a.event = 'ENTERQUEUE' AND a.callid NOT IN (SELECT callid FROM queue_log c WHERE c.callid = a.callid AND c.event = 'CONNECT') $filtro_perdidas GROUP BY a.callid ORDER BY a.callid", "a.queuename AS 'enterqueue_queuename', a.callid AS 'enterqueue_callid', a.data2 AS 'enterqueue_data2', (SELECT SUM(f.data3) FROM queue_log f WHERE f.callid = a.callid AND (f.event = 'ABANDON' OR f.event = 'EXITWITHTIMEOUT')) AS 'finalizacao_data3', b.queuename AS ultimafila, a.time, a.id");

        if ($dados_perdidas) {
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

        echo' 
        <table class="table table-bordered" style="margin-bottom: 25x;"> 
            <thead>
                <tr style="font-size: 20px;">
                    <th class="text-center" colspan="7">'.$titulo_panel.'</th>
                </tr> 
                <tr style="font-size: 16px;"> 
                    <th class="text-center" style="font-weight: 300;">Ligações Atendidas</th>
                    <th class="text-center" style="font-weight: 300;">Ligações Perdidas</th>                
                    <th class="text-center" style="font-weight: 300;" title="Tempo Médio de Espera">TME</th>
                    <th class="text-center" style="font-weight: 300;" title="Tempo Médio das Perdidas">TMP</th>
                    <th class="text-center" style="font-weight: 300;" title="Tempo Médio de Atendimento">TMA</th>
                    <th class="text-center" style="font-weight: 300;" title="Nota Média de Atendimento">NMA</th>
                    <th class="text-center" style="font-weight: 300;" title="Ligações com Nota">LN</th>
                </tr>
            </thead> 
            <tbody style="font-size: 34px;">
                <tr>
                    <td class="text-center success">'.$qtd_atendidas.'</td>
                    <td class="text-center danger">'.$qtd_perdidas.'</td>
                    <td class="text-center warning">'.gmdate("H:i:s", $soma_te_atendidas/($qtd_atendidas == 0 ? 1 : $qtd_atendidas)).'</td>
                    <td class="text-center warning">'.gmdate("H:i:s", $soma_te_perdidas/($qtd_perdidas == 0 ? 1 : $qtd_perdidas)).'</td>
                    <td class="text-center info">'.gmdate("H:i:s", $soma_ta_atendidas/($qtd_atendidas == 0 ? 1 : $qtd_atendidas)).'</td>
                    <td class="text-center info">'.sprintf("%01.2f", round($soma_notas/($qtd_notas == 0 ? 1 : $qtd_notas), 2)).'</td>
                    <td class="text-center info">'.sprintf("%01.2f", round($qtd_notas*100/($qtd_atendidas == 0 ? 1 : $qtd_atendidas), 2)).'%</td>
                </tr>
            </tbody> 
        </table>
        ';

    } else if ($tipo == 'totais_ontem') {
        $titulo_panel = '<span style="font-weight: 300;">Totais de </span>ONTEM';

        $totais_ontem = DBRead('', 'tb_telao_total_ontem');

        $qtd_atendidas = $totais_ontem[0]['atendidas'];
        $qtd_perdidas = $totais_ontem[0]['perdidas'];
        $tempo_medio_espera = $totais_ontem[0]['tempo_medio_espera'];
        $tempo_medio_perdida = $totais_ontem[0]['tempo_medio_perdidas'];
        $tempo_medio_atendimento = $totais_ontem[0]['tempo_medio_atendimento'];
        $nota_media = $totais_ontem[0]['nota_media'];
        $status_porcentagem_ligacao_nota = $totais_ontem[0]['status_porcentagem_ligacao_nota'];

        echo' 
        <table class="table table-bordered" style="margin-bottom: 10px;"> 
            <thead>
                <tr style="font-size: 20px;">
                    <th class="text-center" colspan="7">'.$titulo_panel.'</th>
                </tr> 
                <tr style="font-size: 16px;"> 
                    <th class="text-center" style="font-weight: 300;">Ligações Atendidas</th>
                    <th class="text-center" style="font-weight: 300;">Ligações Perdidas</th>                
                    <th class="text-center" style="font-weight: 300;" title="Tempo Médio de Espera">TME</th>
                    <th class="text-center" style="font-weight: 300;" title="Tempo Médio das Perdidas">TMP</th>
                    <th class="text-center" style="font-weight: 300;" title="Tempo Médio de Atendimento">TMA</th>
                    <th class="text-center" style="font-weight: 300;" title="Nota Média de Atendimento">NMA</th>
                    <th class="text-center" style="font-weight: 300;" title="Ligações com Nota">LN</th>
                </tr>
            </thead> 
            <tbody style="font-size: 34px;">
                <tr>
                    <td class="text-center success">'.$qtd_atendidas.'</td>
                    <td class="text-center danger">'.$qtd_perdidas.'</td>
                    <td class="text-center warning">'.gmdate("H:i:s", $tempo_medio_espera).'</td>
                    <td class="text-center warning">'.gmdate("H:i:s", $tempo_medio_perdida).'</td>
                    <td class="text-center info">'.gmdate("H:i:s", $tempo_medio_atendimento).'</td>
                    <td class="text-center info">'.sprintf("%01.2f", $nota_media).'</td>
                    <td class="text-center info">'.sprintf("%01.2f", $status_porcentagem_ligacao_nota).'%</td>
                </tr>
            </tbody> 
        </table>
        ';
        
    } else {        
        $titulo_panel = '<span style="font-weight: 300;">Totais de </span>'.$mes.' (até ontem)';

        $totais_mes = DBRead('', 'tb_telao_total_mes');

        $qtd_atendidas = $totais_mes[0]['atendidas'];
        $qtd_perdidas = $totais_mes[0]['perdidas'];
        $tempo_medio_espera = $totais_mes[0]['tempo_medio_espera'];
        $tempo_medio_perdida = $totais_mes[0]['tempo_medio_perdidas'];
        $tempo_medio_atendimento = $totais_mes[0]['tempo_medio_atendimento'];
        $nota_media = $totais_mes[0]['nota_media'];
        $status_porcentagem_ligacao_nota = $totais_mes[0]['status_porcentagem_ligacao_nota'];

        echo' 
        <table class="table table-bordered" style="margin-bottom: 10px;"> 
            <thead>
                <tr style="font-size: 20px;">
                    <th class="text-center" colspan="7">'.$titulo_panel.'</th>
                </tr> 
                <tr style="font-size: 16px;"> 
                    <th class="text-center" style="font-weight: 300;">Ligações Atendidas</th>
                    <th class="text-center" style="font-weight: 300;">Ligações Perdidas</th>                
                    <th class="text-center" style="font-weight: 300;" title="Tempo Médio de Espera">TME</th>
                    <th class="text-center" style="font-weight: 300;" title="Tempo Médio das Perdidas">TMP</th>
                    <th class="text-center" style="font-weight: 300;" title="Tempo Médio de Atendimento">TMA</th>
                    <th class="text-center" style="font-weight: 300;" title="Nota Média de Atendimento">NMA</th>
                    <th class="text-center" style="font-weight: 300;" title="Ligações com Nota">LN</th>
                </tr>
            </thead> 
            <tbody style="font-size: 34px;">
                <tr>
                    <td class="text-center success">'.$qtd_atendidas.'</td>
                    <td class="text-center danger">'.$qtd_perdidas.'</td>
                    <td class="text-center warning">'.gmdate("H:i:s", $tempo_medio_espera).'</td>
                    <td class="text-center warning">'.gmdate("H:i:s", $tempo_medio_perdida).'</td>
                    <td class="text-center info">'.gmdate("H:i:s", $tempo_medio_atendimento).'</td>
                    <td class="text-center info">'.sprintf("%01.2f", $nota_media).'</td>
                    <td class="text-center info">'.sprintf("%01.2f", $status_porcentagem_ligacao_nota).'%</td>
                </tr>
            </tbody> 
        </table>
        ';
    }
}

function metas($data_ate, $mes){
    $metas = DBRead('', 'tb_meta', "WHERE tipo = '1' AND status = '1' AND data_ate >= '".$data_ate."' AND data_de <= '".$data_ate."' ORDER BY nota_media DESC, porcentagem_ligacao_nota DESC");
    
    echo '
        <table class="table table-bordered"> 
            <thead> 
                <tr style="font-size: 20px;">
                    <th class="text-center" colspan="11"><span style="font-weight: 300;">Metas individuais de </span>'.$mes.'</th>
                </tr>
                <tr style="font-size: 16px;"> 
                    <th class="text-center" style="font-weight: 300;">Meta</th>
                    <th class="text-center" style="font-weight: 300;">TMA</th>
                    <th class="text-center" style="font-weight: 300;">NMA</th>
                    <th class="text-center" style="font-weight: 300;">LN</th>
                    <th class="text-center" style="font-weight: 300;">Reclamações/Erros</th>
                    <th class="text-center" style="font-weight: 300;">Faltas justificadas</th>
                    <th class="text-center" style="font-weight: 300;">Absenteísmo</th>
                    <th class="text-center" style="font-weight: 300;">Pausa registro</th>
                    <th class="text-center" style="font-weight: 300;">Monitoria</th>
                    <th class="text-center" style="font-weight: 300;">Resolução</th>
                    <th class="text-center" style="font-weight: 300;">Atendimentos por Hora</th>
                    <th class="text-center" style="font-weight: 300;">Total de Atendimentos Integral/Meio turno</th>
                    <th class="text-center" style="font-weight: 300;">Qtd. de Ajudas Integral/Meio turno</th>
                </tr>
            </thead> 
            <tbody style="font-size: 25px;">
    ';
    if($metas){
		foreach ($metas as $meta) {
			if($meta['nome'] ==  'Bronze'){
				$img = '<img src="inc/img/meta_bronze.png" height="52" width="40">';
			}else if($meta['nome'] ==  'Silver'){
				$img = '<img src="inc/img/meta_silver.png" height="52" width="40">';
			}else if($meta['nome'] ==  'Gold'){
				$img = '<img src="inc/img/meta_gold.png" height="52" width="40">';
			}else if($meta['nome'] ==  'Diamond'){
				$img = '<img src="inc/img/meta_diamond.png" height="52" width="40">';
			}	
			echo "<tr>";
			echo "<td class='text-center' style='vertical-align: middle;'>$img</td>";
			if($meta['status_tempo_medio_atendimento'] == 1){
				echo "<td class='text-center' style='vertical-align: middle;'>".gmdate("H:i:s", $meta['tempo_medio_atendimento'])."</td>";
			}else{
				echo '<td class="text-center" style="vertical-align: middle;"><i class="fa fa-minus" data-toggle="tooltip" data-placement="top" data-container="body" title="Não exigido"></i></td>';
			}
			if($meta['status_nota_media'] == 1){
				echo "<td class='text-center' style='vertical-align: middle;'>".$meta['nota_media']."</td>";
			}else{
				echo '<td class="text-center" style="vertical-align: middle;"><i class="fa fa-minus" data-toggle="tooltip" data-placement="top" data-container="body" title="Não exigido"></i></td>';
			}
			if($meta['status_porcentagem_ligacao_nota'] == 1){
				echo "<td class='text-center' style='vertical-align: middle;'>".$meta['porcentagem_ligacao_nota']."%</td>";
			}else{
				echo '<td class="text-center" style="vertical-align: middle;"><i class="fa fa-minus" data-toggle="tooltip" data-placement="top" data-container="body" title="Não exigido"></i></td>';
			}
			if($meta['status_erros_reclamacoes'] == 1){
				echo "<td class='text-center' style='vertical-align: middle;'>".$meta['erros_reclamacoes']."</td>";
			}else{
				echo '<td class="text-center" style="vertical-align: middle;"><i class="fa fa-minus" data-toggle="tooltip" data-placement="top" data-container="body" title="Não exigido"></i></td>';
			}
			if($meta['status_faltas_justificadas'] == 1){
				echo "<td class='text-center' style='vertical-align: middle;'>".$meta['faltas_justificadas']."</td>";
			}else{
				echo '<td class="text-center" style="vertical-align: middle;"><i class="fa fa-minus" data-toggle="tooltip" data-placement="top" data-container="body" title="Não exigido"></i></td>';
			}
			if($meta['status_absenteismo'] == 1){
				echo "<td class='text-center' style='vertical-align: middle;'>".$meta['absenteismo']."%</td>";
			}else{
				echo '<td class="text-center" style="vertical-align: middle;"><i class="fa fa-minus" data-toggle="tooltip" data-placement="top" data-container="body" title="Não exigido"></i></td>';
            }
            if($meta['status_pausa_registro'] == 1){
				echo "<td class='text-center' style='vertical-align: middle;'>".converteSegundosHoras($meta['pausa_registro']*60)."</td>";
			}else{
				echo '<td class="text-center" style="vertical-align: middle;"><i class="fa fa-minus" data-toggle="tooltip" data-placement="top" data-container="body" title="Não exigido"></i></td>';
            }
            if($meta['status_monitoria'] == 1){
				echo "<td class='text-center' style='vertical-align: middle;'>".sprintf("%01.2f", $meta['monitoria'])."%</td>";
			}else{
				echo '<td class="text-center" style="vertical-align: middle;"><i class="fa fa-minus" data-toggle="tooltip" data-placement="top" data-container="body" title="Não exigido"></i></td>';
            }
            if($meta['status_resolucao'] == 1){
				echo "<td class='text-center' style='vertical-align: middle;'>".$meta['resolucao']."%</td>";
			}else{
				echo '<td class="text-center" style="vertical-align: middle;"><i class="fa fa-minus" data-toggle="tooltip" data-placement="top" data-container="body" title="Não exigido"></i></td>';
            }
            if($meta['status_atendimentos_hora'] == 1){
				echo "<td class='text-center' style='vertical-align: middle;'>".$meta['atendimentos_hora']."%</td>";
			}else{
				echo '<td class="text-center" style="vertical-align: middle;"><i class="fa fa-minus" data-toggle="tooltip" data-placement="top" data-container="body" title="Não exigido"></i></td>';
			}

            if($meta['status_total_atendimentos'] == 1){
                if($meta['status_total_atendimentos_meio_turno'] == 1){
                    echo "<td class='text-center' style='vertical-align: middle;'>".$meta['total_atendimentos']."/".$meta['total_atendimentos_meio_turno']."</td>";
                }else{
                    echo "<td class='text-center' style='vertical-align: middle;'>".$meta['total_atendimentos']."</td>";
                }
			}else{
                echo '<td class="text-center" style="vertical-align: middle;"><i class="fa fa-minus" data-toggle="tooltip" data-placement="top" data-container="body" title="Não exigido"></i></td>';
			}

			if($meta['status_qtd_ajudas'] == 1){
				if($meta['status_qtd_ajudas_meio_turno'] == 1){
                    echo "<td class='text-center' style='vertical-align: middle;'>".$meta['qtd_ajudas']."/".$meta['qtd_ajudas_meio_turno']."</td>";
				}else{
                    echo "<td class='text-center' style='vertical-align: middle;'>".$meta['qtd_ajudas']."</td>";
				}
			}else{
				echo '<td class="text-center" style="vertical-align: middle;"><i class="fa fa-minus" data-toggle="tooltip" data-placement="top" data-container="body" title="Não exigido"></i></td>';
			}

			echo "</tr>";
        }
    }else{
        echo '<tr><td class="text-center warning" colspan="11">Sem metas cadastradas!</td></tr>';
    }
    echo "
            </tbody> 
        </table>
    ";
   
}

function pesquisas(){
    $dados_pesquisas = DBRead('', 'tb_pesquisa a', "INNER JOIN tb_contrato_plano_pessoa b ON a.id_contrato_plano_pessoa = b.id_contrato_plano_pessoa INNER JOIN tb_pessoa c ON b.id_pessoa = c.id_pessoa WHERE a.status = 1 OR a.status = 5", "c.nome, a.titulo, a.id_pesquisa, b.qtd_contratada");
    $qtd_pesquisas = 0;
    if($dados_pesquisas){
        foreach ($dados_pesquisas as $pesquisa) {
            $clientes = DBRead('', 'tb_contatos_pesquisa a', "INNER JOIN tb_pesquisa b ON a.id_pesquisa = b.id_pesquisa WHERE a.id_pesquisa = '".$pesquisa['id_pesquisa']."' AND a.id_contatos_pesquisa NOT IN (SELECT id_contatos_pesquisa FROM tb_agendamento_pesquisa WHERE status_agendamento = 0 AND data_hora > '".getDataHora()."') AND a.id_contatos_pesquisa NOT IN (SELECT id_contatos_pesquisa FROM tb_data_contato_pesquisa WHERE data_atualizacao >= ('".getDataHora()."' - INTERVAL 15 SECOND)) AND a.data_ultimo_contato < DATE_ADD('".getDataHora()."', INTERVAL - b.horas_entre_tentativas HOUR) AND a.status_pesquisa = 0 ORDER BY a.data_ultimo_contato","COUNT(*) AS cont");
            if($clientes[0]['cont'] >= 1){
                $qtd_pesquisas++;
            }
        }        
    }

    if($qtd_pesquisas == 0){
        $mensagem = '';
    }else if($qtd_pesquisas == 1){
        $mensagem = 'Existe 1 pesquisa pendente!';
    }else{
        $mensagem = 'Existem '.$qtd_pesquisas.' pesquisas pendentes!';
    }

    echo '<table class="table table-bordered" style="margin-bottom: 25px;">
            <thead>
                <tr style="font-size: 20px;">
                    <th class="text-center"><span style="font-weight: 300;">Ativos pendentes </span>('.$qtd_pesquisas.')</th>
                </tr>
                <tr>
                    <th style="font-size: 16px; font-weight: 300;" class="text-center">Alerta</th>
                </tr>
            </thead>
            <tbody style="font-size: 34px;">';
            if($qtd_pesquisas){    
             echo "
                <tr class=\"text-center warning\">
                    <td>$mensagem</td>    
                </tr>";    
            }
        echo'</tbody>
        </table>';
}

function atendimentos_pendentes(){
    $atendimentos_pendentes_integracao = DBRead('', "tb_integracao_atendimento_ixc", "WHERE salvo = 0","COUNT(*) AS qtd");
    $atendimentos_pendentes_chamado = DBRead('', "tb_chamado a", "INNER JOIN tb_chamado_atendimento b ON a.id_chamado = b.id_chamado WHERE a.id_chamado_status != '3' AND a.id_chamado_status != '4'","COUNT(*) AS qtd");
    if($atendimentos_pendentes_integracao[0]['qtd'] == 0){
        $mensagem_integracao = '';
    }else if($atendimentos_pendentes_integracao[0]['qtd'] == 1){
        $mensagem_integracao = 'Existe 1 atendimento pendente na integração!';
    }else{
        $mensagem_integracao = 'Existem '.$atendimentos_pendentes_integracao[0]['qtd'].' atendimentos pendentes na integração!';
    }
    if($atendimentos_pendentes_chamado[0]['qtd'] == 0){
        $mensagem_chamado = '';
    }else if($atendimentos_pendentes_chamado[0]['qtd'] == 1){
        $mensagem_chamado = 'Existe 1 atendimento pendente nos chamados!';
    }else{
        $mensagem_chamado = 'Existem '.$atendimentos_pendentes_chamado[0]['qtd'].' atendimentos pendentes nos chamados!';
    }
    $qtd_total = $atendimentos_pendentes_integracao[0]['qtd']+$atendimentos_pendentes_chamado[0]['qtd'];
    echo '<table class="table table-bordered" style="margin-bottom: 25px;">
            <thead>
                <tr style="font-size: 20px;">
                    <th class="text-center"><span style="font-weight: 300;">Atendimentos pendentes </span>('.$qtd_total.')</th>
                </tr>
                <tr>
                    <th style="font-size: 16px; font-weight: 300;" class="text-center">Alerta</th>
                </tr>
            </thead>
            <tbody style="font-size: 34px;">';
            if($atendimentos_pendentes_integracao[0]['qtd']){    
                echo "
                <tr class=\"text-center danger\">
                    <td>$mensagem_integracao</td>    
                </tr>";    
            }
            if($atendimentos_pendentes_chamado[0]['qtd']){    
                echo "
                <tr class=\"text-center danger\">
                    <td>$mensagem_chamado</td>    
                </tr>";    
            }
        echo'</tbody>
        </table>';
}

function solicitacoes_ajuda(){
    $dados_ajuda = DBRead('', 'tb_solicitacao_ajuda a', "INNER JOIN tb_usuario b ON a.atendente = b.id_usuario INNER JOIN tb_pessoa c ON b.id_pessoa = c.id_pessoa WHERE a.data_encerramento IS NULL ORDER BY a.id_solicitacao_ajuda ASC","a.data_inicio, b.id_asterisk, c.nome AS 'nome_usuario'");
    $retorno_agents = troca_dados_curl("http://172.31.18.211/central_simples/retorna_agents.php");

    if($dados_ajuda){
        $qtd_ajuda = sizeof($dados_ajuda);
    }else{
        $qtd_ajuda = 0;
    }

    echo '<table class="table table-bordered" style="margin-bottom: 25px;">
            <thead>
                <tr style="font-size: 20px;">
                    <th class="text-center" colspan="3"><span style="font-weight: 300;">Solicitações de ajuda </span>('.$qtd_ajuda.')</th>
                </tr>
                <tr style="font-size: 16px;">
                    <th style="font-weight: 300;">PA</th>
                    <th style="font-weight: 300;">Atendente</th>
                    <th style="font-weight: 300;">Hora</th>
                </tr>
            </thead>
            <tbody style="font-size: 34px;">';

            if($dados_ajuda){    
                foreach ($dados_ajuda as $conteudo_ajuda) {
                    $sip = '';
                    $nome = '';
                    foreach ($retorno_agents['dados'] as $conteudo_agents) {
                        if($conteudo_agents['agent'] == $conteudo_ajuda['id_asterisk']){                    
                            $nome = $conteudo_agents['nome'];
                            $sip = $conteudo_agents['sip'];
                            break;
                        }
                    }
                    if(!$nome){
                        $nome = $conteudo_ajuda['nome_usuario'];
                    }
                    echo "<tr class=\"danger\">
                            <td>".$sip."</td>
                            <td>".$nome."</td>
                            <td>".substr($conteudo_ajuda['data_inicio'],11,8)."</td>          
                        </tr>";
                }    
            }

    echo '</tbody></table>';
}

function solicitacoes_ajuda_audio(){    
    $dados_ajuda = DBRead('', 'tb_solicitacao_ajuda', "WHERE data_encerramento IS NULL");
    if($dados_ajuda){
        echo 1;
    }else{
        echo 0;
    }
}

function resultado_metas($mes){

    $hora_agora = explode(':',getDataHora('hora'));
    $minuto_agora = $hora_agora[1];
    
    $dados_medalhistas = DBRead('','tb_telao_resultado_metas','ORDER BY numero_medalha ASC, nome_atendente ASC');
    
    echo '
        <table class="table table-bordered" id="table_resultado_metas" style="margin-bottom: 0;"> 
            <tbody style="font-size: 34px;">
    ';
    if($dados_medalhistas && $minuto_agora >= 5){       
        
        foreach ($dados_medalhistas as $medalhista) {
            if($medalhista['numero_medalha'] ==  '1'){
                $img = '<img src="inc/img/meta_diamond.png" height="52" width="40">';
            }else if($medalhista['numero_medalha'] ==  '2'){
                $img = '<img src="inc/img/meta_gold.png" height="52" width="40">';
            }else if($medalhista['numero_medalha'] ==  '3'){       
                $img = '<img src="inc/img/meta_silver.png" height="52" width="40">';
            }else if($medalhista['numero_medalha'] ==  '4'){
                $img = '<img src="inc/img/meta_bronze.png" height="52" width="40">';
            }
            echo '
            <tr>
                <td class="text-center">'.$img.'</td>
                <td>'.$medalhista['nome_atendente'].'</td>
            </tr>
            ';
        }

    }else if($minuto_agora < 5){
        echo '<tr><td class="text-center info" colspan="2" style="padding:0px;">Calculando...</td></tr>';
    }else{
        echo '<tr><td class="text-center warning" colspan="2" style="padding:0px;">Sem medalhistas!</td></tr>';
    }
    echo "
            </tbody> 
        </table>
    ";
}

function resultado_metas_mes($mes){    
    $hora_agora = explode(':',getDataHora('hora'));
    $minuto_agora = $hora_agora[1];
    
    if($minuto_agora < 5){
        echo '
            <table class="table table-bordered" style="margin-bottom:0;"> 
                <thead> 
                    <tr style="font-size: 20px;">
                        <th class="text-center" colspan="9"><span style="font-weight: 300;">Medalhistas de </span>'.$mes.' (?)</th>
                    </tr>
                </thead>
            </table>
        ';
    
    }else{
        $cont_medalhistas = DBRead('','tb_telao_resultado_metas','ORDER BY numero_medalha ASC, nome_atendente ASC',"COUNT(*) AS 'total'");
        echo '
            <table class="table table-bordered" style="margin-bottom:0;"> 
                <thead> 
                    <tr style="font-size: 20px;">
                        <th class="text-center" colspan="9"><span style="font-weight: 300;">Medalhistas de </span>'.$mes.' ('.$cont_medalhistas[0]['total'].')</th>
                    </tr>
                </thead>
            </table>
        ';
    }

    
}

function alerta_painel(){
    $dados_alertas = DBRead('', 'tb_alerta_painel a', "WHERE status = '1' OR status = '4' ","id_alerta_painel");
    
    if($dados_alertas){
        $qtd_alertas = sizeof($dados_alertas);
    }else{
        $qtd_alertas = 0;
    }

    if($qtd_alertas == 0){
        $mensagem = '';
    }else if($qtd_alertas == 1){
        $mensagem = '1 notificação pendente!';
    }else{
        $mensagem = $qtd_alertas.' notificações pendentes!';
    }

    if($dados_alertas){    
        echo '
        <table class="table table-bordered" style="margin-bottom: 25px;">
            <thead>
                <tr style="font-size: 20px;">
                    <th class="text-center"><span style="font-weight: 300;">Notificação de parada </span>('.$qtd_alertas.')</th>
                </tr>
                <tr style="font-size: 16px;">
                    <th class="text-center" style="font-weight: 300;">Alerta</th>
                </tr>
            </thead>
            <tbody style="font-size: 34px;">
                <tr class="danger">
                    <td class="text-center">'.$mensagem.'</td>
                </tr>
            </tbody>
        </table>
        ';
    }
   
}

function alerta_painel_audio(){    
    $dados_alertas = DBRead('', 'tb_alerta_painel a', "WHERE status = '1'","id_alerta_painel");
    if($dados_alertas){
        echo 1;
    }else{
        echo 0;
    }
}

function contador_ajuda_alerta () {
    $dados_ajuda = DBRead('', 'tb_solicitacao_ajuda a', "INNER JOIN tb_usuario b ON a.atendente = b.id_usuario INNER JOIN tb_pessoa c ON b.id_pessoa = c.id_pessoa WHERE a.data_encerramento IS NULL ORDER BY a.id_solicitacao_ajuda ASC","a.data_inicio, b.id_asterisk, c.nome AS 'nome_usuario'");

    $dados_alertas = DBRead('', 'tb_alerta_painel a', "WHERE status = '1' OR status = '4' ","id_alerta_painel");

    if ($dados_ajuda != false || $dados_alertas != false) {
        $cont = sizeof($dados_ajuda);

    } else {
        $cont = 0;
    }

    echo json_encode($cont);
}

?>