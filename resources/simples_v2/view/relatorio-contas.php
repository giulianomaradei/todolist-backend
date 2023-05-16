<?php
require_once(__DIR__."/../class/System.php");

$primeiro_dia = new DateTime(getDataHora('data'));
$primeiro_dia->modify('first day of this month');
$primeiro_dia = $primeiro_dia->format('d/m/Y');

$ultimo_dia = new DateTime(getDataHora('data'));
$ultimo_dia->modify('last day of this month');
$ultimo_dia = $ultimo_dia->format('d/m/Y');

$data_de = (!empty($_POST['data_de'])) ? $_POST['data_de'] : $primeiro_dia;
$data_ate = (!empty($_POST['data_ate'])) ? $_POST['data_ate'] : $ultimo_dia;

$gerar = (!empty($_POST['gerar'])) ? 1 : 0;
$tipo_relatorio = (!empty($_POST['tipo_relatorio'])) ? $_POST['tipo_relatorio'] : 3;

$id_caixa = (!empty($_POST['id_caixa'])) ? $_POST['id_caixa'] : '';
$situacao = (!empty($_POST['situacao'])) ? $_POST['situacao'] : '';
$id_natureza_financeira_receber = (!empty($_POST['id_natureza_financeira_receber'])) ? $_POST['id_natureza_financeira_receber'] : '';
$id_natureza_financeira_pagar = (!empty($_POST['id_natureza_financeira_pagar'])) ? $_POST['id_natureza_financeira_pagar'] : '';
$tipo = (!empty($_POST['tipo'])) ? $_POST['tipo'] : '';

$id_pessoa = (!empty($_POST['id_pessoa'])) ? $_POST['id_pessoa'] : '';

$incluir_atrasadas = (!empty($_POST['incluir_atrasadas'])) ? $_POST['incluir_atrasadas'] : 0;
$data_ate_fluxo_caixa = converteDAta(date('Y-m-d', strtotime("+1 years",strtotime(getDataHora()))));
$data_ate_fluxo = (!empty($_POST['data_ate_fluxo'])) ? $_POST['data_ate_fluxo'] : $data_ate_fluxo_caixa;
$dias_descarte = (!empty($_POST['dias_descarte'])) ? $_POST['dias_descarte'] : 0;

$ignorar_transferencia = (!empty($_POST['ignorar_transferencia'])) ? $_POST['ignorar_transferencia'] : '';

$origem = (!empty($_POST['origem'])) ? $_POST['origem'] : '';

$ignora_movimentacao = (!empty($_POST['ignora_movimentacao'])) ? $_POST['ignora_movimentacao'] : '';

$considerar_contratos = (!empty($_POST['considerar_contratos'])) ? $_POST['considerar_contratos'] : '1';

if($id_pessoa){
	$dados_pessoa = DBRead('', 'tb_pessoa', "WHERE id_pessoa = '".$id_pessoa."' ");
	$nome_pessoa = $dados_pessoa[0]['nome'];
}

if($gerar){
	$collapse = '';
	$collapse_icon = 'plus';
}else{
	$collapse = 'in';
	$collapse_icon = 'minus';
} 

if($tipo_relatorio == 1){
	$display_row_caixa = '';
	$display_row_situacao = '';
	$display_row_natureza_financeira_receber = '';
	$display_row_natureza_financeira_pagar = 'style="display:none;"';
	$display_row_tipo = 'style="display:none;"';
	$display_row_periodo = '';
	$display_row_pessoa = '';
	$display_row_ignorar_transferencia = 'style="display:none;"';
	$display_row_origem = 'style="display:none;"';
	$display_row_ignora_movimentacao = '';
	$display_row_considerar_contratos = 'style="display:none;"';

}else if($tipo_relatorio == 2){
	$display_row_caixa = '';
	$display_row_situacao = '';
	$display_row_natureza_financeira_receber = 'style="display:none;"';
	$display_row_natureza_financeira_pagar = '';
	$display_row_tipo = 'style="display:none;"';
	$display_row_periodo = '';
	$display_row_pessoa = '';
	$display_row_ignorar_transferencia = 'style="display:none;"';
	$display_row_origem = 'style="display:none;"';
	$display_row_ignora_movimentacao = '';
	$display_row_considerar_contratos = 'style="display:none;"';

}else if($tipo_relatorio == 3){
	$display_row_caixa = '';
	$display_row_situacao = 'style="display:none;"';
	$display_row_natureza_financeira_receber = 'style="display:none;"';
	$display_row_natureza_financeira_pagar = 'style="display:none;"';
	$display_row_tipo = 'style="display:none;"';
	$display_row_periodo = '';
	$display_row_pessoa = '';
	$display_row_ignorar_transferencia = 'style="display:none;"';
	$display_row_origem = 'style="display:none;"';
	$display_row_ignora_movimentacao = 'style="display:none;"';
	$display_row_considerar_contratos = 'style="display:none;"';

}else if($tipo_relatorio == 4){
	$display_row_caixa = 'style="display:none;"';
	$display_row_situacao = 'style="display:none;"';
	$display_row_natureza_financeira_receber = 'style="display:none;"';
	$display_row_natureza_financeira_pagar = 'style="display:none;"';
	$display_row_tipo = '';
	$display_row_periodo = '';
	$display_row_pessoa = '';
	$display_row_ignorar_transferencia = 'style="display:none;"';
	$display_row_origem = 'style="display:none;"';
	$display_row_ignora_movimentacao = 'style="display:none;"';
	$display_row_considerar_contratos = 'style="display:none;"';

}else if($tipo_relatorio == 5){
	$display_row_caixa = '';
	$display_row_situacao = 'style="display:none;"';
	$display_row_natureza_financeira_receber = 'style="display:none;"';
	$display_row_natureza_financeira_pagar = 'style="display:none;"';
	$display_row_tipo = 'style="display:none;"';
	$display_row_periodo = '';
	$display_row_pessoa = '';
	$display_row_ignorar_transferencia = '';
	$display_row_origem = 'style="display:none;"';
	$display_row_ignora_movimentacao = 'style="display:none;"';
	$display_row_considerar_contratos = 'style="display:none;"';

}else if($tipo_relatorio == 6){
	$display_row_caixa = 'style="display:none;"';
	$display_row_situacao = 'style="display:none;"';
	$display_row_natureza_financeira_receber = 'style="display:none;"';
	$display_row_natureza_financeira_pagar = 'style="display:none;"';
	$display_row_tipo = 'style="display:none;"';
	$display_row_periodo = '';
	$display_row_pessoa = 'style="display:none;"';
	$display_row_ignorar_transferencia = 'style="display:none;"';
	$display_row_origem = '';
	$display_row_ignora_movimentacao = 'style="display:none;"';
	$display_row_considerar_contratos = 'style="display:none;"';

}else if($tipo_relatorio == 7){
	$display_row_caixa = '';
	$display_row_situacao = 'style="display:none;"';
	$display_row_natureza_financeira_receber = 'style="display:none;"';
	$display_row_natureza_financeira_pagar = 'style="display:none;"';
	$display_row_tipo = 'style="display:none;"';
	$display_row_periodo = '';
	$display_row_pessoa = '';
	$display_row_ignorar_transferencia = 'style="display:none;"';
	$display_row_origem = 'style="display:none;"';
	$display_row_ignora_movimentacao = 'style="display:none;"';
	$display_row_considerar_contratos = '';

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
<script src="https://code.highcharts.com/7.2.1/highcharts.js"></script>
<script src="https://code.highcharts.com/7.2.1/modules/exporting.js"></script>
<script src="https://code.highcharts.com/7.2.1/modules/export-data.js"></script>

<div class="container-fluid">
	<form method="post" action="">
	    <div class="row">
	        <div class="col-md-4 col-md-offset-4">
	            <div class="panel panel-default noprint">
	                <div class="panel-heading clearfix">
	                    <h3 class="panel-title text-left pull-left" style="margin-top: 2px;">Relatórios - Controle de Contas:</h3>
	                    <div class="panel-title text-right pull-right"><button data-toggle="collapse" data-target="#accordionRelatorio" class="btn btn-xs btn-info" type="button" title="Visualizar filtros"><i id="i_collapse" class="fa fa-<?=$collapse_icon?>"></i></button></div>
	                </div>
	                <div id="accordionRelatorio" class="panel-collapse collapse <?=$collapse?>">
	                	<div class="panel-body">	 
	                		<div class="row">
                				<div class="col-md-12">
                					<div class="form-group">
								        <label>*Tipo de Relatório:</label>
								        <select name="tipo_relatorio" id="tipo_relatorio" class="form-control input-sm">
								        	<option value="4" <?php if($tipo_relatorio == '4'){ echo 'selected';}?>>Contas Baixadas</option>
								        	<option value="2" <?php if($tipo_relatorio == '2'){ echo 'selected';}?>>Contas a Pagar</option>
								        	<option value="1" <?php if($tipo_relatorio == '1'){ echo 'selected';}?>>Contas a Receber</option>
								        	<option value="5" <?php if($tipo_relatorio == '5'){ echo 'selected';}?>>Gráfico de Movimentações de Caixa</option>
								        	<option value="3" <?php if($tipo_relatorio == '3'){ echo 'selected';}?>>Movimentações de Caixa</option>
								        	<option value="6" <?php if($tipo_relatorio == '6'){ echo 'selected';}?>>Movimentações de Caixa - Agrupado por Mês</option>
								        	<option value="7" <?php if($tipo_relatorio == '7'){ echo 'selected';}?>>Movimentações de Caixa (Contas a Pagar e Receber)</option>
								        </select>
								    </div>
                				</div>
                			</div>       							
							<div class="row" id="row_periodo" <?=$display_row_periodo?>>
								<div class="col-md-6">
									<div class="form-group" >
								        <label>*Data Inicial:</label>
								        <input type="text" class="form-control input-sm date calendar" name="data_de" value="<?=$data_de?>" required>
								    </div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
								        <label>*Data Final:</label>
								        <input type="text" class="form-control input-sm date calendar" name="data_ate" value="<?=$data_ate?>" required>
								    </div>
								</div>
							</div>
                			<div class="row" id="row_caixa" <?=$display_row_caixa?>>
                				<div class="col-md-12">
                					<div class="form-group">
								        <label>*Caixas:</label>
								        <select name="id_caixa" id="id_caixa" class="form-control input-sm">
											<option value="">Todas</option>
								        	<?php
											$dados_caixa = DBRead('', 'tb_caixa', "WHERE status = 1");
											if ($dados_caixa) {
												foreach ($dados_caixa as $conteudo_caixa) {
													$selected = $id_caixa == $conteudo_caixa['id_caixa'] ? "selected" : "";
													echo "<option value='".$conteudo_caixa['id_caixa']."' ".$selected.">".$conteudo_caixa['nome']."</option>";
												}
											}
											?>
								        </select>
								    </div>
                				</div>
							</div>
							
							<div class="row" id="row_ignora_movimentacao" <?=$display_row_ignora_movimentacao?>>
                				<div class="col-md-12">
                					<div class="form-group">
										<label>*Incluir Caixas Que Não Aceitam Transações:</label>
								        <select name="ignora_movimentacao" id="ignora_movimentacao" class="form-control input-sm">
											<option value="1" <?php if($ignora_movimentacao == '1'){ echo 'selected';}?>>Sim</option>
								        	<option value="" <?php if($ignora_movimentacao == ''){ echo 'selected';}?>>Não</option>
								        </select>
								    </div>
                				</div>
							</div>

                			<div class="row" id="row_situacao" <?=$display_row_situacao?>>
                				<div class="col-md-12">
                					<div class="form-group">
								        <label>*Situação:</label>
								        <select name="situacao" id="situacao" class="form-control input-sm">
								        	<option value="" <?php if(!$situacao){ echo 'selected';}?>>Todas</option>
								        	<option value="aberta" <?php if($situacao == 'aberta'){ echo 'selected';}?>>Aberta</option>
								        	<option value="quitada" <?php if($situacao == 'quitada'){ echo 'selected';}?>>Quitada</option>
								        </select>
								    </div>
                				</div>
                			</div>
                			<div class="row" id="row_natureza_financeira_receber" <?=$display_row_natureza_financeira_receber?>>
                				<div class="col-md-12">
                					<div class="form-group">
								        <label>*Natureza Financeira:</label>
								        <select name="id_natureza_financeira_receber" id="id_natureza_financeira_receber" class="form-control input-sm">
											<option value="">Todas</option>
								        	<?php
											$dados_natureza_financeira = DBRead('', 'tb_natureza_financeira', "WHERE tipo = 'conta_receber' ORDER BY nome");
											if ($dados_natureza_financeira) {
												foreach ($dados_natureza_financeira as $conteudo_natureza_financeira) {
													$selected = $id_natureza_financeira_receber == $conteudo_natureza_financeira['id_natureza_financeira'] ? "selected" : "";
													echo "<option value='".$conteudo_natureza_financeira['id_natureza_financeira']."' ". $selected.">".$conteudo_natureza_financeira['nome']."</option>";
												}
											}
											?>
								        </select>
								    </div>
                				</div>
                			</div>
                			<div class="row" id="row_natureza_financeira_pagar" <?=$display_row_natureza_financeira_pagar?>>
                				<div class="col-md-12">
                					<div class="form-group">
								        <label>*Natureza Financeira:</label>
								        <select name="id_natureza_financeira_pagar" id="id_natureza_financeira_pagar" class="form-control input-sm">
											<option value="">Todas</option>
								        	<?php
											$dados_natureza_financeira = DBRead('', 'tb_natureza_financeira', "WHERE tipo = 'conta_pagar' ORDER BY nome");
											if ($dados_natureza_financeira) {
												foreach ($dados_natureza_financeira as $conteudo_natureza_financeira) {
													$selected = $id_natureza_financeira_pagar == $conteudo_natureza_financeira['id_natureza_financeira'] ? "selected" : "";
													echo "<option value='".$conteudo_natureza_financeira['id_natureza_financeira']."' ".$selected.">".$conteudo_natureza_financeira['nome']."</option>";
												}
											}
											?>
								        </select>
								    </div>
                				</div>
                			</div>
                			<div class="row" id="row_tipo" <?=$display_row_tipo?>>
                				<div class="col-md-12">
                					<div class="form-group">
								        <label>*Tipo:</label>
								        <select name="tipo" id="tipo" class="form-control input-sm">
								        	<option value="" <?php if(!$tipo){ echo 'selected';}?>>Todos</option>
								        	<option value="conta_receber" <?php if($tipo == 'conta_receber'){ echo 'selected';}?>>Contas a Receber</option>
								        	<option value="conta_pagar" <?php if($tipo == 'conta_pagar'){ echo 'selected';}?>>Contas a Pagar</option>
								        </select>
								    </div>
                				</div>
                			</div>
                			<div class="row" id="row_pessoa" <?=$display_row_pessoa?>>
	                            <div class="col-md-12">
	                                <div class="form-group">
	                                    <label>Pessoa:</label>
	                                    <div class="input-group">
	                                        <input class="form-control input-sm" id="busca_pessoa" type="text" name="busca_pessoa"  value="<?=$nome_pessoa;?>" placeholder="Informe o nome ou CPF/CNPJ..." autocomplete="off" readonly>
	                                        <div class="input-group-btn">
	                                            <button class="btn btn-info btn-sm" id="habilita_busca_pessoa" name="habilita_busca_pessoa" type="button" title="Clique para selecionar a pessoa" style="height: 30px;"><i class="fa fa-search"></i></button>
	                                        </div>
	                                    </div>
	                                    <input type="hidden" name="id_pessoa" id="id_pessoa" value="<?= $id_pessoa ?>">
	                                </div>
	                            </div>
	                        </div>

	                        <div class="row" id="row_ignorar_transferencia" <?=$display_row_ignorar_transferencia?>>
                				<div class="col-md-12">
                					<div class="form-group">
								        <label>*Ignorar Transferências:</label>
								        <select name="ignorar_transferencia" id="ignorar_transferencia" class="form-control input-sm">
								        	<option value="0" <?php if($ignorar_transferencia == '0'){ echo 'selected';}?>>Sim</option>
								        	<option value="1" <?php if($ignorar_transferencia == '1'){ echo 'selected';}?>>Não</option>
								        </select>
								    </div>
                				</div>
                			</div>

                			<div class="row" id="row_origem" <?=$display_row_origem?>>
                				<div class="col-md-12">
                					<div class="form-group">
								        <label>*Origem:</label>
								        <select name="origem" id="origem" class="form-control input-sm">
								        	<option value="conta_receber" <?php if($origem == 'conta_receber'){ echo 'selected';}?>>Contas a Receber</option>
								        	<option value="conta_pagar" <?php if($origem == 'conta_pagar'){ echo 'selected';}?>>Contas a Pagar</option>
								        </select>
								    </div>
                				</div>
							</div>

							<div class="row" id="row_considerar_contratos" <?=$display_row_considerar_contratos?>>
                				<div class="col-md-12">
                					<div class="form-group">
								        <label>*Considerar Contratos:</label>
								        <select name="considerar_contratos" id="considerar_contratos" class="form-control input-sm">
								        	<option value="1" <?php if($considerar_contratos == '1'){ echo 'selected';}?>>Sim</option>
								        	<option value="2" <?php if($considerar_contratos == '2'){ echo 'selected';}?>>Não</option>
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
				relatorio_receber($data_de, $data_ate, $id_caixa, $situacao, $id_natureza_financeira_receber, $id_pessoa, $ignora_movimentacao);	
			}else if($tipo_relatorio == 2){
				relatorio_pagar($data_de, $data_ate, $id_caixa, $situacao, $id_natureza_financeira_pagar, $id_pessoa, $ignora_movimentacao);
			}else if($tipo_relatorio == 3){
				relatorio_movimentacoes($data_de, $data_ate, $id_caixa, $id_pessoa);			
			}else if($tipo_relatorio == 4){
				relatorio_baixa($data_de, $data_ate, $tipo, $id_pessoa);			
			}else if($tipo_relatorio == 5){
				relatorio_movimentacoes_grafico($data_de, $data_ate, $id_caixa, $id_pessoa, $ignorar_transferencia);			
			}else if($tipo_relatorio == 6){
				relatorio_movimentacoes_agrupado_por_mes($data_de, $data_ate, $origem);			
			}else if($tipo_relatorio == 7){
				relatorio_movimentacoes_pagar_receber($data_de, $data_ate, $id_caixa, $id_pessoa, $considerar_contratos);			
			}

			
		}
		?>
	</div>
