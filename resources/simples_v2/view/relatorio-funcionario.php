<?php
require_once(__DIR__."/../class/System.php");
$liderados = busca_liderados($_SESSION['id_usuario']);

$tipo_relatorio = (!empty($_POST['tipo_relatorio'])) ? $_POST['tipo_relatorio'] : 1;
$data_referencia = getDataHora();
$arrayData = explode(" ", $data_referencia);
$mes_referencia = explode("-", $arrayData[0]);
$mes_atual = date("m", strtotime("-1 month"));
$ano_atual = date("Y");
$mes_entrada = (!empty($_POST['mes_entrada'])) ? $_POST['mes_entrada'] : '00';
$mes_inicio = (!empty($_POST['mes_inicio'])) ? $_POST['mes_inicio'] : $mes_atual;
$ano_inicio = (!empty($_POST['ano_inicio'])) ? $_POST['ano_inicio'] : $ano_atual;
$mes_fim = (!empty($_POST['mes_fim'])) ? $_POST['mes_fim'] : $mes_referencia[1];
$ano_fim = (!empty($_POST['ano_fim'])) ? $_POST['ano_fim'] : $mes_referencia[0];
$dia = (!empty($_POST['dia'])) ? $_POST['dia'] : converteData($arrayData[0]);
$gerar = (!empty($_POST['gerar'])) ? 1 : 0;
$id_usuario = $_SESSION['id_usuario'];
$dados = DBRead('', 'tb_usuario', "WHERE id_usuario = '$id_usuario'");
$perfil_sistema = $dados[0]['id_perfil_sistema'];
$id_perfil_sistema = (!empty($_POST['id_perfil_sistema'])) ? $_POST['id_perfil_sistema'] : '';
$formato = (!empty($_POST['formato'])) ? $_POST['formato'] : '';
$escolaridade = (!empty($_POST['escolaridade'])) ? $_POST['escolaridade'] : '';

if ($gerar) {
	$collapse = '';
	$collapse_icon = 'plus';
} else {
	$collapse = 'in';
	$collapse_icon = 'minus';
}

