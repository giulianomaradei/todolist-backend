<?php
require_once(__DIR__."/System.php");
$parametros = (!empty($_POST['parametros'])) ? $_POST['parametros'] : '';
$tipo_pessoa = addslashes($parametros['tipo_pessoa']);
$letra = addslashes($parametros['nome']);

//contrato
$id_plano = addslashes($parametros['id_plano']);
$servico = addslashes($parametros['servico']);
$tipo_cobranca = addslashes($parametros['tipo_cobranca']);
$id_responsavel = addslashes($parametros['id_responsavel']);

$pf_pj = addslashes($parametros['pf_pj']);
$candidato = addslashes($parametros['candidato']);
$cliente = addslashes($parametros['cliente']);
$fornecedor = addslashes($parametros['fornecedor']);
$funcionario = addslashes($parametros['funcionario']);
$prospeccao = addslashes($parametros['prospeccao']);

echo '
<style>
.body_conta_receber {
    display:block;
    height:430px;
    overflow:auto;
}
thead, tbody tr {
    display:table;
    width:100%;
    table-layout:fixed;
}

</style>';
//$dados = DBRead('', 'tb_pessoa', "WHERE status = 1 AND (nome LIKE '%$letra%' OR razao_social LIKE '%$letra%')", "nome AS nome_pessoa, razao_social AS razao_social_pessoa, email1 AS email_pessoa, tipo, candidato, cliente, fornecedor, funcionario, prospeccao, id_pessoa");

	if($tipo_pessoa == 'pessoa'){
		
		if(!$letra){	
			// Informações da query
			if($pf_pj){
				$filtro_pf_pj = " AND tipo = '".$pf_pj."' ";
			}

			if($candidato){
				if($candidato == 1){
					$valor_candidato = '1';
				}else{
					$valor_candidato = '0';
				}
				$filtro_candidato = " AND candidato = '".$valor_candidato."' ";
			}

			if($cliente){
				if($cliente == 1){
					$valor_cliente = '1';
				}else{
					$valor_cliente = '0';
				}
				$filtro_cliente = " AND cliente = '".$valor_cliente."' ";
			}

			if($fornecedor){
				if($fornecedor == 1){
					$valor_fornecedor = '1';
				}else{
					$valor_fornecedor = '0';
				}
				$filtro_fornecedor = " AND fornecedor = '".$valor_fornecedor."' ";
			}

			if($funcionario){
				if($funcionario == 1){
					$valor_funcionario = '1';
				}else{
					$valor_funcionario = '0';
				}
				$filtro_funcionario = " AND funcionario = '".$valor_funcionario."' ";
			}

			if($prospeccao){
				if($prospeccao == 1){
					$valor_prospeccao = '1';
				}else{
					$valor_prospeccao = '0';
				}
				$filtro_prospeccao = " AND prospeccao = '".$valor_prospeccao."' ";
			}
		
		$filtros_query = " ".$filtro_pf_pj." ".$filtro_candidato." ".$filtro_cliente." ".$filtro_fornecedor." ".$filtro_funcionario." ".$filtro_prospeccao." ORDER BY nome ";
		###################################################################################
		// INICIO DO CONTEÚDO
		//
		
		}else{
			$filtros_query = " AND (nome LIKE '%$letra%' OR razao_social LIKE '%$letra%') ORDER BY nome ";
		}
		$dados = DBRead('', 'tb_pessoa', "WHERE status = 1 ".$filtros_query, "nome AS nome_pessoa, razao_social AS razao_social_pessoa, email1 AS email1_pessoa, email2 AS email2_pessoa, tipo, candidato, cliente, fornecedor, funcionario, prospeccao, id_pessoa");

	}else{
		if(!$letra){
			$dados = DBRead('', 'tb_pessoa', "WHERE status = 1 AND (nome LIKE '%$letra%' OR razao_social LIKE '%$letra%')", "nome AS nome_pessoa, razao_social AS razao_social_pessoa, email1 AS email_pessoa, tipo, candidato, cliente, fornecedor, funcionario, prospeccao, id_pessoa");
			// Informações da query
			if($id_plano){
				$filtro_plano = " AND a.id_plano = '".$id_plano."' ";
			}

			if($servico){
				$filtro_servico = " AND c.cod_servico = '".$servico."' ";
			}

			if($tipo_cobranca){
				$filtro_tipo_cobranca = " AND a.tipo_cobranca = '".$tipo_cobranca."' ";
			}
			
			if($id_responsavel){
				$filtro_id_responsavel = " AND a.id_responsavel = '".$id_responsavel."' ";
			}
			
			$filtros_query = "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa INNER JOIN tb_plano c ON a.id_plano = c.id_plano WHERE a.status = 1 ".$filtro_plano." ".$filtro_servico." ".$filtro_tipo_cobranca." ".$filtro_id_responsavel." ORDER BY b.nome";

			###################################################################################
			// INICIO DO CONTEÚDO
			//
		}else{
			$filtros_query = "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa INNER JOIN tb_plano c ON a.id_plano = c.id_plano WHERE a.status = 1  AND (b.nome LIKE '%$letra%' OR b.razao_social LIKE '%$letra%') ORDER BY b.nome";
		}
		
		$dados = DBRead('', 'tb_contrato_plano_pessoa a', $filtros_query, "DISTINCT b.nome AS nome_pessoa, b.razao_social AS razao_social_pessoa, b.email1 AS email_pessoa, a.email_nf AS email_contrato, c.cod_servico, c.nome AS nome_plano, a.id_responsavel, a.tipo_cobranca, a.id_contrato_plano_pessoa");
	}

