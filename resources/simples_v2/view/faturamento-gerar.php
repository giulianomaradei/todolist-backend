<?php
require_once(__DIR__."/../class/System.php");

$nome_gerar = $_GET['gerar'];

if(!$nome_gerar){
	$nome_gerar = 'call_suporte';
}

$cancelados = $_GET['cancelados'];
$ordenacao = $_GET['ordenacao'];
$ancora = $_GET['ancora'];

$dados_usuario = DBRead('', 'tb_usuario a',"INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE id_usuario = '".$id_usuario."'", "b.nome");
$nome_usuario_session = $dados_usuario[0]['nome'];

if(isset($_GET['gerar'])){
    if($nome_gerar == 'call_suporte'){
        $collapse = '';
        $collapse_icon = 'plus';
		$texto_gerar =  'Gerar Faturamento';
    }else if($nome_gerar == 'gestao_redes'){
        $collapse = '';
        $collapse_icon = 'plus';
		$texto_gerar =  'Gerar Faturamento';
    }else if($nome_gerar == 'call_ativo'){
        $collapse = '';
        $collapse_icon = 'plus';
		$texto_gerar =  'Gerar Faturamento';
    }else if($nome_gerar == 'call_monitoramento'){
        $collapse = '';
        $collapse_icon = 'plus';
		$texto_gerar =  'Gerar Faturamento';
    }else if($nome_gerar == 'adesao'){
        $collapse = '';
        $collapse_icon = 'plus';
		$texto_gerar =  'Exibir Adesões';
    }else if($nome_gerar == 'prepago'){
        $collapse = '';
        $collapse_icon = 'plus';
		$texto_gerar =  'Exibir Pré-Pagos';
    }else{
        $collapse = 'in';
        $collapse_icon = 'minus';
		$texto_gerar =  'Gerar Faturamento';
    }
}else{
    $collapse = 'in';
    $collapse_icon = 'minus';
	$texto_gerar =  'Gerar Faturamento';
}

$data_inicial = new DateTime(getDataHora('data'));
$data_inicial->modify('first day of last month');

$dados_verificacao_alert_call_suporte = DBRead('', 'tb_faturamento a',"INNER JOIN tb_plano b ON a.id_plano = b.id_plano WHERE a.data_referencia = '".$data_inicial->format('Y-m-d')."' AND b.cod_servico = 'call_suporte' AND a.adesao = '0' ", "a.id_faturamento");
// $dados_verificacao_alert_call_gestao_redes = DBRead('', 'tb_faturamento a',"INNER JOIN tb_plano b ON a.id_plano = b.id_plano WHERE a.data_referencia = '".$data_inicial->format('Y-m-d')."' AND b.cod_servico = 'gestao_redes' ", "a.id_faturamento");
$dados_verificacao_alert_call_ativo = DBRead('', 'tb_faturamento a',"INNER JOIN tb_plano b ON a.id_plano = b.id_plano WHERE a.data_referencia = '".$data_inicial->format('Y-m-d')."' AND b.cod_servico = 'call_ativo' AND a.adesao = '0' ", "a.id_faturamento");
$dados_verificacao_alert_call_monitoramento = DBRead('', 'tb_faturamento a',"INNER JOIN tb_plano b ON a.id_plano = b.id_plano WHERE a.data_referencia = '".$data_inicial->format('Y-m-d')."' AND b.cod_servico = 'call_monitoramento' AND a.adesao = '0' ", "a.id_faturamento");

// $dados_comissoes = DBRead('','tb_plantao_redes_comissao',"WHERE data_referencia = '".$data_inicial->format('Y-m-d')."' ");

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

<input type="hidden" id="nome_session" value="<?=$nome_usuario_session?>">

<div class="container-fluid">
	<form method="post" action="/api/ajax?class=Faturamento.php">
		<input type="hidden" name="token" value="<?php echo $request->token ?>">
	    <div class="row">
	        <div class="col-md-4 col-md-offset-4">
	            <div class="panel panel-default noprint">
	                <div class="panel-heading clearfix">
	                    <h3 class="panel-title text-left pull-left" style="margin-top: 2px;">Faturamento:</h3>
	                    <div class="panel-title text-right pull-right"><button data-toggle="collapse" data-target="#accordionRelatorio" class="btn btn-xs btn-info" type="button" title="Visualizar filtros"><i id="i_collapse" class="fa fa-<?=$collapse_icon?>"></i></button></div>
	                </div>
	                <div id="accordionRelatorio" class="panel-collapse collapse <?=$collapse?>">
	                	<div class="panel-body">	                		
							<div class="row">
								<div class="col-md-12">
									<div class="form-group">
										<label>*Serviço:</label>
                                        <select class="form-control input-sm" id="servico" name="servico">
											<option value="adesao" <?php if($nome_gerar == 'adesao'){echo 'selected';}?>>Adesões</option>
                                            <option value="call_ativo" <?php if($nome_gerar == 'call_ativo'){echo 'selected';}?>>Call Center - Ativo</option>
                                            <option value="call_monitoramento" <?php if($nome_gerar == 'call_monitoramento'){echo 'selected';}?>>Call Center - Monitoramento</option>
                                            <option value="call_suporte" <?php if($nome_gerar == 'call_suporte'){echo 'selected';}?>>Call Center - Suporte</option>
                                            <option value="prepago" <?php if($nome_gerar == 'prepago'){echo 'selected';}?>>Pré-Pago</option>
                                            <!-- <option value="gestao_redes" <?php if($nome_gerar == 'gestao_redes'){echo 'selected';}?>>Gestão de Redes</option> -->
										</select>
									</div>
								</div>
							</div>

							<div class="row">
								<div class="col-md-12">
									<div class="form-group">
										<label>*Mostrar Cancelados:</label>
                                        <select class="form-control input-sm" id="cancelados" name="cancelados">
                                            <option value="1" <?php if($cancelados == '1'){ echo 'selected';}?>>Sim</option>
                                            <option value="0" <?php if($cancelados != '1'){ echo 'selected';}?>>Não</option>
                                        </select>
									</div>
								</div>
							</div>

                            <div class="row">
								<div class="col-md-12">
									<div class="form-group">
										<label>*Ordenação:</label>
                                        <select class="form-control input-sm" id="ordenacao" name="ordenacao">  
                                            <option value="cliente" <?php if($ordenacao != 'cliente'){ echo 'selected';}?>>Cliente</option>
                                            <option value="dia_pagamento" <?php if($ordenacao == 'dia_pagamento'){ echo 'selected';}?>>Dia de Pagamento</option>
                                        </select>
									</div>
								</div>
							</div>
							
		                </div>
	            	</div>
                    
                    <input type="hidden" id="ancora" value="<?= $ancora; ?>"/>
	                
	                <div class="panel-footer">
                        <div class="row">
                            <div id="panel_buttons" class="col-md-12" style="text-align: center">
                                <button class="btn btn-primary" name="gerar" id="gerar" value="1" type="submit" disabled><i class="fa fa-check"></i> <?=$texto_gerar?></button>
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
		if(isset($_GET['gerar'])){
			if($nome_gerar == 'call_suporte'){
				faturamento_call_suporte($cancelados, $ordenacao, $request->token);		
			}else if($nome_gerar == 'gestao_redes'){
				faturamento_gestao_redes($cancelados, $ordenacao, $request->token);		
			}else if($nome_gerar == 'call_ativo'){
				faturamento_call_ativo($cancelados, $ordenacao, $request->token);		
			}else if($nome_gerar == 'call_monitoramento'){
				faturamento_call_monitoramento($cancelados, $ordenacao, $request->token);		
			}else if($nome_gerar == 'adesao'){
				faturamento_adesao($cancelados, $ordenacao, $request->token);		
			}else if($nome_gerar == 'prepago'){
				faturamento_prepago($cancelados, $ordenacao, $request->token);		
			}else{
				echo '<div class="col-md-6 col-md-offset-3">';
					echo '<div class="alert alert-danger text-center">Nenhum serviço selecionado!</div>';
				echo "</div>";
			}
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
    	
    	var servico = $("#servico").val();

    	if(servico == 'call_suporte'){
    		var verificacao = '<?=$dados_verificacao_alert_call_suporte?>';
    		var texto_servico = 'Call Suporte';
    	}else if(servico == 'gestao_redes'){
    		var verificacao = '<?=$dados_verificacao_alert_call_gestao_redes?>';
    		var texto_servico = 'Gestão de Redes';

			var dados_comissoes = '<?=$dados_comissoes?>';
			if(!dados_comissoes){
				alert('Para gerar o faturamento de Gestão de Redes deve-se gerar as comissões de plantões antes!');
				verificacao = 1;
				return false;
			}

    	}else if(servico == 'call_ativo'){
    		var verificacao = '<?=$dados_verificacao_alert_call_ativo?>';
    		var texto_servico = 'Call Center - Ativo';

    	}else if(servico == 'call_monitoramento'){
    		var verificacao = '<?=$dados_verificacao_alert_call_monitoramento?>';
    		var texto_servico = 'Call Center - Monitoramento';

    	}else if(servico == 'adesao'){
    		var verificacao = true;

    	}else if(servico == 'prepago'){
    		var verificacao = true;
    	}

    	if(!verificacao){
			if (!confirm('Atenção! Certifique-se que os valores dos contratos de '+texto_servico+' já foram ajustados este mês!')){
    		return false; 
    		}
       	}
    	
        modalAguarde();
    });

    $(document).ready(function(){
	    $('#aguarde').hide();
	    $('#resultado').show();
	    $("#gerar").prop("disabled", false);

	});   

	$(document).on('click', '#modal_id', function(){

		var string = $(this).attr('data-id').split('|');
        var id = string[0];
        var texto_titulo = string[1];

        $("#id_ajuste").val(id);
        $('#titulo').html("Ajuste do faturamento - "+texto_titulo+"");

    });

    $(document).on('click', '#ajustar', function(){

        var id_ajuste = $("#id_ajuste").val();

		var tipo_ajuste = $("#tipo_ajuste").val();
		var valor_ajuste = parseFloat(moedaFloat($("#valor_ajuste").val()));
        var descricao = $("#descricao").val();

        var acrescimo = parseFloat(moedaFloat($("#acrescimo_"+id_ajuste).text()));
        var desconto = parseFloat(moedaFloat($("#desconto_"+id_ajuste).text()));
        var cobranca = parseFloat(moedaFloat($("#cobranca_"+id_ajuste).text()));

        //Verificação dos campos
        if(!id_ajuste || id_ajuste == '' || !descricao || descricao == '' || !tipo_ajuste || tipo_ajuste == ''){
        	alert('Os campos de "tipo", "valor" e "referente a" do ajuste devem estar preenchidos!');
        }else{
        	if(tipo_ajuste == 'acrescimo'){
	        	var texto = 'ACRÉSCIMO';
	        }else{
	        	var texto = 'DESCONTO';
	        }
        	if(confirm("Você está inserindo um "+texto+" no valor de R$ "+floatMoeda((valor_ajuste).toFixed(2))+"?") == true){
	        	
	        		//Insere valores aos campos
		       	if(tipo_ajuste == 'acrescimo'){
		       	    $("#acrescimo_"+id_ajuste).text(floatMoeda((acrescimo+valor_ajuste).toFixed(2)));
		       	    cobranca += valor_ajuste;
		       	    var texto_ajuste = 'Acréscimo: R$ '+floatMoeda((valor_ajuste).toFixed(2));
		        }else{
                    if(cobranca >= valor_ajuste){
                        $("#desconto_"+id_ajuste).text(floatMoeda((desconto+valor_ajuste).toFixed(2)));
                        cobranca -= valor_ajuste;
                        var texto_ajuste = 'Desconto: R$ '+floatMoeda((valor_ajuste).toFixed(2));
                    }else{
                        alert('O valor do desconto deve ser menor ou igual ao valor da cobrança!');
                        return false;
                    }		       	    
		        }
		        //Data e hora atual
				var d = new Date();
				dataHora = (d.toLocaleString());  
				dataHora = dataHora.split(':');
				dataHora = dataHora[0]+':'+dataHora[1];

				

		        $("#cobranca_"+id_ajuste).text(floatMoeda(cobranca.toFixed(2)));
		        
		        //Tirar valor do campo
		        $("#valor_ajuste").val('');
		        $("#descricao").val('');

		        $.ajax({
			        url: "/api/ajax?class=Faturamento.php",
			        method: 'POST',
			        data: {
			            acao: 'ajustar_call_suporte',
			            parametros : {
			               'tipo_ajuste': tipo_ajuste, 
			               'valor_ajuste': valor_ajuste,
			               'descricao': descricao,
			               'id_ajuste': id_ajuste
			            },
			        },
			        success: function(data){
						//Insere uma nova linha no panel
						$('#panel_body_'+id_ajuste).append('<hr id="hr_id_ajuste_'+data+'"><div class="row" id="row_id_ajuste_'+data+'"><div class="col-md-3">'+texto_ajuste+'</div><div class="col-md-3">Referente a: '+descricao+'</div><div class="col-md-3">Data e Hora: '+dataHora+'</div><div class="col-md-2">Usuário: '+$("#nome_session").val()+'</div><div class="col-md-1"><a id="excluir_'+data+'" title="Excluir Ajuste" onclick=\'if (!confirm("Excluir '+texto_ajuste+' ?")) {  return false; } else { exclui_ajuste('+data+', '+id_ajuste+', "'+tipo_ajuste+'", "'+floatMoeda(valor_ajuste)+'"); }\'><i class="fa fa-trash" style="color:#b92c28; cursor: pointer;"></i></a></div></div>');
	                }
		        });   

		        //fechar o modal
		        $('.modal').modal('hide');
        	}
    	}
    });

	$(document).on('change', '#servico', function(){
    	
		var servico = $(this).val();
		if(servico == 'adesao'){
			$("#gerar").html('<i class="fa fa-check"></i> Exibir Adesões');
		}else if(servico == 'prepago'){
			$("#gerar").html('<i class="fa fa-check"></i> Exibir Pré-Pagos');
		}else{
			$("#gerar").html('<i class="fa fa-check"></i> Gerar Faturamento');
		}

	});
</script>
<?php

