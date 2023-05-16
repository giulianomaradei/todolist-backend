
<?php
require_once(__DIR__."/../class/System.php");

$tipo_relatorio = (! empty($_POST['tipo_relatorio'])) ? $_POST['tipo_relatorio'] : 1;
$gerar = (! empty($_POST['gerar'])) ? 1 : 0;
$setor = (! empty($_POST['setor'])) ? $_POST['setor'] : '';
$objetivo = (! empty($_POST['objetivo'])) ? $_POST['objetivo'] : '';
$id_responsavel = (! empty($_POST['id_responsavel'])) ? $_POST['id_responsavel'] : '';
$id_participante = (! empty($_POST['id_participante'])) ? $_POST['id_participante'] : '';
$id_treinamento = (! empty($_POST['id_treinamento'])) ? $_POST['id_treinamento'] : '';
$id_participante2 = (! empty($_POST['id_participante2'])) ? $_POST['id_participante2'] : '';
$primeiro_dia = new DateTime(getDataHora('data'));
$primeiro_dia->modify('first day of this month');
$primeiro_dia = $primeiro_dia->format('d/m/Y');
$ultimo_dia = new DateTime(getDataHora('data'));
$ultimo_dia->modify('last day of this month');
$ultimo_dia = $ultimo_dia ->format('d/m/Y');
$data_de_treinamento = (! empty($_POST['data_de_treinamento'])) ? $_POST['data_de_treinamento'] : $primeiro_dia;
$data_ate_treinamento = (! empty($_POST['data_ate_treinamento'])) ? $_POST['data_ate_treinamento'] : $ultimo_dia;
$data_de_avaliacao = (! empty($_POST['data_de_avaliacao'])) ? $_POST['data_de_avaliacao'] : $primeiro_dia;
$data_ate_avaliacao = (! empty($_POST['data_ate_avaliacao'])) ? $_POST['data_ate_avaliacao'] : $ultimo_dia;

if ($gerar) {
    $collapse = '';
	$collapse_icon = 'plus';
	
} else {
    $collapse = 'in';
    $collapse_icon = 'minus';
}

if($tipo_relatorio == 1){
	$display_row_setor = '';
	$display_row_objetivo = '';
	$display_row_data_treinamento = '';
	$display_row_data_avaliacao = 'style="display:none;"';
	$display_row_id_participante = '';
	$display_row_id_responsavel = '';
	$display_row_id_treinamento = 'style="display:none;"';
	$display_row_id_participante2 = 'style="display:none;"';

}else if($tipo_relatorio == 2){
	$display_row_setor = 'style="display:none;"';
	$display_row_objetivo = 'style="display:none;"';
	$display_row_data_treinamento = 'style="display:none;"';
	$display_row_data_avaliacao = '';
	$display_row_id_participante = 'style="display:none;"';
	$display_row_id_responsavel = 'style="display:none;"';
	$display_row_id_treinamento = '';
	$display_row_id_participante2 = 'style="display:none;"';

}else if($tipo_relatorio == 3){
	$display_row_setor = 'style="display:none;"';
	$display_row_objetivo = 'style="display:none;"';
	$display_row_data_treinamento = '';
	$display_row_data_avaliacao = 'style="display:none;"';
	$display_row_id_participante = 'style="display:none;"';
	$display_row_id_responsavel = 'style="display:none;"';
	$display_row_id_treinamento = 'style="display:none;"';
	$display_row_id_participante2 = '';
}

?>
<style>
@media print {
	.noprint {
		display: none;
	}
	body {
		font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
		padding-top: 0;
	}
}
</style>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs/jszip-2.5.0/dt-1.10.18/b-1.5.4/b-colvis-1.5.4/b-flash-1.5.4/b-html5-1.5.4/b-print-1.5.4/r-2.2.2/datatables.min.css"/> 
 <script type="text/javascript" src="https://cdn.datatables.net/v/bs/jszip-2.5.0/dt-1.10.18/b-1.5.4/b-colvis-1.5.4/b-flash-1.5.4/b-html5-1.5.4/b-print-1.5.4/r-2.2.2/datatables.min.js"></script>
 <script type="text/javascript" src="https://cdn.datatables.net/plug-ins/1.10.19/sorting/time.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/plug-ins/1.10.19/sorting/chinese-string.js"></script>

