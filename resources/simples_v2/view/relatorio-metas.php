<?php
require_once(__DIR__."/../class/System.php");

$usuario = (!empty($_POST['usuario'])) ? $_POST['usuario'] : '';
$lider = (!empty($_POST['lider'])) ? $_POST['lider'] : '';
$afastamentos = (!empty($_POST['afastamentos'])) ? $_POST['afastamentos'] : 's';
$gerar = (!empty($_POST['gerar'])) ? 1 : 0;
$id_usuario = $_SESSION['id_usuario'];
$dados = DBRead('', 'tb_usuario', "WHERE id_usuario = '$id_usuario'");
$perfil_sistema = $dados[0]['id_perfil_sistema'];
$tipo_relatorio = (!empty($_POST['tipo_relatorio'])) ? $_POST['tipo_relatorio'] : '1';
$dobra_qtd_integral = (!empty($_POST['dobra_qtd_integral'])) ? $_POST['dobra_qtd_integral'] : 0;
$dobra_qtd_outros = (!empty($_POST['dobra_qtd_outros'])) ? $_POST['dobra_qtd_outros'] : 0;

$data_de_referencia_hoje = new DateTime(getDataHora('data'));
$data_de_referencia_hoje->modify('first day of this month');
$data_de_inicial = $data_de_referencia_hoje->format('d/m/Y');
$data_de_referencia_hoje->modify('last day of this month');
$data_ate_inicial = $data_de_referencia_hoje->format('d/m/Y');
$data_de_referencia_hoje->modify('first day of this month');
$data_de_referencia_hoje = $data_de_referencia_hoje->format('Y-m-d');

$data_referencia = (!empty($_POST['data_referencia'])) ? $_POST['data_referencia'] : $data_de_referencia_hoje;

