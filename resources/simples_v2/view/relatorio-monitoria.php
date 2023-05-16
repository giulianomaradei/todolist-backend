<?php
require_once(__DIR__."/../class/System.php");

$data_referencia = getDataHora();

$arrayData = explode(" ", $data_referencia);

$mes_referencia = explode("-", $arrayData[0]);

$mes = (!empty($_POST['mes'])) ? $_POST['mes'] : $mes_referencia[1];
$ano = (!empty($_POST['ano'])) ? $_POST['ano'] : $mes_referencia[0];
$canal_atendimento = (!empty($_POST['canal_atendimento'])) ? $_POST['canal_atendimento'] : 1;
$formulario = (!empty($_POST['formulario'])) ? $_POST['formulario'] : '';
$classificacao = (!empty($_POST['classificacao'])) ? $_POST['classificacao'] : 3;
$lider = (!empty($_POST['lider'])) ? $_POST['lider'] : $lider;
$analista = (!empty($_POST['analista'])) ? $_POST['analista'] : $analista;
$usuario = (!empty($_POST['usuario'])) ? $_POST['usuario'] : '';
$gerar = (!empty($_POST['gerar'])) ? 1 : 0;
$id_usuario = $_SESSION['id_usuario'];
$dados = DBRead('', 'tb_usuario', "WHERE id_usuario = '$id_usuario'");
$perfil_sistema = $dados[0]['id_perfil_sistema'];
$tipo_relatorio = (!empty($_POST['tipo_relatorio'])) ? $_POST['tipo_relatorio'] : '1';
$mostrar_recorrencias = (!empty($_POST['mostrar_recorrencias'])) ? $_POST['mostrar_recorrencias'] : '';
$lider_recorrencia = (!empty($_POST['lider_recorrencia'])) ? $_POST['lider_recorrencia'] : '';

$data_hoje = getDataHora();
$data_hoje = explode(" ", $data_hoje);
$data_hoje = $data_hoje[0];
$primeiro_dia = "01/" . $data_hoje[5] . $data_hoje[6] . "/" . $data_hoje[0] . $data_hoje[1] . $data_hoje[2] . $data_hoje[3];

$data_ate = (!empty($_POST['data_ate'])) ? $_POST['data_ate'] : converteData(getDataHora('data'));

$data = (!empty($_POST['data'])) ? $_POST['data'] : $data_ate;

$data_dia = (!empty($_POST['data_dia'])) ? $_POST['data_dia'] : $data_ate;

$primeiro_dia = new DateTime(getDataHora('data'));
$primeiro_dia->modify('first day of last month');
$primeiro_dia = $primeiro_dia->format('Y-m-d');
$mes_inicio_input = $primeiro_dia;
$primeiro_dia = converteData($primeiro_dia);

$mes_fim_input = new DateTime(getDataHora('data'));
$mes_fim_input->modify('first day of this month');
$mes_fim_input = $mes_fim_input->format('Y-m-d');

$mes_inicio = (!empty($_POST['mes_inicio'])) ? $_POST['mes_inicio'] : $mes_inicio_input;
$mes_fim = (!empty($_POST['mes_fim'])) ? $_POST['mes_fim'] : $mes_fim_input;

$primeiro_dia_desse_mes = new DateTime(getDataHora('data'));
$primeiro_dia_desse_mes->modify('first day of this month');
$primeiro_dia_desse_mes = $primeiro_dia_desse_mes->format('Y-m-d');
$primeiro_dia_desse_mes = converteData($primeiro_dia_desse_mes);
$data_dias_de = (!empty($_POST['data_dias_de'])) ? $_POST['data_dias_de'] : $primeiro_dia_desse_mes;
$data_dias_ate = (!empty($_POST['data_dias_ate'])) ? $_POST['data_dias_ate'] : converteData(getDataHora('data'));

$usuario2 = (!empty($_POST['usuario2'])) ? $_POST['usuario2'] : '';
$acao = (!empty($_POST['acao'])) ? $_POST['acao'] : '';
$parabenizado = (!empty($_POST['parabenizado'])) ? $_POST['parabenizado'] : '1';

if ($gerar) {
	$collapse = '';
	$collapse_icon = 'plus';
} else {
	$collapse = 'in';
	$collapse_icon = 'minus';
}

if ($tipo_relatorio == 1) {
	$display_row_lider = 'style="display:none;"';
	$display_row_atendente = '';
	$display_row_analistas = 'style="display: none;"';
	$display_row_periodo = '';
	$display_row_data = 'style="display: none;"';
	$display_row_atendente2 = 'style="display: none;"';
	$display_row_acao = 'style="display: none;"';
	$display_row_parabenizado = 'style="display: none;"';
	$display_row_periodo_recorrencia = 'style="display: none;"';
	$display_row_mostrar_recorrencia = 'style="display: none;"';
	$display_row_lider_recorrencia = 'style="display: none;"';
	$display_row_formulario = '';
	$display_row_canal_atendimento = '';
	$display_row_classificacao = '';
	$display_row_periodo_dias = 'style="display: none;"';

} else if ($tipo_relatorio == 2) {
	$display_row_lider = '';
	$display_row_atendente = 'style="display: none;"';
	$display_row_analistas = 'style="display: none;"';
	$display_row_periodo = '';
	$display_row_data = 'style="display: none;"';
	$display_row_atendente2 = 'style="display: none;"';
	$display_row_acao = 'style="display: none;"';
	$display_row_parabenizado = 'style="display: none;"';
	$display_row_periodo_recorrencia = 'style="display: none;"';
	$display_row_mostrar_recorrencia = 'style="display: none;"';
	$display_row_lider_recorrencia = 'style="display: none;"';
	$display_row_canal_atendimento = '';
	$display_row_classificacao = '';
	$display_row_periodo_dias = 'style="display: none;"';

} else if ($tipo_relatorio == 3) {
	$display_row_lider = 'style="display: none;"';
	$display_row_atendente = 'style="display: none;"';
	$display_row_analistas = 'style="display: none;"';
	$display_row_periodo = '';
	$display_row_data = 'style="display: none;"';
	$display_row_atendente2 = 'style="display: none;"';
	$display_row_acao = 'style="display: none;"';
	$display_row_parabenizado = 'style="display: none;"';
	$display_row_periodo_recorrencia = 'style="display: none;"';
	$display_row_mostrar_recorrencia = 'style="display: none;"';
	$display_row_lider_recorrencia = 'style="display: none;"';
	$display_row_canal_atendimento = '';
	$display_row_classificacao = '';
	$display_row_periodo_dias = 'style="display: none;"';

} else if ($tipo_relatorio == 4) {
	$display_row_lider = 'style="display:none;"';
	$display_row_atendente = 'style="display: none;"';
	$display_row_periodo = 'style="display: block;"';
	$display_row_analistas = '';
	$display_row_data = 'style="display: none;"';
	$display_row_atendente2 = 'style="display: none;"';
	$display_row_acao = 'style="display: none;"';
	$display_row_parabenizado = 'style="display: none;"';
	$display_row_periodo_recorrencia = 'style="display: none;"';
	$display_row_mostrar_recorrencia = 'style="display: none;"';
	$display_row_lider_recorrencia = 'style="display: none;"';
	$display_row_formulario = '';
	$display_row_canal_atendimento = '';
	$display_row_classificacao = '';
	$display_row_periodo_dias = 'style="display: none;"';

} else if ($tipo_relatorio == 5) {

	$display_row_lider = 'style="display:none;"';
	$display_row_atendente = 'style="display: none;"';
	$display_row_periodo = 'style="display: none;"';
	$display_row_analistas = 'style="display: none;"';
	$display_row_data = '';
	$display_row_atendente2 = 'style="display: none;"';
	$display_row_acao = 'style="display: none;"';
	$display_row_parabenizado = 'style="display: none;"';
	$display_row_periodo_recorrencia = 'style="display: none;"';
	$display_row_mostrar_recorrencia = 'style="display: none;"';
	$display_row_lider_recorrencia = 'style="display: none;"';
	$display_row_formulario = 'style="display: none;"';
	$display_row_canal_atendimento = 'style="display: none;"';
	$display_row_classificacao = 'style="display: none;"';
	$display_row_periodo_dias = 'style="display: none;"';

} else if ($tipo_relatorio == 6) {

	$display_row_lider = 'style="display:none;"';
	$display_row_atendente = 'style="display:none;"';
	$display_row_analistas = 'style="display: none;"';
	$display_row_periodo = '';
	$display_row_data = 'style="display: none;"';
	$display_row_atendente2 = '';
	$display_row_acao = '';
	$display_row_parabenizado = '';
	$display_row_periodo_recorrencia = 'style="display: none;"';
	$display_row_mostrar_recorrencia = 'style="display: none;"';
	$display_row_lider_recorrencia = 'style="display: none;"';
	$display_row_formulario = '';
	$display_row_canal_atendimento = '';
	$display_row_classificacao = '';
	$display_row_periodo_dias = 'style="display: none;"';

} else if ($tipo_relatorio == 7) {

	$display_row_lider = 'style="display:none;"';
	$display_row_atendente = 'style="display: none;"';
	$display_row_analistas = 'style="display: none;"';
	$display_row_periodo = 'style="display: none;"';
	$display_row_data = 'style="display: none;"';
	$display_row_acao = 'style="display: none;"';
	$display_row_parabenizado = 'style="display: none;"';
	$display_row_periodo_recorrencia = '';
	$display_row_mostrar_recorrencia = '';
	$display_row_formulario = 'style="display: none;"';
	$display_row_canal_atendimento = '';
	$display_row_classificacao = '';
	$display_row_periodo_dias = 'style="display: none;"';

	if ($lider_recorrencia == '') {
		$display_row_atendente2 = '';
		$display_row_lider_recorrencia = '';
	} else {
		$display_row_atendente2 = 'style="display: none;"';
		$display_row_lider_recorrencia = '';
	}

} else if ($tipo_relatorio == 8) {
	$display_row_lider = 'style="display: none;"';
	$display_row_atendente = 'style="display: none;"';
	$display_row_analistas = 'style="display: none;"';
	$display_row_periodo = '';
	$display_row_data = 'style="display: none;"';
	$display_row_atendente2 = 'style="display: none;"';
	$display_row_acao = 'style="display: none;"';
	$display_row_parabenizado = 'style="display: none;"';
	$display_row_periodo_recorrencia = 'style="display: none;"';
	$display_row_mostrar_recorrencia = 'style="display: none;"';
	$display_row_lider_recorrencia = 'style="display: none;"';
	$display_row_formulario = 'style="display: none;"';
	$display_row_canal_atendimento = 'style="display: none;"';
	$display_row_classificacao = '';
	$display_row_periodo_dias = 'style="display: none;"';

} else if ($tipo_relatorio == 9) {
	$display_row_lider = 'style="display: none;"';
	$display_row_atendente = 'style="display: none;"';
	$display_row_analistas = 'style="display: none;"';
	$display_row_periodo = 'style="display: none;"';
	$display_row_data = 'style="display: none;"';
	$display_row_atendente2 = 'style="display: none;"';
	$display_row_acao = 'style="display: none;"';
	$display_row_parabenizado = 'style="display: none;"';
	$display_row_periodo_recorrencia = 'style="display: none;"';
	$display_row_mostrar_recorrencia = 'style="display: none;"';
	$display_row_lider_recorrencia = 'style="display: none;"';
	$display_row_formulario = 'style="display: none;"';
	$display_row_canal_atendimento = '';
	$display_row_classificacao = 'style="display: none;"';
	$display_row_periodo_dias = '';

} else if ($tipo_relatorio == 10) {
	$display_row_lider = 'style="display: none;"';
	$display_row_atendente = 'style="display: none;"';
	$display_row_analistas = 'style="display: none;"';
	$display_row_periodo = 'style="display: none;"';
	$display_row_data = 'style="display: none;"';
	$display_row_atendente2 = 'style="display: none;"';
	$display_row_acao = 'style="display: none;"';
	$display_row_parabenizado = 'style="display: none;"';
	$display_row_periodo_recorrencia = 'style="display: none;"';
	$display_row_mostrar_recorrencia = 'style="display: none;"';
	$display_row_lider_recorrencia = 'style="display: none;"';
	$display_row_formulario = 'style="display: none;"';
	$display_row_canal_atendimento = '';
	$display_row_classificacao = 'style="display: none;"';
	$display_row_periodo_dias = '';

} else if ($tipo_relatorio == 11) {
	$display_row_lider = 'style="display: none;"';
	$display_row_atendente = 'style="display: none;"';
	$display_row_analistas = '';
	$display_row_periodo = 'style="display: none;"';
	$display_row_data = 'style="display: none;"';
	$display_row_atendente2 = 'style="display: none;"';
	$display_row_acao = 'style="display: none;"';
	$display_row_parabenizado = 'style="display: none;"';
	$display_row_periodo_recorrencia = 'style="display: none;"';
	$display_row_mostrar_recorrencia = 'style="display: none;"';
	$display_row_lider_recorrencia = 'style="display: none;"';
	$display_row_formulario = 'style="display: none;"';
	$display_row_canal_atendimento = 'style="display: none;"';
	$display_row_classificacao = 'style="display: none;"';
	$display_row_periodo_dias = '';
} else if ($tipo_relatorio == 12) {
	$display_row_lider = 'style="display: none;"';
	$display_row_atendente = 'style="display: none;"';
	$display_row_analistas = 'style="display: none;"';
	$display_row_periodo = 'style="display: none;"';
	$display_row_data = 'style="display: none;"';
	$display_row_atendente2 = '';
	$display_row_acao = 'style="display: none;"';
	$display_row_parabenizado = 'style="display: none;"';
	$display_row_periodo_recorrencia = '';
	$display_row_mostrar_recorrencia = 'style="display: none;"';
	$display_row_lider_recorrencia = 'style="display: none;"';
	$display_row_formulario = 'style="display: none;"';
	$display_row_canal_atendimento = '';
	$display_row_classificacao = 'style="display: none;"';
	$display_row_periodo_dias = 'style="display: none;"';
} else if ($tipo_relatorio == 13) {
	$display_row_lider = 'style="display:none;"';
	$display_row_atendente = 'style="display: none;"';
	$display_row_analistas = 'style="display: none;"';
	$display_row_periodo = '';
	$display_row_data = 'style="display: none;"';
	$display_row_atendente2 = '';
	$display_row_acao = 'style="display: none;"';
	$display_row_parabenizado = 'style="display: none;"';
	$display_row_periodo_recorrencia = 'style="display: none;"';
	$display_row_mostrar_recorrencia = 'style="display: none;"';
	$display_row_lider_recorrencia = 'style="display: none;"';
	$display_row_formulario = '';
	$display_row_canal_atendimento = '';
	$display_row_classificacao = '';
	$display_row_periodo_dias = 'style="display: none;"';
}

if ($perfil_sistema == 11) {
	$display_row_lider = 'style="display:none;"';
	$display_row_atendente = 'style="display: none;"';
	$display_row_analistas = 'style="display: none;"';
	$display_row_periodo = 'style="display: none;"';
	$display_row_data = 'style="display: none;"';
	$display_row_atendente2 = 'style="display: none;"';
	$display_row_acao = 'style="display: none;"';
	$display_row_parabenizado = 'style="display: none;"';
	$display_row_periodo_recorrencia = 'style="display: none;"';
	$display_row_mostrar_recorrencia = 'style="display: none;"';
	$display_row_lider_recorrencia = 'style="display: none;"';
	$display_row_formulario = 'style="display: none;"';
	$display_row_canal_atendimento = '';
	$display_row_classificacao = 'style="display: none;"';
	$display_row_periodo_dias = '';
}

?>

<style>
	@media print {
		.noprint {
			display: none;
		}

		body {
			font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
			padding-top: 0;
		}
	}

	.hr-resultado {
		border-top: 1px solid #A9A9A9;
		margin-bottom: 3px;
	}

	.table-responsive {
		border-radius: 3px;
	}

	.quesito {
		min-width: 900px !important;
	}

	.mes_referencia {
		min-width: 80px !important;
	}
</style>

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs/jszip-2.5.0/dt-1.10.18/b-1.5.4/b-colvis-1.5.4/b-flash-1.5.4/b-html5-1.5.4/b-print-1.5.4/r-2.2.2/datatables.min.css" />
<script type="text/javascript" src="https://cdn.datatables.net/v/bs/jszip-2.5.0/dt-1.10.18/b-1.5.4/b-colvis-1.5.4/b-flash-1.5.4/b-html5-1.5.4/b-print-1.5.4/r-2.2.2/datatables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/plug-ins/1.10.19/sorting/time.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/plug-ins/1.10.19/sorting/chinese-string.js"></script>

<div class="container-fluid">
	<form method="post" action="">
		<div class="row">
			<div class="col-md-4 col-md-offset-4">
				<div class="panel panel-default noprint">
					<div class="panel-heading clearfix">
						<h3 class="panel-title text-left pull-left" style="margin-top: 2px;">Relatório - Monitoria:</h3>
						<div class="panel-title text-right pull-right"><button data-toggle="collapse" data-target="#accordionRelatorio" class="btn btn-xs btn-info" type="button" title="Visualizar filtros"><i id="i_collapse" class="fa fa-<?= $collapse_icon ?>"></i></button></div>
					</div>

					<div id="accordionRelatorio" class="panel-collapse collapse <?= $collapse ?>">
						<div class="panel-body">

							<!-- row tipo relatorio -->
							<div class="row">
								<div class="col-md-12">
									<div class="form-group">
										<label>Tipo de Relatório:</label>
										<select name="tipo_relatorio" id="tipo_relatorio" class="form-control input-sm">

											<?php
											if ($perfil_sistema != 11) {
											?>
											<option value="1" <?php if ($tipo_relatorio == '1') {
																	echo 'selected';
																} ?>>Individual</option>

											<?php
											}

											if ($perfil_sistema != 3 && $perfil_sistema != '11') {
											?>
												<option value="2" <?php if ($tipo_relatorio == '2') {
																		echo 'selected';
																	} ?>>Equipe</option>
												<option value="3" <?php if ($tipo_relatorio == '3') {
																		echo 'selected';
																	} ?>>Geral</option>
											<?php
											}  
											if ($perfil_sistema != 11) {
											?>
											<option value="6" <?php if ($tipo_relatorio == '6') {
																	echo 'selected';
																} ?>>Plano de Ação</option>

											<?php
											}
											if ($perfil_sistema == 2 || $perfil_sistema == 18 || $perfil_sistema == 14 || $perfil_sistema == 13 || $perfil_sistema == 23 || $perfil_sistema == 30 || $perfil_sistema == 12 || $perfil_sistema == 15) {
												
											?>
												<option value="4" <?php if ($tipo_relatorio == '4') {
																		echo 'selected';
																	} ?>>Performance dos analistas</option>
												
												<option value="5" <?php if ($tipo_relatorio == '5') {
																		echo 'selected';
																	} ?>>Contagem de avaliações</option>
												<option value="11" <?php if ($tipo_relatorio == '11') {
																		echo 'selected';
																	} ?>>Contagem de avaliações - Agrupado/Mensal</option>
												<option value="8" <?php if ($tipo_relatorio == '8') {
																		echo 'selected';
																	} ?>>TMA dos áudios considerados por atendente</option>
												<option value="13" <?php if ($tipo_relatorio == '13') {
																		echo 'selected';
																	} ?>>Avaliações desconsideradas</option>
											<?php
											}

											if ($perfil_sistema == 2 || $perfil_sistema == 18 || $perfil_sistema == 14 || $perfil_sistema == 13 || $perfil_sistema == 23 || $perfil_sistema == 30 || $perfil_sistema == 12 || $perfil_sistema == 15) {

											?>
												<option value="7" <?php if ($tipo_relatorio == '7') {
																		echo 'selected';
																	} ?>>Recorrência</option>
												<option value="12" <?php if ($tipo_relatorio == '12') {
																		echo 'selected';
																	} ?>>Trajetória</option>
											<?php
											}

											if ($perfil_sistema != 3) {
											?>

												<option value="9" <?php if ($tipo_relatorio == '9') {
																		echo 'selected';
																	} ?>>Atendimento encantador</option>
												<option value="10" <?php if ($tipo_relatorio == '10') {
																		echo 'selected';
																	} ?>>Cliente irritado</option>
											<?php 
											} 
											?>
										</select>
									</div>
								</div>
							</div>
							<!-- end row tipo relatorio -->

							<!-- row periodo mes-->
							<div class="row" id="row_periodo" <?= $display_row_periodo ?>>
								<div class="col-md-6">
									<div class="form-group">
										<label>*Mês:</label>
										<select class="form-control input-sm" name="mes" id="mes" required onChange="selectFormulario()">
											<option value="01" <?= $mes == "01" ? "selected" : ""; ?>>Janeiro</option>
											<option value="02" <?= $mes == "02" ? "selected" : ""; ?>>Fevereiro</option>
											<option value="03" <?= $mes == "03" ? "selected" : ""; ?>>Março</option>
											<option value="04" <?= $mes == "04" ? "selected" : ""; ?>>Abril</option>
											<option value="05" <?= $mes == "06" ? "selected" : ""; ?>>Maio</option>
											<option value="06" <?= $mes == "06" ? "selected" : ""; ?>>Junho</option>
											<option value="07" <?= $mes == "07" ? "selected" : ""; ?>>Julho</option>
											<option value="08" <?= $mes == "08" ? "selected" : ""; ?>>Agosto</option>
											<option value="09" <?= $mes == "09" ? "selected" : ""; ?>>Setembro</option>
											<option value="10" <?= $mes == "10" ? "selected" : ""; ?>>Outubro</option>
											<option value="11" <?= $mes == "11" ? "selected" : ""; ?>>Novembro</option>
											<option value="12" <?= $mes == "12" ? "selected" : ""; ?>>Dezembro</option>
										</select>
									</div>
								</div>

								<div class="col-md-6">
									<div class="form-group">
										<label>*Ano:</label>
										<select class="form-control input-sm" name="ano" id="ano" onChange="selectFormulario()">
											<option value="2019" <?= $ano == "2019" ? "selected" : ""; ?>>2019</option>
											<option value="2020" <?= $ano == "2020" ? "selected" : ""; ?>>2020</option>
											<option value="2021" <?= $ano == "2021" ? "selected" : ""; ?>>2021</option>
											<option value="2022" <?= $ano == "2022" ? "selected" : ""; ?>>2022</option>
											<option value="2023" <?= $ano == "2023" ? "selected" : ""; ?>>2023</option>
											<option value="2024" <?= $ano == "2024" ? "selected" : ""; ?>>2024</option>
											<option value="2025" <?= $ano == "2025" ? "selected" : ""; ?>>2025</option>
										</select>
									</div>
								</div>
							</div>
							<!-- end row periodo -->

							<!-- row periodo reccorencia -->
							<div class="row" id="row_periodo_recorrencia" <?= $display_row_periodo_recorrencia ?>>
								<div class="col-md-6">
									<div class="form-group">
										<label>*Mês inicio:</label>
										<select class="form-control input-sm" name="mes_inicio">
											<?php
											$monitoria_mes = DBRead('', 'tb_monitoria_mes', "ORDER BY data_referencia DESC", 'DISTINCT data_referencia');
											$sel_mes_inicio[$mes_inicio] = 'selected';

											foreach ($monitoria_mes as $conteudo) {

												$data = converteData($conteudo['data_referencia']);
												$data = substr($data, 3);
												$selected = $mes_inicio == $conteudo['data_referencia'] ? "selected" : "";

												echo '<option value="' . $conteudo['data_referencia'] . '" ' . $selected . '>' . $data . '</option>';
											}
											?>
										</select>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label>*Mês fim:</label>
										<select class="form-control input-sm" name="mes_fim">
											<?php
											$monitoria_mes = DBRead('', 'tb_monitoria_mes', "ORDER BY data_referencia DESC", 'DISTINCT data_referencia');
											foreach ($monitoria_mes as $conteudo) {

												$data = converteData($conteudo['data_referencia']);
												$data = substr($data, 3);
												$selected = $mes_fim == $conteudo['data_referencia'] ? "selected" : "";

												echo '<option value="' . $conteudo['data_referencia'] . '" ' . $selected . '>' . $data . '</option>';
											}
											?>
										</select>
									</div>
								</div>
							</div>
							<!-- row periodo reccorencia -->

							<!-- row canal de atendimento -->
							<div class="row" id="row_canal_atendimento" <?= $display_row_canal_atendimento ?>>
								<div class="col-md-12">
									<div class="form-group">
										<label>*Canal de atendimento:</label>
										<select class="form-control input-sm" name="canal_atendimento" id="canal_atendimento" onChange="selectFormulario()">
											<?php
												$sel_canal[$canal_atendimento] = 'selected';
											?>
											<option value="1" <?= $sel_canal[1] ?>>via Telefone</option>
											<option value="2" <?= $sel_canal[2] ?>>via Texto</option>
										</select>
									</div>
								</div>
							</div>
							<!-- row periodo atendimento -->

							<!-- row canal de classificacao -->
							<div class="row" id="row_classificacao" <?= $display_row_classificacao ?>>
								<div class="col-md-12">
									<div class="form-group">
										<label>*Classificação de atendente:</label>
										<select class="form-control input-sm" name="classificacao" id="classificacao" onChange="selectFormulario()">
											<?php
												$sel_classificacao[$classificacao] = 'selected';
											?>
											<option value="1" <?= $sel_classificacao[1] ?>>Em treinamento</option>
											<option value="2" <?= $sel_classificacao[2] ?>>Período de experiência</option>
											<option value="3" <?= $sel_classificacao[3] ?>>Efetivado</option>
										</select>
									</div>
								</div>
							</div>
							<!-- row periodo atendimento -->

							<!-- row formularios -->
							<div class="row" id="formulario" <?= $display_row_formulario ?>>
								<div class="col-md-12">
									<div class="form-group">
										<label>*Formulário:</label>
										<select class="form-control input-sm" name="formulario" id="formulario">
											
										</select>
									</div>
								</div>
							</div>
							<!-- row periodo formularios -->

							<!-- row atendente -->
							<?php if ($perfil_sistema != '3') { ?>
								<div class="row" id="row_atendente" <?= $display_row_atendente ?>>
									<div class="col-md-12">
										<div class="form-group">
											<label for="">*Atendente:</label>
											<select name="usuario" class="form-control input-sm">
												<?php
												$dados_usuarios = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.status = '1' AND (id_perfil_sistema = 3) ORDER BY b.nome ASC", "a.id_usuario, b.nome");
												if ($dados_usuarios) {
													foreach ($dados_usuarios as $conteudo_usuarios) {
														$selected = $usuario == $conteudo_usuarios['id_usuario'] ? "selected" : "";
														echo "<option value='" . $conteudo_usuarios['id_usuario'] . "' " . $selected . ">" . $conteudo_usuarios['nome'] . "</option>";
													}
												}
												?>
											</select>
										</div>
									</div>
								</div>
							<?php } ?>
							<!-- end row atendente -->

							<!-- row açoes chamado -->
							<div class="row" id="row_acao" <?= $display_row_acao ?>>
								<div class="col-md-12">
									<div class="form-group">
										<label for="">*Mostrar Ações:</label>
										<select name="acao" class="form-control input-sm">
											<option value="1" <?php if ($acao == '1') {
																	echo 'selected';
																} ?>>Sim</option>
											<option value="" <?php if ($acao == '') {
																	echo 'selected';
																} ?>>Não</option>

										</select>
									</div>
								</div>
							</div>
							<!-- end açoes chamado -->

							<!-- row atingiu quesitos -->
							<div class="row" id="row_parabenizado" <?= $display_row_parabenizado ?>>
								<div class="col-md-12">
									<div class="form-group">
										<label for="">*Atingiu Quesitos:</label>
										<select name="parabenizado" class="form-control input-sm">
											<option value="3" <?php if ($parabenizado == '3') {
																	echo 'selected';
																} ?>>Qualquer</option>
											<option value="2" <?php if ($parabenizado == '2') {
																	echo 'selected';
																} ?>>Sim</option>
											<option value="1" <?php if ($parabenizado == '1') {
																	echo 'selected';
																} ?>>Não</option>

										</select>
									</div>
								</div>
							</div>
							<!-- end row atingiu quesitos -->

							<!-- row lider -->
							<?php if ($perfil_sistema != '3') { ?>
								<div class="row" id="row_lider_recorrencia" <?= $display_row_lider_recorrencia ?>>
									<div class="col-md-12">
										<div class="form-group">
											<label for="">Líder Direto:</label>
											<select name="lider_recorrencia" id="lider_recorrencia" class="form-control input-sm">
												<option value="">Todos</option>
												<?php
												$dados_lider = DBRead('', 'tb_usuario a', "INNER JOIN tb_usuario b ON a.lider_direto = b.id_usuario INNER JOIN tb_pessoa c ON b.id_pessoa = c.id_pessoa WHERE a.lider_direto AND a.status = '1' AND b.id_perfil_sistema = '15' GROUP BY  a.lider_direto, c.nome ORDER BY c.nome ASC", "a.lider_direto, c.nome");
												if ($dados_lider) {
													foreach ($dados_lider as $conteudo_lider) {
														$selected = $lider_recorrencia == $conteudo_lider['lider_direto'] ? "selected" : "";
														echo "<option value='" . $conteudo_lider['lider_direto'] . "' " . $selected . ">" . $conteudo_lider['nome'] . "</option>";
													}
												}
												?>
											</select>
										</div>
									</div>
								</div>
							<?php } ?>
							<!-- end row lider -->

							<!-- row atendente 2 -->
							<?php if ($perfil_sistema != '3') { ?>
								<div class="row" id="row_atendente2" <?= $display_row_atendente2 ?>>
									<div class="col-md-12">
										<div class="form-group">
											<label for="">Atendente:</label>
											<select name="usuario2" class="form-control input-sm">
												<option value="">Todos</option>
												<?php
												$dados_usuarios = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.status = '1' AND (id_perfil_sistema = 3) OR a.id_perfil_sistema = 15 ORDER BY b.nome ASC", "a.id_usuario, b.nome");

												var_dump($dados_usuarios);
												if ($dados_usuarios) {
													foreach ($dados_usuarios as $conteudo_usuarios) {
														$selected = $usuario2 == $conteudo_usuarios['id_usuario'] ? "selected" : "";
														echo "<option value='" . $conteudo_usuarios['id_usuario'] . "' " . $selected . ">" . $conteudo_usuarios['nome'] . "</option>";
													}
												}
												?>
											</select>
										</div>
									</div>
								</div>
							<?php } ?>
							<!-- end row atendente 2 -->

							<!-- row lider -->
							<?php if ($perfil_sistema != '3') { ?>
								<div class="row" id="row_lider" <?= $display_row_lider ?>>
									<div class="col-md-12">
										<div class="form-group">
											<label for="">*Líder Direto:</label>
											<select name="lider" class="form-control input-sm">
												<?php
												$dados_lider = DBRead('', 'tb_usuario a', "INNER JOIN tb_usuario b ON a.lider_direto = b.id_usuario INNER JOIN tb_pessoa c ON b.id_pessoa = c.id_pessoa WHERE a.lider_direto AND a.status = '1' AND b.id_perfil_sistema = '15' GROUP BY  a.lider_direto, c.nome ORDER BY c.nome ASC", "a.lider_direto, c.nome");
												if ($dados_lider) {
													foreach ($dados_lider as $conteudo_lider) {
														$selected = $lider == $conteudo_lider['lider_direto'] ? "selected" : "";
														echo "<option value='" . $conteudo_lider['lider_direto'] . "' " . $selected . ">" . $conteudo_lider['nome'] . "</option>";
													}
												}
												?>
											</select>
										</div>
									</div>
								</div>
							<?php } ?>
							<!-- end row lider -->

							<!-- row analistas -->
							<?php if ($perfil_sistema == 2 || $perfil_sistema == 18) { ?>
								<div class="row" id="row_analistas" <?= $display_row_analistas ?>>
									<div class="col-md-12">
										<div class="form-group">
											<label for="">Analistas:</label>
											<select name="analista" class="form-control input-sm">
												<option value="">Todos</option>
												<?php
												$dados_analistas = DBRead('', 'tb_monitoria_avaliacao_audio a', "INNER JOIN tb_usuario b ON b.id_usuario = a.id_usuario_analista INNER JOIN tb_pessoa c ON b.id_pessoa = c.id_pessoa WHERE b.status = '1' UNION SELECT d.id_usuario_analista, f.nome FROM tb_monitoria_avaliacao_texto d INNER JOIN tb_usuario e ON e.id_usuario = d.id_usuario_analista INNER JOIN tb_pessoa f ON e.id_pessoa = f.id_pessoa WHERE e.status = '1' ORDER BY nome ASC", "a.id_usuario_analista, c.nome");
												if ($dados_analistas) {
													foreach ($dados_analistas as $conteudo_analista) {
														$selected = $analista == $conteudo_analista['id_usuario_analista'] ? "selected" : "";
														echo "<option value='" . $conteudo_analista['id_usuario_analista'] . "' " . $selected . ">" . $conteudo_analista['nome'] . "</option>";
													}
												}
												?>
											</select>
										</div>
									</div>
								</div>
							<?php } ?>
							<!-- end row analistas -->

							<!-- row datas -->
							<div class="row" id="row_data" <?= $display_row_data ?>>
								<div class="col-md-12">
									<div class="form-group">
										<label>*Data:</label>
										<input type="text" class="form-control date calendar input-sm hasDatepicker" name="data_dia" value="<?= $data_dia ?>" id="" autocomplete="off" placeholder="dd/mm/aaaa" maxlength="10">
									</div>
								</div>
							</div>
							<!-- end row datas -->

							<!-- row mostrar recorrencias -->
							<div class="row" id="row_mostrar_recorrencia" <?= $display_row_mostrar_recorrencia ?>>
								<div class="col-md-12">
									<div class="form-group">
										<label>*Exibição:</label>
										<select class="form-control input-sm" name="mostrar_recorrencias">
											<?php
											$sel_mostrar_recorrencia[$mostrar_recorrencias] = 'selected';
											?>
											<option value="" <?= $sel_mostrar_recorrencia[''] ?>>Mostrar todos quesitos</option>
											<option value="1" <?= $sel_mostrar_recorrencia['1'] ?>>Somente quesitos reprovados</option>
										</select>
									</div>
								</div>
							</div>
							<!-- end row mostrar recorrencias -->
							
							<!-- row periodo dias -->
							<div class="row" id="row_periodo_dias" <?= $display_row_periodo_dias ?>>
								<div class="col-md-6">
									<div class="form-group" >
								        <label>*Data Inicial:</label>
								        <input type="text" class="form-control input-sm date calendar" name="data_dias_de" value="<?=$data_dias_de?>">
								    </div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
								        <label>*Data Final:</label>
								        <input type="text" class="form-control input-sm date calendar" name="data_dias_ate" value="<?=$data_dias_ate?>">
								    </div>
								</div>
							</div>
							<!-- row periodo dias -->
						</div>
					</div>

					<div class="panel-footer">
						<div class="row">
							<div id="panel_buttons" class="col-md-12" style="text-align: center">
								<button class="btn btn-primary" name="gerar" id="gerar" value="1" type="submit" disabled>
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

	<div id="aguarde" class="alert alert-info text-center">
		Aguarde, gerando relatório... <i class="fa fa-spinner faa-spin animated"></i>
	</div>

	<div id="resultado" class="row" style="display:none;">
		<?php
		if ($gerar) {

			if ($perfil_sistema == 3) {
				$usuario = $id_usuario;
			}

			if ($tipo_relatorio == 1) {

				if ($canal_atendimento == 1) {
					relatorio_monitoria_individual_telefone($mes, $ano, $usuario, $perfil_sistema,  $classificacao, $formulario);

				} else if ($canal_atendimento == 2) {
					relatorio_monitoria_individual_texto($mes, $ano, $usuario, $perfil_sistema, $classificacao, $formulario);
				}
				
			} else if (($tipo_relatorio == 2 || $tipo_relatorio == 3) && ($perfil_sistema != 3)) {

				if ($canal_atendimento == 1) {
					relatorio_monitoria_geral_telefone($mes, $ano, $lider, $tipo_relatorio, $classificacao, $formulario);

				} else if ($canal_atendimento == 2) {
					relatorio_monitoria_geral_texto($mes, $ano, $lider, $tipo_relatorio, $classificacao, $formulario);
				}

			} else if (($tipo_relatorio == 4) && ($perfil_sistema == 2 || $perfil_sistema == 18 || $perfil_sistema == 14 || $perfil_sistema == 13 || $perfil_sistema == 23 || $perfil_sistema == 30 || $perfil_sistema == 12)) {

				if ($perfil_sistema == 14 || $perfil_sistema == 13) {
					$analista = $id_usuario;
				}

				if ($canal_atendimento == 1) {
					relatorio_performance_analista_telefone($analista, $mes, $ano, $classificacao, $formulario);
				
				} else if ($canal_atendimento == 2) {
					relatorio_performance_analista_texto($analista, $mes, $ano, $classificacao, $formulario);
				}

			} else if (($tipo_relatorio == 5) && ($perfil_sistema == 2 || $perfil_sistema == 18 || $perfil_sistema == 14 || $perfil_sistema == 13 || $perfil_sistema == 23 || $perfil_sistema == 30 || $perfil_sistema == 12)) {

				if ($perfil_sistema == 14 || $perfil_sistema == 13) {
					$analista = $id_usuario;
				} else {
					$analista = '';
				}

				relatorio_contagem_avaliacoes($analista, $data_dia);

			} else if ($tipo_relatorio == 6) {

				if ($perfil_sistema == 3) {
					$usuario2 = $id_usuario;
				}

				relatorio_plano_de_acao($mes, $ano, $usuario2, $acao, $parabenizado, $canal_atendimento, $classificacao, $formulario);

			} else if (($tipo_relatorio == 7) &&  ($perfil_sistema == 2 || $perfil_sistema == 18 || $perfil_sistema == 14 || $perfil_sistema == 13  || $perfil_sistema == 23 || $perfil_sistema == 30 || $perfil_sistema == 12)) {

				if ($canal_atendimento == 1) {
					relatorio_recorrencia_telefone($mes_inicio, $mes_fim, $usuario2, $lider_recorrencia, $mostrar_recorrencias, $classificacao);

				} else if ($canal_atendimento == 2) {
					relatorio_recorrencia_texto($mes_inicio, $mes_fim, $usuario2, $lider_recorrencia, $mostrar_recorrencias, $classificacao);
				}	

			} else if (($tipo_relatorio == 8) && ($perfil_sistema == 2 || $perfil_sistema == 18 || $perfil_sistema == 14 || $perfil_sistema == 13  || $perfil_sistema == 23 || $perfil_sistema == 30 || $perfil_sistema == 12)) {

				relatorio_tma_atendente($mes, $ano,$classificacao);

			} else if ($tipo_relatorio == 9) {
				
				if ($canal_atendimento == 1) {
					relatorio_atendimento_encantador_telefone($data_dias_de, $data_dias_ate);

				} else if ($canal_atendimento == 2) {
					relatorio_atendimento_encantador_texto($data_dias_de, $data_dias_ate);
				}
			} else if (($tipo_relatorio == 10) && ($perfil_sistema != 3)){
				
				if ($canal_atendimento == 1) {
					relatorio_cliente_irritado_telefone($data_dias_de, $data_dias_ate);

				} else if ($canal_atendimento == 2) {
					relatorio_cliente_irritado_texto($data_dias_de, $data_dias_ate);
				}
			} else if (($tipo_relatorio == 11) && ($perfil_sistema == 2 || $perfil_sistema == 18 || $perfil_sistema == 14 || $perfil_sistema == 13 || $perfil_sistema == 23 || $perfil_sistema == 30 || $perfil_sistema == 12)) {
				
				if ($perfil_sistema == 14 || $perfil_sistema == 13) {
					$analista = $id_usuario;
				}

				relatorio_contagem_avaliacoes_mensal($analista, $data_dias_de, $data_dias_ate);

			} else if ($tipo_relatorio == 12) {

				if ($canal_atendimento == 1) {
					relatorio_trajetoria_telefone($mes_inicio, $mes_fim, $usuario2);

				} else if ($canal_atendimento == 2) {
					relatorio_trajetoria_texto($mes_inicio, $mes_fim, $usuario2);
				}

			} else if ($tipo_relatorio == 13) {

				if ($canal_atendimento == 1) {
					relatorio_monitoria_individual_telefone_desconsiderado($mes, $ano, $usuario2, $perfil_sistema, $classificacao, $formulario);

				} else if ($canal_atendimento == 2) {
					relatorio_monitoria_individual_texto_desconsiderado($mes, $ano, $usuario2, $perfil_sistema, $classificacao, $formulario);
				}

			}else {
				echo '<div class="alert alert-danger text-center">Erro ao exibir relatório!</div>';
			}
		}
		?>
	</div>
