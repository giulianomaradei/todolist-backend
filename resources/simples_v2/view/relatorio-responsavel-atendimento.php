<?php
require_once(__DIR__."/../class/System.php");

$gerar = (! empty($_POST['gerar'])) ? 1 : 0;
$data_hoje = converteDataHora(getDataHora('data'), 'data');
$id_responsavel = (! empty($_POST['id_responsavel'])) ? $_POST['id_responsavel'] : '';
$data_de = (! empty($_POST['data_de'])) ? $_POST['data_de'] : $data_hoje;
$data_ate = (! empty($_POST['data_ate'])) ? $_POST['data_ate'] : $data_hoje;
$tipo = (! empty($_POST['tipo'])) ? $_POST['tipo'] : '';
$id_contrato_plano_pessoa = (!empty($_POST['id_contrato_plano_pessoa'])) ? $_POST['id_contrato_plano_pessoa'] : '';
$id_usuario = $_SESSION['id_usuario'];
$dados_usuario = DBRead('', 'tb_usuario', "WHERE id_usuario = '$id_usuario'");
$perfil_usuario = $dados_usuario[0]['id_perfil_sistema'];

if ($id_contrato_plano_pessoa) {
	$dados_contrato = DBRead('', 'tb_contrato_plano_pessoa a', "INNER JOIN tb_plano b ON a.id_plano = b.id_plano INNER JOIN tb_pessoa c ON a.id_pessoa = c.id_pessoa WHERE a.id_contrato_plano_pessoa = '$id_contrato_plano_pessoa'", "a.*, b.cod_servico, b.nome AS 'plano', c.nome AS 'nome_pessoa'");

	if ($dados_contrato[0]['nome_contrato']) {
		$nome_contrato = " (" . $dados_contrato[0]['nome_contrato'] . ") ";
	}

	$contrato = $dados_contrato[0]['nome_pessoa'] . " " . $nome_contrato . " - " . getNomeServico($dados_contrato[0]['cod_servico']) . " - " . $dados_contrato[0]['plano'] . " (" . $dados_contrato[0]['id_contrato_plano_pessoa'] . ")";
}

if ($gerar) {
    $collapse = '';
    $collapse_icon = 'plus';
} else {
    $collapse = 'in';
    $collapse_icon = 'minus';
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
							style="margin-top: 2px;">Relatório de Responsáveis pelos Atendimentos:</h3>
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
                                        <label>Responsável:</label>
                                        <select name="id_responsavel" id="id_responsavel" class="form-control input-sm" onchange="call_busca_ajax();">
                                            <?php
												if($perfil_usuario == 3){
													$filtro_atendente = "WHERE b.id_usuario = '".$id_usuario."' AND b.status = 1";
												} else {
													echo '<option value="">Todos</option>';
													$filtro_atendente = "WHERE b.status = 1";
												}
												$filtro_busca = "INNER JOIN tb_usuario b ON a.id_usuario = b.id_usuario INNER JOIN tb_pessoa c ON b.id_pessoa = c.id_pessoa $filtro_atendente GROUP BY a.id_usuario ORDER BY c.nome ASC";
												
												$dados_id_responsavel = DBRead('', 'tb_responsavel_atendimento a', $filtro_busca, "a.id_usuario, c.nome");
												if ($dados_id_responsavel) {
													foreach ($dados_id_responsavel as $conteudo_id_responsavel) {
														$selected = $id_responsavel == $conteudo_id_responsavel['id_usuario'] ? "selected" : "";
														echo "<option value='" . $conteudo_id_responsavel['id_usuario'] . "' " . $selected . ">" . $conteudo_id_responsavel['nome'] . "</option>";
													}
												}
                                            ?>
                                        </select>
                                    </div>
                                </div> 
                            </div>

							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label>Data Inicial:</label> 
										<input type="text" class="form-control date calendar input-sm" name="data_de" value="<?=$data_de?>" id="data_de">
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label>Data Final:</label> 
										<input type="text" class="form-control date calendar input-sm" name="data_ate" value="<?=$data_ate?>" id="data_ate">
									</div>
								</div>
							</div>

							<div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Tipo:</label>
                                        <select name="tipo" id="tipo" class="form-control input-sm">
                                            <option value="">Todos</option>
                                            <option value="1" <?php if($tipo == '1'){ echo 'selected';}?>>Via Telefone</option>
                                            <option value="2" <?php if($tipo == '2'){ echo 'selected';}?>>Via Texto</option>
                                        </select>
                                    </div>
                                </div>
							</div>

							<div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Contrato:</label>
                                        <div class="input-group">
                                            <input class="form-control input-sm" id="busca_contrato" type="text" name="busca_contrato" value="<?=$contrato?>" placeholder="Informe o nome ou CNPJ..." autocomplete="off" readonly/>
                                            <div class="input-group-btn">
                                                <button class="btn btn-info btn-sm" id="habilita_busca_contrato" name="habilita_busca_contrato" type="button" title="Clique para selecionar o contrato" style="height: 30px;"><i class="fa fa-search"></i></button>
                                                <?php echo "<input type='hidden' name='id_contrato_plano_pessoa' id='id_contrato_plano_pessoa' value='$id_contrato_plano_pessoa' />";?>

                                            </div>
                                        </div>
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
			relatorio_responsavel_atendimento($id_responsavel, $data_de, $data_ate, $tipo, $id_contrato_plano_pessoa);
		}
	?>
	</div>