<div class="container-fluid">
	<form method="post" action="">
		<div class="row">
			<div class="col-md-4 col-md-offset-4">
				<div class="panel panel-default noprint">
					<div class="panel-heading clearfix">
						<h3 class="panel-title text-left pull-left"
							style="margin-top: 2px;">Relatório de Treinamentos:</h3>
						<div class="panel-title text-right pull-right">
							<button data-toggle="collapse" data-target="#accordionRelatorio"
								class="btn btn-xs btn-info" type="button"
								title="Visualizar filtros">
								<i id="i_collapse" class="fa fa-<?=$collapse_icon?>"></i>
							</button>
						</div>
					</div>
					<div id="accordionRelatorio" class="panel-collapse collapse <?=$collapse?>">
						<div class="panel-body">
							<div class="row">
								<div class="col-md-12">
									<div class="form-group">
										<label>Tipo de Relatório:</label> 
										<select name="tipo_relatorio" id="tipo_relatorio" class="form-control input-sm">
											<option value="1" <?php if($tipo_relatorio == '1'){echo 'selected';}?>>Listagem</option>
											<option value="2" <?php if($tipo_relatorio == '2'){echo 'selected';}?>>Avaliações</option>
											<option value="3" <?php if($tipo_relatorio == '3'){echo 'selected';}?>>Participante</option>
										</select>
									</div>
								</div>
							</div>
							
							<div class="row" id="row_setor" <?=$display_row_setor?>>
								<div class="col-md-12">
									<div class="form-group">
										<label for="">Setor:</label>
										<select name="setor" class="form-control input-sm">
											<option value="">Todos</option>
												<?php
												$dados_setor = DBRead('', 'tb_perfil_sistema', "WHERE status = 1 ORDER BY nome ASC");
												if ($dados_setor) {
													foreach ($dados_setor as $conteudo_setor) {
														$selected = $setor == $conteudo_setor['id_perfil_sistema'] ? "selected" : "";
														echo "<option value='" . $conteudo_setor['id_perfil_sistema'] . "' " . $selected . ">" . $conteudo_setor['nome'] . "</option>";
													}
												}
												?>
										</select>
									</div>
								</div>
                            </div>
                            
                            <div class="row" id="row_objetivo" <?=$display_row_objetivo?>>
								<div class="col-md-12">
									<div class="form-group">
										<label for="">Objetivo:</label>
										<select name="objetivo" class="form-control input-sm">
											<option value="">Todos</option>
                                            <option value="1" <?php if($objetivo == '1'){echo 'selected';}?>>Qualificação</option>
											<option value="2" <?php if($objetivo == '2'){echo 'selected';}?>>Reciclagem</option>
										</select>
									</div>
								</div>
                            </div>

                            <div class="row" id="row_id_responsavel" <?=$display_row_id_responsavel?>>
								<div class="col-md-12">
									<div class="form-group">
										<label for="">Responsável:</label>
										<select name="id_responsavel" class="form-control input-sm">
											<option value="">Todos</option>
												<?php
												$dados_responsavel = DBRead('', 'tb_treinamento_responsavel a', "INNER JOIN tb_usuario b ON a.id_usuario = b.id_usuario INNER JOIN tb_pessoa c ON b.id_pessoa = c.id_pessoa GROUP BY a.id_usuario ORDER BY c.nome ASC", "a.id_usuario AS id_responsavel, c.nome");
												if ($dados_responsavel) {
													foreach ($dados_responsavel as $conteudo_responsavel) {
														$selected = $id_responsavel == $conteudo_responsavel['id_responsavel'] ? "selected" : "";
														echo "<option value='" . $conteudo_responsavel['id_responsavel'] . "' " . $selected . ">" . $conteudo_responsavel['nome'] . "</option>";
													}
												}
												?>
										</select>
									</div>
								</div>
                            </div>

                            <div class="row" id="row_id_participante" <?=$display_row_id_participante?>>
								<div class="col-md-12">
									<div class="form-group">
										<label for="">Participante:</label>
										<select name="id_participante" class="form-control input-sm">
											<option value="">Todos</option>
												<?php
												$dados_participante = DBRead('', 'tb_treinamento_participante a', "INNER JOIN tb_usuario b ON a.id_usuario = b.id_usuario INNER JOIN tb_pessoa c ON b.id_pessoa = c.id_pessoa GROUP BY a.id_usuario ORDER BY c.nome ASC", "a.id_usuario AS id_participante, c.nome");
												if ($dados_participante) {
													foreach ($dados_participante as $conteudo_participante) {
														$selected = $id_participante == $conteudo_participante['id_participante'] ? "selected" : "";
														echo "<option value='" . $conteudo_participante['id_participante'] . "' ".$selected.">" . $conteudo_participante['nome'] . "</option>";
													}
												}
												?>
										</select>
									</div>
								</div>
							</div>

							<div class="row" id="row_id_participante2" <?=$display_row_id_participante2?>>
								<div class="col-md-12">
									<div class="form-group">
										<label for="">Participante:</label>
										<select name="id_participante2" id="id_participante2" class="form-control input-sm">
											<option value="">Selecione um participante!</option>
												<?php
												if ($dados_participante) {
													foreach ($dados_participante as $conteudo_participante) {
														$selected = $id_participante2 == $conteudo_participante['id_participante'] ? "selected" : "";
														echo "<option value='" . $conteudo_participante['id_participante'] . "' " . $selected . ">" . $conteudo_participante['nome'] . "</option>";
													}
												}
												?>
										</select>
									</div>
								</div>
							</div>
							
							<div class="row" id="row_id_treinamento" <?=$display_row_id_treinamento?>>
								<div class="col-md-12">
									<div class="form-group">
										<label for="">Treinamento:</label>
										<select name="id_treinamento" class="form-control input-sm">
											<option value="">Todos</option>
								        </select>
									</div>
								</div>
                            </div>

							<div class="row" id="row_data_treinamento" <?=$display_row_data_treinamento?>>
								<div class="col-md-6">
									<div class="form-group">
										<label>Data Inicial do Treinamento:</label> 
										<input type="text" class="form-control date calendar input-sm" name="data_de_treinamento" value="<?=$data_de_treinamento?>" id="data_de_treinamento" required>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label>Data Final do Treinamento:</label> <input type="text" class="form-control date calendar input-sm" name="data_ate_treinamento" value="<?=$data_ate_treinamento?>" id="data_ate_treinamento" required>
									</div>
								</div>
							</div>

							<div class="row" id="row_data_avaliacao" <?=$display_row_data_avaliacao?>>
								<div class="col-md-6">
									<div class="form-group">
										<label>Data Inicial da Avaliação:</label> 
										<input type="text" class="form-control date calendar input-sm treinamento" name="data_de_avaliacao" value="<?=$data_de_avaliacao?>" id="data_de_avaliacao" required>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label>Data Final da Avaliação:</label> 
										<input type="text" class="form-control date calendar input-sm treinamento" name="data_ate_avaliacao" value="<?=$data_ate_avaliacao?>" id="data_ate_avaliacao" required>
									</div>
								</div>
							</div>
                            
		                </div>
					</div>
					<div class="panel-footer">
						<div class="row">
							<div id="panel_buttons" class="col-md-12" style="text-align: center">
								<button class="btn btn-primary" name="gerar" id="gerar" value="1" type="submit">
									<i class="fa fa-refresh"></i> Gerar
								</button>
								<button class="btn btn-warning" name="imprimir" type="button" onclick="window.print();">
									<i class="fa fa-print"></i> Imprimir
								</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</form>
	<div class="row" id="resultado">
	<?php
		if ($gerar) {
			if ($tipo_relatorio == 1) {
				relatorio_listagem($setor, $objetivo, $id_responsavel, $id_participante, $data_de_treinamento, $data_ate_treinamento);

			} else if ($tipo_relatorio == 2) {
				relatorio_avaliacao($data_de_avaliacao, $data_ate_avaliacao, $id_treinamento);

			} else if ($tipo_relatorio == 3) {
				relatorio_participante($data_de_treinamento, $data_ate_treinamento, $id_participante2);
			}
		}
	?>
	</div>
