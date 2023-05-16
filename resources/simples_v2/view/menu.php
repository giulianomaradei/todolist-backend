<?php
require_once(__DIR__."/../class/System.php");

$id_usuario = $_SESSION['id_usuario'];

function verificaMenu($menu, $perfil_sistema) {

	$dados = DBRead('', 'tb_pagina_sistema_perfil a', "INNER JOIN tb_pagina_sistema b ON a.id_pagina_sistema = b.id_pagina_sistema WHERE b.menu = '$menu' AND a.id_perfil_sistema = '$perfil_sistema'");
	if ($dados) {
		return true;
	} else {
		return false;
	}
}

function verificaSubmenu($pagina, $perfil_sistema) {

	$dados = DBRead('', 'tb_pagina_sistema_perfil a', "INNER JOIN tb_pagina_sistema b ON a.id_pagina_sistema = b.id_pagina_sistema WHERE b.nome_view = '$pagina' AND a.id_perfil_sistema = '$perfil_sistema'");
	if ($dados) {
		return true;
	} else {
		return false;
	}
}

$dados = DBRead('', 'tb_pagina_sistema', "WHERE nome_view = '$view'");
$menu = $dados[0]['menu'];
$active[$menu] = 'active';
$id_contrato_plano_pessoa = $_GET['id_contrato_plano_pessoa'];
?>

<style type="text/css">
	@media (max-width: 1390px) {
		.navbar-header {
			float: none;
		}
		.navbar-left,.navbar-right {
			float: none !important;
		}
		.navbar-toggle {
			display: block;
		}
		.navbar-collapse.in {
			overflow-y: auto !important;;
		}
		.navbar-collapse {
			border-top: 1px solid transparent;
			box-shadow: inset 0 1px 0 rgba(255,255,255,0.1);
		}
		.navbar-fixed-top {
			top: 0;
			border-width: 0 0 1px;
		}
		.navbar-collapse.collapse {
			display: none!important;
		}
		.navbar-nav {
			float: none!important;
			margin-top: 7.5px;
		}
		.navbar-nav>li {
			float: none;
		}
		.navbar-nav>li>a {
			padding-top: 10px;
			padding-bottom: 10px;
		}
		.collapse.in{
			display:block !important;
		}
	}
</style>

