<?php
	require_once(__DIR__."/../class/System.php");
	$id_contrato_plano_pessoa = $_GET['contrato'];
	//$id_contrato_plano_pessoa = 114;

	$dados = DBRead('', 'tb_quadro_informativo_historico a', "INNER JOIN tb_usuario b ON a.id_usuario = b.id_usuario INNER JOIN tb_pessoa c ON b.id_pessoa = c.id_pessoa INNER JOIN tb_quadro_informativo_modulo d ON a.id_quadro_informativo_modulo = d.id_quadro_informativo_modulo WHERE id_contrato_plano_pessoa = $id_contrato_plano_pessoa AND a.tipo = 1 ORDER BY id_quadro_informativo_historico DESC limit 1", "a.*, c.nome, d.nome as modulo");

	if ($dados) {
		$ultima_alteracao = "feita por ".$dados[0]['nome']." na data de ".converteDataHora($dados[0]['data_hora'])." - ".$dados[0]['modulo'];

	} else {
		$ultima_alteracao = "Não consta"; 
	}

?>

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
	#myBtn {
		display: none;
		position: fixed;
		bottom: 20px;
		right: 30px;
		z-index: 99;
		font-size: 15px;
		border: none;
		outline: none;
		color: white;
		cursor: pointer;
		padding: 15px;
		border-radius: 4px;
	}
</style>

<script type="text/javascript">
    $(document).ready(function() {
        document.title = 'Simples V2 - QI';
    });
</script>

