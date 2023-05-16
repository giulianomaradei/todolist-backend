<?php
	require_once(__DIR__."/../class/System.php");

$gerar = (! empty($_POST['gerar'])) ? 1 : 0;

$id_patrimonio_item = (! empty($_POST['id_patrimonio_item'])) ? $_POST['id_patrimonio_item'] : '';
$id_patrimonio_localizacao = (! empty($_POST['id_patrimonio_localizacao'])) ? $_POST['id_patrimonio_localizacao'] : '';
$id_responsavel = (! empty($_POST['id_responsavel'])) ? $_POST['id_responsavel'] : '';
$id_fornecedor = (! empty($_POST['id_fornecedor'])) ? $_POST['id_fornecedor'] : '';
$status = (! empty($_POST['status'])) ? $_POST['status'] : '';
$numero_nota_fiscal = (! empty($_POST['numero_nota_fiscal'])) ? $_POST['numero_nota_fiscal'] : '';
$numero_patrimonio = (! empty($_POST['numero_patrimonio'])) ? $_POST['numero_patrimonio'] : '';
$data_de_compra = (! empty($_POST['data_de_compra'])) ? $_POST['data_de_compra'] : '';
$data_ate_compra = (! empty($_POST['data_ate_compra'])) ? $_POST['data_ate_compra'] : '';
$data_da_garantia = (! empty($_POST['data_da_garantia'])) ? $_POST['data_da_garantia'] : '';

if($id_fornecedor){
	$dados = DBRead('','tb_pessoa',"WHERE id_pessoa = '$id_fornecedor'");
	if($dados){
		$nome_pessoa = $dados[0]['nome'];
		//$pessoa_input = $id_fornecedor . ' - ' . $nome_pessoa;
		$pessoa_input = $nome_pessoa;
	}else{
		$nome_pessoa = '';
		$pessoa_input ='';
	}
}