<div class="navbar navbar-default navbar-fixed-top noprint">
    <div class="container-fluid">
        <div class="navbar-header">
            <a href="/api/iframe?token=<?php echo $request->token ?>&view=home" id='btn-menu-home' class="navbar-brand"><img src="inc/img/logo.png" height="18" width="86"></a>
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-inverse-collapse">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
        </div>
        <div class="navbar-collapse collapse navbar-inverse-collapse">
            <ul class="nav navbar-nav">
                <?php
				if (verificaMenu('cadastros', $perfil_usuario)) {
					echo '
						<li class="dropdown ' . $active['cadastros'] . '">
							<a href="" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-folder-open"></i> Cadastros <strong class="caret"></strong></a>
							<ul class="dropdown-menu">
						';
                    echo '<li class="divider"></li>';
					
					if (verificaSubmenu('faq-busca', $perfil_usuario) || verificaSubmenu('faq-categoria-busca', $perfil_usuario)) {

						echo '<li class="dropdown dropdown-submenu">';
						echo '<a href="" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-question" aria-hidden="true"></i> FAQ</a>';
						echo '<ul class="dropdown-menu">';
						echo '<li class="divider"></li>';

						if (verificaSubmenu('faq-categoria-busca', $perfil_usuario)) {
<<<<<<< HEAD
							echo '<li ><a href="/api/iframe?token=<?php echo $request->token ?>&view=faq-categoria-busca"><i class="fa fa-tags" aria-hidden="true"></i> Categorias</a></li>';
							echo '<li class="divider"></li>';
						}	
						if (verificaSubmenu('faq-busca', $perfil_usuario)) {
							echo '<li ><a href="/api/iframe?token=<?php echo $request->token ?>&view=faq-busca"><i class="fa fa-cog" aria-hidden="true"></i> Gerenciar</a></li>';
=======
							echo '<li ><a href="/api/iframe?token='.$request->token.'&view=faq-categoria-busca"><i class="fa fa-tags" aria-hidden="true"></i> Categorias</a></li>';
							echo '<li class="divider"></li>';
						}	
						if (verificaSubmenu('faq-busca', $perfil_usuario)) {
							echo '<li ><a href="/api/iframe?token='.$request->token.'&view=faq-busca"><i class="fa fa-cog" aria-hidden="true"></i> Gerenciar</a></li>';
>>>>>>> 5cfe18e1256c22b2f7585786435771d80b84680b
							echo '<li class="divider"></li>';
                        }
                        					
						echo '</ul>';
						echo '</li>';
						echo '<li class="divider"></li>';
					}

                    if (verificaSubmenu('feriado-busca', $perfil_usuario)) {
<<<<<<< HEAD
						echo '<li ><a href="/api/iframe?token=<?php echo $request->token ?>&view=feriado-busca"><i class="fas fa-calendar-day" aria-hidden="true"></i> Feriados</a></li>';
=======
						echo '<li ><a href="/api/iframe?token='.$request->token.'&view=feriado-busca"><i class="fas fa-calendar-day" aria-hidden="true"></i> Feriados</a></li>';
>>>>>>> 5cfe18e1256c22b2f7585786435771d80b84680b
						echo '<li class="divider"></li>';
					}
					
					if (verificaSubmenu('email-modelo-busca', $perfil_usuario) || verificaSubmenu('email-enviar-busca', $perfil_usuario)) {
						echo '<li class="dropdown dropdown-submenu">';
						echo '<a href="" class="dropdown-toggle" data-toggle="dropdown"><i class="far fa-envelope" aria-hidden="true"></i> E-mail</a>';
						echo '<ul class="dropdown-menu">';
						echo '<li class="divider"></li>';

						if (verificaSubmenu('email-enviar-busca', $perfil_usuario)) {
<<<<<<< HEAD
							echo '<li ><a href="/api/iframe?token=<?php echo $request->token ?>&view=email-enviar-busca"><i class="fas fa-envelope-open-text" aria-hidden="true"></i> Enviar E-mail</a></li>';
							echo '<li class="divider"></li>';
						}	
						if (verificaSubmenu('email-modelo-busca', $perfil_usuario)) {
							echo '<li ><a href="/api/iframe?token=<?php echo $request->token ?>&view=email-modelo-busca"><i class="far fa-envelope" aria-hidden="true"></i> Modelos de E-mail</a></li>';
=======
							echo '<li ><a href="/api/iframe?token='.$request->token.'&view=email-enviar-busca"><i class="fas fa-envelope-open-text" aria-hidden="true"></i> Enviar E-mail</a></li>';
							echo '<li class="divider"></li>';
						}	
						if (verificaSubmenu('email-modelo-busca', $perfil_usuario)) {
							echo '<li ><a href="/api/iframe?token='.$request->token.'&view=email-modelo-busca"><i class="far fa-envelope" aria-hidden="true"></i> Modelos de E-mail</a></li>';
>>>>>>> 5cfe18e1256c22b2f7585786435771d80b84680b
							echo '<li class="divider"></li>';
                        }
                        					
						echo '</ul>';
						echo '</li>';
						echo '<li class="divider"></li>';
					}
                    
					if (verificaSubmenu('pessoa-busca', $perfil_usuario)) {
<<<<<<< HEAD
						echo '<li ><a href="/api/iframe?token=<?php echo $request->token ?>&view=pessoa-busca"><i class="fa fa-address-card-o"></i> Pessoas</a></li>';
=======
						echo '<li ><a href="/api/iframe?token='.$request->token.'&view=pessoa-busca"><i class="fa fa-address-card-o"></i> Pessoas</a></li>';
>>>>>>> 5cfe18e1256c22b2f7585786435771d80b84680b
						echo '<li class="divider"></li>';
                    }
                    
                    if (verificaSubmenu('erro-atendimento-busca', $perfil_usuario)) {
<<<<<<< HEAD
						echo '<li ><a href="/api/iframe?token=<?php echo $request->token ?>&view=erro-atendimento-busca"><i class="fa fa-bug" aria-hidden="true"></i> Reclamações/Erros</a></li>';
=======
						echo '<li ><a href="/api/iframe?token='.$request->token.'&view=erro-atendimento-busca"><i class="fa fa-bug" aria-hidden="true"></i> Reclamações/Erros</a></li>';
>>>>>>> 5cfe18e1256c22b2f7585786435771d80b84680b
						echo '<li class="divider"></li>';
					}					
					
					echo '
						</ul>
					</li>
					';
				}

				if (verificaMenu('call-center', $perfil_usuario)) {
					echo '
					<li class="dropdown ' . $active['call-center'] . '">
						<a href="" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-phone" aria-hidden="true"></i> Call Center <strong class="caret"></strong></a>
						<ul class="dropdown-menu">
					';
					echo '<li class="divider"></li>';
					
					if (verificaSubmenu('alerta-busca', $perfil_usuario) || verificaSubmenu('alerta-painel-busca', $perfil_usuario)) {
						echo '<li class="dropdown dropdown-submenu">';
						echo '<a href="" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-bell" aria-hidden="true"></i> Alertas</a>';
						echo '<ul class="dropdown-menu">';
						echo '<li class="divider"></li>';
						if (verificaSubmenu('alerta-busca', $perfil_usuario)) {
<<<<<<< HEAD
							echo '<li ><a href="/api/iframe?token=<?php echo $request->token ?>&view=alerta-busca"><i class="fa fa-cog"></i> Gerenciar</a></li>';
							echo '<li class="divider"></li>';
                        }
                        if (verificaSubmenu('alerta-painel-busca', $perfil_usuario)) {
							echo '<li ><a href="/api/iframe?token=<?php echo $request->token ?>&view=alerta-painel-busca"><i class="fas fa-desktop"></i> Painel do Cliente</a></li>';
=======
							echo '<li ><a href="/api/iframe?token='.$request->token.'&view=alerta-busca"><i class="fa fa-cog"></i> Gerenciar</a></li>';
							echo '<li class="divider"></li>';
                        }
                        if (verificaSubmenu('alerta-painel-busca', $perfil_usuario)) {
							echo '<li ><a href="/api/iframe?token='.$request->token.'&view=alerta-painel-busca"><i class="fas fa-desktop"></i> Painel do Cliente</a></li>';
>>>>>>> 5cfe18e1256c22b2f7585786435771d80b84680b
							echo '<li class="divider"></li>';
						}						
						
						echo '</ul>';
						echo '</li>';
						echo '<li class="divider"></li>';
					}

					if (verificaSubmenu('atendimento-busca', $perfil_usuario)) {
<<<<<<< HEAD
						echo '<li ><a href="/api/iframe?token=<?php echo $request->token ?>&view=atendimento-busca"><i class="fas fa-headset"></i> Atendimento</a></li>';
=======
						echo '<li ><a href="/api/iframe?token='.$request->token.'&view=atendimento-busca"><i class="fas fa-headset"></i> Atendimento</a></li>';
>>>>>>> 5cfe18e1256c22b2f7585786435771d80b84680b
						echo '<li class="divider"></li>';
					}

					if (verificaSubmenu('catalogo-equipamento-busca', $perfil_usuario) || verificaSubmenu('catalogo-equipamento-marca-busca', $perfil_usuario)) {
						echo '<li class="dropdown dropdown-submenu">';
						echo '<a href="" class="dropdown-toggle" data-toggle="dropdown"><i class="fas fa-wifi"></i> Catálogo de Equipamentos </a>';
						echo '<ul class="dropdown-menu">';
						echo '<li class="divider"></li>';
						if (verificaSubmenu('catalogo-equipamento-busca', $perfil_usuario)) {
<<<<<<< HEAD
							echo '<li ><a href="/api/iframe?token=<?php echo $request->token ?>&view=catalogo-equipamento-busca"><i class="fa fa-cog" aria-hidden="true"></i> Gerenciar</a></li>';
							echo '<li class="divider"></li>';
						}
						if (verificaSubmenu('catalogo-equipamento-marca-busca', $perfil_usuario)) {
							echo '<li ><a href="/api/iframe?token=<?php echo $request->token ?>&view=catalogo-equipamento-marca-busca"><i class="fab fa-creative-commons-sampling"></i> Marcas</a></li>';
=======
							echo '<li ><a href="/api/iframe?token='.$request->token.'&view=catalogo-equipamento-busca"><i class="fa fa-cog" aria-hidden="true"></i> Gerenciar</a></li>';
							echo '<li class="divider"></li>';
						}
						if (verificaSubmenu('catalogo-equipamento-marca-busca', $perfil_usuario)) {
							echo '<li ><a href="/api/iframe?token='.$request->token.'&view=catalogo-equipamento-marca-busca"><i class="fab fa-creative-commons-sampling"></i> Marcas</a></li>';
>>>>>>> 5cfe18e1256c22b2f7585786435771d80b84680b
							echo '<li class="divider"></li>';
						}
						
						echo '</ul>';
						echo '</li>';
						echo '<li class="divider"></li>';
					}

					if (verificaSubmenu('atendente-central-busca', $perfil_usuario) || verificaSubmenu('prefixo-central-busca', $perfil_usuario) || verificaSubmenu('telao-central', $perfil_usuario) || verificaSubmenu('controle-sessao-central', $perfil_usuario) || verificaSubmenu('converte-csv-xml-zabbix-form', $perfil_usuario)) {
						echo '<li class="dropdown dropdown-submenu">';
						echo '<a href="" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-tty" aria-hidden="true"></i> Central Telefônica</a>';
						echo '<ul class="dropdown-menu">';
						echo '<li class="divider"></li>';
						if (verificaSubmenu('atendente-central-busca', $perfil_usuario)) {
<<<<<<< HEAD
							echo '<li ><a href="/api/iframe?token=<?php echo $request->token ?>&view=atendente-central-busca"><i class="fa fa-user-o"></i> Atendentes</a></li>';
							echo '<li class="divider"></li>';
                        }
                        if (verificaSubmenu('controle-sessao-central', $perfil_usuario)) {
							echo '<li ><a href="/api/iframe?token=<?php echo $request->token ?>&view=controle-sessao-central"><i class="fa fa-phone-square" aria-hidden="true"></i> Controle de sessão</a></li>';
							echo '<li class="divider"></li>';
						}
						if (verificaSubmenu('converte-csv-xml-zabbix-form', $perfil_usuario)) {
							echo '<li ><a href="/api/iframe?token=<?php echo $request->token ?>&view=converte-csv-xml-zabbix-form"><i class="fa fa-refresh"></i> Conversor Zabbix</a></li>';
							echo '<li class="divider"></li>';
						}				
						if (verificaSubmenu('prefixo-central-busca', $perfil_usuario)) {
							echo '<li ><a href="/api/iframe?token=<?php echo $request->token ?>&view=prefixo-central-busca"><i class="fa fa-product-hunt"></i> Prefixos</a></li>';
							echo '<li class="divider"></li>';
                        }
                        if (verificaSubmenu('telao-central', $perfil_usuario)) {
							echo '<li ><a href="/api/iframe?token=<?php echo $request->token ?>&view=telao-central" target="_blank"><i class="fa fa-tv" aria-hidden="true"></i> Telão de monitoramento</a></li>';
=======
							echo '<li ><a href="/api/iframe?token='.$request->token.'&view=atendente-central-busca"><i class="fa fa-user-o"></i> Atendentes</a></li>';
							echo '<li class="divider"></li>';
                        }
                        if (verificaSubmenu('controle-sessao-central', $perfil_usuario)) {
							echo '<li ><a href="/api/iframe?token='.$request->token.'&view=controle-sessao-central"><i class="fa fa-phone-square" aria-hidden="true"></i> Controle de sessão</a></li>';
							echo '<li class="divider"></li>';
						}
						if (verificaSubmenu('converte-csv-xml-zabbix-form', $perfil_usuario)) {
							echo '<li ><a href="/api/iframe?token='.$request->token.'&view=converte-csv-xml-zabbix-form"><i class="fa fa-refresh"></i> Conversor Zabbix</a></li>';
							echo '<li class="divider"></li>';
						}				
						if (verificaSubmenu('prefixo-central-busca', $perfil_usuario)) {
							echo '<li ><a href="/api/iframe?token='.$request->token.'&view=prefixo-central-busca"><i class="fa fa-product-hunt"></i> Prefixos</a></li>';
							echo '<li class="divider"></li>';
                        }
                        if (verificaSubmenu('telao-central', $perfil_usuario)) {
							echo '<li ><a href="/api/iframe?token='.$request->token.'&view=telao-central" target="_blank"><i class="fa fa-tv" aria-hidden="true"></i> Telão de monitoramento</a></li>';
>>>>>>> 5cfe18e1256c22b2f7585786435771d80b84680b
							echo '<li class="divider"></li>';
						}
						echo '</ul>';
						echo '</li>';
						echo '<li class="divider"></li>';
					}

					if (verificaSubmenu('chat-escala-horarios', $perfil_usuario) || verificaSubmenu('grupo-atendimento-chat-busca', $perfil_usuario) || verificaSubmenu('telao-atendimento-chat', $perfil_usuario)) {
						echo '<li class="dropdown dropdown-submenu">';
						echo '<a href="" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-braille"></i> Chat</a>';
						echo '<ul class="dropdown-menu">';
						echo '<li class="divider"></li>';
						
						if (verificaSubmenu('chat-escala-horarios', $perfil_usuario)) {
<<<<<<< HEAD
							echo '<li ><a href="/api/iframe?token=<?php echo $request->token ?>&view=chat-escala-horarios"><i class="fa fa-table"></i> Horários de Chat</a></li>';
=======
							echo '<li ><a href="/api/iframe?token='.$request->token.'&view=chat-escala-horarios"><i class="fa fa-table"></i> Horários de Chat</a></li>';
>>>>>>> 5cfe18e1256c22b2f7585786435771d80b84680b
							echo '<li class="divider"></li>';
						}

						if (verificaSubmenu('grupo-atendimento-chat-busca', $perfil_usuario)) {
<<<<<<< HEAD
							echo '<li ><a href="/api/iframe?token=<?php echo $request->token ?>&view=grupo-atendimento-chat-busca"><i class="fa fa-layer-group" aria-hidden="true"></i> Grupos de Atendimentos por Chat</a></li>';
=======
							echo '<li ><a href="/api/iframe?token='.$request->token.'&view=grupo-atendimento-chat-busca"><i class="fa fa-layer-group" aria-hidden="true"></i> Grupos de Atendimentos por Chat</a></li>';
>>>>>>> 5cfe18e1256c22b2f7585786435771d80b84680b
							echo '<li class="divider"></li>';
						}

						if (verificaSubmenu('telao-atendimento-chat', $perfil_usuario)) {
<<<<<<< HEAD
							echo '<li ><a href="/api/iframe?token=<?php echo $request->token ?>&view=telao-atendimento-chat" target="_blank"><i class="fa fa-slideshare" aria-hidden="true"></i> Telão de atendimento chat</a></li>';
=======
							echo '<li ><a href="/api/iframe?token='.$request->token.'&view=telao-atendimento-chat" target="_blank"><i class="fa fa-slideshare" aria-hidden="true"></i> Telão de atendimento chat</a></li>';
>>>>>>> 5cfe18e1256c22b2f7585786435771d80b84680b
							echo '<li class="divider"></li>';
						}
						
						echo '</ul>';
						echo '</li>';
						echo '<li class="divider"></li>';
					}

					if (verificaSubmenu('equipe-busca', $perfil_usuario)) {
<<<<<<< HEAD
						echo '<li ><a href="/api/iframe?token=<?php echo $request->token ?>&view=equipe-busca"><i class="fa fa-cubes" aria-hidden="true"></i> Equipes</a></li>';
=======
						echo '<li ><a href="/api/iframe?token='.$request->token.'&view=equipe-busca"><i class="fa fa-cubes" aria-hidden="true"></i> Equipes</a></li>';
>>>>>>> 5cfe18e1256c22b2f7585786435771d80b84680b
						echo '<li class="divider"></li>';
					}	

					if (verificaSubmenu('escalas-liberar', $perfil_usuario) || verificaSubmenu('exibe-escala', $perfil_usuario) || verificaSubmenu('gerenciar-escala', $perfil_usuario) || verificaSubmenu('escala-horarios', $perfil_usuario) || verificaSubmenu('ferias', $perfil_usuario) || verificaSubmenu('intervalo', $perfil_usuario)) {
						echo '<li class="dropdown dropdown-submenu">';
						echo '<a href="" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-braille"></i> Escalas</a>';
						echo '<ul class="dropdown-menu">';
						echo '<li class="divider"></li>';
						if (verificaSubmenu('intervalo', $perfil_usuario)) {
<<<<<<< HEAD
							echo '<li ><a href="/api/iframe?token=<?php echo $request->token ?>&view=intervalo"><i class="fa fa-coffee "></i> Controle de Intervalos</a></li>';
							echo '<li class="divider"></li>';
						}
						if (verificaSubmenu('gerenciar-escala', $perfil_usuario)) {
							echo '<li ><a href="/api/iframe?token=<?php echo $request->token ?>&view=gerenciar-escala"><i class="fa fa-clock-o"></i> Disponibilidade</a></li>';
							echo '<li class="divider"></li>';
						}
						if (verificaSubmenu('ferias', $perfil_usuario)) {
							echo '<li ><a href="/api/iframe?token=<?php echo $request->token ?>&view=ferias"><i class="fa fa-suitcase "></i> Férias e afastamaentos</a></li>';
=======
							echo '<li ><a href="/api/iframe?token='.$request->token.'&view=intervalo"><i class="fa fa-coffee "></i> Controle de Intervalos</a></li>';
							echo '<li class="divider"></li>';
						}
						if (verificaSubmenu('gerenciar-escala', $perfil_usuario)) {
							echo '<li ><a href="/api/iframe?token='.$request->token.'&view=gerenciar-escala"><i class="fa fa-clock-o"></i> Disponibilidade</a></li>';
							echo '<li class="divider"></li>';
						}
						if (verificaSubmenu('ferias', $perfil_usuario)) {
							echo '<li ><a href="/api/iframe?token='.$request->token.'&view=ferias"><i class="fa fa-suitcase "></i> Férias e afastamaentos</a></li>';
>>>>>>> 5cfe18e1256c22b2f7585786435771d80b84680b
							echo '<li class="divider"></li>';
						}
						if (verificaSubmenu('escala-horarios', $perfil_usuario) ) {
							if ($perfil_usuario == 3) {
<<<<<<< HEAD
								echo '<li ><a href="/api/iframe?token=<?php echo $request->token ?>&view=exibe-escala-horarios&visualizar=' . $id_usuario . '"><i class="fa fa-table"></i> Horarios</a></li>';
								echo '<li class="divider"></li>';
							} else {
								echo '<li ><a href="/api/iframe?token=<?php echo $request->token ?>&view=escala-horarios"><i class="fa fa-table"></i> Horários</a></li>';
=======
								echo '<li ><a href="/api/iframe?token='.$request->token.'&view=exibe-escala-horarios&visualizar=' . $id_usuario . '"><i class="fa fa-table"></i> Horarios</a></li>';
								echo '<li class="divider"></li>';
							} else {
								echo '<li ><a href="/api/iframe?token='.$request->token.'&view=escala-horarios"><i class="fa fa-table"></i> Horários</a></li>';
>>>>>>> 5cfe18e1256c22b2f7585786435771d80b84680b
								echo '<li class="divider"></li>';
							}

						}
						if (verificaSubmenu('escalas-liberar', $perfil_usuario)) {
<<<<<<< HEAD
							echo '<li ><a href="/api/iframe?token=<?php echo $request->token ?>&view=escalas-liberar"><i class="fa fa-unlock "></i> Liberar/Bloquear escalas</a></li>';
=======
							echo '<li ><a href="/api/iframe?token='.$request->token.'&view=escalas-liberar"><i class="fa fa-unlock "></i> Liberar/Bloquear escalas</a></li>';
>>>>>>> 5cfe18e1256c22b2f7585786435771d80b84680b
							echo '<li class="divider"></li>';
						}
						echo '</ul>';
						echo '</li>';
						echo '<li class="divider"></li>';
					}

					if (verificaSubmenu('arvore-exibe', $perfil_usuario) || verificaSubmenu('area-problema-busca', $perfil_usuario) || verificaSubmenu('arvore-fluxo-busca', $perfil_usuario) || verificaSubmenu('frases-uteis', $perfil_usuario) || verificaSubmenu('instrucao-pergunta-busca', $perfil_usuario) || verificaSubmenu('opcao-resposta-busca', $perfil_usuario) || verificaSubmenu('situacao-busca', $perfil_usuario) || verificaSubmenu('texto-os-busca', $perfil_usuario) || verificaSubmenu('texto-os-busca', $perfil_usuario) || verificaSubmenu('tipo-falha-atendimento-busca', $perfil_usuario)) {

						echo '<li class="dropdown dropdown-submenu">';
						echo '<a href="" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-code-fork"></i> Fluxo</a>';
						echo '<ul class="dropdown-menu">';
						echo '<li class="divider"></li>';
						if (verificaSubmenu('arvore-exibe', $perfil_usuario)) {
							echo '<li ><a href="#"><i class="fa fa-tree"></i> Árvore</a></li>';
							echo '<li class="divider"></li>';
						}
						if (verificaSubmenu('area-problema-busca', $perfil_usuario)) {
<<<<<<< HEAD
							echo '<li ><a href="/api/iframe?token=<?php echo $request->token ?>&view=area-problema-busca"><i class="fa fa-compass"></i> Áreas de problemas</a></li>';
							echo '<li class="divider"></li>';
						}
						if (verificaSubmenu('arvore-fluxo-busca', $perfil_usuario)) {
							echo '<li ><a href="/api/iframe?token=<?php echo $request->token ?>&view=arvore-fluxo-busca"><i class="fa fa-tasks"></i> Fluxo de contratos</a></li>';
							echo '<li class="divider"></li>';
						}
						if (verificaSubmenu('frases-uteis', $perfil_usuario)) {
							echo '<li ><a href="/api/iframe?token=<?php echo $request->token ?>&view=frases-uteis"><i class="fa fa-underline"></i> Frases úteis</a></li>';
							echo '<li class="divider"></li>';
						}
						if (verificaSubmenu('instrucao-pergunta-busca', $perfil_usuario)) {
							echo '<li ><a href="/api/iframe?token=<?php echo $request->token ?>&view=instrucao-pergunta-busca"><i class="fa fa-question-circle"></i> Instruções / Perguntas</a></li>';
							echo '<li class="divider"></li>';
						}
						if (verificaSubmenu('opcao-resposta-busca', $perfil_usuario)) {
							echo '<li ><a href="/api/iframe?token=<?php echo $request->token ?>&view=opcao-resposta-busca"><i class="fa fa-bars"></i> Opções / Respostas</a></li>';
							echo '<li class="divider"></li>';
						}
						if (verificaSubmenu('situacao-busca', $perfil_usuario)) {
							echo '<li ><a href="/api/iframe?token=<?php echo $request->token ?>&view=situacao-busca"><i class="fa fa-crosshairs"></i> Situações</a></li>';
							echo '<li class="divider"></li>';
						}
						if (verificaSubmenu('texto-os-busca', $perfil_usuario)) {
							echo '<li ><a href="/api/iframe?token=<?php echo $request->token ?>&view=texto-os-busca"><i class="fa fa-list-alt"></i> Textos de OS</a></li>';
							echo '<li class="divider"></li>';
						}
						if (verificaSubmenu('tipo-falha-atendimento-busca', $perfil_usuario)) {
							echo '<li ><a href="/api/iframe?token=<?php echo $request->token ?>&view=tipo-falha-atendimento-busca"><i class="fa fa-thumbs-down"></i> Tipos de falha de atendimento</a></li>';
=======
							echo '<li ><a href="/api/iframe?token='.$request->token.'&view=area-problema-busca"><i class="fa fa-compass"></i> Áreas de problemas</a></li>';
							echo '<li class="divider"></li>';
						}
						if (verificaSubmenu('arvore-fluxo-busca', $perfil_usuario)) {
							echo '<li ><a href="/api/iframe?token='.$request->token.'&view=arvore-fluxo-busca"><i class="fa fa-tasks"></i> Fluxo de contratos</a></li>';
							echo '<li class="divider"></li>';
						}
						if (verificaSubmenu('frases-uteis', $perfil_usuario)) {
							echo '<li ><a href="/api/iframe?token='.$request->token.'&view=frases-uteis"><i class="fa fa-underline"></i> Frases úteis</a></li>';
							echo '<li class="divider"></li>';
						}
						if (verificaSubmenu('instrucao-pergunta-busca', $perfil_usuario)) {
							echo '<li ><a href="/api/iframe?token='.$request->token.'&view=instrucao-pergunta-busca"><i class="fa fa-question-circle"></i> Instruções / Perguntas</a></li>';
							echo '<li class="divider"></li>';
						}
						if (verificaSubmenu('opcao-resposta-busca', $perfil_usuario)) {
							echo '<li ><a href="/api/iframe?token='.$request->token.'&view=opcao-resposta-busca"><i class="fa fa-bars"></i> Opções / Respostas</a></li>';
							echo '<li class="divider"></li>';
						}
						if (verificaSubmenu('situacao-busca', $perfil_usuario)) {
							echo '<li ><a href="/api/iframe?token='.$request->token.'&view=situacao-busca"><i class="fa fa-crosshairs"></i> Situações</a></li>';
							echo '<li class="divider"></li>';
						}
						if (verificaSubmenu('texto-os-busca', $perfil_usuario)) {
							echo '<li ><a href="/api/iframe?token='.$request->token.'&view=texto-os-busca"><i class="fa fa-list-alt"></i> Textos de OS</a></li>';
							echo '<li class="divider"></li>';
						}
						if (verificaSubmenu('tipo-falha-atendimento-busca', $perfil_usuario)) {
							echo '<li ><a href="/api/iframe?token='.$request->token.'&view=tipo-falha-atendimento-busca"><i class="fa fa-thumbs-down"></i> Tipos de falha de atendimento</a></li>';
>>>>>>> 5cfe18e1256c22b2f7585786435771d80b84680b
							echo '<li class="divider"></li>';
						}
						echo '</ul>';
						echo '</li>';
						echo '<li class="divider"></li>';
					}

					if (verificaSubmenu('metas-busca', $perfil_usuario)) {
<<<<<<< HEAD
						echo '<li ><a href="/api/iframe?token=<?php echo $request->token ?>&view=metas-busca"><i class="fa fa-line-chart" aria-hidden="true"></i> Metas </a></li>';
=======
						echo '<li ><a href="/api/iframe?token='.$request->token.'&view=metas-busca"><i class="fa fa-line-chart" aria-hidden="true"></i> Metas </a></li>';
>>>>>>> 5cfe18e1256c22b2f7585786435771d80b84680b
						echo '<li class="divider"></li>';
					}

					if (verificaSubmenu('monitoramento-busca', $perfil_usuario)){
<<<<<<< HEAD
						echo '<li ><a href="/api/iframe?token=<?php echo $request->token ?>&view=monitoramento-busca"><i class="fa fa-podcast" aria-hidden="true"></i> Monitoramento</a></li>';
=======
						echo '<li ><a href="/api/iframe?token='.$request->token.'&view=monitoramento-busca"><i class="fa fa-podcast" aria-hidden="true"></i> Monitoramento</a></li>';
>>>>>>> 5cfe18e1256c22b2f7585786435771d80b84680b
						echo '<li class="divider"></li>';
					}

					if (verificaSubmenu('monitoria-quesito-busca', $perfil_usuario) || verificaSubmenu('monitoria-quesito-form', $perfil_usuario) || verificaSubmenu('monitoria-formulario-busca', $perfil_usuario) || verificaSubmenu('monitoria-avaliacao-busca', $perfil_usuario) || verificaSubmenu('monitoria-formulario-busca', $perfil_usuario) || verificaSubmenu('monitoria-plano-acao-busca', $perfil_usuario) || verificaSubmenu('responsavel-atendimento', $perfil_usuario)) {
						echo '<li class="dropdown dropdown-submenu">';
						echo '<a href="" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-tripadvisor" aria-hidden="true"></i> Monitoria </a>';
						echo '<ul class="dropdown-menu">';
						echo '<li class="divider"></li>';
						if (verificaSubmenu('monitoria-avaliacao-busca', $perfil_usuario)) {
<<<<<<< HEAD
							echo '<li ><a href="/api/iframe?token=<?php echo $request->token ?>&view=monitoria-avaliacao-busca"><i class="fa fa fa-gavel" aria-hidden="true"></i> Avaliar</a></li>';
							echo '<li class="divider"></li>';
						}
						if (verificaSubmenu('monitoria-classificacao-atendente-busca', $perfil_usuario)) {
							echo '<li ><a href="/api/iframe?token=<?php echo $request->token ?>&view=monitoria-classificacao-atendente-busca"><i class="fas fa-address-card" aria-hidden="true"></i> Classificação de atendentes</a></li>';
							echo '<li class="divider"></li>';
						}
						if (verificaSubmenu('monitoria-formulario-busca', $perfil_usuario)) {
							echo '<li ><a href="/api/iframe?token=<?php echo $request->token ?>&view=monitoria-formulario-busca"><i class="fa fa fa-edit" aria-hidden="true"></i> Formulário de avaliação</a></li>';
							echo '<li class="divider"></li>';
						}
						if (verificaSubmenu('monitoria-plano-acao-busca', $perfil_usuario)) {
							echo '<li ><a href="/api/iframe?token=<?php echo $request->token ?>&view=monitoria-plano-acao-busca"><i class="fa fa fa-weixin" aria-hidden="true"></i> Plano de ação</a></li>';
							echo '<li class="divider"></li>';
						}
						if (verificaSubmenu('monitoria-quesito-busca', $perfil_usuario)) {
							echo '<li ><a href="/api/iframe?token=<?php echo $request->token ?>&view=monitoria-quesito-busca"><i class="fa fa fa-list-ul" aria-hidden="true"></i> Quesitos</a></li>';
=======
							echo '<li ><a href="/api/iframe?token='.$request->token.'&view=monitoria-avaliacao-busca"><i class="fa fa fa-gavel" aria-hidden="true"></i> Avaliar</a></li>';
							echo '<li class="divider"></li>';
						}
						if (verificaSubmenu('monitoria-classificacao-atendente-busca', $perfil_usuario)) {
							echo '<li ><a href="/api/iframe?token='.$request->token.'&view=monitoria-classificacao-atendente-busca"><i class="fas fa-address-card" aria-hidden="true"></i> Classificação de atendentes</a></li>';
							echo '<li class="divider"></li>';
						}
						if (verificaSubmenu('monitoria-formulario-busca', $perfil_usuario)) {
							echo '<li ><a href="/api/iframe?token='.$request->token.'&view=monitoria-formulario-busca"><i class="fa fa fa-edit" aria-hidden="true"></i> Formulário de avaliação</a></li>';
							echo '<li class="divider"></li>';
						}
						if (verificaSubmenu('monitoria-plano-acao-busca', $perfil_usuario)) {
							echo '<li ><a href="/api/iframe?token='.$request->token.'&view=monitoria-plano-acao-busca"><i class="fa fa fa-weixin" aria-hidden="true"></i> Plano de ação</a></li>';
							echo '<li class="divider"></li>';
						}
						if (verificaSubmenu('monitoria-quesito-busca', $perfil_usuario)) {
							echo '<li ><a href="/api/iframe?token='.$request->token.'&view=monitoria-quesito-busca"><i class="fa fa fa-list-ul" aria-hidden="true"></i> Quesitos</a></li>';
>>>>>>> 5cfe18e1256c22b2f7585786435771d80b84680b
							echo '<li class="divider"></li>';
						}
						echo '</ul>';
						echo '</li>';
						echo '<li class="divider"></li>';
                    }  

					if (verificaSubmenu('gerenciar-pesquisa-busca', $perfil_usuario) || verificaSubmenu('pesquisa-entrevistar-busca', $perfil_usuario)) {
						echo '<li class="dropdown dropdown-submenu">';
						echo '<a href="" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-binoculars" aria-hidden="true"></i> Pesquisa</a>';
						echo '<ul class="dropdown-menu">';
						echo '<li class="divider"></li>';
						if (verificaSubmenu('pesquisa-entrevistar-busca', $perfil_usuario)) {
<<<<<<< HEAD
							echo '<li ><a href="/api/iframe?token=<?php echo $request->token ?>&view=pesquisa-entrevistar-busca"><i class="fa fa-podcast" aria-hidden="true"></i> Entrevistar</a></li>';
							echo '<li class="divider"></li>';
						}
						if (verificaSubmenu('gerenciar-pesquisa-busca', $perfil_usuario)) {
							echo '<li ><a href="/api/iframe?token=<?php echo $request->token ?>&view=gerenciar-pesquisa-busca"><i class="fa fa-cog" aria-hidden="true"></i> Gerenciar</a></li>';
=======
							echo '<li ><a href="/api/iframe?token='.$request->token.'&view=pesquisa-entrevistar-busca"><i class="fa fa-podcast" aria-hidden="true"></i> Entrevistar</a></li>';
							echo '<li class="divider"></li>';
						}
						if (verificaSubmenu('gerenciar-pesquisa-busca', $perfil_usuario)) {
							echo '<li ><a href="/api/iframe?token='.$request->token.'&view=gerenciar-pesquisa-busca"><i class="fa fa-cog" aria-hidden="true"></i> Gerenciar</a></li>';
>>>>>>> 5cfe18e1256c22b2f7585786435771d80b84680b
							echo '<li class="divider"></li>';
						}
						echo '</ul>';
						echo '</li>';
						echo '<li class="divider"></li>';
					}

					if (verificaSubmenu('quadro-informativo', $perfil_usuario)) {
<<<<<<< HEAD
						echo '<li ><a href="/api/iframe?token=<?php echo $request->token ?>&view=quadro-informativo"><i class="fa fa-info" aria-hidden="true"></i> Quadro informativo</a></li>';
=======
						echo '<li ><a href="/api/iframe?token='.$request->token.'&view=quadro-informativo"><i class="fa fa-info" aria-hidden="true"></i> Quadro informativo</a></li>';
>>>>>>> 5cfe18e1256c22b2f7585786435771d80b84680b
						echo '<li class="divider"></li>';
					}

					if (verificaSubmenu('responsavel-atendimento', $perfil_usuario)) {

						echo '<li class="dropdown dropdown-submenu">';
						echo '<a href="" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-hands-helping" aria-hidden="true"></i> Responsáveis pelos Atendimentos</a>';
						echo '<ul class="dropdown-menu">';
						echo '<li class="divider"></li>';
						if (verificaSubmenu('responsavel-atendimento', $perfil_usuario)) {
<<<<<<< HEAD
							echo '<li ><a href="/api/iframe?token=<?php echo $request->token ?>&view=responsavel-atendimento"><i class="fa fa-cog" aria-hidden="true"></i> Gerenciar</a></li>';
=======
							echo '<li ><a href="/api/iframe?token='.$request->token.'&view=responsavel-atendimento"><i class="fa fa-cog" aria-hidden="true"></i> Gerenciar</a></li>';
>>>>>>> 5cfe18e1256c22b2f7585786435771d80b84680b
							echo '<li class="divider"></li>';
						}
						
						/* if (verificaSubmenu('grupo-atendimento-chat-vincular-operador', $perfil_usuario)) {
<<<<<<< HEAD
							echo '<li ><a href="/api/iframe?token=<?php echo $request->token ?>&view=grupo-atendimento-chat-busca"><i class="fa fa-layer-group" aria-hidden="true"></i> Grupos de Atendimentos por Chat - Vincular Operadores</a></li>';
=======
							echo '<li ><a href="/api/iframe?token='.$request->token.'&view=grupo-atendimento-chat-busca"><i class="fa fa-layer-group" aria-hidden="true"></i> Grupos de Atendimentos por Chat - Vincular Operadores</a></li>';
>>>>>>> 5cfe18e1256c22b2f7585786435771d80b84680b
							echo '<li class="divider"></li>';
						} */
						echo '</ul>';
						echo '</li>';
						echo '<li class="divider"></li>';
					}

					if (verificaSubmenu('solicitacoes-ajuda', $perfil_usuario)) {
<<<<<<< HEAD
						echo '<li ><a href="/api/iframe?token=<?php echo $request->token ?>&view=solicitacoes-ajuda"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> Solicitações de ajuda</a></li>';
=======
						echo '<li ><a href="/api/iframe?token='.$request->token.'&view=solicitacoes-ajuda"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> Solicitações de ajuda</a></li>';
>>>>>>> 5cfe18e1256c22b2f7585786435771d80b84680b
						echo '<li class="divider"></li>';
                    }
                    
                    if (verificaSubmenu('telao-atendimento', $perfil_usuario)) {
<<<<<<< HEAD
						echo '<li ><a href="/api/iframe?token=<?php echo $request->token ?>&view=telao-atendimento" target="_blank"><i class="fa fa-slideshare" aria-hidden="true"></i> Telão de atendimento</a></li>';
=======
						echo '<li ><a href="/api/iframe?token='.$request->token.'&view=telao-atendimento" target="_blank"><i class="fa fa-slideshare" aria-hidden="true"></i> Telão de atendimento</a></li>';
>>>>>>> 5cfe18e1256c22b2f7585786435771d80b84680b
						echo '<li class="divider"></li>';
					}

					if (verificaSubmenu('telao-alerta', $perfil_usuario)) {
<<<<<<< HEAD
						echo '<li ><a href="/api/iframe?token=<?php echo $request->token ?>&view=telao-alerta" target="_blank"><i class="fa fa-slideshare" aria-hidden="true"></i> Telão de Alertas</a></li>';
=======
						echo '<li ><a href="/api/iframe?token='.$request->token.'&view=telao-alerta" target="_blank"><i class="fa fa-slideshare" aria-hidden="true"></i> Telão de Alertas</a></li>';
>>>>>>> 5cfe18e1256c22b2f7585786435771d80b84680b
						echo '<li class="divider"></li>';
					}

					

					echo '
						</ul>
					</li>
					';
				}					
				
				if (verificaMenu('comercial', $perfil_usuario)) {
					echo '
						<li class="dropdown ' . $active['comercial'] . '">
							<a href="" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-handshake-o"></i> Comercial <strong class="caret"></strong></a>
							<ul class="dropdown-menu">
						';
					echo '<li class="divider"></li>';
					if (verificaSubmenu('contrato-busca', $perfil_usuario)) {
<<<<<<< HEAD
						echo '<li ><a href="/api/iframe?token=<?php echo $request->token ?>&view=contrato-busca"><i class="fa fa-file-text-o "></i> Contratos</a></li>';
=======
						echo '<li ><a href="/api/iframe?token='.$request->token.'&view=contrato-busca"><i class="fa fa-file-text-o "></i> Contratos</a></li>';
>>>>>>> 5cfe18e1256c22b2f7585786435771d80b84680b
						echo '<li class="divider"></li>';
					}
					
					if (verificaSubmenu('lead-busca', $perfil_usuario) || verificaSubmenu('lead-negocios-busca', $perfil_usuario) || verificaSubmenu('lead-negociacoes-pausadas', $perfil_usuario) || verificaSubmenu('lead-timeline', $perfil_usuario) || verificaSubmenu('lead-negocio-perdido-ganho', $perfil_usuario) || verificaSubmenu('lead-negocio-form', $perfil_usuario) || verificaSubmenu('lead-conversao-busca', $perfil_usuario)) {
						echo '<li class="dropdown dropdown-submenu">';
						echo '<a href="" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-briefcase" aria-hidden="true"></i> Negócios</a>';
						echo '<ul class="dropdown-menu">';
						echo '<li class="divider"></li>';
						if (verificaSubmenu('lead-negocio-perdido-ganho', $perfil_usuario)) {
<<<<<<< HEAD
							echo '<li ><a href="/api/iframe?token=<?php echo $request->token ?>&view=lead-negocio-perdido-ganho"><i class="fas fa-book-open" aria-hidden="true"></i> Acompanhamento</a></li>';
							echo '<li class="divider"></li>';
						}		
						if (verificaSubmenu('lead-conversao-busca', $perfil_usuario)) {
							echo '<li ><a href="/api/iframe?token=<?php echo $request->token ?>&view=lead-conversao-busca"><i class="fas fa-check-double"></i></i> Conversões RD</a></li>';
							echo '<li class="divider"></li>';
						}
						if (verificaSubmenu('lead-tag-busca', $perfil_usuario)) {
							echo '<li ><a href="/api/iframe?token=<?php echo $request->token ?>&view=lead-tag-busca"><i class="fas fa-tags"></i></i> Configurar Tags RD</a></li>';
							echo '<li class="divider"></li>';
						}				
						if (verificaSubmenu('lead-negocios-busca', $perfil_usuario)) {
							echo '<li ><a href="/api/iframe?token=<?php echo $request->token ?>&view=lead-negocios-busca"><i class="fa fa-black-tie" aria-hidden="true"></i> Dashboard</a></li>';
							echo '<li class="divider"></li>';
						}
						if (verificaSubmenu('lead-negocio-form', $perfil_usuario)) {
							echo '<li ><a href="/api/iframe?token=<?php echo $request->token ?>&view=lead-negocio-form"><i class="fa fa-plus" aria-hidden="true"></i> Novo negócio</a></li>';
							echo '<li class="divider"></li>';
						}	
						if (verificaSubmenu('lead-timeline', $perfil_usuario)) {
							echo '<li ><a href="/api/iframe?token=<?php echo $request->token ?>&view=lead-timeline"><i class="fa fa-window-maximize" aria-hidden="true"></i> Timeline</a></li>';
=======
							echo '<li ><a href="/api/iframe?token='.$request->token.'&view=lead-negocio-perdido-ganho"><i class="fas fa-book-open" aria-hidden="true"></i> Acompanhamento</a></li>';
							echo '<li class="divider"></li>';
						}		
						if (verificaSubmenu('lead-conversao-busca', $perfil_usuario)) {
							echo '<li ><a href="/api/iframe?token='.$request->token.'&view=lead-conversao-busca"><i class="fas fa-check-double"></i></i> Conversões RD</a></li>';
							echo '<li class="divider"></li>';
						}
						if (verificaSubmenu('lead-tag-busca', $perfil_usuario)) {
							echo '<li ><a href="/api/iframe?token='.$request->token.'&view=lead-tag-busca"><i class="fas fa-tags"></i></i> Configurar Tags RD</a></li>';
							echo '<li class="divider"></li>';
						}				
						if (verificaSubmenu('lead-negocios-busca', $perfil_usuario)) {
							echo '<li ><a href="/api/iframe?token='.$request->token.'&view=lead-negocios-busca"><i class="fa fa-black-tie" aria-hidden="true"></i> Dashboard</a></li>';
							echo '<li class="divider"></li>';
						}
						if (verificaSubmenu('lead-negocio-form', $perfil_usuario)) {
							echo '<li ><a href="/api/iframe?token='.$request->token.'&view=lead-negocio-form"><i class="fa fa-plus" aria-hidden="true"></i> Novo negócio</a></li>';
							echo '<li class="divider"></li>';
						}	
						if (verificaSubmenu('lead-timeline', $perfil_usuario)) {
							echo '<li ><a href="/api/iframe?token='.$request->token.'&view=lead-timeline"><i class="fa fa-window-maximize" aria-hidden="true"></i> Timeline</a></li>';
>>>>>>> 5cfe18e1256c22b2f7585786435771d80b84680b
							echo '<li class="divider"></li>';
						}
						
						echo '</ul>';
						echo '</li>';
						echo '<li class="divider"></li>';
                    }

                    if (verificaSubmenu('mapa-clientes', $perfil_usuario)) {
<<<<<<< HEAD
                        echo '<li ><a href="/api/iframe?token=<?php echo $request->token ?>&view=mapa-clientes"><i class="fa fa-map-marker" aria-hidden="true"></i> Mapa de clientes</a></li>';
=======
                        echo '<li ><a href="/api/iframe?token='.$request->token.'&view=mapa-clientes"><i class="fa fa-map-marker" aria-hidden="true"></i> Mapa de clientes</a></li>';
>>>>>>> 5cfe18e1256c22b2f7585786435771d80b84680b
                        echo '<li class="divider"></li>';
                    }

					/* if (verificaSubmenu('modelo-contrato-busca', $perfil_usuario) || verificaSubmenu('modelo-contrato-gerar-busca', $perfil_usuario)) {
						echo '<li class="dropdown dropdown-submenu">';
						echo '<a href="" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-file-o" aria-hidden="true"></i> Modelos de contrato</a>';
						echo '<ul class="dropdown-menu">';
						echo '<li class="divider"></li>';
						
						if (verificaSubmenu('modelo-contrato-gerar-busca', $perfil_usuario)) {
<<<<<<< HEAD
							echo '<li ><a href="/api/iframe?token=<?php echo $request->token ?>&view=modelo-contrato-gerar-busca"><i class="fa fa-file-text" aria-hidden="true"></i> Gerar contrato</a></li>';
							echo '<li class="divider"></li>';
                        }
                        if (verificaSubmenu('modelo-contrato-busca', $perfil_usuario)) {
							echo '<li ><a href="/api/iframe?token=<?php echo $request->token ?>&view=modelo-contrato-busca"><i class="fa fa-cog" aria-hidden="true"></i> Gerenciar modelos</a></li>';
=======
							echo '<li ><a href="/api/iframe?token='.$request->token.'&view=modelo-contrato-gerar-busca"><i class="fa fa-file-text" aria-hidden="true"></i> Gerar contrato</a></li>';
							echo '<li class="divider"></li>';
                        }
                        if (verificaSubmenu('modelo-contrato-busca', $perfil_usuario)) {
							echo '<li ><a href="/api/iframe?token='.$request->token.'&view=modelo-contrato-busca"><i class="fa fa-cog" aria-hidden="true"></i> Gerenciar modelos</a></li>';
>>>>>>> 5cfe18e1256c22b2f7585786435771d80b84680b
							echo '<li class="divider"></li>';
						}						
						echo '</ul>';
						echo '</li>';
						echo '<li class="divider"></li>';						
					} */
					
					if (verificaSubmenu('plano-busca', $perfil_usuario)) {
<<<<<<< HEAD
						echo '<li ><a href="/api/iframe?token=<?php echo $request->token ?>&view=plano-busca"><i class="fa fa-cube" aria-hidden="true"></i> Planos</a></li>';
=======
						echo '<li ><a href="/api/iframe?token='.$request->token.'&view=plano-busca"><i class="fa fa-cube" aria-hidden="true"></i> Planos</a></li>';
>>>>>>> 5cfe18e1256c22b2f7585786435771d80b84680b
						echo '<li class="divider"></li>';
                    }

					if (verificaSubmenu('contrato-recarga-form', $perfil_usuario)) {

<<<<<<< HEAD
						echo '<li ><a href="/api/iframe?token=<?php echo $request->token ?>&view=contrato-recarga-busca"><i class="fa fa-credit-card-alt"></i> Recarga Contrato Pré-pago</a></li>';
=======
						echo '<li ><a href="/api/iframe?token='.$request->token.'&view=contrato-recarga-busca"><i class="fa fa-credit-card-alt"></i> Recarga Contrato Pré-pago</a></li>';
>>>>>>> 5cfe18e1256c22b2f7585786435771d80b84680b
						echo '<li class="divider"></li>';
					}

					if (verificaSubmenu('responsavel-contrato-busca', $perfil_usuario)) {

<<<<<<< HEAD
						echo '<li ><a href="/api/iframe?token=<?php echo $request->token ?>&view=responsavel-contrato-busca"><i class="fa fa-suitcase "></i> Responsáveis pelos Contratos</a></li>';
=======
						echo '<li ><a href="/api/iframe?token='.$request->token.'&view=responsavel-contrato-busca"><i class="fa fa-suitcase "></i> Responsáveis pelos Contratos</a></li>';
>>>>>>> 5cfe18e1256c22b2f7585786435771d80b84680b
						echo '<li class="divider"></li>';
					}

					if (verificaSubmenu('telao-marketing-busca', $perfil_usuario)) {
<<<<<<< HEAD
						echo '<li ><a href="/api/iframe?token=<?php echo $request->token ?>&view=telao-marketing-busca"><i class="fa fa-picture-o"></i> Telão Marketing</a></li>';
=======
						echo '<li ><a href="/api/iframe?token='.$request->token.'&view=telao-marketing-busca"><i class="fa fa-picture-o"></i> Telão Marketing</a></li>';
>>>>>>> 5cfe18e1256c22b2f7585786435771d80b84680b
						echo '<li class="divider"></li>';
					}
                  
					echo '
						</ul>
					</li>
					';
				}

				if (verificaMenu('financeiro', $perfil_usuario)) {
					echo '
						<li class="dropdown ' . $active['financeiro'] . '">
							<a href="" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-dollar"></i> Financeiro <strong class="caret"></strong></a>
							<ul class="dropdown-menu">
						';
					echo '<li class="divider"></li>';

                    if (verificaSubmenu('boleto-remessa-busca', $perfil_usuario)) {
<<<<<<< HEAD
                        echo '<li ><a href="/api/iframe?token=<?php echo $request->token ?>&view=boleto-busca"><i class="fa fa-barcode" aria-hidden="true"></i> Boletos</a></li>';
=======
                        echo '<li ><a href="/api/iframe?token='.$request->token.'&view=boleto-busca"><i class="fa fa-barcode" aria-hidden="true"></i> Boletos</a></li>';
>>>>>>> 5cfe18e1256c22b2f7585786435771d80b84680b
                        echo '<li class="divider"></li>';
                    } 

                    if (verificaSubmenu('caixa-busca', $perfil_usuario)) {
<<<<<<< HEAD
						echo '<li ><a href="/api/iframe?token=<?php echo $request->token ?>&view=caixa-busca"><i class="fas fa-piggy-bank"></i> Caixas</a></li>';
=======
						echo '<li ><a href="/api/iframe?token='.$request->token.'&view=caixa-busca"><i class="fas fa-piggy-bank"></i> Caixas</a></li>';
>>>>>>> 5cfe18e1256c22b2f7585786435771d80b84680b
						echo '<li class="divider"></li>';
                    }					

					if (verificaSubmenu('centro-custos-busca', $perfil_usuario) || verificaSubmenu('rateio-mensal-busca', $perfil_usuario)) {
						echo '<li class="dropdown dropdown-submenu">';
						echo '<a href="" class="dropdown-toggle" data-toggle="dropdown"><i class="fas fa-coins" aria-hidden="true"></i> Centros de Custos</a>';
						echo '<ul class="dropdown-menu">';
						echo '<li class="divider"></li>';

						if (verificaSubmenu('rateio-mensal-busca', $perfil_usuario)) {
<<<<<<< HEAD
							echo '<li ><a href="/api/iframe?token=<?php echo $request->token ?>&view=rateio-mensal-busca"><i class="fas fa-divide"></i> Rateio Mensal</a></li>';
							echo '<li class="divider"></li>';
						}	
						if (verificaSubmenu('centro-custos-busca', $perfil_usuario)) {
							echo '<li ><a href="/api/iframe?token=<?php echo $request->token ?>&view=centro-custos-busca"><i class="fa fa-cog" aria-hidden="true"></i> Gerenciar</a></li>';
=======
							echo '<li ><a href="/api/iframe?token='.$request->token.'&view=rateio-mensal-busca"><i class="fas fa-divide"></i> Rateio Mensal</a></li>';
							echo '<li class="divider"></li>';
						}	
						if (verificaSubmenu('centro-custos-busca', $perfil_usuario)) {
							echo '<li ><a href="/api/iframe?token='.$request->token.'&view=centro-custos-busca"><i class="fa fa-cog" aria-hidden="true"></i> Gerenciar</a></li>';
>>>>>>> 5cfe18e1256c22b2f7585786435771d80b84680b
							echo '<li class="divider"></li>';
                        }                        					
						echo '</ul>';
						echo '</li>';
						echo '<li class="divider"></li>';
					}
					
                    if (verificaSubmenu('controle-contas', $perfil_usuario)) {
<<<<<<< HEAD
						echo '<li ><a href="/api/iframe?token=<?php echo $request->token ?>&view=controle-contas"><i class="fas fa-hand-holding-usd"></i> Controle de Contas</a></li>';
=======
						echo '<li ><a href="/api/iframe?token='.$request->token.'&view=controle-contas"><i class="fas fa-hand-holding-usd"></i> Controle de Contas</a></li>';
>>>>>>> 5cfe18e1256c22b2f7585786435771d80b84680b
						echo '<li class="divider"></li>';
					}
					
					if (verificaSubmenu('estoque-item-busca', $perfil_usuario) || verificaSubmenu('estoque-movimentacao-entrada-busca', $perfil_usuario) || verificaSubmenu('estoque-movimentacao-saida-busca', $perfil_usuario) || verificaSubmenu('estoque-localizacao-busca', $perfil_usuario)) {
						echo '<li class="dropdown dropdown-submenu">';
						echo '<a href="" class="dropdown-toggle" data-toggle="dropdown"><i class="fas fa-archive" aria-hidden="true"></i> Estoque</a>';
						echo '<ul class="dropdown-menu">';
						echo '<li class="divider"></li>';
						
						if (verificaSubmenu('estoque-item-busca', $perfil_usuario)) {
<<<<<<< HEAD
							echo '<li ><a href="/api/iframe?token=<?php echo $request->token ?>&view=estoque-item-busca"><i class="fas fa-dolly"></i> Itens</a></li>';
=======
							echo '<li ><a href="/api/iframe?token='.$request->token.'&view=estoque-item-busca"><i class="fas fa-dolly"></i> Itens</a></li>';
>>>>>>> 5cfe18e1256c22b2f7585786435771d80b84680b
							echo '<li class="divider"></li>';
						}
						
						if (verificaSubmenu('estoque-localizacao-busca', $perfil_usuario)) {
<<<<<<< HEAD
							echo '<li ><a href="/api/iframe?token=<?php echo $request->token ?>&view=estoque-localizacao-busca"><i class="fas fa-map-marked-alt"></i> Localizações</a></li>';
=======
							echo '<li ><a href="/api/iframe?token='.$request->token.'&view=estoque-localizacao-busca"><i class="fas fa-map-marked-alt"></i> Localizações</a></li>';
>>>>>>> 5cfe18e1256c22b2f7585786435771d80b84680b
							echo '<li class="divider"></li>';
						}
						
                        if (verificaSubmenu('estoque-movimentacao-entrada-busca', $perfil_usuario)) {
<<<<<<< HEAD
							echo '<li ><a href="/api/iframe?token=<?php echo $request->token ?>&view=estoque-movimentacao-entrada-busca"><i class="fas fa-long-arrow-alt-left"></i> Movimentações (Entradas)</a></li>';
=======
							echo '<li ><a href="/api/iframe?token='.$request->token.'&view=estoque-movimentacao-entrada-busca"><i class="fas fa-long-arrow-alt-left"></i> Movimentações (Entradas)</a></li>';
>>>>>>> 5cfe18e1256c22b2f7585786435771d80b84680b
							echo '<li class="divider"></li>';
						}
						
						if (verificaSubmenu('estoque-movimentacao-saida-busca', $perfil_usuario)) {
<<<<<<< HEAD
							echo '<li ><a href="/api/iframe?token=<?php echo $request->token ?>&view=estoque-movimentacao-saida-busca"><i class="fas fa-long-arrow-alt-right"></i> Movimentações (Saídas)</a></li>';
=======
							echo '<li ><a href="/api/iframe?token='.$request->token.'&view=estoque-movimentacao-saida-busca"><i class="fas fa-long-arrow-alt-right"></i> Movimentações (Saídas)</a></li>';
>>>>>>> 5cfe18e1256c22b2f7585786435771d80b84680b
							echo '<li class="divider"></li>';
						}
						
						echo '</ul>';
						echo '</li>';
						echo '<li class="divider"></li>';						
                    }   

					

					if (verificaSubmenu('faturamento-gerar', $perfil_usuario) || verificaSubmenu('acrescimo-desconto-busca', $perfil_usuario)) {
						echo '<li class="dropdown dropdown-submenu">';
						echo '<a href="" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-money" aria-hidden="true"></i> Faturamento</a>';
						echo '<ul class="dropdown-menu">';
						echo '<li class="divider"></li>';
						
						if (verificaSubmenu('faturamento-gerar', $perfil_usuario)) {
<<<<<<< HEAD
							echo '<li ><a href="/api/iframe?token=<?php echo $request->token ?>&view=faturamento-gerar"><i class="fa fa-money"></i> Gerar Faturamento</a></li>';
							echo '<li class="divider"></li>';
	                    }
                        if (verificaSubmenu('acrescimo-desconto-busca', $perfil_usuario)) {
							echo '<li ><a href="/api/iframe?token=<?php echo $request->token ?>&view=acrescimo-desconto-busca"><i class="fa fa-cog"></i> Acréscimos e Descontos</a></li>';
=======
							echo '<li ><a href="/api/iframe?token='.$request->token.'&view=faturamento-gerar"><i class="fa fa-money"></i> Gerar Faturamento</a></li>';
							echo '<li class="divider"></li>';
	                    }
                        if (verificaSubmenu('acrescimo-desconto-busca', $perfil_usuario)) {
							echo '<li ><a href="/api/iframe?token='.$request->token.'&view=acrescimo-desconto-busca"><i class="fa fa-cog"></i> Acréscimos e Descontos</a></li>';
>>>>>>> 5cfe18e1256c22b2f7585786435771d80b84680b
							echo '<li class="divider"></li>';
	                    }					
						echo '</ul>';
						echo '</li>';
						echo '<li class="divider"></li>';						
                    }       

                    if (verificaSubmenu('natureza-financeira-busca', $perfil_usuario) || verificaSubmenu('natureza-financeira-agrupador-busca', $perfil_usuario)) {
						echo '<li class="dropdown dropdown-submenu">';
						echo '<a href="" class="dropdown-toggle" data-toggle="dropdown"><i class="fas fa-donate" aria-hidden="true"></i> Naturezas Financeiras</a>';
						echo '<ul class="dropdown-menu">';
						echo '<li class="divider"></li>';
						
						if (verificaSubmenu('natureza-financeira-agrupador-busca', $perfil_usuario)) {
<<<<<<< HEAD
							echo '<li ><a href="/api/iframe?token=<?php echo $request->token ?>&view=natureza-financeira-agrupador-busca"><i class="fas fa-layer-group"></i> Agrupadores</a></li>';
							echo '<li class="divider"></li>';
	                    }
                        if (verificaSubmenu('natureza-financeira-busca', $perfil_usuario)) {
							echo '<li ><a href="/api/iframe?token=<?php echo $request->token ?>&view=natureza-financeira-busca"><i class="fa fa-cog"></i> Gerenciar</a></li>';
=======
							echo '<li ><a href="/api/iframe?token='.$request->token.'&view=natureza-financeira-agrupador-busca"><i class="fas fa-layer-group"></i> Agrupadores</a></li>';
							echo '<li class="divider"></li>';
	                    }
                        if (verificaSubmenu('natureza-financeira-busca', $perfil_usuario)) {
							echo '<li ><a href="/api/iframe?token='.$request->token.'&view=natureza-financeira-busca"><i class="fa fa-cog"></i> Gerenciar</a></li>';
>>>>>>> 5cfe18e1256c22b2f7585786435771d80b84680b
							echo '<li class="divider"></li>';
	                    }					
						echo '</ul>';
						echo '</li>';
						echo '<li class="divider"></li>';						
                    }       
                    if (verificaSubmenu('nfs-busca', $perfil_usuario)) {
<<<<<<< HEAD
						echo '<li ><a href="/api/iframe?token=<?php echo $request->token ?>&view=nfs-busca"><i class="fas fa-file-invoice-dollar"></i> NFS-e</a></li>';
=======
						echo '<li ><a href="/api/iframe?token='.$request->token.'&view=nfs-busca"><i class="fas fa-file-invoice-dollar"></i> NFS-e</a></li>';
>>>>>>> 5cfe18e1256c22b2f7585786435771d80b84680b
						echo '<li class="divider"></li>';
					}     
					
					if (verificaSubmenu('patrimonio-item-busca', $perfil_usuario) || verificaSubmenu('patrimonio-localizacao-busca', $perfil_usuario) || verificaSubmenu('patrimonio-busca', $perfil_usuario)) {
						echo '<li class="dropdown dropdown-submenu">';
						echo '<a href="" class="dropdown-toggle" data-toggle="dropdown"><i class="fas fa-city"></i> Patrimônio</a>';
						echo '<ul class="dropdown-menu">';
						echo '<li class="divider"></li>';
						
						if (verificaSubmenu('patrimonio-busca', $perfil_usuario)) {
<<<<<<< HEAD
							echo '<li ><a href="/api/iframe?token=<?php echo $request->token ?>&view=patrimonio-busca"><i class="fa fa-cog"></i> Gerenciar</a></li>';
=======
							echo '<li ><a href="/api/iframe?token='.$request->token.'&view=patrimonio-busca"><i class="fa fa-cog"></i> Gerenciar</a></li>';
>>>>>>> 5cfe18e1256c22b2f7585786435771d80b84680b
							echo '<li class="divider"></li>';
						}
						
						if (verificaSubmenu('patrimonio-item-busca', $perfil_usuario)) {
<<<<<<< HEAD
							echo '<li ><a href="/api/iframe?token=<?php echo $request->token ?>&view=patrimonio-item-busca"><i class="fas fa-dolly"></i> Itens</a></li>';
=======
							echo '<li ><a href="/api/iframe?token='.$request->token.'&view=patrimonio-item-busca"><i class="fas fa-dolly"></i> Itens</a></li>';
>>>>>>> 5cfe18e1256c22b2f7585786435771d80b84680b
							echo '<li class="divider"></li>';
						}
						
						if (verificaSubmenu('patrimonio-localizacao-busca', $perfil_usuario)) {
<<<<<<< HEAD
							echo '<li ><a href="/api/iframe?token=<?php echo $request->token ?>&view=patrimonio-localizacao-busca"><i class="fas fa-map-marked-alt"></i> Localizações</a></li>';
=======
							echo '<li ><a href="/api/iframe?token='.$request->token.'&view=patrimonio-localizacao-busca"><i class="fas fa-map-marked-alt"></i> Localizações</a></li>';
>>>>>>> 5cfe18e1256c22b2f7585786435771d80b84680b
							echo '<li class="divider"></li>';
						}
						
						echo '</ul>';
						echo '</li>';
						echo '<li class="divider"></li>';						
					}
					
					echo '
						</ul>
					</li>
					';
                }	

                /* if (verificaMenu('redes', $perfil_usuario)){
					echo '
					<li class="dropdown ' . $active['redes'] . '">
						<a href="" class="dropdown-toggle" data-toggle="dropdown"><i class="fas fa-network-wired"></i> Redes <strong class="caret"></strong></a>
						<ul class="dropdown-menu">
					';
                    echo '<li class="divider"></li>';
                    if (verificaSubmenu('ativacao-redes-busca', $perfil_usuario)) {
<<<<<<< HEAD
                        echo '<li ><a href="/api/iframe?token=<?php echo $request->token ?>&view=ativacao-redes-busca"><i class="far fa-handshake"></i> Ativações</a></li>';
                        echo '<li class="divider"></li>';
                    }	
                    if (verificaSubmenu('converte-csv-xml-zabbix-form', $perfil_usuario)) {
                        echo '<li ><a href="/api/iframe?token=<?php echo $request->token ?>&view=converte-csv-xml-zabbix-form"><i class="fa fa-refresh"></i> Conversor Zabbix</a></li>';
                        echo '<li class="divider"></li>';
                    }	
                    if (verificaSubmenu('ferramenta-busca', $perfil_usuario)) {
                        echo '<li ><a href="/api/iframe?token=<?php echo $request->token ?>&view=ferramenta-busca"><i class="fas fa-tools"></i> Ferramentas</a></li>';
                        echo '<li class="divider"></li>';
                    }
					if (verificaSubmenu('parametro-redes-busca', $perfil_usuario)) {
                        echo '<li ><a href="/api/iframe?token=<?php echo $request->token ?>&view=parametro-redes-busca"><i class="fa fa-sliders" aria-hidden="true"></i> Parâmetros</a></li>';
=======
                        echo '<li ><a href="/api/iframe?token='.$request->token.'&view=ativacao-redes-busca"><i class="far fa-handshake"></i> Ativações</a></li>';
                        echo '<li class="divider"></li>';
                    }	
                    if (verificaSubmenu('converte-csv-xml-zabbix-form', $perfil_usuario)) {
                        echo '<li ><a href="/api/iframe?token='.$request->token.'&view=converte-csv-xml-zabbix-form"><i class="fa fa-refresh"></i> Conversor Zabbix</a></li>';
                        echo '<li class="divider"></li>';
                    }	
                    if (verificaSubmenu('ferramenta-busca', $perfil_usuario)) {
                        echo '<li ><a href="/api/iframe?token='.$request->token.'&view=ferramenta-busca"><i class="fas fa-tools"></i> Ferramentas</a></li>';
                        echo '<li class="divider"></li>';
                    }
					if (verificaSubmenu('parametro-redes-busca', $perfil_usuario)) {
                        echo '<li ><a href="/api/iframe?token='.$request->token.'&view=parametro-redes-busca"><i class="fa fa-sliders" aria-hidden="true"></i> Parâmetros</a></li>';
>>>>>>> 5cfe18e1256c22b2f7585786435771d80b84680b
                        echo '<li class="divider"></li>';
                    }
					if (verificaSubmenu('plantao-comissoes', $perfil_usuario) || verificaSubmenu('plantao-escala-busca', $perfil_usuario)) {
						echo '<li class="dropdown dropdown-submenu">';
						echo '<a href="" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user-clock" aria-hidden="true"></i> Plantões</a>';
						echo '<ul class="dropdown-menu">';
						echo '<li class="divider"></li>';
						if (verificaSubmenu('plantao-comissoes', $perfil_usuario)) {
<<<<<<< HEAD
							echo '<li ><a href="/api/iframe?token=<?php echo $request->token ?>&view=plantao-comissoes"><i class="fas fa-money-check-alt"></i> Comissões</a></li>';
							echo '<li class="divider"></li>';
						}
						if (verificaSubmenu('plantao-escala-busca', $perfil_usuario)) {
							echo '<li ><a href="/api/iframe?token=<?php echo $request->token ?>&view=plantao-escala-busca"><i class="fa fa-braille"></i> Escalas</a></li>';
=======
							echo '<li ><a href="/api/iframe?token='.$request->token.'&view=plantao-comissoes"><i class="fas fa-money-check-alt"></i> Comissões</a></li>';
							echo '<li class="divider"></li>';
						}
						if (verificaSubmenu('plantao-escala-busca', $perfil_usuario)) {
							echo '<li ><a href="/api/iframe?token='.$request->token.'&view=plantao-escala-busca"><i class="fa fa-braille"></i> Escalas</a></li>';
>>>>>>> 5cfe18e1256c22b2f7585786435771d80b84680b
							echo '<li class="divider"></li>';
						}
						echo '</ul>';
						echo '</li>';
						echo '<li class="divider"></li>';
					}
					echo '
						</ul>
					</li>
					';
				} */
                
                if (verificaMenu('rh', $perfil_usuario)){
					echo '
					<li class="dropdown ' . $active['rh'] . '">
						<a href="" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-users"></i> RH <strong class="caret"></strong></a>
						<ul class="dropdown-menu">
					';
					echo '<li class="divider"></li>';
					if (verificaSubmenu('funcionario-busca', $perfil_usuario)) {
<<<<<<< HEAD
						echo '<li ><a href="/api/iframe?token=<?php echo $request->token ?>&view=funcionario-busca"><i class="fas fa-address-card"></i> Funcionários</a></li>';
						echo '<li class="divider"></li>';
					}
					if (verificaSubmenu('ocorrencia-busca', $perfil_usuario)) {
						echo '<li ><a href="/api/iframe?token=<?php echo $request->token ?>&view=ocorrencia-busca"><i class="fa fa-exclamation-circle" aria-hidden="true"></i> Ocorrências</a></li>';
						echo '<li class="divider"></li>';
					}
					if (verificaSubmenu('selecao-busca', $perfil_usuario)) {
						echo '<li ><a href="/api/iframe?token=<?php echo $request->token ?>&view=selecao-busca"><i class="fas fa-chalkboard-teacher" aria-hidden="true"></i> Seleção</a></li>';
						echo '<li class="divider"></li>';
					}
					if (verificaSubmenu('treinamento-busca', $perfil_usuario)) {
						echo '<li ><a href="/api/iframe?token=<?php echo $request->token ?>&view=treinamento-busca"><i class="fas fa-graduation-cap" aria-hidden="true"></i> Treinamentos</a></li>';
						echo '<li class="divider"></li>';
					}
					if (verificaSubmenu('vaga-busca', $perfil_usuario)) {
						echo '<li ><a href="/api/iframe?token=<?php echo $request->token ?>&view=vaga-busca"><i class="fas fa-door-open" aria-hidden="true"></i> Vagas</a></li>';
=======
						echo '<li ><a href="/api/iframe?token='.$request->token.'&view=funcionario-busca"><i class="fas fa-address-card"></i> Funcionários</a></li>';
						echo '<li class="divider"></li>';
					}
					if (verificaSubmenu('ocorrencia-busca', $perfil_usuario)) {
						echo '<li ><a href="/api/iframe?token='.$request->token.'&view=ocorrencia-busca"><i class="fa fa-exclamation-circle" aria-hidden="true"></i> Ocorrências</a></li>';
						echo '<li class="divider"></li>';
					}
					if (verificaSubmenu('selecao-busca', $perfil_usuario)) {
						echo '<li ><a href="/api/iframe?token='.$request->token.'&view=selecao-busca"><i class="fas fa-chalkboard-teacher" aria-hidden="true"></i> Seleção</a></li>';
						echo '<li class="divider"></li>';
					}
					if (verificaSubmenu('treinamento-busca', $perfil_usuario)) {
						echo '<li ><a href="/api/iframe?token='.$request->token.'&view=treinamento-busca"><i class="fas fa-graduation-cap" aria-hidden="true"></i> Treinamentos</a></li>';
						echo '<li class="divider"></li>';
					}
					if (verificaSubmenu('vaga-busca', $perfil_usuario)) {
						echo '<li ><a href="/api/iframe?token='.$request->token.'&view=vaga-busca"><i class="fas fa-door-open" aria-hidden="true"></i> Vagas</a></li>';
>>>>>>> 5cfe18e1256c22b2f7585786435771d80b84680b
						echo '<li class="divider"></li>';
					}
					echo '
						</ul>
					</li>
					';
				}


			// Sub menu TI
				// if (verificaMenu('ti', $perfil_usuario)){
				// 	echo '
				// 	<li class="dropdown ' . $active['ti'] . '">       
				// 		<a href="" class="dropdown-toggle" data-toggle="dropdown"><i class="fas fa-screwdriver"></i> Infra TI <strong class="caret"></strong></a>
				// 		<ul class="dropdown-menu">
				// 	';
				// 	echo '<li class="divider"></li>';


				// Inventario de software
				// 	if (verificaSubmenu('inventario-software-busca', $perfil_usuario) || verificaSubmenu('inventario-software-form', $perfil_usuario)) {
				// 		echo '<li class="dropdown dropdown-submenu">';
				// 		echo '<a href="" class="dropdown-toggle" data-toggle="dropdown"><i class="fas fa-archive"></i> Inventario </a>';
				// 		echo '<ul class="dropdown-menu">';
				// 		echo '<li class="divider"></li>';
				// 		if (verificaSubmenu('inventario-software-form', $perfil_usuario)) {