<div class="container-fluid">

	<div class="row">
        <div class="col-md-12">
            <div class="panel panel-primary painel-quadro-informativo">
            	<?php 
                	//Dados empresa
				    $empresa = DBRead('', 'tb_contrato_plano_pessoa a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa where a.id_contrato_plano_pessoa = '".$id_contrato_plano_pessoa."'");

				    $booleanos = array(
						"1" => "Sim",
						"0" => "Não"
					);
				?>
            	<div class="panel-heading clearfix">
            		<div class="row">
						<div class="col-md-6">
							<h3 class="panel-title pull-right col-md-12">Quadro informativo: <strong>
							<?php echo $empresa[0]['nome'];
							
							if($empresa[0]['nome_contrato']){
								echo " (". $empresa[0]['nome_contrato'] .")";
							}

							?>
							</strong></h3>
						</div>
						<div class="col-md-6">
							<h3 class="panel-title pull-right">Última alteração: <?= $ultima_alteracao ?> </h3>
						</div>
	                </div>
                </div>
                <div class="panel-body" style="padding-bottom: 0;">
                	<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
					<?php
						//Informações de registro
						$dados_informacoes = DBRead('', 'tb_informacao_geral_contrato', "WHERE id_contrato_plano_pessoa = '".$id_contrato_plano_pessoa."'");

						if(($dados_informacoes[0]['confirmacao_cadastro_cliente'] && $dados_informacoes[0]['acesso_controladoras_bool'] != 2) || $dados_informacoes[0]['nao_cliente'] || $dados_informacoes[0]['classificacao_atendimento_sistema_gestao'] || $dados_informacoes[0]['selecao_finalizacao_sistema_gestao'] || $dados_informacoes[0]['tipo_os'] || $dados_informacoes[0]['suporte_acesso_lento'] || $dados_informacoes[0]['informacoes_adicionais'] || $dados_informacoes[0]['inativo_cancelado']):
					?>
					  <div class="panel panel-info">
					    <div class="panel-heading">
					      <h4 class="panel-title"><strong>Informações de registro</strong></h4>
					    </div>
					    	<div class="panel-body">
					        <?php	
								if($dados_informacoes[0]['confirmacao_cadastro_cliente']){
									echo "<li class='list-group-item'>";
										echo "<div class=\"row\">";
											echo "<div class='col-md-4'>";
												echo "<strong><span>Confirmação de cadastro de cliente</span></strong>";
											echo "</div>";
											echo "<div class='col-md-8'>";
												echo "<span>" . nl2br($dados_informacoes[0]['confirmacao_cadastro_cliente']) . "</span>";
											echo "</div>";
										echo "</div>";
									echo "</li>";
								}
								if($dados_informacoes[0]['acesso_controladoras_bool'] != 2){
									echo "<li class='list-group-item'>";
										echo "<div class=\"row\">";
											echo "<div class='col-md-4'>";
												echo "<strong><span>Sistema informa se o cliente está logado</span></strong>";
											echo "</div>";
											echo "<div class='col-md-1'>";
												echo "<span>" . $booleanos[$dados_informacoes[0]['acesso_controladoras_bool']] . "</span>";
											echo "</div>";
											echo "<div class='col-md-7'>";
												echo "<span>" . nl2br($dados_informacoes[0]['acesso_controladoras']) . "</span>";
											echo "</div>";
										echo "</div>";
									echo "</li>";
								}
								if($dados_informacoes[0]['nao_cliente']){
									echo "<li class='list-group-item'>";
										echo "<div class=\"row\">";
											echo "<div class='col-md-4'>";
												echo "<strong><span>Não clientes</span></strong>";
											echo "</div>";
											echo "<div class='col-md-8'>";
												echo "<span>" . nl2br($dados_informacoes[0]['nao_cliente']) . "</span>";
											echo "</div>";
										echo "</div>";
									echo "</li>";
								}			
								if($dados_informacoes[0]['classificacao_atendimento_sistema_gestao']){
									echo "<li class='list-group-item'>";
										echo "<div class=\"row\">";
											echo "<div class='col-md-4'>";
												echo "<strong><span>Classificação de atendimento no sistema de gestão</span></strong>";
											echo "</div>";
											echo "<div class='col-md-8'>";
												echo "<span>" . nl2br($dados_informacoes[0]['classificacao_atendimento_sistema_gestao']) . "</span>";
											echo "</div>";
										echo "</div>";
									echo "</li>";
								}
								if($dados_informacoes[0]['selecao_finalizacao_sistema_gestao']){
									echo "<li class='list-group-item'>";
										echo "<div class=\"row\">";
											echo "<div class='col-md-4'>";
												echo "<strong><span>Seleção de finalização no sistema de gestão</span></strong>";
											echo "</div>";
											echo "<div class='col-md-8'>";
												echo "<span>" .  nl2br($dados_informacoes[0]['selecao_finalizacao_sistema_gestao'])  . "</span>";
											echo "</div>";
										echo "</div>";
									echo "</li>";
								}
								if($dados_informacoes[0]['informacoes_adicionais']){
									echo "<li class='list-group-item'>";
										echo "<div class=\"row\">";
											echo "<div class='col-md-4'>";
												echo "<strong><span>Informações adicionais</span></strong>";
											echo "</div>";

											echo "<div class='col-md-8'>";
												echo "<span>" . nl2br($dados_informacoes[0]['informacoes_adicionais']) . "</span>";
											echo "</div>";
										echo "</div>";
									echo "</li>";
								}
								if($dados_informacoes[0]['tipo_os']){
									echo "<li class='list-group-item'>";
										echo "<div class=\"row\">";
											echo "<div class='col-md-4'>";
												echo "<strong><span>Tipo de O.S</span></strong>";
											echo "</div>";
											echo "<div class='col-md-8'>";
												echo "<span>" . nl2br($dados_informacoes[0]['tipo_os']) . "</span>";
											echo "</div>";
										echo "</div>";
									echo "</li>";
								}
								if($dados_informacoes[0]['suporte_acesso_lento']){
									echo "<li class='list-group-item'>";
										echo "<div class=\"row\">";
											echo "<div class='col-md-4'>";
												echo "<strong><span>Suporte a acesso lento</span></strong>";
											echo "</div>";
											echo "<div class='col-md-8'>";
												echo "<span>" . nl2br($dados_informacoes[0]['suporte_acesso_lento']) . "</span>";
											echo "</div>";
										echo "</div>";
									echo "</li>";
								}

								if($dados_informacoes[0]['inativo_cancelado']){
									echo "<li class='list-group-item'>";
										echo "<div class=\"row\">";
											echo "<div class='col-md-4'>";
												echo "<strong><span>Cadastro inativo/cancelado.</span></strong>";
											echo "</div>";

											echo "<div class='col-md-8'>";
												echo "<span>" . nl2br($dados_informacoes[0]['inativo_cancelado']) . "</span>";
											echo "</div>";
										echo "</div>";
									echo "</li>";
								}
						    ?>
					    	</div>
					  	</div>
					  <?php
					  echo "<br>";
					  endif;
					//################## Informações de registro

					//Velocidade minima
					$dados = DBRead('', 'tb_velocidade_minima_encaminhar_contrato a', "INNER JOIN tb_velocidade_minima_encaminhar b ON a.id_velocidade_minima_encaminhar_contrato = b.id_velocidade_minima_encaminhar_contrato WHERE a.id_contrato_plano_pessoa = '".$id_contrato_plano_pessoa."'");

					if($dados):
					?>
					<div class="panel panel-warning">
						<div class="panel-heading">
						<h4 class="panel-title"><strong><span>Velocidades mínimas para o encaminhamento</span></strong></h4>
						</div>
							<div class="panel-body">
								<?php

								if($dados[0]['observacao']){
										echo "<div class='alerta-observacao'><strong>Observação: </strong><span><br>" . nl2br($dados[0]['observacao']) . "</span></div>";
									}
								echo "<table class='table table-bordered'>";
										echo "<thead>";
											echo "<tr>";
												echo "<th><span>Tipo de equipamento</span></th>";
												echo "<th><span>Tipo de cliente</span></th>";
												echo "<th><span>Porcentagem(%)</span></th>";
												echo "<th><span>Tipo transferência</span></th>";
											echo "</tr>";
										echo "</thead>";
										echo "<tbody>";
										foreach($dados as $dado){

											$dado_tipo_equipamento = DBRead('', 'tb_tipo_equipamento', "WHERE id_tipo_equipamento = '".$dado['tipo_equipamento']."'");

											$id_tipo_cliente = $dado['id_tipo_plano_cliente'];
											$dados_tipo_cliente = DBRead('', 'tb_tipo_plano_cliente', "WHERE id_tipo_plano_cliente = '$id_tipo_cliente'");
											$descricao = $dados_tipo_cliente[0]['descricao'];
											$tipo_transferencia = $dado['tipo_transferencia'];
											if($tipo_transferencia == 1){
												$tipo_transferencia = 'Download';
											}else if($tipo_transferencia == 2){
												$tipo_transferencia = 'Upload';
											}else{
												$tipo_transferencia = 'Upload/Download';
											}
											echo "<tr>";
												echo "<td><span> " . $dado_tipo_equipamento[0]['descricao'] . "</span></td>";
												echo "<td><span> " . $descricao . "</span></td>";
												echo "<td><span> " . $dado['porcentagem'] . "%" . "</span></td>";
												echo "<td><span> " . $tipo_transferencia . "</span></td>";
											echo "</tr>";
										}
									echo "</tbody>";
								echo "</table>";
								?>
							</div>
					</div>
					<?php
				 	echo "<br>";
				  	endif;
					//###########Velocidade minima

					//Parametros
					?>
						<div class="row" style="margin-bottom: 20px">
							<div class="col-md-12">
								
									<?php
									$parametros = DBRead('', 'tb_parametros', "WHERE id_contrato_plano_pessoa = '$id_contrato_plano_pessoa'");

									if($parametros[0]['ramal_retorno'] || $parametros[0]['prefixo_telefone']):
									?>
									<div class="panel panel-success painel-quadro-informativo">
										<div class="panel-heading clearfix">
											<h4 class="panel-title"><strong>Dados de retorno</strong></h4>
										</div>
										<div class="panel-body">
												<?php
												if($parametros[0]['ramal_retorno'] != ""){
													echo "<li class='list-group-item'>";
														echo "<div class=\"row\">";
															echo "<div class='col-md-4'>";
																echo "<strong><span>Número de retorno</span></strong>";
															echo "</div>";
															echo "<div class='col-md-8'>";
																echo "<span>" . $parametros[0]['ramal_retorno'] . "</span>";
															echo "</div>";
														echo "</div>";
													echo "</li>";
												}

												if($parametros[0]['prefixo_telefone']){
													echo "<li class='list-group-item'>";
														echo "<div class=\"row\">";
															echo "<div class='col-md-4'>";
																echo "<strong><span>Prefixo de retorno</span></strong>";
															echo "</div>";
															echo "<div class='col-md-8'>";
																echo "<span>" . $parametros[0]['prefixo_telefone'] . "</span>";
															echo "</div>";
														echo "</div>";
													echo "</li>";
												}
												?>
										</div>
									</div>
									<?php
									endif;
									?>
								
							</div>
						</div>
						<?php
					//################ Parametros
					 
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
											echo "<div class='alerta-observacao'><strong>Observação: </strong><span>" . nl2br($dados[0]['observacao']) . "</span></div>";
										}		
					      			if($dados[1]['usuario']){
					      				$cont = 0;
						      				foreach($dados as $dado){
						      					$cont++;
						      				echo "<tr>";
						      					echo "<td><span><strong>Usuário (".$cont."): </strong>" . $dado['usuario'] . "</span></td>";
						      					echo "<td><span><strong>Senha (".$cont."): </strong>" . $dado['senha'] . "</span></td>";
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
					endif; 
					//######## Acesso equipamentos

					// Sinal
					$dados = DBRead('', 'tb_sinal_equipamento_contrato a', "INNER JOIN tb_sinal_equipamento b ON a.id_sinal_equipamento_contrato = b.id_sinal_equipamento_contrato WHERE a.id_contrato_plano_pessoa = '".$id_contrato_plano_pessoa."'");
					if($dados):
					?>
					<div class="panel panel-success">
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
											  echo "<th><span>Observação</span></th>";
											echo "</tr>";
										echo "</thead>";
										echo "<tbody>";
											foreach($dados as $dado){
											echo "<tr>";
												echo "<td><span> " . nl2br($dado['nome']) . "</span></td>";
											  echo "<td><span> " . nl2br($dado['intensidade_sinal']) . "</span></td>";
											  echo "<td><span>" . nl2br($dado['observacao']) . "</span></td>";  
											echo "</tr>";
											}
										echo "</tbody>";
									echo "</table>";
								?>
						  </div>
					</div>
				  <?php 
				  echo "<br>";

				  endif; 
				  //################## Sinal
					
					// Configuração de roteadores
					$dados = DBRead('', 'tb_configuracao_roteadores_contrato a', "INNER JOIN tb_configuracao_roteadores b ON a.id_configuracao_roteadores_contrato = b.id_configuracao_roteadores_contrato WHERE a.id_contrato_plano_pessoa = '".$id_contrato_plano_pessoa."'");
					if($dados):
					?>
					  <div class="panel panel-warning">
					    <div class="panel-heading">
					      <h4 class="panel-title"><strong>Conexões de cabos</strong></h4>
					    </div>
					    	<div class="panel-body">
					    		<?php
					    			if($dados[0]['observacao']){
										echo "<div class='alerta-observacao'><strong>Observação: </strong><span>" . nl2br($dados[0]['observacao']) . "</span></div>";
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
						endif;
					//######### Configuração de roteadores
					
					//Reiniciar equipamentos
					  $dados = DBRead('', 'tb_reinicio_equipamento_contrato a', "INNER JOIN tb_reinicio_equipamento b ON a.id_reinicio_equipamento_contrato = b.id_reinicio_equipamento_contrato WHERE a.id_contrato_plano_pessoa = '".$id_contrato_plano_pessoa."'");
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
											$tipo_equipamento = DBRead('', 'tb_tipo_equipamento', "WHERE id_tipo_equipamento = '".$dado['equipamento']."'");
						      				echo "<tr>";
						      					echo "<td><span> " . $tipo_equipamento[0]['descricao'] . "</span></td>";
						      					
						      						echo "<td><span> " .converteSegundosHoras($dado['tempo']). "</span></td>";
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
					endif;
					//################### Reiniciar equipamentos

					// Plantonistas
					$dados = DBRead('', 'tb_plantonista_contrato', "where id_contrato_plano_pessoa = '".$id_contrato_plano_pessoa."'");
					if($dados):
					?> 

					<div class="panel panel-success">
						<div class="panel-heading">
							<h4 class="panel-title"><strong><span>Plantonistas</span></strong></h4>
						</div>
						<div class="panel-body">
							<?php
								echo "<div class='row'>";
									echo "<div class='col-md-12 conteudo-editor'>";
										echo $dados[0]['tabela'];
									echo "</div>";
										echo "</div>";
								?>
						</div>
					</div>
					<?php 
					echo "<br>";
					endif; 
					//############### Plantonistas 

					// Prazo retorno
						$dados = DBRead('', 'tb_prazo_retorno_contrato a', "INNER JOIN tb_prazo_retorno b ON a.id_prazo_retorno_contrato = b.id_prazo_retorno_contrato WHERE a.id_contrato_plano_pessoa = '".$id_contrato_plano_pessoa."'");
						if($dados):
						?>
							<div class="panel panel-warning">
								<div class="panel-heading">
									<h4 class="panel-title"><strong><span>Prazos de retorno</span></strong></h4>
								</div>
								<div class="panel-body">
							<?php
							$dados_contrato = DBRead('', 'tb_prazo_retorno_contrato', "WHERE id_contrato_plano_pessoa = '".$id_contrato_plano_pessoa."'");

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
										echo "<div class='alerta-observacao'><strong>Observação: </strong><span>" . nl2br($dado['observacao']) . "</span></div>";
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
												$dados_tipo_plano = DBRead('', 'tb_tipo_plano_cliente', "WHERE id_tipo_plano_cliente = '$id_tipo_cliente'");
												$descricao = $dados_tipo_plano[0]['descricao'];

												if ($dado_prazo['tempo'] == 0) {
													$legenda_tempo = " o mais breve possível dentro do horário de atendimento.";

												} else {
													$legenda_tempo = $dado_prazo['tempo']. " horas " . $dado_tipo_tempo[$dado_prazo['tipo_tempo']];
												}

												echo "<tr>";
													echo "<td class='col-md-4'><strong>Plano: </strong><span>" . $descricao . " => " . $legenda_tempo . " " .$prazo. "</span></td>";
													echo "<td class='col-md-8'><strong>Observação: </strong><span> " . nl2br($dado_prazo['observacao_prazo']) . "</span></td>";
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
					  
					//########## Prazo retorno
					  
					// Horarios
					  	$dados = DBRead('', 'tb_horario_contrato a', "INNER JOIN tb_horario b ON a.id_horario_contrato = b.id_horario_contrato WHERE a.id_contrato_plano_pessoa = '".$id_contrato_plano_pessoa."' AND a.tipo != '8' ORDER BY dia ASC");

						$dados_informacao_geral = DBRead('', 'tb_informacao_geral_contrato', "WHERE id_contrato_plano_pessoa = '".$id_contrato_plano_pessoa."' AND horarios_monitoramento != '' ");

						if($dados || $dados_informacao_geral[0]['horarios_monitoramento']):
					  ?>

					  <div class="panel panel-success" style='margin-top: 5px;'>
					    <div class="panel-heading">
					      <h4 class="panel-title"><strong>Horários</strong></h4>
					    </div>
						<div class="panel-body">
							<?php

								$tipo_horario = array(
									"1" => "Horários de funcionamento da empresa",
									"2" => "Horários que mandam as chamadas para a Belluno",
									"3" => "Horários de atendimento EMPRESARIAL dos técnicos de campo",
									"5" => "Horários de atendimento DOMÉSTICO dos técnicos de campo",
									"6" => "Horários de atendimento DEDICADO dos técnicos de campo",
									"7" => "Horários gerais de atendimento do técnico de campo",
									"9" => "Horários de atendimento via texto pela Belluno.",
									"10" => "Horários de retorno telefônico do provedor."
								);
								$tipo_dia = array(
									"1" => "Seg. a Dom.",
									"2" => "Seg. a Sab.",
									"3" => "Seg. a Sex.",
									"4" => "Dom. e Feriados",
									"5" => "Feriados",
									"6" => "Domingo",
									"7" => "Segunda",
									"8" => "Terça",
									"9" => "Quarta",
									"10" => "Quinta",
									"11" => "Sexta",
									"12" => "Sabado",
									"13" => "Seg. a Qui."
								);
								
								$dados_horario_contrato = DBRead('', 'tb_horario_contrato', "WHERE id_contrato_plano_pessoa = '".$id_contrato_plano_pessoa."' AND tipo != '8'");

								foreach($dados_horario_contrato as $key => $dado){

									$dados_horario = DBRead('', 'tb_horario', "WHERE id_horario_contrato = " . $dados_horario_contrato[$key]['id_horario_contrato']);

									echo "<div class='row' style=\"margin-left: 2px; margin-right: 2px;\">";
									echo "<div class='col-md-12 alerta-tipo alerta-horario'>";
									if($dado['observacao']){
										$obs =  " </br><strong>Observação:</strong> ".nl2br($dado['observacao'])." ";
									}else{
										$obs = "";
									}
									echo "<span><strong>" . $tipo_horario[$dado['tipo']] . "</strong>".$obs."</span>";
									echo "</div>";

									foreach($dados_horario as $dado_horario){
										echo "<div class='col-md-4 alerta-horario'>";
											echo '<strong>Dia:</strong><span> ' . $tipo_dia[$dado_horario['dia']] . "</span>";
										echo "</div>";
										echo "<div class='col-md-4 alerta-horario'>";
											echo '<strong>Hora início:</strong><span> ' . substr($dado_horario['hora_inicio'],0,-3)  . "</span>";
										echo "</div>";
										echo "<div class='col-md-4 alerta-horario'>";
											echo '<strong>Hora fim:</strong><span> ' . substr($dado_horario['hora_fim'],0,-3) . "</span>";
										echo "</div>";
									}
									echo "</div>";
									echo "<br>";
								}
								
								if($dados_informacao_geral[0]['horarios_monitoramento']){
									echo "<div class='row' style=\"margin-left: 2px; margin-right: 2px;\">";
										echo "<div class='col-md-12 alerta-tipo alerta-horario'>";
											echo "<span><strong>Horários de monitoramento</strong></span>";
										echo "</div>";

										echo "<div class='col-md-12 alerta-horario'>";
											echo '<span>' . nl2br($dados_informacao_geral[0]['horarios_monitoramento'])  . "</span>";
										echo "</div>";
									echo "</div>";
								}
							?>
						</div>
					  </div>
					  <?php
					  echo "<br>";

					endif;
					//############### Horarios
					
					 // URA
					  $dados = DBRead('', 'tb_ura a', "INNER JOIN tb_ura_contrato b ON a.id_ura_contrato = b.id_ura_contrato WHERE b.id_contrato_plano_pessoa = '".$id_contrato_plano_pessoa."'");
						if($dados):
					  ?>

					  <div class="panel panel-info">
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
					  endif;
					  echo "<br>";
					//##################Horarios
					
					//#################Endereço
					  $dados = DBRead('', 'tb_localizacao_contrato a', "INNER JOIN tb_localizacao b ON a.id_localizacao_contrato = b.id_localizacao_contrato where a.id_contrato_plano_pessoa = '".$id_contrato_plano_pessoa."'");
					  //var_dump($dados);
					  if($dados):

					  ?>
					  <div class="panel panel-warning">
					    <div class="panel-heading">
					      <h4 class="panel-title"><strong>Dados da empresa</strong></h4>
					    </div>
					    <div class="panel" role="tabpanel" aria-labelledby="headingOne">
					      <div class="panel-body">

					      	<?php
								$cont = 1;
								$dados_pessoa_contrato = DBRead('', 'tb_contrato_plano_pessoa a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_contrato_plano_pessoa = '".$id_contrato_plano_pessoa."' ", "b.*" );

								if($dados || $dados_pessoa_contrato){
									if($dados_pessoa_contrato[0]['site'] && $dados_pessoa_contrato[0]['site'] != ''){
										$site_contrato =  $dados_pessoa_contrato[0]['site'];
									}else{
										$site_contrato =  "N/D";
									}

									if($dados_pessoa_contrato[0]['facebook'] && $dados_pessoa_contrato[0]['facebook'] != ''){
										$facebook_contrato =  $dados_pessoa_contrato[0]['facebook'];
									}else{
										$facebook_contrato =  "N/D";
									}

									if($dados_pessoa_contrato[0]['linkedin'] && $dados_pessoa_contrato[0]['linkedin'] != ''){
										$linkedin_contrato =  $dados_pessoa_contrato[0]['linkedin'];
									}else{
										$linkedin_contrato =  "N/D";
									}

									if($dados_pessoa_contrato[0]['instagram'] && $dados_pessoa_contrato[0]['instagram'] != ''){
										$instagram_contrato =  $dados_pessoa_contrato[0]['instagram'];
									}else{
										$instagram_contrato =  "N/D";
									}

									if($dados_pessoa_contrato[0]['twitter'] && $dados_pessoa_contrato[0]['twitter'] != ''){
										$twitter_contrato =  $dados_pessoa_contrato[0]['twitter'];
									}else{
										$twitter_contrato =  "N/D";
									}
									
									echo "<table class='table table-bordered'>";
										echo "<tbody>";
											echo "<tr>";
												echo "<td><span><strong>Site: </strong>".$site_contrato."</span></td>";
											echo "</tr>";
											echo "<tr>";
												echo "<td><span><strong>Facebook: </strong>".$facebook_contrato."</span></td>";
											echo "</tr>";
											echo "<tr>";
												echo "<td><span><strong>Linkedin: </strong>".$linkedin_contrato."</span></td>";
											echo "</tr>";
											echo "<tr>";
												echo "<td><span><strong>Instagram: </strong>".$instagram_contrato."</span></td>";
											echo "</tr>";
											echo "<tr>";
												echo "<td><span><strong>Twitter: </strong>".$twitter_contrato."</span></td>";
											echo "</tr>";

										foreach($dados as $dado_localizacao):

											$nome_cidade = DBRead('', 'tb_cidade a', "INNER JOIN tb_estado b ON a.id_estado = b.id_estado WHERE a.id_cidade = '".$dado_localizacao['id_cidade']."'", "a.nome AS nome_cidade, b.nome AS nome_estado");
	
											$lat = $dado_localizacao['latitude'];
											$lon = $dado_localizacao['longitude'];
	
									
												echo "<tr>";
													echo "<td><span><strong>Endereço (".$cont."): </strong>Cidade: ".$nome_cidade[0]['nome_cidade']." / ".$nome_cidade[0]['nome_estado']." - ".$dado_localizacao['endereco']."</span><a target='_blank' class='btn btn-default btn-sm' style='float: right'  href='http://simples.bellunotec.com.br/v2//api/iframe?token=<?php echo $request->token ?>&view=exibe-mapa-localizacao&contrato=$id_contrato_plano_pessoa&lat=$lat&lon=$lon'>Exibir no mapa <i class='fa fa-map-marker' aria-hidden='true'></i></a></td>";
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
															echo "<td><span><strong>Número de Contato: </strong>".$dado_telefone['telefone']."";
														
														if($dado_telefone['observacao']){
															echo " (".nl2br($dado_telefone['observacao']).")";
														}
														echo "</span></td></tr>";
													}
												}
	
												if($dados[0]['observacao']){
													echo "<tr>";
														echo "<td><span><strong>Obs Localização: </strong>" . nl2br($dado_localizacao['observacao']) . "</span></td>";
													echo "</tr>";
												}
												
												if($cont > 1){
													echo "<br>";
												}
												
											$cont++;
										endforeach;
										echo "</tbody>";
									echo "</table>";
								}
								

					      		
					      	?>
					      </div>
					    </div>
					  </div>
					<?php 
					 endif;
					echo "<br>";
					//#################Endereço
					 
					//#################Endereço
					  $dados = DBRead('', 'tb_vinculo_pessoa a', "INNER JOIN tb_pessoa b ON a.id_pessoa_filho = b.id_pessoa WHERE a.id_pessoa_pai = '".$empresa[0]['id_pessoa']."' AND b.status != '2' ORDER BY b.nome ASC", 'a.id_vinculo_pessoa, b.id_pessoa, b.nome, b.fone1, b.email1');

					  if($dados):

					  ?>
					  <div class="panel panel-warning">
					    <div class="panel-heading">
					      <h4 class="panel-title"><strong>Pessoas da empresa</strong></h4>
					    </div>
					    <div class="panel" role="tabpanel" aria-labelledby="headingOne">
					      <div class="panel-body">

					      	<?php
								$cont = 1;

								  foreach($dados as $conteudo):
									
									$dados_vinculo = DBRead('','tb_vinculo_tipo_pessoa a',"INNER JOIN tb_vinculo_tipo b ON a.id_vinculo_tipo = b.id_vinculo_tipo WHERE a.id_vinculo_pessoa = '".$conteudo['id_vinculo_pessoa']."' ORDER BY b.nome");
									
									$tipo = '';
									if($dados_vinculo){
										foreach($dados_vinculo as $conteudo_vinculos){
											$tipo .= $conteudo_vinculos['nome']." | ";
										}
										$tipo = substr($tipo, 0, -3);
									}
							?>
									<a class="list-group-item">
                                      <div style="margin-top: 7px !important; margin-bottom: 7px !important;">
                                        <span class="list-group-item-heading" style="font-size: 14px;">
                                          <strong><?=$conteudo['nome']?></strong>
                                        </span>
                                        <br>
                                        <span class=""> <?=$tipo?></span>
                                        <br>
                                      </div>
                                    </a>
							<?php
								endforeach;
					      	?>
					      </div>
					    </div>
					  </div>
					<?php 
					 endif;
					echo "<br>";
				 	//#################Endereço
					
					//Financeiro
					  $dados_informacao_geral = DBRead('', 'tb_informacao_geral_contrato', "WHERE id_contrato_plano_pessoa = '".$id_contrato_plano_pessoa."'");

					  if($dados_informacao_geral[0]['bloqueados'] || $dados_informacao_geral[0]['cancelamentos'] || $dados_informacao_geral[0]['segunda_via'] || $dados_informacao_geral[0]['descontos']):
					  ?>

					   <div class="panel panel-success">
					    <div class="panel-heading">
					      <h4 class="panel-title"><strong>Financeiro</strong></h4>
					    </div>
					    	<div class="panel-body">
					    		<?php

									if($dados_informacao_geral[0]['bloqueados']){
										echo "<li class='list-group-item'>";
											echo "<div class=\"row\">";
												echo "<div class='col-md-4'>";
													echo "<strong><span>Bloqueados</span></strong>";
												echo "</div>";
												echo "<div class='col-md-8'>";
													echo "<span>" . nl2br($dados_informacao_geral[0]['bloqueados']) . "</span>";
												echo "</div>";
											echo "</div>";
										echo "</li>";
									}

									if ($dados_informacao_geral[0]['cancelamentos']) {
										echo "<li class='list-group-item'>";
											echo "<div class=\"row\">";
												echo "<div class='col-md-4'>";
													echo "<strong><span>Cancelamentos</span></strong>";
												echo "</div>";
												echo "<div class='col-md-8'>";
													echo "<span>" . nl2br($dados_informacao_geral[0]['cancelamentos']) . "</span>";
												echo "</div>";
											echo "</div>";
										echo "</li>";
									}

									if($dados_informacoes[0]['velocidade_reduzida_bool'] != 2){
										echo "<li class='list-group-item'>";
											echo "<div class=\"row\">";
												echo "<div class='col-md-4'>";
													echo "<strong><span>Velocidade reduzida</span></strong>";
												echo "</div>";
												echo "<div class='col-md-1'>";
													echo "<span>" . $booleanos[$dados_informacoes[0]['velocidade_reduzida_bool']] . "</span>";
												echo "</div>";
												echo "<div class='col-md-7'>";
													echo "<span>" . nl2br($dados_informacoes[0]['velocidade_reduzida']) . "</span>";
												echo "</div>";
											echo "</div>";
										echo "</li>";
									}

									if($dados_informacao_geral[0]['segunda_via']){
										echo "<li class='list-group-item'>";
											echo "<div class=\"row\">";
												echo "<div class='col-md-4'>";
													echo "<strong><span>Segunda via de boleto</span></strong>";
												echo "</div>";

												echo "<div class='col-md-8'>";
													echo "<span>" . nl2br($dados_informacao_geral[0]['segunda_via']) . "</span>";
												echo "</div>";
											echo "</div>";
										echo "</li>";
									}
									
									if($dados_informacao_geral[0]['descontos']){
										echo "<li class='list-group-item'>";
											echo "<div class=\"row\">";
												echo "<div class='col-md-4'>";
													echo "<strong><span>Descontos</span></strong>";
												echo "</div>";

												echo "<div class='col-md-8'>";
													echo "<span>" . nl2br($dados_informacao_geral[0]['descontos']) . "</span>";
												echo "</div>";
											echo "</div>";
										echo "</li>";
									}		
								?>
								</div>
							</div>
							<?php
							echo "<br>";
							endif;
					//####################Financeiro

						$dados_planos_cliente = DBRead('', 'tb_plano_cliente_contrato', "WHERE id_contrato_plano_pessoa = '".$id_contrato_plano_pessoa."'");
					
					  if($dados_informacao_geral[0]['troca_endereco'] || $dados_informacao_geral[0]['troca_comodo'] || $dados_informacao_geral[0]['contratacao_servico'] || $dados_informacao_geral[0]['troca_plano'] || $dados_planos_cliente || $dados_informacao_geral[0]['posicao_instalacao'] != ""):
					  ?>

					   <div class="panel panel-info">
					    <div class="panel-heading">
					      <h4 class="panel-title"><strong>Comercial</strong></h4>
					    </div>
					    	<div class="panel-body">
					    		<?php

										if($dados_informacao_geral[0]['troca_endereco']){
											echo "<li class='list-group-item'>";
												echo "<div class=\"row\">";
													echo "<div class='col-md-4'>";
														echo "<strong><span>Troca de endereço</span></strong>";
													echo "</div>";
													echo "<div class='col-md-8'>";
														echo "<span>" . nl2br($dados_informacao_geral[0]['troca_endereco']) . "</span>";
													echo "</div>";
												echo "</div>";
											echo "</li>";
										}

										if($dados_informacao_geral[0]['troca_comodo']){
											echo "<li class='list-group-item'>";
												echo "<div class=\"row\">";
													echo "<div class='col-md-4'>";
														echo "<strong><span>Troca de cômodo</span></strong>";
													echo "</div>";
													echo "<div class='col-md-8'>";
														echo "<span>" . nl2br($dados_informacao_geral[0]['troca_comodo']) . "</span>";
													echo "</div>";
												echo "</div>";
											echo "</li>";
										}
									
										if($dados_informacao_geral[0]['contratacao_servico']){
											echo "<li class='list-group-item'>";
												echo "<div class=\"row\">";
													echo "<div class='col-md-4'>";
														echo "<strong><span>Contratação de serviço</span></strong>";
													echo "</div>";
													echo "<div class='col-md-8'>";
														echo "<span>" . nl2br($dados_informacao_geral[0]['contratacao_servico']) . "</span>";
													echo "</div>";
												echo "</div>";
											echo "</li>";

										}

										if($dados_informacao_geral[0]['troca_plano']){
											echo "<li class='list-group-item'>";
												echo "<div class=\"row\">";
													echo "<div class='col-md-4'>";
														echo "<strong><span>Troca de plano</span></strong>";
													echo "</div>";
													echo "<div class='col-md-8'>";
														echo "<span>" . nl2br($dados_informacao_geral[0]['troca_plano']) . "</span>";
													echo "</div>";
												echo "</div>";
											echo "</li>";
										}

									$dados_planos = DBRead('', 'tb_plano_cliente', "WHERE id_plano_cliente_contrato = '".$dados_planos_cliente[0]['id_plano_cliente_contrato']."'");
									if($dados_planos){

										echo "<li class='list-group-item disabled' style='cursor: context-menu; color: #000;'>Planos:</li>";

										foreach($dados_planos as $conteudo){
											
												echo "<li class='list-group-item'>";
													echo "<div class=\"row\">";
														echo "<div class='col-md-4'>";
															echo "<strong><span>".$conteudo['descricao']."</span></strong>";
														echo "</div>";
														echo "<div class='col-md-8'>";
															echo "<div class='col-md-2'>";
																echo "<span>Download: " . sprintf("%01.2f", $conteudo['download']) . " Mbps</span>";
															echo "</div>";
															echo "<div class='col-md-2'>";
																echo "<span>Upload: " .sprintf("%01.2f", $conteudo['upload']) . " Mbps</span>";
															echo "</div>";
															echo "<div class='col-md-8'>";
																echo "<span>Observação: " . nl2br($conteudo['observacao']) . "</span>";
															echo "</div>";
														echo "</div>";
													echo "</div>";
												echo "</li>";
										}
									}

									if($dados_informacao_geral[0]['posicao_instalacao']){
										echo "<li class='list-group-item'>";
											echo "<div class=\"row\">";
												echo "<div class='col-md-4'>";
													echo "<strong><span>Posição de Instalação.</span></strong>";
												echo "</div>";

												echo "<div class='col-md-8'>";
													echo "<span>" . nl2br($dados_informacao_geral[0]['posicao_instalacao']) . "</span>";
												echo "</div>";
											echo "</div>";
										echo "</li>";
									}
							?>
					    	</div>
					  </div>

					  <?php
					  echo "<br>";
					  endif;
					//##########################Comercial

					//Informações Gerais
						if($dados_informacao_geral[0]['posicao_os'] != "" || ($dados_informacao_geral[0]['servico_telefonia_bool'] && $dados_informacao_geral[0]['servico_telefonia_bool'] != 2) || $dados_informacao_geral[0]['situacoes_adversas'] || $dados_informacao_geral[0]['tipo_equipamento'] || ($dados_informacao_geral[0]['roteadores_bool'] && $dados_informacao_geral[0]['roteadores_bool'] != 2) || ($dados_informacao_geral[0]['computadores_bool'] && $dados_informacao_geral[0]['computadores_bool'] != 2) || ($dados_informacao_geral[0]['suporte_dispositivos_moveis_bool'] && $dados_informacao_geral[0]['suporte_dispositivos_moveis_bool'] != 2) || ($dados_informacao_geral[0]['tv_assinatura_bool'] && $dados_informacao_geral[0]['tv_assinatura_bool'] != 2) || ($dados_informacao_geral[0]['servico_streaming_bool'] && $dados_informacao_geral[0]['servico_streaming_bool'] != 2)):
						?>

					  <div class="panel panel-warning">
					    <div class="panel-heading">
					      <h4 class="panel-title"><strong>Informações Gerais</strong></h4>
					    </div>
					    	<div class="panel-body">
					        <?php
						//}
									//foreach($dados as $dado){
											
										if($dados_informacao_geral[0]['posicao_os']){
											echo "<li class='list-group-item'>";
												echo "<div class=\"row\">";
													echo "<div class='col-md-4'>";
														echo "<strong><span>Posição de atendimento.</span></strong>";
													echo "</div>";

													echo "<div class='col-md-8'>";
														echo "<span>" . nl2br($dados_informacao_geral[0]['posicao_os']) . "</span>";
													echo "</div>";
												echo "</div>";
											echo "</li>";
										}

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

										if($dados_informacoes[0]['servico_streaming_bool'] != 2){
											echo "<li class='list-group-item'>";
												echo "<div class=\"row\">";
													echo "<div class='col-md-4'>";
														echo "<strong><span>Serviço de Streaming</span></strong>";
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
											
										if($dados_informacao_geral[0]['situacoes_adversas']){
											echo "<li class='list-group-item'>";
												echo "<div class=\"row\">";
													echo "<div class='col-md-4'>";
														echo "<strong><span>Situações adversas</span></strong>";
													echo "</div>";

													echo "<div class='col-md-8'>";
														echo "<span>" . nl2br($dados_informacao_geral[0]['situacoes_adversas']) . "</span>";
													echo "</div>";
												echo "</div>";
											echo "</li>";
										}
										
										if($dados_informacao_geral[0]['computadores_bool'] != 2){

											echo "<li class='list-group-item'>";
												echo "<div class=\"row\">";
													echo "<div class='col-md-4'>";
														echo "<strong><span>Suporte a computadores</span></strong>";
													echo "</div>";
													echo "<div class='col-md-1'>";
														echo "<span>" . $booleanos[$dados_informacao_geral[0]['computadores_bool']]."</span>";
													echo "</div>";
														echo "<div class='col-md-7'>";
															echo "<span>"  . nl2br($dados_informacao_geral[0]['computadores']) . "</span>";
														echo "</div>";
												echo "</div>";
											echo "</li>";

										}

										if($dados_informacao_geral[0]['suporte_dispositivos_moveis_bool'] != 2){

											echo "<li class='list-group-item'>";
												echo "<div class=\"row\">";
													echo "<div class='col-md-4'>";
														echo "<strong><span>Suporte a dispositivos móveis</span></strong>";
													echo "</div>";
													echo "<div class='col-md-1'>";
														echo "<span>" . $booleanos[$dados_informacao_geral[0]['suporte_dispositivos_moveis_bool']]."</span>";
													echo "</div>";
													echo "<div class='col-md-7'>";
														echo "<span>"  . nl2br($dados_informacao_geral[0]['suporte_dispositivos_moveis']) . "</span>";
													echo "</div>";
												echo "</div>";
											echo "</li>";
										}

										if($dados_informacao_geral[0]['roteadores_bool'] != 2){

											echo "<li class='list-group-item'>";
												echo "<div class=\"row\">";
													echo "<div class='col-md-4'>";
														echo "<strong><span>Suporte a roteadores</span></strong>";
													echo "</div>";
													echo "<div class='col-md-1'>";
														echo "<span>" . $booleanos[$dados_informacao_geral[0]['roteadores_bool']]."</span>";
													echo "</div>";
													echo "<div class='col-md-7'>";
														echo "<span>"  . nl2br($dados_informacao_geral[0]['roteadores']) . "</span>";
													echo "</div>";
												echo "</div>";
											echo "</li>";
										}		
												
										if($dados_informacao_geral[0]['tipo_equipamento']){
											echo "<li class='list-group-item'>";
												echo "<div class=\"row\">";
													echo "<div class='col-md-4'>";
														echo "<strong><span>Tipo de equipamento</span></strong>";
													echo "</div>";
													echo "<div class='col-md-8'>";
														echo "<span>" . nl2br($dados_informacao_geral[0]['tipo_equipamento']) . "</span>";
													echo "</div>";
												echo "</div>";
											echo "</li>";
										}
						    	?>
					    	</div>
					  </div>

					  <?php
					  echo "<br>";
					  endif;
					  ?>

					  <?php
					  	
                	// Sistema de Gestão	 
					  $dados = DBRead('', 'tb_sistema_gestao_contrato a', "INNER JOIN tb_sistema_gestao_acesso b ON a.id_sistema_gestao_contrato = b.id_sistema_gestao_contrato WHERE a.id_contrato_plano_pessoa = '".$id_contrato_plano_pessoa."'");
						if($dados):
							$integra = DBRead('', 'tb_integracao_contrato', "WHERE id_contrato_plano_pessoa = '$id_contrato_plano_pessoa'");
							if(!$integra && $perfil_usuario != '28'):
					?>

					  <div class="panel panel-info">
					    <div class="panel-heading">
					      <h4 class="panel-title"><strong><span>Sistema de gestão</span></strong></h4>
					    </div>
					    	<div class="panel-body">
					    		<?php
					    		$dados_sistema = DBRead('', 'tb_sistema_gestao_contrato a', "INNER JOIN tb_tipo_sistema_gestao b ON a.id_tipo_sistema_gestao = b.id_tipo_sistema_gestao WHERE a.id_contrato_plano_pessoa = '".$id_contrato_plano_pessoa."'", "a.*, b.nome AS nome_sistema");
					    		$contSistemas = 0;
					    		
					    			foreach($dados_sistema as $key => $dado){
							    		
							    		$dados_acesso = DBRead('', 'tb_sistema_gestao_acesso', "WHERE id_sistema_gestao_contrato = " . $dados_sistema[$key]['id_sistema_gestao_contrato']);
							    		echo "<div class='row linha-tabela-sistema-gestao'>";
							    			echo "<div class='col-md-6 tabela-sistema-gestao'>";
							    				echo "<span><strong>Sistema : </strong></span><span> " . $dado['nome_sistema'] . "</span>";
							    			echo "</div>";
							    			echo "<div class='col-md-6 tabela-sistema-gestao'>";
							    				echo "<strong>Link de acesso : </strong><span><a href='" . $dado['link'] . "' target='_blank'>" . $dado['link'] . "</a></span>";
											echo "</div>";
											echo "<div class='col-md-12 tabela-sistema-gestao'>";
												echo "<strong>Observação:</strong> ".nl2br($dado['observacao']);
											echo "</div>";

							    			$cont = 0;
							    			foreach($dados_acesso as $dado_acesso){
							    			$cont++;

								    			echo "<div class='col-md-6 tabela-sistema-gestao'>";
								    				echo '<strong>Usuário ('.$cont.'):</strong><span> ' . $dado_acesso['usuario'] . "</span>";
								    			echo "</div>";
								    			echo "<div class='col-md-6 tabela-sistema-gestao'>";
													echo '<strong>Senha ('.$cont.'):</strong><span> ' . $dado_acesso['senha'] . "</span>";
												echo "</div>";
							    			}
										echo "</div>";
										echo "<br>";
						    		}
					      		?>
					    	</div>
					  </div>
					<?php 
					endif;
					endif; 
					?>  
					</div>
				</div>
            </div>
        </div>
    </div>
</div>

<button class="btn btn-primary" onclick="topFunction()" id="myBtn" title="Voltar para o ínicio">
	<i class="fa fa-arrow-up"></i>	Voltar para o início
</button>

<script>
// When the user scrolls down 20px from the top of the document, show the button
window.onscroll = function() {scrollFunction()};

function scrollFunction() {
  if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
    document.getElementById("myBtn").style.display = "block";
  } else {
    document.getElementById("myBtn").style.display = "none";
  }
}

// When the user clicks on the button, scroll to the top of the document
function topFunction() {
  //document.body.scrollTop = 0;
  //document.documentElement.scrollTop = 0;

  $("html, body").animate({scrollTop: 0}, 300);
}

$('#accordionRelatorio').on('shown.bs.collapse', function(){
	$("#i_collapse").removeClass("fa fa-plus").addClass("fa fa-minus");
});

$('#accordionRelatorio').on('hidden.bs.collapse', function(){
	$("#i_collapse").removeClass("fa fa-minus").addClass("fa fa-plus");
})
</script>