<?php

	$id_contrato_plano_pessoa = (int)$_GET['contrato'];
	
	$contratoExiste = DBRead('', 'tb_contrato_plano_pessoa a', "INNER JOIN tb_plano b ON a.id_plano = b.id_plano LEFT JOIN tb_informacao_geral_contrato c ON a.id_contrato_plano_pessoa = c.id_contrato_plano_pessoa WHERE a.status = 1 AND a.id_contrato_plano_pessoa = '$id_contrato_plano_pessoa' AND (c.monitoramento = '1' OR b.cod_servico = 'call_monitoramento')");
	if(!$contratoExiste){
<<<<<<< HEAD
		echo "<div class='alert alert-danger text-center'><i class='fa fa-window-close' aria-hidden='true'></i> Erro! Não foi possível localizar os dados. <a href='/api/iframe?token=<?php echo $request->token ?>&view=monitoramento-busca'>Clique para voltar.</a></div>";
=======
		echo "<div class='alert alert-danger text-center'><i class='fa fa-window-close' aria-hidden='true'></i> Erro! Não foi possível localizar os dados. <a href='/api/iframe?token=".$request->token."&view=monitoramento-busca'>Clique para voltar.</a></div>";
>>>>>>> 5cfe18e1256c22b2f7585786435771d80b84680b
		exit;
	}

	$dados = DBRead('', 'tb_contrato_plano_pessoa a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa INNER JOIN tb_cidade c ON b.id_cidade = c.id_cidade INNER JOIN tb_estado d ON c.id_estado = d.id_estado INNER JOIN tb_plano e ON a.id_plano = e.id_plano WHERE a.id_contrato_plano_pessoa = '".$id_contrato_plano_pessoa."'", "b.nome, c.id_cidade, c.nome AS cidade, d.id_estado, d.nome AS estado, d.sigla, e.cor, e.nome AS nome_plano, a.nome_contrato");

	if($dados[0]['nome_contrato']){
		$nome_contrato = $dados[0]['nome'] . ' (' . $dados[0]['nome_contrato'] . ')';
	}else{
		$nome_contrato = $dados[0]['nome'];
	}

	$cidade = $dados[0]['cidade'];
	$sigla = $dados[0]['sigla'];
	$estado = $dados[0]['estado'];
	$cor = $dados[0]['cor'];
	$nome_plano = $dados[0]['nome_plano'];

	$hora_provedor = getDataHora('hora', $sigla);

	$timezone = getTimeZone($sigla);	

	if(!$id_contrato_plano_pessoa){
		echo '<div class="alert alert-danger text-center">Contrato não identificado!</div>';
		exit;
	}


	//Verifica se está empresa está habilitado para integrar o monitoramento
	$integracao_recursos = DBRead('', 'tb_integracao_recursos', "WHERE id_contrato_plano_pessoa = $id_contrato_plano_pessoa");
	$integracao_recurso = 0;
	foreach($integracao_recursos as $key => $conteudo){
		
		if($integracao_recursos[$key]['nome'] == 'integracao_monitoramento'){
			$integracao_recurso = $integracao_recursos[$key]['ativo'];
		}
	}

	$parametros = DBRead('', 'tb_parametros', "WHERE id_contrato_plano_pessoa = '$id_contrato_plano_pessoa'");

	//$temIntegracao = DBRead('', 'tb_integracao_contrato', "WHERE id_contrato_plano_pessoa = '".$id_contrato_plano_pessoa."'"); //comentado por causa do chamado 50478
?>
<style>
	.btn-opcao{
		margin-top: 5px;
		margin-bottom: 5px;
	}
	.conteudo-editor img{
        max-width: 100% !important;
        max-height: 100% !important;
    }
</style>

<script type="text/javascript">
    $(document).ready(function() {
        document.title = 'Simples V2 - Monitoramento';
    });
</script>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-7">
            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    <h3 class="panel-title text-left pull-left"> Monitoramento:</h3>
                    
                    <div class="pull-right">
		                <a href='/api/iframe?token=<?php echo $request->token ?>&view=exibe-manual&contrato=<?=$id_contrato_plano_pessoa?>' target='_blank' class='btn-xs btn btn-info'><i class="fa fa-file-text-o" aria-hidden="true"></i> Manual da empresa</a>
		                <a href="/api/iframe?token=<?php echo $request->token ?>&view=exibe-quadro-informativo&contrato=<?=$id_contrato_plano_pessoa?>" target='_blank' class='btn-xs btn btn-info'><i class="fa fa-info" aria-hidden="true"></i> Quadro informativo</a>
		            </div>
	                
                </div>

                <div class="panel-body">

				<?php			
				$sistemas = DBRead('', 'tb_sistema_gestao_contrato a', "INNER JOIN tb_sistema_gestao_acesso b ON a.id_sistema_gestao_contrato = b.id_sistema_gestao_contrato INNER JOIN tb_tipo_sistema_gestao c ON a.id_tipo_sistema_gestao = c.id_tipo_sistema_gestao WHERE a.id_contrato_plano_pessoa = $id_contrato_plano_pessoa GROUP BY a.id_sistema_gestao_contrato", "c.nome, a.id_sistema_gestao_contrato, a.observacao, a.link");
				?>

				<!-- Modal para apresentar usuários e senhas de contratos que tem mais de um sistema de gestão. -->
				<div id="modalSistemas" class="modal fade" role="dialog">
				  <div class="modal-dialog modal-lg">

					<!-- Modal content-->
					<div class="modal-content">
					  <div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title">Sistemas de gestão</h4>
					  </div>
					  <div class="modal-body">
						  <table class="table">
							  <thead>
								  <tr>
									  <th>Nome</th>
									  <th>Usuário</th>
									  <th>Senha</th>
									<th>Observação</th>
								  </tr>
							  </thead>
							  <tbody>
						  <?php
						  foreach($sistemas as $conteudo):   
                            $usuarios = DBRead('', 'tb_sistema_gestao_acesso', "WHERE id_sistema_gestao_contrato = '".$conteudo['id_sistema_gestao_contrato']."' ORDER BY contador ASC");

                            $usuarios_maior = DBRead('', 'tb_sistema_gestao_acesso', "WHERE id_sistema_gestao_contrato = '".$conteudo['id_sistema_gestao_contrato']."' ORDER BY contador DESC LIMIT 1");
                            
                            if($usuarios_maior){
                                $contador = $usuarios_maior[0]['contador']+1;
                            }else{
                                $contador = 1;
                            }
                            $dados_contador = array(
                                'contador' => $contador
                            );

                            DBUpdate('', 'tb_sistema_gestao_acesso', $dados_contador, "id_sistema_gestao_acesso = '".$usuarios[0]['id_sistema_gestao_acesso']."'");

                        ?>
                        <tr>
                            <td><a target="_blank" href="<?=$conteudo['link']?>"><?=$conteudo['nome']?></a></td>
                            <td><input class="form-control input-sm" type="text" readonly value="<?=$usuarios[0]['usuario']?>"></td>
                            <td><input class="form-control input-sm" type="text" readonly value="<?=$usuarios[0]['senha']?>"></td>
                            <td><?=$conteudo['observacao']?></td>
                        </tr>
						<?php	
						endforeach;
						?>
							</tbody>
						</table>
					  </div>
					</div>

				  </div>
				</div>

				<div class="row">
					<div class="col-lg-12">
						<div class="btn-group btn-group-justified" role="group" aria-label="..." '="">
							<div class="btn-group" role="group">
							<?php

							if($temIntegracao){
								echo '<a class="btn btn-default" style="cursor: inherit; border-left: 20px solid '.$cor.'; text-shadow: 0 0 0 !important; background-image: none !important; background-color: #d9d9d9 !important; padding-top: 16px; padding-bottom: 16px;">';
								$icone_botao_sistema = '<i class="fa fa-joomla" aria-hidden="true"></i> ';
							}else{
								if($sistemas && $parametros[0]['registra_monitormento_sistema_gestao'] == 1){								
									echo "<a data-toggle=\"modal\" data-target=\"#modalSistemas\" class='btn btn-default' style='border-left: 20px solid ".$cor."; text-shadow: 0 0 0 !important; background-image: none !important; background-color: #d9d9d9 !important; padding-top: 16px; padding-bottom: 16px;' onMouseOver=\"this.style.color='#337ab7'\" onMouseOut=\"this.style.color='#000000'\">";
									$icone_botao_sistema = '<i class="fa fa-external-link" aria-hidden="true"></i> ';
								}else{
									echo '<a class="btn btn-default" style="cursor: inherit; border-left: 20px solid '.$cor.'; text-shadow: 0 0 0 !important; background-image: none !important; background-color: #d9d9d9 !important; padding-top: 16px; padding-bottom: 16px;">';
									$icone_botao_sistema = '';
								}
							}
							?>
									<span style="font-size: 13px; display: inline;" class="pull-left"><?=$id_contrato_plano_pessoa?></span>
									<span><?php echo $icone_botao_sistema.''.$nome_contrato; ?></span>
								</a>
							</div>
							<div class="btn-group" role="group">
								<div style="text-shadow: 0 0 0 !important; background-image: none !important; background-color: #d9d9d9 !important; padding: 6px 0 6px 0; text-align: center; border: 1px solid #ccc;">
									<strong>Localidade:</strong>
									<span><?=$cidade.", ".$sigla?></span>
									<input type="hidden" id="timezone" value="<?=$timezone?>">
									<br>
									<strong>Hora no provedor:</strong>
									<span id="hora-provedor"><?=$timezone?></span>
								</div>
							</div>							
						</div>							                	
					</div>
				</div>

				<?php
                    $data_atual = getDataHora();

                    /*
                        * Adiciona os alertas de feriados.
                    */                    
                    $dados_feriados = DBRead('','tb_feriado', "WHERE data = '".substr($data_atual, 5,5)."' AND (tipo = 'Nacional' OR (tipo = 'Estadual' AND id_estado = '".$dados[0]['id_estado']."') OR (tipo = 'Municipal' AND id_cidade = '".$dados[0]['id_cidade']."'))");
                    if($dados_feriados){
                        foreach ($dados_feriados as $conteudo) {
                            echo "<hr><div class='row'>";
                            echo '<div class="col-lg-12">';
                            echo "<div class='alert alert-info text-center' style='margin-bottom: 0' role='alert'>";
                            echo "<div class='row'>";
                            echo "<div class='col-xs-12'>";
                            echo "<span><strong>Feriado ".strtolower($conteudo['tipo']).": </strong>".$conteudo['nome']."</span>";
                            echo "</div>";
                            echo "</div>";		
                            echo "</div>";
                            echo "</div>";
                            echo "</div>";
                        }
                    }

					/*
						* Adiciona os alertas definidos para aparecer durante o monitoramento.
					*/					
					$dados_alerta = DBRead('', 'tb_alerta', "WHERE id_contrato_plano_pessoa = '".$id_contrato_plano_pessoa."' AND (exibicao = 4 OR exibicao = 5) AND data_inicio <= '".$data_atual."' AND (data_vencimento IS NULL OR data_vencimento > '".$data_atual."')");

					if($dados_alerta){
						
						foreach ($dados_alerta as $conteudo) {
							echo "<hr><div class='row'>";
							echo '<div class="col-lg-12">';
							echo "<div class='alert alert-warning text-center' style='margin-bottom: 0' role='alert'>";
							echo "<div class='row'>";
							echo "<div class='col-xs-12'>";
							echo "<span>".nl2br($conteudo['conteudo'])."</span>";
							echo "</div>";
							echo "</div>";
							echo "<hr><div class='row'>";
							echo "<div class='col-xs-12'>";
							echo "<div class='pull-left'>";
							echo "<strong>Início em:</strong> ".converteDataHora($conteudo['data_inicio']);
							echo "</div>";
							echo "<div class='pull-right'>";
							echo "<strong>Vence em:</strong> ".converteDataHora($conteudo['data_vencimento']);
							echo "</div>";
							echo "</div>";
							echo "</div>";
							echo "</div>";
							echo "</div>";
							echo "</div>";
						}
						
					}
				?>

				<hr>
                <form method="post" action="/api/ajax?class=Monitoramento.php" id="monitoramento_form" style="margin-bottom: 0;">
					<input type="hidden" name="token" value="<?php echo $request->token ?>">
					<div id="form-dados-monitoramento">
                		<input type="hidden" name="contrato" value="<?= $id_contrato_plano_pessoa ?>" />
						<input type="hidden" id="getdatahora" value="<?= converteData(getDataHora('data')) ?>" />
							
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label>*Data:</label>
									<input type="text" id="data_queda" class="form-control input-sm date calendar hasDatepicker" name="data_queda" autocomplete="off" value="" required placeholder="dd/mm/aaaa" maxlength="10">
								</div><!-- end form-group -->
							</div><!-- end col-md-3 -->

							<div class="col-md-4">
								<div class="form-group">
									<label>*Hora:</label>
									<input type="time" class="form-control input-sm" id="hora_queda" value="" name="hora_queda" autocomplete="off" required="">
								</div><!-- end form-group -->
							</div><!-- end col-md-3 -->

							<div class="col-md-4">
								<div class="form-group">
									<label>*Contato com o técnico:</label>
									<select class="form-control input-sm" id="status_contato" name="status_contato" autocomplete="off" required="">
										<option></option>
										<option value="1">COM sucesso</option>
										<option value="2">SEM sucesso</option>
									</select>
								</div><!-- end form-group -->
							</div><!-- end col-md-3 -->
						</div><!-- end row -->

                        <div class="row" id="result_status" style="display: none;">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>*Nome do técnico:</label>
                                    <input name="nome_tecnico" autofocus id="nome_tecnico" type="text" class="form-control input-sm" autocomplete="off">
                                </div><!-- end form-group -->
                            </div><!-- end col-md-6 -->

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>*Telefone:</label>
                                    <input name="telefone" type="text" id="telefone" class="form-control input-sm phone" pattern="\([0-9]{2}\)[\s][0-9]{4,5}-[0-9]{4}" placeholder="(00) 00000-0000" maxlength="15" autocomplete="off">
                                </div><!-- end form-group -->
                            </div><!-- end col-md-6 -->
						</div><!-- end row -->

						<div class="row">
							<div class="col-md-12">
								<label>Informações adicionas:</label>
								<textarea name="informacao" id="informacoes_adicionais" class="form-control"></textarea>
							</div>
						</div><!-- end row -->

						<div class="row">
							<br>
							<div class="col-md-12">
                                <div class="panel panel-default" style="margin-bottom:0px;">
                                    <div class="panel-heading clearfix">
                                        <h3 class="panel-title text-left pull-left">POP(s):</h3>
                                    </div>
                                    <div class="panel-body">
                                        <div class='table-responsive'>
                                            <table class='table table-bordered' style='font-size: 14px;'>
                                                <thead>
                                                    <tr>
                                                        <th class='col-md-11'>*POP</th>
                                                        <th class='col-md-1'>Ação</th>
                                                    </tr>
                                                </thead>
                                                <tbody class='pops'>
													<tr>
														<td><input required class='form-control input-sm POP nomes-pops' name='nomes[]' id="pop1" autocomplete='off' required/></td><td><button type="button" class='center-block btn btn-default btn-sm' role='button'><i class='fa fa-ban' aria-hidden='true'></i></button>
														</td>
													</tr>
                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <td></td>
                                                        <td><button type="button" class='center-block btn btn-warning btn-sm' id='adiciona-POP' role='button'><i class='fa fa-plus' aria-hidden='true'></i></button></td>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
						</div><!-- end row -->
					</div>

					<div id="dados-fixar-os" style="display: none; margin-left: 15px;">
						<div class="row">
							<div class="col-md-12">
								<div class="row" style="margin-bottom: 10px;">
									<strong>Data e hora da queda:</strong>&nbsp;<span id="dataHoraOS"></span>
								</div>
								<div class="row" style="margin-bottom: 10px;">
									<strong>Contato com o técnico:</strong>&nbsp;<span id="contatoTecnicoOS"></span>
								</div>
								<div class="row" id='row-nome-tecnico' style="margin-bottom: 10px;">
									<strong>Nome do técnico:</strong>&nbsp;<span id="nomeTecnicoOS"></span>
								</div>
								<div class="row" id='row-telefone-tecnico' style="margin-bottom: 10px;">
									<strong>Telefone:</strong>&nbsp;<span id="telefoneOS"></span>
								</div>
								<div class="row" style="margin-bottom: 10px;">
									<strong>Informações adicionais:</strong>&nbsp;<span id="informacoesOS"></span>
								</div>
								<div class="row" style="margin-bottom: 10px;">
									<strong>POP(s):</strong>&nbsp;<span id="nomePOPOS"></span>
								</div>
							</div>
						</div>
					</div>
					<hr>
					<div class="row" > 
						<div class="col-md-12" style="display: inline;">
							<button type="button" id="buttom-form-dados-monitoramento" class="btn btn-default btn-xs">
								<i class="fa fa-check"></i> Fixar OS
							</button>
							<button type="button" class="btn btn-warning btn-xs" id="clipboard" style="display: none;">
								<i class="fa fa-clone"></i> Copiar
							</button>
						</div>
					</div>

					<div class="row" id="bloco-integracao-monitoramento">
					<?php
						
						if($temIntegracao && $integracao_recurso == 1){
							include __DIR__.'/../integracoes/monitoramento-form-ixc.php';
						}
					?>
					</div>

					</div>
                    <div class="panel-footer">
                        <div class="row">
							<div class="col-md-12 text-center">
								<div class="btn-group" role="group" aria-label="...">
                                	<input type="hidden" id="operacao" value="inserir" name="inserir">
                                	<button type="submit" class="btn btn-primary" name="salvar" id="ok" ><i class="fa fa-floppy-o"></i> Gravar</button>
								</div>
							</div>
                        </div>
               		 </form>
                </div>
            </div>
        </div>
        <div class="col-md-5">
			<div class="row">
				<div class="panel panel-default noprint">
	                <div class="panel-heading clearfix">
	                    <h3 class="panel-title text-left pull-left" style="margin-top: 2px;">Monitoramentos:</h3>
	                    <div class="panel-title text-right pull-right"><button data-toggle="collapse" data-target="#accordionRelatorio" class="btn btn-xs btn-info collapsed" type="button" title="Visualizar filtros" aria-expanded="false"><i id="i_collapse" class="fa fa-plus"></i></button></div>
	                </div>
	                <div id="accordionRelatorio" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
	                	<div class="panel-body">
							<div class="row">
								<div class="col-md-4">
									<label>Empresa:</label>
									<select class="form-control input-sm" id="empresa" onchange="call_busca_ajax()">
										<option value="0">Somente <?=$nome_contrato?></option>
										<option value="1">Visualizar todas</option>
									</select>
								</div>
								<div class="col-md-4">
									<label>Data:</label>
                            		<input type="text" class="form-control input-sm date calendar" onchange="call_busca_ajax()" name="data" id="data">
								</div>
								<div class="col-md-4">
									<label>Filtro:</label>
									<input type="text" class="form-control input-sm" id="filtro" onKeyUp="call_busca_ajax()">
								</div>
							</div>
							<hr>
							<div class="row">
								<div class="col-md-12">
									<div id="resultado_busca"></div>
								</div>
							</div>
						</div>                				        
	            	</div>
	            </div>
			</div>
			<div class="row">
				<div class="panel panel-info">
					<div class="panel-heading clearfix">
						<h3 class="panel-title text-left pull-left">Informações:</h3>
					</div>
					<div class="panel-body" style="padding-bottom: 0;">
						<?php
						$plantonistas = DBRead('', 'tb_plantonista_contrato', "WHERE id_contrato_plano_pessoa = $id_contrato_plano_pessoa");
						//var_dump($plantonistas);
						
						if($plantonistas):
							?> 
							<div class="panel panel-success">
							<div class="panel-heading">
								<h4 class="panel-title"><strong>Plantonistas</strong></h4>
							</div>
								<div class="panel-body">
									<?php
										echo "<div class='row'>";
											echo "<div class='col-md-12 conteudo-editor'>";
												echo "<span> " . $plantonistas[0]['tabela'] . "</span>";
											echo "</div>";
										echo "</div>";
									?>
								</div>
							</div>
							<?php 
							echo "<br>";
							endif;

							if($parametros[0]['ramal_retorno'] || $parametros[0]['prefixo_telefone']):
							?>
							<div class="panel panel-success painel-quadro-informativo">
								<div class="panel-heading clearfix">
									<h4 class="panel-title"><strong>Dados de retorno</strong></h4>
								</div>
								<div class="panel-body">
										<?php
										if($parametros[0]['ramal_retorno']){
											echo "<li class='list-group-item'>";
												echo "<div class=\"row\">";
													echo "<div class='col-md-4'>";
														echo "<strong><span>Número de retorno:</span></strong>";
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
														echo "<strong><span>Prefixo de retorno:</span></strong>";
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

							$dados_informacao_geral = DBRead('', 'tb_informacao_geral_contrato', "WHERE id_contrato_plano_pessoa = '".$id_contrato_plano_pessoa."'");

							//$dados_horario = DBRead('', 'tb_horario_contrato a', "INNER JOIN tb_horario b ON a.id_horario_contrato = b.id_horario_contrato WHERE a.id_contrato_plano_pessoa = '".$id_contrato_plano_pessoa."' AND a.tipo = 1 ORDER BY dia ASC");
							$dados_horario = DBRead('', 'tb_horario_contrato', "WHERE id_contrato_plano_pessoa = '".$id_contrato_plano_pessoa."' AND tipo = 1");

							$dia = array(
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
								"12" => "Sábado",
								"13" => "Seg. a Qui"
							);

							if($dados_informacao_geral[0]['horarios_monitoramento']):
						?>

						<div class="panel panel-success" style='margin-top: 5px;'>
							<div class="panel-heading">
							<h4 class="panel-title"><strong>Horários</strong></h4>
							</div>
								<div class="panel-body">
									<?php

										if($dados_informacao_geral[0]['horarios_monitoramento']){
											echo "<li class='list-group-item'>";
												echo "<div class=\"row\">";
													echo "<div class='col-md-4'>";
														echo "<strong><span>Horário monitoramento:</span></strong>";
													echo "</div>";
													echo "<div class='col-md-8'>";
														echo "<span>" . $dados_informacao_geral[0]['horarios_monitoramento'] . "</span>";
													echo "</div>";
												echo "</div>";

												echo "<hr />";

												echo "<div class=\"row\">";
													echo "<div class='col-md-4'>";
														echo "<strong><span>Horários que a empresa está aberta</span></strong>";
													echo "</div>";
													echo "<div class='col-md-8'>";
													
														if($dados_horario){
															$horarios = DBRead('', 'tb_horario', "WHERE id_horario_contrato = ".$dados_horario[0]['id_horario_contrato']." ORDER BY dia ASC");
															if($horarios){
																foreach($horarios as $conteudo){
																	echo "<span>".$dia[$conteudo['dia']].": ".$conteudo['hora_inicio']." até ".$conteudo['hora_fim']."</span>";
																	echo "<br>";
																}
															}
														}
														
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

					</div>
				</div>
			</div>
        </div>
    </div>
</div>

<?php
//Verifica se existe integração com o sistema de gestão do cliente, se sim, importa a view monitoramento-form-ixc
//$integra = DBRead('', 'tb_integracao_contrato', "WHERE id_contrato_plano_pessoa = $id_contrato_plano_pessoa"); //comentado por causa do chamado 50478

if($integra[0]['id_integracao'] == 1 && $integracao_recurso == 1){
	require_once "integracoes/monitoramento-form-ixc.php";
}
?>

<script src="inc/ckeditor/ckeditor.js"></script>

<script>
	var zone = $('#timezone').val();
    var myVar = setInterval(myTimer ,1000);
	
	function myTimer(){
		var d = new Date(), displayDate;
			if(navigator.userAgent.toLowerCase().indexOf('firefox') > -1){
			    displayDate = d.toLocaleTimeString('pt-BR');
			}else{
			    displayDate = d.toLocaleTimeString('pt-BR', {timeZone: zone});
			}
			document.getElementById("hora-provedor").innerHTML = displayDate;
		}

    var segundo = 0+"0";
	var minuto = 0+"0";
	var hora = 0+"0";

	$("#status_contato").on('change', function(){
		var selecionado = $('#status_contato').find(":selected").val();

		if(selecionado == '1'){
			$('#result_status').show();
			$('#nome_tecnico').prop('required',true);
			$('#telefone').prop('required',true);
		}else{
			$('#result_status').hide();
			$('#nome_tecnico').prop('required',false);
			$('#telefone').prop('required',false);
		}
	});

	$("#adiciona-POP").on('click', function(){
        $("tbody.pops").append("<tr><td><input required class='form-control input-sm POP nomes-pops' name='nomes[]' autocomplete='off' /></td><td><button class='center-block btn btn-danger btn-sm removeLinha' role='button'><i class='fa fa-trash-o' aria-hidden='true'></i></button></td></tr>");

        $(".POP").focus();
	});
	
    $(document).on('click', '.removeLinha', function(){
        if(confirm('Deseja excluir o POP?')) {
			$(this).parent().parent().remove();
		}
		return false;
    });

	$(document).on('click', '#buttom-form-dados-monitoramento', function(){
		var div = $('#form-dados-monitoramento').css('display');
		
		var data = $("input[name=data_queda]").val();
        var hora = $('#hora_queda').val();
        var status = $('#status_contato').val();
		var getdatahora = $('#getdatahora').val();
        var empresa = $('#nome-empresa-os').val();
		var informacoes = $('#informacoes_adicionais').val();
		var nome_tecnico = $('#nome_tecnico').val();
		var telefone = $('#telefone').val();
		nomes_pops = '';

		var cont = telefone.length;

		$(".nomes-pops").each(function() {
			nomes_pops += "<br>" + $(this).val();
		});

		if(data == ''){
			alert('Informe a data!');
			return false;
		}

		if(hora == ''){
			alert('Informe a hora!');
			return false;
		}

		if(status == ''){
			alert('Informe o contato com o técnico!');
			return false;
		}

		if(status == 1 && nome_tecnico == ''){
			alert('Verifique o nome do técnico!');
			$('#nome_tecnico').focus();
			return false;
		}

		if(status == 1 && cont < 14){
			alert('Verifique o número do telefone do técnico!');
			$('#telefone').focus();
			return false;
		}		

		if(nomes_pops == '<br>'){
			alert('Informe pelo menos um POP!');
			return false;
		}

		if(status == 1){
			var contato = 'Com sucesso';
			$('#row-nome-tecnico').show();
			$('#row-telefone-tecnico').show();
			$('#nomeTecnicoOS').text(nome_tecnico);
			$('#telefoneOS').text(telefone.replace(/[^0-9]/g,''));
		}else{
			$('#row-nome-tecnico').hide();
			$('#row-telefone-tecnico').hide();
			var contato = 'Sem sucesso';
		}	

		$('#dataHoraOS').text(data+' '+hora);
		$('#contatoTecnicoOS').text(contato);
		
		$('#informacoesOS').html('<br>'+informacoes.replace(/\n/g,'<br>'));
		$('#nomePOPOS').html(nomes_pops);

		if(div == 'block'){
			$('#form-dados-monitoramento').css('display', 'none');
			$('#dados-fixar-os').css('display', 'block');
			$('#clipboard').css('display', 'inline');
			$('#buttom-form-dados-monitoramento').html('<i class="fa fa-pencil"></i> Editar OS');
			
		}else{
			$('#form-dados-monitoramento').css('display', 'block');
			$('#dados-fixar-os').css('display', 'none');
			$('#clipboard').css('display', 'none');
			$('#buttom-form-dados-monitoramento').html('<i class="fa fa-check"></i> Fixar OS');
		}
	});

	$(function () {
		$('[data-toggle="popover"]').popover();

		$('#clipboard').popover({
		content: "Copiado para a área de transferência!"
		}).click(function() {

		setTimeout(function() {
			$('#clipboard').popover('hide');
		}, 1800);
		});
	});

	$("#clipboard").on('click', function(){
		
		var data = $("input[name=data_queda]").val();
        var hora = $('#hora_queda').val();
        var status = $('#status_contato').val();
		var getdatahora = $('#getdatahora').val();
		var informacoes = $('#informacoes_adicionais').val();
		var nome_tecnico = $('#nome_tecnico').val();
		var telefone = $('#telefone').val();
		nomes_pops = '';

		$(".nomes-pops").each(function() {
			nomes_pops += '\n' + $(this).val();
		});
		

		if(status == 1){
			var contato = 'Com sucesso';
		}else{
			var contato = 'Sem sucesso';
		}

		var textoOS = 
		'Data e hora da queda: ' + data+' '+hora + '\n' +
		'Contato com o técnico: ' + contato + '\n';

		if(status == 1){
			textoOS += 			
			'Nome do técnico: ' + nome_tecnico + '\n' +
			'Telefone: ' + telefone.replace(/[^0-9]/g,'') + '\n';

		}

		textoOS += 
		'\nInformações adicionais: \n' + informacoes + '\n\n' +
		'POPs: ' + nomes_pops + '\n';

		var $temp = $("<textarea>");  
		$("body").append($temp);  
		$temp.val(textoOS).select(); 
	
		document.execCommand("copy");  
		$temp.remove();
	});

	$('.btn-opcoes-atendimento.btn-info').on('click', function(){
		$('.btn-opcoes-atendimento').html("<i class='fa fa-times' aria-hidden='true'></i> Fechar opções").removeClass('btn-info').addClass('btn-danger');
	});

	$('#collapseOpcoes').on('hide.bs.collapse', function(){	    	
		$('.btn-opcoes-atendimento').html("<i class='fa fa-cog' aria-hidden='true'></i> Opções").removeClass('btn-danger').addClass('btn-info');
	});

	$('form').on('keydown', function(e) {
		if (e.which === 13 && !$(e.target).is('textarea')) {
			e.preventDefault();
		}
	});

	$(document).on('submit', '#monitoramento_form', function(){
        var data = $("input[name=data_queda]").val();
        var hora = $('#hora_queda').val();
        var status = $('#status_contato').val();
		var nome_tecnico = $('#nome_tecnico').prop('required',true);
		var telefone = $('#telefone').prop('required',true);
		var pop = $('#pop').val();
		var getdatahora = $('#getdatahora').val();
        var id_contrato_plano_pessoa = $('#id_contrato_plano_pessoa').val();
		
		if(data > getdatahora){
			alert('Data inválida! Informe uma data passada!');
            return false;
		}

        if(data == ""){
            alert('Informe uma data!');
            return false;
        }

        if(hora == ""){
            alert('Informe a hora!');
            return false;
        }

        if(status == ""){
            alert('Informe um status!');
            return false;
        }

		if(pop == ""){
            alert('Informe no mínimo 1 POP!');
            return false;
        }

		if(nome_tecnico == ""){
            alert('Informe o nome do técnico!');
            return false;
        }

		if(telefone == ""){
            alert('Informe o nome do técnico!');
            return false;
        }

		if(!confirm('Deseja salvar o monitoramento?')){
            return false;
        }
      
        modalAguarde();
    });

	function call_busca_ajax(pagina){
        var inicia_busca = 1;
        var id_contrato_plano_pessoa = '<?=$id_contrato_plano_pessoa?>';
        var visualizar = $('#visualizar').val();
        var filtro = $('#filtro').val();
        var data = $("[name=data]").val();
		var empresa = $('#empresa').val();

        if(pagina === undefined){
        	pagina = 1;
        }
        var parametros = {
            'visualizar': visualizar,
            'id_contrato_plano_pessoa': id_contrato_plano_pessoa,
            'filtro': filtro,
            'pagina': pagina,
            'data': data,
			'empresa': empresa
        };
        busca_ajax('<?= $request->token ?>' , 'MonitoramentosRealizadosBusca', 'resultado_busca', parametros);
    }

	$('#accordionRelatorio').on('shown.bs.collapse', function(){
       $("#i_collapse").removeClass("fa fa-plus").addClass("fa fa-minus");
    });
    $('#accordionRelatorio').on('hidden.bs.collapse', function(){
       $("#i_collapse").removeClass("fa fa-minus").addClass("fa fa-plus");
    });

    $(document).on('click', '.troca_pag', function(){
        call_busca_ajax($(this).attr('atr-pagina'));
    });

    call_busca_ajax();
    
</script>