function faturamento_prepago($cancelados, $ordenacao, $token){

	$id_usuario = $_SESSION['id_usuario'];
	
	if($cancelados == 1){
		$filtro_cancelado = '';
		$filtro_cancelado_dados = "";
	}else{
		$filtro_cancelado = 'AND a.status = 1';
		$filtro_cancelado_dados = "AND b.status = 1";
    }
    
    if($ordenacao == 'cliente'){
        $filtro_ordenacao = ' ORDER BY d.nome ASC';
    }else{
        $filtro_ordenacao = ' ORDER BY c.dia_pagamento ASC';
    }

	$data_inicial = new DateTime(getDataHora('data'));
	$data_inicial->modify('first day of next month');
	$data_final = new DateTime(getDataHora('data'));
	$data_final->modify('last day of next month');
	
	$mes_atual = $data_inicial->format('m');
	$ano_atual = $data_inicial->format('Y');

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

	$data_hoje = getDataHora();
	$data_hoje = converteDataHora($data_hoje);	

	$dados_consulta = DBRead('','tb_faturamento_contrato a',"INNER JOIN tb_faturamento b ON a.id_faturamento = b.id_faturamento INNER JOIN tb_contrato_plano_pessoa c ON a.id_contrato_plano_pessoa = c.id_contrato_plano_pessoa INNER JOIN tb_pessoa d ON c.id_pessoa = d.id_pessoa INNER JOIN tb_plano e ON b.id_plano = e.id_plano WHERE b.data_referencia = '".$data_inicial->format('Y-m-d')."' AND a.contrato_pai = '1' AND b.tipo_cobranca = 'prepago' $filtro_cancelado_dados $filtro_ordenacao  ", "a.*, b.*, c.*, d.*, c.status AS status_contrato, e.nome AS nome_plano, b.status AS status_faturamento, b.valor_adesao AS valor_adesao_faturamento");  

	echo "<div class=\"col-md-12\">";
	echo "<legend style=\"text-align:center;\"><strong>Faturamento de Pré-Pagos: Referente a ".$dados_meses[$mes_atual]." de ".$ano_atual."</strong></legend>";

	echo "<div class='row'>";
		echo "<div class=\"col-md-12\" style='margin-bottom: 20px;'>";

			echo "<div style='text-align:center;'>";
		
				echo '<a style="color:#00008B;" href="/api/iframe?token='.$token.'&view=faturamento-prepago-form&cancelados='.$cancelados.'&ordenacao='.$ordenacao.'"><button class="btn" style="background-color: #801f4f !important; border-color: #801f4f !important; color: #fff !important;"> <i class="fa fa-file-invoice-dollar"></i> Inserir Pré-Pago</button></a>';
			
			echo "</div>";
		echo "</div>";

		echo '
		<style>
			.body_scrol_ativo {
				display:block;
				height:480px;
				overflow:auto;
			}		
		</style>';


		echo "<div class=\"col-md-12\" >";

			if($dados_consulta){
				echo '<form method="post" action="/api/ajax?class=ContaReceber.php" id="controle_conta_receber" style="margin-bottom: 0;">
				<input type="hidden" name="token" value="'.$token.'">';

					echo '<div class="row">';
						echo '<div class="col-md-12">
								<div class="panel-group" id="accordionVinculos" role="tablist">
									<div class="panel" style="background-color: white !important; border: 1px solid #c1c1c1;">
										<div class="panel-heading clearfix" style="border-bottom: 1px solid #c1c1c1;">
											<div class="panel-title text-left pull-left">
												<input type="checkbox" id="checkTodosAtivos" name="checkTodosAtivos" style="position: relative; vertical-align: middle;"> 
												&nbsp
												<span style="position: relative; vertical-align: middle;" id="texto_check">Marcar Todos</span>
											</div>
											
										</div>
										
										<div class="panel-body body_scrol_ativo" style="padding:0px !important; overflow-x: hidden  ;" >
											<div class="row">
												<div class="col-md-12">';

												foreach($dados_consulta as $dado_consulta){
													echo '<div id="'.$dado_consulta['id_contrato_plano_pessoa'].'"></div>';
													$exibir = 0;

													$verificacao_status_faturamento = DBRead('', 'tb_faturamento a', "INNER JOIN tb_faturamento_contrato b ON a.id_faturamento = b.id_faturamento WHERE a.data_referencia = '".$data_inicial->format('Y-m-d')."' AND b.id_contrato_plano_pessoa = '".$dado_consulta['id_contrato_plano_pessoa']."'  ".$filtro_cancelado."");
													
													if($verificacao_status_faturamento){
														$exibir = 1;
													}

													if($exibir != 0 && (!$dado_consulta['contrato_pai'] || $dado_consulta['contrato_pai'] == '0')){

														//NOME DO CONTRATO
														if($dado_consulta['nome_contrato']){
															$nome_contrato = " (".$dado_consulta['nome_contrato'].") ";
														}else{
															$nome_contrato = '';
														}
														$contrato = '<strong style="position: relative; vertical-align: middle;">'.$dado_consulta['nome']."</strong> ".$nome_contrato;
															
														$panel = '<div class="panel panel-default" style="margin:10px;">';
														$cabecalho = '	<div class="panel-heading">';
														
														$dados_conta_receber = DBRead('','tb_conta_receber',"WHERE id_faturamento = '".$dado_consulta['id_faturamento']."' AND (situacao = 'aberta' OR situacao = 'quitada') ");  

														if(!$dados_conta_receber){
															$cabecalho .= '
																<div class= "row">
																	<h3 class="panel-title text-left col-md-6" style="margin-top: 2px;">
																		<input aceita-clona="sim" name="selecionar_ativo[]" type="checkbox" value="'.$dado_consulta['id_faturamento'].'" style="position: relative; vertical-align: middle;">
																		&nbsp
																		'.$contrato.'
																	</h3>
																	<div class="panel-title text-right col-md-6">';


															$botoes = "<a style='color:#8B0000;' href=\"/api/ajax?class=Faturamento.php?cancelar_prepago=".$dado_consulta['id_faturamento']."&cancelados=".$cancelados."&ordenacao=".$ordenacao."&servico=prepago&token=". $token ."\" title='Cancelar' onclick=\"if (!confirm('Cancelar a pré-pago da empresa ".addslashes($dado_consulta['nome']."".$nome_contrato).", referente a ".addslashes($dados_meses[$mes_atual]." de ".$ano_atual)."?')) { return false; } else { modalAguarde(); }\"><span class='btn btn-sm btn-danger'><i class= 'fa fa-window-close-o' ></i> Cancelar </span></a>&nbsp;&nbsp;";

															$cliente_id_cidade = $dado_consulta['id_cidade'];
															$cliente_logradouro = $dado_consulta['logradouro'];
															$cliente_numero = $dado_consulta['numero'];
															$cliente_bairro = $dado_consulta['bairro'];
															$cliente_cep = $dado_consulta['cep'];
															$cliente_razao_social = $dado_consulta['razao_social'];
															$cliente_cpf_cnpj = $dado_consulta['cpf_cnpj'];
															$tipo_pessoa = strtoupper(substr($dado_consulta['tipo'], 1));
															
															if(($cliente_razao_social && $cliente_cpf_cnpj && $cliente_id_cidade && $cliente_logradouro && $cliente_numero && $cliente_bairro) && ($tipo_pessoa == 'J' && valida_cnpj($cliente_cpf_cnpj) || $tipo_pessoa == 'F' && valida_cpf($cliente_cpf_cnpj)) && $cliente_cep){		    		

																// $botoes = $botoes."<a style='color:#0B610B;' id='modal_call_ativo_id' href='' data-toggle='modal' data-target='#modal_call_ativo' data-id='".$dado_consulta['id_faturamento']."|".$contrato."' title='Gerar Conta Receber'><span class='btn btn-sm btn-warning'><i class= 'fas fa-arrow-circle-down'></i> Conta a Receber </span></a>";

																$botoes = $botoes."<a style='color:#0B610B;' href=\"/api/ajax?class=ContaReceber.php?faturamento=".$dado_consulta['id_faturamento']."&cancelados=".$cancelados."&ordenacao=".$ordenacao."&token=". $token ."\" title='Gerar NFS-e' onclick=\"if (!confirm('Gerar Conta a receber de pre-pago a partir do faturamento da empresa ".addslashes($dado_consulta['nome'])." ?')) { return false; } else { modalAguarde(); }\"><span class='btn btn-sm btn-warning'><i class= 'fas fa-arrow-circle-down'></i> Conta a Receber </span></a> 
																<a style='color:#00a388;' href=\"/api/ajax?class=ContaReceber.php?prepago_pix=".$dado_consulta['id_faturamento']."&cancelados=".$cancelados."&ordenacao=".$ordenacao."&token=". $token ."\" title='Pré-pago PIX' onclick=\"if (!confirm('Gerar Conta a receber de pré-pago via PIX a partir do faturamento da empresa ".addslashes($dado_consulta['nome'])." ?')) { return false; } else { modalAguarde(); }\"><span class='btn btn-sm' style='background-color:#00a388; color: white'><i class= 'fas fa-arrow-circle-down'></i> PIX </span></a>";


															}else{

																$alerta_nfs  = 'Para a emissão da NFS-e da pessoa '.addslashes($dado_consulta['nome']."".$nome_contrato).', faltam os seguintes dados:\n';
																if(!$cliente_id_cidade || $cliente_id_cidade == '9999999'){
																	$alerta_nfs  = $alerta_nfs.' - Cidade\n';
																}
																if(!$cliente_logradouro){
																	$alerta_nfs  = $alerta_nfs.' - Logradouro\n';
																}
																if(!$cliente_numero){
																	$alerta_nfs  = $alerta_nfs.' - Número do Logradouro\n';
																}
																if(!$cliente_bairro){
																	$alerta_nfs  = $alerta_nfs.' - Bairro\n';
																}
																if(!$cliente_cep){
																	$alerta_nfs  = $alerta_nfs.' - CEP\n';
																}
																if(!$cliente_razao_social){
																	$alerta_nfs  = $alerta_nfs.' - Razão Social\n';
																}
																if(!$cliente_cpf_cnpj){
																	$alerta_nfs  = $alerta_nfs.' - CPF/CNPJ\n';
																}

																$botoes = $botoes."<a style='color:#0B610B;' href=\"\" title='Gerar Conta Receber' onclick=\"alert('".substr($alerta_nfs, 0, -2)."'); return false;\"><span class='btn btn-sm btn-warning'><i class= 'fas fa-arrow-circle-down'></i> Conta a Receber </span></a> 
																<a style='color:#00a388;' href=\"\" title='Gerar Conta Receber' onclick=\"alert('".substr($alerta_nfs, 0, -2)."'); return false;\"><span class='btn btn-sm' style='background-color:#00a388; color: white'><i class= 'fas fa-arrow-circle-down'></i> PIX </span></a>";
															}

														}else{

															$cabecalho .= '
																<div class= "row">
																	<h3 class="panel-title text-left col-md-6" style="margin-top: 2px;">
																		
																	&nbsp
																	&nbsp
																	&nbsp
																	'.$contrato.'
																	</h3>
																	<div class="panel-title text-right col-md-6">';
															
															$botoes = "<span style='color:#000000;' title='Conta a receber já gerada'><span class='btn btn-sm btn-default' style='pointer-events: none;' disabled><i class= 'fa fa-window-close-o' ></i> Cancelar </span></a>&nbsp;&nbsp;";

															$botoes = $botoes."<span style='color:#000000;' data-toggle='tooltip' title='Data e hora da emissão: ".converteDataHora($dados_conta_receber[0]['data_cadastro'])."'><span class='btn btn-sm btn-default' style='pointer-events: none;' disabled><i class= 'fas fa-arrow-circle-down'></i> Conta a Receber </span></span> 
															<span style='color:#000000;' data-toggle='tooltip' title='Data e hora da emissão: ".converteDataHora($dados_conta_receber[0]['data_cadastro'])."'><span class='btn btn-sm btn-default' style='pointer-events: none;' disabled><i class= 'fas fa-arrow-circle-down'></i> PIX </span></span>";
														}

														echo $panel;
														echo $cabecalho;        
														echo $botoes;            	

																echo '
																	</div>
																</div>
															</div>

															<div class="panel-body" id="panel_body_'.$dado_consulta['id_contrato_plano_pessoa'].'">
																<div class="row">
																	<div class="col-md-4"><strong>Plano: </strong>'.$dado_consulta['nome_plano'].'</div>
																	<div class="col-md-4"><strong>Dia do Pagamento: </strong> '.$dado_consulta['dia_pagamento'].'</div>
																	<div class="col-md-4"><strong>Valor: </strong>R$ '.converteMoeda($dado_consulta['valor_total'], 'moeda').'</div>
																</div>';
													echo '</div>
														</div>';			
															
													}
												}

												echo '
												</div>
											</div>
										</div>
										<div class="panel-footer" style="background-color: white !important; border-top: 1px solid #c1c1c1;">
											<div class="row">
												<div class="col-md-12" style="text-align: center">

													<input type="hidden" id="operacao_conta_receber" name="abc" value="1"/>
													<input type="hidden" id="ordenacao" name="ordenacao" value="'.$ordenacao.'"/>
													<input type="hidden" id="cancelados" name="cancelados" value="'.$cancelados.'"/>

													<button class="btn btn-sm btn-primary" id="gerar_contas_receber_todos" type="button" data-toggle="modal" data-target="#modal_call_ativo_check" disabled><i class="fas fa-arrow-circle-down"></i> Gerar Contas a Receber</button>
																								
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>';
					echo '</form>';

			}else{

				echo "<div class='col-md-12'>";
					echo "<table class='table table-bordered'>";
						echo "<tbody>";
							echo "<tr>";
								echo "<td class='text-center'> <h4>Não foram encontradas Pré-Pagos!</h4></td>";
							echo "</tr>";
						echo "</tbody>";
					echo "</table>";
				echo "</div>";

			}
		echo "</div>";
	echo "</div>";

  	?>

	<script>

		$(document).ready(function() {
			ancora = $("#ancora").val();
			if(ancora && ancora != ''){
	        	window.location.href = '#'+ancora;
			}
		  	$('[data-toggle="tooltip"]').tooltip();   
	    });

		//call_ativo
	    $(document).on('click', '#modal_call_ativo_id', function(){

			var string = $(this).attr('data-id').split('|');
	        var id = string[0];
	        var texto_titulo = string[1];

	        $("#id_ajuste_call_ativo").val(id);
	        $("#nome_empresa_cliente").val(texto_titulo);
	        $('#titulo_call_ativo').html("Inserir Conta a Receber - "+texto_titulo+"");

	    });
		
		$('#salvar_call_ativo').click(function(){
	
			var nome_empresa_cliente = $('#nome_empresa_cliente').val();
			nome_empresa_cliente = nome_empresa_cliente.replace("<strong>", "");
			nome_empresa_cliente = nome_empresa_cliente.replace("</strong>", "");

			if($('#descricao_call_ativo').val() != ''){
			    if(!confirm('Inserir faturamento da empresa '+nome_empresa_cliente+'?')) {
					return false;
				}else{
                    modalAguarde();
			    	$('#inserir_conta_receber_call_ativo').submit();
				}
			}else{
				alert('Insira uma descrição!');
				return false;
			}
			return false;
		});

		$(document).on('click', '#checkTodosAtivos', function () {
			if ($(this).is(':checked')){
				$("#gerar_contas_receber_email_todos").attr("disabled", false);
				$("#gerar_contas_receber_boleto_todos").attr("disabled", false);
				$("#gerar_contas_receber_todos").attr("disabled", false);
			
				$('[name ="selecionar_ativo[]"]').prop("checked", true);
				$('#texto_check').text("Desmarcar Todos");
			}else{
				$("#gerar_contas_receber_email_todos").attr("disabled", true);
				$("#gerar_contas_receber_boleto_todos").attr("disabled", true);
				$("#gerar_contas_receber_todos").attr("disabled", true);

				$('[name ="selecionar_ativo[]"]').prop("checked", false);
				$('#texto_check').text("Marcar Todos");
			}
		});

		$(document).on('click', '#gerar_contas_receber_todos', function(){
			if (!confirm('Atenção! Certifique-se que os valores dos contratos de Call Center - Ativos já foram ajustados este mês!')){
    		return false; 
    		}			
			$('#operacao_conta_receber').attr('name', 'conta_receber_check_ativo');
    		$('#controle_conta_receber').submit();
		});
  
		$(document).on('click', '[name ="selecionar_ativo[]"]', function(){
			var libera = 0;
			var nao_libera = 0;
			$("[name ='selecionar_ativo[]']").each(function(){
				if ($(this).is(':checked')){
					libera ++;
				}else{
					nao_libera ++;
				}
			});
			if(libera != 0){
				$("#gerar_contas_receber_email_todos").attr("disabled", false);
				$("#gerar_contas_receber_boleto_todos").attr("disabled", false);
				$("#gerar_contas_receber_todos").attr("disabled", false);

				if(nao_libera == 0){
					$("#checkTodosAtivos").prop("checked", true);
					$('#texto_check').text("Desmarcar Todos");
				}else{
					$("#checkTodosAtivos").prop("checked", false);
					$('#texto_check').text("Marcar Todos");
				}
				
			}else{
				$("#gerar_contas_receber_email_todos").attr("disabled", true);
				$("#gerar_contas_receber_boleto_todos").attr("disabled", true);
				$("#gerar_contas_receber_todos").attr("disabled", true);

				$("#checkTodosAtivos").prop("checked", false);
				$('#texto_check').text("Marcar Todos");
			}
		});

	</script>
	<?php
}

