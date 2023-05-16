<?php
require_once(__DIR__."/System.php");
$parametros = (!empty($_POST['parametros'])) ? $_POST['parametros'] : '';
$letra = addslashes($parametros['nome']);
$situacao = addslashes($parametros['situacao']);
$agrupador = addslashes($parametros['agrupador']);
$id_busca = addslashes($parametros['id_busca']);

echo '
<style>
.body_conta_pagar {
    display:block;
    height:430px;
    overflow:auto;
}
thead, tbody tr {
    display:table;
    width:100%;
    table-layout:fixed;
}

</style>';
if($id_busca){
	$filtros_query = "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa INNER JOIN tb_natureza_financeira c ON a.id_natureza_financeira = c.id_natureza_financeira INNER JOIN tb_natureza_financeira_agrupador d ON c.id_natureza_financeira_agrupador = d.id_natureza_financeira_agrupador INNER JOIN tb_caixa e ON a.id_caixa = e.id_caixa WHERE a.situacao = 'aberta' AND a.id_conta_pagar = '".$id_busca."' ORDER BY a.data_vencimento ASC";
}else{
	if($agrupador){
	    $filtro_agrupador = " AND c.id_natureza_financeira = '".$agrupador."' ";
	}

	if($situacao){
	    $filtro_situacao = " AND a.situacao = '".$situacao."' ";
	}

	if($parametros['data_de'] && $parametros['data_ate']){
	    $data_de = converteData($parametros['data_de']);
	    $data_ate = converteData($parametros['data_ate']);

	    $data_de = $data_de.' 00:00:00';
	    $data_ate = $data_ate.' 23:59:59';

	    $filtro_data = "AND (a.data_vencimento BETWEEN '$data_de' AND '$data_ate')";
	    //$filtro_data = "AND (a.data_emissao BETWEEN '$data_de' AND '$data_ate' OR a.data_vencimento BETWEEN '$data_de' AND '$data_ate')";

	}else if($parametros['data_de'] && !$parametros['data_ate']){
	    $data_de = converteData($parametros['data_de']);

	    $data_de = $data_de.' 00:00:00';

	    $filtro_data = "AND (a.data_vencimento >= '$data_de')";
	    //$filtro_data = "AND (a.data_emissao >= '$data_de' OR a.data_vencimento >= '$data_de')";

	}else if(!$parametros['data_de'] && $parametros['data_ate']){

	    $data_ate = converteData($parametros['data_ate']);

	    $data_ate = $data_ate.' 23:59:59';

	    $filtro_data = "AND (a.data_vencimento <= '$data_ate')";
	    //$filtro_data = "AND (a.data_emissao <= '$data_ate' OR a.data_vencimento <= '$data_ate')";
	}

	// Informações da query

	$filtros_query = "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa INNER JOIN tb_natureza_financeira c ON a.id_natureza_financeira = c.id_natureza_financeira INNER JOIN tb_natureza_financeira_agrupador d ON c.id_natureza_financeira_agrupador = d.id_natureza_financeira_agrupador INNER JOIN tb_caixa e ON a.id_caixa = e.id_caixa WHERE a.situacao = 'aberta' AND (b.nome LIKE '%$letra%' OR b.razao_social LIKE '%$letra%') ".$filtro_data." ".$filtro_agrupador." ".$filtro_situacao." ORDER BY a.data_vencimento ASC";

}

###################################################################################
// INICIO DO CONTEÚDO
//
$dados = DBRead('', 'tb_conta_pagar a', $filtros_query, "a.*, b.nome, c.nome AS nome_natureza, d.nome AS nome_natureza_agrupador, e.aceita_movimentacao");
echo '
	<div class="row">
        <div class="col-md-12">
            <div class="panel-group" id="accordionVinculos" role="tablist">
                <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    <div class="panel-title text-right pull-right">
                        <a href="/api/iframe?token=<?php echo $request->token ?>&view=conta-pagar-form"><button class="btn btn-xs btn-primary"><i class="fa fa-plus"></i> Nova</button></a>
                    </div>
                </div>';

