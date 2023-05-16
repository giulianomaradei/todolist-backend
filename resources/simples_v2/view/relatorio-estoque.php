<?php
require_once(__DIR__."/../class/System.php");

$gerar = (!empty($_POST['gerar'])) ? 1 : 0;
$tipo_relatorio = (!empty($_POST['tipo_relatorio'])) ? $_POST['tipo_relatorio'] : 1;

$primeiro_dia = new DateTime(getDataHora('data'));
$primeiro_dia->modify('first day of this month');
$primeiro_dia = $primeiro_dia->format('d/m/Y');
$ultimo_dia = new DateTime(getDataHora('data'));
$ultimo_dia->modify('last day of this month');
$ultimo_dia = $ultimo_dia->format('d/m/Y');
$data_de = (!empty($_POST['data_de'])) ? $_POST['data_de'] : $primeiro_dia;
$data_ate = (!empty($_POST['data_ate'])) ? $_POST['data_ate'] : $ultimo_dia;
$tipo_movimentacao = (!empty($_POST['tipo_movimentacao'])) ? $_POST['tipo_movimentacao'] : '';
$id_usuario = (!empty($_POST['id_usuario'])) ? $_POST['id_usuario'] : '';
$id_setor = (!empty($_POST['id_setor'])) ? $_POST['id_setor'] : '';
$id_estoque_item = (!empty($_POST['id_estoque_item'])) ? $_POST['id_estoque_item'] : '';
$id_pessoa = (!empty($_POST['id_pessoa'])) ? $_POST['id_pessoa'] : '';
$tipo_entrada = (!empty($_POST['tipo_entrada'])) ? $_POST['tipo_entrada'] : '';
$id_solicitante = (!empty($_POST['id_solicitante'])) ? $_POST['id_solicitante'] : '';
$id_estoque_localizacao = (!empty($_POST['id_estoque_localizacao'])) ? $_POST['id_estoque_localizacao'] : '';

if($gerar){
	$collapse = '';
	$collapse_icon = 'plus';
	$dados = DBRead('','tb_estoque_item',"WHERE id_estoque_item = '$id_estoque_item'");
	if($dados){
		$nome_estoque_item = $dados[0]['nome'];
		$estoque_item_input = $nome_estoque_item;
	}else{
		$nome_estoque_item = '';
		$estoque_item_input ='';
	}

	$dados_pessoa = DBRead('','tb_pessoa',"WHERE id_pessoa = '$id_pessoa'");
	if($dados_pessoa){
		$nome_pessoa = $dados_pessoa[0]['nome'];
		$nome_pessoa_input = $nome_pessoa;
	}else{
		$nome_pessoa = '';
		$nome_pessoa_input ='';
	}
}else{
	$collapse = 'in';
	$collapse_icon = 'minus';
	$nome_estoque_item = '';
	$estoque_item_input ='';
	$nome_pessoa = '';
	$nome_pessoa_input ='';
}

