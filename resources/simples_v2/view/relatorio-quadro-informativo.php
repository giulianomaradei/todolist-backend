<?php
require_once(__DIR__."/../class/System.php");

$tipo_relatorio = (!empty($_POST['tipo_relatorio'])) ? $_POST['tipo_relatorio'] : 1;
$id_contrato_plano_pessoa = (!empty($_POST['id_contrato_plano_pessoa'])) ? $_POST['id_contrato_plano_pessoa'] : '';
$hora_de = (!empty($_POST['hora_de'])) ? $_POST['hora_de'] : '';
$hora_ate = (!empty($_POST['hora_ate'])) ? $_POST['hora_ate'] : '';
$dia = (!empty($_POST['dia'])) ? $_POST['dia'] : '';
$gerar = (!empty($_POST['gerar'])) ? 1 : 0;
$modulo = (!empty($_POST['modulo'])) ? $_POST['modulo'] : '';
$data_de = (!empty($_POST['data_de'])) ? $_POST['data_de'] : '01'.substr(convertedata(getDataHora('data')),2);
$data_ate = (!empty($_POST['data_ate'])) ? $_POST['data_ate'] : convertedata(getDataHora('data'));
$id_tipo_central_telefonica = (!empty($_POST['id_tipo_central_telefonica'])) ? $_POST['id_tipo_central_telefonica'] : '';
$id_plano = (!empty($_POST['id_plano'])) ? $_POST['id_plano'] : '';
$usuario = (!empty($_POST['usuario'])) ? $_POST['usuario'] : '';
$canal_atendimento = (!empty($_POST['canal_atendimento'])) ? $_POST['canal_atendimento'] : '';

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