<<<<<<< HEAD
				// 			echo '<li ><a href="/api/iframe?token=<?php echo $request->token ?>&view=inventario-software-form"><i class="fa fa-cog" aria-hidden="true"></i> Gerenciar</a></li>';
				// 			echo '<li class="divider"></li>';
				// 		}
				// 		if (verificaSubmenu('inventario-software-busca', $perfil_usuario)) {
				// 			echo '<li ><a href="/api/iframe?token=<?php echo $request->token ?>&view=inventario-software-busca"><i class="fab fa-creative-commons-sampling"></i> Software</a></li>';
=======
				// 			echo '<li ><a href="/api/iframe?token='.$request->token.'&view=inventario-software-form"><i class="fa fa-cog" aria-hidden="true"></i> Gerenciar</a></li>';
				// 			echo '<li class="divider"></li>';
				// 		}
				// 		if (verificaSubmenu('inventario-software-busca', $perfil_usuario)) {
				// 			echo '<li ><a href="/api/iframe?token='.$request->token.'&view=inventario-software-busca"><i class="fab fa-creative-commons-sampling"></i> Software</a></li>';
>>>>>>> 5cfe18e1256c22b2f7585786435771d80b84680b
				// 			echo '<li class="divider"></li>';
				// 		}						
				// 		echo '</ul>';
				// 		echo '</li>';
				// 		echo '<li class="divider"></li>';
				// 	}

				// // Troca de Link
				
				
				// 	// OBS: Lembrar de didiconar as Views nas ###
				// 	if (verificaSubmenu('troca-link', $perfil_usuario) || verificaSubmenu('troca-link-trafego', $perfil_usuario) || verificaSubmenu('###', $perfil_usuario)) {

				// 		echo '<li class="dropdown dropdown-submenu">';
				// 		echo '<a href="" class="dropdown-toggle" data-toggle="dropdown"><i class="fas fa-network-wired"></i> Troca de link </a>';
				// 		echo '<ul class="dropdown-menu">';
				// 		echo '<li class="divider"></li>';
				// 		if (verificaSubmenu('troca-link', $perfil_usuario)) {