</div>

<script>
	$('#tipo_relatorio').on('change', function() {
		tipo_relatorio = $(this).val();

		if (tipo_relatorio == 1) {
			$('#row_lider').hide();
			$('#row_atendente').show();
			$('#row_analistas').hide();
			$('#row_periodo').show();
			$('#row_data').hide();
			$('#row_atendente2').hide();
			$('#row_acao').hide();
			$('#row_parabenizado').hide();
			$('#row_mostrar_recorrencia').hide();
			$('#row_periodo_recorrencia').hide();
			$('#row_lider_recorrencia').hide();
			$('#row_canal_atendimento').show();
			$('#row_classificacao').show();
			$('#formulario').show();
			$('#row_periodo_dias').hide();

		} else if (tipo_relatorio == 2) {
			$('#row_lider').show();
			$('#row_atendente').hide();
			$('#row_analistas').hide();
			$('#row_periodo').show();
			$('#row_data').hide();
			$('#row_atendente2').hide();
			$('#row_acao').hide();
			$('#row_parabenizado').hide();
			$('#row_mostrar_recorrencia').hide();
			$('#row_periodo_recorrencia').hide();
			$('#row_lider_recorrencia').hide();
			$('#row_canal_atendimento').show();
			$('#row_classificacao').show();
			$('#formulario').show();
			$('#row_periodo_dias').hide();

		} else if (tipo_relatorio == 3) {
			$('#row_lider').hide();
			$('#row_atendente').hide();
			$('#row_analistas').hide();
			$('#row_periodo').show();
			$('#row_data').hide();
			$('#row_atendente2').hide();
			$('#row_acao').hide();
			$('#row_parabenizado').hide();
			$('#row_mostrar_recorrencia').hide();
			$('#row_periodo_recorrencia').hide();
			$('#row_lider_recorrencia').hide();
			$('#row_canal_atendimento').show();
			$('#row_classificacao').show();
			$('#formulario').show();
			$('#row_periodo_dias').hide();

		} else if (tipo_relatorio == 4) {
			$('#row_lider').hide();
			$('#row_atendente').hide();
			$('#row_analistas').show();
			$('#row_periodo').show();
			$('#row_data').hide();
			$('#row_atendente2').hide();
			$('#row_acao').hide();
			$('#row_parabenizado').hide();
			$('#row_mostrar_recorrencia').hide();
			$('#row_periodo_recorrencia').hide();
			$('#row_canal_atendimento').show();
			$('#row_classificacao').show();
			$('#formulario').show();
			$('#row_periodo_dias').hide();

		} else if (tipo_relatorio == 5) {
			$('#row_lider').hide();
			$('#row_atendente').hide();
			$('#row_analistas').hide();
			$('#row_periodo').hide();
			$('#row_data').show();
			$('#row_atendente2').hide();
			$('#row_acao').hide();
			$('#row_parabenizado').hide();
			$('#row_mostrar_recorrencia').hide();
			$('#row_periodo_recorrencia').hide();
			$('#row_lider_recorrencia').hide();
			$('#row_canal_atendimento').hide();
			$('#row_classificacao').hide();
			$('#formulario').hide();
			$('#row_periodo_dias').hide();

		} else if (tipo_relatorio == 6) {
			$('#row_lider').hide();
			$('#row_atendente').hide();
			$('#row_analistas').hide();
			$('#row_periodo').show();
			$('#row_data').hide();
			$('#row_atendente2').show();
			$('#row_acao').show();
			$('#row_parabenizado').show();
			$('#row_mostrar_recorrencia').hide();
			$('#row_periodo_recorrencia').hide();
			$('#row_lider_recorrencia').hide();
			$('#row_canal_atendimento').show();
			$('#row_classificacao').show();
			$('#formulario').show();
			$('#row_periodo_dias').hide();

		} else if (tipo_relatorio == 7) {
			$('#row_lider').hide();
			$('#row_atendente').hide();
			$('#row_analistas').hide();
			$('#row_periodo').hide();
			$('#row_data').hide();
			$('#row_atendente2').show();
			$('#row_acao').hide();
			$('#row_parabenizado').hide();
			$('#row_mostrar_recorrencia').show();
			$('#row_periodo_recorrencia').show();
			$('#row_lider_recorrencia').show();
			$('#row_canal_atendimento').show();
			$('#row_classificacao').show();
			$('#formulario').hide();
			$('#row_periodo_dias').hide();

		} else if (tipo_relatorio == 8) {
			$('#row_lider').hide();
			$('#row_atendente').hide();
			$('#row_analistas').hide();
			$('#row_periodo').show();
			$('#row_data').hide();
			$('#row_atendente2').hide();
			$('#row_acao').hide();
			$('#row_parabenizado').hide();
			$('#row_mostrar_recorrencia').hide();
			$('#row_periodo_recorrencia').hide();
			$('#row_lider_recorrencia').hide();
			$('#row_canal_atendimento').hide();
			$('#row_classificacao').show();
			$('#formulario').hide();
			$('#row_periodo_dias').hide();

		} else if (tipo_relatorio == 9) {
			$('#row_lider').hide();
			$('#row_atendente').hide();
			$('#row_analistas').hide();
			$('#row_periodo').hide();
			$('#row_data').hide();
			$('#row_atendente2').hide();
			$('#row_acao').hide();
			$('#row_parabenizado').hide();
			$('#row_mostrar_recorrencia').hide();
			$('#row_periodo_recorrencia').hide();
			$('#row_lider_recorrencia').hide();
			$('#row_canal_atendimento').show();
			$('#row_classificacao').hide();
			$('#formulario').hide();
			$('#formulario').hide();
			$('#row_periodo_dias').show();

		} else if (tipo_relatorio == 10) {
			$('#row_lider').hide();
			$('#row_atendente').hide();
			$('#row_analistas').hide();
			$('#row_periodo').hide();
			$('#row_data').hide();
			$('#row_atendente2').hide();
			$('#row_acao').hide();
			$('#row_parabenizado').hide();
			$('#row_mostrar_recorrencia').hide();
			$('#row_periodo_recorrencia').hide();
			$('#row_lider_recorrencia').hide();
			$('#row_canal_atendimento').show();
			$('#row_classificacao').hide();
			$('#formulario').hide();
			$('#formulario').hide();
			$('#row_periodo_dias').show();

		} else if (tipo_relatorio == 11) {
			$('#row_lider').hide();
			$('#row_atendente').hide();
			$('#row_analistas').show();
			$('#row_periodo').hide();
			$('#row_data').hide();
			$('#row_atendente2').hide();
			$('#row_acao').hide();
			$('#row_parabenizado').hide();
			$('#row_mostrar_recorrencia').hide();
			$('#row_periodo_recorrencia').hide();
			$('#row_lider_recorrencia').hide();
			$('#row_canal_atendimento').hide();
			$('#row_classificacao').hide();
			$('#formulario').hide();
			$('#formulario').hide();
			$('#row_periodo_dias').show();

		} else if (tipo_relatorio == 12) {
			$('#row_lider').hide();
			$('#row_atendente').hide();
			$('#row_analistas').hide();
			$('#row_periodo').hide();
			$('#row_data').hide();
			$('#row_atendente2').show();
			$('#row_acao').hide();
			$('#row_parabenizado').hide();
			$('#row_mostrar_recorrencia').hide();
			$('#row_periodo_recorrencia').show();
			$('#row_lider_recorrencia').hide();
			$('#row_canal_atendimento').show();
			$('#row_classificacao').hide();
			$('#formulario').hide();
			$('#formulario').hide();
			$('#row_periodo_dias').hide();
		} else if (tipo_relatorio == 13) {
			$('#row_lider').hide();
			$('#row_atendente').hide();
			$('#row_analistas').hide();
			$('#row_periodo').show();
			$('#row_data').hide();
			$('#row_atendente2').show();
			$('#row_acao').hide();
			$('#row_parabenizado').hide();
			$('#row_mostrar_recorrencia').hide();
			$('#row_periodo_recorrencia').hide();
			$('#row_lider_recorrencia').hide();
			$('#row_canal_atendimento').show();
			$('#row_classificacao').show();
			$('#formulario').show();
			$('#row_periodo_dias').hide();
		}
	});

	$('#lider_recorrencia').on('change', function() {
		var option = $(this).val();

		if (option != '') {
			$('#row_atendente2').hide();
		} else {
			$('#row_atendente2').show();
		}
	});

	$('#accordionRelatorio').on('shown.bs.collapse', function() {
		$("#i_collapse").removeClass("fa fa-plus").addClass("fa fa-minus");
	});

	$('#accordionRelatorio').on('hidden.bs.collapse', function() {
		$("#i_collapse").removeClass("fa fa-minus").addClass("fa fa-plus");
	});

	$(document).on('submit', 'form', function() {
		modalAguarde();
	});

	function selectFormulario(){

		var mes = $('#mes').val();
		var ano = $('#ano').val();
		var canal_atendimento = $('#canal_atendimento').val();
		var classificacao = $('#classificacao').val();

		if(mes != "" && ano != "" && canal_atendimento != ""  && classificacao != "" ){
			$.ajax({
				url: "/api/ajax?class=SelectFormularioMonitoria.php",
				dataType: "html",
				data: {
					acao: 'busca_formulario',
					parametros: {
						'mes' : mes,
						'ano' : ano,
						'canal_atendimento' : canal_atendimento,
						'classificacao' : classificacao
					},
					token: '<?= $request->token ?>'
				},
				success: function (data) {
					$("select[name=formulario]").empty();                    
					$("select[name=formulario]").append(data);                    
				}
			});
		}
	}

	$(document).ready(function() {
		$('#aguarde').hide();
		$('#resultado').show();
		$("#gerar").prop("disabled", false);
		selectFormulario();
	});

	$(function() {
		$('[data-toggle="tooltip"]').tooltip();
	});	
</script>

<?php