</div>

<script>
    $(document).on('submit', 'form', function(){       
        
		var tipo_relatorio = $('#tipo_relatorio').val();
		var id_participante = $('#id_participante2').val();
		if(tipo_relatorio == 3 && !id_participante){
			alert('Selecione um participante!');
			return false;
		}else{
			modalAguarde();
		}
    });

    $('#accordionRelatorio').on('shown.bs.collapse', function () {
       $("#i_collapse").removeClass("fa fa-plus").addClass("fa fa-minus");
    });

    $('#accordionRelatorio').on('hidden.bs.collapse', function () {
       $("#i_collapse").removeClass("fa fa-minus").addClass("fa fa-plus");
    });

    $(document).ready(function(){
	    $('#aguarde').hide();
	    $('#resultado').show();
	    $("#gerar").prop("disabled", false);

    	//____________________________________________________________
		var data_de = $("input[name=data_de_avaliacao]").val();
  		var data_ate = $("input[name=data_ate_avaliacao]").val();
    	//____________________________________________________________

		var id_treinamento = "<?=$id_treinamento?>";
		selectTreinamento(data_de, data_ate, id_treinamento);

	});

	$('#tipo_relatorio').on('change',function(){
		tipo_relatorio = $(this).val();

		if(tipo_relatorio == 1){
			$('#row_setor').show();
			$('#row_objetivo').show();
			$('#row_data_treinamento').show();
			$('#row_data_avaliacao').hide();
			$('#row_id_participante').show();
			$('#row_id_responsavel').show();
			$('#row_id_treinamento').hide();
			$('#row_id_participante2').hide();

		}else if(tipo_relatorio == 2){
			$('#row_setor').hide();
			$('#row_objetivo').hide();
			$('#row_data_treinamento').hide();
			$('#row_data_avaliacao').show();
			$('#row_id_participante').hide();
			$('#row_id_responsavel').hide();
			$('#row_id_treinamento').show();
			$('#row_id_participante2').hide();

		}else if(tipo_relatorio == 3){
			$('#row_setor').hide();
			$('#row_objetivo').hide();
			$('#row_data_treinamento').show();
			$('#row_data_avaliacao').hide();
			$('#row_id_participante').hide();
			$('#row_id_responsavel').hide();
			$('#row_id_treinamento').hide();
			$('#row_id_participante2').show();
		}
	}); 

	$('.treinamento').on('change',function(){
  		var data_de = $("input[name=data_de_avaliacao]").val();
  		var data_ate = $("input[name=data_ate_avaliacao]").val();

  		selectTreinamento(data_de, data_ate,'');
    });

    function selectTreinamento(data_de, data_ate, id_treinamento){
    	
  		if(data_de != "" && data_ate != ""){
            $.ajax({
                url: "/api/ajax?class=SelectTreinamento.php",
                dataType: "html",
                data: {
                    acao: 'busca_treinamento',
                    parametros: {
                        'data_de' : data_de,
                        'data_ate' : data_ate,
                        'id_treinamento' : id_treinamento
                    },
					token: '<?= $request->token ?>'
                },
                 success: function (data) {
					$("select[name=id_treinamento]").empty();                    
					$("select[name=id_treinamento]").append(data);                    
                }
            });
        }
    }