echo '
	<div class="row">
        <div class="col-md-12">
            <div class="panel-group" id="accordionVinculos" role="tablist">
                <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    <div class="panel-title text-left pull-left">
                    Contrato/Pessoa
                    </div>
                </div>';
if (!$dados) {
	echo '<div class="panel-body" styx height: 525px !important;">';
		echo "<p class='alert alert-warning' style='text-align: center'>";
			echo "Não foram encontrados registros!";
		echo "</p>";
	echo '</div>';
}else{

			echo '                                
                <form method="post" action="class/teste.php" id="enviar" style="margin-bottom: 0;">
					<div class="panel-body" styx height: 525px !important;">';
					echo '<input type="hidden" name="tipo_pessoa" value="'.$tipo_pessoa.'"/>';
					echo "<div class='table-condensed'>";
						echo "<table class='table table-condensed' style='font-size: 14px;'>";
							echo "<thead>";
								echo "<tr>";
								    echo "<th class='col-md-1 text-center' style='vertical-align: middle;'><input type='checkbox' id='checkTodos' name='checkTodos'></th>";
								    echo "<th class='col-md-2'>Pessoa/Contrato</th>";
									echo "<th class='col-md-2'>E-mail</th>";
									if($tipo_pessoa == 'contrato'){
										echo "<th class='col-md-2'>Serviço</th>";
										echo "<th class='col-md-2'>Plano</th>";
										echo "<th class='col-md-1'>Tipo de Cobrança</th>";
										echo "<th class='col-md-2'>Responsável</th>";
									}else{
										echo "<th class='col-md-1 text-center'>PF/PJ</th>";
										echo "<th class='col-md-1 text-center'>Candidato</th>";
										echo "<th class='col-md-1 text-center'>Cliente</th>";
										echo "<th class='col-md-1 text-center'>Fornecedor</th>";
										echo "<th class='col-md-1 text-center'>Funcionário</th>";
										echo "<th class='col-md-2 text-center'>Prospecção</th>";
									}
								    
							    echo "</tr>";
							echo "</thead>";
							echo "<tbody class='body_conta_receber'>";

							$cont_registros = 0;
							$cont_emails = 0;
							foreach ($dados as $conteudo) {
						        $cont_registros++;
								if($tipo_pessoa == 'contrato'){
									$id = $conteudo['id_contrato_plano_pessoa'];
								}else{
									$id = $conteudo['id_pessoa'];
								}
						        $nome_pessoa = $conteudo['nome_pessoa'];
						        $razao_social_pessoa = $conteudo['razao_social_pessoa'];
                                
                                $dados_usuario = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_usuario = '".$conteudo['id_responsavel']."' ");
								$responsavel = $dados_usuario[0]['nome'];

								$td_checkbox = "<td class='col-md-1 text-center'><input name='selecionar[]'' type='checkbox' value='".$id."' id='".$id."'></td>";
								$class = 'default';
								if($tipo_pessoa == 'contrato'){
									if(!$conteudo['email_contrato']){
										$class = 'danger';
										$td_checkbox = "<td class='col-md-1 text-center'><input type='checkbox' value='".$id."' id='".$id."' disabled></td>";
										$cont_emails++;
									}
								}else{
									if(!$conteudo['email1_pessoa'] && !$conteudo['email2_pessoa']){
										$class = 'danger';
										$td_checkbox = "<td class='col-md-1 text-center'><input type='checkbox' value='".$id."' id='".$id."' disabled></td>";
										$cont_emails++;
									}
								}
                                echo "<tr class='".$class."' value='".$id."'>";

                                    echo $td_checkbox;
                                                                        
                                    echo "<td  class='col-md-2'> <i class='fa fa-address-card-o'></i> ".$nome_pessoa."<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$razao_social_pessoa."</td>";
									
									if($conteudo['email_contrato']){
										$email = str_replace(","," ",$conteudo['email_contrato']);
										$email = str_replace(";"," ",$email);

									}else{
										if($conteudo['email1_pessoa'] || $conteudo['email2_pessoa']){
											if($conteudo['email1_pessoa']){
												$email = str_replace(","," ",$conteudo['email1_pessoa']);
												$email = str_replace(";"," ",$email);
											}else{
												$email = str_replace(","," ",$conteudo['email2_pessoa']);
												$email = str_replace(";"," ",$email);
											}
											
										}else{
											$email = '';
										}
										
									}
										echo "<td  class='col-md-2'>".$email."</td>";
									if($tipo_pessoa == 'pessoa'){
										if($conteudo['tipo'] == 'pf'){
											$tipo_pf_pj = "Pessoa Física";
										}else{
											$tipo_pf_pj = "Pessoa Jurídica";
										}
										echo "<td class='col-md-1 text-center'>".$tipo_pf_pj."</td>";

										if($conteudo['candidato'] == 1){
											$tipo_candidato = "Sim";
										}else{
											$tipo_candidato = "Não";
										}
										echo "<td class='col-md-1 text-center'>".$tipo_candidato."</td>";

										if($conteudo['cliente'] == 1){
											$tipo_cliente = "Sim";
										}else{
											$tipo_cliente = "Não";
										}
										echo "<td class='col-md-1 text-center'>".$tipo_cliente."</td>";
										
										if($conteudo['fornecedor'] == 1){
											$tipo_fornecedor = "Sim";
										}else{
											$tipo_fornecedor = "Não";
										}
										echo "<td class='col-md-1 text-center'>".$tipo_fornecedor."</td>";
										
										if($conteudo['funcionario'] == 1){
											$tipo_funcionario = "Sim";
										}else{
											$tipo_funcionario = "Não";
										}
										echo "<td class='col-md-1 text-center'>".$tipo_funcionario."</td>";
										
										if($conteudo['prospecacao'] == 1){
											$tipo_prospecacao = "Sim";
										}else{
											$tipo_prospecacao = "Não";
										}
										echo "<td class='col-md-2 text-center'>".$tipo_prospecacao."</td>";
										
									}else{
										echo "<td class='col-md-2'>".getNomeServico($conteudo['cod_servico'])."</td>";
								
										echo "<td class='col-md-2'>".$conteudo['nome_plano']."</td>";
										
										if($conteudo['tipo_cobranca'] == 'mensal_desafogo'){
											$tipo_cobranca = "Mensal com Desafogo";
										}else if($conteudo['tipo_cobranca'] == 'unitario'){
											$tipo_cobranca = "Unitário";
										}else if($conteudo['tipo_cobranca'] == 'x_cliente_base'){
											$tipo_cobranca = "Até X Clientes na Base";
										}else if($conteudo['tipo_cobranca'] == 'prepago'){
											$tipo_cobranca = "Pré-pago";
										}else{
											$tipo_cobranca = ucfirst($conteudo['tipo_cobranca']);
										}
										echo "<td class='col-md-1'>".$tipo_cobranca."</td>";
										
										echo "<td class='col-md-2'>".$responsavel."</td>";
									}

                                
                                // echo "<td class='text-center col-md-1' style='vertical-align: middle;'>";
                                // $enbsp = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
                                // if($conteudo['envio_email'] == 0){
                                //     echo "<a style='padding-left: 4px; padding-right: 4px;' href=\"class/ContaReceber.php?ignorar=$id_conta_receber\" title='Ignorar o envio de e-mail' onclick=\"if (!confirm('Ignorar o envio de e-mail da ".addslashes($origem)." da Pessoa ".addslashes($nome)."?')) { return false; } else { modalAguarde(); }\"><i class='fas fa-exclamation-triangle' style='color:#b92c28;'></i></a>";	                                        								
                                // }
                                // echo "</td>";							        
                            
						        echo "</tr>";
						    }
							echo "</tbody>";
						echo "</table>";

						echo "<hr>";
						echo '
						<div class="row">
			                <div class="col-sm-6">
			                    <div class="form-group">
			                        <h5 class="text-left">Total de registros: <strong>'.$cont_registros.'</strong><br>Total de e-mails faltantes: <strong>'.$cont_emails.'</strong></h5>
			                    </div>
			                </div>
			                <div class="col-sm-6">
			                    <div class="form-group">
			                        <h5 class="text-right">Selecionados: <strong id="total_selecionados">0</strong></h5>
			                    </div>
			                </div>
					    </div> ';

                    echo "
                    </div>";
				echo "</div>";

				echo '
					<div class="panel-footer">
	                    <div class="row">
	                        <div class="col-md-12" style="text-align: center">
                                <input type="hidden" name="enviar_email" value="1"/>

								<button class="btn btn-info" id="enviar_email" type="submit" disabled><i class="fas fa-envelope-open-text"></i> Enviar E-mail</button>
                            
                            </div>
	                    </div>
	                </div>
	            </div>';

	            echo'
	            </form>';
	        
}
echo
			'</div>
	    </div>
	</div>';

