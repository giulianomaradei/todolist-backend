<?php
	require_once(__DIR__."/../class/System.php");
	
	$id_usuario = $_SESSION['id_usuario'];
	$dados = DBRead('', 'tb_usuario', "WHERE id_usuario = '$id_usuario'");

	$data_hoje = getDataHora();
	$data_hoje = explode(" ", $data_hoje);
	$data_hoje = $data_hoje[0];
	$primeiro_dia = "01/".$data_hoje[5].$data_hoje[6]."/".$data_hoje[0].$data_hoje[1].$data_hoje[2].$data_hoje[3];

	$id_pessoa = (!empty($_POST['id_pessoa'])) ? $_POST['id_pessoa'] : '';
	$data_de = (!empty($_POST['data_de'])) ? $_POST['data_de'] : '';
	$data_ate = (!empty($_POST['data_ate'])) ? $_POST['data_ate'] : '';

	$tipo_relatorio = (!empty($_POST['tipo_relatorio'])) ? $_POST['tipo_relatorio'] : '1';
	$responsavel = (!empty($_POST['responsavel'])) ? $_POST['responsavel'] : '';
	$tipo_negocio = (!empty($_POST['tipo_negocio'])) ? $_POST['tipo_negocio'] : '';
	$cod_servico = (!empty($_POST['cod_servico'])) ? $_POST['cod_servico'] : '';
	$id_plano = (!empty($_POST['id_plano'])) ? $_POST['id_plano'] : '';
	$motivo_perda = (!empty($_POST['motivo_perda'])) ? $_POST['motivo_perda'] : '';
	
	$id_lead_origem = (!empty($_POST['id_lead_origem'])) ? $_POST['id_lead_origem'] : '';
	$estado = (!empty($_POST['estado'])) ? $_POST['estado'] : '';
	$situacao = (!empty($_POST['situacao'])) ? $_POST['situacao'] : '';

	$status_andamento = (!empty($_POST['status_andamento'])) ? $_POST['status_andamento'] : '';
	$origem_andamento = (!empty($_POST['origem_andamento'])) ? $_POST['origem_andamento'] : '';

	if ($tipo_relatorio == 1) {
		$display_pausa = 'none';
		$display = 'block';
		$display_perda = 'none';
		$display_row5 = 'block';
		$display_row6 = 'none';
		$display_row7 = 'none';
		
	} else if ($tipo_relatorio == 2) {
		$display_perda = 'block';
		$display_pausa = 'none';
		$display_row5 = 'block';
		$display_row6 = 'none';
		$display_row7 = 'none';
		
	} else if ($tipo_relatorio == 5) {
		$display_pausa = 'none';
		$display_perda = 'none';
		$display = 'none';
		$display_row5 = 'block';
		$display_row6 = 'block';
		$display_row7 = 'none';

	} else if ($tipo_relatorio == 6) {
		$display_pausa = 'none';
		$display_perda = 'none';
		$display = 'none';
		$display_row5 = 'block';
		$display_row6 = 'none';
		$display_row7 = 'block';
	}

	$gerar = (!empty($_POST['gerar'])) ? 1 : 0;
	
	if($id_pessoa){
		$dados_contrato = DBRead('', 'tb_contrato_plano_pessoa a', "INNER JOIN tb_plano b ON a.id_plano = b.id_plano INNER JOIN tb_pessoa c ON a.id_pessoa = c.id_pessoa WHERE c.id_pessoa = '$id_pessoa'", "a.*, b.cod_servico, b.nome AS 'plano', c.nome AS 'nome_pessoa'");

		$contrato = $dados_contrato[0]['nome_pessoa'];
	}else{
		$contrato = '';
	}
	if($gerar){
		$collapse = '';
		$collapse_icon = 'plus';
		
	}else{
		$collapse = 'in';
		$collapse_icon = 'minus';
	}
?>

