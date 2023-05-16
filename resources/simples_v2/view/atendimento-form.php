<?php

$id_atendimento = (int) $_GET['id_atendimento'];
$id_arvore = (isset($_GET['id_arvore'])) ? $_GET['id_arvore'] : 1;
$id_falha = (isset($_GET['id_falha'])) ? $_GET['id_falha'] : 0;

$dados_atendimento = DBRead('', 'tb_atendimento', "WHERE id_atendimento = '$id_atendimento'", "protocolo, contato, fone1, fone2, assinante, cpf_cnpj, dado_adicional, descricao_dado_adicional, id_contrato_plano_pessoa, id_usuario, gravado");

$protocolo = $dados_atendimento[0]['protocolo'];
$contato = $dados_atendimento[0]['contato'];
$fone1 = $dados_atendimento[0]['fone1'];
$fone2 = $dados_atendimento[0]['fone2'];
$assinante = $dados_atendimento[0]['assinante'];
$cpf_cnpj = $dados_atendimento[0]['cpf_cnpj'];

$dado_adicional = $dados_atendimento[0]['dado_adicional'];
$descricao_dado_adicional = $dados_atendimento[0]['descricao_dado_adicional'];

$id_contrato_plano_pessoa = $dados_atendimento[0]['id_contrato_plano_pessoa'];

//------------------------------- buscar grupo de chat e operadores --------------------------------

//$dados_grupo = DBRead('', 'tb_grupo_atendimento_chat_contrato a', "INNER JOIN tb_grupo_atendimento_chat_operador b ON a.id_grupo_atendimento_chat = b.id_grupo_atendimento_chat INNER JOIN tb_usuario c ON b.id_usuario = c.id_usuario INNER JOIN tb_pessoa d ON c.id_pessoa = d.id_pessoa WHERE id_contrato_plano_pessoa = $id_contrato_plano_pessoa", 'c.id_usuario, d.nome');

if ($dados_grupo) {
	$operador_grupo = '';
	foreach ($dados_grupo as $conteudo_operadores) {
		if($dados_grupo != ''){
			$operador_grupo .= ', '.$conteudo_operadores['nome'];
		}else{
			$operador_grupo .= ''.$conteudo_operadores['nome'];
		}
	}
	if(sizeof($dados_grupo) > 1){
		$operador_grupo = "Os responsáveis pelo atendimento via texto no momento: ".$operador_grupo."";
	}else{
		$operador_grupo = "O responsável pelo atendimento via texto no momento: ".$operador_grupo."";
	}

	echo "<div class=\"container-fluid text-center\"><div class='alert alert-info alert-dismissible' role='alert' style='text-align: center'><strong>".$operador_grupo."</strong></div></div>";
}

//------------------------------- buscar grupo de chat e operadores --------------------------------

//_______________________________VERIFICA RESPONSAVEL E INSERE ALERTA______________________________
$dados_responsavel_atendimento = DBRead('', 'tb_responsavel_atendimento a',"INNER JOIN tb_usuario b ON a.id_usuario = b.id_usuario INNER JOIN tb_pessoa c ON b.id_pessoa = c.id_pessoa WHERE a.status = 1 AND a.tipo = 0", "c.nome");

if($dados_responsavel_atendimento){
	$responsaveis = '';
	foreach ($dados_responsavel_atendimento as $conteudo_responsavel_atendimento) {
		if($responsaveis != ''){
			$responsaveis .= ', '.$conteudo_responsavel_atendimento['nome'];
		}else{
			$responsaveis .= ''.$conteudo_responsavel_atendimento['nome'];
		}
	}
	if(sizeof($dados_responsavel_atendimento) > 1){
		$notificacao = "Os responsáveis pelas ajudas dos atendimentos no momento: ".$responsaveis."";
	}else{
		$notificacao = "O responsável pela ajuda do atendimento no momento: ".$responsaveis."";
	}

	echo "<div class=\"container-fluid text-center\"><div class='alert alert-warning alert-dismissible' role='alert' style='text-align: center'><strong>".$notificacao."</strong></div></div>";
}

//$dados_responsavel_atendimento_texto = DBRead('', 'tb_responsavel_atendimento a',"INNER JOIN tb_usuario b ON a.id_usuario = b.id_usuario INNER JOIN tb_pessoa c ON b.id_pessoa = c.id_pessoa WHERE a.status = 1 AND a.tipo = 1 AND a.id_contrato_plano_pessoa = '".$id_contrato_plano_pessoa."' ", "c.nome");

if($dados_responsavel_atendimento_texto){
	$responsaveis = '';
	foreach ($dados_responsavel_atendimento_texto as $conteudo_responsavel_atendimento) {
		if($responsaveis != ''){
			$responsaveis .= ', '.$conteudo_responsavel_atendimento['nome'];
		}else{
			$responsaveis .= ''.$conteudo_responsavel_atendimento['nome'];
		}
	}
	if(sizeof($dados_responsavel_atendimento_texto) > 1){
		$notificacao = "Os responsáveis pelo atendimento via texto no momento: ".$responsaveis."";
	}else{
		$notificacao = "O responsável pelo atendimento via texto no momento: ".$responsaveis."";
	}

	echo "<div class=\"container-fluid text-center\"><div class='alert alert-info alert-dismissible' role='alert' style='text-align: center'><strong>".$notificacao."</strong></div></div>";
}

if (!$id_falha) {
	$dados_arvore = DBRead('', 'tb_arvore a', "INNER JOIN tb_arvore_contrato b ON a.id_arvore = b.id_arvore INNER JOIN tb_resposta c ON a.id_resposta = c.id_resposta WHERE a.id_pai = '$id_arvore' AND id_contrato_plano_pessoa = '$id_contrato_plano_pessoa' ORDER BY a.cliques DESC", "a.id_arvore, c.nome");

	if (!$dados_arvore) {
		$dados_valida_arvore = DBRead('', 'tb_arvore', "WHERE id_arvore = '$id_arvore'");

		if (!$dados_valida_arvore) {
			echo '<div class="alert alert-danger text-center">Passo inexistente!</div>';
			exit;
		}
	}
} else {
	$dados_falha =  DBRead('', 'tb_tipo_falha_atendimento', "WHERE id_tipo_falha_atendimento = '" . $id_falha . "' AND status = '1' AND exibicao != '1'", "resolvido, texto_os");

	if (!$dados_falha) {
		echo '<div class="alert alert-danger text-center">Falha inexistente!</div>';
		exit;
	}else{
        if($dados_falha[0]['resolvido'] == 1){
            $dados_atendimento_falha_resolvido = array(            
                'resolvido' => 1
            );    
            DBUpdate('', 'tb_atendimento', $dados_atendimento_falha_resolvido, "id_atendimento = '".$id_atendimento."'");
        }
    }
	$dados_arvore = NULL;
}

