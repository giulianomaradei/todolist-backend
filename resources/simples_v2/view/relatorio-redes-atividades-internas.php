<?php
require_once(__DIR__."/../class/System.php");

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
	$display_row_ticket_state = 'style="display:none;"';
}else if($tipo_relatorio == 2){
	$display_row_usuario = '';
	$display_row_contrato = 'style="display:none;"';
	$display_row_data = '';
	$display_row_ticket_state = '';
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
	                    <h3 class="panel-title text-left pull-left" style="margin-top: 2px;">Relatório - Atividades Internas - Redes:</h3>
	                    <div class="panel-title text-right pull-right"><button data-toggle="collapse" data-target="#accordionRelatorio" class="btn btn-xs btn-info" type="button" title="Visualizar filtros"><i id="i_collapse" class="fa fa-<?=$collapse_icon?>"></i></button></div>
	                </div>
	                <div id="accordionRelatorio" class="panel-collapse collapse <?=$collapse?>">
	                	<div class="panel-body">

	                		<div class="row">
                				<div class="col-md-12">
                					<div class="form-group">
								        <label>*Tipo de Relatório:</label>
								        <select name="tipo_relatorio" id="tipo_relatorio" class="form-control input-sm">
                                            <option value="1" <?php if($tipo_relatorio == '1'){ echo 'selected';}?>>Ativações</option>
                                            <option value="2" <?php if($tipo_relatorio == '2'){ echo 'selected';}?>>Tarefas Internas</option>
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
				relatorio_ativacoes($data_de, $data_ate, $id_contrato_plano_pessoa, $id_usuario);			
			}else if($tipo_relatorio == 2){
				relatorio_tarefas_internas($data_de, $data_ate, $id_usuario, $ticket_state);			
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
            $('#row_ticket_state').hide();
	
		}else if(tipo_relatorio == 2){
			$('#row_usuario').show();
			$('#row_contrato').hide();
			$('#row_data').show();
            $('#row_ticket_state').show();		
		}
	}); 

</script>

<?php 