<style>
	.conteudo-editor img{
        max-width: 100% !important;
        max-height: 100% !important;
        height: 100% !important;
    }
	
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
	<form method="post" id="relatorio_erro_form">
		<div class="row">
	        <div class="col-md-4 col-md-offset-4">

	        	<div class="panel panel-default noprint">
	                <div class="panel-heading clearfix">
	                    <h3 class="panel-title text-left pull-left" style="margin-top: 2px;">Relatório de Leads/Negócios:</h3>
	                    <div class="panel-title text-right pull-right"><button data-toggle="collapse" data-target="#accordionRelatorio" class="btn btn-xs btn-info" type="button" title="Visualizar filtros"><i id="i_collapse" class="fa fa-<?=$collapse_icon?>"></i></button></div>
	                </div>
	                <div id="accordionRelatorio" class="panel-collapse collapse <?=$collapse?>">
	                	<div class="panel-body">
	                		<div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>*Tipo de Relatório:</label> <select
                                            name="tipo_relatorio" id="tipo_relatorio" class="form-control input-sm">
											<option value="6" <?php if($tipo_relatorio == '5'){echo 'selected';}?>>Em andamento</option>
                                            <option value="1" <?php if($tipo_relatorio == '1'){echo 'selected';}?>>Ganhou</option>
											<option value="2" <?php if($tipo_relatorio == '2'){echo 'selected';}?>>Perdeu</option>
											<option value="5" <?php if($tipo_relatorio == '5'){echo 'selected';}?>>Tabela</option>
											<?php if ($perfil_usuario == 2) { ?>
											<option value="7" <?php if($tipo_relatorio == '7'){echo 'selected';}?>>Tabela - Todos Negócios</option>
											<?php } ?>
                                        </select>
                                    </div>
                                </div>
                            </div>

							<div class="row" id="row2" style="display: <?=$display_perda?>;">
								<div class="col-md-12">
									<div class="form-group">
										<label>*Motivo da perda:</label>
										<select class="form-control input-sm" name="motivo_perda">
											<option value="">Qualquer</option>
										<?php 
											$dados = DBRead('', 'tb_lead_motivo_perda', "ORDER BY descricao");
											foreach($dados as $conteudo){
												$id_lead_motivo_perda = $conteudo['id_lead_motivo_perda'];
												$descricao = $conteudo['descricao'];
												$selected = $motivo_perda == $id_lead_motivo_perda ? "selected" : "";

												echo "<option value='$id_lead_motivo_perda' ".$selected.">$descricao</option>";
											}
										?>
										</select>
									</div>
								</div>
							</div>

							<div class="row" id="row4" style="display: <?=$display?>;">
								<div class="col-md-6">
									<div class="form-group">
										<label>*Responsável:</label>
										<select class="form-control input-sm" name="responsavel">
											<option value="">Qualquer</option>
											<?php
												$usuarios = DBRead('', 'tb_pessoa a', "INNER JOIN tb_usuario b ON a.id_pessoa = b.id_pessoa WHERE (id_perfil_sistema = 22 OR id_perfil_sistema = 11 OR id_perfil_sistema = 8 OR id_perfil_sistema = 7 OR id_perfil_sistema = 29) AND b.status = 1 ORDER BY a.nome ASC", 'b.id_usuario, a.nome, b.email');

												if($usuarios){
													foreach($usuarios as $conteudo){
														$id_usuario = $conteudo['id_usuario'];
														$nomeSelect = $conteudo['nome'];
														$selected = $responsavel == $id_usuario ? "selected" : "";
														echo "<option value='$id_usuario'".$selected.">$nomeSelect</option>";
													}
												}
											?>
										</select>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label>*Tipo:</label>
										<select class="form-control input-sm" id="tipo_negocio" name="tipo_negocio">
											<option value="">Qualquer</option>
											<option value="Novo" <?=$tipo_negocio == "Novo" ? "selected" : "";?> >Novo</option>
											<option value="Upgrade" <?=$tipo_negocio == "Upgrade" ? "selected" : "";?>>Upgrade</option>
											<option value="Downgrade" <?=$tipo_negocio == "Downgrade" ? "selected" : "";?>>Downgrade</option>
											<option value="Cancelado" <?=$tipo_negocio == "Cancelado" ? "selected" : "";?>>Cancelado</option>
											<option value="Pós-venda" <?=$tipo_negocio == "Pós-venda" ? "selected" : "";?>>Pós-venda</option>
										</select>
									</div>
								</div>
							</div>

							<div class="row" id="row5" style="display: <?=$display_row5?>;">
								<div class="col-md-6">
									<div class="form-group">
										<label>*Serviço:</label>
										<select class="form-control input-sm" id="cod_servico" name="cod_servico">
											<?php
												$dados_plano = DBRead('', 'tb_plano', "GROUP BY cod_servico ORDER BY cod_servico ASC","cod_servico");
 												if ($dados_plano) {
													echo "<option value=''>Qualquer</option>";
													foreach ($dados_plano as $conteudo) {
														$servico_select = getNomeServico($conteudo['cod_servico']);
														echo "<option value='".$conteudo['cod_servico']."'  >$servico_select</option>";
													}
												}	
											?>
										</select>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label>*Plano:</label>
										<select class="form-control input-sm" id="id_plano" name="id_plano">
											<option value=''>Qualquer</option>
										</select>
									</div>
								</div>
							</div>

							<div class="row" id="row6" style="display: <?=$display_row6?>;">
								<div class="col-md-4">
									<div class="form-group">
										<label>*Origem:</label>
										<select class="form-control input-sm" id="id_lead_origem" name="id_lead_origem">
                                        <?php
                                            $dados_origem = DBRead('','tb_lead_origem',"ORDER BY descricao");
                                            if($dados_origem){
                                            	echo "<option value=''>Qualquer</option>";
                                                foreach ($dados_origem as $conteudo_origem) {
													$selected = $id_lead_origem == $conteudo_origem['id_lead_origem'] ? "selected" : "";
                                                    echo "<option value='".$conteudo_origem['id_lead_origem']."' ".$selected.">".ucwords(strtolower($conteudo_origem['descricao']))	."</option>";
                                                }
                                            }
                                        ?>
										</select>
									</div>
								</div>
								<div class="col-md-4">
									<div class="form-group">
										<label>*Estado:</label>
										<select class="form-control input-sm" name="estado" id="estado">
                                            <option value=''>Qualquer</option>
                                            <option value='99' <?php if($estado == '99'){echo 'selected';}?>>Não Definido</option>
                                            <?php
                                            $dados = DBRead('', 'tb_estado', "WHERE id_estado != '99' ORDER BY sigla ASC");
                                            if($dados){
                                                foreach($dados as $conteudo){
                                                    $idSelect = $conteudo['id_estado'];
                                                    $estadoSelect = $conteudo['sigla'];
													$selected = $estado == $idSelect ? "selected" : "";
                                                    echo "<option value='$idSelect' ".$selected.">$estadoSelect</option>";
                                                }
                                            }
                                            ?>
                                        </select>
									</div>
								</div>
								<div class="col-md-4">
									<div class="form-group">
										<label>*Situação:</label>
										<select class="form-control input-sm" id="situacao" name="situacao">
											<option value='' <?php if ($situacao == '') {echo 'selected';}?>>Qualquer</option>
											<option value='x' <?php if ($situacao == 'x') {echo 'selected';}?>>Em Andamento</option>
											<option value='1' <?php if ($situacao == '1') {echo 'selected';}?>>Ganhou</option>
											<option value='1' <?php if ($situacao == '1') {echo 'selected';}?>>Ganhou</option>
										</select>
									</div>
								</div>
							</div>

							<div class="row" id="row7" style="display:  <?=$display_row7?>">
								<div class="col-md-6">
									<div class="form-group">
										<label>*Origem:</label>
										<select class="form-control input-sm" id="origem_andamento" name="origem_andamento">
                                        <?php
                                            $dados_origem = DBRead('','tb_lead_origem',"ORDER BY descricao");
                                            if($dados_origem){
                                            	echo "<option value=''>Qualquer</option>";
                                                foreach ($dados_origem as $conteudo_origem) {
													$selected = $origem_andamento == $conteudo_origem['id_lead_origem'] ? "selected" : "";
                                                    echo "<option value='".$conteudo_origem['id_lead_origem']."' ".$selected.">".ucwords(strtolower($conteudo_origem['descricao']))	."</option>";
                                                }
                                            }
                                        ?>
										</select>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label>*Status:</label>
										<select class="form-control input-sm" id="status_andamento" name="status_andamento">
											<option value="">Qualquer</option>
										<?php
											$dados_status = DBRead('', 'tb_lead_status', "ORDER BY descricao ASC");
											foreach ($dados_status as $conteudo_status) {
												$selected = $status_andamento == $conteudo_status['id_lead_status'] ? "selected" : "";
										?>
												<option value="<?=$conteudo_status['id_lead_status']?>" <?=$selected?> ><?=$conteudo_status['descricao']?></option>
										<?php		
											}
										?>	
										</select>
									</div>
								</div>
							</div>

	                		<div class="row">
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
  
	                		<div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Empresa/Pessoa:</label>
                                        <div class="input-group">
                                            <input class="form-control input-sm ui-autocomplete-input" id="busca_contrato" type="text" name="busca_contrato" value="" placeholder="Informe o nome ou CNPJ..." autocomplete="off" readonly="">

                                            <div class="input-group-btn">
                                                <button class="btn btn-info btn-sm" id="habilita_busca_contrato" name="habilita_busca_contrato" type="button" title="Clique para selecionar o contrato" style="height: 30px;" <?=$disabled?>><i class="fa fa-search"></i></button>
                                            </div>
                                        </div>
                                        <input type="hidden" name="id_pessoa" id="id_pessoa" value="">
                                    </div>
                                </div>
                            </div>
		                </div>
	                </div>
	                <div class="panel-footer">
	                    <div class="row">
	                        <div id="panel_buttons" class="col-md-12" style="text-align: center">
	                            <button class="btn btn-primary" name="gerar" id="gerar" value="1" type="submit"><i class="fa fa-refresh"></i> Gerar</button>
	                            <button class="btn btn-warning" name="imprimir" type="button" onclick="window.print();"><i class="fa fa-print"></i> Imprimir</button>
	                        </div>
	                    </div>
	                </div>
	            </div>
	        </div>
	    </div>
	</form>
	<div class="row">
		<?php
			if ($gerar) {
	
				if ($tipo_relatorio == 1) {

					relatorio_ganhou($data_de, $data_ate, $id_pessoa, $responsavel, $tipo_negocio, $cod_servico, $id_plano);

	            } else if ($tipo_relatorio == 2) {

					relatorio_perdeu($data_de, $data_ate, $id_pessoa, $responsavel, $tipo_negocio, $cod_servico, $id_plano, $motivo_perda);

	            } else if ($tipo_relatorio == 5) {

					relatorio_tabela_marketing($data_de, $data_ate, $id_lead_origem, $estado, $situacao, $id_pessoa);

				} else if ($tipo_relatorio == 6) {

					relatorio_em_andamento($data_de, $data_ate, $id_pessoa, $origem_andamento, $tipo_negocio, $status_andamento, $cod_servico, $id_plano);

	            } else if ($tipo_relatorio == 7) {
					relatorio_tabela_negocios();
				}
			}
			?>
	</div>
</div>

