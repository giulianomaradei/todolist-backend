<?php
require_once(__DIR__."/../class/System.php");
include_once("class/FunctionOTRS.php");

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
	$display_row_usuario = 'style="display:none;"';
	$display_row_contrato = '';
	$display_row_data = '';
}else if($tipo_relatorio == 2){
	$display_row_usuario = '';
	$display_row_contrato = 'style="display:none;"';
	$display_row_data = '';
}else if($tipo_relatorio == 3){
	$display_row_usuario = 'style="display:none;"';
	$display_row_contrato = 'style="display:none;"';
	$display_row_data = 'style="display:none;"';
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
	                    <h3 class="panel-title text-left pull-left" style="margin-top: 2px;">Relatório - Indicadores - Redes:</h3>
	                    <div class="panel-title text-right pull-right"><button data-toggle="collapse" data-target="#accordionRelatorio" class="btn btn-xs btn-info" type="button" title="Visualizar filtros"><i id="i_collapse" class="fa fa-<?=$collapse_icon?>"></i></button></div>
	                </div>
	                <div id="accordionRelatorio" class="panel-collapse collapse <?=$collapse?>">
	                	<div class="panel-body">

	                		<div class="row">
                				<div class="col-md-12">
                					<div class="form-group">
								        <label>*Tipo de Relatório:</label>
								        <select name="tipo_relatorio" id="tipo_relatorio" class="form-control input-sm">
                                            <option value="1" <?php if($tipo_relatorio == '1'){ echo 'selected';}?>>Contratos</option>
                                            <?php
											if ($perfil_sistema == 2 || $perfil_sistema == 20 || $perfil_sistema == 16 || $perfil_sistema == 6 || $perfil_sistema == 26) {
											?>
                                            <option value="2" <?php if($tipo_relatorio == '2'){ echo 'selected';}?>>Técnicos</option>
                                            <?php
											}
											?>
                                            <option value="3" <?php if($tipo_relatorio == '3'){ echo 'selected';}?>>Utilização de Contrato - Plano Horas</option>
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
				relatorio_contratos($data_de, $data_ate, $id_contrato_plano_pessoa);			
			}else if($tipo_relatorio == 2){
                if ($perfil_sistema == 2 || $perfil_sistema == 20 || $perfil_sistema == 16 || $perfil_sistema == 6 || $perfil_sistema == 26) {
                    relatorio_tecnicos($data_de, $data_ate, $id_usuario);	
                }		
			}else if($tipo_relatorio == 3){
				relatorio_utilizacao_plano_horas();			
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
			$('#row_usuario').hide();
			$('#row_contrato').show();
			$('#row_data').show();			
		}else if(tipo_relatorio == 2){
			$('#row_usuario').show();
			$('#row_contrato').hide();
			$('#row_data').show();			
		}else if(tipo_relatorio == 3){
			$('#row_usuario').hide();
			$('#row_contrato').hide();
			$('#row_data').hide();			
		}
	}); 

</script>

<?php 