$dados_contrato = DBRead('', 'tb_contrato_plano_pessoa a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa INNER JOIN tb_cidade c ON b.id_cidade = c.id_cidade INNER JOIN tb_estado d ON c.id_estado = d.id_estado INNER JOIN tb_plano e ON a.id_plano = e.id_plano WHERE a.id_contrato_plano_pessoa = '" . $id_contrato_plano_pessoa . "'", "a.nome_contrato, b.nome, c.id_cidade, c.nome AS cidade, d.id_estado, d.nome AS estado, d.sigla, e.cor, e.nome AS nome_plano");
if ($dados_contrato[0]['nome_contrato']) {
	$nome_contrato = $dados_contrato[0]['nome'] . ' (' . $dados_contrato[0]['nome_contrato'] . ')';
} else {
	$nome_contrato = $dados_contrato[0]['nome'];
}

$cidade = $dados_contrato[0]['cidade'];
$sigla = $dados_contrato[0]['sigla'];
$estado = $dados_contrato[0]['estado'];
$cor = $dados_contrato[0]['cor'];
$nome_plano = $dados_contrato[0]['nome_plano'];

$hora_provedor = getDataHora('hora', $sigla);

$timezone = getTimeZone($sigla);

$dados_parametros = DBRead('', 'tb_parametros', "WHERE id_contrato_plano_pessoa = '" . $id_contrato_plano_pessoa . "'", "solicitacao_cpf, exibir_protocolo, prefixo_telefone, retorno_valido_para, atendimento_via_texto, enviar_email");

$solicitacao_cpf_cnpj = $dados_parametros[0]['solicitacao_cpf'];
$exibir_protocolo = $dados_parametros[0]['exibir_protocolo'] ? $dados_parametros[0]['exibir_protocolo'] : 0;
$prefixo_telefone = $dados_parametros[0]['prefixo_telefone'];
$retorno_valido_para = $dados_parametros[0]['retorno_valido_para'];

if ($dados_atendimento[0]['id_usuario'] != $_SESSION['id_usuario']) {
	echo '<div class="alert alert-danger text-center">Você não é o proprietário deste atendimento!</div>';
	exit;
} else if ($dados_atendimento[0]['gravado']) {
	echo '<div class="alert alert-danger text-center">O atendimento ja foi gravado!</div>';
	exit;
}


//Verifica se existe integração com o sistema de gestão do cliente, se sim, importa a view atendimento-form-ixc
$integra = DBRead('', 'tb_integracao_contrato', "WHERE id_contrato_plano_pessoa = $id_contrato_plano_pessoa");
if ($integra[0]['id_integracao'] == 1) {
	require_once "integracoes/atendimento-form-ixc.php";
}
$teste = $integra[0]['id_integracao'];
?>
<style>
	.btn-opcao {
		margin-top: 5px;
		margin-bottom: 5px;
	}

	.conteudo-editor img {
		max-width: 100% !important;
		max-height: 100% !important;
	}
</style>
<script type="text/javascript">
	$(document).ready(function() {
		document.title = 'Simples V2 - Atendimento';
	});
</script>

<link href='inc/ckeditor/css/select2.min.css' />
<script src="inc/ckeditor/ckeditor.js"></script>

<div class="container-fluid">
	<div class="row">
		<div class="col-md-7">
			<form method="post" action="/api/ajax?class=Atendimento.php" id="atendimento_form" style="margin-bottom: 0;">
				<input type="hidden" name="token" value="<?php echo $request->token ?>">
				<input type="hidden" id="id_assinante" name="id_cliente_integracao" />
				<input type="hidden" value="<?= $assinante ?>" name="assinante" />
				<input type="hidden" value="<?= $contato ?>" name="contato" />
				<input type="hidden" value="<?= $fone1 ?>" name="fone1" />
				<input type="hidden" value="<?= $cpf_cnpj ?>" name="cpf_cnpj" />
				<input type="hidden" value="<?= $protocolo ?>" name="protocolo" />
				<input type="hidden" value="<?= $dado_adicional ?>" name="solicitacao" />
				<input type="hidden" value="<?= $descricao_dado_adicional ?>" name="descricao_dado_adicional" />
				<input type="hidden" value="<?= $id_contrato_plano_pessoa ?>" name="id_contrato_plano_pessoa" />
				<input type="hidden" value="<?= $_GET['id_arvore'] ?>" name="id_arvore" id="id_arvore" />

				<div class="panel panel-default">
					<div class="panel-heading clearfix">
						<h3 class="panel-title text-left pull-left">Atendimento:</h3>

						<div class="pull-right">
							<button id='cronometro' type="button" class="btn btn-default btn-xs" style="padding-left: 20px; padding-right: 20px;margin-left: 3px">00:00</button>

                            <div class="btn-group">
                                <button type="button" class="btn btn-xs btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true"><i class="fas fa-tools"></i> Ferramentas</button>
                                <button type="button" class="btn btn-xs btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <span class="caret"></span>
                                    <span class="sr-only">Toggle Dropdown</span>
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a href='#' data-toggle="modal" data-target="#modalCalcPlano" id='btn-calc-plano'><i class="fa fa-calculator" aria-hidden="true"></i> Calc. plano</a></li>

									<li><a href='#' data-toggle="modal" data-target="#modalCalcKBPS" id='btn-conversor'><i class="fas fa-retweet"></i> Conversor KBPS</a></li>

                                    <li><a href='#' data-toggle="modal" data-target="#modalTempo" id='btn-clima'><i class="fa fa-snowflake-o" aria-hidden="true"></i> Clima</a></li>
                                </ul>
                            </div>
							
							<div class="btn-group">
                                <button type="button" class="btn btn-xs btn-info" data-toggle="modal" data-target="#modalIframeManual"><i class="fa fa-file-text-o" aria-hidden="true"></i> Manual</button>
                                <button type="button" class="btn btn-xs btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <span class="caret"></span>
                                    <span class="sr-only">Toggle Dropdown</span>
                                </button>
                                <ul class="dropdown-menu">                                
                                    <li><a href='#' data-toggle="modal" data-target="#modalIframeManual">Abrir aqui</a></li>
                                    <li><a href='/api/iframe?token=<?php echo $request->token ?>&view=exibe-manual&contrato=<?= $id_contrato_plano_pessoa ?>' target='_blank'>Abrir em outra aba</a></li>
                                </ul>
                            </div>
                            <div class="btn-group">
                                <button type="button" class="btn btn-xs btn-info" data-toggle="modal" data-target="#modalIframeQI"><i class="fa fa-info" aria-hidden="true"></i> Quadro informativo</button>
                                <button type="button" class="btn btn-xs btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <span class="caret"></span>
                                    <span class="sr-only">Toggle Dropdown</span>
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a href='#' data-toggle="modal" data-target="#modalIframeQI">Abrir aqui</a></li>
                                    <li><a href="/api/iframe?token=<?php echo $request->token ?>&view=exibe-quadro-informativo&contrato=<?= $id_contrato_plano_pessoa ?>" target='_blank'>Abrir em outra aba</a></li>
                                </ul>
                            </div>

							<?php
								$dados_catalogo_equipamento = DBRead('', 'tb_catalogo_equipamento_qi_contrato', "WHERE id_contrato_plano_pessoa = '".$id_contrato_plano_pessoa."' LIMIT 1");
								if($dados_catalogo_equipamento){
							?>
							<div class="btn-group">
								<button type="button" class="btn btn-xs btn-info" data-toggle="modal" data-target="#modalIframeCatalogo"><i class="fas fa-wifi" aria-hidden="true"></i> Cat. de Equipa.</button>
								<button type="button" class="btn btn-xs btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
									<span class="caret"></span>
									<span class="sr-only">Toggle Dropdown</span>
								</button>
								<ul class="dropdown-menu">
									<li><a href='#' data-toggle="modal" data-target="#modalIframeCatalogo">Abrir aqui</a></li>
									<li><a href="/api/iframe?token=<?php echo $request->token ?>&view=exibe-catalogo-equipamento&contrato=<?= $id_contrato_plano_pessoa ?>" target='_blank'>Abrir em outra aba</a></li>
								</ul>
							</div>

							<?php
								}
							?>

                            <div class="btn-group">
                                <button type="button" class="btn btn-xs btn-info" data-toggle="modal" data-target="#modalIframeFAQ"><i class="fa fa-question" aria-hidden="true"></i> FAQ</button>
                                <button type="button" class="btn btn-xs btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <span class="caret"></span>
                                    <span class="sr-only">Toggle Dropdown</span>
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a href='#' data-toggle="modal" data-target="#modalIframeFAQ">Abrir aqui</a></li>
                                    <li><a href="/api/iframe?token=<?php echo $request->token ?>&view=faq-exibe-busca" target='_blank'>Abrir em outra aba</a></li>
                                </ul>
                            </div>

							<?php
								$sistema_chat = DBRead('', 'tb_sistema_chat_contrato', "WHERE id_contrato_plano_pessoa = '".$id_contrato_plano_pessoa."' LIMIT 1", "id_sistema_chat_contrato");
								if($sistema_chat && $perfil_usuario != '28'){
									echo "<a data-toggle='modal' data-target='#modalSistemaChat' class='btn-xs btn btn-info' role='button'><i class='fa fa-comments' aria-hidden='true'></i> Sistema de chat</a>";
								}
                            ?>
                            
							<?php
							$solicitacao = DBRead('', 'tb_solicitacao_ajuda', "WHERE atendente = '" . $_SESSION['id_usuario'] . "' AND data_encerramento IS NULL LIMIT 1", "id_solicitacao_ajuda");
							if ($solicitacao) {
								echo "<a href='#' id='solicita_ajuda' class='btn-xs btn btn-danger disabled' role='button'><i class='fa fa-exclamation' aria-hidden='true'></i> Ajuda solicitada</a>";
							} else {
								echo "<a href='#' id='solicita_ajuda' class='btn-xs btn btn-info' role='button'><i class='fa fa-question' aria-hidden='true'></i> Solicitar ajuda</a>";
							}
							?>

							<button type="submit" onclick="if(!confirm('Reiniciar atendimento?')){ return false; }" name="reiniciar" value="reiniciar" id="reiniciar" class='btn-submit-fluxo btn-xs btn btn-danger btn-reiniciar' disabled><i class="fa fa-refresh" aria-hidden="true"></i> Reiniciar</button>
							<button type="submit" name="voltar" value="voltar" id="voltar" class='btn-submit-fluxo btn-xs btn btn-warning btn-voltar' disabled><i class="fa fa-arrow-left" aria-hidden="true"></i> Voltar</button>

						</div>

					</div>

					<?php            		
					
					if($sistema_chat){
						$sistema_chat_modal = DBRead('', 'tb_sistema_chat_contrato a', "INNER JOIN tb_tipo_sistema_chat b ON a.id_tipo_sistema_chat = b.id_tipo_sistema_chat INNER JOIN tb_sistema_chat_acesso c ON a.id_sistema_chat_contrato = c.id_sistema_chat_contrato WHERE a.id_contrato_plano_pessoa = $id_contrato_plano_pessoa ", "b.nome, a.id_sistema_chat_contrato, a.observacao, a.link, c.usuario, c.senha");
					}			
                	
                	?>

                    <?php if($perfil_usuario != '28'){ ?>
                	<!-- Modal para apresentar usuários e senhas. -->
                	<div id="modalSistemaChat" class="modal fade" role="dialog">
					  <div class="modal-dialog modal-lg">

					    <!-- Modal content-->
					    <div class="modal-content">
					      <div class="modal-header">
					        <button type="button" class="close" data-dismiss="modal">&times;</button>
					        <h4 class="modal-title">Sistema de chat</h4>
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
							if($sistema_chat):
                                
								foreach ($sistema_chat_modal as $conteudo_sistema_chat) {
									echo'
									<tr>
										<td><a target="_blank" href="'.$conteudo_sistema_chat['link'].'">'.$conteudo_sistema_chat['nome'].'</a></td>
										<td><input class="form-control input-sm" type="text" readonly value="'.$conteudo_sistema_chat['usuario'].'"></td>
										<td><input class="form-control input-sm" type="text" readonly value="'.$conteudo_sistema_chat['senha'].'"></td>
										<td>'.$conteudo_sistema_chat['observacao'].'</td>
									</tr>';
								}
								
							endif;
		                	?>
		                		</tbody>
							</table>
					      </div>
					    </div>

					  </div>
					</div>
                    <?php } ?>

					<div class="panel-body">
						<?php

						$sistemas = DBRead('', 'tb_sistema_gestao_contrato a', "INNER JOIN tb_sistema_gestao_acesso b ON a.id_sistema_gestao_contrato = b.id_sistema_gestao_contrato INNER JOIN tb_tipo_sistema_gestao c ON a.id_tipo_sistema_gestao = c.id_tipo_sistema_gestao WHERE a.id_contrato_plano_pessoa = $id_contrato_plano_pessoa GROUP BY a.id_sistema_gestao_contrato", "c.nome, a.id_sistema_gestao_contrato, a.observacao, a.link");

						?>

                        <?php if($perfil_usuario != '28'){ ?>
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
												if ($sistemas) :
													foreach ($sistemas as $conteudo) :
														$usuarios = DBRead('', 'tb_sistema_gestao_acesso', "WHERE id_sistema_gestao_contrato = '" . $conteudo['id_sistema_gestao_contrato'] . "' ORDER BY contador ASC LIMIT 1", "id_sistema_gestao_acesso, usuario, senha");

														$usuarios_maior = DBRead('', 'tb_sistema_gestao_acesso', "WHERE id_sistema_gestao_contrato = '" . $conteudo['id_sistema_gestao_contrato'] . "' ORDER BY contador DESC LIMIT 1", "contador");

														if ($usuarios_maior) {
															$contador = $usuarios_maior[0]['contador'] + 1;
														} else {
															$contador = 1;
														}
														$dados_contador = array(
															'contador' => $contador
														);

														DBUpdate('', 'tb_sistema_gestao_acesso', $dados_contador, "id_sistema_gestao_acesso = '" . $usuarios[0]['id_sistema_gestao_acesso'] . "'");
												?>
														<tr>
															<td><a target="_blank" href="<?= $conteudo['link'] ?>"><?= $conteudo['nome'] ?></a></td>
															<td><input class="form-control input-sm" type="text" readonly value="<?= $usuarios[0]['usuario'] ?>"></td>
															<td><input class="form-control input-sm" type="text" readonly value="<?= $usuarios[0]['senha'] ?>"></td>
															<td><?= $conteudo['observacao'] ?></td>
														</tr>
												<?php
													endforeach;
												endif;
												?>
											</tbody>
										</table>
									</div>
								</div>

							</div>
						</div>
                        <?php } ?>

						<div class="row">
							<div class="col-lg-12">
								<div class="btn-group btn-group-justified" role="group" aria-label="..." '="">
								<div class="btn-group" role="group">
									
								<?php
								if ($integra) {
									echo '<a class="btn btn-default" style="cursor: inherit; border-left: 20px solid ' . $cor . '; text-shadow: 0 0 0 !important; background-image: none !important; background-color: #d9d9d9 !important; padding-top: 16px; padding-bottom: 16px;">';
									$icone_botao_sistema = '<i class="fa fa-joomla" aria-hidden="true"></i> ';
								} else {
									if ($sistemas) {
										echo "<a data-toggle=\"modal\" data-target=\"#modalSistemas\" class='btn btn-default' style='border-left: 20px solid " . $cor . "; text-shadow: 0 0 0 !important; background-image: none !important; background-color: #d9d9d9 !important; padding-top: 16px; padding-bottom: 16px;' onMouseOver=\"this.style.color='#337ab7'\" onMouseOut=\"this.style.color='#000000'\">";
										$icone_botao_sistema = '<i class="fa fa-external-link" aria-hidden="true"></i> ';
									} else {
										echo '<a class="btn btn-default" style="cursor: inherit; border-left: 20px solid ' . $cor . '; text-shadow: 0 0 0 !important; background-image: none !important; background-color: #d9d9d9 !important; padding-top: 16px; padding-bottom: 16px;">';
										$icone_botao_sistema = '';
									}
								}
								?>
                                        <span style="font-size: 13px; display: inline;" class="pull-left"><?= $id_contrato_plano_pessoa ?></span>
                                        <span><?php echo $icone_botao_sistema . '' . $nome_contrato; ?></span>
                                    </a>
								</div>
								<div class="btn-group" role="group">
									<div style="text-shadow: 0 0 0 !important; background-image: none !important; background-color: #d9d9d9 !important; padding: 6px 0 6px 0; text-align: center; border: 1px solid #ccc;">
										<strong>Localidade:</strong>
										<span><?= $cidade . ", " . $sigla ?></span>
										<input type="hidden" id="timezone" value="<?= $timezone ?>">
										<br>
										<strong>Hora no provedor:</strong>
										<span id="hora-provedor"><?= $timezone ?></span>
									</div>
								</div>
							</div>
						</div>
					</div>
						<?php
						echo "<div class='row'>";
						echo "<div class='col-md-12 text-center'>";
						echo "<ol class='breadcrumb' style='margin-bottom:0px;'>";
						echo "<li><button type='button' data-toggle='modal' data-target='#myModal' id='altera-dados-contato' class='btn btn-link'><i class='fa fa-pencil-square-o' aria-hidden='true'></i> Editar dados</button></li>";

						echo "<li class='li-contato'><strong>Contato:</strong> <span class='span-contato'>" . $contato . "</span></li>";

						echo "<li class='li-fone1'><strong>Fone 1:</strong> <span class='span-fone1'>" . $fone1 . "</span>";

						if ($prefixo_telefone) {
							//verifica se fone2 é para celular
							if (strlen($fone1) == 11 || substr($fone1, 2, 1) == 9) {
								//verifica se o retorno é valido para celular ou para ambos(celular e fixo)
								if ($retorno_valido_para == 2 || $retorno_valido_para == 3) {
									echo " <button type='button' class='btn btn-link' onclick=\"ligar_softphone($('#prefixo_telefone').val()+$('.span-fone1').text())\"><i class='fa fa-phone' aria-hidden='true'></i></button>";
								} else {
									echo " <button type='button' class='btn btn-link disabled' data-toggle='popover' data-html='true' data-trigger='focus' data-content='Retorno indisponível!'><i class='fa fa-phone' aria-hidden='true'></i></button>";
								}
								//verifica se fone2 é fixo
							} else if (strlen($fone1) == 10) {
								//verifica se o retorno é válido para fixo ou ambos
								if ($retorno_valido_para == 1 || $retorno_valido_para == 3) {
									echo " <button type='button' class='btn btn-link' onclick=\"ligar_softphone($('#prefixo_telefone').val()+$('.span-fone1').text())\"><i class='fa fa-phone' aria-hidden='true'></i></button>";
								} else {
									echo " <button type='button' class='btn btn-link disabled' data-toggle='popover' data-html='true' data-trigger='focus' data-content='Retorno indisponível!'><i class='fa fa-phone' aria-hidden='true'></i></button>";
								}
							}
						} else {
							echo " <button type='button' class='btn btn-link disabled' data-toggle='popover' data-html='true' data-trigger='focus' data-content='Retorno indisponível!'><i class='fa fa-phone' aria-hidden='true'></i></button>";
						}
						echo "</li>";
						if (!$fone2) {
							$display_fone2 = 'style="display:none;"';
						} else {
							$display_fone2 = '';
						}

						echo "<li class='li-fone2' $display_fone2><strong>Fone 2:</strong> <span class='span-fone2'>" . $fone2 . "</span>";

						if ($prefixo_telefone) {
							//verifica se fone2 é para celular
							if (strlen($fone2) == 11 || substr($fone2, 2, 1) == 9) {
								//verifica se o retorno é valido para celular ou para ambos(celular e fixo)
								if ($retorno_valido_para == 2 || $retorno_valido_para == 3) {
									echo "<button type='button' class='btn btn-link' onclick=\"ligar_softphone($('#prefixo_telefone').val()+$('.span-fone2').text())\"><i class='fa fa-phone' aria-hidden='true'></i></button>";
								} else {
									echo " <button type='button' class='btn btn-link disabled' data-toggle='popover' data-html='true' data-trigger='focus' data-content='Retorno indisponível!'><i class='fa fa-phone' aria-hidden='true'></i></button>";
								}
								//verifica se fone2 é fixo
							} else if (strlen($fone2) == 10) {
								//verifica se o retorno é válido para fixo ou ambos
								if ($retorno_valido_para == 1 || $retorno_valido_para == 3) {
									echo "<button type='button' class='btn btn-link' onclick=\"ligar_softphone($('#prefixo_telefone').val()+$('.span-fone2').text())\"><i class='fa fa-phone' aria-hidden='true'></i></button>";
								} else {
									echo " <button type='button' class='btn btn-link disabled' data-toggle='popover' data-html='true' data-trigger='focus' data-content='Retorno indisponível!'><i class='fa fa-phone' aria-hidden='true'></i></button>";
								}
							}
						} else {
							echo " <button type='button' class='btn btn-link disabled' data-toggle='popover' data-html='true' data-trigger='focus' data-content='Retorno indisponível!'><i class='fa fa-phone' aria-hidden='true'></i></button>";
						}
						echo "</li>";

						//Verifica se existe integração com IXC para esse cliente.
						if ($integra[0]['id_integracao'] == 1) {
							echo "<li class='li-assinante'><strong>Assinante:</strong> <span class='span-assinante'></span></li>";
						} else {
							echo "<li class='li-assinante'><strong>Assinante:</strong> <span class='span-assinante'>" . $assinante . "</span></li>";
						}

						if ($cpf_cnpj) :
							echo "<li class='li-cpf_cnpj'><strong>CPF/CNPJ: </strong><span class='span-cpf_cnpj'>" . $cpf_cnpj . "</span></li>";
						endif;
						if ($dado_adicional) :
							echo "<li class='li-dado_adicional'><strong>" . $descricao_dado_adicional . ":</strong> <span class='span-dado_adicional'>" . $dado_adicional . "</span></li>";
						endif;
						if ($exibir_protocolo) :
							echo "<li><strong>Protocolo:</strong> <span>" . $protocolo . "</span></li>";
						endif;

						echo "</ol>";

						echo "</div>";
						echo "</div>";

						$data_atual = getDataHora();

						/*
                            * Adiciona os alertas de feriados.
                        */
						$dados_feriados = DBRead('', 'tb_feriado', "WHERE data = '" . substr($data_atual, 5, 5) . "' AND (tipo = 'Nacional' OR (tipo = 'Estadual' AND id_estado = '" . $dados_contrato[0]['id_estado'] . "') OR (tipo = 'Municipal' AND id_cidade = '" . $dados_contrato[0]['id_cidade'] . "'))", "tipo, nome");
						if ($dados_feriados) {
							foreach ($dados_feriados as $conteudo) {
								echo "<hr><div class='row'>";
								echo '<div class="col-lg-12">';
								echo "<div class='alert alert-info text-center' style='margin-bottom: 0' role='alert'>";
								echo "<div class='row'>";
								echo "<div class='col-xs-12'>";
								echo "<span><strong>Feriado " . strtolower($conteudo['tipo']) . ": </strong>" . $conteudo['nome'] . "</span>";
								echo "</div>";
								echo "</div>";
								echo "</div>";
								echo "</div>";
								echo "</div>";
							}
						}

						/*
							* Adiciona os alertas definidos para aparecer durante o fluxo.
						*/
						$dados_alerta = DBRead('', 'tb_alerta', "WHERE (id_contrato_plano_pessoa = '" . $id_contrato_plano_pessoa . "' OR id_contrato_plano_pessoa IS NULL) AND (exibicao = 1 OR exibicao = 5) AND data_inicio <= '" . $data_atual . "' AND (data_vencimento IS NULL OR data_vencimento > '" . $data_atual . "')", "id_contrato_plano_pessoa, conteudo, data_inicio, data_vencimento");

						if ($dados_alerta) {

							foreach ($dados_alerta as $conteudo) {
								echo "<hr><div class='row'>";
								echo '<div class="col-lg-12">';
								echo "<div class='alert alert-warning text-center' style='margin-bottom: 0' role='alert'>";
								echo "<div class='row'>";
								echo "<div class='col-xs-12'>";
								if($conteudo['id_contrato_plano_pessoa']){
									echo "<span>".nl2br($conteudo['conteudo'])."</span>";
								}else{
									echo "<span>Alerta Geral<br>".nl2br($conteudo['conteudo'])."</span>";
								}
								echo "</div>";
								echo "</div>";
								echo "<hr><div class='row'>";
								echo "<div class='col-xs-12'>";
								echo "<div class='pull-left'>";
								echo "<strong>Início em:</strong> " . converteDataHora($conteudo['data_inicio']);
								echo "</div>";
								echo "<div class='pull-right'>";
								echo "<strong>Vence em:</strong> " . converteDataHora($conteudo['data_vencimento']);
								echo "</div>";
								echo "</div>";
								echo "</div>";
								echo "</div>";
								echo "</div>";
								echo "</div>";
							}
						}

						if (!$dados_arvore) {
							/*
									* Adiciona os alertas definidos para aparecer final do fluxo.
								*/
							$dados_alerta = DBRead('', 'tb_alerta', "WHERE (id_contrato_plano_pessoa = '" . $id_contrato_plano_pessoa . "' OR id_contrato_plano_pessoa IS NULL) AND exibicao = 2 AND data_inicio <= '" . $data_atual . "' AND (data_vencimento IS NULL OR data_vencimento > '" . $data_atual . "')", "id_contrato_plano_pessoa, conteudo, data_inicio, data_vencimento");

							if ($dados_alerta) {
								foreach ($dados_alerta as $conteudo) {
									echo "<hr><div class='row'>";
									echo '<div class="col-lg-12">';
									echo "<div class='alert alert-danger text-center' style='margin-bottom: 0' role='alert'>";
									echo "<div class='row'>";
									echo "<div class='col-xs-12'>";
									if($conteudo['id_contrato_plano_pessoa']){
										echo "<span>".nl2br($conteudo['conteudo'])."</span>";
									}else{
										echo "<span>Alerta Geral<br>".nl2br($conteudo['conteudo'])."</span>";
									}
									echo "</div>";
									echo "</div>";
									echo "<hr><div class='row'>";
									echo "<div class='col-xs-12'>";
									echo "<div class='pull-left'>";
									echo "<strong>Início em:</strong> " . converteDataHora($conteudo['data_inicio']);
									echo "</div>";
									echo "<div class='pull-right'>";
									echo "<strong>Vence em:</strong> " . converteDataHora($conteudo['data_vencimento']);
									echo "</div>";
									echo "</div>";
									echo "</div>";
									echo "</div>";
									echo "</div>";
									echo "</div>";
								}
							}
						}
						?>
							<div style="width: 310px;margin: 0 auto;">
								<span style="vertical-align:-18px;font-weight:bolder;font-size:20px;" class="text-danger alerta-sistema-gestao-integracao"></span>
							</div>
							<hr>
							<?php
							include_once("atendimento-form-arvore.php");

							if ($dados_arvore) :
                                $dados_anotacao_padrao = DBRead('', 'tb_arvore', "WHERE id_arvore = '$id_arvore' LIMIT 1", "anotacao_padrao");
							?>
								<div class="collapse" id="collapseAnotacao" style="margin-top: 20px;">
									<div class="panel panel-default"  style="margin-bottom: 0px;">
										<div class="panel-heading clearfix">
											<h3 class="panel-title text-left pull-left">Anotação:</h3>
										</div>
										<div class="panel-body">
											<textarea name="anotacao" id="anotacao" class="form-control input-sm" rows="5"><?=$dados_anotacao_padrao[0]['anotacao_padrao']?></textarea>
										</div>
									</div>
								</div>
								<div class="collapse" id="collapseFalha" style="margin-top: 20px;">
									<div class="panel panel-default"  style="margin-bottom: 0px;">
										<div class="panel-heading clearfix">
											<h3 class="panel-title text-left pull-left">Atendimento incompleto:</h3> 
										</div>
										<div class="panel-body">
											<?php
											$dados_falha = DBRead('', 'tb_tipo_falha_atendimento', "WHERE exibicao != '1' AND status = '1'", "id_tipo_falha_atendimento, opcao");
											if ($dados_falha) {
												foreach ($dados_falha as $conteudo) {
													echo "<a href=\"/api/iframe?token=". $request->token ." &view=atendimento-form&id_atendimento=$id_atendimento&id_falha=" . $conteudo['id_tipo_falha_atendimento'] . "\" class='btn btn-primary form-control btn-opcao'>" . $conteudo['opcao'] . "</a>";
												}
											}
											?>
										</div>
									</div>
								</div>
							<?php
							else :
							?>	
								<br>
								<div class="row">
									<div class="col-md-12">
										<div class="panel panel-default">
                                            <div class="panel-heading clearfix">
                                                <h3 class="panel-title text-left pull-left">Opções:</h3>
                                            </div>
											<div class="panel-body">
                                                <?php
                                                if($dados_parametros[0]['atendimento_via_texto'] == "1"){
                                                ?>
                                                <div class="row">
													<div class="col-md-12">
                                                        <select name='canal_atendimento' class='form-control clipboard_select' id='canal-atendimento' required>
                                                            <option value=''>Selecione o canal de atendimento!</option>
                                                            <option value='telefone'>Atendimento via TELEFONE</option>
                                                            <option value='texto'>Atendimento via TEXTO</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <hr>
                                                <?php
                                                }
                                                ?>
												<div class="row">
                                                    <div class="col-md-12">
                                                        <ul class="list-inline">
                                                            <li class="col-md-6 text-left">
                                                                <div class="checkbox">
                                                                    <label>
                                                                        <input type="checkbox" id="elogio" name="elogio" value="1"> <strong> Atendimento encantador</strong> <i class="fa fa-star" aria-hidden="true"></i>
                                                                    </label>
                                                                </div>
                                                            </li>
                                                            <li class="col-md-6 text-left">
                                                                <div class="checkbox">
                                                                    <label>
                                                                        <input type="checkbox" id="irritado" name="irritado" value="1"> <strong> Cliente Irritado</strong> <i class="fa fa-frown-o" aria-hidden="true"></i>
                                                                    </label>
                                                                </div>
                                                            </li>
                                                        </ul>	
                                                    </div>                                                    	
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <ul class="list-inline">
                                                            <li class="col-md-6 text-left">
                                                                <div class="checkbox">
                                                                    <label>
                                                                        <input class="btn btn-link btn-opcoes-chamado" type="checkbox" id=' chamado' name="chamado" value='10'> <strong> Chamado</strong> <i class="fa fa-bullhorn" aria-hidden="true"></i>
                                                                    </label>
                                                                </div>
                                                            </li>
                                                            <li class="col-md-6 text-left">
                                                                <div class="checkbox">
                                                                    <label>
                                                                        <input class="btn btn-link" type="checkbox" id='enviar-email' name="enviar-email" value='10'> <strong> Enviar E-mail</strong> <i class="fa fa-envelope-o" aria-hidden="true"></i>
                                                                    </label>
                                                                </div>
                                                            </li>
                                                        </ul>
                                                        <div class='collapse' id='collapseChamado'>
                                                            <br>
                                                            <hr>
                                                            <label for="descricao_chamado">*Descrição do chamado:</label>
                                                            <textarea name="descricao_chamado" class="descricao_chamado form-control conteudo ckeditor"><?= $descricao ?></textarea>
                                                            <br>
                                                            <label for="sistema_gestao">*Registrado no Sistema de Gestão:</label>
                                                            <select class="sistema_gestao form-control input-sm" name="sistema_gestao">
                                                                <option value='1'>Sim</option>
                                                                <option value='0'>Não</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
											</div>
										</div>
									</div>
								</div>

	<?php

							endif;
	?>
	</div>
	<div class="row">
		<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title" id="myModalLabel">Editar dados:</h4>
					</div>
					<div class="modal-body">
						<input type="hidden" id="prefixo_telefone" value="<?= $prefixo_telefone ?>">
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label for='contato'>*Contato:</label>
									<input type="text" class="contato form-control input-sm" value="<?= $contato ?>" />
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="assinante">*Assinante: <span id='loading_assinante' class="text-primary"></span></label>
									<?php
									//Verifica se existe integração com sistema de gestão deste cliente, se sim, faz a busca de assinantes da base de dados do sistema de gestão do cliente, caso o contrário segue como um atendimento comum do sistema Simples
									if ($integra) {
										echo '<input autocomplete="off" class="form-control input-sm assinante" id="busca_assinante" type="text" name="assinante" value="' . $assinante . '">';
									} else {
										echo '<input autocomplete="off" type="text" class="assinante form-control input-sm" value="' . $assinante . '">';
									}
									?>
									<span id='erro-busca-assinante'></span>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label for='fone1'>*Fone 1:</label>
									<input type="text" id="fone1-modal" class="fone1 form-control input-sm phone" value="<?= $fone1 ?>" />
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="fone2">Fone 2:</label>
									<input type="text" id="fone2-modal" class="fone2 form-control input-sm phone" value="<?= $fone2 ?>" />
								</div>
							</div>
						</div>

						<?php

						if ($solicitacao_cpf_cnpj || $descricao_dado_adicional) :
						?>
							<div class="row">
								<?php

								if ($solicitacao_cpf_cnpj) :
								?>

									<div class="col-md-2">
										<div class="form-group">
											<label for='tipo'>*Tipo</label>
											<select id='tipo' class='form-control input-sm'>
												<?php

												if (strlen($cpf_cnpj) > 11) {
													$selected_cpf = "";
													$selected_cnpj = "selected";
												} else {
													$selected_cpf = "selected";
													$selected_cnpj = "";
												}
												?>
												<option <?= $selected_cpf ?> value='cpf'>PF</option>
												<option <?= $selected_cnpj ?> value='cnpj'>PJ</option>
											</select>
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group">
											<?php
											if (strlen($cpf_cnpj) > 11) :
											?>
												<label for='cpf_cnpj' class="label_cpf_cnpj">*CNPJ:</label><span class='alerta-validacao-cpf_cnpj' style='display: none;'></span>
											<?php
											else :
											?>
												<label for='cpf_cnpj' class="label_cpf_cnpj">*CPF:</label><span class='alerta-validacao-cpf_cnpj' style='display: none;'></span>
											<?php
											endif;
											?>
											<input type="text" class="cpf_cnpj form-control input-sm" value="<?= $cpf_cnpj ?>" />
										</div>
									</div>
									<script>
										function calc_digitos_posicoes(digitos, posicoes, soma_digitos) {
											digitos = digitos.toString();
											for (var i = 0; i < digitos.length; i++) {
												soma_digitos = soma_digitos + (digitos[i] * posicoes);
												posicoes--;
												if (posicoes < 2) {
													posicoes = 9;
												}
											}
											soma_digitos = soma_digitos % 11;
											if (soma_digitos < 2) {
												soma_digitos = 0;
											} else {
												soma_digitos = 11 - soma_digitos;
											}
											var cpf = digitos + soma_digitos;
											return cpf;
										}

										function valida_cnpj(valor) {
											valor = valor.toString();
											valor = valor.replace(/[^0-9]/g, '');
											var cnpj_original = valor;
											var primeiros_numeros_cnpj = valor.substr(0, 12);
											var primeiro_calculo = calc_digitos_posicoes(primeiros_numeros_cnpj, 5, 0);
											var segundo_calculo = calc_digitos_posicoes(primeiro_calculo, 6, 0);
											var cnpj = segundo_calculo;
											var iguais = false;
											if (cnpj_original == "11111111111111" || cnpj_original == "22222222222222" || cnpj_original == "33333333333333" || cnpj_original == "44444444444444" || cnpj_original == "55555555555555" || cnpj_original == "66666666666666" || cnpj_original == "77777777777777" || cnpj_original == "88888888888888" || cnpj_original == "99999999999999" || cnpj_original == "00000000000000") {
												iguais = true;
											}
											if ((cnpj === cnpj_original) && (!iguais)) {
												return true;
											}
										}

										function valida_cpf(valor) {
											valor = valor.toString();
											valor = valor.replace(/[^0-9]/g, '');
											var digitos = valor.substr(0, 9);
											var novo_cpf = calc_digitos_posicoes(digitos, 10, 0);
											var novo_cpf = calc_digitos_posicoes(novo_cpf, 11, 0);
											var iguais = false;
											if (valor == "11111111111" || valor == "22222222222" || valor == "33333333333" || valor == "44444444444" || valor == "55555555555" || valor == "66666666666" || valor == "77777777777" || valor == "88888888888" || valor == "99999999999" || valor == "00000000000") {
												iguais = true;
											}
											if ((novo_cpf === valor) && (!iguais)) {
												return true;
											}
										}
										///// Função que verifica cpf ou cnpj e insere um alerta para o usuário!
										function verificaCpfCnpj(tipo) {
											if (tipo == "CPF") {
												if (!valida_cpf($(".cpf_cnpj").val()) && $(".cpf_cnpj").val() != "") {
													$(".alerta-validacao-cpf_cnpj").css("display", "inline").removeClass("text-success").addClass("text-danger");
													$(".alerta-validacao-cpf_cnpj").html(" (Inválido)");
												} else if (valida_cpf($(".cpf_cnpj").val())) {
													$(".alerta-validacao-cpf_cnpj").css("display", "inline").removeClass("text-danger").addClass("text-success");
													$(".alerta-validacao-cpf_cnpj").html(" (Válido)");
												}
											} else if (tipo == "CNPJ") {
												if (!valida_cnpj($(".cpf_cnpj").val()) && $(".cpf_cnpj").val() != "") {
													$(".alerta-validacao-cpf_cnpj").css("display", "inline").removeClass("text-success").addClass("text-danger");
													$(".alerta-validacao-cpf_cnpj").html(" (Inválido)");
												} else if (valida_cnpj($(".cpf_cnpj").val())) {
													$(".alerta-validacao-cpf_cnpj").css("display", "inline").removeClass("text-danger").addClass("text-success");
													$(".alerta-validacao-cpf_cnpj").html(" (Válido)");
												}
											}
											$(".cpf_cnpj").on("keyup", function() {
												console.log("clicado");
												if (tipo == "CPF") {
													if (!valida_cpf($(this).val())) {
														$(".alerta-validacao-cpf_cnpj").css("display", "inline").removeClass("text-success").addClass("text-danger");
														$(".alerta-validacao-cpf_cnpj").html(" (Inválido)");
													} else if (valida_cpf($(this).val())) {
														$(".alerta-validacao-cpf_cnpj").css("display", "inline").removeClass("text-danger").addClass("text-success");
														$(".alerta-validacao-cpf_cnpj").html(" (Válido)");
													}
												} else if (tipo == "CNPJ") {
													if (!valida_cnpj($(this).val())) {
														$(".alerta-validacao-cpf_cnpj").css("display", "inline").removeClass("text-success").addClass("text-danger");
														$(".alerta-validacao-cpf_cnpj").html(" (Inválido)");
													} else if (valida_cnpj($(this).val())) {
														$(".alerta-validacao-cpf_cnpj").css("display", "inline").removeClass("text-danger").addClass("text-success");
														$(".alerta-validacao-cpf_cnpj").html(" (Válido)");
													}
												}

											});
										}

										<?php
										if (strlen($cpf_cnpj) > 11) {
											echo "$('.cpf_cnpj').mask('00.000.000/0000-00', {reverse: true, placeholder: '00.000.000/0000-00'});";
										} else {
											echo "$('.cpf_cnpj').mask('000.000.000-00', {reverse: true, placeholder: '000.000.000-00'});";
										}
										?>

										if ($("#tipo").val() == "cpf") {
											verificaCpfCnpj("CPF");
										} else if ($("#tipo").val() == "cnpj") {
											verificaCpfCnpj("CNPJ");
										}

										$('#tipo').on('change', function() {
											var tipo = $(this).val();
											if (tipo == 'cpf') {
												verificaCpfCnpj("CPF");
												$('.label_cpf_cnpj').text('*CPF:');
												$('.cpf_cnpj').mask('000.000.000-00', {
													reverse: true,
													placeholder: '000.000.000-00'
												});
											} else {
												verificaCpfCnpj("CNPJ");
												$('.label_cpf_cnpj').text('*CNPJ:');
												$('.cpf_cnpj').mask('00.000.000/0000-00', {
													reverse: true,
													placeholder: '00.000.000/0000-00'
												});
											}
										});
									</script>

								<?php
								endif;
								if ($descricao_dado_adicional) :
								?>
									<div class="col-md-6">
										<label for='dado_adicional' id='descricao-dado-adicional'>*<?= $descricao_dado_adicional ?>:</label>
										<input type="text" id="dado_adicional" name="dado_adicional" class="form-control input-sm dado_adicional" value="<?= $dado_adicional ?>" />
									</div>
								<?php endif; ?>
							</div>
						<?php
						endif;
						?>
						<div class="row">
							<div class="col-md-12" id="container-info-assinante">

							</div>
						</div>
					</div>

					<div class="modal-footer">
						<button type="button" id="salvar-alteracao-contato" class="btn btn-primary"><i class="fa fa-floppy-o" aria-hidden="true"></i> Salvar</button>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="panel-footer">
		<div class="row">
			<div class="col-md-12 text-center">
				<div class="btn-group" role="group" aria-label="...">
					<input type="hidden" name="id_atendimento" value="<?= $id_atendimento ?>" />
					<input type="hidden" name="id_arvore" value="<?= $id_arvore ?>" />
					<input type="hidden" name="id_falha" value="<?= $id_falha ?>" />
					<?php

					if (!$dados_arvore) {
						if ($dados_parametros[0]['enviar_email'] == "1") {
							$icon_email = ' <i class="fa fa-envelope-o" aria-hidden="true"></i>';
						} else {
							$icon_email = '';
						}
						echo '<button type="submit" id="gravar" name="gravar" value="gravar" class="btn-submit-fluxo btn btn-primary"><i class="fa fa-floppy-o" aria-hidden="true"></i> Gravar' . $icon_email . '</button>';
						echo '<button type="submit" id="cancelar" name="cancelar" value="cancelar" class="btn btn-danger"><i class="fa fa-close" aria-hidden="true"></i> Cancelar</button>';
 						
					} else {
						echo ' <button class="btn btn-anotacao-atendimento btn-info" type="button" data-toggle="collapse" data-target="#collapseAnotacao" aria-expanded="false" aria-controls="collapseAnotacao"><i class="fa fa-pencil" aria-hidden="true"></i> Anotação</button>';
						echo ' <button class="btn btn-atendimento-incompleto btn-warning" type="button" data-toggle="collapse" data-target="#collapseFalha" aria-expanded="false" aria-controls="collapseFalha"><i class="fa fa-stop" aria-hidden="true"></i> Atendimento incompleto</button>';
					}
					?>
				</div>
			</div>
		</div>
	</div>
</div>
</form>
</div>
<div class="col-md-5">
	<?php
	//EXIBIR O COMPLEMENTO SOMENTE SE HOUVER CONTEUDO... CONFORME CONFIGURADO NA ARVORE
	$dados_complemento = DBRead('', 'tb_arvore', "WHERE id_arvore = '$id_arvore' LIMIT 1", "complemento");
	if ($dados_complemento[0]['complemento']) :
	?>
		<div class="panel panel-info">
			<div class="panel-heading clearfix">
				<h3 class="panel-title text-left pull-left">Complemento:</h3>
			</div>
			<div class="panel-body conteudo-editor">
				<?= $dados_complemento[0]['complemento'] ?>
			</div>
		</div>
	<?php
	endif; //Fim if(complemento)
	include_once("atendimento-exibe-quadro-informativo.php");
	?>
</div>

</div>
</div>

<!--Modal manual-->
<div class="modal fade" id="modalIframeManual" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document" style="width: 90% !important; height: 90% !important;">
        <div class="modal-content" style="min-height: 100%; border-radius: 0;">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Manual</h4>
            </div>
            <div class="modal-body">
                <iframe src="" frameborder="0" style="overflow:auto; height: 80vh; min-width:100%"></iframe>
            </div>
        </div>
    </div>
</div>
<!--Fim modal-->            

<!--Modal QI-->
<div class="modal fade" id="modalIframeQI" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document" style="width: 90% !important; height: 90% !important;">
        <div class="modal-content" style="min-height: 100%; border-radius: 0;">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Quadro Informativo</h4>
            </div>
            <div class="modal-body">
                <iframe src="" frameborder="0" style="overflow:auto; height: 80vh; min-width:100%"></iframe>
            </div>
        </div>
    </div>
</div>
<!--Fim modal--> 

<!--Modal catalogo-->
<div class="modal fade" id="modalIframeCatalogo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document" style="width: 90% !important; height: 90% !important;">
        <div class="modal-content" style="min-height: 100%; border-radius: 0;">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Cat. de Equipa.</h4>
            </div>
            <div class="modal-body">
                <iframe src="" frameborder="0" style="overflow:auto; height: 80vh; min-width:100%"></iframe>
            </div>
        </div>
    </div>
</div>
<!--Fim modal--> 

<!--Modal FAQ-->
<div class="modal fade" id="modalIframeFAQ" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document" style="width: 90% !important; height: 90% !important;">
        <div class="modal-content" style="min-height: 100%; border-radius: 0;">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">FAQ</h4>
            </div>
            <div class="modal-body">
                <iframe src="" frameborder="0" style="overflow:auto; height: 80vh; min-width:100%"></iframe>
            </div>
        </div>
    </div>
</div>
<!--Fim modal--> 

<script>
    $('.modal').on('shown.bs.modal',function(){
        if($(this).attr('id') == 'modalIframeManual'){
            $(this).find('iframe').attr('src','/api/iframe?token=<?php echo $request->token ?>&view=exibe-manual&contrato=<?= $id_contrato_plano_pessoa ?>&iframe=1');
        }else if($(this).attr('id') == 'modalIframeQI'){
            $(this).find('iframe').attr('src','/api/iframe?token=<?php echo $request->token ?>&view=exibe-quadro-informativo&contrato=<?= $id_contrato_plano_pessoa ?>&iframe=1');
        }else if($(this).attr('id') == 'modalIframeCatalogo'){
            $(this).find('iframe').attr('src','/api/iframe?token=<?php echo $request->token ?>&view=exibe-catalogo-equipamento&contrato=<?= $id_contrato_plano_pessoa ?>&iframe=1');
        }else if($(this).attr('id') == 'modalIframeFAQ'){
            $(this).find('iframe').attr('src','/api/iframe?token=<?php echo $request->token ?>&view=faq-exibe-busca&iframe=1');
        }
        
    });
</script>

<!--Modal tempo-->
<div class="modal fade" id="modalTempo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Clima</h4>
			</div>
			<div class="modal-body">
				<div style='width: 430px; margin: 0 auto;' id='conteudo-clima'></div>
			</div>
		</div>
	</div>
</div>
<!--Fim modal-->

<!--Modal tempo-->
<div class="modal fade" id="modalCalcPlano" tabindex="-1" role="dialog" aria-labelledby="myModalLabelPlano">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabelPlano">Calculadora de plano</h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-12">
						<label>Planos:</label>
						<select name="plano_calc" id="plano_calc" class="form-control">
							<option value=""></option>
							<?php
							$dados = DBRead('', 'tb_plano_cliente_contrato a', "INNER JOIN tb_plano_cliente b ON a.id_plano_cliente_contrato = b.id_plano_cliente_contrato WHERE a.id_contrato_plano_pessoa = '$id_contrato_plano_pessoa'");
							if ($dados) {
								foreach ($dados as $conteudo) {
									$download = sprintf("%01.2f", $conteudo['download']);
									$upload = sprintf("%01.2f", $conteudo['upload']);
									$descricao = $conteudo['descricao'];
									echo "<option value='$download|$upload'>$descricao - Download: $download Mbps - Upload: $upload Mbps</option>";
								}
							}
							?>
						</select>
					</div>
				</div>
				<hr>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label>Plano - Download(Mbps):</label>
							<input name="down_calc_plano" id="down_calc_plano" type="text" class="form-control input-sm number_float" value="0.00" onKeyUp="oculta_resultado_calc();" autocomplete="off" />
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label>Plano - Upload(Mbps):</label>
							<input name="up_calc_plano" id="up_calc_plano" type="text" class="form-control input-sm number_float" value="0.00" onKeyUp="oculta_resultado_calc();" autocomplete="off" />
						</div>
					</div>
				</div>
				<hr>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label>Result. teste - Download(Mbps):</label>
							<input name="down_calc_teste" id="down_calc_teste" type="text" class="form-control input-sm number_float" value="0.00" onKeyUp="oculta_resultado_calc();" autocomplete="off" />
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label>Result. teste - Upload(Mbps):</label>
							<input name="up_calc_teste" id="up_calc_teste" type="text" class="form-control input-sm number_float" value="0.00" onKeyUp="oculta_resultado_calc();" autocomplete="off" />
						</div>
					</div>
				</div>
				<div id="result_calc_plano" style="display:none;">
					<hr>
					<div class="row">
						<div class="col-md-6" id="result_calc_plano_down">
						</div>
						<div class="col-md-6" id="result_calc_plano_up">
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button style="display:none;" class="btn btn-primary" id="calcular_plano_copiar_anotacao" type="button"><i class="fa fa-files-o"></i> Copiar p/ Anotação</button>
				<button class="btn btn-primary" id="calcular_plano" type="button"><i class="fa fa-check"></i> Calcular</button>
			</div>
		</div>
	</div>
</div>
<!--Fim modal-->

<!--Modal conversor-->
<div class="modal fade" id="modalCalcKBPS" tabindex="-1" role="dialog" aria-labelledby="myModalLabelPlano">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabelPlano">Conversor de Kbp/s para Mbp/s</h4>
			</div>
			<div class="modal-body">
				<!-- <form action="##"> -->
					<div class="row">
						<div class="col-md-12">
							<label class="form-label">Valor em Kbp/s</label>
							<input name="kbps" id="kbps" type="number" class="form-control input-sm number_int">	
						</div>
					</div>
					<div class="modal-footer">
						<div class="row">
							<div class="col-md-6">
								<div id="resultado_busca">
								</div>
							</div>
							<div class="col-md-6">
								<button onClick="call_busca_ajax();" class="btn btn-primary">Converter</button>
							</div>
						</div>
					</div>
				<!-- </form> -->
			</div>
		</div>
	</div>
</div>	
	<script>

	function call_busca_ajax(pagina){	
		var kbps = $('#kbps').val();
       
        var parametros = {
            'kbps': kbps
        };
        busca_ajax('<?= $request->token ?>' , 'class/ConversorKbpsBusca', 'resultado_busca', parametros);
    }

	</script>
<!--Fim modal-->

<?php
//Utiliza esse script somente se houver integração e se essa integração corresponde ao sistema IXC.
//VERIFICAR esse trecho e retirar deste arquivo e passar para uma arquivo externo de importação
if ($integra && $integra[0]['id_integracao'] == "1") :
?>
	<script>
		buscaAssinante();
		//Limpa todos os dados gravados em sessionStorage, sempre que salvo o atendimento o sistema deve apagar dados de atendimento da integração gravados em sessionStorage
		removeSessionStorageAoSalvar()

		$(".alerta-sistema-gestao-integracao").ready(function() {
			alertaIntegracao();
		});
	</script>
<?php
endif;
?>
<script>
	/*$("#editar_razao").html(sessionStorage.getItem("razao_social"));
		$("#editar_cpf_cnpj").html(sessionStorage.getItem("cpf_cnpj"));
		$("#editar_endereco").html(sessionStorage.getItem("endereco") + " " + sessionStorage.getItem("numero_endereco") + ", " + sessionStorage.getItem("cidade"));
		$("#editar_observacao").html(sessionStorage.getItem("observacao"));*/

	function calcular_plano() {
		var down_calc_plano = parseFloat($('#down_calc_plano').val()).toFixed(2);
		var up_calc_plano = parseFloat($('#up_calc_plano').val()).toFixed(2);
		var down_calc_teste = parseFloat($('#down_calc_teste').val()).toFixed(2);
		var up_calc_teste = parseFloat($('#up_calc_teste').val()).toFixed(2);
		var porc_down = (down_calc_teste * 100 / (down_calc_plano ? down_calc_plano : 1)).toFixed(2);
		var porc_up = (up_calc_teste * 100 / (up_calc_plano ? up_calc_plano : 1)).toFixed(2);
		$('#result_calc_plano_down').html('<strong>Download:</strong> ' + porc_down + '%');
		$('#result_calc_plano_up').html('<strong>Upload:</strong> ' + porc_up + '%');
		$('#result_calc_plano').show();
		$('#calcular_plano_copiar_anotacao').show();
	}

	function oculta_resultado_calc() {
		$('#result_calc_plano').hide();
		$('#calcular_plano_copiar_anotacao').hide();
	}

	$(document).on('click', '#calcular_plano', function() {
		calcular_plano();
	});

	$(document).on('click', '#calcular_plano_copiar_anotacao', function() {
		var down_calc_plano = parseFloat($('#down_calc_plano').val()).toFixed(2);
		var up_calc_plano = parseFloat($('#up_calc_plano').val()).toFixed(2);
		var down_calc_teste = parseFloat($('#down_calc_teste').val()).toFixed(2);
		var up_calc_teste = parseFloat($('#up_calc_teste').val()).toFixed(2);
		var porc_down = (down_calc_teste * 100 / (down_calc_plano ? down_calc_plano : 1)).toFixed(2);
		var porc_up = (up_calc_teste * 100 / (up_calc_plano ? up_calc_plano : 1)).toFixed(2);
		var anotacao = $('#anotacao').val();
		$('#collapseAnotacao').collapse('show');
		$('#anotacao').val('Down: ' + down_calc_teste + ' Mbps (' + porc_down + '%), Up: ' + up_calc_teste + ' Mbps (' + porc_up + '%)' + '\n' + anotacao);
	});

	$(document).on('change', '#plano_calc', function() {
		$('#result_calc_plano').hide();
		var valor = $(this).val().split('|');
		$('#down_calc_plano').val(valor[0]);
		$('#up_calc_plano').val(valor[1]);
	});

	function verifica_fone2() {
		if (!$('.span-fone2').text()) {
			$('.li-fone2').hide();
		} else {
			$('.li-fone2').show();
		}
	}
	verifica_fone2();

	$(document).on('click', '#voltar', function() {
		$('#textarea-os').attr('required', false);
		$('#select-situacao').attr('required', false);
		$('#canal-atendimento').attr('required', false);
	});

	$(document).on('click', '#reiniciar', function() {
		$('#textarea-os').attr('required', false);
		$('#select-situacao').attr('required', false);
		$('#canal-atendimento').attr('required', false);
	});

	$(document).on('click', '#cancelar', function() {
		$('#textarea-os').attr('required', false);
		$('#select-situacao').attr('required', false);
		$('#canal-atendimento').attr('required', false);
		if (!confirm('Atendimento não será salvo!')) {
			$('#textarea-os').attr('required', true);
			$('#select-situacao').attr('required', true);
			$('#canal-atendimento').attr('required', true);
			return false;
		}
	});

	$(document).on('click', '.btn-submit-fluxo', function() {
		if ($('#fone1-modal').val().length < 14 && $('#fone1-modal').val().length > 0) {
			$('#fone1-modal').val('');
		}
		if ($('#fone2-modal').val().length < 14 && $('#fone2-modal').val().length > 0) {
			$('#fone2-modal').val('');
		}
	});

	$(document).on('click', '#salvar-alteracao-contato', function() {

		var descricao_dado_adicional = $("#descricao-dado-adicional").text();
		if (!$('.contato').val()) {
			alert('Preencha o campo "Contato"!');
			return false;
		}
		if (!$('.assinante').val()) {
			alert('Preencha o campo "Assinante"!');
			return false;
		}
		if ($('.fone1').val().length < 14) {
			alert('É obrigatório um telefone válido em "Fone 1"!');
			return false;
		}
		if ($('.fone2').val().length < 14 && $('.fone2').val().length > 0) {
			alert('Preencha um telefone válido em "Fone 2"!');
			return false;
		}
		if ($('.dado_adicional').length && (!$('.dado_adicional').val())) {
			alert('Preencha o campo "<?php echo $descricao_dado_adicional ?>"!');
			return false;
		}
		if ($('.cpf_cnpj').length && (($('#tipo').val() == 'cpf' && $('.cpf_cnpj').val().length < 14) || ($('#tipo').val() == 'cnpj' && $('.cpf_cnpj').val().length < 18))) {
			alert('Preencha um ' + $('#tipo').val().toUpperCase() + ' válido em "' + $('#tipo').val().toUpperCase() + '"!');
			return false;
		}

		$.ajax({
			type: "GET",
			url: "/api/ajax?class=AtualizaContatoAtendimento.php",
			dataType: "json",
			data: {
				id_atendimento: <?php echo $id_atendimento ?>,
				contato: $('.contato').val(),
				fone1: $('.fone1').val(),
				fone2: $('.fone2').val(),
				assinante: $('.assinante').val(),
				cpf_cnpj: $('.cpf_cnpj').val(),
				dado_adicional: $('.dado_adicional').val(),
				token: '<?= $request->token ?>'

			},
			success: function(data) {
				$(".dados-contrato-carregado").css("display", "none");
				$('.span-contato').text(data.contato);
				$('.span-fone1').text(data.fone1);
				if ($('.fone2').val()) {
					$('#div-fone2').html("<span id='clip_fone2'><strong>Fone 2:</strong> <span class='span-fone2'>" + $('.fone2').val() + "</span></span><br />");
				} else {
					$('#div-fone2').html("");
				}
				$('.span-fone2').text(data.fone2);
				$('.span-assinante').text(data.assinante);
				$('.span-cpf_cnpj').text(data.cpf_cnpj);
				$('.span-dado_adicional').text(data.dado_adicional);
				verifica_fone2();
				$('#myModal').modal('hide');
			}
		});

	});

	$(document).on('click', '#btn-clima', function() {
		var id_contrato_plano_pessoa = <?php echo $id_contrato_plano_pessoa; ?>;
		$.ajax({
			type: "POST",
			url: "/api/ajax?class=ClimaModal.php",
			data: {
				id_contrato_plano_pessoa: id_contrato_plano_pessoa,
				token: '<?= $request->token ?>'
			},
			success: function(data) {
				$("#conteudo-clima").html(data);
			},
			beforeSend: function() {
				$('#conteudo-clima').html('<div class="alert alert-info" role="alert" style="text-align: center">Buscando...</div>');
			}
		});
	});

	var zone = $('#timezone').val();
	var myVar = setInterval(myTimer, 1000);

	function myTimer() {
		var d = new Date(),
			displayDate;
		if (navigator.userAgent.toLowerCase().indexOf('firefox') > -1) {
			displayDate = d.toLocaleTimeString('pt-BR');
		} else {
			displayDate = d.toLocaleTimeString('pt-BR', {
				timeZone: zone
			});
		}
		document.getElementById("hora-provedor").innerHTML = displayDate;
	}

	$('.btn-atendimento-incompleto.btn-warning').on('click', function() {
		$('.btn-atendimento-incompleto').html("<i class='fa fa-times' aria-hidden='true'></i> Fechar atendimento incompleto").removeClass('btn-warning').addClass('btn-danger');
	});

	$('#collapseFalha').on('hide.bs.collapse', function() {
		$('.btn-atendimento-incompleto').html("<i class='fa fa-stop' aria-hidden='true'></i> Atendimento incompleto").removeClass('btn-danger').addClass('btn-warning');
	});

	$(document).ready(function() {
		$(".btn-opcao").prop("disabled", false);
		$(".btn-voltar").prop("disabled", false);
		$(".btn-reiniciar").prop("disabled", false);
		$(".btn-opcoes-chamado").click(function() {
			$("#collapseChamado").toggle();
		});
	});

	$(document).on('click', '#solicita_ajuda', function() {
		var id_atendimento = <?php echo $id_atendimento; ?>;

		if (confirm('Deseja realmente solicitar ajuda?')) {
			var id_contrato_plano_pessoa = <?php echo $id_contrato_plano_pessoa; ?>;
			$.ajax({
				type: "GET",
				url: "/api/ajax?class=SolicitaAjuda.php",
				dataType: "json",
				data: {
					id_contrato_plano_pessoa: id_contrato_plano_pessoa,
					id_atendimento: id_atendimento,
					token: '<?= $request->token ?>'
				},
				success: function(data) {
					$("#solicita_ajuda").html("<i class='fa fa-exclamation' aria-hidden='true'></i> Ajuda solicitada").removeClass("btn-info").addClass("btn-danger").addClass("disabled");
				}
			});
		}
	});

	var verifica_ajuda = function() {
		$.ajax({
			cache: false,
			type: "POST",
			data: {
				verificar: '1',
				token: '<?= $request->token ?>'
			},
			url: '/api/ajax?class=SolicitaAjuda.php',
			success: function(data) {
				if (data == '0') {
					$("#solicita_ajuda").html("<i class='fa fa-question' aria-hidden='true'></i> Solicitar ajuda").removeClass("btn-danger").addClass("btn-info").removeClass("disabled");
				}
			}
		});
		setTimeout(function() {
			verifica_ajuda();
		}, 5000);
	};
	verifica_ajuda();

	/* $('#atendimento_form').on('submit', function() {
		modalAguarde();
	}); */

	$('form').on('keydown', function(e) {
		if (e.which === 13 && !$(e.target).is('textarea')) {
			e.preventDefault();
		}
	});

	var segundo = 0 + "0";
	var minuto = 0 + "0";
	var hora = 0 + "0";

	function tempo() {
		if (segundo < 59) {
			segundo++
			if (segundo < 10) {
				segundo = "0" + segundo
			}
		} else
		if (segundo == 59 && minuto < 59) {
			segundo = 0 + "0";
			minuto++;
			if (minuto < 10) {
				minuto = "0" + minuto
			}
		}
		if (minuto == 59 && segundo == 59 && hora < 23) {
			segundo = 0 + "0";
			minuto = 0 + "0";
			hora++;
			if (hora < 10) {
				hora = "0" + hora
			}
		} else
		if (minuto == 59 && segundo == 59 && hora == 23) {
			segundo = 0 + "0";
			minuto = 0 + "0";
			hora = 0 + "0";
		}

		document.getElementById("cronometro").innerHTML = minuto + ":" + segundo
	}

	setInterval('tempo()', 983);

	$('#gravar').on('click', function (){        
		
		modalAguarde();

		classificacao = $( "#classificacao option:selected" ).text();
		assunto = $( "#assunto option:selected" ).text();
		prioridade = $( "#prioridade option:selected" ).text();
		setor = $( "select[name=id_setor] option:selected" ).text();
		filial = $( "#filial option:selected" ).text();
		tecnico = $( "#tecnico option:selected" ).text();
		origem = $( "#origem option:selected" ).text();
		contrato = $( "#select_contrato option:selected" ).text();
		login = $( "#login option:selected" ).text();
		processo = $( ".processo option:selected" ).text();

		//atendimento vinculado a OS ja existente
		evento = $( "#evento option:selected" ).text();
		classificacao_evento = $( "#classificacao_evento option:selected" ).text();
		tecnico_responsavel = $( "select[name=tecnico_responsavel] option:selected" ).text();
		id_os = $('input[name=id_atendimento_sistema_gestao]:checked', '#atendimento_form').val();

		id_atendimento = '<?php echo $id_atendimento ?>';

		if (classificacao != '' || assunto !='' || prioridade !='' || setor !='' || filial !='' || tecnico !='' || origem !='' || contrato !='' || login !='' || processo !='' || classificacao_evento !='' || evento !='' || tecnico_responsavel !='' || id_os != '') {

			$.ajax({
				type: "GET",
				url: "/api/ajax?class=IntegracaoCamposDefault.php",
				dataType: "json",
				data: {
					acao: "salva_campos_atendimento",
					classificacao: classificacao,
					assunto: assunto,
					setor: setor,
					filial: filial,
					tecnico: tecnico,
					processo: processo,
					prioridade: prioridade,
					origem: origem,
					contrato: contrato,
					login: login, 
					classificacao_evento: classificacao_evento,
					evento: evento,
					tecnico_responsavel: tecnico_responsavel,
					id_os: id_os,
					id_atendimento: id_atendimento,
					token: '<?= $request->token ?>'
				},
				success: function(data) {
					console.log(data);
				}
			});
		}			
		
		//$( "#atendimento_form" ).submit();
		
    });

</script>