</div>

<script>

	//Busca Pessoa

	    // Atribui evento e função para limpeza dos campos
	    $('#busca_pessoa').on('input', limpaCamposPessoa);

	    // Dispara o Autocomplete da pessoa a partir do segundo caracter
	    $("#busca_pessoa").autocomplete({
	        minLength: 2,
	        source: function(request, response){
	            $.ajax({
	                url: "/api/ajax?class=PessoaAutocomplete.php",
	                dataType: "json",
	                data: {
	                    acao: 'autocomplete',
	                    parametros: {
	                        'nome' : $('#busca_pessoa').val(),
	                        'atributo' : 'cliente_fornecedor_funcionario'
	                    },
						token: '<?= $request->token ?>'
	                },
	                success: function(data){
	                    response(data);
	                }
	            });
	        },
	        focus: function(event, ui){
	            $("#busca_pessoa").val(ui.item.nome);
	            carregarDadosPessoa(ui.item.id_pessoa);
	            return false;
	        },
	        select: function(event, ui){
	            $("#busca_pessoa").val(ui.item.nome);
	            $('#busca_pessoa').attr("readonly", true);
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

    //Busca Pessoa
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
			$('#row_caixa').show();
			$('#row_situacao').show();
			$('#row_natureza_financeira_receber').show();
			$('#row_natureza_financeira_pagar').hide();
			$('#row_tipo').hide();
			$('#row_periodo').show();
			$('#row_pessoa').show();
			$('#row_ignorar_transferencia').hide();
			$('#row_origem').hide();
			$('#row_ignora_movimentacao').show();
			$('#row_considerar_contratos').hide();

		}else if(tipo_relatorio == 2){
			$('#row_caixa').show();
			$('#row_situacao').show();
			$('#row_natureza_financeira_receber').hide();
			$('#row_natureza_financeira_pagar').show();
			$('#row_tipo').hide();
			$('#row_periodo').show();
			$('#row_pessoa').show();
			$('#row_ignorar_transferencia').hide();
			$('#row_origem').hide();
			$('#row_ignora_movimentacao').show();
			$('#row_considerar_contratos').hide();

		}else if(tipo_relatorio == 3){
			$('#row_caixa').show();
			$('#row_situacao').hide();
			$('#row_natureza_financeira_receber').hide();
			$('#row_natureza_financeira_pagar').hide();
			$('#row_tipo').hide();
			$('#row_periodo').show();
			$('#row_pessoa').show();
			$('#row_ignorar_transferencia').hide();
			$('#row_origem').hide();
			$('#row_ignora_movimentacao').hide();
			$('#row_considerar_contratos').hide();

		}else if(tipo_relatorio == 4){
			$('#row_caixa').hide();
			$('#row_situacao').hide();
			$('#row_natureza_financeira_receber').hide();
			$('#row_natureza_financeira_pagar').hide();
			$('#row_tipo').show();
			$('#row_periodo').show();
			$('#row_pessoa').show();
			$('#row_ignorar_transferencia').hide();
			$('#row_origem').hide();
			$('#row_ignora_movimentacao').hide();
			$('#row_considerar_contratos').hide();
			
		}else if(tipo_relatorio == 5){
			$('#row_caixa').show();
			$('#row_situacao').hide();
			$('#row_natureza_financeira_receber').hide();
			$('#row_natureza_financeira_pagar').hide();
			$('#row_tipo').hide();
			$('#row_periodo').show();
			$('#row_pessoa').show();
			$('#row_ignorar_transferencia').show();
			$('#row_origem').hide();
			$('#row_ignora_movimentacao').hide();
			$('#row_considerar_contratos').hide();

		}else if(tipo_relatorio == 6){
			$('#row_caixa').hide();
			$('#row_situacao').hide();
			$('#row_natureza_financeira_receber').hide();
			$('#row_natureza_financeira_pagar').hide();
			$('#row_tipo').hide();
			$('#row_periodo').show();
			$('#row_pessoa').hide();
			$('#row_ignorar_transferencia').hide();
			$('#row_origem').show();
			$('#row_ignora_movimentacao').hide();
			$('#row_considerar_contratos').hide();

		}else if(tipo_relatorio == 7){
			$('#row_caixa').show();
			$('#row_situacao').hide();
			$('#row_natureza_financeira_receber').hide();
			$('#row_natureza_financeira_pagar').hide();
			$('#row_tipo').hide();
			$('#row_periodo').show();
			$('#row_pessoa').show();
			$('#row_ignorar_transferencia').hide();
			$('#row_origem').hide();
			$('#row_ignora_movimentacao').hide();
			$('#row_considerar_contratos').show();

		}
	}); 

	$('#incluir_atrasadas').on('change',function(){
		if($('#incluir_atrasadas').val() == 1){
			$('#row_dias_descarte').show();
		}else{
            $('#row_dias_descarte').hide();
            $('#incluir_atrasadas').val(0);
		}
	}); 
</script>

<?php 