function relatorio_ativacoes($data_de, $data_ate, $id_contrato_plano_pessoa, $id_usuario){

    $data_hoje = converteDataHora(getDataHora());
    
	if($id_contrato_plano_pessoa){
        $dados_id_contrato_plano_pessoa = DBRead('', 'tb_contrato_plano_pessoa a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa INNER JOIN tb_parametro_redes_contrato c ON a.id_contrato_plano_pessoa = c.id_contrato_plano_pessoa WHERE a.id_contrato_plano_pessoa = '".$id_contrato_plano_pessoa."' ", "b.nome, c.id_otrs");
		$legenda_id_contrato_plano_pessoa = $dados_id_contrato_plano_pessoa[0]['nome'];
        $filtro_id_contrato_plano_pessoa = " AND a.id_contrato_plano_pessoa = '".$id_contrato_plano_pessoa."'";
	}else{
		$legenda_id_contrato_plano_pessoa = "Todos";
	}

	if($id_usuario){
        $dados_id_usuario = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_usuario = '".$id_usuario."' ");
        $legenda_id_usuario = $dados_id_usuario[0]['nome'];        
        $filtro_id_usuario = " AND a.id_responsavel = '".$id_usuario."'";
	}else{
		$legenda_id_usuario = "Todos";
	}

	$periodo_amostra = "<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> Data do Prazo De $data_de até $data_ate</span>";
	$gerado = "<span style=\"font-size: 14px;\"><strong>Gerado em: </strong> $data_hoje</span>";

	echo "<div class=\"col-md-12\" style=\"padding: 0\">";
	echo "<legend style=\"text-align:center;\"><strong>Relatório de Ativações</strong><br>$gerado</legend>";
    echo "<legend style=\"text-align:center;\">$periodo_amostra</legend>";
    echo "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong> Contrato - </strong>".$legenda_id_contrato_plano_pessoa.",<strong> Técnico - </strong>".$legenda_id_usuario."</legend>";

    $dados_ativacoes = DBRead('','tb_redes_ativacao a',"INNER JOIN tb_parametro_redes_contrato b ON a.id_contrato_plano_pessoa = b.id_contrato_plano_pessoa INNER JOIN tb_contrato_plano_pessoa c ON a.id_contrato_plano_pessoa = c.id_contrato_plano_pessoa INNER JOIN tb_pessoa d ON c.id_pessoa = d.id_pessoa INNER JOIN tb_usuario e ON a.id_responsavel = e.id_usuario INNER JOIN tb_pessoa f ON e.id_pessoa = f.id_pessoa WHERE a.data_prazo >= '".converteData($data_de)."' AND a.data_prazo <= '".converteData($data_ate)."' $filtro_id_contrato_plano_pessoa $filtro_id_usuario","a.*, b.id_otrs, d.nome AS 'nome_cliente', f.nome AS 'nome_responsavel'");
    
    if($dados_ativacoes){

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

        echo "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong> Total de Ativações: </strong>".sizeof($dados_ativacoes)."</legend>";

        foreach ($dados_ativacoes as $conteudo_ativacao) {
            echo
            ' <div class="panel panel-primary">
                <div class="panel-heading clearfix">
                    <div class="row">
                        <h3 class="panel-title text-left col-md-6"><strong>Cliente: </strong>'.$conteudo_ativacao['nome_cliente'].'</h3>
                        <h3 class="panel-title text-right col-md-6"><strong>Responsável: </strong>'.$conteudo_ativacao['nome_responsavel'].'</h3>
                    </div>
                </div>
                <div class="panel-body painel-body">
            ';

            echo ' 
                    <div class="panel panel-default">
                        <div class="panel-heading clearfix">
                            <h3 class="panel-title text-left">Atividades:</h3>
                        </div>
                        <div class="panel-body painel-body">
            ';

            $dados_atividades = DBRead('otrs',"ticket a","INNER JOIN article b ON a.id = b.ticket_id INNER JOIN time_accounting c ON b.id = c.article_id WHERE a.queue_id = 37 AND a.customer_id = '".$conteudo_ativacao['id_otrs']."' GROUP BY a.tn, b.create_by","a.tn, a.title, a.customer_id, SUM(c.time_unit) AS 'time_unit', b.create_by");
            if($dados_atividades){
                echo '
                    <table class="table table-striped dataTable" style="margin-bottom:0;">
                        <thead>
                            <tr>
                                <th>ContatoID#</th>
                                <th>Assunto</th>
                                <th>Técnico</th>		
                                <th>Tempo</th>		
                            </tr>
                        </thead>
                        <tbody>
                ';

                $tempo_total = 0;
		
                foreach($dados_atividades as $conteudo_atividade){
                    $tempo = intval($conteudo_atividade['time_unit']);
                    echo '
                        <tr>
                            <td>'.$conteudo_atividade['tn'].'</td>                    
                            <td>'.$conteudo_atividade['title'].'</td>
                            <td>'.($nomes_usuarios[$conteudo_atividade['create_by']] ? $nomes_usuarios[$conteudo_atividade['create_by']] : $conteudo_atividade['create_by']).'</td>
                            <td>'.converteSegundosHoras($tempo*60).'</td>
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
                                <th>".converteSegundosHoras($tempo_total*60)."</th>		
                            </tr>
                            <tr>
                                <th>Médias</th>
                                <th></th>
                                <th></th>
                                <th>".converteSegundosHoras($tempo_total/sizeof($dados_atividades)*60)."</th>		
                            </tr>
                        </tfoot>  
                </table>
                ";

            }else{
                echo '<div class="text-center">Nenhuma atividade.</div>';
            }
            
            echo "                       
                        </div>                    
                    </div>
            ";
            echo "<hr>";

            echo ' 
                    <div class="panel panel-default">
                        <div class="panel-heading clearfix">
                            <h3 class="panel-title text-left">Comentários:</h3>
                        </div>
                        <div class="panel-body painel-body">
            ';

            $dados_comentarios = DBRead('','tb_redes_ativacao_comentario a', "INNER JOIN tb_usuario b ON a.id_usuario = b.id_usuario INNER JOIN tb_pessoa c ON b.id_pessoa = c.id_pessoa WHERE a.id_redes_ativacao = '".$conteudo_ativacao['id_redes_ativacao']."'", "a.*, c.nome");
            if($dados_comentarios){
                echo '
                <div class="row">
                    <div class="col-md-12">
                        <ul class="list-group" style="margin-top: 10px;">
                ';
                foreach ($dados_comentarios as $conteudo_comentario) {
                    echo "<li class='list-group-item clearfix'>";
                        echo "<div class='row'>";
                            echo "<div class='col-md-6'>";
                                echo "<p><strong>".$conteudo_comentario['nome']."</strong></p>";
                            echo "</div>";
                            echo "<div class='col-md-6'>";
                                echo " <p class='text-right'><strong>".converteDataHora($conteudo_comentario['data'])."</strong>";                                
                            echo "</div>";
                        echo "</div>";
                        echo "<hr style='margin-top: 0'>";
                        echo "<div class='row'>";
                            echo "<div class='col-md-12'>";
                                echo "<div>";
                                    echo nl2br($conteudo_comentario['comentario']);
                                echo "</div>";
                            echo "</div>";
                        echo "</div>";
                    echo "</li>";
                }
                echo '
                        </ul>
                    </div>
                </div>
                ';
            }else{
                echo '<div class="text-center">Nenhum comentário.</div>';
            }
            
            echo "                       
                        </div>                    
                    </div>
            ";
                        
            echo "
                </div>
                <div class='panel-footer'>
                    <div class='row'>
                        <h3 class='panel-title text-left col-md-4'><strong>Data de início: </strong>".converteData($conteudo_ativacao['data_inicio'])."</h3>
                        <h3 class='panel-title text-center col-md-4'><strong>Data de prazo: </strong>".converteData($conteudo_ativacao['data_prazo'])."</h3>
                        <h3 class='panel-title text-right col-md-4'><strong>Data de conclusão: </strong>".($conteudo_ativacao['data_conclusao'] ? ($conteudo_ativacao['data_conclusao'] > $conteudo_ativacao['data_prazo'] ? converteData($conteudo_ativacao['data_conclusao']).', fora do prazo.' : converteData($conteudo_ativacao['data_conclusao']).', dentro do prazo.'): 'Não concluída')."</h3>
                    </div>
                </div>
            </div>
            ";
            echo "<hr>";            
        }
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

function relatorio_tarefas_internas($data_de, $data_ate, $id_usuario, $ticket_state){

    $data_hoje = converteDataHora(getDataHora());
    
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
	echo "<legend style=\"text-align:center;\"><strong>Relatório de Tarefas Internas</strong><br>$gerado</legend>";
    echo "<legend style=\"text-align:center;\">$periodo_amostra</legend>";
    echo "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong>Técnico - </strong>".$legenda_id_usuario.", <strong>Status - </strong>".$legenda_ticket_state."</legend>";

    $dados_atendimentos = DBRead('otrs',"ticket a","INNER JOIN article b ON a.id = b.ticket_id INNER JOIN time_accounting c ON b.id = c.article_id INNER JOIN ticket_state d ON a.ticket_state_id = d.id WHERE a.queue_id = 2 AND a.customer_id = 'teif' AND b.create_time >= '".converteData($data_de)." 00:00:00' AND b.create_time <= '".converteData($data_ate)." 23:59:59' $filtro_id_usuario $filtro_ticket_state GROUP BY a.tn, b.create_by","a.tn, a.title, a.customer_id, SUM(c.time_unit) AS 'time_unit', b.create_by, d.name, b.create_time");  
    
    if($dados_atendimentos){
        $dados_usuarios = DBRead('',"tb_usuario a", "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE id_otrs");
        $nomes_usuarios = array();
        if($dados_usuarios){
            foreach ($dados_usuarios as $conteudo_usuario) {
                $nomes_usuarios[$conteudo_usuario['id_otrs']] = $conteudo_usuario['nome'];
            }
        }
        
		echo "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong> Total de Tarefas: </strong>".sizeof($dados_atendimentos)."</legend>";

		echo '
            <table class="table table-striped dataTable" style="margin-bottom:0;">
                <thead>
                    <tr>
                        <th>ContatoID#</th>
                        <th>Assunto</th>
                        <th>Técnico</th>		
                        <th>Status</th>			
                        <th>Tempo</th>		
                    </tr>
                </thead>
                <tbody>
        ';

        $tempo_total = 0;
		
        foreach($dados_atendimentos as $conteudo_atendimento){
            $tempo = intval($conteudo_atendimento['time_unit']);
			echo '
                <tr>
                    <td>'.$conteudo_atendimento['tn'].'</td>                    
                    <td>'.$conteudo_atendimento['title'].'</td>
                    <td>'.($nomes_usuarios[$conteudo_atendimento['create_by']] ? $nomes_usuarios[$conteudo_atendimento['create_by']] : $conteudo_atendimento['create_by']).'</td>                
                    <td>'.ucfirst($conteudo_atendimento['name']).'</td>
                    <td>'.converteSegundosHoras($tempo*60).'</td>
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
                        <th>".converteSegundosHoras($tempo_total*60)."</th>		
                    </tr>
                    <tr>
                        <th>Médias</th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th>".converteSegundosHoras($tempo_total/sizeof($dados_atendimentos)*60)."</th>		
                    </tr>
                </tfoot>  
           </table>
        ";
        
        echo "<hr>";

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
