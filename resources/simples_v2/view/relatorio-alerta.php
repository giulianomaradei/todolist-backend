<?php
require_once(__DIR__."/../class/System.php");

$data_hoje = getDataHora();
$data_hoje = explode(" ", $data_hoje);
$data_hoje = $data_hoje[0];
$primeiro_dia = "01/".$data_hoje[5].$data_hoje[6]."/".$data_hoje[0].$data_hoje[1].$data_hoje[2].$data_hoje[3];

$tipo_relatorio = (!empty($_POST['tipo_relatorio'])) ? $_POST['tipo_relatorio'] : 1;
$data_de = (!empty($_POST['data_de'])) ? $_POST['data_de'] :$primeiro_dia;
$data_ate = (!empty($_POST['data_ate'])) ? $_POST['data_ate'] : converteData(getDataHora('data'));
$busca_contrato = (!empty($_POST['busca_contrato'])) ? $_POST['busca_contrato'] : '';

$id_contrato_plano_pessoa = (!empty($_POST['id_contrato_plano_pessoa'])) ? $_POST['id_contrato_plano_pessoa'] : '';
$id_categoria = (!empty($_POST['id_categoria'])) ? $_POST['id_categoria'] : '';
$id_exibicao = (!empty($_POST['id_exibicao'])) ? $_POST['id_exibicao'] : '';

$status = (!empty($_POST['status'])) ? $_POST['status'] : '';
$plano = (!empty($_POST['plano'])) ? $_POST['plano'] : '';
$vencimento = (!empty($_POST['vencimento'])) ? $_POST['vencimento'] : '';

$data_de_vencimento = (!empty($_POST['data_de_vencimento'])) ? $_POST['data_de_vencimento'] : '';
$data_ate_vencimento = (!empty($_POST['data_ate_vencimento'])) ? $_POST['data_ate_vencimento'] : '';

$gerar = (!empty($_POST['gerar'])) ? 1 : 0;

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