if ($gerar) {
    $collapse = '';
    $collapse_icon = 'plus';
} else {
    $collapse = 'in';
    $collapse_icon = 'minus';
}
?>
<style>
@media print {
	.noprint {
		display: none;
	}
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
						<h3 class="panel-title text-left pull-left"
							style="margin-top: 2px;">Relatório de Patrimônios:</h3>
						<div class="panel-title text-right pull-right">
							<button data-toggle="collapse" data-target="#accordionRelatorio"
								class="btn btn-xs btn-info" type="button"
								title="Visualizar filtros">
								<i id="i_collapse" class="fa fa-<?=$collapse_icon?>"></i>
							</button>
						</div>
					</div>
					<div id="accordionRelatorio" class="panel-collapse collapse <?=$collapse?>">
						<div class="panel-body">
							
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Item:</label>
                                        <select name="id_patrimonio_item" id="id_patrimonio_item" class="form-control input-sm" onchange="call_busca_ajax();">
                                        <option value="">Todos</option>
                                        <?php
                                            $dados_patrimonio_item = DBRead('', 'tb_patrimonio_item', "WHERE status = 1 ORDER BY descricao ASC");
                                            if ($dados_patrimonio_item) {
                                                foreach ($dados_patrimonio_item as $conteudo_patrimonio_patrimonio_item) {
													$selected = $id_patrimonio_item == $conteudo_patrimonio_patrimonio_item['id_patrimonio_item'] ? "selected" : "";
                                                    echo "<option value='" . $conteudo_patrimonio_patrimonio_item['id_patrimonio_item'] . "' ".$selected.">" . $conteudo_patrimonio_patrimonio_item['descricao'] . "</option>";
                                                }
                                            }
                                        ?>
                                        </select>
                                    </div>
                                </div> 
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Localização:</label>
                                        <select name="id_patrimonio_localizacao" id="id_patrimonio_localizacao" class="form-control input-sm" onchange="call_busca_ajax();">
                                            <option value="">Todos</option>
                                            <?php
                                                $dados_patrimonio_localizacao = DBRead('', 'tb_patrimonio_localizacao', "WHERE status = 1 ORDER BY nome ASC");
                                                if ($dados_patrimonio_localizacao) {
                                                    foreach ($dados_patrimonio_localizacao as $conteudo_patrimonio_patrimonio_localizacao) {
														$selected = $id_patrimonio_localizacao == $conteudo_patrimonio_patrimonio_localizacao['id_patrimonio_localizacao'] ? "selected" : "";
                                                        echo "<option value='" . $conteudo_patrimonio_patrimonio_localizacao['id_patrimonio_localizacao'] . "' ".$selected.">" . $conteudo_patrimonio_patrimonio_localizacao['nome'] . "</option>";
                                                    }
                                                }
                                            ?>
                                        </select>
                                    </div>
                                </div> 
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Responsável:</label>
                                        <select name="id_responsavel" id="id_responsavel" class="form-control input-sm" onchange="call_busca_ajax();">
                                            <option value="">Todos</option>
                                            <?php
                                                $dados_id_responsavel = DBRead('', 'tb_patrimonio a', "INNER JOIN tb_usuario b ON a.id_responsavel = b.id_usuario INNER JOIN tb_pessoa c ON b.id_pessoa = c.id_pessoa WHERE a.status != 6 GROUP BY a.id_responsavel","a.id_responsavel, c.nome");
                                                if ($dados_id_responsavel) {
                                                    foreach ($dados_id_responsavel as $conteudo_patrimonio_id_responsavel) {
														$selected = $id_responsavel == $conteudo_patrimonio_id_responsavel['id_responsavel'] ? "selected" : "";
                                                        echo "<option value='" . $conteudo_patrimonio_id_responsavel['id_responsavel'] . "' ".$selected.">" . $conteudo_patrimonio_id_responsavel['nome'] . "</option>";
                                                    }
                                                }
                                            ?>
                                        </select>
                                    </div>
                                </div> 
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
									<label>Fornecedor:</label>
									<div class="input-group">
										<input class="form-control input-sm" id="busca_pessoa" type="text" name="busca_pessoa"  value="<?=$pessoa_input;?>" placeholder="Informe o nome do Fornecedor..." autocomplete="off" readonly>
										<div class="input-group-btn">
											<button class="btn btn-info btn-sm" id="habilita_busca_pessoa" name="habilita_busca_pessoa" type="button" title="Clique para selecionar o fornecedor" style="height: 30px;"><i class="fa fa-search"></i></button>
										</div>
									</div>
									<input type="hidden" name="id_fornecedor" id="id_fornecedor" value="<?=$id_fornecedor?>">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Status:</label>
                                        <select name="status" id="status" class="form-control input-sm">
                                            <option value="" <?php if($status == ''){ echo 'selected';}?>>Todos</option>
                                            <option value="4" <?php if($status == '4'){ echo 'selected';}?>>Descartado</option>
                                            <option value="5" <?php if($status == '5'){ echo 'selected';}?>>Doado</option>
                                            <option value="2" <?php if($status == '2'){ echo 'selected';}?>>Em Estoque</option>
                                            <option value="1" <?php if($status == '1'){ echo 'selected';}?>>Em Uso</option>
                                            <option value="3" <?php if($status == '3'){ echo 'selected';}?>>Vendido</option>
                                            <option value="7" <?php if($status == '7'){ echo 'selected';}?>>Manutenção</option>
                                        </select>
                                    </div>
                                </div>
							</div>
							
							<div class="row">
								<div class="col-md-12">
									<div class="form-group">
										<label>Número da Nota Fiscal:</label> 
										<input type="number" class="form-control input-sm" name="numero_nota_fiscal" value="<?=$numero_nota_fiscal?>" id="numero_nota_fiscal">
									</div>
								</div>
							</div>

							<div class="row">
								<div class="col-md-12">
									<div class="form-group">
										<label>Número do Patrimônio:</label> 
										<input type="number" class="form-control input-sm" name="numero_patrimonio" value="<?=$numero_patrimonio?>" id="numero_patrimonio">
									</div>
								</div>
							</div>

							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label>Data Inicial da Compra:</label> 
										<input type="text" class="form-control date calendar input-sm" name="data_de_compra" value="<?=$data_de_compra?>" id="data_de_compra">
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label>Data Final  da Compra:</label> 
										<input type="text" class="form-control date calendar input-sm" name="data_ate_compra" value="<?=$data_ate_compra?>" id="data_ate_compra">
									</div>
								</div>
							</div>

							<div class="row">
								<div class="col-md-12">
									<div class="form-group">
										<label>Data da Garantia:</label> 
										<input type="text" class="form-control date calendar input-sm" name="data_da_garantia" value="<?=$data_da_garantia?>" id="data_da_garantia">
									</div>
								</div>
							</div>
                            
		                </div>
					</div>
					<div class="panel-footer">
						<div class="row">
							<div id="panel_buttons" class="col-md-12" style="text-align: center">
								<button class="btn btn-primary" name="gerar" id="gerar" value="1" type="submit">
									<i class="fa fa-refresh"></i> Gerar
								</button>
								<button class="btn btn-warning" name="imprimir" type="button" onclick="window.print();">
									<i class="fa fa-print"></i> Imprimir
								</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</form>
	<div class="row" id="resultado">
    <?php
		if ($gerar) {
			relatorio_patrimonio($id_patrimonio_item, $id_patrimonio_localizacao, $id_responsavel, $id_fornecedor, $status, $numero_nota_fiscal, $numero_patrimonio, $data_de_compra, $data_ate_compra, $data_da_garantia);
		}
	?>
	</div>
</div>
<script>

    $('#accordionRelatorio').on('shown.bs.collapse', function () {
       $("#i_collapse").removeClass("fa fa-plus").addClass("fa fa-minus");
    });

    $('#accordionRelatorio').on('hidden.bs.collapse', function () {
       $("#i_collapse").removeClass("fa fa-minus").addClass("fa fa-plus");
    });

    $(document).ready(function(){
	    $('#aguarde').hide();
	    $('#resultado').show();
	    $("#gerar").prop("disabled", false);
	});

    $(document).on('submit', 'form', function(){
        modalAguarde();
    });

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
						'atributo' : 'fornecedor',
					},
					token: '<?= $request->token ?>'
				},
				success: function(data){
					response(data);
				}
			});
		},
		focus: function(event, ui){
			$("#busca_pessoa").val(ui.item.nome + " "+ ui.item.nome_contrato);
			carregarDadosPessoa(ui.item.id_pessoa);
			return false;
		},
		select: function(event, ui){
			$("#busca_pessoa").val(ui.item.nome + " "+ ui.item.nome_contrato);
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
		if(!item.nome_contrato){
			item.nome_contrato = '';
		}else{
			item.nome_contrato = ' ('+item.nome_contrato+') '; 
		}

	return $("<li>").append("<a><strong>"+item.id_pessoa+" - "+ item.nome + item.nome_contrato +" </strong><br>" +item.razao_social+ "<br>" +item.cpf_cnpj+ "</a><hr style='margin-bottom: 0px;'>").appendTo(ul);
	};

	// Função para carregar os dados da consulta nos respectivos campos
	function carregarDadosPessoa(id){
		var busca = $('#busca_pessoa').val();

		if(busca != "" && busca.length >= 2){
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
				success: function(data){
					$('#id_fornecedor').val(data[0].id_pessoa);
				}
			});
		}
	}

	// Função para limpar os campos caso a busca esteja vazia
	function limpaCamposPessoa(){
		var busca = $('#busca_pessoa').val();
		if (busca == "") {
			$('#id_fornecedor').val('');
		}
	}

	$(document).on('click', '#habilita_busca_pessoa', function () {
		$('#id_fornecedor').val('');
		$('#busca_pessoa').val('');
		$('#busca_pessoa').attr("readonly", false);
		$('#busca_pessoa').focus();
	});