function faturamento_adesao($cancelados, $ordenacao, $token){

	$id_usuario = $_SESSION['id_usuario'];
	
	if($cancelados == 1){
		$filtro_cancelado = '';
		$filtro_cancelado_dados = "";
	}else{
		$filtro_cancelado = 'AND a.status = 1';
		$filtro_cancelado_dados = "AND b.status = 1";
    }
    
    if($ordenacao == 'cliente'){
        $filtro_ordenacao = ' ORDER BY d.nome ASC';
    }else{
        $filtro_ordenacao = ' ORDER BY c.dia_pagamento ASC';
    }

	$data_inicial = new DateTime(getDataHora('data'));
	$data_inicial->modify('first day of last month');
	$data_final = new DateTime(getDataHora('data'));
	$data_final->modify('last day of last month');
	
	$mes_atual = $data_inicial->format('m');
	$ano_atual = $data_inicial->format('Y');

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

	$data_hoje = getDataHora();
	$data_hoje = converteDataHora($data_hoje);	

	$dados_consulta = DBRead('','tb_faturamento_contrato a',"INNER JOIN tb_faturamento b ON a.id_faturamento = b.id_faturamento INNER JOIN tb_contrato_plano_pessoa c ON a.id_contrato_plano_pessoa = c.id_contrato_plano_pessoa INNER JOIN tb_pessoa d ON c.id_pessoa = d.id_pessoa INNER JOIN tb_plano e ON b.id_plano = e.id_plano WHERE b.data_referencia = '".$data_inicial->format('Y-m-d')."' AND a.contrato_pai = '1' AND b.adesao = '1' $filtro_cancelado_dados $filtro_ordenacao  ", "a.*, b.*, c.*, d.*, c.status AS status_contrato, e.nome AS nome_plano, b.status AS status_faturamento, b.valor_adesao AS valor_adesao_faturamento");  

	echo "<div class=\"col-md-12\">";
	echo "<legend style=\"text-align:center;\"><strong>Faturamento de Adesões: Referente a ".$dados_meses[$mes_atual]." de ".$ano_atual."</strong></legend>";

	echo "<div class='row'>";
		echo "<div class=\"col-md-12\" style='margin-bottom: 20px;'>";

			echo "<div style='text-align:center;'>";
		
				echo '<a style="color:#00008B;" href="/api/iframe?token='.$token.'&view=faturamento-adesao-form&cancelados='.$cancelados.'&ordenacao='.$ordenacao.'"><button class="btn" style="background-color: #801f4f !important; border-color: #801f4f !important; color: #fff !important;"> <i class="fa fa-file-invoice-dollar"></i> Inserir Adesão</button></a>';
			
			echo "</div>";
		echo "</div>";

		echo '
		<style>
			.body_scrol_ativo {
				display:block;
				height:480px;
				overflow:auto;
			}		
		</style>';


		echo "<div class=\"col-md-12\" >";

			if($dados_consulta){
				echo '<form method="post" action="/api/ajax?class=ContaReceber.php" id="controle_conta_receber" style="margin-bottom: 0;">
				<input type="hidden" name="token" value="'.$token.'">';

					echo '<div class="row">';
						echo '<div class="col-md-12">
								<div class="panel-group" id="accordionVinculos" role="tablist">
									<div class="panel" style="background-color: white !important; border: 1px solid #c1c1c1;">
										<div class="panel-heading clearfix" style="border-bottom: 1px solid #c1c1c1;">
											<div class="panel-title text-left pull-left">
												<input type="checkbox" id="checkTodosAtivos" name="checkTodosAtivos" style="position: relative; vertical-align: middle;"> 
												&nbsp
												<span style="position: relative; vertical-align: middle;" id="texto_check">Marcar Todos</span>
											</div>
											
										</div>
										
										<div class="panel-body body_scrol_ativo" style="padding:0px !important; overflow-x: hidden  ;" >
											<div class="row">
												<div class="col-md-12">';

												foreach($dados_consulta as $dado_consulta){
													echo '<div id="'.$dado_consulta['id_contrato_plano_pessoa'].'"></div>';
													$exibir = 0;

													$verificacao_status_faturamento = DBRead('', 'tb_faturamento a', "INNER JOIN tb_faturamento_contrato b ON a.id_faturamento = b.id_faturamento WHERE a.data_referencia = '".$data_inicial->format('Y-m-d')."' AND b.id_contrato_plano_pessoa = '".$dado_consulta['id_contrato_plano_pessoa']."' AND a.adesao = '1' ".$filtro_cancelado."");
													
													if($verificacao_status_faturamento){
														$exibir = 1;
													}

													if($exibir != 0 && (!$dado_consulta['contrato_pai'] || $dado_consulta['contrato_pai'] == '0')){

														//NOME DO CONTRATO
														if($dado_consulta['nome_contrato']){
															$nome_contrato = " (".$dado_consulta['nome_contrato'].") ";
														}else{
															$nome_contrato = '';
														}
														$contrato = '<strong style="position: relative; vertical-align: middle;">'.$dado_consulta['nome']."</strong> ".$nome_contrato;
															
														$panel = '<div class="panel panel-default" style="margin:10px;">';
														$cabecalho = '	<div class="panel-heading">';
														
														$dados_conta_receber = DBRead('','tb_conta_receber',"WHERE id_faturamento = '".$dado_consulta['id_faturamento']."' AND (situacao = 'aberta' OR situacao = 'quitada') ");  

														if(!$dados_conta_receber){
															$cabecalho .= '
																<div class= "row">
																	<h3 class="panel-title text-left col-md-6" style="margin-top: 2px;">
																		<input aceita-clona="sim" name="selecionar_ativo[]" type="checkbox" value="'.$dado_consulta['id_faturamento'].'" style="position: relative; vertical-align: middle;">
																		&nbsp
																		'.$contrato.'
																	</h3>
																	<div class="panel-title text-right col-md-6">';


															$botoes = "<a style='color:#8B0000;' href=\"/api/ajax?class=Faturamento.php?cancelar_adesao=".$dado_consulta['id_faturamento']."&cancelados=".$cancelados."&ordenacao=".$ordenacao."&servico=adesao&token=". $token ."\" title='Cancelar' onclick=\"if (!confirm('Cancelar a adesão da empresa ".addslashes($dado_consulta['nome']."".$nome_contrato).", referente a ".addslashes($dados_meses[$mes_atual]." de ".$ano_atual)."?')) { return false; } else { modalAguarde(); }\"><span class='btn btn-sm btn-danger'><i class= 'fa fa-window-close-o' ></i> Cancelar </span></a>&nbsp;&nbsp;";

															$cliente_id_cidade = $dado_consulta['id_cidade'];
															$cliente_logradouro = $dado_consulta['logradouro'];
															$cliente_numero = $dado_consulta['numero'];
															$cliente_bairro = $dado_consulta['bairro'];
															$cliente_cep = $dado_consulta['cep'];
															$cliente_razao_social = $dado_consulta['razao_social'];
															$cliente_cpf_cnpj = $dado_consulta['cpf_cnpj'];
															$tipo_pessoa = strtoupper(substr($dado_consulta['tipo'], 1));
															
															if(($cliente_razao_social && $cliente_cpf_cnpj && $cliente_id_cidade && $cliente_logradouro && $cliente_numero && $cliente_bairro) && ($tipo_pessoa == 'J' && valida_cnpj($cliente_cpf_cnpj) || $tipo_pessoa == 'F' && valida_cpf($cliente_cpf_cnpj)) && $cliente_cep){		    		

																// $botoes = $botoes."<a style='color:#0B610B;' id='modal_call_ativo_id' href='' data-toggle='modal' data-target='#modal_call_ativo' data-id='".$dado_consulta['id_faturamento']."|".$contrato."' title='Gerar Conta Receber'><span class='btn btn-sm btn-warning'><i class= 'fas fa-arrow-circle-down'></i> Conta a Receber </span></a>";

																$botoes = $botoes."<a style='color:#0B610B;' href=\"/api/ajax?class=ContaReceber.php?faturamento=".$dado_consulta['id_faturamento']."&cancelados=".$cancelados."&ordenacao=".$ordenacao."&token=". $token ."\" title='Gerar NFS-e' onclick=\"if (!confirm('Gerar Conta a receber de adesão a partir do faturamento da empresa ".addslashes($dado_consulta['nome'])." ?')) { return false; } else { modalAguarde(); }\"><span class='btn btn-sm btn-warning'><i class= 'fas fa-arrow-circle-down'></i> Conta a Receber </span></a> 
																<a style='color:#00a388;' href=\"/api/ajax?class=ContaReceber.php?adesao_pix=".$dado_consulta['id_faturamento']."&cancelados=".$cancelados."&ordenacao=".$ordenacao."&token=". $token ."\" title='Adesão PIX' onclick=\"if (!confirm('Gerar Conta a receber de adesão via PIX a partir do faturamento da empresa ".addslashes($dado_consulta['nome'])." ?')) { return false; } else { modalAguarde(); }\"><span class='btn btn-sm' style='background-color:#00a388; color: white'><i class= 'fas fa-arrow-circle-down'></i> PIX </span></a>";


															}else{

																$alerta_nfs  = 'Para a emissão da NFS-e da pessoa '.addslashes($dado_consulta['nome']."".$nome_contrato).', faltam os seguintes dados:\n';
																if(!$cliente_id_cidade || $cliente_id_cidade == '9999999'){
																	$alerta_nfs  = $alerta_nfs.' - Cidade\n';
																}
																if(!$cliente_logradouro){
																	$alerta_nfs  = $alerta_nfs.' - Logradouro\n';
																}
																if(!$cliente_numero){
																	$alerta_nfs  = $alerta_nfs.' - Número do Logradouro\n';
																}
																if(!$cliente_bairro){
																	$alerta_nfs  = $alerta_nfs.' - Bairro\n';
																}
																if(!$cliente_cep){
																	$alerta_nfs  = $alerta_nfs.' - CEP\n';
																}
																if(!$cliente_razao_social){
																	$alerta_nfs  = $alerta_nfs.' - Razão Social\n';
																}
																if(!$cliente_cpf_cnpj){
																	$alerta_nfs  = $alerta_nfs.' - CPF/CNPJ\n';
																}

																$botoes = $botoes."<a style='color:#0B610B;' href=\"\" title='Gerar Conta Receber' onclick=\"alert('".substr($alerta_nfs, 0, -2)."'); return false;\"><span class='btn btn-sm btn-warning'><i class= 'fas fa-arrow-circle-down'></i> Conta a Receber </span></a> 
																<a style='color:#00a388;' href=\"\" title='Gerar Conta Receber' onclick=\"alert('".substr($alerta_nfs, 0, -2)."'); return false;\"><span class='btn btn-sm' style='background-color:#00a388; color: white'><i class= 'fas fa-arrow-circle-down'></i> PIX </span></a>";
															}

														}else{

															$cabecalho .= '
																<div class= "row">
																	<h3 class="panel-title text-left col-md-6" style="margin-top: 2px;">
																		
																	&nbsp
																	&nbsp
																	&nbsp
																	'.$contrato.'
																	</h3>
																	<div class="panel-title text-right col-md-6">';
															
															$botoes = "<span style='color:#000000;' title='Conta a receber já gerada'><span class='btn btn-sm btn-default' style='pointer-events: none;' disabled><i class= 'fa fa-window-close-o' ></i> Cancelar </span></a>&nbsp;&nbsp;";

															$botoes = $botoes."<span style='color:#000000;' data-toggle='tooltip' title='Data e hora da emissão: ".converteDataHora($dados_conta_receber[0]['data_cadastro'])."'><span class='btn btn-sm btn-default' style='pointer-events: none;' disabled><i class= 'fas fa-arrow-circle-down'></i> Conta a Receber </span></span> 
															<span style='color:#000000;' data-toggle='tooltip' title='Data e hora da emissão: ".converteDataHora($dados_conta_receber[0]['data_cadastro'])."'><span class='btn btn-sm btn-default' style='pointer-events: none;' disabled><i class= 'fas fa-arrow-circle-down'></i> PIX </span></span>";
														}

														echo $panel;
														echo $cabecalho;        
														echo $botoes;            	

																echo '
																	</div>
																</div>
															</div>

															<div class="panel-body" id="panel_body_'.$dado_consulta['id_contrato_plano_pessoa'].'">
																<div class="row">
																	<div class="col-md-4"><strong>Plano: </strong>'.$dado_consulta['nome_plano'].'</div>
																	<div class="col-md-4"><strong>Dia do Vencimento: </strong> '.converteDataHora($dado_consulta['dia_pagamento_adesao'], 'data').'</div>
																	<div class="col-md-4"><strong>Valor da Adesão: </strong>R$ '.converteMoeda($dado_consulta['valor_adesao_faturamento'], 'moeda').'</div>
																</div>';
													echo '</div>
														</div>';			
															
													}
												}

												echo '
												</div>
											</div>
										</div>
										<div class="panel-footer" style="background-color: white !important; border-top: 1px solid #c1c1c1;">
											<div class="row">
												<div class="col-md-12" style="text-align: center">

													<input type="hidden" id="operacao_conta_receber" name="abc" value="1"/>
													<input type="hidden" id="ordenacao" name="ordenacao" value="'.$ordenacao.'"/>
													<input type="hidden" id="cancelados" name="cancelados" value="'.$cancelados.'"/>

													<button class="btn btn-sm btn-primary" id="gerar_contas_receber_todos" type="button" data-toggle="modal" data-target="#modal_call_ativo_check" disabled><i class="fas fa-arrow-circle-down"></i> Gerar Contas a Receber</button>
																								
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>';
					echo '</form>';

			}else{

				echo "<div class='col-md-12'>";
					echo "<table class='table table-bordered'>";
						echo "<tbody>";
							echo "<tr>";
								echo "<td class='text-center'> <h4>Não foram encontradas adesões!</h4></td>";
							echo "</tr>";
						echo "</tbody>";
					echo "</table>";
				echo "</div>";

			}
		echo "</div>";
	echo "</div>";


	//MODAL
	// echo '
	// <div class="modal fade" id="modal_call_ativo" role="dialog">
    // 	<div class="modal-dialog">
	// 	    <div class="modal-content">
	// 		    <form method="post" action="class/ContaReceber.php" id="inserir_conta_receber_call_ativo" style="margin-bottom: 0;">

	// 		        <div class="modal-header">
	//             		<h3 class="panel-title text-left pull-left" style="margin-top: 2px;" id="titulo_call_ativo">*Descrição da Nota:</h3>
	// 		        </div>
	// 		        <div class="modal-body">
	// 					<input type="hidden" name="id_ajuste_call_ativo" id="id_ajuste_call_ativo">
	// 					<input type="hidden" name="cancelados_call_ativo" id="cancelados_call_ativo" value="'.$cancelados.'">
	// 					<input type="hidden" name="ordenacao_call_ativo" id="ordenacao_call_ativo" value="'.$ordenacao.'">
	// 					<input type="hidden" name="nome_empresa_cliente" id="nome_empresa_cliente">
				       	
	// 					<div class="row">
	// 						<div class="col-md-12">
	// 							<div class="form-group" >
	//                     			<textarea name="descricao_call_ativo" id="descricao_call_ativo" type="text" class="form-control input-sm" rows="5"></textarea>
	// 							</div>
	// 						</div>
	// 					</div>
								
	// 				</div>
	//                 <div class="modal-footer">
	//                     <div class="row">
	//                         <div id="panel_buttons" class="col-md-12" style="text-align: center">
    //                             <input type="hidden" id="operacao" value="2" name="faturamento_call_ativo"/>
	// 				          	<a href="#" id="salvar_call_ativo" class="btn btn-primary"><i class="fas fa-check"></i> Inserir</a>
	//                         </div>
	//                     </div>
	//                 </div>
	// 			</form>
    //     	</div>
    //   	</div>
  	// </div>';

  	?>

	<script>

		$(document).ready(function() {
			ancora = $("#ancora").val();
			if(ancora && ancora != ''){
	        	window.location.href = '#'+ancora;
			}
		  	$('[data-toggle="tooltip"]').tooltip();   
	    });

		//call_ativo
	    $(document).on('click', '#modal_call_ativo_id', function(){

			var string = $(this).attr('data-id').split('|');
	        var id = string[0];
	        var texto_titulo = string[1];

	        $("#id_ajuste_call_ativo").val(id);
	        $("#nome_empresa_cliente").val(texto_titulo);
	        $('#titulo_call_ativo').html("Inserir Conta a Receber - "+texto_titulo+"");

	    });
		
		$('#salvar_call_ativo').click(function(){
	
			var nome_empresa_cliente = $('#nome_empresa_cliente').val();
			nome_empresa_cliente = nome_empresa_cliente.replace("<strong>", "");
			nome_empresa_cliente = nome_empresa_cliente.replace("</strong>", "");

			if($('#descricao_call_ativo').val() != ''){
			    if(!confirm('Inserir faturamento da empresa '+nome_empresa_cliente+'?')) {
					return false;
				}else{
                    modalAguarde();
			    	$('#inserir_conta_receber_call_ativo').submit();
				}
			}else{
				alert('Insira uma descrição!');
				return false;
			}
			return false;
		});

		$(document).on('click', '#checkTodosAtivos', function () {
			if ($(this).is(':checked')){
				$("#gerar_contas_receber_email_todos").attr("disabled", false);
				$("#gerar_contas_receber_boleto_todos").attr("disabled", false);
				$("#gerar_contas_receber_todos").attr("disabled", false);
			
				$('[name ="selecionar_ativo[]"]').prop("checked", true);
				$('#texto_check').text("Desmarcar Todos");
			}else{
				$("#gerar_contas_receber_email_todos").attr("disabled", true);
				$("#gerar_contas_receber_boleto_todos").attr("disabled", true);
				$("#gerar_contas_receber_todos").attr("disabled", true);

				$('[name ="selecionar_ativo[]"]').prop("checked", false);
				$('#texto_check').text("Marcar Todos");
			}
		});

		$(document).on('click', '#gerar_contas_receber_todos', function(){
			if (!confirm('Atenção! Certifique-se que os valores dos contratos de Call Center - Ativos já foram ajustados este mês!')){
    		return false; 
    		}			
			$('#operacao_conta_receber').attr('name', 'conta_receber_check_ativo');
    		$('#controle_conta_receber').submit();
		});
  
		$(document).on('click', '[name ="selecionar_ativo[]"]', function(){
			var libera = 0;
			var nao_libera = 0;
			$("[name ='selecionar_ativo[]']").each(function(){
				if ($(this).is(':checked')){
					libera ++;
				}else{
					nao_libera ++;
				}
			});
			if(libera != 0){
				$("#gerar_contas_receber_email_todos").attr("disabled", false);
				$("#gerar_contas_receber_boleto_todos").attr("disabled", false);
				$("#gerar_contas_receber_todos").attr("disabled", false);

				if(nao_libera == 0){
					$("#checkTodosAtivos").prop("checked", true);
					$('#texto_check').text("Desmarcar Todos");
				}else{
					$("#checkTodosAtivos").prop("checked", false);
					$('#texto_check').text("Marcar Todos");
				}
				
			}else{
				$("#gerar_contas_receber_email_todos").attr("disabled", true);
				$("#gerar_contas_receber_boleto_todos").attr("disabled", true);
				$("#gerar_contas_receber_todos").attr("disabled", true);

				$("#checkTodosAtivos").prop("checked", false);
				$('#texto_check').text("Marcar Todos");
			}
		});

	</script>
	<?php
}