if($tipo_relatorio == 1){
	$display_row_categoria = '';
	$display_row_exibicao = '';
	$display_row_status = 'style="display:none;"';
	$display_row_plano = 'style="display:none;"';
	$display_row_vencimento = 'style="display:none;"';
	$display_row_periodo = '';
	$display_row_contrato = '';
	$display_row_periodo_vencimento = 'style="display:none;"';

}else if($tipo_relatorio == 2){
	$display_row_categoria = 'style="display:none;"';
	$display_row_exibicao = 'style="display:none;"';
	$display_row_status = '';
	$display_row_plano = '';
	$display_row_vencimento = 'style="display:none;"';
	$display_row_periodo = '';
	$display_row_contrato = '';
	$display_row_periodo_vencimento = 'style="display:none;"';

}else if($tipo_relatorio == 3){
	$display_row_categoria = 'style="display:none;"';
	$display_row_exibicao = 'style="display:none;"';
	$display_row_status = 'style="display:none;"';
	$display_row_plano = 'style="display:none;"';
	$display_row_vencimento = '';
	$display_row_periodo = 'style="display:none;"';
	$display_row_contrato = '';
	$display_row_periodo_vencimento = '';

}else if($tipo_relatorio == 4){
	$display_row_categoria = 'style="display:none;"';
	$display_row_exibicao = 'style="display:none;"';
	$display_row_status = 'style="display:none;"';
	$display_row_plano = 'style="display:none;"';
	$display_row_vencimento = 'style="display:none;"';
	$display_row_periodo = '';
	$display_row_contrato = '';
	$display_row_periodo_vencimento = 'style="display:none;"';

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
	                    <h3 class="panel-title text-left pull-left" style="margin-top: 2px;">Relatório - Alertas:</h3>
	                    <div class="panel-title text-right pull-right"><button data-toggle="collapse" data-target="#accordionRelatorio" class="btn btn-xs btn-info" type="button" title="Visualizar filtros"><i id="i_collapse" class="fa fa-<?=$collapse_icon?>"></i></button></div>
	                </div>
	                <div id="accordionRelatorio" class="panel-collapse collapse <?=$collapse?>">
	                	<div class="panel-body">	                		
                			<div class="row">
                				<div class="col-md-12">
                					<div class="form-group">
								        <label for="">Tipo de Relatório:</label>
								        <select name="tipo_relatorio" id="tipo_relatorio" class="form-control input-sm">
								        	<option value="1" <?php if($tipo_relatorio == '1'){ echo 'selected';}?>>Alertas do Call Center</option>
								        	<option value="4" <?php if($tipo_relatorio == '4'){ echo 'selected';}?>>Alertas do Call Center e do Painel</option>
								        	<option value="2" <?php if($tipo_relatorio == '2'){ echo 'selected';}?>>Alertas do Painel</option>
								        	<option value="3" <?php if($tipo_relatorio == '3'){ echo 'selected';}?>>Vencimentos</option>
								        </select>
								    </div>
                				</div>
                			</div>

                			<div class="row"  id="row_contrato" <?=$display_row_contrato?>>
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

							<div class="row" id="row_periodo" <?=$display_row_periodo?>>
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
							
							<div class="row" id="row_categoria" <?=$display_row_categoria?>>
								<div class="col-md-12">
									<div class="form-group">
								        <label for="">*Categoria:</label>
								        <select name="id_categoria" class="form-control input-sm">
								        	<option value="">Todas</option>
								            <?php							            	
								            	$dados_categoria = DBRead('','tb_categoria',"WHERE exibe_alerta = 1 ORDER BY nome");
								            	if($dados_categoria){
								            		foreach ($dados_categoria as $conteudo_categoria) {
														$selected = $id_categoria == $conteudo_categoria['id_categoria'] ? "selected" : "";
								            			echo "<option value='".$conteudo_categoria['id_categoria']."' ".$selected.">".$conteudo_categoria['nome']."</option>";
								            		}
								            	}
								            ?>
								        </select>
								    </div>
								</div>
							</div>
	
							<div class="row" id="row_exibicao" <?=$display_row_exibicao?>>
								<div class="col-md-12">
									<div class="form-group">
								        <label for="">*Exibição:</label>
								        <select name="id_exibicao" class="form-control input-sm">
								        	<option value="">Todas</option>
								        	<option value="1" <?php if($id_exibicao == '1'){ echo 'selected';}?>>Atendimento - Todo</option>
								        	<option value="2" <?php if($id_exibicao == '2'){ echo 'selected';}?>>Atendimento - Somente na finalização</option>
								        	<option value="3" <?php if($id_exibicao == '3'){ echo 'selected';}?>>Atendimento - Somente no início</option>
								        	<option value="4" <?php if($id_exibicao == '4'){ echo 'selected';}?>> Monitoramento - Todo</option>
								        </select>
								    </div>
								</div>
							</div>

							<div class="row" id="row_status" <?=$display_row_status?>>
								<div class="col-md-12">
									<div class="form-group">
								        <label for="">*Status:</label>
								        <select class="form-control input-sm" name="status" id="status">
		                                    <option value="">Todos</option>
		                                    <option value="2" <?php if($status == '2'){ echo 'selected';}?>>Aprovado</option>
		                                    <option value="4" <?php if($status == '4'){ echo 'selected';}?>>Cancelamento Pendente</option>
		                                    <option value="5" <?php if($status == '5'){ echo 'selected';}?>>Descartado</option>
		                                    <option value="1" <?php if($status == '1'){ echo 'selected';}?>>Pendente</option>
		                                    <option value="3" <?php if($status == '3'){ echo 'selected';}?>>Vencido/Cancelado</option>
		                                </select>
								    </div>
								</div>
							</div>

							<div class="row" id="row_plano" <?=$display_row_plano?>>
								<div class="col-md-12">
									<div class="form-group">
								        <label for="">*Plano:</label>
								        <select class="form-control input-sm" name="plano" id="plano">
											<option value="">Todos</option>
			                                <?php
		                                        $dados = DBRead('', 'tb_plano', "WHERE status = 1 ORDER BY nome ASC");
		                                        if ($dados) {
		                                            foreach ($dados as $conteudo) {
		                                                $id_plano = $conteudo['id_plano'];
		                                                $nome_servico = getNomeServico($conteudo['cod_servico'])." - ".$conteudo['nome'];
														$selected = $plano == $id_plano ? "selected" : "";
		                                                echo "<option value='".$id_plano."' ".$selected.">".$nome_servico."</option>";
		                                            }
		                                        }
		                                    ?>                             
		                                </select>
								    </div>
								</div>
							</div>

							<div class="row" id="row_vencimento" <?=$display_row_vencimento?>>
								<div class="col-md-12">
									<div class="form-group">
								        <label for="">*Status de Vencimento:</label>
								        <select class="form-control input-sm" name="vencimento" id="vencimento">
											<option value="">Todos</option>
		                                    <option value="1" <?php if($status == '1'){ echo 'selected';}?>>Abertos</option>
		                                    <option value="2" <?php if($status == '2'){ echo 'selected';}?>>Vencidos</option>
		                                </select>
								    </div>
								</div>
							</div>

							<div class="row" id="row_periodo_vencimento" <?=$display_row_periodo_vencimento?>>
								<div class="col-md-6">
									<div class="form-group" >
								        <label>Data Inicial (Data de Início):</label>
								        <input type="text" class="form-control input-sm date calendar" name="data_de_vencimento" value="<?=$data_de_vencimento?>">
								    </div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
								        <label>Data Final (Data de Início):</label>
								        <input type="text" class="form-control input-sm date calendar" name="data_ate_vencimento" value="<?=$data_ate_vencimento?>">
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
		if($gerar){
			if ($tipo_relatorio == 1) {
				relatorio_alerta($id_contrato_plano_pessoa, $data_de, $data_ate, $id_categoria, $id_exibicao);
			}else if($tipo_relatorio == 2){
				relatorio_alerta_painel($id_contrato_plano_pessoa, $data_de, $data_ate, $status, $plano);
			}else if($tipo_relatorio == 3){
				relatorio_alerta_vencimento($vencimento, $id_contrato_plano_pessoa, $data_de_vencimento, $data_ate_vencimento);
			}	else if($tipo_relatorio == 4){
				relatorio_alerta_call_center_painel($id_contrato_plano_pessoa, $data_de, $data_ate);
			}				
		}
		?>
	</div>
</div>
<script>

	$('#tipo_relatorio').on('change',function(){
		tipo_relatorio = $(this).val();
		if(tipo_relatorio == 1){
			$('#row_categoria').show();
			$('#row_exibicao').show();
			$('#row_status').hide();
			$('#row_plano').hide();
			$('#row_vencimento').hide();
			$('#row_contrato').show();
			$('#row_periodo').show();
			$('#row_periodo_vencimento').hide();

		}else if(tipo_relatorio == 2){
			$('#row_categoria').hide();
			$('#row_exibicao').hide();
			$('#row_status').show();
			$('#row_plano').show();
			$('#row_vencimento').hide();
			$('#row_contrato').show();
			$('#row_periodo').show();
			$('#row_periodo_vencimento').hide();

		}else if(tipo_relatorio == 3){
			$('#row_categoria').hide();
			$('#row_exibicao').hide();
			$('#row_status').hide();
			$('#row_plano').hide();
			$('#row_vencimento').show();
			$('#row_contrato').show();
			$('#row_periodo').hide();
			$('#row_periodo_vencimento').show();

		}else if(tipo_relatorio == 4){
			$('#row_categoria').hide();
			$('#row_exibicao').hide();
			$('#row_status').hide();
			$('#row_plano').hide();
			$('#row_vencimento').hide();
			$('#row_contrato').show();
			$('#row_periodo').show();
			$('#row_periodo_vencimento').hide();

		}
	}); 
	
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
            }if(!item.nome_contrato){
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

function relatorio_alerta_call_center_painel($id_contrato_plano_pessoa, $data_de, $data_ate){

	if($id_contrato_plano_pessoa){
		$dados = DBRead('','tb_contrato_plano_pessoa a',"INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_contrato_plano_pessoa = '".$id_contrato_plano_pessoa."'");
		$empresa_legenda = $dados[0]['nome'];

		if($dados[0]['nome_contrato']){
			$empresa_legenda = $empresa_legenda." (".$dados[0]['nome_contrato'].") ";
		}
		
		$filtro_contrato_plano_pessoa = " AND a.id_contrato_plano_pessoa ='".$id_contrato_plano_pessoa."' ";
	}else{
		$empresa_legenda = "Todos";
		$filtro_contrato_plano_pessoa = "";
	}

	$data_hoje = getDataHora();
	$data_hoje = converteDataHora($data_hoje);

	$periodo_amostra = "<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> de $data_de até $data_ate</span>";
	$gerado = "<span style=\"font-size: 14px;\"><strong>Gerado em: </strong> $data_hoje</span>";

	$data_de = converteData($data_de);
	$data_ate = converteData($data_ate);

	echo "<div class=\"col-md-12\" style=\"padding: 0\">";
	echo "<legend style=\"text-align:center;\"><strong>Relatório de Alertas do Call Center e do Painel do Cliente</strong><br>$gerado</legend>";
	echo "<legend style=\"text-align:center;\">$periodo_amostra</legend>";
	echo "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong> Contrato - </strong>".$empresa_legenda."</legend>";

	$dados_consulta = DBRead('','tb_alerta a',"INNER JOIN tb_contrato_plano_pessoa b ON a.id_contrato_plano_pessoa = b.id_contrato_plano_pessoa INNER JOIN tb_pessoa c ON b.id_pessoa = c.id_pessoa INNER JOIN tb_plano d ON b.id_plano = d.id_plano INNER JOIN tb_categoria e ON a.id_categoria = e.id_categoria WHERE a.data_inicio >= '".$data_de." 00:00:00' AND a.data_inicio < '".$data_ate." 23:59:59' ".$filtro_contrato_plano_pessoa." ORDER BY c.nome ASC","a.*, c.nome AS nome_empresa, e.nome AS categoria, d.cod_servico, d.nome AS plano");  

	$dados_consulta_painel = DBRead('','tb_alerta_painel a',"INNER JOIN tb_contrato_plano_pessoa b ON a.id_contrato_plano_pessoa = b.id_contrato_plano_pessoa INNER JOIN tb_pessoa c ON b.id_pessoa = c.id_pessoa INNER JOIN tb_plano d ON b.id_plano = d.id_plano WHERE a.data_inicio >= '".$data_de." 00:00:00' AND a.data_inicio < '".$data_ate." 23:59:59' ".$filtro_contrato_plano_pessoa." ORDER BY c.nome ASC","a.*, c.nome AS nome_empresa, d.cod_servico, d.nome AS plano");  

	registraLog('Relatório de alertas call center e painel - Alertas.','rel','relatorio-alerta-call-center-painel',1,"INNER JOIN tb_contrato_plano_pessoa b ON a.id_contrato_plano_pessoa = b.id_contrato_plano_pessoa INNER JOIN tb_pessoa c ON b.id_pessoa = c.id_pessoa INNER JOIN tb_plano d ON b.id_plano = d.id_plano INNER JOIN tb_categoria e ON a.id_categoria = e.id_categoria WHERE a.data_inicio >= '".$data_de." 00:00:00' AND a.data_inicio < '".$data_ate." 23:59:59' ".$filtro_contrato_plano_pessoa." ORDER BY c.nome ASC");
	registraLog('Relatório de alertas call center e painel - Alertas.','rel','relatorio-alerta-call-center-painel',1,"INNER JOIN tb_contrato_plano_pessoa b ON a.id_contrato_plano_pessoa = b.id_contrato_plano_pessoa INNER JOIN tb_pessoa c ON b.id_pessoa = c.id_pessoa INNER JOIN tb_plano d ON b.id_plano = d.id_plano WHERE a.data_inicio >= '".$data_de." 00:00:00' AND a.data_inicio < '".$data_ate." 23:59:59' ".$filtro_contrato_plano_pessoa." ORDER BY c.nome ASC");
	
	if($dados_consulta || $dados_consulta_painel){
		if($dados_consulta){
			$total_consulta = sizeof($dados_consulta);
		}else{
			$total_consulta = 0;
		}
		if($dados_consulta_painel){
			$total_consulta_painel = sizeof($dados_consulta_painel);
		}else{
			$total_consulta_painel = 0;
		}
		$total = $total_consulta + $total_consulta_painel;
		echo "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong>Total: </strong> ".$total."</span></legend>";

		$dados_completo = array();
		$cont = 0;
		if($dados_consulta){
			foreach ($dados_consulta as $conteudo_consulta) {		      	

				$dados_completo[$cont]['nome_empresa'] = $conteudo_consulta['nome_empresa'];
				$dados_completo[$cont]['plano_servico'] = getNomeServico($conteudo_consulta['cod_servico'])." - ".$conteudo_consulta['plano'];
				$dados_completo[$cont]['data_inicio'] = converteDataHora($conteudo_consulta['data_inicio']);
				$dados_completo[$cont]['data_fim'] = converteDataHora($conteudo_consulta['data_vencimento']);
				$dados_completo[$cont]['conteudo'] = $conteudo_consulta['conteudo'];
				$dados_completo[$cont]['status_painel'] = 'N/D';
				$dados_completo[$cont]['origem'] = 'Belluno';

				$cont++;
				

				// $qtd_empresas[$conteudo_consulta['nome_empresa']] += 1;
				// $qtd_categoria[$conteudo_consulta['categoria']] += 1;
				// $qtd_exibicao[$exibicao] += 1;
				// $qtd_atendentes[$conteudo_consulta['nome_atendente']] += 1;
				// $qtd_plano[getNomeServico($conteudo_consulta['cod_servico'])." - ".$conteudo_consulta['plano']] += 1;		      	

			}
		}

		if($dados_consulta_painel){
			foreach ($dados_consulta_painel as $conteudo_painel) {		      	

				$dados_completo[$cont]['nome_empresa'] = $conteudo_painel['nome_empresa'];
				$dados_completo[$cont]['plano_servico'] = getNomeServico($conteudo_painel['cod_servico'])." - ".$conteudo_painel['plano'];
				$dados_completo[$cont]['data_inicio'] = converteDataHora($conteudo_painel['data_inicio']);
				$dados_completo[$cont]['data_fim'] = converteDataHora($conteudo_painel['data_fim']);
				$dados_completo[$cont]['conteudo'] = $conteudo_painel['descricao'];

				if($conteudo_painel['status'] == 1){
					$status = "Pendente";
				}else if($conteudo_painel['status'] == 2){
					$status = "Aprovado";
				}else if($conteudo_painel['status'] == 3){
					$status = "Vencido";
				}else if($conteudo_painel['status'] == 4){
					$status = "Excluído";
				}else if($conteudo_painel['status'] == 5){
					$status = "Descartado";
				}
				$dados_completo[$cont]['status_painel'] = $status;
				$dados_completo[$cont]['origem'] = 'Painel';

				$cont++;
			}
		}

	  	echo '
		<table class="table table-hover dataTable" style="margin-bottom:0;">
			<thead>
		        <tr>
		            <th class="text-left col-md-2">Contrato</th>
			        <th class="text-left col-md-2">Plano</th>
		            <th class="text-left col-md-1">Data de Início</th>
		            <th class="text-left col-md-1">Data Final</th>
		            <th class="text-left col-md-2">Conteúdo</th>
		            <th class="text-left col-md-2">Status Painel</th>
		            <th class="text-left col-md-2">Origem</th>
		        </tr>
			</thead>
			<tbody>';         	 

			foreach ($dados_completo as $conteudo_completo) {		      	
				echo '
				<tr>
					<td class="text-left">'.$conteudo_completo['nome_empresa'].'</td>    
					<td class="text-left">'.$conteudo_completo['plano_servico'].'</td>    
					<td class="text-left">'.$conteudo_completo['data_inicio'].'</td>    
					<td class="text-left">'.$conteudo_completo['data_fim'].'</td>    
					<td class="text-left">'.$conteudo_completo['conteudo'].'</td>    
					<td class="text-left">'.$conteudo_completo['status_painel'].'</td>    
					<td class="text-left">'.$conteudo_completo['origem'].'</td>    
				</tr>';
			}
		      
		    echo "
			</tbody>
		</table>";

		echo "
		<script>
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

function relatorio_alerta_vencimento($vencimento, $id_contrato_plano_pessoa, $data_de_vencimento, $data_ate_vencimento){

	if($id_contrato_plano_pessoa){
		$dados = DBRead('','tb_contrato_plano_pessoa a',"INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_contrato_plano_pessoa = '".$id_contrato_plano_pessoa."'");
		$empresa_legenda = $dados[0]['nome'];

		if($dados[0]['nome_contrato']){
			$empresa_legenda = $empresa_legenda." (".$dados[0]['nome_contrato'].") ";
		}
		
		$filtro_contrato_plano_pessoa = " AND a.id_contrato_plano_pessoa ='".$id_contrato_plano_pessoa."' ";
	}else{
		$empresa_legenda = "Todos";
		$filtro_contrato_plano_pessoa = "";
	}

	$data_hoje = getDataHora();

	if($vencimento){
		if($vencimento == 1){
			$vencimento_legenda = 'Abertos';
			$filtro_vencimento = "  a.data_vencimento > '".$data_hoje."' OR a.data_vencimento IS NULL";

		}else if($vencimento == 2){
			$vencimento_legenda = 'Vencidos';
			$filtro_vencimento = "  a.data_vencimento <= '".$data_hoje."'";
		}
	}else{
		$vencimento_legenda = 'Todos';
		$filtro_vencimento = "  a.data_vencimento IS NOT NULL";

	}

	if($data_de_vencimento || $data_ate_vencimento){
		if($data_de_vencimento && $data_ate_vencimento){
			$legenda_datas_vencimento = ", <strong>Data Inicial - </strong>".($data_de_vencimento).", <strong>Data Final - </strong>".($data_ate_vencimento)." ";
			$filtro_datas_vencimento = " AND (a.data_inicio > '".converteData($data_de_vencimento)." 00:00:00' AND a.data_inicio < '".converteData($data_ate_vencimento)." 23:59:59' ) ";
		}else if($data_de_vencimento){
			$legenda_datas_vencimento = ", <strong>Data Inicial - </strong>".($data_de_vencimento)." ";
			$filtro_datas_vencimento = " AND (a.data_inicio > '".converteData($data_de_vencimento)." 00:00:00' ) ";
		}else if($data_ate_vencimento){
			$legenda_datas_vencimento = ", <strong>Data Final - </strong>".($data_ate_vencimento)." ";
			$filtro_datas_vencimento = " AND (a.data_inicio < '".converteData($data_ate_vencimento)." 23:59:59' ) ";
		}
		
	}else{
		$legenda_datas_vencimento = "";
		$filtro_datas_vencimento = "";
	}

	$data_hoje = converteDataHora($data_hoje);

	$gerado = "<span style=\"font-size: 14px;\"><strong>Gerado em: </strong> $data_hoje</span>";

	echo "<div class=\"col-md-12\" style=\"padding: 0\">";
	echo "<legend style=\"text-align:center;\"><strong>Relatório de Alertas (Vencimentos)</strong><br>$gerado</legend>";
	echo "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong> Contrato - </strong>".$empresa_legenda.", <strong>Status de Vencimento - </strong>".$vencimento_legenda." ".$legenda_datas_vencimento."</legend>";
	
	$dados_consulta = DBRead('','tb_alerta a',"INNER JOIN tb_contrato_plano_pessoa b ON a.id_contrato_plano_pessoa = b.id_contrato_plano_pessoa INNER JOIN tb_pessoa c ON b.id_pessoa = c.id_pessoa INNER JOIN tb_plano d ON b.id_plano = d.id_plano INNER JOIN tb_categoria e ON a.id_categoria = e.id_categoria WHERE ".$filtro_vencimento." ".$filtro_contrato_plano_pessoa." ".$filtro_datas_vencimento." ORDER BY c.nome ASC","a.*, c.nome AS nome_empresa, e.nome AS categoria, d.cod_servico, d.nome AS plano");  

	registraLog('Relatório de alertas - vencimento.','rel','relatorio-alerta',1,"INNER JOIN tb_contrato_plano_pessoa b ON a.id_contrato_plano_pessoa = b.id_contrato_plano_pessoa INNER JOIN tb_pessoa c ON b.id_pessoa = c.id_pessoa INNER JOIN tb_plano d ON b.id_plano = d.id_plano INNER JOIN tb_categoria e ON a.id_categoria = e.id_categoria WHERE ".$filtro_vencimento." ".$filtro_contrato_plano_pessoa."  ORDER BY c.nome ASC");


	if($dados_consulta){
		$contador_dados = "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong>Total: </strong> ".count($dados_consulta)."</span></legend>";
	}
	echo $contador_dados;

    if($dados_consulta){ 
		echo '<table class="table table-hover dataTable" style="margin-bottom:0;">
		      <thead>
		        <tr>
		            <th class="text-left  col-md-2">Contrato</th>
			        <th class="text-left  col-md-2">Plano</th>
		            <th class="text-left  col-md-2">Data de Início</th>
		            <th class="text-left  col-md-2">Data de Vencimento</th>
		            <th class="text-left  col-md-2">Categoria</th>
		            <th class="text-left  col-md-2">Conteúdo</th>
		            <th class="text-left  col-md-2">Exibição</th>
		        </tr>
		      </thead>
		      <tbody>';         	 

		      foreach ($dados_consulta as $conteudo_consulta) {		      	

					if($conteudo_consulta['exibicao'] == 1){
						$exibicao = "Atendimento - Todo";
					}else if($conteudo_consulta['exibicao'] == 2){
						$exibicao = "Atendimento - Somente na finalização";
					}else if($conteudo_consulta['exibicao'] == 3){
						$exibicao = "Atendimento - Somente no início";
					}else if($conteudo_consulta['exibicao'] == 4){
						$exibicao = "Monitoramento - Todo";
					}
					if($conteudo_consulta['data_vencimento']){
						$data_vencimento = converteDataHora($conteudo_consulta['data_vencimento']);
					}else{
						$data_vencimento = 'N/D';
					}
		      		echo '<tr>
			            <td class="text-left">'.$conteudo_consulta['nome_empresa'].'</td>    
			            <td class="text-left">'.getNomeServico($conteudo_consulta['cod_servico'])." - ".$conteudo_consulta['plano'].'</td>
			            <td class="text-left">'.converteDataHora($conteudo_consulta['data_inicio']).'</td>
			            <td class="text-left">'.$data_vencimento.'</td>
			            <td class="text-left">'.$conteudo_consulta['categoria'].'</td>
			            <td class="text-left">'.$conteudo_consulta['conteudo'].'</td>
			            <td class="text-left">'.$exibicao.'</td>
			        </tr>';


		      }
		      
		    echo "</tbody>
		   
		</table>
	
		<hr>"; 
	
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

function relatorio_alerta_painel($id_contrato_plano_pessoa, $data_de, $data_ate, $status, $plano){

	if($id_contrato_plano_pessoa){
		$dados = DBRead('','tb_contrato_plano_pessoa a',"INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_contrato_plano_pessoa = '".$id_contrato_plano_pessoa."'");
		$empresa_legenda = $dados[0]['nome'];

		if($dados[0]['nome_contrato']){
			$empresa_legenda = $empresa_legenda." (".$dados[0]['nome_contrato'].") ";
		}
		
		$filtro_contrato_plano_pessoa = " AND a.id_contrato_plano_pessoa ='".$id_contrato_plano_pessoa."' ";
	}else{
		$empresa_legenda = "Todos";
		$filtro_contrato_plano_pessoa = "";
	}

	if($plano){
		$dados_plano = DBRead('','tb_plano',"WHERE id_plano = '".$plano."' ");

		$plano_legenda = getNomeServico($dados_plano[0]['cod_servico'])." - ".$dados_plano[0]['nome'];
		$filtro_plano = " AND b.id_plano = '".$plano."'";
	}else{
		$plano_legenda = 'Todos';
	}

	if($status){
		if($status == 1){
			$status_legenda = 'Pendente';
		}else if($status == 2){
			$status_legenda = 'Aprovado';
		}else if($status == 3){
			$status_legenda = 'Vencido/Cancelado';
		}else if($status == 4){
			$status_legenda = 'Cancelamento Pendente';
		}else{
			$status_legenda = 'Descartado';
		}

		$filtro_status = " AND a.status = '".$status."'";
	}else{
		$status_legenda = 'Todos';
	}

	$data_hoje = getDataHora();
	$data_hoje = converteDataHora($data_hoje);

	$periodo_amostra = "<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> de $data_de até $data_ate</span>";
	$gerado = "<span style=\"font-size: 14px;\"><strong>Gerado em: </strong> $data_hoje</span>";

	$data_de = converteData($data_de);
	$data_ate = converteData($data_ate);

	echo "<div class=\"col-md-12\" style=\"padding: 0\">";
	echo "<legend style=\"text-align:center;\"><strong>Relatório de Alertas do Painel</strong><br>$gerado</legend>";
	echo "<legend style=\"text-align:center;\">$periodo_amostra</legend>";
	echo "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong> Contrato - </strong>".$empresa_legenda.", <strong>Status - </strong>".$status_legenda.", <strong>Plano - </strong>".$plano_legenda."</legend>";
	
	$dados_consulta = DBRead('','tb_alerta_painel a',"INNER JOIN tb_contrato_plano_pessoa b ON a.id_contrato_plano_pessoa = b.id_contrato_plano_pessoa INNER JOIN tb_pessoa c ON b.id_pessoa = c.id_pessoa INNER JOIN tb_plano d ON b.id_plano = d.id_plano WHERE a.data_cadastro >= '".$data_de." 00:00:00' AND a.data_cadastro < '".$data_ate." 23:59:59' AND a.status != 4 ".$filtro_contrato_plano_pessoa." ".$filtro_plano." ".$filtro_status." ORDER BY c.nome ASC","a.*, a.status AS status_alerta_painel, c.nome AS nome_empresa, d.cod_servico, d.nome AS plano"); 
	
	registraLog('Relatório de alertas - Painel.','rel','relatorio-alerta',1,"INNER JOIN tb_contrato_plano_pessoa b ON a.id_contrato_plano_pessoa = b.id_contrato_plano_pessoa INNER JOIN tb_pessoa c ON b.id_pessoa = c.id_pessoa INNER JOIN tb_plano d ON b.id_plano = d.id_plano WHERE a.data_cadastro >= '".$data_de." 00:00:00' AND a.data_cadastro < '".$data_ate." 23:59:59' AND a.status != 4 ".$filtro_contrato_plano_pessoa." ".$filtro_plano." ".$filtro_status." ORDER BY c.nome ASC");

	
	if($dados_consulta){
		$contador_dados = "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong>Total: </strong> ".count($dados_consulta)."</span></legend>";
	}
	echo $contador_dados;

    if($dados_consulta){ 
		$qtd_empresas = array();
		$qtd_status = array();
		$qtd_atendentes = array();
		$qtd_plano = array();
		echo '<table class="table table-striped dataTable" style="margin-bottom:0;">
		      <thead>
		        <tr>
		            <th>Contrato</th>
			        <th>Plano</th>
		            <th>Data do Cadastro</th>
		            <th>Status</th>
		            <th class="text-left col-md-2">Descrição</th>
		            <th>Data da Resposta</th>
		            <th class="text-left col-md-2">Justificativa</th>
		            <th>Criado por</th>
		            <th>Usuário Ação</th>
		        </tr>
		      </thead>
		      <tbody>';         	 

		      foreach ($dados_consulta as $conteudo_consulta) {		      	

					if($conteudo_consulta['status_alerta_painel'] == 1){
						$status_alerta_painel = "Pendente";
					}else if($conteudo_consulta['status_alerta_painel'] == 2){
						$status_alerta_painel = "Aprovado";
					}else if($conteudo_consulta['status_alerta_painel'] == 3){
						$status_alerta_painel = "Vencido/Cancelado";
					}else if($conteudo_consulta['status_alerta_painel'] == 4){
						$status_alerta_painel = "Cancelamento Pendente";
					}else if($conteudo_consulta['status_alerta_painel'] == 5){
						$status_alerta_painel = "Descartado";
					}

					$dados_usuario_painel = DBRead('','tb_usuario_painel a',"INNER JOIN tb_pessoa b ON a.id_pessoa_usuario = b.id_pessoa WHERE a.id_usuario_painel = '".$conteudo_consulta['id_usuario_painel']."' ", "b.nome");
					
					$dados_usuario = DBRead('','tb_usuario a',"INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_usuario = '".$conteudo_consulta['id_usuario_resposta']."' ", "b.razao_social");
					  
					echo '<tr>
			            <td class="text-left" style="vertical-align: middle;">'.$conteudo_consulta['nome_empresa'].'</td>    
			            <td class="text-left" style="vertical-align: middle;">'.getNomeServico($conteudo_consulta['cod_servico'])." - ".$conteudo_consulta['plano'].'</td>
			            <td class="text-left" style="vertical-align: middle;">'.converteDataHora($conteudo_consulta['data_cadastro']).'</td>
			            <td class="text-left" style="vertical-align: middle;">'.$status_alerta_painel.'</td>
			            <td class="text-left" style="vertical-align: middle;">'.nl2br($conteudo_consulta['descricao']).'</td>
			            <td class="text-left" style="vertical-align: middle;">'.converteDataHora($conteudo_consulta['data_resposta']).'</td>
			            <td class="text-left" style="vertical-align: middle;">'.nl2br($conteudo_consulta['justificativa']).'</td>
			            <td class="text-left" style="vertical-align: middle;">'.$dados_usuario_painel[0]['nome'].'</td>
			            <td class="text-left" style="vertical-align: middle;">'.$dados_usuario[0]['razao_social'].'</td>
			        </tr>';

			        $qtd_empresas[$conteudo_consulta['nome_empresa']] += 1;
			        $qtd_status[$status_alerta_painel] += 1;
				    $qtd_atendentes[$conteudo_consulta['nome_atendente']] += 1;
				    $qtd_plano[getNomeServico($conteudo_consulta['cod_servico'])." - ".$conteudo_consulta['plano']] += 1;		      	

		      }
		      
		    echo "</tbody>
		   
		</table>
	
		<hr>"; 
		
		if(!$id_contrato_plano_pessoa){
			echo '
				<table class="table table-hover dataTable" style="margin-bottom:0;">
					      <thead>
					        <tr>
					            <th class="text-left col-md-8">Contrato</th>
					            <th class="text-left col-md-4">Total</th>
					        </tr>
					      </thead>
					      <tbody>';  

					      $aux_empresa = 0;

						 arsort($qtd_empresas);   
						  foreach ($qtd_empresas as $empresa => $qtd) {
						    echo '<tr>';
						    echo '<td>'.$empresa.'</td>';
						    echo '<td>'.$qtd.'</td>';
						    echo '</tr>';  
						    $aux_empresa = $aux_empresa + (int)$qtd;    
						  }
				          echo '</tbody>';					          
				echo '</table>
				<hr>';       
		}

			echo '
				<table class="table table-hover dataTable" style="margin-bottom:0;">
					      <thead>
					        <tr>
					            <th class="text-left col-md-8">Plano</th>
					            <th class="text-left col-md-4">Total</th>
					        </tr>
					      </thead>
					      <tbody>';  


						 arsort($qtd_plano);   
						  foreach ($qtd_plano as $planos => $qtd) {
						    echo '<tr>';
						    echo '<td>'.$planos.'</td>';
						    echo '<td>'.$qtd.'</td>';
						    echo '</tr>';     
						  }
				          echo '</tbody>
				</table>
				<hr>'; 


			echo '
				<table class="table table-hover dataTable" style="margin-bottom:0;">
					      <thead>
					        <tr>			       
					            <th class="text-left col-md-8">Status</th>
					            <th class="text-left col-md-4">Total</th>
					        </tr>
					      </thead>
					      <tbody>';  

						arsort($qtd_status);   
							foreach ($qtd_status as $status => $qtd) {
							echo '<tr>';
							echo '<td>'.$status.'</td>';
							echo '<td>'.$qtd.'</td>';
							echo '</tr>';     
							}
				    echo '</tbody>
				</table>
				<hr>';     

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

function relatorio_alerta($id_contrato_plano_pessoa, $data_de, $data_ate, $id_categoria, $id_exibicao){

	if($id_contrato_plano_pessoa){
		$dados = DBRead('','tb_contrato_plano_pessoa a',"INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_contrato_plano_pessoa = '".$id_contrato_plano_pessoa."'");
		$empresa_legenda = $dados[0]['nome'];

		if($dados[0]['nome_contrato']){
			$empresa_legenda = $empresa_legenda." (".$dados[0]['nome_contrato'].") ";
		}
		
		$filtro_contrato_plano_pessoa = " AND a.id_contrato_plano_pessoa ='".$id_contrato_plano_pessoa."' ";
	}else{
		$empresa_legenda = "Todos";
	}

	if($id_categoria){
		$dados_categoria = DBRead('','tb_categoria',"WHERE id_categoria = '".$id_categoria."' ");

		$categoria_legenda = $dados_categoria[0]['nome'];
		$filtro_categoria = " AND a.id_categoria = '".$id_categoria."'";
	}else{
		$categoria_legenda = 'Todas';
	}

	if($id_exibicao){
		if($id_exibicao == 1){
			$exibicao_legenda = "Atendimento - Todo";
		}else if($id_exibicao == 2){
			$exibicao_legenda = "Atendimento - Somente na finalização";
		}else if($id_exibicao == 3){
			$exibicao_legenda = "Atendimento = Somente no início";
		}else if($id_exibicao == 4){
			$exibicao_legenda = " Monitoramento - Todo";
		}

		$filtro_exibicao = " AND a.exibicao = '".$id_exibicao."'";
	}else{
		$exibicao_legenda = 'Todas';
	}

	$data_hoje = getDataHora();
	$data_hoje = converteDataHora($data_hoje);

	$periodo_amostra = "<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> de $data_de até $data_ate</span>";
	$gerado = "<span style=\"font-size: 14px;\"><strong>Gerado em: </strong> $data_hoje</span>";

	$data_de = converteData($data_de);
	$data_ate = converteData($data_ate);

	echo "<div class=\"col-md-12\" style=\"padding: 0\">";
	echo "<legend style=\"text-align:center;\"><strong>Relatório de Alertas do Call Center</strong><br>$gerado</legend>";
	echo "<legend style=\"text-align:center;\">$periodo_amostra</legend>";
	echo "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong> Contrato - </strong>".$empresa_legenda.", <strong>Categoria - </strong>".$categoria_legenda.", <strong>Exibição - </strong>".$exibicao_legenda."</legend>";

	
	if($id_contrato_plano_pessoa){
		$filtro_contrato_plano_pessoa = "AND a.id_contrato_plano_pessoa = '".$id_contrato_plano_pessoa."'";
	}else{
		$filtro_contrato_plano_pessoa = "";
	}

	$dados_consulta = DBRead('','tb_alerta a',"INNER JOIN tb_contrato_plano_pessoa b ON a.id_contrato_plano_pessoa = b.id_contrato_plano_pessoa INNER JOIN tb_pessoa c ON b.id_pessoa = c.id_pessoa INNER JOIN tb_plano d ON b.id_plano = d.id_plano INNER JOIN tb_categoria e ON a.id_categoria = e.id_categoria WHERE a.data_inicio >= '".$data_de." 00:00:00' AND a.data_inicio < '".$data_ate." 23:59:59' ".$filtro_contrato_plano_pessoa." ".$filtro_categoria." ".$filtro_exibicao." ORDER BY c.nome ASC","a.*, c.nome AS nome_empresa, e.nome AS categoria, d.cod_servico, d.nome AS plano");  

	registraLog('Relatório de alertas - Alertas.','rel','relatorio-alerta',1,"INNER JOIN tb_contrato_plano_pessoa b ON a.id_contrato_plano_pessoa = b.id_contrato_plano_pessoa INNER JOIN tb_pessoa c ON b.id_pessoa = c.id_pessoa INNER JOIN tb_plano d ON b.id_plano = d.id_plano INNER JOIN tb_categoria e ON a.id_categoria = e.id_categoria WHERE a.data_inicio >= '".$data_de." 00:00:00' AND a.data_inicio < '".$data_ate." 23:59:59' ".$filtro_contrato_plano_pessoa." ".$filtro_categoria." ".$filtro_exibicao." ORDER BY c.nome ASC");

	
	if($dados_consulta){
		$contador_dados = "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong>Total: </strong> ".count($dados_consulta)."</span></legend>";
	}
	echo $contador_dados;

    if($dados_consulta){ 
		$qtd_empresas = array();
		$qtd_categoria = array();
		$qtd_exibicao = array();
		$qtd_atendentes = array();
		$qtd_plano = array();
		echo '<table class="table table-hover dataTable" style="margin-bottom:0;">
		      <thead>
		        <tr>
		            <th class="text-left  col-md-2">Contrato</th>
			        <th class="text-left  col-md-2">Plano</th>
		            <th class="text-left  col-md-2">Data</th>
		            <th class="text-left  col-md-2">Categoria</th>
		            <th class="text-left  col-md-2">Conteúdo</th>
		            <th class="text-left  col-md-2">Exibição</th>
		        </tr>
		      </thead>
		      <tbody>';         	 

		      foreach ($dados_consulta as $conteudo_consulta) {		      	

					if($conteudo_consulta['exibicao'] == 1){
						$exibicao = "Atendimento - Todo";
					}else if($conteudo_consulta['exibicao'] == 2){
						$exibicao = "Atendimento - Somente na finalização";
					}else if($conteudo_consulta['exibicao'] == 3){
						$exibicao = "Atendimento - Somente no início";
					}else if($conteudo_consulta['exibicao'] == 4){
						$exibicao = "Monitoramento - Todo";
					}
		      		echo '<tr>
			            <td class="text-left">'.$conteudo_consulta['nome_empresa'].'</td>    
			            <td class="text-left">'.getNomeServico($conteudo_consulta['cod_servico'])." - ".$conteudo_consulta['plano'].'</td>
			            <td class="text-left">'.converteDataHora($conteudo_consulta['data_inicio']).'</td>
			            <td class="text-left">'.$conteudo_consulta['categoria'].'</td>
			            <td class="text-left">'.$conteudo_consulta['conteudo'].'</td>
			            <td class="text-left">'.$exibicao.'</td>
			        </tr>';

			        $qtd_empresas[$conteudo_consulta['nome_empresa']] += 1;
			        $qtd_categoria[$conteudo_consulta['categoria']] += 1;
			        $qtd_exibicao[$exibicao] += 1;
				    $qtd_atendentes[$conteudo_consulta['nome_atendente']] += 1;
				    $qtd_plano[getNomeServico($conteudo_consulta['cod_servico'])." - ".$conteudo_consulta['plano']] += 1;		      	

		      }
		      
		    echo "</tbody>
		   
		</table>
	
		<hr>"; 
		
		if(!$id_contrato_plano_pessoa){
			echo '
				<table class="table table-hover dataTable" style="margin-bottom:0;">
					      <thead>
					        <tr>
					            <th class="text-left col-md-8">Contrato</th>
					            <th class="text-left col-md-4">Total</th>
					        </tr>
					      </thead>
					      <tbody>';  

					      $aux_empresa = 0;

						 arsort($qtd_empresas);   
						  foreach ($qtd_empresas as $empresa => $qtd) {
						    echo '<tr>';
						    echo '<td>'.$empresa.'</td>';
						    echo '<td>'.$qtd.'</td>';
						    echo '</tr>';  
						    $aux_empresa = $aux_empresa + (int)$qtd;    
						  }
				          echo '</tbody>';					          
				echo '</table>
				<hr>';       
		}

			echo '
				<table class="table table-hover dataTable" style="margin-bottom:0;">
					      <thead>
					        <tr>
					            <th class="text-left col-md-8">Plano</th>
					            <th class="text-left col-md-4">Total</th>
					        </tr>
					      </thead>
					      <tbody>';  


						 arsort($qtd_plano);   
						  foreach ($qtd_plano as $planos => $qtd) {
						    echo '<tr>';
						    echo '<td>'.$planos.'</td>';
						    echo '<td>'.$qtd.'</td>';
						    echo '</tr>';     
						  }
				          echo '</tbody>
				</table>
				<hr>'; 


			echo '
				<table class="table table-hover dataTable" style="margin-bottom:0;">
					      <thead>
					        <tr>			       
					            <th class="text-left col-md-8">Categoria</th>
					            <th class="text-left col-md-4">Total</th>
					        </tr>
					      </thead>
					      <tbody>';  

						arsort($qtd_categoria);   
							foreach ($qtd_categoria as $categoria => $qtd) {
							echo '<tr>';
							echo '<td>'.$categoria.'</td>';
							echo '<td>'.$qtd.'</td>';
							echo '</tr>';     
							}
				    echo '</tbody>
				</table>
				<hr>';     

			echo '
				<table class="table table-hover dataTable" style="margin-bottom:0;">
					      <thead>
					        <tr>			       
					            <th class="text-left col-md-8">Exibição</th>
					            <th class="text-left col-md-4">Total</th>
					        </tr>
					      </thead>
					      <tbody>';  

						arsort($qtd_exibicao);   
							foreach ($qtd_exibicao as $exibicao => $qtd) {
							echo '<tr>';
							echo '<td>'.$exibicao.'</td>';
							echo '<td>'.$qtd.'</td>';
							echo '</tr>';     
							}
				    echo '</tbody>
				</table>
				<hr>';     

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