</div>
<script>

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
	});

    $(document).on('submit', 'form', function(){
        modalAguarde();
	});
	
	// Atribui evento e função para limpeza dos campos
	$('#busca_contrato').on('input', limpaCamposContrato);
	
	// Dispara o Autocomplete da pessoa a partir do segundo caracter
    $("#busca_contrato").autocomplete({
		minLength: 2,
		source: function (request, response) {
			$.ajax({
				url: "/api/ajax?class=ContratoAutocomplete.php",
				dataType: "json",
				data: {
					acao: 'autocomplete',
					parametros: { 
						'nome' : $('#busca_contrato').val(),
					},
					token: '<?= $request->token ?>'
				},
				success: function (data) {
					response(data);
				}
			});
		},
		focus: function (event, ui){
			$("#busca_contrato").val(ui.item.nome + " " + ui.item.nome_contrato +" - " + ui.item.servico + " - " + ui.item.plano + " (" + ui.item.id_contrato_plano_pessoa + ")");
			carregarDadosContrato(ui.item.id_contrato_plano_pessoa);
			return false;
		},
		select: function (event, ui) {
			$("#busca_contrato").val(ui.item.nome + " "+ ui.item.nome_contrato + " - " + ui.item.servico + " - " + ui.item.plano + " (" + ui.item.id_contrato_plano_pessoa + ")");
			$('#busca_contrato').attr("readonly", true);
			return false;
		}
	})
	.autocomplete("instance")._renderItem = function(ul, item){
		if(!item.razao_social){
			item.razao_social = '';
		}
		if(!item.cpf_cnpj){
			item.cpf_cnpj = '';
		}
		if(!item.nome_contrato){
			item.nome_contrato = '';
		}else{
			item.nome_contrato = ' ('+item.nome_contrato+') '; 
		}
		return $("<li>").append("<a><strong>"+item.id_contrato_plano_pessoa + " - " + item.nome + ""+item.nome_contrato+" </strong><br>" +item.razao_social+ "<br>" +item.cpf_cnpj+ "<br>" + item.servico + " - " + item.plano + " (" + item.id_contrato_plano_pessoa + ")" + "</a><hr style='margin-bottom: 0px;'>").appendTo(ul);
    };
    // Função para carregar os dados da consulta nos respectivos campos
    function carregarDadosContrato(id){
        var busca = $('#busca_contrato').val();
        if(busca != "" && busca.length >= 2){
            $.ajax({
                url: "/api/ajax?class=ContratoAutocomplete.php",
                dataType: "json",
                data: {
                    acao: 'consulta',
                    parametros: {
                        'id' : id,
                    },
					token: '<?= $request->token ?>'
                },
                success: function (data) {
                    $('#id_contrato_plano_pessoa').val(data[0].id_contrato_plano_pessoa);
                }
            });
        }
    }
    // Função para limpar os campos caso a busca esteja vazia
    function limpaCamposContrato() {
        var busca = $('#busca_contrato').val();
        if (busca == "") {
            $('#id_contrato_plano_pessoa').val('');
        }
    }
    $(document).on('click', '#habilita_busca_contrato', function () {
        $('#id_contrato_plano_pessoa').val('');
        $('#busca_contrato').val('');
        $('#busca_contrato').attr("readonly", false);
        $('#busca_contrato').focus();
    });

