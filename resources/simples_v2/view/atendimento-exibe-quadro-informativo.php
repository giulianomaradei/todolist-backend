
<style>
		.linha-tabela-sistema-gestao{
			margin-left: 2px;
			margin-right: 2px;
		}
		.tabela-sistema-gestao{
			border: 1px solid #ddd;
			padding: 8px;
		}
		.alerta-horario{
			border: 1px solid #ddd;
			padding-top: 5px;
			padding-bottom: 5px;
		}
		.alerta-observacao{
			padding-bottom: 10px;
		}
		.table{
			margin-bottom: 0px;
		}

    .conteudo-editor img{
        max-width: 100% !important;
        max-height: 100% !important;
    }


	.well{
		margin: 0 !important;
	}

	.dados-assinante div p{
		margin-top: 8px !important;
		margin-left: 10px !important;
	}

</style>
            
            <?php
				//Esse bloco verifica somente se existe integração sem verifica qual é a integração por que ele vai existir independente do sistema que estiver implementando, atendimentos e ordens de serviço aparecerão sempre neste padrão, alterando somente seu conteúdo interno.
				$temIntegracao = DBRead('', 'tb_integracao_contrato', "WHERE id_contrato_plano_pessoa = '".$id_contrato_plano_pessoa."'");
				if($temIntegracao):
				?>
				<!-- Bloco Integração -->
                <!--Informações-->
                <div class="panel panel-info">
                    <div class="panel-heading clearfix">

                        <h3 class="panel-title text-left pull-left" style="margin-top: 2px;">Informações do cliente (Sistema de gestão):</h3>
                        <div class="panel-title text-right pull-right"><button data-toggle="collapse" data-target="#informacoes_cliente" class="btn-integra btn btn-xs btn-info" type="button" title="Informações do cliente" id="informacoes_cliente_integracao"><i id="i_collapse" class="fa fa-plus"></i></button></div>

                    </div>

                    <div class="panel-collapse collapse" id="informacoes_cliente">
                        <div class="panel-body">
                            <div class="painel-informacoes-cliente" style="margin-bottom: 0;"></div>
                            
                            <div class="panel-atendimentos-sg" style="margin-top: 10px;">
                                <div style="margin-bottom: 10px;">
                                    <label>Atendimentos no sistema de gestão:</label>
                                    <select class="form-control" id="escolha-status">
                                        <option value="abertos">Abertos</option>
                                        <option value="fechados">Fechados</option>
                                    </select>
                                </div>

                                <div class="painel-informacoes-atendimento"></div>

                                <div class="painel-informacoes-os"></div>

                                <div class="painel-historico-conexao"></div>
                            </div>
                        </div>

                    </div>
                </div>
                <!-- Bloco Integração -->
				<?php
                endif;
                ?>            

            <div class="panel panel-info painel-quadro-informativo">
                <?php
                	//Dados empresa
				    // $empresa = DBRead('', 'tb_contrato_plano_pessoa a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa where a.id_contrato_plano_pessoa = '".$id_contrato_plano_pessoa."'");
				?>
            	<div class="panel-heading clearfix">
            		<div class="row">
	                    <h3 class="panel-title pull-left col-md-12">Informações:</h3>
	                </div>
                </div>
                <div class="panel-body" style="padding-bottom: 0;">
					<?php
 
					$dadosComplementos = DBRead('', 'tb_arvore a', "INNER JOIN tb_arvore_contrato b ON a.id_arvore = b.id_arvore WHERE a.id_arvore = '$id_arvore' AND b.id_contrato_plano_pessoa = '".$id_contrato_plano_pessoa."'", "a.quadro_informativo");

					//Informações de registro
						$dados_informacao_geral = DBRead('', 'tb_informacao_geral_contrato a', "WHERE id_contrato_plano_pessoa = '".$id_contrato_plano_pessoa."' LIMIT 1");

						if(
							($dados_informacao_geral[0]['confirmacao_cadastro_cliente'] && preg_match('/\bconfirmacao_cadastro_cliente\b/i', $dadosComplementos[0]['quadro_informativo'])) ||
							($dados_informacao_geral[0]['acesso_controladoras_bool'] != 2 && preg_match('/\bacesso_controladoras\b/i', $dadosComplementos[0]['quadro_informativo'])) ||
							($dados_informacao_geral[0]['nao_cliente'] && preg_match('/\bnao_cliente\b/i', $dadosComplementos[0]['quadro_informativo'])) ||
							($dados_informacao_geral[0]['classificacao_atendimento_sistema_gestao'] && preg_match('/\bclassificacao_atendimento_sistema_gestao\b/i', $dadosComplementos[0]['quadro_informativo'])) ||
							($dados_informacao_geral[0]['selecao_finalizacao_sistema_gestao'] && preg_match('/\bselecao_finalizacao_sistema\b/i', $dadosComplementos[0]['quadro_informativo'])) ||
							($dados_informacao_geral[0]['tipo_os'] && preg_match('/\btipo_os\b/i', $dadosComplementos[0]['quadro_informativo'])) ||
							($dados_informacao_geral[0]['suporte_acesso_lento'] && preg_match('/\bsuporte_acesso_lento\b/i', $dadosComplementos[0]['quadro_informativo'])) ||
							($dados_informacao_geral[0]['informacoes_adicionais'] && preg_match('/\binformacoes_adicionais\b/i', $dadosComplementos[0]['quadro_informativo'])) ||
							($dados_informacao_geral[0]['inativo_cancelado'] && preg_match('/\binativo_cancelado\b/i', $dadosComplementos[0]['quadro_informativo']))
						):

						?> 

						<div class="panel panel-warning">
					    <div class="panel-heading">
					      <h4 class="panel-title"><strong>Informações de registro</strong></h4>
					    </div>
					    	<div class="panel-body">
					        <?php
									$booleanos = array(
										"1" => "Sim",
										"0" => "Não",
										"" => "Não"
									);
									
									echo "<ul class='list-group'>";
									if(preg_match('/\bconfirmacao_cadastro_cliente\b/i', $dadosComplementos[0]['quadro_informativo'])){
										if($dados_informacao_geral[0]['confirmacao_cadastro_cliente']){
											echo "<li class='list-group-item'>";
												echo "<div class=\"row\">";
													echo "<div class='col-md-4'>";
														echo "<strong><span>Confirmação de cadastro de cliente</span></strong>";
													echo "</div>";
													echo "<div class='col-md-8'>";
														echo "<span>" . nl2br($dados_informacao_geral[0]['confirmacao_cadastro_cliente']) . "</span>";
													echo "</div>";
												echo "</div>";
											echo "</li>";
										}
									}

									if(preg_match('/\bacesso_controladoras\b/i', $dadosComplementos[0]['quadro_informativo'])){
										if($dados_informacao_geral[0]['acesso_controladoras_bool'] != 2){
											echo "<li class='list-group-item'>";
												echo "<div class=\"row\">";
													echo "<div class='col-md-4'>";
														echo "<strong><span>Sistema informa se o cliente está logado</span></strong>";
													echo "</div>";
														echo "<div class='col-md-1'>";
															echo "<span>" . $booleanos[$dados_informacao_geral[0]['acesso_controladoras_bool']] . "</span>";
														echo "</div>";
														echo "<div class='col-md-7'>";
															echo "<span>" . nl2br($dados_informacao_geral[0]['acesso_controladoras']) . "</span>";
														echo "</div>";
												echo "</div>";
											echo "</li>";
										}
									}

									if(preg_match('/\bnao_cliente\b/i', $dadosComplementos[0]['quadro_informativo'])){
										if($dados_informacao_geral[0]['nao_cliente']){
											echo "<li class='list-group-item'>";
												echo "<div class=\"row\">";
													echo "<div class='col-md-4'>";
														echo "<strong><span>Não clientes</span></strong>";
													echo "</div>";
													echo "<div class='col-md-8'>";
														echo "<span>" . nl2br($dados_informacao_geral[0]['nao_cliente']) . "</span>";
													echo "</div>";
												echo "</div>";
											echo "</li>";
										}
									}

									if(preg_match('/\bclassificacao_atendimento_sistema_gestao\b/i', $dadosComplementos[0]['quadro_informativo'])){
										if($dados_informacao_geral[0]['classificacao_atendimento_sistema_gestao']){
											echo "<li class='list-group-item'>";
												echo "<div class=\"row\">";
													echo "<div class='col-md-4'>";
														echo "<strong><span>Classificação de atendimento no sistema de gestão</span></strong>";
													echo "</div>";
													echo "<div class='col-md-8'>";
														echo "<span>" . nl2br($dados_informacao_geral[0]['classificacao_atendimento_sistema_gestao']) . "</span>";
													echo "</div>";
												echo "</div>";
											echo "</li>";
										}
									}
									
									if(preg_match('/\bselecao_finalizacao_sistema\b/i', $dadosComplementos[0]['quadro_informativo'])){
										if($dados_informacao_geral[0]['selecao_finalizacao_sistema_gestao']){
											echo "<li class='list-group-item'>";
												echo "<div class=\"row\">";
													echo "<div class='col-md-4'>";
														echo "<strong><span>Seleção de finalização no sistema de gestão</span></strong>";
													echo "</div>";
													echo "<div class='col-md-8'>";
														echo "<span>" . nl2br($dados_informacao_geral[0]['selecao_finalizacao_sistema_gestao']) . "</span>";
													echo "</div>";
												echo "</div>";
											echo "</li>";
										}
									}

									if(preg_match('/\binformacoes_adicionais\b/i', $dadosComplementos[0]['quadro_informativo'])){

										if($dados_informacao_geral[0]['informacoes_adicionais']){
											echo "<li class='list-group-item'>";
												echo "<div class=\"row\">";
													echo "<div class='col-md-4'>";
														echo "<strong><span>Informações adicionais</span></strong>";
													echo "</div>";

													echo "<div class='col-md-8'>";
														echo "<span>" . nl2br($dados_informacao_geral[0]['informacoes_adicionais']) . "</span>";
													echo "</div>";
												echo "</div>";
											echo "</li>";
										}
									}

									if(preg_match('/\btipo_os\b/i', $dadosComplementos[0]['quadro_informativo'])){
										if($dados_informacao_geral[0]['tipo_os']){
											echo "<li class='list-group-item'>";
												echo "<div class=\"row\">";
													echo "<div class='col-md-4'>";
														echo "<strong><span>Tipo de O.S</span></strong>";
													echo "</div>";
													echo "<div class='col-md-8'>";
														echo "<span>" . nl2br($dados_informacao_geral[0]['tipo_os']) . "</span>";
													echo "</div>";
												echo "</div>";
											echo "</li>";
										}
									}
									
									if(preg_match('/\bsuporte_acesso_lento\b/i', $dadosComplementos[0]['quadro_informativo'])){
										if($dados_informacao_geral[0]['suporte_acesso_lento']){
											echo "<li class='list-group-item'>";
												echo "<div class=\"row\">";
													echo "<div class='col-md-4'>";
														echo "<strong><span>Suporte a acesso lento</span></strong>";
													echo "</div>";
													echo "<div class='col-md-8'>";
														echo "<span>" . nl2br($dados_informacao_geral[0]['suporte_acesso_lento']) . "</span>";
													echo "</div>";
												echo "</div>";
											echo "</li>";
										}
									}

									if(preg_match('/\binativo_cancelado\b/i', $dadosComplementos[0]['quadro_informativo'])){
										if($dados_informacao_geral[0]['inativo_cancelado']){
											echo "<li class='list-group-item'>";
												echo "<div class=\"row\">";
													echo "<div class='col-md-4'>";
														echo "<strong><span>Cadastro inativo/cancelado</span></strong>";
													echo "</div>";
													echo "<div class='col-md-8'>";
														echo "<span>" . nl2br($dados_informacao_geral[0]['inativo_cancelado']) . "</span>";
													echo "</div>";
												echo "</div>";
											echo "</li>";
										}
									}

									echo "</ul>";
						    ?>
					    	</div>
					  </div>

					  <?php
					  echo "<br>";

					  endif; //fim do if(dados)
					  //################## Informações de registro
					 
					  if(preg_match('/\bacessos_equipamentos\b/i', $dadosComplementos[0]['quadro_informativo'])):

                	  //Acesso a equipamentos
					    $dados = DBRead('', 'tb_equipamento_contrato a', "INNER JOIN tb_equipamento_acesso b ON a.id_equipamento_contrato = b.id_equipamento_contrato WHERE a.id_contrato_plano_pessoa = '".$id_contrato_plano_pessoa."'");
					    if($dados):
                		?>
					  <div class="panel panel-info">
					    <div class="panel-heading">
					      <h4 class="panel-title"><strong>Acessos a equipamentos</strong></h4>
					    </div>
					    <div class="panel" role="tabpanel" aria-labelledby="headingOne">
					      <div class="panel-body">
					      	<?php
					      		echo "<table class='table table-bordered'>";
								echo "<tbody>";
								if($dados[0]['observacao']){
									echo "<div class='alerta-observacao'><strong>Observação: </strong><span>" . $dados[0]['observacao'] . "</span></div>";
								}	

								//COMENTAR EM REUNIAO	
								if($dados[1]['usuario']){
									$cont = 0;
										foreach($dados as $dado){
											$cont++;
											echo "<tr>";
												echo "<td><span><strong>Usuário (".$cont.") : </strong>" . $dado['usuario'] . "</span></td>";
												echo "<td><span><strong>Senha (".$cont.") : </strong>" . $dado['senha'] . "</span></td>";
											echo "</tr>";
										}
									echo "</tbody>";
								echo "</table>";
								}else{
									echo "<tr>";
											echo "<td><span><strong>Usuário: </strong>" . $dados[0]['usuario'] . "</span></td>";
											echo "<td><span><strong>Senha: </strong>" . $dados[0]['senha'] . "</span></td>";
										echo "</tr>";
									echo "</tbody>";
								echo "</table>";
								}
					      	?>
					      </div>
					    </div>
					  </div>
					<?php
					echo "<br>";
					endif; //fim if(dados)

					endif; //Fim do if(preg_match('/\bequipamento_contrato\b/i', $dadosComplementos[0]['quadro_informativo']))
					//######## Acesso equipamentos

					if(preg_match('/\bsinais_equipamentos\b/i', $dadosComplementos[0]['quadro_informativo'])):

						// Sinal
	
						  $dados = DBRead('', 'tb_sinal_equipamento_contrato a', "INNER JOIN tb_sinal_equipamento b ON a.id_sinal_equipamento_contrato = b.id_sinal_equipamento_contrato WHERE a.id_contrato_plano_pessoa = '".$id_contrato_plano_pessoa."'", "b.nome, b.intensidade_sinal");
						  if($dados):
						  ?>
						  <div class="panel panel-warning">
							<div class="panel-heading">
							  <h4 class="panel-title"><strong><span>Sinais dos equipamentos</span></strong></h4>
							</div>
								<div class="panel-body">
									<?php
										echo "<table class='table table-bordered'>";
											  echo "<thead>";
												  echo "<tr>";
													  echo "<th class='col-md-4'><span>Nome</span></th>";
													  echo "<th><span>Intensidade do sinal</span></th>";
												  echo "</tr>";
											  echo "</thead>";
											  echo "<tbody>";
												  foreach($dados as $dado){
												  echo "<tr>";
													  echo "<td><span> " . nl2br($dado['nome']) . "</span></td>";
													  echo "<td><span> " . nl2br($dado['intensidade_sinal']) . "</span></td>";
												  echo "</tr>";
												  }
											  echo "</tbody>";
										  echo "</table>";
									  ?>
								</div>
						  </div>
						<?php 
						echo "<br>";
	
						endif; //if(dados)
	
						endif; //fim if(preg_match('/\bradio_sinal_contrato\b/i', $dadosComplementos[0]['quadro_informativo']))
						//################## Sinal

					if(preg_match('/\bconexoes_cabos\b/i', $dadosComplementos[0]['quadro_informativo'])):
					
					// Configuração de roteadores
					$dados = DBRead('', 'tb_configuracao_roteadores_contrato a', "INNER JOIN tb_configuracao_roteadores b ON a.id_configuracao_roteadores_contrato = b.id_configuracao_roteadores_contrato WHERE a.id_contrato_plano_pessoa = '".$id_contrato_plano_pessoa."'", "a.observacao, b.nome, b.configuracao");
					if($dados):
					?>
					  <div class="panel panel-success">
					    <div class="panel-heading">
					      <h4 class="panel-title"><strong>Conexões de cabos</strong></h4>
					    </div>
					    	<div class="panel-body">
					    		<?php
								if($dados[0]['observacao']){
									echo "<div class='alerta-observacao'><strong>Observação: </strong><span>" . $dados[0]['observacao'] . "</span></div>";
								}
								echo "<table class='table table-bordered'>";
									echo "<thead>";
										echo "<tr>";
											echo "<th><span>Nome</span></th>";
											echo "<th><span>Configuração</span></th>";
										echo "</tr>";
									echo "</thead>";
									echo "<tbody>";
										foreach($dados as $dado){
										echo "<tr>";
											echo "<td><span>" . nl2br($dado['nome']) . "</span></td>";
											echo "<td><span>" . nl2br($dado['configuracao']) . "</span></td>";
										echo "</tr>";
										}
									echo "</tbody>";
								echo "</table>";
						      	?>
					    	</div>
					    </div>
					  <?php
					echo "<br>";
					endif; //fim if(dados)

					endif; //Fim if(preg_match('/\bconfiguracao_roteadores_contrato\b/i', $dadosComplementos[0]['quadro_informativo']))
					//######### Configuração de roteadores

					//Situações adversas
					$dados_informacao_geral_contrato = DBRead('', 'tb_informacao_geral_contrato', "WHERE id_contrato_plano_pessoa = '".$id_contrato_plano_pessoa."'", "situacoes_adversas");

					if(preg_match('/\bsituacao\b/i', $dadosComplementos[0]['quadro_informativo']) && $dados_informacao_geral_contrato[0]['situacoes_adversas'] != ""):
					
					if($dados_informacao_geral_contrato):
					?>
					  <div class="panel panel-success">
					    <div class="panel-heading">
					      <h4 class="panel-title"><strong>Situações adversas</strong></h4>
					    </div>
					    	<div class="panel-body">
					    		<?php
								echo "<table class='table table-bordered'>";
									echo "<thead>";
										echo "<tr>";
											echo "<th><span>Descrição</span></th>";
										echo "</tr>";
									echo "</thead>";
									echo "<tbody>";
										foreach($dados_informacao_geral_contrato as $dado){
										echo "<tr>";
											echo "<td><span>" . nl2br($dado['situacoes_adversas']) . "</span></td>";
										echo "</tr>";
										}
									echo "</tbody>";
								echo "</table>";	
						      	?>
					    	</div>
					    </div>
					  <?php
					echo "<br>";
					endif; //fim if(dados)

					endif; //Fim if(preg_match('/\bsituacao\b/i', $dadosComplementos[0]['quadro_informativo']))
					//######### Configuração de roteadores
					
				    if(preg_match('/\breinicio_equipamento\b/i', $dadosComplementos[0]['quadro_informativo'])):

					//Reiniciar equipamentos
					  $dados = DBRead('', 'tb_reinicio_equipamento_contrato a', "INNER JOIN tb_reinicio_equipamento b ON a.id_reinicio_equipamento_contrato = b.id_reinicio_equipamento_contrato INNER JOIN tb_tipo_equipamento c ON b.equipamento = c.id_tipo_equipamento WHERE a.id_contrato_plano_pessoa = '".$id_contrato_plano_pessoa."'", "b.tempo, b.observacao, c.descricao AS descricao_equipamento");
						if($dados):
					  ?>
					  <div class="panel panel-info">
					    <div class="panel-heading">
					      <h4 class="panel-title"><strong><span>Tempo de reinicio dos equipamentos</span></strong></h4>
					    </div>
					    	<div class="panel-body">
					    		<?php
					    		echo "<table class='table table-bordered'>";
						      			echo "<thead>";
						      				echo "<tr>";
						      					echo "<th class='col-md-3'><span>Equipamento</span></th>";
						      					echo "<th class='col-md-2'><span>Tempo</span></th>";
						      					echo "<th><span>Observação</span></th>";
						      				echo "</tr>";
						      			echo "</thead>";
						      			echo "<tbody>";
						      			foreach($dados as $dado){
						      				echo "<tr>";
						      					echo "<td><span> " . $dado['descricao_equipamento'] . "</span></td>";
						      					echo "<td><span> " .converteSegundosHoras($dado['tempo'])."</span></td>";
						      					echo "<td><span> " . nl2br($dado['observacao']) . "</span></td>";
						      				echo "</tr>";
						      			}
						      		echo "</tbody>";
						      	echo "</table>";
					    		?>
					    	</div>
					  </div>
					  <?php
					  echo "<br>";
					endif; //fim if(dados)

					endif; //fim if(preg_match('/\breinicio_equipamento_contrato\b/i', $dadosComplementos[0]['quadro_informativo']))
					//################### Reiniciar equipamentos

					if(preg_match('/\bvelocidade_minimas_encaminhamento\b/i', $dadosComplementos[0]['quadro_informativo'])):
					 
					//Velocidade minima
					  $dados = DBRead('', 'tb_velocidade_minima_encaminhar_contrato a', "INNER JOIN tb_velocidade_minima_encaminhar b ON a.id_velocidade_minima_encaminhar_contrato = b.id_velocidade_minima_encaminhar_contrato WHERE a.id_contrato_plano_pessoa = '".$id_contrato_plano_pessoa."'");
						if($dados):
					  ?>
					  <div class="panel panel-success">
					    <div class="panel-heading">
					      <h4 class="panel-title"><strong><span>Velocidades mínimas para o encaminhamento</span></strong></h4>
					    </div>
					    	<div class="panel-body">
					    		<?php
					    		if($dados[0]['observacao']){
									echo "<div class='alerta-observacao'><strong>Observação: </strong><span>" . $dados[0]['observacao'] . "</span></div>";
								}
					    		echo "<table class='table table-bordered'>";
						      			echo "<thead>";
						      				echo "<tr>";
						      					echo "<th><span>Tipo de equipamento</span></th>";
						      					echo "<th><span>Tipo de cliente</span></th>";
						      					echo "<th><span>Porcentagem(%)</span></th>";
						      				echo "</tr>";
						      			echo "</thead>";
						      			echo "<tbody>";
						      			foreach($dados as $dado){

											$tipo_equipamento = DBRead('', 'tb_tipo_equipamento', "WHERE id_tipo_equipamento = '".$dado['tipo_equipamento']."'");

						      				$id_tipo_cliente = $dado['id_tipo_plano_cliente'];
						      				$dados_tipo_cliente = DBRead('', 'tb_tipo_plano_cliente', "WHERE id_tipo_plano_cliente = '$id_tipo_cliente' LIMIT 1");
						      				$descricao = $dados_tipo_cliente[0]['descricao'];
						      				echo "<tr>";
						      					echo "<td><span> " . $tipo_equipamento[0]['descricao'] . "</span></td>";
						      					echo "<td><span> " . $descricao . "</span></td>";
						      					echo "<td><span> " . $dado['porcentagem'] . "%" . "</span></td>";
						      				echo "</tr>";
						      			}
						      		echo "</tbody>";
						      	echo "</table>";
					    		?>
					    	</div>
					  </div>
					  <?php
					  echo "<br>";
					  endif; //fim if(dados)

					  endif; //fim if(preg_match('/\bvelocidade_minima_encaminhar_contrato\b/i', $dadosComplementos[0]['quadro_informativo']))
						//###########Velocidade minima
						

						if(preg_match('/\bplantonista\b/i', $dadosComplementos[0]['quadro_informativo'])):

							// Plantonistas
							$dados = DBRead('', 'tb_plantonista_contrato', "where id_contrato_plano_pessoa = '".$id_contrato_plano_pessoa."' LIMIT 1", "tabela");
							if($dados):
							?>

							<div class="panel panel-info">
								<div class="panel-heading">
									<h4 class="panel-title"><strong><span>Plantonistas</span></strong></h4>
								</div>
									<div class="panel-body">
										<?php
										
										echo "<div class='row linha-tabela-localizacao'>";
											echo "<div class='col-md-12 tabela-localizacao conteudo-editor'>";
												echo $dados[0]['tabela'];
											echo "</div>";
												echo "</div>";
											?>
									</div>
							</div>

								<?php 
								echo "<br>";
							endif;  //fim if(dados)

						endif; //if(preg_match('/\bplantonista_contrato\b/i', $dadosComplementos[0]['quadro_informativo']))
						//############### Plantonistas 

					  
					  if(preg_match('/\bprazos_retorno\b/i', $dadosComplementos[0]['quadro_informativo'])):
					
					  	// Prazo retorno
					  	$dados_contrato = DBRead('', 'tb_prazo_retorno_contrato', "WHERE id_contrato_plano_pessoa = '".$id_contrato_plano_pessoa."'");
					  	if($dados_contrato):
							?>
							<div class="panel panel-warning">
									<div class="panel-heading">
										<h4 class="panel-title"><strong><span>Prazos de retorno <?=$tipo?></span></strong></h4>
									</div>
										<div class="panel-body">
							<?php

						foreach($dados_contrato as $key => $dado):

							$dados_prazos = DBRead('', 'tb_prazo_retorno', "WHERE id_prazo_retorno_contrato = " . $dados_contrato[$key]['id_prazo_retorno_contrato']);

							$tipo = '';
							if($dado['tipo'] == '1'){
								$tipo = "Suporte Técnico";
							}else if($dado['tipo'] == '2'){
								$tipo = "Suporte Comercial";
							}else if($dado['tipo'] == '3'){
								$tipo = "Suporte Financeiro";
							}
					
								$dado_tipo_tempo = array(
									"1" => "úteis",
									"2" => "corridas"
								);

								if($dado['observacao']){
									echo "<div class='alerta-observacao'><strong>Observação: </strong><span>" . $dado['observacao'] . "</span></div>";
								}

								echo "<table class='table table-bordered'>";
									echo "<thead>";
										echo "<tr>";
											echo "<th class='col-md-12' colspan='2'>Prazo de retorno para ".$tipo."</th>";
										echo "</tr>";
									echo "</thead>";
									echo "<tbody>";

										foreach($dados_prazos as $dado_prazo){

											$id_tipo_cliente = $dado_prazo['id_tipo_plano_cliente'];
											$dados_tipo_plano = DBRead('', 'tb_tipo_plano_cliente', "WHERE id_tipo_plano_cliente = '$id_tipo_cliente' LIMIT 1", "descricao");
											$descricao = $dados_tipo_plano[0]['descricao'];

											if ($dado_prazo['tempo'] == 0) {
												$legenda_tempo = " o mais breve possível dentro do horário de atendimento.";

											} else {
												$legenda_tempo = $dado_prazo['tempo']. " horas " . $dado_tipo_tempo[$dado_prazo['tipo_tempo']];
											}

											echo "<tr>";
												echo "<td class='col-md-4'><strong>Plano: </strong><span>" . $descricao . " => " . $legenda_tempo . "</span></td>";
												echo "<td class='col-md-8'><strong>Observação: </strong><span> " . $dado_prazo['observacao_prazo'] . "</span></td>";
											echo "</tr>";
										}
										
									echo "</tbody>";
								echo "</table>";
									
						echo "<br>";
						endforeach;
						?>
						</div>
					</div>
					<?php
				  endif;
					 
					  endif; //fim if(preg_match('/\bprazo_retorno_contrato\b/i', $dadosComplementos[0]['quadro_informativo']))
					  //########## Prazo retorno

					  if(preg_match('/\bplano_cliente\b/i', $dadosComplementos[0]['quadro_informativo'])):

					  // Plano cliente
					  $dados = DBRead('', 'tb_plano_cliente_contrato a', "INNER JOIN tb_plano_cliente b ON a.id_plano_cliente_contrato = b.id_plano_cliente_contrato WHERE a.id_contrato_plano_pessoa = '".$id_contrato_plano_pessoa."'");
						if($dados):
					  ?>

					  <div class="panel panel-success">
					    <div class="panel-heading">
					      <h4 class="panel-title"><strong><span>Planos</span></strong></h4>
					    </div>
					    	<div class="panel-body">

					      		<?php
									echo "<table class='table table-bordered'>";
						      			echo "<thead>";
						      				echo "<tr>";
						      					echo "<th class='col-md-2'><span>Download</span></th>";
						      					echo "<th class='col-md-2'><span>Upload</span></th>";
						      					echo "<th class='col-md-4'><span>Descrição do plano</span></th>";
						      					echo "<th class='col-md-4'><span>Observação</span></th>";
						      				echo "</tr>";
						      			echo "</thead>";
						      			echo "<tbody>";

						      				foreach($dados as $dado){

						      					$download = $dado['download'];
						      					$upload = $dado['upload'];
						      					$descricao = $dado['descricao'];
						      					$observacao = $dado['observacao'];

						      				echo "<tr>";
						      					echo "<td><span>" . $download . " Mbps</span></td>";
						      					echo "<td><span>" . $upload . " Mbps</span></td>";
						      					echo "<td><span>" . $descricao . "</span></td>";
						      					echo "<td><span>" . $observacao . "</span></td>";
						      				echo "</tr>";
						      				}

						      			echo "</tbody>";
						      		echo "</table>";
							    ?>
					    	</div>
					  </div>

					  <?php
					  echo "<br>";

					  endif; //fim if(dados)

					  endif; // fim(preg_match('/\bplano_cliente_contrato\b/i', $dadosComplementos[0]['quadro_informativo']))
					  //########## Plano cliente
					  
					  if(preg_match('/\bhorario\b/i', $dadosComplementos[0]['quadro_informativo'])):
					  // Horarios

						$dados_horario = DBRead('', 'tb_horario_contrato a', "INNER JOIN tb_horario b ON a.id_horario_contrato = b.id_horario_contrato WHERE a.id_contrato_plano_pessoa = '".$id_contrato_plano_pessoa."' AND a.tipo != '8'");

						$dados_informacao_geral_horario_monitoramento = DBRead('', 'tb_informacao_geral_contrato', "WHERE id_contrato_plano_pessoa = '".$id_contrato_plano_pessoa."' AND horarios_monitoramento != '' ");

						//$dados_informacao_geral = DBRead('', 'tb_informacao_geral_contrato', "WHERE id_contrato_plano_pessoa = '".$id_contrato_plano_pessoa."'");

						$tipo_horario = array(
							"1" => "Horários de funcionamento da empresa",
							"7" => "Horários gerais de atendimento dos técnicos de campo",
							"3" => "Horários de atendimento EMPRESARIAL dos técnicos de campo",
							"5" => "Horários de atendimento DOMÉSTICO dos técnicos de campo",
							"6" => "Horários de atendimento DEDICADO dos técnicos de campo",
							"9" => "Horários de atendimento via texto pela Belluno",
							"10" => "Horários de retorno telefônico do provedor"
						);
						$tipo_dia = array(
							"1" => "Seg. a Dom.",
							"2" => "Seg. a Sab.",
							"3" => "Seg. a Sex.",
							"4" => "Dom. e Feriados.",
							"5" => "Feriados.",
							"6" => "Domingo.",
							"7" => "Segunda.",
							"8" => "Terça.",
							"9" => "Quarta.",
							"10" => "Quinta.",
							"11" => "Sexta.",
							"12" => "Sabado.",
							"13" => "Seg. a Qui."
						);

						

						if($dados_horario || $dados_informacao_geral[0]['horarios_monitoramento']){

							echo "<div class='panel panel-info'>";
								echo "<div class='panel-heading'>";
									echo "<h4 class='panel-title'><strong><span>Horários</span></strong></h4>";
								echo "</div>";
							echo "<div class='panel-body'>";
							if($dados_horario){
								foreach($dados_horario as $key => $dado){
									
									// 6 - horario_atendimento_dedicado_tecnicos
									// 5 - horario_atendimento_domestico_tecnicos
									// 10 - horario_retorno_telefonico
									// 3 - horario_atendimento_empresarial_tecnicos
									// horario_monitoramento
									// 1 - horario_empresa_aberta
									// 9 - horario_atendimento_texto
									
									if(
										($dado['tipo'] == 1 && preg_match('/\bhorario_empresa_aberta/', $dadosComplementos[0]['quadro_informativo'])) || 
										($dado['tipo'] == 3 && preg_match('/\bhorario_atendimento_empresarial_tecnicos/', $dadosComplementos[0]['quadro_informativo'])) || 
										($dado['tipo'] == 5 && preg_match('/\bhorario_atendimento_domestico_tecnicos/', $dadosComplementos[0]['quadro_informativo'])) || 
										($dado['tipo'] == 6 && preg_match('/\bhorario_atendimento_dedicado_tecnicos/', $dadosComplementos[0]['quadro_informativo'])) || 
										($dado['tipo'] == 9 && preg_match('/\bhorario_atendimento_texto/', $dadosComplementos[0]['quadro_informativo'])) || 
										($dado['tipo'] == 10 && preg_match('/\bhorario_retorno_telefonico/', $dadosComplementos[0]['quadro_informativo'])) ||

										(($dado['tipo'] != 1 && $dado['tipo'] != 3 && $dado['tipo'] != 5 && $dado['tipo'] != 6 && $dado['tipo'] != 9 && $dado['tipo'] != 10) && preg_match('/\bhorario_monitoramento/', $dadosComplementos[0]['quadro_informativo']))

									){
										//Verifica se dois ou mais grupos de horários pertencem ao mesmo tipo e agrupa na mesma div e dá espaço entre tipos diferentes.
										if($tipo_horario[$dado['tipo']] != $auxTipoHorario){
											if($auxTipoHorario != ''){
												echo "</div><br>";
											}
											echo "<div class='row' style=\"margin-left: 0px; margin-right: 0px;\">";

											if($dado['observacao']){
												$obs =  " </br><strong>Observação:</strong> ".$dado['observacao']." ";
											}else{
												$obs = "";
											}
											
											$auxTipoHorario = $tipo_horario[$dado['tipo']];
											echo "<span style='margin-top: 10px; display: block;'><strong>" . $tipo_horario[$dado['tipo']] . "</strong>".$obs."</span>";
											echo "<div class='col-md-4 alerta-horario'>";
												echo '<strong>Dia:</strong><span> ' . $tipo_dia[$dado['dia']] . "</span>";
											echo "</div>";
											echo "<div class='col-md-4 alerta-horario'>";
												echo '<strong>Hora início:</strong><span> ' . substr($dado['hora_inicio'], 0, -3)  . "</span>";
											echo "</div>";
											echo "<div class='col-md-4 alerta-horario'>";
												echo '<strong>Hora fim:</strong><span> ' . substr($dado['hora_fim'], 0, -3) . "</span>";
											echo "</div>";
											
										}else{
											echo "<div class='col-md-4 alerta-horario'>";
												echo '<strong>Dia:</strong><span> ' . $tipo_dia[$dado['dia']] . "</span>";
											echo "</div>";
											echo "<div class='col-md-4 alerta-horario'>";
												echo '<strong>Hora início:</strong><span> ' . substr($dado['hora_inicio'],0,-3)  . "</span>";
											echo "</div>";
											echo "<div class='col-md-4 alerta-horario'>";
												echo '<strong>Hora fim:</strong><span> ' . substr($dado['hora_fim'],0,-3) . "</span>";
											echo "</div>";
										}
									}
								}
							}
							if($dados_horario){
								echo "</div><br>";
							}
							if($dados_informacao_geral_horario_monitoramento[0]['horarios_monitoramento']){
								echo "<div class='row' style=\"margin-left: 0px; margin-right: 0px;\">";
									echo "<div class='col-md-12 alerta-tipo alerta-horario'>";
										echo "<span><strong>Horários de monitoramento</strong></span>";
									echo "</div>";

									echo "<div class='col-md-12 alerta-horario'>";
										echo '<span>' . nl2br($dados_informacao_geral_horario_monitoramento[0]['horarios_monitoramento'])  . "</span>";
									echo "</div>";
								echo "</div>";
							}
							echo "</div>";

						echo "</div><br>";

						}
						
					
					

					endif; //fim if(preg_match('/\bhorario\b/i', $dadosComplementos[0]['quadro_informativo']))
					//############### Horarios
					
					if(preg_match('/\bura\b/i', $dadosComplementos[0]['quadro_informativo'])):

					 // URA
					  $dados = DBRead('', 'tb_ura a', "INNER JOIN tb_ura_contrato b ON a.id_ura_contrato = b.id_ura_contrato WHERE b.id_contrato_plano_pessoa = '".$id_contrato_plano_pessoa."'", "a.numero, a.descricao");

					  if($dados):
					  
					  ?>

					  <div class="panel panel-success">
					    <div class="panel-heading">
					      <h4 class="panel-title"><strong><span>URA's</span></strong></h4>
					    </div>
					    	<div class="panel-body">
					      
					    		<?php

					    		echo "<table class='table table-bordered'>";
						      			echo "<thead>";
						      				echo "<tr>";
						      					echo "<th class='col-md-3'><span>Número</span></th>";
						      					echo "<th><span>Descrição</span></th>";
						      				echo "</tr>";
						      			echo "</thead>";
						      			echo "<tbody>";

						      			foreach($dados as $dado){
						      				echo "<tr>";
						      					echo "<td><span>" . $dado['numero'] . "</span></td>";
						      					echo "<td><span> " . $dado['descricao'] . "</span></td>";
						      				echo "</tr>";
						      			}
						      		echo "</tbody>";
						      	echo "</table>";
					    		?>

					    	</div>
					  </div>

					  <?php

					endif; //fim if(dados)

					endif; //fim if(preg_match('/\bura_contrato\b/i', $dadosComplementos[0]['quadro_informativo']))


					if(preg_match('/\bnumero_retorno\b/i', $dadosComplementos[0]['quadro_informativo'])):

					 // Número Retorno
					  $dados = DBRead('', 'tb_parametros', "WHERE id_contrato_plano_pessoa = '".$id_contrato_plano_pessoa." LIMIT 1'", "ramal_retorno");
						if($dados[0]['ramal_retorno']):
					  
					  ?>

					  <div class="panel panel-success">
					    <div class="panel-heading">
					      <h4 class="panel-title"><strong><span>Número de retorno</span></strong></h4>
					    </div>
					    	<div class="panel-body">
					    		<?php
					    		echo "<table class='table table-bordered'>";
						      			echo "<thead>";
						      				echo "<tr>";
						      					echo "<th class='col-md-3'><span>Número</span></th>";
						      				echo "</tr>";
						      			echo "</thead>";
						      			echo "<tbody>";
						      				echo "<tr>";
						      					echo "<td><span>" . $dados[0]['ramal_retorno'] . "</span></td>";
						      				echo "</tr>";
						      		echo "</tbody>";
						      	echo "</table>";
					    		?>
					    	</div>
					  </div>
					  <?php

					endif; //fim if(dados)

					endif; //fim if(preg_match('/\bnumero_retorno\b/i', $dadosComplementos[0]['quadro_informativo']))


					//##################Horarios
					//Endereço

					if(preg_match('/\blocalizacoes\b/i', $dadosComplementos[0]['quadro_informativo'])):
					  	
					  $dados = DBRead('', 'tb_localizacao_contrato a', "INNER JOIN tb_localizacao b ON a.id_localizacao_contrato = b.id_localizacao_contrato where a.id_contrato_plano_pessoa = '".$id_contrato_plano_pessoa."'");

					  if($dados):

					  ?>
					  <div class="panel panel-warning">
					    <div class="panel-heading">
					      <h4 class="panel-title"><strong>Dados da empresa</strong></h4>
					    </div>
					    <div class="panel" role="tabpanel" aria-labelledby="headingOne">
					      <div class="panel-body">

					      	<?php

					      		foreach($dados as $dado_localizacao):

					      		$nome_cidade = DBRead('', 'tb_cidade a', "INNER JOIN tb_estado b ON a.id_estado = b.id_estado WHERE a.id_cidade = '".$dado_localizacao['id_cidade']."'", "a.nome AS nome_cidade, b.nome AS nome_estado");

					      		$lat = $dado_localizacao['latitude'];
					      		$lon = $dado_localizacao['longitude'];

					      		echo "<table class='table table-bordered' style='margin-top: 10px'>";
					      			echo "<tbody>";
					      				echo "<tr>";
					      					echo "<td><span><strong>Endereço: </strong>Cidade: ".$nome_cidade[0]['nome_cidade']." / ".$nome_cidade[0]['nome_estado']." - ".$dado_localizacao['endereco']."</span><a target='_blank' class='btn btn-default btn-sm' style='float: right'  href='/api/iframe?token=".$request->token.">&view=exibe-mapa-localizacao-form&contrato=$id_contrato_plano_pessoa&lat=$lat&lon=$lon'>Exibir no mapa <i class='fa fa-map-marker' aria-hidden='true'></i></a></td>";
					      				echo "</tr>";
					      				if($dados[0]['ponto_referencia']){
					      					echo "<tr>";
						      					echo "<td><span><strong>Ponto de referência: </strong>" .$dado_localizacao['ponto_referencia'] ."</span></td>";
						      				echo "</tr>";
					      				}

					      				$dados_telefone = DBRead('', 'tb_localizacao_telefone', "WHERE id_localizacao = ".$dado_localizacao['id_localizacao']."");
					      				if($dados_telefone){
						      				foreach ($dados_telefone as $dado_telefone) {
						      					echo "<tr>";
										      		echo "<td><span><strong>Número de Contato: </strong>".$dado_telefone['telefone']."   ".$dado_telefone['observacao']."</span></td>";
										      	echo "</tr>";
						      				}
					      				}

					      				if($dados[0]['observacao']){
					      					echo "<tr>";
							      				echo "<td><span><strong>Obs Localização: </strong>" . $dado_localizacao['observacao'] . "</span></td>";
							      			echo "</tr>";
					      				}
					      			echo "</tbody>";
					      		echo "</table>";

					      		endforeach;
					      	?>
					      </div>
					    </div>
					  </div>

					<?php 
					 
					endif; //fim if(dados)
				    echo "<br>";
					endif; //fim if(preg_match('/\blocalizacao_contrato\b/i', $dadosComplementos[0]['quadro_informativo']))
					
					//Financeiro
					  $dados_financeiro = DBRead('', 'tb_informacao_geral_contrato', "WHERE id_contrato_plano_pessoa = '".$id_contrato_plano_pessoa."'");
					  if($dados_financeiro):

					  if(preg_match('/\bbloqueados\b/i', $dadosComplementos[0]['quadro_informativo']) && $dados_financeiro[0]['bloqueados'] != "" ||
					  preg_match('/\bcancelamento\b/i', $dadosComplementos[0]['quadro_informativo']) && $dados[0]['cancelamentos'] ||
					  preg_match('/\bsegunda_via\b/i', $dadosComplementos[0]['quadro_informativo']) && $dados_financeiro[0]['segunda_via'] != "" ||
					  preg_match('/\bdescontos\b/i', $dadosComplementos[0]['quadro_informativo']) && $dados_financeiro[0]['descontos'] != ""){
					  ?>
						<div class="panel panel-info">
					    <div class="panel-heading">
					      <h4 class="panel-title"><strong>Financeiro</strong></h4>
					    </div>
					    	<div class="panel-body">
					    		<?php
									$booleanos = array(
										"1" => "Sim",
										"0" => "Não",
										"" => "Não"
									);

									foreach($dados_financeiro as $dado){

										if(preg_match('/\bbloqueados\b/i', $dadosComplementos[0]['quadro_informativo'])){
											if($dado['bloqueados']){
												echo "<li class='list-group-item'>";
													echo "<div class=\"row\">";
														echo "<div class='col-md-4'>";
															echo "<strong><span>Bloqueados</span></strong>";
														echo "</div>";
	
														echo "<div class='col-md-8'>";
															echo "<span>" . nl2br($dado['bloqueados']) . "</span>";
														echo "</div>";
													echo "</div>";
												echo "</li>";
											}
										}

										if (preg_match('/\bcancelamento\b/i', $dadosComplementos[0]['quadro_informativo'])) {
											if ($dado['cancelamentos']) {
												echo "<li class='list-group-item'>";
													echo "<div class=\"row\">";
														echo "<div class='col-md-4'>";
															echo "<strong><span>Cancelamentos</span></strong>";
														echo "</div>";
														echo "<div class='col-md-8'>";
															echo "<span>" . nl2br($dado['cancelamentos']) . "</span>";
														echo "</div>";
													echo "</div>";
												echo "</li>";
											}
										}


										if(preg_match('/\bvelocidade_reduzida\b/i', $dadosComplementos[0]['quadro_informativo'])){
											if($dados_informacao_geral[0]['velocidade_reduzida_bool'] != 2){
												echo "<li class='list-group-item'>";
													echo "<div class=\"row\">";
														echo "<div class='col-md-4'>";
															echo "<strong><span>Velocidade reduzida</span></strong>";
														echo "</div>";
															echo "<div class='col-md-1'>";
																echo "<span>" . $booleanos[$dados_informacao_geral[0]['velocidade_reduzida_bool']] . "</span>";
															echo "</div>";
															echo "<div class='col-md-7'>";
																echo "<span>" . nl2br($dados_informacao_geral[0]['velocidade_reduzida']) . "</span>";
															echo "</div>";
													echo "</div>";
												echo "</li>";
											}
										}


										if(preg_match('/\bsegunda_via\b/i', $dadosComplementos[0]['quadro_informativo'])){
											if($dado['segunda_via']){
												echo "<li class='list-group-item'>";
													echo "<div class=\"row\">";
														echo "<div class='col-md-4'>";
															echo "<strong><span>Segunda via de boleto</span></strong>";
														echo "</div>";
	
														echo "<div class='col-md-8'>";
															echo "<span>" . nl2br($dado['segunda_via']) . "</span>";
														echo "</div>";
													echo "</div>";
												echo "</li>";
											}
										}
										if(preg_match('/\bdescontos\b/i', $dadosComplementos[0]['quadro_informativo'])){
											
											if($dado['descontos']){
												echo "<li class='list-group-item'>";
													echo "<div class=\"row\">";
														echo "<div class='col-md-4'>";
															echo "<strong><span>Descontos</span></strong>";
														echo "</div>";
	
														echo "<div class='col-md-8'>";
															echo "<span>" . nl2br($dado['descontos']) . "</span>";
														echo "</div>";
													echo "</div>";
												echo "</li>";
											}		
										}

									}
								?>
								</div>
							</div>
					  <?php
					  echo "<br>";
					  }//fim if preg_matches
						
					  endif; //fim if(dados)

					//Comercial
						$dados = DBRead('', 'tb_informacao_geral_contrato', "WHERE id_contrato_plano_pessoa = '".$id_contrato_plano_pessoa."'");

						$dados_planos_cliente = DBRead('', 'tb_plano_cliente_contrato', "WHERE id_contrato_plano_pessoa = '".$id_contrato_plano_pessoa."'");

						if($dados || $dados_planos_cliente):

					  if(preg_match('/\btroca_endereco\b/i', $dadosComplementos[0]['quadro_informativo']) && $dados[0]['troca_endereco'] ||
					  preg_match('/\bposicao_instalacao\b/i', $dadosComplementos[0]['quadro_informativo']) && $dados[0]['posicao_instalacao'] ||
					  preg_match('/\btroca_comodo\b/i', $dadosComplementos[0]['quadro_informativo']) && $dados[0]['troca_comodo'] ||
					  preg_match('/\bcontratacao_servico\b/i', $dadosComplementos[0]['quadro_informativo']) && $dados[0]['contratacao_servico']  ||
					  preg_match('/\btroca_plano\b/i', $dadosComplementos[0]['quadro_informativo']) && $dados[0]['troca_plano'] ||
					  preg_match('/\bplanos\b/i', $dadosComplementos[0]['quadro_informativo']) && $dados_planos_cliente){

					  ?>
						 <div class="panel panel-success">
					    <div class="panel-heading">
					      <h4 class="panel-title"><strong>Comercial</strong></h4>
					    </div>
					    	<div class="panel-body">
					    		<?php
							
									$booleanos = array(
										"1" => "Sim",
										"0" => "Não",
										"" => "Não"
									);

									foreach($dados as $dado){

										if(preg_match('/\btroca_endereco\b/i', $dadosComplementos[0]['quadro_informativo'])){
											if($dado['troca_endereco']){
												echo "<li class='list-group-item'>";
													echo "<div class=\"row\">";
														echo "<div class='col-md-4'>";
															echo "<strong><span>Troca de endereço</span></strong>";
														echo "</div>";
														echo "<div class='col-md-8'>";
															echo "<span>" . nl2br($dado['troca_endereco']) . "</span>";
														echo "</div>";
													echo "</div>";
												echo "</li>";
											}
										}

										if(preg_match('/\btroca_comodo\b/i', $dadosComplementos[0]['quadro_informativo'])){
											if($dado['troca_comodo']){
												echo "<li class='list-group-item'>";
													echo "<div class=\"row\">";
														echo "<div class='col-md-4'>";
															echo "<strong><span>Troca de cômodo</span></strong>";
														echo "</div>";
														echo "<div class='col-md-8'>";
															echo "<span>" . nl2br($dado['troca_comodo']) . "</span>";
														echo "</div>";
													echo "</div>";
												echo "</li>";
											}
										}

										if(preg_match('/\bcontratacao_servico\b/i', $dadosComplementos[0]['quadro_informativo'])){
											if($dado['contratacao_servico']){
												echo "<li class='list-group-item'>";
													echo "<div class=\"row\">";
														echo "<div class='col-md-4'>";
															echo "<strong><span>Contratação de serviço</span></strong>";
														echo "</div>";
														echo "<div class='col-md-8'>";
															echo "<span>" . nl2br($dado['contratacao_servico']) . "</span>";
														echo "</div>";
													echo "</div>";
												echo "</li>";
											}
										}

										if(preg_match('/\btroca_plano\b/i', $dadosComplementos[0]['quadro_informativo'])){
											if($dado['troca_plano']){
												echo "<li class='list-group-item'>";
													echo "<div class=\"row\">";
														echo "<div class='col-md-4'>";
															echo "<strong><span>Troca de plano</span></strong>";
														echo "</div>";
														echo "<div class='col-md-8'>";
															echo "<span>" . nl2br($dado['troca_plano']) . "</span>";
														echo "</div>";
													echo "</div>";
												echo "</li>";
											}
										}									
									}

									$dados_planos = DBRead('', 'tb_plano_cliente', "WHERE id_plano_cliente_contrato = '".$dados_planos_cliente[0]['id_plano_cliente_contrato']."'");

									if(preg_match('/\bplanos\b/i', $dadosComplementos[0]['quadro_informativo'])){
										
										if($dados_planos){ ?>
										<table class="table table-bordered" style="margin-top: 10px">
										<thead>
											<tr>
												<th colspan="4">Planos:</th>
											</tr>
											<tr>
												<th>Descrição</th>
												<th>Download</th>
												<th>Upload</th>
												<th>Observação</th>
											</tr>
										</thead>
										<tbody>
									<?php
										foreach($dados_planos as $conteudo){
											echo "<td>".$conteudo['descricao']."</td>";
											echo "<td>" . sprintf("%01.2f", $conteudo['download']) . " Mbps</td>";		
											echo "<td>" . sprintf("%01.2f", $conteudo['upload']) . " Mbps</td>";			
											echo "<td>" . $conteudo['observacao'] . "</td>";			
										} ?>
										</tbody>
									</table>
										<?php }
									}

									if(preg_match('/\bposicao_instalacao\b/i', $dadosComplementos[0]['quadro_informativo'])){
										if($dados[0]['posicao_instalacao']){
											echo "<li class='list-group-item'>";
												echo "<div class=\"row\">";
													echo "<div class='col-md-4'>";
														echo "<strong><span>Posição de instalação</span></strong>";
													echo "</div>";

													echo "<div class='col-md-8'>";
														echo "<span>" . nl2br($dados[0]['posicao_instalacao']) . "</span>";
													echo "</div>";
												echo "</div>";
											echo "</li>";
										}
									}

							?>
					    	</div>
					  </div>
					  
					  <?php
					   echo "<br>";
					  }//fim do if preg_matches
					 
					 
					  endif; //fim if(dados)

					  //Informações Gerais
					  	$dados = DBRead('', 'tb_informacao_geral_contrato', "WHERE id_contrato_plano_pessoa = '".$id_contrato_plano_pessoa."'");
						  
						if(
							($dados[0]['posicao_os'] && preg_match('/\bposicao_os\b/i', $dadosComplementos[0]['quadro_informativo'])) || 
							($dados[0]['servico_telefonia'] && preg_match('/\bservico_telefonia\b/i', $dadosComplementos[0]['quadro_informativo'])) ||
							($dados[0]['tv_assinatura'] && preg_match('/\btv_assinatura\b/i', $dadosComplementos[0]['quadro_informativo'])) ||
							($dados[0]['servico_streaming'] && preg_match('/\bservico_streaming\b/i', $dadosComplementos[0]['quadro_informativo'])) ||
							($dados[0]['situacoes_adversas'] && preg_match('/\bsituacoes_adversas\b/i', $dadosComplementos[0]['quadro_informativo'])) ||
							($dados[0]['computadores_bool'] != 2 && preg_match('/\bsuporte_computadores\b/i', $dadosComplementos[0]['quadro_informativo'])) ||
							($dados[0]['suporte_dispositivos_moveis_bool'] != 2 && preg_match('/\bsuporte_dispositivos_moveis\b/i', $dadosComplementos[0]['quadro_informativo'])) ||
							($dados[0]['roteadores_bool'] != 2 && preg_match('/\broteadores\b/i', $dadosComplementos[0]['quadro_informativo'])) || 
							($dados[0]['tipo_equipamento'] && preg_match('/\btipo_equipamento\b/i', $dadosComplementos[0]['quadro_informativo']))
						):
						?>

						<div class="panel panel-warning">
						    <div class="panel-heading">
						      <h4 class="panel-title"><strong>Informações Gerais</strong></h4>
						    </div>
						    	<div class="panel-body">
						        <?php
									$booleanos = array(
										"1" => "Sim",
										"0" => "Não",
										"" => "Não"
									);

									echo "<ul class='list-group'>";
										
										if(preg_match('/\bposicao_os\b/i', $dadosComplementos[0]['quadro_informativo'])){
											if($dados[0]['posicao_os']){
												echo "<li class='list-group-item'>";
													echo "<div class=\"row\">";
														echo "<div class='col-md-4'>";
															echo "<strong><span>Posição de Atendimento.</span></strong>";
														echo "</div>";
	
														echo "<div class='col-md-8'>";
															echo "<span>" . nl2br($dados[0]['posicao_os']) . "</span>";
														echo "</div>";
													echo "</div>";
												echo "</li>";
											}
										}
										
										//VERIFICAR
										if(preg_match('/\bservico_telefonia\b/i', $dadosComplementos[0]['quadro_informativo'])){
											if($dados_informacoes[0]['servico_telefonia_bool'] != 2){
												echo "<li class='list-group-item'>";
													echo "<div class=\"row\">";
														echo "<div class='col-md-4'>";
															echo "<strong><span>Serviço de telefonia</span></strong>";
														echo "</div>";
														echo "<div class='col-md-1'>";
															echo "<span>" . $booleanos[$dados_informacao_geral[0]['servico_telefonia_bool']] . "</span>";
														echo "</div>";
														echo "<div class='col-md-7'>";
															echo "<span>" . nl2br($dados_informacao_geral[0]['servico_telefonia']) . "</span>";
														echo "</div>";
													echo "</div>";
												echo "</li>";
											}
										}

										if(preg_match('/\btv_assinatura\b/i', $dadosComplementos[0]['quadro_informativo'])){
											if($dados_informacoes[0]['tv_assinatura_bool'] != 2){
												echo "<li class='list-group-item'>";
													echo "<div class=\"row\">";
														echo "<div class='col-md-4'>";
															echo "<strong><span>TV por assinatura</span></strong>";
														echo "</div>";
														echo "<div class='col-md-1'>";
															echo "<span>" . $booleanos[$dados_informacao_geral[0]['tv_assinatura_bool']] . "</span>";
														echo "</div>";
														echo "<div class='col-md-7'>";
															echo "<span>" . nl2br($dados_informacao_geral[0]['tv_assinatura']) . "</span>";
														echo "</div>";
													echo "</div>";
												echo "</li>";
											}
										}

										if(preg_match('/\bservico_streaming\b/i', $dadosComplementos[0]['quadro_informativo'])){
											if($dados_informacoes[0]['servico_streaming'] != 2){
												echo "<li class='list-group-item'>";
													echo "<div class=\"row\">";
														echo "<div class='col-md-4'>";
															echo "<strong><span>Serviço Streaming</span></strong>";
														echo "</div>";
														echo "<div class='col-md-1'>";
															echo "<span>" . $booleanos[$dados_informacao_geral[0]['servico_streaming_bool']] . "</span>";
														echo "</div>";
														echo "<div class='col-md-7'>";
															echo "<span>" . nl2br($dados_informacao_geral[0]['servico_streaming']) . "</span>";
														echo "</div>";
													echo "</div>";
												echo "</li>";
											}
										}


										if(preg_match('/\bsituacoes_adversas\b/i', $dadosComplementos[0]['quadro_informativo'])){
											if($dados[0]['situacoes_adversas']){
												echo "<li class='list-group-item'>";
													echo "<div class=\"row\">";
														echo "<div class='col-md-4'>";
															echo "<strong><span>Situações adversas</span></strong>";
														echo "</div>";
	
														echo "<div class='col-md-8'>";
															echo "<span>" . nl2br($dados[0]['situacoes_adversas']) . "</span>";
														echo "</div>";
													echo "</div>";
												echo "</li>";
											}
										}

										if(preg_match('/\bsuporte_computadores\b/i', $dadosComplementos[0]['quadro_informativo'])){

											if($dados[0]['computadores_bool'] != 2){

												echo "<li class='list-group-item'>";
													echo "<div class=\"row\">";
														echo "<div class='col-md-4'>";
															echo "<strong><span>Suporte a computadores</span></strong>";
														echo "</div>";
															echo "<div class='col-md-1'>";
																echo "<span>" . $booleanos[$dados[0]['computadores_bool']] . "</span>";
															echo "</div>";
															echo "<div class='col-md-7'>";
																echo "<span>" . nl2br($dados[0]['computadores']) . "</span>";
															echo "</div>";
													echo "</div>";
												echo "</li>";
											}
										}

										if(preg_match('/\bsuporte_dispositivos_moveis\b/i', $dadosComplementos[0]['quadro_informativo'])){
											
											if($dados[0]['suporte_dispositivos_moveis_bool'] != 2){

												echo "<li class='list-group-item'>";
													echo "<div class=\"row\">";
														echo "<div class='col-md-4'>";
															echo "<strong><span>Suporte a dispositivos moveis</span></strong>";
														echo "</div>";
															echo "<div class='col-md-1'>";
																echo "<span>" . $booleanos[$dados[0]['suporte_dispositivos_moveis_bool']] . "</span>";
															echo "</div>";
															echo "<div class='col-md-7'>";
																echo "<span>" . nl2br($dados[0]['suporte_dispositivos_moveis']) . "</span>";
															echo "</div>";
													echo "</div>";
												echo "</li>";
											}
										}
										
										if(preg_match('/\broteadores\b/i', $dadosComplementos[0]['quadro_informativo'])){
											
											if($dados[0]['roteadores_bool'] != 2){

												echo "<li class='list-group-item'>";
													echo "<div class=\"row\">";
														echo "<div class='col-md-4'>";
															echo "<strong><span>Suporte a roteadores</span></strong>";
														echo "</div>";
														echo "<div class='col-md-1'>";
															echo "<span>" . $booleanos[$dados[0]['roteadores_bool']] . "</span>";
														echo "</div>";
														echo "<div class='col-md-7'>";
															echo "<span>" . nl2br($dados[0]['roteadores']) . "</span>";
														echo "</div>";
													echo "</div>";
												echo "</li>";
											}
										}
										
										if(preg_match('/\btipo_equipamento\b/i', $dadosComplementos[0]['quadro_informativo'])){
											if($dados[0]['tipo_equipamento']){
												echo "<li class='list-group-item'>";
													echo "<div class=\"row\">";
														echo "<div class='col-md-4'>";
															echo "<strong><span>Tipo de equipamento</span></strong>";
														echo "</div>";

														echo "<div class='col-md-8'>";
															echo "<span>" . nl2br($dados[0]['tipo_equipamento']) . "</span>";
														echo "</div>";
													echo "</div>";
												echo "</li>";
											}
										}
										
										echo "</ul>";
						    ?>
					    	</div>
					    </div>

						<?php
							endif;
							//fim do if preg_matchs e verificações

						echo "<br>";
						 //fim do if preg_matchs

					if(preg_match('/\bsistemas_gestao\b/i', $dadosComplementos[0]['quadro_informativo'])):
					  	
                	// Sistema de Gestão	 
					  $dados = DBRead('', 'tb_sistema_gestao_contrato a', "INNER JOIN tb_sistema_gestao_acesso b ON a.id_sistema_gestao_contrato = b.id_sistema_gestao_contrato WHERE a.id_contrato_plano_pessoa = '".$id_contrato_plano_pessoa."'");
						if($dados):
							$integra = DBRead('', 'tb_integracao_contrato', "WHERE id_contrato_plano_pessoa = '$id_contrato_plano_pessoa'");
							if(!$integra):
					?>

					  <div class="panel panel-success">
					    <div class="panel-heading">
					      <h4 class="panel-title"><strong><span>Sistema de gestão</span></strong></h4>
					    </div>
					    	<div class="panel-body">
					    		<?php
								
								$dados_sistema = DBRead('', 'tb_sistema_gestao_contrato a', "INNER JOIN tb_tipo_sistema_gestao b ON a.id_tipo_sistema_gestao = b.id_tipo_sistema_gestao WHERE a.id_contrato_plano_pessoa = '".$id_contrato_plano_pessoa."'", "a.*, b.nome AS nome_sistema");
								
					    		$contSistemas = 0;

					    		if($dados_sistema[0]['nome_sistema']){
					    			foreach($dados_sistema as $key => $dado){
							    		$contSistemas++;
							    		$dados_acesso = DBRead('', 'tb_sistema_gestao_acesso', "WHERE id_sistema_gestao_contrato = " . $dados_sistema[$key]['id_sistema_gestao_contrato']);
							    		echo "<div class='row linha-tabela-sistema-gestao'>";
							    			echo "<div class='col-md-6 tabela-sistema-gestao'>";
							    				echo "<span><strong>Sistema (" . $contSistemas . ") : </strong></span><span> " . $dado['nome_sistema'] . "</span>";
							    			echo "</div>";
							    			echo "<div class='col-md-6 tabela-sistema-gestao'>";
							    				echo "<strong>Link de acesso (" . $contSistemas . ") : </strong><span><a href='" . $dado['link'] . "' target='_blank'>" . $dado['link'] . "</a></span>";
											echo "</div>";
											echo "<div class='col-md-12 tabela-sistema-gestao'>";
												echo "<strong>Observação: </strong>".$dado['observacao'];
											echo "</div>";

							    			$cont = 0;
							    			foreach($dados_acesso as $dado_acesso){

							    			$cont++;

								    			echo "<div class='col-md-6 tabela-sistema-gestao'>";
								    				echo '<strong>Usuário ('.$cont.') :</strong><span> ' . $dado_acesso['usuario'] . "</span>";
								    			echo "</div>";
								    			echo "<div class='col-md-6 tabela-sistema-gestao'>";
								    				echo '<strong>Senha ('.$cont.') :</strong><span> ' . $dado_acesso['senha'] . "</span>";
								    			echo "</div>";
							    			}
							    		echo "</div>";
						    		}
						    		
					    		}else{
					    			$dados_acesso = DBRead('', 'tb_sistema_gestao_acesso', "WHERE id_sistema_gestao_contrato = " . $dados_sistema[0]['id_sistema_gestao_contrato']);
						    		echo "<div class='row linha-tabela-sistema-gestao'>";
						    			echo "<div class='col-md-6 tabela-sistema-gestao'>";
						    				echo "<span><strong>Sistema : </strong></span><span> " . $dados_sistema[0]['nome_sistema'] . "</span>";
						    			echo "</div>";
						    			echo "<div class='col-md-6 tabela-sistema-gestao'>";
						    				echo "<strong>Link de acesso : </strong><span><a href='" . $dados_sistema[0]['link'] . "' target='_blank'>" . $dados_sistema[0]['link'] . "</a></span>";
										echo "</div>";
									echo "</div>";
									echo "<div class='row linha-tabela-sistema-gestao'>";
										echo "<div class='col-md-12 tabela-sistema-gestao'>";
											echo "<strong>Observação: </strong>".$dados_sistema[0]['observacao'];
										echo "</div>";
									echo "</div>";
									echo "<div class='row linha-tabela-sistema-gestao'>";
						    			echo "<div class='col-md-6 tabela-sistema-gestao'>";
						    				echo '<strong>Usuário:</strong><span> ' . $dados_acesso[0]['usuario'] . "</span>";
						    			echo "</div>";
						    			echo "<div class='col-md-6 tabela-sistema-gestao'>";
						    				echo '<strong>Senha:</strong><span> ' . $dados_acesso[0]['senha'] . "</span>";
						    			echo "</div>";
						    		echo "</div>";
					    		}
					      		?>
					    	</div>
					  </div>
					  	<?php 
					echo "<br>";
						endif;
					endif; //fim if(dados)

				endif; //fim if(preg_match('/\bsistema_gestao_contrato\b/i', $dadosComplementos[0]['quadro_informativo']))
					?>
				</div>
            </div>
<?php
//Verifica se a integração é com ixc
if($temIntegracao && $temIntegracao[0]['id_integracao'] == "1"){
	include "integracoes/atendimento-exibe-quadro-informativo-ixc.php";
}
?>