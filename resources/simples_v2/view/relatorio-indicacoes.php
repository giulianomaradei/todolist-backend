<?php
	require_once(__DIR__."/../class/System.php");

	$data_hoje = getDataHora();
	$data_hoje = explode(" ", $data_hoje);
	$data_hoje = $data_hoje[0];
	$primeiro_dia = "01/".$data_hoje[5].$data_hoje[6]."/".$data_hoje[0].$data_hoje[1].$data_hoje[2].$data_hoje[3];

	$id_pessoa_indicacao = (!empty($_POST['id_pessoa_indicacao'])) ? $_POST['id_pessoa_indicacao'] : '';
	$data_de = (!empty($_POST['data_de'])) ? $_POST['data_de'] : '';
	$data_ate = (!empty($_POST['data_ate'])) ? $_POST['data_ate'] : '';

	$tipo_relatorio = (!empty($_POST['tipo_relatorio'])) ? $_POST['tipo_relatorio'] : '1';

	$gerar = (!empty($_POST['gerar'])) ? 1 : 0;
	
	if($id_pessoa_indicacao){
		$dados_pessoa_indicacao = DBRead('', 'tb_pessoa', "WHERE id_pessoa = '".$id_pessoa_indicacao."' ");
		$nome_pessoa_indicacao = $dados_pessoa_indicacao[0]['nome'];
	}else{
		$nome_pessoa_indicacao = '';
	}

	if($gerar){
		$collapse = '';
		$collapse_icon = 'plus';
		
	}else{
		$collapse = 'in';
		$collapse_icon = 'minus';
	}
?>

<style>
	.conteudo-editor img{
        max-width: 100% !important;
        max-height: 100% !important;
        height: 100% !important;
    }
	
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
	<form method="post" id="relatorio_erro_form">
		<div class="row">
	        <div class="col-md-4 col-md-offset-4">

	        	<div class="panel panel-default noprint">
	                <div class="panel-heading clearfix">
	                    <h3 class="panel-title text-left pull-left" style="margin-top: 2px;">Relatório de indicações:</h3>
	                    <div class="panel-title text-right pull-right"><button data-toggle="collapse" data-target="#accordionRelatorio" class="btn btn-xs btn-info" type="button" title="Visualizar filtros"><i id="i_collapse" class="fa fa-<?=$collapse_icon?>"></i></button></div>
	                </div>
	                <div id="accordionRelatorio" class="panel-collapse collapse <?=$collapse?>">
	                	<div class="panel-body">
	                		<div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>*Tipo de Relatório:</label> <select
                                            name="tipo_relatorio" id="tipo_relatorio" class="form-control input-sm">
                                            <option value="1" <?php if($tipo_relatorio == '1'){echo 'selected';}?>>Indicações</option>
                                        </select>
                                    </div>
                                </div>
                            </div>	

	                		<div class="row">
	                			<div class="col-md-6">
	                				<div class="form-group">
										<label>Data Inicial:</label>
										<input type="text" class="form-control date calendar input-sm" name="data_de" id="de" autocomplete="off" value="<?=$data_de?>">
									</div>
	                			</div>
	                			<div class="col-md-6">
	                				<div class="form-group">
										<label>Data Final:</label>
										<input type="text" class="form-control date calendar input-sm" name="data_ate" id="ate" autocomplete="off" value="<?=$data_ate?>">
									</div>
	                			</div>
	                		</div>
  
	                		<div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Empresa/Pessoa:</label>
                                        <div class="input-group">
											<input class="form-control input-sm ui-autocomplete-input" id="busca_pessoa" type="text" name="busca_pessoa" value="<?=$nome_pessoa_indicacao ?>" placeholder="Informe o nome ou CPF/CNPJ..." autocomplete="off" readonly>
											<div class="input-group-btn">
												<button class="btn btn-info btn-sm" id="habilita_busca_pessoa" name="habilita_busca_pessoa" type="button" title="Clique para selecionar a pessoa" style="height: 30px;"><i class="fa fa-search"></i></button>
											</div>
										</div>
										<input type="hidden" name="id_pessoa_indicacao" id="id_pessoa_indicacao" value="<?= $id_pessoa_indicacao ?>">
                                    </div>
                                </div>
                            </div>
		                </div>
	                </div>
	                <div class="panel-footer">
	                    <div class="row">
	                        <div id="panel_buttons" class="col-md-12" style="text-align: center">
	                            <button class="btn btn-primary" name="gerar" id="gerar" value="1" type="submit"><i class="fa fa-refresh"></i> Gerar</button>
	                            <button class="btn btn-warning" name="imprimir" type="button" onclick="window.print();"><i class="fa fa-print"></i> Imprimir</button>
	                        </div>
	                    </div>
	                </div>
	            </div>
	        </div>
	    </div>
	</form>
	<div class="row">
		<?php
			if($gerar){
	
				if($tipo_relatorio == 1){
					relatorio_indicacoes($data_de, $data_ate, $id_pessoa_indicacao);
                }
			}
			?>
	</div>
