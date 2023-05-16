<?php
require_once(__DIR__."/System.php");
$parametros = (!empty($_POST['parametros'])) ? $_POST['parametros'] : '';
$letra = addslashes($parametros['nome']);
$situacao = addslashes($parametros['situacao']);
$agrupador = addslashes($parametros['agrupador']);
$envio_email = addslashes($parametros['envio_email']);
$id_busca = addslashes($parametros['id_busca']);
$data_hoje_consulta = converteData(getDataHora('data'));
$obs_conta_receber = addslashes($parametros['obs_conta_receber']);
$faturamento_conta_receber = addslashes($parametros['faturamento_conta_receber']);

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
if($id_busca){
	// Informações da query
	$filtros_query = "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa INNER JOIN tb_natureza_financeira c ON a.id_natureza_financeira = c.id_natureza_financeira INNER JOIN tb_natureza_financeira_agrupador d ON c.id_natureza_financeira_agrupador = d.id_natureza_financeira_agrupador INNER JOIN tb_caixa e ON a.id_caixa = e.id_caixa WHERE a.situacao = 'aberta' AND a.id_conta_receber = '".$id_busca."' ORDER BY a.data_vencimento ASC";
}else{
	if($agrupador){
	    $filtro_agrupador = " AND c.id_natureza_financeira = '".$agrupador."' ";
	}

	if($situacao){
	    $filtro_situacao = " AND a.situacao = '".$situacao."' ";
	}

	if($parametros['data_de'] && $parametros['data_ate']){
	    $data_de = converteData($parametros['data_de']);
	    $data_ate = converteData($parametros['data_ate']);

	    $data_de = $data_de.' 00:00:00';
	    $data_ate = $data_ate.' 23:59:59';

	    $filtro_data = "AND (a.data_vencimento BETWEEN '$data_de' AND '$data_ate')";
	    //$filtro_data = "AND (a.data_emissao BETWEEN '$data_de' AND '$data_ate' OR a.data_vencimento BETWEEN '$data_de' AND '$data_ate')";

	}else if($parametros['data_de'] && !$parametros['data_ate']){
	    $data_de = converteData($parametros['data_de']);

	    $data_de = $data_de.' 00:00:00';

	    $filtro_data = "AND (a.data_vencimento >= '$data_de')";
	    //$filtro_data = "AND (a.data_emissao >= '$data_de' OR a.data_vencimento >= '$data_de')";

	}else if(!$parametros['data_de'] && $parametros['data_ate']){

	    $data_ate = converteData($parametros['data_ate']);

	    $data_ate = $data_ate.' 23:59:59';

	    $filtro_data = "AND (a.data_vencimento <= '$data_ate')";
	    //$filtro_data = "AND (a.data_emissao <= '$data_ate' OR a.data_vencimento <= '$data_ate')";
	}

	if($envio_email && $envio_email != 4){
	    $filtro_envio_email = "AND a.envio_email = '".$envio_email."' ";
	}else if($envio_email == 0){
	    $filtro_envio_email = "AND a.envio_email = '0' ";
	}

	if($obs_conta_receber){
		if($obs_conta_receber == 1){
			$filtro_obs_conta_receber = "AND (a.observacao IS NOT NULL AND a.observacao != '') ";
		}else if($obs_conta_receber == 2){
			$filtro_obs_conta_receber = "AND (a.observacao IS NULL OR a.observacao = '') ";
		}
	}

	if($faturamento_conta_receber){
		if($faturamento_conta_receber == 1){
			$filtro_faturamento_conta_receber = "AND (a.id_faturamento IS NOT NULL AND a.id_faturamento != '') ";
		}else if($faturamento_conta_receber == 2){
			$filtro_faturamento_conta_receber = "AND (a.id_faturamento IS NULL OR a.id_faturamento = '') ";
		}
	}

	// Informações da query

	$filtros_query = "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa INNER JOIN tb_natureza_financeira c ON a.id_natureza_financeira = c.id_natureza_financeira INNER JOIN tb_natureza_financeira_agrupador d ON c.id_natureza_financeira_agrupador = d.id_natureza_financeira_agrupador INNER JOIN tb_caixa e ON a.id_caixa = e.id_caixa WHERE a.situacao = 'aberta' AND (b.nome LIKE '%$letra%' OR b.razao_social LIKE '%$letra%') ".$filtro_data." ".$filtro_agrupador." ".$filtro_situacao." ".$filtro_envio_email." ".$filtro_obs_conta_receber." ".$filtro_faturamento_conta_receber." ORDER BY a.data_vencimento ASC";

}

