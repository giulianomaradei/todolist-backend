<?php
require_once(__DIR__."/../class/System.php");

// Busca id_usuario e id_perfil_sistema do usuario atual
$id_usuario = $_SESSION['id_usuario'];
$id_perfil_usuario = DBRead('', 'tb_usuario', "WHERE id_usuario = $id_usuario", "id_perfil_sistema");
$id_perfil_usuario = $id_perfil_usuario[0]['id_perfil_sistema'];
////////////////////////////////////////////////////////

$tipo_relatorio = (!empty($_POST['tipo_relatorio'])) ? $_POST['tipo_relatorio'] : '1';
$gerar = (!empty($_POST['gerar'])) ? 1 : 0;

$data_inicial = new DateTime(getDataHora('data'));
$data_inicial->modify('first day of last month');
$referencia = (!empty($_POST['referencia'])) ? $_POST['referencia'] : $data_inicial->format('Y-m-d');
$id_contrato_plano_pessoa = (!empty($_POST['id_contrato_plano_pessoa'])) ? $_POST['id_contrato_plano_pessoa'] : '';
$andamento = (!empty($_POST['andamento'])) ? $_POST['andamento'] : '';
$id_pessoa = (!empty($_POST['id_pessoa'])) ? $_POST['id_pessoa'] : '';
$data_de = (!empty($_POST['data_de'])) ? $_POST['data_de'] : '';
$data_ate = (!empty($_POST['data_ate'])) ? $_POST['data_ate'] : '';  
$id_responsavel = (!empty($_POST['id_responsavel'])) ? $_POST['id_responsavel'] : '';

if ($id_contrato_plano_pessoa) {
	$dados_contrato = DBRead('', 'tb_contrato_plano_pessoa a', "INNER JOIN tb_plano b ON a.id_plano = b.id_plano INNER JOIN tb_pessoa c ON a.id_pessoa = c.id_pessoa WHERE a.id_contrato_plano_pessoa = '$id_contrato_plano_pessoa'", "a.*, b.cod_servico, b.nome AS 'plano', c.nome AS 'nome_pessoa'");

	if ($dados_contrato[0]['nome_contrato']) {
		$nome_contrato = " (" . $dados_contrato[0]['nome_contrato'] . ") ";
	}

	$contrato = $dados_contrato[0]['nome_pessoa'] . " " . $nome_contrato . " - " . getNomeServico($dados_contrato[0]['cod_servico']) . " - " . $dados_contrato[0]['plano'] . " (" . $dados_contrato[0]['id_contrato_plano_pessoa'] . ")";
}

if ($id_pessoa) {
	$dados_pessoa = DBRead('', 'tb_pessoa', "WHERE id_pessoa = '$id_pessoa'");
	$nome_pessoa = $dados_pessoa[0]['nome'];
}

if($gerar){
	$collapse = '';
	$collapse_icon = 'plus';
}else{
	$collapse = 'in';
	$collapse_icon = 'minus';
}

if ($tipo_relatorio == 1) {
	$display_row_contrato = '';
	$display_row_referencia = '';
	$display_row_empresa = 'style="display:none;"';
	$display_row_periodo = 'style="display:none;"';
	$display_row_responsavel = 'style="display:none;"'; 

} else if ($tipo_relatorio == 2) {
	$display_row_contrato = 'style="display:none;"';
	$display_row_referencia = 'style="display:none;"';
	$display_row_empresa = 'style="display:none;"';
	$display_row_periodo = '';
	$display_row_responsavel = '';

} else if ($tipo_relatorio == 3) {
	$display_row_contrato = 'style="display:none;"';
	$display_row_referencia = 'style="display:none;"';
	$display_row_empresa = '';
	$display_row_periodo = '';
	$display_row_responsavel = 'style="display:none;"'; 

} else if ($tipo_relatorio == 4) {
	$display_row_contrato = 'style="display:none;"';
	$display_row_referencia = 'style="display:none;"';
	$display_row_empresa = 'style="display:none;"';
	$display_row_periodo = '';
	$display_row_responsavel = 'style="display:none;"'; 
	
}

$id_usuario = $_SESSION['id_usuario'];
$dados = DBRead('', 'tb_usuario', "WHERE id_usuario = '$id_usuario' LIMIT 1", "id_perfil_sistema");
$perfil_sistema = $dados[0]['id_perfil_sistema'];

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
	                    <h3 class="panel-title text-left pull-left" style="margin-top: 2px;">Relatório - Indicadores:</h3>
	                    <div class="panel-title text-right pull-right"><button data-toggle="collapse" data-target="#accordionRelatorio" class="btn btn-xs btn-info" type="button" title="Visualizar filtros"><i id="i_collapse" class="fa fa-<?=$collapse_icon?>"></i></button></div>
	                </div>
	                <div id="accordionRelatorio" class="panel-collapse collapse <?=$collapse?>">
	                	<div class="panel-body">
	                		<div class="row">											
								<div class="col-md-12">
									<div class="form-group">
										<label>Tipo de Relatório:</label> 
										<select name="tipo_relatorio" id="tipo_relatorio" class="form-control input-sm">
											<option value="1" <?php if($tipo_relatorio == '1'){echo 'selected';}?>>Contratos</option>
											<?php if($id_perfil_usuario != '13' && $id_perfil_usuario != '30' && $id_perfil_usuario != '4'){ ?>
												<option value="2" <?php if($tipo_relatorio == '2'){echo 'selected';}?>>Timeline - Tabela</option>
												<option value="3" <?php if($tipo_relatorio == '3'){echo 'selected';}?>>Timeline - Pessoa/Empresa</option>
												<option value="4" <?php if($tipo_relatorio == '4'){echo 'selected';}?>>Timeline - Sem vínculo</option>
											<?php }?>
										</select>
									</div>
								</div>
							</div>	                		

							<div class="row" id="row_referencia"  <?=$display_row_referencia?>>
								<div class="col-md-12">
									<div class="form-group">
										<label>Data de Referência:</label>
										<select name="referencia" class="form-control input-sm">
											<?php
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

												$dados_referencia = DBRead('', 'tb_faturamento', "WHERE status = 1 GROUP BY data_referencia ORDER BY data_referencia DESC", "data_referencia");
												if ($dados_referencia) {
													foreach ($dados_referencia as $conteudo_referencia) {
														$selected = $referencia == $conteudo_referencia['data_referencia'] ? "selected" : "";
														$mes_ano = explode("-", $conteudo_referencia['data_referencia']);
														$mes = $mes_ano[1];
														$ano = $mes_ano[0];
														echo "<option value='" . $conteudo_referencia['data_referencia'] . "' ".$selected.">" .$dados_meses[$mes]."/".$ano."</option>";
													}
												}
											?>
										</select>
									</div>
								</div>
							</div>

                            <div class="row" id="row_contrato" <?=$display_row_contrato?>>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>*Contrato (cliente):</label>
                                        <div class="input-group">
                                            <input class="form-control input-sm" id="busca_contrato" type="text" name="busca_contrato" value="<?=$contrato?>" placeholder="Informe o nome ou CNPJ..." autocomplete="off" readonly/>
                                            <div class="input-group-btn">
                                                <button class="btn btn-info btn-sm" id="habilita_busca_contrato" name="habilita_busca_contrato" type="button" title="Clique para selecionar o contrato" style="height: 30px;"><i class="fa fa-search"></i></button>
                                                <input type='hidden' name='id_contrato_plano_pessoa' id='id_contrato_plano_pessoa' value='<?= $id_contrato_plano_pessoa?>' />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div> 

							<div class="row" id="row_periodo" <?=$display_row_periodo?>>
	                			<div class="col-md-6">
	                				<div class="form-group">
										<label>Data Inicial:</label>
										<input type="text" class="form-control date calendar input-sm" name="data_de" id="de" autocomplete="off" value="<?=$data_de?>">
									</div>
	                			</div>
	                			<div class="col-md-6">
	                				<div class="form-group">
										<label>Data Final:</label>
										<input type="text" class="form-control date calendar input-sm" name="data_ate" id="ate" autocomplete="off" value="<?=$data_ate?>">
									</div>
	                			</div>
	                		</div>

							<div class="row" id="row_responsavel" <?=$display_row_responsavel?>>
	                			<div class="col-md-12">
	                				<div class="form-group">
										<label>Usuário:</label>
										<select class='form-control input-sm' name='id_responsavel' id='id_responsavel'>
											<?php
												$dados_responsavel = DBRead('', 'tb_usuario a',"INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_perfil_sistema = '11' OR a.id_perfil_sistema = '29' OR a.id_perfil_sistema = '18' ORDER BY b.nome ASC");
												echo "<option value = ''>Qualquer</option>";
												foreach ($dados_responsavel as $dado) {	
													$selected = $id_responsavel == $dado['id_usuario'] ? "selected" : "";
													echo "<option value = '".$dado['id_usuario']."' ".$selected.">".$dado['nome']."</option>";
												}
											?>
										</select>
									</div>
								</div>
							</div>  

							<div class="row" id="row_empresa" <?=$display_row_empresa?>>
                                <div class="col-md-12">
                                    <div class="form-group">
										<label>Pessoa/Empresa:</label>
										<div class="input-group">
											<input class="form-control input-sm" id="busca_pessoa" type="text" name="busca_pessoa"  value="<?=$nome_pessoa;?>" placeholder="Informe o nome ou CPF/CNPJ..." autocomplete="off" readonly>
											<div class="input-group-btn">
												<button class="btn btn-info btn-sm" id="habilita_busca_pessoa" name="habilita_busca_pessoa" type="button" title="Clique para selecionar a pessoa" style="height: 30px;"><i class="fa fa-search"></i></button>
											</div>
										</div>
										<input type="hidden" name="id_pessoa" id="id_pessoa" value="<?=$id_pessoa;?>">
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
		if ($gerar) {
			if ($tipo_relatorio == 1) {
				relatorio_indicadores_contrato($referencia, $id_contrato_plano_pessoa);

            } else if ($tipo_relatorio == 2) {
			 	relatorio_indicadores_timeline_tabela($data_de, $data_ate, $id_responsavel, $dados_responsavel);

            } else if ($tipo_relatorio == 3) {
				relatorio_indicadores_empresa($id_pessoa, $data_de, $data_ate);

            } else if ($tipo_relatorio == 4) {
				relatorio_indicadores_sem_vinculo($data_de, $data_ate);
			}
		}
		?>
	</div>
</div>

