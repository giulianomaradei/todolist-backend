
<?php
	require_once(__DIR__."/../class/System.php");

$hoje = getDataHora();
$hoje = converteDataHora($hoje);
$data_hoje = explode("/", $hoje);
$hoje = $data_hoje[0];
$data_de_hoje = getDataHora();
$data_de_hoje = converteDataHora($data_de_hoje);
$mes_atual = "01/".$data_hoje[1]."/".$data_hoje[2];
$hoje = $data_hoje[0]."/".$data_hoje[1]."/".$data_hoje[2];
$tipo_relatorio = (! empty($_POST['tipo_relatorio'])) ? $_POST['tipo_relatorio'] : 1;
$data_de = (!empty($_POST['data_de'])) ? $_POST['data_de'] : $mes_atual;
$data_ate = (!empty($_POST['data_ate'])) ? $_POST['data_ate'] : $hoje;
$pesquisa = (! empty($_POST['pesquisa'])) ? $_POST['pesquisa'] : '';
$gerar = (! empty($_POST['gerar'])) ? 1 : 0;
$id_usuario = $_SESSION['id_usuario'];
$usuario = (! empty($_POST['usuario'])) ? $_POST['usuario'] : '';
$dados = DBRead('', 'tb_usuario', "WHERE id_usuario = '$id_usuario'");
$perfil_sistema = $dados[0]['id_perfil_sistema'];
$status = (! empty($_POST['status'])) ? $_POST['status'] : '';
$tipo_consulta = (! empty($_POST['tipo_consulta'])) ? $_POST['tipo_consulta'] : '';
$contrato = (! empty($_POST['contrato'])) ? $_POST['contrato'] : '';
$zerados = (! empty($_POST['zerados'])) ? $_POST['zerados'] : '1';
$situacao_agendamento = (! empty($_POST['situacao_agendamento'])) ? $_POST['situacao_agendamento'] : '';
$parametro = (! empty($_POST['parametro'])) ? $_POST['parametro'] : '0';
$contrato2 = (! empty($_POST['contrato2'])) ? $_POST['contrato2'] : '';

if ($gerar) {
    $collapse = '';
    $collapse_icon = 'plus';
} else {
    $collapse = 'in';
    $collapse_icon = 'minus';
}
if ($tipo_relatorio == 1) {
    $display_row_data = '';
    $display_row_pesquisa = '';
    $display_row_operador = 'style="display:none;"';
    $display_row_contatos_consulta_de = 'style="display:none;"';
    $display_row_contatos_status = 'style="display:none;"';
    $display_row_contrato = 'style="display:none;"';
    $display_row_zerados = 'style="display:none;"';
    $display_row_situacao_agendamento = 'style="display:none;"';
    $display_row_parametro = 'style="display:none;"';
    $display_row_contrato2 = 'style="display:none;"';
} else if ($tipo_relatorio == 2) {
    $display_row_data = '';
    $display_row_pesquisa = '';
    $display_row_operador = '';
    $display_row_contatos_consulta_de = 'style="display:none;"';
    $display_row_contatos_status = 'style="display:none;"';
    $display_row_contrato = 'style="display:none;"';
    $display_row_zerados = 'style="display:none;"';
    $display_row_situacao_agendamento = 'style="display:none;"';
    $display_row_parametro = 'style="display:none;"';
    $display_row_contrato2 = 'style="display:none;"';
} else if ($tipo_relatorio == 3) {
    $display_row_data = '';
    $display_row_pesquisa = '';
    $display_row_operador = 'style="display:none;"';
    $display_row_contatos_consulta_de = 'style="display:none;"';
    $display_row_contatos_status = 'style="display:none;"';
    $display_row_contrato = 'style="display:none;"';
    $display_row_zerados = 'style="display:none;"';
    $display_row_situacao_agendamento = 'style="display:none;"';
    $display_row_parametro = 'style="display:none;"';
    $display_row_contrato2 = 'style="display:none;"';
} else if ($tipo_relatorio == 4) {
    $display_row_data = '';
    $display_row_pesquisa = '';
    $display_row_operador = 'style="display:none;"';
    $display_row_contatos_consulta_de = 'style="display:none;"';
    $display_row_contatos_status = 'style="display:none;"';
    $display_row_contrato = 'style="display:none;"';
    $display_row_zerados = 'style="display:none;"';
    $display_row_situacao_agendamento = 'style="display:none;"';
    $display_row_parametro = 'style="display:none;"';
    $display_row_contrato2 = 'style="display:none;"';
} else if ($tipo_relatorio == 5 || $tipo_relatorio == 15) {
    $display_row_data = '';
    $display_row_pesquisa = '';
    $display_row_operador = 'style="display:none;"';
    $display_row_contatos_consulta_de = 'style="display:none;"';
    $display_row_contatos_status = 'style="display:none;"';
    $display_row_contrato = 'style="display:none;"';
    $display_row_zerados = 'style="display:none;"';
    $display_row_situacao_agendamento = 'style="display:none;"';
    $display_row_parametro = 'style="display:none;"';
    $display_row_contrato2 = 'style="display:none;"';
} else if ($tipo_relatorio == 6) {
    $display_row_data = '';
    $display_row_pesquisa = '';
    $display_row_operador = 'style="display:none;"';
    $display_row_contatos_consulta_de = 'style="display:none;"';
    $display_row_contatos_status = 'style="display:none;"';
    $display_row_contrato = 'style="display:none;"';
    $display_row_zerados = 'style="display:none;"';
    $display_row_situacao_agendamento = '';
    $display_row_parametro = 'style="display:none;"';
    $display_row_contrato2 = 'style="display:none;"';
} else if ($tipo_relatorio == 7) {
    $display_row_data = 'style="display:none;"';
    $display_row_pesquisa = '';
    $display_row_operador = 'style="display:none;"';
    $display_row_contatos_consulta_de = 'style="display:none;"';
    $display_row_contatos_status = 'style="display:none;"';
    $display_row_contrato = 'style="display:none;"';
    $display_row_zerados = 'style="display:none;"';
    $display_row_situacao_agendamento = 'style="display:none;"';
    $display_row_parametro = 'style="display:none;"';
    $display_row_contrato2 = 'style="display:none;"';
} else if ($tipo_relatorio == 8) {
    $display_row_data = '';
    $display_row_pesquisa = '';
    $display_row_operador = 'style="display:none;"';
    $display_row_contatos_consulta_de = 'style="display:none;"';
    $display_row_contatos_status = 'style="display:none;"';
    $display_row_contrato = 'style="display:none;"';
    $display_row_zerados = 'style="display:none;"';
    $display_row_situacao_agendamento = 'style="display:none;"';
    $display_row_parametro = 'style="display:none;"';
    $display_row_contrato2 = 'style="display:none;"';
} else if ($tipo_relatorio == 9) {
    $display_row_data = '';
    $display_row_pesquisa = '';
    $display_row_operador = '';
    $display_row_contatos_consulta_de = 'style="display:none;"';
    $display_row_contatos_status = 'style="display:none;"';
    $display_row_contrato = 'style="display:none;"';
    $display_row_zerados = 'style="display:none;"';
    $display_row_situacao_agendamento = 'style="display:none;"';
    $display_row_parametro = 'style="display:none;"';
    $display_row_contrato2 = 'style="display:none;"';
} else if ($tipo_relatorio == 10){
    $display_row_data = '';
    $display_row_pesquisa = '';
    $display_row_operador = 'style="display:none;"';
    $display_row_contatos_consulta_de = 'style="display:none;"';
    $display_row_contatos_status = 'style="display:none;"';
    $display_row_contrato = 'style="display:none;"';
    $display_row_zerados = 'style="display:none;"';
    $display_row_situacao_agendamento = 'style="display:none;"';
    $display_row_parametro = 'style="display:none;"';
    $display_row_contrato2 = 'style="display:none;"';
} else if ($tipo_relatorio == 11){
    $display_row_data = '';
    $display_row_pesquisa = '';
    $display_row_operador = '';
    $display_row_contatos_consulta_de = 'style="display:none;"';
    $display_row_contatos_status = 'style="display:none;"';
    $display_row_contrato = 'style="display:none;"';
    $display_row_zerados = 'style="display:none;"';
    $display_row_situacao_agendamento = 'style="display:none;"';
    $display_row_parametro = 'style="display:none;"';
    $display_row_contrato2 = 'style="display:none;"';
} else if ($tipo_relatorio == 12){
    $display_row_data = '';
    $display_row_pesquisa = '';
    $display_row_operador = 'style="display:none;"';
    $display_row_contatos_consulta_de = '';
    $display_row_contatos_status = '';
    $display_row_contrato = 'style="display:none;"';
    $display_row_zerados = 'style="display:none;"';
    $display_row_situacao_agendamento = 'style="display:none;"';
    $display_row_parametro = 'style="display:none;"';
    $display_row_contrato2 = 'style="display:none;"';
} else if ($tipo_relatorio == 13){
    $display_row_data = '';
    $display_row_pesquisa = 'style="display:none;"';
    $display_row_operador = 'style="display:none;"';
    $display_row_contatos_consulta_de = 'style="display:none;"';
    $display_row_contatos_status = 'style="display:none;"';
    $display_row_contrato = '';
    $display_row_zerados = '';
	$display_row_situacao_agendamento = 'style="display:none;"';
    $display_row_parametro = 'style="display:none;"';
    $display_row_contrato2 = 'style="display:none;"';
} else if ($tipo_relatorio == 14){
    $display_row_data = '';
    $display_row_pesquisa = 'style="display:none;"';
    $display_row_operador = '';
    $display_row_contatos_consulta_de = 'style="display:none;"';
    $display_row_contatos_status = 'style="display:none;"';
    $display_row_contrato = 'style="display:none;"';
    $display_row_zerados = 'style="display:none;"';
    $display_row_situacao_agendamento = 'style="display:none;"';
    $display_row_parametro = '';
    $display_row_contrato2 = '';
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

<script src="https://code.highcharts.com/7.2.1/highcharts.js"></script>
<script src="https://code.highcharts.com/7.2.1/modules/exporting.js"></script>
<script src="https://code.highcharts.com/7.2.1/modules/export-data.js"></script>

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs/jszip-2.5.0/dt-1.10.18/b-1.5.4/b-colvis-1.5.4/b-flash-1.5.4/b-html5-1.5.4/b-print-1.5.4/r-2.2.2/datatables.min.css"/> 
<script type="text/javascript" src="https://cdn.datatables.net/v/bs/jszip-2.5.0/dt-1.10.18/b-1.5.4/b-colvis-1.5.4/b-flash-1.5.4/b-html5-1.5.4/b-print-1.5.4/r-2.2.2/datatables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/plug-ins/1.10.19/sorting/time.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/plug-ins/1.10.19/sorting/chinese-string.js"></script>

<div class="container-fluid">
	<form method="post" action="">
		<div class="row">
			<div class="col-md-4 col-md-offset-4">
				<div class="panel panel-default noprint">
					<div class="panel-heading clearfix">
						<h3 class="panel-title text-left pull-left"
							style="margin-top: 2px;">Relatório de Pesquisas:</h3>
						<div class="panel-title text-right pull-right">
							<button data-toggle="collapse" data-target="#accordionRelatorio"
								class="btn btn-xs btn-info" type="button"
								title="Visualizar filtros">
								<i id="i_collapse" class="fa fa-<?=$collapse_icon?>"></i>
							</button>
						</div>
					</div>
					<div id="accordionRelatorio" class="panel-collapse collapse <?=$collapse?>">
						<div class="panel-body">
							<div class="row">
								<div class="col-md-12">
									<div class="form-group">
										<label>Tipo de Relatório:</label> 
										<select name="tipo_relatorio" id="tipo_relatorio" class="form-control input-sm">
											<option value="6" <?php if($tipo_relatorio == '6'){echo 'selected';}?>>Agendamentos</option>
											<option value="9" <?php if($tipo_relatorio == '9'){echo 'selected';}?>>Contagem por Atendentes - Pesquisa</option>
											<option value="14" <?php if($tipo_relatorio == '14'){echo 'selected';}?>>Contagem por Atendentes - Contrato</option>
											<option value="12"<?php if($tipo_relatorio == '12'){echo 'selected';}?>>Contatos</option>	
											<option value="2" <?php if($tipo_relatorio == '2'){echo 'selected';}?>>Entrevistas</option>
											<option value="10"<?php if($tipo_relatorio == '10'){echo 'selected';}?>>Entrevistas - Tabela</option>
											<option value="4" <?php if($tipo_relatorio == '4'){echo 'selected';}?>>Falhas</option>
											<option value="15" <?php if($tipo_relatorio == '15'){echo 'selected';}?>>Falhas Completas</option>
											<option value="11"<?php if($tipo_relatorio == '11'){echo 'selected';}?>>Falhas Eliminatórias</option>
											<option value="8" <?php if($tipo_relatorio == '8'){echo 'selected';}?>>Faturamento</option>
											<option value="1" <?php if($tipo_relatorio == '1'){echo 'selected';}?>>Geral</option>
											<option value="13" <?php if($tipo_relatorio == '13'){echo 'selected';}?>>Sintético por Cliente</option>
											<!-- <option value="3" <?php //if($tipo_relatorio == '3'){echo 'selected';}?>>Notas</option>-->
											<!-- <option value="5" <?php //if($tipo_relatorio == '5'){echo 'selected';}?>>Falhas Completas</option> -->

											
										</select>
									</div>
								</div>
							</div>
							
							<div class="row" id="row_contatos_consulta_de" <?=$display_row_contatos_consulta_de?>>
								<div class="col-md-12">
									<div class="form-group">
										<label for="">Filtro de Data:</label>
										<select name="tipo_consulta" class="form-control input-sm">
								            <option value=""  <?php if($tipo_consulta == ''){echo 'selected';}?>>Qualquer</option>
											<option value="1" <?php if($tipo_consulta == '1'){echo 'selected';}?>>Data do cadastro</option>
											<option value="2" <?php if($tipo_consulta == '2'){echo 'selected';}?>>Data da última entrevista</option>
								        </select>
									</div>
								</div>
							</div>

							<div class="row" id="row_data" <?=$display_row_data?>>
								<div class="col-md-6">
									<div class="form-group">
										<label>Data Inicial:</label> 
										<input type="text" class="form-control date calendar input-sm" name="data_de" value="<?=substr($data_de,0,10)?>" id="data_de" required>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label>Data Final:</label> <input type="text" class="form-control date calendar input-sm" name="data_ate" value="<?=substr($data_ate,0,10)?>" id="data_ate" required>
									</div>
								</div>
							</div>
							<div class="row" id="row_contatos_status" <?=$display_row_contatos_status?>>
								<div class="col-md-12">
									<div class="form-group">
										<label for="">Status:</label>
										<select name="status" class="form-control input-sm">
											<option value=""  <?php if($status == ''){echo 'selected';}?>>Qualquer</option>
											<option value="7" <?php if($status == '7'){echo 'selected';}?>>Em aberto</option>
											<option value="1" <?php if($status == '1'){echo 'selected';}?>>Entrevista realizada com sucesso</option>
											<option value="2" <?php if($status == '2'){echo 'selected';}?>>Todas as tentativas resultaram em falhas</option>
											<option value="3" <?php if($status == '3'){echo 'selected';}?>>Ligação efetuada mas o cliente não quis participar da entrevista</option>
								        </select>
									</div>
								</div>
							</div>
							<div class="row" id="row_pesquisa" <?=$display_row_pesquisa?>>
								<div class="col-md-12">
									<div class="form-group">
										<label>Pesquisa:</label>
										<select name="pesquisa" class="form-control input-sm">
								        </select>
									</div>
								</div>
							</div>

							<div class="row" id="row_zerados" <?=$display_row_zerados?>>
								<div class="col-md-12">
									<div class="form-group">
										<label>Mostrar Zerados:</label>
										<select name="zerados" class="form-control input-sm">
								        	<option value="1" <?php if($zerados == '1'){echo 'selected';}?>>Sim</option>
								            <option value="2" <?php if($zerados == '2'){echo 'selected';}?>>Não</option>
								        </select>
									</div>
								</div>
							</div>

							<div class="row" id="row_situacao_agendamento" <?=$display_row_situacao_agendamento?>>
								<div class="col-md-12">
									<div class="form-group">
										<label>Situação:</label>
										<select name="situacao_agendamento" class="form-control input-sm">
								        	<option value="" <?php if(!$situacao_agendamento){echo 'selected';}?>>Qualquer</option>
								            <option value="2" <?php if($situacao_agendamento == '2'){echo 'selected';}?>>Em Aberto</option>
								        	<option value="1" <?php if($situacao_agendamento == '1'){echo 'selected';}?>>Entrevistado</option>
								        </select>
									</div>
								</div>
							</div>

							<div class="row" id="row_contrato" <?=$display_row_contrato?>>
								<div class="col-md-12">
									<div class="form-group">
										<label for="">Contrato:</label>
										<select name="contrato" class="form-control input-sm">
								            <?php
											$dados_contratos = DBRead('', 'tb_contrato_plano_pessoa a', "INNER JOIN tb_plano b ON a.id_plano = b.id_plano INNER JOIN tb_pessoa c ON a.id_pessoa = c.id_pessoa WHERE b.cod_servico = 'call_ativo' GROUP BY c.id_pessoa ORDER BY c.nome ASC", "c.id_pessoa, c.nome");
											if ($dados_contratos) {
												echo "<option value=''>Todos</option>";
												foreach ($dados_contratos as $conteudo_contratos) {
													$selected = $contrato == $conteudo_contratos['id_pessoa'] ? "selected" : "";
													echo "<option value='" . $conteudo_contratos['id_pessoa']."' ".$selected.">".$conteudo_contratos['nome']."</option>";
												}
											}
											?>
								        </select>
									</div>
								</div>
							</div>

							<div class="row" id="row_contrato2" <?=$display_row_contrato2?>>
								<div class="col-md-12">
									<div class="form-group">
										<label for="">Contrato:</label>
										<select name="contrato2" class="form-control input-sm">
								            <?php
											if ($dados_contratos) {
												echo "<option value=''>Todos</option>";
												foreach ($dados_contratos as $conteudo_contratos) {
													$selected = $contrato2 == $conteudo_contratos['id_pessoa'] ? "selected" : "";
													echo "<option value='" . $conteudo_contratos['id_pessoa']."' ".$selected.">".$conteudo_contratos['nome']."</option>";
												}
											}
											?>
								        </select>
									</div>
								</div>
							</div>
							
							<?php if($perfil_sistema != '3'){ ?>
							<div class="row" id="row_operador" <?=$display_row_operador?>>
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
														echo "<option value='" . $conteudo_usuarios['id_usuario'] . "' " . $selected . ">" . $conteudo_usuarios['nome'] . "</option>";
													}
												}
												?>
										</select>
									</div>
								</div>
							</div>
							<?php } ?>	
							
							<div class="row" id="row_parametro" <?=$display_row_parametro?>>
								<div class="col-md-12">
									<div class="form-group">
								        <label for="">Quantidade de Perguntas:</label><br>
								        <input type="number" class="form-control input-sm" name="parametro" value="<?=$parametro?>">
								    </div>
								</div>
							</div>

		                </div>
					</div>
					<div class="panel-footer">
						<div class="row">
							<div id="panel_buttons" class="col-md-12" style="text-align: center">
								<button class="btn btn-primary" name="gerar" id="gerar"
									value="1" type="submit">
									<i class="fa fa-refresh"></i> Gerar
								</button>
								<button class="btn btn-warning" name="imprimir" type="button"
									onclick="window.print();">
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
		if ($tipo_relatorio == 1 && $pesquisa) {
			relatorio_geral($data_de, $data_ate, $usuario, $pesquisa);
		} else if ($tipo_relatorio == 2 && $pesquisa) {
			relatorio_entrevistas($data_de, $data_ate, $pesquisa, $usuario);
		} else if ($tipo_relatorio == 3 && $pesquisa) {
			relatorio_notas($data_de, $data_ate, $pesquisa);
		} else if ($tipo_relatorio == 4 && $pesquisa) {
			relatorio_falhas($data_de, $data_ate, $pesquisa);
		} else if ($tipo_relatorio == 5 && $pesquisa) {
			relatorio_falhas_completas($data_de, $data_ate, $pesquisa);
		} else if ($tipo_relatorio == 6 && $pesquisa) {
			relatorio_agendamentos($data_de, $data_ate, $pesquisa,$situacao_agendamento);
		} else if ($tipo_relatorio == 8) {
			relatorio_faturamento($pesquisa, $data_de, $data_ate);
		} else if ($tipo_relatorio == 9 && $pesquisa) {
			relatorio_contagem_atendentes($pesquisa, $data_de, $data_ate, $usuario);
		}else if ($tipo_relatorio == 10 && $pesquisa) {
			relatorio_tabela($data_de, $data_ate, $pesquisa);
		}else if ($tipo_relatorio == 11 && $pesquisa) {
			relatorio_falha_eliminatoria($data_de, $data_ate, $pesquisa);
		}else if ($tipo_relatorio == 12 && $pesquisa) {
			relatorio_contatos($data_de, $data_ate, $pesquisa, $status, $tipo_consulta);
		}else if ($tipo_relatorio == 13) {
			relatorio_contrato($contrato, $data_de, $data_ate, $zerados);
		}else if ($tipo_relatorio == 14) {
			relatorio_contrato_contagem_atendentes($contrato2, $data_de, $data_ate, $usuario, $parametro);
		}else if ($tipo_relatorio == 15 && $pesquisa) {
			relatorio_falhas_completas_tabela($data_de, $data_ate, $pesquisa);
		}else{
            echo '<div class="alert alert-danger text-center">Erro ao exibir relatório!</div>';
        }
	}
	?>
	</div>
