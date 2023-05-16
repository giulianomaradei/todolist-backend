<?php
require_once(__DIR__."/../class/System.php");

$servico = (!empty($_POST['servico'])) ? $_POST['servico'] : '';
$tipo_relatorio = (!empty($_POST['tipo_relatorio'])) ? $_POST['tipo_relatorio'] : '1';
$gerar = (!empty($_POST['gerar'])) ? 1 : 0;
$data_inicial = new DateTime(getDataHora('data'));
$data_inicial->modify('first day of last month');
$referencia = (!empty($_POST['referencia'])) ? $_POST['referencia'] : '';
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

if($tipo_relatorio == 1){
	$display_row_servico = '';
	$display_row_data_referencia = '';
}else if($tipo_relatorio == 2){
	$display_row_servico = '';
	$display_row_data_referencia = '';
}else if($tipo_relatorio == 3){
	$display_row_servico = 'style="display:none;"';
	$display_row_data_referencia = '';
}else if($tipo_relatorio == 4){
	$display_row_servico = 'style="display:none;"';
	$display_row_data_referencia = '';
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
	                    <h3 class="panel-title text-left pull-left" style="margin-top: 2px;">Relatório - Treasy:</h3>
	                    <div class="panel-title text-right pull-right"><button data-toggle="collapse" data-target="#accordionRelatorio" class="btn btn-xs btn-info" type="button" title="Visualizar filtros"><i id="i_collapse" class="fa fa-<?=$collapse_icon?>"></i></button></div>
	                </div>
	                <div id="accordionRelatorio" class="panel-collapse collapse <?=$collapse?>">
	                	<div class="panel-body">
	                	<div class="row">
								<div class="col-md-12">
									<div class="form-group">
										<label>Tipo de Relatório:</label> 
										<select name="tipo_relatorio" id="tipo_relatorio" class="form-control input-sm">
											<option value="3" <?php if($tipo_relatorio == '3'){echo 'selected';}?>>Custos e Despesas</option>
											<!-- <option value="4" <?php if($tipo_relatorio == '4'){echo 'selected';}?>>Custos e Despesas - Agrupado por Mês</option> -->
                                            <option value="1" <?php if($tipo_relatorio == '1'){echo 'selected';}?>>Receita Operacional</option>
                                            <option value="2" <?php if($tipo_relatorio == '2'){echo 'selected';}?>>Receita Operacional - Agrupado por Mês</option>
										</select>
									</div>
								</div>
							</div>	  	
						
							<div class="row" id="row_servico" <?=$display_row_servico?>>
                				<div class="col-md-12">
                					<div class="form-group">
								        <label for="">Serviço:</label>
								        <select name="servico" id="servico" class="form-control input-sm">
                                            <option value="" <?php if($servico == ''){ echo 'selected';}?>>Todos</option>
                                            <option value="adesao" <?php if($servico == 'adesao'){ echo 'selected';}?>>Call Center - Adesões</option>
											<option value="ativo" <?php if($servico == 'ativo'){ echo 'selected';}?>>Call Center - Ativo</option>
								        	<option value="monitoramento" <?php if($servico == 'monitoramento'){ echo 'selected';}?>>Call Center - Monitoramento</option>
								        	<option value="suporte" <?php if($servico == 'suporte'){ echo 'selected';}?>>Call Center - Suporte</option>
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
                                            echo "<option value='' " . $sel_referencia[''] . ">Todas</option>";
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
			if($tipo_relatorio == 1){
				relatorio_receita_operacional($referencia, $dados_meses, $servico);
			}else if($tipo_relatorio == 2){
				relatorio_receita_operacional_agrupado($referencia, $dados_meses, $servico);
			}else if($tipo_relatorio == 3){
				relatorio_custos_despesas($referencia, $dados_meses);
			}else if($tipo_relatorio == 4){
				relatorio_custos_despesas_agrupado($referencia, $dados_meses);
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
    
    $(document).ready(function(){
	    $('#aguarde').hide();
	    $('#resultado').show();
	    $("#gerar").prop("disabled", false);
	});  

	$(function () {
	  $('[data-toggle="tooltip"]').tooltip()
	}) 

	$('#tipo_relatorio').on('change',function(){
		if($(this).val() == 1){
			$('#row_servico').show();
			$('#row_data_referencia').show();
		}else if($(this).val() == 2){
			$('#row_servico').show();
			$('#row_data_referencia').show();
		}else if($(this).val() == 3){
			$('#row_servico').hide();
			$('#row_data_referencia').show();
		}else if($(this).val() == 4){
			$('#row_servico').hide();
			$('#row_data_referencia').show();
			
		}
	}); 

</script>
<?php 

function relatorio_custos_despesas_agrupado($referencia, $dados_meses){

    if($referencia){
        $mes_ano = explode("-", $referencia);
        $mes = $mes_ano[1];
        $ano = $mes_ano[0];
        $periodo_amostra = "<span style=\"font-size: 14px;\"><strong>Data de Referência: </strong>".$dados_meses[$mes]." de ".$ano."</span>";
        $filtro_referencia = " AND a.data_referencia = '".$referencia."' ";    
    }else{ 
        $periodo_amostra = "<span style=\"font-size: 14px;\"><strong>Data de Referência: </strong>Todas</span>";
        $filtro_referencia = '';
    }
	
	$gerado = "<span style=\"font-size: 14px;\"><strong>Gerado em: </strong> ".converteDataHora(getDataHora())."</span>";	

	echo "<div class=\"col-md-10 col-md-offset-1\" style=\"padding: 0\">";
	echo "<legend style=\"text-align:center;\"><strong>Relatório Treasy - Custos e Despesas - Agrupado por Mês</strong><br>$gerado</legend>";
	echo "<legend style=\"text-align:center;\">$periodo_amostra</legend>";

	if($referencia){
		$mes_ano = explode("-", $referencia);
		$mes = $mes_ano[1];
		$ano = $mes_ano[0];
		
		$data_de = new DateTime($referencia);
		$data_de->modify('first day of this month');
		$data_de = $data_de->format('Y-m-d');
	
		$data_ate = new DateTime($referencia);
		$data_ate->modify('last day of this month');
		$data_ate = $data_ate->format('Y-m-d');
	
		$legenda_referencia = "AND c.data_vencimento >= '$data_de' AND c.data_vencimento <= '$data_ate'";
	}else{
		$legenda_referencia = "";
	}
   
    $data_hoje = converteDataHora(getDataHora());

    $id_usuario = $_SESSION['id_usuario'];
    $dados_usuario = DBRead('','tb_usuario',"WHERE id_usuario = '$id_usuario'");

    $nome_centro_custos = array();
    $dados_nome_centro_custos = DBRead('','tb_centro_custos');
    if($dados_nome_centro_custos){
        foreach($dados_nome_centro_custos as $conteudo_centro_custo){
            $nome_centro_custos[$conteudo_centro_custo['id_centro_custos']] = $conteudo_centro_custo['nome'];
        }
    }

	$dados_rateio = DBRead('','tb_conta_pagar_centro_custos a',"INNER JOIN tb_centro_custos b ON a.id_centro_custos = b.id_centro_custos INNER JOIN tb_conta_pagar c ON a.id_conta_pagar = c.id_conta_pagar INNER JOIN tb_natureza_financeira d ON c.id_natureza_financeira = d.id_natureza_financeira INNER JOIN tb_natureza_financeira_agrupador e ON d.id_natureza_financeira_agrupador = e.id_natureza_financeira_agrupador INNER JOIN tb_pessoa f ON c.id_pessoa = f.id_pessoa WHERE c.situacao != 'baixada' ".$legenda_referencia." ORDER BY c.id_conta_pagar ASC", "a.valor AS 'valor_rateio', a.porcentagem AS 'porcentagem_rateio',  b.id_centro_custos, c.id_conta_pagar, c.valor AS 'valor_conta_pagar', d.nome AS 'nome_natureza_financeira', e.nome AS 'nome_natureza_financeira_agrupador', f.nome AS 'nome_pessoa', c.data_vencimento, c.id_caixa");

   
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
            $contas_centro_custos[$conteudo_rateio['id_centro_custos']][$conteudo_rateio['id_conta_pagar']]['data_vencimento'] = $conteudo_rateio['data_vencimento'];

			$dados_caixa = DBRead('','tb_caixa',"WHERE id_caixa = '".$conteudo_rateio['id_caixa']."'");
			$contas_centro_custos[$conteudo_rateio['id_centro_custos']][$conteudo_rateio['id_conta_pagar']]['caixa'] = $dados_caixa[0]['nome'];
        }
      
		echo '  
            <table class="table table-hover dataTable" style="margin-bottom:0;">
                <thead>
                                 
                    <tr>
                        <th>Ano</th>
                        <th>Mês</th>
                        <th>Centro de Custos</th>                  
                        <th>Conta</th>                  
                        <th>Valor</th>                  
                        <th>Valor do Rateio</th>                  
                        <th>Porcentagem do Rateio</th>                  
                    </tr>
                </thead>
                <tbody>
            ';
			$total_rateio = 0;
        foreach($contas_centro_custos as $id_centro_custos => $conteudo_contas_centro_custos){
            
            foreach($conteudo_contas_centro_custos as $id_conta => $conteudo_conta){
				$mes_ano_tabela = explode("-", $conteudo_conta['data_vencimento']);
				$mes_tabela = $mes_ano_tabela[1];
				$ano_tabela = $mes_ano_tabela[0];
                echo '<tr>';
                    echo '<td>'.$ano_tabela.'</td>';
                    echo '<td>'.$mes_tabela.'</td>';
                    echo '<td>'.$nome_centro_custos[$id_centro_custos].'</td>';
                    echo '<td>'.$conteudo_conta['caixa'].'</td>';
					echo '<td data-order="'.$conteudo_conta['valor_conta_pagar'].'">R$ '.converteMoeda($conteudo_conta['valor_conta_pagar']).'</td>';
                    echo '<td data-order="'.$conteudo_conta['valor_rateio'].'">R$ '.converteMoeda($conteudo_conta['valor_rateio']).'</td>';
                    echo '<td>'.$conteudo_conta['porcentagem_rateio'].'%</td>';
                echo '</tr>';

				$total_rateio += $conteudo_conta['valor_rateio'];
            }
            
        }
        
		echo '           
		</tbody>

		<tfoot>
                <tr>
					<th>Totais:</th>
					<th></th>
					<th></th>
					<th></th>
					<th></th>
					<th>R$ '.converteMoeda($total_rateio).'</th>
					<th></th>
                </tr>         
            </tfoot>

	</table>
	';
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

function relatorio_custos_despesas($referencia, $dados_meses){

    if($referencia){
        $mes_ano = explode("-", $referencia);
        $mes = $mes_ano[1];
        $ano = $mes_ano[0];
        $periodo_amostra = "<span style=\"font-size: 14px;\"><strong>Data de Referência: </strong>".$dados_meses[$mes]." de ".$ano."</span>";
        $filtro_referencia = " AND a.data_referencia = '".$referencia."' ";    
    }else{ 
        $periodo_amostra = "<span style=\"font-size: 14px;\"><strong>Data de Referência: </strong>Todas</span>";
        $filtro_referencia = '';
    }
	
	$gerado = "<span style=\"font-size: 14px;\"><strong>Gerado em: </strong> ".converteDataHora(getDataHora())."</span>";	

	echo "<div class=\"col-md-10 col-md-offset-1\" style=\"padding: 0\">";
	echo "<legend style=\"text-align:center;\"><strong>Relatório Treasy - Custos e Despesas</strong><br>$gerado</legend>";
	echo "<legend style=\"text-align:center;\">$periodo_amostra</legend>";

	if($referencia){
		$mes_ano = explode("-", $referencia);
		$mes = $mes_ano[1];
		$ano = $mes_ano[0];
		
		$data_de = new DateTime($referencia);
		$data_de->modify('first day of this month');
		$data_de = $data_de->format('Y-m-d');
	
		$data_ate = new DateTime($referencia);
		$data_ate->modify('last day of this month');
		$data_ate = $data_ate->format('Y-m-d');
	
		$legenda_referencia = "AND c.data_vencimento >= '$data_de' AND c.data_vencimento <= '$data_ate'";
	}else{
		$legenda_referencia = "";
	}
   
    $data_hoje = converteDataHora(getDataHora());

    $id_usuario = $_SESSION['id_usuario'];
    $dados_usuario = DBRead('','tb_usuario',"WHERE id_usuario = '$id_usuario'");

    $nome_centro_custos = array();
    $dados_nome_centro_custos = DBRead('','tb_centro_custos');
    if($dados_nome_centro_custos){
        foreach($dados_nome_centro_custos as $conteudo_centro_custo){
            $nome_centro_custos[$conteudo_centro_custo['id_centro_custos']] = $conteudo_centro_custo['nome'];
        }
    }

	$dados_rateio = DBRead('','tb_conta_pagar_centro_custos a',"INNER JOIN tb_centro_custos b ON a.id_centro_custos = b.id_centro_custos INNER JOIN tb_conta_pagar c ON a.id_conta_pagar = c.id_conta_pagar INNER JOIN tb_natureza_financeira d ON c.id_natureza_financeira = d.id_natureza_financeira INNER JOIN tb_natureza_financeira_agrupador e ON d.id_natureza_financeira_agrupador = e.id_natureza_financeira_agrupador INNER JOIN tb_pessoa f ON c.id_pessoa = f.id_pessoa WHERE c.situacao != 'baixada' ".$legenda_referencia." ORDER BY c.id_conta_pagar ASC", "a.valor AS 'valor_rateio', a.porcentagem AS 'porcentagem_rateio',  b.id_centro_custos, c.id_conta_pagar, c.valor AS 'valor_conta_pagar', d.nome AS 'nome_natureza_financeira', e.nome AS 'nome_natureza_financeira_agrupador', f.nome AS 'nome_pessoa', c.data_vencimento, c.id_caixa");

   
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
            $contas_centro_custos[$conteudo_rateio['id_centro_custos']][$conteudo_rateio['id_conta_pagar']]['data_vencimento'] = $conteudo_rateio['data_vencimento'];

			$dados_caixa = DBRead('','tb_caixa',"WHERE id_caixa = '".$conteudo_rateio['id_caixa']."'");
			$contas_centro_custos[$conteudo_rateio['id_centro_custos']][$conteudo_rateio['id_conta_pagar']]['caixa'] = $dados_caixa[0]['nome'];

        }

      
		echo '  
            <table class="table table-hover dataTable" style="margin-bottom:0;">
                <thead>
                                 
                    <tr>
                        <th>Ano</th>
                        <th>Mês</th>
                        <th>Centro de Custos</th>                  
                        <th>Conta</th>                  
                        <th>Valor Total da Conta</th>                  
                        <th>Valor do Rateio</th>                  
                        <th>Porcentagem do Rateio</th>                  
                    </tr>
                </thead>
                <tbody>
            ';
			$total_rateio = 0;
        foreach($contas_centro_custos as $id_centro_custos => $conteudo_contas_centro_custos){
            
            foreach($conteudo_contas_centro_custos as $id_conta => $conteudo_conta){
				$mes_ano_tabela = explode("-", $conteudo_conta['data_vencimento']);
				$mes_tabela = $mes_ano_tabela[1];
				$ano_tabela = $mes_ano_tabela[0];
                echo '<tr>';
                    echo '<td>'.$ano_tabela.'</td>';
                    echo '<td>'.$mes_tabela.'</td>';
                    echo '<td>'.$nome_centro_custos[$id_centro_custos].'</td>';
                    echo '<td>'.$conteudo_conta['caixa'].'</td>';
					echo '<td data-order="'.$conteudo_conta['valor_conta_pagar'].'">R$ '.converteMoeda($conteudo_conta['valor_conta_pagar']).'</td>';
                    echo '<td data-order="'.$conteudo_conta['valor_rateio'].'">R$ '.converteMoeda($conteudo_conta['valor_rateio']).'</td>';
                    echo '<td>'.$conteudo_conta['porcentagem_rateio'].'%</td>';
                echo '</tr>';

				$total_rateio += $conteudo_conta['valor_rateio'];
            }
            
        }
        
		echo '           
		</tbody>

		<tfoot>
                <tr>
					<th>Totais:</th>
					<th></th>
					<th></th>
					<th></th>
					<th></th>
					<th>R$ '.converteMoeda($total_rateio).'</th>
					<th></th>
                </tr>         
            </tfoot>

	</table>
	';
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
					filename: 'relatorio_treasy_custos_despesas',
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
    echo '</div>';
}

function relatorio_receita_operacional_agrupado($referencia, $dados_meses, $servico){

    $legenda_servico = "Todos";
    if($servico){
        if($servico == 'suporte'){
            $legenda_servico = "Call Center - Suporte";
        }else if($servico == 'ativo'){
            $legenda_servico = "Call Center - Ativo";
        }else if($servico == 'monitoramento'){
            $legenda_servico = "Call Center - Monitoramento";
        }else{
            $legenda_servico = "Call Center - Adesões";
        }
    }

    if($referencia){
        $mes_ano = explode("-", $referencia);
        $mes = $mes_ano[1];
        $ano = $mes_ano[0];
        $periodo_amostra = "<span style=\"font-size: 14px;\"><strong>Data de Referência: </strong>".$dados_meses[$mes]." de ".$ano.", <strong>Serviço: </strong>".$legenda_servico."</span>";
        $filtro_referencia = " AND a.data_referencia = '".$referencia."' ";    
    }else{ 
        $periodo_amostra = "<span style=\"font-size: 14px;\"><strong>Data de Referência: </strong>Todas, <strong>Serviço: </strong>".$legenda_servico."</span>";
        $filtro_referencia = '';
    }
	
	$gerado = "<span style=\"font-size: 14px;\"><strong>Gerado em: </strong> ".converteDataHora(getDataHora())."</span>";	

	echo "<div class=\"col-md-10 col-md-offset-1\" style=\"padding: 0\">";
	echo "<legend style=\"text-align:center;\"><strong>Relatório Treasy - Receita Operacional - Agrupado por Mês</strong><br>$gerado</legend>";
	echo "<legend style=\"text-align:center;\">$periodo_amostra</legend>";

    if($servico == 'adesao' || !$servico){
	    $dados_consulta_adesao = DBRead('', 'tb_faturamento a',"INNER JOIN tb_faturamento_contrato b ON a.id_faturamento = b.id_faturamento INNER JOIN tb_contrato_plano_pessoa c ON b.id_contrato_plano_pessoa = c.id_contrato_plano_pessoa INNER JOIN tb_pessoa d ON c.id_pessoa = d.id_pessoa INNER JOIN tb_plano e ON a.id_plano = e.id_plano WHERE a.adesao = '1' ".$filtro_referencia." ", "a.*, b.*, c.nome_contrato, d.nome, a.status AS status_contrato, a.valor_adesao AS valor_adesao_faturamento, e.nome AS nome_plano, e.cod_servico");
    }

    if($servico == 'suporte' || !$servico){
        $dados_consulta_suporte = DBRead('', 'tb_faturamento a',"INNER JOIN tb_faturamento_contrato b ON a.id_faturamento = b.id_faturamento INNER JOIN tb_contrato_plano_pessoa c ON b.id_contrato_plano_pessoa = c.id_contrato_plano_pessoa INNER JOIN tb_pessoa d ON c.id_pessoa = d.id_pessoa INNER JOIN tb_plano e ON a.id_plano = e.id_plano WHERE e.cod_servico = 'call_suporte' AND b.contrato_pai = '1' ".$filtro_referencia." AND a.status = '1'", "a.*, b.*, c.nome_contrato, d.nome, a.status AS status_contrato, e.nome AS nome_plano, a.qtd_clientes_teto AS quantidade_clientes_teto, e.cod_servico");
    }

    if($servico == 'ativo' || !$servico){
        $dados_consulta_ativo = DBRead('', 'tb_faturamento a',"INNER JOIN tb_faturamento_contrato b ON a.id_faturamento = b.id_faturamento INNER JOIN tb_contrato_plano_pessoa c ON b.id_contrato_plano_pessoa = c.id_contrato_plano_pessoa INNER JOIN tb_pessoa d ON c.id_pessoa = d.id_pessoa INNER JOIN tb_plano e ON a.id_plano = e.id_plano WHERE e.cod_servico = 'call_ativo' AND b.contrato_pai = '1' ".$filtro_referencia." AND a.status = '1' ", "a.*, b.*, c.nome_contrato, d.nome, a.status AS status_contrato, e.nome AS nome_plano, e.cod_servico");
    }

    if($servico == 'monitoramento' || !$servico){
	    $dados_consulta_monitoramento = DBRead('', 'tb_faturamento a',"INNER JOIN tb_faturamento_contrato b ON a.id_faturamento = b.id_faturamento INNER JOIN tb_contrato_plano_pessoa c ON b.id_contrato_plano_pessoa = c.id_contrato_plano_pessoa INNER JOIN tb_pessoa d ON c.id_pessoa = d.id_pessoa INNER JOIN tb_plano e ON a.id_plano = e.id_plano WHERE e.cod_servico = 'call_monitoramento' AND b.contrato_pai = '1' ".$filtro_referencia." AND a.status = '1' ORDER BY d.nome", "a.*, b.*, c.nome_contrato, d.nome, a.status AS status_contrato, a.id_usuario AS id_usuario_faturamento, e.nome AS nome_plano, c.dia_pagamento, e.cod_servico");
    }

    if($dados_consulta_adesao || $dados_consulta_suporte || $dados_consulta_ativo || $dados_consulta_monitoramento){
        echo '
            <table class="table table-hover dataTable" style="margin-bottom:0;">
                <thead>
                    <tr>
                        <th>Ano</th>
                        <th>Mês</th>					
                        <th>Plano</th>					
                        <th>Quantidade</th>					
                        <th>Valor Faturado</th>					
                    </tr>
                </thead>
                <tbody>
        ';              

		$contador_valor_faturamento = 0;
		$contador_quantidade_faturamento = 0;
        $dados_tabela = array();
        $cont = 0;
		foreach($dados_consulta_adesao as $conteudo_consulta_adesao){
			
            $mes_ano_tabela = explode("-", $conteudo_consulta_adesao['data_referencia']);
            $mes_tabela = $mes_ano_tabela[1];
            $ano_tabela = $mes_ano_tabela[0];

               $dados_tabela['adesao'][$ano_tabela.'-'.$mes_tabela]['valor_faturado'] = $dados_tabela['adesao']['valor_faturado'] + $conteudo_consulta_adesao['valor_adesao_faturamento'];
			   $dados_tabela['adesao'][$ano_tabela.'-'.$mes_tabela]['qtd'] ++;
				
			$contador_valor_faturamento = $conteudo_consulta_adesao['valor_adesao_faturamento'] + $contador_valor_faturamento;
		    $contador_quantidade_faturamento ++;
            $cont++;
		}

        foreach($dados_consulta_suporte as $conteudo_consulta_suporte){
			
            $mes_ano_tabela = explode("-", $conteudo_consulta_suporte['data_referencia']);
            $mes_tabela = $mes_ano_tabela[1];
            $ano_tabela = $mes_ano_tabela[0];
			
				$dados_tabela['suporte'][$ano_tabela.'-'.$mes_tabela]['valor_faturado'] = $dados_tabela['suporte']['valor_faturado'] + $conteudo_consulta_suporte['valor_cobranca'];
			   	$dados_tabela['suporte'][$ano_tabela.'-'.$mes_tabela]['qtd'] ++;

			$contador_valor_faturamento = $conteudo_consulta_suporte['valor_cobranca'] + $contador_valor_faturamento;
		    $contador_quantidade_faturamento ++;
            $cont++;
		}

        foreach($dados_consulta_ativo as $conteudo_consulta_ativo){
			
            $mes_ano_tabela = explode("-", $conteudo_consulta_ativo['data_referencia']);
            $mes_tabela = $mes_ano_tabela[1];
            $ano_tabela = $mes_ano_tabela[0];
			
				$dados_tabela['ativo'][$ano_tabela.'-'.$mes_tabela]['valor_faturado'] = $dados_tabela['ativo']['valor_faturado'] + $conteudo_consulta_ativo['valor_cobranca'];
				$dados_tabela['ativo'][$ano_tabela.'-'.$mes_tabela]['qtd'] ++;
				
			$contador_valor_faturamento = $conteudo_consulta_ativo['valor_cobranca'] + $contador_valor_faturamento;
		    $contador_quantidade_faturamento ++;
            $cont++;
		}

        foreach($dados_consulta_monitoramento as $conteudo_consulta_monitoramento){
			
            $mes_ano_tabela = explode("-", $conteudo_consulta_monitoramento['data_referencia']);
            $mes_tabela = $mes_ano_tabela[1];
            $ano_tabela = $mes_ano_tabela[0];
           
				$dados_tabela['monitoramento'][$ano_tabela.'-'.$mes_tabela]['valor_faturado'] = $dados_tabela['monitoramento']['valor_faturado'] + $conteudo_consulta_monitoramento['valor_cobranca'];
				$dados_tabela['monitoramento'][$ano_tabela.'-'.$mes_tabela]['qtd'] ++;
				
			$contador_valor_faturamento = $conteudo_consulta_monitoramento['valor_cobranca'] + $contador_valor_faturamento;
		    $contador_quantidade_faturamento ++;
            $cont++;
		}
        foreach($dados_tabela as $servico => $dados_conteudo_tabela){
			foreach($dados_conteudo_tabela as $key => $conteudo_tabela){
				$mes_ano_tabela_exibicao = explode("-", $key);
				$mes_tabela_exibicao = $mes_ano_tabela_exibicao[1];
				$ano_tabela_exibicao = $mes_ano_tabela_exibicao[0];
	
				if($servico == 'suporte'){
					$plano = "Call Center - Suporte";
				}else if($servico == 'ativo'){
					$plano = "Call Center - Ativo";
				}else if($servico == 'monitoramento'){
					$plano = "Call Center - Monitoramento";
				}else{
					$plano = "Call Center - Adesões";
				}
				echo "<tr>";
					echo "<td>".$ano_tabela_exibicao."</td>";			
					echo "<td>".$mes_tabela_exibicao."</td>";			
					echo "<td>".$plano."</td>";			
					echo "<td>".$conteudo_tabela['qtd']."</td>";				
					echo "<td data-order='".$conteudo_tabela['valor_faturado']."'><strong>R$ ".converteMoeda($conteudo_tabela['valor_faturado'],'moeda')."</strong></td>";	
				echo "</tr>";
			}
		}
       
		echo '		
			</tbody>';
			echo "<tfoot>";
					
					echo '<tr>';
                        echo '<th>Totais</th>';
                        echo '<th></th>';
                        echo '<th></th>';
                        echo '<th>'.$contador_quantidade_faturamento.'</th>';
						echo '<th>R$ '.converteMoeda($contador_valor_faturamento,'moeda').'</th>';
                        echo '<th></th>';
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
								filename: 'relatorio_treasy_receita_operacional_agrupado_por_mes',
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

function relatorio_receita_operacional($referencia, $dados_meses, $servico){

    $legenda_servico = "Todos";
    if($servico){
        if($servico == 'suporte'){
            $legenda_servico = "Call Center - Suporte";
        }else if($servico == 'ativo'){
            $legenda_servico = "Call Center - Ativo";
        }else if($servico == 'monitoramento'){
            $legenda_servico = "Call Center - Monitoramento";
        }else{
            $legenda_servico = "Call Center - Adesões";
        }
    }

    if($referencia){
        $mes_ano = explode("-", $referencia);
        $mes = $mes_ano[1];
        $ano = $mes_ano[0];
        $periodo_amostra = "<span style=\"font-size: 14px;\"><strong>Data de Referência: </strong>".$dados_meses[$mes]." de ".$ano.", <strong>Serviço: </strong>".$legenda_servico."</span>";
        $filtro_referencia = " AND a.data_referencia = '".$referencia."' ";    
    }else{ 
        $periodo_amostra = "<span style=\"font-size: 14px;\"><strong>Data de Referência: </strong>Todas, <strong>Serviço: </strong>".$legenda_servico."</span>";
        $filtro_referencia = '';
    }
	
	$gerado = "<span style=\"font-size: 14px;\"><strong>Gerado em: </strong> ".converteDataHora(getDataHora())."</span>";	

	echo "<div class=\"col-md-10 col-md-offset-1\" style=\"padding: 0\">";
	echo "<legend style=\"text-align:center;\"><strong>Relatório Treasy - Receita Operacional</strong><br>$gerado</legend>";
	echo "<legend style=\"text-align:center;\">$periodo_amostra</legend>";

    if($servico == 'adesao' || !$servico){
	    $dados_consulta_adesao = DBRead('', 'tb_faturamento a',"INNER JOIN tb_faturamento_contrato b ON a.id_faturamento = b.id_faturamento INNER JOIN tb_contrato_plano_pessoa c ON b.id_contrato_plano_pessoa = c.id_contrato_plano_pessoa INNER JOIN tb_pessoa d ON c.id_pessoa = d.id_pessoa INNER JOIN tb_plano e ON a.id_plano = e.id_plano WHERE a.adesao = '1' ".$filtro_referencia." ", "a.*, b.*, c.nome_contrato, d.nome, a.status AS status_contrato, a.valor_adesao AS valor_adesao_faturamento, e.nome AS nome_plano, e.cod_servico");
    }

    if($servico == 'suporte' || !$servico){
        $dados_consulta_suporte = DBRead('', 'tb_faturamento a',"INNER JOIN tb_faturamento_contrato b ON a.id_faturamento = b.id_faturamento INNER JOIN tb_contrato_plano_pessoa c ON b.id_contrato_plano_pessoa = c.id_contrato_plano_pessoa INNER JOIN tb_pessoa d ON c.id_pessoa = d.id_pessoa INNER JOIN tb_plano e ON a.id_plano = e.id_plano WHERE e.cod_servico = 'call_suporte' AND b.contrato_pai = '1' ".$filtro_referencia." AND a.status = '1'", "a.*, b.*, c.nome_contrato, d.nome, a.status AS status_contrato, e.nome AS nome_plano, a.qtd_clientes_teto AS quantidade_clientes_teto, e.cod_servico");
    }

    if($servico == 'ativo' || !$servico){
        $dados_consulta_ativo = DBRead('', 'tb_faturamento a',"INNER JOIN tb_faturamento_contrato b ON a.id_faturamento = b.id_faturamento INNER JOIN tb_contrato_plano_pessoa c ON b.id_contrato_plano_pessoa = c.id_contrato_plano_pessoa INNER JOIN tb_pessoa d ON c.id_pessoa = d.id_pessoa INNER JOIN tb_plano e ON a.id_plano = e.id_plano WHERE e.cod_servico = 'call_ativo' AND b.contrato_pai = '1' ".$filtro_referencia." AND a.status = '1'", "a.*, b.*, c.nome_contrato, d.nome, a.status AS status_contrato, e.nome AS nome_plano, e.cod_servico");
    }

    if($servico == 'monitoramento' || !$servico){
	    $dados_consulta_monitoramento = DBRead('', 'tb_faturamento a',"INNER JOIN tb_faturamento_contrato b ON a.id_faturamento = b.id_faturamento INNER JOIN tb_contrato_plano_pessoa c ON b.id_contrato_plano_pessoa = c.id_contrato_plano_pessoa INNER JOIN tb_pessoa d ON c.id_pessoa = d.id_pessoa INNER JOIN tb_plano e ON a.id_plano = e.id_plano WHERE e.cod_servico = 'call_monitoramento' AND b.contrato_pai = '1' ".$filtro_referencia." AND a.status = '1' ORDER BY d.nome", "a.*, b.*, c.nome_contrato, d.nome, a.status AS status_contrato, a.id_usuario AS id_usuario_faturamento, e.nome AS nome_plano, c.dia_pagamento, e.cod_servico");
    }

    if($dados_consulta_adesao || $dados_consulta_suporte || $dados_consulta_ativo || $dados_consulta_monitoramento){
        echo '
            <table class="table table-hover dataTable" style="margin-bottom:0;">
                <thead>
                    <tr>
                        <th>Ano</th>
                        <th>Mês</th>					
                        <th>Plano</th>					
                        <th>Quantidade</th>					
                        <th>Valor Faturado</th>					
                        <th>Cliente</th>					
                    </tr>
                </thead>
                <tbody>
        ';              

		$contador_valor_faturamento = 0;
		$contador_quantidade_faturamento = 0;
        $dados_tabela = array();
        $cont = 0;

		foreach($dados_consulta_adesao as $conteudo_consulta_adesao){
			
            $mes_ano_tabela = explode("-", $conteudo_consulta_adesao['data_referencia']);
            $mes_tabela = $mes_ano_tabela[1];
            $ano_tabela = $mes_ano_tabela[0];
            $nome_plano = $conteudo_consulta_adesao['nome_plano'];
            $cod_servico = $conteudo_consulta_adesao['cod_servico'];
            $servico = getNomeServico($cod_servico);

			//NOME DO CONTRATO
			if($conteudo_consulta_adesao['nome_contrato']){
                $nome_contrato = " (".$conteudo_consulta_adesao['nome_contrato'].") ";
            }else{
                $nome_contrato = '';
            }

            $contrato = $conteudo_consulta_adesao['nome']." ".$nome_contrato;          
               
               $dados_tabela[$cont]['ano'] = $ano_tabela;
               $dados_tabela[$cont]['mes'] = $mes_tabela;
               $dados_tabela[$cont]['plano'] = $servico." - ".$nome_plano;
               $dados_tabela[$cont]['qtd'] = '1';
               $dados_tabela[$cont]['valor_faturado'] = $conteudo_consulta_adesao['valor_adesao_faturamento'];
               $dados_tabela[$cont]['cliente'] = $contrato;
				
			$contador_valor_faturamento = $conteudo_consulta_adesao['valor_adesao_faturamento'] + $contador_valor_faturamento;
		    $contador_quantidade_faturamento ++;
            $cont++;
		}

        foreach($dados_consulta_suporte as $conteudo_consulta_suporte){
			
            $mes_ano_tabela = explode("-", $conteudo_consulta_suporte['data_referencia']);
            $mes_tabela = $mes_ano_tabela[1];
            $ano_tabela = $mes_ano_tabela[0];
            $nome_plano = $conteudo_consulta_suporte['nome_plano'];
            $cod_servico = $conteudo_consulta_suporte['cod_servico'];
            $servico = getNomeServico($cod_servico);

			//NOME DO CONTRATO
			if($conteudo_consulta_suporte['nome_contrato']){
                $nome_contrato = " (".$conteudo_consulta_suporte['nome_contrato'].") ";
            }else{
                $nome_contrato = '';
            }

            $contrato = $conteudo_consulta_suporte['nome']." ".$nome_contrato;
			
               $dados_tabela[$cont]['ano'] = $ano_tabela;
               $dados_tabela[$cont]['mes'] = $mes_tabela;
               $dados_tabela[$cont]['plano'] = $servico." - ".$nome_plano;
               $dados_tabela[$cont]['qtd'] = '1';
               $dados_tabela[$cont]['valor_faturado'] = $conteudo_consulta_suporte['valor_cobranca'];
               $dados_tabela[$cont]['cliente'] = $contrato;
				
			$contador_valor_faturamento = $conteudo_consulta_suporte['valor_cobranca'] + $contador_valor_faturamento;
		    $contador_quantidade_faturamento ++;
            $cont++;
		}

        foreach($dados_consulta_ativo as $conteudo_consulta_ativo){
			
            $mes_ano_tabela = explode("-", $conteudo_consulta_ativo['data_referencia']);
            $mes_tabela = $mes_ano_tabela[1];
            $ano_tabela = $mes_ano_tabela[0];
            $nome_plano = $conteudo_consulta_ativo['nome_plano'];
            $cod_servico = $conteudo_consulta_ativo['cod_servico'];
            $servico = getNomeServico($cod_servico);

			//NOME DO CONTRATO
			if($conteudo_consulta_ativo['nome_contrato']){
                $nome_contrato = " (".$conteudo_consulta_ativo['nome_contrato'].") ";
            }else{
                $nome_contrato = '';
            }

            $contrato = $conteudo_consulta_ativo['nome']." ".$nome_contrato;
			
               $dados_tabela[$cont]['ano'] = $ano_tabela;
               $dados_tabela[$cont]['mes'] = $mes_tabela;
               $dados_tabela[$cont]['plano'] = $servico." - ".$nome_plano;
               $dados_tabela[$cont]['qtd'] = '1';
               $dados_tabela[$cont]['valor_faturado'] = $conteudo_consulta_ativo['valor_cobranca'];
               $dados_tabela[$cont]['cliente'] = $contrato;
				
			$contador_valor_faturamento = $conteudo_consulta_ativo['valor_cobranca'] + $contador_valor_faturamento;
		    $contador_quantidade_faturamento ++;
            $cont++;
		}

        foreach($dados_consulta_monitoramento as $conteudo_consulta_monitoramento){
			
            $mes_ano_tabela = explode("-", $conteudo_consulta_monitoramento['data_referencia']);
            $mes_tabela = $mes_ano_tabela[1];
            $ano_tabela = $mes_ano_tabela[0];
            $nome_plano = $conteudo_consulta_monitoramento['nome_plano'];
            $cod_servico = $conteudo_consulta_monitoramento['cod_servico'];
            $servico = getNomeServico($cod_servico);

			//NOME DO CONTRATO
			if($conteudo_consulta_monitoramento['nome_contrato']){
                $nome_contrato = " (".$conteudo_consulta_monitoramento['nome_contrato'].") ";
            }else{
                $nome_contrato = '';
            }

            $contrato = $conteudo_consulta_monitoramento['nome']." ".$nome_contrato;
			
               $dados_tabela[$cont]['ano'] = $ano_tabela;
               $dados_tabela[$cont]['mes'] = $mes_tabela;
               $dados_tabela[$cont]['plano'] = $servico;
               $dados_tabela[$cont]['qtd'] = '1';
               $dados_tabela[$cont]['valor_faturado'] = $conteudo_consulta_monitoramento['valor_cobranca'];
               $dados_tabela[$cont]['cliente'] = $contrato;
				
			echo "</tr>";
			$contador_valor_faturamento = $conteudo_consulta_monitoramento['valor_cobranca'] + $contador_valor_faturamento;
		    $contador_quantidade_faturamento ++;
            $cont++;
		}

        foreach($dados_tabela as $conteudo_tabela){
           	echo "<tr>";
               echo "<td>".$conteudo_tabela['ano']."</td>";			
               echo "<td>".$conteudo_tabela['mes']."</td>";			
               echo "<td>".$conteudo_tabela['plano']."</td>";			
               echo "<td>".$conteudo_tabela['qtd']."</td>";				
               echo "<td data-order='".$conteudo_tabela['valor_faturado']."'><strong>R$ ".converteMoeda($conteudo_tabela['valor_faturado'],'moeda')."</strong></td>";	
               echo "<td>".$conteudo_tabela['cliente']."</td>";	
			echo "</tr>";
		}

		echo '		
			</tbody>';
			echo "<tfoot>";
					
					echo '<tr>';
                        echo '<th>Totais</th>';
                        echo '<th></th>';
                        echo '<th></th>';
                        echo '<th>'.$contador_quantidade_faturamento.'</th>';
						echo '<th>R$ '.converteMoeda($contador_valor_faturamento,'moeda').'</th>';
                        echo '<th></th>';
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
								filename: 'relatorio_treasy_receita_operacional',
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