</div>

<script>

    // Atribui evento e função para limpeza dos campos
    $('#busca_pessoa').on('input', limpaCamposPessoa);
    // Dispara o Autocomplete da pessoa a partir do segundo caracter
    $("#busca_pessoa").autocomplete({
            minLength: 2,
            source: function (request, response) {
                $.ajax({
                    url: "/api/ajax?class=PessoaAutocomplete.php",
                    dataType: "json",
                    data: {
                        acao: 'autocomplete',
                        parametros: { 
                            'nome' : $('#busca_pessoa').val(),
                            'atributo' : '',
                        },
						token: '<?= $request->token ?>'
                    },
                    success: function (data) {
                        response(data);
                    }
                });
            },
            focus: function (event, ui) {
                $("#busca_pessoa").val(ui.item.nome);
                carregarDadosPessoa(ui.item.id_pessoa);
                return false;
            },
            select: function (event, ui) {
                $("#busca_pessoa").val(ui.item.nome);
                $('#busca_pessoa').attr("readonly", true);
                return false;
            }
        })
        .autocomplete("instance")._renderItem = function (ul, item) {
            if(!item.razao_social){
                item.razao_social = '';
            }
            if(!item.cpf_cnpj){
                item.cpf_cnpj = '';
            }
        return $("<li>").append("<a><strong>"+item.id_pessoa+" - "+ item.nome + " </strong><br>" +item.razao_social+ "<br>" +item.cpf_cnpj+ "</a><hr style='margin-bottom: 0px;'>").appendTo(ul);
    };
    // Função para carregar os dados da consulta nos respectivos campos
    function carregarDadosPessoa(id) {
        var busca = $('#busca_pessoa').val();

        if (busca != "" && busca.length >= 2) {
            $.ajax({
                url: "/api/ajax?class=PessoaAutocomplete.php",
                dataType: "json",
                data: {
                    acao: 'consulta',
                    parametros: { 
                        'id' : id,                            
                    },
					token: '<?= $request->token ?>'
                },
                success: function (data) {
                    $('#id_pessoa_indicacao').val(data[0].id_pessoa);
                }
            });
        }
    }
    // Função para limpar os campos caso a busca esteja vazia
    function limpaCamposPessoa() {
        var busca = $('#busca_pessoa').val();

        if (busca == "") {
            $('#id_pessoa_indicacao').val('');
        }
    }
	$(document).on('click', '#habilita_busca_pessoa', function () {
        $('#id_pessoa_indicacao').val('');
        $('#busca_pessoa').val('');
        $('#busca_pessoa').attr("readonly", false);
        $('#busca_pessoa').focus();
    });

    $('#accordionRelatorio').on('shown.bs.collapse', function(){
       $("#i_collapse").removeClass("fa fa-plus").addClass("fa fa-minus");
    });

    $('#accordionRelatorio').on('hidden.bs.collapse', function () {
       $("#i_collapse").removeClass("fa fa-minus").addClass("fa fa-plus");
    });	

</script>

