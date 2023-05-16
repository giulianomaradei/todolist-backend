<?php
require_once(__DIR__."/../class/System.php");

$tipo_relatorio = (!empty($_POST['tipo_relatorio'])) ? $_POST['tipo_relatorio'] : 1;
$id_contrato_plano_pessoa = (!empty($_POST['id_contrato_plano_pessoa'])) ? $_POST['id_contrato_plano_pessoa'] : '';
$hora_de = (!empty($_POST['hora_de'])) ? $_POST['hora_de'] : '';
$hora_ate = (!empty($_POST['hora_ate'])) ? $_POST['hora_ate'] : '';
$dia = (!empty($_POST['dia'])) ? $_POST['dia'] : '';
$gerar = (!empty($_POST['gerar'])) ? 1 : 0;

if($gerar){
	$collapse = '';
	$collapse_icon = 'plus';
}else{
	$collapse = 'in';
	$collapse_icon = 'minus';
}

if($id_contrato_plano_pessoa){
	$dados_contrato = DBRead('', 'tb_contrato_plano_pessoa a', "INNER JOIN tb_plano b ON a.id_plano = b.id_plano INNER JOIN tb_pessoa c ON a.id_pessoa = c.id_pessoa WHERE a.id_contrato_plano_pessoa = '$id_contrato_plano_pessoa'", "a.*, b.cod_servico, b.nome AS 'plano', c.nome AS 'nome_pessoa'");

	    if($dados_contrato[0]['nome_contrato']){
	        $nome_contrato = " (".$dados_contrato[0]['nome_contrato'].") ";
	    }

	    $contrato = $dados_contrato[0]['nome_pessoa'] . " ". $nome_contrato ." - " . getNomeServico($dados_contrato[0]['cod_servico']) . " - " . $dados_contrato[0]['plano'] . " (" . $dados_contrato[0]['id_contrato_plano_pessoa'] . ")";
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
	                    <h3 class="panel-title text-left pull-left" style="margin-top: 2px;">Relatório - Horários:</h3>
	                    <div class="panel-title text-right pull-right"><button data-toggle="collapse" data-target="#accordionRelatorio" class="btn btn-xs btn-info" type="button" title="Visualizar filtros"><i id="i_collapse" class="fa fa-<?=$collapse_icon?>"></i></button></div>
	                </div>
	                <div id="accordionRelatorio" class="panel-collapse collapse <?=$collapse?>">
	                	<div class="panel-body">	                		
                			<div class="row">
                				<div class="col-md-12">
                					<div class="form-group">
								        <label for="">Tipo de Relatório:</label>
								        <select name="tipo_relatorio" id="tipo_relatorio" class="form-control input-sm">
								        	<option value="1" <?php if($tipo_relatorio == '1'){ echo 'selected';}?>>Horários que mandam as chamadas para a Belluno</option>
								        </select>
								    </div>
                				</div>
                			</div>
							<div class="row" id="row_contrato">
								<div class="col-md-12">
									<div class="form-group">
								        <label>Contrato (cliente):</label>
								        <div class="input-group">
		                                    <input class="form-control input-sm" id="busca_contrato" type="text" name="busca_contrato"  value="<?=$contrato?>" placeholder="Informe o nome ou CNPJ..." autocomplete="off" readonly />
		                                    <div class="input-group-btn">
		                                        <button class="btn btn-info btn-sm" id="habilita_busca_contrato" name="habilita_busca_contrato" type="button" title="Clique para selecionar o contrato" style="height: 30px;"><i class="fa fa-search"></i></button>
		                                    </div>
		                                </div>
		                                <input type="hidden" name="id_contrato_plano_pessoa" id="id_contrato_plano_pessoa" value="<?=$id_contrato_plano_pessoa;?>" />
								    </div>
								</div>
							</div>
							<div class="row" id="row_contrato">
								<div class="col-md-12">
									<div class="form-group">
								        <label>*Dias da semana:</label>
								        <select class='form-control input-sm dia' name='dia'>
											<option <?= $dia == '' ? 'selected' : '' ?>></option>
											<option value='1' <?= $dia == '1' ? 'selected' : '' ?>>Seg. a Dom.</option>
											<option value='2' <?= $dia == '2' ? 'selected' : '' ?>>Seg. a Sab.</option>
											<option value='3' <?= $dia == '3' ? 'selected' : '' ?>>Seg. a Sex.</option>
											<option value='13' <?= $dia == '13' ? 'selected' : '' ?>>Seg. a Qui.</option>
											<option value='4' <?= $dia == '4' ? 'selected' : '' ?>>Dom. e Feriados</option>
											<option value='5' <?= $dia == '5' ? 'selected' : '' ?>>Feriados</option>
											<option value='6' <?= $dia == '6' ? 'selected' : '' ?>>Domingo</option>
											<option value='7' <?= $dia == '7' ? 'selected' : '' ?>>Segunda</option>
											<option value='8' <?= $dia == '8' ? 'selected' : '' ?>>Terça</option>
											<option value='9' <?= $dia == '9' ? 'selected' : '' ?>>Quarta</option>
											<option value='10' <?= $dia == '10' ? 'selected' : '' ?>>Quinta</option>
											<option value='11' <?= $dia == '11' ? 'selected' : '' ?>>Sexta</option>
											<option value='12' <?= $dia == '12' ? 'selected' : '' ?>>Sabado</option>
										</select>
								    </div>
								</div>
							</div>
							<div class="row" id="row_periodo">
								<div class="col-md-6">
									<div class="form-group" >
								        <label>*Hora De:</label>
								        <input type="time" class="form-control input-sm" name="hora_de" value="<?=$hora_de?>">
								    </div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
								        <label>*Hora Até:</label>
								        <input type="time" class="form-control input-sm" name="hora_ate" value="<?=$hora_ate?>">
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
	<div id="resultado" class="row" style="display:block;">
		<?php
		
		$tipo_relatorio = $_POST['tipo_relatorio'];
		$id_contrato_plano_pessoa = $_POST['id_contrato_plano_pessoa'];
		$dia = $_POST['dia'];
		$hora_de = $_POST['hora_de'];
		$hora_ate = $_POST['hora_ate'];
		$gerar = $_POST['gerar'];
		
		if($gerar){
			//if($tipo_relatorio == 1){
				relatorio_horarios($hora_de, $hora_ate, $id_contrato_plano_pessoa, $tipo_relatorio, $dia);
			//}
		}
		
		?>
	</div>
</div>
<script>

	// Atribui evento e função para limpeza dos campos
    $('#busca_contrato').on('input', limpaCamposContrato);
    // Dispara o Autocomplete da pessoa a partir do segundo caracter
    $("#busca_contrato").autocomplete({
            minLength: 2,
            source: function(request, response){
                $.ajax({
                    url: "/api/ajax?class=ContratoAutocomplete.php",
                    dataType: "json",
                    data: {
                        acao: 'autocomplete',
                        parametros: {
                            'nome' : $('#busca_contrato').val(),
                            'cod_servico' : 'call_suporte'
                        },
						token: '<?= $request->token ?>'
                    },
                    success: function(data){
                        response(data);
                    }
                });
            },
            focus: function(event, ui){
                $("#busca_contrato").val(ui.item.nome + " " + ui.item.nome_contrato +" - " + ui.item.servico + " - " + ui.item.plano + " (" + ui.item.id_contrato_plano_pessoa + ")");
                carregarDadosContrato(ui.item.id_contrato_plano_pessoa);
                return false;
            },
            select: function(event, ui){
				$("#busca_contrato").val(ui.item.nome + " "+ ui.item.nome_contrato + " - " + ui.item.servico + " - " + ui.item.plano + " (" + ui.item.id_contrato_plano_pessoa + ")");
                $('#busca_contrato').attr("readonly", true);
                return false;
            }
        })
        .autocomplete("instance")._renderItem = function(ul, item){
            if(!item.razao_social){
                item.razao_social = '';
            }
            if(!item.cpf_cnpj){
                item.cpf_cnpj = '';
            }if(!item.nome_contrato){
                item.nome_contrato = '';
            }else{
                item.nome_contrato = ' ('+item.nome_contrato+') '; 
            }
            return $("<li>").append("<a><strong>"+item.id_contrato_plano_pessoa + " - " + item.nome + ""+item.nome_contrato+" </strong><br>" +item.razao_social+ "<br>" +item.cpf_cnpj+ "<br>" + item.servico + " - " + item.plano + " (" + item.id_contrato_plano_pessoa + ")" + "</a><hr style='margin-bottom: 0px;'>").appendTo(ul);
    	};
    // Função para carregar os dados da consulta nos respectivos campos
    function carregarDadosContrato(id) {
        var busca = $('#busca_contrato').val();
        if(busca != "" && busca.length >= 2){
            $.ajax({
                url: "/api/ajax?class=ContratoAutocomplete.php",
                dataType: "json",
                data: {
                    acao: 'consulta',
                    parametros: {
                        'id' : id,
                    },
					token: '<?= $request->token ?>'
                },
                success: function (data) {
                    $('#id_contrato_plano_pessoa').val(data[0].id_contrato_plano_pessoa);
                    seleciona_contrato(data[0].id_contrato_plano_pessoa);
                }
            });
        }
    }
    // Função para limpar os campos caso a busca esteja vazia
    function limpaCamposContrato(){
        var busca = $('#busca_contrato').val();
        if (busca == "") {
            $('#id_contrato_plano_pessoa').val('');
        }
    }
    $(document).on('click', '#habilita_busca_contrato', function(){
        $('#id_contrato_plano_pessoa').val('');
        $('#busca_contrato').val('');
        $('#busca_contrato').attr("readonly", false);
        $('#busca_contrato').focus();
    });

    function seleciona_contrato(id_contrato_plano_pessoa){
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
	
	$(function () {
	  $('[data-toggle="tooltip"]').tooltip()
	})

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

function relatorio_horarios($hora_de, $hora_ate, $contrato, $tipo, $dia){

	$filtra_contrato = "";
	$filtro_horaDe = "";
	$filtro_horaAte = "";
	$filtro_dia = "";

	$dias_semana = array(
		"1" => "Seg. a Dom.",
		"2" => "Seg. a Sab.",
		"3" => "Seg. a Sex.",
		"4" => "Dom. e Feriados",
		"5" => "Feriados",
		"6" => "Domingo",
		"7" => "Segunda",
		"8" => "Terça",
		"9" => "Quarta",
		"10" => "Quinta",
		"11" => "Sexta",
		"12" => "Sabado",
		"13" => "Seg. a Qui."
	);

	if($contrato){
		$filtra_contrato = "AND b.id_contrato_plano_pessoa = '$contrato'";
	}else{
		$legenda_contrato = "Todos";
	}

	if($contrato){
		$dados_contrato = DBRead('','tb_contrato_plano_pessoa a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa INNER JOIN tb_plano c ON a.id_plano = c.id_plano WHERE a.id_contrato_plano_pessoa = '$contrato'", "a.id_contrato_plano_pessoa, b.nome AS nome_pessoa, c.nome AS plano");
		foreach($dados_contrato as $key => $conteudo){
			$legenda_contrato = $dados_contrato[$key]['nome_pessoa']. " - ".$dados_contrato[$key]['plano']." (".$dados_contrato[$key]['id_contrato_plano_pessoa'].")";
		}
	}

	if($hora_de){
		$filtro_horaDe = "AND a.hora_inicio >= '$hora_de'";
		$legenda_horaDe = "Hora de: " . $hora_de;
	}else{
		$legenda_horaDe = "Qualquer";
	}

	if($hora_ate){
		$filtro_horaAte = "AND a.hora_fim <= '$hora_ate'";
		$legenda_horaAte = "Hora até: " . $hora_ate;
	}else{
		$legenda_horaAte = "Qualquer";
	}

	if($dia){
		$filtro_dia = "AND a.dia = '$dia'";
		$legenda_dias = $dias_semana[$dia];
	}else{
		$legenda_dias = "Qualquer";
	}

	$dados_horario = DBRead('','tb_horario a', "INNER JOIN tb_horario_contrato b ON a.id_horario_contrato = b.id_horario_contrato INNER JOIN tb_contrato_plano_pessoa c ON b.id_contrato_plano_pessoa = c.id_contrato_plano_pessoa INNER JOIN tb_pessoa d ON c.id_pessoa = d.id_pessoa INNER JOIN tb_plano e ON c.id_plano = e.id_plano WHERE b.tipo = 8 $filtra_contrato $filtro_horaDe $filtro_horaAte $filtro_dia", "a.*, b.*, d.nome AS nome_pessoa, e.nome AS plano");

	$data_hoje = getDataHora();
	$data_hoje = converteDataHora($data_hoje);

	$gerado = "<span style=\"font-size: 14px;\"><strong>Gerado em: </strong> $data_hoje</span>";

	echo "<div class=\"col-md-12\" style=\"padding: 0\">";
	echo "<legend style=\"text-align:center;\"><strong>Relatório - Horários</strong><br>$gerado</legend>";
	echo "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong> Contrato - </strong>".$legenda_contrato.", <strong>Dias da semana - </strong>".$legenda_dias.", <strong>Hora De - </strong>".$legenda_horaDe.", <strong>Hora Até - </strong>".$legenda_horaAte."</legend>";

	$corpo = '';

	if($dados_horario){

		$corpo = $corpo . "
		<table class='table table-hover dataTable' style='margin-bottom:0;'>
			<thead>
				<tr>
					<th>Contrato</th>
					<th>Dias</th>
					<th>Hora De</th>
					<th>Hora Até</th>
				</tr>
			</thead>
			<tbody>";

			foreach($dados_horario as $key => $conteudo){

				$corpo = $corpo . "<tr>
					<td>".$dados_horario[$key]['nome_pessoa']. " - ".$dados_horario[$key]['plano']." (".$dados_horario[$key]['id_contrato_plano_pessoa'].")</td>
					<td>".$dias_semana[$dados_horario[$key]['dia']]."</td>
					<td>".$dados_horario[$key]['hora_inicio']."</td>
					<td>".$dados_horario[$key]['hora_fim']."</td>
				</tr>";
			}

		$corpo = $corpo . "</tbody>
		</table>
		";
		echo $corpo;
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
					/*var buttons = new $.fn.dataTable.Buttons(table, {
						buttons: [
							{
								extend: 'excelHtml5',
								text: '<i class=\"fa fa-file-excel-o\" aria-hidden=\"true\"></i> Exportar',
								filename: 'relatorio_horarios',
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
					}).container().appendTo($('#panel_buttons'));*/
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