function relatorio_movimentacoes_pagar_receber($data_de, $data_ate, $id_caixa, $id_pessoa, $considerar_contratos){

	$data_hoje = converteDataHora(getDataHora());

	if($id_caixa){
		$filtro_caixa = "AND a.id_caixa = '".$id_caixa."' ";
		$dados_caixa = DBRead('','tb_caixa',"WHERE id_caixa = '".$id_caixa."' ", "nome AS nome_caixa");
		$nome_caixa = $dados_caixa[0]['nome_caixa'];
	}else{
		$filtro_caixa = "AND (a.id_caixa = '1' OR a.id_caixa = '3' OR a.id_caixa = '4' ) ";

		$nome_caixa = "Todos";
	}

	if($id_pessoa){
		$dados_pessoa = DBRead('', 'tb_pessoa', "WHERE id_pessoa = '".$id_pessoa."' ");
		$nome_pessoa = $dados_pessoa[0]['nome'];
		$legenda_pessoa = ", <strong> Pessoa - </strong>".$nome_pessoa;
		$filtro_pessoa = "AND a.id_pessoa = '".$id_pessoa."' ";
	}

	if($considerar_contratos == 1){
		$legenda_considerar_contratos = "Sim";
	}else{
		$legenda_considerar_contratos = "Não";
	}

	$periodo_amostra = "<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> de $data_de até $data_ate</span>";
	$gerado = "<span style=\"font-size: 14px;\"><strong>Gerado em: </strong> $data_hoje</span>";

	echo "<div class=\"col-md-12\" style=\"padding: 0\">";
	echo "<legend style=\"text-align:center;\"><strong>Relatório - Movimentações de Caixa (Contas a Pagar e Receber)</strong><br>$gerado</legend>";
    echo "<legend style=\"text-align:center;\">$periodo_amostra</legend>";
    echo "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong> Caixa - </strong>".$nome_caixa."<strong>, Considerar Contratos - </strong>".$legenda_considerar_contratos."".$legenda_pessoa."</legend>";

	

	$dados_conta_receber = DBRead('','tb_conta_receber a',"INNER JOIN tb_natureza_financeira b ON a.id_natureza_financeira = b.id_natureza_financeira INNER JOIN tb_natureza_financeira_agrupador c ON b.id_natureza_financeira_agrupador = c.id_natureza_financeira_agrupador INNER JOIN tb_pessoa d ON a.id_pessoa = d.id_pessoa INNER JOIN tb_caixa e ON a.id_caixa = e.id_caixa WHERE ( (a.data_vencimento >= '".converteDataHora($data_de,'data')."' AND a.data_vencimento <= '".converteDataHora($data_ate,'data')."') OR (a.data_pagamento >= '".converteDataHora($data_de,'data')."' AND a.data_pagamento <= '".converteDataHora($data_ate,'data')."') ) AND (a.situacao = 'aberta' OR a.situacao = 'quitada') ".$filtro_caixa." ".$filtro_pessoa." ", " a.*, b.nome AS nome_natureza, b.tipo AS tipo_movimentacao, c.nome AS nome_agrupador, c.id_natureza_financeira_agrupador, d.nome AS nome_pessoa, d.razao_social, e.nome AS nome_caixa");

	// $dados_conta_pagar = DBRead('','tb_conta_pagar a',"INNER JOIN tb_natureza_financeira b ON a.id_natureza_financeira = b.id_natureza_financeira INNER JOIN tb_natureza_financeira_agrupador c ON b.id_natureza_financeira_agrupador = c.id_natureza_financeira_agrupador INNER JOIN tb_pessoa d ON a.id_pessoa = d.id_pessoa INNER JOIN tb_caixa e ON a.id_caixa = e.id_caixa WHERE ( (a.data_vencimento >= '".converteDataHora($data_de,'data')."' AND a.data_vencimento <= '".converteDataHora($data_ate,'data')."')	OR (a.data_pagamento >= '".converteDataHora($data_de,'data')."' AND a.data_pagamento <= '".converteDataHora($data_ate,'data')."') ) AND (a.situacao = 'aberta' OR a.situacao = 'quitada') AND a.id_natureza_financeira != '87' ".$filtro_caixa." ".$filtro_pessoa." ", " a.*, b.nome AS nome_natureza, b.tipo AS tipo_movimentacao, c.nome AS nome_agrupador, c.id_natureza_financeira_agrupador, d.nome AS nome_pessoa, d.razao_social, e.nome AS nome_caixa");
	$dados_conta_pagar = DBRead('','tb_conta_pagar a',"INNER JOIN tb_natureza_financeira b ON a.id_natureza_financeira = b.id_natureza_financeira INNER JOIN tb_natureza_financeira_agrupador c ON b.id_natureza_financeira_agrupador = c.id_natureza_financeira_agrupador INNER JOIN tb_pessoa d ON a.id_pessoa = d.id_pessoa INNER JOIN tb_caixa e ON a.id_caixa = e.id_caixa WHERE ( (a.data_vencimento >= '".converteDataHora($data_de,'data')."' AND a.data_vencimento <= '".converteDataHora($data_ate,'data')."')	OR (a.data_pagamento >= '".converteDataHora($data_de,'data')."' AND a.data_pagamento <= '".converteDataHora($data_ate,'data')."') ) AND (a.situacao = 'aberta' OR a.situacao = 'quitada') ".$filtro_caixa." ".$filtro_pessoa." ", " a.*, b.nome AS nome_natureza, b.tipo AS tipo_movimentacao, c.nome AS nome_agrupador, c.id_natureza_financeira_agrupador, d.nome AS nome_pessoa, d.razao_social, e.nome AS nome_caixa");

	// $dados_conta_pagar_lucro_distribuido = DBRead('','tb_conta_pagar a',"INNER JOIN tb_natureza_financeira b ON a.id_natureza_financeira = b.id_natureza_financeira INNER JOIN tb_natureza_financeira_agrupador c ON b.id_natureza_financeira_agrupador = c.id_natureza_financeira_agrupador INNER JOIN tb_pessoa d ON a.id_pessoa = d.id_pessoa INNER JOIN tb_caixa e ON a.id_caixa = e.id_caixa WHERE ( (a.data_vencimento >= '".converteDataHora($data_de,'data')."' AND a.data_vencimento <= '".converteDataHora($data_ate,'data')."')	OR (a.data_pagamento >= '".converteDataHora($data_de,'data')."' AND a.data_pagamento <= '".converteDataHora($data_ate,'data')."') ) AND (a.situacao = 'aberta' OR a.situacao = 'quitada') AND a.id_natureza_financeira = '87' ".$filtro_caixa." ".$filtro_pessoa." ", " a.*, b.nome AS nome_natureza, b.tipo AS tipo_movimentacao, c.nome AS nome_agrupador, c.id_natureza_financeira_agrupador, d.nome AS nome_pessoa, d.razao_social, e.nome AS nome_caixa");

	$cont = 0;
	$dados_movimentacoes = array();

	if($considerar_contratos == 1){
		$referencia_data_hoje = new DateTime(getDataHora());
		$referencia_data_hoje->modify('last day of this month');
		$referencia_data_hoje_final = $referencia_data_hoje->format('Y-m-d');
		$referencia_data_hoje->modify('first day of last month');
		$referencia_data_hoje_faturamento = $referencia_data_hoje->format('Y-m-d');

		$dados_verifica_faturamento = DBRead('', 'tb_faturamento', "WHERE adesao = '0' AND data_referencia = '".$referencia_data_hoje_faturamento."' ");

		$data_aux = '';
		foreach(rangeDatas(converteData($data_de), converteData($data_ate)) as $data){
			$referencia_data_range = new DateTime($data);
			$referencia_data_range->modify('first day of this month');
			$referencia_data_range->modify('-1 month');		
			$referencia_data_faturamento = $referencia_data_range->format('Y-m-d');

			if($data_aux != $referencia_data_faturamento){
				$data_aux = $referencia_data_faturamento;

				if($data > $referencia_data_hoje_final || ($data > $referencia_data_hoje_faturamento && !$dados_verifica_faturamento)){
					
					$referencia_data_range->modify('last day of this month');
					// $dados_contrato_futuro = DBRead('', 'tb_contrato_plano_pessoa a', "INNER JOIN tb_pessoa b ON a.id_pessoa= b.id_pessoa INNER JOIN tb_plano c ON a.id_plano = c.id_plano WHERE a.data_final_cobranca >= '".$referencia_data_range->format('Y-m-d')."' AND (a.status = 1 OR a.status = 5 OR a.status = 7) AND c.cod_servico != 'gestao_redes' AND a.contrato_pai = '0' AND b.id_pessoa != '2006' ", "b.nome AS nome_pessoa, b.razao_social, c.cod_servico, a.dia_pagamento, a.valor_total");$referencia_data_range->modify('last day of this month');
					$dados_contrato_futuro = DBRead('', 'tb_contrato_plano_pessoa a', "INNER JOIN tb_pessoa b ON a.id_pessoa= b.id_pessoa INNER JOIN tb_plano c ON a.id_plano = c.id_plano WHERE a.data_final_cobranca >= '".$referencia_data_range->format('Y-m-d')."' AND a.status = 1 and a.realiza_cobranca = 1 AND c.cod_servico != 'gestao_redes' AND b.id_pessoa != '2006' ", "a.id_contrato_plano_pessoa, b.nome AS nome_pessoa, b.razao_social, c.cod_servico, a.dia_pagamento, a.valor_total, a.valor_inicial, a.tipo_cobranca");
					
					$referencia_data_range->modify('first day of this month');
					$referencia_data_range->modify('+1 month');
					$dados_nome_caixa = DBRead('','tb_caixa',"WHERE id_caixa = '1' ", "nome");

					foreach ($dados_contrato_futuro as $conteudo_contrato_futuro) {
						$dados_movimentacoes[$cont]['nome_pessoa'] = $conteudo_contrato_futuro['nome_pessoa'];
						$dados_movimentacoes[$cont]['razao_social'] = $conteudo_contrato_futuro['razao_social'];
						if($conteudo_contrato_futuro['cod_servico'] == 'call_suporte'){
							$dados_agrupador = DBRead('', 'tb_natureza_financeira a', "INNER JOIN tb_natureza_financeira_agrupador b ON a.id_natureza_financeira_agrupador = b.id_natureza_financeira_agrupador WHERE id_natureza_financeira = 1", "a.nome AS nome_natureza, b.nome AS nome_agrupador");
							$dados_movimentacoes[$cont]['nome_agrupador'] = $dados_agrupador[0]['nome_agrupador'];
							$dados_movimentacoes[$cont]['nome_natureza'] = $dados_agrupador[0]['nome_natureza'];
						}else if($conteudo_contrato_futuro['cod_servico'] == 'call_ativo'){
							$dados_agrupador = DBRead('', 'tb_natureza_financeira a', "INNER JOIN tb_natureza_financeira_agrupador b ON a.id_natureza_financeira_agrupador = b.id_natureza_financeira_agrupador WHERE id_natureza_financeira = 2", "a.nome AS nome_natureza, b.nome AS nome_agrupador");
							$dados_movimentacoes[$cont]['nome_agrupador'] = $dados_agrupador[0]['nome_agrupador'];
							$dados_movimentacoes[$cont]['nome_natureza'] = $dados_agrupador[0]['nome_natureza'];
						}else if($conteudo_contrato_futuro['cod_servico'] == 'call_monitoramento'){
							$dados_agrupador = DBRead('', 'tb_natureza_financeira a', "INNER JOIN tb_natureza_financeira_agrupador b ON a.id_natureza_financeira_agrupador = b.id_natureza_financeira_agrupador WHERE id_natureza_financeira = 3", "a.nome AS nome_natureza, b.nome AS nome_agrupador");
							$dados_movimentacoes[$cont]['nome_agrupador'] = $dados_agrupador[0]['nome_agrupador'];
							$dados_movimentacoes[$cont]['nome_natureza'] = $dados_agrupador[0]['nome_natureza'];
						}
							
			
						$dados_movimentacoes[$cont]['tipo_movimentacao'] = 'Entrada';
						$dados_movimentacoes[$cont]['origem_movimentacao'] = 'Conta Receber';

						$dados_movimentacoes[$cont]['data_movimentacao'] = '';

						if($conteudo_contrato_futuro['tipo_cobranca'] == 'unitario'){
							$dados_movimentacoes[$cont]['valor'] = $conteudo_contrato_futuro['valor_inicial'];
                        }else{
							$dados_movimentacoes[$cont]['valor'] = $conteudo_contrato_futuro['valor_total'];
                        }    
			
						$dados_movimentacoes[$cont]['nome_caixa'] = $dados_nome_caixa[0]['nome'];
						$dados_movimentacoes[$cont]['data_emissao'] = '';
						$dados_movimentacoes[$cont]['data_vencimento'] = $referencia_data_range->format('Y-m')."-".$conteudo_contrato_futuro['dia_pagamento'];
						$dados_movimentacoes[$cont]['data_pagamento'] = '';
						$dados_movimentacoes[$cont]['situacao'] = 'Projeção';
						$dados_movimentacoes[$cont]['descricao'] = '';
						$cont++;		
					}
				}
			}
		}
	}

	if($dados_conta_receber){

		foreach ($dados_conta_receber as $conteudo_conta_receber) {
			$dados_movimentacoes[$cont]['nome_pessoa'] = $conteudo_conta_receber['nome_pessoa'];
			$dados_movimentacoes[$cont]['razao_social'] = $conteudo_conta_receber['razao_social'];

				$tipo_movimentacao = 'Entrada';
				$origem_movimentacao = 'Conta Receber';

			$dados_movimentacoes[$cont]['tipo_movimentacao'] = $tipo_movimentacao;
			$dados_movimentacoes[$cont]['origem_movimentacao'] = $origem_movimentacao;

			if($conteudo_conta_receber['id_caixa_movimentacao']){
				$dados_data_movimentacao = DBRead('','tb_caixa_movimentacao',"WHERE id_caixa_movimentacao = '".$conteudo_conta_receber['id_caixa_movimentacao']."' ", "data_movimentacao");
				$data_movimentacao = $dados_data_movimentacao[0]['data_movimentacao'];
			}else{
				$data_movimentacao = '';
			}
			$dados_movimentacoes[$cont]['data_movimentacao'] = $data_movimentacao;

			$dados_movimentacoes[$cont]['valor'] = $conteudo_conta_receber['valor'];
			$dados_movimentacoes[$cont]['nome_caixa'] = $conteudo_conta_receber['nome_caixa'];
			$dados_movimentacoes[$cont]['nome_agrupador'] = $conteudo_conta_receber['nome_agrupador'];
			$dados_movimentacoes[$cont]['nome_natureza'] = $conteudo_conta_receber['nome_natureza'];

			$dados_movimentacoes[$cont]['data_emissao'] = $conteudo_conta_receber['data_emissao'];
			$dados_movimentacoes[$cont]['data_vencimento'] = $conteudo_conta_receber['data_vencimento'];
			$dados_movimentacoes[$cont]['data_pagamento'] = $conteudo_conta_receber['data_pagamento'];
			$dados_movimentacoes[$cont]['situacao'] = $conteudo_conta_receber['situacao'];
			$dados_movimentacoes[$cont]['descricao'] = $conteudo_conta_receber['descricao'];
			$cont++;		
		}
	}

	if($dados_conta_pagar){
		foreach ($dados_conta_pagar as $conteudo_conta_pagar) {
			$dados_movimentacoes[$cont]['nome_pessoa'] = $conteudo_conta_pagar['nome_pessoa'];
			$dados_movimentacoes[$cont]['razao_social'] = $conteudo_conta_pagar['razao_social'];

				$tipo_movimentacao = 'Saída';
				$origem_movimentacao = 'Conta a Pagar';
		
			$dados_movimentacoes[$cont]['tipo_movimentacao'] = $tipo_movimentacao;
			$dados_movimentacoes[$cont]['origem_movimentacao'] = $origem_movimentacao;
	
			if($conteudo_conta_pagar['id_caixa_movimentacao']){
				$dados_data_movimentacao = DBRead('','tb_caixa_movimentacao',"WHERE id_caixa_movimentacao = '".$conteudo_conta_pagar['id_caixa_movimentacao']."' ", "data_movimentacao");
				$data_movimentacao = $dados_data_movimentacao[0]['data_movimentacao'];
			}else{
				$data_movimentacao = '';
			}
			$dados_movimentacoes[$cont]['data_movimentacao'] = $data_movimentacao;
	
			$dados_movimentacoes[$cont]['valor'] = $conteudo_conta_pagar['valor'];
			$dados_movimentacoes[$cont]['nome_caixa'] = $conteudo_conta_pagar['nome_caixa'];
			$dados_movimentacoes[$cont]['nome_agrupador'] = $conteudo_conta_pagar['nome_agrupador'];
			$dados_movimentacoes[$cont]['nome_natureza'] = $conteudo_conta_pagar['nome_natureza'];
			$dados_movimentacoes[$cont]['data_emissao'] = $conteudo_conta_pagar['data_emissao'];
			$dados_movimentacoes[$cont]['data_vencimento'] = $conteudo_conta_pagar['data_vencimento'];
			$dados_movimentacoes[$cont]['data_pagamento'] = $conteudo_conta_pagar['data_pagamento'];
			$dados_movimentacoes[$cont]['situacao'] = $conteudo_conta_pagar['situacao'];
			$dados_movimentacoes[$cont]['numero_parcela'] = $conteudo_conta_pagar['numero_parcela'];
			$dados_movimentacoes[$cont]['descricao'] = $conteudo_conta_pagar['descricao'];

			$cont++;		
		}
	}			

	if($dados_conta_pagar || $dados_conta_receber || $dados_contrato_futuro){
	
			echo '
			<table class="table table-hover dataTable" style="margin-bottom:0;">
				<thead>
					<tr style="vertical-align: middle;">
						<th>Nome</th>
						<th>Razão Social</th>
						<th>Tipo</th>
						<th>Origem</th>
						<th>Data da Movimentação</th>
						<th>Valor</th>			
						<th>Caixa</th>			
						<th>Agrupador da Natureza Fianceira</th>
						<th>Natureza Financeira</th>
						
						<th style="border-left: 1px solid #ddd;">Data de Emissão</th>
						<th>Data de Vencimento</th>
						<th>Data de Pagamento</th>
						<th>Situação</th>
						<th>Nº da Parcela</th>
						<th>Descrição</th>
						
					</tr>
				</thead>
					<tbody>
			';
		

			$array_natureza_financeira = array();
			$array_agrupador = array();
			$array_situacao_conta_pagar = array();
			$array_situacao_conta_receber = array();
			
			$soma_total_movimentacoes = 0;
			foreach($dados_movimentacoes as $conteudo_movimentacoes){

				if($conteudo_movimentacoes['tipo_movimentacao'] == 'Saída'){
					$array_situacao_conta_pagar[$conteudo_movimentacoes['tipo_movimentacao']][ucfirst($conteudo_movimentacoes['situacao'])]['valor'] += $conteudo_movimentacoes['valor'];
					$array_situacao_conta_pagar[$conteudo_movimentacoes['tipo_movimentacao']][ucfirst($conteudo_movimentacoes['situacao'])]['qtd'] += 1;
				}else{
					$array_situacao_conta_receber[$conteudo_movimentacoes['tipo_movimentacao']][ucfirst($conteudo_movimentacoes['situacao'])]['valor'] += $conteudo_movimentacoes['valor'];
					$array_situacao_conta_receber[$conteudo_movimentacoes['tipo_movimentacao']][ucfirst($conteudo_movimentacoes['situacao'])]['qtd'] += 1;
				}
				
				echo '
						<tr>
							<td style="vertical-align: middle;">'.$conteudo_movimentacoes['nome_pessoa'].'</td>
							<td style="vertical-align: middle;">'.$conteudo_movimentacoes['razao_social'].'</td>
							<td style="vertical-align: middle;">'.$conteudo_movimentacoes['tipo_movimentacao'].'</td>
							<td style="vertical-align: middle;">'.$conteudo_movimentacoes['origem_movimentacao'].'</td>
							<td data-order="'.$conteudo_movimentacoes['data_movimentacao'].'" style="vertical-align: middle;">'.converteData($conteudo_movimentacoes['data_movimentacao']).'</td>
							<td data-order-"'.$conteudo_movimentacoes['valor'].'" style="vertical-align: middle;">R$ '.converteMoeda($conteudo_movimentacoes['valor']).'</td>
							<td style="vertical-align: middle;">'.$conteudo_movimentacoes['nome_caixa'].'</td>
							<td style="vertical-align: middle;">'.$conteudo_movimentacoes['nome_agrupador'].'</td>
							<td style="vertical-align: middle;">'.$conteudo_movimentacoes['nome_natureza'].'</td>
							<td data-order="'.$conteudo_movimentacoes['data_emissao'].'" style="vertical-align: middle; border-left: 1px solid #ddd;">'.converteData($conteudo_movimentacoes['data_emissao']).'</td>
							<td data-order="'.$conteudo_movimentacoes['data_vencimento'].'" style="vertical-align: middle;">'.converteData($conteudo_movimentacoes['data_vencimento']).'</td>
							<td data-order="'.$conteudo_movimentacoes['data_pagamento'].'" style="vertical-align: middle;">'.converteData($conteudo_movimentacoes['data_pagamento']).'</td>
							<td style="vertical-align: middle;">'.ucfirst($conteudo_movimentacoes['situacao']).'</td>
							<td style="vertical-align: middle;">'.$conteudo_movimentacoes['numero_parcela'].'</td>
							<td style="vertical-align: middle;">'.$conteudo_movimentacoes['descricao'].'</td>
						</tr>
				';

				if($conteudo_movimentacoes['tipo_movimentacao'] == 'Saída'){
					$soma_total_movimentacoes -= $conteudo_movimentacoes['valor'];
				}else{
					$soma_total_movimentacoes += $conteudo_movimentacoes['valor'];
				}

				$array_natureza_financeira[$conteudo_movimentacoes['tipo_movimentacao']][$conteudo_movimentacoes['nome_natureza']]['valor'] += $conteudo_movimentacoes['valor'];
				$array_agrupador[$conteudo_movimentacoes['tipo_movimentacao']][$conteudo_movimentacoes['nome_agrupador']]['valor'] += $conteudo_movimentacoes['valor'];
				
				$array_natureza_financeira[$conteudo_movimentacoes['tipo_movimentacao']][$conteudo_movimentacoes['nome_natureza']]['qtd'] += 1;
				$array_agrupador[$conteudo_movimentacoes['tipo_movimentacao']][$conteudo_movimentacoes['nome_agrupador']]['qtd'] += 1;
			}
			echo "
					</tbody> 
					<tfoot>
						<tr>
							<th>Resultado:</th>
							<th></th>
							<th></th>
							<th></th>
							<th></th>
							<th>R$ ".converteMoeda($soma_total_movimentacoes)."</th>
							<th></th>
							<th></th>
							<th></th>
							<th></th>
							<th></th>
							<th></th>
							<th></th>
							<th></th>
							<th></th>
							<th></th>
						</tr>
					</tfoot>
				</table>
			";

			echo "<hr>";

			echo "<script>
					$(document).ready(function(){
						var table = $('.dataTable').DataTable({
							\"language\": {
								\"url\": \"//cdn.datatables.net/plug-ins/1.10.19/i18n/Portuguese-Brasil.json\"
							},
							aaSorting: [[5, 'asc']],
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
									filename: 'relatorio_financeiro_movimentacoes',
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

			//Agrupador
				echo '
					<div class="row">
						<div class="col-md-6">
							<table class="table table-hover dataTableExterno" style="margin-bottom:0;">
								<thead>
									<tr>
										<th colspan="3" class="text-center">Entradas</th>
									</tr>
									<tr>
										<th class="col-md-6">Agrupadores de Naturezas Financeiras</th>
										<th class="col-md-3">Quantidade</th>
										<th class="col-md-3">Valor</th>
									</tr>
								</thead>
								<tbody>
							';

							$soma_total_agrupador_entrada = 0;
							$qtd_total_agrupador_entrada = 0;
							foreach($array_agrupador['Entrada'] as $key => $conteudo_array_agrupador){
							
							$soma_total_agrupador_entrada += $conteudo_array_agrupador['valor'];
							$qtd_total_agrupador_entrada += $conteudo_array_agrupador['qtd'];

								echo '
									<tr>
										<td class="col-md-6">'.$key.'</td>
										<td class="col-md-3">'.$conteudo_array_agrupador['qtd'].'</td>
										<td class="col-md-3" data-order-"'.$conteudo_array_agrupador['valor'].'">R$ '.converteMoeda($conteudo_array_agrupador['valor']).'</td>
									</tr>
								';
							} 
							echo '
								</tbody> 
								<tfoot>
									<tr>
										<th>Total:</th>
										<th>'.$qtd_total_agrupador_entrada.'</th>
										<th>R$ '.converteMoeda($soma_total_agrupador_entrada).'</th>
									</tr>
								</tfoot>
							</table>
						</div>
						<div class="col-md-6">
							<table class="table table-hover dataTableExterno" style="margin-bottom:0;">
								<thead>
									<tr>
										<th colspan="3" class="text-center">Saídas</th>
									</tr>
									<tr>
										<th class="col-md-6">Agrupadores de Naturezas Financeiras</th>
										<th class="col-md-3">Quantidade</th>
										<th class="col-md-3">Valor</th>
									</tr>
								</thead>
								<tbody>
							';

							$soma_total_agrupador_saida = 0;
							$qtd_total_agrupador_saida = 0;
							foreach($array_agrupador['Saída'] as $key => $conteudo_array_agrupador){
							
							$soma_total_agrupador_saida += $conteudo_array_agrupador['valor'];
							$qtd_total_agrupador_saida += $conteudo_array_agrupador['qtd'];

								echo '
									<tr>
										<td class="col-md-6">'.$key.'</td>
										<td class="col-md-3">'.$conteudo_array_agrupador['qtd'].'</td>
										<td class="col-md-3" data-order-"'.$conteudo_array_agrupador['valor'].'">R$ '.converteMoeda($conteudo_array_agrupador['valor']).'</td>
									</tr>
								';
							} 
							echo '
								</tbody> 
								<tfoot>
									<tr>
										<th>Total:</th>
										<th>'.$qtd_total_agrupador_saida.'</th>
										<th>R$ '.converteMoeda($soma_total_agrupador_saida).'</th>
									</tr>
								</tfoot>
							</table>
						</div>
					</div>
					<hr>';
					
			//Natureza
				echo '
					<div class="row">
						<div class="col-md-6">
							<table class="table table-hover dataTableExterno" style="margin-bottom:0;">
								<thead>
									<tr>
										<th colspan="3" class="text-center">Entradas</th>
									</tr>
									<tr>
										<th class="col-md-6">Naturezas Financeiras</th>
										<th class="col-md-3">Quantidade</th>
										<th class="col-md-3">Valor</th>
									</tr>
								</thead>
								<tbody>
							';

							$soma_total_natureza_entrada = 0;
							$qtd_total_natureza_entrada = 0;
							foreach($array_natureza_financeira['Entrada'] as $key => $conteudo_array_natureza_financeira){

							$soma_total_natureza_entrada += $conteudo_array_natureza_financeira['valor'];
							$qtd_total_natureza_entrada += $conteudo_array_natureza_financeira['qtd'];
								echo '
									<tr>
										<td class="col-md-6">'.$key.'</td>
										<td class="col-md-3">'.$conteudo_array_natureza_financeira['qtd'].'</td>
										<td class="col-md-3" data-order-"'.$conteudo_array_natureza_financeira['valor'].'">R$ '.converteMoeda($conteudo_array_natureza_financeira['valor']).'</td>

									</tr>
								';
							} 
							echo '
								</tbody> 
								<tfoot>
									<tr>
										<th>Total:</th>
										<th>'.$qtd_total_natureza_entrada.'</th>
										<th>R$ '.converteMoeda($soma_total_natureza_entrada).'</th>
									</tr>
								</tfoot>
							</table>
						</div>
						<div class="col-md-6">
							<table class="table table-hover dataTableExterno" style="margin-bottom:0;">
								<thead>
									<tr>
										<th colspan="3" class="text-center">Saídas</th>
									</tr>
									<tr>
										<th class="col-md-6">Naturezas Financeiras</th>
										<th class="col-md-3">Quantidade</th>
										<th class="col-md-3">Valor</th>
										
									</tr>
								</thead>
								<tbody>
							';

							$soma_total_natureza_saida = 0;
							$qtd_total_natureza_saida = 0;
							foreach($array_natureza_financeira['Saída'] as $key => $conteudo_array_natureza_financeira){

							$soma_total_natureza_saida += $conteudo_array_natureza_financeira['valor'];
							$qtd_total_natureza_saida += $conteudo_array_natureza_financeira['qtd'];
								echo '
									<tr>
										<td class="col-md-6">'.$key.'</td>
										<td class="col-md-3">'.$conteudo_array_natureza_financeira['qtd'].'</td>
										<td class="col-md-3" data-order-"'.$conteudo_array_natureza_financeira['valor'].'">R$ '.converteMoeda($conteudo_array_natureza_financeira['valor']).'</td>
									</tr>
								';
							} 
							echo '
								</tbody> 
								<tfoot>
									<tr>
										<th>Total:</th>
										<th>'.$qtd_total_natureza_saida.'</th>
										<th>R$ '.converteMoeda($soma_total_natureza_saida).'</th>
									</tr>
								</tfoot>
							</table>
						</div>
					</div>
					<hr>';

			
			//Situacao Conta a pagar
			echo '
			<div class="row">
				<div class="col-md-12">
					<table class="table table-hover dataTableExterno" style="margin-bottom:0;">
						<thead>
							<tr>
								<th colspan="3" class="text-center">Situações de Contas a Pagar</th>
							</tr>
							<tr>
								<th class="col-md-6">Situação</th>
								<th class="col-md-3">Quantidade</th>
								<th class="col-md-3">Valor</th>
							</tr>
						</thead>
						<tbody>
					';

					$soma_total_situacao_conta_pagar = 0;
					$qtd_total_situacao_conta_pagar = 0;
					foreach($array_situacao_conta_pagar['Saída'] as $key => $conteudo_array_situacao_conta_pagar){

					$soma_total_situacao_conta_pagar += $conteudo_array_situacao_conta_pagar['valor'];
					$qtd_total_situacao_conta_pagar += $conteudo_array_situacao_conta_pagar['qtd'];
						echo '
							<tr>
								<td class="col-md-6">'.$key.'</td>
								<td class="col-md-3">'.$conteudo_array_situacao_conta_pagar['qtd'].'</td>
								<td class="col-md-3" data-order-"'.$conteudo_array_situacao_conta_pagar['valor'].'">R$ '.converteMoeda($conteudo_array_situacao_conta_pagar['valor']).'</td>

							</tr>
						';
					} 
					echo '
						</tbody> 
						<tfoot>
							<tr>
								<th>Total:</th>
								<th>'.$qtd_total_situacao_conta_pagar.'</th>
								<th>R$ '.converteMoeda($soma_total_situacao_conta_pagar).'</th>
							</tr>
						</tfoot>
					</table>
				</div>
			</div>
			<hr>';

			//Situacao Conta a receber
			echo '
			<div class="row">
				<div class="col-md-12">
					<table class="table table-hover dataTableExterno" style="margin-bottom:0;">
						<thead>
							<tr>
								<th colspan="3" class="text-center">Situações de Contas a Receber</th>
							</tr>
							<tr>
								<th class="col-md-6">Situação</th>
								<th class="col-md-3">Quantidade</th>
								<th class="col-md-3">Valor</th>
							</tr>
						</thead>
						<tbody>
					';

					$soma_total_situacao_conta_receber = 0;
					$qtd_total_situacao_conta_receber = 0;
					foreach($array_situacao_conta_receber['Entrada'] as $key => $conteudo_array_situacao_conta_receber){

					$soma_total_situacao_conta_receber += $conteudo_array_situacao_conta_receber['valor'];
					$qtd_total_situacao_conta_receber += $conteudo_array_situacao_conta_receber['qtd'];
						echo '
							<tr>
								<td class="col-md-6">'.$key.'</td>
								<td class="col-md-3">'.$conteudo_array_situacao_conta_receber['qtd'].'</td>
								<td class="col-md-3" data-order-"'.$conteudo_array_situacao_conta_receber['valor'].'">R$ '.converteMoeda($conteudo_array_situacao_conta_receber['valor']).'</td>

							</tr>
						';
					} 
					echo '
						</tbody> 
						<tfoot>
							<tr>
								<th>Total:</th>
								<th>'.$qtd_total_situacao_conta_receber.'</th>
								<th>R$ '.converteMoeda($soma_total_situacao_conta_receber).'</th>
							</tr>
						</tfoot>
					</table>
				</div>
			</div>
			<hr>';

			echo '
			<script>
				$(document).ready(function(){
					$(".dataTableExterno").DataTable({
						"language": {
							"url": "//cdn.datatables.net/plug-ins/1.10.19/i18n/Portuguese-Brasil.json"
						},
						"searching": false,
						"paging":   false,
						"info":     false
					});
				});
			</script>';
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

function relatorio_movimentacoes_agrupado_por_mes($data_de, $data_ate, $origem){

	$meses = array(
        "01" => "Jan",
        "02" => "Fev",
        "03" => "Mar",
        "04" => "Abr",
        "05" => "Mai",
        "06" => "Jun",
        "07" => "Jul",
        "08" => "Ago",
        "09" => "Set",
        "10" => "Out",
        "11" => "Nov",
        "12" => "Dez",
    );

	if($origem == 'conta_pagar'){
		$legenda_origem = "Contas a Pagar";
	}else{
		$legenda_origem = "Contas a Receber";
	}

	$data_hoje = converteDataHora(getDataHora());

	$periodo_amostra = "<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> de $data_de até $data_ate</span>";
	$gerado = "<span style=\"font-size: 14px;\"><strong>Gerado em: </strong> $data_hoje</span>";

	echo "<div class=\"col-md-12\" style=\"padding: 0\">";
	echo "<legend style=\"text-align:center;\"><strong>Relatório - Movimentações de Caixa - Agrupado por Mês</strong><br>$gerado</legend>";
    echo "<legend style=\"text-align:center;\">$periodo_amostra</legend>";
    echo "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong> Origem - </strong>".$legenda_origem."</legend>";

	$dados_natureza_financeira = DBRead('','tb_natureza_financeira a',"INNER JOIN tb_natureza_financeira_agrupador b ON a.id_natureza_financeira_agrupador = b.id_natureza_financeira_agrupador  WHERE a.tipo = '".$origem."' AND a.status = 1 AND b.tipo != 'transferencia' ORDER BY a.nome ASC", "a.nome AS nome_natureza, a.id_natureza_financeira, b.nome AS nome_agrupador");

	$array_final_natureza = array();
	$array_final_agrupador = array();
	$array_mes_ano = array();

	$data_ate_explode = explode("-", converteData($data_ate));
	$data_ate_mes_ano = $data_ate_explode[0].'-'.$data_ate_explode[1];
	$data_ate_filtro = converteData($data_ate);

	foreach(rangeDatas(converteData($data_de), converteData($data_ate)) as $data){

		$data_explode = explode("-", $data);
		$data_mes_ano = $data_explode[0].'-'.$data_explode[1];

		$data_nome_ano = $meses[$data_explode[1]].'/'.$data_explode[0];
		if (!in_array($data_nome_ano, $array_mes_ano)) { 

			if(converteData($data_de) == $data){
				$data_de = new DateTime(converteData($data_de));
			    $data_ate = new DateTime($data_mes_ano.'-01');
			    $data_ate->modify('last day of this month');

			}else if($data_ate_mes_ano == $data_mes_ano){
				$data_de = new DateTime($data_mes_ano.'-01');
			    $data_de->modify('first day of this month');
			    $data_ate = new DateTime($data_ate_filtro);

			}else{
				$data_de = new DateTime($data_mes_ano.'-01');
			    $data_de->modify('first day of this month');
			    $data_ate = new DateTime($data_mes_ano.'-01');
			    $data_ate->modify('last day of this month');
			}

            $array_mes_ano[] = $data_nome_ano;
			foreach ($dados_natureza_financeira as $conteudo_natureza_financeira) {
				$dados_movimentacoes = DBRead('','tb_caixa_movimentacao',"WHERE origem = '".$origem."' AND data_movimentacao BETWEEN '".$data_de->format('Y-m-d')."' AND '".$data_ate->format('Y-m-d')."' AND id_natureza_financeira = '".$conteudo_natureza_financeira['id_natureza_financeira']."' ", "valor");
				if($dados_movimentacoes){
					foreach ($dados_movimentacoes as $conteudo_movimentacoes) {
						$array_final_natureza[$data_nome_ano][$conteudo_natureza_financeira['nome_natureza']] += $conteudo_movimentacoes['valor'];
						$array_final_agrupador[$data_nome_ano][$conteudo_natureza_financeira['nome_agrupador']] += $conteudo_movimentacoes['valor'];
					}
				}
			}
        }
	}

	$tamanho_colspan = sizeof($array_mes_ano)+1;

	$dados_agrupador = DBRead('','tb_natureza_financeira_agrupador a',"INNER JOIN tb_natureza_financeira b ON a.id_natureza_financeira_agrupador = b.id_natureza_financeira_agrupador WHERE b.tipo = '".$origem."' AND b.status = 1 AND b.tipo != 'transferencia' GROUP BY a.nome ORDER BY a.nome ASC", "a.nome AS nome_agrupador");

	echo '
		<table class="table table-hover dataTable" style="margin-bottom:0;">
	      	<thead>
		      	<tr>
	    			<th colspan="'.$tamanho_colspan.'" class="text-center">Agrupadores de Naturezas Financeiras</th>
	    		</tr>
		        <tr>
		            <th>Agrupadores de Naturezas Financeiras</th>';

		            foreach ($array_mes_ano as $conteudo_mes_ano) {
		            	echo '<th>'.$conteudo_mes_ano.'</th>';
		            }
		            echo'
		            
		        </tr>
	      	</thead>
	      	<tbody>';    
	      $soma_total_mes_agrupador = array();
	      foreach ($dados_agrupador as $conteudo_agrupador) {
	      	echo "<tr>";
				echo "<td>".$conteudo_agrupador['nome_agrupador']."</td>";
				foreach ($array_mes_ano as $conteudo_mes_ano) {
					echo "<td data-order='".$array_final_agrupador[$conteudo_mes_ano][$conteudo_agrupador['nome_agrupador']]."'>R$ ".converteMoeda($array_final_agrupador[$conteudo_mes_ano][$conteudo_agrupador['nome_agrupador']], 'moeda')."</td>";
					$soma_total_mes_agrupador[$conteudo_mes_ano] += $array_final_agrupador[$conteudo_mes_ano][$conteudo_agrupador['nome_agrupador']];
	            }
	        echo "</tr>";
			}
			echo "
			</tbody>
			<tfoot>
				<tr>";
				echo '<th>Totais: </th>';
				foreach ($array_mes_ano as $conteudo_mes_ano) {

					echo '<th>R$ '.converteMoeda($soma_total_mes_agrupador[$conteudo_mes_ano], 'moeda').'</th>';

				}
				echo "
				</tr>
			</tfoot>
		</table>";

	echo "<hr>";

	echo '
		<table class="table table-hover dataTable" style="margin-bottom:0;">
	      	<thead>
		      	<tr>
        			<th colspan="'.$tamanho_colspan.'" class="text-center">Naturezas Financeiras</th>
        		</tr>
		        <tr>
		            <th>Naturezas Financeiras</th>';

		            foreach ($array_mes_ano as $conteudo_mes_ano) {
		            	echo '<th>'.$conteudo_mes_ano.'</th>';
		            }
		            echo'
		            
		        </tr>
	      	</thead>
	      	<tbody>';    

	      $soma_total_mes = array();
	      foreach ($dados_natureza_financeira as $conteudo_natureza_financeira) {

	      	echo "<tr>";
				echo "<td>".$conteudo_natureza_financeira['nome_natureza']."</td>";
				foreach ($array_mes_ano as $conteudo_mes_ano) {
					echo "<td data-order='".$array_final_natureza[$conteudo_mes_ano][$conteudo_natureza_financeira['nome_natureza']]."'>R$ ".converteMoeda($array_final_natureza[$conteudo_mes_ano][$conteudo_natureza_financeira['nome_natureza']], 'moeda')."</td>";
					$soma_total_mes[$conteudo_mes_ano] += $array_final_natureza[$conteudo_mes_ano][$conteudo_natureza_financeira['nome_natureza']];
	            }
	        echo "</tr>";

			}
		echo "
			</tbody>
			<tfoot>
				<tr>";
					echo '<th>Totais: </th>';
					foreach ($array_mes_ano as $conteudo_mes_ano) {

						echo '<th>R$ '.converteMoeda($soma_total_mes[$conteudo_mes_ano], 'moeda').'</th>';

					}
				echo '
				</tr>
			</tfoot>';
		echo "
		</table>";

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

    echo "</div>";
}

function relatorio_movimentacoes_grafico($data_de, $data_ate, $id_caixa, $id_pessoa, $ignorar_transferencia){

	$data_hoje = converteDataHora(getDataHora());

	if($id_caixa){
		$filtro_caixa = "AND id_caixa = '".$id_caixa."' ";
		$dados_caixa = DBRead('','tb_caixa',"WHERE id_caixa = '".$id_caixa."' ", "nome AS nome_caixa");
		$nome_caixa = $dados_caixa[0]['nome_caixa'];
	}else{
		$nome_caixa = "Todas";
	}

	if($id_pessoa){
		$dados_pessoa = DBRead('', 'tb_pessoa', "WHERE id_pessoa = '".$id_pessoa."' ");
		$nome_pessoa = $dados_pessoa[0]['nome'];
		$legenda_pessoa = ", <strong> Pessoa - </strong>".$nome_pessoa;
		$filtro_pessoa = "AND id_pessoa = '".$id_pessoa."' ";
	}

	if(!$ignorar_transferencia){
		$filtro_ignorar_transferencia = "AND origem != 'transferencia'";
		$legenda_ignorar_transferencia = "Sim";
	}else{
		$filtro_ignorar_transferencia = "";
		$legenda_ignorar_transferencia = "Não";
	}

	$periodo_amostra = "<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> de $data_de até $data_ate</span>";
	$gerado = "<span style=\"font-size: 14px;\"><strong>Gerado em: </strong> $data_hoje</span>";

	echo "<div class=\"col-md-12\" style=\"padding: 0\">";
	echo "<legend style=\"text-align:center;\"><strong>Relatório - Gráfico de Movimentações de Caixa</strong><br>$gerado</legend>";
    echo "<legend style=\"text-align:center;\">$periodo_amostra</legend>";
    echo "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong> Caixa - </strong>".$nome_caixa.",<strong> Ignorar Transferências - </strong>".$legenda_ignorar_transferencia."".$legenda_pessoa."</legend>";

	$array_total_entrada_mes_ano = array();
	$array_total_saida_mes_ano = array();
    $array_resultado_mes_ano = array();

	$array_mes_ano = array();
	        
    foreach(rangeDatas(converteData($data_de), converteData($data_ate)) as $data){
    	
    	$data_explode = explode("-", $data);
		$data_mes_ano = $data_explode[1].'/'.$data_explode[0];

		$dados_movimentacoes = DBRead('','tb_caixa_movimentacao',"WHERE data_movimentacao = '".$data."' ".$filtro_caixa." ".$filtro_pessoa." ".$filtro_ignorar_transferencia." ORDER BY data_movimentacao ASC ");

	    if($dados_movimentacoes){
			
	        foreach($dados_movimentacoes as $conteudo_movimentacoes){
				if (!in_array($data_mes_ano, $array_mes_ano)) { 
		            $array_mes_ano[] = $data_mes_ano;
		        }
		        if(!$array_total_saida_mes_ano[$data_mes_ano]){
	        		$array_total_saida_mes_ano[$data_mes_ano] = 0;
		        }
		        if(!$array_total_entrada_mes_ano[$data_mes_ano]){
	        		$array_total_entrada_mes_ano[$data_mes_ano] = 0;
		        }
	        	if($conteudo_movimentacoes['tipo'] == 'saida'){
	        		$tipo_movimentacao = 'Saída';
		        	$array_total_saida_mes_ano[$data_mes_ano] += $conteudo_movimentacoes['valor'];
	        	}else{
	        		$tipo_movimentacao = 'Entrada';
		        	$array_total_entrada_mes_ano[$data_mes_ano] += $conteudo_movimentacoes['valor'];
	        	}
	        }
	       
	    }else{
	    	if (!in_array($data_mes_ano, $array_mes_ano)) { 
		            $array_mes_ano[] = $data_mes_ano;
		        }
	    	if(!$array_total_saida_mes_ano[$data_mes_ano]){
        		$array_total_saida_mes_ano[$data_mes_ano] = 0;
	        }

	        if(!$array_total_entrada_mes_ano[$data_mes_ano]){
        		$array_total_entrada_mes_ano[$data_mes_ano] = 0;
	        }
	    }
	}
	foreach ($array_mes_ano as $mes_ano) {
        $array_resultado_mes_ano[$mes_ano] = $array_total_entrada_mes_ano[$mes_ano] - $array_total_saida_mes_ano[$mes_ano];
    }

	?>
    
    <div id="chart-meses"></div> 
    <script>
        $(function () {
            // Create the first chart
            $('#chart-meses').highcharts({
                chart: {
                    type: 'line'
                },
                title: {
                    text: 'Movimentações de <?=sizeof($array_mes_ano)?> meses' // Title for the chart
                },
                xAxis: {
                    categories: <?php echo json_encode(array_values($array_mes_ano)) ?>
                    // Categories for the charts
                },
                yAxis: {
                    title: {
                        text: 'Valor em R$'
                    },
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
                    shared: true,
                    formatter: function () {
                        var points = this.points;
                        var pointsLength = points.length;
                        var tooltipMarkup = pointsLength ? '<span style="font-size: 10px">' + points[0].key + '</span><br/>' : '';
                        var index;
                        var value;

                        for(index = 0; index < pointsLength; index += 1) {                            
                            value = floatMoeda((points[index].y).toFixed(2));
                            if(points[index].y < 0){
                                value = '-'+value;
                            }
                            tooltipMarkup += '<span style="color:' + points[index].series.color + '">\u25CF</span> ' + points[index].series.name + ': <b>R$ ' + value  + ' </b><br/>';
                        }

                        return tooltipMarkup;
                    }
                },
                colors: [
                    '#006400',
                    '#800000',                    
                    '#FF00FF',
                ],
                plotOptions: {
                    series: {
                        dataLabels: {
                            enabled: false
                        },
                        cursor: 'pointer',
                        point: {
                            events: {
                                click: function () {
                                    var mes_ano = this.category.split('/');
                                    mes_ano = mes_ano[0]+'-'+mes_ano[1];
                                    if($('#'+mes_ano).is(":visible")){
                                        $('#'+mes_ano).hide();
                                    }else{
                                        $('.chart-mensal').hide();
                                        $('#'+mes_ano).show();
                                        $("html, body").animate({ scrollTop: $(document).height() }, 1000);
                                    }                                   
                                }
                            }
                        }
                    }  
                },
                series: [
                    {
                        name: 'Entradas', // Name of your series
                        data: <?php echo "[".join(",", $array_total_entrada_mes_ano)."]" ?> // The data in your series

                    },
                    {
                        name: 'Saídas', // Name of your series
                        data: <?php echo "[".join(",", $array_total_saida_mes_ano)."]" ?> // The data in your series

                    },
                    {
                        name: 'Resultado', // Name of your series
                        data: <?php echo "[".join(",", $array_resultado_mes_ano)."]" ?> // The data in your series

                    },                    
                ],
                navigation: {
                    buttonOptions: {
                        enabled: true
                    }
                }
            });
        });
    </script> 
    <hr> 

    <?php
    
    echo "</div>";
}

function relatorio_receber($data_de, $data_ate, $id_caixa, $situacao, $id_natureza_financeira, $id_pessoa, $ignora_movimentacao){

	$data_hoje = converteDataHora(getDataHora());

	if($id_caixa){
		$filtro_caixa = "AND a.id_caixa = '".$id_caixa."' ";
		$dados_caixa = DBRead('','tb_caixa',"WHERE id_caixa = '".$id_caixa."' ", "nome AS nome_caixa");
		$nome_caixa = $dados_caixa[0]['nome_caixa'];
	}else{
		$nome_caixa = "Todas";
	}

	if($situacao){
		$filtro_situacao = "AND a.situacao = '".$situacao."' ";
		$nome_situacao = ucfirst($situacao);
	}else{
		$nome_situacao = "Todas";
	}

	if($id_natureza_financeira){
		$filtro_natureza_financeira = "AND a.id_natureza_financeira = '".$id_natureza_financeira."' ";
		$dados_natureza_financeira = DBRead('','tb_natureza_financeira',"WHERE id_natureza_financeira = '".$id_natureza_financeira."' ", "nome AS nome_natureza_financeira");
		$nome_natureza_financeira = $dados_natureza_financeira[0]['nome_natureza_financeira'];
	}else{
		$nome_natureza_financeira = "Todas";
	}

	if($id_pessoa){
		$dados_pessoa = DBRead('', 'tb_pessoa', "WHERE id_pessoa = '".$id_pessoa."' ");
		$nome_pessoa = $dados_pessoa[0]['nome'];
		$legenda_pessoa = ", <strong> Pessoa - </strong>".$nome_pessoa;
		$filtro_pessoa = "AND a.id_pessoa = '".$id_pessoa."' ";
	}

	if($ignora_movimentacao){
		$nome_ignora_movimentacao = "Sim";
	}else{
		$filtro_ignora_movimentacao = "AND d.aceita_movimentacao = '1' ";
		$nome_ignora_movimentacao = "Não";
	}

	$periodo_amostra = "<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> de $data_de até $data_ate</span>";
	$gerado = "<span style=\"font-size: 14px;\"><strong>Gerado em: </strong> $data_hoje</span>";

	echo "<div class=\"col-md-12\" style=\"padding: 0\">";
	echo "<legend style=\"text-align:center;\"><strong>Relatório - Contas a Receber</strong><br>$gerado</legend>";
    echo "<legend style=\"text-align:center;\">$periodo_amostra</legend>";
    echo "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong> Caixa - </strong>".$nome_caixa.", <strong> Situação - </strong>".$nome_situacao.", <strong> Natureza Financeira - </strong>".$nome_natureza_financeira."".$legenda_pessoa.", <strong> Incluir Caixas Que Não Aceitam Transações - </strong>".$nome_ignora_movimentacao."</legend>";

	$dados_conta_receber = DBRead('','tb_conta_receber a',"INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa INNER JOIN tb_natureza_financeira c ON a.id_natureza_financeira = c.id_natureza_financeira INNER JOIN tb_caixa d ON a.id_caixa = d.id_caixa WHERE a.situacao != 'baixada' AND a.data_vencimento BETWEEN '".converteData($data_de)." 00:00:00' AND '".converteData($data_ate)." 23:59:59' ".$filtro_caixa." ".$filtro_situacao." ".$filtro_natureza_financeira." ".$filtro_pessoa." ".$filtro_ignora_movimentacao." ORDER BY a.data_vencimento ASC", "a.*, b.nome AS nome_pessoa, c.nome AS nome_natureza_financeira, d.nome AS nome_caixa");

    if($dados_conta_receber){

        echo '
        <table class="table table-hover dataTable" style="margin-bottom:0;">
            <thead>
                <tr style="vertical-align: middle;">
                    <th>#</th>
                    <th>Pessoa</th>
                    <th>Natureza Financeira</th>
                    <th>Valor</th>			
                    <th>Data de Emissão</th>		
                    <th>Data de Vencimento</th>		
                    <th>Data Pagamento</th>		
                    <th>Situação</th>		
                    <th>Número da Parcela</th>		
                    <th>Descrição</th>		
                    <th>Caixa</th>		
                </tr>
            </thead>
            <tbody>
        ';
    	
    	$valor_total = 0;
        foreach($dados_conta_receber as $conteudo_conta_receber){

	    	$valor_total += $conteudo_conta_receber['valor'];
            echo '
                <tr>
                    <td style="vertical-align: middle;">'.$conteudo_conta_receber['id_conta_receber'].'</td>
                    <td style="vertical-align: middle;">'.$conteudo_conta_receber['nome_pessoa'].'</td>
                    <td style="vertical-align: middle;">'.$conteudo_conta_receber['nome_natureza_financeira'].'</td>
                    <td data-order="'.$conteudo_conta_receber['valor'].'" style="vertical-align: middle;">R$ '.converteMoeda($conteudo_conta_receber['valor']).'</td>
                    <td data-order="'.$conteudo_conta_receber['data_emissao'].'" style="vertical-align: middle;">'.converteData($conteudo_conta_receber['data_emissao']).'</td>
                    <td data-order="'.$conteudo_conta_receber['data_vencimento'].'" style="vertical-align: middle;">'.converteData($conteudo_conta_receber['data_vencimento']).'</td>
                    <td data-order="'.$conteudo_conta_receber['data_pagamento'].'" style="vertical-align: middle;">'.converteData($conteudo_conta_receber['data_pagamento']).'</td>
                    <td style="vertical-align: middle;">'.ucfirst($conteudo_conta_receber['situacao']).'</td>
                    <td style="vertical-align: middle;">'.$conteudo_conta_receber['numero_parcela'].'</td>
                    <td style="vertical-align: middle;">'.$conteudo_conta_receber['descricao'].'</td>
                    <td style="vertical-align: middle;">'.$conteudo_conta_receber['nome_caixa'].'</td>
                    
                </tr>
            ';
        }
            
        echo "
            </tbody> 
            <tfoot>
		    	<tr>
		    		<th>Total:</th>
		    		<th></th>
		    		<th></th>
		    		<th>R$ ".converteMoeda($valor_total)."</th>
		    		<th></th>
		    		<th></th>
		    		<th></th>
		    		<th></th>
		    		<th></th>
		    		<th></th>
		    		<th></th>
		    	</tr>
            </tfoot>
        </table>
        ";

        echo "<hr>";

        echo "<script>
				$(document).ready(function(){
					var table = $('.dataTable').DataTable({
						\"language\": {
							\"url\": \"//cdn.datatables.net/plug-ins/1.10.19/i18n/Portuguese-Brasil.json\"
						},
						aaSorting: [[6, 'asc']],
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
								filename: 'relatorio_financeiro_boletos_liquidacao',
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

function relatorio_pagar($data_de, $data_ate, $id_caixa, $situacao, $id_natureza_financeira, $id_pessoa, $ignora_movimentacao){

	$data_hoje = converteDataHora(getDataHora());

	if($id_caixa){
		$filtro_caixa = "AND a.id_caixa = '".$id_caixa."' ";
		$dados_caixa = DBRead('','tb_caixa',"WHERE id_caixa = '".$id_caixa."' ", "nome AS nome_caixa");
		$nome_caixa = $dados_caixa[0]['nome_caixa'];
	}else{
		$nome_caixa = "Todas";
	}

	if($situacao){
		$filtro_situacao = "AND a.situacao = '".$situacao."' ";
		$nome_situacao = ucfirst($situacao);
	}else{
		$nome_situacao = "Todas";
	}

	if($id_natureza_financeira){
		$filtro_natureza_financeira = "AND a.id_natureza_financeira = '".$id_natureza_financeira."' ";
		$dados_natureza_financeira = DBRead('','tb_natureza_financeira',"WHERE id_natureza_financeira = '".$id_natureza_financeira."' ", "nome AS nome_natureza_financeira");
		$nome_natureza_financeira = $dados_natureza_financeira[0]['nome_natureza_financeira'];
	}else{
		$nome_natureza_financeira = "Todas";
	}
 
	if($id_pessoa){
		$dados_pessoa = DBRead('', 'tb_pessoa', "WHERE id_pessoa = '".$id_pessoa."' ");
		$nome_pessoa = $dados_pessoa[0]['nome'];
		$legenda_pessoa = ", <strong> Pessoa - </strong>".$nome_pessoa;
		$filtro_pessoa = "AND a.id_pessoa = '".$id_pessoa."' ";
	}

	if($ignora_movimentacao){
		$nome_ignora_movimentacao = "Sim";
	}else{
		$filtro_ignora_movimentacao = "AND d.aceita_movimentacao = '1' ";
		$nome_ignora_movimentacao = "Não";
	}

	$periodo_amostra = "<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> de $data_de até $data_ate</span>";
	$gerado = "<span style=\"font-size: 14px;\"><strong>Gerado em: </strong> $data_hoje</span>";

	echo "<div class=\"col-md-12\" style=\"padding: 0\">";
	echo "<legend style=\"text-align:center;\"><strong>Relatório - Contas a Pagar</strong><br>$gerado</legend>";
    echo "<legend style=\"text-align:center;\">$periodo_amostra</legend>";
    echo "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong> Caixa - </strong>".$nome_caixa.", <strong> Situação - </strong>".$nome_situacao.", <strong> Natureza Financeira - </strong>".$nome_natureza_financeira."".$legenda_pessoa.", <strong>  Incluir Caixas Que Não Aceitam Transações - </strong>".$nome_ignora_movimentacao."</legend>";

	$dados_conta_pagar = DBRead('','tb_conta_pagar a',"INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa INNER JOIN tb_natureza_financeira c ON a.id_natureza_financeira = c.id_natureza_financeira INNER JOIN tb_caixa d ON a.id_caixa = d.id_caixa INNER JOIN tb_natureza_financeira_agrupador e ON c.id_natureza_financeira_agrupador = e.id_natureza_financeira_agrupador WHERE a.situacao != 'baixada' AND a.data_vencimento BETWEEN '".converteData($data_de)." 00:00:00' AND '".converteData($data_ate)." 23:59:59' ".$filtro_caixa." ".$filtro_situacao." ".$filtro_natureza_financeira." ".$filtro_pessoa." ".$filtro_ignora_movimentacao." ORDER BY a.data_vencimento ASC", "a.*, b.nome AS nome_pessoa, b.razao_social, c.nome AS nome_natureza_financeira, d.nome AS nome_caixa, e.nome AS nome_agrupador");

    if($dados_conta_pagar){

        echo '
            <table class="table table-hover dataTable" style="margin-bottom:0;">
                <thead>
                    <tr style="vertical-align: middle;">
                        <th>#</th>
                        <th>Nome</th>
                        <th>Razão Social</th>
                        <th>Agrupador da Natureza Fianceira</th>
                        <th>Natureza Financeira</th>
                        <th>Valor</th>			
                        <th>Data de Emissão</th>		
                        <th>Data de Vencimento</th>		
                        <th>Data Pagamento</th>		
                        <th>Situação</th>		
                        <th>Número da Parcela</th>
                        <th>Descrição</th>		
                        <th>Caixa</th>		
                    </tr>
                </thead>
                <tbody>
        ';
    	
    	$valor_total = 0;

		$array_natureza_financeira = array();
    	$array_agrupador = array();


        foreach($dados_conta_pagar as $conteudo_conta_pagar){

	    	$valor_total += $conteudo_conta_pagar['valor'];
            echo '
                    <tr>
                        <td style="vertical-align: middle;">'.$conteudo_conta_pagar['id_conta_pagar'].'</td>
                        <td style="vertical-align: middle;">'.$conteudo_conta_pagar['nome_pessoa'].'</td>
                        <td style="vertical-align: middle;">'.$conteudo_conta_pagar['razao_social'].'</td>
                        <td style="vertical-align: middle;">'.$conteudo_conta_pagar['nome_agrupador'].'</td>
                        <td style="vertical-align: middle;">'.$conteudo_conta_pagar['nome_natureza_financeira'].'</td>
                        <td data-order="'.$conteudo_conta_pagar['valor'].'" style="vertical-align: middle;">R$ '.converteMoeda($conteudo_conta_pagar['valor']).'</td>
                        <td data-order="'.$conteudo_conta_pagar['data_emissao'].'" style="vertical-align: middle;">'.converteData($conteudo_conta_pagar['data_emissao']).'</td>
                        <td data-order="'.$conteudo_conta_pagar['data_vencimento'].'" style="vertical-align: middle;">'.converteData($conteudo_conta_pagar['data_vencimento']).'</td>
                        <td data-order="'.$conteudo_conta_pagar['data_pagamento'].'" style="vertical-align: middle;">'.converteData($conteudo_conta_pagar['data_pagamento']).'</td>
                        <td style="vertical-align: middle;">'.ucfirst($conteudo_conta_pagar['situacao']).'</td>
                        <td style="vertical-align: middle;">'.$conteudo_conta_pagar['numero_parcela'].'</td>
                        <td style="vertical-align: middle;">'.$conteudo_conta_pagar['descricao'].'</td>
                        <td style="vertical-align: middle;">'.$conteudo_conta_pagar['nome_caixa'].'</td>
                    </tr>
            	';

				$array_natureza_financeira[$conteudo_conta_pagar['nome_natureza_financeira']]['valor'] += $conteudo_conta_pagar['valor'];
				$array_agrupador[$conteudo_conta_pagar['nome_agrupador']]['valor'] += $conteudo_conta_pagar['valor'];
				$array_natureza_financeira[$conteudo_conta_pagar['nome_natureza_financeira']]['qtd'] += 1;
				$array_agrupador[$conteudo_conta_pagar['nome_agrupador']]['qtd'] += 1;
        }
            
        echo "
            	</tbody> 
                <tfoot>
			    	<tr>
			    		<th>Total:</th>
			    		<th></th>
			    		<th></th>
			    		<th></th>
			    		<th></th>
			    		<th>R$ ".converteMoeda($valor_total)."</th>
			    		<th></th>
			    		<th></th>
			    		<th></th>
			    		<th></th>
			    		<th></th>
			    		<th></th>
			    		<th></th>
			    	</tr>
            	</tfoot>
            </table>
        ";

        echo "<hr>";

        echo "<script>
				$(document).ready(function(){
					var table = $('.dataTable').DataTable({
						\"language\": {
							\"url\": \"//cdn.datatables.net/plug-ins/1.10.19/i18n/Portuguese-Brasil.json\"
						},
						aaSorting: [[5, 'asc']],
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
								filename: 'relatorio_financeiro_boletos_liquidacao',
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

		//Agrupador
		echo '
			<div class="row">
				<div class="col-md-6">
					<table class="table table-hover dataTableExterno" style="margin-bottom:0;">
						<thead>
							<tr>
								<th colspan="3" class="text-center">Agrupadores de Naturezas Financeiras</th>
							</tr>
							<tr>
								<th class="col-md-6">Agrupadores de Naturezas Financeiras</th>
								<th class="col-md-3">Quantidade</th>
								<th class="col-md-3">Valor</th>
							</tr>
						</thead>
						<tbody>
					';

					$soma_total_agrupador_entrada = 0;
					$qtd_total_agrupador_entrada = 0;
					foreach($array_agrupador as $key => $conteudo_array_agrupador){
					
					$soma_total_agrupador_entrada += $conteudo_array_agrupador['valor'];
					$qtd_total_agrupador_entrada += $conteudo_array_agrupador['qtd'];

						echo '
							<tr>
								<td class="col-md-6">'.$key.'</td>
								<td class="col-md-3">'.$conteudo_array_agrupador['qtd'].'</td>
								<td class="col-md-3" data-order-"'.$conteudo_array_agrupador['valor'].'">R$ '.converteMoeda($conteudo_array_agrupador['valor']).'</td>
							</tr>
						';
					} 
					echo '
						</tbody> 
						<tfoot>
							<tr>
								<th>Total:</th>
								<th>'.$qtd_total_agrupador_entrada.'</th>
								<th>R$ '.converteMoeda($soma_total_agrupador_entrada).'</th>
							</tr>
						</tfoot>
					</table>
				</div>
		';


			//Natureza
        	echo '
			
				<div class="col-md-6">
					<table class="table table-hover dataTableExterno" style="margin-bottom:0;">
						<thead>
							<tr>
								<th colspan="3" class="text-center">Naturezas Financeiras</th>
							</tr>
							<tr>
								<th class="col-md-6">Naturezas Financeiras</th>
								<th class="col-md-3">Quantidade</th>
								<th class="col-md-3">Valor</th>
							</tr>
						</thead>
						<tbody>
					';

					$soma_total_natureza_entrada = 0;
					$qtd_total_natureza_entrada = 0;
					foreach($array_natureza_financeira as $key => $conteudo_array_natureza_financeira){

						$soma_total_natureza_entrada += $conteudo_array_natureza_financeira['valor'];
						$qtd_total_natureza_entrada += $conteudo_array_natureza_financeira['qtd'];
						echo '
							<tr>
								<td class="col-md-6">'.$key.'</td>
								<td class="col-md-3">'.$conteudo_array_natureza_financeira['qtd'].'</td>
								<td class="col-md-3" data-order-"'.$conteudo_array_natureza_financeira['valor'].'">R$ '.converteMoeda($conteudo_array_natureza_financeira['valor']).'</td>

							</tr>
						';
					} 
					echo '
						</tbody> 
						<tfoot>
							<tr>
								<th>Total:</th>
								<th>'.$qtd_total_natureza_entrada.'</th>
								<th>R$ '.converteMoeda($soma_total_natureza_entrada).'</th>
							</tr>
						</tfoot>
					</table>
				</div>
			
			</div>

			<hr>';

			echo '
			<script>
				$(document).ready(function(){
					$(".dataTableExterno").DataTable({
						"language": {
							"url": "//cdn.datatables.net/plug-ins/1.10.19/i18n/Portuguese-Brasil.json"
						},
						"searching": false,
						"paging":   false,
						"info":     false
					});
				});
			</script>';
		
			
		
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

function relatorio_movimentacoes($data_de, $data_ate, $id_caixa, $id_pessoa){

	$data_hoje = converteDataHora(getDataHora());

	if($id_caixa){
		$filtro_caixa = "AND a.id_caixa = '".$id_caixa."' ";
		$dados_caixa = DBRead('','tb_caixa',"WHERE id_caixa = '".$id_caixa."' ", "nome AS nome_caixa");
		$nome_caixa = $dados_caixa[0]['nome_caixa'];
	}else{
		$nome_caixa = "Todos";
	}

	if($id_pessoa){
		$dados_pessoa = DBRead('', 'tb_pessoa', "WHERE id_pessoa = '".$id_pessoa."' ");
		$nome_pessoa = $dados_pessoa[0]['nome'];
		$legenda_pessoa = ", <strong> Pessoa - </strong>".$nome_pessoa;
		$filtro_pessoa = "AND a.id_pessoa = '".$id_pessoa."' ";
	}

	$periodo_amostra = "<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> de $data_de até $data_ate</span>";
	$gerado = "<span style=\"font-size: 14px;\"><strong>Gerado em: </strong> $data_hoje</span>";

	echo "<div class=\"col-md-12\" style=\"padding: 0\">";
	echo "<legend style=\"text-align:center;\"><strong>Relatório - Movimentações de Caixa</strong><br>$gerado</legend>";
    echo "<legend style=\"text-align:center;\">$periodo_amostra</legend>";
    echo "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong> Caixa - </strong>".$nome_caixa."".$legenda_pessoa."</legend>";

	$dados_movimentacoes = DBRead('','tb_caixa_movimentacao a',"INNER JOIN tb_natureza_financeira b ON a.id_natureza_financeira = b.id_natureza_financeira INNER JOIN tb_natureza_financeira_agrupador c ON b.id_natureza_financeira_agrupador = c.id_natureza_financeira_agrupador INNER JOIN tb_pessoa d ON a.id_pessoa = d.id_pessoa INNER JOIN tb_caixa e ON a.id_caixa = e.id_caixa WHERE a.data_movimentacao BETWEEN '".converteDataHora($data_de,'data')."' AND '".converteDataHora($data_ate,'data')."' ".$filtro_caixa." ".$filtro_pessoa." ", " a.*, b.nome AS nome_natureza, c.nome AS nome_agrupador, c.id_natureza_financeira_agrupador, d.nome AS nome_pessoa, d.razao_social, e.nome AS nome_caixa");

    if($dados_movimentacoes){

        echo '
            <table class="table table-hover dataTable" style="margin-bottom:0;">
                <thead>
                    <tr style="vertical-align: middle;">
                        <th>#</th>
                        <th>Nome</th>
                        <th>Razão Social</th>
                        <th>Tipo</th>
                        <th>Origem</th>
                        <th>Data da Movimentação</th>
                        <th>Valor</th>			
                        <th>Caixa</th>			
                        <th>Agrupador da Natureza Fianceira</th>
                        <th>Natureza Financeira</th>
                        <th style="border-left: 1px solid #ddd;">Data de Emissão</th>
                        <th>Data de Vencimento</th>
                        <th>Data de Pagamento</th>
                        <th>Situação</th>
                        <th>Nº da Parcela</th>
                        <th>Descrição</th>
                    </tr>
                </thead>
                <tbody>
        ';
    	$array_natureza_financeira = array();
    	$array_agrupador = array();
    	$array_situacao_conta_pagar = array();
		$soma_total_movimentacoes = 0;

        foreach($dados_movimentacoes as $conteudo_movimentacoes){
        	if($conteudo_movimentacoes['tipo'] == 'saida'){
        		$tipo_movimentacao = 'Saída';
        	}else{
        		$tipo_movimentacao = 'Entrada';
        	}

			$data_emissao = '';
			$data_vencimento = '';
			$data_pagamento = '';
			$situacao = '';
			$numero_parcela = '';
			$descricao = '';
        	if($conteudo_movimentacoes['origem'] == 'transferencia'){
        		$origem_movimentacao = 'Transferência';
        	}else if($conteudo_movimentacoes['origem'] == 'conta_receber'){
        		$origem_movimentacao = 'Conta Receber';
        	}else{
        		$origem_movimentacao = 'Conta a Pagar';
				$dados_conta_pagar = DBRead('','tb_conta_pagar',"WHERE id_caixa_movimentacao = '".$conteudo_movimentacoes['id_caixa_movimentacao']."' ", "data_emissao, data_vencimento, data_pagamento, situacao, numero_parcela, descricao");

				$data_emissao = $dados_conta_pagar[0]['data_emissao'];
				$data_vencimento = $dados_conta_pagar[0]['data_vencimento'];
				$data_pagamento = $dados_conta_pagar[0]['data_pagamento'];
				$situacao = $dados_conta_pagar[0]['situacao'];
				$numero_parcela = $dados_conta_pagar[0]['numero_parcela'];
				$descricao = $dados_conta_pagar[0]['descricao'];
				$array_situacao_conta_pagar[$tipo_movimentacao][ucfirst($situacao)]['valor'] += $conteudo_movimentacoes['valor'];
				$array_situacao_conta_pagar[$tipo_movimentacao][ucfirst($situacao)]['qtd'] += 1;
        	}
	    	
            echo '
                    <tr>
                        <td style="vertical-align: middle;">'.$conteudo_movimentacoes['id_caixa_movimentacao'].'</td>
                        <td style="vertical-align: middle;">'.$conteudo_movimentacoes['nome_pessoa'].'</td>
                        <td style="vertical-align: middle;">'.$conteudo_movimentacoes['razao_social'].'</td>
                        <td style="vertical-align: middle;">'.$tipo_movimentacao.'</td>
                        <td style="vertical-align: middle;">'.$origem_movimentacao.'</td>
                        <td data-order="'.$conteudo_movimentacoes['data_movimentacao'].'" style="vertical-align: middle;">'.converteData($conteudo_movimentacoes['data_movimentacao']).'</td>
                        <td data-order-"'.$conteudo_movimentacoes['valor'].'" style="vertical-align: middle;">R$ '.converteMoeda($conteudo_movimentacoes['valor']).'</td>
                        <td style="vertical-align: middle;">'.$conteudo_movimentacoes['nome_caixa'].'</td>
                        <td style="vertical-align: middle;">'.$conteudo_movimentacoes['nome_agrupador'].'</td>
                        <td style="vertical-align: middle;">'.$conteudo_movimentacoes['nome_natureza'].'</td>
                        <td data-order="'.$data_emissao.'" style="vertical-align: middle; border-left: 1px solid #ddd;">'.converteData($data_emissao).'</td>
                        <td data-order="'.$data_vencimento.'" style="vertical-align: middle;">'.converteData($data_vencimento).'</td>
                        <td data-order="'.$data_pagamento.'" style="vertical-align: middle;">'.converteData($data_pagamento).'</td>
                        <td style="vertical-align: middle;">'.ucfirst($situacao).'</td>
                        <td style="vertical-align: middle;">'.$numero_parcela.'</td>
                        <td style="vertical-align: middle;">'.$descricao.'</td>
                    </tr>
            ';

            $soma_total_movimentacoes += $conteudo_movimentacoes['valor'];

        	$array_natureza_financeira[$tipo_movimentacao][$conteudo_movimentacoes['nome_natureza']]['valor'] += $conteudo_movimentacoes['valor'];
        	$array_agrupador[$tipo_movimentacao][$conteudo_movimentacoes['nome_agrupador']]['valor'] += $conteudo_movimentacoes['valor'];
        	
        	$array_natureza_financeira[$tipo_movimentacao][$conteudo_movimentacoes['nome_natureza']]['qtd'] += 1;
        	$array_agrupador[$tipo_movimentacao][$conteudo_movimentacoes['nome_agrupador']]['qtd'] += 1;

			
        }
          
        echo "
                </tbody> 
                <tfoot>
			    	<tr>
			    		<th>Total:</th>
			    		<th></th>
			    		<th></th>
			    		<th></th>
			    		<th></th>
			    		<th></th>
			    		<th>R$ ".converteMoeda($soma_total_movimentacoes)."</th>
			    		<th></th>
			    		<th></th>
			    		<th></th>
			    		<th></th>
			    		<th></th>
			    		<th></th>
			    		<th></th>
			    		<th></th>
			    		<th></th>
			    		<th></th>
			    	</tr>
                </tfoot>
            </table>
        ";

        echo "<hr>";

        echo "<script>
				$(document).ready(function(){
					var table = $('.dataTable').DataTable({
						\"language\": {
							\"url\": \"//cdn.datatables.net/plug-ins/1.10.19/i18n/Portuguese-Brasil.json\"
						},
						aaSorting: [[5, 'asc']],
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
								filename: 'relatorio_financeiro_movimentacoes',
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

        //Agrupador
        	echo '
                <div class="row">
                    <div class="col-md-6">
			            <table class="table table-hover dataTableExterno" style="margin-bottom:0;">
			                <thead>
			                	<tr>
			                		<th colspan="3" class="text-center">Entradas</th>
			                	</tr>
			                    <tr>
			                        <th class="col-md-6">Agrupadores de Naturezas Financeiras</th>
			                       	<th class="col-md-3">Quantidade</th>
			                        <th class="col-md-3">Valor</th>
			                    </tr>
			                </thead>
			                <tbody>
				        ';

				        $soma_total_agrupador_entrada = 0;
				        $qtd_total_agrupador_entrada = 0;
				        foreach($array_agrupador['Entrada'] as $key => $conteudo_array_agrupador){
				        
				        $soma_total_agrupador_entrada += $conteudo_array_agrupador['valor'];
				        $qtd_total_agrupador_entrada += $conteudo_array_agrupador['qtd'];

				            echo '
			                    <tr>
			                        <td class="col-md-6">'.$key.'</td>
			                        <td class="col-md-3">'.$conteudo_array_agrupador['qtd'].'</td>
			                        <td class="col-md-3" data-order-"'.$conteudo_array_agrupador['valor'].'">R$ '.converteMoeda($conteudo_array_agrupador['valor']).'</td>
			                    </tr>
				            ';
				        } 
				        echo '
			                </tbody> 
			                <tfoot>
						    	<tr>
						    		<th>Total:</th>
						    		<th>'.$qtd_total_agrupador_entrada.'</th>
						    		<th>R$ '.converteMoeda($soma_total_agrupador_entrada).'</th>
						    	</tr>
			                </tfoot>
			            </table>
                    </div>
                    <div class="col-md-6">
			            <table class="table table-hover dataTableExterno" style="margin-bottom:0;">
			                <thead>
			                	<tr>
			                		<th colspan="3" class="text-center">Saídas</th>
			                	</tr>
			                    <tr>
			                        <th class="col-md-6">Agrupadores de Naturezas Financeiras</th>
			                        <th class="col-md-3">Quantidade</th>
			                        <th class="col-md-3">Valor</th>
			                    </tr>
			                </thead>
			                <tbody>
				        ';

				        $soma_total_agrupador_saida = 0;
				        $qtd_total_agrupador_saida = 0;
				        foreach($array_agrupador['Saída'] as $key => $conteudo_array_agrupador){
				        
				        $soma_total_agrupador_saida += $conteudo_array_agrupador['valor'];
				        $qtd_total_agrupador_saida += $conteudo_array_agrupador['qtd'];

				            echo '
			                    <tr>
			                        <td class="col-md-6">'.$key.'</td>
			                        <td class="col-md-3">'.$conteudo_array_agrupador['qtd'].'</td>
			                        <td class="col-md-3" data-order-"'.$conteudo_array_agrupador['valor'].'">R$ '.converteMoeda($conteudo_array_agrupador['valor']).'</td>
			                    </tr>
				            ';
				        } 
				        echo '
			                </tbody> 
			                <tfoot>
						    	<tr>
						    		<th>Total:</th>
						    		<th>'.$qtd_total_agrupador_saida.'</th>
						    		<th>R$ '.converteMoeda($soma_total_agrupador_saida).'</th>
						    	</tr>
			                </tfoot>
			            </table>
                    </div>
                </div>

                <hr>';
                
        //Natureza
        	echo '
                <div class="row">
                    <div class="col-md-6">
			            <table class="table table-hover dataTableExterno" style="margin-bottom:0;">
			                <thead>
			                	<tr>
			                		<th colspan="3" class="text-center">Entradas</th>
			                	</tr>
			                    <tr>
			                        <th class="col-md-6">Naturezas Financeiras</th>
			                        <th class="col-md-3">Quantidade</th>
			                        <th class="col-md-3">Valor</th>
			                    </tr>
			                </thead>
			                <tbody>
				        ';

				        $soma_total_natureza_entrada = 0;
				        $qtd_total_natureza_entrada = 0;
				        foreach($array_natureza_financeira['Entrada'] as $key => $conteudo_array_natureza_financeira){

				        $soma_total_natureza_entrada += $conteudo_array_natureza_financeira['valor'];
				        $qtd_total_natureza_entrada += $conteudo_array_natureza_financeira['qtd'];
				            echo '
			                    <tr>
			                        <td class="col-md-6">'.$key.'</td>
			                        <td class="col-md-3">'.$conteudo_array_natureza_financeira['qtd'].'</td>
			                        <td class="col-md-3" data-order-"'.$conteudo_array_natureza_financeira['valor'].'">R$ '.converteMoeda($conteudo_array_natureza_financeira['valor']).'</td>

			                    </tr>
				            ';
				        } 
				        echo '
			                </tbody> 
			                <tfoot>
						    	<tr>
						    		<th>Total:</th>
						    		<th>'.$qtd_total_natureza_entrada.'</th>
						    		<th>R$ '.converteMoeda($soma_total_natureza_entrada).'</th>
						    	</tr>
			                </tfoot>
			            </table>
                    </div>
                    <div class="col-md-6">
			            <table class="table table-hover dataTableExterno" style="margin-bottom:0;">
			                <thead>
			                	<tr>
			                		<th colspan="3" class="text-center">Saídas</th>
			                	</tr>
			                    <tr>
			                        <th class="col-md-6">Naturezas Financeiras</th>
			                        <th class="col-md-3">Quantidade</th>
			                        <th class="col-md-3">Valor</th>
			                        
			                    </tr>
			                </thead>
			                <tbody>
				        ';

				        $soma_total_natureza_saida = 0;
				        $qtd_total_natureza_saida = 0;
				        foreach($array_natureza_financeira['Saída'] as $key => $conteudo_array_natureza_financeira){

				        $soma_total_natureza_saida += $conteudo_array_natureza_financeira['valor'];
				        $qtd_total_natureza_saida += $conteudo_array_natureza_financeira['qtd'];
				            echo '
			                    <tr>
			                        <td class="col-md-6">'.$key.'</td>
			                        <td class="col-md-3">'.$conteudo_array_natureza_financeira['qtd'].'</td>
			                        <td class="col-md-3" data-order-"'.$conteudo_array_natureza_financeira['valor'].'">R$ '.converteMoeda($conteudo_array_natureza_financeira['valor']).'</td>
			                    </tr>
				            ';
				        } 
				        echo '
			                </tbody> 
			                <tfoot>
						    	<tr>
						    		<th>Total:</th>
						    		<th>'.$qtd_total_natureza_saida.'</th>
						    		<th>R$ '.converteMoeda($soma_total_natureza_saida).'</th>
						    	</tr>
			                </tfoot>
			            </table>
                    </div>
                </div>

                <hr>';

		
		//Situacao Conta a pagar
		echo '
		<div class="row">
			<div class="col-md-12">
				<table class="table table-hover dataTableExterno" style="margin-bottom:0;">
					<thead>
						<tr>
							<th colspan="3" class="text-center">Situações de Contas a Pagar</th>
						</tr>
						<tr>
							<th class="col-md-6">Situação</th>
							<th class="col-md-3">Quantidade</th>
							<th class="col-md-3">Valor</th>
						</tr>
					</thead>
					<tbody>
				';

				$soma_total_situacao_conta_pagar = 0;
				$qtd_total_situacao_conta_pagar = 0;
				foreach($array_situacao_conta_pagar['Saída'] as $key => $conteudo_array_situacao_conta_pagar){

				$soma_total_situacao_conta_pagar += $conteudo_array_situacao_conta_pagar['valor'];
				$qtd_total_situacao_conta_pagar += $conteudo_array_situacao_conta_pagar['qtd'];
					echo '
						<tr>
							<td class="col-md-6">'.$key.'</td>
							<td class="col-md-3">'.$conteudo_array_situacao_conta_pagar['qtd'].'</td>
							<td class="col-md-3" data-order-"'.$conteudo_array_situacao_conta_pagar['valor'].'">R$ '.converteMoeda($conteudo_array_situacao_conta_pagar['valor']).'</td>

						</tr>
					';
				} 
				echo '
					</tbody> 
					<tfoot>
						<tr>
							<th>Total:</th>
							<th>'.$qtd_total_situacao_conta_pagar.'</th>
							<th>R$ '.converteMoeda($soma_total_situacao_conta_pagar).'</th>
						</tr>
					</tfoot>
				</table>
			</div>
		</div>

		<hr>';

        echo '
    	<script>
		    $(document).ready(function(){
		        $(".dataTableExterno").DataTable({
		            "language": {
		                "url": "//cdn.datatables.net/plug-ins/1.10.19/i18n/Portuguese-Brasil.json"
		            },
		            "searching": false,
		            "paging":   false,
		            "info":     false
		        });
		    });
		</script>';

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

function relatorio_baixa($data_de, $data_ate, $tipo, $id_pessoa){

	$data_hoje = converteDataHora(getDataHora());

	if($tipo){
		$filtro_tipo = "AND a.tipo = '".$tipo."' ";
		if($tipo == 'conta_receber'){
			$nome_tipo = 'Conta a Receber';
		}else{
			$nome_tipo = 'Conta a Pagar';
		}
	}else{
		$nome_tipo = "Todos";
	}

	if($id_pessoa){
		$dados_pessoa = DBRead('', 'tb_pessoa', "WHERE id_pessoa = '".$id_pessoa."' ");
		$nome_pessoa = $dados_pessoa[0]['nome'];
		$legenda_pessoa = ", <strong> Pessoa - </strong>".$nome_pessoa;
		$filtro_pessoa = "AND a.id_pessoa = '".$id_pessoa."' ";
	}

	$periodo_amostra = "<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> de $data_de até $data_ate</span>";
	$gerado = "<span style=\"font-size: 14px;\"><strong>Gerado em: </strong> $data_hoje</span>";

	echo "<div class=\"col-md-12\" style=\"padding: 0\">";
	echo "<legend style=\"text-align:center;\"><strong>Relatório - Contas Baixadas</strong><br>$gerado</legend>";
    echo "<legend style=\"text-align:center;\">$periodo_amostra</legend>";
    echo "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong> Tipo - </strong>".$nome_tipo."".$legenda_pessoa."</legend>";

	$dados_conta_baixa = DBRead('','tb_conta_baixa a',"INNER JOIN tb_usuario b ON a.id_usuario = b.id_usuario INNER JOIN tb_pessoa c ON b.id_pessoa = c.id_pessoa WHERE a.data BETWEEN '".converteData($data_de)." 00:00:00' AND '".converteData($data_ate)." 23:59:59' ".$filtro_tipo." ORDER BY a.data ASC", "a.*, c.nome AS nome_usuario");

    if($dados_conta_baixa){

        echo '
            <table class="table table-hover dataTable" style="margin-bottom:0;">
                <thead>
                    <tr style="vertical-align: middle;">
                        <th>#</th>
                        <th>Pessoa</th>
                        <th>Natureza Financeira</th>
                        <th>Valor</th>
                        <th>Tipo</th>		
                        <th>Caixa</th>		
                        <th>Usuário</th>		
                        <th>Data</th>		
                        <th>Justificativa</th>		
                    </tr>
                </thead>
                <tbody>
        ';
    	
        foreach($dados_conta_baixa as $conteudo_conta_baixa){
        	$dados_conta = '';
        	if($conteudo_conta_baixa['tipo'] == 'conta_receber'){
        		$dados_conta = DBRead('','tb_conta_receber a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa INNER JOIN tb_natureza_financeira c ON a.id_natureza_financeira = c.id_natureza_financeira INNER JOIN tb_caixa d ON a.id_caixa = d.id_caixa WHERE a.id_conta_receber = '".$conteudo_conta_baixa['id_conta']."' ".$filtro_pessoa." ", "a.*, b.nome AS nome_pessoa, c.nome AS nome_natureza_financeira, d.nome AS nome_caixa");
        		$tipo = "Conta a Receber";
        	}else{
        		$dados_conta = DBRead('','tb_conta_pagar a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa INNER JOIN tb_natureza_financeira c ON a.id_natureza_financeira = c.id_natureza_financeira INNER JOIN tb_caixa d ON a.id_caixa = d.id_caixa WHERE a.id_conta_pagar = '".$conteudo_conta_baixa['id_conta']."' ".$filtro_pessoa." ", "a.*, b.nome AS nome_pessoa, c.nome AS nome_natureza_financeira, d.nome AS nome_caixa");
        		$tipo = "Conta a Pagar";
        	}
        	if($dados_conta){
        		echo '
                    <tr>
                        <td style="vertical-align: middle;">'.$conteudo_conta_baixa['id_conta_baixa'].'</td>
                        <td style="vertical-align: middle;">'.$dados_conta[0]['nome_pessoa'].'</td>
                        <td style="vertical-align: middle;">'.$dados_conta[0]['nome_natureza_financeira'].'</td>
                        <td data-order="'.$dados_conta[0]['valor'].'" style="vertical-align: middle;">R$ '.converteMoeda($dados_conta[0]['valor']).'</td>
                        <td style="vertical-align: middle;">'.$tipo.'</td>
                        <td style="vertical-align: middle;">'.$dados_conta[0]['nome_caixa'].'</td>
                        <td style="vertical-align: middle;">'.$conteudo_conta_baixa['nome_usuario'].'</td>
                        <td data-order="'.$conteudo_conta_baixa['data'].'" style="vertical-align: middle;">'.converteDataHora($conteudo_conta_baixa['data']).'</td>
                        <td style="vertical-align: middle;">'.$conteudo_conta_baixa['justificativa'].'</td>
                    </tr>';
        	}
        }
            
        echo "
            	</tbody> 
            </table>
        ";
        echo "<hr>";

        echo "<script>
				$(document).ready(function(){
					var table = $('.dataTable').DataTable({
						\"language\": {
							\"url\": \"//cdn.datatables.net/plug-ins/1.10.19/i18n/Portuguese-Brasil.json\"
						},
						aaSorting: [[5, 'asc']],
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
								filename: 'relatorio_financeiro_boletos_liquidacao',
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
