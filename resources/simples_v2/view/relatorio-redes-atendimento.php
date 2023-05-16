<?php
require_once(__DIR__."/../class/System.php");
include_once(__DIR__."/../FunctionOTRS.php");

$id_usuario = $_SESSION['id_usuario'];
$dados = DBRead('', 'tb_usuario', "WHERE id_usuario = '$id_usuario'");
$perfil_sistema = $dados[0]['id_perfil_sistema'];
$gerar = (!empty($_POST['gerar'])) ? 1 : 0;
$tipo_relatorio = (!empty($_POST['tipo_relatorio'])) ? $_POST['tipo_relatorio'] : 1;
$primeiro_dia = new DateTime(getDataHora('data'));
$primeiro_dia->modify('first day of this month');
$primeiro_dia = $primeiro_dia->format('d/m/Y');
$ultimo_dia = new DateTime(getDataHora('data'));
$ultimo_dia->modify('last day of this month');
$ultimo_dia = $ultimo_dia->format('d/m/Y');
$data_de = (!empty($_POST['data_de'])) ? $_POST['data_de'] : $primeiro_dia;
$data_ate = (!empty($_POST['data_ate'])) ? $_POST['data_ate'] : $ultimo_dia;
$id_contrato_plano_pessoa = (!empty($_POST['id_contrato_plano_pessoa'])) ? $_POST['id_contrato_plano_pessoa'] : '';
$periodo = explode('/', $primeiro_dia);
$data_periodo_mes = (!empty($_POST['data_periodo_mes'])) ? $_POST['data_periodo_mes'] : $periodo[1];
$data_periodo_ano = (!empty($_POST['data_periodo_ano'])) ? $_POST['data_periodo_ano'] : $periodo[2];
$ticket_state = (!empty($_POST['ticket_state'])) ? $_POST['ticket_state'] : '';

