<?php
require_once(__DIR__."/../class/System.php");
if(!empty(isset($_GET['novo']))){
	$id_horarios_escala = $_GET['novo'];
}else if(isset($_GET['alterar'])){
	$id_horarios_escala = $_GET['alterar'];
}

$mes = $_GET['mes'];
$ano = $_GET['ano'];
$data = $ano.'-'.$mes."-01";
$id_usuario_sessao = $_SESSION['id_usuario'];
$dados = DBRead('', 'tb_usuario', "WHERE id_usuario = '$id_usuario_sessao'");
$perfil_sistema = $dados[0]['id_perfil_sistema'];

if(($perfil_sistema != 3)){				
	$dados = DBRead('', 'tb_horarios_escala', "WHERE id_horarios_escala = '".$id_horarios_escala."' ");

	$nome = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_usuario = '".$dados[0]['id_usuario']."' ");
						
	$dados = DBRead('', 'tb_horarios_escala', "WHERE id_horarios_escala = '".$id_horarios_escala."' ");
	
	$id_horarios_escala = $dados[0]['id_horarios_escala'];

	$folga_seg = $dados[0]['folga_seg'];
	$inicial_seg = $dados[0]['inicial_seg'];
	$final_seg = $dados[0]['final_seg'];
	$inicial_sab = $dados[0]['inicial_sab'];
	$final_sab = $dados[0]['final_sab'];
	$inicial_dom = $dados[0]['inicial_dom'];
	$final_dom = $dados[0]['final_dom'];

	$dados_chat = DBRead('', 'tb_chat_horarios_escala', "WHERE id_horarios_escala = '".$id_horarios_escala."' ");
	$inicial_seg_chat = $dados_chat[0]['inicial_seg'];
	$final_seg_chat = $dados_chat[0]['final_seg'];
	$inicial_sab_chat = $dados_chat[0]['inicial_sab'];
	$final_sab_chat = $dados_chat[0]['final_sab'];
	$inicial_dom_chat = $dados_chat[0]['inicial_dom'];
	$final_dom_chat = $dados_chat[0]['final_dom'];

?>

	<div class="container-fluid">

		<div class="col-md-12">
			<div class="panel panel-default">
				<div class="panel-heading clearfix">
					<div class="col-md-8">
						<h3 class="panel-title text-left pull-left"><?php echo "Horários de Chat da(o) <strong>".$nome[0]['nome']."</strong>"?></h3>
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
				
				<form method="post" action="/api/ajax?class=ChatEscalaHorarios.php" id="escala" style="margin-bottom: 0;">
				<input type="hidden" name="token" value="<?php echo $request->token ?>">
				<input type="hidden" value="<?=$id_usuario?>" name="id_usuario" />
				<input type="hidden" value="<?=$id_horarios_escala?>" name="id_horarios_escala" />

				
				<div class="panel-body">

					<div class="col-md-12">
						<div class="panel panel-default">
							<div class="panel-heading clearfix">
								<h3 class="panel-title text-left pull-left">Folgas</strong></h3>
							</div>	
							<div class="panel-body" style="padding-bottom: 0;">
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<strong><label>Dia de folga:</label></strong>
                                            <input type="text" class="form-control input-sm" value="<?= $folga_seg ?>" disabled>	
										</div>
									</div>

                                    <div class="col-md-6">
										<div class="form-group">
											<strong><label>Domingo(s) de Folga(s):</label></strong>
                                            <?php 
											
												$dados_dom = DBRead('', 'tb_folgas_dom', "WHERE id_horarios_escala = '".$dados[0]['id_horarios_escala']."'");
												$cont = 0;
											
												if($dados_dom){
													foreach ($dados_dom as $folga_domingo) {
                                                        if($cont==0){
                                                            echo '<input type="text" class="form-control input-sm" value="'.converteData($folga_domingo['dia']).'" disabled>';
                                                        }else{
                                                            echo '<label></label>';
                                                            echo '<input type="text" class="form-control input-sm" value="'.converteData($folga_domingo['dia']).'" disabled>';
                                                        }
                                                        $cont++;
													}
                                                }else{
                                                    echo '<input type="text" class="form-control input-sm" value="" disabled>';
                                                }
												?>
													
										</div>
									</div>

								</div>

								
							</div>						
						</div>
					</div>
                    

					
					<!-- Horários -->
					<div class="col-md-4">
						<div class="panel panel-default">
							<div class="panel-heading clearfix">
								<h3 class="panel-title text-left pull-left">Segunda a Sexta</strong></h3>
							</div>
							<div class="panel-body" style="padding-bottom: 0;">
								<div class="row">
									<div class="col-md-12">
										<div class="form-group">
											<label>Horário inicial e final do expediente: </label>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <input type="text" class="form-control input-sm hour" id="inicial_seg" value="<?= $inicial_seg; ?>" disabled>	
                                                    </div>
                                                </div>
                                            
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <input type="text" class="form-control input-sm hour" id="final_seg" value="<?= $final_seg; ?>" disabled>	
                                                    </div>
                                                </div>
                                            </div>
										</div>
									</div>
								</div>

                                <div class="row">
									<div class="col-md-12">
										<div class="form-group label-primary" style="border-top: 1px solid #ddd !important; border-left: 1px solid #ddd !important; border-right: 1px solid #ddd !important; background-color: #fbf6db !important; color: #8a6d3b; border-radius: 5px;">
											<label>&nbsp Horário inicial de chat: </label>
											<input type="text" class="form-control input-sm hour" id="inicial_seg_chat" name="inicial_seg_chat" value="<?=$inicial_seg_chat;?>">	
										</div>
									</div>
								</div>

                                <div class="row">
									<div class="col-md-12">
										<div class="form-group label-primary" style="border-top: 1px solid #ddd !important; border-left: 1px solid #ddd !important; border-right: 1px solid #ddd !important; background-color: #fbf6db !important; color: #8a6d3b; border-radius: 5px;">
											<label>&nbsp Horário final de chat: </label>
											<input type="text" class="form-control input-sm hour" id="final_seg_chat" name="final_seg_chat" value="<?=$final_seg_chat;?>">	
										</div>
									</div>
								</div>
								
							</div>	
						</div>
					</div>

					<div class="col-md-4">
						<div class="panel panel-default">
							<div class="panel-heading clearfix">
								<h3 class="panel-title text-left pull-left">Sábado</strong></h3>
							</div>
							<div class="panel-body" style="padding-bottom: 0;">
								<div class="row">
									<div class="col-md-12">
										<div class="form-group">
                                            <label>Horário inicial e final do expediente: </label>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                    <input type="text" autofocus class="form-control input-sm hour" id="inicial_sab" value="<?=$inicial_sab; ?>" disabled>	
                                                    </div>
                                                </div>
                                            
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <input type="text" class="form-control input-sm hour" id="final_sab" value="<?= $final_sab; ?>" disabled>	
                                                    </div>
                                                </div>
                                            </div>
										</div>
									</div>
								</div>

                                <div class="row">
									<div class="col-md-12">
										<div class="form-group label-primary" style="border-top: 1px solid #ddd !important; border-left: 1px solid #ddd !important; border-right: 1px solid #ddd !important; background-color: #fbf6db !important; color: #8a6d3b; border-radius: 5px;">
											<label>&nbsp Horário inicial de chat: </label>
											<input type="text" class="form-control input-sm hour" id="inicial_sab_chat" name="inicial_sab_chat" value="<?=$inicial_sab_chat;?>">	
										</div>
									</div>
								</div>

                                <div class="row">
									<div class="col-md-12">
										<div class="form-group label-primary" style="border-top: 1px solid #ddd !important; border-left: 1px solid #ddd !important; border-right: 1px solid #ddd !important; background-color: #fbf6db !important; color: #8a6d3b; border-radius: 5px;">
											<label>&nbsp Horário final de chat: </label>
											<input type="text" class="form-control input-sm hour" id="final_sab_chat" name="final_sab_chat" value="<?=$final_sab_chat;?>">	
										</div>
									</div>
								</div>
						
							</div>
						</div>
					</div>

					<div class="col-md-4">
						<div class="panel panel-default">
							<div class="panel-heading clearfix">
								<h3 class="panel-title text-left pull-left">Domingo</strong></h3>
							</div>
							<div class="panel-body" style="padding-bottom: 0;">
								<div class="row">
									<div class="col-md-12">
										<div class="form-group">
                                            <label>Horário inicial e final do expediente: </label>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                    <input type="text" autofocus class="form-control input-sm hour" id="inicial_dom" value="<?=$inicial_dom; ?>" disabled>	
                                                    </div>
                                                </div>
                                            
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <input type="text" class="form-control input-sm hour" id="final_dom" value="<?= $final_dom; ?>" disabled>	
                                                    </div>
                                                </div>
                                            </div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<div class="form-group label-primary" style="border-top: 1px solid #ddd !important; border-left: 1px solid #ddd !important; border-right: 1px solid #ddd !important; background-color: #fbf6db !important; color: #8a6d3b; border-radius: 5px;">
											<label>&nbsp Horário inicial de chat: </label>
											<input type="text" class="form-control input-sm hour" id="inicial_dom_chat" name="inicial_dom_chat" value="<?=$inicial_dom_chat;?>">	
										</div>
									</div>
								</div>

                                <div class="row">
									<div class="col-md-12">
										<div class="form-group label-primary" style="border-top: 1px solid #ddd !important; border-left: 1px solid #ddd !important; border-right: 1px solid #ddd !important; background-color: #fbf6db !important; color: #8a6d3b; border-radius: 5px;">
											<label>&nbsp Horário final de chat: </label>
											<input type="text" class="form-control input-sm hour" id="final_dom_chat" name="final_dom_chat" value="<?=$final_dom_chat;?>">	
										</div>
									</div>
								</div>

							</div>
						</div>
					</div>
					
						<?php
						$dados_especiais = DBRead('', 'tb_horarios_especiais', "WHERE id_horarios_escala = '".$dados[0]['id_horarios_escala']."'");
							
						?>
						<div class="col-md-12" <?=$col_especial?> id="col_especial">
							<div class="panel panel-default">
								<div class="panel-heading clearfix" >
									<h3 class="panel-title text-left pull-left">Horários Especias (Feriados e etc)</strong></h3>
									<div class="panel-title text-right pull-right"></div>
							
								</div>
								<div class="panel-body <?=$var_collapse?>" id="accordionRelatorio">
									<div class='table-responsive'>
										
								<?php 
								
									$dados_especiais = DBRead('', 'tb_horarios_especiais', "WHERE id_horarios_escala = '".$dados[0]['id_horarios_escala']."'");
									if($dados_especiais){
										foreach ($dados_especiais as $especial) { 
											$dados_especiais_chat = DBRead('', 'tb_chat_horarios_especiais', "WHERE id_horarios_especiais = '".$especial['id_horarios_especiais']."'");
											$inicial_especial_chat = $dados_especiais_chat[0]['inicial_especial'];
											$final_especial_chat = $dados_especiais_chat[0]['final_especial'];
											
											?>
											<input type="hidden" value="<?=$especial['id_horarios_especiais']?>" name="id_horarios_especiais[]" />

                                            <table class='table table-bordered' style='font-size: 14px;'>
												<thead>
													<tr>
														<th class='col-md-2'>Dia:</th>
														<th class='col-md-2'>Horário inicial do expediente:</th>
														<th class='col-md-2'>Horário final do expediente:</th>
														<th class='col-md-3 label-primary' style="background-color: #fbf6db !important; color: #8a6d3b; border-radius: 10px;">Horário inicial de chat:</th>
														<th class='col-md-3 label-primary' style="background-color: #fbf6db !important; color: #8a6d3b; border-radius: 10px;">Horário final de chat:</th>
													</tr>
												</thead>
												<tbody>  
                                                    <tr>
														<td>
															<input class='form-control date calendar input-sm especial' name='especial[]' value="<?= converteData($especial['dia']) ?>" disabled/>
														</td>
													
														<td>
															<input type="text" class="form-control input-sm hour inicial-especial" name="inicial_especial[]" value="<?= $especial['inicial_especial']; ?>" disabled>
														</td>

														<td>
															<input type="text" class="form-control input-sm hour final-especial" name="final_especial[]" value="<?= $especial['final_especial']; ?>" disabled>
														</td>

                                                        <td>
															<input type="text" class="form-control input-sm hour inicial-especial-chat" name="inicial_especial_chat[]" value="<?=$inicial_especial_chat;?>">
														</td>

                                                        <td>
															<input type="text" class="form-control input-sm hour final-especial-chat" name="final_especial_chat[]" value="<?=$final_especial_chat?>">
														</td>
													</tr>

                                                   
												</tbody>
                                            </table>
	
                                            <?php
                                        }
                                            ?>
													
                                        <?php
                                    }else{
										echo "<div class=\"container-fluid text-center\"><div class=\"alert alert-warning\">Não foram cadastrados horários especiais para o(a) operador(a)!</div></div>";

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
	
	$(document).on('click', '#ok', function(){

		//SEGUNDA A SEXTA
			var inicial_seg_chat = $("#inicial_seg_chat").val().split(':');
			inicial_seg_chat = inicial_seg_chat[0]+''+inicial_seg_chat[1];
			var final_seg_chat = $("#final_seg_chat").val().split(':');
			final_seg_chat = final_seg_chat[0]+''+final_seg_chat[1];

			var inicial_seg = $("#inicial_seg").val().split(':');
			inicial_seg = inicial_seg[0]+''+inicial_seg[1];
			var final_seg = $("#final_seg").val().split(':');
			final_seg = final_seg[0]+''+final_seg[1];

			if(typeof inicial_seg_chat === "undefined" || typeof final_seg_chat === "undefined"){
				alert("Deve-se preencher os horários de chat de SEGUNDA A SEXTA!");
				return false;
			}else{
				if(inicial_seg < final_seg){
					if((inicial_seg_chat >= inicial_seg && inicial_seg_chat < final_seg) && (final_seg_chat > inicial_seg && final_seg_chat <= final_seg)){
						if(inicial_seg_chat >= final_seg_chat){
							alert("O horário final de chat de SEGUNDA A SEXTA não pode ser menor que o horário inicial de chat de SEGUNDA A SEXTA!");
							return false;
						}
					}else{
						alert("Deve-se preencher os horários de chat de SEGUNDA A SEXTA dentro do expediente do operador!");
						return false;
					}
				}else{
					if((inicial_seg_chat >= inicial_seg || inicial_seg_chat < final_seg) && (final_seg_chat > inicial_seg || final_seg_chat <= final_seg)){
						if(inicial_seg_chat >= final_seg_chat){
							//Para conseguir comparar os horários invertidos
							//Ex:
							//23:00 às 03:00
							//2300 <= 2700
							final_seg_chat = parseInt(final_seg_chat)+2400;
						}
						if(inicial_seg_chat >= final_seg_chat){
							alert("O horário final de chat de SEGUNDA A SEXTA não pode ser menor que o horário inicial de chat de SEGUNDA A SEXTA!");
							return false;
						}
					}else{
						alert("Deve-se preencher os horários de chat de SEGUNDA A SEXTA dentro do expediente do operador!");
						return false;
					}
				}
			}
		//SEGUNDA A SEXTA FIM

		//SABADO
			var inicial_sab_chat = $("#inicial_sab_chat").val().split(':');
			inicial_sab_chat = inicial_sab_chat[0]+''+inicial_sab_chat[1];
			var final_sab_chat = $("#final_sab_chat").val().split(':');
			final_sab_chat = final_sab_chat[0]+''+final_sab_chat[1];

			var inicial_sab = $("#inicial_sab").val().split(':');
			inicial_sab = inicial_sab[0]+''+inicial_sab[1];
			var final_sab = $("#final_sab").val().split(':');
			final_sab = final_sab[0]+''+final_sab[1];

			if(typeof inicial_sab_chat === "undefined" || typeof final_sab_chat === "undefined"){
				alert("Deve-se preencher os horários de chat de SÁBADO!");
				return false;
			}else{
				if(inicial_sab < final_sab){
					if((inicial_sab_chat >= inicial_sab && inicial_sab_chat < final_sab) && (final_sab_chat > inicial_sab && final_sab_chat <= final_sab)){
						if(inicial_sab_chat >= final_sab_chat){
							alert("O horário final de chat de SÁBADO não pode ser menor que o horário inicial de chat de SÁBADO!");
							return false;
						}
					}else{
						alert("Deve-se preencher os horários de chat de SÁBADO dentro do expediente do operador!");
						return false;
					}
				}else{
					if((inicial_sab_chat >= inicial_sab || inicial_sab_chat < final_sab) && (final_sab_chat > inicial_sab || final_sab_chat <= final_sab)){
						if(inicial_sab_chat >= final_sab_chat){
							//Para conseguir comparar os horários invertidos
							//Ex:
							//23:00 às 03:00
							//2300 <= 2700
							final_sab_chat = parseInt(final_sab_chat)+2400;
						}
						if(inicial_sab_chat >= final_sab_chat){
							alert("O horário final de chat de SÁBADO não pode ser menor que o horário inicial de chat de SÁBADO!");
							return false;
						}
					}else{
						alert("Deve-se preencher os horários de chat de SÁBADO dentro do expediente do operador!");
						return false;
					}
				}
			}
		//SABADO FIM

		//DOMINGO
			var inicial_dom_chat = $("#inicial_dom_chat").val().split(':');
			inicial_dom_chat = inicial_dom_chat[0]+''+inicial_dom_chat[1];
			var final_dom_chat = $("#final_dom_chat").val().split(':');
			final_dom_chat = final_dom_chat[0]+''+final_dom_chat[1];

			var inicial_dom = $("#inicial_dom").val().split(':');
			inicial_dom = inicial_dom[0]+''+inicial_dom[1];
			var final_dom = $("#final_dom").val().split(':');
			final_dom = final_dom[0]+''+final_dom[1];

			if(typeof inicial_dom_chat === "undefined" || typeof final_dom_chat === "undefined"){
				alert("Deve-se preencher os horários de chat de DOMINGO!");
				return false;
			}else{
				if(inicial_dom < final_dom){
					if((inicial_dom_chat >= inicial_dom && inicial_dom_chat < final_dom) && (final_dom_chat > inicial_dom && final_dom_chat <= final_dom)){
						if(inicial_dom_chat >= final_dom_chat){
							alert("O horário final de chat de SÁBADO não pode ser menor que o horário inicial de chat de DOMINGO!");
							return false;
						}
					}else{
						alert("Deve-se preencher os horários de chat de DOMINGO dentro do expediente do operador!");
						return false;
					}
				}else{
					if((inicial_dom_chat >= inicial_dom || inicial_dom_chat < final_dom) && (final_dom_chat > inicial_dom || final_dom_chat <= final_dom)){
						if(inicial_dom_chat >= final_dom_chat){
							//Para conseguir comparar os horários invertidos
							//Ex:
							//23:00 às 03:00
							//2300 <= 2700
							final_dom_chat = parseInt(final_dom_chat)+2400;
						}
						if(inicial_dom_chat >= final_dom_chat){
							alert("O horário final de chat de DOMINGO não pode ser menor que o horário inicial de chat de DOMINGO!");
							return false;
						}
					}else{
						alert("Deve-se preencher os horários de chat de DOMINGO dentro do expediente do operador!");
						return false;
					}
				}
			}
		//DOMINGO FIM
		
		//HORÁRIOS ESPECIAIS
			$('.inicial-especial').each(function(){
				var inicial = this.value;		
				inicial = inicial.split(':');
				inicial = inicial[0]+''+inicial[1];

				var final = $(this).parent().parent().find('.final-especial').val();
				final = final.split(':');
				final = final[0]+''+final[1];

				var inicial_chat = $(this).parent().parent().find('.inicial-especial-chat').val();
				inicial_chat = inicial_chat.split(':');
				inicial_chat = inicial_chat[0]+''+inicial_chat[1];

				var final_chat = $(this).parent().parent().find('.final-especial-chat').val();
				final_chat = final_chat.split(':');
				final_chat = final_chat[0]+''+final_chat[1];
				
				if(typeof inicial_chat === "undefined" || typeof final_chat === "undefined"){
					// alert("Deve-se preencher os horários de chat no(s) HORÁRIO(S) ESPECIAL(AIS)!");
					// return false;
				}else{
					if(inicial < final){
						if((inicial_chat >= inicial && inicial_chat < final) && (final_chat > inicial && final_chat <= final)){
							if(inicial_chat >= final_chat){
							alert("O horário final de chat no HORÁRIO ESPECIAL não pode ser menor que o horário inicial de chat de HORÁRIO ESPECIAL!");
							return false;
						}
						}else{
							alert("Deve-se preencher os horários de chat no HORÁRIO ESPECIAL dentro do expediente do operador!");
							return false;
						}
					}else{
						if((inicial_chat >= inicial || inicial_chat < final) && (final_chat > inicial || final_chat <= final)){
						if(inicial_chat >= final_chat){
							//Para conseguir comparar os horários invertidos
							//Ex:
							//23:00 às 03:00
							//2300 <= 2700
							final_chat = parseInt(final_chat)+2400;
						}
						if(inicial_chat >= final_chat){
							alert("O horário final de chat no HORÁRIO ESPECIAL não pode ser menor que o horário inicial de chat no HORÁRIO ESPECIAL!");
							return false;
						}
					}else{
						alert("Deve-se preencher os horários de chat no HORÁRIO ESPECIAL dentro do expediente do operador!");
						return false;
					}
					}
				}
			});	
		//HORÁRIOS ESPECIAIS FIM
 

	});   				    			

</script>
		