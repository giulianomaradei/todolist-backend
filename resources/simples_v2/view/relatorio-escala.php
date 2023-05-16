
<?php
	require_once(__DIR__."/../class/System.php");

$data = getDataHora();
$ano_atual = $data[0].$data[1].$data[2].$data[3];
$mes_atual = $data[5].$data[6];
$dia_de_hoje = $data[8].$data[9];
$mes_atual ++;

if($mes_atual == 13){
    $mes_atual = 01;
    $ano_atual++;
} 

$data = $data[0].$data[1].$data[2].$data[3].$data[5].$data[6].$data[8].$data[9];
$tipo_relatorio = (! empty($_POST['tipo_relatorio'])) ? $_POST['tipo_relatorio'] : 1;
$gerar = (! empty($_POST['gerar'])) ? 1 : 0;
$id_usuario = $_SESSION['id_usuario'];
$usuario = (! empty($_POST['usuario'])) ? $_POST['usuario'] : '';

$id_contrato_plano_pessoa = (! empty($_POST['id_contrato_plano_pessoa'])) ? $_POST['id_contrato_plano_pessoa'] : '';
if ($id_contrato_plano_pessoa) {
	$dados_contrato = DBRead('', 'tb_contrato_plano_pessoa a', "INNER JOIN tb_plano b ON a.id_plano = b.id_plano INNER JOIN tb_pessoa c ON a.id_pessoa = c.id_pessoa WHERE a.id_contrato_plano_pessoa = '$id_contrato_plano_pessoa'", "a.*, b.cod_servico, b.nome AS 'plano', c.nome AS 'nome_pessoa'");

	if ($dados_contrato[0]['nome_contrato']) {
		$nome_contrato = " (" . $dados_contrato[0]['nome_contrato'] . ") ";
	}

	$contrato = $dados_contrato[0]['nome_pessoa'] . " " . $nome_contrato . " - " . getNomeServico($dados_contrato[0]['cod_servico']) . " - " . $dados_contrato[0]['plano'] . " (" . $dados_contrato[0]['id_contrato_plano_pessoa'] . ")";
}

$usuario2 = (! empty($_POST['usuario2'])) ? $_POST['usuario2'] : '';
$dados = DBRead('', 'tb_usuario', "WHERE id_usuario = '$id_usuario'");
$perfil_sistema = $dados[0]['id_perfil_sistema'];
$var_ano = (! empty($_POST['var_ano'])) ? $_POST['var_ano'] : $ano_atual;
$hoje = explode("-", getDataHora());
$hoje = $hoje[1];
$var_mes = (! empty($_POST['var_mes'])) ? $_POST['var_mes'] : $hoje;
$dia_folgas = (! empty($_POST['dia_folgas'])) ? $_POST['dia_folgas'] : '';
$dia_folga_domingo = (! empty($_POST['dia_folga_domingo'])) ? $_POST['dia_folga_domingo'] : '';
$horario_inicial2 = (! empty($_POST['horario_inicial2'])) ? $_POST['horario_inicial2'] : '0';
$horario_final2 = (! empty($_POST['horario_final2'])) ? $_POST['horario_final2'] : '0';
$expediente2 = (! empty($_POST['expediente2'])) ? $_POST['expediente2'] : '0';
$horario_inicial = (! empty($_POST['horario_inicial'])) ? $_POST['horario_inicial'] : '';
$horario_final = (! empty($_POST['horario_final'])) ? $_POST['horario_final'] : '';
$expediente = (! empty($_POST['expediente'])) ? $_POST['expediente'] : '0';
$turno = (! empty($_POST['turno'])) ? $_POST['turno'] : '0';
$turno2 = (! empty($_POST['turno2'])) ? $_POST['turno2'] : '0';
$filtro = (! empty($_POST['filtro'])) ? $_POST['filtro'] : '0';
$folga = (! empty($_POST['folga'])) ? $_POST['folga'] : '';
$folga_domingo = (! empty($_POST['folga_domingo'])) ? $_POST['folga_domingo'] : '';
$status = (! empty($_POST['status'])) ? $_POST['status'] : '';
$lider = (!empty($_POST['lider'])) ? $_POST['lider'] : '';
$ponderado = (!empty($_POST['ponderado'])) ? $_POST['ponderado'] : '';
$mes_inicio = (!empty($_POST['mes_inicio'])) ? $_POST['mes_inicio'] : '';
$mes_fim = (!empty($_POST['mes_fim'])) ? $_POST['mes_fim'] : '';
$data_de_primeiro_dia = '01/'.$hoje.'/'.$ano_atual;
$hoje2 = $hoje+1;
$ano_atual2 = $ano_atual;

if($hoje2 > 11){
    $hoje2 = '01';
    $ano_atual = $ano_atual+1;
}
if($dia_de_hoje == 30 || $dia_de_hoje == 31){
    $dia_de_hoje == 30;
}
if($hoje2 == '02' || $hoje2 == '2'){
    if($dia_de_hoje == 27){
        $dia_de_hoje == 01;
    }
}
//ONTEM
$date = new DateTime('today');
if($date->format('Y-m-d') != $ano_atual2.'-'.sprintf('%02d', $hoje2).'-'.'01'){
    $date = $date->modify('yesterday');
    $hoje_amostra = $date->format('d/m/Y');
}else{
    $hoje_amostra = '01/'.sprintf('%02d', $hoje2).'/'.$ano_atual2;
}

$ultimo_dia = date("t", mktime(0,0,0,$hoje2,'01',$ano_atual2));
$data_de = (!empty($_POST['data_de'])) ? $_POST['data_de'] : '01/'.sprintf('%02d', $hoje2).'/'.$ano_atual2;
$data_ate = (!empty($_POST['data_ate'])) ? $_POST['data_ate'] : $ultimo_dia.'/'.sprintf('%02d', $hoje2).'/'.$ano_atual2;
$ultimo_dia = date("t", mktime(0,0,0,$hoje,'01',$ano_atual));
$data_de_amostra = (!empty($_POST['data_de_amostra'])) ? $_POST['data_de_amostra'] : '01/'.sprintf('%02d', $hoje).'/'.$ano_atual2;
$data_ate_amostra = (!empty($_POST['data_ate_amostra'])) ? $_POST['data_ate_amostra'] : $hoje_amostra;
$tempo_abaixo = (!empty($_POST['tempo_abaixo'])) ? $_POST['tempo_abaixo'] : '0';
$aproveitamento = (!empty($_POST['aproveitamento'])) ? $_POST['aproveitamento'] : '100';
$flag_escalas = (!empty($_POST['flag_escalas'])) ? $_POST['flag_escalas'] : '';
$turno3 = (!empty($_POST['turno3'])) ? $_POST['turno3'] : '';
$dia_de_ontem = $hora_final_consulta = date('Y-m-d', strtotime("-1 days",strtotime($ano_atual."-".$hoje."-".$dia_de_hoje)));
$data_de_intervalo = (!empty($_POST['data_de_intervalo'])) ? $_POST['data_de_intervalo'] : '01/'.sprintf('%02d', $hoje).'/'.$ano_atual;
$data_ate_intervalo = (!empty($_POST['data_ate_intervalo'])) ? $_POST['data_ate_intervalo'] : converteData($dia_de_ontem);
$data_horario_escala = (!empty($_POST['data_horario_escala'])) ? $_POST['data_horario_escala'] : $dia_de_hoje.'/'.sprintf('%02d', $hoje).'/'.$ano_atual;
$qtd_atendimento_chat = (!empty($_POST['qtd_atendimento_chat'])) ? $_POST['qtd_atendimento_chat'] : 1;
$chat = (!empty($_POST['chat'])) ? $_POST['chat'] : '';

if ($gerar) {
    $collapse = '';
    $collapse_icon = 'plus';
} else {
    $collapse = 'in';
    $collapse_icon = 'minus';
}

if ($tipo_relatorio == 1) {
    $display_row_horarios1 = '';
    $display_row_folgas = '';
    $display_row_folgas2 = 'style="display:none;"';
    $display_row_operador = '';
    $display_row_operador2 = 'style="display:none;"';
    $display_row_periodo = '';
    $display_row_lider = '';
    $display_row_compara = 'style="display:none;"';
    $display_row_periodo2 = 'style="display:none;"';
    $display_row_periodo_amostra = 'style="display:none;"';
    $display_row_flag_escalas = 'style="display:none;"';
    $display_row_tempo_abaixo = 'style="display:none;"';
    $display_row_aproveitamento = 'style="display:none;"';
    $display_row_ponderado = 'style="display:none;"';
    $display_row_turno3 = 'style="display:none;"';
    $display_row_periodo3 = 'style="display:none;"';
    $display_row_horario_escala = 'style="display:none;"';
    $display_row_qtd_atendimento_chat = 'style="display:none;"';
    $display_row_contrato = 'style="display:none;"';
    $display_row_faz_chat = 'style="display:none;"';

} else if ($tipo_relatorio == 2) {
    $display_row_horarios1 = 'style="display:none;"';
    $display_row_folgas = 'style="display:none;"';
    $display_row_folgas2 = '';
    $display_row_operador = 'style="display:none;"';
    $display_row_operador2 = '';
    $display_row_periodo = 'style="display:none;"';
    $display_row_lider = 'style="display:none;"';
    $display_row_compara = 'style="display:none;"';
    $display_row_periodo2 = 'style="display:none;"';
    $display_row_periodo_amostra = 'style="display:none;"';
    $display_row_flag_escalas = 'style="display:none;"';
    $display_row_tempo_abaixo = 'style="display:none;"';
    $display_row_aproveitamento = 'style="display:none;"';
    $display_row_ponderado = 'style="display:none;"';
    $display_row_turno3 = 'style="display:none;"';
    $display_row_periodo3 = 'style="display:none;"';
    $display_row_horario_escala = 'style="display:none;"';
    $display_row_qtd_atendimento_chat = 'style="display:none;"';
    $display_row_contrato = 'style="display:none;"';
    $display_row_faz_chat = 'style="display:none;"';


} else if ($tipo_relatorio == 3) {
    $display_row_horarios1 = 'style="display:none;"';
    $display_row_folgas = 'style="display:none;"';
    $display_row_folgas2 = 'style="display:none;"';
    $display_row_operador = 'style="display:none;"';
    $display_row_operador2 = 'style="display:none;"';
    $display_row_periodo = '';
    $display_row_lider = '';
    $display_row_compara = 'style="display:none;"';
    $display_row_periodo2 = 'style="display:none;"';
    $display_row_periodo_amostra = 'style="display:none;"';
    $display_row_flag_escalas = 'style="display:none;"';
    $display_row_tempo_abaixo = 'style="display:none;"';
    $display_row_aproveitamento = 'style="display:none;"';
    $display_row_ponderado = 'style="display:none;"';
    $display_row_turno3 = 'style="display:none;"';
    $display_row_periodo3 = 'style="display:none;"';
    $display_row_horario_escala = 'style="display:none;"';
    $display_row_qtd_atendimento_chat = 'style="display:none;"';
    $display_row_contrato = 'style="display:none;"';
    $display_row_faz_chat = 'style="display:none;"';

} else if ($tipo_relatorio == 4) {
    $display_row_horarios1 = 'style="display:none;"';
    $display_row_folgas = 'style="display:none;"';
    $display_row_folgas2 = 'style="display:none;"';
    $display_row_operador = 'style="display:none;"';
    $display_row_operador2 = 'style="display:none;"';
    $display_row_periodo = 'style="display:none;"';
    $display_row_lider = 'style="display:none;"';
    $display_row_compara = '';
    $display_row_periodo2 = 'style="display:none;"';
    $display_row_periodo_amostra = 'style="display:none;"';
    $display_row_flag_escalas = '';
    $display_row_tempo_abaixo = 'style="display:none;"';
    $display_row_aproveitamento = 'style="display:none;"';
    $display_row_ponderado = 'style="display:none;"';
    $display_row_turno3 = 'style="display:none;"';
    $display_row_periodo3 = 'style="display:none;"';
    $display_row_horario_escala = 'style="display:none;"';
    $display_row_qtd_atendimento_chat = 'style="display:none;"';
    $display_row_contrato = 'style="display:none;"';
    $display_row_faz_chat = 'style="display:none;"';

} else if ($tipo_relatorio == 5) {
    $display_row_horarios1 = 'style="display:none;"';
    $display_row_folgas = 'style="display:none;"';
    $display_row_folgas2 = 'style="display:none;"';
    $display_row_operador = 'style="display:none;"';
    $display_row_operador2 = 'style="display:none;"';
    $display_row_periodo = 'style="display:none;"';
    $display_row_lider = 'style="display:none;"';
    $display_row_compara = 'style="display:none;"';
    $display_row_periodo2 = '';
    $display_row_periodo_amostra = 'style="display:none;"';
    $display_row_flag_escalas = 'style="display:none;"';
    $display_row_tempo_abaixo = 'style="display:none;"';
    $display_row_aproveitamento = 'style="display:none;"';
    $display_row_ponderado = 'style="display:none;"';
    $display_row_turno3 = '';
    $display_row_periodo3 = 'style="display:none;"';
    $display_row_horario_escala = 'style="display:none;"';
    $display_row_qtd_atendimento_chat = 'style="display:none;"';
    $display_row_contrato = 'style="display:none;"';
    $display_row_faz_chat = 'style="display:none;"';

} else if ($tipo_relatorio == 6) {
    $display_row_horarios1 = 'style="display:none;"';
    $display_row_folgas = 'style="display:none;"';
    $display_row_folgas2 = 'style="display:none;"';
    $display_row_operador = 'style="display:none;"';
    $display_row_operador2 = '';
    $display_row_periodo = 'style="display:none;"';
    $display_row_lider = 'style="display:none;"';
    $display_row_compara = 'style="display:none;"';
    $display_row_periodo2 = '';
    $display_row_periodo_amostra = 'style="display:none;"';
    $display_row_flag_escalas = 'style="display:none;"';
    $display_row_tempo_abaixo = 'style="display:none;"';
    $display_row_aproveitamento = 'style="display:none;"';
    $display_row_ponderado = 'style="display:none;"';
    $display_row_turno3 = 'style="display:none;"';
    $display_row_periodo3 = 'style="display:none;"';
    $display_row_horario_escala = 'style="display:none;"';
    $display_row_qtd_atendimento_chat = 'style="display:none;"';
    $display_row_contrato = 'style="display:none;"';
    $display_row_faz_chat = 'style="display:none;"';

} else if ($tipo_relatorio == 7) {
    $display_row_horarios1 = 'style="display:none;"';
    $display_row_folgas = 'style="display:none;"';
    $display_row_folgas2 = 'style="display:none;"';
    $display_row_operador = 'style="display:none;"';
    $display_row_operador2 = 'style="display:none;"';
    $display_row_periodo = 'style="display:none;"';
    $display_row_lider = 'style="display:none;"';
    $display_row_compara = 'style="display:none;"';
    $display_row_periodo2 = '';
    $display_row_periodo_amostra = '';
    $display_row_flag_escalas = 'style="display:none;"';
    $display_row_tempo_abaixo = '';
    $display_row_aproveitamento = '';
    $display_row_ponderado = '';
    $display_row_turno3 = 'style="display:none;"';
    $display_row_periodo3 = 'style="display:none;"';
    $display_row_horario_escala = 'style="display:none;"';
    $display_row_qtd_atendimento_chat = 'style="display:none;"';
    $display_row_contrato = 'style="display:none;"';
    $display_row_faz_chat = 'style="display:none;"';

} else if ($tipo_relatorio == 8) {
    $display_row_horarios1 = 'style="display:none;"';
    $display_row_folgas = 'style="display:none;"';
    $display_row_folgas2 = 'style="display:none;"';
    $display_row_operador = 'style="display:none;"';
    $display_row_operador2 = '';
    $display_row_periodo = 'style="display:none;"';
    $display_row_lider = 'style="display:none;"';
    $display_row_compara = 'style="display:none;"';
    $display_row_periodo2 = 'style="display:none;"';
    $display_row_periodo_amostra = 'style="display:none;"';
    $display_row_flag_escalas = 'style="display:none;"';
    $display_row_tempo_abaixo = 'style="display:none;"';
    $display_row_aproveitamento = 'style="display:none;"';
    $display_row_ponderado = 'style="display:none;"';
    $display_row_turno3 = 'style="display:none;"';
    $display_row_periodo3 = '';
    $display_row_horario_escala = 'style="display:none;"';
    $display_row_qtd_atendimento_chat = 'style="display:none;"';
    $display_row_contrato = 'style="display:none;"';
    $display_row_faz_chat = 'style="display:none;"';

} else if ($tipo_relatorio == 9) {
    $display_row_horarios1 = 'style="display:none;"';
    $display_row_folgas = 'style="display:none;"';
    $display_row_folgas2 = 'style="display:none;"';
    $display_row_operador = 'style="display:none;"';
    $display_row_operador2 = 'style="display:none;"';
    $display_row_periodo = 'style="display:none;"';
    $display_row_lider = 'style="display:none;"';
    $display_row_compara = 'style="display:none;"';
    $display_row_periodo2 = 'style="display:none;"';
    $display_row_periodo_amostra = 'style="display:none;"';
    $display_row_flag_escalas = 'style="display:none;"';
    $display_row_tempo_abaixo = 'style="display:none;"';
    $display_row_aproveitamento = 'style="display:none;"';
    $display_row_ponderado = 'style="display:none;"';
    $display_row_turno3 = 'style="display:none;"';
    $display_row_periodo3 = '';
    $display_row_horario_escala = 'style="display:none;"';
    $display_row_qtd_atendimento_chat = 'style="display:none;"';
    $display_row_contrato = 'style="display:none;"';
    $display_row_faz_chat = 'style="display:none;"';

} else if ($tipo_relatorio == 10) {
    $display_row_horarios1 = 'style="display:none;"';
    $display_row_folgas = 'style="display:none;"';
    $display_row_folgas2 = 'style="display:none;"';
    $display_row_operador = 'style="display:none;"';
    $display_row_operador2 = 'style="display:none;"';
    $display_row_periodo = 'style="display:none;"';
    $display_row_lider = 'style="display:none;"';
    $display_row_compara = 'style="display:none;"';
    $display_row_periodo2 = 'style="display:none;"';
    $display_row_periodo_amostra = 'style="display:none;"';
    $display_row_flag_escalas = 'style="display:none;"';
    $display_row_tempo_abaixo = 'style="display:none;"';
    $display_row_aproveitamento = 'style="display:none;"';
    $display_row_ponderado = 'style="display:none;"';
    $display_row_turno3 = 'style="display:none;"';
    $display_row_periodo3 = 'style="display:none;"';
    $display_row_horario_escala = '';
    $display_row_qtd_atendimento_chat = 'style="display:none;"';
    $display_row_contrato = 'style="display:none;"';
    $display_row_faz_chat = '';

} else if ($tipo_relatorio == 11) {
    $display_row_horarios1 = 'style="display:none;"';
    $display_row_folgas = 'style="display:none;"';
    $display_row_folgas2 = 'style="display:none;"';
    $display_row_operador = 'style="display:none;"';
    $display_row_operador2 = 'style="display:none;"';
    $display_row_periodo = 'style="display:none;"';
    $display_row_lider = 'style="display:none;"';
    $display_row_compara = 'style="display:none;"';
    $display_row_periodo2 = '';
    $display_row_periodo_amostra = 'style="display:none;"';
    $display_row_flag_escalas = 'style="display:none;"';
    $display_row_tempo_abaixo = 'style="display:none;"';
    $display_row_aproveitamento = 'style="display:none;"';
    $display_row_ponderado = 'style="display:none;"';
    $display_row_turno3 = '';
    $display_row_periodo3 = 'style="display:none;"';
    $display_row_horario_escala = 'style="display:none;"';
    $display_row_qtd_atendimento_chat = 'style="display:none;"';
    $display_row_contrato = 'style="display:none;"';
    $display_row_faz_chat = 'style="display:none;"';

} else if ($tipo_relatorio == 12) {
    $display_row_horarios1 = 'style="display:none;"';
    $display_row_folgas = 'style="display:none;"';
    $display_row_folgas2 = 'style="display:none;"';
    $display_row_operador = 'style="display:none;"';
    $display_row_operador2 = 'style="display:none;"';
    $display_row_periodo = 'style="display:none;"';
    $display_row_lider = 'style="display:none;"';
    $display_row_compara = 'style="display:none;"';
    $display_row_periodo2 = '';
    $display_row_periodo_amostra = '';
    $display_row_flag_escalas = 'style="display:none;"';
    $display_row_tempo_abaixo = '';
    $display_row_aproveitamento = '';
    $display_row_ponderado = '';
    $display_row_turno3 = 'style="display:none;"';
    $display_row_periodo3 = 'style="display:none;"';
    $display_row_horario_escala = 'style="display:none;"';
    $display_row_qtd_atendimento_chat = 'style="display:none;"';
    $display_row_contrato = 'style="display:none;"';
    $display_row_faz_chat = 'style="display:none;"';

} else if ($tipo_relatorio == 13) {
    $display_row_horarios1 = 'style="display:none;"';
    $display_row_folgas = 'style="display:none;"';
    $display_row_folgas2 = 'style="display:none;"';
    $display_row_operador = 'style="display:none;"';
    $display_row_operador2 = 'style="display:none;"';
    $display_row_periodo = 'style="display:none;"';
    $display_row_lider = 'style="display:none;"';
    $display_row_compara = 'style="display:none;"';
    $display_row_periodo2 = '';
    $display_row_periodo_amostra = '';
    $display_row_flag_escalas = 'style="display:none;"';
    $display_row_tempo_abaixo = '';
    $display_row_aproveitamento = '';
    $display_row_ponderado = 'style="display:none;"';
    $display_row_turno3 = 'style="display:none;"';
    $display_row_periodo3 = 'style="display:none;"';
    $display_row_horario_escala = 'style="display:none;"';
    $display_row_qtd_atendimento_chat = 'style="display:none;"';
    $display_row_contrato = 'style="display:none;"';
    $display_row_faz_chat = 'style="display:none;"';

} else if ($tipo_relatorio == 14) {
    $display_row_horarios1 = 'style="display:none;"';
    $display_row_folgas = 'style="display:none;"';
    $display_row_folgas2 = 'style="display:none;"';
    $display_row_operador = 'style="display:none;"';
    $display_row_operador2 = 'style="display:none;"';
    $display_row_periodo = 'style="display:none;"';
    $display_row_lider = 'style="display:none;"';
    $display_row_compara = 'style="display:none;"';
    $display_row_periodo2 = '';
    $display_row_periodo_amostra = '';
    $display_row_flag_escalas = 'style="display:none;"';
    $display_row_tempo_abaixo = 'style="display:none;"';
    $display_row_aproveitamento = 'style="display:none;"';
    $display_row_ponderado = 'style="display:none;"';
    $display_row_turno3 = 'style="display:none;"';
    $display_row_periodo3 = 'style="display:none;"';
    $display_row_horario_escala = 'style="display:none;"';
    $display_row_qtd_atendimento_chat = '';
    $display_row_contrato = 'style="display:none;"';
    $display_row_faz_chat = 'style="display:none;"';

} else if ($tipo_relatorio == 15) {
    $display_row_horarios1 = 'style="display:none;"';
    $display_row_folgas = 'style="display:none;"';
    $display_row_folgas2 = 'style="display:none;"';
    $display_row_operador = 'style="display:none;"';
    $display_row_operador2 = 'style="display:none;"';
    $display_row_periodo = 'style="display:none;"';
    $display_row_lider = 'style="display:none;"';
    $display_row_compara = 'style="display:none;"';
    $display_row_periodo2 = 'style="display:none;"';
    $display_row_periodo_amostra = 'style="display:none;"';
    $display_row_flag_escalas = 'style="display:none;"';
    $display_row_tempo_abaixo = 'style="display:none;"';
    $display_row_aproveitamento = 'style="display:none;"';
    $display_row_ponderado = 'style="display:none;"';
    $display_row_turno3 = 'style="display:none;"';
    $display_row_periodo3 = 'style="display:none;"';
    $display_row_horario_escala = 'style="display:none;"';
    $display_row_qtd_atendimento_chat = 'style="display:none;"';
    $display_row_contrato = '';
    $display_row_faz_chat = 'style="display:none;"';

} else if ($tipo_relatorio == 16) {
    $display_row_horarios1 = 'style="display:none;"';
    $display_row_folgas = 'style="display:none;"';
    $display_row_folgas2 = 'style="display:none;"';
    $display_row_operador = 'style="display:none;"';
    $display_row_operador2 = 'style="display:none;"';
    $display_row_periodo = 'style="display:none;"';
    $display_row_lider = 'style="display:none;"';
    $display_row_compara = 'style="display:none;"';
    $display_row_periodo2 = 'style="display:none;"';
    $display_row_periodo_amostra = 'style="display:none;"';
    $display_row_flag_escalas = 'style="display:none;"';
    $display_row_tempo_abaixo = 'style="display:none;"';
    $display_row_aproveitamento = 'style="display:none;"';
    $display_row_ponderado = 'style="display:none;"';
    $display_row_turno3 = 'style="display:none;"';
    $display_row_periodo3 = 'style="display:none;"';
    $display_row_horario_escala = '';
    $display_row_qtd_atendimento_chat = 'style="display:none;"';
    $display_row_contrato = 'style="display:none;"';
    $display_row_faz_chat = 'style="display:none;"';

} else if ($tipo_relatorio == 17) {
    $display_row_horarios1 = 'style="display:none;"';
    $display_row_folgas = 'style="display:none;"';
    $display_row_folgas2 = 'style="display:none;"';
    $display_row_operador = 'style="display:none;"';
    $display_row_operador2 = 'style="display:none;"';
    $display_row_periodo = 'style="display:none;"';
    $display_row_lider = 'style="display:none;"';
    $display_row_compara = 'style="display:none;"';
    $display_row_periodo2 = 'style="display:none;"';
    $display_row_periodo_amostra = 'style="display:none;"';
    $display_row_flag_escalas = 'style="display:none;"';
    $display_row_tempo_abaixo = 'style="display:none;"';
    $display_row_aproveitamento = 'style="display:none;"';
    $display_row_ponderado = 'style="display:none;"';
    $display_row_turno3 = 'style="display:none;"';
    $display_row_periodo3 = 'style="display:none;"';
    $display_row_horario_escala = 'style="display:none;"';
    $display_row_qtd_atendimento_chat = 'style="display:none;"';
    $display_row_contrato = 'style="display:none;"';
    $display_row_faz_chat = 'style="display:none;"';
}

?>
<style>
    @media print {
        @page {size: landscape}
        .noprint { display:none; }
        body {
            font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
            padding-top: 0;
        }
        .highcharts-root {
            max-width: 1050px !important; 
            height: 400px; 
            margin: 0 auto;
        }
        text{
            font-size:17px !important;
        }
       .highcharts-title{
            font-size:25px !important;
        }
        g.highcharts-legend text{

            font-size:13px !important;
        }   
    }
</style>

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs/jszip-2.5.0/dt-1.10.18/b-1.5.4/b-colvis-1.5.4/b-flash-1.5.4/b-html5-1.5.4/b-print-1.5.4/r-2.2.2/datatables.min.css"/> 
<script type="text/javascript" src="https://cdn.datatables.net/v/bs/jszip-2.5.0/dt-1.10.18/b-1.5.4/b-colvis-1.5.4/b-flash-1.5.4/b-html5-1.5.4/b-print-1.5.4/r-2.2.2/datatables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/plug-ins/1.10.19/sorting/time.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/plug-ins/1.10.19/sorting/chinese-string.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/plug-ins/1.10.19/sorting/date-eu.js"></script>
<script src="https://code.highcharts.com/7.2.1/highcharts.js"></script>
<script src="https://code.highcharts.com/7.2.1/modules/exporting.js"></script>
<script src="https://code.highcharts.com/7.2.1/modules/export-data.js"></script>
<?php

$horarios = array(
    "0" => "00:00",
    "1" => "01:00",
    "2" => "02:00",
    "3" => "03:00",
    "4" => "04:00",
    "5" => "05:00",
    "6" => "06:00",
    "7" => "07:00",
    "8" => "08:00",
    "9" => "09:00",
    "10" => "10:00",
    "11" => "11:00",
    "12" => "12:00",
    "13" => "13:00",
    "14" => "14:00",
    "15" => "15:00",
    "16" => "16:00",
    "17" => "17:00",
    "18" => "18:00",
    "19" => "19:00",
    "20" => "20:00",
    "21" => "21:00",
    "22" => "22:00",
    "23" => "23:00"
);

?>

<div class="container-fluid">
    <form method="post" action="">
        <div class="row">
            <div class="col-md-4 col-md-offset-4">
                <div class="panel panel-default noprint">
                    <div class="panel-heading clearfix">
                        <h3 class="panel-title text-left pull-left"
                            style="margin-top: 2px;">Relatório de Escalas:</h3>
                        <div class="panel-title text-right pull-right">
                            <button data-toggle="collapse" data-target="#accordionRelatorio"
                                class="btn btn-xs btn-info" type="button"
                                title="Visualizar filtros">
                                <i id="i_collapse" class="fa fa-<?=$collapse_icon?>"></i>
                            </button>
                        </div>
                    </div>
                    <div id="accordionRelatorio"
                        class="panel-collapse collapse <?=$collapse?>">
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Tipo de Relatório:</label> <select
                                            name="tipo_relatorio" id="tipo_relatorio" class="form-control input-sm">
                                            <option value="4" <?php if($tipo_relatorio == '4'){echo 'selected';}?>>Comparar</option>
                                            <option value="3" <?php if($tipo_relatorio == '3'){echo 'selected';}?>>Confirmações</option>
                                            <option value="8" <?php if($tipo_relatorio == '8'){echo 'selected';}?>>Controle de Intervalos</option>
                                            <option value="2" <?php if($tipo_relatorio == '2'){echo 'selected';}?>>Disponibilidade</option>
                                            <option value="10" <?php if($tipo_relatorio == '10'){echo 'selected';}?>>Escalas/Horários</option>
                                            <option value="6" <?php if($tipo_relatorio == '6'){echo 'selected';}?>>Férias e afastamentos</option>
                                            <option value="5" <?php if($tipo_relatorio == '5'){echo 'selected';}?>>Gráfico de Atendentes por Hora - Separado por Dia do Mês</option>
                                            <option value="11" <?php if($tipo_relatorio == '11'){echo 'selected';}?>>Gráfico de Atendentes por 20min - Separado por Dia do Mês</option>
                                            <option value="7" <?php if($tipo_relatorio == '7'){echo 'selected';}?>>Gráfico de Capacidade por Hora</option>
                                            <option value="12" <?php if($tipo_relatorio == '12'){echo 'selected';}?>>Gráfico de Capacidade por 20min</option>      
                                            <!-- <option value="17" <?php if($tipo_relatorio == '17'){echo 'selected';}?>>Grupos de Chat</option> -->
                                            <!-- <option value="18" <?php if($tipo_relatorio == '18'){echo 'selected';}?>>Grupos de Chat - Histórico</option> -->
                                            <option value="1" <?php if($tipo_relatorio == '1'){echo 'selected';}?>>Horários</option>
                                            <option value="9" <?php if($tipo_relatorio == '9'){echo 'selected';}?>>Horários Especiais</option>
                                            <option value="15" <?php if($tipo_relatorio == '15'){echo 'selected';}?>>Horários de Chat</option>
                                            <option value="13" <?php if($tipo_relatorio == '13'){echo 'selected';}?>>Previsões</option>

                                            <!-- <option value="14" <?php if($tipo_relatorio == '14'){echo 'selected';}?>>Gráfico de Capacidade por Hora - Chat</option> -->

                                            <option value="16" <?php if($tipo_relatorio == '16'){echo 'selected';}?>>Tabelão de chat</option>

                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row" id="row_contrato" <?= $display_row_contrato ?>>
								<div class="col-md-12">
									<div class="form-group">
										<label>Contrato (cliente):</label>
										<div class="input-group">
											<input class="form-control input-sm" id="busca_contrato" type="text" name="busca_contrato" value="<?= $contrato ?>" placeholder="Informe o nome ou CNPJ..." autocomplete="off" readonly />
											<div class="input-group-btn">
												<button class="btn btn-info btn-sm" id="habilita_busca_contrato" name="habilita_busca_contrato" type="button" title="Clique para selecionar o contrato" style="height: 30px;"><i class="fa fa-search"></i></button>
											</div>
										</div>
										<input type="hidden" name="id_contrato_plano_pessoa" id="id_contrato_plano_pessoa" value="<?= $id_contrato_plano_pessoa; ?>" />
									</div>
								</div>
							</div>

                            <div class="row" id="row_periodo" <?=$display_row_periodo?>>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Mês:</label> 
                                        <select name="var_mes" id="var_mes" class="form-control input-sm">
                                                <?php
                                                if($var_mes){
                                                    $selecao_atual = $var_mes;
                                                }else{
                                                    $selecao_atual = $mes_atual;
                                                }
                                                $meses = array(
                                                    "01" => "Janeiro",
                                                    "02" => "Fevereiro",
                                                    "03" => "Março",
                                                    "04" => "Abril",
                                                    "05" => "Maio",
                                                    "06" => "Junho",
                                                    "07" => "Julho",
                                                    "08" => "Agosto",
                                                    "09" => "Setembro",
                                                    "10" => "Outubro",
                                                    "11" => "Novembro",
                                                    "12" => "Dezembro",
                                                );

                                                foreach ($meses as $nume => $mes) {
                                                    $selected = $selecao_atual == $nume ? "selected" : "";
                                                    echo "<option value='".sprintf('%02d', $nume)."' ".$selected.">".$mes."</option>";
                                                }
                                                ?>                                                  
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Ano:</label> 
                                        <select name="var_ano" id="var_ano" class="form-control input-sm">
                                            <?php
                                            $anos = array(
                                                "2015" => "15",
                                                "2016" => "16",
                                                "2017" => "17",
                                                "2018" => "18",
                                                "2019" => "19",
                                                "2020" => "20",
                                                "2021" => "21",
                                                "2022" => "22",
                                                "2023" => "23",
                                                "2024" => "24",
                                                "2025" => "25",
                                            );

                                            foreach ($anos as $num => $ano) { 
                                                $selected = $var_ano == $num ? "selected" : "";
                                                echo "<option value='".$num."' ".$selected.">".$num."</option>";
                                            }
                                            ?>                                                  
                                    </select>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row" id="row_folgas" <?=$display_row_folgas?>>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Dia de folga:</label> 
                                        <select name="dia_folgas" id="dia_folgas" class="form-control input-sm">
                                            <?php
                                            $dias = array(
                                                "" => "Todos",
                                                "1" => "Segunda",
                                                "2" => "Terça",
                                                "3" => "Quarta",
                                                "5" => "Quinta",
                                                "6" => "Sexta",
                                                "7" => "Sábado",
                                            );

                                            foreach ($dias as $num => $dia) { 
                                                $selected = $dia_folgas == $dia ? "selected" : "";
                                                echo "<option value='".$dia."'".$selected.">".$dia."</option>";
                                            }
                                            ?>                                                  
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Domingo de folga:</label> 
                                        <input class="campos-agendar form-control date calendar input-sm" name="dia_folga_domingo" value="<?=$dia_folga_domingo?>" type="text" autocomplete="off"/>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Turno/Horário:</label> 
                                        <select name="turno" id="turno" class="form-control input-sm">
                                            <?php
                                            $turnos = array(
                                                "0" => "Todos",
                                                "1" => "Integral",
                                                "2" => "Meio Turno",
                                                "3" => "Jovem Aprendiz",
                                                "4" => "Estágio"
                                            );
                                            foreach ($turnos as $num => $tur) { 
                                                $selected = $turno == $num ? "selected" : "";
                                                echo "<option value='".$num."'".$selected.">".$tur."</option>";
                                            }
                                            ?>      
                                        </select>                                   
                                    </div>
                                </div>                              
                            </div>

                            <div class="row" id="row_folgas2" <?=$display_row_folgas2?>>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Dia de folga:</label> 
                                        <select name="folga" id="folga" class="form-control input-sm">
                                            <?php
                                            $dias = array(
                                                "" => "Todos",
                                                "1" => "Segunda",
                                                "2" => "Terça",
                                                "3" => "Quarta",
                                                "5" => "Quinta",
                                                "6" => "Sexta",
                                                "7" => "Sábado",
                                            );

                                            foreach ($dias as $num => $dia) { 
                                                $selected = $folga == $dia ? "selected" : "";
                                                echo "<option value='".$dia."' ".$selected.">".$dia."</option>";
                                            }
                                            ?>                                                  
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Domingo de folga:</label> 
                                        <select name="folga_domingo" id="folga_domingo" class="form-control input-sm">
                                            <?php
                                            $domingos = array(
                                                "0" => "Todos",
                                                "1" => "Primeiro",
                                                "2" => "Segundo",
                                                "3" => "Terceiro",
                                                "4" => "Quarto",
                                                "5" => "Quinto"
                                            );
                                            foreach ($domingos as $numero => $domingo) { 
                                                $selected = $folga_domingo == $numero ? "selected" : "";
                                                echo "<option value='".$numero."' ".$selected.">".$domingo."</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Turno/Horário:</label> 
                                        <select name="turno2" id="turno2" class="form-control input-sm">
                                            <?php
                                            $turnos2 = array(
                                                "0" => "Todos",
                                                "1" => "Integral",
                                                "2" => "Meio Turno",
                                                "3" => "Jovem Aprendiz",
                                                "4" => "Estágio"
                                            );
                                            foreach ($turnos2 as $num => $tur) { 
                                                $selected = $turno2 == $num ? "selected" : "";
                                                echo "<option value='".$num."'".$selected.">".$tur."</option>";
                                            }
                                            ?>      
                                            
                                        </select>                                   
                                    </div>
                                </div>                              
                            </div>

                            <div class="row" id="row_horarios1" <?=$display_row_horarios1?>>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Horário Inicial:</label> 
                                            <?php
                                                echo "<select class='form-control input-sm' name='horario_inicial' id='horario_inicial'>";
                                                    echo "<option value = ''>Qualquer</option>";

                                                    foreach ($horarios as $key => $horario) { 
                                                        $selected = $horario_inicial == $key ? "selected" : "";                                                    
                                                        echo "<option value = '".$key."'".$selected.">".$horario."</option>";

                                                    }
                                                ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Horário Final:</label> 
                                            <?php
                                                echo "<select class='form-control input-sm' name='horario_final' id='horario_final'>";
                                                    echo "<option value = ''>Qualquer</option>";
                                                    foreach ($horarios as $key => $horario) { 
                                                        $selected = $horario_final == $key ? "selected" : "";
                                                        echo "<option value = '".$key."'".$selected.">".$horario."</option>";

                                                    }
                                                ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Dia:</label> 
                                            <?php
                                                $expedien = array(
                                                "0" => "Todos",
                                                "1" => "Seg. a Sex.",
                                                "2" => "Sábado",
                                                "3" => "Domingo"
                                            );
                                                echo "<select class='form-control input-sm' name='expediente' id='expediente'>";
                                                    foreach ($expedien as $key => $exp) { 
                                                        $selected = $expediente == $key ? "selected" : "";
                                                        echo "<option value = '".$key."'".$selected.">".$exp."</option>";
                                                    }
                                                ?>
                                        </select>
                                    </div>
                                </div>                                  
                            </div>

                            <div class="row" id="row_operador" <?=$display_row_operador?>>
                                <?php if($perfil_sistema != '3'){ ?>
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label for="">Atendente:</label>
                                        <select name="usuario2" class="form-control input-sm">
                                            <option value="">Todos</option>
                                                <?php
                                                $dados_usuarios = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE id_perfil_sistema = '3' ORDER BY b.nome ASC", "a.id_usuario, b.nome");
                                                if ($dados_usuarios) {
                                                    foreach ($dados_usuarios as $conteudo_usuarios) { 
                                                        $selected = $usuario2 == $conteudo_usuarios['id_usuario'] ? "selected" : "";
                                                        echo "<option value='" . $conteudo_usuarios['id_usuario'] . "' ".$selected.">" . $conteudo_usuarios['nome'] . "</option>";
                                                    }
                                                }
                                                ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Status:</label> 
                                        <select name="status" id="status" class="form-control input-sm">
                                            <option value="">Qualquer</option>>
                                            <option value="1" <?php if($status == '1'){echo 'selected';}?>>Ciente</option>
                                            <option value="2" <?php if($status == '2'){echo 'selected';}?>>Não Ciente</option>
                                        </select>
                                    </div>
                                </div>
                                <?php } ?>
                            </div>

                            <div class="row" id="row_periodo_amostra" <?=$display_row_periodo_amostra?>>
                                <div class="col-md-6">
                                    <div class="form-group" >
                                        <label>*Data Inicial da Amostra de Chamadas:</label>
                                        <input type="text" class="form-control date calendar input-sm" name="data_de_amostra" value="<?=$data_de_amostra?>" id="data_de_amostra">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>*Data Final da Amostra de Chamadas:</label>
                                        <input type="text" class="form-control date calendar input-sm"  name="data_ate_amostra" value="<?=$data_ate_amostra?>" id="data_ate_amostra">
                                    </div>
                                </div>
                            </div>

                            <div class="row" id="row_periodo2" <?=$display_row_periodo2?>>
                                <div class="col-md-6">
                                    <div class="form-group" >
                                        <label>*Data Inicial da Escala:</label>
                                        <input type="text" class="form-control date calendar input-sm" name="data_de" value="<?=$data_de?>" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>*Data Final da Escala:</label>
                                        <input type="text" class="form-control date calendar input-sm" name="data_ate" value="<?=$data_ate?>" required>
                                    </div>
                                </div>
                            </div>

                            <div class="row" id="row_periodo3" <?=$display_row_periodo3?>>
                                <div class="col-md-6">
                                    <div class="form-group" >
                                        <label>*Data Inicial:</label>
                                        <input type="text" class="form-control date calendar input-sm" name="data_de_intervalo" value="<?=$data_de_intervalo?>" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>*Data Final:</label>
                                        <input type="text" class="form-control date calendar input-sm" name="data_ate_intervalo" value="<?=$data_ate_intervalo?>" required>
                                    </div>
                                </div>
                            </div>

                            <div class="row" id="row_turno3" <?=$display_row_turno3?>>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Carga Horária:</label> 
                                        <select name="turno3" id="turno3" class="form-control input-sm">
                                            <option value="">Todas</option>
                                            <option value="1" <?php if($turno3 == '1'){echo 'selected';}?>>Intergral</option>
                                            <option value="2" <?php if($turno3 == '2'){echo 'selected';}?>>Meio Turno</option>
                                            <option value="3" <?php if($turno3 == '3'){echo 'selected';}?>>Jovem Aprendiz</option>
                                            <option value="4" <?php if($turno3 == '4'){echo 'selected';}?>>Estágio</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row" id="row_tempo_abaixo" <?=$display_row_tempo_abaixo?>>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="">Descartar tempo de espera das perdidas abaixo de (segundos):</label><br>
                                        <input type="number" class="form-control input-sm" name="tempo_abaixo" value="<?=$tempo_abaixo?>">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row" id="row_aproveitamento" <?=$display_row_aproveitamento?>>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Aproveitamento (Porcentagem %):</label>
                                        <input type="number" type="range" max="100" class="form-control input-sm" name="aproveitamento" value="<?=$aproveitamento?>">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row" id="row_ponderado" <?=$display_row_ponderado?>>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="">Relatório Ponderado:</label>
                                        <select name="ponderado" id="ponderado" class="form-control input-sm">
                                            <option value=""  <?php if($ponderado != '1'){echo 'selected';}?>>Não</option>
                                            <option value="1" <?php if($ponderado == '1'){echo 'selected';}?>>Sim</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row" id="row_operador2" <?=$display_row_operador2?>>
                                <?php if($perfil_sistema != '3'){ ?>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="">Atendente:</label>
                                        <select name="usuario" class="form-control input-sm">
                                            <option value="">Todos</option>
                                                <?php
                                                $dados_usuarios = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE id_perfil_sistema = '3' ORDER BY b.nome ASC", "a.id_usuario, b.nome");
                                                if ($dados_usuarios) {
                                                    foreach ($dados_usuarios as $conteudo_usuarios) { 
                                                        $selected = $usuario == $conteudo_usuarios['id_usuario'] ? "selected" : "";
                                                        echo "<option value='" . $conteudo_usuarios['id_usuario'] . "' ".$selected.">" . $conteudo_usuarios['nome'] . "</option>";
                                                    }
                                                }
                                                ?>
                                        </select>
                                    </div>
                                </div>
                                <?php } ?>      
                            </div>
                                            
                            <div class="row" id="row_lider" <?=$display_row_lider?>>
                                <?php if($perfil_sistema != '3'){ ?>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="">Líder Direto:</label>
                                            <select name="lider" class="form-control input-sm">
                                                <option value="">Todos</option>
                                                    <?php
                                                    $dados_lider = DBRead('', 'tb_usuario a', "INNER JOIN tb_usuario b ON a.lider_direto = b.id_usuario INNER JOIN tb_pessoa c ON b.id_pessoa = c.id_pessoa where a.lider_direto GROUP BY a.lider_direto, c.nome ORDER BY c.nome ASC", "a.lider_direto, c.nome");

                                                    if ($dados_lider) {
                                                        foreach ($dados_lider as $conteudo_lider) { 
                                                            $selected = $lider == $conteudo_lider['lider_direto'] ? "selected" : "";
                                                            echo "<option value='" . $conteudo_lider['lider_direto'] . "' ".$selected.">" . $conteudo_lider['nome'] . "</option>";
                                                        }
                                                    }
                                                    ?>
                                            </select>
                                        </div>
                                    </div>
                                <?php } ?>  
                            </div>
                                                        
                            <div class="row" id="row_compara" <?=$display_row_compara?>>
                                <?php if($perfil_sistema != '3'){ ?>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Escala 1:</label>
                                        <select name="mes_inicio" class="form-control input-sm" id="mes_inicio">
                                            <?php
                                            $dados_mes_inicio = DBRead('', 'tb_horarios_escala', "GROUP BY data_inicial ORDER BY data_inicial ASC", "data_inicial");
                                            if ($dados_mes_inicio) {
                                                foreach ($dados_mes_inicio as $conteudo_mes_inicio) { 
                                                    $selected = $mes_inicio == $conteudo_mes_inicio['data_inicial'] ? "selected" : "";
                                                    echo "<option value='" . $conteudo_mes_inicio['data_inicial'] . "' ".$selected.">" . converteData($conteudo_mes_inicio['data_inicial']) . "</option>";
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Escala 2:</label> 
                                        <select name="mes_fim" class="form-control input-sm" id="mes_fim">
                                            <?php
                                            $dados_mes_fim = DBRead('', 'tb_horarios_escala', "GROUP BY data_inicial ORDER BY data_inicial ASC", "data_inicial");
                                            if ($dados_mes_fim) {
                                                foreach ($dados_mes_fim as $conteudo_mes_fim) { 
                                                    $selected = $mes_fim == $conteudo_mes_fim['data_inicial'] ? "selected" : "";
                                                    echo "<option value='" . $conteudo_mes_fim['data_inicial'] . "' ".$selected.">" . converteData($conteudo_mes_fim['data_inicial']) . "</option>";
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <?php } ?>  
                            </div>
                            
                            <div class="row" id="row_flag_escalas" <?=$display_row_flag_escalas?>>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="">Mostrar escala 1</label>
                                        <select name="flag_escalas" class="form-control input-sm" id="flag_escalas">
                                            <option value="" <?php if($flag_escalas == ''){echo 'selected';}?>>Sim</option>
                                            <option value="1" <?php if($flag_escalas == '1'){echo 'selected';}?>>Não</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row" id="row_horario_escala" <?=$display_row_horario_escala?>>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>*Data:</label>
                                            <input type="text" class="form-control date calendar input-sm" name="data_horario_escala" value="<?=$data_horario_escala?>" required>
                                    </div>
                                </div>
                            </div>

                            <div class="row" id="row_qtd_atendimento_chat" <?=$display_row_qtd_atendimento_chat?>>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Quantidade de atendimentos cada atendente faz por hora:</label>
                                        <input type="number" class="form-control input-sm" name="qtd_atendimento_chat" value="<?=$qtd_atendimento_chat?>">
                                    </div>
                                </div>
                            </div>

                            <div class="row" id="row_faz_chat" <?=$display_row_faz_chat?>>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Mostrar Somente Atendentes de Chat</label>
                                        <select name="chat" class="form-control input-sm" id="chat">
                                            <option value="" <?php if($chat == ''){echo 'selected';}?>>Não</option>
                                            <option value="1" <?php if($chat == '1'){echo 'selected';}?>>Sim</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="panel-footer">
                        <div class="row">
                            <div id="panel_buttons" class="col-md-12" style="text-align: center">
                                <button class="btn btn-primary" name="gerar" id="gerar" value="1" type="submit">
                                    <i class="fa fa-refresh"></i> Gerar
                                </button>
                                <button class="btn btn-warning" name="imprimir" type="button" onclick="window.print();">
                                    <i class="fa fa-print"></i> Imprimir
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <div class="row" id="resultado">

    <?php
        if ($gerar) {
            if ($perfil_sistema == '3') {
                $usuario = $id_usuario;
            } 
            if ($tipo_relatorio == 1) {
                relatorio_horarios($var_mes, $var_ano, $usuario2, $dia_folgas, $dia_folga_domingo, $horario_inicial, $horario_final, $turno, $expediente, $status, $lider);

            } else if ($tipo_relatorio == 2) {
                relatorio_disponibilidade($usuario, $folga, $folga_domingo, $turno2);
                
            } else if ($tipo_relatorio == 3) {
                relatorio_confirmacao($var_mes, $var_ano, $lider);

            } else if ($tipo_relatorio == 4) {
                relatorio_comparar($mes_inicio, $mes_fim, $flag_escalas);

            } else if ($tipo_relatorio == 5 && $perfil_sistema != '3'){
                relatorio_grafico_atendentes_hora($data_de, $data_ate, $turno3);

            } else if ($tipo_relatorio == 6){
                relatorio_ferias($data_de, $data_ate, $usuario);

            } else if ($tipo_relatorio == 7 && $perfil_usuario != '3'){
                relatorio_tendencia($data_de, $data_ate, $data_de_amostra, $data_ate_amostra, $aproveitamento, $tempo_abaixo, $ponderado);

            } else if ($tipo_relatorio == 8 && $perfil_usuario != '3'){
                relatorio_intervalo($usuario, $data_de_intervalo, $data_ate_intervalo);

            } else if ($tipo_relatorio == 9 && $perfil_usuario != '3'){
                relatorio_horarios_especiais($data_de_intervalo, $data_ate_intervalo);

            } else if ($tipo_relatorio == 10 && $perfil_usuario != '3'){
                relatorio_escala_horarios($data_horario_escala, $chat);

            } else if ($tipo_relatorio == 11){
                relatorio_grafico_atendentes_20min($data_de, $data_ate, $turno3);

            } else if ($tipo_relatorio == 12){
                relatorio_tendencia_20min($data_de, $data_ate, $data_de_amostra, $data_ate_amostra, $aproveitamento, $tempo_abaixo, $ponderado);

            } else if ($tipo_relatorio == 13){
                relatorio_previsoes($data_de, $data_ate, $data_de_amostra, $data_ate_amostra, $aproveitamento, $tempo_abaixo);

            } else if ($tipo_relatorio == 14 && $perfil_sistema != '3'){
                relatorio_tendencia_chat($data_de, $data_ate, $data_de_amostra, $data_ate_amostra, $qtd_atendimento_chat);

            } else if ($tipo_relatorio == 15 && $perfil_sistema != '3') {
                relatorio_horarios_chat($id_contrato_plano_pessoa);

            } else if ($tipo_relatorio == 16 && $perfil_usuario != '3'){
                relatorio_tabelao_chat($data_horario_escala);

            } else if ($tipo_relatorio == 17 && $perfil_usuario != '3'){
                relatorio_grupo_chat();

            } else if ($tipo_relatorio == 18 && $perfil_usuario != '3'){
                relatorio_grupo_chat_historico();

            } else {
                echo '<div class="alert alert-danger text-center">Erro ao exibir relatório!</div>';
            }
        }
    ?>
    </div>
</div>
<script>
    $('#tipo_relatorio').on('change',function(){
        tipo_relatorio = $(this).val();

        if (tipo_relatorio == 1) {
            $('#row_horarios1').show();
            $('#row_folgas').show();
            $('#row_folgas2').hide();
            $('#row_operador').show();
            $('#row_operador2').hide();
            $('#row_periodo').show();
            $('#row_lider').show();
            $('#row_compara').hide();
            $('#row_periodo2').hide();
            $('#row_periodo_amostra').hide();
            $('#row_flag_escalas').hide();
            $('#row_tempo_abaixo').hide();
            $('#row_aproveitamento').hide();
            $('#row_ponderado').hide();
            $('#row_turno3').hide();
            $('#row_periodo3').hide();
            $('#row_horario_escala').hide();
            $('#row_qtd_atendimento_chat').hide();
            $('#row_contrato').hide();
            $('#row_faz_chat').hide();

        } else if (tipo_relatorio == 2) {
            $('#row_horarios1').hide();
            $('#row_folgas').hide();
            $('#row_folgas2').show();
            $('#row_operador').hide();
            $('#row_operador2').show();
            $('#row_periodo').hide();
            $('#row_lider').hide();
            $('#row_compara').hide();
            $('#row_periodo2').hide();
            $('#row_periodo_amostra').hide();
            $('#row_flag_escalas').hide();
            $('#row_tempo_abaixo').hide();
            $('#row_aproveitamento').hide();
            $('#row_ponderado').hide();
            $('#row_turno3').hide();
            $('#row_periodo3').hide();
            $('#row_horario_escala').hide();
            $('#row_qtd_atendimento_chat').hide();
            $('#row_contrato').hide();
            $('#row_faz_chat').hide();

        } else if (tipo_relatorio == 3) {
            $('#row_horarios1').hide();
            $('#row_folgas').hide();
            $('#row_folgas2').hide();
            $('#row_operador').hide();
            $('#row_operador2').hide();
            $('#row_periodo').show();
            $('#row_lider').show();
            $('#row_compara').hide();
            $('#row_periodo2').hide();
            $('#row_periodo_amostra').hide();
            $('#row_flag_escalas').hide();
            $('#row_tempo_abaixo').hide();
            $('#row_aproveitamento').hide();
            $('#row_ponderado').hide();
            $('#row_turno3').hide();
            $('#row_periodo3').hide();
            $('#row_horario_escala').hide();
            $('#row_qtd_atendimento_chat').hide();
            $('#row_contrato').hide();
            $('#row_faz_chat').hide();

        } else if (tipo_relatorio == 4) {
            $('#row_horarios1').hide();
            $('#row_folgas').hide();
            $('#row_folgas2').hide();
            $('#row_operador').hide();
            $('#row_operador2').hide();
            $('#row_periodo').hide();
            $('#row_lider').hide();
            $('#row_compara').show();
            $('#row_periodo2').hide();
            $('#row_periodo_amostra').hide();
            $('#row_flag_escalas').show();
            $('#row_tempo_abaixo').hide();
            $('#row_aproveitamento').hide();
            $('#row_ponderado').hide();
            $('#row_turno3').hide();
            $('#row_periodo3').hide();
            $('#row_horario_escala').hide();
            $('#row_qtd_atendimento_chat').hide();
            $('#row_contrato').hide();
            $('#row_faz_chat').hide();

        } else if (tipo_relatorio == 5) {
            $('#row_horarios1').hide();
            $('#row_folgas').hide();
            $('#row_folgas2').hide();
            $('#row_operador').hide();
            $('#row_operador2').hide();
            $('#row_periodo').hide();
            $('#row_lider').hide();
            $('#row_compara').hide();
            $('#row_periodo2').show();
            $('#row_periodo_amostra').hide();
            $('#row_flag_escalas').hide();
            $('#row_tempo_abaixo').hide();
            $('#row_aproveitamento').hide();
            $('#row_ponderado').hide();
            $('#row_turno3').show();
            $('#row_periodo3').hide();
            $('#row_horario_escala').hide();
            $('#row_qtd_atendimento_chat').hide();
            $('#row_contrato').hide();
            $('#row_faz_chat').hide();

        } else if (tipo_relatorio == 6) {
            $('#row_horarios1').hide();
            $('#row_folgas').hide();
            $('#row_folgas2').hide();
            $('#row_operador').hide();
            $('#row_operador2').show();
            $('#row_periodo').hide();
            $('#row_lider').hide();
            $('#row_compara').hide();
            $('#row_periodo2').show();
            $('#row_periodo_amostra').hide();
            $('#row_flag_escalas').hide();
            $('#row_tempo_abaixo').hide();
            $('#row_aproveitamento').hide();
            $('#row_ponderado').hide();
            $('#row_turno3').hide();
            $('#row_periodo3').hide();
            $('#row_horario_escala').hide();
            $('#row_qtd_atendimento_chat').hide();
            $('#row_contrato').hide();
            $('#row_faz_chat').hide();

        } else if (tipo_relatorio == 7) {
            $('#row_horarios1').hide();
            $('#row_folgas').hide();
            $('#row_folgas2').hide();
            $('#row_operador').hide();
            $('#row_operador2').hide();
            $('#row_periodo').hide();
            $('#row_lider').hide();
            $('#row_compara').hide();
            $('#row_periodo2').show();
            $('#row_periodo_amostra').show();
            $('#row_flag_escalas').hide();
            $('#row_tempo_abaixo').show();
            $('#row_aproveitamento').show();
            $('#row_ponderado').show();
            $('#row_turno3').hide();
            $('#row_periodo3').hide();
            $('#row_horario_escala').hide();
            $('#row_qtd_atendimento_chat').hide();
            $('#row_contrato').hide();
            $('#row_faz_chat').hide();

        } else if (tipo_relatorio == 8) {
            $('#row_horarios1').hide();
            $('#row_folgas').hide();
            $('#row_folgas2').hide();
            $('#row_operador').hide();
            $('#row_operador2').show();
            $('#row_periodo').hide();
            $('#row_lider').hide();
            $('#row_compara').hide();
            $('#row_periodo2').hide();
            $('#row_periodo_amostra').hide();
            $('#row_flag_escalas').hide();
            $('#row_tempo_abaixo').hide();
            $('#row_aproveitamento').hide();
            $('#row_ponderado').hide();
            $('#row_turno3').hide();
            $('#row_periodo3').show();
            $('#row_horario_escala').hide();
            $('#row_qtd_atendimento_chat').hide();
            $('#row_contrato').hide();
            $('#row_faz_chat').hide();

        } else if (tipo_relatorio == 9) {
            $('#row_horarios1').hide();
            $('#row_folgas').hide();
            $('#row_folgas2').hide();
            $('#row_operador').hide();
            $('#row_operador2').hide();
            $('#row_periodo').hide();
            $('#row_lider').hide();
            $('#row_compara').hide();
            $('#row_periodo2').hide();
            $('#row_periodo_amostra').hide();
            $('#row_flag_escalas').hide();
            $('#row_tempo_abaixo').hide();
            $('#row_aproveitamento').hide();
            $('#row_ponderado').hide();
            $('#row_turno3').hide();
            $('#row_periodo3').show();
            $('#row_horario_escala').hide();
            $('#row_qtd_atendimento_chat').hide();
            $('#row_contrato').hide();
            $('#row_faz_chat').hide();

        } else if (tipo_relatorio == 10) {
            $('#row_horarios1').hide();
            $('#row_folgas').hide();
            $('#row_folgas2').hide();
            $('#row_operador').hide();
            $('#row_operador2').hide();
            $('#row_periodo').hide();
            $('#row_lider').hide();
            $('#row_compara').hide();
            $('#row_periodo2').hide();
            $('#row_periodo_amostra').hide();
            $('#row_flag_escalas').hide();
            $('#row_tempo_abaixo').hide();
            $('#row_aproveitamento').hide();
            $('#row_ponderado').hide();
            $('#row_turno3').hide();
            $('#row_periodo3').hide();
            $('#row_horario_escala').show();
            $('#row_qtd_atendimento_chat').hide();
            $('#row_contrato').hide();
            $('#row_faz_chat').show();

        } else if (tipo_relatorio == 11) {
            $('#row_horarios1').hide();
            $('#row_folgas').hide();
            $('#row_folgas2').hide();
            $('#row_operador').hide();
            $('#row_operador2').hide();
            $('#row_periodo').hide();
            $('#row_lider').hide();
            $('#row_compara').hide();
            $('#row_periodo2').show();
            $('#row_periodo_amostra').hide();
            $('#row_flag_escalas').hide();
            $('#row_tempo_abaixo').hide();
            $('#row_aproveitamento').hide();
            $('#row_ponderado').hide();
            $('#row_turno3').show();
            $('#row_periodo3').hide();
            $('#row_horario_escala').hide();
            $('#row_qtd_atendimento_chat').hide();
            $('#row_contrato').hide();
            $('#row_faz_chat').hide();

        } else if (tipo_relatorio == 12) {
            $('#row_horarios1').hide();
            $('#row_folgas').hide();
            $('#row_folgas2').hide();
            $('#row_operador').hide();
            $('#row_operador2').hide();
            $('#row_periodo').hide();
            $('#row_lider').hide();
            $('#row_compara').hide();
            $('#row_periodo2').show();
            $('#row_periodo_amostra').show();
            $('#row_flag_escalas').hide();
            $('#row_tempo_abaixo').show();
            $('#row_aproveitamento').show();
            $('#row_ponderado').show();
            $('#row_turno3').hide();
            $('#row_periodo3').hide();
            $('#row_horario_escala').hide();
            $('#row_qtd_atendimento_chat').hide();
            $('#row_contrato').hide();
            $('#row_faz_chat').hide();

        } else if (tipo_relatorio == 13) {
            $('#row_horarios1').hide();
            $('#row_folgas').hide();
            $('#row_folgas2').hide();
            $('#row_operador').hide();
            $('#row_operador2').hide();
            $('#row_periodo').hide();
            $('#row_lider').hide();
            $('#row_compara').hide();
            $('#row_periodo2').show();
            $('#row_periodo_amostra').show();
            $('#row_flag_escalas').hide();
            $('#row_tempo_abaixo').show();
            $('#row_aproveitamento').show();
            $('#row_ponderado').hide();
            $('#row_turno3').hide();
            $('#row_periodo3').hide();
            $('#row_horario_escala').hide();
            $('#row_qtd_atendimento_chat').hide();
            $('#row_contrato').hide();
            $('#row_faz_chat').hide();

        } else if (tipo_relatorio == 14) {
            $('#row_horarios1').hide();
            $('#row_folgas').hide();
            $('#row_folgas2').hide();
            $('#row_operador').hide();
            $('#row_operador2').hide();
            $('#row_periodo').hide();
            $('#row_lider').hide();
            $('#row_compara').hide();
            $('#row_periodo2').show();
            $('#row_periodo_amostra').show();
            $('#row_flag_escalas').hide();
            $('#row_tempo_abaixo').hide();
            $('#row_aproveitamento').hide();
            $('#row_ponderado').hide();
            $('#row_turno3').hide();
            $('#row_periodo3').hide();
            $('#row_horario_escala').hide();
            $('#row_qtd_atendimento_chat').show();
            $('#row_contrato').hide();
            $('#row_faz_chat').hide();

        } else if (tipo_relatorio == 15) {
            $('#row_horarios1').hide();
            $('#row_folgas').hide();
            $('#row_folgas2').hide();
            $('#row_operador').hide();
            $('#row_operador2').hide();
            $('#row_periodo').hide();
            $('#row_lider').hide();
            $('#row_compara').hide();
            $('#row_periodo2').hide();
            $('#row_periodo_amostra').hide();
            $('#row_flag_escalas').hide();
            $('#row_tempo_abaixo').hide();
            $('#row_aproveitamento').hide();
            $('#row_ponderado').hide();
            $('#row_turno3').hide();
            $('#row_periodo3').hide();
            $('#row_horario_escala').hide();
            $('#row_qtd_atendimento_chat').hide();
            $('#row_contrato').show();
            $('#row_faz_chat').hide();
        
        } else if (tipo_relatorio == 16) {
            $('#row_horarios1').hide();
            $('#row_folgas').hide();
            $('#row_folgas2').hide();
            $('#row_operador').hide();
            $('#row_operador2').hide();
            $('#row_periodo').hide();
            $('#row_lider').hide();
            $('#row_compara').hide();
            $('#row_periodo2').hide();
            $('#row_periodo_amostra').hide();
            $('#row_flag_escalas').hide();
            $('#row_tempo_abaixo').hide();
            $('#row_aproveitamento').hide();
            $('#row_ponderado').hide();
            $('#row_turno3').hide();
            $('#row_periodo3').hide();
            $('#row_horario_escala').show();
            $('#row_qtd_atendimento_chat').hide();
            $('#row_contrato').hide();
            $('#row_faz_chat').hide();

        } else if (tipo_relatorio == 17) {
            $('#row_horarios1').hide();
            $('#row_folgas').hide();
            $('#row_folgas2').hide();
            $('#row_operador').hide();
            $('#row_operador2').hide();
            $('#row_periodo').hide();
            $('#row_lider').hide();
            $('#row_compara').hide();
            $('#row_periodo2').hide();
            $('#row_periodo_amostra').hide();
            $('#row_flag_escalas').hide();
            $('#row_tempo_abaixo').hide();
            $('#row_aproveitamento').hide();
            $('#row_ponderado').hide();
            $('#row_turno3').hide();
            $('#row_periodo3').hide();
            $('#row_horario_escala').hide();
            $('#row_qtd_atendimento_chat').hide();
            $('#row_contrato').hide();
            $('#row_faz_chat').hide();

        }
    });  

    // Atribui evento e função para limpeza dos campos
	$('#busca_contrato').on('input', limpaCamposContrato);
	// Dispara o Autocomplete da pessoa a partir do segundo caracter
	$("#busca_contrato").autocomplete({
			minLength: 2,
			source: function(request, response) {
				$.ajax({
					url: "/api/ajax?class=ContratoAutocomplete.php",
					dataType: "json",
					data: {
						acao: 'autocomplete',
						parametros: {
							'nome': $('#busca_contrato').val(),
							'cod_servico': 'call_suporte'
						},
                        token: '<?= $request->token ?>'
					},
					success: function(data) {
						response(data);
					}
				});
			},
			focus: function(event, ui) {
				$("#busca_contrato").val(ui.item.nome + " " + ui.item.nome_contrato + " - " + ui.item.servico + " - " + ui.item.plano + " (" + ui.item.id_contrato_plano_pessoa + ")");
				carregarDadosContrato(ui.item.id_contrato_plano_pessoa);
				return false;
			},
			select: function(event, ui) {
				$("#busca_contrato").val(ui.item.nome + " " + ui.item.nome_contrato + " - " + ui.item.servico + " - " + ui.item.plano + " (" + ui.item.id_contrato_plano_pessoa + ")");
				$('#busca_contrato').attr("readonly", true);
				return false;
			}
		})
		.autocomplete("instance")._renderItem = function(ul, item) {
			if (!item.razao_social) {
				item.razao_social = '';
			}
			if (!item.cpf_cnpj) {
				item.cpf_cnpj = '';
			}
			if (!item.nome_contrato) {
				item.nome_contrato = '';
			} else {
				item.nome_contrato = ' (' + item.nome_contrato + ') ';
			}
			return $("<li>").append("<a><strong>" + item.id_contrato_plano_pessoa + " - " + item.nome + "" + item.nome_contrato + " </strong><br>" + item.razao_social + "<br>" + item.cpf_cnpj + "<br>" + item.servico + " - " + item.plano + " (" + item.id_contrato_plano_pessoa + ")" + "</a><hr style='margin-bottom: 0px;'>").appendTo(ul);
		};
	// Função para carregar os dados da consulta nos respectivos campos
	function carregarDadosContrato(id) {
		var busca = $('#busca_contrato').val();
		if (busca != "" && busca.length >= 2) {
			$.ajax({
				url: "/api/ajax?class=ContratoAutocomplete.php",
				dataType: "json",
				data: {
					acao: 'consulta',
					parametros: {
						'id': id,
					},
                    token: '<?= $request->token ?>'
				},
				success: function(data) {
					$('#id_contrato_plano_pessoa').val(data[0].id_contrato_plano_pessoa);
					seleciona_contrato(data[0].id_contrato_plano_pessoa);
				}
			});
		}
	}
	// Função para limpar os campos caso a busca esteja vazia
	function limpaCamposContrato() {
		var busca = $('#busca_contrato').val();
		if (busca == "") {
			$('#id_contrato_plano_pessoa').val('');
		}
	}
	$(document).on('click', '#habilita_busca_contrato', function() {
		$('#id_contrato_plano_pessoa').val('');
		$('#busca_contrato').val('');
		$('#busca_contrato').attr("readonly", false);
		$('#busca_contrato').focus();
	});

    $('#accordionRelatorio').on('shown.bs.collapse', function () {
       $("#i_collapse").removeClass("fa fa-plus").addClass("fa fa-minus");
    });

    $('#accordionRelatorio').on('hidden.bs.collapse', function () {
       $("#i_collapse").removeClass("fa fa-minus").addClass("fa fa-plus");
    });

    $(document).on('submit', 'form', function () {
        var tipo_relatorio = $('#tipo_relatorio').val();
        var expediente = $('#expediente').val();
        var horario_inicial = $('#horario_inicial').val();
        var horario_final = $('#horario_final').val();
       
        var mes_inicio = $('#mes_inicio').val();
        var mes_fim = $('#mes_fim').val();

        var data_de = $( "input[name='data_de']" ).val();
        var data_ate = $( "input[name='data_ate']" ).val();

        var data_de_amostra = $( "input[name='data_de_amostra']" ).val();
        var data_ate_amostra = $( "input[name='data_ate_amostra']" ).val();
        
        var ponderado = $('#ponderado').val();

        var ano1 = data_de.split("/")[2];
        var mes1 = data_de.split("/")[1];
        var dia1 = data_de.split("/")[0];

        var ano2 = data_ate.split("/")[2];
        var mes2 = data_ate.split("/")[1];
        var dia2 = data_ate.split("/")[0];

        var ano3 = data_de_amostra.split("/")[2];
        var mes3 = data_de_amostra.split("/")[1];
        var dia3 = data_de_amostra.split("/")[0];

        var ano4 = data_ate_amostra.split("/")[2];
        var mes4 = data_ate_amostra.split("/")[1];
        var dia4 = data_ate_amostra.split("/")[0];

        var data1 = ano1+''+mes1+''+dia1;
        var data2 = ano2+''+mes2+''+dia2;
        var data3 = ano3+''+mes3+''+dia3;
        var data4 = ano4+''+mes4+''+dia4;

        var data_hoje = '<?=$data?>';
       
        if(tipo_relatorio == 1){
            if(!horario_inicial && !horario_final && expediente != 0){
                alert('Dia selecionado, escolha também um horário!');
                return false;
            }else if(horario_inicial && expediente == 0){
                alert('Horário inicial selecionado, escolha também um dia!');
                return false;   
            }else if(horario_final && expediente == 0){
                alert('Horário final selecionado, escolha também um dia!');
                return false;   
            }else if(!horario_inicial && horario_final && expediente != 0){
                alert('Selecionae também o horário inicial!');
                return false;   
            }
        }

        if(tipo_relatorio == 4){

            if(mes_inicio >= mes_fim){
                alert("A data da escala 1 deve ser menor que a data da escala 2!");
                return false;
            }
        }

        if(tipo_relatorio == 7){

            if(!data_ate_amostra || !data_de_amostra){
                alert("Deve-se preencher as duas datas de amostra!");
                return false;
            }else if(!data_ate || !data_de){
                alert("Deve-se preencher as duas datas da escala!");
                return false;
            }else if(data4 >= data_hoje){
                alert("A data de amostra não pode ser maior ou igual a data de hoje!");
                return false;
            }else if(data4 < data3){
                alert("A data final da amostra deve ser maior que a data inicial da amostra!");
                return false;
            }else if(data2 < data1){
                alert("A data final da escala deve ser maior que a data inicial da escala!");
                return false;
            }

            if(ponderado == 1){
                DAY = 1000 * 60 * 60  * 24

                data1 = data_de_amostra;
                data2 = data_ate_amostra;

                var nova1 = data1.toString().split('/');
                Nova1 = nova1[1]+"/"+nova1[0]+"/"+nova1[2];

                var nova2 = data2.toString().split('/');
                Nova2 = nova2[1]+"/"+nova2[0]+"/"+nova2[2];

                d1 = new Date(Nova1)
                d2 = new Date(Nova2)

                diferenca = Math.round((d2.getTime() - d1.getTime()) / DAY)

                if(diferenca < 20){
                    alert('Deve-se selecionar um período mínimo de 3 semanas (21 dias) entre as datas de amostra de chamadas para relatórios ponderados!');
                    return false;
                }
            }
        }

        modalAguarde();
    });

    $(document).ready(function(){
        $('#aguarde').hide();
        $('#resultado').show();
        $("#gerar").prop("disabled", false);
    });
</script>
<?php

function relatorio_tabelao_chat($data){
    
    $data = converteData($data);

    $diasemana = array(
        "0" => "Domingo",
        "1" => "Segunda",
        "2" => "Terça",
        "3" => "Quarta",
        "4" => "Quinta",
        "5" => "Sexta",
        "6" => "Sábado",
    );
    $data_da_consulta = explode('-', $data);
    $data_da_consulta = $data_da_consulta[0].'-'.$data_da_consulta[1].'-01';
    $hora_agora = date('H:i');
    $diasemana_numero = date('w', strtotime($data));
    $diasemana_numero = $diasemana[$diasemana_numero];
    $data_de_hoje = explode(' ', getDataHora());
    $data_de_hoje = $data_de_hoje[0];

    if($diasemana_numero == 'Domingo'){
        $chave_nome_inicial = 'inicial_dom';
        $chave_nome_final = 'final_dom';
    }else if($diasemana_numero == 'Sábado'){
        $chave_nome_inicial = 'inicial_sab';
        $chave_nome_final = 'final_sab';
    }else{
        $chave_nome_inicial = 'inicial_seg';
        $chave_nome_final = 'final_seg';
    }

    $data_hora = converteDataHora(getDataHora());
    $gerado = "<span style=\"font-size: 14px;\"><strong>Gerado em: </strong> $data_hora</span>";
      
    echo "<div class=\"col-md-12\" style=\"padding: 0\">";
    echo "<legend style=\"text-align:center;\"><strong>Relatório Tabelão de Chat</strong><br>$gerado</legend>";
    echo "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong>Data - </strong>".converteData($data)." - ".$diasemana_numero."</legend>";

    $diasemana_numero = date('w', strtotime($data));
    $data_hora = date('Y-m-d H:i:s');
    $diasemana_numero = $diasemana[$diasemana_numero];

    $data_ontem = new DateTime($data);
    $data_ontem->modify('-1 day');
    $data_ontem = $data_ontem->format("Y-m-d");

    $diasemana_numero_ontem = date('w', strtotime($data_ontem));
    $diasemana_numero_ontem = $diasemana[$diasemana_numero_ontem];

    if($diasemana_numero_ontem == 'Domingo'){
        $chave_nome_inicial_ontem = 'inicial_dom';
        $chave_nome_final_ontem = 'final_dom';
    }else if($diasemana_numero_ontem == 'Sábado'){
        $chave_nome_inicial_ontem = 'inicial_sab';
        $chave_nome_final_ontem = 'final_sab';
    }else{
        $chave_nome_inicial_ontem = 'inicial_seg';
        $chave_nome_final_ontem = 'final_seg';
    }

    $dados = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa INNER JOIN tb_horarios_escala c ON a.id_usuario = c.id_usuario INNER JOIN tb_chat_horarios_escala d ON c.id_horarios_escala = d.id_horarios_escala WHERE a.id_perfil_sistema = '3' AND b.status != 2 AND a.status = 1 AND c.data_inicial = '".$data_da_consulta."' AND c.chat = 1 ORDER BY c.".$chave_nome_final." ASC " , "a.id_usuario, b.nome, c.*, d.*");

    $contador_horas = 0;
    echo '<table class="table table-bordered  dataTable" style="margin-bottom:0;">';
            echo "<thead>";
                echo "<tr>";
                while($contador_horas < 24){
                    echo "<th class=\"text-center\">".sprintf('%02d', $contador_horas).":00</th>";
                    $contador_horas ++;
                }
                echo "</tr>";
            echo "</thead>";
            echo "<tbody>";

                foreach ($dados as $atendente) {

                    $id = $atendente['id_usuario'];
                    $dados_horario = DBRead('', 'tb_usuario',"WHERE id_usuario = '".$id."' AND id_usuario NOT IN (SELECT id_usuario FROM tb_ferias WHERE data_de <= '".$data."' AND data_ate >= '".$data."')");
                    
                    $dados_horario_especial = DBRead('', 'tb_horarios_especiais a',"INNER JOIN tb_chat_horarios_especiais b ON a.id_horarios_especiais = b.id_horarios_especiais WHERE a.dia = '".$data."' AND a.id_horarios_escala = '".$atendente['id_horarios_escala']."' ", "b.*");
    
                    if($dados_horario){
              
                        if($dados_horario_especial){
                            $horario_inicial_chat = explode(':', $dados_horario_especial[0]['inicial_especial']);
                            $horario_minutos_inicial_chat = $horario_inicial_chat[1];
                            $horario_inicial_chat = $horario_inicial_chat[0];
                    
                            $horario_final_chat = explode(':', $dados_horario_especial[0]['final_especial']);
                            $horario_minutos_final_chat = $horario_final_chat[1];
                            $horario_final_chat = $horario_final_chat[0];

                            $dados_grupo_chat = DBRead('', 'tb_grupo_atendimento_chat_operador a',"INNER JOIN tb_grupo_atendimento_chat b ON a.id_grupo_atendimento_chat = b.id_grupo_atendimento_chat WHERE a.id_usuario = '".$atendente['id_usuario']."' ");

                            foreach ($dados_grupo_chat as $conteudo_grupo_chat) {
                                echo "<tr>";

                                $contador_horas = 0;
                                while($contador_horas < 24){

                                    if($horario_inicial_chat < $horario_final_chat){
                                        if($horario_inicial_chat <= $contador_horas && $horario_final_chat >= $contador_horas){
                                            if($horario_inicial_chat == $contador_horas){
                                                if($horario_minutos_inicial_chat <= 30){
                                                    echo "<td class=\"text-center\" style='vertical-align: middle; background-color: ".$conteudo_grupo_chat['cor']."'><strong>".$atendente['nome']." </strong></td>";                     
                                                }else{
                                                    echo "<td class=\"text-center\" style='vertical-align: middle'></td>";                     
                                                }
                        
                                            }else if($horario_final_chat == $contador_horas){
                                                if($horario_minutos_final_chat >= 30){
                                                    echo "<td class=\"text-center\" style='vertical-align: middle; background-color: ".$conteudo_grupo_chat['cor']."'><strong>".$atendente['nome']." </strong></td>";                     
                                                }else{
                                                    echo "<td class=\"text-center\" style='vertical-align: middle'></td>";                     
                                                }
                                            }else{
                                                echo "<td class=\"text-center\" style='vertical-align: middle; background-color: ".$conteudo_grupo_chat['cor']."'><strong>".$atendente['nome']." </strong></td>";                     
                                            }
                                        }else{
                                            echo "<td class=\"text-center\" style='vertical-align: middle'></td>";                     
                                        }
                                    }else{
                                        if($horario_inicial_chat <= $contador_horas){
                                            if($horario_inicial_chat == $contador_horas){
                                                if($horario_minutos_inicial_chat <= 30){
                                                    echo "<td class=\"text-center\" style='vertical-align: middle; background-color: ".$conteudo_grupo_chat['cor']."'><strong>".$atendente['nome']." </strong></td>";                     
                                                }else{
                                                    echo "<td class=\"text-center\" style='vertical-align: middle'></td>";                     
                                                }
                        
                                            }else if($horario_final_chat == $contador_horas){
                                                if($horario_minutos_final_chat >= 30){
                                                    echo "<td class=\"text-center\" style='vertical-align: middle; background-color: ".$conteudo_grupo_chat['cor']."'><strong>".$atendente['nome']." </strong></td>";                     
                                                }else{
                                                    echo "<td class=\"text-center\" style='vertical-align: middle'></td>";                     
                                                }
                                            }else{
                                                echo "<td class=\"text-center\" style='vertical-align: middle; background-color: ".$conteudo_grupo_chat['cor']."'><strong>".$atendente['nome']." </strong></td>";                     
                                            }
                                        }else{

                                            $dados_horario_ontem = DBRead('', 'tb_usuario',"WHERE id_usuario = '".$id."' AND id_usuario NOT IN (SELECT id_usuario FROM tb_ferias WHERE data_de <= '".$data_ontem."' AND data_ate >= '".$data_ontem."')");
                        
                                            $dados_horario_especial_ontem = DBRead('', 'tb_horarios_especiais a',"INNER JOIN tb_chat_horarios_especiais b ON a.id_horarios_especiais = b.id_horarios_especiais WHERE a.dia = '".$data_ontem."' AND a.id_horarios_escala = '".$atendente['id_horarios_escala']."' ", "b.*");
                            
                                            if($dados_horario_ontem){
                                    
                                                if($dados_horario_especial_ontem){
                                                    $horario_inicial_chat_ontem = explode(':', $dados_horario_especial_ontem[0]['inicial_especial']);
                                                    $horario_minutos_inicial_chat_ontem = $horario_inicial_chat_ontem[1];
                                                    $horario_inicial_chat_ontem = $horario_inicial_chat_ontem[0];

                                                    $horario_final_chat_ontem = explode(':', $dados_horario_especial_ontem[0]['final_especial']);
                                                    $horario_minutos_final_chat_ontem = $horario_final_chat_ontem[1];
                                                    $horario_final_chat_ontem = $horario_final_chat_ontem[0];
                                            
                                                    if($horario_inicial_chat_ontem >= $horario_final_chat_ontem){
                                                        if($horario_final_chat_ontem >= $contador_horas){
                                                            if($horario_inicial_chat == $contador_horas){
                                                                if($horario_minutos_inicial_chat >= 30){
                                                                    echo "<td class=\"text-center\" style='vertical-align: middle; background-color: ".$conteudo_grupo_chat['cor']."'><strong>".$atendente['nome']."</strong></td>";                     
                                                                }else{
                                                                    echo "<td class=\"text-center\" style='vertical-align: middle'></td>";                     
                                                                }

                                                            }else{
                                                                echo "<td class=\"text-center\" style='vertical-align: middle; background-color: ".$conteudo_grupo_chat['cor']."'><strong>".$atendente['nome']."</strong></td>";                     
                                                            }
                                                        }else{
                                                            echo "<td class=\"text-center\" style='vertical-align: middle'></td>";                     
                                                        }
                                                    }
                        
                                                
                                                }else if($diasemana_numero_ontem == 'Domingo'){
                                                    $dados_folga_domingo_ontem = DBRead('', 'tb_folgas_dom',"WHERE id_horarios_escala = '".$atendente['id_horarios_escala']."' AND dia = '".$data_ontem."'");
                                
                                                    if(!$dados_folga_domingo_ontem){
                                                        $horario_inicial_chat_ontem = explode(':', $atendente[$chave_nome_inicial_ontem]);
                                                        $horario_inicial_chat_ontem = $horario_inicial_chat_ontem[0];
                                                
                                                        $horario_final_chat_ontem = explode(':', $atendente[$chave_nome_final_ontem]);
                                                        $horario_final_chat_ontem = $horario_final_chat_ontem[0];
                                                           
                                                        if($horario_inicial_chat_ontem >= $horario_final_chat_ontem){
                                                            if($horario_final_chat_ontem >= $contador_horas){
                                                                echo "<td class=\"text-center\" style='vertical-align: middle; background-color: ".$conteudo_grupo_chat['cor']."'><strong>".$atendente['nome']."</strong></td>";                     
                                                            }else{
                                                                echo "<td class=\"text-center\" style='vertical-align: middle'></td>";                     
                                                            }
                                                        }
                                                                                                            
                                                    }
                                                }else if($atendente['folga_seg'] != $diasemana_numero_ontem){

                                                    $horario_inicial_chat_ontem = explode(':', $atendente[$chave_nome_inicial_ontem]);
                                                    $horario_minutos_inicial_chat_ontem = $horario_inicial_chat_ontem[1];
                                                    $horario_inicial_chat_ontem = $horario_inicial_chat_ontem[0];
                                            
                                                    $horario_final_chat_ontem = explode(':', $atendente[$chave_nome_final_ontem]);
                                                    $horario_minutos_final_chat_ontem = $horario_final_chat_ontem[1];
                                                    $horario_final_chat_ontem = $horario_final_chat_ontem[0];
                                                                                                                                        
                                                    if($horario_inicial_chat_ontem >= $horario_final_chat_ontem){
                                                        if($horario_final_chat_ontem >= $contador_horas){
                                                            if($horario_final_chat_ontem == $contador_horas){
                                                                if($horario_minutos_final_chat_ontem >= 30){
                                                                    echo "<td class=\"text-center\" style='vertical-align: middle; background-color: ".$conteudo_grupo_chat['cor']."'><strong>".$atendente['nome']."</strong></td>";                     
                                                                }else{
                                                                    echo "<td class=\"text-center\" style='vertical-align: middle'></td>";                     
                                                                }
                                                            }else{
                                                                echo "<td class=\"text-center\" style='vertical-align: middle; background-color: ".$conteudo_grupo_chat['cor']."'><strong>".$atendente['nome']."</strong></td>";                     
                                                            }
                                                        }else{
                                                            echo "<td class=\"text-center\" style='vertical-align: middle'></td>";                     
                                                        }
                                                    }
                                                }
                                            }                    
                                        }                                        
                                    }
                                    $contador_horas ++;
                                }
                                echo "</tr>";
                            }
                        
                        }else if($diasemana_numero == 'Domingo'){
                            $dados_folga_domingo_hoje = DBRead('', 'tb_folgas_dom',"WHERE id_horarios_escala = '".$atendente['id_horarios_escala']."' AND dia = '".$data."'");
        
                            if(!$dados_folga_domingo_hoje){
                                $horario_inicial_chat = explode(':', $atendente[$chave_nome_inicial]);
                                $horario_minutos_inicial_chat = $horario_inicial_chat[1];
                                $horario_inicial_chat = $horario_inicial_chat[0];

                                $horario_final_chat = explode(':', $atendente[$chave_nome_final]);

                                $horario_minutos_final_chat = $horario_final_chat[1];
                                $horario_final_chat = $horario_final_chat[0];
                                
                                $dados_grupo_chat = DBRead('', 'tb_grupo_atendimento_chat_operador a',"INNER JOIN tb_grupo_atendimento_chat b ON a.id_grupo_atendimento_chat = b.id_grupo_atendimento_chat WHERE a.id_usuario = '".$atendente['id_usuario']."' ");

                                foreach ($dados_grupo_chat as $conteudo_grupo_chat) {
                                    echo "<tr>";

                                    $contador_horas = 0;
                                    while($contador_horas < 24){

                                        if($horario_inicial_chat < $horario_final_chat){
                                            if($horario_inicial_chat <= $contador_horas && $horario_final_chat >= $contador_horas){
                                                if($horario_inicial_chat == $contador_horas){
                                                    if($horario_minutos_inicial_chat <= 30){
                                                        echo "<td class=\"text-center\" style='vertical-align: middle; background-color: ".$conteudo_grupo_chat['cor']."'><strong>".$atendente['nome']." </strong></td>";                     
                                                    }else{
                                                        echo "<td class=\"text-center\" style='vertical-align: middle'></td>";                     
                                                    }
                            
                                                }else if($horario_final_chat == $contador_horas){
                                                    if($horario_minutos_final_chat >= 30){
                                                        echo "<td class=\"text-center\" style='vertical-align: middle; background-color: ".$conteudo_grupo_chat['cor']."'><strong>".$atendente['nome']." </strong></td>";                     
                                                    }else{
                                                        echo "<td class=\"text-center\" style='vertical-align: middle'></td>";                     
                                                    }
                                                }else{
                                                    echo "<td class=\"text-center\" style='vertical-align: middle; background-color: ".$conteudo_grupo_chat['cor']."'><strong>".$atendente['nome']." </strong></td>";                     
                                                }
                                            }else{
                                                echo "<td class=\"text-center\" style='vertical-align: middle'></td>";                     
                                            }
                                        }else{
                                            if($horario_inicial_chat <= $contador_horas){
                                                if($horario_inicial_chat == $contador_horas){
                                                    if($horario_minutos_inicial_chat <= 30){
                                                        echo "<td class=\"text-center\" style='vertical-align: middle; background-color: ".$conteudo_grupo_chat['cor']."'><strong>".$atendente['nome']." </strong></td>";                     
                                                    }else{
                                                        echo "<td class=\"text-center\" style='vertical-align: middle'></td>";                     
                                                    }
                            
                                                }else if($horario_final_chat == $contador_horas){
                                                    if($horario_minutos_final_chat >= 30){
                                                        echo "<td class=\"text-center\" style='vertical-align: middle; background-color: ".$conteudo_grupo_chat['cor']."'><strong>".$atendente['nome']." </strong></td>";                     
                                                    }else{
                                                        echo "<td class=\"text-center\" style='vertical-align: middle'></td>";                     
                                                    }
                                                }else{
                                                    echo "<td class=\"text-center\" style='vertical-align: middle; background-color: ".$conteudo_grupo_chat['cor']."'><strong>".$atendente['nome']." </strong></td>";                     
                                                }
                                            }else{
    
                                                $dados_horario_ontem = DBRead('', 'tb_usuario',"WHERE id_usuario = '".$id."' AND id_usuario NOT IN (SELECT id_usuario FROM tb_ferias WHERE data_de <= '".$data_ontem."' AND data_ate >= '".$data_ontem."')");
                            
                                                $dados_horario_especial_ontem = DBRead('', 'tb_horarios_especiais a',"INNER JOIN tb_chat_horarios_especiais b ON a.id_horarios_especiais = b.id_horarios_especiais WHERE a.dia = '".$data_ontem."' AND a.id_horarios_escala = '".$atendente['id_horarios_escala']."' ", "b.*");
                                
                                                if($dados_horario_ontem){
                                        
                                                    if($dados_horario_especial_ontem){
                                                        $horario_inicial_chat_ontem = explode(':', $dados_horario_especial_ontem[0]['inicial_especial']);
                                                        $horario_minutos_inicial_chat_ontem = $horario_inicial_chat_ontem[1];
                                                        $horario_inicial_chat_ontem = $horario_inicial_chat_ontem[0];
    
                                                        $horario_final_chat_ontem = explode(':', $dados_horario_especial_ontem[0]['final_especial']);
                                                        $horario_minutos_final_chat_ontem = $horario_final_chat_ontem[1];
                                                        $horario_final_chat_ontem = $horario_final_chat_ontem[0];
                                                
                                                        if($horario_inicial_chat_ontem >= $horario_final_chat_ontem){
                                                            if($horario_final_chat_ontem >= $contador_horas){
                                                                if($horario_inicial_chat == $contador_horas){
                                                                    if($horario_minutos_inicial_chat >= 30){
                                                                        echo "<td class=\"text-center\" style='vertical-align: middle; background-color: ".$conteudo_grupo_chat['cor']."'><strong>".$atendente['nome']."</strong></td>";                     
                                                                    }else{
                                                                        echo "<td class=\"text-center\" style='vertical-align: middle'></td>";                     
                                                                    }
    
                                                                }else{
                                                                    echo "<td class=\"text-center\" style='vertical-align: middle; background-color: ".$conteudo_grupo_chat['cor']."'><strong>".$atendente['nome']."</strong></td>";                     
                                                                }
                                                            }else{
                                                                echo "<td class=\"text-center\" style='vertical-align: middle'></td>";                     
                                                            }
                                                        }
                            
                                                    
                                                    }else if($atendente['folga_seg'] != $diasemana_numero_ontem){
    
                                                        $horario_inicial_chat_ontem = explode(':', $atendente[$chave_nome_inicial_ontem]);
                                                        $horario_minutos_inicial_chat_ontem = $horario_inicial_chat_ontem[1];
                                                        $horario_inicial_chat_ontem = $horario_inicial_chat_ontem[0];
                                                
                                                        $horario_final_chat_ontem = explode(':', $atendente[$chave_nome_final_ontem]);
                                                        $horario_minutos_final_chat_ontem = $horario_final_chat_ontem[1];
                                                        $horario_final_chat_ontem = $horario_final_chat_ontem[0];
                                                                                                                                            
                                                        if($horario_inicial_chat_ontem >= $horario_final_chat_ontem){
                                                            if($horario_final_chat_ontem >= $contador_horas){
                                                                if($horario_final_chat_ontem == $contador_horas){
                                                                    if($horario_minutos_final_chat_ontem >= 30){
                                                                        echo "<td class=\"text-center\" style='vertical-align: middle; background-color: ".$conteudo_grupo_chat['cor']."'><strong>".$atendente['nome']."</strong></td>";                     
                                                                    }else{
                                                                        echo "<td class=\"text-center\" style='vertical-align: middle'></td>";                     
                                                                    }
                                                                }else{
                                                                    echo "<td class=\"text-center\" style='vertical-align: middle; background-color: ".$conteudo_grupo_chat['cor']."'><strong>".$atendente['nome']."</strong></td>";                     
                                                                }
                                                            }else{
                                                                echo "<td class=\"text-center\" style='vertical-align: middle'></td>";                     
                                                            }
                                                        }
                                                        
                                                    }
                                                }                    
                                            }                                        
                                        }
                                        
                                        // echo $contador_horas."<br>";
                                        $contador_horas ++;
                                    }
                                    echo "</tr>";

                                }
                               
                            }
                        }else if($atendente['folga_seg'] != $diasemana_numero){
                            $horario_inicial_chat = explode(':', $atendente[$chave_nome_inicial]);
                            $horario_minutos_inicial_chat = $horario_inicial_chat[1];
                            $horario_inicial_chat = $horario_inicial_chat[0];

                            $horario_final_chat = explode(':', $atendente[$chave_nome_final]);
                            $horario_minutos_final_chat = $horario_final_chat[1];
                            $horario_final_chat = $horario_final_chat[0];
                            
                            $dados_grupo_chat = DBRead('', 'tb_grupo_atendimento_chat_operador a',"INNER JOIN tb_grupo_atendimento_chat b ON a.id_grupo_atendimento_chat = b.id_grupo_atendimento_chat WHERE a.id_usuario = '".$atendente['id_usuario']."' ");
        
                            foreach ($dados_grupo_chat as $conteudo_grupo_chat) {
                                echo "<tr>";

                                $contador_horas = 0;
                                while($contador_horas < 24){
        
                                    if($horario_inicial_chat < $horario_final_chat){
                                        if($horario_inicial_chat <= $contador_horas && $horario_final_chat >= $contador_horas){
                                            if($horario_inicial_chat == $contador_horas){
                                                if($horario_minutos_inicial_chat <= 30){
                                                    echo "<td class=\"text-center\" style='vertical-align: middle; background-color: ".$conteudo_grupo_chat['cor']."'><strong>".$atendente['nome']." </strong></td>";                     
                                                }else{
                                                    echo "<td class=\"text-center\" style='vertical-align: middle'></td>";                     
                                                }
                        
                                            }else if($horario_final_chat == $contador_horas){
                                                if($horario_minutos_final_chat >= 30){
                                                    echo "<td class=\"text-center\" style='vertical-align: middle; background-color: ".$conteudo_grupo_chat['cor']."'><strong>".$atendente['nome']." </strong></td>";                     
                                                }else{
                                                    echo "<td class=\"text-center\" style='vertical-align: middle'></td>";                     
                                                }
                                            }else{
                                                echo "<td class=\"text-center\" style='vertical-align: middle; background-color: ".$conteudo_grupo_chat['cor']."'><strong>".$atendente['nome']." </strong></td>";                     
                                            }
                                        }else{
                                            echo "<td class=\"text-center\" style='vertical-align: middle'></td>";                     
                                        }
                                    }else{
                                        if($horario_inicial_chat <= $contador_horas){
                                            if($horario_inicial_chat == $contador_horas){
                                                if($horario_minutos_inicial_chat <= 30){
                                                    echo "<td class=\"text-center\" style='vertical-align: middle; background-color: ".$conteudo_grupo_chat['cor']."'><strong>".$atendente['nome']." </strong></td>";                     
                                                }else{
                                                    echo "<td class=\"text-center\" style='vertical-align: middle'></td>";                     
                                                }
                        
                                            }else if($horario_final_chat == $contador_horas){
                                                if($horario_minutos_final_chat >= 30){
                                                    echo "<td class=\"text-center\" style='vertical-align: middle; background-color: ".$conteudo_grupo_chat['cor']."'><strong>".$atendente['nome']." </strong></td>";                     
                                                }else{
                                                    echo "<td class=\"text-center\" style='vertical-align: middle'></td>";                     
                                                }
                                            }else{
                                                echo "<td class=\"text-center\" style='vertical-align: middle; background-color: ".$conteudo_grupo_chat['cor']."'><strong>".$atendente['nome']." </strong></td>";                     
                                            }
                                        }else{

                                            $dados_horario_ontem = DBRead('', 'tb_usuario',"WHERE id_usuario = '".$id."' AND id_usuario NOT IN (SELECT id_usuario FROM tb_ferias WHERE data_de <= '".$data_ontem."' AND data_ate >= '".$data_ontem."')");
                        
                                            $dados_horario_especial_ontem = DBRead('', 'tb_horarios_especiais a',"INNER JOIN tb_chat_horarios_especiais b ON a.id_horarios_especiais = b.id_horarios_especiais WHERE a.dia = '".$data_ontem."' AND a.id_horarios_escala = '".$atendente['id_horarios_escala']."' ", "b.*");

                                            if($dados_horario_ontem){
                                    
                                                if($dados_horario_especial_ontem){
                                                    $horario_inicial_chat_ontem = explode(':', $dados_horario_especial_ontem[0]['inicial_especial']);
                                                    $horario_minutos_inicial_chat_ontem = $horario_inicial_chat_ontem[1];
                                                    $horario_inicial_chat_ontem = $horario_inicial_chat_ontem[0];

                                                    $horario_final_chat_ontem = explode(':', $dados_horario_especial_ontem[0]['final_especial']);
                                                    $horario_minutos_final_chat_ontem = $horario_final_chat_ontem[1];
                                                    $horario_final_chat_ontem = $horario_final_chat_ontem[0];
                                            
                                                    if($horario_inicial_chat_ontem >= $horario_final_chat_ontem){
                                                        if($horario_final_chat_ontem >= $contador_horas){
                                                            if($horario_inicial_chat == $contador_horas){
                                                                if($horario_minutos_inicial_chat >= 30){
                                                                    echo "<td class=\"text-center\" style='vertical-align: middle; background-color: ".$conteudo_grupo_chat['cor']."'><strong>".$atendente['nome']."</strong></td>";                     
                                                                }else{
                                                                    echo "<td class=\"text-center\" style='vertical-align: middle'></td>";                     
                                                                }

                                                            }else{
                                                                echo "<td class=\"text-center\" style='vertical-align: middle; background-color: ".$conteudo_grupo_chat['cor']."'><strong>".$atendente['nome']."</strong></td>";                     
                                                            }
                                                        }else{
                                                            echo "<td class=\"text-center\" style='vertical-align: middle'></td>";                     
                                                        }
                                                    }
                        
                                                
                                                }else if($diasemana_numero_ontem == 'Domingo'){
                                                    $dados_folga_domingo_ontem = DBRead('', 'tb_folgas_dom',"WHERE id_horarios_escala = '".$atendente['id_horarios_escala']."' AND dia = '".$data_ontem."'");
                                
                                                    if(!$dados_folga_domingo_ontem){
                                                        $horario_inicial_chat_ontem = explode(':', $atendente[$chave_nome_inicial_ontem]);
                                                        $horario_inicial_chat_ontem = $horario_inicial_chat_ontem[0];
                                                
                                                        $horario_final_chat_ontem = explode(':', $atendente[$chave_nome_final_ontem]);
                                                        $horario_final_chat_ontem = $horario_final_chat_ontem[0];
                                                           
                                                        if($horario_inicial_chat_ontem >= $horario_final_chat_ontem){
                                                            if($horario_final_chat_ontem >= $contador_horas){
                                                                echo "<td class=\"text-center\" style='vertical-align: middle; background-color: ".$conteudo_grupo_chat['cor']."'><strong>".$atendente['nome']."</strong></td>";                     
                                                            }else{
                                                                echo "<td class=\"text-center\" style='vertical-align: middle'></td>";                     
                                                            }
                                                        }
                                                                                                            
                                                    }
                                                }else if($atendente['folga_seg'] != $diasemana_numero_ontem){

                                                    $horario_inicial_chat_ontem = explode(':', $atendente[$chave_nome_inicial_ontem]);
                                                    $horario_minutos_inicial_chat_ontem = $horario_inicial_chat_ontem[1];
                                                    $horario_inicial_chat_ontem = $horario_inicial_chat_ontem[0];
                                            
                                                    $horario_final_chat_ontem = explode(':', $atendente[$chave_nome_final_ontem]);
                                                    $horario_minutos_final_chat_ontem = $horario_final_chat_ontem[1];
                                                    $horario_final_chat_ontem = $horario_final_chat_ontem[0];
                                                                                                                                        
                                                    if($horario_inicial_chat_ontem >= $horario_final_chat_ontem){
                                                        if($horario_final_chat_ontem >= $contador_horas){
                                                            if($horario_final_chat_ontem == $contador_horas){
                                                                if($horario_minutos_final_chat_ontem >= 30){
                                                                    echo "<td class=\"text-center\" style='vertical-align: middle; background-color: ".$conteudo_grupo_chat['cor']."'><strong>".$atendente['nome']."</strong></td>";                     
                                                                }else{
                                                                    echo "<td class=\"text-center\" style='vertical-align: middle'></td>";                     
                                                                }
                                                            }else{
                                                                echo "<td class=\"text-center\" style='vertical-align: middle; background-color: ".$conteudo_grupo_chat['cor']."'><strong>".$atendente['nome']."</strong></td>";                     
                                                            }
                                                        }else{
                                                            echo "<td class=\"text-center\" style='vertical-align: middle'></td>";                     
                                                        }
                                                    }
                                                }
                                            }                    
                                        }                                        
                                    }
                                    $contador_horas ++;
                                }
                                echo "</tr>";
                            }
                        }
                    }
                }

            echo "</tbody>";
    echo "</table>";
    
    echo "<script>
            $(document).ready(function(){
                $('.dataTable').DataTable({
                    \"language\": {
                        \"url\": \"//cdn.datatables.net/plug-ins/1.10.19/i18n/Portuguese-Brasil.json\"
                    },             
                    aaSorting: [[1, 'asc']],     
                    \"searching\": false,
                    \"paging\":   false,
                    \"info\":     false,
                    \"scrollY\": '500px',
                    \"scrollX\": true,
                    \"scrollCollapse\": true,
                });
            });
        </script>           
        ";

    echo "<hr>";

    echo '
    <div class="row">
        <div class="col-md-12">
                
            <div class="panel-body">	 						
                <div class="row" id="row_periodo">';
                
                $dados_grupo_chat_amostra = DBRead('', 'tb_grupo_atendimento_chat',"WHERE status = 1 ORDER BY nome ASC");
                foreach ($dados_grupo_chat_amostra as $conteudo_grupo_chat_amostra) {
                    echo '
                    <div class="col-md-3">
                        <div class="form-group" >
                            <label>'.$conteudo_grupo_chat_amostra['nome'].'</label>
                            <input type="text" class="form-control input-sm" readonly style="background-color: '.$conteudo_grupo_chat_amostra['cor'].'">
                        </div>
                    </div>';
                }

                echo '
                </div>
            </div>
        </div>    </div>';

    echo '</div>';
}

function relatorio_tendencia_chat($data_de, $data_ate, $data_de_amostra, $data_ate_amostra, $qtd_atendimento_chat){
    $nome_tipo_grafico = 'line';

    $data_hora = converteDataHora(getDataHora());

    $gerado = "<span style=\"font-size: 14px;\"><strong>Gerado em: </strong> $data_hora</span>";

    echo "<div class=\"col-md-12\" style=\"padding: 0\">";
    echo "<legend style=\"text-align:center;\"><strong>Gráfico de Capacidade por Hora - Chat</strong><br>$gerado</legend>";
    echo "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong>Data Inicial da Escala - </strong>".$data_de.", <strong>Data Final da Escala - </strong>".$data_ate.", <strong>Data Inicial de Amostra de Chamadas - </strong>".$data_de_amostra.", <strong>Data Final de Amostra de Chamadas - </strong>".$data_ate_amostra.", <strong>Quantidade de atendimentos cada atendente faz por hora - </strong>".$qtd_atendimento_chat."</legend>";
    
    $dias_semana = array('Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado');
    $horas = array('0','1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17','18','19','20','21','22','23');
    $tma = array();
    $qtd_total_atendidas = array();
    $data_hora = converteDataHora(getDataHora());
    $total_entradas = array();
    $tendencia = array();

    $contador_dias_semana = array();

    $qtd_total_atendidas_dia = array();
    $total_entradas_dia = array();

    $entradas_dia = array();

    $auxiliar_menor_dia = array();
    $auxiliar_maior_dia = array();

    foreach (rangeDatas(converteData($data_de_amostra), converteData($data_ate_amostra)) as $data) {        
        $numero_dia_semana = date('w', strtotime($data));
        $hora = 0;

        $contador_dias_semana[$numero_dia_semana]++;
        while ($hora < 24) {
            $hora_zero = sprintf('%02d', $hora);
            $qtd_atendidas = 0;        

            $dados = DBRead('', 'tb_atendimento a', "WHERE via_texto = 1 AND gravado = '1' AND falha != 2 AND desconsiderar = 0 AND data_inicio BETWEEN '$data $hora_zero:00:00' AND '$data $hora_zero:59:59' ", "COUNT(id_atendimento) as cont");

            $qtd_atendidas = $dados[0]['cont'];
            $atendidas_hora = $dados[0]['cont'];

            $total_entradas[$numero_dia_semana][$hora] += $atendidas_hora;
            $total_entradas_dia[$numero_dia_semana][$contador_dias_semana[$numero_dia_semana]][$hora] += $atendidas_hora;
            $entradas_dia[$numero_dia_semana][$contador_dias_semana[$numero_dia_semana]] += $atendidas_hora;
            $qtd_total_atendidas[$numero_dia_semana][$hora] += $qtd_atendidas;
            $qtd_total_atendidas_dia[$numero_dia_semana][$contador_dias_semana[$numero_dia_semana]][$hora] += $qtd_atendidas;
            $hora++;            
        }
    }

    foreach ($entradas_dia as $dia_semana => $dia) {
        foreach ($dia as $key => $value) {
            if(!$auxiliar_menor_dia[$dia_semana] || $dia[$auxiliar_menor_dia[$dia_semana]] > $value){
                $auxiliar_menor_dia[$dia_semana] = $key;
            }
            if(!$auxiliar_maior_dia[$dia_semana] || $dia[$auxiliar_maior_dia[$dia_semana]] < $value){
                $auxiliar_maior_dia[$dia_semana] = $key;
            }
        }
    }

    $cont_dia = 0;
    while($cont_dia < 7){

        $cont_hora = 0;

        while($cont_hora < 24){
            if(!$total_entradas[$cont_dia][$cont_hora]){
                $total_entradas[$cont_dia][$cont_hora] = 0;
            }
            if(!$total_entradas_dia[$cont_dia][$auxiliar_maior_dia[$cont_dia]][$cont_hora]){
                $total_entradas_dia[$cont_dia][$auxiliar_maior_dia[$cont_dia]][$cont_hora] = 0;
            }
            if(!$total_entradas_dia[$cont_dia][$auxiliar_menor_dia[$cont_dia]][$cont_hora]){
                $total_entradas_dia[$cont_dia][$auxiliar_menor_dia[$cont_dia]][$cont_hora] = 0;
            }
            
            if($total_entradas_dia[$cont_dia][$auxiliar_menor_dia[$cont_dia]][$cont_hora] != 0){
                $tendencia[$cont_dia][$cont_hora] = ($total_entradas[$cont_dia][$cont_hora])/$qtd_atendimento_chat;
                $tendencia[$cont_dia][$cont_hora] = sprintf("%01.2f", $tendencia[$cont_dia][$cont_hora]);
                
            }else{
                $tendencia[$cont_dia][$cont_hora] = 0;
            }
            
            $cont_hora++;
        }       

        $cont_dia++;
    }

    $dias_semana = array('Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado');
    $data_hora = converteDataHora(getDataHora());
    $chart = 0;
    $atendentes_escala = array();
    $contador_dias_semana_escala = array();

    foreach(rangeDatas(converteData($data_de), converteData($data_ate)) as $data){
        $horas = array();
        $numero_dia_semana = date('w', strtotime($data));
        $hora = 0;

        $consulta_data = explode("-", $data);
        $consulta_data = $consulta_data[0].'-'.$consulta_data[1].'-01';
        $data_ontem = date('Y-m-d', strtotime("-1 days",strtotime($consulta_data)));
        $consulta_data_ontem = explode("-", $data_ontem);
        $consulta_data_ontem = $consulta_data_ontem[0].'-'.$consulta_data_ontem[1].'-01';
        $data_ontem = date('Y-m-d', strtotime("-1 days",strtotime($data)));

        $dia_hoje = date('w', strtotime($data));
        $dia_ontem = date('w', strtotime($data_ontem));

        $dia_hoje = $dias_semana[$dia_hoje];
        $dia_ontem = $dias_semana[$dia_ontem];
        $numero_dia_semana = date('w', strtotime($data));
        $hora = 0;
        $contador_dias_semana_escala[$numero_dia_semana]++;
        
        $aux_consulta_data = $consulta_data;
        
        while ($hora < 24) {
            $hora_zero = sprintf('%02d', $hora);
            $hora_proxima = sprintf('%02d', $hora_zero+1);  

            $cont_atendentes_central = 0;
            $cont_atendentes_escala = 0;

            $consulta_data = $aux_consulta_data;

            //início codigo atendentes escala
                
                //VERIFICA O DIA DA CONSULTA PARA PESQUISAR (SEG A SEX, SÁBADO OU DOMINGO)
                if($dia_hoje == 'Domingo'){
                    $consulta_dia =  "AND inicial_dom < '".$hora_proxima.":00' AND final_dom > '".$hora_zero.":00'";
                    
                    $consulta_inicial_banco = 'inicial_dom';
                    $consulta_final_banco = 'final_dom';

                    $consulta_inicial_banco_ontem = 'inicial_sab';
                    $consulta_final_banco_ontem = 'final_sab';

                    $consulta_ontem = "AND final_sab > '".$hora_zero.":00' AND inicial_sab > final_sab AND folga_seg != '".$dia_ontem."'";
                    $consulta_invertidos = "AND inicial_dom < '".$hora_proxima.":00' AND inicial_dom > final_dom AND id_horarios_escala NOT IN (SELECT id_horarios_escala FROM tb_folgas_dom WHERE dia = '".$data."')";
                    $consulta_normais = "AND inicial_dom < final_dom AND id_horarios_escala NOT IN (SELECT id_horarios_escala FROM tb_folgas_dom WHERE dia = '".$data."')";

                    $dia_intervalo = 'dom';
                    $dia_intervalo_ontem = 'sab';

                }else if($dia_hoje == 'Sábado'){
                    $consulta_dia =  "AND inicial_sab < '".$hora_proxima.":00' AND final_sab > '".$hora_zero.":00'";
                    $consulta_inicial_banco = 'inicial_sab';
                    $consulta_final_banco = 'final_sab';
                    
                    $consulta_inicial_banco_ontem = 'inicial_seg';
                    $consulta_final_banco_ontem = 'final_seg';

                    $consulta_ontem = "AND final_seg > '".$hora_zero.":00' AND inicial_seg > final_seg AND folga_seg != '".$dia_ontem."'";
                    $consulta_invertidos = "AND inicial_sab < '".$hora_proxima.":00' AND inicial_sab > final_sab AND folga_seg != '".$dia_hoje."'";
                    $consulta_normais = "AND inicial_sab < final_sab AND folga_seg != '".$dia_hoje."'";
                   
                    $dia_intervalo = 'sab';
                    $dia_intervalo_ontem = 'seg';
                }else{
                    $consulta_dia =  "AND inicial_seg < '".$hora_proxima.":00' AND final_seg > '".$hora_zero.":00'";
                    $consulta_inicial_banco = 'inicial_seg';
                    $consulta_final_banco = 'final_seg';

                    
                    if($dia_hoje == 'Quinta' || $dia_hoje == 'Sexta'){
                        $consulta_invertidos = "AND inicial_seg < '".$hora_proxima.":00' AND inicial_seg > final_seg AND (folga_seg != '".$dia_hoje."' AND folga_seg != 'Quinta e Sexta')";
                        $consulta_normais = "AND inicial_seg < final_seg AND (folga_seg != '".$dia_hoje."' AND folga_seg != 'Quinta e Sexta')";
                    }else{
                        $consulta_invertidos = "AND inicial_seg < '".$hora_proxima.":00' AND inicial_seg > final_seg AND folga_seg != '".$dia_hoje."'";
                        $consulta_normais = "AND inicial_seg < final_seg AND folga_seg != '".$dia_hoje."'";
                    }

                    if($dia_hoje == 'Segunda'){
                        $consulta_ontem = "AND final_dom > '".$hora_zero.":00' AND inicial_dom > final_dom AND id_horarios_escala NOT IN (SELECT id_horarios_escala FROM tb_folgas_dom WHERE dia = '".$data_ontem."')";
                        
                        $consulta_inicial_banco_ontem = 'inicial_dom';
                        $consulta_final_banco_ontem = 'final_dom';

                        $dia_intervalo_ontem = 'dom';
                    }else{
                        if($dia_hoje == 'Sexta'){
                            $consulta_ontem = "AND final_seg > '".$hora_zero.":00' AND inicial_seg > final_seg AND (folga_seg != 'Quinta e Sexta' AND folga_seg != '".$dia_ontem."')";
                        }else{
                            $consulta_ontem = "AND final_seg > '".$hora_zero.":00' AND inicial_seg > final_seg AND folga_seg != '".$dia_ontem."'";
                        }
                    
                        $consulta_inicial_banco_ontem = 'inicial_seg';
                        $consulta_final_banco_ontem = 'final_seg';
                    
                        $dia_intervalo_ontem = 'seg';
                    }
                    $dia_intervalo = 'seg';
                }
                                
                //NORMAIS               
                $dados_normais = DBRead('', 'tb_horarios_escala', "WHERE chat = 1 AND data_inicial = '".$consulta_data."' ".$consulta_dia." ".$consulta_normais." AND atendente = 1 AND id_usuario NOT IN (SELECT id_usuario FROM tb_ferias WHERE id_usuario = id_usuario AND data_de <= '".$data."' AND data_ate >= '".$data."') AND id_usuario NOT IN (SELECT a.id_usuario FROM tb_horarios_escala a INNER JOIN tb_horarios_especiais b ON a.id_horarios_escala = b.id_horarios_escala WHERE b.dia = '".$data."')");

                $dados_normais_especiais = DBRead('', 'tb_horarios_escala a', "INNER JOIN tb_horarios_especiais b ON a.id_horarios_escala = b.id_horarios_escala WHERE a.chat = 1 AND b.dia = '".$data."' AND b.inicial_especial < '".$hora_proxima.":00' AND b.final_especial > '".$hora_zero.":00' ");              
                
                //INVERTIDOS
                $dados_invertidos = DBRead('', 'tb_horarios_escala', "WHERE chat = 1 AND data_inicial = '".$consulta_data."' ".$consulta_invertidos." AND atendente = 1 AND id_usuario NOT IN (SELECT id_usuario FROM tb_ferias WHERE id_usuario = id_usuario AND data_de <= '".$data."' AND data_ate >= '".$data."') AND id_usuario NOT IN (SELECT a.id_usuario FROM tb_horarios_escala a INNER JOIN tb_horarios_especiais b ON a.id_horarios_escala = b.id_horarios_escala WHERE b.dia = '".$data."')");

                $dados_invertidos_especiais = DBRead('', 'tb_horarios_escala a', "INNER JOIN tb_horarios_especiais b ON a.id_horarios_escala = b.id_horarios_escala WHERE a.chat = 1 AND b.dia = '".$data."' AND b.inicial_especial < '".$hora_proxima.":00' AND b.inicial_especial > b.final_especial ");

                if($data == $consulta_data){
                    $consulta_data = $consulta_data_ontem;
                }

                //INVERTIDOS ONTEM
                $dados_invertidos_ontem = DBRead('', 'tb_horarios_escala', "WHERE chat = 1 AND data_inicial = '".$consulta_data."' ".$consulta_ontem." AND atendente = 1 AND id_usuario NOT IN (SELECT id_usuario FROM tb_ferias WHERE id_usuario = id_usuario AND data_de <= '".$data_ontem."' AND data_ate >= '".$data_ontem."') AND id_usuario NOT IN (SELECT a.id_usuario FROM tb_horarios_escala a INNER JOIN tb_horarios_especiais b ON a.id_horarios_escala = b.id_horarios_escala WHERE b.dia = '".$data_ontem."')");
                
                $dados_invertidos_ontem_especiais =  DBRead('', 'tb_horarios_escala a', "INNER JOIN tb_horarios_especiais b ON a.id_horarios_escala = b.id_horarios_escala WHERE a.chat = 1 AND b.dia = '".$data_ontem."' AND b.final_especial >= '".$hora_zero.":00' AND b.inicial_especial > b.final_especial ");

                //NORMAIS
                if($dados_normais){
                    foreach ($dados_normais as $conteudo_normais) {
                        $inicial_hora_funcionario = explode(":", $conteudo_normais[$consulta_inicial_banco]);
                        $final_hora_funcionario = explode(":", $conteudo_normais[$consulta_final_banco]);
                        
                        if(($inicial_hora_funcionario[0] <= $hora_zero) && ($final_hora_funcionario[0] >= $hora_zero)){
                        
                            $dados_intervalo_normais = DBRead('', 'tb_horarios_escala_intervalo', "WHERE id_horarios_escala = '".$conteudo_normais['id_horarios_escala']."' AND dia = '".$dia_intervalo."' ");

                            if(!$dados_intervalo_normais){
                                if($inicial_hora_funcionario[0] == $hora_zero && $inicial_hora_funcionario[1] != '00'){
                                    $valor_final = converteHorasDecimal('00:'.$inicial_hora_funcionario[1]);
                                    $cont_atendentes_escala += 1-(sprintf("%01.2f", round($valor_final, 2)));
                                }else if($final_hora_funcionario[0] == $hora_zero && $final_hora_funcionario[1] != '00'){
                                    $valor_final = converteHorasDecimal('00:'.$final_hora_funcionario[1]);
                                    $cont_atendentes_escala += sprintf("%01.2f", round($valor_final, 2));
                                }else{
                                    $cont_atendentes_escala += 1;
                                }
                            }else{
                                if($dados_intervalo_normais[0]['tipo_intervalo'] == 1){
                                    $data_inicio_compara = date('Y-m-d H:i:s', strtotime("+0 minutes",strtotime($conteudo_normais[$consulta_inicial_banco])));
                                    $data_fim_compara = date('Y-m-d H:i:s', strtotime("+0 minutes",strtotime($conteudo_normais[$consulta_final_banco])));
                                    
                                    if($data_inicio_compara > $data_fim_compara){
                                        $data_inicio_compara = date('Y-m-d H:i:s', strtotime("-1 days",strtotime($conteudo_normais[$consulta_inicial_banco])));
                                    }

                                    $diferenca_datas = strtotime($data_fim_compara) - strtotime($data_inicio_compara);
		
                                    $tempo_total_trabalho = converteSegundosHoras($diferenca_datas);
                                    $tempo_total_trabalho = converteHorasDecimal($tempo_total_trabalho);
    
                                    $tempo_total_intervalo = converteHorasDecimal($dados_intervalo_normais[0]['tempo_intervalo']);
    
                                    $tempo_desconto = $tempo_total_intervalo/$tempo_total_trabalho;
    
                                    if($inicial_hora_funcionario[0] == $hora_zero && $inicial_hora_funcionario[1] != '00'){
                                        $valor_final = converteHorasDecimal('00:'.$inicial_hora_funcionario[1]);
                                        $cont_atendentes_escala += (1 - ((sprintf("%01.2f", round($valor_final, 2))))) - ($tempo_desconto * (1 - ((sprintf("%01.2f", round($valor_final, 2))))));
                            
                                    }else if($final_hora_funcionario[0] == $hora_zero && $final_hora_funcionario[1] != '00'){
                                        $valor_final = converteHorasDecimal('00:'.$final_hora_funcionario[1]);
                                        $cont_atendentes_escala += (((sprintf("%01.2f", round($valor_final, 2)))) - ($tempo_desconto * (sprintf("%01.2f", round($valor_final, 2)))));
                            
                                    }else{
                                        $cont_atendentes_escala += 1 - $tempo_desconto;
                                    } 
                                }else{
                                    $inicial_hora_intervalo = explode(":", $dados_intervalo_normais[0]['intervalo_inicial']);
                                    $final_hora_intervalo = explode(":", $dados_intervalo_normais[0]['intervalo_final']);

                                    if($inicial_hora_funcionario[0] == $hora_zero && $inicial_hora_funcionario[1] != '00'){

                                        if($inicial_hora_intervalo[0] == $hora_zero && $final_hora_intervalo[0] == $hora_zero){
                                            //intervalo 09:00 até 09:30
                                            $valor_final = converteHorasDecimal('00:'.$inicial_hora_funcionario[1]);
                                            $desconto_intervalo = converteHorasDecimal('00:'.($final_hora_intervalo[1] - $inicial_hora_intervalo[1]));

                                            $cont_atendentes_escala += (1 - (sprintf("%01.2f", round($valor_final, 2)))) - ($desconto_intervalo);

                                        }else if($inicial_hora_intervalo[0] == $hora_zero){
                                            //intervalo 09:30 até 10:30
                                            $valor_final = converteHorasDecimal('00:'.$inicial_hora_funcionario[1]);
                                            $desconto_intervalo =  converteHorasDecimal('00:'.$inicial_hora_intervalo[1]);

                                            $cont_atendentes_escala += (sprintf("%01.2f", round($valor_final, 2)) - (1 - $desconto_intervalo));

                                        }else{
                                            $valor_final = converteHorasDecimal('00:'.$inicial_hora_funcionario[1]);
                                            $cont_atendentes_escala += 1 - (sprintf("%01.2f", round($valor_final, 2)));
                                        }

                                    }else if($final_hora_funcionario[0] == $hora_zero && $final_hora_funcionario[1] != '00'){
                                        if($inicial_hora_intervalo[0] == $hora_zero && $final_hora_intervalo[0] == $hora_zero){
                                            //intervalo 09:00 até 09:30
                                            $valor_final = converteHorasDecimal('00:'.$final_hora_funcionario[1]);
                                            $desconto_intervalo = converteHorasDecimal('00:'.($final_hora_intervalo[1] - $inicial_hora_intervalo[1]));

                                            $cont_atendentes_escala += 1 - (sprintf("%01.2f", round($valor_final, 2)) + $desconto_intervalo);

                                        }else if($final_hora_intervalo[0] == $hora_zero){
                                            //intervalo 08:30 até 09:30
                                            $valor_final = converteHorasDecimal('00:'.$final_hora_funcionario[1]);
                                            $desconto_intervalo = converteHorasDecimal('00:'.$final_hora_intervalo[1]);
                                            
                                            $cont_atendentes_escala += (sprintf("%01.2f", round($valor_final, 2)) - $desconto_intervalo);
                                            
                                        }else{
                                            $valor_final = converteHorasDecimal('00:'.$final_hora_funcionario[1]);
                                            $cont_atendentes_escala += sprintf("%01.2f", round($valor_final, 2));
                                        }
                                    }else{
                                        if($inicial_hora_intervalo[0] == $hora_zero && $final_hora_intervalo[0] == $hora_zero){
                                            //intervalo 09:00 até 09:30
                                            $valor_final = converteHorasDecimal('00:'.$inicial_hora_funcionario[1]);
                                            $desconto_intervalo = converteHorasDecimal('00:'.($final_hora_intervalo[1] - $inicial_hora_intervalo[1]));

                                            $cont_atendentes_escala += 1 - $desconto_intervalo;

                                        }else if($inicial_hora_intervalo[0] == $hora_zero){
                                            //intervalo 09:30 até 10:30
                                            $valor_final = converteHorasDecimal('00:'.$inicial_hora_funcionario[1]);
                                            $desconto_intervalo = converteHorasDecimal('00:'.$inicial_hora_intervalo[1]);

                                            $cont_atendentes_escala += $desconto_intervalo;

                                        }else if($final_hora_intervalo[0] == $hora_zero){
                                            //intervalo 08:30 até 09:30
                                            $desconto_intervalo = converteHorasDecimal('00:'.$final_hora_intervalo[1]);
                                            
                                            $cont_atendentes_escala += 1 - $desconto_intervalo;
                                            
                                        }else if($inicial_hora_intervalo[0] < $hora_zero && $final_hora_intervalo[0] > $hora_zero){
                                            //intervalo 08:30 até 10:30
                                            $cont_atendentes_escala += 0;

                                        }else{
                                            //intervalo antes ou depois do horarios
                                            $cont_atendentes_escala += 1;

                                        }
                                    }
                                }
                            }
                        }
                    }                                       
                }

                if($dados_normais_especiais){
                    foreach ($dados_normais_especiais as $conteudo_normais_especiais) {
                        $inicial_hora_funcionario = explode(":", $conteudo_normais_especiais['inicial_especial']);
                        $final_hora_funcionario = explode(":", $conteudo_normais_especiais['final_especial']);
                        
                        if(($inicial_hora_funcionario[0] <= $hora_zero) && ($final_hora_funcionario[0] >= $hora_zero)){
                            
                            if(!$conteudo_normais_especiais['tempo_intervalo']){
                                if($inicial_hora_funcionario[0] == $hora_zero && $inicial_hora_funcionario[1] != '00'){
                                    $valor_final = converteHorasDecimal('00:'.$inicial_hora_funcionario[1]);
                                    $cont_atendentes_escala += 1-(sprintf("%01.2f", round($valor_final, 2)));
                                }else if($final_hora_funcionario[0] == $hora_zero && $final_hora_funcionario[1] != '00'){
                                    $valor_final = converteHorasDecimal('00:'.$final_hora_funcionario[1]);
                                    $cont_atendentes_escala += sprintf("%01.2f", round($valor_final, 2));
                                }else{
                                    $cont_atendentes_escala += 1;
                                }
                            }else{
                                $data_inicio_compara = date('Y-m-d H:i:s', strtotime("+0 minutes",strtotime($conteudo_normais_especiais['inicial_especial'])));
                                $data_fim_compara = date('Y-m-d H:i:s', strtotime("+0 minutes",strtotime($conteudo_normais_especiais['final_especial'])));
                            
                                if($data_inicio_compara > $data_fim_compara){
                                    $data_inicio_compara = date('Y-m-d H:i:s', strtotime("-1 days",strtotime($conteudo_normais_especiais['inicial_especial'])));
                                }
                            
                                $diferenca_datas = strtotime($data_fim_compara) - strtotime($data_inicio_compara);
                                
                                $tempo_total_trabalho = converteSegundosHoras($diferenca_datas);
                                $tempo_total_trabalho = converteHorasDecimal($tempo_total_trabalho);
                                $tempo_total_intervalo = converteHorasDecimal($conteudo_normais_especiais['tempo_intervalo']);
                                $tempo_desconto = $tempo_total_intervalo/$tempo_total_trabalho;
                            
                                if($inicial_hora_funcionario[0] == $hora_zero && $inicial_hora_funcionario[1] != '00'){
                                    $valor_final = converteHorasDecimal('00:'.$inicial_hora_funcionario[1]);
                                    $cont_atendentes_escala += (1 - ((sprintf("%01.2f", round($valor_final, 2))))) - ($tempo_desconto * (1 - ((sprintf("%01.2f", round($valor_final, 2))))));
                                
                                }else if($final_hora_funcionario[0] == $hora_zero && $final_hora_funcionario[1] != '00'){
                                    $valor_final = converteHorasDecimal('00:'.$final_hora_funcionario[1]);
                                    $cont_atendentes_escala += (((sprintf("%01.2f", round($valor_final, 2)))) - ($tempo_desconto * (sprintf("%01.2f", round($valor_final, 2)))));
                                
                                }else{
                                    $cont_atendentes_escala += 1 - $tempo_desconto;
                                }
                            }
                        }
                    }                                       
                }

                //INVERTIDOS
                
                if($dados_invertidos){
                    
                    foreach ($dados_invertidos as $conteudo_invertido) {
                        $inicial_hora_funcionario = explode(":", $conteudo_invertido[$consulta_inicial_banco]);

                        if($inicial_hora_funcionario[0] <= $hora_zero){

                            $dados_intervalo_invertido = DBRead('', 'tb_horarios_escala_intervalo', "WHERE id_horarios_escala = '".$conteudo_invertido['id_horarios_escala']."' AND dia = '".$dia_intervalo."' ");

                            if(!$dados_intervalo_invertido){
                                if($inicial_hora_funcionario[0] == $hora_zero && $inicial_hora_funcionario[1] != '00'){
                                    $valor_final = converteHorasDecimal('00:'.$inicial_hora_funcionario[1]);
                                    $cont_atendentes_escala += 1-(sprintf("%01.2f", round($valor_final, 2)));
                                }else{
                                    $cont_atendentes_escala += 1;
                                } 
                            }else{
                                if($dados_intervalo_invertido[0]['tipo_intervalo'] == 1){

                                    $data_inicio_compara = date('Y-m-d H:i:s', strtotime("+0 minutes",strtotime($conteudo_invertido[$consulta_inicial_banco])));
                                    $data_fim_compara = date('Y-m-d H:i:s', strtotime("+0 minutes",strtotime($conteudo_invertido[$consulta_final_banco])));
                            
                                    if($data_inicio_compara > $data_fim_compara){
                                        $data_inicio_compara = date('Y-m-d H:i:s', strtotime("-1 days",strtotime($conteudo_invertido[$consulta_inicial_banco])));
                                    }
                                    
                                    $diferenca_datas = strtotime($data_fim_compara) - strtotime($data_inicio_compara);
                                    
                                    $tempo_total_trabalho = converteSegundosHoras($diferenca_datas);
                                    $tempo_total_trabalho = converteHorasDecimal($tempo_total_trabalho);
                                    $tempo_total_intervalo = converteHorasDecimal($dados_intervalo_invertido[0]['tempo_intervalo']);
                                    $tempo_desconto = $tempo_total_intervalo/$tempo_total_trabalho;
                            
                                    if($inicial_hora_funcionario[0] == $hora_zero && $inicial_hora_funcionario[1] != '00'){
                                        $valor_final = converteHorasDecimal('00:'.$inicial_hora_funcionario[1]);
                                        $cont_atendentes_escala += (1 - ((sprintf("%01.2f", round($valor_final, 2))))) - ($tempo_desconto * (1 - ((sprintf("%01.2f", round($valor_final, 2))))));
                            
                                    }else{
                                        $cont_atendentes_escala += 1 - $tempo_desconto;
                                    } 
                                }else{	
	
                                    $inicial_hora_intervalo = explode(":", $dados_intervalo_invertido[0]['intervalo_inicial']);
                                    $final_hora_intervalo = explode(":", $dados_intervalo_invertido[0]['intervalo_final']);
                                
                                    if($inicial_hora_funcionario[0] == $hora_zero && $inicial_hora_funcionario[1] != '00'){
                                
                                        if($inicial_hora_intervalo[0] == $hora_zero && $final_hora_intervalo[0] == $hora_zero){
                                            //intervalo 09:00 até 09:30
                                            $valor_final = converteHorasDecimal('00:'.$inicial_hora_funcionario[1]);
                                            $desconto_intervalo = converteHorasDecimal('00:'.($final_hora_intervalo[1] - $inicial_hora_intervalo[1]));
                                            $cont_atendentes_escala += (1 - (sprintf("%01.2f", round($valor_final, 2)))) - ($desconto_intervalo);
                                
                                        }else if($inicial_hora_intervalo[0] == $hora_zero){
                                            //intervalo 09:30 até 10:30
                                            $valor_final = converteHorasDecimal('00:'.$inicial_hora_funcionario[1]);
                                            $desconto_intervalo =  converteHorasDecimal('00:'.$inicial_hora_intervalo[1]);
                                            $cont_atendentes_escala += (sprintf("%01.2f", round($valor_final, 2)) - (1 - $desconto_intervalo));
                                
                                        }else{
                                            $valor_final = converteHorasDecimal('00:'.$inicial_hora_funcionario[1]);
                                            $cont_atendentes_escala += 1 - (sprintf("%01.2f", round($valor_final, 2)));
                                        }
                                
                                    }else{
                                        if($inicial_hora_intervalo[0] == $hora_zero && $final_hora_intervalo[0] == $hora_zero){
                                            //intervalo 09:00 até 09:30
                                            $valor_final = converteHorasDecimal('00:'.$inicial_hora_funcionario[1]);
                                            $desconto_intervalo = converteHorasDecimal('00:'.($final_hora_intervalo[1] - $inicial_hora_intervalo[1]));
                                            $cont_atendentes_escala += 1 - $desconto_intervalo;
                                
                                        }else if($inicial_hora_intervalo[0] == $hora_zero){
                                            //intervalo 09:30 até 10:30
                                            $valor_final = converteHorasDecimal('00:'.$inicial_hora_funcionario[1]);
                                            $desconto_intervalo = converteHorasDecimal('00:'.$inicial_hora_intervalo[1]);
                                            $cont_atendentes_escala += $desconto_intervalo;
                                
                                        }else if($final_hora_intervalo[0] == $hora_zero){
                                            //intervalo 08:30 até 09:30
                                            $desconto_intervalo = converteHorasDecimal('00:'.$final_hora_intervalo[1]);
                                            $cont_atendentes_escala += 1 - $desconto_intervalo;
                                            
                                        }else if($inicial_hora_intervalo[0] < $hora_zero && $final_hora_intervalo[0] > $hora_zero){
                                            //intervalo 08:30 até 10:30
                                            $cont_atendentes_escala += 0;
                                
                                        }else{
                                            //intervalo antes ou depois do horarios
                                            $cont_atendentes_escala += 1;
                                
                                        }
                                    }
                                }
                            }
                        }
                    }
                }

                if($dados_invertidos_especiais){
                    
                    foreach ($dados_invertidos_especiais as $conteudo_invertidos_especiais) {
                        $inicial_hora_funcionario = explode(":", $conteudo_invertidos_especiais['inicial_especial']);

                        if($inicial_hora_funcionario[0] <= $hora_zero){
                            
                            if(!$conteudo_invertidos_especiais['tempo_intervalo']){
                                if($inicial_hora_funcionario[0] == $hora_zero && $inicial_hora_funcionario[1] != '00'){
                                    $valor_final = converteHorasDecimal('00:'.$inicial_hora_funcionario[1]);
                                    $cont_atendentes_escala += 1-(sprintf("%01.2f", round($valor_final, 2)));
                                }else{
                                    $cont_atendentes_escala += 1;
                                }
                            }else{
                                $data_inicio_compara = date('Y-m-d H:i:s', strtotime("+0 minutes",strtotime($conteudo_invertidos_especiais['inicial_especial'])));
                                $data_fim_compara = date('Y-m-d H:i:s', strtotime("+0 minutes",strtotime($conteudo_invertidos_especiais['final_especial'])));
                            
                                if($data_inicio_compara > $data_fim_compara){
                                    $data_inicio_compara = date('Y-m-d H:i:s', strtotime("-1 days",strtotime($conteudo_invertidos_especiais['inicial_especial'])));
                                }
                            
                                $diferenca_datas = strtotime($data_fim_compara) - strtotime($data_inicio_compara);
                                
                                $tempo_total_trabalho = converteSegundosHoras($diferenca_datas);
                                $tempo_total_trabalho = converteHorasDecimal($tempo_total_trabalho);
                                $tempo_total_intervalo = converteHorasDecimal($conteudo_invertidos_especiais['tempo_intervalo']);
                                $tempo_desconto = $tempo_total_intervalo/$tempo_total_trabalho;
                            
                                if($inicial_hora_funcionario[0] == $hora_zero && $inicial_hora_funcionario[1] != '00'){
                                    $valor_final = converteHorasDecimal('00:'.$inicial_hora_funcionario[1]);
                                    $cont_atendentes_escala += (1 - ((sprintf("%01.2f", round($valor_final, 2))))) - ($tempo_desconto * (1 - ((sprintf("%01.2f", round($valor_final, 2))))));
                                }else{
                                    $cont_atendentes_escala += 1 - $tempo_desconto;
                                }
                            }
                        }
                    }
                }

                //INVERTIDOS ONTEM
                if($dados_invertidos_ontem){

                    foreach ($dados_invertidos_ontem as $conteudo_ontem) {
                        $final_hora_funcionario = explode(":", $conteudo_ontem[$consulta_final_banco_ontem]);

                        if($final_hora_funcionario[0] >= $hora_zero){

                            $dados_intervalo_invertido_ontem = DBRead('', 'tb_horarios_escala_intervalo', "WHERE id_horarios_escala = '".$conteudo_ontem['id_horarios_escala']."' AND dia = '".$dia_intervalo_ontem."' ");

                            if(!$dados_intervalo_invertido_ontem){
                                if($final_hora_funcionario[0] == $hora_zero && $final_hora_funcionario[1] != '00'){
                                    $valor_final = converteHorasDecimal('00:'.$final_hora_funcionario[1]);
                                    $cont_atendentes_escala += sprintf("%01.2f", round($valor_final, 2));
                                }else{
                                    $cont_atendentes_escala += 1;
                                }  
                            }else{
                                if($dados_intervalo_invertido_ontem[0]['tipo_intervalo'] == 1){
                            
                                    $data_inicio_compara = date('Y-m-d H:i:s', strtotime("+0 minutes",strtotime($conteudo_ontem[$consulta_inicial_banco_ontem])));
                                    $data_fim_compara = date('Y-m-d H:i:s', strtotime("+0 minutes",strtotime($conteudo_ontem[$consulta_final_banco_ontem])));
                            
                                    if($data_inicio_compara > $data_fim_compara){
                                        $data_inicio_compara = date('Y-m-d H:i:s', strtotime("-1 days",strtotime($conteudo_ontem[$consulta_inicial_banco_ontem])));
                                    }
                                    
                                    $diferenca_datas = strtotime($data_fim_compara) - strtotime($data_inicio_compara);
                                    $tempo_total_trabalho = converteSegundosHoras($diferenca_datas);
                                    $tempo_total_trabalho = converteHorasDecimal($tempo_total_trabalho);
                                    $tempo_total_intervalo = converteHorasDecimal($dados_intervalo_invertido_ontem[0]['tempo_intervalo']);
                                    $tempo_desconto = $tempo_total_intervalo/$tempo_total_trabalho;
                            
                                    if($final_hora_funcionario[0] == $hora_zero && $final_hora_funcionario[1] != '00'){
                                        $valor_final = converteHorasDecimal('00:'.$final_hora_funcionario[1]);
                                        $cont_atendentes_escala += (((sprintf("%01.2f", round($valor_final, 2)))) - ($tempo_desconto * (sprintf("%01.2f", round($valor_final, 2)))));
                            
                                    }else{
                                        $cont_atendentes_escala += 1 - $tempo_desconto;
                                    } 
                                }else{	
	
                                    $inicial_hora_intervalo = explode(":", $dados_intervalo_invertido_ontem[0]['intervalo_inicial']);
                                    $final_hora_intervalo = explode(":", $dados_intervalo_invertido_ontem[0]['intervalo_final']);
                                
                                    if($final_hora_funcionario[0] == $hora_zero && $final_hora_funcionario[1] != '00'){
                                        if($inicial_hora_intervalo[0] == $hora_zero && $final_hora_intervalo[0] == $hora_zero){
                                            //intervalo 09:00 até 09:30
                                            $valor_final = converteHorasDecimal('00:'.$final_hora_funcionario[1]);
                                            $desconto_intervalo = converteHorasDecimal('00:'.($final_hora_intervalo[1] - $inicial_hora_intervalo[1]));
                                            $cont_atendentes_escala += 1 - (sprintf("%01.2f", round($valor_final, 2)) + $desconto_intervalo);
                                
                                        }else if($final_hora_intervalo[0] == $hora_zero){
                                            //intervalo 08:30 até 09:30
                                            $valor_final = converteHorasDecimal('00:'.$final_hora_funcionario[1]);
                                            $desconto_intervalo = converteHorasDecimal('00:'.$final_hora_intervalo[1]);
                                            $cont_atendentes_escala += (sprintf("%01.2f", round($valor_final, 2)) - $desconto_intervalo);
                                            
                                        }else{
                                            $valor_final = converteHorasDecimal('00:'.$final_hora_funcionario[1]);
                                            $cont_atendentes_escala += sprintf("%01.2f", round($valor_final, 2));
                                        }
                                    }else{
                                        if($inicial_hora_intervalo[0] == $hora_zero && $final_hora_intervalo[0] == $hora_zero){
                                            //intervalo 09:00 até 09:30
                                            $valor_final = converteHorasDecimal('00:'.$inicial_hora_funcionario[1]);
                                            $desconto_intervalo = converteHorasDecimal('00:'.($final_hora_intervalo[1] - $inicial_hora_intervalo[1]));
                                            $cont_atendentes_escala += 1 - $desconto_intervalo;
                                
                                        }else if($inicial_hora_intervalo[0] == $hora_zero){
                                            //intervalo 09:30 até 10:30
                                            $valor_final = converteHorasDecimal('00:'.$inicial_hora_funcionario[1]);
                                            $desconto_intervalo = converteHorasDecimal('00:'.$inicial_hora_intervalo[1]);
                                            $cont_atendentes_escala += $desconto_intervalo;
                                
                                        }else if($final_hora_intervalo[0] == $hora_zero){
                                            //intervalo 08:30 até 09:30
                                            $desconto_intervalo = converteHorasDecimal('00:'.$final_hora_intervalo[1]);
                                            $cont_atendentes_escala += 1 - $desconto_intervalo;
                                            
                                        }else if($inicial_hora_intervalo[0] < $hora_zero && $final_hora_intervalo[0] > $hora_zero){
                                            //intervalo 08:30 até 10:30
                                            $cont_atendentes_escala += 0;
                                
                                        }else{
                                            //intervalo antes ou depois do horarios
                                            $cont_atendentes_escala += 1;
                                
                                        }
                                    }
                                }
                            }
                        }
                    }
                }

                if($dados_invertidos_ontem_especiais){

                    foreach ($dados_invertidos_ontem_especiais as $conteudo_invertidos_ontem_especiais) {
                        $final_hora_funcionario = explode(":", $conteudo_invertidos_ontem_especiais['final_especial']);
                        if($final_hora_funcionario[0] >= $hora_zero){

                            if(!$conteudo_invertidos_ontem_especiais['tempo_intervalo']){
                                if($final_hora_funcionario[0] == $hora_zero && $final_hora_funcionario[1] != '00'){
                                    $valor_final = converteHorasDecimal('00:'.$final_hora_funcionario[1]);
                                    $cont_atendentes_escala += sprintf("%01.2f", round($valor_final, 2));
                                }else{
                                    $cont_atendentes_escala += 1;
                                }
                            }else{
                                $data_inicio_compara = date('Y-m-d H:i:s', strtotime("+0 minutes",strtotime($conteudo_invertidos_ontem_especiais['inicial_especial'])));
                                $data_fim_compara = date('Y-m-d H:i:s', strtotime("+0 minutes",strtotime($conteudo_invertidos_ontem_especiais['final_especial'])));
                            
                                if($data_inicio_compara > $data_fim_compara){
                                    $data_inicio_compara = date('Y-m-d H:i:s', strtotime("-1 days",strtotime($conteudo_invertidos_ontem_especiais['inicial_especial'])));
                                }
                            
                                $diferenca_datas = strtotime($data_fim_compara) - strtotime($data_inicio_compara);
                                
                                $tempo_total_trabalho = converteSegundosHoras($diferenca_datas);
                                $tempo_total_trabalho = converteHorasDecimal($tempo_total_trabalho);
                                $tempo_total_intervalo = converteHorasDecimal($conteudo_invertidos_ontem_especiais['tempo_intervalo']);
                                $tempo_desconto = $tempo_total_intervalo/$tempo_total_trabalho;
                            
                                if($final_hora_funcionario[0] == $hora_zero && $final_hora_funcionario[1] != '00'){
                                    $valor_final = converteHorasDecimal('00:'.$final_hora_funcionario[1]);
                                    $cont_atendentes_escala += (((sprintf("%01.2f", round($valor_final, 2)))) - ($tempo_desconto * (sprintf("%01.2f", round($valor_final, 2)))));
                                
                                }else{
                                    $cont_atendentes_escala += 1 - $tempo_desconto;
                                }
                            }
                        }
                    }
                }

                if(!$cont_atendentes_escala){
                    $cont_atendentes_escala = 0;
                }

            //fim codigo atendentes escala
            
            //Adiciona previsao

            $diasemana = array(
                "0" => "Domingo",
                "1" => "Segunda",
                "2" => "Terça",
                "3" => "Quarta",
                "4" => "Quinta",
                "5" => "Sexta",
                "6" => "Sábado",
            );

            $data_de_consulta = date('Y-m-d', strtotime($data));
            $diasemana_numero = date('w', strtotime($data_de_consulta));
            $atendentes_escala[$diasemana_numero][$hora] += sprintf("%01.2f", round($cont_atendentes_escala, 2));
            $hora++;
        }
    }
    $dia = 0;
        while ($dia < 7) {
            $hora = 0;
            while($hora < 24){
                if($contador_dias_semana_escala[$dia] && $contador_dias_semana_escala[$dia] != '0'){
                    $atendentes_escala[$dia][$hora] = sprintf("%01.2f", ($atendentes_escala[$dia][$hora]/$contador_dias_semana_escala[$dia]));
                }else{
                    $atendentes_escala[$dia][$hora] = '0';
                }
                $hora++;
            }
        $dia++;
        }
   
    $chart = 0;
    while ($chart < 7) {?>
        
        <div id="<?php echo "chart-" . $chart; ?>"></div> 
        <script>
            $(function () {
                // Create the first chart
                $('#<?php echo "chart-" . $chart; ?>').highcharts({
                    chart: {
                        type: '<?=$nome_tipo_grafico;?>'
                    },
                    title: {
                        text: '<?=$dias_semana[$chart];?>' // Title for the chart
                    },
                    xAxis: {
                        categories: <?php echo json_encode($horas) ?>
                        // Categories for the charts
                    },
                    yAxis: {
                        min: 0,
                        title: {
                            text: 'Ligações por Hora'
                        },
                        stackLabels: {
                            enabled: true,
                            style: {
                                fontWeight: 'bold',
                                color: (Highcharts.theme && Highcharts.theme.textColor) || 'black'
                            }
                        }
                    },
                    legend: {
                        enabled:true,
                        backgroundColor: (Highcharts.theme && Highcharts.theme.background2) || 'white',
                        borderColor: '#CCC',
                        borderWidth: 1,
                        shadow: false
                    },
                    tooltip: {
                        headerFormat: '<b>{point.x}</b><br/>',
                        pointFormat: '{series.name}: {point.y}<br/>Total: {point.stackTotal}'
                    },
                    plotOptions: {
                                                        
                        series: {
                            dataLabels: {
                                enabled: true
                            }
                        }
                    },
                    series: [
                        {
                            name: 'Capacidade da escala', // Name of your series
                            data: <?php echo json_encode($atendentes_escala[$chart], JSON_NUMERIC_CHECK); ?> // The data in your series

                        },
                        {
                            name: 'Capacidade necessária', // Name of your series
                            data: <?php echo json_encode($tendencia[$chart], JSON_NUMERIC_CHECK) ?> // The data in your series

                        }
                    ],
                    navigation: {
                        buttonOptions: {
                            enabled: true
                        }
                    }
                });
            });
        </script>  
        <?php
        echo '<hr>';
        $chart++;
        
    }
}

function relatorio_horarios($var_mes, $var_ano, $usuario, $dia_folgas, $dia_folga_domingo, $horario_inicial, $horario_final, $turno, $expediente, $status, $lider){

    $data_consulta = $var_ano."-".$var_mes."-01";
    if($var_mes == 12){
        $data_consulta_proximo = ((int)$var_ano+1)."-01"."-01";
    }else{
        $data_consulta_proximo = $var_ano."-".sprintf('%02d', ((int)$var_mes+1))."-01";
    }

    $data_hoje = getDataHora();
    $data_hoje = converteDataHora($data_hoje);
    $meses = array(
        "01" => "Janeiro",
        "02" => "Fevereiro",
        "03" => "Março",
        "04" => "Abril",
        "05" => "Maio",
        "06" => "Junho",
        "07" => "Julho",
        "08" => "Agosto",
        "09" => "Setembro",
        "10" => "Outubro",
        "11" => "Novembro",
        "12" => "Dezembro",
    );
    if($lider){
        $dados_lider = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON b.id_pessoa = a.id_pessoa WHERE a.id_usuario = '".$lider."'");
        $lider_legend = "<strong> Líder Direto - </strong>".$dados_lider[0]['nome'];
        $filtro_lider = "AND a.lider_direto = '".$lider."'";
    }else{
        $lider_legend = "<strong> Líder Direto - </strong>Todos";
    }
    if($status == 1){
        $filtro_status = "AND c.data_lido ";
        $legenda_status = 'Ciente';
    }else if($status == 2){
        $legenda_status = 'Não Ciente';
        $filtro_status = "AND c.data_lido is null ";
    }else{
        $legenda_status = 'Qualquer';
    }
    if($expediente == 1){
            $legenda_expediente = 'Segunda a sexta';
    }else if($expediente == 2){
            $legenda_expediente = 'Sábado';
    }else if($expediente == 3){
            $legenda_expediente = 'Domingo';
    }else{
            $legenda_expediente = 'Todos';
    }
    if($horario_inicial){
        if($horario_inicial < 10){
            $legenda_horario_inicial = '0'.$horario_inicial.':00';
        }else{
            $legenda_horario_inicial = $horario_inicial.':00';
        }
    }else{
        $legenda_horario_inicial = 'Qualquer';
    }
    if($horario_final){
        if($horario_final < 10){
            $legenda_horario_final = '0'.$horario_final.':00';
        }else{
            $legenda_horario_final = $horario_final.':00';
        }
    }else{
        $legenda_horario_final = 'Qualquer';
    }
    if($turno == 1){
        $legenda_turno = 'Integral';
    }else if($turno == 2){
        $legenda_turno = 'Meio Turno';
    }else if($turno == 3){
        $legenda_turno = 'Jovem Aprendiz';
    }else if($turno == 4){
        $legenda_turno = 'Estágio';
    }else{
        $legenda_turno = 'Qualquer';
    }
    if(!$usuario){
        $filtro_usuario = "";
        $legenda_atendente = "Todos";
    }else{
        $nome = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_usuario = '".$usuario."'", "b.nome");
        $filtro_usuario = "AND a.id_usuario = '".$usuario."'";
        $legenda_atendente = $nome[0]['nome'];
    }
    
    if($dia_folgas == "Todos"){
        $filtro_folgas = "";
        $legenda_folga_segunda = 'Todos';
    }else{
        if($dia_folgas == 'Quinta' || $dia_folgas == 'Sexta'){
            $filtro_folgas = "AND (c.folga_seg = '".$dia_folgas."' OR c.folga_seg = 'Quinta e Sexta')";
        }else{
            $filtro_folgas = "AND c.folga_seg = '".$dia_folgas."' ";
        }
        $legenda_folga_segunda = $dia_folgas;
    }
    if($dia_folga_domingo != ""){
        $dia_folga_domingo = converteDataHora($dia_folga_domingo);
        $filtro_folga_domingo = "AND d.dia = '".$dia_folga_domingo."'";
        $legenda_folga_domingo = converteDataHora($dia_folga_domingo);
    }else{
        $legenda_folga_domingo = 'Qualquer';
    }
    if($expediente == 0){
        $filtro_expediente = "";
    }else if($expediente == 1){
        if($horario_inicial && $horario_final){
            if($horario_inicial >= $horario_final){
                $filtro_expediente = "AND c.inicial_seg >= '".$legenda_horario_inicial."' ";
            }else{
                $filtro_expediente = "AND c.inicial_seg >= '".$legenda_horario_inicial."' AND c.final_seg <= '".$legenda_horario_final."' ";
            }
        }else if($horario_inicial && !$horario_final){
            $filtro_expediente = "AND c.inicial_seg >= '".$legenda_horario_inicial."' ";
        }else{
            $filtro_expediente = "AND c.final_seg <= '".$legenda_horario_final."' ";
        }
    }else if($expediente == 2){
        if($horario_inicial && $horario_final){
            if($horario_inicial >= $horario_final){
                $filtro_expediente = "AND c.inicial_sab >= '".$legenda_horario_inicial."' ";
            }else{
                $filtro_expediente = "AND c.inicial_sab >= '".$legenda_horario_inicial."' AND c.final_sab <= '".$legenda_horario_final."' ";
            }
        }else if($horario_inicial && !$horario_final){
            $filtro_expediente = "AND c.inicial_sab >= '".$legenda_horario_inicial."' ";
        }else{
            $filtro_expediente = "AND c.final_sab <= '".$legenda_horario_final."' ";
        }
    }else if($expediente == 3){
        if($horario_inicial && $horario_final){
            if($horario_inicial >= $horario_final){
                $filtro_expediente = "AND c.inicial_dom >= '".$legenda_horario_inicial."' ";
            }else{
                $filtro_expediente = "AND c.inicial_dom >= '".$legenda_horario_inicial."' AND c.final_dom <= '".$legenda_horario_final."'";
            }
        }else if($horario_inicial && !$horario_final){
            $filtro_expediente = "AND c.inicial_dom >= '".$legenda_horario_inicial."' ";
        }else{
            $filtro_expediente = "AND c.final_dom <= '".$legenda_horario_final."' ";
        }
    }

    $periodo_amostra = "<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> $meses[$var_mes] de $var_ano</span>";
    $gerado = "<span style=\"font-size: 14px;\"><strong>Gerado em: </strong> $data_hoje</span>";

    echo "<div class=\"col-md-12\" style=\"padding: 0\">";
    echo "<legend style=\"text-align:center;\"><strong>Relatório de Horarios</strong><br><span style=\"font-size: 14px;\">$gerado</legend>";
    echo "<legend style=\"text-align:center;\">$periodo_amostra</legend>";
    echo "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong> Dia de Folga - </strong>".$legenda_folga_segunda.", <strong>Domingo de Folga - </strong>".$legenda_folga_domingo.", <strong> Turno/Horário - </strong>".$legenda_turno.", <strong>Horário Inicial - </strong>".$legenda_horario_inicial.", <strong>Horário Final - </strong>".$legenda_horario_final.", <strong>Dia - </strong>".$legenda_expediente.", <strong>Atendente - </strong>".$legenda_atendente.", <strong>Status - </strong>".$legenda_status.", ".$lider_legend." ";
    echo '</legend>';
        
    $data_hoje = converteDataHora($data_hoje);
    $data_hoje = explode(" ", $data_hoje);

    if($filtro_folga_domingo){
        $nomes_seg = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa INNER JOIN tb_horarios_escala c ON a.id_usuario = c.id_usuario INNER JOIN tb_folgas_dom d ON c.id_horarios_escala = d.id_horarios_escala WHERE c.data_inicial = '".$data_consulta."' ".$filtro_usuario." ".$filtro_folgas."  ".$filtro_folga_domingo." ".$filtro_status." ".$filtro_lider." ORDER BY b.nome ASC");
    }else{
        $nomes_seg = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa INNER JOIN tb_horarios_escala c ON a.id_usuario = c.id_usuario WHERE c.data_inicial = '".$data_consulta."' ".$filtro_usuario." ".$filtro_folgas." ".$filtro_status." ".$filtro_lider." ".$filtro_expediente." ORDER BY b.nome ASC");
    }
    
    if($nomes_seg){

    ?>
    
    <div class="container-fluid">
        <div class="row">   

            <?php               
            $contador_cabecacalho = 0;
            foreach ($nomes_seg as $nome_seg) {

                //verificação do turno do atendente

                // verificação do atendente (o que veio do banco)
                if($nome_seg['inicial_seg'] > $nome_seg['final_seg']){

                    $consulta_inicial = $data_hoje[0]." ".$nome_seg['inicial_seg'].":00";
                    $consulta_inicial = date('Y-m-d H:i:s', strtotime("+0 days",strtotime($consulta_inicial)));
                    $consulta_final = $data_hoje[0]." ".$nome_seg['final_seg'].":00";
                    $consulta_final = date('Y-m-d H:i:s', strtotime("+1 days",strtotime($consulta_final)));

                }else if($nome_seg['inicial_seg'] <= $nome_seg['final_seg']){

                    $consulta_inicial = $data_hoje[0]." ".$nome_seg['inicial_seg'].":00";
                    $consulta_inicial = date('Y-m-d H:i:s', strtotime("+0 days",strtotime($consulta_inicial)));
                    $consulta_final = $data_hoje[0]." ".$nome_seg['final_seg'].":00";
                    $consulta_final = date('Y-m-d H:i:s', strtotime("+0 days",strtotime($consulta_final)));

                }

                $total = (strtotime($consulta_inicial) - strtotime($consulta_final))/3600;
                $total = $total*(-1);

                $validacao_turno = 0;

                if($turno == 1){
                    if($nome_seg['carga_horaria'] == 'integral'){
                        $validacao_turno = 1;
                    }else{
                        $validacao_turno = 0;
                    }
                }else if($turno == 2){
                    if($nome_seg['carga_horaria'] == 'meio'){
                        $validacao_turno = 1;
                    }else{
                        $validacao_turno = 0;
                    }
                }else if($turno == 3){
                    if($nome_seg['carga_horaria'] == 'jovem'){
                        $validacao_turno = 1;
                    }else{
                        $validacao_turno = 0;
                    }
                }else if($turno == 4){
                    if($nome_seg['carga_horaria'] == 'estagio'){
                        $validacao_turno = 1;
                    }else{
                        $validacao_turno = 0;
                    }
                }else{
                    $validacao_turno = 1;
                }
               
                if($nome_seg['carga_horaria'] == 'meio'){
                    $texto_carga_horaria = 'Meio Turno';
                }else if($nome_seg['carga_horaria'] == 'integral'){
                    $texto_carga_horaria = 'Integral';
                }else if($nome_seg['carga_horaria'] == 'jovem'){
                    $texto_carga_horaria = 'Jovem Aprendiz';
                }else if($nome_seg['carga_horaria'] == 'estagio'){
                    $texto_carga_horaria = 'Estágio';
                }else{
                    $texto_carga_horaria = 'N/D';
                }

                if($validacao_turno == 1){
                    //Sem filtro de hora ou expediente = 0
                    if($expediente == 0){
                        $contador_cabecacalho = 1;
                        ?>
                        <div class="col-md-12">
                            <div class="panel panel-primary">
                                <div class="panel-heading clearfix">
                                    <h3 class="panel-title text-left pull-left"><?=$nome_seg['nome']?> - <?=$texto_carga_horaria?></h3>
                                  <?php if($nome_seg['data_lido']) {
                                            echo '<div class="panel-title text-right pull-center">Ciente ('.converteDataHora($nome_seg['data_lido']).')</div>';
                                        }else{
                                            echo '<div class="panel-title text-right pull-center">Não ciente</div>';
                                        } ?>
                                </div>
                                <div class="panel-body">
                                    <table class="table table-hover" style="margin-bottom:0;">
                                        <thead> 
                                            <tr style="background-color: #EEE9E9;"> 
                                            <th class="col-md-6">Dias</th>
                                                <th class="col-md-3" style ="text-align: center;">Horário Inicial</th>
                                                <th class="col-md-3" style ="text-align: center;" colspan="2">Horário Final</th>
                                            </tr>
                                        </thead> 
                                        <tbody>
                                            <tr>
                                                <td>Segundas a Sextas</td>
                                                <td style ="text-align: center;"><?=$nome_seg['inicial_seg']?></td>
                                                <td style ="text-align: center;" colspan="2"><?=$nome_seg['final_seg']?></td>
                                            </tr>
                                            <tr>
                                                <td>Sábados</td>
                                                <td style ="text-align: center;"><?=$nome_seg['inicial_sab'] ? $nome_seg['inicial_sab'] : 'N/D'?></td>
                                                <td style ="text-align: center;" colspan="2"><?=$nome_seg['final_sab'] ? $nome_seg['final_sab'] : 'N/D'?></td>   
                                            </tr>
                                                <td>Domingos</td>
                                                <td style ="text-align: center;"><?=$nome_seg['inicial_dom'] ? $nome_seg['inicial_dom'] : 'N/D'?></td>
                                                <td style ="text-align: center;" colspan="2"><?=$nome_seg['final_dom'] ? $nome_seg['final_dom'] : 'N/D'?></td>                                   
                                            </tr>
                                        </tbody>
                                        <thead> 
                                            <?php
                                                $folgas_dom = DBRead('', 'tb_folgas_dom', "WHERE id_horarios_escala = '".$nome_seg['id_horarios_escala']."'");
                                                if($folgas_dom[1]['dia']){
                                                    $titulo_folga_domingo = 'Folgas nos Domingos';
                                                }else{
                                                    $titulo_folga_domingo = 'Folga no Domingo';
                                                }
                                            ?>
                                            <tr style="background-color: #EEE9E9;"> 
                                                <th>Folgas na Semana</th>
                                                <th colspan='3' style ="text-align: center;"><?=$titulo_folga_domingo?></th>
                                            </tr>
                                        </thead> 
                                        <tbody>
                                            <tr>
                                                <td><?=$nome_seg['folga_seg']?></td>
                                                <td colspan='3' style ="text-align: center;">
                                                    <?php                                                           
                                                        $cont = 0;
                                                        if($folgas_dom){
                                                            foreach ($folgas_dom as $folga_dom) {
                                                                $cont++;
                                                                if($cont > 1){
                                                                    echo " e dia ";
                                                                }else{
                                                                    echo "Dia ";
                                                                }
                                                                echo converteDataHora($folga_dom['dia']);
                                                            }
                                                        }else{
                                                            echo " N/D ";
                                                        }
                                                    ?>
                                                </td>
                                            </tr>
                                        </tbody>
                                        <?php 

                                        $folgas_especiais = DBRead('', 'tb_horarios_especiais', "WHERE id_horarios_escala = '".$nome_seg['id_horarios_escala']."'");
                                        if($folgas_especiais){
                                        ?>
                                        <thead> 
                                            <tr style="background-color: #EEE9E9;"> 
                                                <th>Horários Especiais (feriados, etc)</th>
                                                <th style ="text-align: center;">Horário Inicial</th>
                                                <th style ="text-align: center;">Horário Final</th>
                                                <th style ="text-align: center;">Tempo de Intervalo</th>
                                            </tr>
                                        </thead> 
                                        <tbody>
                                            <?php 
                                                $cont = 0;
                                                    foreach ($folgas_especiais as $folga_especial) {
                                                        
                                                        echo '
                                                        <tr>
                                                            <td> Dia '.converteData($folga_especial['dia']).'</td>
                                                            <td style ="text-align: center;">'.$folga_especial['inicial_especial'].'</td>
                                                            <td style ="text-align: center;">'.$folga_especial['final_especial'].'</td>';
                                                            if($folga_especial['tempo_intervalo']){
                                                                echo '<td style ="text-align: center;">'.$folga_especial['tempo_intervalo'].'</td>';
                                                            }else{
                                                                echo '<td style ="text-align: center;">N/D</td>';
                                                            }
                                                            echo '
                                                         </tr>';

                                                    }
                                                
                                        echo '</tbody>';
                                        }?>
                                        <?php
                                        $dados_ferias = DBRead('', 'tb_ferias', "WHERE id_usuario = '".$nome_seg['id_usuario']."' AND (data_de BETWEEN '".$data_consulta."' AND '".$data_consulta_proximo."' OR data_ate BETWEEN '".$data_consulta."' AND '".$data_consulta_proximo."')");
                                        
                                        if($dados_ferias){
                                        ?>
                                        <thead> 
                                            <tr style="background-color: #EEE9E9;"> 
                                                <th>Férias/afastamento início</th>
                                                <th style ="text-align: center;" colspan='3'>Férias/afastamento fim</th>
                                            </tr>
                                        </thead> 
                                        <tbody>
                                            <?php 
                                                $cont = 0;
                                                    foreach ($dados_ferias as $ferias) {
                                                        
                                                echo '<tr>
                                                        <td>'.converteData($ferias['data_de']).'</td>
                                                        <td style ="text-align: center;" colspan="3">'.converteData($ferias['data_ate']).'</td>
                                                      </tr>';

                                                    }
                                                
                                            
                                        echo '</tbody>';
                                        }?>

                                    </table>
                                </div>
                            </div>
                        </div>
                    <?php       
                    }
                        if($expediente == 1){
                                        //Com filtro de segunda ou expediente = 1                               
                                if($horario_final == 0){
                                    $final_seg = 24; 
                                }else{
                                    $final_seg = $horario_final; 
                                }

                                // verificação da consulta (o que veio do relatório)
                                if($horario_final < $horario_inicial){
                                    if(!$horario_final){
                                        $horario_final = 0;
                                    }

                                    $consulta_horario_inicial = $data_hoje[0]." ".$horario_inicial.":00";
                                    $consulta_horario_inicial = date('Y-m-d H:i:s', strtotime("+0 days",strtotime($consulta_horario_inicial)));
                                    $consulta_horario_final = $data_hoje[0]." ".$horario_final.":00";
                                    $consulta_horario_final = date('Y-m-d H:i:s', strtotime("+1 days",strtotime($consulta_horario_final))); 
                                
                                    if(!$horario_final || $horario_final == 0){
                                        $consulta_horario_final = date('Y-m-d H:i:s', strtotime("+1 days",strtotime($consulta_horario_final))); 
                                    }

                                    $auxiliar =  $data_hoje[0]." ".$horario_final.":00";
                                    $auxiliar = date('Y-m-d H:i:s', strtotime("+0 days",strtotime($auxiliar))); 

                                    if($nome_seg['inicial_seg'] > $nome_seg['final_seg']){

                                        $consulta_inicial = $data_hoje[0]." ".$nome_seg['inicial_seg'].":00";
                                        $consulta_inicial = date('Y-m-d H:i:s', strtotime("+0 days",strtotime($consulta_inicial)));
                                        $consulta_final = $data_hoje[0]." ".$nome_seg['final_seg'].":00";
                                        $consulta_final = date('Y-m-d H:i:s', strtotime("+1 days",strtotime($consulta_final)));
                                        
                                    }else if($nome_seg['inicial_seg'] <= $nome_seg['final_seg']){

                                        $consulta_inicial = $data_hoje[0]." ".$nome_seg['inicial_seg'].":00";
                                        $consulta_inicial = date('Y-m-d H:i:s', strtotime("+0 days",strtotime($consulta_inicial)));
                                        $consulta_final = $data_hoje[0]." ".$nome_seg['final_seg'].":00";
                                        $consulta_final = date('Y-m-d H:i:s', strtotime("+0 days",strtotime($consulta_final)));

                                    }

                                    if($consulta_inicial < $auxiliar || $consulta_final < $auxiliar){

                                        $consulta_inicial = date('Y-m-d H:i:s', strtotime("+1 days",strtotime($consulta_inicial)));
                                        $consulta_final = date('Y-m-d H:i:s', strtotime("+0 days",strtotime($consulta_final)));

                                    }

                                }else if($horario_final >= $horario_inicial){
                                    $consulta_horario_inicial = $data_hoje[0]." ".$horario_inicial.":00";
                                    $consulta_horario_inicial = date('Y-m-d H:i:s', strtotime("+0 days",strtotime($consulta_horario_inicial)));
                                    $consulta_horario_final = $data_hoje[0]." ".$horario_final.":00";
                                    $consulta_horario_final = date('Y-m-d H:i:s', strtotime("+0 days",strtotime($consulta_horario_final))); 

                                    if($nome_seg['inicial_seg'] > $nome_seg['final_seg']){

                                        $consulta_inicial = $data_hoje[0]." ".$nome_seg['inicial_seg'].":00";
                                        $consulta_inicial = date('Y-m-d H:i:s', strtotime("+0 days",strtotime($consulta_inicial)));
                                        $consulta_final = $data_hoje[0]." ".$nome_seg['final_seg'].":00";
                                        $consulta_final = date('Y-m-d H:i:s', strtotime("+1 days",strtotime($consulta_final)));
                                        
                                    }else if($nome_seg['inicial_seg'] <= $nome_seg['final_seg']){

                                        $consulta_inicial = $data_hoje[0]." ".$nome_seg['inicial_seg'].":00";
                                        $consulta_inicial = date('Y-m-d H:i:s', strtotime("+0 days",strtotime($consulta_inicial)));
                                        $consulta_final = $data_hoje[0]." ".$nome_seg['final_seg'].":00";
                                        $consulta_final = date('Y-m-d H:i:s', strtotime("+0 days",strtotime($consulta_final)));

                                    }
                                }
                                // verificação do atendente (o que veio do banco)

                                if($consulta_inicial >= $consulta_horario_inicial && $consulta_final <= $consulta_horario_final){
                                    $contador_cabecacalho = 1;
                                    ?>
                                    
                                    <div class="col-md-12">
                                        <div class="panel panel-primary">
                                           <div class="panel-heading clearfix">
                                                <h3 class="panel-title text-left pull-left"><?=$nome_seg['nome']?></h3>
                                              <?php if($nome_seg['data_lido']) {
                                                        echo '<div class="panel-title text-right pull-center">Ciente ('.converteDataHora($nome_seg['data_lido']).')</div>';
                                                    }else{
                                                        echo '<div class="panel-title text-right pull-center">Não ciente</div>';
                                                    } ?>
                                            </div>
                                            <div class="panel-body">
                                                <table class="table table-hover" style="margin-bottom:0;">
                                                    <thead> 
                                                        <tr style="background-color: #EEE9E9;"> 
                                                            <th class="col-md-6">Dias</th>
                                                            <th class="col-md-3" style ="text-align: center;">Horário Inicial</th>
                                                            <th class="col-md-3" style ="text-align: center;" colspan="2">Horário Final</th>
                                                        </tr>
                                                    </thead> 
                                                    <tbody>
                                                        <tr>
                                                            <td>Segundas a Sextas</td>
                                                            <td style ="text-align: center;"><strong><?=$nome_seg['inicial_seg'] ? $nome_seg['inicial_seg'] : 'N/D'?></strong></td>
                                                            <td style ="text-align: center;" colspan="2"><strong><?=$nome_seg['final_seg'] ? $nome_seg['final_seg'] : 'N/D'?></strong></td>
                                                        </tr>
                                                        <tr>
                                                            <td>Sábados</td>
                                                            <td style ="text-align: center;"><?=$nome_seg['inicial_sab'] ? $nome_seg['inicial_sab'] : 'N/D'?></td>
                                                            <td style ="text-align: center;" colspan="2"><?=$nome_seg['final_sab'] ? $nome_seg['final_sab'] : 'N/D'?></td>   
                                                        </tr>
                                                            <td>Domingos</td>
                                                            <td style ="text-align: center;"><?=$nome_seg['inicial_dom'] ? $nome_seg['inicial_dom'] : 'N/D'?></td>
                                                            <td style ="text-align: center;" colspan="2"><?=$nome_seg['final_dom'] ? $nome_seg['final_dom'] : 'N/D'?></td>                                   
                                                        </tr>
                                                    </tbody>
                                                    <thead> 
                                                        <?php
                                                            $folgas_dom = DBRead('', 'tb_folgas_dom', "WHERE id_horarios_escala = '".$nome_seg['id_horarios_escala']."'");
                                                            if($folgas_dom[1]['dia']){
                                                                $titulo_folga_domingo = 'Folgas nos Domingos';
                                                            }else{
                                                                $titulo_folga_domingo = 'Folga no Domingo';
                                                            }
                                                        ?>
                                                        <tr style="background-color: #EEE9E9;"> 
                                                            <th>Folgas na Semana</th>
                                                            <th colspan='3' style ="text-align: center;"><?=$titulo_folga_domingo ?></th>
                                                        </tr>
                                                    </thead> 
                                                    <tbody>
                                                        <tr>
                                                            <td><?=$nome_seg['folga_seg']?></td>
                                                            <td colspan='3' style ="text-align: center;">
                                                                <?php                                                                   
                                                                    $cont = 0;
                                                                    if($folgas_dom){
                                                                        foreach ($folgas_dom as $folga_dom) {
                                                                            $cont++;
                                                                            if($cont > 1){
                                                                                echo " e dia ";
                                                                            }else{
                                                                                echo "Dia ";
                                                                            }
                                                                            echo converteDataHora($folga_dom['dia']);
                                                                        }
                                                                    }else{
                                                                        echo " N/D ";
                                                                    }
                                                                ?>
                                                                
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                    <?php 

                                                    $folgas_especiais = DBRead('', 'tb_horarios_especiais', "WHERE id_horarios_escala = '".$nome_seg['id_horarios_escala']."'");
                                                    if($folgas_especiais){
                                                    ?>
                                                    <thead> 
                                                        <tr style="background-color: #EEE9E9;"> 
                                                            <th>Horários Especiais (feriados, etc)</th>
                                                            <th style ="text-align: center;">Horário Inicial</th>
                                                            <th style ="text-align: center;">Horário Final</th>
                                                            <th style ="text-align: center;">Tempo de Intervalo</th>
                                                        </tr>
                                                    </thead> 
                                                    <tbody>
                                                        <?php 

                                                            
                                                            $cont = 0;
                                                                foreach ($folgas_especiais as $folga_especial) {
                                                                    
                                                            echo '<tr>
                                                                    <td> Dia '.converteData($folga_especial['dia']).'</td>
                                                                    <td style ="text-align: center;">'.$folga_especial['inicial_especial'].'</td>
                                                                    <td style ="text-align: center;">'.$folga_especial['final_especial'].'</td>';
                                                                    if($folga_especial['tempo_intervalo']){
                                                                        echo '<td style ="text-align: center;">'.$folga_especial['tempo_intervalo'].'</td>';
                                                                    }else{
                                                                        echo '<td style ="text-align: center;">N/D</td>';
                                                                    }
                                                                    echo '
                                                                  </tr>';

                                                                }
                                                    echo '</tbody>';
                                                    }?>

                                                    <?php
                                                    $dados_ferias = DBRead('', 'tb_ferias', "WHERE id_usuario = '".$nome_seg['id_usuario']."' AND (data_de BETWEEN '".$data_consulta."' AND '".$data_consulta_proximo."' OR data_ate BETWEEN '".$data_consulta."' AND '".$data_consulta_proximo."')");
                                                    
                                                    if($dados_ferias){
                                                    ?>
                                                    <thead> 
                                                        <tr style="background-color: #EEE9E9;"> 
                                                            <th>Férias/afastamento início</th>
                                                            <th style ="text-align: center;" colspan='3'>Férias/afastamento fim</th>
                                                        </tr>
                                                    </thead> 
                                                    <tbody>
                                                        <?php 
                                                            $cont = 0;
                                                                foreach ($dados_ferias as $ferias) {
                                                                    
                                                            echo '<tr>
                                                                    <td>'.converteData($ferias['data_de']).'</td>
                                                                    <td style ="text-align: center;" colspan="3">'.converteData($ferias['data_ate']).'</td>
                                                                  </tr>';

                                                                }
                                                    echo '</tbody>';
                                                    }?>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                <?php
                                }
                        }
                        if($expediente == 2){
                                            
                            if($horario_final == 0 || ($horario_final < $horario_inicial && $horario_final >= $nome_seg['final_sab'])){
                                $final_sab = 24; 
                            }else{
                                $final_sab = $horario_final; 
                            }

                            // verificação da consulta (o que veio do relatório)
                            if($horario_final < $horario_inicial){
                                if(!$horario_final || $horario_final == 0){
                                    $horario_final = 0;
                                }

                                $consulta_horario_inicial = $data_hoje[0]." ".sprintf('%02d', $horario_inicial).":00:00";
                                $consulta_horario_inicial = date('Y-m-d H:i:s', strtotime("+0 days",strtotime($consulta_horario_inicial)));
                                $consulta_horario_final = $data_hoje[0]." ".$horario_final.":00";
                                $consulta_horario_final = date('Y-m-d H:i:s', strtotime("+1 days",strtotime($consulta_horario_final))); 
                                
                                if(!$horario_final || $horario_final == 0){
                                    $consulta_horario_final = date('Y-m-d H:i:s', strtotime("+1 days",strtotime($consulta_horario_final))); 
                                }

                                $auxiliar =  $data_hoje[0]." ".$horario_final.":00";
                                $auxiliar = date('Y-m-d H:i:s', strtotime("+0 days",strtotime($auxiliar))); 

                                if($nome_seg['inicial_sab'] > $nome_seg['final_sab']){

                                    $consulta_inicial = $data_hoje[0]." ".$nome_seg['inicial_sab'].":00";
                                    $consulta_inicial = date('Y-m-d H:i:s', strtotime("+0 days",strtotime($consulta_inicial)));
                                    $consulta_final = $data_hoje[0]." ".$nome_seg['final_sab'].":00";
                                    $consulta_final = date('Y-m-d H:i:s', strtotime("+1 days",strtotime($consulta_final)));

                                }else if($nome_seg['inicial_sab'] <= $nome_seg['final_sab']){

                                    $consulta_inicial = $data_hoje[0]." ".$nome_seg['inicial_sab'].":00";
                                    $consulta_inicial = date('Y-m-d H:i:s', strtotime("+0 days",strtotime($consulta_inicial)));
                                    $consulta_final = $data_hoje[0]." ".$nome_seg['final_sab'].":00";
                                    $consulta_final = date('Y-m-d H:i:s', strtotime("+0 days",strtotime($consulta_final)));

                                }

                                if($consulta_inicial < $auxiliar || $consulta_final < $auxiliar){

                                    $consulta_inicial = date('Y-m-d H:i:s', strtotime("+0 days",strtotime($consulta_inicial)));
                                    $consulta_final = date('Y-m-d H:i:s', strtotime("+0 days",strtotime($consulta_final)));
                                }
                                
                            }else if($horario_final >= $horario_inicial){
                                $consulta_horario_inicial = $data_hoje[0]." ".$horario_inicial.":00";
                                $consulta_horario_inicial = date('Y-m-d H:i:s', strtotime("+0 days",strtotime($consulta_horario_inicial)));
                                $consulta_horario_final = $data_hoje[0]." ".$horario_final.":00";
                                $consulta_horario_final = date('Y-m-d H:i:s', strtotime("+0 days",strtotime($consulta_horario_final))); 

                                if($nome_seg['inicial_sab'] > $nome_seg['final_sab']){

                                    $consulta_inicial = $data_hoje[0]." ".$nome_seg['inicial_sab'].":00";
                                    $consulta_inicial = date('Y-m-d H:i:s', strtotime("+0 days",strtotime($consulta_inicial)));
                                    $consulta_final = $data_hoje[0]." ".$nome_seg['final_sab'].":00";
                                    $consulta_final = date('Y-m-d H:i:s', strtotime("+1 days",strtotime($consulta_final)));

                                }else if($nome_seg['inicial_sab'] <= $nome_seg['final_sab']){

                                    $consulta_inicial = $data_hoje[0]." ".$nome_seg['inicial_sab'].":00";
                                    $consulta_inicial = date('Y-m-d H:i:s', strtotime("+0 days",strtotime($consulta_inicial)));
                                    $consulta_final = $data_hoje[0]." ".$nome_seg['final_sab'].":00";
                                    $consulta_final = date('Y-m-d H:i:s', strtotime("+0 days",strtotime($consulta_final)));

                                }

                            }

                            //echo $consulta_inicial .' >= '. $consulta_horario_inicial .'<br>'. $consulta_final .' <= '. $consulta_horario_final.'<hr>';

                            // verificação do atendente (o que veio do banco)

                            if($consulta_inicial >= $consulta_horario_inicial && $consulta_final <= $consulta_horario_final){
                                $contador_cabecacalho = 1;
                                ?>
                                
                                <div class="col-md-12">
                                    <div class="panel panel-primary">
                                        <div class="panel-heading clearfix">
                                            <h3 class="panel-title text-left pull-left"><?=$nome_seg['nome']?></h3>
                                          <?php if($nome_seg['data_lido']) {
                                                    echo '<div class="panel-title text-right pull-center">Ciente ('.converteDataHora($nome_seg['data_lido']).')</div>';
                                                }else{
                                                    echo '<div class="panel-title text-right pull-center">Não ciente</div>';
                                                } ?>
                                        </div>
                                        <div class="panel-body">
                                            <table class="table table-hover" style="margin-bottom:0;">
                                                <thead> 
                                                    <tr style="background-color: #EEE9E9;">
                                                        <th class="col-md-6">Dias</th>
                                                        <th class="col-md-3" style ="text-align: center;">Horário Inicial</th>
                                                        <th class="col-md-3" style ="text-align: center;" colspan="2">Horário Final</th>
                                                    </tr>
                                                </thead> 
                                                <tbody>
                                                    <tr>
                                                        <td>Segundas a Sextas</td>
                                                        <td style ="text-align: center;"><?=$nome_seg['inicial_seg'] ? $nome_seg['inicial_seg'] : 'N/D'?></td>
                                                        <td style ="text-align: center;" colspan="2"><?=$nome_seg['final_seg'] ? $nome_seg['final_seg'] : 'N/D'?></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Sábados</td>
                                                        <td style ="text-align: center;"><strong><?=$nome_seg['inicial_sab'] ? $nome_seg['inicial_sab'] : 'N/D'?></strong></td>
                                                        <td style ="text-align: center;" colspan="2"><strong><?=$nome_seg['final_sab'] ? $nome_seg['final_sab'] : 'N/D'?></strong></td>  
                                                    </tr>
                                                        <td>Domingos</td>
                                                        <td style ="text-align: center;"><?=$nome_seg['inicial_dom'] ? $nome_seg['inicial_dom'] : 'N/D'?></td>
                                                        <td style ="text-align: center;" colspan="2"><?=$nome_seg['final_dom'] ? $nome_seg['final_dom'] : 'N/D'?></td>                                   
                                                    </tr>
                                                </tbody>
                                                <thead> 
                                                    <?php
                                                        $folgas_dom = DBRead('', 'tb_folgas_dom', "WHERE id_horarios_escala = '".$nome_seg['id_horarios_escala']."'");
                                                        if($folgas_dom[1]['dia']){
                                                            $titulo_folga_domingo = 'Folgas nos Domingos';
                                                        }else{
                                                            $titulo_folga_domingo = 'Folga no Domingo';
                                                        }
                                                    ?>
                                                    <tr style="background-color: #EEE9E9;"> 
                                                        <th>Folgas na Semana</th>
                                                        <th colspan='3' style ="text-align: center;"><?=$titulo_folga_domingo ?></th>
                                                    </tr>
                                                </thead> 
                                                <tbody>
                                                    <tr>
                                                        <td><?=$nome_seg['folga_seg']?></td>
                                                        <td colspan='3' style ="text-align: center;">
                                                            <?php 
                                                                $cont = 0;
                                                                if($folgas_dom){
                                                                    foreach ($folgas_dom as $folga_dom) {
                                                                        $cont++;
                                                                        if($cont > 1){
                                                                            echo " e dia ";
                                                                        }else{
                                                                            echo "Dia ";
                                                                        }
                                                                        echo converteDataHora($folga_dom['dia']);
                                                                    }
                                                                }else{
                                                                    echo " N/D ";
                                                                }
                                                            ?>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                                <?php
                                                $folgas_especiais = DBRead('', 'tb_horarios_especiais', "WHERE id_horarios_escala = '".$nome_seg['id_horarios_escala']."'");
                                                if($folgas_especiais){
                                                ?>
                                                <thead> 
                                                    <tr style="background-color: #EEE9E9;"> 
                                                        <th>Horários Especiais (feriados, etc)</th>
                                                        <th style ="text-align: center;">Horário Inicial</th>
                                                        <th style ="text-align: center;">Horário Final</th>
                                                        <th style ="text-align: center;">Tempo de Intervalo</th>
                                                    </tr>
                                                </thead> 
                                                <tbody>
                                                    <?php 
                                                        $cont = 0;
                                                            foreach ($folgas_especiais as $folga_especial) {
                                                                
                                                        echo '<tr>
                                                                <td> Dia '.converteData($folga_especial['dia']).'</td>
                                                                <td style ="text-align: center;">'.$folga_especial['inicial_especial'].'</td>
                                                                <td style ="text-align: center;">'.$folga_especial['final_especial'].'</td>';
                                                                    if($folga_especial['tempo_intervalo']){
                                                                        echo '<td style ="text-align: center;">'.$folga_especial['tempo_intervalo'].'</td>';
                                                                    }else{
                                                                        echo '<td style ="text-align: center;">N/D</td>';
                                                                    }
                                                                    echo '
                                                              </tr>';

                                                            }
                                                echo '</tbody>';
                                                }?>

                                                <?php
                                                    $dados_ferias = DBRead('', 'tb_ferias', "WHERE id_usuario = '".$nome_seg['id_usuario']."' AND (data_de BETWEEN '".$data_consulta."' AND '".$data_consulta_proximo."' OR data_ate BETWEEN '".$data_consulta."' AND '".$data_consulta_proximo."')");
                                                    
                                                    if($dados_ferias){
                                                    ?>
                                                    <thead> 
                                                        <tr style="background-color: #EEE9E9;"> 
                                                            <th>Férias/afastamento início</th>
                                                            <th style ="text-align: center;" colspan='3'>Férias/afastamento fim</th>
                                                        </tr>
                                                    </thead> 
                                                    <tbody>
                                                        <?php 
                                                            $cont = 0;
                                                                foreach ($dados_ferias as $ferias) {
                                                                    
                                                            echo '<tr>
                                                                    <td>'.converteData($ferias['data_de']).'</td>
                                                                    <td style ="text-align: center;" colspan="3">'.converteData($ferias['data_ate']).'</td>
                                                                  </tr>';

                                                                }
                                                    echo '</tbody>';
                                                    }?>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            <?php
                            }
                        } 
                        if($expediente == 3){
                                        
                            if($horario_final == 0 || ($horario_final < $horario_inicial && $horario_final >= $nome_seg['final_dom'])){
                                $final_sab = 24; 
                            }else{
                                $final_dom = $horario_final; 
                            }

                            // verificação da consulta (o que veio do relatório)
                            if($horario_final < $horario_inicial){
                                if(!$horario_final){
                                    $horario_final = 0;
                                }

                                $consulta_horario_inicial = $data_hoje[0]." ".$horario_inicial.":00";
                                $consulta_horario_inicial = date('Y-m-d H:i:s', strtotime("+0 days",strtotime($consulta_horario_inicial)));
                                $consulta_horario_final = $data_hoje[0]." ".$horario_final.":00";
                                $consulta_horario_final = date('Y-m-d H:i:s', strtotime("+1 days",strtotime($consulta_horario_final))); 

                                if(!$horario_final || $horario_final == 0){
                                    $consulta_horario_final = date('Y-m-d H:i:s', strtotime("+1 days",strtotime($consulta_horario_final))); 
                                }

                                $auxiliar =  $data_hoje[0]." ".$horario_final.":00";
                                $auxiliar = date('Y-m-d H:i:s', strtotime("+0 days",strtotime($auxiliar))); 

                                if($nome_seg['inicial_dom'] > $nome_seg['final_dom']){

                                    $consulta_inicial = $data_hoje[0]." ".$nome_seg['inicial_dom'].":00";
                                    $consulta_inicial = date('Y-m-d H:i:s', strtotime("+0 days",strtotime($consulta_inicial)));
                                    $consulta_final = $data_hoje[0]." ".$nome_seg['final_dom'].":00";
                                    $consulta_final = date('Y-m-d H:i:s', strtotime("+1 days",strtotime($consulta_final)));

                                }else if($nome_seg['inicial_dom'] <= $nome_seg['final_dom']){

                                    $consulta_inicial = $data_hoje[0]." ".$nome_seg['inicial_dom'].":00";
                                    $consulta_inicial = date('Y-m-d H:i:s', strtotime("+0 days",strtotime($consulta_inicial)));
                                    $consulta_final = $data_hoje[0]." ".$nome_seg['final_dom'].":00";
                                    $consulta_final = date('Y-m-d H:i:s', strtotime("+0 days",strtotime($consulta_final)));

                                }

                                if($consulta_inicial < $auxiliar || $consulta_final < $auxiliar){

                                    $consulta_inicial = date('Y-m-d H:i:s', strtotime("+1 days",strtotime($consulta_inicial)));
                                    $consulta_final = date('Y-m-d H:i:s', strtotime("+0 days",strtotime($consulta_final)));

                                }
                            }else if($horario_final >= $horario_inicial){
                                $consulta_horario_inicial = $data_hoje[0]." ".$horario_inicial.":00";
                                $consulta_horario_inicial = date('Y-m-d H:i:s', strtotime("+0 days",strtotime($consulta_horario_inicial)));
                                $consulta_horario_final = $data_hoje[0]." ".$horario_final.":00";
                                $consulta_horario_final = date('Y-m-d H:i:s', strtotime("+1 days",strtotime($consulta_horario_final))); 

                                if($nome_seg['inicial_dom'] > $nome_seg['final_dom']){

                                    $consulta_inicial = $data_hoje[0]." ".$nome_seg['inicial_dom'].":00";
                                    $consulta_inicial = date('Y-m-d H:i:s', strtotime("+0 days",strtotime($consulta_inicial)));
                                    $consulta_final = $data_hoje[0]." ".$nome_seg['final_dom'].":00";
                                    $consulta_final = date('Y-m-d H:i:s', strtotime("+1 days",strtotime($consulta_final)));

                                }else if($nome_seg['inicial_dom'] <= $nome_seg['final_dom']){

                                    $consulta_inicial = $data_hoje[0]." ".$nome_seg['inicial_dom'].":00";
                                    $consulta_inicial = date('Y-m-d H:i:s', strtotime("+0 days",strtotime($consulta_inicial)));
                                    $consulta_final = $data_hoje[0]." ".$nome_seg['final_dom'].":00";
                                    $consulta_final = date('Y-m-d H:i:s', strtotime("+0 days",strtotime($consulta_final)));

                                }

                                if($consulta_inicial < $auxiliar || $consulta_final < $auxiliar){

                                    $consulta_inicial = date('Y-m-d H:i:s', strtotime("+1 days",strtotime($consulta_inicial)));
                                    $consulta_final = date('Y-m-d H:i:s', strtotime("+1 days",strtotime($consulta_final)));
                                }
                            }

                            // verificação do atendente (o que veio do banco)
                            
                                if($consulta_inicial >= $consulta_horario_inicial && $consulta_final <= $consulta_horario_final){
                                    $contador_cabecacalho = 1;
                                ?>
                                
                                <div class="col-md-12">
                                    <div class="panel panel-primary">
                                        <div class="panel-heading clearfix">
                                            <h3 class="panel-title text-left pull-left"><?=$nome_seg['nome']?></h3>
                                          <?php if($nome_seg['data_lido']) {
                                                    echo '<div class="panel-title text-right pull-center">Ciente ('.converteDataHora($nome_seg['data_lido']).')</div>';
                                                }else{
                                                    echo '<div class="panel-title text-right pull-center">Não ciente</div>';
                                                } ?>
                                        </div>
                                        <div class="panel-body">
                                            <table class="table table-hover" style="margin-bottom:0;">
                                                <thead> 
                                                    <tr style="background-color: #BEBEBE;"> 
                                                        <th class="col-md-6">Dias</th>
                                                        <th class="col-md-3" style ="text-align: center;">Horário Inicial</th>
                                                        <th class="col-md-3" style ="text-align: center;" colspan="2">Horário Final</th>
                                                    </tr>
                                                </thead> 
                                                <tbody>
                                                    <tr>
                                                        <td>Segundas a Sextas</td>
                                                        <td style ="text-align: center;"><?=$nome_seg['inicial_seg'] ? $nome_seg['inicial_seg'] : 'N/D'?></td>
                                                        <td style ="text-align: center;" colspan="2"><?=$nome_seg['final_seg'] ? $nome_seg['final_seg'] : 'N/D'?></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Sábados</td>
                                                        <td style ="text-align: center;"><?=$nome_seg['inicial_sab'] ? $nome_seg['inicial_sab'] : 'N/D'?></td>
                                                        <td style ="text-align: center;" colspan="2"><?=$nome_seg['final_sab'] ? $nome_seg['final_sab'] : 'N/D'?></td>   
                                                    </tr>
                                                        <td>Domingos</td>
                                                        <td style ="text-align: center;"><?=$nome_seg['inicial_dom'] ? $nome_seg['inicial_dom'] : 'N/D'?></td>
                                                        <td style ="text-align: center;" colspan="2"><?=$nome_seg['final_dom'] ? $nome_seg['final_dom'] : 'N/D'?></td>                                   
                                                    </tr>
                                                </tbody>
                                                <?php
                                                        $folgas_dom = DBRead('', 'tb_folgas_dom', "WHERE id_horarios_escala = '".$nome_seg['id_horarios_escala']."'");
                                                        if($folgas_dom[1]['dia']){
                                                            $titulo_folga_domingo = 'Folgas nos Domingos';
                                                        }else{
                                                            $titulo_folga_domingo = 'Folga no Domingo';
                                                        }
                                                    ?>
                                                    <tr style="background-color: #EEE9E9;"> 
                                                        <th>Folgas na Semana</th>
                                                        <th colspan='2' style ="text-align: center;"><?=$titulo_folga_domingo ?></th>
                                                    </tr>
                                                </thead> 
                                                <tbody>
                                                    <tr>
                                                        <td><?=$nome_seg['folga_seg']?></td>
                                                        <td colspan='3' style ="text-align: center;">
                                                            <?php 
                                                                $cont = 0;
                                                                if($folgas_dom){
                                                                    foreach ($folgas_dom as $folga_dom) {
                                                                        $cont++;
                                                                        if($cont > 1){
                                                                            echo " e dia ";
                                                                        }else{
                                                                            echo "Dia ";
                                                                        }
                                                                        echo converteDataHora($folga_dom['dia']);
                                                                    }
                                                                }else{
                                                                    echo " N/D ";
                                                                }
                                                            ?>
                                                            
                                                        </td>
                                                    </tr>
                                                </tbody>
                                                <?php
                                                $folgas_especiais = DBRead('', 'tb_horarios_especiais', "WHERE id_horarios_escala = '".$nome_seg['id_horarios_escala']."'");
                                                if($folgas_especiais){
                                                ?>
                                                <thead> 
                                                    <tr style="background-color: #EEE9E9;"> 
                                                        <th>Horários Especiais (feriados, etc)</th>
                                                        <th style ="text-align: center;">Horário Inicial</th>
                                                        <th style ="text-align: center;">Horário Final</th>
                                                        <th style ="text-align: center;">Tempo de Intervalo</th>
                                                    </tr>
                                                </thead> 
                                                <tbody>
                                                    <?php 
                                                        $cont = 0;
                                                            foreach ($folgas_especiais as $folga_especial) {
                                                                
                                                        echo '<tr>
                                                                <td> Dia '.converteData($folga_especial['dia']).'</td>
                                                                <td style ="text-align: center;">'.$folga_especial['inicial_especial'].'</td>
                                                                <td style ="text-align: center;">'.$folga_especial['final_especial'].'</td>';
                                                                if($folga_especial['tempo_intervalo']){
                                                                    echo '<td style ="text-align: center;">'.$folga_especial['tempo_intervalo'].'</td>';
                                                                }else{
                                                                    echo '<td style ="text-align: center;">N/D</td>';
                                                                }
                                                                echo '
                                                              </tr>';

                                                            }
                                                echo '</tbody>';
                                                }?>

                                                <?php
                                                $dados_ferias = DBRead('', 'tb_ferias', "WHERE id_usuario = '".$nome_seg['id_usuario']."' AND (data_de BETWEEN '".$data_consulta."' AND '".$data_consulta_proximo."' OR data_ate BETWEEN '".$data_consulta."' AND '".$data_consulta_proximo."')");
                                                
                                                if($dados_ferias){
                                                ?>
                                                <thead> 
                                                    <tr style="background-color: #EEE9E9;"> 
                                                        <th>Férias ou afastamento</th>
                                                        <th style ="text-align: center;">Data Inicial</th>
                                                        <th style ="text-align: center;">Data Final</th>
                                                    </tr>
                                                </thead> 
                                                <tbody>
                                                    <?php 
                                                        $cont = 0;
                                                            foreach ($dados_ferias as $ferias) {
                                                                
                                                        echo '<tr>
                                                                    <td style ="text-align: center;">'.$ferias['data_de'].'</td>
                                                                    <td style ="text-align: center;">'.$ferias['data_de'].'</td>
                                                                    <td style ="text-align: center;">'.$ferias['data_ate'].'</td>
                                                              </tr>';

                                                            }
                                                echo '</tbody>';
                                                }?>
                
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <?php
                            }
                        }
                    }
                }?>
            </div>
        </div>
                    
    <?php
        if($contador_cabecacalho == 0){
                echo "<table class='table table-bordered'>";
                    echo "<tbody>";
                        echo "<tr>";
                            echo "<td class='text-center'> <h4>Não foram encontrados resultados!</h4></td>";
                        echo "</tr>";
                    echo "</tbody>";
                echo "</table>";
        }
    }else{
        echo "<table class='table table-bordered'>";
            echo "<tbody>";
                echo "<tr>";
                    echo "<td class='text-center'> <h4>Não foram encontrados resultados!</h4></td>";
                echo "</tr>";
            echo "</tbody>";
        echo "</table>";
    }
}
     
function relatorio_disponibilidade($usuario, $folga, $folga_domingo, $turno){
    
    $data_hoje = getDataHora();
    $data_hoje = converteDataHora($data_hoje);

    if(!$usuario){
        $filtro_usuario = "";
        $atendente = "Todos";
    }else{
        $nome = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_usuario = '".$usuario."'", "b.nome");
        $atendente = $nome[0]['nome'];
        $filtro_usuario = "AND a.id_usuario = '".$usuario."'";
    }
    
    if($folga == "Todos"){
        $filtro_folgas = "";
        $legenda_folga = 'Todos';

    }else{
        $filtro_folgas = "AND c.folga = '".$folga."'";
        $legenda_folga = $folga;

    }

    if($folga_domingo == ""){
        $legenda_folga_domingo = 'Todos';
    }else{
        $domingos = array(
            "0" => "Todos",
            "1" => "Primeiro",
            "2" => "Segundo",
            "3" => "Terceiro",
            "4" => "Quarto",
            "5" => "Quinto"
        );
        $legenda_folga_domingo = $domingos[$folga_domingo];
        $filtro_folga_domingo = "AND c.folga_dom = ".$folga_domingo."";
    }

    if($turno == 1){
        $legenda_turno = 'Integral';
    }else if($turno == 2){
        $legenda_turno = 'Meio Turno';
    }else if($turno == 3){
        $legenda_turno = 'Jovem Aprendiz';
    }else if($turno == 4){
        $legenda_turno = 'Estágio';
    }else{
        $legenda_turno = 'Qualquer';
    }

    $gerado = "<span style=\"font-size: 14px;\"><strong>Gerado em: </strong> $data_hoje</span>";

    echo "<div class=\"col-md-12\" style=\"padding: 0\">";
    echo "<legend style=\"text-align:center;\"><strong>Relatório de Horarios</strong><br><span style=\"font-size: 14px;\">$gerado</legend>";
    echo "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong>Atendente - </strong> ".$atendente.", <strong>Dia de folga - </strong>".$legenda_folga.", <strong>Domingo de folga - </strong>".$legenda_folga_domingo.", <strong>Turno/Horário - </strong>".$legenda_turno."</span></legend>";

    $nomes_seg = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa INNER JOIN tb_disponibilidade_escala c ON a.id_usuario = c.id_usuario WHERE a.id_perfil_sistema = '3' ".$filtro_folgas." ".$filtro_folga_domingo." ".$filtro_usuario." ORDER BY b.nome ASC");
    
    if($nomes_seg){
    ?>
    <div class="container-fluid">
    <div class="row">   
        <div class="col-md-12">
            <div class="panel panel-default">
                <ul class="nav nav-tabs" role="tablist">
                    <li role="presentation" class="active"><a href="#seg" aria-controls="seg" role="tab" data-toggle="tab">Segundas a Sextas</a></li>
                    <li role="presentation"><a href="#sab" aria-controls="sab" role="tab" data-toggle="tab">Sábados</a></li>
                    <li role="presentation"><a href="#dom" aria-controls="dom" role="tab" data-toggle="tab">Domingos</a></li>
                </ul>
                <!-- Tab panes -->
                <div class="tab-content">

                    <!-- Começa seg a sab -->
                    <div role="tabpanel" class="tab-pane active" id="seg">
                    <?php

                        $opcoes = array(
                            "2" => "Acessível",
                            "1" => "Preferência",
                            "3" => "Incômodo",
                            "4" => "Impossível"
                        );

                        $horarios = array(
                            "0" => "00:00",
                            "1" => "01:00",
                            "2" => "02:00",
                            "3" => "03:00",
                            "4" => "04:00",
                            "5" => "05:00",
                            "6" => "06:00",
                            "7" => "07:00",
                            "8" => "08:00",
                            "9" => "09:00",
                            "10" => "10:00",
                            "11" => "11:00",
                            "12" => "12:00",
                            "13" => "13:00",
                            "14" => "14:00",
                            "15" => "15:00",
                            "16" => "16:00",
                            "17" => "17:00",
                            "18" => "18:00",
                            "19" => "19:00",
                            "20" => "20:00",
                            "21" => "21:00",
                            "22" => "22:00",
                            "23" => "23:00"
                        );
                        ?>
                            <div style= overflow-x:auto;>
                                <table class="table table-hover dataTable" style="margin-bottom:0;">
                                    <thead> 
                                        <tr> 
                                            <th>Atendente</th>
                                            <th>Turno</th>
                                            <?php
                                                foreach ($horarios as $hora) {
                                                    echo "<th class='text-center'>".$hora."</th>";
                                                }
                                                echo "<th>Folgas na semana</th>";
                                                echo "<th>Justificativa</th>";
                                            ?>
                                        </tr>
                                    </thead> 
                                    <tbody>
                                                        
                                    <?php 

                                    foreach ($nomes_seg as $nome_seg) {

                                        $cont_turno = DBRead('', 'tb_horarios_disponibilidade', "WHERE id_disponibilidade_escala = '".$nome_seg['id_disponibilidade_escala']."' AND disponibilidade = 1 AND periodo = 'seg a sex'","COUNT(*) AS cont");
                                        
                                        $cont['cont'] = $cont_turno[0]['cont'];

                                        if($turno == 1){
                                            if($nome_seg['carga_horaria'] == 'integral'){
                                                $validacao_turno = 1;
                                            }else{
                                                $validacao_turno = 0;
                                            }
                                        }else if($turno == 2){
                                            if($nome_seg['carga_horaria'] == 'meio'){
                                                $validacao_turno = 1;
                                            }else{
                                                $validacao_turno = 0;
                                            }
                                        }else if($turno == 3){
                                            if($nome_seg['carga_horaria'] == 'jovem'){
                                                $validacao_turno = 1;
                                            }else{
                                                $validacao_turno = 0;
                                            }
                                        }else if($turno == 4){
                                            if($nome_seg['carga_horaria'] == 'estagio'){
                                                $validacao_turno = 1;
                                            }else{
                                                $validacao_turno = 0;
                                            }
                                        }else{
                                            $validacao_turno = 1;
                                        }

                                        if($nome_seg['carga_horaria'] == 'meio'){
                                            $texto_carga_horaria = 'Meio Turno';
                                        }else if($nome_seg['carga_horaria'] == 'integral'){
                                            $texto_carga_horaria = 'Integral';
                                        }else if($nome_seg['carga_horaria'] == 'jovem'){
                                            $texto_carga_horaria = 'Jovem Aprendiz';
                                        }else if($nome_seg['carga_horaria'] == 'estagio'){
                                            $texto_carga_horaria = 'Estágio';
                                        }else{
                                            $texto_carga_horaria = 'N/D';
                                        }

                                    if($validacao_turno == 1){

                                        echo "<tr>";
                                        echo "<td>".$nome_seg['nome']."</td>";
                                        echo "<td>".$texto_carga_horaria."</td>";

                                        $escalas_seg = DBRead('', 'tb_horarios_disponibilidade', "WHERE id_disponibilidade_escala = '".$nome_seg['id_disponibilidade_escala']."' AND periodo = 'seg a sex'");
                                        
                                        if($escalas_seg){
                                            foreach ($escalas_seg as $escala_seg) {
                                                if($escala_seg['disponibilidade'] == 1){
                                                    $cor = "bgcolor='#228B22'";
                                                }else if($escala_seg['disponibilidade'] == 2){
                                                    $cor = "bgcolor='#00B2EE'";
                                                }else if($escala_seg['disponibilidade'] == 3){
                                                    $cor = "bgcolor='#B8860B'";
                                                }else if($escala_seg['disponibilidade'] == 4){
                                                    $cor = "bgcolor='#8B0000'";
                                                }

                                                echo "<td class='text-center' ".$cor."><font color='white'>".$escala_seg['disponibilidade']."</font></td>";
                                            }
                                                }else{
                                                    foreach ($horarios as $hora) {
                                                        echo "<td></td>";
                                                    }
                                                }
                                                if($nome_seg['folga']){
                                                    echo "<td>";
                                                        echo $nome_seg['folga'] ? $nome_seg['folga'] : 'N/D';
                                                    echo "</td>";
                                                }else{
                                                    echo "<td></td>";
                                                }
                                                if($nome_seg['justificativa_indisponibilidade']){
                                                    echo "<td>".$nome_seg['justificativa_indisponibilidade']."</td>";
                                                }else{
                                                    echo "<td></td>";
                                                }
                                                echo "</tr>";
                                            }
                                        }
                                        ?>
                                    </tbody> 
                                </table>
                            </div>  
                        </div>
                        <!-- Acaba seg a sab -->

                        <!-- Comça sab -->
                        <div role="tabpanel" class="tab-pane" id="sab">
                        
                            <div style= overflow-x:auto;>
                                <table class="table table-hover dataTable" style="margin-bottom:0;">
                                    <thead> 
                                        <tr> 
                                            <th>Atendente</th>
                                            <?php
                                            foreach ($horarios as $hora) {
                                                echo "<th class='text-center'>".$hora."</th>";
                                            }
                                            echo "<th>Folgas na semana</th>";
                                            echo "<th>Justificativa</th>";
                                            ?>
                                                
                                        </tr>
                                    </thead> 
                                    <tbody>
                                        
                                    <?php 

                                    foreach ($nomes_seg as $nome_seg) {
                                        $cont_turno = DBRead('', 'tb_horarios_disponibilidade', "WHERE id_disponibilidade_escala = '".$nome_seg['id_disponibilidade_escala']."' AND disponibilidade = 1 AND periodo = 'seg a sex'","COUNT(*) AS cont");
                                        
                                        $cont['cont'] = $cont_turno[0]['cont'];
                                        
                                        if($turno == 1){
                                            if($nome_seg['carga_horaria'] == 'integral'){
                                                $validacao_turno = 1;
                                            }else{
                                                $validacao_turno = 0;
                                            }
                                        }else if($turno == 2){
                                            if($nome_seg['carga_horaria'] == 'meio'){
                                                $validacao_turno = 1;
                                            }else{
                                                $validacao_turno = 0;
                                            }
                                        }else if($turno == 3){
                                            if($nome_seg['carga_horaria'] == 'jovem'){
                                                $validacao_turno = 1;
                                            }else{
                                                $validacao_turno = 0;
                                            }
                                        }else if($turno == 4){
                                            if($nome_seg['carga_horaria'] == 'estagio'){
                                                $validacao_turno = 1;
                                            }else{
                                                $validacao_turno = 0;
                                            }
                                        }else{
                                            $validacao_turno = 1;
                                        }

                                    if($validacao_turno == 1){

                                        echo "<tr>";
                                        echo "<td>".$nome_seg['nome']."</td>";

                                        $escalas_sab = DBRead('', 'tb_horarios_disponibilidade', "WHERE id_disponibilidade_escala = '".$nome_seg['id_disponibilidade_escala']."' AND periodo = 'sabado'");
                                        
                                        if($escalas_sab){
                                            foreach ($escalas_sab as $escala_sab) {
                                                if($escala_sab['disponibilidade'] == 1){
                                                    $cor = "bgcolor='#228B22'";
                                                }else if($escala_sab['disponibilidade'] == 2){
                                                    $cor = "bgcolor='#00B2EE'";
                                                }else if($escala_sab['disponibilidade'] == 3){
                                                    $cor = "bgcolor='#B8860B'";
                                                }else if($escala_sab['disponibilidade'] == 4){
                                                    $cor = "bgcolor='#8B0000'";
                                                }
                                                echo "<td class='text-center' ".$cor."><font color='white'>".$escala_sab['disponibilidade']."</font></td>";
                                            }
                                        }else{
                                            foreach ($horarios as $hora) {
                                                echo "<td></td>";
                                            }
                                        }
                                        if($nome_seg['folga']){
                                            echo "<td>";
                                                echo $nome_seg['folga'] ? $nome_seg['folga'] : 'N/D';
                                            echo "</td>";
                                        }else{
                                            echo "<td></td>";
                                        }
                                        if($nome_seg['justificativa_indisponibilidade']){
                                            echo "<td>".$nome_seg['justificativa_indisponibilidade']."</td>";
                                        }else{
                                            echo "<td></td>";
                                        }
                                        echo "</tr>";
                                    }
                                }
                                    ?>
                                </tbody> 
                            </table>
                        </div>
                    </div>
                    <!-- Acaba sab -->

                    <!-- Começa dom -->
                    <div role="tabpanel" class="tab-pane" id="dom">
                        <div style= overflow-x:auto;>
                            <table class="table table-hover dataTable" style="margin-bottom:0;">
                                <thead> 
                                    <tr> 
                                        <th>Atendente</th>
                                        <?php
                                        foreach ($horarios as $hora) {
                                            echo "<th class='text-center'>".$hora."</th>";
                                        }
                                        echo "<th>Folga no domingo</th>";
                                        ?>
                                            
                                    </tr>
                                </thead> 
                                <tbody>
                                
                                    <?php 
                                    
                                    foreach ($nomes_seg as $nome_seg) {

                                        $cont_turno = DBRead('', 'tb_horarios_disponibilidade', "WHERE id_disponibilidade_escala = '".$nome_seg['id_disponibilidade_escala']."' AND disponibilidade = 1 AND periodo = 'seg a sex'","COUNT(*) AS cont");
                                        
                                        $cont['cont'] = $cont_turno[0]['cont'];
                                        
                                        if($turno == 1){
                                            if($nome_seg['carga_horaria'] == 'integral'){
                                                $validacao_turno = 1;
                                            }else{
                                                $validacao_turno = 0;
                                            }
                                        }else if($turno == 2){
                                            if($nome_seg['carga_horaria'] == 'meio'){
                                                $validacao_turno = 1;
                                            }else{
                                                $validacao_turno = 0;
                                            }
                                        }else if($turno == 3){
                                            if($nome_seg['carga_horaria'] == 'jovem'){
                                                $validacao_turno = 1;
                                            }else{
                                                $validacao_turno = 0;
                                            }
                                        }else if($turno == 4){
                                            if($nome_seg['carga_horaria'] == 'estagio'){
                                                $validacao_turno = 1;
                                            }else{
                                                $validacao_turno = 0;
                                            }
                                        }else{
                                            $validacao_turno = 1;
                                        }
                                    if($validacao_turno == 1){

                                        echo "<tr>";
                                        echo "<td>".$nome_seg['nome']."</td>";

                                        $escalas_dom = DBRead('', 'tb_horarios_disponibilidade', "WHERE id_disponibilidade_escala = '".$nome_seg['id_disponibilidade_escala']."' AND periodo = 'domingo'");
                                    
                                        if($escalas_dom){
                                            foreach ($escalas_dom as $escala_dom) {
                                                if($escala_dom['disponibilidade'] == 1){
                                                    $cor = "bgcolor='#228B22'";
                                                }else if($escala_dom['disponibilidade'] == 2){
                                                    $cor = "bgcolor='#00B2EE'";
                                                }else if($escala_dom['disponibilidade'] == 3){
                                                    $cor = "bgcolor='#B8860B'";
                                                }else if($escala_dom['disponibilidade'] == 4){
                                                    $cor = "bgcolor='#8B0000'";
                                                }
                                                echo "<td class='text-center' ".$cor." ><font color='white'>".$escala_dom['disponibilidade']."</font></td>";
                                            }
                                        }else{
                                            foreach ($horarios as $hora) {
                                                echo "<td></td>";
                                            }
                                        }
                                        
                                        if($nome_seg['folga_dom']){
                                            echo "<td class='text-center'>".$nome_seg['folga_dom']."º Domingo</td>";
                                        }else{
                                            echo "<td>N/D</td>";
                                        }

                                        echo "</tr>";
                                    }
                                }
                                    ?>
                                </tbody> 
                            </table>
                        </div>
                    </div>  
                </div>
            </div>
            <div class="row">   
        <div class="col-md-12">  
    
            <div style= overflow-x:auto;>
                <table class="table table-hover dataTable" style="margin-bottom:0;">
                    <thead> 
                        <tr> 
                            <th class="col-md-1">Legenda</th>
                            <th class="col-md-2"></th>
                            <th class="col-md-1"></th>
                            <th class="col-md-2"></th>
                            <th class="col-md-1"></th>
                            <th class="col-md-2"></th>
                            <th class="col-md-1"></th>
                            <th class="col-md-2"></th>
                        </tr>
                    </thead>  
                    <tbody> 
                        <?php
                                                echo "<td class='text-center' bgcolor='#228B22'><font color='white'>1</font></td>";
                            echo "<td>Preferência</td>";
                                                echo "<td class='text-center' bgcolor='#00B2EE'><font color='white'>2</font></td>";
                            echo "<td>Acessível</td>";
                                                echo "<td class='text-center' bgcolor='#B8860B'><font color='white'>3</font></td>";
                            echo "<td>Incômodo</td>";
                                                echo "<td class='text-center' bgcolor='#8B0000'><font color='white'>4</font></td>";
                            echo "<td>Impossível</td>";
                            ?>
                            
                    </tbody> 
                </table>
            </div>  
        </div>
    </div>
        </div>  
    <!-- acaba aqui embaixo -->                    
                                                            
    <?php
    }else{
        echo "<div class='col-md-12'>";
            echo "<table class='table table-bordered'>";
                echo "<tbody>";
                    echo "<tr>";
                        echo "<td class='text-center'> <h4>Não foram encontrados resultados!</h4></td>";
                    echo "</tr>";
                echo "</tbody>";
            echo "</table>";
        echo "</div>";
    
    }
}

function relatorio_confirmacao($var_mes, $var_ano, $lider){

    $data_consulta = $var_ano."-".$var_mes."-01";

    $meses = array(
        "01" => "Janeiro",
        "02" => "Fevereiro",
        "03" => "Março",
        "04" => "Abril",
        "05" => "Maio",
        "06" => "Junho",
        "07" => "Julho",
        "08" => "Agosto",
        "09" => "Setembro",
        "10" => "Outubro",
        "11" => "Novembro",
        "12" => "Dezembro",
    );  
    if($lider){
        $dados_lider = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON b.id_pessoa = a.id_pessoa WHERE a.id_usuario = '".$lider."'");
        $lider_legenda = "<strong> Líder Direto - </strong>".$dados_lider[0]['nome'];
        $filtro_lider = "AND b.lider_direto = '".$lider."'";
    }else{
        $lider_legenda = "<strong> Líder Direto - </strong>Todos";
    }
    $categoria_legenda = "<strong> Escala de - </strong>".$meses[$var_mes]." de ".$var_ano;
    
    $data_hora = converteDataHora(getDataHora());

    $gerado = "<span style=\"font-size: 14px;\"><strong>Gerado em: </strong> $data_hora</span>";

    echo "<div class=\"col-md-8 col-md-offset-2\" style=\"padding: 0\">";
    echo "<legend style=\"text-align:center;\"><strong>Relatório de Horários (Confirmações)</strong><br>$gerado</legend>";
    echo "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\">".$categoria_legenda.", ".$lider_legenda."</legend>";

    $dados = DBRead('', 'tb_horarios_escala a', "INNER JOIN tb_usuario b ON a.id_usuario =  b.id_usuario INNER JOIN tb_pessoa c ON b.id_pessoa = c.id_pessoa WHERE a.data_inicial = '".$data_consulta."' ".$filtro_lider);

    if($dados){
            
        echo '<table class="table table-striped dataTable" style="margin-bottom:0;">
                  <thead>
                    <tr>
                    <th class="text-left col-md-4">Usuário</th>
                    <th class="text-left col-md-4">Turno/Horários</th>
                    <th class="text-left col-md-4">Confirmação</th>
                    </tr>
                  </thead>
                <tbody>';

        foreach($dados as $dado){
                    if($dado['data_lido']){
                        $classe = "class='success'";
                        $lido = converteDataHora($dado['data_lido']);
                    }else{
                        $classe = "";
                        $lido = "Não confirmado";
                    }

                    if($dado['carga_horaria'] == 'meio'){
                        $texto_carga_horaria = 'Meio Turno';
                    }else if($dado['carga_horaria'] == 'integral'){
                        $texto_carga_horaria = 'Integral';
                    }else if($dado['carga_horaria'] == 'jovem'){
                        $texto_carga_horaria = 'Jovem Aprendiz';
                    }else if($dado['carga_horaria'] == 'estagio'){
                        $texto_carga_horaria = 'Estágio';
                    }else{
                        $texto_carga_horaria = 'N/D';
                    }

                  echo "<tr ".$classe.">";
                  echo "<td>".$dado['nome']."</td>";
                  echo "<td>".$texto_carga_horaria."</td>";
                  echo "<td>".$lido."</td>";
                  echo "</tr>";

        }

        echo ' </tbody>
            </table>';

    }else{
        echo "<div class='col-md-12'>";
            echo "<table class='table table-bordered'>";
                echo "<tbody>";
                    echo "<tr>";
                        echo "<td class='text-center'> <h4>Não foram encontrados resultados!</h4></td>";
                    echo "</tr>";
                echo "</tbody>";
            echo "</table>";
        echo "</div>";
    }

    echo "<script>
            $(document).ready(function(){
                $('.dataTable').DataTable({
                    \"language\": {
                        \"url\": \"//cdn.datatables.net/plug-ins/1.10.19/i18n/Portuguese-Brasil.json\"
                    },                  
                    \"searching\": false,
                    \"paging\":   false,
                    \"info\":     false
                });
            });
        </script>           
        ";
    echo "</div>";
} 

function relatorio_comparar($mes_inicio, $mes_fim, $flag_escalas){

    $data_hoje = getDataHora();
    $data_hoje = converteDataHora($data_hoje);

    $gerado = "<span style=\"font-size: 14px;\"><strong>Gerado em: </strong> $data_hoje</span>";

    if($flag_escalas == 1){
        $legenda_flag_escalas = 'Não';
    }else{
        $legenda_flag_escalas = 'Sim';
    }
    
    echo "<div class=\"col-md-12\" style=\"padding: 0\">";
    echo "<legend style=\"text-align:center;\"><strong>Relatório de Mudanças de Escala</strong><br>$gerado</legend>";
    echo "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong>Escala 1 - </strong>".converteData($mes_inicio).", <strong>Escala 2 - </strong>".converteData($mes_fim).", <strong>Mostrar escala 1 - </strong>".$legenda_flag_escalas."</span></legend>";

        echo '<table class="table table-striped dataTable tableAniversario">
              <thead>
                <tr>
                    <th class="text-left col-md-3">Nome</th>
                    <th class="text-left col-md-1">Início Seg. a Sex.</th>
                    <th class="text-left col-md-1">Final Seg. a Sex.</th>
                    <th class="text-left col-md-1">Folga</th>
                    <th class="text-left col-md-1">Início Sábado</th>
                    <th class="text-left col-md-1">Final Sábado</th>
                    <th class="text-left col-md-1">Início Domingo</th>
                    <th class="text-left col-md-1">Final Domingo</th>
                    <th class="text-left col-md-1">Domingo</th>
                    <th class="text-left col-md-1">Férias e afastamentos</th>
                </tr>
              </thead>
              <tbody>';   

    $dados_usuarios = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.status = 1 AND a.id_perfil_sistema = 3 AND id_usuario IN (SELECT id_usuario FROM tb_horarios_escala WHERE data_inicial = '".$mes_inicio."') ORDER by b.nome ASC");    

    if($dados_usuarios){
        foreach ($dados_usuarios as $conteudo_usuario) {
            $dados_data_folga_domingo2 = 0;
            $dados_data_folga_domingo = 0;

            $inicio = DBRead('', 'tb_horarios_escala', "WHERE data_inicial = '".$mes_inicio."' AND id_usuario = '".$conteudo_usuario['id_usuario']."' ");   
            $dados_escala_final = DBRead('', 'tb_horarios_escala', "WHERE data_inicial = '".$mes_fim."' AND id_usuario = '".$inicio[0]['id_usuario']."'");
                echo "<tr>";
                    echo "<td>".$conteudo_usuario['razao_social']."</td>";
                    if($inicio[0]['inicial_seg'] != $dados_escala_final[0]['inicial_seg']){
                        if(!$dados_escala_final[0]['inicial_seg']){
                            $escala_2 = "N/D";
                            if($flag_escalas == 1){
                                $escala_2 = "Não cadastrado";
                            }
                        }else{
                            $escala_2 = $dados_escala_final[0]['inicial_seg'];
                        }

                        if($flag_escalas == 1){
                            echo "<td><span style='color:#B22222;'>".$escala_2."</td>";
                        }else{
                            echo "<td>Escala 1 - ".$inicio[0]['inicial_seg']."</span><br><span style='color:#B22222;'>Escala 2 - ".$escala_2."</td>";
                        }
                        
                    }else{
                        echo "<td> </td>";
                    }

                    if($inicio[0]['final_seg'] != $dados_escala_final[0]['final_seg']){
                        if(!$dados_escala_final[0]['final_seg']){
                            $escala_2 = "N/D";
                            if($flag_escalas == 1){
                                $escala_2 = "Não cadastrado";
                            }
                        }else{
                            $escala_2 = $dados_escala_final[0]['final_seg'];
                        }

                        if($flag_escalas == 1){
                            echo "<td><span style='color:#B22222;'>".$escala_2."</td>";
                        }else{
                            echo "<td>Escala 1 - ".$inicio[0]['final_seg']."<br><span style='color:#B22222;'>Escala 2 - ".$escala_2."</span></td>";
                        }

                    }else{
                        echo "<td> </td>";
                    }

                    if($inicio[0]['folga_seg'] != $dados_escala_final[0]['folga_seg']){
                        if(!$dados_escala_final[0]['folga_seg']){
                            $escala_2 = "N/D";
                            if($flag_escalas == 1){
                                $escala_2 = "Não cadastrado";
                            }
                        }else{
                            $escala_2 = $dados_escala_final[0]['folga_seg'];
                        }

                        if($flag_escalas == 1){
                            echo "<td><span style='color:#B22222;'>".$escala_2."</td>";
                        }else{
                            echo "<td>Escala 1 - ".$inicio[0]['folga_seg']."</span><br><span style='color:#B22222;'>Escala 2 - ".$escala_2."</td>";
                        }
                    }else{
                        echo "<td> </td>";
                    }

                    if($inicio[0]['inicial_sab'] != $dados_escala_final[0]['inicial_sab']){
                        if(!$dados_escala_final[0]['inicial_sab']){
                            $escala_2 = "N/D";
                            if($flag_escalas == 1){
                                $escala_2 = "Não cadastrado";
                            }
                        }else{
                            $escala_2 = $dados_escala_final[0]['inicial_sab'];
                        }
                        
                        if($flag_escalas == 1){
                            echo "<td><span style='color:#B22222;'>".$escala_2."</td>";
                        }else{
                            echo "<td>Escala 1 - ".$inicio[0]['inicial_sab']."<br><span style='color:#B22222;'>Escala 2 - ".$escala_2."</span></td>";
                        }   
                    }else{
                        echo "<td> </td>";
                    }

                    if($inicio[0]['final_sab'] != $dados_escala_final[0]['final_sab']){
                        if(!$dados_escala_final[0]['final_sab']){
                            $escala_2 = "N/D";
                            if($flag_escalas == 1){
                                $escala_2 = "Não cadastrado";
                            }
                        }else{
                            $escala_2 = $dados_escala_final[0]['final_sab'];
                        }


                        if($flag_escalas == 1){
                            echo "<td><span style='color:#B22222;'>".$escala_2."</td>";
                        }else{
                            echo "<td>Escala 1 - ".$inicio[0]['final_sab']."<br><span style='color:#B22222;'>Escala 2 - ".$escala_2."</span></td>";
                        }
                            
                    }else{
                        echo "<td> </td>";
                    }

                    if($inicio[0]['inicial_dom'] != $dados_escala_final[0]['inicial_dom']){
                        if(!$dados_escala_final[0]['final_sab']){
                            $escala_2 = "N/D";
                            if($flag_escalas == 1){
                                $escala_2 = "Não cadastrado";
                            }
                        }else{
                            $escala_2 = $dados_escala_final[0]['inicial_dom'];
                        }
                        
                        if($flag_escalas == 1){
                            echo "<td><span style='color:#B22222;'>".$escala_2."</td>";
                        }else{
                            echo "<td>Escala 1 - ".$inicio[0]['inicial_dom']."<br><span style='color:#B22222;'>Escala 2 - ".$escala_2."</span></td>";
                        }   
                    }else{
                        echo "<td> </td>";
                    }

                    if($inicio[0]['final_dom'] != $dados_escala_final[0]['final_dom']){
                        if(!$dados_escala_final[0]['final_dom']){
                            $escala_2 = "N/D";
                            if($flag_escalas == 1){
                                $escala_2 = "Não cadastrado";
                            }
                        }else{
                            $escala_2 = $dados_escala_final[0]['final_dom'];
                        }

                        if($flag_escalas == 1){
                            echo "<td><span style='color:#B22222;'>".$escala_2."</td>";
                        }else{
                            echo "<td>Escala 1 - ".$inicio[0]['final_dom']."<br><span style='color:#B22222;'>Escala 2 - ".$escala_2."</span></td>";
                        }       
                    }else{
                        echo "<td> </td>";
                    }
                    if($flag_escalas != 1){
                        if($inicio){
                            $dados_data_folga_domingo = DBRead('', 'tb_folgas_dom', "WHERE id_horarios_escala = ".$inicio[0]['id_horarios_escala']." ");

                            if($dados_data_folga_domingo){
                                echo "<td>Escala 1 - ";
                                $escala1 = '';
                                foreach ($dados_data_folga_domingo as $conteudo_data_folga_domingo) {               

                                    $escala1 = $escala1.' '.converteData($conteudo_data_folga_domingo['dia']).",";

                                }
                                echo substr($escala1,0,-1);     
            
                            }else{
                                echo "<td>Escala 1 - N/D";
                            }
                        
                        }else{
                            echo "<td>Escala 1 - N/D";
                        }
                        echo "<br>";
                    }
                    if($dados_escala_final){
                        if($flag_escalas == 1){
                            echo "<td>";
                        }else{
                            $nome_escalas = 'Escala 2 - ';
                        }
                        $dados_data_folga_domingo2 = DBRead('', 'tb_folgas_dom', "WHERE id_horarios_escala = ".$dados_escala_final[0]['id_horarios_escala']." ");

                        if($dados_data_folga_domingo2){
                                echo "<span style='color:#B22222;'>".$nome_escalas."";
                                $escala2 = '';
                            foreach ($dados_data_folga_domingo2 as $conteudo_data_folga_domingo2) {
                                
                                $escala2 = $escala2." ".converteData($conteudo_data_folga_domingo2['dia']).",";

                            }   

                            echo substr($escala2,0,-1);     
                            echo "</span></td>";    
                        }else{
                            if($flag_escalas == 1){
                                $texto = "Não cadastrado";
                            }else{
                                $texto = "N/D";
                            }
                            echo "<span style='color:#B22222;'>".$nome_escalas."".$texto."</span></td>";
                            }
                    }else{
                        if($flag_escalas == 1){
                            echo "<td>";
                        }
                        if($flag_escalas == 1){
                            $texto = "Não cadastrado";
                        }else{
                            $texto = "N/D";
                        }
                        echo "<span style='color:#B22222;'>Escala 2 - ".$texto."</span></td>";
                    }
                    
            
        $dados_ferias = DBRead('', 'tb_ferias', "WHERE id_usuario = '".$conteudo_usuario['id_usuario']."' AND (data_de BETWEEN '".$mes_inicio."' AND '".$mes_fim."' OR data_ate BETWEEN '".$mes_inicio."' AND '".$mes_fim."') ORDER BY data_de");                                       
            
            if($dados_ferias){
                echo "<td>";
                foreach ($dados_ferias as $ferias) {
                    echo "<span style='color:#B22222;'>De: ".converteData($ferias['data_de'])."<br> Até: ".converteData($ferias['data_ate'])."<br>";
                }
                echo "</span></td>";
            }else{
                echo "<td></td>";
            }
            echo "</tr>";
        }
    }

    $dados_escala_inexistente = DBRead('', 'tb_horarios_escala', "WHERE data_inicial = '".$mes_fim."' AND id_usuario NOT IN (SELECT id_usuario FROM tb_horarios_escala WHERE data_inicial = '".$mes_inicio."')");
    
    if($dados_escala_inexistente){
        foreach ($dados_escala_inexistente as $conteudo_escala_inexistente) {
            
            $dados_nome = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON b.id_pessoa = a.id_pessoa WHERE a.id_usuario = '".$conteudo_escala_inexistente['id_usuario']."'");

            echo "<tr>";
                if($flag_escalas == 1){
                    $nome_string = "<td><span style='color:#B22222;'>";
                }else{
                    $nome_string = "<td>Escala 1 - N/D<br><span style='color:#B22222;'>Escala 2 - ";
                }
                echo "<td>".$dados_nome[0]['razao_social']."</td>";
                echo $nome_string."".$conteudo_escala_inexistente['inicial_seg']."</span></td>";
                echo $nome_string."".$conteudo_escala_inexistente['final_seg']."</span></td>";
                echo $nome_string."".$conteudo_escala_inexistente['folga_seg']."</span></td>";
                echo $nome_string."".$conteudo_escala_inexistente['inicial_sab']."</span></td>";
                echo $nome_string."".$conteudo_escala_inexistente['final_sab']."</span></td>";
                echo $nome_string."".$conteudo_escala_inexistente['inicial_dom']."</span></td>";
                echo $nome_string."".$conteudo_escala_inexistente['final_dom']."</span></td>";
                        
                $dados_data_folga_domingo2 = DBRead('', 'tb_folgas_dom', "WHERE id_horarios_escala = ".$conteudo_escala_inexistente['id_horarios_escala']." ");
                
                if($dados_data_folga_domingo2){
                    if($flag_escalas == 1){
                        echo "<td><span style='color:#B22222;'>";
                    }else{
                        echo "<td>Escala 1 - N/D<br><span style='color:#B22222;'>Escala 2 - ";
                    }

                    $escala2 = '';
                    foreach ($dados_data_folga_domingo2 as $conteudo_data_folga_domingo2) {
                        
                        $escala2 = $escala2." ".converteData($conteudo_data_folga_domingo2['dia']).",";
                    
                    }   
                    echo substr($escala2,0,-1);     
                    echo "</span></td>";;   
                }else{
                    if($flag_escalas == 1){
                        echo "<td><span style='color:#B22222;'>Não cadastrado</span></td>";
                    }else{
                        echo "<td>Escala 1 - N/D<br><span style='color:#B22222;'>Escala 2 - N/D</span></td>";
                    }
                }

                $dados_ferias = DBRead('', 'tb_ferias', "WHERE id_usuario = '".$conteudo_escala_inexistente['id_usuario']."' AND (data_de BETWEEN '".$mes_inicio."' AND '".$mes_fim."' OR data_ate BETWEEN '".$mes_inicio."' AND '".$mes_fim."') ORDER BY data_de");                                        
            
                if($dados_ferias){
                    echo "<td>";
                    foreach ($dados_ferias as $ferias) {
                        echo "<span style='color:#B22222;'>De: ".converteData($ferias['data_de'])."<br> Até: ".converteData($ferias['data_ate'])."<br>";
                    }
                    echo "<span></td>";
                }else{
                    echo "<td></td>";
                }

            echo "</tr>";
        }

    }

        echo "</tbody>";
    echo '</table>';
        
        echo "<script>
                $(document).ready(function(){
                    var table = $('.dataTable').DataTable({
                        \"language\": {
                            \"url\": \"//cdn.datatables.net/plug-ins/1.10.19/i18n/Portuguese-Brasil.json\"
                        },                  
                        \"searching\": false,
                        \"paging\":   false,
                        \"info\":     false
                    });
                    var buttons = new $.fn.dataTable.Buttons(table, {
                        buttons: [
                            {
                                extend: 'excelHtml5',
                                text: '<i class=\"fa fa-file-excel-o\" aria-hidden=\"true\"></i> Exportar',
                                filename: 'relatorio_escalas',
                                title : null,
                                exportOptions: {
                                  modifier: {
                                    page: 'all'
                                  }
                                }
                              },
                        ],  
                        dom:
                        {
                            button: {
                                tag: 'button',
                                className: 'btn btn-default'
                            },
                            buttonLiner: { tag: null }
                        }
                   }).container().appendTo($('#panel_buttons'));
                });
            </script>           
            ";

} 

function relatorio_ferias($data_de, $data_ate, $usuario){

    if($usuario){
        $dados_atendente = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_usuario = '".$usuario."' ");
        $legenda_atendente = $dados_atendente[0]['nome'];
        $filtro_usuario = " AND a.id_usuario = '".$usuario."' ";
    }else{
        $legenda_atendente = 'Todos';
    }
    $data_hora = converteDataHora(getDataHora());

    $gerado = "<span style=\"font-size: 14px;\"><strong>Gerado em: </strong> $data_hora</span>";

    echo "<div class=\"col-md-6 col-md-offset-3\" style=\"padding: 0\">";
    echo "<legend style=\"text-align:center;\"><strong>Relatório de Férias e Afastamentos</strong><br>$gerado</legend>";
    echo "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong>Data Início - </strong>".$data_de.", <strong>Data Fim - </strong>".$data_ate.", <strong>Atendente - </strong>".$legenda_atendente."</legend>";
    
    $data_de = converteData($data_de);
    $data_ate = converteData($data_ate);

    $dados_ferias = DBRead('', 'tb_ferias a', "INNER JOIN tb_usuario b ON a.id_usuario = b.id_usuario INNER JOIN tb_pessoa c ON b.id_pessoa = c.id_pessoa WHERE (a.data_de BETWEEN '".$data_de."' AND '".$data_ate."' OR a.data_ate BETWEEN '".$data_de."' AND '".$data_ate."') ".$filtro_usuario." ", "a.*, c.nome");
    
    if($dados_ferias){
            
        echo '<table class="table table-striped dataTable" style="margin-bottom:0;">
                  <thead>
                    <tr>
                        <th class="text-left col-md-6">Atendente</th>
                        <th class="text-left col-md-3">Data Inicial</th>
                        <th class="text-left col-md-3">Data Final</th>
                    </tr>
                  </thead>
                <tbody>';

        foreach($dados_ferias as $conteudo_ferias){

                  echo "<tr>";
                    echo "<td>".$conteudo_ferias['nome']."</td>";
                    echo "<td>".converteData($conteudo_ferias['data_de'])."</td>";
                    echo "<td>".converteData($conteudo_ferias['data_ate'])."</td>";
                  echo "</tr>";

        }

        echo ' </tbody>
            </table>';

    }else{
        echo "<div class='col-md-12'>";
            echo "<table class='table table-bordered'>";
                echo "<tbody>";
                    echo "<tr>";
                        echo "<td class='text-center'> <h4>Não foram encontrados resultados!</h4></td>";
                    echo "</tr>";
                echo "</tbody>";
            echo "</table>";
        echo "</div>";
    }

    echo "<script>
            $(document).ready(function(){
                $('.dataTable').DataTable({
                    \"language\": {
                        \"url\": \"//cdn.datatables.net/plug-ins/1.10.19/i18n/Portuguese-Brasil.json\"
                    },                  
                    \"searching\": false,
                    \"paging\":   false,
                    \"info\":     false,
                    columnDefs: [
                       { type: 'date-eu', targets: 1 },
                       { type: 'date-eu', targets: 2 }
                    ]
                });
            });
        </script>           
        ";
    echo "</div>";
} 

function relatorio_intervalo($usuario, $data_de, $data_ate){

    $diasemana = array(
        "0" => "Domingo",
        "1" => "Segunda-Feira",
        "2" => "Terça-Feira",
        "3" => "Quarta-Feira",
        "4" => "Quinta-Feira",
        "5" => "Sexta-Feira",
        "6" => "Sábado",
    );

    if($usuario){
        $dados_atendente = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_usuario = '".$usuario."' ");
        $legenda_atendente = $dados_atendente[0]['nome'];
        $filtro_usuario = " AND a.id_usuario = '".$usuario."' ";
    }else{
        $legenda_atendente = 'Todos';
    }
    $data_hora = converteDataHora(getDataHora());

    $gerado = "<span style=\"font-size: 14px;\"><strong>Gerado em: </strong> $data_hora</span>";

    echo "<div class=\"col-md-8 col-md-offset-2\" style=\"padding: 0\">";
    echo "<legend style=\"text-align:center;\"><strong>Relatório de Controle de Intervalos</strong><br>$gerado</legend>";
    echo "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong>Atendente - </strong>".$legenda_atendente.", <strong>Data Inicial - </strong>".$data_de.", <strong>Data Final - </strong>".$data_ate."</legend>";
    
    $data_de = converteData($data_de);
    $data_ate = converteData($data_ate);

    foreach (rangeDatas($data_de, $data_ate) as $data) {        

        $diasemana_numero = date('w', strtotime($data));
        $data_hora = date('Y-m-d H:i:s');
        $diasemana_numero = $diasemana[$diasemana_numero];

        $dados_intervalo = DBRead('', 'tb_intervalo a', "INNER JOIN tb_usuario b ON a.id_usuario = b.id_usuario INNER JOIN tb_pessoa c ON b.id_pessoa = c.id_pessoa INNER JOIN tb_usuario d ON d.id_usuario= a.id_usuario_liberou INNER JOIN tb_pessoa e ON d.id_pessoa = e.id_pessoa  WHERE (a.data_inicial LIKE '".$data."%' OR a.data_final LIKE '".$data."%') ".$filtro_usuario." ", "a.data_inicial, a.data_final, c.nome, e.nome AS nome_liberou");

        echo '<br><h5 align="center"><strong>Dia - '.converteData($data).' - '.$diasemana_numero.'</strong></h5>';
            echo '<table class="table table-striped dataTable">
                      <thead>
                        <tr>
                            <th class="text-left col-md-3">Atendente</th>
                            <th class="text-left col-md-3">Liberação para o Intervalo</th>
                            <th class="text-left col-md-3">Conclusão do Intervalo</th>
                            <th class="text-left col-md-3">Liberado por</th>
                        </tr>
                      </thead>
                    <tbody>';
                    if($dados_intervalo){
                
                        foreach ($dados_intervalo as $intervalo) {
                            echo "<tr>";
                                echo "<td>".$intervalo['nome']."</td>";
                                echo "<td>".converteDataHora($intervalo['data_inicial'])."</td>";
                                if($intervalo['data_final']){
                                    echo "<td>".converteDataHora($intervalo['data_final'])."</td>";
                                }else{
                                    echo "<td>Não concluíu o intervalo ainda!</td>";
                                }
                                echo "<td>".$intervalo['nome_liberou']."</td>";
                            echo "</tr>";
                        }
                    }

                      echo ' </tbody>
                    </table>';
                    echo "<hr>";
    }

    echo "<script>
            $(document).ready(function(){
                $('.dataTable').DataTable({
                    \"language\": {
                        \"url\": \"//cdn.datatables.net/plug-ins/1.10.19/i18n/Portuguese-Brasil.json\"
                    },                  
                    \"searching\": false,
                    \"paging\":   false,
                    \"info\":     false,
                });
            });
        </script>           
        ";
    echo "</div>";
}

function relatorio_horarios_especiais($data_de, $data_ate){

    $data_hora = converteDataHora(getDataHora());

    $gerado = "<span style=\"font-size: 14px;\"><strong>Gerado em: </strong> $data_hora</span>";

    echo "<div class=\"col-md-12\" style=\"padding: 0\">";
    echo "<legend style=\"text-align:center;\"><strong>Relatório de Horários Especiais</strong><br>$gerado</legend>";
    echo "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong>Data Inicial - </strong>".$data_de.", <strong>Data Final - </strong>".$data_ate."</legend>";
    
    $data_de = converteData($data_de);
    $data_ate = converteData($data_ate);

    $dados_horarios_especias = DBRead('', 'tb_horarios_especiais a', "INNER JOIN tb_horarios_escala b ON a.id_horarios_escala = b.id_horarios_escala INNER JOIN tb_usuario c ON b.id_usuario = c.id_usuario INNER JOIN tb_pessoa d ON c.id_pessoa = d.id_pessoa WHERE a.dia BETWEEN '".$data_de."' AND '".$data_ate."' ", "a.inicial_especial, a.final_especial, a.dia, d.nome, b.carga_horaria, a.tempo_intervalo, d.razao_social");

    if($dados_horarios_especias){
     echo '<table class="table table-striped dataTable">
              <thead>
                <tr>
                    <th class="text-left col-md-2">Atendente</th>
                    <th class="text-left col-md-2">Carga Horária</th>
                    <th class="text-left col-md-2">Horário Inicial</th>
                    <th class="text-left col-md-2">Horário Final</th>
                    <th class="text-left col-md-2">Tempo de Intervalo</th>
                    <th class="text-left col-md-2">Data</th>
                </tr>
              </thead>
            <tbody>';
        
            foreach ($dados_horarios_especias as $horario_especial) {
                echo "<tr>";
                    echo "<td>".$horario_especial['razao_social']."</td>";
                    if($horario_especial['carga_horaria'] == 'meio'){
                        $texto_carga_horaria = 'Meio Turno';
                    }else if($horario_especial['carga_horaria'] == 'integral'){
                        $texto_carga_horaria = 'Integral';
                    }else if($horario_especial['carga_horaria'] == 'jovem'){
                        $texto_carga_horaria = 'Jovem Aprendiz';
                    }else if($horario_especial['carga_horaria'] == 'estagio'){
                        $texto_carga_horaria = 'Estágio';
                    }else{
                        $texto_carga_horaria = 'N/D';
                    }
                    echo "<td>".$texto_carga_horaria."</td>";
                    echo "<td>".$horario_especial['inicial_especial']."</td>";
                    echo "<td>".$horario_especial['final_especial']."</td>";
                    if($horario_especial['tempo_intervalo']){
                        echo "<td>".$horario_especial['tempo_intervalo']."</td>";
                    }else{
                        echo "<td>N/D</td>";
                    }
                    echo "<td>".converteData($horario_especial['dia'])."</td>";
                echo "</tr>";
            }

              echo ' </tbody>
            </table>';
    }else{

        echo "<div class='col-md-12'>";
            echo "<table class='table table-bordered'>";
                echo "<tbody>";
                    echo "<tr>";
                        echo "<td class='text-center'> <h4>Não foram encontrados resultados!</h4></td>";
                    echo "</tr>";
                echo "</tbody>";
            echo "</table>";
        echo "</div>"; 
   }    
    echo "<script>
            $(document).ready(function(){
                $('.dataTable').DataTable({
                    \"language\": {
                        \"url\": \"//cdn.datatables.net/plug-ins/1.10.19/i18n/Portuguese-Brasil.json\"
                    },                  
                    \"searching\": false,
                    \"paging\":   false,
                    \"info\":     false,
                });
            });
        </script>           
        ";
    echo "</div>";
}

function relatorio_escala_horarios($data, $chat){
    
    $data = converteData($data);

    $diasemana = array(
        "0" => "Domingo",
        "1" => "Segunda",
        "2" => "Terça",
        "3" => "Quarta",
        "4" => "Quinta",
        "5" => "Sexta",
        "6" => "Sábado",
    );
    $data_da_consulta = explode('-', $data);
    $data_da_consulta = $data_da_consulta[0].'-'.$data_da_consulta[1].'-01';
    $hora_agora = date('H:i');
    $diasemana_numero = date('w', strtotime($data));
    $diasemana_numero = $diasemana[$diasemana_numero];
    $data_de_hoje = explode(' ', getDataHora());
    $data_de_hoje = $data_de_hoje[0];

    if($diasemana_numero == 'Domingo'){
        $chave_nome_inicial = 'inicial_dom';
        $chave_nome_final = 'final_dom';
        $chave_intervalo = 'dom';
    }else if($diasemana_numero == 'Sábado'){
        $chave_nome_inicial = 'inicial_sab';
        $chave_nome_final = 'final_sab';
        $chave_intervalo = 'sab';
    }else{
        $chave_nome_inicial = 'inicial_seg';
        $chave_nome_final = 'final_seg';
        $chave_intervalo = 'seg';
    }

    $legenda_chat = "Não";
    $filtro_chat = "";
    if($chat == 1){
        $legenda_chat = "Sim";
        $filtro_chat = "AND chat = 1";
    }
        
    $data_hora = converteDataHora(getDataHora());

    $gerado = "<span style=\"font-size: 14px;\"><strong>Gerado em: </strong> $data_hora</span>";
      
    echo "<div class=\"col-md-12\" style=\"padding: 0\">";
    echo "<legend style=\"text-align:center;\"><strong>Relatório de Escalas/Horários</strong><br>$gerado</legend>";
    echo "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong>Data - </strong>".converteData($data)." - ".$diasemana_numero."</legend>";
    echo "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong>Mostrar Somente Atendentes de Chat - </strong>".$legenda_chat."</legend>";

    $diasemana_numero = date('w', strtotime($data));
    $data_hora = date('Y-m-d H:i:s');
    $diasemana_numero = $diasemana[$diasemana_numero];

    $dados = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa INNER JOIN tb_horarios_escala c ON a.id_usuario = c.id_usuario WHERE a.id_perfil_sistema = '3' AND b.status != 2 AND a.status = 1 AND data_inicial = '".$data_da_consulta."' ORDER BY c.".$chave_nome_final." ASC " , "a.id_usuario, b.nome");
        
    echo '<table class="table table-striped  dataTable" style="margin-bottom:0;">';
            echo "<thead>";
                echo "<tr>";
                    echo "<th class=\"col-md-2\">Nome</th>";
                    echo "<th class=\"col-md-1 text-center\">Horário Inicial</th>";
                    echo "<th class=\"col-md-1 text-center\">Horário Final</th>";
                    echo "<th class=\"col-md-2 text-center\">Folga na Semana</th>";
                    echo "<th class=\"col-md-1 text-center\">Folga no Domingo</th>";
                    echo "<th class=\"col-md-2 text-center\">Intervalo</th>";
                    echo "<th class=\"col-md-2 text-center\">Turno</th>";
                    echo "<th class=\"col-md-1 text-center\">Faz Chat</th>";
                echo "</tr>";
            echo "</thead>";
            echo "<tbody>";
        if($dados){
            foreach ($dados as $atendente) {
              
                $id = $atendente['id_usuario'];
                $dados_horario = DBRead('', 'tb_horarios_escala',"WHERE id_usuario = '".$id."' AND data_inicial = '".$data_da_consulta."' $filtro_chat AND id_usuario NOT IN (SELECT id_usuario FROM tb_ferias WHERE data_de <= '".$data."' AND data_ate >= '".$data."')");

                $dados_folga_domingo = DBRead('', 'tb_folgas_dom',"WHERE id_horarios_escala = '".$dados_horario[0]['id_horarios_escala']."'");

                if($diasemana_numero == 'Domingo'){
                    $dados_folga_domingo_hoje = DBRead('', 'tb_folgas_dom',"WHERE id_horarios_escala = '".$dados_horario[0]['id_horarios_escala']."' AND dia = '".$data."'");
                }

                if(!$dados_folga_domingo_hoje){
                    
                    $dados_horario_especial = DBRead('', 'tb_horarios_especiais',"WHERE dia = '".$data."' AND id_horarios_escala = '".$dados_horario[0]['id_horarios_escala']."' ");

                    if($dados_horario_especial){
                        if($dados_horario){
                            echo "<tr>";    
                                echo "<td style='vertical-align: middle'><span class = 'nome_atendente'>".$atendente['nome']."</span> <strong>(Horário Especial)</strong></td>";
                                echo "<td class=\"text-center\" style='vertical-align: middle'>";
                                    echo $dados_horario_especial[0]['inicial_especial'] ? $dados_horario_especial[0]['inicial_especial'] : "N/D";
                                echo "</td>";
                                echo "<td class=\"text-center\" style='vertical-align: middle'>";
                                    echo $dados_horario_especial[0]['final_especial'] ? $dados_horario_especial[0]['final_especial'] : "N/D";
                                echo "</td>";
                                echo "<td class=\"text-center\" style='vertical-align: middle'>".$dados_horario[0]['folga_seg']."</td>";
                                if($dados_folga_domingo){
                                    echo "<td class=\"text-center\" style='vertical-align: middle'>";
                                    $domingos = '';
                                    foreach ($dados_folga_domingo as $folga_domingo) {
                                        $domingos = $domingos.' '.converteData($folga_domingo['dia']).',';                  
                                    }
                                    $domingos = substr_replace($domingos, ' ', -1);
                                    echo $domingos;
                                    echo "</td>";
                                }else{
                                    echo "<td class=\"text-center\" style='vertical-align: middle'>N/D</td>";                        
                                }  

                                $dados_intervalo = DBRead('', 'tb_horarios_escala_intervalo',"WHERE id_horarios_escala = '".$dados_horario[0]['id_horarios_escala']."' AND dia = '".$chave_intervalo."' ");

                                if($dados_intervalo[0]['tipo_intervalo'] == 1){
                                    $texto_intervalo = "Variável<br>".$dados_intervalo[0]['tempo_intervalo'];
                                }else if($dados_intervalo[0]['tipo_intervalo'] == 2){
                                    $texto_intervalo = "Fixo<br>Início: ".$dados_intervalo[0]['intervalo_inicial']." - Final: ".$dados_intervalo[0]['intervalo_final'];
                                }else{
                                    $texto_intervalo = "N/D";
                                }
                                echo "<td class=\"text-center\" style='vertical-align: middle'>".$texto_intervalo."</td>";                        

                                if($dados_horario[0]['carga_horaria'] == 'meio'){
                                    $texto_carga_horaria = 'Meio Turno';
                                }else if($dados_horario[0]['carga_horaria'] == 'integral'){
                                    $texto_carga_horaria = 'Integral';
                                }else if($dados_horario[0]['carga_horaria'] == 'jovem'){
                                    $texto_carga_horaria = 'Jovem Aprendiz';
                                }else if($dados_horario[0]['carga_horaria'] == 'estagio'){
                                    $texto_carga_horaria = 'Estágio';
                                }else{
                                    $texto_carga_horaria = 'N/D';
                                }

                                $faz_chat = "<span style='color: green'>Sim</span>";
                                if($dados_horario[0]['chat'] == 0){
                                    $faz_chat = "Não";
                                }
                                echo "<td class=\"text-center\" style='vertical-align: middle'>".$texto_carga_horaria."</td>";                        
                                echo "<td class=\"text-center\" style='vertical-align: middle'>".$faz_chat."</td>";                        

                            echo "</tr>";                                  
                        }                          
                    
                    }else{
                        if($dados_horario[0]['folga_seg'] != $diasemana_numero){
                            if($dados_horario){
                        
                                echo "<tr>"; 
                                    echo "<td style='vertical-align: middle'><span class = 'nome_atendente'>".$atendente['nome']."</span></td>";
                                    echo "<td class=\"text-center\" style='vertical-align: middle'>";
                                        echo $dados_horario[0][$chave_nome_inicial] ? $dados_horario[0][$chave_nome_inicial] : "N/D";
                                    echo "</td>";
                                    echo "<td class=\"text-center\" style='vertical-align: middle'>";
                                        echo $dados_horario[0][$chave_nome_final] ? $dados_horario[0][$chave_nome_final] : "N/D";
                                    echo "</td>";
                                    echo "<td class=\"text-center\" style='vertical-align: middle'>".$dados_horario[0]['folga_seg']."</td>";                     

                                    if($dados_folga_domingo){
                                        echo "<td class=\"text-center\" style='vertical-align: middle'>";
                                        $domingos = '';
                                        foreach ($dados_folga_domingo as $folga_domingo) {
                                            $domingos = $domingos.' '.converteData($folga_domingo['dia']).',';                  
                                        }
                                        $domingos = substr_replace($domingos, ' ', -1);
                                        echo $domingos;
                                        echo "</td>";
                                    }else{
                                        echo "<td class=\"text-center\" style='vertical-align: middle'>N/D</td>";                        
                                    }

                                    $dados_intervalo = DBRead('', 'tb_horarios_escala_intervalo',"WHERE id_horarios_escala = '".$dados_horario[0]['id_horarios_escala']."' AND dia = '".$chave_intervalo."' ");

                                    if($dados_intervalo[0]['tipo_intervalo'] == 1){
                                        $texto_intervalo = "Variável<br> Tempo de intervalo: ".$dados_intervalo[0]['tempo_intervalo'];
                                    }else if($dados_intervalo[0]['tipo_intervalo'] == 2){
                                        $texto_intervalo = "Fixo<br>Início: ".$dados_intervalo[0]['intervalo_inicial']." - Final: ".$dados_intervalo[0]['intervalo_final'];
                                    }else{
                                        $texto_intervalo = "N/D";
                                    }
                                    echo "<td class=\"text-center\" style='vertical-align: middle'>".$texto_intervalo."</td>";   
                                        
                                    if($dados_horario[0]['carga_horaria'] == 'meio'){
                                        $texto_carga_horaria = 'Meio Turno';
                                    }else if($dados_horario[0]['carga_horaria'] == 'integral'){
                                        $texto_carga_horaria = 'Integral';
                                    }else if($dados_horario[0]['carga_horaria'] == 'jovem'){
                                        $texto_carga_horaria = 'Jovem Aprendiz';
                                    }else if($dados_horario[0]['carga_horaria'] == 'estagio'){
                                        $texto_carga_horaria = 'Estágio';
                                    }else{
                                        $texto_carga_horaria = 'N/D';
                                    }
    
                                    $faz_chat = "<span style='color: green'>Sim</span>";
                                    if($dados_horario[0]['chat'] == 0){
                                        $faz_chat = "Não";
                                    }
                                    
                                    echo "<td class=\"text-center\" style='vertical-align: middle'>".$texto_carga_horaria."</td>";                        
                                    echo "<td class=\"text-center\" style='vertical-align: middle'>".$faz_chat."</td>";      
                                echo "</tr>";  
                            }
                        }
                    }   
                }
            }
        }

            echo "</tbody>";
        echo "</table>";
    echo "<br>";
    echo "</div>";

    echo "<script>
            $(document).ready(function(){
                $('.dataTable').DataTable({
                    \"language\": {
                        \"url\": \"//cdn.datatables.net/plug-ins/1.10.19/i18n/Portuguese-Brasil.json\"
                    },             
                    aaSorting: [[1, 'asc']],     
                    \"searching\": false,
                    \"paging\":   false,
                    \"info\":     false,
                });
            });
        </script>           
        ";
}

function relatorio_grafico_atendentes_hora($data_de, $data_ate, $turno){

    $dias_semana = array('Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado');
    $data_hora = converteDataHora(getDataHora());
    $tipo_grafico = 1;
    
    if($turno == 1){
        $legenda_turno = 'Integral';
    }else if($turno == 2){
        $legenda_turno = 'Meio Turno';
    }else if($turno == 3){
        $legenda_turno = 'Jovem Aprendiz';
    }else if($turno == 4){
        $legenda_turno = 'Estágio';
    }else{
        $legenda_turno = 'Qualquer';
    }

    if($tipo_grafico == 1){
        $nome_tipo_grafico = 'line';
    }else{
        $nome_tipo_grafico = 'column';
    }

    $gerado = "<span style=\"font-size: 14px;\"><strong>Gerado em: </strong> $data_hora</span>";

    echo "<div class=\"col-md-12\" style=\"padding: 0\">";
    echo "<legend style=\"text-align:center;\"><strong>Gráfico de Atendentes por Hora - Separado por Dia do Mês</strong><br>$gerado</legend>";
    echo "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong>Data Inicial da Escala: </strong>".$data_de.", <strong>Data Final da Escala: </strong>".$data_ate.", <strong>Carga Horária: </strong>".$legenda_turno."</legend></span>";

    $chart = 0;
    foreach(rangeDatas(converteData($data_de), converteData($data_ate)) as $data){
        $atendentes_central = array();
        $atendentes_escala = array();
        $horas = array();
        $numero_dia_semana = date('w', strtotime($data));
        $hora = 0;

        while ($hora < 24) {

            $consulta_data = explode("-", $data);
            $data_ontem = date('Y-m-d', strtotime("-1 days",strtotime($data)));
            $consulta_data = $consulta_data[0].'-'.$consulta_data[1].'-01';

            $dia_hoje = date('w', strtotime($data));
            $dia_ontem = date('w', strtotime($data_ontem));

            $dia_hoje = $dias_semana[$dia_hoje];
            $dia_ontem = $dias_semana[$dia_ontem];
            
            $consulta_data_ontem = explode("-", $data_ontem);

            $consulta_data_ontem = $consulta_data_ontem[0].'-'.$consulta_data_ontem[1].'-01';
            
            $hora_zero = sprintf('%02d', $hora);
            $hora_proxima = sprintf('%02d', $hora_zero+1);

            $cont_atendentes_central = 0;
            $cont_atendentes_escala = 0;

            //início do: pega logins antes da HI e logoffs entre HI e HF
                $dados_atendentes_central = DBRead('snep','queue_agents_log',"WHERE data_login < '$data $hora_zero:00:00' AND data_logoff BETWEEN '$data $hora_zero:00:00' AND '$data $hora_zero:59:59'");
                if($dados_atendentes_central){
                    foreach($dados_atendentes_central as $conteudo_atendentes_central){
                        $dados_usuario = DBRead('','tb_usuario a',"INNER JOIN tb_horarios_escala b ON a.id_usuario = b.id_usuario WHERE a.id_asterisk = '".$conteudo_atendentes_central['codigo']."' ","a.id_usuario, b.inicial_seg, b.final_seg");
                        $id_usuario = $dados_usuario[0]['id_usuario'];
                        
                        if($id_usuario){
                            if(($turno == 1 && $dados_usuario[0]['carga_horaria'] == 'integral') || ($turno == 2 && $dados_usuario[0]['carga_horaria'] == 'meio') || ($turno == 3 && $dados_usuario[0]['carga_horaria'] == 'jovem') || ($turno == 4 && $dados_usuario[0]['carga_horaria'] == 'estagio') || (!$turno) ){
                                $cont_atendentes_central += sprintf("%01.2f", round(converteHorasDecimal('00:'.substr($conteudo_atendentes_central['data_logoff'],14,5)), 2));
                            }
                        
                        }
                        
                    }                   
                }
            //fim do: pega logins antes da HI e logoffs entre HI e HF

            //início do: pega logins entre HI e HF e logoffs entre HI e HF
                $dados_atendentes_central = DBRead('snep','queue_agents_log',"WHERE data_login BETWEEN '$data $hora_zero:00:00' AND '$data $hora_zero:59:59' AND data_logoff BETWEEN '$data $hora_zero:00:00' AND '$data $hora_zero:59:59'");

                if($dados_atendentes_central){
                    foreach($dados_atendentes_central as $conteudo_atendentes_central){
                        $dados_usuario = DBRead('','tb_usuario a',"INNER JOIN tb_horarios_escala b ON a.id_usuario = b.id_usuario WHERE a.id_asterisk = '".$conteudo_atendentes_central['codigo']."' ","a.id_usuario, b.inicial_seg, b.final_seg");
                        $id_usuario = $dados_usuario[0]['id_usuario'];
                        
                        if($id_usuario){
                            if(($turno == 1 && $dados_usuario[0]['carga_horaria'] == 'integral') || ($turno == 2 && $dados_usuario[0]['carga_horaria'] == 'meio') || ($turno == 3 && $dados_usuario[0]['carga_horaria'] == 'jovem') || ($turno == 4 && $dados_usuario[0]['carga_horaria'] == 'estagio') || (!$turno) ){

                                $cont_atendentes_central += sprintf("%01.2f", round(converteHorasDecimal('00:'.substr($conteudo_atendentes_central['data_logoff'],14,5)), 2)) - sprintf("%01.2f", round(converteHorasDecimal('00:'.substr($conteudo_atendentes_central['data_login'],14,5)), 2));
                            }
                        }
                        
                    }
                }
            //fim do: pega logins entre HI e HF e logoffs entre HI e HF

            //início do: pega logins entre HI e HF e logoffs depois da HF
                $dados_atendentes_central = DBRead('snep','queue_agents_log',"WHERE data_login BETWEEN '$data $hora_zero:00:00' AND '$data $hora_zero:59:59' AND data_logoff > '$data $hora_zero:59:59'");
                if($dados_atendentes_central){
                    foreach($dados_atendentes_central as $conteudo_atendentes_central){
                        $dados_usuario = DBRead('','tb_usuario a',"INNER JOIN tb_horarios_escala b ON a.id_usuario = b.id_usuario WHERE a.id_asterisk = '".$conteudo_atendentes_central['codigo']."' ","a.id_usuario, b.inicial_seg, b.final_seg");
                        $id_usuario = $dados_usuario[0]['id_usuario'];
                        
                        if($id_usuario){
                            if(($turno == 1 && $dados_usuario[0]['carga_horaria'] == 'integral') || ($turno == 2 && $dados_usuario[0]['carga_horaria'] == 'meio') || ($turno == 3 && $dados_usuario[0]['carga_horaria'] == 'jovem') || ($turno == 4 && $dados_usuario[0]['carga_horaria'] == 'estagio') || (!$turno) ){
                                
                                $cont_atendentes_central += 1-sprintf("%01.2f", round(converteHorasDecimal('00:'.substr($conteudo_atendentes_central['data_login'],14,5)), 2));
                            }
                        }
                        
                    }
                }
            //fim do: pega logins entre HI e HF e logoffs depois da HF

            //início do: pega logins antes da HI e logoffs depois da HF
                $dados_atendentes_central = DBRead('snep','queue_agents_log',"WHERE data_login < '$data $hora_zero:00:00' AND data_logoff > '$data $hora_zero:59:59'");
                if($dados_atendentes_central){
                    foreach($dados_atendentes_central as $conteudo_atendentes_central){
                        $dados_usuario = DBRead('','tb_usuario a',"INNER JOIN tb_horarios_escala b ON a.id_usuario = b.id_usuario WHERE a.id_asterisk = '".$conteudo_atendentes_central['codigo']."' ","a.id_usuario, b.inicial_seg, b.final_seg");
                        $id_usuario = $dados_usuario[0]['id_usuario'];
                        
                        if($id_usuario){
                            if(($turno == 1 && $dados_usuario[0]['carga_horaria'] == 'integral') || ($turno == 2 && $dados_usuario[0]['carga_horaria'] == 'meio') || ($turno == 3 && $dados_usuario[0]['carga_horaria'] == 'jovem') || ($turno == 4 && $dados_usuario[0]['carga_horaria'] == 'estagio') || (!$turno) ){
                                $cont_atendentes_central += 1;
                            }
                        }
                        
                    }
                }
            //fim do: pega logins antes da HI e logoffs depois da HF

            $atendentes_central[] = sprintf("%01.2f", round($cont_atendentes_central, 2));

            //início codigo atendentes escala
                
                //VERIFICA O DIA DA CONSULTA PARA PESQUISAR (SEG A SEX, SÁBADO OU DOMINGO)
                if($dia_hoje == 'Domingo'){
                    $consulta_dia =  "AND inicial_dom < '".$hora_proxima.":00' AND final_dom > '".$hora_zero.":00'";
                    
                    $consulta_inicial_banco = 'inicial_dom';
                    $consulta_final_banco = 'final_dom';

                    $consulta_inicial_banco_ontem = 'inicial_sab';
                    $consulta_final_banco_ontem = 'final_sab';

                    $consulta_ontem = "AND final_sab > '".$hora_zero.":00' AND inicial_sab > final_sab AND folga_seg != '".$dia_ontem."'";
                    $consulta_invertidos = "AND inicial_dom < '".$hora_proxima.":00' AND inicial_dom > final_dom AND id_horarios_escala NOT IN (SELECT id_horarios_escala FROM tb_folgas_dom WHERE dia = '".$data."')";
                    $consulta_normais = "AND inicial_dom < final_dom AND id_horarios_escala NOT IN (SELECT id_horarios_escala FROM tb_folgas_dom WHERE dia = '".$data."')";

                    $dia_intervalo = 'dom';
                    $dia_intervalo_ontem = 'sab';

                }else if($dia_hoje == 'Sábado'){
                    $consulta_dia =  "AND inicial_sab < '".$hora_proxima.":00' AND final_sab > '".$hora_zero.":00'";
                    $consulta_inicial_banco = 'inicial_sab';
                    $consulta_final_banco = 'final_sab';
                    
                    $consulta_inicial_banco_ontem = 'inicial_seg';
                    $consulta_final_banco_ontem = 'final_seg';

                    $consulta_ontem = "AND final_seg > '".$hora_zero.":00' AND inicial_seg > final_seg AND folga_seg != '".$dia_ontem."'";
                    $consulta_invertidos = "AND inicial_sab < '".$hora_proxima.":00' AND inicial_sab > final_sab AND folga_seg != '".$dia_hoje."'";
                    $consulta_normais = "AND inicial_sab < final_sab AND folga_seg != '".$dia_hoje."'";

                    $dia_intervalo = 'sab';
                    $dia_intervalo_ontem = 'seg';

                }else{
                    $consulta_dia =  "AND inicial_seg < '".$hora_proxima.":00' AND final_seg > '".$hora_zero.":00'";
                    $consulta_inicial_banco = 'inicial_seg';
                    $consulta_final_banco = 'final_seg';

                    if($dia_hoje == 'Quinta' || $dia_hoje == 'Sexta'){
                        $consulta_invertidos = "AND inicial_seg < '".$hora_proxima.":00' AND inicial_seg > final_seg AND (folga_seg != '".$dia_hoje."' AND folga_seg != 'Quinta e Sexta')";
                        $consulta_normais = "AND inicial_seg < final_seg AND (folga_seg != '".$dia_hoje."' AND folga_seg != 'Quinta e Sexta')";
                    }else{
                        $consulta_invertidos = "AND inicial_seg < '".$hora_proxima.":00' AND inicial_seg > final_seg AND folga_seg != '".$dia_hoje."'";
                        $consulta_normais = "AND inicial_seg < final_seg AND folga_seg != '".$dia_hoje."'";
                    }

                    if($dia_hoje == 'Segunda'){
                        $consulta_ontem = "AND final_dom > '".$hora_zero.":00' AND inicial_dom > final_dom AND id_horarios_escala NOT IN (SELECT id_horarios_escala FROM tb_folgas_dom WHERE dia = '".$data_ontem."')";
                        
                        $consulta_inicial_banco_ontem = 'inicial_dom';
                        $consulta_final_banco_ontem = 'final_dom';
                        
                        $dia_intervalo_ontem = 'dom';
                    
                    }else{
                        if($dia_hoje == 'Sexta'){
                            $consulta_ontem = "AND final_seg > '".$hora_zero.":00' AND inicial_seg > final_seg AND (folga_seg != '".$dia_ontem."' AND folga_seg != 'Quinta e Sexta')";
                        }else{
                            $consulta_ontem = "AND final_seg > '".$hora_zero.":00' AND inicial_seg > final_seg AND folga_seg != '".$dia_ontem."'";
                        }
                    
                        $consulta_inicial_banco_ontem = 'inicial_seg';
                        $consulta_final_banco_ontem = 'final_seg';
                    
                        $dia_intervalo_ontem = 'seg';
                    }

                    $dia_intervalo = 'seg';
                }

                //NORMAIS               
                $dados_normais = DBRead('', 'tb_horarios_escala', "WHERE data_inicial = '".$consulta_data."' ".$consulta_dia." ".$consulta_normais." AND atendente = 1 AND id_usuario NOT IN (SELECT id_usuario FROM tb_ferias WHERE id_usuario = id_usuario AND data_de <= '".$data."' AND data_ate >= '".$data."') AND id_usuario NOT IN (SELECT a.id_usuario FROM tb_horarios_escala a INNER JOIN tb_horarios_especiais b ON a.id_horarios_escala = b.id_horarios_escala WHERE b.dia = '".$data."')");

                $dados_normais_especiais = DBRead('', 'tb_horarios_escala a', "INNER JOIN tb_horarios_especiais b ON a.id_horarios_escala = b.id_horarios_escala WHERE b.dia = '".$data."' AND b.inicial_especial < '".$hora_proxima.":00' AND b.final_especial > '".$hora_zero.":00' ");              

                //INVERTIDOS
                $dados_invertidos = DBRead('', 'tb_horarios_escala', "WHERE data_inicial = '".$consulta_data."' ".$consulta_invertidos." AND atendente = 1 AND id_usuario NOT IN (SELECT id_usuario FROM tb_ferias WHERE id_usuario = id_usuario AND data_de <= '".$data."' AND data_ate >= '".$data."') AND id_usuario NOT IN (SELECT a.id_usuario FROM tb_horarios_escala a INNER JOIN tb_horarios_especiais b ON a.id_horarios_escala = b.id_horarios_escala WHERE b.dia = '".$data."')");

                $dados_invertidos_especiais = DBRead('', 'tb_horarios_escala a', "INNER JOIN tb_horarios_especiais b ON a.id_horarios_escala = b.id_horarios_escala WHERE b.dia = '".$data."' AND b.inicial_especial < '".$hora_proxima.":00' AND b.inicial_especial > b.final_especial ");

                if($data == $consulta_data){
                    $consulta_data = $consulta_data_ontem;
                }

                //INVERTIDOS ONTEM
                $dados_invertidos_ontem = DBRead('', 'tb_horarios_escala', "WHERE data_inicial = '".$consulta_data."' ".$consulta_ontem." AND atendente = 1 AND id_usuario NOT IN (SELECT id_usuario FROM tb_ferias WHERE id_usuario = id_usuario AND data_de <= '".$data_ontem."' AND data_ate >= '".$data_ontem."') AND id_usuario NOT IN (SELECT a.id_usuario FROM tb_horarios_escala a INNER JOIN tb_horarios_especiais b ON a.id_horarios_escala = b.id_horarios_escala WHERE b.dia = '".$data_ontem."')");
                
                $dados_invertidos_ontem_especiais =  DBRead('', 'tb_horarios_escala a', "INNER JOIN tb_horarios_especiais b ON a.id_horarios_escala = b.id_horarios_escala WHERE b.dia = '".$data_ontem."' AND b.final_especial > '".$hora_zero.":00' AND b.inicial_especial >= b.final_especial ");

                //NORMAIS
                if($dados_normais){
                    foreach ($dados_normais as $conteudo_normais) {
                        $inicial_hora_funcionario = explode(":", $conteudo_normais[$consulta_inicial_banco]);
                        $final_hora_funcionario = explode(":", $conteudo_normais[$consulta_final_banco]);

                        if(($inicial_hora_funcionario[0] <= $hora_zero) && ($final_hora_funcionario[0] >= $hora_zero)){
                            if(($turno == 1 && $conteudo_normais['carga_horaria'] == 'integral') || ($turno == 2 && $conteudo_normais['carga_horaria'] == 'meio') || ($turno == 3 && $conteudo_normais['carga_horaria'] == 'jovem') || ($turno == 4 && $conteudo_normais['carga_horaria'] == 'estagio') || (!$turno) ){

                                $dados_intervalo_normais = DBRead('', 'tb_horarios_escala_intervalo', "WHERE id_horarios_escala = '".$conteudo_normais['id_horarios_escala']."' AND dia = '".$dia_intervalo."' ");

                                if(!$dados_intervalo_normais){
                                    if($inicial_hora_funcionario[0] == $hora_zero && $inicial_hora_funcionario[1] != '00'){
                                        $valor_final = converteHorasDecimal('00:'.$inicial_hora_funcionario[1]);
                                        $cont_atendentes_escala += 1- ($valor_final);
                                    }else if($final_hora_funcionario[0] == $hora_zero && $final_hora_funcionario[1] != '00'){
                                        $valor_final = converteHorasDecimal('00:'.$final_hora_funcionario[1]);
                                        $cont_atendentes_escala += $valor_final;
                                    }else{
                                        $cont_atendentes_escala += 1;
                                    }     
                                }else{
                                    //1 é variavel

                                    if($dados_intervalo_normais[0]['tipo_intervalo'] == 1){

                                        $data_inicio_compara = date('Y-m-d H:i:s', strtotime("+0 minutes",strtotime($conteudo_normais[$consulta_inicial_banco])));
                                        $data_fim_compara = date('Y-m-d H:i:s', strtotime("+0 minutes",strtotime($conteudo_normais[$consulta_final_banco])));
        
                                        if($data_inicio_compara > $data_fim_compara){
                                            $data_inicio_compara = date('Y-m-d H:i:s', strtotime("-1 days",strtotime($conteudo_normais[$consulta_inicial_banco])));
                                        }
                                        
                                        $diferenca_datas = strtotime($data_fim_compara) - strtotime($data_inicio_compara);
                                        
                                        $tempo_total_trabalho = converteSegundosHoras($diferenca_datas);
                                        $tempo_total_trabalho = converteHorasDecimal($tempo_total_trabalho);
                                        $tempo_total_intervalo = converteHorasDecimal($dados_intervalo_normais[0]['tempo_intervalo']);
                                        $tempo_desconto = $tempo_total_intervalo/$tempo_total_trabalho;

                                        if($inicial_hora_funcionario[0] == $hora_zero && $inicial_hora_funcionario[1] != '00'){
                                            $valor_final = converteHorasDecimal('00:'.$inicial_hora_funcionario[1]);
                                            $cont_atendentes_escala += (1 - ($valor_final)) - ($tempo_desconto * (1 -  ($valor_final)));

                                        }else if($final_hora_funcionario[0] == $hora_zero && $final_hora_funcionario[1] != '00'){
                                            $valor_final = converteHorasDecimal('00:'.$final_hora_funcionario[1]);
                                            $cont_atendentes_escala += (($valor_final) - ($tempo_desconto * $valor_final));
                                            
                                        }else{
                                            $cont_atendentes_escala += 1 - $tempo_desconto;
                                        }     
                                       
                                    }else{

                                        $inicial_hora_intervalo = explode(":", $dados_intervalo_normais[0]['intervalo_inicial']);
                                        $final_hora_intervalo = explode(":", $dados_intervalo_normais[0]['intervalo_final']);

                                        if($inicial_hora_funcionario[0] == $hora_zero && $inicial_hora_funcionario[1] != '00'){

                                            if($inicial_hora_intervalo[0] == $hora_zero && $final_hora_intervalo[0] == $hora_zero){
                                                //intervalo 09:00 até 09:30
                                                $valor_final = converteHorasDecimal('00:'.$inicial_hora_funcionario[1]);
                                                $desconto_intervalo = converteHorasDecimal('00:'.($final_hora_intervalo[1] - $inicial_hora_intervalo[1]));
                                                $cont_atendentes_escala += (1 - ($valor_final)) - ($desconto_intervalo);

                                            }else if($inicial_hora_intervalo[0] == $hora_zero){
                                                //intervalo 09:30 até 10:30
                                                $valor_final = converteHorasDecimal('00:'.$inicial_hora_funcionario[1]);
                                                $desconto_intervalo =  converteHorasDecimal('00:'.$inicial_hora_intervalo[1]);
                                                $cont_atendentes_escala += ($valor_final - (1 - $desconto_intervalo));

                                            }else{
                                                $valor_final = converteHorasDecimal('00:'.$inicial_hora_funcionario[1]);
                                                $cont_atendentes_escala += 1 - ($valor_final);
                                            }

                                        }else if($final_hora_funcionario[0] == $hora_zero && $final_hora_funcionario[1] != '00'){
                                            if($inicial_hora_intervalo[0] == $hora_zero && $final_hora_intervalo[0] == $hora_zero){
                                                //intervalo 09:00 até 09:30
                                                $valor_final = converteHorasDecimal('00:'.$final_hora_funcionario[1]);
                                                $desconto_intervalo = converteHorasDecimal('00:'.($final_hora_intervalo[1] - $inicial_hora_intervalo[1]));
                                                $cont_atendentes_escala += 1 - ($valor_final + $desconto_intervalo);

                                            }else if($final_hora_intervalo[0] == $hora_zero){
                                                //intervalo 08:30 até 09:30
                                                $valor_final = converteHorasDecimal('00:'.$final_hora_funcionario[1]);
                                                $desconto_intervalo = converteHorasDecimal('00:'.$final_hora_intervalo[1]);
                                                $cont_atendentes_escala += ($valor_final - $desconto_intervalo);
                                                
                                            }else{
                                                $valor_final = converteHorasDecimal('00:'.$final_hora_funcionario[1]);
                                                $cont_atendentes_escala += $valor_final;
                                            }
                                        }else{
                                            if($inicial_hora_intervalo[0] == $hora_zero && $final_hora_intervalo[0] == $hora_zero){
                                                //intervalo 09:00 até 09:30
                                                $valor_final = converteHorasDecimal('00:'.$inicial_hora_funcionario[1]);
                                                $desconto_intervalo = converteHorasDecimal('00:'.($final_hora_intervalo[1] - $inicial_hora_intervalo[1]));
                                                $cont_atendentes_escala += 1 - $desconto_intervalo;

                                            }else if($inicial_hora_intervalo[0] == $hora_zero){
                                                //intervalo 09:30 até 10:30
                                                $valor_final = converteHorasDecimal('00:'.$inicial_hora_funcionario[1]);
                                                $desconto_intervalo = converteHorasDecimal('00:'.$inicial_hora_intervalo[1]);
                                                $cont_atendentes_escala += $desconto_intervalo;

                                            }else if($final_hora_intervalo[0] == $hora_zero){
                                                //intervalo 08:30 até 09:30
                                                $desconto_intervalo = converteHorasDecimal('00:'.$final_hora_intervalo[1]);
                                                $cont_atendentes_escala += 1 - $desconto_intervalo;
                                                
                                            }else if($inicial_hora_intervalo[0] < $hora_zero && $final_hora_intervalo[0] > $hora_zero){
                                                //intervalo 08:30 até 10:30
                                                $cont_atendentes_escala += 0;

                                            }else{
                                                //intervalo antes ou depois do horarios
                                                $cont_atendentes_escala += 1;

                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }                                       
                }

                if($dados_normais_especiais){

                    foreach ($dados_normais_especiais as $conteudo_normais_especiais) {
                        $inicial_hora_funcionario = explode(":", $conteudo_normais_especiais['inicial_especial']);
                        $final_hora_funcionario = explode(":", $conteudo_normais_especiais['final_especial']);
                        
                        if(($inicial_hora_funcionario[0] <= $hora_zero) && ($final_hora_funcionario[0] >= $hora_zero)){
                            
                            if(($turno == 1 && $conteudo_normais_especiais['carga_horaria'] == 'integral') || ($turno == 2 && $conteudo_normais_especiais['carga_horaria'] == 'meio') || ($turno == 3 && $conteudo_normais_especiais['carga_horaria'] == 'jovem') || ($turno == 4 && $conteudo_normais_especiais['carga_horaria'] == 'estagio') || (!$turno) ){

                                if(!$conteudo_normais_especiais['tempo_intervalo']){
                                    if($inicial_hora_funcionario[0] == $hora_zero && $inicial_hora_funcionario[1] != '00'){
                                        $valor_final = converteHorasDecimal('00:'.$inicial_hora_funcionario[1]);
                                        $cont_atendentes_escala += 1-($valor_final);
                                    }else if($final_hora_funcionario[0] == $hora_zero && $final_hora_funcionario[1] != '00'){
                                        $valor_final = converteHorasDecimal('00:'.$final_hora_funcionario[1]);
                                        $cont_atendentes_escala += $valor_final;
                                    }else{
                                        $cont_atendentes_escala += 1;
                                    }
                                }else{
                                    $data_inicio_compara = date('Y-m-d H:i:s', strtotime("+0 minutes",strtotime($conteudo_normais_especiais['inicial_especial'])));
                                    $data_fim_compara = date('Y-m-d H:i:s', strtotime("+0 minutes",strtotime($conteudo_normais_especiais['final_especial'])));

                                    if($data_inicio_compara > $data_fim_compara){
                                        $data_inicio_compara = date('Y-m-d H:i:s', strtotime("-1 days",strtotime($conteudo_normais_especiais['inicial_especial'])));
                                    }

                                    $diferenca_datas = strtotime($data_fim_compara) - strtotime($data_inicio_compara);
                                    
                                    $tempo_total_trabalho = converteSegundosHoras($diferenca_datas);
                                    $tempo_total_trabalho = converteHorasDecimal($tempo_total_trabalho);
                                    $tempo_total_intervalo = converteHorasDecimal($conteudo_normais_especiais['tempo_intervalo']);
                                    $tempo_desconto = $tempo_total_intervalo/$tempo_total_trabalho;

                                    if($inicial_hora_funcionario[0] == $hora_zero && $inicial_hora_funcionario[1] != '00'){
                                        $valor_final = converteHorasDecimal('00:'.$inicial_hora_funcionario[1]);
                                        $cont_atendentes_escala += (1 - ($valor_final)) - ($tempo_desconto * (1 -  ($valor_final)));
                                    
                                    }else if($final_hora_funcionario[0] == $hora_zero && $final_hora_funcionario[1] != '00'){
                                        $valor_final = converteHorasDecimal('00:'.$final_hora_funcionario[1]);
                                        $cont_atendentes_escala += (($valor_final) - ($tempo_desconto * $valor_final));
                                    
                                    }else{
                                        $cont_atendentes_escala += 1 - $tempo_desconto;
                                    }
                                }
                            }
                        }
                    }                                       
                }

                //INVERTIDOS
                if($dados_invertidos){
                    
                    foreach ($dados_invertidos as $conteudo_invertidos) {
                        $inicial_hora_funcionario = explode(":", $conteudo_invertidos[$consulta_inicial_banco]);

                        if($inicial_hora_funcionario[0] <= $hora_zero){

                            if(($turno == 1 && $conteudo_invertidos['carga_horaria'] == 'integral') || ($turno == 2 && $conteudo_invertidos['carga_horaria'] == 'meio') || ($turno == 3 && $conteudo_invertidos['carga_horaria'] == 'jovem') || ($turno == 4 && $conteudo_invertidos['carga_horaria'] == 'estagio') || (!$turno) ){

                                $dados_intervalo_invertidos = DBRead('', 'tb_horarios_escala_intervalo', "WHERE id_horarios_escala = '".$conteudo_invertidos['id_horarios_escala']."' AND dia = '".$dia_intervalo."' ");

                                if(!$dados_intervalo_invertidos){
                                    if($inicial_hora_funcionario[0] == $hora_zero && $inicial_hora_funcionario[1] != '00'){
                                        $valor_final = converteHorasDecimal('00:'.$inicial_hora_funcionario[1]);
                                        $cont_atendentes_escala += 1-($valor_final);
                                    }else{
                                        $cont_atendentes_escala += 1;
                                    }
                                }else{
                                    if($dados_intervalo_invertidos[0]['tipo_intervalo'] == 1){

                                        $data_inicio_compara = date('Y-m-d H:i:s', strtotime("+0 minutes",strtotime($conteudo_invertidos[$consulta_inicial_banco])));
                                        $data_fim_compara = date('Y-m-d H:i:s', strtotime("+0 minutes",strtotime($conteudo_invertidos[$consulta_final_banco])));

                                        if($data_inicio_compara > $data_fim_compara){
                                            $data_inicio_compara = date('Y-m-d H:i:s', strtotime("-1 days",strtotime($conteudo_invertidos[$consulta_inicial_banco])));
                                        }
                                        
                                        $diferenca_datas = strtotime($data_fim_compara) - strtotime($data_inicio_compara);
                                        $tempo_total_trabalho = converteSegundosHoras($diferenca_datas);
                                        $tempo_total_trabalho = converteHorasDecimal($tempo_total_trabalho);
                                        $tempo_total_intervalo = converteHorasDecimal($dados_intervalo_invertidos[0]['tempo_intervalo']);
                                        $tempo_desconto = $tempo_total_intervalo/$tempo_total_trabalho;

                                        if($inicial_hora_funcionario[0] == $hora_zero && $inicial_hora_funcionario[1] != '00'){
                                            $valor_final = converteHorasDecimal('00:'.$inicial_hora_funcionario[1]);
                                            $cont_atendentes_escala += (1 - ($valor_final)) - ($tempo_desconto * (1 -  ($valor_final)));
                                        }else{
                                            $cont_atendentes_escala += 1 - $tempo_desconto;
                                        }

                                    }else{
                                        $inicial_hora_intervalo = explode(":", $dados_intervalo_invertidos[0]['intervalo_inicial']);
                                        $final_hora_intervalo = explode(":", $dados_intervalo_invertidos[0]['intervalo_final']);

                                        if($inicial_hora_funcionario[0] == $hora_zero && $inicial_hora_funcionario[1] != '00'){
                                            if($inicial_hora_intervalo[0] == $hora_zero && $final_hora_intervalo[0] == $hora_zero){
                                                $valor_final = converteHorasDecimal('00:'.$inicial_hora_funcionario[1]);
                                                $desconto_intervalo = converteHorasDecimal('00:'.($final_hora_intervalo[1] - $inicial_hora_intervalo[1]));
                                                $cont_atendentes_escala += (1 - ($valor_final)) - ($desconto_intervalo);
                                            }else if($inicial_hora_intervalo[0] == $hora_zero){
                                                $valor_final = converteHorasDecimal('00:'.$inicial_hora_funcionario[1]);
                                                $desconto_intervalo =  converteHorasDecimal('00:'.$inicial_hora_intervalo[1]);
                                                $cont_atendentes_escala += ($valor_final - (1 - $desconto_intervalo));
                                            }else{
                                                $valor_final = converteHorasDecimal('00:'.$inicial_hora_funcionario[1]);
                                                $cont_atendentes_escala += 1 - ($valor_final);
                                            }
                                        }else{
                                           
                                            if($inicial_hora_intervalo[0] == $hora_zero && $final_hora_intervalo[0] == $hora_zero){
                                                $valor_final = converteHorasDecimal('00:'.$inicial_hora_funcionario[1]);
                                                $desconto_intervalo = converteHorasDecimal('00:'.($final_hora_intervalo[1] - $inicial_hora_intervalo[1]));
                                                $cont_atendentes_escala += 1 - $desconto_intervalo;
                                            }else if($inicial_hora_intervalo[0] == $hora_zero){
                                                $valor_final = converteHorasDecimal('00:'.$inicial_hora_funcionario[1]);
                                                $desconto_intervalo = converteHorasDecimal('00:'.$inicial_hora_intervalo[1]);
                                                $cont_atendentes_escala += $desconto_intervalo;
                                            }else if($final_hora_intervalo[0] == $hora_zero){
                                                $desconto_intervalo = converteHorasDecimal('00:'.$final_hora_intervalo[1]);
                                                $cont_atendentes_escala += 1 - $desconto_intervalo;
                                            }else if($inicial_hora_intervalo[0] < $hora_zero && $final_hora_intervalo[0] > $hora_zero){
                                                $cont_atendentes_escala += 0;
                                            }else{
                                                $cont_atendentes_escala += 1;
                                            }
                                        }
                                    }
                                }                               
                            }
                        }
                    }
                }

                if($dados_invertidos_especiais){
                    
                    foreach ($dados_invertidos_especiais as $conteudo_invertidos_especiais) {
                        $inicial_hora_funcionario = explode(":", $conteudo_invertidos_especiais['inicial_especial']);

                        if($inicial_hora_funcionario[0] <= $hora_zero){

                            if(($turno == 1 && $conteudo_invertidos_especiais['carga_horaria'] == 'integral') || ($turno == 2 && $conteudo_invertidos_especiais['carga_horaria'] == 'meio') || ($turno == 3 && $conteudo_invertidos_especiais['carga_horaria'] == 'jovem') || ($turno == 4 && $conteudo_invertidos_especiais['carga_horaria'] == 'estagio') || (!$turno) ){
                                
                                if(!$conteudo_invertidos_especiais['tempo_intervalo']){
                                    if($inicial_hora_funcionario[0] == $hora_zero && $inicial_hora_funcionario[1] != '00'){
                                        $valor_final = converteHorasDecimal('00:'.$inicial_hora_funcionario[1]);
                                        $cont_atendentes_escala += 1-($valor_final);
                                    }else{
                                        $cont_atendentes_escala += 1;
                                    }
                                }else{
                                    $data_inicio_compara = date('Y-m-d H:i:s', strtotime("+0 minutes",strtotime($conteudo_invertidos_especiais['inicial_especial'])));
                                    $data_fim_compara = date('Y-m-d H:i:s', strtotime("+0 minutes",strtotime($conteudo_invertidos_especiais['final_especial'])));

                                    if($data_inicio_compara > $data_fim_compara){
                                        $data_inicio_compara = date('Y-m-d H:i:s', strtotime("-1 days",strtotime($conteudo_invertidos_especiais['inicial_especial'])));
                                    }

                                    $diferenca_datas = strtotime($data_fim_compara) - strtotime($data_inicio_compara);
                                    
                                    $tempo_total_trabalho = converteSegundosHoras($diferenca_datas);
                                    $tempo_total_trabalho = converteHorasDecimal($tempo_total_trabalho);
                                    $tempo_total_intervalo = converteHorasDecimal($conteudo_invertidos_especiais['tempo_intervalo']);
                                    $tempo_desconto = $tempo_total_intervalo/$tempo_total_trabalho;

                                    if($inicial_hora_funcionario[0] == $hora_zero && $inicial_hora_funcionario[1] != '00'){
                                        $valor_final = converteHorasDecimal('00:'.$inicial_hora_funcionario[1]);
                                        $cont_atendentes_escala += (1 - ($valor_final)) - ($tempo_desconto * (1 -  ($valor_final)));
                                    }else{
                                        $cont_atendentes_escala += 1 - $tempo_desconto;
                                    }
                                }
                            }
                        }
                    }
                }

                //INVERTIDOS ONTEM
                if($dados_invertidos_ontem){

                    foreach ($dados_invertidos_ontem as $conteudo_ontem) {
                        $final_hora_funcionario = explode(":", $conteudo_ontem[$consulta_final_banco_ontem]);

                        if($final_hora_funcionario[0] >= $hora_zero){

                            if(($turno == 1 && $conteudo_ontem['carga_horaria'] == 'integral') || ($turno == 2 && $conteudo_ontem['carga_horaria'] == 'meio') || ($turno == 3 && $conteudo_ontem['carga_horaria'] == 'jovem') || ($turno == 4 && $conteudo_ontem['carga_horaria'] == 'estagio') || (!$turno) ){

                                $dados_intervalo_invertidos_ontem = DBRead('', 'tb_horarios_escala_intervalo', "WHERE id_horarios_escala = '".$conteudo_ontem['id_horarios_escala']."' AND dia = '".$dia_intervalo_ontem."' ");

                                if(!$dados_intervalo_invertidos_ontem){
                                    if($final_hora_funcionario[0] == $hora_zero && $final_hora_funcionario[1] != '00'){
                                        $valor_final = converteHorasDecimal('00:'.$final_hora_funcionario[1]);
                                        $cont_atendentes_escala += $valor_final;
                                    }else{
                                        $cont_atendentes_escala += 1;
                                    }
                                }else{
                                    if($dados_intervalo_invertidos_ontem[0]['tipo_intervalo'] == 1){
                                        $data_inicio_compara = date('Y-m-d H:i:s', strtotime("+0 minutes",strtotime($conteudo_ontem[$consulta_inicial_banco_ontem])));
                                        $data_fim_compara = date('Y-m-d H:i:s', strtotime("+0 minutes",strtotime($conteudo_ontem[$consulta_final_banco_ontem])));
                                
                                        if($data_inicio_compara > $data_fim_compara){
                                            $data_inicio_compara = date('Y-m-d H:i:s', strtotime("-1 days",strtotime($conteudo_ontem[$consulta_inicial_banco_ontem])));
                                        }
                                        
                                        $diferenca_datas = strtotime($data_fim_compara) - strtotime($data_inicio_compara);
                                        $tempo_total_trabalho = converteSegundosHoras($diferenca_datas);
                                        $tempo_total_trabalho = converteHorasDecimal($tempo_total_trabalho);
                                        $tempo_total_intervalo = converteHorasDecimal($dados_intervalo_invertidos_ontem[0]['tempo_intervalo']);
                                        $tempo_desconto = $tempo_total_intervalo/$tempo_total_trabalho;

                                        if($final_hora_funcionario[0] == $hora_zero && $final_hora_funcionario[1] != '00'){
                                            $valor_final = converteHorasDecimal('00:'.$final_hora_funcionario[1]);
                                            $cont_atendentes_escala += (($valor_final) - ($tempo_desconto * $valor_final));
                                        }else{
                                            $cont_atendentes_escala += 1 - $tempo_desconto;
                                        } 

                                    }else{

                                        $inicial_hora_intervalo = explode(":", $dados_intervalo_invertidos_ontem[0]['intervalo_inicial']);
                                        $final_hora_intervalo = explode(":", $dados_intervalo_invertidos_ontem[0]['intervalo_final']);
                                    
                                        if($final_hora_funcionario[0] == $hora_zero && $final_hora_funcionario[1] != '00'){
                                            if($inicial_hora_intervalo[0] == $hora_zero && $final_hora_intervalo[0] == $hora_zero){
                                                $valor_final = converteHorasDecimal('00:'.$final_hora_funcionario[1]);
                                                $desconto_intervalo = converteHorasDecimal('00:'.($final_hora_intervalo[1] - $inicial_hora_intervalo[1]));
                                                $cont_atendentes_escala += 1 - ($valor_final + $desconto_intervalo);
                                            }else if($final_hora_intervalo[0] == $hora_zero){
                                                $valor_final = converteHorasDecimal('00:'.$final_hora_funcionario[1]);
                                                $desconto_intervalo = converteHorasDecimal('00:'.$final_hora_intervalo[1]);	
                                                $cont_atendentes_escala += ($valor_final - $desconto_intervalo);
                                            }else{
                                                $valor_final = converteHorasDecimal('00:'.$final_hora_funcionario[1]);
                                                $cont_atendentes_escala += $valor_final;
                                            }
                                        }else{
                                            if($inicial_hora_intervalo[0] == $hora_zero && $final_hora_intervalo[0] == $hora_zero){
                                                $valor_final = converteHorasDecimal('00:'.$inicial_hora_funcionario[1]);
                                                $desconto_intervalo = converteHorasDecimal('00:'.($final_hora_intervalo[1] - $inicial_hora_intervalo[1]));
                                                $cont_atendentes_escala += 1 - $desconto_intervalo;
                                            }else if($inicial_hora_intervalo[0] == $hora_zero){
                                                $valor_final = converteHorasDecimal('00:'.$inicial_hora_funcionario[1]);
                                                $desconto_intervalo = converteHorasDecimal('00:'.$inicial_hora_intervalo[1]);
                                                $cont_atendentes_escala += $desconto_intervalo;
                                            }else if($final_hora_intervalo[0] == $hora_zero){
                                                $desconto_intervalo = converteHorasDecimal('00:'.$final_hora_intervalo[1]);
                                                $cont_atendentes_escala += 1 - $desconto_intervalo;
                                            }else if($inicial_hora_intervalo[0] < $hora_zero && $final_hora_intervalo[0] > $hora_zero){
                                                $cont_atendentes_escala += 0;
                                            }else{
                                                $cont_atendentes_escala += 1;
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }

                if($dados_invertidos_ontem_especiais){

                    foreach ($dados_invertidos_ontem_especiais as $conteudo_invertidos_ontem_especiais) {
                        $final_hora_funcionario = explode(":", $conteudo_invertidos_ontem_especiais['final_especial']);
                        if($final_hora_funcionario[0] >= $hora_zero){

                            if(($turno == 1 && $conteudo_invertidos_ontem_especiais['carga_horaria'] == 'integral') || ($turno == 2 && $conteudo_invertidos_ontem_especiais['carga_horaria'] == 'meio') || ($turno == 3 && $conteudo_invertidos_ontem_especiais['carga_horaria'] == 'jovem') || ($turno == 4 && $conteudo_invertidos_ontem_especiais['carga_horaria'] == 'estagio') || (!$turno) ){
                                if(!$conteudo_invertidos_ontem_especiais['tempo_intervalo']){
                                    if($final_hora_funcionario[0] == $hora_zero && $final_hora_funcionario[1] != '00'){
                                        $valor_final = converteHorasDecimal('00:'.$final_hora_funcionario[1]);
                                        $cont_atendentes_escala += $valor_final;
                                    }else{
                                        $cont_atendentes_escala += 1;
                                    }
                                }else{
                                    $data_inicio_compara = date('Y-m-d H:i:s', strtotime("+0 minutes",strtotime($conteudo_invertidos_ontem_especiais['inicial_especial'])));
                                    $data_fim_compara = date('Y-m-d H:i:s', strtotime("+0 minutes",strtotime($conteudo_invertidos_ontem_especiais['final_especial'])));

                                    if($data_inicio_compara > $data_fim_compara){
                                        $data_inicio_compara = date('Y-m-d H:i:s', strtotime("-1 days",strtotime($conteudo_invertidos_ontem_especiais['inicial_especial'])));
                                    }

                                    $diferenca_datas = strtotime($data_fim_compara) - strtotime($data_inicio_compara);
                                    $tempo_total_trabalho = converteSegundosHoras($diferenca_datas);
                                    $tempo_total_trabalho = converteHorasDecimal($tempo_total_trabalho);
                                    $tempo_total_intervalo = converteHorasDecimal($conteudo_invertidos_ontem_especiais['tempo_intervalo']);
                                    $tempo_desconto = $tempo_total_intervalo/$tempo_total_trabalho;

                                    if($final_hora_funcionario[0] == $hora_zero && $final_hora_funcionario[1] != '00'){
                                        $valor_final = converteHorasDecimal('00:'.$final_hora_funcionario[1]);
                                        $cont_atendentes_escala += (($valor_final) - ($tempo_desconto * $valor_final));
                                    }else{
                                        $cont_atendentes_escala += 1 - $tempo_desconto;
                                    }
                                }
                            }
                        }
                    }
                }

                if(!$cont_atendentes_escala){
                    $cont_atendentes_escala = 0;
                }
                $atendentes_escala[] = sprintf("%01.2f", round($cont_atendentes_escala, 2));
                
            //fim codigo atendentes escala

            $horas[] = $hora_zero.':00';
            $hora++;
        }
        ?>
        <div id="<?php echo "chart-" . $chart; ?>" class="oi"></div> 
        <script>
            $(function () {
                // Create the first chart
                $('#<?php echo "chart-" . $chart; ?>').highcharts({
                    chart: {
                        type: '<?=$nome_tipo_grafico;?>'
                    },
                    title: {
                        text: 'Dia: <?php echo $dias_semana[$numero_dia_semana].', '.converteData($data); ?>' // Title for the chart
                    },
                    xAxis: {
                        categories: <?php echo json_encode($horas) ?>
                        // Categories for the charts
                    },
                    yAxis: {
                        min: 0,
                        title: {
                            text: 'Atendentes por Hora'
                        },
                        stackLabels: {
                            enabled: true,
                            style: {
                                fontWeight: 'bold',
                                color: (Highcharts.theme && Highcharts.theme.textColor) || 'black'
                            }
                        }
                    },
                    legend: {
                        enabled:true,
                        backgroundColor: (Highcharts.theme && Highcharts.theme.background2) || 'white',
                        borderColor: '#CCC',
                        borderWidth: 1,
                        shadow: false
                    },
                    tooltip: {
                        headerFormat: '<b>{point.x}</b><br/>',
                        pointFormat: '{series.name}: {point.y}'
                    },
                    plotOptions: {
                        <?php 
                        if($tipo_grafico == 3){//se for pilha
                            echo "
                            column: {
                                stacking: 'normal',
                                dataLabels: {
                                    enabled: true,
                                    color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white'
                                }
                            }
                            ";
                        }else{
                            echo "                                  
                            series: {
                                dataLabels: {
                                    enabled: true
                                }
                            }
                            ";
                        }
                        ?>   
                    },
                    series: [
                        {
                            name: 'Atendentes logados na central', // Name of your series
                            data: <?php echo json_encode($atendentes_central, JSON_NUMERIC_CHECK); ?> // The data in your series

                        },
                        {
                            name: 'Atendentes na escala', // Name of your series
                            data: <?php echo json_encode($atendentes_escala, JSON_NUMERIC_CHECK); ?> // The data in your series

                        }
                    ],
                    navigation: {
                        buttonOptions: {
                            enabled: true
                        }
                    }
                });
            });
        </script>   
        <?php
        echo '<hr>';
        $chart++;
    }
}

function relatorio_grafico_atendentes_20min($data_de, $data_ate, $turno){

    $dias_semana = array('Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado');
    $data_hora = converteDataHora(getDataHora());
    $tipo_grafico = 1;
    
    if($turno == 1){
        $legenda_turno = 'Integral';
    }else if($turno == 2){
        $legenda_turno = 'Meio Turno';
    }else if($turno == 3){
        $legenda_turno = 'Jovem Aprendiz';
    }else if($turno == 4){
        $legenda_turno = 'Estágio';
    }else{
        $legenda_turno = 'Qualquer';
    }

    if($tipo_grafico == 1){
        $nome_tipo_grafico = 'line';
    }else{
        $nome_tipo_grafico = 'column';
    }

    $gerado = "<span style=\"font-size: 14px;\"><strong>Gerado em: </strong> $data_hora</span>";

    echo "<div class=\"col-md-12\" style=\"padding: 0\">";
    echo "<legend style=\"text-align:center;\"><strong>Gráfico de Atendentes por 20min - Separado por Dia do Mês</strong><br>$gerado</legend>";
    echo "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong>Data Inicial da Escala: </strong>".$data_de.", <strong>Data Final da Escala: </strong>".$data_ate." <strong>Carga Horária: </strong>".$legenda_turno."</legend></span>";
    
    $chart = 0;
    foreach(rangeDatas(converteData($data_de), converteData($data_ate)) as $data){
        $atendentes_central = array();
        $atendentes_escala = array();
        $horas = array();
        $numero_dia_semana = date('w', strtotime($data));
        $hora = 0;
        $hora_array = 0;

        while ($hora < 24) {

            $consulta_data = explode("-", $data);
            $data_ontem = date('Y-m-d', strtotime("-1 days",strtotime($data)));
            $consulta_data = $consulta_data[0].'-'.$consulta_data[1].'-01';

            //MEU TESTE
            $dia_hoje = date('w', strtotime($data));
            $dia_ontem = date('w', strtotime($data_ontem));
            $dia_hoje = $dias_semana[$dia_hoje];
            $dia_ontem = $dias_semana[$dia_ontem];
            $consulta_data_ontem = explode("-", $data_ontem);
            $consulta_data_ontem = $consulta_data_ontem[0].'-'.$consulta_data_ontem[1].'-01';
            $hora_zero = sprintf('%02d', $hora);
            $hora_proxima = sprintf('%02d', $hora_zero+1);  
            $cont_atendentes_escala = 0;
            $cont_atendentes_central = 0;
            
            //início do: pega logins antes da HI e logoffs entre HI e HF
                $dados_atendentes_central = DBRead('snep','queue_agents_log',"WHERE data_login < '$data $hora_zero:00:00' AND data_logoff BETWEEN '$data $hora_zero:00:00' AND '$data $hora_zero:19:59'");
                if($dados_atendentes_central){
                    foreach($dados_atendentes_central as $conteudo_atendentes_central){
                        $dados_usuario = DBRead('','tb_usuario a',"INNER JOIN tb_horarios_escala b ON a.id_usuario = b.id_usuario WHERE a.id_asterisk = '".$conteudo_atendentes_central['codigo']."' ","a.id_usuario, b.inicial_seg, b.final_seg");
                        $id_usuario = $dados_usuario[0]['id_usuario'];
                        
                        if($id_usuario){          
                            if(($turno == 1 && $dados_usuario[0]['carga_horaria'] == 'integral') || ($turno == 2 && $dados_usuario[0]['carga_horaria'] == 'meio') || ($turno == 3 && $dados_usuario[0]['carga_horaria'] == 'jovem') || ($turno == 4 && $dados_usuario[0]['carga_horaria'] == 'estagio') || (!$turno) ){                     
                                $cont_atendentes_central += sprintf("%01.2f", converteHorasDecimal('00:'.substr($conteudo_atendentes_central['data_logoff'],14,5))*3);
                            }
                        }
                    }                   
                }
            //fim do: pega logins antes da HI e logoffs entre HI e HF

            //início do: pega logins entre HI e HF e logoffs entre HI e HF
                $dados_atendentes_central = DBRead('snep','queue_agents_log',"WHERE data_login BETWEEN '$data $hora_zero:00:00' AND '$data $hora_zero:19:59' AND data_logoff BETWEEN '$data $hora_zero:00:00' AND '$data $hora_zero:19:59'");

                if($dados_atendentes_central){
                    foreach($dados_atendentes_central as $conteudo_atendentes_central){
                        $dados_usuario = DBRead('','tb_usuario a',"INNER JOIN tb_horarios_escala b ON a.id_usuario = b.id_usuario WHERE a.id_asterisk = '".$conteudo_atendentes_central['codigo']."' ","a.id_usuario, b.inicial_seg, b.final_seg");
                        $id_usuario = $dados_usuario[0]['id_usuario'];
                        
                        if($id_usuario){   
                            if(($turno == 1 && $dados_usuario[0]['carga_horaria'] == 'integral') || ($turno == 2 && $dados_usuario[0]['carga_horaria'] == 'meio') || ($turno == 3 && $dados_usuario[0]['carga_horaria'] == 'jovem') || ($turno == 4 && $dados_usuario[0]['carga_horaria'] == 'estagio') || (!$turno) ){                         
                                $cont_atendentes_central += sprintf("%01.2f", (converteHorasDecimal('00:'.substr($conteudo_atendentes_central['data_logoff'],14,5))-converteHorasDecimal('00:'.substr($conteudo_atendentes_central['data_login'],14,5)))*3);
                            }
                        }
                    }
                }
            //fim do: pega logins entre HI e HF e logoffs entre HI e HF

            //início do: pega logins entre HI e HF e logoffs depois da HF
                $dados_atendentes_central = DBRead('snep','queue_agents_log',"WHERE data_login BETWEEN '$data $hora_zero:00:00' AND '$data $hora_zero:19:59' AND data_logoff > '$data $hora_zero:19:59'");
                if($dados_atendentes_central){
                    foreach($dados_atendentes_central as $conteudo_atendentes_central){
                        $dados_usuario = DBRead('','tb_usuario a',"INNER JOIN tb_horarios_escala b ON a.id_usuario = b.id_usuario WHERE a.id_asterisk = '".$conteudo_atendentes_central['codigo']."' ","a.id_usuario, b.inicial_seg, b.final_seg");
                        $id_usuario = $dados_usuario[0]['id_usuario'];
                        
                        if($id_usuario){
                            if(($turno == 1 && $dados_usuario[0]['carga_horaria'] == 'integral') || ($turno == 2 && $dados_usuario[0]['carga_horaria'] == 'meio') || ($turno == 3 && $dados_usuario[0]['carga_horaria'] == 'jovem') || ($turno == 4 && $dados_usuario[0]['carga_horaria'] == 'estagio') || (!$turno) ){
                                $cont_atendentes_central += 1-sprintf("%01.2f", converteHorasDecimal('00:'.substr($conteudo_atendentes_central['data_login'],14,5))*3);                                
                            }
                        }
                    }
                }
            //fim do: pega logins entre HI e HF e logoffs depois da HF

            //início do: pega logins antes da HI e logoffs depois da HF
                $dados_atendentes_central = DBRead('snep','queue_agents_log',"WHERE data_login < '$data $hora_zero:00:00' AND data_logoff > '$data $hora_zero:19:59'");
                if($dados_atendentes_central){
                    foreach($dados_atendentes_central as $conteudo_atendentes_central){
                        $dados_usuario = DBRead('','tb_usuario a',"INNER JOIN tb_horarios_escala b ON a.id_usuario = b.id_usuario WHERE a.id_asterisk = '".$conteudo_atendentes_central['codigo']."' ","a.id_usuario, b.inicial_seg, b.final_seg");
                        $id_usuario = $dados_usuario[0]['id_usuario'];
                        
                        if($id_usuario){
                            if(($turno == 1 && $dados_usuario[0]['carga_horaria'] == 'integral') || ($turno == 2 && $dados_usuario[0]['carga_horaria'] == 'meio') || ($turno == 3 && $dados_usuario[0]['carga_horaria'] == 'jovem') || ($turno == 4 && $dados_usuario[0]['carga_horaria'] == 'estagio') || (!$turno) ){
                                $cont_atendentes_central += 1;                                
                            }
                        }
                    }
                }
            //fim do: pega logins antes da HI e logoffs depois da HF

            $atendentes_central[$hora_array] = sprintf("%01.2f", round($cont_atendentes_central, 2));

            $cont_atendentes_central = 0;

            //início do: pega logins antes da HI e logoffs entre HI e HF
                $dados_atendentes_central = DBRead('snep','queue_agents_log',"WHERE data_login < '$data $hora_zero:20:00' AND data_logoff BETWEEN '$data $hora_zero:20:00' AND '$data $hora_zero:39:59'");
                if($dados_atendentes_central){
                    foreach($dados_atendentes_central as $conteudo_atendentes_central){
                        $dados_usuario = DBRead('','tb_usuario a',"INNER JOIN tb_horarios_escala b ON a.id_usuario = b.id_usuario WHERE a.id_asterisk = '".$conteudo_atendentes_central['codigo']."' ","a.id_usuario, b.inicial_seg, b.final_seg");
                        $id_usuario = $dados_usuario[0]['id_usuario'];
                        
                        if($id_usuario){
                            if(($turno == 1 && $dados_usuario[0]['carga_horaria'] == 'integral') || ($turno == 2 && $dados_usuario[0]['carga_horaria'] == 'meio') || ($turno == 3 && $dados_usuario[0]['carga_horaria'] == 'jovem') || ($turno == 4 && $dados_usuario[0]['carga_horaria'] == 'estagio') || (!$turno) ){
                                $cont_atendentes_central += sprintf("%01.2f", converteHorasDecimal('00:'.sprintf('%02d', (substr($conteudo_atendentes_central['data_logoff'],14,2)-20)).substr($conteudo_atendentes_central['data_logoff'],16,3))*3);                                
                            }
                        }
                    }                   
                }
            //fim do: pega logins antes da HI e logoffs entre HI e HF

            //início do: pega logins entre HI e HF e logoffs entre HI e HF
                $dados_atendentes_central = DBRead('snep','queue_agents_log',"WHERE data_login BETWEEN '$data $hora_zero:20:00' AND '$data $hora_zero:39:59' AND data_logoff BETWEEN '$data $hora_zero:20:00' AND '$data $hora_zero:39:59'");

                if($dados_atendentes_central){
                    foreach($dados_atendentes_central as $conteudo_atendentes_central){
                        $dados_usuario = DBRead('','tb_usuario a',"INNER JOIN tb_horarios_escala b ON a.id_usuario = b.id_usuario WHERE a.id_asterisk = '".$conteudo_atendentes_central['codigo']."' ","a.id_usuario, b.inicial_seg, b.final_seg");
                        $id_usuario = $dados_usuario[0]['id_usuario'];
                        
                        if($id_usuario){
                            if(($turno == 1 && $dados_usuario[0]['carga_horaria'] == 'integral') || ($turno == 2 && $dados_usuario[0]['carga_horaria'] == 'meio') || ($turno == 3 && $dados_usuario[0]['carga_horaria'] == 'jovem') || ($turno == 4 && $dados_usuario[0]['carga_horaria'] == 'estagio') || (!$turno) ){
                                $cont_atendentes_central += sprintf("%01.2f", (converteHorasDecimal('00:'.sprintf('%02d', (substr($conteudo_atendentes_central['data_logoff'],14,2)-20)).substr($conteudo_atendentes_central['data_logoff'],16,3))-converteHorasDecimal('00:'.sprintf('%02d', (substr($conteudo_atendentes_central['data_login'],14,2)-20)).substr($conteudo_atendentes_central['data_login'],16,3)))*3);                             
                            }
                        }
                        
                    }
                }
            //fim do: pega logins entre HI e HF e logoffs entre HI e HF

            //início do: pega logins entre HI e HF e logoffs depois da HF
                $dados_atendentes_central = DBRead('snep','queue_agents_log',"WHERE data_login BETWEEN '$data $hora_zero:20:00' AND '$data $hora_zero:39:59' AND data_logoff > '$data $hora_zero:39:59'");
                if($dados_atendentes_central){
                    foreach($dados_atendentes_central as $conteudo_atendentes_central){
                        $dados_usuario = DBRead('','tb_usuario a',"INNER JOIN tb_horarios_escala b ON a.id_usuario = b.id_usuario WHERE a.id_asterisk = '".$conteudo_atendentes_central['codigo']."' ","a.id_usuario, b.inicial_seg, b.final_seg");
                        $id_usuario = $dados_usuario[0]['id_usuario'];
                        
                        if($id_usuario){
                            if(($turno == 1 && $dados_usuario[0]['carga_horaria'] == 'integral') || ($turno == 2 && $dados_usuario[0]['carga_horaria'] == 'meio') || ($turno == 3 && $dados_usuario[0]['carga_horaria'] == 'jovem') || ($turno == 4 && $dados_usuario[0]['carga_horaria'] == 'estagio') || (!$turno) ){
                                $cont_atendentes_central += 1-sprintf("%01.2f", converteHorasDecimal('00:'.sprintf('%02d', (substr($conteudo_atendentes_central['data_login'],14,2)-20)).substr($conteudo_atendentes_central['data_login'],16,3))*3);  
                            }
                        }
                    }
                }
            //fim do: pega logins entre HI e HF e logoffs depois da HF

            //início do: pega logins antes da HI e logoffs depois da HF
                $dados_atendentes_central = DBRead('snep','queue_agents_log',"WHERE data_login < '$data $hora_zero:20:00' AND data_logoff > '$data $hora_zero:39:59'");
                if($dados_atendentes_central){
                    foreach($dados_atendentes_central as $conteudo_atendentes_central){
                        $dados_usuario = DBRead('','tb_usuario a',"INNER JOIN tb_horarios_escala b ON a.id_usuario = b.id_usuario WHERE a.id_asterisk = '".$conteudo_atendentes_central['codigo']."' ","a.id_usuario, b.inicial_seg, b.final_seg");
                        $id_usuario = $dados_usuario[0]['id_usuario'];
                        
                        if($id_usuario){
                            if(($turno == 1 && $dados_usuario[0]['carga_horaria'] == 'integral') || ($turno == 2 && $dados_usuario[0]['carga_horaria'] == 'meio') || ($turno == 3 && $dados_usuario[0]['carga_horaria'] == 'jovem') || ($turno == 4 && $dados_usuario[0]['carga_horaria'] == 'estagio') || (!$turno) ){
                                $cont_atendentes_central += 1;                                
                            }
                        }
                    }
                }
            //fim do: pega logins antes da HI e logoffs depois da HF

            $atendentes_central[$hora_array+1] = sprintf("%01.2f", round($cont_atendentes_central, 2));
            $cont_atendentes_central = 0;

            //início do: pega logins antes da HI e logoffs entre HI e HF
                $dados_atendentes_central = DBRead('snep','queue_agents_log',"WHERE data_login < '$data $hora_zero:40:00' AND data_logoff BETWEEN '$data $hora_zero:40:00' AND '$data $hora_zero:59:59'");
                if($dados_atendentes_central){
                    foreach($dados_atendentes_central as $conteudo_atendentes_central){
                        $dados_usuario = DBRead('','tb_usuario a',"INNER JOIN tb_horarios_escala b ON a.id_usuario = b.id_usuario WHERE a.id_asterisk = '".$conteudo_atendentes_central['codigo']."' ","a.id_usuario, b.inicial_seg, b.final_seg");
                        $id_usuario = $dados_usuario[0]['id_usuario'];
                        
                        if($id_usuario){
                            if(($turno == 1 && $dados_usuario[0]['carga_horaria'] == 'integral') || ($turno == 2 && $dados_usuario[0]['carga_horaria'] == 'meio') || ($turno == 3 && $dados_usuario[0]['carga_horaria'] == 'jovem') || ($turno == 4 && $dados_usuario[0]['carga_horaria'] == 'estagio') || (!$turno) ){
                                $cont_atendentes_central += sprintf("%01.2f", converteHorasDecimal('00:'.sprintf('%02d', (substr($conteudo_atendentes_central['data_logoff'],14,2)-40)).substr($conteudo_atendentes_central['data_logoff'],16,3))*3);                                
                            }
                        }
                    }                   
                }
            //fim do: pega logins antes da HI e logoffs entre HI e HF

            //início do: pega logins entre HI e HF e logoffs entre HI e HF
                $dados_atendentes_central = DBRead('snep','queue_agents_log',"WHERE data_login BETWEEN '$data $hora_zero:40:00' AND '$data $hora_zero:59:59' AND data_logoff BETWEEN '$data $hora_zero:40:00' AND '$data $hora_zero:59:59'");

                if($dados_atendentes_central){
                    foreach($dados_atendentes_central as $conteudo_atendentes_central){
                        $dados_usuario = DBRead('','tb_usuario a',"INNER JOIN tb_horarios_escala b ON a.id_usuario = b.id_usuario WHERE a.id_asterisk = '".$conteudo_atendentes_central['codigo']."' ","a.id_usuario, b.inicial_seg, b.final_seg");
                        $id_usuario = $dados_usuario[0]['id_usuario'];
                        
                        if($id_usuario){
                            if(($turno == 1 && $dados_usuario[0]['carga_horaria'] == 'integral') || ($turno == 2 && $dados_usuario[0]['carga_horaria'] == 'meio') || ($turno == 3 && $dados_usuario[0]['carga_horaria'] == 'jovem') || ($turno == 4 && $dados_usuario[0]['carga_horaria'] == 'estagio') || (!$turno) ){
                                $cont_atendentes_central += sprintf("%01.2f", (converteHorasDecimal('00:'.sprintf('%02d', (substr($conteudo_atendentes_central['data_logoff'],14,2)-40)).substr($conteudo_atendentes_central['data_logoff'],16,3))-converteHorasDecimal('00:'.sprintf('%02d', (substr($conteudo_atendentes_central['data_login'],14,2)-40)).substr($conteudo_atendentes_central['data_login'],16,3)))*3);                             
                            }
                        }
                    }
                }
            //fim do: pega logins entre HI e HF e logoffs entre HI e HF

            //início do: pega logins entre HI e HF e logoffs depois da HF
                $dados_atendentes_central = DBRead('snep','queue_agents_log',"WHERE data_login BETWEEN '$data $hora_zero:40:00' AND '$data $hora_zero:59:59' AND data_logoff > '$data $hora_zero:59:59'");
                if($dados_atendentes_central){
                    foreach($dados_atendentes_central as $conteudo_atendentes_central){
                        $dados_usuario = DBRead('','tb_usuario a',"INNER JOIN tb_horarios_escala b ON a.id_usuario = b.id_usuario WHERE a.id_asterisk = '".$conteudo_atendentes_central['codigo']."' ","a.id_usuario, b.inicial_seg, b.final_seg");
                        $id_usuario = $dados_usuario[0]['id_usuario'];
                        
                        if($id_usuario){  
                            if(($turno == 1 && $dados_usuario[0]['carga_horaria'] == 'integral') || ($turno == 2 && $dados_usuario[0]['carga_horaria'] == 'meio') || ($turno == 3 && $dados_usuario[0]['carga_horaria'] == 'jovem') || ($turno == 4 && $dados_usuario[0]['carga_horaria'] == 'estagio') || (!$turno) ){
                                $cont_atendentes_central += 1-sprintf("%01.2f", converteHorasDecimal('00:'.sprintf('%02d', (substr($conteudo_atendentes_central['data_login'],14,2)-40)).substr($conteudo_atendentes_central['data_login'],16,3))*3);  
                            }
                        }
                    }
                }
            //fim do: pega logins entre HI e HF e logoffs depois da HF

            //início do: pega logins antes da HI e logoffs depois da HF
                $dados_atendentes_central = DBRead('snep','queue_agents_log',"WHERE data_login < '$data $hora_zero:40:00' AND data_logoff > '$data $hora_zero:59:59'");
                if($dados_atendentes_central){
                    foreach($dados_atendentes_central as $conteudo_atendentes_central){
                        $dados_usuario = DBRead('','tb_usuario a',"INNER JOIN tb_horarios_escala b ON a.id_usuario = b.id_usuario WHERE a.id_asterisk = '".$conteudo_atendentes_central['codigo']."' ","a.id_usuario, b.inicial_seg, b.final_seg");
                        $id_usuario = $dados_usuario[0]['id_usuario'];
                        
                        if($id_usuario){
                            if(($turno == 1 && $dados_usuario[0]['carga_horaria'] == 'integral') || ($turno == 2 && $dados_usuario[0]['carga_horaria'] == 'meio') || ($turno == 3 && $dados_usuario[0]['carga_horaria'] == 'jovem') || ($turno == 4 && $dados_usuario[0]['carga_horaria'] == 'estagio') || (!$turno) ){
                                $cont_atendentes_central += 1;
                            }
                        }
                    }
                }
            //fim do: pega logins antes da HI e logoffs depois da HF

            $atendentes_central[$hora_array+2] = sprintf("%01.2f", round($cont_atendentes_central, 2));

            //início codigo atendentes escala 00
                $consulta_data = explode("-", $data);
                $consulta_data = $consulta_data[0].'-'.$consulta_data[1].'-01';       

                //VERIFICA O DIA DA CONSULTA PARA PESQUISAR (SEG A SEX, SÁBADO OU DOMINGO)
                if($dia_hoje == 'Domingo'){
                    $consulta_dia =  "AND inicial_dom < '".$hora_zero.":20' AND final_dom > '".$hora_zero.":00'";
                    
                    $consulta_inicial_banco = 'inicial_dom';
                    $consulta_final_banco = 'final_dom';

                    $consulta_inicial_banco_ontem = 'inicial_sab';
                    $consulta_final_banco_ontem = 'final_sab';

                    $consulta_ontem = "AND final_sab > '".$hora_zero.":00' AND inicial_sab > final_sab AND folga_seg != '".$dia_ontem."'";
                    $consulta_invertidos = "AND inicial_dom < '".$hora_zero.":20' AND inicial_dom > final_dom AND id_horarios_escala NOT IN (SELECT id_horarios_escala FROM tb_folgas_dom WHERE dia = '".$data."')";
                    $consulta_normais = "AND inicial_dom < final_dom AND id_horarios_escala NOT IN (SELECT id_horarios_escala FROM tb_folgas_dom WHERE dia = '".$data."')";

                    $dia_intervalo = 'dom';
                    $dia_intervalo_ontem = 'sab';

                }else if($dia_hoje == 'Sábado'){
                    $consulta_dia =  "AND inicial_sab < '".$hora_zero.":20' AND final_sab > '".$hora_zero.":00'";
                    $consulta_inicial_banco = 'inicial_sab';
                    $consulta_final_banco = 'final_sab';
                    
                    $consulta_inicial_banco_ontem = 'inicial_seg';
                    $consulta_final_banco_ontem = 'final_seg';

                    $consulta_ontem = "AND final_seg > '".$hora_zero.":00' AND inicial_seg > final_seg AND folga_seg != '".$dia_ontem."'";
                    $consulta_invertidos = "AND inicial_sab < '".$hora_zero.":30' AND inicial_sab > final_sab AND folga_seg != '".$dia_hoje."'";
                    $consulta_normais = "AND inicial_sab < final_sab AND folga_seg != '".$dia_hoje."'";

                    $dia_intervalo = 'sab';
                    $dia_intervalo_ontem = 'seg';

                }else{
                    $consulta_dia =  "AND inicial_seg < '".$hora_zero.":20' AND final_seg > '".$hora_zero.":00'";
                    $consulta_inicial_banco = 'inicial_seg';
                    $consulta_final_banco = 'final_seg';

                    if($dia_hoje == 'Quinta' || $dia_hoje == 'Sexta'){
                        $consulta_invertidos = "AND inicial_seg < '".$hora_zero.":20' AND inicial_seg > final_seg AND (folga_seg != '".$dia_hoje."' AND folga_seg != 'Quinta e Sexta')";
                        $consulta_normais = "AND inicial_seg < final_seg AND (folga_seg != '".$dia_hoje."' AND folga_seg != 'Quinta e Sexta')";
                    }else{
                        $consulta_invertidos = "AND inicial_seg < '".$hora_zero.":20' AND inicial_seg > final_seg AND folga_seg != '".$dia_hoje."'";
                        $consulta_normais = "AND inicial_seg < final_seg AND folga_seg != '".$dia_hoje."'";
                    }

                    if($dia_hoje == 'Segunda'){
                        $consulta_ontem = "AND final_dom > '".$hora_zero.":00' AND inicial_dom > final_dom AND id_horarios_escala NOT IN (SELECT id_horarios_escala FROM tb_folgas_dom WHERE dia = '".$data_ontem."')";
                        
                        $consulta_inicial_banco_ontem = 'inicial_dom';
                        $consulta_final_banco_ontem = 'final_dom';
                        $dia_intervalo_ontem = 'dom';

                    }else{

                        if($dia_hoje == 'Sexta'){
                            $consulta_ontem = "AND final_seg > '".$hora_zero.":00' AND inicial_seg > final_seg AND (folga_seg != '".$dia_ontem."' AND folga_seg != 'Quinta e Sexta')";
                        }else{
                            $consulta_ontem = "AND final_seg > '".$hora_zero.":00' AND inicial_seg > final_seg AND folga_seg != '".$dia_ontem."'";
                        }
                    
                        $consulta_inicial_banco_ontem = 'inicial_seg';
                        $consulta_final_banco_ontem = 'final_seg';
                        $dia_intervalo_ontem = 'seg';
                    }
                    $dia_intervalo = 'seg';
                }

                //NORMAIS CONSULTA   

                    $dados_normais = DBRead('', 'tb_horarios_escala', "WHERE data_inicial = '".$consulta_data."' ".$consulta_dia." ".$consulta_normais." AND atendente = 1 AND id_usuario NOT IN (SELECT id_usuario FROM tb_ferias WHERE id_usuario = id_usuario AND data_de <= '".$data."' AND data_ate >= '".$data."') AND id_usuario NOT IN (SELECT a.id_usuario FROM tb_horarios_escala a INNER JOIN tb_horarios_especiais b ON a.id_horarios_escala = b.id_horarios_escala WHERE b.dia = '".$data."')");

                    $dados_normais_especiais = DBRead('', 'tb_horarios_escala a', "INNER JOIN tb_horarios_especiais b ON a.id_horarios_escala = b.id_horarios_escala WHERE b.dia = '".$data."' AND b.inicial_especial < '".$hora_proxima.":20' AND b.final_especial > '".$hora_zero.":00' ");              
                                    
                //NORMAIS 
                if($dados_normais){
                    foreach ($dados_normais as $conteudo_normais) {
                        $inicial_hora_funcionario = explode(":", $conteudo_normais[$consulta_inicial_banco]);
                        $final_hora_funcionario = explode(":", $conteudo_normais[$consulta_final_banco]);
                        
                        if(($inicial_hora_funcionario[0] <= $hora_zero) && ($final_hora_funcionario[0] >= $hora_zero)){
                            
                            if(($turno == 1 && $conteudo_normais['carga_horaria'] == 'integral') || ($turno == 2 && $conteudo_normais['carga_horaria'] == 'meio') || ($turno == 3 && $conteudo_normais['carga_horaria'] == 'jovem') || ($turno == 4 && $conteudo_normais['carga_horaria'] == 'estagio') || (!$turno) ){
                                
                                $dados_intervalo_normais = DBRead('', 'tb_horarios_escala_intervalo', "WHERE id_horarios_escala = '".$conteudo_normais['id_horarios_escala']."' AND dia = '".$dia_intervalo."' ");
                                
                                if(!$dados_intervalo_normais){
                                    if($inicial_hora_funcionario[0] == $hora_zero && $inicial_hora_funcionario[1] != '00' && $inicial_hora_funcionario[1] <= '20'){
                                        $valor_final = converteHorasDecimal('00:'.$inicial_hora_funcionario[1])*3;
                                        $cont_atendentes_escala += 1-($valor_final);
                                    
                                    }else if($final_hora_funcionario[0] == $hora_zero && $final_hora_funcionario[1] != '00' && $final_hora_funcionario[1] <= '20'){
                                        $valor_final = converteHorasDecimal('00:'.$final_hora_funcionario[1])*3;
                                        $cont_atendentes_escala += $valor_final;
                                    
                                    }else{
                                        $cont_atendentes_escala += 1;
                                    }
                                }else{
                                    if($dados_intervalo_normais[0]['tipo_intervalo'] == 1){

                                        $data_inicio_compara = date('Y-m-d H:i:s', strtotime("+0 minutes",strtotime($conteudo_normais[$consulta_inicial_banco])));
                                        $data_fim_compara = date('Y-m-d H:i:s', strtotime("+0 minutes",strtotime($conteudo_normais[$consulta_final_banco])));
                                
                                        if($data_inicio_compara > $data_fim_compara){
                                            $data_inicio_compara = date('Y-m-d H:i:s', strtotime("-1 days",strtotime($conteudo_normais[$consulta_inicial_banco])));
                                        }
                                        
                                        $diferenca_datas = strtotime($data_fim_compara) - strtotime($data_inicio_compara);
                                        
                                        $tempo_total_trabalho = converteSegundosHoras($diferenca_datas);
                                        $tempo_total_trabalho = converteHorasDecimal($tempo_total_trabalho);
                                
                                        $tempo_total_intervalo = converteHorasDecimal($dados_intervalo_normais[0]['tempo_intervalo']);
                                
                                        $tempo_desconto = $tempo_total_intervalo/$tempo_total_trabalho;
                                    
                                        if($inicial_hora_funcionario[0] == $hora_zero && $inicial_hora_funcionario[1] != '00' && $inicial_hora_funcionario[1] <= '20'){
                                            $valor_final = converteHorasDecimal('00:'.$inicial_hora_funcionario[1])*3;
                                            $cont_atendentes_escala += (1 - ($valor_final)) - ($tempo_desconto * (1 -  ($valor_final)));
                                
                                        }else if($final_hora_funcionario[0] == $hora_zero && $final_hora_funcionario[1] != '00' && $final_hora_funcionario[1] <= '20'){
                                            $valor_final = converteHorasDecimal('00:'.$final_hora_funcionario[1])*3;
                                            $cont_atendentes_escala += (($valor_final) - ($tempo_desconto * $valor_final));
                                
                                        }else{
                                            $cont_atendentes_escala += 1 - $tempo_desconto;
                                        }     
                                    
                                    }else{

                                        $inicial_hora_intervalo = explode(":", $dados_intervalo_normais[0]['intervalo_inicial']);
                                        $final_hora_intervalo = explode(":", $dados_intervalo_normais[0]['intervalo_final']);

                                        if($inicial_hora_funcionario[0] == $hora_zero && $inicial_hora_funcionario[1] != '00' && $inicial_hora_funcionario[1] <= '20'){
                                            if($inicial_hora_intervalo[0] == $hora_zero && $final_hora_intervalo[0] == $hora_zero){
                                                if($inicial_hora_intervalo[1] <= '20' && $final_hora_intervalo[1] <= '20'){
                                                    $valor_final = converteHorasDecimal('00:'.$inicial_hora_funcionario[1])*3;
                                                    $desconto_intervalo = converteHorasDecimal('00:'.(20-($final_hora_intervalo[1] - $inicial_hora_intervalo[1]))*3);
                                                    $cont_atendentes_escala += $desconto_intervalo - $valor_final;
                                                }else if($inicial_hora_intervalo[1] <= '20'){
                                                    $desconto_intervalo = converteHorasDecimal('00:'.(20-$inicial_hora_intervalo[1])*3);
                                                    $valor_final = converteHorasDecimal('00:'.$inicial_hora_funcionario[1])*3;
                                                    $cont_atendentes_escala += (1 - ($valor_final)) - ($desconto_intervalo);
                                                }else{
                                                    $cont_atendentes_escala += 1;
                                                }  
                                                
                                            }else if($inicial_hora_intervalo[0] == $hora_zero){
                                                if($inicial_hora_intervalo[1] <= '20'){
                                                    $desconto_intervalo = converteHorasDecimal('00:'.(20-$inicial_hora_intervalo[1])*3);
                                                    $valor_final = converteHorasDecimal('00:'.$inicial_hora_funcionario[1])*3;
                                                    $cont_atendentes_escala += (1 - ($valor_final)) - ($desconto_intervalo);
                                                }else{
                                                    $cont_atendentes_escala += 1;
                                                }
                                               
                                            }else{
                                                $valor_final = converteHorasDecimal('00:'.$inicial_hora_funcionario[1])*3;
                                                $cont_atendentes_escala += 1 - ($valor_final);
                                            }
                                        }else if($final_hora_funcionario[0] == $hora_zero && $final_hora_funcionario[1] != '00' && $final_hora_funcionario[1] <= '20'){
                                            if($inicial_hora_intervalo[0] == $hora_zero && $final_hora_intervalo[0] == $hora_zero){
                                                if($inicial_hora_intervalo[1] <= '20' && $final_hora_intervalo[1] <= '20'){
                                                    $valor_final = converteHorasDecimal('00:'.$inicial_hora_funcionario[1])*3;
                                                    $desconto_intervalo = converteHorasDecimal('00:'.(20-($final_hora_intervalo[1] - $inicial_hora_intervalo[1]))*3);
                                                    $cont_atendentes_escala += $desconto_intervalo - $valor_final;
                                                }else if($inicial_hora_intervalo[1] <= '20'){
                                                    $desconto_intervalo = converteHorasDecimal('00:'.(20-$inicial_hora_intervalo[1])*3);
                                                    $valor_final = converteHorasDecimal('00:'.$inicial_hora_funcionario[1])*3;
                                                    $cont_atendentes_escala += (1 - ($valor_final)) - ($desconto_intervalo);
                                                }else{
                                                    $cont_atendentes_escala += 1;
                                                }
                                            }else if($final_hora_intervalo[0] == $hora_zero){
                                                if($final_hora_intervalo[1] <= '20'){
                                                    $desconto_intervalo = converteHorasDecimal('00:'.(20-$final_hora_intervalo[1])*3);
                                                    $valor_final = converteHorasDecimal('00:'.$final_hora_funcionario[1])*3;
                                                    $cont_atendentes_escala += $valor_final - (1 -$desconto_intervalo);
                                                }else{
                                                    $cont_atendentes_escala += 0;
                                                }
                                            }else{
                                                $valor_final = converteHorasDecimal('00:'.$final_hora_funcionario[1])*3;
                                                $cont_atendentes_escala += $valor_final;
                                            }
                                        }else{
                                            if($inicial_hora_intervalo[0] == $hora_zero && $final_hora_intervalo[0] == $hora_zero){
                                                if($inicial_hora_intervalo[1] <= '20' && $final_hora_intervalo[1] <= '20'){
                                                    $desconto_intervalo = converteHorasDecimal('00:'.(20-($final_hora_intervalo[1] - $inicial_hora_intervalo[1]))*3);
                                                    $cont_atendentes_escala += $desconto_intervalo;
                                                }else if($inicial_hora_intervalo[1] <= '20'){
                                                    $desconto_intervalo = converteHorasDecimal('00:'.(20-$inicial_hora_intervalo[1])*3);
                                                    $cont_atendentes_escala += 1 - $desconto_intervalo;
                                                }else if($final_hora_intervalo[1] <= '20'){
                                                    $desconto_intervalo = converteHorasDecimal('00:'.(20-$final_hora_intervalo[1])*3);
                                                    $cont_atendentes_escala += $desconto_intervalo;
                                                }else{
                                                    $cont_atendentes_escala += 1;
                                                }  
                                            }else if($inicial_hora_intervalo[0] == $hora_zero){
                                                if($inicial_hora_intervalo[1] <= '20'){
                                                    $desconto_intervalo = converteHorasDecimal('00:'.(20-$inicial_hora_intervalo[1])*3);
                                                    $cont_atendentes_escala += 1 - $desconto_intervalo;
                                                }else{
                                                    $cont_atendentes_escala += 1;
                                                }
                                            }else if($final_hora_intervalo[0] == $hora_zero){
                                                if($final_hora_intervalo[1] <= '20'){
                                                    $desconto_intervalo = converteHorasDecimal('00:'.(20-$final_hora_intervalo[1])*3);
                                                    $cont_atendentes_escala += $desconto_intervalo;
                                                }else{
                                                    $cont_atendentes_escala += 1;
                                                }
                                            }else if($inicial_hora_intervalo[0] < $hora_zero && $final_hora_intervalo[0] > $hora_zero){
                                                $cont_atendentes_escala += 0;
                                            }else{
                                                $cont_atendentes_escala += 1;
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }                                       
                }

                if($dados_normais_especiais){
                    foreach ($dados_normais_especiais as $conteudo_normais_especiais) {
                        $inicial_hora_funcionario = explode(":", $conteudo_normais_especiais['inicial_especial']);
                        $final_hora_funcionario = explode(":", $conteudo_normais_especiais['final_especial']);
                        
                        if(($inicial_hora_funcionario[0] <= $hora_zero) && ($final_hora_funcionario[0] >= $hora_zero)){
                            
                            if(($turno == 1 && $conteudo_normais_especiais['carga_horaria'] == 'integral') || ($turno == 2 && $conteudo_normais_especiais['carga_horaria'] == 'meio') || ($turno == 3 && $conteudo_normais_especiais['carga_horaria'] == 'jovem') || ($turno == 4 && $conteudo_normais_especiais['carga_horaria'] == 'estagio') || (!$turno) ){
                                if(!$conteudo_normais_especiais['tempo_intervalo']){
                                    if($inicial_hora_funcionario[0] == $hora_zero && $inicial_hora_funcionario[1] != '00' && $inicial_hora_funcionario[1] <= '20'){
                                        $valor_final = converteHorasDecimal('00:'.$inicial_hora_funcionario[1])*3;
                                        $cont_atendentes_escala += 1-($valor_final);
                                    }else if($final_hora_funcionario[0] == $hora_zero && $final_hora_funcionario[1] != '00' && $final_hora_funcionario[1] <= '20'){
                                        $valor_final = converteHorasDecimal('00:'.$final_hora_funcionario[1])*3;
                                        $cont_atendentes_escala += $valor_final;
                                    }else{
                                        $cont_atendentes_escala += 1;
                                    }
                                }else{
                                    $data_inicio_compara = date('Y-m-d H:i:s', strtotime("+0 minutes",strtotime($conteudo_normais_especiais['inicial_especial'])));
                                    $data_fim_compara = date('Y-m-d H:i:s', strtotime("+0 minutes",strtotime($conteudo_normais_especiais['final_especial'])));

                                    if($data_inicio_compara > $data_fim_compara){
                                        $data_inicio_compara = date('Y-m-d H:i:s', strtotime("-1 days",strtotime($conteudo_normais_especiais['inicial_especial'])));
                                    }

                                    $diferenca_datas = strtotime($data_fim_compara) - strtotime($data_inicio_compara);
                                    
                                    $tempo_total_trabalho = converteSegundosHoras($diferenca_datas);
                                    $tempo_total_trabalho = converteHorasDecimal($tempo_total_trabalho);
                                    $tempo_total_intervalo = converteHorasDecimal($conteudo_normais_especiais['tempo_intervalo']);
                                    $tempo_desconto = $tempo_total_intervalo/$tempo_total_trabalho;

                                    if($inicial_hora_funcionario[0] == $hora_zero && $inicial_hora_funcionario[1] != '00' && $inicial_hora_funcionario[1] <= '20'){
                                        $valor_final = converteHorasDecimal('00:'.$inicial_hora_funcionario[1])*3;
                                        $cont_atendentes_escala += (1 - ($valor_final)) - ($tempo_desconto * (1 -  ($valor_final)));
                                    
                                    }else if($final_hora_funcionario[0] == $hora_zero && $final_hora_funcionario[1] != '00' && $final_hora_funcionario[1] <= '20'){
                                        $valor_final = converteHorasDecimal('00:'.$final_hora_funcionario[1])*3;
                                        $cont_atendentes_escala += (($valor_final) - ($tempo_desconto * $valor_final));
                                    
                                    }else{
                                        $cont_atendentes_escala += 1 - $tempo_desconto;
                                    }
                                }
                            }
                        }
                    }                                       
                }

                //INVERTIDOS CONSULTA
                    $dados_invertidos = DBRead('', 'tb_horarios_escala', "WHERE data_inicial = '".$consulta_data."' ".$consulta_invertidos." AND atendente = 1 AND id_usuario NOT IN (SELECT id_usuario FROM tb_ferias WHERE id_usuario = id_usuario AND data_de <= '".$data."' AND data_ate >= '".$data."') AND id_usuario NOT IN (SELECT a.id_usuario FROM tb_horarios_escala a INNER JOIN tb_horarios_especiais b ON a.id_horarios_escala = b.id_horarios_escala WHERE b.dia = '".$data."')");

                    $dados_invertidos_especiais = DBRead('', 'tb_horarios_escala a', "INNER JOIN tb_horarios_especiais b ON a.id_horarios_escala = b.id_horarios_escala WHERE b.dia = '".$data."' AND b.inicial_especial < '".$hora_zero.":20' AND b.inicial_especial > b.final_especial ");

                //INVERTIDOS
                if($dados_invertidos){
                    
                    foreach ($dados_invertidos as $conteudo_invertido) {
                        $inicial_hora_funcionario = explode(":", $conteudo_invertido[$consulta_inicial_banco]);

                        if($inicial_hora_funcionario[0] <= $hora_zero){
                                                    
                            if(($turno == 1 && $conteudo_invertido['carga_horaria'] == 'integral') || ($turno == 2 && $conteudo_invertido['carga_horaria'] == 'meio') || ($turno == 3 && $conteudo_invertido['carga_horaria'] == 'jovem') || ($turno == 4 && $conteudo_invertido['carga_horaria'] == 'estagio') || (!$turno) ){

                                $dados_intervalo_invertidos = DBRead('', 'tb_horarios_escala_intervalo', "WHERE id_horarios_escala = '".$conteudo_invertido['id_horarios_escala']."' AND dia = '".$dia_intervalo."' ");
                                if(!$dados_intervalo_invertidos){
                                
                                    if($inicial_hora_funcionario[0] == $hora_zero && $inicial_hora_funcionario[1] != '00' && $inicial_hora_funcionario[1] <= '20'){
                                        $valor_final = converteHorasDecimal('00:'.$inicial_hora_funcionario[1])*3;
                                        $cont_atendentes_escala += 1-($valor_final);
                                    }else{
                                        $cont_atendentes_escala += 1;
                                    }   
                                }else{
                                    if($dados_intervalo_invertidos[0]['tipo_intervalo'] == 1){
                                        $data_inicio_compara = date('Y-m-d H:i:s', strtotime("+0 minutes",strtotime($conteudo_invertido[$consulta_inicial_banco])));
                                        $data_fim_compara = date('Y-m-d H:i:s', strtotime("+0 minutes",strtotime($conteudo_invertido[$consulta_final_banco])));

                                        if($data_inicio_compara > $data_fim_compara){
                                            $data_inicio_compara = date('Y-m-d H:i:s', strtotime("-1 days",strtotime($conteudo_invertido[$consulta_inicial_banco])));
                                        }
                                        
                                        $diferenca_datas = strtotime($data_fim_compara) - strtotime($data_inicio_compara);
                                        $tempo_total_trabalho = converteSegundosHoras($diferenca_datas);
                                        $tempo_total_trabalho = converteHorasDecimal($tempo_total_trabalho);
                                        $tempo_total_intervalo = converteHorasDecimal($dados_intervalo_invertidos[0]['tempo_intervalo']);
                                        $tempo_desconto = $tempo_total_intervalo/$tempo_total_trabalho;

                                        if($inicial_hora_funcionario[0] == $hora_zero && $inicial_hora_funcionario[1] != '00' && $inicial_hora_funcionario[1] <= '20'){
                                            $valor_final = converteHorasDecimal('00:'.$inicial_hora_funcionario[1])*3;
                                            $cont_atendentes_escala += (1 - ($valor_final)) - ($tempo_desconto * (1 -  ($valor_final)));
                                
                                        }else{
                                            $cont_atendentes_escala += 1 - $tempo_desconto;
                                        } 
                                    }else{

                                        $inicial_hora_intervalo = explode(":", $dados_intervalo_invertidos[0]['intervalo_inicial']);
                                        $final_hora_intervalo = explode(":", $dados_intervalo_invertidos[0]['intervalo_final']);

                                        if($inicial_hora_funcionario[0] == $hora_zero && $inicial_hora_funcionario[1] != '00' && $inicial_hora_funcionario[1] <= '20'){
                                            if($inicial_hora_intervalo[0] == $hora_zero && $final_hora_intervalo[0] == $hora_zero){
                                                if($inicial_hora_intervalo[1] <= '20' && $final_hora_intervalo[1] <= '20'){
                                                    $valor_final = converteHorasDecimal('00:'.$inicial_hora_funcionario[1])*3;
                                                    $desconto_intervalo = converteHorasDecimal('00:'.(20-($final_hora_intervalo[1] - $inicial_hora_intervalo[1]))*3);
                                                    $cont_atendentes_escala += $desconto_intervalo - $valor_final;
                                                }else if($inicial_hora_intervalo[1] <= '20'){
                                                    $desconto_intervalo = converteHorasDecimal('00:'.(20-$inicial_hora_intervalo[1])*3);
                                                    $valor_final = converteHorasDecimal('00:'.$inicial_hora_funcionario[1])*3;
                                                    $cont_atendentes_escala += (1 - ($valor_final)) - ($desconto_intervalo);
                                                }else{
                                                    $cont_atendentes_escala += 1;
                                                }  
                                                
                                            }else if($inicial_hora_intervalo[0] == $hora_zero){
                                                if($inicial_hora_intervalo[1] <= '20'){
                                                    $desconto_intervalo = converteHorasDecimal('00:'.(20-$inicial_hora_intervalo[1])*3);
                                                    $valor_final = converteHorasDecimal('00:'.$inicial_hora_funcionario[1])*3;
                                                    $cont_atendentes_escala += (1 - ($valor_final)) - ($desconto_intervalo);
                                                }else{
                                                    $cont_atendentes_escala += 1;
                                                }
                                               
                                            }else{
                                                $valor_final = converteHorasDecimal('00:'.$inicial_hora_funcionario[1])*3;
                                                $cont_atendentes_escala += 1 - ($valor_final);
                                            }
                                        }else{
                                            if($inicial_hora_intervalo[0] == $hora_zero && $final_hora_intervalo[0] == $hora_zero){
                                                if($inicial_hora_intervalo[1] <= '20' && $final_hora_intervalo[1] <= '20'){
                                                    $desconto_intervalo = converteHorasDecimal('00:'.(20-($final_hora_intervalo[1] - $inicial_hora_intervalo[1]))*3);
                                                    $cont_atendentes_escala += $desconto_intervalo;
                                                }else if($inicial_hora_intervalo[1] <= '20'){
                                                    $desconto_intervalo = converteHorasDecimal('00:'.(20-$inicial_hora_intervalo[1])*3);
                                                    $cont_atendentes_escala += 1 - $desconto_intervalo;
                                                }else if($final_hora_intervalo[1] <= '20'){
                                                    $desconto_intervalo = converteHorasDecimal('00:'.(20-$final_hora_intervalo[1])*3);
                                                    $cont_atendentes_escala += $desconto_intervalo;
                                                }else{
                                                    $cont_atendentes_escala += 1;
                                                }  
                                            }else if($inicial_hora_intervalo[0] == $hora_zero){
                                                if($inicial_hora_intervalo[1] <= '20'){
                                                    $desconto_intervalo = converteHorasDecimal('00:'.(20-$inicial_hora_intervalo[1])*3);
                                                    $cont_atendentes_escala += 1 - $desconto_intervalo;
                                                }else{
                                                    $cont_atendentes_escala += 1;
                                                }
                                            }else if($final_hora_intervalo[0] == $hora_zero){
                                                if($final_hora_intervalo[1] <= '20'){
                                                    $desconto_intervalo = converteHorasDecimal('00:'.(20-$final_hora_intervalo[1])*3);
                                                    $cont_atendentes_escala += $desconto_intervalo;
                                                }else{
                                                    $cont_atendentes_escala += 1;
                                                }
                                            }else if($inicial_hora_intervalo[0] < $hora_zero && $final_hora_intervalo[0] > $hora_zero){
                                                $cont_atendentes_escala += 0;
                                            }else{
                                                $cont_atendentes_escala += 1;
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }

                if($dados_invertidos_especiais){
                    
                    foreach ($dados_invertidos_especiais as $conteudo_invertidos_especiais) {
                        $inicial_hora_funcionario = explode(":", $conteudo_invertidos_especiais['inicial_especial']);

                        if($inicial_hora_funcionario[0] <= $hora_zero){        

                            if(($turno == 1 && $conteudo_invertidos_especiais['carga_horaria'] == 'integral') || ($turno == 2 && $conteudo_invertidos_especiais['carga_horaria'] == 'meio') || ($turno == 3 && $conteudo_invertidos_especiais['carga_horaria'] == 'jovem') || ($turno == 4 && $conteudo_invertidos_especiais['carga_horaria'] == 'estagio') || (!$turno) ){
                                
                                if(!$conteudo_invertidos_especiais['tempo_intervalo']){
                                    if($inicial_hora_funcionario[0] == $hora_zero && $inicial_hora_funcionario[1] != '00' && $inicial_hora_funcionario[1] <= '20'){
                                        $valor_final = converteHorasDecimal('00:'.$inicial_hora_funcionario[1])*3;
                                        $cont_atendentes_escala += 1-($valor_final);
                                    }else{
                                        $cont_atendentes_escala += 1;
                                    }
                                }else{
                                    $data_inicio_compara = date('Y-m-d H:i:s', strtotime("+0 minutes",strtotime($conteudo_invertidos_especiais['inicial_especial'])));
                                    $data_fim_compara = date('Y-m-d H:i:s', strtotime("+0 minutes",strtotime($conteudo_invertidos_especiais['final_especial'])));

                                    if($data_inicio_compara > $data_fim_compara){
                                        $data_inicio_compara = date('Y-m-d H:i:s', strtotime("-1 days",strtotime($conteudo_invertidos_especiais['inicial_especial'])));
                                    }

                                    $diferenca_datas = strtotime($data_fim_compara) - strtotime($data_inicio_compara);
                                    $tempo_total_trabalho = converteSegundosHoras($diferenca_datas);
                                    $tempo_total_trabalho = converteHorasDecimal($tempo_total_trabalho);
                                    $tempo_total_intervalo = converteHorasDecimal($conteudo_invertidos_especiais['tempo_intervalo']);
                                    $tempo_desconto = $tempo_total_intervalo/$tempo_total_trabalho;

                                    if($inicial_hora_funcionario[0] == $hora_zero && $inicial_hora_funcionario[1] != '00' && $inicial_hora_funcionario[1] <= '20'){
                                        $valor_final = converteHorasDecimal('00:'.$inicial_hora_funcionario[1])*3;
                                        $cont_atendentes_escala += (1 - ($valor_final)) - ($tempo_desconto * (1 -  ($valor_final)));
                                    
                                    }else{
                                        $cont_atendentes_escala += 1 - $tempo_desconto;
                                    }
                                }
                            }
                        }
                    }
                }
                    
                if($data == $consulta_data){
                    $consulta_data = $consulta_data_ontem;
                }

                //INVERTIDOS ONTEM CONSULTA
                    $dados_invertidos_ontem = DBRead('', 'tb_horarios_escala', "WHERE data_inicial = '".$consulta_data."' ".$consulta_ontem." AND atendente = 1 AND id_usuario NOT IN (SELECT id_usuario FROM tb_ferias WHERE id_usuario = id_usuario AND data_de <= '".$data_ontem."' AND data_ate >= '".$data_ontem."') AND id_usuario NOT IN (SELECT a.id_usuario FROM tb_horarios_escala a INNER JOIN tb_horarios_especiais b ON a.id_horarios_escala = b.id_horarios_escala WHERE b.dia = '".$data_ontem."')");

                    $dados_invertidos_ontem_especiais =  DBRead('', 'tb_horarios_escala a', "INNER JOIN tb_horarios_especiais b ON a.id_horarios_escala = b.id_horarios_escala WHERE b.dia = '".$data_ontem."' AND b.final_especial >= '".$hora_zero.":00' AND b.inicial_especial > b.final_especial ");
               
               //INVERTIDOS ONTEM
                if($dados_invertidos_ontem){

                    foreach ($dados_invertidos_ontem as $conteudo_ontem) {
                        $final_hora_funcionario = explode(":", $conteudo_ontem[$consulta_final_banco_ontem]);

                        if($final_hora_funcionario[0] >= $hora_zero){

                            if(($turno == 1 && $conteudo_ontem['carga_horaria'] == 'integral') || ($turno == 2 && $conteudo_ontem['carga_horaria'] == 'meio') || ($turno == 3 && $conteudo_ontem['carga_horaria'] == 'jovem') || ($turno == 4 && $conteudo_ontem['carga_horaria'] == 'estagio') || (!$turno) ){

                                $dados_intervalo_invertidos_ontem = DBRead('', 'tb_horarios_escala_intervalo', "WHERE id_horarios_escala = '".$conteudo_ontem['id_horarios_escala']."' AND dia = '".$dia_intervalo_ontem."' ");

                                if(!$dados_intervalo_invertidos_ontem){
                                    if($final_hora_funcionario[0] == $hora_zero && $final_hora_funcionario[1] != '00' && $final_hora_funcionario[1] <= '20'){
                                        $valor_final = converteHorasDecimal('00:'.$final_hora_funcionario[1])*3;
                                        $cont_atendentes_escala += $valor_final;
                                    }else{
                                        $cont_atendentes_escala += 1;
                                    }
                                }else{
                                    if($dados_intervalo_invertidos_ontem[0]['tipo_intervalo'] == 1){
                                        $data_inicio_compara = date('Y-m-d H:i:s', strtotime("+0 minutes",strtotime($conteudo_ontem[$consulta_inicial_banco_ontem])));
                                        $data_fim_compara = date('Y-m-d H:i:s', strtotime("+0 minutes",strtotime($conteudo_ontem[$consulta_final_banco_ontem])));

                                        if($data_inicio_compara > $data_fim_compara){
                                            $data_inicio_compara = date('Y-m-d H:i:s', strtotime("-1 days",strtotime($conteudo_ontem[$consulta_inicial_banco_ontem])));
                                        }
                                        
                                        $diferenca_datas = strtotime($data_fim_compara) - strtotime($data_inicio_compara);
                                        $tempo_total_trabalho = converteSegundosHoras($diferenca_datas);
                                        $tempo_total_trabalho = converteHorasDecimal($tempo_total_trabalho);
                                        $tempo_total_intervalo = converteHorasDecimal($dados_intervalo_invertidos_ontem[0]['tempo_intervalo']);
                                        $tempo_desconto = $tempo_total_intervalo/$tempo_total_trabalho;

                                        if($final_hora_funcionario[0] == $hora_zero && $final_hora_funcionario[1] != '00' && $final_hora_funcionario[1] <= '20'){
                                            $valor_final = converteHorasDecimal('00:'.$final_hora_funcionario[1])*3;
                                            $cont_atendentes_escala += (($valor_final) - ($tempo_desconto * $valor_final));
                                        }else{
                                            $cont_atendentes_escala += 1 - $tempo_desconto;
                                        }     
                                    }else{

                                        $inicial_hora_intervalo = explode(":", $dados_intervalo_invertidos_ontem[0]['intervalo_inicial']);
                                        $final_hora_intervalo = explode(":", $dados_intervalo_invertidos_ontem[0]['intervalo_final']);

                                        if($final_hora_funcionario[0] == $hora_zero && $final_hora_funcionario[1] != '00' && $final_hora_funcionario[1] <= '20'){
                                            //Ta na mao
                                            if($inicial_hora_intervalo[0] == $hora_zero && $final_hora_intervalo[0] == $hora_zero){
                                                if($inicial_hora_intervalo[1] <= '20' && $final_hora_intervalo[1] <= '20'){
                                                    $valor_final = converteHorasDecimal('00:'.$inicial_hora_funcionario[1])*3;
                                                    $desconto_intervalo = converteHorasDecimal('00:'.(20-($final_hora_intervalo[1] - $inicial_hora_intervalo[1]))*3);
                                                    $cont_atendentes_escala += $desconto_intervalo - $valor_final;
                                                }else if($inicial_hora_intervalo[1] <= '20'){
                                                    $desconto_intervalo = converteHorasDecimal('00:'.(20-$inicial_hora_intervalo[1])*3);
                                                    $valor_final = converteHorasDecimal('00:'.$inicial_hora_funcionario[1])*3;
                                                    $cont_atendentes_escala += (1 - ($valor_final)) - ($desconto_intervalo);
                                                }else{
                                                    $cont_atendentes_escala += 1;
                                                }
                                            }else if($final_hora_intervalo[0] == $hora_zero){
                                                if($final_hora_intervalo[1] <= '20'){
                                                    $desconto_intervalo = converteHorasDecimal('00:'.(20-$final_hora_intervalo[1])*3);
                                                    $valor_final = converteHorasDecimal('00:'.$final_hora_funcionario[1])*3;
                                                    $cont_atendentes_escala += $valor_final - (1 -$desconto_intervalo);
                                                }else{
                                                    $cont_atendentes_escala += 0;
                                                }
                                            }else{
                                                $valor_final = converteHorasDecimal('00:'.$final_hora_funcionario[1])*3;
                                                $cont_atendentes_escala += $valor_final;
                                            }
                                        }else{
                                            //Tá na mão
                                            if($inicial_hora_intervalo[0] == $hora_zero && $final_hora_intervalo[0] == $hora_zero){
                                                if($inicial_hora_intervalo[1] <= '20' && $final_hora_intervalo[1] <= '20'){
                                                    $desconto_intervalo = converteHorasDecimal('00:'.(20-($final_hora_intervalo[1] - $inicial_hora_intervalo[1]))*3);
                                                    $cont_atendentes_escala += $desconto_intervalo;
                                                }else if($inicial_hora_intervalo[1] <= '20'){
                                                    $desconto_intervalo = converteHorasDecimal('00:'.(20-$inicial_hora_intervalo[1])*3);
                                                    $cont_atendentes_escala += 1 - $desconto_intervalo;
                                                }else if($final_hora_intervalo[1] <= '20'){
                                                    $desconto_intervalo = converteHorasDecimal('00:'.(20-$final_hora_intervalo[1])*3);
                                                    $cont_atendentes_escala += $desconto_intervalo;
                                                }else{
                                                    $cont_atendentes_escala += 1;
                                                }  
                                            }else if($inicial_hora_intervalo[0] == $hora_zero){
                                                if($inicial_hora_intervalo[1] <= '20'){
                                                    $desconto_intervalo = converteHorasDecimal('00:'.(20-$inicial_hora_intervalo[1])*3);
                                                    $cont_atendentes_escala += 1 - $desconto_intervalo;
                                                }else{
                                                    $cont_atendentes_escala += 1;
                                                }
                                            }else if($final_hora_intervalo[0] == $hora_zero){
                                                if($final_hora_intervalo[1] <= '20'){
                                                    $desconto_intervalo = converteHorasDecimal('00:'.(20-$final_hora_intervalo[1])*3);
                                                    $cont_atendentes_escala += $desconto_intervalo;
                                                }else{
                                                    $cont_atendentes_escala += 1;
                                                }
                                            }else if($inicial_hora_intervalo[0] < $hora_zero && $final_hora_intervalo[0] > $hora_zero){
                                                $cont_atendentes_escala += 0;
                                            }else{
                                                $cont_atendentes_escala += 1;
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }

                if($dados_invertidos_ontem_especiais){
                    foreach ($dados_invertidos_ontem_especiais as $conteudo_invertidos_ontem_especiais) {
                        $final_hora_funcionario = explode(":", $conteudo_invertidos_ontem_especiais['final_especial']);
                        if($final_hora_funcionario[0] >= $hora_zero){

                            if(($turno == 1 && $conteudo_invertidos_ontem_especiais['carga_horaria'] == 'integral') || ($turno == 2 && $conteudo_invertidos_ontem_especiais['carga_horaria'] == 'meio') || ($turno == 3 && $conteudo_invertidos_ontem_especiais['carga_horaria'] == 'jovem') || ($turno == 4 && $conteudo_invertidos_ontem_especiais['carga_horaria'] == 'estagio') || (!$turno) ){

                                if(!$conteudo_invertidos_ontem_especiais['tempo_intervalo']){
                                    if($final_hora_funcionario[0] == $hora_zero && $final_hora_funcionario[1] != '00'&& $final_hora_funcionario[1] <= '20'){
                                        $valor_final = converteHorasDecimal('00:'.$final_hora_funcionario[1])*3;
                                        $cont_atendentes_escala += $valor_final;
                                    }else{
                                        $cont_atendentes_escala += 1;
                                    }
                                }else{
                                    $data_inicio_compara = date('Y-m-d H:i:s', strtotime("+0 minutes",strtotime($conteudo_invertidos_ontem_especiais['inicial_especial'])));
                                    $data_fim_compara = date('Y-m-d H:i:s', strtotime("+0 minutes",strtotime($conteudo_invertidos_ontem_especiais['final_especial'])));

                                    if($data_inicio_compara > $data_fim_compara){
                                        $data_inicio_compara = date('Y-m-d H:i:s', strtotime("-1 days",strtotime($conteudo_invertidos_ontem_especiais['inicial_especial'])));
                                    }

                                    $diferenca_datas = strtotime($data_fim_compara) - strtotime($data_inicio_compara);
                                    
                                    $tempo_total_trabalho = converteSegundosHoras($diferenca_datas);
                                    $tempo_total_trabalho = converteHorasDecimal($tempo_total_trabalho);
                                    $tempo_total_intervalo = converteHorasDecimal($conteudo_invertidos_ontem_especiais['tempo_intervalo']);
                                    $tempo_desconto = $tempo_total_intervalo/$tempo_total_trabalho;

                                    if($final_hora_funcionario[0] == $hora_zero && $final_hora_funcionario[1] != '00' && $final_hora_funcionario[1] <= '20'){
                                        $valor_final = converteHorasDecimal('00:'.$final_hora_funcionario[1])*3;
                                        $cont_atendentes_escala += (($valor_final) - ($tempo_desconto * $valor_final));
                                    
                                    }else{
                                        $cont_atendentes_escala += 1 - $tempo_desconto;
                                    }
                                }
                            }
                        }
                    }
                }

                if(!$cont_atendentes_escala){
                    $cont_atendentes_escala = 0;
                }  
                
            //fim codigo atendentes escala 00

            $atendentes_escala[$hora_array] = sprintf("%01.2f", round($cont_atendentes_escala, 2));

            $cont_atendentes_escala = 0;

            //início codigo atendentes escala 20
                $consulta_data = explode("-", $data);
                $consulta_data = $consulta_data[0].'-'.$consulta_data[1].'-01';
                //VERIFICA O DIA DA CONSULTA PARA PESQUISAR (SEG A SEX, SÁBADO OU DOMINGO)
                if($dia_hoje == 'Domingo'){
                    $consulta_dia =  "AND inicial_dom < '".$hora_zero.":40' AND final_dom > '".$hora_zero.":20'";
                    
                    $consulta_inicial_banco = 'inicial_dom';
                    $consulta_final_banco = 'final_dom';

                    $consulta_inicial_banco_ontem = 'inicial_sab';
                    $consulta_final_banco_ontem = 'final_sab';

                    $consulta_ontem = "AND final_sab > '".$hora_zero.":20' AND inicial_sab > final_sab AND folga_seg != '".$dia_ontem."'";
                    $consulta_invertidos = "AND inicial_dom < '".$hora_zero.":40' AND inicial_dom > final_dom AND id_horarios_escala NOT IN (SELECT id_horarios_escala FROM tb_folgas_dom WHERE dia = '".$data."')";
                    $consulta_normais = "AND inicial_dom < final_dom AND id_horarios_escala NOT IN (SELECT id_horarios_escala FROM tb_folgas_dom WHERE dia = '".$data."')";
                    
                    $dia_intervalo = 'dom';
                    $dia_intervalo_ontem = 'sab';

                }else if($dia_hoje == 'Sábado'){
                    $consulta_dia =  "AND inicial_sab < '".$hora_zero.":40' AND final_sab > '".$hora_zero.":20'";
                    $consulta_inicial_banco = 'inicial_sab';
                    $consulta_final_banco = 'final_sab';
                    
                    $consulta_inicial_banco_ontem = 'inicial_seg';
                    $consulta_final_banco_ontem = 'final_seg';

                    $consulta_ontem = "AND final_seg > '".$hora_zero.":20' AND inicial_seg > final_seg AND folga_seg != '".$dia_ontem."'";
                    $consulta_invertidos = "AND inicial_sab < '".$hora_zero.":40' AND inicial_sab > final_sab AND folga_seg != '".$dia_hoje."'";
                    $consulta_normais = "AND inicial_sab < final_sab AND folga_seg != '".$dia_hoje."'";

                    $dia_intervalo = 'sab';
                    $dia_intervalo_ontem = 'seg';

                }else{
                    $consulta_dia =  "AND inicial_seg < '".$hora_zero.":40' AND final_seg > '".$hora_zero.":20'";
                    $consulta_inicial_banco = 'inicial_seg';
                    $consulta_final_banco = 'final_seg';

                    if($dia_hoje == 'Quinta' || $dia_hoje == 'Sexta'){
                        $consulta_invertidos = "AND inicial_seg < '".$hora_zero.":40' AND inicial_seg > final_seg AND (folga_seg != '".$dia_hoje."' AND folga_seg != 'Quinta e Sexta')";
                        $consulta_normais = "AND inicial_seg < final_seg AND (folga_seg != '".$dia_hoje."' AND folga_seg != 'Quinta e Sexta')";
                    }else{
                        $consulta_invertidos = "AND inicial_seg < '".$hora_zero.":40' AND inicial_seg > final_seg AND folga_seg != '".$dia_hoje."'";
                        $consulta_normais = "AND inicial_seg < final_seg AND folga_seg != '".$dia_hoje."'";
                    }

                    if($dia_hoje == 'Segunda'){
                        $consulta_ontem = "AND final_dom > '".$hora_zero.":20' AND inicial_dom > final_dom AND id_horarios_escala NOT IN (SELECT id_horarios_escala FROM tb_folgas_dom WHERE dia = '".$data_ontem."')";
                        
                        $consulta_inicial_banco_ontem = 'inicial_dom';
                        $consulta_final_banco_ontem = 'final_dom';
                        
                        $dia_intervalo_ontem = 'dom';
                    
                    }else{
                        if($dia_hoje == 'Sexta'){
                            $consulta_ontem = "AND final_seg > '".$hora_zero.":20' AND inicial_seg > final_seg AND (folga_seg != '".$dia_ontem."' AND folga_seg != 'Quinta e Sexta')";
                        }else{
                            $consulta_ontem = "AND final_seg > '".$hora_zero.":20' AND inicial_seg > final_seg AND folga_seg != '".$dia_ontem."'";
                        }
                    
                        $consulta_inicial_banco_ontem = 'inicial_seg';
                        $consulta_final_banco_ontem = 'final_seg';
                    
                        $dia_intervalo_ontem = 'seg';
                    }

                    $dia_intervalo = 'seg';

                }

                //NORMAIS CONSULTA   

                    $dados_normais = DBRead('', 'tb_horarios_escala', "WHERE data_inicial = '".$consulta_data."' ".$consulta_dia." ".$consulta_normais." AND atendente = 1 AND id_usuario NOT IN (SELECT id_usuario FROM tb_ferias WHERE id_usuario = id_usuario AND data_de <= '".$data."' AND data_ate >= '".$data."') AND id_usuario NOT IN (SELECT a.id_usuario FROM tb_horarios_escala a INNER JOIN tb_horarios_especiais b ON a.id_horarios_escala = b.id_horarios_escala WHERE b.dia = '".$data."')");

                    $dados_normais_especiais = DBRead('', 'tb_horarios_escala a', "INNER JOIN tb_horarios_especiais b ON a.id_horarios_escala = b.id_horarios_escala WHERE b.dia = '".$data."' AND b.inicial_especial < '".$hora_zero.":40' AND b.final_especial > '".$hora_zero.":20' ");              
                    
               //NORMAIS 
                if($dados_normais){
                    foreach ($dados_normais as $conteudo_normais) {
                        $inicial_hora_funcionario = explode(":", $conteudo_normais[$consulta_inicial_banco]);
                        $final_hora_funcionario = explode(":", $conteudo_normais[$consulta_final_banco]);
                        
                        if(($inicial_hora_funcionario[0] <= $hora_zero) && ($final_hora_funcionario[0] >= $hora_zero)){
                          
                            if(($turno == 1 && $conteudo_normais['carga_horaria'] == 'integral') || ($turno == 2 && $conteudo_normais['carga_horaria'] == 'meio') || ($turno == 3 && $conteudo_normais['carga_horaria'] == 'jovem') || ($turno == 4 && $conteudo_normais['carga_horaria'] == 'estagio') || (!$turno) ){

                                $dados_intervalo_normais = DBRead('', 'tb_horarios_escala_intervalo', "WHERE id_horarios_escala = '".$conteudo_normais['id_horarios_escala']."' AND dia = '".$dia_intervalo."' ");
                                if(!$dados_intervalo_normais){
                                    if($inicial_hora_funcionario[0] == $hora_zero && $inicial_hora_funcionario[1] != '00' && $inicial_hora_funcionario[1] >= '20' && $inicial_hora_funcionario[1] < '40'){
                                        $valor_final = converteHorasDecimal('00:'.(($inicial_hora_funcionario[1]-20)*3));
                                        $cont_atendentes_escala += 1-($valor_final);
                                    }else if($final_hora_funcionario[0] == $hora_zero && $final_hora_funcionario[1] != '00' && $final_hora_funcionario[1] >= '20' && $final_hora_funcionario[1] < '40'){
                                        $valor_final = converteHorasDecimal('00:'.(($final_hora_funcionario[1]-20)*3));
                                        $cont_atendentes_escala += $valor_final;
                                    }else{
                                        $cont_atendentes_escala += 1;
                                    }
                                }else{
                                    if($dados_intervalo_normais[0]['tipo_intervalo'] == 1){

                                        $data_inicio_compara = date('Y-m-d H:i:s', strtotime("+0 minutes",strtotime($conteudo_normais[$consulta_inicial_banco])));
                                        $data_fim_compara = date('Y-m-d H:i:s', strtotime("+0 minutes",strtotime($conteudo_normais[$consulta_final_banco])));

                                        if($data_inicio_compara > $data_fim_compara){
                                            $data_inicio_compara = date('Y-m-d H:i:s', strtotime("-1 days",strtotime($conteudo_normais[$consulta_inicial_banco])));
                                        }
                                        
                                        $diferenca_datas = strtotime($data_fim_compara) - strtotime($data_inicio_compara);
                                        
                                        $tempo_total_trabalho = converteSegundosHoras($diferenca_datas);
                                        $tempo_total_trabalho = converteHorasDecimal($tempo_total_trabalho);

                                        $tempo_total_intervalo = converteHorasDecimal($dados_intervalo_normais[0]['tempo_intervalo']);

                                        $tempo_desconto = $tempo_total_intervalo/$tempo_total_trabalho;

                                        if($inicial_hora_funcionario[0] == $hora_zero && $inicial_hora_funcionario[1] >= '20' && $inicial_hora_funcionario[1] < '40'){
                                            $valor_final = converteHorasDecimal('00:'.(($inicial_hora_funcionario[1]-20)*3));
                                            $cont_atendentes_escala += (1 - ($valor_final)) - ($tempo_desconto * (1 -  ($valor_final)));

                                        }else if($final_hora_funcionario[0] == $hora_zero && $final_hora_funcionario[1] >= '20' && $final_hora_funcionario[1] < '40'){
                                            $valor_final = converteHorasDecimal('00:'.(($final_hora_funcionario[1]-20)*3));
                                            $cont_atendentes_escala += (($valor_final) - ($tempo_desconto * $valor_final));
                                        }else{
                                            $cont_atendentes_escala += 1 - $tempo_desconto;
                                        }    
                                    }else{

                                        $inicial_hora_intervalo = explode(":", $dados_intervalo_normais[0]['intervalo_inicial']);
                                        $final_hora_intervalo = explode(":", $dados_intervalo_normais[0]['intervalo_final']);
                                    
                                        if($inicial_hora_funcionario[0] == $hora_zero && $inicial_hora_funcionario[1] >= '20' && $inicial_hora_funcionario[1] < '40'){
                                            if($inicial_hora_intervalo[0] == $hora_zero && $final_hora_intervalo[0] == $hora_zero){
                                                if($inicial_hora_intervalo[1] >= '20' && $inicial_hora_intervalo[1] < '40' && $final_hora_intervalo[1] >= '20' && $final_hora_intervalo[1] < '40'){
                                                    $valor_final = converteHorasDecimal('00:'.(($inicial_hora_funcionario[1]-20)*3));
                                                    $desconto_intervalo = converteHorasDecimal('00:'.(20-($final_hora_intervalo[1] - $inicial_hora_intervalo[1]))*3);
                                                    $cont_atendentes_escala += $desconto_intervalo - $valor_final;
                                                }else if($inicial_hora_intervalo[1] >= '20' && $inicial_hora_intervalo[1] < '40'){
                                                    $desconto_intervalo = converteHorasDecimal('00:'.(20-$inicial_hora_intervalo[1])*3);
                                                    $valor_final = converteHorasDecimal('00:'.(($inicial_hora_funcionario[1]-20)*3));
                                                    $cont_atendentes_escala += (1 - ($valor_final)) - ($desconto_intervalo);
                                                }else{
                                                    $cont_atendentes_escala += 1;
                                                }  
                                                
                                            }else if($inicial_hora_intervalo[0] == $hora_zero){
                                                if($inicial_hora_intervalo[1] >= '20' && $inicial_hora_intervalo[1] < '40'){
                                                    $desconto_intervalo = converteHorasDecimal('00:'.(20-$inicial_hora_intervalo[1])*3);
                                                    $valor_final = converteHorasDecimal('00:'.(($inicial_hora_funcionario[1]-20)*3));
                                                    $cont_atendentes_escala += (1 - ($valor_final)) - ($desconto_intervalo);
                                                }else{
                                                    $cont_atendentes_escala += 1;
                                                }
                                               
                                            }else{
                                                $valor_final = converteHorasDecimal('00:'.(($inicial_hora_funcionario[1]-20)*3));
                                                $cont_atendentes_escala += 1 - ($valor_final);
                                            }

                                        }else if($final_hora_funcionario[0] == $hora_zero  && $final_hora_funcionario[1] >= '20' && $final_hora_funcionario[1] < '40'){
                                            if($inicial_hora_intervalo[0] == $hora_zero && $final_hora_intervalo[0] == $hora_zero){
                                                if($inicial_hora_intervalo[1] >= '20' && $inicial_hora_intervalo[1] < '40' && $final_hora_intervalo[1] >= '20' && $final_hora_intervalo[1] < '40'){
                                                    $valor_final = converteHorasDecimal('00:'.(($inicial_hora_funcionario[1]-20)*3));
                                                    $desconto_intervalo = converteHorasDecimal('00:'.(40-($final_hora_intervalo[1] - $inicial_hora_intervalo[1]))*3);
                                                    $cont_atendentes_escala += $desconto_intervalo - $valor_final;
                                                }else if($inicial_hora_intervalo[1] >= '20' && $inicial_hora_intervalo[1] < '40'){
                                                    $desconto_intervalo = converteHorasDecimal('00:'.(40-$inicial_hora_intervalo[1])*3);
                                                    $valor_final = converteHorasDecimal('00:'.$inicial_hora_funcionario[1])*3;
                                                    $cont_atendentes_escala += (1 - ($valor_final)) - ($desconto_intervalo);
                                                }else if($final_hora_intervalo[1] >= '20' && $final_hora_intervalo[1] < '40'){
                                                    $desconto_intervalo = converteHorasDecimal('00:'.(40-$final_hora_intervalo[1])*3);
                                                    $valor_final = converteHorasDecimal('00:'.(($final_hora_funcionario[1]-20)*3));
                                                    $cont_atendentes_escala += $valor_final - (1 -$desconto_intervalo);
                                                }else{
                                                    $cont_atendentes_escala += 1;
                                                }
                                            }else if($final_hora_intervalo[0] == $hora_zero){
                                                if($final_hora_intervalo[1] >= '20' && $final_hora_intervalo[1] < '40'){
                                                    $desconto_intervalo = converteHorasDecimal('00:'.(40-$final_hora_intervalo[1])*3);
                                                    $valor_final = converteHorasDecimal('00:'.(($final_hora_funcionario[1]-20)*3));
                                                    $cont_atendentes_escala += $valor_final - (1 -$desconto_intervalo);
                                                }else{
                                                    $cont_atendentes_escala += 0;
                                                }
                                            }else{
                                                $valor_final = converteHorasDecimal('00:'.(($final_hora_funcionario[1]-20)*3));
                                                $cont_atendentes_escala += $valor_final;
                                            }

                                        }else{
                                            if($inicial_hora_intervalo[0] == $hora_zero && $final_hora_intervalo[0] == $hora_zero){
                                                if($inicial_hora_intervalo[1] >= '20' && $inicial_hora_intervalo[1] < '40' && $final_hora_intervalo[1] >= '20' && $final_hora_intervalo[1] < '40'){
                                                    $desconto_intervalo = converteHorasDecimal('00:'.(40-($final_hora_intervalo[1] - $inicial_hora_intervalo[1]))*3);
                                                    $cont_atendentes_escala += $desconto_intervalo;
                                                }else if($inicial_hora_intervalo[1] >= '20' && $inicial_hora_intervalo[1] < '40'){
                                                    $desconto_intervalo = converteHorasDecimal('00:'.(40-$inicial_hora_intervalo[1])*3);
                                                    $cont_atendentes_escala += 1 - $desconto_intervalo;
                                                }else if($final_hora_intervalo[1] >= '20' && $final_hora_intervalo[1] < '40'){
                                                    $desconto_intervalo = converteHorasDecimal('00:'.(40-$final_hora_intervalo[1])*3);
                                                    $cont_atendentes_escala += $desconto_intervalo;
                                                }else if($final_hora_intervalo[1] >= '40'){
                                                    $cont_atendentes_escala += 0;
                                                }else{
                                                    $cont_atendentes_escala += 1;
                                                }  
                                            }else if($inicial_hora_intervalo[0] == $hora_zero){
                                                if($inicial_hora_intervalo[1] >= '20' && $inicial_hora_intervalo[1] < '40'){
                                                    $desconto_intervalo = converteHorasDecimal('00:'.(40-$inicial_hora_intervalo[1])*3);
                                                    $cont_atendentes_escala += 1 - $desconto_intervalo;
                                                }else{
                                                    $cont_atendentes_escala += 1;
                                                }
                                            }else if($final_hora_intervalo[0] == $hora_zero){
                                                if($final_hora_intervalo[1] >= '20' && $final_hora_intervalo[1] < '40'){
                                                    $desconto_intervalo = converteHorasDecimal('00:'.(40-$final_hora_intervalo[1])*3);
                                                    $cont_atendentes_escala += $desconto_intervalo;
                                                }else if($final_hora_intervalo[1] >= '40'){
                                                    $cont_atendentes_escala += 0;
                                                }else{
                                                    $cont_atendentes_escala += 1;
                                                }
                                            }else if(($inicial_hora_intervalo[0] < $hora_zero && $final_hora_intervalo[0] > $hora_zero) ){
                                                $cont_atendentes_escala += 0;
                                            }else{
                                                $cont_atendentes_escala += 1;
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }                                       
                }
                if($dados_normais_especiais){
                    foreach ($dados_normais_especiais as $conteudo_normais_especiais) {
                        $inicial_hora_funcionario = explode(":", $conteudo_normais_especiais['inicial_especial']);
                        $final_hora_funcionario = explode(":", $conteudo_normais_especiais['final_especial']);
                        
                        if(($inicial_hora_funcionario[0] <= $hora_zero) && ($final_hora_funcionario[0] >= $hora_zero)){

                            if(($turno == 1 && $conteudo_normais_especiais['carga_horaria'] == 'integral') || ($turno == 2 && $conteudo_normais_especiais['carga_horaria'] == 'meio') || ($turno == 3 && $conteudo_normais_especiais['carga_horaria'] == 'jovem') || ($turno == 4 && $conteudo_normais_especiais['carga_horaria'] == 'estagio') || (!$turno) ){
                                
                                if(!$conteudo_normais_especiais['tempo_intervalo']){
                                    if($inicial_hora_funcionario[0] == $hora_zero && $inicial_hora_funcionario[1] >= '20' && $inicial_hora_funcionario[1] < '40'){
                                        $valor_final = converteHorasDecimal('00:'.(($inicial_hora_funcionario[1]-20)*3));
                                        $cont_atendentes_escala += 1-($valor_final);
                                    }else if($final_hora_funcionario[0] == $hora_zero && $inicial_hora_funcionario[1] >= '20' && $inicial_hora_funcionario[1] < '40'){
                                        $valor_final = converteHorasDecimal('00:'.(($final_hora_funcionario[1]-20)*3));
                                        $cont_atendentes_escala += $valor_final;
                                    }else{
                                        $cont_atendentes_escala += 1;
                                    }
                                }else{
                                    $data_inicio_compara = date('Y-m-d H:i:s', strtotime("+0 minutes",strtotime($conteudo_normais_especiais['inicial_especial'])));
                                    $data_fim_compara = date('Y-m-d H:i:s', strtotime("+0 minutes",strtotime($conteudo_normais_especiais['final_especial'])));
                                
                                    if($data_inicio_compara > $data_fim_compara){
                                        $data_inicio_compara = date('Y-m-d H:i:s', strtotime("-1 days",strtotime($conteudo_normais_especiais['inicial_especial'])));
                                    }
                                
                                    $diferenca_datas = strtotime($data_fim_compara) - strtotime($data_inicio_compara);
                                    
                                    $tempo_total_trabalho = converteSegundosHoras($diferenca_datas);
                                    $tempo_total_trabalho = converteHorasDecimal($tempo_total_trabalho);
                                    $tempo_total_intervalo = converteHorasDecimal($conteudo_normais_especiais['tempo_intervalo']);
                                    $tempo_desconto = $tempo_total_intervalo/$tempo_total_trabalho;
                                
                                    if($inicial_hora_funcionario[0] == $hora_zero && $inicial_hora_funcionario[1] >= '20' && $inicial_hora_funcionario[1] < '40'){
                                        $valor_final = converteHorasDecimal('00:'.(($inicial_hora_funcionario[1]-20)*3));
                                        $cont_atendentes_escala += (1 - ($valor_final)) - ($tempo_desconto * (1 -  ($valor_final)));
                                    
                                    }else if($final_hora_funcionario[0] == $hora_zero && $inicial_hora_funcionario[1] >= '20' && $inicial_hora_funcionario[1] < '40'){
                                        $valor_final = converteHorasDecimal('00:'.(($final_hora_funcionario[1]-20)*3));
                                        $cont_atendentes_escala += (($valor_final) - ($tempo_desconto * $valor_final));
                                    
                                    }else{
                                        $cont_atendentes_escala += 1 - $tempo_desconto;
                                    }
                                }
                            }
                        }
                    }                                       
                }

                //INVERTIDOS CONSULTA
                    $dados_invertidos = DBRead('', 'tb_horarios_escala', "WHERE data_inicial = '".$consulta_data."' ".$consulta_invertidos." AND atendente = 1 AND id_usuario NOT IN (SELECT id_usuario FROM tb_ferias WHERE id_usuario = id_usuario AND data_de <= '".$data."' AND data_ate >= '".$data."') AND id_usuario NOT IN (SELECT a.id_usuario FROM tb_horarios_escala a INNER JOIN tb_horarios_especiais b ON a.id_horarios_escala = b.id_horarios_escala WHERE b.dia = '".$data."')");

                    $dados_invertidos_especiais = DBRead('', 'tb_horarios_escala a', "INNER JOIN tb_horarios_especiais b ON a.id_horarios_escala = b.id_horarios_escala WHERE b.dia = '".$data."' AND b.inicial_especial < '".$hora_zero.":40' AND b.inicial_especial > b.final_especial ");

                //INVERTIDOS
                if($dados_invertidos){
                    
                    foreach ($dados_invertidos as $conteudo_invertido) {
                        $inicial_hora_funcionario = explode(":", $conteudo_invertido[$consulta_inicial_banco]);

                        if($inicial_hora_funcionario[0] <= $hora_zero){

                            if(($turno == 1 && $conteudo_invertido['carga_horaria'] == 'integral') || ($turno == 2 && $conteudo_invertido['carga_horaria'] == 'meio') || ($turno == 3 && $conteudo_invertido['carga_horaria'] == 'jovem') || ($turno == 4 && $conteudo_invertido['carga_horaria'] == 'estagio') || (!$turno) ){

                                $dados_intervalo_invertidos = DBRead('', 'tb_horarios_escala_intervalo', "WHERE id_horarios_escala = '".$conteudo_invertido['id_horarios_escala']."' AND dia = '".$dia_intervalo."' ");
                                if(!$dados_intervalo_normais){
                                    if($inicial_hora_funcionario[0] == $hora_zero && $inicial_hora_funcionario[1] >= '20' && $inicial_hora_funcionario[1] < '40'){
                                        $valor_final = converteHorasDecimal('00:'.(($inicial_hora_funcionario[1]-20)*3));
                                        $cont_atendentes_escala += 1-($valor_final);
                                    }else{
                                        $cont_atendentes_escala += 1;
                                    }
                                }else{
                                    if($dados_intervalo_invertidos[0]['tipo_intervalo'] == 1){
                                        $data_inicio_compara = date('Y-m-d H:i:s', strtotime("+0 minutes",strtotime($conteudo_invertido[$consulta_inicial_banco])));
                                        $data_fim_compara = date('Y-m-d H:i:s', strtotime("+0 minutes",strtotime($conteudo_invertido[$consulta_final_banco])));

                                        if($data_inicio_compara > $data_fim_compara){
                                            $data_inicio_compara = date('Y-m-d H:i:s', strtotime("-1 days",strtotime($conteudo_invertido[$consulta_inicial_banco])));
                                        }
                                        
                                        $diferenca_datas = strtotime($data_fim_compara) - strtotime($data_inicio_compara);
                                        
                                        $tempo_total_trabalho = converteSegundosHoras($diferenca_datas);
                                        $tempo_total_trabalho = converteHorasDecimal($tempo_total_trabalho);

                                        $tempo_total_intervalo = converteHorasDecimal($dados_intervalo_normais[0]['tempo_intervalo']);

                                        $tempo_desconto = $tempo_total_intervalo/$tempo_total_trabalho;

                                        if($inicial_hora_funcionario[0] == $hora_zero && $inicial_hora_funcionario[1] >= '20' && $inicial_hora_funcionario[1] < '40'){
                                            $valor_final = converteHorasDecimal('00:'.(($inicial_hora_funcionario[1]-20)*3));
                                            $cont_atendentes_escala += (1 - ($valor_final)) - ($tempo_desconto * (1 -  ($valor_final)));
                                        }else{
                                            $cont_atendentes_escala += 1 - $tempo_desconto;
                                        } 
                                    }else{

                                        $inicial_hora_intervalo = explode(":", $dados_intervalo_invertidos[0]['intervalo_inicial']);
                                        $final_hora_intervalo = explode(":", $dados_intervalo_invertidos[0]['intervalo_final']);
                                    
                                        if($inicial_hora_funcionario[0] == $hora_zero && $inicial_hora_funcionario[1] >= '20' && $inicial_hora_funcionario[1] < '40'){
                                            if($inicial_hora_intervalo[0] == $hora_zero && $final_hora_intervalo[0] == $hora_zero){
                                                if($inicial_hora_intervalo[1] >= '20' && $inicial_hora_intervalo[1] < '40' && $final_hora_intervalo[1] >= '20' && $final_hora_intervalo[1] < '40'){
                                                    $valor_final = converteHorasDecimal('00:'.(($inicial_hora_funcionario[1]-20)*3));
                                                    $desconto_intervalo = converteHorasDecimal('00:'.(20-($final_hora_intervalo[1] - $inicial_hora_intervalo[1]))*3);
                                                    $cont_atendentes_escala += $desconto_intervalo - $valor_final;
                                                }else if($inicial_hora_intervalo[1] >= '20' && $inicial_hora_intervalo[1] < '40'){
                                                    $desconto_intervalo = converteHorasDecimal('00:'.(20-$inicial_hora_intervalo[1])*3);
                                                    $valor_final = converteHorasDecimal('00:'.(($inicial_hora_funcionario[1]-20)*3));
                                                    $cont_atendentes_escala += (1 - ($valor_final)) - ($desconto_intervalo);
                                                }else{
                                                    $cont_atendentes_escala += 1;
                                                }  
                                                
                                            }else if($inicial_hora_intervalo[0] == $hora_zero){
                                                if($inicial_hora_intervalo[1] >= '20' && $inicial_hora_intervalo[1] < '40'){
                                                    $desconto_intervalo = converteHorasDecimal('00:'.(20-$inicial_hora_intervalo[1])*3);
                                                    $valor_final = converteHorasDecimal('00:'.(($inicial_hora_funcionario[1]-20)*3));
                                                    $cont_atendentes_escala += (1 - ($valor_final)) - ($desconto_intervalo);
                                                }else{
                                                    $cont_atendentes_escala += 1;
                                                }
                                               
                                            }else{
                                                $valor_final = converteHorasDecimal('00:'.(($inicial_hora_funcionario[1]-20)*3));
                                                $cont_atendentes_escala += 1 - ($valor_final);
                                            }
                                    
                                        }else{
                                            if($inicial_hora_intervalo[0] == $hora_zero && $final_hora_intervalo[0] == $hora_zero){
                                                if($inicial_hora_intervalo[1] >= '20' && $inicial_hora_intervalo[1] < '40' && $final_hora_intervalo[1] >= '20' && $final_hora_intervalo[1] < '40'){
                                                    $desconto_intervalo = converteHorasDecimal('00:'.(40-($final_hora_intervalo[1] - $inicial_hora_intervalo[1]))*3);
                                                    $cont_atendentes_escala += $desconto_intervalo;
                                                }else if($inicial_hora_intervalo[1] >= '20' && $inicial_hora_intervalo[1] < '40'){
                                                    $desconto_intervalo = converteHorasDecimal('00:'.(40-$inicial_hora_intervalo[1])*3);
                                                    $cont_atendentes_escala += 1 - $desconto_intervalo;
                                                }else if($final_hora_intervalo[1] >= '20' && $final_hora_intervalo[1] < '40'){
                                                    $desconto_intervalo = converteHorasDecimal('00:'.(40-$final_hora_intervalo[1])*3);
                                                    $cont_atendentes_escala += $desconto_intervalo;
                                                }else if($final_hora_intervalo[1] >= '40'){
                                                    $cont_atendentes_escala += 0;
                                                }else{
                                                    $cont_atendentes_escala += 1;
                                                }  
                                            }else if($inicial_hora_intervalo[0] == $hora_zero){
                                                if($inicial_hora_intervalo[1] >= '20' && $inicial_hora_intervalo[1] < '40'){
                                                    $desconto_intervalo = converteHorasDecimal('00:'.(40-$inicial_hora_intervalo[1])*3);
                                                    $cont_atendentes_escala += 1 - $desconto_intervalo;
                                                }else{
                                                    $cont_atendentes_escala += 1;
                                                }
                                            }else if($final_hora_intervalo[0] == $hora_zero){
                                                if($final_hora_intervalo[1] >= '20' && $final_hora_intervalo[1] < '40'){
                                                    $desconto_intervalo = converteHorasDecimal('00:'.(40-$final_hora_intervalo[1])*3);
                                                    $cont_atendentes_escala += $desconto_intervalo;
                                                }else if($final_hora_intervalo[1] >= '40'){
                                                    $cont_atendentes_escala += 0;
                                                }else{
                                                    $cont_atendentes_escala += 1;
                                                }
                                            }else if(($inicial_hora_intervalo[0] < $hora_zero && $final_hora_intervalo[0] > $hora_zero) ){
                                                $cont_atendentes_escala += 0;
                                            }else{
                                                $cont_atendentes_escala += 1;
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }

                if($dados_invertidos_especiais){
                    
                    foreach ($dados_invertidos_especiais as $conteudo_invertidos_especiais) {
                        $inicial_hora_funcionario = explode(":", $conteudo_invertidos_especiais['inicial_especial']);

                        if($inicial_hora_funcionario[0] <= $hora_zero){

                            if(($turno == 1 && $conteudo_invertidos_especiais['carga_horaria'] == 'integral') || ($turno == 2 && $conteudo_invertidos_especiais['carga_horaria'] == 'meio') || ($turno == 3 && $conteudo_invertidos_especiais['carga_horaria'] == 'jovem') || ($turno == 4 && $conteudo_invertidos_especiais['carga_horaria'] == 'estagio') || (!$turno) ){
                                
                                if(!$conteudo_invertidos_especiais['tempo_intervalo']){
                                    if($inicial_hora_funcionario[0] == $hora_zero && $inicial_hora_funcionario[1] >= '20' && $inicial_hora_funcionario[1] < '40'){
                                        $valor_final = converteHorasDecimal('00:'.(($inicial_hora_funcionario[1]-20)*3));
                                        $cont_atendentes_escala += 1-($valor_final);
                                    }else{
                                        $cont_atendentes_escala += 1;
                                    }
                                }else{
                                    $data_inicio_compara = date('Y-m-d H:i:s', strtotime("+0 minutes",strtotime($conteudo_invertidos_especiais['inicial_especial'])));
                                    $data_fim_compara = date('Y-m-d H:i:s', strtotime("+0 minutes",strtotime($conteudo_invertidos_especiais['final_especial'])));
                                
                                    if($data_inicio_compara > $data_fim_compara){
                                        $data_inicio_compara = date('Y-m-d H:i:s', strtotime("-1 days",strtotime($conteudo_invertidos_especiais['inicial_especial'])));
                                    }
                                
                                    $diferenca_datas = strtotime($data_fim_compara) - strtotime($data_inicio_compara);
                                    
                                    $tempo_total_trabalho = converteSegundosHoras($diferenca_datas);
                                    $tempo_total_trabalho = converteHorasDecimal($tempo_total_trabalho);
                                    $tempo_total_intervalo = converteHorasDecimal($conteudo_invertidos_especiais['tempo_intervalo']);
                                    $tempo_desconto = $tempo_total_intervalo/$tempo_total_trabalho;
                                
                                    if($inicial_hora_funcionario[0] == $hora_zero && $inicial_hora_funcionario[1] >= '20' && $inicial_hora_funcionario[1] < '40'){
                                        $valor_final = converteHorasDecimal('00:'.(($inicial_hora_funcionario[1]-20)*3));
                                        $cont_atendentes_escala += (1 - ($valor_final)) - ($tempo_desconto * (1 -  ($valor_final)));
                                    
                                    }else{
                                        $cont_atendentes_escala += 1 - $tempo_desconto;
                                    }
                                }
                            }
                        }
                    }
                }

                if($data == $consulta_data){
                    $consulta_data = $consulta_data_ontem;
                }

                //INVERTIDOS ONTEM CONSULTA
                    $dados_invertidos_ontem = DBRead('', 'tb_horarios_escala', "WHERE data_inicial = '".$consulta_data."' ".$consulta_ontem." AND atendente = 1 AND id_usuario NOT IN (SELECT id_usuario FROM tb_ferias WHERE id_usuario = id_usuario AND data_de <= '".$data_ontem."' AND data_ate >= '".$data_ontem."') AND id_usuario NOT IN (SELECT a.id_usuario FROM tb_horarios_escala a INNER JOIN tb_horarios_especiais b ON a.id_horarios_escala = b.id_horarios_escala WHERE b.dia = '".$data_ontem."')");

                    $dados_invertidos_ontem_especiais =  DBRead('', 'tb_horarios_escala a', "INNER JOIN tb_horarios_especiais b ON a.id_horarios_escala = b.id_horarios_escala WHERE b.dia = '".$data_ontem."' AND b.final_especial > '".$hora_zero.":20' AND b.inicial_especial > b.final_especial ");
               
               //INVERTIDOS ONTEM
                if($dados_invertidos_ontem){
                    foreach ($dados_invertidos_ontem as $conteudo_ontem) {
                        $final_hora_funcionario = explode(":", $conteudo_ontem[$consulta_final_banco_ontem]);

                        if($final_hora_funcionario[0] >= $hora_zero){

                            if(($turno == 1 && $conteudo_ontem['carga_horaria'] == 'integral') || ($turno == 2 && $conteudo_ontem['carga_horaria'] == 'meio') || ($turno == 3 && $conteudo_ontem['carga_horaria'] == 'jovem') || ($turno == 4 && $conteudo_ontem['carga_horaria'] == 'estagio') || (!$turno) ){

                                $dados_intervalo_invertidos_ontem = DBRead('', 'tb_horarios_escala_intervalo', "WHERE id_horarios_escala = '".$conteudo_ontem['id_horarios_escala']."' AND dia = '".$dia_intervalo_ontem."' ");
                                if(!$dados_intervalo_invertidos_ontem){
                                    if($final_hora_funcionario[0] == $hora_zero && $final_hora_funcionario[1] >= '20' && $final_hora_funcionario[1] < '40'){
                                        $valor_final = converteHorasDecimal('00:'.(($final_hora_funcionario[1]-20)*3));
                                        $cont_atendentes_escala += $valor_final;
                                    }else{
                                        $cont_atendentes_escala += 1;
                                    }
                                }else{
                                    if($dados_intervalo_normais[0]['tipo_intervalo'] == 1){
                                        $data_inicio_compara = date('Y-m-d H:i:s', strtotime("+0 minutes",strtotime($conteudo_ontem[$consulta_inicial_banco_ontem])));
                                        $data_fim_compara = date('Y-m-d H:i:s', strtotime("+0 minutes",strtotime($conteudo_ontem[$consulta_final_banco_ontem])));
                                
                                        if($data_inicio_compara > $data_fim_compara){
                                            $data_inicio_compara = date('Y-m-d H:i:s', strtotime("-1 days",strtotime($conteudo_ontem[$consulta_inicial_banco_ontem])));
                                        }
                                        
                                        $diferenca_datas = strtotime($data_fim_compara) - strtotime($data_inicio_compara);
                                        
                                        $tempo_total_trabalho = converteSegundosHoras($diferenca_datas);
                                        $tempo_total_trabalho = converteHorasDecimal($tempo_total_trabalho);
                                
                                        $tempo_total_intervalo = converteHorasDecimal($dados_intervalo_normais[0]['tempo_intervalo']);
                                
                                        $tempo_desconto = $tempo_total_intervalo/$tempo_total_trabalho;

                                        if($final_hora_funcionario[0] == $hora_zero && $final_hora_funcionario[1] >= '20' && $final_hora_funcionario[1] < '40'){
                                            $valor_final = converteHorasDecimal('00:'.(($final_hora_funcionario[1]-20)*3));
                                            $cont_atendentes_escala += (($valor_final) - ($tempo_desconto * $valor_final));
                                
                                        }else{
                                            $cont_atendentes_escala += 1 - $tempo_desconto;
                                        } 
                                    }else{

                                        $inicial_hora_intervalo = explode(":", $dados_intervalo_invertidos_ontem[0]['intervalo_inicial']);
                                        $final_hora_intervalo = explode(":", $dados_intervalo_invertidos_ontem[0]['intervalo_final']);
                                    
                                        if($final_hora_funcionario[0] == $hora_zero  && $final_hora_funcionario[1] >= '20' && $final_hora_funcionario[1] < '40'){
                                            if($inicial_hora_intervalo[0] == $hora_zero && $final_hora_intervalo[0] == $hora_zero){
                                                if($inicial_hora_intervalo[1] >= '20' && $inicial_hora_intervalo[1] < '40' && $final_hora_intervalo[1] >= '20' && $final_hora_intervalo[1] < '40'){
                                                    $valor_final = converteHorasDecimal('00:'.(($inicial_hora_funcionario[1]-20)*3));
                                                    $desconto_intervalo = converteHorasDecimal('00:'.(40-($final_hora_intervalo[1] - $inicial_hora_intervalo[1]))*3);
                                                    $cont_atendentes_escala += $desconto_intervalo - $valor_final;
                                                }else if($inicial_hora_intervalo[1] >= '20' && $inicial_hora_intervalo[1] < '40'){
                                                    $desconto_intervalo = converteHorasDecimal('00:'.(40-$inicial_hora_intervalo[1])*3);
                                                    $valor_final = converteHorasDecimal('00:'.$inicial_hora_funcionario[1])*3;
                                                    $cont_atendentes_escala += (1 - ($valor_final)) - ($desconto_intervalo);
                                                }else if($final_hora_intervalo[1] >= '20' && $final_hora_intervalo[1] < '40'){
                                                    $desconto_intervalo = converteHorasDecimal('00:'.(40-$final_hora_intervalo[1])*3);
                                                    $valor_final = converteHorasDecimal('00:'.(($final_hora_funcionario[1]-20)*3));
                                                    $cont_atendentes_escala += $valor_final - (1 -$desconto_intervalo);
                                                }else{
                                                    $cont_atendentes_escala += 1;
                                                }
                                            }else if($final_hora_intervalo[0] == $hora_zero){
                                                if($final_hora_intervalo[1] >= '20' && $final_hora_intervalo[1] < '40'){
                                                    $desconto_intervalo = converteHorasDecimal('00:'.(40-$final_hora_intervalo[1])*3);
                                                    $valor_final = converteHorasDecimal('00:'.(($final_hora_funcionario[1]-20)*3));
                                                    $cont_atendentes_escala += $valor_final - (1 -$desconto_intervalo);
                                                }else{
                                                    $cont_atendentes_escala += 0;
                                                }
                                            }else{
                                                $valor_final = converteHorasDecimal('00:'.(($final_hora_funcionario[1]-20)*3));
                                                $cont_atendentes_escala += $valor_final;
                                            }
                                    
                                        }else{
                                            if($inicial_hora_intervalo[0] == $hora_zero && $final_hora_intervalo[0] == $hora_zero){
                                                if($inicial_hora_intervalo[1] >= '20' && $inicial_hora_intervalo[1] < '40' && $final_hora_intervalo[1] >= '20' && $final_hora_intervalo[1] < '40'){
                                                    $desconto_intervalo = converteHorasDecimal('00:'.(40-($final_hora_intervalo[1] - $inicial_hora_intervalo[1]))*3);
                                                    $cont_atendentes_escala += $desconto_intervalo;
                                                }else if($inicial_hora_intervalo[1] >= '20' && $inicial_hora_intervalo[1] < '40'){
                                                    $desconto_intervalo = converteHorasDecimal('00:'.(40-$inicial_hora_intervalo[1])*3);
                                                    $cont_atendentes_escala += 1 - $desconto_intervalo;
                                                }else if($final_hora_intervalo[1] >= '20' && $final_hora_intervalo[1] < '40'){
                                                    $desconto_intervalo = converteHorasDecimal('00:'.(40-$final_hora_intervalo[1])*3);
                                                    $cont_atendentes_escala += $desconto_intervalo;
                                                }else if($final_hora_intervalo[1] >= '40'){
                                                    $cont_atendentes_escala += 0;
                                                }else{
                                                    $cont_atendentes_escala += 1;
                                                }  
                                            }else if($inicial_hora_intervalo[0] == $hora_zero){
                                                if($inicial_hora_intervalo[1] >= '20' && $inicial_hora_intervalo[1] < '40'){
                                                    $desconto_intervalo = converteHorasDecimal('00:'.(40-$inicial_hora_intervalo[1])*3);
                                                    $cont_atendentes_escala += 1 - $desconto_intervalo;
                                                }else{
                                                    $cont_atendentes_escala += 1;
                                                }
                                            }else if($final_hora_intervalo[0] == $hora_zero){
                                                if($final_hora_intervalo[1] >= '20' && $final_hora_intervalo[1] < '40'){
                                                    $desconto_intervalo = converteHorasDecimal('00:'.(40-$final_hora_intervalo[1])*3);
                                                    $cont_atendentes_escala += $desconto_intervalo;
                                                }else if($final_hora_intervalo[1] >= '40'){
                                                    $cont_atendentes_escala += 0;
                                                }else{
                                                    $cont_atendentes_escala += 1;
                                                }
                                            }else if(($inicial_hora_intervalo[0] < $hora_zero && $final_hora_intervalo[0] > $hora_zero) ){
                                                $cont_atendentes_escala += 0;
                                            }else{
                                                $cont_atendentes_escala += 1;
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }

                if($dados_invertidos_ontem_especiais){
                    foreach ($dados_invertidos_ontem_especiais as $conteudo_invertidos_ontem_especiais) {
                        $final_hora_funcionario = explode(":", $conteudo_invertidos_ontem_especiais['final_especial']);
                        if($final_hora_funcionario[0] >= $hora_zero){

                            if(($turno == 1 && $conteudo_invertidos_ontem_especiais['carga_horaria'] == 'integral') || ($turno == 2 && $conteudo_invertidos_ontem_especiais['carga_horaria'] == 'meio') || ($turno == 3 && $conteudo_invertidos_ontem_especiais['carga_horaria'] == 'jovem') || ($turno == 4 && $conteudo_invertidos_ontem_especiais['carga_horaria'] == 'estagio') || (!$turno) ){

                                if(!$conteudo_invertidos_ontem_especiais['tempo_intervalo']){
                                    if($final_hora_funcionario[0] == $hora_zero && $final_hora_funcionario[1] >= '20' && $final_hora_funcionario[1] < '40'){
                                        $valor_final = converteHorasDecimal('00:'.(($final_hora_funcionario[1]-20)*3));
                                        $cont_atendentes_escala += sprintf("%01.2f", $valor_final);
                                    }else{
                                        $cont_atendentes_escala += 1;
                                    }
                                }else{
                                    $data_inicio_compara = date('Y-m-d H:i:s', strtotime("+0 minutes",strtotime($conteudo_invertidos_ontem_especiais['inicial_especial'])));
                                    $data_fim_compara = date('Y-m-d H:i:s', strtotime("+0 minutes",strtotime($conteudo_invertidos_ontem_especiais['final_especial'])));
                                
                                    if($data_inicio_compara > $data_fim_compara){
                                        $data_inicio_compara = date('Y-m-d H:i:s', strtotime("-1 days",strtotime($conteudo_invertidos_ontem_especiais['inicial_especial'])));
                                    }
                                
                                    $diferenca_datas = strtotime($data_fim_compara) - strtotime($data_inicio_compara);
                                    
                                    $tempo_total_trabalho = converteSegundosHoras($diferenca_datas);
                                    $tempo_total_trabalho = converteHorasDecimal($tempo_total_trabalho);
                                    $tempo_total_intervalo = converteHorasDecimal($conteudo_invertidos_ontem_especiais['tempo_intervalo']);
                                    $tempo_desconto = $tempo_total_intervalo/$tempo_total_trabalho;
                                
                                    if($final_hora_funcionario[0] == $hora_zero && $inicial_hora_funcionario[1] >= '20' && $inicial_hora_funcionario[1] < '40'){
                                        $valor_final = converteHorasDecimal('00:'.(($final_hora_funcionario[1]-20)*3));
                                        $cont_atendentes_escala += (($valor_final) - ($tempo_desconto * $valor_final));
                                    
                                    }else{
                                        $cont_atendentes_escala += 1 - $tempo_desconto;
                                    }
                                }
                            }
                        }
                    }
                }

                if(!$cont_atendentes_escala){
                    $cont_atendentes_escala = 0;
                }  
                
            //fim codigo atendentes escala 20

            $atendentes_escala[$hora_array+1] = sprintf("%01.2f", round($cont_atendentes_escala, 2));
            $cont_atendentes_escala = 0;

            //início codigo atendentes escala 40
                $consulta_data = explode("-", $data);
                $consulta_data = $consulta_data[0].'-'.$consulta_data[1].'-01';

                //VERIFICA O DIA DA CONSULTA PARA PESQUISAR (SEG A SEX, SÁBADO OU DOMINGO)
                if($dia_hoje == 'Domingo'){
                    $consulta_dia =  "AND inicial_dom < '".$hora_proxima.":00' AND final_dom > '".$hora_zero.":40'";
                    
                    $consulta_inicial_banco = 'inicial_dom';
                    $consulta_final_banco = 'final_dom';

                    $consulta_inicial_banco_ontem = 'inicial_sab';
                    $consulta_final_banco_ontem = 'final_sab';

                    $consulta_ontem = "AND final_sab > '".$hora_zero.":40' AND inicial_sab > final_sab AND folga_seg != '".$dia_ontem."'";
                    $consulta_invertidos = "AND inicial_dom < '".$hora_proxima.":00' AND inicial_dom > final_dom AND id_horarios_escala NOT IN (SELECT id_horarios_escala FROM tb_folgas_dom WHERE dia = '".$data."')";
                    $consulta_normais = "AND inicial_dom < final_dom AND id_horarios_escala NOT IN (SELECT id_horarios_escala FROM tb_folgas_dom WHERE dia = '".$data."')";

                    $dia_intervalo = 'dom';
                    $dia_intervalo_ontem = 'sab';

                }else if($dia_hoje == 'Sábado'){
                    $consulta_dia =  "AND inicial_sab < '".$hora_proxima.":00' AND final_sab > '".$hora_zero.":40'";
                    $consulta_inicial_banco = 'inicial_sab';
                    $consulta_final_banco = 'final_sab';
                    
                    $consulta_inicial_banco_ontem = 'inicial_seg';
                    $consulta_final_banco_ontem = 'final_seg';

                    $consulta_ontem = "AND final_seg > '".$hora_zero.":40' AND inicial_seg > final_seg AND folga_seg != '".$dia_ontem."'";
                    $consulta_invertidos = "AND inicial_sab < '".$hora_proxima.":00' AND inicial_sab > final_sab AND folga_seg != '".$dia_hoje."'";
                    $consulta_normais = "AND inicial_sab < final_sab AND folga_seg != '".$dia_hoje."'";

                    $dia_intervalo = 'sab';
                    $dia_intervalo_ontem = 'seg';

                }else{
                    $consulta_dia =  "AND inicial_seg < '".$hora_proxima.":00' AND final_seg > '".$hora_zero.":40'";
                    $consulta_inicial_banco = 'inicial_seg';
                    $consulta_final_banco = 'final_seg';

                    if($dia_hoje == 'Quinta' || $dia_hoje == 'Sexta'){
                        $consulta_invertidos = "AND inicial_seg < '".$hora_proxima.":00' AND inicial_seg > final_seg AND (folga_seg != '".$dia_hoje."' AND folga_seg != 'Quinta e Sexta')";
                        $consulta_normais = "AND inicial_seg < final_seg AND (folga_seg != '".$dia_hoje."' AND folga_seg != 'Quinta e Sexta')";
                    }else{
                        $consulta_invertidos = "AND inicial_seg < '".$hora_proxima.":00' AND inicial_seg > final_seg AND folga_seg != '".$dia_hoje."'";
                        $consulta_normais = "AND inicial_seg < final_seg AND folga_seg != '".$dia_hoje."'";
                    }

                    $consulta_invertidos = "AND inicial_seg < '".$hora_proxima.":00' AND inicial_seg > final_seg AND folga_seg != '".$dia_hoje."'";
                    $consulta_normais = "AND inicial_seg < final_seg AND folga_seg != '".$dia_hoje."'";

                    if($dia_hoje == 'Segunda'){
                        $consulta_ontem = "AND final_dom > '".$hora_zero.":40' AND inicial_dom > final_dom AND id_horarios_escala NOT IN (SELECT id_horarios_escala FROM tb_folgas_dom WHERE dia = '".$data_ontem."')";
                        
                        $consulta_inicial_banco_ontem = 'inicial_dom';
                        $consulta_final_banco_ontem = 'final_dom';

                        $dia_intervalo_ontem = 'dom';

                    }else{
                        if($dia_hoje == 'Sexta'){
                            $consulta_ontem = "AND final_seg > '".$hora_zero.":40' AND inicial_seg > final_seg AND (folga_seg != '".$dia_ontem."' AND folga_seg != 'Quinta e Sexta')";
                        }else{
                            $consulta_ontem = "AND final_seg > '".$hora_zero.":40' AND inicial_seg > final_seg AND folga_seg != '".$dia_ontem."'";
                        }
                        $consulta_inicial_banco_ontem = 'inicial_seg';
                        $consulta_final_banco_ontem = 'final_seg';
                        
                        $dia_intervalo_ontem = 'seg';
                    }

                    $dia_intervalo = 'seg';

                }
                //NORMAIS CONSULTA   

                    $dados_normais = DBRead('', 'tb_horarios_escala', "WHERE data_inicial = '".$consulta_data."' ".$consulta_dia." ".$consulta_normais." AND atendente = 1 AND id_usuario NOT IN (SELECT id_usuario FROM tb_ferias WHERE id_usuario = id_usuario AND data_de <= '".$data."' AND data_ate >= '".$data."') AND id_usuario NOT IN (SELECT a.id_usuario FROM tb_horarios_escala a INNER JOIN tb_horarios_especiais b ON a.id_horarios_escala = b.id_horarios_escala WHERE b.dia = '".$data."')");

                    $dados_normais_especiais = DBRead('', 'tb_horarios_escala a', "INNER JOIN tb_horarios_especiais b ON a.id_horarios_escala = b.id_horarios_escala WHERE b.dia = '".$data."' AND b.inicial_especial < '".$hora_proxima.":00' AND b.final_especial > '".$hora_zero.":40' ");              
                    
                //NORMAIS 
                if($dados_normais){
                    foreach ($dados_normais as $conteudo_normais) {
                        $inicial_hora_funcionario = explode(":", $conteudo_normais[$consulta_inicial_banco]);
                        $final_hora_funcionario = explode(":", $conteudo_normais[$consulta_final_banco]);
                        
                        if(($inicial_hora_funcionario[0] <= $hora_zero) && ($final_hora_funcionario[0] >= $hora_zero)){
                            
                            if(($turno == 1 && $conteudo_normais['carga_horaria'] == 'integral') || ($turno == 2 && $conteudo_normais['carga_horaria'] == 'meio') || ($turno == 3 && $conteudo_normais['carga_horaria'] == 'jovem') || ($turno == 4 && $conteudo_normais['carga_horaria'] == 'estagio') || (!$turno) ){

                                $dados_intervalo_normais = DBRead('', 'tb_horarios_escala_intervalo', "WHERE id_horarios_escala = '".$conteudo_normais['id_horarios_escala']."' AND dia = '".$dia_intervalo."' ");
                                if(!$dados_intervalo_normais){
                                    if($inicial_hora_funcionario[0] == $hora_zero && $inicial_hora_funcionario[1] != '00' && $inicial_hora_funcionario[1] >= '40'){

                                        $valor_final = converteHorasDecimal('00:'.(($inicial_hora_funcionario[1]-40)*3));
                                        $cont_atendentes_escala += 1-($valor_final);
                                    
                                    }else if($final_hora_funcionario[0] == $hora_zero && $final_hora_funcionario[1] != '00' && $final_hora_funcionario[1] >= '40'){
    
                                        $valor_final = converteHorasDecimal('00:'.(($final_hora_funcionario[1]-40)*3));
                                        $cont_atendentes_escala += $valor_final;
                                    
                                    }else{
                                        $cont_atendentes_escala += 1;
                                    
                                    }
                                }else{
                                    if($dados_intervalo_normais[0]['tipo_intervalo'] == 1){

                                        $data_inicio_compara = date('Y-m-d H:i:s', strtotime("+0 minutes",strtotime($conteudo_normais[$consulta_inicial_banco])));
                                        $data_fim_compara = date('Y-m-d H:i:s', strtotime("+0 minutes",strtotime($conteudo_normais[$consulta_final_banco])));

                                        if($data_inicio_compara > $data_fim_compara){
                                            $data_inicio_compara = date('Y-m-d H:i:s', strtotime("-1 days",strtotime($conteudo_normais[$consulta_inicial_banco])));
                                        }
                                        
                                        $diferenca_datas = strtotime($data_fim_compara) - strtotime($data_inicio_compara);
                                        
                                        $tempo_total_trabalho = converteSegundosHoras($diferenca_datas);
                                        $tempo_total_trabalho = converteHorasDecimal($tempo_total_trabalho);

                                        $tempo_total_intervalo = converteHorasDecimal($dados_intervalo_normais[0]['tempo_intervalo']);

                                        $tempo_desconto = $tempo_total_intervalo/$tempo_total_trabalho;

                                        if($inicial_hora_funcionario[0] == $hora_zero && $inicial_hora_funcionario[1] != '00' && $inicial_hora_funcionario[1] >= '40'){
                                            $valor_final = converteHorasDecimal('00:'.(($inicial_hora_funcionario[1]-40)*3));
                                            $cont_atendentes_escala += (1 - ($valor_final)) - ($tempo_desconto * (1 -  ($valor_final)));

                                        }else if($final_hora_funcionario[0] == $hora_zero && $final_hora_funcionario[1] != '00' && $final_hora_funcionario[1] >= '40'){
                                            $valor_final = converteHorasDecimal('00:'.(($final_hora_funcionario[1]-40)*3));
                                            $cont_atendentes_escala += (($valor_final) - ($tempo_desconto * $valor_final));

                                        }else{
                                            $cont_atendentes_escala += 1 - $tempo_desconto;
                                        }     
                                    }else{

                                        $inicial_hora_intervalo = explode(":", $dados_intervalo_normais[0]['intervalo_inicial']);
                                        $final_hora_intervalo = explode(":", $dados_intervalo_normais[0]['intervalo_final']);
                                    
                                        if($inicial_hora_funcionario[0] == $hora_zero && $inicial_hora_funcionario[1] != '00' && $inicial_hora_funcionario[1] >= '40'){

                                            //Ta na mao
                                            if($inicial_hora_intervalo[0] == $hora_zero && $final_hora_intervalo[0] == $hora_zero){
                                                if($inicial_hora_intervalo[1] >= '40' && $final_hora_intervalo[1] >= '40'){
                                                    $valor_final = converteHorasDecimal('00:'.(($inicial_hora_funcionario[1]-40)*3));
                                                    $desconto_intervalo = converteHorasDecimal('00:'.(60-($final_hora_intervalo[1] - $inicial_hora_intervalo[1]))*3);
                                                    $cont_atendentes_escala += $desconto_intervalo - $valor_final;
                                                }else if($inicial_hora_intervalo[1] >= '40'){
                                                    $desconto_intervalo = converteHorasDecimal('00:'.(60-$inicial_hora_intervalo[1])*3);
                                                    $valor_final = converteHorasDecimal('00:'.(($inicial_hora_funcionario[1]-40)*3));
                                                    $cont_atendentes_escala += (1 - ($valor_final)) - ($desconto_intervalo);
                                                }else{
                                                    $cont_atendentes_escala += 1;
                                                }  
                                                
                                            }else if($inicial_hora_intervalo[0] == $hora_zero){
                                                if($inicial_hora_intervalo[1] >= '40'){
                                                    $desconto_intervalo = converteHorasDecimal('00:'.(60-$inicial_hora_intervalo[1])*3);
                                                    $valor_final = converteHorasDecimal('00:'.(($inicial_hora_funcionario[1]-40)*3));
                                                    $cont_atendentes_escala += (1 - ($valor_final)) - ($desconto_intervalo);
                                                }else{
                                                    $cont_atendentes_escala += 1;
                                                }
                                               
                                            }else{
                                                $valor_final = converteHorasDecimal('00:'.(($inicial_hora_funcionario[1]-20)*3));
                                                $cont_atendentes_escala += 1 - ($valor_final);
                                            }

                                        }else if($final_hora_funcionario[0] == $hora_zero && $final_hora_funcionario[1] >= '40'){
                                            if($inicial_hora_intervalo[0] == $hora_zero && $final_hora_intervalo[0] == $hora_zero){
                                                if($inicial_hora_intervalo[1] >= '40' && $final_hora_intervalo[1] >= '40'){
                                                    $valor_final = converteHorasDecimal('00:'.(($inicial_hora_funcionario[1]-40)*3));
                                                    $desconto_intervalo = converteHorasDecimal('00:'.(60-($final_hora_intervalo[1] - $inicial_hora_intervalo[1]))*3);
                                                    $cont_atendentes_escala += $desconto_intervalo - $valor_final;
                                                }else if($final_hora_intervalo[1] >= '40'){
                                                    $desconto_intervalo = converteHorasDecimal('00:'.(60-$final_hora_intervalo[1])*3);
                                                    $valor_final = converteHorasDecimal('00:'.(($final_hora_funcionario[1]-40)*3));
                                                    $cont_atendentes_escala += $valor_final - (1 -$desconto_intervalo);
                                                }else{
                                                    $cont_atendentes_escala += 1;
                                                }
                                            }else if($final_hora_intervalo[0] == $hora_zero){
                                                if($final_hora_intervalo[1] >= '40'){
                                                   
                                                    $desconto_intervalo = converteHorasDecimal('00:'.(60-$final_hora_intervalo[1])*3);
                                                    $valor_final = converteHorasDecimal('00:'.(($final_hora_funcionario[1]-40)*3));
                                                    $cont_atendentes_escala += $valor_final - (1 -$desconto_intervalo);
                                                }else{
                                                    $cont_atendentes_escala += 0;
                                                }
                                            }else{
                                                $valor_final = converteHorasDecimal('00:'.(($final_hora_funcionario[1]-40)*3));
                                                $cont_atendentes_escala += $valor_final;
                                            }

                                        }else{
                                            if($inicial_hora_intervalo[0] == $hora_zero && $final_hora_intervalo[0] == $hora_zero){
                                                if($inicial_hora_intervalo[1] >= '40' && $final_hora_intervalo[1] >= '40'){
                                                    $desconto_intervalo = converteHorasDecimal('00:'.(60-($final_hora_intervalo[1] - $inicial_hora_intervalo[1]))*3);
                                                    $cont_atendentes_escala += $desconto_intervalo;
                                                }else if($inicial_hora_intervalo[1] >= '40'){
                                                    $desconto_intervalo = converteHorasDecimal('00:'.(60-$inicial_hora_intervalo[1])*3);
                                                    $cont_atendentes_escala += 1 - $desconto_intervalo;
                                                }else if($final_hora_intervalo[1] >= '40'){
                                                    $desconto_intervalo = converteHorasDecimal('00:'.(60-$final_hora_intervalo[1])*3);
                                                    $cont_atendentes_escala += $desconto_intervalo;
                                                }else{
                                                    $cont_atendentes_escala += 1;
                                                }  
                                            }else if($inicial_hora_intervalo[0] == $hora_zero){
                                                if($inicial_hora_intervalo[1] >= '40'){
                                                    $desconto_intervalo = converteHorasDecimal('00:'.(60-$inicial_hora_intervalo[1])*3);
                                                    $cont_atendentes_escala += 1 - $desconto_intervalo;
                                                }else{
                                                    $cont_atendentes_escala += 1;
                                                }
                                            }else if($final_hora_intervalo[0] == $hora_zero){
                                                if($final_hora_intervalo[1] >= '40'){
                                                    $desconto_intervalo = converteHorasDecimal('00:'.(60-$final_hora_intervalo[1])*3);
                                                    $cont_atendentes_escala += $desconto_intervalo;
                                                 }else{
                                                    $cont_atendentes_escala += 1;
                                                }
                                            }else if(($inicial_hora_intervalo[0] < $hora_zero && $final_hora_intervalo[0] > $hora_zero) ){
                                                $cont_atendentes_escala += 0;
                                            }else{
                                                $cont_atendentes_escala += 1;
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }                                       
                }

                if($dados_normais_especiais){
                    foreach ($dados_normais_especiais as $conteudo_normais_especiais) {
                        $inicial_hora_funcionario = explode(":", $conteudo_normais_especiais['inicial_especial']);
                        $final_hora_funcionario = explode(":", $conteudo_normais_especiais['final_especial']);
                        
                        if(($inicial_hora_funcionario[0] <= $hora_zero) && ($final_hora_funcionario[0] >= $hora_zero)){

                            if(($turno == 1 && $conteudo_normais_especiais['carga_horaria'] == 'integral') || ($turno == 2 && $conteudo_normais_especiais['carga_horaria'] == 'meio') || ($turno == 3 && $conteudo_normais_especiais['carga_horaria'] == 'jovem') || ($turno == 4 && $conteudo_normais_especiais['carga_horaria'] == 'estagio') || (!$turno) ){

                                if(!$conteudo_normais_especiais['tempo_intervalo']){
                                    if($inicial_hora_funcionario[0] == $hora_zero && $inicial_hora_funcionario[1] != '00' && $inicial_hora_funcionario[1] >= '40'){
                                        $valor_final = converteHorasDecimal('00:'.(($inicial_hora_funcionario[1]-40)*3));
                                        $cont_atendentes_escala += 1-($valor_final);
                                    }else if($final_hora_funcionario[0] == $hora_zero && $final_hora_funcionario[1] != '00' && $final_hora_funcionario[1] >= '40'){
                                        $valor_final = converteHorasDecimal('00:'.(($final_hora_funcionario[1]-40)*3));
                                        $cont_atendentes_escala += $valor_final;
                                    }else{
                                        $cont_atendentes_escala += 1;
                                    }
                                }else{
                                    $data_inicio_compara = date('Y-m-d H:i:s', strtotime("+0 minutes",strtotime($conteudo_normais_especiais['inicial_especial'])));
                                    $data_fim_compara = date('Y-m-d H:i:s', strtotime("+0 minutes",strtotime($conteudo_normais_especiais['final_especial'])));
                                
                                    if($data_inicio_compara > $data_fim_compara){
                                        $data_inicio_compara = date('Y-m-d H:i:s', strtotime("-1 days",strtotime($conteudo_normais_especiais['inicial_especial'])));
                                    }
                                
                                    $diferenca_datas = strtotime($data_fim_compara) - strtotime($data_inicio_compara);
                                    
                                    $tempo_total_trabalho = converteSegundosHoras($diferenca_datas);
                                    $tempo_total_trabalho = converteHorasDecimal($tempo_total_trabalho);
                                    $tempo_total_intervalo = converteHorasDecimal($conteudo_normais_especiais['tempo_intervalo']);
                                    $tempo_desconto = $tempo_total_intervalo/$tempo_total_trabalho;
                                
                                    if($inicial_hora_funcionario[0] == $hora_zero && $inicial_hora_funcionario[1] != '00' && $inicial_hora_funcionario[1] >= '40'){
                                        $valor_final = converteHorasDecimal('00:'.(($inicial_hora_funcionario[1]-40)*3));
                                        $cont_atendentes_escala += (1 - ($valor_final)) - ($tempo_desconto * (1 -  ($valor_final)));
                                    
                                    }else if($final_hora_funcionario[0] == $hora_zero && $final_hora_funcionario[1] != '00' && $final_hora_funcionario[1] >= '40'){
                                        $valor_final = converteHorasDecimal('00:'.(($final_hora_funcionario[1]-40)*3));
                                        $cont_atendentes_escala += (($valor_final) - ($tempo_desconto * $valor_final));
                                    
                                    }else{
                                        $cont_atendentes_escala += 1 - $tempo_desconto;
                                    }
                                }
                            }
                        }
                    }                                       
                }

                //INVERTIDOS CONSULTA
                    $dados_invertidos = DBRead('', 'tb_horarios_escala', "WHERE data_inicial = '".$consulta_data."' ".$consulta_invertidos." AND atendente = 1 AND id_usuario NOT IN (SELECT id_usuario FROM tb_ferias WHERE id_usuario = id_usuario AND data_de <= '".$data."' AND data_ate >= '".$data."') AND id_usuario NOT IN (SELECT a.id_usuario FROM tb_horarios_escala a INNER JOIN tb_horarios_especiais b ON a.id_horarios_escala = b.id_horarios_escala WHERE b.dia = '".$data."')");

                    $dados_invertidos_especiais = DBRead('', 'tb_horarios_escala a', "INNER JOIN tb_horarios_especiais b ON a.id_horarios_escala = b.id_horarios_escala WHERE b.dia = '".$data."' AND b.inicial_especial < '".$hora_proxima.":00' AND b.inicial_especial > b.final_especial ");

                //INVERTIDOS
                if($dados_invertidos){
                    
                    foreach ($dados_invertidos as $conteudo_invertido) {
                        $inicial_hora_funcionario = explode(":", $conteudo_invertido[$consulta_inicial_banco]);

                        if($inicial_hora_funcionario[0] <= $hora_zero){

                            if(($turno == 1 && $conteudo_invertido['carga_horaria'] == 'integral') || ($turno == 2 && $conteudo_invertido['carga_horaria'] == 'meio') || ($turno == 3 && $conteudo_invertido['carga_horaria'] == 'jovem') || ($turno == 4 && $conteudo_invertido['carga_horaria'] == 'estagio') || (!$turno) ){

                                $dados_intervalo_invertidos = DBRead('', 'tb_horarios_escala_intervalo', "WHERE id_horarios_escala = '".$conteudo_invertido['id_horarios_escala']."' AND dia = '".$dia_intervalo."' ");
                                if(!$dados_intervalo_invertidos){
                                    if($inicial_hora_funcionario[0] == $hora_zero && $inicial_hora_funcionario[1] >= '40'){

                                        $valor_final = converteHorasDecimal('00:'.(($inicial_hora_funcionario[1]-40)*3));
                                        $cont_atendentes_escala += 1-($valor_final);
                                    }else{
                                        $cont_atendentes_escala += 1;
                                    }
                                }else{
                                    if($dados_intervalo_invertidos[0]['tipo_intervalo'] == 1){
                                        $data_inicio_compara = date('Y-m-d H:i:s', strtotime("+0 minutes",strtotime($conteudo_invertido[$consulta_inicial_banco])));
                                        $data_fim_compara = date('Y-m-d H:i:s', strtotime("+0 minutes",strtotime($conteudo_invertido[$consulta_final_banco])));

                                        if($data_inicio_compara > $data_fim_compara){
                                            $data_inicio_compara = date('Y-m-d H:i:s', strtotime("-1 days",strtotime($conteudo_invertido[$consulta_inicial_banco])));
                                        }
                                        
                                        $diferenca_datas = strtotime($data_fim_compara) - strtotime($data_inicio_compara);
                                        $tempo_total_trabalho = converteSegundosHoras($diferenca_datas);
                                        $tempo_total_trabalho = converteHorasDecimal($tempo_total_trabalho);
                                        $tempo_total_intervalo = converteHorasDecimal($dados_intervalo_invertidos[0]['tempo_intervalo']);
                                        $tempo_desconto = $tempo_total_intervalo/$tempo_total_trabalho;

                                        if($inicial_hora_funcionario[0] == $hora_zero && $inicial_hora_funcionario[1] >= '40'){
                                            $valor_final = converteHorasDecimal('00:'.(($inicial_hora_funcionario[1]-40)*3));
                                            $cont_atendentes_escala += (1 - ($valor_final)) - ($tempo_desconto * (1 -  ($valor_final)));
                                        }else{
                                            $cont_atendentes_escala += 1 - $tempo_desconto;
                                        } 
                                    }else{

                                        $inicial_hora_intervalo = explode(":", $dados_intervalo_invertidos[0]['intervalo_inicial']);
                                        $final_hora_intervalo = explode(":", $dados_intervalo_invertidos[0]['intervalo_final']);
                                    
                                        if($inicial_hora_funcionario[0] == $hora_zero && $inicial_hora_funcionario[1] != '00' && $inicial_hora_funcionario[1] >= '40'){
                                            if($inicial_hora_intervalo[0] == $hora_zero && $final_hora_intervalo[0] == $hora_zero){
                                                if($inicial_hora_intervalo[1] >= '40' && $final_hora_intervalo[1] >= '40'){
                                                    $valor_final = converteHorasDecimal('00:'.(($inicial_hora_funcionario[1]-40)*3));
                                                    $desconto_intervalo = converteHorasDecimal('00:'.(60-($final_hora_intervalo[1] - $inicial_hora_intervalo[1]))*3);
                                                    $cont_atendentes_escala += $desconto_intervalo - $valor_final;
                                                }else if($inicial_hora_intervalo[1] >= '40'){
                                                    $desconto_intervalo = converteHorasDecimal('00:'.(60-$inicial_hora_intervalo[1])*3);
                                                    $valor_final = converteHorasDecimal('00:'.(($inicial_hora_funcionario[1]-40)*3));
                                                    $cont_atendentes_escala += (1 - ($valor_final)) - ($desconto_intervalo);
                                                }else{
                                                    $cont_atendentes_escala += 1;
                                                }  
                                                
                                            }else if($inicial_hora_intervalo[0] == $hora_zero){
                                                if($inicial_hora_intervalo[1] >= '40'){
                                                    $desconto_intervalo = converteHorasDecimal('00:'.(60-$inicial_hora_intervalo[1])*3);
                                                    $valor_final = converteHorasDecimal('00:'.(($inicial_hora_funcionario[1]-40)*3));
                                                    $cont_atendentes_escala += (1 - ($valor_final)) - ($desconto_intervalo);
                                                }else{
                                                    $cont_atendentes_escala += 1;
                                                }
                                               
                                            }else{
                                                $valor_final = converteHorasDecimal('00:'.(($inicial_hora_funcionario[1]-20)*3));
                                                $cont_atendentes_escala += 1 - ($valor_final);
                                            }
                                    
                                        }else{
                                            if($inicial_hora_intervalo[0] == $hora_zero && $final_hora_intervalo[0] == $hora_zero){
                                                if($inicial_hora_intervalo[1] >= '40' && $final_hora_intervalo[1] >= '40'){
                                                    $desconto_intervalo = converteHorasDecimal('00:'.(60-($final_hora_intervalo[1] - $inicial_hora_intervalo[1]))*3);
                                                    $cont_atendentes_escala += $desconto_intervalo;
                                                }else if($inicial_hora_intervalo[1] >= '40'){
                                                    $desconto_intervalo = converteHorasDecimal('00:'.(60-$inicial_hora_intervalo[1])*3);
                                                    $cont_atendentes_escala += 1 - $desconto_intervalo;
                                                }else if($final_hora_intervalo[1] >= '40'){
                                                    $desconto_intervalo = converteHorasDecimal('00:'.(60-$final_hora_intervalo[1])*3);
                                                    $cont_atendentes_escala += $desconto_intervalo;
                                                }else{
                                                    $cont_atendentes_escala += 1;
                                                }  
                                            }else if($inicial_hora_intervalo[0] == $hora_zero){
                                                if($inicial_hora_intervalo[1] >= '40'){
                                                    $desconto_intervalo = converteHorasDecimal('00:'.(60-$inicial_hora_intervalo[1])*3);
                                                    $cont_atendentes_escala += 1 - $desconto_intervalo;
                                                }else{
                                                    $cont_atendentes_escala += 1;
                                                }
                                            }else if($final_hora_intervalo[0] == $hora_zero){
                                                if($final_hora_intervalo[1] >= '40'){
                                                    $desconto_intervalo = converteHorasDecimal('00:'.(60-$final_hora_intervalo[1])*3);
                                                    $cont_atendentes_escala += $desconto_intervalo;
                                                 }else{
                                                    $cont_atendentes_escala += 1;
                                                }
                                            }else if(($inicial_hora_intervalo[0] < $hora_zero && $final_hora_intervalo[0] > $hora_zero) ){
                                                $cont_atendentes_escala += 0;
                                            }else{
                                                $cont_atendentes_escala += 1;
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }

                if($dados_invertidos_especiais){
                    
                    foreach ($dados_invertidos_especiais as $conteudo_invertidos_especiais) {
                        $inicial_hora_funcionario = explode(":", $conteudo_invertidos_especiais['inicial_especial']);

                        if($inicial_hora_funcionario[0] <= $hora_zero){

                            if(($turno == 1 && $conteudo_invertidos_especiais['carga_horaria'] == 'integral') || ($turno == 2 && $conteudo_invertidos_especiais['carga_horaria'] == 'meio') || ($turno == 3 && $conteudo_invertidos_especiais['carga_horaria'] == 'jovem') || ($turno == 4 && $conteudo_invertidos_especiais['carga_horaria'] == 'estagio') || (!$turno) ){
                                
                                if(!$conteudo_invertidos_especiais['tempo_intervalo']){
                                    if($inicial_hora_funcionario[0] == $hora_zero && $inicial_hora_funcionario[1] != '00' && $inicial_hora_funcionario[1] >= '40'){
                                        $valor_final = converteHorasDecimal('00:'.(($inicial_hora_funcionario[1]-40)*3));
                                        $cont_atendentes_escala += 1-($valor_final);
                                    }else{
                                        $cont_atendentes_escala += 1;
                                    }
                                }else{
                                    $data_inicio_compara = date('Y-m-d H:i:s', strtotime("+0 minutes",strtotime($conteudo_invertidos_especiais['inicial_especial'])));
                                    $data_fim_compara = date('Y-m-d H:i:s', strtotime("+0 minutes",strtotime($conteudo_invertidos_especiais['final_especial'])));
                                
                                    if($data_inicio_compara > $data_fim_compara){
                                        $data_inicio_compara = date('Y-m-d H:i:s', strtotime("-1 days",strtotime($conteudo_invertidos_especiais['inicial_especial'])));
                                    }
                                
                                    $diferenca_datas = strtotime($data_fim_compara) - strtotime($data_inicio_compara);
                                    $tempo_total_trabalho = converteSegundosHoras($diferenca_datas);
                                    $tempo_total_trabalho = converteHorasDecimal($tempo_total_trabalho);
                                    $tempo_total_intervalo = converteHorasDecimal($conteudo_invertidos_especiais['tempo_intervalo']);
                                    $tempo_desconto = $tempo_total_intervalo/$tempo_total_trabalho;
                                
                                    if($inicial_hora_funcionario[0] == $hora_zero && $inicial_hora_funcionario[1] != '00' && $inicial_hora_funcionario[1] >= '40'){
                                        $valor_final = converteHorasDecimal('00:'.(($inicial_hora_funcionario[1]-40)*3));
                                        $cont_atendentes_escala += (1 - ($valor_final)) - ($tempo_desconto * (1 -  ($valor_final)));
                                    
                                    }else{
                                        $cont_atendentes_escala += 1 - $tempo_desconto;
                                    }
                                }
                            }
                        }
                    }
                }

                if($data == $consulta_data){
                    $consulta_data = $consulta_data_ontem;
                }

                //INVERTIDOS ONTEM CONSULTA

                    $dados_invertidos_ontem = DBRead('', 'tb_horarios_escala', "WHERE data_inicial = '".$consulta_data."' ".$consulta_ontem." AND atendente = 1 AND id_usuario NOT IN (SELECT id_usuario FROM tb_ferias WHERE id_usuario = id_usuario AND data_de <= '".$data_ontem."' AND data_ate >= '".$data_ontem."') AND id_usuario NOT IN (SELECT a.id_usuario FROM tb_horarios_escala a INNER JOIN tb_horarios_especiais b ON a.id_horarios_escala = b.id_horarios_escala WHERE b.dia = '".$data_ontem."')");
                
                    $dados_invertidos_ontem_especiais =  DBRead('', 'tb_horarios_escala a', "INNER JOIN tb_horarios_especiais b ON a.id_horarios_escala = b.id_horarios_escala WHERE b.dia = '".$data_ontem."' AND b.final_especial >= '".$hora_zero.":40' AND b.inicial_especial > b.final_especial ");
               
               //INVERTIDOS ONTEM
                if($dados_invertidos_ontem){
                    foreach ($dados_invertidos_ontem as $conteudo_ontem) {
                        $final_hora_funcionario = explode(":", $conteudo_ontem[$consulta_final_banco_ontem]);

                        if($final_hora_funcionario[0] >= $hora_zero){

                            if(($turno == 1 && $conteudo_ontem['carga_horaria'] == 'integral') || ($turno == 2 && $conteudo_ontem['carga_horaria'] == 'meio') || ($turno == 3 && $conteudo_ontem['carga_horaria'] == 'jovem') || ($turno == 4 && $conteudo_ontem['carga_horaria'] == 'estagio') || (!$turno) ){

                                $dados_intervalo_invertidos_ontem = DBRead('', 'tb_horarios_escala_intervalo', "WHERE id_horarios_escala = '".$conteudo_ontem['id_horarios_escala']."' AND dia = '".$dia_intervalo_ontem."' ");

                                if(!$dados_intervalo_invertidos_ontem){
                                    if($final_hora_funcionario[0] == $hora_zero && $final_hora_funcionario[1] >= '40'){

                                        $valor_final = converteHorasDecimal('00:'.(($final_hora_funcionario[1]-40)*3));
                                        $cont_atendentes_escala += $valor_final;
                                    }else{
                                        $cont_atendentes_escala += 1;
                                    }
                                }else{
                                    if($dados_intervalo_invertidos[0]['tipo_intervalo'] == 1){
                                        $data_inicio_compara = date('Y-m-d H:i:s', strtotime("+0 minutes",strtotime($conteudo_ontem[$consulta_inicial_banco_ontem])));
                                        $data_fim_compara = date('Y-m-d H:i:s', strtotime("+0 minutes",strtotime($conteudo_ontem[$consulta_final_banco_ontem])));

                                        if($data_inicio_compara > $data_fim_compara){
                                            $data_inicio_compara = date('Y-m-d H:i:s', strtotime("-1 days",strtotime($conteudo_ontem[$consulta_inicial_banco_ontem])));
                                        }
                                        
                                        $diferenca_datas = strtotime($data_fim_compara) - strtotime($data_inicio_compara);
                                        $tempo_total_trabalho = converteSegundosHoras($diferenca_datas);
                                        $tempo_total_trabalho = converteHorasDecimal($tempo_total_trabalho);
                                        $tempo_total_intervalo = converteHorasDecimal($dados_intervalo_invertidos_ontem[0]['tempo_intervalo']);
                                        $tempo_desconto = $tempo_total_intervalo/$tempo_total_trabalho;

                                        if($final_hora_funcionario[0] == $hora_zero && $final_hora_funcionario[1] >= '40'){
                                            $valor_final = converteHorasDecimal('00:'.(($final_hora_funcionario[1]-40)*3));
                                            $cont_atendentes_escala += (($valor_final) - ($tempo_desconto * $valor_final));
                                        }else{
                                            $cont_atendentes_escala += 1 - $tempo_desconto;
                                        }
                                    }else{

                                        $inicial_hora_intervalo = explode(":", $dados_intervalo_normais[0]['intervalo_inicial']);
                                        $final_hora_intervalo = explode(":", $dados_intervalo_normais[0]['intervalo_final']);
                                    
                                        if($final_hora_funcionario[0] == $hora_zero && $final_hora_funcionario[1] >= '40'){
                                            if($inicial_hora_intervalo[0] == $hora_zero && $final_hora_intervalo[0] == $hora_zero){
                                                if($inicial_hora_intervalo[1] >= '40' && $final_hora_intervalo[1] >= '40'){
                                                    $valor_final = converteHorasDecimal('00:'.(($inicial_hora_funcionario[1]-40)*3));
                                                    $desconto_intervalo = converteHorasDecimal('00:'.(60-($final_hora_intervalo[1] - $inicial_hora_intervalo[1]))*3);
                                                    $cont_atendentes_escala += $desconto_intervalo - $valor_final;
                                                }else if($final_hora_intervalo[1] >= '40'){
                                                    $desconto_intervalo = converteHorasDecimal('00:'.(60-$final_hora_intervalo[1])*3);
                                                    $valor_final = converteHorasDecimal('00:'.(($final_hora_funcionario[1]-40)*3));
                                                    $cont_atendentes_escala += $valor_final - (1 -$desconto_intervalo);
                                                }else{
                                                    $cont_atendentes_escala += 1;
                                                }
                                            }else if($final_hora_intervalo[0] == $hora_zero){
                                                if($final_hora_intervalo[1] >= '40'){
                                                   
                                                    $desconto_intervalo = converteHorasDecimal('00:'.(60-$final_hora_intervalo[1])*3);
                                                    $valor_final = converteHorasDecimal('00:'.(($final_hora_funcionario[1]-40)*3));
                                                    $cont_atendentes_escala += $valor_final - (1 -$desconto_intervalo);
                                                }else{
                                                    $cont_atendentes_escala += 0;
                                                }
                                            }else{
                                                $valor_final = converteHorasDecimal('00:'.(($final_hora_funcionario[1]-40)*3));
                                                $cont_atendentes_escala += $valor_final;
                                            }
                                    
                                        }else{
                                            if($inicial_hora_intervalo[0] == $hora_zero && $final_hora_intervalo[0] == $hora_zero){
                                                if($inicial_hora_intervalo[1] >= '40' && $final_hora_intervalo[1] >= '40'){
                                                    $desconto_intervalo = converteHorasDecimal('00:'.(60-($final_hora_intervalo[1] - $inicial_hora_intervalo[1]))*3);
                                                    $cont_atendentes_escala += $desconto_intervalo;
                                                }else if($inicial_hora_intervalo[1] >= '40'){
                                                    $desconto_intervalo = converteHorasDecimal('00:'.(60-$inicial_hora_intervalo[1])*3);
                                                    $cont_atendentes_escala += 1 - $desconto_intervalo;
                                                }else if($final_hora_intervalo[1] >= '40'){
                                                    $desconto_intervalo = converteHorasDecimal('00:'.(60-$final_hora_intervalo[1])*3);
                                                    $cont_atendentes_escala += $desconto_intervalo;
                                                }else{
                                                    $cont_atendentes_escala += 1;
                                                }  
                                            }else if($inicial_hora_intervalo[0] == $hora_zero){
                                                if($inicial_hora_intervalo[1] >= '40'){
                                                    $desconto_intervalo = converteHorasDecimal('00:'.(60-$inicial_hora_intervalo[1])*3);
                                                    $cont_atendentes_escala += 1 - $desconto_intervalo;
                                                }else{
                                                    $cont_atendentes_escala += 1;
                                                }
                                            }else if($final_hora_intervalo[0] == $hora_zero){
                                                if($final_hora_intervalo[1] >= '40'){
                                                    $desconto_intervalo = converteHorasDecimal('00:'.(60-$final_hora_intervalo[1])*3);
                                                    $cont_atendentes_escala += $desconto_intervalo;
                                                 }else{
                                                    $cont_atendentes_escala += 1;
                                                }
                                            }else if(($inicial_hora_intervalo[0] < $hora_zero && $final_hora_intervalo[0] > $hora_zero) ){
                                                $cont_atendentes_escala += 0;
                                            }else{
                                                $cont_atendentes_escala += 1;
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }

                if($dados_invertidos_ontem_especiais){
                    foreach ($dados_invertidos_ontem_especiais as $conteudo_invertidos_ontem_especiais) {
                        $final_hora_funcionario = explode(":", $conteudo_invertidos_ontem_especiais['final_especial']);
                        if($final_hora_funcionario[0] >= $hora_zero){

                            if(($turno == 1 && $conteudo_invertidos_ontem_especiais['carga_horaria'] == 'integral') || ($turno == 2 && $conteudo_invertidos_ontem_especiais['carga_horaria'] == 'meio') || ($turno == 3 && $conteudo_invertidos_ontem_especiais['carga_horaria'] == 'jovem') || ($turno == 4 && $conteudo_invertidos_ontem_especiais['carga_horaria'] == 'estagio') || (!$turno) ){
                                
                                if(!$conteudo_invertidos_ontem_especiais['tempo_intervalo']){
                                    if($final_hora_funcionario[0] == $hora_zero && $final_hora_funcionario[1] != '00' && $final_hora_funcionario[1] >= '40'){
                                        $valor_final = converteHorasDecimal('00:'.(($final_hora_funcionario[1]-40)*3));
                                        $cont_atendentes_escala += $valor_final;
                                    }else{
                                        $cont_atendentes_escala += 1;
                                    }
                                }else{
                                    $data_inicio_compara = date('Y-m-d H:i:s', strtotime("+0 minutes",strtotime($conteudo_invertidos_ontem_especiais['inicial_especial'])));
                                    $data_fim_compara = date('Y-m-d H:i:s', strtotime("+0 minutes",strtotime($conteudo_invertidos_ontem_especiais['final_especial'])));
                                
                                    if($data_inicio_compara > $data_fim_compara){
                                        $data_inicio_compara = date('Y-m-d H:i:s', strtotime("-1 days",strtotime($conteudo_invertidos_ontem_especiais['inicial_especial'])));
                                    }
                                
                                    $diferenca_datas = strtotime($data_fim_compara) - strtotime($data_inicio_compara);
                                    $tempo_total_trabalho = converteSegundosHoras($diferenca_datas);
                                    $tempo_total_trabalho = converteHorasDecimal($tempo_total_trabalho);
                                    $tempo_total_intervalo = converteHorasDecimal($conteudo_invertidos_ontem_especiais['tempo_intervalo']);
                                    $tempo_desconto = $tempo_total_intervalo/$tempo_total_trabalho;
                                
                                    if($final_hora_funcionario[0] == $hora_zero && $final_hora_funcionario[1] != '00' && $final_hora_funcionario[1] >= '40'){
                                        $valor_final = converteHorasDecimal('00:'.(($final_hora_funcionario[1]-40)*3));
                                        $cont_atendentes_escala += (($valor_final) - ($tempo_desconto * $valor_final));
                                    
                                    }else{
                                        $cont_atendentes_escala += 1 - $tempo_desconto;
                                    }
                                }
                            }
                        }
                    }
                }

                if(!$cont_atendentes_escala){
                    $cont_atendentes_escala = 0;
                }  
                
            //fim codigo atendentes escala 40

            $atendentes_escala[$hora_array+2] = sprintf("%01.2f", round($cont_atendentes_escala, 2));

            $horas[$hora_array] = $hora_zero.':00';
            $horas[$hora_array+1] = $hora_zero.':20';
            $horas[$hora_array+2] = $hora_zero.':40';
            $hora++;
            $hora_array+=3;
        }
        ?>
        <div id="<?php echo "chart-" . $chart; ?>" class="oi"></div> 
        <script>
            $(function () {
                // Create the first chart
                $('#<?php echo "chart-" . $chart; ?>').highcharts({
                    chart: {
                        type: '<?=$nome_tipo_grafico;?>'
                    },
                    title: {
                        text: 'Dia: <?php echo $dias_semana[$numero_dia_semana].', '.converteData($data); ?>' // Title for the chart
                    },
                    xAxis: {
                        categories: <?php echo json_encode($horas) ?>
                        // Categories for the charts
                    },
                    yAxis: {
                        min: 0,
                        title: {
                            text: 'Atendentes por Hora'
                        },
                        stackLabels: {
                            enabled: true,
                            style: {
                                fontWeight: 'bold',
                                color: (Highcharts.theme && Highcharts.theme.textColor) || 'black'
                            }
                        }
                    },
                    legend: {
                        enabled:true,
                        backgroundColor: (Highcharts.theme && Highcharts.theme.background2) || 'white',
                        borderColor: '#CCC',
                        borderWidth: 1,
                        shadow: false
                    },
                    tooltip: {
                        headerFormat: '<b>{point.x}</b><br/>',
                        pointFormat: '{series.name}: {point.y}'
                    },
                    plotOptions: {
                        <?php 
                        if($tipo_grafico == 3){//se for pilha
                            echo "
                            column: {
                                stacking: 'normal',
                                dataLabels: {
                                    enabled: true,
                                    color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white'
                                }
                            }
                            ";
                        }else{
                            echo "                                  
                            series: {
                                dataLabels: {
                                    enabled: true
                                }
                            }
                            ";
                        }
                        ?>
                    },
                    series: [
                        {
                            name: 'Atendentes logados na central', // Name of your series
                            data: <?php echo json_encode($atendentes_central, JSON_NUMERIC_CHECK); ?> // The data in your series

                        },
                        {
                            name: 'Atendentes na escala', // Name of your series
                            data: <?php echo json_encode($atendentes_escala, JSON_NUMERIC_CHECK); ?> // The data in your series

                        }
                    ],
                    navigation: {
                        buttonOptions: {
                            enabled: true
                        }
                    }
                });
            });
        </script>   
        <?php
        echo '<hr>';
        $chart++;
    }
}

function relatorio_tendencia($data_de, $data_ate, $data_de_amostra, $data_ate_amostra, $aproveitamento, $segundos_espera_perdida, $ponderado){
    
    if($ponderado){
        $legenda_ponderado = '(Ponderado)';
    }
    $nome_tipo_grafico = 'line';

    $fila = "'callATENDIMENTOvip','callATENDIMENTOalta','callATENDIMENTOnormal','callATENDIMENTObaixa','callATENDIMENTOnotificacaoparada', 'callATENDIMENTOvipEXT','callATENDIMENTOaltaEXT','callATENDIMENTOnormalEXT','callATENDIMENTObaixaEXT','callATENDIMENTOnotificacaoparadaEXT','callATENDIMENTOvipEXP','callATENDIMENTOaltaEXP','callATENDIMENTOnormalEXP','callATENDIMENTObaixaEXP','callATENDIMENTOnotificacaoparadaEXP'";

    if($aproveitamento){
        if($aproveitamento > 99){
            $legenda_aproveitamento = "100%";
            $aproveitamento = '1';      
        }else{
            $legenda_aproveitamento = $aproveitamento."%";
            $aproveitamento = '0.'.$aproveitamento;
        }

    }else{
        $legenda_aproveitamento = '100%';
        $aproveitamento = '1';
    }

    if($segundos_espera_perdida){
        $legenda_tempo_abaixo = $segundos_espera_perdida." segundos";
    }else{
        $legenda_tempo_abaixo = '0 segundos';
    }

    $nome_tipo_grafico = 'line';
    $filtro_perdidas = '';
    
    if($segundos_espera_perdida){
        $filtro_perdidas .= " AND b.data3 >= $segundos_espera_perdida";
    }

    $data_hora = converteDataHora(getDataHora());

    $gerado = "<span style=\"font-size: 14px;\"><strong>Gerado em: </strong> $data_hora</span>";

    echo "<div class=\"col-md-12\" style=\"padding: 0\">";
    echo "<legend style=\"text-align:center;\"><strong>Gráfico de Capacidade por Hora ".$legenda_ponderado."</strong><br>$gerado</legend>";
    echo "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong>Data Inicial da Escala - </strong>".$data_de.", <strong>Data Final da Escala - </strong>".$data_ate.", <strong>Data Inicial de Amostra de Chamadas - </strong>".$data_de_amostra.", <strong>Data Final de Amostra de Chamadas - </strong>".$data_ate_amostra.", <strong> Descartar tempo de atendimento abaixo de - </strong> ".$legenda_tempo_abaixo.", <strong>Aproveitamento - </strong>".$legenda_aproveitamento."</legend>";
    
    $dias_semana = array('Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado');
    $horas = array('0','1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17','18','19','20','21','22','23');
    $tma = array();
    $qtd_total_atendidas = array();
    $data_hora = converteDataHora(getDataHora());
    $total_entradas = array();
    $tendencia = array();
    $previsao = array();

    $contador_dias_semana = array();

    $tma_dia = array();
    $qtd_total_atendidas_dia = array();
    $total_entradas_dia = array();

    $entradas_dia = array();

    $auxiliar_menor_dia = array();
    $auxiliar_maior_dia = array();

    foreach (rangeDatas(converteData($data_de_amostra), converteData($data_ate_amostra)) as $data) {        
        $numero_dia_semana = date('w', strtotime($data));
        $hora = 0;

        $contador_dias_semana[$numero_dia_semana]++;
        while ($hora < 24) {
            $hora_zero = sprintf('%02d', $hora);
            $qtd_atendidas = 0;
            $qtd_perdidas = 0;
            $soma_ta_atendidas = 0;
        
            $dados_atendidas_grafico = DBRead('snep','queue_log a',"INNER JOIN queue_log b ON (b.callid = a.callid AND b.event = 'CONNECT') INNER JOIN queue_log c ON (c.callid = a.callid AND (c.event = 'COMPLETEAGENT' OR c.event = 'COMPLETECALLER' OR c.event = 'BLINDTRANSFER' OR c.event = 'ATTENDEDTRANSFER')) WHERE a.event = 'ENTERQUEUE' AND b.time BETWEEN '$data $hora_zero:00:00' AND '$data $hora_zero:59:59' GROUP BY b.id ORDER BY b.id", "a.queuename as 'enterqueue_queuename', b.id, c.event AS 'finalizacao_event', c.data1 AS 'finalizacao_data1', c.data2 AS 'finalizacao_data2', c.data3 AS 'finalizacao_data3', c.data4 AS 'finalizacao_data4'");

            $atendidas_hora = 0;
            if($dados_atendidas_grafico){
                foreach ($dados_atendidas_grafico as $conteudo_atendidas) {
                    if(preg_match("/'".$conteudo_atendidas['enterqueue_queuename']."'/i", $fila) || !$fila){
                        if($conteudo_atendidas['finalizacao_event']=='COMPLETEAGENT' || $conteudo_atendidas['finalizacao_event']=='COMPLETECALLER'){
                            $soma_ta_atendidas +=  $conteudo_atendidas['finalizacao_data2'];
                        }elseif(($conteudo_atendidas['finalizacao_event']=='BLINDTRANSFER' || $conteudo_atendidas['finalizacao_event']=='ATTENDEDTRANSFER') && $conteudo_atendidas['finalizacao_data1'] != 'LINK'){
                            $soma_ta_atendidas +=  $conteudo_atendidas['finalizacao_data4'];
                        }
                        $qtd_atendidas++;
                        $atendidas_hora ++;
                    }
                }
            }   

            $dados_perdidas_grafico = DBRead('snep','queue_log a',"INNER JOIN queue_log b ON (b.callid = a.callid AND (b.event = 'ABANDON' OR b.event = 'EXITWITHTIMEOUT')) WHERE a.event = 'ENTERQUEUE' AND a.time BETWEEN '$data $hora_zero:00:00' AND '$data $hora_zero:59:59' $filtro_perdidas GROUP BY b.id ORDER BY b.id","a.queuename as 'enterqueue_queuename', b.id");

            $perdidas_hora = 0;
            if($dados_perdidas_grafico){
                foreach ($dados_perdidas_grafico as $conteudo_perdidas) {
                    if(preg_match("/'".$conteudo_perdidas['enterqueue_queuename']."'/i", $fila) || !$fila){
                        $perdidas_hora ++;
                    }
                }
            }   

            $total_entradas[$numero_dia_semana][$hora] += $atendidas_hora + $perdidas_hora;
            $total_entradas_dia[$numero_dia_semana][$contador_dias_semana[$numero_dia_semana]][$hora] += $atendidas_hora + $perdidas_hora;
            $entradas_dia[$numero_dia_semana][$contador_dias_semana[$numero_dia_semana]] += $atendidas_hora + $perdidas_hora;
            $tma[$numero_dia_semana][$hora] += $soma_ta_atendidas;
            $qtd_total_atendidas[$numero_dia_semana][$hora] += $qtd_atendidas;
            $tma_dia[$numero_dia_semana][$contador_dias_semana[$numero_dia_semana]][$hora] += $soma_ta_atendidas;
            $qtd_total_atendidas_dia[$numero_dia_semana][$contador_dias_semana[$numero_dia_semana]][$hora] += $qtd_atendidas;
            $hora++;            
        }
    }

    foreach ($entradas_dia as $dia_semana => $dia) {
        foreach ($dia as $key => $value) {
            if(!$auxiliar_menor_dia[$dia_semana] || $dia[$auxiliar_menor_dia[$dia_semana]] > $value){
                $auxiliar_menor_dia[$dia_semana] = $key;
            }
            if(!$auxiliar_maior_dia[$dia_semana] || $dia[$auxiliar_maior_dia[$dia_semana]] < $value){
                $auxiliar_maior_dia[$dia_semana] = $key;
            }
        }
    }

    $cont_dia = 0;
    while($cont_dia < 7){

        $cont_hora = 0;
        while($cont_hora < 24){
            if(!$total_entradas[$cont_dia][$cont_hora]){
                $total_entradas[$cont_dia][$cont_hora] = 0;
            }
            if(!$total_entradas_dia[$cont_dia][$auxiliar_maior_dia[$cont_dia]][$cont_hora]){
                $total_entradas_dia[$cont_dia][$auxiliar_maior_dia[$cont_dia]][$cont_hora] = 0;
            }
            if(!$total_entradas_dia[$cont_dia][$auxiliar_menor_dia[$cont_dia]][$cont_hora]){
                $total_entradas_dia[$cont_dia][$auxiliar_menor_dia[$cont_dia]][$cont_hora] = 0;
            }

            if($ponderado){
                $tma[$cont_dia][$cont_hora] = sprintf("%01.2f", round(($tma[$cont_dia][$cont_hora]-$tma_dia[$cont_dia][$auxiliar_maior_dia[$cont_dia]][$cont_hora]-$tma_dia[$cont_dia][$auxiliar_menor_dia[$cont_dia]][$cont_hora])/($qtd_total_atendidas[$cont_dia][$cont_hora] == 0 ? 1 : $qtd_total_atendidas[$cont_dia][$cont_hora]-$qtd_total_atendidas_dia[$cont_dia][$auxiliar_maior_dia[$cont_dia]][$cont_hora]-$qtd_total_atendidas_dia[$cont_dia][$auxiliar_menor_dia[$cont_dia]][$cont_hora]), 2));
                if($tma[$cont_dia][$cont_hora] != 0){
                    $media_dia_hora = ($tma[$cont_dia][$cont_hora])/($contador_dias_semana[$cont_dia]-2);
                    $hora_por_tma = 3600/$media_dia_hora;
                    $aproveitamento_media_dia_hora = $hora_por_tma*$aproveitamento;
                    $tendencia[$cont_dia][$cont_hora] = ($total_entradas[$cont_dia][$cont_hora]-$total_entradas_dia[$cont_dia][$auxiliar_maior_dia[$cont_dia]][$cont_hora]-$total_entradas_dia[$cont_dia][$auxiliar_menor_dia[$cont_dia]][$cont_hora])/$aproveitamento_media_dia_hora;
                    $tendencia[$cont_dia][$cont_hora] = sprintf("%01.2f", $tendencia[$cont_dia][$cont_hora]);
                }else{
                    $tendencia[$cont_dia][$cont_hora] = 0;
                }
            }else{
                $tma[$cont_dia][$cont_hora] = sprintf("%01.2f", round(($tma[$cont_dia][$cont_hora])/($qtd_total_atendidas[$cont_dia][$cont_hora] == 0 ? 1 : $qtd_total_atendidas[$cont_dia][$cont_hora]), 2));
                if($tma[$cont_dia][$cont_hora] != 0){
                    $media_dia_hora = ($tma[$cont_dia][$cont_hora])/($contador_dias_semana[$cont_dia]);
                    $hora_por_tma = 3600/$media_dia_hora;
                    $aproveitamento_media_dia_hora = $hora_por_tma*$aproveitamento;
                    $tendencia[$cont_dia][$cont_hora] = ($total_entradas[$cont_dia][$cont_hora])/$aproveitamento_media_dia_hora;
                    $tendencia[$cont_dia][$cont_hora] = sprintf("%01.2f", $tendencia[$cont_dia][$cont_hora]);
                }else{
                    $tendencia[$cont_dia][$cont_hora] = 0;
                }
            }           
            $cont_hora++;
        }       
        $cont_dia++;
    }

    $dias_semana = array('Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado');
    $data_hora = converteDataHora(getDataHora());
    $chart = 0;
    $atendentes_escala = array();
    $contador_dias_semana_escala = array();

    foreach(rangeDatas(converteData($data_de), converteData($data_ate)) as $data){
        $atendentes_central = array();
        $horas = array();
        $numero_dia_semana = date('w', strtotime($data));
        $hora = 0;
        $consulta_data = explode("-", $data);
        $consulta_data = $consulta_data[0].'-'.$consulta_data[1].'-01';
        $data_ontem = date('Y-m-d', strtotime("-1 days",strtotime($consulta_data)));
        $consulta_data_ontem = explode("-", $data_ontem);
        $consulta_data_ontem = $consulta_data_ontem[0].'-'.$consulta_data_ontem[1].'-01';
        $data_ontem = date('Y-m-d', strtotime("-1 days",strtotime($data)));
        $dia_hoje = date('w', strtotime($data));
        $dia_ontem = date('w', strtotime($data_ontem));
        $dia_hoje = $dias_semana[$dia_hoje];
        $dia_ontem = $dias_semana[$dia_ontem];
        $numero_dia_semana = date('w', strtotime($data));
        $hora = 0;
        $contador_dias_semana_escala[$numero_dia_semana]++;
        $aux_consulta_data = $consulta_data;
        
        while ($hora < 24) {
            $hora_zero = sprintf('%02d', $hora);
            $hora_proxima = sprintf('%02d', $hora_zero+1);  

            $cont_atendentes_central = 0;
            $cont_atendentes_escala = 0;

            $consulta_data = $aux_consulta_data;

            //início codigo atendentes escala
                
                //VERIFICA O DIA DA CONSULTA PARA PESQUISAR (SEG A SEX, SÁBADO OU DOMINGO)
                if($dia_hoje == 'Domingo'){
                    $consulta_dia =  "AND inicial_dom < '".$hora_proxima.":00' AND final_dom > '".$hora_zero.":00'";
                    
                    $consulta_inicial_banco = 'inicial_dom';
                    $consulta_final_banco = 'final_dom';

                    $consulta_inicial_banco_ontem = 'inicial_sab';
                    $consulta_final_banco_ontem = 'final_sab';

                    $consulta_ontem = "AND final_sab > '".$hora_zero.":00' AND inicial_sab > final_sab AND folga_seg != '".$dia_ontem."'";
                    $consulta_invertidos = "AND inicial_dom < '".$hora_proxima.":00' AND inicial_dom > final_dom AND id_horarios_escala NOT IN (SELECT id_horarios_escala FROM tb_folgas_dom WHERE dia = '".$data."')";
                    $consulta_normais = "AND inicial_dom < final_dom AND id_horarios_escala NOT IN (SELECT id_horarios_escala FROM tb_folgas_dom WHERE dia = '".$data."')";

                    $dia_intervalo = 'dom';
                    $dia_intervalo_ontem = 'sab';

                }else if($dia_hoje == 'Sábado'){
                    $consulta_dia =  "AND inicial_sab < '".$hora_proxima.":00' AND final_sab > '".$hora_zero.":00'";
                    $consulta_inicial_banco = 'inicial_sab';
                    $consulta_final_banco = 'final_sab';
                    
                    $consulta_inicial_banco_ontem = 'inicial_seg';
                    $consulta_final_banco_ontem = 'final_seg';

                    $consulta_ontem = "AND final_seg > '".$hora_zero.":00' AND inicial_seg > final_seg AND folga_seg != '".$dia_ontem."'";
                    $consulta_invertidos = "AND inicial_sab < '".$hora_proxima.":00' AND inicial_sab > final_sab AND folga_seg != '".$dia_hoje."'";
                    $consulta_normais = "AND inicial_sab < final_sab AND folga_seg != '".$dia_hoje."'";
                   
                    $dia_intervalo = 'sab';
                    $dia_intervalo_ontem = 'seg';
                }else{
                    $consulta_dia =  "AND inicial_seg < '".$hora_proxima.":00' AND final_seg > '".$hora_zero.":00'";
                    $consulta_inicial_banco = 'inicial_seg';
                    $consulta_final_banco = 'final_seg';

                    
                    if($dia_hoje == 'Quinta' || $dia_hoje == 'Sexta'){
                        $consulta_invertidos = "AND inicial_seg < '".$hora_proxima.":00' AND inicial_seg > final_seg AND (folga_seg != '".$dia_hoje."' AND folga_seg != 'Quinta e Sexta')";
                        $consulta_normais = "AND inicial_seg < final_seg AND (folga_seg != '".$dia_hoje."' AND folga_seg != 'Quinta e Sexta')";
                    }else{
                        $consulta_invertidos = "AND inicial_seg < '".$hora_proxima.":00' AND inicial_seg > final_seg AND folga_seg != '".$dia_hoje."'";
                        $consulta_normais = "AND inicial_seg < final_seg AND folga_seg != '".$dia_hoje."'";
                    }

                    if($dia_hoje == 'Segunda'){
                        $consulta_ontem = "AND final_dom > '".$hora_zero.":00' AND inicial_dom > final_dom AND id_horarios_escala NOT IN (SELECT id_horarios_escala FROM tb_folgas_dom WHERE dia = '".$data_ontem."')";
                        
                        $consulta_inicial_banco_ontem = 'inicial_dom';
                        $consulta_final_banco_ontem = 'final_dom';

                        $dia_intervalo_ontem = 'dom';
                    }else{
                        if($dia_hoje == 'Sexta'){
                            $consulta_ontem = "AND final_seg > '".$hora_zero.":00' AND inicial_seg > final_seg AND (folga_seg != 'Quinta e Sexta' AND folga_seg != '".$dia_ontem."')";
                        }else{
                            $consulta_ontem = "AND final_seg > '".$hora_zero.":00' AND inicial_seg > final_seg AND folga_seg != '".$dia_ontem."'";
                        }
                    
                        $consulta_inicial_banco_ontem = 'inicial_seg';
                        $consulta_final_banco_ontem = 'final_seg';
                    
                        $dia_intervalo_ontem = 'seg';
                    }
                    $dia_intervalo = 'seg';
                }
                                
                //NORMAIS               
                $dados_normais = DBRead('', 'tb_horarios_escala', "WHERE data_inicial = '".$consulta_data."' ".$consulta_dia." ".$consulta_normais." AND atendente = 1 AND id_usuario NOT IN (SELECT id_usuario FROM tb_ferias WHERE id_usuario = id_usuario AND data_de <= '".$data."' AND data_ate >= '".$data."') AND id_usuario NOT IN (SELECT a.id_usuario FROM tb_horarios_escala a INNER JOIN tb_horarios_especiais b ON a.id_horarios_escala = b.id_horarios_escala WHERE b.dia = '".$data."')");

                $dados_normais_especiais = DBRead('', 'tb_horarios_escala a', "INNER JOIN tb_horarios_especiais b ON a.id_horarios_escala = b.id_horarios_escala WHERE b.dia = '".$data."' AND b.inicial_especial < '".$hora_proxima.":00' AND b.final_especial > '".$hora_zero.":00' ");              
                
                //INVERTIDOS
                $dados_invertidos = DBRead('', 'tb_horarios_escala', "WHERE data_inicial = '".$consulta_data."' ".$consulta_invertidos." AND atendente = 1 AND id_usuario NOT IN (SELECT id_usuario FROM tb_ferias WHERE id_usuario = id_usuario AND data_de <= '".$data."' AND data_ate >= '".$data."') AND id_usuario NOT IN (SELECT a.id_usuario FROM tb_horarios_escala a INNER JOIN tb_horarios_especiais b ON a.id_horarios_escala = b.id_horarios_escala WHERE b.dia = '".$data."')");

                $dados_invertidos_especiais = DBRead('', 'tb_horarios_escala a', "INNER JOIN tb_horarios_especiais b ON a.id_horarios_escala = b.id_horarios_escala WHERE b.dia = '".$data."' AND b.inicial_especial < '".$hora_proxima.":00' AND b.inicial_especial > b.final_especial ");

                if($data == $consulta_data){
                    $consulta_data = $consulta_data_ontem;
                }

                //INVERTIDOS ONTEM
                $dados_invertidos_ontem = DBRead('', 'tb_horarios_escala', "WHERE data_inicial = '".$consulta_data."' ".$consulta_ontem." AND atendente = 1 AND id_usuario NOT IN (SELECT id_usuario FROM tb_ferias WHERE id_usuario = id_usuario AND data_de <= '".$data_ontem."' AND data_ate >= '".$data_ontem."') AND id_usuario NOT IN (SELECT a.id_usuario FROM tb_horarios_escala a INNER JOIN tb_horarios_especiais b ON a.id_horarios_escala = b.id_horarios_escala WHERE b.dia = '".$data_ontem."')");
                
                $dados_invertidos_ontem_especiais =  DBRead('', 'tb_horarios_escala a', "INNER JOIN tb_horarios_especiais b ON a.id_horarios_escala = b.id_horarios_escala WHERE b.dia = '".$data_ontem."' AND b.final_especial >= '".$hora_zero.":00' AND b.inicial_especial > b.final_especial ");

                //NORMAIS
                if($dados_normais){
                    foreach ($dados_normais as $conteudo_normais) {
                        $inicial_hora_funcionario = explode(":", $conteudo_normais[$consulta_inicial_banco]);
                        $final_hora_funcionario = explode(":", $conteudo_normais[$consulta_final_banco]);
                        
                        if(($inicial_hora_funcionario[0] <= $hora_zero) && ($final_hora_funcionario[0] >= $hora_zero)){
                        
                            $dados_intervalo_normais = DBRead('', 'tb_horarios_escala_intervalo', "WHERE id_horarios_escala = '".$conteudo_normais['id_horarios_escala']."' AND dia = '".$dia_intervalo."' ");

                            if(!$dados_intervalo_normais){
                                if($inicial_hora_funcionario[0] == $hora_zero && $inicial_hora_funcionario[1] != '00'){
                                    $valor_final = converteHorasDecimal('00:'.$inicial_hora_funcionario[1]);
                                    $cont_atendentes_escala += 1-(sprintf("%01.2f", round($valor_final, 2)));
                                }else if($final_hora_funcionario[0] == $hora_zero && $final_hora_funcionario[1] != '00'){
                                    $valor_final = converteHorasDecimal('00:'.$final_hora_funcionario[1]);
                                    $cont_atendentes_escala += sprintf("%01.2f", round($valor_final, 2));
                                }else{
                                    $cont_atendentes_escala += 1;
                                }
                            }else{
                                if($dados_intervalo_normais[0]['tipo_intervalo'] == 1){
                                    $data_inicio_compara = date('Y-m-d H:i:s', strtotime("+0 minutes",strtotime($conteudo_normais[$consulta_inicial_banco])));
                                    $data_fim_compara = date('Y-m-d H:i:s', strtotime("+0 minutes",strtotime($conteudo_normais[$consulta_final_banco])));
                                    
                                    if($data_inicio_compara > $data_fim_compara){
                                        $data_inicio_compara = date('Y-m-d H:i:s', strtotime("-1 days",strtotime($conteudo_normais[$consulta_inicial_banco])));
                                    }

                                    $diferenca_datas = strtotime($data_fim_compara) - strtotime($data_inicio_compara);
		
                                    $tempo_total_trabalho = converteSegundosHoras($diferenca_datas);
                                    $tempo_total_trabalho = converteHorasDecimal($tempo_total_trabalho);
    
                                    $tempo_total_intervalo = converteHorasDecimal($dados_intervalo_normais[0]['tempo_intervalo']);
    
                                    $tempo_desconto = $tempo_total_intervalo/$tempo_total_trabalho;
    
                                    if($inicial_hora_funcionario[0] == $hora_zero && $inicial_hora_funcionario[1] != '00'){
                                        $valor_final = converteHorasDecimal('00:'.$inicial_hora_funcionario[1]);
                                        $cont_atendentes_escala += (1 - ((sprintf("%01.2f", round($valor_final, 2))))) - ($tempo_desconto * (1 - ((sprintf("%01.2f", round($valor_final, 2))))));
                            
                                    }else if($final_hora_funcionario[0] == $hora_zero && $final_hora_funcionario[1] != '00'){
                                        $valor_final = converteHorasDecimal('00:'.$final_hora_funcionario[1]);
                                        $cont_atendentes_escala += (((sprintf("%01.2f", round($valor_final, 2)))) - ($tempo_desconto * (sprintf("%01.2f", round($valor_final, 2)))));
                            
                                    }else{
                                        $cont_atendentes_escala += 1 - $tempo_desconto;
                                    } 
                                }else{
                                    $inicial_hora_intervalo = explode(":", $dados_intervalo_normais[0]['intervalo_inicial']);
                                    $final_hora_intervalo = explode(":", $dados_intervalo_normais[0]['intervalo_final']);

                                    if($inicial_hora_funcionario[0] == $hora_zero && $inicial_hora_funcionario[1] != '00'){

                                        if($inicial_hora_intervalo[0] == $hora_zero && $final_hora_intervalo[0] == $hora_zero){
                                            //intervalo 09:00 até 09:30
                                            $valor_final = converteHorasDecimal('00:'.$inicial_hora_funcionario[1]);
                                            $desconto_intervalo = converteHorasDecimal('00:'.($final_hora_intervalo[1] - $inicial_hora_intervalo[1]));

                                            $cont_atendentes_escala += (1 - (sprintf("%01.2f", round($valor_final, 2)))) - ($desconto_intervalo);

                                        }else if($inicial_hora_intervalo[0] == $hora_zero){
                                            //intervalo 09:30 até 10:30
                                            $valor_final = converteHorasDecimal('00:'.$inicial_hora_funcionario[1]);
                                            $desconto_intervalo =  converteHorasDecimal('00:'.$inicial_hora_intervalo[1]);

                                            $cont_atendentes_escala += (sprintf("%01.2f", round($valor_final, 2)) - (1 - $desconto_intervalo));

                                        }else{
                                            $valor_final = converteHorasDecimal('00:'.$inicial_hora_funcionario[1]);
                                            $cont_atendentes_escala += 1 - (sprintf("%01.2f", round($valor_final, 2)));
                                        }

                                    }else if($final_hora_funcionario[0] == $hora_zero && $final_hora_funcionario[1] != '00'){
                                        if($inicial_hora_intervalo[0] == $hora_zero && $final_hora_intervalo[0] == $hora_zero){
                                            //intervalo 09:00 até 09:30
                                            $valor_final = converteHorasDecimal('00:'.$final_hora_funcionario[1]);
                                            $desconto_intervalo = converteHorasDecimal('00:'.($final_hora_intervalo[1] - $inicial_hora_intervalo[1]));

                                            $cont_atendentes_escala += 1 - (sprintf("%01.2f", round($valor_final, 2)) + $desconto_intervalo);

                                        }else if($final_hora_intervalo[0] == $hora_zero){
                                            //intervalo 08:30 até 09:30
                                            $valor_final = converteHorasDecimal('00:'.$final_hora_funcionario[1]);
                                            $desconto_intervalo = converteHorasDecimal('00:'.$final_hora_intervalo[1]);
                                            
                                            $cont_atendentes_escala += (sprintf("%01.2f", round($valor_final, 2)) - $desconto_intervalo);
                                            
                                        }else{
                                            $valor_final = converteHorasDecimal('00:'.$final_hora_funcionario[1]);
                                            $cont_atendentes_escala += sprintf("%01.2f", round($valor_final, 2));
                                        }
                                    }else{
                                        if($inicial_hora_intervalo[0] == $hora_zero && $final_hora_intervalo[0] == $hora_zero){
                                            //intervalo 09:00 até 09:30
                                            $valor_final = converteHorasDecimal('00:'.$inicial_hora_funcionario[1]);
                                            $desconto_intervalo = converteHorasDecimal('00:'.($final_hora_intervalo[1] - $inicial_hora_intervalo[1]));

                                            $cont_atendentes_escala += 1 - $desconto_intervalo;

                                        }else if($inicial_hora_intervalo[0] == $hora_zero){
                                            //intervalo 09:30 até 10:30
                                            $valor_final = converteHorasDecimal('00:'.$inicial_hora_funcionario[1]);
                                            $desconto_intervalo = converteHorasDecimal('00:'.$inicial_hora_intervalo[1]);

                                            $cont_atendentes_escala += $desconto_intervalo;

                                        }else if($final_hora_intervalo[0] == $hora_zero){
                                            //intervalo 08:30 até 09:30
                                            $desconto_intervalo = converteHorasDecimal('00:'.$final_hora_intervalo[1]);
                                            
                                            $cont_atendentes_escala += 1 - $desconto_intervalo;
                                            
                                        }else if($inicial_hora_intervalo[0] < $hora_zero && $final_hora_intervalo[0] > $hora_zero){
                                            //intervalo 08:30 até 10:30
                                            $cont_atendentes_escala += 0;

                                        }else{
                                            //intervalo antes ou depois do horarios
                                            $cont_atendentes_escala += 1;

                                        }
                                    }
                                }
                            }
                        }
                    }                                       
                }

                if($dados_normais_especiais){
                    foreach ($dados_normais_especiais as $conteudo_normais_especiais) {
                        $inicial_hora_funcionario = explode(":", $conteudo_normais_especiais['inicial_especial']);
                        $final_hora_funcionario = explode(":", $conteudo_normais_especiais['final_especial']);
                        
                        if(($inicial_hora_funcionario[0] <= $hora_zero) && ($final_hora_funcionario[0] >= $hora_zero)){
                            
                            if(!$conteudo_normais_especiais['tempo_intervalo']){
                                if($inicial_hora_funcionario[0] == $hora_zero && $inicial_hora_funcionario[1] != '00'){
                                    $valor_final = converteHorasDecimal('00:'.$inicial_hora_funcionario[1]);
                                    $cont_atendentes_escala += 1-(sprintf("%01.2f", round($valor_final, 2)));
                                }else if($final_hora_funcionario[0] == $hora_zero && $final_hora_funcionario[1] != '00'){
                                    $valor_final = converteHorasDecimal('00:'.$final_hora_funcionario[1]);
                                    $cont_atendentes_escala += sprintf("%01.2f", round($valor_final, 2));
                                }else{
                                    $cont_atendentes_escala += 1;
                                }
                            }else{
                                $data_inicio_compara = date('Y-m-d H:i:s', strtotime("+0 minutes",strtotime($conteudo_normais_especiais['inicial_especial'])));
                                $data_fim_compara = date('Y-m-d H:i:s', strtotime("+0 minutes",strtotime($conteudo_normais_especiais['final_especial'])));
                            
                                if($data_inicio_compara > $data_fim_compara){
                                    $data_inicio_compara = date('Y-m-d H:i:s', strtotime("-1 days",strtotime($conteudo_normais_especiais['inicial_especial'])));
                                }
                            
                                $diferenca_datas = strtotime($data_fim_compara) - strtotime($data_inicio_compara);
                                
                                $tempo_total_trabalho = converteSegundosHoras($diferenca_datas);
                                $tempo_total_trabalho = converteHorasDecimal($tempo_total_trabalho);
                                $tempo_total_intervalo = converteHorasDecimal($conteudo_normais_especiais['tempo_intervalo']);
                                $tempo_desconto = $tempo_total_intervalo/$tempo_total_trabalho;
                            
                                if($inicial_hora_funcionario[0] == $hora_zero && $inicial_hora_funcionario[1] != '00'){
                                    $valor_final = converteHorasDecimal('00:'.$inicial_hora_funcionario[1]);
                                    $cont_atendentes_escala += (1 - ((sprintf("%01.2f", round($valor_final, 2))))) - ($tempo_desconto * (1 - ((sprintf("%01.2f", round($valor_final, 2))))));
                                
                                }else if($final_hora_funcionario[0] == $hora_zero && $final_hora_funcionario[1] != '00'){
                                    $valor_final = converteHorasDecimal('00:'.$final_hora_funcionario[1]);
                                    $cont_atendentes_escala += (((sprintf("%01.2f", round($valor_final, 2)))) - ($tempo_desconto * (sprintf("%01.2f", round($valor_final, 2)))));
                                
                                }else{
                                    $cont_atendentes_escala += 1 - $tempo_desconto;
                                }
                            }
                        }
                    }                                       
                }

                //INVERTIDOS
                
                if($dados_invertidos){
                    
                    foreach ($dados_invertidos as $conteudo_invertido) {
                        $inicial_hora_funcionario = explode(":", $conteudo_invertido[$consulta_inicial_banco]);

                        if($inicial_hora_funcionario[0] <= $hora_zero){

                            $dados_intervalo_invertido = DBRead('', 'tb_horarios_escala_intervalo', "WHERE id_horarios_escala = '".$conteudo_invertido['id_horarios_escala']."' AND dia = '".$dia_intervalo."' ");

                            if(!$dados_intervalo_invertido){
                                if($inicial_hora_funcionario[0] == $hora_zero && $inicial_hora_funcionario[1] != '00'){
                                    $valor_final = converteHorasDecimal('00:'.$inicial_hora_funcionario[1]);
                                    $cont_atendentes_escala += 1-(sprintf("%01.2f", round($valor_final, 2)));
                                }else{
                                    $cont_atendentes_escala += 1;
                                } 
                            }else{
                                if($dados_intervalo_invertido[0]['tipo_intervalo'] == 1){

                                    $data_inicio_compara = date('Y-m-d H:i:s', strtotime("+0 minutes",strtotime($conteudo_invertido[$consulta_inicial_banco])));
                                    $data_fim_compara = date('Y-m-d H:i:s', strtotime("+0 minutes",strtotime($conteudo_invertido[$consulta_final_banco])));
                            
                                    if($data_inicio_compara > $data_fim_compara){
                                        $data_inicio_compara = date('Y-m-d H:i:s', strtotime("-1 days",strtotime($conteudo_invertido[$consulta_inicial_banco])));
                                    }
                                    
                                    $diferenca_datas = strtotime($data_fim_compara) - strtotime($data_inicio_compara);
                                    
                                    $tempo_total_trabalho = converteSegundosHoras($diferenca_datas);
                                    $tempo_total_trabalho = converteHorasDecimal($tempo_total_trabalho);
                            
                                    $tempo_total_intervalo = converteHorasDecimal($dados_intervalo_invertido[0]['tempo_intervalo']);
                            
                                    $tempo_desconto = $tempo_total_intervalo/$tempo_total_trabalho;
                            
                                    if($inicial_hora_funcionario[0] == $hora_zero && $inicial_hora_funcionario[1] != '00'){
                                        $valor_final = converteHorasDecimal('00:'.$inicial_hora_funcionario[1]);
                                        $cont_atendentes_escala += (1 - ((sprintf("%01.2f", round($valor_final, 2))))) - ($tempo_desconto * (1 - ((sprintf("%01.2f", round($valor_final, 2))))));
                            
                                    }else{
                                        $cont_atendentes_escala += 1 - $tempo_desconto;
                                    } 
                                }else{	
	
                                    $inicial_hora_intervalo = explode(":", $dados_intervalo_invertido[0]['intervalo_inicial']);
                                    $final_hora_intervalo = explode(":", $dados_intervalo_invertido[0]['intervalo_final']);
                                
                                    if($inicial_hora_funcionario[0] == $hora_zero && $inicial_hora_funcionario[1] != '00'){
                                
                                        if($inicial_hora_intervalo[0] == $hora_zero && $final_hora_intervalo[0] == $hora_zero){
                                            //intervalo 09:00 até 09:30
                                            $valor_final = converteHorasDecimal('00:'.$inicial_hora_funcionario[1]);
                                            $desconto_intervalo = converteHorasDecimal('00:'.($final_hora_intervalo[1] - $inicial_hora_intervalo[1]));
                                
                                            $cont_atendentes_escala += (1 - (sprintf("%01.2f", round($valor_final, 2)))) - ($desconto_intervalo);
                                
                                        }else if($inicial_hora_intervalo[0] == $hora_zero){
                                            //intervalo 09:30 até 10:30
                                            $valor_final = converteHorasDecimal('00:'.$inicial_hora_funcionario[1]);
                                            $desconto_intervalo =  converteHorasDecimal('00:'.$inicial_hora_intervalo[1]);
                                
                                            $cont_atendentes_escala += (sprintf("%01.2f", round($valor_final, 2)) - (1 - $desconto_intervalo));
                                
                                        }else{
                                            $valor_final = converteHorasDecimal('00:'.$inicial_hora_funcionario[1]);
                                            $cont_atendentes_escala += 1 - (sprintf("%01.2f", round($valor_final, 2)));
                                        }
                                
                                    }else{
                                        if($inicial_hora_intervalo[0] == $hora_zero && $final_hora_intervalo[0] == $hora_zero){
                                            //intervalo 09:00 até 09:30
                                            $valor_final = converteHorasDecimal('00:'.$inicial_hora_funcionario[1]);
                                            $desconto_intervalo = converteHorasDecimal('00:'.($final_hora_intervalo[1] - $inicial_hora_intervalo[1]));
                                
                                            $cont_atendentes_escala += 1 - $desconto_intervalo;
                                
                                        }else if($inicial_hora_intervalo[0] == $hora_zero){
                                            //intervalo 09:30 até 10:30
                                            $valor_final = converteHorasDecimal('00:'.$inicial_hora_funcionario[1]);
                                            $desconto_intervalo = converteHorasDecimal('00:'.$inicial_hora_intervalo[1]);
                                
                                            $cont_atendentes_escala += $desconto_intervalo;
                                
                                        }else if($final_hora_intervalo[0] == $hora_zero){
                                            //intervalo 08:30 até 09:30
                                            $desconto_intervalo = converteHorasDecimal('00:'.$final_hora_intervalo[1]);
                                            
                                            $cont_atendentes_escala += 1 - $desconto_intervalo;
                                            
                                        }else if($inicial_hora_intervalo[0] < $hora_zero && $final_hora_intervalo[0] > $hora_zero){
                                            //intervalo 08:30 até 10:30
                                            $cont_atendentes_escala += 0;
                                
                                        }else{
                                            //intervalo antes ou depois do horarios
                                            $cont_atendentes_escala += 1;
                                
                                        }
                                    }
                                }
                            }
                        }
                    }
                }

                if($dados_invertidos_especiais){
                    
                    foreach ($dados_invertidos_especiais as $conteudo_invertidos_especiais) {
                        $inicial_hora_funcionario = explode(":", $conteudo_invertidos_especiais['inicial_especial']);

                        if($inicial_hora_funcionario[0] <= $hora_zero){
                            
                            if(!$conteudo_invertidos_especiais['tempo_intervalo']){
                                if($inicial_hora_funcionario[0] == $hora_zero && $inicial_hora_funcionario[1] != '00'){
                                    $valor_final = converteHorasDecimal('00:'.$inicial_hora_funcionario[1]);
                                    $cont_atendentes_escala += 1-(sprintf("%01.2f", round($valor_final, 2)));
                                }else{
                                    $cont_atendentes_escala += 1;
                                }
                            }else{
                                $data_inicio_compara = date('Y-m-d H:i:s', strtotime("+0 minutes",strtotime($conteudo_invertidos_especiais['inicial_especial'])));
                                $data_fim_compara = date('Y-m-d H:i:s', strtotime("+0 minutes",strtotime($conteudo_invertidos_especiais['final_especial'])));
                            
                                if($data_inicio_compara > $data_fim_compara){
                                    $data_inicio_compara = date('Y-m-d H:i:s', strtotime("-1 days",strtotime($conteudo_invertidos_especiais['inicial_especial'])));
                                }
                            
                                $diferenca_datas = strtotime($data_fim_compara) - strtotime($data_inicio_compara);
                                
                                $tempo_total_trabalho = converteSegundosHoras($diferenca_datas);
                                $tempo_total_trabalho = converteHorasDecimal($tempo_total_trabalho);
                                $tempo_total_intervalo = converteHorasDecimal($conteudo_invertidos_especiais['tempo_intervalo']);
                                $tempo_desconto = $tempo_total_intervalo/$tempo_total_trabalho;
                            
                                if($inicial_hora_funcionario[0] == $hora_zero && $inicial_hora_funcionario[1] != '00'){
                                    $valor_final = converteHorasDecimal('00:'.$inicial_hora_funcionario[1]);
                                    $cont_atendentes_escala += (1 - ((sprintf("%01.2f", round($valor_final, 2))))) - ($tempo_desconto * (1 - ((sprintf("%01.2f", round($valor_final, 2))))));
                                }else{
                                    $cont_atendentes_escala += 1 - $tempo_desconto;
                                }
                            }
                        }
                    }
                }

                //INVERTIDOS ONTEM
                if($dados_invertidos_ontem){

                    foreach ($dados_invertidos_ontem as $conteudo_ontem) {
                        $final_hora_funcionario = explode(":", $conteudo_ontem[$consulta_final_banco_ontem]);

                        if($final_hora_funcionario[0] >= $hora_zero){

                            $dados_intervalo_invertido_ontem = DBRead('', 'tb_horarios_escala_intervalo', "WHERE id_horarios_escala = '".$conteudo_ontem['id_horarios_escala']."' AND dia = '".$dia_intervalo_ontem."' ");

                            if(!$dados_intervalo_invertido_ontem){
                                if($final_hora_funcionario[0] == $hora_zero && $final_hora_funcionario[1] != '00'){
                                    $valor_final = converteHorasDecimal('00:'.$final_hora_funcionario[1]);
                                    $cont_atendentes_escala += sprintf("%01.2f", round($valor_final, 2));
                                }else{
                                    $cont_atendentes_escala += 1;
                                }  
                            }else{
                                if($dados_intervalo_invertido_ontem[0]['tipo_intervalo'] == 1){
                            
                                    $data_inicio_compara = date('Y-m-d H:i:s', strtotime("+0 minutes",strtotime($conteudo_ontem[$consulta_inicial_banco_ontem])));
                                    $data_fim_compara = date('Y-m-d H:i:s', strtotime("+0 minutes",strtotime($conteudo_ontem[$consulta_final_banco_ontem])));
                            
                                    if($data_inicio_compara > $data_fim_compara){
                                        $data_inicio_compara = date('Y-m-d H:i:s', strtotime("-1 days",strtotime($conteudo_ontem[$consulta_inicial_banco_ontem])));
                                    }
                                    
                                    $diferenca_datas = strtotime($data_fim_compara) - strtotime($data_inicio_compara);
                                    
                                    $tempo_total_trabalho = converteSegundosHoras($diferenca_datas);
                                    $tempo_total_trabalho = converteHorasDecimal($tempo_total_trabalho);
                            
                                    $tempo_total_intervalo = converteHorasDecimal($dados_intervalo_invertido_ontem[0]['tempo_intervalo']);
                            
                                    $tempo_desconto = $tempo_total_intervalo/$tempo_total_trabalho;
                            
                                    if($final_hora_funcionario[0] == $hora_zero && $final_hora_funcionario[1] != '00'){
                                        $valor_final = converteHorasDecimal('00:'.$final_hora_funcionario[1]);
                                        $cont_atendentes_escala += (((sprintf("%01.2f", round($valor_final, 2)))) - ($tempo_desconto * (sprintf("%01.2f", round($valor_final, 2)))));
                            
                                    }else{
                                        $cont_atendentes_escala += 1 - $tempo_desconto;
                                    } 
                                }else{	
	
                                    $inicial_hora_intervalo = explode(":", $dados_intervalo_invertido_ontem[0]['intervalo_inicial']);
                                    $final_hora_intervalo = explode(":", $dados_intervalo_invertido_ontem[0]['intervalo_final']);
                                
                                    if($final_hora_funcionario[0] == $hora_zero && $final_hora_funcionario[1] != '00'){
                                        if($inicial_hora_intervalo[0] == $hora_zero && $final_hora_intervalo[0] == $hora_zero){
                                            //intervalo 09:00 até 09:30
                                            $valor_final = converteHorasDecimal('00:'.$final_hora_funcionario[1]);
                                            $desconto_intervalo = converteHorasDecimal('00:'.($final_hora_intervalo[1] - $inicial_hora_intervalo[1]));
                                
                                            $cont_atendentes_escala += 1 - (sprintf("%01.2f", round($valor_final, 2)) + $desconto_intervalo);
                                
                                        }else if($final_hora_intervalo[0] == $hora_zero){
                                            //intervalo 08:30 até 09:30
                                            $valor_final = converteHorasDecimal('00:'.$final_hora_funcionario[1]);
                                            $desconto_intervalo = converteHorasDecimal('00:'.$final_hora_intervalo[1]);
                                            
                                            $cont_atendentes_escala += (sprintf("%01.2f", round($valor_final, 2)) - $desconto_intervalo);
                                            
                                        }else{
                                            $valor_final = converteHorasDecimal('00:'.$final_hora_funcionario[1]);
                                            $cont_atendentes_escala += sprintf("%01.2f", round($valor_final, 2));
                                        }
                                    }else{
                                        if($inicial_hora_intervalo[0] == $hora_zero && $final_hora_intervalo[0] == $hora_zero){
                                            //intervalo 09:00 até 09:30
                                            $valor_final = converteHorasDecimal('00:'.$inicial_hora_funcionario[1]);
                                            $desconto_intervalo = converteHorasDecimal('00:'.($final_hora_intervalo[1] - $inicial_hora_intervalo[1]));
                                
                                            $cont_atendentes_escala += 1 - $desconto_intervalo;
                                
                                        }else if($inicial_hora_intervalo[0] == $hora_zero){
                                            //intervalo 09:30 até 10:30
                                            $valor_final = converteHorasDecimal('00:'.$inicial_hora_funcionario[1]);
                                            $desconto_intervalo = converteHorasDecimal('00:'.$inicial_hora_intervalo[1]);
                                
                                            $cont_atendentes_escala += $desconto_intervalo;
                                
                                        }else if($final_hora_intervalo[0] == $hora_zero){
                                            //intervalo 08:30 até 09:30
                                            $desconto_intervalo = converteHorasDecimal('00:'.$final_hora_intervalo[1]);
                                            
                                            $cont_atendentes_escala += 1 - $desconto_intervalo;
                                            
                                        }else if($inicial_hora_intervalo[0] < $hora_zero && $final_hora_intervalo[0] > $hora_zero){
                                            //intervalo 08:30 até 10:30
                                            $cont_atendentes_escala += 0;
                                
                                        }else{
                                            //intervalo antes ou depois do horarios
                                            $cont_atendentes_escala += 1;
                                
                                        }
                                    }
                                }
                            }
                        }
                    }
                }

                if($dados_invertidos_ontem_especiais){

                    foreach ($dados_invertidos_ontem_especiais as $conteudo_invertidos_ontem_especiais) {
                        $final_hora_funcionario = explode(":", $conteudo_invertidos_ontem_especiais['final_especial']);
                        if($final_hora_funcionario[0] >= $hora_zero){

                            if(!$conteudo_invertidos_ontem_especiais['tempo_intervalo']){
                                if($final_hora_funcionario[0] == $hora_zero && $final_hora_funcionario[1] != '00'){
                                    $valor_final = converteHorasDecimal('00:'.$final_hora_funcionario[1]);
                                    $cont_atendentes_escala += sprintf("%01.2f", round($valor_final, 2));
                                }else{
                                    $cont_atendentes_escala += 1;
                                }
                            }else{
                                $data_inicio_compara = date('Y-m-d H:i:s', strtotime("+0 minutes",strtotime($conteudo_invertidos_ontem_especiais['inicial_especial'])));
                                $data_fim_compara = date('Y-m-d H:i:s', strtotime("+0 minutes",strtotime($conteudo_invertidos_ontem_especiais['final_especial'])));
                            
                                if($data_inicio_compara > $data_fim_compara){
                                    $data_inicio_compara = date('Y-m-d H:i:s', strtotime("-1 days",strtotime($conteudo_invertidos_ontem_especiais['inicial_especial'])));
                                }
                            
                                $diferenca_datas = strtotime($data_fim_compara) - strtotime($data_inicio_compara);
                                
                                $tempo_total_trabalho = converteSegundosHoras($diferenca_datas);
                                $tempo_total_trabalho = converteHorasDecimal($tempo_total_trabalho);
                                $tempo_total_intervalo = converteHorasDecimal($conteudo_invertidos_ontem_especiais['tempo_intervalo']);
                                $tempo_desconto = $tempo_total_intervalo/$tempo_total_trabalho;
                            
                                if($final_hora_funcionario[0] == $hora_zero && $final_hora_funcionario[1] != '00'){
                                    $valor_final = converteHorasDecimal('00:'.$final_hora_funcionario[1]);
                                    $cont_atendentes_escala += (((sprintf("%01.2f", round($valor_final, 2)))) - ($tempo_desconto * (sprintf("%01.2f", round($valor_final, 2)))));
                                
                                }else{
                                    $cont_atendentes_escala += 1 - $tempo_desconto;
                                }
                            }
                        }
                    }
                }


                if(!$cont_atendentes_escala){
                    $cont_atendentes_escala = 0;
                }

            //fim codigo atendentes escala
            
            //Adiciona previsao

            $diasemana = array(
                "0" => "Domingo",
                "1" => "Segunda",
                "2" => "Terça",
                "3" => "Quarta",
                "4" => "Quinta",
                "5" => "Sexta",
                "6" => "Sábado",
            );

            $data_de_consulta = date('Y-m-d', strtotime($data));
            $diasemana_numero = date('w', strtotime($data_de_consulta));
            $atendentes_escala[$diasemana_numero][$hora] += sprintf("%01.2f", round($cont_atendentes_escala, 2));
            $hora++;
        }
    }
    $dia = 0;
        while ($dia < 7) {
            $hora = 0;
            while($hora < 24){
                if($contador_dias_semana_escala[$dia] && $contador_dias_semana_escala[$dia] != '0'){
                    $atendentes_escala[$dia][$hora] = sprintf("%01.2f", ($atendentes_escala[$dia][$hora]/$contador_dias_semana_escala[$dia]));
                }else{
                    $atendentes_escala[$dia][$hora] = '0';
                }
                $hora++;
            }
        $dia++;
        }
   
    $chart = 0;
    while ($chart < 7) {?>
        
        <div id="<?php echo "chart-" . $chart; ?>"></div> 
        <script>
            $(function () {
                // Create the first chart
                $('#<?php echo "chart-" . $chart; ?>').highcharts({
                    chart: {
                        type: '<?=$nome_tipo_grafico;?>'
                    },
                    title: {
                        text: '<?=$dias_semana[$chart];?>' // Title for the chart
                    },
                    xAxis: {
                        categories: <?php echo json_encode($horas) ?>
                        // Categories for the charts
                    },
                    yAxis: {
                        min: 0,
                        title: {
                            text: 'Ligações por Hora'
                        },
                        stackLabels: {
                            enabled: true,
                            style: {
                                fontWeight: 'bold',
                                color: (Highcharts.theme && Highcharts.theme.textColor) || 'black'
                            }
                        }
                    },
                    legend: {
                        enabled:true,
                        backgroundColor: (Highcharts.theme && Highcharts.theme.background2) || 'white',
                        borderColor: '#CCC',
                        borderWidth: 1,
                        shadow: false
                    },
                    tooltip: {
                        headerFormat: '<b>{point.x}</b><br/>',
                        pointFormat: '{series.name}: {point.y}<br/>Total: {point.stackTotal}'
                    },
                    plotOptions: {
                                                        
                        series: {
                            dataLabels: {
                                enabled: true
                            }
                        }
                    },
                    series: [
                        {
                            name: 'Capacidade da escala', // Name of your series
                            data: <?php echo json_encode($atendentes_escala[$chart], JSON_NUMERIC_CHECK); ?> // The data in your series

                        },
                        {
                            name: 'Capacidade necessária', // Name of your series
                            data: <?php echo json_encode($tendencia[$chart], JSON_NUMERIC_CHECK) ?> // The data in your series

                        }
                    ],
                    navigation: {
                        buttonOptions: {
                            enabled: true
                        }
                    }
                });
            });
        </script>  
        <?php
        echo '<hr>';
        $chart++;
        
    }
}

function relatorio_tendencia_20min($data_de, $data_ate, $data_de_amostra, $data_ate_amostra, $aproveitamento, $segundos_espera_perdida, $ponderado){
    
    if($ponderado){
        $legenda_ponderado = '(Ponderado)';
    }
    $nome_tipo_grafico = 'line';

    $fila = "'callATENDIMENTOvip','callATENDIMENTOalta','callATENDIMENTOnormal','callATENDIMENTObaixa','callATENDIMENTOnotificacaoparada', 'callATENDIMENTOvipEXT','callATENDIMENTOaltaEXT','callATENDIMENTOnormalEXT','callATENDIMENTObaixaEXT','callATENDIMENTOnotificacaoparadaEXT','callATENDIMENTOvipEXP','callATENDIMENTOaltaEXP','callATENDIMENTOnormalEXP','callATENDIMENTObaixaEXP','callATENDIMENTOnotificacaoparadaEXP'";

    if($aproveitamento){
        if($aproveitamento > 99){
            $legenda_aproveitamento = "100%";
            $aproveitamento = '1';      
        }else{
            $legenda_aproveitamento = $aproveitamento."%";
            $aproveitamento = '0.'.$aproveitamento;
        }

    }else{
        $legenda_aproveitamento = '100%';
        $aproveitamento = '1';
    }

    if($segundos_espera_perdida){
        $legenda_tempo_abaixo = $segundos_espera_perdida." segundos";
    }else{
        $legenda_tempo_abaixo = '0 segundos';
    }

    $nome_tipo_grafico = 'line';

    $filtro_perdidas = '';  
    if($segundos_espera_perdida){
        $filtro_perdidas .= " AND b.data3 >= $segundos_espera_perdida";
    }

    $data_hora = converteDataHora(getDataHora());

    $gerado = "<span style=\"font-size: 14px;\"><strong>Gerado em: </strong> $data_hora</span>";

    echo "<div class=\"col-md-12\" style=\"padding: 0\">";
    echo "<legend style=\"text-align:center;\"><strong>Gráfico de Capacidade por 20min ".$legenda_ponderado."</strong><br>$gerado</legend>";
    echo "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong>Data Inicial da Escala- </strong>".$data_de.", <strong>Data Final da Escala- </strong>".$data_ate.", <strong>Data Inicial de Amostra de Chamadas - </strong>".$data_de_amostra.", <strong>Data Final de Amostra de Chamadas - </strong>".$data_ate_amostra.", <strong> Descartar tempo de atendimento abaixo de - </strong> ".$legenda_tempo_abaixo.", <strong>Aproveitamento - </strong>".$legenda_aproveitamento."</legend>";
    
    $dias_semana = array('Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado');
    $horas = array('0','1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17','18','19','20','21','22','23');
    $tma = array();
    $qtd_total_atendidas = array();
    $data_hora = converteDataHora(getDataHora());
    $total_entradas = array();
    $tendencia = array();
    $previsao = array();

    $contador_dias_semana = array();

    $tma_dia = array();
    $qtd_total_atendidas_dia = array();
    $total_entradas_dia = array();

    $entradas_dia = array();

    $auxiliar_menor_dia = array();
    $auxiliar_maior_dia = array();
    
    foreach (rangeDatas(converteData($data_de_amostra), converteData($data_ate_amostra)) as $data) {        
        $numero_dia_semana = date('w', strtotime($data));
        $hora = 0;
        $hora_array = 0;
        $contador_dias_semana[$numero_dia_semana]++;

        while ($hora < 24) {
            $hora_zero = sprintf('%02d', $hora);

            // inicio 00
                $qtd_atendidas = 0;
                $qtd_perdidas = 0;
                $soma_ta_atendidas = 0;
            
                $dados_atendidas_grafico = DBRead('snep','queue_log a',"INNER JOIN queue_log b ON (b.callid = a.callid AND b.event = 'CONNECT') INNER JOIN queue_log c ON (c.callid = a.callid AND (c.event = 'COMPLETEAGENT' OR c.event = 'COMPLETECALLER' OR c.event = 'BLINDTRANSFER' OR c.event = 'ATTENDEDTRANSFER')) WHERE a.event = 'ENTERQUEUE' AND b.time BETWEEN '$data $hora_zero:00:00' AND '$data $hora_zero:19:59' GROUP BY b.id ORDER BY b.id", "a.queuename AS 'enterqueue_queuename', b.id, c.event AS 'finalizacao_event', c.data1 AS 'finalizacao_data1', c.data2 AS 'finalizacao_data2', c.data3 AS 'finalizacao_data3', c.data4 AS 'finalizacao_data4'");

                $atendidas_hora = 0;
                if($dados_atendidas_grafico){
                    foreach ($dados_atendidas_grafico as $conteudo_atendidas) {
                        if(preg_match("/'".$conteudo_atendidas['enterqueue_queuename']."'/i", $fila) || !$fila){
                            if($conteudo_atendidas['finalizacao_event']=='COMPLETEAGENT' || $conteudo_atendidas['finalizacao_event']=='COMPLETECALLER'){
                                $soma_ta_atendidas +=  $conteudo_atendidas['finalizacao_data2'];
                            }elseif(($conteudo_atendidas['finalizacao_event']=='BLINDTRANSFER' || $conteudo_atendidas['finalizacao_event']=='ATTENDEDTRANSFER') && $conteudo_atendidas['finalizacao_data1'] != 'LINK'){
                                $soma_ta_atendidas +=  $conteudo_atendidas['finalizacao_data4'];
                            }
                            $qtd_atendidas++;
                            $atendidas_hora++;
                        }
                    }
                }     

                $dados_perdidas_grafico = DBRead('snep','queue_log a',"INNER JOIN queue_log b ON (b.callid = a.callid AND (b.event = 'ABANDON' OR b.event = 'EXITWITHTIMEOUT')) WHERE a.event = 'ENTERQUEUE' AND a.time BETWEEN '$data $hora_zero:00:00' AND '$data $hora_zero:19:59' $filtro_perdidas GROUP BY b.id ORDER BY b.id","a.queuename AS 'enterqueue_queuename', b.id");

                $perdidas_hora = 0;
                if($dados_perdidas_grafico){
                    foreach ($dados_perdidas_grafico as $conteudo_perdidas) {
                        if(preg_match("/'".$conteudo_perdidas['enterqueue_queuename']."'/i", $fila) || !$fila){
                            $perdidas_hora++;
                        }
                    }
                }     
                
                $total_entradas[$numero_dia_semana][$hora_array] += $atendidas_hora + $perdidas_hora;
                $total_entradas_dia[$numero_dia_semana][$contador_dias_semana[$numero_dia_semana]][$hora_array] += $atendidas_hora + $perdidas_hora;
                $entradas_dia[$numero_dia_semana][$contador_dias_semana[$numero_dia_semana]] += $atendidas_hora + $perdidas_hora;
                $tma[$numero_dia_semana][$hora_array] += $soma_ta_atendidas;
                $qtd_total_atendidas[$numero_dia_semana][$hora_array] += $qtd_atendidas;
                $tma_dia[$numero_dia_semana][$contador_dias_semana[$numero_dia_semana]][$hora_array] += $soma_ta_atendidas;
                $qtd_total_atendidas_dia[$numero_dia_semana][$contador_dias_semana[$numero_dia_semana]][$hora_array] += $qtd_atendidas;
            // fim 00

            // inicio 20
                $qtd_atendidas = 0;
                $qtd_perdidas = 0;
                $soma_ta_atendidas = 0;
            
                $dados_atendidas_grafico = DBRead('snep','queue_log a',"INNER JOIN queue_log b ON (b.callid = a.callid AND b.event = 'CONNECT') INNER JOIN queue_log c ON (c.callid = a.callid AND (c.event = 'COMPLETEAGENT' OR c.event = 'COMPLETECALLER' OR c.event = 'BLINDTRANSFER' OR c.event = 'ATTENDEDTRANSFER')) WHERE a.event = 'ENTERQUEUE' AND b.time BETWEEN '$data $hora_zero:20:00' AND '$data $hora_zero:39:59' GROUP BY b.id ORDER BY b.id", "a.queuename AS 'enterqueue_queuename', b.id, c.event AS 'finalizacao_event', c.data1 AS 'finalizacao_data1', c.data2 AS 'finalizacao_data2', c.data3 AS 'finalizacao_data3', c.data4 AS 'finalizacao_data4'");

                $atendidas_hora = 0;
                if($dados_atendidas_grafico){
                    foreach ($dados_atendidas_grafico as $conteudo_atendidas) {
                        if(preg_match("/'".$conteudo_atendidas['enterqueue_queuename']."'/i", $fila) || !$fila){
                            if($conteudo_atendidas['finalizacao_event']=='COMPLETEAGENT' || $conteudo_atendidas['finalizacao_event']=='COMPLETECALLER'){
                                $soma_ta_atendidas +=  $conteudo_atendidas['finalizacao_data2'];
                            }elseif(($conteudo_atendidas['finalizacao_event']=='BLINDTRANSFER' || $conteudo_atendidas['finalizacao_event']=='ATTENDEDTRANSFER') && $conteudo_atendidas['finalizacao_data1'] != 'LINK'){
                                $soma_ta_atendidas +=  $conteudo_atendidas['finalizacao_data4'];
                            }
                            $qtd_atendidas++;
                            $atendidas_hora++;
                        }
                    }
                }           

                $dados_perdidas_grafico = DBRead('snep','queue_log a',"INNER JOIN queue_log b ON (b.callid = a.callid AND (b.event = 'ABANDON' OR b.event = 'EXITWITHTIMEOUT')) WHERE a.event = 'ENTERQUEUE' AND a.time BETWEEN '$data $hora_zero:20:00' AND '$data $hora_zero:39:59' $filtro_perdidas GROUP BY b.id ORDER BY b.id","a.queuename AS 'enterqueue_queuename', b.id");

                $perdidas_hora = 0;
                if($dados_perdidas_grafico){
                    foreach ($dados_perdidas_grafico as $conteudo_perdidas) {
                        if(preg_match("/'".$conteudo_perdidas['enterqueue_queuename']."'/i", $fila) || !$fila){
                            $perdidas_hora++;
                        }
                    }
                }           

                $total_entradas[$numero_dia_semana][$hora_array+1] += $atendidas_hora + $perdidas_hora;
                $total_entradas_dia[$numero_dia_semana][$contador_dias_semana[$numero_dia_semana]][$hora_array+1] += $atendidas_hora + $perdidas_hora;
                $entradas_dia[$numero_dia_semana][$contador_dias_semana[$numero_dia_semana]] += $atendidas_hora + $perdidas_hora;

                $tma[$numero_dia_semana][$hora_array+1] += $soma_ta_atendidas;
                $qtd_total_atendidas[$numero_dia_semana][$hora_array+1] += $qtd_atendidas;
                $tma_dia[$numero_dia_semana][$contador_dias_semana[$numero_dia_semana]][$hora_array+1] += $soma_ta_atendidas;
                $qtd_total_atendidas_dia[$numero_dia_semana][$contador_dias_semana[$numero_dia_semana]][$hora_array+1] += $qtd_atendidas;
            // fim 20

            // inicio 40
                $qtd_atendidas = 0;
                $qtd_perdidas = 0;
                $soma_ta_atendidas = 0;
            
                $dados_atendidas_grafico = DBRead('snep','queue_log a',"INNER JOIN queue_log b ON (b.callid = a.callid AND b.event = 'CONNECT') INNER JOIN queue_log c ON (c.callid = a.callid AND (c.event = 'COMPLETEAGENT' OR c.event = 'COMPLETECALLER' OR c.event = 'BLINDTRANSFER' OR c.event = 'ATTENDEDTRANSFER')) WHERE a.event = 'ENTERQUEUE' AND b.time BETWEEN '$data $hora_zero:40:00' AND '$data $hora_zero:59:59' GROUP BY b.id ORDER BY b.id", "a.queuename AS 'enterqueue_queuename', b.id, c.event AS 'finalizacao_event', c.data1 AS 'finalizacao_data1', c.data2 AS 'finalizacao_data2', c.data3 AS 'finalizacao_data3', c.data4 AS 'finalizacao_data4'");

                $atendidas_hora = 0;
                if($dados_atendidas_grafico){
                    foreach ($dados_atendidas_grafico as $conteudo_atendidas) {
                        if(preg_match("/'".$conteudo_atendidas['enterqueue_queuename']."'/i", $fila) || !$fila){
                            if($conteudo_atendidas['finalizacao_event']=='COMPLETEAGENT' || $conteudo_atendidas['finalizacao_event']=='COMPLETECALLER'){
                                $soma_ta_atendidas +=  $conteudo_atendidas['finalizacao_data2'];
                            }elseif(($conteudo_atendidas['finalizacao_event']=='BLINDTRANSFER' || $conteudo_atendidas['finalizacao_event']=='ATTENDEDTRANSFER') && $conteudo_atendidas['finalizacao_data1'] != 'LINK'){
                                $soma_ta_atendidas +=  $conteudo_atendidas['finalizacao_data4'];
                            }
                            $qtd_atendidas++;
                            $atendidas_hora++;
                        }
                    }
                }       

                $dados_perdidas_grafico = DBRead('snep','queue_log a',"INNER JOIN queue_log b ON (b.callid = a.callid AND (b.event = 'ABANDON' OR b.event = 'EXITWITHTIMEOUT')) WHERE a.event = 'ENTERQUEUE' AND a.time BETWEEN '$data $hora_zero:40:00' AND '$data $hora_zero:59:59' $filtro_perdidas GROUP BY b.id ORDER BY b.id","a.queuename AS 'enterqueue_queuename', b.id");

                $perdidas_hora = 0;
                if($dados_perdidas_grafico){
                    foreach ($dados_perdidas_grafico as $conteudo_perdidas) {
                        if(preg_match("/'".$conteudo_perdidas['enterqueue_queuename']."'/i", $fila) || !$fila){
                            $perdidas_hora++;
                        }
                    }
                }       
              
                $total_entradas[$numero_dia_semana][$hora_array+2] += $atendidas_hora + $perdidas_hora;
                $total_entradas_dia[$numero_dia_semana][$contador_dias_semana[$numero_dia_semana]][$hora_array+2] += $atendidas_hora + $perdidas_hora;
                $entradas_dia[$numero_dia_semana][$contador_dias_semana[$numero_dia_semana]] += $atendidas_hora + $perdidas_hora;
                $tma[$numero_dia_semana][$hora_array+2] += $soma_ta_atendidas;
                $qtd_total_atendidas[$numero_dia_semana][$hora_array+2] += $qtd_atendidas;
                $tma_dia[$numero_dia_semana][$contador_dias_semana[$numero_dia_semana]][$hora_array+2] += $soma_ta_atendidas;
                $qtd_total_atendidas_dia[$numero_dia_semana][$contador_dias_semana[$numero_dia_semana]][$hora_array+2] += $qtd_atendidas;
            // fim 40

            $hora_array += 3;
            $hora++;            
        }
    }

    foreach ($entradas_dia as $dia_semana => $dia) {
        foreach ($dia as $key => $value) {
            if(!$auxiliar_menor_dia[$dia_semana] || $dia[$auxiliar_menor_dia[$dia_semana]] > $value){
                $auxiliar_menor_dia[$dia_semana] = $key;
            }
            if(!$auxiliar_maior_dia[$dia_semana] || $dia[$auxiliar_maior_dia[$dia_semana]] < $value){
                $auxiliar_maior_dia[$dia_semana] = $key;
            }
        }
    }

    $cont_dia = 0;
    while($cont_dia < 7){

        $cont_hora = 0;
        $cont_hora_array = 0;
        while($cont_hora < 24){
            // inicio 00
                if(!$total_entradas[$cont_dia][$cont_hora_array]){
                    $total_entradas[$cont_dia][$cont_hora_array] = 0;
                }
                if(!$total_entradas_dia[$cont_dia][$auxiliar_maior_dia[$cont_dia]][$cont_hora_array]){
                    $total_entradas_dia[$cont_dia][$auxiliar_maior_dia[$cont_dia]][$cont_hora_array] = 0;
                }
                if(!$total_entradas_dia[$cont_dia][$auxiliar_menor_dia[$cont_dia]][$cont_hora_array]){
                    $total_entradas_dia[$cont_dia][$auxiliar_menor_dia[$cont_dia]][$cont_hora_array] = 0;
                }

                if($ponderado){
                    $tma[$cont_dia][$cont_hora_array] = sprintf("%01.2f", round(($tma[$cont_dia][$cont_hora_array]-$tma_dia[$cont_dia][$auxiliar_maior_dia[$cont_dia]][$cont_hora_array]-$tma_dia[$cont_dia][$auxiliar_menor_dia[$cont_dia]][$cont_hora_array])/($qtd_total_atendidas[$cont_dia][$cont_hora_array] == 0 ? 1 : $qtd_total_atendidas[$cont_dia][$cont_hora_array]-$qtd_total_atendidas_dia[$cont_dia][$auxiliar_maior_dia[$cont_dia]][$cont_hora_array]-$qtd_total_atendidas_dia[$cont_dia][$auxiliar_menor_dia[$cont_dia]][$cont_hora_array]), 2));
                    if($tma[$cont_dia][$cont_hora_array] != 0){
                        $media_dia_hora = ($tma[$cont_dia][$cont_hora_array])/($contador_dias_semana[$cont_dia]-2);
                        $hora_por_tma = 1200/$media_dia_hora;
                        $aproveitamento_media_dia_hora = $hora_por_tma*$aproveitamento;
                        $tendencia[$cont_dia][$cont_hora_array] = ($total_entradas[$cont_dia][$cont_hora_array]-$total_entradas_dia[$cont_dia][$auxiliar_maior_dia[$cont_dia]][$cont_hora_array]-$total_entradas_dia[$cont_dia][$auxiliar_menor_dia[$cont_dia]][$cont_hora_array])/$aproveitamento_media_dia_hora;
                        $tendencia[$cont_dia][$cont_hora_array] = sprintf("%01.2f", $tendencia[$cont_dia][$cont_hora_array]);
                        
                    }else{
                        $tendencia[$cont_dia][$cont_hora_array] = 0;
                    }
                }else{
                    $tma[$cont_dia][$cont_hora_array] = sprintf("%01.2f", round(($tma[$cont_dia][$cont_hora_array])/($qtd_total_atendidas[$cont_dia][$cont_hora_array] == 0 ? 1 : $qtd_total_atendidas[$cont_dia][$cont_hora_array]), 2));
                    if($tma[$cont_dia][$cont_hora_array] != 0){
                        $media_dia_hora = ($tma[$cont_dia][$cont_hora_array])/($contador_dias_semana[$cont_dia]);
                        $hora_por_tma = 1200/$media_dia_hora;
                        $aproveitamento_media_dia_hora = $hora_por_tma*$aproveitamento;
                        $tendencia[$cont_dia][$cont_hora_array] = ($total_entradas[$cont_dia][$cont_hora_array])/$aproveitamento_media_dia_hora;
                        $tendencia[$cont_dia][$cont_hora_array] = sprintf("%01.2f", $tendencia[$cont_dia][$cont_hora_array]);
                        
                    }else{
                        $tendencia[$cont_dia][$cont_hora_array] = 0;
                    }
                }           
            // fim 00

            // inicio 20
                if(!$total_entradas[$cont_dia][$cont_hora_array+1]){
                    $total_entradas[$cont_dia][$cont_hora_array+1] = 0;
                }
                if(!$total_entradas_dia[$cont_dia][$auxiliar_maior_dia[$cont_dia]][$cont_hora_array+1]){
                    $total_entradas_dia[$cont_dia][$auxiliar_maior_dia[$cont_dia]][$cont_hora_array+1] = 0;
                }
                if(!$total_entradas_dia[$cont_dia][$auxiliar_menor_dia[$cont_dia]][$cont_hora_array+1]){
                    $total_entradas_dia[$cont_dia][$auxiliar_menor_dia[$cont_dia]][$cont_hora_array+1] = 0;
                }

                if($ponderado){
                    $tma[$cont_dia][$cont_hora_array+1] = sprintf("%01.2f", round(($tma[$cont_dia][$cont_hora_array+1]-$tma_dia[$cont_dia][$auxiliar_maior_dia[$cont_dia]][$cont_hora_array+1]-$tma_dia[$cont_dia][$auxiliar_menor_dia[$cont_dia]][$cont_hora_array+1])/($qtd_total_atendidas[$cont_dia][$cont_hora_array+1] == 0 ? 1 : $qtd_total_atendidas[$cont_dia][$cont_hora_array+1]-$qtd_total_atendidas_dia[$cont_dia][$auxiliar_maior_dia[$cont_dia]][$cont_hora_array+1]-$qtd_total_atendidas_dia[$cont_dia][$auxiliar_menor_dia[$cont_dia]][$cont_hora_array+1]), 2));
                    if($tma[$cont_dia][$cont_hora_array+1] != 0){
                        $media_dia_hora = ($tma[$cont_dia][$cont_hora_array+1])/($contador_dias_semana[$cont_dia]-2);
                        $hora_por_tma = 1200/$media_dia_hora;
                        $aproveitamento_media_dia_hora = $hora_por_tma*$aproveitamento;
                        $tendencia[$cont_dia][$cont_hora_array+1] = ($total_entradas[$cont_dia][$cont_hora_array+1]-$total_entradas_dia[$cont_dia][$auxiliar_maior_dia[$cont_dia]][$cont_hora_array+1]-$total_entradas_dia[$cont_dia][$auxiliar_menor_dia[$cont_dia]][$cont_hora_array+1])/$aproveitamento_media_dia_hora;
                        $tendencia[$cont_dia][$cont_hora_array+1] = sprintf("%01.2f", $tendencia[$cont_dia][$cont_hora_array+1]);
                        
                    }else{
                        $tendencia[$cont_dia][$cont_hora_array+1] = 0;
                    }
                }else{
                    $tma[$cont_dia][$cont_hora_array+1] = sprintf("%01.2f", round(($tma[$cont_dia][$cont_hora_array+1])/($qtd_total_atendidas[$cont_dia][$cont_hora_array+1] == 0 ? 1 : $qtd_total_atendidas[$cont_dia][$cont_hora_array+1]), 2));
                    if($tma[$cont_dia][$cont_hora_array+1] != 0){
                        $media_dia_hora = ($tma[$cont_dia][$cont_hora_array+1])/($contador_dias_semana[$cont_dia]);
                        $hora_por_tma = 1200/$media_dia_hora;
                        $aproveitamento_media_dia_hora = $hora_por_tma*$aproveitamento;
                        $tendencia[$cont_dia][$cont_hora_array+1] = ($total_entradas[$cont_dia][$cont_hora_array+1])/$aproveitamento_media_dia_hora;
                        $tendencia[$cont_dia][$cont_hora_array+1] = sprintf("%01.2f", $tendencia[$cont_dia][$cont_hora_array+1]);
                        
                    }else{
                        $tendencia[$cont_dia][$cont_hora_array+1] = 0;
                    }
                }           
            // fim 20

            // inicio 40
                if(!$total_entradas[$cont_dia][$cont_hora_array+2]){
                    $total_entradas[$cont_dia][$cont_hora_array+2] = 0;
                }
                if(!$total_entradas_dia[$cont_dia][$auxiliar_maior_dia[$cont_dia]][$cont_hora_array+2]){
                    $total_entradas_dia[$cont_dia][$auxiliar_maior_dia[$cont_dia]][$cont_hora_array+2] = 0;
                }
                if(!$total_entradas_dia[$cont_dia][$auxiliar_menor_dia[$cont_dia]][$cont_hora_array+2]){
                    $total_entradas_dia[$cont_dia][$auxiliar_menor_dia[$cont_dia]][$cont_hora_array+2] = 0;
                }

                if($ponderado){
                    $tma[$cont_dia][$cont_hora_array+2] = sprintf("%01.2f", round(($tma[$cont_dia][$cont_hora_array+2]-$tma_dia[$cont_dia][$auxiliar_maior_dia[$cont_dia]][$cont_hora_array+2]-$tma_dia[$cont_dia][$auxiliar_menor_dia[$cont_dia]][$cont_hora_array+2])/($qtd_total_atendidas[$cont_dia][$cont_hora_array+2] == 0 ? 1 : $qtd_total_atendidas[$cont_dia][$cont_hora_array+2]-$qtd_total_atendidas_dia[$cont_dia][$auxiliar_maior_dia[$cont_dia]][$cont_hora_array+2]-$qtd_total_atendidas_dia[$cont_dia][$auxiliar_menor_dia[$cont_dia]][$cont_hora_array+2]), 2));
                    if($tma[$cont_dia][$cont_hora_array+2] != 0){
                        $media_dia_hora = ($tma[$cont_dia][$cont_hora_array+2])/($contador_dias_semana[$cont_dia]-2);
                        $hora_por_tma = 1200/$media_dia_hora;
                        $aproveitamento_media_dia_hora = $hora_por_tma*$aproveitamento;
                        $tendencia[$cont_dia][$cont_hora_array+2] = ($total_entradas[$cont_dia][$cont_hora_array+2]-$total_entradas_dia[$cont_dia][$auxiliar_maior_dia[$cont_dia]][$cont_hora_array+2]-$total_entradas_dia[$cont_dia][$auxiliar_menor_dia[$cont_dia]][$cont_hora_array+2])/$aproveitamento_media_dia_hora;
                        $tendencia[$cont_dia][$cont_hora_array+2] = sprintf("%01.2f", $tendencia[$cont_dia][$cont_hora_array+2]);
                        
                    }else{
                        $tendencia[$cont_dia][$cont_hora_array+2] = 0;
                    }
                }else{
                    $tma[$cont_dia][$cont_hora_array+2] = sprintf("%01.2f", round(($tma[$cont_dia][$cont_hora_array+2])/($qtd_total_atendidas[$cont_dia][$cont_hora_array+2] == 0 ? 1 : $qtd_total_atendidas[$cont_dia][$cont_hora_array+2]), 2));
                    if($tma[$cont_dia][$cont_hora_array+2] != 0){
                        $media_dia_hora = ($tma[$cont_dia][$cont_hora_array+2])/($contador_dias_semana[$cont_dia]);
                        $hora_por_tma = 1200/$media_dia_hora;
                        $aproveitamento_media_dia_hora = $hora_por_tma*$aproveitamento;
                        $tendencia[$cont_dia][$cont_hora_array+2] = ($total_entradas[$cont_dia][$cont_hora_array+2])/$aproveitamento_media_dia_hora;
                        $tendencia[$cont_dia][$cont_hora_array+2] = sprintf("%01.2f", $tendencia[$cont_dia][$cont_hora_array+2]);
                        
                    }else{
                        $tendencia[$cont_dia][$cont_hora_array+2] = 0;
                    }
                }           
            // fim 40

            $cont_hora_array += 3;
            $cont_hora++;
        }       
        $cont_dia++;
    }

    $dias_semana = array('Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado');
    $data_hora = converteDataHora(getDataHora());
    $chart = 0;
    $atendentes_escala = array();
    $contador_dias_semana_escala = array();

    foreach(rangeDatas(converteData($data_de), converteData($data_ate)) as $data){
        $atendentes_central = array();
        $horas = array();
        $numero_dia_semana = date('w', strtotime($data));
        $hora = 0;

        $consulta_data = explode("-", $data);
        $consulta_data = $consulta_data[0].'-'.$consulta_data[1].'-01';
        $data_ontem = date('Y-m-d', strtotime("-1 days",strtotime($consulta_data)));
        $consulta_data_ontem = explode("-", $data_ontem);
        $consulta_data_ontem = $consulta_data_ontem[0].'-'.$consulta_data_ontem[1].'-01';
        $data_ontem = date('Y-m-d', strtotime("-1 days",strtotime($data)));

        $dia_hoje = date('w', strtotime($data));
        $dia_ontem = date('w', strtotime($data_ontem));

        $dia_hoje = $dias_semana[$dia_hoje];
        $dia_ontem = $dias_semana[$dia_ontem];
        $numero_dia_semana = date('w', strtotime($data));
        $hora = 0;
        $contador_dias_semana_escala[$numero_dia_semana]++;

        $hora_array = 0;

        while ($hora < 24) {
            $hora_zero = sprintf('%02d', $hora);
            $hora_proxima = sprintf('%02d', $hora_zero+1);  
            $cont_atendentes_central = 0;
            $cont_atendentes_escala = 0;
            $data_de_consulta = date('Y-m-d', strtotime($data));
            $diasemana_numero = date('w', strtotime($data_de_consulta));

            //início codigo atendentes escala 00
                
                //VERIFICA O DIA DA CONSULTA PARA PESQUISAR (SEG A SEX, SÁBADO OU DOMINGO)
                $consulta_data = explode("-", $data);
                $consulta_data = $consulta_data[0].'-'.$consulta_data[1].'-01';       

                //VERIFICA O DIA DA CONSULTA PARA PESQUISAR (SEG A SEX, SÁBADO OU DOMINGO)
                if($dia_hoje == 'Domingo'){
                    $consulta_dia =  "AND inicial_dom < '".$hora_zero.":20' AND final_dom > '".$hora_zero.":00'";
                    
                    $consulta_inicial_banco = 'inicial_dom';
                    $consulta_final_banco = 'final_dom';

                    $consulta_inicial_banco_ontem = 'inicial_sab';
                    $consulta_final_banco_ontem = 'final_sab';

                    $consulta_ontem = "AND final_sab > '".$hora_zero.":00' AND inicial_sab > final_sab AND folga_seg != '".$dia_ontem."'";
                    $consulta_invertidos = "AND inicial_dom < '".$hora_zero.":20' AND inicial_dom > final_dom AND id_horarios_escala NOT IN (SELECT id_horarios_escala FROM tb_folgas_dom WHERE dia = '".$data."')";
                    $consulta_normais = "AND inicial_dom < final_dom AND id_horarios_escala NOT IN (SELECT id_horarios_escala FROM tb_folgas_dom WHERE dia = '".$data."')";

                    $dia_intervalo = 'dom';
                    $dia_intervalo_ontem = 'sab';
  
                }else if($dia_hoje == 'Sábado'){
                    $consulta_dia =  "AND inicial_sab < '".$hora_zero.":20' AND final_sab > '".$hora_zero.":00'";
                    $consulta_inicial_banco = 'inicial_sab';
                    $consulta_final_banco = 'final_sab';
                    
                    $consulta_inicial_banco_ontem = 'inicial_seg';
                    $consulta_final_banco_ontem = 'final_seg';

                    $consulta_ontem = "AND final_seg > '".$hora_zero.":00' AND inicial_seg > final_seg AND folga_seg != '".$dia_ontem."'";
                    $consulta_invertidos = "AND inicial_sab < '".$hora_zero.":30' AND inicial_sab > final_sab AND folga_seg != '".$dia_hoje."'";
                    $consulta_normais = "AND inicial_sab < final_sab AND folga_seg != '".$dia_hoje."'";

                    $dia_intervalo = 'sab';
                    $dia_intervalo_ontem = 'seg';

                }else{
                    $consulta_dia =  "AND inicial_seg < '".$hora_zero.":20' AND final_seg > '".$hora_zero.":00'";
                    $consulta_inicial_banco = 'inicial_seg';
                    $consulta_final_banco = 'final_seg';

                    if($dia_hoje == 'Quinta' || $dia_hoje == 'Sexta'){
                        $consulta_invertidos = "AND inicial_seg < '".$hora_zero.":20' AND inicial_seg > final_seg AND (folga_seg != '".$dia_hoje."' AND folga_seg != 'Quinta e Sexta')";
                        $consulta_normais = "AND inicial_seg < final_seg AND (folga_seg != '".$dia_hoje."' AND folga_seg != 'Quinta e Sexta')";
                    }else{
                        $consulta_invertidos = "AND inicial_seg < '".$hora_zero.":20' AND inicial_seg > final_seg AND folga_seg != '".$dia_hoje."'";
                        $consulta_normais = "AND inicial_seg < final_seg AND folga_seg != '".$dia_hoje."'";
                    }

                    if($dia_hoje == 'Segunda'){
                        $consulta_ontem = "AND final_dom > '".$hora_zero.":00' AND inicial_dom > final_dom AND id_horarios_escala NOT IN (SELECT id_horarios_escala FROM tb_folgas_dom WHERE dia = '".$data_ontem."')";
                        
                        $consulta_inicial_banco_ontem = 'inicial_dom';
                        $consulta_final_banco_ontem = 'final_dom';

                        $dia_intervalo_ontem = 'dom';

                    }else{
                        if($dia_hoje == 'Sexta'){
                            $consulta_ontem = "AND final_seg > '".$hora_zero.":00' AND inicial_seg > final_seg AND (folga_seg != '".$dia_ontem."' AND folga_seg != 'Quinta e Sexta')";
                        }else{
                            $consulta_ontem = "AND final_seg > '".$hora_zero.":00' AND inicial_seg > final_seg AND folga_seg != '".$dia_ontem."'";
                        }
                    
                        $consulta_inicial_banco_ontem = 'inicial_seg';
                        $consulta_final_banco_ontem = 'final_seg';

                        $dia_intervalo_ontem = 'seg';
                    }
                    $dia_intervalo = 'seg';
                }

                //NORMAIS CONSULTA   

                    $dados_normais = DBRead('', 'tb_horarios_escala', "WHERE data_inicial = '".$consulta_data."' ".$consulta_dia." ".$consulta_normais." AND atendente = 1 AND id_usuario NOT IN (SELECT id_usuario FROM tb_ferias WHERE id_usuario = id_usuario AND data_de <= '".$data."' AND data_ate >= '".$data."') AND id_usuario NOT IN (SELECT a.id_usuario FROM tb_horarios_escala a INNER JOIN tb_horarios_especiais b ON a.id_horarios_escala = b.id_horarios_escala WHERE b.dia = '".$data."')");

                    $dados_normais_especiais = DBRead('', 'tb_horarios_escala a', "INNER JOIN tb_horarios_especiais b ON a.id_horarios_escala = b.id_horarios_escala WHERE b.dia = '".$data."' AND b.inicial_especial < '".$hora_proxima.":20' AND b.final_especial > '".$hora_zero.":00' ");                                 
                
                //NORMAIS 
                if($dados_normais){
                    foreach ($dados_normais as $conteudo_normais) {
                        $inicial_hora_funcionario = explode(":", $conteudo_normais[$consulta_inicial_banco]);
                        $final_hora_funcionario = explode(":", $conteudo_normais[$consulta_final_banco]);
                        
                        if(($inicial_hora_funcionario[0] <= $hora_zero) && ($final_hora_funcionario[0] >= $hora_zero)){

                            $dados_intervalo_normais = DBRead('', 'tb_horarios_escala_intervalo', "WHERE id_horarios_escala = '".$conteudo_normais['id_horarios_escala']."' AND dia = '".$dia_intervalo."' ");
                            if(!$dados_intervalo_normais){
                                if($inicial_hora_funcionario[0] == $hora_zero && $inicial_hora_funcionario[1] != '00' && $inicial_hora_funcionario[1] <= '20'){
                                    $valor_final = converteHorasDecimal('00:'.$inicial_hora_funcionario[1])*3;
                                    $cont_atendentes_escala += 1-($valor_final);
                                }else if($final_hora_funcionario[0] == $hora_zero && $final_hora_funcionario[1] != '00' && $final_hora_funcionario[1] <= '20'){
                                    $valor_final = converteHorasDecimal('00:'.$final_hora_funcionario[1])*3;
                                    $cont_atendentes_escala += $valor_final;
                                }else{
                                    $cont_atendentes_escala += 1;
                                }   
                            }else{
                                if($dados_intervalo_normais[0]['tipo_intervalo'] == 1){
                            
                                    $data_inicio_compara = date('Y-m-d H:i:s', strtotime("+0 minutes",strtotime($conteudo_normais[$consulta_inicial_banco])));
                                    $data_fim_compara = date('Y-m-d H:i:s', strtotime("+0 minutes",strtotime($conteudo_normais[$consulta_final_banco])));
                            
                                    if($data_inicio_compara > $data_fim_compara){
                                        $data_inicio_compara = date('Y-m-d H:i:s', strtotime("-1 days",strtotime($conteudo_normais[$consulta_inicial_banco])));
                                    }
                                    
                                    $diferenca_datas = strtotime($data_fim_compara) - strtotime($data_inicio_compara);
                                    $tempo_total_trabalho = converteSegundosHoras($diferenca_datas);
                                    $tempo_total_trabalho = converteHorasDecimal($tempo_total_trabalho);
                                    $tempo_total_intervalo = converteHorasDecimal($dados_intervalo_normais[0]['tempo_intervalo']);
                                    $tempo_desconto = $tempo_total_intervalo/$tempo_total_trabalho;
                            
                                    if($inicial_hora_funcionario[0] == $hora_zero && $inicial_hora_funcionario[1] != '00' && $inicial_hora_funcionario[1] <= '20'){
                                        $valor_final = converteHorasDecimal('00:'.$inicial_hora_funcionario[1])*3;
                                        $cont_atendentes_escala += (1 - (($valor_final) - ($tempo_desconto - (1 - ($valor_final)))));
                            
                                    }else if($final_hora_funcionario[0] == $hora_zero && $final_hora_funcionario[1] != '00' && $final_hora_funcionario[1] <= '20'){
                                        $valor_final = converteHorasDecimal('00:'.$final_hora_funcionario[1])*3;
                                        $cont_atendentes_escala += ($valor_final) - ($tempo_desconto * ($valor_final));
                                    }else{
                                        $cont_atendentes_escala += 1 - $tempo_desconto;
                                    } 
                                }else{

                                    $inicial_hora_intervalo = explode(":", $dados_intervalo_normais[0]['intervalo_inicial']);
                                    $final_hora_intervalo = explode(":", $dados_intervalo_normais[0]['intervalo_final']);
                                
                                    if($inicial_hora_funcionario[0] == $hora_zero && $inicial_hora_funcionario[1] != '00' && $inicial_hora_funcionario[1] <= '20'){
                                        if($inicial_hora_intervalo[0] == $hora_zero && $final_hora_intervalo[0] == $hora_zero){
                                            if($inicial_hora_intervalo[1] <= '20' && $final_hora_intervalo[1] <= '20'){
                                                $valor_final = converteHorasDecimal('00:'.$inicial_hora_funcionario[1])*3;
                                                $desconto_intervalo = converteHorasDecimal('00:'.(20-($final_hora_intervalo[1] - $inicial_hora_intervalo[1]))*3);
                                                $cont_atendentes_escala += $desconto_intervalo - $valor_final;
                                            }else if($inicial_hora_intervalo[1] <= '20'){
                                                $desconto_intervalo = converteHorasDecimal('00:'.(20-$inicial_hora_intervalo[1])*3);
                                                $valor_final = converteHorasDecimal('00:'.$inicial_hora_funcionario[1])*3;
                                                $cont_atendentes_escala += (1 - ($valor_final)) - ($desconto_intervalo);
                                            }else{
                                                $cont_atendentes_escala += 1;
                                            }  
                                            
                                        }else if($inicial_hora_intervalo[0] == $hora_zero){
                                            if($inicial_hora_intervalo[1] <= '20'){
                                                $desconto_intervalo = converteHorasDecimal('00:'.(20-$inicial_hora_intervalo[1])*3);
                                                $valor_final = converteHorasDecimal('00:'.$inicial_hora_funcionario[1])*3;
                                                $cont_atendentes_escala += (1 - ($valor_final)) - ($desconto_intervalo);
                                            }else{
                                                $cont_atendentes_escala += 1;
                                            }
                                           
                                        }else{
                                            $valor_final = converteHorasDecimal('00:'.$inicial_hora_funcionario[1])*3;
                                            $cont_atendentes_escala += 1 - ($valor_final);
                                        }
                                    }else if($final_hora_funcionario[0] == $hora_zero && $final_hora_funcionario[1] != '00' && $final_hora_funcionario[1] <= '20'){
                                        if($inicial_hora_intervalo[0] == $hora_zero && $final_hora_intervalo[0] == $hora_zero){
                                            if($inicial_hora_intervalo[1] <= '20' && $final_hora_intervalo[1] <= '20'){
                                                $valor_final = converteHorasDecimal('00:'.$inicial_hora_funcionario[1])*3;
                                                $desconto_intervalo = converteHorasDecimal('00:'.(20-($final_hora_intervalo[1] - $inicial_hora_intervalo[1]))*3);
                                                $cont_atendentes_escala += $desconto_intervalo - $valor_final;
                                            }else if($inicial_hora_intervalo[1] <= '20'){
                                                $desconto_intervalo = converteHorasDecimal('00:'.(20-$inicial_hora_intervalo[1])*3);
                                                $valor_final = converteHorasDecimal('00:'.$inicial_hora_funcionario[1])*3;
                                                $cont_atendentes_escala += (1 - ($valor_final)) - ($desconto_intervalo);
                                            }else{
                                                $cont_atendentes_escala += 1;
                                            }
                                        }else if($final_hora_intervalo[0] == $hora_zero){
                                            if($final_hora_intervalo[1] <= '20'){
                                                $desconto_intervalo = converteHorasDecimal('00:'.(20-$final_hora_intervalo[1])*3);
                                                $valor_final = converteHorasDecimal('00:'.$final_hora_funcionario[1])*3;
                                                $cont_atendentes_escala += $valor_final - (1 -$desconto_intervalo);
                                            }else{
                                                $cont_atendentes_escala += 0;
                                            }
                                        }else{
                                            $valor_final = converteHorasDecimal('00:'.$final_hora_funcionario[1])*3;
                                            $cont_atendentes_escala += $valor_final;
                                        }
                                    }else{
                                        if($inicial_hora_intervalo[0] == $hora_zero && $final_hora_intervalo[0] == $hora_zero){
                                            if($inicial_hora_intervalo[1] <= '20' && $final_hora_intervalo[1] <= '20'){
                                                $desconto_intervalo = converteHorasDecimal('00:'.(20-($final_hora_intervalo[1] - $inicial_hora_intervalo[1]))*3);
                                                $cont_atendentes_escala += $desconto_intervalo;
                                            }else if($inicial_hora_intervalo[1] <= '20'){
                                                $desconto_intervalo = converteHorasDecimal('00:'.(20-$inicial_hora_intervalo[1])*3);
                                                $cont_atendentes_escala += 1 - $desconto_intervalo;
                                            }else if($final_hora_intervalo[1] <= '20'){
                                                $desconto_intervalo = converteHorasDecimal('00:'.(20-$final_hora_intervalo[1])*3);
                                                $cont_atendentes_escala += $desconto_intervalo;
                                            }else{
                                                $cont_atendentes_escala += 1;
                                            }  
                                        }else if($inicial_hora_intervalo[0] == $hora_zero){
                                            if($inicial_hora_intervalo[1] <= '20'){
                                                $desconto_intervalo = converteHorasDecimal('00:'.(20-$inicial_hora_intervalo[1])*3);
                                                $cont_atendentes_escala += 1 - $desconto_intervalo;
                                            }else{
                                                $cont_atendentes_escala += 1;
                                            }
                                        }else if($final_hora_intervalo[0] == $hora_zero){
                                            if($final_hora_intervalo[1] <= '20'){
                                                $desconto_intervalo = converteHorasDecimal('00:'.(20-$final_hora_intervalo[1])*3);
                                                $cont_atendentes_escala += $desconto_intervalo;
                                            }else{
                                                $cont_atendentes_escala += 1;
                                            }
                                        }else if($inicial_hora_intervalo[0] < $hora_zero && $final_hora_intervalo[0] > $hora_zero){
                                            $cont_atendentes_escala += 0;
                                        }else{
                                            $cont_atendentes_escala += 1;
                                        }
                                    }
                                }
                            }
                        }
                    }                                       
                }

                if($dados_normais_especiais){
                    foreach ($dados_normais_especiais as $conteudo_normais_especiais) {
                        $inicial_hora_funcionario = explode(":", $conteudo_normais_especiais['inicial_especial']);
                        $final_hora_funcionario = explode(":", $conteudo_normais_especiais['final_especial']);
                        
                        if(($inicial_hora_funcionario[0] <= $hora_zero) && ($final_hora_funcionario[0] >= $hora_zero)){

                            if(!$conteudo_normais_especiais['tempo_intervalo']){
                                if($inicial_hora_funcionario[0] == $hora_zero && $inicial_hora_funcionario[1] != '00' && $inicial_hora_funcionario[1] <= '20'){
                                    $valor_final = converteHorasDecimal('00:'.$inicial_hora_funcionario[1])*3;
                                    $cont_atendentes_escala += 1-($valor_final);
                                }else if($final_hora_funcionario[0] == $hora_zero && $final_hora_funcionario[1] != '00' && $final_hora_funcionario[1] <= '20'){
                                    $valor_final = converteHorasDecimal('00:'.$final_hora_funcionario[1])*3;
                                    $cont_atendentes_escala += $valor_final;
                                }else{
                                    $cont_atendentes_escala += 1;
                                }
                            }else{
                                $data_inicio_compara = date('Y-m-d H:i:s', strtotime("+0 minutes",strtotime($conteudo_normais_especiais['inicial_especial'])));
                                $data_fim_compara = date('Y-m-d H:i:s', strtotime("+0 minutes",strtotime($conteudo_normais_especiais['final_especial'])));
                            
                                if($data_inicio_compara > $data_fim_compara){
                                    $data_inicio_compara = date('Y-m-d H:i:s', strtotime("-1 days",strtotime($conteudo_normais_especiais['inicial_especial'])));
                                }
                            
                                $diferenca_datas = strtotime($data_fim_compara) - strtotime($data_inicio_compara);
                                $tempo_total_trabalho = converteSegundosHoras($diferenca_datas);
                                $tempo_total_trabalho = converteHorasDecimal($tempo_total_trabalho);
                                $tempo_total_intervalo = converteHorasDecimal($conteudo_normais_especiais['tempo_intervalo']);
                                $tempo_desconto = $tempo_total_intervalo/$tempo_total_trabalho;
                            
                                if($inicial_hora_funcionario[0] == $hora_zero && $inicial_hora_funcionario[1] != '00' && $inicial_hora_funcionario[1] <= '20'){
                                    $valor_final = converteHorasDecimal('00:'.$inicial_hora_funcionario[1])*3;
                                    $cont_atendentes_escala += (1 - ($valor_final)) - ($tempo_desconto * (1 -  ($valor_final)));
                                
                                }else if($final_hora_funcionario[0] == $hora_zero && $final_hora_funcionario[1] != '00' && $final_hora_funcionario[1] <= '20'){
                                    $valor_final = converteHorasDecimal('00:'.$final_hora_funcionario[1])*3;
                                    $cont_atendentes_escala += (($valor_final) - ($tempo_desconto * $valor_final));
                                
                                }else{
                                    $cont_atendentes_escala += 1 - $tempo_desconto;
                                }
                            }
                        }
                    }                                       
                }

                //INVERTIDOS CONSULTA
                $dados_invertidos = DBRead('', 'tb_horarios_escala', "WHERE data_inicial = '".$consulta_data."' ".$consulta_invertidos." AND atendente = 1 AND id_usuario NOT IN (SELECT id_usuario FROM tb_ferias WHERE id_usuario = id_usuario AND data_de <= '".$data."' AND data_ate >= '".$data."') AND id_usuario NOT IN (SELECT a.id_usuario FROM tb_horarios_escala a INNER JOIN tb_horarios_especiais b ON a.id_horarios_escala = b.id_horarios_escala WHERE b.dia = '".$data."')");

                $dados_invertidos_especiais = DBRead('', 'tb_horarios_escala a', "INNER JOIN tb_horarios_especiais b ON a.id_horarios_escala = b.id_horarios_escala WHERE b.dia = '".$data."' AND b.inicial_especial < '".$hora_zero.":20' AND b.inicial_especial > b.final_especial ");

                //INVERTIDOS
                if($dados_invertidos){
                    
                    foreach ($dados_invertidos as $conteudo_invertido) {
                        $inicial_hora_funcionario = explode(":", $conteudo_invertido[$consulta_inicial_banco]);

                        if($inicial_hora_funcionario[0] <= $hora_zero){
                             
                            $dados_intervalo_invertido = DBRead('', 'tb_horarios_escala_intervalo', "WHERE id_horarios_escala = '".$conteudo_invertido['id_horarios_escala']."' AND dia = '".$dia_intervalo."' ");
                            if(!$dados_intervalo_invertido){
                                if($inicial_hora_funcionario[0] == $hora_zero && $inicial_hora_funcionario[1] != '00' && $inicial_hora_funcionario[1] <= '20'){
                                    $valor_final = converteHorasDecimal('00:'.$inicial_hora_funcionario[1])*3;
                                    $cont_atendentes_escala += 1-($valor_final);
                                }else{
                                    $cont_atendentes_escala += 1;
                                } 
                            }else{
                                if($dados_intervalo_invertido[0]['tipo_intervalo'] == 1){
                            
                                    $data_inicio_compara = date('Y-m-d H:i:s', strtotime("+0 minutes",strtotime($conteudo_invertido[$consulta_inicial_banco])));
                                    $data_fim_compara = date('Y-m-d H:i:s', strtotime("+0 minutes",strtotime($conteudo_invertido[$consulta_final_banco])));
                            
                                    if($data_inicio_compara > $data_fim_compara){
                                        $data_inicio_compara = date('Y-m-d H:i:s', strtotime("-1 days",strtotime($conteudo_invertido[$consulta_inicial_banco])));
                                    }
                                    
                                    $diferenca_datas = strtotime($data_fim_compara) - strtotime($data_inicio_compara);
                                    $tempo_total_trabalho = converteSegundosHoras($diferenca_datas);
                                    $tempo_total_trabalho = converteHorasDecimal($tempo_total_trabalho);
                                    $tempo_total_intervalo = converteHorasDecimal($dados_intervalo_invertido[0]['tempo_intervalo']);
                                    $tempo_desconto = $tempo_total_intervalo/$tempo_total_trabalho;
                            
                                    if($inicial_hora_funcionario[0] == $hora_zero && $inicial_hora_funcionario[1] != '00' && $inicial_hora_funcionario[1] <= '20'){
                                        $valor_final = converteHorasDecimal('00:'.$inicial_hora_funcionario[1])*3;
                                        $cont_atendentes_escala += (1 - (($valor_final) - ($tempo_desconto - (1 - ($valor_final)))));
                                    }else{
                                        $cont_atendentes_escala += 1 - $tempo_desconto;
                                    } 
                                }else{

                                    $inicial_hora_intervalo = explode(":", $dados_intervalo_invertido[0]['intervalo_inicial']);
                                    $final_hora_intervalo = explode(":", $dados_intervalo_invertido[0]['intervalo_final']);
                                
                                    if($inicial_hora_funcionario[0] == $hora_zero && $inicial_hora_funcionario[1] != '00' && $inicial_hora_funcionario[1] <= '20'){
                                        if($inicial_hora_intervalo[0] == $hora_zero && $final_hora_intervalo[0] == $hora_zero){
                                            if($inicial_hora_intervalo[1] <= '20' && $final_hora_intervalo[1] <= '20'){
                                                $valor_final = converteHorasDecimal('00:'.$inicial_hora_funcionario[1])*3;
                                                $desconto_intervalo = converteHorasDecimal('00:'.(20-($final_hora_intervalo[1] - $inicial_hora_intervalo[1]))*3);
                                                $cont_atendentes_escala += $desconto_intervalo - $valor_final;
                                            }else if($inicial_hora_intervalo[1] <= '20'){
                                                $desconto_intervalo = converteHorasDecimal('00:'.(20-$inicial_hora_intervalo[1])*3);
                                                $valor_final = converteHorasDecimal('00:'.$inicial_hora_funcionario[1])*3;
                                                $cont_atendentes_escala += (1 - ($valor_final)) - ($desconto_intervalo);
                                            }else{
                                                $cont_atendentes_escala += 1;
                                            }  
                                            
                                        }else if($inicial_hora_intervalo[0] == $hora_zero){
                                            if($inicial_hora_intervalo[1] <= '20'){
                                                $desconto_intervalo = converteHorasDecimal('00:'.(20-$inicial_hora_intervalo[1])*3);
                                                $valor_final = converteHorasDecimal('00:'.$inicial_hora_funcionario[1])*3;
                                                $cont_atendentes_escala += (1 - ($valor_final)) - ($desconto_intervalo);
                                            }else{
                                                $cont_atendentes_escala += 1;
                                            }
                                           
                                        }else{
                                            $valor_final = converteHorasDecimal('00:'.$inicial_hora_funcionario[1])*3;
                                            $cont_atendentes_escala += 1 - ($valor_final);
                                        }
                                    }else{
                                        if($inicial_hora_intervalo[0] == $hora_zero && $final_hora_intervalo[0] == $hora_zero){
                                            if($inicial_hora_intervalo[1] <= '20' && $final_hora_intervalo[1] <= '20'){
                                                $desconto_intervalo = converteHorasDecimal('00:'.(20-($final_hora_intervalo[1] - $inicial_hora_intervalo[1]))*3);
                                                $cont_atendentes_escala += $desconto_intervalo;
                                            }else if($inicial_hora_intervalo[1] <= '20'){
                                                $desconto_intervalo = converteHorasDecimal('00:'.(20-$inicial_hora_intervalo[1])*3);
                                                $cont_atendentes_escala += 1 - $desconto_intervalo;
                                            }else if($final_hora_intervalo[1] <= '20'){
                                                $desconto_intervalo = converteHorasDecimal('00:'.(20-$final_hora_intervalo[1])*3);
                                                $cont_atendentes_escala += $desconto_intervalo;
                                            }else{
                                                $cont_atendentes_escala += 1;
                                            }  
                                        }else if($inicial_hora_intervalo[0] == $hora_zero){
                                            if($inicial_hora_intervalo[1] <= '20'){
                                                $desconto_intervalo = converteHorasDecimal('00:'.(20-$inicial_hora_intervalo[1])*3);
                                                $cont_atendentes_escala += 1 - $desconto_intervalo;
                                            }else{
                                                $cont_atendentes_escala += 1;
                                            }
                                        }else if($final_hora_intervalo[0] == $hora_zero){
                                            if($final_hora_intervalo[1] <= '20'){
                                                $desconto_intervalo = converteHorasDecimal('00:'.(20-$final_hora_intervalo[1])*3);
                                                $cont_atendentes_escala += $desconto_intervalo;
                                            }else{
                                                $cont_atendentes_escala += 1;
                                            }
                                        }else if($inicial_hora_intervalo[0] < $hora_zero && $final_hora_intervalo[0] > $hora_zero){
                                            $cont_atendentes_escala += 0;
                                        }else{
                                            $cont_atendentes_escala += 1;
                                        }
                                    }
                                }
                            }
                        }
                    }
                }

                if($dados_invertidos_especiais){
                    
                    foreach ($dados_invertidos_especiais as $conteudo_invertidos_especiais) {
                        $inicial_hora_funcionario = explode(":", $conteudo_invertidos_especiais['inicial_especial']);

                        if($inicial_hora_funcionario[0] <= $hora_zero){
                                
                            if(!$conteudo_invertidos_especiais['tempo_intervalo']){
                                if($inicial_hora_funcionario[0] == $hora_zero && $inicial_hora_funcionario[1] != '00' && $inicial_hora_funcionario[1] <= '20'){
                                    $valor_final = converteHorasDecimal('00:'.$inicial_hora_funcionario[1])*3;
                                    $cont_atendentes_escala += 1-($valor_final);
                                }else{
                                    $cont_atendentes_escala += 1;
                                }
                            }else{
                                $data_inicio_compara = date('Y-m-d H:i:s', strtotime("+0 minutes",strtotime($conteudo_invertidos_especiais['inicial_especial'])));
                                $data_fim_compara = date('Y-m-d H:i:s', strtotime("+0 minutes",strtotime($conteudo_invertidos_especiais['final_especial'])));
                            
                                if($data_inicio_compara > $data_fim_compara){
                                    $data_inicio_compara = date('Y-m-d H:i:s', strtotime("-1 days",strtotime($conteudo_invertidos_especiais['inicial_especial'])));
                                }
                            
                                $diferenca_datas = strtotime($data_fim_compara) - strtotime($data_inicio_compara);
                                $tempo_total_trabalho = converteSegundosHoras($diferenca_datas);
                                $tempo_total_trabalho = converteHorasDecimal($tempo_total_trabalho);
                                $tempo_total_intervalo = converteHorasDecimal($conteudo_invertidos_especiais['tempo_intervalo']);
                                $tempo_desconto = $tempo_total_intervalo/$tempo_total_trabalho;
                            
                                if($inicial_hora_funcionario[0] == $hora_zero && $inicial_hora_funcionario[1] != '00' && $inicial_hora_funcionario[1] <= '20'){
                                    $valor_final = converteHorasDecimal('00:'.$inicial_hora_funcionario[1])*3;
                                    $cont_atendentes_escala += (1 - ($valor_final)) - ($tempo_desconto * (1 -  ($valor_final)));
                                }else{
                                    $cont_atendentes_escala += 1 - $tempo_desconto;
                                }
                            }
                        }
                    }
                }

                if($data == $consulta_data){
                    $consulta_data = $consulta_data_ontem;
                }

                //INVERTIDOS ONTEM CONSULTA
                    $dados_invertidos_ontem = DBRead('', 'tb_horarios_escala', "WHERE data_inicial = '".$consulta_data."' ".$consulta_ontem." AND atendente = 1 AND id_usuario NOT IN (SELECT id_usuario FROM tb_ferias WHERE id_usuario = id_usuario AND data_de <= '".$data_ontem."' AND data_ate >= '".$data_ontem."') AND id_usuario NOT IN (SELECT a.id_usuario FROM tb_horarios_escala a INNER JOIN tb_horarios_especiais b ON a.id_horarios_escala = b.id_horarios_escala WHERE b.dia = '".$data_ontem."')");
                
                    $dados_invertidos_ontem_especiais =  DBRead('', 'tb_horarios_escala a', "INNER JOIN tb_horarios_especiais b ON a.id_horarios_escala = b.id_horarios_escala WHERE b.dia = '".$data_ontem."' AND b.final_especial >= '".$hora_zero.":00' AND b.inicial_especial > b.final_especial ");
               
               //INVERTIDOS ONTEM
                if($dados_invertidos_ontem){

                    foreach ($dados_invertidos_ontem as $conteudo_ontem) {
                        $final_hora_funcionario = explode(":", $conteudo_ontem[$consulta_final_banco_ontem]);

                        if($final_hora_funcionario[0] >= $hora_zero){

                            $dados_intervalo_invertido_ontem = DBRead('', 'tb_horarios_escala_intervalo', "WHERE id_horarios_escala = '".$conteudo_ontem['id_horarios_escala']."' AND dia = '".$dia_intervalo_ontem."' ");
                            if(!$dados_intervalo_invertido_ontem){
                                if($final_hora_funcionario[0] == $hora_zero && $final_hora_funcionario[1] != '00' && $final_hora_funcionario[1] <= '20'){
                                    $valor_final = converteHorasDecimal('00:'.$final_hora_funcionario[1])*3;
                                    $cont_atendentes_escala += $valor_final;
                                }else{
                                    $cont_atendentes_escala += 1;
                                }    
                            }else{
                                if($dados_intervalo_invertido_ontem[0]['tipo_intervalo'] == 1){
                            
                                    $data_inicio_compara = date('Y-m-d H:i:s', strtotime("+0 minutes",strtotime($conteudo_ontem[$consulta_inicial_banco_ontem])));
                                    $data_fim_compara = date('Y-m-d H:i:s', strtotime("+0 minutes",strtotime($conteudo_ontem[$consulta_final_banco_ontem])));
                            
                                    if($data_inicio_compara > $data_fim_compara){
                                        $data_inicio_compara = date('Y-m-d H:i:s', strtotime("-1 days",strtotime($conteudo_ontem[$consulta_inicial_banco_ontem])));
                                    }
                                    
                                    $diferenca_datas = strtotime($data_fim_compara) - strtotime($data_inicio_compara);
                                    $tempo_total_trabalho = converteSegundosHoras($diferenca_datas);
                                    $tempo_total_trabalho = converteHorasDecimal($tempo_total_trabalho);
                                    $tempo_total_intervalo = converteHorasDecimal($dados_intervalo_invertido_ontem[0]['tempo_intervalo']);
                                    $tempo_desconto = $tempo_total_intervalo/$tempo_total_trabalho;
                            
                                    if($final_hora_funcionario[0] == $hora_zero && $final_hora_funcionario[1] != '00' && $final_hora_funcionario[1] <= '20'){
                                        $valor_final = converteHorasDecimal('00:'.$final_hora_funcionario[1])*3;
                                        $cont_atendentes_escala += ($valor_final) - ($tempo_desconto * ($valor_final));
                                    }else{
                                        $cont_atendentes_escala += 1 - $tempo_desconto;
                                    } 
                                }else{

                                    $inicial_hora_intervalo = explode(":", $dados_intervalo_invertido_ontem[0]['intervalo_inicial']);
                                    $final_hora_intervalo = explode(":", $dados_intervalo_invertido_ontem[0]['intervalo_final']);
                                
                                    if($final_hora_funcionario[0] == $hora_zero && $final_hora_funcionario[1] != '00' && $final_hora_funcionario[1] <= '20'){
                                        if($inicial_hora_intervalo[0] == $hora_zero && $final_hora_intervalo[0] == $hora_zero){
                                            if($inicial_hora_intervalo[1] <= '20' && $final_hora_intervalo[1] <= '20'){
                                                $valor_final = converteHorasDecimal('00:'.$inicial_hora_funcionario[1])*3;
                                                $desconto_intervalo = converteHorasDecimal('00:'.(20-($final_hora_intervalo[1] - $inicial_hora_intervalo[1]))*3);
                                                $cont_atendentes_escala += $desconto_intervalo - $valor_final;
                                            }else if($inicial_hora_intervalo[1] <= '20'){
                                                $desconto_intervalo = converteHorasDecimal('00:'.(20-$inicial_hora_intervalo[1])*3);
                                                $valor_final = converteHorasDecimal('00:'.$inicial_hora_funcionario[1])*3;
                                                $cont_atendentes_escala += (1 - ($valor_final)) - ($desconto_intervalo);
                                            }else{
                                                $cont_atendentes_escala += 1;
                                            }
                                        }else if($final_hora_intervalo[0] == $hora_zero){
                                            if($final_hora_intervalo[1] <= '20'){
                                                $desconto_intervalo = converteHorasDecimal('00:'.(20-$final_hora_intervalo[1])*3);
                                                $valor_final = converteHorasDecimal('00:'.$final_hora_funcionario[1])*3;
                                                $cont_atendentes_escala += $valor_final - (1 -$desconto_intervalo);
                                            }else{
                                                $cont_atendentes_escala += 0;
                                            }
                                        }else{
                                            $valor_final = converteHorasDecimal('00:'.$final_hora_funcionario[1])*3;
                                            $cont_atendentes_escala += $valor_final;
                                        }
                                    }else{
                                        if($inicial_hora_intervalo[0] == $hora_zero && $final_hora_intervalo[0] == $hora_zero){
                                            if($inicial_hora_intervalo[1] <= '20' && $final_hora_intervalo[1] <= '20'){
                                                $desconto_intervalo = converteHorasDecimal('00:'.(20-($final_hora_intervalo[1] - $inicial_hora_intervalo[1]))*3);
                                                $cont_atendentes_escala += $desconto_intervalo;
                                            }else if($inicial_hora_intervalo[1] <= '20'){
                                                $desconto_intervalo = converteHorasDecimal('00:'.(20-$inicial_hora_intervalo[1])*3);
                                                $cont_atendentes_escala += 1 - $desconto_intervalo;
                                            }else if($final_hora_intervalo[1] <= '20'){
                                                $desconto_intervalo = converteHorasDecimal('00:'.(20-$final_hora_intervalo[1])*3);
                                                $cont_atendentes_escala += $desconto_intervalo;
                                            }else{
                                                $cont_atendentes_escala += 1;
                                            }  
                                        }else if($inicial_hora_intervalo[0] == $hora_zero){
                                            if($inicial_hora_intervalo[1] <= '20'){
                                                $desconto_intervalo = converteHorasDecimal('00:'.(20-$inicial_hora_intervalo[1])*3);
                                                $cont_atendentes_escala += 1 - $desconto_intervalo;
                                            }else{
                                                $cont_atendentes_escala += 1;
                                            }
                                        }else if($final_hora_intervalo[0] == $hora_zero){
                                            if($final_hora_intervalo[1] <= '20'){
                                                $desconto_intervalo = converteHorasDecimal('00:'.(20-$final_hora_intervalo[1])*3);
                                                $cont_atendentes_escala += $desconto_intervalo;
                                            }else{
                                                $cont_atendentes_escala += 1;
                                            }
                                        }else if($inicial_hora_intervalo[0] < $hora_zero && $final_hora_intervalo[0] > $hora_zero){
                                            $cont_atendentes_escala += 0;
                                        }else{
                                            $cont_atendentes_escala += 1;
                                        }
                                    }
                                }
                            }                            
                        }
                    }
                }

                if($dados_invertidos_ontem_especiais){
                    foreach ($dados_invertidos_ontem_especiais as $conteudo_invertidos_ontem_especiais) {
                        $final_hora_funcionario = explode(":", $conteudo_invertidos_ontem_especiais['final_especial']);
                        if($final_hora_funcionario[0] >= $hora_zero){
                            
                            if(!$conteudo_invertidos_ontem_especiais['tempo_intervalo']){
                                if($final_hora_funcionario[0] == $hora_zero && $final_hora_funcionario[1] != '00'&& $final_hora_funcionario[1] <= '20'){
                                    $valor_final = converteHorasDecimal('00:'.$final_hora_funcionario[1])*3;
                                    $cont_atendentes_escala += $valor_final;
                                }else{
                                    $cont_atendentes_escala += 1;
                                }
                            }else{
                                $data_inicio_compara = date('Y-m-d H:i:s', strtotime("+0 minutes",strtotime($conteudo_invertidos_ontem_especiais['inicial_especial'])));
                                $data_fim_compara = date('Y-m-d H:i:s', strtotime("+0 minutes",strtotime($conteudo_invertidos_ontem_especiais['final_especial'])));
                            
                                if($data_inicio_compara > $data_fim_compara){
                                    $data_inicio_compara = date('Y-m-d H:i:s', strtotime("-1 days",strtotime($conteudo_invertidos_ontem_especiais['inicial_especial'])));
                                }
                            
                                $diferenca_datas = strtotime($data_fim_compara) - strtotime($data_inicio_compara);
                                $tempo_total_trabalho = converteSegundosHoras($diferenca_datas);
                                $tempo_total_trabalho = converteHorasDecimal($tempo_total_trabalho);
                                $tempo_total_intervalo = converteHorasDecimal($conteudo_invertidos_ontem_especiais['tempo_intervalo']);
                                $tempo_desconto = $tempo_total_intervalo/$tempo_total_trabalho;
                            
                                if($final_hora_funcionario[0] == $hora_zero && $final_hora_funcionario[1] != '00' && $final_hora_funcionario[1] <= '20'){
                                    $valor_final = converteHorasDecimal('00:'.$final_hora_funcionario[1])*3;
                                    $cont_atendentes_escala += (($valor_final) - ($tempo_desconto * $valor_final));
                                }else{
                                    $cont_atendentes_escala += 1 - $tempo_desconto;
                                }
                            }
                        }
                    }
                }

                if(!$cont_atendentes_escala){
                    $cont_atendentes_escala = 0;
                }  
                

            //fim codigo atendentes escala 00
                
                $atendentes_escala[$diasemana_numero][$hora_array] += sprintf("%01.2f", round($cont_atendentes_escala, 2));
                $cont_atendentes_escala = 0;

            //início codigo atendentes escala 20
                
                $consulta_data = explode("-", $data);
                $consulta_data = $consulta_data[0].'-'.$consulta_data[1].'-01';
                //VERIFICA O DIA DA CONSULTA PARA PESQUISAR (SEG A SEX, SÁBADO OU DOMINGO)
                if($dia_hoje == 'Domingo'){
                    $consulta_dia =  "AND inicial_dom < '".$hora_zero.":40' AND final_dom > '".$hora_zero.":20'";
                    
                    $consulta_inicial_banco = 'inicial_dom';
                    $consulta_final_banco = 'final_dom';

                    $consulta_inicial_banco_ontem = 'inicial_sab';
                    $consulta_final_banco_ontem = 'final_sab';

                    $consulta_ontem = "AND final_sab > '".$hora_zero.":20' AND inicial_sab > final_sab AND folga_seg != '".$dia_ontem."'";
                    $consulta_invertidos = "AND inicial_dom < '".$hora_zero.":40' AND inicial_dom > final_dom AND id_horarios_escala NOT IN (SELECT id_horarios_escala FROM tb_folgas_dom WHERE dia = '".$data."')";
                    $consulta_normais = "AND inicial_dom < final_dom AND id_horarios_escala NOT IN (SELECT id_horarios_escala FROM tb_folgas_dom WHERE dia = '".$data."')";

                    $dia_intervalo = 'dom';
                    $dia_intervalo_ontem = 'sab';

                }else if($dia_hoje == 'Sábado'){
                    $consulta_dia =  "AND inicial_sab < '".$hora_zero.":40' AND final_sab > '".$hora_zero.":20'";
                    $consulta_inicial_banco = 'inicial_sab';
                    $consulta_final_banco = 'final_sab';
                    
                    $consulta_inicial_banco_ontem = 'inicial_seg';
                    $consulta_final_banco_ontem = 'final_seg';

                    $consulta_ontem = "AND final_seg > '".$hora_zero.":20' AND inicial_seg > final_seg AND folga_seg != '".$dia_ontem."'";
                    $consulta_invertidos = "AND inicial_sab < '".$hora_zero.":40' AND inicial_sab > final_sab AND folga_seg != '".$dia_hoje."'";
                    $consulta_normais = "AND inicial_sab < final_sab AND folga_seg != '".$dia_hoje."'";

                    $dia_intervalo = 'sab';
                    $dia_intervalo_ontem = 'seg';
                
                }else{
                    $consulta_dia =  "AND inicial_seg < '".$hora_zero.":40' AND final_seg > '".$hora_zero.":20'";
                    $consulta_inicial_banco = 'inicial_seg';
                    $consulta_final_banco = 'final_seg';

                    if($dia_hoje == 'Quinta' || $dia_hoje == 'Sexta'){
                        $consulta_invertidos = "AND inicial_seg < '".$hora_zero.":40' AND inicial_seg > final_seg AND (folga_seg != '".$dia_hoje."' AND folga_seg != 'Quinta e Sexta')";
                        $consulta_normais = "AND inicial_seg < final_seg AND (folga_seg != '".$dia_hoje."' AND folga_seg != 'Quinta e Sexta')";
                    }else{
                        $consulta_invertidos = "AND inicial_seg < '".$hora_zero.":40' AND inicial_seg > final_seg AND folga_seg != '".$dia_hoje."'";
                        $consulta_normais = "AND inicial_seg < final_seg AND folga_seg != '".$dia_hoje."'";
                    }

                    if($dia_hoje == 'Segunda'){
                        $consulta_ontem = "AND final_dom > '".$hora_zero.":20' AND inicial_dom > final_dom AND id_horarios_escala NOT IN (SELECT id_horarios_escala FROM tb_folgas_dom WHERE dia = '".$data_ontem."')";
                        
                        $consulta_inicial_banco_ontem = 'inicial_dom';
                        $consulta_final_banco_ontem = 'final_dom';
                        $dia_intervalo_ontem = 'dom';

                    }else{
                        if($dia_hoje == 'Sexta'){
                            $consulta_ontem = "AND final_seg > '".$hora_zero.":20' AND inicial_seg > final_seg AND (folga_seg != '".$dia_ontem."' AND folga_seg != 'Quinta e Sexta')";
                        }else{
                            $consulta_ontem = "AND final_seg > '".$hora_zero.":20' AND inicial_seg > final_seg AND folga_seg != '".$dia_ontem."'";
                        }
                    
                        $consulta_inicial_banco_ontem = 'inicial_seg';
                        $consulta_final_banco_ontem = 'final_seg';
                        $dia_intervalo_ontem = 'seg';
                    }
                    $dia_intervalo = 'seg';
                }

                //NORMAIS CONSULTA   
                    $dados_normais = DBRead('', 'tb_horarios_escala', "WHERE data_inicial = '".$consulta_data."' ".$consulta_dia." ".$consulta_normais." AND atendente = 1 AND id_usuario NOT IN (SELECT id_usuario FROM tb_ferias WHERE id_usuario = id_usuario AND data_de <= '".$data."' AND data_ate >= '".$data."') AND id_usuario NOT IN (SELECT a.id_usuario FROM tb_horarios_escala a INNER JOIN tb_horarios_especiais b ON a.id_horarios_escala = b.id_horarios_escala WHERE b.dia = '".$data."')");

                    $dados_normais_especiais = DBRead('', 'tb_horarios_escala a', "INNER JOIN tb_horarios_especiais b ON a.id_horarios_escala = b.id_horarios_escala WHERE b.dia = '".$data."' AND b.inicial_especial < '".$hora_zero.":40' AND b.final_especial > '".$hora_zero.":20' ");              
                                    
                //NORMAIS 
                if($dados_normais){
                    foreach ($dados_normais as $conteudo_normais) {
                        $inicial_hora_funcionario = explode(":", $conteudo_normais[$consulta_inicial_banco]);
                        $final_hora_funcionario = explode(":", $conteudo_normais[$consulta_final_banco]);
                        
                        if(($inicial_hora_funcionario[0] <= $hora_zero) && ($final_hora_funcionario[0] >= $hora_zero)){

                            $dados_intervalo_normais = DBRead('', 'tb_horarios_escala_intervalo', "WHERE id_horarios_escala = '".$conteudo_normais['id_horarios_escala']."' AND dia = '".$dia_intervalo."' ");
                            if(!$dados_intervalo_normais){
                                if($inicial_hora_funcionario[0] == $hora_zero && $inicial_hora_funcionario[1] >= '20' && $inicial_hora_funcionario[1] < '40'){
                                    $valor_final = converteHorasDecimal('00:'.(($inicial_hora_funcionario[1]-20)*3));
                                    $cont_atendentes_escala += 1-($valor_final);
                                }else if($final_hora_funcionario[0] == $hora_zero && $final_hora_funcionario[1] >= '20' && $final_hora_funcionario[1] < '40'){
                                    $valor_final = converteHorasDecimal('00:'.(($final_hora_funcionario[1]-20)*3));
                                    $cont_atendentes_escala += $valor_final;
                                }else{
                                    $cont_atendentes_escala += 1;
                                }       
                            }else{
                                if($dados_intervalo_normais[0]['tipo_intervalo'] == 1){

                                    $data_inicio_compara = date('Y-m-d H:i:s', strtotime("+0 minutes",strtotime($conteudo_normais[$consulta_inicial_banco])));
                                    $data_fim_compara = date('Y-m-d H:i:s', strtotime("+0 minutes",strtotime($conteudo_normais[$consulta_final_banco])));
                            
                                    if($data_inicio_compara > $data_fim_compara){
                                        $data_inicio_compara = date('Y-m-d H:i:s', strtotime("-1 days",strtotime($conteudo_normais[$consulta_inicial_banco])));
                                    }
                                    
                                    $diferenca_datas = strtotime($data_fim_compara) - strtotime($data_inicio_compara);
                                    $tempo_total_trabalho = converteSegundosHoras($diferenca_datas);
                                    $tempo_total_trabalho = converteHorasDecimal($tempo_total_trabalho);
                                    $tempo_total_intervalo = converteHorasDecimal($dados_intervalo_normais[0]['tempo_intervalo']);
                                    $tempo_desconto = $tempo_total_intervalo/$tempo_total_trabalho;
                            
                                    if($inicial_hora_funcionario[0] == $hora_zero && $inicial_hora_funcionario[1] != '00' && $inicial_hora_funcionario[1] >= '20' && $inicial_hora_funcionario[1] < '40'){
                                        $valor_final = converteHorasDecimal('00:'.(($inicial_hora_funcionario[1]-20)*3));
                                        $cont_atendentes_escala += (1 - (($valor_final) - ($tempo_desconto - (1 - ($valor_final)))));
                            
                                    }else if($final_hora_funcionario[0] == $hora_zero && $final_hora_funcionario[1] != '00' && $final_hora_funcionario[1] >= '20' && $final_hora_funcionario[1] < '40'){
                                        $valor_final = converteHorasDecimal('00:'.(($final_hora_funcionario[1]-20)*3));
                                        $cont_atendentes_escala += ($valor_final) - ($tempo_desconto * ($valor_final));
                                    }else{
                                        $cont_atendentes_escala += 1 - $tempo_desconto;
                                    } 
                                }else{

                                    $inicial_hora_intervalo = explode(":", $dados_intervalo_normais[0]['intervalo_inicial']);
                                    $final_hora_intervalo = explode(":", $dados_intervalo_normais[0]['intervalo_final']);
                                
                                    if($inicial_hora_funcionario[0] == $hora_zero && $inicial_hora_funcionario[1] >= '20' && $inicial_hora_funcionario[1] < '40'){
                                        if($inicial_hora_intervalo[0] == $hora_zero && $final_hora_intervalo[0] == $hora_zero){
                                            if($inicial_hora_intervalo[1] >= '20' && $inicial_hora_intervalo[1] < '40' && $final_hora_intervalo[1] >= '20' && $final_hora_intervalo[1] < '40'){
                                                $valor_final = converteHorasDecimal('00:'.(($inicial_hora_funcionario[1]-20)*3));
                                                $desconto_intervalo = converteHorasDecimal('00:'.(20-($final_hora_intervalo[1] - $inicial_hora_intervalo[1]))*3);
                                                $cont_atendentes_escala += $desconto_intervalo - $valor_final;
                                            }else if($inicial_hora_intervalo[1] >= '20' && $inicial_hora_intervalo[1] < '40'){
                                                $desconto_intervalo = converteHorasDecimal('00:'.(20-$inicial_hora_intervalo[1])*3);
                                                $valor_final = converteHorasDecimal('00:'.(($inicial_hora_funcionario[1]-20)*3));
                                                $cont_atendentes_escala += (1 - ($valor_final)) - ($desconto_intervalo);
                                            }else{
                                                $cont_atendentes_escala += 1;
                                            }  
                                            
                                        }else if($inicial_hora_intervalo[0] == $hora_zero){
                                            if($inicial_hora_intervalo[1] >= '20' && $inicial_hora_intervalo[1] < '40'){
                                                $desconto_intervalo = converteHorasDecimal('00:'.(20-$inicial_hora_intervalo[1])*3);
                                                $valor_final = converteHorasDecimal('00:'.(($inicial_hora_funcionario[1]-20)*3));
                                                $cont_atendentes_escala += (1 - ($valor_final)) - ($desconto_intervalo);
                                            }else{
                                                $cont_atendentes_escala += 1;
                                            }
                                           
                                        }else{
                                            $valor_final = converteHorasDecimal('00:'.(($inicial_hora_funcionario[1]-20)*3));
                                            $cont_atendentes_escala += 1 - ($valor_final);
                                        }
                                
                                    }else if($final_hora_funcionario[0] == $hora_zero  && $final_hora_funcionario[1] >= '20' && $final_hora_funcionario[1] < '40'){
                                        if($inicial_hora_intervalo[0] == $hora_zero && $final_hora_intervalo[0] == $hora_zero){
                                            if($inicial_hora_intervalo[1] >= '20' && $inicial_hora_intervalo[1] < '40' && $final_hora_intervalo[1] >= '20' && $final_hora_intervalo[1] < '40'){
                                                $valor_final = converteHorasDecimal('00:'.(($inicial_hora_funcionario[1]-20)*3));
                                                $desconto_intervalo = converteHorasDecimal('00:'.(40-($final_hora_intervalo[1] - $inicial_hora_intervalo[1]))*3);
                                                $cont_atendentes_escala += $desconto_intervalo - $valor_final;
                                            }else if($inicial_hora_intervalo[1] >= '20' && $inicial_hora_intervalo[1] < '40'){
                                                $desconto_intervalo = converteHorasDecimal('00:'.(40-$inicial_hora_intervalo[1])*3);
                                                $valor_final = converteHorasDecimal('00:'.$inicial_hora_funcionario[1])*3;
                                                $cont_atendentes_escala += (1 - ($valor_final)) - ($desconto_intervalo);
                                            }else if($final_hora_intervalo[1] >= '20' && $final_hora_intervalo[1] < '40'){
                                                $desconto_intervalo = converteHorasDecimal('00:'.(40-$final_hora_intervalo[1])*3);
                                                $valor_final = converteHorasDecimal('00:'.(($final_hora_funcionario[1]-20)*3));
                                                $cont_atendentes_escala += $valor_final - (1 -$desconto_intervalo);
                                            }else{
                                                $cont_atendentes_escala += 1;
                                            }
                                        }else if($final_hora_intervalo[0] == $hora_zero){
                                            if($final_hora_intervalo[1] >= '20' && $final_hora_intervalo[1] < '40'){
                                                $desconto_intervalo = converteHorasDecimal('00:'.(40-$final_hora_intervalo[1])*3);
                                                $valor_final = converteHorasDecimal('00:'.(($final_hora_funcionario[1]-20)*3));
                                                $cont_atendentes_escala += $valor_final - (1 -$desconto_intervalo);
                                            }else{
                                                $cont_atendentes_escala += 0;
                                            }
                                        }else{
                                            $valor_final = converteHorasDecimal('00:'.(($final_hora_funcionario[1]-20)*3));
                                            $cont_atendentes_escala += $valor_final;
                                        }
                                
                                    }else{
                                        if($inicial_hora_intervalo[0] == $hora_zero && $final_hora_intervalo[0] == $hora_zero){
                                            if($inicial_hora_intervalo[1] >= '20' && $inicial_hora_intervalo[1] < '40' && $final_hora_intervalo[1] >= '20' && $final_hora_intervalo[1] < '40'){
                                                $desconto_intervalo = converteHorasDecimal('00:'.(40-($final_hora_intervalo[1] - $inicial_hora_intervalo[1]))*3);
                                                $cont_atendentes_escala += $desconto_intervalo;
                                            }else if($inicial_hora_intervalo[1] >= '20' && $inicial_hora_intervalo[1] < '40'){
                                                $desconto_intervalo = converteHorasDecimal('00:'.(40-$inicial_hora_intervalo[1])*3);
                                                $cont_atendentes_escala += 1 - $desconto_intervalo;
                                            }else if($final_hora_intervalo[1] >= '20' && $final_hora_intervalo[1] < '40'){
                                                $desconto_intervalo = converteHorasDecimal('00:'.(40-$final_hora_intervalo[1])*3);
                                                $cont_atendentes_escala += $desconto_intervalo;
                                            }else if($final_hora_intervalo[1] >= '40'){
                                                $cont_atendentes_escala += 0;
                                            }else{
                                                $cont_atendentes_escala += 1;
                                            }  
                                        }else if($inicial_hora_intervalo[0] == $hora_zero){
                                            if($inicial_hora_intervalo[1] >= '20' && $inicial_hora_intervalo[1] < '40'){
                                                $desconto_intervalo = converteHorasDecimal('00:'.(40-$inicial_hora_intervalo[1])*3);
                                                $cont_atendentes_escala += 1 - $desconto_intervalo;
                                            }else{
                                                $cont_atendentes_escala += 1;
                                            }
                                        }else if($final_hora_intervalo[0] == $hora_zero){
                                            if($final_hora_intervalo[1] >= '20' && $final_hora_intervalo[1] < '40'){
                                                $desconto_intervalo = converteHorasDecimal('00:'.(40-$final_hora_intervalo[1])*3);
                                                $cont_atendentes_escala += $desconto_intervalo;
                                            }else if($final_hora_intervalo[1] >= '40'){
                                                $cont_atendentes_escala += 0;
                                            }else{
                                                $cont_atendentes_escala += 1;
                                            }
                                        }else if(($inicial_hora_intervalo[0] < $hora_zero && $final_hora_intervalo[0] > $hora_zero) ){
                                            $cont_atendentes_escala += 0;
                                        }else{
                                            $cont_atendentes_escala += 1;
                                        }
                                    }
                                }
                            }                            
                        }
                    }                                       
                }

                if($dados_normais_especiais){
                    foreach ($dados_normais_especiais as $conteudo_normais_especiais) {
                        $inicial_hora_funcionario = explode(":", $conteudo_normais_especiais['inicial_especial']);
                        $final_hora_funcionario = explode(":", $conteudo_normais_especiais['final_especial']);
                        
                        if(($inicial_hora_funcionario[0] <= $hora_zero) && ($final_hora_funcionario[0] >= $hora_zero)){
                            
                            if(!$conteudo_normais_especiais['tempo_intervalo']){
                                if($inicial_hora_funcionario[0] == $hora_zero && $inicial_hora_funcionario[1] >= '20' && $inicial_hora_funcionario[1] < '40'){
                                    $valor_final = converteHorasDecimal('00:'.(($inicial_hora_funcionario[1]-20)*3));
                                    $cont_atendentes_escala += 1-($valor_final);
                                }else if($final_hora_funcionario[0] == $hora_zero && $inicial_hora_funcionario[1] >= '20' && $inicial_hora_funcionario[1] < '40'){
                                    $valor_final = converteHorasDecimal('00:'.(($final_hora_funcionario[1]-20)*3));
                                    $cont_atendentes_escala += $valor_final;
                                }else{
                                    $cont_atendentes_escala += 1;
                                }
                            }else{
                                $data_inicio_compara = date('Y-m-d H:i:s', strtotime("+0 minutes",strtotime($conteudo_normais_especiais['inicial_especial'])));
                                $data_fim_compara = date('Y-m-d H:i:s', strtotime("+0 minutes",strtotime($conteudo_normais_especiais['final_especial'])));
                            
                                if($data_inicio_compara > $data_fim_compara){
                                    $data_inicio_compara = date('Y-m-d H:i:s', strtotime("-1 days",strtotime($conteudo_normais_especiais['inicial_especial'])));
                                }
                            
                                $diferenca_datas = strtotime($data_fim_compara) - strtotime($data_inicio_compara);
                                
                                $tempo_total_trabalho = converteSegundosHoras($diferenca_datas);
                                $tempo_total_trabalho = converteHorasDecimal($tempo_total_trabalho);
                                $tempo_total_intervalo = converteHorasDecimal($conteudo_normais_especiais['tempo_intervalo']);
                                $tempo_desconto = $tempo_total_intervalo/$tempo_total_trabalho;
                            
                                if($inicial_hora_funcionario[0] == $hora_zero && $inicial_hora_funcionario[1] >= '20' && $inicial_hora_funcionario[1] < '40'){
                                    $valor_final = converteHorasDecimal('00:'.(($inicial_hora_funcionario[1]-20)*3));
                                    $cont_atendentes_escala += (1 - ($valor_final)) - ($tempo_desconto * (1 -  ($valor_final)));
                                }else if($final_hora_funcionario[0] == $hora_zero && $inicial_hora_funcionario[1] >= '20' && $inicial_hora_funcionario[1] < '40'){
                                    $valor_final = converteHorasDecimal('00:'.(($final_hora_funcionario[1]-20)*3));
                                    $cont_atendentes_escala += (($valor_final) - ($tempo_desconto * $valor_final));
                                }else{
                                    $cont_atendentes_escala += 1 - $tempo_desconto;
                                }
                            }
                        }
                    }                                       
                }

                //INVERTIDOS CONSULTA
                    $dados_invertidos = DBRead('', 'tb_horarios_escala', "WHERE data_inicial = '".$consulta_data."' ".$consulta_invertidos." AND atendente = 1 AND id_usuario NOT IN (SELECT id_usuario FROM tb_ferias WHERE id_usuario = id_usuario AND data_de <= '".$data."' AND data_ate >= '".$data."') AND id_usuario NOT IN (SELECT a.id_usuario FROM tb_horarios_escala a INNER JOIN tb_horarios_especiais b ON a.id_horarios_escala = b.id_horarios_escala WHERE b.dia = '".$data."')");

                    $dados_invertidos_especiais = DBRead('', 'tb_horarios_escala a', "INNER JOIN tb_horarios_especiais b ON a.id_horarios_escala = b.id_horarios_escala WHERE b.dia = '".$data."' AND b.inicial_especial < '".$hora_zero.":40' AND b.inicial_especial > b.final_especial ");

                //INVERTIDOS
                if($dados_invertidos){
                    
                    foreach ($dados_invertidos as $conteudo_invertido) {
                        $inicial_hora_funcionario = explode(":", $conteudo_invertido[$consulta_inicial_banco]);

                        if($inicial_hora_funcionario[0] <= $hora_zero){

                            $dados_intervalo_invertido = DBRead('', 'tb_horarios_escala_intervalo', "WHERE id_horarios_escala = '".$conteudo_invertido['id_horarios_escala']."' AND dia = '".$dia_intervalo."' ");
                            if(!$dados_intervalo_invertido){
                                if($inicial_hora_funcionario[0] == $hora_zero && $inicial_hora_funcionario[1] >= '20' && $inicial_hora_funcionario[1] < '40'){
                                    $valor_final = converteHorasDecimal('00:'.(($inicial_hora_funcionario[1]-20)*3));
                                    $cont_atendentes_escala += 1-($valor_final);
                                }else{
                                    $cont_atendentes_escala += 1;
                                }  
                            }else{
                                if($dados_intervalo_invertido[0]['tipo_intervalo'] == 1){

                                    $data_inicio_compara = date('Y-m-d H:i:s', strtotime("+0 minutes",strtotime($conteudo_invertido[$consulta_inicial_banco])));
                                    $data_fim_compara = date('Y-m-d H:i:s', strtotime("+0 minutes",strtotime($conteudo_invertido[$consulta_final_banco])));
                            
                                    if($data_inicio_compara > $data_fim_compara){
                                        $data_inicio_compara = date('Y-m-d H:i:s', strtotime("-1 days",strtotime($conteudo_invertido[$consulta_inicial_banco])));
                                    }
                                    
                                    $diferenca_datas = strtotime($data_fim_compara) - strtotime($data_inicio_compara);
                                    $tempo_total_trabalho = converteSegundosHoras($diferenca_datas);
                                    $tempo_total_trabalho = converteHorasDecimal($tempo_total_trabalho);
                                    $tempo_total_intervalo = converteHorasDecimal($dados_intervalo_invertido[0]['tempo_intervalo']);
                                    $tempo_desconto = $tempo_total_intervalo/$tempo_total_trabalho;
                            
                                    if($inicial_hora_funcionario[0] == $hora_zero && $inicial_hora_funcionario[1] >= '20' && $inicial_hora_funcionario[1] < '40'){
                                        $valor_final = converteHorasDecimal('00:'.(($inicial_hora_funcionario[1]-20)*3));
                                        $cont_atendentes_escala += (1 - (($valor_final) - ($tempo_desconto - (1 - ($valor_final)))));
                            
                                    }else{
                                        $cont_atendentes_escala += 1 - $tempo_desconto;
                                    } 
                                }else{

                                    $inicial_hora_intervalo = explode(":", $dados_intervalo_invertido[0]['intervalo_inicial']);
                                    $final_hora_intervalo = explode(":", $dados_intervalo_invertido[0]['intervalo_final']);
                                
                                    if($inicial_hora_funcionario[0] == $hora_zero && $inicial_hora_funcionario[1] >= '20' && $inicial_hora_funcionario[1] < '40'){
                                        if($inicial_hora_intervalo[0] == $hora_zero && $final_hora_intervalo[0] == $hora_zero){
                                            if($inicial_hora_intervalo[1] >= '20' && $inicial_hora_intervalo[1] < '40' && $final_hora_intervalo[1] >= '20' && $final_hora_intervalo[1] < '40'){
                                                $valor_final = converteHorasDecimal('00:'.(($inicial_hora_funcionario[1]-20)*3));
                                                $desconto_intervalo = converteHorasDecimal('00:'.(20-($final_hora_intervalo[1] - $inicial_hora_intervalo[1]))*3);
                                                $cont_atendentes_escala += $desconto_intervalo - $valor_final;
                                            }else if($inicial_hora_intervalo[1] >= '20' && $inicial_hora_intervalo[1] < '40'){
                                                $desconto_intervalo = converteHorasDecimal('00:'.(20-$inicial_hora_intervalo[1])*3);
                                                $valor_final = converteHorasDecimal('00:'.(($inicial_hora_funcionario[1]-20)*3));
                                                $cont_atendentes_escala += (1 - ($valor_final)) - ($desconto_intervalo);
                                            }else{
                                                $cont_atendentes_escala += 1;
                                            }  
                                            
                                        }else if($inicial_hora_intervalo[0] == $hora_zero){
                                            if($inicial_hora_intervalo[1] >= '20' && $inicial_hora_intervalo[1] < '40'){
                                                $desconto_intervalo = converteHorasDecimal('00:'.(20-$inicial_hora_intervalo[1])*3);
                                                $valor_final = converteHorasDecimal('00:'.(($inicial_hora_funcionario[1]-20)*3));
                                                $cont_atendentes_escala += (1 - ($valor_final)) - ($desconto_intervalo);
                                            }else{
                                                $cont_atendentes_escala += 1;
                                            }
                                           
                                        }else{
                                            $valor_final = converteHorasDecimal('00:'.(($inicial_hora_funcionario[1]-20)*3));
                                            $cont_atendentes_escala += 1 - ($valor_final);
                                        }
                                
                                    }else{
                                        if($inicial_hora_intervalo[0] == $hora_zero && $final_hora_intervalo[0] == $hora_zero){
                                            if($inicial_hora_intervalo[1] >= '20' && $inicial_hora_intervalo[1] < '40' && $final_hora_intervalo[1] >= '20' && $final_hora_intervalo[1] < '40'){
                                                $desconto_intervalo = converteHorasDecimal('00:'.(40-($final_hora_intervalo[1] - $inicial_hora_intervalo[1]))*3);
                                                $cont_atendentes_escala += $desconto_intervalo;
                                            }else if($inicial_hora_intervalo[1] >= '20' && $inicial_hora_intervalo[1] < '40'){
                                                $desconto_intervalo = converteHorasDecimal('00:'.(40-$inicial_hora_intervalo[1])*3);
                                                $cont_atendentes_escala += 1 - $desconto_intervalo;
                                            }else if($final_hora_intervalo[1] >= '20' && $final_hora_intervalo[1] < '40'){
                                                $desconto_intervalo = converteHorasDecimal('00:'.(40-$final_hora_intervalo[1])*3);
                                                $cont_atendentes_escala += $desconto_intervalo;
                                            }else if($final_hora_intervalo[1] >= '40'){
                                                $cont_atendentes_escala += 0;
                                            }else{
                                                $cont_atendentes_escala += 1;
                                            }  
                                        }else if($inicial_hora_intervalo[0] == $hora_zero){
                                            if($inicial_hora_intervalo[1] >= '20' && $inicial_hora_intervalo[1] < '40'){
                                                $desconto_intervalo = converteHorasDecimal('00:'.(40-$inicial_hora_intervalo[1])*3);
                                                $cont_atendentes_escala += 1 - $desconto_intervalo;
                                            }else{
                                                $cont_atendentes_escala += 1;
                                            }
                                        }else if($final_hora_intervalo[0] == $hora_zero){
                                            if($final_hora_intervalo[1] >= '20' && $final_hora_intervalo[1] < '40'){
                                                $desconto_intervalo = converteHorasDecimal('00:'.(40-$final_hora_intervalo[1])*3);
                                                $cont_atendentes_escala += $desconto_intervalo;
                                            }else if($final_hora_intervalo[1] >= '40'){
                                                $cont_atendentes_escala += 0;
                                            }else{
                                                $cont_atendentes_escala += 1;
                                            }
                                        }else if(($inicial_hora_intervalo[0] < $hora_zero && $final_hora_intervalo[0] > $hora_zero) ){
                                            $cont_atendentes_escala += 0;
                                        }else{
                                            $cont_atendentes_escala += 1;
                                        }
                                    }
                                }
                            }
                        }
                    }
                }

                if($dados_invertidos_especiais){
                    
                    foreach ($dados_invertidos_especiais as $conteudo_invertidos_especiais) {
                        $inicial_hora_funcionario = explode(":", $conteudo_invertidos_especiais['inicial_especial']);

                        if($inicial_hora_funcionario[0] <= $hora_zero){
                                    
                            if(!$conteudo_invertidos_especiais['tempo_intervalo']){
                                if($inicial_hora_funcionario[0] == $hora_zero && $inicial_hora_funcionario[1] >= '20' && $inicial_hora_funcionario[1] < '40'){
                                    $valor_final = converteHorasDecimal('00:'.(($inicial_hora_funcionario[1]-20)*3));
                                    $cont_atendentes_escala += 1-($valor_final);
                                }else{
                                    $cont_atendentes_escala += 1;
                                }
                            }else{
                                $data_inicio_compara = date('Y-m-d H:i:s', strtotime("+0 minutes",strtotime($conteudo_invertidos_especiais['inicial_especial'])));
                                $data_fim_compara = date('Y-m-d H:i:s', strtotime("+0 minutes",strtotime($conteudo_invertidos_especiais['final_especial'])));
                            
                                if($data_inicio_compara > $data_fim_compara){
                                    $data_inicio_compara = date('Y-m-d H:i:s', strtotime("-1 days",strtotime($conteudo_invertidos_especiais['inicial_especial'])));
                                }
                            
                                $diferenca_datas = strtotime($data_fim_compara) - strtotime($data_inicio_compara);
                                $tempo_total_trabalho = converteSegundosHoras($diferenca_datas);
                                $tempo_total_trabalho = converteHorasDecimal($tempo_total_trabalho);
                                $tempo_total_intervalo = converteHorasDecimal($conteudo_invertidos_especiais['tempo_intervalo']);
                                $tempo_desconto = $tempo_total_intervalo/$tempo_total_trabalho;
                            
                                if($inicial_hora_funcionario[0] == $hora_zero && $inicial_hora_funcionario[1] >= '20' && $inicial_hora_funcionario[1] < '40'){
                                    $valor_final = converteHorasDecimal('00:'.(($inicial_hora_funcionario[1]-20)*3));
                                    $cont_atendentes_escala += (1 - ($valor_final)) - ($tempo_desconto * (1 -  ($valor_final)));
                                }else{
                                    $cont_atendentes_escala += 1 - $tempo_desconto;
                                }
                            }
                        }
                    }
                }

                if($data == $consulta_data){
                    $consulta_data = $consulta_data_ontem;
                }

                //INVERTIDOS ONTEM CONSULTA
                    $dados_invertidos_ontem = DBRead('', 'tb_horarios_escala', "WHERE data_inicial = '".$consulta_data."' ".$consulta_ontem." AND atendente = 1 AND id_usuario NOT IN (SELECT id_usuario FROM tb_ferias WHERE id_usuario = id_usuario AND data_de <= '".$data_ontem."' AND data_ate >= '".$data_ontem."') AND id_usuario NOT IN (SELECT a.id_usuario FROM tb_horarios_escala a INNER JOIN tb_horarios_especiais b ON a.id_horarios_escala = b.id_horarios_escala WHERE b.dia = '".$data_ontem."')");
                
                    $dados_invertidos_ontem_especiais =  DBRead('', 'tb_horarios_escala a', "INNER JOIN tb_horarios_especiais b ON a.id_horarios_escala = b.id_horarios_escala WHERE b.dia = '".$data_ontem."' AND b.final_especial > '".$hora_zero.":20' AND b.inicial_especial > b.final_especial ");
               
               //INVERTIDOS ONTEM
                if($dados_invertidos_ontem){
                    foreach ($dados_invertidos_ontem as $conteudo_ontem) {
                        $final_hora_funcionario = explode(":", $conteudo_ontem[$consulta_final_banco_ontem]);

                        if($final_hora_funcionario[0] >= $hora_zero){
                            $dados_intervalo_invertido_ontem = DBRead('', 'tb_horarios_escala_intervalo', "WHERE id_horarios_escala = '".$conteudo_ontem['id_horarios_escala']."' AND dia = '".$dia_intervalo_ontem."' ");
                            if(!$dados_intervalo_invertido_ontem){
                                if($final_hora_funcionario[0] == $hora_zero && $final_hora_funcionario[1] >= '20' && $final_hora_funcionario[1] < '40'){
                                    $valor_final = converteHorasDecimal('00:'.(($final_hora_funcionario[1]-20)*3));
                                    $cont_atendentes_escala += $valor_final;
                                }else{
                                    $cont_atendentes_escala += 1;
                                }  
                            }else{
                                if($dados_intervalo_invertido_ontem[0]['tipo_intervalo'] == 1){

                                    $data_inicio_compara = date('Y-m-d H:i:s', strtotime("+0 minutes",strtotime($conteudo_ontem[$consulta_inicial_banco_ontem])));
                                    $data_fim_compara = date('Y-m-d H:i:s', strtotime("+0 minutes",strtotime($conteudo_ontem[$consulta_final_banco_ontem])));
                            
                                    if($data_inicio_compara > $data_fim_compara){
                                        $data_inicio_compara = date('Y-m-d H:i:s', strtotime("-1 days",strtotime($conteudo_ontem[$consulta_inicial_banco_ontem])));
                                    }
                                    
                                    $diferenca_datas = strtotime($data_fim_compara) - strtotime($data_inicio_compara);
                                    $tempo_total_trabalho = converteSegundosHoras($diferenca_datas);
                                    $tempo_total_trabalho = converteHorasDecimal($tempo_total_trabalho);
                                    $tempo_total_intervalo = converteHorasDecimal($dados_intervalo_invertido_ontem[0]['tempo_intervalo']);
                                    $tempo_desconto = $tempo_total_intervalo/$tempo_total_trabalho;
                            
                                    if($final_hora_funcionario[0] == $hora_zero && $final_hora_funcionario[1] >= '20' && $final_hora_funcionario[1] < '40'){
                                        $valor_final = converteHorasDecimal('00:'.(($final_hora_funcionario[1]-20)*3));
                                        $cont_atendentes_escala += ($valor_final) - ($tempo_desconto * ($valor_final));
                                    }else{
                                        $cont_atendentes_escala += 1 - $tempo_desconto;
                                    } 
                                }else{

                                    $inicial_hora_intervalo = explode(":", $dados_intervalo_invertido_ontem[0]['intervalo_inicial']);
                                    $final_hora_intervalo = explode(":", $dados_intervalo_invertido_ontem[0]['intervalo_final']);
                                
                                    if($final_hora_funcionario[0] == $hora_zero  && $final_hora_funcionario[1] >= '20' && $final_hora_funcionario[1] < '40'){
                                        if($inicial_hora_intervalo[0] == $hora_zero && $final_hora_intervalo[0] == $hora_zero){
                                            if($inicial_hora_intervalo[1] >= '20' && $inicial_hora_intervalo[1] < '40' && $final_hora_intervalo[1] >= '20' && $final_hora_intervalo[1] < '40'){
                                                $valor_final = converteHorasDecimal('00:'.(($inicial_hora_funcionario[1]-20)*3));
                                                $desconto_intervalo = converteHorasDecimal('00:'.(40-($final_hora_intervalo[1] - $inicial_hora_intervalo[1]))*3);
                                                $cont_atendentes_escala += $desconto_intervalo - $valor_final;
                                            }else if($inicial_hora_intervalo[1] >= '20' && $inicial_hora_intervalo[1] < '40'){
                                                $desconto_intervalo = converteHorasDecimal('00:'.(40-$inicial_hora_intervalo[1])*3);
                                                $valor_final = converteHorasDecimal('00:'.$inicial_hora_funcionario[1])*3;
                                                $cont_atendentes_escala += (1 - ($valor_final)) - ($desconto_intervalo);
                                            }else if($final_hora_intervalo[1] >= '20' && $final_hora_intervalo[1] < '40'){
                                                $desconto_intervalo = converteHorasDecimal('00:'.(40-$final_hora_intervalo[1])*3);
                                                $valor_final = converteHorasDecimal('00:'.(($final_hora_funcionario[1]-20)*3));
                                                $cont_atendentes_escala += $valor_final - (1 -$desconto_intervalo);
                                            }else{
                                                $cont_atendentes_escala += 1;
                                            }
                                        }else if($final_hora_intervalo[0] == $hora_zero){
                                            if($final_hora_intervalo[1] >= '20' && $final_hora_intervalo[1] < '40'){
                                                $desconto_intervalo = converteHorasDecimal('00:'.(40-$final_hora_intervalo[1])*3);
                                                $valor_final = converteHorasDecimal('00:'.(($final_hora_funcionario[1]-20)*3));
                                                $cont_atendentes_escala += $valor_final - (1 -$desconto_intervalo);
                                            }else{
                                                $cont_atendentes_escala += 0;
                                            }
                                        }else{
                                            $valor_final = converteHorasDecimal('00:'.(($final_hora_funcionario[1]-20)*3));
                                            $cont_atendentes_escala += $valor_final;
                                        }
                                
                                    }else{
                                        if($inicial_hora_intervalo[0] == $hora_zero && $final_hora_intervalo[0] == $hora_zero){
                                            if($inicial_hora_intervalo[1] >= '20' && $inicial_hora_intervalo[1] < '40' && $final_hora_intervalo[1] >= '20' && $final_hora_intervalo[1] < '40'){
                                                $desconto_intervalo = converteHorasDecimal('00:'.(40-($final_hora_intervalo[1] - $inicial_hora_intervalo[1]))*3);
                                                $cont_atendentes_escala += $desconto_intervalo;
                                            }else if($inicial_hora_intervalo[1] >= '20' && $inicial_hora_intervalo[1] < '40'){
                                                $desconto_intervalo = converteHorasDecimal('00:'.(40-$inicial_hora_intervalo[1])*3);
                                                $cont_atendentes_escala += 1 - $desconto_intervalo;
                                            }else if($final_hora_intervalo[1] >= '20' && $final_hora_intervalo[1] < '40'){
                                                $desconto_intervalo = converteHorasDecimal('00:'.(40-$final_hora_intervalo[1])*3);
                                                $cont_atendentes_escala += $desconto_intervalo;
                                            }else if($final_hora_intervalo[1] >= '40'){
                                                $cont_atendentes_escala += 0;
                                            }else{
                                                $cont_atendentes_escala += 1;
                                            }  
                                        }else if($inicial_hora_intervalo[0] == $hora_zero){
                                            if($inicial_hora_intervalo[1] >= '20' && $inicial_hora_intervalo[1] < '40'){
                                                $desconto_intervalo = converteHorasDecimal('00:'.(40-$inicial_hora_intervalo[1])*3);
                                                $cont_atendentes_escala += 1 - $desconto_intervalo;
                                            }else{
                                                $cont_atendentes_escala += 1;
                                            }
                                        }else if($final_hora_intervalo[0] == $hora_zero){
                                            if($final_hora_intervalo[1] >= '20' && $final_hora_intervalo[1] < '40'){
                                                $desconto_intervalo = converteHorasDecimal('00:'.(40-$final_hora_intervalo[1])*3);
                                                $cont_atendentes_escala += $desconto_intervalo;
                                            }else if($final_hora_intervalo[1] >= '40'){
                                                $cont_atendentes_escala += 0;
                                            }else{
                                                $cont_atendentes_escala += 1;
                                            }
                                        }else if(($inicial_hora_intervalo[0] < $hora_zero && $final_hora_intervalo[0] > $hora_zero) ){
                                            $cont_atendentes_escala += 0;
                                        }else{
                                            $cont_atendentes_escala += 1;
                                        }
                                    }
                                }
                            }
                        }
                    }
                }

                if($dados_invertidos_ontem_especiais){
                    foreach ($dados_invertidos_ontem_especiais as $conteudo_invertidos_ontem_especiais) {
                        $final_hora_funcionario = explode(":", $conteudo_invertidos_ontem_especiais['final_especial']);
                        if($final_hora_funcionario[0] >= $hora_zero){

                            if(!$conteudo_normais_especiais['tempo_intervalo']){
                                if($final_hora_funcionario[0] == $hora_zero && $final_hora_funcionario[1] >= '20' && $final_hora_funcionario[1] < '40'){
                                    $valor_final = converteHorasDecimal('00:'.(($final_hora_funcionario[1]-20)*3));
                                    $cont_atendentes_escala += sprintf("%01.2f", $valor_final);
                                }else{
                                    $cont_atendentes_escala += 1;
                                }
                            }else{
                                $data_inicio_compara = date('Y-m-d H:i:s', strtotime("+0 minutes",strtotime($conteudo_normais_especiais['inicial_especial'])));
                                $data_fim_compara = date('Y-m-d H:i:s', strtotime("+0 minutes",strtotime($conteudo_normais_especiais['final_especial'])));
                            
                                if($data_inicio_compara > $data_fim_compara){
                                    $data_inicio_compara = date('Y-m-d H:i:s', strtotime("-1 days",strtotime($conteudo_normais_especiais['inicial_especial'])));
                                }
                            
                                $diferenca_datas = strtotime($data_fim_compara) - strtotime($data_inicio_compara);
                                $tempo_total_trabalho = converteSegundosHoras($diferenca_datas);
                                $tempo_total_trabalho = converteHorasDecimal($tempo_total_trabalho);
                                $tempo_total_intervalo = converteHorasDecimal($conteudo_normais_especiais['tempo_intervalo']);
                                $tempo_desconto = $tempo_total_intervalo/$tempo_total_trabalho;
                            
                                if($final_hora_funcionario[0] == $hora_zero && $inicial_hora_funcionario[1] >= '20' && $inicial_hora_funcionario[1] < '40'){
                                    $valor_final = converteHorasDecimal('00:'.(($final_hora_funcionario[1]-20)*3));
                                    $cont_atendentes_escala += (($valor_final) - ($tempo_desconto * $valor_final));
                                }else{
                                    $cont_atendentes_escala += 1 - $tempo_desconto;
                                }
                            }
                        }
                    }
                }

                if(!$cont_atendentes_escala){
                    $cont_atendentes_escala = 0;
                }  

            //fim codigo atendentes escala 20

                $atendentes_escala[$diasemana_numero][$hora_array+1] += sprintf("%01.2f", round($cont_atendentes_escala, 2));
                $cont_atendentes_escala = 0;

            //início codigo atendentes escala 40
                
                $consulta_data = explode("-", $data);
                $consulta_data = $consulta_data[0].'-'.$consulta_data[1].'-01';

                //VERIFICA O DIA DA CONSULTA PARA PESQUISAR (SEG A SEX, SÁBADO OU DOMINGO)
                if($dia_hoje == 'Domingo'){
                    $consulta_dia =  "AND inicial_dom < '".$hora_proxima.":00' AND final_dom > '".$hora_zero.":40'";
                    
                    $consulta_inicial_banco = 'inicial_dom';
                    $consulta_final_banco = 'final_dom';

                    $consulta_inicial_banco_ontem = 'inicial_sab';
                    $consulta_final_banco_ontem = 'final_sab';

                    $consulta_ontem = "AND final_sab > '".$hora_zero.":40' AND inicial_sab > final_sab AND folga_seg != '".$dia_ontem."'";
                    $consulta_invertidos = "AND inicial_dom < '".$hora_proxima.":00' AND inicial_dom > final_dom AND id_horarios_escala NOT IN (SELECT id_horarios_escala FROM tb_folgas_dom WHERE dia = '".$data."')";
                    $consulta_normais = "AND inicial_dom < final_dom AND id_horarios_escala NOT IN (SELECT id_horarios_escala FROM tb_folgas_dom WHERE dia = '".$data."')";

                    $dia_intervalo = 'dom';
                    $dia_intervalo_ontem = 'sab';

                }else if($dia_hoje == 'Sábado'){
                    $consulta_dia =  "AND inicial_sab < '".$hora_proxima.":00' AND final_sab > '".$hora_zero.":40'";
                    $consulta_inicial_banco = 'inicial_sab';
                    $consulta_final_banco = 'final_sab';
                    
                    $consulta_inicial_banco_ontem = 'inicial_seg';
                    $consulta_final_banco_ontem = 'final_seg';

                    $consulta_ontem = "AND final_seg > '".$hora_zero.":40' AND inicial_seg > final_seg AND folga_seg != '".$dia_ontem."'";
                    $consulta_invertidos = "AND inicial_sab < '".$hora_proxima.":00' AND inicial_sab > final_sab AND folga_seg != '".$dia_hoje."'";
                    $consulta_normais = "AND inicial_sab < final_sab AND folga_seg != '".$dia_hoje."'";

                    $dia_intervalo = 'sab';
                    $dia_intervalo_ontem = 'seg';
                
                }else{
                    $consulta_dia =  "AND inicial_seg < '".$hora_proxima.":00' AND final_seg > '".$hora_zero.":40'";
                    $consulta_inicial_banco = 'inicial_seg';
                    $consulta_final_banco = 'final_seg';

                    if($dia_hoje == 'Quinta' || $dia_hoje == 'Sexta'){
                        $consulta_invertidos = "AND inicial_seg < '".$hora_proxima.":00' AND inicial_seg > final_seg AND (folga_seg != '".$dia_hoje."' AND folga_seg != 'Quinta e Sexta')";
                        $consulta_normais = "AND inicial_seg < final_seg AND (folga_seg != '".$dia_hoje."' AND folga_seg != 'Quinta e Sexta')";
                    }else{
                        $consulta_invertidos = "AND inicial_seg < '".$hora_proxima.":00' AND inicial_seg > final_seg AND folga_seg != '".$dia_hoje."'";
                        $consulta_normais = "AND inicial_seg < final_seg AND folga_seg != '".$dia_hoje."'";
                    }

                    if($dia_hoje == 'Segunda'){
                        $consulta_ontem = "AND final_dom > '".$hora_zero.":40' AND inicial_dom > final_dom AND id_horarios_escala NOT IN (SELECT id_horarios_escala FROM tb_folgas_dom WHERE dia = '".$data_ontem."')";
                        
                        $consulta_inicial_banco_ontem = 'inicial_dom';
                        $consulta_final_banco_ontem = 'final_dom';

                        $dia_intervalo_ontem = 'dom';

                    }else{                        
                        if($dia_hoje == 'Sexta'){
                            $consulta_ontem = "AND final_seg > '".$hora_zero.":40' AND inicial_seg > final_seg AND (folga_seg != '".$dia_ontem."' AND folga_seg != 'Quinta e Sexta')";
                        }else{
                            $consulta_ontem = "AND final_seg > '".$hora_zero.":40' AND inicial_seg > final_seg AND folga_seg != '".$dia_ontem."'";
                        }
                        $consulta_inicial_banco_ontem = 'inicial_seg';
                        $consulta_final_banco_ontem = 'final_seg';

                        $dia_intervalo_ontem = 'seg';
                    
                    }
                    $dia_intervalo = 'seg';
                }
                //NORMAIS CONSULTA   
                    $dados_normais = DBRead('', 'tb_horarios_escala', "WHERE data_inicial = '".$consulta_data."' ".$consulta_dia." ".$consulta_normais." AND atendente = 1 AND id_usuario NOT IN (SELECT id_usuario FROM tb_ferias WHERE id_usuario = id_usuario AND data_de <= '".$data."' AND data_ate >= '".$data."') AND id_usuario NOT IN (SELECT a.id_usuario FROM tb_horarios_escala a INNER JOIN tb_horarios_especiais b ON a.id_horarios_escala = b.id_horarios_escala WHERE b.dia = '".$data."')");

                    $dados_normais_especiais = DBRead('', 'tb_horarios_escala a', "INNER JOIN tb_horarios_especiais b ON a.id_horarios_escala = b.id_horarios_escala WHERE b.dia = '".$data."' AND b.inicial_especial < '".$hora_proxima.":00' AND b.final_especial > '".$hora_zero.":40' ");              
                
                //NORMAIS 
                if($dados_normais){
                    foreach ($dados_normais as $conteudo_normais) {
                        $inicial_hora_funcionario = explode(":", $conteudo_normais[$consulta_inicial_banco]);
                        $final_hora_funcionario = explode(":", $conteudo_normais[$consulta_final_banco]);
                        
                        if(($inicial_hora_funcionario[0] <= $hora_zero) && ($final_hora_funcionario[0] >= $hora_zero)){

                            $dados_intervalo_normais = DBRead('', 'tb_horarios_escala_intervalo', "WHERE id_horarios_escala = '".$conteudo_normais['id_horarios_escala']."' AND dia = '".$dia_intervalo."' ");

                            if(!$dados_intervalo_normais){
                                if($inicial_hora_funcionario[0] == $hora_zero && $inicial_hora_funcionario[1] >= '40'){
                                    $valor_final = converteHorasDecimal('00:'.(($inicial_hora_funcionario[1]-40)*3));
                                    $cont_atendentes_escala += 1-($valor_final);
                                }else if($final_hora_funcionario[0] == $hora_zero && $final_hora_funcionario[1] >= '40'){
                                    $valor_final = converteHorasDecimal('00:'.(($final_hora_funcionario[1]-40)*3));
                                    $cont_atendentes_escala += $valor_final;
                                }else{
                                    $cont_atendentes_escala += 1;
                                }      
                            }else{
                                if($dados_intervalo_normais[0]['tipo_intervalo'] == 1){
                            
                                    $data_inicio_compara = date('Y-m-d H:i:s', strtotime("+0 minutes",strtotime($conteudo_normais[$consulta_inicial_banco])));
                                    $data_fim_compara = date('Y-m-d H:i:s', strtotime("+0 minutes",strtotime($conteudo_normais[$consulta_final_banco])));
                            
                                    if($data_inicio_compara > $data_fim_compara){
                                        $data_inicio_compara = date('Y-m-d H:i:s', strtotime("-1 days",strtotime($conteudo_normais[$consulta_inicial_banco])));
                                    }
                                    
                                    $diferenca_datas = strtotime($data_fim_compara) - strtotime($data_inicio_compara);
                                    $tempo_total_trabalho = converteSegundosHoras($diferenca_datas);
                                    $tempo_total_trabalho = converteHorasDecimal($tempo_total_trabalho);
                                    $tempo_total_intervalo = converteHorasDecimal($dados_intervalo_normais[0]['tempo_intervalo']);
                                    $tempo_desconto = $tempo_total_intervalo/$tempo_total_trabalho;
                            
                                    if($inicial_hora_funcionario[0] == $hora_zero && $inicial_hora_funcionario[1] >= '40'){
                                        $valor_final = converteHorasDecimal('00:'.(($inicial_hora_funcionario[1]-40)*3));
                                        $cont_atendentes_escala += (1 - (($valor_final) - ($tempo_desconto - (1 - ($valor_final)))));
                                    }else if($final_hora_funcionario[0] == $hora_zero && $final_hora_funcionario[1] >= '40'){
                                        $valor_final = converteHorasDecimal('00:'.(($final_hora_funcionario[1]-40)*3));
                                        $cont_atendentes_escala += ($valor_final) - ($tempo_desconto * ($valor_final));
                                    }else{
                                        $cont_atendentes_escala += 1 - $tempo_desconto;
                                    } 
                                }else{

                                    $inicial_hora_intervalo = explode(":", $dados_intervalo_normais[0]['intervalo_inicial']);
                                    $final_hora_intervalo = explode(":", $dados_intervalo_normais[0]['intervalo_final']);
                                
                                    if($inicial_hora_funcionario[0] == $hora_zero && $inicial_hora_funcionario[1] >= '40'){
                                
                                        if($inicial_hora_intervalo[0] == $hora_zero && $final_hora_intervalo[0] == $hora_zero){
                                            if($inicial_hora_intervalo[1] >= '40' && $final_hora_intervalo[1] >= '40'){
                                                $valor_final = converteHorasDecimal('00:'.(($inicial_hora_funcionario[1]-40)*3));
                                                $desconto_intervalo = converteHorasDecimal('00:'.(60-($final_hora_intervalo[1] - $inicial_hora_intervalo[1]))*3);
                                                $cont_atendentes_escala += $desconto_intervalo - $valor_final;
                                            }else if($inicial_hora_intervalo[1] >= '40'){
                                                $desconto_intervalo = converteHorasDecimal('00:'.(60-$inicial_hora_intervalo[1])*3);
                                                $valor_final = converteHorasDecimal('00:'.(($inicial_hora_funcionario[1]-40)*3));
                                                $cont_atendentes_escala += (1 - ($valor_final)) - ($desconto_intervalo);
                                            }else{
                                                $cont_atendentes_escala += 1;
                                            }  
                                            
                                        }else if($inicial_hora_intervalo[0] == $hora_zero){
                                            if($inicial_hora_intervalo[1] >= '40'){
                                                $desconto_intervalo = converteHorasDecimal('00:'.(60-$inicial_hora_intervalo[1])*3);
                                                $valor_final = converteHorasDecimal('00:'.(($inicial_hora_funcionario[1]-40)*3));
                                                $cont_atendentes_escala += (1 - ($valor_final)) - ($desconto_intervalo);
                                            }else{
                                                $cont_atendentes_escala += 1;
                                            }
                                           
                                        }else{
                                            $valor_final = converteHorasDecimal('00:'.(($inicial_hora_funcionario[1]-20)*3));
                                            $cont_atendentes_escala += 1 - ($valor_final);
                                        }
                                
                                    }else if($final_hora_funcionario[0] == $hora_zero && $final_hora_funcionario[1] >= '40'){
                                        if($inicial_hora_intervalo[0] == $hora_zero && $final_hora_intervalo[0] == $hora_zero){
                                            if($inicial_hora_intervalo[1] >= '40' && $final_hora_intervalo[1] >= '40'){
                                                $valor_final = converteHorasDecimal('00:'.(($inicial_hora_funcionario[1]-40)*3));
                                                $desconto_intervalo = converteHorasDecimal('00:'.(60-($final_hora_intervalo[1] - $inicial_hora_intervalo[1]))*3);
                                                $cont_atendentes_escala += $desconto_intervalo - $valor_final;
                                            }else if($final_hora_intervalo[1] >= '40'){
                                                $desconto_intervalo = converteHorasDecimal('00:'.(60-$final_hora_intervalo[1])*3);
                                                $valor_final = converteHorasDecimal('00:'.(($final_hora_funcionario[1]-40)*3));
                                                $cont_atendentes_escala += $valor_final - (1 -$desconto_intervalo);
                                            }else{
                                                $cont_atendentes_escala += 1;
                                            }
                                        }else if($final_hora_intervalo[0] == $hora_zero){
                                            if($final_hora_intervalo[1] >= '40'){
                                               
                                                $desconto_intervalo = converteHorasDecimal('00:'.(60-$final_hora_intervalo[1])*3);
                                                $valor_final = converteHorasDecimal('00:'.(($final_hora_funcionario[1]-40)*3));
                                                $cont_atendentes_escala += $valor_final - (1 -$desconto_intervalo);
                                            }else{
                                                $cont_atendentes_escala += 0;
                                            }
                                        }else{
                                            $valor_final = converteHorasDecimal('00:'.(($final_hora_funcionario[1]-40)*3));
                                            $cont_atendentes_escala += $valor_final;
                                        }
                                
                                    }else{
                                        if($inicial_hora_intervalo[0] == $hora_zero && $final_hora_intervalo[0] == $hora_zero){
                                            if($inicial_hora_intervalo[1] >= '40' && $final_hora_intervalo[1] >= '40'){
                                                $desconto_intervalo = converteHorasDecimal('00:'.(60-($final_hora_intervalo[1] - $inicial_hora_intervalo[1]))*3);
                                                $cont_atendentes_escala += $desconto_intervalo;
                                            }else if($inicial_hora_intervalo[1] >= '40'){
                                                $desconto_intervalo = converteHorasDecimal('00:'.(60-$inicial_hora_intervalo[1])*3);
                                                $cont_atendentes_escala += 1 - $desconto_intervalo;
                                            }else if($final_hora_intervalo[1] >= '40'){
                                                $desconto_intervalo = converteHorasDecimal('00:'.(60-$final_hora_intervalo[1])*3);
                                                $cont_atendentes_escala += $desconto_intervalo;
                                            }else{
                                                $cont_atendentes_escala += 1;
                                            }  
                                        }else if($inicial_hora_intervalo[0] == $hora_zero){
                                            if($inicial_hora_intervalo[1] >= '40'){
                                                $desconto_intervalo = converteHorasDecimal('00:'.(60-$inicial_hora_intervalo[1])*3);
                                                $cont_atendentes_escala += 1 - $desconto_intervalo;
                                            }else{
                                                $cont_atendentes_escala += 1;
                                            }
                                        }else if($final_hora_intervalo[0] == $hora_zero){
                                            if($final_hora_intervalo[1] >= '40'){
                                                $desconto_intervalo = converteHorasDecimal('00:'.(60-$final_hora_intervalo[1])*3);
                                                $cont_atendentes_escala += $desconto_intervalo;
                                             }else{
                                                $cont_atendentes_escala += 1;
                                            }
                                        }else if(($inicial_hora_intervalo[0] < $hora_zero && $final_hora_intervalo[0] > $hora_zero) ){
                                            $cont_atendentes_escala += 0;
                                        }else{
                                            $cont_atendentes_escala += 1;
                                        }
                                    }
                                }
                            }                   
                        }
                    }                                       
                }

                if($dados_normais_especiais){
                    foreach ($dados_normais_especiais as $conteudo_normais_especiais) {
                        $inicial_hora_funcionario = explode(":", $conteudo_normais_especiais['inicial_especial']);
                        $final_hora_funcionario = explode(":", $conteudo_normais_especiais['final_especial']);
                        
                        if(($inicial_hora_funcionario[0] <= $hora_zero) && ($final_hora_funcionario[0] >= $hora_zero)){

                            if(!$conteudo_normais_especiais['tempo_intervalo']){
                                if($inicial_hora_funcionario[0] == $hora_zero && $inicial_hora_funcionario[1] != '00' && $inicial_hora_funcionario[1] >= '40'){
                                    $valor_final = converteHorasDecimal('00:'.(($inicial_hora_funcionario[1]-40)*3));
                                    $cont_atendentes_escala += 1-($valor_final);
                                }else if($final_hora_funcionario[0] == $hora_zero && $final_hora_funcionario[1] != '00' && $final_hora_funcionario[1] >= '40'){
                                    $valor_final = converteHorasDecimal('00:'.(($final_hora_funcionario[1]-40)*3));
                                    $cont_atendentes_escala += $valor_final;
                                }else{
                                    $cont_atendentes_escala += 1;
                                }
                            }else{
                                $data_inicio_compara = date('Y-m-d H:i:s', strtotime("+0 minutes",strtotime($conteudo_normais_especiais['inicial_especial'])));
                                $data_fim_compara = date('Y-m-d H:i:s', strtotime("+0 minutes",strtotime($conteudo_normais_especiais['final_especial'])));
                            
                                if($data_inicio_compara > $data_fim_compara){
                                    $data_inicio_compara = date('Y-m-d H:i:s', strtotime("-1 days",strtotime($conteudo_normais_especiais['inicial_especial'])));
                                }
                            
                                $diferenca_datas = strtotime($data_fim_compara) - strtotime($data_inicio_compara);
                                
                                $tempo_total_trabalho = converteSegundosHoras($diferenca_datas);
                                $tempo_total_trabalho = converteHorasDecimal($tempo_total_trabalho);
                                $tempo_total_intervalo = converteHorasDecimal($conteudo_normais_especiais['tempo_intervalo']);
                                $tempo_desconto = $tempo_total_intervalo/$tempo_total_trabalho;
                            
                                if($inicial_hora_funcionario[0] == $hora_zero && $inicial_hora_funcionario[1] != '00' && $inicial_hora_funcionario[1] >= '40'){
                                    $valor_final = converteHorasDecimal('00:'.(($inicial_hora_funcionario[1]-40)*3));
                                    $cont_atendentes_escala += (1 - ($valor_final)) - ($tempo_desconto * (1 -  ($valor_final)));
                                }else if($final_hora_funcionario[0] == $hora_zero && $final_hora_funcionario[1] != '00' && $final_hora_funcionario[1] >= '40'){
                                    $valor_final = converteHorasDecimal('00:'.(($final_hora_funcionario[1]-40)*3));
                                    $cont_atendentes_escala += (($valor_final) - ($tempo_desconto * $valor_final));
                                }else{
                                    $cont_atendentes_escala += 1 - $tempo_desconto;
                                }
                            }
                        }
                    }                                       
                }

                //INVERTIDOS CONSULTA
                    $dados_invertidos = DBRead('', 'tb_horarios_escala', "WHERE data_inicial = '".$consulta_data."' ".$consulta_invertidos." AND atendente = 1 AND id_usuario NOT IN (SELECT id_usuario FROM tb_ferias WHERE id_usuario = id_usuario AND data_de <= '".$data."' AND data_ate >= '".$data."') AND id_usuario NOT IN (SELECT a.id_usuario FROM tb_horarios_escala a INNER JOIN tb_horarios_especiais b ON a.id_horarios_escala = b.id_horarios_escala WHERE b.dia = '".$data."')");

                    $dados_invertidos_especiais = DBRead('', 'tb_horarios_escala a', "INNER JOIN tb_horarios_especiais b ON a.id_horarios_escala = b.id_horarios_escala WHERE b.dia = '".$data."' AND b.inicial_especial < '".$hora_proxima.":00' AND b.inicial_especial > b.final_especial ");

                //INVERTIDOS
                if($dados_invertidos){
                    
                    foreach ($dados_invertidos as $conteudo_invertido) {
                        $inicial_hora_funcionario = explode(":", $conteudo_invertido[$consulta_inicial_banco]);

                        if($inicial_hora_funcionario[0] <= $hora_zero){
                            $dados_intervalo_invertido = DBRead('', 'tb_horarios_escala_intervalo', "WHERE id_horarios_escala = '".$conteudo_invertido['id_horarios_escala']."' AND dia = '".$dia_intervalo."' ");
                            if(!$dados_intervalo_invertido){
                                if($inicial_hora_funcionario[0] == $hora_zero && $inicial_hora_funcionario[1] >= '40'){
                                    $valor_final = converteHorasDecimal('00:'.(($inicial_hora_funcionario[1]-40)*3));
                                    $cont_atendentes_escala += 1-($valor_final);
                                }else{
                                    $cont_atendentes_escala += 1;
                                }  
                            }else{
                                if($dados_intervalo_invertido[0]['tipo_intervalo'] == 1){

                                    $data_inicio_compara = date('Y-m-d H:i:s', strtotime("+0 minutes",strtotime($conteudo_invertido[$consulta_inicial_banco])));
                                    $data_fim_compara = date('Y-m-d H:i:s', strtotime("+0 minutes",strtotime($conteudo_invertido[$consulta_final_banco])));
                            
                                    if($data_inicio_compara > $data_fim_compara){
                                        $data_inicio_compara = date('Y-m-d H:i:s', strtotime("-1 days",strtotime($conteudo_invertido[$consulta_inicial_banco])));
                                    }
                                    
                                    $diferenca_datas = strtotime($data_fim_compara) - strtotime($data_inicio_compara);
                                    $tempo_total_trabalho = converteSegundosHoras($diferenca_datas);
                                    $tempo_total_trabalho = converteHorasDecimal($tempo_total_trabalho);
                                    $tempo_total_intervalo = converteHorasDecimal($dados_intervalo_invertido[0]['tempo_intervalo']);
                                    $tempo_desconto = $tempo_total_intervalo/$tempo_total_trabalho;
                            
                                    if($inicial_hora_funcionario[0] == $hora_zero && $inicial_hora_funcionario[1] >= '40'){
                                        $valor_final = converteHorasDecimal('00:'.(($inicial_hora_funcionario[1]-40)*3));
                                        $cont_atendentes_escala += (1 - (($valor_final) - ($tempo_desconto - (1 - ($valor_final)))));
                                    }else{
                                        $cont_atendentes_escala += 1 - $tempo_desconto;
                                    } 
                                }else{

                                    $inicial_hora_intervalo = explode(":", $dados_intervalo_invertido[0]['intervalo_inicial']);
                                    $final_hora_intervalo = explode(":", $dados_intervalo_invertido[0]['intervalo_final']);
                                
                                    if($inicial_hora_funcionario[0] == $hora_zero && $inicial_hora_funcionario[1] >= '40'){
                                
                                        if($inicial_hora_intervalo[0] == $hora_zero && $final_hora_intervalo[0] == $hora_zero){
                                            if($inicial_hora_intervalo[1] >= '40' && $final_hora_intervalo[1] >= '40'){
                                                $valor_final = converteHorasDecimal('00:'.(($inicial_hora_funcionario[1]-40)*3));
                                                $desconto_intervalo = converteHorasDecimal('00:'.(60-($final_hora_intervalo[1] - $inicial_hora_intervalo[1]))*3);
                                                $cont_atendentes_escala += $desconto_intervalo - $valor_final;
                                            }else if($inicial_hora_intervalo[1] >= '40'){
                                                $desconto_intervalo = converteHorasDecimal('00:'.(60-$inicial_hora_intervalo[1])*3);
                                                $valor_final = converteHorasDecimal('00:'.(($inicial_hora_funcionario[1]-40)*3));
                                                $cont_atendentes_escala += (1 - ($valor_final)) - ($desconto_intervalo);
                                            }else{
                                                $cont_atendentes_escala += 1;
                                            }  
                                            
                                        }else if($inicial_hora_intervalo[0] == $hora_zero){
                                            if($inicial_hora_intervalo[1] >= '40'){
                                                $desconto_intervalo = converteHorasDecimal('00:'.(60-$inicial_hora_intervalo[1])*3);
                                                $valor_final = converteHorasDecimal('00:'.(($inicial_hora_funcionario[1]-40)*3));
                                                $cont_atendentes_escala += (1 - ($valor_final)) - ($desconto_intervalo);
                                            }else{
                                                $cont_atendentes_escala += 1;
                                            }
                                           
                                        }else{
                                            $valor_final = converteHorasDecimal('00:'.(($inicial_hora_funcionario[1]-20)*3));
                                            $cont_atendentes_escala += 1 - ($valor_final);
                                        }
                                
                                    }else{
                                        if($inicial_hora_intervalo[0] == $hora_zero && $final_hora_intervalo[0] == $hora_zero){
                                            if($inicial_hora_intervalo[1] >= '40' && $final_hora_intervalo[1] >= '40'){
                                                $desconto_intervalo = converteHorasDecimal('00:'.(60-($final_hora_intervalo[1] - $inicial_hora_intervalo[1]))*3);
                                                $cont_atendentes_escala += $desconto_intervalo;
                                            }else if($inicial_hora_intervalo[1] >= '40'){
                                                $desconto_intervalo = converteHorasDecimal('00:'.(60-$inicial_hora_intervalo[1])*3);
                                                $cont_atendentes_escala += 1 - $desconto_intervalo;
                                            }else if($final_hora_intervalo[1] >= '40'){
                                                $desconto_intervalo = converteHorasDecimal('00:'.(60-$final_hora_intervalo[1])*3);
                                                $cont_atendentes_escala += $desconto_intervalo;
                                            }else{
                                                $cont_atendentes_escala += 1;
                                            }  
                                        }else if($inicial_hora_intervalo[0] == $hora_zero){
                                            if($inicial_hora_intervalo[1] >= '40'){
                                                $desconto_intervalo = converteHorasDecimal('00:'.(60-$inicial_hora_intervalo[1])*3);
                                                $cont_atendentes_escala += 1 - $desconto_intervalo;
                                            }else{
                                                $cont_atendentes_escala += 1;
                                            }
                                        }else if($final_hora_intervalo[0] == $hora_zero){
                                            if($final_hora_intervalo[1] >= '40'){
                                                $desconto_intervalo = converteHorasDecimal('00:'.(60-$final_hora_intervalo[1])*3);
                                                $cont_atendentes_escala += $desconto_intervalo;
                                             }else{
                                                $cont_atendentes_escala += 1;
                                            }
                                        }else if(($inicial_hora_intervalo[0] < $hora_zero && $final_hora_intervalo[0] > $hora_zero) ){
                                            $cont_atendentes_escala += 0;
                                        }else{
                                            $cont_atendentes_escala += 1;
                                        }
                                    }
                                }
                            }
                        }
                    }
                }

                if($dados_invertidos_especiais){
                    
                    foreach ($dados_invertidos_especiais as $conteudo_invertidos_especiais) {
                        $inicial_hora_funcionario = explode(":", $conteudo_invertidos_especiais['inicial_especial']);

                        if($inicial_hora_funcionario[0] <= $hora_zero){
                            
                            if(!$conteudo_invertidos_especiais['tempo_intervalo']){
                                if($inicial_hora_funcionario[0] == $hora_zero && $inicial_hora_funcionario[1] != '00' && $inicial_hora_funcionario[1] >= '40'){
                                    $valor_final = converteHorasDecimal('00:'.(($inicial_hora_funcionario[1]-40)*3));
                                    $cont_atendentes_escala += 1-($valor_final);
                                }else{
                                    $cont_atendentes_escala += 1;
                                }
                            }else{
                                $data_inicio_compara = date('Y-m-d H:i:s', strtotime("+0 minutes",strtotime($conteudo_invertidos_especiais['inicial_especial'])));
                                $data_fim_compara = date('Y-m-d H:i:s', strtotime("+0 minutes",strtotime($conteudo_invertidos_especiais['final_especial'])));
                            
                                if($data_inicio_compara > $data_fim_compara){
                                    $data_inicio_compara = date('Y-m-d H:i:s', strtotime("-1 days",strtotime($conteudo_invertidos_especiais['inicial_especial'])));
                                }
                            
                                $diferenca_datas = strtotime($data_fim_compara) - strtotime($data_inicio_compara);
                                $tempo_total_trabalho = converteSegundosHoras($diferenca_datas);
                                $tempo_total_trabalho = converteHorasDecimal($tempo_total_trabalho);
                                $tempo_total_intervalo = converteHorasDecimal($conteudo_invertidos_especiais['tempo_intervalo']);
                                $tempo_desconto = $tempo_total_intervalo/$tempo_total_trabalho;
                            
                                if($inicial_hora_funcionario[0] == $hora_zero && $inicial_hora_funcionario[1] != '00' && $inicial_hora_funcionario[1] >= '40'){
                                    $valor_final = converteHorasDecimal('00:'.(($inicial_hora_funcionario[1]-40)*3));
                                    $cont_atendentes_escala += (1 - ($valor_final)) - ($tempo_desconto * (1 -  ($valor_final)));
                                }else{
                                    $cont_atendentes_escala += 1 - $tempo_desconto;
                                }
                            }
                        }
                    }
                }

                if($data == $consulta_data){
                    $consulta_data = $consulta_data_ontem;
                }

                //INVERTIDOS ONTEM CONSULTA
                    $dados_invertidos_ontem = DBRead('', 'tb_horarios_escala', "WHERE data_inicial = '".$consulta_data."' ".$consulta_ontem." AND atendente = 1 AND id_usuario NOT IN (SELECT id_usuario FROM tb_ferias WHERE id_usuario = id_usuario AND data_de <= '".$data_ontem."' AND data_ate >= '".$data_ontem."') AND id_usuario NOT IN (SELECT a.id_usuario FROM tb_horarios_escala a INNER JOIN tb_horarios_especiais b ON a.id_horarios_escala = b.id_horarios_escala WHERE b.dia = '".$data_ontem."')");

                    $dados_invertidos_ontem_especiais =  DBRead('', 'tb_horarios_escala a', "INNER JOIN tb_horarios_especiais b ON a.id_horarios_escala = b.id_horarios_escala WHERE b.dia = '".$data_ontem."' AND b.final_especial >= '".$hora_zero.":40' AND b.inicial_especial > b.final_especial ");
               
               //INVERTIDOS ONTEM
                if($dados_invertidos_ontem){
                    foreach ($dados_invertidos_ontem as $conteudo_ontem) {
                        $final_hora_funcionario = explode(":", $conteudo_ontem[$consulta_final_banco_ontem]);

                        if($final_hora_funcionario[0] >= $hora_zero){
                            $dados_intervalo_invertido_ontem = DBRead('', 'tb_horarios_escala_intervalo', "WHERE id_horarios_escala = '".$conteudo_ontem['id_horarios_escala']."' AND dia = '".$dia_intervalo_ontem."' ");
                            if(!$dados_intervalo_invertido_ontem){
                                if($final_hora_funcionario[0] == $hora_zero && $final_hora_funcionario[1] >= '40'){
                                    $valor_final = converteHorasDecimal('00:'.(($final_hora_funcionario[1]-40)*3));
                                    $cont_atendentes_escala += $valor_final;
                                }else{
                                    $cont_atendentes_escala += 1;
                                }   
                            }else{
                                if($dados_intervalo_invertido_ontem[0]['tipo_intervalo'] == 1){
                            
                                    $data_inicio_compara = date('Y-m-d H:i:s', strtotime("+0 minutes",strtotime($conteudo_ontem[$consulta_inicial_banco_ontem])));
                                    $data_fim_compara = date('Y-m-d H:i:s', strtotime("+0 minutes",strtotime($conteudo_ontem[$consulta_final_banco_ontem])));
                            
                                    if($data_inicio_compara > $data_fim_compara){
                                        $data_inicio_compara = date('Y-m-d H:i:s', strtotime("-1 days",strtotime($conteudo_ontem[$consulta_inicial_banco_ontem])));
                                    }
                                    
                                    $diferenca_datas = strtotime($data_fim_compara) - strtotime($data_inicio_compara);
                                    $tempo_total_trabalho = converteSegundosHoras($diferenca_datas);
                                    $tempo_total_trabalho = converteHorasDecimal($tempo_total_trabalho);
                                    $tempo_total_intervalo = converteHorasDecimal($dados_intervalo_invertido_ontem[0]['tempo_intervalo']);
                                    $tempo_desconto = $tempo_total_intervalo/$tempo_total_trabalho;
                            
                                    if($final_hora_funcionario[0] == $hora_zero && $final_hora_funcionario[1] >= '40'){
                                        $valor_final = converteHorasDecimal('00:'.(($final_hora_funcionario[1]-40)*3));
                                        $cont_atendentes_escala += ($valor_final) - ($tempo_desconto * ($valor_final));
                                    }else{
                                        $cont_atendentes_escala += 1 - $tempo_desconto;
                                    } 
                                }else{

                                    $inicial_hora_intervalo = explode(":", $dados_intervalo_normais[0]['intervalo_inicial']);
                                    $final_hora_intervalo = explode(":", $dados_intervalo_normais[0]['intervalo_final']);
                                
                                    if($final_hora_funcionario[0] == $hora_zero && $final_hora_funcionario[1] >= '40'){
                                        if($inicial_hora_intervalo[0] == $hora_zero && $final_hora_intervalo[0] == $hora_zero){
                                            if($inicial_hora_intervalo[1] >= '40' && $final_hora_intervalo[1] >= '40'){
                                                $valor_final = converteHorasDecimal('00:'.(($inicial_hora_funcionario[1]-40)*3));
                                                $desconto_intervalo = converteHorasDecimal('00:'.(60-($final_hora_intervalo[1] - $inicial_hora_intervalo[1]))*3);
                                                $cont_atendentes_escala += $desconto_intervalo - $valor_final;
                                            }else if($final_hora_intervalo[1] >= '40'){
                                                $desconto_intervalo = converteHorasDecimal('00:'.(60-$final_hora_intervalo[1])*3);
                                                $valor_final = converteHorasDecimal('00:'.(($final_hora_funcionario[1]-40)*3));
                                                $cont_atendentes_escala += $valor_final - (1 -$desconto_intervalo);
                                            }else{
                                                $cont_atendentes_escala += 1;
                                            }
                                        }else if($final_hora_intervalo[0] == $hora_zero){
                                            if($final_hora_intervalo[1] >= '40'){
                                               
                                                $desconto_intervalo = converteHorasDecimal('00:'.(60-$final_hora_intervalo[1])*3);
                                                $valor_final = converteHorasDecimal('00:'.(($final_hora_funcionario[1]-40)*3));
                                                $cont_atendentes_escala += $valor_final - (1 -$desconto_intervalo);
                                            }else{
                                                $cont_atendentes_escala += 0;
                                            }
                                        }else{
                                            $valor_final = converteHorasDecimal('00:'.(($final_hora_funcionario[1]-40)*3));
                                            $cont_atendentes_escala += $valor_final;
                                        }
                                
                                    }else{
                                        if($inicial_hora_intervalo[0] == $hora_zero && $final_hora_intervalo[0] == $hora_zero){
                                            if($inicial_hora_intervalo[1] >= '40' && $final_hora_intervalo[1] >= '40'){
                                                $desconto_intervalo = converteHorasDecimal('00:'.(60-($final_hora_intervalo[1] - $inicial_hora_intervalo[1]))*3);
                                                $cont_atendentes_escala += $desconto_intervalo;
                                            }else if($inicial_hora_intervalo[1] >= '40'){
                                                $desconto_intervalo = converteHorasDecimal('00:'.(60-$inicial_hora_intervalo[1])*3);
                                                $cont_atendentes_escala += 1 - $desconto_intervalo;
                                            }else if($final_hora_intervalo[1] >= '40'){
                                                $desconto_intervalo = converteHorasDecimal('00:'.(60-$final_hora_intervalo[1])*3);
                                                $cont_atendentes_escala += $desconto_intervalo;
                                            }else{
                                                $cont_atendentes_escala += 1;
                                            }  
                                        }else if($inicial_hora_intervalo[0] == $hora_zero){
                                            if($inicial_hora_intervalo[1] >= '40'){
                                                $desconto_intervalo = converteHorasDecimal('00:'.(60-$inicial_hora_intervalo[1])*3);
                                                $cont_atendentes_escala += 1 - $desconto_intervalo;
                                            }else{
                                                $cont_atendentes_escala += 1;
                                            }
                                        }else if($final_hora_intervalo[0] == $hora_zero){
                                            if($final_hora_intervalo[1] >= '40'){
                                                $desconto_intervalo = converteHorasDecimal('00:'.(60-$final_hora_intervalo[1])*3);
                                                $cont_atendentes_escala += $desconto_intervalo;
                                             }else{
                                                $cont_atendentes_escala += 1;
                                            }
                                        }else if(($inicial_hora_intervalo[0] < $hora_zero && $final_hora_intervalo[0] > $hora_zero) ){
                                            $cont_atendentes_escala += 0;
                                        }else{
                                            $cont_atendentes_escala += 1;
                                        }
                                    }
                                }
                            }
                        }
                    }
                }

                if($dados_invertidos_ontem_especiais){
                    foreach ($dados_invertidos_ontem_especiais as $conteudo_invertidos_ontem_especiais) {
                        $final_hora_funcionario = explode(":", $conteudo_invertidos_ontem_especiais['final_especial']);
                        if($final_hora_funcionario[0] >= $hora_zero){
                        
                            if(!$conteudo_invertidos_ontem_especiais['tempo_intervalo']){
                                if($final_hora_funcionario[0] == $hora_zero && $final_hora_funcionario[1] != '00' && $final_hora_funcionario[1] >= '40'){
                                    $valor_final = converteHorasDecimal('00:'.(($final_hora_funcionario[1]-40)*3));
                                    $cont_atendentes_escala += $valor_final;
                                }else{
                                    $cont_atendentes_escala += 1;
                                }
                            }else{
                                $data_inicio_compara = date('Y-m-d H:i:s', strtotime("+0 minutes",strtotime($conteudo_invertidos_ontem_especiais['inicial_especial'])));
                                $data_fim_compara = date('Y-m-d H:i:s', strtotime("+0 minutes",strtotime($conteudo_invertidos_ontem_especiais['final_especial'])));
                            
                                if($data_inicio_compara > $data_fim_compara){
                                    $data_inicio_compara = date('Y-m-d H:i:s', strtotime("-1 days",strtotime($conteudo_invertidos_ontem_especiais['inicial_especial'])));
                                }
                            
                                $diferenca_datas = strtotime($data_fim_compara) - strtotime($data_inicio_compara);
                                $tempo_total_trabalho = converteSegundosHoras($diferenca_datas);
                                $tempo_total_trabalho = converteHorasDecimal($tempo_total_trabalho);
                                $tempo_total_intervalo = converteHorasDecimal($conteudo_invertidos_ontem_especiais['tempo_intervalo']);
                                $tempo_desconto = $tempo_total_intervalo/$tempo_total_trabalho;
                            
                                if($final_hora_funcionario[0] == $hora_zero && $final_hora_funcionario[1] != '00' && $final_hora_funcionario[1] >= '40'){
                                    $valor_final = converteHorasDecimal('00:'.(($final_hora_funcionario[1]-40)*3));
                                    $cont_atendentes_escala += (($valor_final) - ($tempo_desconto * $valor_final));
                                }else{
                                    $cont_atendentes_escala += 1 - $tempo_desconto;
                                }
                            }
                        }
                    }
                }

                if(!$cont_atendentes_escala){
                    $cont_atendentes_escala = 0;
                }  

            //fim codigo atendentes escala 40

                $atendentes_escala[$diasemana_numero][$hora_array+2] += sprintf("%01.2f", round($cont_atendentes_escala, 2));
                $cont_atendentes_escala = 0;
            
            //Adiciona previsao

            $diasemana = array(
                "0" => "Domingo",
                "1" => "Segunda",
                "2" => "Terça",
                "3" => "Quarta",
                "4" => "Quinta",
                "5" => "Sexta",
                "6" => "Sábado",
            );

            $horas[$hora_array] = $hora_zero.':00';
            $horas[$hora_array+1] = $hora_zero.':20';
            $horas[$hora_array+2] = $hora_zero.':40';

            $hora++;
            $hora_array+=3;
        }
    }
    $dia = 0;
        while ($dia < 7) {
            $hora = 0;
            $hora_array = 0;
            while($hora < 24){
                if($contador_dias_semana_escala[$dia] && $contador_dias_semana_escala[$dia] != '0'){
                    $atendentes_escala[$dia][$hora_array] = sprintf("%01.2f", ($atendentes_escala[$dia][$hora_array]/$contador_dias_semana_escala[$dia]));
                    $atendentes_escala[$dia][$hora_array+1] = sprintf("%01.2f", ($atendentes_escala[$dia][$hora_array+1]/$contador_dias_semana_escala[$dia]));
                    $atendentes_escala[$dia][$hora_array+2] = sprintf("%01.2f", ($atendentes_escala[$dia][$hora_array+2]/$contador_dias_semana_escala[$dia]));
                }else{
                    $atendentes_escala[$dia][$hora_array] = '0';
                    $atendentes_escala[$dia][$hora_array+1] = '0';
                    $atendentes_escala[$dia][$hora_array+2] = '0';
                }
                $hora++;
                $hora_array+=3;
            }
        $dia++;
        }
   
    $chart = 0;
    while ($chart < 7) {
    ?>
        <div id="<?php echo "chart-" . $chart; ?>"></div> 
        <script>
            $(function () {
                // Create the first chart
                $('#<?php echo "chart-" . $chart; ?>').highcharts({
                    chart: {
                        type: '<?=$nome_tipo_grafico;?>'
                    },
                    title: {
                        text: '<?=$dias_semana[$chart];?>' // Title for the chart
                    },
                    xAxis: {
                        categories: <?php echo json_encode($horas) ?>
                        // Categories for the charts
                    },
                    yAxis: {
                        min: 0,
                        title: {
                            text: 'Ligações por Hora'
                        },
                        stackLabels: {
                            enabled: true,
                            style: {
                                fontWeight: 'bold',
                                color: (Highcharts.theme && Highcharts.theme.textColor) || 'black'
                            }
                        }
                    },
                    legend: {
                        enabled:true,
                        backgroundColor: (Highcharts.theme && Highcharts.theme.background2) || 'white',
                        borderColor: '#CCC',
                        borderWidth: 1,
                        shadow: false
                    },
                    tooltip: {
                        headerFormat: '<b>{point.x}</b><br/>',
                        pointFormat: '{series.name}: {point.y}<br/>Total: {point.stackTotal}'
                    },
                    plotOptions: {
                                                        
                        series: {
                            dataLabels: {
                                enabled: true
                            }
                        }
                    },
                    series: [
                        {
                            name: 'Capacidade da escala', // Name of your series
                            data: <?php echo json_encode($atendentes_escala[$chart], JSON_NUMERIC_CHECK); ?> // The data in your series

                        },
                        {
                            name: 'Capacidade necessária', // Name of your series
                            data: <?php echo json_encode($tendencia[$chart], JSON_NUMERIC_CHECK) ?> // The data in your series

                        }
                    ],
                    navigation: {
                        buttonOptions: {
                            enabled: true
                        }
                    }
                });
            });
        </script>  
        <?php
        echo '<hr>';
        $chart++;
    }
}

function relatorio_previsoes($data_de, $data_ate, $data_de_amostra, $data_ate_amostra, $aproveitamento, $segundos_espera_perdida){

    if($aproveitamento){
        if($aproveitamento > 99){
            $legenda_aproveitamento = "100%";
            $aproveitamento = '1';      
        }else{
            $legenda_aproveitamento = $aproveitamento."%";
            $aproveitamento = '0.'.$aproveitamento;
        }

    }else{
        $legenda_aproveitamento = '100%';
        $aproveitamento = '1';
    }

    $filtro_perdidas = '';
    if($segundos_espera_perdida){
        $legenda_tempo_abaixo = $segundos_espera_perdida." segundos";
        $filtro_perdidas .= " AND b.data3 >= $segundos_espera_perdida";
    }else{
        $legenda_tempo_abaixo = '0 segundos';
    }

    $nome_tipo_grafico = 'line';
    
    $fila = "'callATENDIMENTOvip','callATENDIMENTOalta','callATENDIMENTOnormal','callATENDIMENTObaixa','callATENDIMENTOnotificacaoparada', 'callATENDIMENTOvipEXT','callATENDIMENTOaltaEXT','callATENDIMENTOnormalEXT','callATENDIMENTObaixaEXT','callATENDIMENTOnotificacaoparadaEXT','callATENDIMENTOvipEXP','callATENDIMENTOaltaEXP','callATENDIMENTOnormalEXP','callATENDIMENTObaixaEXP','callATENDIMENTOnotificacaoparadaEXP'";

    $data_hora = converteDataHora(getDataHora());

    $gerado = "<span style=\"font-size: 14px;\"><strong>Gerado em: </strong> $data_hora</span>";

    echo "<div class=\"col-md-10  col-md-offset-1\" style=\"padding: 0\">";
    echo "<legend style=\"text-align:center;\"><strong>Gráfico de Capacidade por Hora </strong><br>$gerado</legend>";
    echo "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong>Data Inicial da Escala - </strong>".$data_de.", <strong>Data Final da Escala - </strong>".$data_ate.", <strong>Data Inicial de Amostra de Chamadas - </strong>".$data_de_amostra.", <strong>Data Final de Amostra de Chamadas - </strong>".$data_ate_amostra.", <strong> Descartar tempo de atendimento abaixo de - </strong> ".$legenda_tempo_abaixo.", <strong>Aproveitamento - </strong>".$legenda_aproveitamento."</legend>";
    
    $dias_semana = array('Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado');
    $horas = array('0','1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17','18','19','20','21','22','23');
    $tma = array();
    $qtd_total_atendidas = array();
    $data_hora = converteDataHora(getDataHora());
    $total_entradas = array();
    $atendidas = array();
    $perdidas_menor_60 = array();
    $perdidas_maior_60 = array();
    $contador_dias_semana_amostra = array();
    $horas = array();
    $dias_semana = array('Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado');
    $data_hora = converteDataHora(getDataHora());
    $chart = 0;
    $atendentes_escala = array();
    $contador_dias_semana_escala = array();

    if($data_de && $data_ate && $data_de_amostra && $data_ate_amostra){

        foreach(rangeDatas(converteData($data_de), converteData($data_ate)) as $data){
           
            $atendentes_central = array();
            $horas = array();
            $numero_dia_semana = date('w', strtotime($data));
            $hora = 0;

            $consulta_data = explode("-", $data);
            $consulta_data = $consulta_data[0].'-'.$consulta_data[1].'-01';
            $data_ontem = date('Y-m-d', strtotime("-1 days",strtotime($consulta_data)));
            $consulta_data_ontem = explode("-", $data_ontem);
            $consulta_data_ontem = $consulta_data_ontem[0].'-'.$consulta_data_ontem[1].'-01';
            $data_ontem = date('Y-m-d', strtotime("-1 days",strtotime($data)));

            $dia_hoje = date('w', strtotime($data));
            $dia_ontem = date('w', strtotime($data_ontem));

            $dia_hoje = $dias_semana[$dia_hoje];
            $dia_ontem = $dias_semana[$dia_ontem];
            $numero_dia_semana = date('w', strtotime($data));
            $hora = 0;
            
            $aux_consulta_data = $consulta_data;
            
            $contador_dias_semana_escala[$numero_dia_semana]++;

            while ($hora < 24) {
                $hora_zero = sprintf('%02d', $hora);
                $hora_proxima = sprintf('%02d', $hora_zero+1);  

                $cont_atendentes_central = 0;
                $cont_atendentes_escala = 0;

                $consulta_data = $aux_consulta_data;

                //início codigo atendentes escala
                    
                    //VERIFICA O DIA DA CONSULTA PARA PESQUISAR (SEG A SEX, SÁBADO OU DOMINGO)
                    if($dia_hoje == 'Domingo'){
                        $consulta_dia =  "AND inicial_dom < '".$hora_proxima.":00' AND final_dom > '".$hora_zero.":00'";
                        
                        $consulta_inicial_banco = 'inicial_dom';
                        $consulta_final_banco = 'final_dom';

                        $consulta_inicial_banco_ontem = 'inicial_sab';
                        $consulta_final_banco_ontem = 'final_sab';

                        $consulta_ontem = "AND final_sab > '".$hora_zero.":00' AND inicial_sab > final_sab AND folga_seg != '".$dia_ontem."'";
                        $consulta_invertidos = "AND inicial_dom < '".$hora_proxima.":00' AND inicial_dom > final_dom AND id_horarios_escala NOT IN (SELECT id_horarios_escala FROM tb_folgas_dom WHERE dia = '".$data."')";
                        $consulta_normais = "AND inicial_dom < final_dom AND id_horarios_escala NOT IN (SELECT id_horarios_escala FROM tb_folgas_dom WHERE dia = '".$data."')";

                        $dia_intervalo = 'dom';
                        $dia_intervalo_ontem = 'sab';
                        
                    }else if($dia_hoje == 'Sábado'){
                        $consulta_dia =  "AND inicial_sab < '".$hora_proxima.":00' AND final_sab > '".$hora_zero.":00'";
                        $consulta_inicial_banco = 'inicial_sab';
                        $consulta_final_banco = 'final_sab';
                        
                        $consulta_inicial_banco_ontem = 'inicial_seg';
                        $consulta_final_banco_ontem = 'final_seg';

                        $consulta_ontem = "AND final_seg > '".$hora_zero.":00' AND inicial_seg > final_seg AND folga_seg != '".$dia_ontem."'";
                        $consulta_invertidos = "AND inicial_sab < '".$hora_proxima.":00' AND inicial_sab > final_sab AND folga_seg != '".$dia_hoje."'";
                        $consulta_normais = "AND inicial_sab < final_sab AND folga_seg != '".$dia_hoje."'";

                        $dia_intervalo = 'sab';
                        $dia_intervalo_ontem = 'seg';

                    }else{
                        $consulta_dia =  "AND inicial_seg < '".$hora_proxima.":00' AND final_seg > '".$hora_zero.":00'";
                        $consulta_inicial_banco = 'inicial_seg';
                        $consulta_final_banco = 'final_seg';

                        if($dia_hoje == 'Quinta' || $dia_hoje == 'Sexta'){
                            $consulta_invertidos = "AND inicial_seg < '".$hora_proxima.":00' AND inicial_seg > final_seg AND (folga_seg != '".$dia_hoje."' AND folga_seg != 'Quinta e Sexta')";
                            $consulta_normais = "AND inicial_seg < final_seg AND (folga_seg != '".$dia_hoje."' AND folga_seg != 'Quinta e Sexta')";
                        }else{
                            $consulta_invertidos = "AND inicial_seg < '".$hora_proxima.":00' AND inicial_seg > final_seg AND folga_seg != '".$dia_hoje."'";
                            $consulta_normais = "AND inicial_seg < final_seg AND folga_seg != '".$dia_hoje."'";
                        }

                        if($dia_hoje == 'Segunda'){
                            $consulta_ontem = "AND final_dom > '".$hora_zero.":00' AND inicial_dom > final_dom AND id_horarios_escala NOT IN (SELECT id_horarios_escala FROM tb_folgas_dom WHERE dia = '".$data_ontem."')";
                            
                            $consulta_inicial_banco_ontem = 'inicial_dom';
                            $consulta_final_banco_ontem = 'final_dom';
                            $dia_intervalo_ontem = 'dom';

                        }else{

                            if($dia_hoje == 'Sexta'){
                                $consulta_ontem = "AND final_seg > '".$hora_zero.":00' AND inicial_seg > final_seg AND (folga_seg != '".$dia_ontem."' AND folga_seg != 'Quinta e Sexta')";
                            }else{
                                $consulta_ontem = "AND final_seg > '".$hora_zero.":00' AND inicial_seg > final_seg AND folga_seg != '".$dia_ontem."'";
                            }
                        
                            $consulta_inicial_banco_ontem = 'inicial_seg';
                            $consulta_final_banco_ontem = 'final_seg';
                            $dia_intervalo_ontem = 'seg';                        
                        }
                        $dia_intervalo = 'seg';
                    }
                                    
                    //NORMAIS               
                    $dados_normais = DBRead('', 'tb_horarios_escala', "WHERE data_inicial = '".$consulta_data."' ".$consulta_dia." ".$consulta_normais." AND atendente = 1 AND id_usuario NOT IN (SELECT id_usuario FROM tb_ferias WHERE id_usuario = id_usuario AND data_de <= '".$data."' AND data_ate >= '".$data."') AND id_usuario NOT IN (SELECT a.id_usuario FROM tb_horarios_escala a INNER JOIN tb_horarios_especiais b ON a.id_horarios_escala = b.id_horarios_escala WHERE b.dia = '".$data."')");

                    $dados_normais_especiais = DBRead('', 'tb_horarios_escala a', "INNER JOIN tb_horarios_especiais b ON a.id_horarios_escala = b.id_horarios_escala WHERE b.dia = '".$data."' AND b.inicial_especial < '".$hora_proxima.":00' AND b.final_especial > '".$hora_zero.":00' ");              
                    
                    //INVERTIDOS
                    $dados_invertidos = DBRead('', 'tb_horarios_escala', "WHERE data_inicial = '".$consulta_data."' ".$consulta_invertidos." AND atendente = 1 AND id_usuario NOT IN (SELECT id_usuario FROM tb_ferias WHERE id_usuario = id_usuario AND data_de <= '".$data."' AND data_ate >= '".$data."') AND id_usuario NOT IN (SELECT a.id_usuario FROM tb_horarios_escala a INNER JOIN tb_horarios_especiais b ON a.id_horarios_escala = b.id_horarios_escala WHERE b.dia = '".$data."')");

                    $dados_invertidos_especiais = DBRead('', 'tb_horarios_escala a', "INNER JOIN tb_horarios_especiais b ON a.id_horarios_escala = b.id_horarios_escala WHERE b.dia = '".$data."' AND b.inicial_especial < '".$hora_proxima.":00' AND b.inicial_especial > b.final_especial ");

                    if($data == $consulta_data){
                        $consulta_data = $consulta_data_ontem;
                    }

                    //INVERTIDOS ONTEM
                    $dados_invertidos_ontem = DBRead('', 'tb_horarios_escala', "WHERE data_inicial = '".$consulta_data."' ".$consulta_ontem." AND atendente = 1 AND id_usuario NOT IN (SELECT id_usuario FROM tb_ferias WHERE id_usuario = id_usuario AND data_de <= '".$data_ontem."' AND data_ate >= '".$data_ontem."') AND id_usuario NOT IN (SELECT a.id_usuario FROM tb_horarios_escala a INNER JOIN tb_horarios_especiais b ON a.id_horarios_escala = b.id_horarios_escala WHERE b.dia = '".$data_ontem."')");
                    
                    $dados_invertidos_ontem_especiais =  DBRead('', 'tb_horarios_escala a', "INNER JOIN tb_horarios_especiais b ON a.id_horarios_escala = b.id_horarios_escala WHERE b.dia = '".$data_ontem."' AND b.final_especial >= '".$hora_zero.":00' AND b.inicial_especial > b.final_especial ");

                    //NORMAIS
                    if($dados_normais){
                        foreach ($dados_normais as $conteudo_normais) {
                            $inicial_hora_funcionario = explode(":", $conteudo_normais[$consulta_inicial_banco]);
                            $final_hora_funcionario = explode(":", $conteudo_normais[$consulta_final_banco]);
                            
                            if(($inicial_hora_funcionario[0] <= $hora_zero) && ($final_hora_funcionario[0] >= $hora_zero)){
                                $dados_intervalo_normais = DBRead('', 'tb_horarios_escala_intervalo', "WHERE id_horarios_escala = '".$conteudo_normais['id_horarios_escala']."' AND dia = '".$dia_intervalo."' ");
                                if(!$dados_intervalo_normais){
                                    if($inicial_hora_funcionario[0] == $hora_zero && $inicial_hora_funcionario[1] != '00'){
                                        $valor_final = converteHorasDecimal('00:'.$inicial_hora_funcionario[1]);
                                        $cont_atendentes_escala += 1-(sprintf("%01.2f", round($valor_final, 2)));
                                    }else if($final_hora_funcionario[0] == $hora_zero && $final_hora_funcionario[1] != '00'){
                                        $valor_final = converteHorasDecimal('00:'.$final_hora_funcionario[1]);
                                        $cont_atendentes_escala += sprintf("%01.2f", round($valor_final, 2));
                                    }else{
                                        $cont_atendentes_escala += 1;
                                    }
                                }else{
                                    if($dados_intervalo_normais[0]['tipo_intervalo'] == 1){
                                
                                        $data_inicio_compara = date('Y-m-d H:i:s', strtotime("+0 minutes",strtotime($conteudo_normais[$consulta_inicial_banco])));
                                        $data_fim_compara = date('Y-m-d H:i:s', strtotime("+0 minutes",strtotime($conteudo_normais[$consulta_final_banco])));
                                
                                        if($data_inicio_compara > $data_fim_compara){
                                            $data_inicio_compara = date('Y-m-d H:i:s', strtotime("-1 days",strtotime($conteudo_normais[$consulta_inicial_banco])));
                                        }
                                        
                                        $diferenca_datas = strtotime($data_fim_compara) - strtotime($data_inicio_compara);
                                        $tempo_total_trabalho = converteSegundosHoras($diferenca_datas);
                                        $tempo_total_trabalho = converteHorasDecimal($tempo_total_trabalho);
                                        $tempo_total_intervalo = converteHorasDecimal($dados_intervalo_normais[0]['tempo_intervalo']);
                                        $tempo_desconto = $tempo_total_intervalo/$tempo_total_trabalho;
                                
                                        if($inicial_hora_funcionario[0] == $hora_zero && $inicial_hora_funcionario[1] != '00'){
                                            $valor_final = converteHorasDecimal('00:'.$inicial_hora_funcionario[1]);
                                            $cont_atendentes_escala += (1 - ((sprintf("%01.2f", round($valor_final, 2))))) - ($tempo_desconto * (1 - ((sprintf("%01.2f", round($valor_final, 2))))));
                                        }else if($final_hora_funcionario[0] == $hora_zero && $final_hora_funcionario[1] != '00'){
                                            $valor_final = converteHorasDecimal('00:'.$final_hora_funcionario[1]);
                                            $cont_atendentes_escala += (((sprintf("%01.2f", round($valor_final, 2)))) - ($tempo_desconto * (sprintf("%01.2f", round($valor_final, 2)))));
                                        }else{
                                            $cont_atendentes_escala += 1 - $tempo_desconto;
                                        } 
                                    }else{

                                        $inicial_hora_intervalo = explode(":", $dados_intervalo_normais[0]['intervalo_inicial']);
                                        $final_hora_intervalo = explode(":", $dados_intervalo_normais[0]['intervalo_final']);
                                    
                                        if($inicial_hora_funcionario[0] == $hora_zero && $inicial_hora_funcionario[1] != '00'){
                                    
                                            if($inicial_hora_intervalo[0] == $hora_zero && $final_hora_intervalo[0] == $hora_zero){
                                                //intervalo 09:00 até 09:30
                                                $valor_final = converteHorasDecimal('00:'.$inicial_hora_funcionario[1]);
                                                $desconto_intervalo = converteHorasDecimal('00:'.($final_hora_intervalo[1] - $inicial_hora_intervalo[1]));
                                                $cont_atendentes_escala += (1 - ($valor_final)) - ($desconto_intervalo);
                                    
                                            }else if($inicial_hora_intervalo[0] == $hora_zero){
                                                //intervalo 09:30 até 10:30
                                                $valor_final = converteHorasDecimal('00:'.$inicial_hora_funcionario[1]);
                                                $desconto_intervalo =  converteHorasDecimal('00:'.$inicial_hora_intervalo[1]);
                                                $cont_atendentes_escala += ($valor_final - (1 - $desconto_intervalo));
                                    
                                            }else{
                                                $valor_final = converteHorasDecimal('00:'.$inicial_hora_funcionario[1]);
                                                $cont_atendentes_escala += 1 - ($valor_final);
                                            }
                                    
                                        }else if($final_hora_funcionario[0] == $hora_zero && $final_hora_funcionario[1] != '00'){
                                            if($inicial_hora_intervalo[0] == $hora_zero && $final_hora_intervalo[0] == $hora_zero){
                                                //intervalo 09:00 até 09:30
                                                $valor_final = converteHorasDecimal('00:'.$final_hora_funcionario[1]);
                                                $desconto_intervalo = converteHorasDecimal('00:'.($final_hora_intervalo[1] - $inicial_hora_intervalo[1]));
                                                $cont_atendentes_escala += 1 - ($valor_final + $desconto_intervalo);
                                    
                                            }else if($final_hora_intervalo[0] == $hora_zero){
                                                //intervalo 08:30 até 09:30
                                                $valor_final = converteHorasDecimal('00:'.$final_hora_funcionario[1]);
                                                $desconto_intervalo = converteHorasDecimal('00:'.$final_hora_intervalo[1]);
                                                $cont_atendentes_escala += ($valor_final - $desconto_intervalo);
                                                
                                            }else{
                                                $valor_final = converteHorasDecimal('00:'.$final_hora_funcionario[1]);
                                                $cont_atendentes_escala += $valor_final;
                                            }
                                        }else{
                                            if($inicial_hora_intervalo[0] == $hora_zero && $final_hora_intervalo[0] == $hora_zero){
                                                //intervalo 09:00 até 09:30
                                                $valor_final = converteHorasDecimal('00:'.$inicial_hora_funcionario[1]);
                                                $desconto_intervalo = converteHorasDecimal('00:'.($final_hora_intervalo[1] - $inicial_hora_intervalo[1]));
                                                $cont_atendentes_escala += 1 - $desconto_intervalo;
                                    
                                            }else if($inicial_hora_intervalo[0] == $hora_zero){
                                                //intervalo 09:30 até 10:30
                                                $valor_final = converteHorasDecimal('00:'.$inicial_hora_funcionario[1]);
                                                $desconto_intervalo = converteHorasDecimal('00:'.$inicial_hora_intervalo[1]);

                                                $cont_atendentes_escala += $desconto_intervalo;
                                    
                                            }else if($final_hora_intervalo[0] == $hora_zero){
                                                //intervalo 08:30 até 09:30
                                                $desconto_intervalo = converteHorasDecimal('00:'.$final_hora_intervalo[1]);
                                                $cont_atendentes_escala += 1 - $desconto_intervalo;
                                                
                                            }else if($inicial_hora_intervalo[0] < $hora_zero && $final_hora_intervalo[0] > $hora_zero){
                                                //intervalo 08:30 até 10:30
                                                $cont_atendentes_escala += 0;
                                    
                                            }else{
                                                //intervalo antes ou depois do horarios
                                                $cont_atendentes_escala += 1;
                                    
                                            }
                                        }
                                    }
                                }
                            }
                        }                                       
                    }

                    if($dados_normais_especiais){
                        foreach ($dados_normais_especiais as $conteudo_normais_especiais) {
                            $inicial_hora_funcionario = explode(":", $conteudo_normais_especiais['inicial_especial']);
                            $final_hora_funcionario = explode(":", $conteudo_normais_especiais['final_especial']);
                            
                            if(($inicial_hora_funcionario[0] <= $hora_zero) && ($final_hora_funcionario[0] >= $hora_zero)){
                                
                                if(!$conteudo_normais_especiais['tempo_intervalo']){
                                    if($inicial_hora_funcionario[0] == $hora_zero && $inicial_hora_funcionario[1] != '00'){
                                        $valor_final = converteHorasDecimal('00:'.$inicial_hora_funcionario[1]);
                                        $cont_atendentes_escala += 1-(sprintf("%01.2f", round($valor_final, 2)));
                                    }else if($final_hora_funcionario[0] == $hora_zero && $final_hora_funcionario[1] != '00'){
                                        $valor_final = converteHorasDecimal('00:'.$final_hora_funcionario[1]);
                                        $cont_atendentes_escala += sprintf("%01.2f", round($valor_final, 2));
                                    }else{
                                        $cont_atendentes_escala += 1;
                                    }
                                }else{
                                    $data_inicio_compara = date('Y-m-d H:i:s', strtotime("+0 minutes",strtotime($conteudo_normais_especiais['inicial_especial'])));
                                    $data_fim_compara = date('Y-m-d H:i:s', strtotime("+0 minutes",strtotime($conteudo_normais_especiais['final_especial'])));
                                
                                    if($data_inicio_compara > $data_fim_compara){
                                        $data_inicio_compara = date('Y-m-d H:i:s', strtotime("-1 days",strtotime($conteudo_normais_especiais['inicial_especial'])));
                                    }
                                
                                    $diferenca_datas = strtotime($data_fim_compara) - strtotime($data_inicio_compara);
                                    $tempo_total_trabalho = converteSegundosHoras($diferenca_datas);
                                    $tempo_total_trabalho = converteHorasDecimal($tempo_total_trabalho);
                                    $tempo_total_intervalo = converteHorasDecimal($conteudo_normais_especiais['tempo_intervalo']);
                                    $tempo_desconto = $tempo_total_intervalo/$tempo_total_trabalho;
                                
                                    if($inicial_hora_funcionario[0] == $hora_zero && $inicial_hora_funcionario[1] != '00'){
                                        $valor_final = converteHorasDecimal('00:'.$inicial_hora_funcionario[1]);
                                        $cont_atendentes_escala += (1 - ((sprintf("%01.2f", round($valor_final, 2))))) - ($tempo_desconto * (1 - ((sprintf("%01.2f", round($valor_final, 2))))));
                                    }else if($final_hora_funcionario[0] == $hora_zero && $final_hora_funcionario[1] != '00'){
                                        $valor_final = converteHorasDecimal('00:'.$final_hora_funcionario[1]);
                                        $cont_atendentes_escala += (((sprintf("%01.2f", round($valor_final, 2)))) - ($tempo_desconto * (sprintf("%01.2f", round($valor_final, 2)))));
                                    }else{
                                        $cont_atendentes_escala += 1 - $tempo_desconto;
                                    }
                                }
                            }
                        }                                       
                    }

                    //INVERTIDOS
                    
                    if($dados_invertidos){
                        
                        foreach ($dados_invertidos as $conteudo_invertido) {
                            $inicial_hora_funcionario = explode(":", $conteudo_invertido[$consulta_inicial_banco]);

                            if($inicial_hora_funcionario[0] <= $hora_zero){
                                $dados_intervalo_invertido = DBRead('', 'tb_horarios_escala_intervalo', "WHERE id_horarios_escala = '".$conteudo_invertido['id_horarios_escala']."' AND dia = '".$dia_intervalo."' ");
                                if(!$dados_intervalo_invertido){
                                    if($inicial_hora_funcionario[0] == $hora_zero && $inicial_hora_funcionario[1] != '00'){
                                        $valor_final = converteHorasDecimal('00:'.$inicial_hora_funcionario[1]);
                                        $cont_atendentes_escala += 1-(sprintf("%01.2f", round($valor_final, 2)));
                                    }else{
                                        $cont_atendentes_escala += 1;
                                    }
                                }else{
                                    if($dados_intervalo_invertido[0]['tipo_intervalo'] == 1){
                                
                                        $data_inicio_compara = date('Y-m-d H:i:s', strtotime("+0 minutes",strtotime($conteudo_invertido[$consulta_inicial_banco])));
                                        $data_fim_compara = date('Y-m-d H:i:s', strtotime("+0 minutes",strtotime($conteudo_invertido[$consulta_final_banco])));
                                
                                        if($data_inicio_compara > $data_fim_compara){
                                            $data_inicio_compara = date('Y-m-d H:i:s', strtotime("-1 days",strtotime($conteudo_invertido[$consulta_inicial_banco])));
                                        }
                                        
                                        $diferenca_datas = strtotime($data_fim_compara) - strtotime($data_inicio_compara);
                                        $tempo_total_trabalho = converteSegundosHoras($diferenca_datas);
                                        $tempo_total_trabalho = converteHorasDecimal($tempo_total_trabalho);
                                        $tempo_total_intervalo = converteHorasDecimal($dados_intervalo_invertido[0]['tempo_intervalo']);
                                        $tempo_desconto = $tempo_total_intervalo/$tempo_total_trabalho;
                                
                                        if($inicial_hora_funcionario[0] == $hora_zero && $inicial_hora_funcionario[1] != '00'){
                                            $valor_final = converteHorasDecimal('00:'.$inicial_hora_funcionario[1]);
                                            $cont_atendentes_escala += (1 - ((sprintf("%01.2f", round($valor_final, 2))))) - ($tempo_desconto * (1 - ((sprintf("%01.2f", round($valor_final, 2))))));
                                        }else{
                                            $cont_atendentes_escala += 1 - $tempo_desconto;
                                        } 
                                    }else{

                                        $inicial_hora_intervalo = explode(":", $dados_intervalo_invertido[0]['intervalo_inicial']);
                                        $final_hora_intervalo = explode(":", $dados_intervalo_invertido[0]['intervalo_final']);
                                    
                                        if($inicial_hora_funcionario[0] == $hora_zero && $inicial_hora_funcionario[1] != '00'){
                                    
                                            if($inicial_hora_intervalo[0] == $hora_zero && $final_hora_intervalo[0] == $hora_zero){
                                                //intervalo 09:00 até 09:30
                                                $valor_final = converteHorasDecimal('00:'.$inicial_hora_funcionario[1]);
                                                $desconto_intervalo = converteHorasDecimal('00:'.($final_hora_intervalo[1] - $inicial_hora_intervalo[1]));
                                                $cont_atendentes_escala += (1 - ($valor_final)) - ($desconto_intervalo);
                                    
                                            }else if($inicial_hora_intervalo[0] == $hora_zero){
                                                //intervalo 09:30 até 10:30
                                                $valor_final = converteHorasDecimal('00:'.$inicial_hora_funcionario[1]);
                                                $desconto_intervalo =  converteHorasDecimal('00:'.$inicial_hora_intervalo[1]);
                                                $cont_atendentes_escala += ($valor_final - (1 - $desconto_intervalo));
                                    
                                            }else{
                                                $valor_final = converteHorasDecimal('00:'.$inicial_hora_funcionario[1]);
                                                $cont_atendentes_escala += 1 - ($valor_final);
                                            }
                                    
                                        }else{
                                            if($inicial_hora_intervalo[0] == $hora_zero && $final_hora_intervalo[0] == $hora_zero){
                                                //intervalo 09:00 até 09:30
                                                $valor_final = converteHorasDecimal('00:'.$inicial_hora_funcionario[1]);
                                                $desconto_intervalo = converteHorasDecimal('00:'.($final_hora_intervalo[1] - $inicial_hora_intervalo[1]));
                                                $cont_atendentes_escala += 1 - $desconto_intervalo;
                                    
                                            }else if($inicial_hora_intervalo[0] == $hora_zero){
                                                //intervalo 09:30 até 10:30
                                                $valor_final = converteHorasDecimal('00:'.$inicial_hora_funcionario[1]);
                                                $desconto_intervalo = converteHorasDecimal('00:'.$inicial_hora_intervalo[1]);
                                                $cont_atendentes_escala += $desconto_intervalo;
                                    
                                            }else if($final_hora_intervalo[0] == $hora_zero){
                                                //intervalo 08:30 até 09:30
                                                $desconto_intervalo = converteHorasDecimal('00:'.$final_hora_intervalo[1]);
                                                $cont_atendentes_escala += 1 - $desconto_intervalo;
                                                
                                            }else if($inicial_hora_intervalo[0] < $hora_zero && $final_hora_intervalo[0] > $hora_zero){
                                                //intervalo 08:30 até 10:30
                                                $cont_atendentes_escala += 0;
                                    
                                            }else{
                                                //intervalo antes ou depois do horarios
                                                $cont_atendentes_escala += 1;
                                    
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }

                    if($dados_invertidos_especiais){
                        
                        foreach ($dados_invertidos_especiais as $conteudo_invertidos_especiais) {
                            $inicial_hora_funcionario = explode(":", $conteudo_invertidos_especiais['inicial_especial']);

                            if($inicial_hora_funcionario[0] <= $hora_zero){

                                if(!$conteudo_invertidos_especiais['tempo_intervalo']){
                                    if($inicial_hora_funcionario[0] == $hora_zero && $inicial_hora_funcionario[1] != '00'){
                                        $valor_final = converteHorasDecimal('00:'.$inicial_hora_funcionario[1]);
                                        $cont_atendentes_escala += 1-(sprintf("%01.2f", round($valor_final, 2)));
                                    }else{
                                        $cont_atendentes_escala += 1;
                                    }
                                }else{
                                    $data_inicio_compara = date('Y-m-d H:i:s', strtotime("+0 minutes",strtotime($conteudo_invertidos_especiais['inicial_especial'])));
                                    $data_fim_compara = date('Y-m-d H:i:s', strtotime("+0 minutes",strtotime($conteudo_invertidos_especiais['final_especial'])));
                                
                                    if($data_inicio_compara > $data_fim_compara){
                                        $data_inicio_compara = date('Y-m-d H:i:s', strtotime("-1 days",strtotime($conteudo_invertidos_especiais['inicial_especial'])));
                                    }
                                
                                    $diferenca_datas = strtotime($data_fim_compara) - strtotime($data_inicio_compara);
                                    $tempo_total_trabalho = converteSegundosHoras($diferenca_datas);
                                    $tempo_total_trabalho = converteHorasDecimal($tempo_total_trabalho);
                                    $tempo_total_intervalo = converteHorasDecimal($conteudo_invertidos_especiais['tempo_intervalo']);
                                    $tempo_desconto = $tempo_total_intervalo/$tempo_total_trabalho;
                                
                                    if($inicial_hora_funcionario[0] == $hora_zero && $inicial_hora_funcionario[1] != '00'){
                                        $valor_final = converteHorasDecimal('00:'.$inicial_hora_funcionario[1]);
                                        $cont_atendentes_escala += (1 - ((sprintf("%01.2f", round($valor_final, 2))))) - ($tempo_desconto * (1 - ((sprintf("%01.2f", round($valor_final, 2))))));
                                    }else{
                                        $cont_atendentes_escala += 1 - $tempo_desconto;
                                    }
                                }
                            }
                        }
                    }

                    //INVERTIDOS ONTEM
                    if($dados_invertidos_ontem){

                        foreach ($dados_invertidos_ontem as $conteudo_ontem) {
                            $final_hora_funcionario = explode(":", $conteudo_ontem[$consulta_final_banco_ontem]);

                            if($final_hora_funcionario[0] >= $hora_zero){
                                $dados_intervalo_invertido_ontem = DBRead('', 'tb_horarios_escala_intervalo', "WHERE id_horarios_escala = '".$conteudo_ontem['id_horarios_escala']."' AND dia = '".$dia_intervalo_ontem."' ");

                                if(!$dados_intervalo_invertido_ontem){
                                    if($final_hora_funcionario[0] == $hora_zero && $final_hora_funcionario[1] != '00'){
                                        $valor_final = converteHorasDecimal('00:'.$final_hora_funcionario[1]);
                                        $cont_atendentes_escala += sprintf("%01.2f", round($valor_final, 2));
                                    }else{
                                        $cont_atendentes_escala += 1;
                                    } 
                                }else{
                                    if($dados_intervalo_invertido_ontem[0]['tipo_intervalo'] == 1){
                                
                                        $data_inicio_compara = date('Y-m-d H:i:s', strtotime("+0 minutes",strtotime($conteudo_ontem[$consulta_inicial_banco_ontem])));
                                        $data_fim_compara = date('Y-m-d H:i:s', strtotime("+0 minutes",strtotime($conteudo_ontem[$consulta_final_banco_ontem])));
                                
                                        if($data_inicio_compara > $data_fim_compara){
                                            $data_inicio_compara = date('Y-m-d H:i:s', strtotime("-1 days",strtotime($conteudo_ontem[$consulta_inicial_banco_ontem])));
                                        }
                                        
                                        $diferenca_datas = strtotime($data_fim_compara) - strtotime($data_inicio_compara);
                                        $tempo_total_trabalho = converteSegundosHoras($diferenca_datas);
                                        $tempo_total_trabalho = converteHorasDecimal($tempo_total_trabalho);
                                        $tempo_total_intervalo = converteHorasDecimal($dados_intervalo_invertido_ontem[0]['tempo_intervalo']);
                                        $tempo_desconto = $tempo_total_intervalo/$tempo_total_trabalho;
                                
                                        if($final_hora_funcionario[0] == $hora_zero && $final_hora_funcionario[1] != '00'){
                                            $valor_final = converteHorasDecimal('00:'.$final_hora_funcionario[1]);
                                            $cont_atendentes_escala += (((sprintf("%01.2f", round($valor_final, 2)))) - ($tempo_desconto * (sprintf("%01.2f", round($valor_final, 2)))));
                                        }else{
                                            $cont_atendentes_escala += 1 - $tempo_desconto;
                                        } 
                                    }else{

                                        $inicial_hora_intervalo = explode(":", $dados_intervalo_invertido_ontem[0]['intervalo_inicial']);
                                        $final_hora_intervalo = explode(":", $dados_intervalo_invertido_ontem[0]['intervalo_final']);
                                    
                                        if($final_hora_funcionario[0] == $hora_zero && $final_hora_funcionario[1] != '00'){
                                            if($inicial_hora_intervalo[0] == $hora_zero && $final_hora_intervalo[0] == $hora_zero){
                                                //intervalo 09:00 até 09:30
                                                $valor_final = converteHorasDecimal('00:'.$final_hora_funcionario[1]);
                                                $desconto_intervalo = converteHorasDecimal('00:'.($final_hora_intervalo[1] - $inicial_hora_intervalo[1]));
                                                $cont_atendentes_escala += 1 - ($valor_final + $desconto_intervalo);
                                    
                                            }else if($final_hora_intervalo[0] == $hora_zero){
                                                //intervalo 08:30 até 09:30
                                                $valor_final = converteHorasDecimal('00:'.$final_hora_funcionario[1]);
                                                $desconto_intervalo = converteHorasDecimal('00:'.$final_hora_intervalo[1]);
                                                $cont_atendentes_escala += ($valor_final - $desconto_intervalo);
                                                
                                            }else{
                                                $valor_final = converteHorasDecimal('00:'.$final_hora_funcionario[1]);
                                                $cont_atendentes_escala += $valor_final;
                                            }
                                        }else{
                                            if($inicial_hora_intervalo[0] == $hora_zero && $final_hora_intervalo[0] == $hora_zero){
                                                //intervalo 09:00 até 09:30
                                                $valor_final = converteHorasDecimal('00:'.$inicial_hora_funcionario[1]);
                                                $desconto_intervalo = converteHorasDecimal('00:'.($final_hora_intervalo[1] - $inicial_hora_intervalo[1]));
                                                $cont_atendentes_escala += 1 - $desconto_intervalo;
                                    
                                            }else if($inicial_hora_intervalo[0] == $hora_zero){
                                                //intervalo 09:30 até 10:30
                                                $valor_final = converteHorasDecimal('00:'.$inicial_hora_funcionario[1]);
                                                $desconto_intervalo = converteHorasDecimal('00:'.$inicial_hora_intervalo[1]);
                                                $cont_atendentes_escala += $desconto_intervalo;
                                    
                                            }else if($final_hora_intervalo[0] == $hora_zero){
                                                //intervalo 08:30 até 09:30
                                                $desconto_intervalo = converteHorasDecimal('00:'.$final_hora_intervalo[1]);
                                                $cont_atendentes_escala += 1 - $desconto_intervalo;
                                                
                                            }else if($inicial_hora_intervalo[0] < $hora_zero && $final_hora_intervalo[0] > $hora_zero){
                                                //intervalo 08:30 até 10:30
                                                $cont_atendentes_escala += 0;
                                    
                                            }else{
                                                //intervalo antes ou depois do horarios
                                                $cont_atendentes_escala += 1;
                                    
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }

                    if($dados_invertidos_ontem_especiais){

                        foreach ($dados_invertidos_ontem_especiais as $conteudo_invertidos_ontem_especiais) {
                            $final_hora_funcionario = explode(":", $conteudo_invertidos_ontem_especiais['final_especial']);
                            if($final_hora_funcionario[0] >= $hora_zero){

                                if(!$conteudo_invertidos_ontem_especiais['tempo_intervalo']){
                                    if($final_hora_funcionario[0] == $hora_zero && $final_hora_funcionario[1] != '00'){
                                        $valor_final = converteHorasDecimal('00:'.$final_hora_funcionario[1]);
                                        $cont_atendentes_escala += sprintf("%01.2f", round($valor_final, 2));
                                    }else{
                                        $cont_atendentes_escala += 1;
                                    }
                                }else{
                                    $data_inicio_compara = date('Y-m-d H:i:s', strtotime("+0 minutes",strtotime($conteudo_invertidos_ontem_especiais['inicial_especial'])));
                                    $data_fim_compara = date('Y-m-d H:i:s', strtotime("+0 minutes",strtotime($conteudo_invertidos_ontem_especiais['final_especial'])));
                                
                                    if($data_inicio_compara > $data_fim_compara){
                                        $data_inicio_compara = date('Y-m-d H:i:s', strtotime("-1 days",strtotime($conteudo_invertidos_ontem_especiais['inicial_especial'])));
                                    }
                                
                                    $diferenca_datas = strtotime($data_fim_compara) - strtotime($data_inicio_compara);
                                    $tempo_total_trabalho = converteSegundosHoras($diferenca_datas);
                                    $tempo_total_trabalho = converteHorasDecimal($tempo_total_trabalho);
                                    $tempo_total_intervalo = converteHorasDecimal($conteudo_invertidos_ontem_especiais['tempo_intervalo']);
                                    $tempo_desconto = $tempo_total_intervalo/$tempo_total_trabalho;
                                
                                    if($final_hora_funcionario[0] == $hora_zero && $final_hora_funcionario[1] != '00'){
                                        $valor_final = converteHorasDecimal('00:'.$final_hora_funcionario[1]);
                                        $cont_atendentes_escala += (((sprintf("%01.2f", round($valor_final, 2)))) - ($tempo_desconto * (sprintf("%01.2f", round($valor_final, 2)))));
                                    }else{
                                        $cont_atendentes_escala += 1 - $tempo_desconto;
                                    }
                                }
                            }
                        }
                    }

                    if(!$cont_atendentes_escala){
                        $cont_atendentes_escala = 0;
                    }

                //fim codigo atendentes escala
                
                $diasemana = array(
                    "0" => "Domingo",
                    "1" => "Segunda",
                    "2" => "Terça",
                    "3" => "Quarta",
                    "4" => "Quinta",
                    "5" => "Sexta",
                    "6" => "Sábado",
                );
                
                $horas[] = $hora_zero.':00';
                $data_de_consulta = date('Y-m-d', strtotime($data));
                $diasemana_numero = date('w', strtotime($data_de_consulta));
                $atendentes_escala[$diasemana_numero][$hora] += sprintf("%01.2f", round($cont_atendentes_escala, 2));
                $hora++;
            }
        }

        foreach(rangeDatas(converteData($data_de_amostra), converteData($data_ate_amostra)) as $data){
            $atendentes_central = array();
            $horas = array();
            $hora = 0;

            $consulta_data = explode("-", $data);
            $consulta_data = $consulta_data[0].'-'.$consulta_data[1].'-01';
            $data_ontem = date('Y-m-d', strtotime("-1 days",strtotime($consulta_data)));
            $consulta_data_ontem = explode("-", $data_ontem);
            $consulta_data_ontem = $consulta_data_ontem[0].'-'.$consulta_data_ontem[1].'-01';
            $data_ontem = date('Y-m-d', strtotime("-1 days",strtotime($data)));

            $dia_hoje = date('w', strtotime($data));
            $dia_ontem = date('w', strtotime($data_ontem));

            $dia_hoje = $dias_semana[$dia_hoje];
            $dia_ontem = $dias_semana[$dia_ontem];
            $numero_dia_semana = date('w', strtotime($data));
            $hora = 0;
            $contador_dias_semana_amostra[$numero_dia_semana]++;

            $aux_consulta_data = $consulta_data;
            
            while ($hora < 24) {
                $hora_zero = sprintf('%02d', $hora);                
                $horas[] = $hora_zero.':00';

            //Entradas

                $hora_zero = sprintf('%02d', $hora);
                $atendidas_hora = 0;
                $perdidas_menor_60_hora = 0;
                $perdidas_maior_60_hora = 0;

                $dados_atendidas_grafico = DBRead('snep','queue_log a',"INNER JOIN queue_log b ON (b.callid = a.callid AND b.event = 'CONNECT') INNER JOIN queue_log c ON (c.callid = a.callid AND (c.event = 'COMPLETEAGENT' OR c.event = 'COMPLETECALLER' OR c.event = 'BLINDTRANSFER' OR c.event = 'ATTENDEDTRANSFER')) WHERE a.event = 'ENTERQUEUE' AND b.time BETWEEN '$data $hora_zero:00:00' AND '$data $hora_zero:59:59' GROUP BY b.id ORDER BY b.id", "a.queuename AS 'enterqueue_queuename', b.id");

                $atendidas_hora = 0;
                if($dados_atendidas_grafico){
                    foreach ($dados_atendidas_grafico as $conteudo_atendidas) {
                        if(preg_match("/'".$conteudo_atendidas['enterqueue_queuename']."'/i", $fila) || !$fila){
                            $atendidas_hora++;
                        }
                    }
                }    

                $dados_perdidas_menor_60_grafico = DBRead('snep','queue_log a',"INNER JOIN queue_log b ON (b.callid = a.callid AND (b.event = 'ABANDON' OR b.event = 'EXITWITHTIMEOUT')) WHERE a.event = 'ENTERQUEUE' AND a.time BETWEEN '$data $hora_zero:00:00' AND '$data $hora_zero:59:59' AND b.data3 <= 60 $filtro_perdidas GROUP BY b.id ORDER BY b.id","a.queuename AS 'enterqueue_queuename', b.id");

                $perdidas_menor_60_hora = 0;
                if($dados_perdidas_menor_60_grafico){
                    foreach ($dados_perdidas_menor_60_grafico as $conteudo_perdidas_menor_60_grafico) {
                        if(preg_match("/'".$conteudo_perdidas_menor_60_grafico['enterqueue_queuename']."'/i", $fila) || !$fila){
                            $perdidas_menor_60_hora++;
                        }
                    }
                }    

                $dados_perdidas_maior_60_grafico = DBRead('snep','queue_log a',"INNER JOIN queue_log b ON (b.callid = a.callid AND (b.event = 'ABANDON' OR b.event = 'EXITWITHTIMEOUT')) WHERE a.event = 'ENTERQUEUE' AND a.time BETWEEN '$data $hora_zero:00:00' AND '$data $hora_zero:59:59' AND b.data3 > 60 $filtro_perdidas GROUP BY b.id ORDER BY b.id","a.queuename AS 'enterqueue_queuename', b.id");

                $perdidas_maior_60_hora = 0;
                if($dados_perdidas_maior_60_grafico){
                    foreach ($dados_perdidas_maior_60_grafico as $conteudo_perdidas_maior_60_grafico) {
                        if(preg_match("/'".$conteudo_perdidas_maior_60_grafico['enterqueue_queuename']."'/i", $fila) || !$fila){
                            $perdidas_maior_60_hora++;
                        }
                    }
                }  

                $atendidas[$numero_dia_semana][$hora] += $atendidas_hora;
                $perdidas_menor_60[$numero_dia_semana][$hora] += $perdidas_menor_60_hora;
                $perdidas_maior_60[$numero_dia_semana][$hora] += $perdidas_maior_60_hora;
                $total_entradas[$numero_dia_semana][$hora] += $atendidas_hora + $perdidas_menor_60_hora + $perdidas_maior_60_hora;


            //Fim Entradas

            //TMA

                $hora_zero = sprintf('%02d', $hora);
                $qtd_atendidas = 0;
                $soma_ta_atendidas = 0;

                $dados_atendidas = DBRead('snep','queue_log a',"INNER JOIN queue_log b ON (b.callid = a.callid AND b.event = 'CONNECT') INNER JOIN queue_log c ON (c.callid = a.callid AND (c.event = 'COMPLETEAGENT' OR c.event = 'COMPLETECALLER' OR c.event = 'BLINDTRANSFER' OR c.event = 'ATTENDEDTRANSFER')) WHERE a.event = 'ENTERQUEUE' AND b.time BETWEEN '$data $hora_zero:00:00' AND '$data $hora_zero:59:59' GROUP BY b.id ORDER BY b.id", "a.queuename AS 'enterqueue_queuename', c.event AS 'finalizacao_event', c.data1 AS 'finalizacao_data1', c.data2 AS 'finalizacao_data2', c.data3 AS 'finalizacao_data3', c.data4 AS 'finalizacao_data4'"); 

                if($dados_atendidas){
                    foreach ($dados_atendidas as $conteudo_atendidas) {
                        if(preg_match("/'".$conteudo_atendidas['enterqueue_queuename']."'/i", $fila) || !$fila){
                            if($conteudo_atendidas['finalizacao_event']=='COMPLETEAGENT' || $conteudo_atendidas['finalizacao_event']=='COMPLETECALLER'){
                                $soma_ta_atendidas +=  $conteudo_atendidas['finalizacao_data2'];
                            }elseif(($conteudo_atendidas['finalizacao_event']=='BLINDTRANSFER' || $conteudo_atendidas['finalizacao_event']=='ATTENDEDTRANSFER') && $conteudo_atendidas['finalizacao_data1'] != 'LINK'){
                                $soma_ta_atendidas +=  $conteudo_atendidas['finalizacao_data4'];
                            }
                            $qtd_atendidas++;
                        }
                    }
                }           

                $tma[$numero_dia_semana][$hora] += $soma_ta_atendidas;
                $qtd_total_atendidas[$numero_dia_semana][$hora] += $qtd_atendidas;

            //Fim TMA
                $hora++;
            }
        }
        
        //Escalas
        $dia = 0;
        while ($dia < 7) {
            $hora = 0;
            while($hora < 24){
                if($contador_dias_semana_escala[$dia] && $contador_dias_semana_escala[$dia] != '0'){
                    $atendentes_escala[$dia][$hora] = sprintf("%01.2f", ($atendentes_escala[$dia][$hora]/$contador_dias_semana_escala[$dia]));
                }else{
                    $atendentes_escala[$dia][$hora] = '0';
                }

                $hora++;
            }
        $dia++;
        }
        //Fim Escalas

        //Entradas
        $cont_dia = 0;
        while($cont_dia < 7){
            $cont_hora = 0;
            while($cont_hora < 24){
                if(!$total_entradas[$cont_dia][$cont_hora]){
                    $total_entradas[$cont_dia][$cont_hora]=0;
                }else if($contador_dias_semana_amostra[$cont_dia] && $contador_dias_semana_amostra[$cont_dia] != '0'){
                    $total_entradas[$cont_dia][$cont_hora] = $total_entradas[$cont_dia][$cont_hora]/$contador_dias_semana_amostra[$cont_dia];
                }
                $cont_hora++;
            }       
            $cont_dia++;
        }
        //Fim Entradas

        //TMA
        
        $cont_dia = 0;
        while($cont_dia < 7){
            $cont_hora = 0;
            while($cont_hora < 24){
                $tma[$cont_dia][$cont_hora] = sprintf("%01.2f", round($tma[$cont_dia][$cont_hora]/($qtd_total_atendidas[$cont_dia][$cont_hora] == 0 ? 1 : $qtd_total_atendidas[$cont_dia][$cont_hora]), 2));
                $cont_hora++;
            }       
            $cont_dia++;
        }

        //Fim TMA

        $chart = 0;
        $total_previsao = 0;
        $total_capacidade = 0;
        $total_saldo = 0;
        $total_sobra = 0; 
        $total_falta = 0;

        while ($chart < 7) {
            echo '<div class="panel" style="border-color: #E6E6E6 !important;">
                    <div class="panel-heading" style="background-color: #E6E6E6 !important;">

                        <div class="row">
                            <div class="col-md-12">
                                <span class="text-center">
                                    <h2 class="panel-title"><strong>'.$dias_semana[$chart].'</strong></h2>
                                </span>    
                            </div>
                        </div>

                    </div>
                    <div class="panel-body">';

                    echo '<table class="table table-hover" style="margin-bottom:0;">
                            <thead>
                                <tr>
                                    <th class="col-md-1">Hora</th>
                                    <th class="col-md-1">Previsão</th>
                                    <th class="col-md-2">Capacidade</th>
                                    <th class="col-md-2">Saldo</th>  
                                    <th class="col-md-1">Sobra</th>  
                                    <th class="col-md-1">Falta</th>  
                                    <th class="col-md-2">Qtd. de Atendentes na Escala</th>
                                    <th class="col-md-2">TMA Médio</th>                              
                                </tr>
                            </thead>
                            <tbody>
                    ';        
                    $hora = 0;

                    $total_previsao_dia = 0;
                    $total_capacidade_dia = 0;
                    $total_saldo_dia = 0; 
                    $total_atendentes_escala = 0; 
                    $total_tma = 0; 
                    $total_sobra_dia = 0; 
                    $total_falta_dia = 0; 

                    while ($hora < 24){
                        if($atendentes_escala[$chart][$hora] == 0){
                            $capacidade = 0;
                        }else if($tma[$chart][$hora] == 0){
                            $capacidade = $total_entradas[$chart][$hora];
                        }else{
                            $hora_por_tma = 3600/($tma[$chart][$hora] == 0 ? 1 : $tma[$chart][$hora]);
                            $aproveitamento_media_dia_hora = $hora_por_tma*$aproveitamento;
                            $capacidade = ($aproveitamento_media_dia_hora) * ($atendentes_escala[$chart][$hora] == 0 ? 1 : $atendentes_escala[$chart][$hora]);
                        }
                        $saldo = $capacidade - $total_entradas[$chart][$hora];
                        
                        echo "<tr>";
                            echo "<td>".$horas[$hora]."</td>";
                            echo "<td>".sprintf("%01.2f", $total_entradas[$chart][$hora])."</td>";
                            echo "<td>".sprintf("%01.2f", $capacidade)."</td>";
                            if($saldo < 0){
                                echo "<td><span style='color:#B22222;'><strong>".sprintf("%01.2f", $saldo)."</strong></span></td>";                   
                            }else{
                                echo "<td>".sprintf("%01.2f", $saldo)."</td>";                   
                            }
                            if($saldo < 0){
                                echo "<td>0.00</td>";
                                echo "<td>".sprintf("%01.2f", ($saldo*(-1)))."</td>";
                                $total_falta_dia = $total_falta_dia + ($saldo*(-1));
                                $total_falta = $total_falta + ($saldo*(-1));
                            }else{
                                echo "<td>".sprintf("%01.2f", $saldo)."</td>";
                                echo "<td>0.00</td>";
                                $total_sobra_dia = $total_sobra_dia + $saldo;
                                $total_sobra = $total_sobra + $saldo;
                            }
                               
                               echo "<td>".$atendentes_escala[$chart][$hora]."</td>";
                            echo "<td>".converteSegundosHoras($tma[$chart][$hora])."</td>";   
                        echo "</tr>";

                        $total_previsao_dia = $total_previsao_dia + $total_entradas[$chart][$hora];
                        $total_capacidade_dia = $total_capacidade_dia + $capacidade;
                        $total_saldo_dia = $total_saldo_dia + $saldo;
                        $total_atendentes_escala = $total_atendentes_escala + $atendentes_escala[$chart][$hora];
                        $total_tma = $total_tma + $tma[$chart][$hora];

                        $hora++;
                    }

                    echo '      
                        </tbody>';
                        echo "<tfoot>";
                                echo '<tr>';
                                    echo '<th>Totais</th>';
                                    echo '<th>'.sprintf("%01.2f", $total_previsao_dia).'</th>';
                                    echo '<th>'.sprintf("%01.2f", $total_capacidade_dia).'</th>';
                                    if($total_saldo_dia >= 0){
                                         $cor_saldo = "class='success'";
                                    }else{
                                         $cor_saldo = "class='danger'";
                                    }
                                    echo '<th '.$cor_saldo.'>'.sprintf("%01.2f", $total_saldo_dia).'</th>';
                                    echo '<th>'.sprintf("%01.2f", $total_sobra_dia).'</th>';
                                    echo '<th>'.sprintf("%01.2f", $total_falta_dia).'</th>';
                                    echo '<th></th>';
                                    echo '<th></th>';
                                echo '</tr>';
                        echo "</tfoot> ";
                    echo '</table>';
                echo '</div>';
            echo '</div>';

            $total_previsao = $total_previsao + $total_previsao_dia;
            $total_capacidade = $total_capacidade + $total_capacidade_dia;
            $total_saldo = $total_saldo + $total_saldo_dia;
            $chart++;

            echo "<br>";
        }

        echo '<div class="row">
                <div class="col-md-12">
                    <div class="jumbotron" style="border: 1px solid #A9A9A9;">
                        <span class="text-center">
                            <h3>
                                <strong>
                                    Totais
                                </strong>
                            </h3>
                        </span>
                        <br>

                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead> 
                                    <tr>
                                        <th class="col-md-2 text-center">Previsão</th>
                                        <th class="col-md-2 text-center">Capacidade</th>
                                        <th class="col-md-4 text-center">Saldo</th>
                                        <th class="col-md-2 text-center">Sobra</th>
                                        <th class="col-md-2 text-center">Falta</th>
                                    </tr>
                                </thead>
                                <tbody>';
                                if($total_saldo >= 0){
                                     $cor_final = "class='success text-center'";
                                }else{
                                     $cor_final = "class='danger text-center'";
                                }
                                echo '<tr>
                                        <td class="text-center"><strong>'.sprintf("%01.2f", $total_previsao).'</strong></td>
                                        <td class="text-center"><strong>'.sprintf("%01.2f", $total_capacidade).'</strong></td>
                                        <td '.$cor_final.'><strong>'.sprintf("%01.2f", $total_saldo).'</strong></td>
                                        <td class="text-center"><strong>'.sprintf("%01.2f", $total_sobra).'</strong></td>
                                        <td class="text-center"><strong>'.sprintf("%01.2f", $total_falta).'</strong></td>
                                    </tr>';

                            echo '</tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>';


        echo '<div class="row" style="display:none;">';
            echo '<table class="table table-hover dataTable" style="margin-bottom:0;">
                <thead>
                    <tr>
                        <th class="text-center">Hora</th>
                        <th class="text-center">Previsão</th>
                        <th class="text-center">Capacidade</th>
                        <th class="text-center">Saldo</th>  
                        <th class="text-center">Qtd. de Atendentes na Escala</th>
                        <th class="text-center">TMA Médio</th>                              
                        <th class="text-center">Dia</th>                              
                    </tr>
                </thead>
                <tbody>';
            
            $chart = 0;
            while ($chart < 7) {
                $hora = 0;
                while ($hora < 24){
                    if($atendentes_escala[$chart][$hora] == 0){
                        $capacidade = 0;
                    }else if($tma[$chart][$hora] == 0){
                        $capacidade = $total_entradas[$chart][$hora];
                    }else{
                        $hora_por_tma = 3600/($tma[$chart][$hora] == 0 ? 1 : $tma[$chart][$hora]);
                        $aproveitamento_media_dia_hora = $hora_por_tma*$aproveitamento;
                        $capacidade = ($aproveitamento_media_dia_hora) * ($atendentes_escala[$chart][$hora] == 0 ? 1 : $atendentes_escala[$chart][$hora]);
                    }
                    $saldo = $capacidade - $total_entradas[$chart][$hora];
                    
                    echo "<tr>";
                        echo "<td class='text-center'>".$horas[$hora]."</td>";
                        echo "<td class='text-center'>".sprintf("%01.2f", $total_entradas[$chart][$hora])."</td>";
                        echo "<td class='text-center'>".sprintf("%01.2f", $capacidade)."</td>";
                        if($saldo < 0){
                            echo "<td class='text-center'><span style='color:#B22222;'><strong>".sprintf("%01.2f", $saldo)."</strong></span></td>";                   
                        }else{
                            echo "<td class='text-center'>".sprintf("%01.2f", $saldo)."</td>";                   
                        }
                        echo "<td class='text-center'>".$atendentes_escala[$chart][$hora]."</td>";
                        echo "<td class='text-center'>".converteSegundosHoras($tma[$chart][$hora])."</td>";  
                        echo "<td class='text-center' data-order='".$chart."'>".$dias_semana[$chart] ."</td>";  
                    echo "</tr>";

                    $hora++;
                }
                $chart++;
            }
                echo "</tbody>";
                    echo "<tfoot>";
                        echo '<tr>';
                            echo '<th class="text-center">Totais</th>';
                            echo '<th class="text-center">'.sprintf("%01.2f", $total_previsao).'</th>';
                            echo '<th class="text-center">'.sprintf("%01.2f", $total_capacidade).'</th>';
                            if($total_saldo >= 0){
                                 $cor_saldo = "class='success text-center'";
                            }else{
                                 $cor_saldo = "class='danger text-center'";
                            }
                            echo '<th '.$cor_saldo.'>'.sprintf("%01.2f", $total_saldo).'</th>';
                            echo '<th class="text-center"></th>';
                            echo '<th class="text-center"></th>';
                            echo '<th class="text-center"></th>';
                        echo '</tr>';
                echo "</tfoot> ";
            echo '</table>';
        }
    echo '</div>';

        echo "<script>
                    $(document).ready(function(){
                        var table = $('.dataTable').DataTable({
                            \"language\": {
                                \"url\": \"//cdn.datatables.net/plug-ins/1.10.19/i18n/Portuguese-Brasil.json\"
                            },  
                            aaSorting: [[6, 'asc']],
            
                            \"searching\": false,
                            \"paging\":   false,
                            \"info\":     false
                        });
                        var buttons = new $.fn.dataTable.Buttons(table, {
                            buttons: [
                                {
                                    extend: 'excelHtml5',
                                    text: '<i class=\"fa fa-file-excel-o\" aria-hidden=\"true\"></i> Exportar',
                                    filename: 'relatorio_previsoes',
                                    title : null,
                                    exportOptions: {
                                      modifier: {
                                        page: 'all'
                                      }
                                    }
                                  },
                            ],  
                            dom:
                            {
                                button: {
                                    tag: 'button',
                                    className: 'btn btn-default'
                                },
                                buttonLiner: { tag: null }
                            }
                       }).container().appendTo($('#panel_buttons'));
                    });
                </script>           
                ";
    
}

function relatorio_horarios_chat ($id_contrato_plano_pessoa) {

    $data_hoje = getDataHora();

    if ($id_contrato_plano_pessoa) {
		$dados = DBRead('', 'tb_contrato_plano_pessoa a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_contrato_plano_pessoa = '" . $id_contrato_plano_pessoa . "'");
		$empresa_legenda = $dados[0]['nome'];

		if ($dados[0]['nome_contrato']) {
			$empresa_legenda = $empresa_legenda . " (" . $dados[0]['nome_contrato'] . ") ";
		}

		$filtro_contrato_plano_pessoa = "AND a.id_contrato_plano_pessoa ='" . $id_contrato_plano_pessoa . "' ";
	} else {
		$empresa_legenda = "Todos";
	}

    $gerado = "<span style=\"font-size: 14px;\"><strong>Gerado em: </strong> ".converteDataHora($data_hoje)."</span>";

    echo "<div class=\"col-md-10 col-md-offset-1\" style=\"padding: 0\">";
    echo "<legend style=\"text-align:center;\"><strong>Relatório de Horarios</strong><br><span style=\"font-size: 14px;\">$gerado</legend>";
    echo "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong> Contrato - </strong>".$empresa_legenda."</legend>";
    echo '</legend>';

    $dados = DBRead('', 'tb_contrato_plano_pessoa a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa INNER JOIN tb_parametros c ON a.id_contrato_plano_pessoa = c.id_contrato_plano_pessoa WHERE c.atendimento_via_texto = 1 $filtro_contrato_plano_pessoa", "a.id_contrato_plano_pessoa, b.nome");

    $tipo_dia_atendimento = array(
        "1" => "Seg. a Dom.",
        "2" => "Seg. a Sáb.",
        "3" => "Seg. a Sex.",
        "4" => "Dom. e Feriados",
        "5" => "Feriados",
        "6" => "Domingo",
        "7" => "Segunda",
        "8" => "Terça",
        "9" => "Quarta",
        "10" => "Quinta",
        "11" => "Sexta",
        "12" => "Sábado",
        "13" => "Seg. a Qui."
    );

    if ($dados) {
        foreach ($dados as $conteudo) {
            $horarios = DBRead('', 'tb_horario_contrato a', "INNER JOIN tb_horario b ON a.id_horario_contrato = b.id_horario_contrato WHERE a.id_contrato_plano_pessoa = '".$conteudo['id_contrato_plano_pessoa']."' AND a.tipo = 9");
            ?>

            <div class="panel panel-primary">
                <div class="panel-heading clearfix">
                    <h3 class="panel-title text-left pull-left"><?=$conteudo['nome']?></h3>
                </div>
            <div class="panel-body">

            <?php
                foreach ($horarios as $hora) {
                    $hora_inicio = explode(':', $hora['hora_inicio']);
                    $hora_inicio = $hora_inicio[0].":".$hora_inicio[1];
                    $hora_fim = explode(':', $hora['hora_fim']);
                    $hora_fim = $hora_fim[0].":".$hora_fim[1];
                    echo "Início: ".$hora_inicio." - Final: ".$hora_fim." - ".$tipo_dia_atendimento[$hora['dia']]."<br>";
                }    

            ?>
                
                </div>
            </div>
            <?php
        }
    }
}

function relatorio_grupo_chat () {

    $data_hoje = getDataHora();

    $gerado = "<span style=\"font-size: 14px;\"><strong>Gerado em: </strong> ".converteDataHora($data_hoje)."</span>";

    echo "<div class=\"col-md-10 col-md-offset-1\" style=\"padding: 0\">";
    echo "<legend style=\"text-align:center;\"><strong>Relatório de Grupos de Chat</strong><br><span style=\"font-size: 14px;\">$gerado</legend>";
    echo '</legend>';

    $dados = DBRead('', 'tb_grupo_atendimento_chat', "WHERE status = 1 ORDER BY nome ASC");

    $tipo_dia_atendimento = array(
        "1" => "Seg. a Dom.",
        "2" => "Seg. a Sáb.",
        "3" => "Seg. a Sex.",
        "4" => "Dom. e Feriados",
        "5" => "Feriados",
        "6" => "Domingo",
        "7" => "Segunda",
        "8" => "Terça",
        "9" => "Quarta",
        "10" => "Quinta",
        "11" => "Sexta",
        "12" => "Sábado",
        "13" => "Seg. a Qui."
    );

    if ($dados) {
        foreach ($dados as $conteudo) {

            $contratos = DBRead('', 'tb_grupo_atendimento_chat_contrato a', "INNER JOIN tb_contrato_plano_pessoa b ON a.id_contrato_plano_pessoa = b.id_contrato_plano_pessoa INNER JOIN tb_pessoa c ON b.id_pessoa = c.id_pessoa WHERE id_grupo_atendimento_chat = '".$conteudo['id_grupo_atendimento_chat']."' ", "b.id_contrato_plano_pessoa, b.id_pessoa, c.nome");

            $operadores = DBRead('', 'tb_grupo_atendimento_chat_operador a', "INNER JOIN tb_usuario b ON a.id_usuario = b.id_usuario INNER JOIN tb_pessoa c ON b.id_pessoa = c.id_pessoa WHERE id_grupo_atendimento_chat = '".$conteudo['id_grupo_atendimento_chat']."' ", 'c.nome');
            
            ?>

            <div class="panel panel-default" style="border-color: <?= $conteudo['cor'] ?>; border-width: 2px;">
                <div class="panel-heading clearfix">
                    <h3 class="panel-title text-left pull-left">
                        <i class="fa fa-layer-group" style="font-size: 20px; color: <?= $conteudo['cor'] ?> "></i>
                        <?=$conteudo['nome']?>
                    </h3>
                </div>
                <div class="panel-body">

                    <div class="row">
                        <div class="col-md-6">
                            <?php 
                            if ($contratos) {
                                foreach ($contratos as $contrato) {
                                    $horarios = DBRead('', 'tb_horario_contrato a', "INNER JOIN tb_horario b ON a.id_horario_contrato = b.id_horario_contrato WHERE a.id_contrato_plano_pessoa = '".$contrato['id_contrato_plano_pessoa']."' AND a.tipo = 9");
                                    ?>

                                    <div class="panel panel-default">
                                        <div class="panel-heading clearfix">
                                            <h3 class="panel-title text-left pull-left"><?=$contrato['nome']?></h3>
                                        </div>
                                    <div class="panel-body">

                                    <?php
                                        foreach ($horarios as $hora) {
                                            $hora_inicio = explode(':', $hora['hora_inicio']);
                                            $hora_inicio = $hora_inicio[0].":".$hora_inicio[1];
                                            $hora_fim = explode(':', $hora['hora_fim']);
                                            $hora_fim = $hora_fim[0].":".$hora_fim[1];

                                            echo "Início: ".$hora_inicio." - Final: ".$hora_fim." - ".$tipo_dia_atendimento[$hora['dia']]."<br>";
                                        }    
                                    ?>
                                        </div>
                                    </div>
                                <?php
                                }
                            } else {
                                echo "<div class='col-md-12'>";
                                    echo "<table class='table table-bordered'>";
                                        echo "<tbody>";
                                            echo "<tr>";
                                                echo "<td class='text-center'> <h4>Não há contratos vínculados a este grupo!</h4></td>";
                                            echo "</tr>";
                                        echo "</tbody>";
                                    echo "</table>";
                                echo "</div>";
                            }
                            ?>
                        </div>
                        <div class="col-md-6">
                            <div class="panel panel-default">
                                <div class="panel-heading clearfix">
                                    <h3 class="panel-title text-left pull-left">Operadores</h3>
                                </div>
                                <div class="panel-body">
                                <?php
                                if ($operadores) {

                                    foreach ($operadores as $operador) {

                                        echo "Nome: ".$operador['nome']."<br>";
                                    }   
                                } else {
                                    echo "<div class='col-md-12'>";
                                        echo "<table class='table'>";
                                            echo "<tbody>";
                                                echo "<tr>";
                                                    echo "<td class='alert alert-warning text-center'> Não há operadores vínculados a este grupo!</td>";
                                                echo "</tr>";
                                            echo "</tbody>";
                                        echo "</table>";
                                    echo "</div>";
                                } 

                                ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <br>
            <?php
        }
    }
}

function relatorio_grupo_chat_historico () {

    $data_hoje = getDataHora();

    $gerado = "<span style=\"font-size: 14px;\"><strong>Gerado em: </strong> ".converteDataHora($data_hoje)."</span>";

    echo "<div class=\"col-md-10 col-md-offset-1\" style=\"padding: 0\">";
    echo "<legend style=\"text-align:center;\"><strong>Relatório de Grupos de Chat - Histórico</strong><br><span style=\"font-size: 14px;\">$gerado</legend>";
    echo '</legend>';

    $dados = DBRead('', 'tb_grupo_atendimento_chat', "ORDER BY nome ASC");

    if ($dados) {
        foreach ($dados as $conteudo) {

            $operadores = DBRead('', 'tb_grupo_atendimento_chat_operador_historico a', "INNER JOIN tb_usuario b ON a.id_usuario = b.id_usuario INNER JOIN tb_pessoa c ON b.id_pessoa = c.id_pessoa WHERE id_grupo_atendimento_chat = '".$conteudo['id_grupo_atendimento_chat']."' ", 'c.nome');
            
            ?>

            <div class="panel panel-default" style="border-color: <?= $conteudo['cor'] ?>; border-width: 2px;">
                <div class="panel-heading clearfix">
                    <h3 class="panel-title text-left pull-left">
                        <i class="fa fa-layer-group" style="font-size: 20px; color: <?= $conteudo['cor'] ?> "></i>
                        <?=$conteudo['nome']?>
                    </h3>
                </div>
                <div class="panel-body">

                    <div class="row">
                        <div class="col-md-6">
                            <?php 
                            $contrato = '';
                            // if ($contratos) {
                            //     foreach ($contratos as $contrato) {
                            //         $horarios = DBRead('', 'tb_horario_contrato a', "INNER JOIN tb_horario b ON a.id_horario_contrato = b.id_horario_contrato WHERE a.id_contrato_plano_pessoa = '".$contrato['id_contrato_plano_pessoa']."' AND a.tipo = 9");

                                    ?>
                                    <!-- 
                                    <div class="panel panel-default">
                                        <div class="panel-heading clearfix">
                                            <h3 class="panel-title text-left pull-left"><?=$contrato['nome']?></h3>
                                        </div>
                                    <div class="panel-body"> -->

                                    <?php
                                        // foreach ($horarios as $hora) {
                                        //     $hora_inicio = explode(':', $hora['hora_inicio']);
                                        //     $hora_inicio = $hora_inicio[0].":".$hora_inicio[1];
                                        //     $hora_fim = explode(':', $hora['hora_fim']);
                                        //     $hora_fim = $hora_fim[0].":".$hora_fim[1];

                                        //     echo "Início: ".$hora_inicio." - Final: ".$hora_fim." - ".$tipo_dia_atendimento[$hora['dia']]."<br>";
                                        // }    

                                    ?>

                                        <!-- </div>
                                    </div> -->
                                <?php
                            //     }
                            // } else {
                            //     echo "<div class='col-md-12'>";
                            //         echo "<table class='table table-bordered'>";
                            //             echo "<tbody>";
                            //                 echo "<tr>";
                            //                     echo "<td class='text-center'> <h4>Não há contratos vínculados a este grupo!</h4></td>";
                            //                 echo "</tr>";
                            //             echo "</tbody>";
                            //         echo "</table>";
                            //     echo "</div>";
                            // }
                            ?>
                        </div>
                        <div class="col-md-6">
                        
                            <div class="panel panel-default">
                                <div class="panel-heading clearfix">
                                    <h3 class="panel-title text-left pull-left">Operadores</h3>
                                </div>
                                <div class="panel-body">

                                <?php
                                if ($operadores) {

                                    foreach ($operadores as $operador) {

                                        echo "Nome: ".$operador['nome']."<br>";
                                    }   
                                } else {
                                    echo "<div class='col-md-12'>";
                                        echo "<table class='table'>";
                                            echo "<tbody>";
                                                echo "<tr>";
                                                    echo "<td class='alert alert-warning text-center'> Não há operadores vínculados a este grupo!</td>";
                                                echo "</tr>";
                                            echo "</tbody>";
                                        echo "</table>";
                                    echo "</div>";
                                } 

                                ?>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <br>
            <?php
        }
    }
}
?>                  