if (!$dados) {
	echo '<div class="panel-body" styx height: 525px !important;">';
		echo "<p class='alert alert-warning' style='text-align: center'>";
		if(!$letra) {
				echo "Não foram encontrados registros!";
		}else{
			echo "Nenhum resultado encontrado na busca por \"<strong>$letra</strong>\"";
		}
		echo "</p>";
	echo '</div>';
}else{
	
			echo '                                
                <form method="post" action="class/ContaPagar.php" id="controle_conta_pagar" style="margin-bottom: 0;">
                    <div class="panel-body" styx height: 525px !important;">';
					echo "<div class='table-condensed'>";
						echo "<table class='table table-condensed' style='font-size: 14px;'>";
							echo "<thead>";
								echo "<tr>";
								    echo "<th class='col-md-1 text-center' style='vertical-align: middle;'><input type='checkbox' id='checkTodos' name='checkTodos'></th>";
								    echo "<th class='col-md-1'>#</th>";
								    echo "<th class='col-md-4'>Descrição</th>";
								    echo "<th class='col-md-2 text-center'>Data de Emissão</th>";
								    echo "<th class='col-md-2 text-center'>Data de Vencimento</th>";
								    echo "<th class='col-md-1 text-center'>Valor</th>";
								    echo "<th class='col-md-1 text-center'>Opções</th>";
							    echo "</tr>";
							echo "</thead>";
							echo "<tbody class='body_conta_pagar'>";

							echo '<input type="hidden" id="id_antigo_tabela_conta_pagar" value="'.$dados[0]['id_conta_pagar'].'">';
							echo '<input type="hidden" id="data_pagamento_hidden_conta_pagar" name="data_pagamento_hidden_conta_pagar">';
							echo '<input type="hidden" id="descricao_baixar_hidden_conta_pagar" name="descricao_baixar_hidden_conta_pagar">';

							$cont_registros_conta_pagar = 0;
							$soma_total_conta_pagar = 0;
							foreach ($dados as $conteudo) {
								
								$cont_registros_conta_pagar++;

						        $id_conta_pagar = $conteudo['id_conta_pagar'];

						        $nome = $conteudo['nome'];

						        $natureza = $conteudo['nome_natureza_agrupador']." (".$conteudo['nome_natureza'].")";
						        $nome_natureza = $conteudo['nome_natureza'];
						        
						        $data_emissao = converteData($conteudo['data_emissao']);
						        
						        $data_vencimento = $conteudo['data_vencimento'];
								$data_hoje = getDataHora('data');
								
								$aceita_movimentacao = $conteudo['aceita_movimentacao'];

						        if(strtotime($data_hoje) > strtotime($data_vencimento)){
									$data_do_vencimento = "<td class='col-md-2 text-center modal_contas_pagar' style='vertical-align: middle;' attr-id='$id_conta_pagar'><strong class=' text-danger'>".converteData($conteudo['data_vencimento'])."<strong></td>";
									
						        }else if(strtotime($data_hoje) == strtotime($data_vencimento)){
						        	$data_do_vencimento = "<td class='col-md-2 text-center modal_contas_pagar' style='vertical-align: middle;' attr-id='$id_conta_pagar' ><strong class=' text-warning'>".converteData($conteudo['data_vencimento'])."<strong></td>";
						        }else{
						        	$data_do_vencimento = "<td class='col-md-2 text-center modal_contas_pagar' style='vertical-align: middle;' attr-id='$id_conta_pagar' >".converteData($conteudo['data_vencimento'])."</td>";
						        }
						        $soma_total_conta_pagar += $conteudo['valor'];
						        $valor = converteMoeda($conteudo['valor']);

						        $situacao = ucfirst($conteudo['situacao']);

						        if($conteudo['tipo'] == 'entrada'){
						        	$tipo = '<span class="label label-success" style="display: inline-block; min-width: 50px;">Entrada</span>';
						        	$tipo_modal = '<span class="label label-success" style="display: inline-block; min-width: 100px;"> Entrada </span>';
						        }else{
						        	$tipo = '<span class="label label-danger" style="display: inline-block; min-width: 50px;"> Saída </span>';
						        	$tipo_modal = '<span class="label label-danger" style="display: inline-block; min-width: 100px;"> Saída </span>';
						        }

								$origem = 'Conta Pagar';

								if($aceita_movimentacao == 0){
						        	echo "<tr class='warning' id='tr_id_conta_pagar' value='".$id_conta_pagar."' style='cursor: pointer;'>";
								}else{
									if($dados[0]['id_conta_pagar'] == $id_conta_pagar){
										echo "<tr class='info' id='tr_id_conta_pagar' value='".$id_conta_pagar."' style='cursor: pointer;'>";
									}else{
										echo "<tr class='default' id='tr_id_conta_pagar' value='".$id_conta_pagar."' style='cursor: pointer;'>";
									}	
								}
									
									echo "<td class='col-md-1 text-center' style='vertical-align: middle;'><input name='selecionar_conta_pagar[]'' type='checkbox' value='$id_conta_pagar' id='$id_conta_pagar'></td>";
									
									echo "<td class='col-md-1 modal_contas_pagar' style='vertical-align: middle;' name='td_nome_conta_pagar[]' attr-id='$id_conta_pagar'>".$id_conta_pagar."</td>";
									
									if($aceita_movimentacao == 0){
										echo "
										<td class='col-md-4 modal_contas_pagar' attr-id='$id_conta_pagar'><i class='fas fa-donate'></i> ".$natureza." - (Não aceita movimentação)<br>
											<i class='fa fa-address-card-o'></i> ".$nome."<br>";
											if($conteudo['descricao']){
												echo "<i class='fa fa-list-alt'></i> ".limitarTexto($conteudo['descricao'], 40);
											}
										echo "</td>";
									}else{
										echo "
										<td class='col-md-4 modal_contas_pagar' attr-id='$id_conta_pagar'><i class='fas fa-donate'></i> ".$natureza."<br>
											<i class='fa fa-address-card-o'></i> ".$nome."<br>";
											if($conteudo['descricao']){
												echo "<i class='fa fa-list-alt'></i> ".limitarTexto($conteudo['descricao'], 40);
											}
										echo "</td>";
									}

							        
									
									echo "<td class='col-md-2 text-center modal_contas_pagar' style='vertical-align: middle;' attr-id='$id_conta_pagar'>".$data_emissao."</td>";
									
							        echo $data_do_vencimento;
							        echo "<td class='col-md-1 text-center modal_contas_pagar' style='vertical-align: middle;' attr-id='$id_conta_pagar'>R$ ".$valor."</td>";
									
									echo "<td class='col-md-1 text-center' style='vertical-align: middle;'>";
                                        echo "<a href='/api/iframe?token=<?php echo $request->token ?>&view=conta-pagar-form&alterar=".$id_conta_pagar."' title='Alterar'><i class='fa fa-pencil' style='color:#0174DF;'></i></a>";	                                        								
                                    echo "</td>";                           
                                    
                                echo "</tr>";
						    }
							echo "</tbody>";
						echo "</table>";

						echo "<hr>";
						echo '
						<div class="row">
			                <div class="col-sm-6">
			                    <div class="form-group">
			                        <h5 class="text-left">Total de registros: <strong>'.$cont_registros_conta_pagar.'</strong></h5>
			                    </div>
			                </div>
			                <div class="col-sm-6">
			                    <div class="form-group">
			                        <h5 class="text-right">Valor Total: <strong>'.converteMoeda($soma_total_conta_pagar).'</strong></h5>
			                    </div>
			                </div>
					    </div> ';

					echo "</div>";
				echo "</div>";

				echo '
					<div class="panel-footer">
	                    <div class="row">
	                        <div class="col-md-12" style="text-align: center">
                                <input type="hidden" id="operacao_conta_pagar" name="abc" value="1"/>
                                <button class="btn btn-danger" id="baixar_conta_pagar" type="button" data-toggle="modal" data-target="#confirm_submit_baixar_conta_pagar" disabled><i class="fas fa-times"></i> Baixar</button>
                                <button class="btn btn-success" id="quitar_conta_pagar" type="button" data-toggle="modal" data-target="#confirm_submit_conta_pagar" disabled><i class="fas fa-check"></i> Quitar</button>
                                <button class="btn btn-warning" id="clonar_conta_pagar" type="submit" disabled><i class="far fa-clone"></i> Clonar</button>
                            </div>
	                    </div>
	                </div>
	            </div>';
                                
	            echo '
	            	<div class="modal fade" id="confirm_submit_conta_pagar" role="dialog">
					    <div class="modal-dialog">
					      	<!-- Modal content-->
					      	<div class="modal-content">
					        	<div class="modal-header">
							        <h3 class="panel-title text-center pull-center" style="margin-top: 2px; font-size: 150%;">Data do Pagamento (Quitação)</h3>
					        	</div>
					        	<div class="modal-body">
					          		<div class="row">
	                                    <div class="col-md-12">
				                            <div class="form-group has-feedback">
				                                <label class="control-label">*Data: </label>
				                                <input class="form-control date calendar hasDatePicker hasDatepicker" type="text" id="data_pagamento_conta_pagar" name="data_pagamento_conta_pagar" placeholder="dd/mm/aaaa" autocomplete="off" maxlength="10">
				                            </div>
				                        </div>
				                    </div>
					        	</div>
					        	<div class="modal-footer">
					          		<a href="#" id="submit_conta_pagar" class="btn btn-primary"><i class="fas fa-check"></i> Quitar</a>
					        	</div>
					      	</div>      
					    </div>
					</div>';

					//MODAL baixar CONTA PAGAR
					echo '
						<div class="modal fade" id="confirm_submit_baixar_conta_pagar" role="dialog" >
	                        <div class="modal-dialog">
	                            <div class="modal-content">
	                                <div class="modal-header">
	                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	                                    <h4 class="modal-title" id="myModalLabel">ATENÇÃO!</h4>
	                                </div>
	                                <div class="modal-body">
	                                    <div class="row">
		                                    <div class="col-md-12">
					                            <div class="form-group has-feedback">
					                                 <label>*Descrição:</label>
					                                    <textarea name="descricao_baixar_conta_pagar" id="descricao_baixar_conta_pagar" rows="5" cols="100" class="form-control"></textarea>
					                            </div>
					                        </div>
					                    </div>
	                                </div>

	                                <div class="modal-footer">
				                        <button class="btn btn-primary" id="submit_baixar_conta_pagar" type="button"><i class="fas fa-check"></i> Baixar</button>
	                                </div>
	                            </div>
	                        </div>
	                    </div>
	                    ';

	            echo'
	            </form>';
}
echo '
			</div>
	    </div>
	</div>';

