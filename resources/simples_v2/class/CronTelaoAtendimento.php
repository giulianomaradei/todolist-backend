<?php
require_once(__DIR__ . "/System.php");

$fila = "'callATENDIMENTOvip','callATENDIMENTOalta','callATENDIMENTOnormal','callATENDIMENTObaixa','callATENDIMENTOnotificacaoparada', 'callATENDIMENTOvipEXT','callATENDIMENTOaltaEXT','callATENDIMENTOnormalEXT','callATENDIMENTObaixaEXT','callATENDIMENTOnotificacaoparadaEXT','callATENDIMENTOvipEXP','callATENDIMENTOaltaEXP','callATENDIMENTOnormalEXP','callATENDIMENTObaixaEXP','callATENDIMENTOnotificacaoparadaEXP'";

$data_hoje = explode('-',getDataHora('data'));

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

$afastamentos = 'n';

if ($data_de) {
    $filtro_entradas .= " AND b.time >= '".$data_de." 00:00:00'";
    $filtro_pausas .= " AND data_pause >= '".$data_de." 00:00:00'";
    $filtro_erros .= " AND data_cadastrado >= '".$data_de." 00:00:00'";
}

if ($data_ate) {
    $filtro_entradas .= " AND b.time <= '".$data_ate." 23:59:59'";
    $filtro_pausas .= " AND data_pause <= '".$data_ate." 23:59:59'";
    $filtro_erros .= " AND data_cadastrado <= '".$data_ate." 23:59:59'";
}

$query = "DELETE FROM tb_telao_resultado_metas WHERE id_telao_resultado_metas > 0";
$link = DBConnect('');
$result = @mysqli_query($link, $query);
DBClose($link);

$data_referencia = new DateTime($data_ate);
$data_referencia->modify('first day of this month');
$data_referencia = $data_referencia->format('Y-m-d');

$query = "DELETE FROM tb_resultado_metas WHERE data_referencia = '".$data_referencia."' ";
$link = DBConnect('');
$result = @mysqli_query($link, $query);
DBClose($link);

$metas = DBRead('', 'tb_meta', "WHERE tipo = '1' AND status = '1' AND data_ate >= '".$data_ate."' AND data_de <= '".$data_ate."' ORDER BY nota_media, porcentagem_ligacao_nota ASC");

$dados_usuarios = DBRead('','tb_usuario a'," INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.lider_direto AND a.status = 1 AND a.id_perfil_sistema = 3 ORDER BY b.nome");
// $dados_usuarios = DBRead('','tb_usuario a'," INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_usuario = 375 ORDER BY b.nome");