<script>	

	$('#tipo_relatorio').on('change',function() {
		tipo_relatorio = $(this).val();
		
		if (tipo_relatorio == 1) {
			$('#row_contrato').show();
			$('#row_referencia').show();
			$('#row_empresa').hide();
			$('#row_periodo').hide();
			$('#row_responsavel').hide();
			
		} else if (tipo_relatorio == 2) {
			$('#row_contrato').hide();
			$('#row_referencia').hide();
			$('#row_empresa').hide();
			$('#row_periodo').show();
			$('#row_responsavel').show();

		} else if(tipo_relatorio == 3) {
			$('#row_contrato').hide();
			$('#row_referencia').hide();
			$('#row_empresa').show();
			$('#row_periodo').show();
			$('#row_responsavel').hide();
			
		} else if (tipo_relatorio == 4) {
			$('#row_contrato').hide();
			$('#row_referencia').hide();
			$('#row_empresa').hide();
			$('#row_periodo').show();
			$('#row_responsavel').hide();
		}
	});   

    $('#accordionRelatorio').on('shown.bs.collapse', function(){
       $("#i_collapse").removeClass("fa fa-plus").addClass("fa fa-minus");
    });
    $('#accordionRelatorio').on('hidden.bs.collapse', function(){
       $("#i_collapse").removeClass("fa fa-minus").addClass("fa fa-plus");
    });

    $(document).on('submit', 'form', function(){

		var id_contrato_plano_pessoa = $('#id_contrato_plano_pessoa').val();
		var tipo_relatorio = $('#tipo_relatorio').val();

		if (tipo_relatorio == 1 && id_contrato_plano_pessoa == '') {
			alert('Informe o contrato!');
			return false;
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

    // Atribui evento e função para limpeza dos campos
    $('#busca_contrato').on('input', limpaCamposContrato);
    // Dispara o Autocomplete da pessoa a partir do segundo caracter
    $("#busca_contrato").autocomplete({
            minLength: 2,
            source: function (request, response) {
                $.ajax({
                    url: "/api/ajax?class=ContratoAutocomplete.php",
                    dataType: "json",
                    data: {
                        acao: 'autocomplete',
                        parametros: { 
                            'nome' : $('#busca_contrato').val(),
                        },
						token: '<?= $request->token ?>'
                    },
                    success: function (data) {
                        response(data);
                    }
                });
            },
            focus: function (event, ui){
                $("#busca_contrato").val(ui.item.nome + " " + ui.item.nome_contrato +" - " + ui.item.servico + " - " + ui.item.plano + " (" + ui.item.id_contrato_plano_pessoa + ")");
                carregarDadosContrato(ui.item.id_contrato_plano_pessoa);
                return false;
            },
            select: function (event, ui) {
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
            }
            if(!item.nome_contrato){
                item.nome_contrato = '';
            }else{
                item.nome_contrato = ' ('+item.nome_contrato+') '; 
            }
            return $("<li>").append("<a><strong>"+item.id_contrato_plano_pessoa + " - " + item.nome + ""+item.nome_contrato+" </strong><br>" +item.razao_social+ "<br>" +item.cpf_cnpj+ "<br>" + item.servico + " - " + item.plano + " (" + item.id_contrato_plano_pessoa + ")" + "</a><hr style='margin-bottom: 0px;'>").appendTo(ul);
    };
    // Função para carregar os dados da consulta nos respectivos campos
    function carregarDadosContrato(id){
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
                }
            });
        }
    }
    // Função para limpar os campos caso a busca esteja vazia
    function limpaCamposContrato() {
        var busca = $('#busca_contrato').val();
        if (busca == "") {
            $('#id_contrato_plano_pessoa').val('');
        }
    }
    $(document).on('click', '#habilita_busca_contrato', function () {
        $('#id_contrato_plano_pessoa').val('');
        $('#busca_contrato').val('');
        $('#busca_contrato').attr("readonly", false);
        $('#busca_contrato').focus();
    });


	//-------------------------Pessoa-------------------------------------------

	// Atribui evento e função para limpeza dos campos
	$('#busca_pessoa').on('input', limpaCamposPessoa);
    // Dispara o Autocomplete da pessoa a partir do segundo caracter
    $("#busca_pessoa").autocomplete({
            minLength: 2,
            source: function (request, response) {
                $.ajax({
                    url: "/api/ajax?class=PessoaAutocomplete.php",
                    dataType: "json",
                    data: {
                        acao: 'autocomplete',
                        parametros: { 
                            'nome' : $('#busca_pessoa').val(),
                        },
						token: '<?= $request->token ?>'
                    },
                    success: function (data) {
                        response(data);
                    }
                });
            },
            focus: function (event, ui) {
                $("#busca_pessoa").val(ui.item.nome);
                carregarDadosPessoa(ui.item.id_pessoa);
                return false;
            },
            select: function (event, ui) {
                $("#busca_pessoa").val(ui.item.nome);
                $('#busca_pessoa').attr("readonly", true);
                return false;
            }
        })
        .autocomplete("instance")._renderItem = function (ul, item) {
            if(!item.razao_social){
                item.razao_social = '';
            }
            if(!item.cpf_cnpj){
                item.cpf_cnpj = '';
            }
        return $("<li>").append("<a><strong>"+item.id_pessoa+" - "+ item.nome + " </strong><br>" +item.razao_social+ "<br>" +item.cpf_cnpj+ "</a><hr style='margin-bottom: 0px;'>").appendTo(ul);
    };
    // Função para carregar os dados da consulta nos respectivos campos
    function carregarDadosPessoa(id) {
        var busca = $('#busca_pessoa').val();

        if (busca != "" && busca.length >= 2) {
            $.ajax({
                url: "/api/ajax?class=PessoaAutocomplete.php",
                dataType: "json",
                data: {
                    acao: 'consulta',
                    parametros: { 
                        'id' : id,                            
                    },
					token: '<?= $request->token ?>'
                },
                success: function (data) {
                    $('#id_pessoa').val(data[0].id_pessoa);
                }
            });
        }
    }
    // Função para limpar os campos caso a busca esteja vazia
    function limpaCamposPessoa() {
        var busca = $('#busca_pessoa').val();

        if (busca == "") {
            $('#id_pessoa').val('');
        }
    }
    
    $(document).on('click', '#habilita_busca_pessoa', function () {
        $('#id_pessoa').val('');
        $('#busca_pessoa').val('');
        $('#busca_pessoa').attr("readonly", false);
        $('#busca_pessoa').focus();
    });

</script>
<?php

