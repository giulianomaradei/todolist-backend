<?php
require_once(__DIR__."/../class/System.php");


$id_usuario = $_GET['visualizar'];
?>
<div class="container-fluid">

    <div class="row">
				<?php 
				
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


				$dados = DBRead('', 'tb_disponibilidade_escala', "WHERE id_usuario = '".$id_usuario."'");
				
			?>

            <div class="col-md-4 col-md-offset-4">
	            <div class="panel panel-default">
	                <div class="panel-heading clearfix">
	                    <h3 class="panel-title text-left pull-left">Disponibilidade da(o) : <strong><?php echo $nome[0]['nome']; ?></strong></h3>
	                </div>
	                <div class="panel-body">
						<ul class="nav nav-tabs" role="tablist">
                            <li role="presentation" class="active"><a href="#seg" aria-controls="seg" role="tab" data-toggle="tab">Segundas a Sextas</a></li>
                            <li role="presentation"><a href="#sab" aria-controls="sab" role="tab" data-toggle="tab">Sábados</a></li>
                            <li role="presentation"><a href="#dom" aria-controls="dom" role="tab" data-toggle="tab">Domingos</a></li>
                        </ul>
                        <form method="post" action="/api/ajax?class=Escala.php" id="escala" style="margin-bottom: 0;">
							<input type="hidden" name="token" value="<?php echo $request->token ?>">
                                    <!-- Tab panes -->
                                    <div class="tab-content">

                                    	<!-- Começa seg a sab -->
                                        <div role="tabpanel" class="tab-pane active" id="seg">
                                        
		                
		                    				<?php

											$opcoes = array(
												"2" => "Acessível",
												"1" => "Preferência",
												"3" => "Incômodo",
												"4" => "Impossível"
											);

											$horarios = array(
												"0" => "00:00",
												"1" => "01:00",
												"2" => "02:00",
												"3" => "03:00",
												"4" => "04:00",
												"5" => "05:00",
												"6" => "06:00",
												"7" => "07:00",
												"8" => "08:00",
												"9" => "09:00",
												"10" => "10:00",
												"11" => "11:00",
												"12" => "12:00",
												"13" => "13:00",
												"14" => "14:00",
												"15" => "15:00",
												"16" => "16:00",
												"17" => "17:00",
												"18" => "18:00",
												"19" => "19:00",
												"20" => "20:00",
												"21" => "21:00",
												"22" => "22:00",
												"23" => "23:00"
											);
											
											$dados_seg = DBRead('', 'tb_horarios_disponibilidade', "WHERE id_disponibilidade_escala = '".$dados[0]['id_disponibilidade_escala']."' AND periodo = 'seg a sex'");


											if($dados_seg){

												echo "<ul class='list-group'>";
											
												echo "<li class='list-group-item'>";
													echo "<div class=\"row\">";


													foreach ($dados_seg as $seg) {
														$selected = $seg['disponibilidade'] == $key ? "selected" : "";

														if($seg['disponibilidade'] == 1){
															$var = "success";
														}else if($seg['disponibilidade'] == 2){
															$var = "info";

														}else if($seg['disponibilidade'] == 3){
															$var = "warning";

														}else if($seg['disponibilidade'] == 4){
															$var = "danger";

														}
														if($seg['horario'] < 10){
															$hora_seg = "0".$seg['horario'].":00";
														}else{
															$hora_seg = $seg['horario'].":00";
														}
														echo "<div class='col-md-3'>";
															echo "<span>".$hora_seg."</span>";
														echo "</div>";
														
														echo "<div class='col-md-9'>";
															echo "<select id='".$seg['horario']."' class='form-control input-sm select-pref label-".$var."' cor='label-".$var."' name='escala_seg[]' id='escala'>";
			                                       				
																if($seg['disponibilidade'] == 1){
																	$opc = "Preferência";
																}else if($seg['disponibilidade'] == 2){
																	$opc = "Acessível";

																}else if($seg['disponibilidade'] == 3){
																	$opc = "Incômodo";

																}else if($seg['disponibilidade'] == 4){
																	$opc = "Impossível";

																}
																	echo "<option class = 'label-".$var."' value='".$key."' ".$selected.">".$opc."</option>";

																
															echo "</select>";	
														echo "<br>";


														echo "</div>";

													}
														

												echo "</li>";
											
													
											echo "</ul>";

											}else{
											echo "<ul class='list-group'>";
											
												echo "<li class='list-group-item'>";
													echo "<div class=\"row\">";

													foreach ($horarios as $hora) {
													
														echo "<div class='col-md-3'>";
															echo "<span>".$hora."</span>";
														echo "</div>";

														echo "<div class='col-md-9'>";
															echo "<select disabled id='".$hora."' class='form-control input-sm select-pref label-info' cor='label-info' name='escala_seg[]' id='escala' disabled>";
			                                       				
																foreach ($opcoes as $key => $opc) {
																	
																	if($opc == "Preferência"){
																		$var = "success";
																	}else if($opc == "Acessível"){
																		$var = "info";
																	}else if($opc == "Incômodo"){
																		$var = "warning";
																	}else if($opc == "Impossível"){
																		$var = "danger";
																	}

																	echo "<option class = 'label-".$var."' value='".$key."'>".$opc."</option>";
																}

															echo "</select>";	
														echo "<br>";


														echo "</div>";

													}
														

												echo "</li>";
											
													
											echo "</ul>";
						

										}
											?>
						                     

                    	
               
            							</div>
            							<!-- Acaba seg a sab -->

            							<!-- Comça sab -->
                                        <div role="tabpanel" class="tab-pane" id="sab">
                
		                    				<?php

											$opcoes = array(
												"2" => "Acessível",
												"1" => "Preferência",
												"3" => "Incômodo",
												"4" => "Impossível"
											);

											$horarios = array(
												"0" => "00:00",
												"1" => "01:00",
												"2" => "02:00",
												"3" => "03:00",
												"4" => "04:00",
												"5" => "05:00",
												"6" => "06:00",
												"7" => "07:00",
												"8" => "08:00",
												"9" => "09:00",
												"10" => "10:00",
												"11" => "11:00",
												"12" => "12:00",
												"13" => "13:00",
												"14" => "14:00",
												"15" => "15:00",
												"16" => "16:00",
												"17" => "17:00",
												"18" => "18:00",
												"19" => "19:00",
												"20" => "20:00",
												"21" => "21:00",
												"22" => "22:00",
												"23" => "23:00"
											);
											
											$dados_sab = DBRead('', 'tb_horarios_disponibilidade', "WHERE id_disponibilidade_escala = '".$dados[0]['id_disponibilidade_escala']."' AND periodo = 'sabado'");


											if($dados_sab){

												echo "<ul class='list-group'>";
											
												echo "<li class='list-group-item'>";
													echo "<div class=\"row\">";


													foreach ($dados_sab as $sab) {
														$selected = $sab['disponibilidade'] == $key ? "selected" : "";

														if($sab['disponibilidade'] == 1){
															$var = "success";
														}else if($sab['disponibilidade'] == 2){
															$var = "info";

														}else if($sab['disponibilidade'] == 3){
															$var = "warning";

														}else if($sab['disponibilidade'] == 4){
															$var = "danger";

														}
														if($sab['horario'] < 10){
															$hora_sab = "0".$sab['horario'].":00";
														}else{
															$hora_sab = $sab['horario'].":00";
														}
														echo "<div class='col-md-3'>";
															echo "<span>".$hora_sab."</span>";
														echo "</div>";

														echo "<div class='col-md-9'>";
															echo "<select id='".$sab['horario']."' class='form-control input-sm select-pref label-".$var."' cor='label-".$var."' name='escala_sab[]' id='escala'>";
			                                       				
																
																
																if($sab['disponibilidade'] == 1){
																	$opc = "Preferência";
																}else if($sab['disponibilidade'] == 2){
																	$opc = "Acessível";

																}else if($sab['disponibilidade'] == 3){
																	$opc = "Incômodo";

																}else if($sab['disponibilidade'] == 4){
																	$opc = "Impossível";

																}
																	echo "<option class = 'label-".$var."' value='".$key."' ".$selected.">".$opc."</option>";
															echo "</select>";	
														echo "<br>";


														echo "</div>";

													}
														

												echo "</li>";
											
													
											echo "</ul>";

											}else{
											echo "<ul class='list-group'>";
											
												echo "<li class='list-group-item'>";
													echo "<div class=\"row\">";

													foreach ($horarios as $hora) {
													
														echo "<div class='col-md-3'>";
															echo "<span>".$hora."</span>";
														echo "</div>";

														echo "<div class='col-md-9'>";
															echo "<select disabled id='".$hora."' class='form-control input-sm select-pref label-info' cor='label-info' name='escala_sab[]' id='escala'>";
			                                       				
																foreach ($opcoes as $key => $opc) {
																	
																	if($opc == "Preferência"){
																		$var = "success";
																	}else if($opc == "Acessível"){
																		$var = "info";
																	}else if($opc == "Incômodo"){
																		$var = "warning";
																	}else if($opc == "Impossível"){
																		$var = "danger";
																	}

																	echo "<option class = 'label-".$var."' value='".$key."'>".$opc."</option>";
																}

															echo "</select>";	
														echo "<br>";


														echo "</div>";

													}
														

												echo "</li>";
											
													
											echo "</ul>";
						

										}
											?>
						                     

                    	
               
            							</div>
               							<!-- Acaba sab -->

               							<!-- Começa dom -->
                                        <div role="tabpanel" class="tab-pane" id="dom">
                
		                    				<?php

											$opcoes = array(
												"2" => "Acessível",
												"1" => "Preferência",
												"3" => "Incômodo",
												"4" => "Impossível"
											);

											$horarios = array(
												"0" => "00:00",
												"1" => "01:00",
												"2" => "02:00",
												"3" => "03:00",
												"4" => "04:00",
												"5" => "05:00",
												"6" => "06:00",
												"7" => "07:00",
												"8" => "08:00",
												"9" => "09:00",
												"10" => "10:00",
												"11" => "11:00",
												"12" => "12:00",
												"13" => "13:00",
												"14" => "14:00",
												"15" => "15:00",
												"16" => "16:00",
												"17" => "17:00",
												"18" => "18:00",
												"19" => "19:00",
												"20" => "20:00",
												"21" => "21:00",
												"22" => "22:00",
												"23" => "23:00"
											);
											
											$dados_dom = DBRead('', 'tb_horarios_disponibilidade', "WHERE id_disponibilidade_escala = '".$dados[0]['id_disponibilidade_escala']."' AND periodo = 'domingo'");


											if($dados_dom){

												echo "<ul class='list-group'>";
											
												echo "<li class='list-group-item'>";
													echo "<div class=\"row\">";


													foreach ($dados_dom as $dom) {
														$selected = $dom['disponibilidade'] == $key ? "selected" : "";

														if($dom['disponibilidade'] == 1){
															$var = "success";
														}else if($dom['disponibilidade'] == 2){
															$var = "info";

														}else if($dom['disponibilidade'] == 3){
															$var = "warning";

														}else if($dom['disponibilidade'] == 4){
															$var = "danger";

														}
														if($dom['horario'] < 10){
															$hora_dom = "0".$dom['horario'].":00";
														}else{
															$hora_dom = $dom['horario'].":00";
														}
														echo "<div class='col-md-3'>";
															echo "<span>".$hora_dom."</span>";
														echo "</div>";

														echo "<div class='col-md-9'>";
															echo "<select id='".$dom['horario']."' class='form-control input-sm select-pref label-".$var."' cor='label-".$var."' name='escala_dom[]' id='escala'>";
			                                       				
																
																
																if($dom['disponibilidade'] == 1){
																	$opc = "Preferência";
																}else if($dom['disponibilidade'] == 2){
																	$opc = "Acessível";

																}else if($dom['disponibilidade'] == 3){
																	$opc = "Incômodo";

																}else if($dom['disponibilidade'] == 4){
																	$opc = "Impossível";

																}

																	echo "<option class = 'label-".$var."' value='".$key."' ".$selected.">".$opc."</option>";

															echo "</select>";	
														echo "<br>";


														echo "</div>";

													}
														

												echo "</li>";
											
													
											echo "</ul>";

											}else{
											echo "<ul class='list-group'>";
											
												echo "<li class='list-group-item'>";
													echo "<div class=\"row\">";

													foreach ($horarios as $hora) {
													
														echo "<div class='col-md-3'>";
															echo "<span>".$hora."</span>";
														echo "</div>";

														echo "<div class='col-md-9'>";
															echo "<select disabled id='".$hora."' class='form-control input-sm select-pref label-info' cor='label-info' name='escala_dom[]' id='escala'>";
			                                       				
																foreach ($opcoes as $key => $opc) {
																	
																	if($opc == "Preferência"){
																		$var = "success";
																	}else if($opc == "Acessível"){
																		$var = "info";
																	}else if($opc == "Incômodo"){
																		$var = "warning";
																	}else if($opc == "Impossível"){
																		$var = "danger";
																	}

																	echo "<option class = 'label-".$var."' value='".$key."'>".$opc."</option>";
																}

															echo "</select>";	
														echo "<br>";


														echo "</div>";

													}
														

												echo "</li>";
											
													
											echo "</ul>";
						

										}
											?>
						                     

                    	
               
            							</div>
            							
																
				                    <!-- acaba aqui embaixo -->                    
				                     </div>
				                   <div class="row">
										
										<div class="col-md-12 ">
											<div class="form-group">
												<div class='col-md-12'>
													<strong><label>Preferência de dia de folga:</label></strong>
													<select disabled name="folga" id="folga" class="form-control">
													<?php
													$dias = array(
														"1" => "Segunda",
														"2" => "Terça",
														"3" => "Quarta",
														"5" => "Quinta",
														"6" => "Sexta",
														"7" => "Sábado",
													);

													foreach ($dias as $num => $dia) {
														$selected = $dados[0]['folga'] == $dia ? "selected" : "";
														echo "<option value='".$dia."' ".$selected.">".$dia."</option>";
													}
													?>													
												</select>
												</div>
												
											</div>
										</div>
										
										<div class="col-md-12 ">
											<div class="form-group">
												<div class='col-md-12'>
													<br>
													<strong><label>Preferência de Domingo de folga:</label></strong>
													<select disabled name="folga_domingo" id="folga_domingo" class="form-control">
													<?php
													$domingos = array(
														"1" => "Primeiro",
														"2" => "Segundo",
														"3" => "Terceiro",
														"4" => "Quarto"
													);
													foreach ($domingos as $numero => $domingo) {
														$selected = $dados[0]['folga_dom'] == $numero ? "selected" : "";
														echo "<option value='".$numero."' ".$selected.">".$domingo."</option>";
													}
													?>
												</select>
												</div>
											</div>
										</div>

									<div class="col-md-12 ">
										<div class="form-group">
											<div class='col-md-12'>
												<br>
												<strong><label>Justificativa sobre o horários de indisponibilidade (impossível):</label></strong>
											</div>
											<div class='col-md-12'>
												<textarea disabled class='form-control'  name='justificativa'><?php echo $dados[0]['justificativa_indisponibilidade']?></textarea>
											</div>
										</div>
									</div>														
							</div>
						<br>
					</div>
	            </div>
             <form>
        </div>
    </div>
</div>
<?php
}else{
	echo "<div class=\"container-fluid text-center\"><div class=\"alert alert-danger\"><i class=\"fa fa-ban\" aria-hidden=\"true\"></i> Ops! Você não tem permissão de acesso!</div></div>";
}
?>
	    
						    <script>
						    	$(document).on('change', '.select-pref', function(){
							        cor = $(this).attr('cor');
							        val = $(this).val();
							        $(this).removeClass(cor);
							        if(val == '1'){
							        	$(this).addClass('label-success');
							        	$(this).attr('cor','label-success');
							        }else if(val == '2'){
							        	$(this).addClass('label-info');
							        	$(this).attr('cor','label-info');
							        }else if(val == '3'){
							        	$(this).addClass('label-warning');
							        	$(this).attr('cor','label-warning');							        	
							        }else if(val == '4'){
							        	$(this).addClass('label-danger');
							        	$(this).attr('cor','label-danger');							        	
							        }
							    });
						    </script>
					    	</div>
					  </div>              	
					</div>
				</div>
                
            </div>
        </div>
    </div>