<?php
require_once(__DIR__."/../class/System.php");

$servico = (!empty($_POST['servico'])) ? $_POST['servico'] : 1;
$tipo_relatorio = (!empty($_POST['tipo_relatorio'])) ? $_POST['tipo_relatorio'] : '2';
$gerar = (!empty($_POST['gerar'])) ? 1 : 0;
$data_inicial = new DateTime(getDataHora('data'));
$data_inicial->modify('first day of last month');
$referencia = (!empty($_POST['referencia'])) ? $_POST['referencia'] : $data_inicial->format('Y-m-d');
$cancelados = (!empty($_POST['cancelados'])) ? $_POST['cancelados'] : 0;
$duplicados = (!empty($_POST['duplicados'])) ? $_POST['duplicados'] : '';
$tipo_cobranca_select = (!empty($_POST['tipo_cobranca_select'])) ? $_POST['tipo_cobranca_select'] : '';
$tipo_cobranca_redes = (!empty($_POST['tipo_cobranca_redes'])) ? $_POST['tipo_cobranca_redes'] : '';
$ano_hoje = new DateTime(getDataHora('data'));
$ano_hoje = $ano_hoje->format('Y');
$ano_de_referencia = (!empty($_POST['ano_de_referencia'])) ? $_POST['ano_de_referencia'] : $ano_hoje;
$ano_ate_referencia = (!empty($_POST['ano_ate_referencia'])) ? $_POST['ano_ate_referencia'] : $ano_hoje;

if($gerar){
	$collapse = '';
	$collapse_icon = 'plus';
}else{
	$collapse = 'in';
	$collapse_icon = 'minus';
} 