function relatorio_indicadores_empresa($id_pessoa, $data_de, $data_ate){?>
	<style>
	.timeline-badge {
		color: #fff;
		width: 50px;
		height: 50px;
		line-height: 50px;
		font-size: 1.4em;
		text-align: center;
		display: inline-block;
		top: 16px;
		left: 5%;
		background-color: #999999;
		z-index: 100;
		border-top-right-radius: 50%;
		border-top-left-radius: 50%;
		border-bottom-right-radius: 50%;
		border-bottom-left-radius: 50%;
	}
	</style>
	<?php
	if($id_pessoa){
		$filtro_id_pessoa = "AND b.id_pessoa = '".$id_pessoa."'";
		$dados_id_pessoa = DBRead('', 'tb_pessoa',"WHERE id_pessoa = '".$id_pessoa."' ", "nome");
		$legenda_id_pessoa = $dados_id_pessoa[0]['nome'];
	}else{
		$filtro_id_pessoa = "";
		$legenda_id_pessoa = 'Todos';
	}

	if ($data_de && $data_ate) {
		$periodo_amostra = "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> De $data_de até $data_ate</span></legend>";
		$filtro_data = " AND ( (a.data_inicio >= '".converteData($data_de)."' AND a.data_inicio <= '".converteData($data_ate)."') OR (a.data_conclusao >= '".converteData($data_de)."' AND a.data_conclusao <= '".converteData($data_ate)."') ) ";
		
    } else if ($data_de) {
		$periodo_amostra ="<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> A partir de $data_de</span></legend>";
		$filtro_data = " AND ( (a.data_inicio >= '".converteData($data_de)."') OR (a.data_conclusao >= '".converteData($data_de)."') )";
		
	} else if ($data_ate) {
		$periodo_amostra ="<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> Até $data_ate</span></legend>";
		$filtro_data = " AND ( (a.data_inicio <= '".converteData($data_ate)."') OR (a.data_conclusao >= '".converteData($data_de)."') ) ";

	} else {
	    $periodo_amostra = "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> Qualquer</span></legend>";
	}

	$gerado = "<span style=\"font-size: 14px;\"><strong>Gerado em: </strong> ".converteDataHora(getDataHora())."</span>";	
	echo "<div class=\"col-md-12\" style=\"padding: 0\">";
	echo "<legend style=\"text-align:center;\"><strong>Relatório Indicadores - Timeline - Pessoa/Empresa</strong><br>$gerado</legend>";
	echo "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong>Pessoa/Empresa - </strong>".$legenda_id_pessoa."</legend>";
	echo "$periodo_amostra";

	$dados_lead_negocio = DBRead('', 'tb_lead_negocio a',"INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE id_lead_negocio ".$filtro_id_pessoa." $filtro_data GROUP BY a.id_pessoa ORDER BY b.nome ASC", "a.id_pessoa, b.nome");

	if($dados_lead_negocio){
		$dados_lead_tipo_item_timeline = DBRead('', 'tb_lead_tipo_item_timeline',"WHERE exibe = '1' ORDER BY nome ASC");

		$dados_array_total = array();
	
		foreach($dados_lead_negocio as $conteudo_lead_negocio){
			$id_pessoa = $conteudo_lead_negocio['id_pessoa'];
			$nome = $conteudo_lead_negocio['nome'];
			
			$dados_negocio_pessoa = DBRead('', 'tb_lead_negocio',"WHERE id_pessoa = '".$id_pessoa."' ");

			foreach($dados_lead_tipo_item_timeline as $conteudo_lead_tipo_item_timeline){
				$dados_array_total[$conteudo_lead_tipo_item_timeline['id_lead_tipo_item_timeline']] = 0;
			}

			$qtd_negocio = sizeof($dados_negocio_pessoa);
			if($qtd_negocio == 1){
				$descricao_qtd_negocio = $qtd_negocio." Negócio";
			}else{
				$descricao_qtd_negocio = $qtd_negocio." Negócios";
			}
			echo '
			<div class="panel panel-primary">';
				echo '
				<div class="panel-heading clearfix">';
					echo'
					<div class="text-left pull-left"><div class="panel-title ">'.$nome.' ('.$descricao_qtd_negocio.')</div></div>';
					
					echo '
					<div class="text-right pull-right"><div class="panel-title text-right pull-right"><button data-toggle="collapse" data-target="#negocio_collapse_'.$id_pessoa.'" class="btn btn-xs btn-info negocio_collapse" type="button" title="Expandir"><i id="i_collapse_negocio_'.$id_pessoa.'" class="fa fa-plus"></i></button></div></ strong></div>';
				echo '
				</div>';

				echo '<div id="negocio_collapse_'.$id_pessoa.'" value="'.$id_pessoa.'" class="negocio_collapse_panel panel-collapse collapse">';

				foreach($dados_negocio_pessoa as $conteudo_negocio_pessoa){
					if($conteudo_negocio_pessoa['andamento'] == 0){
						$andamento = "Andamento";
					}else if($conteudo_negocio_pessoa['andamento'] == 1){
						$andamento = "Ganhou";
					}else if($conteudo_negocio_pessoa['andamento'] == 2){
						$andamento = "Perdeu";
					}

					foreach($dados_lead_tipo_item_timeline as $conteudo_lead_tipo_item_timeline){
						$dados_array[$conteudo_lead_tipo_item_timeline['id_lead_tipo_item_timeline']] = 0;
					}
					
					echo '
					<div class="panel-body">';
						echo '
						<div class="panel"  style="border: 1px solid;">';
							echo '
							<div class="panel-heading clearfix" style="border-bottom: 1px solid black !important;">';
								echo'
								<div class="text-left pull-left">Tipo de negócio: <strong>'.$conteudo_negocio_pessoa['tipo_negocio'].'</strong></div>';
								
								echo '
								<div class="text-right pull-right">Andamento: <strong>'.$andamento.'</strong></div>';
							echo '
							</div>';
								echo '
								<div class="panel-body">';
									echo '
									<div class="panel panel-default">';
										echo '
										<div class="panel-heading clearfix">';
											echo'
											<div class="text-left pull-left">Negócio</div>';									

										echo '
										</div>';
										echo "<hr style='margin: 0px !important;'>";
										echo '
										<div class="panel-body" style="padding-top: 5px !important; padding-right: 25px !important; padding-left: 25px !important;">';

											echo '
											<div class="row" style="margin-bottom: 5px !important;"> 
												<div class="col-md-12">
													<label>Descrição:</label>
													<br>
													'.nl2br ($conteudo_negocio_pessoa['descricao']).'
												</div>
											</div>';


											$dados_id_lead_status = DBRead('', 'tb_lead_status',"WHERE id_lead_status = '".$conteudo_negocio_pessoa['id_lead_status']."' ", "descricao");
											$dados_id_usuario_responsavel = DBRead('', 'tb_usuario a',"INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_usuario = '".$conteudo_negocio_pessoa['id_usuario_responsavel']."' ", "b.nome");
											echo '
											<div class="row">
												<div class="col-md-4">
													<label>Status:</label> '.$dados_id_lead_status[0]['descricao'].'
												</div>
												<div class="col-md-4">
													<label>Usuário Responsável:</label> '.$dados_id_usuario_responsavel[0]['nome'].'
												</div>
												<div class="col-md-4">
													<label>Valor do Contrato:</label> R$ '.converteMoeda($conteudo_negocio_pessoa['valor_contrato'], 'moeda').'
												</div>
											</div>';

											if($conteudo_negocio_pessoa['id_plano']){
												$plano_servico = DBRead('', 'tb_plano',"WHERE id_plano = '".$conteudo_negocio_pessoa['id_plano']."' ");
												$plano_servico = getNomeServico($plano_servico[0]['cod_servico']).' - '.$plano_servico[0]['nome'];
											}else{
												$plano_servico = 'N/D';
											}

											$dados_id_lead_status = DBRead('', 'tb_lead_status',"WHERE id_lead_status = '".$conteudo_negocio_pessoa['id_lead_status']."' ", "descricao");
											echo '
											<div class="row">
												<div class="col-md-4">
													<label>Data de Início:</label> '.converteData($conteudo_negocio_pessoa['data_inicio']).'
												</div>
												<div class="col-md-4">
													<label>Data de Conclusão:</label> '.converteData($conteudo_negocio_pessoa['data_conclusao']).'
												</div>
												<div class="col-md-4">
													<label>Plano:</label> '.$plano_servico.'
												</div>
											</div>';

											if($conteudo_negocio_pessoa['id_pessoa_fechado_com']){
												$dados_id_pessoa_fechado_com = DBRead('', 'tb_pessoa',"WHERE id_pessoa = '".$conteudo_negocio_pessoa['id_pessoa_fechado_com']."' ", "nome");
												$pessoa_fechado_com = $dados_id_pessoa_fechado_com[0]['nome'];
											}else{
												$pessoa_fechado_com = 'N/D';
											}

											if($conteudo_negocio_pessoa['sinalizacao_rd'] == 1){
												$sinalizacao_rd = "Sinalizado";
											}else{
												$sinalizacao_rd = "Não Sinalizado";
											}
											
											echo '
											<div class="row">
												<div class="col-md-4">
													<label>Valor da Adesão:</label> R$ '.converteMoeda($conteudo_negocio_pessoa['valor_adesao']).'
												</div>
												<div class="col-md-4">
													<label>Fechado com:</label> '.$pessoa_fechado_com.'
												</div>
												<div class="col-md-4">
													<label>RD:</label> '.$sinalizacao_rd.'
												</div>
											</div>';
										echo '
										</div>';
									echo '
									</div>';

									echo '
									<div class="panel panel-default">';
										echo '
										<div class="panel-heading clearfix">';
											echo'
											<div class="text-left pull-left">Timeline</div>';									

										echo '
										</div>';
										echo "<hr style='margin: 0px !important;'>";
										echo '
										<div class="panel-body" style="padding-top: 5px !important; padding-right: 25px !important; padding-left: 25px !important;">';										

										$dados_lead_timeline = DBRead('', 'tb_lead_timeline',"WHERE id_lead_negocio = '".$conteudo_negocio_pessoa['id_lead_negocio']."' AND id_lead_tipo_item_timeline != '6' ");
										foreach($dados_lead_timeline as $conteudo_lead_timeline){
											if ($conteudo_lead_timeline['id_lead_tipo_item_timeline'] == 1) {
												$badge = 'glyphicon glyphicon-envelope';
												$cor = '#265a88';
											}else if ($conteudo_lead_timeline['id_lead_tipo_item_timeline'] == 2) {
												$badge = 'glyphicon-earphone';
												$cor = '#5bc0de';
											}else if ($conteudo_lead_timeline['id_lead_tipo_item_timeline'] == 3) {
												$badge = 'glyphicon glyphicon-usd';
												$cor = '#20B2AA';
											}else if ($conteudo_lead_timeline['id_lead_tipo_item_timeline'] == 4) {
												$badge = 'glyphicon glyphicon-pushpin';
												$cor = '#9370DB';
											}else if ($conteudo_lead_timeline['id_lead_tipo_item_timeline'] == 5) {
												$badge = 'glyphicon glyphicon-map-marker';
												$cor = '#EE8262';
											}else if ($conteudo_lead_timeline['id_lead_tipo_item_timeline'] == 6) {
												$badge = 'glyphicon glyphicon-briefcase';
												$cor = '#59ba1f';
											}else if ($conteudo_lead_timeline['id_lead_tipo_item_timeline'] == 7) {
												$badge = 'glyphicon glyphicon-file';
												$cor = '#151515';
											}else if ($conteudo_lead_timeline['id_lead_tipo_item_timeline'] == 8) {
												$badge = 'glyphicon glyphicon-comment';
												$cor = '#00FFBF';
											}

											$dados_lead_tipo_item_timeline = DBRead('', 'tb_lead_tipo_item_timeline',"WHERE id_lead_tipo_item_timeline = '".$conteudo_lead_timeline['id_lead_tipo_item_timeline']."' ", "nome, id_lead_tipo_item_timeline");
				
											$dados_array[$dados_lead_tipo_item_timeline[0]['id_lead_tipo_item_timeline']] ++;
											$dados_array_total[$dados_lead_tipo_item_timeline[0]['id_lead_tipo_item_timeline']] ++;

											echo '
											<div class="row">
												<div class="col-md-6" style="margin-bottom: 10px;">
													<div class="timeline-badge" style="background-color: '.$cor.'">
														<i class="glyphicon '.$badge.'" style="font-size: 18px; margin-top: 15px !important;"></i>
													</div>
													<label>&nbsp&nbsp'.$dados_lead_tipo_item_timeline[0]['nome'].'</label> 
												</div>
											</div>';
											echo '
											<div class="row" style="margin-bottom: 5px !important;"> 
												<div class="col-md-12">
													<label>Descrição:</label>
													<br>
													'.nl2br ($conteudo_lead_timeline['descricao']).'
												</div>
											</div>';

											$dados_id_usuario = DBRead('', 'tb_usuario a',"INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_usuario = '".$conteudo_lead_timeline['id_usuario']."' ", "b.nome");

											if($conteudo_lead_timeline['contato_realizado'] == 1){
												$contato_realizado = 'Sim';
											}else{
												$contato_realizado = 'Não';
											}

											echo '
											<div class="row">
												<div class="col-md-4">
													<label>Usuário:</label> '.$dados_id_usuario[0]['nome'].'
												</div>
												<div class="col-md-4">
													<label>Data:</label> '.converteDataHora($conteudo_lead_timeline['data']).'
												</div>
												<div class="col-md-4">
													<label>Contato Realizado:</label> '.$contato_realizado.'
												</div>
											</div>';

											if($conteudo_lead_timeline['id_usuario_finalizou']){
												$dados_id_usuario_finalizou = DBRead('', 'tb_usuario a',"INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_usuario = '".$conteudo_lead_timeline['id_usuario_finalizou']."' ", "b.nome");
												$id_usuario_finalizou = $dados_id_usuario_finalizou[0]['nome'];
											}else{
												$id_usuario_finalizou = 'N/D';
											}

											if($conteudo_lead_timeline['finalizado']){
												$finalizado = converteDataHora($conteudo_lead_timeline['finalizado']);
											}else{
												$finalizado = 'N/D';
											}

											echo '
											<div class="row">
												<div class="col-md-6">
													<label>Usuário que Finalizou:</label> '.$id_usuario_finalizou.'
												</div>
												<div class="col-md-6">
													<label>Data da Finalização:</label> '.$finalizado.'
												</div>
											</div>';
											echo "<hr>";

										}
									
										echo "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong>Totais da Timeline do Negócio</strong></legend>";
										
										echo "
										<legend style=\"text-align:center;\">
											<span style=\"font-size: 14px;\">";

											echo "<table class='table table-borderless' style='font-size='14px'>";
												echo "<thead>";
													echo "<tr>";
														$dados_lead_tipo_item_timeline = DBRead('', 'tb_lead_tipo_item_timeline',"WHERE exibe = '1' ORDER BY nome ASC");
														foreach($dados_lead_tipo_item_timeline as $conteudo_lead_tipo_item_timeline){
															echo "<th class='col-md-1 text-center' style='vertical-align: middle;'>".$conteudo_lead_tipo_item_timeline['nome']."</th>";
														}
													echo "</tr>";
												echo "</thead>";
												echo "<tbody>";
												
													echo "<tr>";
													foreach($dados_lead_tipo_item_timeline as $conteudo_lead_tipo_item_timeline){
														echo "<td style='vertical-align: middle;'>".$dados_array[$conteudo_lead_tipo_item_timeline['id_lead_tipo_item_timeline']]."</td>";
													}
													echo "</tr>";
												echo "</tbody>";
											echo "</table>";
										
											echo "
											</span>
										</legend>";									
										echo '
										</div>';
									echo '
									</div>';
								echo '
								</div>';
						echo '
						</div>';
					echo '
					</div>';
				}

				echo "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong>Totais da Empresa</strong></legend>";
									
				echo "
				<legend style=\"text-align:center;\">
					<span style=\"font-size: 14px;\">";

					echo "<table class='table table-borderless' style='font-size='14px'>";
						echo "<thead>";
							echo "<tr>";
								$dados_lead_tipo_item_timeline = DBRead('', 'tb_lead_tipo_item_timeline',"WHERE exibe = '1' ORDER BY nome ASC");
								foreach($dados_lead_tipo_item_timeline as $conteudo_lead_tipo_item_timeline){
									echo "<th class='col-md-1 text-center' style='vertical-align: middle;'>".$conteudo_lead_tipo_item_timeline['nome']."</th>";
								}
							echo "</tr>";
						echo "</thead>";
						echo "<tbody>";
						
							echo "<tr>";
							foreach($dados_lead_tipo_item_timeline as $conteudo_lead_tipo_item_timeline){
								echo "<td style='vertical-align: middle;'>".$dados_array_total[$conteudo_lead_tipo_item_timeline['id_lead_tipo_item_timeline']]."</td>";
							}
							echo "</tr>";
						echo "</tbody>";
					echo "</table>";
					echo "
					</span>
				</legend>";
				echo '
				</div>';
			echo '
			</div>';
		}
		echo "<br>";

		
	}else{
		echo "<table class='table table-bordered'>";
			echo "<tbody>";
				echo "<tr>";
					echo "<td class='text-center'> <h4>Não foram encontrados resultados!</h4></td>";
				echo "</tr>";
			echo "</tbody>";
		echo "</table>";
	}
	echo '</div>';

	?>
		<script>
			$('.negocio_collapse_panel').on('show.bs.collapse', function(){
				var botao = $(this).attr('id').split("negocio_collapse_");
				var botao = '#i_collapse_negocio_'+botao[1];
				$(botao).removeClass("fa fa-plus").addClass("fa fa-minus");
			});
			$('.negocio_collapse_panel').on('hidden.bs.collapse', function(){
				var botao = $(this).attr('id').split("negocio_collapse_");
				var botao = '#i_collapse_negocio_'+botao[1];
				$(botao).removeClass("fa fa-minus").addClass("fa fa-plus");
			});
		</script>
	<?php

}

