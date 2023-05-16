<?php
require_once(__DIR__."/../class/System.php");

$data_hoje = getDataHora();
$data_hoje = explode(" ", $data_hoje);
$data_hoje = $data_hoje[0];
$primeiro_dia = "01/" . $data_hoje[5] . $data_hoje[6] . "/" . $data_hoje[0] . $data_hoje[1] . $data_hoje[2] . $data_hoje[3];

$tipo_relatorio = (!empty($_POST['tipo_relatorio'])) ? $_POST['tipo_relatorio'] : 1;
$data_de = (!empty($_POST['data_de'])) ? $_POST['data_de'] : $primeiro_dia;
$data_ate = (!empty($_POST['data_ate'])) ? $_POST['data_ate'] : converteData(getDataHora('data'));
$busca_contrato = (!empty($_POST['busca_contrato'])) ? $_POST['busca_contrato'] : '';
$operador = (!empty($_POST['operador'])) ? $_POST['operador'] : '';
$falha = (!empty($_POST['falha'])) ? $_POST['falha'] : '';
$faturado = (!empty($_POST['faturado'])) ? $_POST['faturado'] : '';
$resolvido = (!empty($_POST['resolvido'])) ? $_POST['resolvido'] : '';
$id_contrato_plano_pessoa = (!empty($_POST['id_contrato_plano_pessoa'])) ? $_POST['id_contrato_plano_pessoa'] : '';
$situacao = (!empty($_POST['situacao'])) ? $_POST['situacao'] : '';
$id_subarea_problema = (!empty($_POST['id_subarea_problema'])) ? $_POST['id_subarea_problema'] : '';
$id_area_problema = (!empty($_POST['id_area_problema'])) ? $_POST['id_area_problema'] : '';
$tipo_atendimento = (!empty($_POST['tipo_atendimento'])) ? $_POST['tipo_atendimento'] : '';
$minutos_duplicados = (!empty($_POST['minutos_duplicados'])) ? $_POST['minutos_duplicados'] : 15;
$id_plano = (!empty($_POST['id_plano'])) ? $_POST['id_plano'] : '';

$gerar = (!empty($_POST['gerar'])) ? 1 : 0;

$id_usuario = $_SESSION['id_usuario'];
$dados = DBRead('', 'tb_usuario', "WHERE id_usuario = '$id_usuario'");
$perfil_sistema = $dados[0]['id_perfil_sistema'];
$id_asterisk_usuario = $dados[0]['id_asterisk'];

$tipo_grafico = (!empty($_POST['tipo_grafico'])) ? $_POST['tipo_grafico'] : 1;

$envia_email = (!empty($_POST['envia_email'])) ? $_POST['envia_email'] : '';

$desconsidera = (!empty($_POST['desconsidera'])) ? $_POST['desconsidera'] : '';
$canal_atendimento = (!empty($_POST['canal_atendimento'])) ? $_POST['canal_atendimento'] : '';

$turno = (!empty($_POST['turno'])) ? $_POST['turno'] : '';
$lider = (!empty($_POST['lider'])) ? $_POST['lider'] : '';
if($lider != "" && ($tipo_relatorio == 3 || $tipo_relatorio == 16)){
	$operador = "";
}

if ($gerar) {
	$collapse = '';
	$collapse_icon = 'plus';
} else {
	$collapse = 'in';
	$collapse_icon = 'minus';
}

if ($tipo_relatorio == 1 || $tipo_relatorio == 3) {
	$display_row_tipo_atendimento = 'style="display: block;"';
} else {
	$display_row_tipo_atendimento = 'style="display: none;"';
}

if ($tipo_relatorio == 6) {
	$display_row_falha = '';
	$display_row_res_fat = '';
} else {
	$display_row_falha = 'style="display:none;"';
	$display_row_res_fat = 'style="display:none;"';
}

if ($tipo_relatorio == 7 || $tipo_relatorio == 8 || $tipo_relatorio == 15) {
	if($tipo_relatorio == 8){
		$display_row_subarea = '';
	}else{
		$display_row_subarea = 'style="display:none;"';
	}
	$display_row_situacao = '';

} else {
	if($tipo_relatorio == 1 || $tipo_relatorio == 5){
		$display_row_subarea = '';
	}else{
		$display_row_subarea = 'style="display:none;"';
	}
	$display_row_situacao = 'style="display:none;"';

}

if ($tipo_relatorio == 9 || $tipo_relatorio == 13 || $tipo_relatorio == 14 || $tipo_relatorio == 10 || $tipo_relatorio == 18 || $tipo_relatorio == 19) {
	$display_row_operador = 'style="display:none;"';
} else {
	$display_row_operador = '';
}

if ($tipo_relatorio == 10) {
	$display_row_minutos_duplicados = '';
} else {
	$display_row_minutos_duplicados = 'style="display:none;"';
}

if ($tipo_relatorio == 13 || $tipo_relatorio == 14) {
	$display_row_tipo_grafico = '';
	$display_row_canal_atendimento = '';
} else {
	$display_row_tipo_grafico = 'style="display: none;"';
	$display_row_canal_atendimento = 'style="display: none;"';
}

if($tipo_relatorio == 1){
	$display_row_envia_email = '';
}else{
	$display_row_envia_email = 'style="display:none;"';
}

if ($tipo_relatorio == 16) {
	$display_row_contrato = 'style="display:none;"';
} else {
	$display_row_contrato = '';
}

if ($tipo_relatorio == 18) {
	$display_row_desconsidera = '';
} else {
	$display_row_desconsidera = 'style="display:none;"';
}

if($tipo_relatorio == 3 || $tipo_relatorio == 16){
	$display_row_lider = '';
	$display_row_turno = '';
}else{
	$display_row_lider = 'style="display:none;"';
	$display_row_turno = 'style="display:none;"';
}

if ($tipo_relatorio == 5) {
	$display_row_plano = '';
} else {
	$display_row_plano = 'style="display:none;"';
}