if($tipo_relatorio == 1){
	$display_row_periodo = '';
	$display_row_tipo_movimentacao = '';
	$display_row_id_usuario = '';
	$display_row_id_setor = 'style="display:none;"';
	$display_row_fornecedor = 'style="display:none;"';
	$display_row_tipo_entrada = 'style="display:none;"';
	$display_row_id_estoque_localizacao = 'style="display:none;"';
}else if($tipo_relatorio == 2){
	$display_row_periodo = 'style="display:none;"';
	$display_row_tipo_movimentacao = 'style="display:none;"';
	$display_row_id_usuario = 'style="display:none;"';
	$display_row_id_setor = 'style="display:none;"';
	$display_row_fornecedor = 'style="display:none;"';
	$display_row_tipo_entrada = 'style="display:none;"';
	$display_row_id_estoque_localizacao = '';
}else if($tipo_relatorio == 3){
	$display_row_periodo = '';
	$display_row_tipo_movimentacao = 'style="display:none;"';
	$display_row_id_usuario = '';
	$display_row_id_setor = 'style="display:none;"';
	$display_row_fornecedor = '';
	$display_row_tipo_entrada = '';
	$display_row_id_estoque_localizacao = 'style="display:none;"';
}else if($tipo_relatorio == 4){
	$display_row_periodo = '';
	$display_row_tipo_movimentacao = 'style="display:none;"';
	$display_row_id_usuario = '';
	$display_row_id_setor = '';
	$display_row_fornecedor = 'style="display:none;"';
	$display_row_tipo_entrada = 'style="display:none;"';
	$display_row_id_estoque_localizacao = '';
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
	                    <h3 class="panel-title text-left pull-left" style="margin-top: 2px;">Relatórios - Estoque:</h3>
	                    <div class="panel-title text-right pull-right"><button data-toggle="collapse" data-target="#accordionRelatorio" class="btn btn-xs btn-info" type="button" title="Visualizar filtros"><i id="i_collapse" class="fa fa-<?=$collapse_icon?>"></i></button></div>
	                </div>
	                <div id="accordionRelatorio" class="panel-collapse collapse <?=$collapse?>">
	                	<div class="panel-body">
	                		<div class="row">
                				<div class="col-md-12">
                					<div class="form-group">
								        <label>*Tipo de Relatório:</label>
								        <select name="tipo_relatorio" id="tipo_relatorio" class="form-control input-sm">
                                        <option value="2" <?php if($tipo_relatorio == '2'){ echo 'selected';}?>>Itens do Estoque</option>
										<option value="1" <?php if($tipo_relatorio == '1'){ echo 'selected';}?>>Movimentações de Estoque</option>
										<option value="3" <?php if($tipo_relatorio == '3'){ echo 'selected';}?>>Movimentações de Estoque (Entradas)</option>
                                        <option value="4" <?php if($tipo_relatorio == '4'){ echo 'selected';}?>>Movimentações de Estoque (Saídas)</option>
								        </select>
								    </div>
                				</div>
                			</div> 
							
							<div class="row">
								<div class="col-md-12">
									<div class="form-group">
										<label>Item do Estoque:</label>
										<div class="input-group">
											<input class="form-control input-sm" id="busca_estoque_item" type="text" name="busca_estoque_item" value="<?=$estoque_item_input;?>" placeholder="Informe o nome do item..." autocomplete="off" readonly>
											<div class="input-group-btn">
												<button class="btn btn-info btn-sm" id="habilita_busca_estoque_item" name="habilita_busca_estoque_item" type="button" title="Clique para selecionar o item" style="height: 30px;"><i class="fa fa-search"></i></button>
											</div>
										</div>
										<input type="hidden" name="id_estoque_item" id="id_estoque_item" value="<?=$id_estoque_item?>">
									</div>
								</div>
							</div>

							<div class="row" id='row_fornecedor' <?=$display_row_fornecedor?>>
								<div class="col-md-12">
									<div class="form-group">
										<label>Fornecedor:</label>
											<div class="input-group">
												<input class="form-control input-sm" id="busca_pessoa" type="text" name="busca_pessoa" value="<?=$nome_pessoa_input;?>" placeholder="Informe o nome ou CPF/CNPJ..." autocomplete="off" readonly>
												<div class="input-group-btn">
													<button class="btn btn-info btn-sm" id="habilita_busca_pessoa" name="habilita_busca_pessoa" type="button" title="Clique para selecionar a pessoa" style="height: 30px;"><i class="fa fa-search"></i></button>
												</div>
											</div>
											<input type="hidden" name="id_pessoa" id="id_pessoa" value="<?=$id_pessoa?>">
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

							<div class="row" id="row_tipo_movimentacao" <?=$display_row_tipo_movimentacao?>>
								<div class="col-md-12">
									<div class="form-group" >
										<label>*Tipo de Movimentação:</label>
								        <select name="tipo_movimentacao" id="tipo_movimentacao" class="form-control input-sm">
											<option value="" <?php if($tipo_movimentacao == ''){ echo 'selected';}?>>Qualquer</option>
											<option value="entrada" <?php if($tipo_movimentacao == 'entrada'){ echo 'selected';}?>>Entrada</option>
											<option value="saida" <?php if($tipo_movimentacao == 'saida'){ echo 'selected';}?>>Saída</option>
								        </select>
								    </div>
								</div>
							</div>

							<div class="row" id="row_id_usuario" <?=$display_row_id_usuario?>>
								<div class="col-md-12">
									<div class="form-group" >
										<label>*Realizada Por:</label>
								        <select name="id_usuario" id="id_usuario" class="form-control input-sm">
										<option value="">Qualquer</option>
										<?php
										$dados_id_usuario = DBRead('', 'tb_estoque_movimentacao a', "INNER JOIN tb_usuario b ON a.id_usuario = b.id_usuario INNER JOIN tb_pessoa c ON b.id_pessoa = c.id_pessoa GROUP BY a.id_usuario ORDER BY c.nome", "a.id_usuario, c.nome");
										if ($dados_id_usuario) {
											foreach ($dados_id_usuario as $conteudo_id_usuario) {
												$selected = $id_usuario == $conteudo_id_usuario['id_usuario'] ? "selected" : "";
												echo "<option value='" . $conteudo_id_usuario['id_usuario'] . "' ".$selected.">" . $conteudo_id_usuario['nome'] . "</option>";
											}
										}
										?>
								        </select>
								    </div>
								</div>
							</div>

							<div class="row" id="row_id_setor" <?=$display_row_id_setor?>>
								<div class="col-md-12">
									<div class="form-group" >
										<label>*Setor Responsável:</label>
								        <select name="id_setor" id="id_setor" class="form-control input-sm">
										<option value="">Qualquer</option>
										<?php
										$dados_id_setor = DBRead('', 'tb_setor', "WHERE status = 1 ORDER BY descricao ASC");
										if ($dados_id_setor) {
											foreach ($dados_id_setor as $conteudo_id_setor) {
												$selected = $id_setor == $conteudo_id_setor['id_setor'] ? "selected" : "";
												echo "<option value='" . $conteudo_id_setor['id_setor'] . "' ".$selected.">" . $conteudo_id_setor['descricao'] . "</option>";
											}
										}
										?>
								        </select>
								    </div>
								</div>

								<div class="col-md-12">
									<div class="form-group" >
										<label>*Solicitante:</label>
								        <select name="id_solicitante" id="id_solicitante" class="form-control input-sm">
										<option value="">Qualquer</option>
										<?php
										$dados_id_solicitante = DBRead('', 'tb_estoque_movimentacao_item a', "INNER JOIN tb_usuario b ON a.id_solicitante = b.id_usuario INNER JOIN tb_pessoa c ON b.id_pessoa = c.id_pessoa GROUP BY a.id_solicitante ORDER BY c.nome", "a.id_solicitante, c.nome");

										if ($dados_id_solicitante) {
											foreach ($dados_id_solicitante as $conteudo_id_solicitante) {
												$selected = $id_solicitante == $conteudo_id_solicitante['id_solicitante'] ? "selected" : "";
												echo "<option value='" . $conteudo_id_solicitante['id_solicitante'] . "' ".$selected.">" . $conteudo_id_solicitante['nome'] . "</option>";
											}
										}
										?>
								        </select>
								    </div>
								</div>
							</div>

							<div class="row" id="row_id_estoque_localizacao" <?=$display_row_id_estoque_localizacao?>>
								<div class="col-md-12">
									<div class="form-group" >
										<label>*Localização:</label>
								        <select name="id_estoque_localizacao" id="id_estoque_localizacao" class="form-control input-sm">
										<option value="">Qualquer</option>
										<?php
										$dados_id_estoque_localizacao = DBRead('', 'tb_estoque_localizacao', "ORDER BY nome");
										if ($dados_id_estoque_localizacao) {
											foreach ($dados_id_estoque_localizacao as $conteudo_id_estoque_localizacao) {
												$selected = $id_estoque_localizacao == $conteudo_id_estoque_localizacao['id_estoque_localizacao'] ? "selected" : "";
												echo "<option value='" . $conteudo_id_estoque_localizacao['id_estoque_localizacao'] . "' ".$selected.">" . $conteudo_id_estoque_localizacao['nome'] . "</option>";
											}
										}
										?>
								        </select>
								    </div>
								</div>

							</div>

							<div class="row" id="row_tipo_entrada" <?=$display_row_tipo_entrada?>>
								<div class="col-md-12">
									<div class="form-group" >
										<label>*Tipo de Entrada:</label>
								        <select name="tipo_entrada" id="tipo_entrada" class="form-control input-sm">
											<option value="" <?php if($tipo_entrada == ''){ echo 'selected';}?>>Qualquer</option>
											<option value="1" <?php if($tipo_entrada == '1'){ echo 'selected';}?>>Compra</option>
											<option value="2" <?php if($tipo_entrada == '2'){ echo 'selected';}?>>Interna</option>
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
				relatorio_movimentacoes($data_de, $data_ate, $tipo_movimentacao, $id_usuario, $id_estoque_item);			
			}else if($tipo_relatorio == 2){
				relatorio_itens($id_estoque_item, $id_estoque_localizacao);			
			}else if($tipo_relatorio == 3){
				relatorio_movimentacoes_entrada($data_de, $data_ate, $id_usuario, $id_estoque_item, $id_pessoa, $tipo_entrada);			
			}else if($tipo_relatorio == 4){
				relatorio_movimentacoes_saida($data_de, $data_ate, $id_usuario, $id_setor, $id_estoque_item, $id_solicitante, $id_estoque_localizacao);			
			}
		}
		?>
	</div>