if ($tipo_relatorio == 1) {
	$display_row_periodo = '';
	$display_row_mes_ano_inicio = 'style="display: none;"';
	$display_row_mes_ano_fim = 'style="display: none;"';
	$display_row_dia = 'style="display: none;"';
	$display_row_perfil_sistema = '';
	$display_row_formato = '';	
	$display_row_escolaridade = '';	
	$display_row_mes_entrada = '';

} else if ($tipo_relatorio == 2) {
	$display_row_periodo = '';
	$display_row_mes_ano_inicio = '';
	$display_row_mes_ano_fim = '';
	$display_row_dia = 'style="display: none;"';
	$display_row_perfil_sistema = 'style="display: none;"';
	$display_row_formato = 'style="display: none;"';	
	$display_row_escolaridade = 'style="display: none;"';
	$display_row_mes_entrada = 'style="display: none;"';	

} else if ($tipo_relatorio == 3) {
	$display_row_periodo = '';
	$display_row_mes_ano_inicio = '';
	$display_row_mes_ano_fim = '';
	$display_row_dia = 'style="display: none;"';
	$display_row_perfil_sistema = 'style="display: none;"';
	$display_row_formato = 'style="display: none;"';	
	$display_row_escolaridade = 'style="display: none;"';
	$display_row_mes_entrada = 'style="display: none;"';	

} else if ($tipo_relatorio == 4) {
	$display_row_periodo = '';
	$display_row_mes_ano_inicio = 'style="display: none;"';
	$display_row_mes_ano_fim = 'style="display: none;"';
	$display_row_dia = '';
	$display_row_perfil_sistema = 'style="display: none;"';
	$display_row_formato = 'style="display: none;"';	
	$display_row_escolaridade = 'style="display: none;"';
	$display_row_mes_entrada = 'style="display: none;"';	

} else if ($tipo_relatorio == 5) {
	$display_row_periodo = '';
	$display_row_mes_ano_inicio = 'style="display: none;"';
	$display_row_mes_ano_fim = 'style="display: none;"';
	$display_row_dia = '';
	$display_row_perfil_sistema = 'style="display: none;"';
	$display_row_formato = 'style="display: none;"';	
	$display_row_escolaridade = 'style="display: none;"';
	$display_row_mes_entrada = 'style="display: none;"';
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
	                    <h3 class="panel-title text-left pull-left" style="margin-top: 2px;">Relatório - Funcionários:</h3>
	                    <div class="panel-title text-right pull-right"><button data-toggle="collapse" data-target="#accordionRelatorio" class="btn btn-xs btn-info" type="button" title="Visualizar filtros"><i id="i_collapse" class="fa fa-<?=$collapse_icon?>"></i></button></div>
	                </div>
	                <div id="accordionRelatorio" class="panel-collapse collapse <?=$collapse?>">
	                	<div class="panel-body">
                            <div class="row">
                				<div class="col-md-12">
                					<div class="form-group">
								        <label for="">Tipo de Relatório:</label>
								        <select name="tipo_relatorio" id="tipo_relatorio" class="form-control input-sm">
								        	<option value="1" <?php if($tipo_relatorio == '1'){ echo 'selected';}?>>Tempo na empresa</option>
											<option value="2" <?php if($tipo_relatorio == '2'){ echo 'selected';}?>>Entradas e saídas</option>
											<option value="3" <?php if($tipo_relatorio == '3'){ echo 'selected';}?>>Desligamentos</option>
											<option value="4" <?php if($tipo_relatorio == '4'){ echo 'selected';}?>>Geral</option>
											<option value="5" <?php if($tipo_relatorio == '5'){ echo 'selected';}?>>Cotas</option>
								        </select>
								    </div>
                				</div>
                			</div>
                            <div class="row" id="row_dia" <?= $display_row_dia ?>>
								<div class="col-md-12">
									<div class="form-group" >
								        <label>*Data:</label>
								        <input type="text" class="form-control date calendar input-sm" name="dia" value="<?=$dia?>">
								    </div>
								</div>
							</div>
							<!-- row periodo mes-->
							<div class="row" id="row_mes_ano_inicio" <?= $display_row_mes_ano_inicio ?>>
								<div class="col-md-6">
									<div class="form-group">
										<label>Mês início:</label>
										<select class="form-control input-sm" name="mes_inicio" id="mes_inicio" required onChange="selectFormulario()">
											<?php
											$sel_mes_inicio[$mes_inicio] = 'selected';
											?>
											<option value="01" <?= $sel_mes_inicio['01'] ?>>Janeiro</option>
											<option value="02" <?= $sel_mes_inicio['02'] ?>>Fevereiro</option>
											<option value="03" <?= $sel_mes_inicio['03'] ?>>Março</option>
											<option value="04" <?= $sel_mes_inicio['04'] ?>>Abril</option>
											<option value="05" <?= $sel_mes_inicio['05'] ?>>Maio</option>
											<option value="06" <?= $sel_mes_inicio['06'] ?>>Junho</option>
											<option value="07" <?= $sel_mes_inicio['07'] ?>>Julho</option>
											<option value="08" <?= $sel_mes_inicio['08'] ?>>Agosto</option>
											<option value="09" <?= $sel_mes_inicio['09'] ?>>Setembro</option>
											<option value="10" <?= $sel_mes_inicio['10'] ?>>Outubro</option>
											<option value="11" <?= $sel_mes_inicio['11'] ?>>Novembro</option>
											<option value="12" <?= $sel_mes_inicio['12'] ?>>Dezembro</option>
										</select>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label>*Ano início:</label>
										<select class="form-control input-sm" name="ano_inicio" id="ano_inicio" >
											<?php
											$sel_ano_inicio[$ano_inicio] = 'selected';
											?>
											<option value="2012" <?= $sel_ano_inicio['2012'] ?>>2012</option>
											<option value="2013" <?= $sel_ano_inicio['2013'] ?>>2013</option>
											<option value="2014" <?= $sel_ano_inicio['2014'] ?>>2014</option>
											<option value="2015" <?= $sel_ano_inicio['2015'] ?>>2015</option>
											<option value="2016" <?= $sel_ano_inicio['2016'] ?>>2016</option>
											<option value="2017" <?= $sel_ano_inicio['2017'] ?>>2017</option>
											<option value="2018" <?= $sel_ano_inicio['2018'] ?>>2018</option>
											<option value="2019" <?= $sel_ano_inicio['2019'] ?>>2019</option>
											<option value="2020" <?= $sel_ano_inicio['2020'] ?>>2020</option>
											<option value="2021" <?= $sel_ano_inicio['2021'] ?>>2021</option>
											<option value="2022" <?= $sel_ano_inicio['2022'] ?>>2022</option>
											<option value="2023" <?= $sel_ano_inicio['2023'] ?>>2023</option>
											<option value="2024" <?= $sel_ano_inicio['2024'] ?>>2024</option>
											<option value="2025" <?= $sel_ano_inicio['2025'] ?>>2025</option>
										</select>
									</div>
								</div>
							</div>
							<!-- end row periodo -->
							<!-- row periodo mes-->
							<div class="row" id="row_mes_ano_fim" <?= $display_row_mes_ano_fim ?>>
								<div class="col-md-6">
									<div class="form-group">
										<label>Mês fim:</label>
										<select class="form-control input-sm" name="mes_fim" id="mes_fim" required>
											<?php
											$sel_mes_fim[$mes_fim] = 'selected';
											?>
											<option value="01" <?= $sel_mes_fim['01'] ?>>Janeiro</option>
											<option value="02" <?= $sel_mes_fim['02'] ?>>Fevereiro</option>
											<option value="03" <?= $sel_mes_fim['03'] ?>>Março</option>
											<option value="04" <?= $sel_mes_fim['04'] ?>>Abril</option>
											<option value="05" <?= $sel_mes_fim['05'] ?>>Maio</option>
											<option value="06" <?= $sel_mes_fim['06'] ?>>Junho</option>
											<option value="07" <?= $sel_mes_fim['07'] ?>>Julho</option>
											<option value="08" <?= $sel_mes_fim['08'] ?>>Agosto</option>
											<option value="09" <?= $sel_mes_fim['09'] ?>>Setembro</option>
											<option value="10" <?= $sel_mes_fim['10'] ?>>Outubro</option>
											<option value="11" <?= $sel_mes_fim['11'] ?>>Novembro</option>
											<option value="12" <?= $sel_mes_fim['12'] ?>>Dezembro</option>
										</select>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label>*Ano fim:</label>
										<select class="form-control input-sm" name="ano_fim" id="ano_fim" >
											<?php
											$sel_ano_fim[$ano_fim] = 'selected';
											?>
											<option value="2012" <?= $sel_ano_fim['2012'] ?>>2012</option>
											<option value="2013" <?= $sel_ano_fim['2013'] ?>>2013</option>
											<option value="2014" <?= $sel_ano_fim['2014'] ?>>2014</option>
											<option value="2015" <?= $sel_ano_fim['2015'] ?>>2015</option>
											<option value="2016" <?= $sel_ano_fim['2016'] ?>>2016</option>
											<option value="2017" <?= $sel_ano_fim['2017'] ?>>2017</option>
											<option value="2018" <?= $sel_ano_fim['2018'] ?>>2018</option>
											<option value="2019" <?= $sel_ano_fim['2019'] ?>>2019</option>
											<option value="2020" <?= $sel_ano_fim['2020'] ?>>2020</option>
											<option value="2021" <?= $sel_ano_fim['2021'] ?>>2021</option>
											<option value="2022" <?= $sel_ano_fim['2022'] ?>>2022</option>
											<option value="2023" <?= $sel_ano_fim['2023'] ?>>2023</option>
											<option value="2024" <?= $sel_ano_fim['2024'] ?>>2024</option>
											<option value="2025" <?= $sel_ano_fim['2025'] ?>>2025</option>
										</select>
									</div>
								</div>
							</div>
							<!-- end row periodo -->
							


							<div class="row" id="row_mes_entrada" <?= $display_row_mes_entrada ?>>
								<div class="col-md-12">
									<div class="form-group">
										<label>Mês de entrada:</label>
										<select class="form-control input-sm" name="mes_entrada" id="mes_entrada" >
											<?php
											$sel_mes_entrada[$mes_entrada] = 'selected';
											?>
											<option value="00" <?= $sel_mes_entrada['00'] ?>>Todos</option>
											<option value="01" <?= $sel_mes_entrada['01'] ?>>Janeiro</option>
											<option value="02" <?= $sel_mes_entrada['02'] ?>>Fevereiro</option>
											<option value="03" <?= $sel_mes_entrada['03'] ?>>Março</option>
											<option value="04" <?= $sel_mes_entrada['04'] ?>>Abril</option>
											<option value="05" <?= $sel_mes_entrada['05'] ?>>Maio</option>
											<option value="06" <?= $sel_mes_entrada['06'] ?>>Junho</option>
											<option value="07" <?= $sel_mes_entrada['07'] ?>>Julho</option>
											<option value="08" <?= $sel_mes_entrada['08'] ?>>Agosto</option>
											<option value="09" <?= $sel_mes_entrada['09'] ?>>Setembro</option>
											<option value="10" <?= $sel_mes_entrada['10'] ?>>Outubro</option>
											<option value="11" <?= $sel_mes_entrada['11'] ?>>Novembro</option>
											<option value="12" <?= $sel_mes_entrada['12'] ?>>Dezembro</option>
										</select>
									</div>
								</div>
							</div>


							<!-- end row mes de entrada -->
							<div class="row" id="row_perfil_sistema" <?=$display_row_perfil_sistema?>>
								<div class="col-md-12">
									<div class="form-group">
								        <label>Perfil do Sistema:</label>
								        <select name="id_perfil_sistema[]" class="form-control input-sm" multiple="multiple" size=15>
								            <?php
												$sel_perfil_sistema[$id_perfil_sistema] = 'selected';
								            	$dados_perfil_sistema = DBRead('','tb_perfil_sistema',"WHERE status = 1 ORDER BY nome ASC");
								            	if($dados_perfil_sistema){
								            		foreach ($dados_perfil_sistema as $conteudo_perfil_sistema) {
														$selected = '';
														if(in_array($conteudo_perfil_sistema['id_perfil_sistema'], $id_perfil_sistema)){
															$selected = 'selected';
														}
								            			echo "<option value='".$conteudo_perfil_sistema['id_perfil_sistema']."' ".$selected.">".$conteudo_perfil_sistema['nome']."</option>";
								            		}
								            	}
								            ?>
								        </select>
								    </div>
								</div>
							</div>

							<div class="row" id="row_formato" <?=$display_row_formato?>>
                				<div class="col-md-12">
                					<div class="form-group">
								        <label>*Formato:</label>
								        <select name="formato" id="formato" class="form-control input-sm">
											<option value="" <?php if($formato == ''){ echo 'selected';}?>>Todos</option>
											<option value="1" <?php if($formato == '1'){ echo 'selected';}?>>Efetivo</option>
											<option value="2" <?php if($formato == '2'){ echo 'selected';}?>>Estágio</option>
											<option value="3" <?php if($formato == '3'){ echo 'selected';}?>>Jovem aprendiz</option>
											<option value="4" <?php if($formato == '4'){ echo 'selected';}?>>PCD</option>
								        </select>
								    </div>
                				</div>
                			</div>

							<div class="row" id="row_escolaridade" <?=$display_row_escolaridade?>>
                				<div class="col-md-12">
                					<div class="form-group">
								        <label>*Escolaridade:</label>
								        <select name="escolaridade" id="escolaridade" class="form-control input-sm">
											<option value="" <?php if($escolaridade == ''){ echo 'selected';}?>>Todas</option>
											<option value="1" <?php if($escolaridade == '1'){ echo 'selected';}?>>Primeiro grau completo</option>
											<option value="2" <?php if($escolaridade == '2'){ echo 'selected';}?>>Primeiro grau incompleto</option>
											<option value="3" <?php if($escolaridade == '3'){ echo 'selected';}?>>Segundo grau completo</option>
											<option value="4" <?php if($escolaridade == '4'){ echo 'selected';}?>>Segundo grau incompleto</option>
											<option value="5" <?php if($escolaridade == '5'){ echo 'selected';}?>>Superior completo</option>
											<option value="6" <?php if($escolaridade == '6'){ echo 'selected';}?>>Superior incompleto</option>
											<option value="7" <?php if($escolaridade == '7'){ echo 'selected';}?>>Pós-graduação em andamento</option>
											<option value="8" <?php if($escolaridade == '8'){ echo 'selected';}?>>Pós-graduação completa</option>
											<option value="9" <?php if($escolaridade == '9'){ echo 'selected';}?>>Mestrando</option>
											<option value="10" <?php if($escolaridade == '10'){ echo 'selected';}?>>Mestre</option>
											<option value="11" <?php if($escolaridade == '11'){ echo 'selected';}?>>Doutorando</option>
											<option value="12" <?php if($escolaridade == '12'){ echo 'selected';}?>>Doutor</option>
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
                relatorio_tempo_empresa($id_perfil_sistema, $formato, $escolaridade, $mes_entrada);

			} else if ($tipo_relatorio == 2) {
				relatorio_entrada_saida($mes_inicio, $ano_inicio, $mes_fim, $ano_fim);

			} else if ($tipo_relatorio == 3) {
				relatorio_desligamentos($mes_inicio, $ano_inicio, $mes_fim, $ano_fim);
			
			} else if ($tipo_relatorio == 4) {
				relatorio_geral($dia);
	
			} else if ($tipo_relatorio == 5) {
				relatorio_cotas($dia);
	
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
			$('#row_mes_ano_inicio').hide();
			$('#row_mes_ano_fim').hide();
			$('#row_dia').hide();
			$('#row_perfil_sistema').show();
			$('#row_formato').show();
			$('#row_escolaridade').show();
			$('#row_mes_entrada').show();


		} else if (tipo_relatorio == 2) {
			$('#row_mes_ano_inicio').show();
			$('#row_mes_ano_fim').show();
			$('#row_dia').hide();
			$('#row_perfil_sistema').hide();
			$('#row_formato').hide();
			$('#row_escolaridade').hide();
			$('#row_mes_entrada').hide();

		} else if (tipo_relatorio == 3) {
			$('#row_mes_ano_inicio').show();
			$('#row_mes_ano_fim').show();
			$('#row_dia').hide();
			$('#row_perfil_sistema').hide();
			$('#row_formato').hide();
			$('#row_escolaridade').hide();
			$('#row_mes_entrada').hide();

		} else if (tipo_relatorio == 4) {
			$('#row_mes_ano_inicio').hide();
			$('#row_mes_ano_fim').hide();
			$('#row_dia').show();
			$('#row_perfil_sistema').hide();
			$('#row_formato').hide();
			$('#row_escolaridade').hide();
			$('#row_mes_entrada').hide();

		} else if (tipo_relatorio == 5) {
			$('#row_mes_ano_inicio').hide();
			$('#row_mes_ano_fim').hide();
			$('#row_dia').show();
			$('#row_perfil_sistema').hide();
			$('#row_formato').hide();
			$('#row_escolaridade').hide();
			$('#row_mes_entrada').hide();
		}
    });

	$('#accordionRelatorio').on('shown.bs.collapse', function(){
       $("#i_collapse").removeClass("fa fa-plus").addClass("fa fa-minus");
    });

    $('#accordionRelatorio').on('hidden.bs.collapse', function(){
       $("#i_collapse").removeClass("fa fa-minus").addClass("fa fa-plus");
    });

    $(document).on('submit', 'form', function(){		
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
function relatorio_tempo_empresa($id_perfil_sistema, $formato, $escolaridade, $mes_entrada){
    $data_hora = converteDataHora(getDataHora());

	if ($mes_entrada) {

		if($mes_entrada > 00){
			$filtro_entrada = "AND b.data_inicio LIKE '%-$mes_entrada-%'";
		}else{
			$filtro_entrada = "";
		}
		$array_mes_entrada = array(
			'01' => 'Janeiro',
			'02' => 'Fevereiro',
			'03' => 'Março',
			'04' => 'Abril',
			'05' => 'Maio',
			'06' => 'Junho',
			'07' => 'Julho',
			'08' => 'Agosto',
			'09' => 'Setembro',
			'10' => 'Outubro',
			'11' => 'Novembro',
			'12' => 'Dezembro',
			'00' => 'Todos',
		);
		$legenda_entrada = $array_mes_entrada[$mes_entrada];

	}

	$filtro_perfil = '';

	if($id_perfil_sistema){
		
		$cont = 0;
		$filtro_perfil = 'AND (';
		$legenda_perfil = '';
		foreach($id_perfil_sistema as $conteudo_perfil_sistema){
			if($cont == 0){
				$filtro_perfil .= 'e.id_perfil_sistema = "'.$conteudo_perfil_sistema.'" ';
			}else{
				$filtro_perfil .= 'OR e.id_perfil_sistema = "'.$conteudo_perfil_sistema.'" ';
			}
			$cont++;

			$dados_perfil_sistema = DBRead('','tb_perfil_sistema',"WHERE id_perfil_sistema = '".$conteudo_perfil_sistema."' ", "nome");
			$legenda_perfil .= $dados_perfil_sistema[0]['nome'].", ";
		}

		$legenda_perfil = substr($legenda_perfil, 0, -2);
		$filtro_perfil .= ')';

	}else{
		$legenda_perfil ='Todos';
	}

	$filtro_formato = '';
	if($formato){
		if($formato == 1){
			$legenda_formato = 'Efetivo';
		}else if ($formato == 2){
			$legenda_formato = 'Estágio';
		}else if ($formato == 3){
			$legenda_formato = 'Jovem aprendiz';
		}else if ($formato == 4){
			$legenda_formato = 'PCD';
		}
		$filtro_formato = 'AND b.formato = "'.$formato.'" ';
		
	}else{
		$legenda_formato ='Todos';
	}

	$filtro_escolaridade = '';
	if($escolaridade){
		if($escolaridade == 1){
			$legenda_escolaridade = 'Primeiro grau completo';
		}else if ($escolaridade == 2){
			$legenda_escolaridade = 'Primeiro grau incompleto';
		}else if ($escolaridade == 3){
			$legenda_escolaridade = 'Segundo grau completo';
		}else if ($escolaridade == 4){
			$legenda_escolaridade = 'Segundo grau incompleto';
		}else if ($escolaridade == 5){
			$legenda_escolaridade = 'Superior completo';
		}else if ($escolaridade == 6){
			$legenda_escolaridade = 'Superior incompleto';
		}else if ($escolaridade == 7){
			$legenda_escolaridade = 'Pós-graduação em andamento';
		}else if ($escolaridade == 8){
			$legenda_escolaridade = 'Pós-graduação completa';
		}else if ($escolaridade == 9){
			$legenda_escolaridade = 'Mestrando';
		}else if ($escolaridade == 10){
			$legenda_escolaridade = 'Mestre';
		}else if ($escolaridade == 11){
			$legenda_escolaridade = 'Doutorando';
		}else if ($escolaridade == 12){
			$legenda_escolaridade = 'Doutor';
		}
		$filtro_escolaridade = 'AND b.escolaridade = "'.$escolaridade.'" ';
	}else{
		$legenda_escolaridade ='Todas';
	}

	echo "<div class=\"col-md-8 col-md-offset-2\" style=\"padding: 0\">";
	echo "<legend style=\"text-align:center;\"><strong>Relatório de Funcionários - Tempo de empresa</strong><br><span style=\"font-size: 14px;\"><strong>Gerado em:</strong> $data_hora</span></legend>";
	echo "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong> Formato - </strong>".$legenda_formato.", <strong> Escolaridade - </strong>".$legenda_escolaridade.", <strong> Perfil do Sistema - </strong>".$legenda_perfil.", <strong>Mês de entrada - </strong>".$legenda_entrada."</legend>";
	

    $dados = DBRead('','tb_funcionario a',"INNER JOIN tb_funcionario_periodo b ON a.id_funcionario = b.id_funcionario INNER JOIN tb_usuario c ON a.id_usuario = c.id_usuario INNER JOIN tb_pessoa d ON c.id_pessoa = d.id_pessoa INNER JOIN tb_perfil_sistema e ON c.id_perfil_sistema = e.id_perfil_sistema WHERE b.data_fim IS NULL $filtro_perfil $filtro_formato $filtro_escolaridade $filtro_entrada ORDER BY d.nome ASC", 'b.*, d.nome, e.nome AS nome_perfil');

    if ($dados) {
        echo "<div class='row'>";
        	echo "<div class='col-md-12'>";
            echo "<table class='table table-hover dataTable' style='font-size='14px'>";
			echo "<thead>";
				echo "<tr>";
					echo "<th>Nome</th>";
					echo "<th>Perfil do Sistema</th>";
					echo "<th>Escolaridade</th>";
					echo "<th>Formato</th>";
					echo "<th>Data de início</th>";
					echo "<th>Tempo (em meses)</th>";
					echo "<th>Tempo (Anos / Meses)</th>";
				echo "</tr>";
			echo "</thead>";
			echo "<tbody>";

		$total_meses = 0;
		$legenda_total = 0;
		$contador_funcionario = 0;
		foreach($dados as $conteudo){

            if ($conteudo['escolaridade'] == 1) {
                $escolaridade = 'Primeiro grau completo';

            } else if ($conteudo['escolaridade'] == 2) {
                $escolaridade = 'Primeiro grau incompleto';

            } else if ($conteudo['escolaridade'] == 3) {
                $escolaridade = 'Segundo grau completo';

            } else if ($conteudo['escolaridade'] == 4) {
                $escolaridade = 'Segundo grau incompleto';

            } else if ($conteudo['escolaridade'] == 5) {
                $escolaridade = 'Superior completo';

            } else if ($conteudo['escolaridade'] == 6) {
                $escolaridade = 'Superior incompleto';

            } else if ($conteudo['escolaridade'] == 7) {
                $escolaridade = 'Pós-graduação em andamento';

            } else if ($conteudo['escolaridade'] == 8) {
                $escolaridade = 'Pós-graduação completa';

            } else if ($conteudo['escolaridade'] == 9) {
                $escolaridade = 'Mestrando';

            } else if ($conteudo['escolaridade'] == 10) {
                $escolaridade = 'Mestre';

            } else if ($conteudo['escolaridade'] == 11) {
                $escolaridade = 'Doutorando';

            }  else if ($conteudo['escolaridade'] == 12) {
                $escolaridade = 'Doutor';

            } else {
                $escolaridade = 'N/D';
            }

			if ($conteudo['formato'] == 1) {
				$formato = 'Efetivo';

			} else if ($conteudo['formato'] == 2) {
				$formato = 'Estágio';

			} else if ($conteudo['formato'] == 3) {
				$formato = 'Jovem aprendiz';

			} else if ($conteudo['formato'] == 4) {
				$formato = 'PCD';
			}

			$data_hoje = getDataHora();
			$data_hoje = explode(' ', $data_hoje);

			$data_inicio = new DateTime($conteudo['data_inicio']);
			$data_fim = new DateTime($data_hoje[0]);

			$dateInterval = $data_inicio->diff($data_fim);
			$tempo_meses = $dateInterval->days/30;
			$anos = $dateInterval->y;
			$meses = $dateInterval->m;

			$total_meses += $tempo_meses;
			
			if ($anos > 0) {
				$legenda = $anos.' a '.$meses.' m';
				$legenda_total = ($anos*12) + $meses + $legenda_total;
			} else {
				$legenda = $meses.' m';
				$legenda_total = $meses + $legenda_total;
			}

			$contador_funcionario++;
		
			echo "<tr>";
				echo "<td>".$conteudo['nome']."</td>";
				echo "<td>".$conteudo['nome_perfil']."</td>";
				echo "<td>".$escolaridade."</td>";
				echo "<td>".$formato."</td>";
				echo "<td>".converteData($conteudo['data_inicio'])."</td>";
				echo "<td>".sprintf("%01.1f", $tempo_meses)."</td>";
				echo "<td>".$legenda."</td>";
			echo "</tr>";
		}

		$legenda_total_1 = $legenda_total/$contador_funcionario;
		$legenda_total_ano = $legenda_total_1/12;
		$legenda_total_ano = explode(".", $legenda_total_ano);
		$legenda_total_ano = $legenda_total_ano[0];
		$legenda_total_mes = $legenda_total%12;

		if($legenda_total_ano > 0){
			$legenda_media = $legenda_total_ano.' a '.$legenda_total_mes.' m';
		}else {
			$legenda_media = $legenda_total_mes.' m';
		}
		
		$legenda_total_ano = $legenda_total/12;
		$legenda_total_ano = explode(".", $legenda_total_ano);
		$legenda_total_ano = $legenda_total_ano[0];
		$legenda_total_mes = $legenda_total%12;

		if($legenda_total_ano > 0){
			$legenda_total = $legenda_total_ano.' a '.$legenda_total_mes.' m';
		}else {
			$legenda_total = $legenda_total_mes.' m';
		}	

			echo "</tbody>";
			echo "<tfoot>";
				echo "<tr>";
					echo "<th>Totais:</th>";
					echo "<th></th>";
					echo "<th></th>";
					echo "<th></th>";
					echo "<th></th>";
					echo "<th>".sprintf("%01.1f", $total_meses)."</th>";
					echo "<th>".$legenda_total."</th>";
				echo "</tr>";
				echo "<tr>";
					echo "<th>Médias:</th>";
					echo "<th></th>";
					echo "<th></th>";
					echo "<th></th>";
					echo "<th></th>";
					echo "<th>".sprintf("%01.1f", $total_meses/$contador_funcionario)."</th>";
					echo "<th>".$legenda_media."</th>";
				echo "</tr>";
			echo "</tfoot>";
		echo "</table>";
		echo "</div>";
		echo "</div>";

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
			});
		</script>			
		";

    } else {
        echo "<table class='table table-bordered'>";
            echo "<tbody>";
                echo "<tr>";
                    echo "<td class='text-center'><h4>Não foram encontrados resultados!</h4></td>";
                echo "</tr>";
            echo "</tbody>";
        echo "</table>";
    }
    echo "</div>";
}