if($tipo_relatorio == 1){
	$display_row_dia_semana = '';
	$display_row_hora = '';
	$display_row_central_telefonica = 'style="display:none;"';
	$display_row_plano = 'style="display:none;"';
	$display_row_servico = 'style="display:none;"';
	$display_row_canal_atendimento = '';
	$display_row_usuario = 'style="display:none;"';
	$display_row_modulo = 'style="display:none;"';
	$display_row_data = 'style="display:none;"';

}else if($tipo_relatorio == 2){
	$display_row_dia_semana = 'style="display:none;"';
	$display_row_hora = 'style="display:none;"';
	$display_row_central_telefonica = '';
	$display_row_plano = '';
	$display_row_servico = '';
	$display_row_canal_atendimento = 'style="display:none;"';
	$display_row_usuario = 'style="display:none;"';
	$display_row_modulo = 'style="display:none;"';
	$display_row_data = 'style="display:none;"';

}else if($tipo_relatorio == 3){
	$display_row_dia_semana = 'style="display:none;"';
	$display_row_hora = 'style="display:none;"';
	$display_row_central_telefonica = 'style="display:none;"';
	$display_row_plano = 'style="display:none;"';
	$display_row_servico = 'style="display:none;"';
	$display_row_canal_atendimento = 'style="display:none;"';
	$display_row_usuario = '';
	$display_row_modulo = '';
	$display_row_data = '';
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
	                    <h3 class="panel-title text-left pull-left" style="margin-top: 2px;">Relatório - Quadro Informativo:</h3>
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
										<option value="2" <?php if($tipo_relatorio == '2'){ echo 'selected';}?>>Central Telefônica</option>
										<option value="3" <?php if($tipo_relatorio == '3'){ echo 'selected';}?>>Histórico</option>
								        </select>
								    </div>
                				</div>
                			</div>
							<div class="row" id="row_contrato">
								<div class="col-md-12">
									<div class="form-group">
								        <label>Contrato (Cliente):</label>
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
							<div class="row" id="row_dia_semana" <?=$display_row_dia_semana?>>
								<div class="col-md-12">
									<div class="form-group">
								        <label>Dias da Semana:</label>
								        <select class='form-control input-sm dia' name='dia'>
											<option value=''   <?= $dia == '' ? 'selected' : '' ?>>Qualquer</option>
											<option value='1'  <?= $dia == '1' ? 'selected' : '' ?>>Seg. a Dom.</option>
											<option value='2'  <?= $dia == '2' ? 'selected' : '' ?>>Seg. a Sab.</option>
											<option value='3'  <?= $dia == '3' ? 'selected' : '' ?>>Seg. a Sex.</option>
											<option value='13' <?= $dia == '13' ? 'selected' : '' ?>>Seg. a Qui.</option>
											<option value='4'  <?= $dia == '4' ? 'selected' : '' ?>>Dom. e Feriados</option>
											<option value='5'  <?= $dia == '5' ? 'selected' : '' ?>>Feriados</option>
											<option value='6'  <?= $dia == '6' ? 'selected' : '' ?>>Domingo</option>
											<option value='7'  <?= $dia == '7' ? 'selected' : '' ?>>Segunda</option>
											<option value='8'  <?= $dia == '8' ? 'selected' : '' ?>>Terça</option>
											<option value='9'  <?= $dia == '9' ? 'selected' : '' ?>>Quarta</option>
											<option value='10' <?= $dia == '10' ? 'selected' : '' ?>>Quinta</option>
											<option value='11' <?= $dia == '11' ? 'selected' : '' ?>>Sexta</option>
											<option value='12' <?= $dia == '12' ? 'selected' : '' ?>>Sabado</option>
										</select>
								    </div>
								</div>
							</div>
							<div class="row" id="row_hora" <?=$display_row_hora?>>
								<div class="col-md-6">
									<div class="form-group" >
								        <label>Hora De:</label>
								        <input type="time" class="form-control input-sm" name="hora_de" value="<?=$hora_de?>">
								    </div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
								        <label>Hora Até:</label>
								        <input type="time" class="form-control input-sm" name="hora_ate" value="<?=$hora_ate?>">
								    </div>
								</div>
							</div>
							<div class="row" id="row_central_telefonica" <?=$display_row_central_telefonica?>>
								<div class="col-md-12">
									<div class="form-group" >
										<label>Central Telefônica:</label>
										<select name="id_tipo_central_telefonica" class="form-control input-sm">
											<option value="">Todas</option>
												<?php
												$dados_id_tipo_central_telefonica = DBRead('', 'tb_tipo_central_telefonica', "ORDER BY descricao ASC");
												if ($dados_id_tipo_central_telefonica) {
													foreach ($dados_id_tipo_central_telefonica as $conteudo_id_tipo_central_telefonica) {
														$selected = $id_tipo_central_telefonica == $conteudo_id_tipo_central_telefonica['id_tipo_central_telefonica'] ? "selected" : "";
														echo "<option value='" . $conteudo_id_tipo_central_telefonica['id_tipo_central_telefonica'] . "' ".$selected.">" . $conteudo_id_tipo_central_telefonica['descricao'] . "</option>";
													}
												}
												?>
										</select>
								    </div>
								</div>
							</div>

							<div class="row" id="row_plano" <?=$display_row_plano?>>
								<div class="col-md-12">
									<div class="form-group" >
								        <label>Plano:</label>
								        <select name="id_plano" class="form-control input-sm">
											<option value="">Todos</option>
												<?php
												$dados_id_plano = DBRead('', 'tb_plano', "WHERE status = 1 AND (cod_servico = 'call_suporte' OR cod_servico = 'call_ativo' ) ORDER BY nome ASC", "id_plano, cod_servico, nome");
												if ($dados_id_plano) {
													foreach ($dados_id_plano as $conteudo_id_plano) {
														$selected = $id_plano == $conteudo_id_plano['id_plano'] ? "selected" : "";
														echo "<option value='" . $conteudo_id_plano['id_plano'] . "' ".$selected.">" . getNomeServico($conteudo_id_plano['cod_servico'])." - ".$conteudo_id_plano['nome'] . "</option>";
													}
												}
												?>
										</select>
								    </div>
								</div>
							</div>

							<div class="row" id="row_canal_atendimento" <?=$display_row_canal_atendimento?>>
                				<div class="col-md-12">
                					<div class="form-group">
								        <label for="">Canal de Atendimento:</label>
										<select class="form-control input-sm" name="canal_atendimento" id="canal_atendimento">
											<option value=''>Qualquer</option>                                    
											<option value='telefone' <?php if($canal_atendimento == 'telefone'){ echo 'selected';}?>>Telefone</option>
											<option value='texto' <?php if($canal_atendimento == 'texto'){ echo 'selected';}?>>Texto</option>
										</select>
								    </div>
                				</div>
                			</div>
							
							<!-- INICIO FILTRO USUARIO -->
							<div class="row" id="row_usuario" <?=$display_row_usuario?>>
								<div class="col-md-12">
									<div class="form-group">
										<label class="form-control-imput-sm" name="usuario" id="usuario">Usuario:</label>
											<select class="form-control input-sm" name="usuario" id="usuario">
												<option value="">Todos</option>
												<?php
													$dados_usuario = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.status = 1 ORDER BY b.nome");	
													foreach($dados_usuario as $dado){
														$selected = $usuario == $dado['id_usuario'] ? "selected" : ""; ?>
														<option value="<?=$dado['id_usuario']?>" <?=$selected?>><?=$dado['nome']?></option>
													<?php } ?>
											</select>
									</div>
								</div>
							</div>
							<!-- FIM FILTRO USUARIO -->

							<!-- INICIO FILTRO MODULO -->
							<div class="row" id="row_modulo" <?=$display_row_modulo?>>
								<div class="col-md-12">
									<div class="form-group">
										<label class="form-control-imput-sm" name="modulo" id="modulo">Modulo:</label>
											<select class="form-control input-sm" name="modulo" id="modulo">
												<option value="">Todos</option>
												<?php
													$dados_modulo = DBRead('', 'tb_quadro_informativo_modulo', " ");	
													foreach($dados_modulo as $dado){ 
														$selected = $modulo == $dado['id_quadro_informativo_modulo'] ? "selected" : "";?>
														<option value="<?=$dado['id_quadro_informativo_modulo']?>" <?=$selected?> ><?=$dado['nome']?></option>
													<?php } ?>
											</select>
									</div>
								</div>
							</div>
							<!-- FIM FILTRO MODULO -->

							<!-- INICIO FILTRO DATA -->
							<div class="row" id="row_data" <?=$display_row_data?>>
								<div class="col-md-6">
									<div class="form-group" >
								        <label>Data Inicial:</label>
								        <input type="text" class="form-control input-sm date calendar" name="data_de" value="<?=$data_de?>">
								    </div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
								        <label>Data Final:</label>
								        <input type="text" class="form-control input-sm date calendar" name="data_ate" value="<?=$data_ate?>">
								    </div>
								</div>
							</div>
							<!-- FIM FILTRO DATA -->

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
		if($gerar){
			if($tipo_relatorio == 1){
				relatorio_horarios($hora_de, $hora_ate, $id_contrato_plano_pessoa, $dia, $canal_atendimento);
			}else if($tipo_relatorio == 2){
				relatorio_central_telefonica($id_tipo_central_telefonica, $id_plano, $id_contrato_plano_pessoa);
			}
			else if($tipo_relatorio == 3){
				historico_quadro_informativo($id_contrato_plano_pessoa, $usuario, $data_de, $data_ate, $modulo);
			}
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

	$('#tipo_relatorio').on('change',function(){
		tipo_relatorio = $(this).val();
		if(tipo_relatorio == 1){
			$('#row_dia_semana').show();
			$('#row_hora').show();
			$('#row_central_telefonica').hide();
			$('#row_plano').hide();
			$('#row_servico').hide();
			$('#row_canal_atendimento').show();
			$('#row_usuario').hide(); 
			$('#row_modulo').hide(); 
			$('#row_data').hide(); 

		}else if(tipo_relatorio == 2){
			$('#row_dia_semana').hide();
			$('#row_hora').hide();
			$('#row_central_telefonica').show();
			$('#row_plano').show();
			$('#row_servico').show();
			$('#row_canal_atendimento').hide();
			$('#row_usuario').hide(); 
			$('#row_modulo').hide(); 
			$('#row_data').hide(); 


		}else if(tipo_relatorio == 3){
			$('#row_dia_semana').hide();
			$('#row_hora').hide();
			$('#row_central_telefonica').hide();
			$('#row_plano').hide();
			$('#row_servico').hide();
			$('#row_canal_atendimento').hide();
			$('#row_usuario').show(); 
			$('#row_modulo').show(); 
			$('#row_data').show(); 

		}
	}); 

</script>
<?php

function relatorio_horarios($hora_de, $hora_ate, $contrato, $dia, $canal_atendimento){

	$filtra_contrato = "";
	$filtro_horaDe = "";
	$filtro_horaAte = "";
	$filtro_dia = "";

	$dias_semana = array(
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

	if($canal_atendimento){

		if($canal_atendimento == 'telefone'){
			$filtro_canal_atendimento = "b.tipo = 8";
			$legenda_canal_atendimento = "Telefone";

		}else{
			$filtro_canal_atendimento = "b.tipo = 9";
			$legenda_canal_atendimento = "Texto";

		}
	}else{
		$filtro_canal_atendimento = "(b.tipo = 8 OR b.tipo = 9)";
		$legenda_canal_atendimento = "Qualquer";
	}

	$dados_horario = DBRead('','tb_horario a', "INNER JOIN tb_horario_contrato b ON a.id_horario_contrato = b.id_horario_contrato INNER JOIN tb_contrato_plano_pessoa c ON b.id_contrato_plano_pessoa = c.id_contrato_plano_pessoa INNER JOIN tb_pessoa d ON c.id_pessoa = d.id_pessoa INNER JOIN tb_plano e ON c.id_plano = e.id_plano WHERE $filtro_canal_atendimento $filtra_contrato $filtro_horaDe $filtro_horaAte $filtro_dia", "a.*, b.*, d.nome AS nome_pessoa, e.nome AS plano");

	$data_hoje = getDataHora();
	$data_hoje = converteDataHora($data_hoje);

	$gerado = "<span style=\"font-size: 14px;\"><strong>Gerado em: </strong> $data_hoje</span>";

	echo "<div class=\"col-md-12\" style=\"padding: 0\">";
	echo "<legend style=\"text-align:center;\"><strong>Relatório - Horários</strong><br>$gerado</legend>";
	echo "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong> Contrato - </strong>".$legenda_contrato.", <strong>Dias da semana - </strong>".$legenda_dias.", <strong>Hora De - </strong>".$legenda_horaDe.", <strong>Hora Até - </strong>".$legenda_horaAte.", <strong>Canal de Atendimento - </strong>".$legenda_canal_atendimento."</legend>";

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
					<th>Canal de Atendimento</th>
				</tr>
			</thead>
			<tbody>";

			foreach($dados_horario as $key => $conteudo){

				if($dados_horario[$key]['tipo'] == 8){
					$tipo_canal_atendimento = "Telefone";
				}else{
					$tipo_canal_atendimento = "Texto";
				}

				$corpo = $corpo . "<tr>
					<td>".$dados_horario[$key]['nome_pessoa']. " - ".$dados_horario[$key]['plano']." (".$dados_horario[$key]['id_contrato_plano_pessoa'].")</td>
					<td>".$dias_semana[$dados_horario[$key]['dia']]."</td>
					<td>".$dados_horario[$key]['hora_inicio']."</td>
					<td>".$dados_horario[$key]['hora_fim']."</td>
					<td>".$tipo_canal_atendimento."</td>
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
	echo "<div>";

}

function relatorio_central_telefonica($id_tipo_central_telefonica, $id_plano, $id_contrato_plano_pessoa){

	if($id_contrato_plano_pessoa){
		$dados_id_contrato_plano_pessoa = DBRead('', 'tb_contrato_plano_pessoa a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_contrato_plano_pessoa = '".$id_contrato_plano_pessoa."' ", "b.nome");
		$legenda_id_contrato_plano_pessoa = $dados_id_contrato_plano_pessoa[0]['nome'];
		$filtro_id_contrato_plano_pessoa = " AND b.id_contrato_plano_pessoa = '".$id_contrato_plano_pessoa."' ";
	}else{
		$legenda_id_contrato_plano_pessoa = "Todos";
	}

	if($id_tipo_central_telefonica){
		$dados_id_tipo_central_telefonica = DBRead('', 'tb_tipo_central_telefonica', "WHERE id_tipo_central_telefonica = '".$id_tipo_central_telefonica."' ", "descricao");
		$legenda_id_tipo_central_telefonica = $dados_id_tipo_central_telefonica[0]['descricao'];
		$filtro_id_tipo_central_telefonica = " AND a.id_tipo_central_telefonica = '".$id_tipo_central_telefonica."' ";
	}else{
		$legenda_id_tipo_central_telefonica = "Todas";
	}

	if($id_plano){
		$dados_id_plano = DBRead('', 'tb_plano', "WHERE id_plano = '".$id_plano."' ", "id_plano, cod_servico, nome");
		$legenda_id_plano = getNomeServico($dados_id_plano[0]['cod_servico'])." - ".$dados_id_plano[0]['nome'];
		$filtro_id_plano = " AND b.id_plano = '".$id_plano."' ";
	}else{
		$legenda_id_plano = "Todos";
	}
    
	
    $dados_parametro = DBRead('', 'tb_parametros a', "INNER JOIN tb_contrato_plano_pessoa b ON a.id_contrato_plano_pessoa = b.id_contrato_plano_pessoa INNER JOIN tb_pessoa c ON b.id_pessoa = c.id_pessoa INNER JOIN tb_tipo_central_telefonica d ON a.id_tipo_central_telefonica = d.id_tipo_central_telefonica INNER JOIN tb_plano e ON b.id_plano = e.id_plano WHERE b.status = '1' AND b.id_pessoa != '2006' ".$filtro_id_tipo_central_telefonica." ".$filtro_id_plano." ".$filtro_id_contrato_plano_pessoa." ", "c.nome AS noma_contrato, d.descricao AS nome_central, e.nome As nome_plano, e.cod_servico");
    

	$data_hora = converteDataHora(getDataHora());
	echo "<div class=\"col-md-10 col-md-offset-1\" style=\"padding: 0\">";

	echo "<legend style=\"text-align:center;\"><strong>Relatório - Central Eletrônica</strong><br><span style=\"font-size: 14px;\"><strong>Gerado em:</strong> $data_hora</span></legend>";
	echo "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong> Contrato - </strong>".$legenda_id_contrato_plano_pessoa.",<strong> Central Telefônica - </strong>".$legenda_id_tipo_central_telefonica.", <strong> Plano - </strong>".$legenda_id_plano;
	echo "</legend>"; 
    
    if($dados_parametro){

		echo "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong> Total: </strong>".sizeof($dados_parametro)."</legend>";
        echo "<table class='table table-hover dataTable' style='font-size: 14px;'>";
			echo "<thead>";
				echo "<tr>";
					echo "<th class='col-md-4'>Contrato</th>";
					echo "<th class='col-md-4'>Central Telefônica</th>";
					echo "<th class='col-md-4'>Plano</th>";
				echo "</tr>";
			echo "</thead>";
            echo "<tbody>";
            $valor_total = 0;
			foreach ($dados_parametro as $conteudo_parametro) {
                $noma_contrato = $conteudo_parametro['noma_contrato'];
                $nome_central = $conteudo_parametro['nome_central'];
                $nome_plano = getNomeServico($conteudo_parametro['cod_servico'])." - ".$conteudo_parametro['nome_plano'];
				
				echo "<tr>";
				 	echo "<td>$noma_contrato</td>";
					echo "<td>$nome_central</td>";
					echo "<td>$nome_plano</td>";
				echo "</tr>";
			}
            echo "</tbody>";
		echo "</table>";
				
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
		
    </div>";
	
}

function historico_quadro_informativo($id_contrato_plano_pessoa, $usuario, $data_de, $data_ate, $modulo){
	if($id_contrato_plano_pessoa){
		$contrato = DBRead('', 'tb_contrato_plano_pessoa a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_contrato_plano_pessoa = '".$id_contrato_plano_pessoa."' ", "b.nome");	
		$legenda_contrato = $contrato[0]['nome'];		
		$filtro_contrato = 'AND id_contrato_plano_pessoa ='.$id_contrato_plano_pessoa;
	}else {
		$legenda_contrato = 'Todos';
		$filtro_contrato = '';
	}

	if($usuario){
		$dados_usuario = DBRead('', 'tb_usuario a', 'INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_usuario = '.$usuario.'', 'nome');	
		$legenda_usuario = $dados_usuario[0]['nome'];
		$filtro_usuario = ' AND id_usuario ='.$usuario;
	}else {
		$legenda_usuario = 'Todos';
		$filtro_usuario = '';
	}

	if($data_de || $data_ate){

		if($data_de){
			$legenda_data_hora = 'De '.$data_de;
			$filtro_data_hora = "AND data_hora >= '".converteDataHora($data_de)." 00:00:00'";
		}
		if($data_ate){
			$legenda_data_hora = $legenda_data_hora.' Até '.$data_ate;
			$filtro_data_hora = "$filtro_data_hora AND data_hora <= '".converteDataHora($data_ate)." 23:59:59'";
		}
	
	}else{
	$legenda_data_hora = 'Qualquer';
	$filtro_data_hora = '';
	}

	if($modulo){
		$legenda_modulo = DBRead('', 'tb_quadro_informativo_modulo', 'WHERE id_quadro_informativo_modulo = '.$modulo.'', 'nome');
		$legenda_modulo = $legenda_modulo[0]['nome'];
		$filtro_modulo = 'AND id_quadro_informativo_modulo ='.$modulo;
	}else {
		$legenda_modulo = 'Todos';
		$filtro_modulo = '';
	}

    $dados_quadro_informarivo_historico = DBRead('', 'tb_quadro_informativo_historico', "WHERE tipo = 1 $filtro_modulo $filtro_data_hora $filtro_contrato $filtro_usuario ORDER BY data_hora ASC");
	$data_hora = converteDataHora(getDataHora()); ?>
	
	<div class="col-md-12w" style="padding: 0">

	<legend style="text-align:center;">
		<strong>Relatório - Quadro Informativo Histórico</strong><br>
		<span style="font-size: 14px;"><strong>Gerado em:</strong> <?=$data_hora?></span>
	</legend>
	<legend style="text-align:center;"><span style="font-size: 14px;">
		<strong> Usuario - </strong><?=$legenda_usuario?>,
		<strong> Contrato - </strong><?=$legenda_contrato?>, 
		<strong> Data Hora - </strong><?=$legenda_data_hora?>,
		<strong> Módulo - </strong><?=$legenda_modulo?>
	</legend>
    
    <?php if($dados_quadro_informarivo_historico){ ?>

		<legend style="text-align:center;"><span style="font-size: 14px;"><strong> Total: </strong><?php sizeof($dados_quadro_informarivo_historico)?></legend>
        <table class='table table-hover' style='font-size: 14px;'>
			<thead>
				<tr>
					<th>#</th>
					<th class="col-md-1">Usuario</th>
					<th class="col-md-1">Contrato</th>
					<th class="col-md-1">Data Hora</th>
					<th class="col-md-2">Modulo</th>
					<th class="col-md-1">Ação</th>
					<th class="col-md-2">Legenda</th>
					<th class="col-md-3">Dados</th>
				</tr>
				</thead>
            <tbody>
	
	<?php foreach ($dados_quadro_informarivo_historico as $dados) {

		$id_quadro_informativo_historico = $dados['id_quadro_informativo_historico'];
		$usuario = DBRead('', 'tb_usuario a', 'INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_usuario = '.$dados['id_usuario'].'', 'nome');	
		$contrato = DBRead('', 'tb_contrato_plano_pessoa a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_contrato_plano_pessoa = '".$dados['id_contrato_plano_pessoa']."' ", "b.nome");
		$data_hora = converteDataHora($dados['data_hora']);
		$acao = array(
			1 => "Inserção",
			2 => "Alteração",
			3 => "Exclusão"
		);
		$acao = $acao[$dados['tipo_acao']];
		$dados_acao = str_replace("|", "<br>", $dados['dados']);
		$legenda = $dados['legenda_acao'];
		$modulo = DBRead('', 'tb_quadro_informativo_modulo', 'WHERE id_quadro_informativo_modulo = '.$dados['id_quadro_informativo_modulo'].'', 'nome');
		
		?>
		
		<tr>
			<td> <?= $id_quadro_informativo_historico?> </td>
			<td> <?= $usuario[0]['nome']?> </td>
			<td> <?= $contrato[0]['nome']?> </td>
			<td> <?= $data_hora?> </td>
			<td> <?= $modulo[0]['nome']?> </td>
			<td> <?= $acao?> </td>
			<td> <?= $legenda?> </td>
			<td> <?= $dados_acao?> </td>
		</tr> 

	<?php } ?>

            </tbody>
		</table>

	<?php }else{ ?>

		<div class='col-md-12'>
			<table class='table table-bordered'>
				<tbody>
					<tr>
						<td class='text-center'> <h4>Não foram encontrados resultados!</h4></td>
					</tr>
				</tbody>
			</table>
		</div>
		
	<?php } ?>

		<script>
			$(document).ready(function(){
				$('.dataTable').DataTable({
					"language": {
						"url": "//cdn.datatables.net/plug-ins/1.10.19/i18n/Portuguese-Brasil.json"
					},			        
					"searching": false,
					"paging":   false,
					"info":     false
				});
				});
			</script>
			
		</div>

	<?php } ?>