<script>

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
                        'pagina' : 'lead-negocio-form'
                    },
					token: '<?= $request->token ?>'
                },
                success: function (data) {
                    response(data);
                }
            });
        },
        focus: function (event, ui) {
            $("#busca_contrato").val(ui.item.nome);
            carregarDadosContrato(ui.item.id_pessoa);
            return false;
        },
        select: function (event, ui) {
            $("#busca_contrato").val(ui.item.nome);
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
        
        return $("<li>").append("<a><strong>"+ item.nome + "</strong><br>" +item.razao_social+ "<br>" +item.cpf_cnpj+ "<br></a><hr style='margin-bottom: 0px;'>").appendTo(ul);
    };

    // Função para carregar os dados da consulta nos respectivos campos
    function carregarDadosContrato(id){
        var busca = $('#busca_contrato').val();
        if(busca != "" && busca.length >= 2){
            $.ajax({
                url: "/api/ajax?class=ContratoAutocomplete.php",
                dataType: "json",
                data: {
                    acao: 'consulta_lead',
                    parametros: {
                        'id' : id,
                    },
					token: '<?= $request->token ?>'
                },
                success: function(data){
                    $('#id_pessoa').val(data[0].id_pessoa);
                }
            });
        }
    }

    // Função para limpar os campos caso a busca esteja vazia
    function limpaCamposContrato(){
        var busca = $('#busca_contrato').val();
        if(busca == ""){
            $('#id_pessoa').val('');
        }
    }

    $(document).on('click', '#habilita_busca_contrato', function(){
        $('#id_pesssoa').val('');
        $('#busca_contrato').val('');
        $('#busca_contrato').attr("readonly", false);
        $('#busca_contrato').focus();
    });

    $('#accordionRelatorio').on('shown.bs.collapse', function(){
       $("#i_collapse").removeClass("fa fa-plus").addClass("fa fa-minus");
    });

    $('#accordionRelatorio').on('hidden.bs.collapse', function () {
       $("#i_collapse").removeClass("fa fa-minus").addClass("fa fa-plus");
    });

	$('#tipo_relatorio').on('change', function(){
		tipo_relatorio = $(this).val();

		if(tipo_relatorio == 1){
			$('#row2').hide();
			$('#row3').hide();
			$('#row4').show();
			$('#row5').show();
			$('#row6').hide();
			$('#row7').hide();
		
		}else if(tipo_relatorio == 2){
			$('#row2').show();
			$('#row3').hide();
			$('#row7').hide();
		
		}else if(tipo_relatorio == 5){
			$('#row2').hide();
			$('#row3').hide();
			$('#row4').hide();
			$('#row5').hide();
			$('#row6').show();
			$('#row7').hide();
		}
		else if(tipo_relatorio == 6){
			$('#row2').hide();
			$('#row3').hide();
			$('#row4').hide();
			$('#row5').show();
			$('#row6').hide();
			$('#row7').show();
		}	
	});

	function selectplano(cod_servico, id_plano){        
        id_plano  = '<?=$id_plano?>';
        pagina = 'relatorio-leads';
        $("select[name=id_plano]").html('<option value="">Carregando...</option>');
        $.post("/api/ajax?class=SelectPlano.php", {cod_servico: cod_servico, id_plano: id_plano, pagina: pagina, token: '<?= $request->token ?>'},
            function(valor){
                $("select[name=id_plano]").html(valor);
                if(id_plano == 6){
                    $('#div_valor_unitario').hide();
                }
            }
        )        
    }

    $(document).on('change', 'select[name=cod_servico]', function(){
        selectplano($(this).val());
    });

</script>

<?php 
function relatorio_ganhou($data_de, $data_ate, $id_pessoa, $responsavel, $tipo_negocio, $cod_servico, $id_plano){

	$data_hora = converteDataHora(getDataHora());

	if ($data_de && $data_ate) {
		$periodo_amostra ="<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> De $data_de até $data_ate</span>";
		
	} else if ($data_de) {
		$periodo_amostra ="<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> A partir de $data_de</span>";
		
	} else if ($data_ate) {
		$periodo_amostra ="<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> Até $data_ate</span>";
		
	} else {
	    $periodo_amostra = "<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> Qualquer</span>";
	}

	if ($id_pessoa) {
		$filtro_pessoa = " AND a.id_pessoa = $id_pessoa";
		$nome = DBRead('', 'tb_pessoa', "WHERE id_pessoa = $id_pessoa", 'nome');
		$empresa_legenda = $nome[0]['nome'];

	} else {
		$empresa_legenda = 'Todas';
	}

	if ($responsavel != '') {
		$filtro_responsavel = 'AND a.id_usuario_responsavel = '.$responsavel;
	}

	if ($tipo_negocio != '') {
		$filtro_tipo_negocio = 'AND a.tipo_negocio = "'.$tipo_negocio.'"';
	}

	if ($id_plano != '') {
		$filtro_plano = ' AND a.id_plano = '.$id_plano;
	}

	if ($cod_servico != '') {
		$filtro_servico = 'AND f.cod_servico = "'.$cod_servico.'" ';
		$legenda_servico = getNomeServico($cod_servico);

	} else {
		$legenda_servico = "Qualquer";
	}
	
	echo "<div class=\"col-md-12\" style=\"padding: 0\">";
	echo "<legend style=\"text-align:center;\"><strong>Relatório de Negócios Ganhos</strong><br><span style=\"font-size: 14px;\"><strong>Gerado em:</strong> $data_hora</span></legend>";	
	echo "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\">".$periodo_amostra."</legend>";
	echo "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong> Serviço - </strong>".$legenda_servico.", <strong> Empresa/Pessoa - </strong>".$empresa_legenda.", <strong> Tipo - </strong>".$tipo_negocio."</legend>";
	
	$filtro = '';
    if ($data_de) {
		$filtro .= " AND a.data_conclusao >= '".converteData($data_de)."'";
	}
	if ($data_ate) {
		$filtro .= " AND a.data_conclusao <= '".converteData($data_ate)."'";
	}

	$dados = DBRead('', 'tb_lead_negocio a', "INNER JOIN tb_usuario b ON a.id_usuario_responsavel = b.id_usuario INNER JOIN tb_pessoa c ON b.id_pessoa = c.id_pessoa INNER JOIN tb_lead_status d ON a.id_lead_status = d.id_lead_status INNER JOIN tb_pessoa e ON a.id_pessoa = e.id_pessoa LEFT JOIN tb_plano f ON a.id_plano = f.id_plano INNER JOIN tb_cidade g ON e.id_cidade = g.id_cidade WHERE a.andamento = 1 $filtro $filtro_pessoa $filtro_responsavel $filtro_tipo_negocio $filtro_plano $filtro_servico",'a.id_lead_negocio, a.id_plano, a.descricao AS negocio_descricao, a.valor_contrato, a.valor_adesao, a.data_inicio, a.data_conclusao, a.tipo_negocio, c.nome AS nome_responsavel, d.descricao AS status_descricao, e.nome AS nome_lead, f.cod_servico, f.nome AS nome_plano, a.id_lead_status, a.obs_ganhou');

	$contador_dados = 0;
	$total_contratos = 0;
	$total_adesoes = 0;

	if ($dados) {
		
		echo "<div class='row'>";
        	echo "<div class='col-xs-12'>";
            echo "<table class='table table-hover dataTable' style='font-size='14px'>";
			echo "<thead>";
				echo "<tr>";
					echo "<th>Empresa/Pessoa</th>";
					echo "<th>Tipo do negócio</th>";
					echo "<th>Data início</th>";
					echo "<th>Responsável</th>";
					echo "<th>Valor contrato</th>";
					echo "<th>Valor adesão</th>";
					echo "<th>Serviço</th>";
					echo "<th>Plano</th>";
					echo "<th>Data conclusão</th>";
					echo "<th>Descrição</th>";
					echo "<th>Observação</th>";
				echo "</tr>";
			echo "</thead>";
			echo "<tbody>";

		foreach($dados as $dado){

			$servico = getNomeServico($dado['cod_servico']);
			$plano = $dado['nome_plano'];

			if($servico == ''){
				$servico = 'N/D';
			}

			if($plano == ''){
				$plano = 'N/D';
			}

			echo "<tr>";
				echo "<td>".$dado['nome_lead']."</td>";
				echo "<td>".$dado['tipo_negocio']."</td>";
				echo "<td>".converteData($dado['data_inicio'])."</td>";
				echo "<td>".$dado['nome_responsavel']."</td>";
				echo "<td>".converteMoeda($dado['valor_contrato'])."</td>";
				echo "<td>".converteMoeda($dado['valor_adesao'])."</td>";
				echo "<td>".$servico."</td>";
				echo "<td>".$plano."</td>";
				echo "<td>".converteData($dado['data_conclusao'])."</td>";
				echo "<td>".$dado['negocio_descricao']."</td>";
				echo "<td>".$dado['obs_ganhou']."</td>";
			echo "</tr>";
			
			$contador_dados++;
			$total_contratos += $dado['valor_contrato'];
			$total_adesoes += $dado['valor_adesao'];
		}
		
		echo "</tbody>";
		echo "<tfoot>";
		echo "<tr>";
			echo "<th>Total: $contador_dados</th>";
			echo "<th></th>";
			echo "<th></th>";
			echo "<th></th>";
			echo "<th>R$".converteMoeda($total_contratos)."</th>";
			echo "<th>R$".converteMoeda($total_adesoes)."</th>";
			echo "<th></th>";
			echo "<th></th>";
			echo "<th></th>";
			echo "<th></th>";
			echo "<th></th>";
		echo "</tr>";
		echo "</tfoot>";
		echo "</table>";
		echo "</div>";
		echo "</div>";
		
	} else {
		echo "<table class='table table-bordered'>";
			echo "<tbody>";
				echo "<tr>";
					echo "<td class='text-center'> <h4>Não foram encontrados resultados!</h4></td>";
				echo "</tr>";
			echo "</tbody>";
		echo "</table>";
	}
}