function relatorio_entrada_saida($mes_inicio, $ano_inicio, $mes_fim, $ano_fim)
{
    $data_hora = converteDataHora(getDataHora());
	$data_inicio = '01/'.$mes_inicio.'/'.$ano_inicio;
	$data = $ano_inicio.'-'.$mes_inicio.'-01';
	$data = new DateTime($data);
	$data_inicio = $data->modify('first day of this month')->format('Y-m-d');
	$data_fim = '01/'.$mes_fim.'/'.$ano_fim;
	$data = $ano_fim.'-'.$mes_fim.'-01';
	$data = new DateTime($data);
	$data_fim = $data->modify('last day of this month')->format('Y-m-d');
	
	if ($data_inicio && $data_fim) {
	    $periodo_amostra ="<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> De ".converteData($data_inicio)." até ".converteData($data_fim)."</span>";

	} else {
	    $periodo_amostra = "";
	}

	echo "<div class=\"col-md-12\" style=\"padding: 0\">";
	echo "<legend style=\"text-align:center;\"><strong>Relatório de Funcionários - Entradas e saídas</strong><br><span style=\"font-size: 14px;\"><strong>Gerado em:</strong> $data_hora</span></legend>";
    echo "<legend style=\"text-align:center;\">$periodo_amostra</legend>";
	
	if ($data_inicio && $data_fim) {
	 	$filtro_data = " AND ((a.data_inicio >= '$data_inicio' AND a.data_inicio <= '$data_fim') OR (a.data_fim >= '$data_inicio' AND a.data_fim <= '$data_fim'))  ";
     }

	$dados = DBRead('', 'tb_funcionario_periodo a', "INNER JOIN tb_funcionario b ON a.id_funcionario = b.id_funcionario INNER JOIN tb_usuario c ON b.id_usuario = c.id_usuario INNER JOIN tb_pessoa d ON c.id_pessoa = d.id_pessoa WHERE a.id_funcionario_periodo $filtro_data ORDER BY a.data_inicio ASC, d.nome ASC", 'a.*, b.id_usuario, d.nome, d.sexo');

    if ($dados) {

		$array_mes = array();
		$incremento = 2;

		$cont_entradas = 0;

		$cont_entrada_efetivo = 0;
		$cont_entrada_estagio = 0;
		$cont_entrada_jovem_aprendiz = 0;
		$cont_entrada_pcd = 0;
		$cont_entrada_estagio_pcd = 0;
		$cont_entrada_terceirizado = 0;
		$cont_entrada_mulher = 0;
		$cont_entrada_homem = 0;

		$cont_antigo_efetivo = 0;
		$cont_antigo_estagio = 0;
		$cont_antigo_jovem_aprendiz = 0;
		$cont_antigo_pcd = 0;
		$cont_antigo_estagio_pcd = 0;
		$cont_antigo_terceirizado = 0;
		$cont_antigo_homens = 0;
		$cont_antigo_mulheres = 0;

		$total_entrada_mes = 0;

		$funcionarios_antigos = '';
		$array_data_fim = array();

		foreach ($dados as $conteudo) {
			
			//data inicio
			$mes = explode('-', $conteudo['data_inicio']);
			$data_referencia = $mes[1].'/'.$mes[0];

			$mes = $mes[0].'-'.$mes[1].'-01';
			$data_mes = new DateTime($mes);
			$data_mes_inicio = $data_mes->modify('first day of this month')->format('Y-m-d');
			$data_mes_fim = $data_mes->modify('last day of this month')->format('Y-m-d');
			//data inicio

			if ($conteudo['data_fim'] != '') {
				$array_data_fim[] = $conteudo['data_fim'];
			}

			if (!isset($data_referencia, $array_mes['mes_referencia'][$data_referencia])) {

				$funcionarios_antigos = DBRead('', 'tb_funcionario_periodo', "WHERE data_inicio <= '$data_mes_inicio' AND (data_fim IS NULL OR data_fim >= '$data_mes_inicio')", 'count(*) as total');

				$mulheres = DBRead('', 'tb_funcionario_periodo a', "INNER JOIN tb_funcionario b ON a.id_funcionario = b.id_funcionario INNER JOIN tb_usuario c ON b.id_usuario = c.id_usuario INNER JOIN tb_pessoa d ON c.id_pessoa = d.id_pessoa WHERE a.data_inicio <= '$data_mes_inicio' AND (a.data_fim IS NULL OR a.data_fim >= '$data_mes_inicio') AND d.sexo = 'f' ", 'count(*) as total_mulheres');

				$homens = DBRead('', 'tb_funcionario_periodo a', "INNER JOIN tb_funcionario b ON a.id_funcionario = b.id_funcionario INNER JOIN tb_usuario c ON b.id_usuario = c.id_usuario INNER JOIN tb_pessoa d ON c.id_pessoa = d.id_pessoa WHERE a.data_inicio <= '$data_mes_inicio' AND (a.data_fim IS NULL OR a.data_fim >= '$data_mes_inicio') AND d.sexo = 'm' ", 'count(*) as total_homens');

				$saidas =  DBRead('', 'tb_funcionario_periodo a', "WHERE a.id_funcionario_periodo AND (a.data_fim >= '$data_mes_inicio' AND a.data_fim <= '$data_mes_fim') ORDER BY a.data_inicio ASC", 'count(*) as total_saida_mes');

				$saidas_homens =  DBRead('', 'tb_funcionario_periodo a', "INNER JOIN tb_funcionario b ON a.id_funcionario = b.id_funcionario INNER JOIN tb_usuario c ON b.id_usuario = c.id_usuario INNER JOIN tb_pessoa d ON c.id_pessoa = d.id_pessoa WHERE a.id_funcionario_periodo AND (a.data_fim >= '$data_mes_inicio' AND a.data_fim <= '$data_mes_fim') AND d.sexo = 'm' ORDER BY a.data_inicio ASC", 'count(*) as total_saida_homens');

				$saidas_mulheres =  DBRead('', 'tb_funcionario_periodo a', "INNER JOIN tb_funcionario b ON a.id_funcionario = b.id_funcionario INNER JOIN tb_usuario c ON b.id_usuario = c.id_usuario INNER JOIN tb_pessoa d ON c.id_pessoa = d.id_pessoa WHERE a.id_funcionario_periodo AND (a.data_fim >= '$data_mes_inicio' AND a.data_fim <= '$data_mes_fim') AND d.sexo = 'f' ORDER BY a.data_inicio ASC", 'count(*) as total_saida_mulheres');

				$total_entrada_mes = 0;
				$cont_entrada_efetivo = 0;
				$cont_entrada_estagio = 0;
				$cont_entrada_jovem_aprendiz = 0;
				$cont_entrada_pcd = 0;
				$cont_entrada_terceirizado = 0;
				$cont_entrada_estagio_pcd = 0;
				$cont_entrada_mulher = 0;
				$cont_entrada_homem = 0;
			}
			
			$array_mes['mes_referencia'][$data_referencia];
			$array_mes['mes_referencia'][$data_referencia]['funcionarios_antigos'] = $funcionarios_antigos[0]['total'];
			$array_mes['mes_referencia'][$data_referencia]['antigo_homens'] = $homens[0]['total_homens'];
			$array_mes['mes_referencia'][$data_referencia]['antigo_mulheres'] = $mulheres[0]['total_mulheres'];
			$array_mes['mes_referencia'][$data_referencia]['total_saida_homens'] = $saidas_homens[0]['total_saida_homens'];
			$array_mes['mes_referencia'][$data_referencia]['total_saida_mulheres'] = $saidas_mulheres[0]['total_saida_mulheres'];
			$array_mes['mes_referencia'][$data_referencia]['total_saida_mes'] = $saidas[0]['total_saida_mes'];

			if ($conteudo['data_inicio'] >= $data_inicio) {
				$cont_entradas++;
				$total_entrada_mes++;

				if ($conteudo['formato'] == 1) {
					$cont_entrada_efetivo++;

				} else if ($conteudo['formato'] == 2) {
					$cont_entrada_estagio++;

				} else if ($conteudo['formato'] == 3) {
					$cont_entrada_jovem_aprendiz++;

				} else if ($conteudo['formato'] == 4) {
					$cont_entrada_pcd++;

				} else if ($conteudo['formato'] == 5) {
					$cont_entrada_terceirizado++;

				} else if ($conteudo['formato'] == 6) {
					$cont_entrada_estagio_pcd++;
				}

				if ($conteudo['sexo'] == 'f') {
					$cont_entrada_mulher++;

				} else if ($conteudo['sexo'] == 'm') {
					$cont_entrada_homem++;
				}
			}

			$array_mes['mes_referencia'][$data_referencia]['total_entrada_mes'] = $total_entrada_mes;
			$array_mes['mes_referencia'][$data_referencia]['total_entrada_efetivo'] = $cont_entrada_efetivo;
			$array_mes['mes_referencia'][$data_referencia]['total_entrada_estagio'] = $cont_entrada_estagio;
			$array_mes['mes_referencia'][$data_referencia]['total_entrada_jovem_aprendiz'] = $cont_entrada_jovem_aprendiz;
			$array_mes['mes_referencia'][$data_referencia]['total_entrada_pcd'] = $cont_entrada_pcd;
			$array_mes['mes_referencia'][$data_referencia]['total_entrada_terceirizado'] = $cont_entrada_terceirizado;
			$array_mes['mes_referencia'][$data_referencia]['total_entrada_estagio_pcd'] = $cont_entrada_estagio_pcd;
			$array_mes['mes_referencia'][$data_referencia]['total_entrada_efetivo_aprendiz'] = $cont_entrada_efetivo + $cont_entrada_jovem_aprendiz;
			$array_mes['mes_referencia'][$data_referencia]['total_contratacoes'] = $cont_entrada_efetivo + $cont_entrada_estagio + $cont_entrada_pcd + $cont_entrada_estagio_pcd + $cont_entrada_terceirizado;
			$array_mes['mes_referencia'][$data_referencia]['total_entrada_homens'] = $cont_entrada_homem;
			$array_mes['mes_referencia'][$data_referencia]['total_entrada_mulheres'] = $cont_entrada_mulher;
		}
		
		foreach ($array_data_fim as $k => $v) {
			
			$mes = explode('-', $v);
			$data_referencia = $mes[1].'/'.$mes[0];

			$mes = $mes[0].'-'.$mes[1].'-01';
			$data_mes = new DateTime($mes);
			$data_mes_inicio = $data_mes->modify('first day of this month')->format('Y-m-d');
			$data_mes_fim = $data_mes->modify('last day of this month')->format('Y-m-d');

			if (!isset($data_referencia, $array_mes['mes_referencia'][$data_referencia])) {

				$funcionarios_antigos = DBRead('', 'tb_funcionario_periodo', "WHERE data_inicio <= '$data_mes_inicio' AND (data_fim IS NULL OR data_fim >= '$data_mes_inicio')", 'count(*) as total');

				$mulheres = DBRead('', 'tb_funcionario_periodo a', "INNER JOIN tb_funcionario b ON a.id_funcionario = b.id_funcionario INNER JOIN tb_usuario c ON b.id_usuario = c.id_usuario INNER JOIN tb_pessoa d ON c.id_pessoa = d.id_pessoa WHERE a.data_inicio <= '$data_mes_inicio' AND (a.data_fim IS NULL OR a.data_fim >= '$data_mes_inicio') AND d.sexo = 'f' ", 'count(*) as total_mulheres');

				$homens = DBRead('', 'tb_funcionario_periodo a', "INNER JOIN tb_funcionario b ON a.id_funcionario = b.id_funcionario INNER JOIN tb_usuario c ON b.id_usuario = c.id_usuario INNER JOIN tb_pessoa d ON c.id_pessoa = d.id_pessoa WHERE a.data_inicio <= '$data_mes_inicio' AND (a.data_fim IS NULL OR a.data_fim >= '$data_mes_inicio') AND d.sexo = 'm' ", 'count(*) as total_homens');

				$saidas =  DBRead('', 'tb_funcionario_periodo a', "WHERE a.id_funcionario_periodo AND (a.data_fim >= '$data_mes_inicio' AND a.data_fim <= '$data_mes_fim') ORDER BY a.data_inicio ASC", 'count(*) as total_saida_mes');

				$saidas_homens =  DBRead('', 'tb_funcionario_periodo a', "INNER JOIN tb_funcionario b ON a.id_funcionario = b.id_funcionario INNER JOIN tb_usuario c ON b.id_usuario = c.id_usuario INNER JOIN tb_pessoa d ON c.id_pessoa = d.id_pessoa WHERE a.id_funcionario_periodo AND (a.data_fim >= '$data_mes_inicio' AND a.data_fim <= '$data_mes_fim') AND d.sexo = 'm' ORDER BY a.data_inicio ASC", 'count(*) as total_saida_homens');

				$saidas_mulheres =  DBRead('', 'tb_funcionario_periodo a', "INNER JOIN tb_funcionario b ON a.id_funcionario = b.id_funcionario INNER JOIN tb_usuario c ON b.id_usuario = c.id_usuario INNER JOIN tb_pessoa d ON c.id_pessoa = d.id_pessoa WHERE a.id_funcionario_periodo AND (a.data_fim >= '$data_mes_inicio' AND a.data_fim <= '$data_mes_fim') AND d.sexo = 'f' ORDER BY a.data_inicio ASC", 'count(*) as total_saida_mulheres');

				$total_entrada_mes = 0;

				$cont_entrada_efetivo = 0;
				$cont_entrada_estagio = 0;
				$cont_entrada_jovem_aprendiz = 0;
				$cont_entrada_pcd = 0;
				$cont_entrada_terceirizado = 0;
				$cont_entrada_estagio_pcd = 0;
				$cont_entrada_mulher = 0;
				$cont_entrada_homem = 0;
			}
			
			$array_mes['mes_referencia'][$data_referencia];
			$array_mes['mes_referencia'][$data_referencia]['funcionarios_antigos'] = $funcionarios_antigos[0]['total'];
			$array_mes['mes_referencia'][$data_referencia]['antigo_homens'] = $homens[0]['total_homens'];
			$array_mes['mes_referencia'][$data_referencia]['antigo_mulheres'] = $mulheres[0]['total_mulheres'];
			$array_mes['mes_referencia'][$data_referencia]['total_saida_homens'] = $saidas_homens[0]['total_saida_homens'];
			$array_mes['mes_referencia'][$data_referencia]['total_saida_mulheres'] = $saidas_mulheres[0]['total_saida_mulheres'];
			$array_mes['mes_referencia'][$data_referencia]['total_saida_mes'] = $saidas[0]['total_saida_mes'];
			
			$array_mes['mes_referencia'][$data_referencia]['total_entrada_mes'] = $total_entrada_mes;
			$array_mes['mes_referencia'][$data_referencia]['total_entrada_efetivo'] = $cont_entrada_efetivo;
			$array_mes['mes_referencia'][$data_referencia]['total_entrada_estagio'] = $cont_entrada_estagio;
			$array_mes['mes_referencia'][$data_referencia]['total_entrada_jovem_aprendiz'] = $cont_entrada_jovem_aprendiz;
			$array_mes['mes_referencia'][$data_referencia]['total_entrada_pcd'] = $cont_entrada_pcd;
			$array_mes['mes_referencia'][$data_referencia]['total_entrada_terceirizado'] = $cont_entrada_terceirizado;
			$array_mes['mes_referencia'][$data_referencia]['total_entrada_estagio_pcd'] = $cont_entrada_estagio_pcd;

			$array_mes['mes_referencia'][$data_referencia]['total_entrada_efetivo_aprendiz'] = $cont_entrada_efetivo + $cont_entrada_jovem_aprendiz;
			$array_mes['mes_referencia'][$data_referencia]['total_contratacoes'] = $cont_entrada_efetivo + $cont_entrada_estagio + $cont_entrada_pcd + $cont_entrada_estagio_pcd + $cont_entrada_terceirizado;
			$array_mes['mes_referencia'][$data_referencia]['total_entrada_homens'] = $cont_entrada_homem;
			$array_mes['mes_referencia'][$data_referencia]['total_entrada_mulheres'] = $cont_entrada_mulher;
		}

		$array_sort = array();
		$cont = 0;
		foreach ($array_mes['mes_referencia'] as $key => $value) {
			$data_completa = '01/'.$key;
			$data_completa = converteData($data_completa);
			$aux = $value;
			$array_sort[$cont]['mes_referencia'] = $data_completa;
			$array_sort[$cont]['dados'] = $aux;
			$cont++;
		}

		usort($array_sort, function($a, $b) { return $a['mes_referencia'] <=> $b['mes_referencia']; });;

		foreach ($array_sort as $key => $value) {
			if ($value['mes_referencia'] < $data_inicio) {
				unset($array_sort[$key]);
			}
		}

		?>
		<div class="row">
			<div class="col-md-12" style="padding: 0px 15px 15px 15px;">
				<div class="table-responsive">
					<table class="table table-bordered" style="border: solid 1px #D8D8D8">
						<thead>
							<tr style="background-color: #D8D8D8">
								<th class="text-center col-md-3"></th>
								<?php

								foreach ($array_sort as $key => $conteudo) {
									$mes = explode('-', $conteudo['mes_referencia']);
									$data_referencia = $mes[1].'/'.$mes[0];

									echo '<th class="mes_referencia text-center">'.$data_referencia.'</th>';
								}
								
								?>
								<th class="text-center">Total</th>
								<th class="text-center"></th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td></td>
								<?php 
								$total = 0;
								foreach ($array_sort as $key => $conteudo) {
									$total += $conteudo['dados']['total_entrada_efetivo_aprendiz'];

									$active = '';
									if ($conteudo['dados']['total_entrada_efetivo_aprendiz'] != 0) {
										$active = 'active';
									}	

									echo '<td class=" text-center '.$active.' ">'.$conteudo['dados']['total_entrada_efetivo_aprendiz'].'</td>';
								}
								
								?>
								<td class="text-center active"><?= $total ?></td>
								<td class='success'>Contratações CLT / Aprendiz</td>
							</tr>
							<tr>
								<td></td>
								<?php 
								$total_estagio = 0;
								foreach ($array_sort as $key => $conteudo) {
									$total_estagio += $conteudo['dados']['total_entrada_estagio'];

									$active = '';
									if ($conteudo['dados']['total_entrada_estagio'] != 0) {
										$active = 'active';
									}

									echo '<td class=" text-center '.$active.'">'.$conteudo['dados']['total_entrada_estagio'].'</td>';
									}
								?>
								<td class="text-center active"><?= $total_estagio ?></td>
								<td class='success'>Contratações estagiários</td>
							</tr>
							<tr>
								<td></td>
								<?php 
								$total_estagio_pcd = 0;
								foreach ($array_sort as $key => $conteudo) {
									$total_estagio_pcd += $conteudo['dados']['total_entrada_estagio_pcd'];

									$active = '';
									if ($conteudo['dados']['total_entrada_estagio_pcd'] != 0) {
										$active = 'active';
									}

									echo '<td class=" text-center '.$active.'">'.$conteudo['dados']['total_entrada_estagio_pcd'].'</td>';
									}
								?>
								<td class="text-center active"><?= $total_estagio_pcd ?></td>
								<td class='success'>Contratações estagiarios PCD</td>
							</tr>
							<tr>
								<td></td>
								<?php 
								$total_pcd = 0;
								foreach ($array_sort as $key => $conteudo) {
									$total_pcd += $conteudo['dados']['total_entrada_pcd'];

									$active = '';
									if ($conteudo['dados']['total_entrada_pcd'] != 0) {
										$active = 'active';
									}

									echo '<td class=" text-center '.$active.'">'.$conteudo['dados']['total_entrada_pcd'].'</td>';
									}
								?>
								<td class="text-center active"><?= $total_pcd ?></td>
								<td class='success'>Contratações PCD</td>
							</tr>
							<tr>
								<td></td>
								<?php 
								$total_terceirizados = 0;
								foreach ($array_sort as $key => $conteudo) {
									$total_terceirizados += $conteudo['dados']['total_entrada_terceirizado'];

									$active = '';
									if ($conteudo['dados']['total_entrada_terceirizado'] != 0) {
										$active = 'active';
									}

									echo '<td class=" text-center '.$active.'">'.$conteudo['dados']['total_entrada_terceirizado'].'</td>';
									}
								?>
								<td class="text-center active"><?= $total_terceirizados ?></td>
								<td class='success'>Contratações terceirizados</td>
							</tr>
							<tr>
								<td></td>
								<?php 
								$total_entrada_mes = 0;
								foreach ($array_sort as $key => $conteudo) {
									$total_entrada_mes += $conteudo['dados']['total_contratacoes'];

									$active = '';
									if ($total_entrada_mes != 0) {
										$active = 'active';
									}

									echo '<td class=" text-center '.$active.'">'.$conteudo['dados']['total_contratacoes'].'</td>';
									}
								?>
								<td class="text-center active"><?= $total_entrada_mes ?></td>
								<td class='success'>Total de contratações</td>
							</tr>
							<tr>
								<td class="info">Número homens no inicio do mês</td>
								<?php 
								foreach ($array_sort as $key => $conteudo) {

									$active = '';
									if ($conteudo['dados']['antigo_homens'] != 0) {
										$active = 'active';
									}

									echo '<td class=" text-center '.$active.'">'.$conteudo['dados']['antigo_homens'].'</td>';
									}
								?>
								<td class="text-center"></td>
								<td class=""></td>
							</tr>
							<tr>
								<td class="info">Número homens no fim do mês</td>
								<?php 
								foreach ($array_sort as $key => $conteudo) {

									$diferenca = ($conteudo['dados']['total_entrada_homens'] - $conteudo['dados']['total_saida_homens']) + $conteudo['dados']['antigo_homens'];

									$active = '';
									if ($diferenca != 0) {
										$active = 'active';
									}

									echo '<td class=" text-center '.$active.'">'.$diferenca.'</td>';
									}
								?>
								<td class="text-center"></td>
								<td class=""></td>
							</tr>
							<tr>
								<td style="background-color: #F2E0F7">Número mulheres no inicio do mês</td>
								<?php 
								foreach ($array_sort as $key => $conteudo) {

									$active = '';
									if ($conteudo['dados']['antigo_mulheres'] != 0) {
										$active = 'active';
									}

									echo '<td class=" text-center '.$active.'">'.$conteudo['dados']['antigo_mulheres'].'</td>';
									}
								?>
								<td class="text-center"></td>
								<td class=""></td>
							</tr>
							<tr>
								<td style="background-color: #F2E0F7">Número mulheres no fim do mês</td>
								<?php 
								foreach ($array_sort as $key => $conteudo) {

									$diferenca = ($conteudo['dados']['total_entrada_mulheres'] - $conteudo['dados']['total_saida_mulheres']) + $conteudo['dados']['antigo_mulheres'];

									$active = '';
									if ($diferenca != 0) {
										$active = 'active';
									}

									echo '<td class=" text-center '.$active.'">'.$diferenca.'</td>';
								}
								?>
								<td class="text-center"></td>
								<td class=""></td>
							</tr>
							<tr>
								<td style="background-color: #A9D0F5">Numero de colaboradores no inicio do mês</td>
								<?php 
								foreach ($array_sort as $key => $conteudo) {

									$active = '';
									if ($conteudo['dados']['funcionarios_antigos'] != 0) {
										$active = 'active';
									}

									if ($key === array_key_first($array_sort)) {
										$total_antes = $conteudo['dados']['funcionarios_antigos'];
									}

									if ($key === array_key_last($array_sort)) {
										$total_depois = ($conteudo['dados']['total_entrada_mes'] - $conteudo['dados']['total_saida_mes']) + $conteudo['dados']['funcionarios_antigos'];
									}

									echo '<td class=" text-center '.$active.'">'.$conteudo['dados']['funcionarios_antigos'].'</td>';
									}
								?>
								<td class="text-center active"><?= ($total_depois) - ($total_antes) ?></td>
								<td style="background-color: #A9D0F5">Incremento</td>
							</tr>
							<tr>
								<td class="warning">Total de desligamentos</td>
								<?php 
								$total_saidas = 0;
								foreach ($array_sort as $key => $conteudo) {
									$total_saidas += $conteudo['dados']['total_saida_mes'];

									$active = '';
									if ($total_saidas != 0) {
										$active = 'active';
									}
									echo '<td class="text-center '.$active.'">'.$conteudo['dados']['total_saida_mes'].'</td>';
								}
								?>
								<td class="text-center <?= $active ?>"><?= $total_saidas ?></td>
								<td class="warning">Desligamentos</td>
							</tr>
							<tr>
								<td class="success">Número de funcionários no fim do mês</td>
								<?php 
								foreach ($array_sort as $key => $conteudo) {

									$diferenca = ($conteudo['dados']['total_entrada_mes'] - $conteudo['dados']['total_saida_mes']) + $conteudo['dados']['funcionarios_antigos'];

									$active = '';
									if ($diferenca != 0) {
										$active = 'active';
									}

									echo '<td class=" text-center '.$active.'">'.$diferenca.'</td>';
									}
								?>
							</tr>
							<tr>
								<td class="">Cota PCD</td>
								<?php 
								foreach ($array_sort as $key => $conteudo) {

									$cota = $conteudo['dados']['antigo_efetivo'] * 0.02;

									$percentual = ($conteudo['dados']['antigo_pcd']*100)/$conteudo['dados']['antigo_efetivo'];

									if (is_nan($percentual) || $percentual == INF){
										$percentual = 0;
									}

									if ($percentual >= $cota) {
										$class = 'success';
									} else {
										$class = 'danger';
									}

									echo '<td class=" text-center '.$class.' ">'.sprintf("%01.2f", $percentual).'%</td>';
									}
								?>
							</tr>
							<tr> 
								<td class="">Cota jovem aprendiz</td>
								<?php 
								foreach ($array_sort as $key => $conteudo) {

									$cota = $conteudo['dados']['antigo_efetivo'] * 0.05;
									$percentual = ($conteudo['dados']['antigo_jovem_aprendiz']*100)/$conteudo['dados']['antigo_efetivo'];

									if (is_nan($percentual) || $percentual == INF){
										$percentual = 0;
									}

									if ($percentual >= $cota) {
										$class = 'success';
									} else {
										$class = 'danger';
									}

									echo '<td class=" text-center '.$class.' ">'.sprintf("%01.2f", $percentual).'%</td>';
									}
								?>
							</tr>
							<tr>
								<td>% Turnover saída </td>
								<?php 
								foreach ($array_sort as $key => $conteudo) {
									
									$diferenca = ($conteudo['dados']['total_entrada_mes'] - $conteudo['dados']['total_saida_mes']) + $conteudo['dados']['funcionarios_antigos'];

									$percentual = ($conteudo['dados']['total_saida_mes']/$diferenca) * 100; 

									echo '<td class=" text-center">'.sprintf("%01.2f", $percentual).'%</td>';
									}
								?>
							</tr>
							<tr>
								<td>% Turnover Geral</td>
								<?php 
								foreach ($array_sort as $key => $conteudo) {

									$diferenca = ($conteudo['dados']['total_entrada_mes'] - $conteudo['dados']['total_saida_mes']) + $conteudo['dados']['funcionarios_antigos'];

									$percentual = ((($conteudo['dados']['total_saida_mes'] + $conteudo['dados']['total_entrada_mes'])/2)/$diferenca) * 100; 

									echo '<td class=" text-center">'.sprintf("%01.2f", $percentual).'%</td>';
									}
								?>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
		<?php

    } else {
        echo "<table class='table table-bordered'>";
            echo "<tbody>";
                echo "<tr>";
                    echo "<td class='text-center'><h4>Não foram encontrados resultados!</h4></td>";
                echo "</tr>";
            echo "</tbody>";
        echo "</table>";
    }
    echo "</div>";
}