function relatorio_indicadores_sem_vinculo($data_de, $data_ate){?>
	<style>
		.timeline-badge {
			color: #fff;
			width: 50px;
			height: 50px;
			line-height: 50px;
			font-size: 1.4em;
			text-align: center;
			display: inline-block;
			top: 16px;
			left: 5%;
			background-color: #999999;
			z-index: 100;
			border-top-right-radius: 50%;
			border-top-left-radius: 50%;
			border-bottom-right-radius: 50%;
			border-bottom-left-radius: 50%;
		}
		hr {
			border-top: 1px solid #BDBDBD !important;
			font-size: 20px;
		}
	</style>
	<?php

	$legenda_id_pessoa = 'Sem vínculo';

	if ($data_de && $data_ate) {
		$periodo_amostra = "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> De $data_de até $data_ate</span></legend>";
		$filtro_data = " AND a.data >= '".converteData($data_de)." 00:00:00' AND a.data <= '".converteData($data_ate)." 23:59:59'";
		
    } else if ($data_de) {
		$periodo_amostra ="<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> A partir de $data_de</span></legend>";
		$filtro_data = " AND a.data >= '".converteData($data_de)."'";
		
	} else if ($data_ate) {
		$periodo_amostra ="<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> Até $data_ate</span></legend>";
		$filtro_data = " AND a.data >= '".converteData($data_ate)."'";

	} else {
	    $periodo_amostra = "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> Qualquer</span></legend>";
	}

	$gerado = "<span style=\"font-size: 14px;\"><strong>Gerado em: </strong> ".converteDataHora(getDataHora())."</span>";	

	echo "<div class=\"col-md-12\" style=\"padding: 0\">";
	echo "<legend style=\"text-align:center;\"><strong>Relatório Indicadores - Timeline - Sem vínculo</strong><br>$gerado</legend>";
	echo "$periodo_amostra";

	$dados_lead_negocio = DBRead('', 'tb_lead_timeline a',"INNER JOIN tb_usuario b ON a.id_usuario = b.id_usuario INNER JOIN tb_pessoa c ON b.id_pessoa = c.id_pessoa WHERE id_lead_negocio = 0 $filtro_data ORDER BY a.data ASC", "a.*, c.razao_social");

	if($dados_lead_negocio){
		$total = sizeof($dados_lead_negocio);
		echo "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong>Total:  $total </strong></legend>";
	}

	if($dados_lead_negocio){

		echo '
		<div class="panel panel-default">';
			echo '
			<div class="panel-heading clearfix">';
				echo'
				<div class="text-left pull-left">Timeline</div>';									

			echo '
			</div>';
		
			echo '
			<div class="panel-body" style="padding-top: 5px !important; padding-right: 25px !important; padding-left: 25px !important;"><br>';

			foreach($dados_lead_negocio as $conteudo_lead_timeline){
				if ($conteudo_lead_timeline['id_lead_tipo_item_timeline'] == 1) {
					$badge = 'glyphicon glyphicon-envelope';
					$cor = '#265a88';
				}else if ($conteudo_lead_timeline['id_lead_tipo_item_timeline'] == 2) {
					$badge = 'glyphicon-earphone';
					$cor = '#5bc0de';
				}else if ($conteudo_lead_timeline['id_lead_tipo_item_timeline'] == 3) {
					$badge = 'glyphicon glyphicon-usd';
					$cor = '#20B2AA';
				}else if ($conteudo_lead_timeline['id_lead_tipo_item_timeline'] == 4) {
					$badge = 'glyphicon glyphicon-pushpin';
					$cor = '#9370DB';
				}else if ($conteudo_lead_timeline['id_lead_tipo_item_timeline'] == 5) {
					$badge = 'glyphicon glyphicon-map-marker';
					$cor = '#EE8262';
				}else if ($conteudo_lead_timeline['id_lead_tipo_item_timeline'] == 6) {
					$badge = 'glyphicon glyphicon-briefcase';
					$cor = '#59ba1f';
				}else if ($conteudo_lead_timeline['id_lead_tipo_item_timeline'] == 7) {
					$badge = 'glyphicon glyphicon-file';
					$cor = '#151515';
				}else if ($conteudo_lead_timeline['id_lead_tipo_item_timeline'] == 8) {
					$badge = 'glyphicon glyphicon-comment';
					$cor = '#00FFBF';
				}

				$dados_lead_tipo_item_timeline = DBRead('', 'tb_lead_tipo_item_timeline',"WHERE id_lead_tipo_item_timeline = '".$conteudo_lead_timeline['id_lead_tipo_item_timeline']."' ", "nome, id_lead_tipo_item_timeline");

				echo '
				<div class="row">
					<div class="col-md-6" style="margin-bottom: 10px;">
						<div class="timeline-badge" style="background-color: '.$cor.'">
							<i class="glyphicon '.$badge.'" style="font-size: 18px; margin-top: 15px !important;"></i>
						</div>
						<label>&nbsp&nbsp'.$dados_lead_tipo_item_timeline[0]['nome'].'</label> 
					</div>
				</div>';
				echo '
				<div class="row" style="margin-bottom: 5px !important;"> 
					<div class="col-md-12">
						<label>Descrição:</label>
						<br>
						'.nl2br ($conteudo_lead_timeline['descricao']).'
					</div>
				</div>';

				$dados_id_usuario = DBRead('', 'tb_usuario a',"INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_usuario = '".$conteudo_lead_timeline['id_usuario']."' ", "b.nome");

				if($conteudo_lead_timeline['contato_realizado'] == 1){
					$contato_realizado = 'Sim';
				}else{
					$contato_realizado = 'Não';
				}

				echo '<br>
				<div class="row">
					<div class="col-md-4">
						<label>Usuário:</label> '.$dados_id_usuario[0]['nome'].'
					</div>
				</div>';

				echo '<br>
				<div class="row">
					<div class="col-md-4">
						<label>Data:</label> '.converteDataHora($conteudo_lead_timeline['data']).'
					</div>
				</div>';

				echo "<hr>";

			}
			
			echo '
			</div>';
		echo '
		</div>';

		echo "<br>";

	} else {
		echo "<table class='table table-bordered'>";
			echo "<tbody>";
				echo "<tr>";
					echo "<td class='text-center'> <h4>Não foram encontrados resultados!</h4></td>";
				echo "</tr>";
			echo "</tbody>";
		echo "</table>";
	}
	echo '</div>';

}

function relatorio_indicadores_timeline_tabela($data_de, $data_ate, $id_responsavel, $dados_responsavel){

	$gerado = "<span style=\"font-size: 14px;\"><strong>Gerado em: </strong> ".converteDataHora(getDataHora())."</span>";

	if ($data_de && $data_ate) {
		$periodo_amostra = "<strong>Período da amostra:</strong> De $data_de até $data_ate";
		$filtro_data = " AND ( (a.data_inicio >= '".converteData($data_de)."' AND a.data_inicio <= '".converteData($data_ate)."') OR (a.data_conclusao >= '".converteData($data_de)."' AND a.data_conclusao <= '".converteData($data_ate)."') ) ";
		
    } else if ($data_de) {
		$periodo_amostra ="<strong>Período da amostra:</strong> A partir de $data_de";
		$filtro_data = " AND ( (a.data_inicio >= '".converteData($data_de)."') OR (a.data_conclusao >= '".converteData($data_de)."') )";
		
	} else if ($data_ate) {
		$periodo_amostra ="<strong>Período da amostra:</strong> Até $data_ate";
		$filtro_data = " AND ( (a.data_inicio <= '".converteData($data_ate)."') OR (a.data_conclusao >= '".converteData($data_de)."') ) ";

	} else {
	    $periodo_amostra = "<strong>Período da amostra:</strong> Qualquer";
	}

	if($id_responsavel){
		$dados_responsavel = DBRead('', 'tb_usuario a',"INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE id_usuario = '".$id_responsavel."' ");
		$usuario_responsavel = "<strong>Usuário: </strong>".$dados_responsavel[0]['nome'];

		$filtro_responsavel = "AND id_usuario = '".$id_responsavel."'";
	} else {
		$usuario_responsavel = "<strong>Usuário:</strong> Qualquer";
		$filtro_responsavel = "";
	}
	
	echo "<div class=\"col-md-12\" style=\"padding: 0\">";
	echo "<legend style=\"text-align:center;\"><strong>Relatório Indicadores - Timeline - Tabela</strong><br>$gerado</legend>";
	echo "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\">".$periodo_amostra.", ".$usuario_responsavel."</span></legend>";

	$dados_lead_negocio = DBRead('', 'tb_lead_negocio a',"INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE id_lead_negocio ".$filtro_data." GROUP BY a.id_pessoa ORDER BY b.nome ASC", "a.id_pessoa, b.nome");
	
	if($dados_lead_negocio){
		
		echo "<table class='table table-hover table-striped dataTable' style='font-size='14px'>";
			echo "<thead>";
				echo "<tr>";
					echo "<th class='col-md-1' style='vertical-align: middle;'>Pessoa/Empresa</th>";
					echo "<th class='col-md-1 text-center' style='vertical-align: middle;'>Quantidade de Negócios</th>";
					$dados_lead_tipo_item_timeline = DBRead('', 'tb_lead_tipo_item_timeline',"WHERE exibe = '1' ORDER BY nome ASC");
					foreach($dados_lead_tipo_item_timeline as $conteudo_lead_tipo_item_timeline){
						echo "<th class='col-md-1 text-center' style='vertical-align: middle;'>".$conteudo_lead_tipo_item_timeline['nome']."</th>";
					}
					echo "<th class='col-md-1 text-center' style='vertical-align: middle; border-right: 1px solid #ddd;'>Total</th>";
					echo "<th class='col-md-1 text-center' style='vertical-align: middle;'>Ganhou</th>";
					echo "<th class='col-md-1 text-center' style='vertical-align: middle;'>Perdeu</th>";
					echo "<th class='col-md-1 text-center' style='vertical-align: middle;'>Andamento</th>";

				echo "</tr>";
			echo "</thead>";
			echo "<tbody>";

			$dados_array_total = array();
			$contador_negocio_total = 0;
			$contador_total_total = 0;
			$contador_ganhou_total  = 0;
			$contador_perdeu_total  = 0;
			$contador_andamento_total  = 0;

			foreach($dados_lead_tipo_item_timeline as $conteudo_lead_tipo_item_timeline){
				$dados_array_total[$conteudo_lead_tipo_item_timeline['id_lead_tipo_item_timeline']] = 0;
			}

			foreach($dados_lead_negocio as $conteudo_lead_negocio){
				$id_pessoa = $conteudo_lead_negocio['id_pessoa'];
				$nome = $conteudo_lead_negocio['nome'];

				$contador_negocio = 0;
				$dados_negocio_cont = DBRead('', 'tb_lead_negocio',"WHERE id_pessoa = '".$id_pessoa."'");

				$dados_array = array();

				$contador_total = 0;
				foreach($dados_lead_tipo_item_timeline as $conteudo_lead_tipo_item_timeline){
					$dados_array[$conteudo_lead_tipo_item_timeline['id_lead_tipo_item_timeline']] = 0;
				}

				$contador_ganhou  = 0;
				$contador_perdeu  = 0;
				$contador_andamento  = 0;

				foreach($dados_negocio_cont as $conteudo_negocio_cont){
					
					$dados_lead_timeline = DBRead('', 'tb_lead_timeline',"WHERE id_lead_negocio = '".$conteudo_negocio_cont['id_lead_negocio']."' AND id_lead_tipo_item_timeline != '6' ".$filtro_responsavel." ");
					if($dados_lead_timeline){
						$contador_negocio ++;
						$contador_negocio_total ++;
						if($conteudo_negocio_cont['andamento'] == 0){
							$contador_andamento ++;
							$contador_andamento_total ++;
						}else if($conteudo_negocio_cont['andamento'] == 1){
							$contador_ganhou ++;
							$contador_ganhou_total ++;
						}else if($conteudo_negocio_cont['andamento'] == 2){
							$contador_perdeu ++;
							$contador_perdeu_total ++;
						}

							foreach($dados_lead_timeline as $conteudo_lead_timeline){
							$dados_array[$conteudo_lead_timeline['id_lead_tipo_item_timeline']] ++;		
							$dados_array_total[$conteudo_lead_timeline['id_lead_tipo_item_timeline']] ++;		
							$contador_total ++;				
							$contador_total_total ++;				
						}
					}
				}

				
				if($contador_total > 0){
					echo "<tr>";
					echo "<td style='vertical-align: middle;'>".$nome."</td>";
					echo "<td class='text-center' style='vertical-align: middle;'>".$contador_negocio."</td>";
					foreach($dados_lead_tipo_item_timeline as $conteudo_lead_tipo_item_timeline){
						echo "<td class='text-center' style='vertical-align: middle;'>".$dados_array[$conteudo_lead_tipo_item_timeline['id_lead_tipo_item_timeline']]."</td>";
					}
					echo "<td class='text-center' style='vertical-align: middle; border-right: 1px solid #ddd;'>".$contador_total."</td>";
					echo "<td class='text-center' style='vertical-align: middle;'>".$contador_ganhou."</td>";
					echo "<td class='text-center' style='vertical-align: middle;'>".$contador_perdeu."</td>";
					echo "<td class='text-center' style='vertical-align: middle;'>".$contador_andamento."</td>";
				echo "</tr>";
				}
				
			}
			echo "</tbody>";
			echo "<tfoot>";
				echo "<tr>";
					echo "<th class='col-md-1'>Totais:</th>";
					echo "<th class='col-md-1 text-center' style='vertical-align: middle;'>".$contador_negocio_total."</th>";
					foreach($dados_lead_tipo_item_timeline as $conteudo_lead_tipo_item_timeline){
						echo "<th class='col-md-1 text-center' style='vertical-align: middle;'>".$dados_array_total[$conteudo_lead_tipo_item_timeline['id_lead_tipo_item_timeline']]."</th>";
					}
					echo "<th class='col-md-1 text-center' style='border-right: 1px solid #ddd; vertical-align: middle;'>".$contador_total_total."</th>";
					echo "<th class='col-md-1 text-center' style='vertical-align: middle;'>".$contador_ganhou_total."</th>";
					echo "<th class='col-md-1 text-center' style='vertical-align: middle;'>".$contador_perdeu_total."</th>";
					echo "<th class='col-md-1 text-center' style='vertical-align: middle;'>".$contador_andamento_total."</th>";

				echo "</tr>";
			echo "</tfoot>";
		echo "</table>";
		echo "<br>";

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
									extend: 'excelHtml5', footer: true,
									text: '<i class=\"fa fa-file-excel-o\" aria-hidden=\"true\"></i> Exportar',
									filename: 'relatorio_indicadores_timeline_tabela',
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
		echo "<table class='table table-bordered'>";
			echo "<tbody>";
				echo "<tr>";
					echo "<td class='text-center'> <h4>Não foram encontrados resultados!</h4></td>";
				echo "</tr>";
			echo "</tbody>";
		echo "</table>";
	}
	echo '</div>';

}

