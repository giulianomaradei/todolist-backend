<?php
require_once(__DIR__."/../class/System.php");

$data_hoje = getDataHora();
$data_hoje = explode(" ", $data_hoje);
$data_hoje = $data_hoje[0];
$primeiro_dia = "01/".$data_hoje[5].$data_hoje[6]."/".$data_hoje[0].$data_hoje[1].$data_hoje[2].$data_hoje[3];

$data_de = (!empty($_POST['data_de'])) ? $_POST['data_de'] :$primeiro_dia;
$data_ate = (!empty($_POST['data_ate'])) ? $_POST['data_ate'] : converteData(getDataHora('data'));
$busca_contrato = (!empty($_POST['busca_contrato'])) ? $_POST['busca_contrato'] : '';
$operador = (!empty($_POST['operador'])) ? $_POST['operador'] : '';
$ajudante = (!empty($_POST['ajudante'])) ? $_POST['ajudante'] : '';
$id_contrato_plano_pessoa = (!empty($_POST['id_contrato_plano_pessoa'])) ? $_POST['id_contrato_plano_pessoa'] : '';
$motivo = (!empty($_POST['motivo'])) ? $_POST['motivo'] : '';
$plano = (!empty($_POST['plano'])) ? "'".join("','", $_POST['plano'])."'" : '';
$tipo_ajuda = (!empty($_POST['tipo_ajuda'])) ? $_POST['tipo_ajuda'] : '';

$gerar = (!empty($_POST['gerar'])) ? 1 : 0;

$id_usuario = $_SESSION['id_usuario'];
$dados = DBRead('', 'tb_usuario', "WHERE id_usuario = '$id_usuario'");
$perfil_sistema = $dados[0]['id_perfil_sistema'];
$id_asterisk_usuario = $dados[0]['id_asterisk'];

if($gerar){
	$collapse = '';
	$collapse_icon = 'plus';
}else{
	$collapse = 'in';
	$collapse_icon = 'minus';
}

if($id_contrato_plano_pessoa){
	$dados_contrato = DBRead('', 'tb_contrato_plano_pessoa a', "INNER JOIN tb_plano b ON a.id_plano = b.id_plano INNER JOIN tb_pessoa c ON a.id_pessoa = c.id_pessoa WHERE a.id_contrato_plano_pessoa = '$id_contrato_plano_pessoa'", "a.*, b.cod_servico, b.nome AS 'plano', c.nome AS 'nome_pessoa'");

	    if($dados_contrato[0]['nome_contrato']){
	        $nome_contrato = " (".$dados_contrato[0]['nome_contrato'].") ";
	    }

	    $contrato = $dados_contrato[0]['nome_pessoa'] . " ". $nome_contrato ." - " . getNomeServico($dados_contrato[0]['cod_servico']) . " - " . $dados_contrato[0]['plano'] . " (" . $dados_contrato[0]['id_contrato_plano_pessoa'] . ")";
}

if($perfil_sistema == 3){
	$display_row_contrato = 'style="display:none;"';
	$display_row_motivo = 'style="display:none;"';
	$display_row_plano = 'style="display:none;"';
	$display_row_atendente = 'style="display:none;"';
	$display_row_ajudante = 'style="display:none;"';
}


?>