function relatorio_perdeu($data_de, $data_ate, $id_pessoa, $responsavel, $tipo_negocio, $cod_servico, $id_plano, $motivo_perda){

	$data_hora = converteDataHora(getDataHora());

	if ($data_de && $data_ate) {
		$periodo_amostra ="<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> De $data_de até $data_ate</span>";
		
	} else if ($data_de) {
		$periodo_amostra ="<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> A partir de $data_de</span>";
		
	} elseif ($data_ate) {
		$periodo_amostra ="<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> Até $data_ate</span>";
		
	} else {
	    $periodo_amostra = "<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> Qualquer</span>";
	}

	if ($id_pessoa) {
		$filtro_pessoa = " AND a.id_pessoa = $id_pessoa";
		$nome = DBRead('', 'tb_pessoa', "WHERE id_pessoa = $id_pessoa", 'nome');
		$empresa_legenda = $nome[0]['nome'];

	} else {
		$empresa_legenda = 'Todas';
	}

	if ($responsavel != '') {
		$filtro_responsavel = 'AND a.id_usuario_responsavel = '.$responsavel;
	}

	if ($tipo_negocio != '') {
		$filtro_tipo_negocio = 'AND a.tipo_negocio = "'.$tipo_negocio.'"';
	}

	if ($id_plano != '') {
		$filtro_plano = ' AND a.id_plano = '.$id_plano;
	}

	if ($cod_servico != '') {
		$filtro_servico = 'AND f.cod_servico = "'.$cod_servico.'" ';
		$legenda_servico = getNomeServico($cod_servico);

	} else {
		$legenda_servico = "Qualquer";
	}

	if ($motivo_perda != '') {
		$filtro_motivo_perda = 'AND i.id_lead_motivo_perda = "'.$motivo_perda.'" ';
	}
	
	echo "<div class=\"col-md-12\" style=\"padding: 0\">";
	echo "<legend style=\"text-align:center;\"><strong>Relatório de Negócios Perdidos</strong><br><span style=\"font-size: 14px;\"><strong>Gerado em:</strong> $data_hora</span>, ";	
	echo "<span style=\"font-size: 14px;\">".$periodo_amostra.", ";
	echo "<strong> Serviço - </strong>".$legenda_servico.", ";
	echo "<strong> Empresa/Pessoa - </strong>".$empresa_legenda.", ";
	echo "<strong> Tipo - </strong>".$tipo_negocio."</legend>";

	$filtro = '';
    if ($data_de) {
		$filtro .= " AND a.data_conclusao >= '".converteData($data_de)."'";
	}
	if ($data_ate) {
		$filtro .= " AND a.data_conclusao <= '".converteData($data_ate)."'";
	}

	$dados = DBRead('', 'tb_lead_negocio a', "INNER JOIN tb_usuario b ON a.id_usuario_responsavel = b.id_usuario INNER JOIN tb_pessoa c ON b.id_pessoa = c.id_pessoa INNER JOIN tb_lead_status d ON a.id_lead_status = d.id_lead_status INNER JOIN tb_pessoa e ON a.id_pessoa = e.id_pessoa LEFT JOIN tb_plano f ON a.id_plano = f.id_plano INNER JOIN tb_cidade g ON e.id_cidade = g.id_cidade INNER JOIN tb_lead_negocio_perdido h ON a.id_lead_negocio = h.id_lead_negocio INNER JOIN tb_lead_motivo_perda i ON h.id_lead_motivo_perda = i.id_lead_motivo_perda WHERE a.andamento = 2 $filtro $filtro_pessoa $filtro_responsavel $filtro_tipo_negocio $filtro_plano $filtro_servico $filtro_motivo_perda",'a.id_lead_negocio, a.id_plano, a.descricao AS negocio_descricao, a.valor_contrato, a.valor_adesao, a.data_inicio, a.data_conclusao, a.tipo_negocio, c.nome AS nome_responsavel, d.descricao AS status_descricao, e.nome AS nome_lead, f.cod_servico, f.nome AS nome_plano, a.id_lead_status, i.descricao AS motivo_perda, h.observacao');

	$contador_dados = 0;
	$total_contratos = 0;
	$total_adesoes = 0;

	if ($dados) {
		
		echo "<div class='row'>";
        	echo "<div class='col-xs-12'>";
            echo "<table class='table table-hover dataTable' style='font-size='14px'>";
			echo "<thead>";
				echo "<tr>";
					echo "<th>Empresa/Pessoa</th>";
					echo "<th>Tipo do negócio</th>";
					echo "<th>Data início</th>";
					echo "<th>Responsável</th>";
					echo "<th>Valor contrato</th>";
					echo "<th>Valor adesão</th>";
					echo "<th>Serviço</th>";
					echo "<th>Plano</th>";
					echo "<th>Data conclusão</th>";
					echo "<th>Descrição</th>";
					echo "<th>Motivo perda</th>";
					echo "<th>Observação</th>";
				echo "</tr>";
			echo "</thead>";
			echo "<tbody>";

		foreach($dados as $dado){

			$servico = getNomeServico($dado['cod_servico']);
			$plano = $dado['nome_plano'];

			if($servico == ''){
				$servico = 'N/D';
			}

			if($plano == ''){
				$plano = 'N/D';
			}

			echo "<tr>";
				echo "<td>".$dado['nome_lead']."</td>";
				echo "<td>".$dado['tipo_negocio']."</td>";
				echo "<td>".converteData($dado['data_inicio'])."</td>";
				echo "<td>".$dado['nome_responsavel']."</td>";
				echo "<td>".converteMoeda($dado['valor_contrato'])."</td>";
				echo "<td>".converteMoeda($dado['valor_adesao'])."</td>";
				echo "<td>".$servico."</td>";
				echo "<td>".$plano."</td>";
				echo "<td>".converteData($dado['data_conclusao'])."</td>";
				echo "<td>".$dado['negocio_descricao']."</td>";
				echo "<td>".$dado['motivo_perda']."</td>";
				echo "<td>".$dado['observacao']."</td>";
			echo "</tr>";
			
			$contador_dados++;
			$total_contratos += $dado['valor_contrato'];
			$total_adesoes += $dado['valor_adesao'];
		}
		
		echo "</tbody>";
		echo "<tfoot>";
		echo "<tr>";
			echo "<th>Total: $contador_dados</th>";
			echo "<th></th>";
			echo "<th></th>";
			echo "<th></th>";
			echo "<th>R$".converteMoeda($total_contratos)."</th>";
			echo "<th>R$".converteMoeda($total_adesoes)."</th>";
			echo "<th></th>";
			echo "<th></th>";
			echo "<th></th>";
			echo "<th></th>";
			echo "<th></th>";
			echo "<th></th>";
		echo "</tr>";
		echo "</tfoot>";
		echo "</table>";
		echo "</div>";
		echo "</div>";
		
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
			";
	} else {
		echo "<table class='table table-bordered'>";
			echo "<tbody>";
				echo "<tr>";
					echo "<td class='text-center'> <h4>Não foram encontrados resultados!</h4></td>";
				echo "</tr>";
			echo "</tbody>";
		echo "</table>";
	}
}