function relatorio_desligamentos($mes_inicio, $ano_inicio, $mes_fim, $ano_fim)
{
	$data_hora = converteDataHora(getDataHora());

	$data_inicio = '01/'.$mes_inicio.'/'.$ano_inicio;
	$data = $ano_inicio.'-'.$mes_inicio.'-01';

	$data = new DateTime($data);
	$data_inicio = $data->modify('first day of this month')->format('Y-m-d');

	$data_fim = '01/'.$mes_fim.'/'.$ano_fim;
	$data = $ano_fim.'-'.$mes_fim.'-01';

	$data = new DateTime($data);
	$data_fim = $data->modify('last day of this month')->format('Y-m-d');
	
	if ($data_inicio && $data_fim) {
	    $periodo_amostra ="<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> De ".converteData($data_inicio)." até ".converteData($data_fim)."</span>";
		
	} else {
	    $periodo_amostra = "";
	}

	echo "<div class=\"col-md-8 col-md-offset-2\" style=\"padding: 0\">";
	echo "<legend style=\"text-align:center;\"><strong>Relatório de Funcionários - Desligamentos</strong><br><span style=\"font-size: 14px;\"><strong>Gerado em:</strong> $data_hora</span></legend>";
    echo "<legend style=\"text-align:center;\">$periodo_amostra</legend>";
	
	$filtro_data = '';
    if ($data_inicio) {
		$filtro_data .= " AND b.data_fim >= '$data_inicio'";
	}

	if ($data_fim) {
		$filtro_data .= " AND b.data_fim <= '$data_fim'";
    }
    
    $dados = DBRead('','tb_funcionario a',"INNER JOIN tb_funcionario_periodo b ON a.id_funcionario = b.id_funcionario INNER JOIN tb_usuario c ON a.id_usuario = c.id_usuario INNER JOIN tb_pessoa d ON c.id_pessoa = d.id_pessoa WHERE a.id_funcionario $filtro_data ORDER BY d.nome ASC", 'b.*, d.nome');

    if ($dados) {

        echo "<div class='row'>";
        	echo "<div class='col-md-12'>";
            echo "<table class='table table-hover dataTable' style='font-size='14px'>";
			echo "<thead>";
				echo "<tr>";
					echo "<th>Nome</th>";
					echo "<th>Escolaridade</th>";
					echo "<th>Formato</th>";
					echo "<th>Data de início</th>";
					echo "<th>Data de fim</th>";
					echo "<th>Tempo (em meses)</th>";
					echo "<th>Demissão</th>";
				echo "</tr>";
			echo "</thead>";
			echo "<tbody>";

		foreach($dados as $conteudo){

            if ($conteudo['escolaridade'] == 1) {
                $escolaridade = 'Primeiro grau completo';

            } else if ($conteudo['escolaridade'] == 2) {
                $escolaridade = 'Primeiro grau incompleto';

            } else if ($conteudo['escolaridade'] == 3) {
                $escolaridade = 'Segundo grau completo';

            } else if ($conteudo['escolaridade'] == 4) {
                $escolaridade = 'Segundo grau incompleto';

            } else if ($conteudo['escolaridade'] == 5) {
                $escolaridade = 'Superior completo';

            } else if ($conteudo['escolaridade'] == 6) {
                $escolaridade = 'Superior incompleto';

            } else if ($conteudo['escolaridade'] == 7) {
                $escolaridade = 'Pós-graduação em andamento';

            } else if ($conteudo['escolaridade'] == 8) {
                $escolaridade = 'Pós-graduação completa';

            } else if ($conteudo['escolaridade'] == 8) {
                $escolaridade = 'Mestrando';

            } else if ($conteudo['escolaridade'] == 10) {
                $escolaridade = 'Mestre';

            } else if ($conteudo['escolaridade'] == 11) {
                $escolaridade = 'Doutorando';

            }  else if ($conteudo['escolaridade'] == 12) {
                $escolaridade = 'Doutor';

            } else {
                $escolaridade = 'N/D';
            }

			if ($conteudo['formato'] == 1) {
				$formato = 'Efetivo';

			} else if ($conteudo['formato'] == 2) {
				$formato = 'Estágio';

			} else if ($conteudo['formato'] == 3) {
				$formato = 'Jovem aprendiz';

			} else if ($conteudo['formato'] == 4) {
				$formato = 'PCD';
			}

			if ($conteudo['demissao'] == 1) {
				$demissao = 'Dispensado';

			} else if ($conteudo['demissao'] == 2) {
				$demissao = 'Pedido';

			} else if ($conteudo['demissao'] == 3) {
				$demissao = 'Fim de contrato';
			}


			$data_hoje = getDataHora();
			$data_hoje = explode(' ', $data_hoje);
			$data_inicio = new DateTime($conteudo['data_inicio']);
			$data_fim = new DateTime($conteudo['data_fim']);
			$dateInterval = $data_inicio->diff($data_fim);
			$meses = $dateInterval->days/30;
		
			echo "<tr>";
				echo "<td>".$conteudo['nome']."</td>";
				echo "<td>".$escolaridade."</td>";
				echo "<td>".$formato."</td>";
				echo "<td>".converteData($conteudo['data_inicio'])."</td>";
				echo "<td>".converteData($conteudo['data_fim'])."</td>";
				echo "<td>".sprintf("%01.1f", $meses)."</td>";
				echo "<td>".$demissao."</td>";
			echo "</tr>";
		}
		
		echo "</tbody>";
		echo "</table>";
		echo "</div>";
		echo "</div>";

    } else {
        echo "<table class='table table-bordered'>";
            echo "<tbody>";
                echo "<tr>";
                    echo "<td class='text-center'><h4>Não foram encontrados resultados!</h4></td>";
                echo "</tr>";
            echo "</tbody>";
        echo "</table>";
    }
    echo "</div>";
}