<style>
    @media print {
        .noprint { display:none; }
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
	                    <h3 class="panel-title text-left pull-left" style="margin-top: 2px;">Relatórios - Solicitações de Ajuda:</h3>
	                    <div class="panel-title text-right pull-right"><button data-toggle="collapse" data-target="#accordionRelatorio" class="btn btn-xs btn-info" type="button" title="Visualizar filtros"><i id="i_collapse" class="fa fa-<?=$collapse_icon?>"></i></button></div>
	                </div>
	                <div id="accordionRelatorio" class="panel-collapse collapse <?=$collapse?>">
	                	<div class="panel-body">	        
							<div class="row" id="row_periodo">
								<div class="col-md-6">
									<div class="form-group" >
								        <label>*Data Inicial:</label>
								        <input type="text" class="form-control input-sm date calendar" name="data_de" value="<?=$data_de?>" required>
								    </div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
								        <label>*Data Final:</label>
								        <input type="text" class="form-control input-sm date calendar" name="data_ate" value="<?=$data_ate?>" required>
								    </div>
								</div>
							</div>
                			<div class="row" id="row_contrato" <?=$display_row_contrato?>>
		                        <div class="col-md-12">
		                            <div class="form-group">
		                                <label>Contrato (cliente):</label>
		                                <div class="input-group">
		                                    <input class="form-control input-sm" id="busca_contrato" type="text" name="busca_contrato"  value="<?=$contrato?>" placeholder="Informe o nome ou CNPJ..." autocomplete="off" readonly />
		                                    <div class="input-group-btn">
		                                        <button class="btn btn-info btn-sm" id="habilita_busca_contrato" name="habilita_busca_contrato" type="button" title="Clique para selecionar o contrato" style="height: 30px;"><i class="fa fa-search"></i></button>
		                                    </div>
		                                </div>
		                                <input type="hidden" name="id_contrato_plano_pessoa" id="id_contrato_plano_pessoa" value="<?=$id_contrato_plano_pessoa;?>" />
		                            </div>
		                        </div>
		                    </div>      
							
							<div class="row" id="row_motivo" <?=$display_row_motivo?>>
                				<div class="col-md-12">
                					<div class="form-group">
								        <label for="">Motivo:</label>
								        <select name="motivo" class="form-control input-sm">
								        	<option value="">Todos</option>
								            <?php
								            	$dados_motivo = DBRead('','tb_motivo_solicitacao_ajuda',"WHERE status = 1 ORDER BY descricao ASC");
								            	if($dados_motivo){
								            		foreach ($dados_motivo as $dado_motivo) {
														$selected = $motivo == $dado_motivo['id_motivo_solicitacao_ajuda'] ? "selected" : "";
								            			echo "<option value='".$dado_motivo['id_motivo_solicitacao_ajuda']."' ".$selected.">".$dado_motivo['descricao']."</option>";
								            		}
								            	}
								            ?>
								        </select>
								    </div>
                				</div>
                			</div>

							<div class="row" id="row_plano" <?=$display_row_plano?>>
                				<div class="col-md-12">
                					<div class="form-group">								        
                                        <label for="">Plano:</label>
								        <select name="plano[]" class="form-control input-sm" multiple="multiple" size=6>
								            <?php 
								            	$dados_planos = DBRead('', 'tb_plano', "WHERE id_plano != 6 AND id_plano != 5 ORDER BY cod_servico ASC, nome ASC");
								            	if($dados_planos){
								            		foreach ($dados_planos as $conteudo_planos) {
								            			if(preg_match('/'.$conteudo_planos['id_plano'].'/i', $plano)){
								            				$sel_plano = 'selected';
								            			}else{
								            				$sel_plano = '';
								            			}
								            			$nome_select = $conteudo_planos['nome'];
                                                    	$servico_select = getNomeServico($conteudo_planos['cod_servico']);
								            			echo "<option value='".$conteudo_planos['id_plano']."' $sel_plano>$servico_select - $nome_select</option>";
								            		}
								            	}
								            ?>
							            </select>
								    </div>
                				</div>
                			</div>

							<?php if($perfil_sistema != '3'){ ?>
							<div class="row"  id="row_atendente" <?=$display_row_atendente?>>
								<div class="col-md-12">
									<div class="form-group">
								        <label for="">Atendente:</label>
								        <select name="operador" class="form-control input-sm">
								        	<option value="">Todos</option>
								            <?php
								            	$dados_operadores = DBRead('','tb_usuario a',"INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa where a.status = 1 AND a.id_perfil_sistema = 3 ORDER BY b.nome ASC");
								            	if($dados_operadores){
								            		foreach ($dados_operadores as $conteudo_operadores) {
														$selected = $operador == $conteudo_operadores['id_usuario'] ? "selected" : "";
								            			echo "<option value='".$conteudo_operadores['id_usuario']."' ".$selected.">".$conteudo_operadores['nome']."</option>";
								            		}
								            	}
								            ?>
								        </select>
								    </div>
								</div>
							</div>
							<?php } ?>

							<div class="row"  id="row_ajudante" <?=$display_row_ajudante?>>
								<div class="col-md-12">
									<div class="form-group">
								        <label for="">Ajudante:</label>
								        <select name="ajudante" class="form-control input-sm">
								        	<option value="">Todos</option>
								            <?php
								            	$dados_ajudante = DBRead('','tb_solicitacao_ajuda a',"INNER JOIN tb_usuario b ON a.ajudante = b.id_usuario INNER JOIN tb_pessoa c ON b.id_pessoa = c.id_pessoa WHERE ajudante IS NOT NULL GROUP BY ajudante ORDER BY c.nome ASC","a.ajudante, c.nome");
												if($dados_ajudante){
								            		foreach ($dados_ajudante as $dado_ajudante) {	
														$selected = $ajudante == $dado_ajudante['ajudante'] ? "selected" : "";							            		
								            			echo "<option value='".$dado_ajudante['ajudante']."' ".$selected.">".$dado_ajudante['nome']."</option>";
								            		}
								            	}
								            ?>
								        </select>
								    </div>
								</div>
							</div>

							<div class="row"  id="row_tipo_ajuda" <?=$display_row_tipo_ajuda?>>
								<div class="col-md-12">
									<div class="form-group">
								        <label for="">Tipo de Ajuda:</label>
								        <select name="tipo_ajuda" class="form-control input-sm">
								        	<option value="">Todas</option>
											<option value="1" <?php if($tipo_ajuda == '1'){ echo 'selected';}?>>Automática</option>
											<option value="2" <?php if($tipo_ajuda == '2'){ echo 'selected';}?>>Solicitada</option>

								        </select>
								    </div>
								</div>
							</div>
							
		                </div>
	            	</div>
	                <div class="panel-footer">
                        <div class="row">
                            <div id="panel_buttons" class="col-md-12" style="text-align: center">
                                <button class="btn btn-primary" name="gerar" id="gerar" value="1" type="submit" disabled><i class="fa fa-refresh"></i> Gerar</button>
                                <button class="btn btn-warning" name="imprimir" type="button" onclick="window.print();"><i class="fa fa-print"></i> Imprimir</button>
                            </div>
                        </div>
                    </div>
	            </div>
	        </div>
	    </div>
	</form>
	<div id="aguarde" class="alert alert-info text-center">Aguarde, gerando relatório... <i class="fa fa-spinner faa-spin animated"></i></div>	
	<div id="resultado" class="row" style="display:none;">		
		<?php 
		if($perfil_sistema == '3'){
			$operador = $_SESSION['id_usuario'];
		}
		if($gerar){
				relatorio($id_contrato_plano_pessoa, $motivo, $operador, $ajudante, $data_de, $data_ate, $plano, $perfil_sistema, $tipo_ajuda);			
		}
		?>
	</div>
</div>

<script>	
    $('#accordionRelatorio').on('shown.bs.collapse', function(){
       $("#i_collapse").removeClass("fa fa-plus").addClass("fa fa-minus");
    });
    $('#accordionRelatorio').on('hidden.bs.collapse', function(){
       $("#i_collapse").removeClass("fa fa-minus").addClass("fa fa-plus");
    });
    $(document).on('submit', 'form', function(){
        modalAguarde();
    });
    $(document).ready(function(){
	    $('#aguarde').hide();
	    $('#resultado').show();
	    $("#gerar").prop("disabled", false);
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
                            'cod_servico' : 'call_suporte'
                        },
						token: '<?= $request->token ?>'
                    },
                    success: function (data) {
                        response(data);
                    }
                });
            },
            focus: function (event, ui) {
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
    function carregarDadosContrato(id) {
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
                    seleciona_contrato(data[0].id_contrato_plano_pessoa);
                }
            });
        }
    }
    // Função para limpar os campos caso a busca esteja vazia
    function limpaCamposContrato(){
        var busca = $('#busca_contrato').val();
        if (busca == "") {
            $('#id_contrato_plano_pessoa').val('');
        }
    }
    $(document).on('click', '#habilita_busca_contrato', function(){
        $('#id_contrato_plano_pessoa').val('');
        $('#busca_contrato').val('');
        $('#busca_contrato').attr("readonly", false);
        $('#busca_contrato').focus();
    });

    function seleciona_contrato(id_contrato_plano_pessoa){
        $.ajax({
            type: "GET",
            url: "/api/ajax?class=ArvoreContratoBusca.php",
            dataType: "json",
            data: {
                id_contrato_plano_pessoa: id_contrato_plano_pessoa,
				token: '<?= $request->token ?>'
            },
           
        });
    };