</div>
<script>

	$(function () {
	  $('[data-toggle="tooltip"]').tooltip()
	})
	
	$('#tipo_relatorio').on('change',function(){
		tipo_relatorio = $(this).val();
		var data_de = $("input[name=data_de]").val();
  		var data_ate = $("input[name=data_ate]").val();
		if(tipo_relatorio == 1){
			$('#row_data').show();
			$('#row_pesquisa').show();
			$('#row_operador').hide();
			$('#row_contatos_consulta_de').hide();
			$('#row_contatos_status').hide();
			$('#row_contrato').hide();
			$('#row_zerados').hide();
			$('#row_situacao_agendamento').hide();
			$('#row_parametro').hide();
			$('#row_contrato2').hide();
			selectPesquisa(data_de, data_ate, '');

		}else if(tipo_relatorio == 2){
			$('#row_data').show();
			$('#row_pesquisa').show();
			$('#row_operador').show();
			$('#row_contatos_consulta_de').hide();
			$('#row_contatos_status').hide();
			$('#row_contrato').hide();
			$('#row_zerados').hide();
			$('#row_situacao_agendamento').hide();
			$('#row_parametro').hide();
			$('#row_contrato2').hide();
			selectPesquisa(data_de, data_ate, '');

		}else if(tipo_relatorio == 3){
			$('#row_data').show();
			$('#row_pesquisa').show();
			$('#row_operador').hide();
			$('#row_contatos_consulta_de').hide();
			$('#row_contatos_status').hide();
			$('#row_contrato').hide();
			$('#row_zerados').hide();
			$('#row_situacao_agendamento').hide();
			$('#row_parametro').hide();
			$('#row_contrato2').hide();
			selectPesquisa(data_de, data_ate, '');

		}else if(tipo_relatorio == 4){
			$('#row_data').show();
			$('#row_pesquisa').show();
			$('#row_operador').hide();
			$('#row_contatos_consulta_de').hide();
			$('#row_contatos_status').hide();
			$('#row_contrato').hide();
			$('#row_zerados').hide();
			$('#row_situacao_agendamento').hide();
			$('#row_parametro').hide();
			$('#row_contrato2').hide();
			selectPesquisa(data_de, data_ate, '');

		}else if(tipo_relatorio == 5 || tipo_relatorio == 15){
			$('#row_data').show();
			$('#row_pesquisa').show();
			$('#row_operador').hide();
			$('#row_contatos_consulta_de').hide();
			$('#row_contatos_status').hide();
			$('#row_contrato').hide();
			$('#row_zerados').hide();
			$('#row_situacao_agendamento').hide();
			$('#row_parametro').hide();
			$('#row_contrato2').hide();
			selectPesquisa(data_de, data_ate, '');

		}else if(tipo_relatorio == 6){
			$('#row_data').show();
			$('#row_pesquisa').show();
			$('#row_operador').hide();
			$('#row_contatos_consulta_de').hide();
			$('#row_contatos_status').hide();
			$('#row_contrato').hide();
			$('#row_zerados').hide();
			$('#row_situacao_agendamento').show();
			$('#row_parametro').hide();
			$('#row_contrato2').hide();
			selectPesquisa(data_de, data_ate, '');

		}else if(tipo_relatorio == 7){
			$('#row_data').hide();
			$('#row_pesquisa').show();
			$('#row_operador').hide();
			$('#row_contatos_consulta_de').hide();
			$('#row_contatos_status').hide();
			$('#row_contrato').hide();
			$('#row_zerados').hide();
			$('#row_situacao_agendamento').hide();
			$('#row_parametro').hide();
			$('#row_contrato2').hide();
			selectPesquisa(data_de, data_ate, '');

		}else if(tipo_relatorio == 8){
			$('#row_data').show();
			$('#row_pesquisa').show();
			$('#row_operador').hide();
			$('#row_contatos_consulta_de').hide();
			$('#row_contatos_status').hide();
			$('#row_contrato').hide();
			$('#row_zerados').hide();
			$('#row_situacao_agendamento').hide();
			$('#row_parametro').hide();
			$('#row_contrato2').hide();
			selectPesquisaFaturamento(data_de, data_ate, '');

		}else if(tipo_relatorio == 9){
			$('#row_data').show();
			$('#row_pesquisa').show();
			$('#row_operador').show();
			$('#row_contatos_consulta_de').hide();
			$('#row_contatos_status').hide();
			$('#row_contrato').hide();
			$('#row_zerados').hide();
			$('#row_situacao_agendamento').hide();
			$('#row_parametro').hide();
			$('#row_contrato2').hide();
			selectPesquisa(data_de, data_ate, '');

		}else if(tipo_relatorio == 10){
			$('#row_data').show();
			$('#row_pesquisa').show();
			$('#row_operador').hide();
			$('#row_contatos_consulta_de').hide();
			$('#row_contatos_status').hide();
			$('#row_contrato').hide();
			$('#row_zerados').hide();
			$('#row_situacao_agendamento').hide();
			$('#row_parametro').hide();
			$('#row_contrato2').hide();
			selectPesquisa(data_de, data_ate, '');

		}else if(tipo_relatorio == 11){
			$('#row_data').show();
			$('#row_pesquisa').show();
			$('#row_operador').hide();
			$('#row_contatos_consulta_de').hide();
			$('#row_contatos_status').hide();
			$('#row_contrato').hide();
			$('#row_zerados').hide();
			$('#row_situacao_agendamento').hide();
			$('#row_parametro').hide();
			$('#row_contrato2').hide();
			selectPesquisa(data_de, data_ate, '');

		}else if(tipo_relatorio == 12){
			$('#row_data').show();
			$('#row_pesquisa').show();
			$('#row_operador').hide();
			$('#row_contatos_consulta_de').show();
			$('#row_contatos_status').show();
			$('#row_contrato').hide();
			$('#row_zerados').hide();
			$('#row_situacao_agendamento').hide();
			$('#row_parametro').hide();
			$('#row_contrato2').hide();
			selectPesquisa(data_de, data_ate, '');

		}else if(tipo_relatorio == 13){
			$('#row_data').show();
			$('#row_pesquisa').hide();
			$('#row_operador').hide();
			$('#row_contatos_consulta_de').hide();
			$('#row_contatos_status').hide();
			$('#row_contrato').show();
			$('#row_zerados').show();
			$('#row_situacao_agendamento').hide();
			$('#row_parametro').hide();
			$('#row_contrato2').hide();
			selectPesquisa(data_de, data_ate, '');

		}else if(tipo_relatorio == 14){
			$('#row_data').show();
			$('#row_pesquisa').hide();
			$('#row_operador').show();
			$('#row_contatos_consulta_de').hide();
			$('#row_contatos_status').hide();
			$('#row_contrato').hide();
			$('#row_zerados').hide();
			$('#row_situacao_agendamento').hide();
			$('#row_parametro').show();
			$('#row_contrato2').show();
			selectPesquisa(data_de, data_ate, '');

		}
	});  

	$('#accordionRelatorio').on('shown.bs.collapse', function () {
		$("#i_collapse").removeClass("fa fa-plus").addClass("fa fa-minus");
	});

	$('#accordionRelatorio').on('hidden.bs.collapse', function () {
		$("#i_collapse").removeClass("fa fa-minus").addClass("fa fa-plus");
	});

	$(document).ready(function(){
		$('#aguarde').hide();
		$('#resultado').show();
		$("#gerar").prop("disabled", false);

		var data_de = $("input[name=data_de]").val();
  		var data_ate = $("input[name=data_ate]").val();
    //_________________________________________________________________________________________
		var id_pesquisa = "<?=$pesquisa?>";

		var tipo_relatorio = $('#tipo_relatorio').val();
		
		if(tipo_relatorio == '8'){
			selectPesquisaFaturamento(data_de, data_ate, id_pesquisa);
		}else{
			selectPesquisa(data_de, data_ate, id_pesquisa);
		}
    //_________________________________________________________________________________________
	});

	function selectplano(cod_servico, id_plano){        
        id_plano  = '<?=$id_plano?>';
        $("select[name=id_plano]").html('<option value="">Carregando...</option>');
        $.post("/api/ajax?class=SelectPlano.php",
            {cod_servico: cod_servico,
            id_plano: id_plano,
			token: '<?= $request->token ?>'},
            function(valor){
                $("select[name=id_plano]").html(valor);
                if(id_plano == 6){
                    $('#div_valor_unitario').hide();
                }
            }
        )        
    }

    //_________________________________________________________________________________________
    $('.date').on('change',function(){
  		var data_de = $("input[name=data_de]").val();
  		var data_ate = $("input[name=data_ate]").val();
		var tipo_relatorio = $('#tipo_relatorio').val();

		if(tipo_relatorio == '8'){
			selectPesquisaFaturamento(data_de, data_ate, '');
		}else{
			selectPesquisa(data_de, data_ate, '');
		}
    });

    function selectPesquisa(data_de, data_ate, id_pesquisa){
    	
  		if(data_de != "" && data_ate != ""){
            $.ajax({
                url: "/api/ajax?class=SelectPesquisa.php",
                dataType: "html",
                data: {
                    acao: 'busca_pesquisa',
                    parametros: {
                        'data_de' : data_de,
                        'data_ate' : data_ate,
                        'id_pesquisa' : id_pesquisa
                    },
					token: '<?= $request->token ?>'
                },
                 success: function (data) {
					$("select[name=pesquisa]").empty();                    
					$("select[name=pesquisa]").append(data);                    
                }
            });
        }
    }
    //_________________________________________________________________________________________

	function selectPesquisaFaturamento(data_de, data_ate, id_pesquisa){
    	
		if(data_de != "" && data_ate != ""){
		  $.ajax({
			  url: "/api/ajax?class=SelectPesquisa.php",
			  dataType: "html",
			  data: {
				  acao: 'busca_pesquisa_faturamento',
				  parametros: {
					  'data_de' : data_de,
					  'data_ate' : data_ate,
					  'id_pesquisa' : id_pesquisa
				  },
				  token: '<?= $request->token ?>'
			  },
			   success: function (data) {
				  $("select[name=pesquisa]").empty();                    
				  $("select[name=pesquisa]").append(data);                    
			  }
		  });
	  }
  }

    $(document).on('submit', 'form', function () {       
		modalAguarde();
	});

</script>

<?php

