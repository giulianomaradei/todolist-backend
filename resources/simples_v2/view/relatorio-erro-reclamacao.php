<?php
	require_once(__DIR__."/../class/System.php");
	
	$id_usuario = $_SESSION['id_usuario'];
	$dados = DBRead('', 'tb_usuario', "WHERE id_usuario = '$id_usuario'");
	$perfil_sistema = $dados[0]['id_perfil_sistema'];
	$data_hoje = getDataHora();
	$data_hoje = explode(" ", $data_hoje);
	$data_hoje = $data_hoje[0];
	$primeiro_dia = "01/".$data_hoje[5].$data_hoje[6]."/".$data_hoje[0].$data_hoje[1].$data_hoje[2].$data_hoje[3];
	$usuario = (!empty($_POST['usuario'])) ? $_POST['usuario'] : '';
	$id_contrato_plano_pessoa = (!empty($_POST['id_contrato_plano_pessoa'])) ? $_POST['id_contrato_plano_pessoa'] : '';
	$data_de = (!empty($_POST['data_de'])) ? $_POST['data_de'] : $primeiro_dia;
	$data_ate = (!empty($_POST['data_ate'])) ? $_POST['data_ate'] : converteData(getDataHora('data'));
	$tipo_erro = (!empty($_POST['tipo_erro'])) ? $_POST['tipo_erro'] : '';
	$origem = (!empty($_POST['origem'])) ? $_POST['origem'] : '';
	$lider = '';
	if($perfil_usuario == 15){
		$lider = 15;
	}
	$lider = (!empty($_POST['lider'])) ? $_POST['lider'] : '';
	$realizado_monitoria = (!empty($_POST['realizado_monitoria'])) ? $_POST['realizado_monitoria'] : '';
	$canal_atendimento = (!empty($_POST['canal_atendimento'])) ? $_POST['canal_atendimento'] : '';
	$tipo_relatorio = (!empty($_POST['tipo_relatorio'])) ? $_POST['tipo_relatorio'] : '';
	$gerar = (!empty($_POST['gerar'])) ? 1 : 0;	
	$encarteiramento = (!empty($_POST['encarteiramento'])) ? $_POST['encarteiramento'] : '';
	
	if($id_contrato_plano_pessoa){
		$dados_contrato = DBRead('', 'tb_contrato_plano_pessoa a', "INNER JOIN tb_plano b ON a.id_plano = b.id_plano INNER JOIN tb_pessoa c ON a.id_pessoa = c.id_pessoa WHERE a.id_contrato_plano_pessoa = '$id_contrato_plano_pessoa'", "a.*, b.cod_servico, b.nome AS 'plano', c.nome AS 'nome_pessoa'");

		$contrato = $dados_contrato[0]['nome_pessoa'] . " - " . getNomeServico($dados_contrato[0]['cod_servico']) . " - " . $dados_contrato[0]['plano'] . " (" . $dados_contrato[0]['id_contrato_plano_pessoa'] . ")";
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

	if ($tipo_relatorio == '1') {
    	$display_row_usuario = '';
    	$display_row_lider = '';
    	$display_row_realizado_monitoria = '';
    	$display_row_encarteiramento = '';

	}else if($tipo_relatorio == '2'){
	    $display_row_usuario = 'style="display:none;"';
       	$display_row_lider = 'style="display:none;"';
		$display_row_realizado_monitoria = 'style="display:none;"';
		$display_row_encarteiramento = 'style="display:none;"';

	}else if($tipo_relatorio == '3'){
	    $display_row_usuario = '';
       	$display_row_lider = '';
		$display_row_realizado_monitoria = 'style="display:none;"';
		$display_row_encarteiramento = '';

	}else if($tipo_relatorio == '4'){
	    $display_row_usuario = '';
       	$display_row_lider = '';
		$display_row_realizado_monitoria = '';
		$display_row_encarteiramento = '';
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
	                    <h3 class="panel-title text-left pull-left" style="margin-top: 2px;">Relatório de Reclamações/Erros:</h3>
	                    <div class="panel-title text-right pull-right"><button data-toggle="collapse" data-target="#accordionRelatorio" class="btn btn-xs btn-info" type="button" title="Visualizar filtros"><i id="i_collapse" class="fa fa-<?=$collapse_icon?>"></i></button></div>
	                </div>
	                <div id="accordionRelatorio" class="panel-collapse collapse <?=$collapse?>">
	                	<div class="panel-body">
	                		<div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Tipo de Relatório:</label> <select
                                            name="tipo_relatorio" id="tipo_relatorio" class="form-control input-sm">
											<option value="1" <?php if($tipo_relatorio == '1'){echo 'selected';}?>>Reclamações/Erros</option> 
											<option value="4" <?php if($tipo_relatorio == '4'){echo 'selected';}?>>Reclamações/Erros - Tabela</option> 

                                            <?php if($perfil_sistema != 3){?>
                                            	<option value="2" <?php if($tipo_relatorio == '2'){echo 'selected';}?>>Contagem por Contratos</option>
                                            <?php }
	                                            if($perfil_sistema != 3){?>
                                            		<option value="3" <?php if($tipo_relatorio == '3'){echo 'selected';}?>>Contagem por Funcionários</option>
	                                        <?php }?> 
  
                                        </select>
                                    </div>
                                </div>
                            </div>
	                		<div class="row">
	                			<div class="col-md-6">
	                				<div class="form-group">
										<label>*Data Inicial:</label>
										<input type="text" class="form-control date calendar input-sm" name="data_de" id="de" autocomplete="off" value="<?=$data_de?>" required>

									</div>
	                			</div>
	                			<div class="col-md-6">
	                				<div class="form-group">
										<label>*Data Final:</label>
										<input type="text" class="form-control date calendar input-sm" name="data_ate" id="ate" autocomplete="off" value="<?=$data_ate?>" required>
									</div>
	                			</div>
	                		</div>
	                	<?php if($perfil_sistema != 3){?>
	                		<div class="row" id="row_usuario" <?= $display_row_usuario ?>>
								<div class="col-md-12">
									<div class="form-group">
								        <label for="">Funcionário:</label>
								        <select name="usuario" class="form-control input-sm">
									            <option value =''>Todos</option>
									            <?php
												if($perfil_usuario == 15){
									            	$dados_usuarios = DBRead('','tb_usuario a',"INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.status = '1' AND lider_direto = '".$id_usuario."' ORDER BY b.nome ASC","a.id_usuario, b.nome");
												}else{
									            	$dados_usuarios = DBRead('','tb_usuario a',"INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.status = '1' ORDER BY b.nome ASC","a.id_usuario, b.nome");
												}
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
	                		<div class="row">	                			
	                			<div class="col-md-12">
	                				<div class="form-group">
	                                    <label>Contrato (Cliente):</label>
	                                    <div class="input-group">
	                                        <input class="form-control input-sm" id="busca_contrato" type="text" name="busca_contrato"  value="<?=$contrato?>" placeholder="Informe o nome ou CNPJ..." autocomplete="off" readonly/>
	                                        <div class="input-group-btn">
	                                            <button class="btn btn-info btn-sm" id="habilita_busca_contrato" name="habilita_busca_contrato" type="button" title="Clique para selecionar o contrato"><i class="fa fa-search"></i></button>
	                                        </div>
	                                    </div>
	                                    <input type="hidden" name="id_contrato_plano_pessoa" id="id_contrato_plano_pessoa" value="<?=$id_contrato_plano_pessoa;?>" />
	                                </div>
	                			</div>
	                		</div>
	                		<div class="row">
	                			<div class="col-md-12">
	                				<div class="form-group">
										<label>Tipo de Reclamação/Erro:</label>
										<select class='form-control input-sm' name='tipo_erro' id='tipo_erro'>
											<option value="">Todos</option>
											<option value="1" <?php if($tipo_erro == '1'){ echo 'selected';}?>>Finalização</option>
											<option value="2" <?php if($tipo_erro == '2'){ echo 'selected';}?>>Encaminhamento</option>
											<option value="3" <?php if($tipo_erro == '3'){ echo 'selected';}?>>Falta de procedimento</option>
											<option value="4" <?php if($tipo_erro == '4'){ echo 'selected';}?>>Erro de procedimento</option>
											<option value="5" <?php if($tipo_erro == '5'){ echo 'selected';}?>>Ponto a desenvolver</option>
										</select>
									</div>
	                			</div>
	                		</div>
	                		<div class="row">
	                			<div class="col-md-12">
	                				<div class="form-group">
										<label>Origem:</label>
										<select class='form-control input-sm' name='origem' id='origem'>
											<option value="">Todas</option>
											<option value="2" <?php if($origem == '2'){ echo 'selected';}?>>Belluno</option>
											<option value="1" <?php if($origem == '1'){ echo 'selected';}?>>Cliente</option>
										</select>
									</div>
	                			</div>
							</div>
							<div class="row">
	                			<div class="col-md-12">
	                				<div class="form-group">
										<label>Canal de atendimento:</label>
										<select class='form-control input-sm' name='canal_atendimento' id='canal_atendimento'>
											<option value="">Todos</option>
											<option value="1" <?php if($canal_atendimento == '1'){ echo 'selected';}?>>via Telefone</option>
											<option value="2" <?php if($canal_atendimento == '2'){ echo 'selected';}?>>via Texto</option>
											
										</select>
									</div>
	                			</div>
	                		</div>
	                	<?php if($perfil_sistema != 3){?>
	                		<div class="row" id="row_lider" <?= $display_row_lider ?>>
								<div class="col-md-12">
									<div class="form-group">
										<label for="">Líder Direto:</label>
										<select name="lider" class="form-control input-sm">
											<option value="">Todos</option>
												<?php
												if($perfil_usuario == 15){
													$dados_lider = DBRead('', 'tb_usuario a', "INNER JOIN tb_usuario b ON a.lider_direto = b.id_usuario INNER JOIN tb_pessoa c ON b.id_pessoa = c.id_pessoa where a.lider_direto = '".$id_usuario."' GROUP BY a.lider_direto, c.nome ORDER BY c.nome ASC", "a.lider_direto, c.nome");
												}else{
													$dados_lider = DBRead('', 'tb_usuario a', "INNER JOIN tb_usuario b ON a.lider_direto = b.id_usuario INNER JOIN tb_pessoa c ON b.id_pessoa = c.id_pessoa where a.lider_direto GROUP BY a.lider_direto, c.nome ORDER BY c.nome ASC", "a.lider_direto, c.nome");
												}
												if ($dados_lider) {
													foreach ($dados_lider as $conteudo_lider) {
														$selected = $lider == $conteudo_lider['lider_direto'] ? "selected" : "";
														echo "<option value='" . $conteudo_lider['lider_direto'] . "' ".$selected.">" . $conteudo_lider['nome'] . "</option>";
													}
												}
												?>
										</select>
									</div>
								</div>
							</div>	                			
						<?php }?> 

						<?php if($perfil_sistema != 3){?>
	                		<div class="row" id="row_encarteiramento" <?= $display_row_encarteiramento ?>>
								<div class="col-md-12">
									<div class="form-group">
										<label for="">Encarteiramento do analista:</label>
										<select name="encarteiramento" class="form-control input-sm">
											<option value="">Todos</option>
												<?php
												$dados_encarteiramento = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON b.id_pessoa = a.id_pessoa WHERE a.id_perfil_sistema = 14 AND a.status = 1 ORDER BY b.nome ASC", "a.id_usuario, b.nome");

												if ($dados_encarteiramento) {
													foreach ($dados_encarteiramento as $conteudo_encarteiramento) {
														$selected = $encarteiramento == $conteudo_encarteiramento['id_usuario'] ? "selected" : "";
														echo "<option value='" . $conteudo_encarteiramento['id_usuario'] . "' ".$selected.">" . $conteudo_encarteiramento['nome'] . "</option>";
													}
												}
												?>
										</select>
									</div>
								</div>
							</div>	                			
						<?php }?>   
						
						<div class="row" id="row_realizado_monitoria" <?= $display_row_realizado_monitoria ?>>
							<div class="col-md-12">
								<div class="form-group">
									<label>Realizada Monitoria:</label>
									<select class='form-control input-sm' name='realizado_monitoria' id='realizado_monitoria'>
										<option value="">Todos</option>
										<option value="2" <?php if($realizado_monitoria == '2'){ echo 'selected';}?>>Sim</option>
										<option value="1" <?php if($realizado_monitoria == '1'){ echo 'selected';}?>>Não</option>
									</select>
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
			if($gerar){

				if ($perfil_sistema == '3') { 
					$usuario = $id_usuario;
				}

				if ($tipo_relatorio == 1) {
					relatorio_analitico($usuario, $id_contrato_plano_pessoa, $data_de, $data_ate, $tipo_erro, $lider, $origem, $realizado_monitoria, $canal_atendimento, $encarteiramento);

	            } else if ($tipo_relatorio == 2) {
					relatorio_contagem_cliente($id_contrato_plano_pessoa, $data_de, $data_ate, $tipo_erro, $origem);

	            } else if ($tipo_relatorio == 3) {
					relatorio_contagem_funcionario($usuario, $id_contrato_plano_pessoa, $data_de, $data_ate, $tipo_erro, $lider, $origem);

	            } else if($tipo_relatorio == 4) {
					relatorio_analitico_tabela($usuario, $id_contrato_plano_pessoa, $data_de, $data_ate, $tipo_erro, $lider, $origem, $realizado_monitoria, $canal_atendimento);
	            }		
			}
			?>
	</div>
</div>

<script>

    $('#tipo_relatorio').on('change',function(){
		tipo_relatorio = $(this).val();
		if(tipo_relatorio == 1){
			$('#row_usuario').show();
			$('#row_lider').show();
			$('#row_realizado_monitoria').show();
			$('#row_encarteiramento').show();
			
		}else if(tipo_relatorio == 2){
			$('#row_usuario').hide();
			$('#row_lider').hide();
			$('#row_realizado_monitoria').hide();
			$('#row_encarteiramento').hide();
			
		}else if(tipo_relatorio == 3){
			$('#row_usuario').show();
			$('#row_lider').show();
			$('#row_realizado_monitoria').hide();
			$('#row_encarteiramento').show();
			
		}else if(tipo_relatorio == 4){
			$('#row_usuario').show();
			$('#row_lider').show();
			$('#row_realizado_monitoria').show();
			$('#row_encarteiramento').show();
			
		}
	});   

    //BUSCA CONTRATO
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
            focus: function (event, ui) {
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
                success: function(data){
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

    $(document).on('click', '#habilita_busca_contrato', function(){
        $('#id_contrato_plano_pessoa').val('');
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

</script>

<?php 

function relatorio_analitico_tabela($usuario, $id_contrato_plano_pessoa, $data_de, $data_ate, $tipo_erro, $lider, $origem, $realizado_monitoria, $canal_atendimento){

    $dados_usuario = DBRead('', 'tb_usuario', "WHERE id_usuario = '".$_SESSION['id_usuario']."'");
    $perfil_usuario = $dados_usuario[0]['id_perfil_sistema'];

	$data_hora = converteDataHora(getDataHora());

	if($tipo_erro){
		$dados_tipo_erro = DBRead('','tb_tipo_erro',"WHERE id_tipo_erro = '".$tipo_erro."'");
		$nome_erro = '<span style="font-size: 14px;"><strong>Tipo da reclamação/erro -</strong> '.$dados_tipo_erro[0]['nome'].'</span>';
	}else{
		$nome_erro = '<span style="font-size: 14px;"><strong>Tipo da reclamação/erro -</strong> Todos</span>';
	}

	if($data_de && $data_ate){
	    $periodo_amostra ="<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> De $data_de até $data_ate</span>";
	}elseif($data_de){
	    $periodo_amostra ="<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> A partir de $data_de</span>";
	}elseif($data_ate){
	    $periodo_amostra ="<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> Até $data_ate</span>";
	}else{
	    $periodo_amostra = "";
	}

	if($lider){
		$dados_lider = DBRead('','tb_usuario a',"INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_usuario='$lider'");
		$nome_lider = '<span style="font-size: 14px;"><strong>Líder Direto -</strong> '.$dados_lider[0]['nome'].'</span>';
	}else{
		$nome_lider = '<span style="font-size: 14px;"><strong>Líder Direto -</strong> Todos</span>';		
	}
	if($usuario){
		$dados_usuario = DBRead('','tb_usuario a',"INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_usuario='$usuario'");
		if($dados_usuario){
			$nome_usuario = '<span style="font-size: 14px;"><strong>Funcionário -</strong> '.$dados_usuario[0]['nome'].'</span>';
		}else{
			$nome_usuario = '<span style="font-size: 14px;"><strong>Funcionário -</strong> Não identificado</span>';
		}
	}else{
		$nome_usuario = '<span style="font-size: 14px;"><strong>Funcionário -</strong> Todos</span>';
	}
	if($id_contrato_plano_pessoa){
		$dados_contrato_plano_pessoa = DBRead('','tb_contrato_plano_pessoa a',"INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_contrato_plano_pessoa='$id_contrato_plano_pessoa'");

		if($dados_contrato_plano_pessoa){
			$nome_empresa = $dados_contrato_plano_pessoa[0]['nome'];
		}else{
			$nome_empresa = 'Não identificada';
		}
	}else{
		$nome_empresa = '<span style="font-size: 14px;">Todos</span>';
	}

	if($origem){
		if($origem == 2){
			$legenda_origem = '<span style="font-size: 14px;"><strong>Origem -</strong> Belluno</span>';
		}else{
			$legenda_origem = '<span style="font-size: 14px;"><strong>Origem -</strong> Cliente</span>';
		}
	}else{
		$legenda_origem = '<span style="font-size: 14px;"><strong>Origem -</strong> Todas</span>';
	}

	if ($canal_atendimento) {
		if ($canal_atendimento == 1) {
			$legenda_origem = '<span style="font-size: 14px;"><strong>Canal de atendimento -</strong> Via Telefone</span>';

		} else if ($canal_atendimento == 1) {
			$legenda_origem = '<span style="font-size: 14px;"><strong>Canal de atendimento -</strong> via Texto</span>';
		}
	} else{
		$legenda_canal_atendimento = '<span style="font-size: 14px;"><strong>Canal de atendimento -</strong> Todas</span>';
	}

	if($realizado_monitoria){
		if($realizado_monitoria == 2){
			$legenda_realizado_monitoria = '<span style="font-size: 14px;"><strong>Realizada Monitoria -</strong> Sim</span>';
		}else{
			$legenda_realizado_monitoria = '<span style="font-size: 14px;"><strong>Realizada Monitoria -</strong> Não</span>';
		}
	}else{
		$legenda_realizado_monitoria = '<span style="font-size: 14px;"><strong>Realizada Monitoria -</strong> Todos</span>';
	}
	
	echo "<div class=\"col-md-12\" style=\"padding: 0\">";
	echo "<legend style=\"text-align:center;\"><strong>Relatório de Reclamações/Erros - Tabela</strong><br><span style=\"font-size: 14px;\"><strong>Gerado em:</strong> $data_hora</span></legend>";	
	echo "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\">".$periodo_amostra."</legend>";
    echo "<legend style=\"text-align:center; font-size: 14px;\">$nome_usuario, <strong>Contrato - </strong>$nome_empresa, $nome_erro, $nome_lider, $legenda_origem, $legenda_canal_atendimento, $legenda_realizado_monitoria</legend>";

	$filtro= '';
    if($data_de){
		$filtro .= " AND a.data_cadastrado >= '".converteData($data_de)." 00:00:00'";
	}

	if($data_ate){
		$filtro .= " AND a.data_cadastrado <= '".converteData($data_ate)." 23:59:59'";
	}

	if($tipo_erro){
		$filtro .= " AND a.id_tipo_erro = '$tipo_erro'";
	}

	if($usuario){
		$filtro .= " AND a.id_usuario = '$usuario'";
	}

	if($id_contrato_plano_pessoa){
		$filtro .= " AND a.id_contrato_plano_pessoa = '$id_contrato_plano_pessoa'";
	}

	if($lider){
		$filtro .= " AND c.lider_direto = '$lider'";
    }
    if($origem){
		$filtro .= " AND a.origem = '$origem'";
	}

	if($canal_atendimento){
		$filtro .= " AND a.canal_atendimento = '$canal_atendimento'";
	}

	if($realizado_monitoria){
		if($realizado_monitoria == 2){
			$filtro .= 'AND (f.id_erro IS NOT NULL OR g.id_erro IS NOT NULL)';
		}else{
			$filtro .= 'AND (f.id_erro IS NULL AND g.id_erro IS NULL)';
		}
	}

	$dados = DBRead('', 'tb_erro_atendimento a', "INNER JOIN tb_contrato_plano_pessoa b ON a.id_contrato_plano_pessoa = b.id_contrato_plano_pessoa INNER JOIN tb_usuario c ON a.id_usuario = c.id_usuario INNER JOIN tb_pessoa d ON c.id_pessoa = d.id_pessoa INNER JOIN tb_plano e ON b.id_plano = e.id_plano LEFT JOIN tb_monitoria_avaliacao_audio f ON a.id_erro_atendimento = f.id_erro LEFT JOIN tb_monitoria_avaliacao_texto g ON a.id_erro_atendimento = g.id_erro WHERE a.status != 2 $filtro", "a.*, b.*, c.*, d.*, d.nome AS nome_atendente, e.cod_servico, e.nome AS nome_plano, f.id_monitoria_avaliacao_audio, f.considerar as considerar_audio, g.id_monitoria_avaliacao_texto, g.considerar as considerar_texto");

	if($dados){
		
		echo "<table class='table table-hover dataTable' style='font-size='14px'>";
			echo "<thead>";
				echo "<tr>";
					echo "<th class='col-md-1'>Contrato</th>";
					echo "<th class='col-md-1'>Plano</th>";
					echo "<th class='col-md-1'>Tipo de reclamação/erro</th>";
					echo "<th class='col-md-2'>Funcionário</th>";
					echo "<th class='col-md-2'>Criado por</th>";
					echo "<th class='col-md-1'>Origem</th>";
					echo "<th class='col-md-1'>Canal de atendimento</th>";
					echo "<th class='col-md-1'>Realizada monitoria</th>";
					echo "<th class='col-md-1'>Data da ocorrência</th>";
					echo "<th class='col-md-1'>Data do cadastro</th>";
				echo "</tr>";
			echo "</thead>";
			echo "<tbody>";

		$contrato_total = array();
		$plano_total = array();
		$funcionario_total = array();
		$usuario_cadastrou_total = array();
		$tipo_total = array();
		$origem_total = array();
		$canal_atendimento_total['Telefone'] = 0;
		$canal_atendimento_total['Texto'] = 0;
		$realizada_monitoria_total['Sim'] = 0;
		$realizada_monitoria_total['Não'] = 0;

		foreach($dados as $dado){

			$erro_lider = DBRead('', 'tb_erro_atendimento_lider', "WHERE id_erro_atendimento = '".$dado['id_erro_atendimento']."' ");
			
            if($perfil_usuario != '3' || $erro_lider[0]['lido'] == '1'){
		
				$nome_cliente = DBRead('', 'tb_pessoa a', "INNER JOIN tb_contrato_plano_pessoa b ON a.id_pessoa = b.id_pessoa WHERE id_contrato_plano_pessoa = ".$dado['id_contrato_plano_pessoa']);

				if($nome_cliente[0]['nome_contrato']){
					$nome_contrato = $nome_cliente[0]['nome']." (". $nome_cliente[0]['nome_contrato'] .")";
				}else{
					$nome_contrato = $nome_cliente[0]['nome'];
				}
				
				$usuario_cadastrou = DBRead('', 'tb_pessoa a', "INNER JOIN tb_usuario b ON a.id_pessoa = b.id_pessoa WHERE b.id_usuario = ".$dado['id_usuario_cadastrou']);

				$tipos = DBRead('', 'tb_erro_atendimento a',"INNER JOIN tb_tipo_erro b ON a.id_tipo_erro = b.id_tipo_erro WHERE a.id_erro_atendimento = '".$dado['id_erro_atendimento']."'", "a.*,b.nome AS nome_tipo");

				$hora_erro = $dado['hora_erro'];
				$hora_erro = explode(":", $hora_erro);
				$hora_erro = $hora_erro[0].":".$hora_erro[1];
				
				if ($dado['origem'] == 1) {
					$origem_nome = "Cliente";
				} else {
					$origem_nome = "Belluno";
				}

				if (($dado['id_monitoria_avaliacao_audio'] && $dado['considerar_audio'] == 1) || ($dado['id_monitoria_avaliacao_texto'] && $dado['considerar_texto'] == 1)) {
					$monitoria = "Sim";
					$realizada_monitoria_total['Sim']++;
				} else {
					$monitoria = "Não";
					$realizada_monitoria_total['Não']++;
				}

				if ($dado['canal_atendimento'] == 1) {
					$canal_atendimento = "via Telefone";
					$canal_atendimento_total['Telefone']++;
				} else {
					$canal_atendimento = "via Texto";
					$canal_atendimento_total['Texto']++;
				}

				echo "<tr>";
				
					echo "<td style='vertical-align: middle'>".$nome_contrato."</td>";
					echo "<td style='vertical-align: middle'>".getNomeServico($dado['cod_servico'])." - ".$dado['nome_plano']."</td>";
					echo "<td style='vertical-align: middle'>".$tipos[0]['nome_tipo']."</td>";
					echo "<td style='vertical-align: middle'>".$dado['nome_atendente']."</td>";
					echo "<td style='vertical-align: middle'>".$usuario_cadastrou[0]['nome']."</td>";
					echo "<td style='vertical-align: middle'>".$origem_nome."</td>";
					echo "<td style='vertical-align: middle'>".$canal_atendimento."</td>";
					
					echo "<td style='vertical-align: middle'>".$monitoria."</td>";
					
					echo "<td data-order='".$dado['data_erro']." ".$hora_erro."' style='vertical-align: middle'>".converteData($dado['data_erro'])." ".$hora_erro."</td>";
					echo "<td data-order='".$dado['data_cadastrado']."' style='vertical-align: middle'>".converteDataHora($dado['data_cadastrado'])."</td>";

				echo "</tr>";
                
				$contrato_total[$nome_contrato] ++;
				$plano_total[getNomeServico($dado['cod_servico'])." - ".$dado['nome_plano']] ++;
				$funcionario_total[$dado['nome_atendente']] ++;
				$usuario_cadastrou_total[$usuario_cadastrou[0]['nome']] ++;
				$tipo_total[$tipos[0]['nome_tipo']] ++;
				$origem_total[$origem_nome] ++;
				
			}
			
		}
			echo "</tbody>";
		echo "</table>";

		echo "
				<script>
					$(document).ready(function(){
						var table = $('.dataTable').DataTable({
							\"language\": {
								\"url\": \"//cdn.datatables.net/plug-ins/1.10.19/i18n/Portuguese-Brasil.json\"
							},
							columnDefs: [
								{ type: 'time-uni', targets: 2 },
							],				        
							\"searching\": false,
							\"paging\":   false,
							\"info\":     false
						});
  
						var buttons = new $.fn.dataTable.Buttons(table, {
							buttons: [
								{
									extend: 'excelHtml5', footer: true,
									text: '<i class=\"fa fa-file-excel-o\" aria-hidden=\"true\"></i> Exportar',
									filename: 'relatorio_reclamacao_erro_tabela',
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
				
		echo "
		<hr>
		<div class='row'>";
		if($contrato_total){
			
			echo '
				<div class="col-md-6">';
					echo "
					<table class=\"table table-hover dataTableAgrupado\"> 
						<thead> 
							<tr> 
								<th class='col-md-6'>Contrato</th>
								<th class='col-md-6'>Quantidade</th>
							</tr>
						</thead> 
						<tbody>"
						;
				arsort($contrato_total);		
				foreach ($contrato_total as $nome => $quantidade) {
					echo '<tr>';
						echo '<td>'.$nome.'</td>';
						echo '<td>'.$quantidade.'</td>';
					echo '</tr>';			
				}
					echo "
						</tbody> 
					</table>";

				echo "
			</div>";		
		}

		if($plano_total){

			echo '
				<div class="col-md-6">';
					echo "
					<table class=\"table table-hover dataTableAgrupado\"> 
						<thead> 
							<tr> 
								<th class='col-md-6'>Plano</th>
								<th class='col-md-6'>Quantidade</th>
							</tr>
						</thead> 
						<tbody>"
						;
				arsort($plano_total);		
				foreach ($plano_total as $nome => $quantidade) {
					echo '<tr>';
						echo '<td>'.$nome.'</td>';
						echo '<td>'.$quantidade.'</td>';
					echo '</tr>';			
				}
					echo "
						</tbody> 
					</table>";

				echo "
			</div>";		
		}

		echo "
		</div>	
		<hr>
		<div class='row'>";

		if($funcionario_total){

			echo '
				<div class="col-md-6">';
					echo "
					<table class=\"table table-hover dataTableAgrupado\"> 
						<thead> 
							<tr> 
								<th class='col-md-6'>Funcionário</th>
								<th class='col-md-6'>Quantidade</th>
							</tr>
						</thead> 
						<tbody>"
						;
				arsort($funcionario_total);		
				foreach ($funcionario_total as $nome => $quantidade) {
					echo '<tr>';
						echo '<td>'.$nome.'</td>';
						echo '<td>'.$quantidade.'</td>';
					echo '</tr>';			
				}
					echo "
						</tbody> 
					</table>";

				echo "
			</div>";		
		}

		if($usuario_cadastrou_total){

			echo '
				<div class="col-md-6">';
					echo "
					<table class=\"table table-hover dataTableAgrupado\"> 
						<thead> 
							<tr> 
								<th class='col-md-6'>Criado por</th>
								<th class='col-md-6'>Quantidade</th>
							</tr>
						</thead> 
						<tbody>"
						;
				arsort($usuario_cadastrou_total);		
				foreach ($usuario_cadastrou_total as $nome => $quantidade) {
					echo '<tr>';
						echo '<td>'.$nome.'</td>';
						echo '<td>'.$quantidade.'</td>';
					echo '</tr>';			
				}
					echo "
						</tbody> 
					</table>";

				echo "
			</div>";		
		}

		echo "
		</div>	
		<hr>
		<div class='row'>";

		if($tipo_total){

			echo '
				<div class="col-md-6">';
					echo "
					<table class=\"table table-hover dataTableAgrupado\"> 
						<thead> 
							<tr> 
								<th class='col-md-6'>Tipo de reclamação/erro</th>
								<th class='col-md-6'>Quantidade</th>
							</tr>
						</thead> 
						<tbody>"
						;
				arsort($tipo_total);		
				foreach ($tipo_total as $nome => $quantidade) {
					echo '<tr>';
						echo '<td>'.$nome.'</td>';
						echo '<td>'.$quantidade.'</td>';
					echo '</tr>';			
				}
					echo "
						</tbody> 
					</table>";

				echo "
			</div>";		
		}

		if($origem_total){

			echo '
				<div class="col-md-6">';
					echo "
					<table class=\"table table-hover dataTableAgrupado\"> 
						<thead> 
							<tr> 
								<th class='col-md-6'>Origem</th>
								<th class='col-md-6'>Quantidade</th>
							</tr>
						</thead> 
						<tbody>"
						;
				arsort($origem_total);		
				foreach ($origem_total as $nome => $quantidade) {
					echo '<tr>';
						echo '<td>'.$nome.'</td>';
						echo '<td>'.$quantidade.'</td>';
					echo '</tr>';			
				}
					echo "
						</tbody> 
					</table>";

				echo "
			</div>";		
		}
		echo "
		</div>	
		<hr>
		<div class='row'>";

		if($canal_atendimento_total){

			echo '
				<div class="col-md-6">';
					echo "
					<table class=\"table table-hover dataTableAgrupado\"> 
						<thead> 
							<tr> 
								<th class='col-md-6'>Canal de atendimento</th>
								<th class='col-md-6'>Quantidade</th>
							</tr>
						</thead> 
						<tbody>"
						;
				arsort($canal_atendimento_total);		
				foreach ($canal_atendimento_total as $nome => $quantidade) {
					echo '<tr>';
						echo '<td>'.$nome.'</td>';
						echo '<td>'.$quantidade.'</td>';
					echo '</tr>';			
				}
					echo "
						</tbody> 
					</table>";

				echo "
			</div>";		
		}

		if($realizada_monitoria_total){

			echo '
				<div class="col-md-6">';
					echo "
					<table class=\"table table-hover dataTableAgrupado\"> 
						<thead> 
							<tr> 
								<th class='col-md-6'>Realizada Monitoria</th>
								<th class='col-md-6'>Quantidade</th>
							</tr>
						</thead> 
						<tbody>"
						;
				arsort($realizada_monitoria_total);		
				foreach ($realizada_monitoria_total as $nome => $quantidade) {
					echo '<tr>';
						echo '<td>'.$nome.'</td>';
						echo '<td>'.$quantidade.'</td>';
					echo '</tr>';			
				}
					echo "
						</tbody> 
					</table>";

				echo "
			</div>";		
		}

		echo "
		</div><br><br>";	

		echo "
		<script>
			$(document).ready(function(){
			    $('.dataTableAgrupado').DataTable({
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

function relatorio_analitico($usuario, $id_contrato_plano_pessoa, $data_de, $data_ate, $tipo_erro, $lider, $origem, $realizado_monitoria, $canal_atendimento, $encarteiramento){

    $dados_usuario = DBRead('', 'tb_usuario', "WHERE id_usuario = '".$_SESSION['id_usuario']."'");
    $perfil_usuario = $dados_usuario[0]['id_perfil_sistema'];

	$data_hora = converteDataHora(getDataHora());

	if ($tipo_erro) {
		$dados_tipo_erro = DBRead('','tb_tipo_erro',"WHERE id_tipo_erro = '".$tipo_erro."'");
		$nome_erro = '<span style="font-size: 14px;"><strong>Tipo da reclamação/erro -</strong> '.$dados_tipo_erro[0]['nome'].'</span>';
	} else {
		$nome_erro = '<span style="font-size: 14px;"><strong>Tipo da reclamação/erro -</strong> Todos</span>';
	}

	if ($data_de && $data_ate) {
	    $periodo_amostra ="<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> De $data_de até $data_ate</span>";
	} else if($data_de) {
	    $periodo_amostra ="<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> A partir de $data_de</span>";
	} else if ($data_ate) {
	    $periodo_amostra ="<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> Até $data_ate</span>";
	} else {
	    $periodo_amostra = "";
	}

	if ($lider) {
		$dados_lider = DBRead('','tb_usuario a',"INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_usuario='$lider'");
		$nome_lider = '<span style="font-size: 14px;"><strong>Líder Direto -</strong> '.$dados_lider[0]['nome'].'</span>';
	} else {
		$nome_lider = '<span style="font-size: 14px;"><strong>Líder Direto -</strong> Todos</span>';		
	}

	if ($usuario) {
		$dados_usuario = DBRead('','tb_usuario a',"INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_usuario='$usuario'");
		if ($dados_usuario) {
			$nome_usuario = '<span style="font-size: 14px;"><strong>Funcionário -</strong> '.$dados_usuario[0]['nome'].'</span>';
		} else {
			$nome_usuario = '<span style="font-size: 14px;"><strong>Funcionário -</strong> Não identificado</span>';
		}
	} else {
		$nome_usuario = '<span style="font-size: 14px;"><strong>Funcionário -</strong> Todos</span>';
	}

	if ($encarteiramento) {
		$dados_usuario_criador = DBRead('','tb_usuario a',"INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_usuario='$encarteiramento'");
		if ($dados_usuario_criador) {
			$nome_usuario_criador = '<span style="font-size: 14px;"><strong>Criado por -</strong> '.$dados_usuario_criador[0]['nome'].'</span>';
		} else {
			$nome_usuario_criador = '<span style="font-size: 14px;"><strong>Criado por -</strong> Não identificado</span>';
		}
	} else {
		$nome_usuario_criador = '<span style="font-size: 14px;"><strong>Criado por -</strong> Todos</span>';
	}

	if ($id_contrato_plano_pessoa) {
		$dados_contrato_plano_pessoa = DBRead('','tb_contrato_plano_pessoa a',"INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_contrato_plano_pessoa='$id_contrato_plano_pessoa'");

		if ($dados_contrato_plano_pessoa) {
			$nome_empresa = $dados_contrato_plano_pessoa[0]['nome'];
		} else {
			$nome_empresa = 'Não identificada';
		}
	} else {
		$nome_empresa = '<span style="font-size: 14px;">Todos</span>';
	}

	if ($origem) {
		if ($origem == 2) {
			$legenda_origem = '<span style="font-size: 14px;"><strong>Origem -</strong> Belluno</span>';
		} else {
			$legenda_origem = '<span style="font-size: 14px;"><strong>Origem -</strong> Cliente</span>';
		}
	} else {
		$legenda_origem = '<span style="font-size: 14px;"><strong>Origem -</strong> Todas</span>';
	}

	if ($canal_atendimento) {
		if ($canal_atendimento == 1) {
			$legenda_canal_atendimento = '<span style="font-size: 14px;"><strong>Canal de atendimento -</strong> Via Telefone</span>';

		} else if ($canal_atendimento == 2) {
			$legenda_canal_atendimento = '<span style="font-size: 14px;"><strong>Canal de atendimento -</strong> via Texto</span>';
		}
	} else {
		$legenda_canal_atendimento = '<span style="font-size: 14px;"><strong>Canal de atendimento -</strong> Todas</span>';
	}

	if ($realizado_monitoria) {
		if ($realizado_monitoria == 2) {
			$legenda_realizado_monitoria = '<span style="font-size: 14px;"><strong>Realizada Monitoria -</strong> Sim</span>';
		} else {
			$legenda_realizado_monitoria = '<span style="font-size: 14px;"><strong>Realizada Monitoria -</strong> Não</span>';
		}
	} else {
		$legenda_realizado_monitoria = '<span style="font-size: 14px;"><strong>Realizada Monitoria -</strong> Todos</span>';
	}
	
	echo "<div class=\"col-md-12\" style=\"padding: 0\">";
	echo "<legend style=\"text-align:center;\"><strong>Relatório de Reclamações/Erros</strong><br><span style=\"font-size: 14px;\"><strong>Gerado em:</strong> $data_hora</span></legend>";	
	echo "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\">".$periodo_amostra."</legend>";
    echo "<legend style=\"text-align:center; font-size: 14px;\">$nome_usuario, <strong>Contrato (cliente) - </strong>$nome_empresa, $nome_erro, $nome_lider, $nome_usuario_criador, $legenda_origem, $legenda_canal_atendimento, $legenda_realizado_monitoria</legend>";

	$filtro = '';
    if ($data_de) {
		$filtro .= " AND a.data_cadastrado >= '".converteData($data_de)." 00:00:00'";
	}

	if ($data_ate) {
		$filtro .= " AND a.data_cadastrado <= '".converteData($data_ate)." 23:59:59'";
	}

	if ($tipo_erro) {
		$filtro .= " AND a.id_tipo_erro = '$tipo_erro'";
	}

	if ($usuario) {
		$filtro .= " AND a.id_usuario = '$usuario'";
	}

	if ($id_contrato_plano_pessoa) {
		$filtro .= " AND a.id_contrato_plano_pessoa = '$id_contrato_plano_pessoa'";
	}

	if ($lider) {
		$filtro .= " AND c.lider_direto = '$lider'";
	}
	
    if ($origem) {
		$filtro .= " AND a.origem = '$origem'";
	}

	if ($canal_atendimento) {
		$filtro .= " AND a.canal_atendimento = '$canal_atendimento'";
	}

	if ($realizado_monitoria) {
		if ($realizado_monitoria == 2) {
			$filtro .= 'AND (f.id_erro IS NOT NULL OR g.id_erro IS NOT NULL)';
		} else {
			$filtro .= 'AND (f.id_erro IS NULL AND g.id_erro IS NULL)';
		}
	}

	if ($encarteiramento) {
		$filtro .= "AND (h.id_analista_telefone = $encarteiramento OR h.id_analista_texto = $encarteiramento)";
	}

	$dados = DBRead('', 'tb_erro_atendimento a', "INNER JOIN tb_contrato_plano_pessoa b ON a.id_contrato_plano_pessoa = b.id_contrato_plano_pessoa INNER JOIN tb_usuario c ON a.id_usuario = c.id_usuario INNER JOIN tb_pessoa d ON c.id_pessoa = d.id_pessoa LEFT JOIN tb_monitoria_avaliacao_audio f ON a.id_erro_atendimento = f.id_erro LEFT JOIN tb_monitoria_avaliacao_texto g ON a.id_erro_atendimento = g.id_erro LEFT JOIN tb_monitoria_classificacao_usuario h ON a.id_usuario = h.id_usuario WHERE a.status != 2 $filtro ORDER BY a.data_cadastrado DESC", "a.*, b.*, c.*, d.*, d.nome AS nome_atendente, f.id_monitoria_avaliacao_audio, f.considerar as considerar_audio, g.id_monitoria_avaliacao_texto, g.considerar as considerar_texto");

	$contador_dados = 0;  

	if($dados){
		 
		foreach($dados as $dado){

            $erro_lider = DBRead('', 'tb_erro_atendimento_lider', "WHERE id_erro_atendimento = '".$dado['id_erro_atendimento']."' ");
            if($perfil_usuario != '3' || $erro_lider[0]['lido'] == '1'){

                echo "<div class='panel panel-primary'>";
                echo "<div class='panel-heading clearfix'>";
                
                    echo "<div class='row'>";
                        $nome_cliente = DBRead('', 'tb_pessoa a', "INNER JOIN tb_contrato_plano_pessoa b ON a.id_pessoa = b.id_pessoa WHERE id_contrato_plano_pessoa = ".$dado['id_contrato_plano_pessoa']);
                        echo "<h3 class='panel-title text-left col-md-4'><strong>Cliente:</strong> ".$nome_cliente[0]['nome'];

                        if($nome_cliente[0]['nome_contrato']){
                            echo " (". $nome_cliente[0]['nome_contrato'] .")";
                        }

						echo "</h3>";
						echo "<h3 class='panel-title text-center col-md-4'><strong>";

						
						if(($dado['id_monitoria_avaliacao_audio'] && $dado['considerar_audio'] == 1) || ($dado['id_monitoria_avaliacao_texto'] && $dado['considerar_texto'] == 1)){
                            echo "Realizada Monitoria - Sim";
						}
						echo "</strong></h3>";

                        if($dado['protocolo']){
                            echo "<h3 class='panel-title text-right col-md-4'><strong>Protocolo:</strong> ".$dado['protocolo']."</h3>";
                        }
                    echo "</div>";
                echo "</div>";
                        
                echo "<div class='panel-body'>";
                    echo "<div class='row'>";
                    echo "<div class='col-md-12'>";
                    echo "<table class='table table-hover' style='font-size='14px'>";
                    echo "<thead>";
                        echo "<tr style='background-color: #ddd;'>";
                            echo "<th class='col-md-1'>Tipo de reclamação/erro</th>";
                            echo "<th class='col-md-2'>Funcionário</th>";
                            echo "<th class='col-md-2'>Criado por</th>";
                            echo "<th class='col-md-1'>Origem</th>";
                            echo "<th class='col-md-1'>Canal de atendimento</th>";
                            echo "<th class='col-md-1'>Assinante</th>";
                            echo "<th class='col-md-1'>Data da ocorrência</th>";
                            echo "<th class='col-md-1'>Data do cadastro</th>";
                        echo "</tr>";
                    echo "</thead>";
                    echo "<tbody>";

                        $usuario_cadastrou = DBRead('', 'tb_pessoa a', "INNER JOIN tb_usuario b ON a.id_pessoa = b.id_pessoa WHERE b.id_usuario = ".$dado['id_usuario_cadastrou']);

                        $tipos = DBRead('', 'tb_erro_atendimento a',"INNER JOIN tb_tipo_erro b ON a.id_tipo_erro = b.id_tipo_erro WHERE a.id_erro_atendimento = '".$dado['id_erro_atendimento']."'", "a.*,b.nome AS nome_tipo");

                        $hora_erro = $dado['hora_erro'];
                        $hora_erro = explode(":", $hora_erro);
                        $hora_erro = $hora_erro[0].":".$hora_erro[1];
						
						if($dado['origem'] == 1){
					        $origem_nome = "Cliente";
					    }else{
					        $origem_nome = "Belluno";
						}
						
						if ($dado['canal_atendimento'] == 1) {
							$canal_atendimento = 'via Telefone';

						} else if ($dado['canal_atendimento'] == 2) {
							$canal_atendimento = 'via Texto';
						}

                        echo "<tr>";
                            
                            echo "<td>".$tipos[0]['nome_tipo']."</td>";
                            echo "<td>".$dado['nome_atendente']."</td>";
                            echo "<td>".$usuario_cadastrou[0]['nome']."</td>";
                            echo "<td>".$origem_nome."</td>";
                            echo "<td>".$canal_atendimento."</td>";
                            echo "<td>".$dado['assinante']."</td>";
                            echo "<td>".converteData($dado['data_erro'])." ".$hora_erro."</td>";
                            echo "<td>".converteDataHora($dado['data_cadastrado'])."</td>";

                        echo "</tr>";
                echo "</tbody>";
                echo "</table>";
                echo "</div>";
                echo "</div>";
                echo "<br><br>";
                echo "<div class='row'>";
                    echo "<div class='col-md-12'>";
                    echo "<table class='table table-hover' style='font-size='14px'>";
                    echo "<thead>";
                        echo "<tr style='background-color: #ddd;'>";
                            echo "<th>Descrição da reclamação/erro</th>";
                        echo "</tr>";
                    echo "</thead>";
                    echo "<tbody>";
                        echo "<tr>";
                            echo "<td><div class='conteudo-editor'>".$dado['descricao_cliente']."</div></td>";
                        echo "</tr>";
                    echo "</tbody>";
                    echo "</table>";
                    echo "</div>";
                echo "</div>";
                if($dado['justificativa']){
                    echo "<br><br>";
                    echo "<div class='row'>";
                        echo "<div class='col-md-12'>";
                            echo "<table class='table table-hover' style='font-size='14px'>";
                                echo "<thead>";
                                    echo "<tr style='background-color: #ddd;'>";
                                        echo "<th class = 'col-md-6'>O que me levou a cometer a reclamação/erro</th>";
                                        echo "<th class = 'col-md-4'>O que farei para não cometer a reclamação/erro novamente</th>";
                                        echo "<th class = 'col-md-2'>Data da justificativa: ".converteDataHora($dado['data_justificativa'])."</th>";
                                    echo "</tr>";
                                echo "</thead>";
                                echo "<tbody>";
                                    echo "<tr>";
                                        echo "<td><div class='conteudo-editor'>".$dado['justificativa']."</div></td>";
                                        echo "<td colspan='2'><div class='conteudo-editor'>".$dado['precaucao_futura']."</div></td>";
                                    echo "</tr>";
                                echo "</tbody>";
                            echo "</table>";				
                        echo "</div>";
                    echo "</div>";
                }			

                if($erro_lider[0]['parecer'] && $erro_lider[0]['parecer'] != ''){

                    $nome_lider_2 = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_usuario = '".$erro_lider[0]['id_usuario']."'");

                    echo "<br><br>";
                    echo "<div class='row'>";
                        echo "<div class='col-xs-12'>";
                            echo "<table class='table table-hover' style='font-size='14px'>";
                                echo "<thead>";
                                    echo "<tr class='info'>";
                                        echo "<th class = 'col-xs-10'>Parecer do líder (".$nome_lider_2[0]['nome'].")</th>";
                                        echo "<th class = 'col-xs-2'>Data do parecer: ".converteDataHora($erro_lider[0]['data_parecer'])."</th>";
                                    echo "</tr>";
                                echo "</thead>";
                                echo "<tbody>";
                                    echo "<tr>";
                                        echo "<td colspan='2'><div class='conteudo-editor'>".$erro_lider[0]['parecer']."</div></td>";
                                    echo "</tr>";
                                echo "</tbody>";
                            echo "</table>";				
                        echo "</div>";
                    echo "</div>";
                    echo "<br>";		
                }
                echo "</div>";
                echo "</div>";
                $contador_dados++;
            }
        }
        echo "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong>Total: </strong> $contador_dados</span></legend>";		
	}else{
            echo "<table class='table table-bordered'>";
                echo "<tbody>";
                    echo "<tr>";
                        echo "<td class='text-center'> <h4>Não foram encontrados resultados!</h4></td>";
                    echo "</tr>";
                echo "</tbody>";
            echo "</table>";
	}
	
}

function relatorio_contagem_cliente($id_contrato_plano_pessoa, $data_de, $data_ate, $tipo_erro, $origem){

	$data_hora = converteDataHora(getDataHora());

	if($tipo_erro){
		$dados_tipo_erro = DBRead('','tb_tipo_erro',"WHERE id_tipo_erro = '".$tipo_erro."'");
		$nome_erro = '<span style="font-size: 14px;"><strong>Tipo de reclamação/erro -</strong> '.$dados_tipo_erro[0]['nome'].'</span>';
		$filtro_erro = " AND id_tipo_erro = '".$tipo_erro."' ";
	}else{
		$nome_erro = '<span style="font-size: 14px;"><strong>Tipo de reclamação/erro -</strong> Todos</span>';
	}

	if($data_de && $data_ate){
	    $periodo_amostra ="<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> De $data_de até $data_ate</span>";
	}elseif($data_de){
	    $periodo_amostra ="<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> A partir de $data_de</span>";
	}elseif($data_ate){
	    $periodo_amostra ="<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> Até $data_ate</span>";
	}else{
	    $periodo_amostra = "";
	}

	if($data_de){
		$filtro_data_de = " AND data_cadastrado >= '".converteData($data_de)." 00:00:00'";
	}
	if($data_ate){
		$filtro_data_ate = " AND data_cadastrado <= '".converteData($data_ate)." 23:59:59'";
	}

	if($id_contrato_plano_pessoa){
		$dados_contrato_plano_pessoa = DBRead('','tb_contrato_plano_pessoa a',"INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_contrato_plano_pessoa='$id_contrato_plano_pessoa'");
		if($dados_contrato_plano_pessoa){
			$nome_empresa = $dados_contrato_plano_pessoa[0]['nome'];
		}else{
			$nome_empresa = 'Não identificada';
		}
		$filtro_contrato = " AND id_contrato_plano_pessoa = '".$id_contrato_plano_pessoa."' ";
	}else{
		$nome_empresa = '<span style="font-size: 14px;">Todos</span>';
	}

	if($origem){
		if($origem == 2){
			$legenda_origem = '<span style="font-size: 14px;"><strong>Origem -</strong> Belluno</span>';
		}else{
			$legenda_origem = '<span style="font-size: 14px;"><strong>Origem -</strong> Cliente</span>';
		}
		
		$filtro_origem = " AND origem = '$origem'";

	}else{
		$legenda_origem = '<span style="font-size: 14px;"><strong>Origem -</strong> Todas</span>';
	}
	
	echo "<div class=\"col-md-8 col-md-offset-2\" style=\"padding: 0\">";
	echo "<legend style=\"text-align:center;\"><strong>Relatório de Contagem por Contratos</strong><br><span style=\"font-size: 14px;\"><strong>Gerado em:</strong> $data_hora</span></legend>";	
	echo "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\">".$periodo_amostra."</legend>";
    echo "<legend style=\"text-align:center; font-size: 14px;\"><strong>Contrato - </strong>$nome_empresa, $nome_erro, $legenda_origem</legend>";

	$dados_consulta = DBRead('','tb_contrato_plano_pessoa a',"INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa INNER JOIN tb_plano c ON a.id_plano = c.id_plano ORDER BY b.nome ASC","b.nome AS nome_empresa, a.*, c.*");  

	if($dados_consulta){
		echo '<table class="table table-hover dataTable" style="margin-bottom:0;">
		      <thead>
		        <tr>
		            <th class="text-left col-md-4">Contrato</th>
		            <th class="text-left col-md-4">Plano</th>
		            <th class="text-left col-md-4">Quantidade de Reclamações/Erros</th>
		        </tr>
		      </thead>
		      <tbody>';              

		$contador_total = 0;

		foreach($dados_consulta as $dado_consulta){
			
			$cont_erro = 0;
			$cont_dados_erros = DBRead('', 'tb_erro_atendimento', "WHERE id_contrato_plano_pessoa = '".$dado_consulta['id_contrato_plano_pessoa']."' AND status != 2 ".$filtro_data_de." ".$filtro_data_ate." ".$filtro_erro." ".$filtro_contrato." ".$filtro_origem." ");
			
			if(($id_contrato_plano_pessoa && $cont_dados_erros) || (!$id_contrato_plano_pessoa)) {

				if($cont_dados_erros){
		   	 		$cont_erro = count($cont_dados_erros);
				}else{
		   	 		$cont_erro = '0';
				}

		   	 	$conteudo_empresa = DBRead('','tb_contrato_plano_pessoa a',"INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_contrato_plano_pessoa = '".$dado_consulta['id_contrato_plano_pessoa']."'");

		   	 	if($conteudo_empresa[0]['nome_contrato']){
		   	 		$empresa = $conteudo_empresa[0]['nome']." (".$conteudo_empresa[0]['nome_contrato'].")";
		   	 	}else{
		   	 		$empresa = $conteudo_empresa[0]['nome'];
		   	 	}

		   	 	if($dado_consulta['nome_contrato']){
	                $nome_contrato = " (".$dado_consulta['nome_contrato'].") ";
	            }else{
	                $nome_contrato = '';
	            }
	            
            	$contrato = $dado_consulta['nome_empresa'] . " ". $nome_contrato ;

				echo '<tr>
			            <td class="text-left">'.$contrato.'</td>  
						<td class="text-left">'.getNomeServico($dado_consulta['cod_servico'])." - ".$dado_consulta['nome'].'</td>                
			            <td class="text-left">'.$cont_erro.'</td>
			        </tr>'; 


			}
			$contador_total = $contador_total + $cont_erro;
		}

		echo '		
		      </tbody>';
		       echo "<tfoot>";
					
					echo '<tr>';
						echo '<th></th>';
						echo '<th>Totais</th>';
						echo '<th>'.$contador_total.'</th>';
					echo '</tr>';

				echo "</tfoot> ";
		echo '</table>

		<br><br><br>';

		echo "<script>
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
			
	}else{
            echo "<table class='table table-bordered'>";
                echo "<tbody>";
                    echo "<tr>";
                        echo "<td class='text-center'> <h4>Não foram encontrados resultados!</h4></td>";
                    echo "</tr>";
                echo "</tbody>";
            echo "</table>";
	}
	echo "<div>";

}

function relatorio_contagem_funcionario($usuario, $id_contrato_plano_pessoa, $data_de, $data_ate, $tipo_erro, $lider, $origem){

	$data_hora = converteDataHora(getDataHora());

	if($tipo_erro){
		$dados_tipo_erro = DBRead('','tb_tipo_erro',"WHERE id_tipo_erro = '".$tipo_erro."'");
		$nome_erro = '<span style="font-size: 14px;"><strong>Tipo de reclamação/erro -</strong> '.$dados_tipo_erro[0]['nome'].'</span>';
		$filtro_erro = " AND a.id_tipo_erro = '".$tipo_erro."' ";
	}else{
		$nome_erro = '<span style="font-size: 14px;"><strong>Tipo de reclamação/erro -</strong> Todos</span>';
	}

	if($data_de && $data_ate){
	    $periodo_amostra ="<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> De $data_de até $data_ate</span>";
	}elseif($data_de){
	    $periodo_amostra ="<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> A partir de $data_de</span>";
	}elseif($data_ate){
	    $periodo_amostra ="<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> Até $data_ate</span>";
	}else{
	    $periodo_amostra = "";
	}

	if($data_de){
		$filtro_data_de = " AND a.data_cadastrado >= '".converteData($data_de)." 00:00:00'";
	}
	if($data_ate){
		$filtro_data_ate = " AND a.data_cadastrado <= '".converteData($data_ate)." 23:59:59'";
	}
	if($lider){
		$dados_lider = DBRead('','tb_usuario a',"INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_usuario='$lider'");
		$nome_lider = '<span style="font-size: 14px;"><strong>Líder Direto -</strong> '.$dados_lider[0]['nome'].'</span>';
		$filtro_lider = " AND b.lider_direto = '".$lider."' ";
	}else{
		$nome_lider = '<span style="font-size: 14px;"><strong>Líder Direto -</strong> Todos</span>';		
	}
	if($usuario){
		$dados_usuario = DBRead('','tb_usuario a',"INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_usuario='$usuario'");
		$filtro_usuario = " AND a.id_usuario = '".$usuario."' ";
		if($dados_usuario){
			$nome_usuario = '<span style="font-size: 14px;"><strong>Funcionário -</strong> '.$dados_usuario[0]['nome'].'</span>';
		}else{
			$nome_usuario = '<span style="font-size: 14px;"><strong>Funcionário -</strong> Não identificado</span>';
		}
	}else{
		$nome_usuario = '<span style="font-size: 14px;"><strong>Funcionário -</strong> Todos</span>';
	}
	if($id_contrato_plano_pessoa){
		$dados_contrato_plano_pessoa = DBRead('','tb_contrato_plano_pessoa a',"INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_contrato_plano_pessoa='$id_contrato_plano_pessoa'");

		if($dados_contrato_plano_pessoa){
			$nome_empresa = $dados_contrato_plano_pessoa[0]['nome'];
		}else{
			$nome_empresa = 'Não identificada';
		}
		$filtro_contrato = " AND a.id_contrato_plano_pessoa = '".$id_contrato_plano_pessoa."' ";
	}else{
		$nome_empresa = '<span style="font-size: 14px;">Todos</span>';
	}

	if($origem){
		if($origem == 2){
			$legenda_origem = '<span style="font-size: 14px;"><strong>Origem -</strong> Belluno</span>';
		}else{
			$legenda_origem = '<span style="font-size: 14px;"><strong>Origem -</strong> Cliente</span>';
		}
		
		$filtro_origem = " AND a.origem = '$origem'";

	}else{
		$legenda_origem = '<span style="font-size: 14px;"><strong>Origem -</strong> Todas</span>';
	}
	
	echo "<div class=\"col-md-8 col-md-offset-2\" style=\"padding: 0\">";
	echo "<legend style=\"text-align:center;\"><strong>Relatório de Contagem por Funcionários</strong><br><span style=\"font-size: 14px;\"><strong>Gerado em:</strong> $data_hora</span></legend>";	
	echo "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\">".$periodo_amostra."</legend>";
    echo "<legend style=\"text-align:center; font-size: 14px;\">$nome_usuario, <strong>Contrato (cliente) - </strong>$nome_empresa, $nome_erro, $nome_lider, $legenda_origem</legend>";

	$dados_consulta = DBRead('','tb_usuario a',"INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.status = 1 ORDER BY b.nome ASC");  

	if($dados_consulta){
		echo '<table class="table table-hover dataTable" style="margin-bottom:0;">
		      <thead>
		        <tr>
		            <th class="text-left col-md-4">Funcionário</th>
		            <th class="text-left col-md-4">Quantidade de Reclamações/Erros</th>
		            <th class="text-left col-md-4">Meses de Contratação</th>
		        </tr>
		      </thead>
		      <tbody>'; 

			
		$contador_total = 0;
		
		foreach($dados_consulta as $dado_consulta){
		
			$cont_erro = 0;
			$cont_dados_erros = DBRead('', 'tb_erro_atendimento a', "INNER JOIN tb_usuario b ON a.id_usuario = b.id_usuario WHERE a.id_usuario = '".$dado_consulta['id_usuario']."' ".$filtro_lider." ".$filtro_erro." ".$filtro_usuario." ".$filtro_contrato." ".$filtro_data_de." ".$filtro_data_ate." ".$filtro_origem." ");
			
			if($lider || $usuario){
				if($cont_dados_erros){
					if($cont_dados_erros){
		   	 			$cont_erro = sizeof($cont_dados_erros);
					}else{
			   	 		$cont_erro = '0';
					}

					if($dado_consulta['id_ponto']){
						$data_string_ponto = '
							{
								"report": {	
									"group_by": "",
									"columns": "name,shift,admission_date,email",
									"employee_id": '.$dado_consulta['id_ponto'].',
									"row_filters": "",
									"format": "json"
								}
							}
						';  
						
						$result_ponto = troca_dados_curl('https://api.pontomais.com.br/external_api/v1/reports/employees', $data_string_ponto, array('Content-Type: application/json','access-token: FcogHwphYbzMk3IF1tXBmh5ypV49O-74CSf7dMxE3cMcabhbaajbefjai'));
					
						
						$meses_contratacao = sprintf("%01.1f", (floor((strtotime(getDataHora('data')) - strtotime(converteData(end(explode(' ', $result_ponto['data'][0][0]['data'][0]['admission_date']))))) / (60 * 60 * 24))) / 30);
					}else{
						$meses_contratacao = "N/D";
					}

					echo '<tr>
				            <td class="text-left">'.$dado_consulta['nome'].'</td>                
				            <td class="text-left">'.$cont_erro.'</td>
				            <td class="text-left">'.$meses_contratacao.'</td>
				        </tr>'; 
					$contador_total = $contador_total + $cont_erro;
				}

			}else{
				if($cont_dados_erros){
		   	 		$cont_erro = sizeof($cont_dados_erros);
				}else{
		   	 		$cont_erro = '0';
				}

				if($dado_consulta['id_ponto']){
					$data_string_ponto = '
						{
							"report": {	
								"group_by": "",
								"columns": "name,shift,admission_date,email",
								"employee_id": '.$dado_consulta['id_ponto'].',
								"row_filters": "",
								"format": "json"
							}
						}
					';  
					
					$result_ponto = troca_dados_curl('https://api.pontomais.com.br/external_api/v1/reports/employees', $data_string_ponto, array('Content-Type: application/json','access-token: FcogHwphYbzMk3IF1tXBmh5ypV49O-74CSf7dMxE3cMcabhbaajbefjai'));
				
					
					$meses_contratacao = sprintf("%01.1f", (floor((strtotime(getDataHora('data')) - strtotime(converteData(end(explode(' ', $result_ponto['data'][0][0]['data'][0]['admission_date']))))) / (60 * 60 * 24))) / 30);
				}else{
					$meses_contratacao = "N/D";
				}

				echo '<tr>
			            <td class="text-left">'.$dado_consulta['nome'].'</td>                
			            <td class="text-left">'.$cont_erro.'</td>
						<td class="text-left">'.$meses_contratacao.'</td>
			        </tr>'; 
				$contador_total = $contador_total + $cont_erro;
			}

				
		}

		echo '		
		      </tbody>';
		       echo "<tfoot>";
					
					echo '<tr>';
						echo '<th>Totais</th>';
						echo '<th>'.$contador_total.'</th>';
						echo '<th></th>';
					echo '</tr>';

				echo "</tfoot> ";
		echo '</table>

		<br><br><br>';

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
			
	}else{
            echo "<table class='table table-bordered'>";
                echo "<tbody>";
                    echo "<tr>";
                        echo "<td class='text-center'> <h4>Não foram encontrados resultados!</h4></td>";
                    echo "</tr>";
                echo "</tbody>";
            echo "</table>";
	}
	echo "<div>";

}
?>