</div>

<script>

	//BUSCA ITEM	

        // Atribui evento e função para limpeza dos campos
        $('#busca_estoque_item').on('input', limpaCamposEstoque);
        
        // Dispara o Autocomplete do estoque a partir do segundo caracter
        $("#busca_estoque_item").autocomplete({
                minLength: 2,
                source: function (request, response) {
                    $.ajax({
                        url: "/api/ajax?class=EstoqueItemAutocomplete.php",
                        dataType: "json",
                        data: {
                            acao: 'autocomplete',
                            parametros: { 
                                'nome' : $('#busca_estoque_item').val(),
                            },
							token: '<?= $request->token ?>'
                        },
                        success: function (data) {
                            response(data);
                        }
                    });
                },
                focus: function (event, ui) {
                    $("#busca_estoque_item").val(ui.item.nome);
                    carregarDadosEstoque(ui.item.id_estoque_item);
                    return false;
                },
                select: function (event, ui) {
                    $("#busca_estoque_item").val(ui.item.nome+" ("+ui.item.informacao_adicional+")");
                    $('#busca_estoque_item').attr("readonly", true);
                    return false;
                }
            })
            .autocomplete("instance")._renderItem = function (ul, item) {

            return $("<li>").append("<a><strong>"+item.nome+" ("+item.informacao_adicional+")</a><hr style='margin-bottom: 0px;'>").appendTo(ul);
        };

        // Função para carregar os dados da consulta nos respectivos campos
        function carregarDadosEstoque(id) {
            var busca = $('#busca_estoque_item').val();

            if (busca != "" && busca.length >= 2) {
                $.ajax({
                    url: "/api/ajax?class=EstoqueItemAutocomplete.php",
                    dataType: "json",
                    data: {
                        acao: 'consulta',
                        parametros: { 
                            'id' : id,                            
                        },
						token: '<?= $request->token ?>'
                    },
                    success: function (data) {
                        $('#id_estoque_item').val(data[0].id_estoque_item);
                        $('#quantidade_minima').val(data[0].quantidade_minima);
                        $('#quantidade_atual').val(data[0].quantidade);

                    }
                });
            }
        }

        // Função para limpar os campos caso a busca esteja vazia
        function limpaCamposEstoque() {
            var busca = $('#busca_estoque_item').val();
            if (busca == "") {
                $('#id_estoque_item').val('');
            }
        }

        $(document).on('click', '#habilita_busca_estoque_item', function () {
            $('#id_estoque_item').val('');
            $('#busca_estoque_item').val('');
            $('#busca_estoque_item').attr("readonly", false);
            $('#busca_estoque_item').focus();
        });
    
    //FIM BUSCA ITEM

    //BUSCA FORNECEDOR

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
	                        'atributo' : 'fornecedor'
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

    //FIM BUSCA FORNECEDOR


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
			$('#row_periodo').show();
			$('#row_tipo_movimentacao').show();
			$('#row_id_usuario').show();
			$('#row_id_setor').hide();
			$('#row_fornecedor').hide();
			$('#row_tipo_entrada').hide();
			$('#row_id_estoque_localizacao').hide();
		}else if(tipo_relatorio == 2){
			$('#row_periodo').hide();
			$('#row_tipo_movimentacao').hide();
			$('#row_id_usuario').hide();
			$('#row_id_setor').hide();
			$('#row_fornecedor').hide();
			$('#row_tipo_entrada').hide();
			$('#row_id_estoque_localizacao').show();
		}else if(tipo_relatorio == 3){
			$('#row_periodo').show();
			$('#row_tipo_movimentacao').hide();
			$('#row_id_usuario').show();
			$('#row_id_setor').hide();
			$('#row_fornecedor').show();
			$('#row_tipo_entrada').show();
			$('#row_id_estoque_localizacao').hide();
		}else if(tipo_relatorio == 4){
			$('#row_periodo').show();
			$('#row_tipo_movimentacao').hide();
			$('#row_id_usuario').show();
			$('#row_id_setor').show();
			$('#row_fornecedor').hide();
			$('#row_tipo_entrada').hide();
			$('#row_id_estoque_localizacao').show();
		}
	}); 