function relatorio_falhas_completas_tabela($data_de, $data_ate, $pesquisa){
    
    $data_de_filtro = converteDataHora($data_de);
    $data_ate_filtro = converteDataHora($data_ate);
       
	$pesquisas = DBRead('', 'tb_pesquisa a', "INNER JOIN tb_contrato_plano_pessoa b ON a.id_contrato_plano_pessoa = b.id_contrato_plano_pessoa INNER JOIN tb_pessoa c ON b.id_pessoa = c.id_pessoa WHERE a.id_pesquisa = '$pesquisa'", "c.nome, a.titulo, a.id_pesquisa, b.qtd_contratada, a.qtd_tentativas_cliente");

	$titulo_empresa = $pesquisas[0]['titulo'];
	$nome_empresa = $pesquisas[0]['nome'];
	$id_pesquisa = $pesquisas[0]['id_pesquisa'];
	$qtd_tentativas_cliente = $pesquisas[0]['qtd_tentativas_cliente'];
	

	$falhas = DBRead('', 'tb_falhas_pesquisa a', "INNER JOIN tb_contatos_pesquisa b ON a.id_contatos_pesquisa = b.id_contatos_pesquisa WHERE a.falha != 8 AND a.id_pesquisa = '" . $id_pesquisa . "' AND b.status_pesquisa = 2 AND b.qtd_tentativas_cliente = '".$pesquisas[0]['qtd_tentativas_cliente']."' AND b.id_contatos_pesquisa NOT IN (SELECT id_contatos_pesquisa FROM tb_falhas_pesquisa WHERE falha = 8 ) AND b.data_ultimo_contato BETWEEN '".$data_de_filtro." 00:00:00' AND '".$data_ate_filtro." 23:59:59' GROUP BY b.id_contatos_pesquisa", "COUNT(*) AS cont");
	
	if($falhas){
		$falhas_cont = 0;
		foreach ($falhas as $falha) {
			$falhas_cont = (int)$falhas_cont + (int)$falha['cont'];
		}
	}else{
		$falhas_cont = 0;
	}
		
	$falhas = DBRead('', 'tb_falhas_pesquisa a', "INNER JOIN tb_contatos_pesquisa b ON a.id_contatos_pesquisa = b.id_contatos_pesquisa WHERE a.falha != 8 AND a.id_pesquisa = '" . $id_pesquisa . "' AND b.status_pesquisa = 2 AND b.qtd_tentativas_cliente = '".$pesquisas[0]['qtd_tentativas_cliente']."' AND b.id_contatos_pesquisa NOT IN (SELECT id_contatos_pesquisa FROM tb_falhas_pesquisa WHERE falha = 8 ) AND b.data_ultimo_contato BETWEEN '".$data_de_filtro." 00:00:00' AND '".$data_ate_filtro." 23:59:59' ORDER BY b.id_contatos_pesquisa ASC");

	$data_hora = converteDataHora(getDataHora());
	
	if($data_de && $data_ate){
		$periodo_amostra = "<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> De $data_de até $data_ate</span>";
	}
	echo "<div class=\"col-md-10 col-md-offset-1\" style=\"padding: 0\">";
	echo "<legend style=\"text-align:center;\"><strong>Relatório de Pesquisas - Falhas Completas</strong><br><span style=\"font-size: 14px;\"><strong>Gerado em:</strong> $data_hora</span></legend>";
	echo "<legend style=\"text-align:center;\">$periodo_amostra</legend>";
	echo "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong> Contrato - </strong>".$nome_empresa." - ".$titulo_empresa."</legend>";

	if ($falhas) {
		echo "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong> Total: </strong>".(int)$falhas_cont/(int)$qtd_tentativas_cliente."</legend>";
		
		echo "<table class='table table-hover dataTable'>";
		echo "
			<thead>
				<tr>
					<th class='col-md-3'> Assinante</th>
					<th class='col-md-2'> Telefone</th>
					<th class='col-md-2'> Tipo de falha</th>
					<th class='col-md-3'> Atendente</th>
					<th class='col-md-2'> Data e hora</th>
				</tr>
			</thead>
			<tbody>";
			
		foreach ($falhas as $falha) {
            $pessoa = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_usuario = '" . $falha['id_usuario'] . "'");
            $data_falha = $falha['data_falha'];
            $data_falha = converteDataHora($data_falha);
            if ($falha['falha'] == 1) {
                $tipo_falha = "Chamou até cair";
            } else if ($falha['falha'] == 2) {
                $tipo_falha = "Número não existe";
            } else if ($falha['falha'] == 3) {
                $tipo_falha = "Número errado";
            } else if ($falha['falha'] == 4) {
                $tipo_falha = "Caixa postal/fora de área";
            } else if ($falha['falha'] == 5) {
                $tipo_falha = "Número ocupado";
            } else if ($falha['falha'] == 6) {
                $tipo_falha = "Ligação caiu";
            } else if ($falha['falha'] == 7) {
                $tipo_falha = "Ligar mais tarde";
            } else if ($falha['falha'] == 8) {
                $tipo_falha = "Não ligar novamente";
            }else if ($falha['falha'] == 9) {
	            $tipo_falha = "Chamada com interferência";
			}else if ($falha['falha'] == 10) {
				$tipo_falha = "Contato solicitou para ligar em outro horário, porém era a última tentativa.";
			}

				echo "
				<tr>
					<td data-order='".$falha['nome']."".$falha['id_contatos_pesquisa']."'>".$falha['nome']."</td>
					<td><span class = 'phone'>".$falha['telefone']."</span></td>
					<td>" . $tipo_falha . "</td>
					<td>" . $pessoa[0]['nome'] . "</td>
					<td>" . $data_falha . "</td>
				</tr>";

		}
			echo "
			</tbody>";
		echo "
		</table>";
				
		echo "
		<script>
			$(document).ready(function(){
				var table = $('.dataTable').DataTable({
					\"language\": {
						\"url\": \"//cdn.datatables.net/plug-ins/1.10.19/i18n/Portuguese-Brasil.json\"
					},
					aaSorting: [[1, 'asc']],
					columnDefs: [
						{ type: 'chinese-string', targets: 0 },
					],				        
					\"searching\": false,
					\"paging\":   false,
					\"info\":     false
				});

				var buttons = new $.fn.dataTable.Buttons(table, {
					buttons: [
						{
							extend: 'excelHtml5',
							text: '<i class=\"fa fa-file-excel-o\" aria-hidden=\"true\"></i> Exportar',
							filename: 'relatorio_pesquisa (".$nome_empresa.")',
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

	echo "
	</div>";
    
}

function relatorio_contrato_contagem_atendentes($contrato, $data_de, $data_ate, $usuario, $parametro){
	
	$contrato = '';

	if($contrato && $contrato != ''){
		$dados_contrato = DBRead('', 'tb_pessoa', "WHERE id_pessoa = '".$contrato."' ", "nome");
		$legenda_contrato = $dados_contrato[0]['nome'];
		$filtro_contrato = "AND d.id_pessoa = '$contrato'";
	}else{
		$legenda_contrato = "Todos";
	}

	if($usuario){
		$dados_usuario = DBRead('', 'tb_pessoa a', "INNER JOIN tb_usuario b ON a.id_pessoa = b.id_pessoa WHERE b.id_usuario = '".$usuario."' ", "a.nome");
		$legenda_usuario = $dados_usuario[0]['nome'];
		$filtro_usuario = "AND a.id_usuario = '$usuario'";
	}else{
		$legenda_usuario = 'Todos';
	}
	
	if($parametro){
		$legenda_parametro = $parametro;
	}else{
		$parametro = '0';
		$legenda_parametro = $parametro;
	}

    $data_hora = converteDataHora(getDataHora());

    if ($data_de && $data_ate) {
        $periodo_amostra = "<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> De $data_de até $data_ate</span>";
    }
    echo "<div class=\"col-md-12\" style=\"padding: 0\">";
    echo "<legend style=\"text-align:center;\"><strong>Relatório de Pesquisas - Contagem por Atendentes (Contrato)</strong><br><span style=\"font-size: 14px;\"><strong>Gerado em:</strong> $data_hora</span></legend>";
    echo "<legend style=\"text-align:center;\">$periodo_amostra</legend>";
    echo "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong> Contrato - </strong>".$legenda_contrato.",<strong> Atendente - </strong>".$legenda_usuario.",<strong> Quantidade de Perguntas - </strong>".$legenda_parametro."</legend>";

    $data_ate = converteData($data_ate);
    $data_de = converteData($data_de);
	
    $dados_pesquisa = DBRead('', 'tb_entrevista_pesquisa a', "INNER JOIN tb_contatos_pesquisa b ON a.id_contatos_pesquisa = b.id_contatos_pesquisa INNER JOIN tb_pesquisa c ON a.id_pesquisa = c.id_pesquisa INNER JOIN tb_contrato_plano_pessoa d ON c.id_contrato_plano_pessoa = d .id_contrato_plano_pessoa INNER JOIN tb_pessoa e ON d.id_pessoa = e.id_pessoa WHERE c.id_pesquisa $filtro_contrato $filtro_usuario AND b.data_ultimo_contato BETWEEN '".$data_de." 00:00:00' AND '".$data_ate." 23:59:59' GROUP BY c.id_pesquisa ", "c.id_pesquisa, c.titulo, e.nome AS nome_empresa");

	if($dados_pesquisa){
		$array_atendente = array();
		$array_pesquisas = array();

		foreach ($dados_pesquisa as $conteudo_pesquisa) {
			
			$pesquisa = $conteudo_pesquisa['id_pesquisa'];
			$titulo_pesquisa = $conteudo_pesquisa['nome_empresa']." - ".$conteudo_pesquisa['titulo'];
			
			$dados_pergunta_pesquisa = DBRead('', 'tb_pergunta_pesquisa', "WHERE id_pesquisa = '".$pesquisa."' AND status = 1", "COUNT(id_pergunta_pesquisa) as cont");
			if($dados_pergunta_pesquisa){
				$contador_perguntas = $dados_pergunta_pesquisa[0]['cont'];
			}else{
				$contador_perguntas = 0;
			}

			if(!$usuario){
				$filtro_usuario = "";
			}else{
				$filtro_usuario = " AND a.id_usuario = '$usuario'";
			}

			//Busca por nome cada atendente que trabalhou na pesquisa em questão
			$atendentes = DBRead('', 'tb_usuario a', "INNER JOIN tb_entrevista_pesquisa b ON a.id_usuario = b.id_usuario INNER JOIN tb_pessoa c ON a.id_pessoa = c.id_pessoa WHERE b.id_pesquisa = '".$pesquisa."' $filtro_usuario GROUP BY c.nome, a.id_usuario", "a.id_usuario, c.nome");

			$array_pesquisas[$titulo_pesquisa]['perguntas'] = $contador_perguntas;
			foreach($atendentes as $atendente){

				//Contador para as falhas de cada atendente
				$falhas = DBRead('', 'tb_falhas_pesquisa', "WHERE id_pesquisa = '$pesquisa' AND falha != 8 AND id_usuario = '".$atendente['id_usuario']."' AND data_falha >= '".$data_de." 00:00:00' AND data_falha <= '".$data_ate." 23:59:59'", "COUNT(*) AS total");

				//Contador para as entrevistas concluidas sem falha de cada atendente
				$entrevistas = DBRead('', 'tb_entrevista_pesquisa a', "INNER JOIN tb_contatos_pesquisa b ON a.id_contatos_pesquisa = b.id_contatos_pesquisa where b.status_pesquisa = 1 and b.data_ultimo_contato >= '".$data_de." 00:00:00' AND b.data_ultimo_contato <= '".$data_ate." 23:59:59' AND a.id_pesquisa = '$pesquisa' AND a.id_usuario = '".$atendente['id_usuario']."' GROUP BY a.id_pergunta_pesquisa", "COUNT(a.id_pergunta_pesquisa) AS total_entrevistas");
				
				//Total de ligações efetuadas por cada atendente
				$total_com_falhas = $entrevistas[0]['total_entrevistas'] + $falhas[0]['total'];

				if($total_com_falhas != 0){
					
					$total_entrevistas = !$entrevistas[0]['total_entrevistas'] ? '0' : $entrevistas[0]['total_entrevistas'];
					
					if($contador_perguntas > $parametro){
						$array_atendente[$atendente['nome']]['entrevistas_maior'] += $entrevistas[0]['total_entrevistas'];
						$array_atendente[$atendente['nome']]['entrevistas_menor'] += 0;
						$array_atendente[$atendente['nome']]['falhas'] += $falhas[0]['total'];
						$array_atendente[$atendente['nome']]['total_com_falhas'] += $total_com_falhas;
						$array_atendente[$atendente['nome']]['total_sem_falhas'] += $entrevistas[0]['total_entrevistas'];
					}else{
						$array_atendente[$atendente['nome']]['entrevistas_maior'] += 0;
						$array_atendente[$atendente['nome']]['entrevistas_menor'] += $entrevistas[0]['total_entrevistas'];
						$array_atendente[$atendente['nome']]['falhas'] += $falhas[0]['total'];
						$array_atendente[$atendente['nome']]['total_com_falhas'] += $total_com_falhas;
						$array_atendente[$atendente['nome']]['total_sem_falhas'] += $entrevistas[0]['total_entrevistas'];
					}
					
					$array_pesquisas[$titulo_pesquisa][$atendente['nome']]['total_entrevistas'] = $total_entrevistas;
					$array_pesquisas[$titulo_pesquisa][$atendente['nome']]['falhas_total'] = $falhas[0]['total'];
					$array_pesquisas[$titulo_pesquisa][$atendente['nome']]['total_com_falhas'] = $total_com_falhas;
					
				}else{
					$array_atendente[$atendente['nome']]['entrevistas_maior'] += 0;
					$array_atendente[$atendente['nome']]['entrevistas_menor'] += 0;
					$array_atendente[$atendente['nome']]['falhas'] += 0;
					$array_atendente[$atendente['nome']]['total_com_falhas'] += 0;
					$array_atendente[$atendente['nome']]['total_sem_falhas'] += 0;
				}
			}
		}
		
		echo '
		<table class="table table-hover dataTable" style="margin-bottom:0;">
				<thead>
				<tr>
					<th colspan="6" class="text-center"><h3>Contagem por Atendentes</h3><h4>Quantidade de Pesquisas - '.sizeof($dados_pesquisa).'</h4></th>
				</tr>
				<tr>
					<th class="text-left col-md-2">Nome</th>
					<th class="text-center col-md-2">Entrevistas Maiores que '.$parametro.'</th>
					<th class="text-center col-md-2">Entrevistas Menores/Iguais que '.$parametro.'</th>
					<th class="text-center col-md-2">Falhas</th>
					<th class="text-center col-md-2">Total com Falhas</th>
					<th class="text-center col-md-2">Total sem Falhas</th>
				</tr>
			</thead>
			<tbody>';     
			
			$contador_entrevistas_maiores = 0;
			$contador_entrevistas_menores = 0;
			$contador_falhas = 0;
			$contador_total_com_falhas = 0;
			$contador_total_sem_falhas = 0;

			foreach ($array_atendente as $nome_atendente => $conteudo) {

				echo "<tr>";
					echo "<td class='text-left'>" . $nome_atendente . "</td>";
					echo "<td class='text-center'>" . $conteudo['entrevistas_maior'] . "</td>";
					echo "<td class='text-center'>" . $conteudo['entrevistas_menor'] . "</td>";
					echo "<td class='text-center'>" . $conteudo['falhas'] . "</td>";
					echo "<td class='text-center'>" . $conteudo['total_com_falhas'] . "</td>";
					echo "<td class='text-center'>" . $conteudo['total_sem_falhas'] . "</td>";
				echo "</tr>";

				$contador_entrevistas_maiores = $contador_entrevistas_maiores + $conteudo['entrevistas_maior'];
				$contador_entrevistas_menores = $contador_entrevistas_menores + $conteudo['entrevistas_menor'];
				$contador_falhas = $contador_falhas + $conteudo['falhas'];
				$contador_total_com_falhas = $contador_total_com_falhas + $conteudo['total_com_falhas'];
				$contador_total_sem_falhas = $contador_total_sem_falhas + $conteudo['total_sem_falhas'];
					
			}
			echo "
				</tbody>";
				echo "
				<tfoot>";
					echo '<tr>';
						echo '<th class="text-left">Totais</th>';
						echo '<th class="text-center">'.$contador_entrevistas_maiores.'</th>';		
						echo '<th class="text-center">'.$contador_entrevistas_menores.'</th>';		
						echo '<th class="text-center">'.$contador_falhas.'</th>';		
						echo '<th class="text-center">'.$contador_total_com_falhas.'</th>';		
						echo '<th class="text-center">'.$contador_total_sem_falhas.'</th>';		
					echo '</tr>';
				echo "
				</tfoot> ";

			echo '
			</table>';

			echo "<hr>";
			
			echo "</div>";
			echo "<div class=\"col-md-6 col-md-offset-3\" style=\"padding: 0\">";

		foreach ($array_pesquisas as $titulo_pesquisa => $dados) {

			echo '
			<table class="table table-hover dataTable" style="margin-bottom:0;">
				  <thead>
				  	<tr>
						<th colspan="4" class="text-center"><h3>'.$titulo_pesquisa.'</h3><br>Quantidade de Perguntas: '.$dados['perguntas'].'</th>
					</tr>
		        	<tr>
		           		<th class="text-left col-md-3">Nome</th>
		            	<th class="text-center col-md-3">Entrevistas</th>
		            	<th class="text-center col-md-3">Falhas</th>
		            	<th class="text-center col-md-3">Total</th>
		        	</tr>
		      	</thead>
		      	<tbody>';     
				 
				$contador_entrevistas = 0;
				$contador_falhas = 0;
				$contador_total_com_falhas = 0;
				
				foreach ($dados as $nome_atendente => $conteudo) {
					if($nome_atendente != 'perguntas'){

						echo "<tr>";
							echo "<td class='text-left'>" . $nome_atendente . "</td>";
							echo "<td class='text-center'>" . $conteudo['total_entrevistas'] . "</td>";
							echo "<td class='text-center'>" . $conteudo['falhas_total'] . "</td>";
							echo "<td class='text-center'>" . $conteudo['total_com_falhas'] . "</td>";
						echo "</tr>";
						
						$contador_entrevistas = $contador_entrevistas + $conteudo['total_entrevistas'];
						$contador_falhas = $contador_falhas + $conteudo['falhas_total'];
						$contador_total_com_falhas = $contador_total_com_falhas + $conteudo['total_com_falhas'];
					}
				}
					echo "
					</tbody>";
					echo "
					<tfoot>";
							
						echo '<tr>';
							echo '<th class="text-left">Totais</th>';
							echo '<th class="text-center">'.$contador_entrevistas.'</th>';		
							echo '<th class="text-center">'.$contador_falhas.'</th>';		
							echo '<th class="text-center">'.$contador_total_com_falhas.'</th>';		
						echo '</tr>';
					echo "
					</tfoot> ";

				echo '
				</table>';
				echo "<hr>";
		}

		echo "
				<script>
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

function relatorio_tabela($data_de, $data_ate, $pesquisa){
	
	$empresa = DBRead('', 'tb_pesquisa a', "INNER JOIN tb_contrato_plano_pessoa b ON a.id_contrato_plano_pessoa = b.id_contrato_plano_pessoa INNER JOIN tb_pessoa c ON b.id_pessoa = c.id_pessoa WHERE a.id_pesquisa = $pesquisa ", "c.nome, a.titulo, a.id_pesquisa, b.qtd_contratada");

	//Tirei esta parte, quando não tinha contatos retornava vazio
    //INNER JOIN tb_contatos_pesquisa d ON a.id_pesquisa = d.id_pesquisa

	$titulo_empresa = $empresa[0]['titulo'];

    $data_hora = converteDataHora(getDataHora());

	$nome_empresa = $empresa[0]['nome'];
    if ($data_de && $data_ate) {
        $periodo_amostra = "<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> De $data_de até $data_ate</span>";
    }
    	
    	$data_ate = converteData($data_ate);
	    $data_de = converteData($data_de);

    $contatos_pesquisa = DBRead('', 'tb_entrevista_pesquisa a', "INNER JOIN tb_contatos_pesquisa b ON a.id_contatos_pesquisa = b.id_contatos_pesquisa WHERE a.id_pesquisa = '".$pesquisa."' AND b.data_ultimo_contato BETWEEN '".$data_de." 00:00:00' AND '".$data_ate." 23:59:59' GROUP BY a.id_contatos_pesquisa","a.id_contatos_pesquisa");

    $dados_pesquisa = DBRead('', 'tb_pesquisa a', "WHERE id_pesquisa = '".$pesquisa."'");

    echo "<div class=\"col-md-12\" style=\"padding: 0\">";
    echo "<legend style=\"text-align:center;\"><strong>Relatório de Pesquisas - Tabela</strong><br><span style=\"font-size: 14px;\"><strong>Gerado em:</strong> $data_hora</span></legend>";
    echo "<legend style=\"text-align:center;\">$periodo_amostra</legend>";
    echo "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong> Contrato - </strong>".$nome_empresa." - ".$titulo_empresa."</strong></legend>";

	$nome_empresa = strtoupper($nome_empresa);

    if($contatos_pesquisa){
	    
		echo "
			<div style=\"overflow-x:auto;\">
				<table class=\"table table-hover dataTable\"> 
					<thead> 
						<tr> 
	    					<th>Contato</th>
	    					<th>Telefone</th>
	    					<th>Data e hora da entrevista <span class='badge' title=></span></th>";
	    					if($dados_pesquisa[0]['dado1']){
	    						echo "<th>".$dados_pesquisa[0]['dado1']."</th>";
	    					}
	    					if($dados_pesquisa[0]['dado2']){
	    						echo "<th>".$dados_pesquisa[0]['dado2']."</th>";
	    					}
	    					if($dados_pesquisa[0]['dado3']){
	    						echo "<th>".$dados_pesquisa[0]['dado3']."</th>";
	    					}

	    					$perguntas = DBRead('', 'tb_pergunta_pesquisa', "WHERE id_pesquisa = $pesquisa AND status != '0'");
						    
						    if($perguntas){
						    	 foreach ($perguntas as $pergunta) {
							    	echo "<th>".$pergunta['descricao']."</th>";
		    					}
						    }
						   
	    					echo "
						</tr>
					</thead> 
					<tbody>";

			foreach ($contatos_pesquisa as $contato_pesquisa) {

		    	$nomes = DBRead('', 'tb_contatos_pesquisa', "WHERE id_contatos_pesquisa = '".$contato_pesquisa['id_contatos_pesquisa']."'","nome, data_ultimo_contato,dado1, dado2, dado3, telefone");
		   		$nome = $nomes[0]['nome'];

		    	$data_ultimo_contato = $nomes[0]['data_ultimo_contato'];

		    	echo '<tr>
				    	<td>'.$nome.'</td>
				    	<td><span class="phone">'. $nomes[0]['telefone']. '</span></td>
					 	<td>'.converteDataHora($data_ultimo_contato).'</td>';

					 	if($dados_pesquisa[0]['dado1']){
					 		if($nomes[0]['dado1']){
    							echo "<td>".$nomes[0]['dado1']."</td>";
					 		}else{
    							echo "<td>N/D</td>";
					 		}
    					}
    					if($dados_pesquisa[0]['dado2']){
    						if($nomes[0]['dado2']){
    							echo "<td>".$nomes[0]['dado2']."</td>";
					 		}else{
    							echo "<td>N/D</td>";
					 		}
    					}
    					if($dados_pesquisa[0]['dado3']){
    						if($nomes[0]['dado3']){
    							echo "<td>".$nomes[0]['dado3']."</td>";
					 		}else{
    							echo "<td>N/D</td>";
					 		}
    					}
	  	
		    	$perguntas = DBRead('', 'tb_pergunta_pesquisa', "WHERE id_pesquisa = $pesquisa AND status != '0'");
		    	if($perguntas){
				    foreach ($perguntas as $pergunta) {
						$respostas = DBRead('', 'tb_entrevista_pesquisa', "WHERE id_pergunta_pesquisa = '".$pergunta['id_pergunta_pesquisa']."' AND id_contatos_pesquisa = '".$contato_pesquisa['id_contatos_pesquisa']."'");
						if($respostas){
							foreach ($respostas as $resposta) {
					    		if($resposta['resposta']){
					    			echo '<td>'.$resposta['resposta'];

					    			if($resposta['obs_entrevista']){
					    				echo '<br>Obs: '.$resposta['obs_entrevista'];
					    			}
					    			echo '</td>';
					    		}else{
									echo "<td> N/D </td>";
								}
					    	}    
						}else{
							echo "<td> N/D </td>";

				    	}
					}
			   	}
			}

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
								filename: 'relatorio_pesquisa (".$nome_empresa.")',
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

			echo '</tr>';
			echo "
					</tbody> 
				</table>
			</div>
			";
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

/*
  * Função que gera dados para o faturamento de determinada pesquisa.
*/
function relatorio_faturamento($pesquisa, $data_de, $data_ate){

    $data_hora = converteDataHora(getDataHora());

    if ($data_de && $data_ate) {
        $periodo_amostra = "<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> De $data_de até $data_ate</span>";
	}

    $data_consulta = converteDataHora($data_ate)." 23:59:59";
	
	if($pesquisa){
		$dados_empresa = DBRead('', 'tb_pesquisa a', "INNER JOIN tb_contrato_plano_pessoa b ON a.id_contrato_plano_pessoa = b.id_contrato_plano_pessoa INNER JOIN tb_pessoa c ON b.id_pessoa = c.id_pessoa WHERE a.id_pesquisa = $pesquisa AND b.data_atualizacao <= '".$data_consulta."'", "c.nome, a.titulo, a.id_pesquisa, b.qtd_contratada, a.qtd_tentativas_cliente");

		$nome_empresa = $dados_empresa[0]['nome'];
		$titulo_empresa = $dados_empresa[0]['titulo'];
		$legenda_pesquisa = $nome_empresa." - ".$titulo_empresa;
		$filtro_pesquisa = " AND a.id_pesquisa = '".$pesquisa."' ";
	}else{
		$legenda_pesquisa = 'Todas';
	}

    $data_ate = converteData($data_ate);
	$data_de = converteData($data_de);
	
	$dados_empresa = DBRead('', 'tb_pesquisa a', "INNER JOIN tb_contrato_plano_pessoa b ON a.id_contrato_plano_pessoa = b.id_contrato_plano_pessoa INNER JOIN tb_pessoa c ON b.id_pessoa = c.id_pessoa INNER JOIN tb_contatos_pesquisa d ON a.id_pesquisa = d.id_pesquisa WHERE d.data_ultimo_contato BETWEEN '".$data_de." 00:00:00' AND '".$data_ate." 23:59:59' ".$filtro_pesquisa." ORDER BY c.nome", "DISTINCT (a.id_contrato_plano_pessoa), c.nome, a.titulo, a.id_pesquisa, b.qtd_contratada, a.qtd_tentativas_cliente"); 

	echo "<div class=\"col-md-6 col-md-offset-3\" style=\"padding: 0\">";
    echo "<legend style=\"text-align:center;\"><strong>Relatório de Pesquisas - Faturamento</strong><br><span style=\"font-size: 14px;\"><strong>Gerado em:</strong> $data_hora</span></legend>";
    echo "<legend style=\"text-align:center;\">$periodo_amostra</legend>";
    echo "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong> Contrato - </strong>".$legenda_pesquisa."</legend>";

	if($dados_empresa){
		$total_qtd_contratada = 0;
		$total_entrevistas_total = 0;
		$total_qtd_falhas_completas = 0;
		$total_falha_eliminatoria = 0;
		$total_total = 0;
		$total_excedente = 0;
		foreach ($dados_empresa as $conteudo_empresa) {

			$dados_pergunta_pesquisa = DBRead('', 'tb_pergunta_pesquisa', "WHERE id_pesquisa = '".$conteudo_empresa['id_pesquisa']."' AND status = 1", "COUNT(id_pergunta_pesquisa) as cont");
			if($dados_pergunta_pesquisa){
				$contador_perguntas = $dados_pergunta_pesquisa[0]['cont'];
			}else{
				$contador_perguntas = 0;
			}

			//contador de entrevistas concluidas com sucesso.
			$entrevistas = DBRead('', 'tb_contatos_pesquisa', "WHERE id_pesquisa = '".$conteudo_empresa['id_pesquisa']."' AND status_pesquisa = 1 AND data_ultimo_contato BETWEEN '".$data_de." 00:00:00' AND '".$data_ate." 23:59:59' GROUP BY id_pesquisa", "COUNT(*) AS cont, id_pesquisa");

			//Contém somente falhas do tipo "Não ligar novamente".
			$falha_eliminatoria = DBRead('', 'tb_falhas_pesquisa a', "INNER JOIN tb_contatos_pesquisa b  ON a.id_contatos_pesquisa = b.id_contatos_pesquisa WHERE a.falha = 8 AND a.id_pesquisa = '".$conteudo_empresa['id_pesquisa']."' AND b.data_ultimo_contato BETWEEN '".$data_de." 00:00:00' AND '".$data_ate." 23:59:59'", "COUNT(*) AS cont_falhas_eliminatorias");

			//Calculo que faz número de falhas do plano serem faturadas como se fosse uma ligação bem sucedida.
			$falhas = DBRead('', 'tb_falhas_pesquisa a', "INNER JOIN tb_contatos_pesquisa b  ON a.id_contatos_pesquisa = b.id_contatos_pesquisa WHERE a.falha != 8 AND a.id_pesquisa = '".$conteudo_empresa['id_pesquisa']."' AND b.data_ultimo_contato BETWEEN '".$data_de." 00:00:00' AND '".$data_ate." 23:59:59' GROUP BY a.id_contatos_pesquisa HAVING COUNT(*) >= '".$dados_empresa[0]['qtd_tentativas_cliente']."' ", "COUNT(*) AS total");
			$qtd_falhas_completas = 0;
			
			//if( ($entrevistas && $entrevistas[0]['cont'] != 0 ) || ($falhas && $falhas[0]['total'] != 0 ) || ($falha_eliminatoria && $falha_eliminatoria[0]['cont_falhas_eliminatorias'] != 0 ) ){

			if( ($entrevistas) || ($falhas) || ($falha_eliminatoria) ){
				if($falhas){
					foreach($falhas as $falha){
						if($falha['total'] == $dados_empresa[0]['qtd_tentativas_cliente']){
							$qtd_falhas_completas++;
						}
					}
				}

				echo "<div class='col-md-12'>";
				echo "<table class='table table-hover table-striped'>";
					echo "<thead>";
						echo "<tr>";
							echo "<th colspan='6' class='text-center'><h4>".$conteudo_empresa['nome']."</h4>".$conteudo_empresa['titulo']." (".$contador_perguntas." perguntas)</th>";
						echo "</tr>";
						echo "<tr>";
							echo "<th class='text-center'>Qtd. contratada</th>";
							echo "<th class='text-center'>Entrevistas</th>";
							echo "<th class='text-center'>Falhas completas</th>";
							echo "<th class='text-center'>Falhas eliminatórias</th>";
							echo "<th class='text-center'>Total</th>";
							echo "<th class='text-center'>Excedente</th>";
						echo "</tr>";
					echo "</thead>";
					echo "<tbody>";

						$qtd_contratada = $dados_empresa[0]['qtd_contratada'];

						$entrevistas_total = $entrevistas[0]['cont'];
						$falha_eliminatoria = $falha_eliminatoria[0]['cont_falhas_eliminatorias'];
						$total = (int)$entrevistas_total + (int)$falha_eliminatoria + $qtd_falhas_completas;

						//Verifica se o total é maior que a quantidade contratada, se sim, subtrai de total a quantidade contratada e retorna o que foi excedente; senão retorna excedente igual a zero.
						if($total > (int)$qtd_contratada){
							$excedente = $total - (int)$qtd_contratada;
						}else{
							$excedente = 0;
						}

						echo "<tr>";
							echo "<td class='text-center'>" . $qtd_contratada . "</td>";
							echo "<td class='text-center'>" . $entrevistas_total . "</td>";
							echo "<td class='text-center'>" . $qtd_falhas_completas . "</td>";
							echo "<td class='text-center'>" . $falha_eliminatoria . "</td>";
							echo "<td class='text-center'>" . $total . "</td>";
							echo "<td class='text-center'>" . $excedente . "</td>";
						echo "</tr>";
					echo "</tbody>";
				echo "</table>";
				echo "<br>";
				echo "<hr>";
				echo "</div>";

				$total_qtd_contratada += $qtd_contratada;
				$total_entrevistas_total += $entrevistas_total;
				$total_qtd_falhas_completas += $qtd_falhas_completas;
				$total_falha_eliminatoria += $falha_eliminatoria;
				$total_total += $total;
				$total_excedente += $excedente;
			}			
		}

		echo "<div class='col-md-12'>";
			echo "<table class='table table-hover table-striped'>";
				echo "<thead>";
					echo "<tr>";
						echo "<th colspan='6' class='text-center'><h4><strong>Totais</strong></h4></th>";
					echo "</tr>";
					echo "<tr>";
						echo "<th class='text-center info'>Qtd. contratada</th>";
						echo "<th class='text-center info'>Entrevistas</th>";
						echo "<th class='text-center info'>Falhas completas</th>";
						echo "<th class='text-center info'>Falhas eliminatórias</th>";
						echo "<th class='text-center info'>Total</th>";
						echo "<th class='text-center info'>Excedente</th>";
					echo "</tr>";
				echo "</thead>";
				echo "<tbody>";
					echo "<tr>";
						echo "<td class='text-center'>" . $total_qtd_contratada . "</td>";
						echo "<td class='text-center'>" . $total_entrevistas_total . "</td>";
						echo "<td class='text-center'>" . $total_qtd_falhas_completas . "</td>";
						echo "<td class='text-center'>" . $total_falha_eliminatoria . "</td>";
						echo "<td class='text-center'>" . $total_total . "</td>";
						echo "<td class='text-center'>" . $total_excedente . "</td>";
					echo "</tr>";
				echo "</tbody>";
			echo "</table>";
			echo "<br>";
		echo "</div>";
	
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
	echo "</div>";
}
/*
 * Função que gera relatórios referentes ao número de atendimentos concluidos com sucesso, falhas e o total dos dois de cada atendente.
*/

function relatorio_contagem_atendentes($pesquisa, $data_de, $data_ate, $usuario){

    $empresa = DBRead('', 'tb_pesquisa a', "INNER JOIN tb_contrato_plano_pessoa b ON a.id_contrato_plano_pessoa = b.id_contrato_plano_pessoa INNER JOIN tb_pessoa c ON b.id_pessoa = c.id_pessoa WHERE a.id_pesquisa = $pesquisa ", "c.nome, a.titulo, a.id_pesquisa, b.qtd_contratada, a.qtd_tentativas_cliente");

    //Tirei esta parte, quando não tinha contatos retornava vazio
    //INNER JOIN tb_contatos_pesquisa d ON a.id_pesquisa = d.id_pesquisa

	$titulo_empresa = $empresa[0]['titulo'];

    $data_hora = converteDataHora(getDataHora());

	$nome_empresa = $empresa[0]['nome'];
    if ($data_de && $data_ate) {
        $periodo_amostra = "<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> De $data_de até $data_ate</span>";
    }
    echo "<div class=\"col-md-6 col-md-offset-3\" style=\"padding: 0\">";
    echo "<legend style=\"text-align:center;\"><strong>Relatório de Pesquisas - Contagem por Atendentes (Pesquisa)</strong><br><span style=\"font-size: 14px;\"><strong>Gerado em:</strong> $data_hora</span></legend>";
    echo "<legend style=\"text-align:center;\">$periodo_amostra</legend>";
    echo "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong> Pesquisa - </strong>".$nome_empresa." - ".$titulo_empresa."</legend>";

    $data_ate = converteData($data_ate);
    $data_de = converteData($data_de);

    if(!$usuario){
		$filtro = "";
	}else{
		$filtro = "AND a.id_usuario = '$usuario'";
	}
	
    $pesquisas = DBRead('', 'tb_entrevista_pesquisa a', "INNER JOIN tb_contatos_pesquisa b ON a.id_contatos_pesquisa = b.id_contatos_pesquisa INNER JOIN tb_pesquisa c ON a.id_pesquisa = c.id_pesquisa WHERE c.id_pesquisa = '$pesquisa' $filtro AND b.status_pesquisa = '1' AND b.data_ultimo_contato BETWEEN '".$data_de." 00:00:00' AND '".$data_ate." 23:59:59' GROUP BY b.id_contatos_pesquisa, a.id_usuario", "b.id_contatos_pesquisa, b.nome, b.telefone, b.data_ultimo_contato");

    if($pesquisas){
				
		$dados_pergunta_pesquisa = DBRead('', 'tb_pergunta_pesquisa', "WHERE id_pesquisa = '".$pesquisa."' AND status = 1", "COUNT(id_pergunta_pesquisa) as cont");
		if($dados_pergunta_pesquisa){
			$contador_perguntas = $dados_pergunta_pesquisa[0]['cont'];
		}else{
			$contador_perguntas = 0;
		}
	    	
    	echo '<table class="table table-hover dataTable" style="margin-bottom:0;">
			  <thead>
			  	<tr>
					<th colspan="4" class="text-center">Quantidade de Perguntas: '.$contador_perguntas.'</th>
				</tr>
		        <tr>
		            <th class="text-left col-md-3">Nome</th>
		            <th class="text-left col-md-3">Entrevistas</th>
		            <th class="text-left col-md-3">Falhas</th>
		            <th class="text-left col-md-3">Total</th>
		        </tr>
		      </thead>
		      <tbody>';     


	    if(!$usuario){
	        $filtro_usuario = "";
	    }else{
	        $filtro_usuario = " AND a.id_usuario = '$usuario'";
	    }
	    //Filtro caso não exita falhas
	    $filtro_falha = DBRead('', 'tb_falhas_pesquisa', "where id_pesquisa = '".$pesquisa."'");
	    if($filtro_falha){
	    	$filtro_falha = "INNER JOIN tb_falhas_pesquisa e ON b.id_pesquisa = e.id_pesquisa";
	    }else{
	    	$filtro_falha = "";
	    }

	    //Busca por nome cada atendente que trabalhou na pesquisa em questão
	    $atendentes = DBRead('', 'tb_usuario a', "INNER JOIN tb_entrevista_pesquisa b ON a.id_usuario = b.id_usuario INNER JOIN tb_pessoa d ON a.id_pessoa = d.id_pessoa $filtro_falha WHERE b.id_pesquisa = '".$pesquisa."' $filtro_usuario GROUP BY d.nome, a.id_usuario", "d.nome, a.id_usuario");

	    $contador_entrevistas = 0;
        $contador_falhas = 0;
        $contador_total = 0;
		
	    foreach($atendentes as $atendente){

	    	//Contador para as falhas de cada atendente
	    	$falhas = DBRead('', 'tb_falhas_pesquisa', "WHERE id_pesquisa = '$pesquisa' AND falha != 8 AND id_usuario = '".$atendente['id_usuario']."' AND data_falha >= '".$data_de." 00:00:00' AND data_falha <= '".$data_ate." 23:59:59'", "COUNT(*) AS total");

	    	//Contador para as entrevistas concluidas sem falha de cada atendente
	    	$entrevistas = DBRead('', 'tb_entrevista_pesquisa a', "INNER JOIN tb_contatos_pesquisa b ON a.id_contatos_pesquisa = b.id_contatos_pesquisa where b.status_pesquisa = 1 and b.data_ultimo_contato >= '".$data_de." 00:00:00' AND b.data_ultimo_contato <= '".$data_ate." 23:59:59' AND a.id_pesquisa = '$pesquisa' AND a.id_usuario = '".$atendente['id_usuario']."' GROUP BY a.id_pergunta_pesquisa", "COUNT(a.id_pergunta_pesquisa) AS total_entrevistas");
	    	
	    	//Total de ligações efetuadas por cada atendente
	    	$total = $entrevistas[0]['total_entrevistas'] + $falhas[0]['total'];

	    	if($total != 0){

	    		$total_entrevistas = !$entrevistas[0]['total_entrevistas'] ? '0' : $entrevistas[0]['total_entrevistas'];

	    		echo "<tr>";
				    echo "<td class='text-left'>" . $atendente['nome'] . "</td>";
				    echo "<td class='text-left'>" . $total_entrevistas . "</td>";
				    echo "<td class='text-left'>" . $falhas[0]['total'] . "</td>";
				    echo "<td class='text-left'>" . $total . "</td>";
			    echo "</tr>";

			    $contador_entrevistas = $contador_entrevistas + $total_entrevistas;
		        $contador_falhas = $contador_falhas + $falhas[0]['total'];
		        $contador_total = $contador_total + $total;
	    	}
	    }

	    echo "</tbody>";
	    echo "<tfoot>";
					
					echo '<tr>';
						echo '<th>Totais</th>';
						echo '<th>'.$contador_entrevistas.'</th>';		
						echo '<th>'.$contador_falhas.'</th>';		
						echo '<th>'.$contador_total.'</th>';		
					echo '</tr>';

				echo "</tfoot> ";
		echo '</table>';
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

/*
	 * Função que gera relatórios com uma listagem de todos os atendimentos feitos por determinado atendente em determinada pesquisa.
	 * Revisar esse relatório
 */
function relatorio_entrevistas($data_de, $data_ate, $pesquisa, $usuario){

	$empresa = DBRead('', 'tb_pesquisa a', "INNER JOIN tb_contrato_plano_pessoa b ON a.id_contrato_plano_pessoa = b.id_contrato_plano_pessoa INNER JOIN tb_pessoa c ON b.id_pessoa = c.id_pessoa WHERE a.id_pesquisa = $pesquisa ", "c.nome, a.titulo, a.id_pesquisa, b.qtd_contratada, a.dado1, a.dado2, a.dado3");

	////Tirei esta parte, quando não tinha contatos retornava vazio
    //INNER JOIN tb_contatos_pesquisa d ON a.id_pesquisa = d.id_pesquisa
	$titulo_empresa = $empresa[0]['titulo'];
    $data_hora = converteDataHora(getDataHora());

	$nome_empresa = $empresa[0]['nome'];
	if($usuario){
		$nome_usuario = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_usuario = '".$usuario."'", "b.nome");

		$usuario_legenda = $nome_usuario[0]['nome'];
	}else{
		$usuario_legenda = 'Todos';
	}
    if($data_de && $data_ate){
        $periodo_amostra = "<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> De $data_de até $data_ate</span>";
    }
    echo "<div class=\"col-md-10 col-md-offset-1\" style=\"padding: 0\">";
    echo "<legend style=\"text-align:center;\"><strong>Relatório de Pesquisas - Entrevistas</strong><br><span style=\"font-size: 14px;\"><strong>Gerado em:</strong> $data_hora</span></legend>";
    echo "<legend style=\"text-align:center;\">$periodo_amostra</legend>";
    echo "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong> Contrato - </strong>".$nome_empresa." - ".$titulo_empresa.", <strong> Atendente - </strong>".$usuario_legenda."</legend>";

    $data_de = converteDataHora($data_de) . ' 00:00:00';
	$data_ate = converteDataHora($data_ate) . ' 23:59:59';

	if(!$usuario){
		$filtro = "";
	}else{
		$filtro = "AND a.id_usuario = '$usuario'";
	}
	
	$entrevistas = DBRead('', 'tb_entrevista_pesquisa a', "INNER JOIN tb_contatos_pesquisa b ON a.id_contatos_pesquisa = b.id_contatos_pesquisa INNER JOIN tb_pergunta_pesquisa c ON a.id_pergunta_pesquisa = c.id_pergunta_pesquisa INNER JOIN tb_usuario d ON a.id_usuario = d.id_usuario INNER JOIN tb_pessoa e ON d.id_pessoa = e.id_pessoa WHERE b.data_ultimo_contato BETWEEN '$data_de' AND '$data_ate' AND c.status != 0 AND a.id_pesquisa = '$pesquisa' $filtro GROUP BY b.id_contatos_pesquisa, d.id_usuario", "b.nome, b.telefone, b.data_ultimo_contato, a.id_contatos_pesquisa, d.id_usuario, b.dado1, b.dado2, b.dado3, e.nome AS nome_operador");

	$cont = DBRead('', 'tb_contatos_pesquisa', "WHERE id_pesquisa = '$pesquisa' AND status_pesquisa = 1 AND data_ultimo_contato BETWEEN '$data_de' AND '$data_ate'", "COUNT(*) AS cont");
	if($cont[0]['cont']){
		echo "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong> Total: </strong>".$cont[0]['cont']."</legend>";
	}
    	
	if($entrevistas){
		foreach($entrevistas as $entrevista){
			echo "<br>";
			echo "<ul class='list-group container-entrevista'>";
				echo "<li class='list-group-item'>
						<div class='row'>
							<div class='col-md-4 text-left'>
								<strong style='font-size:16px'>".$entrevista['nome']." - 
									<span class='phone'>".$entrevista['telefone']."</span>
								</strong>
							</div>
							<div class='col-md-4 text-center'>
							    <span>".converteDataHora($entrevista['data_ultimo_contato'])."</span>
							</div>
							<div class='col-md-4 text-right'>
								<strong style='font-size:16px'>
									Atendente: ".$entrevista['nome_operador']."
								</strong>
							</div>
						</div>
					</li>";

				if($entrevista['dado1']){
					echo "<li class='list-group-item'><strong>".$empresa[0]['dado1'].":</strong> ".$entrevista['dado1']."</li>";
				}
				if($entrevista['dado2']){
					echo "<li class='list-group-item'><strong>".$empresa[0]['dado2'].":</strong> ".$entrevista['dado2']."</li>";
				}
				if($entrevista['dado3']){
					echo "<li class='list-group-item'><strong>".$empresa[0]['dado3'].":	</strong> ".$entrevista['dado3']."</li>";
				}

				$entrevistas2 = DBRead('', 'tb_entrevista_pesquisa', "WHERE id_contatos_pesquisa = '".$entrevista['id_contatos_pesquisa']."'");
				foreach($entrevistas2 as $e){

					if($e['obs_entrevista']){
						$obs = "Observação: ".$e['obs_entrevista'];
					}else{
						$obs = "";
					}
					echo "<li class='list-group-item'><strong>".$e['pergunta']."</strong> ".$e['resposta']."<br>".$obs."</li>";
				}									
			echo "</ul>";
			echo "<br>";
		}
		
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

/*
 * Relatório geral com dados referentes a pesquisa.
*/
function relatorio_geral($data_de, $data_ate, $usuario, $pesquisa){
	$data_consulta = converteDataHora($data_ate)." 23:59:59";
    $data_de_filtro = converteDataHora($data_de);
    $data_ate_filtro = converteDataHora($data_ate);

    $pesquisas = DBRead('', 'tb_pesquisa a', "INNER JOIN tb_contrato_plano_pessoa b ON a.id_contrato_plano_pessoa = b.id_contrato_plano_pessoa INNER JOIN tb_pessoa c ON b.id_pessoa = c.id_pessoa WHERE a.id_pesquisa = $pesquisa AND b.data_atualizacao <= '".$data_consulta."'", "c.nome, a.titulo, a.id_pesquisa, b.qtd_contratada, a.qtd_tentativas_cliente");
	
	if(!$pesquisas){
		$pesquisas = DBRead('', 'tb_pesquisa a', "INNER JOIN tb_contrato_plano_pessoa_historico b ON a.id_contrato_plano_pessoa = b.id_contrato_plano_pessoa INNER JOIN tb_pessoa c ON b.id_pessoa = c.id_pessoa WHERE a.id_pesquisa = $pesquisa AND b.data_atualizacao < '".$data_consulta."'", "c.nome, a.titulo, a.id_pesquisa, b.qtd_contratada, a.qtd_tentativas_cliente");
    }

	$titulo_empresa = $pesquisas[0]['titulo'];
    $id_pesquisa = $pesquisas[0]['id_pesquisa'];
    $nome_empresa = $pesquisas[0]['nome'];
    
    if($pesquisas){
    	$qtd_contratada = $pesquisas[0]['qtd_contratada'];
    }else{
    	$qtd_contratada = 0;
    }

    $cont_contato = DBRead('', 'tb_contatos_pesquisa', "WHERE status_pesquisa = 1 AND id_pesquisa = '" . $id_pesquisa . "' AND data_ultimo_contato BETWEEN '" . converteDataHora($data_de) . " 00:00:00' AND '" . converteDataHora($data_ate) . " 23:59:59' GROUP BY id_pesquisa", "COUNT(*) AS cont, id_pesquisa");

    $cont['cont'] = $cont_contato[0]['cont'];

    if(!$cont['cont']){
        $qtd_realizada = '0';
    } else {
        $qtd_realizada = $cont['cont'];
    }
    $falhas = DBRead('', 'tb_falhas_pesquisa', "WHERE id_pesquisa = '" . $id_pesquisa . "' AND data_falha BETWEEN '" . converteDataHora($data_de) . " 00:00:00' AND '" . converteDataHora($data_ate) . " 23:59:59' GROUP BY id_pesquisa", "COUNT(*) AS cont, id_pesquisa");
    $falhas_cont['cont'] = $falhas[0]['cont'];
    if(!$falhas_cont['cont']){
        $qtd_falhas = '0';
    } else {
        $qtd_falhas = $falhas_cont['cont'];
    }
    $data_hora = converteDataHora(getDataHora());
    $dados_usuario = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_usuario = '$usuario'", "a.*, b.nome");
    $nome_usuario = $dados_usuario[0]['nome'];
    $id_asterisk_usuario = $dados_usuario[0]['id_asterisk'];
    $id_ponto_usuario = $dados_usuario[0]['id_ponto'];
    if(!$nome_usuario){
        $nome_usuario = "Todos";
    }
    if($data_de && $data_ate){
        $periodo_amostra = "<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> de $data_de até $data_ate</span>";
    }
    echo "<div class=\"col-md-6 col-md-offset-3\" style=\"padding: 0\">";
    echo "<legend style=\"text-align:center;\"><strong>Relatório de Pesquisas - Geral</strong><br><span style=\"font-size: 14px;\"><strong>Gerado em:</strong> $data_hora</span></legend>";
    echo "<legend style=\"text-align:center;\">$periodo_amostra</legend>";
    echo "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong> Contrato - </strong>".$nome_empresa." - ".$titulo_empresa."</legend>";

 	$falhas_eliminatorias = DBRead('', 'tb_falhas_pesquisa', "WHERE falha = 8 AND id_pesquisa = '" . $id_pesquisa . "' AND data_falha BETWEEN '" . converteDataHora($data_de) . " 00:00:00' AND '" . converteDataHora($data_ate) . " 23:59:59' GROUP BY id_pesquisa", "COUNT(*) AS cont, id_pesquisa");

    if($falhas_eliminatorias && $falhas_eliminatorias != ''){
        $qtd_falhas_eliminatorias = $falhas_eliminatorias[0]['cont'];
    }else{
        $qtd_falhas_eliminatorias = '0';
    }

    $falhas_completas = DBRead('', 'tb_falhas_pesquisa a', "INNER JOIN tb_contatos_pesquisa b ON a.id_contatos_pesquisa = b.id_contatos_pesquisa WHERE a.falha != 8 AND a.id_pesquisa = '" . $id_pesquisa . "' AND b.status_pesquisa = 2 AND b.qtd_tentativas_cliente = '".$pesquisas[0]['qtd_tentativas_cliente']."' AND b.id_contatos_pesquisa NOT IN (SELECT id_contatos_pesquisa FROM tb_falhas_pesquisa WHERE falha = 8 ) AND b.data_ultimo_contato BETWEEN '".$data_de_filtro." 00:00:00' AND '".$data_ate_filtro." 23:59:59' GROUP BY b.id_contatos_pesquisa", "COUNT(*) AS cont");

    if($falhas_completas && $falhas_completas != ''){
    	$qtd_falhas_completas = sizeof($falhas_completas);
    }else{
    	$qtd_falhas_completas = '0';
    }

    echo "
		<table class=\"table table-hover\"> 
			<thead> 
				<tr>
    				<th class='text-center'>Qtd. Contratada
    				<th class='text-center'>Qtd. Entrevistado</th>
    				<th class='text-center'>Qtd. Falhas</th>
    				<th class='text-center'>Qtd. Falhas Eliminatórias</th>
    				<th class='text-center'>Qtd. Falhas Completas</th>
				</tr>
			</thead> 
			<tbody>";
    echo '
	    <tr>
			<td class="text-center">' . $qtd_contratada . '</td>
			<td class="text-center">' . $qtd_realizada . '</td>
			<td class="text-center">' . $qtd_falhas . '</td>
			<td class="text-center">' . $qtd_falhas_eliminatorias . '</td>
			<td class="text-center">' . $qtd_falhas_completas . '</td>
		</tr>
		';
    echo "
				</tbody>
			</table>
		";
	
    $perguntas_pesquisa = DBRead('', 'tb_pergunta_pesquisa', "WHERE id_pesquisa = '$pesquisa' AND status != 0");
    
    if($cont_contato){
    	echo "<div class='row' id='graficos'>";
    				
    	foreach($perguntas_pesquisa as $pergunta){
    		if($pergunta['id_tipo_resposta_pesquisa'] != 1){
    			
    			echo "<hr>";
    			echo "<div id='container-".$pergunta['id_pergunta_pesquisa']."' style='overflow: initial !important; min-width: 310px; max-width: 600px; margin: 0px auto;'>";
    			echo "</div>";
    		}
    	}

		echo "</div>";

    }
    

    foreach($perguntas_pesquisa as $pergunta){

    	$respostas = DBRead('', 'tb_resposta_pesquisa', "WHERE id_pergunta_pesquisa = '".$pergunta['id_pergunta_pesquisa']."'");

    	if($pergunta['id_tipo_resposta_pesquisa'] == 2 || $pergunta['id_tipo_resposta_pesquisa'] == 4){

	?>

    	<script>
    		// Make monochrome colors
			var pieColors = (function () {
			    var colors = [],
			        base = Highcharts.getOptions().colors[3],
			        i;

			    for (i = 0; i < 10; i += 1) {
			        // Start out with a darkened base color (negative brighten), and end
			        // up with a much brighter color
			        colors.push(Highcharts.Color(base).brighten((i - 5) / 4).get());
			    }
			    return colors;
			}());
			
			Highcharts.chart('container-' + <?= $pergunta['id_pergunta_pesquisa'] ?>, {
				chart: {
				    plotBackgroundColor: null,
				    plotBorderWidth: null,
				    plotShadow: false,
				    type: 'pie'
				},
				title: {
				    text: '<?= preg_replace('/\s/',' ',$pergunta['descricao']); ?>'
				},
				
				<?php
				if($pergunta['id_tipo_resposta_pesquisa'] == 4){
					$opcoes = DBRead('', 'tb_resposta_pesquisa', "WHERE id_pergunta_pesquisa = '".$pergunta['id_pergunta_pesquisa']."'"); 
				    $contador = 0;
				    $soma = 0;
				    foreach ($opcoes as $opcao):

				    	$respostas = DBRead('', 'tb_entrevista_pesquisa a', "INNER JOIN tb_contatos_pesquisa b ON a.id_contatos_pesquisa = b.id_contatos_pesquisa WHERE a.id_pergunta_pesquisa = '".$pergunta['id_pergunta_pesquisa']."' AND a.resposta = '".$opcao['descricao']."' AND b.data_ultimo_contato BETWEEN '" . converteDataHora($data_de) . " 00:00:00' AND '" . converteDataHora($data_ate) . " 23:59:59'  group by a.resposta","COUNT(resposta) as cont, resposta");

				    		if($respostas){
								$contador = $contador + (int)$respostas[0]['cont'];
					    		$soma = $soma + ((int)$respostas[0]['resposta'] * (int)$respostas[0]['cont']);
				    		}
				    		
				    	
				    	
					endforeach;
					$media = $soma/($contador == 0 ? 1 : $contador);
					$media = number_format($media, 2);
					
				?>	
					subtitle: {
				    	text: 'Média: <?= $media ?>'
				    },
				<?php 
				}
				
				?>
				tooltip: {
				    pointFormat: '{series.name} : <b>{point.percentage:.1f}%</b>, '
				},
				 legend: {
                    enabled: true //desabilita a legenda. Setar para true se quiser habilitar a legenda
                },
                <?php

				    
				    
				    $opcoes = DBRead('', 'tb_resposta_pesquisa', "WHERE id_pergunta_pesquisa = '".$pergunta['id_pergunta_pesquisa']."'"); 
				    
				    foreach ($opcoes as $opcao):

				    	$respostas = DBRead('', 'tb_entrevista_pesquisa a', "INNER JOIN tb_contatos_pesquisa b ON a.id_contatos_pesquisa = b.id_contatos_pesquisa WHERE a.id_pergunta_pesquisa = '".$pergunta['id_pergunta_pesquisa']."' AND a.resposta = '".$opcao['descricao']."' AND b.data_ultimo_contato BETWEEN '" . converteDataHora($data_de) . " 00:00:00' AND '" . converteDataHora($data_ate) . " 23:59:59'  group by a.resposta","COUNT(resposta) as cont, resposta");
				    	if($respostas){
				    		$qtd = $respostas[0]['cont'];
				    	}else{
				    		$qtd = 0;
				    	}
				    	

					    ?>
					  
				plotOptions: {
				      pie: {
				            allowPointSelect: true,
				            cursor: 'pointer',
				            dataLabels: {
				                enabled: true,
				                format: '<b>{point.y}</b> ({point.percentage:.1f}%)',
				               
				            }
				        }    
				},
				<?php

					endforeach;
				    	
				    	?>
				series: [
				    {
				    name: 'Seleção Única',
				    colorByPoint: true,
				    data: [
				    <?php
				    
				    $opcoes = DBRead('', 'tb_resposta_pesquisa', "WHERE id_pergunta_pesquisa = '".$pergunta['id_pergunta_pesquisa']."'"); 
				    
				    foreach ($opcoes as $opcao):

				    	$respostas = DBRead('', 'tb_entrevista_pesquisa a', "INNER JOIN tb_contatos_pesquisa b ON a.id_contatos_pesquisa = b.id_contatos_pesquisa WHERE a.id_pergunta_pesquisa = '".$pergunta['id_pergunta_pesquisa']."' AND a.resposta = '".$opcao['descricao']."' AND b.data_ultimo_contato BETWEEN '" . converteDataHora($data_de) . " 00:00:00' AND '" . converteDataHora($data_ate) . " 23:59:59'  group by a.resposta","COUNT(resposta) as cont, resposta");
				    	if($respostas){
				    		$qtd = $respostas[0]['cont'];
				    	}else{
				    		$qtd = 0;
				    	}
				    	?>

						    {
						        name: '<?= $opcao['descricao'] ?>',
						        y: <?= $qtd ?>
						    },

					    <?php

					endforeach;
				    	
				    	?>
				    	
				    ],
				    showInLegend: true
				}
				]
			});
			
		</script>

		<?php

    	}else if($pergunta['id_tipo_resposta_pesquisa'] == 3){
    		$categories = DBRead('', 'tb_resposta_pesquisa', "WHERE id_pergunta_pesquisa = '".$pergunta['id_pergunta_pesquisa']."'");

    		$cont = DBRead('', 'tb_resposta_pesquisa a', "INNER JOIN  tb_entrevista_pesquisa b ON a.id_pergunta_pesquisa = b.id_pergunta_pesquisa WHERE b.id_pergunta_pesquisa = '".$respostas['id_pergunta_pesquisa']."'", "COUNT(resposta) AS cont");

			?>
    		<script>
    			Highcharts.chart('container-' + <?= $pergunta['id_pergunta_pesquisa'] ?>, {
			    chart: {
			        type: 'column'
			    },
			    title: {
			        text: '<?= $pergunta['descricao'] ?>'
			    },
			    xAxis: {
			        type: 'category'
			    },
			    yAxis: {
			        title: {
			            text: ''
			        }
			    },
			    legend: {
			        enabled: false
			    },
			    plotOptions: {
			        series: {
			            borderWidth: 0,
			            dataLabels: {
			                enabled: true,
			                format: '{point.y:.2f}%'
			            }
			        }
			    },

			    tooltip: {
			        headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
			        pointFormat: '<span style="color:{point.color}"></span>: <b>{point.y:.2f}%'
			    },

			    "series": [
			        {
			            "name": "Seleção Multipla",
			            "colorByPoint": true,
			            "data": [
			            	<?php
			            	$opcoes = DBRead('', 'tb_resposta_pesquisa', "WHERE id_pergunta_pesquisa = '".$pergunta['id_pergunta_pesquisa']."'"); 
				    
				    			foreach ($opcoes as $opcao):

				    				$filtro = "%".$opcao['descricao']."%";

				    				$respostas = DBRead('', 'tb_entrevista_pesquisa', "WHERE id_pergunta_pesquisa = '".$pergunta['id_pergunta_pesquisa']."' and resposta LIKE '".$filtro."' group by id_pergunta_pesquisa","COUNT(id_pergunta_pesquisa) as cont");


				    				$respostas = DBRead('', 'tb_entrevista_pesquisa a', "INNER JOIN tb_contatos_pesquisa b ON a.id_contatos_pesquisa = b.id_contatos_pesquisa WHERE a.id_pergunta_pesquisa = '".$pergunta['id_pergunta_pesquisa']."' AND a.resposta LIKE '".$filtro."' AND b.data_ultimo_contato BETWEEN '" . converteDataHora($data_de) . " 00:00:00' AND '" . converteDataHora($data_ate) . " 23:59:59'  group by id_pergunta_pesquisa","COUNT(id_pergunta_pesquisa) as cont");

				    				$total = DBRead('', 'tb_entrevista_pesquisa', "WHERE id_pergunta_pesquisa = '".$pergunta['id_pergunta_pesquisa']."' order by id_pergunta_pesquisa","COUNT(id_pergunta_pesquisa) as cont");

				    				if($respostas){
				    					
				    					$qtd = ($respostas[0]['cont']*100) /($total[0]['cont'] == 0 ? 1 : $total[0]['cont']);
				    					$contador = $respostas[0]['cont'];

							    	}else{
							    		$qtd = 0;
							    		$contador = 0;
							    	}
							    	?>
									    {
									        name: '<?= $opcao['descricao']." - Respostas: <strong>".$contador."</strong>" ?>',
									        y: <?= $qtd ?>
									    },
				                <?php
								endforeach;
							    
							    ?>
			            ]
			        }
			    ]
			});
			</script>
		<?php
    	}
    }
}

/*
 * Função que gera relatórios de falhas individuais.
*/
function relatorio_falhas($data_de, $data_ate, $pesquisa) {
    
    $data_de_filtro = converteDataHora($data_de);
    $data_ate_filtro = converteDataHora($data_ate);

    $pesquisas = DBRead('', 'tb_pesquisa a', "INNER JOIN tb_contrato_plano_pessoa b ON a.id_contrato_plano_pessoa = b.id_contrato_plano_pessoa INNER JOIN tb_pessoa c ON b.id_pessoa = c.id_pessoa WHERE a.id_pesquisa = $pesquisa", "c.nome, a.titulo, a.id_pesquisa, b.qtd_contratada");
    
    //Tirei esta parte, quando não tinha contatos retornava vazio
    //INNER JOIN tb_contatos_pesquisa d ON a.id_pesquisa = d.id_pesquisa

    $titulo_empresa = $pesquisas[0]['titulo'];
    $nome_empresa = $pesquisas[0]['nome'];
    $id_pesquisa = $pesquisas[0]['id_pesquisa'];
    $falhas = DBRead('', 'tb_falhas_pesquisa', "WHERE id_pesquisa = '" . $id_pesquisa . "' AND data_falha BETWEEN '$data_de_filtro 00:00:00' AND '$data_ate_filtro 23:59:59' GROUP BY id_pesquisa", "COUNT(*) AS cont, id_pesquisa");    
    $falhas_cont['cont'] = $falhas[0]['cont'];
    
    $falhas = DBRead('', 'tb_falhas_pesquisa a', "INNER JOIN tb_contatos_pesquisa b ON a.id_contatos_pesquisa = b.id_contatos_pesquisa WHERE a.id_pesquisa = '" . $id_pesquisa . "' AND a.data_falha BETWEEN '$data_de_filtro 00:00:00' AND '$data_ate_filtro 23:59:59'");

    $data_hora = converteDataHora(getDataHora());
    $dados_usuario = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE id_usuario = '$usuario'", "a.*, b.nome");
    $nome_usuario = $dados_usuario[0]['nome'];
    $id_asterisk_usuario = $dados_usuario[0]['id_asterisk'];
    $id_ponto_usuario = $dados_usuario[0]['id_ponto'];
    if (! $nome_usuario) {
        $nome_usuario = "Todos";
    }
    if ($data_de && $data_ate) {
        $periodo_amostra = "<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> De $data_de até $data_ate</span>";
    }
    echo "<div class=\"col-md-12\" style=\"padding: 0\">";
    echo "<legend style=\"text-align:center;\"><strong>Relatório de Pesquisas - Falhas</strong><br><span style=\"font-size: 14px;\"><strong>Gerado em:</strong> $data_hora</span></legend>";
    echo "<legend style=\"text-align:center;\">$periodo_amostra</legend>";
    echo "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong> Contrato - </strong>".$nome_empresa." - ".$titulo_empresa."</legend>";

    if ($falhas) {
    	echo "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong> Total: </strong>".$falhas_cont['cont']."</legend>";
		
		$qtd_falha = array();
		$qtd_atendentes = array();
    	foreach ($falhas as $falha) {
	        $pessoa = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_usuario = '" . $falha['id_usuario'] . "'");
	        $data_falha = converteDataHora($falha['data_falha']);

	        if ($falha['falha'] == 1) {
	            $tipo_falha = "Chamou até cair";
		        } else if ($falha['falha'] == 2) {
		            $tipo_falha = "Número não existe";
		        } else if ($falha['falha'] == 3) {
		            $tipo_falha = "Número errado";
		        } else if ($falha['falha'] == 4) {
		            $tipo_falha = "Caixa postal/fora de área";
		        } else if ($falha['falha'] == 5) {
		            $tipo_falha = "Número ocupado";
		        } else if ($falha['falha'] == 6) {
		            $tipo_falha = "Ligação caiu";
		        } else if ($falha['falha'] == 7) {
		            $tipo_falha = "Ligar mais tarde";
		        } else if ($falha['falha'] == 8) {
		            $tipo_falha = "<span class='text-danger'>Não ligar novamente</span>";
		        }else if ($falha['falha'] == 9) {
		            $tipo_falha = "Chamada com interferência";
		        }else if ($falha['falha'] == 10) {
		            $tipo_falha = "Contato solicitou para ligar em outro horário, porém era a última tentativa.";
		        }

	        $qtd_falha[$tipo_falha] += 1;
			$qtd_atendentes[$pessoa[0]['nome']] += 1;
			
			$dados_tabela[] = array(
				'data' => $data_falha,
				'nome' => $falha['nome'],
				'telefone' => $falha['telefone'],
				'tipo_de_falha' => $tipo_falha,
				'atendente' => $pessoa[0]['nome']
			);
	    }
	echo "<div class = 'row'>";

		echo "<div class = 'col-md-12'>";
			echo '
			<table class="table table-hover dataTable" style="margin-bottom:0;">
		      <thead>
		        <tr>
		            <th class="text-left  col-md-3">Nome</th>
			        <th class="text-left  col-md-2">Telefone</th>
		            <th class="text-left  col-md-3">Tipo de Falha</th>
		            <th class="text-left  col-md-3">Atendente</th>
		            <th class="text-left  col-md-1">Data e Hora</th>
		        </tr>
		      </thead>
			  <tbody>'; 

			  foreach($dados_tabela as $conteudo_tabela){
				echo '<tr>
			            <td class="text-left">'.$conteudo_tabela['nome'].'</td>    
			            <td class="text-left"><span class ="phone">' . $conteudo_tabela['telefone'] . '</span></td>
			            <td class="text-left">'.$conteudo_tabela['tipo_de_falha'].'</td>
			            <td class="text-left">'.$conteudo_tabela['atendente'].'</td>
			            <td class="text-left">'.$conteudo_tabela['data'].'</td>
					</tr>';
			  }
			  
				echo "</tbody>
			</table>";

		echo "</div>";

		echo "<script>
				$(document).ready(function(){
					var table = $('.dataTable').DataTable({
						\"language\": {
							\"url\": \"//cdn.datatables.net/plug-ins/1.10.19/i18n/Portuguese-Brasil.json\"
						},
						aaSorting: [[4, 'asc']],
						columnDefs: [
							{ type: 'chinese-string', targets: 0 },
						],				        
						\"searching\": false,
						\"paging\":   false,
						\"info\":     false
					});

					var buttons = new $.fn.dataTable.Buttons(table, {
						buttons: [
							{
								extend: 'excelHtml5',
								text: '<i class=\"fa fa-file-excel-o\" aria-hidden=\"true\"></i> Exportar',
								filename: 'relatorio_pesquisa_falhas',
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

		echo "<div class = 'col-md-6'>";
				echo '
				<table class="table table-hover dataTable2" style="margin-bottom:0;">
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
					echo '<td>'.$falha.'</td>';
					echo '<td>'.$qtd.'</td>';
					echo '</tr>';     
					}
			    echo '</tbody>
			</table>';  

		echo "</div>";
		
		echo "<div class = 'col-md-6'>";
			echo '
				<table class="table table-hover dataTable2" style="margin-bottom:0;">
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
				    echo '<td>'.$atendente.'</td>';
				    echo '<td>'.$qtd.'</td>';
				    echo '</tr>';     
				  }
		          echo '</tbody>
			</table>';
		echo "</div>";
	echo "</div>";

        echo '
    	<script>
		    $(document).ready(function(){
		        $(".dataTable2").DataTable({
		            "language": {
		                "url": "//cdn.datatables.net/plug-ins/1.10.19/i18n/Portuguese-Brasil.json"
		            },
		            "searching": false,
		            "paging":   false,
		            "info":     false
		        });
		    });
		</script>';


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

/*Relatorio de falhas eleiminatórias*/
function relatorio_falha_eliminatoria($data_de, $data_ate, $pesquisa) {
    
    $data_de_filtro = converteDataHora($data_de);
    $data_ate_filtro = converteDataHora($data_ate);

    $pesquisas = DBRead('', 'tb_pesquisa a', "INNER JOIN tb_contrato_plano_pessoa b ON a.id_contrato_plano_pessoa = b.id_contrato_plano_pessoa INNER JOIN tb_pessoa c ON b.id_pessoa = c.id_pessoa WHERE a.id_pesquisa = $pesquisa", "c.nome, a.titulo, a.id_pesquisa, b.qtd_contratada");
    
    //Tirei esta parte, quando não tinha contatos retornava vazio
    //INNER JOIN tb_contatos_pesquisa d ON a.id_pesquisa = d.id_pesquisa

    $titulo_empresa = $pesquisas[0]['titulo'];
    $nome_empresa = $pesquisas[0]['nome'];
    $id_pesquisa = $pesquisas[0]['id_pesquisa'];
    $falhas = DBRead('', 'tb_falhas_pesquisa', "WHERE falha = 8 AND id_pesquisa = '" . $id_pesquisa . "' AND data_falha BETWEEN '$data_de_filtro 00:00:00' AND '$data_ate_filtro 23:59:59' GROUP BY id_pesquisa", "COUNT(*) AS cont, id_pesquisa");
    $falhas_cont = $falhas[0]['cont'];

    $falhas = DBRead('', 'tb_falhas_pesquisa a', "INNER JOIN tb_contatos_pesquisa b ON a.id_contatos_pesquisa = b.id_contatos_pesquisa WHERE a.falha = 8 AND a.id_pesquisa = '" . $id_pesquisa . "' AND a.data_falha BETWEEN '$data_de_filtro 00:00:00' AND '$data_ate_filtro 23:59:59'");

    $data_hora = converteDataHora(getDataHora());
  
    if ($data_de && $data_ate) {
        $periodo_amostra = "<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> De $data_de até $data_ate</span>";
    }
    	
	echo "<div class=\"col-md-12\" style=\"padding: 0\">";
    echo "<legend style=\"text-align:center;\"><strong>Relatório de Pesquisas - Falhas Eliminatórias</strong><br><span style=\"font-size: 14px;\"><strong>Gerado em:</strong> $data_hora</span></legend>";
    echo "<legend style=\"text-align:center;\">$periodo_amostra</legend>";
    echo "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong> Contrato - </strong>".$nome_empresa." - ".$titulo_empresa."</legend>";

    if ($falhas) {
    	echo "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong> Total: </strong>".$falhas_cont."</legend>";
    } 
    
    if($falhas){
		echo "<table class='table table-hover dataTable'>";
		echo "
			<thead>
				<tr>
					<th class='col-md-3'> Assinante</th>
					<th class='col-md-1'> Telefone</th>
					<th class='col-md-2'> Tipo de falha</th>
					<th class='col-md-2'> Observação</th>
					<th class='col-md-2'> Atendente</th>
					<th class='col-md-2'> Data e hora</th>
				</tr>
			</thead>
			<tbody>";

	    foreach ($falhas as $falha) {
	        $pessoa = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_usuario = '" . $falha['id_usuario'] . "'");
	        $data_falha = $falha['data_falha'];
	        $data_falha = converteDataHora($data_falha);

	        $tipo_falha = "Não ligar novamente";
			if($falha['obs_falha']){
				$obs = $falha['obs_falha'];
			}else{
				$obs = '';
			}
				echo "
				<tr>
					<td data-order='".$falha['nome']."".$falha['id_contatos_pesquisa']."'>".$falha['nome']."</td>
					<td><span class = 'phone'>".$falha['telefone']."</span></td>
					<td>" . $tipo_falha . "</td>
					<td>" . $obs . "</td>
					<td>" . $pessoa[0]['nome'] . "</td>
					<td>" . $data_falha . "</td>
				</tr>";
		}
			echo "
			</tbody>";
		echo "
		</table>";
				
		echo "
		<script>
			$(document).ready(function(){
				var table = $('.dataTable').DataTable({
					\"language\": {
						\"url\": \"//cdn.datatables.net/plug-ins/1.10.19/i18n/Portuguese-Brasil.json\"
					},
					aaSorting: [[0, 'asc']],
					columnDefs: [
						{ type: 'chinese-string', targets: 0 },
					],				        
					\"searching\": false,
					\"paging\":   false,
					\"info\":     false
				});

				var buttons = new $.fn.dataTable.Buttons(table, {
					buttons: [
						{
							extend: 'excelHtml5',
							text: '<i class=\"fa fa-file-excel-o\" aria-hidden=\"true\"></i> Exportar',
							filename: 'relatorio_pesquisa (".$nome_empresa.")',
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

/*
 * Relatório com contatos que falharam em todas as tentativas efetuadas pelo atendente.
*/
function relatorio_falhas_completas($data_de, $data_ate, $pesquisa){
    
    $data_de_filtro = converteDataHora($data_de);
    $data_ate_filtro = converteDataHora($data_ate);
       
	$pesquisas = DBRead('', 'tb_pesquisa a', "INNER JOIN tb_contrato_plano_pessoa b ON a.id_contrato_plano_pessoa = b.id_contrato_plano_pessoa INNER JOIN tb_pessoa c ON b.id_pessoa = c.id_pessoa WHERE a.id_pesquisa = '$pesquisa'", "c.nome, a.titulo, a.id_pesquisa, b.qtd_contratada, a.qtd_tentativas_cliente");

	//Tirei esta parte, quando não tinha contatos retornava vazio
	//INNER JOIN tb_contatos_pesquisa d ON a.id_pesquisa = d.id_pesquisa

	$titulo_empresa = $pesquisas[0]['titulo'];
	$nome_empresa = $pesquisas[0]['nome'];
	$id_pesquisa = $pesquisas[0]['id_pesquisa'];
	$qtd_tentativas_cliente = $pesquisas[0]['qtd_tentativas_cliente'];
	
	$falhas = DBRead('', 'tb_falhas_pesquisa a', "INNER JOIN tb_contatos_pesquisa b ON a.id_contatos_pesquisa = b.id_contatos_pesquisa WHERE a.falha != 8 AND a.id_pesquisa = '" . $id_pesquisa . "' AND b.status_pesquisa = 2 AND b.qtd_tentativas_cliente = '".$pesquisas[0]['qtd_tentativas_cliente']."' AND b.id_contatos_pesquisa NOT IN (SELECT id_contatos_pesquisa FROM tb_falhas_pesquisa WHERE falha = 8 ) AND b.data_ultimo_contato BETWEEN '".$data_de_filtro." 00:00:00' AND '".$data_ate_filtro." 23:59:59' GROUP BY b.id_contatos_pesquisa", "COUNT(*) AS cont");
	
	$falhas_cont = 0;
	if($falhas){
		foreach ($falhas as $falha) {
			$falhas_cont = (int)$falhas_cont + (int)$falha['cont'];
		}
	}
		
	$falhas = DBRead('', 'tb_falhas_pesquisa a', "INNER JOIN tb_contatos_pesquisa b ON a.id_contatos_pesquisa = b.id_contatos_pesquisa WHERE a.falha != 8 AND a.id_pesquisa = '" . $id_pesquisa . "' AND b.status_pesquisa = 2 AND b.qtd_tentativas_cliente = '".$pesquisas[0]['qtd_tentativas_cliente']."' AND b.id_contatos_pesquisa NOT IN (SELECT id_contatos_pesquisa FROM tb_falhas_pesquisa WHERE falha = 8 ) AND b.data_ultimo_contato BETWEEN '".$data_de_filtro." 00:00:00' AND '".$data_ate_filtro." 23:59:59' ORDER BY b.nome ASC");

	$data_hora = converteDataHora(getDataHora());
	$dados_usuario = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE id_usuario = '$usuario'", "a.*, b.nome");
	$nome_usuario = $dados_usuario[0]['nome'];
	if(! $nome_usuario){
		$nome_usuario = "Todos";
	}
	if($data_de && $data_ate){
		$periodo_amostra = "<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> De $data_de até $data_ate</span>";
	}
	echo "<div class=\"col-md-6 col-md-offset-3\" style=\"padding: 0\">";
	echo "<legend style=\"text-align:center;\"><strong>Relatório de Pesquisas - Falhas Completas</strong><br><span style=\"font-size: 14px;\"><strong>Gerado em:</strong> $data_hora</span></legend>";
	echo "<legend style=\"text-align:center;\">$periodo_amostra</legend>";
	echo "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong> Contrato - </strong>".$nome_empresa." - ".$titulo_empresa."</legend>";

	if ($falhas) {
		echo "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong> Total: </strong>".(int)$falhas_cont/(int)$qtd_tentativas_cliente."</legend>";
	} 
	
	if($falhas){

		foreach ($falhas as $falha) {
			$pessoa = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_usuario = '" . $falha['id_usuario'] . "'");
			$data_falha = $falha['data_falha'];
			$data_falha = converteDataHora($data_falha);
			if ($falha['falha'] == 1) {
				$tipo_falha = "Chamou até cair";
			} else if ($falha['falha'] == 2) {
				$tipo_falha = "Número não existe";
			} else if ($falha['falha'] == 3) {
				$tipo_falha = "Número errado";
			} else if ($falha['falha'] == 4) {
				$tipo_falha = "Caixa postal/fora de área";
			} else if ($falha['falha'] == 5) {
				$tipo_falha = "Número ocupado";
			} else if ($falha['falha'] == 6) {
				$tipo_falha = "Ligação caiu";
			} else if ($falha['falha'] == 7) {
				$tipo_falha = "Ligar mais tarde";
			} else if ($falha['falha'] == 8) {
				$tipo_falha = "Não ligar novamente";
			}else if ($falha['falha'] == 9) {
				$tipo_falha = "Chamada com interferência";
			}else if ($falha['falha'] == 10) {
				$tipo_falha = "Contato solicitou para ligar em outro horário, porém era a última tentativa.";
			}
			echo "
				<ul class='list-group'>
				<li class='list-group-item'><strong>Nome: " . $falha['nome'] . "</strong></li>
				<li class='list-group-item'><strong>Telefone: </strong><span class = 'phone'>" . $falha['telefone'] . "</span></li>
				<li class='list-group-item'><strong>Tipo de falha: </strong>" . $tipo_falha . "</li>
				<li class='list-group-item'><strong>Atendente: </strong>" . $pessoa[0]['nome'] . "</li>
				<li class='list-group-item'><strong>Data e hora: </strong>" . $data_falha . "</li>
				</ul>";
		}
        echo "</div>";
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

/*
 * Relatório de todos os agendamentos de determinada pesquisa.
*/

function relatorio_agendamentos($data_de, $data_ate, $pesquisa, $situacao_agendamento) {
    
	if($situacao_agendamento == 1){
		$legenda_situacao_agendamento = 'Entrevistado';
		$filtro_situacao_agendamento = " AND a.status_agendamento = '1' ";
	}else if($situacao_agendamento == 2){
		$legenda_situacao_agendamento = 'Em Aberto';
		$filtro_situacao_agendamento = " AND a.status_agendamento = '0' ";
	}else{
		$legenda_situacao_agendamento = 'Qualquer';
		$filtro_situacao_agendamento = "";
	}

    $data_de_filtro = converteDataHora($data_de);
    $data_ate_filtro = converteDataHora($data_ate);

    $pesquisas = DBRead('', 'tb_pesquisa a', "INNER JOIN tb_contrato_plano_pessoa b ON a.id_contrato_plano_pessoa = b.id_contrato_plano_pessoa INNER JOIN tb_pessoa c ON b.id_pessoa = c.id_pessoa WHERE a.id_pesquisa = $pesquisa", "c.nome, a.titulo, a.id_pesquisa, b.qtd_contratada");
    $titulo_empresa = $pesquisas[0]['titulo'];
    $nome_empresa = $pesquisas[0]['nome'];
    $id_pesquisa = $pesquisas[0]['id_pesquisa'];

    $agendamentos = DBRead('', 'tb_agendamento_pesquisa a', "INNER JOIN tb_contatos_pesquisa b ON a.id_contatos_pesquisa = b.id_contatos_pesquisa WHERE b.id_pesquisa = '".$id_pesquisa."' AND b.data_ultimo_contato BETWEEN '".$data_de_filtro." 00:00:00' AND '".$data_ate_filtro." 23:59:59' ".$filtro_situacao_agendamento." ","a.*, b.*, b.telefone AS telefone1, a.telefone AS telefone2");

    if($agendamentos){
    	$agendamentos_cont = sizeof($agendamentos);
    }
    
	$data_hora = converteDataHora(getDataHora());
	if ($data_de && $data_ate) {
		$periodo_amostra = "<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> De $data_de até $data_ate</span>";
	}
	
	echo "<div class=\"col-md-6 col-md-offset-3\" style=\"padding: 0\">";
	echo "<legend style=\"text-align:center;\"><strong>Relatório de Pesquisas - Agendamentos</strong><br><span style=\"font-size: 14px;\"><strong>Gerado em:</strong> $data_hora</span></legend>";
	echo "<legend style=\"text-align:center;\">$periodo_amostra</legend>";
	echo "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong> Contrato - </strong>".$nome_empresa." - ".$titulo_empresa.",<strong> Situação - </strong>".$legenda_situacao_agendamento."</legend>";

	if ($agendamentos) {
		echo "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong> Total: </strong>".$agendamentos_cont."</legend>";
	} 

	if($agendamentos_cont){
	    foreach ($agendamentos as $agendamento) {
	    	if($agendamento['telefone2']){
				$filtro = "para o número: <span class='phone'>".$agendamento['telefone2']. "</span>";
	    	}else{
	    		$filtro = "";
	    	}
	        $agendamento_telefone = $agendamento['telefone1'];
	        $agendamento_nome = $agendamento['nome'];
	        $agendamento_data_hora = $agendamento['data_hora'];
	        $agendamento_retorno = $agendamento['data_retorno'];
	        
	        if ($agendamento['status_agendamento'] == 1) {
	            $status = " <span style='color:#088A08;'>Entrevistado</span>";
	        } else {
	            $status = " <span style='color:#B22222;'>Em Aberto</span>";
	        }
	        echo "
				<ul class='list-group'>
					<li class='list-group-item'><strong>Nome: " . $agendamento_nome . "</strong></li>
					<li class='list-group-item'><strong>Telefone: </strong><span class='phone'>". $agendamento_telefone . "</span></li>
					<li class='list-group-item'><strong>Situação: </strong>" . $status . "</li>
					<li class='list-group-item'><strong>Ligar novamente em: </strong>" . converteDataHora($agendamento_data_hora)." ".$filtro."</li>";

					if($agendamento_retorno && $agendamento_retorno){
						echo "
						<li class='list-group-item'><strong>Data e hora do retorno: </strong>" . converteDataHora($agendamento_retorno) . "</li>";
					}
					echo "
				</ul>";
	    }
	    echo "</div>";
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

/*
 * Relatório de notas de atendimentos referente a pesquisa em questão, esse relatório só é gerado em pesquisa com pergunta com o campo do tipo "Escala Linear", gerando uma média aritmética com os valores. 
*/
function relatorio_notas($data_de, $data_ate, $pesquisa) {

	$data_de_filtro = converteDataHora($data_de);
	$data_ate_filtro = converteDataHora($data_ate);
	
	$empresa = DBRead('', 'tb_pesquisa a', "INNER JOIN tb_contrato_plano_pessoa b ON a.id_contrato_plano_pessoa = b.id_contrato_plano_pessoa INNER JOIN tb_pessoa c ON b.id_pessoa = c.id_pessoa INNER JOIN tb_contatos_pesquisa d ON a.id_pesquisa = d.id_pesquisa WHERE a.id_pesquisa = $pesquisa ", "c.nome, a.titulo, a.id_pesquisa, b.qtd_contratada");
 	
 	$titulo_empresa = $empresa[0]['titulo'];


    $data_hora = converteDataHora(getDataHora());

	$nome_empresa = $empresa[0]['nome'];
    if ($data_de && $data_ate) {
        $periodo_amostra = "<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> De $data_de até $data_ate</span>";
    }
    echo "<div class=\"col-md-8 col-md-offset-2\" style=\"padding: 0\">";
    echo "<legend style=\"text-align:center;\"><strong>$nome_empresa - $titulo_empresa</strong><br><strong>Relatório de Notas</strong><br><span style=\"font-size: 14px;\"><strong>Gerado em:</strong> $data_hora</span></legend>";
    echo "<legend style=\"text-align:center;\">$periodo_amostra</legend>";

    $pesquisas = DBRead('', 'tb_entrevista_pesquisa a', "INNER JOIN tb_contatos_pesquisa b ON a.id_contatos_pesquisa = b.id_contatos_pesquisa INNER JOIN tb_pergunta_pesquisa c ON a.id_pergunta_pesquisa = c.id_pergunta_pesquisa WHERE a.id_pesquisa = '$pesquisa' AND b.data_ultimo_contato BETWEEN '$data_de_filtro 00:00:00' AND '$data_ate_filtro 23:59:59' AND c.id_tipo_resposta_pesquisa = 4 AND c.status != 0");

    $total = count($pesquisas);

    if($pesquisas){
		$soma = 0;

	    echo "<div class='col-md-10 col-md-offset-1'>";

	    echo "<table class='table table-bordered'>";
	    echo "<thead>";
	    echo "<tr>";
	    echo "<td class='col-md-3'>Nome</td>";
	    echo "<td class='col-md-2'>Telefone</td>";
	    echo "<td class='col-md-1'>Nota</td>";
	    echo "<td class='col-md-4'>Sugestão</td>";
	    echo "<td class='col-md-2'>Hora do atendimento";
	    echo "</tr>";
	    echo "</thead>";
	    echo "<tbody>";
	    foreach ($pesquisas as $pesquisa) {
	        $soma = $soma + $pesquisa['resposta'];
	        echo "<tr>";
	        echo "<td>" . $pesquisa['nome'] . "</td>";
	        echo "<td class='phone'>" . $pesquisa['telefone'] . "</td>";
	        echo "<td>" . $pesquisa['resposta'] . "</td>";
	        echo "<td>" . $pesquisa['observacao'] . "</td>";
	        echo "<td>" . converteDataHora($pesquisa['data_ultimo_contato']) . "</td>";
	        echo "</tr>";
	    }
	    $media = $soma /($total == 0 ? 1 : $total);
	    $media = number_format($media, 2);
	    echo "</tbody>";
	    echo "<tfoot>";
	    echo "<tr>";
	    echo "<th></th>";
	    echo "<th></th>";
	    echo "<th>Média: " . $media . "</th>";
	    echo "<th></th>";
	    echo "<th></th>";
	    echo "</tr>";
	    echo "</tfoot>";
	    echo "</table>";

	    echo "</div>";
	}else{

		echo "<div class='col-md-10 col-md-offset-1'>";
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

function relatorio_contatos($data_de, $data_ate, $pesquisa, $status, $tipo_consulta){
	
	if ($data_de && $data_ate) {
        $periodo_amostra = "<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> De $data_de até $data_ate</span>";
    }
    	
	$data_ate = converteData($data_ate);
    $data_de = converteData($data_de);

	if($status){
		if($status == '7'){
			$status = '0';
		}
		$consulta_status = "AND status_pesquisa = '".$status."' ";

		if($status == 0){
			$legenda_status = "Em aberto";
		}else if($status == 1){
			$legenda_status = "Entrevista realizada com sucesso";
		}else if($status == 2){
			$legenda_status = "Todas as tentativas resultaram em falhas";
		}else if($status == 3){
			$legenda_status = "Ligação efetuada mas o cliente não quis participar da entrevista";
		}
	}else{
		$legenda_status = "Qualquer";
	}

	if($tipo_consulta){
		if($tipo_consulta == 1){
			$legenda_tipo_consulta = "Data do cadastro";
			$consulta_tipo_consulta = "AND data_inclusao BETWEEN '".$data_de." 00:00:00' AND '".$data_ate." 23:59:59'";
		}else if($tipo_consulta == 2){
			$legenda_tipo_consulta = "Data da última entrevista";
			$consulta_tipo_consulta = "AND data_ultimo_contato BETWEEN '".$data_de." 00:00:00' AND '".$data_ate." 23:59:59'"; 
		}
	}else{
		$legenda_tipo_consulta = "Qualquer";
		$consulta_tipo_consulta = "AND ((data_ultimo_contato BETWEEN '".$data_de." 00:00:00' AND '".$data_ate." 23:59:59') OR (data_inclusao BETWEEN '".$data_de." 00:00:00' AND '".$data_ate." 23:59:59'))";
	}

	$empresa = DBRead('', 'tb_pesquisa a', "INNER JOIN tb_contrato_plano_pessoa b ON a.id_contrato_plano_pessoa = b.id_contrato_plano_pessoa INNER JOIN tb_pessoa c ON b.id_pessoa = c.id_pessoa WHERE a.id_pesquisa = $pesquisa ", "c.nome, a.titulo, a.id_pesquisa, b.qtd_contratada");

	$titulo_empresa = $empresa[0]['titulo'];

    $data_hora = converteDataHora(getDataHora());

	$nome_empresa = $empresa[0]['nome'];
    

    $contatos_pesquisa = DBRead('', 'tb_contatos_pesquisa', "WHERE id_pesquisa = '".$pesquisa."' ".$consulta_status." ".$consulta_tipo_consulta." GROUP BY id_contatos_pesquisa");

    $dados_pesquisa = DBRead('', 'tb_pesquisa a', "WHERE id_pesquisa = '".$pesquisa."'");

    echo "<div class=\"col-md-12\" style=\"padding: 0\">";
    echo "<legend style=\"text-align:center;\"><strong>Relatório de Pesquisas - Contatos</strong><br><span style=\"font-size: 14px;\"><strong>Gerado em:</strong> $data_hora</span></legend>";
    echo "<legend style=\"text-align:center;\">$periodo_amostra</legend>";
    echo "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong> Contrato - </strong>".$nome_empresa." - ".$titulo_empresa.", <strong> Status - </strong>".$legenda_status.", <strong> Filtro de Data - </strong>".$legenda_tipo_consulta."</legend>";

	$nome_empresa = strtoupper($nome_empresa);
    if($contatos_pesquisa){
    
		echo "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong> Total: </strong>".sizeof($contatos_pesquisa)."</legend>";
			    
		echo "
			<div style=\"overflow-x:auto;\">
				<table class=\"table table-hover dataTable\"> 
					<thead> 
						<tr> 
	    					<th>Contato</th>
	    					<th>Telefone</th>";
	    					if($dados_pesquisa[0]['dado1']){
	    						echo "<th>".$dados_pesquisa[0]['dado1']."</th>";
	    					}
	    					if($dados_pesquisa[0]['dado2']){
	    						echo "<th>".$dados_pesquisa[0]['dado2']."</th>";
	    					}
	    					if($dados_pesquisa[0]['dado3']){
	    						echo "<th>".$dados_pesquisa[0]['dado3']."</th>";
	    					}
	    					echo "
	    					<th>Data e hora do cadastro <span class='badge' title=></span></th>
	    					<th>Data e hora da última entrevista <span class='badge' title=></span></th>
	    					<th>Qtd. de tentativas<span class='badge' title=></span></th>
	    					<th>Status do contato<span class='badge' title=></span></th>";
	    					echo "
						</tr>
					</thead> 
					<tbody>";

			foreach ($contatos_pesquisa as $contato_pesquisa) {

		    	echo '<tr>
				    	<td>'.$contato_pesquisa['nome'].'</td>
				    	<td><span class="phone">'. $contato_pesquisa['telefone']. '</span></td>';
				    	if($dados_pesquisa[0]['dado1']){
					 		if($contato_pesquisa['dado1']){
    							echo "<td>".$contato_pesquisa['dado1']."</td>";
					 		}else{
    							echo "<td>N/D</td>";
					 		}
    					}
    					if($dados_pesquisa[0]['dado2']){
    						if($contato_pesquisa['dado2']){
    							echo "<td>".$contato_pesquisa['dado2']."</td>";
					 		}else{
    							echo "<td>N/D</td>";
					 		}
    					}
    					if($dados_pesquisa[0]['dado3']){
    						if($contato_pesquisa['dado3']){
    							echo "<td>".$contato_pesquisa['dado3']."</td>";
					 		}else{
    							echo "<td>N/D</td>";
					 		}
    					}
    					echo '<td>'.converteDataHora($contato_pesquisa['data_inclusao']).'</td>';
    					if($contato_pesquisa['data_ultimo_contato'] != '1970-01-01 00:00:00'){
					 		echo '<td>'.converteDataHora($contato_pesquisa['data_ultimo_contato']).'</td>';
    					}else{
					 		echo '<td>N/D</td>';
    					}

					 	$qtd_tentativas_cliente = $contato_pesquisa['qtd_tentativas_cliente'];
					 	if($contato_pesquisa['status_pesquisa'] == 0){
					 		$status = 'Em aberto';
					 	}else if($contato_pesquisa['status_pesquisa'] == 1){
					 		$status = 'Entrevista realizada com sucesso';
					 		if($contato_pesquisa['qtd_tentativas_cliente'] == 0){
					 			$qtd_tentativas_cliente = 1;
					 		}
					 	}else if($contato_pesquisa['status_pesquisa'] == 2){
					 		$status = 'Todas as tentativas resultaram em falhas';
					 	}else if($contato_pesquisa['status_pesquisa'] == 3){
					 		$status = 'Ligação efetuada mas o cliente não quis participar da entrevista';
					 	}

					 	echo '<td>'.$qtd_tentativas_cliente.'</td>';

					 	echo '<td>'.$status.'</td>';
			}

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
								filename: 'relatorio_pesquisa (".$nome_empresa.") - contatos',
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

			echo '</tr>';
			echo "
					</tbody> 
				</table>
				
				<hr>

			</div>
			";
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

function relatorio_contrato($contrato, $data_de, $data_ate, $zerados){

	if ($data_de && $data_ate) {
        $periodo_amostra = "<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> De $data_de até $data_ate</span>";
    }
	$data_ate = converteData($data_ate);
    $data_de = converteData($data_de);

	if($zerados == 1){
		$legenda_zerados = 'Sim';
	}else{
		$legenda_zerados = 'Não';
	}

	if($contrato){
		$empresa = DBRead('', 'tb_pesquisa a', "INNER JOIN tb_contrato_plano_pessoa b ON a.id_contrato_plano_pessoa = b.id_contrato_plano_pessoa INNER JOIN tb_pessoa c ON b.id_pessoa = c.id_pessoa WHERE c.id_pessoa = '".$contrato."' ", "c.nome, a.titulo, a.id_pesquisa, b.qtd_contratada");
		$nome_empresa = $empresa[0]['nome'];
		$filtro_pesquisa = "AND c.id_pessoa = '".$contrato."' ";
	}else{
		$nome_empresa = "Todos";
	}

    $data_hora = converteDataHora(getDataHora());

    $dados_pesquisa_contrato = DBRead('', 'tb_pesquisa a', "INNER JOIN tb_contrato_plano_pessoa b ON a.id_contrato_plano_pessoa = b.id_contrato_plano_pessoa INNER JOIN tb_pessoa c ON b.id_pessoa = c.id_pessoa WHERE a.status != '2' ".$filtro_pesquisa." ","a.*, b.*, c.*, a.status AS status_pesquisa");

    echo "<div class=\"col-md-12\" style=\"padding: 0\">";
    echo "<legend style=\"text-align:center;\"><strong>Relatório de Pesquisas - Sintético por Cliente</strong><br><span style=\"font-size: 14px;\"><strong>Gerado em:</strong> $data_hora</span></legend>";
    echo "<legend style=\"text-align:center;\">$periodo_amostra</legend>";
    echo "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong> Cliente - </strong>".$nome_empresa.",<strong> Mostrar Zerados - </strong>".$legenda_zerados."</legend>";

	$nome_empresa = strtoupper($nome_empresa);
    if($dados_pesquisa_contrato){
		echo "
			<div style=\"overflow-x:auto;\">
				<table class=\"table table-hover table-striped dataTable\"> 
					<thead> 
						<tr> 
							<th>Título</th>
							<th>Contrato</th>
							<th>Status</th>
	    					<th>Data e Hora da Criação</th>
	    					<th>Entrevistas Realizadas</th>
	    					<th>Falhas</th>
	    					<th>Falhas Eliminatórias</th>
	    					<th>Falhas Completas</th>
	    					<th>Contatos Cadastrados</th>
	    					<th>Falhas + Entrevistas Realizadas</th>
	    					<th>Contatos em Aberto</th>
	    					<th>Quantidade de Perguntas</th>
						</tr>
					</thead> 
					<tbody>";

		$contador_realizadas = 0;
		$contador_falhas = 0;
		$contador_falhas_eliminatorias = 0;
		$contador_falhas_completas = 0;
		$contador_qtd_contatos = 0;
		$contador_perguntas = 0;
		$contador_falhas_e_realizadas = 0;
		$contador_contatos_e_realizadas = 0;

		foreach ($dados_pesquisa_contrato as $conteudo_pesquisa_contrato) {

			$empresa = DBRead('', 'tb_pesquisa a', "INNER JOIN tb_contrato_plano_pessoa b ON a.id_contrato_plano_pessoa = b.id_contrato_plano_pessoa INNER JOIN tb_pessoa c ON b.id_pessoa = c.id_pessoa WHERE a.id_pesquisa = '".$conteudo_pesquisa_contrato['id_pesquisa']."' ", "c.nome");
			$nome_empresa = $empresa[0]['nome'];

			$entrevistas = DBRead('', 'tb_contatos_pesquisa', "WHERE id_pesquisa = '".$conteudo_pesquisa_contrato['id_pesquisa']."' AND status_pesquisa = '1' AND data_ultimo_contato >= '".$data_de." 00:00:00' AND data_ultimo_contato <= '".$data_ate." 23:59:59' GROUP BY id_pesquisa", "COUNT(*) AS cont_entrevista");

		    //Contém somente falhas do tipo "Não ligar novamente".
		    $falha_eliminatoria = DBRead('', 'tb_falhas_pesquisa a', "INNER JOIN tb_contatos_pesquisa b  ON a.id_contatos_pesquisa = b.id_contatos_pesquisa WHERE a.falha = 8 AND a.id_pesquisa = '".$conteudo_pesquisa_contrato['id_pesquisa']."' AND b.data_ultimo_contato >= '".$data_de." 00:00:00' AND b.data_ultimo_contato <= '".$data_ate." 23:59:59'", "COUNT(*) AS cont_falhas_eliminatorias");

		    //Contém as falhas completas (falhas do cliente == quantidade de tentativas na configuração da pesquisa)
		    $falhas_completas = DBRead('', 'tb_falhas_pesquisa a', "INNER JOIN tb_contatos_pesquisa b  ON a.id_contatos_pesquisa = b.id_contatos_pesquisa WHERE a.falha != 8 AND a.id_pesquisa = '".$conteudo_pesquisa_contrato['id_pesquisa']."' AND b.data_ultimo_contato >= '".$data_de." 00:00:00' AND b.data_ultimo_contato <= '".$data_ate." 23:59:59' GROUP BY a.id_contatos_pesquisa HAVING COUNT(*) >= '".$conteudo_pesquisa_contrato['qtd_tentativas_cliente']."' ", "COUNT(*) AS falhas_completas");

		    //Todas as falhas
			$falhas = DBRead('', 'tb_falhas_pesquisa', "WHERE id_pesquisa = '".$conteudo_pesquisa_contrato['id_pesquisa']."' AND falha != 8 AND data_falha >= '".$data_de." 00:00:00' AND data_falha <= '".$data_ate." 23:59:59'", "COUNT(*) AS falhas");
			
			//Total Perguntas
			$perguntas = DBRead('', 'tb_pergunta_pesquisa', "WHERE id_pesquisa = '".$conteudo_pesquisa_contrato['id_pesquisa']."' AND status = 1", "COUNT(*) AS contador_perguntas");
			$qtd_perguntas = $perguntas[0]['contador_perguntas'];

			$contatos_pesquisa = DBRead('', 'tb_contatos_pesquisa', "WHERE id_pesquisa = '".$conteudo_pesquisa_contrato['id_pesquisa']."' ", "COUNT(*) AS qtd_contatos");
			if(!$contatos_pesquisa){
		    	$qtd_contatos = 0;
		    }else{
		    	$qtd_contatos = $contatos_pesquisa[0]['qtd_contatos'];
		    }
			
			$qtd_falhas_completas = 0;
		    if($falhas_completas){
		    	foreach($falhas_completas as $conteudo_falhas_completas){
			    	if($conteudo_falhas_completas['falhas_completas'] == $conteudo_pesquisa_contrato['qtd_tentativas_cliente']){
			    		$qtd_falhas_completas++;
			    	}
			    }
		    }
		    
		    if(!$entrevistas){
		    	$qtd_entrevistas = 0;
		    }else{
		    	$qtd_entrevistas = $entrevistas[0]['cont_entrevista'];
		    }

		    if(!$falha_eliminatoria){
		    	$qtd_falhas_eliminatorias = 0;
		    }else{
		    	$qtd_falhas_eliminatorias = $falha_eliminatoria[0]['cont_falhas_eliminatorias'];
		    }

		    if(!$falhas){
		    	$qtd_falhas = 0;
		    }else{
		    	$qtd_falhas = $falhas[0]['falhas'];
		    	//$qtd_falhas = $qtd_falhas - ($qtd_falhas_completas*$conteudo_pesquisa_contrato['qtd_tentativas_cliente']);
		    }

		    if ($conteudo_pesquisa_contrato['status_pesquisa'] == 1){
				$status_pesquisa = "Ativa";
			}else if ($conteudo_pesquisa_contrato['status_pesquisa'] == 3){
				$status_pesquisa = "Pausada";
			}else if ($conteudo_pesquisa_contrato['status_pesquisa'] == 0){
				$status_pesquisa = "Concluída";
			}else if ($conteudo_pesquisa_contrato['status_pesquisa'] == 4){
				$status_pesquisa = "Pausada Automaticamente";
			}

			$falhas_e_realizadas = $qtd_entrevistas+$qtd_falhas_completas+$qtd_falhas_eliminatorias;
			$contatos_e_realizadas = $qtd_contatos-($qtd_entrevistas+$qtd_falhas_completas+$qtd_falhas_eliminatorias);

			if($zerados == '2' && ($qtd_entrevistas != '0' || $qtd_falhas_eliminatorias != '0' || $qtd_falhas != '0')){

				echo '<tr>
				    	<td style="vertical-align: middle;">'.$conteudo_pesquisa_contrato['titulo'].'</td>
				    	<td style="vertical-align: middle;">'.$nome_empresa.'</td>
				    	<td style="vertical-align: middle;">'.$status_pesquisa.'</td>
				    	<td style="vertical-align: middle;">'.converteDataHora($conteudo_pesquisa_contrato['data_criacao']).'</td>
					 	<td style="vertical-align: middle;">'.$qtd_entrevistas.'</td>
					 	<td style="vertical-align: middle;">'.$qtd_falhas.'</td>
					 	<td style="vertical-align: middle;">'.$qtd_falhas_eliminatorias.'</td>
					 	<td style="vertical-align: middle;">'.$qtd_falhas_completas.'</td>
					 	<td style="vertical-align: middle;">'.$qtd_contatos.'</td>
					 	<td style="vertical-align: middle;">'.$falhas_e_realizadas.'</td>
					 	<td style="vertical-align: middle;">'.$contatos_e_realizadas.'</td>
					 	<td style="vertical-align: middle;">'.$qtd_perguntas.'</td>
					</tr>';
					
			}else if($zerados == '1'){
				echo '<tr>
				    	<td style="vertical-align: middle;">'.$conteudo_pesquisa_contrato['titulo'].'</td>
				    	<td style="vertical-align: middle;">'.$nome_empresa.'</td>
				    	<td style="vertical-align: middle;">'.$status_pesquisa.'</td>
				    	<td style="vertical-align: middle;">'.converteDataHora($conteudo_pesquisa_contrato['data_criacao']).'</td>
					 	<td style="vertical-align: middle;">'.$qtd_entrevistas.'</td>
					 	<td style="vertical-align: middle;">'.$qtd_falhas.'</td>
					 	<td style="vertical-align: middle;">'.$qtd_falhas_eliminatorias.'</td>
					 	<td style="vertical-align: middle;">'.$qtd_falhas_completas.'</td>
					 	<td style="vertical-align: middle;">'.$qtd_contatos.'</td>
					 	<td style="vertical-align: middle;">'.$falhas_e_realizadas.'</td>	
					 	<td style="vertical-align: middle;">'.$contatos_e_realizadas.'</td>
					 	<td style="vertical-align: middle;">'.$qtd_perguntas.'</td>
					</tr>';
			}
			$contador_realizadas = $contador_realizadas + $qtd_entrevistas;
			$contador_falhas = $contador_falhas + $qtd_falhas;
			$contador_falhas_eliminatorias = $contador_falhas_eliminatorias + $qtd_falhas_eliminatorias;
			$contador_falhas_completas = $contador_falhas_completas + $qtd_falhas_completas;
			$contador_qtd_contatos = $contador_qtd_contatos + $qtd_contatos;
			$contador_perguntas = $contador_perguntas + $qtd_perguntas;
			$contador_falhas_e_realizadas = $contador_falhas_e_realizadas + $falhas_e_realizadas;
			$contador_contatos_e_realizadas = $contador_contatos_e_realizadas + $contatos_e_realizadas;
	    	
		}
				echo "</tbody>";
				echo "<tfoot>";
					echo '<tr>';
						echo '<th>Totais</th>';
						echo '<th></th>';
						echo '<th></th>';
						echo '<th></th>';
						echo '<th>'.$contador_realizadas.'</th>';
						echo '<th>'.$contador_falhas.'</th>';
						echo '<th>'.$contador_falhas_eliminatorias.'</th>';
						echo '<th>'.$contador_falhas_completas.'</th>';
						echo '<th>'.$contador_qtd_contatos.'</th>';
						echo '<th>'.$contador_falhas_e_realizadas.'</th>';
						echo '<th>'.$contador_contatos_e_realizadas.'</th>';
						echo '<th>'.$contador_perguntas.'</th>';
					echo '</tr>';
				echo "</tfoot> ";
			echo "</table>
		
		<hr>

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
								filename: 'relatorio_pesquisa (".$nome_empresa.") - contatos',
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

?>