if($id_contrato_plano_pessoa){
    $dados = DBRead('', 'tb_contrato_plano_pessoa a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa INNER JOIN tb_plano c ON a.id_plano = c.id_plano WHERE a.id_contrato_plano_pessoa = '".$id_contrato_plano_pessoa."' ", "b.id_pessoa, a.id_contrato_plano_pessoa, a.nome_contrato, b.nome, b.cpf_cnpj, b.razao_social, c.cod_servico AS 'servico', c.nome AS 'plano' ");
	if($dados){
		$nome_contrato = $dados[0]['nome']." - ".getNomeServico($dados[0]['servico'])." - ".$dados[0]['plano']." (".$dados[0]['id_contrato_plano_pessoa'].")";
		$contrato_input = $id_contrato_plano_pessoa;
	}else{
		$nome_contrato = '';
		$contrato_input ='';
	}
}

$id_usuario = (!empty($_POST['id_usuario'])) ? $_POST['id_usuario'] : '';

if($gerar){
	$collapse = '';
	$collapse_icon = 'plus';
	
}else{
	$collapse = 'in';
	$collapse_icon = 'minus';
	$nome_estoque_item = '';
	$estoque_item_input ='';
	$nome_pessoa = '';
	$nome_pessoa_input ='';
}

if($tipo_relatorio == 1){
	$display_row_usuario = '';
	$display_row_contrato = '';
	$display_row_data = '';
	$display_row_data_periodo = 'style="display:none;"';
	$display_row_ticket_state = '';

}else if($tipo_relatorio == 2){
	$display_row_usuario = 'style="display:none;"';
	$display_row_contrato = '';
	$display_row_data = 'style="display:none;"';
	$display_row_data_periodo = '';
	$display_row_ticket_state = 'style="display:none;"';

}else if($tipo_relatorio == 3){
	$display_row_usuario = 'style="display:none;"';
	$display_row_contrato = 'style="display:none;"';
	$display_row_data = '';
    $display_row_data_periodo = 'style="display:none;"';
	$display_row_ticket_state = 'style="display:none;"';
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
<script src="https://code.highcharts.com/7.2.1/highcharts.js"></script>
<script src="https://code.highcharts.com/7.2.1/modules/exporting.js"></script>
<script src="https://code.highcharts.com/7.2.1/modules/export-data.js"></script>

<div class="container-fluid">
	<form method="post" action="">
	    <div class="row">
	        <div class="col-md-4 col-md-offset-4">
	            <div class="panel panel-default noprint">
	                <div class="panel-heading clearfix">
	                    <h3 class="panel-title text-left pull-left" style="margin-top: 2px;">Relatório - Atendimentos - Redes:</h3>
	                    <div class="panel-title text-right pull-right"><button data-toggle="collapse" data-target="#accordionRelatorio" class="btn btn-xs btn-info" type="button" title="Visualizar filtros"><i id="i_collapse" class="fa fa-<?=$collapse_icon?>"></i></button></div>
	                </div>
	                <div id="accordionRelatorio" class="panel-collapse collapse <?=$collapse?>">
	                	<div class="panel-body">

	                		<div class="row">
                				<div class="col-md-12">
                					<div class="form-group">
								        <label>*Tipo de Relatório:</label>
								        <select name="tipo_relatorio" id="tipo_relatorio" class="form-control input-sm">
                                            <option value="1" <?php if($tipo_relatorio == '1'){ echo 'selected';}?>>Atendimentos</option>
                                            <option value="2" <?php if($tipo_relatorio == '2'){ echo 'selected';}?>>Contagens</option>
											<option value="3" <?php if($tipo_relatorio == '3'){ echo 'selected';}?>>Gráfico de Atendimentos por Dia da Semana - Acumulado Geral</option>
								        </select>
								    </div>
                				</div>
                            </div> 
                            
                            <div class="row" id="row_contrato" <?=$display_row_contrato?>>
                				<div class="col-md-12">
                					<div class="form-group">
                                        <label>Contrato (cliente):</label>
                                        <div class="input-group">
                                            <input class="form-control input-sm" id="busca_contrato" type="text" name="busca_contrato"  value="<?=$nome_contrato?>" placeholder="Informe o nome ou CNPJ..." autocomplete="off" readonly />
                                            <div class="input-group-btn">
                                                <button class="btn btn-info btn-sm" id="habilita_busca_contrato" name="habilita_busca_contrato" type="button" title="Clique para selecionar o contrato" style="height: 30px;"><i class="fa fa-search"></i></button>
                                            </div>
                                        </div>
                                     
                                        <input type='hidden' name='id_contrato_plano_pessoa' id='id_contrato_plano_pessoa' value='<?=$id_contrato_plano_pessoa?>' />

                                    </div>
                                </div>
                            </div>
							
							<div class="row" id="row_data" <?=$display_row_data?>>
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

                            <div class="row" id="row_usuario" <?=$display_row_usuario?>>
                                <div class="col-md-12">
                                    <div class="form-group has-feedback">
                                        <label>*Técnico:</label>
                                        <select class="form-control input-sm" id="id_usuario" name="id_usuario">
                                            <option value=''>Todos</option>
                                            <?php
                                                $dados_usuario = DBRead('', 'tb_perfil_sistema a', "INNER JOIN tb_usuario b ON a.id_perfil_sistema = b.id_perfil_sistema INNER JOIN tb_pessoa c ON b.id_pessoa = c.id_pessoa WHERE (a.id_perfil_sistema = 6 OR a.id_perfil_sistema = 26) AND b.status = 1 ORDER BY c.nome ASC","b.id_usuario, c.nome");
                                                if ($dados_usuario) {
                                                    foreach ($dados_usuario as $conteudo_usuario) {
                                                        $selected = $id_usuario == $conteudo_usuario['id_usuario'] ? "selected" : "";
                                                        echo "<option value='".$conteudo_usuario['id_usuario']."' ".$selected.">".$conteudo_usuario['nome']."</option>";
                                                    }
                                                }
                                            ?>
                                        </select>  
                                    </div>
                                </div>
                            </div>							
							<div class="row" id="row_data_periodo" <?=$display_row_data_periodo?>>
                                <div class="col-md-6">
                                    <div class="form-group has-feedback">
                                        <label>*Mês:</label>
                                        <select class="form-control input-sm" id="data_periodo_mes" name="data_periodo_mes">
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

											foreach ($meses as $key => $mes) {
                                                $selected = $data_periodo_mes == $key ? "selected" : "";
												echo "<option value='".$key."' ".$selected.">".$mes."</option>";
											}
                                            ?>
                                        </select>  
                                    </div>
								</div>
								
								<div class="col-md-6">
                                    <div class="form-group has-feedback">
                                        <label>*Ano:</label>
                                        <select class="form-control input-sm" id="data_periodo_ano" name="data_periodo_ano">
											<?php
											$anos = array(
												"2019" => "2019",
												"2020" => "2020",
												"2021" => "2021",
												"2022" => "2022",
												"2023" => "2023",
												"2024" => "2024",
												"2025" => "2025",
												"2026" => "2026"
											);     

											foreach ($anos as $key => $ano) {
                                                $selected = $data_periodo_ano == $key ? "selected" : "";
												echo "<option value='".$key."' ".$selected.">".$ano."</option>";
											}
                                            ?>
                                        </select>  
                                    </div>
								</div>
								
                            </div>

                            <div class="row" id="row_ticket_state" <?=$display_row_ticket_state?>>
                                <div class="col-md-12">
                                    <div class="form-group has-feedback">
                                        <label>*Status:</label>
                                        <select class="form-control input-sm" id="ticket_state" name="ticket_state">
                                            <option value=''>Todos</option>
                                            <?php
                                                
                                                $dados_ticket_state = DBRead('otrs',"ticket_state","ORDER BY name ASC");    
                                                if ($dados_ticket_state) {
                                                    foreach ($dados_ticket_state as $conteudo_ticket_state) {
                                                        $selected = $ticket_state == $conteudo_ticket_state['id'] ? "selected" : "";
                                                        echo "<option value='".$conteudo_ticket_state['id']."' ".$selected.">".ucfirst($conteudo_ticket_state['name'])."</option>";
                                                    }
                                                }
                                            ?>
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
		if($gerar){
			if($tipo_relatorio == 1){
				relatorio_atendimentos($data_de, $data_ate, $id_contrato_plano_pessoa, $id_usuario, $ticket_state);			
			}else if($tipo_relatorio == 2){
                relatorio_contagens($data_periodo_mes, $data_periodo_ano, $id_contrato_plano_pessoa);
			}else if($tipo_relatorio == 3){
				relatorio_grafico_dia_semana_acumulado_geral($data_de, $data_ate);
			}
		}
		?>
	</div>
</div>

<script>

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
                            'cod_servico' : 'gestao_redes'
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

	$('#tipo_relatorio').on('change',function(){
		tipo_relatorio = $(this).val();
		if(tipo_relatorio == 1){
			$('#row_usuario').show();
			$('#row_contrato').show();
			$('#row_data').show();
			$('#row_data_periodo').hide();
			$('#row_ticket_state').show();
			
		}else if(tipo_relatorio == 2){
			$('#row_usuario').hide();
			$('#row_contrato').show();
			$('#row_data').hide();
			$('#row_data_periodo').show();
			$('#row_ticket_state').hide();
			
		}else if(tipo_relatorio == 3){
			$('#row_usuario').hide();
			$('#row_contrato').hide();
			$('#row_data').show();
			$('#row_data_periodo').hide();	
			$('#row_ticket_state').hide();
		}
	}); 

</script>

<?php 

function relatorio_atendimentos($data_de, $data_ate, $id_contrato_plano_pessoa, $id_usuario, $ticket_state){

    $data_hoje = converteDataHora(getDataHora());
   
	if($id_contrato_plano_pessoa){
        $dados_id_contrato_plano_pessoa = DBRead('', 'tb_contrato_plano_pessoa a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa INNER JOIN tb_parametro_redes_contrato c ON a.id_contrato_plano_pessoa = c.id_contrato_plano_pessoa WHERE a.id_contrato_plano_pessoa = '".$id_contrato_plano_pessoa."' ", "b.nome, c.id_otrs");
		$legenda_id_contrato_plano_pessoa = $dados_id_contrato_plano_pessoa[0]['nome'];
        $filtro_id_contrato_plano_pessoa = " AND a.customer_id = '".$dados_id_contrato_plano_pessoa[0]['id_otrs']."'";
	}else{
		$legenda_id_contrato_plano_pessoa = "Todos";
	}

	if($id_usuario){
        $dados_id_usuario = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_usuario = '".$id_usuario."' ");
        $legenda_id_usuario = $dados_id_usuario[0]['nome'];        
        $filtro_id_usuario = " AND b.create_by = '".$dados_id_usuario[0]['id_otrs']."'";
	}else{
		$legenda_id_usuario = "Todos";
    }
    
    if($ticket_state){
        $dados_ticket_state = DBRead('otrs',"ticket_state","WHERE id = '".$ticket_state."' ");    
        $legenda_ticket_state = ucfirst($dados_ticket_state[0]['name']);
        $filtro_ticket_state = " AND a.ticket_state_id = '".$dados_ticket_state[0]['id']."' ";
	}else{
		$legenda_ticket_state = "Todos";
	}


	$periodo_amostra = "<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> De $data_de até $data_ate</span>";
	$gerado = "<span style=\"font-size: 14px;\"><strong>Gerado em: </strong> $data_hoje</span>";

	echo "<div class=\"col-md-12\" style=\"padding: 0\">";
	echo "<legend style=\"text-align:center;\"><strong>Relatório de Atendimentos</strong><br>$gerado</legend>";
    echo "<legend style=\"text-align:center;\">$periodo_amostra</legend>";
    echo "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong> Contrato - </strong>".$legenda_id_contrato_plano_pessoa.",<strong> Técnico - </strong>".$legenda_id_usuario.",<strong> Status - </strong>".$legenda_ticket_state."</legend>";
    
    $dados_id_otrs_contrato = DBRead('','tb_parametro_redes_contrato',"WHERE id_otrs IS NOT NULL AND id_otrs != ''","id_otrs");
    if($dados_id_otrs_contrato){
        $filtro_id_otrs_contrato = " AND a.customer_id IN (";
        foreach ($dados_id_otrs_contrato as $conteudo_id_otrs_contrato) {
            $filtro_id_otrs_contrato .= "'".$conteudo_id_otrs_contrato['id_otrs']."',";
        }
        $filtro_id_otrs_contrato = substr($filtro_id_otrs_contrato, 0, -1);
        $filtro_id_otrs_contrato .= ")";
    }else{
        $filtro_id_otrs_contrato = '';
    }

    $dados_atendimentos = DBRead('otrs',"ticket a","INNER JOIN article b ON a.id = b.ticket_id INNER JOIN time_accounting c ON b.id = c.article_id INNER JOIN ticket_state d ON a.ticket_state_id = d.id WHERE (a.queue_id = 2 OR a.queue_id = 42) AND b.create_time >= '".converteData($data_de)." 00:00:00' AND b.create_time <= '".converteData($data_ate)." 23:59:59' $filtro_id_usuario $filtro_id_contrato_plano_pessoa $filtro_id_otrs_contrato $filtro_ticket_state GROUP BY a.tn, b.create_by","a.tn, a.title, a.customer_id, a.create_time, SUM(c.time_unit) AS 'time_unit', b.create_by, d.name");    
    
    if($dados_atendimentos){
        
        // ______________________________________________
        // ESTAVA NO COMEÇO
        $dados_usuarios = DBRead('',"tb_usuario a", "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE id_otrs");
        $nomes_usuarios = array();
        if($dados_usuarios){
            foreach ($dados_usuarios as $conteudo_usuario) {
                $nomes_usuarios[$conteudo_usuario['id_otrs']] = $conteudo_usuario['nome'];
            }
        }

        $dados_contratos = DBRead('','tb_contrato_plano_pessoa a',"INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa INNER JOIN tb_plano c ON a.id_plano = c.id_plano INNER JOIN tb_parametro_redes_contrato d ON a.id_contrato_plano_pessoa = d.id_contrato_plano_pessoa INNER JOIN tb_usuario e ON d.id_responsavel = e.id_usuario INNER JOIN tb_pessoa f ON e.id_pessoa = f.id_pessoa WHERE c.cod_servico = 'gestao_redes' ORDER BY b.nome ASC", "b.nome, d.id_otrs");
        $nomes_contratos = array();
        if($dados_contratos){
            foreach ($dados_contratos as $conteudo_contrato) {
                $nomes_contratos[$conteudo_contrato['id_otrs']] = $conteudo_contrato['nome'];;
            }
        }

        // ______________________________________________

		echo "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong> Total de Atendimentos: </strong>".sizeof($dados_atendimentos)."</legend>";

		echo '
            <table class="table table-striped dataTable" style="margin-bottom:0;">
                <thead>
                    <tr>
                        <th>ContatoID#</th>
                        <th>Contrato</th>
                        <th>Assunto</th>
                        <th>Técnico</th>	
                        <th>Status</th>			
                        <th>Tempo</th>		
                        <th>Tempo da Primeira Resposta</th>		
                    </tr>
                </thead>
                <tbody>
        ';

        $tempo_total = 0;
        $qtd_tpr = 0;
        $tpr_total = 0;
        foreach($dados_atendimentos as $conteudo_atendimento){
            $tempo = intval($conteudo_atendimento['time_unit']);

            if($conteudo_atendimento['create_time'] >= converteData($data_de)." 00:00:00" && $conteudo_atendimento['create_time'] <= converteData($data_ate)." 23:59:59"){
                $tpr = getTempoPrimeiraRespostaOTRS($conteudo_atendimento['tn'], $conteudo_atendimento['create_by']);
                if($tpr > 0){
                    $qtd_tpr ++;
                    $tpr_total += $tpr;
                    $descricao_tpr = converteSegundosHoras($tpr*60);
                }else if($tpr == 0){
                    $descricao_tpr = converteSegundosHoras($tpr*60);
                }else if($tpr == -1){
                    $descricao_tpr = 'Erro de datas';
                }else if($tpr == -2){
                    $descricao_tpr = 'Respondido por outro técnico';
                }
                
            }else{
                $descricao_tpr = 'Entrou antes do período selecionado';
            }

			echo '
                <tr>
                    <td style="vertical-align: middle;">'.$conteudo_atendimento['tn'].'</td>
                    <td style="vertical-align: middle;">'.($nomes_contratos[$conteudo_atendimento['customer_id']] ? $nomes_contratos[$conteudo_atendimento['customer_id']] : '000 - '.$conteudo_atendimento['customer_id']).'</td>
                    <td style="vertical-align: middle;">'.$conteudo_atendimento['title'].'</td>
                    <td style="vertical-align: middle;">'.($nomes_usuarios[$conteudo_atendimento['create_by']] ? $nomes_usuarios[$conteudo_atendimento['create_by']] : $conteudo_atendimento['create_by']).'</td>
                    <td style="vertical-align: middle;">'.ucfirst($conteudo_atendimento['name']).'</td>
                    <td style="vertical-align: middle;">'.converteSegundosHoras($tempo*60).'</td>
                    <td style="vertical-align: middle;">'.$descricao_tpr.'</td>
                </tr>
            ';
            $tempo_total += $tempo;
        }
           
        echo "
                </tbody>    
                <tfoot>
                    <tr>
                        <th>Totais</th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th>".converteSegundosHoras($tempo_total*60)."</th>		
                        <th>".converteSegundosHoras($tpr_total*60)."</th>
                    </tr>
                    <tr>
                        <th>Médias</th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th>".converteSegundosHoras($tempo_total/sizeof($dados_atendimentos)*60)."</th>		
                        <th>".converteSegundosHoras(($tpr_total/$qtd_tpr)*60)."</th>
                    </tr>
                </tfoot>  
           </table>
        ";
        
        echo "<hr>";

        echo "<script>
				$(document).ready(function(){
					var table = $('.dataTable').DataTable({
						\"language\": {
							\"url\": \"//cdn.datatables.net/plug-ins/1.10.19/i18n/Portuguese-Brasil.json\"
						},
						aaSorting: [[0, 'asc']],
						columnDefs: [
							{ type: 'chinese-string', targets: 0 },
						],				        
						\"searching\": false,
						\"paging\":   false,
						\"info\":     false
					});

					var buttons = new $.fn.dataTable.Buttons(table, {
						buttons: [
							{
								extend: 'excelHtml5',
								text: '<i class=\"fa fa-file-excel-o\" aria-hidden=\"true\"></i> Exportar',
								filename: 'relatorio_atendimentos_gestao_redes',
								title : null,
								exportOptions: {
									modifier: {
									page: 'all'
									}
								}
								},
						],	
						dom:
						{
							button: {
								tag: 'button',
								className: 'btn btn-default'
							},
							buttonLiner: { tag: null }
						}
					}).container().appendTo($('#panel_buttons'));
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
    
    echo "</div>";
}

function relatorio_contagens($data_periodo_mes, $data_periodo_ano, $id_contrato_plano_pessoa){

	$data_hoje = converteDataHora(getDataHora());

	if($id_contrato_plano_pessoa){
        $filtro_id_contrato_plano_pessoa = "AND a.id_contrato_plano_pessoa = '".$id_contrato_plano_pessoa."' ";
        $dados_id_contrato_plano_pessoa = DBRead('', 'tb_contrato_plano_pessoa a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_contrato_plano_pessoa = '".$id_contrato_plano_pessoa."' ", "b.nome");
		$legenda_id_contrato_plano_pessoa = $dados_id_contrato_plano_pessoa[0]['nome'];
	
	}else{
		$legenda_id_contrato_plano_pessoa = "Todos";
	}

	$data_de = new DateTime($data_periodo_ano.'-'.$data_periodo_mes.'-01');
	$data_de->modify('first day of this month');
	$data_de = $data_de->format('Y-m-d');

	$data_ate = new DateTime($data_periodo_ano.'-'.$data_periodo_mes.'-01');
	$data_ate->modify('last day of this month');
	$data_ate = $data_ate->format('Y-m-d');

	$periodo_amostra = "<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> De ".converteData($data_de)." até ".converteData($data_ate)."</span>";
	$gerado = "<span style=\"font-size: 14px;\"><strong>Gerado em: </strong> $data_hoje</span>";

	echo "<div class=\"col-md-10 col-md-offset-1\" style=\"padding: 0\">";
	echo "<legend style=\"text-align:center;\"><strong>Relatório de Contagens de Atendimentos</strong><br>$gerado</legend>";
    echo "<legend style=\"text-align:center;\">$periodo_amostra</legend>";
    echo "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong> Contrato - </strong>".$legenda_id_contrato_plano_pessoa."</legend>";
    
    $dados_usuarios = DBRead('',"tb_usuario a", "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE id_otrs");
    $nomes_usuarios = array();
    if($dados_usuarios){
        foreach ($dados_usuarios as $conteudo_usuario) {
            $nomes_usuarios[$conteudo_usuario['id_otrs']] = $conteudo_usuario['nome'];
        }
    }
    
    $dados_contratos = DBRead('','tb_contrato_plano_pessoa a',"INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa INNER JOIN tb_plano c ON a.id_plano = c.id_plano INNER JOIN tb_parametro_redes_contrato d ON a.id_contrato_plano_pessoa = d.id_contrato_plano_pessoa INNER JOIN tb_usuario e ON d.id_responsavel = e.id_usuario INNER JOIN tb_pessoa f ON e.id_pessoa = f.id_pessoa WHERE c.cod_servico = 'gestao_redes' AND a.status != 5 AND (a.status = 1 OR a.data_status >= '$data_de') $filtro_id_contrato_plano_pessoa ORDER BY b.nome ASC", "a.*, b.nome AS 'nome_cliente', d.*, f.nome AS 'nome_responsavel'");  

	if($dados_contratos){

        echo "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong> Total de Contratos: </strong>".sizeof($dados_contratos)."</legend>";

        foreach ($dados_contratos as $conteudo_contrato) {

            //usar o GROUP BY a.tn para agrupar por ticket number e juntar os tempos de articles do mesmo ticket
            $dados_atendimentos = DBRead('otrs',"ticket a","INNER JOIN article b ON a.id = b.ticket_id INNER JOIN time_accounting c ON b.id = c.article_id WHERE a.customer_id = '".$conteudo_contrato['id_otrs']."' AND (a.queue_id = 2 OR a.queue_id = 42) AND b.create_time >= '$data_de 00:00:00' AND b.create_time <= '$data_ate 23:59:59' GROUP BY a.tn","a.tn, a.title, a.responsible_user_id, a.create_time, SUM(c.time_unit) AS 'tempo_total'");    
            
            echo
            ' <div class="panel panel-primary">
                <div class="panel-heading clearfix">
                    <div class="row">
                        <h3 class="panel-title text-center col-md-12"><strong>'.$conteudo_contrato['nome_cliente'].'</strong></h3>
                    </div>
                </div>
                <div class="panel-body painel-body">';


            if($dados_atendimentos){
                
                $tempo_total_contrato = 0;
                $qtd_tpr = 0;
                $tpr_total = 0;
                $contador = 0;
                foreach($dados_atendimentos as $conteudo_atendimento){

                    $tempo_total = 0;

                    $dados_acoes = DBRead('otrs',"ticket a","INNER JOIN article b ON a.id = b.ticket_id INNER JOIN time_accounting c ON b.id = c.article_id WHERE a.customer_id = '".$conteudo_contrato['id_otrs']."' AND a.tn = '".$conteudo_atendimento['tn']."' AND b.create_time >= '$data_de 00:00:00' AND b.create_time <= '$data_ate 23:59:59' ","a.tn, a.title, b.create_by, b.create_time, c.time_unit");    

                    echo
                    ' <div class="panel panel-default">
                        <div class="panel-heading clearfix">
                            <div class="row">
                                <h3 class="panel-title text-left col-md-3"><strong>ContatoID#: </strong>'.$conteudo_atendimento['tn'].'</h3>
                                <h3 class="panel-title text-right col-md-9"><strong>Assunto: </strong>'.$conteudo_atendimento['title'].'</h3>
                            </div>
                        </div>
                        <div class="panel-body painel-body">';

                            echo '
                            <table class="table table-striped dataTable" style="margin-bottom:0;">
                                <thead>
                                    <tr>
                                        <th class="col-md-4">Data</th>
                                        <th class="col-md-4">Técnico</th>
                                        <th class="col-md-4">Tempo</th>			
                                    </tr>
                                </thead>
                                <tbody>
                            ';

                                foreach($dados_acoes as $conteudo_acoes){
                                    $tempo_chamado_otrs = intval($conteudo_acoes['time_unit']); 

                                    echo '
                                    <tr>
                                        <td data-order="'.$conteudo_acoes['create_time'].'">'.converteDataHora($conteudo_acoes['create_time']).'</td>
                                        <td>'.$nomes_usuarios[$conteudo_acoes['create_by']].'</td>
                                        <td>'.converteSegundosHoras($tempo_chamado_otrs*60).'</td>
                                    </tr>
                                    ';
                                    $tempo_total += $tempo_chamado_otrs;
                                }
                                echo "
                                </tbody>    
                                <tfoot>
                                    <tr>
                                        <th>Totais</th>                                    
                                        <th></th>
                                        <th>".converteSegundosHoras($tempo_total*60)."</th>
                                    </tr>
                                    <tr>
                                        <th>Médias</th>                                    
                                        <th></th>
                                        <th>".converteSegundosHoras($tempo_total/sizeof($dados_acoes)*60)."</th>
                                    </tr>
                                </tfoot>  
                            </table>                    
                            ";                        
                    
                        if($conteudo_atendimento['create_time'] >= $data_de." 00:00:00" && $conteudo_atendimento['create_time'] <= $data_ate." 23:59:59"){
                            $tpr = getTempoPrimeiraRespostaOTRS($conteudo_atendimento['tn']);
                            if($tpr > 0){
                                $qtd_tpr ++;
                                $tpr_total += $tpr;
                                $descricao_tpr = converteSegundosHoras($tpr*60);
                            }else if($tpr == 0){
                                $descricao_tpr = converteSegundosHoras($tpr*60);
                            }else if($tpr == -1){
                                $descricao_tpr = 'Erro de datas';
                            }else if($tpr == -2){
                                $descricao_tpr = 'Respondido por outro técnico';
                            }
                        }else{
                            $descricao_tpr = 'Entrou antes do período selecionado';
                        }

                        echo "
                       
                        </div>
                        
                        <div class='panel-footer'>
                            <div class='row'>
                            <h3 class='panel-title text-left col-md-4'><strong>Data da Criação: </strong>".converteDataHora($conteudo_atendimento['create_time'])."</h3>
                            <h3 class='panel-title text-center col-md-4'><strong>Tempo da Primeira Resposta: </strong>".$descricao_tpr."</h3>
                            <h3 class='panel-title text-right col-md-4'><strong>Responsável: </strong>".$nomes_usuarios[$conteudo_atendimento['responsible_user_id']]."</h3>
                            </div>
                        </div>
                    
                    </div>";

                    echo "<hr>";

                    $tempo_total_contrato += $tempo_total;
                }

                if($contador > 0){
                    $descricao_tpr_total = converteSegundosHoras(($tpr_total/$qtd_tpr)*60);
                }else{
                    $descricao_tpr_total = 'N/D';
                }
                echo "
                </div>
                <div class='panel-footer'>
                    <p class='text-center'><strong>Tempo Total dos Atendimentos: ".converteSegundosHoras($tempo_total_contrato*60)."</strong></p>
                    <p class='text-center'><strong>QTD Atendimentos: ".sizeof($dados_atendimentos)."</strong></p>
                    <p class='text-center'><strong>Tempo Médio por Atendimento: ".converteSegundosHoras($tempo_total_contrato/sizeof($dados_atendimentos)*60)."</strong></p>
                    <p class='text-center'><strong>Tempo Médio da Primeira Resposta: ".$descricao_tpr_total."</strong></p>
                </div>
            </div>";

            }else{
                echo '<div class="alert alert-info text-center">Não foram encontrados atendimentos.</div>';
                echo "
                </div>
            </div>";
            }

            echo "
                   
                <hr>
            ";
            
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
    
    echo "</div>";
}

function relatorio_grafico_dia_semana_acumulado_geral($data_de, $data_ate){
	$dias_semana = array('Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado');
    $atendimento = array('0'=>0, '1'=>0, '2'=>0, '3'=>0, '4'=>0, '5'=>0, '6'=>0);
	$data_hora = converteDataHora(getDataHora());
		
	$nome_tipo_grafico = 'line';
	
    if($data_de && $data_ate){
	    $periodo_amostra ="<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> De $data_de até $data_ate</span>";
	}elseif($data_de){
	    $periodo_amostra ="<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> A partir de $data_de</span>";
	}elseif($data_ate){
	    $periodo_amostra ="<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> Até $data_ate</span>";
	}else{
	    $periodo_amostra = "";
	}

	$gerado = "<span style=\"font-size: 14px;\"><strong>Gerado em: </strong> $data_hora</span>";

    echo "<div class=\"col-md-12\" style=\"padding: 0\">";
	echo "<legend style=\"text-align:center;\"><strong>Gráfico de Atendimentos por Dia da Semana - Acumulado Geral</strong><br>$gerado</legend>";
    echo "<legend style=\"text-align:center;\">$periodo_amostra</legend>";
    echo "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"></legend>";
    
    $dados_id_otrs_contrato = DBRead('','tb_parametro_redes_contrato',"WHERE id_otrs IS NOT NULL AND id_otrs != ''","id_otrs");
    if($dados_id_otrs_contrato){
        $filtro_id_otrs_contrato = " AND a.customer_id IN (";
        foreach ($dados_id_otrs_contrato as $conteudo_id_otrs_contrato) {
            $filtro_id_otrs_contrato .= "'".$conteudo_id_otrs_contrato['id_otrs']."',";
        }
        $filtro_id_otrs_contrato = substr($filtro_id_otrs_contrato, 0, -1);
        $filtro_id_otrs_contrato .= ")";
    }else{
        $filtro_id_otrs_contrato = '';
    }

	$chart = 0;
	foreach (rangeDatas(converteData($data_de), converteData($data_ate)) as $data) {		
		$numero_dia_semana = date('w', strtotime($data));

        $atendimento_dia = 0;

        $dados_atendimentos = DBRead('otrs',"ticket a","INNER JOIN article b ON a.id = b.ticket_id INNER JOIN time_accounting c ON b.id = c.article_id WHERE (a.queue_id = 2 OR a.queue_id = 42) AND a.create_time >= '$data 00:00:00' AND a.create_time <= '$data 23:59:59' $filtro_id_otrs_contrato GROUP BY a.tn","a.tn");
        			
		if($dados_atendimentos){
			$atendimento_dia = sizeof($dados_atendimentos);
		}else{
			$atendimento_dia = 0;
		}			

		$atendimento[$numero_dia_semana] += $atendimento_dia;
	      		
	}
	?>
        <div id="<?php echo "chart-" . $chart; ?>"></div> 
        <script>
            $(function () {
                // Create the first chart
                $('#<?php echo "chart-" . $chart; ?>').highcharts({
                    chart: {
                        type: '<?=$nome_tipo_grafico;?>'
                    },
                    title: {
                        text: '' // Title for the chart
                    },
                    xAxis: {
                        categories: <?php echo json_encode($dias_semana) ?>
                        // Categories for the charts
                    },
				    yAxis: {
				        min: 0,
				        title: {
				            text: 'Qauntidade de Atendimentos'
				        },
				        stackLabels: {
				            enabled: true,
				            style: {
				                fontWeight: 'bold',
				                color: (Highcharts.theme && Highcharts.theme.textColor) || 'black'
				            }
				        }
				    },
				    legend: {
				        enabled:true,
				        backgroundColor: (Highcharts.theme && Highcharts.theme.background2) || 'white',
				        borderColor: '#CCC',
				        borderWidth: 1,
				        shadow: false
				    },
				    tooltip: {
				        headerFormat: '<b>{point.x}</b><br/>',
				        pointFormat: '{series.name}: {point.y}'
				    },
                   								
                        colors: [
                            '#0000CD',
                        ],
                       
				    plotOptions: {
											
					        series: {
	                            dataLabels: {
	                                enabled: true
	                            }
	                        }
			    			
				    },
                    series: [
                    	
                    	{
                            name: 'Atendimentos', // Name of your series
                            data: <?php echo json_encode($atendimento, JSON_NUMERIC_CHECK) ?> // The data in your series

                        }],
                       
                    navigation: {
                        buttonOptions: {
                            enabled: true
                        }
                    }
                });
            });
        </script>   
        <?php
		echo '<hr>';
}

?>
