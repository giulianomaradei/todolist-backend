<?php
require_once(__DIR__."/../class/System.php");
$tipo_relatorio = (!empty($_POST['tipo_relatorio'])) ? $_POST['tipo_relatorio'] : 1;
$data_de = (!empty($_POST['data_de'])) ? $_POST['data_de'] : '01'.substr(converteData(getDataHora('data')), 2);
$data_ate = (!empty($_POST['data_ate'])) ? $_POST['data_ate'] : converteData(getDataHora('data_ontem'));
$usuario = (!empty($_POST['usuario'])) ? $_POST['usuario'] : '';
$afastamentos = (!empty($_POST['afastamentos'])) ? $_POST['afastamentos'] : 's';
$empresa = (!empty($_POST['empresa'])) ? $_POST['empresa'] : '';
$fila = (!empty($_POST['fila'])) ? "'".join("','", $_POST['fila'])."'" : '';
$segundos_atendimento = (!empty($_POST['segundos_atendimento'])) ? $_POST['segundos_atendimento'] : 0;
$segundos_espera_perdida = (!empty($_POST['segundos_espera_perdida'])) ? $_POST['segundos_espera_perdida'] : 0;

$data_hora_atual = getDataHora();
$ano_atual = $data_hora_atual[0].$data_hora_atual[1].$data_hora_atual[2].$data_hora_atual[3];
$mes_atual = $data_hora_atual[5].$data_hora_atual[6];
$ano_de = (! empty($_POST['ano_de'])) ? $_POST['ano_de'] : $ano_atual;
$mes_de = (! empty($_POST['mes_de'])) ? $_POST['mes_de'] : '01';
$ano_ate = (! empty($_POST['ano_ate'])) ? $_POST['ano_ate'] : $ano_atual;
$mes_ate = (! empty($_POST['mes_ate'])) ? $_POST['mes_ate'] : $mes_atual;

$filtro = (!empty($_POST['filtro'])) ? $_POST['filtro'] : 'atendente';
$lider = (!empty($_POST['lider'])) ? $_POST['lider'] : '';

$gerar = (!empty($_POST['gerar'])) ? 1 : 0;
$id_usuario = $_SESSION['id_usuario'];
$dados = DBRead('', 'tb_usuario', "WHERE id_usuario = '$id_usuario'");
$perfil_sistema = $dados[0]['id_perfil_sistema'];