function relatorio_indicadores_contrato($referencia, $id_contrato_plano_pessoa){
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

	if ($id_contrato_plano_pessoa) {
		$dados_contrato = DBRead('', 'tb_contrato_plano_pessoa a', "INNER JOIN tb_plano b ON a.id_plano = b.id_plano INNER JOIN tb_pessoa c ON a.id_pessoa = c.id_pessoa WHERE a.id_contrato_plano_pessoa = '$id_contrato_plano_pessoa'", "a.*, b.cod_servico, b.nome AS 'plano', c.nome AS 'nome_pessoa'");
	
		if ($dados_contrato[0]['nome_contrato']) {
			$nome_contrato = " (" . $dados_contrato[0]['nome_contrato'] . ") ";
		}
	
		$contrato = $dados_contrato[0]['nome_pessoa'] . " " . $nome_contrato . " - " . getNomeServico($dados_contrato[0]['cod_servico']) . " - " . $dados_contrato[0]['plano'] . " (" . $dados_contrato[0]['id_contrato_plano_pessoa'] . ")";
	}

	$mes_ano = explode("-", $referencia);
	$mes = $mes_ano[1];
	$ano = $mes_ano[0];

    $data_de = $referencia;

    $date = new DateTime($referencia);
    $date->modify('last day of this month');
    $data_ate = $date->format('Y-m-d');

	$periodo_amostra = "<span style=\"font-size: 14px;\"><strong>Referência do faturamento: </strong>".$dados_meses[$mes]." de ".$ano."</span>";
	$gerado = "<span style=\"font-size: 14px;\"><strong>Gerado em: </strong> ".converteDataHora(getDataHora())."</span>";	

	echo "<div class=\"col-md-12\" style=\"padding: 0\">";
	echo "<legend style=\"text-align:center;\"><strong>Relatório Indicadores - Contratos</strong><br>$gerado</legend>";
	echo "<legend style=\"text-align:center;\">$periodo_amostra</legend>";
	echo "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong>Dados do período: </strong> de ".converteData($data_de)." até ".converteData($data_ate)."</span></legend>";
	echo "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong>Contrato: </strong> $contrato</span></legend>";

    //FATURAMENTO
	$dados_consulta = DBRead('', 'tb_faturamento a',"INNER JOIN tb_faturamento_contrato b ON a.id_faturamento = b.id_faturamento INNER JOIN tb_contrato_plano_pessoa c ON b.id_contrato_plano_pessoa = c.id_contrato_plano_pessoa INNER JOIN tb_pessoa d ON c.id_pessoa = d.id_pessoa INNER JOIN tb_plano e ON a.id_plano = e.id_plano WHERE e.cod_servico = 'call_suporte' AND b.contrato_pai = '1' AND a.data_referencia = '".$referencia."' AND c.id_contrato_plano_pessoa = $id_contrato_plano_pessoa", "a.*, b.*, c.nome_contrato, d.nome, a.status AS status_contrato, e.nome AS nome_plano");

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
				$tipo_cobranca = "Pré-pago";
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
			}

			$dados_usuario = DBRead('', 'tb_usuario a',"INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE id_usuario = '".$conteudo_consulta['id_usuario']."'", "b.nome");
		
			echo '
			<div class="panel-heading clearfix">
                <div class="row"> 
                    <h1 class="panel-title text-center col-md-12" style="margin-top: 2px;"><strong>Financeiro</strong></h1>
			    </div>
                </div>

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
    //FATURAMENTO

	//SINTETICO GERAL LIGACOES
	$filtro_atendidas = '';
	$filtro_perdidas = '';
    if($data_de){
		$filtro_atendidas .= " AND b.time >= '$data_de 00:00:00'";
		$filtro_perdidas .= " AND a.time >= '$data_de 00:00:00'";
	}
	if($data_ate){		
		$filtro_atendidas .= " AND b.time <= '$data_ate 23:59:59'";
		$filtro_perdidas .= " AND a.time <= '$data_ate 23:59:59'";
	}

    $dados_empresas = DBRead('', 'tb_contrato_plano_pessoa a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa INNER JOIN tb_plano c ON a.id_plano = c.id_plano INNER JOIN tb_parametros d ON a.id_contrato_plano_pessoa = d.id_contrato_plano_pessoa WHERE d.id_asterisk AND c.cod_servico = 'call_suporte' AND a.status = 1 AND a.id_contrato_plano_pessoa = $id_contrato_plano_pessoa", "a.id_contrato_plano_pessoa, a.nome_contrato, a.qtd_contratada, b.nome AS 'nome_empresa', c.nome AS 'nome_plano', d.id_asterisk");

	if($dados_empresas){

		$qtd_atendidas = 0;
		$qtd_atendidas_30 = 0;
		$qtd_atendidas_60 = 0;
		$qtd_atendidas_90 = 0;
		$qtd_atendidas_180 = 0;
		$qtd_atendidas_maior_180 = 0;
		$qtd_perdidas = 0;
		$qtd_perdidas_30 = 0;
		$qtd_perdidas_60 = 0;
		$qtd_perdidas_90 = 0;
		$qtd_perdidas_180 = 0;
		$qtd_perdidas_maior_180 = 0;
		$soma_notas = 0;
		$qtd_notas = 0;
		$soma_ta_atendidas = 0;
		$soma_te_atendidas = 0;
		$soma_te_perdidas = 0;

        echo '<br><br>';
        echo '<div class="panel panel-default">';
        echo '<div class="panel-heading clearfix"> 
                <div class="row"> 
                <h1 class="panel-title text-center col-md-12" style="margin-top: 2px;"><strong>Sintético geral de ligações </strong></h1>
                </div></div>';
        echo '<div class="panel-body">';
		
		foreach ($dados_empresas as $conteudo_empresa) {
			$qtd_atendidas = 0;
			$qtd_perdidas = 0;
			$soma_notas = 0;
			$qtd_notas = 0;
			$soma_ta_atendidas = 0;
			$soma_te_atendidas = 0;
			$soma_te_perdidas = 0;

			$filtro_atendidas_empresa = " AND a.data2 LIKE '".$conteudo_empresa['id_asterisk']."%'";
			$filtro_perdidas_empresa = " AND a.data2 LIKE '".$conteudo_empresa['id_asterisk']."%'";

			$dados_atendidas = DBRead('snep','queue_log a',"INNER JOIN queue_log b ON (b.callid = a.callid AND b.event = 'CONNECT') INNER JOIN queue_log c ON (c.callid = a.callid AND (c.event = 'COMPLETEAGENT' OR c.event = 'COMPLETECALLER' OR c.event = 'BLINDTRANSFER' OR c.event = 'ATTENDEDTRANSFER')) LEFT JOIN pesquisa d ON a.callid = d.UNIQUEID WHERE a.event = 'ENTERQUEUE' $filtro_atendidas $filtro_atendidas_empresa GROUP BY b.id ORDER BY b.id", "a.callid AS 'enterqueue_callid', c.event AS 'finalizacao_event', c.data1 AS 'finalizacao_data1', c.data2 AS 'finalizacao_data2', c.data3 AS 'finalizacao_data3', c.data4 AS 'finalizacao_data4', d.nota AS 'nota', (SELECT SUM(data3) FROM queue_log e WHERE e.callid = a.callid AND e.event = 'EXITWITHTIMEOUT') AS 'tempo_espera_timeout'");

			if($dados_atendidas){
				foreach ($dados_atendidas as $conteudo_atendidas) {
					$te_entrada = 'n';								
					if($conteudo_atendidas['finalizacao_event']=='COMPLETEAGENT' || $conteudo_atendidas['finalizacao_event']=='COMPLETECALLER'){
						$soma_ta_atendidas +=  $conteudo_atendidas['finalizacao_data2'];
						$soma_te_atendidas += $conteudo_atendidas['finalizacao_data1'];
						$te_entrada = $conteudo_atendidas['finalizacao_data1'];				
					}elseif(($conteudo_atendidas['finalizacao_event']=='BLINDTRANSFER' || $conteudo_atendidas['finalizacao_event']=='ATTENDEDTRANSFER') && $conteudo_atendidas['finalizacao_data1'] != 'LINK'){
						$soma_ta_atendidas +=  $conteudo_atendidas['finalizacao_data4'];
						$soma_te_atendidas += $conteudo_atendidas['finalizacao_data3'];
						$te_entrada = $conteudo_atendidas['finalizacao_data3'];
					}
					if($conteudo_atendidas['tempo_espera_timeout'] && $conteudo_atendidas['tempo_espera_timeout'] > 0){
						$soma_te_atendidas += $conteudo_atendidas['tempo_espera_timeout'];
					}
					if($te_entrada != 'n'){
						if($te_entrada <= 30){
							$qtd_atendidas_30++;
						}else if($te_entrada <= 60){
							$qtd_atendidas_60++;
						}else if($te_entrada <= 90){
							$qtd_atendidas_90++;
						}else if($te_entrada <= 180){
							$qtd_atendidas_180++;
						}else if($te_entrada > 180){
							$qtd_atendidas_maior_180++;
						}
					}
					if($conteudo_atendidas['nota']){
						$soma_notas += $conteudo_atendidas['nota'];
						$qtd_notas++;	
					}			
					$qtd_atendidas++;
				}
			}

            $dados_perdidas = DBRead('snep','queue_log a',"INNER JOIN queue_log b ON (b.callid = a.callid AND (b.event = 'ABANDON' OR b.event = 'EXITWITHTIMEOUT')) WHERE a.event = 'ENTERQUEUE' AND a.callid NOT IN (SELECT callid FROM queue_log c WHERE c.callid = a.callid AND c.event = 'CONNECT') $filtro_perdidas $filtro_perdidas_empresa GROUP BY a.callid ORDER BY a.callid", "a.queuename AS 'enterqueue_queuename', a.callid AS 'enterqueue_callid', a.data2 AS 'enterqueue_data2', (SELECT SUM(f.data3) FROM queue_log f WHERE f.callid = a.callid AND (f.event = 'ABANDON' OR f.event = 'EXITWITHTIMEOUT')) AS 'finalizacao_data3'");

			if($dados_perdidas){
				foreach ($dados_perdidas as $conteudo_perdidas) {			
					if($conteudo_perdidas['finalizacao_data3'] <= 30){
						$qtd_perdidas_30++;
					}else if($conteudo_perdidas['finalizacao_data3'] <= 60){
						$qtd_perdidas_60++;
					}else if($conteudo_perdidas['finalizacao_data3'] <= 90){
						$qtd_perdidas_90++;
					}else if($conteudo_perdidas['finalizacao_data3'] <= 180){
						$qtd_perdidas_180++;
					}else if($conteudo_perdidas['finalizacao_data3'] > 180){
						$qtd_perdidas_maior_180++;
					}
					$qtd_perdidas++;			
					$soma_te_perdidas += $conteudo_perdidas['finalizacao_data3'];
				}
			}
		}

		echo '
	<table class="table table-hover"> 
		<thead> 
			<tr> 
				<th class="text-center" width="16.6666666667%">Total de atendidas</th>
				<th class="text-center" width="16.6666666667%">Atendidas até 30s</th>
				<th class="text-center" width="16.6666666667%">Atendidas até 60s</th>
				<th class="text-center" width="16.6666666667%">Atendidas até 90s</th>
				<th class="text-center" width="16.6666666667%">Atendidas até 180s</th>
				<th class="text-center" width="16.6666666667%">Atendidas maiores de 180s</th>
			</tr>
		</thead> 
		<tbody>
			<tr>
				<td class="text-center success">'.$qtd_atendidas.' (<span data-toggle="tooltip" data-placement="top" data-container="body" title="Referente a total de ligações">'.sprintf("%01.2f", round($qtd_atendidas*100/(($qtd_atendidas+$qtd_perdidas) == 0 ? 1 : ($qtd_atendidas+$qtd_perdidas)), 2)).'%</span>)</td>
				<td class="text-center success">'.$qtd_atendidas_30.' (<span data-toggle="tooltip" data-placement="top" data-container="body" title="Referente a total de atendidas">'.sprintf("%01.2f", round($qtd_atendidas_30*100/($qtd_atendidas == 0 ? 1 : $qtd_atendidas), 2)).'%</span> | <span data-toggle="tooltip" data-placement="top" data-container="body" title="Referente a total de ligações">'.sprintf("%01.2f", round($qtd_atendidas_30*100/(($qtd_atendidas+$qtd_perdidas) == 0 ? 1 : ($qtd_atendidas+$qtd_perdidas)), 2)).'%</span>)</td>
				<td class="text-center success">'.$qtd_atendidas_60.' (<span data-toggle="tooltip" data-placement="top" data-container="body" title="Referente a total de atendidas">'.sprintf("%01.2f", round($qtd_atendidas_60*100/($qtd_atendidas == 0 ? 1 : $qtd_atendidas), 2)).'%</span> | <span data-toggle="tooltip" data-placement="top" data-container="body" title="Referente a total de ligações">'.sprintf("%01.2f", round($qtd_atendidas_60*100/(($qtd_atendidas+$qtd_perdidas) == 0 ? 1 : ($qtd_atendidas+$qtd_perdidas)), 2)).'%</span>)</td>
				<td class="text-center success">'.$qtd_atendidas_90.' (<span data-toggle="tooltip" data-placement="top" data-container="body" title="Referente a total de atendidas">'.sprintf("%01.2f", round($qtd_atendidas_90*100/($qtd_atendidas == 0 ? 1 : $qtd_atendidas), 2)).'%</span> | <span data-toggle="tooltip" data-placement="top" data-container="body" title="Referente a total de ligações">'.sprintf("%01.2f", round($qtd_atendidas_90*100/(($qtd_atendidas+$qtd_perdidas) == 0 ? 1 : ($qtd_atendidas+$qtd_perdidas)), 2)).'%</span>)</td>
				<td class="text-center success">'.$qtd_atendidas_180.' (<span data-toggle="tooltip" data-placement="top" data-container="body" title="Referente a total de atendidas">'.sprintf("%01.2f", round($qtd_atendidas_180*100/($qtd_atendidas == 0 ? 1 : $qtd_atendidas), 2)).'%</span> | <span data-toggle="tooltip" data-placement="top" data-container="body" title="Referente a total de ligações">'.sprintf("%01.2f", round($qtd_atendidas_180*100/(($qtd_atendidas+$qtd_perdidas) == 0 ? 1 : ($qtd_atendidas+$qtd_perdidas)), 2)).'%</span>)</td>
				<td class="text-center success">'.$qtd_atendidas_maior_180.' (<span data-toggle="tooltip" data-placement="top" data-container="body" title="Referente a total de atendidas">'.sprintf("%01.2f", round($qtd_atendidas_maior_180*100/($qtd_atendidas == 0 ? 1 : $qtd_atendidas), 2)).'%</span> | <span data-toggle="tooltip" data-placement="top" data-container="body" title="Referente a total de ligações">'.sprintf("%01.2f", round($qtd_atendidas_maior_180*100/(($qtd_atendidas+$qtd_perdidas) == 0 ? 1 : ($qtd_atendidas+$qtd_perdidas)), 2)).'%</span>)</td>
			</tr>
		</tbody> 
	</table>
	<table class="table table-hover"> 
		<thead> 
			<tr> 
				<th class="text-center" width="16.6666666667%">Total de perdidas</th>
				<th class="text-center" width="16.6666666667%">Perdidas até 30s</th>
				<th class="text-center" width="16.6666666667%">Perdidas até 60s</th>
				<th class="text-center" width="16.6666666667%">Perdidas até 90s</th>
				<th class="text-center" width="16.6666666667%">Perdidas até 180s</th>
				<th class="text-center" width="16.6666666667%">Perdidas maiores de 180s</th>
			</tr>
		</thead> 
		<tbody>
			<tr>
				<td class="text-center danger">'.$qtd_perdidas.' (<span data-toggle="tooltip" data-placement="top" data-container="body" title="Referente a total de ligações">'.sprintf("%01.2f", round($qtd_perdidas*100/(($qtd_atendidas+$qtd_perdidas) == 0 ? 1 : ($qtd_atendidas+$qtd_perdidas)), 2)).'%</span>)</td>
				<td class="text-center danger">'.$qtd_perdidas_30.' (<span data-toggle="tooltip" data-placement="top" data-container="body" title="Referente a total de perdidas">'.sprintf("%01.2f", round($qtd_perdidas_30*100/($qtd_perdidas == 0 ? 1 : $qtd_perdidas), 2)).'%</span> | <span data-toggle="tooltip" data-placement="top" data-container="body" title="Referente a total de ligações">'.sprintf("%01.2f", round($qtd_perdidas_30*100/(($qtd_atendidas+$qtd_perdidas) == 0 ? 1 : ($qtd_atendidas+$qtd_perdidas)), 2)).'%</span>)</td>
				<td class="text-center danger">'.$qtd_perdidas_60.' (<span data-toggle="tooltip" data-placement="top" data-container="body" title="Referente a total de perdidas">'.sprintf("%01.2f", round($qtd_perdidas_60*100/($qtd_perdidas == 0 ? 1 : $qtd_perdidas), 2)).'%</span> | <span data-toggle="tooltip" data-placement="top" data-container="body" title="Referente a total de ligações">'.sprintf("%01.2f", round($qtd_perdidas_60*100/(($qtd_atendidas+$qtd_perdidas) == 0 ? 1 : ($qtd_atendidas+$qtd_perdidas)), 2)).'%</span>)</td>
				<td class="text-center danger">'.$qtd_perdidas_90.' (<span data-toggle="tooltip" data-placement="top" data-container="body" title="Referente a total de perdidas">'.sprintf("%01.2f", round($qtd_perdidas_90*100/($qtd_perdidas == 0 ? 1 : $qtd_perdidas), 2)).'%</span> | <span data-toggle="tooltip" data-placement="top" data-container="body" title="Referente a total de ligações">'.sprintf("%01.2f", round($qtd_perdidas_90*100/(($qtd_atendidas+$qtd_perdidas) == 0 ? 1 : ($qtd_atendidas+$qtd_perdidas)), 2)).'%</span>)</td>
				<td class="text-center danger">'.$qtd_perdidas_180.' (<span data-toggle="tooltip" data-placement="top" data-container="body" title="Referente a total de perdidas">'.sprintf("%01.2f", round($qtd_perdidas_180*100/($qtd_perdidas == 0 ? 1 : $qtd_perdidas), 2)).'%</span> | <span data-toggle="tooltip" data-placement="top" data-container="body" title="Referente a total de ligações">'.sprintf("%01.2f", round($qtd_perdidas_180*100/(($qtd_atendidas+$qtd_perdidas) == 0 ? 1 : ($qtd_atendidas+$qtd_perdidas)), 2)).'%</span>)</td>
				<td class="text-center danger">'.$qtd_perdidas_maior_180.' (<span data-toggle="tooltip" data-placement="top" data-container="body" title="Referente a total de perdidas">'.sprintf("%01.2f", round($qtd_perdidas_maior_180*100/($qtd_perdidas == 0 ? 1 : $qtd_perdidas), 2)).'%</span> | <span data-toggle="tooltip" data-placement="top" data-container="body" title="Referente a total de ligações">'.sprintf("%01.2f", round($qtd_perdidas_maior_180*100/(($qtd_atendidas+$qtd_perdidas) == 0 ? 1 : ($qtd_atendidas+$qtd_perdidas)), 2)).'%</span>)</td>
			</tr>
		</tbody> 
	</table>	
	<table class="table table-hover"> 
		<thead> 
			<tr> 
				<th class="text-center" width="16.6666666667%">Total de ligações</th>
				<th class="text-center" width="16.6666666667%" title="Tempo Médio de Atendimento">TMA</th>
				<th class="text-center" width="16.6666666667%" title="Nota Média de Atendimento">NMA</th>
				<th class="text-center" width="16.6666666667%" title="Ligações com Nota">LN</th>				
				<th class="text-center" width="16.6666666667%" title="Tempo Médio de Espera">TME</th>
				<th class="text-center" width="16.6666666667%" title="Tempo Médio das Perdidas">TMP</th>
			</tr>
		</thead> 
		<tbody>
			<tr>
				<td class="text-center active">'.($qtd_atendidas+$qtd_perdidas).'</td>
				<td class="text-center info">'.gmdate("H:i:s", $soma_ta_atendidas/($qtd_atendidas == 0 ? 1 : $qtd_atendidas)).'</td>
				<td class="text-center info">'.sprintf("%01.2f", round($soma_notas/($qtd_notas == 0 ? 1 : $qtd_notas), 2)).'</td>
				<td class="text-center info">'.sprintf("%01.2f", round($qtd_notas*100/($qtd_atendidas == 0 ? 1 : $qtd_atendidas), 2)).'%</td>
				<td class="text-center warning">'.gmdate("H:i:s", $soma_te_atendidas/($qtd_atendidas == 0 ? 1 : $qtd_atendidas)).'</td>
				<td class="text-center warning">'.gmdate("H:i:s", $soma_te_perdidas/($qtd_perdidas == 0 ? 1 : $qtd_perdidas)).'</td>
			</tr>
		</tbody> 
	</table>
	';  

		echo '
		</div></div>';
		
	}
    //SINTETICO GERAL LIGACOES

	echo '<br><br>';

    //RESOLUCAO
    $dados_consulta = DBRead('', 'tb_contrato_plano_pessoa a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa INNER JOIN tb_plano c ON a.id_plano = c.id_plano WHERE c.cod_servico = 'call_suporte' AND a.id_contrato_plano_pessoa = $id_contrato_plano_pessoa AND a.status = 1 AND b.nome NOT LIKE '%Belluno%' ORDER BY b.nome ASC", "a.*, b.*, a.status AS status_contrato");

	if ($dados_consulta) {

        echo '<div class="panel panel-default">';
        echo '<div class="panel-heading clearfix"> <div class="row"> 
            <h1 class="panel-title text-center col-md-12" style="margin-top: 2px;"><strong>Resolução</strong></h1>
            </div></div>';
        echo '<div class="panel-body">';

		echo '<table class="table table-hover dataTable" style="margin-bottom:0;">
			<thead>
				<tr>
					<th class="text-left col-md-1">Não Resolvido</th>
					<th class="text-left col-md-1">Não Resolvido (%)</th>
					<th class="text-left col-md-1">Resolvido</th>
					<th class="text-left col-md-1">Resolvido (%)</th>
					<th class="text-left col-md-1">Diagnosticado</th>
					<th class="text-left col-md-1">Diagnosticado (%)</th>
					<th class="text-left col-md-1">Diagnosticado + Resolvido</th>
					<th class="text-left col-md-1">Diagnosticado + Resolvido (%)</th>
					<th class="text-left col-md-1">Total</th>
				</tr>
			</thead>
			<tbody>';

		$contador_n_rest = 0;
		$contador_res = 0;
		$contador_diag = 0;
		$contador_total = 0;

		foreach ($dados_consulta as $dado_consulta) {
			$cont_nao_resolvidos = 0;
			$cont_resolvidos = 0;
			$cont_diagnosticados = 0;
			$total = 0;

			$cont_dados_nao_resolvidos = DBRead('', 'tb_atendimento a', " WHERE a.falha != '2' AND a.gravado = '1' AND a.resolvido = '2' AND a.data_inicio BETWEEN '" . $data_de . " 00:00:00' AND '" . $data_ate . " 23:59:59' AND a.id_contrato_plano_pessoa = '" . $id_contrato_plano_pessoa . "' ");

			if ($cont_dados_nao_resolvidos) {
				foreach ($cont_dados_nao_resolvidos as $conteudo) {
					$cont_nao_resolvidos += 1;
				}
			}

			$cont_dados_resolvidos = DBRead('', 'tb_atendimento a', " WHERE a.falha != '2' AND a.gravado = '1' AND a.resolvido = '1' AND a.data_inicio BETWEEN '" . $data_de . " 00:00:00' AND '" . $data_ate . " 23:59:59' AND a.id_contrato_plano_pessoa = '" . $id_contrato_plano_pessoa . "' ");

			if ($cont_dados_resolvidos) {
				foreach ($cont_dados_resolvidos as $conteudo) {
					$cont_resolvidos += 1;
				}
			}

			$cont_dados_diagnosticados = DBRead('', 'tb_atendimento a', " WHERE a.falha != '2' AND a.gravado = '1' AND a.resolvido = '3' AND a.data_inicio BETWEEN '" . $data_de . " 00:00:00' AND '" . $data_ate . " 23:59:59' AND a.id_contrato_plano_pessoa = '" . $id_contrato_plano_pessoa . "' ");

			if ($cont_dados_diagnosticados) {
				foreach ($cont_dados_diagnosticados as $conteudo) {
					$cont_diagnosticados += 1;
				}
			}

			$total = $cont_nao_resolvidos + $cont_resolvidos + $cont_diagnosticados;

			$percentual_cont_nao_resolvidos = sprintf("%01.2f", round($cont_nao_resolvidos * 100) / ($total == 0 ? 1 : $total), 2);
			$percentual_cont_diagnosticados = sprintf("%01.2f", round($cont_diagnosticados * 100) / ($total == 0 ? 1 : $total), 2);
			$percentual_cont_resolvidos = sprintf("%01.2f", round($cont_resolvidos * 100) / ($total == 0 ? 1 : $total), 2);

			$cont_diagnosticados_resolvidos = $cont_diagnosticados + $cont_resolvidos;
			$percentual_cont_diagnosticados_resolvidos = sprintf("%01.2f", round($cont_diagnosticados_resolvidos * 100) / ($total == 0 ? 1 : $total), 2);
			echo "<div class ='row'>";
			echo '<tr>                
					<td class="text-left">' . $cont_nao_resolvidos . '</td>
					<td class="text-left">' . $percentual_cont_nao_resolvidos . '%</td>
					<td class="text-left">' . $cont_resolvidos . '</td>                
					<td class="text-left">' . $percentual_cont_resolvidos . '%</td>    
					<td class="text-left">' . $cont_diagnosticados . '</td>
					<td class="text-left">' . $percentual_cont_diagnosticados . '%</td>
					<td class="text-left">' . $cont_diagnosticados_resolvidos . '</td>                
					<td class="text-left success">' . $percentual_cont_diagnosticados_resolvidos . '%</td>  
					<td class="text-left">' . $total . '</td>                
				</tr>';

			$contador_n_rest = $contador_n_rest + $cont_nao_resolvidos;
			$contador_res = $contador_res + $cont_resolvidos;
			$contador_diag = $contador_diag + $cont_diagnosticados;
			$contador_total = $contador_total + $total;
		}
		$percentual_contador_n_rest = sprintf("%01.2f", ($contador_n_rest * 100) / ($contador_total == 0 ? 1 : $contador_total));
		$percentual_contador_res = sprintf("%01.2f", ($contador_res * 100) / ($contador_total == 0 ? 1 : $contador_total));
		$percentual_contador_diag = sprintf("%01.2f", ($contador_diag * 100) / ($contador_total == 0 ? 1 : $contador_total));
		$contador_diag_res = $contador_diag + $contador_res;
		$percentual_contador_diag_res = sprintf("%01.2f", ($contador_diag_res * 100) / ($contador_total == 0 ? 1 : $contador_total));

		echo '</tbody>';
		echo '</table><br>';

		echo "<div class='row' id='graficos'>";
			echo "<div id='container-resolucao' style='overflow: initial !important; min-width: 310px; max-width: 600px; margin: 0px auto;'>";
			
			echo "</div>";
		echo "</div></div></div>";

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
			
			Highcharts.chart('container-resolucao', {
				chart: {
					plotBackgroundColor: null,
					plotBorderWidth: null,
					plotShadow: false,
					type: 'pie'
				},
				title: {
					text: ''
				},
				subtitle: {
					text: ''
				},
				tooltip: {
					pointFormat: '{series.name} : <b>{point.percentage:.1f}%</b>, '
				},
					legend: {
					enabled: true //desabilita a legenda. Setar para true se quiser habilitar a legenda
				},	  
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
				series: [
					{
						name: '',
						colorByPoint: true,
						data: [	
								{
									name: 'Resolvido',
									y: <?= $contador_res ?>,
									color: "#58FAAC"
								},
								{
									name: 'Diagnosticado',
									y: <?= $contador_diag ?>,
									color: "#81BEF7"
								},
								{
									name: 'Não resolvido',
									y: <?= $contador_n_rest ?>,
									color: "#F7D358"
								},
						],
						showInLegend: true
					}
				]
			});
			
		</script>

		<?php

	} else {

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
    //RESOLUCAO

    //AREA E SUBAREA
    $dados_consulta = DBRead('', 'tb_atendimento a', "INNER JOIN tb_subarea_problema_atendimento b ON a.id_atendimento = b.id_atendimento INNER JOIN tb_subarea_problema c ON b.id_subarea_problema = c.id_subarea_problema INNER JOIN tb_contrato_plano_pessoa d ON a.id_contrato_plano_pessoa = d.id_contrato_plano_pessoa INNER JOIN tb_pessoa e ON d.id_pessoa = e.id_pessoa INNER JOIN tb_usuario f ON a.id_usuario = f.id_usuario INNER JOIN tb_pessoa g ON f.id_pessoa = g.id_pessoa INNER JOIN tb_area_problema h ON c.id_area_problema = h.id_area_problema INNER JOIN tb_plano i ON d.id_plano = i.id_plano INNER JOIN tb_situacao_atendimento j ON a.id_atendimento = j.id_atendimento INNER JOIN tb_situacao k ON j.id_situacao = k.id_situacao WHERE a.data_inicio >= '" . $data_de . " 00:00:00' AND a.data_inicio < '" . $data_ate . " 23:59:59' AND d.id_contrato_plano_pessoa = $id_contrato_plano_pessoa ORDER BY e.nome ASC", "k.nome AS nome_situacao, a.data_inicio, a.resolvido , c.descricao, e.nome AS nome_empresa, g.nome AS nome_atendente, d.id_plano, h.nome AS nome_area_problema, d.nome_contrato, i.nome AS nome_plano, i.cod_servico");

	//Consulta para atendimentos incompletos
	$dados_atendimentos_incompletos = DBRead('', 'tb_atendimento a', "INNER JOIN tb_contrato_plano_pessoa b ON a.id_contrato_plano_pessoa = b.id_contrato_plano_pessoa INNER JOIN tb_pessoa c ON b.id_pessoa = c.id_pessoa INNER JOIN tb_usuario d ON a.id_usuario = d.id_usuario INNER JOIN tb_pessoa e ON d.id_pessoa = e.id_pessoa INNER JOIN tb_plano f ON b.id_plano = f.id_plano INNER JOIN tb_situacao_atendimento j ON a.id_atendimento = j.id_atendimento INNER JOIN tb_situacao k ON j.id_situacao = k.id_situacao  WHERE a.data_inicio >= '" . $data_de . " 00:00:00' AND a.data_inicio < '" . $data_ate . " 23:59:59' AND a.falha = 1 AND b.id_contrato_plano_pessoa = $id_contrato_plano_pessoa AND a.id_atendimento NOT IN (SELECT id_atendimento FROM tb_subarea_problema_atendimento) ORDER BY c.nome ASC", "k.nome as nome_situacao, a.data_inicio, a.resolvido, c.nome AS nome_empresa, e.nome AS nome_atendente, b.id_plano, b.nome_contrato, f.nome AS nome_plano, f.cod_servico");

	if ($dados_consulta || $dados_atendimentos_incompletos) {
		if ($dados_consulta && !$dados_atendimentos_incompletos) {
			$contador_total = count($dados_consulta);
		} else if (!$dados_consulta && $dados_atendimentos_incompletos) {
			$contador_total = count($dados_atendimentos_incompletos);
		} else {
			$contador_total = count($dados_consulta) + count($dados_atendimentos_incompletos);
		}
	}


	if ($dados_consulta || $dados_atendimentos_incompletos) {
		$qtd_sub_area = array();
		$qtd_area = array();
		if ($dados_consulta) {
			foreach ($dados_consulta as $conteudo_consulta) {

				$qtd_sub_area[$conteudo_consulta['descricao']]['quantidade'] += 1;
				$qtd_area[$conteudo_consulta['nome_area_problema']]['quantidade'] += 1;

				if ($qtd_sub_area[$conteudo_consulta['descricao']]['resolvido'] == '') {
					$qtd_sub_area[$conteudo_consulta['descricao']]['resolvido'] = 0;
				}

				if ($qtd_sub_area[$conteudo_consulta['descricao']]['nao_resolvido'] == '') {
					$qtd_sub_area[$conteudo_consulta['descricao']]['nao_resolvido'] = 0;
				}

				if ($qtd_sub_area[$conteudo_consulta['descricao']]['diagnosticado'] == '') {
					$qtd_sub_area[$conteudo_consulta['descricao']]['diagnosticado'] = 0;
				}

				if ($qtd_area[$conteudo_consulta['nome_area_problema']]['resolvido'] == '') {
					$qtd_area[$conteudo_consulta['nome_area_problema']]['resolvido'] = 0;
				}

				if ($qtd_area[$conteudo_consulta['nome_area_problema']]['nao_resolvido'] == '') {
					$qtd_area[$conteudo_consulta['nome_area_problema']]['nao_resolvido'] = 0;
				}

				if ($qtd_area[$conteudo_consulta['nome_area_problema']]['diagnosticado'] == '') {
					$qtd_area[$conteudo_consulta['nome_area_problema']]['diagnosticado'] = 0;
				}

				if ($conteudo_consulta['resolvido'] == 1) {
					$qtd_sub_area[$conteudo_consulta['descricao']]['resolvido'] += 1;
					$qtd_area[$conteudo_consulta['nome_area_problema']]['resolvido'] += 1;

				} else if ($conteudo_consulta['resolvido'] == 2) {
					$qtd_sub_area[$conteudo_consulta['descricao']]['nao_resolvido'] += 1;
					$qtd_area[$conteudo_consulta['nome_area_problema']]['nao_resolvido'] += 1;

				} else if ($conteudo_consulta['resolvido'] == 3) {
					$qtd_sub_area[$conteudo_consulta['descricao']]['diagnosticado'] += 1;
					$qtd_area[$conteudo_consulta['nome_area_problema']]['diagnosticado'] += 1;	
				} 
			}
		}

		if ($dados_atendimentos_incompletos) {
			foreach ($dados_atendimentos_incompletos as $atendimentos_incompletos) {

				$qtd_sub_area['Atendimento Incompleto']['quantidade'] += 1;
				$qtd_area['Atendimento Incompleto']['quantidade'] += 1;

				if ($qtd_sub_area['Atendimento Incompleto']['resolvido'] == '') {
					$qtd_sub_area['Atendimento Incompleto']['resolvido'] = 0;
				}

				if ($qtd_sub_area['Atendimento Incompleto']['nao_resolvido'] == '') {
					$qtd_sub_area['Atendimento Incompleto']['nao_resolvido'] = 0;
				}

				if ($qtd_sub_area['Atendimento Incompleto']['diagnosticado'] == '') {
					$qtd_sub_area['Atendimento Incompleto']['diagnosticado'] = 0;
				}

				if ($qtd_area['Atendimento Incompleto']['resolvido'] == '') {
					$qtd_area['Atendimento Incompleto']['resolvido'] = 0;
				}

				if ($qtd_area['Atendimento Incompleto']['nao_resolvido'] == '') {
					$qtd_area['Atendimento Incompleto']['nao_resolvido'] = 0;
				}

				if ($qtd_area['Atendimento Incompleto']['diagnosticado'] == '') {
					$qtd_area['Atendimento Incompleto']['diagnosticado'] = 0;
				}

				if ($conteudo_consulta['resolvido'] == 1) {
					$qtd_sub_area['Atendimento Incompleto']['resolvido'] += 1;
					$qtd_area['Atendimento Incompleto']['resolvido'] += 1;

				} else if ($conteudo_consulta['resolvido'] == 2) {
					$qtd_sub_area['Atendimento Incompleto']['nao_resolvido'] += 1;
					$qtd_area['Atendimento Incompleto']['nao_resolvido'] += 1;

				} else if ($conteudo_consulta['resolvido'] == 3) {
					$qtd_sub_area['Atendimento Incompleto']['diagnosticado'] += 1;
					$qtd_area['Atendimento Incompleto']['diagnosticado'] += 1;	
				} 
			}
		}

        echo '<br><br>';
        echo '<div class="panel panel-default">';
        echo '<div class="panel-heading clearfix"> <div class="row"> 
            <h1 class="panel-title text-center col-md-12" style="margin-top: 2px;"><strong>Subáreas de finalização</strong></h1>
            </div></div>';
        echo '<div class="panel-body">';

		echo '
				<table class="table table-hover dataTable" style="margin-bottom:0;">
					      <thead>
					        <tr>			       
					            <th class="text-left col-md-4">Subárea de Problema</th>
								<th class="text-left col-md-2">Total</th>
					            <th class="text-left col-md-2">Resolvido</th>
					            <th class="text-left col-md-2">Diagnosticado</th>
					            <th class="text-left col-md-2">Não resolvido</th>
					        </tr>
					      </thead>
					      <tbody>';

		ksort($qtd_sub_area);

		$array_sub = array();
		$array_resolvidos = array();
		$array_diagnosticados = array();
		$array_nao_resolvidos = array();
		foreach ($qtd_sub_area as $key => $conteudo) {

			if (!in_array($key, $array_sub)){
				array_push($array_sub, $key);
			}

			array_push($array_resolvidos,  $conteudo['resolvido']);
			array_push($array_diagnosticados, $conteudo['diagnosticado']);
			array_push($array_nao_resolvidos, $conteudo['nao_resolvido']);

			$class_quantidade = '';
			if ($conteudo['quantidade'] > 0) {
				$class_quantidade = 'active';
			}

			$class_resolvido = '';
			if ($conteudo['resolvido'] > 0) {
				$class_resolvido = 'success';
			}

			$class_diagnosticado = '';
			if ($conteudo['diagnosticado'] > 0) {
				$class_diagnosticado = 'info';
			}

			$class_nao_resolvido = '';
			if ($conteudo['nao_resolvido'] > 0) {
				$class_nao_resolvido = 'warning';
			}

			echo '<tr>';
			echo '<td>' . $key . '</td>';
			echo '<td class="'.$class_quantidade.'">' . $conteudo['quantidade'] . '</td>';
			echo '<td class="'.$class_resolvido.'">' . $conteudo['resolvido'] . '</td>';
			echo '<td class="'.$class_diagnosticado.'">' . $conteudo['diagnosticado'] . '</td>';
			echo '<td class="'.$class_nao_resolvido.'">' . $conteudo['nao_resolvido'] . '</td>';
			echo '</tr>';
		}
		echo '</tbody>
				</table>
				<br>';

		?>
        <div id="grafico-subarea"></div> 
        <script>
            $(function () {
                // Create the first chart
                $('#grafico-subarea').highcharts({
                    chart: {
                        type: 'column'
                    },
                    title: {
                        text: '' // Title for the chart
                    },
                    xAxis: {
                        categories: <?php echo json_encode($array_sub) ?>
                        // Categories for the charts
                    },
				    yAxis: {
				        min: 0,
				        stackLabels: {
				            enabled: true,
				            style: {
				                fontWeight: 'bold',
				                color: (Highcharts.theme && Highcharts.theme.textColor) || 'black'
				            }
				        }
				    },
				    legend: {
				        enabled:true,
				        backgroundColor: (Highcharts.theme && Highcharts.theme.background2) || 'white',
				        borderColor: '#CCC',
				        borderWidth: 1,
				        shadow: false
				    },
				    tooltip: {
				        pointFormat: '<span style="color:{series.color}">{series.name}</span>: <b>{point.y}</b> ({point.percentage:.0f}%)<br/>',
   						 shared: true
				    },
                    colors: [
                            '#58FAAC',
                            '#81BEF7',
							'#F7D358'
                        ],
				    plotOptions: {
						column: {
							stacking: 'normal',
							dataLabels: {
								enabled: true,
								color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white'
							}
						}
				    },
                    series: [
                    	{
                            name: 'Resolvido', // Name of your series
                            data: <?php echo json_encode($array_resolvidos, JSON_NUMERIC_CHECK) ?> // The data in your series
                        },
                        {
                            name: 'Diagnosticado', // Name of your series
                            data: <?php echo json_encode($array_diagnosticados, JSON_NUMERIC_CHECK) ?> // The data in your series
                        },
						{
                            name: 'Não resolvido', // Name of your series
                            data: <?php echo json_encode($array_nao_resolvidos, JSON_NUMERIC_CHECK) ?> // The data in your series
                        }
					],
                    navigation: {
                        buttonOptions: {
                            enabled: true
                        }
                    }
                });
            });
        </script>   
        <?php
        echo '</div></div>';

        echo '<br><br>';
        echo '<div class="panel panel-default">';
        echo '<div class="panel-heading clearfix"> <div class="row"> 
            <h1 class="panel-title text-center col-md-12" style="margin-top: 2px;"><strong>Áreas de finalização</strong></h1>
            </div></div>';
        echo '<div class="panel-body">';

		echo '
				<table class="table table-hover dataTable" style="margin-bottom:0;">
					      <thead>
					        <tr>			       
					            <th class="text-left col-md-4">Área de Problema</th>
					            <th class="text-left col-md-2">Total</th>
								<th class="text-left col-md-2">Resolvido</th>
					            <th class="text-left col-md-2">Diagnosticado</th>
					            <th class="text-left col-md-2">Não resolvido</th>
					        </tr>
					      </thead>
					      <tbody>';

		
	    arsort($qtd_area);
		
		$array_area = array();
		$array_area_resolvidos = array();
		$array_area_diagnosticados = array();
		$array_area_nao_resolvidos = array();

		foreach ($qtd_area as $key => $conteudo) {

			if (!in_array($key, $array_area)){
				array_push($array_area, $key);
			}

			array_push($array_area_resolvidos,  $conteudo['resolvido']);
			array_push($array_area_diagnosticados, $conteudo['diagnosticado']);
			array_push($array_area_nao_resolvidos, $conteudo['nao_resolvido']);

			$class_quantidade = '';
			if ($conteudo['quantidade'] > 0) {
				$class_quantidade = 'active';
			}

			$class_resolvido = '';
			if ($conteudo['resolvido'] > 0) {
				$class_resolvido = 'success';
			}

			$class_diagnosticado = '';
			if ($conteudo['diagnosticado'] > 0) {
				$class_diagnosticado = 'info';
			}

			$class_nao_resolvido = '';
			if ($conteudo['nao_resolvido'] > 0) {
				$class_nao_resolvido = 'warning';
			}

			echo '<tr>';
			echo '<td>' . $key . '</td>';
			echo '<td class="'.$class_quantidade.'">' . $conteudo['quantidade'] . '</td>';
			echo '<td class="'.$class_resolvido.'">' . $conteudo['resolvido'] . '</td>';
			echo '<td class="'.$class_diagnosticado.'">' . $conteudo['diagnosticado'] . '</td>';
			echo '<td class="'.$class_nao_resolvido.'">' . $conteudo['nao_resolvido'] . '</td>';
			echo '</tr>';
		}
		echo '</tbody>
				</table><br>';

				?>
        <div id="grafico-area"></div> 
        <script>
            $(function () {
                // Create the first chart
                $('#grafico-area').highcharts({
                    chart: {
                        type: 'column'
                    },
                    title: {
                        text: '' // Title for the chart
                    },
                    xAxis: {
                        categories: <?php echo json_encode($array_area) ?>
                        // Categories for the charts
                    },
				    yAxis: {
				        min: 0,
				        stackLabels: {
				            enabled: true,
				            style: {
				                fontWeight: 'bold',
				                color: (Highcharts.theme && Highcharts.theme.textColor) || 'black'
				            }
				        }
				    },
				    legend: {
				        enabled:true,
				        backgroundColor: (Highcharts.theme && Highcharts.theme.background2) || 'white',
				        borderColor: '#CCC',
				        borderWidth: 1,
				        shadow: false
				    },
				    tooltip: {
				        pointFormat: '<span style="color:{series.color}">{series.name}</span>: <b>{point.y}</b> ({point.percentage:.0f}%)<br/>',
   						 shared: true
				    },
                    colors: [
                            '#58FAAC',
                            '#81BEF7',
							'#F7D358'
                        ],
				    plotOptions: {
						column: {
							stacking: 'normal',
							dataLabels: {
								enabled: true,
								color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white'
							}
						}
				    },
                    series: [
                    	{
                            name: 'Resolvido', // Name of your series
                            data: <?php echo json_encode($array_area_resolvidos, JSON_NUMERIC_CHECK) ?> // The data in your series
                        },
                        {
                            name: 'Diagnosticado', // Name of your series
                            data: <?php echo json_encode($array_area_diagnosticados, JSON_NUMERIC_CHECK) ?> // The data in your series
                        },
						{
                            name: 'Não resolvido', // Name of your series
                            data: <?php echo json_encode($array_area_nao_resolvidos, JSON_NUMERIC_CHECK) ?> // The data in your series
                        }
					],
                    navigation: {
                        buttonOptions: {
                            enabled: true
                        }
                    }
                });
            });
        </script>   
        <?php

        echo "</div></div>";
	} else {

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
    //AREA E SUBAREA

    //SITUAÇÕES
    $dados_consulta = DBRead('', 'tb_atendimento a', "INNER JOIN tb_situacao_atendimento b ON a.id_atendimento = b.id_atendimento INNER JOIN tb_situacao c ON b.id_situacao = c.id_situacao INNER JOIN tb_contrato_plano_pessoa d ON a.id_contrato_plano_pessoa = d.id_contrato_plano_pessoa INNER JOIN tb_pessoa e ON d.id_pessoa = e.id_pessoa INNER JOIN tb_usuario f ON a.id_usuario = f.id_usuario INNER JOIN tb_pessoa g ON f.id_pessoa = g.id_pessoa WHERE a.data_inicio >= '" . $data_de . " 00:00:00' AND a.data_inicio < '" . $data_ate . " 23:59:59' AND d.id_contrato_plano_pessoa = $id_contrato_plano_pessoa ORDER BY c.nome ASC", "a.id_atendimento, c.nome, a.id_contrato_plano_pessoa, a.data_inicio, a.id_usuario, d.id_plano, e.nome AS nome_empresa, g.nome AS nome_atendente, d.nome_contrato");

	$at_encerrado = 0;
	$at_encaminhado = 0;
	$at_vinculado = 0;

    if ($dados_consulta) {
		$qtd_empresas = array();
		$qtd_situacao = array();
		foreach ($dados_consulta as $conteudo_consulta) {

			$qtd_empresas[$contrato] += 1;
			$qtd_situacao[$conteudo_consulta['nome']] += 1;

			if ($conteudo_consulta['nome'] == 'ATENDIMENTO ENCAMINHADO AO SETOR RESPONSÁVEL.') {
				$at_encaminhado += 1;

			} else if ($conteudo_consulta['nome'] == 'ATENDIMENTO ENCERRADO.') {
				$at_encerrado += 1;

			} else if ($conteudo_consulta['nome'] == 'ATENDIMENTO VINCULADO A OS JÁ EXISTENTE.') {
				$at_vinculado += 1;
			}
		}

        echo '<br><br>';
        echo '<div class="panel panel-default">';
        echo '<div class="panel-heading clearfix"> <div class="row"> 
            <h1 class="panel-title text-center col-md-12" style="margin-top: 2px;"><strong>Situação</strong></h1>
            </div></div>';
        echo '<div class="panel-body">';

		echo '
				<table class="table table-hover dataTable" style="margin-bottom:0;">
					      <thead>
					        <tr>			       
					            <th class="text-left col-md-8">Situação</th>
					            <th class="text-left col-md-4">Total</th>
					        </tr>
					      </thead>
					      <tbody>';

		arsort($qtd_situacao);
		
		foreach ($qtd_situacao as $situacao => $qtd) {
			echo '<tr>';
			echo '<td>' . $situacao . '</td>';
			echo '<td>' . $qtd . '</td>';
			echo '</tr>';
		}
		echo '</tbody>
				</table><br>';

		echo "<div class='row' id='grafico-situacao'>";
			echo "<div id='container-situacao' style='min-width: 310px; max-width: 600px; margin: 0px auto; overflow: hidden;'>";
			
			echo "</div>";
		echo "</div>";

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
			
			Highcharts.chart('container-situacao', {
				chart: {
					plotBackgroundColor: null,
					plotBorderWidth: null,
					plotShadow: false,
					type: 'pie'
				},
				title: {
					text: ''
				},
				subtitle: {
					text: ''
				},
				tooltip: {
					pointFormat: '{series.name} : <b>{point.percentage:.1f}%</b>, '
				},
					legend: {
					enabled: true //desabilita a legenda. Setar para true se quiser habilitar a legenda
				},	  
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
				series: [
					{
						name: '',
						colorByPoint: true,
						data: [	
								{
									name: 'Encerrado',
									y: <?= $at_encerrado ?>,
									color: "#58FAAC"
								},
								{
									name: 'Encaminhado ao setor responsável',
									y: <?= $at_encaminhado ?>,
									color: "#81BEF7"
								},
								{
									name: 'Vinculado a OS já existente',
									y: <?= $at_vinculado ?>,
									color: "#F7D358"
								},
						],
						showInLegend: true
					}
				]
			});
			
		</script>

		<?php

		echo '</div></div>';

	} else {

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
    //SITUAÇÕES

    echo "</div></div></div>";
}

?>