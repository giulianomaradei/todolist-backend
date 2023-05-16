<div class="container-fluid">

	<style>
		.btn-quadro{
			margin-top: 10px;
			margin-bottom: 10px;
			font-size: 16px;
		}
	</style>
	
	<div class="panel panel-default">
		<div class="panel-heading clearfix">
			<h3 class="panel-title text-left pull-left" style="margin-top: 2px;">Quadro Informativo:</h3>
			<div class="panel-title text-right pull-right"><a href="/api/iframe?token=<?php echo $request->token ?>&view=ativacao-quadro-informativo&ativacao=1" class="btn btn-xs btn-info" style="color: #fff;"><i class="fa fa-handshake-o" aria-hidden="true"></i> Ativação</a></div>
		</div>
		<div class="panel-body">
		
				<div class="col-md-6 col-lg-4">
					<div class="btn-group btn-group-justified" role="group">
            			<div class="btn-group" role="group">
							<a href="/api/iframe?token=<?php echo $request->token ?>&view=acesso-equipamento-busca" class="btn btn-default btn-lg btn-quadro"><i class="fa fa-microchip" aria-hidden="true"></i> Acessos a equipamentos</a>
						</div>
					</div>
				</div>
				<div class="col-md-6 col-lg-4">
					<div class="btn-group btn-group-justified" role="group">
            			<div class="btn-group" role="group">
							<a href="/api/iframe?token=<?php echo $request->token ?>&view=configuracao-roteadores-busca" class="btn btn-default btn-lg btn-quadro"><i class="fa fa-cog" aria-hidden="true"></i> Conexões de cabos</a>
						</div>
					</div>
				</div>

				<div class="col-md-6 col-lg-4">
					<div class="btn-group btn-group-justified" role="group">
            			<div class="btn-group" role="group">
							<a href="/api/iframe?token=<?php echo $request->token ?>&view=equipamento-busca" class="btn btn-default btn-lg btn-quadro"><i class="fas fa-wifi" aria-hidden="true"></i> Equipamentos</a>
						</div>
					</div>
				</div>

				<div class="col-md-6 col-lg-4">
					<div class="btn-group btn-group-justified" role="group">
            			<div class="btn-group" role="group">
							<a href="/api/iframe?token=<?php echo $request->token ?>&view=horario-busca" class="btn btn-default btn-lg btn-quadro"><i class="fa fa-clock-o" aria-hidden="true"></i> Horários</a>
						</div>
					</div>
				</div>
				<div class="col-md-6 col-lg-4">
					<div class="btn-group btn-group-justified" role="group">
            			<div class="btn-group" role="group">
							<a href="/api/iframe?token=<?php echo $request->token ?>&view=informacoes-gerais-busca" class="btn btn-default btn-lg btn-quadro"><i class="fa fa-list-alt" aria-hidden="true"></i> Informações gerais e de registro</a>
						</div>
					</div>
				</div>
				<div class="col-md-6 col-lg-4">
					<div class="btn-group btn-group-justified" role="group">
            			<div class="btn-group" role="group">
							<a href="/api/iframe?token=<?php echo $request->token ?>&view=integracao-busca" class="btn btn-default btn-lg btn-quadro"><i class="fa fa-joomla" aria-hidden="true"></i> Integrações</a>
						</div>
					</div>
				</div>
				<div class="col-md-6 col-lg-4">
					<div class="btn-group btn-group-justified" role="group">
            			<div class="btn-group" role="group">
							<a href="/api/iframe?token=<?php echo $request->token ?>&view=localizacao-busca" class="btn btn-default btn-lg btn-quadro"><i class="fa fa-map" aria-hidden="true"></i> Localizações</a>
						</div>
					</div>
				</div>
				<div class="col-md-6 col-lg-4">
					<div class="btn-group btn-group-justified" role="group">
            			<div class="btn-group" role="group">
							<a href="/api/iframe?token=<?php echo $request->token ?>&view=manual-busca" class="btn btn-default btn-lg btn-quadro"><i class="fa fa-book" aria-hidden="true"></i> Manuais</a>
						</div>
					</div>
				</div>
				<div class="col-md-6 col-lg-4">
					<div class="btn-group btn-group-justified" role="group">
            			<div class="btn-group" role="group">
							<a href="/api/iframe?token=<?php echo $request->token ?>&view=parametro-busca" class="btn btn-default btn-lg btn-quadro"><i class="fa fa-sliders" aria-hidden="true"></i> Parâmetros</a>
						</div>
					</div>
				</div>
				<div class="col-md-6 col-lg-4">

					<div class="btn-group btn-group-justified" role="group">
            			<div class="btn-group" role="group">
							<a href="/api/iframe?token=<?php echo $request->token ?>&view=plano-cliente-busca" class="btn btn-default btn-lg btn-quadro"><i class="fa fa-cube" aria-hidden="true"></i> Planos</a>
						</div>
					</div>

					
				</div>
				<div class="col-md-6 col-lg-4">

					<div class="btn-group btn-group-justified" role="group">
            			<div class="btn-group" role="group">
							<a href="/api/iframe?token=<?php echo $request->token ?>&view=plantonista-busca" class="btn btn-default btn-lg btn-quadro"><i class="fa fa-id-badge" aria-hidden="true"></i> Plantonistas</a>
						</div>
					</div>
					
				</div>
				<div class="col-md-6 col-lg-4">

					<div class="btn-group btn-group-justified" role="group">
            			<div class="btn-group" role="group">
							<a href="/api/iframe?token=<?php echo $request->token ?>&view=prazo-retorno-busca" class="btn btn-default btn-lg btn-quadro"><i class="fa fa-hourglass-half" aria-hidden="true"></i> Prazos de retorno</a>
						</div>
					</div>
					
				</div>
				<div class="col-md-6 col-lg-4">

					<div class="btn-group btn-group-justified" role="group">
            			<div class="btn-group" role="group">
							<a href="/api/iframe?token=<?php echo $request->token ?>&view=sinal-equipamento-busca" class="btn btn-default btn-lg btn-quadro"><i class="fa fa-signal" aria-hidden="true"></i> Sinais dos equipamentos</a>
						</div>
					</div>
					
				</div>
				<div class="col-md-6 col-lg-4">

					<div class="btn-group btn-group-justified" role="group">
            			<div class="btn-group" role="group">
							<a href="/api/iframe?token=<?php echo $request->token ?>&view=sistema-gestao-busca" class="btn btn-default btn-lg btn-quadro"><i class="fa fa-tachometer"></i> Sistemas de gestão</a>
						</div>
					</div>
					
				</div>
				
				<div class="col-md-6 col-lg-4">

					<div class="btn-group btn-group-justified" role="group">
            			<div class="btn-group" role="group">
							<a href="/api/iframe?token=<?php echo $request->token ?>&view=sistema-chat-busca" class="btn btn-default btn-lg btn-quadro"><i class="fa fa-comments"></i> Sistemas de chat</a>
						</div>
					</div>
					
				</div>

				<div class="col-md-6 col-lg-4">

					<div class="btn-group btn-group-justified" role="group">
            			<div class="btn-group" role="group">
							<a href="/api/iframe?token=<?php echo $request->token ?>&view=reinicio-equipamento-busca" class="btn btn-default btn-lg btn-quadro"><i class="fa fa-clock-o" aria-hidden="true"></i> Tempo de reinício dos equipamentos</a>
						</div>
					</div>
					
				</div>
				<div class="col-md-6 col-lg-4">

					<div class="btn-group btn-group-justified" role="group">
            			<div class="btn-group" role="group">
							<a href="/api/iframe?token=<?php echo $request->token ?>&view=ura-busca" class="btn btn-default btn-lg btn-quadro"><i class="fa fa-phone" aria-hidden="true"></i> URAs</a>
						</div>
					</div>
					
					
				</div>

				<div class="col-md-6 col-lg-4">
					
					<div class="btn-group btn-group-justified" role="group">
            			<div class="btn-group" role="group">
							<a href="/api/iframe?token=<?php echo $request->token ?>&view=velocidade-minima-encaminhar-busca" class="btn btn-default btn-lg btn-quadro"><i class="fa fa-level-up" aria-hidden="true"></i> Velocidades mínimas para encaminhamento</a>
						</div>
					</div>

				</div>
				
			</div>
	</div>

</div>