###################################################################################
// INICIO DO CONTEÚDO
//
$dados = DBRead('', 'tb_conta_receber a', $filtros_query, "a.*, b.nome, c.nome AS nome_natureza, d.nome AS nome_natureza_agrupador, e.aceita_movimentacao");

echo '
	<div class="row">
        <div class="col-md-12">
            <div class="panel-group" id="accordionVinculos" role="tablist">
                <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    <div class="panel-title text-right pull-right">
                        <a href="/api/iframe?token=<?php echo $request->token ?>&view=boleto-busca" target="_blank"><button class="btn btn-xs btn-primary"><i class="fa fa-barcode"></i> Boletos</button></a>
                        <a href="/api/iframe?token=<?php echo $request->token ?>&view=nfs-busca" target="_blank"><button class="btn btn-xs btn-primary"><i class="fas fa-file-invoice-dollar"></i> NFS-e</button></a>
                        <a href="/api/iframe?token=<?php echo $request->token ?>&view=conta-receber-form"><button class="btn btn-xs btn-primary"><i class="fa fa-plus"></i> Nova</button></a>
                    </div>
                </div>';
if (!$dados) {
	echo '<div class="panel-body" styx height: 525px !important;">';
		echo "<p class='alert alert-warning' style='text-align: center'>";
		if(!$letra) {
				echo "Não foram encontrados registros!";
		}else{
			echo "Nenhum resultado encontrado na busca por \"<strong>$letra</strong>\"";
		}
		echo "</p>";
	echo '</div>';
}else{

			echo '                                
                <form method="post" action="class/ContaReceber.php" id="controle_conta_receber" style="margin-bottom: 0;">
                    <div class="panel-body" styx height: 525px !important;">';
					echo "<div class='table-condensed'>";
						echo "<table class='table table-condensed' style='font-size: 14px;'>";
							echo "<thead>";
								echo "<tr>";
								    echo "<th class='col-md-1 text-center' style='vertical-align: middle;'><input type='checkbox' id='checkTodosReceber' name='checkTodosReceber'></th>";
								    echo "<th class='col-md-1'>#</th>";
								    echo "<th class='col-md-4'>Descrição</th>";
								    echo "<th class='col-md-1 text-center'>Informações</th>";
								    echo "<th class='col-md-2 text-center'>Data de Emissão</th>";
								    echo "<th class='col-md-2 text-center'>Data de Vencimento</th>";
								    echo "<th class='col-md-1 text-center'>Valor</th>";
								    echo "<th class='col-md-1 text-center'>Opções</th>";
							    echo "</tr>";
							echo "</thead>";
							echo "<tbody class='body_conta_receber'>";

							echo '<input type="hidden" id="id_antigo_tabela_conta_receber" value="'.$dados[0]['id_conta_receber'].'">';
							echo '<input type="hidden" id="data_pagamento_hidden_conta_receber" name="data_pagamento_hidden_conta_receber">';
							echo '<input type="hidden" id="descricao_baixar_hidden_conta_receber" name="descricao_baixar_hidden_conta_receber">';


							$cont_registros_conta_receber = 0;
							$soma_total_conta_receber = 0;
							foreach ($dados as $conteudo) {
						        $cont_registros_conta_receber++;

						        $id_conta_receber = $conteudo['id_conta_receber'];

						        $nome = $conteudo['nome'];

						        $natureza = $conteudo['nome_natureza_agrupador']." (".$conteudo['nome_natureza'].")";
						        $nome_natureza = $conteudo['nome_natureza'];
                                
                                $informacoes = '';

						        $data_emissao = converteData($conteudo['data_emissao']);
						        
						        $data_vencimento = $conteudo['data_vencimento'];
								$data_hoje = getDataHora('data');
								
								$aceita_movimentacao = $conteudo['aceita_movimentacao'];
                                
                                $dados_caixa = DBRead('', 'tb_caixa', "WHERE id_caixa = '".$conteudo['id_caixa']."' ");
                                $dados_usuario = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_usuario = '".$conteudo['id_usuario']."' ");
                                $dados_pessoa = DBRead('', 'tb_pessoa', "WHERE id_pessoa = '".$conteudo['id_pessoa']."' ");
                                
                                if($dados_pessoa[0]['cpf_cnpj']){
                                    $cpf_cnpj = formataCampo('cpf_cnpj', $dados_pessoa[0]['cpf_cnpj']);
                                }else{
                                    $cpf_cnpj = '';
                                }

                                $titulo_conta = 'Conta a Receber';                                

                                if($conteudo['id_conta_pai']){
                                    $conta_pai = 'Possui';
                                }else{
                                    $conta_pai = '';
								}
								$teste = '';
								if($conteudo['observacao']){
									$informacoes .= '<span style="color:#FF8C00;"><i class="fas fa-clipboard-list"></i> Observação</span><br>';
									$teste = "obs_sim";
								}

                                if($conteudo['id_boleto']){
                                    $id_boleto = $conteudo['id_boleto'];
                                    $btn_visualizar_boleto = ' <a href="/api/iframe?token=<?php echo $request->token ?>&view=boleto-visualizar&visualizar='.$id_boleto.'" target="_blank"><i class="fa fa-eye"></i></a>';
                                    $dados_boleto = DBRead('','tb_boleto', "WHERE id_boleto = '$id_boleto'");
                                    if($dados_boleto[0]['situacao'] != 'EMITIDO' && $dados_boleto[0]['situacao'] != 'REJEITADO'){
                                        $informacoes .= '<span><i class="fa fa-barcode" aria-hidden="true"></i> Boleto registrado</span>';
                                    }else if($dados_boleto[0]['situacao'] == 'REJEITADO'){
                                        $informacoes .= '<span class="text-danger faa-flash animated"><i class="fa fa-barcode" aria-hidden="true"></i> Boleto rejeitado</span>';
                                    }else{
                                        $informacoes .= '<span class="text-warning faa-flash animated"><i class="fa fa-barcode" aria-hidden="true"></i> Boleto não registrado</span>';
                                    }
                                }else{
                                    $id_boleto = '';
                                    $btn_visualizar_boleto = '';
                                }

                                if($conteudo['id_nfs']){
                                    $id_nfs = $conteudo['id_nfs'];
                                    $dados_nfs = DBRead('','tb_nfs',"WHERE id_nfs = '$id_nfs'");
                                    $btn_visualizar_nfs = ' <a href="/api/iframe?token=<?php echo $request->token ?>&view=nfs-visualizar&visualizar='.$id_nfs.'" target="_blank"><i class="fa fa-eye"></i></a>';
                                    if($dados_nfs[0]['status'] == 'autorizada'){                                       
                                        $informacoes .= '<br><span><i class="fas fa-file-invoice-dollar"></i> NFS-e emitida</span>';
                                    }else if($dados_nfs[0]['status'] == 'negada'){
                                        $informacoes .= '<br><span class="text-danger faa-flash animated"><i class="fas fa-file-invoice-dollar"></i> NFS-e negada</span>';
                                    }else{
                                        $informacoes .= '<br><span class="text-warning faa-flash animated"><i class="fas fa-file-invoice-dollar"></i> NFS-e pendente</span>';
                                    }
                                    
                                }else{
                                    $id_nfs = '';
                                    $btn_visualizar_nfs = '';
                                }

                                if($conteudo['id_faturamento']){
                                    $id_faturamento = $conteudo['id_faturamento'];
                                }else{
                                    $id_faturamento = '';
                                }

                                if($conteudo['data_pagamento']){
                                    $data_pagamento = converteData($conteudo['data_pagamento']);
                                }else{
                                    $data_pagamento = '';
                                }

                                if($dados_pessoa[0]['email']){
                                    $email = $dados_pessoa[0]['email'];
                                }else{
                                    $email = '';
                                }

                                if($dados_pessoa[0]['fone1']){
                                    $telefone = formataCampo('fone', $dados_pessoa[0]['fone1']);
                                }else{
                                    $telefone = '';
                                }

						        if(strtotime($data_hoje) > strtotime($data_vencimento)){
						        	$data_do_vencimento = "<td class='col-md-2 text-center modal_contas_receber' id='data_vencimento_td' style='vertical-align: middle; cursor: pointer;' attr-id='$id_conta_receber'><strong class=' text-danger'>".converteData($conteudo['data_vencimento'])."<strong></td>";
						        }else if(strtotime($data_hoje) == strtotime($data_vencimento)){
						        	$data_do_vencimento = "<td class='col-md-2 text-center modal_contas_receber' id='data_vencimento_td' style='vertical-align: middle; cursor: pointer;' attr-id='$id_conta_receber'><strong class=' text-warning'>".converteData($conteudo['data_vencimento'])."<strong></td>";
						        }else{
						        	$data_do_vencimento = "<td class='col-md-2 text-center modal_contas_receber' id='data_vencimento_td' style='vertical-align: middle; cursor: pointer;' attr-id='$id_conta_receber'>".converteData($conteudo['data_vencimento'])."</td>";
						        }

								$soma_total_conta_receber += $conteudo['valor'];
						        $valor = converteMoeda($conteudo['valor']);

						        $situacao = ucfirst($conteudo['situacao']);

						        if($conteudo['tipo'] == 'entrada'){
						        	$tipo = '<span class="label label-success" style="display: inline-block; min-width: 50px;">Entrada</span>';
						        	$tipo_modal = '<span class="label label-success" style="display: inline-block; min-width: 100px;"> Entrada </span>';
						        }else{
						        	$tipo = '<span class="label label-danger" style="display: inline-block; min-width: 50px;"> Saída </span>';
						        	$tipo_modal = '<span class="label label-danger" style="display: inline-block; min-width: 100px;"> Saída </span>';
						        }

					        	$origem = 'Conta Receber';

								if($aceita_movimentacao == 0){
						        	echo "<tr class='warning ".$teste."' id='tr_id_conta_receber' value='".$id_conta_receber."'>";
								}else{
									if($dados[0]['id_conta_receber'] == $id_conta_receber){
										echo "<tr class='info ".$teste."' id='tr_id_conta_receber' value='".$id_conta_receber."'>";
									}else{
										echo "<tr class='default ".$teste."' id='tr_id_conta_receber' value='".$id_conta_receber."'>";
									}	
								}

								if(!$conteudo['id_boleto'] && !$conteudo['id_nfs'] && !$conteudo['id_faturamento']){
									echo "<td class='col-md-1 text-center' style='vertical-align: middle;'><input aceita-clona='sim' name='selecionar_conta_receber[]'' type='checkbox' value='$id_conta_receber' id='$id_conta_receber'></td>";
								}else{
									echo "<td class='col-md-1 text-center' style='vertical-align: middle;'><input aceita-clona='nao' name='selecionar_conta_receber[]'' type='checkbox' value='$id_conta_receber' id='$id_conta_receber'></td>";

								}
									
									echo "<td class='col-md-1 modal_contas_receber' style='vertical-align: middle; cursor: pointer;' name='td_nome_conta_receber[]' attr-id='$id_conta_receber' >".$id_conta_receber."</td>";
									
							        echo "<td  class='col-md-4 modal_contas_receber' style='vertical-align: middle; cursor: pointer;' attr-id='$id_conta_receber'><i class='fas fa-donate'></i> ".$natureza."<br>
							        		  <i class='fa fa-address-card-o'></i> ".$nome."<br>";
							        		  if($conteudo['descricao']){
							        		  echo "<i class='fa fa-list-alt'></i> ".limitarTexto($conteudo['descricao'], 40);
							        		  }
									echo "</td>";
									
									echo "<td class='col-md-1 text-center modal_contas_receber' style='vertical-align: middle; cursor: pointer;' attr-id='$id_conta_receber' >".$informacoes."</td>";
									
									echo "<td class='col-md-2 text-center modal_contas_receber' style='vertical-align: middle; cursor: pointer;' attr-id='$id_conta_receber' >".$data_emissao."</td>";
									
							        echo $data_do_vencimento;
                                    echo "<td class='col-md-1 text-center modal_contas_receber' style='vertical-align: middle; cursor: pointer;' attr-id='$id_conta_receber'>R$ ".$valor."</td>";
                                    
                                    echo "<td class='text-center col-md-1' style='vertical-align: middle;'>";
                                    $enbsp = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';

                                    if(!$conteudo['id_nfs'] && !$conteudo['id_conta_pai']){
                                        echo "<a style='padding-left: 4px; padding-right: 4px; color:#0174DF;' href=\"class/ContaReceber.php?emitir_nfs=$id_conta_receber\" title='Emitir NFS-e' onclick=\"if (!confirm('Deseja emitir uma NFS-e para a ".addslashes($origem)." da Pessoa ".addslashes($nome)."?')) { return false; } else { modalAguarde(); }\"><i class='fas fa-file-invoice-dollar'></i></a>";
                                    }elseif($conteudo['id_nfs'] && $dados_nfs[0]['status'] == 'negada'){
                                        echo "<a style='padding-left: 4px; padding-right: 4px; color:#EB9316;' href=\"class/ContaReceber.php?reprocessar_nfs=$id_conta_receber\" title='Reprocessar NFS-e' onclick=\"if (!confirm('Deseja reprocessar a NFS-e para a ".addslashes($origem)." da Pessoa ".addslashes($nome)."?')) { return false; } else { modalAguarde(); }\"><i class='fas fa-file-invoice-dollar'></i></a>";
                                    }
                                    
                                    if($conteudo['id_boleto']){
                                        if($dados_boleto[0]['situacao'] == 'REJEITADO'){
                                            echo "<a style='padding-left: 4px; padding-right: 4px; color:#0174DF;' href=\"class/ContaReceber.php?emitir_boleto=$id_conta_receber\" title='Emitir boleto' onclick=\"if (!confirm('Deseja emitir um boleto para a ".addslashes($origem)." da Pessoa ".addslashes($nome)."?')) { return false; } else { modalAguarde(); }\"><i class='fa fa-barcode' aria-hidden='true'></i></a>";
                                        }
                                        if($dados_boleto[0]['situacao'] != 'EMITIDO' && !$dados_boleto[0]['remessa_pendente']){
                                            echo '<a style="padding-left: 4px; padding-right: 4px;" href="" title="Alterar Vencimento" value="'.$id_conta_receber.'" id="modal_alterar_vencimento" data-toggle="modal" data-target="#alterar_vencimento"><i class="fas fa-calendar-alt" style="color:#0174DF;"></i></a>';
                                        }
                                    }else{
                                        //mostrar botão emitir boleto

                                        echo "<a style='padding-left: 4px; padding-right: 4px; color:#0174DF;' href=\"class/ContaReceber.php?emitir_boleto=$id_conta_receber\" title='Emitir boleto' onclick=\"if (!confirm('Deseja emitir um boleto para a ".addslashes($origem)." da Pessoa ".addslashes($nome)."?')) { return false; } else { modalAguarde(); }\"><i class='fa fa-barcode' aria-hidden='true'></i></a>";	

                                        echo '<a style="padding-left: 4px; padding-right: 4px;" href="" title="Alterar Vencimento" value="'.$id_conta_receber.'" id="modal_alterar_vencimento" data-toggle="modal" data-target="#alterar_vencimento"><i class="fas fa-calendar-alt" style="color:#0174DF;"></i></a>';
                                    }	

							        if($conteudo['envio_email'] == 0){
                                        echo "<a style='padding-left: 4px; padding-right: 4px;' href=\"class/ContaReceber.php?ignorar=$id_conta_receber\" title='Ignorar o envio de e-mail' onclick=\"if (!confirm('Ignorar o envio de e-mail da ".addslashes($origem)." da Pessoa ".addslashes($nome)."?')) { return false; } else { modalAguarde(); }\"><i class='fas fa-exclamation-triangle' style='color:#b92c28;'></i></a>";	                                        								
                                    }

                                    echo "</td>";							        
								
						        echo "</tr>";
						    }
							echo "</tbody>";
						echo "</table>";

						echo "<hr>";
						echo '
						<div class="row">
			                <div class="col-sm-6">
			                    <div class="form-group">
			                        <h5 class="text-left">Total de registros: <strong>'.$cont_registros_conta_receber.'</strong></h5>
			                    </div>
			                </div>
			                <div class="col-sm-6">
			                    <div class="form-group">
			                        <h5 class="text-right">Valor Total: <strong>'.converteMoeda($soma_total_conta_receber).'</strong></h5>
			                    </div>
			                </div>
					    </div> ';



					echo "</div>";
				echo "</div>";

				//MODAL ALTERACAO VENCIMENTO CONTA RECEBER
                echo '
	            	<div class="modal fade" id="alterar_vencimento" role="dialog">
					    <div class="modal-dialog">
					      	<!-- Modal content-->
					      	<div class="modal-content">
					        	<div class="modal-header">
							        <h3 class="panel-title text-center pull-center" style="margin-top: 2px; font-size: 150%;">Data de Vencimento</h3>
					        	</div>
					        	<div class="modal-body">
									<input type="hidden" id="id_conta_receber_altera_vencimento" name="id_conta_receber_altera_vencimento" value="1"/>
									<input type="hidden" id="data_vencimento_antigo" name="data_vencimento_antigo" value="1"/>
					          		<div class="row">
	                                    <div class="col-md-12">
				                            <div class="form-group has-feedback">
				                                <label class="control-label">*Nova Data de Vencimento: </label>
				                                <input class="form-control date calendar hasDatePicker hasDatepicker" type="text" name="nova_data_vencimento" id="nova_data_vencimento" placeholder="dd/mm/aaaa" autocomplete="off" maxlength="10">
				                            </div>
				                        </div>
				                    </div>
					        	</div>
					        	<div class="modal-footer">
					          		<a href="#" id="submit_altera_vencimento" class="btn btn-primary"><i class="fas fa-check"></i> Alterar</a>
					        	</div>
					      	</div>      
					    </div>
					</div>';	

				
				echo '<input type="hidden" id="baixa_nota" name="baixa_nota" value="1"/>';
				echo '
					<div class="panel-footer">
	                    <div class="row">
	                        <div class="col-md-12" style="text-align: center">
                                <input type="hidden" id="operacao_conta_receber" name="abc" value="1"/>

                                <button class="btn btn-danger" id="baixar_conta_receber" type="button" data-toggle="modal" data-target="#confirm_submit_baixar_conta_receber" disabled><i class="fas fa-times"></i> Baixar</button>

								<button class="btn btn-success" id="quitar_conta_receber" type="button" data-toggle="modal" data-target="#confirm_submit_conta_receber" disabled><i class="fas fa-check"></i> Quitar</button>
								
								<button class="btn btn-info" id="enviar_email_conta_receber" type="submit" ><i class="fas fa-envelope-open-text"></i> Enviar E-mail</button>

								<button class="btn btn-warning" id="clonar_conta_receber" type="submit" disabled><i class="far fa-clone"></i> Clonar</button>

                            
                            </div>
	                    </div>
	                </div>
	            </div>';
                                
	            //MODAL QUITAR CONTA RECEBER
	            echo '
	            	<div class="modal fade" id="confirm_submit_conta_receber" role="dialog">
					    <div class="modal-dialog">
					      	<!-- Modal content-->
					      	<div class="modal-content">
					        	<div class="modal-header">
							        <h3 class="panel-title text-center pull-center" style="margin-top: 2px; font-size: 150%;">Data do Pagamento (Quitação)</h3>
					        	</div>
					        	<div class="modal-body">
					          		<div class="row">
	                                    <div class="col-md-12">
				                            <div class="form-group has-feedback">
				                                <label class="control-label">*Data: </label>
				                                <input class="form-control date calendar hasDatePicker hasDatepicker" type="text" id="data_pagamento_conta_receber" name="data_pagamento_conta_receber" placeholder="dd/mm/aaaa" autocomplete="off" maxlength="10">
				                            </div>
				                        </div>
				                    </div>
					        	</div>
					        	<div class="modal-footer">
					          		<a href="#" id="submit_conta_receber" class="btn btn-primary"><i class="fas fa-check"></i> Quitar</a>
					        	</div>
					      	</div>      
					    </div>
					</div>';
	            
	            //MODAL baixar CONTA RECEBER
				echo '
					<div class="modal fade" id="confirm_submit_baixar_conta_receber" role="dialog" >
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <h4 class="modal-title" id="myModalLabel">ATENÇÃO!</h4>
                                </div>
                                <div class="modal-body">

                                	<div class="row">
									    <div class="col-md-8">
									      	<div class="form-group has-feedback">
									        	<label>*Cancelar as Notas da(s) Conta(s) Selecionada(s)?</label>
									      	</div>
										</div>
										<div class="col-md-4">
										    <div class="form-group has-feedback">
										        <label class="radio-inline">
										    		<input type="radio" value="1" id="cancelar_nota_baixa_sim" name="cancelar_nota_quitar">Sim
										    	</label>
										    	<label class="radio-inline">
										      		<input type="radio" value="2" id="cancelar_nota_baixa_nao" name="cancelar_nota_quitar">Não
										    	</label>
										    </div>
										</div>
									</div>
                                    
                                    <div class="row">
	                                    <div class="col-md-12">
				                            <div class="form-group has-feedback">
				                                 <label>*Descrição:</label>
				                                    <textarea name="descricao_baixar_conta_receber" id="descricao_baixar_conta_receber" rows="5" cols="100" class="form-control"></textarea>
				                            </div>
				                        </div>
				                    </div>
                                </div>

                                <div class="modal-footer">
			                        <button class="btn btn-primary" id="submit_baixar_conta_receber" type="button"><i class="fas fa-check"></i> Baixar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    ';
					//<button class="btn btn-default" id="submit_baixar_conta_receber_nao" type="button"><i class="fas fa-times"></i> Não</button>

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

$(document).on('click', '#clonar_conta_receber', function(){
	$('#operacao_conta_receber').attr('name', 'clonar_conta_receber');

	$('#controle_conta_receber').attr('action', "/v2//api/iframe?token=<?php echo $request->token ?>&view=controle-contas-receber-clonar-form").submit();
});

$('#modal_alterar_vencimento').click(function(){
    
    var id_conta_receber = $(this).parent().parent().text().split(" ");
    $('#id_conta_receber_altera_vencimento').val(id_conta_receber[0]);
    $('#data_vencimento_antigo').val($(this).parent().parent().find('#data_vencimento_td').text());
	configuraDatepicker();
    configuraMascaras();
});

$('#submit_altera_vencimento').click(function(){
	$('#operacao_conta_receber').attr('name', 'alterar_conta_receber');
   
   	var data_nova = $('input[name="nova_data_vencimento"]').val();
   	var data_antiga = $('input[name="data_vencimento_antigo"]').val();
   	var data_hoje = '<?= $data_hoje_consulta?>';
   	
   	data_nova = data_nova.split("/");
   	data_antiga = data_antiga.split("/");
   	data_hoje = data_hoje.split("/");

   	data_nova = data_nova[2]+''+data_nova[1]+''+data_nova[0];
   	data_antiga = data_antiga[2]+''+data_antiga[1]+''+data_antiga[0];
   	data_hoje = data_hoje[2]+''+data_hoje[1]+''+data_hoje[0];

    if(!confirm('Alterar o vencimento da conta a receber?')) {
		return false;
	}else{
		if(data_nova <= data_antiga || data_nova <= data_hoje){
	    	alert('A nova data de vencimento deve ser maior que a data de vencimento antiga e maior que a data atual!');
	    	return false;
	    }else{
    		$('#controle_conta_receber').submit();
	    }
	}

});


$('#submit_baixar_conta_receber').click(function(){
	$('#operacao_conta_receber').attr('name', 'baixar_conta_receber');
	$('#descricao_baixar_hidden_conta_receber').val($('#descricao_baixar_conta_receber').val());

	if(($('#cancelar_nota_baixa_sim').is(':checked') || $('#cancelar_nota_baixa_nao').is(':checked')) && ($('#descricao_baixar_conta_receber').val() && $('#descricao_baixar_conta_receber').val() != '' )){
		if($('#cancelar_nota_baixa_sim').is(':checked')){
			$('#baixa_nota').val('1');
		}else{
			$('#baixa_nota').val('0');
		}

		var cont =0;
		$("[name ='selecionar_conta_receber[]']").each(function(){
	        if ($(this).is(':checked')){
	        	cont = cont+1;
	        }
	    });
	    if(!confirm('Dar baixa em '+cont+' conta(s) a receber?')) {
			return false;
		}else{
	    	$('#controle_conta_receber').submit();
		}
	}else{
	    alert('O campo de descrição e de cancelamento de notas devem estar preenchidos (selecionados)!');
	}

	

});

/*$('#submit_baixar_conta_receber_nao').click(function(){
	$('#operacao_conta_receber').attr('name', 'baixar_conta_receber');
	$('#baixa_nota').val('0');

	var cont =0;
	$("[name ='selecionar_conta_receber[]']").each(function(){
        if ($(this).is(':checked')){
        	cont = cont+1;
        }
    });
    if(!confirm('Dar baixa em '+cont+' conta(s) a receber?')) {
		return false;
	}else{
    	$('#controle_conta_receber').submit();
	}
});*/

$('#submit_conta_receber').click(function(){
	
	if($('input[name="data_pagamento_conta_receber"]').val() && $('input[name="data_pagamento_conta_receber"]').val() != ''){
		$('#data_pagamento_hidden_conta_receber').val($('input[name="data_pagamento_conta_receber"]').val());

		var cont =0;
		$("[name ='selecionar_conta_receber[]']").each(function(){
	        if ($(this).is(':checked')){
	        	cont = cont+1;
	        }
	    });
	    if(!confirm('Quitar '+cont+' conta(s) a receber?')) {
			return false;
		}else{
	    	$('#controle_conta_receber').submit();
		}
	}else{
		alert('Insira uma data!');
		return false;
	}
});



/*$(document).on('click', '#baixar_conta_receber', function(){
	$('#operacao_conta_receber').attr('name', 'baixar_conta_receber');

	var cont =0;
	$("[name ='selecionar_conta_receber[]']").each(function(){
        if ($(this).is(':checked')){
        	cont = cont+1;
        }
    });
    if(!confirm('Dar baixa em '+cont+' conta(s) a receber?')) {
		return false;
	} 
});*/

$(document).on('click', '#quitar_conta_receber', function(){
	configuraDatepicker();
    configuraMascaras();

	$('#operacao_conta_receber').attr('name', 'quitar_conta_receber');

});

$(document).on('click', '#enviar_email_conta_receber', function(){
	var cont = 0;
	$("[name ='selecionar_conta_receber[]']").each(function(){
		if ($(this).is(':checked')){
			if($(this).parent().parent().hasClass("obs_sim")){
				cont = 1;
			}
		}
	});

	if(cont == 1){
		alert("Você selecionou uma ou mais contas a receber com observações!");
		return false;

	}else{
		$('#operacao_conta_receber').attr('name', 'enviar_email_conta_receber');
		$('#controle_conta_receber').attr('action', "/v2//api/iframe?token=<?php echo $request->token ?>&view=conta-receber-enviar-email-form").submit();
	}

});

$(document).on('click', '#checkTodosReceber', function () {
	if ($(this).is(':checked')){
		if($('[name ="selecionar_conta_receber[]"]').parent().parent().hasClass('warning')){
			$("#baixar_conta_receber").attr("disabled", false);
			$("#quitar_conta_receber").attr("disabled", true);
			$("#enviar_email_conta_receber").attr("disabled", true);
			$("#clonar_conta_receber").attr("disabled", true);
		}else{
			$("#baixar_conta_receber").attr("disabled", false);
			$("#quitar_conta_receber").attr("disabled", false);
			$("#enviar_email_conta_receber").attr("disabled", false);
			$("#clonar_conta_receber").attr("disabled", true);
		}
		$('[name ="selecionar_conta_receber[]"]').prop("checked", true);
	}else{
    	$("#baixar_conta_receber").attr("disabled", true);
    	$("#quitar_conta_receber").attr("disabled", true);
    	$("#enviar_email_conta_receber").attr("disabled", true);
		$("#clonar_conta_receber").attr("disabled", true);
        $('[name ="selecionar_conta_receber[]"]').prop("checked", false);
    }
});

$(document).on('click', '[name ="selecionar_conta_receber[]"]', function(){
	var cont =0;
	var nao =0;
	var nao_clona =0;

	$("[name ='selecionar_conta_receber[]']").each(function(){
        if ($(this).is(':checked')){
			if($(this).attr("aceita-clona") == 'nao'){
				nao_clona ++;
			}

        	cont = cont+1;
			if($(this).parent().parent().hasClass('warning')){
				nao ++;		
			}
        }
    });
	if(nao != 0){
		if(cont == 1){
			if(nao_clona == 0){
				$("#clonar_conta_receber").attr("disabled", false);
			}else{
				$("#clonar_conta_receber").attr("disabled", true);
			}
		}else{
			$("#clonar_conta_receber").attr("disabled", true);
		}
		$("#baixar_conta_receber").attr("disabled", false);
    	$("#quitar_conta_receber").attr("disabled", true);
		$("#enviar_email_conta_receber").attr("disabled", true);

	}else{
		if(cont == 1){
			$("#baixar_conta_receber").attr("disabled", false);
			$("#quitar_conta_receber").attr("disabled", false);
			$("#enviar_email_conta_receber").attr("disabled", false);
			if(nao_clona == 0){
				$("#clonar_conta_receber").attr("disabled", false);
			}else{
				$("#clonar_conta_receber").attr("disabled", true);
			}
		}else if(cont > 1){
			$("#baixar_conta_receber").attr("disabled", false);
			$("#quitar_conta_receber").attr("disabled", false);
			$("#enviar_email_conta_receber").attr("disabled", false);
			$("#clonar_conta_receber").attr("disabled", true);
		}else{
			$("#baixar_conta_receber").attr("disabled", true);
			$("#quitar_conta_receber").attr("disabled", true);
			$("#enviar_email_conta_receber").attr("disabled", true);
			$("#clonar_conta_receber").attr("disabled", true);
		}
	}
	

});

$(document).on('click', '#tr_id_conta_receber', function(){
	
	var id_conta_receber = $(this).find("[name ='td_nome_conta_receber[]']").text()
	var antigo_tabela = $('#id_antigo_tabela_conta_receber').val();

	$("[name ='td_nome_conta_receber[]']").each(function(){

        if($(this).text() == id_conta_receber){
			$(this).parent().removeClass('default');
			$(this).parent().addClass('info');
		}else if($(this).text() == antigo_tabela){
			$(this).parent().removeClass('info');
			$(this).parent().addClass('default');
		}
    });
    $('#id_antigo_tabela_conta_receber').val(id_conta_receber);
});

</script>