// FIM DO CONTEUDO
###################################################################################
?>

<script>

$('#submit_baixar_conta_pagar').click(function(){
	$('#operacao_conta_pagar').attr('name', 'baixar_conta_pagar');
	$('#descricao_baixar_hidden_conta_pagar').val($('#descricao_baixar_conta_pagar').val());

	if( $('#descricao_baixar_conta_pagar').val() && $('#descricao_baixar_conta_pagar').val() != '' ){
		
		var cont =0;
		$("[name ='selecionar_conta_pagar[]']").each(function(){
	        if ($(this).is(':checked')){
	        	cont = cont+1;
	        }
	    });
	    if(!confirm('Dar baixa em '+cont+' conta(s) a pagar?')) {
			return false;
		}else{
	    	$('#controle_conta_pagar').submit();
		}
	}else{
	    alert('O campo de descrição deve estar preenchido!');
	}

	

});
$('#submit_conta_pagar').click(function(){
	
	if($('input[name="data_pagamento_conta_pagar"]').val() && $('input[name="data_pagamento_conta_pagar"]').val() != ''){
		$('#data_pagamento_hidden_conta_pagar').val($('input[name="data_pagamento_conta_pagar"]').val());

		var cont =0;
		$("[name ='selecionar_conta_pagar[]']").each(function(){
	        if ($(this).is(':checked')){
	        	cont = cont+1;
	        }
	    });
	    if(!confirm('Quitar '+cont+' conta(s) a pagar?')) {
			return false;
		}else{
	    	$('#controle_conta_pagar').submit();
		}
	}else{
		alert('Insira uma data!');
		return false;
	}
		return false;
});