// FIM DO CONTEUDO
###################################################################################
?>

<script>
$("#enviar_email").click(function(){
	var tipo_pessoa = '<?=$tipo_pessoa?>';
	if(tipo_pessoa == 'contrato'){
		tipo_pessoa = 'Contrato(s)';
	}else{
		tipo_pessoa = 'Pessoa(s)';
	}
	var cont =0;
	$("[name ='selecionar[]']").each(function(){
		if ($(this).is(':checked')){
			cont = cont+1;
		}
	});
	if(!confirm('Enviar e-mail para '+cont+' '+tipo_pessoa+'?')) {
		return false;
	}
	$('#enviar').attr('action', "/v2//api/iframe?token=<?php echo $request->token ?>&view=email-enviar-form").submit();
});

$(document).on('click', '#checkTodos', function () {
    if ($(this).is(':checked')){
    	$("#enviar_email").attr("disabled", false);
        $('[name ="selecionar[]"]').prop("checked", true);
		
		var cont =0;
		$("[name ='selecionar[]']").each(function(){
			if ($(this).is(':checked')){
				cont = cont+1;
			}
		});

    	$("#total_selecionados").text(cont);
    }else{
    	$("#enviar_email").attr("disabled", true);
        $('[name ="selecionar[]"]').prop("checked", false);
    	$("#total_selecionados").text('0');
    }
});

$(document).on('click', '[name ="selecionar[]"]', function(){
	var cont =0;
	$("[name ='selecionar[]']").each(function(){
        if ($(this).is(':checked')){
        	cont = cont+1;
        }
    });

    if(cont >= 1){
    	$("#total_selecionados").text(cont);
    	$("#enviar_email").attr("disabled", false);
    }else{
    	$("#total_selecionados").text('0');
    	$("#enviar_email").attr("disabled", true);
    }
});

</script>