function relatorio_geral($dia){
	$data_hora = converteDataHora(getDataHora());
	
	if ($dia) {
	    $periodo_amostra ="<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> Dia $dia</span>";

	} else {
	    $periodo_amostra = "";
	}

	echo "<div class=\"col-md-12\" style=\"padding: 0\">";
	echo "<legend style=\"text-align:center;\"><strong>Relatório de Funcionários - Geral</strong><br><span style=\"font-size: 14px;\"><strong>Gerado em:</strong> $data_hora</span></legend>";
    echo "<legend style=\"text-align:center;\">$periodo_amostra</legend>";
    
    $dados = DBRead('','tb_funcionario a',"INNER JOIN tb_usuario b ON b.id_usuario = a.id_usuario INNER JOIN tb_pessoa c ON c.id_pessoa = b.id_pessoa INNER JOIN tb_perfil_sistema d ON b.id_perfil_sistema = d.id_perfil_sistema ORDER BY c.nome ASC", 'a.*, c.nome, d.nome AS nome_perfil');

    if ($dados) {

		$array_funcionarios = array();
		$array_perfil = array();

		$cont_efetivo = 0;
		$cont_estagio = 0;
		$cont_jovem_aprendiz = 0;
		$cont_pcd = 0;
		$cont_terceirizado = 0;
		$cont_estagio_pcd = 0;

		$dados_perfil = DBRead('', 'tb_perfil_sistema',"WHERE status = 1 AND id_perfil_sistema != 19 ORDER BY nome ASC");
		foreach ($dados_perfil as $conteudo_perfil) {
			$array_perfil[$conteudo_perfil['nome']] = 0;
		}

		foreach ($dados as $funcionario) {

			$id_funcionario = $funcionario['id_funcionario'];

			$check = DBRead('', 'tb_funcionario_periodo a',"WHERE id_funcionario = $id_funcionario AND ((data_fim IS NULL AND data_inicio < '".converteData($dia)."') OR (data_inicio <= '".converteData($dia)."' AND data_fim > '".converteData($dia)."'))");

			if ($check) {

				if ($check[0]['escolaridade'] == 1) {
					$escolaridade = 'Primeiro grau completo';
	
				} else if ($check[0]['escolaridade'] == 2) {
					$escolaridade = 'Primeiro grau incompleto';
	
				} else if ($check[0]['escolaridade'] == 3) {
					$escolaridade = 'Segundo grau completo';
	
				} else if ($check[0]['escolaridade'] == 4) {
					$escolaridade = 'Segundo grau incompleto';
	
				} else if ($check[0]['escolaridade'] == 5) {
					$escolaridade = 'Superior completo';
	
				} else if ($check[0]['escolaridade'] == 6) {
					$escolaridade = 'Superior incompleto';
	
				} else if ($check[0]['escolaridade'] == 7) {
					$escolaridade = 'Pós-graduação em andamento';
	
				} else if ($check[0]['escolaridade'] == 8) {
					$escolaridade = 'Pós-graduação completa';
	
				} else if ($check[0]['escolaridade'] == 8) {
					$escolaridade = 'Mestrando';
	
				} else if ($check[0]['escolaridade'] == 10) {
					$escolaridade = 'Mestre';
	
				} else if ($check[0]['escolaridade'] == 11) {
					$escolaridade = 'Doutorando';
	
				}  else if ($check[0]['escolaridade'] == 12) {
					$escolaridade = 'Doutor';
	
				} else {
					$escolaridade = 'N/D';
				}
	
				if ($check[0]['formato'] == 1) {
					$formato = 'Efetivo';
					$cont_efetivo++;
	
				} else if ($check[0]['formato'] == 2) {
					$formato = 'Estágio';
					$cont_estagio++;
	
				} else if ($check[0]['formato'] == 3) {
					$formato = 'Jovem aprendiz';
					$cont_jovem_aprendiz++;
	
				} else if ($check[0]['formato'] == 4) {
					$formato = 'PCD';
					$cont_pcd++;

				} else if ($check[0]['formato'] == 5) {
					$formato = 'Terceirizado';
					$cont_terceirizado++;

				} else if ($check[0]['formato'] == 6) {
					$formato = 'Estágio PCD';
					$cont_estagio_pcd++;
				}

				
				$array_funcionarios[$id_funcionario]['nome'] = $funcionario['nome'];
				$array_funcionarios[$id_funcionario]['escolaridade'] = $escolaridade;
				$array_funcionarios[$id_funcionario]['formato'] = $formato;
				$array_funcionarios[$id_funcionario]['perfil_sistema'] = $funcionario['nome_perfil'];
				$array_funcionarios[$id_funcionario]['data_inicio'] = $check[0]['data_inicio'];
				$array_perfil[$funcionario['nome_perfil']] ++;
			}
		}

		$total_funcionarios = $cont_efetivo + $cont_estagio + $cont_estagio_pcd + $cont_jovem_aprendiz + $cont_pcd + $cont_terceirizado;

		echo "<div class='row'>";
		echo "<div class='col-md-12'>";
		echo "<table class='table table-hover dataTable' style='font-size='14px'>";
		echo "<thead>";
			echo "<tr>";
				echo "<th>Formato</th>";
				echo "<th>Total</th>";
			echo "</tr>";
		echo "</thead>";
		echo "<tbody>";
			echo "<tr>";
				echo "<td class='col-md-8'>Efetivo</td>";
				echo "<td class='col-md-4'>".$cont_efetivo."</td>";
			echo "</tr>";
			echo "<tr>";
				echo "<td class='col-md-8'>Estágio</td>";
				echo "<td class='col-md-4'>".$cont_estagio."</td>";
			echo "</tr>";
			echo "<tr>";
				echo "<td class='col-md-8'>Estágio PCD</td>";
				echo "<td class='col-md-4'>".$cont_estagio_pcd."</td>";
			echo "</tr>";
			echo "<tr>";
				echo "<td class='col-md-8'>Jovem Aprendiz</td>";
				echo "<td class='col-md-4'>".$cont_jovem_aprendiz."</td>";
			echo "</tr>";
			echo "<tr>";
				echo "<td class='col-md-8'>PCD</td>";
				echo "<td class='col-md-4'>".$cont_pcd."</td>";
			echo "</tr>";
			echo "<tr>";
				echo "<td class='col-md-8'>Terceirizado</td>";
				echo "<td class='col-md-4'>".$cont_terceirizado."</td>";
			echo "</tr>";
		echo "</tbody>";
		echo "<tfoot>";
			echo "<tr class='active'>";
				echo "<th>Total de funcionários</th>";
				echo "<td>".$total_funcionarios."</td>";
			echo "</tr>";
		echo "</tfoot>";
		echo "</table>";
		echo "</div>";
		echo "</div><hr><br>";

		echo "<div class='row'>";
		echo "<div class='col-md-12'>";
		echo "<table class='table table-hover dataTable' style='font-size='14px'>";
		echo "<thead>";
			echo "<tr>";
				echo "<th>Perfil</th>";
				echo "<th>Total</th>";
			echo "</tr>";
		echo "</thead>";
		echo "<tbody>";

		$contador_perfil = 0;
		foreach ($dados_perfil as $conteudo_perfil) {
			if($array_perfil[$conteudo_perfil['nome']] != 0){
				echo "<tr>";
					echo "<td class='col-md-8'>".$conteudo_perfil['nome']."</td>";
					echo "<td class='col-md-4'>".$array_perfil[$conteudo_perfil['nome']]."</td>";
				echo "</tr>";

				$contador_perfil = $contador_perfil+$array_perfil[$conteudo_perfil['nome']];
			}
		}
			
		echo "</tbody>";
		echo "<tfoot>";
			echo "<tr class='active'>";
				echo "<th>Total de Perfis</th>";
				echo "<td>".$contador_perfil."</td>";
			echo "</tr>";
		echo "</tfoot>";
		echo "</table>";
		echo "</div>";
		echo "</div><hr><br>";

        echo "<div class='row'>";
        	echo "<div class='col-md-12'>";
            echo "<table class='table table-hover dataTable' style='font-size='14px'>";
			echo "<thead>";
				echo "<tr>";
					echo "<th>Nome do funcionário</th>";
					echo "<th>Escolaridade</th>";
					echo "<th>Formato</th>";
					echo "<th>Perfil</th>";
					echo "<th>Data de início</th>";
				echo "</tr>";
			echo "</thead>";
			echo "<tbody>";

		foreach($array_funcionarios as $conteudo){
			echo "<tr>";
				echo "<td>".$conteudo['nome']."</td>";
				echo "<td>".$conteudo['escolaridade']."</td>";
				echo "<td>".$conteudo['formato']."</td>";
				echo "<td>".$conteudo['perfil_sistema']."</td>";
				echo "<td>".converteData($conteudo['data_inicio'])."</td>";
			echo "</tr>";
		}
		
		echo "</tbody>";
		echo "</table>";
		echo "</div>";
		echo "</div><hr>";

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
			});
		</script>			
		";

    } else {
        echo "<table class='table table-bordered'>";
            echo "<tbody>";
                echo "<tr>";
                    echo "<td class='text-center'><h4>Não foram encontrados resultados!</h4></td>";
                echo "</tr>";
            echo "</tbody>";
        echo "</table>";
    }
    echo "</div>";
}