$(document).on('click', '#clonar_conta_pagar', function(){
	$('#operacao_conta_pagar').attr('name', 'clonar_conta_pagar');

	var cont =0;
	$("[name ='selecionar_conta_pagar[]']").each(function(){
        if ($(this).is(':checked')){
        	cont = cont+1;
        }
    });

	$('#controle_conta_pagar').attr('action', "/v2//api/iframe?token=<?php echo $request->token ?>&view=controle-contas-pagar-clonar-form").submit();
});

$(document).on('click', '#quitar_conta_pagar', function(){
	configuraDatepicker();
    configuraMascaras();

	$('#operacao_conta_pagar').attr('name', 'quitar_conta_pagar');

});

$(document).on('click', '#checkTodos', function () {

    if ($(this).is(':checked')){
		if($('[name ="selecionar_conta_pagar[]"]').parent().parent().hasClass('warning')){
			$("#baixar_conta_pagar").attr("disabled", false);
			$("#clonar_conta_pagar").attr("disabled", true);
			$("#quitar_conta_pagar").attr("disabled", true);

		}else{
			$("#baixar_conta_pagar").attr("disabled", false);
			$("#clonar_conta_pagar").attr("disabled", true);
			$("#quitar_conta_pagar").attr("disabled", false);
		}
		$('[name ="selecionar_conta_pagar[]"]').prop("checked", true);
    	
    }else{
    	$("#baixar_conta_pagar").attr("disabled", true);
    	$("#clonar_conta_pagar").attr("disabled", true);
    	$("#quitar_conta_pagar").attr("disabled", true);
        $('[name ="selecionar_conta_pagar[]"]').prop("checked", false);
    }
});

