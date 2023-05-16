<?php
require_once(__DIR__."/../class/System.php");

$primeiro_dia = new DateTime(getDataHora('data'));
$primeiro_dia->modify('first day of this month');
$primeiro_dia = $primeiro_dia->format('Y-m-d');
 
$ultimo_dia = new DateTime(getDataHora('data'));
$ultimo_dia->modify('last day of this month');
$ultimo_dia = $ultimo_dia->format('Y-m-d');

$data_inicial_referencia = new DateTime(getDataHora('data'));
$data_inicial_referencia->modify('first day of last month');

$id_usuario = $_SESSION['id_usuario'];
$dados = DBRead('', 'tb_usuario', "WHERE id_usuario = '$id_usuario'");
$perfil_sistema = $dados[0]['id_perfil_sistema'];  

if($perfil_sistema == 30){
	$tipo_relatorio = (!empty($_POST['tipo_relatorio'])) ? $_POST['tipo_relatorio'] : 3;
}else{
	$tipo_relatorio = (!empty($_POST['tipo_relatorio'])) ? $_POST['tipo_relatorio'] : 2;
}
$servico = (!empty($_POST['servico'])) ? $_POST['servico'] : 1;
$gerar = (!empty($_POST['gerar'])) ? 1 : 0;
$referencia_faturamento = (!empty($_POST['referencia_faturamento'])) ? $_POST['referencia_faturamento'] : $data_inicial_referencia->format('Y-m-d');

$referencia_rateio_mensal = (!empty($_POST['referencia_rateio_mensal'])) ? $_POST['referencia_rateio_mensal'] : $data_inicial_referencia->format('Y-m-d');

$referencia_rateio_conta_pagar = (!empty($_POST['referencia_rateio_conta_pagar'])) ? $_POST['referencia_rateio_conta_pagar'] : $data_inicial_referencia->format('Y-m-d');
 
$id_responsavel = (!empty($_POST['id_responsavel'])) ? $_POST['id_responsavel'] : '';

if($gerar){
	$collapse = '';
	$collapse_icon = 'plus';
}else{
	$collapse = 'in';
	$collapse_icon = 'minus';
}