$data_de = (!empty($_POST['data_de'])) ? $_POST['data_de'] : $data_de_inicial;
$data_ate = (!empty($_POST['data_ate'])) ? $_POST['data_ate'] : $data_ate_inicial;

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
    $display_row_afastamentos = 'style="display:none;"';   
    $display_row_qtd_dobra = 'style="display:none;"';   
    $display_row_datas = '';   
    $display_row_data_referencia = 'style="display:none;"';  

}else if ($tipo_relatorio == 2) {
    $display_row_lider = '';
	$display_row_atendente = 'style="display:none;"';	
    $display_row_afastamentos = '';
    $display_row_qtd_dobra = 'style="display:none;"';   
    $display_row_datas = '';     
    $display_row_data_referencia = 'style="display:none;"'; 

}else if ($tipo_relatorio == 3) {
    $display_row_lider = 'style="display:none;"';
	$display_row_atendente = 'style="display:none;"';	
    $display_row_afastamentos = '';
    $display_row_qtd_dobra = 'style="display:none;"';     
    $display_row_datas = '';    
    $display_row_data_referencia = 'style="display:none;"'; 

}else if ($tipo_relatorio == 4) {
    $display_row_lider = 'style="display:none;"';
	$display_row_atendente = 'style="display:none;"';	
    $display_row_afastamentos = 'style="display:none;"';
    $display_row_qtd_dobra = '';      
    $display_row_datas = '';   
    $display_row_data_referencia = 'style="display:none;"'; 

}else if ($tipo_relatorio == 5) {
    $display_row_lider = 'style="display:none;"';
	$display_row_atendente = 'style="display:none;"';	
    $display_row_afastamentos = 'style="display:none;"';
    $display_row_qtd_dobra = 'style="display:none;"';      
    $display_row_datas = 'style="display:none;"';        
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
	                    <h3 class="panel-title text-left pull-left" style="margin-top: 2px;">Relatório - Metas:</h3>
	                    <div class="panel-title text-right pull-right"><button data-toggle="collapse" data-target="#accordionRelatorio" class="btn btn-xs btn-info" type="button" title="Visualizar filtros"><i id="i_collapse" class="fa fa-<?=$collapse_icon?>"></i></button></div>
	                </div>
	                <div id="accordionRelatorio" class="panel-collapse collapse <?=$collapse?>">
	                	<div class="panel-body">
							<?php if($perfil_sistema != '3'){ ?>
	                		<div class="row">
								<div class="col-md-12">
									<div class="form-group">
										<label>Tipo de Relatório:</label> 
										<select name="tipo_relatorio" id="tipo_relatorio" class="form-control input-sm">
											<option value="1" <?php if($tipo_relatorio == '1'){echo 'selected';}?>>Individual</option>											
											<option value="2" <?php if($tipo_relatorio == '2'){echo 'selected';}?>>Equipe</option>
											<option value="3" <?php if($tipo_relatorio == '3'){echo 'selected';}?>>Geral</option>
											<option value="5" <?php if($tipo_relatorio == '5'){echo 'selected';}?>>Mensal</option>
											<option value="4" <?php if($tipo_relatorio == '4'){echo 'selected';}?>>Meta Solidária</option>
										</select>
									</div>
								</div>
							</div>
							<?php } ?>	
							<div class="row"  id="row_datas" <?=$display_row_datas?>>
								<div class="col-md-6">
									<div class="form-group" >
								        <label>*Data Inicial:</label>
								        <input type="text" class="form-control date calendar input-sm" name="data_de" value="<?=$data_de?>" required>
								    </div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
								        <label>*Data Final:</label>
								        <input type="text" class="form-control date calendar input-sm" name="data_ate" value="<?=$data_ate?>" required>
								    </div>
								</div>
							</div>
							<?php if($perfil_sistema != '3'){ ?>
							<div class="row" id="row_atendente" <?=$display_row_atendente?>>
								<div class="col-md-12">
									<div class="form-group">
								        <label for="">Atendente:</label>
								        <select name="usuario" class="form-control input-sm">
								            <?php
								            	$dados_usuarios = DBRead('','tb_usuario a',"INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.status = '1' AND (id_perfil_sistema = 3) ORDER BY b.nome ASC","a.id_usuario, b.nome");
								            	if($dados_usuarios){
								            		foreach ($dados_usuarios as $conteudo_usuarios) {
														$selected = $usuario == $conteudo_usuarios['id_usuario'] ? "selected" : "";
								            			echo "<option value='".$conteudo_usuarios['id_usuario']."' ".$selected.">".$conteudo_usuarios['nome']."</option>";
								            		}
								            	}
								            ?>
								        </select>
								    </div>
								</div>
							</div>
							<?php } ?>	
							<?php if($perfil_sistema != '3'){ ?>
							<div class="row" id="row_lider" <?=$display_row_lider?>>
								<div class="col-md-12">
									<div class="form-group">
										<label for="">Líder Direto:</label>
										<select name="lider" class="form-control input-sm">
												<?php
												$dados_lider = DBRead('', 'tb_usuario a', "INNER JOIN tb_usuario b ON a.lider_direto = b.id_usuario INNER JOIN tb_pessoa c ON b.id_pessoa = c.id_pessoa WHERE a.lider_direto AND a.status = '1' AND b.status = 1 AND (b.id_perfil_sistema = '23' OR b.id_perfil_sistema = '13' OR b.id_perfil_sistema = '12' OR b.id_perfil_sistema = '15') GROUP BY a.lider_direto, c.nome ORDER BY c.nome ASC", "a.lider_direto, c.nome");
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
							<?php if($perfil_sistema != '3'){ ?>
								<div class="row" id="row_afastamentos" <?=$display_row_afastamentos?>>
									<div class="col-md-12">
										<div class="form-group">
											<label for="">Ignorar afastamentos:</label>
											<select name="afastamentos" class="form-control input-sm">
												<option value="s" <?php if($afastamentos == 's'){ echo 'selected';}?>>Sim</option>
												<option value="n" <?php if($afastamentos == 'n'){ echo 'selected';}?>>Não</option>
											</select>
										</div>
									</div>
								</div>											  
							<?php } ?>  
                            <?php if($perfil_sistema != '3'){ ?>
							<div class="row" id="row_qtd_dobra" <?=$display_row_qtd_dobra?>>
								<div class="col-md-6">
									<div class="form-group">
                                        <label>*QTD Dobra Turno Integral:</label>
								        <input type="text" class="form-control number_int input-sm" name="dobra_qtd_integral" value="<?=$dobra_qtd_integral?>" required>
								    </div>
								</div>
                                <div class="col-md-6">
									<div class="form-group">
                                        <label>*QTD Dobra Outros Turnos:</label>
								        <input type="text" class="form-control number_int input-sm" name="dobra_qtd_outros" value="<?=$dobra_qtd_outros?>" required>
								    </div>
								</div>
							</div>
							<?php } ?>	
							<?php if($perfil_sistema != '3'){ ?>
								<div class="row" id="row_data_referencia" <?=$display_row_data_referencia?>>
									<div class="col-md-12">
										<div class="form-group">
											<label>Data de Referência:</label>
											<select name="data_referencia" class="form-control input-sm">
												<?php
												$dados_data_referencia = DBRead('', 'tb_resultado_metas', "GROUP BY data_referencia ORDER BY data_referencia ASC", "data_referencia");

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

												if ($dados_data_referencia) {
													foreach ($dados_data_referencia as $conteudo_data_referencia) {
														$mes = explode("-", $conteudo_data_referencia['data_referencia']);
														$ano = $mes[0];
														$mes = $meses[$mes[1]];
														$selected = $data_referencia == $conteudo_data_referencia['data_referencia'] ? "selected" : "";
														
														echo "<option value='".$conteudo_data_referencia['data_referencia']."' ".$selected.">".$mes."/".$ano."</option>";
													}
												}
												?>
										</select>
										</div>
									</div>
								</div>											  
							<?php } ?>  
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

	<div id="aguarde" class="alert alert-info text-center">
		Aguarde, gerando relatório... <i class="fa fa-spinner faa-spin animated"></i>
	</div>	
	
	<div id="resultado" class="row" style="display:none;">	
		<?php 
			if ($gerar) {
				if ($perfil_sistema == '3') {
					$usuario = $id_usuario;
					$tipo_relatorio == 1;
				}
				if ($tipo_relatorio == 1) {
					relatorio_meta_individual($data_de, $data_ate, $usuario);

				} else if ($tipo_relatorio == 2 && $perfil_sistema != '3') {
					relatorio_meta_equipe($data_de, $data_ate, $lider, $perfil_sistema, $afastamentos);	

				}else if ($tipo_relatorio == 3 && $perfil_sistema != '3') {
					relatorio_meta_geral($data_de, $data_ate, $perfil_sistema, $afastamentos);		

				}else if ($tipo_relatorio == 4 && $perfil_sistema != '3') {
					relatorio_meta_solidaria($data_de, $data_ate, $dobra_qtd_integral, $dobra_qtd_outros);		

				} else if ($tipo_relatorio == 5 && $perfil_sistema != '3') {
					relatorio_meta_mes($data_referencia);		
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
		if(tipo_relatorio == 1){
			$('#row_lider').hide();
			$('#row_atendente').show();
			$('#row_afastamentos').hide();
			$('#row_qtd_dobra').hide();
			$('#row_datas').show();
			$('#row_data_referencia').hide();
		}else if(tipo_relatorio == 2){
			$('#row_lider').show();
			$('#row_atendente').hide();
			$('#row_afastamentos').show();
			$('#row_qtd_dobra').hide();
			$('#row_datas').show();
			$('#row_data_referencia').hide();
		}else if(tipo_relatorio == 3){
			$('#row_lider').hide();
			$('#row_atendente').hide();
			$('#row_afastamentos').show();
			$('#row_qtd_dobra').hide();
			$('#row_datas').show();
			$('#row_data_referencia').hide();
		}else if(tipo_relatorio == 4){
			$('#row_lider').hide();
			$('#row_atendente').hide();
			$('#row_afastamentos').hide();
			$('#row_qtd_dobra').show();
			$('#row_datas').show();
			$('#row_data_referencia').hide();
		}else if(tipo_relatorio == 5){
			$('#row_lider').hide();
			$('#row_atendente').hide();
			$('#row_afastamentos').hide();
			$('#row_qtd_dobra').hide();
			$('#row_datas').hide();
			$('#row_data_referencia').show();
		}
	});

	$('#accordionRelatorio').on('shown.bs.collapse', function(){
       $("#i_collapse").removeClass("fa fa-plus").addClass("fa fa-minus");
    });

    $('#accordionRelatorio').on('hidden.bs.collapse', function(){
       $("#i_collapse").removeClass("fa fa-minus").addClass("fa fa-plus");
    });

	$(document).on('submit', 'form', function () {       
        modalAguarde();
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

function relatorio_meta_mes($data_referencia){

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
	
	$mes = explode("-", $data_referencia);
	$ano = $mes[0];
	$mes = $meses[$mes[1]];

	$periodo_amostra ="<span style=\"font-size: 14px;\"><strong>Data de Referência:</strong> ".$mes."/".$ano."</span>";
	$data_hora = converteDataHora(getDataHora());

	echo "<div class=\"col-md-6 col-md-offset-3\" style=\"padding: 0\">";
	echo "<legend style=\"text-align:center;\"><strong>Relatório de Metas - Mensal</strong><br><span style=\"font-size: 14px;\"><strong>Gerado em:</strong> $data_hora</span></legend>";	
    echo "<legend style=\"text-align:center;\">$periodo_amostra</legend>";

	$dados_resultado_meta = DBRead('', 'tb_resultado_metas', "WHERE data_referencia = '".$data_referencia."' ORDER BY numero_medalha ASC");
	echo '
		<table class="table table-hover dataTable"> 
			<thead> 
				<tr> 
					<th>Medalha</th>
					<th>Atendente</th>
				</tr>
			</thead> 
			<tbody>'
	;

	if($dados_resultado_meta){	
		foreach ($dados_resultado_meta as $conteudo_resultado_meta) {
			if($conteudo_resultado_meta['numero_medalha'] ==  '4'){
				$img = '<img src="inc/img/meta_bronze.png" height="25" width="18">';
				$nome_medalha = "Bronze";
			}else if($conteudo_resultado_meta['numero_medalha'] ==  '3'){
				$img = '<img src="inc/img/meta_silver.png" height="25" width="18">';
				$nome_medalha = "Silver";
			}else if($conteudo_resultado_meta['numero_medalha'] ==  '2'){
				$img = '<img src="inc/img/meta_gold.png" height="25" width="18">';
				$nome_medalha = "Gold";
			}else if($conteudo_resultado_meta['numero_medalha'] ==  '4'){
				$img = '<img src="inc/img/meta_diamond.png" height="25" width="18">';
				$nome_medalha = "Diamond";
			}	
			echo "<tr>";
			echo "<td data-order='".$conteudo_resultado_meta['numero_medalha']."'>".$img." ".$nome_medalha."</td>";
			echo "<td>".$conteudo_resultado_meta['nome_atendente']."</td>";
			
			echo "</tr>";
		}
		echo "
				</tbody> 
			</table>
		";
		
	}else{
		echo '<div style="text-align: center"><strong>Não foram encontradas metas para esse período!</strong></div>';
	}
	
    echo "</div>";

	echo "
		<script>
			$(document).ready(function(){
			    var table = $('.dataTable').DataTable({
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

function relatorio_meta_individual($data_de, $data_ate, $usuario){

	$data_inicial_turno = (new DateTime(converteData($data_de)))->modify('first day of this month')->format('Y-m-d');
	$turno_atendente = DBRead('', 'tb_horarios_escala', "WHERE data_inicial = '".$data_inicial_turno."' AND id_usuario = '".$usuario."' LIMIT 1", "carga_horaria");
	if($turno_atendente){
		$turno_atendente = $turno_atendente[0]['carga_horaria'];
	}else{
		$turno_atendente = "integral";
	}

	$fila = "'callATENDIMENTOvip','callATENDIMENTOalta','callATENDIMENTOnormal','callATENDIMENTObaixa','callATENDIMENTOnotificacaoparada', 'callATENDIMENTOvipEXT','callATENDIMENTOaltaEXT','callATENDIMENTOnormalEXT','callATENDIMENTObaixaEXT','callATENDIMENTOnotificacaoparadaEXT','callATENDIMENTOvipEXP','callATENDIMENTOaltaEXP','callATENDIMENTOnormalEXP','callATENDIMENTObaixaEXP','callATENDIMENTOnotificacaoparadaEXP'";

	$data_hora = converteDataHora(getDataHora());
	$dados_usuario = DBRead('','tb_usuario a',"INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE id_usuario = '$usuario'","a.*, b.nome");
	$nome_usuario = $dados_usuario[0]['nome'];
	$id_asterisk_usuario = $dados_usuario[0]['id_asterisk'];
	$id_ponto_usuario = $dados_usuario[0]['id_ponto']; 

	$qtd_entradas = 0;
	$soma_notas = 0;
	$qtd_notas = 0;
	$soma_ta = 0;
    $duracao_total_pausa_registro = 0;
    $duracao_total_pausa_ativo = 0;
    $duracao_total_pausa_manutencao_ti = 0;
	$duracao_total_pausa_ajuda_supervisao = 0;
    $monitoria_resultado = 0;

	$nome_usuario_legend = '<span style="font-size: 14px;"><strong>Atendente:</strong> '.$nome_usuario.'</span>';	
	if ($data_de && $data_ate) {
		$periodo_amostra ="<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> De $data_de até $data_ate</span>";
		
	} else if ($data_de) {
		$periodo_amostra ="<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> A partir de $data_de</span>";
		
	} else if ($data_ate) {
		$periodo_amostra ="<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> Até $data_ate</span>";
		
	} else {
	    $periodo_amostra = "";
    }
    
	echo "<div class=\"col-md-10 col-md-offset-1\" style=\"padding: 0\">";
	echo "<legend style=\"text-align:center;\"><strong>Relatório de Metas Individuais</strong><br><span style=\"font-size: 14px;\"><strong>Gerado em:</strong> $data_hora</span></legend>";	
    echo "<legend style=\"text-align:center;\">$periodo_amostra</legend>";
    echo "<legend style=\"text-align:center;\">$nome_usuario_legend</legend>";
	
	$filtro_entradas = "";
	$filtro_pausas = "";  
	$filtro_erros = "";

    if ($data_de) {
		$filtro_entradas .= " AND b.time >= '".converteData($data_de)." 00:00:00'";
		$filtro_pausas .= " AND data_pause >= '".converteData($data_de)." 00:00:00'";    
		$filtro_erros .= " AND data_cadastrado >= '".converteData($data_de)." 00:00:00'";
	}

	if ($data_ate) {
        $filtro_entradas .= " AND b.time <= '".converteData($data_ate)." 23:59:59'";
        $filtro_pausas .= " AND data_pause <= '".converteData($data_ate)." 23:59:59'";
		$filtro_erros .= " AND data_cadastrado <= '".converteData($data_ate)." 23:59:59'";
	}

	$filtro_entradas .= " AND b.agent = 'AGENT/$id_asterisk_usuario' AND (c.data2 >= 30 OR c.data4 >= 30)";

	$dados_entradas = DBRead('snep','queue_log a',"INNER JOIN queue_log b ON (b.callid = a.callid AND b.event = 'CONNECT') INNER JOIN queue_log c ON (c.callid = a.callid AND (c.event = 'COMPLETEAGENT' OR c.event = 'COMPLETECALLER' OR c.event = 'BLINDTRANSFER' OR c.event = 'ATTENDEDTRANSFER')) LEFT JOIN pesquisa d ON  a.callid = d.UNIQUEID WHERE a.event = 'ENTERQUEUE' $filtro_entradas GROUP BY b.id ORDER BY b.id", "a.queuename AS 'enterqueue_queuename', c.event AS 'finalizacao_event', c.data1 AS 'finalizacao_data1', c.data2 AS 'finalizacao_data2', c.data4 AS 'finalizacao_data4', d.nota AS 'nota'");

	if ($dados_entradas) {
		
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

	$dados_erros = DBRead('','tb_erro_atendimento',"WHERE id_usuario = '$usuario' AND status = '1' $filtro_erros","COUNT(*) AS 'qtd_erros'");
	$qtd_erros = $dados_erros[0]['qtd_erros'];

	$data_de = converteData($data_de);
	$data_ate = converteData($data_ate);
	
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
	if($result_ponto['data'][0][0]['data']){
		foreach ($result_ponto['data'][0][0]['data'] as $conteudo) {
			if($conteudo['missing_motive'] == 'Atestado'){
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

    $dados_pausas = DBRead('snep','queue_agents_pause',"WHERE codigo = '$id_asterisk_usuario' AND (tipo_pausa = 4 OR tipo_pausa = 6 OR tipo_pausa = 15 OR tipo_pausa = 9) AND data_unpause IS NOT NULL $filtro_pausas ORDER BY data_pause ASC");
    if($dados_pausas){
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
		$dados_resultado = DBRead('','tb_monitoria_resultado', "WHERE id_usuario = '$usuario' AND id_monitoria_mes = '".$monitoria['id_monitoria_mes']."' ");

		if ($dados_resultado) {
			$monitoria_resultado = $dados_resultado[0]['resultado'];

			break;
		}
	}

	$dados_atendimentos = DBRead('','tb_atendimento', "WHERE falha != '2' AND gravado = '1' AND data_inicio BETWEEN '" .$data_de. " 00:00:00' AND '" .$data_ate. " 23:59:59' AND id_usuario = '$usuario'", "COUNT(*) as cont");
    $total_atendimentos = $dados_atendimentos[0]['cont'];

    $atendimentos_hora = '';
	if ($total_atendimentos != 0) {
		$dados_cont_resolucao = DBRead('', 'tb_atendimento', "WHERE falha != '2' AND gravado = '1' AND (resolvido = '1' OR resolvido = '3') AND data_inicio BETWEEN '" .$data_de. " 00:00:00' AND '" .$data_ate. " 23:59:59' AND id_usuario = '$usuario'", "COUNT(*) as cont");
		$cont_resolucao = $dados_cont_resolucao[0]['cont'];
        $percentual_cont_resolucao = sprintf("%01.2f", round($cont_resolucao * 100) / ($total_atendimentos == 0 ? 1 : $total_atendimentos), 2);
        
        $horas_calc_atendimentos_hora = converteHorasDecimal(converteSegundosHoras($segundos_ponto-$duracao_total_pausa_ativo-$duracao_total_pausa_manutencao_ti-$duracao_total_pausa_registro-$duracao_total_pausa_ajuda_supervisao));

        $atendimentos_hora = sprintf("%01.2f", $total_atendimentos / ($horas_calc_atendimentos_hora == 0 ? 1 : $horas_calc_atendimentos_hora), 2);

	} else {
		$cont_resolucao = 0;
        $percentual_cont_resolucao = 0;         
        $horas_calc_atendimentos_hora = 0;
        $atendimentos_hora = 0;
	}

	$qtd_ajuda = DBRead('','tb_solicitacao_ajuda', "WHERE data_inicio BETWEEN '" .$data_de. " 00:00:00' AND '" .$data_ate. " 23:59:59' AND atendente = '".$usuario."' ", "COUNT(*) as cont");
	$qtd_ajuda = $qtd_ajuda[0]['cont'];



	$metas = DBRead('', 'tb_meta', "WHERE tipo = '1' AND status = '1' AND data_ate >= '".$data_ate."' AND data_de <= '".$data_ate."' ORDER BY nota_media, porcentagem_ligacao_nota ASC");
	
	if ($metas) {
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
			
			if(($meta_tma >= floor($soma_ta/($qtd_entradas == 0 ? 1 : $qtd_entradas)) || $status_meta_tma == 2) && ($meta_nota_media <= round($soma_notas/($qtd_notas == 0 ? 1 : $qtd_notas), 2) || $status_meta_nota_media == 2) && ($meta_porcentagem <= round($qtd_notas * 100 /($qtd_entradas == 0 ? 1 : $qtd_entradas), 2) || $status_meta_porcentagem == 2) && ($meta_erros >= $qtd_erros || $status_meta_erros == 2) && ($meta_faltas_justificadas >= $qtd_faltas_justificadas || $status_meta_faltas_justificadas == 2) && ($meta_absenteismo >= $absenteismo || $status_meta_absenteismo == 2) && ($meta_pausa_registro >= ($duracao_total_pausa_registro/60) || $status_meta_pausa_registro == 2) && (($meta_monitoria <= sprintf("%01.2f", $monitoria_resultado)) || $status_meta_monitoria == 2) && (($meta_resolucao <= $percentual_cont_resolucao) || $status_meta_resolucao == 2) && (($meta_atendimentos_hora <= $atendimentos_hora) || $status_meta_atendimentos_hora == 2) && (($meta_qtd_ajudas >= $qtd_ajuda) || $status_meta_qtd_ajudas == 2) && ($flag_tempo_atendimento == 1) ){
				$flag = $meta_nome;
			}
		}
	}
	echo '
			<table class="table table-hover"> 
				<thead> 
					<tr> 
    					<th>TMA</th>
    					<th>Nota média</th>
    					<th>Ligações com nota</th>
						<th>Erros/Reclamações</th>
						<th>Faltas justificadas</th>
						<th>Pontualidade</th>
						<th>Pausa registro</th>
						<th>Monitoria</th>
                        <th>Resolução <i class="fa fa-question-circle" style="cursor:help;" data-toggle="tooltip" data-placement="top" data-container="body" title="Diagnosticado + Resolvido"></i></th>
                        <th>Atendimentos por Hora <i class="fa fa-question-circle" style="cursor:help;" data-toggle="tooltip" data-placement="top" data-container="body" title="Atendimentos Faturados divididos por (Horas na empresa menos Tempo em Ativo, Manutenção/TI, Registro e Ajuda Supervisão)"></i></th>
						<th>Total de atendimentos</th>
						<th>Qtd. ajudas</th>
					</tr>
				</thead>
				<tbody>'
		;
    echo '
    <tr>
		<td>'.gmdate("H:i:s", $soma_ta/($qtd_entradas == 0 ? 1 : $qtd_entradas)).'</td>
		<td>'.sprintf("%01.2f", round($soma_notas/($qtd_notas == 0 ? 1 : $qtd_notas), 2)).'</td>
		<td>'.sprintf("%01.2f", round($qtd_notas * 100 /($qtd_entradas == 0 ? 1 : $qtd_entradas), 2)).'%</td>
		<td>'.$qtd_erros.'</td>
		<td>'.$qtd_faltas_justificadas.'</td>
        <td style="cursor:help;" data-toggle="tooltip" data-placement="top" data-container="body" data-html="true" title="Previsto (sem intervalo): '.$horas_previstas.'<br>Ausência: '.$horas_faltantes.'">'.$absenteismo.'%</td>
        <td>'.converteSegundosHoras($duracao_total_pausa_registro).'</td>
        <td>'.sprintf("%01.2f", $monitoria_resultado).'%</td>
        <td>'.$percentual_cont_resolucao.'%</td>
        <td>'.$atendimentos_hora.'</td>
        <td>'.$total_atendimentos.'</td>
        <td>'.$qtd_ajuda.'</td>
	</tr>	
	';
	echo "
				</tbody> 
			</table>
		";	
	echo "<hr>";
	
	if ($metas) {
		if (!$flag) {
			echo '<div style="text-align: center">
					<strong>Ainda pode alcançar uma meta! Avançar agora! A² :)</strong>
				</div>';
		} else {
			echo '<div style="text-align: center">
					<strong>Com o resultado atual, está atingindo a meta '.$flag.'!</strong>
				</div>';
				if($flag ==  'Bronze'){
					echo '<div style="text-align: center">
							<img src="inc/img/meta_bronze.png" height="200" width="149">
						</div>';
				}else if($flag ==  'Silver'){
					echo '<div style="text-align: center">
							<img src="inc/img/meta_silver.png" height="200" width="149">
						</div>';
				}else if($flag ==  'Gold'){
					echo '<div style="text-align: center">
							<img src="inc/img/meta_gold.png" height="200" width="149">
						</div>';
				}else if($flag ==  'Diamond'){
					echo '<div style="text-align: center">
							<img src="inc/img/meta_diamond.png" height="200" width="149">
						</div>';
				}			
		}

		echo "<hr>";

		echo '
			<table class="table table-hover"> 
				<thead> 
					<tr> 
						<th>Meta</th>
    					<th>TMA</th>
    					<th>Nota média</th>
    					<th>Ligações com nota</th>
						<th>Erros/Reclamações</th>
						<th>Faltas justificadas</th>
						<th>Pontualidade</th>
						<th>Pausa registro</th>
						<th>Monitoria</th>
                        <th>Resolução <i class="fa fa-question-circle" style="cursor:help;" data-toggle="tooltip" data-placement="top" data-container="body" title="Diagnosticado + Resolvido"></i></th>
                        <th>Atendimentos por Hora <i class="fa fa-question-circle" style="cursor:help;" data-toggle="tooltip" data-placement="top" data-container="body" title="Atendimentos Faturados divididos por (Horas na empresa menos Tempo em Ativo, Manutenção/TI, Registro e Ajuda Supervisão)"></i></th>
						<th>Total de atendimentos</th>
						<th>Qtd. ajudas</th>
					</tr>
				</thead> 
				<tbody>'
		;
		$metas = array_reverse($metas);
		foreach ($metas as $meta) {
			if($meta['nome'] ==  'Bronze'){
				$img = '<img src="inc/img/meta_bronze.png" height="25" width="18">';
			}else if($meta['nome'] ==  'Silver'){
				$img = '<img src="inc/img/meta_silver.png" height="25" width="18">';
			}else if($meta['nome'] ==  'Gold'){
				$img = '<img src="inc/img/meta_gold.png" height="25" width="18">';
			}else if($meta['nome'] ==  'Diamond'){
				$img = '<img src="inc/img/meta_diamond.png" height="25" width="18">';
			}	
			echo "<tr>";
			echo "<td>$img ".$meta['nome']."</td>";
			if($meta['status_tempo_medio_atendimento'] == 1){
				echo "<td>".gmdate("H:i:s", $meta['tempo_medio_atendimento'])."</td>";
			}else{
				echo '<td><i class="fa fa-minus" data-toggle="tooltip" data-placement="top" data-container="body" title="Não exigido"></i></td>';
			}
			if($meta['status_nota_media'] == 1){
				echo "<td>".$meta['nota_media']."</td>";
			}else{
				echo '<td><i class="fa fa-minus" data-toggle="tooltip" data-placement="top" data-container="body" title="Não exigido"></i></td>';
			}
			if($meta['status_porcentagem_ligacao_nota'] == 1){
				echo "<td>".$meta['porcentagem_ligacao_nota']."%</td>";
			}else{
				echo '<td><i class="fa fa-minus" data-toggle="tooltip" data-placement="top" data-container="body" title="Não exigido"></i></td>';
			}
			if($meta['status_erros_reclamacoes'] == 1){
				echo "<td>".$meta['erros_reclamacoes']."</td>";
			}else{
				echo '<td><i class="fa fa-minus" data-toggle="tooltip" data-placement="top" data-container="body" title="Não exigido"></i></td>';
			}
			if($meta['status_faltas_justificadas'] == 1){
				echo "<td>".$meta['faltas_justificadas']."</td>";
			}else{
				echo '<td><i class="fa fa-minus" data-toggle="tooltip" data-placement="top" data-container="body" title="Não exigido"></i></td>';
			}
			if($meta['status_absenteismo'] == 1){
				echo "<td>".$meta['absenteismo']."%</td>";
			}else{
				echo '<td><i class="fa fa-minus" data-toggle="tooltip" data-placement="top" data-container="body" title="Não exigido"></i></td>';
            }
            if($meta['status_pausa_registro'] == 1){
				echo "<td>".converteSegundosHoras($meta['pausa_registro']*60)."</td>";
			}else{
				echo '<td><i class="fa fa-minus" data-toggle="tooltip" data-placement="top" data-container="body" title="Não exigido"></i></td>';
            }
            if($meta['status_monitoria'] == 1){
				echo "<td>".sprintf("%01.2f", $meta['monitoria'])."%</td>";
			}else{
				echo '<td><i class="fa fa-minus" data-toggle="tooltip" data-placement="top" data-container="body" title="Não exigido"></i></td>';
			}

			if($meta['status_resolucao'] == 1){
				echo "<td>".$meta['resolucao']."%</td>";
			}else{
				echo '<td><i class="fa fa-minus" data-toggle="tooltip" data-placement="top" data-container="body" title="Não exigido"></i></td>';
            }
            
            if($meta['status_atendimentos_hora'] == 1){
				echo "<td>".$meta['atendimentos_hora']."</td>";
			}else{
				echo '<td><i class="fa fa-minus" data-toggle="tooltip" data-placement="top" data-container="body" title="Não exigido"></i></td>';
			}

			if($meta['status_total_atendimentos'] == 1){
				$firstDate  = new DateTime($data_de);
				$secondDate = new DateTime($data_ate);
				$intvl = $firstDate->diff($secondDate);
				$intervalo = $intvl->d+1;

				//Roger quis que desconsiderasse as 5 folgas mensais
				if($intervalo == 31){
					$intervalo_folgas = 26;
				}else{
					$intervalo_folgas = 25;
				}

				$data_hoje = new DateTime('today');

				if($data_hoje->format('Y-m-d') >= $secondDate->format('Y-m-d')){
					if($turno_atendente != "integral" && $meta['status_total_atendimentos_meio_turno'] == 1){
						echo "<td>".$meta['total_atendimentos_meio_turno']."</td>";
					}else{
						echo "<td>".$meta['total_atendimentos']."</td>";
					}
				}else{
					if($turno_atendente != "integral" && $meta['status_total_atendimentos_meio_turno'] == 1){
						echo "<td>".$meta['total_atendimentos_meio_turno']." <i class='fa fa-question-circle' style='cursor:help;' data-toggle='tooltip' data-placement='top' data-container='body' title='Para atingir a meta deve-se realizar em média ".round($meta['total_atendimentos_meio_turno']/$intervalo_folgas)." atendimentos por dia. Ou ter realizado no mínimo ".round($data_hoje->format('d')*$meta['total_atendimentos_meio_turno']/$intervalo)." atendimentos até o dia de hoje'></i></td>";
					}else{
						echo "<td>".$meta['total_atendimentos']." <i class='fa fa-question-circle' style='cursor:help;' data-toggle='tooltip' data-placement='top' data-container='body' title='Para atingir a meta deve-se realizar em média ".round($meta['total_atendimentos']/$intervalo_folgas)." atendimentos por dia. Ou ter realizado no mínimo ".round($data_hoje->format('d')*$meta['total_atendimentos']/$intervalo)." atendimentos até o dia de hoje'></i></td>";
					}
				}

			}else{
				echo '<td><i class="fa fa-minus" data-toggle="tooltip" data-placement="top" data-container="body" title="Não exigido"></i></td>';
			}

			if($meta['status_qtd_ajudas'] == 1){
				if($turno_atendente != "integral" && $meta['status_qtd_ajudas_meio_turno'] == 1){
					echo "<td>".$meta['qtd_ajudas_meio_turno']."</td>";
				}else{
					echo "<td>".$meta['qtd_ajudas']."</td>";
				}
			}else{
				echo '<td><i class="fa fa-minus" data-toggle="tooltip" data-placement="top" data-container="body" title="Não exigido"></i></td>';
			}

			echo "</tr>";
		}
		echo "
				</tbody> 
			</table>
		";
	}else{
		echo '<div style="text-align: center"><strong>Não foram encontradas metas para esse período!</strong></div>';
	}
	
    echo "</div>";
}

function relatorio_meta_equipe($data_de, $data_ate, $lider, $perfil_sistema, $afastamentos){

	$fila = "'callATENDIMENTOvip','callATENDIMENTOalta','callATENDIMENTOnormal','callATENDIMENTObaixa','callATENDIMENTOnotificacaoparada', 'callATENDIMENTOvipEXT','callATENDIMENTOaltaEXT','callATENDIMENTOnormalEXT','callATENDIMENTObaixaEXT','callATENDIMENTOnotificacaoparadaEXT','callATENDIMENTOvipEXP','callATENDIMENTOaltaEXP','callATENDIMENTOnormalEXP','callATENDIMENTObaixaEXP','callATENDIMENTOnotificacaoparadaEXP'";

	$qtd_entradas_total = 0;
	$soma_notas_total = 0;
	$qtd_notas_total = 0;
	$soma_ta_total = 0;
	$qtd_erros_total = 0;				
    $duracao_total_pausa_registro_total = 0;
    $qtd_faltas_justificadas_total = 0;
    $qtd_monitoria = 0;
	$monitoria_resultado_total = 0;
	
	$resolucao_resultado_total = 0;
    $resolucao_quantidade_total = 0;
    
    $total_atendimentos_total = 0;
    $horas_calc_atendimentos_hora_total = 0;

	$total_qtd_ajuda = 0;


	$data_hora = converteDataHora(getDataHora());

	$dados_usuario = DBRead('','tb_usuario a',"INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE id_usuario = '".$lider."'","b.nome");
	$nome_usuario = $dados_usuario[0]['nome'];

	if(!$lider){
		$nome_usuario_legend ='';
	}else{
		$nome_usuario_legend = '<span style="font-size: 14px;"><strong>Líder Direto:</strong> '.$nome_usuario.'</span><br>';	
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

	echo "<div class=\"col-md-12\" style=\"padding: 0\">";
	echo "<legend style=\"text-align:center;\"><strong>Relatório de Metas por Equipe</strong><br><span style=\"font-size: 14px;\"><strong>Gerado em:</strong> $data_hora</span></legend>";	
    echo "<legend style=\"text-align:center;\">$periodo_amostra</legend>";
    echo "<legend style=\"text-align:center;\">$nome_usuario_legend</legend>";
   
		$filtro_entradas = "";
		$filtro_pausas = "";
		$filtro_erros = "";

    if($data_de){
        $filtro_entradas .= " AND b.time >= '".converteData($data_de)." 00:00:00'";
        $filtro_pausas .= " AND data_pause >= '".converteData($data_de)." 00:00:00'";   
		$filtro_erros .= " AND data_cadastrado >= '".converteData($data_de)." 00:00:00'";
	}
	if($data_ate){
		$filtro_entradas .= " AND b.time <= '".converteData($data_ate)." 23:59:59'";
		$filtro_pausas .= " AND data_pause <= '".converteData($data_ate)." 23:59:59'";
		$filtro_erros .= " AND data_cadastrado <= '".converteData($data_ate)." 23:59:59'";
	}

	if($lider){

		echo '
			<table class="table table-hover dataTable"> 
				<thead> 
					<tr> 
						<th>Nome</th>
						<th>TMA</th>
						<th>Nota média</th>
						<th>Ligações com nota</th>
						<th>Erros/Reclamações</th>
						<th>Faltas justificadas</th>
						<th>Pontualidade</th>	
                        <th>Pausa registro</th>	                        
						<th>Monitoria</th>
                        <th>Resolução <i class="fa fa-question-circle" style="cursor:help;" data-toggle="tooltip" data-placement="top" data-container="body" title="Diagnosticado + Resolvido"></i></th>
                        <th>Atendimentos por Hora <i class="fa fa-question-circle" style="cursor:help;" data-toggle="tooltip" data-placement="top" data-container="body" title="Atendimentos Faturados divididos por (Horas na empresa menos Tempo em Ativo, Manutenção/TI, Registro e Ajuda Supervisão)"></i></th>
						<th>Total de atendimentos</th>
						<th>Qtd. ajudas</th>
						<th>Meta</th>
					</tr>
				</thead> 
				<tbody>'
		;
		$data_de = converteData($data_de);
		$data_ate = converteData($data_ate);

		$metas = DBRead('', 'tb_meta', "WHERE tipo = '1' AND status = '1' AND data_ate >= '".$data_ate."' AND data_de <= '".$data_ate."' ORDER BY nota_media, porcentagem_ligacao_nota ASC");

		$dados_equipe = DBRead('','tb_usuario',"WHERE lider_direto = '$lider' AND status = 1 AND id_perfil_sistema = 3", "id_usuario");

		foreach ($dados_equipe as $equipe) {
			$data_inicial_turno = (new DateTime($data_de))->modify('first day of this month')->format('Y-m-d');
			$turno_atendente = DBRead('', 'tb_horarios_escala', "WHERE data_inicial = '".$data_inicial_turno."' AND id_usuario = '".$equipe['id_usuario']."' LIMIT 1", "carga_horaria");
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
			$img_individual = '';
			

			$data_hora = converteDataHora(getDataHora());
			$dados_usuario = DBRead('','tb_usuario a',"INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE id_usuario = '".$equipe['id_usuario']."'","a.id_asterisk, a.id_ponto, b.nome");
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
		
			if($result_ponto['data'][0][0]['data'][0]['start_date']){
			    $afastamento_usuario = 1;
			}else{
				$afastamento_usuario = 0;
			}

			if(($afastamentos == 'n') || ($afastamentos == 's' && !$afastamento_usuario)){


				$filtro_entradas_usuario = " AND b.agent = 'AGENT/$id_asterisk_usuario' AND (c.data2 >= 30 OR c.data4 >= 30)";

				$dados_entradas = DBRead('snep','queue_log a',"INNER JOIN queue_log b ON (b.callid = a.callid AND b.event = 'CONNECT') INNER JOIN queue_log c ON (c.callid = a.callid AND (c.event = 'COMPLETEAGENT' OR c.event = 'COMPLETECALLER' OR c.event = 'BLINDTRANSFER' OR c.event = 'ATTENDEDTRANSFER')) LEFT JOIN pesquisa d ON  a.callid = d.UNIQUEID WHERE a.event = 'ENTERQUEUE' $filtro_entradas $filtro_entradas_usuario GROUP BY b.id ORDER BY b.id", "a.queuename AS 'enterqueue_queuename', c.event AS 'finalizacao_event', c.data1 AS 'finalizacao_data1', c.data2 AS 'finalizacao_data2', c.data4 AS 'finalizacao_data4', d.nota AS 'nota'");

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

				$dados_erros = DBRead('','tb_erro_atendimento',"WHERE id_usuario = '".$equipe['id_usuario']."' AND status = '1' $filtro_erros","COUNT(id_erro_atendimento) AS 'qtd_erros'");
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

				if($result_ponto['data'][0][0]['data']){
					foreach ($result_ponto['data'][0][0]['data'] as $conteudo) {
						if($conteudo['missing_motive'] == 'Atestado'){
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
                
                $dados_pausas = DBRead('snep','queue_agents_pause',"WHERE codigo = '$id_asterisk_usuario' AND (tipo_pausa = 4 OR tipo_pausa = 6 OR tipo_pausa = 15 OR tipo_pausa = 9) AND data_unpause IS NOT NULL $filtro_pausas ORDER BY data_pause ASC");

                if($dados_pausas){
                    foreach ($dados_pausas as $conteudo_pausas) {
                        $data_pausa = strtotime($conteudo_pausas['data_pause']);
                        $data_retorno = strtotime($conteudo_pausas['data_unpause']);
                        $duracao_pausa = ($data_retorno - $data_pausa);
                        if($conteudo_pausas['tipo_pausa'] == '4'){
                            $duracao_total_pausa_registro += $duracao_pausa;
                        }else if($conteudo_pausas['tipo_pausa'] == '6'){
                            $duracao_total_pausa_ativo += $duracao_pausa;				
                        }else if($conteudo_pausas['tipo_pausa'] == '15'){
                            $duracao_total_pausa_manutencao_ti += $duracao_pausa;					
						}else if ($conteudo_pausas['tipo_pausa'] == '9') {
							$duracao_total_pausa_ajuda_supervisao += $duracao_pausa;					
						}    
                    }
				}
				
				$dados_monitorias = DBRead('', 'tb_monitoria_mes', "WHERE tipo_monitoria = 1 AND data_referencia = '".substr($data_de,0,7)."-01'", 'id_monitoria_mes');

				foreach ($dados_monitorias as $monitoria) {
					$dados_resultado = DBRead('','tb_monitoria_resultado', "WHERE id_usuario = '".$equipe['id_usuario']."' AND id_monitoria_mes = '".$monitoria['id_monitoria_mes']."' ");

					if ($dados_resultado) {
						$monitoria_resultado = $dados_resultado[0]['resultado'];

						break;
					}
				}
				
				$dados_atendimentos = DBRead('','tb_atendimento', "WHERE falha != '2' AND gravado = '1' AND data_inicio BETWEEN '" .$data_de. " 00:00:00' AND '" .$data_ate. " 23:59:59' AND id_usuario = '".$equipe['id_usuario']."' ", "COUNT(*) as cont");
                $total_atendimentos = $dados_atendimentos[0]['cont'];
                
				if($total_atendimentos != 0){
					$dados_cont_resolucao = DBRead('', 'tb_atendimento', "WHERE falha != '2' AND gravado = '1' AND (resolvido = '1' OR resolvido = '3') AND data_inicio BETWEEN '" .$data_de. " 00:00:00' AND '" .$data_ate. " 23:59:59' AND id_usuario = '".$equipe['id_usuario']."'", "COUNT(*) as cont");
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


				$qtd_ajuda = DBRead('','tb_solicitacao_ajuda', "WHERE data_inicio BETWEEN '" .$data_de. " 00:00:00' AND '" .$data_ate. " 23:59:59' AND atendente = '".$equipe['id_usuario']."' ", "COUNT(*) as cont");
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

						$flag_total_atendimentos = 0;

						if($meta['status_total_atendimentos'] == 2){
							$flag_total_atendimentos = 1;
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
								$flag_total_atendimentos = 1;
							}
						}		

					
						if(($meta_tma >= floor($soma_ta/($qtd_entradas == 0 ? 1 : $qtd_entradas)) || $status_meta_tma == 2) && ($meta_nota_media <= round($soma_notas/($qtd_notas == 0 ? 1 : $qtd_notas), 2) || $status_meta_nota_media == 2) && ($meta_porcentagem <= round($qtd_notas * 100 /($qtd_entradas == 0 ? 1 : $qtd_entradas), 2) || $status_meta_porcentagem == 2) && ($meta_erros >= $qtd_erros || $status_meta_erros == 2) && ($meta_faltas_justificadas >= $qtd_faltas_justificadas || $status_meta_faltas_justificadas == 2) && ($meta_absenteismo >= $absenteismo || $status_meta_absenteismo == 2) && ($meta_pausa_registro >= ($duracao_total_pausa_registro/60) || $status_meta_pausa_registro == 2) && (($meta_monitoria <= sprintf("%01.2f", $monitoria_resultado)) || $status_meta_monitoria == 2) && (($meta_resolucao <= $percentual_cont_resolucao) || $status_meta_resolucao == 2) && (($meta_atendimentos_hora <= $atendimentos_hora) || $status_meta_atendimentos_hora == 2) && (($meta_qtd_ajudas >= $qtd_ajuda) || $status_meta_qtd_ajudas == 2) && ($flag_total_atendimentos == 1) ){
							$flag_individual = $meta_nome;
						}
					}
				}
				if($flag_individual ==  'Bronze'){
					$img_individual = '<td data-order="4"><img src="inc/img/meta_bronze.png" height="25" width="18"></td>';
				}else if($flag_individual ==  'Silver'){
					$img_individual = '<td data-order="3"><img src="inc/img/meta_silver.png" height="25" width="18"></td>';
				}else if($flag_individual ==  'Gold'){
					$img_individual = '<td data-order="2"><img src="inc/img/meta_gold.png" height="25" width="18"></td>';
				}else if($flag_individual ==  'Diamond'){
					$img_individual = '<td data-order="1"><img src="inc/img/meta_diamond.png" height="25" width="18"></td>';
				}else{
					$img_individual = '<td data-order="5"></td>';
				}
			
				if($perfil_sistema == '3'){
					if($equipe['id_usuario'] == $_SESSION['id_usuario']){
							echo '
							<tr>
								<td>'.$nome_usuario.'</td>
								<td>'.gmdate("H:i:s", $soma_ta/($qtd_entradas == 0 ? 1 : $qtd_entradas)).'</td>
								<td>'.sprintf("%01.2f", round($soma_notas/($qtd_notas == 0 ? 1 : $qtd_notas), 2)).'</td>
								<td>'.sprintf("%01.2f", round($qtd_notas * 100 /($qtd_entradas == 0 ? 1 : $qtd_entradas), 2)).'%</td>
								<td>'.$qtd_erros.'</td>
								<td>'.$qtd_faltas_justificadas.'</td>
								<td style="cursor:help;" data-toggle="tooltip" data-placement="top" data-container="body" data-html="true" title="Previsto (sem intervalo): '.$horas_previstas.'<br>Ausência: '.$horas_faltantes.'">'.$absenteismo.'%</td>
                                <td>'.converteSegundosHoras($duracao_total_pausa_registro).'</td>
                                <td>'.sprintf("%01.2f", $monitoria_resultado).'%</td>
                                <td>'.$percentual_cont_resolucao.'%</td>
                                <td>'.$atendimentos_hora.'</td>
                                <td>'.$total_atendimentos.'</td>
                                <td>'.$qtd_ajuda.'</td>
								'.$img_individual.'
							</tr>
							';
					}
				}else{
					echo '
						<tr>
							<td>'.$nome_usuario.'</td>
							<td>'.gmdate("H:i:s", $soma_ta/($qtd_entradas == 0 ? 1 : $qtd_entradas)).'</td>
							<td>'.sprintf("%01.2f", round($soma_notas/($qtd_notas == 0 ? 1 : $qtd_notas), 2)).'</td>
							<td>'.sprintf("%01.2f", round($qtd_notas * 100 /($qtd_entradas == 0 ? 1 : $qtd_entradas), 2)).'%</td>
							<td>'.$qtd_erros.'</td>
							<td>'.$qtd_faltas_justificadas.'</td>
							<td style="cursor:help;" data-toggle="tooltip" data-placement="top" data-container="body"  data-html="true" title="Previsto (sem intervalo): '.$horas_previstas.'<br>Ausência: '.$horas_faltantes.'">'.$absenteismo.'%</td>
                            <td>'.converteSegundosHoras($duracao_total_pausa_registro).'</td>
                            <td>'.sprintf("%01.2f", $monitoria_resultado).'%</td>
							<td>'.$percentual_cont_resolucao.'%</td>
                            <td>'.$atendimentos_hora.'</td>
							<td>'.$total_atendimentos.'</td>
							<td>'.$qtd_ajuda.'</td>
							'.$img_individual.'
						</tr>
						';
				}
				$soma_ta_total += $soma_ta;
				$qtd_entradas_total += $qtd_entradas;
				$soma_notas_total += $soma_notas;
				$qtd_notas_total += $qtd_notas;
                $qtd_erros_total += $qtd_erros;                
                $duracao_total_pausa_registro_total += $duracao_total_pausa_registro;
                $qtd_faltas_justificadas_total += $qtd_faltas_justificadas;
                if($monitoria_resultado){
                    $qtd_monitoria++;
                    $monitoria_resultado_total += $monitoria_resultado;
				}               
				
				$resolucao_resultado_total += $cont_resolucao;
                $resolucao_quantidade_total += $total_atendimentos;
                
                $total_atendimentos_total += $total_atendimentos;
                $horas_calc_atendimentos_hora_total += $horas_calc_atendimentos_hora;
                
				$total_qtd_ajuda += $qtd_ajuda;

			}
		}
        
		echo "</tbody>";
			echo "<tfoot>";
					echo '<tr>';
						echo '<th>Equipe</th>';						
						echo '<th>'.gmdate("H:i:s", $soma_ta_total/($qtd_entradas_total == 0 ? 1 : $qtd_entradas_total)).'</th>';
						echo '<th>'.sprintf("%01.2f", round($soma_notas_total/($qtd_notas_total == 0 ? 1 : $qtd_notas_total), 2)).'</th>';			
						echo '<th>'.sprintf("%01.2f", round($qtd_notas_total * 100 /($qtd_entradas_total == 0 ? 1 : $qtd_entradas_total), 2)).'%</th>';
						echo '<th>'.$qtd_erros_total.'</th>';
						echo '<th>'.$qtd_faltas_justificadas_total.'</th>';
						echo '<th></th>';	
						echo '<th>'.converteSegundosHoras($duracao_total_pausa_registro_total).'</th>';	
						echo '<th>'.sprintf("%01.2f", $monitoria_resultado_total/($qtd_monitoria == 0 ? 1 : $qtd_monitoria)).'%</th>';	
						echo '<th>'.sprintf("%01.2f", round($resolucao_resultado_total * 100 / ($resolucao_quantidade_total == 0 ? 1 : $resolucao_quantidade_total), 2)).'%</th>';	
						echo '<th>'.sprintf("%01.2f", $total_atendimentos_total / ($horas_calc_atendimentos_hora_total == 0 ? 1 : $horas_calc_atendimentos_hora_total), 2).'</th>';	
						echo '<th>'.$total_atendimentos_total.'</th>';
						echo '<th>'.$total_qtd_ajuda.'</th>';
						echo '<th></th>';	
					echo '</tr>';
				echo "</tfoot> "; 
			echo "</table>";
		echo "<hr>";
		
		echo "
		<script>
			$(document).ready(function(){
			    var table = $('.dataTable').DataTable({
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

                var buttons = new $.fn.dataTable.Buttons(table, {
					buttons: [
						{
							extend: 'excelHtml5',
							footer: true,
							text: '<i class=\"fa fa-file-excel-o\" aria-hidden=\"true\"></i> Exportar',
							filename: 'relatorio_indicadores_mensais_call_center',
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

		$metas = DBRead('', 'tb_meta', "WHERE tipo = '2' AND status = '1' AND lider_direto = '".$lider."' AND data_ate >= '".$data_ate."' AND data_de <= '".$data_ate."' ORDER BY nota_media, porcentagem_ligacao_nota ASC");
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
                $meta_monitoria = $meta['monitoria'];
				$status_meta_monitoria = $meta['status_monitoria'];
				$meta_resolucao = $meta['resolucao'];
                $status_meta_resolucao = $meta['status_resolucao'];
                $meta_atendimentos_hora = $meta['atendimentos_hora'];
			    $status_meta_atendimentos_hora = $meta['status_atendimentos_hora'];

				$firstDate  = new DateTime($data_de);
				$secondDate = new DateTime($data_ate);
				$intvl = $firstDate->diff($secondDate);
				$intervalo = $intvl->d+1;

				$data_hoje = new DateTime('today');

				$flag_total_atendimentos = 0;

				if($meta['status_total_atendimentos'] == 2){
					$flag_total_atendimentos = 1;
				}else{
					
					if($data_hoje->format('Y-m-d') >= $secondDate->format('Y-m-d')){
						$total_atendimentos_hoje = $meta['total_atendimentos'];	
					}else{
						$total_atendimentos_hoje = round($data_hoje->format('d')*$meta['total_atendimentos']/$intervalo);	
					}
					if($total_atendimentos_hoje <= $total_atendimentos_total){
						$flag_total_atendimentos = 1;
					}
					$title_total_atendimentos_hoje = $total_atendimentos_hoje;

				}		
				
				if(($meta_tma >= floor($soma_ta_total/($qtd_entradas_total == 0 ? 1 : $qtd_entradas_total)) || $status_meta_tma == 2) && ($meta_nota_media <= round($soma_notas_total/($qtd_notas_total == 0 ? 1 : $qtd_notas_total), 2) || $status_meta_nota_media == 2) && ($meta_porcentagem <= round($qtd_notas_total * 100 /($qtd_entradas_total == 0 ? 1 : $qtd_entradas_total), 2) || $status_meta_porcentagem == 2) && ($meta_erros >= $qtd_erros_total || $status_meta_erros == 2) && (($meta_monitoria <= sprintf("%01.2f", $monitoria_resultado_total/($qtd_monitoria == 0 ? 1 : $qtd_monitoria))) || $status_meta_monitoria == 2) && (($meta_resolucao <= sprintf("%01.2f", round($resolucao_resultado_total * 100) / ($resolucao_quantidade_total == 0 ? 1 : $resolucao_quantidade_total), 2)) || $status_meta_resolucao == 2) && (($meta_atendimentos_hora <= sprintf("%01.2f", $total_atendimentos_total / ($horas_calc_atendimentos_hora_total == 0 ? 1 : $horas_calc_atendimentos_hora_total), 2)) || $status_meta_atendimentos_hora == 2) && ($flag_total_atendimentos == 1) ){
					$flag = $meta_nome;
				}
			}
		}
		if($metas){
			if(!$flag){
				echo '<div style="text-align: center">
						<strong>Sua equipe ainda pode alcançar uma meta! Avançar agora! :)</strong>
					</div>';
			}else{
				echo '<div style="text-align: center">
						<strong>Com o resultado atual, está atingindo a meta '.$flag.'!</strong>
					</div>';
					if($flag ==  'Bronze'){
						echo '<div style="text-align: center">
								<img src="inc/img/meta_bronze.png" height="200" width="149">
							</div>';
					}else if($flag ==  'Silver'){
						echo '<div style="text-align: center">
								<img src="inc/img/meta_silver.png" height="200" width="149">
							</div>';
					}else if($flag ==  'Gold'){
						echo '<div style="text-align: center">
								<img src="inc/img/meta_gold.png" height="200" width="149">
							</div>';
					}else if($flag ==  'Diamond'){
						echo '<div style="text-align: center">
								<img src="inc/img/meta_diamond.png" height="200" width="149">
							</div>';
					}			
			}
			echo "<hr>";

			echo '
				<table class="table table-hover"> 
					<thead> 
						<tr> 
							<th>Meta</th>
	    					<th>TMA</th>
	    					<th>Nota média</th>
	    					<th>Ligações com nota</th>
	    					<th>Erros/Reclamações</th>
                            <th>Monitoria</th>
                            <th>Resolução <i class="fa fa-question-circle" style="cursor:help;" data-toggle="tooltip" data-placement="top" data-container="body" title="Diagnosticado + Resolvido"></i></th>
                            <th>Atendimentos por Hora <i class="fa fa-question-circle" style="cursor:help;" data-toggle="tooltip" data-placement="top" data-container="body" title="Atendimentos Faturados divididos por (Horas na empresa menos Tempo em Ativo, Manutenção/TI, Registro e Ajuda Supervisão)"></i></th>
                            <th>Total de Atendimentos</th>
						</tr>
					</thead> 
					<tbody>'
			;

			$metas = array_reverse($metas);

			foreach ($metas as $meta) {
				if($meta['nome'] ==  'Bronze'){
					$img = '<img src="inc/img/meta_bronze.png" height="25" width="18">';
				}else if($meta['nome'] ==  'Silver'){
					$img = '<img src="inc/img/meta_silver.png" height="25" width="18">';
				}else if($meta['nome'] ==  'Gold'){
					$img = '<img src="inc/img/meta_gold.png" height="25" width="18">';
				}else if($meta['nome'] ==  'Diamond'){
					$img = '<img src="inc/img/meta_diamond.png" height="25" width="18">';
				}	
				echo "<tr>";
				echo "<td>$img ".$meta['nome']."</td>";
				if($meta['status_tempo_medio_atendimento'] == 1){
					echo "<td>".gmdate("H:i:s", $meta['tempo_medio_atendimento'])."</td>";
				}else{
					echo '<td><i class="fa fa-minus" data-toggle="tooltip" data-placement="top" data-container="body" title="Não exigido"></i></td>';
				}
				if($meta['status_nota_media'] == 1){
					echo "<td>".$meta['nota_media']."</td>";
				}else{
					echo '<td><i class="fa fa-minus" data-toggle="tooltip" data-placement="top" data-container="body" title="Não exigido"></i></td>';
				}
				if($meta['status_porcentagem_ligacao_nota'] == 1){
					echo "<td>".$meta['porcentagem_ligacao_nota']."%</td>";
				}else{
					echo '<td><i class="fa fa-minus" data-toggle="tooltip" data-placement="top" data-container="body" title="Não exigido"></i></td>';
				}
				if($meta['status_erros_reclamacoes'] == 1){
					echo "<td>".$meta['erros_reclamacoes']."</td>";
				}else{
					echo '<td><i class="fa fa-minus" data-toggle="tooltip" data-placement="top" data-container="body" title="Não exigido"></i></td>';
				}                
                if($meta['status_monitoria'] == 1){
                    echo "<td>".sprintf("%01.2f", $meta['monitoria'])."%</td>";
                }else{
                    echo '<td><i class="fa fa-minus" data-toggle="tooltip" data-placement="top" data-container="body" title="Não exigido"></i></td>';
				}
				
				if($meta['status_resolucao'] == 1){
                    echo "<td>".$meta['resolucao']."%</td>";
                }else{
                    echo '<td><i class="fa fa-minus" data-toggle="tooltip" data-placement="top" data-container="body" title="Não exigido"></i></td>';
                }
                
                if($meta['status_atendimentos_hora'] == 1){
                    echo "<td>".$meta['atendimentos_hora']."</td>";
                }else{
                    echo '<td><i class="fa fa-minus" data-toggle="tooltip" data-placement="top" data-container="body" title="Não exigido"></i></td>';
                }

				if($meta['status_total_atendimentos'] == 1){
                    echo '<td>'.$meta['total_atendimentos'].' <i class="fa fa-question-circle" style="cursor:help;" data-toggle="tooltip" data-placement="top" data-container="body" title="'.$title_total_atendimentos_hoje.' atendimentos até hoje!"></i></td>';
                }else{
                    echo '<td><i class="fa fa-minus" data-toggle="tooltip" data-placement="top" data-container="body" title="Não exigido"></i></td>';
                }
				
				echo "</tr>";
			}
			echo "
					</tbody> 
				</table>
			";
		}else{
			echo '<div style="text-align: center"><strong>Não foram encontradas metas para esse período!</strong></div>';
		}
		
	}else{
		echo '<div style="text-align: center"><strong>Não existe equipe para este usuário!</strong></div>';
	}
    echo "</div>";

}

function relatorio_meta_geral($data_de, $data_ate, $perfil_sistema, $afastamentos){
	
	$fila = "'callATENDIMENTOvip','callATENDIMENTOalta','callATENDIMENTOnormal','callATENDIMENTObaixa','callATENDIMENTOnotificacaoparada', 'callATENDIMENTOvipEXT','callATENDIMENTOaltaEXT','callATENDIMENTOnormalEXT','callATENDIMENTObaixaEXT','callATENDIMENTOnotificacaoparadaEXT','callATENDIMENTOvipEXP','callATENDIMENTOaltaEXP','callATENDIMENTOnormalEXP','callATENDIMENTObaixaEXP','callATENDIMENTOnotificacaoparadaEXP'";

	$qtd_entradas_total = 0;
	$soma_notas_total = 0;
	$qtd_notas_total = 0;
	$soma_ta_total = 0;
	$qtd_erros_total = 0;				
    $duracao_total_pausa_registro_total = 0;
    $qtd_faltas_justificadas_total = 0;
    $qtd_monitoria = 0;
    $monitoria_resultado_total = 0;

	$resolucao_resultado_total = 0;
    $resolucao_quantidade_total = 0;
    
    $total_atendimentos_total = 0;
    $horas_calc_atendimentos_hora_total = 0;

    $total_qtd_ajuda = 0;

	$data_hora = converteDataHora(getDataHora());

	if($data_de && $data_ate){
	    $periodo_amostra ="<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> De $data_de até $data_ate</span>";
	}elseif($data_de){
	    $periodo_amostra ="<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> A partir de $data_de</span>";
	}elseif($data_ate){
	    $periodo_amostra ="<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> Até $data_ate</span>";
	}else{
	    $periodo_amostra = "";
	}
	echo "<div class=\"col-md-12\" style=\"padding: 0\">";
	echo "<legend style=\"text-align:center;\"><strong>Relatório de Metas Gerais</strong><br><span style=\"font-size: 14px;\"><strong>Gerado em:</strong> $data_hora</span></legend>";	
    echo "<legend style=\"text-align:center;\">$periodo_amostra</legend>";

	$filtro_entradas = '';
	$filtro_pausas = '';
	$filtro_pausas_retorno = '';
	$filtro_erros = '';

    if($data_de){
		$filtro_entradas .= " AND b.time >= '".converteData($data_de)." 00:00:00'";
		$filtro_pausas .= " AND data_pause >= '".converteData($data_de)." 00:00:00'";    
		$filtro_erros .= " AND data_cadastrado >= '".converteData($data_de)." 00:00:00'";
	}
	if($data_ate){
		$filtro_entradas .= " AND b.time <= '".converteData($data_ate)." 23:59:59'";
		$filtro_pausas .= " AND data_pause <= '".converteData($data_ate)." 23:59:59'";
		$filtro_erros .= " AND data_cadastrado <= '".converteData($data_ate)." 23:59:59'";
	}

	echo '
		<table class="table table-hover dataTable"> 
			<thead> 
				<tr> 
					<th>Nome</th>
					<th>TMA</th>
					<th>Nota média</th>
					<th>Ligações com nota</th>
					<th>Erros/Reclamações</th>
					<th>Faltas justificadas</th>
					<th>Pontualidade</th>	
                    <th>Pausa registro</th>		                        
					<th>Monitoria</th>
                    <th>Resolução <i class="fa fa-question-circle" style="cursor:help;" data-toggle="tooltip" data-placement="top" data-container="body" title="Diagnosticado + Resolvido"></i></th>
                    <th>Atendimentos por Hora <i class="fa fa-question-circle" style="cursor:help;" data-toggle="tooltip" data-placement="top" data-container="body" title="Atendimentos Faturados divididos por (Horas na empresa menos Tempo em Ativo, Manutenção/TI, Registro e Ajuda Supervisão)"></i></th>
					<th>Total de atendimentos</th>
					<th>Qtd. ajudas</th>
					<th>Meta</th>
				</tr>
			</thead> 
			<tbody>'
	;

	$data_de = converteData($data_de);
	$data_ate = converteData($data_ate);

	$metas = DBRead('', 'tb_meta', "WHERE tipo = '1' AND status = '1' AND data_ate >= '".$data_ate."' AND data_de <= '".$data_ate."' ORDER BY nota_media, porcentagem_ligacao_nota ASC");

	$dados_equipe = DBRead('','tb_usuario a'," INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.lider_direto AND a.status = 1 AND a.id_perfil_sistema = 3 ORDER BY b.nome");

	foreach ($dados_equipe as $equipe) {
		
		$data_inicial_turno = (new DateTime($data_de))->modify('first day of this month')->format('Y-m-d');
		$turno_atendente = DBRead('', 'tb_horarios_escala', "WHERE data_inicial = '".$data_inicial_turno."' AND id_usuario = '".$equipe['id_usuario']."' LIMIT 1", "carga_horaria");
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
		$duracao_total_pausa_ajuda_supervisao = 0;;
        $monitoria_resultado = 0;
		$flag_individual = '';
		$img_individual = '';

		$data_hora = converteDataHora(getDataHora());
		$dados_usuario = DBRead('','tb_usuario a',"INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE id_usuario = '".$equipe['id_usuario']."'","a.*, b.nome");
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
	
		if($result_ponto['data'][0][0]['data'][0]['start_date']){
			$afastamento_usuario = 1;
		}else{
			$afastamento_usuario = 0;
		}

		if(($afastamentos == 'n') || ($afastamentos == 's' && !$afastamento_usuario)){

			$filtro_entradas_usuario = " AND b.agent = 'AGENT/$id_asterisk_usuario' AND (c.data2 >= 30 OR c.data4 >= 30)";

			$dados_entradas = DBRead('snep','queue_log a',"INNER JOIN queue_log b ON (b.callid = a.callid AND b.event = 'CONNECT') INNER JOIN queue_log c ON (c.callid = a.callid AND (c.event = 'COMPLETEAGENT' OR c.event = 'COMPLETECALLER' OR c.event = 'BLINDTRANSFER' OR c.event = 'ATTENDEDTRANSFER')) LEFT JOIN pesquisa d ON  a.callid = d.UNIQUEID WHERE a.event = 'ENTERQUEUE' $filtro_entradas $filtro_entradas_usuario GROUP BY b.id ORDER BY b.id", "a.queuename AS 'enterqueue_queuename', c.event AS 'finalizacao_event', c.data1 AS 'finalizacao_data1', c.data2 AS 'finalizacao_data2', c.data4 AS 'finalizacao_data4', d.nota AS 'nota'");

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

			$dados_erros = DBRead('','tb_erro_atendimento',"WHERE id_usuario = '".$equipe['id_usuario']."' AND status = '1' $filtro_erros","COUNT(*) AS 'qtd_erros'");
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

			if($result_ponto['data'][0][0]['data']){
				foreach ($result_ponto['data'][0][0]['data'] as $conteudo) {
					if($conteudo['missing_motive'] == 'Atestado'){
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

            $dados_pausas = DBRead('snep','queue_agents_pause',"WHERE codigo = '$id_asterisk_usuario' AND (tipo_pausa = 4 OR tipo_pausa = 6 OR tipo_pausa = 15 OR tipo_pausa = 9) AND data_unpause IS NOT NULL $filtro_pausas ORDER BY data_pause ASC");

            if($dados_pausas){
                foreach ($dados_pausas as $conteudo_pausas) {
                    $data_pausa = strtotime($conteudo_pausas['data_pause']);
                    $data_retorno = strtotime($conteudo_pausas['data_unpause']);
                    $duracao_pausa = ($data_retorno - $data_pausa);
                    if($conteudo_pausas['tipo_pausa'] == '4'){
                        $duracao_total_pausa_registro += $duracao_pausa;
                    }else if($conteudo_pausas['tipo_pausa'] == '6'){
                        $duracao_total_pausa_ativo += $duracao_pausa;				
                    }else if($conteudo_pausas['tipo_pausa'] == '15'){
                        $duracao_total_pausa_manutencao_ti += $duracao_pausa;					
                    }else if ($conteudo_pausas['tipo_pausa'] == '9') {
						$duracao_total_pausa_ajuda_supervisao += $duracao_pausa;					
					}   
                }
            }
			
			$dados_monitorias = DBRead('', 'tb_monitoria_mes', "WHERE tipo_monitoria = 1 AND data_referencia = '".substr($data_de,0,7)."-01'", 'id_monitoria_mes');

			foreach ($dados_monitorias as $monitoria) {
				$dados_resultado = DBRead('','tb_monitoria_resultado', "WHERE id_usuario = '".$equipe['id_usuario']."' AND id_monitoria_mes = '".$monitoria['id_monitoria_mes']."' ");

				if ($dados_resultado) {
					$monitoria_resultado = $dados_resultado[0]['resultado'];
					break;
				}
			}
			
			$dados_atendimentos = DBRead('','tb_atendimento', "WHERE falha != '2' AND gravado = '1' AND data_inicio BETWEEN '" .$data_de. " 00:00:00' AND '" .$data_ate. " 23:59:59' AND id_usuario = '".$equipe['id_usuario']."' ", "COUNT(*) as cont");			
            $total_atendimentos = $dados_atendimentos[0]['cont'];
            
			if($total_atendimentos != 0){
				$dados_cont_resolucao = DBRead('', 'tb_atendimento', "WHERE falha != '2' AND gravado = '1' AND (resolvido = '1' OR resolvido = '3') AND data_inicio BETWEEN '" .$data_de. " 00:00:00' AND '" .$data_ate. " 23:59:59' AND id_usuario = '".$equipe['id_usuario']."'", "COUNT(*) as cont");

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
			
			$qtd_ajuda = DBRead('','tb_solicitacao_ajuda', "WHERE data_inicio BETWEEN '" .$data_de. " 00:00:00' AND '" .$data_ate. " 23:59:59' AND atendente = '".$equipe['id_usuario']."' ", "COUNT(*) as cont");
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
					
					if(($meta_tma >= floor($soma_ta/($qtd_entradas == 0 ? 1 : $qtd_entradas)) || $status_meta_tma == 2) && ($meta_nota_media <= round($soma_notas/($qtd_notas == 0 ? 1 : $qtd_notas), 2) || $status_meta_nota_media == 2) && ($meta_porcentagem <= round($qtd_notas * 100 /($qtd_entradas == 0 ? 1 : $qtd_entradas), 2) || $status_meta_porcentagem == 2) && ($meta_erros >= $qtd_erros || $status_meta_erros == 2) && ($meta_faltas_justificadas >= $qtd_faltas_justificadas || $status_meta_faltas_justificadas == 2) && ($meta_absenteismo >= $absenteismo || $status_meta_absenteismo == 2) && ($meta_pausa_registro >= ($duracao_total_pausa_registro/60) || $status_meta_pausa_registro == 2) && (($meta_monitoria <= sprintf("%01.2f", $monitoria_resultado)) || $status_meta_monitoria == 2) && (($meta_resolucao <= $percentual_cont_resolucao) || $status_meta_resolucao == 2) && (($meta_atendimentos_hora <= $atendimentos_hora)  || $status_meta_atendimentos_hora == 2) && (($meta_qtd_ajudas >= $qtd_ajuda) || $status_meta_qtd_ajudas == 2) && ($flag_tempo_atendimento == 1)){
						$flag_individual = $meta_nome;
					}
				}
            }
            
            if($flag_individual ==  'Bronze'){
                $img_individual = '<td data-order="4"><img src="inc/img/meta_bronze.png" height="25" width="18"></td>';
            }else if($flag_individual ==  'Silver'){
                $img_individual = '<td data-order="3"><img src="inc/img/meta_silver.png" height="25" width="18"></td>';
            }else if($flag_individual ==  'Gold'){
                $img_individual = '<td data-order="2"><img src="inc/img/meta_gold.png" height="25" width="18"></td>';
            }else if($flag_individual ==  'Diamond'){
                $img_individual = '<td data-order="1"><img src="inc/img/meta_diamond.png" height="25" width="18"></td>';
            }else{
                $img_individual = '<td data-order="5"></td>';
            }
            
			if($perfil_sistema == '3'){
				if($equipe['id_usuario'] == $_SESSION['id_usuario']){
					echo '
						<tr>
							<td>'.$nome_usuario.'</td>
							<td>'.gmdate("H:i:s", $soma_ta/($qtd_entradas == 0 ? 1 : $qtd_entradas)).'</td>
							<td>'.sprintf("%01.2f", round($soma_notas/($qtd_notas == 0 ? 1 : $qtd_notas), 2)).'</td>
							<td>'.sprintf("%01.2f", round($qtd_notas * 100 /($qtd_entradas == 0 ? 1 : $qtd_entradas), 2)).'%</td>
							<td>'.$qtd_erros.'</td>
							<td>'.$qtd_faltas_justificadas.'</td>
							<td style="cursor:help;" data-toggle="tooltip" data-placement="top" data-container="body" data-html="true" title="Previsto (sem intervalo): '.$horas_previstas.'<br>Ausência: '.$horas_faltantes.'">'.$absenteismo.'%</td>
                            <td>'.converteSegundosHoras($duracao_total_pausa_registro).'</td>
                            <td>'.sprintf("%01.2f", $monitoria_resultado).'%</td>
							<td>'.$percentual_cont_resolucao.'%</td>
                            <td>'.$atendimentos_hora.'</td>
							<td>'.$total_atendimentos.'</td>
        					<td>'.$qtd_ajuda.'</td>
							'.$img_individual.'
						</tr>
						';
				}
			}else{
				echo '
					<tr>
						<td>'.$nome_usuario.'</td>
						<td>'.gmdate("H:i:s", $soma_ta/($qtd_entradas == 0 ? 1 : $qtd_entradas)).'</td>
						<td>'.sprintf("%01.2f", round($soma_notas/($qtd_notas == 0 ? 1 : $qtd_notas), 2)).'</td>
						<td>'.sprintf("%01.2f", round($qtd_notas * 100 /($qtd_entradas == 0 ? 1 : $qtd_entradas), 2)).'%</td>
						<td>'.$qtd_erros.'</td>
						<td>'.$qtd_faltas_justificadas.'</td>
						<td style="cursor:help;" data-toggle="tooltip" data-placement="top" data-container="body" data-html="true" title="Previsto (sem intervalo): '.$horas_previstas.'<br>Ausência: '.$horas_faltantes.'">'.$absenteismo.'%</td>
                        <td>'.converteSegundosHoras($duracao_total_pausa_registro).'</td>
                        <td>'.sprintf("%01.2f", $monitoria_resultado).'%</td>
						<td>'.$percentual_cont_resolucao.'%</td>
                        <td>'.$atendimentos_hora.'</td>
						<td>'.$total_atendimentos.'</td>
        				<td>'.$qtd_ajuda.'</td>
						'.$img_individual.'
					</tr>
					';
			}

			$soma_ta_total += $soma_ta;
			$qtd_entradas_total += $qtd_entradas;
			$soma_notas_total += $soma_notas;
			$qtd_notas_total += $qtd_notas;
			$qtd_erros_total += $qtd_erros;            
            $duracao_total_pausa_registro_total += $duracao_total_pausa_registro;
			$qtd_faltas_justificadas_total += $qtd_faltas_justificadas;
            if($monitoria_resultado){
                $qtd_monitoria++;
                $monitoria_resultado_total += $monitoria_resultado;
			}  
			
			$resolucao_resultado_total += $cont_resolucao;
            $resolucao_quantidade_total += $total_atendimentos;
            
            $total_atendimentos_total += $total_atendimentos;
            $horas_calc_atendimentos_hora_total += $horas_calc_atendimentos_hora;

			$total_qtd_ajuda += $qtd_ajuda;


		}
	}

	echo "</tbody>";
			echo "<tfoot>";
				echo '<tr>';
					echo '<th>Média Total Geral</th>';
					echo '<th>'.gmdate("H:i:s", $soma_ta_total/($qtd_entradas_total == 0 ? 1 : $qtd_entradas_total)).'</th>';
					echo '<th>'.sprintf("%01.2f", round($soma_notas_total/($qtd_notas_total == 0 ? 1 : $qtd_notas_total), 2)).'</th>';			
					echo '<th>'.sprintf("%01.2f", round($qtd_notas_total * 100 /($qtd_entradas_total == 0 ? 1 : $qtd_entradas_total), 2)).'%</th>';			
					echo '<th>'.$qtd_erros_total.'</th>';
                    echo '<th>'.$qtd_faltas_justificadas_total.'</th>';
					echo '<th></th>';
                    echo '<th>'.converteSegundosHoras($duracao_total_pausa_registro_total).'</th>';	
                    echo '<th>'.sprintf("%01.2f", $monitoria_resultado_total/($qtd_monitoria == 0 ? 1 : $qtd_monitoria)).'%</th>';	
                    echo '<th>'.sprintf("%01.2f", round($resolucao_resultado_total * 100 / ($resolucao_quantidade_total == 0 ? 1 : $resolucao_quantidade_total), 2)).'%</th>';	
					echo '<th>'.sprintf("%01.2f", $total_atendimentos_total / ($horas_calc_atendimentos_hora_total == 0 ? 1 : $horas_calc_atendimentos_hora_total), 2).'</th>';
					echo '<th>'.$total_atendimentos_total.'</th>';
					echo '<th>'.$total_qtd_ajuda.'</th>';
	

					echo '<th></th>';		
				echo '</tr>';
			echo "</tfoot> "; 
		echo "</table>";
	echo "<hr>";

	echo "
		<script>
			$(document).ready(function(){
			    var table = $('.dataTable').DataTable({
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
                
                var buttons = new $.fn.dataTable.Buttons(table, {
					buttons: [
						{
							extend: 'excelHtml5',
							footer: true,
							text: '<i class=\"fa fa-file-excel-o\" aria-hidden=\"true\"></i> Exportar',
							filename: 'relatorio_indicadores_mensais_call_center',
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

	$metas = DBRead('', 'tb_meta', "WHERE tipo = '3' AND status = '1' AND data_ate >= '".$data_ate."' AND data_de <= '".$data_ate."' ORDER BY nota_media, porcentagem_ligacao_nota ASC");
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
            $meta_monitoria = $meta['monitoria'];
			$status_meta_monitoria = $meta['status_monitoria'];
			$meta_resolucao = $meta['resolucao'];
            $status_meta_resolucao = $meta['status_resolucao'];
            $meta_atendimentos_hora = $meta['atendimentos_hora'];
            $status_meta_atendimentos_hora = $meta['status_atendimentos_hora'];

			$firstDate  = new DateTime($data_de);
			$secondDate = new DateTime($data_ate);
			$intvl = $firstDate->diff($secondDate);
			$intervalo = $intvl->d+1;

			$data_hoje = new DateTime('today');

			$flag_total_atendimentos = 0;

			if($meta['status_total_atendimentos'] == 2){
				$flag_total_atendimentos = 1;
			}else{
				
				if($data_hoje->format('Y-m-d') >= $secondDate->format('Y-m-d')){
					$total_atendimentos_hoje = $meta['total_atendimentos'];	
				}else{
					$total_atendimentos_hoje = round($data_hoje->format('d')*$meta['total_atendimentos']/$intervalo);	
				}
				if($total_atendimentos_hoje <= $total_atendimentos_total){
					$flag_total_atendimentos = 1;
				}
				$title_total_atendimentos_hoje = $total_atendimentos_hoje;

			}		
            
            if(($meta_tma >= floor($soma_ta_total/($qtd_entradas_total == 0 ? 1 : $qtd_entradas_total)) || $status_meta_tma == 2) && ($meta_nota_media <= round($soma_notas_total/($qtd_notas_total == 0 ? 1 : $qtd_notas_total), 2) || $status_meta_nota_media == 2) && ($meta_porcentagem <= round($qtd_notas_total * 100 /($qtd_entradas_total == 0 ? 1 : $qtd_entradas_total), 2) || $status_meta_porcentagem == 2) && ($meta_erros >= $qtd_erros_total || $status_meta_erros == 2) && (($meta_monitoria <= sprintf("%01.2f", $monitoria_resultado_total/($qtd_monitoria == 0 ? 1 : $qtd_monitoria))) || $status_meta_monitoria == 2) && (($meta_resolucao <= sprintf("%01.2f", round($resolucao_resultado_total * 100) / ($resolucao_quantidade_total == 0 ? 1 : $resolucao_quantidade_total), 2)) || $status_meta_resolucao == 2) && (($meta_atendimentos_hora <= sprintf("%01.2f", $total_atendimentos_total / ($horas_calc_atendimentos_hora_total == 0 ? 1 : $horas_calc_atendimentos_hora_total), 2)) || $status_meta_atendimentos_hora == 2) && ($flag_total_atendimentos == 1) ){
                $flag = $meta_nome;
            }
		}
	}
	if($metas){
		if(!$flag){
			echo '<div style="text-align: center">
					<strong>A equipe inteira ainda pode alcançar uma meta! Avançar agora! :)</strong>
				</div>';
		}else{
			echo '<div style="text-align: center">
					<strong>Com o resultado atual, está atingindo a meta '.$flag.'!</strong>
				</div>';
				if($flag ==  'Bronze'){
					echo '<div style="text-align: center">
							<img src="inc/img/meta_bronze.png" height="200" width="149">
						</div>';
				}else if($flag ==  'Silver'){
					echo '<div style="text-align: center">
							<img src="inc/img/meta_silver.png" height="200" width="149">
						</div>';
				}else if($flag ==  'Gold'){
					echo '<div style="text-align: center">
							<img src="inc/img/meta_gold.png" height="200" width="149">
						</div>';
				}else if($flag ==  'Diamond'){
					echo '<div style="text-align: center">
							<img src="inc/img/meta_diamond.png" height="200" width="149">
						</div>';
				}			
		}
		echo "<hr>";
		echo '<div>';

		echo '
			<table class="table table-hover"> 
				<thead> 
					<tr> 
						<th>Meta</th>
    					<th>TMA</th>
    					<th>Nota média</th>
    					<th>Ligações com nota</th>
    					<th>Erros/Reclamações</th>
                        <th>Monitoria</th>
                        <th>Resolução <i class="fa fa-question-circle" style="cursor:help;" data-toggle="tooltip" data-placement="top" data-container="body" title="Diagnosticado + Resolvido"></i></th>
                        <th>Atendimentos por Hora <i class="fa fa-question-circle" style="cursor:help;" data-toggle="tooltip" data-placement="top" data-container="body" title="Atendimentos Faturados divididos por (Horas na empresa menos Tempo em Ativo, Manutenção/TI, Registro e Ajuda Supervisão)"></i></th>
						<th>Total de Atendimentos</th>

					</tr>
				</thead> 
				<tbody>'
		;
		
		$metas = array_reverse($metas);

		foreach ($metas as $meta) {
			if($meta['nome'] ==  'Bronze'){
                $img = '<img src="inc/img/meta_bronze.png" height="25" width="18">';
            }else if($meta['nome'] ==  'Silver'){
                $img = '<img src="inc/img/meta_silver.png" height="25" width="18">';
            }else if($meta['nome'] ==  'Gold'){
                $img = '<img src="inc/img/meta_gold.png" height="25" width="18">';
            }else if($meta['nome'] ==  'Diamond'){
                $img = '<img src="inc/img/meta_diamond.png" height="25" width="18">';
            }	
            echo "<tr>";
            echo "<td>$img ".$meta['nome']."</td>";
            if($meta['status_tempo_medio_atendimento'] == 1){
                echo "<td>".gmdate("H:i:s", $meta['tempo_medio_atendimento'])."</td>";
            }else{
                echo '<td><i class="fa fa-minus" data-toggle="tooltip" data-placement="top" data-container="body" title="Não exigido"></i></td>';
            }
            if($meta['status_nota_media'] == 1){
                echo "<td>".$meta['nota_media']."</td>";
            }else{
                echo '<td><i class="fa fa-minus" data-toggle="tooltip" data-placement="top" data-container="body" title="Não exigido"></i></td>';
            }
            if($meta['status_porcentagem_ligacao_nota'] == 1){
                echo "<td>".$meta['porcentagem_ligacao_nota']."%</td>";
            }else{
                echo '<td><i class="fa fa-minus" data-toggle="tooltip" data-placement="top" data-container="body" title="Não exigido"></i></td>';
            }
            if($meta['status_erros_reclamacoes'] == 1){
                echo "<td>".$meta['erros_reclamacoes']."</td>";
            }else{
                echo '<td><i class="fa fa-minus" data-toggle="tooltip" data-placement="top" data-container="body" title="Não exigido"></i></td>';
            }                
            if($meta['status_monitoria'] == 1){
                echo "<td>".sprintf("%01.2f", $meta['monitoria'])."%</td>";
            }else{
                echo '<td><i class="fa fa-minus" data-toggle="tooltip" data-placement="top" data-container="body" title="Não exigido"></i></td>';
			}
			
			if($meta['status_resolucao'] == 1){
                echo "<td>".$meta['resolucao']."%</td>";
            }else{
                echo '<td><i class="fa fa-minus" data-toggle="tooltip" data-placement="top" data-container="body" title="Não exigido"></i></td>';
            }
            
            if($meta['status_atendimentos_hora'] == 1){
				echo "<td>".$meta['atendimentos_hora']."</td>";
			}else{
				echo '<td><i class="fa fa-minus" data-toggle="tooltip" data-placement="top" data-container="body" title="Não exigido"></i></td>';
			}

			if($meta['status_total_atendimentos'] == 1){
				echo '<td>'.$meta['total_atendimentos'].' <i class="fa fa-question-circle" style="cursor:help;" data-toggle="tooltip" data-placement="top" data-container="body" title="'.$title_total_atendimentos_hoje.' atendimentos até hoje!"></i></td>';
			}else{
				echo '<td><i class="fa fa-minus" data-toggle="tooltip" data-placement="top" data-container="body" title="Não exigido"></i></td>';
			}
			
            echo "</tr>";
		}
		echo "
				</tbody> 
			</table>
		";
		echo "</div>";

	}else{
		echo '<div style="text-align: center"><strong>Não foram encontradas metas para esse período!</strong></div>';
	}
	
    echo "</div>";
}

function relatorio_meta_solidaria($data_de, $data_ate, $dobra_qtd_integral, $dobra_qtd_outros){

	$fila = "'callATENDIMENTOvip','callATENDIMENTOalta','callATENDIMENTOnormal','callATENDIMENTObaixa','callATENDIMENTOnotificacaoparada', 'callATENDIMENTOvipEXT','callATENDIMENTOaltaEXT','callATENDIMENTOnormalEXT','callATENDIMENTObaixaEXT','callATENDIMENTOnotificacaoparadaEXT','callATENDIMENTOvipEXP','callATENDIMENTOaltaEXP','callATENDIMENTOnormalEXP','callATENDIMENTObaixaEXP','callATENDIMENTOnotificacaoparadaEXP'";

    $data_hora = converteDataHora(getDataHora());    

    $data_inicial = (new DateTime(converteData($data_de)))->modify('first day of this month')->format('Y-m-d');

    $dobra_qtd_integral_legend = '<span style="font-size: 14px;"><strong>QTD Dobra Doação Turno Integral:</strong> Acima de '.$dobra_qtd_integral.' atendimento(s)</span><br>';	
    $dobra_qtd_outros_legend = '<span style="font-size: 14px;"><strong>QTD Dobra Outros Turnos:</strong> Acima de '.$dobra_qtd_outros.' atendimento(s)</span><br>';	

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
	echo "<legend style=\"text-align:center;\"><strong>Relatório de Meta Solidária</strong><br><span style=\"font-size: 14px;\"><strong>Gerado em:</strong> $data_hora</span></legend>";	
    echo "<legend style=\"text-align:center;\">$periodo_amostra</legend>";        
    echo "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong>Fórmula:</strong> Quantidade de atendimentos faturados x NMA / 500 = Doação</span><br></legend>";
    echo "<legend style=\"text-align:center;\">$dobra_qtd_integral_legend</legend>";
    echo "<legend style=\"text-align:center;\">$dobra_qtd_outros_legend</legend>";

    $dados_atendimentos = DBRead('','tb_atendimento a',"INNER JOIN tb_usuario b ON a.id_usuario = b.id_usuario INNER JOIN tb_pessoa c ON b.id_pessoa = c.id_pessoa WHERE a.gravado = 1 AND a.falha != 2 AND a.data_inicio BETWEEN '".converteData($data_de)." 00:00:00' AND '".converteData($data_ate)." 23:59:59'", "a.id_usuario, b.id_asterisk, c.nome");

    $array_atendentes_atendimento = array();

    if($dados_atendimentos){
        foreach($dados_atendimentos as $conteudo_atendimento){
            $array_atendentes_atendimento[$conteudo_atendimento['id_usuario']]['nome'] = $conteudo_atendimento['nome'];  
            $array_atendentes_atendimento[$conteudo_atendimento['id_usuario']]['id_asterisk'] = $conteudo_atendimento['id_asterisk'];  
            $array_atendentes_atendimento[$conteudo_atendimento['id_usuario']]['qtd'] += 1;  
        }
    
        echo '
            <table class="table table-hover dataTable" style="margin-bottom:0;">
                <thead>
                    <tr>
                        <th>Atendente</th>
                        <th>Atendimentos Faturados</th>
                        <th>NMA</th>			
                        <th>Turno</th>			
                        <th>Doação</th>		
                        <th>Dobrou</th>		
                    </tr>
                </thead>
                <tbody>
        ';

        foreach($array_atendentes_atendimento as $id_usuario => $conteudo_atendente_faturamento){

            $dados_escala = DBRead('','tb_horarios_escala', "WHERE id_usuario = $id_usuario AND data_inicial = '$data_inicial'");

            $qtd_entradas = 0;
            $qtd_notas = 0;
            $soma_ta = 0;
            $soma_notas = 0;

            $filtro_entradas_usuario = " AND b.time >= '".converteData($data_de)." 00:00:00' AND b.time <= '".converteData($data_ate)." 23:59:59' AND b.agent = 'AGENT/".$conteudo_atendente_faturamento['id_asterisk']."' AND (c.data2 >= 30 OR c.data4 >= 30)";

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

            if($dados_escala[0]['carga_horaria'] == 'integral'){
                $turno = 'Integral';
            }elseif($dados_escala[0]['carga_horaria'] == 'meio'){
                $turno = 'Meio Turno';
            }elseif($dados_escala[0]['carga_horaria'] == 'estagio'){
                $turno = 'Estágio';
            }elseif($dados_escala[0]['carga_horaria'] == 'jovem'){
                $turno = 'Jovem Aprendiz';
            }else{
                $turno = 'N/D';
            }
            
            $nma = sprintf("%01.2f", round($soma_notas/($qtd_notas == 0 ? 1 : $qtd_notas), 2));

            $doacao = round($conteudo_atendente_faturamento['qtd']*$nma/500);
            $dobrou = 'Não';

            if($dados_escala[0]['carga_horaria'] == 'integral'){
                if($conteudo_atendente_faturamento['qtd'] > $dobra_qtd_integral){
                    $doacao = round(($conteudo_atendente_faturamento['qtd']*$nma/500)*2);
                    $dobrou = 'Sim';
                }
            }else{
                if($conteudo_atendente_faturamento['qtd'] > $dobra_qtd_outros){
                    $doacao = round(($conteudo_atendente_faturamento['qtd']*$nma/500)*2);
                    $dobrou = 'Sim';
                }
            }

            echo '
                    <tr>
                        <td>'.$conteudo_atendente_faturamento['nome'].'</td>
                        <td>'.$conteudo_atendente_faturamento['qtd'].'</td>
                        <td>'.$nma.'</td>
                        <td>'.$turno.'</td>
                        <td>'.$doacao.'</td>
                        <td>'.$dobrou.'</td>
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

}
?>