</script>
<?php

function relatorio_responsavel_atendimento($id_responsavel, $data_de, $data_ate, $tipo, $id_contrato_plano_pessoa){
	
    if($id_responsavel){
        $dados_id_responsavel = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_usuario = '".$id_responsavel."' ", "b.nome");
        $legenda_id_responsavel = $dados_id_responsavel[0]['nome'];
        $filtro_id_responsavel = " AND a.id_usuario = '".$id_responsavel."' ";
    }else{
        $legenda_id_responsavel = "Todos";
	}
	
	if($tipo){
		if($tipo == 1){
			$legenda_tipo = "Via Telefone";
			$filtro_tipo = " AND a.tipo = '0' ";
		}else if($tipo == 2){
			$legenda_tipo = "Via Texto";
			$filtro_tipo = " AND a.tipo = '1' ";
		}
	}else{
		$legenda_tipo = "Todos";
	}

	if($id_contrato_plano_pessoa){
        $filtro_contrato_plano_pessoa = "AND a.id_contrato_plano_pessoa = '".$id_contrato_plano_pessoa."'";
        $dados_contrato  =  DBRead('','tb_contrato_plano_pessoa a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_contrato_plano_pessoa = '".$id_contrato_plano_pessoa."'", "b.nome");
        $legenda_contrato = $dados_contrato[0]['nome'];
    }else{
        $legenda_contrato = 'Todos';
    }
      	
    $dados_responsavel_atendimento = DBRead('', 'tb_responsavel_atendimento a', "INNER JOIN tb_usuario b ON a.id_usuario = b.id_usuario INNER JOIN tb_pessoa c ON b.id_pessoa = c.id_pessoa WHERE a.data_hora_inserido >= '".converteDataHora($data_de)." 00:00:00' AND a.data_hora_inserido <= '".converteDataHora($data_ate)." 23:59:59' ".$filtro_id_responsavel." ".$filtro_tipo." ".$filtro_contrato_plano_pessoa." ", "a.*, c.nome");
    
    $data_hora = converteDataHora(getDataHora());
    
	echo "<div class=\"col-md-12\" style=\"padding: 0\">";
	echo "<legend style=\"text-align:center;\"><strong>Relatório de Responsáveis pelos Atendimentos:</strong><br><span style=\"font-size: 14px;\"><strong>Gerado em:</strong> $data_hora</span></legend>";
	echo "<legend style=\"text-align:center;\"><span class=\"noprint\" style=\"font-size: 14px;\"><strong>Período da Amostra:</strong> De $data_de até $data_ate</span></legend>";
	echo "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong> Responsável - </strong>".$legenda_id_responsavel.",<strong> Tipo - </strong>".$legenda_tipo.",<strong> Contrato - </strong>".$legenda_contrato." ";
	echo "</legend>"; 
    
    if($dados_responsavel_atendimento){

        echo "<table class='table table-hover dataTable' style='font-size: 14px;'>";
			echo "<thead>";
				echo "<tr>";
					echo "<th>Responsável</th>";
					echo "<th>Data e Hora da Responsabilidade</th>";
					echo "<th>Data e Hora da Finalização</th>";
					echo "<th>Usuário que Inseriu</th>";
					echo "<th>Usuário que Finalizou</th>";
					echo "<th>Tipo</th>";
					echo "<th>Grupo de atendimento via Chat</th>";
					echo "<th>Contratos no Grupo de atendimento via Chat</th>";
				echo "</tr>";
			echo "</thead>";
            echo "<tbody>";

			$auxiliar_id_usuario = '';
			$auxiliar_data_hora_inserido = '';
			$auxiliar_id_grupo_atendimento_chat = '';

            foreach ($dados_responsavel_atendimento as $conteudo_responsavel_atendimento) {
					
					$nome = $conteudo_responsavel_atendimento['nome'];
					$data_hora_inserido = converteDataHora($conteudo_responsavel_atendimento['data_hora_inserido']);
					$dados_usuario_inserido = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_usuario = '".$conteudo_responsavel_atendimento['id_usuario_inserido']."'", "b.nome");
					$usuario_inserido = $dados_usuario_inserido[0]['nome'];
					
					if($conteudo_responsavel_atendimento['id_usuario_removido']){
						$dados_usuario_removido = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_usuario = '".$conteudo_responsavel_atendimento['id_usuario_removido']."'", "b.nome");
						$usuario_removido = $dados_usuario_removido[0]['nome'];
					}else{
						if($conteudo_responsavel_atendimento['data_hora_removido']){
							$usuario_removido = 'Atingiu o Tempo Limite';
						}else{
							$usuario_removido = 'Responsável no Momento';
						}
					}
					
					if($conteudo_responsavel_atendimento['data_hora_removido']){
						$data_hora_removido = converteDataHora($conteudo_responsavel_atendimento['data_hora_removido']);
					}else{
						$data_hora_removido = 'Responsável no Momento';
					}
					
					if($conteudo_responsavel_atendimento['tipo'] == 1){
						$tipo_canal = "Via Texto";
					}else{
						$tipo_canal = "Via Telefone";
					}
				
					$grupo_atendimento_chat = "-";
					$contratos = '-';

					if($conteudo_responsavel_atendimento['id_grupo_atendimento_chat']){		
						$contratos = '';
					
						$dados_grupo_atendimento_chat = DBRead('', 'tb_grupo_atendimento_chat',"WHERE id_grupo_atendimento_chat = '".$conteudo_responsavel_atendimento['id_grupo_atendimento_chat']."' ");
						$grupo_atendimento_chat = $dados_grupo_atendimento_chat[0]['nome'];

						$dados_grupo_atendimento_chat_contrato = DBRead('', 'tb_grupo_atendimento_chat_contrato a',"INNER JOIN tb_contrato_plano_pessoa b ON a.id_contrato_plano_pessoa = b.id_contrato_plano_pessoa INNER JOIN tb_pessoa c ON b.id_pessoa = c.id_pessoa WHERE a.id_grupo_atendimento_chat = '".$conteudo_responsavel_atendimento['id_grupo_atendimento_chat']."' ORDER BY c.nome ASC", "c.nome");

						foreach ($dados_grupo_atendimento_chat_contrato as $conteudo) {
							$contratos .= $conteudo['nome']."<br>";
						}
						$grupo_atendimento_chat = $dados_grupo_atendimento_chat[0]['nome'];

						if( ($conteudo_responsavel_atendimento['id_usuario'] != $auxiliar_id_usuario) || ($conteudo_responsavel_atendimento['data_hora_inserido'] != $auxiliar_data_hora_inserido) || ($conteudo_responsavel_atendimento['id_grupo_atendimento_chat'] != $auxiliar_id_grupo_atendimento_chat)){
							$auxiliar_id_usuario = $conteudo_responsavel_atendimento['id_usuario'];
							$auxiliar_data_hora_inserido = $conteudo_responsavel_atendimento['data_hora_inserido'];
							$auxiliar_id_grupo_atendimento_chat = $conteudo_responsavel_atendimento['id_grupo_atendimento_chat'];
	
							echo "<tr>";
								echo "<td style='vertical-align: middle;'>$nome</td>";
								echo "<td style='vertical-align: middle;'>$data_hora_inserido</td>";
								echo "<td style='vertical-align: middle;'>$data_hora_removido</td>";
								echo "<td style='vertical-align: middle;'>$usuario_inserido</td>";
								echo "<td style='vertical-align: middle;'>$usuario_removido</td>";
								echo "<td style='vertical-align: middle;'>$tipo_canal</td>";
								echo "<td style='vertical-align: middle;'>$grupo_atendimento_chat</td>";
								echo "<td style='vertical-align: middle;'>$contratos</td>";
							echo "</tr>";
						}
					}else{
						echo "<tr>";
							echo "<td style='vertical-align: middle;'>$nome</td>";
							echo "<td style='vertical-align: middle;'>$data_hora_inserido</td>";
							echo "<td style='vertical-align: middle;'>$data_hora_removido</td>";
							echo "<td style='vertical-align: middle;'>$usuario_inserido</td>";
							echo "<td style='vertical-align: middle;'>$usuario_removido</td>";
							echo "<td style='vertical-align: middle;'>$tipo_canal</td>";
							echo "<td style='vertical-align: middle;'>$grupo_atendimento_chat</td>";
							echo "<td style='vertical-align: middle;'>$contratos</td>";
						echo "</tr>";
					}
			}
            echo "</tbody>";
		echo "</table>";
		
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

?>					