$(document).on('click', '[name ="selecionar_conta_pagar[]"]', function(){

	var cont =0;
	var nao =0;
	$("[name ='selecionar_conta_pagar[]']").each(function(){
		
        if ($(this).is(':checked')){
        	cont = cont+1;
			if($(this).parent().parent().hasClass('warning')){
				nao ++;		
			}
        }
    });

	if(nao != 0){
		$("#baixar_conta_pagar").attr("disabled", false);
		if(nao == 1){
			$("#clonar_conta_pagar").attr("disabled", false);
		}else{
			$("#clonar_conta_pagar").attr("disabled", true);
		}
    	$("#quitar_conta_pagar").attr("disabled", true);
	}else{
		if(cont == 1){
			$("#baixar_conta_pagar").attr("disabled", false);
			$("#clonar_conta_pagar").attr("disabled", false);
			$("#quitar_conta_pagar").attr("disabled", false);
		}else if(cont > 1){
			$("#baixar_conta_pagar").attr("disabled", false);
			$("#clonar_conta_pagar").attr("disabled", true);
			$("#quitar_conta_pagar").attr("disabled", false);
		}else{
			$("#baixar_conta_pagar").attr("disabled", true);
			$("#clonar_conta_pagar").attr("disabled", true);
			$("#quitar_conta_pagar").attr("disabled", true);
		}
	}
});

$(document).on('click', '#tr_id_conta_pagar', function(){
	
	var id_conta_pagar = $(this).find("[name ='td_nome_conta_pagar[]']").text()
	var antigo_tabela = $('#id_antigo_tabela_conta_pagar').val();

	$("[name ='td_nome_conta_pagar[]']").each(function(){

        if($(this).text() == id_conta_pagar){
			$(this).parent().removeClass('default');
			$(this).parent().addClass('info');
		}else if($(this).text() == antigo_tabela){
			$(this).parent().removeClass('info');
			$(this).parent().addClass('default');
		}
    });
    $('#id_antigo_tabela_conta_pagar').val(id_conta_pagar);
});

</script>