</script>

<?php

function relatorio_listagem($setor, $objetivo, $id_responsavel, $id_participante, $data_de, $data_ate)
{
    if ($setor) {
        $dados_setor = DBRead('', 'tb_perfil_sistema', "WHERE id_perfil_sistema = '".$setor."' ");
        $legenda_setor = $dados_setor[0]['descricao'];
        $filtro_setor1 = " INNER JOIN tb_treinamento_perfil_sistema b ON a.id_treinamento = b.id_treinamento";
		$filtro_setor2 = " AND b.id_perfil_sistema = '".$setor."' ";
		
    } else {
        $legenda_setor = "Todos";
    }

    if ($objetivo) {
        if ($objetivo == 1) {
			$legenda_objetivo = 'Qualificação';
			
        } else {
            $legenda_objetivo = 'Reciclagem';
		}
		
		$filtro_objetivo = " AND a.objetivo = '".$objetivo."' ";
		
    } else {
        $legenda_objetivo = "Todos";
    }

    if ($id_responsavel) {
        $dados_responsavel = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_usuario = '".$id_responsavel."' ", "b.nome");
        $legenda_responsavel = $dados_responsavel[0]['nome'];
        $filtro_responsavel1 = " INNER JOIN tb_treinamento_responsavel c ON a.id_treinamento = c.id_treinamento";
		$filtro_responsavel2 = " AND c.id_usuario = '".$id_responsavel."' ";
		
    } else {
        $legenda_responsavel = "Todos";
    }

    if ($id_participante) {
        $dados_participante = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_usuario = '".$id_participante."' ", "b.nome");
        $legenda_participante = $dados_participante[0]['nome'];
        $filtro_participante1 = " INNER JOIN tb_treinamento_participante d ON a.id_treinamento = d.id_treinamento";
		$filtro_participante2 = " AND d.id_usuario = '".$id_participante."' ";
		
    } else {
        $legenda_participante = "Todos";
	}
	
    $dados_treinamento = DBRead('', 'tb_treinamento a', " ".$filtro_setor1." ".$filtro_responsavel1." ".$filtro_participante1." WHERE a.data_inicio >= '".converteData($data_de)." 00:00:00' AND a.data_inicio <= '".converteData($data_ate)." 23:59:59' ".$filtro_setor2." ".$filtro_responsavel2." ".$filtro_objetivo." ".$filtro_participante2." AND a.status = 1 ORDER BY a.avaliar_em ASC", "a.*");

	$periodo_amostra = "<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> de $data_de até $data_ate</span>";

	$data_hora = converteDataHora(getDataHora());
	echo "<div class=\"col-md-12\" style=\"padding: 0\">";

	echo "<legend style=\"text-align:center;\"><strong>Relatório de Treinamentos - Listagem</strong><br><span style=\"font-size: 14px;\"><strong>Gerado em:</strong> $data_hora</span></legend>";
	echo "<legend style=\"text-align:center;\">$periodo_amostra</legend>";
	echo "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong> Setor - </strong>".$legenda_setor.",<strong> Objetivo - </strong>".$legenda_objetivo.",<strong> Responsável - </strong>".$legenda_responsavel.",<strong> Participante - </strong>".$legenda_participante."";
	echo "</legend>";

	if($dados_treinamento){
		echo '<table class="table table-hover dataTable" style="margin-bottom:0;">
				<thead>
					<tr>
						<th>Nome</th>
						<th>Descrição</th>
						<th>Data Inicial</th>
						<th>Data Final</th>
						<th>Objetivo</th>
						<th>Carga Horária</th>
						<th>Data da Avaliação</th>
						<th>Responsáveis</th>
						<th>Participantes</th>
						<th>Setores</th>
					</tr>
				</thead>
				<tbody>';    
				$cont = 0;

				$carga_horaria_total = 0;

			foreach ($dados_treinamento as $conteudo_treinamento) {
				echo "<tr>";
					echo "<td style='vertical-align: middle;'>".$conteudo_treinamento['nome']."</td>";
					echo "<td style='vertical-align: middle;'>".$conteudo_treinamento['descricao']."</td>";
					echo "<td style='vertical-align: middle;'>".converteDataHora($conteudo_treinamento['data_inicio'], 'data')."</td>";
					echo "<td style='vertical-align: middle;'>".converteDataHora($conteudo_treinamento['data_fim'], 'data')."</td>";
					if($conteudo_treinamento['objetivo'] == 1){
						echo "<td style='vertical-align: middle;'>Qualificação</td>";
					}else{
						echo "<td style='vertical-align: middle;'>Reciclagem</td>";
					}
					echo "<td style='vertical-align: middle;'>".$conteudo_treinamento['carga_horaria']."</td>";
					echo "<td style='vertical-align: middle;'>".converteDataHora($conteudo_treinamento['avaliar_em'])."</td>";

					$carga_horaria_total += $conteudo_treinamento['carga_horaria'];
					
					$dados_responsavel = DBRead('', 'tb_treinamento_responsavel a', "INNER JOIN tb_usuario b ON a.id_usuario = b.id_usuario INNER JOIN tb_pessoa c ON b.id_pessoa = c.id_pessoa WHERE a.id_treinamento = '".$conteudo_treinamento['id_treinamento']."' ORDER BY c.nome", "c.nome");

					if ($dados_responsavel) {
						$responsavel = '';
						foreach ($dados_responsavel as $conteudo_responsavel) {
							if ($responsavel == '') {
								$responsavel .= $conteudo_responsavel['nome'];

							} else {
								$responsavel .= "<br>".$conteudo_responsavel['nome'];
							}
						
						}
						echo "<td style='vertical-align: middle;'>".$responsavel."</td>";
					}

					$dados_participante = DBRead('', 'tb_treinamento_participante a', "INNER JOIN tb_usuario b ON a.id_usuario = b.id_usuario INNER JOIN tb_pessoa c ON b.id_pessoa = c.id_pessoa WHERE a.id_treinamento = '".$conteudo_treinamento['id_treinamento']."' ORDER BY c.nome", "c.nome");

					if ($dados_participante) {
						$participante = '';
						foreach ($dados_participante as $conteudo_participante) {
							if ($participante == '') {
								$participante .= $conteudo_participante['nome'];

							}else{
								$participante .= "<br>".$conteudo_participante['nome'];
							}
						
						}
						echo "<td style='vertical-align: middle;'>".$participante."</td>";
					}

					$dados_setor = DBRead('', 'tb_treinamento_perfil_sistema a', "INNER JOIN tb_perfil_sistema b ON a.id_perfil_sistema = b.id_perfil_sistema WHERE a.id_treinamento = '".$conteudo_treinamento['id_treinamento']."' ORDER BY b.nome", "b.nome AS nome");

					if ($dados_setor) {
						$setor = '';
						foreach ($dados_setor as $conteudo_setor) {
							if ($setor == '') {
								$setor .= $conteudo_setor['nome'];

							} else {
								$setor .= "<br>".$conteudo_setor['nome'];
							}
						
						}
						echo "<td style='vertical-align: middle;'>".$setor."</td>";
					}
				echo "</tr>";
				$cont++;
			}
			echo "</tbody>";
			echo "<tfoot>
					<tr class='active'>
						<td><strong>Total</strong></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td><strong>$carga_horaria_total</strong></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
				  </tfoot>";
		echo "</table><br><br>";
				
		
	}else{

		echo "<div class='col-md-12'>";
			echo "<table class='table table-bordered'>";
				echo "<tbody>";
					echo "<tr>";
						echo "<td class='text-center'> <h4>Não foram encontrados resultados!</h4></td>";
					echo "</tr>";
				echo "</tbody>";
			echo "</table>";
		echo "</div>";
	}
	echo "<script>
			$(document).ready(function(){
				$('.dataTable').DataTable({
					\"language\": {
						\"url\": \"//cdn.datatables.net/plug-ins/1.10.19/i18n/Portuguese-Brasil.json\"
					},			        
					\"searching\": false,
					\"paging\":   false,
					\"info\":     false
				});
			});
		</script>
		
    </div>";
}