if($gerar){
	$collapse = '';
	$collapse_icon = 'plus';
}else{
	$collapse = 'in';
	$collapse_icon = 'minus';
}
if($tipo_relatorio == 1){
	$display_row_periodo = '';
    $display_row_afastamentos = '';    
    $display_row_periodo_mes_ano = 'style="display:none;"';
    $display_row_empresa = 'style="display:none;"';
	$display_row_fila = 'style="display:none;"';
    $display_row_segundos_atendimento = 'style="display:none;"';    
    $display_row_segundos_espera_perdida = 'style="display:none;"';
    if($filtro == 'atendente'){
        $display_row_operador = '';
        $display_row_lider = 'style="display:none;"';
    }else{
        $display_row_operador = 'style="display:none;"';
        $display_row_lider = '';
    }
    $display_row_filtro = '';    
}else if($tipo_relatorio == 2){
    $display_row_periodo = 'style="display:none;"';	
	$display_row_afastamentos = 'style="display:none;"';    
    $display_row_periodo_mes_ano = '';    
    $display_row_empresa = '';    
	$display_row_fila = '';
    $display_row_segundos_atendimento = '';
    if($usuario){
        $display_row_segundos_espera_perdida = 'style="display:none;"';
    }else{
        $display_row_segundos_espera_perdida = '';
    }	
    $display_row_filtro = 'style="display:none;"';
    $display_row_operador = '';
    $display_row_lider = 'style="display:none;"';
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
	                    <h3 class="panel-title text-left pull-left" style="margin-top: 2px;">Relatório - Indicadores - Call Center:</h3>
	                    <div class="panel-title text-right pull-right"><button data-toggle="collapse" data-target="#accordionRelatorio" class="btn btn-xs btn-info" type="button" title="Visualizar filtros"><i id="i_collapse" class="fa fa-<?=$collapse_icon?>"></i></button></div>
	                </div>
	                <div id="accordionRelatorio" class="panel-collapse collapse <?=$collapse?>">
	                	<div class="panel-body">
                            <div class="row">
                				<div class="col-md-12">
                					<div class="form-group">
								        <label for="">Tipo de Relatório:</label>
								        <select name="tipo_relatorio" id="tipo_relatorio" class="form-control input-sm">
								        	<option value="1" <?php if($tipo_relatorio == '1'){ echo 'selected';}?>>Indicadores Detalhados</option>
                                            <option value="2" <?php if($tipo_relatorio == '2'){ echo 'selected';}?>>Indicadores Mensais</option>
								        </select>
								    </div>
                				</div>
                			</div>
                            <div class="row" id="row_periodo" <?=$display_row_periodo?>>
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
                            <div class="row" id="row_periodo_mes_ano" <?=$display_row_periodo_mes_ano?>>
								<div class="col-md-3">
									<div class="form-group">
										<label>Mês ini.:</label> 
										<select name="mes_de" id="mes_de" class="form-control input-sm">
												<?php
												if($mes_de){
													$sel_dados_mes_de[$mes_de] = 'selected';  
												}else{
													$sel_dados_mes_de[$mes_atual] = 'selected';  
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
													echo "<option value='".sprintf('%02d', $nume)."' ".$sel_dados_mes_de[$nume].">".$mes."</option>";
												}
												?>													
										</select>
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group">
										<label>Ano ini.:</label> 
										<select name="ano_de" id="ano_de" class="form-control input-sm">
											<?php
											$sel_dados_ano_de[$ano_de] = 'selected';
											$anos = array(												
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
												$selected = $ano_de == $num ? "selected" : "";
												echo "<option value='".$num."' ".$selected.">".$num."</option>";
											}
											?>													
									</select>
									</div>
								</div>
                                <div class="col-md-3">
									<div class="form-group">
										<label>Mês fin.:</label> 
										<select name="mes_ate" id="mes_ate" class="form-control input-sm">
												<?php
												if($mes_ate){
													$sel_dados_mes_ate[$mes_ate] = 'selected';  
												}else{
													$sel_dados_mes_ate[$mes_atual] = 'selected';  
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
													echo "<option value='".sprintf('%02d', $nume)."' ".$sel_dados_mes_ate[$nume].">".$mes."</option>";
												}
												?>													
										</select>
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group">
										<label>Ano fin.:</label> 
										<select name="ano_ate" id="ano_ate" class="form-control input-sm">
											<?php
											$sel_dados_ano_ate[$ano_ate] = 'selected';
											$anos = array(												
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
												$selected = $ano_ate == $num ? "selected" : "";
												echo "<option value='".$num."' ".$selected.">".$num."</option>";
											}
											?>													
									</select>
									</div>
								</div>
							</div>
                            <div class="row" id="row_empresa" <?=$display_row_empresa?>>
								<div class="col-md-12">
									<div class="form-group">
								        <label for="">Empresa:</label>
								        <select name="empresa" class="form-control input-sm">
								        	<option value="">Todas</option>
								            <?php 
								            	$dados_empresas = DBRead('snep','empresas',"WHERE status!= 2 ORDER BY nome ASC");
								            	if($dados_empresas){
								            		foreach ($dados_empresas as $conteudo_empresas) {
														$selected = $empresa == $conteudo_empresas['id'] ? "selected" : "";
								            			echo "<option value='".$conteudo_empresas['id']."' ".$selected.">".$conteudo_empresas['nome']."</option>";
								            		}
								            	}
								            ?>
								        </select>
								    </div>
								</div>
                            </div>
                            <?php if($perfil_sistema != '3'){ ?>
							<div class="row" id="row_filtro" <?=$display_row_filtro?>>
								<div class="col-md-12">
									<div class="form-group">
								        <label for="">Filtrar por:</label>
								        <select name="filtro" class="form-control input-sm" id='filtro'>
								        	<option value="atendente" <?php if($filtro == 'atendente'){echo 'selected';} ?> >Atendente</option>
								        	<option value="lider" <?php if($filtro == 'lider'){echo 'selected';} ?> >Líder Direto</option>
								        </select>
								    </div>
								</div>
							</div>
                            <?php }?>
                            <?php if($perfil_sistema != '3'){ ?>
							<div class="row" id="row_operador" <?=$display_row_operador?>>
								<div class="col-md-12">
									<div class="form-group">
								        <label for="">Atendente:</label>
								        <select name="usuario" class="form-control input-sm" id='usuario'>
								        	<option value="">Todos</option>
								            <?php
												$sel_usuario[$usuario] = 'selected';			
												$dados_usuarios = DBRead('','tb_usuario a',"INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE id_perfil_sistema = '3' AND a.id_asterisk AND a.id_ponto ORDER BY b.nome ASC","a.id_usuario, b.nome");
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
                            <?php }?>
                            <?php if($perfil_sistema != '3'){ ?>
							<div class="row" id="row_lider" <?=$display_row_lider?>>
								<div class="col-md-12">
									<div class="form-group">
										<label for="">Líder Direto:</label>
										<select name="lider" class="form-control input-sm" id="lider">
												<?php
												$dados_lider = DBRead('', 'tb_usuario a', "INNER JOIN tb_usuario b ON a.lider_direto = b.id_usuario INNER JOIN tb_pessoa c ON b.id_pessoa = c.id_pessoa WHERE a.lider_direto AND a.status = '1' AND b.id_perfil_sistema = '13' GROUP BY  a.lider_direto, c.nome ORDER BY c.nome ASC", "a.lider_direto, c.nome");
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
                            <div class="row" id="row_fila" <?=$display_row_fila?>>
								<div class="col-md-12">
									<div class="form-group">
								        <label for="">Fila:</label>
								        <select name="fila[]" class="form-control input-sm" multiple="multiple" size=15>
								            <?php
								            	$dados_filas = DBRead('snep','queues',"ORDER BY name DESC");
								            	if($dados_filas){
								            		foreach ($dados_filas as $conteudo_filas) {
								            			if(preg_match('/'.$conteudo_filas['name'].'/i', $fila)){
								            				$sel_fila = 'selected';
								            			}else{
								            				$sel_fila = '';
								            			}
								            			echo "<option value='".$conteudo_filas['name']."' $sel_fila>".$conteudo_filas['name']."</option>";
								            		}
								            	}
								            ?>
								        </select>
								    </div>
								</div>
							</div>
                            <div class="row" id="row_segundos_atendimento" <?=$display_row_segundos_atendimento?>>
								<div class="col-md-12">
									<div class="form-group">
								        <label for="">Descartar tempo de atendimento abaixo de (segundos):</label><br>
								        <input type="number" class="form-control input-sm" name="segundos_atendimento" value="<?=$segundos_atendimento?>">
								    </div>
								</div>
							</div>
                            <?php if($perfil_sistema != '3'){ ?>
                            <div class="row" id="row_segundos_espera_perdida" <?=$display_row_segundos_espera_perdida?>>
								<div class="col-md-12">
									<div class="form-group">
								        <label for="">Descartar tempo de espera das perdidas abaixo de (segundos):</label><br>
								        <input type="number" class="form-control input-sm" name="segundos_espera_perdida" value="<?=$segundos_espera_perdida?>">
								    </div>
								</div>
							</div>                            
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
            if($perfil_sistema == '3'){
				$usuario = $id_usuario;
			}
            if($tipo_relatorio == 1){
                relatorio_indicadores_detalhado($data_de, $data_ate, $usuario, $afastamentos, $filtro, $lider);
            }elseif($tipo_relatorio == 2){					
                relatorio_indicadores_mensais($mes_de, $mes_ate, $ano_de, $ano_ate, $usuario, $empresa, $fila, $segundos_atendimento, $segundos_espera_perdida);
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
			$('#row_periodo').show();
			           
			$('#row_afastamentos').show();
            $('#row_periodo_mes_ano').hide();            
            $('#row_empresa').hide();            
			$('#row_fila').hide();
			$('#row_segundos_atendimento').hide();
            if ($('#usuario').val() != ''){
                $('#row_segundos_espera_perdida').hide();
            }else{
                $('#row_segundos_espera_perdida').show();  
            }
            $('#row_filtro').show(); 
            if ($('#lider').val() == 'atendente'){
                $('#row_operador').show(); 
                $('#row_lider').hide(); 
            }else{
                $('#row_operador').hide(); 
                $('#row_lider').show();
            }
		}else if(tipo_relatorio == 2){
            $('#row_periodo').hide();          
			$('#row_afastamentos').hide();
            $('#row_periodo_mes_ano').show();                
			$('#row_empresa').show();            
			$('#row_fila').show();
			$('#row_segundos_atendimento').show();
            if ($('#usuario').val() != ''){
                $('#row_segundos_espera_perdida').hide();
            }else{
                $('#row_segundos_espera_perdida').show();  
            }
            $('#row_filtro').hide(); 
            $('#row_operador').show(); 
            $('#row_lider').hide(); 
        }
    });
    $('#filtro').on('change',function(){
		filtro = $(this).val();
		if(filtro == 'atendente'){		
            $('#row_operador').show(); 
            $('#row_lider').hide(); 
		}else{
            $('#row_operador').hide(); 
            $('#row_lider').show(); 
        }
    });
	$('#accordionRelatorio').on('shown.bs.collapse', function(){
       $("#i_collapse").removeClass("fa fa-plus").addClass("fa fa-minus");
    });
    $('#accordionRelatorio').on('hidden.bs.collapse', function(){
       $("#i_collapse").removeClass("fa fa-minus").addClass("fa fa-plus");
    });
    $(document).on('submit', 'form', function(){
		if (!confirm('Atenção! Relatório pode demorar alguns minutos para ser processado, não atualize a pagina após clicar em "OK"!')){
    		return false; 
    	}    
        modalAguarde();
    });
    $(document).on('change', '#usuario', function(){
		if ($(this).val() != ''){
			$('#row_segundos_espera_perdida').hide();
    	}else{
            $('#row_segundos_espera_perdida').show();  
        }
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
function relatorio_indicadores_detalhado($data_de, $data_ate, $usuario, $afastamentos, $filtro, $lider){
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
	echo "<legend style=\"text-align:center;\"><strong>Relatório de Indicadores Detalhados</strong><br><span style=\"font-size: 14px;\"><strong>Gerado em:</strong> $data_hora</span></legend>";
    echo "<legend style=\"text-align:center;\">$periodo_amostra</legend>";

	if($usuario){
		$filtro_usuario = " AND a.id_usuario = '$usuario'";
	}else{
		$filtro_usuario = "";
    }
    
    if($filtro == 'atendente'){
        $filtro_lider = "";
    }else{
        $filtro_usuario = " AND a.lider_direto = '$lider'";
    }

	$fila = "'callATENDIMENTOvip','callATENDIMENTOalta','callATENDIMENTOnormal','callATENDIMENTObaixa','callATENDIMENTOnotificacaoparada', 'callATENDIMENTOvipEXT','callATENDIMENTOaltaEXT','callATENDIMENTOnormalEXT','callATENDIMENTObaixaEXT','callATENDIMENTOnotificacaoparadaEXT','callATENDIMENTOvipEXP','callATENDIMENTOaltaEXP','callATENDIMENTOnormalEXP','callATENDIMENTObaixaEXP','callATENDIMENTOnotificacaoparadaEXP'";
	
	$dados_usuario = DBRead('','tb_usuario a',"INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE id_perfil_sistema = '3' $filtro_usuario AND a.id_asterisk AND a.id_ponto ORDER BY b.nome","a.*, b.nome");

	if($dados_usuario){
		echo "
			<table class=\"table table-hover dataTable\"> 
				<thead> 
					<tr> 
    					<th>Atendente</th>
						<th>Meses contratação</th>
    					<th>Horas na empresa <i class=\"fa fa-question-circle\" style=\"cursor:help;\" data-toggle=\"tooltip\" data-placement=\"top\" data-container=\"body\" title='Tempo total em expediente no ponto'></i></th>
    					<th>Faltas justificadas</th>
    					<th>Faltas não justificadas</th>
    					<th>Absenteísmo</th>
    					<th>Tempo em ativo <i class=\"fa fa-question-circle\" style=\"cursor:help;\" data-toggle=\"tooltip\" data-placement=\"top\" data-container=\"body\" title='Tempo em que o atendente ficou em pausa no tipo \"Ativo\"'></i></th>
    					<th>Realização de ativo  <i class=\"fa fa-question-circle\" style=\"cursor:help;\" data-toggle=\"tooltip\" data-placement=\"top\" data-container=\"body\" title='Porcentagem de \"Tempo em ativo\" sobre as \"Horas na empresa\"'></i></th>
                        
                        <th>Tempo em chat <i class=\"fa fa-question-circle\" style=\"cursor:help;\" data-toggle=\"tooltip\" data-placement=\"top\" data-container=\"body\" title='Tempo em que o atendente ficou em pausa no tipo \"Atendimento via chat\"'></i></th>
                        <th>Realização de chat  <i class=\"fa fa-question-circle\" style=\"cursor:help;\" data-toggle=\"tooltip\" data-placement=\"top\" data-container=\"body\" title='Porcentagem de \"Tempo em chat\" sobre as \"Horas na empresa\"'></i></th>
                        
                        <th>Tempo em outras atividades <i class=\"fa fa-question-circle\" style=\"cursor:help;\" data-toggle=\"tooltip\" data-placement=\"top\" data-container=\"body\" title='Tempo em que o atendente ficou em pausa exceto nos tipos \"Ativo\", Chat, \"Banheiro\" e  \"Cozinha\"'></i></th>
						<th>Realização de outras atividades  <i class=\"fa fa-question-circle\" style=\"cursor:help;\" data-toggle=\"tooltip\" data-placement=\"top\" data-container=\"body\" title='Porcentagem de \"Tempo em outras atividades\" sobre as \"Horas na empresa\"'></i></th>
						<th>Tempo em pausa <i class=\"fa fa-question-circle\" style=\"cursor:help;\" data-toggle=\"tooltip\" data-placement=\"top\" data-container=\"body\" title='Tempo em que o atendente ficou em pausa nos tipos \"Banheiro\" e \"Cozinha\"'></i></th>
						<th>Realização de pausa  <i class=\"fa fa-question-circle\" style=\"cursor:help;\" data-toggle=\"tooltip\" data-placement=\"top\" data-container=\"body\" title='Porcentagem de \"Tempo em pausa\" sobre as \"Horas na empresa\"'></i></th>
    					<th>Ligações atendidas</th>
						<th>Tempo em atendimento</th>
						<th>Realização de atendimento  <i class=\"fa fa-question-circle\" style=\"cursor:help;\" data-toggle=\"tooltip\" data-placement=\"top\" data-container=\"body\" title='Porcentagem de \"Tempo em atendimento\" sobre as \"Horas na empresa\"'></i></th>
    					<th>Tempo ocioso</th>
    					<th>Ociosidade <i class=\"fa fa-question-circle\" style=\"cursor:help;\" data-toggle=\"tooltip\" data-placement=\"top\" data-container=\"body\" title='Porcentagem sobre as \"Horas na empresa\"'></i></th>
    					<th>Ligações por hora <i class=\"fa fa-question-circle\" style=\"cursor:help;\" data-toggle=\"tooltip\" data-placement=\"top\" data-container=\"body\"title='\"Ligações atendidas\" divididas por (\"Horas na empresa\" menos \"Tempo em ativo\" menos o \"Tempo em chat\" menos \"Tempo em outras pausas\")'></i></th>
    					<th>TMA <i class=\"fa fa-question-circle\" style=\"cursor:help;\" data-toggle=\"tooltip\" data-placement=\"top\" data-container=\"body\" title='Tempo Médio de Atendimento'></i></th>
    					<th>Nota média <i class=\"fa fa-question-circle\" style=\"cursor:help;\" data-toggle=\"tooltip\" data-placement=\"top\" data-container=\"body\" title='Média de todas as notas recebidas'></i></th>
    					<th>Ligações com nota <i class=\"fa fa-question-circle\" style=\"cursor:help;\" data-toggle=\"tooltip\" data-placement=\"top\" data-container=\"body\" title='Porcentagem de ligações com nota'></i></th>
    					<th>Solicitações de ajuda</th>
    					<th>Erros/Reclamações</th>
                        <th>Monitoria</th>
                        <th>Resolução <i class=\"fa fa-question-circle\" style=\"cursor:help;\" data-toggle=\"tooltip\" data-placement=\"top\" data-container=\"body\" title='Diagnosticado + Resolvido'></i></th>
                        <th>Atendimentos por Hora <i class=\"fa fa-question-circle\" style=\"cursor:help;\" data-toggle=\"tooltip\" data-placement=\"top\" data-container=\"body\" title='Atendimentos Faturados divididos por (Horas na empresa menos Tempo em Ativo menos Tempo em Manutenção/TI)'></i></th>
					</tr>
				</thead> 
				<tbody>"
		;

		$total_qtd_faltas_justificadas = 0;
		$total_qtd_faltas_nao_justificadas = 0;
		$total_qtd_entradas = 0;
		$total_qtd_ajuda = 0;
		$total_qtd_erros = 0;
		$cont_absenteismo = 0;
		$total_realizacao_pausa_produtiva = 0;
		$total_segundos_ponto = 0;
		$total_duracao_total_pausas = 0;
        $total_qtd_soma_ta = 0;
        $total_atendentes = 0;
        $total_meses_contratacao = 0;
        $total_soma_notas = 0;
        $total_qtd_notas = 0;
        $total_monitoria_resultado = 0;
        $total_atendentes_monitoria = 0;
        $total_absenteismo = 0;
        $total_realizacao_ativo = 0;
        $total_realizacao_chat = 0;
        $total_realizacao_pausa_improdutiva = 0;
        $total_realizacao_atendimentos = 0;
        $total_soma_notas  = 0;
        $total_qtd_ocioso = 0;
        $total_qtd_duracao_total_pausas_improdutivas = 0;
        $total_qtd_duracao_total_chat = 0;
        $total_qtd_duracao_total_pausas_produtivas = 0;
        $total_qtd_duracao_total_ativo = 0;
        $total_qtd_horas_empresa = 0;
        $total_realizacao_ocioso = 0;
        $resolucao_resultado_total = 0;
        $resolucao_quantidade_total = 0;
        $total_atendimentos_total = 0;
        $horas_calc_atendimentos_hora_total = 0;

		foreach ($dados_usuario as $conteudo_usuario) {

			$id_usuario_conteudo = $conteudo_usuario['id_usuario'];
			$nome_usuario = $conteudo_usuario['nome'];
			$id_asterisk_usuario = $conteudo_usuario['id_asterisk'];
			$id_ponto_usuario = $conteudo_usuario['id_ponto']; 

			$data_string_ponto = '
				{
					"report": {				
						"start_date": "'.converteData($data_de).'",
				        "end_date": "'.converteData($data_ate).'",
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

			$data_string_ponto = '
				{
					"report": {	
						"group_by": "",
						"start_date": "'.converteData($data_de).'",
						"end_date": "'.converteData($data_ate).'",
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
			$horas_empresa = $result_ponto['data'][0][0]['data'][0]['total_time'];
			$qtd_faltas_nao_justificadas = $result_ponto['data'][0][0]['data'][0]['missing_days'];
			if((($afastamentos == 'n') || ($afastamentos == 's' && !$afastamento_usuario) || ($afastamentos == 's' && $horas_empresa != '00:00')) && $horas_empresa){

				$qtd_entradas = 0;
				$soma_notas = 0;
				$qtd_notas = 0;
				$soma_ta = 0;
				$soma_ta_menor_30 = 0;
				$duracao_total_pausas = 0;
				$duracao_total_pausas_produtivas = 0;
				$duracao_total_pausas_improdutivas = 0;
				$duracao_total_ativo = 0;
				$duracao_total_manutencao_ti = 0;
				$duracao_total_chat = 0;
				$duracao_tipos_pausas_produtivas = array();
				$duracao_tipos_pausas_improdutivas = array();
				$title_tipos_pausas_produtivas = "";
				$title_tipos_pausas_improdutivas = "";

				$filtro_entradas = "";
				$filtro_pausas = "";
				$filtro_erros = "";
                
				if($data_de){
                    $filtro_entradas .= " AND b.time >= '".converteData($data_de)." 00:00:00'";
                    $filtro_pausas .= " AND a.data_pause >= '".converteData($data_de)." 00:00:00'";
					$filtro_erros .= " AND data_cadastrado >= '".converteData($data_de)." 00:00:00'";
				}
				if($data_ate){
					$filtro_entradas .= " AND b.time <= '".converteData($data_ate)." 23:59:59'";
					$filtro_pausas .= " AND a.data_pause <= '".converteData($data_ate)." 23:59:59'";
					$filtro_erros .= " AND data_cadastrado <= '".converteData($data_ate)." 23:59:59'";
				}
			    
				$filtro_entradas .= " AND b.agent = 'AGENT/$id_asterisk_usuario' AND (c.data2 >= 30 OR c.data4 >= 30)";

				$dados_entradas = DBRead('snep','queue_log a',"INNER JOIN queue_log b ON (b.callid = a.callid AND b.event = 'CONNECT') INNER JOIN queue_log c ON (c.callid = a.callid AND (c.event = 'COMPLETEAGENT' OR c.event = 'COMPLETECALLER' OR c.event = 'BLINDTRANSFER' OR c.event = 'ATTENDEDTRANSFER')) LEFT JOIN pesquisa d ON  a.callid = d.UNIQUEID WHERE a.event = 'ENTERQUEUE' $filtro_entradas GROUP BY b.id ORDER BY b.id", "a.queuename AS 'enterqueue_queuename', c.event AS 'finalizacao_event', c.data1 AS 'finalizacao_data1', c.data2 AS 'finalizacao_data2', c.data3 AS 'finalizacao_data3', d.nota AS 'nota'");

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
							if($info_chamada['tempo_atendimento'] >= 30){
								$soma_ta += $info_chamada['tempo_atendimento'];	
							}else{
								$soma_ta_menor_30 += $info_chamada['tempo_atendimento'];	
							}			
						}									
					}
                }
                
                $dados_pausas = DBRead('snep','queue_agents_pause a',"INNER JOIN tipo_pausa b ON a.tipo_pausa = b.id WHERE a.codigo = '$id_asterisk_usuario' AND a.data_unpause IS NOT NULL $filtro_pausas ORDER BY a.data_pause ASC", "a.*, b.nome");
                if($dados_pausas){
                    foreach ($dados_pausas as $conteudo_pausas) {
                        $data_pausa = strtotime($conteudo_pausas['data_pause']);
                        $data_retorno = strtotime($conteudo_pausas['data_unpause']);
                        $nome_pausa = $conteudo_pausas['nome'];
                        $duracao_pausa = ($data_retorno - $data_pausa);
                        if($conteudo_pausas['tipo_pausa'] == '6'){
                            $duracao_total_ativo += $duracao_pausa;
                        }else if($conteudo_pausas['tipo_pausa'] == '19'){
                            $duracao_total_chat += $duracao_pausa;					
                        }else if($conteudo_pausas['tipo_pausa'] == '1' || $conteudo_pausas['tipo_pausa'] == '2' || $conteudo_pausas['tipo_pausa'] == '20'){
                            $duracao_tipos_pausas_improdutivas[$nome_pausa] += $duracao_pausa;
                            $duracao_total_pausas_improdutivas += $duracao_pausa;						
                        }else{
                            $duracao_tipos_pausas_produtivas[$nome_pausa] += $duracao_pausa;
                            $duracao_total_pausas_produtivas += $duracao_pausa;
                            if($conteudo_pausas['tipo_pausa'] == '15'){
                                $duracao_total_manutencao_ti += $duracao_pausa;					
                            }    
                        }
                        $duracao_total_pausas += $duracao_pausa;
                    }
                }

				$data_string_ponto = '
					{
						"report": {	
							"group_by": "",
							"columns": "name,shift,admission_date,email",
							"employee_id": '.$id_ponto_usuario.',
							"row_filters": "",
							"format": "json"
						}
					}
				';  
				
				$result_ponto = troca_dados_curl('https://api.pontomais.com.br/external_api/v1/reports/employees', $data_string_ponto, array('Content-Type: application/json','access-token: FcogHwphYbzMk3IF1tXBmh5ypV49O-74CSf7dMxE3cMcabhbaajbefjai'));
				
				$carga_horaria = explode('-', $result_ponto['data'][0][0]['data'][0]['shift']);
				$carga_horaria = end(explode(' ', $carga_horaria[0]));
				$meses_contratacao = sprintf("%01.1f", (floor((strtotime(converteData($data_ate)) - strtotime(converteData(end(explode(' ', $result_ponto['data'][0][0]['data'][0]['admission_date']))))) / (60 * 60 * 24))) / 30);
				
				
				$data_string_ponto = '
					{
						"report": {				
							"start_date": "'.converteData($data_de).'",
							"end_date": "'.converteData($data_ate).'",
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
							"start_date": "'.converteData($data_de).'",
							"end_date": "'.converteData($data_ate).'",
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

				$dados_erros = DBRead('','tb_erro_atendimento',"WHERE id_usuario = '$id_usuario_conteudo' AND status = '1' $filtro_erros","COUNT(*) AS 'qtd_erros'");
                $qtd_erros = $dados_erros[0]['qtd_erros'];
                
                $dados_monitoria = DBRead('','tb_monitoria_resultado', "WHERE id_usuario = '$id_usuario_conteudo' AND data_referencia = '".substr(converteData($data_de),0,7)."-01' ORDER BY id_monitoria_resultado ASC LIMIT 1");
                if($dados_monitoria){
                    $monitoria_resultado = $dados_monitoria[0]['resultado'];
                    $total_atendentes_monitoria++;
                }else{
                    $monitoria_resultado = 0;
                }

				$dados_ajuda = DBRead('','tb_solicitacao_ajuda a',"WHERE a.data_inicio >= '".converteData($data_de)." 00:00:00' AND a.data_inicio <='".converteData($data_ate)." 23:59:59' AND a.atendente = '".$id_usuario_conteudo."'", "COUNT(atendente) AS 'qtd_ajuda'");
                $qtd_ajuda = $dados_ajuda[0]['qtd_ajuda'];	
                
                $dados_atendimentos = DBRead('','tb_atendimento', "WHERE falha != '2' AND gravado = '1' AND data_inicio BETWEEN '" .converteData($data_de). " 00:00:00' AND '" .converteData($data_ate). " 23:59:59' AND id_usuario = '$id_usuario_conteudo' ", "COUNT(*) as cont");			
                $total_atendimentos = $dados_atendimentos[0]['cont'];

                if($total_atendimentos != 0){
                    $dados_cont_resolucao = DBRead('', 'tb_atendimento', "WHERE falha != '2' AND gravado = '1' AND (resolvido = '1' OR resolvido = '3') AND data_inicio BETWEEN '" .converteData($data_de). " 00:00:00' AND '" .converteData($data_ate). " 23:59:59' AND id_usuario = '$id_usuario_conteudo'", "COUNT(*) as cont");

                    $cont_resolucao = $dados_cont_resolucao[0]['cont'];
                    $percentual_cont_resolucao = sprintf("%01.2f", round($cont_resolucao * 100) / ($total_atendimentos == 0 ? 1 : $total_atendimentos), 2);

                    $horas_calc_atendimentos_hora = converteHorasDecimal(converteSegundosHoras($segundos_ponto-$duracao_total_ativo-$duracao_total_manutencao_ti));
                    $atendimentos_hora = sprintf("%01.2f", $total_atendimentos / ($horas_calc_atendimentos_hora == 0 ? 1 : $horas_calc_atendimentos_hora), 2);
                }else{
                    $cont_resolucao = 0;
                    $percentual_cont_resolucao = 0;
                    $horas_calc_atendimentos_hora = 0;
                    $atendimentos_hora = 0;     
                }

				arsort($duracao_tipos_pausas_improdutivas);		
				foreach ($duracao_tipos_pausas_improdutivas as $tipo => $duracao) {
					$title_tipos_pausas_improdutivas .= "$tipo - ".converteSegundosHoras($duracao)." (".sprintf("%01.2f", round( $duracao * 100 /($duracao_total_pausas_improdutivas == 0 ? 1 : $duracao_total_pausas_improdutivas), 2))."%)<br>";
				}

				arsort($duracao_tipos_pausas_produtivas);		
				foreach ($duracao_tipos_pausas_produtivas as $tipo => $duracao) {
					$title_tipos_pausas_produtivas .= "$tipo - ".converteSegundosHoras($duracao)." (".sprintf("%01.2f", round( $duracao * 100 /($duracao_total_pausas_produtivas == 0 ? 1 : $duracao_total_pausas_produtivas), 2))."%)<br>";
				}

				$total_qtd_faltas_justificadas = (int)$total_qtd_faltas_justificadas + (int)$qtd_faltas_justificadas;
				$total_qtd_faltas_nao_justificadas = (int)$total_qtd_faltas_nao_justificadas + (int)$qtd_faltas_nao_justificadas;
				$total_qtd_entradas = (int)$total_qtd_entradas + (int)$qtd_entradas;
				$total_qtd_ajuda = (int)$total_qtd_ajuda + (int)$qtd_ajuda;
				$total_qtd_erros = (int)$total_qtd_erros + (int)$qtd_erros;
				
				//total de horas na empresa
				$segundos_hora_empresa = explode(':', $horas_empresa.':00');
				$segundos_hora_empresa = $segundos_hora_empresa[0] * 3600 + $segundos_hora_empresa[1] * 60;
				$total_qtd_horas_empresa += $segundos_hora_empresa;                

				//total de tempo em ativo
                $total_qtd_duracao_total_ativo += $duracao_total_ativo;   
                
                //total de tempo em chat
				$total_qtd_duracao_total_chat += $duracao_total_chat;  

				//total de pausa produtiva
				$total_qtd_duracao_total_pausas_produtivas += $duracao_total_pausas_produtivas;         

				//total de pausa improdutiva
				$total_qtd_duracao_total_pausas_improdutivas += $duracao_total_pausas_improdutivas;         

				//total de tempo de atendimento
				$total_qtd_soma_ta += $soma_ta;   

				//total tempo ocioso
                $segundos_ocioso = $segundos_ponto-$duracao_total_pausas-$soma_ta-$soma_ta_menor_30;
                if($segundos_ocioso > 0){
                    $total_qtd_ocioso += $segundos_ocioso;   
                }					 				

				//absenteismo
				if($absenteismo){
					$total_absenteismo += $absenteismo;
					$cont_absenteismo++;
				}else{
					$cont_absenteismo++;
				}

				//Realização de ativo
				if(sprintf("%01.2f", round($duracao_total_ativo * 100 /($segundos_ponto == 0 ? 1 : $segundos_ponto), 2)) != '0.00'){
					$total_realizacao_ativo += sprintf("%01.2f", round($duracao_total_ativo * 100 /($segundos_ponto == 0 ? 1 : $segundos_ponto), 2));					
                }
                
                //Realização de chat
				if(sprintf("%01.2f", round($duracao_total_chat * 100 /($segundos_ponto == 0 ? 1 : $segundos_ponto), 2)) != '0.00'){
					$total_realizacao_chat += sprintf("%01.2f", round($duracao_total_chat * 100 /($segundos_ponto == 0 ? 1 : $segundos_ponto), 2));					
				}

				//Realização Pausas Produtivas
				if(sprintf("%01.2f", round($duracao_total_pausas_produtivas * 100 /($segundos_ponto == 0 ? 1 : $segundos_ponto), 2)) != '0.00'){
					$total_realizacao_pausa_produtiva += sprintf("%01.2f", round($duracao_total_pausas_produtivas * 100 /($segundos_ponto == 0 ? 1 : $segundos_ponto), 2));
				}

				//Realização Pausas Improdutivas
				if(sprintf("%01.2f", round($duracao_total_pausas_improdutivas * 100 /($segundos_ponto == 0 ? 1 : $segundos_ponto), 2)) != '0.00'){
					$total_realizacao_pausa_improdutiva += sprintf("%01.2f", round($duracao_total_pausas_improdutivas * 100 /($segundos_ponto == 0 ? 1 : $segundos_ponto), 2));
				}
				
				//Realização de atendimentos
				if(sprintf("%01.2f", round($soma_ta * 100 /($segundos_ponto == 0 ? 1 : $segundos_ponto), 2)) != '0.00'){
					$total_realizacao_atendimentos += sprintf("%01.2f", round($soma_ta * 100 /($segundos_ponto == 0 ? 1 : $segundos_ponto), 2));					
				}

				//Realização ociosos
				if(sprintf("%01.2f", round(($segundos_ponto-$duracao_total_pausas-$soma_ta-$soma_ta_menor_30) * 100 /($segundos_ponto == 0 ? 1 : $segundos_ponto), 2)) != '0.00'){
					$total_realizacao_ocioso += sprintf("%01.2f", round(($segundos_ponto-$duracao_total_pausas-$soma_ta-$soma_ta_menor_30) * 100 /($segundos_ponto == 0 ? 1 : $segundos_ponto), 2));
                }


				//Realização nota media e porcentagem               
                $total_soma_notas += $soma_notas;
                $total_qtd_notas += $qtd_notas;				

					
				//total ponto e pausas
				$total_segundos_ponto += $segundos_ponto;
				$total_duracao_total_pausas += $duracao_total_pausas;
                
                //total meses contratacao
                $total_meses_contratacao += $meses_contratacao;
                $total_atendentes++;

                //total monitoria
                $total_monitoria_resultado += $monitoria_resultado;

                //total resolucao
                $resolucao_resultado_total += $cont_resolucao;
                $resolucao_quantidade_total += $total_atendimentos;
                
                //total atendimentos por hora
                $total_atendimentos_total += $total_atendimentos;
                $horas_calc_atendimentos_hora_total += $horas_calc_atendimentos_hora;

				echo '
				<tr>
					<td>'.$nome_usuario.'</td>
					<td>'.$meses_contratacao.'</td>
					<td>'.$horas_empresa.':00</td>
					<td>'.$qtd_faltas_justificadas.'</td>
					<td>'.$qtd_faltas_nao_justificadas.'</td>					
					<td style="cursor:help;" data-toggle="tooltip" data-placement="top" data-container="body" data-html="true" title="Previsto (sem intervalo): '.$horas_previstas.'<br>Ausência: '.$horas_faltantes.'">'.$absenteismo.'%</td>
					<td>'.converteSegundosHoras($duracao_total_ativo).'</td>
					<td>'.sprintf("%01.2f", round($duracao_total_ativo * 100 /($segundos_ponto == 0 ? 1 : $segundos_ponto), 2)).'%</td>
                    
                    <td>'.converteSegundosHoras($duracao_total_chat).'</td>
					<td>'.sprintf("%01.2f", round($duracao_total_chat * 100 /($segundos_ponto == 0 ? 1 : $segundos_ponto), 2)).'%</td>

                    <td style="cursor:help;" data-toggle="tooltip" data-placement="top" data-container="body" data-html="true" title="'.$title_tipos_pausas_produtivas.'">'.converteSegundosHoras($duracao_total_pausas_produtivas).'</td>
					<td>'.sprintf("%01.2f", round($duracao_total_pausas_produtivas * 100 /($segundos_ponto == 0 ? 1 : $segundos_ponto), 2)).'%</td>
					
					<td style="cursor:help;" data-toggle="tooltip" data-placement="top" data-container="body" data-html="true" title="'.$title_tipos_pausas_improdutivas.'">'.converteSegundosHoras($duracao_total_pausas_improdutivas).'</td>
					<td>'.sprintf("%01.2f", round($duracao_total_pausas_improdutivas * 100 /($segundos_ponto == 0 ? 1 : $segundos_ponto), 2)).'%</td>
					<td>'.$qtd_entradas.'</td>
					<td>'.converteSegundosHoras($soma_ta).'</td>
                    <td>'.sprintf("%01.2f", round($soma_ta * 100 /($segundos_ponto == 0 ? '1' : $segundos_ponto), 2)).'%</td>';
                    
                    if($segundos_ponto-$duracao_total_pausas-$soma_ta-$soma_ta_menor_30 > 0){
                        echo '<td>'.converteSegundosHoras($segundos_ponto-$duracao_total_pausas-$soma_ta-$soma_ta_menor_30).'</td>';
                        echo '<td>'.sprintf("%01.2f", round(($segundos_ponto-$duracao_total_pausas-$soma_ta-$soma_ta_menor_30) * 100 /($segundos_ponto == 0 ? 1 : $segundos_ponto), 2)).'%</td>';
                    }else{
                        echo '<td>00:00:00</td>';
                        echo '<td>0.00%</td>';
                    }    
                    
                    $aux_lig_hora = converteHorasDecimal(converteSegundosHoras($segundos_ponto-$duracao_total_pausas));
                    if($aux_lig_hora <= 0){
                        $aux_lig_hora = 1;
                    }
					if(sprintf("%01.2f", $qtd_entradas/$aux_lig_hora) != 0){
						echo '<td>'.sprintf("%01.2f", $qtd_entradas/$aux_lig_hora).'</td>';
					}else{
						echo '<td>0</td>';
					}

					echo '
					<td>'.gmdate("H:i:s", $soma_ta/($qtd_entradas == 0 ? 1 : $qtd_entradas)).'</td>
					<td>'.sprintf("%01.2f", round($soma_notas/($qtd_notas == 0 ? 1 : $qtd_notas), 2)).'</td>
					
					<td>'.sprintf("%01.2f", round($qtd_notas * 100 /($qtd_entradas == 0 ? 1 : $qtd_entradas), 2)).'%</td>
					<td>'.$qtd_ajuda.'</td>
					<td>'.$qtd_erros.'</td>
                    <td>'.sprintf("%01.2f", $monitoria_resultado).'%</td>
                    <td>'.$percentual_cont_resolucao.'%</td>
                    <td>'.$atendimentos_hora.'</td>
				</tr>
				';
			    
			}
		}
		echo "
				</tbody>";
				

				$total_horas_empresa = converteSegundosHoras($total_qtd_horas_empresa);
                $total_duracao_total_ativo = converteSegundosHoras($total_qtd_duracao_total_ativo);
                
                $total_duracao_total_chat = converteSegundosHoras($total_qtd_duracao_total_chat);
                
				$total_duracao_total_pausas_produtivas = converteSegundosHoras($total_qtd_duracao_total_pausas_produtivas);
				$total_duracao_total_pausas_improdutivas = converteSegundosHoras($total_qtd_duracao_total_pausas_improdutivas);
				$total_soma_ta = converteSegundosHoras($total_qtd_soma_ta);
				$total_ocioso = converteSegundosHoras($total_qtd_ocioso);				
				$total_tma = gmdate("H:i:s", $total_qtd_soma_ta/($total_qtd_entradas == 0 ? 1 : $total_qtd_entradas));
                $total_absenteismo = $total_absenteismo/($cont_absenteismo == 0 ? 1 : $cont_absenteismo);  
                $total_realizacao_ativo = $total_qtd_duracao_total_ativo*100/($total_qtd_horas_empresa == 0 ? 1 : $total_qtd_horas_empresa);
                
				$total_realizacao_chat = $total_qtd_duracao_total_chat*100/($total_qtd_horas_empresa == 0 ? 1 : $total_qtd_horas_empresa);
                
                $total_realizacao_pausa_produtiva = $total_qtd_duracao_total_pausas_produtivas*100/($total_qtd_horas_empresa == 0 ? 1 : $total_qtd_horas_empresa);
				$total_realizacao_pausa_improdutiva = $total_qtd_duracao_total_pausas_improdutivas*100/($total_qtd_horas_empresa == 0 ? 1 : $total_qtd_horas_empresa);
                $total_realizacao_atendimentos = $total_qtd_soma_ta*100/($total_qtd_horas_empresa == 0 ? 1 : $total_qtd_horas_empresa);
                $total_realizacao_ocioso = $total_qtd_ocioso*100/($total_qtd_horas_empresa == 0 ? 1 : $total_qtd_horas_empresa);
				$total_realizacao_ligacao_nota = $total_qtd_notas*100/($total_qtd_entradas == 0 ? 1 : $total_qtd_entradas);
                $total_realizacao_nota_media = $total_soma_notas/($total_qtd_notas == 0 ? 1 : $total_qtd_notas);      
                $total_realizacao_ligacao_hora = $total_qtd_entradas/(converteHorasDecimal(converteSegundosHoras($total_segundos_ponto-$total_duracao_total_pausas)) == 0 ? 1 : converteHorasDecimal(converteSegundosHoras($total_segundos_ponto-$total_duracao_total_pausas)));
                $total_realizacao_monitoria_resultado = $total_monitoria_resultado/($total_atendentes_monitoria == 0 ? 1 : $total_atendentes_monitoria);
                $total_realizacao_resolucao = round($resolucao_resultado_total * 100 / ($resolucao_quantidade_total == 0 ? 1 : $resolucao_quantidade_total), 2);


				echo "<tfoot>";
					echo '<tr>';
						echo '<th>Médias / Totais</th>';
						echo '<th>'.sprintf("%01.1f", $total_meses_contratacao/($total_atendentes == 0 ? 1 : $total_atendentes)).'</th>';
						echo '<th>'.$total_horas_empresa.'</th>';
						echo '<th>'.$total_qtd_faltas_justificadas.'</th>';
						echo '<th>'.$total_qtd_faltas_nao_justificadas.'</th>';						
						echo '<th>'.sprintf("%01.2f", $total_absenteismo).'%</th>';
						echo '<th>'.$total_duracao_total_ativo.'</th>';
                        echo '<th>'.sprintf("%01.2f", $total_realizacao_ativo).'%</th>';
                        
                        echo '<th>'.$total_duracao_total_chat.'</th>';
                        echo '<th>'.sprintf("%01.2f", $total_realizacao_chat).'%</th>';

						echo '<th>'.$total_duracao_total_pausas_produtivas.'</th>';
						echo '<th>'.sprintf("%01.2f", $total_realizacao_pausa_produtiva).'%</th>';						
						echo '<th>'.$total_duracao_total_pausas_improdutivas.'</th>';
						echo '<th>'.sprintf("%01.2f", $total_realizacao_pausa_improdutiva).'%</th>';
						echo '<th>'.$total_qtd_entradas.'</th>';
						echo '<th>'.$total_soma_ta.'</th>';
						echo '<th>'.sprintf("%01.2f", $total_realizacao_atendimentos).'%</th>';						
						echo '<th>'.$total_ocioso.'</th>';
						echo '<th>'.sprintf("%01.2f", $total_realizacao_ocioso).'%</th>';
						echo '<th>'.sprintf("%01.2f", $total_realizacao_ligacao_hora).'</th>';
						echo '<th>'.$total_tma.'</th>';
						echo '<th>'.sprintf("%01.2f", $total_realizacao_nota_media).'</th>';						
						echo '<th>'.sprintf("%01.2f", $total_realizacao_ligacao_nota).'%</th>';
						echo '<th>'.$total_qtd_ajuda.'</th>';
						echo '<th>'.$total_qtd_erros.'</th>';
                        echo '<td>'.sprintf("%01.2f", $total_realizacao_monitoria_resultado).'%</td>';
                        echo '<th>'.sprintf("%01.2f", $total_realizacao_resolucao).'%</th>';
                        echo '<th>'.sprintf("%01.2f", $total_atendimentos_total / ($horas_calc_atendimentos_hora_total == 0 ? 1 : $horas_calc_atendimentos_hora_total), 2).'</th>';		
					echo '</tr>';
				echo "</tfoot> ";
			echo "</table>
		";

		echo "
		<script>
			$(document).ready(function(){
			    var table = $('.dataTable').DataTable({
				    \"language\": {
			            \"url\": \"//cdn.datatables.net/plug-ins/1.10.19/i18n/Portuguese-Brasil.json\"
			        },
			        columnDefs: [
				    	{ type: 'time-uni', targets: 2 },
						{ type: 'time-uni', targets: 6 },
						{ type: 'time-uni', targets: 8 },
						{ type: 'time-uni', targets: 10 },
						{ type: 'time-uni', targets: 13 },
						{ type: 'time-uni', targets: 15 },
						{ type: 'time-uni', targets: 18 },
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
							filename: 'relatorio_indicadores_atendentes',
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
		echo "<legend style=\"text-align:center;\"><strong>Sem resultados!</strong></legend>";
	}
    echo "</div>";
}

function relatorio_indicadores_mensais($mes_de, $mes_ate, $ano_de, $ano_ate, $usuario, $empresa, $fila, $segundos_atendimento, $segundos_espera_perdida){

    $data_de = new DateTime($ano_de.'-'.$mes_de.'-01');
    $data_de->modify('first day of this month');
    $data_ate = new DateTime($ano_ate.'-'.$mes_ate.'-01');
    $data_ate->modify('first day of next month');
    $intervalo = DateInterval::createFromDateString('1 month');
    $periodo = new DatePeriod($data_de, $intervalo, $data_ate);

    if($usuario){
		$dados_usuario = DBRead('','tb_usuario a',"INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_usuario='$usuario'","a.*, b.nome");
		if($dados_usuario){
            $usuario_legenda = $dados_usuario[0]['nome'];
            $id_asterisk_usuario = $dados_usuario[0]['id_asterisk'];
		}else{
            $usuario_legenda = 'Não identificado';
            $id_asterisk_usuario = '';
		}
	}else{
            $usuario_legenda = 'Todos';
            $id_asterisk_usuario = '';
	}
    if($empresa){
		$dados_empresa = DBRead('snep','empresas',"WHERE id='$empresa'");
		if($dados_empresa){
			$nome_empresa = $dados_empresa[0]['nome'];
			$empresa_legenda = $dados_empresa[0]['nome'];
		}else{
			$empresa_legenda = 'Não identificada';
		}
	}else{
			$empresa_legenda = 'Todas';
	}
    if($segundos_atendimento){
		$legenda_descarte_segundos_atendimento = ", <strong>Descartado ligações com tempo de atendimento abaixo de - </strong>".$segundos_atendimento." segundos";
	}else{
		$legenda_descarte_segundos_atendimento = '';
	}
	if($segundos_espera_perdida && !$usuario){
		$legenda_descarte_segundos_espera_perdida = ", <strong>Descartado ligações perdidas com tempo de espera abaixo de - </strong>".$segundos_espera_perdida." segundos";
	}else{
		$legenda_descarte_segundos_espera_perdida = '';
    }

    echo "<div class=\"col-md-12\" style=\"padding: 0\">";
	echo "<legend style=\"text-align:center;\"><strong>Relatório de Indicadores Mensais</strong></legend>";
	echo "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong>Empresa - </strong>".$empresa_legenda.", <strong> Atendente - </strong>".$usuario_legenda."".$legenda_descarte_segundos_atendimento."".$legenda_descarte_segundos_espera_perdida;
    echo "</legend>";

    $filtro_atendidas = '';
    $filtro_erros = '';
    $filtro_ajuda = '';
    $filtro_perdidas = '';

    if($usuario){
        $filtro_atendidas .= " AND b.agent = 'AGENT/".$id_asterisk_usuario."'";
        $filtro_erros .= " AND id_usuario = '$usuario'";
        $filtro_ajuda .= " AND atendente = '$usuario'";
    } 
    
	if($empresa){
		$filtro_atendidas .= " AND a.data2 LIKE '$empresa%'";
        $filtro_perdidas .= " AND a.data2 LIKE '$empresa%'";
        
        $dados_contrato_empresa = DBRead('','tb_parametros',"WHERE id_asterisk = '$empresa'");
        $filtro_erros .= "AND id_contrato_plano_pessoa = '".$dados_contrato_empresa[0]['id_contrato_plano_pessoa']."'";
        $filtro_ajuda .= "AND a.id_contrato_plano_pessoa = '".$dados_contrato_empresa[0]['id_contrato_plano_pessoa']."'";

    }
    
	if($segundos_atendimento){
		$filtro_atendidas .= " AND (c.data2 >= $segundos_atendimento OR c.data4 >= $segundos_atendimento)";
    }
    
	if($segundos_espera_perdida){
		$filtro_perdidas .= " AND (SELECT SUM(c.data3) FROM queue_log c WHERE c.callid = a.callid AND (c.event = 'ABANDON' OR c.event = 'EXITWITHTIMEOUT')) >= $segundos_espera_perdida";
	}
    echo "
		<div>
			<table class=\"table table-hover dataTable\"> 
				<thead> 
                    <tr>";
                    echo "<th>Ano/Mês</th>";						
                    echo "<th>Ligações Atendidas</th>";
                    if(!$usuario){
                        echo "<th>Ligações Perdidas</th>";
                    }
                    echo "<th>TMA</th>";				
                    echo "<th>Nota Média</th>";					
                    echo "<th>Ligações com nota</th>";
                    if(!$usuario){					
                        echo "<th>TME</th>";
                    }							
                    echo "<th>Solicitações de Ajuda</th>";			
                    echo "<th>Erros/Reclamações</th>";	
                    if($usuario){					
                        echo "<th>Faltas justificadas</th>";
                        echo "<th>Absenteísmo</th>";
                        echo "<th>Pausa registro</th>";
                    }
                    echo "<th>Monitoria Telefone</th>";			
                    echo "<th>Monitoria Texto</th>";			
	echo"           </tr>
				</thead> 
				<tbody>";

    foreach ($periodo as $data_inicial) {
        $data_final = new DateTime($data_inicial->format('Y-m-d'));
        $data_final->modify('last day of this month');

        if($usuario){
            $dados_usuario = DBRead('','tb_usuario',"WHERE id_usuario = '$usuario'");
            $data_string_ponto = '
                {
                    "report": {	
                        "group_by": "",
                        "columns": "name,shift,admission_date,email",
                        "employee_id": '.$dados_usuario[0]['id_ponto'].',
                        "row_filters": "",
                        "format": "json"
                    }
                }
            ';  
            
            $result_ponto = troca_dados_curl('https://api.pontomais.com.br/external_api/v1/reports/employees', $data_string_ponto, array('Content-Type: application/json','access-token: FcogHwphYbzMk3IF1tXBmh5ypV49O-74CSf7dMxE3cMcabhbaajbefjai'));
            $data_contratacao = new DateTime(converteData(end(explode(' ', $result_ponto['data'][0][0]['data'][0]['admission_date']))));
            if($data_contratacao->format('Y-m-d') <= $data_inicial->format('Y-m-d')){
                $exibe_mes = 1;
            }else{
                $exibe_mes = 0;
            }
            if($exibe_mes){
                $data_string_ponto = '
                    {
                        "report": {				
                            "start_date": "'.$data_inicial->format('Y-m-d').'",
                            "end_date": "'.$data_final->format('Y-m-d').'",
                            "group_by": "",
                            "row_filters": "compensatory,missing_time_1h,missing_time_2h,missing_time_3h,missing_time_4h,missing_time_5h,missing_time_6h,missing_time_7h,missing_time_8h",
                            "employee_id": '.$dados_usuario[0]['id_ponto'].',
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
                            "start_date": "'.$data_inicial->format('Y-m-d').'",
                            "end_date": "'.$data_final->format('Y-m-d').'",
                            "group_by": "",
                            "columns": "name,date,missing_motive",
                            "employee_id": '.$dados_usuario[0]['id_ponto'].',
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
                
                $duracao_total_pausa_registro = 0;
                $dados_pausas = DBRead('snep','queue_agents_pause',"WHERE codigo = '".$dados_usuario[0]['id_asterisk']."' AND tipo_pausa = 4 AND data_unpause IS NOT NULL AND data_pause >= '".$data_inicial->format('Y-m-d')." 00:00:00' AND data_pause <= '".$data_final->format('Y-m-d')." 23:59:59' ORDER BY data_pause ASC");
                if($dados_pausas){
                    foreach ($dados_pausas as $conteudo_pausas) {
                        $data_pausa = strtotime($conteudo_pausas['data_pause']);
                        $data_retorno = strtotime($conteudo_pausas['data_unpause']);
                
                        $duracao_total_pausa_registro += ($data_retorno - $data_pausa);
                    }
                }

            }  
        }else{
            $exibe_mes = 1;
        }       

        if($exibe_mes){
            $qtd_atendidas = 0;
            $qtd_perdidas = 0;
            $soma_notas = 0;
            $qtd_notas = 0;
            $soma_ta_atendidas = 0;
            $soma_te_atendidas = 0;
            $soma_te_perdidas = 0;
            $qtd_ajuda = 0;
            
            $dados_atendidas = DBRead('snep','queue_log a',"INNER JOIN queue_log b ON (b.callid = a.callid AND b.event = 'CONNECT') INNER JOIN queue_log c ON (c.callid = a.callid AND (c.event = 'COMPLETEAGENT' OR c.event = 'COMPLETECALLER' OR c.event = 'BLINDTRANSFER' OR c.event = 'ATTENDEDTRANSFER')) LEFT JOIN pesquisa d ON a.callid = d.UNIQUEID WHERE a.event = 'ENTERQUEUE' $filtro_atendidas  AND b.time >= '".$data_inicial->format("Y-m-d")." 00:00:00' AND b.time <= '".$data_final->format("Y-m-d")." 23:59:59' GROUP BY b.id ORDER BY b.id", "a.queuename AS 'enterqueue_queuename', a.callid AS 'enterqueue_callid', c.event AS 'finalizacao_event', c.data1 AS 'finalizacao_data1', c.data2 AS 'finalizacao_data2', c.data3 AS 'finalizacao_data3', c.data4 AS 'finalizacao_data4', d.nota AS 'nota', (SELECT SUM(data3) FROM queue_log e WHERE e.callid = a.callid AND e.event = 'EXITWITHTIMEOUT') AS 'tempo_espera_timeout'");

            if($dados_atendidas){
                foreach ($dados_atendidas as $conteudo_atendidas) {
					if(preg_match("/'".$conteudo_atendidas['enterqueue_queuename']."'/i", $fila) || !$fila){
						if($conteudo_atendidas['finalizacao_event']=='COMPLETEAGENT' || $conteudo_atendidas['finalizacao_event']=='COMPLETECALLER'){
							$soma_ta_atendidas +=  $conteudo_atendidas['finalizacao_data2'];
							$soma_te_atendidas += $conteudo_atendidas['finalizacao_data1'];	
						}elseif(($conteudo_atendidas['finalizacao_event']=='BLINDTRANSFER' || $conteudo_atendidas['finalizacao_event']=='ATTENDEDTRANSFER') && $conteudo_atendidas['finalizacao_data1'] != 'LINK'){
							$soma_ta_atendidas +=  $conteudo_atendidas['finalizacao_data4'];
							$soma_te_atendidas += $conteudo_atendidas['finalizacao_data3'];
						}
						if($conteudo_atendidas['tempo_espera_timeout'] && $conteudo_atendidas['tempo_espera_timeout'] > 0){
							$soma_te_atendidas += $conteudo_atendidas['tempo_espera_timeout'];
						}
						if($conteudo_atendidas['nota']){
							$soma_notas += $conteudo_atendidas['nota'];
							$qtd_notas++;	
						}			
						$qtd_atendidas++;
					}
                }
            }

            $dados_perdidas = DBRead('snep','queue_log a',"INNER JOIN queue_log b ON (b.callid = a.callid AND (b.event = 'ABANDON' OR b.event = 'EXITWITHTIMEOUT')) WHERE a.event = 'ENTERQUEUE' AND a.callid NOT IN (SELECT callid FROM queue_log c WHERE c.callid = a.callid AND c.event = 'CONNECT') $filtro_perdidas AND a.time >= '".$data_inicial->format("Y-m-d")." 00:00:00' AND a.time <= '".$data_final->format("Y-m-d")." 23:59:59' GROUP BY a.callid ORDER BY a.callid", "a.queuename AS 'enterqueue_queuename', a.callid AS 'enterqueue_callid', a.data2 AS 'enterqueue_data2', (SELECT SUM(f.data3) FROM queue_log f WHERE f.callid = a.callid AND (f.event = 'ABANDON' OR f.event = 'EXITWITHTIMEOUT')) AS 'finalizacao_data3'");

            if($dados_perdidas){
                foreach ($dados_perdidas as $conteudo_perdidas) {
					if(preg_match("/'".$conteudo_perdidas['enterqueue_queuename']."'/i", $fila) || !$fila){
						$qtd_perdidas++;			
						$soma_te_perdidas += $conteudo_perdidas['finalizacao_data3'];
					}
                }
            }

            $dados_ajuda = DBRead('','tb_solicitacao_ajuda a',"WHERE a.data_inicio >= '".$data_inicial->format("Y-m-d")." 00:00:00' AND a.data_inicio <= '".$data_final->format("Y-m-d")." 23:59:59'$filtro_ajuda", "COUNT(atendente) AS 'qtd_ajuda'");
            $qtd_ajuda = $dados_ajuda[0]['qtd_ajuda'];
            
            $dados_erros = DBRead('','tb_erro_atendimento',"WHERE status = '1' AND data_cadastrado >= '".$data_inicial->format("Y-m-d")." 00:00:00' AND data_cadastrado <= '".$data_final->format("Y-m-d")." 23:59:59'$filtro_erros","COUNT(*) AS 'qtd_erros'");
            $qtd_erros = $dados_erros[0]['qtd_erros'];

            if($usuario){
                $filtro_monitoria = "AND id_usuario = '$usuario'";
            }

            $qtd_atendentes_monitoria_telefone = 0;
            $qtd_atendentes_monitoria_texto = 0;
            $monitoria_resultado_telefone = 0;
            $monitoria_resultado_texto = 0;

            $dados_monitoria = DBRead('','tb_monitoria_resultado', "WHERE data_referencia = '".$data_inicial->format("Y-m")."-01' $filtro_monitoria");

            if($dados_monitoria){
                foreach($dados_monitoria as $conteudo_monitoria){
					
					$monitoria_mes = DBRead('', 'tb_monitoria_mes', "WHERE id_monitoria_mes = '".$conteudo_monitoria['id_monitoria_mes']."'", 'tipo_monitoria');

					if ($monitoria_mes[0]['tipo_monitoria'] == 1) {
						$monitoria_resultado_telefone += $conteudo_monitoria['resultado'];
						$qtd_atendentes_monitoria_telefone++;

					} else if ($monitoria_mes[0]['tipo_monitoria'] == 2) {
						$monitoria_resultado_texto += $conteudo_monitoria['resultado'];
						$qtd_atendentes_monitoria_texto++;
					}
                }                
            }

            echo "<tr>";
                echo "<td>".$data_inicial->format("Y/m")."</td>";						
                echo "<td>$qtd_atendidas</td>";
                if(!$usuario){
                    echo "<td>$qtd_perdidas</td>";
                }
                echo "<td>".gmdate("H:i:s", $soma_ta_atendidas/($qtd_atendidas == 0 ? 1 : $qtd_atendidas))."</td>";				
                echo "<td>".sprintf("%01.2f", round($soma_notas/($qtd_notas == 0 ? 1 : $qtd_notas), 2))."</td>";				
                echo "<td>".sprintf("%01.2f", round($qtd_notas*100/($qtd_atendidas == 0 ? 1 : $qtd_atendidas), 2))."%</td>";	
                if(!$usuario){
                    echo "<td>".gmdate("H:i:s", $soma_te_atendidas/($qtd_atendidas == 0 ? 1 : $qtd_atendidas))."</td>";
                }		
                echo "<td>$qtd_ajuda</td>";						
                echo "<td>$qtd_erros</td>";
                if($usuario){
                    echo '
                    <td>'.$qtd_faltas_justificadas.'</td>
                    <td style="cursor:help;" data-toggle="tooltip" data-placement="top" data-container="body" data-html="true" title="Previsto (sem intervalo): '.$horas_previstas.'<br>Ausência: '.$horas_faltantes.'">'.$absenteismo.'%</td>
                    <td>'.converteSegundosHoras($duracao_total_pausa_registro).'</td>
                    ';
                }					
                echo "<td>".sprintf("%01.2f", $monitoria_resultado_telefone/($qtd_atendentes_monitoria_telefone == 0 ? 1 : $qtd_atendentes_monitoria_telefone))."%</td>";
				echo "<td>".sprintf("%01.2f", $monitoria_resultado_texto/($qtd_atendentes_monitoria_texto == 0 ? 1 : $qtd_atendentes_monitoria_texto))."%</td>";
            echo "</tr>";
        }
    }

    echo "
                </tbody>";
    echo "  </table>
        </div>
    ";

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
}
?>