</script>

<?php 

function relatorio_itens($id_estoque_item, $id_estoque_localizacao){

	if($id_estoque_item){
		$filtro_id_estoque_item = "AND id_estoque_item = '".$id_estoque_item."' ";
		$dados_id_estoque_item = DBRead('', 'tb_estoque_item', "WHERE id_estoque_item = '".$id_estoque_item."' ");
		$legenda_id_estoque_itemr = $dados_id_estoque_item[0]['nome'];
	}else{
		$legenda_id_estoque_itemr = "Todos";
	}

	if($id_estoque_localizacao){
		$filtro_id_estoque_localizacao = "AND id_estoque_localizacao = '".$id_estoque_localizacao."' ";
		$dados_id_estoque_localizacao = DBRead('', 'tb_estoque_localizacao', "WHERE id_estoque_localizacao = '".$id_estoque_localizacao."' ");
		$legenda_id_estoque_localizacao = $dados_id_estoque_localizacao[0]['nome'];
	}else{
		$legenda_id_estoque_localizacao = "Qualquer";
	}

	$data_hoje = converteDataHora(getDataHora());

	$gerado = "<span style=\"font-size: 14px;\"><strong>Gerado em: </strong> $data_hoje</span>";

	echo "<div class=\"col-md-10 col-md-offset-1\" style=\"padding: 0\">";
	echo "<legend style=\"text-align:center;\"><strong>Relatório - Itens do Estoque</strong><br>$gerado</legend>";
	echo "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong> Item do Estoque - </strong>".$legenda_id_estoque_itemr.",<strong> Localização - </strong>".$legenda_id_estoque_localizacao	."</legend>";

	$dados_item = DBRead('','tb_estoque_item'," WHERE id_estoque_item ".$filtro_id_estoque_item." ".$filtro_id_estoque_localizacao." ");

    if($dados_item){
		echo "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong> Total de Itens: </strong>".sizeof($dados_item)."</legend>";
        echo '
            <table class="table table-striped dataTable" style="margin-bottom:0;">
                <thead>
                    <tr style="vertical-align: middle;">
                        <th>Item</th>
                        <th>Quantidade</th>	
                        <th>Quantidade Mínima</th>	
                        <th>Valor Unitário</th>			
                        <th>Valor Total em Estoque</th>			
                        <th>Informação Adicional</th>
                        <th>Localização</th>
                    </tr>
                </thead>
                <tbody>
        ';
		
        foreach($dados_item as $conteudo_item){
            
            $nome = $conteudo_item['nome'];
            $quantidade = $conteudo_item['quantidade'];
            $quantidade_minima = $conteudo_item['quantidade_minima'];
			$valor_unitario = converteMoeda($conteudo_item['valor_unitario']);
			
			if($quantidade > 0){
				$valor_total_estoque = converteMoeda($quantidade * $conteudo_item['valor_unitario']);
			}else{
				$valor_total_estoque = converteMoeda(0);
			}

            $informacao_adicional = $conteudo_item['informacao_adicional'];
			
			if($quantidade == $quantidade_minima){
				$td_quantidade = "<td style='vertical-align: middle;' class='warning'>$quantidade</td>";
			}else if($quantidade < $quantidade_minima){
				$td_quantidade = "<td style='vertical-align: middle;' class='danger'>$quantidade</td>";
			}else{
				$td_quantidade = "<td style='vertical-align: middle;'>$quantidade</td>";
			}

			if($conteudo_item['id_estoque_localizacao']){
				$dados_id_estoque_localizacao = DBRead('', 'tb_estoque_localizacao', "WHERE id_estoque_localizacao = '".$conteudo_item['id_estoque_localizacao']."' ");
				$nome_localizacao = $dados_id_estoque_localizacao[0]['nome'];
			}else{
				$nome_localizacao = '';
			}

            echo '
                <tr>
                    <td style="vertical-align: middle;">'.$nome.'</td>
                    '.$td_quantidade.'
                    <td style="vertical-align: middle;">'.$quantidade_minima.'</td>
                    <td data-order-"'.$conteudo_item['valor_unitario'].'" style="vertical-align: middle;">R$ '.$valor_unitario.'</td>
                    <td data-order-"'.$valor_total_estoque.'" style="vertical-align: middle;">R$ '.$valor_total_estoque.'</td>
                    <td style="vertical-align: middle;">'.$informacao_adicional.'</td>
                    <td style="vertical-align: middle;">'.$nome_localizacao.'</td>
                </tr>
            ';
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
						aaSorting: [[0, 'asc']],
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
								filename: 'relatorio_estoque_itens',
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

function relatorio_movimentacoes($data_de, $data_ate, $tipo_movimentacao, $id_usuario, $id_estoque_item){

	$data_hoje = converteDataHora(getDataHora());

	if($tipo_movimentacao){
		$filtro_tipo_movimentacao = "AND a.tipo_movimentacao = '".$tipo_movimentacao."' ";
		if($tipo_movimentacao == 'saida'){
			$legenda_tipo_movimentacao = "Saída";
		}else{
			$legenda_tipo_movimentacao = "Entrada";
		}
	}else{
		$legenda_tipo_movimentacao = "Qualquer";
	}

	if($id_usuario){
		$filtro_id_usuario = "AND b.id_usuario = '".$id_usuario."' ";
		$dados_id_usuario = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_usuario = '".$id_usuario."' ORDER BY b.nome ASC");
		$legenda_id_usuario = $dados_id_usuario[0]['nome'];
	}else{
		$legenda_id_usuario = "Qualquer";
	}

	if($id_estoque_item){
		$filtro_id_estoque_item = "AND c.id_estoque_item = '".$id_estoque_item."' ";
		$dados_id_estoque_item = DBRead('', 'tb_estoque_item', "WHERE id_estoque_item = '".$id_estoque_item."' ");
		$legenda_id_estoque_itemr = $dados_id_estoque_item[0]['nome'];
	}else{
		$legenda_id_estoque_itemr = "Qualquer";
	}

	$periodo_amostra = "<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> de $data_de até $data_ate</span>";
	$gerado = "<span style=\"font-size: 14px;\"><strong>Gerado em: </strong> $data_hoje</span>";

	echo "<div class=\"col-md-12\" style=\"padding: 0\">";
	echo "<legend style=\"text-align:center;\"><strong>Relatório - Movimentações de Estoque</strong><br>$gerado</legend>";
    echo "<legend style=\"text-align:center;\">$periodo_amostra</legend>";
	echo "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong> Item do Estoque - </strong>".$legenda_id_estoque_itemr.",<strong> Tipo de Movimentação - </strong>".$legenda_tipo_movimentacao.", <strong> Realizada Por - </strong>".$legenda_id_usuario."</legend>";

	$dados_movimentacoes = DBRead('','tb_estoque_movimentacao_item a',"INNER JOIN tb_estoque_movimentacao b ON a.id_estoque_movimentacao = b.id_estoque_movimentacao INNER JOIN tb_estoque_item c ON a.id_estoque_item = c.id_estoque_item INNER JOIN tb_usuario d ON b.id_usuario = d.id_usuario INNER JOIN tb_pessoa e ON d.id_pessoa = e.id_pessoa WHERE b.data BETWEEN '".converteDataHora($data_de, "data")." 00:00:00' AND '".converteDataHora($data_ate, "data")." 23:59:59' AND a.status = '1' ".$filtro_tipo_movimentacao." ".$filtro_id_usuario." ".$filtro_id_estoque_item." ", "a.*, a.quantidade AS quantidade_movimentacao, b.*, c.*, c.quantidade AS quantidade_item, e.nome AS nome_usuario");

    if($dados_movimentacoes){
		echo "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong> Total de Movimentações: </strong>".sizeof($dados_movimentacoes)."</legend>";

		echo '
            <table class="table table-striped dataTable" style="margin-bottom:0;">
                <thead>
                    <tr style="vertical-align: middle;">
                        <th>Item</th>
                        <th>Informação Adicional</th>	
                        <th>Tipo de Movimentação</th>
                        <th>Quantidade da Movimentação</th>
                        <th>Realizada Por</th>
                        <th>Data</th>		
                    </tr>
                </thead>
                <tbody>
        ';
		
        foreach($dados_movimentacoes as $conteudo_movimentacoes){

        	if($conteudo_movimentacoes['tipo_movimentacao'] == 'saida'){
        		$tipo_movimentacao = 'Saída';
        	}else{
        		$tipo_movimentacao = 'Entrada';
            }
            
            $nome = $conteudo_movimentacoes['nome'];
            $informacao_adicional = $conteudo_movimentacoes['informacao_adicional'];
            $quantidade_movimentacao = $conteudo_movimentacoes['quantidade_movimentacao'];

			$nome_usuario = $conteudo_movimentacoes['nome_usuario'];
            $data = converteDataHora($conteudo_movimentacoes['data']);

			echo '
                <tr>
                    <td style="vertical-align: middle;">'.$nome.'</td>
                    <td style="vertical-align: middle;">'.$informacao_adicional.'</td>
                    <td style="vertical-align: middle;">'.$tipo_movimentacao.'</td>
                    <td style="vertical-align: middle;">'.$quantidade_movimentacao.'</td>
                    <td style="vertical-align: middle;">'.$nome_usuario.'</td>
                    <td data-order="'.$conteudo_movimentacoes['data'].' "style="vertical-align: middle;">'.$data.'</td>
                </tr>
            ';
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
								filename: 'relatorio_estoque_movimentacoes',
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

function relatorio_movimentacoes_entrada($data_de, $data_ate, $id_usuario, $id_estoque_item, $id_fornecedor, $tipo_entrada){

	$data_hoje = converteDataHora(getDataHora());

	if($id_usuario){
		$filtro_id_usuario = "AND b.id_usuario = '".$id_usuario."' ";
		$dados_id_usuario = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_usuario = '".$id_usuario."' ORDER BY b.nome ASC");
		$legenda_id_usuario = $dados_id_usuario[0]['nome'];
	}else{
		$legenda_id_usuario = "Qualquer";
	}

	if($id_estoque_item){
		$filtro_id_estoque_item = "AND c.id_estoque_item = '".$id_estoque_item."' ";
		$dados_id_estoque_item = DBRead('', 'tb_estoque_item', "WHERE id_estoque_item = '".$id_estoque_item."' ");
		$legenda_id_estoque_itemr = $dados_id_estoque_item[0]['nome'];
	}else{
		$legenda_id_estoque_itemr = "Qualquer";
	}

	if($id_fornecedor){
		$filtro_fornecedor = "AND a.id_fornecedor = '".$id_fornecedor."' ";
		$dados_fornecedor = DBRead('', 'tb_pessoa', "WHERE id_pessoa = '".$id_fornecedor."' ", "nome");
		$legenda_fornecedor = $dados_fornecedor[0]['nome'];
	}else{
		$legenda_fornecedor = "Qualquer";
	}

	if($tipo_entrada){
		if($tipo_entrada == 1){
			$legenda_tipo_entrada = "Compra";
		}else{
			$legenda_tipo_entrada = "Interna";
		}
		$filtro_tipo_entrada = "AND a.tipo = '".$tipo_entrada."' ";
	}else{
		$legenda_tipo_entrada = "Qualquer";
	}

	$periodo_amostra = "<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> de $data_de até $data_ate</span>";
	$gerado = "<span style=\"font-size: 14px;\"><strong>Gerado em: </strong> $data_hoje</span>";

	echo "<div class=\"col-md-12\" style=\"padding: 0\">";
	echo "<legend style=\"text-align:center;\"><strong>Relatório - Movimentações de Estoque (Entradas)</strong><br>$gerado</legend>";
    echo "<legend style=\"text-align:center;\">$periodo_amostra</legend>";
	echo "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong> Item do Estoque - </strong>".$legenda_id_estoque_itemr.",<strong> Fornecedor - </strong>".$legenda_fornecedor.", <strong> Realizada Por - </strong>".$legenda_id_usuario.", <strong> Tipo de Entrada - </strong>".$legenda_tipo_entrada."</legend>";

	$dados_movimentacoes = DBRead('','tb_estoque_movimentacao_item a',"INNER JOIN tb_estoque_movimentacao b ON a.id_estoque_movimentacao = b.id_estoque_movimentacao INNER JOIN tb_estoque_item c ON a.id_estoque_item = c.id_estoque_item INNER JOIN tb_usuario d ON b.id_usuario = d.id_usuario INNER JOIN tb_pessoa e ON d.id_pessoa = e.id_pessoa WHERE b.data BETWEEN '".converteDataHora($data_de, "data")." 00:00:00' AND '".converteDataHora($data_ate, "data")." 23:59:59' AND a.tipo_movimentacao = 'entrada' AND a.status = '1' ".$filtro_id_usuario." ".$filtro_id_estoque_item." ".$filtro_fornecedor." ".$filtro_tipo_entrada." ", "a.*, a.quantidade AS quantidade_movimentacao, b.*, c.*, c.quantidade AS quantidade_item, e.nome AS nome_usuario, a.valor_unitario AS valor_unitario_movimentacao");

    if($dados_movimentacoes){
		echo "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong> Total de Movimentações: </strong>".sizeof($dados_movimentacoes)."</legend>";

		echo '
            <table class="table table-striped dataTable" style="margin-bottom:0;">
                <thead>
                    <tr style="vertical-align: middle;">
                        <th>Item</th>
                        <th>Informação Adicional</th>	
                        <th>Fornecedor</th>	
                        <th>Tipo de Entrada</th>	
                        <th>Quantidade da Movimentação</th>
                        <th>Valor Unitário</th>			
                        <th>Valor da Transação</th>			
                        <th>Realizada Por</th>
                        <th>Data</th>			
                    </tr>
                </thead>
                <tbody>
        ';
		$total_transacao = 0;
		$quantidade_total = 0;
        foreach($dados_movimentacoes as $conteudo_movimentacoes){

            $nome = $conteudo_movimentacoes['nome'];
            $informacao_adicional = $conteudo_movimentacoes['informacao_adicional'];
            $quantidade_movimentacao = $conteudo_movimentacoes['quantidade_movimentacao'];
            $valor_unitario_movimentacao = converteMoeda($conteudo_movimentacoes['valor_unitario_movimentacao']);

			$valor_transacao = converteMoeda($conteudo_movimentacoes['valor_unitario_movimentacao'] * $quantidade_movimentacao);

			$nome_usuario = $conteudo_movimentacoes['nome_usuario'];
			$data = converteDataHora($conteudo_movimentacoes['data']);
			if($conteudo_movimentacoes['tipo'] == 1){
				$tipo = 'Compra';
			}else{
				$tipo = 'Interna';
			}

			$id_fornecedor = $conteudo_movimentacoes['id_fornecedor'];
			if($id_fornecedor){
				$dados_fornecedor = DBRead('','tb_pessoa', "WHERE id_pessoa = '".$id_fornecedor."' ", 'nome');
				$nome_fornecedor = $dados_fornecedor[0]['nome'];
			}else{
				$nome_fornecedor = '';
			}

			$total_transacao += ($conteudo_movimentacoes['valor_unitario_movimentacao'] * $quantidade_movimentacao);
			$quantidade_total += $quantidade_movimentacao;

			echo '
                <tr>
                    <td style="vertical-align: middle;">'.$nome.'</td>
                    <td style="vertical-align: middle;">'.$informacao_adicional.'</td>
                    <td style="vertical-align: middle;">'.$nome_fornecedor.'</td>
                    <td style="vertical-align: middle;">'.$tipo.'</td>
                    <td style="vertical-align: middle;">'.$quantidade_movimentacao.'</td>
                    <td data-order-"'.$conteudo_movimentacoes['valor_unitario_movimentacao'].'" style="vertical-align: middle;">R$ '.$valor_unitario_movimentacao.'</td>
                    <td data-order-"'.$valor_transacao.'" style="vertical-align: middle;">R$ '.$valor_transacao.'</td>
                    <td style="vertical-align: middle;">'.$nome_usuario.'</td>
					<td data-order="'.$conteudo_movimentacoes['data'].' "style="vertical-align: middle;">'.$data.'</td>
                </tr>
            ';
        }
           
        echo "
				</tbody>      
				<tfoot>
					<tr>
						<th>Totais</th>
						<th></th>
						<th></th>
						<th></th>
						<th>".$quantidade_total."</th>
						<th></th>
						<th>R$ ".converteMoeda($total_transacao)."</th>
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
								filename: 'relatorio_estoque_movimentacoes_entradas',
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

function relatorio_movimentacoes_saida($data_de, $data_ate, $id_usuario, $id_setor, $id_estoque_item, $id_solicitante, $id_estoque_localizacao){
	
	$data_hoje = converteDataHora(getDataHora());

	if($id_usuario){
		$filtro_id_usuario = "AND b.id_usuario = '".$id_usuario."' ";
		$dados_id_usuario = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_usuario = '".$id_usuario."' ORDER BY b.nome ASC");
		$legenda_id_usuario = $dados_id_usuario[0]['nome'];
	}else{
		$legenda_id_usuario = "Qualquer";
	}

	if($id_setor){
		$filtro_id_setor = "AND a.id_setor = '".$id_setor."' ";
		$dados_id_setor = DBRead('', 'tb_setor', "WHERE id_setor = '".$id_setor."' ");
		$legenda_id_setor = $dados_id_setor[0]['descricao'];
	}else{
		$legenda_id_setor = "Qualquer";
	}

	if($id_estoque_item){
		$filtro_id_estoque_item = "AND c.id_estoque_item = '".$id_estoque_item."' ";
		$dados_id_estoque_item = DBRead('', 'tb_estoque_item', "WHERE id_estoque_item = '".$id_estoque_item."' ");
		$legenda_id_estoque_itemr = $dados_id_estoque_item[0]['nome'];
	}else{
		$legenda_id_estoque_itemr = "Qualquer";
	}

	if($id_solicitante){
		$filtro_id_solicitante = "AND a.id_solicitante = '".$id_solicitante."' ";
		$dados_id_solicitante = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_usuario = '".$id_solicitante."' ", "b.nome");
		$legenda_id_solicitante = $dados_id_solicitante[0]['nome'];
	}else{
		$legenda_id_solicitante = "Qualquer";
	}

	if($id_estoque_localizacao){
		$filtro_id_estoque_localizacao = "AND a.id_estoque_localizacao = '".$id_estoque_localizacao."' ";
		$dados_id_estoque_localizacao = DBRead('', 'tb_estoque_localizacao', "WHERE id_estoque_localizacao = '".$id_estoque_localizacao."' ");
		$legenda_id_estoque_localizacao = $dados_id_estoque_localizacao[0]['nome'];
	}else{
		$legenda_id_estoque_localizacao = "Qualquer";
	}

	$periodo_amostra = "<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> de $data_de até $data_ate</span>";
	$gerado = "<span style=\"font-size: 14px;\"><strong>Gerado em: </strong> $data_hoje</span>";

	echo "<div class=\"col-md-12\" style=\"padding: 0\">";
	echo "<legend style=\"text-align:center;\"><strong>Relatório - Movimentações de Estoque (Saídas)</strong><br>$gerado</legend>";
    echo "<legend style=\"text-align:center;\">$periodo_amostra</legend>";
	echo "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong> Item do Estoque - </strong>".$legenda_id_estoque_itemr.",<strong> Realizada Por - </strong>".$legenda_id_usuario.", <strong> Setor Responsável - </strong>".$legenda_id_setor.", <strong> Solicitante - </strong>".$legenda_id_solicitante.", <strong> Localização - </strong>".$legenda_id_estoque_localizacao."</legend>";

	$dados_movimentacoes = DBRead('','tb_estoque_movimentacao_item a',"INNER JOIN tb_estoque_movimentacao b ON a.id_estoque_movimentacao = b.id_estoque_movimentacao INNER JOIN tb_estoque_item c ON a.id_estoque_item = c.id_estoque_item INNER JOIN tb_usuario d ON b.id_usuario = d.id_usuario INNER JOIN tb_pessoa e ON d.id_pessoa = e.id_pessoa WHERE b.data BETWEEN '".converteDataHora($data_de, "data")." 00:00:00' AND '".converteDataHora($data_ate, "data")." 23:59:59' AND a.tipo_movimentacao = 'saida' AND a.status = '1' ".$filtro_id_usuario." ".$filtro_id_setor." ".$filtro_id_estoque_item." ".$filtro_id_solicitante." ".$filtro_id_estoque_localizacao." ", "a.*, a.quantidade AS quantidade_movimentacao, b.*, c.*, c.quantidade AS quantidade_item, e.nome AS nome_usuario, a.valor_unitario AS valor_unitario_movimentacao");

	if($dados_movimentacoes){
		echo "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong> Total de Movimentações: </strong>".sizeof($dados_movimentacoes)."</legend>";

		echo '
            <table class="table table-striped dataTable" style="margin-bottom:0;">
                <thead>
                    <tr style="vertical-align: middle;">
                        <th>Item</th>
                        <th>Informação Adicional</th>	
                        <th>Observação</th>	
                        <th>Setor Responsável</th>	
                        <th>Quantidade da Movimentação</th>
                        <th>Solicitante</th>
                        <th>Realizada Por</th>
                        <th>Localização</th>
                        <th>Data</th>			
                    </tr>
                </thead>
                <tbody>
		';
		
		$quantidade_total = 0;
		foreach($dados_movimentacoes as $conteudo_movimentacoes){

            $nome = $conteudo_movimentacoes['nome'];
            $informacao_adicional = $conteudo_movimentacoes['informacao_adicional'];
			$observacao = $conteudo_movimentacoes['observacao'];
            $quantidade_movimentacao = $conteudo_movimentacoes['quantidade_movimentacao'];

			$nome_usuario = $conteudo_movimentacoes['nome_usuario'];
            $data = converteDataHora($conteudo_movimentacoes['data']);
			$nome_setor = $conteudo_movimentacoes['nome_setor'];

			if($conteudo_movimentacoes['id_setor']){
				$dados_setor = DBRead('','tb_setor', "WHERE id_setor = '".$conteudo_movimentacoes['id_setor']."' ", "descricao");
				$nome_setor = $dados_setor[0]['descricao'];
			}else{
				$nome_setor = '';
			}

			if($conteudo_movimentacoes['id_solicitante']){
				$dados_id_solicitante = DBRead('','tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_usuario = '".$conteudo_movimentacoes['id_solicitante']."' ", "b.nome");
				$nome_solicitante = $dados_id_solicitante[0]['nome'];
			}else{
				$nome_solicitante = '';
			}

			if($conteudo_movimentacoes['id_estoque_localizacao']){
				$dados_id_estoque_localizacao = DBRead('','tb_estoque_localizacao', "WHERE id_estoque_localizacao = '".$conteudo_movimentacoes['id_estoque_localizacao']."' ", "nome");
				$nome_localizacao = $dados_id_estoque_localizacao[0]['nome'];
			}else{
				$nome_localizacao = '';
			}

			echo '
                <tr>
                    <td style="vertical-align: middle;">'.$nome.'</td>
                    <td style="vertical-align: middle;">'.$informacao_adicional.'</td>
                    <td style="vertical-align: middle;">'.$observacao.'</td>
                    <td style="vertical-align: middle;">'.$nome_setor.'</td>
                    <td style="vertical-align: middle;">'.$quantidade_movimentacao.'</td>
                    <td style="vertical-align: middle;">'.$nome_solicitante.'</td>
                    <td style="vertical-align: middle;">'.$nome_usuario.'</td>
                    <td style="vertical-align: middle;">'.$nome_localizacao.'</td>
                    <td data-order="'.$conteudo_movimentacoes['data'].' "style="vertical-align: middle;">'.$data.'</td>
                </tr>
			';
			
			$quantidade_total += $quantidade_movimentacao;
        }
           
        echo "
				</tbody>      
				<tfoot>
					<tr>
						<th>Totais</th>
						<th></th>
						<th></th>
						<th></th>
						<th>".$quantidade_total."</th>
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
								filename: 'relatorio_estoque_movimentacoes_saidas',
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