function relatorio_avaliacao($data_de, $data_ate, $id_treinamento)
{
	$periodo_amostra = "<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> de $data_de até $data_ate</span>";
	$data_hora = converteDataHora(getDataHora());

	if ($id_treinamento) {
        $dados_id_treinamento = DBRead('', 'tb_treinamento', "WHERE id_treinamento = '".$id_treinamento."' ");
		$filtro_id_treinamento = " AND a.id_treinamento = '".$id_treinamento."' ";
		$legenda_id_treinamento =  "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong> Treinamento - </strong>".$dados_id_treinamento[0]['nome'];
	}

	echo "<div class=\"col-md-12\" style=\"padding: 0\">";
	echo "<legend style=\"text-align:center;\"><strong>Relatório de Treinamentos - Avaliações</strong><br><span style=\"font-size: 14px;\"><strong>Gerado em:</strong> $data_hora</span></legend>";
	echo "<legend style=\"text-align:center;\">$periodo_amostra</legend>";
	echo $legenda_id_treinamento;
	echo "</legend>";

	$dados_treinamento = DBRead('', 'tb_treinamento a', " WHERE a.avaliar_em >= '".converteData($data_de)." 00:00:00' AND a.avaliar_em <= '".converteData($data_ate)." 23:59:59' AND a.status = 1 ".$filtro_id_treinamento." ORDER BY a.avaliar_em ASC", "a.nome, a.id_treinamento, a.avaliar_em");

	if($dados_treinamento){
		foreach ($dados_treinamento as $conteudo_treinamento) {
			echo '
			<table class="table table-hover dataTable" style="margin-bottom:0;">
				<thead>
					<tr>
						<th colspan="6" class="text-center"><h4><b>'.$conteudo_treinamento['nome'].'</b></h4> (Avaliar em: '.converteDataHora($conteudo_treinamento['avaliar_em'], 'data').')</th>
					</tr>
					<tr>
						<th>Participante</th>
						<th>Responsável</th>
						<th>Data da Avaliação</th>
						<th>Resultado</th>
						<th class="col-md-4">Plano de ação</th>
						<th class="col-md-3">Observações</th>
					</tr>
				</thead>
				<tbody>';    
				
				$dados_participante = DBRead('', 'tb_treinamento_participante a', "INNER JOIN tb_usuario b ON a.id_usuario = b.id_usuario INNER JOIN tb_pessoa c ON b.id_pessoa = c.id_pessoa WHERE id_treinamento = '".$conteudo_treinamento['id_treinamento']."' ", "a.id_treinamento_participante, a.obs, c.nome");
				
				if($dados_participante){
					foreach ($dados_participante as $conteudo_participante) {
						$dados_avaliacao = DBRead('', 'tb_treinamento_avaliacao a', "INNER JOIN tb_treinamento_responsavel b ON a.id_treinamento_responsavel = b.id_treinamento_responsavel INNER JOIN tb_usuario c ON b.id_usuario = c.id_usuario INNER JOIN tb_pessoa d ON c.id_pessoa = d.id_pessoa WHERE id_treinamento_participante = '".$conteudo_participante['id_treinamento_participante']."' ", "a.eficaz, a.data_avaliacao, a.plano_acao, d.nome");

						echo "<tr>";
							echo "<td style='vertical-align: middle;'>".$conteudo_participante['nome']."</td>";
							if ($dados_avaliacao) {
								echo "<td style='vertical-align: middle;'>".$dados_avaliacao[0]['nome']."</td>";
								echo "<td style='vertical-align: middle;'>".converteDataHora($dados_avaliacao[0]['data_avaliacao'], 'data')."</td>";
								echo "<td style='vertical-align: middle;'>".$dados_avaliacao[0]['eficaz']."</td>";
								echo "<td style='vertical-align: middle;'>".$dados_avaliacao[0]['plano_acao']."</td>";
								echo "<td style='vertical-align: middle;'>".$conteudo_participante['obs']."</td>";

							} else {
								echo "<td style='vertical-align: middle;'> - </td>";
								echo "<td style='vertical-align: middle;'> - </td>";
								echo "<td style='vertical-align: middle;'> - </td>";
								echo "<td style='vertical-align: middle;'> - </td>";
								echo "<td style='vertical-align: middle;'> - </td>";
							}
							
						echo "</tr>";
					}
				}

				echo "
				</tbody>";
			echo"
			</table>";
		}
	} else {

		echo "<div class='col-md-12'>";
			echo "<table class='table table-bordered'>";
				echo "<tbody>";
					echo "<tr>";
						echo "<td class='text-center'> <h4>Não foram encontrados resultados!</h4></td>";
					echo "</tr>";
				echo "</tbody>";
			echo "</table>";
		echo "</div>";
	}
			
	echo "<script>
			$(document).ready(function(){
				$('.dataTable').DataTable({
					\"language\": {
						\"url\": \"//cdn.datatables.net/plug-ins/1.10.19/i18n/Portuguese-Brasil.json\"
					},			        
					\"searching\": false,
					\"paging\":   false,
					\"info\":     false
				});
			});
		</script>			
			
    </div>";
}