<?php 
function relatorio_indicacoes($data_de, $data_ate, $id_pessoa_indicacao){

	$data_hora = converteDataHora(getDataHora());

	if ($data_de && $data_ate) {
		$periodo_amostra ="<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> De $data_de até $data_ate</span>";
		
	} else if ($data_de) {
		$periodo_amostra ="<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> A partir de $data_de</span>";
		
	} else if ($data_ate) {
		$periodo_amostra ="<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> Até $data_ate</span>";
		
	} else {
	    $periodo_amostra = "<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> Qualquer</span>";
	}

	if ($id_pessoa_indicacao) {
		$filtro_pessoa = " AND a.id_pessoa = $id_pessoa_indicacao";
		$nome = DBRead('', 'tb_pessoa', "WHERE id_pessoa = $id_pessoa_indicacao", 'nome');
		$empresa_legenda = $nome[0]['nome'];

	} else {
		$empresa_legenda = 'Todas';
	}
	
	echo "<div class=\"col-md-12\" style=\"padding: 0\">";
	echo "<legend style=\"text-align:center;\"><strong>Relatório de indicações</strong><br><span style=\"font-size: 14px;\"><strong>Gerado em:</strong> $data_hora</span></legend>";	
	echo "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\">".$periodo_amostra."</legend>";
	echo "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong> Empresa/Pessoa - </strong>".$empresa_legenda."</legend>";
	
	$filtro = '';
    if ($data_de) {
		$filtro .= " AND a.data_pessoa_indicacao >= '".converteData($data_de)."'";
	}
	if ($data_ate) {
		$filtro .= " AND a.data_pessoa_indicacao <= '".converteData($data_ate)."'";
	}

	$dados = DBRead('', 'tb_pessoa a', "WHERE a.status = 1 $filtro_pessoa ORDER BY a.nome ASC", 'a.id_pessoa, a.nome');

	if ($dados) {

        $array_empresa = array();
		$array_indicacoes_total = array();
		$qtd_total = 0;
        foreach($dados as $conteudo) {
            $indicacoes = DBRead('', 'tb_pessoa_prospeccao a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_pessoa_indicacao = '".$conteudo['id_pessoa']."' $filtro", 'a.id_pessoa, b.nome, a.data_pessoa_indicacao');

            if ($indicacoes) {
				foreach ($indicacoes as $conteudo_indicacoes) {
					$array_indicacoes_total[$conteudo['nome']]['qtd_indicacoes'] += 1; 
					$array_empresa[$conteudo['nome']][$conteudo_indicacoes['nome']] = $conteudo_indicacoes['data_pessoa_indicacao'];
				}
            }
        }

		if (sizeof($array_indicacoes_total) > 0) {
			echo "<div class='row'>";
        	echo "<div class='col-xs-12'>";
            echo "<table class='table table-hover dataTable' style='font-size='14px'>";
			echo "<thead>";
				echo "<tr>";
					echo "<th class='col-md-8'>Empresa/Pessoa</th>";
					echo "<th class='col-md-4'>Quantidade de indicações</th>";

				echo "</tr>";
			echo "</thead>";
			echo "<tbody>";

			$qtd_total = 0;
			foreach($array_indicacoes_total as $key => $dado){

				$qtd_total += $dado['qtd_indicacoes'];

				echo "<tr>";
					echo "<td>".$key."</td>";
					echo "<td>".$dado['qtd_indicacoes']."</td>";
				echo "</tr>";
			}
		
			echo "</tbody>";
			echo "<tfoot>";
			echo "<tr>";
				echo "<th>Total: $qtd_total</th>";
				echo "<th></th>";
			echo "</tr>";
			echo "</tfoot>";
			echo "</table>";
			echo "</div>";
			echo "</div><hr>";
		
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

			foreach($array_empresa as $key => $value) {
				echo "<div class='row'>";
					echo "<div class='col-xs-12'>";
					echo "<table class='table table-hover dataTable' style='font-size='14px'>";
					echo "<thead>";
						echo "<tr>";
							echo "<th class='col-md-8'>$key</th>";
							echo "<th class='col-md-4'>Data</th>";
						echo "</tr>";
					echo "</thead>";
					echo "<tbody>";
						foreach($array_empresa[$key] as $dado1 => $dado2) {
						echo "<tr>";
							echo "<td>".$dado1."</td>";
							echo "<td>".converteData($dado2)."</td>";
						echo "</tr>";
						}
					echo "</tbody>";
			echo "</table><hr>";
			}

		} else {
			echo "<table class='table table-bordered'>";
			echo "<tbody>";
				echo "<tr>";
					echo "<td class='text-center'> <h4>Não foram encontrados resultados!</h4></td>";
				echo "</tr>";
			echo "</tbody>";
			echo "</table>";
		}

	} else {
		echo "<table class='table table-bordered'>";
			echo "<tbody>";
				echo "<tr>";
					echo "<td class='text-center'> <h4>Não foram encontrados resultados!</h4></td>";
				echo "</tr>";
			echo "</tbody>";
		echo "</table>";
	}
}


?>