function relatorio_em_andamento($data_de, $data_ate, $id_pessoa, $origem_andamento, $tipo_negocio, $status_andamento, $cod_servico, $id_plano){

	$data_hora = converteDataHora(getDataHora());

	if ($data_de && $data_ate) {
		$periodo_amostra ="<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> De $data_de até $data_ate</span>";
		
	} else if ($data_de) {
		$periodo_amostra ="<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> A partir de $data_de</span>";
		
	} else if ($data_ate) {
		$periodo_amostra ="<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> Até $data_ate</span>";
		
	} else {
	    $periodo_amostra = "<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> Qualquer</span>";
	}

	if ($origem_andamento) {
		$filtro_origem = " AND c.id_lead_origem = $origem_andamento";
	}

	if ($status_andamento) {
		$filtro_status = " AND a.id_lead_status = $status_andamento";
	}
	$filtro_data = '';
	if ($data_de) {
		$filtro_data .= " AND a.data_inicio >= '".converteData($data_de)."'";
	}
	if ($data_ate) {
		$filtro_data .= " AND a.data_inicio <= '".converteData($data_ate)."'";
	}

	if ($id_plano != ''){
		$filtro_plano = ' AND a.id_plano = '.$id_plano;
	}

	if ($cod_servico != ''){
		$filtro_servico = 'AND g.cod_servico = "'.$cod_servico.'" ';
		$legenda_servico = getNomeServico($cod_servico);

	} else {
		$legenda_servico = "Qualquer";
	}

	echo "<div class=\"col-md-12\" style=\"padding: 0\">";
	echo "<legend style=\"text-align:center;\"><strong>Relatório de Negócios em Andamento</strong><br><span style=\"font-size: 14px;\"><strong>Gerado em:</strong> $data_hora</span></legend>";	
	echo "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\">".$periodo_amostra."</legend>";
	echo "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong> Serviço - </strong>".$legenda_servico."</legend>";

	$dados = DBRead('', 'tb_lead_negocio a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa LEFT JOIN tb_pessoa_prospeccao c ON b.id_pessoa = c.id_pessoa LEFT JOIN tb_lead_segmento d ON c.segmento = d.id_lead_segmento LEFT JOIN tb_lead_origem e ON c.id_lead_origem = e.id_lead_origem INNER JOIN tb_lead_status f ON a.id_lead_status = f.id_lead_status LEFT JOIN tb_plano g ON a.id_plano = g.id_plano WHERE a.andamento = 0 $filtro_data $filtro_origem $filtro_status $filtro_plano $filtro_servico ORDER BY a.data_inicio ASC", 'a.tipo_negocio, a.id_lead_status, a.id_plano, a.data_inicio,a.data_conclusao, b.nome, b.email1, c.quantidade_clientes, c.terceirizacao_atendimento, c.qualificacao_cliente, c.reclamacoes_redes_sociais, c.horario_mais_ligacoes, d.nome as segmento, e.descricao as origem, f.descricao as status, g.cod_servico');

	$contador_dados = 0;

	if ($dados) {
		
		foreach ($dados as $conteudo) {

			$id = $conteudo['id_lead_negocio'];
			$nome = $conteudo['nome'];
			$descricao_origem = $conteudo['descricao_origem'];
			$email = $conteudo['email1'];
			$segmento = $conteudo['segmento'];
			$origem = $conteudo['origem'];
			$quantidade_clientes = $conteudo['quantidade_clientes'];
			$terceirizacao_atendimento = $conteudo['terceirizacao_atendimento'];
			$qualificacao_cliente = $conteudo['qualificacao_cliente'];
			$reclamacoes_redes_sociais = $conteudo['reclamacoes_redes_sociais'];
			$horario_mais_ligacoes = $conteudo['horario_mais_ligacoes'];
			$tipo_negocio = $conteudo['tipo_negocio'];
			$status = $conteudo['status'];
			$cod_servico = $conteudo['cod_servico'];
			$data_inicio = converteData($conteudo['data_inicio']);
			$data_conclusao = converteData($conteudo['data_conclusao']);

			if ($terceirizacao_atendimento == 1) {
				$terceirizacao = 'Redução de custo';

			} else if ($terceirizacao_atendimento == 2) {
				$terceirizacao = 'Melhor Qualidade';

			} else if ($terceirizacao_atendimento == 3) {
				$terceirizacao = 'Outros';

			} else if ($terceirizacao_atendimento == 4) {
				$terceirizacao = 'Falta de pessoas qualificadas para fazer internamente';

			} else if ($terceirizacao_atendimento == 5) {
				$terceirizacao = 'Falta de tempo para gerir equipe interna';

			} else if ($terceirizacao_atendimento == 6) {
				$terceirizacao = 'É mais barato terceirizar';
			}

			if ($qualificacao_cliente == '') {
				$qualificacao_cliente = 'N/D'; 
			}

			if ($quantidade_clientes == '') {
				$quantidade_clientes = 'N/D'; 
			}

			if ($reclamacoes_redes_sociais == '') {
				$reclamacoes_redes_sociais = 'N/D'; 
			}

			if ($horario_mais_ligacoes == '') {
				$horario_mais_ligacoes = 'N/D'; 
			}
			
			$data_pausa = $conteudo['data_pausa'];
			$data_lembrete = $conteudo['data_lembrete'];

			if($segmento == ''){
				$segmento = 'N/D';
			}

			if($origem == ''){
				$origem = 'N/D';
			}

			$servico = getNomeServico($conteudo['cod_servico']);
		
		?>
			<div class="panel panel-primary" style="border: 1px solid #0B4C5F;;">
				<div class="panel-heading clearfix" style="background: #0B4C5F;">
					<div class="row">
						<h3 class="panel-title text-left col-xs-6"><strong>Nome:</strong> <?=$nome?></h3>
					</div>
				</div>
				<div class="panel-body painel-body-externo">
					<table class="table table-bordered">
						<tbody>
							<tr>
								<td class="col-md-4 active">
									<strong>Email:</strong> <?=$email?>
								</td>
								<td class="col-md-5 active">
									<strong>Segmento: </strong><?=$segmento?>
								</td>
								<td class="col-md-3 active">
									<strong>Origem: </strong><?=$origem?>
								</td>
							</tr>
							<tr>
								<td class="col-md-4 active">
									<strong>Quantidade de clientes: </strong> <?=$quantidade_clientes?>
								</td>
								<td class="col-md-5 active">
									<strong>Por que procura a terceirização do atendimento: </strong> <?=$terceirizacao?>
								</td>
								<td class="col-md-3 active">
									<strong>Satisfação dos clientes (0 a 10): </strong> <?=$qualificacao_cliente?> 
								</td>
							</tr>
							<tr>
								<td class="col-md-4 active">
									<strong>Reclamações nas redes sociais (0 a 5): </strong> <?=$reclamacoes_redes_sociais?>
								</td>
								<td class="col-md-5 active">
									<strong>Horários que mais tem ligações: </strong> <?=$horario_mais_ligacoes?>
								</td>
								<td class="col-md-3 active">
									
								</td>
							</tr>
							<tr>
								<td class="col-md-4 info">
									<strong>Tipo do negócio: </strong> <?=$tipo_negocio?>
								</td>
								<td class="col-md-5 info">
									<strong>Status: </strong> <?=$status?>
								</td>
								<td class="col-md-3 info">
									<strong>Serviço: </strong> <?=$servico?>
								</td>
							</tr>
							<tr>
								<td class="col-md-4 info">
									<strong>Data de início: </strong> <?=$data_inicio?>
								</td>
								<td class="col-md-5 info">
									<strong>Data de conclusão: </strong> <?=$data_conclusao?>
								</td>
								<td class="col-md-3 info"></td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
			<hr>
		<?php
			
			$contador_dados++;
		}

		echo "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong>Total: </strong> $contador_dados<br><br></span></legend>";
		
	} else {
		echo "<table class='table table-bordered'>";
			echo "<tbody>";
				echo "<tr>";
					echo "<td class='text-center'> <h4>Não foram encontrados resultados!</h4></td>";
				echo "</tr>";
			echo "</tbody>";
		echo "</table>";
	}
}

function relatorio_tabela_marketing($data_de, $data_ate, $origem, $estado, $situacao, $id_pessoa){

    if ($data_de && $data_ate) {
		$periodo_amostra = "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> De $data_de até $data_ate</span></legend>";
		
    } else if ($data_de) {
		$periodo_amostra ="<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> A partir de $data_de</span></legend>";
		
	} else if ($data_ate) {
		$periodo_amostra ="<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> Até $data_ate</span></legend>";
		
	} else {
	    $periodo_amostra = "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> Qualquer</span></legend>";
	}

    if ($origem) {
    	$dados_origem = DBRead('', 'tb_lead_origem', "WHERE id_lead_origem = '".$origem."' ");
    	$legenda_origem = $dados_origem[0]['descricao'];
    	$filtro_origem = " AND c.id_lead_origem = '".$origem."' ";

    } else {
    	$legenda_origem = 'Qualquer';
    }

    if ($situacao) {
    	if ($situacao == 1) {
    		$legenda_situacao = 'Ganhou';
			$filtro_situacao = " AND a.andamento = '".$situacao."' ";
			
    	} else if ($situacao == 2) {
    		$legenda_situacao = 'Perdeu';
			$filtro_situacao = " AND a.andamento = '".$situacao."' ";
			
    	} else if ($situacao == 'x'){
    		$legenda_situacao = 'Em Andamento';
    		$filtro_situacao = " AND a.andamento = '0' ";
    	}

    } else {
    	$legenda_situacao = 'Qualquer';
    	$filtro_situacao = "";
    }

    if ($estado) {
    	$dados_estado = DBRead('', 'tb_estado', "WHERE id_estado = '".$estado."' ");
        $legenda_estado = $dados_estado[0]['nome'];
		$filtro_estado = " AND d.id_estado = '$estado'";
		
    } else {
    	$legenda_estado = 'Qualquer';
    }

    if ($id_pessoa) {
    	$dados_id_pessoa = DBRead('', 'tb_pessoa', "WHERE id_pessoa = '".$id_pessoa."' ");
		$legenda_id_pessoa = $dados_id_pessoa[0]['nome'];
		
    } else {
    	$legenda_id_pessoa = 'Qualquer';
    }
	
	$data_ate = converteData($data_ate);
    $data_de = converteData($data_de);

    $filtro_data = '';
    if ($data_de) {
		$filtro_data .= " AND a.data_conclusao >= '".$data_de."'";
	}
	if ($data_ate) {
		$filtro_data .= " AND a.data_conclusao <= '".$data_ate."'";
	}

    echo "<div class=\"col-md-10 col-md-offset-1\" style=\"padding: 0\">";
    echo "<legend style=\"text-align:center;\"><strong>Relatório de Leads - Tabela</strong><br><span style=\"font-size: 14px;\"><strong>Gerado em:</strong>".converteDataHora(getDataHora())."
	</span></legend>";
    echo "$periodo_amostra";
    echo "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong> Origem - </strong>".$legenda_origem.",<strong> Estado - </strong>".$legenda_estado.",<strong> Situação - </strong>".$legenda_situacao.",<strong> Empresa/Pessoa - </strong>".$legenda_id_pessoa."</legend>";

    $dados_lead = DBRead('', 'tb_lead_negocio a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa LEFT JOIN tb_pessoa_prospeccao c ON a.id_pessoa = c.id_pessoa INNER JOIN tb_cidade d ON b.id_cidade = d.id_cidade INNER JOIN tb_estado e ON d.id_estado = e.id_estado LEFT JOIN tb_lead_origem f ON c.id_lead_origem = f.id_lead_origem INNER JOIN tb_lead_status g ON a.id_lead_status = g.id_lead_status WHERE id_lead_negocio ".$filtro_data." ".$filtro_origem." ".$filtro_situacao." $filtro_estado", "a.*, b.*, c.quantidade_clientes, e.nome AS nome_estado, f.descricao AS descricao_origem, g.descricao AS descricao_status, g.id_lead_status");

    if ($dados_lead) {
    	$qtd_estados = array();
    	$qtd_origem = array();
    	$qtd_situacao = array();

    	echo "
			<table class=\"table table-hover dataTable_expo\"> 
				<thead> 
					<tr> 
    					<th class='text-left col-md-2'>Nome da Empresa/Pessoa</th>
    					<th class='text-left>Origem</th>
    					<th class='text-left col-md-2'>Estado</th>
    					<th class='text-left'>Qtd. de Clientes</th>
    					<th class='text-left'>Tipo negócio</th>
    					<th class='text-left'>Situação</th>
    					<th class='text-left'>Status</th>
    					<th class='text-left'>Valor contrato</th>
    					<th class='text-left'>Valor (redução|aumento)</th>
						<th class='text-left'>Data Início</th>
    					<th class='text-left'>Data Conclusão/Atualização</th>
    					<th class='text-left'>Origem</th>
					</tr>
				</thead> 
				<tbody>";		 

		$saldo = 0;
		$total_valor_contrato = 0;
		$valor = 0;
	    foreach ($dados_lead as $conteudo_lead) {
			
	    	$nome_pessoa = $conteudo_lead['nome'];

	    	$origem_descricao = $conteudo_lead['descricao_origem'];

	    	$qtd_clientes = $conteudo_lead['quantidade_clientes'];

			$status = $conteudo_lead['descricao_status'];

	    	if(!$qtd_clientes){
	    		$qtd_clientes = '-';
	    	}

	    	$nome_estado = $conteudo_lead['nome_estado'];

	    	if($conteudo_lead['andamento'] == 1){
	    		$situacao_descricao = 'Ganhou';

	    	}else if($conteudo_lead['andamento'] == 2){
	    		$situacao_descricao = 'Perdeu';

	    	}else if($conteudo_lead['andamento'] == 0){
	    		$situacao_descricao = 'Em Andamento';

	    	}else{
	    		$situacao_descricao = $conteudo_lead['andamento'];
            }

            $data_inicio = $conteudo_lead['data_inicio'];

            if($conteudo_lead['andamento'] == 0){                
                $timeline = DBRead('', 'tb_lead_timeline', "WHERE id_lead_negocio = '".$conteudo_lead['id_lead_negocio']."' ORDER BY data DESC LIMIT 1", 'data');
                if($timeline){
                    $data_conclusao = substr($timeline[0]['data'], 0, 10);
                }else{
                    $data_conclusao = $data_inicio;
                }
            }else{
                $data_conclusao = $conteudo_lead['data_conclusao'];
            }

			if ($conteudo_lead['id_lead_status'] == 15 && $conteudo_lead['andamento'] == 2) {
				$valor = '-'.$conteudo_lead['valor_contrato'];
				$saldo -= $conteudo_lead['valor_contrato'];

			} else if ($conteudo_lead['andamento'] == 1) {
				$valor_contrato = $conteudo_lead['valor_contrato'];
				$total_valor_contrato += $conteudo_lead['valor_contrato'];
				$valor = 0;

			} else if ($conteudo_lead['tipo_negocio'] == 'Downgrade') {
				$valor = '-'.$conteudo_lead['valor_reducao'];
				$saldo -= $conteudo_lead['valor_reducao'];

			} else if ($conteudo_lead['tipo_negocio'] == 'Upgrade') {

				if ($conteudo_lead['valor_aumento'] == '') {
					$valor = 0;
				} else {
					$valor = $conteudo_lead['valor_aumento'];
				}
				$saldo += $conteudo_lead['valor_aumento'];

			} else {
				$valor_contrato = $conteudo_lead['valor_contrato'];
				$valor = 0;
			}

	    	echo '<tr>
			    	<td>'.$nome_pessoa.'-'.$conteudo_lead['id_lead_negocio'].'</td>
			    	<td>'.$nome_estado.'</td>
			    	<td>'.$qtd_clientes.'</td>
			    	<td>'.$conteudo_lead['tipo_negocio'].'</td>
                    <td>'.$situacao_descricao.'</td>
                    <td>'.$status.'</td>
                    <td> R$ '.converteMoeda($valor_contrato).'</td>
                    <td> R$ '.converteMoeda($valor).'</td>
                    <td data-order="'.$data_inicio.'">'.converteData($data_inicio).'</td>
                    <td data-order="'.$data_conclusao.'">'.converteData($data_conclusao).'</td>
                    <td>'.$origem_descricao.'</td>
            ';
			echo '</tr>';

			$qtd_estados[$nome_estado] += 1;
			$qtd_origem[$origem_descricao] += 1;
			$qtd_situacao[$situacao_descricao] += 1;

	    }
		   			    	
		echo "
			</tbody>
			<tfoot>
				<tr class='active'>
					<th>Saldo</th>
					<th></th>
					<th></th>
					<th></th>
					<th></th>
					<th></th>
					<th>R$ ".converteMoeda($total_valor_contrato)."</th>
					<th>R$ ".converteMoeda($saldo)."</th>
					<th></th>
					<th></th>
					<th></th>
				</tr>
			</tfoot>
		</table>
		<hr>
		";	


	echo "
	<div class = 'row'>";

		if ($qtd_estados) {

			echo '
			<div class = "col-md-4">
				<table class="table table-hover dataTable" style="margin-bottom:0;">
			      	<thead>
			        	<tr>
				            <th class="text-left col-md-6">Estado</th>
				            <th class="text-left col-md-6">Total</th>
			        	</tr>
			      	</thead>
			      	<tbody>';  

				 	arsort($qtd_estados);   
				  	foreach ($qtd_estados as $estados => $qtd) {
					    echo '<tr>';
						    echo '<td>'.$estados.'</td>';
						    echo '<td>'.$qtd.'</td>';
					    echo '</tr>';  

					  }
		          	echo 
		          	'</tbody>';					          
				echo 
				'</table>
			</div>
			';       
		}

		if ($qtd_origem) {

			echo '
			<div class = "col-md-4">
				<table class="table table-hover dataTable" style="margin-bottom:0;">
			      	<thead>
			        	<tr>
				            <th class="text-left col-md-6">Origem</th>
				            <th class="text-left col-md-6">Total</th>
			        	</tr>
			      	</thead>
			      	<tbody>';  

				 	arsort($qtd_origem);   
				  	foreach ($qtd_origem as $origens => $qtd) {
					    echo '<tr>';
						    echo '<td>'.$origens.'</td>';
						    echo '<td>'.$qtd.'</td>';
					    echo '</tr>';  

					  }
		          	echo 
		          	'</tbody>';					          
				echo 
				'</table>
			</div>
			';       
		}	 

		if ($qtd_situacao) {

			echo '
			<div class = "col-md-4">
				<table class="table table-hover dataTable" style="margin-bottom:0;">
			      	<thead>
			        	<tr>
				            <th class="text-left col-md-6">Situação</th>
				            <th class="text-left col-md-6">Total</th>
			        	</tr>
			      	</thead>
			      	<tbody>';  

				 	arsort($qtd_situacao);   
				  	foreach ($qtd_situacao as $situacoes => $qtd) {
					    echo '<tr>';
						    echo '<td>'.$situacoes.'</td>';
						    echo '<td>'.$qtd.'</td>';
					    echo '</tr>';  

					  }
		          	echo 
		          	'</tbody>';					          
				echo 
				'</table>
			</div>
			';      
		}	
		echo "<hr>
	<div/>
	<br><br><br>";

	echo "
	<script>
		$(document).ready(function(){
			$('.dataTable').DataTable({
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

	echo "<script>
			$(document).ready(function(){
				var table = $('.dataTable_expo').DataTable({
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
							text: '<i class=\"fa fa-file-excel-o\" aria-hidden=\"true\"></i> Exportar',
							filename: 'relatorio_leads',
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
}

function relatorio_tabela_negocios(){

    echo "<div class=\"col-md-10 col-md-offset-1\" style=\"padding: 0\">";
    echo "<legend style=\"text-align:center;\"><strong>Relatório de Leads - Tabela</strong><br><span style=\"font-size: 14px;\"><strong>Gerado em: </strong>".converteDataHora(getDataHora())."
	</span></legend>";

    $dados_lead = DBRead('', 'tb_lead_negocio a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa LEFT JOIN tb_pessoa_prospeccao c ON a.id_pessoa = c.id_pessoa LEFT JOIN tb_plano d ON a.id_plano = d.id_plano LEFT JOIN tb_lead_origem f ON c.id_lead_origem = f.id_lead_origem INNER JOIN tb_lead_status g ON a.id_lead_status = g.id_lead_status ORDER BY b.nome ASC", "a.*, b.*, c.quantidade_clientes, f.descricao AS descricao_origem, g.descricao AS descricao_status, g.id_lead_status, d.nome AS nome_plano, d.cod_servico");

    
    if ($dados_lead) {

    	echo "
			<table class=\"table table-hover dataTable_expo\"> 
				<thead> 
					<tr> 
    					<th class='text-left col-md-2'>Nome</th>
    					<th class='text-left col-md-2'>Email</th>
    					<th class='text-left'>Telefone</th>
    					<th class='text-left'>Produto de interesse</th>
    					<th class='text-left'>Plano de interesse</th>
    					<th class='text-left'>Fase</th>
    					<th class='text-left'>Status</th>
    					<th class='text-left'>Valor contrato</th>
    					<th class='text-left'>Último contato realizado</th>
    					<th class='text-left'>Observações</th>";

						for ($i=1; $i<=59; $i++) {
							echo '<th class="text-left">Interação '.$i.'</th>';
						} 

		echo "</tr>
				</thead> 
				<tbody>";		 

		$saldo = 0;
		$total_valor_contrato = 0;
		$valor = 0;
		$total_timeline = 0;
	    foreach ($dados_lead as $conteudo_lead) {

			$id_lead_negocio = $conteudo_lead['id_lead_negocio'];
			
	    	$nome_pessoa = $conteudo_lead['nome'];

	    	$email = $conteudo_lead['email1'];

	    	$telefone = $conteudo_lead['fone1'];

			$status = $conteudo_lead['descricao_status'];

			$status_planilha = '';
			if ($status == 'Prospect') {
				$status_planilha = 'Entrar em contato';

			} else if ($status == 'MQL') {
				$status_planilha = 'Qualificar';

			} else if ($status == 'SQL de proposta' || $status == 'Reunião Agendada') {
				$status_planilha = 'Fazer Reunião';

			} else if ($status == 'Proposta Enviada') {
				$status_planilha = 'Negociar';

			} else if ($status == 'Follow up 1' || $status == 'Follow up 2') {
				$status_planilha = 'Fazer Contato';

			} else if ($status == 'Link de Cadastro' || $status == 'Contrato Pendente' || $status == 'Boleto Pendente') {
				$status_planilha = 'Aguardar Pagamento';

			} else if ($status == 'Cancelado') {
				$status_planilha = 'Cancelado';
			}

			$servico = getNomeServico($conteudo_lead['cod_servico']);

			if ($servico == '') {
				$servico = 'N/D';
			}

	    	if ($conteudo_lead['andamento'] == 1) {
	    		$situacao_descricao = 'Ganhou';

	    	} else if ($conteudo_lead['andamento'] == 2) {
	    		$situacao_descricao = 'Perdeu';

	    	} else if ($conteudo_lead['andamento'] == 0) {
	    		$situacao_descricao = 'Em Andamento';

	    	} else {
	    		$situacao_descricao = $conteudo_lead['andamento'];
            }

            $data_inicio = $conteudo_lead['data_inicio'];

            if ($conteudo_lead['andamento'] == 0) {                
                $timeline = DBRead('', 'tb_lead_timeline', "WHERE id_lead_negocio = '".$conteudo_lead['id_lead_negocio']."' ORDER BY data DESC LIMIT 1", 'data');
                if($timeline){
                    $data_conclusao = substr($timeline[0]['data'], 0, 10);
                }else{
                    $data_conclusao = $data_inicio;
                }
            } else {
                $data_conclusao = $conteudo_lead['data_conclusao'];
            }

			if ($conteudo_lead['id_lead_status'] == 15 && $conteudo_lead['andamento'] == 2) {
				$valor = '-'.$conteudo_lead['valor_contrato'];
				$saldo -= $conteudo_lead['valor_contrato'];

			} else if ($conteudo_lead['andamento'] == 1) {
				$valor_contrato = $conteudo_lead['valor_contrato'];
				$total_valor_contrato += $conteudo_lead['valor_contrato'];
				$valor = 0;

			} else if ($conteudo_lead['tipo_negocio'] == 'Downgrade') {
				$valor = '-'.$conteudo_lead['valor_reducao'];
				$saldo -= $conteudo_lead['valor_reducao'];

			} else if ($conteudo_lead['tipo_negocio'] == 'Upgrade') {

				if ($conteudo_lead['valor_aumento'] == '') {
					$valor = 0;
				} else {
					$valor = $conteudo_lead['valor_aumento'];
				}
				$saldo += $conteudo_lead['valor_aumento'];

			} else {
				$valor_contrato = $conteudo_lead['valor_contrato'];
				$valor = 0;
			}

			if ($conteudo_lead['tipo_negocio'] == 'Upgrade') {
				$produto = 'Aumento de vendas';

			} else if ($conteudo_lead['cod_servico'] == 'call_suporte') {
				$produto = 'Contact Center';

			} else if ($conteudo_lead['cod_servico'] == 'call_ativo') {
				$produto = 'Encantamento de clientes';

			} else if ($conteudo_lead['cod_servico'] == 'gestao_redes') {
				$produto = 'Redes';

			} else {
				$produto = 'N/D';
			}

			if ($conteudo_lead['nome_plano'] == '') {
				$nome_plano = 'N/D';

			} else {
				$nome_plano = $conteudo_lead['nome_plano'];
			}

			$ultimo_contato = DBRead('', 'tb_lead_timeline', "WHERE id_lead_negocio = $id_lead_negocio ORDER BY id_lead_timeline DESC LIMIT 1", 'data');
			
			$observacoes = '';

			$negocios_perdidos = DBRead('', 'tb_lead_negocio_perdido', "WHERE id_lead_negocio = $id_lead_negocio");

			if ($negocios_perdidos != false) {
				foreach ($negocios_perdidos as $perdidos) {
					$observacoes .= ' - '.mb_strtolower($perdidos['observacao']).'<br>';
				}
			}

			$negocios_pausados = DBRead('', 'tb_lead_negocio_pausado', "WHERE id_lead_negocio = $id_lead_negocio");

			if ($negocios_pausados != false) {
				foreach ($negocios_pausados as $pausados) {
					$observacoes .= ' - '.mb_strtolower($pausados['observacao']).'<br>';
				}
			}

			$timeline = DBRead('', 'tb_lead_timeline', "WHERE id_lead_negocio = $id_lead_negocio");

			if ($timeline != false) {
				$total = sizeof($timeline);

				if ($total > $total_timeline) {
					$total_timeline = $total;
				}
			}
			
	    	echo '<tr>
			    	<td>'.$nome_pessoa.'</td>
			    	<td>'.$email.'</td>
			    	<td class="phone">'.$telefone.'</td>
					<td>'.$produto.'</td>
					<td>'.$nome_plano.'</td>
					<td>'.$status_planilha.'</td>
                    <td>'.$situacao_descricao.'</td>                    
                    <td> R$ '.converteMoeda($valor_contrato).'</td>
                    <td>'.converteDataHora($ultimo_contato[0]['data']).'</td>
                    <td>'.$observacoes.'</td>
            ';

			for ($i=0; $i<=58; $i++) {
				if ($timeline[$i]['descricao'] != '') {
					echo '<td>'.$timeline[$i]['descricao'].'</td>';

				} else {
					echo '<td>--</td>';
				}
				
			}
			
			echo '</tr>';
	    }
		   			    	
		echo "
			</tbody>
		</table>
		<hr>
		";	

	echo "
	<div class = 'row'>";
		echo "<hr>
	<div/>
	<br><br><br>";


		echo "<script>
			$(document).ready(function(){
				var table = $('.dataTable_expo').DataTable({
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
							text: '<i class=\"fa fa-file-excel-o\" aria-hidden=\"true\"></i> Exportar',
							filename: 'planilha',
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
}

?>