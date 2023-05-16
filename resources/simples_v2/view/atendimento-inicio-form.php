<?php

$id_contrato_plano_pessoa = (int)$_GET['contrato'];
$bina_atendimento = (isset($_GET['bina'])) ? $_GET['bina'] : '';;

$dados = DBRead('', 'tb_contrato_plano_pessoa a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa INNER JOIN tb_cidade c ON b.id_cidade = c.id_cidade INNER JOIN tb_estado d ON c.id_estado = d.id_estado INNER JOIN tb_plano e ON a.id_plano = e.id_plano WHERE a.id_contrato_plano_pessoa = '".$id_contrato_plano_pessoa."'", "b.nome, c.id_cidade, c.nome AS cidade, d.id_estado, d.nome AS estado, d.sigla, e.cor, e.nome AS nome_plano, a.nome_contrato");

if($dados[0]['nome_contrato']){
	$nome_contrato = $dados[0]['nome'] . ' (' . $dados[0]['nome_contrato'] . ')';
}else{
	$nome_contrato = $dados[0]['nome'];
}

//------------------------------- buscar grupo de chat e operadores --------------------------------

//$dados_grupo = DBRead('', 'tb_grupo_atendimento_chat_contrato a', "INNER JOIN tb_grupo_atendimento_chat_operador b ON a.id_grupo_atendimento_chat = b.id_grupo_atendimento_chat INNER JOIN tb_usuario c ON b.id_usuario = c.id_usuario INNER JOIN tb_pessoa d ON c.id_pessoa = d.id_pessoa WHERE id_contrato_plano_pessoa = $id_contrato_plano_pessoa", 'c.id_usuario, d.nome');

if ($dados_grupo) {
	$operador_grupo = '';
	foreach ($dados_grupo as $conteudo_operadores) {
		if($operador_grupo != ''){
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

/* if($dados_responsavel_atendimento_texto){
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
 */
$cidade = $dados[0]['cidade'];
$sigla = $dados[0]['sigla'];
$estado = $dados[0]['estado'];
$cor = $dados[0]['cor'];
$nome_plano = $dados[0]['nome_plano'];

$hora_provedor = getDataHora('hora', $sigla);

$timezone = getTimeZone($sigla);

$dados_parametros = DBRead('', 'tb_parametros', "WHERE id_contrato_plano_pessoa = '".$id_contrato_plano_pessoa."'", "solicitacao_dados, solicitacao_dados_descricao, solicitacao_cpf, prefixo_telefone");

$solicitacao_dados = $dados_parametros[0]['solicitacao_dados'];
$label_solicitacao = $dados_parametros[0]['solicitacao_dados_descricao'];
$cpf_cnpj = $dados_parametros[0]['solicitacao_cpf'];
$prefixo_telefone = $dados_parametros[0]['prefixo_telefone'];

if(!$id_contrato_plano_pessoa){
	echo '<div class="alert alert-danger text-center">Contrato não identificado!</div>';
	exit;
}

//Verifica se há integração para esse contrato
$integra = DBRead('', 'tb_integracao_contrato', "WHERE id_contrato_plano_pessoa = $id_contrato_plano_pessoa");
?>
<style>

.btn-opcao{
    margin-top: 5px;
    margin-bottom: 5px;
}

</style>
<script type="text/javascript">
    $(document).ready(function() {
        document.title = 'Simples V2 - Atendimento';
    });
</script>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-7">
            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    <h3 class="panel-title text-left pull-left">Atendimento:</h3>
                    
                    <div class="pull-right">

						<?php
							if($prefixo_telefone){
								echo '<a href="#"class="btn-xs btn btn-success disabled" role="button"><i class="fa fa-phone" aria-hidden="true"></i> Retorno disponível!</a>';
							}else{
								echo '<a href="#"class="btn-xs btn btn-default disabled" role="button"><i class="fa fa-phone" aria-hidden="true"></i> Retorno indisponível!</a>';
							}
						?>
						<button type="button" class="btn-xs btn btn-info" data-toggle="modal" data-target="#modalTempo" id='btn-clima'><i class="fa fa-snowflake-o" aria-hidden="true"></i> Clima</button>						
                        
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
							$solicitacao = DBRead('', 'tb_solicitacao_ajuda', "WHERE atendente = '".$_SESSION['id_usuario']."' AND data_encerramento IS NULL LIMIT 1", "id_solicitacao_ajuda");
		                	if($solicitacao){
		                		echo "<a href='#' id='solicita_ajuda' class='btn-xs btn btn-danger disabled' role='button'><i class='fa fa-exclamation' aria-hidden='true'></i> Ajuda solicitada</a>";
		                	}else{
		                		echo "<a href='#' id='solicita_ajuda' class='btn-xs btn btn-info' role='button'><i class='fa fa-question' aria-hidden='true'></i> Solicitar ajuda</a>";
		                	}
		                ?>
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
							if($sistemas):
                                foreach($sistemas as $conteudo):   
									$usuarios = DBRead('', 'tb_sistema_gestao_acesso', "WHERE id_sistema_gestao_contrato = '" . $conteudo['id_sistema_gestao_contrato'] . "' ORDER BY contador ASC LIMIT 1", "id_sistema_gestao_acesso, usuario, senha");

									$usuarios_maior = DBRead('', 'tb_sistema_gestao_acesso', "WHERE id_sistema_gestao_contrato = '" . $conteudo['id_sistema_gestao_contrato'] . "' ORDER BY contador DESC LIMIT 1", "contador");
                                    
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
								
                                if($integra){
                                    echo '<a class="btn btn-default" style="cursor: inherit; border-left: 20px solid '.$cor.'; text-shadow: 0 0 0 !important; background-image: none !important; background-color: #d9d9d9 !important; padding-top: 16px; padding-bottom: 16px;">';
                                    $icone_botao_sistema = '<i class="fa fa-joomla" aria-hidden="true"></i> ';
                                }else{
                                    if($sistemas){								
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
				<div id="aviso-integracao" style="width: 100%;text-align: center;margin-top: 24px;"></div>

				<?php
                    $data_atual = getDataHora();
                    /*
						* Adiciona os alertas de feriados.
                    */         
					$dados_feriados = DBRead('', 'tb_feriado', "WHERE data = '".substr($data_atual, 5,5)."' AND (tipo = 'Nacional' OR (tipo = 'Estadual' AND id_estado = '" . $dados[0]['id_estado'] . "') OR (tipo = 'Municipal' AND id_cidade = '" . $dados[0]['id_cidade'] . "'))", "tipo, nome");
           
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
						* Adiciona os alertas definidos para aparecer no início o fluxo.
            		*/            		
                	$dados_alerta = DBRead('', 'tb_alerta', "WHERE (id_contrato_plano_pessoa = '".$id_contrato_plano_pessoa."' OR id_contrato_plano_pessoa IS NULL) AND exibicao = 3 AND data_inicio <= '".$data_atual."' AND (data_vencimento IS NULL OR data_vencimento > '".$data_atual."')", "id_contrato_plano_pessoa, conteudo, data_inicio, data_vencimento");

					
                	if($dados_alerta){
                		
                		foreach ($dados_alerta as $conteudo) {
							echo "<hr><div class='row'>";
							echo '<div class="col-lg-12">';
							echo "<div class='alert alert-info text-center' style='margin-bottom: 0' role='alert'>";
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

					/*
						* Adiciona os alertas definidos para aparecer durante o fluxo.
            		*/            		
                	$dados_alerta = DBRead('', 'tb_alerta', "WHERE (id_contrato_plano_pessoa = '".$id_contrato_plano_pessoa."' OR id_contrato_plano_pessoa IS NULL) AND (exibicao = 1 OR exibicao = 5) AND data_inicio <= '".$data_atual."' AND (data_vencimento IS NULL OR data_vencimento > '".$data_atual."')", "id_contrato_plano_pessoa, conteudo, data_inicio, data_vencimento");
                	if($dados_alerta){
                		
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
                <form method="post" action="/api/ajax?class=Atendimento.php" id="atendimento_form" style="margin-bottom: 0;">
					<input type="hidden" name="token" value="<?php echo $request->token ?>">

                		<!--<input type="hidden" name="contrato" id="id_contrato_plano_pessoa" value="<?= $id_contrato_plano_pessoa ?>" />-->
						<!--<input type="hidden" name="id_cliente_integracao" id="id_cliente_integracao" />
						<input type="hidden" name="id_cidade" value="<?= $id_cidade ?>" id="id_cidade" />-->
						<input type="hidden" name="data_provedor" value="<?= getDataHora() ?>" id="data_provedor" />

						<p class="text-danger"></p>
                    
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>*Contato:</label>
                                    <input name="contato" autofocus id="contato" type="text" class="form-control input-sm" autocomplete="off" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>*Assinante: <span id='loading_assinante' class="text-primary"></span></label>
									<?php
									//Se integra altera o input de assinantes para um autocomplete que busca os assinantes da base do provedor
									if($integra){
										//Apresentação de assinante para não clientes descrito no quadro informativo para melhor orientação do atendente
										$nao_cliente = DBRead('', 'tb_informacao_geral_contrato', "WHERE id_contrato_plano_pessoa = $id_contrato_plano_pessoa LIMIT 1", "nao_cliente");
										echo '<input class="form-control input-sm assinante" placeholder="Não clientes: '.$nao_cliente[0]['nao_cliente'].'" id="busca_assinante" type="text" name="assinante" autocomplete="off"  value="" required>';
									}else{
										echo '<input name="assinante" id="assinante" type="text" class="form-control input-sm assinante" autocomplete="off" required>';
									}
									?>
									<span id='erro-busca-assinante'></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                            	<div class="form-group">
                                    <label>*Fone 1:</label>
                                    <input name="fone1" type="text" id='fone1' class="form-control input-sm phone" pattern="\([0-9]{2}\)[\s][0-9]{4,5}-[0-9]{4}" placeholder="(00) 00000-0000" maxlength="15" autocomplete="off" value='<?=$bina_atendimento?>' required>
                                </div>
                            </div>
                            <div class="col-md-6">
                            	<div class="form-group">
                                    <label>Fone 2:</label>
                                    <input name="fone2" id="fone2" type="text" class="form-control input-sm phone" pattern="\([0-9]{2}\)[\s][0-9]{4,5}-[0-9]{4}" placeholder="(00) 00000-0000" maxlength="15" autocomplete="off" />
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
										<h4 class="modal-title" id="myModalLabel">Catálogo de Equipamentos</h4>
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
                                    $(this).find('iframe').attr('src','./api/iframe?token=<?php echo $request->token ?>&view=exibe-manual&contrato=<?= $id_contrato_plano_pessoa ?>&iframe=1');
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
						<?php

						if($solicitacao_dados != 0 || $cpf_cnpj != 0){
							echo "<div class='row'>";
								if($cpf_cnpj != 0){
									echo "<div class='col-md-2'>";
										echo "<label for='tipo'>*Tipo</label>";
										echo "<select id='tipo' class='form-control input-sm'>";
											echo "<option value='cpf'>PF</option>";
											echo "<option value='cnpj'>PJ</option>";
										echo "</select>";
									echo "</div>";
									echo "<div class='col-md-4'>";
										echo "<label for='cpf_cnpj' id='label_cpf_cnpj'></label><span id='alerta-validacao-cpf_cnpj'><span id='alerta'></span></span>";
										echo "<input type='text' id='cpf_cnpj' name='cpf_cnpj' class='form-control input-sm cpf_cnpj' required />";
									echo "</div>";
									?>
									
									<script>
										function calc_digitos_posicoes(digitos, posicoes, soma_digitos){
											digitos = digitos.toString();
											for(var i = 0; i < digitos.length; i++){
												soma_digitos = soma_digitos + (digitos[i] * posicoes);
												posicoes--;
												if(posicoes < 2){ posicoes = 9; }
											}
											soma_digitos = soma_digitos % 11;
											if(soma_digitos < 2){ soma_digitos = 0; } 
											else { soma_digitos = 11 - soma_digitos; }
											var cpf = digitos + soma_digitos;
											return cpf;
										}
										function valida_cnpj(valor){
											valor = valor.toString();
											valor = valor.replace(/[^0-9]/g, '');
											var cnpj_original = valor;
											var primeiros_numeros_cnpj = valor.substr(0, 12);
											var primeiro_calculo = calc_digitos_posicoes(primeiros_numeros_cnpj, 5, 0);
											var segundo_calculo = calc_digitos_posicoes(primeiro_calculo, 6, 0);
											var cnpj = segundo_calculo;
											var iguais = false;
											if(cnpj_original == "11111111111111" || cnpj_original == "22222222222222" || cnpj_original == "33333333333333" || cnpj_original == "44444444444444" || cnpj_original == "55555555555555" || cnpj_original == "66666666666666" || cnpj_original == "77777777777777" || cnpj_original == "88888888888888" || cnpj_original == "99999999999999" || cnpj_original == "00000000000000" ){
												iguais = true;
											}
											if((cnpj === cnpj_original) && (!iguais)){ return true; }
										}
										function valida_cpf(valor){
											valor = valor.toString();
											valor = valor.replace(/[^0-9]/g, '');
											var digitos = valor.substr(0, 9);
											var novo_cpf = calc_digitos_posicoes(digitos, 10, 0);
											var novo_cpf = calc_digitos_posicoes(novo_cpf, 11, 0);
											var iguais = false;
											if(valor == "11111111111" || valor == "22222222222" || valor == "33333333333" || valor == "44444444444" || valor == "55555555555" || valor == "66666666666" || valor == "77777777777" || valor == "88888888888" || valor == "99999999999" || valor == "00000000000" ){
												iguais = true;
											}
											if((novo_cpf === valor) && (!iguais)){ return true; }
										}
										///// Função que verifica cpf ou cnpj e insere um alerta para o usuário!
										function verificaCpfCnpj(tipo){
											if(tipo == "CPF"){
												if(!valida_cpf($("#cpf_cnpj").val()) && $("#cpf_cnpj").val() != ""){
													$("#alerta").show().removeClass("text-success").addClass("text-danger");
													$("#alerta").html(" (Inválido)");
												}else if(valida_cpf($("#cpf_cnpj").val())){
													$("#alerta").show().removeClass("text-danger").addClass("text-success");
													$("#alerta").html(" (Válido)");
												}
											}else if(tipo == "CNPJ"){
												if(!valida_cnpj($("#cpf_cnpj").val()) && $("#cpf_cnpj").val() != ""){
													$("#alerta").show().removeClass("text-success").addClass("text-danger");
													$("#alerta").html(" (Inválido)");
												}else if(valida_cnpj($("#cpf_cnpj").val())){
													$("#alerta").show().removeClass("text-danger").addClass("text-success");
													$("#alerta").html(" (Válido)");
												}
											}
											$("#cpf_cnpj").on("keyup", function(){
												if(tipo == "CPF"){
													if(!valida_cpf($(this).val())){
														$("#alerta").show().removeClass("text-success").addClass("text-danger");
														$("#alerta").html(" (Inválido)");
													}else if(valida_cpf($(this).val())){
														$("#alerta").show().removeClass("text-danger").addClass("text-success");
														$("#alerta").html(" (Válido)");
													}
												}else if(tipo == "CNPJ"){
													if(!valida_cnpj($(this).val())){
														$("#alerta").show().removeClass("text-success").addClass("text-danger");
														$("#alerta").html(" (Inválido)");
													}else if(valida_cnpj($(this).val())){
														$("#alerta").show().removeClass("text-danger").addClass("text-success");
														$("#alerta").html(" (Válido)");
													}
												}
											});
										}
										///////////////////////////

										$('#label_cpf_cnpj').text('*CPF:');
									    $('#cpf_cnpj').mask('000.000.000-00', {reverse: true, placeholder: '000.000.000-00'});
										verificaCpfCnpj("CPF");
								    	$('#tipo').on('change', function(){
								    		var tipo = $(this).val();
									        if(tipo == 'cpf'){
												verificaCpfCnpj("CPF");
									            $('#label_cpf_cnpj').text('*CPF:');
									            $('#cpf_cnpj').mask('000.000.000-00', {reverse: true, placeholder: '000.000.000-00'});
									        }else{
												verificaCpfCnpj("CNPJ");
									            $('#label_cpf_cnpj').text('*CNPJ:');
									            $('#cpf_cnpj').mask('00.000.000/0000-00', {reverse: true, placeholder: '00.000.000/0000-00'});
									        }
									    });
									   
										

									</script>
									<?php
								}
								if($solicitacao_dados != 0){
									echo "<div class='col-md-6'>";
										echo "<label for='solicitacao'>*" . $label_solicitacao . ":</label>";
										echo "<input type='hidden' name='label_solicitacao' value='".$label_solicitacao."' />";
										echo "<input type='text' id='solicitacao' name='solicitacao' class='form-control input-sm' required />";
									echo "</div>";
								}

							echo "</div>";
						}

						echo "<input type='hidden' name='data_inicio' value='".getDataHora()."' />";
						?>

						<?php
						//Bloco onde são exibidas as informações do cliente na base de dados do sistema de gestão que está sendo integrado
						if($integra): 
						?>
						<div class="row">
							<div class="col-md-12" id='container-info-assinante'></div>
						</div>
                        <?php endif; ?>
						<div class="row">
							<div class="collapse" id="collapseFalha" style="margin-top: 20px;">
								<div class="panel panel-default"  style="margin-bottom: 0px;">
									<div class="panel-heading clearfix">
										<h3 class="panel-title text-left pull-left">Atendimento incompleto:</h3> 
									</div>
									<div class="panel-body">
										<?php
										$dados_falha = DBRead('', 'tb_tipo_falha_atendimento', "WHERE exibicao != '2' AND status = '1'", "id_tipo_falha_atendimento, opcao");
										foreach($dados_falha as $conteudo){
											echo "<button type='submit' name='falha_inicio' value='".$conteudo['id_tipo_falha_atendimento']."' class='btn btn-primary form-control btn-opcao'>".$conteudo['opcao']."</button>";
										}
										?>
									</div>
								</div>
							</div>
						</div>	
                    </div>
                    
                    <div class="panel-footer">
                        <div class="row">
                            <div class="col-md-12 text-center">
                                <div class="btn-group" role="group" aria-label="...">
                                	<input type="hidden" name="id_contrato_plano_pessoa" id="id_contrato_plano_pessoa" value="<?=$id_contrato_plano_pessoa?>" />
                                	<!--<input type="hidden" name="cpf_cnpj_atendimento_integracao" id="cpf_cnpj_atendimento_integracao" value="" />-->
								    <button type="submit" name="inserir" id='inserir' value='inserir' class="btn btn-primary"><i class="fa fa-play" aria-hidden="true"></i> Iniciar atendimento</button>
								    <button class="btn btn-atendimento-incompleto btn-warning" type="button" data-toggle="collapse" data-target="#collapseFalha" aria-expanded="false" aria-controls="collapseFalha"><i class="fa fa-stop" aria-hidden="true"></i> Atendimento incompleto</button>
								</div>
                            </div>
                        </div>
                </form>                
                </div>
            </div>
        </div>
        <div class="col-md-5">
        	<div class="panel panel-default">
				<div class="panel-heading clearfix">
                    <h3 class="panel-title text-left pull-left">Atendimentos:</h3>
                </div>
				<div class="panel-body" style="padding-bottom: 0;">
					<div class="row">
						<div class="col-md-3">
							<label>Atendente:</label>
							<select class="form-control input-sm" id="visualizar" onchange="call_busca_ajax()">
								<option value="0">Visualizar todos</option>
								<option value="1">Somente os meus</option>
							</select>
						</div>
						<div class="col-md-3">
							<label>Empresa:</label>
							<select class="form-control input-sm" id="empresa" onchange="call_busca_ajax()">
								<option value="0">Somente <?=$nome_contrato?></option>
								<option value="1">Visualizar todas</option>
							</select>
						</div>
						<div class="col-md-3">
							<label>Data:</label>
								<input type="text" class="form-control input-sm date calendar" onchange="call_busca_ajax()" name="data" id="data">
						</div>
						<div class="col-md-3">
							<label>Filtro:</label>
							<input type="text" class="form-control input-sm" id="filtro" onKeyUp="call_busca_ajax()"  value='<?=$bina_atendimento?>'>
						</div>
					</div>
					<hr>
					<div class="row">
						<div class="col-md-12">
							<div id="resultado_busca">
								<div class='alert alert-warning' role='alert' style='text-align: center'><strong>Estamos enfrentando problemas. Desculpe pelo transtorno!!</strong></div>
							</div>
						</div>
					</div>
				</div>
            </div>

        </div>
    </div>
</div>

<?php
//integracao
//Importação de arquivos externos para atendimentos integrados a sistemas de gestão
if($integra && $integra[0]['id_integracao'] == "1"):
	include "integracoes/atendimento-inicio-form-ixc.php";
endif;
?>
<script>

	var zone = $('#timezone').val();
	var myVar = setInterval(myTimer ,1000);
	function myTimer(){
		var d = new Date(), displayDate;
		if (navigator.userAgent.toLowerCase().indexOf('firefox') > -1) {
			displayDate = d.toLocaleTimeString('pt-BR');
		} else {
			displayDate = d.toLocaleTimeString('pt-BR', {timeZone: zone});
		}
			document.getElementById("hora-provedor").innerHTML = displayDate;
	}					 

	$('.btn-atendimento-incompleto.btn-warning').on('click', function(){
		$('.btn-atendimento-incompleto').html("<i class='fa fa-times' aria-hidden='true'></i> Fechar atendimento incompleto").removeClass('btn-warning').addClass('btn-danger');
	});

	$('#collapseFalha').on('shown.bs.collapse', function(){
		$('#contato').attr('required', false);
		$('#assinante').attr('required', false);
		$('#fone1').attr('required', false);
		$('#solicitacao').attr('required', false);
		$('#cpf_cnpj').attr('required', false);
	});

	$('#collapseFalha').on('hide.bs.collapse', function(){
		$('#contato').attr('required', true);
		$('#assinante').attr('required', true);
		$('#fone1').attr('required', true);
		$('#solicitacao').attr('required', true);
		$('#cpf_cnpj').attr('required', true);
		$('.btn-atendimento-incompleto').html("<i class='fa fa-stop' aria-hidden='true'></i> Atendimento incompleto").removeClass('btn-danger').addClass('btn-warning');
	});

	$(document).on('click', '#solicita_ajuda', function(){
		if(confirm('Deseja realmente solicitar ajuda?')){
			var id_contrato_plano_pessoa = <?php echo $id_contrato_plano_pessoa; ?>;
			$.ajax({
				type: "GET",
				url: "/api/ajax?class=SolicitaAjuda.php",
				dataType: "json",
				data: {
					id_contrato_plano_pessoa: id_contrato_plano_pessoa,
					token: '<?= $request->token ?>'
				},
				success: function(data){
					$("#solicita_ajuda").html("<i class='fa fa-exclamation' aria-hidden='true'></i> Ajuda solicitada").removeClass("btn-info").addClass("btn-danger").addClass("disabled");
				}
			});
		}	
	});

	$(document).on('click', '#btn-clima', function(){
		var id_contrato_plano_pessoa = <?php echo $id_contrato_plano_pessoa; ?>;
		$.ajax({
			type: "POST",
			url: "/api/ajax?class=ClimaModal.php",
			data: {
				id_contrato_plano_pessoa: id_contrato_plano_pessoa,
				token: '<?= $request->token ?>'
			},
			success: function(data){
				$("#conteudo-clima").html(data);
			},
			beforeSend: function(){
				$('#conteudo-clima').html('<div class="alert alert-info" role="alert" style="text-align: center">Buscando...</div>');
			}
		});
	});

	var verifica_ajuda = function(){
		$.ajax({
			cache: false,
			type: "POST",
			data: { 
				verificar:'1',
				token: '<?= $request->token ?>'
			},
			url:'/api/ajax?class=SolicitaAjuda.php',
			success: function(data){
				if(data == '0'){
					$("#solicita_ajuda").html("<i class='fa fa-question' aria-hidden='true'></i> Solicitar ajuda").removeClass("btn-danger").addClass("btn-info").removeClass("disabled");
				}
			}
		});
		setTimeout(function(){ verifica_ajuda(); },5000);
	};
	verifica_ajuda();

	$('#inserir').on('click', function(){
		var cpf_cnpj = $(".cpf_cnpj").val();
		if($('#cpf_cnpj').length && (($('#tipo').val() == 'cpf' && $('#cpf_cnpj').val().length < 14) || ($('#tipo').val() == 'cnpj' && $('#cpf_cnpj').val().length < 18))){
			alert('Preencha um '+$('#tipo').val().toUpperCase()+' válido em "'+$('#tipo').val().toUpperCase()+'"!');
			return false;
		}
	});
    <?php 
    if(!$integra){
    ?>
	$('#atendimento_form').on('submit', function(){				        
		modalAguarde();
    });
    <?php
    }
    ?>

	$('form').on('keydown', function(e) {
		if (e.which === 13 && !$(e.target).is('textarea')) {
			e.preventDefault();
		}
	});

	function call_busca_ajax(pagina){
        var inicia_busca = 1;
        var id_contrato_plano_pessoa = $('#id_contrato_plano_pessoa').val();
        var visualizar = $('#visualizar').val();
        var filtro = $('#filtro').val();
        var empresa = $('#empresa').val();
        var data = $("[name=data]").val();

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
        busca_ajax('<?= $request->token ?>' , 'AtendimentosRealizadosBusca', 'resultado_busca', parametros);
    }

    $(document).on('click', '.troca_pag', function(){
        call_busca_ajax($(this).attr('atr-pagina'));
    });

    call_busca_ajax();
</script>