function faturamento_call_suporte($cancelados, $ordenacao, $token){
	
	$id_usuario = $_SESSION['id_usuario'];
	
	if($cancelados == 1){
		$filtro_cancelado = '';
		$filtro_cancelado_dados = "";
	}else{
		$filtro_cancelado = 'AND a.status = 1';
		$filtro_cancelado_dados = "AND b.status = 1";
    }
    
    if($ordenacao == 'cliente'){
        $filtro_ordenacao = ' ORDER BY d.nome ASC';
    }else{
        $filtro_ordenacao = ' ORDER BY c.dia_pagamento ASC';
    }

	$data_inicial = new DateTime(getDataHora('data'));
	$data_inicial->modify('first day of last month');
	$data_final = new DateTime(getDataHora('data'));
	$data_final->modify('last day of last month');
	
	$mes_atual = $data_inicial->format('m');
	$ano_atual = $data_inicial->format('Y');

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

	$data_hoje = getDataHora();
	$data_hoje = converteDataHora($data_hoje);	

	$dados_consulta = DBRead('','tb_faturamento_contrato a',"INNER JOIN tb_faturamento b ON a.id_faturamento = b.id_faturamento INNER JOIN tb_contrato_plano_pessoa c ON a.id_contrato_plano_pessoa = c.id_contrato_plano_pessoa INNER JOIN tb_pessoa d ON c.id_pessoa = d.id_pessoa INNER JOIN tb_plano e ON c.id_plano = e.id_plano WHERE e.cod_servico = 'call_suporte' AND b.data_referencia = '".$data_inicial->format('Y-m-d')."' AND a.contrato_pai = '1' AND b.adesao = '0' $filtro_cancelado_dados $filtro_ordenacao ", "a.*, b.*, c.*, d.*, c.status AS status_contrato, e.nome AS nome_plano, b.qtd_clientes_teto AS quantidade_clientes_teto");  


	echo "<div class=\"col-md-12\" style=\"padding: 0\">";
	echo "<legend style=\"text-align:center;\"><strong>Faturamento de Call Center - Suporte: Referente a ".$dados_meses[$mes_atual]." de ".$ano_atual."</strong></legend>";

	echo "<div class=\"col-md-12\" >";

	if($dados_consulta){

		echo '
		<style>
			.body_scrol_ativo {
				display:block;
				height:480px;
				overflow:auto;
			}		
		</style>';

		echo '<form method="post" action="/api/ajax?class=ContaReceber.php" id="controle_conta_receber" style="margin-bottom: 0;">
		<input type="hidden" name="token" value="'.$token.'">';

			echo '<div class="row">';
				echo '<div class="col-md-12">
						<div class="panel-group" id="accordionVinculos" role="tablist">
							<div class="panel" style="background-color: white !important; border: 1px solid #c1c1c1;">
								<div class="panel-heading clearfix" style="border-bottom: 1px solid #c1c1c1;">
									<div class="panel-title text-left pull-left">
										<input type="checkbox" id="checkTodosSuporte" name="checkTodosSuporte" style="position: relative; vertical-align: middle;"> 
										&nbsp
										<span style="position: relative; vertical-align: middle;" id="texto_check">Marcar Todos</span>
									</div>
									
								</div>
								
								<div class="panel-body body_scrol_ativo" style="padding:0px !important; overflow-x: hidden  ;" >
									<div class="row">
										<div class="col-md-12">';
											foreach($dados_consulta as $dado_consulta){
												echo '<div id="'.$dado_consulta['id_contrato_plano_pessoa'].'"></div>';
												$exibir = 0;

												if($dado_consulta['status_contrato'] == '1' || $dado_consulta['data_status'] >= $data_inicial->format('Y-m-d')){
													$verificacao_status_faturamento = DBRead('', 'tb_faturamento a', "INNER JOIN tb_faturamento_contrato b ON a.id_faturamento = b.id_faturamento WHERE a.data_referencia = '".$data_inicial->format('Y-m-d')."' AND b.id_contrato_plano_pessoa = '".$dado_consulta['id_contrato_plano_pessoa']."' AND a.adesao = '0' ".$filtro_cancelado." ");
													
													if($verificacao_status_faturamento){
														$exibir = 1;
													}
												}

												$dados_consulta_filho = DBRead('','tb_contrato_plano_pessoa',"WHERE contrato_pai = '".$dado_consulta['id_contrato_plano_pessoa']."' ");

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

												if($exibir != 0 && (!$dado_consulta['contrato_pai'] || $dado_consulta['contrato_pai'] == '0')){


													//NOME DO CONTRATO
													if($dado_consulta['nome_contrato']){
														$nome_contrato = " (".$dado_consulta['nome_contrato'].") ";
													}else{
														$nome_contrato = '';
													}
													$contrato = "<strong>".$dado_consulta['nome']."</strong> ".$nome_contrato."".$contrato_filho;
														
													//Verifica acrescimo, desconto, cobranca, etc
													$dados_verificacao = DBRead('', 'tb_faturamento a',"INNER JOIN tb_faturamento_contrato b ON a.id_faturamento = b.id_faturamento WHERE b.id_contrato_plano_pessoa = '".$dado_consulta['id_contrato_plano_pessoa']."' AND a.data_referencia = '".$data_inicial->format('Y-m-d')."' AND a.adesao = '0' ");

													if($dados_verificacao[0]['tipo_cobranca'] == 'mensal_desafogo'){
														$tipo_cobranca = "Mensal com Desafogo (".$dados_verificacao[0]['desafogo_contrato']."%)";
													}else if($dados_verificacao[0]['tipo_cobranca'] == 'x_cliente_base'){
														$tipo_cobranca = "Até X Clientes na Base";
													}else if($dado_consulta[0]['tipo_cobranca'] == 'unitario'){
														$tipo_cobranca = "Unitário";
													}else if($dado_consulta[0]['tipo_cobranca'] == 'prepago'){
														$tipo_cobranca = "Pré-pago";
													}else{
														$tipo_cobranca = ucfirst($dados_verificacao[0]['tipo_cobranca']);
													}

													if($dados_verificacao[0]['remove_duplicados_contrato'] == '1'){
														$remove_duplicados = "Sim (".$dados_verificacao[0]['minutos_duplicados_contrato']." minutos)";
													}else{
														$remove_duplicados = "Não";
													}
													if($dados_verificacao[0]['status'] != 0){
														$panel = '<div class="panel panel-default" style="margin:10px;">';

														

																
																			
															$dados_conta_receber = DBRead('','tb_conta_receber',"WHERE id_faturamento = '".$dado_consulta['id_faturamento']."' AND (situacao = 'aberta' OR situacao = 'quitada') ");  

															if(!$dados_conta_receber){

																$cabecalho = '	
																<div class="panel-heading">
																	<div class= "row">
																		<h3 class="panel-title text-left col-md-6" style="margin-top: 2px;">
																			<input aceita-clona="sim" name="selecionar_suporte[]" type="checkbox" value="'.$dado_consulta['id_faturamento'].'" style="position: relative; vertical-align: middle;">
																			&nbsp
																			'.$contrato.'
																		</h3>
																	<div class="panel-title text-right col-md-6">';
																	$botoes = '';
																if($dados_verificacao[0]['tipo_cobranca'] == 'x_cliente_base'){
																	// $botoes = '<a href="/api/iframe?token=<?php echo $request->token ?>&view=faturamento-ajustar-call-suporte&alterar='.$dado_consulta['id_faturamento'].'&cancelados='.$cancelados.'&ordenacao='.$ordenacao.'" title="Quantidade de Clientes"><span class="btn btn-sm btn-primary"><i class="fas fa-edit"></i> Qtd. Clientes</span></a>&nbsp;&nbsp;<a style="color:#00008B;" id="modal_id" href="" data-toggle="modal" data-target="#modal" data-id="'.$dado_consulta['id_contrato_plano_pessoa'].'|'.$contrato.'" title="Ajustar"><span class="btn btn-sm btn-info"><i class="fa fa-pencil"></i> Ajustar</span></a>&nbsp;&nbsp;';
																	$botoes = '<a href="/api/iframe?token='.$token.'&view=faturamento-ajustar-call-suporte&alterar='.$dado_consulta['id_faturamento'].'&cancelados='.$cancelados.'&ordenacao='.$ordenacao.'" title="Quantidade de Clientes"><span class="btn btn-sm btn-primary"><i class="fas fa-edit"></i> Qtd. Clientes</span></a>&nbsp;&nbsp;';
																}else{
																	// $botoes = '<a style="color:#00008B;" id="modal_id" href="" data-toggle="modal" data-target="#modal" data-id="'.$dado_consulta['id_contrato_plano_pessoa'].'|'.$contrato.'" title="Ajustar"><span class="btn btn-sm btn-info"><i class="fa fa-pencil"></i> Ajustar</span></a>&nbsp;&nbsp;';
																}



																$botoes = $botoes."<a style='color:#8B0000;' href=\"/api/ajax?class=Faturamento.php?cancelar=".$dado_consulta['id_contrato_plano_pessoa']."&cancelados=".$cancelados."&ordenacao=".$ordenacao."&servico=call_suporte&token=". $token ."\" title='Cancelar' onclick=\"if (!confirm('Cancelar a fatura da empresa ".addslashes($dado_consulta['nome']."".$nome_contrato).", referente a ".addslashes($dados_meses[$mes_atual]." de ".$ano_atual)."?')) { return false; } else { modalAguarde(); }\"><span class='btn btn-sm btn-danger'><i class= 'fa fa-window-close-o' ></i> Cancelar </span></a>&nbsp;&nbsp;";

															
																$cliente_id_cidade = $dado_consulta['id_cidade'];
																$cliente_logradouro = $dado_consulta['logradouro'];
																$cliente_numero = $dado_consulta['numero'];
																$cliente_bairro = $dado_consulta['bairro'];
																$cliente_cep = $dado_consulta['cep'];
																$cliente_razao_social = $dado_consulta['razao_social'];
																$cliente_cpf_cnpj = $dado_consulta['cpf_cnpj'];
																$tipo_pessoa = strtoupper(substr($dado_consulta['tipo'], 1));
																
																if(($cliente_razao_social && $cliente_cpf_cnpj && $cliente_id_cidade && $cliente_logradouro && $cliente_numero && $cliente_bairro) && ($tipo_pessoa == 'J' && valida_cnpj($cliente_cpf_cnpj) || $tipo_pessoa == 'F' && valida_cpf($cliente_cpf_cnpj)) && $cliente_cep){

																	$botoes = $botoes."<a style='color:#0B610B;' href=\"/api/ajax?class=ContaReceber.php?faturamento=".$dado_consulta['id_faturamento']."&cancelados=".$cancelados."&ordenacao=".$ordenacao."&token=". $token ."\" title='Gerar NFS-e' onclick=\"if (!confirm('Gerar NFS-e a partir da fatura da empresa ".addslashes($dado_consulta['nome']."".$nome_contrato).", referente a ".addslashes($dados_meses[$mes_atual]." de ".$ano_atual)."?')) { return false; } else { modalAguarde(); }\"><span class='btn btn-sm btn-warning'><i class= 'fas fa-arrow-circle-down'></i> Conta a Receber </span></a>";

																}else{

																	$alerta_nfs  = 'Para a emissão da NFS-e da pessoa '.addslashes($dado_consulta['nome']."".$nome_contrato).', faltam os seguintes dados:\n';
																	if(!$cliente_id_cidade || $cliente_id_cidade == '9999999'){
																		$alerta_nfs  = $alerta_nfs.' - Cidade\n';
																	}
																	if(!$cliente_logradouro){
																		$alerta_nfs  = $alerta_nfs.' - Logradouro\n';
																	}
																	if(!$cliente_numero){
																		$alerta_nfs  = $alerta_nfs.' - Número do Logradouro\n';
																	}
																	if(!$cliente_bairro){
																		$alerta_nfs  = $alerta_nfs.' - Bairro\n';
																	}
																	if(!$cliente_cep){
																		$alerta_nfs  = $alerta_nfs.' - CEP\n';
																	}
																	if(!$cliente_razao_social){
																		$alerta_nfs  = $alerta_nfs.' - Razão Social\n';
																	}
																	if(!$cliente_cpf_cnpj){
																		$alerta_nfs  = $alerta_nfs.' - CPF/CNPJ\n';
																	}

																	$botoes = $botoes."<a style='color:#0B610B;' href=\"/api/ajax?class=ContaReceber.php?faturamento=".$dado_consulta['id_faturamento']."&cancelados=".$cancelados."&ordenacao=".$ordenacao."&token=". $token ."\" title='Gerar NFS-e' onclick=\"alert('".substr($alerta_nfs, 0, -2)."'); return false;\"><span class='btn btn-sm btn-warning'><i class= 'fas fa-arrow-circle-down'></i> Conta a Receber </span></a>";
																	
																}

															}else{

																$cabecalho = '	<div class="panel-heading">
																			<div class= "row">
																				<h3 class="panel-title text-left col-md-6" style="margin-top: 2px;">
																					&nbsp
																					&nbsp
																					&nbsp
																					'.$contrato.'
																				</h3>
																			<div class="panel-title text-right col-md-6">';
																
																// $botoes = "<span style='color:#000000;' title='Conta a receber já gerada'><span class='btn btn-sm btn-default' style='pointer-events: none;' disabled><i class='fa fa-pencil'></i> Ajustar</span></a>&nbsp;&nbsp;";
																$botoes = '';
																$botoes = $botoes."<span style='color:#000000;' title='Conta a receber já gerada'><span class='btn btn-sm btn-default' style='pointer-events: none;' disabled><i class= 'fa fa-window-close-o' ></i> Cancelar </span></a>&nbsp;&nbsp;";

																$botoes = $botoes."<span style='color:#000000;' data-toggle='tooltip' title='Data e hora da emissão: ".converteDataHora($dados_conta_receber[0]['data_cadastro'])."'><span class='btn btn-sm btn-default' style='pointer-events: none;' disabled><i class= 'fas fa-arrow-circle-down'></i> Conta a Receber </span></span>";
															}
																
																	
													}else{
														$panel = '<div class="panel panel-danger" style="margin:10px;">';
														
														$cabecalho = '	<div class="panel-heading">
																			<div class= "row">
																				<h3 class="panel-title text-left col-md-6" style="margin-top: 2px;">
																					&nbsp
																					&nbsp
																					&nbsp
																					'.$contrato.'
																				</h3>
																			<div class="panel-title text-right col-md-6">';
																				

														$botoes = "<a style='color:#088A08;' href=\"/api/ajax?class=Faturamento.php?reativar=".$dado_consulta['id_contrato_plano_pessoa']."&cancelados=".$cancelados."&ordenacao=".$ordenacao."&servico=call_suporte&token=". $token ."\" title='Reativar' onclick=\"if (!confirm('Reativar a fatura da empresa ".addslashes($dado_consulta['nome']."".$nome_contrato).", referente a ".addslashes($dados_meses[$mes_atual]." de ".$ano_atual)."?')) { return false; } else { modalAguarde(); }\"><span class='btn btn-sm btn-success'><i class= 'fa fa-check-square-o' ></i> Reativar </span></a>&nbsp;&nbsp";
													}
													

														echo $panel;
														echo $cabecalho;        
														echo $botoes;   
														
													

																echo '
																	</div>
																</div>
															</div>';

															if($dados_verificacao[0]['tipo_cobranca'] == 'x_cliente_base'){
																echo '
																<div class="panel-body" id="panel_body_'.$dado_consulta['id_contrato_plano_pessoa'].'">
																	<div class="row">
																		<div class="col-md-3"><strong>Plano: </strong>'.$dado_consulta['nome_plano'].'</div>
																		<div class="col-md-3"><strong>Tipo de Cobrança: </strong>'.$tipo_cobranca.'</div>';

																		if($dados_verificacao[0]['tipo_cobranca'] == 'x_cliente_base'){
																			echo '
																			<div class="col-md-3"><strong>Quantidade de Clientes: </strong>'.$dado_consulta['qtd_clientes'].'</div>
																			<div class="col-md-3"><strong>Quantidade Contratada (Clientes): </strong>'.$dado_consulta['quantidade_clientes_teto'].'</div>';
																		}

																	echo '
																	</div>';
																		
																	echo '
																	<div class="row">
																		<div class="col-md-3"><strong>Valor do Contrato: </strong>R$ <span id = "valor_contrato_'.$dado_consulta['id_contrato_plano_pessoa'].'">'.converteMoeda($dados_verificacao[0]['valor_total_contrato'],'moeda').'</span></div>
																		<div class="col-md-3"><strong>Valor Excedente do Contrato (Clientes): </strong>R$ <span id = "valor_excedente_'.$dado_consulta['id_contrato_plano_pessoa'].'">'.converteMoeda($dados_verificacao[0]['valor_excedente_contrato'],'moeda').'</span></div>
																	</div>
									
																	<div class="row">

																		<div class="col-md-3"><strong>Quantidade Contratada (Atendimentos): </strong>'.$dados_verificacao[0]['qtd_contratada'].'</div>
																		<div class="col-md-3"><strong>Quantidade Efetuada (Atendimentos): </strong>'.$dados_verificacao[0]['qtd_efetuada'].'</div>
																		<div class="col-md-3"><strong>Quantidade de Excedente (Clientes): </strong>'.$dados_verificacao[0]['qtd_excedente'].'</div>
																	</div>';
										
																	
									
															}else{
																echo '
																<div class="panel-body" id="panel_body_'.$dado_consulta['id_contrato_plano_pessoa'].'">
																	<div class="row">
																		<div class="col-md-3"><strong>Plano: </strong>'.$dado_consulta['nome_plano'].'</div>
																		<div class="col-md-3"><strong>Tipo de Cobrança: </strong>'.$tipo_cobranca.'</div>
																		<div class="col-md-3"><strong>Remove Duplicados: </strong>'.$remove_duplicados.'</div>';
									
																		if($dados_verificacao[0]['remove_duplicados_contrato'] == '1'){
																			echo '<div class="col-md-3"><strong>Quantidade de Duplicados: </strong>'.$dados_verificacao[0]['qtd_duplicados'].'</div>';
																		}
									
																		echo '
																		<div class="col-md-3"><strong>Valor Inicial do Contrato: </strong>R$ <span id = "valor_inicial_'.$dado_consulta['id_contrato_plano_pessoa'].'">'.converteMoeda($dados_verificacao[0]['valor_inicial_contrato'],'moeda').'</span></div>
																		<div class="col-md-3"><strong>Valor do Contrato: </strong>R$ <span id = "valor_contrato_'.$dado_consulta['id_contrato_plano_pessoa'].'">'.converteMoeda($dados_verificacao[0]['valor_total_contrato'],'moeda').'</span></div>
																		<div class="col-md-3"><strong>Valor Unitário do Contrato (Via Telefone): </strong>R$ <span id = "valor_unitario_'.$dado_consulta['id_contrato_plano_pessoa'].'">'.converteMoeda($dados_verificacao[0]['valor_unitario_contrato'],'moeda').'</span></div>
																		<div class="col-md-3"><strong>Valor Excedente do Contrato (Via Telefone): </strong>R$ <span id = "valor_excedente_'.$dado_consulta['id_contrato_plano_pessoa'].'">'.converteMoeda($dados_verificacao[0]['valor_excedente_contrato'],'moeda').'</span></div>
																		<div class="col-md-3"><strong>Quantidade Contratada (Via Telefone): </strong>'.$dados_verificacao[0]['qtd_contratada'].'</div>
									
																	</div>
									
																	<div class="row">
																		<div class="col-md-3"><strong>Quantidade Efetuada (Via Telefone e Monitoramentos): </strong>'.$dados_verificacao[0]['qtd_efetuada'].' <i class="fa fa-question-circle" data-toggle="tooltip" title="'.($dados_verificacao[0]['qtd_efetuada'] - $dados_verificacao[0]['qtd_monitoramento']).' via telefone e '.$dados_verificacao[0]['qtd_monitoramento'].' monitoramentos!" style="color: #337ab7;"></i></div>
																		<div class="col-md-3"><strong>Quantidade de Desafogo (Via Telefone e Monitoramentos): </strong>'.$dados_verificacao[0]['qtd_desafogo'].'</div>
																		<div class="col-md-3"><strong>Quantidade de Excedente (Via Telefone e Monitoramentos): </strong>'.$dados_verificacao[0]['qtd_excedente'].'</div>
																	</div>';
									
																	if($dados_verificacao[0]['valor_diferente_texto'] == 1){
																		echo '
																		<div class="row">
																			<div class="col-md-3"><strong>Valor Unitário do Contrato (Via Texto): </strong>R$ '.converteMoeda($dado_consulta['valor_unitario_texto_contrato'],'moeda').'</span></div>
																			<div class="col-md-3"><strong>Valor Excedente do Contrato (Via Texto): </strong>R$ '.converteMoeda($dado_consulta['valor_excedente_texto_contrato'],'moeda').'</span></div>
																			<div class="col-md-3"><strong>Quantidade Contratada (Via Texto): </strong>'.$dado_consulta['qtd_contratada_texto'].'</div>
																			<div class="col-md-3"><strong>Quantidade Efetuada (Via Texto): </strong>'.$dado_consulta['qtd_efetuada_texto'].'</div>
																		</div>
									
																		<div class="row">
																			<div class="col-md-3"><strong>Quantidade de Desafogo (Via Texto): </strong>'.$dado_consulta['qtd_desafogo_texto'].'</div>
																			<div class="col-md-3"><strong>Quantidade de Excedente (Via Texto): </strong>'.$dado_consulta['qtd_excedente_texto'].'</div>
																		</div>';
																	}
									
																
									
															}
															


																echo '
																<div class="row">
																	<div class="col-md-3"><strong>Total de Acréscimos: </strong>R$ <span id = "acrescimo_'.$dado_consulta['id_contrato_plano_pessoa'].'">'.converteMoeda($dados_verificacao[0]['acrescimo'],'moeda').'</span></div>
																	<div class="col-md-3"><strong>Total de Descontos: </strong>R$ <span id = "desconto_'.$dado_consulta['id_contrato_plano_pessoa'].'">'.converteMoeda($dados_verificacao[0]['desconto'], 'moeda').'</span></div>
																	<div class="col-md-3"><strong>Valor da Cobrança: </strong>R$ <span id = "cobranca_'.$dado_consulta['id_contrato_plano_pessoa'].'">'.converteMoeda($dados_verificacao[0]['valor_cobranca'], 'moeda').'</span></div>
																	<div class="col-md-3"><strong>Dia de Pagamento: </strong>'.$dado_consulta['dia_pagamento'].'</div>
																</div>';
																
																if($dados_verificacao[0]['acrescimo'] != '0.00' || $dados_verificacao[0]['desconto'] != '0.00'){

																	$dados_ajuste = DBRead('', 'tb_faturamento_ajuste',"WHERE id_faturamento = '".$dados_verificacao[0]['id_faturamento']."' ");
																	if($dados_ajuste){
																		foreach($dados_ajuste as $conteudo_ajuste){
																			$id_ajuste = $conteudo_ajuste['id_faturamento_ajuste'];

																			echo '<hr id="hr_id_ajuste_'.$id_ajuste.'">';
																			echo "<strong>Ajustes: </strong>";
																			
																			if($conteudo_ajuste['tipo'] == 'acrescimo'){
																				$tipo_de_ajuste = "Acréscimo";
																			}else{
																				$tipo_de_ajuste = "Desconto";
																			}
																			

																			$dados_usuario = DBRead('', 'tb_usuario a',"INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE id_usuario = '".$conteudo_ajuste['id_usuario']."'", "b.nome");

																			echo '
																			<div class="row" id="row_id_ajuste_'.$id_ajuste.'">
																				<div class="col-md-3">'.$tipo_de_ajuste.': R$ '.converteMoeda($conteudo_ajuste['valor'],'moeda').'</div>
																				<div class="col-md-3">Referente a: '.nl2br($conteudo_ajuste['descricao']).'</div>
																				<div class="col-md-3">Data e Hora: '.converteDataHora($conteudo_ajuste['data']).'</div>
																				<div class="col-md-2">Usuário: '.$dados_usuario[0]['nome'].'</div>
																				<div class="col-md-1">';
																				if(!$dados_conta_receber){
																					// echo '<a id="excluir_'.$id_ajuste.'" title="Excluir Ajuste" onclick=\'if (!confirm("Excluir '.addslashes($tipo_de_ajuste).' de R$ '.converteMoeda($conteudo_ajuste['valor'],'moeda').' ?")) {  return false; } else { exclui_ajuste('.$id_ajuste.', '.$dado_consulta['id_contrato_plano_pessoa'].', "'.$conteudo_ajuste['tipo'].'", "'.converteMoeda($conteudo_ajuste['valor'],'moeda').'"); }\'><i class="fa fa-trash" style="color:#b92c28; cursor: pointer;"></i></a>';
																				}
										
																				echo '
																				</div>
																			</div>';

																		}	
																	}
																} 

																$dados_antecipacao = DBRead('', 'tb_faturamento_antecipacao',"WHERE id_faturamento = '".$dado_consulta['id_faturamento']."' LIMIT 1");
																if($dados_antecipacao){
																	$id_ajuste = $conteudo_ajuste['id_faturamento_antecipacao'];

																	echo '<hr>';	
																	echo "<strong>Antecipação: </strong>";

																	$data_referencia_antecipacao = new DateTime($dados_antecipacao[0]['data_referencia']);
																	$mes_referencia_antecipacao = $data_referencia_antecipacao->format('m');
																	$ano_referencia_antecipacao = $data_referencia_antecipacao->format('Y');

																	echo '
																	<div class="row" id="row_id_ajuste_'.$id_ajuste.'">
																		<div class="col-md-3">Referente a: '.$dados_meses[$mes_referencia_antecipacao].' de '.$ano_referencia_antecipacao.'</div>
																		<div class="col-md-3">Quantidade de Dias: '.$dados_antecipacao[0]['qtd_dias'].'</div>
																		<div class="col-md-3">Valor: R$ '.converteMoeda($dados_antecipacao[0]['valor'],'moeda').'</div>
																		<div class="col-md-2"></div>
																		<div class="col-md-1">';
																		if(!$dados_conta_receber){
																			// echo '<a id="excluir_'.$id_ajuste.'" title="Excluir Ajuste" onclick=\'if (!confirm("Excluir '.addslashes($tipo_de_ajuste).' de R$ '.converteMoeda($conteudo_ajuste['valor'],'moeda').' ?")) {  return false; } else { exclui_ajuste('.$id_ajuste.', '.$dado_consulta['id_contrato_plano_pessoa'].', "'.$conteudo_ajuste['tipo'].'", "'.converteMoeda($conteudo_ajuste['valor'],'moeda').'"); }\'><i class="fa fa-trash" style="color:#b92c28; cursor: pointer;"></i></a>';
																		}
								
																		echo '
																		</div>
																	</div>';
																}

																$dados_proporcional_cancelado = DBRead('', 'tb_faturamento_proporcional',"WHERE id_faturamento = '".$dado_consulta['id_faturamento']."' AND tipo = 1 LIMIT 1");
																if($dados_proporcional_cancelado){

																	echo '<hr>';	
																	echo "<strong>Proporcional ao Cancelamento: </strong>";

																	echo '
																	<div class="row" id="row_id_ajuste_'.$id_ajuste.'">
																		<div class="col-md-12">Quantidade de Dias: '.$dados_proporcional_cancelado[0]['qtd_dias'].'</div>
																	</div>';
																}

																$dados_proporcional_ativo = DBRead('', 'tb_faturamento_proporcional',"WHERE id_faturamento = '".$dado_consulta['id_faturamento']."' AND tipo = 2 LIMIT 1");
																if($dados_proporcional_ativo){

																	echo '<hr>';	
																	echo "<strong>Proporcional a Ativação: </strong>";

																	echo '
																	<div class="row" id="row_id_ajuste_'.$id_ajuste.'">
																		<div class="col-md-12">Quantidade de Dias: '.$dados_proporcional_ativo[0]['qtd_dias'].'</div>
																	</div>';

																}



													echo '</div>
														</div>';				
												}
											}
										echo '
										</div>
									</div>
								</div>
								<div class="panel-footer" style="background-color: white !important; border-top: 1px solid #c1c1c1;">
									<div class="row">
										<div class="col-md-12" style="text-align: center">

											<input type="hidden" id="operacao_conta_receber" name="abc" value="1"/>
											<input type="hidden" id="ordenacao" name="ordenacao" value="'.$ordenacao.'"/>
											<input type="hidden" id="cancelados" name="cancelados" value="'.$cancelados.'"/>

											<button class="btn btn-sm btn-primary" id="gerar_contas_receber_todos" type="button" data-toggle="modal" data-target="#modal_call_ativo_check" disabled><i class="fas fa-arrow-circle-down"></i> Gerar Contas a Receber</button>
																						
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>';
			echo '</form>';

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
		
	echo '<br><br>';

 	//MODAL
	echo '<div class="modal fade" id="modal" role="dialog">
	    	<div class="modal-dialog">
			    <div class="modal-content">
			        <div class="modal-header">
                		<h3 class="panel-title text-left pull-left" style="margin-top: 2px;" id="titulo"></h3>
			        </div>
			        <div class="modal-body">';
						
						echo '<input type="hidden" name="id_ajuste" id="id_ajuste">';
				        echo '	                		
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<label>*Tipo:</label>
                                    <select class="form-control input-sm" id="tipo_ajuste" name="tipo_ajuste">
										<option value="">Selecione um tipo de ajuste...</option>
										<option value="acrescimo">Acréscimo</option>
										<option value="desconto">Desconto</option>
                                    </select>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<div class="form-group" >
							        <label>*Valor (R$):</label>
                        			<input name="valor_ajuste" id="valor_ajuste" type="text" class="form-control input-sm money" autocomplete="off">
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<div class="form-group" >
							        <label>*Referente a:</label>
                        			<textarea name="descricao" id="descricao" type="text" class="form-control input-sm" rows="5"></textarea>
								</div>
							</div>
						</div>
								
					</div>
                    <div class="modal-footer">
                        <div class="row">
                            <div id="panel_buttons" class="col-md-12" style="text-align: center">
                                <span class="btn btn-primary" name="ajustar" id="ajustar" value="2" type="submit"><i class="fa fa-check"></i> Realizar Ajuste</span>';
                            echo '
                            </div>
                        </div>
                    </div>
	            	';
	     	   echo '
	        	</div>
	      	</div>
	  	</div>';
	
	  	?>

	<script>
		
		$(document).ready(function() {
			ancora = $("#ancora").val();
			if(ancora && ancora != ''){
	        	window.location.href = '#'+ancora;
			}
		  	$('[data-toggle="tooltip"]').tooltip();   
	    });

		function exclui_ajuste(id_ajuste, id_contrato_plano_pessoa, tipo_ajuste, valor_ajuste){
			$('#row_id_ajuste_'+id_ajuste).remove();
			$('#hr_id_ajuste_'+id_ajuste).remove();
			
			var valor_ajuste = parseFloat(moedaFloat(valor_ajuste));
	        var cobranca = parseFloat(moedaFloat($("#cobranca_"+id_contrato_plano_pessoa).text()));
	        var acrescimo = parseFloat(moedaFloat($("#acrescimo_"+id_contrato_plano_pessoa).text()));
	        var desconto = parseFloat(moedaFloat($("#desconto_"+id_contrato_plano_pessoa).text()));
	        
			if(tipo_ajuste == 'acrescimo'){
	       	    cobranca -= valor_ajuste;
	       	 	$("#acrescimo_"+id_contrato_plano_pessoa).text(floatMoeda((acrescimo-valor_ajuste).toFixed(2)));
	        }else{
	            cobranca += valor_ajuste;
	       	 	$("#desconto_"+id_contrato_plano_pessoa).text(floatMoeda((desconto-valor_ajuste).toFixed(2)));
	        }
			
			$("#cobranca_"+id_contrato_plano_pessoa).text(floatMoeda(cobranca.toFixed(2)));
			
			$.ajax({
		        url: "/api/ajax?class=Faturamento.php",
		        dataType: "json",
		        method: 'POST',
		        data: {
		            acao: 'excluir_ajuste',
		            parametros : {
		               'tipo_ajuste': tipo_ajuste, 
		               'valor_ajuste': valor_ajuste,
		               'id_ajuste': id_ajuste
		            },
					token: '<?= $token ?>'
		        },
	        });    
		}

		$(document).on('click', '#checkTodosSuporte', function () {
			if ($(this).is(':checked')){
				$("#gerar_contas_receber_email_todos").attr("disabled", false);
				$("#gerar_contas_receber_boleto_todos").attr("disabled", false);
				$("#gerar_contas_receber_todos").attr("disabled", false);
			
				$('[name ="selecionar_suporte[]"]').prop("checked", true);
				$('#texto_check').text("Desmarcar Todos");
			}else{
				$("#gerar_contas_receber_email_todos").attr("disabled", true);
				$("#gerar_contas_receber_boleto_todos").attr("disabled", true);
				$("#gerar_contas_receber_todos").attr("disabled", true);

				$('[name ="selecionar_suporte[]"]').prop("checked", false);
				$('#texto_check').text("Marcar Todos");
			}
		});

		$(document).on('click', '#gerar_contas_receber_todos', function(){
			if (!confirm('Atenção! Certifique-se que os valores dos contratos de Call Center - Suporte já foram ajustados este mês!')){
    		return false; 
    		}			
			$('#operacao_conta_receber').attr('name', 'conta_receber_check_suporte');
    		$('#controle_conta_receber').submit();
		});
  
		$(document).on('click', '[name ="selecionar_suporte[]"]', function(){
			var libera = 0;
			var nao_libera = 0;
			$("[name ='selecionar_suporte[]']").each(function(){
				if ($(this).is(':checked')){
					libera ++;
				}else{
					nao_libera ++;
				}
			});
			if(libera != 0){
				$("#gerar_contas_receber_email_todos").attr("disabled", false);
				$("#gerar_contas_receber_boleto_todos").attr("disabled", false);
				$("#gerar_contas_receber_todos").attr("disabled", false);

				if(nao_libera == 0){
					$("#checkTodosSuporte").prop("checked", true);
					$('#texto_check').text("Desmarcar Todos");
				}else{
					$("#checkTodosSuporte").prop("checked", false);
					$('#texto_check').text("Marcar Todos");
				}
				
			}else{
				$("#gerar_contas_receber_email_todos").attr("disabled", true);
				$("#gerar_contas_receber_boleto_todos").attr("disabled", true);
				$("#gerar_contas_receber_todos").attr("disabled", true);

				$("#checkTodosSuporte").prop("checked", false);
				$('#texto_check').text("Marcar Todos");
			}
		});

	</script>

	</script>

	<?php
}

function faturamento_gestao_redes($cancelados, $ordenacao, $token){

	$id_usuario = $_SESSION['id_usuario'];
	
	if($cancelados == 1){
		$filtro_cancelado = '';
		$filtro_cancelado_dados = "";
	}else{
		$filtro_cancelado = 'AND a.status = 1';
		$filtro_cancelado_dados = "AND b.status = 1";
    }
    
    if($ordenacao == 'cliente'){
        $filtro_ordenacao = ' ORDER BY d.nome ASC';
    }else{
        $filtro_ordenacao = ' ORDER BY c.dia_pagamento ASC';
    }

	$data_inicial = new DateTime(getDataHora('data'));
	$data_inicial->modify('first day of last month');
	$data_final = new DateTime(getDataHora('data'));
	$data_final->modify('last day of last month');
	
	$mes_atual = $data_inicial->format('m');
	$ano_atual = $data_inicial->format('Y');

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

	$data_hoje = getDataHora();
	$data_hoje = converteDataHora($data_hoje);	

	$dados_consulta = DBRead('','tb_faturamento_contrato a',"INNER JOIN tb_faturamento b ON a.id_faturamento = b.id_faturamento INNER JOIN tb_contrato_plano_pessoa c ON a.id_contrato_plano_pessoa = c.id_contrato_plano_pessoa INNER JOIN tb_pessoa d ON c.id_pessoa = d.id_pessoa INNER JOIN tb_plano e ON c.id_plano = e.id_plano WHERE e.cod_servico = 'gestao_redes' AND b.data_referencia = '".$data_inicial->format('Y-m-d')."' AND a.contrato_pai = '1' $filtro_cancelado_dados ORDER BY b.id_faturamento ", "a.*, b.*, c.*, d.*, c.status AS status_contrato, e.nome AS nome_plano, b.status AS status_faturamento, b.valor_total AS valor_total_faturamento");  
	//$dados_consulta = DBRead('','tb_faturamento_contrato a',"INNER JOIN tb_faturamento b ON a.id_faturamento = b.id_faturamento INNER JOIN tb_contrato_plano_pessoa c ON a.id_contrato_plano_pessoa = c.id_contrato_plano_pessoa INNER JOIN tb_pessoa d ON c.id_pessoa = d.id_pessoa INNER JOIN tb_plano e ON c.id_plano = e.id_plano WHERE e.cod_servico = 'gestao_redes' AND b.data_referencia = '".$data_inicial->format('Y-m-d')."' AND a.contrato_pai = '1' $filtro_ordenacao", "a.*, b.*, c.*, d.*, c.status AS status_contrato, e.nome AS nome_plano, b.status AS status_faturamento");  

	echo "<div class=\"col-md-12\" style=\"padding: 0\">";
	echo "<legend style=\"text-align:center;\"><strong>Faturamento de Gestão de Redes: Referente a ".$dados_meses[$mes_atual]." de ".$ano_atual."</strong></legend>";

	if($dados_consulta){
		foreach($dados_consulta as $dado_consulta){
			echo '<div id="'.$dado_consulta['id_contrato_plano_pessoa'].'"></div>';
			$exibir = 0;

			if($dado_consulta['status_contrato'] == '1' || $dado_consulta['data_status'] >= $data_inicial->format('Y-m-d')){
				$verificacao_status_faturamento = DBRead('', 'tb_faturamento a', "INNER JOIN tb_faturamento_contrato b ON a.id_faturamento = b.id_faturamento WHERE a.data_referencia = '".$data_inicial->format('Y-m-d')."' AND b.id_contrato_plano_pessoa = '".$dado_consulta['id_contrato_plano_pessoa']."' ".$filtro_cancelado." ");
				
				if($verificacao_status_faturamento){
					$exibir = 1;
				}
			}

			if($exibir != 0 && (!$dado_consulta['contrato_pai'] || $dado_consulta['contrato_pai'] == '0')){

				//NOME DO CONTRATO
				if($dado_consulta['nome_contrato']){
	                $nome_contrato = " (".$dado_consulta['nome_contrato'].") ";
	            }else{
	                $nome_contrato = '';
	            }
	            $contrato = "<strong>".$dado_consulta['nome']."</strong> ".$nome_contrato;
					
				
				if($dado_consulta['tipo_cobranca'] == 'mensal_desafogo'){
					$tipo_cobranca = "Mensal com Desafogo";
				}else if($dado_consulta['tipo_cobranca'] == 'cliente_base'){
					$tipo_cobranca = "Clientes na Base";
				}else if($dado_consulta['tipo_cobranca'] == 'cliente_ativo'){
					$tipo_cobranca = "Clientes Ativos";
				}else if($dado_consulta['tipo_cobranca'] == 'x_cliente_base'){
					$tipo_cobranca = "Até X Clientes na Base";
				}else if($dado_consulta['tipo_cobranca'] == 'prepago'){
					$tipo_cobranca = "Pré-pago";
				}else{
					$tipo_cobranca = ucfirst($dado_consulta['tipo_cobranca']);
				}

		        if($dado_consulta['status_faturamento'] != 0){
		        	$panel = '<div class="panel panel-default">';

		        	$cabecalho = '	<div class="panel-heading clearfix">
		        						<div class= "row">
							                <h3 class="panel-title text-left col-md-6" style="margin-top: 2px;">'.$contrato.'</h3>
										<div class="panel-title text-right col-md-6">';
										
										// <h3 class="panel-title text-left col-md-6" style="margin-top: 2px;">'.$contrato.' - '.$dado_consulta['id_faturamento'].'</h3>

		        	
						$dados_conta_receber = DBRead('','tb_conta_receber',"WHERE id_faturamento = '".$dado_consulta['id_faturamento']."' AND (situacao = 'aberta' OR situacao = 'quitada') ");  

		        		if(!$dados_conta_receber){
							if($tipo_cobranca == 'Horas'){

								$botoes = '<a id="modal_id" href="" data-toggle="modal" data-target="#modal" data-id="'.$dado_consulta['id_contrato_plano_pessoa'].'|'.$contrato.'" title="Ajustar"><button class="btn btn-sm btn-info"><i class="fa fa-pencil"></i> Ajustar</button></a>&nbsp;&nbsp;';

								$botoes = $botoes."<a style='color:#8B0000;' href=\"/api/ajax?class=Faturamento.php?cancelar=".$dado_consulta['id_contrato_plano_pessoa']."&cancelados=".$cancelados."&ordenacao=".$ordenacao."&servico=gestao_redes&token=". $token ."\" title='Cancelar' onclick=\"if (!confirm('Cancelar a fatura da empresa ".addslashes($dado_consulta['nome']."".$nome_contrato).", referente a ".addslashes($dados_meses[$mes_atual]." de ".$ano_atual)."?')) { return false; } else { modalAguarde(); }\"><button class='btn btn-sm btn-danger'><i class= 'fa fa-window-close-o' ></i> Cancelar </button></a>&nbsp;&nbsp;";
								
							}else{
								$botoes = '<a href="/api/iframe?token='.$token.'&view=faturamento-ajustar-redes&alterar='.$dado_consulta['id_faturamento'].'&cancelados='.$cancelados.'&ordenacao='.$ordenacao.'" title="Quantidade de Clientes"><button class="btn btn-sm btn-primary"><i class="fas fa-edit"></i> Qtd. Clientes</button></a>&nbsp;&nbsp;';

								
								$botoes = $botoes.'<a id="modal_id" href="" data-toggle="modal" data-target="#modal" data-id="'.$dado_consulta['id_contrato_plano_pessoa'].'|'.$contrato.'" title="Ajustar"><button class="btn btn-sm btn-info"><i class="fa fa-pencil"></i> Ajustar</button></a>&nbsp;&nbsp;';

								$botoes = $botoes."<a style='color:#8B0000;' href=\"/api/ajax?class=Faturamento.php?cancelar=".$dado_consulta['id_contrato_plano_pessoa']."&cancelados=".$cancelados."&ordenacao=".$ordenacao."&servico=gestao_redes&token=". $token ."\" title='Cancelar' onclick=\"if (!confirm('Cancelar a fatura da empresa ".addslashes($dado_consulta['nome']."".$nome_contrato).", referente a ".addslashes($dados_meses[$mes_atual]." de ".$ano_atual)."?')) { return false; } else { modalAguarde(); }\"><button class='btn btn-sm btn-danger'><i class= 'fa fa-window-close-o' ></i> Cancelar </button></a>&nbsp;&nbsp;";
							}
							

						
		        			$cliente_id_cidade = $dado_consulta['id_cidade'];
					        $cliente_logradouro = $dado_consulta['logradouro'];
					        $cliente_numero = $dado_consulta['numero'];
					        $cliente_bairro = $dado_consulta['bairro'];
					        $cliente_cep = $dado_consulta['cep'];
					        $cliente_razao_social = $dado_consulta['razao_social'];
					        $cliente_cpf_cnpj = $dado_consulta['cpf_cnpj'];
							$tipo_pessoa = strtoupper(substr($dado_consulta['tipo'], 1));
					    	
					    	if(($cliente_razao_social && $cliente_cpf_cnpj && $cliente_id_cidade && $cliente_logradouro && $cliente_numero && $cliente_bairro) && ($tipo_pessoa == 'J' && valida_cnpj($cliente_cpf_cnpj) || $tipo_pessoa == 'F' && valida_cpf($cliente_cpf_cnpj)) && $cliente_cep){		    		

					    		$botoes = $botoes."<a style='color:#0B610B;' href=\"/api/ajax?class=ContaReceber.php?faturamento_gestao_redes=".$dado_consulta['id_faturamento']."&cancelados=".$cancelados."&ordenacao=".$ordenacao."&token=". $token ."\" title='Gerar NFS-e' onclick=\"if (!confirm('Gerar NFS-e a partir da fatura da empresa ".addslashes($dado_consulta['nome']."".$nome_contrato).", referente a ".addslashes($dados_meses[$mes_atual]." de ".$ano_atual)."?')) { return false; } else { modalAguarde(); }\"><button class='btn btn-sm btn-warning'><i class= 'fas fa-arrow-circle-down'></i> Conta a Receber </button></a>";

					    	}else{

					    		$alerta_nfs  = 'Para a emissão da NFS-e da pessoa '.addslashes($dado_consulta['nome']."".$nome_contrato).', faltam os seguintes dados:\n';
						        if(!$cliente_id_cidade || $cliente_id_cidade == '9999999'){
						            $alerta_nfs  = $alerta_nfs.' - Cidade\n';
						        }
						        if(!$cliente_logradouro){
						            $alerta_nfs  = $alerta_nfs.' - Logradouro\n';
						        }
						        if(!$cliente_numero){
						            $alerta_nfs  = $alerta_nfs.' - Número do Logradouro\n';
						        }
						        if(!$cliente_bairro){
						            $alerta_nfs  = $alerta_nfs.' - Bairro\n';
						        }
						        if(!$cliente_cep){
						            $alerta_nfs  = $alerta_nfs.' - CEP\n';
						        }
						        if(!$cliente_razao_social){
						            $alerta_nfs  = $alerta_nfs.' - Razão Social\n';
						        }
						        if(!$cliente_cpf_cnpj){
						            $alerta_nfs  = $alerta_nfs.' - CPF/CNPJ\n';
						        }

					    		$botoes = $botoes."<a style='color:#0B610B;' href='' title='Gerar Conta Receber' onclick=\"alert('".substr($alerta_nfs, 0, -2)."'); return false;\"><button class='btn btn-sm btn-warning'><i class= 'fas fa-arrow-circle-down'></i> Conta a Receber </button></a>";
					    	}

		        		}else{
		        			
		        			$botoes = "<span style='color:#000000;' title='Conta a receber já gerada'><button class='btn btn-sm btn-default' style='pointer-events: none;' disabled><i class='fa fa-pencil'></i> Ajustar</button></a>&nbsp;&nbsp;";

		        			$botoes = $botoes."<span style='color:#000000;' title='Conta a receber já gerada'><button class='btn btn-sm btn-default' style='pointer-events: none;' disabled><i class= 'fa fa-window-close-o' ></i> Cancelar </button></a>&nbsp;&nbsp;";

		        			$botoes = $botoes."<span style='color:#000000;' data-toggle='tooltip' title='Data e hora da emissão: ".converteDataHora($dados_conta_receber[0]['data_cadastro'])."'><button class='btn btn-sm btn-default' style='pointer-events: none;' disabled><i class= 'fas fa-arrow-circle-down'></i> Conta a Receber </button></span>";
		        		}
		        			  
				}else{
					$panel = '<div class="panel panel-danger">';
					
					$cabecalho = '	<div class="panel-heading clearfix">
										<div class= "row">
						                    <h3 class="panel-title text-left col-md-4" style="margin-top: 2px;">'.$contrato.'</h3>
						                    	<h3 class="panel-title text-center col-md-4" style="margin-top: 2px;"><strong>Cancelado</strong></h3>
					                    	<div class="panel-title text-right col-md-4">';

					$botoes = "<a style='color:#088A08;' href=\"/api/ajax?class=Faturamento.php?reativar=".$dado_consulta['id_contrato_plano_pessoa']."&cancelados=".$cancelados."&ordenacao=".$ordenacao."&servico=gestao_redes&token=". $token ."\" title='Reativar' onclick=\"if (!confirm('Reativar a fatura da empresa ".addslashes($dado_consulta['nome']."".$nome_contrato).", referente a ".addslashes($dados_meses[$mes_atual]." de ".$ano_atual)."?')) { return false; } else { modalAguarde(); }\"><button class='btn btn-sm btn-success'><i class= 'fa fa-check-square-o' ></i> Reativar </button></a>&nbsp;&nbsp";
				}

				echo $panel;
				echo $cabecalho;        
		        echo $botoes;            	

						echo '
    						</div>
    					</div>
	                </div>

				  	<div class="panel-body" id="panel_body_'.$dado_consulta['id_contrato_plano_pessoa'].'">
				    	<div class="row">
							<div class="col-md-3"><strong>Plano: </strong>'.$dado_consulta['nome_plano'].'</div>
							<div class="col-md-3"><strong>Tipo de Cobrança: </strong>'.$tipo_cobranca.'</div>
								
							<div class="col-md-3"><strong>Dia de Pagamento: </strong>'.$dado_consulta['dia_pagamento'].'</div>
							<div class="col-md-3"><strong>Valor do Contrato: </strong>R$ '.converteMoeda($dado_consulta['valor_total_contrato'], 'moeda').'</div>

                        </div>

						<div class="row">';
							if($tipo_cobranca == 'Até X Clientes na Base'){
								echo '<div class="col-md-3"><strong>Qtd. Contratada (teto): </strong>'.$dado_consulta['qtd_contratada'].'</div>';
							}else{
								echo '<div class="col-md-3"><strong>Qtd. Contratada: </strong>'.$dado_consulta['qtd_contratada'].'</div>';
							}

							if($dado_consulta['tipo_plantao'] == '0'){
								$tipo_plantao = "N/D";
							}else{
								if($dado_consulta['tipo_plantao'] == '1'){
									$tipo_plantao = "30 em 30";
								}else if($dado_consulta['tipo_plantao'] == '2'){
									$tipo_plantao = "60 em 60";
								}else if($dado_consulta['tipo_plantao'] == '3'){
									$tipo_plantao = "60 em 60 proporcional";
								}else{
									$tipo_plantao = "Isento";
								}
							}

							echo'
							<div class="col-md-3"><strong>Qtd. Efetuada: </strong>'.$dado_consulta['qtd_efetuada'].'</div>
							<div class="col-md-3"><strong>Qtd. Excedente: </strong>'.$dado_consulta['qtd_excedente'].'</div>
							<div class="col-md-3"><strong>Valor Excedente do Contrato: </strong>R$ '.converteMoeda($dado_consulta['valor_excedente_contrato'], 'moeda').'</div>
						</div>
						
						<div class="row">
							<div class="col-md-3"><strong>Qtd. Clientes: </strong>'.$dado_consulta['qtd_clientes'].'</div>
							<div class="col-md-3"><strong>Qtd. de Plantões: </strong>'.$dado_consulta['qtd_plantao'].'</div>
							<div class="col-md-3"><strong>Valor Unitário do Plantão: </strong>R$ '.converteMoeda($dado_consulta['valor_plantao_contrato'], 'moeda').'</div>
							<div class="col-md-3"><strong>Tipo de Plantão: </strong>'.($tipo_plantao).'</div>
						</div>

						<div class="row">
							<div class="col-md-3"><strong>Valor Total dos Plantões: </strong>R$ '.converteMoeda($dado_consulta['valor_plantao_total'], 'moeda').'</div>
							<div class="col-md-3"><strong>Total da Acréscimos: </strong>R$ <span id = "acrescimo'.$dado_consulta['id_contrato_plano_pessoa'].'">'.converteMoeda($dado_consulta['acrescimo'], 'moeda').'</span></div>
							<div class="col-md-3"><strong>Total de Descontos: </strong>R$ <span id = "desconto_'.$dado_consulta['id_contrato_plano_pessoa'].'">'.converteMoeda($dado_consulta['desconto'], 'moeda').'</span></div>
							<div class="col-md-3"><strong>Valor da Cobrança: </strong>R$ <span id = "cobranca_'.$dado_consulta['id_contrato_plano_pessoa'].'">'.converteMoeda($dado_consulta['valor_cobranca'],'moeda').'</span></div>				

							
							
						</div>';

						$dados_verificacao = DBRead('', 'tb_faturamento a',"INNER JOIN tb_faturamento_contrato b ON a.id_faturamento = b.id_faturamento WHERE b.id_contrato_plano_pessoa = '".$dado_consulta['id_contrato_plano_pessoa']."' AND a.data_referencia = '".$data_inicial->format('Y-m-d')."' ");

						
						if($dados_verificacao[0]['acrescimo'] != '0.00' || $dados_verificacao[0]['desconto'] != '0.00'){

							$dados_ajuste = DBRead('', 'tb_faturamento_ajuste',"WHERE id_faturamento = '".$dados_verificacao[0]['id_faturamento']."' ");
							if($dados_ajuste){
								foreach($dados_ajuste as $conteudo_ajuste){
									$id_ajuste = $conteudo_ajuste['id_faturamento_ajuste'];

									echo '<hr id="hr_id_ajuste_'.$id_ajuste.'">';

									if($conteudo_ajuste['tipo'] == 'acrescimo'){
										$tipo_de_ajuste = "Acréscimo";
									}else{
										$tipo_de_ajuste = "Desconto";
									}
									

									$dados_usuario = DBRead('', 'tb_usuario a',"INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE id_usuario = '".$conteudo_ajuste['id_usuario']."'", "b.nome");

									echo '
									<div class="row" id="row_id_ajuste_'.$id_ajuste.'">
										<div class="col-md-3">'.$tipo_de_ajuste.': R$ '.converteMoeda($conteudo_ajuste['valor'],'moeda').'</div>
										<div class="col-md-3">Referente a: '.nl2br($conteudo_ajuste['descricao']).'</div>
										<div class="col-md-3">Data e Hora: '.converteDataHora($conteudo_ajuste['data']).'</div>
										<div class="col-md-2">Usuário: '.$dados_usuario[0]['nome'].'</div>
										<div class="col-md-1">';
										if(!$dados_conta_receber){
											echo '<a id="excluir_'.$id_ajuste.'" title="Excluir Ajuste" onclick=\'if (!confirm("Excluir '.addslashes($tipo_de_ajuste).' de R$ '.converteMoeda($conteudo_ajuste['valor'],'moeda').' ?")) {  return false; } else { exclui_ajuste('.$id_ajuste.', '.$dado_consulta['id_contrato_plano_pessoa'].', "'.$conteudo_ajuste['tipo'].'", "'.converteMoeda($conteudo_ajuste['valor'],'moeda').'"); }\'><i class="fa fa-trash" style="color:#b92c28; cursor: pointer;"></i></a>';
										}else{
											echo '';
										}

										echo '
										</div>
									</div>';

								}	
							}
							
						}

			   echo '</div>
				  </div>';			

			}
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
		
	echo '<br><br>';

		//MODAL
		echo '<div class="modal fade" id="modal" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h3 class="panel-title text-left pull-left" style="margin-top: 2px;" id="titulo"></h3>
				</div>
				<div class="modal-body">';
					
					echo '<input type="hidden" name="id_ajuste" id="id_ajuste">';
					echo '	                		
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label>*Tipo:</label>
								<select class="form-control input-sm" id="tipo_ajuste" name="tipo_ajuste">
									<option value="acrescimo">Acréscimo</option>
									<option value="desconto">Desconto</option>
								</select>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<div class="form-group" >
								<label>*Valor (R$):</label>
								<input name="valor_ajuste" id="valor_ajuste" type="text" class="form-control input-sm money" autocomplete="off">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<div class="form-group" >
								<label>*Referente a:</label>
								<textarea name="descricao" id="descricao" type="text" class="form-control input-sm" rows="5"></textarea>
							</div>
						</div>
					</div>
							
				</div>
				<div class="modal-footer">
					<div class="row">
						<div id="panel_buttons" class="col-md-12" style="text-align: center">
							<button class="btn btn-primary" name="ajustar" id="ajustar" value="2" type="submit"><i class="fa fa-check"></i> Realizar Ajuste</button>';
						echo '
						</div>
					</div>
				</div>
				';
			echo '
			</div>
		  </div>
	  </div>';


	//MODAL
	// echo '
	// <div class="modal fade" id="modal_gestao_redes" role="dialog">
    // 	<div class="modal-dialog">
	// 	    <div class="modal-content">
	// 		    <form method="post" action="class/ContaReceber.php" id="inserir_conta_receber_redes" style="margin-bottom: 0;">

	// 		        <div class="modal-header">
	//             		<h3 class="panel-title text-left pull-left" style="margin-top: 2px;" id="titulo_redes"></h3>
	// 		        </div>
	// 		        <div class="modal-body">
	// 					<input type="hidden" name="id_ajuste_redes" id="id_ajuste_redes">
	// 					<input type="hidden" name="cancelados_redes" id="cancelados_redes" value="'.$cancelados.'">
	// 					<input type="hidden" name="ordenacao_redes" id="ordenacao_redes" value="'.$ordenacao.'">
	// 					<input type="hidden" name="nome_empresa_cliente" id="nome_empresa_cliente">
				       	
	// 					<div class="row">
	// 						<div class="col-md-12">
	// 							<div class="form-group" >
	// 						        <label>*Descrição da Nota:</label>
	//                     			<textarea name="descricao_redes" id="descricao_redes" type="text" class="form-control input-sm" rows="5"></textarea>
	// 							</div>
	// 						</div>
	// 					</div>
								
	// 				</div>
	//                 <div class="modal-footer">
	//                     <div class="row">
	//                         <div id="panel_buttons" class="col-md-12" style="text-align: center">
    //                             <input type="hidden" id="operacao" value="2" name="faturamento_gestao_redes"/>
	// 				          	<a href="#" id="salvar_redes" class="btn btn-primary"><i class="fas fa-check"></i> Inserir</a>
	//                         </div>
	//                     </div>
	//                 </div>
	// 			</form>
    //     	</div>
    //   	</div>
  	// </div>';

  	?>

	<script>

		$(document).ready(function() {
			ancora = $("#ancora").val();
			if(ancora && ancora != ''){
	        	window.location.href = '#'+ancora;
			}
		  	$('[data-toggle="tooltip"]').tooltip();   
	    });

		//Redes
	    $(document).on('click', '#modal_redes_id', function(){

			var string = $(this).attr('data-id').split('|');
	        var id = string[0];
	        var texto_titulo = string[1];

	        $("#id_ajuste_redes").val(id);
	        $("#nome_empresa_cliente").val(texto_titulo);
	        $('#titulo_redes').html("Inserir Conta a Receber - "+texto_titulo+"");

	    });
		
		$('#salvar_redes').click(function(){
	
			var nome_empresa_cliente = $('#nome_empresa_cliente').val();
			nome_empresa_cliente = nome_empresa_cliente.replace("<strong>", "");
			nome_empresa_cliente = nome_empresa_cliente.replace("</strong>", "");

			if($('#descricao_redes').val() != ''){
			    if(!confirm('Inserir faturamento da empresa '+nome_empresa_cliente+'?')) {
					return false;
				}else{
                    modalAguarde();
			    	$('#inserir_conta_receber_redes').submit();
				}
			}else{
				alert('Insira uma descrição!');
				return false;
			}
			return false;
		});


		//Ajuste
		
		$(document).ready(function() {
			ancora = $("#ancora").val();
			if(ancora && ancora != ''){
	        	window.location.href = '#'+ancora;
			}
		  	$('[data-toggle="tooltip"]').tooltip();   
	    });

		function exclui_ajuste(id_ajuste, id_contrato_plano_pessoa, tipo_ajuste, valor_ajuste){
			$('#row_id_ajuste_'+id_ajuste).remove();
			$('#hr_id_ajuste_'+id_ajuste).remove();
			
			var valor_ajuste = parseFloat(moedaFloat(valor_ajuste));
	        var cobranca = parseFloat(moedaFloat($("#cobranca_"+id_contrato_plano_pessoa).text()));
	        var acrescimo = parseFloat(moedaFloat($("#acrescimo_"+id_contrato_plano_pessoa).text()));
	        var desconto = parseFloat(moedaFloat($("#desconto_"+id_contrato_plano_pessoa).text()));
	        
			if(tipo_ajuste == 'acrescimo'){
	       	    cobranca -= valor_ajuste;
	       	 	$("#acrescimo_"+id_contrato_plano_pessoa).text(floatMoeda((acrescimo-valor_ajuste).toFixed(2)));
	        }else{
	            cobranca += valor_ajuste;
	       	 	$("#desconto_"+id_contrato_plano_pessoa).text(floatMoeda((desconto-valor_ajuste).toFixed(2)));
	        }
			
			$("#cobranca_"+id_contrato_plano_pessoa).text(floatMoeda(cobranca.toFixed(2)));
			
			$.ajax({
		        url: "class/Faturamento.php",
		        dataType: "json",
		        method: 'POST',
		        data: {
		            acao: 'excluir_ajuste',
		            parametros : {
		               'tipo_ajuste': tipo_ajuste, 
		               'valor_ajuste': valor_ajuste,
		               'id_ajuste': id_ajuste
		            },
					token: '<?= $token ?>'
		        },
	        });    
		}


	</script>

	<?php
}

function faturamento_call_ativo($cancelados, $ordenacao, $token){

	$id_usuario = $_SESSION['id_usuario'];
	
	if($cancelados == 1){
		$filtro_cancelado = '';
		$filtro_cancelado_dados = "";
	}else{
		$filtro_cancelado = 'AND a.status = 1';
		$filtro_cancelado_dados = "AND b.status = 1";
    }
    
    if($ordenacao == 'cliente'){
        $filtro_ordenacao = ' ORDER BY d.nome ASC';
    }else{
        $filtro_ordenacao = ' ORDER BY c.dia_pagamento ASC';
    }

	$data_inicial = new DateTime(getDataHora('data'));
	$data_inicial->modify('first day of last month');
	$data_final = new DateTime(getDataHora('data'));
	$data_final->modify('last day of last month');
	
	$mes_atual = $data_inicial->format('m');
	$ano_atual = $data_inicial->format('Y');

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

	$data_hoje = getDataHora();
	$data_hoje = converteDataHora($data_hoje);	

	$dados_consulta = DBRead('','tb_faturamento_contrato a',"INNER JOIN tb_faturamento b ON a.id_faturamento = b.id_faturamento INNER JOIN tb_contrato_plano_pessoa c ON a.id_contrato_plano_pessoa = c.id_contrato_plano_pessoa INNER JOIN tb_pessoa d ON c.id_pessoa = d.id_pessoa INNER JOIN tb_plano e ON c.id_plano = e.id_plano WHERE e.cod_servico = 'call_ativo' AND b.data_referencia = '".$data_inicial->format('Y-m-d')."' AND a.contrato_pai = '1' AND b.adesao = '0' $filtro_cancelado_dados $filtro_ordenacao ", "a.*, b.*, c.*, d.*, c.status AS status_contrato, e.nome AS nome_plano, b.status AS status_faturamento");  

	echo "<div class=\"col-md-12\">";
	echo "<legend style=\"text-align:center;\"><strong>Faturamento de Call Center - Ativo: Referente a ".$dados_meses[$mes_atual]." de ".$ano_atual."</strong></legend>";

	echo "<div class='row'>";
		echo "<div class=\"col-md-12\" style='margin-bottom: 20px;'>";

			echo "<div style='text-align:center;'>";
		
				echo '<a style="color:#00008B;" href="/api/iframe?token='.$token.'&view=faturamento-avulso-form&cancelados='.$cancelados.'&ordenacao='.$ordenacao.'"><button class="btn" style="background-color: #a82fca !important; border-color: #a82fca !important; color: #fff !important;"> <i class="fa fa-file-invoice-dollar"></i> Inserir Faturamento de Avulso</button></a>';
			
			echo "</div>";
		echo "</div>";

		echo '
		<style>
			.body_scrol_ativo {
				display:block;
				height:480px;
				overflow:auto;
			}		
		</style>';


		echo "<div class=\"col-md-12\" >";

			if($dados_consulta){
				echo '<form method="post" action="/api/ajax?class=ContaReceber.php" id="controle_conta_receber" style="margin-bottom: 0;">
				<input type="hidden" name="token" value="'.$token.'">
				';

					echo '<div class="row">';
						echo '<div class="col-md-12">
								<div class="panel-group" id="accordionVinculos" role="tablist">
									<div class="panel" style="background-color: white !important; border: 1px solid #c1c1c1;">
										<div class="panel-heading clearfix" style="border-bottom: 1px solid #c1c1c1;">
											<div class="panel-title text-left pull-left">
												<input type="checkbox" id="checkTodosAtivos" name="checkTodosAtivos" style="position: relative; vertical-align: middle;"> 
												&nbsp
												<span style="position: relative; vertical-align: middle;" id="texto_check">Marcar Todos</span>
											</div>
											
										</div>
										
										<div class="panel-body body_scrol_ativo" style="padding:0px !important; overflow-x: hidden  ;" >
											<div class="row">
												<div class="col-md-12">';

												foreach($dados_consulta as $dado_consulta){
													echo '<div id="'.$dado_consulta['id_contrato_plano_pessoa'].'"></div>';
													$exibir = 0;

													if($dado_consulta['status_contrato'] == '1' || $dado_consulta['data_status'] >= $data_inicial->format('Y-m-d')){
														$verificacao_status_faturamento = DBRead('', 'tb_faturamento a', "INNER JOIN tb_faturamento_contrato b ON a.id_faturamento = b.id_faturamento WHERE a.data_referencia = '".$data_inicial->format('Y-m-d')."' AND b.id_contrato_plano_pessoa = '".$dado_consulta['id_contrato_plano_pessoa']."' AND a.adesao = '0' ".$filtro_cancelado." ");
														
														if($verificacao_status_faturamento){
															$exibir = 1;
														}

													} 

													if($exibir != 0 && (!$dado_consulta['contrato_pai'] || $dado_consulta['contrato_pai'] == '0')){

														//NOME DO CONTRATO
														if($dado_consulta['nome_contrato']){
															$nome_contrato = " (".$dado_consulta['nome_contrato'].") ";
														}else{
															$nome_contrato = '';
														}
														$contrato = '<strong style="position: relative; vertical-align: middle;">'.$dado_consulta['nome']."</strong> ".$nome_contrato;
															
														if($dado_consulta['tipo_cobranca'] == 'mensal_desafogo'){
															$tipo_cobranca = "Mensal com Desafogo";
														}else if($dado_consulta['tipo_cobranca'] == 'unitario'){
															$tipo_cobranca = "Unitário";
														}else if($dado_consulta['tipo_cobranca'] == 'prepago'){
															$tipo_cobranca = "Pré-pago";
														}else{
															$tipo_cobranca = ucfirst($dado_consulta['tipo_cobranca']);
														}

														if($dado_consulta['status_faturamento'] != 0){
															if($dado_consulta['avulso'] == 1){
																$panel = '<div class="panel panel-default" style="margin:10px;">';
																$cabecalho = '	<div class="panel-heading clearfix"style="color: #a82fca;">';

															}else{
																$panel = '<div class="panel panel-default" style="margin:10px;">';
																$cabecalho = '	<div class="panel-heading">';

															}

															//AQUI
															
															
															$dados_conta_receber = DBRead('','tb_conta_receber',"WHERE id_faturamento = '".$dado_consulta['id_faturamento']."' AND (situacao = 'aberta' OR situacao = 'quitada') ");  

															if(!$dados_conta_receber){
																if($dado_consulta['avulso'] == 1){
																	$cabecalho .= '
																	<div class= "row">
																		<h3 class="panel-title text-left col-md-6" style="margin-top: 2px;">
																			<input aceita-clona="sim" name="selecionar_ativo[]" type="checkbox" value="'.$dado_consulta['id_faturamento'].'" style="position: relative; vertical-align: middle;">
																			&nbsp
																			'.$contrato.' (Avulso)
																		</h3>
																		<div class="panel-title text-right col-md-6">';
	
																}else{
																	$cabecalho .= '
																	<div class= "row">
																		<h3 class="panel-title text-left col-md-6" style="margin-top: 2px;">
																			<input aceita-clona="sim" name="selecionar_ativo[]" type="checkbox" value="'.$dado_consulta['id_faturamento'].'" style="position: relative; vertical-align: middle;">
																			&nbsp
																			'.$contrato.'
																		</h3>
																		<div class="panel-title text-right col-md-6">';
																}
																

																		

																$botoes = '<a style="color:#00008B;" href="/api/iframe?token='.$token.'&view=faturamento-ajustar&alterar='.$dado_consulta['id_faturamento'].'&cancelados='.$cancelados.'&ordenacao='.$ordenacao.'" title="Ajustar"><span class="btn btn-sm btn-info"><i class="fa fa-pencil"></i> Ajustar</span></a>&nbsp;&nbsp;';

																$botoes = $botoes."<a style='color:#8B0000;' href=\"/api/ajax?class=Faturamento.php?cancelar=".$dado_consulta['id_contrato_plano_pessoa']."&cancelados=".$cancelados."&ordenacao=".$ordenacao."&servico=call_ativo&token=". $token ."\" title='Cancelar' onclick=\"if (!confirm('Cancelar a fatura da empresa ".addslashes($dado_consulta['nome']."".$nome_contrato).", referente a ".addslashes($dados_meses[$mes_atual]." de ".$ano_atual)."?')) { return false; } else { modalAguarde(); }\"><span class='btn btn-sm btn-danger'><i class= 'fa fa-window-close-o' ></i> Cancelar </span></a>&nbsp;&nbsp;";

																$cliente_id_cidade = $dado_consulta['id_cidade'];
																$cliente_logradouro = $dado_consulta['logradouro'];
																$cliente_numero = $dado_consulta['numero'];
																$cliente_bairro = $dado_consulta['bairro'];
																$cliente_cep = $dado_consulta['cep'];
																$cliente_razao_social = $dado_consulta['razao_social'];
																$cliente_cpf_cnpj = $dado_consulta['cpf_cnpj'];
																$tipo_pessoa = strtoupper(substr($dado_consulta['tipo'], 1));
																
																if(($cliente_razao_social && $cliente_cpf_cnpj && $cliente_id_cidade && $cliente_logradouro && $cliente_numero && $cliente_bairro) && ($tipo_pessoa == 'J' && valida_cnpj($cliente_cpf_cnpj) || $tipo_pessoa == 'F' && valida_cpf($cliente_cpf_cnpj)) && $cliente_cep){		    		

																	$botoes = $botoes."<a style='color:#0B610B;' id='modal_call_ativo_id' href='' data-toggle='modal' data-target='#modal_call_ativo' data-id='".$dado_consulta['id_faturamento']."|".$contrato."' title='Gerar Conta Receber'><span class='btn btn-sm btn-warning'><i class= 'fas fa-arrow-circle-down'></i> Conta a Receber </span></a>";

																}else{

																	$alerta_nfs  = 'Para a emissão da NFS-e da pessoa '.addslashes($dado_consulta['nome']."".$nome_contrato).', faltam os seguintes dados:\n';
																	if(!$cliente_id_cidade || $cliente_id_cidade == '9999999'){
																		$alerta_nfs  = $alerta_nfs.' - Cidade\n';
																	}
																	if(!$cliente_logradouro){
																		$alerta_nfs  = $alerta_nfs.' - Logradouro\n';
																	}
																	if(!$cliente_numero){
																		$alerta_nfs  = $alerta_nfs.' - Número do Logradouro\n';
																	}
																	if(!$cliente_bairro){
																		$alerta_nfs  = $alerta_nfs.' - Bairro\n';
																	}
																	if(!$cliente_cep){
																		$alerta_nfs  = $alerta_nfs.' - CEP\n';
																	}
																	if(!$cliente_razao_social){
																		$alerta_nfs  = $alerta_nfs.' - Razão Social\n';
																	}
																	if(!$cliente_cpf_cnpj){
																		$alerta_nfs  = $alerta_nfs.' - CPF/CNPJ\n';
																	}

																	$botoes = $botoes."<a style='color:#0B610B;' href=\"\" title='Gerar Conta Receber' onclick=\"alert('".substr($alerta_nfs, 0, -2)."'); return false;\"><span class='btn btn-sm btn-warning'><i class= 'fas fa-arrow-circle-down'></i> Conta a Receber </span></a>";
																}

															}else{

																$cabecalho .= '
																	<div class= "row">
																		<h3 class="panel-title text-left col-md-6" style="margin-top: 2px;">
																			
																		&nbsp
																		&nbsp
																		&nbsp
																		'.$contrato.'
																		</h3>
																		<div class="panel-title text-right col-md-6">';
																
																$botoes = "<span style='color:#000000;' title='Conta a receber já gerada'><span class='btn btn-sm btn-default' style='pointer-events: none;' disabled><i class='fa fa-pencil'></i> Ajustar</span></a>&nbsp;&nbsp;";

																$botoes = $botoes."<span style='color:#000000;' title='Conta a receber já gerada'><span class='btn btn-sm btn-default' style='pointer-events: none;' disabled><i class= 'fa fa-window-close-o' ></i> Cancelar </span></a>&nbsp;&nbsp;";

																$botoes = $botoes."<span style='color:#000000;' data-toggle='tooltip' title='Data e hora da emissão: ".converteDataHora($dados_conta_receber[0]['data_cadastro'])."'><span class='btn btn-sm btn-default' style='pointer-events: none;' disabled><i class= 'fas fa-arrow-circle-down'></i> Conta a Receber </span></span>";
															}
																	
														}else{

															$panel = '<div class="panel panel-danger" style="margin:10px;">';
															
															$cabecalho = '
																	<div class= "row">
																		<h3 class="panel-title text-left col-md-6" style="margin-top: 2px;">
																			
																		&nbsp
																		&nbsp
																		&nbsp
																		'.$contrato.'
																		</h3>
																		<div class="panel-title text-right col-md-6">';

															$botoes = "<a style='color:#088A08;' href=\"/api/ajax?class=Faturamento.php?reativar=".$dado_consulta['id_contrato_plano_pessoa']."&cancelados=".$cancelados."&ordenacao=".$ordenacao."&servico=call_ativo&token=". $token ."\" title='Reativar' onclick=\"if (!confirm('Reativar a fatura da empresa ".addslashes($dado_consulta['nome']."".$nome_contrato).", referente a ".addslashes($dados_meses[$mes_atual]." de ".$ano_atual)."?')) { return false; } else { modalAguarde(); }\"><span class='btn btn-sm btn-success'><i class= 'fa fa-check-square-o' ></i> Reativar </span></a>&nbsp;&nbsp";
														}

														echo $panel;
														echo $cabecalho;        
														echo $botoes;            	

																echo '
																	</div>
																</div>
															</div>

															<div class="panel-body" id="panel_body_'.$dado_consulta['id_contrato_plano_pessoa'].'">
																<div class="row">
																	<div class="col-md-3"><strong>Plano: </strong>'.$dado_consulta['nome_plano'].'</div>
																	<div class="col-md-3"><strong>Tipo de Cobrança: </strong>'.$tipo_cobranca.'</div>
																	<div class="col-md-3"><strong>Valor Total do Contrato: </strong>R$ '.converteMoeda($dado_consulta['valor_total'], 'moeda').'</div>
																	<div class="col-md-3"><strong>Valor da Cobrança: </strong>R$ '.converteMoeda($dado_consulta['valor_cobranca'],'moeda').'</div>					
																</div>

																<div class="row">
																	<div class="col-md-3"><strong>Qtd. Contratada: </strong>'.$dado_consulta['qtd_contratada'].'</div>
																	<div class="col-md-3"><strong>Qtd. Efetuada: </strong>'.$dado_consulta['qtd_efetuada'].'</div>
																	<div class="col-md-3"><strong>Qtd. Excedente: </strong>'.$dado_consulta['qtd_excedente'].'</div>
																	<div class="col-md-3"><strong>Dia de Pagamento: </strong>'.$dado_consulta['dia_pagamento'].'</div>
																</div>';

													echo '</div>
														</div>';			
															
													}
												}

												echo '
												</div>
											</div>
										</div>
										<div class="panel-footer" style="background-color: white !important; border-top: 1px solid #c1c1c1;">
											<div class="row">
												<div class="col-md-12" style="text-align: center">

													<input type="hidden" id="operacao_conta_receber" name="abc" value="1"/>
													<input type="hidden" id="ordenacao" name="ordenacao" value="'.$ordenacao.'"/>
													<input type="hidden" id="cancelados" name="cancelados" value="'.$cancelados.'"/>

													<button class="btn btn-sm btn-primary" id="gerar_contas_receber_todos" type="button" data-toggle="modal" data-target="#modal_call_ativo_check" disabled><i class="fas fa-arrow-circle-down"></i> Gerar Contas a Receber</button>
																								
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>';
					echo '</form>';

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
	echo "</div>";


	//MODAL
	echo '
	<div class="modal fade" id="modal_call_ativo" role="dialog">
    	<div class="modal-dialog">
		    <div class="modal-content">
			    <form method="post" action="/api/ajax?class=ContaReceber.php" id="inserir_conta_receber_call_ativo" style="margin-bottom: 0;">
				<input type="hidden" name="token" value="'.$token.'">

			        <div class="modal-header">
	            		<h3 class="panel-title text-left pull-left" style="margin-top: 2px;" id="titulo_call_ativo">*Descrição da Nota:</h3>
			        </div>
			        <div class="modal-body">
						<input type="hidden" name="id_ajuste_call_ativo" id="id_ajuste_call_ativo">
						<input type="hidden" name="cancelados_call_ativo" id="cancelados_call_ativo" value="'.$cancelados.'">
						<input type="hidden" name="ordenacao_call_ativo" id="ordenacao_call_ativo" value="'.$ordenacao.'">
						<input type="hidden" name="nome_empresa_cliente" id="nome_empresa_cliente">
				       	
						<div class="row">
							<div class="col-md-12">
								<div class="form-group" >
	                    			<textarea name="descricao_call_ativo" id="descricao_call_ativo" type="text" class="form-control input-sm" rows="5"></textarea>
								</div>
							</div>
						</div>
								
					</div>
	                <div class="modal-footer">
	                    <div class="row">
	                        <div id="panel_buttons" class="col-md-12" style="text-align: center">
                                <input type="hidden" id="operacao" value="2" name="faturamento_call_ativo"/>
					          	<a href="#" id="salvar_call_ativo" class="btn btn-primary"><i class="fas fa-check"></i> Inserir</a>
	                        </div>
	                    </div>
	                </div>
				</form>
        	</div>
      	</div>
  	</div>';

  	?>

	<script>

		$(document).ready(function() {
			ancora = $("#ancora").val();
			if(ancora && ancora != ''){
	        	window.location.href = '#'+ancora;
			}
		  	$('[data-toggle="tooltip"]').tooltip();   
	    });

		//call_ativo
	    $(document).on('click', '#modal_call_ativo_id', function(){

			var string = $(this).attr('data-id').split('|');
	        var id = string[0];
	        var texto_titulo = string[1];

	        $("#id_ajuste_call_ativo").val(id);
	        $("#nome_empresa_cliente").val(texto_titulo);
	        $('#titulo_call_ativo').html("Inserir Conta a Receber - "+texto_titulo+"");

	    });
		
		$('#salvar_call_ativo').click(function(){
	
			var nome_empresa_cliente = $('#nome_empresa_cliente').val();
			nome_empresa_cliente = nome_empresa_cliente.replace("<strong>", "");
			nome_empresa_cliente = nome_empresa_cliente.replace("</strong>", "");

			if($('#descricao_call_ativo').val() != ''){
			    if(!confirm('Inserir faturamento da empresa '+nome_empresa_cliente+'?')) {
					return false;
				}else{
                    modalAguarde();
			    	$('#inserir_conta_receber_call_ativo').submit();
				}
			}else{
				alert('Insira uma descrição!');
				return false;
			}
			return false;
		});

		$(document).on('click', '#checkTodosAtivos', function () {
			if ($(this).is(':checked')){
				$("#gerar_contas_receber_email_todos").attr("disabled", false);
				$("#gerar_contas_receber_boleto_todos").attr("disabled", false);
				$("#gerar_contas_receber_todos").attr("disabled", false);
			
				$('[name ="selecionar_ativo[]"]').prop("checked", true);
				$('#texto_check').text("Desmarcar Todos");
			}else{
				$("#gerar_contas_receber_email_todos").attr("disabled", true);
				$("#gerar_contas_receber_boleto_todos").attr("disabled", true);
				$("#gerar_contas_receber_todos").attr("disabled", true);

				$('[name ="selecionar_ativo[]"]').prop("checked", false);
				$('#texto_check').text("Marcar Todos");
			}
		});

		$(document).on('click', '#gerar_contas_receber_todos', function(){
			if (!confirm('Atenção! Certifique-se que os valores dos contratos de Call Center - Ativos já foram ajustados este mês!')){
    		return false; 
    		}			
			$('#operacao_conta_receber').attr('name', 'conta_receber_check_ativo');
    		$('#controle_conta_receber').submit();
		});
  
		$(document).on('click', '[name ="selecionar_ativo[]"]', function(){
			var libera = 0;
			var nao_libera = 0;
			$("[name ='selecionar_ativo[]']").each(function(){
				if ($(this).is(':checked')){
					libera ++;
				}else{
					nao_libera ++;
				}
			});
			if(libera != 0){
				$("#gerar_contas_receber_email_todos").attr("disabled", false);
				$("#gerar_contas_receber_boleto_todos").attr("disabled", false);
				$("#gerar_contas_receber_todos").attr("disabled", false);

				if(nao_libera == 0){
					$("#checkTodosAtivos").prop("checked", true);
					$('#texto_check').text("Desmarcar Todos");
				}else{
					$("#checkTodosAtivos").prop("checked", false);
					$('#texto_check').text("Marcar Todos");
				}
				
			}else{
				$("#gerar_contas_receber_email_todos").attr("disabled", true);
				$("#gerar_contas_receber_boleto_todos").attr("disabled", true);
				$("#gerar_contas_receber_todos").attr("disabled", true);

				$("#checkTodosAtivos").prop("checked", false);
				$('#texto_check').text("Marcar Todos");
			}
		});

	</script>
	<?php
}

function faturamento_call_monitoramento($cancelados, $ordenacao, $token){
	
	$id_usuario = $_SESSION['id_usuario'];

	if($cancelados == 1){
		$filtro_cancelado = '';
		$filtro_cancelado_dados = '';
	}else{
		$filtro_cancelado = 'AND a.status = 1';
		$filtro_cancelado_dados = "AND b.status = 1";
    }
    
    if($ordenacao == 'cliente'){
        $filtro_ordenacao = ' ORDER BY d.nome ASC';
    }else{
        $filtro_ordenacao = ' ORDER BY c.dia_pagamento ASC';
    }

	$data_inicial = new DateTime(getDataHora('data'));
	$data_inicial->modify('first day of last month');
	$data_final = new DateTime(getDataHora('data'));
	$data_final->modify('last day of last month');
	
	$mes_atual = $data_inicial->format('m');
	$ano_atual = $data_inicial->format('Y');

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

	$data_hoje = getDataHora();
	$data_hoje = converteDataHora($data_hoje);	

	$dados_consulta = DBRead('','tb_faturamento_contrato a',"INNER JOIN tb_faturamento b ON a.id_faturamento = b.id_faturamento INNER JOIN tb_contrato_plano_pessoa c ON a.id_contrato_plano_pessoa = c.id_contrato_plano_pessoa INNER JOIN tb_pessoa d ON c.id_pessoa = d.id_pessoa INNER JOIN tb_plano e ON c.id_plano = e.id_plano WHERE e.cod_servico = 'call_monitoramento' AND b.data_referencia = '".$data_inicial->format('Y-m-d')."' AND a.contrato_pai = '1' AND b.adesao = '0' $filtro_cancelado_dados $filtro_ordenacao ", "a.*, b.*, c.*, d.*, c.status AS status_contrato, e.nome AS nome_plano");  

	echo "<div class=\"col-md-12\" style=\"padding: 0\">";
	echo "<legend style=\"text-align:center;\"><strong>Faturamento de Call Center - Monitoramento: Referente a ".$dados_meses[$mes_atual]." de ".$ano_atual."</strong></legend>";
	if($dados_consulta){
		echo '
		<style>
			.body_scrol_ativo {
				display:block;
				height:480px;
				overflow:auto;
			}		
		</style>';

		echo '<form method="post" action="/api/ajax?class=ContaReceber.php" id="controle_conta_receber" style="margin-bottom: 0;">
		<input type="hidden" name="token" value="'.$token.'">';

			echo '<div class="row">';
				echo '<div class="col-md-12">
						<div class="panel-group" id="accordionVinculos" role="tablist">
							<div class="panel" style="background-color: white !important; border: 1px solid #c1c1c1;">
								<div class="panel-heading clearfix" style="border-bottom: 1px solid #c1c1c1;">
									<div class="panel-title text-left pull-left">
										<input type="checkbox" id="checkTodosMonitoramento" name="checkTodosMonitoramento" style="position: relative; vertical-align: middle;"> 
										&nbsp
										<span style="position: relative; vertical-align: middle;" id="texto_check">Marcar Todos</span>
									</div>
									
								</div>
								
								<div class="panel-body body_scrol_ativo" style="padding:0px !important; overflow-x: hidden  ;" >
									<div class="row">
										<div class="col-md-12">';

		foreach($dados_consulta as $dado_consulta){
			echo '<div id="'.$dado_consulta['id_contrato_plano_pessoa'].'"></div>';
			$exibir = 0;

			if($dado_consulta['status_contrato'] == '1' || $dado_consulta['data_status'] >= $data_inicial->format('Y-m-d')){
				$verificacao_status_faturamento = DBRead('', 'tb_faturamento a', "INNER JOIN tb_faturamento_contrato b ON a.id_faturamento = b.id_faturamento WHERE a.data_referencia = '".$data_inicial->format('Y-m-d')."' AND b.id_contrato_plano_pessoa = '".$dado_consulta['id_contrato_plano_pessoa']."' AND a.adesao = '0' ".$filtro_cancelado." ");
				
				if($verificacao_status_faturamento){
					$exibir = 1;
				}
			}

			if($exibir != 0 && (!$dado_consulta['contrato_pai'] || $dado_consulta['contrato_pai'] == '0')){

				//NOME DO CONTRATO
				if($dado_consulta['nome_contrato']){
	                $nome_contrato = " (".$dado_consulta['nome_contrato'].") ";
	            }else{
	                $nome_contrato = '';
	            }
	            $contrato = "<strong>".$dado_consulta['nome']."</strong> ".$nome_contrato;
					
				//Verifica acrescimo, desconto, cobranca, etc
				$dados_verificacao = DBRead('', 'tb_faturamento a',"INNER JOIN tb_faturamento_contrato b ON a.id_faturamento = b.id_faturamento WHERE b.id_contrato_plano_pessoa = '".$dado_consulta['id_contrato_plano_pessoa']."' AND a.data_referencia = '".$data_inicial->format('Y-m-d')."' AND a.adesao = '0' ");

				if($dados_verificacao[0]['tipo_cobranca'] == 'mensal_desafogo'){
					$tipo_cobranca = "Mensal com Desafogo (".$dados_verificacao[0]['desafogo_contrato']."%)";
				}else if($dado_consulta[0]['tipo_cobranca'] == 'unitario'){
					$tipo_cobranca = "Unitário";
				}else if($dado_consulta[0]['tipo_cobranca'] == 'prepago'){
					$tipo_cobranca = "Pré-pago";
				}else{
					$tipo_cobranca = ucfirst($dados_verificacao[0]['tipo_cobranca']);
				}

		        if($dados_verificacao[0]['status'] != 0){
		        	$panel = '<div class="panel panel-default" style="margin:10px;">';

						$dados_conta_receber = DBRead('','tb_conta_receber',"WHERE id_faturamento = '".$dado_consulta['id_faturamento']."' AND (situacao = 'aberta' OR situacao = 'quitada') ");  

		        		if(!$dados_conta_receber){

							$cabecalho = '
								<div class="panel-heading">
									<div class= "row">
										<h3 class="panel-title text-left col-md-6" style="margin-top: 2px;">
											<input aceita-clona="sim" name="selecionar_monitoramento[]" type="checkbox" value="'.$dado_consulta['id_faturamento'].'" style="position: relative; vertical-align: middle;">
											&nbsp
											'.$contrato.'
										</h3>
									<div class="panel-title text-right col-md-6">';
										
		        			$botoes = '<a style="color:#00008B;" id="modal_id" href="" data-toggle="modal" data-target="#modal" data-id="'.$dado_consulta['id_contrato_plano_pessoa'].'|'.$contrato.'" title="Ajustar"><span class="btn btn-sm btn-info"><i class="fa fa-pencil"></i> Ajustar</span></a>&nbsp;&nbsp;';

		        			$botoes = $botoes."<a style='color:#8B0000;' href=\"/api/ajax?class=Faturamento.php?cancelar=".$dado_consulta['id_contrato_plano_pessoa']."&cancelados=".$cancelados."&ordenacao=".$ordenacao."&servico=call_monitoramento&token=". $token ."\" title='Cancelar' onclick=\"if (!confirm('Cancelar a fatura da empresa ".addslashes($dado_consulta['nome']."".$nome_contrato).", referente a ".addslashes($dados_meses[$mes_atual]." de ".$ano_atual)."?')) { return false; } else { modalAguarde(); }\"><span class='btn btn-sm btn-danger'><i class= 'fa fa-window-close-o' ></i> Cancelar </span></a>&nbsp;&nbsp;";

						
		        			$cliente_id_cidade = $dado_consulta['id_cidade'];
					        $cliente_logradouro = $dado_consulta['logradouro'];
					        $cliente_numero = $dado_consulta['numero'];
					        $cliente_bairro = $dado_consulta['bairro'];
					        $cliente_cep = $dado_consulta['cep'];
					        $cliente_razao_social = $dado_consulta['razao_social'];
					        $cliente_cpf_cnpj = $dado_consulta['cpf_cnpj'];
							$tipo_pessoa = strtoupper(substr($dado_consulta['tipo'], 1));
					    	
					    	if(($cliente_razao_social && $cliente_cpf_cnpj && $cliente_id_cidade && $cliente_logradouro && $cliente_numero && $cliente_bairro) && ($tipo_pessoa == 'J' && valida_cnpj($cliente_cpf_cnpj) || $tipo_pessoa == 'F' && valida_cpf($cliente_cpf_cnpj)) && $cliente_cep){

					    		$botoes = $botoes."<a style='color:#0B610B;' href=\"/api/ajax?class=ContaReceber.php?faturamento=".$dado_consulta['id_faturamento']."&cancelados=".$cancelados."&ordenacao=".$ordenacao."&token=". $token ."\" title='Gerar NFS-e' onclick=\"if (!confirm('Gerar NFS-e a partir da fatura da empresa ".addslashes($dado_consulta['nome']."".$nome_contrato).", referente a ".addslashes($dados_meses[$mes_atual]." de ".$ano_atual)."?')) { return false; } else { modalAguarde(); }\"><span class='btn btn-sm btn-warning'><i class= 'fas fa-arrow-circle-down'></i> Conta a Receber </span></a>";

					    	}else{

					    		$alerta_nfs  = 'Para a emissão da NFS-e da pessoa '.addslashes($dado_consulta['nome']."".$nome_contrato).', faltam os seguintes dados:\n';
						        if(!$cliente_id_cidade || $cliente_id_cidade == '9999999'){
						            $alerta_nfs  = $alerta_nfs.' - Cidade\n';
						        }
						        if(!$cliente_logradouro){
						            $alerta_nfs  = $alerta_nfs.' - Logradouro\n';
						        }
						        if(!$cliente_numero){
						            $alerta_nfs  = $alerta_nfs.' - Número do Logradouro\n';
						        }
						        if(!$cliente_bairro){
						            $alerta_nfs  = $alerta_nfs.' - Bairro\n';
						        }
						        if(!$cliente_cep){
						            $alerta_nfs  = $alerta_nfs.' - CEP\n';
						        }
						        if(!$cliente_razao_social){
						            $alerta_nfs  = $alerta_nfs.' - Razão Social\n';
						        }
						        if(!$cliente_cpf_cnpj){
						            $alerta_nfs  = $alerta_nfs.' - CPF/CNPJ\n';
						        }

					    		$botoes = $botoes."<a style='color:#0B610B;' href=\"/api/ajax?class=ContaReceber.php?faturamento=".$dado_consulta['id_faturamento']."&cancelados=".$cancelados."&ordenacao=".$ordenacao."&token=". $token ."\" title='Gerar NFS-e' onclick=\"alert('".substr($alerta_nfs, 0, -2)."'); return false;\"><span class='btn btn-sm btn-warning'><i class= 'fas fa-arrow-circle-down'></i> Conta a Receber </span></a>";
		        				
					    	}

		        		}else{

							$cabecalho = '
								<div class="panel-heading">
									<div class= "row">
										<h3 class="panel-title text-left col-md-6" style="margin-top: 2px;">
											&nbsp
											&nbsp
											&nbsp
											'.$contrato.'
										</h3>
									<div class="panel-title text-right col-md-6">';
		        			
		        			$botoes = "<span style='color:#000000;' title='Conta a receber já gerada'><span class='btn btn-sm btn-default' style='pointer-events: none;' disabled><i class='fa fa-pencil'></i> Ajustar</span></a>&nbsp;&nbsp;";

		        			$botoes = $botoes."<span style='color:#000000;' title='Conta a receber já gerada'><span class='btn btn-sm btn-default' style='pointer-events: none;' disabled><i class= 'fa fa-window-close-o' ></i> Cancelar </span></a>&nbsp;&nbsp;";

		        			$botoes = $botoes."<span style='color:#000000;' data-toggle='tooltip' title='Data e hora da emissão: ".converteDataHora($dados_conta_receber[0]['data_cadastro'])."'><span class='btn btn-sm btn-default' style='pointer-events: none;' disabled><i class= 'fas fa-arrow-circle-down'></i> Conta a Receber </span></span>";
		        		}
		        			  
		        			  	  
				}else{
					$panel = '<div class="panel panel-danger" style="margin:10px;">';
					
					$cabecalho = '
					<div class="panel-heading">
						<div class= "row">
							<h3 class="panel-title text-left col-md-6" style="margin-top: 2px;">
								&nbsp
								&nbsp
								&nbsp
								'.$contrato.'
							</h3>
						<div class="panel-title text-right col-md-6">';

					$botoes = "<a style='color:#088A08;' href=\"/api/ajax?class=Faturamento.php?reativar=".$dado_consulta['id_contrato_plano_pessoa']."&cancelados=".$cancelados."&ordenacao=".$ordenacao."&servico=call_monitoramento&token=". $token ."\" title='Reativar' onclick=\"if (!confirm('Reativar a fatura da empresa ".addslashes($dado_consulta['nome']."".$nome_contrato).", referente a ".addslashes($dados_meses[$mes_atual]." de ".$ano_atual)."?')) { return false; } else { modalAguarde(); }\"><span class='btn btn-sm btn-success'><i class= 'fa fa-check-square-o' ></i> Reativar </span></a>&nbsp;&nbsp";
				}
				

					echo $panel;
					echo $cabecalho;        
			        echo $botoes;            	
		                      	
							echo '
        						</div>
        					</div>
		                </div>
					  	<div class="panel-body" id="panel_body_'.$dado_consulta['id_contrato_plano_pessoa'].'">
					    	<div class="row">
								<div class="col-md-3"><strong>Plano: </strong>'.$dado_consulta['nome_plano'].'</div>
								<div class="col-md-3"><strong>Tipo de Cobrança: </strong>'.$tipo_cobranca.'</div>
                                <div class="col-md-3"><strong>Dia de Pagamento: </strong>'.$dado_consulta['dia_pagamento'].'</div>
								<div class="col-md-3"><strong>Valor Unitário do Contrato: </strong>R$ <span id = "valor_unitario_'.$dado_consulta['id_contrato_plano_pessoa'].'">'.converteMoeda($dados_verificacao[0]['valor_unitario_contrato'],'moeda').'</span></div>
							</div>

							<div class="row">
								<div class="col-md-3"><strong>Valor Excedente do Contrato: </strong>R$ <span id = "valor_excedente_'.$dado_consulta['id_contrato_plano_pessoa'].'">'.converteMoeda($dados_verificacao[0]['valor_excedente_contrato'],'moeda').'</span></div>
								<div class="col-md-3"><strong>Valor Total do Contrato: </strong>R$ '.converteMoeda($dados_verificacao[0]['valor_total_contrato'],'moeda').'</div>
								<div class="col-md-3"><strong>Quantidade Contratada: </strong>'.$dados_verificacao[0]['qtd_contratada'].'</div>
								<div class="col-md-3"><strong>Quantidade Efetuada: </strong>'.$dados_verificacao[0]['qtd_efetuada'].'</div>							
							</div>
							
							<div class="row">
								<div class="col-md-3"><strong>Quantidade de Excedente: </strong>'.$dados_verificacao[0]['qtd_excedente'].'</div>
								<div class="col-md-3"><strong>Total de Acréscimos: </strong>R$ <span id = "acrescimo_'.$dado_consulta['id_contrato_plano_pessoa'].'">'.converteMoeda($dados_verificacao[0]['acrescimo'],'moeda').'</span></div>
								<div class="col-md-3"><strong>Total de Descontos: </strong>R$ <span id = "desconto_'.$dado_consulta['id_contrato_plano_pessoa'].'">'.converteMoeda($dados_verificacao[0]['desconto'], 'moeda').'</span></div>
                                <div class="col-md-3"><strong>Valor da Cobrança: </strong>R$ <span id = "cobranca_'.$dado_consulta['id_contrato_plano_pessoa'].'">'.converteMoeda($dados_verificacao[0]['valor_cobranca'], 'moeda').'</span></div>
                            </div>';
                            
							if($dados_verificacao[0]['acrescimo'] != '0.00' || $dados_verificacao[0]['desconto'] != '0.00'){

								$dados_ajuste = DBRead('', 'tb_faturamento_ajuste',"WHERE id_faturamento = '".$dados_verificacao[0]['id_faturamento']."' ");
								if($dados_ajuste){
									foreach($dados_ajuste as $conteudo_ajuste){
										$id_ajuste = $conteudo_ajuste['id_faturamento_ajuste'];

										echo '<hr id="hr_id_ajuste_'.$id_ajuste.'">';

										if($conteudo_ajuste['tipo'] == 'acrescimo'){
											$tipo_de_ajuste = "Acréscimo";
										}else{
											$tipo_de_ajuste = "Desconto";
										}
										

										$dados_usuario = DBRead('', 'tb_usuario a',"INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE id_usuario = '".$conteudo_ajuste['id_usuario']."'", "b.nome");

									    echo '
									    <div class="row" id="row_id_ajuste_'.$id_ajuste.'">
											<div class="col-md-3">'.$tipo_de_ajuste.': R$ '.converteMoeda($conteudo_ajuste['valor'],'moeda').'</div>
											<div class="col-md-3">Referente a: '.nl2br($conteudo_ajuste['descricao']).'</div>
											<div class="col-md-3">Data e Hora: '.converteDataHora($conteudo_ajuste['data']).'</div>
											<div class="col-md-2">Usuário: '.$dados_usuario[0]['nome'].'</div>
											<div class="col-md-1">';
											if(!$dados_conta_receber){
												echo '<a id="excluir_'.$id_ajuste.'" title="Excluir Ajuste" onclick=\'if (!confirm("Excluir '.addslashes($tipo_de_ajuste).' de R$ '.converteMoeda($conteudo_ajuste['valor'],'moeda').' ?")) {  return false; } else { exclui_ajuste('.$id_ajuste.', '.$dado_consulta['id_contrato_plano_pessoa'].', "'.$conteudo_ajuste['tipo'].'", "'.converteMoeda($conteudo_ajuste['valor'],'moeda').'"); }\'><i class="fa fa-trash" style="color:#b92c28; cursor: pointer;"></i></a>';
											}else{
												echo '';
											}
	
											echo '
											</div>
										</div>';

									}	
								}
								
							}
		        			$botoes = '<a style="color:#00008B;" id="modal_id" href="" data-toggle="modal" data-target="#modal" data-id="'.$dado_consulta['id_contrato_plano_pessoa'].'|'.$contrato.'" title="Ajustar"><span class="btn btn-sm btn-info"><i class="fa fa-pencil"></i> Ajustar</span></a>&nbsp;&nbsp;';

				   echo '</div>
					  </div>';				
			}
		}

		echo '
									</div>
								</div>
							</div>
							<div class="panel-footer" style="background-color: white !important; border-top: 1px solid #c1c1c1;">
								<div class="row">
									<div class="col-md-12" style="text-align: center">

										<input type="hidden" id="operacao_conta_receber" name="abc" value="1"/>
										<input type="hidden" id="ordenacao" name="ordenacao" value="'.$ordenacao.'"/>
										<input type="hidden" id="cancelados" name="cancelados" value="'.$cancelados.'"/>

										<button class="btn btn-sm btn-primary" id="gerar_contas_receber_todos" type="button" data-toggle="modal" data-target="#modal_call_ativo_check" disabled><i class="fas fa-arrow-circle-down"></i> Gerar Contas a Receber</button>
																					
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>';
		echo '</form>';

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
		
	echo '<br><br>';

 	//MODAL
	echo '<div class="modal fade" id="modal" role="dialog">
	    	<div class="modal-dialog">
			    <div class="modal-content">
			        <div class="modal-header">
                		<h3 class="panel-title text-left pull-left" style="margin-top: 2px;" id="titulo"></h3>
			        </div>
			        <div class="modal-body">';
						
						echo '<input type="hidden" name="id_ajuste" id="id_ajuste">';
				        echo '	                		
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<label>*Tipo:</label>
                                    <select class="form-control input-sm" id="tipo_ajuste" name="tipo_ajuste">
										<option value="">Selecione um tipo de ajuste...</option>
                                        <option value="acrescimo">Acréscimo</option>
                                        <option value="desconto">Desconto</option>
                                    </select>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<div class="form-group" >
							        <label>*Valor (R$):</label>
                        			<input name="valor_ajuste" id="valor_ajuste" type="text" class="form-control input-sm money" autocomplete="off">
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<div class="form-group" >
							        <label>*Referente a:</label>
                        			<textarea name="descricao" id="descricao" type="text" class="form-control input-sm" rows="5"></textarea>
								</div>
							</div>
						</div>
								
					</div>
                    <div class="modal-footer">
                        <div class="row">
                            <div id="panel_buttons" class="col-md-12" style="text-align: center">
                                <button class="btn btn-primary" name="ajustar" id="ajustar" value="2" type="submit"><i class="fa fa-check"></i> Realizar Ajuste</button>';
                            echo '
                            </div>
                        </div>
                    </div>
	            	';
	     	   echo '
	        	</div>
	      	</div>
	  	</div>';
	
	  	?>

	<script>
		
		$(document).ready(function() {
			ancora = $("#ancora").val();
			if(ancora && ancora != ''){
	        	window.location.href = '#'+ancora;
			}
		  	$('[data-toggle="tooltip"]').tooltip();   
	    });

		function exclui_ajuste(id_ajuste, id_contrato_plano_pessoa, tipo_ajuste, valor_ajuste){
			$('#row_id_ajuste_'+id_ajuste).remove();
			$('#hr_id_ajuste_'+id_ajuste).remove();
			
			var valor_ajuste = parseFloat(moedaFloat(valor_ajuste));
	        var cobranca = parseFloat(moedaFloat($("#cobranca_"+id_contrato_plano_pessoa).text()));
	        var acrescimo = parseFloat(moedaFloat($("#acrescimo_"+id_contrato_plano_pessoa).text()));
	        var desconto = parseFloat(moedaFloat($("#desconto_"+id_contrato_plano_pessoa).text()));
	        
			if(tipo_ajuste == 'acrescimo'){
	       	    cobranca -= valor_ajuste;
	       	 	$("#acrescimo_"+id_contrato_plano_pessoa).text(floatMoeda((acrescimo-valor_ajuste).toFixed(2)));
	        }else{
	            cobranca += valor_ajuste;
	       	 	$("#desconto_"+id_contrato_plano_pessoa).text(floatMoeda((desconto-valor_ajuste).toFixed(2)));
	        }
			
			$("#cobranca_"+id_contrato_plano_pessoa).text(floatMoeda(cobranca.toFixed(2)));
			
			$.ajax({
		        url: "class/Faturamento.php",
		        dataType: "json",
		        method: 'POST',
		        data: {
		            acao: 'excluir_ajuste',
		            parametros : {
		               'tipo_ajuste': tipo_ajuste, 
		               'valor_ajuste': valor_ajuste,
		               'id_ajuste': id_ajuste
		            },
					token: '<?= $token ?>'
		        },
	        });    
		}

		$(document).ready(function() {
			ancora = $("#ancora").val();
			if(ancora && ancora != ''){
	        	window.location.href = '#'+ancora;
			}
		  	$('[data-toggle="tooltip"]').tooltip();   
	    });

		$(document).on('click', '#checkTodosMonitoramento', function () {
			if ($(this).is(':checked')){
				
				$("#gerar_contas_receber_email_todos").attr("disabled", false);
				$("#gerar_contas_receber_boleto_todos").attr("disabled", false);
				$("#gerar_contas_receber_todos").attr("disabled", false);
			
				$('[name ="selecionar_monitoramento[]"]').prop("checked", true);
				$('#texto_check').text("Desmarcar Todos");
			}else{
				$("#gerar_contas_receber_email_todos").attr("disabled", true);
				$("#gerar_contas_receber_boleto_todos").attr("disabled", true);
				$("#gerar_contas_receber_todos").attr("disabled", true);

				$('[name ="selecionar_monitoramento[]"]').prop("checked", false);
				$('#texto_check').text("Marcar Todos");
			}
		});

		$(document).on('click', '#gerar_contas_receber_todos', function(){
			if (!confirm('Atenção! Certifique-se que os valores dos contratos de Call Center - Monitoramento já foram ajustados este mês!')){
    		return false; 
    		}			
			$('#operacao_conta_receber').attr('name', 'conta_receber_check_monitoramento');
    		$('#controle_conta_receber').submit();
		});
  
		$(document).on('click', '[name ="selecionar_monitoramento[]"]', function(){
			var libera = 0;
			var nao_libera = 0;
			$("[name ='selecionar_monitoramento[]']").each(function(){
				if ($(this).is(':checked')){
					libera ++;
				}else{
					nao_libera ++;
				}
			});
			if(libera != 0){
				$("#gerar_contas_receber_email_todos").attr("disabled", false);
				$("#gerar_contas_receber_boleto_todos").attr("disabled", false);
				$("#gerar_contas_receber_todos").attr("disabled", false);

				if(nao_libera == 0){
					$("#checkTodosMonitoramento").prop("checked", true);
					$('#texto_check').text("Desmarcar Todos");
				}else{
					$("#checkTodosMonitoramento").prop("checked", false);
					$('#texto_check').text("Marcar Todos");
				}
				
			}else{
				$("#gerar_contas_receber_email_todos").attr("disabled", true);
				$("#gerar_contas_receber_boleto_todos").attr("disabled", true);
				$("#gerar_contas_receber_todos").attr("disabled", true);

				$("#checkTodosMonitoramento").prop("checked", false);
				$('#texto_check').text("Marcar Todos");
			}
		});

	</script>

	<?php
}
?>