function relatorio_cotas($dia)
{
	$data_hora = converteDataHora(getDataHora());
	
	if ($dia) {
	    $periodo_amostra ="<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> Dia $dia</span>";

	} else {
	    $periodo_amostra = "";
	}

	echo "<div class=\"col-md-8 col-md-offset-2\" style=\"padding: 0\">";
	echo "<legend style=\"text-align:center;\"><strong>Relatório de Funcionários - Cotas</strong><br><span style=\"font-size: 14px;\"><strong>Gerado em:</strong> $data_hora</span></legend>";
    echo "<legend style=\"text-align:center;\">$periodo_amostra</legend>";
    
    $dados = DBRead('','tb_funcionario a',"INNER JOIN tb_usuario b ON b.id_usuario = a.id_usuario INNER JOIN tb_pessoa c ON c.id_pessoa = b.id_pessoa ORDER BY c.nome ASC", 'a.*, c.nome');

    if ($dados) {

		$cont_efetivo = 0;
		$cont_estagio = 0;
		$cont_jovem_aprendiz = 0;
		$cont_pcd = 0;
		$cont_terceirizado = 0;
		$cont_estagio_pcd = 0;

		foreach ($dados as $funcionario) {

			$id_funcionario = $funcionario['id_funcionario'];

			$check = DBRead('', 'tb_funcionario_periodo a',"WHERE id_funcionario = $id_funcionario AND ((data_fim IS NULL AND data_inicio < '".converteData($dia)."') OR (data_inicio <= '".converteData($dia)."' AND data_fim > '".converteData($dia)."'))");

			if ($check) {
	
				if ($check[0]['formato'] == 1) {
					$cont_efetivo++;
	
				} else if ($check[0]['formato'] == 2) {
					$cont_estagio++;
	
				} else if ($check[0]['formato'] == 3) {
					$cont_jovem_aprendiz++;
	
				} else if ($check[0]['formato'] == 4) {
					$cont_pcd++;

				} else if ($check[0]['formato'] == 6) {
					$cont_estagio_pcd++;
				}
			}

		}
	
		$total_funcionarios = $cont_efetivo + $cont_estagio + $cont_estagio_pcd + $cont_jovem_aprendiz + $cont_pcd;
		$total_com_socios = $total_funcionarios + 2; 

		if ($cont_efetivo >= 100) {
			$cota_pcd = ceil(porcentagem(2, $cont_efetivo));

			if ($cont_pcd < $cota_pcd) {
				$legenda_pcd = 'Problema';
				$class_pcd = 'danger';
	
			} else if ($cont_pcd == $cota_pcd) {
				$legenda_pcd = 'No limite';
				$class_pcd = 'warning';
	
			} else if ($cont_pcd > $cota_pcd) {
				$legenda_pcd = 'OK';
				$class_pcd = 'success';
			}

		} else {
			$cota_pcd = 0;
			$legenda_pcd = 'OK';
				$class_pcd = 'success';
		}

		if ($cont_efetivo >= 7) {
			$cota_jovem_aprendiz = ceil(porcentagem(5, $cont_efetivo));

			if ($cont_jovem_aprendiz < $cota_jovem_aprendiz) {
				$legenda_jovem_aprendiz = 'Problema';
				$class_jovem_aprendiz = 'danger';
	
			} else if ($cont_jovem_aprendiz == $cota_jovem_aprendiz) {
				$legenda_jovem_aprendiz = 'No limite';
				$class_jovem_aprendiz = 'warning';
	
			} else if ($cont_jovem_aprendiz > $cota_jovem_aprendiz) {
				$legenda_jovem_aprendiz = 'OK';
				$class_jovem_aprendiz = 'success';
			}

		} else {
			$cota_jovem_aprendiz = 0;
			$legenda_jovem_aprendiz = 'OK';
			$class_jovem_aprendiz = 'success';
		}
		
		$cota_estagio = floor(porcentagem(20, $cont_efetivo));

		if ($cont_estagio >= 10) {
			$cota_estagio_pcd = ceil(porcentagem(10, $cont_estagio));
			
			if ($cont_estagio_pcd < $cota_estagio_pcd) {
				$legenda_estagio_pcd = 'Problema';
				$class_estagio_pcd = 'danger';
	
			} else if ($cont_estagio_pcd == $cota_estagio_pcd) {
				$legenda_estagio_pcd = 'No limite';
				$class_estagio_pcd = 'warning';
	
			} else if ($cont_estagio_pcd > $cota_estagio_pcd) {
				$legenda_estagio_pcd = 'OK';
				$class_estagio_pcd = 'success';
			}
			
		} else {
			$cota_estagio_pcd = 1;
			if ($cont_estagio_pcd < $cota_estagio_pcd) {
				$legenda_estagio_pcd = 'Problema';
				$class_estagio_pcd = 'danger';
	
			} else if ($cont_estagio_pcd == $cota_estagio_pcd) {
				$legenda_estagio_pcd = 'No limite';
				$class_estagio_pcd = 'warning';
	
			} else if ($cont_estagio_pcd > $cota_estagio_pcd) {
				$legenda_estagio_pcd = 'OK';
				$class_estagio_pcd = 'success';
			}
		}

		if ($cont_estagio > $cota_estagio) {
			$legenda_estagio = 'Problema';
			$class_estagio = 'danger';

		} else if ($cont_estagio == $cota_estagio) {
			$legenda_estagio = 'No limite';
			$class_estagio = 'warning';

		} else if ($cont_estagio < $cota_estagio) {
			$legenda_estagio = 'OK';
			$class_estagio = 'success';
		}
		
		echo "<div class='row'>";
		echo "<div class='col-md-12'>";
		echo "<table class='table table-hover dataTable' style='font-size='14px'>";
		echo "<thead>";
			echo "<tr>";
				echo "<th>Formato</th>";
				echo "<th>Cota</th>";
				echo "<th>Temos</th>";
				echo "<th>Situação</th>";
				echo "<th></th>";
			echo "</tr>";
		echo "</thead>";
		echo "<tbody>";
			echo "<tr>";
				echo "<td>Efetivo</td>";
				echo "<td class='active'></td>";
				echo "<td class='text-center info'>".$cont_efetivo."</td>";
				echo "<td></td>";
				echo "<td></td>";
			echo "</tr>";
			echo "<tr>";
				echo "<td>PCD</td>";
				echo "<td class='text-center active'>".$cota_pcd."</td>";
				echo "<td class='text-center info'>".$cont_pcd."</td>";
				echo "<td class='text-center ".$class_pcd."'>".$legenda_pcd."</td>";
				echo "<td>Cota mínima de 2% da quantidade de CLTs (arredondado para mais) quando a empresa tiver 100 ou mais CLTs.</td>";
			echo "</tr>";
			echo "<tr>";
				echo "<td>Jovem Aprendiz</td>";
				echo "<td class='text-center active'>".$cota_jovem_aprendiz."</td>";
				echo "<td class='text-center info'>".$cont_jovem_aprendiz."</td>";
				echo "<td class='text-center ".$class_jovem_aprendiz."'>".$legenda_jovem_aprendiz."</td>";
				echo "<td>Cota mínima de 5% da quantidade de CLTs (arredondado para mais) a partir de 7 funcionários.</td>";
			echo "</tr>";
			echo "<tr>";
				echo "<td>Estágio</td>";
				echo "<td class='text-center active'>".$cota_estagio."</td>";
				echo "<td class='text-center info'>".$cont_estagio."</td>";
				echo "<td class='text-center ".$class_estagio."'>".$legenda_estagio."</td>";
				echo "<td>Máximo de 20% da quantidade de CLT somados a quantidade de CLT-PCD.</td>";
			echo "</tr>";
			echo "<tr>";
				echo "<td>Estágio PCD</td>";
				echo "<td class='text-center active'>".$cota_estagio_pcd."</td>";
				echo "<td class='text-center info'>".$cont_estagio_pcd."</td>";
				echo "<td class='text-center ".$class_estagio_pcd."'>".$legenda_estagio_pcd."</td>";
				echo "<td>Cota mínima de 10% das vagas de estagios devem ser para Estagiários PCD. A partir de 10 estagiários.</td>";
			echo "</tr>";
			echo "<tr>";
				echo "<td>Terceirizado</td>";
				echo "<td class='text-center active'></td>";
				echo "<td class='text-center info'>".$cont_terceirizado."</td>";
				echo "<td></td>";
				echo "<td></td>";
			echo "</tr>";
		echo "</tbody>";
		echo "<tfoot>";
			echo "<tr class='active'>";
				echo "<th>Total de funcionários</th>";
				echo "<td class='active'></td>";
				echo "<td class='text-center'>".$total_funcionarios."</td>";
				echo "<td></td>";
				echo "<td></td>";
			echo "</tr>";
			echo "<tr class='active'>";
				echo "<th>Total com sócios</th>";
				echo "<td class='active'></td>";
				echo "<td class='text-center'>".$total_com_socios."</td>";
				echo "<td></td>";
				echo "<td></td>";
			echo "</tr>";
		echo "</tfoot>";
		echo "</table>";
		echo "</div>";
		echo "</div><hr><br>";

    } else {
        echo "<table class='table table-bordered'>";
            echo "<tbody>";
                echo "<tr>";
                    echo "<td class='text-center'><h4>Não foram encontrados resultados!</h4></td>";
                echo "</tr>";
            echo "</tbody>";
        echo "</table>";
    }
    echo "</div>";
}

function porcentagem( $porcentagem, $total ) {
	return ( $porcentagem / 100 ) * $total;
}

?>