$dados_meses = array(
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

if($tipo_relatorio == 3){
	$display_row_servico = 'style="display:none;"';
	$display_row_cancelados = 'style="display:none;"';
	$display_row_remove_duplicados = 'style="display:none;"';
	$display_row_tipo_cobranca_redes = 'style="display:none;"';
	$display_row_tipo_cobranca = 'style="display:none;"';
	$display_row_data_referencia = 'style="display:none;"';
	$display_row_ano_referencia_totais = '';
}else{
	$display_row_servico = '';
	$display_row_cancelados = '';
	$display_row_data_referencia = '';
	$display_row_ano_referencia_totais = 'style="display:none;"';

	if($servico == 1){
		$display_row_remove_duplicados = '';
		$display_row_tipo_cobranca_redes = 'style="display:none;"';
		$display_row_tipo_cobranca = '';
		$display_row_cancelados = '';
	}else if($servico == 2){
		$display_row_remove_duplicados = 'style="display:none;"';
		$display_row_tipo_cobranca_redes = '';
		$display_row_tipo_cobranca = 'style="display:none;"';
		$display_row_cancelados = '';
	}else if($servico == 3){
		$display_row_remove_duplicados = 'style="display:none;"';
		$display_row_tipo_cobranca_redes = 'style="display:none;"';
		$display_row_tipo_cobranca = '';
		$display_row_cancelados = '';
	}else if($servico == 4){
		$display_row_remove_duplicados = 'style="display:none;"';
		$display_row_tipo_cobranca_redes = 'style="display:none;"';
		$display_row_tipo_cobranca = '';
		$display_row_cancelados = '';
	}else if($servico == 5){
		$display_row_remove_duplicados = 'style="display:none;"';
		$display_row_tipo_cobranca_redes = 'style="display:none;"';
		$display_row_tipo_cobranca = 'style="display:none;"';
		$display_row_cancelados = 'style="display:none;"';
	}
}

?>

<style>
    @media print {
        .noprint { display:none; }
        body {
            font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
            padding-top: 0;
        }
    }
</style>

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
	                    <h3 class="panel-title text-left pull-left" style="margin-top: 2px;">Relatório - Faturamento:</h3>
	                    <div class="panel-title text-right pull-right"><button data-toggle="collapse" data-target="#accordionRelatorio" class="btn btn-xs btn-info" type="button" title="Visualizar filtros"><i id="i_collapse" class="fa fa-<?=$collapse_icon?>"></i></button></div>
	                </div>
	                <div id="accordionRelatorio" class="panel-collapse collapse <?=$collapse?>">
	                	<div class="panel-body">
	                	<div class="row">
								<div class="col-md-12">
									<div class="form-group">
										<label>Tipo de Relatório:</label> 
										<select name="tipo_relatorio" id="tipo_relatorio" class="form-control input-sm">
											<option value="1" <?php if($tipo_relatorio == '1'){echo 'selected';}?>>Detalhado</option>
											<option value="2" <?php if($tipo_relatorio == '2'){echo 'selected';}?>>Tabela</option>
											<option value="3" <?php if($tipo_relatorio == '3'){echo 'selected';}?>>Totais Agrupados por Mês</option>
										</select>
									</div>
								</div>
							</div>	  	
						
							<div class="row" id="row_servico" <?=$display_row_servico?>>
                				<div class="col-md-12">
                					<div class="form-group">
								        <label for="">Serviço:</label>
								        <select name="servico" id="servico" class="form-control input-sm">
											<option value="5" <?php if($servico == '5'){ echo 'selected';}?>>Call Center - Adesões</option>
											<option value="3" <?php if($servico == '3'){ echo 'selected';}?>>Call Center - Ativo</option>
								        	<option value="4" <?php if($servico == '4'){ echo 'selected';}?>>Call Center - Monitoramento</option>
								        	<option value="1" <?php if($servico == '1'){ echo 'selected';}?>>Call Center - Suporte</option>
								        	<option value="2" <?php if($servico == '2'){ echo 'selected';}?>>Gestão de Redes</option>
								        </select>
								    </div>
                				</div>
                			</div>
	                		              		
							<div class="row" id="row_cancelados" <?=$display_row_cancelados?>>
								<div class="col-md-12">
									<div class="form-group">
										<label>Mostrar Cancelados:</label>
                                        <select class="form-control input-sm" id="cancelados" name="cancelados">
                                            <option value="1" <?php if($cancelados == '1'){ echo 'selected';}?>>Sim</option>
                                            <option value="0" <?php if($cancelados != '1'){ echo 'selected';}?>>Não</option>
                                        </select>
									</div>
								</div>
							</div>
							<div class="row" id="row_remove_duplicados" <?=$display_row_remove_duplicados?>>
								<div class="col-md-12">
									<div class="form-group">
										<label>Remove Duplicados:</label>
                                        <select class="form-control input-sm" id="duplicados" name="duplicados">
                                            <option value="" <?php if($duplicados == ''){ echo 'selected';}?>>Qualquer</option>
                                            <option value="1" <?php if($duplicados == '1'){ echo 'selected';}?>>Sim</option>
                                            <option value="2" <?php if($duplicados == '2'){ echo 'selected';}?>>Não</option>
                                        </select>
									</div>
								</div>
							</div>
							<div class="row" id='row_tipo_cobranca' <?=$display_row_tipo_cobranca?>>
								<div class="col-md-12">
									<div class="form-group">
										<label>Tipo de Cobrança</label>
                                        <select class="form-control input-sm" id="tipo_cobranca_select" name="tipo_cobranca_select">
											<option value="">Todos</option>
                                            <option value="anual" <?php if($tipo_cobranca_select == 'anual'){ echo 'selected';}?>>Anual</option>
                                            <option value="cliente" <?php if($tipo_cobranca_select == 'cliente'){ echo 'selected';}?>>Cliente</option>
                                            <option value="mensal" <?php if($tipo_cobranca_select == 'mensal'){ echo 'selected';}?>>Mensal</option>
                                            <option value="mensal_desafogo" <?php if($tipo_cobranca_select == 'mensal_desafogo'){ echo 'selected';}?>>Mensal com Desafogo</option>
                                            <option value="unitario" <?php if($tipo_cobranca_select == 'unitario'){ echo 'selected';}?>>Unitário</option>
                                            <option value="x_cliente_base" <?php if($tipo_cobranca_select == 'x_cliente_base'){ echo 'selected';}?>>Até X Clientes na Base</option>
                                            <option value="prepago" <?php if($tipo_cobranca_select == 'prepago'){ echo 'selected';}?>>Pré Pago</option>
                                        </select>
									</div>
								</div>
							</div>

							<div class="row" id='row_tipo_cobranca_redes' <?=$display_row_tipo_cobranca_redes?>>
								<div class="col-md-12">
									<div class="form-group">
										<label>Tipo de Cobrança</label>
                                        <select class="form-control input-sm" id="tipo_cobranca_redes" name="tipo_cobranca_redes">
											<option value="">Todos</option>
                                            <option value="x_cliente_base" <?php if($tipo_cobranca_redes == 'x_cliente_base'){ echo 'selected';}?>>Até X Clientes na Base</option>
                                            <option value="cliente_ativo" <?php if($tipo_cobranca_redes == 'cliente_ativo'){ echo 'selected';}?>>Clientes Ativos</option>
                                            <option value="cliente_base" <?php if($tipo_cobranca_redes == 'cliente_base'){ echo 'selected';}?>>Clientes na Base</option>
                                            <option value="horas" <?php if($tipo_cobranca_redes == 'horas'){ echo 'selected';}?>>Horas</option>
                                            <option value="ilimitado" <?php if($tipo_cobranca_redes == 'ilimitado'){ echo 'selected';}?>>Ilimitado</option>
                                        </select>
									</div>
								</div>
							</div>

							<div class="row" id="row_data_referencia" <?=$display_row_data_referencia?>>
								<div class="col-md-12">
									<div class="form-group">
										<label>Data de Referência:</label>
										<select name="referencia" class="form-control input-sm">
											<?php
											$dados_referencia = DBRead('', 'tb_faturamento', "WHERE status = 1 GROUP BY data_referencia ORDER BY data_referencia ASC", "data_referencia");

											if ($dados_referencia) {
												foreach ($dados_referencia as $conteudo_referencia) {
													$mes_ano = explode("-", $conteudo_referencia['data_referencia']);
													$mes = $mes_ano[1];
													$ano = $mes_ano[0];
													$selected = $referencia == $conteudo_referencia['data_referencia'] ? "selected" : "";
													echo "<option value='" . $conteudo_referencia['data_referencia'] . "' ".$selected.">" .$dados_meses[$mes]."/".$ano."</option>";
												}
											}
											?>
										</select>
									</div>
								</div>
							</div>

							<div class="row" id="row_ano_referencia_totais" <?=$display_row_ano_referencia_totais?>>
								<div class="col-md-6">
									<div class="form-group">
										<label>Ano Inicial de Referência:</label>
										<select name="ano_de_referencia" class="form-control input-sm">
											<?php
											$dados_referencia = DBRead('', 'tb_faturamento', "WHERE status = 1 GROUP BY data_referencia ORDER BY data_referencia ASC LIMIT 1", "data_referencia");
											$mes_ano = explode("-", $dados_referencia[0]['data_referencia']);
											$ano = $mes_ano[0];
											while($ano <= $ano_hoje){
												$selected = $ano_de_referencia == $ano ? "selected" : "";
												echo "<option value='" . $ano . "' ".$selected.">" .$ano."</option>";
												$ano++;
											}
											?>
										</select>
									</div>
								</div>

								<div class="col-md-6">
									<div class="form-group">
										<label>Ano Final de Referência:</label>
										<select name="ano_ate_referencia" class="form-control input-sm">
											<?php
											$dados_referencia = DBRead('', 'tb_faturamento', "WHERE status = 1 GROUP BY data_referencia ORDER BY data_referencia ASC LIMIT 1", "data_referencia");
											$mes_ano = explode("-", $dados_referencia[0]['data_referencia']);
											$ano = $mes_ano[0];
											while($ano <= $ano_hoje){
												$selected = $ano_ate_referencia == $ano ? "selected" : "";
												echo "<option value='" . $ano . "' ".$selected.">" .$ano."</option>";
												$ano++;
											}
											?>
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
                                <?php if($gerar != 0){
                                	echo '<button class="btn btn-warning" name="imprimir" type="button" onclick="window.print();"><i class="fa fa-print"></i> Imprimir</button>';

                                }?>
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
		if($gerar){
			if($tipo_relatorio == 2){
				if ($servico == 1) {
					relatorio_tabela_call_suporte($referencia, $cancelados, $dados_meses, $tipo_cobranca_select, $duplicados);
				}else if ($servico == 2) {
					relatorio_tabela_gestao_redes($referencia, $cancelados, $dados_meses, $tipo_cobranca_redes);
				}else if ($servico == 3) {
					relatorio_tabela_call_ativo($referencia, $cancelados, $dados_meses, $tipo_cobranca_select);
				}else if ($servico == 4) {
					relatorio_tabela_monitoramento($referencia, $cancelados, $dados_meses, $tipo_cobranca_select);
				}else if ($servico == 5) {
					relatorio_tabela_adesao($referencia, $dados_meses);
				}
			}else if($tipo_relatorio == 1){
				if ($servico == 1) {
					relatorio_detalhado_call_suporte($referencia, $cancelados, $dados_meses, $tipo_cobranca_select, $duplicados);
				}else if ($servico == 2) {
					relatorio_detalhado_gestao_redes($referencia, $cancelados, $dados_meses, $tipo_cobranca_redes);
				}else if ($servico == 3) {
					relatorio_detalhado_call_ativo($referencia, $cancelados, $dados_meses, $tipo_cobranca_select);
				}else if ($servico == 4) {
					relatorio_detalhado_monitoramento($referencia, $cancelados, $dados_meses, $tipo_cobranca_select);
				}else if ($servico == 5) {
					relatorio_detalhado_adesao($referencia, $dados_meses);
				}
			}else if($tipo_relatorio == 3){
				relatorio_agrupado_mes($ano_de_referencia, $ano_ate_referencia);
			}
			
		}
		?>
	</div>
</div>
<script>	
    $('#accordionRelatorio').on('shown.bs.collapse', function(){
       $("#i_collapse").removeClass("fa fa-plus").addClass("fa fa-minus");
    });
    $('#accordionRelatorio').on('hidden.bs.collapse', function(){
       $("#i_collapse").removeClass("fa fa-minus").addClass("fa fa-plus");
    });
    $(document).on('submit', 'form', function(){
		var tipo_relatorio = $('#tipo_relatorio').val();
		if(tipo_relatorio == 3){
			var ano_de_referencia = $('select[name="ano_de_referencia"]').val();
			var ano_ate_referencia = $('select[name="ano_ate_referencia"]').val();
			if(ano_de_referencia > ano_ate_referencia){
				alert('A ano inicial de ser menor ou igual ao ano final!');
				return false;
			}
		}
        modalAguarde();
    });
    $(document).ready(function(){
	    $('#aguarde').hide();
	    $('#resultado').show();
	    $("#gerar").prop("disabled", false);
	});  

	$(function () {
	  $('[data-toggle="tooltip"]').tooltip()
	}) 

	$('#servico').on('change',function(){
		servico = $(this).val();
		if(servico == 1){
			$('#row_remove_duplicados').show();
			$('#row_tipo_cobranca').show();
			$('#row_tipo_cobranca_redes').hide();
			$('#row_cancelados').show();
		}else if(servico == 2){
			$('#row_remove_duplicados').hide();
			$('#row_tipo_cobranca').hide();
			$('#row_tipo_cobranca_redes').show();
			$('#row_cancelados').show();
		}else if(servico == 3){
			$('#row_remove_duplicados').hide();
			$('#row_tipo_cobranca').show();
			$('#row_tipo_cobranca_redes').hide();
			$('#row_cancelados').show();
		}else if(servico == 4){
			$('#row_remove_duplicados').hide();
			$('#row_tipo_cobranca').show();
			$('#row_tipo_cobranca_redes').hide();
			$('#row_cancelados').show();
		}else if(servico == 5){
			$('#row_remove_duplicados').hide();
			$('#row_tipo_cobranca').hide();
			$('#row_tipo_cobranca_redes').hide();
			$('#row_cancelados').hide();

		}
	}); 

	$('#tipo_relatorio').on('change',function(){
		if($(this).val() == 3){
			$('#row_servico').hide();
			$('#row_cancelados').hide();
			$('#row_remove_duplicados').hide();
			$('#row_tipo_cobranca').hide();
			$('#row_tipo_cobranca_redes').hide();
			$('#row_data_referencia').hide();
			$('#row_ano_referencia_totais').show();
		}else{
			servico = $('#servico').val();
			
			$('#row_servico').show();
			$('#row_data_referencia').show();
			$('#row_ano_referencia_totais').hide();

			if(servico == 1){
				$('#row_remove_duplicados').show();
				$('#row_cancelados').show();
				$('#row_tipo_cobranca').show();
				$('#row_tipo_cobranca_redes').hide();
			}else if(servico == 2){
				$('#row_remove_duplicados').hide();
				$('#row_cancelados').show();
				$('#row_tipo_cobranca').hide();
				$('#row_tipo_cobranca_redes').show();
			}else if(servico == 3){
				$('#row_remove_duplicados').hide();
				$('#row_cancelados').show();
				$('#row_tipo_cobranca').show();
				$('#row_tipo_cobranca_redes').hide();
			}else if(servico == 4){
				$('#row_remove_duplicados').hide();
				$('#row_cancelados').show();
				$('#row_tipo_cobranca').show();
				$('#row_tipo_cobranca_redes').hide();
			}else if(servico == 5){
				$('#row_remove_duplicados').hide();
				$('#row_cancelados').hide();
				$('#row_tipo_cobranca').hide();
				$('#row_tipo_cobranca_redes').hide();
			}
		}
	}); 

</script>
<?php 

function relatorio_tabela_adesao($referencia, $dados_meses){

	$mes_ano = explode("-", $referencia);
	$mes = $mes_ano[1];
	$ano = $mes_ano[0];

	$periodo_amostra = "<span style=\"font-size: 14px;\"><strong>Referência: </strong>".$dados_meses[$mes]." de ".$ano."</span>";
	$gerado = "<span style=\"font-size: 14px;\"><strong>Gerado em: </strong> ".converteDataHora(getDataHora())."</span>";	

	echo "<div class=\"col-md-6 col-md-offset-3\" style=\"padding: 0\">";
	echo "<legend style=\"text-align:center;\"><strong>Relatório de Faturamento - Call Center - Adesões (Tabela)</strong><br>$gerado</legend>";
	echo "<legend style=\"text-align:center;\">$periodo_amostra</legend>";

	$dados_consulta = DBRead('', 'tb_faturamento a',"INNER JOIN tb_faturamento_contrato b ON a.id_faturamento = b.id_faturamento INNER JOIN tb_contrato_plano_pessoa c ON b.id_contrato_plano_pessoa = c.id_contrato_plano_pessoa INNER JOIN tb_pessoa d ON c.id_pessoa = d.id_pessoa WHERE a.adesao = '1' AND a.data_referencia = '".$referencia."' ", "a.*, b.*, c.nome_contrato, d.nome, a.status AS status_contrato, a.valor_adesao AS valor_adesao_faturamento");

	if($dados_consulta){
        echo '
            <table class="table table-hover dataTable" style="margin-bottom:0;">
                <thead>
                    <tr>
                        <th>Contrato</th>
                        <th>Valor da Adesão</th>					
                    </tr>
                </thead>
                <tbody>
        ';              

		$contador_valor_adesao_faturamento = 0;

		foreach($dados_consulta as $dado_consulta){
			
			//NOME DO CONTRATO
			if($dado_consulta['nome_contrato']){
                $nome_contrato = " (".$dado_consulta['nome_contrato'].") ";
            }else{
                $nome_contrato = '';
            }

            $contrato = $dado_consulta['nome']." ".$nome_contrato;
			if($dado_consulta['tipo_cobranca'] == 'mensal_desafogo'){
				$tipo_cobranca = "Mensal com Desafogo";
			}else if($dado_consulta['tipo_cobranca'] == 'cliente_base'){
				$tipo_cobranca = "Clientes na Base";
			}else if($dado_consulta['tipo_cobranca'] == 'cliente_ativo'){
				$tipo_cobranca = "Clientes Ativos";
			}else if($dado_consulta['tipo_cobranca'] == 'x_cliente_base'){
				$tipo_cobranca = "Até X Clientes na Base";
			}else if($dado_consulta['tipo_cobranca'] == 'prepago'){
				$tipo_cobranca = "Pré-pago";
			}else{
				$tipo_cobranca = ucfirst($dado_consulta['tipo_cobranca']);
			}			

           	echo "<tr>";
				echo "<td>".$contrato."</td>";			
				echo "<td data-order='".$dado_consulta['valor_adesao_faturamento']."'><strong>R$ ".converteMoeda($dado_consulta['valor_adesao_faturamento'],'moeda')."</strong></td>";				
			echo "</tr>";
			$contador_valor_adesao_faturamento = $dado_consulta['valor_adesao_faturamento'] + $contador_valor_adesao_faturamento;
							
		}

		echo '		
			</tbody>';
			echo "<tfoot>";
					echo '<tr>';
						echo '<th>Totais</th>';
						echo '<th>R$ '.converteMoeda($contador_valor_adesao_faturamento,'moeda').'</th>';
					echo '</tr>';
				echo "</tfoot> ";
        echo '</table>';
        echo "<br><br><br>";

		echo "<script>
				$(document).ready(function(){
					var table = $('.dataTable').DataTable({
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

					var buttons = new $.fn.dataTable.Buttons(table, {
						buttons: [
							{
								extend: 'excelHtml5',
								text: '<i class=\"fa fa-file-excel-o\" aria-hidden=\"true\"></i> Exportar',
								filename: 'relatorio_faturamento_call_ativo',
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

function relatorio_detalhado_adesao($referencia, $dados_meses){

	$mes_ano = explode("-", $referencia);
	$mes = $mes_ano[1];
	$ano = $mes_ano[0];

	$periodo_amostra = "<span style=\"font-size: 14px;\"><strong>Referência: </strong>".$dados_meses[$mes]." de ".$ano."</span>";
	$gerado = "<span style=\"font-size: 14px;\"><strong>Gerado em: </strong> ".converteDataHora(getDataHora())."</span>";	

	echo "<div class=\"col-md-12\" style=\"padding: 0\">";
	echo "<legend style=\"text-align:center;\"><strong>Relatório de Faturamento - Call Center - Adesões (Detalhado)</strong><br>$gerado</legend>";
	echo "<legend style=\"text-align:center;\">$periodo_amostra</legend>";

	$dados_consulta = DBRead('', 'tb_faturamento a',"INNER JOIN tb_faturamento_contrato b ON a.id_faturamento = b.id_faturamento INNER JOIN tb_contrato_plano_pessoa c ON b.id_contrato_plano_pessoa = c.id_contrato_plano_pessoa INNER JOIN tb_pessoa d ON c.id_pessoa = d.id_pessoa WHERE a.adesao = '1' AND a.data_referencia = '".$referencia."' ", "a.*, b.*, c.nome_contrato, d.nome, a.status AS status_contrato, a.valor_adesao AS valor_adesao_faturamento, a.id_usuario AS id_usuario_faturamento");

	if($dados_consulta){
		foreach($dados_consulta as $conteudo_consulta){
			
			//NOME DO CONTRATO
			if($conteudo_consulta['nome_contrato']){
                $nome_contrato = " (".$conteudo_consulta['nome_contrato'].") ";
            }else{
                $nome_contrato = '';
            }
            $contrato = "<strong>".$conteudo_consulta['nome']."</strong> ".$nome_contrato;
			
			echo '<div class="panel panel-default">
						<div class="panel-heading">';

			$dados_usuario = DBRead('', 'tb_usuario a',"INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE id_usuario = '".$conteudo_consulta['id_usuario_faturamento']."'", "b.nome");
			
			echo 
			'
                <div class="row"> 
                    <h3 class="panel-title text-left col-md-6" style="margin-top: 2px;">'.$contrato.'</h3>
                    <h3 class="panel-title text-right col-md-6" style="margin-top: 2px;">Gerado em '.converteDataHora($conteudo_consulta['data_gerado']).', por '.$dados_usuario[0]['nome'].'</h3>';
			            
	            echo '
			</div>
                </div>

			  	<div class="panel-body" id="panel_body_'.$conteudo_consulta['id_contrato_plano_pessoa'].'">
					<div class="row">
						<div class="col-md-3"><strong>Valor da Adesão: </strong>R$ '.converteMoeda($conteudo_consulta['valor_adesao_faturamento'], 'moeda').'</div>
					</div>

				</div>
			</div>';				
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
    echo "</div>";
}

function relatorio_agrupado_mes($ano_de_referencia, $ano_ate_referencia){

	$gerado = "<span style=\"font-size: 14px;\"><strong>Gerado em: </strong> ".converteDataHora(getDataHora())."</span>";	

	echo "<div class=\"col-md-12\" style=\"padding: 0\">";
	echo "<legend style=\"text-align:center;\"><strong>Relatório de Faturamento - Totais Agrupados por mês</strong><br>$gerado</legend>";
	
	while($ano_de_referencia <= $ano_ate_referencia){

		echo '
		<table class="table table-hover dataTable" style="margin-bottom:0;">
			<thead>
				<tr>';
				echo 
						'<th class="col-md-1"></th>';	
						$mes_condicao = 1;
						while($mes_condicao <= 12){
							echo 
							'<th class="col-md-1">'.(($mes_condicao < 10) ? "0".$mes_condicao : $mes_condicao)."/".$ano_de_referencia.'</th>';	
							$mes_condicao++;
							
						}
					echo '</tr>
					</thead>
					<tbody>';

		echo "<tr>";
		echo "<td>Call Suporte</td>";

		$mes_condicao = 1;
		while($mes_condicao <= 12){
			$data_referencia = $ano_de_referencia."-".(($mes_condicao < 10) ? "0".$mes_condicao : $mes_condicao)."-01";

			$dados_consulta_suporte = DBRead('', 'tb_faturamento a',"INNER JOIN tb_plano b ON a.id_plano = b.id_plano WHERE b.cod_servico = 'call_suporte' AND data_referencia = '".$data_referencia."' AND a.status = '1'  ", "SUM(a.valor_cobranca) AS soma_total");
			if($dados_consulta_suporte[0]['soma_total']){
				$total_suporte = $dados_consulta_suporte[0]['soma_total'];
			}else{
				$total_suporte = 0;
			}

			echo "<td> R$ ".converteMoeda($total_suporte)."</td>";
			$mes_condicao++;
		}
		echo "</tr>";

		echo "<tr>";
		echo "<td>Call Ativo - Mensal</td>";

		$mes_condicao = 1;
		while($mes_condicao <= 12){
			$data_referencia = $ano_de_referencia."-".(($mes_condicao < 10) ? "0".$mes_condicao : $mes_condicao)."-01";

			$dados_consulta_ativo = DBRead('', 'tb_faturamento a',"INNER JOIN tb_plano b ON a.id_plano = b.id_plano WHERE b.cod_servico = 'call_ativo' AND data_referencia = '".$data_referencia."' AND a.status = '1' AND avulso = 0", "SUM(a.valor_cobranca) AS soma_total");
			if($dados_consulta_ativo[0]['soma_total']){
				$total_ativo = $dados_consulta_ativo[0]['soma_total'];
			}else{
				$total_ativo = 0;
			}

			echo "<td> R$ ".converteMoeda($total_ativo)."</td>";
			$mes_condicao++;
		}
		echo "</tr>";

		echo "<tr>";
		echo "<td>Call Ativo - Avulso</td>";

		$mes_condicao = 1;
		while($mes_condicao <= 12){
			$data_referencia = $ano_de_referencia."-".(($mes_condicao < 10) ? "0".$mes_condicao : $mes_condicao)."-01";

			$dados_consulta_ativo = DBRead('', 'tb_faturamento a',"INNER JOIN tb_plano b ON a.id_plano = b.id_plano WHERE b.cod_servico = 'call_ativo' AND data_referencia = '".$data_referencia."' AND a.status = '1' AND avulso = 1", "SUM(a.valor_cobranca) AS soma_total");
			if($dados_consulta_ativo[0]['soma_total']){
				$total_ativo = $dados_consulta_ativo[0]['soma_total'];
			}else{
				$total_ativo = 0;
			}

			echo "<td> R$ ".converteMoeda($total_ativo)."</td>";
			$mes_condicao++;
		}
		echo "</tr>";

		echo "<tr>";
		echo "<td>Call Monitoramento</td>";

		$mes_condicao = 1;
		while($mes_condicao <= 12){
			$data_referencia = $ano_de_referencia."-".(($mes_condicao < 10) ? "0".$mes_condicao : $mes_condicao)."-01";
			
			$dados_consulta_monitoramento = DBRead('', 'tb_faturamento a',"INNER JOIN tb_plano b ON a.id_plano = b.id_plano WHERE b.cod_servico = 'call_monitoramento' AND data_referencia = '".$data_referencia."' AND a.status = '1' ", "SUM(a.valor_cobranca) AS soma_total");
			if($dados_consulta_monitoramento[0]['soma_total']){
				$total_monitoramento = $dados_consulta_monitoramento[0]['soma_total'];
			}else{
				$total_monitoramento = 0;
			}

			echo "<td> R$ ".converteMoeda($total_monitoramento)."</td>";
			$mes_condicao++;
		}
		echo "</tr>";

		echo "<tr>";
		echo "<td>Adesões</td>";

		$mes_condicao = 1;
		while($mes_condicao <= 12){
			$data_referencia = $ano_de_referencia."-".(($mes_condicao < 10) ? "0".$mes_condicao : $mes_condicao)."-01";

			$dados_consulta_adesao = DBRead('', 'tb_faturamento',"WHERE adesao = '1' AND data_referencia = '".$data_referencia."' ", "SUM(valor_adesao) AS soma_total");
			if($dados_consulta_adesao[0]['soma_total']){
				$total_adesao = $dados_consulta_adesao[0]['soma_total'];
			}else{
				$total_adesao = 0;
			}

			echo "<td> R$ ".converteMoeda($total_adesao)."</td>";
			$mes_condicao++;
		}
		echo "</tr>";

			echo '		
			</tbody>';
			echo "<tfoot>";
			
				echo '<tr>';
				echo "<td></td>";
			
				$mes_condicao = 1;
				while($mes_condicao <= 12){
					$data_referencia = $ano_de_referencia."-".(($mes_condicao < 10) ? "0".$mes_condicao : $mes_condicao)."-01";

					$dados_consulta = DBRead('', 'tb_faturamento a',"INNER JOIN tb_plano b ON a.id_plano = b.id_plano WHERE (b.cod_servico = 'call_monitoramento' OR b.cod_servico = 'call_ativo' OR b.cod_servico = 'call_suporte') AND data_referencia = '".$data_referencia."'  AND a.status = '1' ", "SUM(a.valor_cobranca) AS soma_total");
					
					$dados_consulta_adesao = DBRead('', 'tb_faturamento',"WHERE adesao = '1' AND data_referencia = '".$data_referencia."'  ", "SUM(valor_adesao) AS soma_total");
					if($dados_consulta[0]['soma_total'] && $dados_consulta_adesao[0]['soma_total']){
						$soma_total = $dados_consulta[0]['soma_total'] + $dados_consulta_adesao[0]['soma_total'];
					}else if($dados_consulta[0]['soma_total']){
						$soma_total = $dados_consulta[0]['soma_total'];
					}else if($dados_consulta_adesao[0]['soma_total']){
						$soma_total = $dados_consulta_adesao[0]['soma_total'];
					}else{
						$soma_total = 0;
					}
			
					echo "<td>R$ ".converteMoeda($soma_total)."</td>";
					$mes_condicao++;

				}
					
				echo '</tr>';

			echo "</tfoot> ";
        echo '</table>';
	
		echo "<hr>";
		$ano_de_referencia++;
	}
    echo "</div>";
}

function relatorio_tabela_monitoramento($referencia, $cancelados, $dados_meses, $tipo_cobranca_select){

	if($cancelados == 1){
		$legenda_cancelados = 'Sim';
	}else{
		$legenda_cancelados = 'Não';
		$consulta_cancelados = "AND a.status = '1'";
	}

	if($tipo_cobranca_select){
		if($tipo_cobranca_select == 'mensal_desafogo'){
			$legenda_tipo_cobranca = "Mensal com Desafogo";
		}else if($tipo_cobranca_select == 'unitario'){
			$legenda_tipo_cobranca = "Unitário";
		}else if($tipo_cobranca_select == 'prepago'){
			$legenda_tipo_cobranca = "Pré-Pago";
		}else{
			$legenda_tipo_cobranca = ucfirst($tipo_cobranca_select);
		}
		$consulta_tipo_cobranca = "AND a.tipo_cobranca = '".$tipo_cobranca_select."' ";
	}else{
		$legenda_tipo_cobranca = 'Todas';
		$consulta_tipo_cobranca = "";
	}

	$mes_ano = explode("-", $referencia);
	$mes = $mes_ano[1];
	$ano = $mes_ano[0];

	$periodo_amostra = "<span style=\"font-size: 14px;\"><strong>Referência: </strong>".$dados_meses[$mes]." de ".$ano.", <strong>Mostrar Cancelados: </strong>".$legenda_cancelados.", <strong>Tipo de Cobrança: </strong>".$legenda_tipo_cobranca."</span>";
	$gerado = "<span style=\"font-size: 14px;\"><strong>Gerado em: </strong> ".converteDataHora(getDataHora())."</span>";	

	echo "<div class=\"col-md-12\" style=\"padding: 0\">";
	echo "<legend style=\"text-align:center;\"><strong>Relatório de Faturamento - Call Center - Monitoramento (Tabela)</strong><br>$gerado</legend>";
	echo "<legend style=\"text-align:center;\">$periodo_amostra</legend>";

	$dados_consulta = DBRead('', 'tb_faturamento a',"INNER JOIN tb_faturamento_contrato b ON a.id_faturamento = b.id_faturamento INNER JOIN tb_contrato_plano_pessoa c ON b.id_contrato_plano_pessoa = c.id_contrato_plano_pessoa INNER JOIN tb_pessoa d ON c.id_pessoa = d.id_pessoa INNER JOIN tb_plano e ON a.id_plano = e.id_plano WHERE e.cod_servico = 'call_monitoramento' AND b.contrato_pai = '1' AND a.data_referencia = '".$referencia."' ".$consulta_cancelados." ".$consulta_tipo_cobranca." ORDER BY d.nome", "a.*, b.*, c.nome_contrato, d.nome, a.status AS status_contrato, a.id_usuario AS id_usuario_faturamento, e.nome AS nome_plano, c.dia_pagamento");

	if($dados_consulta){
        echo '
            <table class="table table-hover dataTable" style="margin-bottom:0;">
                <thead>
                    <tr>
                        <th>Contrato</th>
                        <th>Plano</th>
                        <th>Tipo de Cobrança</th>
                        <th>Dia de Pagamento</th>	
                        <th>Valor Unitário</th>
                        <th>Valor Excedente</th>
                        <th>Valor Total do Contrato</th>
                        <th>Qtd Contratada</th>
                        <th>Qtd Efetuada</th>
						<th>Qtd de Excedentes</th>
                        <th>Valor da Cobrança</th>					
                    </tr>
                </thead>
                <tbody>
        ';              
	
		$contador_efetuada = 0;
		$contador_excedente = 0;
		$contador_valor = 0;
		$contador_qtd_contrato = 0;

		foreach($dados_consulta as $dado_consulta){
			
			//NOME DO CONTRATO
			if($dado_consulta['nome_contrato']){
                $nome_contrato = " (".$dado_consulta['nome_contrato'].") ";
            }else{
                $nome_contrato = '';
            }

            $contrato = $dado_consulta['nome']." ".$nome_contrato;
			if($dado_consulta['tipo_cobranca'] == 'mensal_desafogo'){
				$tipo_cobranca = "Mensal com Desafogo";
			}else if($dado_consulta['tipo_cobranca'] == 'cliente_base'){
				$tipo_cobranca = "Clientes na Base";
			}else if($dado_consulta['tipo_cobranca'] == 'cliente_ativo'){
				$tipo_cobranca = "Clientes Ativos";
			}else if($dado_consulta['tipo_cobranca'] == 'x_cliente_base'){
				$tipo_cobranca = "Até X Clientes na Base";
			}else if($dado_consulta['tipo_cobranca'] == 'prepago'){
				$tipo_cobranca = "Pré-pago";
			}else{
				$tipo_cobranca = ucfirst($dado_consulta['tipo_cobranca']);
			}
			
			if($dado_consulta['status'] != 1){
            	$style_cancelado = "style='color:#B22222;'";
            }else{
            	$style_cancelado = "";
            }

           	echo "<tr>";

				echo "<td ".$style_cancelado.">".$contrato."</td>";
				echo "<td>".$dado_consulta['nome_plano']."</td>";
				echo "<td>".$tipo_cobranca."</td>";
				echo "<td>".$dado_consulta['dia_pagamento']."</td>";
				echo "<td data-order='".$dado_consulta['valor_unitario_contrato']."'>R$ ".converteMoeda($dado_consulta['valor_unitario_contrato'],'moeda')."</td>";
				echo "<td data-order='".$dado_consulta['valor_excedente_contrato']."'>R$ ".converteMoeda($dado_consulta['valor_excedente_contrato'],'moeda')."</td>";
				echo "<td data-order='".$dado_consulta['valor_total_contrato']."'>R$ ".converteMoeda($dado_consulta['valor_total_contrato'],'moeda')."</td>";
				echo "<td>".$dado_consulta['qtd_contratada']."</td>";
				echo "<td>".$dado_consulta['qtd_efetuada']."</td>";
				echo "<td>".$dado_consulta['qtd_excedente']."</td>";
				echo "<td data-order='".$dado_consulta['valor_cobranca']."'><strong>R$ ".converteMoeda($dado_consulta['valor_cobranca'],'moeda')."</strong></td>";				
				
			echo "</tr>";
			$contador_efetuada = $dado_consulta['qtd_efetuada'] + $contador_efetuada;
			$contador_excedente = $dado_consulta['qtd_excedente'] + $contador_excedente;
			$contador_valor = $dado_consulta['valor_cobranca'] + $contador_valor;
			$contador_qtd_contrato = $dado_consulta['qtd_contratada'] + $contador_qtd_contrato;
							
		}

		echo '		
			</tbody>';
			echo "<tfoot>";
					
					echo '<tr>';
						echo '<th>Totais</th>';
						echo '<th></th>';
						echo '<th></th>';
						echo '<th></th>';
						echo '<th></th>';
						echo '<th></th>';
						echo '<th></th>';
						echo '<th>'.$contador_qtd_contrato.'</th>';
						echo '<th>'.$contador_efetuada.'</th>';
						echo '<th>'.$contador_excedente.'</th>';
						echo '<th>R$ '.converteMoeda($contador_valor,'moeda').'</th>';
					echo '</tr>';

				echo "</tfoot> ";
        echo '</table>';
        echo "<br><br><br>";

		echo "<script>
				$(document).ready(function(){
					var table = $('.dataTable').DataTable({
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

					var buttons = new $.fn.dataTable.Buttons(table, {
						buttons: [
							{
								extend: 'excelHtml5',
								text: '<i class=\"fa fa-file-excel-o\" aria-hidden=\"true\"></i> Exportar',
								filename: 'relatorio_faturamento_monitoramento',
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

function relatorio_detalhado_monitoramento($referencia, $cancelados, $dados_meses, $tipo_cobranca_select){
	
	if($cancelados == 1){
		$legenda_cancelados = 'Sim';
	}else{
		$legenda_cancelados = 'Não';
		$consulta_cancelados = "AND a.status = '1'";
	}

	if($tipo_cobranca_select){
		if($tipo_cobranca_select == 'mensal_desafogo'){
			$legenda_tipo_cobranca = "Mensal com Desafogo";
		}else if($tipo_cobranca_select == 'unitario'){
			$legenda_tipo_cobranca = "Unitário";
		}else if($tipo_cobranca_select == 'prepago'){
			$legenda_tipo_cobranca = "Pré-Pago";
		}else{
			$legenda_tipo_cobranca = ucfirst($tipo_cobranca_select);
		}
		$consulta_tipo_cobranca = "AND a.tipo_cobranca = '".$tipo_cobranca_select."' ";
	}else{
		$legenda_tipo_cobranca = 'Todas';
		$consulta_tipo_cobranca = "";
	}

	$mes_ano = explode("-", $referencia);
	$mes = $mes_ano[1];
	$ano = $mes_ano[0];

	$periodo_amostra = "<span style=\"font-size: 14px;\"><strong>Referência: </strong>".$dados_meses[$mes]." de ".$ano.", <strong>Mostrar Cancelados: </strong>".$legenda_cancelados.", <strong>Tipo de Cobrança: </strong>".$legenda_tipo_cobranca."</span>";
	$gerado = "<span style=\"font-size: 14px;\"><strong>Gerado em: </strong> ".converteDataHora(getDataHora())."</span>";	

	echo "<div class=\"col-md-12\" style=\"padding: 0\">";
	echo "<legend style=\"text-align:center;\"><strong>Relatório de Faturamento - Call Center - Monitoramento (Detalhado)</strong><br>$gerado</legend>";
	echo "<legend style=\"text-align:center;\">$periodo_amostra</legend>";

	$dados_consulta = DBRead('', 'tb_faturamento a',"INNER JOIN tb_faturamento_contrato b ON a.id_faturamento = b.id_faturamento INNER JOIN tb_contrato_plano_pessoa c ON b.id_contrato_plano_pessoa = c.id_contrato_plano_pessoa INNER JOIN tb_pessoa d ON c.id_pessoa = d.id_pessoa INNER JOIN tb_plano e ON a.id_plano = e.id_plano WHERE e.cod_servico = 'call_monitoramento' AND b.contrato_pai = '1' AND a.data_referencia = '".$referencia."' ".$consulta_cancelados." ".$consulta_tipo_cobranca." ORDER BY d.nome", "a.*, b.*, c.nome_contrato, d.nome, a.status AS status_contrato, a.id_usuario AS id_usuario_faturamento, e.nome AS nome_plano, c.dia_pagamento");

	if($dados_consulta){
		foreach($dados_consulta as $conteudo_consulta){
			
			//NOME DO CONTRATO
			if($conteudo_consulta['nome_contrato']){
                $nome_contrato = " (".$conteudo_consulta['nome_contrato'].") ";
            }else{
                $nome_contrato = '';
            }
            $contrato = "<strong>".$conteudo_consulta['nome']."</strong> ".$nome_contrato;

			if($conteudo_consulta['tipo_cobranca'] == 'mensal_desafogo'){
				$tipo_cobranca = "Mensal com Desafogo (".$conteudo_consulta['desafogo_contrato']."%)";
			}else if($conteudo_consulta['tipo_cobranca'] == 'unitario'){
				$tipo_cobranca = "Unitário";
			}else if($conteudo_consulta['tipo_cobranca'] == 'prepago'){
				$tipo_cobranca = "Pré-Pago";
			}else{
				$tipo_cobranca = ucfirst($conteudo_consulta['tipo_cobranca']);
			}

	        if($conteudo_consulta['status_contrato'] != 0){
	        	echo '<div class="panel panel-default">';
			}else{
				echo '<div class="panel panel-danger">';
			}

			$dados_usuario = DBRead('', 'tb_usuario a',"INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE id_usuario = '".$conteudo_consulta['id_usuario_faturamento']."'", "b.nome");
		
			echo 
			'<div class="panel-heading clearfix">
				<div class= "row">
					<h3 class="panel-title text-left col-md-6" style="margin-top: 2px;">'.$contrato.'</h3>
					<h3 class="panel-title text-right col-md-6" style="margin-top: 2px;">Gerado em '.converteDataHora($conteudo_consulta['data_gerado']).', por '.$dados_usuario[0]['nome'].'</h3>';
			            				echo '
				</div>
			</div>

			<div class="panel-body" id="panel_body_'.$conteudo_consulta['id_contrato_plano_pessoa'].'">
				<div class="row">
					<div class="col-md-3"><strong>Plano: </strong>'.$conteudo_consulta['nome_plano'].'</div>
					<div class="col-md-3"><strong>Tipo de Cobrança: </strong>'.$tipo_cobranca.'</div>
					<div class="col-md-3"><strong>Dia de Pagamento: </strong>'.$conteudo_consulta['dia_pagamento'].'</div>
					<div class="col-md-3"><strong>Valor Unitário do Contrato: </strong>R$ <span id = "valor_unitario_'.$conteudo_consulta['id_contrato_plano_pessoa'].'">'.converteMoeda($conteudo_consulta['valor_unitario_contrato'],'moeda').'</span></div>
				</div>

				<div class="row">
					<div class="col-md-3"><strong>Valor Excedente do Contrato: </strong>R$ <span id = "valor_excedente_'.$conteudo_consulta['id_contrato_plano_pessoa'].'">'.converteMoeda($conteudo_consulta['valor_excedente_contrato'],'moeda').'</span></div>
					<div class="col-md-3"><strong>Valor Total do Contrato: </strong>R$ '.converteMoeda($conteudo_consulta['valor_total_contrato'],'moeda').'</div>
					<div class="col-md-3"><strong>Quantidade Contratada: </strong>'.$conteudo_consulta['qtd_contratada'].'</div>
					<div class="col-md-3"><strong>Quantidade Efetuada: </strong>'.$conteudo_consulta['qtd_efetuada'].'</div>							
				</div>
				
				<div class="row">
					<div class="col-md-3"><strong>Quantidade de Excedente: </strong>'.$conteudo_consulta['qtd_excedente'].'</div>
					<div class="col-md-3"><strong>Total de Acréscimos: </strong>R$ <span id = "acrescimo_'.$conteudo_consulta['id_contrato_plano_pessoa'].'">'.converteMoeda($conteudo_consulta['acrescimo'],'moeda').'</span></div>
					<div class="col-md-3"><strong>Total de Descontos: </strong>R$ <span id = "desconto_'.$conteudo_consulta['id_contrato_plano_pessoa'].'">'.converteMoeda($conteudo_consulta['desconto'], 'moeda').'</span></div>
					<div class="col-md-3"><strong>Valor da Cobrança: </strong>R$ <span id = "cobranca_'.$conteudo_consulta['id_contrato_plano_pessoa'].'">'.converteMoeda($conteudo_consulta['valor_cobranca'], 'moeda').'</span></div>
				</div>';

				if($conteudo_consulta['acrescimo'] != '0.00' || $conteudo_consulta['desconto'] != '0.00'){

					$dados_ajuste = DBRead('', 'tb_faturamento_ajuste',"WHERE id_faturamento = '".$conteudo_consulta['id_faturamento']."' ");

					foreach($dados_ajuste as $conteudo_ajuste){
						echo '<hr>';
						if($conteudo_ajuste['tipo'] == 'acrescimo'){
							$tipo_de_ajuste = "Acréscimo";
						}else{
							$tipo_de_ajuste = "Desconto";
						}

						$dados_usuario = DBRead('', 'tb_usuario a',"INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE id_usuario = '".$conteudo_ajuste['id_usuario']."'", "b.nome");

						echo '<div class="row">
								<div class="col-md-3">'.$tipo_de_ajuste.': R$ '.converteMoeda($conteudo_ajuste['valor'],'moeda').'</div>
								<div class="col-md-3">Descrição: '.nl2br($conteudo_ajuste['descricao']).'</div>
								<div class="col-md-3">Data e Hora: '.converteDataHora($conteudo_ajuste['data']).'</div>
								<div class="col-md-3">Usuário: '.$dados_usuario[0]['nome'].'</div>
							  </div>';
					}
				}

				echo'
			</div>
		</div>';				
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
    echo "</div>";
}

function relatorio_tabela_call_suporte($referencia, $cancelados, $dados_meses, $tipo_cobranca_select, $duplicados){

	if($cancelados == 1){
		$legenda_cancelados = 'Sim';
	}else{
		$legenda_cancelados = 'Não';
		$consulta_cancelados = "AND a.status = '1'";
	}

	if($tipo_cobranca_select){
		if($tipo_cobranca_select == 'mensal_desafogo'){
			$legenda_tipo_cobranca = "Mensal com Desafogo";
		}else if($tipo_cobranca_select == 'unitario'){
			$legenda_tipo_cobranca = "Unitário";
		}else if($tipo_cobranca_select == 'x_cliente_base'){
			$legenda_tipo_cobranca = "Até X Clientes na Base";
		}else if($tipo_cobranca_select == 'prepago'){
			$legenda_tipo_cobranca = "Pré-Pago";
		}else{
			$legenda_tipo_cobranca = ucfirst($tipo_cobranca_select);
		}
		$consulta_tipo_cobranca = "AND a.tipo_cobranca = '".$tipo_cobranca_select."' ";
	}else{
		$legenda_tipo_cobranca = 'Todas';
		$consulta_tipo_cobranca = "";
	}

	if($duplicados){
		
		if($duplicados == 1){
			$legenda_duplicados = 'Sim';
			$consulta_duplicados = "AND a.remove_duplicados_contrato = '1'";	
		}else{
			$legenda_duplicados = 'Não';
			$consulta_duplicados = "AND a.remove_duplicados_contrato = '0'";	
		}
	}else{
		$legenda_duplicados = 'Qualquer';
		$consulta_duplicados = "";	
	}

	$mes_ano = explode("-", $referencia);
	$mes = $mes_ano[1];
	$ano = $mes_ano[0];

	$periodo_amostra = "<span style=\"font-size: 14px;\"><strong>Referência: </strong>".$dados_meses[$mes]." de ".$ano.", <strong>Mostrar Cancelados: </strong>".$legenda_cancelados.", <strong>Tipo de Cobrança: </strong>".$legenda_tipo_cobranca.", <strong>Remove Duplicados: </strong>".$legenda_duplicados."</span>";
	$gerado = "<span style=\"font-size: 14px;\"><strong>Gerado em: </strong> ".converteDataHora(getDataHora())."</span>";	

	echo "<div class=\"col-md-12\" style=\"padding: 0\">";
	echo "<legend style=\"text-align:center;\"><strong>Relatório de Faturamento - Call Center - Suporte (Tabela)</strong><br>$gerado</legend>";
	echo "<legend style=\"text-align:center;\">$periodo_amostra</legend>";

	$dados_consulta = DBRead('', 'tb_faturamento a',"INNER JOIN tb_faturamento_contrato b ON a.id_faturamento = b.id_faturamento INNER JOIN tb_contrato_plano_pessoa c ON b.id_contrato_plano_pessoa = c.id_contrato_plano_pessoa INNER JOIN tb_pessoa d ON c.id_pessoa = d.id_pessoa INNER JOIN tb_plano e ON a.id_plano = e.id_plano WHERE e.cod_servico = 'call_suporte' AND b.contrato_pai = '1' AND a.data_referencia = '".$referencia."' ".$consulta_cancelados." ".$consulta_tipo_cobranca." ".$consulta_duplicados." ", "a.*, b.*, c.nome_contrato, d.nome, a.status AS status_contrato, e.nome AS nome_plano, a.qtd_clientes_teto AS quantidade_clientes_teto");


	if($dados_consulta){
        echo '
            <table class="table table-hover dataTable" style="margin-bottom:0;">
                <thead>
                    <tr>
                        <th>Contrato</th>
                        <th>Plano</th>
                        <th>Tipo de Cobrança</th>
                        <th>Valor Contrato</th>
                        <th>Valor Inicial</th>
                        <th>Valor Unitário do Contrato (Via Telefone)</th>
                        <th>Valor Excedente do Contrato (Via Telefone)</th>
                        <th>Qtd Contratada (Via Telefone)</th>
                        <th>Qtd Efetuada (Via Telefone)</th>
                        <th>Qtd de Excedentes (Via Telefone)</th>
                        <th>Qtd de Desafogos (Via Telefone)</th>
                        <th>Qtd Duplicados</th>
                        <th>Valor Unitário do Contrato (Via Texto)</th>
                        <th>Valor Excedente do Contrato (Via Texto)</th>
                        <th>Qtd Contratada (Via Texto)</th>
                        <th>Qtd Efetuada (Via Texto)</th>
                        <th>Qtd Desafogo (Via Texto)</th>
                        <th>Qtd Excedente (Via Texto)</th>
                        <th>Qtd Efetuadas (Telefone + Texto)</th>
                        <th>Qtd Clientes</th>
                        <th>Qtd Contratada (Clientes)</th>
                        <th>Total de Acréscimos</th>
                        <th>Total de Descontos</th>
                        <th>Valor Total do Faturamento</th>					
                        <th>Valor da Cobrança</th>					
                    </tr>
                </thead>
                <tbody>
		';              		
	
		$contador_efetuada = 0;
		$contador_desafogo = 0;
		$contador_acrescimo = 0;
		$contador_desconto = 0;
		$contador_duplicados = 0;
		$contador_excedente = 0;
		$contador_valor = 0;
		$contador_valor_contrato = 0;
		$contador_qtd_contrato = 0;
		$contador_valor_total_faturamento = 0;

		$contador_qtd_contratada_texto = 0;
		$contador_qtd_efetuada_texto = 0;
		$contador_qtd_desafogo_texto = 0;
		$contador_qtd_excedente_texto = 0;

		$contador_qtd_clientes = 0;
		$contador_qtd_clientes_teto = 0;
		
		$contador_qtd_ligacao_texto = 0;

		foreach($dados_consulta as $dado_consulta){
			
			$dados_consulta_filho = DBRead('','tb_faturamento_contrato',"WHERE (contrato_pai = '0' OR contrato_pai IS NULL) AND id_faturamento = '".$dado_consulta['id_faturamento']."' ");

			//NOME DO CONTRATO
			if($dado_consulta['nome_contrato']){
                $nome_contrato = " (".$dado_consulta['nome_contrato'].") ";
            }else{
                $nome_contrato = '';
            }
			
			$texto_filho = '';
            
            if($dados_consulta_filho){
            	//TEM FILHO

            	foreach ($dados_consulta_filho as $conteudo_consulta_filho) {
					$dados_filho = DBRead('','tb_contrato_plano_pessoa a',"INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE id_contrato_plano_pessoa = '".$conteudo_consulta_filho['id_contrato_plano_pessoa']."' ");
					$texto_filho .= '<a tabindex="0" data-toggle="tooltip" title="'.$dados_filho[0]['nome'].'"> <i class="fa fa-question-circle"></i></a>';
				}
            }

            $contrato = $dado_consulta['nome']." ".$nome_contrato." ".$texto_filho;

			if($dado_consulta['tipo_cobranca'] == 'mensal_desafogo'){
				$tipo_cobranca = "Mensal com Desafogo (".$dado_consulta['desafogo_contrato']."%)";
			}else if($dado_consulta['tipo_cobranca'] == 'unitario'){
				$tipo_cobranca = "Unitário";
			}else if($dado_consulta['tipo_cobranca'] == 'x_cliente_base'){
				$tipo_cobranca = "Até X Clientes na Base";
			}else if($dado_consulta['tipo_cobranca'] == 'prepago'){
				$tipo_cobranca = "Pré-Pago";
			}else{
				$tipo_cobranca = ucfirst($dado_consulta['tipo_cobranca']);
			}

           	echo "<tr>";
				echo "<td style='vertical-align: middle'>".$contrato."</td>";
				echo "<td style='vertical-align: middle'>".$dado_consulta['nome_plano']."</td>";
				echo "<td style='vertical-align: middle'>".$tipo_cobranca."</td>";
				echo "<td style='vertical-align: middle' data-order='".$dado_consulta['valor_total_contrato']."'>R$ ".converteMoeda($dado_consulta['valor_total_contrato'],'moeda')."</td>";
				echo "<td style='vertical-align: middle' data-order='".$dado_consulta['valor_inicial_contrato']."'>R$ ".converteMoeda($dado_consulta['valor_inicial_contrato'],'moeda')."</td>";
				echo "<td style='vertical-align: middle' data-order='".$dado_consulta['valor_unitario_contrato']."'>R$ ".converteMoeda($dado_consulta['valor_unitario_contrato'],'moeda')."</td>";
				echo "<td style='vertical-align: middle' data-order='".$dado_consulta['valor_excedente_contrato']."'>R$ ".converteMoeda($dado_consulta['valor_excedente_contrato'],'moeda')."</td>";
				echo "<td style='vertical-align: middle'>".$dado_consulta['qtd_contratada']."</td>";
				echo "<td style='vertical-align: middle'>".$dado_consulta['qtd_efetuada']."</td>";
				echo "<td style='vertical-align: middle'>".$dado_consulta['qtd_excedente']."</td>";
				echo "<td style='vertical-align: middle'>".$dado_consulta['qtd_desafogo']."</td>";
				echo "<td style='vertical-align: middle'>".$dado_consulta['qtd_duplicados']."</td>";
				echo "<td style='vertical-align: middle'>R$ ".converteMoeda($dado_consulta['valor_unitario_texto_contrato'], 'moeda')."</td>";
				echo "<td style='vertical-align: middle'>R$ ".converteMoeda($dado_consulta['valor_excedente_texto_contrato'], 'moeda')."</td>";
				echo "<td style='vertical-align: middle'>".$dado_consulta['qtd_contratada_texto']."</td>";
				echo "<td style='vertical-align: middle'>".$dado_consulta['qtd_efetuada_texto']."</td>";
				echo "<td style='vertical-align: middle'>".$dado_consulta['qtd_desafogo_texto']."</td>";
				echo "<td style='vertical-align: middle'>".$dado_consulta['qtd_excedente_texto']."</td>";
				echo "<td style='vertical-align: middle'>".($dado_consulta['qtd_efetuada'] + $dado_consulta['qtd_efetuada_texto'])."</td>";
				echo "<td style='vertical-align: middle'>".($dado_consulta['qtd_clientes'])."</td>";
				echo "<td style='vertical-align: middle'>".($dado_consulta['quantidade_clientes_teto'])."</td>";
				echo "<td style='vertical-align: middle' data-order='".$dado_consulta['acrescimo']."'>R$ ".converteMoeda($dado_consulta['acrescimo'],'moeda')."</td>";
				echo "<td style='vertical-align: middle' data-order='".$dado_consulta['desconto']."'>R$ ".converteMoeda($dado_consulta['desconto'],'moeda')."</td>";
				echo "<td style='vertical-align: middle' data-order='".$dado_consulta['valor_total']."'>R$ ".converteMoeda($dado_consulta['valor_total'],'moeda')."</td>";				
				echo "<td style='vertical-align: middle' data-order='".$dado_consulta['valor_cobranca']."'><strong>R$ ".converteMoeda($dado_consulta['valor_cobranca'],'moeda')."</strong></td>";				
			echo "</tr>";
			$contador_efetuada = $dado_consulta['qtd_efetuada'] + $contador_efetuada;
			$contador_excedente = $dado_consulta['qtd_excedente'] + $contador_excedente;
			$contador_desafogo = $dado_consulta['qtd_desafogo'] + $contador_desafogo;
			$contador_duplicados = $dado_consulta['qtd_duplicados'] + $contador_duplicados;
			$contador_qtd_contratada_texto = $dado_consulta['qtd_contratada_texto'] + $contador_qtd_contratada_texto;
			$contador_qtd_efetuada_texto = $dado_consulta['qtd_efetuada_texto'] + $contador_qtd_efetuada_texto;
			$contador_qtd_desafogo_texto = $dado_consulta['qtd_desafogo_texto'] + $contador_qtd_desafogo_texto;
			$contador_qtd_excedente_texto = $dado_consulta['qtd_excedente_texto'] + $contador_qtd_excedente_texto;
			$contador_qtd_clientes = $dado_consulta['qtd_clientes'] + $contador_qtd_clientes;
			$contador_qtd_clientes_teto = $dado_consulta['quantidade_clientes_teto'] + $contador_qtd_clientes_teto;
			$contador_qtd_ligacao_texto = ($dado_consulta['qtd_efetuada'] + $dado_consulta['qtd_efetuada_texto']) + $contador_qtd_ligacao_texto;
			$contador_acrescimo = $dado_consulta['acrescimo'] + $contador_acrescimo;
			$contador_desconto = $dado_consulta['desconto'] + $contador_desconto;
			$contador_valor = $dado_consulta['valor_cobranca'] + $contador_valor;
			$contador_valor_contrato = $dado_consulta['valor_total_contrato'] + $contador_valor_contrato;
			$contador_qtd_contrato = $dado_consulta['qtd_contratada'] + $contador_qtd_contrato;
			$contador_valor_total_faturamento = $dado_consulta['valor_total'] + $contador_valor_total_faturamento;
		}

		echo '		
			</tbody>';
			echo "<tfoot>";
					echo '<tr>';
						echo '<th style="vertical-align: middle">Totais</th>';
						echo '<th style="vertical-align: middle"></th>';
						echo '<th style="vertical-align: middle"></th>';
						echo '<th style="vertical-align: middle">R$ '.converteMoeda($contador_valor_contrato,'moeda').'</th>';
						echo '<th style="vertical-align: middle"></th>';
						echo '<th style="vertical-align: middle"></th>';
						echo '<th style="vertical-align: middle"></th>';
						echo '<th style="vertical-align: middle">'.$contador_qtd_contrato.'</th>';
						echo '<th style="vertical-align: middle">'.$contador_efetuada.'</th>';
						echo '<th style="vertical-align: middle">'.$contador_excedente.'</th>';
						echo '<th style="vertical-align: middle">'.$contador_desafogo.'</th>';
						echo '<th style="vertical-align: middle">'.$contador_duplicados.'</th>'; 
						echo '<th style="vertical-align: middle"></th>'; 
						echo '<th style="vertical-align: middle"></th>'; 
						echo '<th style="vertical-align: middle">'.$contador_qtd_contratada_texto.'</th>'; 
						echo '<th style="vertical-align: middle">'.$contador_qtd_efetuada_texto.'</th>'; 
						echo '<th style="vertical-align: middle">'.$contador_qtd_desafogo_texto.'</th>'; 
						echo '<th style="vertical-align: middle">'.$contador_qtd_excedente_texto.'</th>'; 
						echo '<th style="vertical-align: middle">'.$contador_qtd_ligacao_texto.'</th>'; 
						echo '<th style="vertical-align: middle">'.$contador_qtd_clientes.'</th>'; 
						echo '<th style="vertical-align: middle">'.$contador_qtd_clientes_teto.'</th>'; 
						echo '<th style="vertical-align: middle">R$ '.converteMoeda($contador_acrescimo,'moeda').'</th>';
						echo '<th style="vertical-align: middle">R$ '.converteMoeda($contador_desconto,'moeda').'</th>';
						echo '<th style="vertical-align: middle">R$ '.converteMoeda($contador_valor_total_faturamento,'moeda').'</th>';
						echo '<th style="vertical-align: middle">R$ '.converteMoeda($contador_valor,'moeda').'</th>';
					echo '</tr>';
				echo "</tfoot> ";
        echo '</table>';
        echo "<br><br><br>";

		echo "<script>
				$(document).ready(function(){
					var table = $('.dataTable').DataTable({
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

					var buttons = new $.fn.dataTable.Buttons(table, {
						buttons: [
							{
								extend: 'excelHtml5',
								text: '<i class=\"fa fa-file-excel-o\" aria-hidden=\"true\"></i> Exportar',
								filename: 'relatorio_faturamento_callcenter',
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

function relatorio_detalhado_call_suporte($referencia, $cancelados, $dados_meses, $tipo_cobranca_select, $duplicados){
	
	if($cancelados == 1){
		$legenda_cancelados = 'Sim';
	}else{
		$legenda_cancelados = 'Não';
		$consulta_cancelados = "AND a.status = '1'";
	}

	if($tipo_cobranca_select){
		if($tipo_cobranca_select == 'mensal_desafogo'){
			$legenda_tipo_cobranca = "Mensal com Desafogo";
		}else if($tipo_cobranca_select == 'unitario'){
			$legenda_tipo_cobranca = "Unitário";
		}else if($tipo_cobranca_select == 'x_cliente_base'){
			$legenda_tipo_cobranca = "Até X Clientes na Base";
		}else if($tipo_cobranca_select == 'prepago'){
			$legenda_tipo_cobranca = "Pré-Pago";
		}else{
			$legenda_tipo_cobranca = ucfirst($tipo_cobranca_select);
		}
		$consulta_tipo_cobranca = "AND a.tipo_cobranca = '".$tipo_cobranca_select."' ";
	}else{
		$legenda_tipo_cobranca = 'Todas';
		$consulta_tipo_cobranca = "";
	}

	if($duplicados){
		if($duplicados == 1){
			$legenda_duplicados = 'Sim';
			$consulta_duplicados = "AND a.remove_duplicados_contrato = '1'";	
		}else{
			$legenda_duplicados = 'Não';
			$consulta_duplicados = "AND a.remove_duplicados_contrato = '0'";	
		}
	}else{
		$legenda_duplicados = 'Qualquer';
		$consulta_duplicados = "";	
	}

	$mes_ano = explode("-", $referencia);
	$mes = $mes_ano[1];
	$ano = $mes_ano[0];

	$periodo_amostra = "<span style=\"font-size: 14px;\"><strong>Referência: </strong>".$dados_meses[$mes]." de ".$ano.", <strong>Mostrar Cancelados: </strong>".$legenda_cancelados.", <strong>Tipo de Cobrança: </strong>".$legenda_tipo_cobranca.", <strong>Remove Duplicados: </strong>".$legenda_duplicados."</span>";
	$gerado = "<span style=\"font-size: 14px;\"><strong>Gerado em: </strong> ".converteDataHora(getDataHora())."</span>";	

	echo "<div class=\"col-md-12\" style=\"padding: 0\">";
	echo "<legend style=\"text-align:center;\"><strong>Relatório de Faturamento - Call Center - Suporte (Detalhado)</strong><br>$gerado</legend>";
	echo "<legend style=\"text-align:center;\">$periodo_amostra</legend>";

	$dados_consulta = DBRead('', 'tb_faturamento a',"INNER JOIN tb_faturamento_contrato b ON a.id_faturamento = b.id_faturamento INNER JOIN tb_contrato_plano_pessoa c ON b.id_contrato_plano_pessoa = c.id_contrato_plano_pessoa INNER JOIN tb_pessoa d ON c.id_pessoa = d.id_pessoa INNER JOIN tb_plano e ON a.id_plano = e.id_plano WHERE e.cod_servico = 'call_suporte' AND adesao = 0 AND b.contrato_pai = '1' AND a.data_referencia = '".$referencia."' ".$consulta_cancelados." ".$consulta_tipo_cobranca." ".$consulta_duplicados." ORDER BY d.nome", "a.*, b.*, c.nome_contrato, d.nome, a.status AS status_contrato, e.nome AS nome_plano, a.qtd_clientes_teto AS quantidade_clientes_teto");
	
	if($dados_consulta){
		foreach($dados_consulta as $conteudo_consulta){
			
			$dados_consulta_filho = DBRead('','tb_contrato_plano_pessoa',"WHERE contrato_pai = '".$conteudo_consulta['id_contrato_plano_pessoa']."' ");

			$contrato_filho = '';
			if($dados_consulta_filho){

				foreach ($dados_consulta_filho as $conteudo_consulta_filho) {

					$dados_filho = DBRead('','tb_contrato_plano_pessoa a',"INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE id_contrato_plano_pessoa = '".$conteudo_consulta_filho['id_contrato_plano_pessoa']."' ");

					//NOME DO CONTRATO
					if($dados_filho[0]['nome_contrato']){
		                $nome_contrato_filho = " (".$dados_filho[0]['nome_contrato'].") ";
		            }else{
		                $nome_contrato_filho = '';
		            }
		           
		            $contrato_filho = $contrato_filho." - Vínculo com: <strong>".$dados_filho[0]['nome']."</strong> ".$nome_contrato_filho;

		        }

			}else{
				$contrato_filho = '';
			}

			//NOME DO CONTRATO
			if($conteudo_consulta['nome_contrato']){
                $nome_contrato = " (".$conteudo_consulta['nome_contrato'].") ";
            }else{
                $nome_contrato = '';
            }
            $contrato = "<strong>".$conteudo_consulta['nome']."</strong> ".$nome_contrato."".$contrato_filho;

			if($conteudo_consulta['tipo_cobranca'] == 'mensal_desafogo'){
				$tipo_cobranca = "Mensal com Desafogo (".$conteudo_consulta['desafogo_contrato']."%)";
			}else if($conteudo_consulta['tipo_cobranca'] == 'unitario'){
				$tipo_cobranca = "Unitário";
			}else if($conteudo_consulta['tipo_cobranca'] == 'x_cliente_base'){
				$tipo_cobranca = "Até X Clientes na Base";
			}else if($conteudo_consulta['tipo_cobranca'] == 'prepago'){
				$tipo_cobranca = "Pré-Pago";
			}else{
				$tipo_cobranca = ucfirst($conteudo_consulta['tipo_cobranca']);
			}

			if($conteudo_consulta['remove_duplicados_contrato'] == '1'){
				$remove_duplicados = "Sim (".$conteudo_consulta['minutos_duplicados_contrato']." minutos)";
			}else{
				$remove_duplicados = "Não";
			}

	        if($conteudo_consulta['status_contrato'] != 0){
	        	echo '<div class="panel panel-default">';
	        	
			}else{
				echo '<div class="panel panel-danger">';
				//		                    	<h3 class="panel-title text-center col-md-4" style="margin-top: 2px;"><strong>Cancelado</strong></h3>
			}

			$dados_usuario = DBRead('', 'tb_usuario a',"INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE id_usuario = '".$conteudo_consulta['id_usuario']."'", "b.nome");
		
			echo '
			<div class="panel-heading clearfix">
                <div class="row"> 
                    <h3 class="panel-title text-left col-md-6" style="margin-top: 2px;">'.$contrato.'</h3>
                    <h3 class="panel-title text-right col-md-6" style="margin-top: 2px;">Gerado em '.converteDataHora($conteudo_consulta['data_gerado']).', por '.$dados_usuario[0]['nome'].'</h3>';
					
	        echo '
			</div>
                </div>';

				if($conteudo_consulta['tipo_cobranca'] == 'x_cliente_base'){
					echo '
					<div class="panel-body" id="panel_body_'.$conteudo_consulta['id_contrato_plano_pessoa'].'">
					  	<div class="row">
						  	<div class="col-md-3"><strong>Plano: </strong>'.$conteudo_consulta['nome_plano'].'</div>
						  	<div class="col-md-3"><strong>Tipo de Cobrança: </strong>'.$tipo_cobranca.'</div>';

							if($conteudo_consulta['tipo_cobranca'] == 'x_cliente_base'){
								echo '
									<div class="col-md-3"><strong>Quantidade de Clientes: </strong>'.$conteudo_consulta['qtd_clientes'].'</div>
									<div class="col-md-3"><strong>Quantidade Contratada (Clientes): </strong>'.$conteudo_consulta['quantidade_clientes_teto'].'</div>';
							}
						echo '
					  	</div>
  
					  	<div class="row">
						  	<div class="col-md-3"><strong>Valor Excedente do Contrato (Clientes): </strong>R$ '.converteMoeda($conteudo_consulta['valor_excedente_contrato'],'moeda').'</div>
						  	<div class="col-md-3"><strong>Valor Total do Contrato: </strong>R$ '.converteMoeda($conteudo_consulta['valor_total_contrato'],'moeda').'</div>
					  	</div>
  
					  	<div class="row">
						  	<div class="col-md-3"><strong>Quantidade Contratada (Atendimentos): </strong>'.$conteudo_consulta['qtd_contratada'].'</div>
						  	<div class="col-md-3"><strong>Quantidade Realizada (Atendimentos): </strong>'.$conteudo_consulta['qtd_efetuada'].'</div>
						  	<div class="col-md-3"><strong>Quantidade de Excedente (Clientes): </strong>'.$conteudo_consulta['qtd_excedente'].'</div>
					  	</div>';
  
					  	
					  	
				}else{
					echo '
					<div class="panel-body" id="panel_body_'.$conteudo_consulta['id_contrato_plano_pessoa'].'">
						<div class="row">
							<div class="col-md-3"><strong>Plano: </strong>'.$conteudo_consulta['nome_plano'].'</div>
							<div class="col-md-3"><strong>Tipo de Cobrança: </strong>'.$tipo_cobranca.'</div>
							<div class="col-md-3"><strong>Remove Duplicados: </strong>'.$remove_duplicados.'</div>
							<div class="col-md-3"><strong>Quantidade de Duplicados: </strong>'.$conteudo_consulta['qtd_duplicados'].'</div>
						</div>

					<div class="row">
						<div class="col-md-3"><strong>Valor Inicial do Contrato: </strong>R$ '.converteMoeda($conteudo_consulta['valor_inicial_contrato'],'moeda').'</div>
						<div class="col-md-3"><strong>Valor Unitário do Contrato (Via Telefone): </strong>R$ '.converteMoeda($conteudo_consulta['valor_unitario_contrato'],'moeda').'</div>
						<div class="col-md-3"><strong>Valor Excedente do Contrato (Via Telefone): </strong>R$ '.converteMoeda($conteudo_consulta['valor_excedente_contrato'],'moeda').'</div>
						<div class="col-md-3"><strong>Valor Total do Contrato: </strong>R$ '.converteMoeda($conteudo_consulta['valor_total_contrato'],'moeda').'</div>
					</div>

					<div class="row">
						<div class="col-md-3"><strong>Quantidade Contratada (Via Telefone): </strong>'.$conteudo_consulta['qtd_contratada'].'</div>
						<div class="col-md-3"><strong>Quantidade Realizada (Via Telefone): </strong>'.$conteudo_consulta['qtd_efetuada'].'</div>
						<div class="col-md-3"><strong>Quantidade de Desafogo (Via Telefone): </strong>'.$conteudo_consulta['qtd_desafogo'].'</div>
						<div class="col-md-3"><strong>Quantidade de Excedente (Via Telefone): </strong>'.$conteudo_consulta['qtd_excedente'].'</div>
					</div>';

					if($conteudo_consulta['valor_diferente_texto'] == 1){
						echo '

						<div class="row">
							<div class="col-md-3"><strong>Valor Unitário do Contrato (Via Texto): </strong>R$ '.converteMoeda($conteudo_consulta['valor_unitario_texto_contrato'],'moeda').'</span></div>
							<div class="col-md-3"><strong>Valor Excedente do Contrato (Via Texto): </strong>R$ '.converteMoeda($conteudo_consulta['valor_excedente_texto_contrato'],'moeda').'</span></div>
							<div class="col-md-3"><strong>Quantidade Contratada (Via Texto): </strong>'.$conteudo_consulta['qtd_contratada_texto'].'</div>
							<div class="col-md-3"><strong>Quantidade Efetuada (Via Texto): </strong>'.$conteudo_consulta['qtd_efetuada_texto'].'</div>
						</div>

						<div class="row">
							<div class="col-md-3"><strong>Quantidade de Desafogo (Via Texto): </strong>'.$conteudo_consulta['qtd_desafogo_texto'].'</div>
							<div class="col-md-3"><strong>Quantidade de Excedente (Via Texto): </strong>'.$conteudo_consulta['qtd_excedente_texto'].'</div>
						</div>';
					
					}
					
					if($conteudo_consulta['tipo_cobranca'] == 'x_cliente_base'){
						echo '
						<div class="row">
							<div class="col-md-3"><strong>Quantidade de Clientes: </strong>'.$conteudo_consulta['qtd_clientes'].'</div>
							<div class="col-md-3"><strong>Quantidade Contratada (Cliente): </strong>'.$conteudo_consulta['quantidade_clientes_teto'].'</div>
						</div>';
					}
				}

					echo '
					<div class="row">
						<div class="col-md-3"><strong>Total de Acréscimos: </strong>R$ '.converteMoeda($conteudo_consulta['acrescimo'],'moeda').'</div>
						<div class="col-md-3"><strong>Total de Descontos: </strong>R$ '.converteMoeda($conteudo_consulta['desconto'], 'moeda').'</div>
						<div class="col-md-3"><strong>Valor Total do Faturamento: </strong>R$ '.converteMoeda($conteudo_consulta['valor_total'], 'moeda').'</div>
						<div class="col-md-3"><strong>Valor da Cobrança: </strong>R$ '.converteMoeda($conteudo_consulta['valor_cobranca'], 'moeda').'</div>
					</div>';

					if($conteudo_consulta['acrescimo'] != '0.00' || $conteudo_consulta['desconto'] != '0.00'){

						$dados_ajuste = DBRead('', 'tb_faturamento_ajuste',"WHERE id_faturamento = '".$conteudo_consulta['id_faturamento']."' ");

						foreach($dados_ajuste as $conteudo_ajuste){
							echo '<hr>';
							echo "<strong>Ajustes: </strong>";

							if($conteudo_ajuste['tipo'] == 'acrescimo'){
								$tipo_de_ajuste = "Acréscimo";
							}else{
								$tipo_de_ajuste = "Desconto";
							}

							$dados_usuario = DBRead('', 'tb_usuario a',"INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE id_usuario = '".$conteudo_ajuste['id_usuario']."'", "b.nome");

						    echo '<div class="row">
									<div class="col-md-3">'.$tipo_de_ajuste.': R$ '.converteMoeda($conteudo_ajuste['valor'],'moeda').'</div>
									<div class="col-md-3">Descrição: '.nl2br($conteudo_ajuste['descricao']).'</div>
									<div class="col-md-3">Data e Hora: '.converteDataHora($conteudo_ajuste['data']).'</div>
									<div class="col-md-3">Usuário: '.$dados_usuario[0]['nome'].'</div>
								  </div>';
						}
					}

					$dados_antecipacao = DBRead('', 'tb_faturamento_antecipacao',"WHERE id_faturamento = '".$conteudo_consulta['id_faturamento']."' LIMIT 1");
					if($dados_antecipacao){

						echo '<hr>';	
						echo "<strong>Antecipação: </strong>";

						$data_referencia_antecipacao = new DateTime($dados_antecipacao[0]['data_referencia']);
						$mes_referencia_antecipacao = $data_referencia_antecipacao->format('m');
						$ano_referencia_antecipacao = $data_referencia_antecipacao->format('Y');

						echo '
						<div class="row">
							<div class="col-md-3">Referente a: '.$dados_meses[$mes_referencia_antecipacao].' de '.$ano_referencia_antecipacao.'</div>
							<div class="col-md-3">Quantidade de Dias: '.$dados_antecipacao[0]['qtd_dias'].'</div>
							<div class="col-md-3">Valor: R$ '.converteMoeda($dados_antecipacao[0]['valor'],'moeda').'</div>
							<div class="col-md-3"></div>';
							
							echo '
						</div>';

					}

					$dados_proporcional_cancelado = DBRead('', 'tb_faturamento_proporcional',"WHERE id_faturamento = '".$conteudo_consulta['id_faturamento']."' AND tipo = 1 LIMIT 1");
					if($dados_proporcional_cancelado){

						echo '<hr>';

						echo "<strong>Proporcional ao Cancelamento: </strong>";

						echo '
						<div class="row">
							<div class="col-md-12">Quantidade de Dias: '.$dados_proporcional_cancelado[0]['qtd_dias'].'</div>
						</div>';

					}

					$dados_proporcional_ativo = DBRead('', 'tb_faturamento_proporcional',"WHERE id_faturamento = '".$conteudo_consulta['id_faturamento']."' AND tipo = 2 LIMIT 1");
					if($dados_proporcional_ativo){

						echo '<hr>';
							
						echo "<strong>Proporcional a Ativação: </strong>";

						echo '
						<div class="row">
							<div class="col-md-12">Quantidade de Dias: '.$dados_proporcional_ativo[0]['qtd_dias'].'</div>
						</div>';

					}


		    echo '</div>
			</div>';				
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
    echo "</div>";
}

function relatorio_tabela_gestao_redes($referencia, $cancelados, $dados_meses, $tipo_cobranca){

	if($cancelados == 1){
		$legenda_cancelados = 'Sim';
	}else{
		$legenda_cancelados = 'Não';
		$consulta_cancelados = "AND a.status = '1'";
	}
	
	if($tipo_cobranca){
		if($tipo_cobranca == 'mensal_desafogo'){
			$legenda_tipo_cobranca = "Mensal com Desafogo";
		}else if($tipo_cobranca == 'cliente_base'){
			$legenda_tipo_cobranca = "Clientes na Base";
		}else if($tipo_cobranca == 'cliente_ativo'){
			$legenda_tipo_cobranca = "Clientes Ativos";
		}else if($tipo_cobranca == 'x_cliente_base'){
			$legenda_tipo_cobranca = "Até X Clientes na Base";
		}else if($tipo_cobranca == 'prepago'){
			$legenda_tipo_cobranca = "Pré_pago";
		}else{
			$legenda_tipo_cobranca = ucfirst($tipo_cobranca);
		}
		$consulta_tipo_cobranca = "AND a.tipo_cobranca = '".$tipo_cobranca."' ";
	}else{
		$legenda_tipo_cobranca = 'Todas';
		$consulta_tipo_cobranca = "";
	}

	$mes_ano = explode("-", $referencia);
	$mes = $mes_ano[1];
	$ano = $mes_ano[0];

	$periodo_amostra = "<span style=\"font-size: 14px;\"><strong>Referência: </strong>".$dados_meses[$mes]." de ".$ano.", <strong>Mostrar Cancelados: </strong>".$legenda_cancelados.", <strong>Tipo de Cobrança: </strong>".$legenda_tipo_cobranca."</span>";
	$gerado = "<span style=\"font-size: 14px;\"><strong>Gerado em: </strong> ".converteDataHora(getDataHora())."</span>";	

	echo "<div class=\"col-md-12\" style=\"padding: 0\">";
	echo "<legend style=\"text-align:center;\"><strong>Relatório de Faturamento - Gestão de Redes (Tabela)</strong><br>$gerado</legend>";
	echo "<legend style=\"text-align:center;\">$periodo_amostra</legend>";

	$dados_consulta = DBRead('', 'tb_faturamento a',"INNER JOIN tb_faturamento_contrato b ON a.id_faturamento = b.id_faturamento INNER JOIN tb_contrato_plano_pessoa c ON b.id_contrato_plano_pessoa = c.id_contrato_plano_pessoa INNER JOIN tb_pessoa d ON c.id_pessoa = d.id_pessoa INNER JOIN tb_plano e ON a.id_plano = e.id_plano WHERE e.cod_servico = 'gestao_redes' AND b.contrato_pai = '1' AND a.data_referencia = '".$referencia."' ".$consulta_cancelados." ".$consulta_tipo_cobranca." ", "a.*, b.*, c.nome_contrato, d.nome, a.status AS status_contrato, e.nome AS nome_plano");

	if($dados_consulta){
        echo '
            <table class="table table-hover dataTable" style="margin-bottom:0;">
                <thead>
                    <tr>
                        <th>Contrato</th>
                        <th>Plano</th>
                        <th>Tipo de Cobrança</th>
                        <th>Valor Contrato</th>
                        <th>Valor Inicial</th>
                        <th>Valor Excedente</th>
                        <th>Valor Plantão</th>
                        <th>Qtd Contratada</th>
                        <th>Qtd Efetuada</th>
                        <th>Qtd de Excedentes</th>
                        <th>Tipo de Plantão</th>
                        <th>Qtd de Plantões</th>
                        <th>Valor Total dos Plantões</th>
                        <th>Valor Total do Faturamento</th>
                        <th>Valor da Cobrança</th>					
                        <th>Valor Líquido da Cobrança</th>					
                    </tr>
                </thead>
                <tbody>
        ';              
	
		$contador_contratada = 0;
		$contador_efetuada = 0;
		$contador_excedente = 0;
		$contador_plantao = 0;
		$contador_valor = 0;
		$contador_valor_contrato = 0;
		$contador_qtd_contrato = 0;
		$contador_valor_total_plantao = 0;
		$contador_valor_total_faturamento = 0;
		$contador_valor_total_liquido = 0;

		foreach($dados_consulta as $dado_consulta){
			
			//NOME DO CONTRATO
			if($dado_consulta['nome_contrato']){
                $nome_contrato = " (".$dado_consulta['nome_contrato'].") ";
            }else{
                $nome_contrato = '';
            }

            $contrato = $dado_consulta['nome']." ".$nome_contrato;

			if($dado_consulta['tipo_cobranca'] == 'mensal_desafogo'){
				$tipo_cobranca = "Mensal com Desafogo";
			}else if($dado_consulta['tipo_cobranca'] == 'cliente_base'){
				$tipo_cobranca = "Clientes na Base";
			}else if($dado_consulta['tipo_cobranca'] == 'cliente_ativo'){
				$tipo_cobranca = "Clientes Ativos";
			}else if($dado_consulta['tipo_cobranca'] == 'x_cliente_base'){
				$tipo_cobranca = "Até X Clientes na Base";
			}else if($dado_consulta['tipo_cobranca'] == 'prepago'){
				$tipo_cobranca = "Pré-Pago";
			}else{
				$tipo_cobranca = ucfirst($dado_consulta['tipo_cobranca']);
			}
			
			if($dado_consulta['status'] != 1){
            	$style_cancelado = "style='color:#B22222;'";
            }else{
            	$style_cancelado = "";
            }
			
			$dados_valor_liquido = DBRead('', 'tb_faturamento a',"INNER JOIN tb_conta_receber b on a.id_faturamento = b.id_faturamento INNER JOIN tb_boleto c on b.id_boleto = c.id_boleto WHERE a.id_faturamento = '".$dado_consulta['id_faturamento']."' ", "c.titulo_valor as valor_liquido");

			if($dados_valor_liquido){
				$valor_liquido = $dados_valor_liquido[0]['valor_liquido'];
			}else{
				$valor_liquido = "0";
			}

			if($dado_consulta['tipo_plantao'] == '0'){
				$tipo_plantao = "N/D";
			}else{
				if($dado_consulta['tipo_plantao'] == '1'){
					$tipo_plantao = "30 em 30";
				}else if($dado_consulta['tipo_plantao'] == '2'){
					$tipo_plantao = "60 em 60";
				}else if($dado_consulta['tipo_plantao'] == '3'){
					$tipo_plantao = "60 em 60 proporcional";
				}else{
					$tipo_plantao = "Isento";
				}
			}

			echo "<tr>";
				echo "<td ".$style_cancelado.">".$contrato."</td>";
				echo "<td>".$dado_consulta['nome_plano']."</td>";
				echo "<td>".$tipo_cobranca."</td>";
				echo "<td data-order='".$dado_consulta['valor_total_contrato']."'>R$ ".converteMoeda($dado_consulta['valor_total_contrato'],'moeda')."</td>";
				echo "<td data-order='".$dado_consulta['valor_inicial_contrato']."'>R$ ".converteMoeda($dado_consulta['valor_inicial_contrato'],'moeda')."</td>";
				echo "<td data-order='".$dado_consulta['valor_excedente_contrato']."'>R$ ".converteMoeda($dado_consulta['valor_excedente_contrato'],'moeda')."</td>";
				echo "<td data-order='".$dado_consulta['valor_plantao_contrato']."'>R$ ".converteMoeda($dado_consulta['valor_plantao_contrato'],'moeda')."</td>";
				echo "<td>".$dado_consulta['qtd_contratada']."</td>";
				echo "<td>".$dado_consulta['qtd_efetuada']."</td>";
				echo "<td>".$dado_consulta['qtd_excedente']."</td>";			
				echo "<td>".$tipo_plantao."</td>";			
				echo "<td>".$dado_consulta['qtd_plantao']."</td>";			
				echo "<td data-order='".$dado_consulta['valor_plantao_total']."'>R$ ".converteMoeda($dado_consulta['valor_plantao_total'],'moeda')."</td>";	
				echo "<td data-order='".$dado_consulta['valor_total']."'>R$ ".converteMoeda($dado_consulta['valor_total'],'moeda')."</td>";				
				echo "<td data-order='".$dado_consulta['valor_cobranca']."'><strong>R$ ".converteMoeda($dado_consulta['valor_cobranca'],'moeda')."</strong></td>";				
				echo "<td data-order='".$valor_liquido."'><strong>R$ ".converteMoeda($valor_liquido,'moeda')."</strong></td>";				
			echo "</tr>";
			$contador_efetuada = $dado_consulta['qtd_efetuada'] + $contador_efetuada;
			$contador_excedente = $dado_consulta['qtd_excedente'] + $contador_excedente;
			$contador_plantao = $dado_consulta['qtd_plantao'] + $contador_plantao;
			$contador_valor = $dado_consulta['valor_cobranca'] + $contador_valor;
			$contador_valor_contrato = $dado_consulta['valor_total_contrato'] + $contador_valor_contrato;
			$contador_qtd_contrato = $dado_consulta['qtd_contratada'] + $contador_qtd_contrato;
			$contador_valor_total_plantao = $dado_consulta['valor_plantao_total'] + $contador_valor_total_plantao;
			$contador_valor_total_faturamento = $dado_consulta['valor_total'] + $contador_valor_total_faturamento;
			$contador_valor_total_liquido = $valor_liquido + $contador_valor_total_liquido;
		}

		echo '		
			</tbody>';
			echo "<tfoot>";
					echo '<tr>';
						echo '<th>Totais</th>';
						echo '<th></th>';
						echo '<th></th>';
						echo '<th>R$ '.converteMoeda($contador_valor_contrato,'moeda').'</th>';
						echo '<th></th>';
						echo '<th></th>';
						echo '<th></th>';
						echo '<th>'.$contador_qtd_contrato.'</th>';
						echo '<th>'.$contador_efetuada.'</th>';
						echo '<th>'.$contador_excedente.'</th>';
						echo '<th></th>';
						echo '<th>'.$contador_plantao.'</th>';
						echo '<th>R$ '.converteMoeda($contador_valor_total_plantao,'moeda').'</th>';
						echo '<th>R$ '.converteMoeda($contador_valor_total_faturamento,'moeda').'</th>';
						echo '<th>R$ '.converteMoeda($contador_valor,'moeda').'</th>';
						echo '<th>R$ '.converteMoeda($contador_valor_total_liquido,'moeda').'</th>';
					echo '</tr>';
				echo "</tfoot> ";
        echo '</table>';
        echo "<br><br><br>";

		echo "<script>
				$(document).ready(function(){
					var table = $('.dataTable').DataTable({
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

					var buttons = new $.fn.dataTable.Buttons(table, {
						buttons: [
							{
								extend: 'excelHtml5',
								text: '<i class=\"fa fa-file-excel-o\" aria-hidden=\"true\"></i> Exportar',
								filename: 'relatorio_faturamento_gesta_redes',
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

function relatorio_detalhado_gestao_redes($referencia, $cancelados, $dados_meses, $tipo_cobranca){
	
	if($cancelados == 1){
		$legenda_cancelados = 'Sim';
	}else{
		$legenda_cancelados = 'Não';
		$consulta_cancelados = "AND a.status = '1'";
	}

	if($tipo_cobranca){
		if($tipo_cobranca == 'mensal_desafogo'){
			$legenda_tipo_cobranca = "Mensal com Desafogo";
		}else if($tipo_cobranca == 'cliente_base'){
			$legenda_tipo_cobranca = "Clientes na Base";
		}else if($tipo_cobranca == 'cliente_ativo'){
			$legenda_tipo_cobranca = "Clientes Ativos";
		}else if($tipo_cobranca == 'x_cliente_base'){
			$legenda_tipo_cobranca = "Até X Clientes na Base";
		}else if($tipo_cobranca == 'prepago'){
			$legenda_tipo_cobranca = "Pré-Pago";
		}else{
			$legenda_tipo_cobranca = ucfirst($tipo_cobranca);
		}
		$consulta_tipo_cobranca = "AND a.tipo_cobranca = '".$tipo_cobranca."' ";
	}else{
		$legenda_tipo_cobranca = 'Todas';
		$consulta_tipo_cobranca = "";
	}

	$mes_ano = explode("-", $referencia);
	$mes = $mes_ano[1];
	$ano = $mes_ano[0];

	$periodo_amostra = "<span style=\"font-size: 14px;\"><strong>Referência: </strong>".$dados_meses[$mes]." de ".$ano.", <strong>Mostrar Cancelados: </strong>".$legenda_cancelados.", <strong>Tipo de Cobrança: </strong>".$legenda_tipo_cobranca."</span>";
	$gerado = "<span style=\"font-size: 14px;\"><strong>Gerado em: </strong> ".converteDataHora(getDataHora())."</span>";	

	echo "<div class=\"col-md-12\" style=\"padding: 0\">";
	echo "<legend style=\"text-align:center;\"><strong>Relatório de Faturamento - Gestão de Redes (Detalhado)</strong><br>$gerado</legend>";
	echo "<legend style=\"text-align:center;\">$periodo_amostra</legend>";

	$dados_consulta = DBRead('', 'tb_faturamento a',"INNER JOIN tb_faturamento_contrato b ON a.id_faturamento = b.id_faturamento INNER JOIN tb_contrato_plano_pessoa c ON b.id_contrato_plano_pessoa = c.id_contrato_plano_pessoa INNER JOIN tb_pessoa d ON c.id_pessoa = d.id_pessoa INNER JOIN tb_plano e ON a.id_plano = e.id_plano WHERE e.cod_servico = 'gestao_redes' AND b.contrato_pai = '1' AND a.data_referencia = '".$referencia."' ".$consulta_cancelados." ".$consulta_tipo_cobranca." ORDER BY d.nome", "a.*, b.*, c.nome_contrato, d.nome, a.status AS status_contrato, a.id_usuario AS id_usuario_faturamento, e.nome AS nome_plano");

	if($dados_consulta){
		foreach($dados_consulta as $conteudo_consulta){
			
			//NOME DO CONTRATO
			if($conteudo_consulta['nome_contrato']){
                $nome_contrato = " (".$conteudo_consulta['nome_contrato'].") ";
            }else{
                $nome_contrato = '';
            }
            $contrato = "<strong>".$conteudo_consulta['nome']."</strong> ".$nome_contrato;

			if($conteudo_consulta['tipo_cobranca'] == 'mensal_desafogo'){
				$tipo_cobranca = "Mensal com Desafogo";
			}else if($conteudo_consulta['tipo_cobranca'] == 'cliente_base'){
				$tipo_cobranca = "Clientes na Base";
			}else if($conteudo_consulta['tipo_cobranca'] == 'cliente_ativo'){
				$tipo_cobranca = "Clientes Ativos";
			}else if($conteudo_consulta['tipo_cobranca'] == 'x_cliente_base'){
				$tipo_cobranca = "Até X Clientes na Base";
			}else if($conteudo_consulta['tipo_cobranca'] == 'prepago'){
				$tipo_cobranca = "Pré-Pago";
			}else{
				$tipo_cobranca = ucfirst($conteudo_consulta['tipo_cobranca']);
			}

	        if($conteudo_consulta['status_contrato'] != 0){
	        	echo '<div class="panel panel-default">';
	        	
			}else{
				echo '<div class="panel panel-danger">';
			}

			$dados_usuario = DBRead('', 'tb_usuario a',"INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE id_usuario = '".$conteudo_consulta['id_usuario_faturamento']."'", "b.nome");
			
			$dados_conta_receber = DBRead('', 'tb_conta_receber', "WHERE id_faturamento = '".$conteudo_consulta['id_faturamento']."'");
			if($dados_conta_receber){
				$valor_liquido = $dados_conta_receber[0]['valor'];
			}else{
				$valor_liquido = '0.00';
			}

			if($conteudo_consulta['tipo_plantao'] == '0'){
				$tipo_plantao = "N/D";
			}else{
				if($conteudo_consulta['tipo_plantao'] == '1'){
					$tipo_plantao = "30 em 30";
				}else if($conteudo_consulta['tipo_plantao'] == '2'){
					$tipo_plantao = "60 em 60";
				}else if($conteudo_consulta['tipo_plantao'] == '3'){
					$tipo_plantao = "60 em 60 proporcional";
				}else{
					$tipo_plantao = "Isento";
				}
			}

			echo 
			'<div class="panel-heading clearfix">
                <div class="row"> 
                    <h3 class="panel-title text-left col-md-6" style="margin-top: 2px;">'.$contrato.'</h3>
                    <h3 class="panel-title text-right col-md-6" style="margin-top: 2px;">Gerado em '.converteDataHora($conteudo_consulta['data_gerado']).', por '.$dados_usuario[0]['nome'].'</h3>';
			            
	            echo '
			</div>
                </div>

			  	<div class="panel-body" id="panel_body_'.$conteudo_consulta['id_contrato_plano_pessoa'].'">
			    	<div class="row">
						<div class="col-md-3"><strong>Plano: </strong>'.$conteudo_consulta['nome_plano'].'</div>
						<div class="col-md-3"><strong>Tipo de Cobrança: </strong>'.$tipo_cobranca.'</div>
						<div class="col-md-3"><strong>Qtd. Clientes: </strong>'.$conteudo_consulta['qtd_clientes'].'</div>
						<div class="col-md-3"><strong>Valor Inicial do Contrato: </strong>R$ '.converteMoeda($conteudo_consulta['valor_inicial_contrato'],'moeda').'</div>
					</div>

					<div class="row">
						<div class="col-md-3"><strong>Valor do Contrato: </strong>R$ '.converteMoeda($conteudo_consulta['valor_total_contrato'],'moeda').'</div>
						<div class="col-md-3"><strong>Valor Unitário do Contrato: </strong>R$ '.converteMoeda($conteudo_consulta['valor_unitário_contrato'],'moeda').'</div>
						<div class="col-md-3"><strong>Valor Excedente do Contrato: </strong>R$ '.converteMoeda($conteudo_consulta['valor_excedente_contrato'],'moeda').'</div>
						<div class="col-md-3"><strong>Valor Unitário do Plantão: </strong>R$ '.converteMoeda($conteudo_consulta['valor_plantao_contrato'], 'moeda').'</div>
					</div>

					<div class="row">
						<div class="col-md-3"><strong>Quantidade Contratada: </strong>'.$conteudo_consulta['qtd_contratada'].'</div>
						<div class="col-md-3"><strong>Quantidade Realizada: </strong>'.$conteudo_consulta['qtd_efetuada'].'</div>
						<div class="col-md-3"><strong>Quantidade de Excedente: </strong>'.$conteudo_consulta['qtd_excedente'].'</div>
						<div class="col-md-3"><strong>Tipo de Plantão: </strong>'.$tipo_plantao.'</div>
						<div class="col-md-3"><strong>Quantidade de Plantões: </strong>'.$conteudo_consulta['qtd_plantao'].'</div>
					</div>
					<div class="row">
					<div class="col-md-3"><strong>Valor Total dos Plantões: </strong>R$ '.converteMoeda($conteudo_consulta['valor_plantao_total'], 'moeda').'</div>
					<div class="col-md-3"><strong>Valor Total do Faturamento: </strong>R$ '.converteMoeda($conteudo_consulta['valor_total'], 'moeda').'</div>
					<div class="col-md-3"><strong>Valor da Cobrança: </strong>R$ '.converteMoeda($conteudo_consulta['valor_cobranca'], 'moeda').'</div>
					<div class="col-md-3"><strong>Valor Líquido da Cobrança: </strong>R$ '.converteMoeda($valor_liquido, 'moeda').'</div>

					</div>';

					if($conteudo_consulta['acrescimo'] != '0.00' || $conteudo_consulta['desconto'] != '0.00'){

						$dados_ajuste = DBRead('', 'tb_faturamento_ajuste',"WHERE id_faturamento = '".$conteudo_consulta['id_faturamento']."' ");

						foreach($dados_ajuste as $conteudo_ajuste){
							echo '<hr>';
							if($conteudo_ajuste['tipo'] == 'acrescimo'){
								$tipo_de_ajuste = "Acréscimo";
							}else{
								$tipo_de_ajuste = "Desconto";
							}

							$dados_usuario = DBRead('', 'tb_usuario a',"INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE id_usuario = '".$conteudo_ajuste['id_usuario']."'", "b.nome");

							echo '
							<div class="row">
								<div class="col-md-3">'.$tipo_de_ajuste.': R$ '.converteMoeda($conteudo_ajuste['valor'],'moeda').'</div>
								<div class="col-md-3">Descrição: '.nl2br($conteudo_ajuste['descricao']).'</div>
								<div class="col-md-3">Data e Hora: '.converteDataHora($conteudo_ajuste['data']).'</div>
								<div class="col-md-3">Usuário: '.$dados_usuario[0]['nome'].'</div>
							</div>';
						}
					}
				echo '
				</div>
			</div>';				
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
    echo "</div>";
}

function relatorio_tabela_call_ativo($referencia, $cancelados, $dados_meses, $tipo_cobranca_select){

	if($cancelados == 1){
		$legenda_cancelados = 'Sim';
	}else{
		$legenda_cancelados = 'Não';
		$consulta_cancelados = "AND a.status = '1'";
	}

	if($tipo_cobranca_select){
		if($tipo_cobranca_select == 'mensal_desafogo'){
			$legenda_tipo_cobranca = "Mensal com Desafogo";
		}else if($tipo_cobranca_select == 'unitario'){
			$legenda_tipo_cobranca = "Unitário";
		}else if($tipo_cobranca_select == 'prepago'){
			$legenda_tipo_cobranca = "Pré-Pago";
		}else{
			$legenda_tipo_cobranca = ucfirst($tipo_cobranca_select);
		}
		$consulta_tipo_cobranca = "AND a.tipo_cobranca = '".$tipo_cobranca_select."' ";
	}else{
		$legenda_tipo_cobranca = 'Todas';
		$consulta_tipo_cobranca = "";
	}

	$mes_ano = explode("-", $referencia);
	$mes = $mes_ano[1];
	$ano = $mes_ano[0];

	$periodo_amostra = "<span style=\"font-size: 14px;\"><strong>Referência: </strong>".$dados_meses[$mes]." de ".$ano.", <strong>Mostrar Cancelados: </strong>".$legenda_cancelados.", <strong>Tipo de Cobrança: </strong>".$legenda_tipo_cobranca."</span>";
	$gerado = "<span style=\"font-size: 14px;\"><strong>Gerado em: </strong> ".converteDataHora(getDataHora())."</span>";	

	echo "<div class=\"col-md-12\" style=\"padding: 0\">";
	echo "<legend style=\"text-align:center;\"><strong>Relatório de Faturamento - Call Center - Ativo (Tabela)</strong><br>$gerado</legend>";
	echo "<legend style=\"text-align:center;\">$periodo_amostra</legend>";

	$dados_consulta = DBRead('', 'tb_faturamento a',"INNER JOIN tb_faturamento_contrato b ON a.id_faturamento = b.id_faturamento INNER JOIN tb_contrato_plano_pessoa c ON b.id_contrato_plano_pessoa = c.id_contrato_plano_pessoa INNER JOIN tb_pessoa d ON c.id_pessoa = d.id_pessoa INNER JOIN tb_plano e ON a.id_plano = e.id_plano WHERE e.cod_servico = 'call_ativo' AND b.contrato_pai = '1' AND a.data_referencia = '".$referencia."' ".$consulta_cancelados." ".$consulta_tipo_cobranca." ", "a.*, b.*, c.nome_contrato, d.nome, a.status AS status_contrato, e.nome AS nome_plano");

	if($dados_consulta){
        echo '
            <table class="table table-hover dataTable" style="margin-bottom:0;">
                <thead>
                    <tr>
                        <th>Contrato</th>
                        <th>Plano</th>
                        <th>Tipo de Cobrança</th>
                        <th>Valor Contrato</th>
                        <th>Valor Unitário</th>
                        <th>Valor Excedente</th>
                        <th>Qtd Contratada</th>
                        <th>Qtd Efetuada</th>
                        <th>Qtd de Excedentes</th>
                        <th>Valor Total do Faturamento</th>					
                        <th>Valor da Cobrança</th>					
                    </tr>
                </thead>
                <tbody>
        ';              
	
		$contador_contratada = 0;
		$contador_efetuada = 0;
		$contador_excedente = 0;
		$contador_valor = 0;
		$contador_valor_contrato = 0;
		$contador_qtd_contrato = 0;
		$contador_valor_total_faturamento = 0;

		foreach($dados_consulta as $dado_consulta){
			
			//NOME DO CONTRATO
			if($dado_consulta['nome_contrato']){
                $nome_contrato = " (".$dado_consulta['nome_contrato'].") ";
            }else{
                $nome_contrato = '';
            }

            $contrato = $dado_consulta['nome']." ".$nome_contrato;
			
			if($dado_consulta['tipo_cobranca'] == 'mensal_desafogo'){
				$tipo_cobranca = "Mensal com Desafogo";
			}else if($dado_consulta['tipo_cobranca'] == 'cliente_base'){
				$tipo_cobranca = "Clientes na Base";
			}else if($dado_consulta['tipo_cobranca'] == 'cliente_ativo'){
				$tipo_cobranca = "Clientes Ativos";
			}else if($dado_consulta['tipo_cobranca'] == 'x_cliente_base'){
				$tipo_cobranca = "Até X Clientes na Base";
			}else if($dado_consulta['tipo_cobranca'] == 'prepago'){
				$tipo_cobranca = "Pré-pago";
			}else{
				$tipo_cobranca = ucfirst($dado_consulta['tipo_cobranca']);
			}
			
			if($dado_consulta['status'] != 1){
            	$style_cancelado = "style='color:#B22222;'";
            }else{
            	$style_cancelado = "";
				if($dado_consulta['avulso'] == 1){
					$style_avulso = "style='color:#a82fca;'";
					$texto_avulso = ' - (Avulso)';
				}else{
					$style_avulso = "";
					$texto_avulso = '';
				}
            }

           	echo "<tr>";
				echo "<td ".$style_cancelado." ".$style_avulso.">".$contrato."".$texto_avulso."</td>";
				echo "<td>".$dado_consulta['nome_plano']."</td>";
				echo "<td>".$tipo_cobranca."</td>";
				echo "<td data-order='".$dado_consulta['valor_total_contrato']."'>R$ ".converteMoeda($dado_consulta['valor_total_contrato'],'moeda')."</td>";
				echo "<td data-order='".$dado_consulta['valor_unitario_contrato']."'>R$ ".converteMoeda($dado_consulta['valor_unitario_contrato'],'moeda')."</td>";
				echo "<td data-order='".$dado_consulta['valor_excedente_contrato']."'>R$ ".converteMoeda($dado_consulta['valor_excedente_contrato'],'moeda')."</td>";
				echo "<td>".$dado_consulta['qtd_contratada']."</td>";
				echo "<td>".$dado_consulta['qtd_efetuada']."</td>";
				echo "<td>".$dado_consulta['qtd_excedente']."</td>";
				echo "<td data-order='".$dado_consulta['valor_total']."'>R$ ".converteMoeda($dado_consulta['valor_total'],'moeda')."</td>";				
				echo "<td data-order='".$dado_consulta['valor_cobranca']."'><strong>R$ ".converteMoeda($dado_consulta['valor_cobranca'],'moeda')."</strong></td>";				
			echo "</tr>";
			$contador_efetuada = $dado_consulta['qtd_efetuada'] + $contador_efetuada;
			$contador_excedente = $dado_consulta['qtd_excedente'] + $contador_excedente;
			$contador_valor = $dado_consulta['valor_cobranca'] + $contador_valor;
			$contador_valor_contrato = $dado_consulta['valor_total_contrato'] + $contador_valor_contrato;
			$contador_qtd_contrato = $dado_consulta['qtd_contratada'] + $contador_qtd_contrato;
			$contador_valor_total_faturamento = $dado_consulta['valor_total'] + $contador_valor_total_faturamento;
		}

		echo '		
			</tbody>';
			echo "<tfoot>";
					echo '<tr>';
						echo '<th>Totais</th>';
						echo '<th></th>';
						echo '<th></th>';
						echo '<th>R$ '.converteMoeda($contador_valor_contrato,'moeda').'</th>';
						echo '<th></th>';
						echo '<th></th>';
						echo '<th>'.$contador_qtd_contrato.'</th>';
						echo '<th>'.$contador_efetuada.'</th>';
						echo '<th>'.$contador_excedente.'</th>';
						echo '<th>R$ '.converteMoeda($contador_valor_total_faturamento,'moeda').'</th>';
						echo '<th>R$ '.converteMoeda($contador_valor,'moeda').'</th>';
					echo '</tr>';
				echo "</tfoot> ";
        echo '</table>';
        echo "<br><br><br>";

		echo "<script>
				$(document).ready(function(){
					var table = $('.dataTable').DataTable({
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

					var buttons = new $.fn.dataTable.Buttons(table, {
						buttons: [
							{
								extend: 'excelHtml5',
								text: '<i class=\"fa fa-file-excel-o\" aria-hidden=\"true\"></i> Exportar',
								filename: 'relatorio_faturamento_call_ativo',
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

function relatorio_detalhado_call_ativo($referencia, $cancelados, $dados_meses, $tipo_cobranca_select){
	
	if($cancelados == 1){
		$legenda_cancelados = 'Sim';
	}else{
		$legenda_cancelados = 'Não';
		$consulta_cancelados = "AND a.status = '1'";
	}

	if($tipo_cobranca_select){
		if($tipo_cobranca_select == 'mensal_desafogo'){
			$legenda_tipo_cobranca = "Mensal com Desafogo";
		}else if($tipo_cobranca_select == 'unitario'){
			$legenda_tipo_cobranca = "Unitário";
		}else if($tipo_cobranca_select == 'prepago'){
			$legenda_tipo_cobranca = "Pré-Pago";
		}else{
			$legenda_tipo_cobranca = ucfirst($tipo_cobranca_select);
		}
		$consulta_tipo_cobranca = "AND a.tipo_cobranca = '".$tipo_cobranca_select."' ";
	}else{
		$legenda_tipo_cobranca = 'Todas';
		$consulta_tipo_cobranca = "";
	}

	$mes_ano = explode("-", $referencia);
	$mes = $mes_ano[1];
	$ano = $mes_ano[0];

	$periodo_amostra = "<span style=\"font-size: 14px;\"><strong>Referência: </strong>".$dados_meses[$mes]." de ".$ano.", <strong>Mostrar Cancelados: </strong>".$legenda_cancelados.", <strong>Tipo de Cobrança: </strong>".$legenda_tipo_cobranca."</span>";
	$gerado = "<span style=\"font-size: 14px;\"><strong>Gerado em: </strong> ".converteDataHora(getDataHora())."</span>";	

	echo "<div class=\"col-md-12\" style=\"padding: 0\">";
	echo "<legend style=\"text-align:center;\"><strong>Relatório de Faturamento - Call Center - Ativo (Detalhado)</strong><br>$gerado</legend>";
	echo "<legend style=\"text-align:center;\">$periodo_amostra</legend>";

	$dados_consulta = DBRead('', 'tb_faturamento a',"INNER JOIN tb_faturamento_contrato b ON a.id_faturamento = b.id_faturamento INNER JOIN tb_contrato_plano_pessoa c ON b.id_contrato_plano_pessoa = c.id_contrato_plano_pessoa INNER JOIN tb_pessoa d ON c.id_pessoa = d.id_pessoa INNER JOIN tb_plano e ON a.id_plano = e.id_plano WHERE e.cod_servico = 'call_ativo' AND b.contrato_pai = '1' AND a.data_referencia = '".$referencia."' ".$consulta_cancelados." ".$consulta_tipo_cobranca." ORDER BY d.nome", "a.*, b.*, c.nome_contrato, d.nome, a.status AS status_contrato, a.id_usuario AS id_usuario_faturamento, e.nome AS nome_plano");

	if($dados_consulta){
		foreach($dados_consulta as $conteudo_consulta){
			
			//NOME DO CONTRATO
			if($conteudo_consulta['nome_contrato']){
                $nome_contrato = " (".$conteudo_consulta['nome_contrato'].") ";
            }else{
                $nome_contrato = '';
            }
            $contrato = "<strong>".$conteudo_consulta['nome']."</strong> ".$nome_contrato;

			if($conteudo_consulta['tipo_cobranca'] == 'mensal_desafogo'){
				$tipo_cobranca = "Mensal com Desafogo (".$conteudo_consulta['desafogo_contrato']."%)";
			}else if($conteudo_consulta['tipo_cobranca'] == 'unitario'){
				$tipo_cobranca = "Unitário";
			}else if($conteudo_consulta['tipo_cobranca'] == 'prepago'){
				$tipo_cobranca = "Pré-Pago";
			}else{
				$tipo_cobranca = ucfirst($conteudo_consulta['tipo_cobranca']);
			}

	        if($conteudo_consulta['status_contrato'] != 0){
				if($conteudo_consulta['avulso'] == 1){
					$texto_avulso = ' - (Avulso)';
					echo '<div class="panel" style="border-color: #a82fca3b;">
						<div class="panel-heading clearfix"style="background-color: #a82fca3b; color: #a82fca;">';

				}else{
					$texto_avulso = '';
					echo '<div class="panel panel-default">
						<div class="panel-heading">';

				}
			}else{
				echo '<div class="panel panel-danger">
					<div class="panel-heading clearfix">';
			}

			$dados_usuario = DBRead('', 'tb_usuario a',"INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE id_usuario = '".$conteudo_consulta['id_usuario_faturamento']."'", "b.nome");
			
			echo 
			'
                <div class="row"> 
                    <h3 class="panel-title text-left col-md-6" style="margin-top: 2px;">'.$contrato.''.$texto_avulso.'</h3>
                    <h3 class="panel-title text-right col-md-6" style="margin-top: 2px;">Gerado em '.converteDataHora($conteudo_consulta['data_gerado']).', por '.$dados_usuario[0]['nome'].'</h3>';
			            
	            echo '
			</div>
                </div>

			  	<div class="panel-body" id="panel_body_'.$conteudo_consulta['id_contrato_plano_pessoa'].'">
			    	<div class="row">
						<div class="col-md-3"><strong>Plano: </strong>'.$conteudo_consulta['nome_plano'].'</div>
						<div class="col-md-3"><strong>Tipo de Cobrança: </strong>'.$tipo_cobranca.'</div>
						<div class="col-md-3"><strong>Quantidade Contratada: </strong>'.$conteudo_consulta['qtd_contratada'].'</div>
						<div class="col-md-3"><strong>Quantidade Realizada: </strong>'.$conteudo_consulta['qtd_efetuada'].'</div>
					</div>

					<div class="row">
						<div class="col-md-3"><strong>Valor Inicial do Contrato: </strong>R$ '.converteMoeda($conteudo_consulta['valor_inicial_contrato'],'moeda').'</div>
						<div class="col-md-3"><strong>Valor Unitário do Contrato: </strong>R$ '.converteMoeda($conteudo_consulta['valor_unitario_contrato'],'moeda').'</div>
						<div class="col-md-3"><strong>Valor Excedente do Contrato: </strong>R$ '.converteMoeda($conteudo_consulta['valor_excedente_contrato'],'moeda').'</div>
						<div class="col-md-3"><strong>Qtd. Clientes: </strong>'.$conteudo_consulta['qtd_clientes'].'</div>
					</div>

					<div class="row">
						<div class="col-md-3"><strong>Quantidade de Excedentes: </strong>'.$conteudo_consulta['qtd_excedente'].'</div>
						<div class="col-md-3"><strong>Valor Total do Contrato: </strong>R$ '.converteMoeda($conteudo_consulta['valor_total_contrato'],'moeda').'</div>
						<div class="col-md-3"><strong>Valor Total do Faturamento: </strong>R$ '.converteMoeda($conteudo_consulta['valor_total'],'moeda').'</div>
						<div class="col-md-3"><strong>Valor da Cobrança: </strong>R$ '.converteMoeda($conteudo_consulta['valor_cobranca'], 'moeda').'</div>
					</div>

				</div>
			</div>';				
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
    echo "</div>";
}

?>