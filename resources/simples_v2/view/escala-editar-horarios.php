<?php
require_once(__DIR__."/../class/System.php");
if(isset($_GET['novo'])){
	$id_usuario = (int)$_GET['novo'];
}
if(isset($_GET['alterar'])){
	$id_usuario = $_GET['alterar'];
}

$mes = $_GET['mes'];
$ano = $_GET['ano'];
$data = $ano.'-'.$mes."-01";
$id_usuario_sessao = $_SESSION['id_usuario'];
$dados = DBRead('', 'tb_usuario', "WHERE id_usuario = '$id_usuario_sessao'");
$perfil_sistema = $dados[0]['id_perfil_sistema'];

if($id_usuario == $id_usuario_sessao || ($perfil_sistema != 3)){			
			
	if(!$id_usuario){
	
		$id_usuario = $_SESSION['id_usuario'];
		$nome = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_usuario = $id_usuario");
							
	}else{
		$nome = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_usuario = $id_usuario");
						
	}
	$dados_ponto_usuario = DBRead('', 'tb_usuario', "WHERE id_usuario = '$id_usuario'");					
	$id_ponto_usuario = $dados_ponto_usuario[0]['id_ponto'];
	
		$data_string_ponto = '
			{
				"report": {	
					"group_by": "",
					"columns": "name,shift,admission_date,email",
					"employee_id": '.$id_ponto_usuario.',
					"row_filters": "",
					"format": "json"
				}
			}
		';  
		
		$result_ponto = troca_dados_curl('https://api.pontomais.com.br/external_api/v1/reports/employees', $data_string_ponto, array('Content-Type: application/json','access-token: FcogHwphYbzMk3IF1tXBmh5ypV49O-74CSf7dMxE3cMcabhbaajbefjai'));
		
		$carga_horaria_ponto = explode('-', $result_ponto['data'][0][0]['data'][0]['shift']);
		$carga_horaria_ponto = end(explode(' ', $carga_horaria_ponto[0]));

	if($carga_horaria_ponto == 110){
		$carga_horaria_ponto = "meio";
		$texto_carga_horaria = 'Meio Turno';
	}else if($carga_horaria_ponto == 180){
		$carga_horaria_ponto = "integral";
		$texto_carga_horaria = 'Integral';
	}else if($carga_horaria_ponto == 100){
		$carga_horaria_ponto = "jovem";
		$texto_carga_horaria = 'Jovem Aprendiz';
	}else if($carga_horaria_ponto == 150){
		$carga_horaria_ponto = "estagio";
		$texto_carga_horaria = 'Estágio';
	}

	

	if(isset($_GET['alterar'])){
		$dados = DBRead('', 'tb_horarios_escala', "WHERE id_usuario = '".$id_usuario."' AND data_inicial = '".$data."'");

		$folga_seg = $dados[0]['folga_seg'];
		$inicial_seg = $dados[0]['inicial_seg'];
		$final_seg = $dados[0]['final_seg'];
		$inicial_sab = $dados[0]['inicial_sab'];
		$final_sab = $dados[0]['final_sab'];
		$inicial_dom = $dados[0]['inicial_dom'];
		$final_dom = $dados[0]['final_dom'];


		$carga_horaria = $dados[0]['carga_horaria'];

		// Seg a Sex
		$dados_intervalo_seg = DBRead('', 'tb_horarios_escala_intervalo', "WHERE id_horarios_escala = '".$dados[0]['id_horarios_escala']."' AND dia = 'seg' ");

		if($dados_intervalo_seg){
			if($dados_intervalo_seg[0]['tipo_intervalo'] == 1){
				$tempo_intervalo_seg = $dados_intervalo_seg[0]['tempo_intervalo'];
				$checked_intervalo_variavel_seg = 'checked';
				$display_fixo_seg = "style= 'display: none'";
				$display_variavel_seg = "";
				$required_tempo_intervalo_seg = "required";
			}else{
				$intervalo_inicial_seg = $dados_intervalo_seg[0]['intervalo_inicial'];
				$intervalo_final_seg = $dados_intervalo_seg[0]['intervalo_final'];
				$checked_intervalo_fixo_seg = 'checked';
				$display_fixo_seg = "";
				$display_variavel_seg = "style= 'display: none'";
				$required_intervalo_inicial_seg = "required";
				$required_intervalo_final_seg = "required";
			}
		}else{
			$checked_intervalo_variavel_seg = 'checked';
			$display_fixo_seg = "style= 'display: none'";
			$display_variavel_seg = "";
			
			$required_tempo_intervalo_seg = "required";
		}

		// Sabado
		$dados_intervalo_sab = DBRead('', 'tb_horarios_escala_intervalo', "WHERE id_horarios_escala = '".$dados[0]['id_horarios_escala']."' AND dia = 'sab' ");

		if($dados_intervalo_sab){
			if($dados_intervalo_sab[0]['tipo_intervalo'] == 1){
				$tempo_intervalo_sab = $dados_intervalo_sab[0]['tempo_intervalo'];
				$checked_intervalo_variavel_sab = 'checked';
				$display_fixo_sab = "style= 'display: none'";
				$display_variavel_sab = "";
				$required_tempo_intervalo_sab = "required";
			}else{
				$intervalo_inicial_sab = $dados_intervalo_sab[0]['intervalo_inicial'];
				$intervalo_final_sab = $dados_intervalo_sab[0]['intervalo_final'];
				$checked_intervalo_fixo_sab = 'checked';
				$display_fixo_sab = "";
				$display_variavel_sab = "style= 'display: none'";
				$required_intervalo_inicial_sab = "required";
				$required_intervalo_final_sab = "required";
			}
		}else{
			$checked_intervalo_variavel_sab = 'checked';
			$display_fixo_sab = "style= 'display: none'";
			$display_variavel_sab = "";
			
			$required_tempo_intervalo_sab = "required";
		}

		// Domingo
		$dados_intervalo_dom = DBRead('', 'tb_horarios_escala_intervalo', "WHERE id_horarios_escala = '".$dados[0]['id_horarios_escala']."' AND dia = 'dom' ");

		if($dados_intervalo_dom){
			if($dados_intervalo_dom[0]['tipo_intervalo'] == 1){
				$tempo_intervalo_dom = $dados_intervalo_dom[0]['tempo_intervalo'];
				$checked_intervalo_variavel_dom = 'checked';
				$display_fixo_dom = "style= 'display: none'";
				$display_variavel_dom = "";
				$required_tempo_intervalo_dom = "required";
			}else{
				$intervalo_inicial_dom = $dados_intervalo_dom[0]['intervalo_inicial'];
				$intervalo_final_dom = $dados_intervalo_dom[0]['intervalo_final'];
				$checked_intervalo_fixo_dom = 'checked';
				$display_fixo_dom = "";
				$display_variavel_dom = "style= 'display: none'";
				$required_intervalo_inicial_dom = "required";
				$required_intervalo_final_dom = "required";
			}
		}else{
			$checked_intervalo_variavel_dom = 'checked';
			$display_fixo_dom = "style= 'display: none'";
			$display_variavel_dom = "";

			$required_tempo_intervalo_dom = "required";
		}

	}else{
		$checked_intervalo_variavel_seg = 'checked';
		$display_fixo_seg = "style= 'display: none'";
		$display_variavel_seg = "";
		
		$required_tempo_intervalo_seg = "required";

		$checked_intervalo_variavel_sab = 'checked';
		$display_fixo_sab = "style= 'display: none'";
		$display_variavel_sab = "";

		$required_tempo_intervalo_sab = "required";

		$checked_intervalo_variavel_dom = 'checked';
		$display_fixo_dom = "style= 'display: none'";
		$display_variavel_dom = "";

		$required_tempo_intervalo_dom = "required";
	}

	if($carga_horaria == 'jovem' || $carga_horaria == 'estagio'){
		// $inicial_seg = "Segunda";
		// $final_seg = "Segunda";
		if($carga_horaria == 'jovem'){
			$inicial_sab = "";
			$final_sab = "";
		}
		$inicial_dom = "";
		$final_dom = "";

		

		if($carga_horaria == 'jovem'){
			$disabled_jovem = 'disabled';

			$row_intervalo_dom = "style= 'display: none'";
			$row_intervalo_sab = "style= 'display: none'";

			$required_tempo_intervalo_sab = "";
			$required_intervalo_inicial_sab = "";
			$required_intervalo_final_sab = "";

			$required_tempo_intervalo_dom = "";
			$required_intervalo_inicial_dom = "";
			$required_intervalo_final_dom = "";

		}else{

			if($dados[0]['inicial_sab']){
				$checked_sabado = 'checked';
				$disabled_sabado_estagio = '';

				$row_intervalo_sab = "";
				
				$dados_intervalo_sab_aux = DBRead('', 'tb_horarios_escala_intervalo', "WHERE id_horarios_escala = '".$dados[0]['id_horarios_escala']."' AND dia = 'sab' ");

				if($dados_intervalo_sab_aux){
					if($dados_intervalo_sab_aux[0]['tipo_intervalo'] == 1){
						$tempo_intervalo_sab = $dados_intervalo_sab_aux[0]['tempo_intervalo'];
						$checked_intervalo_variavel_sab = 'checked';
						$display_fixo_sab = "style= 'display: none'";
						$display_variavel_sab = "";
						$required_tempo_intervalo_sab = "required";
					}else{
						$intervalo_inicial_sab = $dados_intervalo_sab_aux[0]['intervalo_inicial'];
						$intervalo_final_sab = $dados_intervalo_sab_aux[0]['intervalo_final'];
						$checked_intervalo_fixo_sab = 'checked';
						$display_fixo_sab = "";
						$display_variavel_sab = "style= 'display: none'";
						$required_intervalo_inicial_sab = "required";
						$required_intervalo_final_sab = "required";
					}
				}else{
					$row_intervalo_sab = "";
		
					$required_intervalo_inicial_sab = "";
					$required_intervalo_final_sab = "";
					
					$checked_intervalo_variavel_sab = 'checked';
					$display_fixo_sab = "style= 'display: none'";
					$display_variavel_sab = "";
					$required_tempo_intervalo_sab = "required";
					
				}
				$row_intervalo_dom = "style= 'display: none'";

				$required_tempo_intervalo_dom = "";
				$required_intervalo_inicial_dom = "";
				$required_intervalo_final_dom = "";
			
			}else{
				$disabled_sabado_estagio = 'disabled';
				$inicial_sab = "";
				$final_sab = "";

				$row_intervalo_dom = "style= 'display: none'";
				$row_intervalo_sab = "style= 'display: none'";

				$required_tempo_intervalo_sab = "";
				$required_intervalo_inicial_sab = "";
				$required_intervalo_final_sab = "";

				$required_tempo_intervalo_dom = "";
				$required_intervalo_inicial_dom = "";
				$required_intervalo_final_dom = "";

			}
			$disabled_estagio = 'disabled';		
		}
	}

	if($dados[0]['data_inicial']){
		$ano_atual = $dados[0]['data_inicial'];
		$data = explode("-", $ano_atual);

		$ano_atual = $data[0];
		$mes_atual = $data[1];


	}else{
		$data = getDataHora();
		$ano_atual = $data[0].$data[1].$data[2].$data[3];
		$mes_atual = $data[5].$data[6];
		$mes_atual++;
		$mes_atual = sprintf('%02d', $mes_atual);
		if($mes_atual == 13){
			$mes_atual = 01;
			$ano_atual++;
		}
	}

	// var_dump($dados);

	if(!$carga_horaria){
		$carga_horaria = $carga_horaria_ponto;
	}
	

?>

	<div class="container-fluid">

		<div class="col-md-12">
		<?php
		echo '<div class="resultado_alerta">';
			if($dados[0]['carga_horaria'] && $dados[0]['carga_horaria'] != $carga_horaria_ponto){
				if($dados[0]['carga_horaria'] == "meio"){
					$texto_carga_horaria_escala = 'Meio Turno';
				}else if($dados[0]['carga_horaria'] == "integral"){
					$texto_carga_horaria_escala = 'Integral';
				}else if($dados[0]['carga_horaria'] == "jovem"){
					$texto_carga_horaria_escala = 'Jovem Aprendiz';
				}else if($dados[0]['carga_horaria'] == "estagio"){
					$texto_carga_horaria_escala = 'Estágio';
				}
				echo '<div class="alert alert-warning" role="alert" style="text-align: center">A carga horária da Escala está diferente do Ponto Mais (Escala: '.$texto_carga_horaria_escala.', Ponto Mais: '.$texto_carga_horaria.')!</div>';
			}
		echo '</div>';
		?>
		

			<div class="panel panel-default">
				<div class="panel-heading clearfix">
					<div class="col-md-8">
						<h3 class="panel-title text-left pull-left"><?php echo "Horários e folgas da(o) <strong>".$nome[0]['nome']."</strong>"?></h3>
					</div>
					
					<div class="col-md-4" id="col_exibe_sabado">
						<?php if($carga_horaria == 'estagio'){?>
							<div class="input-group">
								<span class="input-group-addon">
									<input <?= $checked_sabado?> type="checkbox" name="exibe_sabado" id="exibe_sabado" value="1">
								</span>
								<input type="text" class="form-control" aria-label="..." disabled value="Trabalha Sábado" style="cursor: context-menu; background-color: white;">
							</div>
						<?php } ?>
					</div>
					
				</div>
				
				<form method="post" action="/api/ajax?class=EscalaHorarios.php" id="escala" style="margin-bottom: 0;">
					<input type="hidden" name="token" value="<?php echo $request->token ?>">
					<input type="hidden" value="<?=$id_usuario?>" name="id_usuario" />
					<!-- <input type="hidden" value="<?=$carga_horaria?>" id="carga_horaria" name="carga_horaria" /> -->

				
				<div class="panel-body">

					<div class="col-md-4">
						<div class="panel panel-default" style="height: 280px !important;">
							<div class="panel-heading clearfix">
								<h3 class="panel-title text-left pull-left">*Atributos</strong></h3>
							</div>	
							<div class="panel-body" style="padding-bottom: 0;">
								<div class="row">
									<div class="col-md-12">
										<div class="form-group">
											<strong><label>*Atendente:</label></strong>
											<select name="atendente" id="atendente" class="form-control" required>
												<option value=''></option>
												<option value='1' <?php if($dados[0]['atendente'] == 1) echo 'selected'?>>Sim</option>
												<option value='0' <?php if($dados[0]['atendente'] == 0) echo 'selected'?>>Não</option>
											</select>
										</div>
									</div>

				
									<div class="col-md-12">
										<div class="form-group">
											<strong><label>*Carga Horária:</label></strong>
											<select name="carga_horaria" id="carga_horaria" class="form-control" required>
												<option value='estagio' <?php if($dados[0]['carga_horaria'] == 'estagio') echo 'selected'?>>Estágio</option>
												<option value='integral' <?php if($dados[0]['carga_horaria'] == 'integral') echo 'selected'?>>Integral</option>
												<option value='jovem' <?php if($dados[0]['carga_horaria'] == 'jovem') echo 'selected'?>>Jovem Aprendiz</option>
												<option value='meio' <?php if($dados[0]['carga_horaria'] == 'meio') echo 'selected'?>>Meio Turno</option>
											</select>
										</div>
									</div>

									<div class="col-md-12">
										<div class="form-group">
											<strong><label>*Atendimento por Chat:</label></strong>
											<select name="chat" id="chat" class="form-control" required>
												<option value='0' <?php if($dados[0]['chat'] == 0) echo 'selected'?>>Não</option>
												<option value='1' <?php if($dados[0]['chat'] == 1) echo 'selected'?>>Sim</option>
											</select>
										</div>
									</div>

								</div>

							</div>						
						</div>
					</div>

					<div class="col-md-4">
						<div class="panel panel-default" style="height: 280px !important;">
							<div class="panel-heading clearfix">
								<h3 class="panel-title text-left pull-left">*Folgas</strong></h3>
							</div>	
							<div class="panel-body" style="padding-bottom: 0;">
								<div class="row">
									<div class="col-md-12">
										<div class="form-group">
											<strong><label>*Dia de folga:</label></strong>
												<select name="folga" id="folga" class="form-control">
													<?php
													$dias = array(
														"1" => "Segunda",
														"2" => "Terça",
														"3" => "Quarta",
														"5" => "Quinta",
														"6" => "Sexta",
														"7" => "Sábado",
													);
													$dados_folga = '';
													foreach ($dias as $num => $dia) {
														$selected = $folga_seg == $dia ? "selected" : "";
														$dados_folga .="<option value='".$dia."' ".$selected.">".$dia."</option>";
													}
													$dados_folga_jovem = "<option value='Sexta' selected>Sexta</option>";
													$dados_folga_estagio = "<option value='N/D'>N/D</option>".$dados_folga;

													if($carga_horaria == 'jovem'){
														echo $dados_folga_jovem;
													}else if($carga_horaria == 'estagio'){
														echo $dados_folga_estagio;
													}else{
														echo $dados_folga;
													}
													
													?>													
												</select>

										</div>
									</div>
								</div>

								
							</div>						
						</div>
					</div>

					<div class="col-md-4">
						<div class="panel panel-default" style="height: 280px !important;">
							<div class="panel-heading clearfix">
								<h3 class="panel-title text-left pull-left">*Período de Início</strong></h3>
							</div>
							<div class="panel-body" style="padding-bottom: 0;">
								<div class="row">
									<div class="col-md-12">
										<div class="form-group">
											
											<strong><label>*Mês:</label></strong>
												<select name="mes" id="mes" class="form-control">
													<?php
													$meses = array(
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

													foreach ($meses as $num => $mes) {
														$selected = $mes_atual == $num ? "selected" : "";
														echo "<option value='".sprintf('%02d', $num)."' ".$selected.">".$mes."</option>";
													}
													?>													
												</select>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<div class="form-group">
											<strong><label>*Ano:</label></strong>
											<select name="ano" id="ano" class="form-control">
												<?php
												$anos = array(
													"2018" => "18",
													"2019" => "19",
													"2020" => "20",
													"2021" => "21",
													"2022" => "22",
													"2023" => "23",
													"2024" => "24",
													"2025" => "25",
												);

												foreach ($anos as $num => $ano) {
													$selected = $ano_atual == $num ? "selected" : "";
													echo "<option value='".$num."' ".$sel_dados_folga[$num].">".$num."</option>";
												}
												?>													
											</select>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>


					<!-- Horários -->
					<div class="col-md-4">
						<div class="panel panel-default" style="height: 422px !important;">
							<div class="panel-heading clearfix">
								<h3 class="panel-title text-left pull-left">*Segunda a Sexta</strong></h3>
							</div>
							<div class="panel-body" style="padding-bottom: 0;">
								<div class="row">
									<div class="col-md-12">
										<div class="form-group">
											<label for='escala_inicial_seg'>*Horário inicial do expediente: </label>
											<input name="escala_inicial_seg" id="escala_inicial_seg" type="text" autofocus class="form-control input-sm hour" value="<?= $inicial_seg; ?>" autocomplete="off" required>	
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<div class="form-group">
											<label for='escala_final_seg'>*Horário final do expediente: </label>
											<input name="escala_final_seg" id="escala_final_seg" type="text" autofocus class="form-control input-sm hour" value="<?= $final_seg; ?>" autocomplete="off" required>	
										</div>	
									</div>
								</div>



								<div class="row">
									<div class="col-md-12">
										<div class="form-group">
											<strong><label>*Intervalo (Segunda a Sexta):</label></strong>
											<div class="row" style="margin-bottom: 15px;">
												<div class="col-md-6">
													<div class="input-group">
														<span class="input-group-addon">
															<input type="radio" name="tipo_intervalo_seg" id="intervalo_variavel_seg" value="1" <?=$checked_intervalo_variavel_seg?>>
														</span>
														<input type="text" class="form-control mensagem" aria-label="..." disabled="" value="Variável" style="cursor: context-menu; background-color: white;">
													</div><!-- /input-group -->
												</div>

												<div class="col-md-6">
													<div class="input-group">
														<span class="input-group-addon">
															<input type="radio" name="tipo_intervalo_seg" id="intervalo_fixo_seg" value="2" <?=$checked_intervalo_fixo_seg?>>
														</span>
														<input type="text" class="form-control mensagem" aria-label="..." disabled="" value="Fixo" style="cursor: context-menu; background-color: white;">
													</div><!-- /input-group -->
												</div>
											</div>
											
											<div <?=$display_fixo_seg?> id="display_fixo_seg">

												<div class="row">
													<div class="col-md-12">
														<div class="form-group">
															<label>*Horário inicial do intervalo: </label>
															<input name="intervalo_inicial_seg" id="intervalo_inicial_seg" type="text" autofocus class="form-control input-sm hour" value="<?= $intervalo_inicial_seg ?>" autocomplete="off" <?=$required_intervalo_inicial_seg?>>	
														</div>
													</div>
												</div>
												<div class="row">
													<div class="col-md-12">
														<div class="form-group">
															<label>*Horário final do intervalo: </label>
															<input name="intervalo_final_seg" id="intervalo_final_seg" type="text" autofocus class="form-control input-sm hour" value="<?= $intervalo_final_seg ?>" autocomplete="off" <?=$required_intervalo_final_seg?>>	
														</div>	
													</div>
												</div>

											</div>

											<div class="row" <?=$display_variavel_seg?> id="display_variavel_seg">
												<div class="col-md-12">
													<div class="form-group">
														<label>*Tempo de intervalo: </label>
														<input name="tempo_intervalo_seg" id="tempo_intervalo_seg" type="text" autofocus class="form-control input-sm hour" value="<?= $tempo_intervalo_seg ?>" autocomplete="off" <?=$required_tempo_intervalo_seg?>>	
													</div>
												</div>
											</div>
										
										</div>
									</div>
								</div>



							</div>	
						</div>
					</div>

					<div class="col-md-4">
						<div class="panel panel-default" style="height: 422px !important;">
							<div class="panel-heading clearfix">
								<h3 class="panel-title text-left pull-left">*Sábado</strong></h3>
							</div>
							<div class="panel-body" style="padding-bottom: 0;">
								<div class="row">
									<div class="col-md-12">
										<div class="form-group">
											<label for='escala_inicial_sab'>*Horário inicial do expediente: </label>
											<input name="escala_inicial_sab" id="escala_inicial_sab" type="text" autofocus class="form-control input-sm hour" value="<?=$inicial_sab; ?>" autocomplete="off" required <?=$disabled_jovem?> <?=$disabled_sabado_estagio?>>	
										</div>
									</div>
								</div>
								<div class ="row">
									<div class="col-md-12">
										<div class = "form-group">
											<label for='escala_final_sab'>*Horário final do expediente: </label>
											<input name="escala_final_sab" id="escala_final_sab" type="text" autofocus class="form-control input-sm hour" value="<?=  $final_sab; ?>" autocomplete="off" required <?=$disabled_jovem?> <?=$disabled_sabado_estagio?>>	  
										</div>
									</div>
								</div>


								<div class="row" <?=$row_intervalo_sab?> id="row_intervalo_sab">
									<div class="col-md-12">
										<div class="form-group">
											<strong><label>*Intervalo (Sábado):</label></strong>
											<div class="row" style="margin-bottom: 15px;">
												<div class="col-md-6">
													<div class="input-group">
														<span class="input-group-addon">
															<input type="radio" name="tipo_intervalo_sab" id="intervalo_variavel_sab" value="1" <?=$checked_intervalo_variavel_sab?>>
														</span>
														<input type="text" class="form-control mensagem" aria-label="..." disabled="" value="Variável" style="cursor: context-menu; background-color: white;">
													</div><!-- /input-group -->
												</div>

												<div class="col-md-6">
													<div class="input-group">
														<span class="input-group-addon">
															<input type="radio" name="tipo_intervalo_sab" id="intervalo_fixo_sab" value="2" <?=$checked_intervalo_fixo_sab?>>
														</span>
														<input type="text" class="form-control mensagem" aria-label="..." disabled="" value="Fixo" style="cursor: context-menu; background-color: white;">
													</div><!-- /input-group -->
												</div>
											</div>
											
											<div <?=$display_fixo_sab?> id="display_fixo_sab">

												<div class="row">
													<div class="col-md-12">
														<div class="form-group">
															<label>*Horário inicial do intervalo: </label>
															<input name="intervalo_inicial_sab" id="intervalo_inicial_sab" type="text" autofocus class="form-control input-sm hour" value="<?= $intervalo_inicial_sab ?>" autocomplete="off" <?=$required_intervalo_inicial_sab?>>	
														</div>
													</div>
												</div>
												<div class="row">
													<div class="col-md-12">
														<div class="form-group">
															<label>*Horário final do intervalo: </label>
															<input name="intervalo_final_sab" id="intervalo_final_sab" type="text" autofocus class="form-control input-sm hour" value="<?= $intervalo_final_sab ?>" autocomplete="off" <?=$required_intervalo_final_sab?>>	
														</div>	
													</div>
												</div>

											</div>

											<div class="row" <?=$display_variavel_sab?> id="display_variavel_sab">
												<div class="col-md-12">
													<div class="form-group">
														<label>*Tempo de intervalo: </label>
														<input name="tempo_intervalo_sab" id="tempo_intervalo_sab" type="text" autofocus class="form-control input-sm hour" value="<?= $tempo_intervalo_sab ?>" autocomplete="off" <?=$required_tempo_intervalo_sab?>>	
													</div>
												</div>
											</div>
										
										</div>
									</div>
								</div>


							</div>
						</div>
					</div>

					<div class="col-md-4">
						<div class="panel panel-default" style="height: 422px !important;">
							<div class="panel-heading clearfix">
								<h3 class="panel-title text-left pull-left">*Domingo</strong></h3>
							</div>
							<div class="panel-body" style="padding-bottom: 0;">
								<div class="row">
									<div class="col-md-12">
										<div class="form-group">
											<label for='escala_inicial_dom'>*Horário inicial do expediente: </label>
											<input name="escala_inicial_dom" id="escala_inicial_dom" type="text" autofocus class="form-control input-sm hour" value="<?= $inicial_dom; ?>" autocomplete="off" required <?=$disabled_jovem?> <?=$disabled_estagio?>>	
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<div class="form-group">
											<label for='escala_final_dom'>*Horário final do expediente: </label>
											<input name="escala_final_dom" id="escala_final_dom" type="text" autofocus class="form-control input-sm hour" value="<?= $final_dom; ?>" autocomplete="off" required <?=$disabled_jovem?> <?=$disabled_estagio?>>	
										</div>
									</div>
								</div>


								<div class="row" <?=$row_intervalo_dom?> id="row_intervalo_dom">
									<div class="col-md-12">
										<div class="form-group">
											<strong><label>*Intervalo:</label></strong>
											<div class="row" style="margin-bottom: 15px;">
												<div class="col-md-6">
													<div class="input-group">
														<span class="input-group-addon">
															<input type="radio" name="tipo_intervalo_dom" id="intervalo_variavel_dom" value="1" <?=$checked_intervalo_variavel_dom?>>
														</span>
														<input type="text" class="form-control mensagem" aria-label="..." disabled="" value="Variável" style="cursor: context-menu; background-color: white;">
													</div><!-- /input-group -->
												</div>

												<div class="col-md-6">
													<div class="input-group">
														<span class="input-group-addon">
															<input type="radio" name="tipo_intervalo_dom" id="intervalo_fixo_dom" value="2" <?=$checked_intervalo_fixo_dom?>>
														</span>
														<input type="text" class="form-control mensagem" aria-label="..." disabled="" value="Fixo" style="cursor: context-menu; background-color: white;">
													</div><!-- /input-group -->
												</div>
											</div>
											
											<div <?=$display_fixo_dom?> id="display_fixo_dom">

												<div class="row">
													<div class="col-md-12">
														<div class="form-group">
															<label>*Horário inicial do intervalo: </label>
															<input name="intervalo_inicial_dom" id="intervalo_inicial_dom" type="text" autofocus class="form-control input-sm hour" value="<?= $intervalo_inicial_dom ?>" autocomplete="off" <?=$required_intervalo_inicial_dom?>>	
														</div>
													</div>
												</div>
												<div class="row">
													<div class="col-md-12">
														<div class="form-group">
															<label>*Horário final do intervalo: </label>
															<input name="intervalo_final_dom" id="intervalo_final_dom" type="text" autofocus class="form-control input-sm hour" value="<?= $intervalo_final_dom ?>" autocomplete="off" <?=$required_intervalo_final_dom?>>	
														</div>	
													</div>
												</div>

											</div>

											<div class="row" <?=$display_variavel_dom?> id="display_variavel_dom">
												<div class="col-md-12">
													<div class="form-group">
														<label>*Tempo de intervalo: </label>
														<input name="tempo_intervalo_dom" id="tempo_intervalo_dom" type="text" autofocus class="form-control input-sm hour" value="<?= $tempo_intervalo_dom ?>" autocomplete="off" <?=$required_tempo_intervalo_dom?>>	
													</div>
												</div>
											</div>
										
										</div>
									</div>
								</div>



							</div>
						</div>
					</div>

					<?php 
					if($carga_horaria == 'jovem' || $carga_horaria == 'estagio'){
						$col_domingo_folga = "style = 'display: none;'";
					} ?>
					<div class="col-md-12" <?=$col_domingo_folga?> id="col_domingo_folga">
						<div class="panel panel-default">
							<div class="panel-heading clearfix">
								<h3 class="panel-title text-left pull-left">Domingo(s) de Folga(s)</strong></h3>
							</div>
							<div class="panel-body">
								<div class='table-responsive'>
									<table class='table table-bordered' style='font-size: 14px;'>
										<thead>
											<tr>
												<th class='col-md-11'>Dia</th>
												<th class='col-md-1'>Ação</th>
											</tr>
										</thead>
										<tbody class="domingo_de_folga">   
										</tbody>
										<tfoot>
											<?php 
											
												$dados_dom = DBRead('', 'tb_folgas_dom', "WHERE id_horarios_escala = '".$dados[0]['id_horarios_escala']."'");
												$cont = 0;
											
												if($dados_dom){
													foreach ($dados_dom as $folga_domingo) {
														if($cont < 1){
															$cont++;
															?>
															<tr>
																<td> <input class="campos-agendar form-control date calendar input-sm" name="folga_domingo[]" value="<?= converteData($folga_domingo['dia']) ?>" type="text" autocomplete="off"/></td>
																<td><button type="button" class='center-block btn btn-warning btn-sm' id='adiciona-usuario' role='button'><i class='fa fa-plus' aria-hidden='true'></i></button></td>
															</tr>
														<?php
														}else{

															echo "<tr class='linha_linha_domingo'><td><input required class='campos-agendar form-control date calendar input-sm' name='folga_domingo[]' value='".converteData($folga_domingo['dia'])."' type='text' autocomplete='off' /></td><td><button class='center-block btn btn-danger btn-sm removeLinha' role='button'><i class='fa fa-trash-o' aria-hidden='true'></i></button></td></tr>";

														}
													}
													?>
													
											<?php        
												}else{
													?>
														<tr>
															<td> <input class="campos-agendar form-control date calendar input-sm" name="folga_domingo[]" value="<?= converteData($folga_domingo['dia']) ?>" type="text" autocomplete="off"/></td>
															<td><button type="button" class='center-block btn btn-warning btn-sm' id='adiciona-usuario' role='button'><i class='fa fa-plus' aria-hidden='true'></i></button></td>
														</tr>
											<?php
												}

											?>
										</tfoot>
									</table>
								</div>
							</div>
						</div>
					</div>	
					
					<?php
						if($carga_horaria == 'jovem' || $carga_horaria == 'estagio'){
							$col_especial = "style = 'display: none;'";
						} 
					?>

						<?php
						$dados_especiais = DBRead('', 'tb_horarios_especiais', "WHERE id_horarios_escala = '".$dados[0]['id_horarios_escala']."'");
							if($dados_especiais){
								$collapse_icon = 'minus';
								$var_collapse  = 'collapse in';
							}else{
								$collapse_icon = 'plus';
								$var_collapse  = 'collapse';
							}
						?>
						<div class="col-md-12" <?=$col_especial?> id="col_especial">
							<div class="panel panel-default">
								<div class="panel-heading clearfix" >
									<h3 class="panel-title text-left pull-left">Horários Especias (Feriados e etc)</strong></h3>
									<div class="panel-title text-right pull-right"><button data-toggle="collapse" aria-expanded="false" data-target="#accordionRelatorio" class="btn btn-xs btn-info " type="button" title="Visualizar filtros"><i id="i_collapse" class="fa fa-<?=$collapse_icon?>"></i></button></div>
							
								</div>
								<div class="panel-body <?=$var_collapse?>" id="accordionRelatorio">
									<div class='table-responsive'>
										
								<?php 
								
									$dados_especiais = DBRead('', 'tb_horarios_especiais', "WHERE id_horarios_escala = '".$dados[0]['id_horarios_escala']."'");
									$cont = 0;
									if($dados_especiais){
										?>
								
										<?php
										$cont = 1;
										foreach ($dados_especiais as $especial) {
											if($cont == 1){
												echo "<table class='table table-bordered' style='font-size: 14px;'>";
											}else{
												echo "<table class='table table-bordered' style='font-size: 14px;'>";
											}
											$cont++;
											?>
											
												<thead>
													<tr>
														<th class='col-md-2'>Dia:</th>
														<th class='col-md-3'>Horário inicial do expediente:</th>
														<th class='col-md-3'>Horário final do expediente:</th>
														<th class='col-md-2'>Tempo de Intervalo</th>
														<th class='col-md-2'>Ação</th>
													</tr>
												</thead>
												<tbody class = 'datas_especiais'>  
												</tbody>
												<tfoot>
													<tr class='linha_linha_especial'>
														<td>
															<input class='campos-agendar form-control date calendar input-sm especial' name='especial[]' value="<?= converteData($especial['dia']) ?>" type='text' autocomplete='off' />
														</td>
													
														<td>
															<input name="inicial_especial[]" type="text" autofocus class="form-control input-sm hour inicial-especial" value="<?= $especial['inicial_especial']; ?>" autocomplete="off">
														</td>

														<td>
															<input name="final_especial[]" type="text" autofocus class="form-control input-sm hour final-especial" value="<?= $especial['final_especial']; ?>" autocomplete="off">
														</td>

														<td>
															<input name="tempo_intervalo_especial[]" type="text" autofocus class="form-control input-sm hour aqui" value="<?= $especial['tempo_intervalo']; ?>" autocomplete="off">
														</td>

														<td>
															<button class='center-block btn btn-danger btn-sm removeLinhaEspecial' role='button'><i class='fa fa-trash-o' aria-hidden='true'></i>
															</button>
														</td>
													</tr>
													<?php
													}
													?>
														</tfoot>
													</table>
													<table class='table table-bordered datas_especiais_table' style='font-size: 14px;'>
														<thead>
															<tr>
																<th class='col-md-2'></th>
																<th class='col-md-3'></th>
																<th class='col-md-3'></th>
																<th class='col-md-2'></th>
																<th class='col-md-2'>Ação</th>
															</tr>
														</thead>
														<tbody class = 'datas_especiais'>  
														</tbody>
														<tr class='linha_linha_especial'>
															<td></td>
															<td></td>
															<td></td>
															<td></td>
															<td>
																<button type="button" class='center-block btn btn-warning btn-sm' id='adiciona-data-especial' role='button'><i class='fa fa-plus' aria-hidden='true'></i>
																</button>
															</td>
														</tr>
														</tfoot>
													</table>
												<?php
												}else{
													?>
													<table class='table table-bordered datas_especiais_table' style='font-size: 14px;'>
														<thead>
															<tr>
																<th class='col-md-2'></th>
																<th class='col-md-3'></th>
																<th class='col-md-3'></th>
																<th class='col-md-2'></th>
																<th class='col-md-2'>Ação</th>
															</tr>
														</thead>
														<tbody class = 'datas_especiais'>  
														</tbody>
														<tfoot>
															<tr class='linha_linha_especial'>
																<td></td>
																<td></td>
																<td></td>
																<td></td>
																<td>
																	<button type="button" class='center-block btn btn-warning btn-sm' id='adiciona-data-especial' role='button'><i class='fa fa-plus' aria-hidden='true'></i>
																	</button>
																</td>
															</tr>
														</tfoot>
													</table>
												<?php
												}
												?>
											
									</div>
								</div>
							</div>
						</div>
				</div>

				<div id="teste"></div>
					
				<div class="panel-footer">
					<div class="row">
						<div class="col-md-12" style="text-align: center">
							<input type="hidden" id="inserir" value="inserir" name="inserir" />
								<?php
									echo "<button class='btn btn-primary' name='salvar' id='ok' type='submit'><i class='fa fa-floppy-o'></i> Salvar</button>";
								?>
						</div>
					</div>
				</div>
				</form>
			</div>
				
		</div>
	</div>

	<?php

}else{
echo "<div class=\"container-fluid text-center\"><div class=\"alert alert-danger\"><i class=\"fa fa-ban\" aria-hidden=\"true\"></i> Ops! Você não tem permissão de acesso!</div></div>";
}
?>

<script>

	$("#carga_horaria").on('change', function(){
		
		//Insere o alerta
		$(".resultado_alerta").html('');
		var carga_horaria_ponto = '<?=$carga_horaria_ponto?>';
		var texto_carga_horaria = '<?=$texto_carga_horaria?>';

		if(carga_horaria_ponto && carga_horaria_ponto != $(this).val()){
			$(".resultado_alerta").html('<div class="alert alert-warning" role="alert" style="text-align: center">A carga horária da Escala está diferente do Ponto Mais (Escala: '+$("#carga_horaria option:selected").text()+', Ponto Mais: '+texto_carga_horaria+')!</div>');
		}

		//_______________________________________________________________

		//disabled e etc
		$("#col_exibe_sabado").html('');

		if($(this).val() == 'jovem' || $(this).val() == 'estagio'){
			
			$("#escala_inicial_dom").val('');
			$("#escala_final_dom").val('');

			$("#escala_inicial_sab").val('');
			$("#escala_final_sab").val('');

			$("#escala_inicial_sab").attr('disabled', true);
			$("#escala_final_sab").attr('disabled', true);

			$("#escala_inicial_dom").attr('disabled', true);
			$("#escala_final_dom").attr('disabled', true);

			$("#row_intervalo_dom").hide();
			$("#row_intervalo_sab").hide();

			$("#tempo_intervalo_sab").attr('required', false);
			$("#intervalo_inicial_sab").attr('required', false);
			$("#intervalo_final_sab").attr('required', false);
			
			$("#tempo_intervalo_dom").attr('required', false);
			$("#intervalo_inicial_dom").attr('required', false);
			$("#intervalo_final_dom").attr('required', false);

			if($(this).val() == 'estagio'){
				$("#col_exibe_sabado").html('<div class="input-group"><span class="input-group-addon"><input type="checkbox" name="exibe_sabado" id="exibe_sabado" value="1"></span><input type="text" class="form-control" aria-label="..." disabled value="Trabalha Sábado" style="cursor: context-menu; background-color: white;"></div>');
				$("#folga").html("<?=$dados_folga_estagio?>");
			}else{
				$("#folga").html("<?=$dados_folga_jovem?>");
			}

			$("#col_domingo_folga").hide();
			$("#col_especial").hide();

			$('.removeLinha').each(function(){
				$(this).parent().parent().remove();
			});	

			$('.removeLinhaEspecial').each(function(){
				$(this).parent().parent().parent().parent().remove();
			});
			
			$('.campos-agendar').each(function(){
				$(this).val('');
			});

		}else{
			$("#col_domingo_folga").show();
			$("#col_especial").show();

			$("#escala_inicial_sab").attr('disabled', false);
			$("#escala_final_sab").attr('disabled', false);

			$("#escala_inicial_dom").attr('disabled', false);
			$("#escala_final_dom").attr('disabled', false);

			$("#row_intervalo_dom").show();
			$("#row_intervalo_sab").show();

			$("#tempo_intervalo_sab").attr('required', true);
			$("#intervalo_inicial_sab").attr('required', false);
			$("#intervalo_final_sab").attr('required', false);
			
			$("#tempo_intervalo_dom").attr('required', true);
			$("#intervalo_inicial_dom").attr('required', false);
			$("#intervalo_final_dom").attr('required', false);

			$("#folga").html("<?=$dados_folga?>");
		}



	});

	$(document).on('click', "input[name = 'tipo_intervalo_seg']", function () {

		if($(this).val() == 1){
			$("#display_variavel_seg").show();
			$("#display_fixo_seg").hide();

			$("#tempo_intervalo_seg").attr('required', true);
			$("#intervalo_inicial_seg").attr('required', false);
			$("#intervalo_final_seg").attr('required', false);
		}else{
			$("#display_variavel_seg").hide();
			$("#display_fixo_seg").show();

			$("#tempo_intervalo_seg").attr('required', false);
			$("#intervalo_inicial_seg").attr('required', true);
			$("#intervalo_final_seg").attr('required', true);
		}
	});

	$(document).on('click', "input[name = 'tipo_intervalo_sab']", function () {

		if($(this).val() == 1){
			$("#display_variavel_sab").show();
			$("#display_fixo_sab").hide();

			$("#tempo_intervalo_sab").attr('required', true);
			$("#intervalo_inicial_sab").attr('required', false);
			$("#intervalo_final_sab").attr('required', false);
		}else{
			$("#display_variavel_sab").hide();
			$("#display_fixo_sab").show();

			$("#tempo_intervalo_sab").attr('required', false);
			$("#intervalo_inicial_sab").attr('required', true);
			$("#intervalo_final_sab").attr('required', true);
		}
	});

	$(document).on('click', "input[name = 'tipo_intervalo_dom']", function () {

		if($(this).val() == 1){
			$("#display_variavel_dom").show();
			$("#display_fixo_dom").hide();

			$("#tempo_intervalo_dom").attr('required', true);
			$("#intervalo_inicial_dom").attr('required', false);
			$("#intervalo_final_dom").attr('required', false);
		}else{
			$("#display_variavel_dom").hide();
			$("#display_fixo_dom").show();

			$("#tempo_intervalo_dom").attr('required', false);
			$("#intervalo_inicial_dom").attr('required', true);
			$("#intervalo_final_dom").attr('required', true);
		}
	});

	$(document).on('click', '#exibe_sabado', function () {


		var valor_escala_inicial_sab = '<?=$dados[0]['inicial_sab']?>';
		var valor_escala_final_sab = '<?=$dados[0]['final_sab']?>';



		var valor_inicial_sab = '';
		var valor_final_sab = '';
		var intervalo_inicial_sab = '';
		var intervalo_final_sab = '';
		var tempo_intervalo_sab = '';
		
		var dados_intervalo_sab_aux = '<?=$dados_intervalo_sab_aux[0]['tipo_intervalo']?>';
		
		if(valor_escala_inicial_sab && valor_escala_inicial_sab != 0 && valor_escala_inicial_sab != ''){

			if(dados_intervalo_sab_aux){
				if(dados_intervalo_sab_aux == 1){
					tempo_intervalo_sab = '<?=$dados_intervalo_sab_aux[0]['tempo_intervalo']?>';
				}else{
					intervalo_inicial_sab = '<?=$dados_intervalo_sab_aux[0]['intervalo_inicial']?>';
					intervalo_final_sab = '<?=$dados_intervalo_sab_aux[0]['intervalo_final']?>';
				}
			}	

			valor_inicial_sab = valor_escala_inicial_sab;
			valor_final_sab = valor_escala_final_sab;
		}else{
			valor_inicial_sab = '';
			valor_final_sab = '';
		}

		if($(this).is(':checked')){
			// $('#folga').html("<?=$dados_folga?>");

			if(dados_intervalo_sab_aux){
				if(dados_intervalo_sab_aux == 1){
					$('#tempo_intervalo_sab').val(tempo_intervalo_sab);

					$('#intervalo_inicial_sab').attr('required', false);
					$('#intervalo_final_sab').attr('required', false);

					$('#tempo_intervalo_sab').attr('required', true);

				}else{
					$('#intervalo_inicial_sab').val(intervalo_inicial_sab);
					$('#intervalo_final_sab').val(intervalo_final_sab);

					$('#intervalo_inicial_sab').attr('required', true);
					$('#intervalo_final_sab').attr('required', true);

					$('#tempo_intervalo_sab').attr('required', false);
				}
				
			}else{
				$('#tempo_intervalo_sab').attr('required', true);
			}
			
			$('#row_intervalo_sab').show();

			$('#escala_inicial_sab').attr('disabled', false);
			$('#escala_final_sab').attr('disabled', false);

			$('#escala_inicial_sab').val(valor_escala_inicial_sab);
			$('#escala_final_sab').val(valor_escala_final_sab);

		}else{
			
			// $('#folga').html("<option value='N/D' selected>N/D</option>");

			$('#escala_inicial_sab').attr('disabled', true);
			$('#escala_final_sab').attr('disabled', true);
			
			$('#escala_inicial_sab').val('');
			$('#escala_final_sab').val('');

			$('#tempo_intervalo_sab').val('');
			$('#intervalo_inicial_sab').val('');
			$('#intervalo_final_sab').val('');
			$('#intervalo_inicial_sab').attr('required', false);
			$('#intervalo_final_sab').attr('required', false);
			$('#tempo_intervalo_sab').attr('required', false);

			$('#row_intervalo_sab').hide();

		}
		
	});

	$("#adiciona-usuario").on('click', function(){
		$("tbody.domingo_de_folga").append("<tr class='linha_linha_domingo'><td><input required class='campos-agendar form-control date calendar input-sm' name='folga_domingo[]' value='' type='text' autocomplete='off' /></td><td><button class='center-block btn btn-danger btn-sm removeLinha' role='button'><i class='fa fa-trash-o' aria-hidden='true'></i></button></td></tr>");
		$(".usuario").focus();
		configuraDatepicker();
	});

	$(document).on('click', '.removeLinha', function(){
		if(confirm('Deseja excluir esta folga de domingo?')) {
			$(this).parent().parent().remove();
		}
		return false;
	});		

	// $(document).on('click', '.tipo_intervalo_especial', function(){
		// 	if($(this).val() == 1){
		// 		$(this).parent().parent().parent().parent().parent().parent().parent().parent().find('.teste').text('Tempo de Intervalo');
				

		// 		// <input name="tempo_intervalo_especial[]" type="text" autofocus class="form-control input-sm hour" value="<?= $especial['final_especial']; ?>" autocomplete="off">
		// 	}else{
		// 		$(this).parent().parent().parent().parent().parent().parent().parent().parent().find('.teste').text('Horários do Intervalo');
		// 	}
		// 	alert($(this).parent().parent().parent().parent().parent().parent().find('.testinho').find('.aqui').html());
	// });
												
	$("#adiciona-data-especial").on('click', function(){
		// alert('oi');
		$(".datas_especiais_table").before("<table class='table table-bordered' style='font-size: 14px;' class = 'datas_especiais_table'><thead><tr><th class='col-md-2'>Dia:</th><th class='col-md-3'>Horário inicial do expediente:</th><th class='col-md-3'>Horário final do expediente:</th><th class='col-md-2'>Tempo de Intervalo</th><th class='col-md-2'>Ação</th></tr></thead><tbody class = 'datas_especiais'>  <tr class='linha_linha_especial'><td><input class='campos-agendar form-control date calendar input-sm especial' name='especial[]' value='' type='text' autocomplete='off' /></td><td><input name='inicial_especial[]' type='text' autofocus class='form-control input-sm hour inicial-especial' value='<?= $escala_especial ?>' autocomplete='off'></td><td><input name='final_especial[]' type='text' autofocus class='form-control input-sm hour final-especial' value='<?= $escala_especial; ?>' autocomplete='off'></td><td><input name='tempo_intervalo_especial[]' type='text' autofocus class='form-control input-sm hour' value='00:20' autocomplete='off' required></td><td><button class='center-block btn btn-danger btn-sm removeLinhaEspecial' role='button'><i class='fa fa-trash-o' aria-hidden='true'></i></button></td></tr></tfoot></table>");
		configuraDatepicker();
	});

	$(document).on('click', '.removeLinhaEspecial', function(){
		if(confirm('Deseja excluir esta data especial?')) {
			$(this).parent().parent().parent().parent().remove();
		}
		return false;
	});

	$('#accordionRelatorio').on('hidden.bs.collapse', function () {
		$("#i_collapse").removeClass("fa fa-minus").addClass("fa fa-plus");
	});

	$('#accordionRelatorio').on('shown.bs.collapse', function(){
		$("#i_collapse").removeClass("fa fa-plus").addClass("fa fa-minus");

	});    			
	
	$(document).on('click', '#ok', function(){

		var cont = '0';
		var cont1 = '0';

		$('.especial').each(function(){
			var folga = $("#folga").val();
			var semana = ["Domingo", "Segunda", "Terça", "Quarta", "Quinta", "Sexta", "Sábado"];
			var data = this.value;
			var arr = data.split("/").reverse();
			var teste = new Date(arr[0], arr[1] - 1, arr[2]);
			var dia = teste.getDay();
				
			if(semana[dia] == folga){
				cont = '2';
			}
		});	
		

		$('.inicial-especial').each(function(){
			var inicial = this.value;						
			var final = $(this).parent().parent().find('.final-especial').val();
			var especial = $(this).parent().parent().find('.especial').val();

			if(especial && inicial && !final){
				cont1 = '1';	
			}else if(especial && final && !inicial){
				cont1 = '1';	
			}else if(inicial && final && !especial){
				cont1 = '1';	
			}
		});	

		if(cont1 == '1'){
			alert("Preencha todos os campos do horário especial!");
				return false;
		}	

		if(cont == '2'){
			alert("A data especial é no mesmo dia da folga do atendente!");
				return false;
		}	 

		if($("#intervalo_fixo_seg").is(':checked')){
			var inicial_atendente = $("#escala_inicial_seg").val();
			var inicial_hora_atendente = inicial_atendente.split(":")[0];
			var inicial_minutos_atendente = inicial_atendente.split(":")[1];
				
			var final_atendente = $("#escala_final_seg").val();
			var final_hora_atendente = final_atendente.split(":")[0];
			var final_minutos_atendente = final_atendente.split(":")[1];

			var hora_agora_inicial = $("#intervalo_inicial_seg").val();
			var hora_inicial = hora_agora_inicial.split(":")[0];
			var minutos_inicial = hora_agora_inicial.split(":")[1];

			var hora_agora_final = $("#intervalo_final_seg").val();
			var hora_final = hora_agora_final.split(":")[0];
			var minutos_final = hora_agora_final.split(":")[1];
			if(hora_agora_inicial <= '23:59' && hora_agora_final <= '23:59'){
				if(inicial_atendente <= final_atendente){					
					if(final_atendente > hora_agora_final && inicial_atendente < hora_agora_inicial){
						inicial_hora_atendente = parseInt(inicial_hora_atendente)+1;
						if(inicial_hora_atendente <10){
							inicial_hora_atendente = '0'+inicial_hora_atendente;
						}
						inicial_atendente = inicial_hora_atendente+':'+inicial_minutos_atendente;
						
						final_hora_atendente = final_hora_atendente-1;
						if(final_hora_atendente <10){
							final_hora_atendente = '0'+final_hora_atendente;
						}
						final_atendente = final_hora_atendente+':'+final_minutos_atendente;
						if(final_atendente < hora_agora_inicial || inicial_atendente > hora_agora_inicial){
							if(final_atendente < hora_agora_inicial){
								alert('O atendente não pode sair para o intervalo com menos de 1 hora antes do horário final da sua escala (Seg A Sex)!');
								return false;
							}else{
								alert('O atendente não pode sair para o intervalo com menos de 1 hora antes do horário inicial da sua escala (Seg A Sex)!');
								return false;
							}
						}

						if(final_atendente < hora_agora_final || inicial_atendente > hora_agora_final){
							if(final_atendente < hora_agora_final){
								alert('O atendente não pode voltar do intervalo com menos de 1 hora antes do horário final da sua escala (Seg A Sex)!');
								return false;
							}else{
								alert('O atendente não pode voltar do intervalo com menos de 1 hora antes do horário inicial da sua escala (Seg A Sex)!');
								return false;
							}
						}
					}else{
						alert('O intervalo ultrapassa o horário da escala!');
					}
				}else{
					final_hora_atendente = parseInt(final_hora_atendente)+24;
					final_atendente = final_hora_atendente+':'+final_minutos_atendente;
					if(hora_agora_inicial > hora_agora_final){
						hora_final = parseInt(hora_final)+24;
						hora_agora_final = hora_final+':'+minutos_final;
					}else if(hora_agora_inicial <= inicial_atendente && hora_agora_final <= inicial_atendente){
						hora_inicial = parseInt(hora_inicial)+24;
						hora_agora_inicial = hora_inicial+':'+minutos_inicial;
						hora_final = parseInt(hora_final)+24;
						hora_agora_final = hora_final+':'+minutos_final;
					}
					if(final_atendente > hora_agora_final && inicial_atendente < hora_agora_inicial){
						inicial_hora_atendente = parseInt(inicial_hora_atendente)+1;
						if(inicial_hora_atendente <10){
							inicial_hora_atendente = '0'+inicial_hora_atendente;
						}
						inicial_atendente = inicial_hora_atendente+':'+inicial_minutos_atendente;

						final_hora_atendente = parseInt(final_hora_atendente)-1;
						final_atendente = final_hora_atendente+':'+final_minutos_atendente;

						if(final_atendente < hora_agora_inicial || inicial_atendente > hora_agora_inicial){
							if(final_atendente < hora_agora_inicial){
								alert('O atendente não pode sair para o intervalo com menos de 1 hora antes do horário final da sua escala! (Seg A Sex)');
								return false;
							}else{
								alert('O atendente não pode sair para o intervalo com menos de 1 hora antes do horário inicial da sua escala! (Seg A Sex)');
								return false;
							}
						}

						if(final_atendente < hora_agora_final || inicial_atendente > hora_agora_final){
							if(final_atendente < hora_agora_final){
								alert('O atendente não pode voltar do intervalo com menos de 1 hora antes do horário final da sua escala! (Seg A Sex)');
								return false;
							}else{
								alert('O atendente não pode voltar do intervalo com menos de 1 hora antes do horário inicial da sua escala! (Seg A Sex)');
								return false;
							}
						}
					}else{
						alert('O intervalo ultrapassa o horário da escala! (Seg A Sex)');
					}
				}
			}else{
				alert('O horário do intervalo deve ser menor que 23:59! (Seg A Sex)');
			}
		}

		if($("#intervalo_fixo_sab").is(':checked')){
			if($('#carga_horaria').val() == 'jovem' || $('#carga_horaria').val() == 'estagio'){
				if($('#carga_horaria').val() == 'estagio' && $('#exibe_sabado').is(':checked')){
					if($("#intervalo_fixo_sab").is(':checked')){
						var inicial_atendente = $("#escala_inicial_sab").val();
						var inicial_hora_atendente = inicial_atendente.split(":")[0];
						var inicial_minutos_atendente = inicial_atendente.split(":")[1];
							
						var final_atendente = $("#escala_final_sab").val();
						var final_hora_atendente = final_atendente.split(":")[0];
						var final_minutos_atendente = final_atendente.split(":")[1];

						var hora_agora_inicial = $("#intervalo_inicial_sab").val();
						var hora_inicial = hora_agora_inicial.split(":")[0];
						var minutos_inicial = hora_agora_inicial.split(":")[1];

						var hora_agora_final = $("#intervalo_final_sab").val();
						var hora_final = hora_agora_final.split(":")[0];
						var minutos_final = hora_agora_final.split(":")[1];
						if(hora_agora_inicial <= '23:59' && hora_agora_final <= '23:59'){
							if(inicial_atendente <= final_atendente){					
								if(final_atendente > hora_agora_final && inicial_atendente < hora_agora_inicial){
									inicial_hora_atendente = parseInt(inicial_hora_atendente)+1;
									if(inicial_hora_atendente <10){
										inicial_hora_atendente = '0'+inicial_hora_atendente;
									}
									inicial_atendente = inicial_hora_atendente+':'+inicial_minutos_atendente;
									
									final_hora_atendente = final_hora_atendente-1;
									if(final_hora_atendente <10){
										final_hora_atendente = '0'+final_hora_atendente;
									}
									final_atendente = final_hora_atendente+':'+final_minutos_atendente;
									if(final_atendente < hora_agora_inicial || inicial_atendente > hora_agora_inicial){
										if(final_atendente < hora_agora_inicial){
											alert('O atendente não pode sair para o intervalo com menos de 1 hora antes do horário final da sua escala (Sab)!');
											return false;
										}else{
											alert('O atendente não pode sair para o intervalo com menos de 1 hora antes do horário inicial da sua escala (Sab)!');
											return false;
										}
									}

									if(final_atendente < hora_agora_final || inicial_atendente > hora_agora_final){
										if(final_atendente < hora_agora_final){
											alert('O atendente não pode voltar do intervalo com menos de 1 hora antes do horário final da sua escala (Sab)!');
											return false;
										}else{
											alert('O atendente não pode voltar do intervalo com menos de 1 hora antes do horário inicial da sua escala (Sab)!');
											return false;
										}
									}
								}else{
									alert('O intervalo ultrapassa o horário da escala!');
									return false;
								}
							}else{
								final_hora_atendente = parseInt(final_hora_atendente)+24;
								final_atendente = final_hora_atendente+':'+final_minutos_atendente;
								if(hora_agora_inicial > hora_agora_final){
									hora_final = parseInt(hora_final)+24;
									hora_agora_final = hora_final+':'+minutos_final;
								}else if(hora_agora_inicial <= inicial_atendente && hora_agora_final <= inicial_atendente){
									hora_inicial = parseInt(hora_inicial)+24;
									hora_agora_inicial = hora_inicial+':'+minutos_inicial;
									hora_final = parseInt(hora_final)+24;
									hora_agora_final = hora_final+':'+minutos_final;
								}
								if(final_atendente > hora_agora_final && inicial_atendente < hora_agora_inicial){
									inicial_hora_atendente = parseInt(inicial_hora_atendente)+1;
									if(inicial_hora_atendente <10){
										inicial_hora_atendente = '0'+inicial_hora_atendente;
									}
									inicial_atendente = inicial_hora_atendente+':'+inicial_minutos_atendente;

									final_hora_atendente = parseInt(final_hora_atendente)-1;
									final_atendente = final_hora_atendente+':'+final_minutos_atendente;

									if(final_atendente < hora_agora_inicial || inicial_atendente > hora_agora_inicial){
										if(final_atendente < hora_agora_inicial){
											alert('O atendente não pode sair para o intervalo com menos de 1 hora antes do horário final da sua escala! (Sab)');
											return false;
										}else{
											alert('O atendente não pode sair para o intervalo com menos de 1 hora antes do horário inicial da sua escala! (Sab)');
											return false;
										}
									}

									if(final_atendente < hora_agora_final || inicial_atendente > hora_agora_final){
										if(final_atendente < hora_agora_final){
											alert('O atendente não pode voltar do intervalo com menos de 1 hora antes do horário final da sua escala! (Sab)');
											return false;
										}else{
											alert('O atendente não pode voltar do intervalo com menos de 1 hora antes do horário inicial da sua escala! (Sab)');
											return false;
										}
									}
								}else{
									alert('O intervalo ultrapassa o horário da escala! (Sab)');
									return false;
								}
							}
						}else{
							alert('O horário do intervalo deve ser menor que 23:59! (Sab)');
							return false;
						}
					}
				}			
			}else{
				var inicial_atendente = $("#escala_inicial_sab").val();
				var inicial_hora_atendente = inicial_atendente.split(":")[0];
				var inicial_minutos_atendente = inicial_atendente.split(":")[1];
					
				var final_atendente = $("#escala_final_sab").val();
				var final_hora_atendente = final_atendente.split(":")[0];
				var final_minutos_atendente = final_atendente.split(":")[1];

				var hora_agora_inicial = $("#intervalo_inicial_sab").val();
				var hora_inicial = hora_agora_inicial.split(":")[0];
				var minutos_inicial = hora_agora_inicial.split(":")[1];

				var hora_agora_final = $("#intervalo_final_sab").val();
				var hora_final = hora_agora_final.split(":")[0];
				var minutos_final = hora_agora_final.split(":")[1];
				if(hora_agora_inicial <= '23:59' && hora_agora_final <= '23:59'){
					if(inicial_atendente <= final_atendente){					
						if(final_atendente > hora_agora_final && inicial_atendente < hora_agora_inicial){
							inicial_hora_atendente = parseInt(inicial_hora_atendente)+1;
							if(inicial_hora_atendente <10){
								inicial_hora_atendente = '0'+inicial_hora_atendente;
							}
							inicial_atendente = inicial_hora_atendente+':'+inicial_minutos_atendente;
							
							final_hora_atendente = final_hora_atendente-1;
							if(final_hora_atendente <10){
								final_hora_atendente = '0'+final_hora_atendente;
							}
							final_atendente = final_hora_atendente+':'+final_minutos_atendente;
							if(final_atendente < hora_agora_inicial || inicial_atendente > hora_agora_inicial){
								if(final_atendente < hora_agora_inicial){
									alert('O atendente não pode sair para o intervalo com menos de 1 hora antes do horário final da sua escala (Sab)!');
									return false;
								}else{
									alert('O atendente não pode sair para o intervalo com menos de 1 hora antes do horário inicial da sua escala (Sab)!');
									return false;
								}
							}

							if(final_atendente < hora_agora_final || inicial_atendente > hora_agora_final){
								if(final_atendente < hora_agora_final){
									alert('O atendente não pode voltar do intervalo com menos de 1 hora antes do horário final da sua escala (Sab)!');
									return false;
								}else{
									alert('O atendente não pode voltar do intervalo com menos de 1 hora antes do horário inicial da sua escala (Sab)!');
									return false;
								}
							}
						}else{
							alert('O intervalo ultrapassa o horário da escala!');
							return false;
						}
					}else{
						final_hora_atendente = parseInt(final_hora_atendente)+24;
						final_atendente = final_hora_atendente+':'+final_minutos_atendente;
						if(hora_agora_inicial > hora_agora_final){
							hora_final = parseInt(hora_final)+24;
							hora_agora_final = hora_final+':'+minutos_final;
						}else if(hora_agora_inicial <= inicial_atendente && hora_agora_final <= inicial_atendente){
							hora_inicial = parseInt(hora_inicial)+24;
							hora_agora_inicial = hora_inicial+':'+minutos_inicial;
							hora_final = parseInt(hora_final)+24;
							hora_agora_final = hora_final+':'+minutos_final;
						}
						if(final_atendente > hora_agora_final && inicial_atendente < hora_agora_inicial){
							inicial_hora_atendente = parseInt(inicial_hora_atendente)+1;
							if(inicial_hora_atendente <10){
								inicial_hora_atendente = '0'+inicial_hora_atendente;
							}
							inicial_atendente = inicial_hora_atendente+':'+inicial_minutos_atendente;

							final_hora_atendente = parseInt(final_hora_atendente)-1;
							final_atendente = final_hora_atendente+':'+final_minutos_atendente;

							if(final_atendente < hora_agora_inicial || inicial_atendente > hora_agora_inicial){
								if(final_atendente < hora_agora_inicial){
									alert('O atendente não pode sair para o intervalo com menos de 1 hora antes do horário final da sua escala! (Sab)');
									return false;
								}else{
									alert('O atendente não pode sair para o intervalo com menos de 1 hora antes do horário inicial da sua escala! (Sab)');
									return false;
								}
							}

							if(final_atendente < hora_agora_final || inicial_atendente > hora_agora_final){
								if(final_atendente < hora_agora_final){
									alert('O atendente não pode voltar do intervalo com menos de 1 hora antes do horário final da sua escala! (Sab)');
									return false;
								}else{
									alert('O atendente não pode voltar do intervalo com menos de 1 hora antes do horário inicial da sua escala! (Sab)');
									return false;
								}
							}
						}else{
							alert('O intervalo ultrapassa o horário da escala! (Sab)');
							return false;
						}
					}
				}else{
					alert('O horário do intervalo deve ser menor que 23:59! (Sab)');
					return false;
				}
			}
			
		}

		if($("#intervalo_fixo_dom").is(':checked')){
			if($('#carga_horaria').val() != 'jovem' && $('#carga_horaria').val() != 'estagio'){
				var inicial_atendente = $("#escala_inicial_dom").val();
				var inicial_hora_atendente = inicial_atendente.split(":")[0];
				var inicial_minutos_atendente = inicial_atendente.split(":")[1];
					
				var final_atendente = $("#escala_final_dom").val();
				var final_hora_atendente = final_atendente.split(":")[0];
				var final_minutos_atendente = final_atendente.split(":")[1];

				var hora_agora_inicial = $("#intervalo_inicial_dom").val();
				var hora_inicial = hora_agora_inicial.split(":")[0];
				var minutos_inicial = hora_agora_inicial.split(":")[1];

				var hora_agora_final = $("#intervalo_final_dom").val();
				var hora_final = hora_agora_final.split(":")[0];
				var minutos_final = hora_agora_final.split(":")[1];
				if(hora_agora_inicial <= '23:59' && hora_agora_final <= '23:59'){
					if(inicial_atendente <= final_atendente){					
						if(final_atendente > hora_agora_final && inicial_atendente < hora_agora_inicial){
							inicial_hora_atendente = parseInt(inicial_hora_atendente)+1;
							if(inicial_hora_atendente <10){
								inicial_hora_atendente = '0'+inicial_hora_atendente;
							}
							inicial_atendente = inicial_hora_atendente+':'+inicial_minutos_atendente;
							
							final_hora_atendente = final_hora_atendente-1;
							if(final_hora_atendente <10){
								final_hora_atendente = '0'+final_hora_atendente;
							}
							final_atendente = final_hora_atendente+':'+final_minutos_atendente;
							if(final_atendente < hora_agora_inicial || inicial_atendente > hora_agora_inicial){
								if(final_atendente < hora_agora_inicial){
									alert('O atendente não pode sair para o intervalo com menos de 1 hora antes do horário final da sua escala (Dom)!');
									return false;
								}else{
									alert('O atendente não pode sair para o intervalo com menos de 1 hora antes do horário inicial da sua escala (Dom)!');
									return false;
								}
							}

							if(final_atendente < hora_agora_final || inicial_atendente > hora_agora_final){
								if(final_atendente < hora_agora_final){
									alert('O atendente não pode voltar do intervalo com menos de 1 hora antes do horário final da sua escala (Dom)!');
									return false;
								}else{
									alert('O atendente não pode voltar do intervalo com menos de 1 hora antes do horário inicial da sua escala (Dom)!');
									return false;
								}
							}
						}else{
							alert('O intervalo ultrapassa o horário da escala!');
							return false;
						}
					}else{
						final_hora_atendente = parseInt(final_hora_atendente)+24;
						final_atendente = final_hora_atendente+':'+final_minutos_atendente;
						if(hora_agora_inicial > hora_agora_final){
							hora_final = parseInt(hora_final)+24;
							hora_agora_final = hora_final+':'+minutos_final;
						}else if(hora_agora_inicial <= inicial_atendente && hora_agora_final <= inicial_atendente){
							hora_inicial = parseInt(hora_inicial)+24;
							hora_agora_inicial = hora_inicial+':'+minutos_inicial;
							hora_final = parseInt(hora_final)+24;
							hora_agora_final = hora_final+':'+minutos_final;
						}
						if(final_atendente > hora_agora_final && inicial_atendente < hora_agora_inicial){
							inicial_hora_atendente = parseInt(inicial_hora_atendente)+1;
							if(inicial_hora_atendente <10){
								inicial_hora_atendente = '0'+inicial_hora_atendente;
							}
							inicial_atendente = inicial_hora_atendente+':'+inicial_minutos_atendente;

							final_hora_atendente = parseInt(final_hora_atendente)-1;
							final_atendente = final_hora_atendente+':'+final_minutos_atendente;

							if(final_atendente < hora_agora_inicial || inicial_atendente > hora_agora_inicial){
								if(final_atendente < hora_agora_inicial){
									alert('O atendente não pode sair para o intervalo com menos de 1 hora antes do horário final da sua escala! (Dom)');
									return false;
								}else{
									alert('O atendente não pode sair para o intervalo com menos de 1 hora antes do horário inicial da sua escala! (Dom)');
									return false;
								}
							}

							if(final_atendente < hora_agora_final || inicial_atendente > hora_agora_final){
								if(final_atendente < hora_agora_final){
									alert('O atendente não pode voltar do intervalo com menos de 1 hora antes do horário final da sua escala! (Dom)');
									return false;
								}else{
									alert('O atendente não pode voltar do intervalo com menos de 1 hora antes do horário inicial da sua escala! (Dom)');
									return false;
								}
							}
						}else{
							alert('O intervalo ultrapassa o horário da escala! (Dom)');
							return false;
						}
					}
				}else{
					alert('O horário do intervalo deve ser menor que 23:59! (Dom)');
					return false;
				}
			}
		}

	});   				    			

</script>
		