function relatorio_monitoria_individual_telefone($mes, $ano, $usuario, $perfil_sistema, $classificacao, $formulario)
{

	$fila = "'callATENDIMENTOvip','callATENDIMENTOalta','callATENDIMENTOnormal','callATENDIMENTObaixa','callATENDIMENTOnotificacaoparada', 'callATENDIMENTOvipEXT','callATENDIMENTOaltaEXT','callATENDIMENTOnormalEXT','callATENDIMENTObaixaEXT','callATENDIMENTOnotificacaoparadaEXT','callATENDIMENTOvipEXP','callATENDIMENTOaltaEXP','callATENDIMENTOnormalEXP','callATENDIMENTObaixaEXP','callATENDIMENTOnotificacaoparadaEXP'";

	$data_hora = converteDataHora(getDataHora());

	$data_referencia = $ano . '-' . $mes . '-01';
	$legenda_mes_referencia = $mes . '/' . $ano;

	$dados_usuario = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_usuario = '$usuario'", "b.nome");
	$nome_usuario = $dados_usuario[0]['nome'];
	//$id_asterisk_usuario = $dados_usuario[0]['id_asterisk'];

	$turno = DBRead('', 'tb_horarios_escala', "WHERE id_usuario = '$usuario' AND data_inicial = '$data_referencia' ", 'inicial_seg, final_seg');

	if ($turno) {
		$inicial_seg = $turno[0]['inicial_seg'];
		$final_seg = $turno[0]['final_seg'];

		if ($inicial_seg > $final_seg) {
			$hora1 = '2000-10-10 ' . $inicial_seg . ':00';
			$hora2 = '2000-10-11 ' . $final_seg . ':00';
			$data1 = date('Y-m-d H:i:s', strtotime("+0 days", strtotime($hora1)));
			$data2 = date('Y-m-d H:i:s', strtotime("+0 days", strtotime($hora2)));
			$resultado = strtotime($data2) - strtotime($data1);

		} else {
			$hora1 = strtotime('' . $inicial_seg . '');
			$hora2 = strtotime('' . $final_seg . '');
			$resultado = ($hora2 - $hora1);
		}

		$h = ($resultado / (60 * 60)) % 24;

		$dados_turno = DBRead('', 'tb_monitoria_mes', "WHERE data_referencia = '$data_referencia' AND id_monitoria_mes = $formulario AND status = 1");

		$soma = 0;
		if ($h >= 5) {

			$soma = $dados_turno[0]['qtd_audios_monitoria_integral_sn'] + $dados_turno[0]['qtd_audios_monitoria_integral_n1'] + $dados_turno[0]['qtd_audios_monitoria_integral_n2'] + $dados_turno[0]['qtd_audios_monitoria_integral_n3'] + $dados_turno[0]['qtd_audios_monitoria_integral_n4'] + $dados_turno[0]['qtd_audios_monitoria_integral_n5'];

		} else {
			$soma = $dados_turno[0]['qtd_audios_monitoria_meio_turno_sn'] + $dados_turno[0]['qtd_audios_monitoria_meio_turno_n1'] + $dados_turno[0]['qtd_audios_monitoria_meio_turno_n2'] + $dados_turno[0]['qtd_audios_monitoria_meio_turno_n3'] + $dados_turno[0]['qtd_audios_monitoria_meio_turno_n4'] + $dados_turno[0]['qtd_audios_monitoria_meio_turno_n5'];
		}
	}

	if ($formulario) {
		$dados_monitoria = DBRead('', 'tb_monitoria_avaliacao_audio a', "INNER JOIN tb_subarea_problema b ON a.id_subarea_problema = b.id_subarea_problema INNER JOIN tb_area_problema c ON b.id_area_problema = c.id_area_problema WHERE id_usuario_atendente = '$usuario' AND a.id_monitoria_mes = $formulario AND a.considerar = 1 ORDER BY data_monitoria DESC");

		$verifica_plano_acao = DBRead('', 'tb_monitoria_mes_plano_acao_chamado', "WHERE id_monitoria_mes = $formulario and data_referencia = '$data_referencia' ");

	} else {
		$dados_monitoria = '';
		$verifica_plano_acao = '';
	}

	if ($classificacao == 1) {
		$legenda_classificacao = ' (via Telefone - Em Treinamento)';

	} else if ($classificacao == 2) {
		$legenda_classificacao = ' (via Telefone - Período de Experiência)';

	} else if ($classificacao == 3) {
		$legenda_classificacao = ' (via Telefone - Efetivado)';
	}

	$nome_usuario_legend = '<span style="font-size: 14px;"><strong>Atendente:</strong> ' . $nome_usuario . '</span>';
	$legenda_mes_referencia = '<span style="font-size: 14px;"><strong>Mês referência:</strong> ' . $legenda_mes_referencia . '</span>';

	echo "<div class=\"col-md-12\" style=\"padding: 0\">";
	echo "<legend style=\"text-align:center;\"><strong>Relatório de Monitoria - Individual $legenda_classificacao</strong><br><span style=\"font-size: 14px;\"><strong>Gerado em:</strong> $data_hora</span></legend>";
	echo "<legend style=\"text-align:center;\">$legenda_mes_referencia</legend>";
	echo "<legend style=\"text-align:center;\">$nome_usuario_legend</legend>";

	if ($dados_monitoria) {

		$array_pontos = array();
		$soma_total_percentual = array();
		$tempo_total_avaliacao = array();

		$cont_id = 0;

		foreach ($dados_monitoria as $conteudo) {
			
			$cont_id++;

			$id_monitoria_avaliacao_audio = $conteudo['id_monitoria_avaliacao_audio'];
			$id_usuario_analista = $conteudo['id_usuario_analista'];

			$analista = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_usuario = '$id_usuario_analista' ");

			$id_ligacao = $conteudo['id_ligacao'];
			$observacao = $conteudo['obs_avaliacao'];
			$id_erro = $conteudo['id_erro'];

			$dados_ligacao = DBRead('snep', 'queue_log a', "INNER JOIN queue_log b ON (b.callid = a.callid AND b.event = 'CONNECT') INNER JOIN queue_log c ON (c.callid = a.callid AND (c.event = 'COMPLETEAGENT' OR c.event = 'COMPLETECALLER' OR c.event = 'BLINDTRANSFER' OR c.event = 'ATTENDEDTRANSFER')) LEFT JOIN pesquisa d ON  a.callid = d.UNIQUEID WHERE a.event = 'ENTERQUEUE' AND (c.data2 >= 30 OR c.data4 >= 30) AND a.callid = '$id_ligacao' GROUP BY b.id ORDER BY b.id LIMIT 1", "a.queuename AS 'enterqueue_queuename', a.callid AS 'enterqueue_callid', a.data2 AS 'enterqueue_data2', b.agent AS 'connect_agent', b.time AS 'connect_time', c.event AS 'finalizacao_event', c.data1 AS 'finalizacao_data1', c.data2 AS 'finalizacao_data2', c.data3 AS 'finalizacao_data3', c.data4 AS 'finalizacao_data4', d.nota AS 'nota', (SELECT SUM(data3) FROM queue_log e WHERE e.callid = a.callid AND e.event = 'EXITWITHTIMEOUT') AS 'tempo_espera_timeout'");

			if(preg_match("/'".$dados_ligacao[0]['enterqueue_queuename']."'/i", $fila) || !$fila){

				$id_asterisk = explode("-", $dados_ligacao[0]['enterqueue_data2']);

				$id_contrato_plano_pessoa = DBRead('', 'tb_parametros', "WHERE id_asterisk = '" . $id_asterisk[0] . "' ", 'id_contrato_plano_pessoa');

				$id_ligacao = $dados_ligacao[0]['enterqueue_callid'];

				$enterqueue_data2 = explode('-', $dados_ligacao[0]['enterqueue_data2']);

				$id_empresa_entrada = $enterqueue_data2[0];
				$dados_empresa = DBRead('snep', 'empresas', "WHERE id='$id_empresa_entrada'");

				if ($dados_empresa) {
					$nome_empresa = $dados_empresa[0]['nome'];
				} else {
					$nome_empresa = 'Não identificada';
				}

				if (is_numeric(end($enterqueue_data2)) && strlen(end($enterqueue_data2)) >= 10) {
					$bina = ltrim(end($enterqueue_data2), '0');
				} elseif (end($enterqueue_data2) == 'anonymous') {
					$bina = 'Nº anônimo';
				} else {
					$bina = 'Não identificado';
				}

				$info_chamada = array(
					'data_hora_atendida' => '',
					'nome_empresa' => $nome_empresa,
					'bina' => $bina,
					'tempo_atendimento' => '',
					'nota' => '',
					'gravacao' => '',
					'finalizacao' => '',
					'tempo_espera' => ''
				);

				$info_chamada['data_hora_atendida'] = date('d/m/Y H:i:s', strtotime($dados_ligacao[0]['connect_time']));

				$operador_chamada = explode('/', $dados_ligacao[0]['connect_agent']);
				$tipo_operador_chamada = $operador_chamada[0];
				$cod_operador_chamada = $operador_chamada[1];

				$dados_cdr = DBRead('snep', 'cdr', "WHERE uniqueid ='" . $dados_ligacao[0]['enterqueue_callid'] . "' LIMIT 1", "userfield");

				$arquivo_gravacao = explode(';', $dados_cdr[0]['userfield']);

				if (count($arquivo_gravacao) > 1) {

					$info_chamada['gravacao'] = 'https://pabx.bellunotec.com.br/snep/arquivos/' . date('Y-m-d', strtotime($dados_ligacao[0]['connect_time'])) . '/' . $arquivo_gravacao[1];
				} else {
					$info_chamada['gravacao'] = 'https://pabx.bellunotec.com.br/snep/arquivos/' . date('Y-m-d', strtotime($dados_ligacao[0]['connect_time'])) . '/' . $arquivo_gravacao[0];
				}

				$info_chamada['nota'] = $dados_ligacao[0]['nota'];

				if ($dados_ligacao[0]['finalizacao_event'] == 'COMPLETEAGENT' || $dados_ligacao[0]['finalizacao_event'] == 'COMPLETECALLER') {

					$info_chamada['tempo_espera'] = $dados_ligacao[0]['finalizacao_data1'];
					$info_chamada['tempo_atendimento'] = $dados_ligacao[0]['finalizacao_data2'];
					$tempo_total_avaliacao['tempo'] += $dados_ligacao[0]['finalizacao_data2'];

					if ($dados_ligacao[0]['finalizacao_event'] == 'COMPLETEAGENT') {
						$info_chamada['finalizacao'] = 'Atendente';
					} else {
						$info_chamada['finalizacao'] = 'Cliente';
					}
				} elseif (($dados_ligacao[0]['finalizacao_event'] == 'BLINDTRANSFER' || $dados_ligacao[0]['finalizacao_event'] == 'ATTENDEDTRANSFER') && $dados_ligacao[0]['finalizacao_data1'] != 'LINK') {
					$info_chamada['tempo_atendimento'] = $dados_ligacao[0]['finalizacao_data4'];
					$tempo_total_avaliacao['tempo'] += $dados_ligacao[0]['finalizacao_data4'];
					$info_chamada['tempo_espera'] = $dados_ligacao[0]['finalizacao_data3'];
					$info_chamada['finalizacao'] = 'Transferida';
				}
				if($dados_ligacao[0]['tempo_espera_timeout'] && $dados_ligacao[0]['tempo_espera_timeout'] > 0){
					$info_chamada['tempo_espera'] += $dados_ligacao[0]['tempo_espera_timeout'];
				}
			}

    ?>
			<!-- panel -->
			<div class="panel" style="border-color: #A9A9A9 !important;">
				<div class="panel-heading" style="background-color: #D3D3D3 !important;">

					<div class="row">
						<div class="col-md-3">
							<h3 class="panel-title">Data da monitoria: <strong><?= converteDataHora($conteudo['data_monitoria']) ?></strong>
							</h3>
						</div>
						<div class="col-md-3 text-center">

							<?php if ($perfil_sistema != 3) { ?>

								<h3 class="panel-title">Tempo de avaliação: <strong><?= converteSegundosHoras($conteudo['duracao_avaliacao']) ?></strong>
								</h3>

							<?php } ?>
						</div>
						<div class="col-md-3 text-center">
							<?php if ($perfil_sistema != 3) { ?>
								<h3 class="panel-title text-center">
									<span style="font-size: 15px;">Feita por: <strong><?= $analista[0]['nome'] ?></span></strong>
								</h3>
							<?php } ?>
						</div>
						<div class="col-md-3">
							
							<button data-toggle="collapse" data-target="#accordionPlano_<?=$cont_id?>" class="btn btn-xs btn-info pull-right" type="button" title="Visualizar filtros"><i id="i_collapse_<?=$cont_id?>" class="fa fa-plus"></i></button>
                                                                                
						</div>
					</div>
				</div>
				<div class="panel-body">

					<!-- table audio responsive -->
					<div class="table-responsive">
						<table class="table table-hover">
							<thead>
								<tr style="background-color: #f5f5f5">
									<th>Data ligação</th>
									<th>Empresa</th>
									<th>Número</th>
									<th>Finalização</th>
									<th>T. Atendimento</th>
									<th>T. Espera</th>
									<th>Nota</th>
									<?php if ($perfil_sistema != 3) { ?>
										<th>Gravação</th>
									<?php } ?>
									<th>
										<?php if ( (!$verifica_plano_acao) && ($perfil_sistema == 2 || $perfil_sistema == 18 || $perfil_sistema == 14 || $perfil_sistema == 30)) { ?>
											Desconsiderar
										<?php } ?>
									</th>
								</tr>
							</thead>

							<tbody>
								<tr>
									<td><?= $info_chamada['data_hora_atendida'] ?></td>
									<td><?= $info_chamada['nome_empresa'] ?></td>
									<td><?= $info_chamada['bina'] ?></td>
									<td><?= $info_chamada['finalizacao'] ?></td>
									<td><?= gmdate("H:i:s", $info_chamada['tempo_atendimento']) ?></td>
									<td><?= gmdate("H:i:s", $info_chamada['tempo_espera']) ?></td>
									<td><?= $info_chamada['nota'] ?></td>
									<?php if ($perfil_sistema != 3) { ?>
										<td>
											<audio controls preload="none" class="audio_gravacao" style="width: 100%; min-width: 300px;">
												<source src="<?= $info_chamada['gravacao'] . '.mp3' ?>" type="audio/mp3">
												<source src="<?= $info_chamada['gravacao'] . '.wav' ?>" type="audio/wav">Seu navegador não aceita o player nativo.
											</audio>
										</td>
									<?php } ?>
									<td><?php if ( (!$verifica_plano_acao) && ($perfil_sistema == 2 || $perfil_sistema == 18 || $perfil_sistema == 14 || $perfil_sistema == 30)) { ?>

										<button class="btn btn-warning btn-xs desconsiderar" id="<?= $id_monitoria_avaliacao_audio ?>">
											<i class="fa fa-close"></i> Desconsiderar avaliação
										</button>

									<?php } ?></td>
								</tr>
							</tbody>
						</table>
					</div>
					<!--end table audio responsive -->

					<!-- table outras informacoes -->
					<table class="table table-hover">
						<thead>
							<tr style="background-color: #f5f5f5">
								<th class="col-md-3">Nome do contato</th>
								<th class="col-md-3">Área do problema</th>
								<th class="col-md-3">Subárea do problema</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td><?= $conteudo['nome_contato'] ?></td>
								<td><?= $conteudo['nome'] ?></td>
								<td><?= $conteudo['descricao'] ?></td>
							</tr>
						</tbody>
					</table>
					<!-- end table outras informacoes -->

					<br>

					<?php
					if ($id_erro) {

						$dados_erro = DBRead('', 'tb_erro_atendimento a', "INNER JOIN tb_contrato_plano_pessoa b ON a.id_contrato_plano_pessoa = b.id_contrato_plano_pessoa INNER JOIN tb_pessoa c ON b.id_pessoa = c.id_pessoa INNER JOIN tb_tipo_erro d ON a.id_tipo_erro = d.id_tipo_erro INNER JOIN tb_usuario e ON a.id_usuario_cadastrou = e.id_usuario INNER JOIN tb_pessoa f ON e.id_pessoa = f.id_pessoa WHERE a.id_erro_atendimento = '$id_erro' ", 'a.id_erro_atendimento, a.protocolo, a.assinante, a.data_erro, a.hora_erro, a.descricao_cliente, c.nome AS nome_empresa, d.nome AS descricao_erro, f.nome AS usuario_cadastrou');

						//var_dump($dados_erro);

						if ($dados_erro[0]['protocolo'] == '') {
							$protocolo = 'N/D';
						} else {
							$protocolo = $dados_erro[0]['protocolo'];
						}
					?>
						<!-- panel erro -->
						<div class="panel panel-danger" style="border-color: #FFB6C1 !important;">
							<div class="panel-heading">
								<div class="row">
									<div class="col-md-4" style="padding-left: 7px;">
										<h6 class="panel-title" style="font-size: 14px !important;">
											Reclamação/Erro
										</h6>
									</div>
									<div class="col-md-4">
										<h6 class="panel-title text-center" style="font-size: 14px !important;">
											Protocolo: <?= $dados_erro[0]['protocolo'] ?>
										</h6>
									</div>
									<div class="col-md-4" style="padding-right: 7px;">

									</div>
								</div>
							</div>
							<div class="panel-body">
								<div class="table-responsive">
									<table class="table table-hover" style="margin-bottom: 8px;">
										<thead>
											<tr>
												<th>Tipo erro reclamação</th>
												<th>Criado por</th>
												<th>Empresa</th>
												<th>Assinante</th>
												<th>Data do erro/reclamação</th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td><?= $dados_erro[0]['descricao_erro'] ?></td>
												<td><?= $dados_erro[0]['usuario_cadastrou'] ?></td>
												<td><?= $dados_erro[0]['nome_empresa'] ?></td>
												<td><?= $dados_erro[0]['assinante'] ?></td>
												<td><?= converteData($dados_erro[0]['data_erro']) ?> <?= $dados_erro[0]['hora_erro'] ?></td>
											</tr>
										</tbody>
									</table>
								</div>

								<hr>
								<div class='conteudo-editor' style="margin-left: 7px;">
									<strong>Descrição</strong><br><br>
									<?= $dados_erro[0]['descricao_cliente'] ?>
								</div>
							</div>
						</div>
						<!-- end panel erro -->

					<?php
					}
					?>

					<br>

					<div id="accordionPlano_<?=$cont_id?>" class="panel-collapse collapse accordionPlano">
						<!-- table responsive -->
						<div class="table-responsive">
							<table class="table table-hover table-quesitos" style="border: 1px solid #A9A9A9;">
								<thead>
									<tr style="background-color: #f5f5f5">
										<th class="col-md-6">Quesito</th>
										<th class="col-md-2 text-center">Resultado</th>
										<th class="col-md-2 text-center">Valor do quesito</th>
										<th class="col-md-2 text-center">Valor obtido</th>
									</tr>
								</thead>
								<tbody>

									<?php
									$dados_monitoria_mes = DBRead('', 'tb_monitoria_avaliacao_audio a', "INNER JOIN tb_monitoria_avaliacao_audio_mes b ON a.id_monitoria_avaliacao_audio = b.id_monitoria_avaliacao_audio INNER JOIN tb_monitoria_mes_quesito c ON b.id_monitoria_mes_quesito = c.id_monitoria_mes_quesito INNER JOIN tb_monitoria_quesito d ON c.id_monitoria_quesito = d.id_monitoria_quesito
									INNER JOIN tb_usuario e ON a.id_usuario_analista = e.id_usuario INNER JOIN tb_pessoa f ON e.id_pessoa = f.id_pessoa INNER JOIN tb_monitoria_mes g ON g.id_monitoria_mes = c.id_monitoria_mes WHERE a.id_monitoria_mes = $formulario AND a.id_usuario_atendente = '$usuario' AND a.id_ligacao = '$id_ligacao' AND g.status = 1 AND a.considerar = 1", 'a.nome_contato, a.id_erro, a.obs_avaliacao, a.total_pontos, c.pontos_valor, c.porcentagem_plano_acao, b.pontos, d.descricao, d.plano_acao,f.nome as nome_analista, g.soma_total_pontos_quesitos, d.id_monitoria_quesito');

									if ($dados_monitoria_mes) {

										$total_pontos_geral = 0;
										$total_pontos_feitos = 0;

										foreach ($dados_monitoria_mes as $conteudo_monitoria) {

											$array_pontos[$conteudo_monitoria['id_monitoria_quesito']]['pontos'] += $conteudo_monitoria['pontos'];

											$array_pontos[$conteudo_monitoria['id_monitoria_quesito']]['qtd'] += 1;

											$array_pontos[$conteudo_monitoria['id_monitoria_quesito']]['pontos_valor'] = $conteudo_monitoria['pontos_valor'];

											$array_pontos[$conteudo_monitoria['id_monitoria_quesito']]['descricao'] = $conteudo_monitoria['descricao'];

											$array_pontos[$conteudo_monitoria['id_monitoria_quesito']]['plano_acao'] = $conteudo_monitoria['plano_acao'];

											$array_pontos[$conteudo_monitoria['id_monitoria_quesito']]['porcentagem_plano_acao'] = $conteudo_monitoria['porcentagem_plano_acao'];

											$observacao = $conteudo_monitoria['obs_avaliacao'];

											$analista = $conteudo_monitoria['nome_analista'];
											$contato = $conteudo_monitoria['nome_contato'];
											$total_pontos = $conteudo_monitoria['pontos_valor'];
											$pontos = $conteudo_monitoria['pontos'];

											$total_pontos_geral += $conteudo_monitoria['pontos_valor'];
											$total_pontos_feitos += $conteudo_monitoria['pontos'];

											$resultado = ($pontos * 100) / $total_pontos;
											$resultado = number_format($resultado, 2, '.', ' ');

											$class_bar = '';
											if ($resultado == 100.00) {
												$span = '<span class="label label-success" style="font-size: 11px;display: inline-block; min-width: 160px;">Atendeu ao quesito <i class="fa fa-thumbs-up pull-right" style="color: white;"></i></span>';
											} else if ($resultado != 100.00) {
												$span = '<span class="label label-danger" style="font-size: 11px;display: inline-block; min-width: 160px;">Não atendeu ao quesito <i class="fa fa-thumbs-down pull-right" style="color: white;"></i></span>';
											}

									?>
											<tr>
												<td><?= $conteudo_monitoria['descricao'] ?></td>
												<td class="text-center">
													<?= $span ?>
												</td>
												<td class="text-center"><?= $total_pontos ?></td>
												<td class="text-center"><?= $pontos ?></td>
											</tr>
									<?php
										}

										$resultado_avalicao = ($total_pontos_feitos * 100) / $total_pontos_geral;

										$soma_total_percentual['resultado'] += $resultado_avalicao;
										$soma_total_percentual['qtd'] += 1;
									}

									?>
								</tbody>
								<tfoot>
									<tr class="success">
										<td><strong>Resultados</strong></td>
										<td class="text-center"><strong><?= number_format($resultado_avalicao, 2, '.', ' '); ?>%</strong></td>
										<td class="text-center"><strong><?= $total_pontos_geral ?></strong></td>
										<td class="text-center"><strong><?= $total_pontos_feitos ?></strong></td>
									</tr>
								</tfoot>
							</table>

						</div>
						<!--end table responsive -->

						<br>

						<!-- table -->

						<?php if ($observacao != '') { ?>
							<table class="table table-hover">
								<thead>
									<tr style="background-color: #f5f5f5">
										<th class="col-md-12">Observação da avaliação</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td><?= $observacao ?></td>
									</tr>
								</tbody>
							</table>
						<?php } ?>
						<!-- end table -->
					</div>
				</div>
				<!--end panel body -->
			</div>
			<!--end panel -->
			<br>
		<?php

		} //end foreach

		?>
		<br>

		<!-- row resultado geral -->
		<div class="row">
			<div class="col-md-12">
				<?php

				if ($soma_total_percentual['qtd'] > 0) {
					$geral = $soma_total_percentual['resultado'] / $soma_total_percentual['qtd'];
				} else {
					$geral = 0;
				}

				?>

				<div class="jumbotron" style="border: 1px solid #A9A9A9;">
					<span class="text-center">
						<h3 style="margin-top: 5px;">
							<strong>
								Resultado da monitoria: <?= number_format($geral, 2, '.', ' ') ?>%
							</strong>
						</h3>

						<hr class="hr-resultado">

						<br>

						<?php
						$tma = $tempo_total_avaliacao['tempo'] / $soma_total_percentual['qtd'];
						?>

						<h4 style="margin-top: 5px;">
							Quantidade de avaliações: <?= $soma_total_percentual['qtd'] ?> de <?= $soma ?>
						</h4>

						<hr class="hr-resultado">

						<br>
						<h4 style="margin-top: 0px;">
							<strong>Resultado da monitoria por quesito
								<i class="fa fa-tripadvisor"></i>
							</strong>
						</h4>
					</span>
					<br>

					<div class="table-responsive">
						<table class="table table-striped">
							<thead>
								<tr>
									<th class="col-md-5">Quesito</th>
									<th class="col-md-2 text-center">
										<span style="margin-left: 8px;">Resultado</span>
									</th>
									<th class="col-md-5">Plano de ação</th>
								</tr>
							</thead>
							<tbody>
								<?php
								foreach ($array_pontos as $key => $conteudo_resultados) {

									$quesito = DBRead('', 'tb_monitoria_mes a', "INNER JOIN tb_monitoria_mes_quesito b ON a.id_monitoria_mes = b.id_monitoria_mes INNER JOIN tb_monitoria_quesito c ON b.id_monitoria_quesito = c.id_monitoria_quesito WHERE c.id_monitoria_quesito = '$key' AND a.id_monitoria_mes = $formulario AND a.status = 1", "b.porcentagem_plano_acao, c.descricao, c.plano_acao");

									$media = $conteudo_resultados['pontos'] / $conteudo_resultados['qtd'];
									$resultado_geral = ($media * 100) / $conteudo_resultados['pontos_valor'];

									$porcentagem = $conteudo_resultados['porcentagem_plano_acao'];

									if ($porcentagem >= $resultado_geral) {
										$txt_plano_acao = $conteudo_resultados['plano_acao'];
									} else {
										$txt_plano_acao = '';
									}

									$class_bar = '';
									if ($resultado_geral >= $quesito[0]['porcentagem_plano_acao']) {
										$class_bar = 'progress-bar-success';
										$value_bar = $resultado_geral;
									} else if ($resultado_geral < $quesito[0]['porcentagem_plano_acao'] && $resultado_geral > 0) {
										$class_bar = 'progress-bar-warning ';
										$value_bar = $resultado_geral;
									} else if ($resultado_geral == 0) {
										$class_bar = 'progress-bar-danger ';
										$value_bar = 7;
									}

								?>
									<tr>
										<td><?= $conteudo_resultados['descricao'] ?></td>
										<td>
											<div class="progress" style="margin-left: 8px; margin-right: 9px;">
												<div class="progress-bar progress-bar-striped <?= $class_bar ?>" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: <?= $value_bar ?>%;">
													<span style="color: black;">
														<strong>
															<?= number_format($resultado_geral, 2, '.', ''); ?>%
														</strong>
													</span>
												</div>
											</div>
										</td>
										<td><?= $txt_plano_acao ?></td>
									</tr>
								<?php
								}
								?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
		<!-- end row resultado geral -->

		<br>

		<script>
			$('.accordionPlano').on('shown.bs.collapse', function(){
				var i_collapse_ = $(this).attr('id').split("_");
				i_collapse_ = '#i_collapse_'+i_collapse_[1];
				$(i_collapse_).removeClass("fa fa-plus").addClass("fa fa-minus");
			});
			$('.accordionPlano').on('hidden.bs.collapse', function(){
				var i_collapse_ = $(this).attr('id').split("_");
				i_collapse_ = '#i_collapse_'+i_collapse_[1];
				$(i_collapse_).removeClass("fa fa-minus").addClass("fa fa-plus");
			});
		</script>


	<?php

	} else {
		echo '<div style="text-align: center"><h4>Não foram encontrados resultados!</h4></div>';
	}

	echo "</div>";
}

function relatorio_monitoria_individual_texto($mes, $ano, $usuario, $perfil_sistema, $classificacao, $formulario)
{
	$data_hora = converteDataHora(getDataHora());

	$data_referencia = $ano . '-' . $mes . '-01';
	$legenda_mes_referencia = $mes . '/' . $ano;

	$dados_usuario = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_usuario = '$usuario'", "b.nome");
	$nome_usuario = $dados_usuario[0]['nome'];

	$turno = DBRead('', 'tb_horarios_escala', "WHERE id_usuario = '$usuario' AND data_inicial = '$data_referencia' ", 'inicial_seg, final_seg');

	if ($turno) {
		$inicial_seg = $turno[0]['inicial_seg'];
		$final_seg = $turno[0]['final_seg'];

		if ($inicial_seg > $final_seg) {
			$hora1 = '2000-10-10 ' . $inicial_seg . ':00';
			$hora2 = '2000-10-11 ' . $final_seg . ':00';
			$data1 = date('Y-m-d H:i:s', strtotime("+0 days", strtotime($hora1)));
			$data2 = date('Y-m-d H:i:s', strtotime("+0 days", strtotime($hora2)));
			$resultado = strtotime($data2) - strtotime($data1);

		} else {
			$hora1 = strtotime('' . $inicial_seg . '');
			$hora2 = strtotime('' . $final_seg . '');
			$resultado = ($hora2 - $hora1);
		}

		$h = ($resultado / (60 * 60)) % 24;

		$dados_turno = DBRead('', 'tb_monitoria_mes', "WHERE id_monitoria_mes = $formulario AND data_referencia = '$data_referencia' AND status = 1");

		$soma = 0;
		if ($h >= 5) {
			$soma = $dados_turno[0]['qtd_texto_monitoria_integral'];

		} else {
			$soma = $dados_turno[0]['qtd_texto_monitoria_meio_turno'];
		}
	}

	if ($classificacao == 1) {
		$legenda_classificacao = ' (via Texto - Em Treinamento)';

	} else if ($classificacao == 2) {
		$legenda_classificacao = ' (via Texto - Período de Experiência)';

	} else if ($classificacao == 3) {
		$legenda_classificacao = ' (via Texto - Efetivado)';
	}

	if ($formulario) {
		$dados_monitoria = DBRead('', 'tb_monitoria_avaliacao_texto', "WHERE id_usuario_atendente = '$usuario' AND id_monitoria_mes = $formulario AND considerar = 1 ORDER BY data_monitoria DESC");

	} else {
		$dados_monitoria = '';
	}

	$nome_usuario_legenda = '<span style="font-size: 14px;"><strong>Atendente:</strong> ' . $nome_usuario . '</span>';
	$legenda_mes_referencia = '<span style="font-size: 14px;"><strong>Mês referência:</strong> '.$legenda_mes_referencia.'</span>';

	echo "<div class=\"col-md-12\" style=\"padding: 0\">";
	echo "<legend style=\"text-align:center;\"><strong>Relatório de Monitoria - Individual $legenda_classificacao</strong><br><span style=\"font-size: 14px;\"><strong>Gerado em:</strong> $data_hora</span></legend>";
	echo "<legend style=\"text-align:center;\">$legenda_mes_referencia</legend>";
	echo "<legend style=\"text-align:center;\">$nome_usuario_legenda</legend>";

	if ($dados_monitoria) {

		$array_pontos = array();
		$soma_total_percentual = array();
		$tempo_total_avaliacao = array();

		foreach ($dados_monitoria as $conteudo) {

			$id_monitoria_avaliacao_texto = $conteudo['id_monitoria_avaliacao_texto'];
			$id_usuario_analista = $conteudo['id_usuario_analista'];

			$analista = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_usuario = '$id_usuario_analista' ");

			$id_atendimento = $conteudo['id_atendimento'];
			$observacao = $conteudo['obs_avaliacao'];
			$id_erro = $conteudo['id_erro'];

			$dados_atendimento = DBRead('', 'tb_atendimento a', "INNER JOIN tb_situacao_atendimento b ON a.id_atendimento = b.id_atendimento INNER JOIN tb_situacao c ON b.id_situacao = c.id_situacao LEFT JOIN tb_subarea_problema_atendimento d ON a.id_atendimento = d.id_atendimento LEFT JOIN tb_subarea_problema e ON d.id_subarea_problema = e.id_subarea_problema LEFT JOIN tb_area_problema f ON e.id_area_problema = f.id_area_problema WHERE a.id_atendimento = $id_atendimento", "a.*, c.nome, e.descricao as descricao_subarea_problema, f.nome as descricao_area_problema");

			$conteudo_empresa = DBRead('', 'tb_contrato_plano_pessoa a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa INNER JOIN tb_plano c ON a.id_plano = c.id_plano WHERE a.id_contrato_plano_pessoa = '" . $dados_atendimento[0]['id_contrato_plano_pessoa'] . "'", "b.nome AS nome_empresa, a.*, c.*");

			if ($conteudo_empresa[0]['nome_contrato']) {
				$nome_contrato = " (" . $conteudo_empresa[0]['nome_contrato'] . ") ";
			} else {
				$nome_contrato = '';
			}
                
			$contrato = $conteudo_empresa[0]['nome_empresa'] . " " . $nome_contrato;

			$nome_empresa = $contrato;

			if ($dados_atendimento[0]['fone2']) {
				$legenda_telefone = $dados_atendimento[0]['fone1'].' | '.$dados_atendimento[0]['fone1'];
			} else {
				$legenda_telefone = $dados_atendimento[0]['fone1'];
			}

			$situacao_protocolo = DBRead('', 'tb_parametros', "WHERE id_contrato_plano_pessoa = '" . $dados_atendimento[0]['id_contrato_plano_pessoa'] . "'", "exibir_protocolo, solicitacao_cpf");

			if ($situacao_protocolo[0]['solicitacao_cpf'] == 1) {
				if ($dados_atendimento[0]['cpf_cnpj']) {
					$cpf_cnpj = $dados_atendimento[0]['cpf_cnpj'];
				}
			} else {
				$cpf_cnpj = ' - - - - - - - - - - -';
			}
			
			if ($dados_atendimento[0]['descricao_dado_adicional'] && $dados_atendimento[0]['dado_adicional']) {
				$dado_adicional = $dados_atendimento[0]['descricao_dado_adicional'].' - '. $dados_atendimento[0]['dado_adicional'];

			} else {
				$dado_adicional = ' - - - - - - - - - - -';
			}

			if ($situacao_protocolo[0]['exibir_protocolo'] == 1) {
				$protocolo =  $dados_atendimento[0]['protocolo'];
			} else {
				$protocolo = ' - - - - - - - - - - -';
			}

			if ($dados_atendimento[0]['descricao_subarea_problema']) {
				$sub_area_problema = $dados_atendimento[0]['descricao_subarea_problema'];

			} else {
				$sub_area_problema = ' - - - - - - - - - - -';
			}

			if ($dados_atendimento[0]['descricao_area_problema']) {
				$area_problema = $dados_atendimento[0]['descricao_area_problema'];

			} else {
				$area_problema = ' - - - - - - - - - - -';
			}

			$os = $dados_atendimento[0]['os']. PHP_EOL .$dados_atendimento[0]['nome'];

    ?>

			<!-- panel -->
			<div class="panel" style="border-color: #A9A9A9 !important;">
				<div class="panel-heading" style="background-color: #D3D3D3 !important;">

					<div class="row">
						<div class="col-md-3">
							<h3 class="panel-title">Data da monitoria: <strong><?= converteDataHora($conteudo['data_monitoria']) ?></strong>
							</h3>
						</div>
						<div class="col-md-3 text-center">

							<?php if ($perfil_sistema != 3) { ?>

								<h3 class="panel-title">Tempo de avaliação: <strong><?= converteSegundosHoras($conteudo['duracao_avaliacao']) ?></strong>
								</h3>

							<?php } ?>
						</div>
						<div class="col-md-3 text-center">
							<?php if ($perfil_sistema != 3) { ?>
								<h3 class="panel-title text-center">
									<span style="font-size: 15px;">Feita por: <strong><?= $analista[0]['nome'] ?></span></strong>
								</h3>
							<?php } ?>
						</div>
						<div class="col-md-3">
							<?php if ($perfil_sistema == 2 || $perfil_sistema == 18 || $perfil_sistema == 14) { ?>

								<button class="btn btn-warning btn-xs pull-right desconsiderar_texto" id="<?= $id_monitoria_avaliacao_texto ?>">
									<i class="fa fa-close"></i> Desconsiderar avaliação
								</button>

							<?php } ?>
						</div>
					</div>

				</div>
				<div class="panel-body">

					<!-- table texto responsive -->
					<div class="table-responsive">
						<table class="table table-hover">
							<thead> 
								<tr>
								<th>Data</th>
								<th>Empresa</th>
								<th>Protocolo</th>
								<th>Assinante</th>
								<th>CPF/CNPJ</th>
								<th>Dado adicional</th>
								<th>Contato</th>
								<th>Telefone (1/2)</th>
								<th>Área do problema</th>
								<th>Subarea do problema</th>
								</tr>
							</thead> 
							<tbody>
								<tr>
									<td><?=  converteDataHora($dados_atendimento[0]['data_inicio']) ?></td>
									<td><?= $nome_empresa ?></td>
									<td><?= $protocolo ?></td>
									<td><?= $dados_atendimento[0]['assinante'] ?></td>
									<td><?= $cpf_cnpj ?></td>
									<td><?= $dado_adicional ?></td>
									<td><?= $dados_atendimento[0]['contato'] ?></td>
									<td><?= $legenda_telefone ?></td>
									<td><?= $sub_area_problema ?></td>
									<td><?= $area_problema ?></td>
									<td></td>
								</tr>
							</tbody>
						</table>
					</div>
					<!--end table texto responsive -->

					<br>

					<?php
					if ($id_erro) {

						$dados_erro = DBRead('', 'tb_erro_atendimento a', "INNER JOIN tb_contrato_plano_pessoa b ON a.id_contrato_plano_pessoa = b.id_contrato_plano_pessoa INNER JOIN tb_pessoa c ON b.id_pessoa = c.id_pessoa INNER JOIN tb_tipo_erro d ON a.id_tipo_erro = d.id_tipo_erro INNER JOIN tb_usuario e ON a.id_usuario_cadastrou = e.id_usuario INNER JOIN tb_pessoa f ON e.id_pessoa = f.id_pessoa WHERE a.id_erro_atendimento = '$id_erro' ", 'a.id_erro_atendimento, a.protocolo, a.assinante, a.data_erro, a.hora_erro, a.descricao_cliente, c.nome AS nome_empresa, d.nome AS descricao_erro, f.nome AS usuario_cadastrou');

						if ($dados_erro[0]['protocolo'] == '') {
							$protocolo = 'N/D';
						} else {
							$protocolo = $dados_erro[0]['protocolo'];
						}
					?>
						<!-- panel erro -->
						<div class="panel panel-danger" style="border-color: #FFB6C1 !important;">
							<div class="panel-heading">
								<div class="row">
									<div class="col-md-4" style="padding-left: 7px;">
										<h6 class="panel-title" style="font-size: 14px !important;">
											Reclamação/Erro
										</h6>
									</div>
									<div class="col-md-4">
										<h6 class="panel-title text-center" style="font-size: 14px !important;">
											Protocolo: <?= $dados_erro[0]['protocolo'] ?>
										</h6>
									</div>
									<div class="col-md-4" style="padding-right: 7px;">

									</div>
								</div>
							</div>
							<div class="panel-body">
								<div class="table-responsive">
									<table class="table table-hover" style="margin-bottom: 8px;">
										<thead>
											<tr>
												<th>Tipo erro reclamação</th>
												<th>Criado por</th>
												<th>Empresa</th>
												<th>Assinante</th>
												<th>Data do erro/reclamação</th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td><?= $dados_erro[0]['descricao_erro'] ?></td>
												<td><?= $dados_erro[0]['usuario_cadastrou'] ?></td>
												<td><?= $dados_erro[0]['nome_empresa'] ?></td>
												<td><?= $dados_erro[0]['assinante'] ?></td>
												<td><?= converteData($dados_erro[0]['data_erro']) ?> <?= $dados_erro[0]['hora_erro'] ?></td>
											</tr>
										</tbody>
									</table>
								</div>

								<hr>
								<div class='conteudo-editor' style="margin-left: 7px;">
									<strong>Descrição</strong><br><br>
									<?= $dados_erro[0]['descricao_cliente'] ?>
								</div>
							</div>
						</div>
						<!-- end panel erro -->

					<?php
					}
					?>

					<br>

					<!-- table responsive -->
					<div class="table-responsive">
						<table class="table table-hover table-quesitos" style="border: 1px solid #A9A9A9;">
							<thead>
								<tr style="background-color: #f5f5f5">
									<th class="col-md-6">Quesito</th>
									<th class="col-md-2 text-center">Resultado</th>
									<th class="col-md-2 text-center">Valor do quesito</th>
									<th class="col-md-2 text-center">Valor obtido</th>
								</tr>
							</thead>
							<tbody>

								<?php
								$dados_monitoria_mes = DBRead('', 'tb_monitoria_avaliacao_texto a', "INNER JOIN tb_monitoria_avaliacao_texto_mes b ON a.id_monitoria_avaliacao_texto = b.id_monitoria_avaliacao_texto INNER JOIN tb_monitoria_mes_quesito c ON b.id_monitoria_mes_quesito = c.id_monitoria_mes_quesito INNER JOIN tb_monitoria_quesito d ON c.id_monitoria_quesito = d.id_monitoria_quesito
								INNER JOIN tb_usuario e ON a.id_usuario_analista = e.id_usuario INNER JOIN tb_pessoa f ON e.id_pessoa = f.id_pessoa INNER JOIN tb_monitoria_mes g ON g.id_monitoria_mes = c.id_monitoria_mes WHERE a.id_monitoria_mes = $formulario AND a.id_atendimento = $id_atendimento AND a.id_usuario_atendente = '$usuario' AND g.status = 1 AND a.considerar = 1", 'a.id_erro, a.obs_avaliacao, a.total_pontos, c.pontos_valor, c.porcentagem_plano_acao, b.pontos, d.descricao, d.plano_acao,f.nome as nome_analista, g.soma_total_pontos_quesitos, d.id_monitoria_quesito');
								
								if ($dados_monitoria_mes) {

									$total_pontos_geral = 0;
									$total_pontos_feitos = 0;

									foreach ($dados_monitoria_mes as $conteudo_monitoria) {

										$array_pontos[$conteudo_monitoria['id_monitoria_quesito']]['pontos'] += $conteudo_monitoria['pontos'];

										$array_pontos[$conteudo_monitoria['id_monitoria_quesito']]['qtd'] += 1;

										$array_pontos[$conteudo_monitoria['id_monitoria_quesito']]['pontos_valor'] = $conteudo_monitoria['pontos_valor'];

										$array_pontos[$conteudo_monitoria['id_monitoria_quesito']]['descricao'] = $conteudo_monitoria['descricao'];

										$array_pontos[$conteudo_monitoria['id_monitoria_quesito']]['plano_acao'] = $conteudo_monitoria['plano_acao'];

										$array_pontos[$conteudo_monitoria['id_monitoria_quesito']]['porcentagem_plano_acao'] = $conteudo_monitoria['porcentagem_plano_acao'];

										$observacao = $conteudo_monitoria['obs_avaliacao'];

										$analista = $conteudo_monitoria['nome_analista'];
										$contato = $conteudo_monitoria['nome_contato'];
										$total_pontos = $conteudo_monitoria['pontos_valor'];
										$pontos = $conteudo_monitoria['pontos'];

										$total_pontos_geral += $conteudo_monitoria['pontos_valor'];
										$total_pontos_feitos += $conteudo_monitoria['pontos'];

										$resultado = ($pontos * 100) / $total_pontos;
										$resultado = number_format($resultado, 2, '.', ' ');

										$class_bar = '';
										if ($resultado == 100.00) {
											$span = '<span class="label label-success" style="font-size: 11px;display: inline-block; min-width: 160px;">Atendeu ao quesito <i class="fa fa-thumbs-up pull-right" style="color: white;"></i></span>';
										} else if ($resultado != 100.00) {
											$span = '<span class="label label-danger" style="font-size: 11px;display: inline-block; min-width: 160px;">Não atendeu ao quesito <i class="fa fa-thumbs-down pull-right" style="color: white;"></i></span>';
										}

								?>
										<tr>
											<td><?= $conteudo_monitoria['descricao'] ?></td>
											<td class="text-center">
												<?= $span ?>
											</td>
											<td class="text-center"><?= $total_pontos ?></td>
											<td class="text-center"><?= $pontos ?></td>
										</tr>
								<?php
									}

									$resultado_avalicao = ($total_pontos_feitos * 100) / $total_pontos_geral;

									$soma_total_percentual['resultado'] += $resultado_avalicao;
									$soma_total_percentual['qtd'] += 1;
								}

								?>
							</tbody>
							<tfoot>
								<tr class="success">
									<td><strong>Resultados</strong></td>
									<td class="text-center"><strong><?= number_format($resultado_avalicao, 2, '.', ' '); ?>%</strong></td>
									<td class="text-center"><strong><?= $total_pontos_geral ?></strong></td>
									<td class="text-center"><strong><?= $total_pontos_feitos ?></strong></td>
								</tr>
							</tfoot>
						</table>

					</div>
					<!--end table responsive -->

					<br>

					<!-- table -->

					<?php if ($observacao != '') { ?>
						<table class="table table-hover">
							<thead>
								<tr style="background-color: #f5f5f5">
									<th class="col-md-12">Observação da avaliação</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td><?= $observacao ?></td>
								</tr>
							</tbody>
						</table>
					<?php } ?>
					<!-- end table -->

				</div>
				<!--end panel body -->
			</div>
			<!--end panel -->

			<br>
		<?php

		} //end foreach

		?>
		<br>

		<!-- row resultado geral -->
		<div class="row">
			<div class="col-md-12">
				<?php

				if ($soma_total_percentual['qtd'] > 0) {
					$geral = $soma_total_percentual['resultado'] / $soma_total_percentual['qtd'];
				} else {
					$geral = 0;
				}

				?>

				<div class="jumbotron" style="border: 1px solid #A9A9A9;">
					<span class="text-center">
						<h3 style="margin-top: 5px;">
							<strong>
								Resultado da monitoria: <?= number_format($geral, 2, '.', ' ') ?>%
							</strong>
						</h3>

						<hr class="hr-resultado">

						<br>

						<?php
						$tma = $tempo_total_avaliacao['tempo'] / $soma_total_percentual['qtd'];
						?>

						<h4 style="margin-top: 5px;">
							Quantidade de avaliações: <?= $soma_total_percentual['qtd'] ?> de <?= $soma ?>
						</h4>

						<hr class="hr-resultado">

						<br>
						<h4 style="margin-top: 0px;">
							<strong>Resultado da monitoria por quesito
								<i class="fa fa-tripadvisor"></i>
							</strong>
						</h4>
					</span>
					<br>

					<div class="table-responsive">
						<table class="table table-striped">
							<thead>
								<tr>
									<th class="col-md-5">Quesito</th>
									<th class="col-md-2 text-center">
										<span style="margin-left: 8px;">Resultado</span>
									</th>
									<th class="col-md-5">Plano de ação</th>
								</tr>
							</thead>
							<tbody>
								<?php
								foreach ($array_pontos as $key => $conteudo_resultados) {

									$quesito = DBRead('', 'tb_monitoria_mes a', "INNER JOIN tb_monitoria_mes_quesito b ON a.id_monitoria_mes = b.id_monitoria_mes INNER JOIN tb_monitoria_quesito c ON b.id_monitoria_quesito = c.id_monitoria_quesito WHERE c.id_monitoria_quesito = '$key' AND a.id_monitoria_mes = $formulario AND a.status = 1", "b.porcentagem_plano_acao, c.descricao, c.plano_acao");

									$media = $conteudo_resultados['pontos'] / $conteudo_resultados['qtd'];
									$resultado_geral = ($media * 100) / $conteudo_resultados['pontos_valor'];

									$porcentagem = $conteudo_resultados['porcentagem_plano_acao'];

									if ($porcentagem >= $resultado_geral) {
										$txt_plano_acao = $conteudo_resultados['plano_acao'];
									} else {
										$txt_plano_acao = '';
									}

									$class_bar = '';
									if ($resultado_geral >= $quesito[0]['porcentagem_plano_acao']) {
										$class_bar = 'progress-bar-success';
										$value_bar = $resultado_geral;
									} else if ($resultado_geral < $quesito[0]['porcentagem_plano_acao'] && $resultado_geral > 0) {
										$class_bar = 'progress-bar-warning ';
										$value_bar = $resultado_geral;
									} else if ($resultado_geral == 0) {
										$class_bar = 'progress-bar-danger ';
										$value_bar = 7;
									}

								?>
									<tr>
										<td><?= $conteudo_resultados['descricao'] ?></td>
										<td>
											<div class="progress" style="margin-left: 8px; margin-right: 9px;">
												<div class="progress-bar progress-bar-striped <?= $class_bar ?>" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: <?= $value_bar ?>%;">
													<span style="color: black;">
														<strong>
															<?= number_format($resultado_geral, 2, '.', ''); ?>%
														</strong>
													</span>
												</div>
											</div>
										</td>
										<td><?= $txt_plano_acao ?></td>
									</tr>
								<?php
								}
								?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
		<!-- end row resultado geral -->

		<br>

	<?php

	} else {
		echo '<div style="text-align: center"><h4>Não foram encontrados resultados!</h4></div>';
	}

	echo "</div>";
}

function relatorio_monitoria_geral_telefone($mes, $ano, $lider, $tipo_relatorio, $classificacao, $formulario)
{
	$data_hora = converteDataHora(getDataHora());

	$data_referencia = $ano . '-' . $mes . '-01';
	$legenda_mes_referencia = $mes . '/' . $ano;

	$filtro = " data_referencia = '" . $data_referencia . "' ";

	if ($classificacao == 1) {
		$legenda_classificacao = ' (via Telefone - Em Treinamento)';

	} else if ($classificacao == 2) {
		$legenda_classificacao = ' (via Telefone - Período de Experiência)';

	} else if ($classificacao == 3) {
		$legenda_classificacao = ' (via Telefone - Efetivado)';
	}

	$periodo_amostra = "<span style=\"font-size: 14px;\"><strong>Mês referência:</strong></span>";

	if ($tipo_relatorio == 2) {

		$filtro_lider_mes_atual = "AND a.lider_direto = '$lider'";
		$filtro_lider_outro_mes = "AND c.lider_direto = '$lider'";

		$dados_lider = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE id_usuario = '" . $lider . "'", "a.*, b.nome");
		$nome_lider = $dados_lider[0]['nome'];

		if (!$lider) {
			$legenda_lider = '';
		} else {
			$legenda_lider = "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong>Líder Direto:</strong> $nome_lider</span></legend>";
		}

		$nome_relatorio = 'Relatório de Monitoria por Equipe';
	} else {
		$nome_relatorio = 'Relatório de Monitoria Geral';
	}

	echo "<div class=\"col-md-12\" style=\"padding: 0\">";
	echo "<legend style=\"text-align:center;\"><strong>$nome_relatorio $legenda_classificacao</strong><br><span style=\"font-size: 14px;\"><strong>Gerado em:</strong> $data_hora</span></legend>";
	echo "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong>Mês referência:</strong> $legenda_mes_referencia</span></legend>";
	echo $legenda_lider;

	$mes_atual = explode('/', $data_hora);

	if ($formulario) {
		if ($mes_atual[1] == $mes) {
			$dados = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa INNER JOIN tb_monitoria_classificacao_usuario c ON a.id_usuario = c.id_usuario WHERE a.id_perfil_sistema = 3 AND a.status = 1 $filtro_lider_mes_atual AND c.tipo_classificacao = $classificacao ORDER BY b.nome", "a.id_usuario, b.nome");
	
		} else {
			$dados = DBRead('', 'tb_monitoria_avaliacao_audio a', "INNER JOIN tb_monitoria_mes b ON a.id_monitoria_mes = b.id_monitoria_mes INNER JOIN tb_usuario c ON a.id_usuario_atendente = c.id_usuario INNER JOIN tb_pessoa d ON c.id_pessoa = d.id_pessoa WHERE a.id_monitoria_mes = $formulario AND b.tipo_monitoria = 1 AND b.classificacao_atendente = $classificacao $filtro_lider_outro_mes  GROUP BY a.id_usuario_atendente ORDER BY d.nome ASC ", 'a.id_usuario_atendente as id_usuario, d.nome');
		} 

	} else {
		$dados = '';
	}

	$resultado_geral_usuario = array();

	if ($dados) {

		echo "
		<table class=\"table table-striped dataTable\"> 
			<thead> 
				<tr> 
					<th>Atendente</th>
					<th class='text-center'>Resultado</th>
				</tr>
			</thead> 
			<tbody>";

		$soma = 0;
		$qtd_antendentes_avaliados = 0;

		foreach ($dados as $conteudo) {

			$resultado = 0;

			$nome = $conteudo['nome'];
			$id_usuario = $conteudo['id_usuario'];

			$turno = DBRead('', 'tb_horarios_escala', "WHERE id_usuario = '$id_usuario' AND data_inicial = '$data_referencia' ", 'inicial_seg, final_seg');

			if ($turno) {
				$inicial_seg = $turno[0]['inicial_seg'];
				$final_seg = $turno[0]['final_seg'];

				if ($inicial_seg > $final_seg) {
					$resultado = $inicial_seg - $final_seg;
				} else {
					$resultado = $final_seg - $inicial_seg;
				}

				if ($resultado == 6) {
					$turno = 'Integral';
				} else {
					$turno = 'Meio turno';
				}

				$resultado_monitoria = DBRead('', 'tb_monitoria_resultado', "WHERE id_monitoria_mes = $formulario AND $filtro AND id_usuario = '$id_usuario'");

				if (!$resultado_monitoria || $resultado_monitoria[0]['resultado'] == '0.00') {
					$resultado = 0.00 . '%';
				} else {

					$dados_monitoria = DBRead('', 'tb_monitoria_avaliacao_audio a', "INNER JOIN tb_subarea_problema b ON a.id_subarea_problema = b.id_subarea_problema INNER JOIN tb_area_problema c ON b.id_area_problema = c.id_area_problema WHERE id_usuario_atendente = '$id_usuario' AND a.id_monitoria_mes = $formulario ORDER BY data_monitoria");

					$array_pontos = array();

					if ($dados_monitoria) {
						foreach ($dados_monitoria as $conteudo) {

							$id_ligacao = $conteudo['id_ligacao'];

							$dados_monitoria_mes = DBRead('', 'tb_monitoria_avaliacao_audio a', "INNER JOIN tb_monitoria_avaliacao_audio_mes b ON a.id_monitoria_avaliacao_audio = b.id_monitoria_avaliacao_audio INNER JOIN tb_monitoria_mes_quesito c ON b.id_monitoria_mes_quesito = c.id_monitoria_mes_quesito INNER JOIN tb_monitoria_quesito d ON c.id_monitoria_quesito = d.id_monitoria_quesito INNER JOIN tb_usuario e ON a.id_usuario_analista = e.id_usuario INNER JOIN tb_pessoa f ON e.id_pessoa = f.id_pessoa INNER JOIN tb_monitoria_mes g ON g.id_monitoria_mes = c.id_monitoria_mes WHERE a.id_usuario_atendente = '$id_usuario' AND a.id_ligacao = '$id_ligacao' AND a.id_monitoria_mes = $formulario AND g.status = 1 AND a.considerar = 1", 'a.nome_contato, a.id_erro, a.obs_avaliacao, a.total_pontos,c.pontos_valor, c.porcentagem_plano_acao, b.pontos, d.descricao, d.plano_acao,f.nome as nome_analista, g.soma_total_pontos_quesitos, d.id_monitoria_quesito');

							if ($dados_monitoria_mes) {
								foreach ($dados_monitoria_mes as $conteudo_monitoria) {

									$array_pontos[$conteudo_monitoria['id_monitoria_quesito']]['pontos'] += $conteudo_monitoria['pontos'];
									$array_pontos[$conteudo_monitoria['id_monitoria_quesito']]['qtd'] += 1;
									$array_pontos[$conteudo_monitoria['id_monitoria_quesito']]['pontos_valor'] = $conteudo_monitoria['pontos_valor'];
									$array_pontos[$conteudo_monitoria['id_monitoria_quesito']]['descricao'] = $conteudo_monitoria['descricao'];
									$array_pontos[$conteudo_monitoria['id_monitoria_quesito']]['plano_acao'] = $conteudo_monitoria['plano_acao'];
									$array_pontos[$conteudo_monitoria['id_monitoria_quesito']]['porcentagem_plano_acao'] = $conteudo_monitoria['porcentagem_plano_acao'];
									$array_pontos[$conteudo_monitoria['id_monitoria_quesito']]['id_monitoria_quesito'] = $conteudo_monitoria['id_monitoria_quesito'];
								}
							}
						}
					}

					foreach ($array_pontos as $conteudo_resultados) {

						$media = $conteudo_resultados['pontos'] / $conteudo_resultados['qtd'];
						$resultado_geral = ($media * 100) / $conteudo_resultados['pontos_valor'];

						$porcentagem = $conteudo_resultados['porcentagem_plano_acao'];

						$resultado_geral_usuario[$id_usuario][$conteudo_resultados['id_monitoria_quesito']]['media'] += $resultado_geral;
					}

					$resultado = $resultado_monitoria[0]['resultado'] . '%';
					$soma += $resultado_monitoria[0]['resultado'];
					$qtd_antendentes_avaliados++;
				}
			} else {
				$turno = 'Não possui escala definida!';
				$resultado = 0.00 . '%';
			}

			echo '
				<tr>
					<td>' . $nome . '</td>
					<td class="text-center">' . $resultado . '</td>
				</tr>	
			';
		}

		$resultado_total = sprintf("%01.2f", $soma / ($qtd_antendentes_avaliados == 0 ? 1 : $qtd_antendentes_avaliados));

		echo "</tbody>";
		echo "<tfoot>";
		echo "<tr>";
		echo "<th>Total:</th>";
		echo "<th class='text-center'>$resultado_total%</th>";
		echo "</tr>";
		echo "</tfoot>";
		echo "</table><br><br>";

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
			</script>";

		$soma_quesitos = array();
		foreach ($resultado_geral_usuario as $usuario) {
			foreach ($usuario as $quesito => $media) {
				$soma_quesitos[$quesito]['soma'] += $media['media'];
				$soma_quesitos[$quesito]['qtd'] += 1;
			}
		}

	?>

		<!-- row resultado geral -->
		<div class="row">
			<div class="col-md-12">

				<div class="jumbotron" style="border: 1px solid #A9A9A9;">
					<span class="text-center">
						<h3 style="margin-top: 5px;">
							<strong>
								Resultado geral da monitoria: <?= number_format($resultado_total, 2, '.', ' ') ?>%
							</strong>
						</h3>

						<hr class="hr-resultado">

						<br>
						<h4 style="margin-top: 5px;">
							<strong>Resultado geral da monitoria por quesito
								<i class="fa fa-tripadvisor"></i>
							</strong>
						</h4>
					</span>
					<br>

					<div class="table-responsive">
						<table class="table table-striped">
							<thead>
								<!-- <tr style="background-color: #D3D3D3 "> -->
								<tr>
									<th class="col-md-4">Quesito</th>
									<th class="col-md-1 text-center">Porcentagem definida</th>
									<th class="col-md-2 text-center">
										<span style="margin-left: 8px;">Resultado</span>
									</th>
									<th class="col-md-5">Plano de ação</th>
								</tr>
							</thead>
							<tbody>
								<?php
								foreach ($soma_quesitos as $key => $value) {

									$quesito = DBRead('', 'tb_monitoria_mes a', "INNER JOIN tb_monitoria_mes_quesito b ON a.id_monitoria_mes = b.id_monitoria_mes INNER JOIN tb_monitoria_quesito c ON b.id_monitoria_quesito = c.id_monitoria_quesito WHERE c.id_monitoria_quesito = '$key' AND a.id_monitoria_mes = $formulario AND a.status = 1", "b.porcentagem_plano_acao, c.descricao, c.plano_acao");

									$resultado_geral = $value['soma'] / $value['qtd'];

									$class_bar = '';
									if ($resultado_geral >= $quesito[0]['porcentagem_plano_acao']) {
										$class_bar = 'progress-bar-success';
										$value_bar = $resultado_geral;
									} else if ($resultado_geral < $quesito[0]['porcentagem_plano_acao'] && $resultado_geral > 0) {
										$class_bar = 'progress-bar-warning ';
										$value_bar = $resultado_geral;
									} else if ($resultado_geral == 0) {
										$class_bar = 'progress-bar-danger ';
										$value_bar = 7;
									}
								?>
									<tr>
										<td><?= $quesito[0]['descricao'] ?></td>
										<td class="text-center"><?= $quesito[0]['porcentagem_plano_acao'] ?>%</td>
										<td class="text-center">
											<div class="progress" style="margin-left: 8px; margin-right: 9px;">
												<div class="progress-bar progress-bar-striped <?= $class_bar ?>" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: <?= $value_bar ?>%;">
													<span style="color: black;">
														<strong>
															<?= number_format($resultado_geral, 2, '.', ''); ?>%
														</strong>
													</span>
												</div>
											</div>
										</td>
										<td>
											<?= $quesito[0]['plano_acao'] ?>
										</td>

									</tr>
								<?php
								}
								?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
		<!-- end row resultado geral -->


		<?php

	} else {
		echo '<div style="text-align: center"><h4>Não foram encontrados resultados!</h4></div>';
	}
}

function relatorio_monitoria_geral_texto($mes, $ano, $lider, $tipo_relatorio, $classificacao, $formulario)
{
	$data_hora = converteDataHora(getDataHora());

	$data_referencia = $ano . '-' . $mes . '-01';
	$legenda_mes_referencia = $mes . '/' . $ano;

	$filtro = " data_referencia = '" . $data_referencia . "' ";

	$periodo_amostra = "<span style=\"font-size: 14px;\"><strong>Mês referência:</strong></span>";

	if ($tipo_relatorio == 2) {

		$filtro_lider_mes_atual = "AND a.lider_direto = '$lider'";
		$filtro_lider_outro_mes = "AND c.lider_direto = '$lider'";

		$dados_lider = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE id_usuario = '" . $lider . "'", "a.*, b.nome");
		$nome_lider = $dados_lider[0]['nome'];

		if (!$lider) {
			$legenda_lider = '';
		} else {
			$legenda_lider = "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong>Líder Direto:</strong> $nome_lider</span></legend>";
		}

		$filtro_lider = "AND a.lider_direto = '$lider' ";
		$nome_relatorio = 'Relatório de Monitoria por Equipe';
	} else {
		$nome_relatorio = 'Relatório de Monitoria Geral';
	}

	if ($classificacao == 1) {
		$legenda_classificacao = ' (via Texto - Em Treinamento)';

	} else if ($classificacao == 2) {
		$legenda_classificacao = ' (via Texto - Período de Experiência)';

	} else if ($classificacao == 3) {
		$legenda_classificacao = ' (via Texto - Efetivado)';
	}

	echo "<div class=\"col-md-12\" style=\"padding: 0\">";
	echo "<legend style=\"text-align:center;\"><strong>$nome_relatorio $legenda_classificacao</strong><br><span style=\"font-size: 14px;\"><strong>Gerado em:</strong> $data_hora</span></legend>";
	echo "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong>Mês referência:</strong> $legenda_mes_referencia</span></legend>";
	echo $legenda_lider;

	$mes_atual = explode('/', $data_hora);

	if ($formulario) {
		if ($mes_atual[1] == $mes) {
			$dados = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa INNER JOIN tb_monitoria_classificacao_usuario c ON a.id_usuario = c.id_usuario WHERE (a.id_perfil_sistema = 3) AND a.status = 1 $filtro_lider_mes_atual AND c.tipo_classificacao = $classificacao ORDER BY b.nome", "a.id_usuario, b.nome");
	
		} else {
			$dados = DBRead('', 'tb_monitoria_avaliacao_texto a', "INNER JOIN tb_monitoria_mes b ON a.id_monitoria_mes = b.id_monitoria_mes INNER JOIN tb_usuario c ON a.id_usuario_atendente = c.id_usuario INNER JOIN tb_pessoa d ON c.id_pessoa = d.id_pessoa WHERE a.id_monitoria_mes = $formulario AND b.tipo_monitoria = 2 AND b.classificacao_atendente = $classificacao $filtro_lider_outro_mes GROUP BY a.id_usuario_atendente ORDER BY d.nome ASC ", 'a.id_usuario_atendente as id_usuario, d.nome');
		}

	} else {
		$dados = '';
	}

	$resultado_geral_usuario = array();

	if ($dados) {

		echo "
		<table class=\"table table-striped dataTable\"> 
			<thead> 
				<tr> 
					<th>Atendente</th>
					<th class='text-center'>Resultado</th>
				</tr>
			</thead> 
			<tbody>";

		$soma = 0;
		$qtd_antendentes_avaliados = 0;

		foreach ($dados as $conteudo) {

			$resultado = 0;

			$nome = $conteudo['nome'];
			$id_usuario = $conteudo['id_usuario'];

			$turno = DBRead('', 'tb_horarios_escala', "WHERE id_usuario = '$id_usuario' AND data_inicial = '$data_referencia' ", 'inicial_seg, final_seg');

			if ($turno) {
				$inicial_seg = $turno[0]['inicial_seg'];
				$final_seg = $turno[0]['final_seg'];

				if ($inicial_seg > $final_seg) {
					$resultado = $inicial_seg - $final_seg;
				} else {
					$resultado = $final_seg - $inicial_seg;
				}

				if ($resultado == 6) {
					$turno = 'Integral';
				} else {
					$turno = 'Meio turno';
				}

				$resultado_monitoria = DBRead('', 'tb_monitoria_resultado', "WHERE id_monitoria_mes = $formulario AND $filtro AND id_usuario = '$id_usuario' ");

				if (!$resultado_monitoria || $resultado_monitoria[0]['resultado'] == '0.00') {
					$resultado = 0.00 . '%';
				} else {

					$dados_monitoria = DBRead('', 'tb_monitoria_avaliacao_texto', "WHERE id_usuario_atendente = '$id_usuario' AND id_monitoria_mes = $formulario ORDER BY data_monitoria ");

					$array_pontos = array();

					if ($dados_monitoria) {
						foreach ($dados_monitoria as $conteudo) {

							$id_atendimento = $conteudo['id_atendimento'];

							$dados_monitoria_mes = DBRead('', 'tb_monitoria_avaliacao_texto a', "INNER JOIN tb_monitoria_avaliacao_texto_mes b ON a.id_monitoria_avaliacao_texto = b.id_monitoria_avaliacao_texto INNER JOIN tb_monitoria_mes_quesito c ON b.id_monitoria_mes_quesito = c.id_monitoria_mes_quesito INNER JOIN tb_monitoria_quesito d ON c.id_monitoria_quesito = d.id_monitoria_quesito INNER JOIN tb_usuario e ON a.id_usuario_analista = e.id_usuario INNER JOIN tb_pessoa f ON e.id_pessoa = f.id_pessoa INNER JOIN tb_monitoria_mes g ON g.id_monitoria_mes = c.id_monitoria_mes WHERE a.id_usuario_atendente = '$id_usuario' AND a.id_atendimento = '$id_atendimento' AND a.id_monitoria_mes = $formulario AND g.status = 1 AND a.considerar = 1", 'a.id_erro, a.obs_avaliacao, a.total_pontos,c.pontos_valor, c.porcentagem_plano_acao, b.pontos, d.descricao, d.plano_acao,f.nome as nome_analista, g.soma_total_pontos_quesitos, d.id_monitoria_quesito');

							if ($dados_monitoria_mes) {
								foreach ($dados_monitoria_mes as $conteudo_monitoria) {

									$array_pontos[$conteudo_monitoria['id_monitoria_quesito']]['pontos'] += $conteudo_monitoria['pontos'];
									$array_pontos[$conteudo_monitoria['id_monitoria_quesito']]['qtd'] += 1;
									$array_pontos[$conteudo_monitoria['id_monitoria_quesito']]['pontos_valor'] = $conteudo_monitoria['pontos_valor'];
									$array_pontos[$conteudo_monitoria['id_monitoria_quesito']]['descricao'] = $conteudo_monitoria['descricao'];
									$array_pontos[$conteudo_monitoria['id_monitoria_quesito']]['plano_acao'] = $conteudo_monitoria['plano_acao'];
									$array_pontos[$conteudo_monitoria['id_monitoria_quesito']]['porcentagem_plano_acao'] = $conteudo_monitoria['porcentagem_plano_acao'];
									$array_pontos[$conteudo_monitoria['id_monitoria_quesito']]['id_monitoria_quesito'] = $conteudo_monitoria['id_monitoria_quesito'];
								}
							}
						}
					}

					foreach ($array_pontos as $conteudo_resultados) {

						$media = $conteudo_resultados['pontos'] / $conteudo_resultados['qtd'];
						$resultado_geral = ($media * 100) / $conteudo_resultados['pontos_valor'];

						$porcentagem = $conteudo_resultados['porcentagem_plano_acao'];

						$resultado_geral_usuario[$id_usuario][$conteudo_resultados['id_monitoria_quesito']]['media'] += $resultado_geral;
					}

					$resultado = $resultado_monitoria[0]['resultado'] . '%';
					$soma += $resultado_monitoria[0]['resultado'];
					$qtd_antendentes_avaliados++;
				}
			} else {
				$turno = 'Não possui escala definida!';
				$resultado = 0.00 . '%';
			}

			echo '
				<tr>
					<td>' . $nome . '</td>
					<td class="text-center">' . $resultado . '</td>
				</tr>	
			';
		}

		$resultado_total = sprintf("%01.2f", $soma / ($qtd_antendentes_avaliados == 0 ? 1 : $qtd_antendentes_avaliados));

		echo "</tbody>";
		echo "<tfoot>";
		echo "<tr>";
		echo "<th>Total:</th>";
		echo "<th class='text-center'>$resultado_total%</th>";
		echo "</tr>";
		echo "</tfoot>";
		echo "</table><br><br>";

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
			</script>";

		$soma_quesitos = array();
		foreach ($resultado_geral_usuario as $usuario) {
			foreach ($usuario as $quesito => $media) {
				$soma_quesitos[$quesito]['soma'] += $media['media'];
				$soma_quesitos[$quesito]['qtd'] += 1;
			}
		}

	?>
		<!-- row resultado geral -->
		<div class="row">
			<div class="col-md-12">

				<div class="jumbotron" style="border: 1px solid #A9A9A9;">
					<span class="text-center">
						<h3 style="margin-top: 5px;">
							<strong>
								Resultado geral da monitoria: <?= number_format($resultado_total, 2, '.', ' ') ?>%
							</strong>
						</h3>

						<hr class="hr-resultado">

						<br>
						<h4 style="margin-top: 5px;">
							<strong>Resultado geral da monitoria por quesito
								<i class="fa fa-tripadvisor"></i>
							</strong>
						</h4>
					</span>
					<br>

					<div class="table-responsive">
						<table class="table table-striped">
							<thead>
								<!-- <tr style="background-color: #D3D3D3 "> -->
								<tr>
									<th class="col-md-4">Quesito</th>
									<th class="col-md-1 text-center">Porcentagem definida</th>
									<th class="col-md-2 text-center">
										<span style="margin-left: 8px;">Resultado</span>
									</th>
									<th class="col-md-5">Plano de ação</th>
								</tr>
							</thead>
							<tbody>
								<?php
								foreach ($soma_quesitos as $key => $value) {

									$quesito = DBRead('', 'tb_monitoria_mes a', "INNER JOIN tb_monitoria_mes_quesito b ON a.id_monitoria_mes = b.id_monitoria_mes INNER JOIN tb_monitoria_quesito c ON b.id_monitoria_quesito = c.id_monitoria_quesito WHERE c.id_monitoria_quesito = '$key' AND a.id_monitoria_mes = $formulario AND a.status = 1", "b.porcentagem_plano_acao, c.descricao, c.plano_acao");

									$resultado_geral = $value['soma'] / $value['qtd'];

									$class_bar = '';
									if ($resultado_geral >= $quesito[0]['porcentagem_plano_acao']) {
										$class_bar = 'progress-bar-success';
										$value_bar = $resultado_geral;
									} else if ($resultado_geral < $quesito[0]['porcentagem_plano_acao'] && $resultado_geral > 0) {
										$class_bar = 'progress-bar-warning ';
										$value_bar = $resultado_geral;
									} else if ($resultado_geral == 0) {
										$class_bar = 'progress-bar-danger ';
										$value_bar = 7;
									}
								?>
									<tr>
										<td><?= $quesito[0]['descricao'] ?></td>
										<td class="text-center"><?= $quesito[0]['porcentagem_plano_acao'] ?>%</td>
										<td class="text-center">
											<div class="progress" style="margin-left: 8px; margin-right: 9px;">
												<div class="progress-bar progress-bar-striped <?= $class_bar ?>" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: <?= $value_bar ?>%;">
													<span style="color: black;">
														<strong>
															<?= number_format($resultado_geral, 2, '.', ''); ?>%
														</strong>
													</span>
												</div>
											</div>
										</td>
										<td>
											<?= $quesito[0]['plano_acao'] ?>
										</td>

									</tr>
								<?php
								}
								?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
		<!-- end row resultado geral -->


		<?php

	} else {
		echo '<div style="text-align: center"><h4>Não foram encontrados resultados!</h4></div>';
	}
}

function relatorio_performance_analista_telefone($analista, $mes, $ano, $classificacao, $formulario)
{

	if ($analista) {
		$filtro_analista = "AND a.id_usuario_analista = '$analista' ";

		$nome_analista = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_usuario = '$analista' ", 'b.nome');

		$legenda_analista = $nome_analista[0]['nome'];
	} else {
		$legenda_analista = 'Todos';
	}

	$data_hora = converteDataHora(getDataHora());

	$data_referencia = $ano . '-' . $mes . '-01';
	$legenda_mes_referencia = $mes . '/' . $ano;

	if ($classificacao == 1) {
		$legenda_classificacao = '- Em Treinamento';

	} else if ($classificacao == 2) {
		$legenda_classificacao = '- Período de Experiência';

	} else if ($classificacao == 3) {
		$legenda_classificacao = '- Efetivado';
	}

	echo "<div class=\"col-md-12\" style=\"padding: 0\">";
	echo "<legend style=\"text-align:center;\"><strong>Relatório de Performance dos Analistas (via Telefone $legenda_classificacao)</strong><br><span style=\"font-size: 14px;\"><strong>Gerado em:</strong> $data_hora</span></legend>";
	echo "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong>Mês referência:</strong> $legenda_mes_referencia</span></legend>";
	echo "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong>Analista:</strong> $legenda_analista</span></legend>";

	$dados_analistas = DBRead('', 'tb_monitoria_avaliacao_audio a', "INNER JOIN tb_usuario b ON b.id_usuario = a.id_usuario_analista INNER JOIN tb_pessoa c ON b.id_pessoa = c.id_pessoa WHERE b.status = '1' $filtro_analista AND a.id_monitoria_mes = $formulario ORDER BY c.nome ASC", "distinct a.id_usuario_analista, c.nome");

	if ($dados_analistas) {

		$cont_resutado = 0;
		$qtd_total_audios = 0;

		foreach ($dados_analistas as $conteudo) {

			$id_usuario = $conteudo['id_usuario_analista'];
			$nome_analista = $conteudo['nome'];

			$dados_monitoria = DBRead('', 'tb_monitoria_avaliacao_audio', "WHERE id_usuario_analista = '$id_usuario' AND data_referencia = '$data_referencia' ORDER BY data_monitoria ");

			$tempo_duracao_audio = 0;
			$tempo_duracao_audio_desconsiderados = 0;
			$tempo_total_avaliacoes = 0;

			if ($dados_monitoria) {
				$cont_resutado++;

				$array_pontos = array();
				$soma_resultados = array();

				foreach ($dados_monitoria as $conteudo) {

					$qtd_total_audios++;
					$considerar = $conteudo['considerar'];
					$duracao_audio = $conteudo['duracao_audio'];
					$duracao_avaliacao = $conteudo['duracao_avaliacao'];
					$id_ligacao = $conteudo['id_ligacao'];

					$dados_monitoria_mes = DBRead('', 'tb_monitoria_avaliacao_audio a', "INNER JOIN tb_monitoria_avaliacao_audio_mes b ON a.id_monitoria_avaliacao_audio = b.id_monitoria_avaliacao_audio INNER JOIN tb_monitoria_mes_quesito c ON b.id_monitoria_mes_quesito = c.id_monitoria_mes_quesito INNER JOIN tb_monitoria_quesito d ON c.id_monitoria_quesito = d.id_monitoria_quesito INNER JOIN tb_usuario e ON a.id_usuario_analista = e.id_usuario INNER JOIN tb_pessoa f ON e.id_pessoa = f.id_pessoa INNER JOIN tb_monitoria_mes g ON g.id_monitoria_mes = c.id_monitoria_mes WHERE a.data_referencia = '$data_referencia' AND a.id_usuario_analista = '$id_usuario' AND a.id_ligacao = '$id_ligacao' AND g.status = 1", 'a.duracao_audio, a.considerar, a.total_pontos, a.duracao_avaliacao, a.considerar, c.pontos_valor, c.porcentagem_plano_acao, b.pontos, d.plano_acao, d.descricao, f.nome as nome_analista, g.soma_total_pontos_quesitos, d.id_monitoria_quesito');

					if ($dados_monitoria_mes) {

						$total_pontos_feitos = 0;
						$soma_total_pontos_quesitos = 0;
						$duracao_total_audios = 0;
						$cont = 1;

						foreach ($dados_monitoria_mes as $conteudo_monitoria) {

							$considerar = $conteudo_monitoria['considerar'];

							$total_pontos_feitos = $conteudo_monitoria['total_pontos'];
							$soma_total_pontos_quesitos = $conteudo_monitoria['soma_total_pontos_quesitos'];
							$duracao_total_audios = $conteudo_monitoria['duracao_audio'];
							$duracao_total_avaliacao = $conteudo_monitoria['duracao_avaliacao'];
						
							$array_pontos[$conteudo_monitoria['id_monitoria_quesito']]['pontos'] += $conteudo_monitoria['pontos'];
							$array_pontos[$conteudo_monitoria['id_monitoria_quesito']]['qtd'] += 1;
							$array_pontos[$conteudo_monitoria['id_monitoria_quesito']]['pontos_valor'] = $conteudo_monitoria['pontos_valor'];
							$array_pontos[$conteudo_monitoria['id_monitoria_quesito']]['descricao'] = $conteudo_monitoria['descricao'];
							$array_pontos[$conteudo_monitoria['id_monitoria_quesito']]['plano_acao'] = $conteudo_monitoria['plano_acao'];
							$array_pontos[$conteudo_monitoria['id_monitoria_quesito']]['porcentagem_plano_acao'] = $conteudo_monitoria['porcentagem_plano_acao'];
							$array_pontos[$conteudo_monitoria['id_monitoria_quesito']]['id_monitoria_quesito'] = $conteudo_monitoria['id_monitoria_quesito'];
						}

						if ($considerar == 1) {

							$resultado_audio = ($total_pontos_feitos * 100) / $soma_total_pontos_quesitos;
							$soma_resultados[$id_usuario]['nome'] = $nome_analista;
							$soma_resultados[$id_usuario]['resultados'] += $resultado_audio;
							$soma_resultados[$id_usuario]['qtd_audios'] += 1;
							$soma_resultados[$id_usuario]['soma_duracao_audios'] += $duracao_total_audios;
							$soma_resultados[$id_usuario]['soma_duracao_avaliacao'] += $duracao_total_avaliacao;
							
						} else {

							$soma_resultados[$id_usuario]['qtd_audios_desconsiderados'] += 1;
							$soma_resultados[$id_usuario]['soma_duracao_audios_desconsiderados'] += $duracao_total_audios;
							$soma_resultados[$id_usuario]['soma_duracao_avaliacao_desconsiderados'] += $duracao_total_avaliacao;
						}
					}
				}

				$resultado_geral_usuario = array();

				foreach ($array_pontos as $conteudo_resultados) {

					$media = $conteudo_resultados['pontos'] / $conteudo_resultados['qtd'];
					$resultado_geral = ($media * 100) / $conteudo_resultados['pontos_valor'];				
					$porcentagem = $conteudo_resultados['porcentagem_plano_acao'];

					$resultado_geral_usuario[$id_usuario][$conteudo_resultados['id_monitoria_quesito']]['media'] += $resultado_geral;
					$resultado_geral_usuario[$id_usuario][$conteudo_resultados['id_monitoria_quesito']]['porcentagem_plano_acao'] = $conteudo_resultados['porcentagem_plano_acao'];
					$resultado_geral_usuario[$id_usuario][$conteudo_resultados['id_monitoria_quesito']]['descricao'] = $conteudo_resultados['descricao'];
					$resultado_geral_usuario[$id_usuario][$conteudo_resultados['id_monitoria_quesito']]['plano_acao'] = $conteudo_resultados['plano_acao'];
				}

				$soma_quesitos = array();
				foreach ($resultado_geral_usuario as $usuario) {
					foreach ($usuario as $quesito => $media) {
						$soma_quesitos[$quesito]['soma'] += $media['media'];
						$soma_quesitos[$quesito]['qtd'] += 1;
						$soma_quesitos[$quesito]['porcentagem_plano_acao'] = $media['porcentagem_plano_acao'];
						$soma_quesitos[$quesito]['descricao'] = $media['descricao'];
						$soma_quesitos[$quesito]['plano_acao'] = $media['plano_acao'];
					}
				}
		?>

				<!-- row resultado geral -->
				<div class="row">
					<div class="col-md-12">

						<div class="jumbotron" style="border: 1px solid #A9A9A9;">

							<span class="text-center">

								<h5 style="margin-top: 0px; font-size: 15px;">
									Analista: <strong><?= $soma_resultados[$id_usuario]['nome'] ?></strong>
								</h5>
								<hr class="hr-resultado">

								<div class="row" style="margin-top: 15px;">

									<div class="col-md-2">
										<h5 style="margin-top: 15px; font-size: 15px;">
											<?php
											$resultado_geral = $soma_resultados[$id_usuario]['resultados'] / $soma_resultados[$id_usuario]['qtd_audios'];
											?>
											<i class="fa fa-check" style="color: green;"></i> Resultado geral da monitoria (média do analista): <br><strong><?= number_format($resultado_geral, 2, '.', ' ') ?>%</strong>
											</strong>
										</h5>
									</div>

									<div class="col-md-2">
										<h5 style="margin-top: 15px; font-size: 15px;">
											<i class="fa fa-check" style="color: green;"></i> Áudios considerados: <br><strong><?= $soma_resultados[$id_usuario]['qtd_audios'] ?></strong>
										</h5>
									</div>

									<div class="col-md-3">
										<h5 style="margin-top: 15px; font-size: 15px;">
											<?php
											$TMA = $soma_resultados[$id_usuario]['soma_duracao_audios'] / $soma_resultados[$id_usuario]['qtd_audios'];
											?>
											<i class="fa fa-check" style="color: green;"></i> TMA dos áudios avaliados considerados: <br><strong><?= converteSegundosHoras($TMA) ?></strong>
										</h5>
									</div>

									<div class="col-md-2">
										<h5 style="margin-top: 15px; font-size: 15px;">

											<i class="fa fa-check" style="color: green;"></i> Tempo gasto em avaliações consideradas: <br><strong><?= converteSegundosHoras($soma_resultados[$id_usuario]['soma_duracao_avaliacao']) ?></strong>

										</h5>
									</div>

									<div class="col-md-2">
										<h5 style="margin-top: 15px; font-size: 15px;">

											<?php
											$TMA_avaliacoes = $soma_resultados[$id_usuario]['soma_duracao_avaliacao'] / $soma_resultados[$id_usuario]['qtd_audios']
											?>

											<i class="fa fa-check" style="color: green;"></i> TM gasto em avaliações consideradas: <br><strong><?= converteSegundosHoras($TMA_avaliacoes) ?></strong>

										</h5>
									</div>

								</div>

								<hr class="hr-resultado">
								<br>

								<?php if ($soma_resultados[$id_usuario]['qtd_audios_desconsiderados'] > 0) { ?>
									<div class="row">

										<div class="col-md-2">
										</div>

										<div class="col-md-2">
											<h5 style="margin-top: 15px; font-size: 15px;">

												<?php
												$qtd_audios_desconsiderados = $soma_resultados[$id_usuario]['qtd_audios_desconsiderados'];

												if ($qtd_audios_desconsiderados == '') {
													$qtd_audios_desconsiderados = 0;
												}
												?>

												<i class="fa fa-close" style="color: #B40404;"></i> Áudios desconsiderados: <br><strong><?= $qtd_audios_desconsiderados ?></strong>

											</h5>
										</div>

										<div class="col-md-3">
											<h5 style="margin-top: 15px; font-size: 15px;">
												<?php

												if ($soma_resultados[$id_usuario]['soma_duracao_audios_desconsiderados'] != 0) {
													$TMA_desconsiderados = $soma_resultados[$id_usuario]['soma_duracao_audios_desconsiderados'] / $soma_resultados[$id_usuario]['qtd_audios_desconsiderados'];
												} else {
													$TMA_desconsiderados = '00:00:00';
												}

												?>
												<i class="fa fa-close" style="color: #B40404;"></i> TMA dos áudios avaliados desconsiderados: <br><strong><?= converteSegundosHoras($TMA_desconsiderados) ?></strong>
											</h5>
										</div>

										<div class="col-md-2">
											<h5 style="margin-top: 15px; font-size: 15px;">

												<i class="fa fa-close" style="color: #B40404;"></i> Tempo gasto em avaliações desconsideradas: <br><strong><?= converteSegundosHoras($soma_resultados[$id_usuario]['soma_duracao_avaliacao_desconsiderados']) ?></strong>

											</h5>
										</div>

										<div class="col-md-2">
											<h5 style="margin-top: 15px; font-size: 15px;">

												<?php
												$TMA_avaliacoes_desconsideradas = $soma_resultados[$id_usuario]['soma_duracao_avaliacao_desconsiderados'] / $soma_resultados[$id_usuario]['qtd_audios_desconsiderados']
												?>

												<i class="fa fa-close" style="color: #B40404;"></i> TM gasto em avaliações desconsideradas: <br><strong><?= converteSegundosHoras($TMA_avaliacoes_desconsideradas) ?></strong>

											</h5>
										</div>

									</div>

									<hr class="hr-resultado">
									<br>
								<?php } ?>

								<h4 style="margin-top: 5px; font-size: 15px;">
									<strong>Resultado geral da monitoria por quesito (média do analista em avaliações consideradas)
										<i class="fa fa-tripadvisor"></i>
									</strong>
								</h4>

							</span>
							<br>

							<div class="table-responsive">
								<table class="table table-striped">
									<thead>
										<tr>
											<th class="col-md-4">Quesito</th>
											<th class="col-md-1 text-center">Porcentagem definida</th>
											<th class="col-md-2 text-center">
												<span style="margin-left: 8px;">Resultado</span>
											</th>
											<th class="col-md-5">Plano de ação</th>
										</tr>
									</thead>
									<tbody>
										<?php
										foreach ($soma_quesitos as $key => $value) {
											$resultado_geral = 0;
											$resultado_geral = $value['soma'] / $value['qtd'];

											$class_bar = '';
											if ($resultado_geral >= $value[0]['porcentagem_plano_acao']) {
												$class_bar = 'progress-bar-success';
												$value_bar = $resultado_geral;
											} else if ($resultado_geral < $value[0]['porcentagem_plano_acao'] && $resultado_geral > 0) {
												$class_bar = 'progress-bar-warning ';
												$value_bar = $resultado_geral;
											} else if ($resultado_geral == 0) {
												$class_bar = 'progress-bar-danger ';
												$value_bar = 7;
											}
										?>
											<tr>
												<td><?= $value['descricao'] ?></td>
												<td class="text-center"><?= $value['porcentagem_plano_acao'] ?>%</td>
												<td class="text-center">
													<div class="progress" style="margin-left: 8px; margin-right: 9px;">
														<div class="progress-bar progress-bar-striped <?= $class_bar ?>" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: <?= $value_bar ?>%;">
															<span style="color: black;">
																<strong>
																	<?= number_format($resultado_geral, 2, '.', ''); ?>%
																</strong>
															</span>
														</div>
													</div>
												</td>
												<td>
													<?= $value['plano_acao'] ?>
												</td>

											</tr>
										<?php
										}
										?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
				<!-- end row resultado geral -->

				<br>

			<?php
			}
		}

		if ($cont_resutado == 0) {
			echo '<div style="text-align: center"><h4>Não foram encontrados resultados!</h4></div>';
		} else {
			echo '<div class="row"><div class="col-md-4"></div><div class="col-md-4 text-center"><h4><strong>Quantidade total de áudios avaliados: </strong>' . $qtd_total_audios . '</h4></div><div class="col-md-4"></div></div><br><br>';
		}
	}
}

function relatorio_performance_analista_texto($analista, $mes, $ano, $classificacao, $formulario)
{

	if ($analista) {
		$filtro_analista = "AND a.id_usuario_analista = '$analista' ";

		$nome_analista = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_usuario = '$analista' ", 'b.nome');

		$legenda_analista = $nome_analista[0]['nome'];
	} else {
		$legenda_analista = 'Todos';
	}

	$data_hora = converteDataHora(getDataHora());

	$data_referencia = $ano . '-' . $mes . '-01';
	$legenda_mes_referencia = $mes . '/' . $ano;

	if ($classificacao == 1) {
		$legenda_classificacao = '- Em Treinamento';

	} else if ($classificacao == 2) {
		$legenda_classificacao = '- Período de Experiência';

	} else if ($classificacao == 3) {
		$legenda_classificacao = '- Efetivado';
	}

	echo "<div class=\"col-md-12\" style=\"padding: 0\">";
	echo "<legend style=\"text-align:center;\"><strong>Relatório de Performance dos Analistas (via Texto $legenda_classificacao)</strong><br><span style=\"font-size: 14px;\"><strong>Gerado em:</strong> $data_hora</span></legend>";
	echo "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong>Mês referência:</strong> $legenda_mes_referencia</span></legend>";
	echo "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong>Analista:</strong> $legenda_analista</span></legend>";

	$dados_analistas = DBRead('', 'tb_monitoria_avaliacao_texto a', "INNER JOIN tb_usuario b ON b.id_usuario = a.id_usuario_analista INNER JOIN tb_pessoa c ON b.id_pessoa = c.id_pessoa WHERE b.status = '1' $filtro_analista AND a.id_monitoria_mes = $formulario ORDER BY c.nome ASC", "distinct a.id_usuario_analista, c.nome");

	if ($dados_analistas) {

		$cont_resutado = 0;
		$qtd_total_atendimentos = 0;

		foreach ($dados_analistas as $conteudo) {

			$id_usuario = $conteudo['id_usuario_analista'];
			$nome_analista = $conteudo['nome'];

			$dados_monitoria = DBRead('', 'tb_monitoria_avaliacao_texto', "WHERE id_usuario_analista = '$id_usuario' AND data_referencia = '$data_referencia' ORDER BY data_monitoria ");

			$tempo_duracao_audio_desconsiderados = 0;
			$tempo_total_avaliacoes = 0;

			if ($dados_monitoria) {
				$cont_resutado++;

				$array_pontos = array();
				$soma_resultados = array();

				foreach ($dados_monitoria as $conteudo) {

					$qtd_total_atendimentos++;
					$considerar = $conteudo['considerar'];
					$duracao_avaliacao = $conteudo['duracao_avaliacao'];
					$id_atendimento = $conteudo['id_atendimento'];

					$dados_monitoria_mes = DBRead('', 'tb_monitoria_avaliacao_texto a', "INNER JOIN tb_monitoria_avaliacao_texto_mes b ON a.id_monitoria_avaliacao_texto = b.id_monitoria_avaliacao_texto INNER JOIN tb_monitoria_mes_quesito c ON b.id_monitoria_mes_quesito = c.id_monitoria_mes_quesito INNER JOIN tb_monitoria_quesito d ON c.id_monitoria_quesito = d.id_monitoria_quesito INNER JOIN tb_usuario e ON a.id_usuario_analista = e.id_usuario INNER JOIN tb_pessoa f ON e.id_pessoa = f.id_pessoa INNER JOIN tb_monitoria_mes g ON g.id_monitoria_mes = c.id_monitoria_mes WHERE a.data_referencia = '$data_referencia' AND a.id_usuario_analista = '$id_usuario' AND a.id_atendimento = '$id_atendimento' AND g.status = 1", 'a.considerar, a.total_pontos, a.duracao_avaliacao, a.considerar, c.pontos_valor, c.porcentagem_plano_acao, b.pontos, d.descricao, f.nome as nome_analista, g.soma_total_pontos_quesitos, d.id_monitoria_quesito');

					if ($dados_monitoria_mes) {

						$total_pontos_feitos = 0;
						$soma_total_pontos_quesitos = 0;

						foreach ($dados_monitoria_mes as $conteudo_monitoria) {

							$considerar = $conteudo_monitoria['considerar'];

							$total_pontos_feitos = $conteudo_monitoria['total_pontos'];
							$soma_total_pontos_quesitos = $conteudo_monitoria['soma_total_pontos_quesitos'];
							$duracao_total_avaliacao = $conteudo_monitoria['duracao_avaliacao'];

							$array_pontos[$conteudo_monitoria['id_monitoria_quesito']]['pontos'] += $conteudo_monitoria['pontos'];
							$array_pontos[$conteudo_monitoria['id_monitoria_quesito']]['qtd'] += 1;
							$array_pontos[$conteudo_monitoria['id_monitoria_quesito']]['pontos_valor'] = $conteudo_monitoria['pontos_valor'];
							$array_pontos[$conteudo_monitoria['id_monitoria_quesito']]['descricao'] = $conteudo_monitoria['descricao'];
							$array_pontos[$conteudo_monitoria['id_monitoria_quesito']]['plano_acao'] = $conteudo_monitoria['plano_acao'];
							$array_pontos[$conteudo_monitoria['id_monitoria_quesito']]['porcentagem_plano_acao'] = $conteudo_monitoria['porcentagem_plano_acao'];
							$array_pontos[$conteudo_monitoria['id_monitoria_quesito']]['id_monitoria_quesito'] = $conteudo_monitoria['id_monitoria_quesito'];
						}

						if ($considerar == 1) {
							$resultado_texto = ($total_pontos_feitos * 100) / $soma_total_pontos_quesitos;
							$soma_resultados[$id_usuario]['nome'] = $nome_analista;
							$soma_resultados[$id_usuario]['resultados'] += $resultado_texto;
							$soma_resultados[$id_usuario]['qtd_atendimentos'] += 1;
							$soma_resultados[$id_usuario]['soma_duracao_avaliacao'] += $duracao_total_avaliacao;

						} else {
							$soma_resultados[$id_usuario]['qtd_atendimentos_desconsiderados'] += 1;
							$soma_resultados[$id_usuario]['soma_duracao_avaliacao_desconsiderados'] += $duracao_total_avaliacao;
						}
					}
				}

				$resultado_geral_usuario = array();

				foreach ($array_pontos as $conteudo_resultados) {

					$media = $conteudo_resultados['pontos'] / $conteudo_resultados['qtd'];
					$resultado_geral = ($media * 100) / $conteudo_resultados['pontos_valor'];

					$porcentagem = $conteudo_resultados['porcentagem_plano_acao'];

					$resultado_geral_usuario[$id_usuario][$conteudo_resultados['id_monitoria_quesito']]['media'] += $resultado_geral;
				}

				$soma_quesitos = array();
				foreach ($resultado_geral_usuario as $usuario) {
					foreach ($usuario as $quesito => $media) {
						$soma_quesitos[$quesito]['soma'] += $media['media'];
						$soma_quesitos[$quesito]['qtd'] += 1;
					}
				}

		?>

				<!-- row resultado geral -->
				<div class="row">
					<div class="col-md-12">

						<div class="jumbotron" style="border: 1px solid #A9A9A9;">

							<span class="text-center">

								<h5 style="margin-top: 0px; font-size: 15px;">
									Analista: <strong><?= $soma_resultados[$id_usuario]['nome'] ?></strong>
								</h5>
								<hr class="hr-resultado">

								<div class="row" style="margin-top: 15px;">

									<div class="col-md-3">
										<h5 style="margin-top: 15px; font-size: 15px;">
											<?php
											$resultado_geral = $soma_resultados[$id_usuario]['resultados'] / $soma_resultados[$id_usuario]['qtd_atendimentos'];
											?>
											<i class="fa fa-check" style="color: green;"></i> Resultado geral da monitoria (média do analista): <br><strong><?= number_format($resultado_geral, 2, '.', ' ') ?>%</strong>
											</strong>
										</h5>
									</div>

									<div class="col-md-3">
										<h5 style="margin-top: 15px; font-size: 15px;">

											<i class="fa fa-check" style="color: green;"></i> Atendimentos considerados: <br><strong><?= $soma_resultados[$id_usuario]['qtd_atendimentos'] ?></strong>

										</h5>
									</div>

									<div class="col-md-3">
										<h5 style="margin-top: 15px; font-size: 15px;">

											<i class="fa fa-check" style="color: green;"></i> Tempo gasto em avaliações consideradas: <br><strong><?= converteSegundosHoras($soma_resultados[$id_usuario]['soma_duracao_avaliacao']) ?></strong>

										</h5>
									</div>

									<div class="col-md-3">
										<h5 style="margin-top: 15px; font-size: 15px;">

											<?php
											$TMA_avaliacoes = $soma_resultados[$id_usuario]['soma_duracao_avaliacao'] / $soma_resultados[$id_usuario]['qtd_atendimentos']
											?>

											<i class="fa fa-check" style="color: green;"></i> TM gasto em avaliações consideradas: <br><strong><?= converteSegundosHoras($TMA_avaliacoes) ?></strong>

										</h5>
									</div>

								</div>

								<hr class="hr-resultado">
								<br>

								<?php if ($soma_resultados[$id_usuario]['qtd_audios_desconsiderados'] > 0) { ?>
									<div class="row">

										<div class="col-md-2">
										</div>

										<div class="col-md-2">
											<h5 style="margin-top: 15px; font-size: 15px;">

												<?php
												$qtd_audios_desconsiderados = $soma_resultados[$id_usuario]['qtd_atendimentos_desconsiderados'];

												if ($qtd_audios_desconsiderados == '') {
													$qtd_audios_desconsiderados = 0;
												}
												?>

												<i class="fa fa-close" style="color: #B40404;"></i> Áudios desconsiderados: <br><strong><?= $qtd_audios_desconsiderados ?></strong>

											</h5>
										</div>

										<div class="col-md-3">
											<h5 style="margin-top: 15px; font-size: 15px;">
												<?php

												if ($soma_resultados[$id_usuario]['soma_duracao_audios_desconsiderados'] != 0) {
													$TMA_desconsiderados = $soma_resultados[$id_usuario]['soma_duracao_audios_desconsiderados'] / $soma_resultados[$id_usuario]['qtd_audios_desconsiderados'];
												} else {
													$TMA_desconsiderados = '00:00:00';
												}

												?>
												<i class="fa fa-close" style="color: #B40404;"></i> TMA dos áudios avaliados desconsiderados: <br><strong><?= converteSegundosHoras($TMA_desconsiderados) ?></strong>
											</h5>
										</div>

										<div class="col-md-2">
											<h5 style="margin-top: 15px; font-size: 15px;">

												<i class="fa fa-close" style="color: #B40404;"></i> Tempo gasto em avaliações desconsideradas: <br><strong><?= converteSegundosHoras($soma_resultados[$id_usuario]['soma_duracao_avaliacao_desconsiderados']) ?></strong>

											</h5>
										</div>

										<div class="col-md-2">
											<h5 style="margin-top: 15px; font-size: 15px;">

												<?php
												$TMA_avaliacoes_desconsideradas = $soma_resultados[$id_usuario]['soma_duracao_avaliacao_desconsiderados'] / $soma_resultados[$id_usuario]['qtd_audios_desconsiderados']
												?>

												<i class="fa fa-close" style="color: #B40404;"></i> TM gasto em avaliações desconsideradas: <br><strong><?= converteSegundosHoras($TMA_avaliacoes_desconsideradas) ?></strong>

											</h5>
										</div>

									</div>

									<hr class="hr-resultado">
									<br>
								<?php } ?>

								<h4 style="margin-top: 5px; font-size: 15px;">
									<strong>Resultado geral da monitoria por quesito (média do analista em avaliações consideradas)
										<i class="fa fa-tripadvisor"></i>
									</strong>
								</h4>

							</span>
							<br>

							<div class="table-responsive">
								<table class="table table-striped">
									<thead>
										<tr>
											<th class="col-md-4">Quesito</th>
											<th class="col-md-1 text-center">Porcentagem definida</th>
											<th class="col-md-2 text-center">
												<span style="margin-left: 8px;">Resultado</span>
											</th>
											<th class="col-md-5">Plano de ação</th>
										</tr>
									</thead>
									<tbody>
										<?php
										foreach ($soma_quesitos as $key => $value) {

											$quesito = DBRead('', 'tb_monitoria_mes a', "INNER JOIN tb_monitoria_mes_quesito b ON a.id_monitoria_mes = b.id_monitoria_mes INNER JOIN tb_monitoria_quesito c ON b.id_monitoria_quesito = c.id_monitoria_quesito WHERE c.id_monitoria_quesito = '$key' AND a.data_referencia = '$data_referencia' AND a.status = 1", "b.porcentagem_plano_acao, c.descricao, c.plano_acao");

											$resultado_geral = $value['soma'] / $value['qtd'];

											$class_bar = '';
											if ($resultado_geral >= $quesito[0]['porcentagem_plano_acao']) {
												$class_bar = 'progress-bar-success';
												$value_bar = $resultado_geral;
											} else if ($resultado_geral < $quesito[0]['porcentagem_plano_acao'] && $resultado_geral > 0) {
												$class_bar = 'progress-bar-warning ';
												$value_bar = $resultado_geral;
											} else if ($resultado_geral == 0) {
												$class_bar = 'progress-bar-danger ';
												$value_bar = 7;
											}
										?>
											<tr>
												<td><?= $quesito[0]['descricao'] ?></td>
												<td class="text-center"><?= $quesito[0]['porcentagem_plano_acao'] ?>%</td>
												<td class="text-center">
													<div class="progress" style="margin-left: 8px; margin-right: 9px;">
														<div class="progress-bar progress-bar-striped <?= $class_bar ?>" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: <?= $value_bar ?>%;">
															<span style="color: black;">
																<strong>
																	<?= number_format($resultado_geral, 2, '.', ''); ?>%
																</strong>
															</span>
														</div>
													</div>
												</td>
												<td>
													<?= $quesito[0]['plano_acao'] ?>
												</td>

											</tr>
										<?php
										}
										?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
				<!-- end row resultado geral -->

				<br>

			<?php
			}
		}

		if ($cont_resutado == 0) {
			echo '<div style="text-align: center"><h4>Não foram encontrados resultados!</h4></div>';
		} else {
			echo '<div class="row"><div class="col-md-4"></div><div class="col-md-4 text-center"><h4><strong>Quantidade total de atendimentos avaliados: </strong>' . $qtd_total_atendimentos . '</h4></div><div class="col-md-4"></div></div><br><br>';
		}
	}
}

function relatorio_contagem_avaliacoes($analista, $data_dia)
{

	if ($analista) {
		$filtro_analista = "AND a.id_usuario_analista = '$analista' ";

		$nome_analista = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_usuario = '$analista' ", 'b.nome');

		$legenda_analista = $nome_analista[0]['nome'];
	} else {
		$legenda_analista = 'Todos';
	}

	$data_legenda = converteDataHora($data_dia);
	$data_hora = converteDataHora(getDataHora());

	$data_de = $data_legenda.'00:00:00';
	$data_ate = $data_legenda.'23:59:59';

	echo "<div class=\"col-md-8 col-md-offset-2\" style=\"padding: 0\">";
	echo "<legend style=\"text-align:center;\"><strong>Relatório de Contagem de Avaliações</strong><br><span style=\"font-size: 14px;\"><strong>Gerado em:</strong> $data_hora</span></legend>";
	echo "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong>Data:</strong> " . converteDataHora($data_legenda) . "</span></legend>";
	echo "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong>Analista:</strong> $legenda_analista</span></legend>";

	$dados_analistas = DBRead('', 'tb_monitoria_avaliacao_audio a', "INNER JOIN tb_usuario b ON b.id_usuario = a.id_usuario_analista INNER JOIN tb_pessoa c ON b.id_pessoa = c.id_pessoa WHERE b.status = '1' AND (a.data_monitoria BETWEEN '$data_de' AND '$data_ate') UNION SELECT d.id_usuario_analista, f.nome FROM tb_monitoria_avaliacao_texto d INNER JOIN tb_usuario e ON e.id_usuario = d.id_usuario_analista INNER JOIN tb_pessoa f ON e.id_pessoa = f.id_pessoa WHERE e.status = '1' AND (d.data_monitoria BETWEEN '$data_de' AND '$data_ate') ORDER BY nome ASC", "a.id_usuario_analista, c.nome");

	if ($dados_analistas) {

		foreach ($dados_analistas as $conteudo) {

			$contagem_telefone = DBRead('', 'tb_monitoria_avaliacao_audio a', "INNER JOIN tb_monitoria_mes b ON a.id_monitoria_mes = b.id_monitoria_mes WHERE id_usuario_analista = '" . $conteudo['id_usuario_analista'] . "' AND (data_monitoria >= '$data_de' AND data_monitoria <= '$data_ate') ORDER BY data_monitoria DESC ");

			$contagem_texto = DBRead('', 'tb_monitoria_avaliacao_texto a', "INNER JOIN tb_monitoria_mes b ON a.id_monitoria_mes = b.id_monitoria_mes WHERE id_usuario_analista = '" . $conteudo['id_usuario_analista'] . "' AND (data_monitoria >= '$data_de' AND data_monitoria <= '$data_ate') ORDER BY data_monitoria DESC ");

			if ($contagem_telefone || $contagem_texto) {

				$cont_telefone_treinamento = 0;
				$cont_telefone_experiencia = 0;
				$cont_telefone_efetivado = 0;
				$cont_telefone = 0;
				$cont_texto_treinamento = 0;
				$cont_texto_experiencia  = 0;
				$cont_texto_efetivado = 0;
				$cont_texto = 0;
			
			?>	
				<div class="panel panel-default" style="border: 1px solid #BDBDBD;">
					<div class="panel-heading clearfix">
						<h3 class="panel-title text-left">
							<span>&nbspAnalista:<strong> <?= $conteudo['nome'] ?></strong></span>
						</h3>
					</div>
					<div class="panel-body">

						<?php if ($contagem_telefone) { ?>
							
							<span class="label label-primary"><strong>via Telefone:</strong></span>
							<table class="table table-hover dataTable">
								<thead style="background-color: #F2F2F2">
									<tr>
										<td class='col-md-3'><strong>Data da monitoria</strong></td>
										<td class='col-md-3 text-center'><strong>Nota</strong></td>
										<td class='col-md-3 text-center'><strong>Classificação de atendente</strong></td>
										<td class='col-md-3 text-center'><strong>Considerada para avaliação</strong></td>
									</tr>
								</thead>
								<tbody>

									<?php
									foreach ($contagem_telefone as $conteudo_contagem) {
										$cont_telefone++;

										if ($conteudo_contagem['nota'] == '') {
											$nota = 'Sem nota';
										} else {
											$nota = $conteudo_contagem['nota'];
										}

										if ($conteudo_contagem['considerar'] == 1) {
											$considerar = 'Sim';
										} else {
											$considerar = 'Não';
										}

										if ($conteudo_contagem['classificacao_atendente'] == 1) {
											$classificacao = 'Em Treinamento';
											$cont_telefone_treinamento++;

										} else if ($conteudo_contagem['classificacao_atendente'] == 2) {
											$classificacao = 'Período de Experiência';
											$cont_telefone_experiencia++;

										} else if ($conteudo_contagem['classificacao_atendente'] == 3) {
											$classificacao = 'Efetivado';
											$cont_telefone_efetivado++;
										}

										echo '<tr>
												<td>' . converteDataHora($conteudo_contagem['data_monitoria']) . '</td>
												<td class="text-center">' . $nota . '</td>
												<td class="text-center">' . $classificacao . '</td>
												<td class="text-center">' . $considerar . '</td>
											</tr>';
									}
									?>

								</tbody>
								<tfoot>
									<tr>
										<td>
											<strong>Total de avaliações:</strong>
											<span class="label label-default" style="font-size: 14px;">
												<strong><?= $cont_telefone ?></strong>
											</span>
										</td>
										<td></td>
										<td></td>
										<td></td>
									<tr>
								</tfoot>
							</table>
							<hr>

						<?php } else { ?>
							
							<div style="text-align: center"><h4>Não foram encontradas avaliações via Telefone!</h4></div>

						<?php } ?>

						<?php if ($contagem_texto) { ?>
						
							<span class="label label-info"><strong>via Texto:</strong></span>
							<table class="table table-hover dataTable">
								<thead style="background-color: #F2F2F2">
									<tr>
										<td class='col-md-3'><strong>Data da monitoria</strong></td>
										<td class='col-md-3 text-center'></td>
										<td class='col-md-3 text-center'><strong>Classificação de atendente</strong></td>
										<td class='col-md-3 text-center'><strong>Considerada para avaliação</strong></td>
									</tr>
								</thead>
								<tbody>

									<?php
									foreach ($contagem_texto as $conteudo_contagem) {
										$cont_texto++;

										if ($conteudo_contagem['considerar'] == 1) {
											$considerar = 'Sim';
										} else {
											$considerar = 'Não';
										}

										if ($conteudo_contagem['classificacao_atendente'] == 1) {
											$classificacao = 'Em Treinamento';
											$cont_texto_treinamento++;

										} else if ($conteudo_contagem['classificacao_atendente'] == 2) {
											$classificacao = 'Período de Experiência';
											$cont_texto_experiencia++;

										} else if ($conteudo_contagem['classificacao_atendente'] == 3) {
											$classificacao = 'Efetivado';
											$cont_texto_efetivado++;
										}

										echo '<tr>
												<td>' . converteDataHora($conteudo_contagem['data_monitoria']) . '</td>
												<td></td>
												<td class="text-center">' . $classificacao . '</td>
												<td class="text-center">' . $considerar . '</td>
											</tr>';
									}
									?>

								</tbody>
								<tfoot>
									<tr>
										<td>
											<strong>Total de avaliações:</strong> 
											<span class="label label-default" style="font-size: 14px;">
												<strong><?= $cont_texto ?></strong>
											</span>
										</td>
										<td></td>
										<td></td>
										<td></td>
									<tr>
								</tfoot>
							</table>
						
						<?php } else { ?>

							<div style="text-align: center"><h4>Não foram encontradas avaliações via Texto!</h4></div>
						
						<?php } ?>
						
						<hr>
						<table class="table table-hover dataTable">
							<thead style="background-color: #F2F2F2">
								<tr>
									<td class='col-md-5 text-center' colspan="3" style="background-color: #337ab7; color: white;">
										<strong>via Telefone</strong>
									</td>
									<td class='col-md-5 text-center' colspan="3" style="background-color: #5bc0de; color: white;">
										<strong>via Texto</strong>
									</td>
									<td class='col-md-2 text-center'  style="background-color: #777; color: white;">Total</td>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td class="col-md-2 text-center">Em treinamento</td>
									<td class="col-md-2 text-center">Período do Experiência</td>
									<td class="col-md-2 text-center">Efetivado</td>
									<td class="col-md-2 text-center">Em treinamento</td>
									<td class="col-md-2 text-center">Período do Experiência</td>
									<td class="col-md-2 text-center">Efetivado</td>
									<td class="col-md-2 text-center">Avaliações</td>
								</tr>
								<tr>
									<td class="text-center">
										<span class="label label-primary" style="font-size: 14px;">
											<?= $cont_telefone_treinamento ?>
										</span>
									</td>
									<td class="text-center">
										<span class="label label-primary" style="font-size: 14px;">
											<?= $cont_telefone_experiencia ?>
										</span>
									</td>
									<td class="text-center">
										<span class="label label-primary" style="font-size: 14px;">
											<?= $cont_telefone_efetivado ?>
										</span>
									</td>
									<td class="text-center">
										<span class="label label-info" style="font-size: 14px;">
											<?= $cont_texto_treinamento ?>
										</span>
									</td>
									<td class="text-center">
										<span class="label label-info" style="font-size: 14px;">
											<?= $cont_texto_experiencia ?>
										</span>
									</td>
									<td class="text-center">
										<span class="label label-info" style="font-size: 14px;">
											<?= $cont_texto_efetivado ?>
										</span>
									</td>
									<?php
										$total = $cont_telefone_treinamento + $cont_telefone_experiencia + $cont_telefone_efetivado + $cont_texto_treinamento + $cont_texto_experiencia + $cont_texto_efetivado;
									?>
									<td class="text-center">
										<span class="label label-default" style="font-size: 14px;">
											<?= $total ?>
										</span>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
				<br>

			<?php

			} else {

			?>
				<span>&nbspAnalista:<strong> <?= $conteudo['nome'] ?></strong></span>
				<table class="table table-hover dataTable">
					<thead style="background-color: #e8e8e8">
						<tr class='danger'>
							<td>Sem avaliações!</td>
						</tr>
					</thead>
				</table>

				<br>
				<hr>
				<br>

			<?php
			}
		}
	} else {
		echo '<div style="text-align: center"><h4>Não foram encontradas avaliações!</h4></div>';
	}
}

function relatorio_plano_de_acao($mes, $ano, $usuario2, $acao, $parabenizado, $canal_atendimento, $classificacao, $formulario)
{
	if ($usuario2) {
		$nome_usuario = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_usuario = '" . $usuario2 . "' ", 'b.nome');
		$legenda_usuario = $nome_usuario[0]['nome'];

		$filtro_usuario = " AND b.id_usuario_remetente = '$usuario2' ";

	} else {
		$legenda_usuario = 'Todos';
	}

	if ($parabenizado == 1) {
		$filtro_parabenizado = " AND b.descricao LIKE '%Quesito%'";
		$legenda_parabenizado = 'Não';

	} else if ($parabenizado == 2) {
		$filtro_parabenizado = " AND b.descricao NOT LIKE '%Quesito%'";
		$legenda_parabenizado = 'Sim';

	} else {
		$legenda_parabenizado = 'Qualquer';
	}

	if ($acao) {
		$legenda_acao = 'Sim';
	} else {
		$legenda_acao = 'Não';
	}

	if ($canal_atendimento == 1) {
		$legenda_tipo_classificacao = '(via Telefone - ';

	} else if ($canal_atendimento == 2) {
		$legenda_tipo_classificacao = '(via Texto - ';
	}

	if ($classificacao == 1) {
		$legenda_tipo_classificacao .= 'Em treinamento)';

	} else if ($classificacao == 2) {
		$legenda_tipo_classificacao .= 'Período de Experiência)';

	} else if ($classificacao == 3) {
		$legenda_tipo_classificacao .= 'Efetivado)';
	}

	$referencia = $mes . "/" . $ano;
	$data_referencia = $ano . '-' . $mes . '-01';

	$date = new DateTime($data_referencia);
	$date->modify('first day of next month');
	$primeiro_dia = $date->format('Y-m-d');
	$primeiro_dia .= ' 00:00:00';

	$date2 = new DateTime($data_referencia);
	$date2->modify('last day of next month');
	$ultimo_dia = $date2->format('Y-m-d');
	$ultimo_dia .= ' 23:59:59';

	$filtro_data = "AND a.data_criacao >= '" . $primeiro_dia . "' AND a.data_criacao <= '" . $ultimo_dia . "'";

	$data_hora = converteDataHora(getDataHora());

	echo "<div class=\"col-md-10 col-md-offset-1\" style=\"padding: 0\">";
	echo "<legend style=\"text-align:center;\"><strong>Relatório de Planos de Ações $legenda_tipo_classificacao</strong><br><span style=\"font-size: 14px;\"><strong>Gerado em:</strong> $data_hora</span></legend>";
	echo "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong>Referência:</strong> " . $referencia . "</span></legend>";
	echo "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"> <strong>Atendente -</strong> " . $legenda_usuario . ", <strong>Mostrar Ações -</strong> " . $legenda_acao . ", <strong>Atingiu Quesitos -</strong> " . $legenda_parabenizado . "</span></legend>";

	if ($formulario) {
		$dados_chamado = DBRead('', 'tb_monitoria_mes_chamado a', "INNER JOIN tb_chamado b ON a.id_chamado = b.id_chamado INNER JOIN tb_chamado_origem c ON b.id_chamado_origem = c.id_chamado_origem INNER JOIN tb_chamado_status d ON b.id_chamado_status = d.id_chamado_status INNER JOIN tb_chamado_categoria e ON b.id_chamado = e.id_chamado INNER JOIN tb_usuario f ON b.id_usuario_responsavel = f.id_usuario INNER JOIN tb_pessoa g ON f.id_pessoa = g.id_pessoa WHERE a.id_monitoria_mes = $formulario $filtro_usuario ORDER BY b.id_chamado DESC", "c.descricao AS descricao_chamado, d.descricao AS descricao_status, g.nome AS nome_responsavel, b.*");

	} else {
		$dados_chamado = '';
	}

	if ($dados_chamado) {

		foreach ($dados_chamado as $dado) {
			$dados_usuario = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_usuario = '" . $dado['id_usuario_remetente'] . "'");

			?>

			<div class="panel panel-default">
				<div class="panel-heading clearfix">
					<div class="row">
						<h3 class="panel-title text-left col-md-4"><strong>#</strong> <?= $dado['id_chamado'] ?></h3>
						<h3 class="panel-title text-center col-md-4"><strong>Atendente: <?= $dados_usuario[0]['nome'] ?></strong></h3>
						<h3 class="panel-title text-right col-md-4"></h3>
					</div>
				</div>
				<div class="panel-body painel-body">
					<div class="row">
						<div class="col-md-12">
							<table class="table table-hover">
								<tbody>
									<tr>
										<td class="td-table col-md-4 text-left" style="border-top: 0 !important"><strong>Status:</strong> <?= $dado['descricao_status'] ?></td>
									</tr>

								</tbody>
							</table>
						</div><!-- end col -->

						<div class="col-md-12">
							<table class="table table-hover">
								<tbody>
									<tr>
										<td class="td-table col-md-3 conteudo-editor"><strong>Descrição: </strong> <?= $dado['descricao'] ?></td>
									</tr>
								</tbody>
							</table>
						</div><!-- end col -->
					</div><!-- end row -->
					<hr>

					<?php

					if ($acao) {
						$chamados_acao = DBRead('', 'tb_chamado_acao a', "INNER JOIN tb_chamado_status b ON a.id_chamado_status = b.id_chamado_status INNER JOIN tb_usuario c ON a.id_usuario_responsavel = c.id_usuario INNER JOIN tb_pessoa d ON c.id_pessoa = d.id_pessoa INNER JOIN tb_usuario e ON a.id_usuario_acao = e.id_usuario INNER JOIN tb_pessoa f ON e.id_pessoa = f.id_pessoa WHERE id_chamado = '" . $dado['id_chamado'] . "' AND a.acao != 'criacao' ORDER BY a.data ASC", "b.descricao AS descricao_status, d.nome AS nome_responsavel, f.nome AS nome_usuario_acao, a.*");
					}

					if ($chamados_acao) {
					?>

						<div class="panel panel-warning">
							<div class="panel-heading">
								<strong>Ações do Chamado:</strong>
							</div>
							<div class="panel-body">
								<?php

								$contador = 1;
								foreach ($chamados_acao as $conteudo) :

									if ($conteudo['acao'] == "criacao") {
										$acao = "Criação do chamado";
										$icone = "<i class='fa fa-tag' aria-hidden='true'></i>";

										$css = 'border-left: 5px solid #265a88 !important;';
									} else if ($conteudo['acao'] == "encerrar") {
										$acao = "Chamado encerrado";
										if ($conteudo['id_chamado_status'] == 3) {
											$icone = "<i class='fa fa-check' aria-hidden='true'></i>";

											$css = 'border-left: 5px solid #59ba1f !important;';
										}
										if ($conteudo['id_chamado_status'] == 4) {
											$icone = "<i class='fa fa-check' aria-hidden='true'></i>";

											$css = 'border-left: 5px solid #ba1f1f !important;';
										}
									} else if ($conteudo['acao'] == "encaminhar") {
										$acao = "Troca de responsável";
										$icone = "<i class='fa fa-exchange' aria-hidden='true'></i>";

										$css = 'border-left: 5px solid #FFC125 !important;';
									} else if ($conteudo['acao'] == "nota_geral") {
										$acao = "Nota adicionada";
										$icone = "<i class='fa fa-file' aria-hidden='true'></i>";

										$css = 'border-left: 5px solid #5bc0de !important;';
									} else if ($conteudo['acao'] == "nota_interna") {
										$acao = "Nota interna adicionada";
										$icone = "<i class='fa fa-file' aria-hidden='true'></i>";

										$css = 'border-left: 5px solid #363636 !important;';
									} else if ($conteudo['acao'] == "desbloquear") {
										$acao = "Chamado desbloqueado";
										$icone = "<i class='fa fa-unlock' aria-hidden='true'></i>";

										$css = 'border-left: 5px solid #DF7401 !important;';
									} else if ($conteudo['acao'] == "bloquear") {
										$acao = "Chamado bloqueado";
										$icone = "<i class='fa fa-lock' aria-hidden='true'></i>";

										$css = 'border-left: 5px solid #DF7401 !important;';
									} else if ($conteudo['acao'] == "reabrir") {
										$acao = "Chamado reaberto";
										$icone = "<i class='fa fa-undo' aria-hidden='true'></i>";

										$css = 'border-left: 5px solid #265a88 !important;';
									} else if ($conteudo['acao'] == "gerenciar") {
										$acao = "Gerenciamento dos envolvidos";
										$icone = "<i class='fa fa-cog' aria-hidden='true'></i>";

										$css = 'border-left: 5px solid #20B2AA !important;';
									} else if ($conteudo['acao'] == "alterar") {
										$acao = "Alteração do chamado";
										$icone = "<i class='fa fa-edit' aria-hidden='true'></i>";

										$css = 'border-left: 5px solid #9370DB
									!important;';
									} else if ($conteudo['acao'] == "assumir") {
										$acao = "Assumiu responsabilidade";
										$icone = "<i class='fa fa-exchange' aria-hidden='true'></i>";

										$css = 'border-left: 5px solid #FFC125 !important;';
									} else if ($conteudo['acao'] == "pendencia") {
										$data_pendencia =  DBRead('', 'tb_chamado_pendencia', "WHERE id_chamado_acao = '" . $conteudo['id_chamado_acao'] . "'");

										$acao = "Adicionada uma pendência (" . converteDataHora($data_pendencia[0]['data']) . ")";
										$icone = "<i class='fa fa-calendar-minus-o' aria-hidden='true'></i>";

										$css = 'border-left: 5px solid #EE8262 !important;';
									}
									echo '<span class="timeline-title" style="font-size: 15px; "><strong> ' . $contador . '</strong> - <strong>' . $acao . '</strong>  ' . $icone . ' </span>';

								?>
									<div class="panel panel" style="<?= $css ?>">

										<div class="panel-body" style="border: solid 1px #d9d9d9;">
											<div class="row">
												<div class="col-md-12" style="margin-left: -5px;">
													<table class="table table-hover">
														<tbody>
															<tr>
																<td class="td-table col-md-3" style="border-top: 0 !important"><strong>Ação realizada por:</strong> <?= $conteudo['nome_usuario_acao'] ?></td>

																<td class="td-table col-md-3" style="border-top: 0 !important"><strong>Status:</strong> <?= $conteudo['descricao_status'] ?></td>

																<?php
																$visibilidade = array(
																	"1" => "Público",
																	"2" => "Privado"
																);
																?>
																<td class="td-table col-md-3" style="border-top: 0 !important"><strong>Visibilidade:</strong> <?= $visibilidade[$dado['visibilidade']] ?></td>
															</tr>
														</tbody>
													</table>
												</div><!-- end col -->
												<div class="col-md-12" style="margin-left: -5px;">
													<table class="table table-hover">
														<tbody>
															<tr>
																<td class="td-table col-md-3" style="border-top: 0 !important"><strong>Responsável:</strong> <?= $conteudo['nome_responsavel'] ?></td>

																<td class="td-table col-md-3" style="border-top: 0 !important"><strong>Data:</strong> <?= converteDataHora($conteudo['data']) ?></td>
																<?php
																$tempo = explode(':', converteSegundosHoras($conteudo['tempo'] * 60));
																$horas = intval($tempo[0]);
																$minutos = intval($tempo[1]);

																if ($horas < '1') {
																	$tempo_total = $minutos . " m";
																} else {
																	$tempo_total = $horas . " h " . $minutos . " m";
																}
																?>
																<td class="td-table col-md-3" style="border-top: 0 !important"><strong>Tempo da ação:</strong> <?= $tempo_total ?></td>
															</tr>
														</tbody>
													</table>
												</div><!-- end col -->
												<div class="col-md-12" style="margin-left: -5px;">
													<table class="table table-hover">
														<tbody>
															<tr>
																<td class="td-table col-md-3 conteudo-editor"><strong>Descrição: </strong> <?= $conteudo['descricao'] ?></td>


															</tr>
														</tbody>
													</table>
												</div><!-- end col -->
											</div>
										</div>
									</div>
									</li>
								<?php
									$contador++;
								endforeach;

								?>

							</div>
						</div>
					<?php  } ?>
				</div>
			</div>

			<?php
		}
	} else {
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

function relatorio_recorrencia_telefone($mes_inicio, $mes_fim, $usuario2, $lider_recorrencia, $mostrar_recorrencias, $classificacao){

	$mes_inicio_legenda = converteData($mes_inicio);
	$mes_inicio_legenda = substr($mes_inicio_legenda, 3);

	$mes_fim_legenda = converteData($mes_fim);
	$mes_fim_legenda = substr($mes_fim_legenda, 3);

	$data_hora = converteDataHora(getDataHora());

	if ($usuario2) {
		$dados_usuario = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_usuario = '$usuario2'", "b.nome");
		$nome_usuario = $dados_usuario[0]['nome'];

		$filtro_usuario = "AND id_usuario = '" . $usuario2 . "' ";
	} else {
		$nome_usuario = 'Todos';
	}

	if ($lider_recorrencia != '') {

		$dados_lider = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE id_usuario = '" . $lider_recorrencia . "'", "a.*, b.nome");
		$nome_lider = $dados_lider[0]['nome'];

		$legenda_lider = '<span style="font-size: 14px;"><strong>Líder direto:</strong> ' . $nome_lider . '</span>';

		$filtro_lider = "AND a.lider_direto = '$lider_recorrencia' ";
	} else {
		$legenda_lider = '<span style="font-size: 14px;"><strong>Líder direto:</strong> Todos</span>';
	}

	if ($mostrar_recorrencias) {
		$legenda_mostrar_ocorrencias = '<span style="font-size: 14px;"><strong>Exibição:</strong> Somente quesitos reprovados</span>';

	} else {
		$legenda_mostrar_ocorrencias = '<span style="font-size: 14px;"><strong>Exibição:</strong> Todos quesitos</span>';
	}

	if ($classificacao == 1) {
		$legenda_classificacao = '(via Telefone - Em treinamento)';

	} else if ($classificacao == 2) {
		$legenda_classificacao = '(via Telefone - Período de Experiência)';

	} else if ($classificacao == 3) {
		$legenda_classificacao = '(via Telefone - Efetivado)';
	}

	$nome_usuario_legend = '<span style="font-size: 14px;"><strong>Atendente:</strong> ' . $nome_usuario . '</span>';
	$legenda_mes_referencia = '<span style="font-size: 14px;"><strong>Mês inicio:</strong> ' . $mes_inicio_legenda . ' - <strong>Mês fim:</strong> ' . $mes_fim_legenda . ' </span>';

	echo "<div class=\"col-md-12\" style=\"padding: 0\">";
	echo "<legend style=\"text-align:center;\"><strong>Relatório de Recorrência $legenda_classificacao</strong><br><span style=\"font-size: 14px;\"><strong>Gerado em:</strong> $data_hora</span></legend>";
	echo "<legend style=\"text-align:center;\">$legenda_mes_referencia</legend>";
	echo "<legend style=\"text-align:center;\">$nome_usuario_legend</legend>";
	echo "<legend style=\"text-align:center;\">$legenda_lider</legend>";
	echo "<legend style=\"text-align:center;\">$legenda_mostrar_ocorrencias</legend><br>";

	$dados_formulario = DBRead('', 'tb_monitoria_mes', "WHERE data_referencia >= '$mes_inicio' AND data_referencia <= '$mes_fim' AND tipo_monitoria = 1 AND classificacao_atendente = $classificacao", 'id_monitoria_mes, data_referencia');

	$dados_usuarios = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_perfil_sistema = 3 AND a.status = 1 $filtro_usuario $filtro_lider ORDER BY b.nome ASC", 'a.id_usuario, b.nome');
	
	if ($dados_usuarios) {

		$verifica = 0;
		$cont_resultado = 0;
		foreach ($dados_usuarios as $conteudo_usuarios) {

			$usuario = $conteudo_usuarios['id_usuario'];

			$array_recorrencia = array();
			$array_quesitos = array();

			foreach ($dados_formulario as $conteudo) {

				$data_referencia = $conteudo['data_referencia'];
				$id_monitoria_mes = $conteudo['id_monitoria_mes'];

				$dados_monitoria = DBRead('', 'tb_monitoria_avaliacao_audio a', "INNER JOIN tb_subarea_problema b ON a.id_subarea_problema = b.id_subarea_problema INNER JOIN tb_area_problema c ON b.id_area_problema = c.id_area_problema WHERE id_usuario_atendente = '$usuario' AND a.id_monitoria_mes = $id_monitoria_mes AND a.considerar = 1 ORDER BY data_monitoria DESC");

				$resultado_monitoria = DBRead('', 'tb_monitoria_resultado', "WHERE id_monitoria_mes = $id_monitoria_mes AND id_usuario = '$usuario' ");

				if ($dados_monitoria) {

					$cont_resultado++;
					$array_pontos = array();
					$soma_total_percentual = array();

					foreach ($dados_monitoria as $conteudo) {

						$id_monitoria_avaliacao_audio = $conteudo['id_monitoria_avaliacao_audio'];
						$id_usuario_analista = $conteudo['id_usuario_analista'];

						$analista = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_usuario = '$id_usuario_analista' ");

						$id_ligacao = $conteudo['id_ligacao'];
						$observacao = $conteudo['obs_avaliacao'];
						$id_erro = $conteudo['id_erro'];

						$dados_monitoria_mes = DBRead('', 'tb_monitoria_avaliacao_audio a', "INNER JOIN tb_monitoria_avaliacao_audio_mes b ON a.id_monitoria_avaliacao_audio = b.id_monitoria_avaliacao_audio INNER JOIN tb_monitoria_mes_quesito c ON b.id_monitoria_mes_quesito = c.id_monitoria_mes_quesito INNER JOIN tb_monitoria_quesito d ON c.id_monitoria_quesito = d.id_monitoria_quesito INNER JOIN tb_usuario e ON a.id_usuario_analista = e.id_usuario INNER JOIN tb_pessoa f ON e.id_pessoa = f.id_pessoa INNER JOIN tb_monitoria_mes g ON g.id_monitoria_mes = c.id_monitoria_mes WHERE a.id_monitoria_mes = $id_monitoria_mes AND a.id_usuario_atendente = '$usuario' AND a.id_ligacao = '$id_ligacao' AND g.status = 1 AND a.considerar = 1", 'a.nome_contato, a.id_erro, a.obs_avaliacao, a.total_pontos, c.pontos_valor, c.porcentagem_plano_acao, b.pontos, d.descricao, d.plano_acao,f.nome as nome_analista, g.soma_total_pontos_quesitos, d.id_monitoria_quesito');

						if ($dados_monitoria_mes) {

							$total_pontos_geral = 0;
							$total_pontos_feitos = 0;

							foreach ($dados_monitoria_mes as $conteudo_monitoria) {

								$array_quesitos[$conteudo_monitoria['id_monitoria_quesito']]['descricao'] = $conteudo_monitoria['descricao'];

								$array_pontos[$conteudo_monitoria['id_monitoria_quesito']]['pontos'] += $conteudo_monitoria['pontos'];

								$array_pontos[$conteudo_monitoria['id_monitoria_quesito']]['qtd'] += 1;

								$array_pontos[$conteudo_monitoria['id_monitoria_quesito']]['pontos_valor'] = $conteudo_monitoria['pontos_valor'];

								$array_pontos[$conteudo_monitoria['id_monitoria_quesito']]['descricao'] = $conteudo_monitoria['descricao'];

								$array_pontos[$conteudo_monitoria['id_monitoria_quesito']]['plano_acao'] = $conteudo_monitoria['plano_acao'];

								$array_pontos[$conteudo_monitoria['id_monitoria_quesito']]['porcentagem_plano_acao'] = $conteudo_monitoria['porcentagem_plano_acao'];

								$observacao = $conteudo_monitoria['obs_avaliacao'];

								$analista = $conteudo_monitoria['nome_analista'];
								$contato = $conteudo_monitoria['nome_contato'];
								$total_pontos = $conteudo_monitoria['pontos_valor'];
								$pontos = $conteudo_monitoria['pontos'];

								$total_pontos_geral += $conteudo_monitoria['pontos_valor'];
								$total_pontos_feitos += $conteudo_monitoria['pontos'];

								$resultado = ($pontos * 100) / $total_pontos;
								$resultado = number_format($resultado, 2, '.', ' ');
							}

							$resultado_avalicao = ($total_pontos_feitos * 100) / $total_pontos_geral;

							$soma_total_percentual['resultado'] += $resultado_avalicao;
							$soma_total_percentual['qtd'] += 1;
						}
					} //end foreach

					if ($soma_total_percentual['qtd'] > 0) {
						$geral = $soma_total_percentual['resultado'] / $soma_total_percentual['qtd'];
					} else {
						$geral = 0;
					}
					
					foreach ($array_pontos as $key => $conteudo_resultados) {

						$quesito = DBRead('', 'tb_monitoria_mes a', "INNER JOIN tb_monitoria_mes_quesito b ON a.id_monitoria_mes = b.id_monitoria_mes INNER JOIN tb_monitoria_quesito c ON b.id_monitoria_quesito = c.id_monitoria_quesito WHERE c.id_monitoria_quesito = '$key' AND a.id_monitoria_mes = $id_monitoria_mes AND a.status = 1", "b.porcentagem_plano_acao, c.descricao, c.plano_acao");

						$media = $conteudo_resultados['pontos'] / $conteudo_resultados['qtd'];
						$resultado_geral = ($media * 100) / $conteudo_resultados['pontos_valor'];

						$porcentagem = $conteudo_resultados['porcentagem_plano_acao'];

						$array_recorrencia[$data_referencia]['resultado_geral_monitoria'] = $resultado_monitoria[0]['resultado'];
						$array_recorrencia[$data_referencia][$key]['resultado'] = $resultado_geral;
						$array_recorrencia[$data_referencia][$key]['resultado_geral'] = $resultado_monitoria[0]['resultado'];

						if ($porcentagem >= $resultado_geral) {
							$txt_plano_acao = $conteudo_resultados['plano_acao'];
							$array_recorrencia[$data_referencia][$key]['passou'] = 'N';
						} else {
							$array_recorrencia[$data_referencia][$key]['passou'] = 'S';
							$txt_plano_acao = '';
						}
					}
				}
			}

			if ($array_recorrencia) {	
			
				$flag_atendente = 0;
				$array_cont = array();
				$cont_recorrencia = 0;

				foreach ($array_quesitos as $id_quesito => $conteudo_quesito) {

					$flag = 1;

					foreach ($array_recorrencia as $mes => $conteudo_mes) {
						if ($conteudo_mes[$id_quesito]['passou'] == 'S' || !$conteudo_mes[$id_quesito]['passou']) {
							$flag = 0;
						}

						if ($conteudo_mes[$id_quesito]['passou'] == 'N') {
							$array_cont[$id_quesito] += 1;
						}
					}
					if ($flag) {
						$flag_atendente = 1;
					}
				}

				foreach ($array_cont as $id_quesito) {
					if ($id_quesito >= 2) {
						$cont_recorrencia = 1;
					}
				}
			
				if (($flag_atendente || !$mostrar_recorrencias) && $cont_recorrencia > 0) {
					?>
					<div class="row">
						<div class="col-md-4">
						</div>
						<div class="col-md-4 text-center" style="padding: 5px 5px 5px 5px;">
							<span style="font-size: 16px;">
								Atendente: <strong><?= $conteudo_usuarios['nome'] ?></strong>
							</span>
						</div>
						<div class="col-md-4">
						</div>
					</div>

					<!-- table resultados -->
					<div class="row">
						<div class="col-md-12" style="padding: 0px 15px 15px 15px;">
							<table class="table table-striped">
								<thead>
									<tr style="background-color: #BDBDBD;">
										<th></th>
										<?php

										foreach ($array_recorrencia as $mes => $conteudo) {

											$mes_referencia = converteData($mes);
											$mes_referencia = substr($mes_referencia, 3);
										?>

											<th class="mes_referencia text-center"><?= $mes_referencia ?></th>
										<?php } ?>
									</tr>
								</thead>
								<tbody>
									<?php
									foreach ($array_quesitos as $id_quesito => $conteudo_quesito) {

										$flag = 0;

										foreach ($array_recorrencia as $mes => $conteudo_mes) {
											if ($conteudo_mes[$id_quesito]['passou'] == 'S' || !$conteudo_mes[$id_quesito]['passou']) {
												$flag = 1;
											}
										}

										if ($flag != 1 || !$mostrar_recorrencias) {
									?>
											<tr>
												<td class="col-md-10 quesito"><?= $conteudo_quesito['descricao'] ?></td>
												<?php
												foreach ($array_recorrencia as $mes => $conteudo_mes) {

													$resultado = $conteudo_mes[$id_quesito]['resultado'];
													if ($conteudo_mes[$id_quesito] && $conteudo_mes[$id_quesito]['passou'] == 'S') {

														echo '<td class="success text-center" style="border-right: solid 1px #BDBDBD; border-left: solid 1px #BDBDBD; border-bottom: solid 1px #BDBDBD;">' . number_format($resultado, 2, '.', ' ') . '%</td>';
													} else if ($conteudo_mes[$id_quesito] && $conteudo_mes[$id_quesito]['passou'] == 'N') {

														echo '<td class="danger text-center" style="border-right: solid 1px #BDBDBD; border-left: solid 1px #BDBDBD; border-bottom: solid 1px #BDBDBD;">' . number_format($resultado, 2, '.', ' ') . '%</td>';
													} else {

														echo '<td class="warning text-center" style="border-right: solid 1px #BDBDBD; border-left: solid 1px #BDBDBD; border-bottom: solid 1px #BDBDBD;">Não avaliado!</td>';
													}
												}
												?>
											</tr>
									<?php
										}
									}

									?>
								</tbody>
								<tfoot>
									<tr style="border-bottom: solid 1px #D8D8D8;">
										<td class="info"><strong>Resultado Geral da monitoria</strong></td>

										<?php
										foreach ($array_recorrencia as $mes => $conteudo_mes) {

											$resultado_geral = $array_recorrencia[$mes]['resultado_geral_monitoria'];

											echo '<td class="info text-center" style="border-right: solid 1px #BDBDBD; border-left: solid 1px #BDBDBD; border-bottom: solid 1px #BDBDBD;"><strong>' . number_format($resultado_geral, 2, '.', ' ') . '%</strong></td>';
										}

										?>
									</tr>
								</tfoot>
							</table>
							<hr>
						</div>
					</div>
					<!-- end table resultados -->

				<?php
				} else if ($cont_recorrencia == 0 && $usuario2) {
					echo '<div style="text-align: center"><h4>Não foram encontrados resultados!</h4></div>';
				} else {
					$verifica = 1;
				}
			}
		}

		if ($cont_resultado == 0 || $verifica > 0) {
			echo '<div style="text-align: center"><h4>Não foram encontrados resultados de recorrência!</h4></div>';
		}
	} else {
		echo '<div style="text-align: center"><h4>Não foram encontrados resultados!</h4></div>';
	}
}

function relatorio_recorrencia_texto($mes_inicio, $mes_fim, $usuario2, $lider_recorrencia, $mostrar_recorrencias, $classificacao){

	$mes_inicio_legenda = converteData($mes_inicio);
	$mes_inicio_legenda = substr($mes_inicio_legenda, 3);

	$mes_fim_legenda = converteData($mes_fim);
	$mes_fim_legenda = substr($mes_fim_legenda, 3);

	$data_hora = converteDataHora(getDataHora());

	if ($usuario2) {
		$dados_usuario = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_usuario = '$usuario2'", "b.nome");
		$nome_usuario = $dados_usuario[0]['nome'];

		$filtro_usuario = "AND id_usuario = '" . $usuario2 . "' ";
	} else {
		$nome_usuario = 'Todos';
	}

	if ($lider_recorrencia != '') {

		$dados_lider = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE id_usuario = '" . $lider_recorrencia . "'", "a.*, b.nome");
		$nome_lider = $dados_lider[0]['nome'];

		$legenda_lider = '<span style="font-size: 14px;"><strong>Líder direto:</strong> ' . $nome_lider . '</span>';

		$filtro_lider = "AND a.lider_direto = '$lider_recorrencia' ";
	} else {
		$legenda_lider = '<span style="font-size: 14px;"><strong>Líder direto:</strong> Todos</span>';
	}

	if ($mostrar_recorrencias) {
		$legenda_mostrar_ocorrencias = '<span style="font-size: 14px;"><strong>Exibição:</strong> Somente quesitos reprovados</span>';

	} else {
		$legenda_mostrar_ocorrencias = '<span style="font-size: 14px;"><strong>Exibição:</strong> Todos quesitos</span>';
	}

	if ($classificacao == 1) {
		$legenda_classificacao = '(via Texto - Em treinamento)';

	} else if ($classificacao == 2) {
		$legenda_classificacao = '(via Texto - Período de Experiência)';

	} else if ($classificacao == 3) {
		$legenda_classificacao = '(via Texto - Efetivado)';
	}

	$nome_usuario_legend = '<span style="font-size: 14px;"><strong>Atendente:</strong> ' . $nome_usuario . '</span>';
	$legenda_mes_referencia = '<span style="font-size: 14px;"><strong>Mês inicio:</strong> ' . $mes_inicio_legenda . ' - <strong>Mês fim:</strong> ' . $mes_fim_legenda . ' </span>';

	echo "<div class=\"col-md-12\" style=\"padding: 0\">";
	echo "<legend style=\"text-align:center;\"><strong>Relatório de Recorrência $legenda_classificacao</strong><br><span style=\"font-size: 14px;\"><strong>Gerado em:</strong> $data_hora</span></legend>";
	echo "<legend style=\"text-align:center;\">$legenda_mes_referencia</legend>";
	echo "<legend style=\"text-align:center;\">$nome_usuario_legend</legend>";
	echo "<legend style=\"text-align:center;\">$legenda_lider</legend>";
	echo "<legend style=\"text-align:center;\">$legenda_mostrar_ocorrencias</legend><br>";

	$dados_formulario = DBRead('', 'tb_monitoria_mes', "WHERE data_referencia >= '$mes_inicio' AND data_referencia <= '$mes_fim' AND tipo_monitoria = 2 AND classificacao_atendente = $classificacao", 'id_monitoria_mes, data_referencia');

	$dados_usuarios = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE (a.id_perfil_sistema = 3) AND a.status = 1 $filtro_usuario $filtro_lider ORDER BY b.nome ASC", 'a.id_usuario, b.nome');
	
	if ($dados_usuarios) {

		$verifica = 0;
		$cont_resultado = 0;
		foreach ($dados_usuarios as $conteudo_usuarios) {

			$usuario = $conteudo_usuarios['id_usuario'];

			$array_recorrencia = array();
			$array_quesitos = array();

			foreach ($dados_formulario as $conteudo) {

				$data_referencia = $conteudo['data_referencia'];
				$id_monitoria_mes = $conteudo['id_monitoria_mes'];

				$dados_monitoria = DBRead('', 'tb_monitoria_avaliacao_texto a', "WHERE id_usuario_atendente = '$usuario' AND a.considerar = 1 AND a.id_monitoria_mes = $id_monitoria_mes ORDER BY data_monitoria DESC");

				$resultado_monitoria = DBRead('', 'tb_monitoria_resultado', "WHERE id_monitoria_mes = $id_monitoria_mes AND id_usuario = '$usuario' ");

				if ($dados_monitoria) {

					$cont_resultado++;
					$array_pontos = array();
					$soma_total_percentual = array();

					foreach ($dados_monitoria as $conteudo) {

						$id_monitoria_avaliacao_texto = $conteudo['id_monitoria_avaliacao_texto'];
						$id_usuario_analista = $conteudo['id_usuario_analista'];

						$analista = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_usuario = '$id_usuario_analista' ");

						$id_atendimento = $conteudo['id_atendimento'];
						$observacao = $conteudo['obs_avaliacao'];
						$id_erro = $conteudo['id_erro'];

						$dados_monitoria_mes = DBRead('', 'tb_monitoria_avaliacao_texto a', "INNER JOIN tb_monitoria_avaliacao_texto_mes b ON a.id_monitoria_avaliacao_texto = b.id_monitoria_avaliacao_texto INNER JOIN tb_monitoria_mes_quesito c ON b.id_monitoria_mes_quesito = c.id_monitoria_mes_quesito INNER JOIN tb_monitoria_quesito d ON c.id_monitoria_quesito = d.id_monitoria_quesito INNER JOIN tb_usuario e ON a.id_usuario_analista = e.id_usuario INNER JOIN tb_pessoa f ON e.id_pessoa = f.id_pessoa INNER JOIN tb_monitoria_mes g ON g.id_monitoria_mes = c.id_monitoria_mes WHERE a.id_monitoria_mes = $id_monitoria_mes AND a.id_usuario_atendente = '$usuario' AND a.id_atendimento = '$id_atendimento' AND g.status = 1 AND a.considerar = 1", 'a.id_erro, a.obs_avaliacao, a.total_pontos, c.pontos_valor, c.porcentagem_plano_acao, b.pontos, d.descricao, d.plano_acao,f.nome as nome_analista, g.soma_total_pontos_quesitos, d.id_monitoria_quesito');

						if ($dados_monitoria_mes) {

							$total_pontos_geral = 0;
							$total_pontos_feitos = 0;

							foreach ($dados_monitoria_mes as $conteudo_monitoria) {

								$array_quesitos[$conteudo_monitoria['id_monitoria_quesito']]['descricao'] = $conteudo_monitoria['descricao'];

								$array_pontos[$conteudo_monitoria['id_monitoria_quesito']]['pontos'] += $conteudo_monitoria['pontos'];

								$array_pontos[$conteudo_monitoria['id_monitoria_quesito']]['qtd'] += 1;

								$array_pontos[$conteudo_monitoria['id_monitoria_quesito']]['pontos_valor'] = $conteudo_monitoria['pontos_valor'];

								$array_pontos[$conteudo_monitoria['id_monitoria_quesito']]['descricao'] = $conteudo_monitoria['descricao'];

								$array_pontos[$conteudo_monitoria['id_monitoria_quesito']]['plano_acao'] = $conteudo_monitoria['plano_acao'];

								$array_pontos[$conteudo_monitoria['id_monitoria_quesito']]['porcentagem_plano_acao'] = $conteudo_monitoria['porcentagem_plano_acao'];

								$observacao = $conteudo_monitoria['obs_avaliacao'];

								$analista = $conteudo_monitoria['nome_analista'];
								$contato = $conteudo_monitoria['nome_contato'];
								$total_pontos = $conteudo_monitoria['pontos_valor'];
								$pontos = $conteudo_monitoria['pontos'];

								$total_pontos_geral += $conteudo_monitoria['pontos_valor'];
								$total_pontos_feitos += $conteudo_monitoria['pontos'];

								$resultado = ($pontos * 100) / $total_pontos;
								$resultado = number_format($resultado, 2, '.', ' ');
							}

							$resultado_avalicao = ($total_pontos_feitos * 100) / $total_pontos_geral;

							$soma_total_percentual['resultado'] += $resultado_avalicao;
							$soma_total_percentual['qtd'] += 1;
						}
					} //end foreach

					if ($soma_total_percentual['qtd'] > 0) {
						$geral = $soma_total_percentual['resultado'] / $soma_total_percentual['qtd'];
					} else {
						$geral = 0;
					}
	
					foreach ($array_pontos as $key => $conteudo_resultados) {

						$quesito = DBRead('', 'tb_monitoria_mes a', "INNER JOIN tb_monitoria_mes_quesito b ON a.id_monitoria_mes = b.id_monitoria_mes INNER JOIN tb_monitoria_quesito c ON b.id_monitoria_quesito = c.id_monitoria_quesito WHERE c.id_monitoria_quesito = '$key' AND a.id_monitoria_mes = $id_monitoria_mes AND a.status = 1", "b.porcentagem_plano_acao, c.descricao, c.plano_acao");

						$media = $conteudo_resultados['pontos'] / $conteudo_resultados['qtd'];
						$resultado_geral = ($media * 100) / $conteudo_resultados['pontos_valor'];

						$porcentagem = $conteudo_resultados['porcentagem_plano_acao'];

						$array_recorrencia[$data_referencia]['resultado_geral_monitoria'] = $resultado_monitoria[0]['resultado'];
						$array_recorrencia[$data_referencia][$key]['resultado'] = $resultado_geral;
						$array_recorrencia[$data_referencia][$key]['resultado_geral'] = $resultado_monitoria[0]['resultado'];

						if ($porcentagem >= $resultado_geral) {
							$txt_plano_acao = $conteudo_resultados['plano_acao'];
							$array_recorrencia[$data_referencia][$key]['passou'] = 'N';
						} else {
							$array_recorrencia[$data_referencia][$key]['passou'] = 'S';
							$txt_plano_acao = '';
						}
					}
				}
			}
			if ($array_recorrencia) {	
				
				$flag_atendente = 0;
				$array_cont = array();
				$cont_recorrencia = 0;

				foreach ($array_quesitos as $id_quesito => $conteudo_quesito) {

					$flag = 1;

					foreach ($array_recorrencia as $mes => $conteudo_mes) {
						if ($conteudo_mes[$id_quesito]['passou'] == 'S' || !$conteudo_mes[$id_quesito]['passou']) {
							$flag = 0;
						}

						if ($conteudo_mes[$id_quesito]['passou'] == 'N') {
							$array_cont[$id_quesito] += 1;
						}
					}
					if ($flag) {
						$flag_atendente = 1;
					}
				}

				foreach ($array_cont as $id_quesito) {
					if ($id_quesito >= 2) {
						$cont_recorrencia = 1;
					}
				}

				if (($flag_atendente || !$mostrar_recorrencias) && $cont_recorrencia > 0) {

					?>
					<div class="row">
						<div class="col-md-4">
						</div>
						<div class="col-md-4 text-center" style="padding: 5px 5px 5px 5px;">
							<span style="font-size: 16px;">
								Atendente: <strong><?= $conteudo_usuarios['nome'] ?></strong>
							</span>
						</div>
						<div class="col-md-4">
						</div>
					</div>

					<!-- table resultados -->
					<div class="row">
						<div class="col-md-12" style="padding: 0px 15px 15px 15px;">
							<table class="table table-striped">
								<thead>
									<tr style="background-color: #BDBDBD;">
										<th></th>
										<?php

										foreach ($array_recorrencia as $mes => $conteudo) {

											$mes_referencia = converteData($mes);
											$mes_referencia = substr($mes_referencia, 3);
										?>

											<th class="mes_referencia text-center"><?= $mes_referencia ?></th>
										<?php } ?>
									</tr>
								</thead>
								<tbody>
									<?php
									foreach ($array_quesitos as $id_quesito => $conteudo_quesito) {

										$flag = 0;

										foreach ($array_recorrencia as $mes => $conteudo_mes) {
											if ($conteudo_mes[$id_quesito]['passou'] == 'S' || !$conteudo_mes[$id_quesito]['passou']) {
												$flag = 1;
											}
										}

										if ($flag != 1 || !$mostrar_recorrencias) {
									?>
											<tr>
												<td class="col-md-10 quesito"><?= $conteudo_quesito['descricao'] ?></td>
												<?php
												foreach ($array_recorrencia as $mes => $conteudo_mes) {

													$resultado = $conteudo_mes[$id_quesito]['resultado'];
													if ($conteudo_mes[$id_quesito] && $conteudo_mes[$id_quesito]['passou'] == 'S') {

														echo '<td class="success text-center" style="border-right: solid 1px #BDBDBD; border-left: solid 1px #BDBDBD; border-bottom: solid 1px #BDBDBD;">' . number_format($resultado, 2, '.', ' ') . '%</td>';
													} else if ($conteudo_mes[$id_quesito] && $conteudo_mes[$id_quesito]['passou'] == 'N') {

														echo '<td class="danger text-center" style="border-right: solid 1px #BDBDBD; border-left: solid 1px #BDBDBD; border-bottom: solid 1px #BDBDBD;">' . number_format($resultado, 2, '.', ' ') . '%</td>';
													} else {

														echo '<td class="warning text-center" style="border-right: solid 1px #BDBDBD; border-left: solid 1px #BDBDBD; border-bottom: solid 1px #BDBDBD;">Não avaliado!</td>';
													}
												}
												?>
											</tr>
									<?php
										}
									}

									?>
								</tbody>
								<tfoot>
									<tr style="border-bottom: solid 1px #D8D8D8;">
										<td class="info"><strong>Resultado Geral da monitoria</strong></td>

										<?php
										foreach ($array_recorrencia as $mes => $conteudo_mes) {

											$resultado_geral = $array_recorrencia[$mes]['resultado_geral_monitoria'];

											echo '<td class="info text-center" style="border-right: solid 1px #BDBDBD; border-left: solid 1px #BDBDBD; border-bottom: solid 1px #BDBDBD;"><strong>' . number_format($resultado_geral, 2, '.', ' ') . '%</strong></td>';
										}

										?>
									</tr>
								</tfoot>
							</table>
							<hr>
						</div>
					</div>
					<!-- end table resultados -->

				<?php
				} else if ($cont_recorrencia == 0 && $usuario2) {
					echo '<div style="text-align: center"><h4>Não foram encontrados resultados!</h4></div>';
				} else {
					$verifica = 1;
				}
			}
		}

		if ($cont_resultado == 0 || $verifica > 0) {
			echo '<div style="text-align: center"><h4>Não foram encontrados resultados de recorrência!</h4></div>';
		}
	} else {
		echo '<div style="text-align: center"><h4>Não foram encontrados resultados!</h4></div>';
	}
}

function relatorio_tma_atendente($mes, $ano, $classificacao)
{
	$data_hora = converteDataHora(getDataHora());

	$data_referencia = $ano . '-' . $mes . '-01';
	$legenda_mes_referencia = $mes . '/' . $ano;

	if ($classificacao == 1) {
		$legenda_classificacao = ' (Em Treinamento)';

	} else if ($classificacao == 2) {
		$legenda_classificacao = ' (Período de Experiência)';

	} else if ($classificacao == 3) {
		$legenda_classificacao = ' (Efetivado)';
	}

	echo "<div class=\"col-md-12\" style=\"padding: 0\">";
	echo "<legend style=\"text-align:center;\"><strong>TMA dos áudios considerados por atendente $legenda_classificacao</strong><br><span style=\"font-size: 14px;\"><strong>Gerado em:</strong> $data_hora</span></legend>";
	echo "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong>Mês referência:</strong> $legenda_mes_referencia</span></legend>";

	$dados = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_perfil_sistema = 3 AND a.status = 1 ORDER BY b.nome", "a.id_usuario, b.nome");

	$mes_atual = explode('/', $data_hora);

	if ($mes_atual[1] == $mes) {

		$dados = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa INNER JOIN tb_monitoria_classificacao_usuario c ON a.id_usuario = c.id_usuario WHERE a.id_perfil_sistema = 3 AND a.status = 1 AND c.tipo_classificacao = $classificacao ORDER BY b.nome", "a.id_usuario, b.nome");

	} else {

		$dados = DBRead('', 'tb_monitoria_avaliacao_audio a', "INNER JOIN tb_monitoria_mes b ON a.id_monitoria_mes = b.id_monitoria_mes INNER JOIN tb_usuario c ON a.id_usuario_atendente = c.id_usuario INNER JOIN tb_pessoa d ON c.id_pessoa = d.id_pessoa WHERE a.data_referencia = '$data_referencia' AND b.classificacao_atendente = $classificacao GROUP BY a.id_usuario_atendente ORDER BY d.nome ASC ", 'a.id_usuario_atendente as id_usuario, d.nome');
	}

	if ($dados) {

		$tempo_total_avaliacao = array();

		foreach ($dados as $conteudo) {

			$id_usuario = $conteudo['id_usuario'];
			$nome =  $conteudo['nome'];

			$dados_monitoria = DBRead('', 'tb_monitoria_avaliacao_audio a', "INNER JOIN tb_monitoria_mes b ON a.id_monitoria_mes = b.id_monitoria_mes WHERE id_usuario_atendente = '$id_usuario' AND a.data_referencia = '$data_referencia' AND a.considerar = 1 AND b.classificacao_atendente = $classificacao ORDER BY data_monitoria DESC");

			if ($dados_monitoria) {
				foreach ($dados_monitoria as $conteudo_monitoria) {
					$tempo_total_avaliacao[$nome]['tempo'] += $conteudo_monitoria['duracao_audio'];
					$tempo_total_avaliacao[$nome]['qtd'] += 1;
				}
			} else {
				$tempo_total_avaliacao[$nome]['tempo'] = 0;
				$tempo_total_avaliacao[$nome]['qtd'] = 0;
			}
		}

		echo "
			<table class=\"table table-striped dataTable\"> 
				<thead> 
					<tr> 
						<th>Atendente</th>
						<th class='text-center'>TMA dos áudios considerados</th>
					</tr>
				</thead> 
				<tbody>";

		foreach ($tempo_total_avaliacao as $key => $value) {

			if ($value['tempo'] != 0) {
				$style = 'style="font-weight: 600"';
			} else {
				$style = '';
			}

			if ($value['tempo'] > 0 && $value['qtd'] > 0) {
				$resultado = $value['tempo'] / $value['qtd'];
			} else {
				$resultado = 0;
			}

			echo '
					<tr>
						<td>' . $key . '</td>
						<td class="text-center" ' . $style . ' >' . gmdate("H:i:s", $resultado) . '</td>
					</tr>	
				';
		}

		echo "</tbody>";
		echo "</table><br>";

	} else {
		echo '<div style="text-align: center"><h4>Não foram encontrados resultados!</h4></div>';
	}
}

function relatorio_atendimento_encantador_telefone($data_de, $data_ate) 
{
	$fila = "'callATENDIMENTOvip','callATENDIMENTOalta','callATENDIMENTOnormal','callATENDIMENTObaixa','callATENDIMENTOnotificacaoparada', 'callATENDIMENTOvipEXT','callATENDIMENTOaltaEXT','callATENDIMENTOnormalEXT','callATENDIMENTObaixaEXT','callATENDIMENTOnotificacaoparadaEXT','callATENDIMENTOvipEXP','callATENDIMENTOaltaEXP','callATENDIMENTOnormalEXP','callATENDIMENTObaixaEXP','callATENDIMENTOnotificacaoparadaEXP'";

	$data_hora = converteDataHora(getDataHora());

	$periodo_amostra = "<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> de $data_de até $data_ate</span>";

	$dados_monitoria = DBRead('', 'tb_monitoria_avaliacao_audio a', "INNER JOIN tb_subarea_problema b ON a.id_subarea_problema = b.id_subarea_problema INNER JOIN tb_area_problema c ON b.id_area_problema = c.id_area_problema WHERE a.considerar = 1 AND a.elogio = 1 AND data_monitoria BETWEEN '".converteData($data_de)." 00:00:00' AND '".converteData($data_ate)." 23:59:59' ORDER BY data_monitoria DESC");

	echo "<div class=\"col-md-12\" style=\"padding: 0\">";
	echo "<legend style=\"text-align:center;\"><strong>Relatório de Monitoria - Atendimento encantador (via Telefone)</strong><br><span style=\"font-size: 14px;\"><strong>Gerado em:</strong> $data_hora</span></legend>";
	echo "<legend style=\"text-align:center;\">$periodo_amostra</legend>";

	if ($dados_monitoria) {

		foreach ($dados_monitoria as $conteudo) {

			$id_monitoria_avaliacao_audio = $conteudo['id_monitoria_avaliacao_audio'];
			$id_usuario_analista = $conteudo['id_usuario_analista'];
			$id_usuario_atendente = $conteudo['id_usuario_atendente'];

			$irritado = 'N/D';
			if ($conteudo['irritado'] == 1) {
				$irritado = 'Sim';
			}

			$analista = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_usuario = '$id_usuario_analista' ");

			$atendente = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_usuario = '$id_usuario_atendente' ");

			$id_ligacao = $conteudo['id_ligacao'];
			$observacao = $conteudo['obs_avaliacao'];

			$dados_ligacao = DBRead('snep', 'queue_log a', "INNER JOIN queue_log b ON (b.callid = a.callid AND b.event = 'CONNECT') INNER JOIN queue_log c ON (c.callid = a.callid AND (c.event = 'COMPLETEAGENT' OR c.event = 'COMPLETECALLER' OR c.event = 'BLINDTRANSFER' OR c.event = 'ATTENDEDTRANSFER')) LEFT JOIN pesquisa d ON  a.callid = d.UNIQUEID WHERE a.event = 'ENTERQUEUE' AND (c.data2 >= 30 OR c.data4 >= 30) AND a.callid = '$id_ligacao' GROUP BY b.id ORDER BY b.id LIMIT 1", "a.queuename AS 'enterqueue_queuename', a.callid AS 'enterqueue_callid', a.data2 AS 'enterqueue_data2', b.agent AS 'connect_agent', b.time AS 'connect_time', c.event AS 'finalizacao_event', c.data1 AS 'finalizacao_data1', c.data2 AS 'finalizacao_data2', c.data3 AS 'finalizacao_data3', c.data4 AS 'finalizacao_data4', d.nota AS 'nota', (SELECT SUM(data3) FROM queue_log e WHERE e.callid = a.callid AND e.event = 'EXITWITHTIMEOUT') AS 'tempo_espera_timeout'");

			if(preg_match("/'".$dados_ligacao[0]['enterqueue_queuename']."'/i", $fila) || !$fila){
				
				$id_asterisk = explode("-", $dados_ligacao[0]['enterqueue_data2']);

				$id_contrato_plano_pessoa = DBRead('', 'tb_parametros', "WHERE id_asterisk = '" . $id_asterisk[0] . "' ", 'id_contrato_plano_pessoa');

				$id_ligacao = $dados_ligacao[0]['enterqueue_callid'];

				$enterqueue_data2 = explode('-', $dados_ligacao[0]['enterqueue_data2']);

				$id_empresa_entrada = $enterqueue_data2[0];
				$dados_empresa = DBRead('snep', 'empresas', "WHERE id='$id_empresa_entrada'");

				if ($dados_empresa) {
					$nome_empresa = $dados_empresa[0]['nome'];
				} else {
					$nome_empresa = 'Não identificada';
				}

				if (is_numeric(end($enterqueue_data2)) && strlen(end($enterqueue_data2)) >= 10) {
					$bina = ltrim(end($enterqueue_data2), '0');
				} elseif (end($enterqueue_data2) == 'anonymous') {
					$bina = 'Nº anônimo';
				} else {
					$bina = 'Não identificado';
				}

				$info_chamada = array(
					'data_hora_atendida' => '',
					'nome_empresa' => $nome_empresa,
					'bina' => $bina,
					'tempo_atendimento' => '',
					'nota' => '',
					'gravacao' => '',
					'finalizacao' => '',
					'tempo_espera' => ''
				);

				$info_chamada['data_hora_atendida'] = date('d/m/Y H:i:s', strtotime($dados_ligacao[0]['connect_time']));

				$operador_chamada = explode('/', $dados_ligacao[0]['connect_agent']);
				$tipo_operador_chamada = $operador_chamada[0];
				$cod_operador_chamada = $operador_chamada[1];

				$dados_cdr = DBRead('snep', 'cdr', "WHERE uniqueid ='" . $dados_ligacao[0]['enterqueue_callid'] . "' LIMIT 1", "userfield");

				$arquivo_gravacao = explode(';', $dados_cdr[0]['userfield']);

				if (count($arquivo_gravacao) > 1) {

					$info_chamada['gravacao'] = 'http://pabx.bellunotec.com.br/snep/arquivos/' . date('Y-m-d', strtotime($dados_ligacao[0]['connect_time'])) . '/' . $arquivo_gravacao[1];
				} else {
					$info_chamada['gravacao'] = 'http://pabx.bellunotec.com.br/snep/arquivos/' . date('Y-m-d', strtotime($dados_ligacao[0]['connect_time'])) . '/' . $arquivo_gravacao[0];
				}

				$info_chamada['nota'] = $dados_ligacao[0]['nota'];

				if ($dados_ligacao[0]['finalizacao_event'] == 'COMPLETEAGENT' || $dados_ligacao[0]['finalizacao_event'] == 'COMPLETECALLER') {

					$info_chamada['tempo_espera'] = $dados_ligacao[0]['finalizacao_data1'];
					$info_chamada['tempo_atendimento'] = $dados_ligacao[0]['finalizacao_data2'];

					if ($dados_ligacao[0]['finalizacao_event'] == 'COMPLETEAGENT') {
						$info_chamada['finalizacao'] = 'Atendente';
					} else {
						$info_chamada['finalizacao'] = 'Cliente';
					}

				} elseif (($dados_ligacao[0]['finalizacao_event'] == 'BLINDTRANSFER' || $dados_ligacao[0]['finalizacao_event'] == 'ATTENDEDTRANSFER') && $dados_ligacao[0]['finalizacao_data1'] != 'LINK') {
					$info_chamada['tempo_atendimento'] = $dados_ligacao[0]['finalizacao_data4'];
					$info_chamada['tempo_espera'] = $dados_ligacao[0]['finalizacao_data3'];
					$info_chamada['finalizacao'] = 'Transferida';
				}
				if($dados_ligacao[0]['tempo_espera_timeout'] && $dados_ligacao[0]['tempo_espera_timeout'] > 0){
					$info_chamada['tempo_espera'] += $dados_ligacao[0]['tempo_espera_timeout'];
				}
			}
			
    ?>
			<!-- panel -->
			<div class="panel" style="border-color: #A9A9A9 !important;">
				<div class="panel-heading" style="background-color: #D3D3D3 !important;">

					<div class="row">
						<div class="col-md-3">
							<h3 class="panel-title">Data da monitoria: <strong><?= converteDataHora($conteudo['data_monitoria']) ?></strong>
							</h3>
						</div>
						<div class="col-md-3 text-center">
						</div>
						<div class="col-md-3 text-center">
						</div>
						<div class="col-md-3">
							<h3 class="panel-title pull-right"><span style="font-size: 15px;">Feita por: <strong><?= $analista[0]['nome'] ?></span></strong>
							</h3>
						</div>
					</div>

				</div>
				<div class="panel-body">

					<!-- table audio responsive -->
					<div class="table-responsive">
						<table class="table table-hover">
							<thead>
								<tr style="background-color: #f5f5f5">
									<th>Data ligação</th>
									<th>Empresa</th>
									<th>Atendente</th>
									<th>T. Atendimento</th>
									<th>T. Espera</th>
									<th>Nota</th>
									<th>Gravação</th>
									<th>Cliente irritado</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td><?= $info_chamada['data_hora_atendida'] ?></td>
									<td><?= $info_chamada['nome_empresa'] ?></td>
									<td><?= $atendente[0]['nome'] ?></td>
									<td><?= gmdate("H:i:s", $info_chamada['tempo_atendimento']) ?></td>
									<td><?= gmdate("H:i:s", $info_chamada['tempo_espera']) ?></td>
									<td><?= $info_chamada['nota'] ?></td>
									<td>
										<audio controls preload="none" class="audio_gravacao" style="width: 100%; min-width: 300px;">
											<source src="<?= $info_chamada['gravacao'] . '.mp3' ?>" type="audio/mp3">
											<source src="<?= $info_chamada['gravacao'] . '.wav' ?>" type="audio/wav">Seu navegador não aceita o player nativo.
										</audio>
									</td>
									<td><?= $irritado ?></td>
								</tr>
							</tbody>
						</table>
					</div>
					<!--end table audio responsive -->

				</div>
				<!--end panel body -->
			</div>
			<!--end panel -->

			<br>
		<?php

		} //end foreach

		?>
		<br>

	<?php

	} else {
		echo '<div style="text-align: center"><h4>Não foram encontrados resultados!</h4></div>';
	}

	echo "</div>";
}

function relatorio_atendimento_encantador_texto($data_de, $data_ate) 
{
	$data_hora = converteDataHora(getDataHora());

	$periodo_amostra = "<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> de $data_de até $data_ate</span>";
	
	$dados_monitoria = DBRead('', 'tb_monitoria_avaliacao_texto a', "INNER JOIN tb_usuario c ON a.id_usuario_atendente = c.id_usuario INNER JOIN tb_pessoa d ON c.id_pessoa = d.id_pessoa WHERE a.elogio = 1 AND a.data_monitoria BETWEEN '".converteData($data_de)." 00:00:00' AND '".converteData($data_ate)." 23:59:59' ORDER BY data_monitoria DESC");

	echo "<div class=\"col-md-12\" style=\"padding: 0\">";
	echo "<legend style=\"text-align:center;\"><strong>Relatório de Monitoria - Atendimento encantador (via Texto)</strong><br><span style=\"font-size: 14px;\"><strong>Gerado em:</strong> $data_hora</span></legend>";
	echo "<legend style=\"text-align:center;\">$periodo_amostra</legend>";

	if ($dados_monitoria) {

		foreach ($dados_monitoria as $conteudo) {

			$id_monitoria_avaliacao_texto = $conteudo['id_monitoria_avaliacao_texto'];
			$id_usuario_analista = $conteudo['id_usuario_analista'];
			$id_usuario_atendente = $conteudo['id_usuario_atendente'];

			$irritado = 'N/D';
			if ($conteudo['irritado'] == 1) {
				$irritado = 'Sim';
			}

			$analista = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_usuario = '$id_usuario_analista' ");

			$atendente = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_usuario = '$id_usuario_atendente' ");

			$id_atendimento = $conteudo['id_atendimento'];

			$dados_atendimento = DBRead('', 'tb_atendimento a', "INNER JOIN tb_situacao_atendimento b ON a.id_atendimento = b.id_atendimento INNER JOIN tb_situacao c ON b.id_situacao = c.id_situacao LEFT JOIN tb_subarea_problema_atendimento d ON a.id_atendimento = d.id_atendimento LEFT JOIN tb_subarea_problema e ON d.id_subarea_problema = e.id_subarea_problema LEFT JOIN tb_area_problema f ON e.id_area_problema = f.id_area_problema WHERE a.id_atendimento = $id_atendimento", "a.*, c.nome, e.descricao as descricao_subarea_problema, f.nome as descricao_area_problema");

			$conteudo_empresa = DBRead('', 'tb_contrato_plano_pessoa a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa INNER JOIN tb_plano c ON a.id_plano = c.id_plano WHERE a.id_contrato_plano_pessoa = '" . $dados_atendimento[0]['id_contrato_plano_pessoa'] . "'", "b.nome AS nome_empresa, a.*, c.*");

			if ($conteudo_empresa[0]['nome_contrato']) {
				$nome_contrato = " (" . $conteudo_empresa[0]['nome_contrato'] . ") ";
			} else {
				$nome_contrato = '';
			}
                
			$contrato = $conteudo_empresa[0]['nome_empresa'] . " " . $nome_contrato;

			$nome_empresa = $contrato;

			if ($dados_atendimento[0]['fone2']) {
				$legenda_telefone = $dados_atendimento[0]['fone1'].' | '.$dados_atendimento[0]['fone1'];
			} else {
				$legenda_telefone = $dados_atendimento[0]['fone1'];
			}

			$situacao_protocolo = DBRead('', 'tb_parametros', "WHERE id_contrato_plano_pessoa = '" . $dados_atendimento[0]['id_contrato_plano_pessoa'] . "'", "exibir_protocolo, solicitacao_cpf");

			if ($situacao_protocolo[0]['solicitacao_cpf'] == 1) {
				if ($dados_atendimento[0]['cpf_cnpj']) {
					$cpf_cnpj = $dados_atendimento[0]['cpf_cnpj'];
				}
			} else {
				$cpf_cnpj = ' - - - - - - - - - - -';
			}
			
			if ($dados_atendimento[0]['descricao_dado_adicional'] && $dados_atendimento[0]['dado_adicional']) {
				$dado_adicional = $dados_atendimento[0]['descricao_dado_adicional'].' - '. $dados_atendimento[0]['dado_adicional'];

			} else {
				$dado_adicional = ' - - - - - - - - - - -';
			}

			if ($situacao_protocolo[0]['exibir_protocolo'] == 1) {
				$protocolo =  $dados_atendimento[0]['protocolo'];
			} else {
				$protocolo = ' - - - - - - - - - - -';
			}

			if ($dados_atendimento[0]['descricao_subarea_problema']) {
				$sub_area_problema = $dados_atendimento[0]['descricao_subarea_problema'];

			} else {
				$sub_area_problema = ' - - - - - - - - - - -';
			}

			if ($dados_atendimento[0]['descricao_area_problema']) {
				$area_problema = $dados_atendimento[0]['descricao_area_problema'];

			} else {
				$area_problema = ' - - - - - - - - - - -';
			}

			$os = $dados_atendimento[0]['os']. PHP_EOL .$dados_atendimento[0]['nome'];

    ?>

			<!-- panel -->
			<div class="panel" style="border-color: #A9A9A9 !important;">
				<div class="panel-heading" style="background-color: #D3D3D3 !important;">

					<div class="row">
						<div class="col-md-3">
							<h3 class="panel-title">Data da monitoria: <strong><?= converteDataHora($conteudo['data_monitoria']) ?></strong>
							</h3>
						</div>
						<div class="col-md-3 text-center">
						</div>
						<div class="col-md-3 text-center">
						</div>
						<div class="col-md-3">
						<h3 class="panel-title pull-right"><span style="font-size: 15px;">Feita por: <strong><?= $analista[0]['nome'] ?></span></strong>
							</h3>
						</div>
					</div>

				</div>
				<div class="panel-body">

					<!-- table texto responsive -->
					<div class="table-responsive">
						<table class="table table-hover">
							<thead> 
								<tr>
								<th>Data</th>
								<th>Empresa</th>
								<th>Atendente</th>
								<th>Protocolo</th>
								<th>Assinante</th>
								<th>Contato</th>
								<th>Área do problema</th>
								<th>Subarea do problema</th>
								<th>Cliente irritado</th>
								</tr>
							</thead> 
							<tbody>
								<tr>
									<td><?=  converteDataHora($dados_atendimento[0]['data_inicio']) ?></td>
									<td><?= $nome_empresa ?></td>
									<td><?= $atendente[0]['nome'] ?></td>
									<td><?= $protocolo ?></td>
									<td><?= $dados_atendimento[0]['assinante'] ?></td>
									<td><?= $dados_atendimento[0]['contato'] ?></td>
									<td><?= $sub_area_problema ?></td>
									<td><?= $area_problema ?></td>
									<td><?= $irritado ?></td>
								</tr>
							</tbody>
						</table>
					</div>
					<!--end table texto responsive -->

					<br>

				</div>
				<!--end panel body -->
			</div>
			<!--end panel -->

			<br>
		<?php

		} //end foreach

	} else {
		echo '<div style="text-align: center"><h4>Não foram encontrados resultados!</h4></div>';
	}

	echo "</div>";
}

function relatorio_cliente_irritado_telefone($data_de, $data_ate) 
{

	$fila = "'callATENDIMENTOvip','callATENDIMENTOalta','callATENDIMENTOnormal','callATENDIMENTObaixa','callATENDIMENTOnotificacaoparada', 'callATENDIMENTOvipEXT','callATENDIMENTOaltaEXT','callATENDIMENTOnormalEXT','callATENDIMENTObaixaEXT','callATENDIMENTOnotificacaoparadaEXT','callATENDIMENTOvipEXP','callATENDIMENTOaltaEXP','callATENDIMENTOnormalEXP','callATENDIMENTObaixaEXP','callATENDIMENTOnotificacaoparadaEXP'";

	$data_hora = converteDataHora(getDataHora());

	$periodo_amostra = "<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> de $data_de até $data_ate</span>";
	
	$dados_monitoria = DBRead('', 'tb_monitoria_avaliacao_audio a', "INNER JOIN tb_subarea_problema b ON a.id_subarea_problema = b.id_subarea_problema INNER JOIN tb_area_problema c ON b.id_area_problema = c.id_area_problema WHERE a.considerar = 1 AND a.irritado = 1 AND a.data_monitoria BETWEEN '".converteData($data_de)." 00:00:00' AND '".converteData($data_ate)." 23:59:59' ORDER BY data_monitoria DESC");

	echo "<div class=\"col-md-12\" style=\"padding: 0\">";
	echo "<legend style=\"text-align:center;\"><strong>Relatório de Monitoria - Cliente irritado (via Telefone)</strong><br><span style=\"font-size: 14px;\"><strong>Gerado em:</strong> $data_hora</span></legend>";
	echo "<legend style=\"text-align:center;\">$periodo_amostra</legend>";

	if ($dados_monitoria) {

		foreach ($dados_monitoria as $conteudo) {

			$id_monitoria_avaliacao_audio = $conteudo['id_monitoria_avaliacao_audio'];
			$id_usuario_analista = $conteudo['id_usuario_analista'];
			$id_usuario_atendente = $conteudo['id_usuario_atendente'];

			$atendimento_encantador = 'N/D';
			if ($conteudo['elogio'] == 1) {
				$atendimento_encantador = 'Sim';
			}

			$analista = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_usuario = '$id_usuario_analista' ");

			$atendente = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_usuario = '$id_usuario_atendente' ");

			$id_ligacao = $conteudo['id_ligacao'];
			$observacao = $conteudo['obs_avaliacao'];

			$dados_ligacao = DBRead('snep', 'queue_log a', "INNER JOIN queue_log b ON (b.callid = a.callid AND b.event = 'CONNECT') INNER JOIN queue_log c ON (c.callid = a.callid AND (c.event = 'COMPLETEAGENT' OR c.event = 'COMPLETECALLER' OR c.event = 'BLINDTRANSFER' OR c.event = 'ATTENDEDTRANSFER')) LEFT JOIN pesquisa d ON  a.callid = d.UNIQUEID WHERE a.event = 'ENTERQUEUE' AND (c.data2 >= 30 OR c.data4 >= 30) AND a.callid = '$id_ligacao' GROUP BY b.id ORDER BY b.id LIMIT 1", "a.queuename AS 'enterqueue_queuename', a.callid AS 'enterqueue_callid', a.data2 AS 'enterqueue_data2', b.agent AS 'connect_agent', b.time AS 'connect_time', c.event AS 'finalizacao_event', c.data1 AS 'finalizacao_data1', c.data2 AS 'finalizacao_data2', c.data3 AS 'finalizacao_data3', c.data4 AS 'finalizacao_data4', d.nota AS 'nota', (SELECT SUM(data3) FROM queue_log e WHERE e.callid = a.callid AND e.event = 'EXITWITHTIMEOUT') AS 'tempo_espera_timeout'");

			if(preg_match("/'".$dados_ligacao[0]['enterqueue_queuename']."'/i", $fila) || !$fila){

				$id_asterisk = explode("-", $dados_ligacao[0]['enterqueue_data2']);

				$id_contrato_plano_pessoa = DBRead('', 'tb_parametros', "WHERE id_asterisk = '" . $id_asterisk[0] . "' ", 'id_contrato_plano_pessoa');

				$id_ligacao = $dados_ligacao[0]['enterqueue_callid'];

				$enterqueue_data2 = explode('-', $dados_ligacao[0]['enterqueue_data2']);

				$id_empresa_entrada = $enterqueue_data2[0];
				$dados_empresa = DBRead('snep', 'empresas', "WHERE id='$id_empresa_entrada'");

				if ($dados_empresa) {
					$nome_empresa = $dados_empresa[0]['nome'];
				} else {
					$nome_empresa = 'Não identificada';
				}

				if (is_numeric(end($enterqueue_data2)) && strlen(end($enterqueue_data2)) >= 10) {
					$bina = ltrim(end($enterqueue_data2), '0');
				} elseif (end($enterqueue_data2) == 'anonymous') {
					$bina = 'Nº anônimo';
				} else {
					$bina = 'Não identificado';
				}

				$info_chamada = array(
					'data_hora_atendida' => '',
					'nome_empresa' => $nome_empresa,
					'bina' => $bina,
					'tempo_atendimento' => '',
					'nota' => '',
					'gravacao' => '',
					'finalizacao' => '',
					'tempo_espera' => ''
				);

				$info_chamada['data_hora_atendida'] = date('d/m/Y H:i:s', strtotime($dados_ligacao[0]['connect_time']));

				$operador_chamada = explode('/', $dados_ligacao[0]['connect_agent']);
				$tipo_operador_chamada = $operador_chamada[0];
				$cod_operador_chamada = $operador_chamada[1];

				$dados_cdr = DBRead('snep', 'cdr', "WHERE uniqueid ='" . $dados_ligacao[0]['enterqueue_callid'] . "' LIMIT 1", "userfield");

				$arquivo_gravacao = explode(';', $dados_cdr[0]['userfield']);

				if (count($arquivo_gravacao) > 1) {

					$info_chamada['gravacao'] = 'http://pabx.bellunotec.com.br/snep/arquivos/' . date('Y-m-d', strtotime($dados_ligacao[0]['connect_time'])) . '/' . $arquivo_gravacao[1];
				} else {
					$info_chamada['gravacao'] = 'http://pabx.bellunotec.com.br/snep/arquivos/' . date('Y-m-d', strtotime($dados_ligacao[0]['connect_time'])) . '/' . $arquivo_gravacao[0];
				}

				$info_chamada['nota'] = $dados_ligacao[0]['nota'];

				if ($dados_ligacao[0]['finalizacao_event'] == 'COMPLETEAGENT' || $dados_ligacao[0]['finalizacao_event'] == 'COMPLETECALLER') {

					$info_chamada['tempo_espera'] = $dados_ligacao[0]['finalizacao_data1'];
					$info_chamada['tempo_atendimento'] = $dados_ligacao[0]['finalizacao_data2'];

					if ($dados_ligacao[0]['finalizacao_event'] == 'COMPLETEAGENT') {
						$info_chamada['finalizacao'] = 'Atendente';
					} else {
						$info_chamada['finalizacao'] = 'Cliente';
					}

				} elseif (($dados_ligacao[0]['finalizacao_event'] == 'BLINDTRANSFER' || $dados_ligacao[0]['finalizacao_event'] == 'ATTENDEDTRANSFER') && $dados_ligacao[0]['finalizacao_data1'] != 'LINK') {
					$info_chamada['tempo_atendimento'] = $dados_ligacao[0]['finalizacao_data4'];
					$info_chamada['tempo_espera'] = $dados_ligacao[0]['finalizacao_data3'];
					$info_chamada['finalizacao'] = 'Transferida';
				}
				if($dados_ligacao[0]['tempo_espera_timeout'] && $dados_ligacao[0]['tempo_espera_timeout'] > 0){
					$info_chamada['tempo_espera'] += $dados_ligacao[0]['tempo_espera_timeout'];
				}
			}
			
    ?>
			<!-- panel -->
			<div class="panel" style="border-color: #A9A9A9 !important;">
				<div class="panel-heading" style="background-color: #D3D3D3 !important;">

					<div class="row">
						<div class="col-md-3">
							<h3 class="panel-title">Data da monitoria: <strong><?= converteDataHora($conteudo['data_monitoria']) ?></strong>
							</h3>
						</div>
						<div class="col-md-3 text-center">
						</div>
						<div class="col-md-3 text-center">
						</div>
						<div class="col-md-3">
							<h3 class="panel-title pull-right"><span style="font-size: 15px;">Feita por: <strong><?= $analista[0]['nome'] ?></span></strong>
							</h3>
						</div>
					</div>

				</div>
				<div class="panel-body">

					<!-- table audio responsive -->
					<div class="table-responsive">
						<table class="table table-hover">
							<thead>
								<tr style="background-color: #f5f5f5">
									<th>Data ligação</th>
									<th>Empresa</th>
									<th>Atendente</th>
									<th>T. Atendimento</th>
									<th>T. Espera</th>
									<th>Nota</th>
									<th>Gravação</th>
									<th>Atendimento encantador</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td><?= $info_chamada['data_hora_atendida'] ?></td>
									<td><?= $info_chamada['nome_empresa'] ?></td>
									<td><?= $atendente[0]['nome'] ?></td>
									<td><?= gmdate("H:i:s", $info_chamada['tempo_atendimento']) ?></td>
									<td><?= gmdate("H:i:s", $info_chamada['tempo_espera']) ?></td>
									<td><?= $info_chamada['nota'] ?></td>
									<td>
										<audio controls preload="none" class="audio_gravacao" style="width: 100%; min-width: 300px;">
											<source src="<?= $info_chamada['gravacao'] . '.mp3' ?>" type="audio/mp3">
											<source src="<?= $info_chamada['gravacao'] . '.wav' ?>" type="audio/wav">Seu navegador não aceita o player nativo.
										</audio>
									</td>
									<td><?= $atendimento_encantador ?></td>
								</tr>
							</tbody>
						</table>
					</div>
					<!--end table audio responsive -->

				</div>
				<!--end panel body -->
			</div>
			<!--end panel -->

			<br>
		<?php

		} //end foreach

		?>
		<br>

	<?php

	} else {
		echo '<div style="text-align: center"><h4>Não foram encontrados resultados!</h4></div>';
	}

	echo "</div>";
}

function relatorio_cliente_irritado_texto($data_de, $data_ate) 
{
	$data_hora = converteDataHora(getDataHora());

	$periodo_amostra = "<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> de $data_de até $data_ate</span>";
	
	$dados_monitoria = DBRead('', 'tb_monitoria_avaliacao_texto a', "INNER JOIN tb_usuario c ON a.id_usuario_atendente = c.id_usuario INNER JOIN tb_pessoa d ON c.id_pessoa = d.id_pessoa WHERE a.irritado = 1 AND data_monitoria BETWEEN '".converteData($data_de)." 00:00:00' AND '".converteData($data_ate)." 23:59:59' ORDER BY data_monitoria DESC");

	echo "<div class=\"col-md-12\" style=\"padding: 0\">";
	echo "<legend style=\"text-align:center;\"><strong>Relatório de Monitoria - Cliente irritado (via Texto)</strong><br><span style=\"font-size: 14px;\"><strong>Gerado em:</strong> $data_hora</span></legend>";
	echo "<legend style=\"text-align:center;\">$periodo_amostra</legend>";

	if ($dados_monitoria) {

		foreach ($dados_monitoria as $conteudo) {

			$id_monitoria_avaliacao_texto = $conteudo['id_monitoria_avaliacao_texto'];
			$id_usuario_analista = $conteudo['id_usuario_analista'];
			$id_usuario_atendente = $conteudo['id_usuario_atendente'];

			$elogio = 'N/D';
			if ($conteudo['elogio'] == 1) {
				$elogio = 'Sim';
			}

			$analista = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_usuario = '$id_usuario_analista' ");

			$atendente = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_usuario = '$id_usuario_atendente' ");

			$id_atendimento = $conteudo['id_atendimento'];

			$dados_atendimento = DBRead('', 'tb_atendimento a', "INNER JOIN tb_situacao_atendimento b ON a.id_atendimento = b.id_atendimento INNER JOIN tb_situacao c ON b.id_situacao = c.id_situacao LEFT JOIN tb_subarea_problema_atendimento d ON a.id_atendimento = d.id_atendimento LEFT JOIN tb_subarea_problema e ON d.id_subarea_problema = e.id_subarea_problema LEFT JOIN tb_area_problema f ON e.id_area_problema = f.id_area_problema WHERE a.id_atendimento = $id_atendimento", "a.*, c.nome, e.descricao as descricao_subarea_problema, f.nome as descricao_area_problema");

			$conteudo_empresa = DBRead('', 'tb_contrato_plano_pessoa a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa INNER JOIN tb_plano c ON a.id_plano = c.id_plano WHERE a.id_contrato_plano_pessoa = '" . $dados_atendimento[0]['id_contrato_plano_pessoa'] . "'", "b.nome AS nome_empresa, a.*, c.*");

			if ($conteudo_empresa[0]['nome_contrato']) {
				$nome_contrato = " (" . $conteudo_empresa[0]['nome_contrato'] . ") ";
			} else {
				$nome_contrato = '';
			}
                
			$contrato = $conteudo_empresa[0]['nome_empresa'] . " " . $nome_contrato;

			$nome_empresa = $contrato;

			if ($dados_atendimento[0]['fone2']) {
				$legenda_telefone = $dados_atendimento[0]['fone1'].' | '.$dados_atendimento[0]['fone1'];
			} else {
				$legenda_telefone = $dados_atendimento[0]['fone1'];
			}

			$situacao_protocolo = DBRead('', 'tb_parametros', "WHERE id_contrato_plano_pessoa = '" . $dados_atendimento[0]['id_contrato_plano_pessoa'] . "'", "exibir_protocolo, solicitacao_cpf");

			if ($situacao_protocolo[0]['solicitacao_cpf'] == 1) {
				if ($dados_atendimento[0]['cpf_cnpj']) {
					$cpf_cnpj = $dados_atendimento[0]['cpf_cnpj'];
				}
			} else {
				$cpf_cnpj = ' - - - - - - - - - - -';
			}
			
			if ($dados_atendimento[0]['descricao_dado_adicional'] && $dados_atendimento[0]['dado_adicional']) {
				$dado_adicional = $dados_atendimento[0]['descricao_dado_adicional'].' - '. $dados_atendimento[0]['dado_adicional'];

			} else {
				$dado_adicional = ' - - - - - - - - - - -';
			}

			if ($situacao_protocolo[0]['exibir_protocolo'] == 1) {
				$protocolo =  $dados_atendimento[0]['protocolo'];
			} else {
				$protocolo = ' - - - - - - - - - - -';
			}

			if ($dados_atendimento[0]['descricao_subarea_problema']) {
				$sub_area_problema = $dados_atendimento[0]['descricao_subarea_problema'];

			} else {
				$sub_area_problema = ' - - - - - - - - - - -';
			}

			if ($dados_atendimento[0]['descricao_area_problema']) {
				$area_problema = $dados_atendimento[0]['descricao_area_problema'];

			} else {
				$area_problema = ' - - - - - - - - - - -';
			}

			$os = $dados_atendimento[0]['os']. PHP_EOL .$dados_atendimento[0]['nome'];

    ?>

			<!-- panel -->
			<div class="panel" style="border-color: #A9A9A9 !important;">
				<div class="panel-heading" style="background-color: #D3D3D3 !important;">

					<div class="row">
						<div class="col-md-3">
							<h3 class="panel-title">Data da monitoria: <strong><?= converteDataHora($conteudo['data_monitoria']) ?></strong>
							</h3>
						</div>
						<div class="col-md-3 text-center">
						</div>
						<div class="col-md-3 text-center">
						</div>
						<div class="col-md-3">
						<h3 class="panel-title pull-right"><span style="font-size: 15px;">Feita por: <strong><?= $analista[0]['nome'] ?></span></strong>
							</h3>
						</div>
					</div>

				</div>
				<div class="panel-body">

					<!-- table texto responsive -->
					<div class="table-responsive">
						<table class="table table-hover">
							<thead> 
								<tr>
								<th>Data</th>
								<th>Empresa</th>
								<th>Atendente</th>
								<th>Protocolo</th>
								<th>Assinante</th>
								<th>Contato</th>
								<th>Área do problema</th>
								<th>Subarea do problema</th>
								<th>Atendimento encantador</th>
								</tr>
							</thead> 
							<tbody>
								<tr>
									<td><?=  converteDataHora($dados_atendimento[0]['data_inicio']) ?></td>
									<td><?= $nome_empresa ?></td>
									<td><?= $atendente[0]['nome'] ?></td>
									<td><?= $protocolo ?></td>
									<td><?= $dados_atendimento[0]['assinante'] ?></td>
									<td><?= $dados_atendimento[0]['contato'] ?></td>
									<td><?= $sub_area_problema ?></td>
									<td><?= $area_problema ?></td>
									<td><?= $elogio ?></td>
									<td></td>
								</tr>
							</tbody>
						</table>
					</div>
					<!--end table texto responsive -->

					<br>

				</div>
				<!--end panel body -->
			</div>
			<!--end panel -->

			<br>
		<?php

		} //end foreach

	} else {
		echo '<div style="text-align: center"><h4>Não foram encontrados resultados!</h4></div>';
	}

	echo "</div>";
}

function relatorio_contagem_avaliacoes_mensal($analista, $data_de, $data_ate)
{	
	echo $analista;
	if ($analista) {
		$filtro_analista = "AND a.id_usuario_analista = '$analista' ";
		$filtro_analista_texto = "AND d.id_usuario_analista = '$analista' ";

		$nome_analista = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_usuario = '$analista' ", 'b.nome');

		$legenda_analista = $nome_analista[0]['nome'];
	} else {
		$legenda_analista = 'Todos';
	}

	$data_hora = converteDataHora(getDataHora());

	$periodo_amostra = "<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> de $data_de até $data_ate</span>";

	echo "<div class=\"col-md-8 col-md-offset-2\" style=\"padding: 0\">";
	echo "<legend style=\"text-align:center;\"><strong>Relatório de Contagem de Avaliações - Agrupado/Mensal</strong><br><span style=\"font-size: 14px;\"><strong>Gerado em:</strong> $data_hora</span></legend>";
	echo "<legend style=\"text-align:center;\">$periodo_amostra</legend>";
	echo "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong>Analista:</strong> $legenda_analista</span></legend>";

	$dados_analistas = DBRead('', 'tb_monitoria_avaliacao_audio a', "INNER JOIN tb_usuario b ON b.id_usuario = a.id_usuario_analista INNER JOIN tb_pessoa c ON b.id_pessoa = c.id_pessoa WHERE b.status = '1' AND (a.data_monitoria BETWEEN '".converteData($data_de)." 00:00:00' AND '".converteData($data_ate)." 23:59:59')  $filtro_analista UNION SELECT d.id_usuario_analista, f.nome FROM tb_monitoria_avaliacao_texto d INNER JOIN tb_usuario e ON e.id_usuario = d.id_usuario_analista INNER JOIN tb_pessoa f ON e.id_pessoa = f.id_pessoa WHERE e.status = '1' AND (d.data_monitoria BETWEEN '".converteData($data_de)." 00:00:00' AND '".converteData($data_ate)." 23:59:59') $filtro_analista_texto ORDER BY nome ASC", "a.id_usuario_analista, c.nome");

	//var_dump($dados_analistas);

	if ($dados_analistas) {

		foreach ($dados_analistas as $conteudo) {

			$contagem_avaliacoes = array();

			$contagem_telefone = DBRead('', 'tb_monitoria_avaliacao_audio a', "INNER JOIN tb_monitoria_mes b ON a.id_monitoria_mes = b.id_monitoria_mes WHERE id_usuario_analista = '" . $conteudo['id_usuario_analista'] . "' AND (data_monitoria BETWEEN '".converteData($data_de)." 00:00:00' AND '".converteData($data_ate)." 23:59:59') $filtro_analista ORDER BY data_monitoria ASC ");

			$contagem_texto = DBRead('', 'tb_monitoria_avaliacao_texto a', "INNER JOIN tb_monitoria_mes b ON a.id_monitoria_mes = b.id_monitoria_mes WHERE id_usuario_analista = '" . $conteudo['id_usuario_analista'] . "' AND (data_monitoria BETWEEN '".converteData($data_de)." 00:00:00' AND '".converteData($data_ate)." 23:59:59') $filtro_analista ORDER BY data_monitoria ASC ");

			if ($contagem_telefone) {
				foreach ($contagem_telefone as $conteudo_cont_telefone) {
					$data = explode(" ", $conteudo_cont_telefone['data_monitoria']);
					$data = converteData($data[0]);

					$contagem_avaliacoes[$data]['telefone']['total']++;
				}
			}

			if ($contagem_texto) {
				foreach ($contagem_texto as $conteudo_cont_texto) {
					$data = explode(" ", $conteudo_cont_texto['data_monitoria']);
					$data = converteData($data[0]);

					$contagem_avaliacoes[$data]['texto']['total']++;
				}
			}

			?>

				<div class="panel panel-default" style="border: 1px solid #BDBDBD;">
					<div class="panel-heading clearfix">
						<h3 class="panel-title text-left">
							<span>&nbsp;Analista:<strong> <?= $conteudo['nome'] ?> </strong></span>
						</h3>
					</div>
					<div class="panel-body">				
						<table class="table table-hover dataTable">
							<thead style="background-color: #F2F2F2">
								<tr>
									<td class="col-md-3"><strong>Data da monitoria</strong></td>
									<td class="col-md-3 text-center"><strong>via Telefone</strong></td>
									<td class="col-md-3 text-center"><strong>via Texto</strong></td>
									<td class="col-md-3 text-center"><strong>Total</strong></td>
								</tr>
							</thead>
							<tbody>
								<?php 

								$total = 0;
								foreach ($contagem_avaliacoes as $data => $conteudo_contagem) { 									
									$total += $conteudo_contagem['telefone']['total'] + $conteudo_contagem['texto']['total'];
								?>
									<tr>
										<td><?= $data ?></td>
										<td class="text-center">
											<?= $conteudo_contagem['telefone']['total'] ?: 0 ?>
										</td>
										<td class="text-center">
											<?= $conteudo_contagem['texto']['total'] ?: 0 ?>
										</td>
										<td class="text-center">
										<?= $conteudo_contagem['telefone']['total'] + $conteudo_contagem['texto']['total'] ?>
										</td>
									</tr>
								<?php } ?>
							</tbody>
							<tfoot>
								<tr >
									<td><strong>Total de monitorias realizadas</strong></td>
									<td></td>
									<td></td>
									<td class="text-center">
										<span class="label label-default" style="font-size: 14px">
											<?= $total ?>
										</span>
									</td>
								</tr>
							</tfoot>
						</table>
					</div>
				</div>
					
			<?php
				}

	} else {
		echo '<div style="text-align: center"><h4>Não foram encontradas avaliações!</h4></div>';
	}
}

function relatorio_trajetoria_telefone($mes_inicio, $mes_fim, $usuario2)
{
	$mes_inicio_legenda = converteData($mes_inicio);
	$mes_inicio_legenda = substr($mes_inicio_legenda, 3);

	$mes_fim_legenda = converteData($mes_fim);
	$mes_fim_legenda = substr($mes_fim_legenda, 3);

	$data_hora = converteDataHora(getDataHora());

	if ($usuario2) {
		$dados_usuario = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_usuario = '$usuario2'", "b.nome");
		$nome_usuario = $dados_usuario[0]['nome'];

		$filtro_usuario = "AND id_usuario = '" . $usuario2 . "' ";
	} else {
		$nome_usuario = 'Todos';
	}

	$nome_usuario_legend = '<span style="font-size: 14px;"><strong>Atendente:</strong> ' . $nome_usuario . '</span>';
	$legenda_mes_referencia = '<span style="font-size: 14px;"><strong>Mês inicio:</strong> ' . $mes_inicio_legenda . ' - <strong>Mês fim:</strong> ' . $mes_fim_legenda . ' </span>';

	echo "<div class=\"col-md-12\" style=\"padding: 0\">";
	echo "<legend style=\"text-align:center;\"><strong>Relatório de Trajetória - (Via Telefone)</strong><br><span style=\"font-size: 14px;\"><strong>Gerado em:</strong> $data_hora</span></legend>";
	echo "<legend style=\"text-align:center;\">$legenda_mes_referencia</legend>";
	echo "<legend style=\"text-align:center;\">$nome_usuario_legend</legend>";

	$dados_formulario = DBRead('', 'tb_monitoria_mes', "WHERE data_referencia >= '$mes_inicio' AND data_referencia <= '$mes_fim' AND tipo_monitoria = 1", 'id_monitoria_mes, data_referencia');

	$dados_usuarios = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_perfil_sistema = 3 AND a.status = 1 $filtro_usuario ORDER BY b.nome ASC", 'a.id_usuario, b.nome');

	if ($dados_usuarios) {

		$array_resultado = array();
		foreach ($dados_usuarios as $conteudo_usuarios) {

			$usuario = $conteudo_usuarios['id_usuario'];
			$nome = $conteudo_usuarios['nome'];

			foreach ($dados_formulario as $conteudo) {

				$data_referencia = $conteudo['data_referencia'];
				$id_monitoria_mes = $conteudo['id_monitoria_mes'];

				$resultado_monitoria = DBRead('', 'tb_monitoria_resultado', "WHERE id_monitoria_mes = $id_monitoria_mes AND id_usuario = $usuario");

				if ($resultado_monitoria) {

					$array_resultado[$nome]['mes_referencia'][$data_referencia]['resultado'] = $resultado_monitoria[0]['resultado'];
					
				} else {

					if (!$array_resultado[$nome]['mes_referencia'][$data_referencia]['resultado']) {
						$array_resultado[$nome]['mes_referencia'][$data_referencia]['resultado'] = ' - - - -';
					}
				}
			}
		}

		foreach ($array_resultado as $key => $value) {

		?>

			<div class="row">
				<div class="col-md-4">
				</div>
				<div class="col-md-4 text-center" style="padding: 5px 5px 5px 5px;">
					<span style="font-size: 16px;">
						Atendente: <strong><?= $key ?></strong>
					</span>
				</div>
				<div class="col-md-4">
				</div>
			</div>

			<!-- table resultados -->
			<div class="row">
				<div class="col-md-12" style="padding: 0px 15px 15px 15px;">
					<table class="table table-bordered" style="border: solid 1px #D8D8D8">
						<thead>
							<tr style="background-color: #D8D8D8">
								<?php

								foreach ($array_resultado[$key] as $conteudo) {

									foreach ($conteudo as $mes => $b) {									

										$mes_referencia = converteData($mes);
										$mes_referencia = substr($mes_referencia, 3);
								?>

										<th class="mes_referencia text-center"><?= $mes_referencia ?></th>
								<?php 
									}
								} 
								?>
							</tr>
						</thead>
						<tbody>
							<?php
								foreach ($array_resultado[$key] as $conteudo) {

									foreach ($conteudo as $mes => $resultado) {
										
										if ($resultado['resultado'] == ' - - - -') {
											$class = '';
											$legenda = $resultado['resultado'];
										} else {
											$class = 'info';
											$legenda = $resultado['resultado'].'%';
										}

								?>

										<td class="text-center <?=$class?>"><?= $resultado['resultado'] ?></td>	
							<?php 
									}
								} 
							?>
						</tbody>
					</table>
					<hr>
				</div>
			</div>
			<!-- end table resultados -->

		<?php
		}

	} else {
		echo '<div style="text-align: center"><h4>Não foram encontrados resultados!</h4></div>';
	}
}

function relatorio_trajetoria_texto($mes_inicio, $mes_fim, $usuario2)
{
	$mes_inicio_legenda = converteData($mes_inicio);
	$mes_inicio_legenda = substr($mes_inicio_legenda, 3);

	$mes_fim_legenda = converteData($mes_fim);
	$mes_fim_legenda = substr($mes_fim_legenda, 3);

	$data_hora = converteDataHora(getDataHora());

	if ($usuario2) {
		$dados_usuario = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_usuario = '$usuario2'", "b.nome");
		$nome_usuario = $dados_usuario[0]['nome'];

		$filtro_usuario = "AND id_usuario = '" . $usuario2 . "' ";
	} else {
		$nome_usuario = 'Todos';
	}

	$nome_usuario_legend = '<span style="font-size: 14px;"><strong>Atendente:</strong> ' . $nome_usuario . '</span>';
	$legenda_mes_referencia = '<span style="font-size: 14px;"><strong>Mês inicio:</strong> ' . $mes_inicio_legenda . ' - <strong>Mês fim:</strong> ' . $mes_fim_legenda . ' </span>';

	echo "<div class=\"col-md-12\" style=\"padding: 0\">";
	echo "<legend style=\"text-align:center;\"><strong>Relatório de Trajetória - (Via Texto)</strong><br><span style=\"font-size: 14px;\"><strong>Gerado em:</strong> $data_hora</span></legend>";
	echo "<legend style=\"text-align:center;\">$legenda_mes_referencia</legend>";
	echo "<legend style=\"text-align:center;\">$nome_usuario_legend</legend>";

	$dados_formulario = DBRead('', 'tb_monitoria_mes', "WHERE data_referencia >= '$mes_inicio' AND data_referencia <= '$mes_fim' AND tipo_monitoria = 2", 'id_monitoria_mes, data_referencia');

	$dados_usuarios = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_perfil_sistema = 3 AND a.status = 1 $filtro_usuario ORDER BY b.nome ASC", 'a.id_usuario, b.nome');

	if ($dados_usuarios) {

		$array_resultado = array();
		foreach ($dados_usuarios as $conteudo_usuarios) {

			$usuario = $conteudo_usuarios['id_usuario'];
			$nome = $conteudo_usuarios['nome'];

			foreach ($dados_formulario as $conteudo) {

				$data_referencia = $conteudo['data_referencia'];
				$id_monitoria_mes = $conteudo['id_monitoria_mes'];

				$resultado_monitoria = DBRead('', 'tb_monitoria_resultado', "WHERE id_monitoria_mes = $id_monitoria_mes AND id_usuario = '$usuario' ");
			
				if ($resultado_monitoria) {

					$array_resultado[$nome]['mes_referencia'][$data_referencia]['resultado'] = $resultado_monitoria[0]['resultado'];
					
				} else {
					
					if (!$array_resultado[$nome]['mes_referencia'][$data_referencia]['resultado']) {
						$array_resultado[$nome]['mes_referencia'][$data_referencia]['resultado'] = ' - - - -';
					}
				}
			}
		}

		foreach ($array_resultado as $key => $value) {

		?>

			<div class="row">
				<div class="col-md-4">
				</div>
				<div class="col-md-4 text-center" style="padding: 5px 5px 5px 5px;">
					<span style="font-size: 16px;">
						Atendente: <strong><?= $key ?></strong>
					</span>
				</div>
				<div class="col-md-4">
				</div>
			</div>

			<!-- table resultados -->
			<div class="row">
				<div class="col-md-12" style="padding: 0px 15px 15px 15px;">
					<table class="table table-bordered" style="border: solid 1px #D8D8D8">
						<thead>
							<tr style="background-color: #D8D8D8">
								<?php

								foreach ($array_resultado[$key] as $conteudo) {

									foreach ($conteudo as $mes => $b) {									

										$mes_referencia = converteData($mes);
										$mes_referencia = substr($mes_referencia, 3);
								?>

										<th class="mes_referencia text-center"><?= $mes_referencia ?></th>
								<?php 
									}
								} 
								?>
							</tr>
						</thead>
						<tbody>
							<?php
								foreach ($array_resultado[$key] as $conteudo) {

									foreach ($conteudo as $mes => $resultado) {
										
										if ($resultado['resultado'] == ' - - - -') {
											$class = '';
											$legenda = $resultado['resultado'];
										} else {
											$class = 'info';
											$legenda = $resultado['resultado'].'%';
										}

								?>

										<td class="text-center <?=$class?>"><?= $resultado['resultado'] ?></td>	
							<?php 
									}
								} 
							?>
						</tbody>
					</table>
					<hr>
				</div>
			</div>
			<!-- end table resultados -->

		<?php
		}

	} else {
		echo '<div style="text-align: center"><h4>Não foram encontrados resultados!</h4></div>';
	}
}

function relatorio_monitoria_individual_telefone_desconsiderado($mes, $ano, $usuario, $perfil_sistema, $classificacao, $formulario)
{

	$fila = "'callATENDIMENTOvip','callATENDIMENTOalta','callATENDIMENTOnormal','callATENDIMENTObaixa','callATENDIMENTOnotificacaoparada', 'callATENDIMENTOvipEXT','callATENDIMENTOaltaEXT','callATENDIMENTOnormalEXT','callATENDIMENTObaixaEXT','callATENDIMENTOnotificacaoparadaEXT','callATENDIMENTOvipEXP','callATENDIMENTOaltaEXP','callATENDIMENTOnormalEXP','callATENDIMENTObaixaEXP','callATENDIMENTOnotificacaoparadaEXP'";

	$data_hora = converteDataHora(getDataHora());

	$data_referencia = $ano . '-' . $mes . '-01';
	$legenda_mes_referencia = $mes . '/' . $ano;

	$dados_usuario = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_usuario = '$usuario'", "b.nome");
	$nome_usuario = $dados_usuario[0]['nome'];

	$turno = DBRead('', 'tb_horarios_escala', "WHERE id_usuario = '$usuario' AND data_inicial = '$data_referencia' ", 'inicial_seg, final_seg');

	if ($turno) {
		$inicial_seg = $turno[0]['inicial_seg'];
		$final_seg = $turno[0]['final_seg'];

		if ($inicial_seg > $final_seg) {
			$hora1 = '2000-10-10 ' . $inicial_seg . ':00';
			$hora2 = '2000-10-11 ' . $final_seg . ':00';
			$data1 = date('Y-m-d H:i:s', strtotime("+0 days", strtotime($hora1)));
			$data2 = date('Y-m-d H:i:s', strtotime("+0 days", strtotime($hora2)));
			$resultado = strtotime($data2) - strtotime($data1);

		} else {
			$hora1 = strtotime('' . $inicial_seg . '');
			$hora2 = strtotime('' . $final_seg . '');
			$resultado = ($hora2 - $hora1);
		}

		$h = ($resultado / (60 * 60)) % 24;

		$dados_turno = DBRead('', 'tb_monitoria_mes', "WHERE data_referencia = '$data_referencia' AND id_monitoria_mes = $formulario AND status = 1");

		$soma = 0;
		if ($h >= 5) {

			$soma = $dados_turno[0]['qtd_audios_monitoria_integral_sn'] + $dados_turno[0]['qtd_audios_monitoria_integral_n1'] + $dados_turno[0]['qtd_audios_monitoria_integral_n2'] + $dados_turno[0]['qtd_audios_monitoria_integral_n3'] + $dados_turno[0]['qtd_audios_monitoria_integral_n4'] + $dados_turno[0]['qtd_audios_monitoria_integral_n5'];

		} else {
			$soma = $dados_turno[0]['qtd_audios_monitoria_meio_turno_sn'] + $dados_turno[0]['qtd_audios_monitoria_meio_turno_n1'] + $dados_turno[0]['qtd_audios_monitoria_meio_turno_n2'] + $dados_turno[0]['qtd_audios_monitoria_meio_turno_n3'] + $dados_turno[0]['qtd_audios_monitoria_meio_turno_n4'] + $dados_turno[0]['qtd_audios_monitoria_meio_turno_n5'];
		}
	}

	if ($formulario) {
		$dados_monitoria = DBRead('', 'tb_monitoria_avaliacao_audio a', "INNER JOIN tb_subarea_problema b ON a.id_subarea_problema = b.id_subarea_problema INNER JOIN tb_area_problema c ON b.id_area_problema = c.id_area_problema INNER JOIN tb_usuario d ON a.id_usuario_atendente = d.id_usuario INNER JOIN tb_pessoa e ON d.id_pessoa = e.id_pessoa WHERE a.id_monitoria_mes = $formulario AND a.considerar = 2 ORDER BY data_monitoria DESC", 'a.*, b.*, c.*, e.nome');

		$verifica_plano_acao = DBRead('', 'tb_monitoria_mes_plano_acao_chamado', "WHERE id_monitoria_mes = $formulario and data_referencia = '$data_referencia' ");

	} else {
		$dados_monitoria = '';
		$verifica_plano_acao = '';
	}

	if ($classificacao == 1) {
		$legenda_classificacao = ' (via Telefone - Em Treinamento)';

	} else if ($classificacao == 2) {
		$legenda_classificacao = ' (via Telefone - Período de Experiência)';

	} else if ($classificacao == 3) {
		$legenda_classificacao = ' (via Telefone - Efetivado)';
	}

	if ($usuario) {
		$nome_usuario_legenda = '<span style="font-size: 14px;"><strong>Atendente:</strong> ' . $nome_usuario . '</span>';
		$filtro_usuario = "AND a.id_usuario_atendente = '$usuario' ";

	} else {
		$nome_usuario_legenda = '<span style="font-size: 14px;"><strong>Atendente:</strong>Todos</span>';
		$filtro_usuario = '';
	}

	$legenda_mes_referencia = '<span style="font-size: 14px;"><strong>Mês referência:</strong> ' . $legenda_mes_referencia . '</span>';

	echo "<div class=\"col-md-12\" style=\"padding: 0\">";
	echo "<legend style=\"text-align:center;\"><strong>Relatório de Monitoria - Individual $legenda_classificacao</strong><br><span style=\"font-size: 14px;\"><strong>Gerado em:</strong> $data_hora</span></legend>";
	echo "<legend style=\"text-align:center;\">$legenda_mes_referencia</legend>";
	echo "<legend style=\"text-align:center;\">$nome_usuario_legenda</legend>";

	if ($dados_monitoria) {

		$array_pontos = array();
		$soma_total_percentual = array();
		$tempo_total_avaliacao = array();

		$cont_id = 0;

		foreach ($dados_monitoria as $conteudo) {
			
			$cont_id++;

			$id_monitoria_avaliacao_audio = $conteudo['id_monitoria_avaliacao_audio'];
			$id_usuario_analista = $conteudo['id_usuario_analista'];

			$analista = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_usuario = '$id_usuario_analista' ");

			$id_ligacao = $conteudo['id_ligacao'];
			$observacao = $conteudo['obs_avaliacao'];
			$id_erro = $conteudo['id_erro'];

			$dados_ligacao = DBRead('snep', 'queue_log a', "INNER JOIN queue_log b ON (b.callid = a.callid AND b.event = 'CONNECT') INNER JOIN queue_log c ON (c.callid = a.callid AND (c.event = 'COMPLETEAGENT' OR c.event = 'COMPLETECALLER' OR c.event = 'BLINDTRANSFER' OR c.event = 'ATTENDEDTRANSFER')) LEFT JOIN pesquisa d ON  a.callid = d.UNIQUEID WHERE a.event = 'ENTERQUEUE' AND (c.data2 >= 30 OR c.data4 >= 30) AND a.callid = '$id_ligacao' GROUP BY b.id ORDER BY b.id LIMIT 1", "a.queuename AS 'enterqueue_queuename', a.callid AS 'enterqueue_callid', a.data2 AS 'enterqueue_data2', b.agent AS 'connect_agent', b.time AS 'connect_time', c.event AS 'finalizacao_event', c.data1 AS 'finalizacao_data1', c.data2 AS 'finalizacao_data2', c.data3 AS 'finalizacao_data3', c.data4 AS 'finalizacao_data4', d.nota AS 'nota', (SELECT SUM(data3) FROM queue_log e WHERE e.callid = a.callid AND e.event = 'EXITWITHTIMEOUT') AS 'tempo_espera_timeout'");

			if(preg_match("/'".$dados_ligacao[0]['enterqueue_queuename']."'/i", $fila) || !$fila){

				$id_asterisk = explode("-", $dados_ligacao[0]['enterqueue_data2']);

				$id_contrato_plano_pessoa = DBRead('', 'tb_parametros', "WHERE id_asterisk = '" . $id_asterisk[0] . "' ", 'id_contrato_plano_pessoa');

				$id_ligacao = $dados_ligacao[0]['enterqueue_callid'];

				$enterqueue_data2 = explode('-', $dados_ligacao[0]['enterqueue_data2']);

				$id_empresa_entrada = $enterqueue_data2[0];
				$dados_empresa = DBRead('snep', 'empresas', "WHERE id='$id_empresa_entrada'");

				if ($dados_empresa) {
					$nome_empresa = $dados_empresa[0]['nome'];
				} else {
					$nome_empresa = 'Não identificada';
				}

				if (is_numeric(end($enterqueue_data2)) && strlen(end($enterqueue_data2)) >= 10) {
					$bina = ltrim(end($enterqueue_data2), '0');
				} elseif (end($enterqueue_data2) == 'anonymous') {
					$bina = 'Nº anônimo';
				} else {
					$bina = 'Não identificado';
				}

				$info_chamada = array(
					'data_hora_atendida' => '',
					'nome_empresa' => $nome_empresa,
					'bina' => $bina,
					'tempo_atendimento' => '',
					'nota' => '',
					'gravacao' => '',
					'finalizacao' => '',
					'tempo_espera' => ''
				);

				$info_chamada['data_hora_atendida'] = date('d/m/Y H:i:s', strtotime($dados_ligacao[0]['connect_time']));

				$operador_chamada = explode('/', $dados_ligacao[0]['connect_agent']);
				$tipo_operador_chamada = $operador_chamada[0];
				$cod_operador_chamada = $operador_chamada[1];

				$dados_cdr = DBRead('snep', 'cdr', "WHERE uniqueid ='" . $dados_ligacao[0]['enterqueue_callid'] . "' LIMIT 1", "userfield");

				$arquivo_gravacao = explode(';', $dados_cdr[0]['userfield']);

				if (count($arquivo_gravacao) > 1) {

					$info_chamada['gravacao'] = 'https://pabx.bellunotec.com.br/snep/arquivos/' . date('Y-m-d', strtotime($dados_ligacao[0]['connect_time'])) . '/' . $arquivo_gravacao[1];
				} else {
					$info_chamada['gravacao'] = 'https://pabx.bellunotec.com.br/snep/arquivos/' . date('Y-m-d', strtotime($dados_ligacao[0]['connect_time'])) . '/' . $arquivo_gravacao[0];
				}

				$info_chamada['nota'] = $dados_ligacao[0]['nota'];

				if ($dados_ligacao[0]['finalizacao_event'] == 'COMPLETEAGENT' || $dados_ligacao[0]['finalizacao_event'] == 'COMPLETECALLER') {

					$info_chamada['tempo_espera'] = $dados_ligacao[0]['finalizacao_data1'];
					$info_chamada['tempo_atendimento'] = $dados_ligacao[0]['finalizacao_data2'];
					$tempo_total_avaliacao['tempo'] += $dados_ligacao[0]['finalizacao_data2'];

					if ($dados_ligacao[0]['finalizacao_event'] == 'COMPLETEAGENT') {
						$info_chamada['finalizacao'] = 'Atendente';
					} else {
						$info_chamada['finalizacao'] = 'Cliente';
					}
				} elseif (($dados_ligacao[0]['finalizacao_event'] == 'BLINDTRANSFER' || $dados_ligacao[0]['finalizacao_event'] == 'ATTENDEDTRANSFER') && $dados_ligacao[0]['finalizacao_data1'] != 'LINK') {
					$info_chamada['tempo_atendimento'] = $dados_ligacao[0]['finalizacao_data4'];
					$tempo_total_avaliacao['tempo'] += $dados_ligacao[0]['finalizacao_data4'];
					$info_chamada['tempo_espera'] = $dados_ligacao[0]['finalizacao_data3'];
					$info_chamada['finalizacao'] = 'Transferida';
				}
				if($dados_ligacao[0]['tempo_espera_timeout'] && $dados_ligacao[0]['tempo_espera_timeout'] > 0){
					$info_chamada['tempo_espera'] += $dados_ligacao[0]['tempo_espera_timeout'];
				}
			}

    ?>
			<!-- panel -->
			<div class="panel" style="border-color: #A9A9A9 !important;">
				<div class="panel-heading" style="background-color: #D3D3D3 !important;">

					<div class="row">
						<div class="col-md-3">
							<h3 class="panel-title">Data da monitoria: <strong><?= converteDataHora($conteudo['data_monitoria']) ?></strong>
							</h3>
						</div>
						<div class="col-md-3 text-center">

							<?php if ($perfil_sistema != 3) { ?>

								<h3 class="panel-title">Tempo de avaliação: <strong><?= converteSegundosHoras($conteudo['duracao_avaliacao']) ?></strong>
								</h3>

							<?php } ?>
						</div>
						<div class="col-md-3 text-center">
							<?php if ($perfil_sistema != 3) { ?>
								<h3 class="panel-title text-center">
									<span style="font-size: 15px;">Feita por: <strong><?= $analista[0]['nome'] ?></span></strong>
								</h3>
							<?php } ?>
						</div>
						<div class="col-md-3">
							<h3 class="panel-title pull-right">
								<span style="font-size: 15px;">Feita por: <strong><?= $conteudo['nome'] ?></span></strong>
							</h3>
						</div>
					</div>
				</div>
				<div class="panel-body">

					<!-- table audio responsive -->
					<div class="table-responsive">
						<table class="table table-hover">
							<thead>
								<tr style="background-color: #f5f5f5">
									<th>Data ligação</th>
									<th>Empresa</th>
									<th>Número</th>
									<th>Finalização</th>
									<th>T. Atendimento</th>
									<th>T. Espera</th>
									<th>Nota</th>
									<?php if ($perfil_sistema != 3) { ?>
										<th>Gravação</th>
									<?php } ?>
								</tr>
							</thead>

							<tbody>
								<tr>
									<td><?= $info_chamada['data_hora_atendida'] ?></td>
									<td><?= $info_chamada['nome_empresa'] ?></td>
									<td><?= $info_chamada['bina'] ?></td>
									<td><?= $info_chamada['finalizacao'] ?></td>
									<td><?= gmdate("H:i:s", $info_chamada['tempo_atendimento']) ?></td>
									<td><?= gmdate("H:i:s", $info_chamada['tempo_espera']) ?></td>
									<td><?= $info_chamada['nota'] ?></td>
									<?php if ($perfil_sistema != 3) { ?>
										<td>
											<audio controls preload="none" class="audio_gravacao" style="width: 100%; min-width: 300px;">
												<source src="<?= $info_chamada['gravacao'] . '.mp3' ?>" type="audio/mp3">
												<source src="<?= $info_chamada['gravacao'] . '.wav' ?>" type="audio/wav">Seu navegador não aceita o player nativo.
											</audio>
										</td>
									<?php } ?>
							</tbody>
						</table>
					</div>
					<!--end table audio responsive -->

					<!-- table outras informacoes -->
					<table class="table table-hover">
						<thead>
							<tr style="background-color: #f5f5f5">
								<th class="col-md-3">Nome do contato</th>
								<th class="col-md-3">Área do problema</th>
								<th class="col-md-3">Subárea do problema</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td><?= $conteudo['nome_contato'] ?></td>
								<td><?= $conteudo['nome'] ?></td>
								<td><?= $conteudo['descricao'] ?></td>
							</tr>
						</tbody>
					</table>
					<!-- end table outras informacoes -->

					<br>

					<?php
					if ($id_erro) {

						$dados_erro = DBRead('', 'tb_erro_atendimento a', "INNER JOIN tb_contrato_plano_pessoa b ON a.id_contrato_plano_pessoa = b.id_contrato_plano_pessoa INNER JOIN tb_pessoa c ON b.id_pessoa = c.id_pessoa INNER JOIN tb_tipo_erro d ON a.id_tipo_erro = d.id_tipo_erro INNER JOIN tb_usuario e ON a.id_usuario_cadastrou = e.id_usuario INNER JOIN tb_pessoa f ON e.id_pessoa = f.id_pessoa WHERE a.id_erro_atendimento = '$id_erro' ", 'a.id_erro_atendimento, a.protocolo, a.assinante, a.data_erro, a.hora_erro, a.descricao_cliente, c.nome AS nome_empresa, d.nome AS descricao_erro, f.nome AS usuario_cadastrou');

						if ($dados_erro[0]['protocolo'] == '') {
							$protocolo = 'N/D';
						} else {
							$protocolo = $dados_erro[0]['protocolo'];
						}
					?>
						<!-- panel erro -->
						<div class="panel panel-danger" style="border-color: #FFB6C1 !important;">
							<div class="panel-heading">
								<div class="row">
									<div class="col-md-4" style="padding-left: 7px;">
										<h6 class="panel-title" style="font-size: 14px !important;">
											Reclamação/Erro
										</h6>
									</div>
									<div class="col-md-4">
										<h6 class="panel-title text-center" style="font-size: 14px !important;">
											Protocolo: <?= $dados_erro[0]['protocolo'] ?>
										</h6>
									</div>
									<div class="col-md-4" style="padding-right: 7px;">

									</div>
								</div>
							</div>
							<div class="panel-body">
								<div class="table-responsive">
									<table class="table table-hover" style="margin-bottom: 8px;">
										<thead>
											<tr>
												<th>Tipo erro reclamação</th>
												<th>Criado por</th>
												<th>Empresa</th>
												<th>Assinante</th>
												<th>Data do erro/reclamação</th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td><?= $dados_erro[0]['descricao_erro'] ?></td>
												<td><?= $dados_erro[0]['usuario_cadastrou'] ?></td>
												<td><?= $dados_erro[0]['nome_empresa'] ?></td>
												<td><?= $dados_erro[0]['assinante'] ?></td>
												<td><?= converteData($dados_erro[0]['data_erro']) ?> <?= $dados_erro[0]['hora_erro'] ?></td>
											</tr>
										</tbody>
									</table>
								</div>

								<hr>
								<div class='conteudo-editor' style="margin-left: 7px;">
									<strong>Descrição</strong><br><br>
									<?= $dados_erro[0]['descricao_cliente'] ?>
								</div>
							</div>
						</div>
						<!-- end panel erro -->

					<?php
					}
					?>

					<br>

					<div id="accordionPlano_<?=$cont_id?>" class="panel-collapse collapse in accordionPlano">
						<!-- table responsive -->
						<div class="table-responsive">
							<table class="table table-hover table-quesitos" style="border: 1px solid #A9A9A9;">
								<thead>
									<tr style="background-color: #f5f5f5">
										<th class="col-md-6">Quesito</th>
										<th class="col-md-2 text-center">Resultado</th>
										<th class="col-md-2 text-center">Valor do quesito</th>
										<th class="col-md-2 text-center">Valor obtido</th>
									</tr>
								</thead>
								<tbody>

									<?php
									$dados_monitoria_mes = DBRead('', 'tb_monitoria_avaliacao_audio a', "INNER JOIN tb_monitoria_avaliacao_audio_mes b ON a.id_monitoria_avaliacao_audio = b.id_monitoria_avaliacao_audio INNER JOIN tb_monitoria_mes_quesito c ON b.id_monitoria_mes_quesito = c.id_monitoria_mes_quesito INNER JOIN tb_monitoria_quesito d ON c.id_monitoria_quesito = d.id_monitoria_quesito
									INNER JOIN tb_usuario e ON a.id_usuario_analista = e.id_usuario INNER JOIN tb_pessoa f ON e.id_pessoa = f.id_pessoa INNER JOIN tb_monitoria_mes g ON g.id_monitoria_mes = c.id_monitoria_mes WHERE a.id_monitoria_mes = $formulario $filtro_usuario AND a.id_ligacao = '$id_ligacao' AND g.status = 1 AND a.considerar = 2", 'a.nome_contato, a.id_erro, a.obs_avaliacao, a.total_pontos, c.pontos_valor, c.porcentagem_plano_acao, b.pontos, d.descricao, d.plano_acao,f.nome as nome_analista, g.soma_total_pontos_quesitos, d.id_monitoria_quesito');

									if ($dados_monitoria_mes) {

										$total_pontos_geral = 0;
										$total_pontos_feitos = 0;

										foreach ($dados_monitoria_mes as $conteudo_monitoria) {

											$array_pontos[$conteudo_monitoria['id_monitoria_quesito']]['pontos'] += $conteudo_monitoria['pontos'];

											$array_pontos[$conteudo_monitoria['id_monitoria_quesito']]['qtd'] += 1;

											$array_pontos[$conteudo_monitoria['id_monitoria_quesito']]['pontos_valor'] = $conteudo_monitoria['pontos_valor'];

											$array_pontos[$conteudo_monitoria['id_monitoria_quesito']]['descricao'] = $conteudo_monitoria['descricao'];

											$array_pontos[$conteudo_monitoria['id_monitoria_quesito']]['plano_acao'] = $conteudo_monitoria['plano_acao'];

											$array_pontos[$conteudo_monitoria['id_monitoria_quesito']]['porcentagem_plano_acao'] = $conteudo_monitoria['porcentagem_plano_acao'];

											$observacao = $conteudo_monitoria['obs_avaliacao'];

											$analista = $conteudo_monitoria['nome_analista'];
											$contato = $conteudo_monitoria['nome_contato'];
											$total_pontos = $conteudo_monitoria['pontos_valor'];
											$pontos = $conteudo_monitoria['pontos'];

											$total_pontos_geral += $conteudo_monitoria['pontos_valor'];
											$total_pontos_feitos += $conteudo_monitoria['pontos'];

											$resultado = ($pontos * 100) / $total_pontos;
											$resultado = number_format($resultado, 2, '.', ' ');

											$class_bar = '';
											if ($resultado == 100.00) {
												$span = '<span class="label label-success" style="font-size: 11px;display: inline-block; min-width: 160px;">Atendeu ao quesito <i class="fa fa-thumbs-up pull-right" style="color: white;"></i></span>';
											} else if ($resultado != 100.00) {
												$span = '<span class="label label-danger" style="font-size: 11px;display: inline-block; min-width: 160px;">Não atendeu ao quesito <i class="fa fa-thumbs-down pull-right" style="color: white;"></i></span>';
											}

									?>
											<tr>
												<td><?= $conteudo_monitoria['descricao'] ?></td>
												<td class="text-center">
													<?= $span ?>
												</td>
												<td class="text-center"><?= $total_pontos ?></td>
												<td class="text-center"><?= $pontos ?></td>
											</tr>
									<?php
										}

										$resultado_avalicao = ($total_pontos_feitos * 100) / $total_pontos_geral;

										$soma_total_percentual['resultado'] += $resultado_avalicao;
										$soma_total_percentual['qtd'] += 1;
									}

									?>
								</tbody>
								<tfoot>
									<tr class="success">
										<td><strong>Resultados</strong></td>
										<td class="text-center"><strong><?= number_format($resultado_avalicao, 2, '.', ' '); ?>%</strong></td>
										<td class="text-center"><strong><?= $total_pontos_geral ?></strong></td>
										<td class="text-center"><strong><?= $total_pontos_feitos ?></strong></td>
									</tr>
								</tfoot>
							</table>

						</div>
						<!--end table responsive -->

						<br>

						<!-- table -->

						<?php if ($observacao != '') { ?>
							<table class="table table-hover">
								<thead>
									<tr style="background-color: #f5f5f5">
										<th class="col-md-12">Observação da avaliação</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td><?= $observacao ?></td>
									</tr>
								</tbody>
							</table>
						<?php } ?>
						<!-- end table -->
					</div>
				</div>
				<!--end panel body -->
			</div>
			<!--end panel -->
			<br>
		<?php

		} //end foreach

		?>
		<br>

		<script>
			$('.accordionPlano').on('shown.bs.collapse', function(){
				var i_collapse_ = $(this).attr('id').split("_");
				i_collapse_ = '#i_collapse_'+i_collapse_[1];
				$(i_collapse_).removeClass("fa fa-plus").addClass("fa fa-minus");
			});
			$('.accordionPlano').on('hidden.bs.collapse', function(){
				var i_collapse_ = $(this).attr('id').split("_");
				i_collapse_ = '#i_collapse_'+i_collapse_[1];
				$(i_collapse_).removeClass("fa fa-minus").addClass("fa fa-plus");
			});
		</script>


	<?php

	} else {
		echo '<div style="text-align: center"><h4>Não foram encontrados resultados!</h4></div>';
	}

	echo "</div>";
}

function relatorio_monitoria_individual_texto_desconsiderado($mes, $ano, $usuario, $perfil_sistema, $classificacao, $formulario)
{
	$data_hora = converteDataHora(getDataHora());

	$data_referencia = $ano . '-' . $mes . '-01';
	$legenda_mes_referencia = $mes . '/' . $ano;

	$dados_usuario = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_usuario = '$usuario'", "b.nome");
	$nome_usuario = $dados_usuario[0]['nome'];

	$turno = DBRead('', 'tb_horarios_escala', "WHERE id_usuario = '$usuario' AND data_inicial = '$data_referencia' ", 'inicial_seg, final_seg');

	if ($turno) {
		$inicial_seg = $turno[0]['inicial_seg'];
		$final_seg = $turno[0]['final_seg'];

		if ($inicial_seg > $final_seg) {
			$hora1 = '2000-10-10 ' . $inicial_seg . ':00';
			$hora2 = '2000-10-11 ' . $final_seg . ':00';
			$data1 = date('Y-m-d H:i:s', strtotime("+0 days", strtotime($hora1)));
			$data2 = date('Y-m-d H:i:s', strtotime("+0 days", strtotime($hora2)));
			$resultado = strtotime($data2) - strtotime($data1);

		} else {
			$hora1 = strtotime('' . $inicial_seg . '');
			$hora2 = strtotime('' . $final_seg . '');
			$resultado = ($hora2 - $hora1);
		}

		$h = ($resultado / (60 * 60)) % 24;

		$dados_turno = DBRead('', 'tb_monitoria_mes', "WHERE id_monitoria_mes = $formulario AND data_referencia = '$data_referencia' AND status = 1");

		$soma = 0;
		if ($h >= 5) {
			$soma = $dados_turno[0]['qtd_texto_monitoria_integral'];

		} else {
			$soma = $dados_turno[0]['qtd_texto_monitoria_meio_turno'];
		}
	}

	if ($classificacao == 1) {
		$legenda_classificacao = ' (via Texto - Em Treinamento)';

	} else if ($classificacao == 2) {
		$legenda_classificacao = ' (via Texto - Período de Experiência)';

	} else if ($classificacao == 3) {
		$legenda_classificacao = ' (via Texto - Efetivado)';
	}

	if ($formulario) {
		$dados_monitoria = DBRead('', 'tb_monitoria_avaliacao_texto a', "INNER JOIN tb_usuario b ON a.id_usuario_atendente = b.id_usuario INNER JOIN tb_pessoa c ON b.id_pessoa = c.id_pessoa WHERE a.id_monitoria_mes = $formulario AND a.considerar = 2 ORDER BY a.data_monitoria DESC", 'a.*, c.nome');

	} else {
		$dados_monitoria = '';
	}

	if ($usuario) {
		$nome_usuario_legenda = '<span style="font-size: 14px;"><strong>Atendente:</strong> ' . $nome_usuario . '</span>';
		$filtro_usuario = "AND a.id_usuario_atendente = '$usuario' ";

	} else {
		$nome_usuario_legenda = '<span style="font-size: 14px;"><strong>Atendente:</strong>Todos</span>';
		$filtro_usuario = '';
	}


	$legenda_mes_referencia = '<span style="font-size: 14px;"><strong>Mês referência:</strong> '.$legenda_mes_referencia.'</span>';

	echo "<div class=\"col-md-12\" style=\"padding: 0\">";
	echo "<legend style=\"text-align:center;\"><strong>Relatório de Monitoria - Individual $legenda_classificacao</strong><br><span style=\"font-size: 14px;\"><strong>Gerado em:</strong> $data_hora</span></legend>";
	echo "<legend style=\"text-align:center;\">$legenda_mes_referencia</legend>";
	echo "<legend style=\"text-align:center;\">$nome_usuario_legenda</legend>";

	if ($dados_monitoria) {

		$array_pontos = array();
		$soma_total_percentual = array();
		$tempo_total_avaliacao = array();

		foreach ($dados_monitoria as $conteudo) {

			$id_monitoria_avaliacao_texto = $conteudo['id_monitoria_avaliacao_texto'];
			$id_usuario_analista = $conteudo['id_usuario_analista'];

			$analista = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_usuario = '$id_usuario_analista' ");

			$id_atendimento = $conteudo['id_atendimento'];
			$observacao = $conteudo['obs_avaliacao'];
			$id_erro = $conteudo['id_erro'];

			$dados_atendimento = DBRead('', 'tb_atendimento a', "INNER JOIN tb_situacao_atendimento b ON a.id_atendimento = b.id_atendimento INNER JOIN tb_situacao c ON b.id_situacao = c.id_situacao LEFT JOIN tb_subarea_problema_atendimento d ON a.id_atendimento = d.id_atendimento LEFT JOIN tb_subarea_problema e ON d.id_subarea_problema = e.id_subarea_problema LEFT JOIN tb_area_problema f ON e.id_area_problema = f.id_area_problema WHERE a.id_atendimento = $id_atendimento", "a.*, c.nome, e.descricao as descricao_subarea_problema, f.nome as descricao_area_problema");

			$conteudo_empresa = DBRead('', 'tb_contrato_plano_pessoa a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa INNER JOIN tb_plano c ON a.id_plano = c.id_plano WHERE a.id_contrato_plano_pessoa = '" . $dados_atendimento[0]['id_contrato_plano_pessoa'] . "'", "b.nome AS nome_empresa, a.*, c.*");

			if ($conteudo_empresa[0]['nome_contrato']) {
				$nome_contrato = " (" . $conteudo_empresa[0]['nome_contrato'] . ") ";
			} else {
				$nome_contrato = '';
			}
                
			$contrato = $conteudo_empresa[0]['nome_empresa'] . " " . $nome_contrato;

			$nome_empresa = $contrato;

			if ($dados_atendimento[0]['fone2']) {
				$legenda_telefone = $dados_atendimento[0]['fone1'].' | '.$dados_atendimento[0]['fone1'];
			} else {
				$legenda_telefone = $dados_atendimento[0]['fone1'];
			}

			$situacao_protocolo = DBRead('', 'tb_parametros', "WHERE id_contrato_plano_pessoa = '" . $dados_atendimento[0]['id_contrato_plano_pessoa'] . "'", "exibir_protocolo, solicitacao_cpf");

			if ($situacao_protocolo[0]['solicitacao_cpf'] == 1) {
				if ($dados_atendimento[0]['cpf_cnpj']) {
					$cpf_cnpj = $dados_atendimento[0]['cpf_cnpj'];
				}
			} else {
				$cpf_cnpj = ' - - - - - - - - - - -';
			}
			
			if ($dados_atendimento[0]['descricao_dado_adicional'] && $dados_atendimento[0]['dado_adicional']) {
				$dado_adicional = $dados_atendimento[0]['descricao_dado_adicional'].' - '. $dados_atendimento[0]['dado_adicional'];

			} else {
				$dado_adicional = ' - - - - - - - - - - -';
			}

			if ($situacao_protocolo[0]['exibir_protocolo'] == 1) {
				$protocolo =  $dados_atendimento[0]['protocolo'];
			} else {
				$protocolo = ' - - - - - - - - - - -';
			}

			if ($dados_atendimento[0]['descricao_subarea_problema']) {
				$sub_area_problema = $dados_atendimento[0]['descricao_subarea_problema'];

			} else {
				$sub_area_problema = ' - - - - - - - - - - -';
			}

			if ($dados_atendimento[0]['descricao_area_problema']) {
				$area_problema = $dados_atendimento[0]['descricao_area_problema'];

			} else {
				$area_problema = ' - - - - - - - - - - -';
			}

			$os = $dados_atendimento[0]['os']. PHP_EOL .$dados_atendimento[0]['nome'];

    ?>

			<!-- panel -->
			<div class="panel" style="border-color: #A9A9A9 !important;">
				<div class="panel-heading" style="background-color: #D3D3D3 !important;">

					<div class="row">
						<div class="col-md-3">
							<h3 class="panel-title">Data da monitoria: <strong><?= converteDataHora($conteudo['data_monitoria']) ?></strong>
							</h3>
						</div>
						<div class="col-md-3 text-center">

							<?php if ($perfil_sistema != 3) { ?>

								<h3 class="panel-title">Tempo de avaliação: <strong><?= converteSegundosHoras($conteudo['duracao_avaliacao']) ?></strong>
								</h3>

							<?php } ?>
						</div>
						<div class="col-md-3 text-center">
							<?php if ($perfil_sistema != 3) { ?>
								<h3 class="panel-title text-center">
									<span style="font-size: 15px;">Feita por: <strong><?= $analista[0]['nome'] ?></span></strong>
								</h3>
							<?php } ?>
						</div>
						<div class="col-md-3">
							<h3 class="panel-title pull-right">Operador: <strong><?= $conteudo['nome'] ?></strong></h3>
						</div>
					</div>

				</div>
				<div class="panel-body">

					<!-- table texto responsive -->
					<div class="table-responsive">
						<table class="table table-hover">
							<thead> 
								<tr>
								<th>Data</th>
								<th>Empresa</th>
								<th>Protocolo</th>
								<th>Assinante</th>
								<th>CPF/CNPJ</th>
								<th>Dado adicional</th>
								<th>Contato</th>
								<th>Telefone (1/2)</th>
								<th>Área do problema</th>
								<th>Subarea do problema</th>
								</tr>
							</thead> 
							<tbody>
								<tr>
									<td><?=  converteDataHora($dados_atendimento[0]['data_inicio']) ?></td>
									<td><?= $nome_empresa ?></td>
									<td><?= $protocolo ?></td>
									<td><?= $dados_atendimento[0]['assinante'] ?></td>
									<td><?= $cpf_cnpj ?></td>
									<td><?= $dado_adicional ?></td>
									<td><?= $dados_atendimento[0]['contato'] ?></td>
									<td><?= $legenda_telefone ?></td>
									<td><?= $sub_area_problema ?></td>
									<td><?= $area_problema ?></td>
									<td></td>
								</tr>
							</tbody>
						</table>
					</div>
					<!--end table texto responsive -->

					<br>

					<?php
					if ($id_erro) {

						$dados_erro = DBRead('', 'tb_erro_atendimento a', "INNER JOIN tb_contrato_plano_pessoa b ON a.id_contrato_plano_pessoa = b.id_contrato_plano_pessoa INNER JOIN tb_pessoa c ON b.id_pessoa = c.id_pessoa INNER JOIN tb_tipo_erro d ON a.id_tipo_erro = d.id_tipo_erro INNER JOIN tb_usuario e ON a.id_usuario_cadastrou = e.id_usuario INNER JOIN tb_pessoa f ON e.id_pessoa = f.id_pessoa WHERE a.id_erro_atendimento = '$id_erro' ", 'a.id_erro_atendimento, a.protocolo, a.assinante, a.data_erro, a.hora_erro, a.descricao_cliente, c.nome AS nome_empresa, d.nome AS descricao_erro, f.nome AS usuario_cadastrou');

						if ($dados_erro[0]['protocolo'] == '') {
							$protocolo = 'N/D';
						} else {
							$protocolo = $dados_erro[0]['protocolo'];
						}
					?>
						<!-- panel erro -->
						<div class="panel panel-danger" style="border-color: #FFB6C1 !important;">
							<div class="panel-heading">
								<div class="row">
									<div class="col-md-4" style="padding-left: 7px;">
										<h6 class="panel-title" style="font-size: 14px !important;">
											Reclamação/Erro
										</h6>
									</div>
									<div class="col-md-4">
										<h6 class="panel-title text-center" style="font-size: 14px !important;">
											Protocolo: <?= $dados_erro[0]['protocolo'] ?>
										</h6>
									</div>
									<div class="col-md-4" style="padding-right: 7px;">

									</div>
								</div>
							</div>
							<div class="panel-body">
								<div class="table-responsive">
									<table class="table table-hover" style="margin-bottom: 8px;">
										<thead>
											<tr>
												<th>Tipo erro reclamação</th>
												<th>Criado por</th>
												<th>Empresa</th>
												<th>Assinante</th>
												<th>Data do erro/reclamação</th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td><?= $dados_erro[0]['descricao_erro'] ?></td>
												<td><?= $dados_erro[0]['usuario_cadastrou'] ?></td>
												<td><?= $dados_erro[0]['nome_empresa'] ?></td>
												<td><?= $dados_erro[0]['assinante'] ?></td>
												<td><?= converteData($dados_erro[0]['data_erro']) ?> <?= $dados_erro[0]['hora_erro'] ?></td>
											</tr>
										</tbody>
									</table>
								</div>

								<hr>
								<div class='conteudo-editor' style="margin-left: 7px;">
									<strong>Descrição</strong><br><br>
									<?= $dados_erro[0]['descricao_cliente'] ?>
								</div>
							</div>
						</div>
						<!-- end panel erro -->

					<?php
					}
					?>

					<br>

					<!-- table responsive -->
					<div class="table-responsive">
						<table class="table table-hover table-quesitos" style="border: 1px solid #A9A9A9;">
							<thead>
								<tr style="background-color: #f5f5f5">
									<th class="col-md-6">Quesito</th>
									<th class="col-md-2 text-center">Resultado</th>
									<th class="col-md-2 text-center">Valor do quesito</th>
									<th class="col-md-2 text-center">Valor obtido</th>
								</tr>
							</thead>
							<tbody>

								<?php
								$dados_monitoria_mes = DBRead('', 'tb_monitoria_avaliacao_texto a', "INNER JOIN tb_monitoria_avaliacao_texto_mes b ON a.id_monitoria_avaliacao_texto = b.id_monitoria_avaliacao_texto INNER JOIN tb_monitoria_mes_quesito c ON b.id_monitoria_mes_quesito = c.id_monitoria_mes_quesito INNER JOIN tb_monitoria_quesito d ON c.id_monitoria_quesito = d.id_monitoria_quesito
								INNER JOIN tb_usuario e ON a.id_usuario_analista = e.id_usuario INNER JOIN tb_pessoa f ON e.id_pessoa = f.id_pessoa INNER JOIN tb_monitoria_mes g ON g.id_monitoria_mes = c.id_monitoria_mes WHERE a.id_monitoria_mes = $formulario AND a.id_atendimento = $id_atendimento $filtro_usuario AND g.status = 1 AND a.considerar = 2", 'a.id_erro, a.obs_avaliacao, a.total_pontos, c.pontos_valor, c.porcentagem_plano_acao, b.pontos, d.descricao, d.plano_acao,f.nome as nome_analista, g.soma_total_pontos_quesitos, d.id_monitoria_quesito');
								
								if ($dados_monitoria_mes) {

									$total_pontos_geral = 0;
									$total_pontos_feitos = 0;

									foreach ($dados_monitoria_mes as $conteudo_monitoria) {

										$array_pontos[$conteudo_monitoria['id_monitoria_quesito']]['pontos'] += $conteudo_monitoria['pontos'];

										$array_pontos[$conteudo_monitoria['id_monitoria_quesito']]['qtd'] += 1;

										$array_pontos[$conteudo_monitoria['id_monitoria_quesito']]['pontos_valor'] = $conteudo_monitoria['pontos_valor'];

										$array_pontos[$conteudo_monitoria['id_monitoria_quesito']]['descricao'] = $conteudo_monitoria['descricao'];

										$array_pontos[$conteudo_monitoria['id_monitoria_quesito']]['plano_acao'] = $conteudo_monitoria['plano_acao'];

										$array_pontos[$conteudo_monitoria['id_monitoria_quesito']]['porcentagem_plano_acao'] = $conteudo_monitoria['porcentagem_plano_acao'];

										$observacao = $conteudo_monitoria['obs_avaliacao'];

										$analista = $conteudo_monitoria['nome_analista'];
										$contato = $conteudo_monitoria['nome_contato'];
										$total_pontos = $conteudo_monitoria['pontos_valor'];
										$pontos = $conteudo_monitoria['pontos'];

										$total_pontos_geral += $conteudo_monitoria['pontos_valor'];
										$total_pontos_feitos += $conteudo_monitoria['pontos'];

										$resultado = ($pontos * 100) / $total_pontos;
										$resultado = number_format($resultado, 2, '.', ' ');

										$class_bar = '';
										if ($resultado == 100.00) {
											$span = '<span class="label label-success" style="font-size: 11px;display: inline-block; min-width: 160px;">Atendeu ao quesito <i class="fa fa-thumbs-up pull-right" style="color: white;"></i></span>';
										} else if ($resultado != 100.00) {
											$span = '<span class="label label-danger" style="font-size: 11px;display: inline-block; min-width: 160px;">Não atendeu ao quesito <i class="fa fa-thumbs-down pull-right" style="color: white;"></i></span>';
										}

								?>
										<tr>
											<td><?= $conteudo_monitoria['descricao'] ?></td>
											<td class="text-center">
												<?= $span ?>
											</td>
											<td class="text-center"><?= $total_pontos ?></td>
											<td class="text-center"><?= $pontos ?></td>
										</tr>
								<?php
									}

									$resultado_avalicao = ($total_pontos_feitos * 100) / $total_pontos_geral;

									$soma_total_percentual['resultado'] += $resultado_avalicao;
									$soma_total_percentual['qtd'] += 1;
								}

								?>
							</tbody>
							<tfoot>
								<tr class="success">
									<td><strong>Resultados</strong></td>
									<td class="text-center"><strong><?= number_format($resultado_avalicao, 2, '.', ' '); ?>%</strong></td>
									<td class="text-center"><strong><?= $total_pontos_geral ?></strong></td>
									<td class="text-center"><strong><?= $total_pontos_feitos ?></strong></td>
								</tr>
							</tfoot>
						</table>

					</div>
					<!--end table responsive -->

					<br>

					<!-- table -->

					<?php if ($observacao != '') { ?>
						<table class="table table-hover">
							<thead>
								<tr style="background-color: #f5f5f5">
									<th class="col-md-12">Observação da avaliação</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td><?= $observacao ?></td>
								</tr>
							</tbody>
						</table>
					<?php } ?>
					<!-- end table -->

				</div>
				<!--end panel body -->
			</div>
			<!--end panel -->

			<br>
		<?php

		} //end foreach

		?>
		<br>

	<?php

	} else {
		echo '<div style="text-align: center"><h4>Não foram encontrados resultados!</h4></div>';
	}

	echo "</div>";
}

?>

<script>
	$('.desconsiderar').on('click', function() {

		var id_monitoria_avaliacao_audio = this.id;

		if (!confirm('Tem certeza que deseja desconsiderar este áudio?')) {
			return false;
		} else {

			$.ajax({
				url: "/api/ajax?class=MonitoriaDesconsiderarAudio.php",
				dataType: "json",
				method: 'POST',
				data: {
					parametros: {
						'id_monitoria_avaliacao_audio': id_monitoria_avaliacao_audio
					},
					token: '<?= $request->token ?>'
				},
				success: function(data) {
					alert('Áudio desconsiderado!');
					location.reload();
				}
			});
		}
	});

	$('.desconsiderar_texto').on('click', function() {

		var id_monitoria_avaliacao_texto = this.id;

		if (!confirm('Tem certeza que deseja desconsiderar este atendimento?')) {
			return false;
		} else {

			$.ajax({
				url: "/api/ajax?class=MonitoriaDesconsiderarTexto.php",
				dataType: "json",
				method: 'POST',
				data: {
					parametros: {
						'id_monitoria_avaliacao_texto': id_monitoria_avaliacao_texto
					},
					token: '<?= $request->token ?>'
				},
				success: function(data) {
					alert('atendimento desconsiderado!');
					location.reload();
				}
			});
		}
	});
</script>