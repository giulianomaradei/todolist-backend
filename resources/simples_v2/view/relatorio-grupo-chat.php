
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

//---------------------------------------------------------
$grupo_chat = (!empty($_POST['grupo_chat'])) ? $_POST['grupo_chat'] : '';

if ($gerar) {
    $collapse = '';
    $collapse_icon = 'plus';
} else {
    $collapse = 'in';
    $collapse_icon = 'minus';
}

if ($tipo_relatorio == 1) {
    //$display_row_data = 'style="display:none;"';
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
    $display_row_grupo_chat = 'style="display:none;"';

} else if ($tipo_relatorio == 2) {
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
    $display_row_grupo_chat = '';

}else if ($tipo_relatorio == 3) {
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
    $display_row_grupo_chat = '';

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
                            style="margin-top: 2px;">Relatório de Grupos de Atendimentos por Chat:</h3>
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
                                            <option value="1" <?php if($tipo_relatorio == '1'){echo 'selected';}?>>Grupos de Chat</option>
                                            <option value="2" <?php if($tipo_relatorio == '2'){echo 'selected';}?>>Grupos de Chat (Contratos) - Histórico</option>
                                            <option value="3" <?php if($tipo_relatorio == '3'){echo 'selected';}?>>Grupos de Chat (Operadores) - Histórico</option>
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

                            <div class="row" id="row_grupo_chat" <?=$display_row_grupo_chat?>>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Grupo de Chat:</label>
                                        <select name="grupo_chat" class="form-control input-sm">
                                            <option value="">Todos</option>
                                                <?php
                                                $dados_grupo_chat = DBRead('', 'tb_grupo_atendimento_chat', "WHERE status = 1 ORDER BY nome ASC");

                                                if ($dados_grupo_chat) {
                                                    foreach ($dados_grupo_chat as $conteudo_grupo_chat) {
                                                        $selected = $grupo_chat == $conteudo_grupo_chat['id_grupo_atendimento_chat'] ? "selected" : "";
                                                        echo "<option value='" . $conteudo_grupo_chat['id_grupo_atendimento_chat'] . "' ".$selected.">" . $conteudo_grupo_chat['nome'] . "</option>";
                                                    }
                                                }
                                                ?>
                                        </select>
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
                                                    $sel_dados_mes[$var_mes] = 'selected';  
                                                }else{
                                                    $sel_dados_mes[$mes_atual] = 'selected';  
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
                                                    echo "<option value='".sprintf('%02d', $nume)."' ".$sel_dados_mes[$nume].">".$mes."</option>";
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
                                                    $sel_dados_horario_final[$horario_final] = 'selected';
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
                                                    echo "<option value='" . $conteudo_mes_inicio['data_inicial'] . "' ". $selected.">" . converteData($conteudo_mes_inicio['data_inicial']) . "</option>";
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
            if ($tipo_relatorio == 1 && $perfil_sistema != '3'){
                relatorio_grupo_chat($id_contrato_plano_pessoa);

            } else if ($tipo_relatorio == 2 && $perfil_sistema != '3'){
                relatorio_grupo_chat_historico($grupo_chat);

            } else if ($tipo_relatorio == 3 && $perfil_sistema != '3'){
                relatorio_grupo_chat_historico_operador($grupo_chat);

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
            $('#row_grupo_chat').hide();

        } else if (tipo_relatorio == 2) {
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
            $('#row_grupo_chat').show();

        } else if (tipo_relatorio == 3) {
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
            $('#row_grupo_chat').show();

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

function relatorio_grupo_chat_historico_operador ($grupo_chat) {

    if($grupo_chat){
        $dados_grupo_chat = DBRead('','tb_grupo_atendimento_chat',"WHERE id_grupo_atendimento_chat = '".$grupo_chat."' ", "nome");
        $grupo_chat_nome = $dados_grupo_chat[0]['nome'];
        $filtro_grupo_chat = "AND id_grupo_atendimento_chat = '".$grupo_chat."' ";
    }else{
        $filtro_grupo_chat = "";
        $grupo_chat_nome = 'Todos';
    }

    $data_hoje = getDataHora();

    $gerado = "<span style=\"font-size: 14px;\"><strong>Gerado em: </strong> ".converteDataHora($data_hoje)."</span>";

    echo "<div class=\"col-md-10 col-md-offset-1\" style=\"padding: 0\">";
    echo "<legend style=\"text-align:center;\"><strong>Relatório de Grupos de Chat (Operadores) - Histórico</strong><br><span style=\"font-size: 14px;\">$gerado</legend>";
    echo "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong>Grupo de Chat - </strong>".$grupo_chat_nome." </legend>";

    $dados = DBRead('', 'tb_grupo_atendimento_chat', "WHERE status = 1 ".$filtro_grupo_chat." ORDER BY nome ASC");

    if ($dados) {
        foreach ($dados as $conteudo) {
            $cont=0;
            $dados_historico = DBRead('', 'tb_grupo_atendimento_chat_operador_historico', "WHERE id_grupo_atendimento_chat = '".$conteudo['id_grupo_atendimento_chat']."' GROUP BY data, id_grupo_atendimento_chat, id_usuario_alterou", "id_grupo_atendimento_chat, data, id_usuario_alterou");
            
            ?>

            <div class="panel panel-default" style="border-color: <?= $conteudo['cor'] ?>; border-width: 2px;">
                <div class="panel-heading clearfix">
                    <h3 class="panel-title text-left pull-left">
                        <i class="fa fa-comment" style="font-size: 20px; color: <?= $conteudo['cor'] ?> "></i>
                        <?=$conteudo['nome']?>
                    </h3>
                </div>
                <div class="panel-body">

                    <div class="row">
                        <div class="col-md-12">
                            <?php 
                            if ($dados_historico) {
                                foreach ($dados_historico as $conteudo_historico) {
                                    if($cont != 0){
                                    ?>
                                        <hr style="border-color: <?= $conteudo['cor'] ?>">
                                    <?php
                                    }
                                    $cont++;

                                    $dados_usuario_alterou = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE id_usuario = '".$conteudo_historico['id_usuario_alterou']."' ", "b.nome");

                                    ?>
                                    <div class="panel panel-default">
                                        <div class="panel-heading clearfix">
                                            <h3 class="panel-title text-left pull-left">Alterado por <?=$dados_usuario_alterou[0]['nome']?> em <?= converteDataHora($conteudo_historico['data'])?></h3>
                                        </div>
                                        <div class="panel-body">

                                            <?php
                                            $dados_operadores = DBRead('', 'tb_grupo_atendimento_chat_operador_historico a', "INNER JOIN tb_usuario b ON a.id_usuario = b.id_usuario INNER JOIN tb_pessoa c ON b.id_pessoa = c.id_pessoa WHERE a.id_grupo_atendimento_chat = '".$conteudo['id_grupo_atendimento_chat']."' AND a.data = '".$conteudo_historico['data']."' ORDER BY c.nome ASC ", "c.nome");

                                            if($dados_operadores){
                                                foreach ($dados_operadores as $operador) {
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
                                            
                                <?php
                                }
                            } else {
                                echo "<div class='col-md-12'>";
                                    echo "<table class='table table-bordered'>";
                                        echo "<tbody>";
                                            echo "<tr>";
                                                echo "<td class='text-center'> <h4>Não há histórico de contratos vínculados a este grupo!</h4></td>";
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

            <br>

            <?php
        }
    }
}

function relatorio_grupo_chat_historico ($grupo_chat) {

    if($grupo_chat){
        $dados_grupo_chat = DBRead('','tb_grupo_atendimento_chat',"WHERE id_grupo_atendimento_chat = '".$grupo_chat."' ", "nome");
        $grupo_chat_nome = $dados_grupo_chat[0]['nome'];
        $filtro_grupo_chat = "AND id_grupo_atendimento_chat = '".$grupo_chat."' ";
    }else{
        $filtro_grupo_chat = "";
        $grupo_chat_nome = 'Todos';
    }

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

    $data_hoje = getDataHora();

    $gerado = "<span style=\"font-size: 14px;\"><strong>Gerado em: </strong> ".converteDataHora($data_hoje)."</span>";

    echo "<div class=\"col-md-10 col-md-offset-1\" style=\"padding: 0\">";
    echo "<legend style=\"text-align:center;\"><strong>Relatório de Grupos de Chat (Contratos) - Histórico</strong><br><span style=\"font-size: 14px;\">$gerado</legend>";
    echo "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong>Grupo de Chat - </strong>".$grupo_chat_nome." </legend>";

    $dados = DBRead('', 'tb_grupo_atendimento_chat', "WHERE status = 1 ".$filtro_grupo_chat." ORDER BY nome ASC");

    if ($dados) {
        foreach ($dados as $conteudo) {
            $cont=0;
            $dados_historico = DBRead('', 'tb_grupo_atendimento_chat_contrato_historico', "WHERE id_grupo_atendimento_chat = '".$conteudo['id_grupo_atendimento_chat']."' GROUP BY data, id_grupo_atendimento_chat, id_usuario_alterou", "id_grupo_atendimento_chat, data, id_usuario_alterou");
            
            ?>

            <div class="panel panel-default" style="border-color: <?= $conteudo['cor'] ?>; border-width: 2px;">
                <div class="panel-heading clearfix">
                    <h3 class="panel-title text-left pull-left">
                        <i class="fa fa-comment" style="font-size: 20px; color: <?= $conteudo['cor'] ?> "></i>
                        <?=$conteudo['nome']?>
                    </h3>
                </div>
                <div class="panel-body">

                    <div class="row">
                        <div class="col-md-12">
                            <?php 
                            if ($dados_historico) {
                                foreach ($dados_historico as $conteudo_historico) {
                                    if($cont != 0){
                                    ?>
                                        <hr style="border-color: <?= $conteudo['cor'] ?>">
                                    <?php
                                    }
                                    $cont++;

                                    $dados_usuario_alterou = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE id_usuario = '".$conteudo_historico['id_usuario_alterou']."' ", "b.nome");

                                    ?>
                                    <div class="panel panel-default">
                                        <div class="panel-heading clearfix">
                                            <h3 class="panel-title text-left pull-left">Alterado por <?=$dados_usuario_alterou[0]['nome']?> em <?= converteDataHora($conteudo_historico['data'])?></h3>
                                        </div>
                                        <div class="panel-body">

                                            <?php
                                            $dados_contratos = DBRead('', 'tb_grupo_atendimento_chat_contrato_historico a', "INNER JOIN tb_contrato_plano_pessoa b ON a.id_contrato_plano_pessoa = b.id_contrato_plano_pessoa INNER JOIN tb_pessoa c ON b.id_pessoa = c.id_pessoa WHERE id_grupo_atendimento_chat = '".$conteudo['id_grupo_atendimento_chat']."' AND data = '".$conteudo_historico['data']."' ", "b.id_contrato_plano_pessoa, b.id_pessoa, c.nome");

                                            if($dados_contratos){
                                                foreach ($dados_contratos as $conteudo_contrato) {

                                                ?>
                                                    <div class="panel panel-default">
                                                        <div class="panel-heading clearfix">
                                                            <h3 class="panel-title text-left pull-left"><?=$conteudo_contrato['nome']?></h3>
                                                        </div>
                                                    <div class="panel-body">

                                                    <?php

                                                    $horarios = DBRead('', 'tb_horario_contrato a', "INNER JOIN tb_horario b ON a.id_horario_contrato = b.id_horario_contrato WHERE a.id_contrato_plano_pessoa = '".$conteudo_contrato['id_contrato_plano_pessoa']."' AND a.tipo = 9");

                                                    foreach ($horarios as $hora) {
                                                        $hora_inicio = explode(':', $hora['hora_inicio']);
                                                        $hora_inicio = $hora_inicio[0].":".$hora_inicio[1];
                                                        $hora_fim = explode(':', $hora['hora_fim']);
                                                        $hora_fim = $hora_fim[0].":".$hora_fim[1];
            
                                                        echo "Início: ".$hora_inicio." - Final: ".$hora_fim." - ".$tipo_dia_atendimento[$hora['dia']]."<br>";
                                                    } ?>
                                        </div>
                                    </div>
                                            <?php
                                            } 
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
                                                echo "<td class='text-center'> <h4>Não há histórico de contratos vínculados a este grupo!</h4></td>";
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

            <br>

            <?php
        }
    }
}

function relatorio_grupo_chat ($id_contrato_plano_pessoa) {

    if($id_contrato_plano_pessoa){
        $dados_contrato = DBRead('','tb_contrato_plano_pessoa a',"INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_contrato_plano_pessoa = '".$id_contrato_plano_pessoa."' ", "nome");
        $contrato_nome = $dados_contrato[0]['nome'];
        $filtro_contrato_plano_pessoa = "AND a.id_contrato_plano_pessoa = '".$id_contrato_plano_pessoa."' ";
    }else{
        $filtro_contrato_plano_pessoa = "";
        $contrato_nome = 'Qualquer';
    }

    $data_hoje = getDataHora();

    $gerado = "<span style=\"font-size: 14px;\"><strong>Gerado em: </strong> ".converteDataHora($data_hoje)."</span>";

    echo "<div class=\"col-md-10 col-md-offset-1\" style=\"padding: 0\">";
    echo "<legend style=\"text-align:center;\"><strong>Relatório de Grupos de Chat</strong><br><span style=\"font-size: 14px;\">$gerado</legend>";
    echo "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong>Contrato - </strong>".$contrato_nome." </legend>";

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

            $contratos = DBRead('', 'tb_grupo_atendimento_chat_contrato a', "INNER JOIN tb_contrato_plano_pessoa b ON a.id_contrato_plano_pessoa = b.id_contrato_plano_pessoa INNER JOIN tb_pessoa c ON b.id_pessoa = c.id_pessoa WHERE id_grupo_atendimento_chat = '".$conteudo['id_grupo_atendimento_chat']."' $filtro_contrato_plano_pessoa ", "b.id_contrato_plano_pessoa, b.id_pessoa, c.nome");

            $operadores = DBRead('', 'tb_grupo_atendimento_chat_operador a', "INNER JOIN tb_usuario b ON a.id_usuario = b.id_usuario INNER JOIN tb_pessoa c ON b.id_pessoa = c.id_pessoa WHERE id_grupo_atendimento_chat = '".$conteudo['id_grupo_atendimento_chat']."' ", 'c.nome');
            
            if($contratos){
                $contratos = DBRead('', 'tb_grupo_atendimento_chat_contrato a', "INNER JOIN tb_contrato_plano_pessoa b ON a.id_contrato_plano_pessoa = b.id_contrato_plano_pessoa INNER JOIN tb_pessoa c ON b.id_pessoa = c.id_pessoa WHERE id_grupo_atendimento_chat = '".$conteudo['id_grupo_atendimento_chat']."' ", "b.id_contrato_plano_pessoa, b.id_pessoa, c.nome");

            ?>

                <div class="panel panel-default" style="border-color: <?= $conteudo['cor'] ?>; border-width: 2px;">
                    <div class="panel-heading clearfix">
                        <h3 class="panel-title text-left pull-left">
                            <i class="fa fa-comment" style="font-size: 20px; color: <?= $conteudo['cor'] ?> "></i>
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
}

?>                  