if($tipo_relatorio == 1){
	$display_row_servico = 'style="display:none;"';
	$display_row_referencia_rateio_conta_pagar = 'style="display:none;"';
	$display_row_referencia_faturamento = '';
	$display_row_referencia_rateio_mensal = 'style="display:none;"';
	$display_row_responsavel = 'style="display:none;"';

}else if($tipo_relatorio == 2){
    $display_row_servico = '';
	$display_row_referencia_rateio_conta_pagar = 'style="display:none;"';
	$display_row_referencia_faturamento = '';
	$display_row_referencia_rateio_mensal = 'style="display:none;"';
	$display_row_responsavel = '';

}else if($tipo_relatorio == 3){
    $display_row_servico = 'style="display:none;"';
	$display_row_referencia_rateio_conta_pagar = 'style="display:none;"';
	$display_row_referencia_faturamento = '';
	$display_row_referencia_rateio_mensal = 'style="display:none;"';
	$display_row_responsavel = 'style="display:none;"';

}else if($tipo_relatorio == 4){
    $display_row_servico = 'style="display:none;"';
	$display_row_referencia_rateio_conta_pagar = '';
	$display_row_referencia_faturamento = 'style="display:none;"';
	$display_row_referencia_rateio_mensal = 'style="display:none;"';
	$display_row_responsavel = 'style="display:none;"';

}else if($tipo_relatorio == 5){
    $display_row_servico = 'style="display:none;"';
	$display_row_referencia_rateio_conta_pagar = 'style="display:none;"';
	$display_row_referencia_faturamento = 'style="display:none;"';
	$display_row_referencia_rateio_mensal = '';
	$display_row_responsavel = 'style="display:none;"';
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
?>
<style>
    @media print {
        .noprint { display:none; }
        body {
            font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
            padding-top: 0;
        }
    }
	.tooltip-inner {
		max-width: 100% !important;
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
	                    <h3 class="panel-title text-left pull-left" style="margin-top: 2px;">Relatório - Indicadores - Financeiro:</h3>
	                    <div class="panel-title text-right pull-right"><button data-toggle="collapse" data-target="#accordionRelatorio" class="btn btn-xs btn-info" type="button" title="Visualizar filtros"><i id="i_collapse" class="fa fa-<?=$collapse_icon?>"></i></button></div>
	                </div>
	                <div id="accordionRelatorio" class="panel-collapse collapse <?=$collapse?>">
	                	<div class="panel-body">
                            <div class="row">
                				<div class="col-md-12">
                					<div class="form-group">
								        <label for="">Tipo de Relatório:</label>
								        <select name="tipo_relatorio" id="tipo_relatorio" class="form-control input-sm">
											<?php
											if($perfil_sistema == 30){
											?>
                                            	<option value="3" <?php if($tipo_relatorio == '3'){ echo 'selected';}?>>Rentabilidade por Atendente</option>  
											<?php
											}else{
											?>
												<option value="2" <?php if($tipo_relatorio == '2'){ echo 'selected';}?>>Alterações de Faturamento (Contrato)</option>                                            
												<option value="1" <?php if($tipo_relatorio == '1'){ echo 'selected';}?>>Faturamento - Atendimentos</option>
												<option value="5" <?php if($tipo_relatorio == '5'){ echo 'selected';}?>>Rateio de Centro de Custos</option>
												<option value="4" <?php if($tipo_relatorio == '4'){ echo 'selected';}?>>Rateio de Contas a Pagar por Centro de Custos</option>
												<option value="3" <?php if($tipo_relatorio == '3'){ echo 'selected';}?>>Rentabilidade por Atendente</option>  
											<?php
											}
                                            ?>                                     
								        </select>
								    </div>
                				</div>
                			</div> 
                			<div class="row" id="row_servico" <?=$display_row_servico?>>
                				<div class="col-md-12">
                					<div class="form-group">
								        <label for="">Serviço:</label>
								        <select name="servico" id="servico" class="form-control input-sm">
											<option value="3" <?php if($servico == '3'){echo 'selected';}?>>Call Center - Ativo</option>
                                            <option value="1" <?php if($servico == '1'){echo 'selected';}?>>Call Center - Suporte</option>
                                            <option value="4" <?php if($servico == '4'){echo 'selected';}?>>Call Center - Monitoramento</option>
                                            <!-- <option value="2" <?php if($servico == '2'){echo 'selected';}?>>Gestão de Redes</option> -->
   								        </select>
								    </div>
                				</div>
							</div>
							<div class="row" id="row_referencia_faturamento" <?=$display_row_referencia_faturamento?>>
								<div class="col-md-12">
									<div class="form-group">
										<label>Data de Referência:</label>
										<select name="referencia_faturamento" class="form-control input-sm">
											<?php
											$dados_referencia = DBRead('', 'tb_faturamento', "WHERE status = 1 GROUP BY data_referencia ORDER BY data_referencia ASC", "data_referencia");

											if ($dados_referencia) {
												foreach ($dados_referencia as $conteudo_referencia) {
													$selected = $referencia_faturamento == $conteudo_referencia['data_referencia'] ? "selected" : "";
													$mes_ano = explode("-", $conteudo_referencia['data_referencia']);
													$mes = $mes_ano[1];
													$ano = $mes_ano[0];

													echo "<option value='" . $conteudo_referencia['data_referencia'] . "' " . $selected . ">" .$dados_meses[$mes]."/".$ano."</option>";
												}
											}
											?>
										</select>
									</div>
								</div>
                            </div>	
                            <div class="row" id="row_referencia_rateio_conta_pagar" <?=$display_row_referencia_rateio_conta_pagar?>>
								<div class="col-md-12">
									<div class="form-group">
									<label>Data de Referência:</label>
										<select name="referencia_rateio_conta_pagar" class="form-control input-sm">
											<?php
											$dados_referencia_rateio_conta_pagar = DBRead('', 'tb_conta_pagar', "WHERE CONCAT(DATE_FORMAT(data_vencimento,'%Y-%m'), '-01') IS NOT NULL
                                            GROUP BY data_referencia ORDER BY data_referencia ASC", "CONCAT(DATE_FORMAT(data_vencimento,'%Y-%m'), '-01') AS 'data_referencia'");

											if ($dados_referencia_rateio_conta_pagar) {
												foreach ($dados_referencia_rateio_conta_pagar as $conteudo_referencia_rateio_conta_pagar) {
													$selected = $referencia_rateio_conta_pagar == $conteudo_referencia_rateio_conta_pagar['data_referencia'] ? "selected" : "";
													$mes_ano = explode("-", $conteudo_referencia_rateio_conta_pagar['data_referencia']);
													$mes = $mes_ano[1];
													$ano = $mes_ano[0];

													echo "<option value='" . $conteudo_referencia_rateio_conta_pagar['data_referencia'] . "' " . $selected. ">" .$dados_meses[$mes]."/".$ano."</option>";
												}
											}
											?>
										</select>
									</div>
								</div>
							</div>						
							<div class="row" id="row_referencia_rateio_mensal" <?=$display_row_referencia_rateio_mensal?>>
								<div class="col-md-12">
									<div class="form-group">
									<label>Data de Referência:</label>
										<select name="referencia_rateio_mensal" class="form-control input-sm">
											<?php
											$dados_referencia_rateio_mensal = DBRead('', 'tb_centro_custos_rateio', "GROUP BY data_referencia ORDER BY data_referencia ASC", "data_referencia");

											if ($dados_referencia_rateio_mensal) {
												foreach ($dados_referencia_rateio_mensal as $conteudo_referencia_rateio_mensal) {
													$selected = $referencia_rateio_mensal == $conteudo_referencia_rateio_mensal['data_referencia'] ? "selected" : "";
													$mes_ano = explode("-", $conteudo_referencia_rateio_mensal['data_referencia']);
													$mes = $mes_ano[1];
													$ano = $mes_ano[0];

													echo "<option value='" . $conteudo_referencia_rateio_mensal['data_referencia'] . "' " . $selected . ">" .$dados_meses[$mes]."/".$ano."</option>";
												}
											}
											?>
										</select>
									</div>
								</div>
							</div>   

							<div class="row" id="row_responsavel" <?=$display_row_responsavel?>>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Responsável pelo Relacionamento:</label>
                                        <select class="form-control input-sm" id="id_responsavel" name="id_responsavel">
                                            <option value=''>Qualquer</option>
                                            <?php
                                                $dados_responsavel = DBRead('', 'tb_perfil_sistema a', "INNER JOIN tb_usuario b ON a.id_perfil_sistema = b.id_perfil_sistema INNER JOIN tb_pessoa c ON b.id_pessoa = c.id_pessoa WHERE a.id_perfil_sistema = 11 AND b.status = 1 ORDER BY c.nome ASC","b.id_usuario, c.nome");
                                                
                                                if ($dados_responsavel) {
                                                    foreach ($dados_responsavel as $conteudo_responsavel) {
														$selected = $id_responsavel == $conteudo_responsavel['id_usuario'] ? "selected" : "";
                                                        echo "<option value='".$conteudo_responsavel['id_usuario']."' ".$selected.">".$conteudo_responsavel['nome']."</option>";
                                                    }
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
		if($gerar){
            if($tipo_relatorio == 1){
                relatorio_faturamento_atendimento($referencia_faturamento, $dados_meses);
            }else if($tipo_relatorio == 2){
            	if($servico == '1'){
                	relatorio_alteracao_faturamento_call_suporte($referencia_faturamento, $dados_meses, $id_responsavel);
            	}else if($servico == '2'){
                	relatorio_alteracao_faturamento_gestao_redes($referencia_faturamento, $dados_meses, $id_responsavel);
            	}else if($servico == '3'){
                	relatorio_alteracao_faturamento_call_ativo($referencia_faturamento, $dados_meses, $id_responsavel);
            	}else if($servico == '4'){
                	relatorio_alteracao_faturamento_call_monitoramento($referencia_faturamento, $dados_meses, $id_responsavel);
            	}
			}else if($tipo_relatorio == 3){					
                relatorio_rentabilidade_atendente($referencia_faturamento, $dados_meses);
			}else if($tipo_relatorio == 4){					
                relatorio_rateio_conta_pagar($referencia_rateio_conta_pagar, $dados_meses);
			}else if($tipo_relatorio == 5){					
                relatorio_rateio_centro_custos_mensal($referencia_rateio_mensal, $dados_meses);
			}else{
				echo '<div class="alert alert-danger text-center">Erro ao exibir relatório!</div>';
			}
		}
		?>
	</div>
</div>

<script>
    $('#tipo_relatorio').on('change',function(){
		tipo_relatorio = $(this).val();
		if(tipo_relatorio == 1){
			$('#row_servico').hide();
			$('#row_referencia_rateio_conta_pagar').hide();
			$('#row_referencia_faturamento').show();
			$('#row_referencia_rateio_mensal').hide();
			$('#row_responsavel').hide();
		}else if(tipo_relatorio == 2){
            $('#row_servico').show();
			$('#row_referencia_rateio_conta_pagar').hide();
			$('#row_referencia_faturamento').show();
			$('#row_referencia_rateio_mensal').hide();
			$('#row_responsavel').show();
        }else if(tipo_relatorio == 3){
            $('#row_servico').hide();
			$('#row_referencia_rateio_conta_pagar').hide();
			$('#row_referencia_faturamento').show();
			$('#row_referencia_rateio_mensal').hide();
			$('#row_responsavel').hide();
        }else if(tipo_relatorio == 4){
            $('#row_servico').hide();
			$('#row_referencia_rateio_conta_pagar').show();
			$('#row_referencia_faturamento').hide();
			$('#row_referencia_rateio_mensal').hide();
			$('#row_responsavel').hide();
        }else if(tipo_relatorio == 5){
            $('#row_servico').hide();
			$('#row_referencia_rateio_conta_pagar').hide();
			$('#row_referencia_faturamento').hide();
			$('#row_referencia_rateio_mensal').show();
			$('#row_responsavel').hide();
        }
    });
	$('#accordionRelatorio').on('shown.bs.collapse', function(){
       $("#i_collapse").removeClass("fa fa-plus").addClass("fa fa-minus");
    });
    $('#accordionRelatorio').on('hidden.bs.collapse', function(){
       $("#i_collapse").removeClass("fa fa-minus").addClass("fa fa-plus");
    });
    
    $(document).ready(function(){
	    $('#aguarde').hide();
	    $('#resultado').show();
	    $("#gerar").prop("disabled", false);
	});
	$(function(){
        $('[data-toggle="tooltip"]').tooltip();
    });
	
</script>

<?php 

function relatorio_alteracao_faturamento_call_monitoramento($referencia, $dados_meses, $id_responsavel){
	if ($id_responsavel) {
        $filtro_id_responsavel = "AND c.id_responsavel = '".$id_responsavel."'";
        $dados_id_responsavel = DBRead('', 'tb_usuario a',"INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_usuario = '".$id_responsavel."' ", "b.nome");
        $legenda_id_responsavel = $dados_id_responsavel[0]['nome'];

    } else {
        $filtro_id_responsavel = "";
        $legenda_id_responsavel = 'Qualquer';
    }
	$legenda_servico = 'Call Center - Monitoramento';
	$filtro_servico = 'call_monitoramento';

	$mes_ano = explode("-", $referencia);
	$mes = $mes_ano[1];
	$ano = $mes_ano[0];

	$periodo_amostra = "<span style=\"font-size: 14px;\"><strong>Serviço: </strong>".$legenda_servico.", <strong>Responsável pelo Relacionamento: </strong>".$legenda_id_responsavel.", <strong>Referência: </strong>".$dados_meses[$mes]." de ".$ano."</span>";
	$gerado = "<span style=\"font-size: 14px;\"><strong>Gerado em: </strong> ".converteDataHora(getDataHora())."</span>";	

	echo "<div class=\"col-md-12\" style=\"padding: 0\">";
	echo "<legend style=\"text-align:center;\"><strong>Relatório de Indicadores - Alterações de Faturamento (Contrato)</strong><br>$gerado</legend>";
	echo "<legend style=\"text-align:center;\">$periodo_amostra</legend>";

	$dados_consulta = DBRead('', 'tb_faturamento a',"INNER JOIN tb_faturamento_contrato b ON a.id_faturamento = b.id_faturamento INNER JOIN tb_contrato_plano_pessoa c ON b.id_contrato_plano_pessoa = c.id_contrato_plano_pessoa INNER JOIN tb_pessoa d ON c.id_pessoa = d.id_pessoa INNER JOIN tb_plano e ON a.id_plano = e.id_plano WHERE e.cod_servico = '".$filtro_servico."' AND b.contrato_pai = '1' AND a.data_referencia = '".$referencia."' AND a.status = '1' AND a.adesao = '0' ", "a.*, b.*, c.nome_contrato, c.qtd_clientes, d.nome, a.status AS status_contrato");
	
	if($dados_consulta){

        $resultados_down = array();   
        $resultados_up = array();   
        $resultados_ajuste = array();   
        $resultados_ativacao = array();   
        $resultados_cancelamento = array();   
        $resultados_adesao = array();   
        $cont_down = 0;
        $cont_up = 0;
        $cont_ajuste = 0;
        $cont_ativacao = 0;
        $cont_cancelamento = 0;
        $cont_adesao = 0;
	
        $total_down = 0;
        $total_up = 0;
        $total_ajuste = 0;
        $total_ativacao = 0;
        $total_cancelamento = 0;
        $total_adesao = 0;

        $total_diferenca_qtd_down = 0;
        $total_diferenca_qtd_up = 0;
        $total_diferenca_qtd_ajuste = 0;
        $total_diferenca_qtd_ativacao = 0;
        $total_diferenca_qtd_cancelamento = 0;

        $total_diferenca_valor_down = 0;
        $total_diferenca_valor_up = 0;
        $total_diferenca_valor_ajuste = 0;
        $total_diferenca_valor_ativacao = 0;
        $total_diferenca_valor_cancelamento = 0;

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

           	$dados_planos = DBRead('','tb_plano',"WHERE id_plano = '".$dado_consulta['id_plano']."'");
			$plano = $dados_planos[0]['nome'];

			$data_inicial = new DateTime($ano."-".$mes."-01");
			$data_inicial->modify('first day of last month');
			
			$valor_total = $dado_consulta['valor_total_contrato'];
			$valor_inicial_contrato = $dado_consulta['valor_inicial_contrato'];
			$valor_unitario_contrato = $dado_consulta['valor_unitario_contrato'];
			$valor_excedente_contrato = $dado_consulta['valor_excedente_contrato'];

			$qtd_contratada = $dado_consulta['qtd_contratada'];
			$qtd_clientes = $dado_consulta['qtd_clientes'];
			$qtd_clientes_teto = $dado_consulta['qtd_clientes_teto'];
           	
			$dados_consulta_passado = DBRead('', 'tb_faturamento a',"INNER JOIN tb_faturamento_contrato b ON a.id_faturamento = b.id_faturamento INNER JOIN tb_contrato_plano_pessoa c ON b.id_contrato_plano_pessoa = c.id_contrato_plano_pessoa INNER JOIN tb_pessoa d ON c.id_pessoa = d.id_pessoa WHERE b.contrato_pai = '1' AND a.data_referencia = '".$data_inicial->format('Y-m-d')."' AND b.id_contrato_plano_pessoa = '".$dado_consulta['id_contrato_plano_pessoa']."' AND a.status = '1' AND a.adesao = '0' ", "a.*, b.*, c.nome_contrato, c.qtd_clientes, d.nome, c.status AS status_contrato");
			
			$dados_planos_passado = DBRead('','tb_plano',"WHERE id_plano = '".$dados_consulta_passado[0]['id_plano']."'");
			$plano_passado = $dados_planos_passado[0]['nome'];
			
			$valor_total_passado = $dados_consulta_passado[0]['valor_total_contrato'];

			$qtd_contratada_passado = $dados_consulta_passado[0]['qtd_contratada'];

			if($valor_total != $valor_total_passado && $dados_consulta_passado){
				if($valor_total < $valor_total_passado){

					// Down
					$resultados_down[$cont_down]['contrato'] = $contrato;
					$resultados_down[$cont_down]['plano'] = $plano;
					$resultados_down[$cont_down]['tipo_cobranca'] = $tipo_cobranca;
					$resultados_down[$cont_down]['valor_total'] = converteMoeda($valor_total,'moeda');
					$resultados_down[$cont_down]['valor_inicial_contrato'] = converteMoeda($valor_inicial_contrato,'moeda');
					$resultados_down[$cont_down]['valor_unitario_contrato'] = converteMoeda($valor_unitario_contrato,'moeda');
					$resultados_down[$cont_down]['valor_excedente_contrato'] = converteMoeda($valor_excedente_contrato,'moeda');
					$resultados_down[$cont_down]['qtd_contratada'] = $qtd_contratada;
					$resultados_down[$cont_down]['diferenca_qtd_contratada'] = $qtd_contratada - $qtd_contratada_passado;
					$resultados_down[$cont_down]['diferenca_valor'] = converteMoeda($valor_total - $valor_total_passado, 'moeda');
					
					$total_down = $total_down + ($dados_consulta_passado[0]['valor_total'] - $valor_total);
					
					$total_diferenca_qtd_down= $total_diferenca_qtd_down + ($qtd_contratada - $qtd_contratada_passado);
					$total_diferenca_valor_down = $total_diferenca_valor_down + ($valor_total - $valor_total_passado);

					$resultados_down[$cont_down]['qtd_clientes'] = $qtd_clientes;
					$resultados_down[$cont_down]['qtd_clientes_teto'] = $qtd_clientes_teto;

					$cont_down++;
				}else if($valor_total > $valor_total_passado){
					
					$qtd_contratada_passado = $dados_consulta_passado[0]['qtd_contratada'];

					if($plano != $plano_passado || $qtd_contratada != $qtd_contratada_passado){
						
						// UP
						$resultados_up[$cont_up]['contrato'] = $contrato;
						$resultados_up[$cont_up]['plano'] = $plano;
						$resultados_up[$cont_up]['tipo_cobranca'] = $tipo_cobranca;
						$resultados_up[$cont_up]['valor_total'] = converteMoeda($valor_total,'moeda');
						$resultados_up[$cont_up]['valor_inicial_contrato'] = converteMoeda($valor_inicial_contrato,'moeda');
						$resultados_up[$cont_up]['valor_unitario_contrato'] = converteMoeda($valor_unitario_contrato,'moeda');
						$resultados_up[$cont_up]['valor_excedente_contrato'] = converteMoeda($valor_excedente_contrato,'moeda');
						$resultados_up[$cont_up]['qtd_contratada'] = $qtd_contratada;
						$resultados_up[$cont_up]['diferenca_qtd_contratada'] = $qtd_contratada - $qtd_contratada_passado;
						$resultados_up[$cont_up]['diferenca_valor'] = converteMoeda($valor_total - $valor_total_passado, 'moeda');
						
						$total_up = $total_up + ($valor_total - $dados_consulta_passado[0]['valor_total_contrato']);

						$total_diferenca_qtd_up= $total_diferenca_qtd_up + ($qtd_contratada - $qtd_contratada_passado);
						$total_diferenca_valor_up = $total_diferenca_valor_up + ($valor_total - $valor_total_passado);

						$resultados_up[$cont_up]['qtd_clientes'] = $qtd_clientes;
						$resultados_up[$cont_up]['qtd_clientes_teto'] = $qtd_clientes_teto;
						
						$cont_up++;
					}else{
						
						// Ajuste
						$resultados_ajuste[$cont_ajuste]['contrato'] = $contrato;
						$resultados_ajuste[$cont_ajuste]['plano'] = $plano;
						$resultados_ajuste[$cont_ajuste]['tipo_cobranca'] = $tipo_cobranca;
						$resultados_ajuste[$cont_ajuste]['valor_total'] = converteMoeda($valor_total,'moeda');
						$resultados_ajuste[$cont_ajuste]['valor_inicial_contrato'] = converteMoeda($valor_inicial_contrato,'moeda');
						$resultados_ajuste[$cont_ajuste]['valor_unitario_contrato'] = converteMoeda($valor_unitario_contrato,'moeda');
						$resultados_ajuste[$cont_ajuste]['valor_excedente_contrato'] = converteMoeda($valor_excedente_contrato,'moeda');
						$resultados_ajuste[$cont_ajuste]['qtd_contratada'] = $qtd_contratada;
						$resultados_ajuste[$cont_ajuste]['diferenca_qtd_contratada'] = $qtd_contratada - $qtd_contratada_passado;
						$resultados_ajuste[$cont_ajuste]['diferenca_valor'] = converteMoeda($valor_total - $valor_total_passado, 'moeda');
						
						$total_ajuste = $total_ajuste + ($valor_total - $dados_consulta_passado[0]['valor_total_contrato']);

						$total_diferenca_qtd_ajuste = $total_diferenca_qtd_ajuste + ($qtd_contratada - $qtd_contratada_passado);
						$total_diferenca_valor_ajuste = $total_diferenca_valor_ajuste + ($valor_total - $valor_total_passado);

						$resultados_ajuste[$cont_ajuste]['qtd_clientes'] = $qtd_clientes;
						$resultados_ajuste[$cont_ajuste]['qtd_clientes_teto'] = $qtd_clientes_teto;
						
						$cont_ajuste++;
					}
					
				}
				
			}else if(!$dados_consulta_passado){

				// Ativação
				$resultados_ativacao[$cont_ativacao]['contrato'] = $contrato;
				$resultados_ativacao[$cont_ativacao]['plano'] = $plano;
				$resultados_ativacao[$cont_ativacao]['tipo_cobranca'] = $tipo_cobranca;
				$resultados_ativacao[$cont_ativacao]['valor_total'] = converteMoeda($valor_total,'moeda');
				$resultados_ativacao[$cont_ativacao]['valor_inicial_contrato'] = converteMoeda($valor_inicial_contrato,'moeda');
				$resultados_ativacao[$cont_ativacao]['valor_unitario_contrato'] = converteMoeda($valor_unitario_contrato,'moeda');
				$resultados_ativacao[$cont_ativacao]['valor_excedente_contrato'] = converteMoeda($valor_excedente_contrato,'moeda');
				$resultados_ativacao[$cont_ativacao]['qtd_contratada'] = $qtd_contratada;
				$resultados_ativacao[$cont_ativacao]['diferenca_qtd_contratada'] = $qtd_contratada;
				$resultados_ativacao[$cont_ativacao]['diferenca_valor'] = converteMoeda($valor_total, 'moeda');

				$total_ativacao = $total_ativacao + $valor_total;

				$total_diferenca_qtd_ativacao = $total_diferenca_qtd_ativacao + ($qtd_contratada);
				$total_diferenca_valor_ativacao = $total_diferenca_valor_ativacao + $valor_total;

				$resultados_ativacao[$cont_ativacao]['qtd_clientes'] = $qtd_clientes;
				$resultados_ativacao[$cont_ativacao]['qtd_clientes_teto'] = $qtd_clientes_teto;

				$cont_ativacao++;
			}
		}

		$data_inicial = new DateTime($ano."-".$mes."-01");
		$data_inicial->modify('first day of last month');

		$data_final = new DateTime($ano."-".$mes."-01");
		$data_final->modify('last day of last month');

		$dados_consulta_cancelamento = DBRead('', 'tb_faturamento a',"INNER JOIN tb_faturamento_contrato b ON a.id_faturamento = b.id_faturamento INNER JOIN tb_contrato_plano_pessoa c ON b.id_contrato_plano_pessoa = c.id_contrato_plano_pessoa INNER JOIN tb_pessoa d ON c.id_pessoa = d.id_pessoa INNER JOIN tb_plano e ON a.id_plano = e.id_plano WHERE e.cod_servico = '".$filtro_servico."' AND b.contrato_pai = '1' AND a.data_referencia = '".$data_inicial->format('Y-m-d')."' AND a.status = '1' AND a.adesao = '0' AND b.id_contrato_plano_pessoa NOT IN (SELECT b.id_contrato_plano_pessoa FROM bd_simples.tb_faturamento a INNER JOIN tb_faturamento_contrato b ON a.id_faturamento = b.id_faturamento INNER JOIN tb_contrato_plano_pessoa c ON b.id_contrato_plano_pessoa = c.id_contrato_plano_pessoa INNER JOIN tb_pessoa d ON c.id_pessoa = d.id_pessoa WHERE b.contrato_pai = '1' AND a.data_referencia = '".$referencia."' AND a.status = '1' AND a.adesao = '0') ", "a.*, b.*, c.nome_contrato, c.qtd_clientes, d.nome, c.status AS status_contrato");

		//$dados_consulta_cancelamento = DBRead('', 'tb_contrato_plano_pessoa a',"INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa INNER JOIN tb_plano c ON a.id_plano = c.id_plano WHERE a.status = 3 AND a.data_status >= '".$data_inicial->format('Y-m-d')."' AND a.data_status <= '".$data_final->format('Y-m-d')."' AND c.cod_servico = 'call_suporte' ", 'b.nome, a.nome_contrato, a.tipo_cobranca, a.desafogo AS desafogo_contrato, a.id_plano, a.valor_total, a.valor_inicial AS valor_inicial_contrato, a.valor_unitario AS valor_unitario_contrato, a.valor_excedente AS valor_excedente_contrato, a.qtd_contratada, a.data_status');

		if($dados_consulta_cancelamento){
			foreach($dados_consulta_cancelamento as $dado_consulta){
				//NOME DO CONTRATO
				if($dado_consulta['nome_contrato']){
					$nome_contrato = " (".$dado_consulta['nome_contrato'].") ";
				}else{
					$nome_contrato = '';
				}
				
				$contrato = $dado_consulta['nome']." ".$nome_contrato;

				if($dado_consulta['tipo_cobranca'] == 'mensal_desafogo'){
					$tipo_cobranca = "Mensal com Desafogo (".$dado_consulta['desafogo_contrato']."%)";
				}else if($dado_consulta['tipo_cobranca'] == 'unitario'){
					$tipo_cobranca = "Unitário";
				}else if($dado_consulta['tipo_cobranca'] == 'prepago'){
					$tipo_cobranca = "Pré-Pago";
				}else if($dado_consulta['tipo_cobranca'] == 'x_cliente_base'){
					$tipo_cobranca = "Até X Clientes na Base";
				}else{
					$tipo_cobranca = ucfirst($dado_consulta['tipo_cobranca']);
				}

				$dados_planos = DBRead('','tb_plano',"WHERE id_plano = '".$dado_consulta['id_plano']."'");
				$plano = $dados_planos[0]['nome'];
				
				$valor_total = $dado_consulta['valor_total_contrato'];
				$valor_inicial_contrato = $dado_consulta['valor_inicial_contrato'];
				$valor_unitario_contrato = $dado_consulta['valor_unitario_contrato'];
				$valor_excedente_contrato = $dado_consulta['valor_excedente_contrato'];

				$qtd_contratada = $dado_consulta['qtd_contratada'];
				$qtd_clientes = $dado_consulta['qtd_clientes'];
				$qtd_clientes_teto = $dado_consulta['qtd_clientes_teto'];
				
				$resultados_cancelamento[$cont_cancelamento]['contrato'] = $contrato;
				$resultados_cancelamento[$cont_cancelamento]['plano'] = $plano;
				$resultados_cancelamento[$cont_cancelamento]['tipo_cobranca'] = $tipo_cobranca;
				$resultados_cancelamento[$cont_cancelamento]['valor_total'] = converteMoeda($valor_total,'moeda');
				$resultados_cancelamento[$cont_cancelamento]['valor_inicial_contrato'] = converteMoeda($valor_inicial_contrato,'moeda');
				$resultados_cancelamento[$cont_cancelamento]['valor_unitario_contrato'] = converteMoeda($valor_unitario_contrato,'moeda');
				$resultados_cancelamento[$cont_cancelamento]['valor_excedente_contrato'] = converteMoeda($valor_excedente_contrato,'moeda');
				$resultados_cancelamento[$cont_cancelamento]['qtd_contratada'] = $qtd_contratada;
				$resultados_cancelamento[$cont_cancelamento]['diferenca_qtd_contratada'] = - $qtd_contratada;
				$resultados_cancelamento[$cont_cancelamento]['diferenca_valor'] = converteMoeda(-$valor_total, 'moeda');
				
				$total_cancelamento = $total_cancelamento + $valor_total;

				$total_diferenca_qtd_cancelamento = $total_diferenca_qtd_cancelamento - ($qtd_contratada);
				$total_diferenca_valor_cancelamento = $total_diferenca_valor_cancelamento - ($valor_total);

				$resultados_cancelamento[$cont_cancelamento]['qtd_clientes'] = $qtd_clientes;
				$resultados_cancelamento[$cont_cancelamento]['qtd_clientes_teto'] = $qtd_clientes_teto;

				$cont_cancelamento++;
			}
		}
       
		if($resultados_ativacao){
			echo "<legend style=\"text-align:center;\"><strong>Novos Contratos</strong></legend>";

				echo '
				<table class="table table-hover dataTable" style="margin-bottom:0;">
				      	<thead>
					        <tr>
		                        <th>Contrato</th>
		                        <th>Plano</th>
		                        <th>Tipo de Cobrança</th>
		                        <th>Valor Contrato</th>
		                        <th>Valor Inicial</th>
		                        <th>Valor Unitário</th>
		                        <th>Valor Excedente</th>
		                        <th>Qtd. Clientes</th>
		                        <th>Qtd. Contratada (Clientes)</th>
		                        <th>Qtd. Contratada</th>
		                        <th>Dif. Qtd. Contratada</th>
		                        <th>Dif. Valor Contrato</th>
		                    </tr>
				      	</thead>
				    <tbody>';  
					
					arsort($resultados_ativacao);
					
						foreach ($resultados_ativacao as $resultado) {
							echo "</tr>";								
								echo "<td>".$resultado['contrato']."</td>";
								echo "<td>".$resultado['plano']."</td>";
								echo "<td>".$resultado['tipo_cobranca']."</td>";
								echo "<td data-order='".converteMoeda($resultado['valor_total'],'banco')."'>R$".$resultado['valor_total']."</td>";
								echo "<td data-order='".converteMoeda($resultado['valor_inicial_contrato'],'banco')."'>R$".$resultado['valor_inicial_contrato']."</td>";
								echo "<td data-order='".converteMoeda($resultado['valor_unitario_contrato'],'banco')."'>R$".$resultado['valor_unitario_contrato']."</td>";
								echo "<td data-order='".converteMoeda($resultado['valor_excedente_contrato'],'banco')."'>R$".$resultado['valor_excedente_contrato']."</td>";
								echo "<td>".$resultado['qtd_clientes']."</td>";
								echo "<td>".$resultado['qtd_clientes_teto']."</td>";
								echo "<td>".$resultado['qtd_contratada']."</td>";
								echo "<td>".$resultado['diferenca_qtd_contratada']."</td>";
								echo "<td data-order='".converteMoeda($resultado['diferenca_valor'],'banco')."'>R$".$resultado['diferenca_valor']."</td>";
							echo "</tr>";
						}

			        echo '</tbody>';	

			        echo "<tfoot>";
						echo '<tr>';
							echo '<th></th>';
							echo '<th></th>';
							echo '<th></th>';
							echo '<th></th>';
							echo '<th></th>';
							echo '<th></th>';
							echo '<th></th>';
							echo '<th></th>';
							echo '<th></th>';
							echo '<th>Totais</th>';
							echo '<th>'.$total_diferenca_qtd_ativacao.'</th>';
							echo '<th>R$'.converteMoeda($total_diferenca_valor_ativacao, 'moeda').'</th>';
						echo '</tr>';
					echo "</tfoot> ";	

				echo '</table><hr>';

		}

		if($resultados_up){
			echo "<legend style=\"text-align:center;\"><strong>Upgrade</strong></legend>";
				
				echo '
				<table class="table table-hover dataTable" style="margin-bottom:0;">
				      	<thead>
					        <tr>
		                        <th>Contrato</th>
		                        <th>Plano</th>
		                        <th>Tipo de Cobrança</th>
		                        <th>Valor Contrato</th>
		                        <th>Valor Inicial</th>
		                        <th>Valor Unitário</th>
		                        <th>Valor Excedente</th>
		                        <th>Qtd. Clientes</th>
		                        <th>Qtd. Contratada (Clientes)</th>
		                        <th>Qtd. Contratada</th>
		                        <th>Dif. Qtd. Contratada</th>
		                        <th>Dif. Valor Contrato</th>
		                    </tr>
				      	</thead>
				    <tbody>';  
							
					arsort($resultados_up);

						foreach ($resultados_up as $resultado) {	
							echo "</tr>";								
                                echo "<td>".$resultado['contrato']."</td>";
                                echo "<td>".$resultado['plano']."</td>";
                                echo "<td>".$resultado['tipo_cobranca']."</td>";
                                echo "<td data-order='".converteMoeda($resultado['valor_total'],'banco')."'>R$".$resultado['valor_total']."</td>";
                                echo "<td data-order='".converteMoeda($resultado['valor_inicial_contrato'],'banco')."'>R$".$resultado['valor_inicial_contrato']."</td>";
                                echo "<td data-order='".converteMoeda($resultado['valor_unitario_contrato'],'banco')."'>R$".$resultado['valor_unitario_contrato']."</td>";
                                echo "<td data-order='".converteMoeda($resultado['valor_excedente_contrato'],'banco')."'>R$".$resultado['valor_excedente_contrato']."</td>";
								echo "<td>".$resultado['qtd_clientes']."</td>";
								echo "<td>".$resultado['qtd_clientes_teto']."</td>";
                                echo "<td>".$resultado['qtd_contratada']."</td>";
                                echo "<td>".$resultado['diferenca_qtd_contratada']."</td>";
                                echo "<td data-order='".converteMoeda($resultado['diferenca_valor'],'banco')."'>R$".$resultado['diferenca_valor']."</td>";
                            echo "</tr>";
						}

			        echo '</tbody>';	

			        echo "<tfoot>";
						echo '<tr>';
							echo '<th></th>';
							echo '<th></th>';
							echo '<th></th>';
							echo '<th></th>';
							echo '<th></th>';
							echo '<th></th>';
							echo '<th></th>';
							echo '<th></th>';
							echo '<th></th>';
							echo '<th>Totais</th>';
							echo '<th>'.$total_diferenca_qtd_up.'</th>';
							echo '<th>R$'.converteMoeda($total_diferenca_valor_up, 'moeda').'</th>';
						echo '</tr>';
					echo "</tfoot> ";	

				echo '</table><hr>';
		}	

		if($resultados_ajuste){
			echo "<legend style=\"text-align:center;\"><strong>Ajuste Anual</strong></legend>";

				echo '
				<table class="table table-hover dataTable" style="margin-bottom:0;">
				      	<thead>
					        <tr>
		                        <th>Contrato</th>
		                        <th>Plano</th>
		                        <th>Tipo de Cobrança</th>
		                        <th>Valor Contrato</th>
		                        <th>Valor Inicial</th>
		                        <th>Valor Unitário</th>
		                        <th>Valor Excedente</th>
		                        <th>Qtd. Clientes</th>
		                        <th>Qtd. Contratada (Clientes)</th>
		                        <th>Qtd. Contratada</th>
		                        <th>Dif. Qtd. Contratada</th>
		                        <th>Dif. Valor Contrato</th>
		                    </tr>
				      	</thead>
				    <tbody>';  
					
					arsort($resultados_ajuste);
					
						foreach ($resultados_ajuste as $resultado) {
							echo "</tr>";								
                                echo "<td>".$resultado['contrato']."</td>";
                                echo "<td>".$resultado['plano']."</td>";
                                echo "<td>".$resultado['tipo_cobranca']."</td>";
                                echo "<td data-order='".converteMoeda($resultado['valor_total'],'banco')."'>R$".$resultado['valor_total']."</td>";
                                echo "<td data-order='".converteMoeda($resultado['valor_inicial_contrato'],'banco')."'>R$".$resultado['valor_inicial_contrato']."</td>";
                                echo "<td data-order='".converteMoeda($resultado['valor_unitario_contrato'],'banco')."'>R$".$resultado['valor_unitario_contrato']."</td>";
                                echo "<td data-order='".converteMoeda($resultado['valor_excedente_contrato'],'banco')."'>R$".$resultado['valor_excedente_contrato']."</td>";
								echo "<td>".$resultado['qtd_clientes']."</td>";
								echo "<td>".$resultado['qtd_clientes_teto']."</td>";
                                echo "<td>".$resultado['qtd_contratada']."</td>";
                                echo "<td>".$resultado['diferenca_qtd_contratada']."</td>";
                                echo "<td data-order='".converteMoeda($resultado['diferenca_valor'],'banco')."'>R$".$resultado['diferenca_valor']."</td>";
                            echo "</tr>";
						}

			        echo '</tbody>';

			        echo "<tfoot>";
						echo '<tr>';
							echo '<th></th>';
							echo '<th></th>';
							echo '<th></th>';
							echo '<th></th>';
							echo '<th></th>';
							echo '<th></th>';
							echo '<th></th>';
							echo '<th></th>';
							echo '<th></th>';
							echo '<th>Totais</th>';
							echo '<th>'.$total_diferenca_qtd_ajuste.'</th>';
							echo '<th>R$'.converteMoeda($total_diferenca_valor_ajuste).'</th>';
						echo '</tr>';
					echo "</tfoot> ";	

				echo '</table><hr>';
		}

		if($resultados_down){
        	echo "<legend style=\"text-align:center;\"><strong>Downgrade</strong></legend>";
			
			echo '
				<table class="table table-hover dataTable" style="margin-bottom:0;">
				      	<thead>
					        <tr>
		                        <th>Contrato</th>
		                        <th>Plano</th>
		                        <th>Tipo de Cobrança</th>
		                        <th>Valor Contrato</th>
		                        <th>Valor Inicial</th>
		                        <th>Valor Unitário</th>
		                        <th>Valor Excedente</th>
		                        <th>Qtd. Clientes</th>
		                        <th>Qtd. Contratada (Clientes)</th>
		                        <th>Qtd. Contratada</th>
		                        <th>Dif. Qtd. Contratada</th>
		                        <th>Dif. Valor Contrato</th>
		                    </tr>
				      	</thead>
				    <tbody>';  
							
					arsort($resultados_down);

						foreach ($resultados_down as $resultado) {
							echo "</tr>";								
                                echo "<td>".$resultado['contrato']."</td>";
                                echo "<td>".$resultado['plano']."</td>";
                                echo "<td>".$resultado['tipo_cobranca']."</td>";
                                echo "<td data-order='".converteMoeda($resultado['valor_total'],'banco')."'>R$".$resultado['valor_total']."</td>";
                                echo "<td data-order='".converteMoeda($resultado['valor_inicial_contrato'],'banco')."'>R$".$resultado['valor_inicial_contrato']."</td>";
                                echo "<td data-order='".converteMoeda($resultado['valor_unitario_contrato'],'banco')."'>R$".$resultado['valor_unitario_contrato']."</td>";
                                echo "<td data-order='".converteMoeda($resultado['valor_excedente_contrato'],'banco')."'>R$".$resultado['valor_excedente_contrato']."</td>";
								echo "<td>".$resultado['qtd_clientes']."</td>";
								echo "<td>".$resultado['qtd_clientes_teto']."</td>";
                                echo "<td>".$resultado['qtd_contratada']."</td>";
                                echo "<td>".$resultado['diferenca_qtd_contratada']."</td>";
                                echo "<td data-order='".converteMoeda($resultado['diferenca_valor'],'banco')."'>R$".$resultado['diferenca_valor']."</td>";
                            echo "</tr>";
						}

			        echo '</tbody>';

			        echo "<tfoot>";
						echo '<tr>';
							echo '<th></th>';
							echo '<th></th>';
							echo '<th></th>';
							echo '<th></th>';
							echo '<th></th>';
							echo '<th></th>';
							echo '<th></th>';
							echo '<th></th>';
							echo '<th></th>';
							echo '<th>Totais</th>';
							echo '<th>'.$total_diferenca_qtd_down.'</th>';
							echo '<th>R$'.converteMoeda($total_diferenca_valor_down, 'moeda').'</th>';
						echo '</tr>';
					echo "</tfoot> ";	

				echo '</table><hr>';
		}
		
		if($resultados_cancelamento){
			echo "<legend style=\"text-align:center;\"><strong>Cancelamento</strong></legend>";
			
				echo '
				<table class="table table-hover dataTable" style="margin-bottom:0;">
					      	<thead>
						        <tr>
			                        <th>Contrato</th>
			                        <th>Plano</th>
			                        <th>Tipo de Cobrança</th>
			                        <th>Valor Contrato</th>
			                        <th>Valor Inicial</th>
			                        <th>Valor Unitário</th>
			                        <th>Valor Excedente</th>
									<th>Qtd. Clientes</th>
									<th>Qtd. Contratada (Clientes)</th>
			                        <th>Qtd. Contratada</th>
			                        <th>Dif. Qtd. Contratada</th>
			                        <th>Dif. Valor Contrato</th>
			                    </tr>
					      	</thead>
					    <tbody>';  
								
						arsort($resultados_cancelamento);

							foreach ($resultados_cancelamento as $resultado) {
								echo "</tr>";								
                                    echo "<td>".$resultado['contrato']."</td>";
                                    echo "<td>".$resultado['plano']."</td>";
                                    echo "<td>".$resultado['tipo_cobranca']."</td>";
                                    echo "<td data-order='".converteMoeda($resultado['valor_total'],'banco')."'>R$".$resultado['valor_total']."</td>";
                                    echo "<td data-order='".converteMoeda($resultado['valor_inicial_contrato'],'banco')."'>R$".$resultado['valor_inicial_contrato']."</td>";
                                    echo "<td data-order='".converteMoeda($resultado['valor_unitario_contrato'],'banco')."'>R$".$resultado['valor_unitario_contrato']."</td>";
                                    echo "<td data-order='".converteMoeda($resultado['valor_excedente_contrato'],'banco')."'>R$".$resultado['valor_excedente_contrato']."</td>";
									echo "<td>".$resultado['qtd_clientes']."</td>";
									echo "<td>".$resultado['qtd_clientes_teto']."</td>";
                                    echo "<td>".$resultado['qtd_contratada']."</td>";
                                    echo "<td>".$resultado['diferenca_qtd_contratada']."</td>";
                                    echo "<td data-order='".converteMoeda($resultado['diferenca_valor'],'banco')."'>R$".$resultado['diferenca_valor']."</td>";
                                echo "</tr>";
							}

				        echo '</tbody>';

				        echo "<tfoot>";
							echo '<tr>';
								echo '<th></th>';
								echo '<th></th>';
								echo '<th></th>';
								echo '<th></th>';
								echo '<th></th>';
								echo '<th></th>';
								echo '<th></th>';
								echo '<th></th>';
								echo '<th></th>';
								echo '<th>Totais</th>';
								echo '<th>'.$total_diferenca_qtd_cancelamento.'</th>';
								echo '<th>R$'.converteMoeda($total_diferenca_valor_cancelamento, 'moeda').'</th>';
							echo '</tr>';
						echo "</tfoot> ";	

				echo '</table><hr>';
		}		     

		$dados_consulta_adesao = DBRead('', 'tb_faturamento a',"INNER JOIN tb_faturamento_contrato b ON a.id_faturamento = b.id_faturamento INNER JOIN tb_contrato_plano_pessoa c ON b.id_contrato_plano_pessoa = c.id_contrato_plano_pessoa INNER JOIN tb_pessoa d ON c.id_pessoa = d.id_pessoa INNER JOIN tb_plano e ON a.id_plano = e.id_plano WHERE e.cod_servico = '".$filtro_servico."' AND a.data_referencia = '".$referencia."' AND a.adesao = '1' ", "a.*, b.*, c.nome_contrato, d.nome, a.valor_adesao AS valor_adesao_faturamento");

		if($dados_consulta_adesao){
			foreach($dados_consulta_adesao as $dado_consulta){
				//NOME DO CONTRATO
				if($dado_consulta['nome_contrato']){
					$nome_contrato = " (".$dado_consulta['nome_contrato'].") ";
				}else{
					$nome_contrato = '';
				}				
				$contrato = $dado_consulta['nome']." ".$nome_contrato;

				$dados_planos = DBRead('','tb_plano',"WHERE id_plano = '".$dado_consulta['id_plano']."'");
				$plano = $dados_planos[0]['nome'];
				
				$valor_adesao_faturamento = $dado_consulta['valor_adesao_faturamento'];
				
				$resultados_adesao[$cont_adesao]['contrato'] = $contrato;
				$resultados_adesao[$cont_adesao]['plano'] = $plano;
				$resultados_adesao[$cont_adesao]['valor_adesao_faturamento'] = converteMoeda($valor_adesao_faturamento,'moeda');
				
				$total_adesao = $total_adesao + $valor_adesao_faturamento;
				$cont_adesao++;
			}

			echo "<legend style=\"text-align:center;\"><strong>Adesões</strong></legend>";
			
			echo '
			<table class="table table-hover dataTable" style="margin-bottom:0;">
						<thead>
							<tr>
								<th>Contrato</th>
								<th>Plano</th>
								<th>Valor da Adesão</th>
							</tr>
						</thead>
					<tbody>';  

					arsort($resultados_adesao);

					foreach ($resultados_adesao as $resultado) {
						echo "</tr>";								
							echo "<td>".$resultado['contrato']."</td>";
							echo "<td>".$resultado['plano']."</td>";
							echo "<td data-order='".converteMoeda($resultado['valor_adesao_faturamento'],'banco')."'>R$".$resultado['valor_adesao_faturamento']."</td>";
						echo "</tr>";
					}
					echo "<tfoot>";
						echo '<tr>';
							echo '<th></th>';
							echo '<th>Totais</th>';
							echo '<th>R$'.converteMoeda($total_adesao, 'moeda').'</th>';
						echo '</tr>';
					echo "</tfoot> ";	
				echo '</tbody>';					          
			echo '</table>
			
			<hr>';
		}

		echo "<legend style=\"text-align:center;\"><strong>Totalizador de Valores (com Adesões)</strong></legend>";
			
		echo '
		<table class="table table-hover dataTable" style="margin-bottom:0;">
			      	<thead>
				        <tr>
	                        <th style="text-align:center;">Novos Contratos</th>
	                        <th style="text-align:center;">Upgrade</th>
	                        <th style="text-align:center;">Ajuste Anual</th>
	                        <th style="text-align:center;">Downgrade</th>
	                        <th style="text-align:center;">Cancelamento</th>
	                        <th style="text-align:center;">Adesões</th>
	                        <th style="text-align:center;">Balanço Final</th>
	                    </tr>
			      	</thead>
			    <tbody>';  

			    $balanço = ($total_up + $total_ativacao + $total_ajuste + $total_adesao) - ($total_down + $total_cancelamento);

				echo "<tr>";
					echo "<td style='text-align:center;'>R$".converteMoeda($total_ativacao,'moeda')."</td>";
					echo "<td style='text-align:center;'>R$".converteMoeda($total_up,'moeda')."</td>";
					echo "<td style='text-align:center;'>R$".converteMoeda($total_ajuste,'moeda')."</td>";
					echo "<td style='text-align:center;'>R$".converteMoeda($total_down,'moeda')."</td>";
					echo "<td style='text-align:center;'>R$".converteMoeda($total_cancelamento,'moeda')."</td>";
					echo "<td style='text-align:center;'>R$".converteMoeda($total_adesao,'moeda')."</td>";
					if($balanço >=0){
						echo "<td class='success' style='text-align:center;'><strong>R$".converteMoeda($balanço,'moeda')."</strong></td>";
					}else{
						echo "<td class='danger' style='text-align:center;'><strong>R$".converteMoeda($balanço,'moeda')."</strong></td>";
					}
				echo "</tr>";
	        echo '</tbody>';					          
		echo '</table><hr>';

		echo "<legend style=\"text-align:center;\"><strong>Totalizador de Valores (sem Adesões)</strong></legend>";
			
		echo '
		<table class="table table-hover dataTable" style="margin-bottom:0;">
			      	<thead>
				        <tr>
	                        <th style="text-align:center;">Novos Contratos</th>
	                        <th style="text-align:center;">Upgrade</th>
	                        <th style="text-align:center;">Ajuste Anual</th>
	                        <th style="text-align:center;">Downgrade</th>
	                        <th style="text-align:center;">Cancelamento</th>
	                        <th style="text-align:center;">Balanço Final</th>
	                    </tr>
			      	</thead>
			    <tbody>';  

			    $balanço = ($total_up + $total_ativacao+$total_ajuste) - ($total_down + $total_cancelamento);

				echo "<tr>";
					echo "<td style='text-align:center;'>R$".converteMoeda($total_ativacao,'moeda')."</td>";
					echo "<td style='text-align:center;'>R$".converteMoeda($total_up,'moeda')."</td>";
					echo "<td style='text-align:center;'>R$".converteMoeda($total_ajuste,'moeda')."</td>";
					echo "<td style='text-align:center;'>R$".converteMoeda($total_down,'moeda')."</td>";
					echo "<td style='text-align:center;'>R$".converteMoeda($total_cancelamento,'moeda')."</td>";
					if($balanço >=0){
						echo "<td class='success' style='text-align:center;'><strong>R$".converteMoeda($balanço,'moeda')."</strong></td>";
					}else{
						echo "<td class='danger' style='text-align:center;'><strong>R$".converteMoeda($balanço,'moeda')."</strong></td>";
					}
				echo "</tr>";
	        echo '</tbody>';					          
		echo '</table><hr>';


		echo "<div class=\"col-md-4 col-md-offset-4\" style=\"padding: 0\">";
			echo "<legend style=\"text-align:center;\"><strong>Totalizador de Quantidades</strong></legend>";
			

			echo '
			<table class="table table-hover dataTable" style="margin-bottom:0;">
				      	<thead>
					        <tr>
		                        <th style="text-align:center;">Quantidades Contratadas</th>
		                    </tr>
				      	</thead>
				    <tbody>';  

				    $balanço_diferenca_qtd = $total_diferenca_qtd_up + $total_diferenca_qtd_ativacao + $total_diferenca_qtd_ajuste +$total_diferenca_qtd_down + $total_diferenca_qtd_cancelamento;

					echo "<tr>";
						
						if($balanço_diferenca_qtd >=0){
							echo "<td class='success' style=\"text-align:center;\"><strong>".$balanço_diferenca_qtd."</strong></td>";
						}else{
							echo "<td class='danger' style=\"text-align:center;\"><strong>".$balanço_diferenca_qtd."</strong></td>";
						}

						
					echo "</tr>";
		        echo '</tbody>';					          
			echo '</table>';
        echo "<br><br><br>";
		echo "</div>";


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

function relatorio_alteracao_faturamento_gestao_redes($referencia, $dados_meses){

	$legenda_servico = 'Gestão de Redes';
	$filtro_servico = 'gestao_redes';

	$mes_ano = explode("-", $referencia);
	$mes = $mes_ano[1];
	$ano = $mes_ano[0];

	$periodo_amostra = "<span style=\"font-size: 14px;\"><strong>Serviço: </strong>".$legenda_servico.", <strong>Referência: </strong>".$dados_meses[$mes]." de ".$ano."</span>";
	$gerado = "<span style=\"font-size: 14px;\"><strong>Gerado em: </strong> ".converteDataHora(getDataHora())."</span>";	

	echo "<div class=\"col-md-12\" style=\"padding: 0\">";
	echo "<legend style=\"text-align:center;\"><strong>Relatório de Indicadores - Alterações de Faturamento (Contrato)</strong><br>$gerado</legend>";
	echo "<legend style=\"text-align:center;\">$periodo_amostra</legend>";

	$dados_consulta = DBRead('', 'tb_faturamento a',"INNER JOIN tb_faturamento_contrato b ON a.id_faturamento = b.id_faturamento INNER JOIN tb_contrato_plano_pessoa c ON b.id_contrato_plano_pessoa = c.id_contrato_plano_pessoa INNER JOIN tb_pessoa d ON c.id_pessoa = d.id_pessoa INNER JOIN tb_plano e ON a.id_plano = e.id_plano WHERE e.cod_servico = '".$filtro_servico."' AND b.contrato_pai = '1' AND a.data_referencia = '".$referencia."' AND a.status = '1' ", "a.*, b.*, c.nome_contrato, c.valor_adesao, d.nome, a.status AS status_contrato");
	
	if($dados_consulta){
 
        $resultados_ativacao = array();   

        $cont_ativacao = 0;
	
        $total_valor_ativacao = 0;
        $total_adesao_ativacao = 0;
        $total_qtd_clientes_ativacao = 0;
        $total_qtd_contratada_ativacao = 0;

		foreach($dados_consulta as $dado_consulta){

            $contrato = $dado_consulta['nome'];

            if($dado_consulta['nome_contrato'] || $dado_consulta['nome_contrato'] != ''){
                $contrato .= " (".$dado_consulta['nome_contrato'].")";
            }

			if($dado_consulta['tipo_cobranca'] == 'mensal_desafogo'){
				$tipo_cobranca = "Mensal com Desafogo (".$dado_consulta['desafogo_contrato']."%)";
			}else if($dado_consulta['tipo_cobranca'] == 'unitario'){
				$tipo_cobranca = "Unitário";
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

           	$dados_planos = DBRead('','tb_plano',"WHERE id_plano = '".$dado_consulta['id_plano']."'");
			$plano = $dados_planos[0]['nome'];

			$data_inicial = new DateTime($ano."-".$mes."-01");
			$data_inicial->modify('first day of last month');
			
			$valor_total = $dado_consulta['valor_total'];
			$valor_inicial_contrato = $dado_consulta['valor_inicial_contrato'];
			$valor_unitario_contrato = $dado_consulta['valor_unitario_contrato'];
			$valor_excedente_contrato = $dado_consulta['valor_excedente_contrato'];

			$qtd_contratada = $dado_consulta['qtd_contratada'];
			
			$qtd_clientes = $dado_consulta['qtd_clientes'];
			$valor_adesao = $dado_consulta['valor_adesao'];
           	
			$dados_consulta_passado = DBRead('', 'tb_faturamento a',"INNER JOIN tb_faturamento_contrato b ON a.id_faturamento = b.id_faturamento INNER JOIN tb_contrato_plano_pessoa c ON b.id_contrato_plano_pessoa = c.id_contrato_plano_pessoa INNER JOIN tb_pessoa d ON c.id_pessoa = d.id_pessoa WHERE b.contrato_pai = '1' AND a.data_referencia = '".$data_inicial->format('Y-m-d')."' AND b.id_contrato_plano_pessoa = '".$dado_consulta['id_contrato_plano_pessoa']."' AND a.status = '1' ", "a.id_faturamento");
			
			if(!$dados_consulta_passado){

				// Ativação
				$resultados_ativacao[$cont_ativacao]['contrato'] = $contrato;
				$resultados_ativacao[$cont_ativacao]['plano'] = $plano;
				$resultados_ativacao[$cont_ativacao]['tipo_cobranca'] = $tipo_cobranca;
				$resultados_ativacao[$cont_ativacao]['valor_adesao'] = converteMoeda($valor_adesao, 'moeda');
				$resultados_ativacao[$cont_ativacao]['valor_total'] = converteMoeda($valor_total,'moeda');
				$resultados_ativacao[$cont_ativacao]['valor_inicial_contrato'] = converteMoeda($valor_inicial_contrato,'moeda');
				$resultados_ativacao[$cont_ativacao]['valor_unitario_contrato'] = converteMoeda($valor_unitario_contrato,'moeda');
				$resultados_ativacao[$cont_ativacao]['valor_excedente_contrato'] = converteMoeda($valor_excedente_contrato,'moeda');
				$resultados_ativacao[$cont_ativacao]['qtd_contratada'] = $qtd_contratada;
				$resultados_ativacao[$cont_ativacao]['qtd_clientes'] = $qtd_clientes;

				$total_valor_ativacao = $total_valor_ativacao + $valor_total;
				$total_adesao_ativacao = $total_adesao_ativacao + $valor_adesao;
				$total_qtd_clientes_ativacao = $total_qtd_clientes_ativacao + $qtd_clientes;
				$total_qtd_contratada_ativacao = $total_qtd_contratada_ativacao + $qtd_contratada;

				$cont_ativacao++;
			}
		}

		if($resultados_ativacao){
			echo "<legend style=\"text-align:center;\"><strong>Novos Contratos</strong></legend>";

				echo '
				<table class="table table-hover dataTable" style="margin-bottom:0;">
				      	<thead>
					        <tr>
		                        <th>Contrato</th>
		                        <th>Plano</th>
		                        <th>Tipo de Cobrança</th>
		                        <th>Valor da Adesão</th>
		                        <th>Valor Contrato</th>
		                        <th>Valor Inicial</th>
		                        <th>Valor Unitário</th>
		                        <th>Valor Excedente</th>
		                        <th>Qtd. Contratada</th>
		                        <th>Qtd. Clientes</th>
		                    </tr>
				      	</thead>
				    <tbody>';  
					
					arsort($resultados_ativacao);
					
						foreach ($resultados_ativacao as $resultado) {
							echo "</tr>";								
								echo "<td>".$resultado['contrato']."</td>";
								echo "<td>".$resultado['plano']."</td>";
								echo "<td>".$resultado['tipo_cobranca']."</td>";
								echo "<td data-order='".converteMoeda($resultado['valor_adesao'], 'banco')."'>R$".$resultado['valor_adesao']."</td>";
								echo "<td data-order='".converteMoeda($resultado['valor_total'],'banco')."'>R$".$resultado['valor_total']."</td>";
								echo "<td data-order='".converteMoeda($resultado['valor_inicial_contrato'],'banco')."'>R$".$resultado['valor_inicial_contrato']."</td>";
								echo "<td data-order='".converteMoeda($resultado['valor_unitario_contrato'],'banco')."'>R$".$resultado['valor_unitario_contrato']."</td>";
								echo "<td data-order='".converteMoeda($resultado['valor_excedente_contrato'],'banco')."'>R$".$resultado['valor_excedente_contrato']."</td>";
								echo "<td>".$resultado['qtd_contratada']."</td>";
								echo "<td>".$resultado['qtd_clientes']."</td>";
							echo "</tr>";
						}

			        echo '</tbody>';	

			        /*echo "<tfoot>";
						echo '<tr>';
							echo '<th></th>';
							echo '<th></th>';
							echo '<th></th>';
							echo '<th></th>';
							echo '<th></th>';
							echo '<th></th>';
							echo '<th></th>';
							echo '<th>Totais</th>';
							echo '<th>'.$total_qtd_clientes_ativacao.'</th>';
						echo '</tr>';
					echo "</tfoot> ";	*/

				echo '</table><hr>';

		}

		echo "<legend style=\"text-align:center;\"><strong>Totalizadores</strong></legend>";
			
		echo '
		<table class="table table-hover dataTable" style="margin-bottom:0;">
			      	<thead>
				        <tr>
	                        <th style="text-align:center;">Valores de Adesões</th>
	                        <th style="text-align:center;">Valores de Contratos</th>
	                        <th style="text-align:center;">Qtd. Contratadas</th>
	                        <th style="text-align:center;">Qtd. Clientes</th>
	                    </tr>
			      	</thead>
			    <tbody>';  

				echo "<tr>";
					echo "<td style='text-align:center;'>R$".converteMoeda($total_adesao_ativacao,'moeda')."</td>";
					echo "<td style='text-align:center;'>R$".converteMoeda($total_valor_ativacao,'moeda')."</td>";
					echo "<td style='text-align:center;'>".$total_qtd_contratada_ativacao."</td>";
					echo "<td style='text-align:center;'>".$total_qtd_clientes_ativacao."</td>";

				echo "</tr>";
	        echo '</tbody>';					          
		echo '</table><hr>';

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

function relatorio_alteracao_faturamento_call_ativo($referencia, $dados_meses, $id_responsavel){
	if ($id_responsavel) {
        $filtro_id_responsavel = "AND c.id_responsavel = '".$id_responsavel."'";
        $dados_id_responsavel = DBRead('', 'tb_usuario a',"INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_usuario = '".$id_responsavel."' ", "b.nome");
        $legenda_id_responsavel = $dados_id_responsavel[0]['nome'];

    } else {
        $filtro_id_responsavel = "";
        $legenda_id_responsavel = 'Qualquer';
    }

	$legenda_servico = 'Call Center - Ativo';
	$filtro_servico = 'call_ativo';

	$mes_ano = explode("-", $referencia);
	$mes = $mes_ano[1];
	$ano = $mes_ano[0];

	$periodo_amostra = "<span style=\"font-size: 14px;\"><strong>Serviço: </strong>".$legenda_servico.", <strong>Responsável pelo Relacionamento: </strong>".$legenda_id_responsavel.", <strong>Referência: </strong>".$dados_meses[$mes]." de ".$ano."</span>";
	$gerado = "<span style=\"font-size: 14px;\"><strong>Gerado em: </strong> ".converteDataHora(getDataHora())."</span>";	

	echo "<div class=\"col-md-12\" style=\"padding: 0\">";
	echo "<legend style=\"text-align:center;\"><strong>Relatório de Indicadores - Alterações de Faturamento (Contrato)</strong><br>$gerado</legend>";
	echo "<legend style=\"text-align:center;\">$periodo_amostra</legend>";

	$dados_consulta = DBRead('', 'tb_faturamento a',"INNER JOIN tb_faturamento_contrato b ON a.id_faturamento = b.id_faturamento INNER JOIN tb_contrato_plano_pessoa c ON b.id_contrato_plano_pessoa = c.id_contrato_plano_pessoa INNER JOIN tb_pessoa d ON c.id_pessoa = d.id_pessoa INNER JOIN tb_plano e ON a.id_plano = e.id_plano WHERE e.cod_servico = '".$filtro_servico."' AND b.contrato_pai = '1' AND a.data_referencia = '".$referencia."' AND a.status = '1' AND a.adesao = '0' ", "a.*, b.*, c.nome_contrato, c.qtd_clientes, d.nome, a.status AS status_contrato");
	
	if($dados_consulta){

        $resultados_down = array();   
        $resultados_up = array();   
        $resultados_ativacao = array();   
        $resultados_adesao = array();   

        $cont_down = 0;
        $cont_up = 0;
        $cont_ativacao = 0;
        $cont_adesao = 0;
	
        $total_down = 0;
        $total_up = 0;
        $total_ativacao = 0;
        $total_adesao = 0;

        $total_soma_qtd_down = 0;
        $total_soma_qtd_up = 0;
        $total_soma_qtd_ativacao = 0;

        $total_soma_valor_down = 0;
        $total_soma_valor_up = 0;
        $total_soma_valor_ativacao = 0;

		foreach($dados_consulta as $dado_consulta){

			//NOME DO CONTRATO
			if($dado_consulta['nome_contrato']){
                $nome_contrato = " (".$dado_consulta['nome_contrato'].") ";
            }else{
                $nome_contrato = '';
            }
			            
            $contrato = $dado_consulta['nome']." ".$nome_contrato;

			if($dado_consulta['tipo_cobranca'] == 'mensal_desafogo'){
				$tipo_cobranca = "Mensal com Desafogo (".$dado_consulta['desafogo_contrato']."%)";
			}else if($dado_consulta['tipo_cobranca'] == 'unitario'){
				$tipo_cobranca = "Unitário";
			}else if($dado_consulta['tipo_cobranca'] == 'prepago'){
				$tipo_cobranca = "Pré-Pago";
			}else{
				$tipo_cobranca = ucfirst($dado_consulta['tipo_cobranca']);
			}

           	$dados_planos = DBRead('','tb_plano',"WHERE id_plano = '".$dado_consulta['id_plano']."'");
			$plano = $dados_planos[0]['nome'];

			$data_inicial = new DateTime($ano."-".$mes."-01");
			$data_inicial->modify('first day of last month');
			
			$valor_adesao = $dado_consulta['valor_adesao'];

			$valor_total = $dado_consulta['valor_total'];
			$valor_inicial_contrato = $dado_consulta['valor_inicial_contrato'];
			$valor_unitario_contrato = $dado_consulta['valor_unitario_contrato'];
			$valor_excedente_contrato = $dado_consulta['valor_excedente_contrato'];

			$qtd_contratada = $dado_consulta['qtd_contratada'];
           	
			$dados_consulta_passado = DBRead('', 'tb_faturamento a',"INNER JOIN tb_faturamento_contrato b ON a.id_faturamento = b.id_faturamento INNER JOIN tb_contrato_plano_pessoa c ON b.id_contrato_plano_pessoa = c.id_contrato_plano_pessoa INNER JOIN tb_pessoa d ON c.id_pessoa = d.id_pessoa WHERE b.contrato_pai = '1' AND a.data_referencia = '".$data_inicial->format('Y-m-d')."' AND b.id_contrato_plano_pessoa = '".$dado_consulta['id_contrato_plano_pessoa']."' AND a.status = '1' AND a.adesao = '0'", "a.*, b.*, c.nome_contrato, c.qtd_clientes, d.nome, a.status AS status_contrato");
			
			$dados_planos_passado = DBRead('','tb_plano',"WHERE id_plano = '".$dados_consulta_passado[0]['id_plano']."'");

			if($dados_consulta_passado[0]['tipo_cobranca'] == 'mensal_desafogo'){
				$tipo_cobranca_passado = "Mensal com Desafogo (".$dados_consulta_passado[0]['desafogo_contrato']."%)";
			}else if($dados_consulta_passado[0]['tipo_cobranca'] == 'unitario'){
				$tipo_cobranca_passado = "Unitário";
			}else if($dados_consulta_passado[0]['tipo_cobranca'] == 'prepago'){
				$tipo_cobranca_passado = "Pré-Pago";
			}else{
				$tipo_cobranca_passado = ucfirst($dados_consulta_passado[0]['tipo_cobranca']);
			}
			
			$valor_total_passado = $dados_consulta_passado[0]['valor_total'];
			$valor_inicial_contrato_passado = $dados_consulta_passado[0]['valor_inicial_contrato'];
			$valor_unitario_contrato_passado = $dados_consulta_passado[0]['valor_unitario_contrato'];
			$valor_excedente_contrato_passado = $dados_consulta_passado[0]['valor_excedente_contrato'];

			$qtd_contratada_passado = $dados_consulta_passado[0]['qtd_contratada'];

			if($qtd_contratada != $qtd_contratada_passado && $dados_consulta_passado){
				if($qtd_contratada < $qtd_contratada_passado){

					// Down
					$resultados_down[$cont_down]['contrato'] = $contrato;
					$resultados_down[$cont_down]['plano'] = $plano;
					$resultados_down[$cont_down]['tipo_cobranca'] = $tipo_cobranca;
					$resultados_down[$cont_down]['valor_total'] = converteMoeda($valor_total,'moeda');
					$resultados_down[$cont_down]['valor_inicial_contrato'] = converteMoeda($valor_inicial_contrato,'moeda');
					$resultados_down[$cont_down]['valor_unitario_contrato'] = converteMoeda($valor_unitario_contrato,'moeda');
					$resultados_down[$cont_down]['valor_excedente_contrato'] = converteMoeda($valor_excedente_contrato,'moeda');
					$resultados_down[$cont_down]['qtd_contratada'] = $qtd_contratada;
					$resultados_down[$cont_down]['diferenca_qtd_contratada'] = $qtd_contratada - $qtd_contratada_passado;
										
					$total_soma_valor_down = $total_soma_valor_down + $valor_total;
					$total_soma_qtd_down = $total_soma_qtd_down + $qtd_contratada;

					$cont_down++;

				}else if($qtd_contratada > $qtd_contratada_passado){
					
					$qtd_contratada_passado = $dados_consulta_passado[0]['qtd_contratada'];
					
					// UP
					$resultados_up[$cont_up]['contrato'] = $contrato;
					$resultados_up[$cont_up]['plano'] = $plano;
					$resultados_up[$cont_up]['tipo_cobranca'] = $tipo_cobranca;
					$resultados_up[$cont_up]['valor_total'] = converteMoeda($valor_total,'moeda');
					$resultados_up[$cont_up]['valor_inicial_contrato'] = converteMoeda($valor_inicial_contrato,'moeda');
					$resultados_up[$cont_up]['valor_unitario_contrato'] = converteMoeda($valor_unitario_contrato,'moeda');
					$resultados_up[$cont_up]['valor_excedente_contrato'] = converteMoeda($valor_excedente_contrato,'moeda');
					$resultados_up[$cont_up]['qtd_contratada'] = $qtd_contratada;
					$resultados_up[$cont_up]['diferenca_qtd_contratada'] = $qtd_contratada - $qtd_contratada_passado;
					
					$total_soma_valor_up = $total_soma_valor_up + $valor_total;
					$total_soma_qtd_up = $total_soma_qtd_up + $qtd_contratada;
					
					$cont_up++;
					
				}
				
			}else if(!$dados_consulta_passado){

				// Ativação
				$resultados_ativacao[$cont_ativacao]['contrato'] = $contrato;
				$resultados_ativacao[$cont_ativacao]['plano'] = $plano;
				$resultados_ativacao[$cont_ativacao]['tipo_cobranca'] = $tipo_cobranca;
				$resultados_ativacao[$cont_ativacao]['valor_adesao'] = converteMoeda($valor_adesao,'moeda');
				$resultados_ativacao[$cont_ativacao]['valor_total'] = converteMoeda($valor_total,'moeda');
				$resultados_ativacao[$cont_ativacao]['valor_inicial_contrato'] = converteMoeda($valor_inicial_contrato,'moeda');
				$resultados_ativacao[$cont_ativacao]['valor_unitario_contrato'] = converteMoeda($valor_unitario_contrato,'moeda');
				$resultados_ativacao[$cont_ativacao]['valor_excedente_contrato'] = converteMoeda($valor_excedente_contrato,'moeda');
				$resultados_ativacao[$cont_ativacao]['qtd_contratada'] = $qtd_contratada;
				$resultados_ativacao[$cont_ativacao]['diferenca_qtd_contratada'] = "-";

				$total_soma_valor_ativacao = $total_soma_valor_ativacao + $valor_total;
				$total_soma_qtd_ativacao = $total_soma_qtd_ativacao + $qtd_contratada;

				$cont_ativacao++;
			}
		}

		if($resultados_ativacao){
			echo "<legend style=\"text-align:center;\"><strong>Novos Contratos</strong></legend>";

				echo '
				<table class="table table-hover dataTable" style="margin-bottom:0;">
				      	<thead>
					        <tr>
		                        <th class="col-md-3">Contrato</th>
		                        <th class="col-md-2">Plano</th>
		                        <th class="col-md-1">Tipo de Cobrança</th>
		                        <th class="col-md-1">Valor Adesão</th>
		                        <th class="col-md-1">Valor Total</th>
		                        <th class="col-md-1">Valor Inicial</th>
		                        <th class="col-md-1">Valor Unitário</th>
		                        <th class="col-md-1">Valor Excedente</th>
		                        <th class="col-md-1">Qtd. Contratada</th>
		                    </tr>
				      	</thead>
				    <tbody>';  
					
					arsort($resultados_ativacao);
					
						foreach ($resultados_ativacao as $resultado) {
							echo "</tr>";								
								echo "<td>".$resultado['contrato']."</td>";
								echo "<td>".$resultado['plano']."</td>";
								echo "<td>".$resultado['tipo_cobranca']."</td>";
								echo "<td data-order='".converteMoeda($resultado['valor_adesao'],'banco')."'>R$".$resultado['valor_adesao']."</td>";
								echo "<td data-order='".converteMoeda($resultado['valor_total'],'banco')."'>R$".$resultado['valor_total']."</td>";
								echo "<td data-order='".converteMoeda($resultado['valor_inicial_contrato'],'banco')."'>R$".$resultado['valor_inicial_contrato']."</td>";
								echo "<td data-order='".converteMoeda($resultado['valor_unitario_contrato'],'banco')."'>R$".$resultado['valor_unitario_contrato']."</td>";
								echo "<td data-order='".converteMoeda($resultado['valor_excedente_contrato'],'banco')."'>R$".$resultado['valor_excedente_contrato']."</td>";
								echo "<td>".$resultado['qtd_contratada']."</td>";
							echo "</tr>";
						}

			        echo '</tbody>';	

			      /*  echo "<tfoot>";
						echo '<tr>';
							echo '<th></th>';
							echo '<th></th>';
							echo '<th></th>';
							echo '<th></th>';
							echo '<th></th>';
							echo '<th></th>';
							echo '<th></th>';
							echo '<th>Totais</th>';
							echo '<th>'.$total_soma_qtd_ativacao.'</th>';
							echo '<th></th>';
						echo '</tr>';
					echo "</tfoot> ";	*/

				echo '</table><hr>';
		}

		if($resultados_up){
			echo "<legend style=\"text-align:center;\"><strong>Upgrade</strong></legend>";
				
				echo '
				<table class="table table-hover dataTable" style="margin-bottom:0;">
					      	<thead>
						        <tr>
			                        <th class="col-md-3">Contrato</th>
			                        <th class="col-md-2">Plano</th>
			                        <th class="col-md-1">Tipo de Cobrança</th>
		                        	<th class="col-md-1">Valor Total</th>
			                        <th class="col-md-1">Valor Inicial</th>
			                        <th class="col-md-1">Valor Unitário</th>
			                        <th class="col-md-1">Valor Excedente</th>
			                        <th class="col-md-1">Qtd. Contratada</th>
			                        <th class="col-md-1">Dif. Qtd. Contratada</th>
			                    </tr>
					      	</thead>
					    <tbody>';  
								
						arsort($resultados_up);

							foreach ($resultados_up as $resultado) {	
								echo "</tr>";								
                                    echo "<td>".$resultado['contrato']."</td>";
                                    echo "<td>".$resultado['plano']."</td>";
                                    echo "<td>".$resultado['tipo_cobranca']."</td>";
                                    echo "<td data-order='".converteMoeda($resultado['valor_total'],'banco')."'>R$".$resultado['valor_total']."</td>";
                                    echo "<td data-order='".converteMoeda($resultado['valor_inicial_contrato'],'banco')."'>R$".$resultado['valor_inicial_contrato']."</td>";
                                    echo "<td data-order='".converteMoeda($resultado['valor_unitario_contrato'],'banco')."'>R$".$resultado['valor_unitario_contrato']."</td>";
                                    echo "<td data-order='".converteMoeda($resultado['valor_excedente_contrato'],'banco')."'>R$".$resultado['valor_excedente_contrato']."</td>";
                                    echo "<td>".$resultado['qtd_contratada']."</td>";
                                    echo "<td>".$resultado['diferenca_qtd_contratada']."</td>";
                                echo "</tr>";
							}

				        echo '</tbody>';	

				      /*  echo "<tfoot>";
							echo '<tr>';
								echo '<th></th>';
								echo '<th></th>';
								echo '<th></th>';
								echo '<th></th>';
								echo '<th></th>';
								echo '<th></th>';
								echo '<th>Totais</th>';
								echo '<th>'.$total_soma_qtd_up.'</th>';
								echo '<th></th>';
							echo '</tr>';
						echo "</tfoot> ";	*/

				echo '</table><hr>';
		}	

		if($resultados_down){
        	echo "<legend style=\"text-align:center;\"><strong>Downgrade</strong></legend>";
			
			echo '
				<table class="table table-hover dataTable" style="margin-bottom:0;">
				      	<thead>
					        <tr>
		                        <th class="col-md-3">Contrato</th>
		                        <th class="col-md-2">Plano</th>
		                        <th class="col-md-1">Tipo de Cobrança</th>
		                        <th class="col-md-1">Valor Total</th>
		                        <th class="col-md-1">Valor Inicial</th>
		                        <th class="col-md-1">Valor Unitário</th>
		                        <th class="col-md-1">Valor Excedente</th>
		                        <th class="col-md-1">Qtd. Contratada</th>
		                        <th class="col-md-1">Dif. Qtd. Contratada</th>
		                    </tr>
				      	</thead>
				    <tbody>';  
							
					arsort($resultados_down);

						foreach ($resultados_down as $resultado) {
							echo "</tr>";								
                                echo "<td>".$resultado['contrato']."</td>";
                                echo "<td>".$resultado['plano']."</td>";
                                echo "<td>".$resultado['tipo_cobranca']."</td>";
                                echo "<td data-order='".converteMoeda($resultado['valor_total'],'banco')."'>R$".$resultado['valor_total']."</td>";
                                echo "<td data-order='".converteMoeda($resultado['valor_inicial_contrato'],'banco')."'>R$".$resultado['valor_inicial_contrato']."</td>";
                                echo "<td data-order='".converteMoeda($resultado['valor_unitario_contrato'],'banco')."'>R$".$resultado['valor_unitario_contrato']."</td>";
                                echo "<td data-order='".converteMoeda($resultado['valor_excedente_contrato'],'banco')."'>R$".$resultado['valor_excedente_contrato']."</td>";
                                echo "<td>".$resultado['qtd_contratada']."</td>";
                                echo "<td>".$resultado['diferenca_qtd_contratada']."</td>";
                            echo "</tr>";
						}

			        echo '</tbody>';

			        /*echo "<tfoot>";
						echo '<tr>';
							echo '<th></th>';
							echo '<th></th>';
							echo '<th></th>';
							echo '<th></th>';
							echo '<th></th>';
							echo '<th></th>';
							echo '<th>Totais</th>';
							echo '<th>'.$total_soma_qtd_down.'</th>';
							echo '<th></th>';
						echo '</tr>';
					echo "</tfoot> ";	*/

				echo '</table><hr>';
		}

		$dados_consulta_adesao = DBRead('', 'tb_faturamento a',"INNER JOIN tb_faturamento_contrato b ON a.id_faturamento = b.id_faturamento INNER JOIN tb_contrato_plano_pessoa c ON b.id_contrato_plano_pessoa = c.id_contrato_plano_pessoa INNER JOIN tb_pessoa d ON c.id_pessoa = d.id_pessoa INNER JOIN tb_plano e ON a.id_plano = e.id_plano WHERE e.cod_servico = '".$filtro_servico."' AND a.data_referencia = '".$referencia."' AND a.adesao = '1' ", "a.*, b.*, c.nome_contrato, d.nome, a.valor_adesao AS valor_adesao_faturamento");

		if($dados_consulta_adesao){
			foreach($dados_consulta_adesao as $dado_consulta){
				//NOME DO CONTRATO
				if($dado_consulta['nome_contrato']){
					$nome_contrato = " (".$dado_consulta['nome_contrato'].") ";
				}else{
					$nome_contrato = '';
				}				
				$contrato = $dado_consulta['nome']." ".$nome_contrato;
				$dados_planos = DBRead('','tb_plano',"WHERE id_plano = '".$dado_consulta['id_plano']."'");
				$plano = $dados_planos[0]['nome'];
				
				$valor_adesao_faturamento = $dado_consulta['valor_adesao_faturamento'];
				
				$resultados_adesao[$cont_adesao]['contrato'] = $contrato;
				$resultados_adesao[$cont_adesao]['plano'] = $plano;
				$resultados_adesao[$cont_adesao]['valor_adesao_faturamento'] = converteMoeda($valor_adesao_faturamento,'moeda');
				
				$total_adesao = $total_adesao + $valor_adesao_faturamento;
				$cont_adesao++;
			}

			echo "<legend style=\"text-align:center;\"><strong>Adesões</strong></legend>";
			
			echo '
			<table class="table table-hover dataTable" style="margin-bottom:0;">
						<thead>
							<tr>
								<th>Contrato</th>
								<th>Plano</th>
								<th>Valor da Adesão</th>
							</tr>
						</thead>
					<tbody>';  

					arsort($resultados_adesao);

					foreach ($resultados_adesao as $resultado) {
						echo "</tr>";								
							echo "<td>".$resultado['contrato']."</td>";
							echo "<td>".$resultado['plano']."</td>";
							echo "<td data-order='".converteMoeda($resultado['valor_adesao_faturamento'],'banco')."'>R$".$resultado['valor_adesao_faturamento']."</td>";
						echo "</tr>";
					}
					echo "<tfoot>";
						echo '<tr>';
							echo '<th></th>';
							echo '<th>Totais</th>';
							echo '<th>R$'.converteMoeda($total_adesao, 'moeda').'</th>';
						echo '</tr>';
					echo "</tfoot> ";	
				echo '</tbody>';					          
			echo '</table>
			
			<hr>';
		}
		
		$balanço_diferenca_qtd = $total_soma_qtd_up + $total_soma_qtd_ativacao + $total_soma_qtd_down;
		$balanço_total_valor = $total_soma_valor_up + $total_soma_valor_ativacao + $total_adesao - $total_soma_valor_down;

		echo "<legend style=\"text-align:center;\"><strong>Totalizadores (com Adesões)</strong></legend>";
			
		echo '
		<table class="table table-hover dataTable" style="margin-bottom:0;">
			      	<thead>
				        <tr>
	                        <th class="col-md-3" style="text-align:center;">Quantidades Contratadas</th>
	                        <th class="col-md-3" style="text-align:center;">Quantidades Contratadas de Novos Contratos</th>
	                        <th class="col-md-3" style="text-align:center;">Valor Total de Contratos + Adesões</th>
	                    </tr>
			      	</thead>
			    <tbody>';  

				echo "<tr>";
					echo "<td style='text-align:center;'>".$balanço_diferenca_qtd."</td>";
					echo "<td style='text-align:center;'>".$total_soma_qtd_ativacao."</td>";
					echo "<td style='text-align:center;'>R$".converteMoeda($balanço_total_valor,'moeda')."</td>";

				echo "</tr>";
	        echo '</tbody>';					          
		echo '</table><hr>';

		$balanço_diferenca_qtd = $total_soma_qtd_up + $total_soma_qtd_ativacao +$total_soma_qtd_down;
		$balanço_total_valor = $total_soma_valor_up + $total_soma_valor_ativacao - $total_soma_valor_down;

		echo "<legend style=\"text-align:center;\"><strong>Totalizadores (sem Adesões)</strong></legend>";
			
		echo '
		<table class="table table-hover dataTable" style="margin-bottom:0;">
			      	<thead>
				        <tr>
	                        <th class="col-md-4" style="text-align:center;">Quantidades Contratadas</th>
	                        <th class="col-md-4" style="text-align:center;">Quantidades Contratadas de Novos Contratos</th>
	                        <th class="col-md-4" style="text-align:center;">Valor Total de Contratos</th>
	                    </tr>
			      	</thead>
			    <tbody>';  

				echo "<tr>";
					echo "<td style='text-align:center;'>".$balanço_diferenca_qtd."</td>";
					echo "<td style='text-align:center;'>".$total_soma_qtd_ativacao."</td>";
					echo "<td style='text-align:center;'>R$".converteMoeda($balanço_total_valor,'moeda')."</td>";

				echo "</tr>";
	        echo '</tbody>';					          
		echo '</table><hr>';


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

function relatorio_alteracao_faturamento_call_suporte($referencia, $dados_meses, $id_responsavel){
	
	if ($id_responsavel) {
        $filtro_id_responsavel = "AND c.id_responsavel = '".$id_responsavel."'";
        $dados_id_responsavel = DBRead('', 'tb_usuario a',"INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_usuario = '".$id_responsavel."' ", "b.nome");
        $legenda_id_responsavel = $dados_id_responsavel[0]['nome'];

    } else {
        $filtro_id_responsavel = "";
        $legenda_id_responsavel = 'Qualquer';
    }

	$legenda_servico = 'Call Center - Suporte';
	$filtro_servico = 'call_suporte';

	$mes_ano = explode("-", $referencia);
	$mes = $mes_ano[1];
	$ano = $mes_ano[0];

	$periodo_amostra = "<span style=\"font-size: 14px;\"><strong>Serviço: </strong>".$legenda_servico.", <strong>Responsável pelo Relacionamento: </strong>".$legenda_id_responsavel.", <strong>Referência: </strong>".$dados_meses[$mes]." de ".$ano."</span>";
	$gerado = "<span style=\"font-size: 14px;\"><strong>Gerado em: </strong> ".converteDataHora(getDataHora())."</span>";	

	echo "<div class=\"col-md-12\" style=\"padding: 0\">";
	echo "<legend style=\"text-align:center;\"><strong>Relatório de Indicadores - Alterações de Faturamento (Contrato)</strong><br>$gerado</legend>";
	echo "<legend style=\"text-align:center;\">$periodo_amostra</legend>";

	$dados_consulta = DBRead('', 'tb_faturamento a',"INNER JOIN tb_faturamento_contrato b ON a.id_faturamento = b.id_faturamento INNER JOIN tb_contrato_plano_pessoa c ON b.id_contrato_plano_pessoa = c.id_contrato_plano_pessoa INNER JOIN tb_pessoa d ON c.id_pessoa = d.id_pessoa INNER JOIN tb_plano e ON a.id_plano = e.id_plano WHERE e.cod_servico = '".$filtro_servico."' AND b.contrato_pai = '1' AND a.data_referencia = '".$referencia."' AND a.status = '1' AND a.adesao = '0' ".$filtro_id_responsavel." ", "a.*, b.*, c.nome_contrato, c.qtd_clientes, d.nome, a.status AS status_contrato");
	
	if($dados_consulta){

        $resultados_down = array();   
        $resultados_up = array();   
        $resultados_ajuste = array();   
        $resultados_ativacao = array();   
        $resultados_cancelamento = array();   
        $resultados_adesao = array();   
        $cont_down = 0;
        $cont_up = 0;
        $cont_ajuste = 0;
        $cont_ativacao = 0;
        $cont_cancelamento = 0;
        $cont_adesao = 0;
	
        $total_down = 0;
        $total_up = 0;
        $total_ajuste = 0;
        $total_ativacao = 0;
        $total_cancelamento = 0;
        $total_adesao = 0;

        $total_diferenca_qtd_down = 0;
        $total_diferenca_qtd_up = 0;
        $total_diferenca_qtd_ajuste = 0;
        $total_diferenca_qtd_ativacao = 0;
        $total_diferenca_qtd_cancelamento = 0;

        $total_diferenca_valor_down = 0;
        $total_diferenca_valor_up = 0;
        $total_diferenca_valor_ajuste = 0;
        $total_diferenca_valor_ativacao = 0;
        $total_diferenca_valor_cancelamento = 0;

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

           	$dados_planos = DBRead('','tb_plano',"WHERE id_plano = '".$dado_consulta['id_plano']."'");
			$plano = $dados_planos[0]['nome'];

			$data_inicial = new DateTime($ano."-".$mes."-01");
			$data_inicial->modify('first day of last month');
			
			$valor_total = $dado_consulta['valor_total_contrato'];
			$valor_inicial_contrato = $dado_consulta['valor_inicial_contrato'];
			$valor_unitario_contrato = $dado_consulta['valor_unitario_contrato'];
			$valor_excedente_contrato = $dado_consulta['valor_excedente_contrato'];

			$qtd_contratada = $dado_consulta['qtd_contratada'];
			$qtd_clientes = $dado_consulta['qtd_clientes'];
			$qtd_clientes_teto = $dado_consulta['qtd_clientes_teto'];
           	
			$dados_consulta_passado = DBRead('', 'tb_faturamento a',"INNER JOIN tb_faturamento_contrato b ON a.id_faturamento = b.id_faturamento INNER JOIN tb_contrato_plano_pessoa c ON b.id_contrato_plano_pessoa = c.id_contrato_plano_pessoa INNER JOIN tb_pessoa d ON c.id_pessoa = d.id_pessoa WHERE b.contrato_pai = '1' AND a.data_referencia = '".$data_inicial->format('Y-m-d')."' AND b.id_contrato_plano_pessoa = '".$dado_consulta['id_contrato_plano_pessoa']."' AND a.status = '1' AND a.adesao = '0' ", "a.*, b.*, c.nome_contrato, c.qtd_clientes, d.nome, c.status AS status_contrato");
			
			$dados_planos_passado = DBRead('','tb_plano',"WHERE id_plano = '".$dados_consulta_passado[0]['id_plano']."'");
			$plano_passado = $dados_planos_passado[0]['nome'];
			
			$valor_total_passado = $dados_consulta_passado[0]['valor_total_contrato'];

			$qtd_contratada_passado = $dados_consulta_passado[0]['qtd_contratada'];

			if($valor_total != $valor_total_passado && $dados_consulta_passado){
				if($valor_total < $valor_total_passado){

					// Down
					$resultados_down[$cont_down]['contrato'] = $contrato;
					$resultados_down[$cont_down]['plano'] = $plano;
					$resultados_down[$cont_down]['tipo_cobranca'] = $tipo_cobranca;
					$resultados_down[$cont_down]['valor_total'] = converteMoeda($valor_total,'moeda');
					$resultados_down[$cont_down]['valor_inicial_contrato'] = converteMoeda($valor_inicial_contrato,'moeda');
					$resultados_down[$cont_down]['valor_unitario_contrato'] = converteMoeda($valor_unitario_contrato,'moeda');
					$resultados_down[$cont_down]['valor_excedente_contrato'] = converteMoeda($valor_excedente_contrato,'moeda');
					$resultados_down[$cont_down]['qtd_contratada'] = $qtd_contratada;
					$resultados_down[$cont_down]['diferenca_qtd_contratada'] = $qtd_contratada - $qtd_contratada_passado;
					$resultados_down[$cont_down]['diferenca_valor'] = converteMoeda($valor_total - $valor_total_passado, 'moeda');
					
					$total_down = $total_down + ($dados_consulta_passado[0]['valor_total'] - $valor_total);
					
					$total_diferenca_qtd_down= $total_diferenca_qtd_down + ($qtd_contratada - $qtd_contratada_passado);
					$total_diferenca_valor_down = $total_diferenca_valor_down + ($valor_total - $valor_total_passado);

					$resultados_down[$cont_down]['qtd_clientes'] = $qtd_clientes;
					$resultados_down[$cont_down]['qtd_clientes_teto'] = $qtd_clientes_teto;

					$cont_down++;
				}else if($valor_total > $valor_total_passado){
					
					$qtd_contratada_passado = $dados_consulta_passado[0]['qtd_contratada'];

					if($plano != $plano_passado || $qtd_contratada != $qtd_contratada_passado){
						
						// UP
						$resultados_up[$cont_up]['contrato'] = $contrato;
						$resultados_up[$cont_up]['plano'] = $plano;
						$resultados_up[$cont_up]['tipo_cobranca'] = $tipo_cobranca;
						$resultados_up[$cont_up]['valor_total'] = converteMoeda($valor_total,'moeda');
						$resultados_up[$cont_up]['valor_inicial_contrato'] = converteMoeda($valor_inicial_contrato,'moeda');
						$resultados_up[$cont_up]['valor_unitario_contrato'] = converteMoeda($valor_unitario_contrato,'moeda');
						$resultados_up[$cont_up]['valor_excedente_contrato'] = converteMoeda($valor_excedente_contrato,'moeda');
						$resultados_up[$cont_up]['qtd_contratada'] = $qtd_contratada;
						$resultados_up[$cont_up]['diferenca_qtd_contratada'] = $qtd_contratada - $qtd_contratada_passado;
						$resultados_up[$cont_up]['diferenca_valor'] = converteMoeda($valor_total - $valor_total_passado, 'moeda');
						
						$total_up = $total_up + ($valor_total - $dados_consulta_passado[0]['valor_total_contrato']);

						$total_diferenca_qtd_up= $total_diferenca_qtd_up + ($qtd_contratada - $qtd_contratada_passado);
						$total_diferenca_valor_up = $total_diferenca_valor_up + ($valor_total - $valor_total_passado);

						$resultados_up[$cont_up]['qtd_clientes'] = $qtd_clientes;
						$resultados_up[$cont_up]['qtd_clientes_teto'] = $qtd_clientes_teto;
						
						$cont_up++;
					}else{
						
						// Ajuste
						$resultados_ajuste[$cont_ajuste]['contrato'] = $contrato;
						$resultados_ajuste[$cont_ajuste]['plano'] = $plano;
						$resultados_ajuste[$cont_ajuste]['tipo_cobranca'] = $tipo_cobranca;
						$resultados_ajuste[$cont_ajuste]['valor_total'] = converteMoeda($valor_total,'moeda');
						$resultados_ajuste[$cont_ajuste]['valor_inicial_contrato'] = converteMoeda($valor_inicial_contrato,'moeda');
						$resultados_ajuste[$cont_ajuste]['valor_unitario_contrato'] = converteMoeda($valor_unitario_contrato,'moeda');
						$resultados_ajuste[$cont_ajuste]['valor_excedente_contrato'] = converteMoeda($valor_excedente_contrato,'moeda');
						$resultados_ajuste[$cont_ajuste]['qtd_contratada'] = $qtd_contratada;
						$resultados_ajuste[$cont_ajuste]['diferenca_qtd_contratada'] = $qtd_contratada - $qtd_contratada_passado;
						$resultados_ajuste[$cont_ajuste]['diferenca_valor'] = converteMoeda($valor_total - $valor_total_passado, 'moeda');
						
						$total_ajuste = $total_ajuste + ($valor_total - $dados_consulta_passado[0]['valor_total_contrato']);

						$total_diferenca_qtd_ajuste = $total_diferenca_qtd_ajuste + ($qtd_contratada - $qtd_contratada_passado);
						$total_diferenca_valor_ajuste = $total_diferenca_valor_ajuste + ($valor_total - $valor_total_passado);

						$resultados_ajuste[$cont_ajuste]['qtd_clientes'] = $qtd_clientes;
						$resultados_ajuste[$cont_ajuste]['qtd_clientes_teto'] = $qtd_clientes_teto;
						
						$cont_ajuste++;
					}
					
				}
				
			}else if(!$dados_consulta_passado){

				// Ativação
				$resultados_ativacao[$cont_ativacao]['contrato'] = $contrato;
				$resultados_ativacao[$cont_ativacao]['plano'] = $plano;
				$resultados_ativacao[$cont_ativacao]['tipo_cobranca'] = $tipo_cobranca;
				$resultados_ativacao[$cont_ativacao]['valor_total'] = converteMoeda($valor_total,'moeda');
				$resultados_ativacao[$cont_ativacao]['valor_inicial_contrato'] = converteMoeda($valor_inicial_contrato,'moeda');
				$resultados_ativacao[$cont_ativacao]['valor_unitario_contrato'] = converteMoeda($valor_unitario_contrato,'moeda');
				$resultados_ativacao[$cont_ativacao]['valor_excedente_contrato'] = converteMoeda($valor_excedente_contrato,'moeda');
				$resultados_ativacao[$cont_ativacao]['qtd_contratada'] = $qtd_contratada;
				$resultados_ativacao[$cont_ativacao]['diferenca_qtd_contratada'] = $qtd_contratada;
				$resultados_ativacao[$cont_ativacao]['diferenca_valor'] = converteMoeda($valor_total, 'moeda');

				$total_ativacao = $total_ativacao + $valor_total;

				$total_diferenca_qtd_ativacao = $total_diferenca_qtd_ativacao + ($qtd_contratada);
				$total_diferenca_valor_ativacao = $total_diferenca_valor_ativacao + $valor_total;

				$resultados_ativacao[$cont_ativacao]['qtd_clientes'] = $qtd_clientes;
				$resultados_ativacao[$cont_ativacao]['qtd_clientes_teto'] = $qtd_clientes_teto;

				$cont_ativacao++;
			}
		}

		$data_inicial = new DateTime($ano."-".$mes."-01");
		$data_inicial->modify('first day of last month');

		$data_final = new DateTime($ano."-".$mes."-01");
		$data_final->modify('last day of last month');

		$dados_consulta_cancelamento = DBRead('', 'tb_faturamento a',"INNER JOIN tb_faturamento_contrato b ON a.id_faturamento = b.id_faturamento INNER JOIN tb_contrato_plano_pessoa c ON b.id_contrato_plano_pessoa = c.id_contrato_plano_pessoa INNER JOIN tb_pessoa d ON c.id_pessoa = d.id_pessoa INNER JOIN tb_plano e ON a.id_plano = e.id_plano WHERE e.cod_servico = '".$filtro_servico."' AND b.contrato_pai = '1' AND a.data_referencia = '".$data_inicial->format('Y-m-d')."' AND a.status = '1' AND a.adesao = '0' AND b.id_contrato_plano_pessoa NOT IN (SELECT b.id_contrato_plano_pessoa FROM bd_simples.tb_faturamento a INNER JOIN tb_faturamento_contrato b ON a.id_faturamento = b.id_faturamento INNER JOIN tb_contrato_plano_pessoa c ON b.id_contrato_plano_pessoa = c.id_contrato_plano_pessoa INNER JOIN tb_pessoa d ON c.id_pessoa = d.id_pessoa WHERE b.contrato_pai = '1' AND a.data_referencia = '".$referencia."' AND a.status = '1' AND a.adesao = '0') ", "a.*, b.*, c.nome_contrato, c.qtd_clientes, d.nome, c.status AS status_contrato");

		//$dados_consulta_cancelamento = DBRead('', 'tb_contrato_plano_pessoa a',"INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa INNER JOIN tb_plano c ON a.id_plano = c.id_plano WHERE a.status = 3 AND a.data_status >= '".$data_inicial->format('Y-m-d')."' AND a.data_status <= '".$data_final->format('Y-m-d')."' AND c.cod_servico = 'call_suporte' ", 'b.nome, a.nome_contrato, a.tipo_cobranca, a.desafogo AS desafogo_contrato, a.id_plano, a.valor_total, a.valor_inicial AS valor_inicial_contrato, a.valor_unitario AS valor_unitario_contrato, a.valor_excedente AS valor_excedente_contrato, a.qtd_contratada, a.data_status');

		if($dados_consulta_cancelamento){
			foreach($dados_consulta_cancelamento as $dado_consulta){
				if($dado_consulta['status_contrato'] == 3){
					//NOME DO CONTRATO
					if($dado_consulta['nome_contrato']){
						$nome_contrato = " (".$dado_consulta['nome_contrato'].") ";
					}else{
						$nome_contrato = '';
					}
					
					$contrato = $dado_consulta['nome']." ".$nome_contrato;

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

					$dados_planos = DBRead('','tb_plano',"WHERE id_plano = '".$dado_consulta['id_plano']."'");
					$plano = $dados_planos[0]['nome'];
					
					$valor_total = $dado_consulta['valor_total_contrato'];
					$valor_inicial_contrato = $dado_consulta['valor_inicial_contrato'];
					$valor_unitario_contrato = $dado_consulta['valor_unitario_contrato'];
					$valor_excedente_contrato = $dado_consulta['valor_excedente_contrato'];

					$qtd_contratada = $dado_consulta['qtd_contratada'];
					$qtd_clientes = $dado_consulta['qtd_clientes'];
					$qtd_clientes_teto = $dado_consulta['qtd_clientes_teto'];
					
					$resultados_cancelamento[$cont_cancelamento]['contrato'] = $contrato;
					$resultados_cancelamento[$cont_cancelamento]['plano'] = $plano;
					$resultados_cancelamento[$cont_cancelamento]['tipo_cobranca'] = $tipo_cobranca;
					$resultados_cancelamento[$cont_cancelamento]['valor_total'] = converteMoeda($valor_total,'moeda');
					$resultados_cancelamento[$cont_cancelamento]['valor_inicial_contrato'] = converteMoeda($valor_inicial_contrato,'moeda');
					$resultados_cancelamento[$cont_cancelamento]['valor_unitario_contrato'] = converteMoeda($valor_unitario_contrato,'moeda');
					$resultados_cancelamento[$cont_cancelamento]['valor_excedente_contrato'] = converteMoeda($valor_excedente_contrato,'moeda');
					$resultados_cancelamento[$cont_cancelamento]['qtd_contratada'] = $qtd_contratada;
					$resultados_cancelamento[$cont_cancelamento]['diferenca_qtd_contratada'] = - $qtd_contratada;
					$resultados_cancelamento[$cont_cancelamento]['diferenca_valor'] = converteMoeda(-$valor_total, 'moeda');
					
					$total_cancelamento = $total_cancelamento + $valor_total;

					$total_diferenca_qtd_cancelamento = $total_diferenca_qtd_cancelamento - ($qtd_contratada);
					$total_diferenca_valor_cancelamento = $total_diferenca_valor_cancelamento - ($valor_total);

					$resultados_cancelamento[$cont_cancelamento]['qtd_clientes'] = $qtd_clientes;
					$resultados_cancelamento[$cont_cancelamento]['qtd_clientes_teto'] = $qtd_clientes_teto;

					$cont_cancelamento++;
				}
				
			}
		}
       
		if($resultados_ativacao){
			echo "<legend style=\"text-align:center;\"><strong>Novos Contratos</strong></legend>";

				echo '
				<table class="table table-hover dataTable" style="margin-bottom:0;">
				      	<thead>
					        <tr>
		                        <th>Contrato</th>
		                        <th>Plano</th>
		                        <th>Tipo de Cobrança</th>
		                        <th>Valor Contrato</th>
		                        <th>Valor Inicial</th>
		                        <th>Valor Unitário</th>
		                        <th>Valor Excedente</th>
		                        <th>Qtd. Clientes</th>
		                        <th>Qtd. Contratada (Clientes)</th>
		                        <th>Qtd. Contratada</th>
		                        <th>Dif. Qtd. Contratada</th>
		                        <th>Dif. Valor Contrato</th>
		                    </tr>
				      	</thead>
				    <tbody>';  
					
					arsort($resultados_ativacao);
					
						foreach ($resultados_ativacao as $resultado) {
							echo "</tr>";								
								echo "<td>".$resultado['contrato']."</td>";
								echo "<td>".$resultado['plano']."</td>";
								echo "<td>".$resultado['tipo_cobranca']."</td>";
								echo "<td data-order='".converteMoeda($resultado['valor_total'],'banco')."'>R$".$resultado['valor_total']."</td>";
								echo "<td data-order='".converteMoeda($resultado['valor_inicial_contrato'],'banco')."'>R$".$resultado['valor_inicial_contrato']."</td>";
								echo "<td data-order='".converteMoeda($resultado['valor_unitario_contrato'],'banco')."'>R$".$resultado['valor_unitario_contrato']."</td>";
								echo "<td data-order='".converteMoeda($resultado['valor_excedente_contrato'],'banco')."'>R$".$resultado['valor_excedente_contrato']."</td>";
								echo "<td>".$resultado['qtd_clientes']."</td>";
								echo "<td>".$resultado['qtd_clientes_teto']."</td>";
								echo "<td>".$resultado['qtd_contratada']."</td>";
								echo "<td>".$resultado['diferenca_qtd_contratada']."</td>";
								echo "<td data-order='".converteMoeda($resultado['diferenca_valor'],'banco')."'>R$".$resultado['diferenca_valor']."</td>";
							echo "</tr>";
						}

			        echo '</tbody>';	

			        echo "<tfoot>";
						echo '<tr>';
							echo '<th></th>';
							echo '<th></th>';
							echo '<th></th>';
							echo '<th></th>';
							echo '<th></th>';
							echo '<th></th>';
							echo '<th></th>';
							echo '<th></th>';
							echo '<th></th>';
							echo '<th>Totais</th>';
							echo '<th>'.$total_diferenca_qtd_ativacao.'</th>';
							echo '<th>R$'.converteMoeda($total_diferenca_valor_ativacao, 'moeda').'</th>';
						echo '</tr>';
					echo "</tfoot> ";	

				echo '</table><hr>';

		}

		if($resultados_up){
			echo "<legend style=\"text-align:center;\"><strong>Upgrade</strong></legend>";
				
				echo '
				<table class="table table-hover dataTable" style="margin-bottom:0;">
				      	<thead>
					        <tr>
		                        <th>Contrato</th>
		                        <th>Plano</th>
		                        <th>Tipo de Cobrança</th>
		                        <th>Valor Contrato</th>
		                        <th>Valor Inicial</th>
		                        <th>Valor Unitário</th>
		                        <th>Valor Excedente</th>
		                        <th>Qtd. Clientes</th>
		                        <th>Qtd. Contratada (Clientes)</th>
		                        <th>Qtd. Contratada</th>
		                        <th>Dif. Qtd. Contratada</th>
		                        <th>Dif. Valor Contrato</th>
		                    </tr>
				      	</thead>
				    <tbody>';  
							
					arsort($resultados_up);

						foreach ($resultados_up as $resultado) {	
							echo "</tr>";								
                                echo "<td>".$resultado['contrato']."</td>";
                                echo "<td>".$resultado['plano']."</td>";
                                echo "<td>".$resultado['tipo_cobranca']."</td>";
                                echo "<td data-order='".converteMoeda($resultado['valor_total'],'banco')."'>R$".$resultado['valor_total']."</td>";
                                echo "<td data-order='".converteMoeda($resultado['valor_inicial_contrato'],'banco')."'>R$".$resultado['valor_inicial_contrato']."</td>";
                                echo "<td data-order='".converteMoeda($resultado['valor_unitario_contrato'],'banco')."'>R$".$resultado['valor_unitario_contrato']."</td>";
                                echo "<td data-order='".converteMoeda($resultado['valor_excedente_contrato'],'banco')."'>R$".$resultado['valor_excedente_contrato']."</td>";
								echo "<td>".$resultado['qtd_clientes']."</td>";
								echo "<td>".$resultado['qtd_clientes_teto']."</td>";
                                echo "<td>".$resultado['qtd_contratada']."</td>";
                                echo "<td>".$resultado['diferenca_qtd_contratada']."</td>";
                                echo "<td data-order='".converteMoeda($resultado['diferenca_valor'],'banco')."'>R$".$resultado['diferenca_valor']."</td>";
                            echo "</tr>";
						}

			        echo '</tbody>';	

			        echo "<tfoot>";
						echo '<tr>';
							echo '<th></th>';
							echo '<th></th>';
							echo '<th></th>';
							echo '<th></th>';
							echo '<th></th>';
							echo '<th></th>';
							echo '<th></th>';
							echo '<th></th>';
							echo '<th></th>';
							echo '<th>Totais</th>';
							echo '<th>'.$total_diferenca_qtd_up.'</th>';
							echo '<th>R$'.converteMoeda($total_diferenca_valor_up, 'moeda').'</th>';
						echo '</tr>';
					echo "</tfoot> ";	

				echo '</table><hr>';
		}	

		if($resultados_ajuste){
			echo "<legend style=\"text-align:center;\"><strong>Ajuste Anual</strong></legend>";

				echo '
				<table class="table table-hover dataTable" style="margin-bottom:0;">
				      	<thead>
					        <tr>
		                        <th>Contrato</th>
		                        <th>Plano</th>
		                        <th>Tipo de Cobrança</th>
		                        <th>Valor Contrato</th>
		                        <th>Valor Inicial</th>
		                        <th>Valor Unitário</th>
		                        <th>Valor Excedente</th>
		                        <th>Qtd. Clientes</th>
		                        <th>Qtd. Contratada (Clientes)</th>
		                        <th>Qtd. Contratada</th>
		                        <th>Dif. Qtd. Contratada</th>
		                        <th>Dif. Valor Contrato</th>
		                    </tr>
				      	</thead>
				    <tbody>';  
					
					arsort($resultados_ajuste);
					
						foreach ($resultados_ajuste as $resultado) {
							echo "</tr>";								
                                echo "<td>".$resultado['contrato']."</td>";
                                echo "<td>".$resultado['plano']."</td>";
                                echo "<td>".$resultado['tipo_cobranca']."</td>";
                                echo "<td data-order='".converteMoeda($resultado['valor_total'],'banco')."'>R$".$resultado['valor_total']."</td>";
                                echo "<td data-order='".converteMoeda($resultado['valor_inicial_contrato'],'banco')."'>R$".$resultado['valor_inicial_contrato']."</td>";
                                echo "<td data-order='".converteMoeda($resultado['valor_unitario_contrato'],'banco')."'>R$".$resultado['valor_unitario_contrato']."</td>";
                                echo "<td data-order='".converteMoeda($resultado['valor_excedente_contrato'],'banco')."'>R$".$resultado['valor_excedente_contrato']."</td>";
								echo "<td>".$resultado['qtd_clientes']."</td>";
								echo "<td>".$resultado['qtd_clientes_teto']."</td>";
                                echo "<td>".$resultado['qtd_contratada']."</td>";
                                echo "<td>".$resultado['diferenca_qtd_contratada']."</td>";
                                echo "<td data-order='".converteMoeda($resultado['diferenca_valor'],'banco')."'>R$".$resultado['diferenca_valor']."</td>";
                            echo "</tr>";
						}

			        echo '</tbody>';

			        echo "<tfoot>";
						echo '<tr>';
							echo '<th></th>';
							echo '<th></th>';
							echo '<th></th>';
							echo '<th></th>';
							echo '<th></th>';
							echo '<th></th>';
							echo '<th></th>';
							echo '<th></th>';
							echo '<th></th>';
							echo '<th>Totais</th>';
							echo '<th>'.$total_diferenca_qtd_ajuste.'</th>';
							echo '<th>R$'.converteMoeda($total_diferenca_valor_ajuste).'</th>';
						echo '</tr>';
					echo "</tfoot> ";	

				echo '</table><hr>';
		}

		if($resultados_down){
        	echo "<legend style=\"text-align:center;\"><strong>Downgrade</strong></legend>";
			
			echo '
				<table class="table table-hover dataTable" style="margin-bottom:0;">
				      	<thead>
					        <tr>
		                        <th>Contrato</th>
		                        <th>Plano</th>
		                        <th>Tipo de Cobrança</th>
		                        <th>Valor Contrato</th>
		                        <th>Valor Inicial</th>
		                        <th>Valor Unitário</th>
		                        <th>Valor Excedente</th>
		                        <th>Qtd. Clientes</th>
		                        <th>Qtd. Contratada (Clientes)</th>
		                        <th>Qtd. Contratada</th>
		                        <th>Dif. Qtd. Contratada</th>
		                        <th>Dif. Valor Contrato</th>
		                    </tr>
				      	</thead>
				    <tbody>';  
							
					arsort($resultados_down);

						foreach ($resultados_down as $resultado) {
							echo "</tr>";								
                                echo "<td>".$resultado['contrato']."</td>";
                                echo "<td>".$resultado['plano']."</td>";
                                echo "<td>".$resultado['tipo_cobranca']."</td>";
                                echo "<td data-order='".converteMoeda($resultado['valor_total'],'banco')."'>R$".$resultado['valor_total']."</td>";
                                echo "<td data-order='".converteMoeda($resultado['valor_inicial_contrato'],'banco')."'>R$".$resultado['valor_inicial_contrato']."</td>";
                                echo "<td data-order='".converteMoeda($resultado['valor_unitario_contrato'],'banco')."'>R$".$resultado['valor_unitario_contrato']."</td>";
                                echo "<td data-order='".converteMoeda($resultado['valor_excedente_contrato'],'banco')."'>R$".$resultado['valor_excedente_contrato']."</td>";
								echo "<td>".$resultado['qtd_clientes']."</td>";
								echo "<td>".$resultado['qtd_clientes_teto']."</td>";
                                echo "<td>".$resultado['qtd_contratada']."</td>";
                                echo "<td>".$resultado['diferenca_qtd_contratada']."</td>";
                                echo "<td data-order='".converteMoeda($resultado['diferenca_valor'],'banco')."'>R$".$resultado['diferenca_valor']."</td>";
                            echo "</tr>";
						}

			        echo '</tbody>';

			        echo "<tfoot>";
						echo '<tr>';
							echo '<th></th>';
							echo '<th></th>';
							echo '<th></th>';
							echo '<th></th>';
							echo '<th></th>';
							echo '<th></th>';
							echo '<th></th>';
							echo '<th></th>';
							echo '<th></th>';
							echo '<th>Totais</th>';
							echo '<th>'.$total_diferenca_qtd_down.'</th>';
							echo '<th>R$'.converteMoeda($total_diferenca_valor_down, 'moeda').'</th>';
						echo '</tr>';
					echo "</tfoot> ";	

				echo '</table><hr>';
		}
		
		if($resultados_cancelamento){
			echo "<legend style=\"text-align:center;\"><strong>Cancelamento</strong></legend>";
			
				echo '
				<table class="table table-hover dataTable" style="margin-bottom:0;">
					      	<thead>
						        <tr>
			                        <th>Contrato</th>
			                        <th>Plano</th>
			                        <th>Tipo de Cobrança</th>
			                        <th>Valor Contrato</th>
			                        <th>Valor Inicial</th>
			                        <th>Valor Unitário</th>
			                        <th>Valor Excedente</th>
									<th>Qtd. Clientes</th>
									<th>Qtd. Contratada (Clientes)</th>
			                        <th>Qtd. Contratada</th>
			                        <th>Dif. Qtd. Contratada</th>
			                        <th>Dif. Valor Contrato</th>
			                    </tr>
					      	</thead>
					    <tbody>';  
								
						arsort($resultados_cancelamento);

							foreach ($resultados_cancelamento as $resultado) {
								echo "</tr>";								
                                    echo "<td>".$resultado['contrato']."</td>";
                                    echo "<td>".$resultado['plano']."</td>";
                                    echo "<td>".$resultado['tipo_cobranca']."</td>";
                                    echo "<td data-order='".converteMoeda($resultado['valor_total'],'banco')."'>R$".$resultado['valor_total']."</td>";
                                    echo "<td data-order='".converteMoeda($resultado['valor_inicial_contrato'],'banco')."'>R$".$resultado['valor_inicial_contrato']."</td>";
                                    echo "<td data-order='".converteMoeda($resultado['valor_unitario_contrato'],'banco')."'>R$".$resultado['valor_unitario_contrato']."</td>";
                                    echo "<td data-order='".converteMoeda($resultado['valor_excedente_contrato'],'banco')."'>R$".$resultado['valor_excedente_contrato']."</td>";
									echo "<td>".$resultado['qtd_clientes']."</td>";
									echo "<td>".$resultado['qtd_clientes_teto']."</td>";
                                    echo "<td>".$resultado['qtd_contratada']."</td>";
                                    echo "<td>".$resultado['diferenca_qtd_contratada']."</td>";
                                    echo "<td data-order='".converteMoeda($resultado['diferenca_valor'],'banco')."'>R$".$resultado['diferenca_valor']."</td>";
                                echo "</tr>";
							}

				        echo '</tbody>';

				        echo "<tfoot>";
							echo '<tr>';
								echo '<th></th>';
								echo '<th></th>';
								echo '<th></th>';
								echo '<th></th>';
								echo '<th></th>';
								echo '<th></th>';
								echo '<th></th>';
								echo '<th></th>';
								echo '<th></th>';
								echo '<th>Totais</th>';
								echo '<th>'.$total_diferenca_qtd_cancelamento.'</th>';
								echo '<th>R$'.converteMoeda($total_diferenca_valor_cancelamento, 'moeda').'</th>';
							echo '</tr>';
						echo "</tfoot> ";	

				echo '</table><hr>';
		}		     

		$dados_consulta_adesao = DBRead('', 'tb_faturamento a',"INNER JOIN tb_faturamento_contrato b ON a.id_faturamento = b.id_faturamento INNER JOIN tb_contrato_plano_pessoa c ON b.id_contrato_plano_pessoa = c.id_contrato_plano_pessoa INNER JOIN tb_pessoa d ON c.id_pessoa = d.id_pessoa INNER JOIN tb_plano e ON a.id_plano = e.id_plano WHERE e.cod_servico = '".$filtro_servico."' AND a.data_referencia = '".$referencia."' AND a.adesao = '1' ", "a.*, b.*, c.nome_contrato, d.nome, a.valor_adesao AS valor_adesao_faturamento");

		if($dados_consulta_adesao){
			foreach($dados_consulta_adesao as $dado_consulta){
				//NOME DO CONTRATO
				if($dado_consulta['nome_contrato']){
					$nome_contrato = " (".$dado_consulta['nome_contrato'].") ";
				}else{
					$nome_contrato = '';
				}				
				$contrato = $dado_consulta['nome']." ".$nome_contrato;

				$dados_planos = DBRead('','tb_plano',"WHERE id_plano = '".$dado_consulta['id_plano']."'");
				$plano = $dados_planos[0]['nome'];
				
				$valor_adesao_faturamento = $dado_consulta['valor_adesao_faturamento'];
				
				$resultados_adesao[$cont_adesao]['contrato'] = $contrato;
				$resultados_adesao[$cont_adesao]['plano'] = $plano;
				$resultados_adesao[$cont_adesao]['valor_adesao_faturamento'] = converteMoeda($valor_adesao_faturamento,'moeda');
				
				$total_adesao = $total_adesao + $valor_adesao_faturamento;
				$cont_adesao++;
			}

			echo "<legend style=\"text-align:center;\"><strong>Adesões</strong></legend>";
			
			echo '
			<table class="table table-hover dataTable" style="margin-bottom:0;">
						<thead>
							<tr>
								<th>Contrato</th>
								<th>Plano</th>
								<th>Valor da Adesão</th>
							</tr>
						</thead>
					<tbody>';  

					arsort($resultados_adesao);

					foreach ($resultados_adesao as $resultado) {
						echo "</tr>";								
							echo "<td>".$resultado['contrato']."</td>";
							echo "<td>".$resultado['plano']."</td>";
							echo "<td data-order='".converteMoeda($resultado['valor_adesao_faturamento'],'banco')."'>R$".$resultado['valor_adesao_faturamento']."</td>";
						echo "</tr>";
					}
					echo "<tfoot>";
						echo '<tr>';
							echo '<th></th>';
							echo '<th>Totais</th>';
							echo '<th>R$'.converteMoeda($total_adesao, 'moeda').'</th>';
						echo '</tr>';
					echo "</tfoot> ";	
				echo '</tbody>';					          
			echo '</table>
			
			<hr>';
		}

		echo "<legend style=\"text-align:center;\"><strong>Totalizador de Valores (com Adesões)</strong></legend>";
			
		echo '
		<table class="table table-hover dataTable" style="margin-bottom:0;">
			      	<thead>
				        <tr>
	                        <th style="text-align:center;">Novos Contratos</th>
	                        <th style="text-align:center;">Upgrade</th>
	                        <th style="text-align:center;">Ajuste Anual</th>
	                        <th style="text-align:center;">Downgrade</th>
	                        <th style="text-align:center;">Cancelamento</th>
	                        <th style="text-align:center;">Adesões</th>
	                        <th style="text-align:center;">Balanço Final</th>
	                    </tr>
			      	</thead>
			    <tbody>';  

			    $balanço = ($total_up + $total_ativacao + $total_ajuste + $total_adesao) - ($total_down + $total_cancelamento);

				echo "<tr>";
					echo "<td style='text-align:center;'>R$".converteMoeda($total_ativacao,'moeda')."</td>";
					echo "<td style='text-align:center;'>R$".converteMoeda($total_up,'moeda')."</td>";
					echo "<td style='text-align:center;'>R$".converteMoeda($total_ajuste,'moeda')."</td>";
					echo "<td style='text-align:center;'>R$".converteMoeda($total_down,'moeda')."</td>";
					echo "<td style='text-align:center;'>R$".converteMoeda($total_cancelamento,'moeda')."</td>";
					echo "<td style='text-align:center;'>R$".converteMoeda($total_adesao,'moeda')."</td>";
					if($balanço >=0){
						echo "<td class='success' style='text-align:center;'><strong>R$".converteMoeda($balanço,'moeda')."</strong></td>";
					}else{
						echo "<td class='danger' style='text-align:center;'><strong>R$".converteMoeda($balanço,'moeda')."</strong></td>";
					}
				echo "</tr>";
	        echo '</tbody>';					          
		echo '</table><hr>';

		echo "<legend style=\"text-align:center;\"><strong>Totalizador de Valores (sem Adesões)</strong></legend>";
			
		echo '
		<table class="table table-hover dataTable" style="margin-bottom:0;">
			      	<thead>
				        <tr>
	                        <th style="text-align:center;">Novos Contratos</th>
	                        <th style="text-align:center;">Upgrade</th>
	                        <th style="text-align:center;">Ajuste Anual</th>
	                        <th style="text-align:center;">Downgrade</th>
	                        <th style="text-align:center;">Cancelamento</th>
	                        <th style="text-align:center;">Balanço Final</th>
	                    </tr>
			      	</thead>
			    <tbody>';  

			    $balanço = ($total_up + $total_ativacao+$total_ajuste) - ($total_down + $total_cancelamento);

				echo "<tr>";
					echo "<td style='text-align:center;'>R$".converteMoeda($total_ativacao,'moeda')."</td>";
					echo "<td style='text-align:center;'>R$".converteMoeda($total_up,'moeda')."</td>";
					echo "<td style='text-align:center;'>R$".converteMoeda($total_ajuste,'moeda')."</td>";
					echo "<td style='text-align:center;'>R$".converteMoeda($total_down,'moeda')."</td>";
					echo "<td style='text-align:center;'>R$".converteMoeda($total_cancelamento,'moeda')."</td>";
					if($balanço >=0){
						echo "<td class='success' style='text-align:center;'><strong>R$".converteMoeda($balanço,'moeda')."</strong></td>";
					}else{
						echo "<td class='danger' style='text-align:center;'><strong>R$".converteMoeda($balanço,'moeda')."</strong></td>";
					}
				echo "</tr>";
	        echo '</tbody>';					          
		echo '</table><hr>';


		echo "<div class=\"col-md-4 col-md-offset-4\" style=\"padding: 0\">";
			echo "<legend style=\"text-align:center;\"><strong>Totalizador de Quantidades</strong></legend>";
			

			echo '
			<table class="table table-hover dataTable" style="margin-bottom:0;">
				      	<thead>
					        <tr>
		                        <th style="text-align:center;">Quantidades Contratadas</th>
		                    </tr>
				      	</thead>
				    <tbody>';  

				    $balanço_diferenca_qtd = $total_diferenca_qtd_up + $total_diferenca_qtd_ativacao + $total_diferenca_qtd_ajuste +$total_diferenca_qtd_down + $total_diferenca_qtd_cancelamento;

					echo "<tr>";
						
						if($balanço_diferenca_qtd >=0){
							echo "<td class='success' style=\"text-align:center;\"><strong>".$balanço_diferenca_qtd."</strong></td>";
						}else{
							echo "<td class='danger' style=\"text-align:center;\"><strong>".$balanço_diferenca_qtd."</strong></td>";
						}

						
					echo "</tr>";
		        echo '</tbody>';					          
			echo '</table>';
        echo "<br><br><br>";
		echo "</div>";


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

function relatorio_faturamento_atendimento($referencia, $dados_meses){
   
	$fila = "'callATENDIMENTOvip','callATENDIMENTOalta','callATENDIMENTOnormal','callATENDIMENTObaixa','callATENDIMENTOnotificacaoparada', 'callATENDIMENTOvipEXT','callATENDIMENTOaltaEXT','callATENDIMENTOnormalEXT','callATENDIMENTObaixaEXT','callATENDIMENTOnotificacaoparadaEXT','callATENDIMENTOvipEXP','callATENDIMENTOaltaEXP','callATENDIMENTOnormalEXP','callATENDIMENTObaixaEXP','callATENDIMENTOnotificacaoparadaEXP'";

	$mes_ano = explode("-", $referencia);
	$mes = $mes_ano[1];
	$ano = $mes_ano[0];

	$periodo_amostra = "<span style=\"font-size: 14px;\"><strong>Referência: </strong>".$dados_meses[$mes]." de ".$ano."</span>";
	$gerado = "<span style=\"font-size: 14px;\"><strong>Gerado em: </strong> ".converteDataHora(getDataHora())."</span>";	

	echo "<div class=\"col-md-12\" style=\"padding: 0\">";
	echo "<legend style=\"text-align:center;\"><strong>Relatório de Indicadores - Faturamento - Atendimentos</strong><br>$gerado</legend>";
	echo "<legend style=\"text-align:center;\">$periodo_amostra</legend>";

	$dados_consulta = DBRead('', 'tb_faturamento a',"INNER JOIN tb_faturamento_contrato b ON a.id_faturamento = b.id_faturamento INNER JOIN tb_contrato_plano_pessoa c ON b.id_contrato_plano_pessoa = c.id_contrato_plano_pessoa INNER JOIN tb_pessoa d ON c.id_pessoa = d.id_pessoa INNER JOIN tb_plano e ON a.id_plano = e.id_plano WHERE b.contrato_pai = '1' AND a.data_referencia = '".$referencia."' AND a.status = '1' AND e.cod_servico = 'call_suporte' ", "a.*, b.*, c.nome_contrato, c.qtd_clientes, d.nome, a.status AS status_contrato");

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
                        <th>Valor Unitário (Via Telefone)</th>
                        <th>Valor Excedente (Via Telefone) </th>
                        <th>Qtd Contratada (Via Telefone) </th>
						<th>Qtd Efetuada (Via Telefone) </th>
                        <th>Qtd de Excedentes (Via Telefone) </th>
						<th>Qtd de Desafogos (Via Telefone) </th>
						
						<th>Qtd Contratada (Via Texto)</th>
						<th>Qtd Efetuada (Via Texto)</th>
						<th>Qtd Excedente (Via Texto)</th>
						<th>Qtd Desafogo (Via Texto)</th>
						<th>Valor Unitário (Via Texto)</th>
						<th>Valor Excedente (Via Texto)</th>
						<th>Qtd Efetuada Monitoramento</th>
						<th>Qtd Efetuada Total</th>
						<th>Qtd Clientes</th>
						<th>Qtd Contratada (Clientes))</th>
						
                        <th>Total de Acréscimos</th>
                        <th>Total de Descontos</th>
                        <th>Valor da Cobrança</th>					
                        <th>Valor Médio</th>					
                        <th>Valor por Minuto</th>					
                        <th>TMA</th>					
                        <th>Nota Média</th>					
                        <th>Ligações com Nota (%)</th>					
                        <th>TME</th>					
                        <th>TMP</th>					
                        <th>Pedidos de Ajuda</th>					
                        <th>Sistema de Gestão</th>					
                    </tr>
                </thead>
                <tbody>
		';           
		
			$contador_qtd_contratada_texto = 0;
			$contador_qtd_efetuada_texto = 0;
			$contador_qtd_excedente_texto = 0;
			$contador_qtd_desafogo_texto = 0;
			$contador_qtd_monitoramento = 0;
			$contador_qtd_efetuada_voz = 0;
	
			$contador_valor_total_contrato = 0;
			
			$contador_qtd_contratada = 0;

			$contador_qtd_efetuada_total = 0;

			$contador_qtd_excedente = 0;
			$contador_qtd_desafogo = 0;
			
			$contador_acrescimo = 0;
			$contador_desconto = 0;
			$contador_valor_cobranca = 0;
			$contador_valor_medio = 0;
			$contador_valor_minuto = 0;
			
			$contador_tma = 0;
			$contador_nota_media = 0;
			$contador_ligacoes_nota = 0;
			$contador_tme = 0;
			$contador_tmp = 0;
			
			$contador_qtd_ajuda = 0;
			$contador_qtd_clientes = 0;
			$contador_qtd_clientes_teto = 0;

			$total_soma_ta = 0;
			$total_soma_te = 0;
			$total_soma_tp = 0;
			$total_ligacoes_nota = 0;
			$total_nota_media = 0;

			$total_qtd_entradas = 0;
			$total_qtd_perdidas = 0;
			$total_qtd_notas = 0;

			$dados_valor_medio_plano = array();
			$dados_valor_medio_sistema_gestao = array();

			foreach($dados_consulta as $dado_consulta){
			$filtro_id_asterisk_contrato = '';
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
				
						$dados_parametros = DBRead('', 'tb_parametros',"WHERE id_contrato_plano_pessoa = '".$conteudo_consulta_filho['id_contrato_plano_pessoa']."'");
						$id_asterisk_contrato = $dados_parametros[0]['id_asterisk'];
				
						$filtro_id_asterisk_contrato .= "a.data2 LIKE '$id_asterisk_contrato%' OR ";
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

			$dados_planos = DBRead('','tb_plano',"WHERE id_plano = '".$dado_consulta['id_plano']."'");
			
			$data_inicial = new DateTime($ano."-".$mes."-01");
			$data_inicial->modify('first day of this month');

			$data_final = new DateTime($ano."-".$mes."-01");
			$data_final->modify('last day of this month');

			$dados_ajuda = DBRead('', 'tb_solicitacao_ajuda',"WHERE data_inicio >= '".$data_inicial->format('Y-m-d')." 00:00:00' AND data_inicio <= '".$data_final->format('Y-m-d')." 23:59:59' AND id_contrato_plano_pessoa = '".$dado_consulta['id_contrato_plano_pessoa']."' ", "COUNT(id_contrato_plano_pessoa) AS cont");
			
			$dados_sistema_gestao = DBRead('', 'tb_sistema_gestao_contrato a',"INNER JOIN tb_tipo_sistema_gestao b ON a.id_tipo_sistema_gestao = b.id_tipo_sistema_gestao WHERE id_contrato_plano_pessoa = '".$dado_consulta['id_contrato_plano_pessoa']."' ", "b.nome");

			$dados_parametros = DBRead('', 'tb_parametros',"WHERE id_contrato_plano_pessoa = '".$dado_consulta['id_contrato_plano_pessoa']."'");
			$id_asterisk_contrato = $dados_parametros[0]['id_asterisk'];


           	if($dados_sistema_gestao){
           		$sistema_gestao  = $dados_sistema_gestao[0]['nome'];
           	}else{
           		$sistema_gestao  = 'N/D';
           	}

			$qtd_entradas = 0;
			$soma_notas = 0;
			$qtd_notas = 0;
			$soma_ta = 0;
			$soma_te = 0;

			$filtro_entradas = "";

			$filtro_entradas .= " AND b.time >= '".$data_inicial->format('Y-m-d')." 00:00:00'";
			$filtro_entradas .= " AND b.time <= '".$data_final->format('Y-m-d')." 23:59:59'";
			$filtro_id_asterisk_contrato = "";
			$filtro_id_asterisk_contrato .= " a.data2 LIKE '$id_asterisk_contrato%'";
			
			// $filtro_entradas .= " AND ($filtro_id_asterisk_contrato) AND a.queuename IN ('callATENDIMENTOvip','callATENDIMENTOalta','callATENDIMENTOnormal','callATENDIMENTObaixa','callATENDIMENTOnotificacaoparada', 'callATENDIMENTOvipEXT','callATENDIMENTOaltaEXT','callATENDIMENTOnormalEXT','callATENDIMENTObaixaEXT','callATENDIMENTOnotificacaoparadaEXT','callATENDIMENTOvipEXP','callATENDIMENTOaltaEXP','callATENDIMENTOnormalEXP','callATENDIMENTObaixaEXP','callATENDIMENTOnotificacaoparadaEXP') AND (c.data2 >= 30 OR c.data4 >= 30)";
			$filtro_entradas .= " AND ($filtro_id_asterisk_contrato) AND (c.data2 >= 30 OR c.data4 >= 30)";
			
			// $dados_entradas = DBRead('snep','queue_log a',"INNER JOIN queue_log b ON (b.callid = a.callid AND b.event = 'CONNECT') INNER JOIN queue_log c ON (c.callid = a.callid AND (c.event = 'COMPLETEAGENT' OR c.event = 'COMPLETECALLER' OR c.event = 'BLINDTRANSFER' OR c.event = 'ATTENDEDTRANSFER')) LEFT JOIN pesquisa d ON  a.callid = d.UNIQUEID WHERE a.event = 'ENTERQUEUE' $filtro_entradas GROUP BY b.id ORDER BY b.id", "a.callid AS 'enterqueue_callid', c.event AS 'finalizacao_event', c.data1 AS 'finalizacao_data1', c.data2 AS 'finalizacao_data2', c.data3 AS 'finalizacao_data3', d.nota AS 'nota', (SELECT SUM(data3) FROM queue_log e WHERE e.callid = a.callid AND e.event = 'EXITWITHTIMEOUT') AS 'tempo_espera_timeout'");
			$dados_entradas = DBRead('snep','queue_log a',"INNER JOIN queue_log b ON (b.callid = a.callid AND b.event = 'CONNECT') INNER JOIN queue_log c ON (c.callid = a.callid AND (c.event = 'COMPLETEAGENT' OR c.event = 'COMPLETECALLER' OR c.event = 'BLINDTRANSFER' OR c.event = 'ATTENDEDTRANSFER')) LEFT JOIN pesquisa d ON  a.callid = d.UNIQUEID WHERE a.event = 'ENTERQUEUE' $filtro_entradas GROUP BY b.id ORDER BY b.id", "a.queuename AS 'enterqueue_queuename', a.callid AS 'enterqueue_callid', c.event AS 'finalizacao_event', c.data1 AS 'finalizacao_data1', c.data2 AS 'finalizacao_data2', c.data3 AS 'finalizacao_data3', d.nota AS 'nota', (SELECT SUM(data3) FROM queue_log e WHERE e.callid = a.callid AND e.event = 'EXITWITHTIMEOUT') AS 'tempo_espera_timeout'");

			if($dados_entradas){
				foreach ($dados_entradas as $conteudo_entradas) {
					if(preg_match("/'".$conteudo_entradas['enterqueue_queuename']."'/i", $fila) || !$fila){

						$info_chamada = array(
							'tempo_espera' => '',
							'tempo_atendimento' => '',
							'nota' => ''
						);
						$info_chamada['nota'] = $conteudo_entradas['nota'];
						if($conteudo_entradas['finalizacao_event']=='COMPLETEAGENT' || $conteudo_entradas['finalizacao_event']=='COMPLETECALLER'){
							$info_chamada['tempo_espera'] = $conteudo_entradas['finalizacao_data1'];
							$info_chamada['tempo_atendimento'] = $conteudo_entradas['finalizacao_data2'];	
						}elseif(($conteudo_entradas['finalizacao_event']=='BLINDTRANSFER' || $conteudo_entradas['finalizacao_event']=='ATTENDEDTRANSFER') && $conteudo_entradas['finalizacao_data1'] != 'LINK'){
							$info_chamada['tempo_espera'] = $conteudo_entradas['finalizacao_data3'];		
							$info_chamada['tempo_atendimento'] = $conteudo_entradas['finalizacao_data4'];
						}
						if($conteudo_entradas['tempo_espera_timeout'] && $conteudo_entradas['tempo_espera_timeout'] > 0){
							$info_chamada['tempo_espera'] += $conteudo_entradas['tempo_espera_timeout'];
						}
						$qtd_entradas++;
						if($info_chamada['nota']){
							$soma_notas += $info_chamada['nota'];
							$qtd_notas++;
						}
						$soma_ta += $info_chamada['tempo_atendimento'];	
						$soma_te += $info_chamada['tempo_espera'];

					}
				}
			}

			//consulta perdidas
			$soma_te_perdidas = 0;
			$qtd_perdidas = 0;
			
			$filtro_perdidas = '';
			$filtro_perdidas .= " AND a.time >= '".$data_inicial->format('Y-m-d')." 00:00:00'";
			$filtro_perdidas .= " AND a.time <= '".$data_final->format('Y-m-d')." 23:59:59'";
			$filtro_perdidas .= " AND a.data2 LIKE '$id_asterisk_contrato%' AND (b.data3 >= 5)";
        
            $dados_perdidas = DBRead('snep','queue_log a',"INNER JOIN queue_log b ON (b.callid = a.callid AND (b.event = 'ABANDON' OR b.event = 'EXITWITHTIMEOUT')) WHERE a.event = 'ENTERQUEUE' AND a.callid NOT IN (SELECT callid FROM queue_log c WHERE c.callid = a.callid AND c.event = 'CONNECT') $filtro_perdidas GROUP BY a.callid ORDER BY a.callid", "a.queuename AS 'enterqueue_queuename', a.callid AS 'enterqueue_callid', a.data2 AS 'enterqueue_data2', (SELECT SUM(f.data3) FROM queue_log f WHERE f.callid = a.callid AND (f.event = 'ABANDON' OR f.event = 'EXITWITHTIMEOUT')) AS 'finalizacao_data3'");

			if($dados_perdidas){		
				foreach ($dados_perdidas as $conteudo_perdidas) {
					if(preg_match("/'".$conteudo_perdidas['enterqueue_queuename']."'/i", $fila) || !$fila){
						$qtd_perdidas++;			
						$soma_te_perdidas += $conteudo_perdidas['finalizacao_data3'];
					}
				}
			}

			$plano = $dados_planos[0]['nome'];
			
			$valor_total_contrato = $dado_consulta['valor_total_contrato'];
			$valor_inicial_contrato = $dado_consulta['valor_inicial_contrato'];
			$valor_unitario_contrato = $dado_consulta['valor_unitario_contrato'];
			$valor_excedente_contrato = $dado_consulta['valor_excedente_contrato'];

			$qtd_contratada = $dado_consulta['qtd_contratada'];
			$qtd_efetuada = $dado_consulta['qtd_efetuada'];
			$qtd_excedente = $dado_consulta['qtd_excedente'];
			$qtd_desafogo = $dado_consulta['qtd_desafogo'];

			$acrescimo = $dado_consulta['acrescimo'];
			$desconto = $dado_consulta['desconto'];
			$valor_cobranca = $dado_consulta['valor_cobranca'];

				$valor_medio = $dado_consulta['valor_cobranca']/( ($dado_consulta['qtd_efetuada'] + $dado_consulta['qtd_efetuada_texto']) == 0 ? 1 : ($dado_consulta['qtd_efetuada'] + $dado_consulta['qtd_efetuada_texto']));

				$dados_valor_medio_plano[$plano]['valor_cobranca'] += $dado_consulta['valor_cobranca'];
				$dados_valor_medio_plano[$plano]['qtd_texto'] += $dado_consulta['qtd_efetuada_texto'];
				$dados_valor_medio_plano[$plano]['qtd_voz'] += $dado_consulta['qtd_efetuada'];
				$dados_valor_medio_plano[$plano]['qtd_contratada'] += $dado_consulta['qtd_contratada'];
				$dados_valor_medio_plano[$plano]['qtd_contratada_texto'] += $dado_consulta['qtd_contratada_texto'];
				$dados_valor_medio_plano[$plano]['valor_total_contrato'] += $dado_consulta['valor_total_contrato'];

				$dados_valor_medio_sistema_gestao[$sistema_gestao]['valor_cobranca'] += $dado_consulta['valor_cobranca'];
				$dados_valor_medio_sistema_gestao[$sistema_gestao]['qtd_texto'] += $dado_consulta['qtd_efetuada_texto'];
				$dados_valor_medio_sistema_gestao[$sistema_gestao]['qtd_voz'] += $dado_consulta['qtd_efetuada'];
				$dados_valor_medio_sistema_gestao[$sistema_gestao]['qtd_contratada'] += $dado_consulta['qtd_contratada'];
				$dados_valor_medio_sistema_gestao[$sistema_gestao]['qtd_contratada_texto'] += $dado_consulta['qtd_contratada_texto'];
				$dados_valor_medio_sistema_gestao[$sistema_gestao]['valor_total_contrato'] += $dado_consulta['valor_total_contrato'];

			$valor_minuto = ($valor_medio)/($soma_ta/$qtd_entradas)*60;

			$tma = $soma_ta/($qtd_entradas == 0 ? 1 : $qtd_entradas);
			$nota_media = $soma_notas/($qtd_notas == 0 ? 1 : $qtd_notas);
			$ligacoes_nota = $qtd_notas*100/($qtd_entradas == 0 ? 1 : $qtd_entradas);
			$tme = $soma_te/($qtd_entradas == 0 ? 1 : $qtd_entradas);
			$tmp = $soma_te_perdidas/($qtd_perdidas == 0 ? 1 : $qtd_perdidas);

			$qtd_ajuda = $dados_ajuda[0]['cont'];
			

			$qtd_contratada_texto = $dado_consulta['qtd_contratada_texto'];
			$qtd_efetuada_texto = $dado_consulta['qtd_efetuada_texto'];
			$qtd_excedente_texto = $dado_consulta['qtd_excedente_texto'];
			$qtd_desafogo_texto = $dado_consulta['qtd_desafogo_texto'];
			$valor_unitario_texto_contrato = $dado_consulta['valor_unitario_texto_contrato'];
			$valor_excedente_texto_contrato = $dado_consulta['valor_excedente_texto_contrato'];
			$qtd_monitoramento = $dado_consulta['qtd_monitoramento'];

			$qtd_efetuada_voz = $qtd_efetuada - $qtd_monitoramento;
			$qtd_efetuada_total = $qtd_efetuada + $qtd_efetuada_texto;

			if($dado_consulta['qtd_clientes'] == 0){
				$qtd_clientes = 'N/D';
			}else{
				$qtd_clientes = $dado_consulta['qtd_clientes'];
			}
			$qtd_clientes_teto = $dado_consulta['qtd_clientes_teto'];

			echo "<tr>";
			   	echo "<td style='vertical-align: middle'>".$contrato."</td>";
			   	echo "<td style='vertical-align: middle'>".$plano."</td>";
				echo "<td style='vertical-align: middle'>".$tipo_cobranca."</td>";
				echo "<td style='vertical-align: middle'data-order='$valor_total_contrato'>R$".converteMoeda($valor_total_contrato,'moeda')."</td>";
				echo "<td style='vertical-align: middle'data-order='$valor_inicial_contrato'>R$".converteMoeda($valor_inicial_contrato,'moeda')."</td>";
				echo "<td style='vertical-align: middle'data-order='$valor_unitario_contrato'>R$".converteMoeda($valor_unitario_contrato,'moeda')."</td>";
				echo "<td style='vertical-align: middle'data-order='$valor_excedente_contrato'>R$".converteMoeda($valor_excedente_contrato,'moeda')."</td>";
				echo "<td style='vertical-align: middle'>".$qtd_contratada."</td>";
				echo "<td style='vertical-align: middle'>".$qtd_efetuada_voz."</td>";
				echo "<td style='vertical-align: middle'>".$qtd_excedente."</td>";
				echo "<td style='vertical-align: middle'>".$qtd_desafogo."</td>";
				echo "<td style='vertical-align: middle'>".$qtd_contratada_texto."</td>";
				echo "<td style='vertical-align: middle'>".$qtd_efetuada_texto."</td>";
				echo "<td style='vertical-align: middle'>".$qtd_excedente_texto."</td>";
				echo "<td style='vertical-align: middle'>".$qtd_desafogo_texto."</td>";
				echo "<td style='vertical-align: middle'>R$".converteMoeda($valor_unitario_texto_contrato,'moeda')."</td>";
				echo "<td style='vertical-align: middle'>R$".converteMoeda($valor_excedente_texto_contrato,'moeda')."</td>";
				echo "<td style='vertical-align: middle'>".$qtd_monitoramento."</td>";
				echo "<td style='vertical-align: middle'>".$qtd_efetuada_total."</td>";	
				echo "<td style='vertical-align: middle'>".$qtd_clientes."</td>";			
				echo "<td style='vertical-align: middle'>".$qtd_clientes_teto."</td>";		
				echo "<td style='vertical-align: middle' data-order='$acrescimo'>R$".converteMoeda($acrescimo, 'moeda')."</td>";
				echo "<td style='vertical-align: middle' data-order='$desconto'>R$".converteMoeda($desconto, 'moeda')."</td>";
				echo "<td style='vertical-align: middle' data-order='$valor_cobranca'><strong>R$".converteMoeda($valor_cobranca, 'moeda')."</strong></td>";				
				echo "<td style='vertical-align: middle' data-order='$valor_medio'>R$".converteMoeda($valor_medio, 'moeda')."</td>";
				echo "<td style='vertical-align: middle' data-order='$valor_minuto'>R$".converteMoeda($valor_minuto, 'moeda')."</td>";				
				echo "<td style='vertical-align: middle'>".gmdate("H:i:s", $tma)."</td>";				
				echo "<td style='vertical-align: middle'>".sprintf("%01.2f", round($nota_media, 2))."</td>";				
				echo "<td style='vertical-align: middle'>".sprintf("%01.2f", round($ligacoes_nota, 2))."%</td>";				
				echo "<td style='vertical-align: middle'>".gmdate("H:i:s", $tme)."</td>";				
				echo "<td style='vertical-align: middle'>".gmdate("H:i:s", $tmp)."</td>";				
				echo "<td style='vertical-align: middle'>".$qtd_ajuda."</td>";			
				echo "<td style='vertical-align: middle'>".$sistema_gestao."</td>";				
			echo "</tr>";	
			
			$contador_qtd_contratada_texto = $qtd_contratada_texto + $contador_qtd_contratada_texto;
			$contador_qtd_efetuada_texto = $qtd_efetuada_texto + $contador_qtd_efetuada_texto;
			$contador_qtd_excedente_texto = $qtd_excedente_texto + $contador_qtd_excedente_texto;
			$contador_qtd_desafogo_texto = $qtd_desafogo_texto + $contador_qtd_desafogo_texto;
			$contador_qtd_monitoramento = $qtd_monitoramento + $contador_qtd_monitoramento;
			$contador_qtd_efetuada_voz = $qtd_efetuada_voz + $contador_qtd_efetuada_voz;

			$contador_valor_total_contrato = $valor_total_contrato + $contador_valor_total_contrato;
			
			$contador_qtd_contratada = $qtd_contratada + $contador_qtd_contratada;
			$contador_qtd_efetuada_total = $qtd_efetuada_total + $contador_qtd_efetuada_total;
			$contador_qtd_excedente = $qtd_excedente + $contador_qtd_excedente;
			$contador_qtd_desafogo = $qtd_desafogo + $contador_qtd_desafogo;
			
			$contador_acrescimo = $acrescimo + $contador_acrescimo;
			$contador_desconto = $desconto + $contador_desconto;
			$contador_valor_cobranca = $valor_cobranca + $contador_valor_cobranca;		
	
			$contador_qtd_ajuda = $qtd_ajuda + $contador_qtd_ajuda;
			$contador_qtd_clientes = $qtd_clientes + $contador_qtd_clientes;
			$contador_qtd_clientes_teto = $qtd_clientes_teto + $contador_qtd_clientes_teto;
						
			$total_qtd_entradas = $qtd_entradas + $total_qtd_entradas;
			$total_qtd_perdidas = $qtd_perdidas + $total_qtd_perdidas;
			$total_qtd_notas = $qtd_notas + $total_qtd_notas;

			$total_soma_ta = $soma_ta + $total_soma_ta;
			$total_soma_te = $soma_te + $total_soma_te;
			$total_soma_tp = $soma_te_perdidas + $total_soma_tp;
			$total_ligacoes_nota = $qtd_notas + $total_ligacoes_nota;
			$total_nota_media = $soma_notas + $total_nota_media;
		}

		$contador_valor_medio = $contador_valor_cobranca/($contador_qtd_efetuada_total == 0 ? 1 : $contador_qtd_efetuada_total);
		$contador_tma = $total_soma_ta/($total_qtd_entradas == 0 ? 1 : $total_qtd_entradas);
		$contador_nota_media = $total_nota_media/($total_qtd_notas == 0 ? 1 : $total_qtd_notas);
		$contador_ligacoes_nota = ($total_ligacoes_nota*100)/($total_qtd_entradas == 0 ? 1 : $total_qtd_entradas);
		$contador_tme = $total_soma_te/$total_qtd_entradas;
		$contador_tmp = $total_soma_tp/$total_qtd_perdidas;
		$contador_valor_minuto = ($contador_valor_medio)/($contador_tma)*60;

		echo '		
			</tbody>';
			echo "<tfoot>";
					
					echo '<tr>';
						echo '<th>Totais</th>';
						echo '<th></th>';
						echo '<th></th>';
						echo '<th>R$'.converteMoeda($contador_valor_total_contrato,'moeda').'</th>';
						echo '<th></th>';
						echo '<th></th>';
						echo '<th></th>';
						echo '<th>'.$contador_qtd_contratada.'</th>';
						echo '<th>'.$contador_qtd_efetuada_voz.'</th>';
						echo '<th>'.$contador_qtd_excedente.'</th>';
						echo '<th>'.$contador_qtd_desafogo.'</th>';
						echo '<th>'.$contador_qtd_contratada_texto.'</th>';
						echo '<th>'.$contador_qtd_efetuada_texto.'</th>';
						echo '<th>'.$contador_qtd_excedente_texto.'</th>';
						echo '<th>'.$contador_qtd_desafogo_texto.'</th>';
						echo '<th></th>';
						echo '<th></th>';
						echo '<th>'.$contador_qtd_monitoramento.'</th>';
						echo '<th>'.$contador_qtd_efetuada_total.'</th>';
						echo '<th>'.$contador_qtd_clientes.'</th>';
						echo '<th>'.$contador_qtd_clientes_teto.'</th>';
						echo '<th>R$'.converteMoeda($contador_acrescimo,'moeda').'</th>';
						echo '<th>R$'.converteMoeda($contador_desconto,'moeda').'</th>';
						echo '<th>R$'.converteMoeda($contador_valor_cobranca,'moeda').'</th>';
						echo '<th>R$'.converteMoeda($contador_valor_medio, 'moeda').'</th>';
						echo '<th>R$'.converteMoeda($contador_valor_minuto, 'moeda').'</th>';
						echo '<th>'.gmdate("H:i:s", $contador_tma).'</th>';
						echo '<th>'.sprintf("%01.2f", round($contador_nota_media, 2)).'</th>';
						echo '<th>'.sprintf("%01.2f", round($contador_ligacoes_nota, 2)).'%</th>';
						echo '<th>'.gmdate("H:i:s", $contador_tme).'</th>';
						echo '<th>'.gmdate("H:i:s", $contador_tmp).'</th>';
						echo '<th>'.$contador_qtd_ajuda.'</th>';
						echo '<th><th>';
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
								extend: 'excelHtml5', footer: true,
								text: '<i class=\"fa fa-file-excel-o\" aria-hidden=\"true\"></i> Exportar',
								filename: 'relatorio_indicadores_financeiro_callcenter',
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

			if($dados_valor_medio_plano){
				
				asort($dados_valor_medio_plano);		

				echo "
				<div class='col-md-12 style='padding: 0'>";
				
				echo "<legend style=\"text-align:center;\">Contagem por Plano</legend>";

					echo '
					<table class="table table-hover dataTableAgrupado" style="margin-bottom:0;">
						<thead>
							<tr>
								<th>Plano</th>
								<th>Qtd Efetuada (Via Telefone)</th>
								<th>Qtd Efetuada (Via Texto)</th>
								<th>Qtd Efetuada Total</th>
								<th>Valor da Cobrança</th>
								<th>Valor Médio (Efetuadas)</th>

								<th>Qtd Contratada (Via Telefone)</th>
								<th>Qtd Contratada (Via Texto)</th>
								<th>Qtd Contratada Total</th>
								<th>Valor do Contrato</th>
								<th>Valor Médio (Contratadas)</th>

							</tr>
						</thead>
						<tbody>
					';           

					foreach ($dados_valor_medio_plano as $nome_plano => $conteudo_valor_medio_plano) {
						
						$qtd_total = $conteudo_valor_medio_plano['qtd_texto'] + $conteudo_valor_medio_plano['qtd_voz'];
						$valor_medio = $conteudo_valor_medio_plano['valor_cobranca']/($qtd_total == 0 ? 1 : $qtd_total);
						
						$qtd_contratada_total = $conteudo_valor_medio_plano['qtd_contratada'] + $conteudo_valor_medio_plano['qtd_contratada_texto'];
						$valor_medio_contratado = $conteudo_valor_medio_plano['valor_total_contrato']/($qtd_contratada_total == 0 ? 1 : $qtd_contratada_total);

						echo "<tr>";
							echo "<td style='vertical-align: middle'>".$nome_plano."</td>";
							echo "<td style='vertical-align: middle'>".$conteudo_valor_medio_plano['qtd_voz']."</td>";
							echo "<td style='vertical-align: middle'>".$conteudo_valor_medio_plano['qtd_texto']."</td>";
							echo "<td style='vertical-align: middle'>".$qtd_total."</td>";
							echo "<td style='vertical-align: middle'>R$".converteMoeda($conteudo_valor_medio_plano['valor_cobranca'], 'moeda')."</td>";
							echo "<td style='vertical-align: middle'>R$".converteMoeda($valor_medio, 'moeda')."</td>";

							echo "<td style='vertical-align: middle'>".$conteudo_valor_medio_plano['qtd_contratada']."</td>";
							echo "<td style='vertical-align: middle'>".$conteudo_valor_medio_plano['qtd_contratada_texto']."</td>";
							echo "<td style='vertical-align: middle'>".$qtd_contratada_total."</td>";
							echo "<td style='vertical-align: middle'>R$".converteMoeda($conteudo_valor_medio_plano['valor_total_contrato'], 'moeda')."</td>";
							echo "<td style='vertical-align: middle'>R$".converteMoeda($valor_medio_contratado, 'moeda')."</td>";
						echo "</tr>";
						
						
					}
						echo '		
						</tbody>
					</table>';

					echo "
					<br><br><br>";

				echo "
				</div>";
			}

			if($dados_valor_medio_sistema_gestao){
				
				asort($dados_valor_medio_sistema_gestao);		

				echo "
				<div class='col-md-12 style='padding: 0'>";
				
				echo "<legend style=\"text-align:center;\">Contagem por Sistema de Gestão</legend>";

					echo '
					<table class="table table-hover dataTableAgrupado" style="margin-bottom:0;">
						<thead>
							<tr>
								<th>Sistema de Gestão</th>
								<th>Qtd Efetuada (Via Telefone)</th>
								<th>Qtd Efetuada (Via Texto)</th>
								<th>Qtd Efetuada Total</th>
								<th>Valor da Cobrança</th>
								<th>Valor Médio (Efetuadas)</th>

								<th>Qtd Contratada (Via Telefone)</th>
								<th>Qtd Contratada (Via Texto)</th>
								<th>Qtd Contratada Total</th>
								<th>Valor do Contrato</th>
								<th>Valor Médio (Contratadas)</th>
							</tr>
						</thead>
						<tbody>
					';           

					foreach ($dados_valor_medio_sistema_gestao as $nome_sistema_gestao => $conteudo_valor_medio_sistema_gestao) {
						
						$qtd_total = $conteudo_valor_medio_sistema_gestao['qtd_texto'] + $conteudo_valor_medio_sistema_gestao['qtd_voz'];
						$valor_medio = $conteudo_valor_medio_sistema_gestao['valor_cobranca']/($qtd_total == 0 ? 1 : $qtd_total);

						$qtd_contratada_total = $conteudo_valor_medio_sistema_gestao['qtd_contratada'] + $conteudo_valor_medio_sistema_gestao['qtd_contratada_texto'];
						$valor_medio_contratado = $conteudo_valor_medio_sistema_gestao['valor_total_contrato']/($qtd_contratada_total == 0 ? 1 : $qtd_contratada_total);
					
						echo "<tr>";
							echo "<td style='vertical-align: middle'>".$nome_sistema_gestao."</td>";
							echo "<td style='vertical-align: middle'>".$conteudo_valor_medio_sistema_gestao['qtd_voz']."</td>";
							echo "<td style='vertical-align: middle'>".$conteudo_valor_medio_sistema_gestao['qtd_texto']."</td>";
							echo "<td style='vertical-align: middle'>".$qtd_total."</td>";
							echo "<td style='vertical-align: middle'>R$".converteMoeda($conteudo_valor_medio_sistema_gestao['valor_cobranca'], 'moeda')."</td>";
							echo "<td style='vertical-align: middle'>R$".converteMoeda($valor_medio, 'moeda')."</td>";

							echo "<td style='vertical-align: middle'>".$conteudo_valor_medio_sistema_gestao['qtd_contratada']."</td>";
							echo "<td style='vertical-align: middle'>".$conteudo_valor_medio_sistema_gestao['qtd_contratada_texto']."</td>";
							echo "<td style='vertical-align: middle'>".$qtd_contratada_total."</td>";
							echo "<td style='vertical-align: middle'>R$".converteMoeda($conteudo_valor_medio_sistema_gestao['valor_total_contrato'], 'moeda')."</td>";
							echo "<td style='vertical-align: middle'>R$".converteMoeda($valor_medio_contratado, 'moeda')."</td>";
						echo "</tr>";
						
						
					}
						echo '		
						</tbody>
					</table>';

					echo "
					<br><br><br>";

				echo "
				</div>";
			}

			echo "
				<script>
					$(document).ready(function(){
						$('.dataTableAgrupado').DataTable({
							\"language\": {
								\"url\": \"//cdn.datatables.net/plug-ins/1.10.19/i18n/Portuguese-Brasil.json\"
							},
							columnDefs: [
								{ type: 'time-uni', targets: 1 },
							],
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
    echo "</div>";
}

function relatorio_rentabilidade_atendente($referencia, $dados_meses){

	$fila = "'callATENDIMENTOvip','callATENDIMENTOalta','callATENDIMENTOnormal','callATENDIMENTObaixa','callATENDIMENTOnotificacaoparada', 'callATENDIMENTOvipEXT','callATENDIMENTOaltaEXT','callATENDIMENTOnormalEXT','callATENDIMENTObaixaEXT','callATENDIMENTOnotificacaoparadaEXT','callATENDIMENTOvipEXP','callATENDIMENTOaltaEXP','callATENDIMENTOnormalEXP','callATENDIMENTObaixaEXP','callATENDIMENTOnotificacaoparadaEXP'";
          
    $data_de = $referencia;
    $data = new DateTime($referencia);
    $data->modify('last day of this month');
    $data_ate = $data->format('Y-m-d');
    $array_atendentes_faturamento = array();
    $array_faturamento = array();
    $array_contratos = array();
	$mes_ano = explode("-", $referencia);
	$mes = $mes_ano[1];
	$ano = $mes_ano[0];

	$periodo_amostra = "<span style=\"font-size: 14px;\"><strong>Referência: </strong>".$dados_meses[$mes]." de ".$ano."</span>";
	$gerado = "<span style=\"font-size: 14px;\"><strong>Gerado em: </strong> ".converteDataHora(getDataHora())."</span>";	

	echo "<div class=\"col-md-12\" style=\"padding: 0\">";
	echo "<legend style=\"text-align:center;\"><strong>Relatório de Rentabilidade por Atendente</strong><br>$gerado</legend>";
    echo "<legend style=\"text-align:center;\">$periodo_amostra</legend>";
        

    $dados_faturamento = DBRead('', 'tb_faturamento a',"INNER JOIN tb_faturamento_contrato b ON a.id_faturamento = b.id_faturamento WHERE a.data_referencia = '".$referencia."' AND a.status = '1' ", "a.*, b.*");
    if($dados_faturamento){
        foreach ($dados_faturamento as $conteudo_faturamento) {
			if($conteudo_faturamento['valor_diferente_texto'] == 1){
				$array_faturamento[$conteudo_faturamento['id_contrato_plano_pessoa']."_voz"] = $conteudo_faturamento['valor_unitario_contrato'];
				$array_faturamento[$conteudo_faturamento['id_contrato_plano_pessoa']."_chat"] = $conteudo_faturamento['valor_unitario_texto'];
			}else{
				$array_faturamento[$conteudo_faturamento['id_contrato_plano_pessoa']."_voz"] = $conteudo_faturamento['valor_unitario_contrato'];
				$array_faturamento[$conteudo_faturamento['id_contrato_plano_pessoa']."_chat"] = $conteudo_faturamento['valor_unitario_contrato'];
			}
            
            $array_contratos[]=$conteudo_faturamento['id_contrato_plano_pessoa'];
        }
    }

    $filtro_contratos = " AND a.id_contrato_plano_pessoa IN ('".join("','", $array_contratos)."')";
    $dados_atendimentos = DBRead('','tb_atendimento a',"WHERE a.gravado = '1' AND a.falha != 2 AND a.data_inicio BETWEEN '".$data_de." 00:00:00' AND '".$data_ate." 23:59:59' $filtro_contratos");

    if($dados_atendimentos){
        foreach($dados_atendimentos as $conteudo_atendimento){
			if(!$array_atendentes_faturamento[$conteudo_atendimento['id_usuario']]['qtd_chat']){
				$array_atendentes_faturamento[$conteudo_atendimento['id_usuario']]['qtd_chat'] = 0;
			}
			if(!$array_atendentes_faturamento[$conteudo_atendimento['id_usuario']]['rentabilidade_chat']){
				$array_atendentes_faturamento[$conteudo_atendimento['id_usuario']]['rentabilidade_chat'] = 0;
			}
			if($conteudo_atendimento['via_texto'] == 1){
				$array_atendentes_faturamento[$conteudo_atendimento['id_usuario']]['rentabilidade_chat'] += $array_faturamento[$conteudo_atendimento['id_contrato_plano_pessoa']."_chat"];
				$array_atendentes_faturamento[$conteudo_atendimento['id_usuario']]['qtd_chat'] += 1;   
				$array_atendentes_faturamento[$conteudo_atendimento['id_usuario']]['rentabilidade_total'] += $array_faturamento[$conteudo_atendimento['id_contrato_plano_pessoa']."_chat"];
     
			}else{
				$array_atendentes_faturamento[$conteudo_atendimento['id_usuario']]['rentabilidade_voz'] += $array_faturamento[$conteudo_atendimento['id_contrato_plano_pessoa']."_voz"];
            	$array_atendentes_faturamento[$conteudo_atendimento['id_usuario']]['qtd_voz'] += 1;   
				$array_atendentes_faturamento[$conteudo_atendimento['id_usuario']]['rentabilidade_total'] += $array_faturamento[$conteudo_atendimento['id_contrato_plano_pessoa']."_voz"];
     
			}
			$array_atendentes_faturamento[$conteudo_atendimento['id_usuario']]['qtd_total'] += 1;
			
			
        }

        echo '
            <table class="table table-hover dataTable" style="margin-bottom:0;">
                <thead>
                    <tr>
                        <th>Atendente</th>
                        <th>Atendimentos Voz</th>
                        <th>Rentabilidade Total Voz</th>			
                        <th>Rentabilidade Média Voz</th>	
                        <th>Atendimentos Chat</th>
                        <th>Rentabilidade Total Chat</th>			
                        <th>Rentabilidade Média Chat</th>	
                        <th>Atendimentos Total</th>
                        <th>Rentabilidade Total</th>			
                        <th>Rentabilidade Média Total</th>		
                        <th>TMA</th>		
                    </tr>
                </thead>
                <tbody>
        ';

        foreach($array_atendentes_faturamento as $id_usuario => $conteudo_atendente_faturamento){
            $dados_atendente = DBRead('','tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE id_usuario = '$id_usuario'");

            $qtd_entradas = 0;
            $qtd_notas = 0;
            $soma_ta = 0;
            $soma_notas = 0;

            $filtro_entradas_usuario = " AND b.time >= '".$data_de." 00:00:00' AND b.time <= '".$data_ate." 23:59:59' AND b.agent = 'AGENT/".$dados_atendente[0]['id_asterisk']."' AND (c.data2 >= 30 OR c.data4 >= 30)";

            $dados_entradas = DBRead('snep','queue_log a',"INNER JOIN queue_log b ON (b.callid = a.callid AND b.event = 'CONNECT') INNER JOIN queue_log c ON (c.callid = a.callid AND (c.event = 'COMPLETEAGENT' OR c.event = 'COMPLETECALLER' OR c.event = 'BLINDTRANSFER' OR c.event = 'ATTENDEDTRANSFER')) LEFT JOIN pesquisa d ON  a.callid = d.UNIQUEID WHERE a.event = 'ENTERQUEUE' $filtro_entradas_usuario GROUP BY b.id ORDER BY b.id", "a.queuename AS 'enterqueue_queuename', c.event AS 'finalizacao_event', c.data1 AS 'finalizacao_data1', c.data2 AS 'finalizacao_data2', c.data4 AS 'finalizacao_data4', d.nota AS 'nota'");

            if($dados_entradas){
				foreach ($dados_entradas as $conteudo_entradas) {
					if(preg_match("/'".$conteudo_entradas['enterqueue_queuename']."'/i", $fila) || !$fila){

						$info_chamada = array(
							'tempo_atendimento' => '',
							'nota' => ''
						);
						$info_chamada['nota'] = $conteudo_entradas['nota'];
						if($conteudo_entradas['finalizacao_event']=='COMPLETEAGENT' || $conteudo_entradas['finalizacao_event']=='COMPLETECALLER'){
							$info_chamada['tempo_atendimento'] = $conteudo_entradas['finalizacao_data2'];	
						}elseif(($conteudo_entradas['finalizacao_event']=='BLINDTRANSFER' || $conteudo_entradas['finalizacao_event']=='ATTENDEDTRANSFER') && $conteudo_entradas['finalizacao_data1'] != 'LINK'){				
							$info_chamada['tempo_atendimento'] = $conteudo_entradas['finalizacao_data4'];
						}				
						$qtd_entradas++;
						if($info_chamada['nota']){
							$soma_notas += $info_chamada['nota'];
							$qtd_notas++;
						}						
						$soma_ta += $info_chamada['tempo_atendimento'];	
					}
				}
			}

            echo '
                    <tr>
                        <td>'.$dados_atendente[0]['nome'].'</td>
                        
						<td>'.$conteudo_atendente_faturamento['qtd_voz'].'</td>
						<td data-order="'.$conteudo_atendente_faturamento['rentabilidade_voz'].'">R$'.converteMoeda($conteudo_atendente_faturamento['rentabilidade_voz'], 'moeda').'</td>
                        <td data-order="'.$conteudo_atendente_faturamento['rentabilidade_voz']/$conteudo_atendente_faturamento['qtd_voz'].'">R$'.converteMoeda($conteudo_atendente_faturamento['rentabilidade_voz']/$conteudo_atendente_faturamento['qtd_voz'], 'moeda').'</td>



                        <td>'.$conteudo_atendente_faturamento['qtd_chat'].'</td>';

						if($conteudo_atendente_faturamento['rentabilidade_chat'] == 0){
							echo '
							<td data-order="'.$conteudo_atendente_faturamento['rentabilidade_chat'].'">R$'.converteMoeda($conteudo_atendente_faturamento['rentabilidade_chat'], 'moeda').'</td>
							<td data-order="'.$conteudo_atendente_faturamento['rentabilidade_chat']/$conteudo_atendente_faturamento['qtd_chat'].'">R$'.converteMoeda($conteudo_atendente_faturamento['rentabilidade_chat'], 'moeda').'</td>';
						}else{
							echo '
							<td data-order="'.$conteudo_atendente_faturamento['rentabilidade_chat'].'">R$'.converteMoeda($conteudo_atendente_faturamento['rentabilidade_chat'], 'moeda').'</td>
							<td data-order="'.$conteudo_atendente_faturamento['rentabilidade_chat']/$conteudo_atendente_faturamento['qtd_chat'].'">R$'.converteMoeda($conteudo_atendente_faturamento['rentabilidade_chat']/$conteudo_atendente_faturamento['qtd_chat'], 'moeda').'</td>';
						}
						


						echo '
						<td>'.$conteudo_atendente_faturamento['qtd_total'].'</td>
                        <td data-order="'.$conteudo_atendente_faturamento['rentabilidade_total'].'">R$'.converteMoeda($conteudo_atendente_faturamento['rentabilidade_total'], 'moeda').'</td>
                        <td data-order="'.$conteudo_atendente_faturamento['rentabilidade_total']/$conteudo_atendente_faturamento['qtd_total'].'">R$'.converteMoeda($conteudo_atendente_faturamento['rentabilidade_total']/$conteudo_atendente_faturamento['qtd_total'], 'moeda').'</td>
                        <td>'.gmdate("H:i:s", $soma_ta/($qtd_entradas == 0 ? 1 : $qtd_entradas)).'</td>
                    </tr>
            ';
        }
            
        echo"
                </tbody> 
            </table>
        ";

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
								extend: 'excelHtml5', footer: true,
								text: '<i class=\"fa fa-file-excel-o\" aria-hidden=\"true\"></i> Exportar',
								filename: 'relatorio_indicadores_financeiro_callcenter',
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

function relatorio_rateio_conta_pagar($referencia_rateio_conta_pagar, $dados_meses){

    $mes_ano = explode("-", $referencia_rateio_conta_pagar);
	$mes = $mes_ano[1];
    $ano = $mes_ano[0];
    
    $data_de = new DateTime($referencia_rateio_conta_pagar);
	$data_de->modify('first day of this month');
	$data_de = $data_de->format('Y-m-d');

	$data_ate = new DateTime($referencia_rateio_conta_pagar);
	$data_ate->modify('last day of this month');
	$data_ate = $data_ate->format('Y-m-d');

    $data_hoje = converteDataHora(getDataHora());

    $periodo_amostra = "<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> ".$dados_meses[$mes]." de ".$ano."</span>";
	$gerado = "<span style=\"font-size: 14px;\"><strong>Gerado em: </strong> $data_hoje</span>";

	echo "<div class=\"col-md-12\" style=\"padding: 0\">";
	echo "<legend style=\"text-align:center;\"><strong>Relatório - Rateio de Contas a Pagar por Centro de Custos</strong><br>$gerado</legend>";
    echo "<legend style=\"text-align:center;\">$periodo_amostra</legend>";

    $id_usuario = $_SESSION['id_usuario'];
    $dados_usuario = DBRead('','tb_usuario',"WHERE id_usuario = '$id_usuario'");

    $nome_centro_custos = array();
    $dados_nome_centro_custos = DBRead('','tb_centro_custos');
    if($dados_nome_centro_custos){
        foreach($dados_nome_centro_custos as $conteudo_centro_custo){
            $nome_centro_custos[$conteudo_centro_custo['id_centro_custos']] = $conteudo_centro_custo['nome'];
        }
    }

    if($dados_usuario[0]['id_perfil_sistema'] == '2' || $dados_usuario[0]['id_perfil_sistema'] == '20' || $dados_usuario[0]['id_perfil_sistema'] == '10' || $dados_usuario[0]['id_perfil_sistema'] == '24' || $dados_usuario[0]['id_perfil_sistema'] == '18'){  

        $dados_rateio = DBRead('','tb_conta_pagar_centro_custos a',"INNER JOIN tb_centro_custos b ON a.id_centro_custos = b.id_centro_custos INNER JOIN tb_conta_pagar c ON a.id_conta_pagar = c.id_conta_pagar INNER JOIN tb_natureza_financeira d ON c.id_natureza_financeira = d.id_natureza_financeira INNER JOIN tb_natureza_financeira_agrupador e ON d.id_natureza_financeira_agrupador = e.id_natureza_financeira_agrupador INNER JOIN tb_pessoa f ON c.id_pessoa = f.id_pessoa WHERE c.situacao != 'baixada' AND c.data_vencimento >= '$data_de' AND c.data_vencimento <= '$data_ate' ORDER BY c.id_conta_pagar ASC", "a.valor AS 'valor_rateio', a.porcentagem AS 'porcentagem_rateio',  b.id_centro_custos, c.id_conta_pagar, c.valor AS 'valor_conta_pagar', d.nome AS 'nome_natureza_financeira', e.nome AS 'nome_natureza_financeira_agrupador', f.nome AS 'nome_pessoa'");

    }else{
        $dados_rateio = DBRead('','tb_conta_pagar_centro_custos a',"INNER JOIN tb_centro_custos b ON a.id_centro_custos = b.id_centro_custos INNER JOIN tb_conta_pagar c ON a.id_conta_pagar = c.id_conta_pagar INNER JOIN tb_natureza_financeira d ON c.id_natureza_financeira = d.id_natureza_financeira INNER JOIN tb_natureza_financeira_agrupador e ON d.id_natureza_financeira_agrupador = e.id_natureza_financeira_agrupador INNER JOIN tb_pessoa f ON c.id_pessoa = f.id_pessoa WHERE b.id_usuario_responsavel = '$id_usuario' AND c.situacao != 'baixada' AND c.data_vencimento >= '$data_de' AND c.data_vencimento <= '$data_ate' ORDER BY c.id_conta_pagar ASC", "a.valor AS 'valor_rateio', a.porcentagem AS 'porcentagem_rateio', b.id_centro_custos, c.id_conta_pagar, c.valor AS 'valor_conta_pagar', d.nome AS 'nome_natureza_financeira', e.nome AS 'nome_natureza_financeira_agrupador', f.nome AS 'nome_pessoa'");
    }

    $centros_custos = array();   
    $totais_natureza_financeira = array();
    $totais_natureza_financeira_agrupador = array();
    $contas_centro_custos = array();
    $totais_gerais = array();

    if($dados_rateio){
        foreach($dados_rateio as $conteudo_rateio){
            if (!in_array($conteudo_rateio['id_centro_custos'], $centros_custos)) { 
                $centros_custos[] = $conteudo_rateio['id_centro_custos'];
            }           
            $totais_natureza_financeira[$conteudo_rateio['nome_natureza_financeira']]['total'] += $conteudo_rateio['valor_rateio'];
            $totais_natureza_financeira[$conteudo_rateio['nome_natureza_financeira']][$conteudo_rateio['id_centro_custos']] += $conteudo_rateio['valor_rateio'];

            $totais_natureza_financeira_agrupador[$conteudo_rateio['nome_natureza_financeira_agrupador']]['total'] += $conteudo_rateio['valor_rateio'];
            $totais_natureza_financeira_agrupador[$conteudo_rateio['nome_natureza_financeira_agrupador']][$conteudo_rateio['id_centro_custos']] += $conteudo_rateio['valor_rateio'];

            $contas_centro_custos[$conteudo_rateio['id_centro_custos']][$conteudo_rateio['id_conta_pagar']]['valor_rateio'] = $conteudo_rateio['valor_rateio'];
            $contas_centro_custos[$conteudo_rateio['id_centro_custos']][$conteudo_rateio['id_conta_pagar']]['porcentagem_rateio'] = $conteudo_rateio['porcentagem_rateio'];
            $contas_centro_custos[$conteudo_rateio['id_centro_custos']][$conteudo_rateio['id_conta_pagar']]['valor_conta_pagar'] = $conteudo_rateio['valor_conta_pagar'];
            $contas_centro_custos[$conteudo_rateio['id_centro_custos']][$conteudo_rateio['id_conta_pagar']]['valor_conta_pagar'] = $conteudo_rateio['valor_conta_pagar'];
            $contas_centro_custos[$conteudo_rateio['id_centro_custos']][$conteudo_rateio['id_conta_pagar']]['nome_natureza_financeira'] = $conteudo_rateio['nome_natureza_financeira'];
            $contas_centro_custos[$conteudo_rateio['id_centro_custos']][$conteudo_rateio['id_conta_pagar']]['nome_natureza_financeira_agrupador'] = $conteudo_rateio['nome_natureza_financeira_agrupador'];
            $contas_centro_custos[$conteudo_rateio['id_centro_custos']][$conteudo_rateio['id_conta_pagar']]['nome_pessoa'] = $conteudo_rateio['nome_pessoa'];
        }

        echo '    
        <legend class="text-center">Totais por Agrupador de Natureza Financeira</legend>
        <table class="table table-hover dataTable" style="margin-bottom:0;">
            <thead>                    
                <tr>
                    <th>Agrupadores de Naturezas Financeiras</th>
                    <th>Total</th>                  
        ';
        foreach ($centros_custos as $conteudo_centro_custo) {
            echo '<th>'.$nome_centro_custos[$conteudo_centro_custo].'</th>';
        }
        echo '
                </tr>
            </thead>
            <tbody>
        ';
        foreach ($totais_natureza_financeira_agrupador as $natureza_financeira_agrupador => $conteudo_totais) {
            echo '<tr>';
                echo '<td>'.$natureza_financeira_agrupador.'</td>';
                echo '<td data-order="'.$conteudo_totais['total'].'">R$ '.converteMoeda($conteudo_totais['total']).'</td>';
                foreach ($centros_custos as $conteudo_centro_custo) {
                    if($conteudo_totais[$conteudo_centro_custo]){
                        $valor = $conteudo_totais[$conteudo_centro_custo];
                    }else{
                        $valor = 0.00;
                    }
                    echo '<td data-order="'.$valor.'">R$ '.converteMoeda($valor).'</td>';
                    $totais_gerais['total'] += $valor;
                    $totais_gerais[$conteudo_centro_custo] += $valor;
                }
            echo '</tr>';
        }
        echo '           
            </tbody>
            <tfoot>
                <tr>
                    <th>Totais:</th>
                    <th>R$ '.converteMoeda($totais_gerais['total']).'</th>
        ';
            foreach ($centros_custos as $conteudo_centro_custo) {
                echo '<th>R$ '.converteMoeda($totais_gerais[$conteudo_centro_custo]).'</th>';
            }
        echo '  
                </tr>         
            </tfoot>
        </table>
        ';
       
        unset($totais_gerais);        
        echo '   
        <hr>
        <legend class="text-center">Totais por Natureza Financeira</legend>
        <table class="table table-hover dataTable" style="margin-bottom:0;">
            <thead>                    
                <tr>
                    <th>Naturezas Financeiras</th>
                    <th>Total</th>                  
        ';
        foreach ($centros_custos as $conteudo_centro_custo) {
            echo '<th>'.$nome_centro_custos[$conteudo_centro_custo].'</th>';
        }
        echo '
                </tr>
            </thead>
            <tbody>
        ';
        foreach ($totais_natureza_financeira as $natureza_financeira => $conteudo_totais) {
            echo '<tr>';
                echo '<td>'.$natureza_financeira.'</td>';
                echo '<td data-order="'.$conteudo_totais['total'].'">R$ '.converteMoeda($conteudo_totais['total']).'</td>';
                foreach ($centros_custos as $conteudo_centro_custo) {
                    if($conteudo_totais[$conteudo_centro_custo]){
                        $valor = $conteudo_totais[$conteudo_centro_custo];
                    }else{
                        $valor = 0.00;
                    }
                    echo '<td data-order="'.$valor.'">R$ '.converteMoeda($valor).'</td>';
                    $totais_gerais['total'] += $valor;
                    $totais_gerais[$conteudo_centro_custo] += $valor;
                }
            echo '</tr>';
        }
        echo '           
            </tbody>
            <tfoot>
                <tr>
                    <th>Totais:</th>
                    <th>R$ '.converteMoeda($totais_gerais['total']).'</th>
        ';
            foreach ($centros_custos as $conteudo_centro_custo) {
                echo '<th>R$ '.converteMoeda($totais_gerais[$conteudo_centro_custo]).'</th>';
            }
        echo '  
                </tr>         
            </tfoot>
        </table>
        ';

        echo '   
        <hr>
        <legend class="text-center">Detalhes por Cento de Custos</legend>               
        ';

        foreach($contas_centro_custos as $id_centro_custos => $conteudo_contas_centro_custos){
            echo '  
            <table class="table table-hover dataTable" style="margin-bottom:0;">
                <thead>
                    <tr>
                        <th colspan="7" class="text-center">'.$nome_centro_custos[$id_centro_custos].'</th>
                    </tr>                 
                    <tr>
                        <th>#</th>
                        <th>Pessoa / Fornecedor</th>
                        <th>Agrupador</th>                  
                        <th>Natureza Financeira</th>                  
                        <th>Valor da Conta a Pagar</th>                  
                        <th>Valor do Rateio</th>                  
                        <th>Porcentagem do Rateio</th>                  
                    </tr>
                </thead>
                <tbody>
            ';
            foreach($conteudo_contas_centro_custos as $id_conta => $conteudo_conta){
                echo '<tr>';
                    echo '<td>'.$id_conta.'</td>';
                    echo '<td>'.$conteudo_conta['nome_pessoa'].'</td>';
                    echo '<td>'.$conteudo_conta['nome_natureza_financeira_agrupador'].'</td>';
                    echo '<td>'.$conteudo_conta['nome_natureza_financeira'].'</td>';
                    echo '<td data-order="'.$conteudo_conta['valor_conta_pagar'].'">R$ '.converteMoeda($conteudo_conta['valor_conta_pagar']).'</td>';
                    echo '<td data-order="'.$conteudo_conta['valor_rateio'].'">R$ '.converteMoeda($conteudo_conta['valor_rateio']).'</td>';
                    echo '<td>'.$conteudo_conta['porcentagem_rateio'].'%</td>';
                echo '</tr>';
            }
            echo '           
                </tbody>                
            </table>
            ';
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
    echo '</div>';
}

function relatorio_rateio_centro_custos_mensal($referencia_rateio_mensal, $dados_meses){
	
	$mes_ano = explode("-", $referencia_rateio_mensal);
	$mes = $mes_ano[1];
    $ano = $mes_ano[0];
    
    $data_de = new DateTime($referencia_rateio_mensal);
	$data_de->modify('first day of this month');
	$data_de = $data_de->format('Y-m-d');

	$data_ate = new DateTime($referencia_rateio_mensal);
	$data_ate->modify('last day of this month');
	$data_ate = $data_ate->format('Y-m-d');

	$data_hoje = converteDataHora(getDataHora());

    $periodo_amostra = "<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> ".$dados_meses[$mes]." de ".$ano."</span>";
	$gerado = "<span style=\"font-size: 14px;\"><strong>Gerado em: </strong> $data_hoje</span>";

	$filtro_data = " AND data_referencia = '".$ano."-".$mes."-01' ";

	echo "<div class=\"col-md-6 col-md-offset-3\" style=\"padding: 0\">";
	echo "<legend style=\"text-align:center;\"><strong>Relatório - Rateio de Centro de Custos</strong><br>$gerado</legend>";
	echo "<legend style=\"text-align:center;\">$periodo_amostra</legend>";

    
    $dados_rateio = DBRead('','tb_conta_pagar_centro_custos a',"INNER JOIN tb_centro_custos b ON a.id_centro_custos = b.id_centro_custos INNER JOIN tb_conta_pagar c ON a.id_conta_pagar = c.id_conta_pagar INNER JOIN tb_natureza_financeira d ON c.id_natureza_financeira = d.id_natureza_financeira INNER JOIN tb_natureza_financeira_agrupador e ON d.id_natureza_financeira_agrupador = e.id_natureza_financeira_agrupador INNER JOIN tb_pessoa f ON c.id_pessoa = f.id_pessoa WHERE c.situacao != 'baixada' AND c.data_vencimento >= '$data_de' AND c.data_vencimento <= '$data_ate' ORDER BY c.id_conta_pagar ASC", "a.valor AS 'valor_rateio', a.porcentagem AS 'porcentagem_rateio',  b.id_centro_custos, c.id_conta_pagar, c.valor AS 'valor_conta_pagar', d.nome AS 'nome_natureza_financeira', e.nome AS 'nome_natureza_financeira_agrupador', f.nome AS 'nome_pessoa'");
    $contas_centro_custos = array();
    
    if($dados_rateio){
        foreach($dados_rateio as $conteudo_rateio){
            $contas_centro_custos[$conteudo_rateio['id_centro_custos']] += $conteudo_rateio['valor_rateio'];
        }
    }
    
    $dados_nome_centro_custos = DBRead('','tb_centro_custos');
    $nome_centro_custos = array();

    if($dados_nome_centro_custos){
        foreach($dados_nome_centro_custos as $conteudo_centro_custo){
            $nome_centro_custos[$conteudo_centro_custo['id_centro_custos']] = $conteudo_centro_custo['nome'];
        }
    }

    $dados_centro_custos_rateio_mensal = DBRead('','tb_centro_custos_rateio a',"INNER JOIN tb_centro_custos_rateio_centro_custos b ON a.id_centro_custos_rateio = b.id_centro_custos_rateio INNER JOIN tb_centro_custos c ON b.id_centro_custos = c.id_centro_custos INNER JOIN tb_centro_custos d ON a.id_centro_custos_principal = d.id_centro_custos WHERE a.id_centro_custos_rateio ".$filtro_data." ORDER BY d.nome ASC, c.nome ASC", "a.*, b.*, c.nome, d.nome AS nome_centro_custos_principal");
    $dados_centro_custos_rateio = array();

    if($dados_centro_custos_rateio_mensal){
        foreach($dados_centro_custos_rateio_mensal as $conteudo_centro_custos_rateio_mensal){
            $dados_centro_custos_rateio[$conteudo_centro_custos_rateio_mensal['id_centro_custos_principal']][$conteudo_centro_custos_rateio_mensal['id_centro_custos']] = $conteudo_centro_custos_rateio_mensal['porcentagem'];

            $dados_centro_custos_rateio_teste[$conteudo_centro_custos_rateio_mensal['id_centro_custos_principal'].' - '.$conteudo_centro_custos_rateio_mensal['nome_centro_custos_principal']][$conteudo_centro_custos_rateio_mensal['id_centro_custos'].' - '.$conteudo_centro_custos_rateio_mensal['nome']] = $conteudo_centro_custos_rateio_mensal['porcentagem'];
        }   

        if($dados_centro_custos_rateio){
            foreach ($dados_centro_custos_rateio as $id_centro_custos_principal => $conteudo_centro_custo_rateio) {
               			
				echo '<legend class="text-center">'.$nome_centro_custos[$id_centro_custos_principal].'</legend>';
				
				echo '
				<table class="table table-striped dataTable" style="margin-bottom:0;">
					<thead>
						<tr>
							<th>Centro de Custos</th>						
							<th>Custo Total</th>						
							<th>Porcentagem Utilizada</th>					
							<th>Custo Utilizado</th>					
						</tr>
					</thead>
					<tbody>
                ';
                echo '
                    <tr>
                        <td>'.$nome_centro_custos[$id_centro_custos_principal].'</td>                        
                        <td>R$ '.converteMoeda($contas_centro_custos[$id_centro_custos_principal]).'</td>                        
                        <td>100%</td>                        
                        <td>R$ '.converteMoeda($contas_centro_custos[$id_centro_custos_principal]).'</td>                        
                    </tr>
                ';

                $custo_utilizado_total = $contas_centro_custos[$id_centro_custos_principal];

                if($conteudo_centro_custo_rateio){
                    foreach ($conteudo_centro_custo_rateio as $id_centro_custos => $porcentagem) {
                        echo '
                            <tr>
                                <td>'.$nome_centro_custos[$id_centro_custos].'</td>                        
                                <td>R$ '.converteMoeda($contas_centro_custos[$id_centro_custos]).'</td>                        
                                <td>'.$porcentagem.'%</td>                        
                                <td>R$ '.converteMoeda($contas_centro_custos[$id_centro_custos]*$porcentagem/100).'</td>                        
                            </tr>
                        ';
                        $custo_utilizado_total += ($contas_centro_custos[$id_centro_custos]*$porcentagem/100);
                    }
                }
                
                

                echo '
                    </tbody>    
                    <tfoot>
                        <tr>
                            <th>Totais</th>                           
                            <th></th>                           
                            <th></th>                           
                            <th>R$ '.converteMoeda($custo_utilizado_total).'</th>                           
                        </tr>
                    </tfoot>  
                </table>
                ';
                echo '<hr>';
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
    echo '</div>';
}
 
?>
