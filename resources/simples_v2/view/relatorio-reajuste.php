<?php
require_once(__DIR__."/../class/System.php");

$gerar = (!empty($_POST['gerar'])) ? 1 : 0;
$data_hoje = new DateTime(getDataHora('data'));
$dia = $data_hoje->format('d');
$mes = (!empty($_POST['mes'])) ? $_POST['mes'] : $data_hoje->format('m');
$ano = (!empty($_POST['ano'])) ? $_POST['ano'] : $data_hoje->format('Y');
$id_responsavel_tecnico = (!empty($_POST['id_responsavel_tecnico'])) ? $_POST['id_responsavel_tecnico'] : '';

if($gerar){
	$collapse = '';
	$collapse_icon = 'plus';
}else{
	$collapse = 'in';
	$collapse_icon = 'minus';
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
	                    <h3 class="panel-title text-left pull-left" style="margin-top: 2px;">Relatório - Reajustes:</h3>
	                    <div class="panel-title text-right pull-right"><button data-toggle="collapse" data-target="#accordionRelatorio" class="btn btn-xs btn-info" type="button" title="Visualizar filtros"><i id="i_collapse" class="fa fa-<?=$collapse_icon?>"></i></button></div>
	                </div>
	                <div id="accordionRelatorio" class="panel-collapse collapse <?=$collapse?>">
	                	<div class="panel-body">
	                		
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label>Mês:</label> 
										<select name="mes" id="mes" class="form-control input-sm">
											<option value="01" <?php if($mes == '01'){ echo 'selected';}?>>Janeiro</option>
											<option value="02" <?php if($mes == '02'){ echo 'selected';}?>>Fevereiro</option>
											<option value="03" <?php if($mes == '03'){ echo 'selected';}?>>Março</option>
											<option value="04" <?php if($mes == '04'){ echo 'selected';}?>>Abril</option>
											<option value="05" <?php if($mes == '05'){ echo 'selected';}?>>Maio</option>
											<option value="06" <?php if($mes == '06'){ echo 'selected';}?>>Junho</option>
											<option value="07" <?php if($mes == '07'){ echo 'selected';}?>>Julho</option>
											<option value="08" <?php if($mes == '08'){ echo 'selected';}?>>Agosto</option>
											<option value="09" <?php if($mes == '09'){ echo 'selected';}?>>Setembro</option>
											<option value="10" <?php if($mes == '10'){ echo 'selected';}?>>Outubro</option>
											<option value="11" <?php if($mes == '11'){ echo 'selected';}?>>Novembro</option>
											<option value="12" <?php if($mes == '12'){ echo 'selected';}?>>Dezembro</option>
										</select>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label>Ano:</label> 
										<select name="ano" id="ano" class="form-control input-sm">
											<option value="2019" <?php if($ano == '2019'){ echo 'selected';}?>>2019</option>
											<option value="2020" <?php if($ano == '2020'){ echo 'selected';}?>>2020</option>
											<option value="2021" <?php if($ano == '2021'){ echo 'selected';}?>>2021</option>
											<option value="2022" <?php if($ano == '2022'){ echo 'selected';}?>>2022</option>
											<option value="2023" <?php if($ano == '2023'){ echo 'selected';}?>>2023</option>
											<option value="2024" <?php if($ano == '2024'){ echo 'selected';}?>>2024</option>	
									</select>
									</div>
								</div>
							</div>

							<div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Responsável Técnico:</label>
                                        <select class="form-control input-sm" id="id_responsavel_tecnico" name="id_responsavel_tecnico">
                                            <option value=''>Qualquer</option>
                                            <?php
                                                $dados_responsavel_tecnico = DBRead('', 'tb_perfil_sistema a', "INNER JOIN tb_usuario b ON a.id_perfil_sistema = b.id_perfil_sistema INNER JOIN tb_pessoa c ON b.id_pessoa = c.id_pessoa WHERE a.id_perfil_sistema = 4 AND b.status = 1 ORDER BY c.nome ASC","b.id_usuario, c.nome");
                                                if ($dados_responsavel_tecnico) {
                                                    foreach ($dados_responsavel_tecnico as $conteudo_responsavel_tecnico) {
														$selected = $id_responsavel_tecnico == $conteudo_responsavel_tecnico['id_usuario'] ? "selected" : "";
                                                        echo "<option value='".$conteudo_responsavel_tecnico['id_usuario']."' ".$selected.">".$conteudo_responsavel_tecnico['nome']."</option>";
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
			relatorio_imposto($mes, $ano, $dia, $id_responsavel_tecnico);			
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
        modalAguarde();
    });
    $(document).ready(function(){
	    $('#aguarde').hide();
	    $('#resultado').show();
	    $("#gerar").prop("disabled", false);
	});   

</script>
<?php

function relatorio_imposto($mes, $ano, $dia, $id_responsavel_tecnico){

	if ($id_responsavel_tecnico) {
        $filtro_responsavel_tecnico = "AND a.id_responsavel_tecnico = '".$id_responsavel_tecnico."'";
        $dados_responsavel_tecnico = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_usuario = '".$id_responsavel_tecnico."' ","b.nome");
        $legenda_responsavel_tecnico = $dados_responsavel_tecnico[0]['nome'];

    } else {
        $filtro_responsavel_tecnico = "";
        $legenda_responsavel_tecnico = 'Qualquer';
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
	$data = $ano.'-'.$mes.'-'.$dia;
	$data_inicial = new DateTime($data);
	$data_inicial->modify('first day of this month');
	$data_final = new DateTime($data);
	$data_final->modify('last day of this month');

	$periodo_amostra = "<span style=\"font-size: 14px;\"><strong>Referente ao mês de ".$dados_meses[$mes]." de ".$ano."</strong></span>";
	$gerado = "<span style=\"font-size: 14px;\"><strong>Gerado em: </strong> ".converteDataHora(getDataHora())."</span>";	

	echo "<div class=\"col-md-12\" style=\"padding: 0\">";
	echo "<legend style=\"text-align:center;\"><strong>Relatório - Reajustes</strong><br>$gerado</legend>";
	echo "<legend style=\"text-align:center;\">$periodo_amostra</legend>";
	echo "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong> Responsável Técnico - </strong>".$legenda_responsavel_tecnico." </span></legend>";

	$dados_consulta = DBRead('', 'tb_contrato_plano_pessoa a',"INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa INNER JOIN tb_usuario c ON a.id_responsavel_tecnico = c.id_usuario INNER JOIN tb_pessoa d ON c.id_pessoa = d.id_pessoa WHERE a.status = '1' AND a.data_ajuste <= '".$data_final->format('Y-m-d')."' $filtro_responsavel_tecnico ", "a.*, b.*, d.nome AS nome_responsavel_tecnico");

	if($dados_consulta){
		echo '
            <table class="table table-striped dataTable" style="margin-bottom:0;">
                <thead>
                    <tr>
                        <th class="col-md-1">#</th>
                        <th class="col-md-2">Cliente</th>
                        <th class="col-md-2">Plano</th>
                        <th class="col-md-1">Índice</th>
                        <th class="col-md-1">Data de Início</th>
                        <th class="col-md-1">Data de Reajuste</th>
                        <th class="col-md-2">Período de Contrato</th>
                        <th class="col-md-2">Responsável Técnico</th>
                    </tr>
                </thead>
                <tbody>
        ';              
		foreach($dados_consulta as $dado_consulta){
			if($dado_consulta['nome_contrato']){
                $nome_contrato = " (".$dado_consulta['nome_contrato'].") ";
            }else{
                $nome_contrato = '';
            }            
            $contrato = $dado_consulta['nome']." ".$nome_contrato ;
            $dados_planos = DBRead('','tb_plano',"WHERE id_plano = '".$dado_consulta['id_plano']."'");
            echo "<tr>";
				echo "<td style='vertical-align: middle;'>".$dado_consulta['id_contrato_plano_pessoa']."</td>";
				echo "<td style='vertical-align: middle;'><a href='/api/iframe?token=<?php echo $request->token ?>&view=contrato-form&alterar=".$dado_consulta['id_contrato_plano_pessoa']."' target='_blank'>".$contrato."</a></td>";
				echo "<td style='vertical-align: middle;'>".getNomeServico($dados_planos[0]['cod_servico']) . " - " .$dados_planos[0]['nome']."</td>";
				echo "<td style='vertical-align: middle;'>".$dado_consulta['indice_reajuste']."</td>";
				echo "<td style='vertical-align: middle;'>".converteDataHora($dado_consulta['data_inicio'],'data')."</td>";
				echo "<td style='vertical-align: middle;'>".converteDataHora($dado_consulta['data_ajuste'],'data')."</td>";
				echo "<td style='vertical-align: middle;'>".$dado_consulta['periodo_contrato']." meses</td>";
				echo "<td style='vertical-align: middle;'>".$dado_consulta['nome_responsavel_tecnico']."</td>";
			echo "</tr>";			
		}
			echo "</tbody>";
		echo "</table>";
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
                        aaSorting: [[1, 'asc']],		        
						\"searching\": false,
						\"paging\":   false,
						\"info\":     false
					});

					var buttons = new $.fn.dataTable.Buttons(table, {
						buttons: [
							{
								extend: 'excelHtml5',
								text: '<i class=\"fa fa-file-excel-o\" aria-hidden=\"true\"></i> Exportar',
								filename: 'relatorio_reajustes',
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