function relatorio_participante($data_de, $data_ate, $id_participante)
{
	$periodo_amostra = "<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> de $data_de até $data_ate</span>";
	$data_hora = converteDataHora(getDataHora());

	if($id_participante){
        $dados_id_participante = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_usuario = '".$id_participante."' ");
		$legenda_id_participante =  "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong> Participante - </strong>".$dados_id_participante[0]['nome'];
	}

	echo "<div class=\"col-md-12\" style=\"padding: 0\">";

	echo "<legend style=\"text-align:center;\"><strong>Relatório de Treinamentos - Avaliações</strong><br><span style=\"font-size: 14px;\"><strong>Gerado em:</strong> $data_hora</span></legend>";
	echo "<legend style=\"text-align:center;\">$periodo_amostra</legend>";
	echo $legenda_id_participante;
	echo "</legend>";

	$dados_participante = DBRead('', 'tb_treinamento_participante a', "INNER JOIN tb_treinamento b ON a.id_treinamento = b.id_treinamento INNER JOIN tb_usuario c ON a.id_usuario = c.id_usuario INNER JOIN tb_pessoa d ON c.id_pessoa = d.id_pessoa WHERE b.status = 1 AND a.id_usuario = '".$id_participante."' AND b.avaliar_em >= '".converteDataHora($data_de, 'data')."' AND b.avaliar_em <= '".converteDataHora($data_ate, 'data')."' ", "a.id_treinamento_participante, d.nome AS nome_participante, b.nome AS nome_treinamento, b.data_inicio, b.data_fim");

	if ($dados_participante) {
		foreach ($dados_participante as $conteudo_treinamento) {
			echo '
			<table class="table table-hover" style="margin-bottom:0;">
				<thead>
					<tr>
						<th colspan="5" class="text-center"><h4><b>' . $conteudo_treinamento['nome_treinamento'] . '</b></h4> (Data de Início: '.converteDataHora($conteudo_treinamento['data_inicio'], 'data').', Data do Término:  '.converteDataHora($conteudo_treinamento['data_fim'], 'data').')</th>
					</tr>
					<tr>
						<th class="col-md-2">Participante</th>
						<th class="col-md-2">Responsável</th>
						<th class="col-md-2">Data da Avaliação</th>
						<th class="col-md-2">Avaliação</th>
						<th class="col-md-4">Plano de ação</th>
					</tr>
				</thead>
				<tbody>';

					$dados_avaliacao = DBRead('', 'tb_treinamento_avaliacao a', "INNER JOIN tb_treinamento_responsavel b ON a.id_treinamento_responsavel = b.id_treinamento_responsavel INNER JOIN tb_usuario c ON b.id_usuario = c.id_usuario INNER JOIN tb_pessoa d ON c.id_pessoa = d.id_pessoa WHERE id_treinamento_participante = '" . $conteudo_treinamento['id_treinamento_participante'] . "' ", "a.eficaz, a.data_avaliacao, a.plano_acao, d.nome");

					echo "<tr>";
					echo "<td style='vertical-align: middle;'>" . $conteudo_treinamento['nome_participante'] . "</td>";
					if ($dados_avaliacao) {
						echo "<td style='vertical-align: middle;'>" . $dados_avaliacao[0]['nome'] . "</td>";
						echo "<td style='vertical-align: middle;'>" . converteDataHora($dados_avaliacao[0]['data_avaliacao'], 'data') . "</td>";
						echo "<td style='vertical-align: middle;'>" . $dados_avaliacao[0]['eficaz'] . "</td>";
						echo "<td style='vertical-align: middle;'>" . $dados_avaliacao[0]['plano_acao'] . "</td>";
					} else {
						echo "<td style='vertical-align: middle;'> - </td>";
						echo "<td style='vertical-align: middle;'> - </td>";
						echo "<td style='vertical-align: middle;'> N/A </td>";
						echo "<td style='vertical-align: middle;'> - </td>";
					}

					echo "</tr>
				</tbody>
			</table>";
		}

	}else{

		echo "<div class='col-md-12'>";
		echo "<table class='table table-bordered'>";
		echo "<tbody>";
		echo "<tr>";
		echo "<td class='text-center'> <h4>Não foram encontrados resultados!</h4></td>";
		echo "</tr>";
		echo "</tbody>";
		echo "</table>";
		echo "</div>";
	}
	echo "
    </div>";
} 
?>					