<<<<<<< HEAD
				// 			echo '<li ><a href="/api/iframe?token=<?php echo $request->token ?>&view=troca-link"><i class="fas fa-link"></i> Links de Acesso</a></li>';
				// 			echo '<li class="divider"></li>';
				// 		}
				// 		if (verificaSubmenu('troca-link-trafego', $perfil_usuario)) {
				// 			echo '<li ><a href="/api/iframe?token=<?php echo $request->token ?>&view=troca-link-trafego"><i class="fas fa-server"></i> Trafego de dados</a></li>';
=======
				// 			echo '<li ><a href="/api/iframe?token='.$request->token.'&view=troca-link"><i class="fas fa-link"></i> Links de Acesso</a></li>';
				// 			echo '<li class="divider"></li>';
				// 		}
				// 		if (verificaSubmenu('troca-link-trafego', $perfil_usuario)) {
				// 			echo '<li ><a href="/api/iframe?token='.$request->token.'&view=troca-link-trafego"><i class="fas fa-server"></i> Trafego de dados</a></li>';
>>>>>>> 5cfe18e1256c22b2f7585786435771d80b84680b
				// 			echo '<li class="divider"></li>';
				// 		}
						
				// 		echo '</ul>';
				// 		echo '</li>';
				// 		echo '<li class="divider"></li>';
				// 	}

					
				// 	echo '</ul>';
				// 	echo '</li>';
				// 	echo '<li class="divider"></li>';
				// }
                
                if (verificaMenu('chamados', $perfil_usuario)) {
					echo '
					<li class="dropdown ' . $active['chamados'] . '">
						<a href="" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-clipboard"></i> Chamados <strong class="caret"></strong></a>
						<ul class="dropdown-menu">
					';

					if (verificaSubmenu('chamado-busca', $perfil_usuario)) {
						echo '<li class="divider"></li>';
<<<<<<< HEAD
						echo '<li><a href="/api/iframe?token=<?php echo $request->token ?>&view=chamado-busca"><i class="fa fa-bullhorn" aria-hidden="true"></i> Chamados</a></li>';
=======
						echo '<li><a href="/api/iframe?token='.$request->token.'&view=chamado-busca"><i class="fa fa-bullhorn" aria-hidden="true"></i> Chamados</a></li>';
>>>>>>> 5cfe18e1256c22b2f7585786435771d80b84680b
						echo '<li class="divider"></li>';
					}

					if (verificaSubmenu('chamado-script-busca', $perfil_usuario)) {
<<<<<<< HEAD
						echo '<li><a href="/api/iframe?token=<?php echo $request->token ?>&view=chamado-script-busca"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Scripts</a></li>';
=======
						echo '<li><a href="/api/iframe?token='.$request->token.'&view=chamado-script-busca"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Scripts</a></li>';
>>>>>>> 5cfe18e1256c22b2f7585786435771d80b84680b
						echo '<li class="divider"></li>';
					}

					echo '
						</ul>
					</li>
					';
				}

                if (verificaMenu('topicos', $perfil_usuario)) {
<<<<<<< HEAD
					echo '<li  class="'. $active['topicos'] . '"><a href="/api/iframe?token=<?php echo $request->token ?>&view=topico-busca"><i class="fa fa-comment-o" aria-hidden="true"></i> Tópicos</a></li>';
=======
					echo '<li  class="'. $active['topicos'] . '"><a href="/api/iframe?token='.$request->token.'&view=topico-busca"><i class="fa fa-comment-o" aria-hidden="true"></i> Tópicos</a></li>';
>>>>>>> 5cfe18e1256c22b2f7585786435771d80b84680b
                }   

				if (verificaMenu('relatorios', $perfil_usuario)) {
					echo '
					<li class="dropdown ' . $active['relatorios'] . '">
						<a href="" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-clipboard"></i> Relatórios <strong class="caret"></strong></a>
						<ul class="dropdown-menu">
					';
					echo '<li class="divider"></li>';
					
					if (verificaSubmenu('relatorio-erro-reclamacao', $perfil_usuario) || verificaSubmenu('relatorio-pessoa', $perfil_usuario)) {
						echo '<li class="dropdown dropdown-submenu">';
						echo '<a href="" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-folder-open" aria-hidden="true"></i> Cadastros</a>';
						echo '<ul class="dropdown-menu">';
						echo '<li class="divider"></li>';
						if (verificaSubmenu('relatorio-erro-reclamacao', $perfil_usuario)) {
<<<<<<< HEAD
							echo '<li ><a href="/api/iframe?token=<?php echo $request->token ?>&view=relatorio-erro-reclamacao"><i class="fa fa-bug" aria-hidden="true"></i> Reclamações/Erros</a></li>';
							echo '<li class="divider"></li>';
						}
						if (verificaSubmenu('relatorio-pessoa', $perfil_usuario)) {
							echo '<li ><a href="/api/iframe?token=<?php echo $request->token ?>&view=relatorio-pessoa"><i class="fa fa-address-card-o"></i> Pessoas</a></li>';
=======
							echo '<li ><a href="/api/iframe?token='.$request->token.'&view=relatorio-erro-reclamacao"><i class="fa fa-bug" aria-hidden="true"></i> Reclamações/Erros</a></li>';
							echo '<li class="divider"></li>';
						}
						if (verificaSubmenu('relatorio-pessoa', $perfil_usuario)) {
							echo '<li ><a href="/api/iframe?token='.$request->token.'&view=relatorio-pessoa"><i class="fa fa-address-card-o"></i> Pessoas</a></li>';
>>>>>>> 5cfe18e1256c22b2f7585786435771d80b84680b
							echo '<li class="divider"></li>';
						}
						echo '</ul>';
						echo '</li>';
						echo '<li class="divider"></li>';
					}

					if (verificaSubmenu('relatorio-atendimento', $perfil_usuario) || verificaSubmenu('relatorio-central-telefonica', $perfil_usuario) || verificaSubmenu('relatorio-equipe', $perfil_usuario) || verificaSubmenu('relatorio-escala', $perfil_usuario) || verificaSubmenu('relatorio-indicadores-callcenter', $perfil_usuario) || verificaSubmenu('relatorio-metas', $perfil_usuario) || verificaSubmenu('relatorio-monitoramento', $perfil_usuario) || verificaSubmenu('relatorio-pesquisa', $perfil_usuario) || verificaSubmenu('relatorio-ajuda', $perfil_usuario) || verificaSubmenu('relatorio-alerta', $perfil_usuario) || verificaSubmenu('relatorio-callcenter-contagens', $perfil_usuario) || verificaSubmenu('relatorio-quadro-informativo', $perfil_usuario) || verificaSubmenu('relatorio-responsavel-atendimento', $perfil_usuario)) {
						echo '<li class="dropdown dropdown-submenu">';
						echo '<a href="" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-phone" aria-hidden="true"></i> Call Center</a>';
						echo '<ul class="dropdown-menu">';
						echo '<li class="divider"></li>';

						if (verificaSubmenu('relatorio-alerta', $perfil_usuario)) {
<<<<<<< HEAD
							echo '<li ><a href="/api/iframe?token=<?php echo $request->token ?>&view=relatorio-alerta"><i class="fa fa-bell" aria-hidden="true"></i> Alertas</a></li>';
=======
							echo '<li ><a href="/api/iframe?token='.$request->token.'&view=relatorio-alerta"><i class="fa fa-bell" aria-hidden="true"></i> Alertas</a></li>';
>>>>>>> 5cfe18e1256c22b2f7585786435771d80b84680b
							echo '<li class="divider"></li>';
						}

						if (verificaSubmenu('relatorio-atendimento', $perfil_usuario)) {
<<<<<<< HEAD
							echo '<li ><a href="/api/iframe?token=<?php echo $request->token ?>&view=relatorio-atendimento"><i class="fas fa-headset"></i> Atendimentos</a></li>';
=======
							echo '<li ><a href="/api/iframe?token='.$request->token.'&view=relatorio-atendimento"><i class="fas fa-headset"></i> Atendimentos</a></li>';
>>>>>>> 5cfe18e1256c22b2f7585786435771d80b84680b
							echo '<li class="divider"></li>';
						}

						if (verificaSubmenu('relatorio-central-telefonica', $perfil_usuario)) {
<<<<<<< HEAD
							echo '<li ><a href="/api/iframe?token=<?php echo $request->token ?>&view=relatorio-central-telefonica"><i class="fa fa-tty" aria-hidden="true"></i> Central Telefônica</a></li>';
=======
							echo '<li ><a href="/api/iframe?token='.$request->token.'&view=relatorio-central-telefonica"><i class="fa fa-tty" aria-hidden="true"></i> Central Telefônica</a></li>';
>>>>>>> 5cfe18e1256c22b2f7585786435771d80b84680b
							echo '<li class="divider"></li>';
						}

						if (verificaSubmenu('relatorio-callcenter-contagens', $perfil_usuario)) {
<<<<<<< HEAD
							echo '<li ><a href="/api/iframe?token=<?php echo $request->token ?>&view=relatorio-callcenter-contagens"><i class="fa fa-list-ol" aria-hidden="true"></i> Contagens</a></li>';
=======
							echo '<li ><a href="/api/iframe?token='.$request->token.'&view=relatorio-callcenter-contagens"><i class="fa fa-list-ol" aria-hidden="true"></i> Contagens</a></li>';
>>>>>>> 5cfe18e1256c22b2f7585786435771d80b84680b
							echo '<li class="divider"></li>';
						}

						if (verificaSubmenu('relatorio-equipe', $perfil_usuario)) {
<<<<<<< HEAD
							echo '<li ><a href="/api/iframe?token=<?php echo $request->token ?>&view=relatorio-equipe"><i class="fa fa-cubes" aria-hidden="true"></i> Equipes</a></li>';
=======
							echo '<li ><a href="/api/iframe?token='.$request->token.'&view=relatorio-equipe"><i class="fa fa-cubes" aria-hidden="true"></i> Equipes</a></li>';
>>>>>>> 5cfe18e1256c22b2f7585786435771d80b84680b
							echo '<li class="divider"></li>';
						}

						if (verificaSubmenu('relatorio-escala', $perfil_usuario)) {
<<<<<<< HEAD
							echo '<li ><a href="/api/iframe?token=<?php echo $request->token ?>&view=relatorio-escala"><i class="fa fa-braille" aria-hidden="true"></i> Escalas</a></li>';
=======
							echo '<li ><a href="/api/iframe?token='.$request->token.'&view=relatorio-escala"><i class="fa fa-braille" aria-hidden="true"></i> Escalas</a></li>';
>>>>>>> 5cfe18e1256c22b2f7585786435771d80b84680b
							echo '<li class="divider"></li>';
						}

						if (verificaSubmenu('relatorio-grupo-chat', $perfil_usuario)) {
<<<<<<< HEAD
							echo '<li ><a href="/api/iframe?token=<?php echo $request->token ?>&view=relatorio-grupo-chat"><i class="fa fa-layer-group" aria-hidden="true"></i> Grupos de Atendimento por Chat</a></li>';
=======
							echo '<li ><a href="/api/iframe?token='.$request->token.'&view=relatorio-grupo-chat"><i class="fa fa-layer-group" aria-hidden="true"></i> Grupos de Atendimento por Chat</a></li>';
>>>>>>> 5cfe18e1256c22b2f7585786435771d80b84680b
							echo '<li class="divider"></li>';
						}

						if (verificaSubmenu('relatorio-indicadores-callcenter', $perfil_usuario)) {
<<<<<<< HEAD
							echo '<li ><a href="/api/iframe?token=<?php echo $request->token ?>&view=relatorio-indicadores-callcenter"><i class="fa fa-bar-chart" aria-hidden="true"></i> Indicadores</a></li>';
=======
							echo '<li ><a href="/api/iframe?token='.$request->token.'&view=relatorio-indicadores-callcenter"><i class="fa fa-bar-chart" aria-hidden="true"></i> Indicadores</a></li>';
>>>>>>> 5cfe18e1256c22b2f7585786435771d80b84680b
							echo '<li class="divider"></li>';
						}

						if (verificaSubmenu('relatorio-metas', $perfil_usuario)) {
<<<<<<< HEAD
							echo '<li ><a href="/api/iframe?token=<?php echo $request->token ?>&view=relatorio-metas"><i class="fa fa-line-chart" aria-hidden="true"></i> Metas</a></li>';
=======
							echo '<li ><a href="/api/iframe?token='.$request->token.'&view=relatorio-metas"><i class="fa fa-line-chart" aria-hidden="true"></i> Metas</a></li>';
>>>>>>> 5cfe18e1256c22b2f7585786435771d80b84680b
							echo '<li class="divider"></li>';
						}

						if (verificaSubmenu('relatorio-monitoramento', $perfil_usuario)){
<<<<<<< HEAD
							echo '<li ><a href="/api/iframe?token=<?php echo $request->token ?>&view=relatorio-monitoramento"><i class="fa fa-podcast" aria-hidden="true"></i> Monitoramento</a></li>';
=======
							echo '<li ><a href="/api/iframe?token='.$request->token.'&view=relatorio-monitoramento"><i class="fa fa-podcast" aria-hidden="true"></i> Monitoramento</a></li>';
>>>>>>> 5cfe18e1256c22b2f7585786435771d80b84680b
							echo '<li class="divider"></li>';
						}

						if (verificaSubmenu('relatorio-monitoria', $perfil_usuario)){
<<<<<<< HEAD
							echo '<li ><a href="/api/iframe?token=<?php echo $request->token ?>&view=relatorio-monitoria"><i class="fa fa-tripadvisor" aria-hidden="true"></i> Monitoria</a></li>';
=======
							echo '<li ><a href="/api/iframe?token='.$request->token.'&view=relatorio-monitoria"><i class="fa fa-tripadvisor" aria-hidden="true"></i> Monitoria</a></li>';
>>>>>>> 5cfe18e1256c22b2f7585786435771d80b84680b
							echo '<li class="divider"></li>';
						}

						if (verificaSubmenu('relatorio-pesquisa', $perfil_usuario)) {
<<<<<<< HEAD
							echo '<li ><a href="/api/iframe?token=<?php echo $request->token ?>&view=relatorio-pesquisa"><i class="fa fa-binoculars" aria-hidden="true"></i> Pesquisa</a></li>';
=======
							echo '<li ><a href="/api/iframe?token='.$request->token.'&view=relatorio-pesquisa"><i class="fa fa-binoculars" aria-hidden="true"></i> Pesquisa</a></li>';
>>>>>>> 5cfe18e1256c22b2f7585786435771d80b84680b
							echo '<li class="divider"></li>';
						}

						if (verificaSubmenu('relatorio-quadro-informativo', $perfil_usuario)) {
<<<<<<< HEAD
							echo '<li ><a href="/api/iframe?token=<?php echo $request->token ?>&view=relatorio-quadro-informativo"><i class="fa fa-clock-o " aria-hidden="true"></i> Quadro Informativo</a></li>';
=======
							echo '<li ><a href="/api/iframe?token='.$request->token.'&view=relatorio-quadro-informativo"><i class="fa fa-clock-o " aria-hidden="true"></i> Quadro Informativo</a></li>';
>>>>>>> 5cfe18e1256c22b2f7585786435771d80b84680b
							echo '<li class="divider"></li>';
						}

						if (verificaSubmenu('relatorio-responsavel-atendimento', $perfil_usuario)) {
<<<<<<< HEAD
							echo '<li ><a href="/api/iframe?token=<?php echo $request->token ?>&view=relatorio-responsavel-atendimento"><i class="fa fa-hands-helping" aria-hidden="true"></i> Responsáveis pelos Atendimentos</a></li>';
=======
							echo '<li ><a href="/api/iframe?token='.$request->token.'&view=relatorio-responsavel-atendimento"><i class="fa fa-hands-helping" aria-hidden="true"></i> Responsáveis pelos Atendimentos</a></li>';
>>>>>>> 5cfe18e1256c22b2f7585786435771d80b84680b
							echo '<li class="divider"></li>';
						}

						if (verificaSubmenu('relatorio-ajuda', $perfil_usuario)) {
<<<<<<< HEAD
							echo '<li ><a href="/api/iframe?token=<?php echo $request->token ?>&view=relatorio-ajuda"><i class="fa fa-exclamation-triangle " aria-hidden="true"></i> Solicitações de Ajuda</a></li>';
=======
							echo '<li ><a href="/api/iframe?token='.$request->token.'&view=relatorio-ajuda"><i class="fa fa-exclamation-triangle " aria-hidden="true"></i> Solicitações de Ajuda</a></li>';
>>>>>>> 5cfe18e1256c22b2f7585786435771d80b84680b
							echo '<li class="divider"></li>';
						}

						echo '</ul>';
						echo '</li>';
						echo '<li class="divider"></li>';
					}	

					if (verificaSubmenu('relatorio-chamado', $perfil_usuario)) {
<<<<<<< HEAD
						echo '<li ><a href="/api/iframe?token=<?php echo $request->token ?>&view=relatorio-chamado"><i class="fa fa-bullhorn" aria-hidden="true"></i> Chamados</a></li>';
=======
						echo '<li ><a href="/api/iframe?token='.$request->token.'&view=relatorio-chamado"><i class="fa fa-bullhorn" aria-hidden="true"></i> Chamados</a></li>';
>>>>>>> 5cfe18e1256c22b2f7585786435771d80b84680b
						echo '<li class="divider"></li>';
					}

					if (verificaSubmenu('relatorio-contratos', $perfil_usuario) || verificaSubmenu('relatorio-leads', $perfil_usuario) || verificaSubmenu('relatorio-clientes', $perfil_usuario) || verificaSubmenu('relatorio-indicacoes', $perfil_usuario)) {
						echo '<li class="dropdown dropdown-submenu">';
						echo '<a href="" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-handshake-o" aria-hidden="true"></i> Comercial</a>';
						echo '<ul class="dropdown-menu">';
						echo '<li class="divider"></li>';
						if (verificaSubmenu('relatorio-contratos', $perfil_usuario)) {
<<<<<<< HEAD
							echo '<li ><a href="/api/iframe?token=<?php echo $request->token ?>&view=relatorio-contratos"><i class="fa fa-file-text-o" aria-hidden="true"></i> Contratos</a></li>';
							echo '<li class="divider"></li>';
						}
						if (verificaSubmenu('relatorio-clientes', $perfil_usuario)) {
							echo '<li ><a href="/api/iframe?token=<?php echo $request->token ?>&view=relatorio-clientes"><i class="fa fa-bar-chart" aria-hidden="true"></i> Indicadores</a></li>';
							echo '<li class="divider"></li>';
						}
						if (verificaSubmenu('relatorio-indicacoes', $perfil_usuario)) {
							echo '<li ><a href="/api/iframe?token=<?php echo $request->token ?>&view=relatorio-indicacoes"><i class="fas fa-comment-dollar"></i> Indicações</a></li>';
							echo '<li class="divider"></li>';
						}
						if (verificaSubmenu('relatorio-leads', $perfil_usuario)) {
							echo '<li ><a href="/api/iframe?token=<?php echo $request->token ?>&view=relatorio-leads"><i class="fa fa-address-card" aria-hidden="true"></i> Leads</a></li>';
=======
							echo '<li ><a href="/api/iframe?token='.$request->token.'&view=relatorio-contratos"><i class="fa fa-file-text-o" aria-hidden="true"></i> Contratos</a></li>';
							echo '<li class="divider"></li>';
						}
						if (verificaSubmenu('relatorio-clientes', $perfil_usuario)) {
							echo '<li ><a href="/api/iframe?token='.$request->token.'&view=relatorio-clientes"><i class="fa fa-bar-chart" aria-hidden="true"></i> Indicadores</a></li>';
							echo '<li class="divider"></li>';
						}
						if (verificaSubmenu('relatorio-indicacoes', $perfil_usuario)) {
							echo '<li ><a href="/api/iframe?token='.$request->token.'&view=relatorio-indicacoes"><i class="fas fa-comment-dollar"></i> Indicações</a></li>';
							echo '<li class="divider"></li>';
						}
						if (verificaSubmenu('relatorio-leads', $perfil_usuario)) {
							echo '<li ><a href="/api/iframe?token='.$request->token.'&view=relatorio-leads"><i class="fa fa-address-card" aria-hidden="true"></i> Leads</a></li>';
>>>>>>> 5cfe18e1256c22b2f7585786435771d80b84680b
							echo '<li class="divider"></li>';
						}						
						echo '</ul>';
						echo '</li>';
						echo '<li class="divider"></li>';
					}

					if (verificaSubmenu('relatorio-faturamento', $perfil_usuario) || verificaSubmenu('relatorio-reajuste', $perfil_usuario) || verificaSubmenu('relatorio-indicadores-financeiro', $perfil_usuario) || verificaSubmenu('relatorio-boleto', $perfil_usuario) || verificaSubmenu('relatorio-contas', $perfil_usuario) || verificaSubmenu('relatorio-fluxo-caixa', $perfil_usuario) || verificaSubmenu('relatorio-patrimonio', $perfil_usuario) || verificaSubmenu('relatorio-estoque', $perfil_usuario) || verificaSubmenu('relatorio-treasy', $perfil_usuario)) {
						echo '<li class="dropdown dropdown-submenu">';
						echo '<a href="" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-dollar" aria-hidden="true"></i> Financeiro</a>';
						echo '<ul class="dropdown-menu">';
						echo '<li class="divider"></li>';

						if (verificaSubmenu('relatorio-boleto', $perfil_usuario)) {
<<<<<<< HEAD
							echo '<li ><a href="/api/iframe?token=<?php echo $request->token ?>&view=relatorio-boleto"><i class="fa fa-barcode" aria-hidden="true"></i> Boletos</a></li>';
=======
							echo '<li ><a href="/api/iframe?token='.$request->token.'&view=relatorio-boleto"><i class="fa fa-barcode" aria-hidden="true"></i> Boletos</a></li>';
>>>>>>> 5cfe18e1256c22b2f7585786435771d80b84680b
							echo '<li class="divider"></li>';
                        }

                        if (verificaSubmenu('relatorio-contas', $perfil_usuario)) {
<<<<<<< HEAD
							echo '<li ><a href="/api/iframe?token=<?php echo $request->token ?>&view=relatorio-contas"><i class="fas fa-hand-holding-usd" aria-hidden="true"></i> Controle de Contas</a></li>';
=======
							echo '<li ><a href="/api/iframe?token='.$request->token.'&view=relatorio-contas"><i class="fas fa-hand-holding-usd" aria-hidden="true"></i> Controle de Contas</a></li>';
>>>>>>> 5cfe18e1256c22b2f7585786435771d80b84680b
							echo '<li class="divider"></li>';
						}
						
						if (verificaSubmenu('relatorio-estoque', $perfil_usuario)) {
<<<<<<< HEAD
							echo '<li ><a href="/api/iframe?token=<?php echo $request->token ?>&view=relatorio-estoque"><i class="fa fa-archive" aria-hidden="true"></i> Estoque</a></li>';
=======
							echo '<li ><a href="/api/iframe?token='.$request->token.'&view=relatorio-estoque"><i class="fa fa-archive" aria-hidden="true"></i> Estoque</a></li>';
>>>>>>> 5cfe18e1256c22b2f7585786435771d80b84680b
							echo '<li class="divider"></li>';
						}

                        if (verificaSubmenu('relatorio-faturamento', $perfil_usuario)) {
<<<<<<< HEAD
							echo '<li ><a href="/api/iframe?token=<?php echo $request->token ?>&view=relatorio-faturamento"><i class="fa fa-money" aria-hidden="true"></i> Faturamento</a></li>';
=======
							echo '<li ><a href="/api/iframe?token='.$request->token.'&view=relatorio-faturamento"><i class="fa fa-money" aria-hidden="true"></i> Faturamento</a></li>';
>>>>>>> 5cfe18e1256c22b2f7585786435771d80b84680b
							echo '<li class="divider"></li>';
                        }

                        if (verificaSubmenu('relatorio-fluxo-caixa', $perfil_usuario)) {
<<<<<<< HEAD
							echo '<li ><a href="/api/iframe?token=<?php echo $request->token ?>&view=relatorio-fluxo-caixa"><i class="fas fa-chart-area"></i> Fluxo de Caixa</a></li>';
=======
							echo '<li ><a href="/api/iframe?token='.$request->token.'&view=relatorio-fluxo-caixa"><i class="fas fa-chart-area"></i> Fluxo de Caixa</a></li>';
>>>>>>> 5cfe18e1256c22b2f7585786435771d80b84680b
							echo '<li class="divider"></li>';
                        }

                        if (verificaSubmenu('relatorio-indicadores-financeiro', $perfil_usuario)) {
<<<<<<< HEAD
							echo '<li ><a href="/api/iframe?token=<?php echo $request->token ?>&view=relatorio-indicadores-financeiro"><i class="fa fa-bar-chart" aria-hidden="true"></i> Indicadores</a></li>';
=======
							echo '<li ><a href="/api/iframe?token='.$request->token.'&view=relatorio-indicadores-financeiro"><i class="fa fa-bar-chart" aria-hidden="true"></i> Indicadores</a></li>';
>>>>>>> 5cfe18e1256c22b2f7585786435771d80b84680b
							echo '<li class="divider"></li>';
						}
						
						if (verificaSubmenu('relatorio-patrimonio', $perfil_usuario)) {
<<<<<<< HEAD
							echo '<li ><a href="/api/iframe?token=<?php echo $request->token ?>&view=relatorio-patrimonio"><i class="fas fa-city"></i> Patrimônio</a></li>';
=======
							echo '<li ><a href="/api/iframe?token='.$request->token.'&view=relatorio-patrimonio"><i class="fas fa-city"></i> Patrimônio</a></li>';
>>>>>>> 5cfe18e1256c22b2f7585786435771d80b84680b
							echo '<li class="divider"></li>';
						}
                        
						if (verificaSubmenu('relatorio-reajuste', $perfil_usuario)) {
<<<<<<< HEAD
							echo '<li ><a href="/api/iframe?token=<?php echo $request->token ?>&view=relatorio-reajuste"><i class="fa fa-balance-scale" aria-hidden="true"></i> Reajustes</a></li>';
=======
							echo '<li ><a href="/api/iframe?token='.$request->token.'&view=relatorio-reajuste"><i class="fa fa-balance-scale" aria-hidden="true"></i> Reajustes</a></li>';
>>>>>>> 5cfe18e1256c22b2f7585786435771d80b84680b
							echo '<li class="divider"></li>';
						}

						if (verificaSubmenu('relatorio-treasy', $perfil_usuario)) {
<<<<<<< HEAD
							echo '<li ><a href="/api/iframe?token=<?php echo $request->token ?>&view=relatorio-treasy"><i class="fas fa-table"></i> Treasy</a></li>';
=======
							echo '<li ><a href="/api/iframe?token='.$request->token.'&view=relatorio-treasy"><i class="fas fa-table"></i> Treasy</a></li>';
>>>>>>> 5cfe18e1256c22b2f7585786435771d80b84680b
							echo '<li class="divider"></li>';
						}
						
						echo '</ul>';
						echo '</li>';
						echo '<li class="divider"></li>';
					}
	
					if (verificaSubmenu('relatorio-redes-plantao', $perfil_usuario) || verificaSubmenu('relatorio-redes-atendimento', $perfil_usuario)) {
						echo '<li class="dropdown dropdown-submenu">';
						echo '<a href="" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-network-wired" aria-hidden="true"></i> Redes</a>';
						echo '<ul class="dropdown-menu">';
                        echo '<li class="divider"></li>';
						
						if (verificaSubmenu('relatorio-redes-atendimento', $perfil_usuario)) {
<<<<<<< HEAD
							echo '<li ><a href="/api/iframe?token=<?php echo $request->token ?>&view=relatorio-redes-atendimento"><i class="fas fa-headset"></i> Atendimentos</a></li>';
=======
							echo '<li ><a href="/api/iframe?token='.$request->token.'&view=relatorio-redes-atendimento"><i class="fas fa-headset"></i> Atendimentos</a></li>';
>>>>>>> 5cfe18e1256c22b2f7585786435771d80b84680b
							echo '<li class="divider"></li>';
                        }

                        if (verificaSubmenu('relatorio-redes-ativacoes', $perfil_usuario)) {
<<<<<<< HEAD
							echo '<li ><a href="/api/iframe?token=<?php echo $request->token ?>&view=relatorio-redes-ativacoes"><i class="far fa-handshake"></i> Ativações</a></li>';
=======
							echo '<li ><a href="/api/iframe?token='.$request->token.'&view=relatorio-redes-ativacoes"><i class="far fa-handshake"></i> Ativações</a></li>';
>>>>>>> 5cfe18e1256c22b2f7585786435771d80b84680b
							echo '<li class="divider"></li>';
                        }

                        if (verificaSubmenu('relatorio-redes-atividades-internas', $perfil_usuario)) {
<<<<<<< HEAD
							echo '<li ><a href="/api/iframe?token=<?php echo $request->token ?>&view=relatorio-redes-atividades-internas"><i class="fas fa-hammer"></i> Atividades Internas</a></li>';
=======
							echo '<li ><a href="/api/iframe?token='.$request->token.'&view=relatorio-redes-atividades-internas"><i class="fas fa-hammer"></i> Atividades Internas</a></li>';
>>>>>>> 5cfe18e1256c22b2f7585786435771d80b84680b
							echo '<li class="divider"></li>';
                        }
                        
                        if (verificaSubmenu('relatorio-indicadores-redes', $perfil_usuario)) {
<<<<<<< HEAD
							echo '<li ><a href="/api/iframe?token=<?php echo $request->token ?>&view=relatorio-indicadores-redes"><i class="fa fa-bar-chart" aria-hidden="true"></i> Indicadores</a></li>';
=======
							echo '<li ><a href="/api/iframe?token='.$request->token.'&view=relatorio-indicadores-redes"><i class="fa fa-bar-chart" aria-hidden="true"></i> Indicadores</a></li>';
>>>>>>> 5cfe18e1256c22b2f7585786435771d80b84680b
							echo '<li class="divider"></li>';
						}

						if (verificaSubmenu('relatorio-redes-plantao', $perfil_usuario)) {
<<<<<<< HEAD
							echo '<li ><a href="/api/iframe?token=<?php echo $request->token ?>&view=relatorio-redes-plantao"><i class="fa fa-user-clock" aria-hidden="true"></i> Plantões</a></li>';
=======
							echo '<li ><a href="/api/iframe?token='.$request->token.'&view=relatorio-redes-plantao"><i class="fa fa-user-clock" aria-hidden="true"></i> Plantões</a></li>';
>>>>>>> 5cfe18e1256c22b2f7585786435771d80b84680b
							echo '<li class="divider"></li>';
						}

						echo '</ul>';
						echo '</li>';
						echo '<li class="divider"></li>';
					}
					
                    if (verificaSubmenu('relatorio-ocorrencias', $perfil_usuario) || verificaSubmenu('relatorio-pessoa-rh', $perfil_usuario) || verificaSubmenu('relatorio-selecao', $perfil_usuario) || verificaSubmenu('relatorio-treinamento', $perfil_usuario) || verificaSubmenu('relatorio-funcionario', $perfil_usuario)) {
						echo '<li class="dropdown dropdown-submenu">';
						echo '<a href="" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-users" aria-hidden="true"></i> RH</a>';
						echo '<ul class="dropdown-menu">';
                        echo '<li class="divider"></li>';
                        if (verificaSubmenu('relatorio-pessoa-rh', $perfil_usuario)) {
<<<<<<< HEAD
							echo '<li ><a href="/api/iframe?token=<?php echo $request->token ?>&view=relatorio-pessoa-rh"><i class="fas fa-address-book"></i></i> Currículos</a></li>';
							echo '<li class="divider"></li>';
						}
						if (verificaSubmenu('relatorio-funcionario', $perfil_usuario)) {
							echo '<li ><a href="/api/iframe?token=<?php echo $request->token ?>&view=relatorio-funcionario"><i class="fas fa-address-card" aria-hidden="true"></i> Funcionários</a></li>';
							echo '<li class="divider"></li>';
						}
						if (verificaSubmenu('relatorio-ocorrencias', $perfil_usuario)) {
							echo '<li ><a href="/api/iframe?token=<?php echo $request->token ?>&view=relatorio-ocorrencias"><i class="fa fa-exclamation-circle" aria-hidden="true"></i> Ocorrências</a></li>';
							echo '<li class="divider"></li>';
						}
						if (verificaSubmenu('relatorio-selecao', $perfil_usuario)) {
							echo '<li ><a href="/api/iframe?token=<?php echo $request->token ?>&view=relatorio-selecao"><i class="fas fa-chalkboard-teacher" aria-hidden="true"></i> Seleção</a></li>';
							echo '<li class="divider"></li>';
						}	
						if (verificaSubmenu('relatorio-treinamento', $perfil_usuario)) {
							echo '<li ><a href="/api/iframe?token=<?php echo $request->token ?>&view=relatorio-treinamento"><i class="fas fa-graduation-cap" aria-hidden="true"></i> Treinamentos</a></li>';
=======
							echo '<li ><a href="/api/iframe?token='.$request->token.'&view=relatorio-pessoa-rh"><i class="fas fa-address-book"></i></i> Currículos</a></li>';
							echo '<li class="divider"></li>';
						}
						if (verificaSubmenu('relatorio-funcionario', $perfil_usuario)) {
							echo '<li ><a href="/api/iframe?token='.$request->token.'&view=relatorio-funcionario"><i class="fas fa-address-card" aria-hidden="true"></i> Funcionários</a></li>';
							echo '<li class="divider"></li>';
						}
						if (verificaSubmenu('relatorio-ocorrencias', $perfil_usuario)) {
							echo '<li ><a href="/api/iframe?token='.$request->token.'&view=relatorio-ocorrencias"><i class="fa fa-exclamation-circle" aria-hidden="true"></i> Ocorrências</a></li>';
							echo '<li class="divider"></li>';
						}
						if (verificaSubmenu('relatorio-selecao', $perfil_usuario)) {
							echo '<li ><a href="/api/iframe?token='.$request->token.'&view=relatorio-selecao"><i class="fas fa-chalkboard-teacher" aria-hidden="true"></i> Seleção</a></li>';
							echo '<li class="divider"></li>';
						}	
						if (verificaSubmenu('relatorio-treinamento', $perfil_usuario)) {
							echo '<li ><a href="/api/iframe?token='.$request->token.'&view=relatorio-treinamento"><i class="fas fa-graduation-cap" aria-hidden="true"></i> Treinamentos</a></li>';
>>>>>>> 5cfe18e1256c22b2f7585786435771d80b84680b
							echo '<li class="divider"></li>';
						}

						echo '</ul>';
						echo '</li>';
						echo '<li class="divider"></li>';
					}

					if (verificaSubmenu('relatorio-topicos', $perfil_usuario)) {
<<<<<<< HEAD
						echo '<li ><a href="/api/iframe?token=<?php echo $request->token ?>&view=relatorio-topicos"><i class="fa fa-comment-o" aria-hidden="true"></i> Tópicos</a></li>';
=======
						echo '<li ><a href="/api/iframe?token='.$request->token.'&view=relatorio-topicos"><i class="fa fa-comment-o" aria-hidden="true"></i> Tópicos</a></li>';
>>>>>>> 5cfe18e1256c22b2f7585786435771d80b84680b
						echo '<li class="divider"></li>';
					}

					if (verificaSubmenu('relatorio-painel', $perfil_usuario) || verificaSubmenu('relatorio-email', $perfil_usuario)) {
						echo '<li class="dropdown dropdown-submenu">';
						echo '<a href="" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-cogs" aria-hidden="true"></i> Sistema</a>';
						echo '<ul class="dropdown-menu">';
						echo '<li class="divider"></li>';

						if (verificaSubmenu('relatorio-email', $perfil_usuario)) {
<<<<<<< HEAD
							echo '<li ><a href="/api/iframe?token=<?php echo $request->token ?>&view=relatorio-email"><i class="far fa-envelope" aria-hidden="true"></i> E-mails</a></li>';
=======
							echo '<li ><a href="/api/iframe?token='.$request->token.'&view=relatorio-email"><i class="far fa-envelope" aria-hidden="true"></i> E-mails</a></li>';
>>>>>>> 5cfe18e1256c22b2f7585786435771d80b84680b
							echo '<li class="divider"></li>';
						}

						if (verificaSubmenu('relatorio-painel', $perfil_usuario)) {
<<<<<<< HEAD
							echo '<li ><a href="/api/iframe?token=<?php echo $request->token ?>&view=relatorio-painel"><i class="fa fa-desktop" aria-hidden="true"></i> Painel do Cliente</a></li>';
=======
							echo '<li ><a href="/api/iframe?token='.$request->token.'&view=relatorio-painel"><i class="fa fa-desktop" aria-hidden="true"></i> Painel do Cliente</a></li>';
>>>>>>> 5cfe18e1256c22b2f7585786435771d80b84680b
							echo '<li class="divider"></li>';
						}
						
						echo '</ul>';
						echo '</li>';
						echo '<li class="divider"></li>';
					}

					echo '
						</ul>
					</li>
					';
				}  
				  
				if (verificaMenu('sistema', $perfil_usuario)){
					echo '
					<li class="dropdown ' . $active['sistema'] . '">
						<a href="" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-cogs"></i> Sistema <strong class="caret"></strong></a>
						<ul class="dropdown-menu">
					';
					echo '<li class="divider"></li>';
					
					if (verificaSubmenu('categoria-busca', $perfil_usuario)) {
<<<<<<< HEAD
						echo '<li ><a href="/api/iframe?token=<?php echo $request->token ?>&view=categoria-busca"><i class="fa fa-tags" aria-hidden="true"></i> Categorias</a></li>';
=======
						echo '<li ><a href="/api/iframe?token='.$request->token.'&view=categoria-busca"><i class="fa fa-tags" aria-hidden="true"></i> Categorias</a></li>';
>>>>>>> 5cfe18e1256c22b2f7585786435771d80b84680b
						echo '<li class="divider"></li>';
					}

					if ( verificaSubmenu('sistema-boleto-busca', $perfil_usuario) || verificaSubmenu('sistema-nfs-busca', $perfil_usuario) ) {
						echo '<li class="dropdown dropdown-submenu">';
						echo '<a href="" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-dollar"></i> Financeiro</a>';
						echo '<ul class="dropdown-menu">';
						echo '<li class="divider"></li>';
						if (verificaSubmenu('sistema-boleto-busca', $perfil_usuario)) {
<<<<<<< HEAD
							echo '<li ><a href="/api/iframe?token=<?php echo $request->token ?>&view=sistema-boleto-busca"><i class="fa fa-barcode"></i> Boletos</a></li>';
							echo '<li class="divider"></li>';
						}
						if (verificaSubmenu('sistema-nfs-busca', $perfil_usuario)) {
							echo '<li ><a href="/api/iframe?token=<?php echo $request->token ?>&view=sistema-nfs-busca"><i class="fas fa-file-invoice-dollar"></i> NFS-e</a></li>';
=======
							echo '<li ><a href="/api/iframe?token='.$request->token.'&view=sistema-boleto-busca"><i class="fa fa-barcode"></i> Boletos</a></li>';
							echo '<li class="divider"></li>';
						}
						if (verificaSubmenu('sistema-nfs-busca', $perfil_usuario)) {
							echo '<li ><a href="/api/iframe?token='.$request->token.'&view=sistema-nfs-busca"><i class="fas fa-file-invoice-dollar"></i> NFS-e</a></li>';
>>>>>>> 5cfe18e1256c22b2f7585786435771d80b84680b
							echo '<li class="divider"></li>';
						}
						
						echo '</ul>';
						echo '</li>';
						echo '<li class="divider"></li>';
                    }

					if (verificaSubmenu('log-busca', $perfil_usuario)) {
<<<<<<< HEAD
						echo '<li ><a href="/api/iframe?token=<?php echo $request->token ?>&view=log-busca"><i class="fa fa-robot" aria-hidden="true"></i> Log</a></li>';
=======
						echo '<li ><a href="/api/iframe?token='.$request->token.'&view=log-busca"><i class="fa fa-robot" aria-hidden="true"></i> Log</a></li>';
>>>>>>> 5cfe18e1256c22b2f7585786435771d80b84680b
						echo '<li class="divider"></li>';
					}

					if (verificaSubmenu('pagina-sistema-busca', $perfil_usuario)) {
<<<<<<< HEAD
						echo '<li ><a href="/api/iframe?token=<?php echo $request->token ?>&view=pagina-sistema-busca"><i class="fa fa-file-code-o"></i> Páginas do sistema</a></li>';
=======
						echo '<li ><a href="/api/iframe?token='.$request->token.'&view=pagina-sistema-busca"><i class="fa fa-file-code-o"></i> Páginas do sistema</a></li>';
>>>>>>> 5cfe18e1256c22b2f7585786435771d80b84680b
						echo '<li class="divider"></li>';
                    }    

					if (verificaSubmenu('usuario-painel-busca', $perfil_usuario) || verificaSubmenu('painel-cliente-novidade-busca', $perfil_usuario) || verificaSubmenu('painel-cliente-horarios-form', $perfil_usuario)) {
						echo '<li class="dropdown dropdown-submenu">';
						echo '<a href="" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa fa-desktop" aria-hidden="true"></i> Painel do cliente</a>';
						echo '<ul class="dropdown-menu">';
						echo '<li class="divider"></li>';
						if (verificaSubmenu('usuario-painel-busca', $perfil_usuario)) {
<<<<<<< HEAD
							echo '<li ><a href="/api/iframe?token=<?php echo $request->token ?>&view=usuario-painel-busca"><i class="fa fa-user-circle"></i> Usuários</a></li>';
							echo '<li class="divider"></li>';
						}
						if (verificaSubmenu('painel-cliente-novidade-busca', $perfil_usuario)) {
							echo '<li ><a href="/api/iframe?token=<?php echo $request->token ?>&view=painel-cliente-novidade-busca"><i class="fa fa-lightbulb-o"></i> Novidades</a></li>';
							echo '<li class="divider"></li>';
						}
						if (verificaSubmenu('painel-cliente-horarios-form', $perfil_usuario)) {
							echo '<li ><a href="/api/iframe?token=<?php echo $request->token ?>&view=painel-cliente-horarios-form"><i class="fa fa-clock-o"></i> Horários</a></li>';
=======
							echo '<li ><a href="/api/iframe?token='.$request->token.'&view=usuario-painel-busca"><i class="fa fa-user-circle"></i> Usuários</a></li>';
							echo '<li class="divider"></li>';
						}
						if (verificaSubmenu('painel-cliente-novidade-busca', $perfil_usuario)) {
							echo '<li ><a href="/api/iframe?token='.$request->token.'&view=painel-cliente-novidade-busca"><i class="fa fa-lightbulb-o"></i> Novidades</a></li>';
							echo '<li class="divider"></li>';
						}
						if (verificaSubmenu('painel-cliente-horarios-form', $perfil_usuario)) {
							echo '<li ><a href="/api/iframe?token='.$request->token.'&view=painel-cliente-horarios-form"><i class="fa fa-clock-o"></i> Horários</a></li>';
>>>>>>> 5cfe18e1256c22b2f7585786435771d80b84680b
							echo '<li class="divider"></li>';
						}
						echo '</ul>';
						echo '</li>';
						echo '<li class="divider"></li>';
                    }

                    if (verificaSubmenu('parametros-integracao-busca', $perfil_usuario)) {
<<<<<<< HEAD
						echo '<li ><a href="/api/iframe?token=<?php echo $request->token ?>&view=parametros-integracao-busca"><i class="fa fa-gg" aria-hidden="true"></i> Parâmetros de integração</a></li>';
=======
						echo '<li ><a href="/api/iframe?token='.$request->token.'&view=parametros-integracao-busca"><i class="fa fa-gg" aria-hidden="true"></i> Parâmetros de integração</a></li>';
>>>>>>> 5cfe18e1256c22b2f7585786435771d80b84680b
						echo '<li class="divider"></li>';
					}

					if (verificaSubmenu('perfil-sistema-busca', $perfil_usuario)) {
<<<<<<< HEAD
						echo '<li ><a href="/api/iframe?token=<?php echo $request->token ?>&view=perfil-sistema-busca"><i class="fa fa-street-view"></i> Perfis</a></li>';
=======
						echo '<li ><a href="/api/iframe?token='.$request->token.'&view=perfil-sistema-busca"><i class="fa fa-street-view"></i> Perfis</a></li>';
>>>>>>> 5cfe18e1256c22b2f7585786435771d80b84680b
						echo '<li class="divider"></li>';
					}

					if (verificaSubmenu('telao-acesso-form', $perfil_usuario)) {
<<<<<<< HEAD
						echo '<li ><a href="/api/iframe?token=<?php echo $request->token ?>&view=telao-acesso-form"><i class="fa fa-slideshare"></i> Telão - Acesso</a></li>';
=======
						echo '<li ><a href="/api/iframe?token='.$request->token.'&view=telao-acesso-form"><i class="fa fa-slideshare"></i> Telão - Acesso</a></li>';
>>>>>>> 5cfe18e1256c22b2f7585786435771d80b84680b
						echo '<li class="divider"></li>';
					}
					
					if (verificaSubmenu('vinculo-tipo-busca', $perfil_usuario)) {
<<<<<<< HEAD
						echo '<li ><a href="/api/iframe?token=<?php echo $request->token ?>&view=vinculo-tipo-busca"><i class="fa fa-link"></i> Tipos de vínculos</a></li>';
=======
						echo '<li ><a href="/api/iframe?token='.$request->token.'&view=vinculo-tipo-busca"><i class="fa fa-link"></i> Tipos de vínculos</a></li>';
>>>>>>> 5cfe18e1256c22b2f7585786435771d80b84680b
						echo '<li class="divider"></li>';
					}

					if (verificaSubmenu('usuario-busca', $perfil_usuario)) {
<<<<<<< HEAD
						echo '<li ><a href="/api/iframe?token=<?php echo $request->token ?>&view=usuario-busca"><i class="fa fa-user"></i> Usuários</a></li>';
=======
						echo '<li ><a href="/api/iframe?token='.$request->token.'&view=usuario-busca"><i class="fa fa-user"></i> Usuários</a></li>';
>>>>>>> 5cfe18e1256c22b2f7585786435771d80b84680b
						echo '<li class="divider"></li>';
					}
					
					echo '
						</ul>
					</li>
					';
				}
				?>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <?php if (verificaSubmenu('menu-softphone', $perfil_usuario)) {?><li id="li-softphone"><a href="#" data-toggle="modal" data-target="#modal_softphone" id='btn-menu-softphone'><i class="fa fa-phone-square"></i> Softphone <i id="exclamation_softphone" class="fa fa-exclamation-circle faa-flash animated" style="color: #b92c28; display: none;"></i></a></li><?php }?>

                <li class="dropdown <?=$active['usuario'];?> ">
                    <?php
					$dados_usuario = DBRead('','tb_usuario a',"INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_usuario = '$id_usuario'",'b.nome');
					$nome = explode(' ', $dados_usuario[0]['nome']);
					$nome = $nome[0];

					?>

                    <a href="" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i> <?=$nome?><strong class="caret"></strong></a>

                    <ul class="dropdown-menu">
						<li class="divider"></li>
                        <?php if (verificaSubmenu('usuario-senha', $perfil_usuario)) {
<<<<<<< HEAD
							echo '<li><a href="/api/iframe?token=<?php echo $request->token ?>&view=usuario-senha"><i class="fa fa-key"></i> Alterar senha</span></a></li>';
=======
							echo '<li><a href="/api/iframe?token='.$request->token.'&view=usuario-senha"><i class="fa fa-key"></i> Alterar senha</span></a></li>';
>>>>>>> 5cfe18e1256c22b2f7585786435771d80b84680b
							echo '<li class="divider"></li>';
						}?>


						<?php if (verificaSubmenu('gerenciar-senhas-busca', $perfil_usuario)) {
<<<<<<< HEAD
							echo '<li><a href="/api/iframe?token=<?php echo $request->token ?>&view=gerenciar-senhas-busca"><i class="fas fa-unlock-alt"></i> Acessos</span></a></li>';
=======
							echo '<li><a href="/api/iframe?token='.$request->token.'&view=gerenciar-senhas-busca"><i class="fas fa-unlock-alt"></i> Acessos</span></a></li>';
>>>>>>> 5cfe18e1256c22b2f7585786435771d80b84680b
							echo '<li class="divider"></li>';
						}?>
						
                        <?php if (verificaSubmenu('anotacoes-form', $perfil_usuario)) {
<<<<<<< HEAD
							echo '<li><a href="/api/iframe?token=<?php echo $request->token ?>&view=anotacoes-form" target="_blank"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Anotações</a></li>';
=======
							echo '<li><a href="/api/iframe?token='.$request->token.'&view=anotacoes-form" target="_blank"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Anotações</a></li>';
>>>>>>> 5cfe18e1256c22b2f7585786435771d80b84680b
							echo '<li class="divider"></li>';
						}?>

						<?php if (verificaSubmenu('faq-exibe-busca', $perfil_usuario)) {
<<<<<<< HEAD
							echo '<li><a href="/api/iframe?token=<?php echo $request->token ?>&view=faq-exibe-busca" target="_blank"><i class="fa fa-question" aria-hidden="true"></i> FAQ</a></li>';
=======
							echo '<li><a href="/api/iframe?token='.$request->token.'&view=faq-exibe-busca" target="_blank"><i class="fa fa-question" aria-hidden="true"></i> FAQ</a></li>';
>>>>>>> 5cfe18e1256c22b2f7585786435771d80b84680b
							echo '<li class="divider"></li>';
						}?>

						<li ><a href="https://drive.google.com/drive/u/0/folders/1dbyPJbAQjnFaBat4JdjtZIB9L4NWB0-Z" target="_blank"><i class="fab fa-google-drive"></i> Google Drive</a></li>
						<li class="divider"></li>

						<li ><a href="https://sites.google.com/belluno.company/sqb/home" target="_blank"><i class="fas fa-laptop"></i> Intranet</a></li>
						<li class="divider"></li>

                        <?php if (verificaSubmenu('meus-dados', $perfil_usuario)) {
<<<<<<< HEAD
							echo '<li><a href="/api/iframe?token=<?php echo $request->token ?>&view=meus-dados"><i class="fa fa-address-card-o" aria-hidden="true"></i> Meus dados</a></li>';
							echo '<li class="divider"></li>';
						}?>
                        <?php if (verificaSubmenu('ramais-internos-busca', $perfil_usuario)) {
							echo '<li><a href="/api/iframe?token=<?php echo $request->token ?>&view=ramais-internos-busca"><i class="fas fa-blender-phone"></i> Ramais internos</a></li>';
=======
							echo '<li><a href="/api/iframe?token='.$request->token.'&view=meus-dados"><i class="fa fa-address-card-o" aria-hidden="true"></i> Meus dados</a></li>';
							echo '<li class="divider"></li>';
						}?>
                        <?php if (verificaSubmenu('ramais-internos-busca', $perfil_usuario)) {
							echo '<li><a href="/api/iframe?token='.$request->token.'&view=ramais-internos-busca"><i class="fas fa-blender-phone"></i> Ramais internos</a></li>';
>>>>>>> 5cfe18e1256c22b2f7585786435771d80b84680b
							echo '<li class="divider"></li>';
						} ?>
						
						<?php if (verificaSubmenu('usuario-foto-busca', $perfil_usuario)) {
<<<<<<< HEAD
							echo '<li ><a href="/api/iframe?token=<?php echo $request->token ?>&view=usuario-foto-busca"><i class="fa fa-rocket" aria-hidden="true"></i> Time Belluno</a></li>';
=======
							echo '<li ><a href="/api/iframe?token='.$request->token.'&view=usuario-foto-busca"><i class="fa fa-rocket" aria-hidden="true"></i> Time Belluno</a></li>';
>>>>>>> 5cfe18e1256c22b2f7585786435771d80b84680b
							echo '<li class="divider"></li>';
						}?>

						<?php if (verificaSubmenu('perfil-sistema-vinculo', $perfil_usuario)) {
<<<<<<< HEAD
							echo '<li ><a href="/api/iframe?token=<?php echo $request->token ?>&view=perfil-sistema-vinculo"><i class="fa fa-link" aria-hidden="true"></i> Vínculos do perfil</a></li>';
=======
							echo '<li ><a href="/api/iframe?token='.$request->token.'&view=perfil-sistema-vinculo"><i class="fa fa-link" aria-hidden="true"></i> Vínculos do perfil</a></li>';
>>>>>>> 5cfe18e1256c22b2f7585786435771d80b84680b
							echo '<li class="divider"></li>';
						}?>


                        <li><a href="class/Login.php?sair=1" onclick="if (!confirm('Sair do sistema?')) { return false; } else { modalAguarde(); }"><i class="fa fa-sign-out"></i> Sair</a></li>
						<li class="divider"></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</div>