/* foreach ($dados_usuarios as $usuario) {
    echo 'Nome: '. $usuario['nome'].'<br>';
}

die(); */
foreach ($dados_usuarios as $usuario) {

    $data_inicial_turno = (new DateTime($data_de))->modify('first day of this month')->format('Y-m-d');
    $turno_atendente = DBRead('', 'tb_horarios_escala', "WHERE data_inicial = '".$data_inicial_turno."' AND id_usuario = '".$usuario['id_usuario']."' LIMIT 1", "carga_horaria");
    if($turno_atendente){
        $turno_atendente = $turno_atendente[0]['carga_horaria'];
    }else{
        $turno_atendente = "integral";
    }

    $qtd_entradas = 0;
    $soma_notas = 0;
    $qtd_notas = 0;
    $soma_ta = 0;	
    $qtd_erros = 0;		
    $duracao_total_pausa_registro = 0;    
    $duracao_total_pausa_ativo = 0;
    $duracao_total_pausa_manutencao_ti = 0;
    $duracao_total_pausa_ajuda_supervisao = 0;					
    $monitoria_resultado = 0;
    $flag_individual = '';

    $dados_usuario = DBRead('','tb_usuario a',"INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE id_usuario = '".$usuario['id_usuario']."'","a.*, b.nome");

    $nome_usuario = $dados_usuario[0]['nome'];
    $id_asterisk_usuario = $dados_usuario[0]['id_asterisk'];
    $id_ponto_usuario = $dados_usuario[0]['id_ponto'];

    $data_string_ponto = '
        {
            "report": {				
                "start_date": "'.$data_de.'",
                "end_date": "'.$data_ate.'",
                "group_by": "",
                "columns": "employee_name,start_date,end_date,observation",
                "employee_id": '.$id_ponto_usuario.',
                "row_filters": "",
                "format": "json"
            }
        }
    ';  
    
    $result_ponto = troca_dados_curl('https://api.pontomais.com.br/external_api/v1/reports/absences', $data_string_ponto, array('Content-Type: application/json','access-token: FcogHwphYbzMk3IF1tXBmh5ypV49O-74CSf7dMxE3cMcabhbaajbefjai'));

    if ($result_ponto['data'][0][0]['data'][0]['start_date']) {
        $afastamento_usuario = 1;

    } else {
        $afastamento_usuario = 0;
    }

    if(($afastamentos == 'n') || ($afastamentos == 's' && !$afastamento_usuario)){

        // $filtro_entradas_usuario = " AND b.agent = 'AGENT/$id_asterisk_usuario' AND a.queuename IN ('callATENDIMENTOvip','callATENDIMENTOalta','callATENDIMENTOnormal','callATENDIMENTObaixa','callATENDIMENTOnotificacaoparada', 'callATENDIMENTOvipEXT','callATENDIMENTOaltaEXT','callATENDIMENTOnormalEXT','callATENDIMENTObaixaEXT','callATENDIMENTOnotificacaoparadaEXT', 'callATENDIMENTOvipEXP','callATENDIMENTOaltaEXP','callATENDIMENTOnormalEXP','callATENDIMENTObaixaEXP','callATENDIMENTOnotificacaoparadaEXP') AND (c.data2 >= 30 OR c.data4 >= 30)";
        $filtro_entradas_usuario = " AND b.agent = 'AGENT/$id_asterisk_usuario' AND (c.data2 >= 30 OR c.data4 >= 30)";

        // $dados_entradas = DBRead('snep','queue_log a',"INNER JOIN queue_log b ON (b.callid = a.callid AND b.event = 'CONNECT') INNER JOIN queue_log c ON (c.callid = a.callid AND (c.event = 'COMPLETEAGENT' OR c.event = 'COMPLETECALLER' OR c.event = 'BLINDTRANSFER' OR c.event = 'ATTENDEDTRANSFER')) LEFT JOIN pesquisa d ON  a.callid = d.UNIQUEID WHERE a.event = 'ENTERQUEUE' $filtro_entradas $filtro_entradas_usuario GROUP BY b.id ORDER BY b.id", "c.event AS 'finalizacao_event', c.data1 AS 'finalizacao_data1', c.data2 AS 'finalizacao_data2', c.data4 AS 'finalizacao_data4', d.nota AS 'nota'");
        
            //COMENTEI
        $dados_entradas = DBRead('snep','queue_log a',"INNER JOIN queue_log b ON (b.callid = a.callid AND b.event = 'CONNECT') INNER JOIN queue_log c ON (c.callid = a.callid AND (c.event = 'COMPLETEAGENT' OR c.event = 'COMPLETECALLER' OR c.event = 'BLINDTRANSFER' OR c.event = 'ATTENDEDTRANSFER')) LEFT JOIN pesquisa d ON  a.callid = d.UNIQUEID WHERE a.event = 'ENTERQUEUE' $filtro_entradas $filtro_entradas_usuario GROUP BY b.id ORDER BY b.id", "a.queuename AS 'enterqueue_queuename', c.event AS 'finalizacao_event', c.data1 AS 'finalizacao_data1', c.data2 AS 'finalizacao_data2', c.data4 AS 'finalizacao_data4', d.nota AS 'nota'");

        if($dados_entradas){
            foreach ($dados_entradas as $conteudo_entradas) {
                if(preg_match("/'".$conteudo_entradas['enterqueue_queuename']."'/i", $fila) || !$fila){

                    $info_chamada = array(
                        'tempo_atendimento' => '',
                        'nota' => ''
                    );
    
                    $info_chamada['nota'] = $conteudo_entradas['nota'];
    
                    if ($conteudo_entradas['finalizacao_event']=='COMPLETEAGENT' || $conteudo_entradas['finalizacao_event']=='COMPLETECALLER') {
                        $info_chamada['tempo_atendimento'] = $conteudo_entradas['finalizacao_data2'];	
    
                    } else if (($conteudo_entradas['finalizacao_event']=='BLINDTRANSFER' || $conteudo_entradas['finalizacao_event']=='ATTENDEDTRANSFER') && $conteudo_entradas['finalizacao_data1'] != 'LINK') {				
                        $info_chamada['tempo_atendimento'] = $conteudo_entradas['finalizacao_data4'];
                    }				
    
                    $qtd_entradas++;
    
                    if ($info_chamada['nota']) {
                        $soma_notas += $info_chamada['nota'];
                        $qtd_notas++;
                    }						
                    $soma_ta += $info_chamada['tempo_atendimento'];	
                }
            }
        }

        $dados_erros = DBRead('','tb_erro_atendimento',"WHERE id_usuario = '".$usuario['id_usuario']."' AND status = '1' $filtro_erros","COUNT(*) AS 'qtd_erros'");
        $qtd_erros = $qtd_erros + $dados_erros[0]['qtd_erros'];

        $data_string_ponto = '
            {
                "report": {				
                    "start_date": "'.$data_de.'",
                    "end_date": "'.$data_ate.'",
                    "group_by": "",
                    "row_filters": "compensatory,missing_time_1h,missing_time_2h,missing_time_3h,missing_time_4h,missing_time_5h,missing_time_6h,missing_time_7h,missing_time_8h",
                    "employee_id": '.$id_ponto_usuario.',
                    "columns": "name,total_working_time,total_missing_time,total_worked_time,percent_abs",
                    "format": "json"
                }
            }
        ';  
        $result_ponto = troca_dados_curl('https://api.pontomais.com.br/external_api/v1/reports/absenteeism', $data_string_ponto, array('Content-Type: application/json','access-token: FcogHwphYbzMk3IF1tXBmh5ypV49O-74CSf7dMxE3cMcabhbaajbefjai'));
        $horas_previstas = $result_ponto['data'][0][0]['data'][0]['total_working_time'];
        $horas_faltantes = $result_ponto['data'][0][0]['data'][0]['total_missing_time'];
        $absenteismo = $result_ponto['data'][0][0]['data'][0]['percent_abs'];
        $absenteismo = str_replace('%','',$absenteismo);
        $absenteismo = str_replace(' ','',$absenteismo);
        $absenteismo = str_replace(',','.',$absenteismo);

        $data_string_ponto = '
            {
                "report": {				
                    "start_date": "'.$data_de.'",
                    "end_date": "'.$data_ate.'",
                    "group_by": "",
                    "columns": "name,date,missing_motive",
                    "employee_id": '.$id_ponto_usuario.',
                    "row_filters": "",
                    "format": "json"
                }
            }
        ';  
        
        $result_ponto = troca_dados_curl('https://api.pontomais.com.br/external_api/v1/reports/missing_days', $data_string_ponto, array('Content-Type: application/json','access-token: FcogHwphYbzMk3IF1tXBmh5ypV49O-74CSf7dMxE3cMcabhbaajbefjai'));
        $qtd_faltas_justificadas = 0;

        if ($result_ponto['data'][0][0]['data']) {
            foreach ($result_ponto['data'][0][0]['data'] as $conteudo) {
                if ($conteudo['missing_motive'] == 'Atestado') {
                    $qtd_faltas_justificadas++;
                }
            }
        }

        $data_string_ponto = '
            {
                "report": {	
                    "group_by": "",
                    "start_date": "'.$data_de.'",
                    "end_date": "'.$data_ate.'",
                    "columns": "employee_name,total_time,missing_days",
                    "employee_id": '.$id_ponto_usuario.',
                    "row_filters": "",
                    "format": "json"
                }
            }
        ';  

        $result_ponto = troca_dados_curl('https://api.pontomais.com.br/external_api/v1/reports/period_summaries', $data_string_ponto, array('Content-Type: application/json','access-token: FcogHwphYbzMk3IF1tXBmh5ypV49O-74CSf7dMxE3cMcabhbaajbefjai'));
                
        $horas_ponto = explode(":", $result_ponto['data'][0][0]['data'][0]['total_time']);
        if($horas_ponto[0] && $horas_ponto[1]) {
            $segundos_ponto = ($horas_ponto[0]*3600)+($horas_ponto[1]*60);
        }else{
            $segundos_ponto = 0;
        }

        //COMENTEI
        $dados_pausas = DBRead('snep','queue_agents_pause',"WHERE codigo = '$id_asterisk_usuario' AND (tipo_pausa = 4 OR tipo_pausa = 9 OR tipo_pausa = 6 OR tipo_pausa = 15) AND data_unpause IS NOT NULL $filtro_pausas ORDER BY data_pause ASC");

        if ($dados_pausas) {
            foreach ($dados_pausas as $conteudo_pausas) {
                $data_pausa = strtotime($conteudo_pausas['data_pause']);
                $data_retorno = strtotime($conteudo_pausas['data_unpause']);
                $duracao_pausa = ($data_retorno - $data_pausa);
                if ($conteudo_pausas['tipo_pausa'] == '4') {
                    $duracao_total_pausa_registro += $duracao_pausa;
        
                } else if ($conteudo_pausas['tipo_pausa'] == '6') {
                    $duracao_total_pausa_ativo += $duracao_pausa;	
        
                } else if ($conteudo_pausas['tipo_pausa'] == '15') {
                    $duracao_total_pausa_manutencao_ti += $duracao_pausa;					
        
                } else if ($conteudo_pausas['tipo_pausa'] == '9') {
                    $duracao_total_pausa_ajuda_supervisao += $duracao_pausa;					
                }   
            }
        }

        

        $dados_monitorias = DBRead('', 'tb_monitoria_mes', "WHERE tipo_monitoria = 1 AND data_referencia = '".substr($data_de,0,7)."-01'", 'id_monitoria_mes');

        foreach ($dados_monitorias as $monitoria) {
            $dados_resultado = DBRead('','tb_monitoria_resultado', "WHERE id_usuario = '".$usuario['id_usuario']."' AND id_monitoria_mes = '".$monitoria['id_monitoria_mes']."' ");

            if ($dados_resultado) {
                $monitoria_resultado = $dados_resultado[0]['resultado'];
                break;
            }
        }

        $dados_atendimentos = DBRead('','tb_atendimento', "WHERE falha != '2' AND gravado = '1' AND data_inicio BETWEEN '" .$data_de. " 00:00:00' AND '" .$data_ate. " 23:59:59' AND id_usuario = '".$usuario['id_usuario']."' ", "COUNT(*) as cont");
        $total_atendimentos = $dados_atendimentos[0]['cont'];

        if($total_atendimentos != 0){
            $dados_cont_resolucao = DBRead('', 'tb_atendimento', "WHERE falha != '2' AND gravado = '1' AND (resolvido = '1' OR resolvido = '3') AND data_inicio BETWEEN '" .$data_de. " 00:00:00' AND '" .$data_ate. " 23:59:59' AND id_usuario = '".$usuario['id_usuario']."'", "COUNT(*) as cont");

            $cont_resolucao = $dados_cont_resolucao[0]['cont'];
            $percentual_cont_resolucao = sprintf("%01.2f", round($cont_resolucao * 100) / ($total_atendimentos == 0 ? 1 : $total_atendimentos), 2);

            $horas_calc_atendimentos_hora = converteHorasDecimal(converteSegundosHoras($segundos_ponto-$duracao_total_pausa_ativo-$duracao_total_pausa_manutencao_ti-$duracao_total_pausa_registro-$duracao_total_pausa_ajuda_supervisao));
            $atendimentos_hora = sprintf("%01.2f", $total_atendimentos / ($horas_calc_atendimentos_hora == 0 ? 1 : $horas_calc_atendimentos_hora), 2);
        }else{
            $cont_resolucao = 0;
            $percentual_cont_resolucao = 0;            
            $horas_calc_atendimentos_hora = 0;
            $atendimentos_hora = 0;
        }

        $qtd_ajuda = DBRead('','tb_solicitacao_ajuda', "WHERE data_inicio BETWEEN '" .$data_de. " 00:00:00' AND '" .$data_ate. " 23:59:59' AND atendente = '".$usuario['id_usuario']."' ", "COUNT(*) as cont");
        $qtd_ajuda = $qtd_ajuda[0]['cont'];

        if($metas){
            foreach ($metas as $meta) {
                $meta_nome = $meta['nome'];
                $meta_tma = $meta['tempo_medio_atendimento'];
                $status_meta_tma = $meta['status_tempo_medio_atendimento'];
                $meta_nota_media = $meta['nota_media'];
                $status_meta_nota_media = $meta['status_nota_media'];
                $meta_porcentagem = $meta['porcentagem_ligacao_nota'];	
                $status_meta_porcentagem = $meta['status_porcentagem_ligacao_nota'];	
                $meta_erros = $meta['erros_reclamacoes'];
                $status_meta_erros = $meta['status_erros_reclamacoes'];
                $meta_faltas_justificadas = $meta['faltas_justificadas'];
                $status_meta_faltas_justificadas = $meta['status_faltas_justificadas'];
                $meta_absenteismo = $meta['absenteismo'];
                $status_meta_absenteismo = $meta['status_absenteismo'];
                $meta_pausa_registro = $meta['pausa_registro'];
                $status_meta_pausa_registro = $meta['status_pausa_registro'];
                $meta_monitoria = $meta['monitoria'];
                $status_meta_monitoria = $meta['status_monitoria'];
                $meta_resolucao = $meta['resolucao'];
                $status_meta_resolucao = $meta['status_resolucao'];
                $meta_atendimentos_hora = $meta['atendimentos_hora'];
                $status_meta_atendimentos_hora = $meta['status_atendimentos_hora'];

                $meta_qtd_ajudas = $meta['qtd_ajudas'];
                $status_meta_qtd_ajudas = $meta['status_qtd_ajudas'];

                $firstDate  = new DateTime($data_de);
                $secondDate = new DateTime($data_ate);
                $intvl = $firstDate->diff($secondDate);
                $intervalo = $intvl->d+1;

                $data_hoje = new DateTime('today');

                $flag_tempo_atendimento = 0;

                if($meta['tempo_atendimento'] == 2){
                    $flag_tempo_atendimento = 1;
                }else{                        
                    if($turno_atendente != "integral" && $meta['status_total_atendimentos_meio_turno'] == 1){
                        if($data_hoje->format('Y-m-d') >= $secondDate->format('Y-m-d')){
                            $total_atendimentos_hoje = $meta['total_atendimentos_meio_turno'];	
                        }else{
                            $total_atendimentos_hoje = round($data_hoje->format('d')*$meta['total_atendimentos_meio_turno']/$intervalo);
                        }
                    }else{
                        if($data_hoje->format('Y-m-d') >= $secondDate->format('Y-m-d')){
                            $total_atendimentos_hoje = $meta['total_atendimentos'];	
                        }else{
                            $total_atendimentos_hoje = round($data_hoje->format('d')*$meta['total_atendimentos']/$intervalo);	
                        }
                    }
                    if($total_atendimentos_hoje <= $total_atendimentos){
                        $flag_tempo_atendimento = 1;
                    }
                }		

                // if($usuario['id_usuario'] == '327' && $meta_nome == 'Bronze'){
                    // var_dump("Metas: "+$meta_tma >= floor($soma_ta/($qtd_entradas == 0 ? 1 : $qtd_entradas)) || $status_meta_tma == 2,
                    // "Nota Media: "+$meta_nota_media <= round($soma_notas/($qtd_notas == 0 ? 1 : $qtd_notas), 2) || $status_meta_nota_media == 2,
                    // "Meta Porcentagem: "+$meta_porcentagem <= round($qtd_notas * 100 /($qtd_entradas == 0 ? 1 : $qtd_entradas), 2) || $status_meta_porcentagem == 2,
                    // "Meta Erros: "+$meta_erros >= $qtd_erros || $status_meta_erros == 2,
                    // "Faltas Justificadas: "+$meta_faltas_justificadas >= $qtd_faltas_justificadas || $status_meta_faltas_justificadas == 2,
                    // "Absebteismo: "+$meta_absenteismo >= $absenteismo || $status_meta_absenteismo == 2,
                    // "Pausa: "+$meta_pausa_registro >= ($duracao_total_pausa_registro/60) || $status_meta_pausa_registro == 2,
                    // "Monitoria: "+$meta_monitoria <= sprintf("%01.2f", $monitoria_resultado) || $status_meta_monitoria == 2,
                    // "Resolução: "+$meta_resolucao <= $percentual_cont_resolucao || $status_meta_resolucao == 2,
                    // "Metas: "+$meta_atendimentos_hora <= $atendimentos_hora || $status_meta_atendimentos_hora == 2
                    
                        
                    // );
                    
                // }
                
                if(
                    ($meta_tma >= floor($soma_ta/($qtd_entradas == 0 ? 1 : $qtd_entradas)) || $status_meta_tma == 2) && 
                    ($meta_nota_media <= round($soma_notas/($qtd_notas == 0 ? 1 : $qtd_notas), 2) || $status_meta_nota_media == 2) && 
                    ($meta_porcentagem <= round($qtd_notas * 100 /($qtd_entradas == 0 ? 1 : $qtd_entradas), 2) || $status_meta_porcentagem == 2) && 
                    ($meta_erros >= $qtd_erros || $status_meta_erros == 2) && 
                    ($meta_faltas_justificadas >= $qtd_faltas_justificadas || $status_meta_faltas_justificadas == 2) && 
                    ($meta_absenteismo >= $absenteismo || $status_meta_absenteismo == 2) && 
                    ($meta_pausa_registro >= ($duracao_total_pausa_registro/60) || $status_meta_pausa_registro == 2) && 
                    ($meta_monitoria <= sprintf("%01.2f", $monitoria_resultado) || $status_meta_monitoria == 2) && 
                    ($meta_resolucao <= $percentual_cont_resolucao || $status_meta_resolucao == 2) && 
                    ($meta_atendimentos_hora <= $atendimentos_hora || $status_meta_atendimentos_hora == 2) && 
                    ($meta_qtd_ajudas >= $qtd_ajuda || $status_meta_qtd_ajudas == 2) && 
                    ($flag_tempo_atendimento == 1)
                )
                {
                    $flag_individual = $meta_nome;
                }
            }
        }
        $numero_medalha = '';
        if($flag_individual ==  'Bronze'){
            $numero_medalha = 4;
        }else if($flag_individual ==  'Silver'){
            $numero_medalha = 3;
        }else if($flag_individual ==  'Gold'){
            $numero_medalha = 2;
        }else if($flag_individual ==  'Diamond'){
            $numero_medalha = 1;
        }

        if($numero_medalha != ''){
            $dados_atendente = DBRead('snep','queue_agents',"WHERE codigo='".$usuario['id_asterisk']."'");

            $dados = array(
                'id_usuario' => $usuario['id_usuario'],
                'numero_medalha' => $numero_medalha,
                'nome_atendente' => $dados_atendente[0]['membername'],
            );

            DBCreate('', 'tb_telao_resultado_metas', $dados);

            $dados_resultado_metas = array(
                'id_usuario' => $usuario['id_usuario'],
                'numero_medalha' => $numero_medalha,
                'nome_atendente' => $dados_atendente[0]['membername'],
                'data_referencia' => $data_referencia
            );

            DBCreate('', 'tb_resultado_metas', $dados_resultado_metas);
        }
    }
}




?>