if ($id_contrato_plano_pessoa) {
	$dados_contrato = DBRead('', 'tb_contrato_plano_pessoa a', "INNER JOIN tb_plano b ON a.id_plano = b.id_plano INNER JOIN tb_pessoa c ON a.id_pessoa = c.id_pessoa WHERE a.id_contrato_plano_pessoa = '$id_contrato_plano_pessoa'", "a.*, b.cod_servico, b.nome AS 'plano', c.nome AS 'nome_pessoa'");

	if ($dados_contrato[0]['nome_contrato']) {
		$nome_contrato = " (" . $dados_contrato[0]['nome_contrato'] . ") ";
	}

	$contrato = $dados_contrato[0]['nome_pessoa'] . " " . $nome_contrato . " - " . getNomeServico($dados_contrato[0]['cod_servico']) . " - " . $dados_contrato[0]['plano'] . " (" . $dados_contrato[0]['id_contrato_plano_pessoa'] . ")";
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
</style>

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs/jszip-2.5.0/dt-1.10.18/b-1.5.4/b-colvis-1.5.4/b-flash-1.5.4/b-html5-1.5.4/b-print-1.5.4/r-2.2.2/datatables.min.css" />
<script type="text/javascript" src="https://cdn.datatables.net/v/bs/jszip-2.5.0/dt-1.10.18/b-1.5.4/b-colvis-1.5.4/b-flash-1.5.4/b-html5-1.5.4/b-print-1.5.4/r-2.2.2/datatables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/plug-ins/1.10.19/sorting/time.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/plug-ins/1.10.19/sorting/chinese-string.js"></script>
<script src="https://code.highcharts.com/7.2.1/highcharts.js"></script>
<script src="https://code.highcharts.com/7.2.1/modules/exporting.js"></script>
<script src="https://code.highcharts.com/7.2.1/modules/export-data.js"></script>

<div class="container-fluid">
	<form method="post" action="">
		<div class="row">
			<div class="col-md-4 col-md-offset-4">
				<div class="panel panel-default noprint">
					<div class="panel-heading clearfix">
						<h3 class="panel-title text-left pull-left" style="margin-top: 2px;">Relatório - Atendimentos - Call Center:</h3>
						<div class="panel-title text-right pull-right"><button data-toggle="collapse" data-target="#accordionRelatorio" class="btn btn-xs btn-info" type="button" title="Visualizar filtros"><i id="i_collapse" class="fa fa-<?= $collapse_icon ?>"></i></button></div>
					</div>
					<div id="accordionRelatorio" class="panel-collapse collapse <?= $collapse ?>">
						<div class="panel-body">
							<div class="row">
								<div class="col-md-12">
									<div class="form-group">
										<label for="">Tipo de Relatório:</label>
										<select name="tipo_relatorio" id="tipo_relatorio" class="form-control input-sm">
											<?php
											if ($perfil_sistema != '3' && $perfil_sistema != '28') {
											?>
												<option value="4" <?php if ($tipo_relatorio == '4') {echo 'selected';} ?>>Atendimento Encantador</option>
												<option value="17" <?php if ($tipo_relatorio == '17') {echo 'selected';} ?>>Atendimento por Chat (Empresa)</option>
												<option value="16" <?php if ($tipo_relatorio == '16') {echo 'selected';} ?>>Atendimento por Chat (Operador)</option>
												<option value="11" <?php if ($tipo_relatorio == '11') {echo 'selected';} ?>>Auditoria</option>
												<option value="3" <?php if ($tipo_relatorio == '3') {echo 'selected';} ?>>Contagem</option>
												<option value="12" <?php if ($tipo_relatorio == '12') {echo 'selected';} ?>>Clientes Irritados</option>
												<option value="18" <?php if ($tipo_relatorio == '18') {echo 'selected';} ?>>Desconsiderados</option>
												<option value="10" <?php if ($tipo_relatorio == '10') {echo 'selected';} ?>>Duplicados</option>
                                                <option value="6" <?php if ($tipo_relatorio == '6') {echo 'selected';} ?>>Falhas</option>
                                            <?php
											}
											?>
                                                <option value="1" <?php if ($tipo_relatorio == '1') {echo 'selected';} ?>>Faturados</option>
                                            <?php
											if ($perfil_sistema != '3' && $perfil_sistema != '28') {
											?>
												<option value="9" <?php if ($tipo_relatorio == '9') {echo 'selected';} ?>>Faturados (Tabela)</option>
												<option value="13" <?php if ($tipo_relatorio == '13') {echo 'selected';} ?>>Gráfico de Atendimentos por Hora - Separado por Dia do Mês</option>
												<option value="14" <?php if ($tipo_relatorio == '14') {echo 'selected';} ?>>Gráfico de Atendimentos por Hora - Acumulado por Dia da Semana</option>
												<option value="2" <?php if ($tipo_relatorio == '2') {echo 'selected';} ?>>Não Faturados</option>

											<?php
											}
											?>
											<option value="5" <?php if ($tipo_relatorio == '5') {echo 'selected';} ?>>Resolução</option>
											<?php
											if ($perfil_sistema != '3' && $perfil_sistema != '28') {
											?>
												<option value="7" <?php if ($tipo_relatorio == '7') {echo 'selected';} ?>>Situações</option>
												<option value="15" <?php if ($tipo_relatorio == '15') {echo 'selected';} ?>>Situações (Porcentagens)</option>
												<option value="8" <?php if ($tipo_relatorio == '8') {echo 'selected';} ?>>Subáreas e Áreas de Finalização</option>
												
											<?php 
											}
											?>

												<option value="19" <?php if($tipo_relatorio == '19'){echo 'selected';}?>>Médias de Atendimentos de Chat por Empresa</option>

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

							<div class="row" id="row_tipo_atendimento" <?= $display_row_tipo_atendimento ?>>
								<div class="col-md-12">
									<div class="form-group">
										<label for="">Tipo do atendimento</label>
										<select class="form-control input-sm" name="tipo_atendimento" id="tipo_atendimento">
											<?php
												$sel_tipo_atendimento[$tipo_atendimento] = 'selected';
											?>
											<option value="" <?= $sel_tipo_atendimento[''] ?>>Todos</option>
											<option value="1" <?= $sel_tipo_atendimento['1'] ?>>Via telefone</option>
											<option value="2" <?= $sel_tipo_atendimento['2'] ?>>Via texto</option>
										</select>
									</div>
								</div>
							</div>

							<div class="row" id="row_periodo">
								<div class="col-md-6">
									<div class="form-group">
										<label>*Data Inicial:</label>
										<input type="text" class="form-control input-sm date calendar" name="data_de" value="<?= $data_de ?>" required>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label>*Data Final:</label>
										<input type="text" class="form-control input-sm date calendar" name="data_ate" value="<?= $data_ate ?>" required>
									</div>
								</div>
							</div>

							<div class="row" id="row_falha" <?= $display_row_falha ?>>
								<div class="col-md-12">
									<div class="form-group">
										<label for="">Falha:</label>
										<select name="falha" class="form-control input-sm">
											<option value="">Todos</option>
											<?php
											$dados_falha = DBRead('', 'tb_tipo_falha_atendimento', "WHERE status = 1 ORDER BY opcao");
											if ($dados_falha) {
												foreach ($dados_falha as $conteudo_falha) {
													$selected = $falha == $conteudo_falha['id_tipo_falha_atendimento'] ? "selected" : "";
													if ($conteudo_falha['exibicao'] == 1) {
														$exibicao_falha = 'Início do atendimento';
													} else if ($conteudo_falha['exibicao'] == 2) {
														$exibicao_falha = 'Durante o atendimento';
													} else if ($conteudo_falha['exibicao'] == 3) {
														$exibicao_falha = 'Ambas as telas';
													}
													echo "<option value='" . $conteudo_falha['id_tipo_falha_atendimento'] . "' ".$selected.">" . $conteudo_falha['opcao'] . "  (" . $exibicao_falha . ")</option>";
												}
											}

											?>
										</select>
									</div>
								</div>
							</div>

							<div class="row" id="row_res_fat" <?= $display_row_res_fat ?>>
								<div class="col-md-6">
									<div class="form-group">
										<label for="">Faturados:</label>
										<select name="faturado" id="faturado" class="form-control input-sm">
											<option value="" <?php if ($faturado == '0') {
																	echo 'selected';
																} ?>>Todos</option>
											<option value="1" <?php if ($faturado == '1') {
																	echo 'selected';
																} ?>>Sim</option>
											<option value="2" <?php if ($faturado == '2') {
																	echo 'selected';
																} ?>>Não</option>
										</select>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label for="">Resolvido:</label>
										<select name="resolvido" id="resolvido" class="form-control input-sm">
											<option value="" <?php if ($resolvido == '') {
																	echo 'selected';
																} ?>>Todos</option>
											<option value="1" <?php if ($resolvido == '1') {
																	echo 'selected';
																} ?>>Resolvido</option>
											<option value="2" <?php if ($resolvido == '2') {
																	echo 'selected';
																} ?>>Não Resolvido</option>
											<option value="3" <?php if ($resolvido == '3') {
																	echo 'selected';
																} ?>>Diagnosticado</option>
										</select>
									</div>
								</div>
							</div>

							<div class="row" id="row_situacao" <?= $display_row_situacao ?>>
								<div class="col-md-12">
									<div class="form-group">
										<label for="">Situação:</label>
										<select name="situacao" class="form-control input-sm">
											<option value="">Todas</option>
											<?php
											$dados_situacao = DBRead('', 'tb_situacao', "ORDER BY nome");
											if ($dados_situacao) {
												foreach ($dados_situacao as $conteudo_situacao) {
													$selected = $situacao == $conteudo_situacao['id_situacao'] ? "selected" : "";
													echo "<option value='" . $conteudo_situacao['id_situacao'] . "' ".$selected.">" . ucwords(strtolower($conteudo_situacao['nome']))	. "</option>";
												}
											}
											?>
										</select>
									</div>
								</div>
							</div>

							<div class="row" id="row_minutos_duplicados" <?= $display_row_minutos_duplicados ?>>
								<div class="col-md-12">
									<div class="form-group">
										<label>*Tempo Duplicados (minutos):</label>
										<select name="minutos_duplicados" id="minutos_duplicados" class="form-control input-sm">
											<?php
											$sel_minutos_duplicados[$minutos_duplicados] = 'selected';
											echo "<option value='15'" . $sel_minutos_duplicados['15'] . " id = 'option_minutos_duplicados'>15</option>";
											?>
										</select>
									</div>
								</div>
							</div>

							<div class="row" id="row_subarea" <?= $display_row_subarea ?>>
								<div class="col-md-6">
									<div class="form-group">
										<label>Área do problema:</label>
										<select class="form-control input-sm" name="id_area_problema" id="id_area_problema">
											<option value="">Todos</option>
											<?php
											$dados = DBRead('', 'tb_area_problema', "ORDER BY nome ASC");
											if ($dados) {
												foreach ($dados as $conteudo) {
													$idSelect = $conteudo['id_area_problema'];
													$nomeSelect = $conteudo['nome']; 
													$selected = $id_area_problema == $idSelect ? "selected" : "";
													echo "<option value='$idSelect'" .$selected.">$nomeSelect</option>";
												}
											}
											?>

											<option value="nao_identificada" <?php if ($id_area_problema == 'nao_identificada') {echo 'selected';} ?>>Atendimento Incompleto</option>

										</select>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label>Subárea do problema:</label>
										<select class="form-control input-sm" name="id_subarea_problema" id="id_subarea_problema">
											<?php
											if ($id_area_problema) {
												if ($id_area_problema == 'nao_identificada') {
													echo "<option value=''>Não identificada</option>";
												} else {
													echo '<option value="">Todos</option>';
													$dados = DBRead('', 'tb_subarea_problema', "WHERE id_area_problema = '$id_area_problema' ORDER BY descricao ASC");
													if ($dados) {
														foreach ($dados as $conteudo) {
															$idSelect = $conteudo['id_subarea_problema'];
															$descricaoSelect = $conteudo['descricao'];
															$selected = $id_subarea_problema == $idSelect ? "selected" : "";
															echo "<option value='$idSelect'" .$selected.">$descricaoSelect</option>";
														}
													}
												}
											} else {
												echo '<option value="">Selecione uma área do problema antes!</option>';
											}
											?>
										</select>
									</div>
								</div>

							</div>

							<?php if ($perfil_sistema != '3' && $perfil_sistema != '28') { ?>
								<div class="row" id="row_operador" <?= $display_row_operador ?>>
									<div class="col-md-12">
										<div class="form-group">
											<label for="">Atendente:</label>
											<select name="operador" class="form-control input-sm">
												<option value="">Todos</option>
												<?php
												$dados_operadores = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE (a.id_perfil_sistema = 3 OR a.id_perfil_sistema = 15 OR a.id_perfil_sistema = 28) AND a.status = 1 ORDER BY b.nome");
												if ($dados_operadores) {
													foreach ($dados_operadores as $conteudo_operadores) {
														$selected = $operador == $conteudo_operadores['id_usuario'] ? "selected" : "";
														echo "<option value='" . $conteudo_operadores['id_usuario'] . "' ".$selected.">" . $conteudo_operadores['nome'] . "</option>";
													}
												}
												?>
											</select>
										</div>
									</div>
								</div>
							<?php } ?>

							<div class="row" id="row_plano"<?= $display_row_plano ?>>
									<div class="col-md-12">
										<div class="form-group">
											<label for="">Plano:</label>
											<select name="id_plano" id="id_plano" class="form-control input-sm">
												<option value="">Todos</option>
												<?php
												$dados_plano = DBRead('', 'tb_plano', "WHERE status = '1' AND cod_servico = 'call_suporte' ORDER BY nome");
												if ($dados_plano) {
													foreach ($dados_plano as $conteudo_plano) {
														$selected = $plano == $conteudo_plano['id_plano'] ? "selected" : "";
														echo '<option value="'.$conteudo_plano['id_plano'].'" '.$selected.'>'.$conteudo_plano['nome'].'</option>';
													}
												}
												?>
											</select>
										</div>
									</div>
								</div>

							<?php if($perfil_sistema != '3' && $perfil_sistema != '28'){ ?>
                			<div class="row" id="row_tipo_grafico" <?=$display_row_tipo_grafico?>>
                				<div class="col-md-12">
                					<div class="form-group">
								        <label for="">Tipo do gráfico:</label>
								        <select name="tipo_grafico" id="tipo_grafico" class="form-control input-sm">
								        	<option value="1" <?php if($tipo_grafico == '1'){ echo 'selected';}?>>Linhas</option>	
								        	<option value="2" <?php if($tipo_grafico == '2'){ echo 'selected';}?>>Colunas</option>
								        </select>
								    </div>
                				</div>
							</div>
							<?php }?>

							<div class="row" id="row_envia_email" <?=$display_row_envia_email?>>
                				<div class="col-md-12">
                					<div class="form-group">
								        <label for="">Email enviados:</label>
								        <select name="envia_email" id="envia_email" class="form-control input-sm">
											<option value="" <?php if($envia_email == ''){ echo 'selected';}?>>Todos</option>	
											<option value="1" <?php if($envia_email == '1'){ echo 'selected';}?>>Somente os que foram enviados e-mails</option>	
								        	<option value="2" <?php if($envia_email == '2'){ echo 'selected';}?>>Somente os que não foram enviados e-mails</option>
								        </select>
								    </div>
                				</div>
							</div>

							<div class="row" id="row_desconsidera" <?=$display_row_desconsidera?>>
                				<div class="col-md-12">
                					<div class="form-group">
								        <label>Desconsiderado por:</label>
								        <select name="desconsidera" id="desconsidera" class="form-control input-sm">
											<option value="">Todos</option>
											<?php
											$dados_desconsidera = DBRead('', 'tb_desconsiderar a', "INNER JOIN tb_usuario b ON a.id_usuario = b.id_usuario INNER JOIN tb_pessoa c ON b.id_pessoa = c.id_pessoa GROUP BY a.id_usuario ORDER BY c.nome ASC", "a.id_usuario, c.nome");
											if ($dados_desconsidera) {
												foreach ($dados_desconsidera as $conteudo_desconsidera) {
													$selected = $desconsidera == $conteudo_desconsidera['id_usuario'] ? "selected" : "";
													echo "<option value='".$conteudo_desconsidera['id_usuario']."' ".$selected.">".$conteudo_desconsidera['nome']."</option>";
												}
											}
											?>
								        </select>
								    </div>
                				</div>
							</div>

							<div class="row" id="row_lider" <?=$display_row_lider?>>
								<div class="col-md-12">
									<div class="form-group">
										<label for="">Líder Direto:</label>
										<select name="lider" class="form-control input-sm">
											<option value="" <?php if($lider == ''){ echo 'selected';}?>>Todos</option>
											<?php
											$dados_lider = DBRead('', 'tb_usuario a', "INNER JOIN tb_usuario b ON a.lider_direto = b.id_usuario INNER JOIN tb_pessoa c ON b.id_pessoa = c.id_pessoa WHERE a.lider_direto AND a.status = '1' AND b.id_perfil_sistema = '13' GROUP BY  a.lider_direto, c.nome ORDER BY c.nome ASC", "a.lider_direto, c.nome");
											// AND b.id_perfil_sistema = '13'
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
							</div>

							<div class="row" id="row_turno" <?=$display_row_turno?>>
								<div class="col-md-12">
                                    <div class="form-group">
                                        <label>Turno/Horário:</label> 
                                        <select name="turno" id="turno" class="form-control input-sm">
                                            <?php
                                            $turnos = array(
                                                "" => "Todos",
                                                "integral" => "Integral",
                                                "meio" => "Meio Turno",
                                                "jovem" => "Jovem Aprendiz",
                                                "estagio" => "Estágio"
                                            );
                                            foreach ($turnos as $value => $tur) {
												$selected = $turno == $value ? "selected" : "";
                                                echo "<option value='".$value."'".$selected.">".$tur."</option>";
                                            }
                                            ?>      
                                        </select>                                   
                                    </div>
								</div>       
							</div> 

							<div class="row" id="row_canal_atendimento" <?=$display_row_canal_atendimento?>>
								<div class="col-md-12">
									<div class="form-group">
										<label>Canal de Atendimento:</label>
										<select class="form-control input-sm" name="canal_atendimento" id="canal_atendimento">
											<option value='' <?php if($canal_atendimento == ''){ echo 'selected';}?>>Qualquer</option>                                    
											<option value='telefone' <?php if($canal_atendimento == 'telefone'){ echo 'selected';}?>>Telefone</option>
											<option value='texto' <?php if($canal_atendimento == 'texto'){ echo 'selected';}?>>Texto</option>
										</select>
									</div>
								</div>
							</div>

						</div>
					</div>
					<div class="panel-footer">
						<div class="row">
							<div id="panel_buttons" class="col-md-12" style="text-align: center">
								<button class="btn btn-primary" name="gerar" id="gerar" value="1" type="submit" disabled><i class="fa fa-refresh"></i> Gerar</button>
								<button class="btn btn-warning" name="imprimir" type="button" onclick="window.print();"><i class="fa fa-print"></i> Imprimir</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</form>
	<div id="aguarde" class="alert alert-info text-center">Aguarde, gerando relatório... <i class="fa fa-spinner faa-spin animated"></i></div>
	<div id="resultado" class="row" style="display:none;">
		<?php
		if ($gerar) {

			if ($tipo_relatorio == 1) {
                if ($perfil_sistema == 3 || $perfil_sistema == 28) {
					$operador = $id_usuario;
				}
                relatorio_faturados($id_contrato_plano_pessoa, $data_de, $data_ate, $operador, $tipo_atendimento, $envia_email, $id_area_problema, $id_subarea_problema);
                
			} else if ($tipo_relatorio == 2 && $perfil_sistema != 3 && $perfil_sistema != 28) {
				relatorio_nao_faturados($id_contrato_plano_pessoa, $data_de, $data_ate, $operador);

			} else if ($tipo_relatorio == 3 && $perfil_sistema != 3 && $perfil_sistema != 28) {
				if($lider != ""){
					$operador = '';
				}

				relatorio_contagem($id_contrato_plano_pessoa, $data_de, $data_ate, $operador, $tipo_atendimento, $turno, $lider);

			} else if ($tipo_relatorio == 4 && $perfil_sistema != 3 && $perfil_sistema != 28) {
				relatorio_elogios($id_contrato_plano_pessoa, $data_de, $data_ate, $operador);

			} else if ($tipo_relatorio == 5) {
				if ($perfil_sistema == 3 || $perfil_sistema == 28) {
					$operador = $id_usuario;
				}
				relatorio_resolucao($id_contrato_plano_pessoa, $data_de, $data_ate, $operador, $id_area_problema, $id_subarea_problema, $id_plano);

			} else if ($tipo_relatorio == 6 && $perfil_sistema != 3 && $perfil_sistema != 28) {
				relatorio_falhas($id_contrato_plano_pessoa, $data_de, $data_ate, $operador, $falha, $faturado, $resolvido);

			} else if ($tipo_relatorio == 7 && $perfil_sistema != 3 && $perfil_sistema != 28) {
				relatorio_situacao($id_contrato_plano_pessoa, $data_de, $data_ate, $operador, $situacao);

			} else if ($tipo_relatorio == 8 && $perfil_sistema != 3 && $perfil_sistema != 28) {
				relatorio_subarea($id_contrato_plano_pessoa, $data_de, $data_ate, $operador, $id_area_problema, $id_subarea_problema, $situacao);

			} else if ($tipo_relatorio == 9 && $perfil_sistema != 3 && $perfil_sistema != 28) {
				relatorio_faturados_tabela($id_contrato_plano_pessoa, $data_de, $data_ate);

			} else if ($tipo_relatorio == 10 && $perfil_sistema != 3 && $perfil_sistema != 28) {
				relatorio_duplicados($id_contrato_plano_pessoa, $data_de, $data_ate, $minutos_duplicados);

			} else if ($tipo_relatorio == 11 && $perfil_sistema != 3 && $perfil_sistema != 28) {
				relatorio_faturados_auditoria($id_contrato_plano_pessoa, $data_de, $data_ate, $operador, $request);

			} else if ($tipo_relatorio == 12 && $perfil_sistema != 3 && $perfil_sistema != 28) {
				relatorio_irritados($id_contrato_plano_pessoa, $data_de, $data_ate, $operador);

			}else if ($tipo_relatorio == 13 && $perfil_sistema != 3 && $perfil_sistema != 28) {
				relatorio_grafico_hora($data_de, $data_ate, $id_contrato_plano_pessoa, $tipo_grafico, $canal_atendimento);

			} else if ($tipo_relatorio == 14 && $perfil_sistema != 3 && $perfil_sistema != 28) {
				relatorio_grafico_hora_acumulado_semana($data_de, $data_ate, $id_contrato_plano_pessoa, $tipo_grafico, $canal_atendimento);

			} else if ($tipo_relatorio == 15 && $perfil_sistema != 3 && $perfil_sistema != 28) {
				relatorio_situacao_porcentagem($id_contrato_plano_pessoa, $data_de, $data_ate, $operador, $situacao);

			} else if ($tipo_relatorio == 16 && $perfil_sistema != 3 && $perfil_sistema != 28) {
				if($lider != ""){
					$operador = '';
				}
				relatorio_chat_operador($data_de, $data_ate, $operador, $turno, $lider);

			} else if ($tipo_relatorio == 17 && $perfil_sistema != 3 && $perfil_sistema != 28) {
				relatorio_chat_empresa($data_de, $data_ate, $id_contrato_plano_pessoa);

			}else if ($tipo_relatorio == 18 && $perfil_sistema != 3 && $perfil_sistema != 28) {
				relatorio_desconsiderados($data_de, $data_ate, $id_contrato_plano_pessoa, $desconsidera, $request);

			} else if ($tipo_relatorio == 19) {
                relatorio_media_chat_por_empresa($id_contrato_plano_pessoa, $data_de, $data_ate);
			}
            
		}
		?>
	</div>
</div>
<script>
	function selectAreaSubareaProblema(id_area_problema) {

		$("select[name=id_subarea_problema]").html('<option value="">Carregando...</option>');
		$.post("/api/ajax?class=SelectAreaSubareaProblema.php", {
				area_problema: id_area_problema,
				token: '<?= $request->token ?>'
			},
			function(valor) {
				$("select[name=id_subarea_problema]").html(valor);
			}
		)
	}

	$(document).on('change', 'select[name=id_area_problema]', function() {
		selectAreaSubareaProblema($(this).val());
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

	$(document).ready(function() {
		$('#aguarde').hide();
		$('#resultado').show();
		$("#gerar").prop("disabled", false);
	});

	$('#tipo_relatorio').on('change', function() {
		tipo_relatorio = $(this).val();

		if (tipo_relatorio == 1 || tipo_relatorio == 3) {
			$('#row_tipo_atendimento').show();
		} else {
			$('#row_tipo_atendimento').hide();
		}

		if (tipo_relatorio == 6) {
			$('#row_falha').show();
			$('#row_res_fat').show();
		} else {
			$('#row_falha').hide();
			$('#row_res_fat').hide();
		}

		if (tipo_relatorio == 7 || tipo_relatorio == 8 || tipo_relatorio == 15) {
			if(tipo_relatorio == 8){
				$('#row_subarea').show();
			} else {
				$('#row_subarea').hide();
			}
			$('#row_situacao').show();
		}else{
			if (tipo_relatorio == 1 || tipo_relatorio == 5){
				$('#row_subarea').show();
			}else{
				$('#row_subarea').hide();
			}
			$('#row_situacao').hide();
		}

		if (tipo_relatorio == 9 || tipo_relatorio == 13 || tipo_relatorio == 14 || tipo_relatorio == 10 || tipo_relatorio == 18 || tipo_relatorio == 19) {

			$('#row_operador').hide();
		} else {
			$('#row_operador').show();
		}

		if (tipo_relatorio == 10) {
			$('#row_minutos_duplicados').show();
		} else {
			$('#row_minutos_duplicados').hide();
		}

		if (tipo_relatorio == 13 || tipo_relatorio == 14) {
			$('#row_tipo_grafico').show();
			$('#row_canal_atendimento').show();
		} else {
			$('#row_tipo_grafico').hide();
			$('#row_canal_atendimento').hide();
		}

		if (tipo_relatorio == 1) {
			$('#row_envia_email').show();
		} else {
			$('#row_envia_email').hide();
		}

		if (tipo_relatorio == 16) {
			$('#row_contrato').hide();
		}

		if (tipo_relatorio == 17) {
			$('#row_contrato').show();
			$('#row_operador').hide();
		}

		if (tipo_relatorio == 18) {
			$('#row_contrato').show();
			$('#row_operador').hide();
			$('#row_desconsidera').show();
		}else{
			$('#row_desconsidera').hide();
		}

		if (tipo_relatorio == 3 || tipo_relatorio == 16) {
			$('#row_turno').show();
			$('#row_lider').show();
		} else {
			$('#row_turno').hide();
			$('#row_lider').hide();
		}

		if (tipo_relatorio == 5) {
			$('#row_plano').show();
		} else {
			$('#row_plano').hide();
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

	function seleciona_contrato(id_contrato_plano_pessoa) {
		$.ajax({
			type: "GET",
			url: "/api/ajax?class=ArvoreContratoBusca.php",
			dataType: "json",
			data: {
				id_contrato_plano_pessoa: id_contrato_plano_pessoa,
				token: '<?= $request->token ?>'
			},

		});
	};

	$(document).on('click', '#gerar', function() {
		var busca = $('#busca_contrato').val();
		var tipo_relatorio = $('#tipo_relatorio').val();
		if (!busca && tipo_relatorio == 9) {
			alert("Deve-se selecionar um contrato!");
			return false;
		}
		modalAguarde();
	});
</script>

<?php
function relatorio_media_chat_por_empresa ($id_contrato_plano_pessoa, $data_de, $data_ate) {

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
		$filtro_contrato_plano_pessoa = '';
	}

	if($data_de && $data_ate){
	    $periodo_amostra ="<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> De $data_de até $data_ate</span>";
	}elseif($data_de){
	    $periodo_amostra ="<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> A partir de $data_de</span>";
	}elseif($data_ate){
	    $periodo_amostra ="<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> Até $data_ate</span>";
	}else{
	    $periodo_amostra = "";
	}

    $gerado = "<span style=\"font-size: 14px;\"><strong>Gerado em: </strong> ".converteDataHora($data_hoje)."</span>";

    echo "<div class=\"col-md-10 col-md-offset-1\" style=\"padding: 0\">";
    echo "<legend style=\"text-align:center;\"><strong>Relatório de Médias de Atendimentos de Chat por Empresa</strong><br><span style=\"font-size: 14px;\">$gerado</legend>";
	echo "<legend style=\"text-align:center;\">$periodo_amostra</legend>";
    echo "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong> Contrato - </strong>" . $empresa_legenda . "</legend>";
    echo '</legend>';

    $dados = DBRead('', 'tb_contrato_plano_pessoa a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa INNER JOIN tb_parametros c ON a.id_contrato_plano_pessoa = c.id_contrato_plano_pessoa INNER JOIN tb_cidade d ON b.id_cidade = d.id_cidade WHERE c.atendimento_via_texto = 1 $filtro_contrato_plano_pessoa", "a.id_contrato_plano_pessoa, b.nome, b.id_cidade, d.id_estado");

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

					<div class='col-md-12'>
						<table class='table table-bordered'>
							<tbody>
							<thead>
								<tr>
									<th class="text-left col-md-6">Dia(s) e horário(s)</th>
									<th class="text-left col-md-2">Total de Atendimentos</th>
									<th class="text-left col-md-2">Qtd. de Dias</th>
									<th class="text-left col-md-2">Média de Atendimentos</th>
								</tr>
							</thead>
							<?php
								$qtd_dias_total = 0;
								$atendimentos_total = 0;
								foreach ($horarios as $hora) {
									$hora_inicio = explode(':', $hora['hora_inicio']);
									$hora_inicio = $hora_inicio[0].":".$hora_inicio[1];
									$hora_fim = explode(':', $hora['hora_fim']);
									$complemento = "59";
									if($hora_fim[1] == '00'){
										$complemento = "00";
									}
									$hora_fim = $hora_fim[0].":".$hora_fim[1];

									$qtd_dias = 0;
									$filtro_data = '';
									foreach (rangeDatas(converteData($data_de), converteData($data_ate)) as $data) {	
										$numero_dia_semana = date('w', strtotime($data));
										if($tipo_dia_atendimento[$hora['dia']] == "Seg. a Dom."){
											$qtd_dias ++;
											if($filtro_data == ''){
												// $filtro_data .= " (data_inicio BETWEEN '".$data." ".$hora_inicio.":00' AND '".$data." ".$hora_fim.":".$complemento."')";
												$filtro_data .= " (data_inicio LIKE '%".$data."%')";
											}else{
												// $filtro_data .= " OR (data_inicio BETWEEN '".$data." ".$hora_inicio.":00' AND '".$data." ".$hora_fim.":".$complemento."')";
												$filtro_data .= " OR (data_inicio LIKE '%".$data."%')";
											}
										}else if($tipo_dia_atendimento[$hora['dia']] == "Seg. a Sáb."){
											if($numero_dia_semana != 0){
												$qtd_dias ++;
												if($filtro_data == ''){
													$filtro_data .= " (data_inicio LIKE '%".$data."%')";
												}else{
													$filtro_data .= " OR (data_inicio LIKE '%".$data."%')";
												}
											}
										}else if($tipo_dia_atendimento[$hora['dia']] == "Seg. a Sex."){
											if($numero_dia_semana != 0 && $numero_dia_semana != 6){
												$qtd_dias ++;
												if($filtro_data == ''){
													$filtro_data .= " (data_inicio LIKE '%".$data."%')";
												}else{
													$filtro_data .= " OR (data_inicio LIKE '%".$data."%')";
												}
											}

										}else if($tipo_dia_atendimento[$hora['dia']] == "Dom. e Feriados"){
											$data_complementar = explode('-', $data);
											$data_complementar = $data_complementar[1]."-".$data_complementar[2];

											$feriado_nacional = DBRead('', 'tb_feriado', "WHERE tipo = 'Nacional' AND data = '".$data_complementar."'");
											$feriado_estadual = DBRead('', 'tb_feriado', "WHERE tipo = 'Estadual' AND id_estado = '".$conteudo['id_estado']."' AND data = '".$data_complementar."'");
											$feriado_municipal = DBRead('', 'tb_feriado', "WHERE tipo = 'Municipal' AND id_cidade = '".$conteudo['id_cidade']."' AND data = '".$data_complementar."'");

											if($numero_dia_semana == 0 || ($feriado_nacional || $feriado_estadual || $feriado_municipal)){
												$qtd_dias ++;
												if($filtro_data == ''){
													$filtro_data .= " (data_inicio LIKE '%".$data."%')";
												}else{
													$filtro_data .= " OR (data_inicio LIKE '%".$data."%')";
												}
											}
										}else if($tipo_dia_atendimento[$hora['dia']] == "Feriados"){
											$data_complementar = explode('-', $data);
											$data_complementar = $data_complementar[1]."-".$data_complementar[2];

											$feriado_nacional = DBRead('', 'tb_feriado', "WHERE tipo = 'Nacional' AND data = '".$data_complementar."'");
											$feriado_estadual = DBRead('', 'tb_feriado', "WHERE tipo = 'Estadual' AND id_estado = '".$conteudo['id_estado']."' AND data = '".$data_complementar."'");
											$feriado_municipal = DBRead('', 'tb_feriado', "WHERE tipo = 'Municipal' AND id_cidade = '".$conteudo['id_cidade']."' AND data = '".$data_complementar."'");

											if($feriado_nacional || $feriado_estadual || $feriado_municipal){
												$qtd_dias ++;
												if($filtro_data == ''){
													$filtro_data .= " (data_inicio LIKE '%".$data."%')";
												}else{
													$filtro_data .= " OR (data_inicio LIKE '%".$data."%')";
												}
											}

										}else if($tipo_dia_atendimento[$hora['dia']] == "Domingo"){
											if($numero_dia_semana == 0){
												$qtd_dias ++;
												if($filtro_data == ''){
													$filtro_data .= " (data_inicio LIKE '%".$data."%')";
												}else{
													$filtro_data .= " OR (data_inicio LIKE '%".$data."%')";
												}
											}
										}else if($tipo_dia_atendimento[$hora['dia']] == "Segunda"){
											if($numero_dia_semana == 1){
												$qtd_dias ++;
												if($filtro_data == ''){
													$filtro_data .= " (data_inicio LIKE '%".$data."%')";
												}else{
													$filtro_data .= " OR (data_inicio LIKE '%".$data."%')";
												}
											}
										}else if($tipo_dia_atendimento[$hora['dia']] == "Terça"){
											if($numero_dia_semana == 2){
												$qtd_dias ++;
												if($filtro_data == ''){
													$filtro_data .= " (data_inicio LIKE '%".$data."%')";
												}else{
													$filtro_data .= " OR (data_inicio LIKE '%".$data."%')";
												}
											}
										}else if($tipo_dia_atendimento[$hora['dia']] == "Quarta"){
											if($numero_dia_semana == 3){
												$qtd_dias ++;
												if($filtro_data == ''){
													$filtro_data .= " (data_inicio LIKE '%".$data."%')";
												}else{
													$filtro_data .= " OR (data_inicio LIKE '%".$data."%')";
												}
											}
										}else if($tipo_dia_atendimento[$hora['dia']] == "Quinta"){
											if($numero_dia_semana == 4){
												$qtd_dias ++;
												if($filtro_data == ''){
													$filtro_data .= " (data_inicio LIKE '%".$data."%')";
												}else{
													$filtro_data .= " OR (data_inicio LIKE '%".$data."%')";
												}
											}
										}else if($tipo_dia_atendimento[$hora['dia']] == "Sexta"){
											if($numero_dia_semana == 5){
												$qtd_dias ++;
												if($filtro_data == ''){
													$filtro_data .= " (data_inicio LIKE '%".$data."%')";
												}else{
													$filtro_data .= " OR (data_inicio LIKE '%".$data."%')";
												}
											}
										}else if($tipo_dia_atendimento[$hora['dia']] == "Sábado"){
											if($numero_dia_semana == 6){
												$qtd_dias ++;
												if($filtro_data == ''){
													$filtro_data .= " (data_inicio LIKE '%".$data."%')";
												}else{
													$filtro_data .= " OR (data_inicio LIKE '%".$data."%')";
												}
											}
										}else if($tipo_dia_atendimento[$hora['dia']] == "Seg. a Qui."){
											if($numero_dia_semana != 0 && $numero_dia_semana != 6 && $numero_dia_semana != 5){
												$qtd_dias ++;
												if($filtro_data == ''){
													$filtro_data .= " (data_inicio LIKE '%".$data."%')";
												}else{
													$filtro_data .= " OR (data_inicio LIKE '%".$data."%')";
												}
											}
										}		
									}
									$dados = DBRead('', 'tb_atendimento a', "WHERE via_texto = 1 AND gravado = '1' AND falha != 2 AND desconsiderar = 0 AND (".$filtro_data.")" , "COUNT(id_atendimento) as cont");

									$qtd_dias_total += $qtd_dias;
									$atendimentos_total += $dados[0]['cont'];

									echo "
									<tr>
									<td class='text-left'>".$tipo_dia_atendimento[$hora['dia']]." (Das ".$hora_inicio." - Até ".$hora_fim.")</td>
									<td class='text-left'>".$dados[0]['cont']."</td>
									<td class='text-left'>".$qtd_dias."</td>
									<td class='text-left'>".sprintf("%01.2f", $dados[0]['cont']/$qtd_dias)."</td>
									</tr>
									";
								}    
							?>
							</tbody>
							<tfoot>
								<tr>
									<th>Média Total:</th>
									<?php
									echo "<th>".$atendimentos_total."</th>";
									echo "<th>".$qtd_dias_total."</th>";
									echo "<th>".sprintf("%01.2f", $atendimentos_total/$qtd_dias_total)."</th>";
									?>
								</tr>
							</tfoot>
						</table>
					</div>
					
                </div>
            </div>

            <?php
        }
    }
}

function relatorio_grafico_hora_acumulado_semana($data_de, $data_ate, $id_contrato_plano_pessoa, $tipo_grafico, $canal_atendimento){
	
	$horas = array('00:00','01:00','02:00','03:00','04:00','05:00','06:00','07:00','08:00','09:00','10:00','11:00','12:00','13:00','14:00','15:00','16:00','17:00','18:00','19:00','20:00','21:00','22:00','23:00');
	$dias_semana = array('Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado');
	$data_hora = converteDataHora(getDataHora());

	$atendidas = array();

	if($canal_atendimento){
		if($canal_atendimento == 'telefone'){
			$nome_canal_atendimento = 'Telefone';
			$filtro_canal_atendimento = 'AND via_texto = 0';
		}else{
			$nome_canal_atendimento = 'Texto';
			$filtro_canal_atendimento = 'AND via_texto = 1';
		}
	}else{
		$nome_canal_atendimento = 'Qualquer';
		$filtro_canal_atendimento = '';
	}

	if($tipo_grafico == 1){
		$nome_tipo_grafico = 'line';
	}else{
		$nome_tipo_grafico = 'column';
	}

	if ($id_contrato_plano_pessoa) {
		$dados = DBRead('', 'tb_contrato_plano_pessoa a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_contrato_plano_pessoa = '" . $id_contrato_plano_pessoa . "'");
		$empresa_legenda = $dados[0]['nome'];

		if ($dados[0]['nome_contrato']) {
			$empresa_legenda = $empresa_legenda . " (" . $dados[0]['nome_contrato'] . ") ";
		}

		$filtro_contrato_plano_pessoa = "AND id_contrato_plano_pessoa ='" . $id_contrato_plano_pessoa . "' ";
	} else {
		$empresa_legenda = "Todos";
	}

    if($data_de && $data_ate){
	    $periodo_amostra ="<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> De $data_de até $data_ate</span>";
	}elseif($data_de){
	    $periodo_amostra ="<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> A partir de $data_de</span>";
	}elseif($data_ate){
	    $periodo_amostra ="<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> Até $data_ate</span>";
	}else{
	    $periodo_amostra = "";
	}

	$gerado = "<span style=\"font-size: 14px;\"><strong>Gerado em: </strong> $data_hora</span>";

    echo "<div class=\"col-md-12\" style=\"padding: 0\">";
	echo "<legend style=\"text-align:center;\"><strong>Gráfico de Chat por Hora - Acumulado por Dia da Semana</strong><br>$gerado</legend>";
    echo "<legend style=\"text-align:center;\">$periodo_amostra</legend>";
	echo "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong> Contrato - </strong>" . $empresa_legenda . ", <strong> Canal de Atendimento - </strong>" . $nome_canal_atendimento . "";
	echo "</legend>";

	registraLog('Relatório de atendimentos - Gráfico de Atendimentos por Hora - Acumulado por Dia da Semana.','rel','relatorio-atendimento',1,"de $data_de até $data_ate");
	
	$chart = 0;
	foreach (rangeDatas(converteData($data_de), converteData($data_ate)) as $data) {		
		$numero_dia_semana = date('w', strtotime($data));
		$hora = 0;
		while ($hora < 24) {
			$hora_zero = sprintf('%02d', $hora);
			$atendidas_hora = 0;

			$dados_atendidas_grafico = DBRead('', 'tb_atendimento', "WHERE gravado = '1' AND falha != 2 ".$filtro_canal_atendimento." AND data_inicio BETWEEN '$data $hora_zero:00:00' AND '$data $hora_zero:59:59' ".$filtro_contrato_plano_pessoa." ");

			if($dados_atendidas_grafico){
				$atendidas_hora = sizeof($dados_atendidas_grafico);
			}else{
				$atendidas_hora = 0;
			}

			$atendidas[$numero_dia_semana][$hora] += $atendidas_hora;
			$hora++;
		}		
	}
	$cont_dia = 0;
	while($cont_dia < 7){
		$cont_hora = 0;
		while($cont_hora < 24){
			if(!$atendidas[$cont_dia][$cont_hora]){$atendidas[$cont_dia][$cont_hora]=0;}
			$cont_hora++;
		}		
		$cont_dia++;
	}
	$chart = 0;
	while($chart < 7){	
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
				        pointFormat: '{series.name}: {point.y}'
				    },                    
                    
					colors: [
						'#0000CD',
						'#228B22',
					],
                   
					plotOptions: {
						series: {
							dataLabels: {
								enabled: true
							}
						}
				    },
					series: [	
                    	{
                            name: 'Atendimentos', // Name of your series
                            data: <?php echo json_encode($atendidas[$chart], JSON_NUMERIC_CHECK) ?> // The data in your series

                        },
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

function relatorio_grafico_hora($data_de, $data_ate, $id_contrato_plano_pessoa, $tipo_grafico, $canal_atendimento){
	
	$dias_semana = array('Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado');
	$data_hora = converteDataHora(getDataHora());

	if($canal_atendimento){
		if($canal_atendimento == 'telefone'){
			$nome_canal_atendimento = 'Telefone';
			$filtro_canal_atendimento = 'AND via_texto = 0';
		}else{
			$nome_canal_atendimento = 'Texto';
			$filtro_canal_atendimento = 'AND via_texto = 1';
		}
	}else{
		$nome_canal_atendimento = 'Qualquer';
		$filtro_canal_atendimento = '';
	}

	if($tipo_grafico == 1){
		$nome_tipo_grafico = 'line';
	}else{
		$nome_tipo_grafico = 'column';
	}

	if ($id_contrato_plano_pessoa) {
		$dados = DBRead('', 'tb_contrato_plano_pessoa a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_contrato_plano_pessoa = '" . $id_contrato_plano_pessoa . "'");
		$empresa_legenda = $dados[0]['nome'];

		if ($dados[0]['nome_contrato']) {
			$empresa_legenda = $empresa_legenda . " (" . $dados[0]['nome_contrato'] . ") ";
		}

		$filtro_contrato_plano_pessoa = "AND id_contrato_plano_pessoa ='" . $id_contrato_plano_pessoa . "' ";
	} else {
		$empresa_legenda = "Todos";
	}

    if($data_de && $data_ate){
	    $periodo_amostra ="<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> De $data_de até $data_ate</span>";
	}elseif($data_de){
	    $periodo_amostra ="<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> A partir de $data_de</span>";
	}elseif($data_ate){
	    $periodo_amostra ="<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> Até $data_ate</span>";
	}else{
	    $periodo_amostra = "";
	}

	$gerado = "<span style=\"font-size: 14px;\"><strong>Gerado em: </strong> $data_hora</span>";

    echo "<div class=\"col-md-12\" style=\"padding: 0\">";
	echo "<legend style=\"text-align:center;\"><strong>Gráfico de Atendimentos por Hora - Separado por Dia do Mês</strong><br>$gerado</legend>";
    echo "<legend style=\"text-align:center;\">$periodo_amostra</legend>";
	echo "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong> Contrato - </strong>" . $empresa_legenda . ", <strong> Canal de Atendimento - </strong>" . $nome_canal_atendimento . "";
	echo "</legend>";

	registraLog('Relatório de atendimentos - Gráfico de Chat por Hora - Separado por Dia do Mês.','rel','relatorio-atendimento',1,"de $data_de até $data_ate");
	
	$chart = 0;
	foreach(rangeDatas(converteData($data_de), converteData($data_ate)) as $data){
		$atendidas = array();
        $horas = array();
		$numero_dia_semana = date('w', strtotime($data));
		$hora = 0;
		while ($hora < 24) {
			$hora_zero = sprintf('%02d', $hora);
			$atendidas_hora = 0;

			$dados_atendidas_grafico = DBRead('', 'tb_atendimento', "WHERE gravado = '1' AND falha != 2 ".$filtro_canal_atendimento." AND data_inicio BETWEEN '$data $hora_zero:00:00' AND '$data $hora_zero:59:59' ".$filtro_contrato_plano_pessoa." ");
		
			if($dados_atendidas_grafico){
				$atendidas_hora = sizeof($dados_atendidas_grafico);
			}else{
				$atendidas_hora = 0;
			}

		
			$atendidas[] = $atendidas_hora;
	        $horas[] = $hora_zero.':00';
			$hora++;
		}

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
                        text: 'Dia: <?php echo $dias_semana[$numero_dia_semana].', '.converteData($data); ?>' // Title for the chart
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
				        pointFormat: '{series.name}: {point.y}'
				    },
									
					colors: [
						'#0000CD',
						'#228B22',
					],
                       
				    plotOptions: {
				    
						series: {
							dataLabels: {
								enabled: true
							}
						}

				    },
                    series: [

                    	{
                            name: 'Atendimentos', // Name of your series
                            data: <?php echo json_encode($atendidas, JSON_NUMERIC_CHECK) ?> // The data in your series

                        },
                       
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

function relatorio_desconsiderados($data_de, $data_ate, $id_contrato_plano_pessoa, $desconsidera, $request){

	if ($id_contrato_plano_pessoa) {
		$dados = DBRead('', 'tb_contrato_plano_pessoa a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_contrato_plano_pessoa = '" . $id_contrato_plano_pessoa . "'");
		$empresa_legenda = $dados[0]['nome'];

		if ($dados[0]['nome_contrato']) {
			$empresa_legenda = $empresa_legenda . " (" . $dados[0]['nome_contrato'] . ") ";
		}

		$filtro_contrato_plano_pessoa = "AND id_contrato_plano_pessoa ='" . $id_contrato_plano_pessoa . "' ";
	} else {
		$empresa_legenda = "Todos";
	}

	if ($desconsidera) {
		$dados_desconsidera = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_usuario = '" . $desconsidera . "'", "b.nome");

		$desconsidera_legenda = $dados_desconsidera[0]['nome'];
		$filtro_usuario = "AND d.id_usuario = '" . $desconsidera . "'";
	} else {
		$desconsidera_legenda = 'Todos';
	}

	$data_hoje = getDataHora();
	$data_hoje = converteDataHora($data_hoje);

	$periodo_amostra = "<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> de $data_de até $data_ate</span>";
	$gerado = "<span style=\"font-size: 14px;\"><strong>Gerado em: </strong> $data_hoje</span>";

	$data_de = converteData($data_de);
	$data_ate = converteData($data_ate);

	$dados = DBRead('', 'tb_atendimento a', "INNER JOIN tb_situacao_atendimento b ON a.id_atendimento = b.id_atendimento INNER JOIN tb_situacao c ON b.id_situacao = c.id_situacao INNER JOIN tb_desconsiderar d ON a.id_atendimento = d.id_atendimento INNER JOIN tb_usuario e ON d.id_usuario = e.id_usuario INNER JOIN tb_pessoa f ON e.id_pessoa = f.id_pessoa WHERE a.gravado = '1' AND a.falha != 2 AND a.data_inicio BETWEEN '" . $data_de . " 00:00:00' AND '" . $data_ate . " 23:59:59' " . $filtro_contrato_plano_pessoa . " " . $filtro_usuario . " ORDER BY a.data_inicio ASC", "a.*, a.id_atendimento as atendimento_id, c.nome, d.data_hora AS 'data_hora_desconsiderar', f.nome AS 'nome_desconsiderar' ");

	registraLog('Relatório de atendimentos - Desconsiderados.','rel','relatorio-atendimento',1,"INNER JOIN tb_situacao_atendimento b ON a.id_atendimento = b.id_atendimento INNER JOIN tb_situacao c ON b.id_situacao = c.id_situacao INNER JOIN tb_desconsiderar d ON a.id_atendimento = d.id_atendimento INNER JOIN tb_usuario e ON d.id_usuario = e.id_usuario INNER JOIN tb_pessoa f ON e.id_pessoa = f.id_pessoa WHERE a.gravado = '1' AND a.falha != 2 AND a.data_inicio BETWEEN '" . $data_de . " 00:00:00' AND '" . $data_ate . " 23:59:59' " . $filtro_contrato_plano_pessoa . " " . $filtro_usuario . " ORDER BY a.data_inicio ASC");


	if ($dados) {
		$contador_dados = "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong>Total: </strong> " . count($dados) . "</span></legend>";
	}

	echo "<div class=\"col-md-10 col-md-offset-1\" style=\"padding: 0\">";
	echo "<legend style=\"text-align:center;\"><strong>Relatório de Atendimentos - Desconsiderados</strong><br>$gerado</legend>";
	echo "<legend style=\"text-align:center;\">$periodo_amostra</legend>";
	echo "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong> Contrato - </strong>" . $empresa_legenda . ", <strong>Desconsiderado por - </strong>" . $desconsidera_legenda . "</legend>";

	echo $contador_dados;

	if ($dados) {
		foreach ($dados as $dado) {
			
			$conteudo_empresa = DBRead('', 'tb_contrato_plano_pessoa a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa INNER JOIN tb_plano c ON a.id_plano = c.id_plano WHERE a.id_contrato_plano_pessoa = '" . $dado['id_contrato_plano_pessoa'] . "'", "b.nome AS nome_empresa, a.*, c.*");

			if ($conteudo_empresa[0]['nome_contrato']) {
				$nome_contrato = " (" . $conteudo_empresa[0]['nome_contrato'] . ") ";
			} else {
				$nome_contrato = '';
			}

			$contrato = $conteudo_empresa[0]['nome_empresa'] . " " . $nome_contrato;

			$nome_empresa = "<span><strong>" . $contrato . "</strong></span>";

			$situacao_protocolo = DBRead('', 'tb_parametros', "WHERE id_contrato_plano_pessoa = '" . $dado['id_contrato_plano_pessoa'] . "'", "exibir_protocolo, solicitacao_cpf");

			if ($dado['flag_pendencia'] == 1) {
				$data_inicio = converteDataHora($dado['data']);
				$conteudo_pessoa = DBRead('', 'tb_pessoa a', "INNER JOIN tb_usuario b ON a.id_pessoa = b.id_pessoa WHERE b.id_usuario = '" . $dado['id_usuario_pendencia'] . "' ");
				$nome_pessoa = $conteudo_pessoa[0]['nome'].' <strong>(Atendimento pendente)</strong> <i class="fas fa-exclamation-triangle" style="color: #F7D358"></i>';

			} else {
				$data_inicio = converteDataHora($dado['data_inicio']);
				$conteudo_pessoa = DBRead('', 'tb_pessoa a', "INNER JOIN tb_usuario b ON a.id_pessoa = b.id_pessoa WHERE b.id_usuario = '" . $dado['id_usuario'] . "' ");
				$nome_pessoa = $conteudo_pessoa[0]['nome'];
			}

			echo "<div class = 'row'>";
			echo "<div class='col-md-12'>";
			echo "<ul class='list-group container-entrevista ul-esquerda'>";
			echo "
				<li class='list-group-item'>
					<div class='row'>
						
						<div class='col-md-6' style='text-align: center'>
							<strong style='font-size:16px'>" .$nome_empresa . "</strong><br>
							<span> ". $data_inicio . "</span> <br><br>
						</div>
						<div class='col-md-6' style='text-align: center'>
							<strong>Desconsiderado por:</strong><br>
							".$dado['nome_desconsiderar']."<br>
							<span> ". converteDataHora($dado['data_hora_desconsiderar']) . "</span> <br><br>
						</div>
					</div>
				</li>";

			echo "<li class='list-group-item'><strong>Assinante: </strong> " . $dado['assinante'] . "</li>";
			echo "<li class='list-group-item'><strong>Contato: </strong> " . $dado['contato'] . "</li>";
			if ($dado['fone2']) {
				echo "<li class='list-group-item'><strong>Telefone 1: </strong> <span class='phone'>" . $dado['fone1'] . "</span></li>";
				echo "<li class='list-group-item'><strong>Telefone 2: </strong> <span class='phone'>" . $dado['fone2'] . "</span></li>";
			} else {
				echo "<li class='list-group-item'><strong>Telefone: </strong> <span class='phone'>" . $dado['fone1'] . "</span></li>";
			}

			if ($situacao_protocolo[0]['solicitacao_cpf'] == 1) {
				if ($dado['cpf_cnpj']) {
					echo "<li class='list-group-item'><strong>CPF/CNPJ: </strong> <span>" . $dado['cpf_cnpj'] . "</span></li>";
				}
			}
			if ($dado['descricao_dado_adicional'] && $dado['dado_adicional']) {
				echo "<li class='list-group-item'><strong>" . $dado['descricao_dado_adicional'] . ": </strong> <span>" . $dado['dado_adicional'] . "</span></li>";
			}
			if ($situacao_protocolo[0]['exibir_protocolo'] == 1) {
				echo "<li class='list-group-item'><strong>Protocolo: </strong> <span>" . $dado['protocolo'] . "</span></li>";
			}

			echo "<li class='list-group-item'><strong>Atendente: </strong> " . $nome_pessoa . "</li>";
			echo "<li class='list-group-item'><strong>OS: </strong><br> " . nl2br($dado['os']) . "<br><br>" . $dado['nome'] . "</li>";

			echo "</ul>";
			echo "</div>";


			echo "</div><br><br>";
		}

	?>
		<script>
			window.addEventListener('DOMContentLoaded', function() {
				$('.esquerda').each(function(i) {
					var tamanho_heigth = $(this).height();
					var tamanho_width = $(this).find('.ul-esquerda').width();

					$(this).parent().find('.direita').height(tamanho_heigth);

					$(this).parent().find('.ul-direita').css('bottom', '0').css('position', 'absolute').width(tamanho_width);
				});

			});

			$(document).on('click', '#faturar', function(){
				var id_atendimento = $(this).val();
				if (!confirm('Você tem certeza que quer desconsiderar o atendimento?')){
					return false; 
				}
				$.ajax({
					url: "/api/ajax?class=DesconsiderarAtendimento.php",
					dataType: "json",
					data: {
						acao: 'faturar',
						parametros: {
							'id_atendimento': id_atendimento,
						},
						token: '<?= $request->token ?>'
					},
					success: function(data) {
						if(data[0].desconsiderar == 0){
							$('.desconsiderar_'+id_atendimento).html("<button class='btn btn-xs btn-danger' id='nao_faturar' value='"+id_atendimento+"'><i class='far fa-times-circle'></i> Não Faturar</button>");
						}else{
							alert('Ocorreu algum erro, não foi possível atualizar o status do atendimento. Atualize a página e tente novamente!');
						}
						
					}
				});
			});

			$(document).on('click', '#nao_faturar', function(){
				var id_atendimento = $(this).val();
				var id_usuario = '<?= $_SESSION['id_usuario'] ?>';
				if (!confirm('Você tem certeza que quer faturar o atendimento?')){
					return false; 
				}
				$.ajax({
					url: "/api/ajax?class=DesconsiderarAtendimento.php",
					dataType: "json",
					data: {
						acao: 'nao_faturar',
						parametros: {
							'id_atendimento': id_atendimento,
							'id_usuario': id_usuario,
						},
						token: '<?= $request->token ?>'
					},
					success: function(data) {
						if(data[0].desconsiderar == 1){
							$('.desconsiderar_'+id_atendimento).html("<button class='btn btn-xs btn-success' id='faturar' value='"+id_atendimento+"'><i class='far fa-check-circle'></i> Faturar</button>");
						}else{
							alert('Ocorreu algum erro, não foi possível atualizar o status do atendimento. Atualize a página e tente novamente!');
						}
						
					}
				});
			});

		</script>

	<?php

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

function relatorio_situacao_porcentagem($id_contrato_plano_pessoa, $data_de, $data_ate, $operador, $situacao){

	if ($id_contrato_plano_pessoa) {
		$dados = DBRead('', 'tb_contrato_plano_pessoa a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_contrato_plano_pessoa = '" . $id_contrato_plano_pessoa . "'");
		$empresa_legenda = $dados[0]['nome'];

		if ($dados[0]['nome_contrato']) {
			$empresa_legenda = $empresa_legenda . " (" . $dados[0]['nome_contrato'] . ") ";
		}

		$filtro_contrato_plano_pessoa = "AND id_contrato_plano_pessoa ='" . $id_contrato_plano_pessoa . "' ";
	} else {
		$empresa_legenda = "Todos";
	}

	if ($operador) {
		$dados_operadores = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_usuario = '" . $operador . "'");

		$operador_legenda = $dados_operadores[0]['nome'];
		$filtro_usuario = " AND a.id_usuario = '" . $operador . "'";
	} else {
		$operador_legenda = 'Todos';
	}

	if ($situacao) {
		$dados_situacao = DBRead('', 'tb_situacao', "WHERE id_situacao = '" . $situacao . "'");
		$situacao_legenda =  ucwords(strtolower($dados_situacao[0]['nome']));
		$filtro_situacao = " AND b.id_situacao = '" . $situacao . "'";
	} else {
		$situacao_legenda = 'Todas';
	}

	$data_hoje = getDataHora();
	$data_hoje = converteDataHora($data_hoje);

	$periodo_amostra = "<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> de $data_de até $data_ate</span>";
	$gerado = "<span style=\"font-size: 14px;\"><strong>Gerado em: </strong> $data_hoje</span>";

	$data_de = converteData($data_de);
	$data_ate = converteData($data_ate);

	echo "<div class=\"col-md-12\" style=\"padding: 0\">";
	echo "<legend style=\"text-align:center;\"><strong>Relatório de Atendimentos - Situação (Porcentagens)</strong><br>$gerado</legend>";
	echo "<legend style=\"text-align:center;\">$periodo_amostra</legend>";
	echo "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong> Contrato - </strong>" . $empresa_legenda . ", <strong>Situação - </strong>" . $situacao_legenda . ", <strong>Atendente - </strong>" . $operador_legenda . "</legend>";
	echo "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong> Contrato - </strong>" . $empresa_legenda . ", <strong>Atendente - </strong>" . $operador_legenda . "</legend>";

	if ($id_contrato_plano_pessoa) {
		$filtro_contrato_plano_pessoa = "AND a.id_contrato_plano_pessoa = '" . $id_contrato_plano_pessoa . "'";
	} else {
		$filtro_contrato_plano_pessoa = "";
	}

	$dados_consulta = DBRead('', 'tb_contrato_plano_pessoa a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa INNER JOIN tb_plano c ON a.id_plano = c.id_plano WHERE c.cod_servico = 'call_suporte' " . $filtro_contrato_plano_pessoa . " AND a.status = 1 AND b.nome NOT LIKE '%Belluno%' ORDER BY b.nome ASC", "a.*, b.*, a.status AS status_contrato");//id_pessoa belluno = 

	registraLog('Relatório de atendimentos - Situação (Porcentagem).','rel','relatorio-atendimento',1,"INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa INNER JOIN tb_plano c ON a.id_plano = c.id_plano WHERE c.cod_servico = 'call_suporte' " . $filtro_contrato_plano_pessoa . " AND a.status = 1 AND b.nome NOT LIKE '%Belluno%' ORDER BY b.nome ASC");


	if ($dados_consulta) {
		echo '<table class="table table-striped dataTable" style="margin-bottom:0;">
			<thead>
				<tr>
					<th class="text-left col-md-2">Contrato</th>
			        <th class="text-left col-md-2">Plano</th>
		            <th class="text-left col-md-1">Atendimento Encerrado</th>
					<th class="text-left col-md-1">Atendimento Encaminhado ao setor responsável</th>
					<th class="text-left col-md-1">Atendimento Vinculado a OS já existente</th>
					<th class="text-left col-md-1">Total</th>
					<th class="text-left col-md-1">Encerrado + Encaminhado</th>
					<th class="text-left col-md-1">Encerrado + Vinculado</th>
					<th class="text-left col-md-1">Vinculado + Encaminhado</th>
				</tr>
			</thead>
			<tbody>';

		$contador_encerrado = 0;
		$contador_setor_responsavel = 0;
		$contador_vinculado = 0;
		$contador_total = 0;

		$situacao_usuario = array();
		
		foreach ($dados_consulta as $dado_consulta) {
			$cont_encerrado = 0;
			$cont_setor_responsavel = 0;
			$cont_vinculado = 0;
			$total = 0;

			$dados = DBRead('', 'tb_atendimento a', " INNER JOIN tb_situacao_atendimento b ON a.id_atendimento = b.id_atendimento WHERE (b.id_situacao = 3 OR b.id_situacao = 4 OR b.id_situacao = 7) AND (a.data_inicio BETWEEN '" . $data_de . " 00:00:00' AND '" . $data_ate . " 23:59:59') AND a.id_contrato_plano_pessoa = '" . $dado_consulta['id_contrato_plano_pessoa'] . "' " . $filtro_usuario . " ".$filtro_situacao." ");

			if ($dados) {
				foreach ($dados as $conteudo) {

					if ($conteudo['id_situacao'] == 3) {
						$situacao_usuario[$conteudo['id_usuario']]['encerrado'] += 1;
						$cont_encerrado += 1;

					} else if ($conteudo['id_situacao'] == 4) {
						$situacao_usuario[$conteudo['id_usuario']]['setor_responsavel'] += 1;
						$cont_setor_responsavel += 1;

					} else if ($conteudo['id_situacao'] == 7) {
						$situacao_usuario[$conteudo['id_usuario']]['vinculado'] += 1;
						$cont_vinculado += 1;
					}
				}
			}

			/* $cont_dados_setor_responsavel = DBRead('', 'tb_atendimento a', "INNER JOIN tb_situacao_atendimento b ON a.id_atendimento = b.id_atendimento WHERE b.id_situacao = '4' AND a.data_inicio BETWEEN '" . $data_de . " 00:00:00' AND '" . $data_ate . " 23:59:59' AND a.id_contrato_plano_pessoa = '" . $dado_consulta['id_contrato_plano_pessoa'] . "' " . $filtro_usuario . " ".$filtro_situacao." ");

			if ($cont_dados_setor_responsavel) {
				foreach ($cont_dados_setor_responsavel as $conteudo) {
					
				}
			}

			$cont_dados_vinculado = DBRead('', 'tb_atendimento a', " INNER JOIN tb_situacao_atendimento b ON a.id_atendimento = b.id_atendimento WHERE b.id_situacao = '7' AND a.data_inicio BETWEEN '" . $data_de . " 00:00:00' AND '" . $data_ate . " 23:59:59' AND a.id_contrato_plano_pessoa = '" . $dado_consulta['id_contrato_plano_pessoa'] . "' " . $filtro_usuario . " ".$filtro_situacao." ");

			if ($cont_dados_vinculado) {
				foreach ($cont_dados_vinculado as $conteudo) {
					
				}
			} */

			$total = $cont_encerrado + $cont_setor_responsavel + $cont_vinculado;

			if ($dado_consulta['nome_contrato']) {
				$nome_contrato = " (" . $dado_consulta['nome_contrato'] . ") ";
			} else {
				$nome_contrato = '';
			}

			$contrato = $dado_consulta['nome'] . " " . $nome_contrato;

			$dados_planos = DBRead('', 'tb_plano', "WHERE id_plano = '" . $dado_consulta['id_plano'] . "'");

			$percentual_cont_encerrado = sprintf("%01.2f", round($cont_encerrado * 100) / ($total == 0 ? 1 : $total), 2);
			$percentual_cont_setor_responsavel = sprintf("%01.2f", round($cont_setor_responsavel * 100) / ($total == 0 ? 1 : $total), 2);
			$percentual_cont_vinculado = sprintf("%01.2f", round($cont_vinculado * 100) / ($total == 0 ? 1 : $total), 2);
			
			echo '<tr>
					<td class="text-left">' . $contrato . '</td>                
					<td class="text-left">' . getNomeServico($dados_planos[0]['cod_servico']) . " - " . $dados_planos[0]['nome'] . '</td>  
					<td class="text-left success" data-order="'.$percentual_cont_encerrado.'">' . $cont_encerrado . ' <span class="pull-right"> ('.$percentual_cont_encerrado.'%)</span></td>

					<td class="text-left info" data-order="'.$percentual_cont_setor_responsavel.'">' . $cont_setor_responsavel . ' <span class="pull-right"> ('.$percentual_cont_setor_responsavel.'%)</span></td>   

					<td class="text-left warning" data-order="'.$percentual_cont_vinculado.'">' . $cont_vinculado . ' <span class="pull-right"> ('.$percentual_cont_vinculado.'%)</span></td>

					<td class="text-left">' . $total . '</td> 

					<td class="text-left" style="background-color: #F2EFFB" data-order="'.($percentual_cont_encerrado + $percentual_cont_setor_responsavel).'">' . ($cont_encerrado + $cont_setor_responsavel) .' <span class="pull-right"> ('.($percentual_cont_encerrado + $percentual_cont_setor_responsavel) . '%)</span></td>

					<td class="text-left" style="background-color: #EFFBF8" data-order="'.($percentual_cont_encerrado + $percentual_cont_vinculado).'">' . ($cont_encerrado + $cont_vinculado) . ' <span class="pull-right"> ('. ($percentual_cont_encerrado + $percentual_cont_vinculado).'%) </span></td>

					<td class="text-left" style="background-color: #FBF2EF" data-order="'.($percentual_cont_setor_responsavel + $percentual_cont_vinculado).'">' . ($cont_setor_responsavel + $cont_vinculado) . ' <span class="pull-right"> ('. ($percentual_cont_setor_responsavel + $percentual_cont_vinculado) .'%) </span></td>               
				</tr>';

			$contador_encerrado = $contador_encerrado + $cont_encerrado;
			$contador_setor_responsavel = $contador_setor_responsavel + $cont_setor_responsavel;
			$contador_vinculado = $contador_vinculado + $cont_vinculado;
			$contador_total = $contador_total + $total;
		}
		$percentual_contador_encerrado = sprintf("%01.2f", ($contador_encerrado * 100) / ($contador_total == 0 ? 1 : $contador_total));
		$percentual_contador_setor_responsavel = sprintf("%01.2f", ($contador_setor_responsavel * 100) / ($contador_total == 0 ? 1 : $contador_total));
		$percentual_contador_vinculado = sprintf("%01.2f", ($contador_vinculado * 100) / ($contador_total == 0 ? 1 : $contador_total));

		echo '</tbody>';
		echo "<tfoot>";

		echo '<tr>';
		echo '<th>Totais</th>';
		echo '<th></th>';
		echo '<th>' . $contador_encerrado . ' <span class="pull-right"> ('.$percentual_contador_encerrado.'%) </span></th>';

		echo '<th>' . $contador_setor_responsavel . ' <span class="pull-right"> ('.$percentual_contador_setor_responsavel.'%) </span></th>';

		echo '<th>' . $contador_vinculado . ' <span class="pull-right"> ('.$percentual_contador_vinculado.'%) </span></th>';

		echo '<th>' . $contador_total . '</th>';

		echo '<th>' . ($contador_encerrado + $contador_setor_responsavel) . ' <span class="pull-right"> ('.($percentual_contador_encerrado + $percentual_contador_setor_responsavel).'%) </span></th>';

		echo '<th>' . ($contador_encerrado + $contador_vinculado) . ' <span class="pull-right"> ('.($percentual_contador_encerrado + $percentual_contador_vinculado).'%) </span></th>';

		echo '<th>' . ($contador_setor_responsavel + $contador_vinculado) . ' <span class="pull-right"> ('.($percentual_contador_setor_responsavel + $percentual_contador_vinculado).'%)</span></th>';
		echo '</tr>';

		echo "</tfoot> ";
		echo '</table>';
		if ($operador) {
			echo "<hr><legend style=\"text-align:center;\"><strong>Atendente</strong></legend>";
		} else {
			echo "<hr><legend style=\"text-align:center;\"><strong>Atendentes</strong></legend>";
		}

		echo '<table class="table table-striped dataTable" style="margin-bottom:0;">
			<thead>
				<tr>
					<th class="text-left col-md-4">Atendente</th>
					<th class="text-left col-md-1">Atendimento Encerrado</th>
					<th class="text-left col-md-1">Atendimento Encaminhado ao setor responsável</th>
					<th class="text-left col-md-1">Atendimento Vinculado a OS já existente</th>
					<th class="text-left col-md-1">Total</th>
					<th class="text-left col-md-1">Encerrado + Encaminhado</th>
					<th class="text-left col-md-1">Encerrado + Vinculado</th>
					<th class="text-left col-md-1">Encaminhado + Vinculado</th>
				</tr>
			</thead>
			<tbody>';

		foreach ($situacao_usuario as $id => $conteudo) {
			$dados = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE id_usuario = '" . $id . "' ", "b.nome");
			if ($operador && $operador == $id || !$operador) {

				$total_atendente = $situacao_usuario[$id]['encerrado'] + $situacao_usuario[$id]['setor_responsavel'] + $situacao_usuario[$id]['vinculado'];

				$percentual_cont_encerrado = sprintf("%01.2f", ($situacao_usuario[$id]['encerrado'] * 100) / ($total_atendente == 0 ? 1 : $total_atendente));
				$percentual_cont_setor_responsavel = sprintf("%01.2f", ($situacao_usuario[$id]['setor_responsavel'] * 100) / ($total_atendente == 0 ? 1 : $total_atendente));
				$percentual_cont_vinculado = sprintf("%01.2f", ($situacao_usuario[$id]['vinculado'] * 100) / ($total_atendente == 0 ? 1 : $total_atendente));

			
				echo '<tr>
						<td class="text-left">' . $dados[0]['nome'] . '</td>           

						<td class="text-left success" data-order="'.$percentual_cont_encerrado.'">' . (!$situacao_usuario[$id]['encerrado'] ? 0 : $situacao_usuario[$id]['encerrado']) . ' <span class="pull-right">('.$percentual_cont_encerrado.'%)</span></td>                

						<td class="text-left info" data-order="'.$percentual_cont_setor_responsavel.'">' . (!$situacao_usuario[$id]['setor_responsavel'] ? 0 : $situacao_usuario[$id]['setor_responsavel']) . ' <span class="pull-right">('.$percentual_cont_setor_responsavel.'%)</span></td>                  

						<td class="text-left warning" data-order="'.$percentual_cont_vinculado.'">' . (!$situacao_usuario[$id]['vinculado'] ? 0 : $situacao_usuario[$id]['vinculado']) . '<span class="pull-right">('.$percentual_cont_vinculado.'%)</span></td>                 
					
						<td class="text-left">' . $total_atendente . '</td>

						<td class="text-left" style="background-color: #F2EFFB" data-order="'.($percentual_cont_encerrado + $percentual_cont_setor_responsavel).'">' .  ((!$situacao_usuario[$id]['encerrado'] ? 0 : $situacao_usuario[$id]['encerrado']) + (!$situacao_usuario[$id]['setor_responsavel'] ? 0 : $situacao_usuario[$id]['setor_responsavel'])) .'  <span class="pull-right">('.($percentual_cont_encerrado + $percentual_cont_setor_responsavel) . '%)</span></td>

						<td class="text-left" style="background-color: #EFFBF8" data-order="'.($percentual_cont_encerrado + $percentual_cont_vinculado).'">' . ((!$situacao_usuario[$id]['encerrado'] ? 0 : $situacao_usuario[$id]['encerrado']) + (!$situacao_usuario[$id]['vinculado'] ? 0 : $situacao_usuario[$id]['vinculado']))  . ' <span class="pull-right">('. ($percentual_cont_encerrado + $percentual_cont_vinculado).'%)</span></td>

						<td class="text-left" style="background-color: #FBF2EF"  data-order="'.($percentual_cont_setor_responsavel + $percentual_cont_vinculado).'">' . ((!$situacao_usuario[$id]['setor_responsavel'] ? 0 : $situacao_usuario[$id]['setor_responsavel']) + (!$situacao_usuario[$id]['vinculado'] ? 0 : $situacao_usuario[$id]['vinculado'])) . ' <span class="pull-right">('.($percentual_cont_vinculado + $percentual_cont_setor_responsavel) .'%)</span></td>            
					</tr>';
			}
		}
		echo "</tfoot> ";
		echo '</table><br><br><br>';

		echo "<script>
				$(document).ready(function(){
					$('.dataTable').DataTable({
						\"language\": {
							\"url\": \"//cdn.datatables.net/plug-ins/1.10.19/i18n/Portuguese-Brasil.json\"
						},
						columnDefs: [
							{ type: 'chinese-string', targets: 0 },
						],				        
						\"searching\": false,
						\"paging\":   false,
						\"info\":     false
					});
				});
			</script>			
			";
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
	echo "</div>";
}

function relatorio_faturados($id_contrato_plano_pessoa, $data_de, $data_ate, $operador, $tipo_atendimento, $envia_email, $id_area_problema, $id_subarea_problema){

	if ($envia_email == 1) {
		$filtro_envia_email = " AND enviado = '1' ";
		$envia_email_legenda = 'Somente com E-mail enviados';
	} else if ($envia_email == 2) {
		$filtro_envia_email = " AND enviado = '0' ";
		$envia_email_legenda = 'Somente com E-mail não enviados';
	} else {
		$envia_email_legenda = 'Todos';
	}

	if ($tipo_atendimento == 2) {
		$filtro_tipo_atendimento = " AND via_texto = 1";
		$filtro_legenda = 'Via texto';
	} else if ($tipo_atendimento == 1) {
		$filtro_tipo_atendimento = " AND via_texto != 1";
		$filtro_legenda = 'Via telefone';
	} else {
		$filtro_legenda = 'Todos';
	}

	if ($id_contrato_plano_pessoa) {
		$dados = DBRead('', 'tb_contrato_plano_pessoa a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_contrato_plano_pessoa = '" . $id_contrato_plano_pessoa . "'");
		$empresa_legenda = $dados[0]['nome'];

		if ($dados[0]['nome_contrato']) {
			$empresa_legenda = $empresa_legenda . " (" . $dados[0]['nome_contrato'] . ") ";
		}

		$filtro_contrato_plano_pessoa = "AND id_contrato_plano_pessoa ='" . $id_contrato_plano_pessoa . "' ";
	} else {
		$empresa_legenda = "Todos";
	}

	if ($operador) {
		$dados_operadores = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_usuario = '" . $operador . "'");

		$operador_legenda = $dados_operadores[0]['nome'];
		$filtro_usuario = "AND id_usuario = '" . $operador . "'";
	} else {
		$operador_legenda = 'Todos';
	}

	if ($id_area_problema) {
		$dados_area = DBRead('', 'tb_area_problema', "WHERE id_area_problema = '" . $id_area_problema . "' ");

        $area_legenda = $dados_area[0]['nome'];        
        $inner_filtro_area = "INNER JOIN tb_subarea_problema_atendimento d ON a.id_atendimento = d.id_atendimento INNER JOIN tb_subarea_problema e ON d.id_subarea_problema = e.id_subarea_problema";
		$filtro_area = " AND e.id_area_problema = '" . $id_area_problema . "'";
		if ($id_area_problema == 'nao_identificada') {
			$area_legenda = "Atendimento Incompleto";
			$subarea_legenda = 'Atendimento Incompleto';
		}

		if ($id_subarea_problema) {
			$dados_subarea = DBRead('', 'tb_subarea_problema', "WHERE id_subarea_problema = '" . $id_subarea_problema . "' ");
	
			$subarea_legenda = $dados_subarea[0]['descricao'];
			$filtro_subarea = " AND d.id_subarea_problema = '" . $id_subarea_problema . "'";
		} else {
			$subarea_legenda = 'Todas';
		}
	} else {
		$area_legenda = 'Todas';
	}

	$data_hoje = getDataHora();
	$data_hoje = converteDataHora($data_hoje);

	$periodo_amostra = "<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> de $data_de até $data_ate</span>";
	$gerado = "<span style=\"font-size: 14px;\"><strong>Gerado em: </strong> $data_hoje</span>";

	$data_de = converteData($data_de);
	$data_ate = converteData($data_ate);

	$dados = DBRead('', 'tb_atendimento a', "INNER JOIN tb_situacao_atendimento b ON a.id_atendimento = b.id_atendimento INNER JOIN tb_situacao c ON b.id_situacao = c.id_situacao $inner_filtro_area WHERE a.gravado = '1' AND a.falha != 2 AND a.desconsiderar = 0 AND a.data_inicio BETWEEN '" . $data_de . " 00:00:00' AND '" . $data_ate . " 23:59:59' " . $filtro_contrato_plano_pessoa . " " . $filtro_usuario . " ". $filtro_tipo_atendimento." ".$filtro_envia_email." ".$filtro_subarea." ".$filtro_area." ORDER BY a.data_inicio ASC", "a.*, c.nome");

	registraLog('Relatório de atendimentos - Faturados.','rel','relatorio-atendimento',1,"INNER JOIN tb_situacao_atendimento b ON a.id_atendimento = b.id_atendimento INNER JOIN tb_situacao c ON b.id_situacao = c.id_situacao $inner_filtro_area WHERE a.gravado = '1' AND a.falha != 2 AND a.desconsiderar = 0 AND a.data_inicio BETWEEN '" . $data_de . " 00:00:00' AND '" . $data_ate . " 23:59:59' " . $filtro_contrato_plano_pessoa . " " . $filtro_usuario . " ". $filtro_tipo_atendimento." ".$filtro_envia_email." ".$filtro_subarea." ".$filtro_area." ORDER BY a.data_inicio ASC");



	if ($dados) {
		$contador_dados = "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong>Total: </strong> " . count($dados) . "</span></legend>";
	}

	echo "<div class=\"col-md-8 col-md-offset-2\" style=\"padding: 0\">";
	echo "<legend style=\"text-align:center;\"><strong>Relatório de Atendimentos - Faturados</strong><br>$gerado</legend>";
	echo "<legend style=\"text-align:center;\">$periodo_amostra</legend>";
	echo "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong> Contrato - </strong>" . $empresa_legenda . ", <strong>Atendente - </strong>" . $operador_legenda . ", <strong>Tipo do atendimento - </strong>" . $filtro_legenda . ", <strong>E-mail enviado - </strong>" . $envia_email_legenda . ", <strong>Área de Finalização - </strong>" . $area_legenda . ", <strong>Subárea de Finalização - </strong>" . $subarea_legenda . "</legend>";

	echo $contador_dados;
 
	if ($dados) {
		foreach ($dados as $dado) {
			
			$conteudo_empresa = DBRead('', 'tb_contrato_plano_pessoa a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa INNER JOIN tb_plano c ON a.id_plano = c.id_plano WHERE a.id_contrato_plano_pessoa = '" . $dado['id_contrato_plano_pessoa'] . "'", "b.nome AS nome_empresa, a.*, c.*");

			if ($conteudo_empresa[0]['nome_contrato']) {
				$nome_contrato = " (" . $conteudo_empresa[0]['nome_contrato'] . ") ";
			} else {
				$nome_contrato = '';
			}

			$contrato = $conteudo_empresa[0]['nome_empresa'] . " " . $nome_contrato;

			$nome_empresa = "<span><strong>" . $contrato . "</strong></span>";

			$situacao_protocolo = DBRead('', 'tb_parametros', "WHERE id_contrato_plano_pessoa = '" . $dado['id_contrato_plano_pessoa'] . "'", "exibir_protocolo, solicitacao_cpf");

			$conteudo_pessoa = DBRead('', 'tb_pessoa a', "INNER JOIN tb_usuario b ON a.id_pessoa = b.id_pessoa WHERE b.id_usuario = '" . $dado['id_usuario'] . "' ");
			$nome_pessoa = $conteudo_pessoa[0]['nome'];

			echo "<ul class='list-group container-entrevista'>";
			echo "<li class='list-group-item'><div class='row'>
						<div class='col-md-4' style='text-align: left'></div>
						<div class='col-md-4' style='text-align: center'>
							<strong style='font-size:16px'>" . $nome_empresa . "</strong><br>
							<span>" . converteDataHora($dado['data_inicio']) . "</span>
						</div>
						<div class='col-md-4' style='text-align: right;'>
							<div class='panel-title text-right pull-right'>
								<div class='col-md-12'>
									<span class='text-right' style = 'font-size:12px;'></span><br>
								</div>
							</div>
						</div>
		               	
					  </li>";

			echo "<li class='list-group-item'><strong>Assinante: </strong> " . $dado['assinante'] . "</li>";
			echo "<li class='list-group-item'><strong>Contato: </strong> " . $dado['contato'] . "</li>";
			if ($dado['fone2']) {
				echo "<li class='list-group-item'><strong>Telefone 1: </strong> <span class='phone'>" . $dado['fone1'] . "</span></li>";
				echo "<li class='list-group-item'><strong>Telefone 2: </strong> <span class='phone'>" . $dado['fone2'] . "</span></li>";
			} else {
				echo "<li class='list-group-item'><strong>Telefone: </strong> <span class='phone'>" . $dado['fone1'] . "</span></li>";
			}

			if ($situacao_protocolo[0]['solicitacao_cpf'] == 1) {
				if ($dado['cpf_cnpj']) {
					echo "<li class='list-group-item'><strong>CPF/CNPJ: </strong> <span>" . $dado['cpf_cnpj'] . "</span></li>";
				}
			}
			if ($dado['descricao_dado_adicional'] && $dado['dado_adicional']) {
				echo "<li class='list-group-item'><strong>" . $dado['descricao_dado_adicional'] . ": </strong> <span>" . $dado['dado_adicional'] . "</span></li>";
			}
			if ($situacao_protocolo[0]['exibir_protocolo'] == 1) {
				echo "<li class='list-group-item'><strong>Protocolo: </strong> <span>" . $dado['protocolo'] . "</span></li>";
			}

			echo "<li class='list-group-item'><strong>Atendente: </strong> " . $nome_pessoa . "</li>";
			echo "<li class='list-group-item'><strong>OS: </strong><br> " . nl2br($dado['os']) . "<br><br>" . $dado['nome'] . "</li>";
			echo "</ul>";
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

function relatorio_nao_faturados($id_contrato_plano_pessoa, $data_de, $data_ate, $operador){

	if ($id_contrato_plano_pessoa) {
		$dados = DBRead('', 'tb_contrato_plano_pessoa a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_contrato_plano_pessoa = '" . $id_contrato_plano_pessoa . "'");
		$empresa_legenda = $dados[0]['nome'];

		if ($dados[0]['nome_contrato']) {
			$empresa_legenda = $empresa_legenda . " (" . $dados[0]['nome_contrato'] . ") ";
		}

		$filtro_contrato_plano_pessoa = "AND id_contrato_plano_pessoa ='" . $id_contrato_plano_pessoa . "' ";
	} else {
		$empresa_legenda = "Todos";
	}

	if ($operador) {
		$dados_operadores = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_usuario = '" . $operador . "'");

		$operador_legenda = $dados_operadores[0]['nome'];
		$filtro_usuario = "AND id_usuario = '" . $operador . "'";
	} else {
		$operador_legenda = 'Todos';
	}

	$data_hoje = getDataHora();
	$data_hoje = converteDataHora($data_hoje);

	$periodo_amostra = "<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> de $data_de até $data_ate</span>";
	$gerado = "<span style=\"font-size: 14px;\"><strong>Gerado em: </strong> $data_hoje</span>";

	$data_de = converteData($data_de);
	$data_ate = converteData($data_ate);

	$dados_consulta = DBRead('', 'tb_atendimento', "WHERE ((gravado = '1' AND falha = 2) OR (desconsiderar = '1')) AND data_inicio BETWEEN '" . $data_de . " 00:00:00' AND '" . $data_ate . " 23:59:59' " . $filtro_contrato_plano_pessoa . " " . $filtro_usuario . " GROUP BY id_contrato_plano_pessoa", " COUNT(id_contrato_plano_pessoa) AS 'cont', id_contrato_plano_pessoa");

	registraLog('Relatório de Atendimentos - Não Faturados.','rel','relatorio_atendimento',1,"((gravado = '1' AND falha = 2) OR (desconsiderar = '1')) AND data_inicio BETWEEN '" . $data_de . " 00:00:00' AND '" . $data_ate . " 23:59:59' " . $filtro_contrato_plano_pessoa . " " . $filtro_usuario . " GROUP BY id_contrato_plano_pessoa");

	echo "<div class=\"col-md-8 col-md-offset-2\" style=\"padding: 0\">";
	echo "<legend style=\"text-align:center;\"><strong>Relatório de Atendimentos - Não Faturados</strong><br>$gerado</legend>";
	echo "<legend style=\"text-align:center;\">$periodo_amostra</legend>";
	echo "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong> Contrato - </strong>" . $empresa_legenda . ", <strong>Atendente - </strong>" . $operador_legenda . "</legend>";

	if ($dados_consulta) {

		$dados_total = DBRead('', 'tb_atendimento', "WHERE ((gravado = '1' AND falha = 2) OR (desconsiderar = '1')) AND data_inicio BETWEEN '" . $data_de . " 00:00:00' AND '" . $data_ate . " 23:59:59' " . $filtro_contrato_plano_pessoa . " " . $filtro_usuario . " ) as a", "sum(a.cnt) from (select count(id_contrato_plano_pessoa) as cnt");

		$contador_dados = "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong>Total: </strong> " . $dados_total[0]['sum(a.cnt)'] . "</span></legend>";
	}

	echo $contador_dados;

	if ($dados_consulta) {
		foreach ($dados_consulta as $dado_consulta) {

			$dados = DBRead('', 'tb_atendimento a', "WHERE ((a.gravado = '1' AND a.falha = 2) OR (a.desconsiderar = '1')) AND a.data_inicio BETWEEN '" . $data_de . " 00:00:00' AND '" . $data_ate . " 23:59:59' AND a.id_contrato_plano_pessoa = '" . $dado_consulta['id_contrato_plano_pessoa'] . "' " . $filtro_usuario . " ");

			$cont['cont'] = sizeof($dados);

			$conteudo_empresa = DBRead('', 'tb_contrato_plano_pessoa a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa INNER JOIN tb_plano c ON a.id_plano = c.id_plano WHERE a.id_contrato_plano_pessoa = '" . $dado_consulta['id_contrato_plano_pessoa'] . "'", "b.nome AS nome_empresa, a.*, c.*");

			if ($conteudo_empresa[0]['nome_contrato']) {
				$nome_contrato = " (" . $conteudo_empresa[0]['nome_contrato'] . ") ";
			} else {
				$nome_contrato = '';
			}

			$contrato = $conteudo_empresa[0]['nome_empresa'] . " " . $nome_contrato;

			$nome_empresa = "<span><strong>" . $contrato . "</strong></span>";

			echo "<ul class='list-group container-entrevista'>";
			echo "<li class='list-group-item'><div class='row'><div class='col-md-6 text-left'><strong style='font-size:16px'>" . $nome_empresa . "</strong></div><div class='col-md-6 text-right'><strong style='font-size:16px'>Total: </strong>" . $cont['cont'] . "</div></div></li>";

			foreach ($dados as $dado) {
				$conteudo_falha = DBRead('', 'tb_falha_atendimento a', "INNER JOIN tb_tipo_falha_atendimento b ON a.id_tipo_falha_atendimento = b.id_tipo_falha_atendimento WHERE a.id_atendimento = '" . $dado['id_atendimento'] . "' ");
				$texto_falha = $conteudo_falha[0]['texto_os'];
				if(!$conteudo_falha && $dado['desconsiderar'] == 1){
					$texto_falha = '<span style="color: red;"> Desconsiderado </span>';
				}
				
				$conteudo_pessoa = DBRead('', 'tb_pessoa a', "INNER JOIN tb_usuario b ON a.id_pessoa = b.id_pessoa WHERE b.id_usuario = '" . $dado['id_usuario'] . "' ");
				$nome_pessoa = $conteudo_pessoa[0]['nome'];

				echo "<li class='list-group-item'>
                    <div class='row'>
                        <div class='col-md-4 text-left'><strong>Atendente: </strong>" . $nome_pessoa . "</div>
                        <div class='col-md-4 text-left'><strong>Falha: </strong>" . $texto_falha . "</div>
                        <div class='col-md-4 text-left'><strong>Data: </strong>" . converteDataHora($dado['data_inicio']) . "</div>
                    </div>
                    <div class='row'>
                        <div class='col-md-4 text-left'><strong>Assinante: </strong>" . $dado['assinante'] . "</div>
                        <div class='col-md-4 text-left'><strong>Contato: </strong>" . $dado['contato'] . "</div>
                        <div class='col-md-4 text-left'><strong>Fone 1: </strong><span class='phone'>" . $dado['fone1'] . "</span></div>
                    </div>
                </li>";
			}
			echo "</ul>";
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

function relatorio_contagem($id_contrato_plano_pessoa, $data_de, $data_ate, $operador, $tipo_atendimento, $turno, $lider){

	if ($tipo_atendimento == 2) {
		$filtro_tipo_atendimento = " AND a.via_texto = 1";
		$filtro_legenda = 'Via texto';
	} else if ($tipo_atendimento == 1) {
		$filtro_tipo_atendimento = " AND a.via_texto != 1";
		$filtro_legenda = 'Via telefone';
	} else {
		$filtro_legenda = 'Todos';
	}

	if ($id_contrato_plano_pessoa) {
		$dados = DBRead('', 'tb_contrato_plano_pessoa a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_contrato_plano_pessoa = '" . $id_contrato_plano_pessoa . "'");
		$empresa_legenda = $dados[0]['nome'];

		if ($dados[0]['nome_contrato']) {
			$empresa_legenda = $empresa_legenda . " (" . $dados[0]['nome_contrato'] . ") ";
		}

		$filtro_contrato_plano_pessoa = "AND id_contrato_plano_pessoa ='" . $id_contrato_plano_pessoa . "' ";
	} else {
		$empresa_legenda = "Todos";
	}
	
	if ($operador) {
		$dados_operadores = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_usuario = '" . $operador . "'");

		$operador_legenda = $dados_operadores[0]['nome'];
		$filtro_usuario = "AND a.id_usuario = '" . $operador . "'";
	} else {
		$operador_legenda = 'Todos';
	}
  
	$data_de_dias = new DateTime(converteData($data_de));
	$data_de_dias->modify('first day of this month');
	$referencia_escala = $data_de_dias->format('Y-m-d');

	if($lider){
		$filtro_lider = "AND b.lider_direto = '".$lider."'";
		$dados_lider = DBRead('','tb_usuario a',"INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_usuario = '".$lider."'");
		$legenda_lider = $dados_lider[0]['nome'];
	}else{
		$legenda_lider = 'Qualquer';
		$filtro_lider = "";
	}
	
	if($turno){
		$filtro_turno = "AND d.data_inicial = '".$referencia_escala."' AND d.carga_horaria = '".$turno."' ";
		$inner_join_turno = "INNER JOIN tb_horarios_escala d ON a.id_usuario = d.id_usuario";
		if($turno == "integral"){
			$legenda_turno = "Integral";
		}else if($turno == "meio"){
			$legenda_turno = "Meio Turno";
		}else if($turno == "jovem"){
			$legenda_turno = "Jovem Aprendiz";
		}else if($turno == "estagio"){
			$legenda_turno = "Estágio";
		}
		
	}else{
		$legenda_turno = 'Qualquer';
		$filtro_turno = "";
		$inner_join_turno = "";
	}
	
	$data_hoje = getDataHora();
	$data_hoje = converteDataHora($data_hoje);

	$periodo_amostra = "<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> de $data_de até $data_ate</span>";
	$gerado = "<span style=\"font-size: 14px;\"><strong>Gerado em: </strong> $data_hoje</span>";

	$data_de = converteData($data_de);
	$data_ate = converteData($data_ate);

	echo "<div class=\"col-md-8 col-md-offset-2\" style=\"padding: 0\">";
	echo "<legend style=\"text-align:center;\"><strong>Relatório de Atendimentos - Contagem</strong><br>$gerado</legend>";
	echo "<legend style=\"text-align:center;\">$periodo_amostra</legend>";
	echo "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong> Contrato - </strong>".$empresa_legenda.", <strong>Atendente - </strong>".$operador_legenda.", <strong>Tipo do atendimento - </strong>".$filtro_legenda.", <strong>Líder - </strong>".$legenda_lider.", <strong>Turno/Horário - </strong>".$legenda_turno."</legend>";

 
	if ($id_contrato_plano_pessoa) {
		$filtro_contrato_plano_pessoa = "AND a.id_contrato_plano_pessoa = '" . $id_contrato_plano_pessoa . "'";
	} else {
		$filtro_contrato_plano_pessoa = "";
    }
    
    $array_atendimentos_operadores = array();

	$dados_consulta = DBRead('', 'tb_contrato_plano_pessoa a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa INNER JOIN tb_plano c ON a.id_plano = c.id_plano WHERE c.cod_servico = 'call_suporte' " . $filtro_contrato_plano_pessoa . " ORDER BY b.nome ASC", "a.status AS status_contrato, a.data_status, a.id_contrato_plano_pessoa, a.nome_contrato, b.nome, a.qtd_contratada, c.cod_servico, c.nome AS nome_plano");

	registraLog('Relatório de Atendimentos - Contagem.','rel','relatorio_atendimento',1,"INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa INNER JOIN tb_plano c ON a.id_plano = c.id_plano WHERE c.cod_servico = 'call_suporte' " . $filtro_contrato_plano_pessoa . " ORDER BY b.nome ASC");

	if ($dados_consulta) {
		echo '<table class="table table-hover dataTable" style="margin-bottom:0;">
			<thead>
				<tr>
					<th class="text-left col-md-3">Contrato</th>
					<th class="text-left col-md-4">Plano</th>
					<th class="text-left col-md-1">Qtd Contratada</th>
					<th class="text-left col-md-1">Faturados</th>
					<th class="text-left col-md-1">Não Faturados</th>
					<th class="text-left col-md-1">Total</th>
				</tr>
			</thead>
			<tbody>';


		$contador_fat = 0;
		$contador_n_fat = 0;
		$contador_total = 0;
		foreach ($dados_consulta as $dado_consulta) {

			$exibir = 0;
			if ($dado_consulta['status_contrato'] == '1' || $dado_consulta['data_status'] >= $data_de) {
				$exibir = 1;
			}
			if ($exibir != 0) {
				$dados_faturado = DBRead('', 'tb_atendimento a', "INNER JOIN tb_usuario b ON a.id_usuario = b.id_usuario INNER JOIN tb_pessoa c ON b.id_pessoa = c.id_pessoa ".$inner_join_turno." WHERE a.gravado = '1' AND a.falha != 2 AND a.data_inicio BETWEEN '" . $data_de . " 00:00:00' AND '" . $data_ate . " 23:59:59' AND a.id_contrato_plano_pessoa = '" . $dado_consulta['id_contrato_plano_pessoa'] . "' " . $filtro_usuario . " ". $filtro_tipo_atendimento." ".$filtro_lider." ".$filtro_turno." ", "a.id_usuario, c.nome");
                $cont_faturado = 0;                
                if($dados_faturado){
                    foreach ($dados_faturado as $conteudo_faturado) {
                        $array_atendimentos_operadores[$conteudo_faturado['id_usuario']]['faturado'] += 1;
                        $array_atendimentos_operadores[$conteudo_faturado['id_usuario']]['nome'] = $conteudo_faturado['nome'];
                        $cont_faturado++;
                    }
                }

				$dados_nao_faturado = DBRead('', 'tb_atendimento a', "INNER JOIN tb_usuario b ON a.id_usuario = b.id_usuario INNER JOIN tb_pessoa c ON b.id_pessoa = c.id_pessoa ".$inner_join_turno." WHERE a.gravado = '1' AND a.falha = '2' AND a.data_inicio BETWEEN '" . $data_de . " 00:00:00' AND '" . $data_ate . " 23:59:59' AND a.id_contrato_plano_pessoa = '" . $dado_consulta['id_contrato_plano_pessoa'] . "' " . $filtro_usuario . " ". $filtro_tipo_atendimento." ".$filtro_lider." ".$filtro_turno." ", "a.id_usuario, c.nome");
                $cont_nao_faturado = 0;
                if($dados_nao_faturado){
                    foreach ($dados_nao_faturado as $conteudo_nao_faturado) {
                        $array_atendimentos_operadores[$conteudo_nao_faturado['id_usuario']]['nao_faturado'] += 1;
                        $array_atendimentos_operadores[$conteudo_nao_faturado['id_usuario']]['nome'] = $conteudo_nao_faturado['nome'];
                        $cont_nao_faturado++;
                    }
                }
                
				$total = (int) $cont_faturado + (int) $cont_nao_faturado;

				if ($dado_consulta['nome_contrato']) {
					$nome_contrato = " (" . $dado_consulta['nome_contrato'] . ") ";
				} else {
					$nome_contrato = '';
				}

				$contrato = $dado_consulta['nome'] . " " . $nome_contrato;

				echo '<tr>
						<td class="text-left">' . $contrato . '</td>                
						<td class="text-left">' . getNomeServico($dado_consulta['cod_servico']) . " - " . $dado_consulta['nome_plano'] . '</td>                
						<td class="text-left">' . $dado_consulta['qtd_contratada'] . '</td>                
						<td class="text-left">' . $cont_faturado . '</td>
						<td class="text-left">' . $cont_nao_faturado . '</td>
						<td class="text-left">' . $total . '</td>
					</tr>';
				$contador_fat = $contador_fat + $cont_faturado;
				$contador_n_fat = $contador_n_fat + $cont_nao_faturado;
				$contador_total = $contador_total + $total;
			}
		}

		echo '		
			</tbody>';
		echo "<tfoot>";

		echo '<tr>';
		echo '<th>Totais</th>';
		echo '<th></th>';
		echo '<th></th>';
		echo '<th>' . $contador_fat . '</th>';
		echo '<th>' . $contador_n_fat . '</th>';
		echo '<th>' . $contador_total . '</th>';
		echo '</tr>';

		echo "</tfoot> ";
        echo '</table>';
        
        echo '<hr>';

        echo '<table class="table table-hover dataTable" style="margin-bottom:0;">
			<thead>
				<tr>
					<th class="text-left col-md-6">Atendente</th>
					<th class="text-left col-md-2">Faturados</th>
					<th class="text-left col-md-2">Não Faturados</th>
					<th class="text-left col-md-2">Total</th>
				</tr>
			</thead>
            <tbody>';
            $contador_fat = 0;
            $contador_n_fat = 0;
            $contador_total = 0;
            foreach ($array_atendimentos_operadores as $operador) {
                $atendente_nome = $operador['nome'];
                $atendente_faturado = $operador['faturado'] ? $operador['faturado'] : 0;
                $atendente_nao_faturado = $operador['nao_faturado'] ? $operador['nao_faturado'] : 0;
                $atendente_total = $atendente_faturado + $atendente_nao_faturado;
                echo '<tr>
                    <td class="text-left">' . $atendente_nome . '</td>           
                    <td class="text-left">' . $atendente_faturado . '</td>
                    <td class="text-left">' . $atendente_nao_faturado . '</td>
                    <td class="text-left">' . $atendente_total . '</td>
                </tr>';
                $contador_fat = $contador_fat + $atendente_faturado;
				$contador_n_fat = $contador_n_fat + $atendente_nao_faturado;
				$contador_total = $contador_total + $atendente_total;
            }

        echo '		
			</tbody>';
		echo "<tfoot>";

		echo '<tr>';
		echo '<th>Totais</th>';
		echo '<th>' . $contador_fat . '</th>';
		echo '<th>' . $contador_n_fat . '</th>';
		echo '<th>' . $contador_total . '</th>';
		echo '</tr>';

		echo "</tfoot> ";
        echo '</table>';

		echo '<br><br><br>';

		echo "<script>
				$(document).ready(function(){
					$('.dataTable').DataTable({
						\"language\": {
							\"url\": \"//cdn.datatables.net/plug-ins/1.10.19/i18n/Portuguese-Brasil.json\"
						},
						columnDefs: [
							{ type: 'chinese-string', targets: 0 },
						],				        
						\"searching\": false,
						\"paging\":   false,
						\"info\":     false
					});
				});
			</script>			
			";
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

function relatorio_elogios($id_contrato_plano_pessoa, $data_de, $data_ate, $operador){

	if ($id_contrato_plano_pessoa) {
		$dados = DBRead('', 'tb_contrato_plano_pessoa a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_contrato_plano_pessoa = '" . $id_contrato_plano_pessoa . "'");
		$empresa_legenda = $dados[0]['nome'];

		if ($dados[0]['nome_contrato']) {
			$empresa_legenda = $empresa_legenda . " (" . $dados[0]['nome_contrato'] . ") ";
		}

		$filtro_contrato_plano_pessoa = "AND id_contrato_plano_pessoa ='" . $id_contrato_plano_pessoa . "' ";
	} else {
		$empresa_legenda = "Todos";
	}

	if ($operador) {
		$dados_operadores = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_usuario = '" . $operador . "'");

		$operador_legenda = $dados_operadores[0]['nome'];
		$filtro_usuario = "AND id_usuario = '" . $operador . "'";
	} else {
		$operador_legenda = 'Todos';
	}

	$data_hoje = getDataHora();
	$data_hoje = converteDataHora($data_hoje);

	$periodo_amostra = "<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> de $data_de até $data_ate</span>";
	$gerado = "<span style=\"font-size: 14px;\"><strong>Gerado em: </strong> $data_hoje</span>";

	$data_de = converteData($data_de);
	$data_ate = converteData($data_ate);

	$dados = DBRead('', 'tb_atendimento', "WHERE gravado = '1' AND falha != 2 AND elogio = '1' AND data_inicio BETWEEN '" . $data_de . " 00:00:00' AND '" . $data_ate . " 23:59:59' " . $filtro_contrato_plano_pessoa . " " . $filtro_usuario . "");

	registraLog('Relatório de Atendimentos - Atendimento Encantador.','rel','relatorio_atendimento',1,"WHERE gravado = '1' AND falha != 2 AND elogio = '1' AND data_inicio BETWEEN '" . $data_de . " 00:00:00' AND '" . $data_ate . " 23:59:59' " . $filtro_contrato_plano_pessoa . " " . $filtro_usuario . "");

	if ($dados) {
		$contador_dados = "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong>Total: </strong> " . count($dados) . "</span></legend>";
	}

	echo "<div class=\"col-md-8 col-md-offset-2\" style=\"padding: 0\">";
	echo "<legend style=\"text-align:center;\"><strong>Relatório de Atendimentos - Atendimento Encantador</strong><br>$gerado</legend>";
	echo "<legend style=\"text-align:center;\">$periodo_amostra</legend>";
	echo "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong> Contrato - </strong>" . $empresa_legenda . ", <strong>Atendente - </strong>" . $operador_legenda . "</legend>";

	echo $contador_dados;

	if ($dados) {
		foreach ($dados as $dado) {

			$situacao_protocolo = DBRead('', 'tb_parametros', "WHERE id_contrato_plano_pessoa = '" . $dado['id_contrato_plano_pessoa'] . "'", "exibir_protocolo, solicitacao_cpf");

			$conteudo_empresa = DBRead('', 'tb_contrato_plano_pessoa a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_contrato_plano_pessoa = '" . $dado['id_contrato_plano_pessoa'] . "'");

			$conteudo_empresa = DBRead('', 'tb_contrato_plano_pessoa a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa INNER JOIN tb_plano c ON a.id_plano = c.id_plano WHERE a.id_contrato_plano_pessoa = '" . $dado['id_contrato_plano_pessoa'] . "'", "b.nome AS nome_empresa, a.*, c.*");

			if ($conteudo_empresa[0]['nome_contrato']) {
				$nome_contrato = " (" . $conteudo_empresa[0]['nome_contrato'] . ") ";
			} else {
				$nome_contrato = '';
			}

			$contrato = $conteudo_empresa[0]['nome_empresa'] . " " . $nome_contrato;

			$nome_empresa = "<span><strong>" . $contrato . "</strong></span>";

			$conteudo_pessoa = DBRead('', 'tb_pessoa a', "INNER JOIN tb_usuario b ON a.id_pessoa = b.id_pessoa WHERE b.id_usuario = '" . $dado['id_usuario'] . "' ");

			$nome_pessoa = $conteudo_pessoa[0]['nome'];

			echo "<ul class='list-group container-entrevista'>";
			echo "<li class='list-group-item'><div class='row'><div class='col-md-12 text-center'><strong style='font-size:16px'>" . $nome_empresa . "<br><span></strong>" . converteDataHora($dado['data_inicio']) . "</span></div></div></li>";

			echo "<li class='list-group-item'><strong>Assinante: </strong> " . $dado['assinante'] . "</li>";
			echo "<li class='list-group-item'><strong>Contato: </strong> " . $dado['contato'] . "</li>";
			if ($dado['fone2']) {
				echo "<li class='list-group-item'><strong>Telefone 1: </strong> <span class='phone'>" . $dado['fone1'] . "</span></li>";
				echo "<li class='list-group-item'><strong>Telefone 2: </strong> <span class='phone'>" . $dado['fone2'] . "</span></li>";
			} else {
				echo "<li class='list-group-item'><strong>Telefone 1: </strong> <span class='phone'>" . $dado['fone1'] . "</span></li>";
			}
			if ($situacao_protocolo[0]['solicitacao_cpf'] == 1) {
				if ($dado['cpf_cnpj']) {
					echo "<li class='list-group-item'><strong>CPF/CNPJ: </strong> <span>" . $dado['cpf_cnpj'] . "</span></li>";
				}
			}

			if ($dado['descricao_dado_adicional'] && $dado['dado_adicional']) {
				echo "<li class='list-group-item'><strong>" . $dado['descricao_dado_adicional'] . ": </strong> <span>" . $dado['dado_adicional'] . "</span></li>";
			}
			if ($situacao_protocolo[0]['exibir_protocolo'] == 1) {
				echo "<li class='list-group-item'><strong>Protocolo: </strong> <span>" . $dado['protocolo'] . "</span></li>";
			}

			echo "<li class='list-group-item'><strong>Atendente: </strong> " . $nome_pessoa . "</li>";
			echo "<li class='list-group-item'><strong>OS: </strong><br> " . nl2br($dado['os']) . "</li>";
			if ($dado['resolvido'] == 1) {
				echo "<li class='list-group-item'><strong>Situação: </strong>Resolvido</li>";
			} else if ($dado['resolvido'] == 2) {
				echo "<li class='list-group-item'><strong>Situação: </strong>Não resolvido</li>";
			} else if ($dado['resolvido'] == 3) {
				echo "<li class='list-group-item'><strong>Situação: </strong>Diagnosticado</li>";
			}

			echo "</ul>";
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

function relatorio_resolucao($id_contrato_plano_pessoa, $data_de, $data_ate, $operador, $id_area_problema, $id_subarea_problema, $id_plano){

	if ($id_contrato_plano_pessoa) {
		$dados = DBRead('', 'tb_contrato_plano_pessoa a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_contrato_plano_pessoa = '" . $id_contrato_plano_pessoa . "'");
		$empresa_legenda = $dados[0]['nome'];

		if ($dados[0]['nome_contrato']) {
			$empresa_legenda = $empresa_legenda . " (" . $dados[0]['nome_contrato'] . ") ";
		}

		$filtro_contrato_plano_pessoa = "AND id_contrato_plano_pessoa ='" . $id_contrato_plano_pessoa . "' ";
	} else {
		$empresa_legenda = "Todos";
	}

	if ($operador) {
		$dados_operadores = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_usuario = '" . $operador . "'");

		$operador_legenda = $dados_operadores[0]['nome'];
		$filtro_usuario = "AND id_usuario = '" . $operador . "'";
	} else {
		$operador_legenda = 'Todos';
	}

	if ($id_area_problema) {
		$dados_area = DBRead('', 'tb_area_problema', "WHERE id_area_problema = '" . $id_area_problema . "' ");

        $area_legenda = $dados_area[0]['nome'];        
        $inner_filtro_area = "INNER JOIN tb_subarea_problema_atendimento b ON a.id_atendimento = b.id_atendimento INNER JOIN tb_subarea_problema c ON b.id_subarea_problema = c.id_subarea_problema";
		$filtro_area = " AND c.id_area_problema = '" . $id_area_problema . "'";
		if ($id_area_problema == 'nao_identificada') {
			$area_legenda = "Atendimento Incompleto";
			$subarea_legenda = 'Atendimento Incompleto';
		}

		if ($id_subarea_problema) {
			$dados_subarea = DBRead('', 'tb_subarea_problema', "WHERE id_subarea_problema = '" . $id_subarea_problema . "' ");
	
			$subarea_legenda = $dados_subarea[0]['descricao'];
			$filtro_subarea = " AND b.id_subarea_problema = '" . $id_subarea_problema . "'";
		} else {
			$subarea_legenda = 'Todas';
		}

	} else {
		$area_legenda = 'Todas';
		$subarea_legenda = 'Todas';
	}

	if ($id_plano) {
		$dados_plano = DBRead('', 'tb_plano', "WHERE id_plano = '" . $id_plano . "' ");

		$plano_legenda = $dados_plano[0]['nome'];
		$filtro_plano = " AND a.id_plano = '" . $id_plano . "'";
	} else {
		$plano_legenda = 'Todos';
	}

	

	$data_hoje = getDataHora();
	$data_hoje = converteDataHora($data_hoje);

	$periodo_amostra = "<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> de $data_de até $data_ate</span>";
	$gerado = "<span style=\"font-size: 14px;\"><strong>Gerado em: </strong> $data_hoje</span>";

	$data_de = converteData($data_de);
	$data_ate = converteData($data_ate);

	echo "<div class=\"col-md-12\" style=\"padding: 0\">";
	echo "<legend style=\"text-align:center;\"><strong>Relatório de Atendimentos - Resolução</strong><br>$gerado</legend>";
	echo "<legend style=\"text-align:center;\">$periodo_amostra</legend>";
	echo "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong> Contrato - </strong>" . $empresa_legenda . ", <strong>Atendente - </strong>" . $operador_legenda . ", <strong>Área de Finalização - </strong>" . $area_legenda . ", <strong>Subárea de Finalização - </strong>" . $subarea_legenda . ", <strong>Plano - </strong>" . $plano_legenda . "</legend>";

	if ($id_contrato_plano_pessoa) {
		$filtro_contrato_plano_pessoa = "AND a.id_contrato_plano_pessoa = '" . $id_contrato_plano_pessoa . "'";
	} else {
		$filtro_contrato_plano_pessoa = "";
	}
	$dados_consulta = DBRead('', 'tb_contrato_plano_pessoa a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa INNER JOIN tb_plano c ON a.id_plano = c.id_plano WHERE c.cod_servico = 'call_suporte' " . $filtro_contrato_plano_pessoa . " ". $filtro_plano ." AND a.status = 1 AND b.nome NOT LIKE '%Belluno%' ORDER BY b.nome ASC", "a.*, b.*, a.status AS status_contrato");

	registraLog('Relatório de Atendimentos - Resolução.','rel','relatorio_atendimento',1,"INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa INNER JOIN tb_plano c ON a.id_plano = c.id_plano WHERE c.cod_servico = 'call_suporte' " . $filtro_contrato_plano_pessoa . " ". $filtro_plano ." AND a.status = 1 AND b.nome NOT LIKE '%Belluno%' ORDER BY b.nome ASC");

	if ($dados_consulta) {
		echo '<table class="table table-hover dataTable" style="margin-bottom:0;">
			<thead>
				<tr>
					<th class="text-left col-md-1">Contrato</th>
					<th class="text-left col-md-1">Plano</th>
					<th class="text-left col-md-1">Não Resolvido</th>
					<th class="text-left col-md-1">Não Resolvido (%)</th>
					<th class="text-left col-md-1">Resolvido</th>
					<th class="text-left col-md-1">Resolvido (%)</th>
					<th class="text-left col-md-1">Diagnosticado</th>
					<th class="text-left col-md-1">Diagnosticado (%)</th>
					<th class="text-left col-md-1">Diagnosticado + Resolvido</th>
					<th class="text-left col-md-1">Diagnosticado + Resolvido (%)</th>
					<th class="text-left col-md-1">Total</th>
				</tr>
			</thead>
			<tbody>';

		$contador_n_rest = 0;
		$contador_res = 0;
		$contador_diag = 0;
		$contador_total = 0;

		$resolucao_usuario = array();
		foreach ($dados_consulta as $dado_consulta) {
			$cont_nao_resolvidos = 0;
			$cont_resolvidos = 0;
			$cont_diagnosticados = 0;
			$total = 0;

			$cont_dados_nao_resolvidos = DBRead('', 'tb_atendimento a', " ".$inner_filtro_area."  WHERE a.falha != '2' AND a.gravado = '1' AND a.resolvido = '2' AND a.data_inicio BETWEEN '" . $data_de . " 00:00:00' AND '" . $data_ate . " 23:59:59' AND a.id_contrato_plano_pessoa = '" . $dado_consulta['id_contrato_plano_pessoa'] . "' " . $filtro_usuario . " ".$filtro_area." ".$filtro_subarea." ");

			if ($cont_dados_nao_resolvidos) {
				foreach ($cont_dados_nao_resolvidos as $conteudo) {
					$resolucao_usuario[$conteudo['id_usuario']]['nao_resolvidos'] += 1;
					$cont_nao_resolvidos += 1;
				}
			}

			$cont_dados_resolvidos = DBRead('', 'tb_atendimento a', " ".$inner_filtro_area." WHERE a.falha != '2' AND a.gravado = '1' AND a.resolvido = '1' AND a.data_inicio BETWEEN '" . $data_de . " 00:00:00' AND '" . $data_ate . " 23:59:59' AND a.id_contrato_plano_pessoa = '" . $dado_consulta['id_contrato_plano_pessoa'] . "' " . $filtro_usuario . " ".$filtro_area." ".$filtro_subarea." ");

			if ($cont_dados_resolvidos) {
				foreach ($cont_dados_resolvidos as $conteudo) {
					$resolucao_usuario[$conteudo['id_usuario']]['resolvidos'] += 1;
					$cont_resolvidos += 1;
				}
			}

			$cont_dados_diagnosticados = DBRead('', 'tb_atendimento a', " ".$inner_filtro_area." WHERE a.falha != '2' AND a.gravado = '1' AND a.resolvido = '3' AND a.data_inicio BETWEEN '" . $data_de . " 00:00:00' AND '" . $data_ate . " 23:59:59' AND a.id_contrato_plano_pessoa = '" . $dado_consulta['id_contrato_plano_pessoa'] . "' " . $filtro_usuario . " ".$filtro_area." ".$filtro_subarea." ");

			if ($cont_dados_diagnosticados) {
				foreach ($cont_dados_diagnosticados as $conteudo) {
					$resolucao_usuario[$conteudo['id_usuario']]['diagnosticados'] += 1;
					$cont_diagnosticados += 1;
				}
			}

			$total = $cont_nao_resolvidos + $cont_resolvidos + $cont_diagnosticados;

			if ($dado_consulta['nome_contrato']) {
				$nome_contrato = " (" . $dado_consulta['nome_contrato'] . ") ";
			} else {
				$nome_contrato = '';
			}

			$contrato = $dado_consulta['nome'] . " " . $nome_contrato;

			$dados_planos = DBRead('', 'tb_plano', "WHERE id_plano = '" . $dado_consulta['id_plano'] . "'");

			$percentual_cont_nao_resolvidos = sprintf("%01.2f", round($cont_nao_resolvidos * 100) / ($total == 0 ? 1 : $total), 2);
			$percentual_cont_diagnosticados = sprintf("%01.2f", round($cont_diagnosticados * 100) / ($total == 0 ? 1 : $total), 2);
			$percentual_cont_resolvidos = sprintf("%01.2f", round($cont_resolvidos * 100) / ($total == 0 ? 1 : $total), 2);

			$cont_diagnosticados_resolvidos = $cont_diagnosticados + $cont_resolvidos;
			$percentual_cont_diagnosticados_resolvidos = sprintf("%01.2f", round($cont_diagnosticados_resolvidos * 100) / ($total == 0 ? 1 : $total), 2);
			echo "<div class ='row'>";
			echo '<tr>
					<td class="text-left">' . $contrato . '</td>                
					<td class="text-left">' . getNomeServico($dados_planos[0]['cod_servico']) . " - " . $dados_planos[0]['nome'] . '</td>                
					<td class="text-left">' . $cont_nao_resolvidos . '</td>
					<td class="text-left">' . $percentual_cont_nao_resolvidos . '%</td>
					<td class="text-left">' . $cont_resolvidos . '</td>                
					<td class="text-left">' . $percentual_cont_resolvidos . '%</td>    
					<td class="text-left">' . $cont_diagnosticados . '</td>
					<td class="text-left">' . $percentual_cont_diagnosticados . '%</td>
					<td class="text-left">' . $cont_diagnosticados_resolvidos . '</td>                
					<td class="text-left">' . $percentual_cont_diagnosticados_resolvidos . '%</td>  
					<td class="text-left">' . $total . '</td>                
				</tr>';

			$contador_n_rest = $contador_n_rest + $cont_nao_resolvidos;
			$contador_res = $contador_res + $cont_resolvidos;
			$contador_diag = $contador_diag + $cont_diagnosticados;
			$contador_total = $contador_total + $total;
		}
		$percentual_contador_n_rest = sprintf("%01.2f", ($contador_n_rest * 100) / ($contador_total == 0 ? 1 : $contador_total));
		$percentual_contador_res = sprintf("%01.2f", ($contador_res * 100) / ($contador_total == 0 ? 1 : $contador_total));
		$percentual_contador_diag = sprintf("%01.2f", ($contador_diag * 100) / ($contador_total == 0 ? 1 : $contador_total));
		$contador_diag_res = $contador_diag + $contador_res;
		$percentual_contador_diag_res = sprintf("%01.2f", ($contador_diag_res * 100) / ($contador_total == 0 ? 1 : $contador_total));

		echo '</tbody>';
		echo "<tfoot>";

		echo '<tr>';
		echo '<th>Totais</th>';
		echo '<th></th>';
		echo '<th>' . $contador_n_rest . '</th>';
		echo '<th>' . $percentual_contador_n_rest . '%</th>';
		echo '<th>' . $contador_res . '</th>';
		echo '<th>' . $percentual_contador_res . '%</th>';
		echo '<th>' . $contador_diag . '</th>';
		echo '<th>' . $percentual_contador_diag . '%</th>';
		echo '<th>' . $contador_diag_res . '</th>';
		echo '<th>' . $percentual_contador_diag_res . '%</th>';
		echo '<th>' . $contador_total . '</th>';
		echo '</tr>';

		echo "</tfoot> ";
		echo '</table>';
		if ($operador) {
			echo "<hr><legend style=\"text-align:center;\"><strong>Atendente</strong></legend>";
		} else {
			echo "<hr><legend style=\"text-align:center;\"><strong>Atendentes</strong></legend>";
		}


		echo '<table class="table table-hover dataTable" style="margin-bottom:0;">
			<thead>
				<tr>
					<th class="text-left col-md-1">Atendente</th>
					<th class="text-left col-md-1">Não Resolvido</th>
					<th class="text-left col-md-1">Não Resolvido (%)</th>
					<th class="text-left col-md-1">Resolvido</th>
					<th class="text-left col-md-1">Resolvido (%)</th>
					<th class="text-left col-md-1">Diagnosticado</th>
					<th class="text-left col-md-1">Diagnosticado (%)</th>
					<th class="text-left col-md-1">Diagnosticado + Resolvido</th>
					<th class="text-left col-md-1">Diagnosticado + Resolvido (%)</th>
					<th class="text-left col-md-1">Total</th>
				</tr>
			</thead>
			<tbody>';

		foreach ($resolucao_usuario as $id => $conteudo) {
			$dados = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE id_usuario = '" . $id . "' ", "b.nome");
			if ($operador && $operador == $id || !$operador) {

				$total_atendente = $resolucao_usuario[$id]['nao_resolvidos'] + $resolucao_usuario[$id]['resolvidos'] + $resolucao_usuario[$id]['diagnosticados'];

				$percentual_cont_nao_resolvidos_atendente = sprintf("%01.2f", ($resolucao_usuario[$id]['nao_resolvidos'] * 100) / ($total_atendente == 0 ? 1 : $total_atendente));
				$percentual_cont_resolvidos_atendente = sprintf("%01.2f", ($resolucao_usuario[$id]['resolvidos'] * 100) / ($total_atendente == 0 ? 1 : $total_atendente));
				$percentual_cont_diagnosticados_atendente = sprintf("%01.2f", ($resolucao_usuario[$id]['diagnosticados'] * 100) / ($total_atendente == 0 ? 1 : $total_atendente));

				$cont_diagnosticados_resolvidos_atendente = $resolucao_usuario[$id]['diagnosticados'] + $resolucao_usuario[$id]['resolvidos'];
				$percentual_cont_diagnosticados_resolvidos_atendente = sprintf("%01.2f", round($cont_diagnosticados_resolvidos_atendente * 100) / ($total_atendente == 0 ? 1 : $total_atendente), 2);

				echo '<tr>
						<td class="text-left">' . $dados[0]['nome'] . '</td>                
						<td class="text-left">' . (!$resolucao_usuario[$id]['nao_resolvidos'] ? 0 : $resolucao_usuario[$id]['nao_resolvidos']) . '</td>                
						<td class="text-left">' . $percentual_cont_nao_resolvidos_atendente . '%</td> 
						<td class="text-left">' . (!$resolucao_usuario[$id]['resolvidos'] ? 0 : $resolucao_usuario[$id]['resolvidos']) . '</td>                
						<td class="text-left">' . $percentual_cont_resolvidos_atendente . '%</td>    
						<td class="text-left">' . (!$resolucao_usuario[$id]['diagnosticados'] ? 0 : $resolucao_usuario[$id]['diagnosticados']) . '</td>                
						<td class="text-left">' . $percentual_cont_diagnosticados_atendente . '%</td>   
						<td class="text-left">' . (!$cont_diagnosticados_resolvidos_atendente ? 0 : $cont_diagnosticados_resolvidos_atendente) . '</td>                
						<td class="text-left">' . $percentual_cont_diagnosticados_resolvidos_atendente . '%</td>                
						<td class="text-left">' . $total_atendente . '</td>                
					</tr>';
			}
		}
		echo "</tfoot> ";
		echo '</table><br><br><br>';

		echo "<script>
				$(document).ready(function(){
					$('.dataTable').DataTable({
						\"language\": {
							\"url\": \"//cdn.datatables.net/plug-ins/1.10.19/i18n/Portuguese-Brasil.json\"
						},
						columnDefs: [
							{ type: 'chinese-string', targets: 0 },
						],				        
						\"searching\": false,
						\"paging\":   false,
						\"info\":     false
					});
				});
			</script>			
			";
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
	echo "</div>";
}

function relatorio_falhas($id_contrato_plano_pessoa, $data_de, $data_ate, $operador, $falha, $faturado, $resolvido){

	if ($id_contrato_plano_pessoa) {
		$dados = DBRead('', 'tb_contrato_plano_pessoa a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_contrato_plano_pessoa = '" . $id_contrato_plano_pessoa . "'");
		$empresa_legenda = $dados[0]['nome'];

		if ($dados[0]['nome_contrato']) {
			$empresa_legenda = $empresa_legenda . " (" . $dados[0]['nome_contrato'] . ") ";
		}

		$filtro_contrato_plano_pessoa = " AND a.id_contrato_plano_pessoa ='" . $id_contrato_plano_pessoa . "' ";
	} else {
		$empresa_legenda = "Todos";
	}

	if ($operador) {
		$dados_operadores = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_usuario = '" . $operador . "'");

		$operador_legenda = $dados_operadores[0]['nome'];
		$filtro_usuario = " AND a.id_usuario = '" . $operador . "'";
	} else {
		$operador_legenda = 'Todos';
	}

	if ($falha) {
		$dados_falha = DBRead('', 'tb_tipo_falha_atendimento', "WHERE id_tipo_falha_atendimento = '" . $falha . "'");
		$filtro_falha = " AND b.id_tipo_falha_atendimento = '" . $falha . "' ";
		$filtro_falha2 = " AND e.id_tipo_falha_atendimento = '" . $falha . "' ";

		$falha_legenda = $dados_falha[0]['opcao'];
	} else {
		$falha_legenda = 'Todos';
	}

	if ($faturado) {
		$filtro_faturado = " AND a.falha = '" . $faturado . "' ";
		if ($faturado == 1) {
			$faturado_legenda = 'Sim';
		} else {
			$faturado_legenda = 'Não';
		}
	} else {
		$faturado_legenda = 'Todos';
	}

	if ($resolvido) {
		$filtro_resolvido = " AND a.resolvido = '" . $resolvido . "' ";
		if ($resolvido == 1) {
			$resolvido_legenda = 'Resolvido';
		} else if ($resolvido == 2) {
			$resolvido_legenda = 'Não Resolvido';
		} else {
			$resolvido_legenda = 'Diagnosticado';
		}
	} else {
		$resolvido_legenda = 'Todos';
	}

	$data_hoje = getDataHora();
	$data_hoje = converteDataHora($data_hoje);

	$periodo_amostra = "<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> de $data_de até $data_ate</span>";
	$gerado = "<span style=\"font-size: 14px;\"><strong>Gerado em: </strong> $data_hoje</span>";

	$data_de = converteData($data_de);
	$data_ate = converteData($data_ate);

	echo "<div class=\"col-md-12\" style=\"padding: 0\">";
	echo "<legend style=\"text-align:center;\"><strong>Relatório de Atendimentos - Falhas</strong><br>$gerado</legend>";
	echo "<legend style=\"text-align:center;\">$periodo_amostra</legend>";
	echo "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong> Contrato - </strong>" . $empresa_legenda . ", <strong>Tipo de Falha - </strong>" . $falha_legenda . ", <strong>Faturado - </strong>" . $faturado_legenda . ", <strong>Resolvido - </strong>" . $resolvido_legenda . ", <strong>Atendente - </strong>" . $operador_legenda . "</legend>";

    $dados_consulta = DBRead('', 'tb_atendimento a', "INNER JOIN tb_contrato_plano_pessoa b ON a.id_contrato_plano_pessoa = b.id_contrato_plano_pessoa INNER JOIN tb_pessoa c ON b.id_pessoa = c.id_pessoa INNER JOIN tb_falha_atendimento d ON a.id_atendimento = d.id_atendimento INNER JOIN tb_tipo_falha_atendimento e ON d.id_tipo_falha_atendimento = e.id_tipo_falha_atendimento WHERE a.gravado = '1' AND a.data_inicio >= '" . $data_de . " 00:00:00' AND a.data_inicio < '" . $data_ate . " 23:59:59' AND a.falha != 0 " . $filtro_contrato_plano_pessoa . " " . $filtro_usuario . " " . $filtro_faturado . " " . $filtro_resolvido . " " . $filtro_falha2 . " ORDER BY c.nome ASC", "a.*, c.*, d.*, e.*");

	registraLog('Relatório de Atendimentos - Falhas.','rel','relatorio_atendimento',1,"INNER JOIN tb_contrato_plano_pessoa b ON a.id_contrato_plano_pessoa = b.id_contrato_plano_pessoa INNER JOIN tb_pessoa c ON b.id_pessoa = c.id_pessoa INNER JOIN tb_falha_atendimento d ON a.id_atendimento = d.id_atendimento INNER JOIN tb_tipo_falha_atendimento e ON d.id_tipo_falha_atendimento = e.id_tipo_falha_atendimento WHERE a.gravado = '1' AND a.data_inicio >= '" . $data_de . " 00:00:00' AND a.data_inicio < '" . $data_ate . " 23:59:59' AND a.falha != 0 " . $filtro_contrato_plano_pessoa . " " . $filtro_usuario . " " . $filtro_faturado . " " . $filtro_resolvido . " " . $filtro_falha2 . " ORDER BY c.nome ASC");


	if ($dados_consulta) {
		$contador_dados = "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong>Total: </strong> " . count($dados_consulta) . "</span></legend>";
	}
	echo $contador_dados;

	if ($dados_consulta) {
		$qtd_empresas = array();
		$qtd_falha = array();
		$qtd_atendentes = array();
		$qtd_faturar = array();
		$qtd_resolvidos = array();
		$qtd_plano = array();

		echo '<table class="table table-hover dataTable" style="margin-bottom:0;">
		      <thead>
		        <tr>
		            <th class="text-left  col-md-2">Contrato</th>
			        <th class="text-left  col-md-2">Plano</th>
		            <th class="text-left  col-md-2">Data</th>
		            <th class="text-left  col-md-2">Tipo de Falha</th>
		            <th class="text-left  col-md-1">Faturado</th>
		            <th class="text-left  col-md-1">Resolução</th>
		            <th class="text-left  col-md-2">Atendente</th>
		        </tr>
		      </thead>
		      <tbody>';

		foreach ($dados_consulta as $conteudo_consulta) {

			$dados_atendente = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_usuario = '" . $conteudo_consulta['id_usuario'] . "'", "a.id_usuario, b.nome");

			$dados_planos = DBRead('', 'tb_plano', "WHERE id_plano = '" . $conteudo_consulta['id_plano'] . "'");

			$dados_falhas = DBRead('', 'tb_atendimento a', "INNER JOIN tb_falha_atendimento b ON a.id_atendimento = b.id_atendimento INNER JOIN tb_tipo_falha_atendimento c ON b.id_tipo_falha_atendimento = c.id_tipo_falha_atendimento WHERE a.id_atendimento = '" . $conteudo_consulta['id_atendimento'] . "' " . $filtro_falha . "");

			//getNomeServico($dados_planos[0]['cod_servico'])." - "

			if ($conteudo_consulta['resolvido'] == 1) {
				$resolvido = 'Resolvido';
			} else if ($conteudo_consulta['resolvido'] == 2) {
				$resolvido = 'Não Resolvido';
			} else if ($conteudo_consulta['resolvido'] == 3) {
				$resolvido = 'Diagnosticado';
			}

			if ($conteudo_consulta['falha'] == 1) {
				$faturar = 'Sim';
			} else if ($conteudo_consulta['falha'] == 2) {
				$faturar = 'Não';
			}
			if ($conteudo_consulta['nome_contrato']) {
				$nome_contrato = " (" . $conteudo_consulta['nome_contrato'] . ") ";
			} else {
				$nome_contrato = '';
			}

			$contrato = $conteudo_consulta['nome'] . " " . $nome_contrato;


			if ($dados_falhas) {
				echo '<tr>
			            <td class="text-left">' . $contrato . '</td>    
			            <td class="text-left">' . getNomeServico($dados_planos[0]['cod_servico']) . " - " . $dados_planos[0]['nome'] . '</td>
			            <td class="text-left">' . converteDataHora($conteudo_consulta['data_inicio']) . '</td>
			            <td class="text-left">' . $dados_falhas[0]['opcao'] . '</td>
			            <td class="text-left">' . $faturar . '</td>
			            <td class="text-left">' . $resolvido . '</td>
			            <td class="text-left">' . $dados_atendente[0]['nome'] . '</td>
			        </tr>';

				$qtd_empresas[$contrato] += 1;
				$qtd_falha[$dados_falhas[0]['opcao']] += 1;
				$qtd_atendentes[$dados_atendente[0]['nome']] += 1;
				$qtd_faturar[$faturar] += 1;
				$qtd_resolvidos[$resolvido] += 1;
				$qtd_plano[getNomeServico($dados_planos[0]['cod_servico']) . " - " . $dados_planos[0]['nome']] += 1;
			}
		}

		echo "</tbody>
		   
		</table>

		<hr>";

		if (!$id_contrato_plano_pessoa) {
			echo '
				<table class="table table-hover dataTable" style="margin-bottom:0;">
					      <thead>
					        <tr>
					            <th class="text-left col-md-8">Contrato</th>
					            <th class="text-left col-md-4">Total</th>
					        </tr>
					      </thead>
					      <tbody>';

			$aux_empresa = 0;

			arsort($qtd_empresas);
			foreach ($qtd_empresas as $empresa => $qtd) {
				echo '<tr>';
				echo '<td>' . $empresa . '</td>';
				echo '<td>' . $qtd . '</td>';
				echo '</tr>';
				$aux_empresa = $aux_empresa + (int) $qtd;
			}
			echo '</tbody>';
			echo '</table>
				<hr>';
		}

		echo '
				<table class="table table-hover dataTable" style="margin-bottom:0;">
					      <thead>
					        <tr>
					            <th class="text-left col-md-8">Plano</th>
					            <th class="text-left col-md-4">Total</th>
					        </tr>
					      </thead>
					      <tbody>';


		arsort($qtd_plano);
		foreach ($qtd_plano as $planos => $qtd) {
			echo '<tr>';
			echo '<td>' . $planos . '</td>';
			echo '<td>' . $qtd . '</td>';
			echo '</tr>';
		}
		echo '</tbody>
				</table>
				<hr>';


		echo '
				<table class="table table-hover dataTable" style="margin-bottom:0;">
					      <thead>
					        <tr>			       
					            <th class="text-left col-md-8">Tipo de falha</th>
					            <th class="text-left col-md-4">Total</th>
					        </tr>
					      </thead>
					      <tbody>';

		arsort($qtd_falha);
		foreach ($qtd_falha as $falha => $qtd) {
			echo '<tr>';
			echo '<td>' . $falha . '</td>';
			echo '<td>' . $qtd . '</td>';
			echo '</tr>';
		}
		echo '</tbody>
				</table>
				<hr>';

		echo '
				<table class="table table-hover dataTable" style="margin-bottom:0;">
					      <thead>
					        <tr>
					            <th class="text-left col-md-8">Faturado</th>
					            <th class="text-left col-md-4">Total</th>
					        </tr>
					      </thead>
					      <tbody>';

		arsort($qtd_faturar);
		foreach ($qtd_faturar as $faturarados => $qtd) {
			echo '<tr>';
			echo '<td>' . $faturarados . '</td>';
			echo '<td>' . $qtd . '</td>';
			echo '</tr>';
		}
		echo ' </tbody>
				</table>
				<br>';

		echo '
				<table class="table table-hover dataTable" style="margin-bottom:0;">
					      <thead>
					        <tr>
					            <th class="text-left col-md-8">Resolução</th>
					            <th class="text-left col-md-4">Total</th>
					        </tr>
					      </thead>
					      <tbody>';

		arsort($qtd_resolvidos);
		foreach ($qtd_resolvidos as $resolvidos => $qtd) {
			echo '<tr>';
			echo '<td>' . $resolvidos . '</td>';
			echo '<td>' . $qtd . '</td>';
			echo '</tr>';
		}
		echo '</tbody>
				</table>
				<hr>';

		echo '
				<table class="table table-hover dataTable" style="margin-bottom:0;">
					      <thead>
					        <tr>
					            <th class="text-left col-md-8">Atendente</th>
					            <th class="text-left col-md-4">Total</th>
					        </tr>
					      </thead>
					      <tbody>';

		arsort($qtd_atendentes);
		foreach ($qtd_atendentes as $atendente => $qtd) {
			echo '<tr>';
			echo '<td>' . $atendente . '</td>';
			echo '<td>' . $qtd . '</td>';
			echo '</tr>';
		}
		echo '</tbody>
				</table>
				<hr>';


		echo "</div>";
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

function relatorio_situacao($id_contrato_plano_pessoa, $data_de, $data_ate, $operador, $situacao){

	if ($id_contrato_plano_pessoa) {
		$dados = DBRead('', 'tb_contrato_plano_pessoa a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_contrato_plano_pessoa = '" . $id_contrato_plano_pessoa . "'");
		$empresa_legenda = $dados[0]['nome'];

		if ($dados[0]['nome_contrato']) {
			$empresa_legenda = $empresa_legenda . " (" . $dados[0]['nome_contrato'] . ") ";
		}

		$filtro_contrato_plano_pessoa = " AND a.id_contrato_plano_pessoa ='" . $id_contrato_plano_pessoa . "' ";
	} else {
		$empresa_legenda = "Todos";
	}

	if ($operador) {
		$dados_operadores = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_usuario = '" . $operador . "'");

		$operador_legenda = $dados_operadores[0]['nome'];
		$filtro_usuario = " AND a.id_usuario = '" . $operador . "'";
	} else {
		$operador_legenda = 'Todos';
	}

	if ($situacao) {
		$dados_situacao = DBRead('', 'tb_situacao', "WHERE id_situacao = '" . $situacao . "'");
		$situacao_legenda =  ucwords(strtolower($dados_situacao[0]['nome']));
		$filtro_situacao = " AND b.id_situacao = '" . $situacao . "'";
	} else {
		$situacao_legenda = 'Todas';
	}

	$data_hoje = getDataHora();
	$data_hoje = converteDataHora($data_hoje);

	$periodo_amostra = "<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> de $data_de até $data_ate</span>";
	$gerado = "<span style=\"font-size: 14px;\"><strong>Gerado em: </strong> $data_hoje</span>";

	$data_de = converteData($data_de);
	$data_ate = converteData($data_ate);

	echo "<div class=\"col-md-12\" style=\"padding: 0\">";
	echo "<legend style=\"text-align:center;\"><strong>Relatório de Atendimentos - Situações</strong><br>$gerado</legend>";
	echo "<legend style=\"text-align:center;\">$periodo_amostra</legend>";
	echo "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong> Contrato - </strong>" . $empresa_legenda . ", <strong>Situação - </strong>" . $situacao_legenda . ", <strong>Atendente - </strong>" . $operador_legenda . "</legend>";


	if ($id_contrato_plano_pessoa) {
		$filtro_contrato_plano_pessoa = "AND a.id_contrato_plano_pessoa = '" . $id_contrato_plano_pessoa . "'";
	} else {
		$filtro_contrato_plano_pessoa = "";
	}

	$dados_consulta = DBRead('', 'tb_atendimento a', "INNER JOIN tb_situacao_atendimento b ON a.id_atendimento = b.id_atendimento INNER JOIN tb_situacao c ON b.id_situacao = c.id_situacao INNER JOIN tb_contrato_plano_pessoa d ON a.id_contrato_plano_pessoa = d.id_contrato_plano_pessoa INNER JOIN tb_pessoa e ON d.id_pessoa = e.id_pessoa INNER JOIN tb_usuario f ON a.id_usuario = f.id_usuario INNER JOIN tb_pessoa g ON f.id_pessoa = g.id_pessoa WHERE a.data_inicio >= '" . $data_de . " 00:00:00' AND a.data_inicio < '" . $data_ate . " 23:59:59' " . $filtro_contrato_plano_pessoa . " " . $filtro_usuario . " " . $filtro_situacao . " ORDER BY c.nome ASC", "a.id_atendimento, c.nome, a.id_contrato_plano_pessoa, a.data_inicio, a.id_usuario, d.id_plano, e.nome AS nome_empresa, g.nome AS nome_atendente, d.nome_contrato");

	registraLog('Relatório de Atendimentos - Situações.','rel','relatorio_atendimento',1,"INNER JOIN tb_situacao_atendimento b ON a.id_atendimento = b.id_atendimento INNER JOIN tb_situacao c ON b.id_situacao = c.id_situacao INNER JOIN tb_contrato_plano_pessoa d ON a.id_contrato_plano_pessoa = d.id_contrato_plano_pessoa INNER JOIN tb_pessoa e ON d.id_pessoa = e.id_pessoa INNER JOIN tb_usuario f ON a.id_usuario = f.id_usuario INNER JOIN tb_pessoa g ON f.id_pessoa = g.id_pessoa WHERE a.data_inicio >= '" . $data_de . " 00:00:00' AND a.data_inicio < '" . $data_ate . " 23:59:59' " . $filtro_contrato_plano_pessoa . " " . $filtro_usuario . " " . $filtro_situacao . " ORDER BY c.nome ASC");


	if ($dados_consulta) {
		$contador_dados = "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong>Total: </strong> " . count($dados_consulta) . "</span></legend>";
	}
	echo $contador_dados;

	if ($dados_consulta) {
		$qtd_empresas = array();
		$qtd_situacao = array();
		$qtd_atendentes = array();
		$qtd_plano = array();

		echo '<table class="table table-hover dataTable" style="margin-bottom:0;">
		      <thead>
		        <tr>
		            <th class="text-left  col-md-3">Contrato</th>
			        <th class="text-left  col-md-2">Plano</th>
		            <th class="text-left  col-md-2">Data</th>
		            <th class="text-left  col-md-3">Situação</th>
		            <th class="text-left  col-md-2">Atendente</th>
		        </tr>
		      </thead>
		      <tbody>';

		foreach ($dados_consulta as $conteudo_consulta) {

			$dados_planos = DBRead('', 'tb_plano', "WHERE id_plano = '" . $conteudo_consulta['id_plano'] . "'");

			if ($conteudo_consulta['nome_contrato']) {
				$nome_contrato = " (" . $conteudo_consulta['nome_contrato'] . ") ";
			} else {
				$nome_contrato = '';
			}

			$contrato = $conteudo_consulta['nome_empresa'] . " " . $nome_contrato;
			echo '<tr>
			            <td class="text-left">' . $contrato . '</td>    
			            <td class="text-left">' . getNomeServico($dados_planos[0]['cod_servico']) . " - " . $dados_planos[0]['nome'] . '</td>
			            <td class="text-left">' . converteDataHora($conteudo_consulta['data_inicio']) . '</td>
			            <td class="text-left">' . $conteudo_consulta['nome'] . '</td>
			            <td class="text-left">' . $conteudo_consulta['nome_atendente'] . '</td>
			        </tr>';

			$qtd_empresas[$contrato] += 1;
			$qtd_situacao[$conteudo_consulta['nome']] += 1;
			$qtd_atendentes[$conteudo_consulta['nome_atendente']] += 1;
			$qtd_plano[getNomeServico($dados_planos[0]['cod_servico']) . " - " . $dados_planos[0]['nome']] += 1;
		}

		echo "</tbody>
		   
		</table>
	
		<hr>";

		if (!$id_contrato_plano_pessoa) {
			echo '
				<table class="table table-hover dataTable" style="margin-bottom:0;">
					      <thead>
					        <tr>
					            <th class="text-left col-md-8">Contrato</th>
					            <th class="text-left col-md-4">Total</th>
					        </tr>
					      </thead>
					      <tbody>';

			$aux_empresa = 0;

			arsort($qtd_empresas);
			foreach ($qtd_empresas as $empresa => $qtd) {
				echo '<tr>';
				echo '<td>' . $empresa . '</td>';
				echo '<td>' . $qtd . '</td>';
				echo '</tr>';
				$aux_empresa = $aux_empresa + (int) $qtd;
			}
			echo '</tbody>';
			echo '</table>
				<hr>';
		}

		echo '
				<table class="table table-hover dataTable" style="margin-bottom:0;">
					      <thead>
					        <tr>
					            <th class="text-left col-md-8">Plano</th>
					            <th class="text-left col-md-4">Total</th>
					        </tr>
					      </thead>
					      <tbody>';


		arsort($qtd_plano);
		foreach ($qtd_plano as $planos => $qtd) {
			echo '<tr>';
			echo '<td>' . $planos . '</td>';
			echo '<td>' . $qtd . '</td>';
			echo '</tr>';
		}
		echo '</tbody>
				</table>
				<hr>';


		echo '
				<table class="table table-hover dataTable" style="margin-bottom:0;">
					      <thead>
					        <tr>			       
					            <th class="text-left col-md-8">Situação</th>
					            <th class="text-left col-md-4">Total</th>
					        </tr>
					      </thead>
					      <tbody>';

		arsort($qtd_situacao);
		foreach ($qtd_situacao as $situacao => $qtd) {
			echo '<tr>';
			echo '<td>' . $situacao . '</td>';
			echo '<td>' . $qtd . '</td>';
			echo '</tr>';
		}
		echo '</tbody>
				</table>
				<hr>';

		echo '
				<table class="table table-hover dataTable" style="margin-bottom:0;">
					      <thead>
					        <tr>
					            <th class="text-left col-md-8">Atendente</th>
					            <th class="text-left col-md-4">Total</th>
					        </tr>
					      </thead>
					      <tbody>';

		arsort($qtd_atendentes);
		foreach ($qtd_atendentes as $atendente => $qtd) {
			echo '<tr>';
			echo '<td>' . $atendente . '</td>';
			echo '<td>' . $qtd . '</td>';
			echo '</tr>';
		}
		echo '</tbody>
				</table>
				<hr>';


		echo "</div>";
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

function relatorio_subarea($id_contrato_plano_pessoa, $data_de, $data_ate, $operador, $id_area_problema, $id_subarea_problema, $situacao){

	if ($id_contrato_plano_pessoa) {
		$dados = DBRead('', 'tb_contrato_plano_pessoa a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_contrato_plano_pessoa = '" . $id_contrato_plano_pessoa . "'");
		$empresa_legenda = $dados[0]['nome'];

		if ($dados[0]['nome_contrato']) {
			$empresa_legenda = $empresa_legenda . " (" . $dados[0]['nome_contrato'] . ") ";
		}

		$filtro_contrato_plano_pessoa = " AND a.id_contrato_plano_pessoa ='" . $id_contrato_plano_pessoa . "' ";
	} else {
		$empresa_legenda = "Todos";
	}

	if ($operador) {
		$dados_operadores = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_usuario = '" . $operador . "'");

		$operador_legenda = $dados_operadores[0]['nome'];
		$filtro_usuario = " AND a.id_usuario = '" . $operador . "'";
	} else {
		$operador_legenda = 'Todos';
	}

	if ($id_subarea_problema) {
		$dados_subarea = DBRead('', 'tb_subarea_problema', "WHERE id_subarea_problema = '" . $id_subarea_problema . "' ");

		$subarea_legenda = $dados_subarea[0]['descricao'];
		$filtro_subarea = " AND c.id_subarea_problema = '" . $id_subarea_problema . "'";
	} else {
		$subarea_legenda = 'Todas';
	}

	if ($id_area_problema) {
		$dados_area = DBRead('', 'tb_area_problema', "WHERE id_area_problema = '" . $id_area_problema . "' ");

		$area_legenda = $dados_area[0]['nome'];
		$filtro_area = " AND c.id_area_problema = '" . $id_area_problema . "'";
		if ($id_area_problema == 'nao_identificada') {
			$area_legenda = "Atendimento Incompleto";
			$subarea_legenda = 'Atendimento Incompleto';
		}
	} else {
		$area_legenda = 'Todas';
	}

	if ($situacao) {
		$dados_situacao = DBRead('', 'tb_situacao', "WHERE id_situacao = '" . $situacao . "'");
		$situacao_legenda =  ucwords(strtolower($dados_situacao[0]['nome']));
		$filtro_situacao = " AND j.id_situacao = '" . $situacao . "'";
	} else {
		$situacao_legenda = 'Todas';
	}

	$data_hoje = getDataHora();
	$data_hoje = converteDataHora($data_hoje);

	$periodo_amostra = "<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> de $data_de até $data_ate</span>";
	$gerado = "<span style=\"font-size: 14px;\"><strong>Gerado em: </strong> $data_hoje</span>";

	$data_de = converteData($data_de);
	$data_ate = converteData($data_ate);

	echo "<div class=\"col-md-12\" style=\"padding: 0\">";
	echo "<legend style=\"text-align:center;\"><strong>Relatório de Atendimentos - Subáreas e Áreas de Finalização</strong><br>$gerado</legend>";
	echo "<legend style=\"text-align:center;\">$periodo_amostra</legend>";
	echo "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong> Contrato - </strong>" . $empresa_legenda . ", <strong>Subárea de Finalização - </strong>" . $subarea_legenda . ", <strong>Área de Finalização - </strong>" . $area_legenda . ", <strong>Atendente - </strong>" . $operador_legenda . ", <strong>Situação - </strong>" . $situacao_legenda . "</legend>";

	$dados_consulta = DBRead('', 'tb_atendimento a', "INNER JOIN tb_subarea_problema_atendimento b ON a.id_atendimento = b.id_atendimento INNER JOIN tb_subarea_problema c ON b.id_subarea_problema = c.id_subarea_problema INNER JOIN tb_contrato_plano_pessoa d ON a.id_contrato_plano_pessoa = d.id_contrato_plano_pessoa INNER JOIN tb_pessoa e ON d.id_pessoa = e.id_pessoa INNER JOIN tb_usuario f ON a.id_usuario = f.id_usuario INNER JOIN tb_pessoa g ON f.id_pessoa = g.id_pessoa INNER JOIN tb_area_problema h ON c.id_area_problema = h.id_area_problema INNER JOIN tb_plano i ON d.id_plano = i.id_plano INNER JOIN tb_situacao_atendimento j ON a.id_atendimento = j.id_atendimento INNER JOIN tb_situacao k ON j.id_situacao = k.id_situacao WHERE a.data_inicio >= '" . $data_de . " 00:00:00' AND a.data_inicio < '" . $data_ate . " 23:59:59' " . $filtro_contrato_plano_pessoa . " " . $filtro_usuario . " " . $filtro_subarea . " " . $filtro_area . " " .$filtro_situacao. " ORDER BY e.nome ASC", "k.nome AS nome_situacao, a.data_inicio, c.descricao, e.nome AS nome_empresa, g.nome AS nome_atendente, d.id_plano, h.nome AS nome_area_problema, d.nome_contrato, i.nome AS nome_plano, i.cod_servico");

	registraLog('Relatório de Atendimentos - Subáreas e Áreas de Finalização.','rel','relatorio_atendimento',1,"INNER JOIN tb_subarea_problema_atendimento b ON a.id_atendimento = b.id_atendimento INNER JOIN tb_subarea_problema c ON b.id_subarea_problema = c.id_subarea_problema INNER JOIN tb_contrato_plano_pessoa d ON a.id_contrato_plano_pessoa = d.id_contrato_plano_pessoa INNER JOIN tb_pessoa e ON d.id_pessoa = e.id_pessoa INNER JOIN tb_usuario f ON a.id_usuario = f.id_usuario INNER JOIN tb_pessoa g ON f.id_pessoa = g.id_pessoa INNER JOIN tb_area_problema h ON c.id_area_problema = h.id_area_problema INNER JOIN tb_plano i ON d.id_plano = i.id_plano INNER JOIN tb_situacao_atendimento j ON a.id_atendimento = j.id_atendimento INNER JOIN tb_situacao k ON j.id_situacao = k.id_situacao WHERE a.data_inicio >= '" . $data_de . " 00:00:00' AND a.data_inicio < '" . $data_ate . " 23:59:59' " . $filtro_contrato_plano_pessoa . " " . $filtro_usuario . " " . $filtro_subarea . " " . $filtro_area . " " .$filtro_situacao. " ORDER BY e.nome ASC");


	//Consulta para atendimentos incompletos
	$dados_atendimentos_incompletos = DBRead('', 'tb_atendimento a', "INNER JOIN tb_contrato_plano_pessoa b ON a.id_contrato_plano_pessoa = b.id_contrato_plano_pessoa INNER JOIN tb_pessoa c ON b.id_pessoa = c.id_pessoa INNER JOIN tb_usuario d ON a.id_usuario = d.id_usuario INNER JOIN tb_pessoa e ON d.id_pessoa = e.id_pessoa INNER JOIN tb_plano f ON b.id_plano = f.id_plano INNER JOIN tb_situacao_atendimento j ON a.id_atendimento = j.id_atendimento INNER JOIN tb_situacao k ON j.id_situacao = k.id_situacao  WHERE a.data_inicio >= '" . $data_de . " 00:00:00' AND a.data_inicio < '" . $data_ate . " 23:59:59' AND a.falha = 1 " . $filtro_contrato_plano_pessoa . " " . $filtro_usuario . " " .$filtro_situacao. "  AND a.id_atendimento NOT IN (SELECT id_atendimento FROM tb_subarea_problema_atendimento) ORDER BY c.nome ASC", "k.nome as nome_situacao, a.data_inicio, c.nome AS nome_empresa, e.nome AS nome_atendente, b.id_plano, b.nome_contrato, f.nome AS nome_plano, f.cod_servico");

	registraLog('Relatório de Atendimentos - Subáreas e Áreas de Finalização.','rel','relatorio_atendimento',1,"INNER JOIN tb_contrato_plano_pessoa b ON a.id_contrato_plano_pessoa = b.id_contrato_plano_pessoa INNER JOIN tb_pessoa c ON b.id_pessoa = c.id_pessoa INNER JOIN tb_usuario d ON a.id_usuario = d.id_usuario INNER JOIN tb_pessoa e ON d.id_pessoa = e.id_pessoa INNER JOIN tb_plano f ON b.id_plano = f.id_plano INNER JOIN tb_situacao_atendimento j ON a.id_atendimento = j.id_atendimento INNER JOIN tb_situacao k ON j.id_situacao = k.id_situacao  WHERE a.data_inicio >= '" . $data_de . " 00:00:00' AND a.data_inicio < '" . $data_ate . " 23:59:59' AND a.falha = 1 " . $filtro_contrato_plano_pessoa . " " . $filtro_usuario . " " .$filtro_situacao. "  AND a.id_atendimento NOT IN (SELECT id_atendimento FROM tb_subarea_problema_atendimento) ORDER BY c.nome ASC");


	if ($dados_consulta || $dados_atendimentos_incompletos) {
		if ($dados_consulta && !$dados_atendimentos_incompletos) {
			$contador_total = count($dados_consulta);
		} else if (!$dados_consulta && $dados_atendimentos_incompletos) {
			$contador_total = count($dados_atendimentos_incompletos);
		} else {
			$contador_total = count($dados_consulta) + count($dados_atendimentos_incompletos);
		}
		$contador_dados = "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong>Total: </strong> " . $contador_total . "</span></legend>";
	}
	echo $contador_dados;

	if ($dados_consulta || $dados_atendimentos_incompletos) {
		if ($dados_consulta) {

			$qtd_empresas = array();
			$qtd_sub_area = array();
			$qtd_area = array();
			$qtd_atendentes = array();
			$qtd_plano = array();

			foreach ($dados_consulta as $conteudo_consulta) {

				if ($conteudo_consulta['nome_contrato']) {
					$nome_contrato = " (" . $conteudo_consulta['nome_contrato'] . ") ";
				} else {
					$nome_contrato = '';
				}

				$contrato = $conteudo_consulta['nome_empresa'] . " " . $nome_contrato;

				$qtd_empresas[$contrato] += 1;
				$qtd_sub_area[$conteudo_consulta['descricao']] += 1;
				$qtd_area[$conteudo_consulta['nome_area_problema']] += 1;
				$qtd_atendentes[$conteudo_consulta['nome_atendente']] += 1;
				$qtd_plano[getNomeServico($conteudo_consulta['cod_servico']) . " - " . $conteudo_consulta['nome_plano']] += 1;

				$dados_tabela[] = array(
					'contrato' => $contrato,
					'plano' => getNomeServico($conteudo_consulta['cod_servico']) . " - " . $conteudo_consulta['nome_plano'],
					'data' => converteDataHora($conteudo_consulta['data_inicio']),
					'subarea_de_finalizacao' => $conteudo_consulta['descricao'],
					'area_de_finalizacao' => $conteudo_consulta['nome_area_problema'],
					'atendente' => $conteudo_consulta['nome_atendente']
				);
			}
		}

		if ($dados_atendimentos_incompletos) {
			foreach ($dados_atendimentos_incompletos as $atendimentos_incompletos) {

				if ($atendimentos_incompletos['nome_contrato']) {
					$nome_contrato = " (" . $atendimentos_incompletos['nome_contrato'] . ") ";
				} else {
					$nome_contrato = '';
				}

				$contrato = $atendimentos_incompletos['nome_empresa'] . " " . $nome_contrato;

				$qtd_empresas[$contrato] += 1;
				$qtd_sub_area['Atendimento Incompleto'] += 1;
				$qtd_area['Atendimento Incompleto'] += 1;
				$qtd_atendentes[$atendimentos_incompletos['nome_atendente']] += 1;
				$qtd_plano[getNomeServico($atendimentos_incompletos['cod_servico']) . " - " . $atendimentos_incompletos['nome_plano']] += 1;

				$dados_tabela[] = array(
					'contrato' => $contrato,
					'plano' => getNomeServico($atendimentos_incompletos['cod_servico']) . " - " . $atendimentos_incompletos['nome_plano'],
					'data' => converteDataHora($atendimentos_incompletos['data_inicio']),
					'subarea_de_finalizacao' => 'Atendimento Incompleto',
					'area_de_finalizacao' => 'Atendimento Incompleto',
					'atendente' => $atendimentos_incompletos['nome_atendente']
				);
			}
		}

		if (!$id_contrato_plano_pessoa) {
			echo '
				<table class="table table-hover dataTable" style="margin-bottom:0;">
					      <thead>
					        <tr>
					            <th class="text-left col-md-8">Contrato</th>
					            <th class="text-left col-md-4">Total</th>
					        </tr>
					      </thead>
					      <tbody>';

			$aux_empresa = 0;

			arsort($qtd_empresas);
			foreach ($qtd_empresas as $empresa => $qtd) {
				echo '<tr>';
				echo '<td>' . $empresa . '</td>';
				echo '<td>' . $qtd . '</td>';
				echo '</tr>';
				$aux_empresa = $aux_empresa + (int) $qtd;
			}
			echo '</tbody>';
			echo '</table>
				<hr>';
		}

		echo '
				<table class="table table-hover dataTable" style="margin-bottom:0;">
					      <thead>
					        <tr>
					            <th class="text-left col-md-8">Plano</th>
					            <th class="text-left col-md-4">Total</th>
					        </tr>
					      </thead>
					      <tbody>';


		arsort($qtd_plano);
		foreach ($qtd_plano as $planos => $qtd) {
			echo '<tr>';
			echo '<td>' . $planos . '</td>';
			echo '<td>' . $qtd . '</td>';
			echo '</tr>';
		}
		echo '</tbody>
				</table>
				<hr>';


		echo '
				<table class="table table-hover dataTable" style="margin-bottom:0;">
					      <thead>
					        <tr>			       
					            <th class="text-left col-md-8">Subárea de Problema</th>
					            <th class="text-left col-md-4">Total</th>
					        </tr>
					      </thead>
					      <tbody>';

		arsort($qtd_sub_area);
		foreach ($qtd_sub_area as $sub_area => $qtd) {
			echo '<tr>';
			echo '<td>' . $sub_area . '</td>';
			echo '<td>' . $qtd . '</td>';
			echo '</tr>';
		}
		echo '</tbody>
				</table>
				<hr>';

		echo '
				<table class="table table-hover dataTable" style="margin-bottom:0;">
					      <thead>
					        <tr>			       
					            <th class="text-left col-md-8">Área de Problema</th>
					            <th class="text-left col-md-4">Total</th>
					        </tr>
					      </thead>
					      <tbody>';

		arsort($qtd_area);
		foreach ($qtd_area as $area => $qtd) {
			echo '<tr>';
			echo '<td>' . $area . '</td>';
			echo '<td>' . $qtd . '</td>';
			echo '</tr>';
		}
		echo '</tbody>
				</table>
				<hr>';

		echo '
				<table class="table table-hover dataTable" style="margin-bottom:0;">
					      <thead>
					        <tr>
					            <th class="text-left col-md-8">Atendente</th>
					            <th class="text-left col-md-4">Total</th>
					        </tr>
					      </thead>
					      <tbody>';

		arsort($qtd_atendentes);
		foreach ($qtd_atendentes as $atendente => $qtd) {
			echo '<tr>';
			echo '<td>' . $atendente . '</td>';
			echo '<td>' . $qtd . '</td>';
			echo '</tr>';
		}
		echo '</tbody>
				</table>
				<hr>';

		echo '<table class="table table-hover dataTable" style="margin-bottom:0;">
		      <thead>
		        <tr>
		            <th class="text-left  col-md-2">Contrato</th>
			        <th class="text-left  col-md-2">Plano</th>
		            <th class="text-left  col-md-2">Data</th>
		            <th class="text-left  col-md-2">Subárea de Finalização</th>
		            <th class="text-left  col-md-2">Área de Finalização</th>
		            <th class="text-left  col-md-2">Atendente</th>
		        </tr>
		      </thead>
		      <tbody>';

		foreach ($dados_tabela as $conteudo_tabela) {
			echo '<tr>
		            <td class="text-left">' . $conteudo_tabela['contrato'] . '</td>    
		            <td class="text-left">' . $conteudo_tabela['plano'] . '</td>
		            <td class="text-left">' . $conteudo_tabela['data'] . '</td>
		            <td class="text-left">' . $conteudo_tabela['subarea_de_finalizacao'] . '</td>
		            <td class="text-left">' . $conteudo_tabela['area_de_finalizacao'] . '</td>
		            <td class="text-left">' . $conteudo_tabela['atendente'] . '</td>
		        </tr>';
		}

		echo ' </tbody>
		   
			</table>

			<hr>';

		echo "</div>";
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

function relatorio_faturados_tabela($id_contrato_plano_pessoa, $data_de, $data_ate){

	if ($id_contrato_plano_pessoa) {
		$dados = DBRead('', 'tb_contrato_plano_pessoa a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_contrato_plano_pessoa = '" . $id_contrato_plano_pessoa . "'");
		$empresa_legenda = $dados[0]['nome'];

		if ($dados[0]['nome_contrato']) {
			$empresa_legenda = $empresa_legenda . " (" . $dados[0]['nome_contrato'] . ") ";
		}

		$filtro_contrato_plano_pessoa = "AND id_contrato_plano_pessoa ='" . $id_contrato_plano_pessoa . "' ";
	} else {
		$empresa_legenda = "Todos";
	}

	$data_hoje = getDataHora();
	$data_hoje = converteDataHora($data_hoje);

	$periodo_amostra = "<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> de $data_de até $data_ate</span>";
	$gerado = "<span style=\"font-size: 14px;\"><strong>Gerado em: </strong> $data_hoje</span>";

	$data_de = converteData($data_de);
	$data_ate = converteData($data_ate);

	$dados = DBRead('', 'tb_atendimento a', "INNER JOIN tb_situacao_atendimento b ON a.id_atendimento = b.id_atendimento INNER JOIN tb_situacao c ON b.id_situacao = c.id_situacao WHERE a.gravado = '1' AND a.falha != 2 AND a.desconsiderar = 0 AND a.data_inicio BETWEEN '" . $data_de . " 00:00:00' AND '" . $data_ate . " 23:59:59' " . $filtro_contrato_plano_pessoa . " ORDER BY a.data_inicio DESC", "a.*, c.nome");

	registraLog('Relatório de Atendimentos - Faturados (Tabela).','rel','relatorio_atendimento',1,"INNER JOIN tb_situacao_atendimento b ON a.id_atendimento = b.id_atendimento INNER JOIN tb_situacao c ON b.id_situacao = c.id_situacao WHERE a.gravado = '1' AND a.falha != 2 AND a.desconsiderar = 0 AND a.data_inicio BETWEEN '" . $data_de . " 00:00:00' AND '" . $data_ate . " 23:59:59' " . $filtro_contrato_plano_pessoa . " ORDER BY a.data_inicio DESC");

	if ($dados) {
		$contador_dados = "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong>Total: </strong> " . count($dados) . "</span></legend>";
	}

	echo "<div class=\"col-md-12\" style=\"padding: 0\">";
	echo "<legend style=\"text-align:center;\"><strong>Relatório de Atendimentos - Faturados</strong><br>$gerado</legend>";
	echo "<legend style=\"text-align:center;\">$periodo_amostra</legend>";
	echo "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong> Contrato - </strong>" . $empresa_legenda . "</legend>";

	$empresa_legenda = strtoupper($empresa_legenda);
	echo $contador_dados;

	if ($dados) {

		$situacao_protocolo = DBRead('', 'tb_parametros', "WHERE id_contrato_plano_pessoa = '" . $id_contrato_plano_pessoa . "'", "exibir_protocolo, solicitacao_cpf, solicitacao_dados");

		echo "
			<div style=\"overflow-x:auto;\">
				<table class=\"table table-hover dataTable\"> 
					<thead> 
						<tr> 
							<th>Data do Atendimento</th>
	    					<th>Assinante</th>
	    					<th>Contato</th>
	    					<th>Telefone 1</th>
	    					<th>Telefone 2</th>";
		if ($situacao_protocolo[0]['solicitacao_cpf'] == 1) {
			echo "<th>CPF/CNPJ</th>";
		}
		if ($situacao_protocolo[0]['solicitacao_dados'] == 1) {
			$situacao_dados = DBRead('', 'tb_atendimento', "WHERE gravado = '1' AND falha != 2 AND data_inicio BETWEEN '" . $data_de . " 00:00:00' AND '" . $data_ate . " 23:59:59' " . $filtro_contrato_plano_pessoa . " AND descricao_dado_adicional != '' ");

			echo "<th>" . $situacao_dados[0]['descricao_dado_adicional'] . "</th>";
		}
		if ($situacao_protocolo[0]['exibir_protocolo'] == 1) {
			echo "<th>Protocolo</th>";
		}
		echo "
	    					<th>Atendente</th>
	    					<th>Tipo do atendimento</th>
	    					<th>OS</th>
	    					";

		echo "
						</tr>
					</thead> 
					<tbody>";

		foreach ($dados as $dado) {

			$conteudo_pessoa = DBRead('', 'tb_pessoa a', "INNER JOIN tb_usuario b ON a.id_pessoa = b.id_pessoa WHERE b.id_usuario = '" . $dado['id_usuario'] . "' ");
			$nome_pessoa = $conteudo_pessoa[0]['nome'];

			if ($dado['via_texto'] == 1) {
				$tipo_atendimento = 'Via texto';
			} else {
				$tipo_atendimento = 'Via telefone';
			}

			echo "<tr>	
						<td>" . converteDataHora($dado['data_inicio']) . "</td>
				    	<td>" . $dado['assinante'] . "</td>
				    	<td>" . $dado['contato'] . "</td>
				    	<td><span class='phone'>" . $dado['fone1'] . "</span></td>";
			if ($dado['fone2']) {
				echo "<td><span class='phone'>" . $dado['fone2'] . "</span></td>";
			} else {
				echo '<td></td>';
			}
			if ($situacao_protocolo[0]['solicitacao_cpf'] == 1) {
				if ($dado['cpf_cnpj']) {
					echo "<td>" . $dado['cpf_cnpj'] . "</td>";
				} else {
					echo "<td></td>";
				}
			}

			if ($situacao_protocolo[0]['solicitacao_dados'] == 1) {
				if ($dado['dado_adicional']) {
					echo "<td>" . $dado['dado_adicional'] . "</td>";
				} else {
					echo "<td></td>";
				}
			}

			if ($situacao_protocolo[0]['exibir_protocolo'] == 1) {
				echo "<td>" . $dado['protocolo'] . "</td>";
			}
			echo "
				    	<td>" . $nome_pessoa . "</td>
				    	<td>" . $tipo_atendimento . "</td>
				    	<td>" . nl2br($dado['os']) . "<br><br>" . $dado['nome'] . "</td>";
		}
		echo '</tr>';
		echo "
					</tbody> 
				</table>
			</div>
			";

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
								filename: 'relatorio_atendimentos_faturados (" . $empresa_legenda . ")',
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

function relatorio_duplicados($id_contrato_plano_pessoa, $data_de, $data_ate, $minutos_duplicados){

	if ($id_contrato_plano_pessoa) {
		$dados = DBRead('', 'tb_contrato_plano_pessoa a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_contrato_plano_pessoa = '" . $id_contrato_plano_pessoa . "'");
		$empresa_legenda = $dados[0]['nome'];

		if ($dados[0]['nome_contrato']) {
			$empresa_legenda = $empresa_legenda . " (" . $dados[0]['nome_contrato'] . ") ";
		}

		$filtro_contrato_plano_pessoa = "AND id_contrato_plano_pessoa ='" . $id_contrato_plano_pessoa . "' ";
	} else {
		$empresa_legenda = "Todos";
	}

	$data_hoje = getDataHora();
	$data_hoje = converteDataHora($data_hoje);

	$periodo_amostra = "<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> de $data_de até $data_ate</span>";
	$gerado = "<span style=\"font-size: 14px;\"><strong>Gerado em: </strong> $data_hoje</span>";

	$data_de = converteData($data_de);
	$data_ate = converteData($data_ate);

	$dados = DBRead('', 'tb_atendimento a', "INNER JOIN tb_situacao_atendimento b ON a.id_atendimento = b.id_atendimento INNER JOIN tb_situacao c ON b.id_situacao = c.id_situacao WHERE a.gravado = '1' AND a.falha != 2 AND a.data_inicio BETWEEN '" . $data_de . " 00:00:00' AND '" . $data_ate . " 23:59:59' " . $filtro_contrato_plano_pessoa, "a.*, c.nome");

	registraLog('Relatório de Atendimentos - Duplicados.','rel','relatorio_atendimento',1,"INNER JOIN tb_situacao_atendimento b ON a.id_atendimento = b.id_atendimento INNER JOIN tb_situacao c ON b.id_situacao = c.id_situacao WHERE a.gravado = '1' AND a.falha != 2 AND a.data_inicio BETWEEN '" . $data_de . " 00:00:00' AND '" . $data_ate . " 23:59:59' ".$filtro_contrato_plano_pessoa."");


	$contador_duplicados = 0;
	echo "<div class=\"col-md-8 col-md-offset-2\" style=\"padding: 0\">";
	echo "<legend style=\"text-align:center;\"><strong>Relatório de Atendimentos - Duplicados</strong><br>$gerado</legend>";
	echo "<legend style=\"text-align:center;\">$periodo_amostra</legend>";
	echo "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong> Contrato - </strong>" . $empresa_legenda . "</legend>";

	if ($dados) {
		
		foreach ($dados as $dado) {

			$data_inicio = date('Y-m-d H:i:s', strtotime("-" . $minutos_duplicados . " minutes", strtotime($dado['data_inicio'])));

			if (valida_cpf($dado['cpf_cnpj']) || valida_cnpj($dado['cpf_cnpj'])) {

				$dados_duplicado = DBRead('', 'tb_atendimento a', "INNER JOIN tb_situacao_atendimento b ON a.id_atendimento = b.id_atendimento INNER JOIN tb_situacao c ON b.id_situacao = c.id_situacao WHERE a.gravado = '1' AND a.falha != 2 AND a.data_inicio <= '" . $dado['data_inicio'] . "' AND a.data_inicio >= '" . $data_inicio . "' AND a.id_contrato_plano_pessoa = '" . $dado['id_contrato_plano_pessoa'] . "' AND a.cpf_cnpj = '" . $dado['cpf_cnpj'] . "' AND a.id_atendimento != '" . $dado['id_atendimento'] . "'");

				if ($dados_duplicado) {
					$conteudo_empresa = DBRead('', 'tb_contrato_plano_pessoa a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa INNER JOIN tb_plano c ON a.id_plano = c.id_plano WHERE a.id_contrato_plano_pessoa = '" . $dado['id_contrato_plano_pessoa'] . "'", "b.nome AS nome_empresa, a.*, c.*");

					if ($conteudo_empresa[0]['nome_contrato']) {
						$nome_contrato = " (" . $conteudo_empresa[0]['nome_contrato'] . ") ";
					} else {
						$nome_contrato = '';
					}

					$contrato = $conteudo_empresa[0]['nome_empresa'] . " " . $nome_contrato;

					$nome_empresa = "<span><strong>" . $contrato . "</strong></span>";

					$situacao_protocolo = DBRead('', 'tb_parametros', "WHERE id_contrato_plano_pessoa = '" . $dado['id_contrato_plano_pessoa'] . "'", "exibir_protocolo, solicitacao_cpf");

					$conteudo_pessoa = DBRead('', 'tb_pessoa a', "INNER JOIN tb_usuario b ON a.id_pessoa = b.id_pessoa WHERE b.id_usuario = '" . $dado['id_usuario'] . "' ");
					$nome_pessoa = $conteudo_pessoa[0]['nome'];

					$conteudo_pessoa_duplicado = DBRead('', 'tb_pessoa a', "INNER JOIN tb_usuario b ON a.id_pessoa = b.id_pessoa WHERE b.id_usuario = '" . $dados_duplicado[0]['id_usuario'] . "' ");
					$nome_pessoa_duplicado = $conteudo_pessoa_duplicado[0]['nome'];

					echo "
                    <div class ='row'>
                        <div class='col-md-5'>
							<ul class='list-group container-entrevista'>";
					echo "<li class='list-group-item'><div class='row'><div class='col-md-12 text-center'><strong style='font-size:16px'>" . $nome_empresa . "<br><span></strong>" . converteDataHora($dado['data_inicio']) . "</span></div></div></li>";

					echo "<li class='list-group-item'><strong>Assinante: </strong> " . $dado['assinante'] . "</li>";
					echo "<li class='list-group-item'><strong>Contato: </strong> " . $dado['contato'] . "</li>";
					if ($dado['fone2']) {
						echo "<li class='list-group-item'><strong>Telefone 1: </strong> <span class='phone'>" . $dado['fone1'] . "</span></li>";
						echo "<li class='list-group-item'><strong>Telefone 2: </strong> <span class='phone'>" . $dado['fone2'] . "</span></li>";
					} else {
						echo "<li class='list-group-item'><strong>Telefone: </strong> <span class='phone'>" . $dado['fone1'] . "</span></li>";
					}

					if ($situacao_protocolo[0]['solicitacao_cpf'] == 1) {
						if ($dado['cpf_cnpj']) {
							echo "<li class='list-group-item'><strong>CPF/CNPJ: </strong> <span>" . $dado['cpf_cnpj'] . "</span></li>";
						}
					}
					if ($dado['descricao_dado_adicional'] && $dado['dado_adicional']) {
						echo "<li class='list-group-item'><strong>" . $dado['descricao_dado_adicional'] . ": </strong> <span>" . $dado['dado_adicional'] . "</span></li>";
					}
					if ($situacao_protocolo[0]['exibir_protocolo'] == 1) {
						echo "<li class='list-group-item'><strong>Protocolo: </strong> <span>" . $dado['protocolo'] . "</span></li>";
					}

					echo "<li class='list-group-item'><strong>Atendente: </strong> " . $nome_pessoa . "</li>";
					echo "<li class='list-group-item'><strong>OS: </strong><br> " . nl2br($dado['os']) . "<br><br>" . $dado['nome'] . "</li>";

					echo "</ul>
                    </div>";
					echo "
                    <div class='col-md-2 text-center'>
                        <i class='fa fa-arrow-right' style='font-size:40px;'></i>
                    </div>  
                    ";
					echo "
                        <div class='col-md-5'>
							<ul class='list-group container-entrevista'>";
					echo "<li class='list-group-item'><div class='row'><div class='col-md-12 text-center'><strong style='font-size:16px'>" . $nome_empresa . "<br><span></strong>" . converteDataHora($dados_duplicado[0]['data_inicio']) . "</span></div></div></li>";

					echo "<li class='list-group-item'><strong>Assinante: </strong> " . $dados_duplicado[0]['assinante'] . "</li>";
					echo "<li class='list-group-item'><strong>Contato: </strong> " . $dados_duplicado[0]['contato'] . "</li>";
					if ($dados_duplicado[0]['fone2']) {
						echo "<li class='list-group-item'><strong>Telefone 1: </strong> <span class='phone'>" . $dados_duplicado[0]['fone1'] . "</span></li>";
						echo "<li class='list-group-item'><strong>Telefone 2: </strong> <span class='phone'>" . $dados_duplicado[0]['fone2'] . "</span></li>";
					} else {
						echo "<li class='list-group-item'><strong>Telefone: </strong> <span class='phone'>" . $dados_duplicado[0]['fone1'] . "</span></li>";
					}

					if ($situacao_protocolo[0]['solicitacao_cpf'] == 1) {
						if ($dado['cpf_cnpj']) {
							echo "<li class='list-group-item'><strong>CPF/CNPJ: </strong> <span>" . $dado['cpf_cnpj'] . "</span></li>";
						}
					}
					if ($dados_duplicado[0]['descricao_dado_adicional'] && $dados_duplicado[0]['dado_adicional']) {
						echo "<li class='list-group-item'><strong>" . $dados_duplicado[0]['descricao_dado_adicional'] . ": </strong> <span>" . $dados_duplicado[0]['dado_adicional'] . "</span></li>";
					}
					if ($situacao_protocolo[0]['exibir_protocolo'] == 1) {
						echo "<li class='list-group-item'><strong>Protocolo: </strong> <span>" . $dados_duplicado[0]['protocolo'] . "</span></li>";
					}

					echo "<li class='list-group-item'><strong>Atendente: </strong> " . $nome_pessoa_duplicado . "</li>";
					echo "<li class='list-group-item'><strong>OS: </strong><br> " . nl2br($dados_duplicado[0]['os']) . "<br><br>" . $dados_duplicado[0]['nome'] . "</li>";

					echo "</ul>
                        </div>
                    </div><hr>
                    ";
					$contador_duplicados++;
				}
			}
		}
		echo "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong>Total: </strong> " . $contador_duplicados . "</span></legend>";
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

function relatorio_faturados_auditoria($id_contrato_plano_pessoa, $data_de, $data_ate, $operador, $request){

	if ($id_contrato_plano_pessoa) {
		$dados = DBRead('', 'tb_contrato_plano_pessoa a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_contrato_plano_pessoa = '" . $id_contrato_plano_pessoa . "'");
		$empresa_legenda = $dados[0]['nome'];

		if ($dados[0]['nome_contrato']) {
			$empresa_legenda = $empresa_legenda . " (" . $dados[0]['nome_contrato'] . ") ";
		}

		$filtro_contrato_plano_pessoa = "AND id_contrato_plano_pessoa ='" . $id_contrato_plano_pessoa . "' ";
	} else {
		$empresa_legenda = "Todos";
	}

	if ($operador) {
		$dados_operadores = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_usuario = '" . $operador . "'");

		$operador_legenda = $dados_operadores[0]['nome'];
		$filtro_usuario = "AND id_usuario = '" . $operador . "'";
	} else {
		$operador_legenda = 'Todos';
	}

	$data_hoje = getDataHora();
	$data_hoje = converteDataHora($data_hoje);

	$periodo_amostra = "<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> de $data_de até $data_ate</span>";
	$gerado = "<span style=\"font-size: 14px;\"><strong>Gerado em: </strong> $data_hoje</span>";

	$data_de = converteData($data_de);
	$data_ate = converteData($data_ate);

	$dados = DBRead('', 'tb_atendimento a', "INNER JOIN tb_situacao_atendimento b ON a.id_atendimento = b.id_atendimento INNER JOIN tb_situacao c ON b.id_situacao = c.id_situacao LEFT JOIN tb_atendimento_valores_integracao f ON a.id_atendimento = f.id_atendimento WHERE a.gravado = '1' AND a.falha != 2 AND a.data_inicio BETWEEN '" . $data_de . " 00:00:00' AND '" . $data_ate . " 23:59:59' " . $filtro_contrato_plano_pessoa . " " . $filtro_usuario . " ORDER BY a.data_inicio ASC", "a.*, a.id_atendimento as atendimento_id, c.nome, f.*");

	registraLog('Relatório de Atendimentos - Auditoria.','rel','relatorio_atendimento',1,"INNER JOIN tb_situacao_atendimento b ON a.id_atendimento = b.id_atendimento INNER JOIN tb_situacao c ON b.id_situacao = c.id_situacao LEFT JOIN tb_atendimento_valores_integracao f ON a.id_atendimento = f.id_atendimento WHERE a.gravado = '1' AND a.falha != 2 AND a.data_inicio BETWEEN '" . $data_de . " 00:00:00' AND '" . $data_ate . " 23:59:59' " . $filtro_contrato_plano_pessoa . " " . $filtro_usuario . " ORDER BY a.data_inicio ASC");


	if ($dados) {
		$contador_dados = "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong>Total: </strong> " . count($dados) . "</span></legend>";
	}

	echo "<div class=\"col-md-10 col-md-offset-1\" style=\"padding: 0\">";
	echo "<legend style=\"text-align:center;\"><strong>Relatório de Atendimentos - Auditoria</strong><br>$gerado</legend>";
	echo "<legend style=\"text-align:center;\">$periodo_amostra</legend>";
	echo "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong> Contrato - </strong>" . $empresa_legenda . ", <strong>Atendente - </strong>" . $operador_legenda . "</legend>";

	echo $contador_dados;

	if ($dados) {
		$perfil_sistema = DBRead('', 'tb_usuario', "WHERE id_usuario = '".$_SESSION['id_usuario']."' ", "id_perfil_sistema");
		$perfil_sistema = $perfil_sistema[0]['id_perfil_sistema'];
		foreach ($dados as $dado) {
			
			if ($dado['resolvido'] == '1') {
				$resolucao = 'Resolvido';
				$cor_resolucao = 'text-success';
			} else if ($dado['resolvido'] == '2') {
				$resolucao = 'Não Resolvido';
				$cor_resolucao = 'text-danger';
			} else if ($dado['resolvido'] == '3') {
				$resolucao = 'Diagnosticado';
				$cor_resolucao = 'text-primary';
			}
			
			$conteudo_empresa = DBRead('', 'tb_contrato_plano_pessoa a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa INNER JOIN tb_plano c ON a.id_plano = c.id_plano WHERE a.id_contrato_plano_pessoa = '" . $dado['id_contrato_plano_pessoa'] . "'", "b.nome AS nome_empresa, a.*, c.*");

			if ($conteudo_empresa[0]['nome_contrato']) {
				$nome_contrato = " (" . $conteudo_empresa[0]['nome_contrato'] . ") ";
			} else {
				$nome_contrato = '';
			}

			$contrato = $conteudo_empresa[0]['nome_empresa'] . " " . $nome_contrato;

			$nome_empresa = "<span><strong>" . $contrato . "</strong></span>";

			$situacao_protocolo = DBRead('', 'tb_parametros', "WHERE id_contrato_plano_pessoa = '" . $dado['id_contrato_plano_pessoa'] . "'", "exibir_protocolo, solicitacao_cpf");

			if ($dado['flag_pendencia'] == 1) {
				$data_inicio = converteDataHora($dado['data']);
				$conteudo_pessoa = DBRead('', 'tb_pessoa a', "INNER JOIN tb_usuario b ON a.id_pessoa = b.id_pessoa WHERE b.id_usuario = '" . $dado['id_usuario_pendencia'] . "' ");
				$nome_pessoa = $conteudo_pessoa[0]['nome'].' <strong>(Atendimento pendente)</strong> <i class="fas fa-exclamation-triangle" style="color: #F7D358"></i>';

			} else {
				$data_inicio = converteDataHora($dado['data_inicio']);
				$conteudo_pessoa = DBRead('', 'tb_pessoa a', "INNER JOIN tb_usuario b ON a.id_pessoa = b.id_pessoa WHERE b.id_usuario = '" . $dado['id_usuario'] . "' ");
				$nome_pessoa = $conteudo_pessoa[0]['nome'];
			}

			echo "<div class = 'row'>";
			echo "<div class='col-md-6 esquerda'>";
			echo "<ul class='list-group container-entrevista ul-esquerda'>";
			echo "
				<li class='list-group-item'>
					<div class='row'>
						<div class='col-md-4'>
							<div class='panel-title text-left pull-left'>
								<div class='col-md-12'>
									<span class='text-right " . $cor_resolucao . "' style = 'font-size:12px;'>". $resolucao . "</span><br>
								</div>
							</div>
						</div>
						<div class='col-md-4' style='text-align: center'>
							<strong style='font-size:16px'>".$nome_empresa . "</strong><br>
							<span> ". $data_inicio . "</span> <br><br>
						</div>
						<div class='col-md-4'>
							<div class='panel-title text-right pull-right;'>
								<div class='col-md-12 desconsiderar_".$dado['atendimento_id']."'>";
								if($perfil_sistema == '14' || $perfil_sistema == '2'){
									if($dado['flag_pendencia'] != 1){
										if($dado['desconsiderar'] == 1){
											echo "
											<button class='btn btn-xs btn-success' id='faturar' value='".$dado['atendimento_id']."'>
											<i class='far fa-check-circle'></i>
												Considerar
											</button>";
										}else{
											echo "
											<button class='btn btn-xs btn-danger' id='nao_faturar' value='".$dado['atendimento_id']."'>
											<i class='far fa-times-circle'></i> 
												Desconsiderar
											</button>";
										}
									}
								}
								echo "
								</div>
							</div>
						</div>
					</div>
				</li>";
			
			if ($perfil_sistema == 2) {
				echo "<li class='list-group-item'><strong>Id atendimento: </strong> " . $dado['atendimento_id'] . "</li>";
			}
			
			echo "<li class='list-group-item'><strong>Assinante: </strong> " . $dado['assinante'] . "</li>";
			echo "<li class='list-group-item'><strong>Contato: </strong> " . $dado['contato'] . "</li>";
			if ($dado['fone2']) {
				echo "<li class='list-group-item'><strong>Telefone 1: </strong> <span class='phone'>" . $dado['fone1'] . "</span></li>";
				echo "<li class='list-group-item'><strong>Telefone 2: </strong> <span class='phone'>" . $dado['fone2'] . "</span></li>";
			} else {
				echo "<li class='list-group-item'><strong>Telefone: </strong> <span class='phone'>" . $dado['fone1'] . "</span></li>";
			}

			if ($situacao_protocolo[0]['solicitacao_cpf'] == 1) {
				if ($dado['cpf_cnpj']) {
					echo "<li class='list-group-item'><strong>CPF/CNPJ: </strong> <span>" . $dado['cpf_cnpj'] . "</span></li>";
				}
			}
			if ($dado['descricao_dado_adicional'] && $dado['dado_adicional']) {
				echo "<li class='list-group-item'><strong>" . $dado['descricao_dado_adicional'] . ": </strong> <span>" . $dado['dado_adicional'] . "</span></li>";
			}
			if ($situacao_protocolo[0]['exibir_protocolo'] == 1) {
				echo "<li class='list-group-item'><strong>Protocolo: </strong> <span>" . $dado['protocolo'] . "</span></li>";
			}

			echo "<li class='list-group-item'><strong>Atendente: </strong> " . $nome_pessoa . "</li>";
			echo "<li class='list-group-item'><strong>OS: </strong><br> " . nl2br($dado['os']) . "<br><br>" . $dado['nome'] . "</li>";

			echo "</ul>";
			echo "</div>";
			echo "<div class='col-md-6 direita'>";

			echo "<ul class='list-group container-entrevista ul-direita'>";
			$dados_atendimento_arvore = DBRead('', 'tb_atendimento_arvore', "WHERE id_atendimento = '" . $dado['atendimento_id'] . "'");

			$texto_os_textarea = '';
			if ($dados_atendimento_arvore) {
				foreach ($dados_atendimento_arvore as $conteudo) {
					if ($conteudo['exibe_texto_os']) {
						if ($conteudo['anotacao']) {
							$texto_os_textarea .= "(" . $conteudo['id_arvore'] . ") - " . $conteudo['texto_os'] . " (" . $conteudo['anotacao'] . ")" . "\n";
						} else {
							$texto_os_textarea .= "(" . $conteudo['id_arvore'] . ") - " . $conteudo['texto_os'] . "\n";
						}
					} else {
                        if ($conteudo['anotacao']) {
                            $texto_os_textarea .= "(" . $conteudo['id_arvore'] . ") - (" . $conteudo['anotacao'] . ")" . "\n";
                        }else{
                            $texto_os_textarea .= "(" . $conteudo['id_arvore'] . ")\n";
                        }
                    }
				}
			}
			if ($dado['classificacao'] || $dado['assunto'] || $dado['prioridade'] || $dado['setor'] || $dado['filial'] || $dado['tecnico'] || $dado['origem'] || $dado['login'] || $dado['processo'] || $dado['evento']) {

				if ($dado['classificacao'] == 'atendimento') {
					$legenda_setor = 'Departamento';
				}else{
					$legenda_setor = 'Setor';
				}
			

				echo "<li class='list-group-item'>";

					echo "<strong>OPÇÕES MARCADAS NA INTEGRAÇÃO: </strong> <br><br>" ;
					if ($dado['classificacao']) {	
							echo "<b> Classificação: </b> <span>" . $dado['classificacao'] . "</span> <br>" ;
					}
					if ($dado['assunto']) {
							echo "<b> Assunto: </b> <span>" . $dado['assunto'] . "</span> <br>";
					}
					if ($dado['prioridade']) {					
							echo "<b> Prioridade: </b> <span>" . $dado['prioridade'] . "</span> <br>";
					}
					if ($dado['setor']) {					
							echo "<b> $legenda_setor: </b> <span>" . $dado['setor'] . "</span> <br>";
					}
					if ($dado['filial']) {					
							echo "<b> Filial: </b> <span>" . $dado['filial'] . "</span> <br>";
					}
					if ($dado['tecnico']) {					
							echo "<b> Tecnico Responsável: </b> <span>" . $dado['tecnico'] . "</span> <br>";
					}
					if ($dado['origem']) {					
							echo "<b> Origem: </b> <span>" . $dado['origem'] . "</span> <br>";
					}
					if ($dado['contrato']) {					
							echo "<b> Contrato: </b> <span>" . $dado['contrato'] . "</span> <br>";
					}
					if ($dado['login']) {		
							echo "<b> Login: </b> <span>" . $dado['login'] . "</span> <br>";
					}
					if ($dado['processo']) {
							
							echo "<b> Processo: </b> <span>" . $dado['processo'] . "</span> <br>";
					}
					if ($dado['evento']) {
					
					echo "<b> Evento: </b> <span>" . $dado['evento'] . "</span> <br>";
					}
					if ($dado['id_sistema_de_gestao']) {
					echo "<b> Nº identificador no sistema de gestão: </b> <span>" . $dado['id_sistema_de_gestao'] . "</span>";
					}
			echo "</li>";
			}
			
			echo "<li class='list-group-item'><strong>OS Gerada pelo Fluxo: </strong><br> " . nl2br(substr($texto_os_textarea, 0, -1)) . "<br><br>" . $dado['nome'] . "</li>";

			echo "</ul>";

			echo "</div>";

			echo "</div><br><br>";
		}

	?>
		<script>
			window.addEventListener('DOMContentLoaded', function() {
				$('.esquerda').each(function(i) {
					var tamanho_heigth = $(this).height();
					var tamanho_width = $(this).find('.ul-esquerda').width();

					$(this).parent().find('.direita').height(tamanho_heigth);

					$(this).parent().find('.ul-direita').css('bottom', '0').css('position', 'absolute').width(tamanho_width);
				});

			});

			$(document).on('click', '#faturar', function(){
				var id_atendimento = $(this).val();
				if (!confirm('Você tem certeza que quer considerar o atendimento?')){
					return false; 
				}
				$.ajax({
					url: "/api/ajax?class=DesconsiderarAtendimento.php",
					dataType: "json",
					data: {
						acao: 'faturar',
						parametros: {
							'id_atendimento': id_atendimento,
						},
						token: '<?= $request->token ?>'
					},
					success: function(data) {
						if(data[0].desconsiderar == 0){
							$('.desconsiderar_'+id_atendimento).html("<button class='btn btn-xs btn-danger' id='nao_faturar' value='"+id_atendimento+"'><i class='far fa-times-circle'></i> Desconsiderar</button>");
						}else{
							alert('Ocorreu algum erro, não foi possível atualizar o status do atendimento. Atualize a página e tente novamente!');
						}
						
					}
				});
			});

			$(document).on('click', '#nao_faturar', function(){
				var id_atendimento = $(this).val();
				var id_usuario = '<?= $_SESSION['id_usuario'] ?>';
				if (!confirm('Você tem certeza que quer desconsiderar o atendimento?')){
					return false; 
				}
				$.ajax({
					url: "/api/ajax?class=DesconsiderarAtendimento.php",
					dataType: "json",
					data: {
						acao: 'nao_faturar',
						parametros: {
							'id_atendimento': id_atendimento,
							'id_usuario': id_usuario,
						},
						token: '<?= $request->token ?>'
					},
					success: function(data) {
						if(data[0].desconsiderar == 1){
							$('.desconsiderar_'+id_atendimento).html("<button class='btn btn-xs btn-success' id='faturar' value='"+id_atendimento+"'><i class='far fa-check-circle'></i> Considerar</button>");
						}else{
							alert('Ocorreu algum erro, não foi possível atualizar o status do atendimento. Atualize a página e tente novamente!');
						}
						
					}
				});
			});

		</script>

	<?php

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

function relatorio_irritados($id_contrato_plano_pessoa, $data_de, $data_ate, $operador){

	if ($id_contrato_plano_pessoa) {
		$dados = DBRead('', 'tb_contrato_plano_pessoa a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_contrato_plano_pessoa = '" . $id_contrato_plano_pessoa . "'");
		$empresa_legenda = $dados[0]['nome'];

		if ($dados[0]['nome_contrato']) {
			$empresa_legenda = $empresa_legenda . " (" . $dados[0]['nome_contrato'] . ") ";
		}

		$filtro_contrato_plano_pessoa = "AND id_contrato_plano_pessoa ='" . $id_contrato_plano_pessoa . "' ";
	} else {
		$empresa_legenda = "Todos";
	}

	if ($operador) {
		$dados_operadores = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_usuario = '" . $operador . "'");

		$operador_legenda = $dados_operadores[0]['nome'];
		$filtro_usuario = "AND id_usuario = '" . $operador . "'";
	} else {
		$operador_legenda = 'Todos';
	}

	$data_hoje = getDataHora();
	$data_hoje = converteDataHora($data_hoje);

	$periodo_amostra = "<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> de $data_de até $data_ate</span>";
	$gerado = "<span style=\"font-size: 14px;\"><strong>Gerado em: </strong> $data_hoje</span>";

	$data_de = converteData($data_de);
	$data_ate = converteData($data_ate);

	$dados = DBRead('', 'tb_atendimento', "WHERE gravado = '1' AND falha != 2 AND irritado = '1' AND data_inicio BETWEEN '" . $data_de . " 00:00:00' AND '" . $data_ate . " 23:59:59' " . $filtro_contrato_plano_pessoa . " " . $filtro_usuario . "");

	registraLog('Relatório de Atendimentos - Clientes Irritados.','rel','relatorio_atendimento',1,"WHERE gravado = '1' AND falha != 2 AND irritado = '1' AND data_inicio BETWEEN '" . $data_de . " 00:00:00' AND '" . $data_ate . " 23:59:59' " . $filtro_contrato_plano_pessoa . " " . $filtro_usuario . "");

	if ($dados) {
		$contador_dados = "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong>Total: </strong> " . count($dados) . "</span></legend>";
	}

	echo "<div class=\"col-md-8 col-md-offset-2\" style=\"padding: 0\">";
	echo "<legend style=\"text-align:center;\"><strong>Relatório de Atendimentos - Clientes Irritados</strong><br>$gerado</legend>";
	echo "<legend style=\"text-align:center;\">$periodo_amostra</legend>";
	echo "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong> Contrato - </strong>" . $empresa_legenda . ", <strong>Atendente - </strong>" . $operador_legenda . "</legend>";

	echo $contador_dados;

	if ($dados) {
		foreach ($dados as $dado) {

			$situacao_protocolo = DBRead('', 'tb_parametros', "WHERE id_contrato_plano_pessoa = '" . $dado['id_contrato_plano_pessoa'] . "'", "exibir_protocolo, solicitacao_cpf");

			$conteudo_empresa = DBRead('', 'tb_contrato_plano_pessoa a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_contrato_plano_pessoa = '" . $dado['id_contrato_plano_pessoa'] . "'");

			$conteudo_empresa = DBRead('', 'tb_contrato_plano_pessoa a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa INNER JOIN tb_plano c ON a.id_plano = c.id_plano WHERE a.id_contrato_plano_pessoa = '" . $dado['id_contrato_plano_pessoa'] . "'", "b.nome AS nome_empresa, a.*, c.*");

			if ($conteudo_empresa[0]['nome_contrato']) {
				$nome_contrato = " (" . $conteudo_empresa[0]['nome_contrato'] . ") ";
			} else {
				$nome_contrato = '';
			}

			$contrato = $conteudo_empresa[0]['nome_empresa'] . " " . $nome_contrato;

			$nome_empresa = "<span><strong>" . $contrato . "</strong></span>";

			$conteudo_pessoa = DBRead('', 'tb_pessoa a', "INNER JOIN tb_usuario b ON a.id_pessoa = b.id_pessoa WHERE b.id_usuario = '" . $dado['id_usuario'] . "' ");

			$nome_pessoa = $conteudo_pessoa[0]['nome'];

			echo "<ul class='list-group container-entrevista'>";
			echo "<li class='list-group-item'><div class='row'><div class='col-md-12 text-center'><strong style='font-size:16px'>" . $nome_empresa . "<br><span></strong>" . converteDataHora($dado['data_inicio']) . "</span></div></div></li>";

			echo "<li class='list-group-item'><strong>Assinante: </strong> " . $dado['assinante'] . "</li>";
			echo "<li class='list-group-item'><strong>Contato: </strong> " . $dado['contato'] . "</li>";
			if ($dado['fone2']) {
				echo "<li class='list-group-item'><strong>Telefone 1: </strong> <span class='phone'>" . $dado['fone1'] . "</span></li>";
				echo "<li class='list-group-item'><strong>Telefone 2: </strong> <span class='phone'>" . $dado['fone2'] . "</span></li>";
			} else {
				echo "<li class='list-group-item'><strong>Telefone 1: </strong> <span class='phone'>" . $dado['fone1'] . "</span></li>";
			}
			if ($situacao_protocolo[0]['solicitacao_cpf'] == 1) {
				if ($dado['cpf_cnpj']) {
					echo "<li class='list-group-item'><strong>CPF/CNPJ: </strong> <span>" . $dado['cpf_cnpj'] . "</span></li>";
				}
			}

			if ($dado['descricao_dado_adicional'] && $dado['dado_adicional']) {
				echo "<li class='list-group-item'><strong>" . $dado['descricao_dado_adicional'] . ": </strong> <span>" . $dado['dado_adicional'] . "</span></li>";
			}
			if ($situacao_protocolo[0]['exibir_protocolo'] == 1) {
				echo "<li class='list-group-item'><strong>Protocolo: </strong> <span>" . $dado['protocolo'] . "</span></li>";
			}

			echo "<li class='list-group-item'><strong>Atendente: </strong> " . $nome_pessoa . "</li>";
			echo "<li class='list-group-item'><strong>OS: </strong><br> " . nl2br($dado['os']) . "</li>";
			if ($dado['resolvido'] == 1) {
				echo "<li class='list-group-item'><strong>Situação: </strong>Resolvido</li>";
			} else if ($dado['resolvido'] == 2) {
				echo "<li class='list-group-item'><strong>Situação: </strong>Não resolvido</li>";
			} else if ($dado['resolvido'] == 3) {
				echo "<li class='list-group-item'><strong>Situação: </strong>Diagnosticado</li>";
			}

			echo "</ul>";
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

function relatorio_chat_operador($data_de, $data_ate, $operador, $turno, $lider){

	$data_hora = converteDataHora(getDataHora());

	if ($operador) {
		$filtro_operador = "AND a.id_usuario = $operador";
		$dados_operadores = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_usuario = '" . $operador . "'");

		$operador_legenda = $dados_operadores[0]['nome'];
	} else {
		$operador_legenda = 'Todos';
	}

	if($data_de && $data_ate){
	    $periodo_amostra ="<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> De $data_de até $data_ate</span>";
	}elseif($data_de){
	    $periodo_amostra ="<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> A partir de $data_de</span>";
	}elseif($data_ate){
	    $periodo_amostra ="<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> Até $data_ate</span>";
	}else{
	    $periodo_amostra = "";
	}

	$data_de_dias = new DateTime($data_de);
	$data_de_dias->modify('first day of this month');
	$referencia_escala = $data_de_dias->format('Y-m-d');

	if($lider){
		$filtro_lider = "AND a.lider_direto = '".$lider."'";
		$dados_lider = DBRead('','tb_usuario a',"INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_usuario = '".$lider."'");
		$legenda_lider = $dados_lider[0]['nome'];
	}else{
		$legenda_lider = 'Qualquer';
		$filtro_lider = "";
	}

	if($turno){
		$filtro_turno = "AND c.data_inicial = '".$referencia_escala."' AND c.carga_horaria = '".$turno."' ";
		$inner_join_turno = "INNER JOIN tb_horarios_escala c ON a.id_usuario = c.id_usuario";
		if($turno == "integral"){
			$legenda_turno = "Integral";
		}else if($turno == "meio"){
			$legenda_turno = "Meio Turno";
		}else if($turno == "jovem"){
			$legenda_turno = "Jovem Aprendiz";
		}else if($turno == "estagio"){
			$legenda_turno = "Estágio";
		}
		
	}else{
		$legenda_turno = 'Qualquer';
		$filtro_turno = "";
		$inner_join_turno = "";
	}
	
	echo "<div class=\"col-md-10 col-md-offset-1\" style=\"padding: 0\">";
	echo "<legend style=\"text-align:center;\"><strong>Relatório de Atendimento por Chat (Operador)</strong><br><span style=\"font-size: 14px;\"><strong>Gerado em:</strong> $data_hora</span></legend>";	
    echo "<legend style=\"text-align:center;\">$periodo_amostra</legend>";
	echo "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong>Atendente - </strong>".$operador_legenda.", <strong>Líder - </strong>".$legenda_lider.", <strong>Turno/Horário - </strong>".$legenda_turno."</legend>";

	$filtro = '';	

    if ($data_de) {
		$filtro .= " AND data_inicio >= '".converteData($data_de)." 00:00:00'";
	}
	if ($data_ate) {
		$filtro .= " AND data_inicio <= '".converteData($data_ate)." 23:59:59'";
	}

	$usuarios = DBRead('','tb_usuario a'," INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa ".$inner_join_turno." WHERE a.id_usuario $filtro_operador ".$filtro_lider." ".$filtro_turno." ORDER BY b.nome", 'a.id_usuario, a.id_asterisk, b.nome');

	registraLog('Relatório de Atendimentos - Atendimento por Chat (Operador).','rel','relatorio_atendimento',1," INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_usuario $filtro_operador ORDER BY b.nome");

	if ($usuarios) {

		$array_atendimentos = array();
		$total_atendimentos = 0;
		$total_horas_pausa = 0;
		$total_tempo_atendimento = 0;
		$total_tma_pausa = 0;
		$total_tma_atendimento = 0;
		$total_percentual = 0;
		$total_atendentes = 0;

		foreach ($usuarios as $conteudo) {

			$atendimento = DBRead('', 'tb_atendimento', "WHERE id_usuario = '".$conteudo['id_usuario']."' AND via_texto = 1 $filtro", 'id_atendimento, data_inicio, data_fim');

			if ($atendimento){
    
    			$dados_pausas = DBRead('snep','queue_agents_pause a',"INNER JOIN queue_agents b ON a.codigo = b.codigo LEFT JOIN tipo_pausa c ON a.tipo_pausa = c.id WHERE a.uniqueid AND a.data_pause >= '".converteData($data_de)." 00:00:00' AND a.data_pause <= '".converteData($data_ate)." 23:59:59' AND a.codigo = '".$conteudo['id_asterisk']."' AND a.tipo_pausa = '19' ORDER BY a.data_pause ASC", "a.*, b.membername AS 'nome_operador', c.nome AS 'nome_pausa'");

				foreach ($dados_pausas as $conteudo_pausa) {
					if($conteudo_pausa['data_pause'] &&  $conteudo_pausa['nome_pausa'] &&  $conteudo_pausa['nome_operador']){
						$data_pausa = strtotime($conteudo_pausa['data_pause']);
						$data_retorno = strtotime($conteudo_pausa['data_unpause']);
						$duracao = $data_retorno ? ($data_retorno - $data_pausa) : 0;
						$array_atendimentos[$conteudo['nome']]['duracao_total_pausa'] += $duracao;
					}
				}
				
				foreach ($atendimento as $conteudo_atendimento) {
					$data_inicio = strtotime($conteudo_atendimento['data_inicio']);
					$data_fim = strtotime($conteudo_atendimento['data_fim']);
					$diferenca = ($data_fim - $data_inicio);
					//echo round(abs($data_fim - $data_inicio) / 60,2). " min<br>";

					$array_atendimentos[$conteudo['nome']]['qtd_atendimentos'] += 1;
					$array_atendimentos[$conteudo['nome']]['duracao_total_atendimento'] += $diferenca;
					$total_atendimentos += 1;
					//echo "id_atendimento: ".$conteudo_atendimento['id_atendimento']." - - - - data_inicio: ".$conteudo_atendimento['data_inicio']." - - - data_fim: ".$conteudo_atendimento['data_fim']." - - - diferenca: ".$diferenca."<hr>";
				}
			}
		}

		if ($array_atendimentos){
			echo '<table class="table table-hover dataTable" style="margin-bottom:0;">
		      <thead>
		        <tr>
		            <th class="text-left">Nome</th>
					<th class="text-left col-md-1">Horas pausa</th>
			        <th class="text-left col-md-1">Quantidade atendimentos</th>
					<th class="text-left col-md-1">Tempo total em atendimento</th>
					<th class="text-left col-md-1">Atendimento hora (horas pausa)</th>
					<th class="text-left col-md-1">Atendimento hora (horas atendimento)</th>
			        <th class="text-left col-md-1">TMA (horas pausa)</th>
					<th class="text-left col-md-1">TMA (horas atendimento)</th>
			        <th class="text-left col-md-1">% sobre</th>
		        </tr>
		      </thead>
		      <tbody>';

			foreach ($array_atendimentos as $key => $value) {
				$total_atendentes++;
				$total_horas_pausa += $value['duracao_total_pausa'];
				$total_tempo_atendimento += $value['duracao_total_atendimento'];

				$tma_pausa = $value['duracao_total_pausa'] / $value['qtd_atendimentos'];
				$total_tma_pausa += $tma_pausa;

				$tma_atendimento = $value['duracao_total_atendimento'] / $value['qtd_atendimentos'];
				$total_tma_atendimento += $tma_atendimento;

				$at_hora_pausa = $value['qtd_atendimentos'] / converteSegundosHoras($value['duracao_total_pausa']);

				$at_hora_atendimento = $value['qtd_atendimentos'] / converteSegundosHoras($value['duracao_total_atendimento']);

				$percentual = ($value['qtd_atendimentos'] * 100) / $total_atendimentos;
				$total_percentual += $percentual;

				$class = '';
				if (converteSegundosHoras($value['duracao_total_pausa']) == '00:00:00') {
					$class = "class='danger'";
				}	

				echo '<tr>';
					echo '<td '.$class.'>'.$key.'</td>';
					echo '<td '.$class.'>'.converteSegundosHoras($value['duracao_total_pausa']).'</td>';
					echo '<td>'.$value['qtd_atendimentos'].'</td>';
					echo '<td>'.converteSegundosHoras($value['duracao_total_atendimento']).'</td>';
					echo '<td class="info">'.floatval(sprintf("%01.2f", $at_hora_pausa)).'</td>';
					echo '<td class="warning">'.floatval(sprintf("%01.2f", $at_hora_atendimento)).'</td>';
					echo '<td class="info">'.converteSegundosHoras($tma_pausa).'</td>';
					echo '<td class="warning">'.converteSegundosHoras($tma_atendimento).'</td>';
					echo '<td>'.floatval(sprintf("%01.2f", $percentual)).'%</td>';
				echo '</tr>';
			}
			echo '</tbody>
				<tfoot>
					<tr>
						<th>Total: '.$total_atendentes.'</th>
						<th>'.converteSegundosHoras($total_horas_pausa).'</th>
						<th>'.$total_atendimentos.'</th>
						<th>'.converteSegundosHoras($total_tempo_atendimento).'</th>
						<th>'.floatval(sprintf("%01.2f", $total_atendimentos/converteSegundosHoras($total_horas_pausa))).'</th>
						<th>'.floatval(sprintf("%01.2f", $total_atendimentos/converteSegundosHoras($total_tempo_atendimento))).'</th>
						<th>'.converteSegundosHoras(($total_tma_pausa/ $total_atendentes)).'</th>
						<th>'.converteSegundosHoras(($total_tma_atendimento/$total_atendentes)).'</th>
						<th>'.floatval(sprintf("%01.2f", $total_percentual)).'%</th>
					</tr>
				</tfoot>
				</table>
				<hr>';

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
								filename: 'relatorio_atendimentos_chat_operador',
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
		}else{
			echo '<div style="text-align: center"><strong>Não foram encontrados atendimentos!</strong></div>';
		}
		
	} else {
		echo '<div style="text-align: center"><strong>Não foram encontrados atendimentos!</strong></div>';
	}
	
    echo "</div>";
}

function relatorio_chat_empresa($data_de, $data_ate, $id_contrato_plano_pessoa){

	$data_hora = converteDataHora(getDataHora());

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

	if($data_de && $data_ate){
	    $periodo_amostra ="<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> De $data_de até $data_ate</span>";
	}elseif($data_de){
	    $periodo_amostra ="<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> A partir de $data_de</span>";
	}elseif($data_ate){
	    $periodo_amostra ="<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> Até $data_ate</span>";
	}else{
	    $periodo_amostra = "";
	}
	
	echo "<div class=\"col-md-10 col-md-offset-1\" style=\"padding: 0\">";
	echo "<legend style=\"text-align:center;\"><strong>Relatório de Atendimento por Chat (Empresa)</strong><br><span style=\"font-size: 14px;\"><strong>Gerado em:</strong> $data_hora</span></legend>";	
    echo "<legend style=\"text-align:center;\">$periodo_amostra</legend>";
	echo "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong>Empresa - </strong>" . $empresa_legenda . "</legend>";

	$filtro = '';	

    if ($data_de) {
		$filtro .= " AND data_inicio >= '".converteData($data_de)." 00:00:00'";
	}
	if ($data_ate) {
		$filtro .= " AND data_inicio <= '".converteData($data_ate)." 23:59:59'";
	}

	$empresas = DBRead('','tb_contrato_plano_pessoa a'," INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_contrato_plano_pessoa $filtro_contrato_plano_pessoa ORDER BY b.nome", 'a.id_contrato_plano_pessoa, a.status, b.nome');

	registraLog('Relatório de Atendimentos - Atendimento por Chat (Empresa).','rel','relatorio_atendimento',1," INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_contrato_plano_pessoa $filtro_contrato_plano_pessoa ORDER BY b.nome");


	if ($empresas) {

		$array_atendimentos = array();
		$total_atendimentos = 0;
		$total_tempo_atendimento = 0;
		$total_tma_atendimento = 0;
		$total_percentual = 0;
		$total_empresas = 0;

		foreach ($empresas as $conteudo) {

			$atendimento = DBRead('', 'tb_atendimento', "WHERE id_contrato_plano_pessoa = '".$conteudo['id_contrato_plano_pessoa']."' $filtro AND via_texto = 1", 'id_atendimento, data_inicio, data_fim');

			if ($atendimento){
				
				foreach ($atendimento as $conteudo_atendimento) {
					$data_inicio = strtotime($conteudo_atendimento['data_inicio']);
					$data_fim = strtotime($conteudo_atendimento['data_fim']);
					$diferenca = ($data_fim - $data_inicio);
					//echo round(abs($data_fim - $data_inicio) / 60,2). " min<br>";

					$array_atendimentos[$conteudo['nome']]['qtd_atendimentos'] += 1;
					$array_atendimentos[$conteudo['nome']]['duracao_total_atendimento'] += $diferenca;
					$total_atendimentos += 1;
					//echo "id_atendimento: ".$conteudo_atendimento['id_atendimento']." - - - - data_inicio: ".$conteudo_atendimento['data_inicio']." - - - data_fim: ".$conteudo_atendimento['data_fim']." - - - diferenca: ".$diferenca."<hr>";
				}
			}
		}

		if ($array_atendimentos){
			echo '<table class="table table-hover dataTable" style="margin-bottom:0;">
		      <thead>
		        <tr>
		            <th class="text-left">Nome</th>
			        <th class="text-left col-md-1">Quantidade atendimentos</th>
					<th class="text-left col-md-1">Tempo total em atendimento</th>
					<th class="text-left col-md-1">Atendimento hora (horas atendimento)</th>
					<th class="text-left col-md-1">TMA (horas atendimento)</th>
			        <th class="text-left col-md-1">% sobre</th>
		        </tr>
		      </thead>
		      <tbody>';

			foreach ($array_atendimentos as $key => $value) {
				$total_empresas++;
				$total_tempo_atendimento += $value['duracao_total_atendimento'];

				$tma_atendimento = $value['duracao_total_atendimento'] / $value['qtd_atendimentos'];
				$total_tma_atendimento += $tma_atendimento;

				$at_hora_atendimento = $value['qtd_atendimentos'] / converteSegundosHoras($value['duracao_total_atendimento']);

				$percentual = ($value['qtd_atendimentos'] * 100) / $total_atendimentos;
				$total_percentual += $percentual;

				echo '<tr>';
					echo '<td>'.$key.'</td>';
					echo '<td>'.$value['qtd_atendimentos'].'</td>';
					echo '<td>'.converteSegundosHoras($value['duracao_total_atendimento']).'</td>';
					echo '<td class="warning">'.floatval(sprintf("%01.2f", $at_hora_atendimento)).'</td>';
					echo '<td class="warning">'.converteSegundosHoras($tma_atendimento).'</td>';
					echo '<td>'.floatval(sprintf("%01.2f", $percentual)).'%</td>';
				echo '</tr>';
			}
			echo '</tbody>
				<tfoot>
					<tr>
						<th>Total: '.$total_empresas.'</th>
						<th>'.$total_atendimentos.'</th>
						<th>'.converteSegundosHoras($total_tempo_atendimento).'</th>
						<th>'.floatval(sprintf("%01.2f", $total_atendimentos/converteSegundosHoras($total_tempo_atendimento))).'</th>
						<th>'.converteSegundosHoras(($total_tma_atendimento/$total_empresas)).'</th>
						<th>'.floatval(sprintf("%01.2f", $total_percentual)).'%</th>
					</tr>
				</tfoot>
				</table>
				<hr>';
			
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
								filename: 'relatorio_atendimentos_chat_empresa',
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
		
	} else {
		echo '<div style="text-align: center"><strong>Não foram encontradas metas para esse período!</strong></div>';
	}
	
    echo "</div>";
}

?>