</script>
<?php

function relatorio_patrimonio($id_patrimonio_item, $id_patrimonio_localizacao, $id_responsavel, $id_fornecedor, $status, $numero_nota_fiscal, $numero_patrimonio, $data_de_compra, $data_ate_compra, $data_da_garantia){

	if(!$numero_nota_fiscal && !$numero_patrimonio){
		if($id_patrimonio_item){
			$dados_id_patrimonio_item = DBRead('', 'tb_patrimonio_item', "WHERE id_patrimonio_item = '".$id_patrimonio_item."' ", "descricao");
			$legenda_id_patrimonio_item = $dados_id_patrimonio_item[0]['descricao'];
			$filtro_id_patrimonio_item = " AND a.id_patrimonio_item = '".$id_patrimonio_item."' ";
		}else{
			$legenda_id_patrimonio_item = "Todos";
		}
	
		if($id_patrimonio_localizacao){
			$dados_id_patrimonio_localizacao = DBRead('', 'tb_patrimonio_localizacao', "WHERE id_patrimonio_localizacao = '".$id_patrimonio_localizacao."' ", "nome");
			$legenda_id_patrimonio_localizacao = $dados_id_patrimonio_localizacao[0]['nome'];
			$filtro_id_patrimonio_localizacao = " AND a.id_patrimonio_localizacao = '".$id_patrimonio_localizacao."' ";
		}else{
			$legenda_id_patrimonio_localizacao = "Todos";
		}
	
		if($id_responsavel){
			$dados_id_responsavel = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_usuario = '".$id_responsavel."' ", "b.nome");
			$legenda_id_responsavel = $dados_id_responsavel[0]['nome'];
			$filtro_id_responsavel = " AND a.id_responsavel = '".$id_responsavel."' ";
		}else{
			$legenda_id_responsavel = "Todos";
		}
	
		if($id_fornecedor){
			$dados_id_fornecedor = DBRead('', 'tb_pessoa', "WHERE id_pessoa = '".$id_fornecedor."' ", "nome");
			$legenda_id_fornecedor = $dados_id_fornecedor[0]['nome'];
			$filtro_id_fornecedor = " AND a.id_fornecedor = '".$id_fornecedor."' ";
		}else{
			$legenda_id_fornecedor = "Todos";
		}

		if($status){
			if($status == 1){
				$legenda_status = "Em Uso";
			}else if($status == 2){
				$legenda_status = "Em Estoque";
			}else if($status == 3){
				$legenda_status = "Vendido";
			}else if($status == 4){
				$legenda_status = "Descartado";
			}else if($status == 5){
				$legenda_status = "Doado";
			}else if($status == 7){
				$legenda_status = "Manutenção";
			}
			$filtro_status = " AND a.status = '".$status."' ";
		}else{
			$legenda_status = "Todos";
		}

		if($data_de_compra || $data_ate_compra){
			if ($data_de_compra && $data_ate_compra) {
				$periodo_amostra = "<span class=\"noprint\" style=\"font-size: 14px;\"><strong>Período da Compra:</strong> De $data_de_compra até $data_ate_compra</span>";
				$filtro_data = " AND a.data_compra >= '".converteDataHora($data_de_compra)."' AND a.data_compra <= '".converteDataHora($data_ate_compra)."' ";
			} elseif ($data_de_compra) {
				$periodo_amostra = "<span style=\"font-size: 14px;\"><strong>Período da Compra:</strong> A partir de $data_de_compra</span>";
				$filtro_data = " AND a.data_compra >= '".converteDataHora($data_de_compra)."' ";
			} elseif ($data_ate_compra) {
				$periodo_amostra = "<span style=\"font-size: 14px;\"><strong>Período da Compra:</strong> Até $data_ate_compra</span>";
				$filtro_data = " AND a.data_compra <= '".converteDataHora($data_ate_compra)."' ";
			}
			$legenda_periodo = "<legend style=\"text-align:center;\">".$periodo_amostra."</legend>";
		}

		if($data_da_garantia){
			$periodo_data_da_garantia = "<span style=\"font-size: 14px;\"><strong>Garantia a Partir de:</strong> $data_da_garantia</span>";
			$filtro_data_da_garantia = " AND a.data_garantia >= '".converteDataHora($data_da_garantia)."' ";
			$legenda_data_da_garantia = "<legend style=\"text-align:center;\">".$periodo_data_da_garantia."</legend>";
		}

		$legenda_busca = "<strong> Item - </strong>".$legenda_id_patrimonio_item.",<strong> Localização - </strong>".$legenda_id_patrimonio_localizacao.",<strong> Responsável - </strong>".$legenda_id_responsavel.",<strong> Fornecedor - </strong>".$legenda_id_fornecedor.",<strong> Status - </strong>".$legenda_status;

	}else{
		if($numero_nota_fiscal && $numero_patrimonio){
			$legenda_numero_nota_fiscal = $numero_nota_fiscal;
			$filtro_numero_nota_fiscal = " AND a.numero_nota_fiscal = '".$numero_nota_fiscal."' ";

			$legenda_numero_patrimonio = $numero_patrimonio;
			$filtro_numero_patrimonio = " AND a.numero_patrimonio = '".$numero_patrimonio."' ";
			$legenda_busca = "<strong> Número da Nota Fiscal - </strong>".$legenda_numero_nota_fiscal.", <strong> Número da Patrimônio - </strong>".$legenda_numero_patrimonio;
		}else if($numero_nota_fiscal && !$numero_patrimonio){
			$legenda_numero_nota_fiscal = $numero_nota_fiscal;
			$filtro_numero_nota_fiscal = " AND a.numero_nota_fiscal = '".$numero_nota_fiscal."' ";

			$legenda_numero_patrimonio = "Todos";
			$filtro_numero_patrimonio = "";
			$legenda_busca = "<strong> Número da Nota Fiscal - </strong>".$legenda_numero_nota_fiscal;
		}else{
			$legenda_numero_nota_fiscal = "Todos";
			$filtro_numero_nota_fiscal = "";

			$legenda_numero_patrimonio = $numero_patrimonio;
			$filtro_numero_patrimonio = " AND a.numero_patrimonio = '".$numero_patrimonio."' ";
			$legenda_busca = "<strong> Número do Patrimônio - </strong>".$legenda_numero_patrimonio;
		}

	}

    $dados_patrimonio = DBRead('', 'tb_patrimonio a', "INNER JOIN tb_patrimonio_item b ON a.id_patrimonio_item = b.id_patrimonio_item INNER JOIN tb_patrimonio_localizacao c ON a.id_patrimonio_localizacao = c.id_patrimonio_localizacao WHERE a.id_patrimonio AND a.status != 6 ".$filtro_id_patrimonio_item." ".$filtro_id_patrimonio_localizacao." ".$filtro_id_responsavel." ".$filtro_id_fornecedor." ".$filtro_status." ".$filtro_numero_nota_fiscal." ".$filtro_numero_patrimonio." ".$filtro_data." ".$filtro_data_da_garantia." ", "a.*, a.status AS status_patrimonio, b.descricao, c.nome AS nome_localizacao");

	$data_hora = converteDataHora(getDataHora());
	echo "<div class=\"col-md-12\" style=\"padding: 0\">";

	echo "<legend style=\"text-align:center;\"><strong>Relatório de Patrimônios</strong><br><span style=\"font-size: 14px;\"><strong>Gerado em:</strong> $data_hora</span></legend>";
	echo $legenda_periodo;
	echo $legenda_data_da_garantia;
	echo "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\">".$legenda_busca." ";
	echo "</legend>"; 
    
    if($dados_patrimonio){

		echo "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong> Total: </strong>".sizeof($dados_patrimonio)."</legend>";
        echo "<table class='table table-hover dataTable' style='font-size: 14px;'>";
			echo "<thead>";
				echo "<tr>";
					echo "<th>#</th>";
					echo "<th>Item</th>";
					echo "<th>Data da Compra</th>";
					echo "<th>Localização</th>";
					echo "<th>Responsável</th>";
					echo "<th>Fornecedor</th>";
					echo "<th>Data da Garantia</th>";
					echo "<th>Número da Nota Fiscal</th>";
					echo "<th>Observação</th>";
					echo "<th>Número do Patrimônio</th>";
					echo "<th>Status</th>";
					echo "<th>Valor da Compra</th>";
					echo "<th>Atualizado em</th>";
				echo "</tr>";
			echo "</thead>";
            echo "<tbody>";
            $valor_total = 0;
			foreach ($dados_patrimonio as $conteudo_patrimonio) {
                $valor_total += $conteudo_patrimonio['valor_compra'];
				$id_patrimonio = $conteudo_patrimonio['id_patrimonio'];
				$descricao = $conteudo_patrimonio['descricao'];
				$data_compra = converteDataHora($conteudo_patrimonio['data_compra'], 'data');
				$valor_compra = converteMoeda($conteudo_patrimonio['valor_compra']);
				
				$nome_localizacao = $conteudo_patrimonio['nome_localizacao'];

				if($conteudo_patrimonio['id_responsavel']){
					$dados_responsavel = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_usuario = '".$conteudo_patrimonio['id_responsavel']."' ", "b.nome");
					$nome_responsavel = $dados_responsavel[0]['nome'];
				}else{
					$nome_responsavel = '';
				}

				if($conteudo_patrimonio['id_fornecedor']){
					$dados_fornecedor = DBRead('', 'tb_pessoa', "WHERE id_pessoa = '".$conteudo_patrimonio['id_fornecedor']."' ", "nome");
					$nome_fornecedor = $dados_fornecedor[0]['nome'];
				}else{
					$nome_fornecedor = '';
				}

				if($conteudo_patrimonio['status_patrimonio'] == 1){
					$status_patrimonio = "Em Uso";
				}else if($conteudo_patrimonio['status_patrimonio'] == 2){
					$status_patrimonio = "Em Estoque";
				}else if($conteudo_patrimonio['status_patrimonio'] == 3){
					$status_patrimonio = "Vendido";
				}else if($conteudo_patrimonio['status_patrimonio'] == 4){
					$status_patrimonio = "Descartado";
				}else if($conteudo_patrimonio['status_patrimonio'] == 5){
					$status_patrimonio = "Doado";
				}else if($conteudo_patrimonio['status_patrimonio'] == 7){
					$status_patrimonio = "Manutenção";
				}

				$data_garantia = converteDataHora($conteudo_patrimonio['data_garantia'], 'data');
				$numero_nota_fiscal = $conteudo_patrimonio['numero_nota_fiscal'];
				$observacao = $conteudo_patrimonio['observacao'];
				$numero_patrimonio = $conteudo_patrimonio['numero_patrimonio'];

				if($conteudo_patrimonio['data_atualizacao']){
					$data_atualizacao = converteDataHora($conteudo_patrimonio['data_atualizacao']);
				}else{
					$data_atualizacao = "N/D";
				}
				
				echo "<tr>";
				 	echo "<td>$id_patrimonio</td>";
					 echo "<td>$descricao</td>";
					 echo "<td>$data_compra</td>";
					 echo "<td>$nome_localizacao</td>";
					 echo "<td>$nome_responsavel</td>";
					 echo "<td>$nome_fornecedor</td>";
					 echo "<td>$data_garantia</td>";
					 echo "<td>$numero_nota_fiscal</td>";
					 echo "<td>$observacao</td>";
					 echo "<td>$numero_patrimonio</td>";
					 echo "<td>$status_patrimonio</td>";
					 echo "<td>R$ $valor_compra</td>";
					 echo "<td>$data_atualizacao</td>";
				echo "</tr>";
			}
            echo "</tbody>";
            echo "<tfoot>";
					
                echo '<tr>';
                    echo '<th></th>';
                    echo '<th></th>';
                    echo '<th></th>';
                    echo '<th></th>';
                    echo '<th></th>';
                    echo '<th></th>';
                    echo '<th></th>';
                    echo '<th></th>';
                    echo '<th></th>';
                    echo '<th></th>';
                    echo '<th>Total:</th>';
                    echo '<th>R$ '.converteMoeda($valor_total).'</th>';
                    echo '<th></th>';
                echo '</tr>';

            echo "</tfoot> ";
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
?>					