</script>

<?php

function relatorio($id_contrato_plano_pessoa, $motivo, $operador ,$ajudante, $data_de, $data_ate, $plano, $perfil_sistema, $tipo_ajuda){


	if($id_contrato_plano_pessoa){
		$dados = DBRead('','tb_contrato_plano_pessoa a',"INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_contrato_plano_pessoa = '".$id_contrato_plano_pessoa."'");
		$empresa_legenda = $dados[0]['nome'];

		if($dados[0]['nome_contrato']){
			$empresa_legenda = $empresa_legenda." (".$dados[0]['nome_contrato'].") ";
		}
		
		$filtro_contrato_plano_pessoa = "AND a.id_contrato_plano_pessoa ='".$id_contrato_plano_pessoa."' ";
	}else{
		$empresa_legenda = "Todos";
	}

	if($motivo){
		$dados_motivo = DBRead('','tb_motivo_solicitacao_ajuda',"WHERE id_motivo_solicitacao_ajuda = '".$motivo."'", "descricao");
		$motivo_legenda = $dados_motivo[0]['descricao'];
		$filtro_motivo = "AND b.id_motivo_solicitacao_ajuda = '".$motivo."'";
	}else{
		$motivo_legenda = 'Todos';
	}
	if($operador){
		$dados_operador = DBRead('','tb_usuario a',"INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_usuario = '".$operador."'", "nome");

		$operador_legenda = $dados_operador[0]['nome'];
		$filtro_atendente = "AND a.atendente = '".$operador."'";
	}else{
		$operador_legenda = 'Todos';
	}
	if($ajudante){
		$dados_ajudante = DBRead('','tb_usuario a',"INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_usuario = '".$ajudante."'", "nome");

		$ajudante_legenda = $dados_ajudante[0]['nome'];
		$filtro_ajudante = "AND a.ajudante = '".$ajudante."'";
	}else{
		$ajudante_legenda = 'Todos';
	}

	if($plano){
		$filtro_plano = "AND a.id_plano IN ($plano)";	
	}	

	if($tipo_ajuda){
		if($tipo_ajuda == 1){
			$filtro_tipo_ajuda = "AND a.ajuda_automatica = '1'";
			$tipo_ajuda_legenda = "Automática";
		}else{
			$filtro_tipo_ajuda = "AND a.ajuda_automatica = '0'";
			$tipo_ajuda_legenda = "Solicitada";
		}
	}else{
		$tipo_ajuda_legenda = 'Todas';
	}


	$data_hoje = getDataHora();
	$data_hoje = converteDataHora($data_hoje);

	$periodo_amostra = "<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> de $data_de até $data_ate</span>";
	$gerado = "<span style=\"font-size: 14px;\"><strong>Gerado em: </strong> $data_hoje</span>";	

	echo "<div class=\"col-md-12\" style=\"padding: 0\">";
	echo "<legend style=\"text-align:center;\"><strong>Relatório de Solicitações de Ajuda</strong><br>$gerado</legend>";
    echo "<legend style=\"text-align:center;\">$periodo_amostra</legend>";
	if($perfil_sistema != '3'){
		echo "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong> Contrato - </strong>".$empresa_legenda.", <strong>Atendente - </strong>".$operador_legenda.", <strong> Motivo - </strong>".$motivo_legenda.", <strong>Ajudante - </strong>".$ajudante_legenda.", <strong>Tipo de Ajuda - </strong>".$tipo_ajuda_legenda."";
	}else{
		echo "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong>Atendente - </strong>".$operador_legenda.", <strong>Tipo de Ajuda - </strong>".$tipo_ajuda_legenda."";
	}

	echo "</legend>";

	$data_de = converteData($data_de);
	$data_ate = converteData($data_ate);

	$dados_ajuda = DBRead('','tb_solicitacao_ajuda a',"INNER JOIN tb_motivo_solicitacao_ajuda b ON a.id_motivo_solicitacao_ajuda = b.id_motivo_solicitacao_ajuda INNER JOIN tb_contrato_plano_pessoa c ON a.id_contrato_plano_pessoa = c.id_contrato_plano_pessoa INNER JOIN tb_pessoa d ON c.id_pessoa = d.id_pessoa WHERE a.id_plano != 6 AND a.id_plano != 5 AND a.data_inicio >= '".$data_de." 00:00:00' AND a.data_inicio <='".$data_ate." 23:59:59' ".$filtro_contrato_plano_pessoa." ".$filtro_motivo." ".$filtro_atendente." ".$filtro_ajudante." ".$filtro_plano." ".$filtro_tipo_ajuda." ","a.data_encerramento, d.nome, b.descricao, a.ajudante, a.atendente, a.id_plano, c.nome_contrato, a.ajuda_automatica");

	registraLog('Relatório de ajuda.','rel','relatorio-ajuda',1,"INNER JOIN tb_motivo_solicitacao_ajuda b ON a.id_motivo_solicitacao_ajuda = b.id_motivo_solicitacao_ajuda INNER JOIN tb_contrato_plano_pessoa c ON a.id_contrato_plano_pessoa = c.id_contrato_plano_pessoa INNER JOIN tb_pessoa d ON c.id_pessoa = d.id_pessoa WHERE a.id_plano != 6 AND a.id_plano != 5 AND a.data_inicio >= '".$data_de." 00:00:00' AND a.data_inicio <='".$data_ate." 23:59:59' ".$filtro_contrato_plano_pessoa." ".$filtro_motivo." ".$filtro_atendente." ".$filtro_ajudante." ".$filtro_plano." ".$filtro_tipo_ajuda." ");

	$qtd_empresas = array();
	$qtd_motivos = array();
	$qtd_atendentes = array();
	$qtd_supervisores = array();
	$qtd_plano = array();
	$qtd_tipo_ajuda = array();

    if($dados_ajuda){ 
		echo '<table class="table table-hover dataTable" style="margin-bottom:0;">
			      <thead>
			        <tr>
			            <th class="text-left">Data</th>
			            <th class="text-left">Contrato</th>
   			            <th class="text-left">Plano</th>
			            <th class="text-left">Motivo</th>
			            <th class="text-left">Atendente</th>
			            <th class="text-left">Ajudante</th>
			            <th class="text-left">Tipo de Ajuda</th>
			        </tr>
			      </thead>
			      <tbody>';         	 
			      
			      foreach ($dados_ajuda as $dado_ajuda) {

			      	$dados_atendente = DBRead('','tb_usuario a',"INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_usuario = '".$dado_ajuda['atendente']."'", "a.id_usuario, b.nome");
			      	
			      	$dados_ajudante = DBRead('','tb_usuario a',"INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_usuario = '".$dado_ajuda['ajudante']."'", "a.id_usuario, b.nome");

			      	$dados_planos = DBRead('','tb_plano',"WHERE id_plano = '".$dado_ajuda['id_plano']."'");

			      	if($dado_ajuda['nome_contrato']){
		                $nome_contrato = " (".$dado_ajuda['nome_contrato'].") ";
		            }else{
		                $nome_contrato = '';
		            }

					if($dado_ajuda['ajuda_automatica'] == 1){
		                $tipo_ajuda = "Automática";
		            }else{
		                $tipo_ajuda = "Solicitada";
		            }
			            
		            $contrato = $dado_ajuda['nome'] . " ". $nome_contrato ;
			      	echo '<tr>
				            <td class="text-left" style="vertical-align: middle">'.converteDataHora($dado_ajuda['data_encerramento']).'</td>                
				            <td class="text-left" style="vertical-align: middle">'.$contrato.'</td>
				            <td class="text-left" style="vertical-align: middle">'.getNomeServico($dados_planos[0]['cod_servico'])." - ".$dados_planos[0]['nome'].'</td>
				            <td class="text-left" style="vertical-align: middle">'.$dado_ajuda['descricao'].'</td>
				            <td class="text-left" style="vertical-align: middle">'.$dados_atendente[0]['nome'].'</td>
				            <td class="text-left" style="vertical-align: middle">'.$dados_ajudante[0]['nome'].'</td>
				            <td class="text-left" style="vertical-align: middle">'.$tipo_ajuda.'</td>
				        </tr>';

				        $qtd_empresas[$contrato] += 1;
				        $qtd_motivos[$dado_ajuda['descricao']] += 1;
					    $qtd_atendentes[$dados_atendente[0]['nome']] += 1;
					    $qtd_supervisores[$dados_ajudante[0]['nome']] += 1;
					    $qtd_plano[getNomeServico($dados_planos[0]['cod_servico'])." - ".$dados_planos[0]['nome']] += 1;
						$qtd_tipo_ajuda[$tipo_ajuda] += 1;

			      }
			      
			    echo "</tbody>
			   <tfoot>
					";
					echo '<tr>';
					
					echo '<tr>';
						echo '<th>Total de Registros: '.sizeof($dados_ajuda).'</th>';
						echo '<th></th>';
						echo '<th></th>';			
						echo '<th></th>';	
						echo '<th></th>';
						echo '<th></th>';
						echo '<th></th>';
					echo '</tr>';
					echo "
				</tfoot> 
			</table>


		
		<br>"; 
		
		if(!$id_contrato_plano_pessoa){
			echo '
				<table class="table table-hover dataTable" style="margin-bottom:0;">
					      <thead>
					        <tr>
					            <th class="text-left col-md-8">Contrato</th>
					            <th class="text-left col-md-4">Quantidade</th>
					        </tr>
					      </thead>
					      <tbody>';  

						 arsort($qtd_empresas);   
						  foreach ($qtd_empresas as $empresa => $qtd) {
						    echo '<tr>';
						    echo '<td style="vertical-align: middle">'.$empresa.'</td>';
						    echo '<td style="vertical-align: middle">'.$qtd.'</td>';
						    echo '</tr>';     
						  }
				          echo '</tbody>
				</table>
				<hr>';       
		}

			echo '
				<table class="table table-hover dataTable" style="margin-bottom:0;">
					      <thead>
					        <tr>
					            <th class="text-left col-md-8">Plano</th>
					            <th class="text-left col-md-4">Quantidade</th>
					        </tr>
					      </thead>
					      <tbody>';  


						 arsort($qtd_plano);   
						  foreach ($qtd_plano as $planos => $qtd) {
						    echo '<tr>';
						    echo '<td style="vertical-align: middle">'.$planos.'</td>';
						    echo '<td style="vertical-align: middle">'.$qtd.'</td>';
						    echo '</tr>';     
						  }
				          echo '</tbody>
				</table>
				<hr>'; 
			
			echo '
				<table class="table table-hover dataTable" style="margin-bottom:0;">
					      <thead>
					        <tr>			       
					            <th class="text-left col-md-8">Motivo</th>
					            <th class="text-left col-md-4">Quantidade</th>
					        </tr>
					      </thead>
					      <tbody>';  

						arsort($qtd_motivos);   
							foreach ($qtd_motivos as $motivo => $qtd) {
							echo '<tr>';
							echo '<td style="vertical-align: middle">'.$motivo.'</td>';
							echo '<td style="vertical-align: middle">'.$qtd.'</td>';
							echo '</tr>';     
							}
				    echo '</tbody>
				</table>
				<hr>'; 
			echo '
				<table class="table table-hover dataTable" style="margin-bottom:0;">
					      <thead>
					        <tr>
					            <th class="text-left col-md-8">Atendente</th>
					            <th class="text-left col-md-4">Quantidade</th>
					        </tr>
					      </thead>
					      <tbody>';  

						 arsort($qtd_atendentes);   
						  foreach ($qtd_atendentes as $atendente => $qtd) {
						    echo '<tr>';
						    echo '<td style="vertical-align: middle">'.$atendente.'</td>';
						    echo '<td style="vertical-align: middle">'.$qtd.'</td>';
						    echo '</tr>';     
						  }
				          echo '</tbody>
				</table>
				<hr>'; 
		
			echo '
				<table class="table table-hover dataTable" style="margin-bottom:0;">
					      <thead>
					        <tr>
					            <th class="text-left col-md-8">Ajudante</th>
					            <th class="text-left col-md-4">Quantidade</th>
					        </tr>
					      </thead>
					      <tbody>';  

						arsort($qtd_supervisores);   
						  foreach ($qtd_supervisores as $supervisor => $qtd) {
						    echo '<tr>';
						    echo '<td style="vertical-align: middle">'.$supervisor.'</td>';
						    echo '<td style="vertical-align: middle">'.$qtd.'</td>';
						    echo '</tr>';     
						  }
				         echo' </tbody>
				</table>
				<hr>';    
				
			echo '
				<table class="table table-hover dataTable" style="margin-bottom:0;">
					      <thead>
					        <tr>
					            <th class="text-left col-md-8">Tipo de Ajuda</th>
					            <th class="text-left col-md-4">Quantidade</th>
					        </tr>
					      </thead>
					      <tbody>';  

						arsort($qtd_tipo_ajuda);   
						  foreach ($qtd_tipo_ajuda as $ajuda => $qtd) {
						    echo '<tr>';
						    echo '<td style="vertical-align: middle">'.$ajuda.'</td>';
						    echo '<td style="vertical-align: middle">'.$qtd.'</td>';
						    echo '</tr>';     
						  }
				         echo' </tbody>
				</table>
				<br>';    
		
			echo "</div>";
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
			";
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

}
?>