<?php
if (verificaSubmenu('menu-softphone', $perfil_usuario)) {
	//include_once "menu-softphone.php";
}
if (verificaSubmenu('arvore-exibe', $perfil_usuario)) {
	?>
<div class="modal fade noprint" id="modal_arvore"  tabindex="-1" role="dialog">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Exibir árvore</h4>
            </div>
            <form action="adm.php" id="exibir_arvore_form" method="get">
                <div class="modal-body" >
					<input type="hidden" name="view" value="arvore-exibe">
					
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label class="control-label">ID do passo:</label>
								<input class="form-control input-sm number_int" name="id" value="1" type="text">
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label class="control-label">Opções:</label>
								<select class="form-control input-sm" name="nivel_limite">
									<!-- <option value='1'>1 Nível</option> -->
									<option value='0'>Até o final</option>
								</select>
							</div>
						</div>
					</div>

					<div class="alert alert-warning text-center">Carregar toda árvore até o final pode causar lentidão.</div>

						<!-- <div class="form-group">
                            <label class="control-label col-md-12" style="text-align: left;">Contrato (cliente):</label>
                            <div class="col-md-12">
								<div class="input-group">
									<input class="form-control input-sm" id="busca_contrato" type="text" name="busca_contrato" placeholder="Informe o nome ou CNPJ..." autocomplete="off" readonly required />
                                    <div class="input-group-btn">
                                        <button class="btn btn-info btn-sm" id="habilita_busca_contrato" name="habilita_busca_contrato" type="button" title="Clique para selecionar o contrato" style="height: 30px;"><i class="fa fa-search"></i></button>
                                    </div>
								</div>
								<input type="hidden" name="id_contrato_plano_pessoa" id="id_contrato_plano_pessoa"/>
                            </div>
						</div> -->
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label class="control-label">Contrato (cliente):</label>
								<select class="form-control input-sm" name="id_contrato_plano_pessoa">
									<option value="">Todos</option>
										<?php
											$dados_contrato = DBRead('', 'tb_contrato_plano_pessoa a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa INNER JOIN tb_plano c ON a.id_plano = c.id_plano WHERE c.cod_servico = 'call_suporte' ORDER BY b.nome ASC", "a.id_contrato_plano_pessoa, a.id_pessoa, b.nome, c.nome AS 'plano', c.cod_servico");
											if($dados_contrato){
												foreach ($dados_contrato as $conteudo_contrato) {
													echo "<option value='".$conteudo_contrato['id_contrato_plano_pessoa']."'>".$conteudo_contrato['nome']." - ".getNomeServico($conteudo_contrato['cod_servico'])." - ".$conteudo_contrato['plano']." (".$conteudo_contrato['id_contrato_plano_pessoa'].")</option>";
												}
											}
										?>
								</select>
							</div>
						</div>
					</div>

                </div>
                <div class="modal-footer text-center">
                    <button type="submit" class="btn btn-primary"><i class="fa fa-check"></i> Ok</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    $(document).on('submit', '#exibir_arvore_form', function () {
        modalAguarde();
    });

	// // Atribui evento e função para limpeza dos campos
		// $('#busca_contrato').on('input', limpaCamposContrato);
		// // Dispara o Autocomplete da pessoa a partir do segundo caracter
		// $("#busca_contrato").autocomplete({
		//         minLength: 2,
		//         source: function (request, response) {
		//             $.ajax({
		//                 url: "class/ContratoAutocomplete.php",
		//                 dataType: "json",
		//                 data: {
		//                     acao: 'autocomplete',
		//                     parametros: { 
		//                         'nome' : $('#busca_contrato').val(),
		//                         'cod_servico' : 'call_suporte'
		//                     }
		//                 },
		//                 success: function (data) {
		//                     response(data);
		//                 }
		//             });
		//         },
		//         focus: function (event, ui) {
		//             $("#busca_contrato").val(ui.item.nome + " - " + ui.item.servico + " - " + ui.item.plano + " (" + ui.item.id_contrato_plano_pessoa + ")");
		//             carregarDadosContrato(ui.item.id_contrato_plano_pessoa);
		//             return false;
		//         },
		//         select: function (event, ui) {
		//             $("#busca_contrato").val(ui.item.nome + " - " + ui.item.servico + " - " + ui.item.plano + " (" + ui.item.id_contrato_plano_pessoa + ")");
		//             $('#busca_contrato').attr("readonly", true);
		//             return false;
		//         }
		//     })
		//     .autocomplete("instance")._renderItem = function(ul, item){
		//         if(!item.razao_social){
		//             item.razao_social = '';
		//         }
		//         if(!item.cpf_cnpj){
		//             item.cpf_cnpj = '';
		//         }
		//     return $("<li>").append("<a><strong>"+item.id_pessoa+" - "+ item.nome + " </strong><br>" +item.razao_social+ "<br>" +item.cpf_cnpj+ "<br>" + item.servico + " - " + item.plano + " (" + item.id_contrato_plano_pessoa + ")" + "</a><hr style='margin-bottom: 0px;'>").appendTo(ul);
		// };
		// // Função para carregar os dados da consulta nos respectivos campos
		// function carregarDadosContrato(id) {
		//     var busca = $('#busca_contrato').val();
		//     if(busca != "" && busca.length >= 2){
		//         $.ajax({
		//             url: "class/ContratoAutocomplete.php",
		//             dataType: "json",
		//             data: {
		//                 acao: 'consulta',
		//                 parametros: {
		//                     'id' : id,
		//                 }
		//             },
		//             success: function (data) {
		//                 $('#id_contrato_plano_pessoa').val(data[0].id_contrato_plano_pessoa);
		//                 seleciona_contrato(data[0].id_contrato_plano_pessoa);
		//             }
		//         });
		//     }
		// }
		// // Função para limpar os campos caso a busca esteja vazia
		// function limpaCamposContrato(){
		//     var busca = $('#busca_contrato').val();
		//     if (busca == "") {
		//         $('#id_contrato_plano_pessoa').val('');
		//     }
		// }
		// $(document).on('click', '#habilita_busca_contrato', function(){
		//     $('#id_contrato_plano_pessoa').val('');
		//     $('#busca_contrato').val('');
		//     $('#busca_contrato').attr("readonly", false);
		//     $('#busca_contrato').focus();
    // });


</script>
<?php }?>