function relatorio_contratos($data_de, $data_ate, $id_contrato_plano_pessoa){

    $data_hoje = converteDataHora(getDataHora());

	if($id_contrato_plano_pessoa){
        $dados_id_contrato_plano_pessoa = DBRead('', 'tb_contrato_plano_pessoa a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa INNER JOIN tb_parametro_redes_contrato c ON a.id_contrato_plano_pessoa = c.id_contrato_plano_pessoa WHERE a.id_contrato_plano_pessoa = '".$id_contrato_plano_pessoa."' ", "b.nome, c.id_otrs");
		$legenda_id_contrato_plano_pessoa = $dados_id_contrato_plano_pessoa[0]['nome'];
        $filtro_id_contrato_plano_pessoa = "AND a.id_contrato_plano_pessoa = '".$id_contrato_plano_pessoa."' ";
	}else{
		$legenda_id_contrato_plano_pessoa = "Todos";
	}

	$periodo_amostra = "<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> De $data_de até $data_ate</span>";
	$gerado = "<span style=\"font-size: 14px;\"><strong>Gerado em: </strong> $data_hoje</span>";

	echo "<div class=\"col-md-12\" style=\"padding: 0\">";
	echo "<legend style=\"text-align:center;\"><strong>Relatório de Indicadores - Contratos</strong><br>$gerado</legend>";
    echo "<legend style=\"text-align:center;\">$periodo_amostra</legend>";
    echo "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong> Contrato - </strong>".$legenda_id_contrato_plano_pessoa."</legend>";

    $data_de = converteData($data_de);
    $data_ate = converteData($data_ate);
    
    $dados_contratos = DBRead('','tb_contrato_plano_pessoa a',"INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa INNER JOIN tb_plano c ON a.id_plano = c.id_plano INNER JOIN tb_parametro_redes_contrato d ON a.id_contrato_plano_pessoa = d.id_contrato_plano_pessoa INNER JOIN tb_usuario e ON d.id_responsavel = e.id_usuario INNER JOIN tb_pessoa f ON e.id_pessoa = f.id_pessoa WHERE c.cod_servico = 'gestao_redes' AND a.status != 5 AND (a.status = 1 OR a.data_status >= '$data_de') $filtro_id_contrato_plano_pessoa ORDER BY b.nome ASC", "a.*, b.nome AS 'nome_cliente', d.*, f.nome AS 'nome_responsavel'");  

	if($dados_contratos){
        $total_tempo_atendimento = 0;
        $total_qtd_atendimento = 0;
        $total_tempo_plantao = 0;
        $total_qtd_plantao = 0;
        $total_tpr_atendimento = 0;
        $total_qtd_tpr_atendimento = 0;

        echo '
        <table class="table table-striped dataTable" style="margin-bottom:0;">
            <thead>
                <tr>
                    <th>Cliente</th>	
                    <th>Tempo em Atendimento</th>	
                    <th>QTD Atendimentos</th>	
                    <th>Tempo Médio por Atendimento</th>	
                    <th>Tempo em Plantão</th>	
                    <th>QTD Plantões</th>	
                    <th>Tempo Médio por Plantão</th>		
                    <th>Tempo Médio da Primeira Resposta</th>		
                </tr>
            </thead>
            <tbody>
        ';

        foreach ($dados_contratos as $conteudo_contrato) {

            $dados_tempo_atendimentos = DBRead('otrs',"ticket a","INNER JOIN article b ON a.id = b.ticket_id INNER JOIN time_accounting c ON b.id = c.article_id WHERE a.customer_id = '".$conteudo_contrato['id_otrs']."' AND a.queue_id = 2 AND b.create_time >= '$data_de 00:00:00' AND b.create_time <= '$data_ate 23:59:59'","SUM(c.time_unit) AS 'tempo'");  
            $tempo_atendimento = $dados_tempo_atendimentos[0]['tempo'] ? intval($dados_tempo_atendimentos[0]['tempo']) : 0;

            $dados_qtd_atendimentos = DBRead('otrs',"ticket a","INNER JOIN article b ON a.id = b.ticket_id INNER JOIN time_accounting c ON b.id = c.article_id WHERE a.customer_id = '".$conteudo_contrato['id_otrs']."' AND a.queue_id = 2 AND b.create_time >= '$data_de 00:00:00' AND b.create_time <= '$data_ate 23:59:59' GROUP BY a.tn","a.tn, a.create_time"); 
            $qtd_atendimento = $dados_qtd_atendimentos ? sizeof($dados_qtd_atendimentos) : 0;

            $dados_tempo_plantoes = DBRead('otrs',"ticket a","INNER JOIN article b ON a.id = b.ticket_id INNER JOIN time_accounting c ON b.id = c.article_id WHERE a.customer_id = '".$conteudo_contrato['id_otrs']."' AND a.queue_id = 10 AND a.create_time >= '$data_de 00:00:00' AND a.create_time <= '$data_ate 23:59:59'","SUM(c.time_unit) AS 'tempo'");  
            $tempo_plantao = $dados_tempo_plantoes[0]['tempo'] ? intval($dados_tempo_plantoes[0]['tempo']) : 0;

            $dados_qtd_plantoes = DBRead('otrs',"ticket a","INNER JOIN article b ON a.id = b.ticket_id INNER JOIN time_accounting c ON b.id = c.article_id WHERE a.customer_id = '".$conteudo_contrato['id_otrs']."' AND a.queue_id = 10 AND a.create_time >= '$data_de 00:00:00' AND a.create_time <= '$data_ate 23:59:59' GROUP BY a.tn","a.tn"); 
            $qtd_plantao = $dados_qtd_plantoes ? sizeof($dados_qtd_plantoes) : 0;
            
            //TPR
            $qtd_tpr = 0;
            $tpr_total = 0;
            $tpr = 0;

            $contador = 0;

            foreach ($dados_qtd_atendimentos as $conteudo_atendimento) {
                if($conteudo_atendimento['create_time'] >= $data_de." 00:00:00" && $conteudo_atendimento['create_time'] <= $data_ate." 23:59:59"){
                    $tpr = getTempoPrimeiraRespostaOTRS($conteudo_atendimento['tn']);
                    if($tpr > 0){
                        $qtd_tpr ++;
                        $tpr_total += $tpr;
                    }
                    $contador++;
                }
            }
            if($contador > 0){
                $descricao_tpr = converteSegundosHoras(($tpr_total/$qtd_tpr)*60);
            }else{
                $descricao_tpr = 'N/D';
            }

            echo '
                <tr>
                    <td>'.$conteudo_contrato['nome_cliente'].'</td>
                    <td>'.converteSegundosHoras($tempo_atendimento*60).'</td>
                    <td>'.$qtd_atendimento.'</td>
                    <td>'.converteSegundosHoras($tempo_atendimento*60/$qtd_atendimento).'</td>
                    <td>'.converteSegundosHoras($tempo_plantao*60).'</td>
                    <td>'.$qtd_plantao.'</td>
                    <td>'.converteSegundosHoras($tempo_plantao*60/$qtd_plantao).'</td>
                    <td>'.$descricao_tpr.'</td>
                </tr>
            ';

            $total_tempo_atendimento += $tempo_atendimento;
            $total_qtd_atendimento += $qtd_atendimento;
            $total_tempo_plantao += $tempo_plantao;
            $total_qtd_plantao += $qtd_plantao;
            $total_tpr_atendimento += $tpr_total;
            $total_qtd_tpr_atendimento += $qtd_tpr;
        }

        echo "
            </tbody>    
            <tfoot>
                <tr>
                    <th>Totais</th>                                    
                    <th>".converteSegundosHoras($total_tempo_atendimento*60)."</th>
                    <th>".$total_qtd_atendimento."</th>
                    <th>".converteSegundosHoras($total_tempo_atendimento*60/$total_qtd_atendimento)."</th>
                    <th>".converteSegundosHoras($total_tempo_plantao*60)."</th>
                    <th>".$total_qtd_plantao."</th>
                    <th>".converteSegundosHoras($total_tempo_plantao*60/$total_qtd_plantao)."</th>
                    <th>".converteSegundosHoras(($total_tpr_atendimento/$total_qtd_tpr_atendimento)*60)."</th>
                </tr>
            </tfoot>  
        </table>                    
        ";

        echo "
        <script>
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

function relatorio_tecnicos($data_de, $data_ate, $id_usuario){

    $data_hoje = converteDataHora(getDataHora());

	if($id_usuario){
        $dados_id_usuario = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_usuario = '".$id_usuario."' ");
        $legenda_id_usuario = $dados_id_usuario[0]['nome'];        
        $filtro_id_usuario = " AND a.id_usuario = '".$id_usuario."'";
	}else{
		$legenda_id_usuario = "Todos";
	}


	$periodo_amostra = "<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> De $data_de até $data_ate</span>";
	$gerado = "<span style=\"font-size: 14px;\"><strong>Gerado em: </strong> $data_hoje</span>";

	echo "<div class=\"col-md-12\" style=\"padding: 0\">";
	echo "<legend style=\"text-align:center;\"><strong>Relatório de Indicadores - Técnicos</strong><br>$gerado</legend>";
    echo "<legend style=\"text-align:center;\">$periodo_amostra</legend>";
    echo "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong> Técnico - </strong>".$legenda_id_usuario."</legend>";

    $data_de = converteData($data_de);
    $data_ate = converteData($data_ate);

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

    $dados_usuario = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON b.id_pessoa = a.id_pessoa WHERE a.id_perfil_sistema = 26 AND a.status = 1 AND a.id_otrs $filtro_id_usuario ORDER BY b.nome ASC","a.*, b.nome");

    if($dados_usuario){
        $total_tempo_atendimento = 0;
        $total_qtd_atendimento = 0;
        $total_tempo_plantao = 0;
        $total_qtd_plantao = 0;
        $total_tempo_atividade_interna = 0;
        $total_qtd_atividade_interna = 0;
        $total_tempo_ponto = 0;
        $total_tpr_atendimento = 0;
        $total_qtd_tpr_atendimento = 0;

        echo '
        <div style=\"overflow-x:auto;\">
            <table class="table table-striped dataTable" style="margin-bottom:0;">
                <thead>
                    <tr>
                        <th>Técnico</th>	
                        <th>Tempo em Atendimento</th>	
                        <th>QTD Atendimentos</th>	
                        <th>Tempo Médio por Atendimento</th>	
                        <th>Tempo em Plantão</th>	
                        <th>QTD Plantões</th>	
                        <th>Tempo Médio por Plantão</th>	
                        <th>Tempo em Atividade Interna</th>	
                        <th>QTD Atividades Internas</th>	
                        <th>Tempo Médio por Atividade Interna</th>	
                        <th>Tempo Total no Ponto</th>	
                        <th>Tempo Total Trabalhado</th>	
                        <th>Tempo Total Ocioso</th>	
                        <th>% Tempo em Atendimento</th>	
                        <th>% Tempo em Atividade Interna</th>	
                        <th>% Tempo Trabalhado</th>	
                        <th>% Tempo Ocioso</th>	
                        <th>Tempo Médio da Primeira Resposta</th>	
                    </tr>
                </thead>
                <tbody>
        ';

        foreach ($dados_usuario as $conteudo_usuario) {
            
            $dados_tempo_atendimentos = DBRead('otrs',"ticket a","INNER JOIN article b ON a.id = b.ticket_id INNER JOIN time_accounting c ON b.id = c.article_id WHERE b.create_by = '".$conteudo_usuario['id_otrs']."' AND a.queue_id = 2 AND b.create_time >= '$data_de 00:00:00' AND b.create_time <= '$data_ate 23:59:59' $filtro_id_otrs_contrato","SUM(c.time_unit) AS 'tempo'");  
            $tempo_atendimento = $dados_tempo_atendimentos[0]['tempo'] ? intval($dados_tempo_atendimentos[0]['tempo']) : 0;

            $dados_qtd_atendimentos = DBRead('otrs',"ticket a","INNER JOIN article b ON a.id = b.ticket_id INNER JOIN time_accounting c ON b.id = c.article_id WHERE b.create_by = '".$conteudo_usuario['id_otrs']."' AND a.queue_id = 2 AND b.create_time >= '$data_de 00:00:00' AND b.create_time <= '$data_ate 23:59:59' $filtro_id_otrs_contrato GROUP BY a.tn","a.tn, a.create_time"); 
            $qtd_atendimento = $dados_qtd_atendimentos ? sizeof($dados_qtd_atendimentos) : 0;

            $dados_tempo_plantoes = DBRead('otrs',"ticket a","INNER JOIN article b ON a.id = b.ticket_id INNER JOIN time_accounting c ON b.id = c.article_id WHERE b.create_by = '".$conteudo_usuario['id_otrs']."' AND a.queue_id = 10 AND a.create_time >= '$data_de 00:00:00' AND a.create_time <= '$data_ate 23:59:59' $filtro_id_otrs_contrato","SUM(c.time_unit) AS 'tempo'");  
            $tempo_plantao = $dados_tempo_plantoes[0]['tempo'] ? intval($dados_tempo_plantoes[0]['tempo']) : 0;

            $dados_qtd_plantoes = DBRead('otrs',"ticket a","INNER JOIN article b ON a.id = b.ticket_id INNER JOIN time_accounting c ON b.id = c.article_id WHERE b.create_by = '".$conteudo_usuario['id_otrs']."' AND a.queue_id = 10 AND a.create_time >= '$data_de 00:00:00' AND a.create_time <= '$data_ate 23:59:59' $filtro_id_otrs_contrato GROUP BY a.tn","a.tn"); 
            $qtd_plantao = $dados_qtd_plantoes ? sizeof($dados_qtd_plantoes) : 0;

            $dados_tempo_atividades_internas = DBRead('otrs',"ticket a","INNER JOIN article b ON a.id = b.ticket_id INNER JOIN time_accounting c ON b.id = c.article_id WHERE b.create_by = '".$conteudo_usuario['id_otrs']."' AND (a.queue_id = 37 OR (a.queue_id = 2 AND a.customer_id = 'teif')) AND b.create_time >= '$data_de 00:00:00' AND b.create_time <= '$data_ate 23:59:59'","SUM(c.time_unit) AS 'tempo'");  
            $tempo_atividade_interna = $dados_tempo_atividades_internas[0]['tempo'] ? intval($dados_tempo_atividades_internas[0]['tempo']) : 0;

            $dados_qtd_atividades_internas = DBRead('otrs',"ticket a","INNER JOIN article b ON a.id = b.ticket_id INNER JOIN time_accounting c ON b.id = c.article_id WHERE b.create_by = '".$conteudo_usuario['id_otrs']."' AND (a.queue_id = 37 OR (a.queue_id = 2 AND a.customer_id = 'teif')) AND b.create_time >= '$data_de 00:00:00' AND b.create_time <= '$data_ate 23:59:59' GROUP BY a.tn","a.tn"); 
            $qtd_atividade_interna = $dados_qtd_atividades_internas ? sizeof($dados_qtd_atividades_internas) : 0;

            $data_string_ponto = '
				{
					"report": {	
						"group_by": "",
						"start_date": "'.$data_de.'",
						"end_date": "'.$data_ate.'",
						"columns": "employee_name,total_time,missing_days",
						"employee_id": '.$conteudo_usuario['id_ponto'].',
						"row_filters": "",
						"format": "json"
					}
				}
			';  
			
			$result_ponto = troca_dados_curl('https://api.pontomais.com.br/external_api/v1/reports/period_summaries', $data_string_ponto, array('Content-Type: application/json','access-token: FcogHwphYbzMk3IF1tXBmh5ypV49O-74CSf7dMxE3cMcabhbaajbefjai'));
			
			$horas_ponto = explode(":", $result_ponto['data'][0][0]['data'][0]['total_time']);
			if($horas_ponto[0] && $horas_ponto[1]) {
				$tempo_ponto = ($horas_ponto[0]*60)+$horas_ponto[1];
			}else{
				$tempo_ponto = 0;
            }

            //TPR
            $qtd_tpr = 0;
            $tpr_total = 0;
            $tpr = 0;

            $contador = 0;

            foreach ($dados_qtd_atendimentos as $conteudo_atendimento) {
                if($conteudo_atendimento['create_time'] >= $data_de." 00:00:00" && $conteudo_atendimento['create_time'] <= $data_ate." 23:59:59"){
                    $tpr = getTempoPrimeiraRespostaOTRS($conteudo_atendimento['tn'], $conteudo_usuario['id_otrs']);
                    if($tpr > 0){
                        $qtd_tpr ++;
                        $tpr_total += $tpr;
                    }
                    $contador++;
                }
            }
            if($contador > 0){
                $descricao_tpr = converteSegundosHoras(($tpr_total/$qtd_tpr)*60);
            }else{
                $descricao_tpr = 'N/D';
            }
                        
            echo '
                <tr>
                    <td style="vertical-align: middle;">'.$conteudo_usuario['nome'].'</td>
                    <td style="vertical-align: middle;">'.converteSegundosHoras($tempo_atendimento*60).'</td>
                    <td style="vertical-align: middle;">'.$qtd_atendimento.'</td>
                    <td style="vertical-align: middle;">'.converteSegundosHoras($tempo_atendimento*60/$qtd_atendimento).'</td>
                    <td style="vertical-align: middle;">'.converteSegundosHoras($tempo_plantao*60).'</td>
                    <td style="vertical-align: middle;">'.$qtd_plantao.'</td>
                    <td style="vertical-align: middle;">'.converteSegundosHoras($tempo_plantao*60/$qtd_plantao).'</td>
                    <td style="vertical-align: middle;">'.converteSegundosHoras($tempo_atividade_interna*60).'</td>
                    <td style="vertical-align: middle;">'.$qtd_atividade_interna.'</td>
                    <td style="vertical-align: middle;">'.converteSegundosHoras($tempo_atividade_interna*60/$qtd_atividade_interna).'</td>
                    <td style="vertical-align: middle;">'.converteSegundosHoras($tempo_ponto*60).'</td>
                    <td style="vertical-align: middle;">'.converteSegundosHoras(($tempo_atendimento+$tempo_atividade_interna)*60).'</td>
                    <td style="vertical-align: middle;">'.converteSegundosHoras(($tempo_ponto-$tempo_atendimento-$tempo_atividade_interna)*60).'</td>
                    <td style="vertical-align: middle;">'.sprintf("%01.2f", round($tempo_atendimento*100/($tempo_ponto == 0 ? 1 : $tempo_ponto), 2)).'%</td>
                    <td style="vertical-align: middle;">'.sprintf("%01.2f", round($tempo_atividade_interna*100/($tempo_ponto == 0 ? 1 : $tempo_ponto), 2)).'%</td>
                    <td style="vertical-align: middle;">'.sprintf("%01.2f", round(($tempo_atendimento+$tempo_atividade_interna)*100/($tempo_ponto == 0 ? 1 : $tempo_ponto), 2)).'%</td>
                    <td style="vertical-align: middle;">'.sprintf("%01.2f", round(($tempo_ponto-$tempo_atendimento-$tempo_atividade_interna)*100/($tempo_ponto == 0 ? 1 : $tempo_ponto), 2)).'%</td>
                    <td style="vertical-align: middle;">'.$descricao_tpr.'</td>
                </tr>
            ';

            $total_tempo_atendimento += $tempo_atendimento;
            $total_qtd_atendimento += $qtd_atendimento;
            $total_tempo_plantao += $tempo_plantao;
            $total_qtd_plantao += $qtd_plantao;
            $total_tempo_atividade_interna += $tempo_atividade_interna;
            $total_qtd_atividade_interna += $qtd_atividade_interna;
            $total_tempo_ponto += $tempo_ponto;
            $total_tpr_atendimento += $tpr_total;
            $total_qtd_tpr_atendimento += $qtd_tpr;
        }

        echo "
                </tbody>    
                <tfoot>
                    <tr>
                        <th>Totais</th>                                    
                        <th>".converteSegundosHoras($total_tempo_atendimento*60)."</th>
                        <th>".$total_qtd_atendimento."</th>
                        <th>".converteSegundosHoras($total_tempo_atendimento*60/$total_qtd_atendimento)."</th>
                        <th>".converteSegundosHoras($total_tempo_plantao*60)."</th>
                        <th>".$total_qtd_plantao."</th>
                        <th>".converteSegundosHoras($total_tempo_plantao*60/$total_qtd_plantao)."</th>
                        <th>".converteSegundosHoras($total_tempo_atividade_interna*60)."</th>
                        <th>".$total_qtd_atividade_interna."</th>
                        <th>".converteSegundosHoras($total_tempo_atividade_interna*60/$total_qtd_atividade_interna)."</th>
                        <th>".converteSegundosHoras($total_tempo_ponto*60)."</th>
                        <th>".converteSegundosHoras(($total_tempo_atendimento+$total_tempo_atividade_interna)*60)."</th>
                        <th>".converteSegundosHoras(($total_tempo_ponto-$total_tempo_atendimento-$total_tempo_atividade_interna)*60)."</th>
                        <th>".sprintf("%01.2f", round($total_tempo_atendimento*100/($total_tempo_ponto == 0 ? 1 : $total_tempo_ponto), 2))."%</th>
                        <th>".sprintf("%01.2f", round($total_tempo_atividade_interna*100/($total_tempo_ponto == 0 ? 1 : $total_tempo_ponto), 2))."%</th>
                        <th>".sprintf("%01.2f", round(($total_tempo_atendimento+$total_tempo_atividade_interna)*100/($total_tempo_ponto == 0 ? 1 : $total_tempo_ponto), 2))."%</th>
                        <th>".sprintf("%01.2f", round(($total_tempo_ponto-$total_tempo_atendimento-$total_tempo_atividade_interna)*100/($total_tempo_ponto == 0 ? 1 : $total_tempo_ponto), 2))."%</th>
                        <th>".converteSegundosHoras(($total_tpr_atendimento/$total_qtd_tpr_atendimento)*60)."</th>
                    </tr>
                </tfoot>  
            </table>        
        </div>            
        ";

        echo "
        <script>
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

function relatorio_utilizacao_plano_horas(){
    
    $data_hoje = converteDataHora(getDataHora());

    $data_de = new DateTime(getDataHora('data'));
    $data_de->modify('first day of this month');
    $data_de = $data_de->format('Y-m-d');
    
    $data_ate = new DateTime(getDataHora('data'));
    $data_ate->modify('last day of this month');
    $data_ate = $data_ate->format('Y-m-d');

	$periodo_amostra = "<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> De ".converteData($data_de)." até ".converteData($data_ate)."</span>";
	$gerado = "<span style=\"font-size: 14px;\"><strong>Gerado em: </strong> $data_hoje</span>";

	echo "<div class=\"col-md-12\" style=\"padding: 0\">";
	echo "<legend style=\"text-align:center;\"><strong>Relatório de Utilização de Contrato - Plano Horas</strong><br>$gerado</legend>";
    echo "<legend style=\"text-align:center;\">$periodo_amostra</legend>";
    
    $dados_contratos = DBRead('','tb_contrato_plano_pessoa a',"INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa INNER JOIN tb_plano c ON a.id_plano = c.id_plano INNER JOIN tb_parametro_redes_contrato d ON a.id_contrato_plano_pessoa = d.id_contrato_plano_pessoa INNER JOIN tb_usuario e ON d.id_responsavel = e.id_usuario INNER JOIN tb_pessoa f ON e.id_pessoa = f.id_pessoa WHERE c.cod_servico = 'gestao_redes' AND a.tipo_cobranca = 'horas' AND a.status != 5 AND (a.status = 1 OR a.data_status >= '$data_de') ORDER BY b.nome ASC", "a.*, b.nome AS 'nome_cliente', d.*, f.nome AS 'nome_responsavel'");  

	if($dados_contratos){       

        echo '
        <table class="table table-striped dataTable" style="margin-bottom:0;">
            <thead>
                <tr>
                    <th>Cliente</th>	
                    <th>Tempo Contratado</th>	
                    <th>Tempo em Atendimento</th>		
                    <th>Tempo em Plantão</th>	
                    <th>Tempo Total</th>	
                    <th>% Utilizada</th>		
                </tr>
            </thead>
            <tbody>
        ';

        foreach ($dados_contratos as $conteudo_contrato) {

            $dados_tempo_atendimentos = DBRead('otrs',"ticket a","INNER JOIN article b ON a.id = b.ticket_id INNER JOIN time_accounting c ON b.id = c.article_id WHERE a.customer_id = '".$conteudo_contrato['id_otrs']."' AND a.queue_id = 2 AND b.create_time >= '$data_de 00:00:00' AND b.create_time <= '$data_ate 23:59:59'","SUM(c.time_unit) AS 'tempo'");  
            $tempo_atendimento = $dados_tempo_atendimentos[0]['tempo'] ? intval($dados_tempo_atendimentos[0]['tempo']) : 0;

            $dados_tempo_plantoes = DBRead('otrs',"ticket a","INNER JOIN article b ON a.id = b.ticket_id INNER JOIN time_accounting c ON b.id = c.article_id WHERE a.customer_id = '".$conteudo_contrato['id_otrs']."' AND a.queue_id = 10 AND a.create_time >= '$data_de 00:00:00' AND a.create_time <= '$data_ate 23:59:59'","SUM(c.time_unit) AS 'tempo'");  
            $tempo_plantao = $dados_tempo_plantoes[0]['tempo'] ? intval($dados_tempo_plantoes[0]['tempo']) : 0;

            if(sprintf("%01.2f", round(($tempo_atendimento+$tempo_plantao)*100/($conteudo_contrato['qtd_contratada']*60 == 0 ? 1 : $conteudo_contrato['qtd_contratada']*60), 2)) > 100){
                $class = ' class = "danger"';
            }else if(sprintf("%01.2f", round(($tempo_atendimento+$tempo_plantao)*100/($conteudo_contrato['qtd_contratada']*60 == 0 ? 1 : $conteudo_contrato['qtd_contratada']*60), 2)) >= 75){
                $class = ' class = "warning"';
            }else{
                $class = '';
            }
            
            echo '
                <tr'.$class.'>
                    <td>'.$conteudo_contrato['nome_cliente'].'</td>
                    <td>'.converteSegundosHoras($conteudo_contrato['qtd_contratada']*3600).'</td>
                    <td>'.converteSegundosHoras($tempo_atendimento*60).'</td>
                    <td>'.converteSegundosHoras($tempo_plantao*60).'</td>
                    <td>'.converteSegundosHoras(($tempo_atendimento+$tempo_plantao)*60).'</td>
                    <td>'.sprintf("%01.2f", round(($tempo_atendimento+$tempo_plantao)*100/($conteudo_contrato['qtd_contratada']*60 == 0 ? 1 : $conteudo_contrato['qtd_contratada']*60), 2)).'%</td>
                </tr>
            ';          
        }

        echo "
            </tbody>                
        </table>                    
        ";

        echo "
        <script>
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
?>
