<?php
require_once(__DIR__."/../class/System.php");
$liderados = busca_liderados($_SESSION['id_usuario']);

$tipo_relatorio = (!empty($_POST['tipo_relatorio'])) ? $_POST['tipo_relatorio'] : 1;
$data_de = (!empty($_POST['data_de'])) ? $_POST['data_de'] : '01'.substr(converteData(getDataHora('data')), 2);
$data_ate = (!empty($_POST['data_ate'])) ? $_POST['data_ate'] : converteData(getDataHora('data'));
$id_usuario_ocorrencia = (!empty($_POST['id_usuario_ocorrencia'])) ? $_POST['id_usuario_ocorrencia'] : '';
$id_ocorrencia_tipo = (!empty($_POST['id_ocorrencia_tipo'])) ? $_POST['id_ocorrencia_tipo'] : '';
$classificacao = (!empty($_POST['classificacao'])) ? $_POST['classificacao'] : '';

$gerar = (!empty($_POST['gerar'])) ? 1 : 0;
$id_usuario = $_SESSION['id_usuario'];
$dados = DBRead('', 'tb_usuario', "WHERE id_usuario = '$id_usuario'");
$perfil_sistema = $dados[0]['id_perfil_sistema'];

if($gerar){
	$collapse = '';
	$collapse_icon = 'plus';
}else{
	$collapse = 'in';
	$collapse_icon = 'minus';
}
if($tipo_relatorio == 1){
	$display_row_periodo = '';
	$display_row_usuario = '';
	$display_row_tipo_ocorrencia = '';
	$display_row_classificacao = '';
}else if($tipo_relatorio == 2){
    $display_row_periodo = '';
	$display_row_usuario = '';
	$display_row_tipo_ocorrencia = 'style="display:none;"';
	$display_row_classificacao = 'style="display:none;"';
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
	.tooltip-inner {
		max-width: 100% !important;
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
	                    <h3 class="panel-title text-left pull-left" style="margin-top: 2px;">Relatório - Ocorrências:</h3>
	                    <div class="panel-title text-right pull-right"><button data-toggle="collapse" data-target="#accordionRelatorio" class="btn btn-xs btn-info" type="button" title="Visualizar filtros"><i id="i_collapse" class="fa fa-<?=$collapse_icon?>"></i></button></div>
	                </div>
	                <div id="accordionRelatorio" class="panel-collapse collapse <?=$collapse?>">
	                	<div class="panel-body">
                            <div class="row">
                				<div class="col-md-12">
                					<div class="form-group">
								        <label for="">Tipo de Relatório:</label>
								        <select name="tipo_relatorio" id="tipo_relatorio" class="form-control input-sm">
								        	<option value="1" <?php if($tipo_relatorio == '1'){ echo 'selected';}?>>Analitico</option>
                                            <option value="2" <?php if($tipo_relatorio == '2'){ echo 'selected';}?>>Sintético</option>
								        </select>
								    </div>
                				</div>
                			</div>
                            <div class="row" id="row_periodo" <?=$display_row_periodo?>>
								<div class="col-md-6">
									<div class="form-group" >
								        <label>*Data Inicial:</label>
								        <input type="text" class="form-control date calendar input-sm" name="data_de" value="<?=$data_de?>" required>
								    </div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
								        <label>*Data Final:</label>
								        <input type="text" class="form-control date calendar input-sm" name="data_ate" value="<?=$data_ate?>" required>
								    </div>
								</div>
							</div> 
							<div class="row" id="row_usuario" <?=$display_row_usuario?>>
								<div class="col-md-12">
									<div class="form-group">
                                        <label for="id_usuario_ocorrencia">Funcionário:</label>
                                        <select name="id_usuario_ocorrencia" class="form-control input-sm" id='id_usuario_ocorrencia'>
                                            <option value="">Todos</option>
                                            <?php
                                                if($perfil_usuario != '20' && $perfil_usuario != '12'){
                                                    $filtro_permissao = " AND a.id_usuario IN ('".join("','", $liderados)."')";
                                                }else{
                                                    $filtro_permissao = '';
                                                }
                                                $dados_usuarios = DBRead('','tb_usuario a',"INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE (a.status = 1 OR a.status = 0) $filtro_permissao ORDER BY b.nome ASC","a.id_usuario, b.nome");
                                                if($dados_usuarios){
                                                    foreach ($dados_usuarios as $conteudo_usuarios) {
                                                        $selected = $id_usuario_ocorrencia == $conteudo_usuarios['id_usuario'] ? "selected" : "";
                                                        echo "<option value='".$conteudo_usuarios['id_usuario']."' ".$selected.">".$conteudo_usuarios['nome']."</option>";
                                                    }
                                                }
                                            ?>
                                        </select>
								    </div>
								</div>
							</div>
                            <div class="row" id="row_tipo_ocorrencia" <?=$display_row_tipo_ocorrencia?>>
								<div class="col-md-12">
									<div class="form-group">
                                        <label for="id_ocorrencia_tipo">Tipo de ocorrência:</label>
                                        <select name="id_ocorrencia_tipo" class="form-control input-sm" id='id_ocorrencia_tipo'>
                                            <option value="">Todos</option>
                                            <?php
                                                $dados_ocorrencia_tipo = DBRead('','tb_ocorrencia_tipo',"WHERE status = 1 ORDER BY descricao ASC");
                                                if($dados_ocorrencia_tipo){
                                                    foreach ($dados_ocorrencia_tipo as $conteudo_ocorrencia_tipo) {
                                                        $selected = $id_ocorrencia_tipo == $conteudo_ocorrencia_tipo['id_ocorrencia_tipo'] ? "selected" : "";
                                                        echo "<option value='".$conteudo_ocorrencia_tipo['id_ocorrencia_tipo']."' ".$selected.">".$conteudo_ocorrencia_tipo['descricao']."</option>";
                                                    }
                                                }
                                            ?>
                                        </select>
								    </div>
								</div>
							</div>		    
                            <div class="row" id="row_classificacao" <?=$display_row_classificacao?>>
								<div class="col-md-12">
									<div class="form-group">
                                        <label for="classificacao">Classificação:</label>
                                        <select name="classificacao" class="form-control input-sm" id="classificacao">
                                            <option value="">Todas</option>
                                            <option value="1" <?php if($classificacao == '1'){ echo 'selected';}?>>Positivo</option>
                                            <option value="2" <?php if($classificacao == '2'){ echo 'selected';}?>>Neutro</option>                                            
                                            <option value="3" <?php if($classificacao == '3'){ echo 'selected';}?>>Negativo</option>
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
            if($tipo_relatorio == 1 && (!$id_usuario_ocorrencia || in_array($id_usuario_ocorrencia,$liderados) || $perfil_usuario == '20' || $perfil_usuario == '12')){
                relatorio_analitico($data_de, $data_ate, $id_usuario_ocorrencia, $id_ocorrencia_tipo, $classificacao);
            }elseif($tipo_relatorio == 2 && (!$id_usuario_ocorrencia || in_array($id_usuario_ocorrencia,$liderados) || $perfil_usuario == '20' || $perfil_usuario == '12')){
                relatorio_sintetico($data_de, $data_ate, $id_usuario_ocorrencia);
			}else{
				echo '<div class="alert alert-danger text-center">Erro ao exibir relatório!</div>';
			}
		}
		?>
	</div>
</div>
<script>
    $('#tipo_relatorio').on('change',function(){
		tipo_relatorio = $(this).val();
		if(tipo_relatorio == 1){
			$('#row_periodo').show();
			$('#row_usuario').show(); 
			$('#row_tipo_ocorrencia').show(); 
			$('#row_classificacao').show(); 
		}else if(tipo_relatorio == 2){
            $('#row_periodo').show();
			$('#row_usuario').show();  
			$('#row_tipo_ocorrencia').hide(); 
			$('#row_classificacao').hide();  
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
    $(function(){
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>

<?php 
function relatorio_analitico($data_de, $data_ate, $id_usuario_ocorrencia, $id_ocorrencia_tipo, $classificacao){
    $data_hora = converteDataHora(getDataHora());
    $liderados = busca_liderados($_SESSION['id_usuario']);
    $dados_usuario = DBRead('', 'tb_usuario', "WHERE id_usuario = '".$_SESSION['id_usuario']."'");
    $perfil_usuario = $dados_usuario[0]['id_perfil_sistema'];
    $filtro_ocorrencias = '';
	
	if($data_de && $data_ate){
	    $periodo_amostra ="<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> De $data_de até $data_ate</span>";
	}elseif($data_de){
	    $periodo_amostra ="<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> A partir de $data_de</span>";
	}elseif($data_ate){
	    $periodo_amostra ="<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> Até $data_ate</span>";
	}else{
	    $periodo_amostra = "";
	}
	echo "<div class=\"col-md-6 col-md-offset-3\" style=\"padding: 0\">";
	echo "<legend style=\"text-align:center;\"><strong>Relatório de Ocorrências - Analítico</strong><br><span style=\"font-size: 14px;\"><strong>Gerado em:</strong> $data_hora</span></legend>";
    echo "<legend style=\"text-align:center;\">$periodo_amostra</legend>";

    if ($id_usuario_ocorrencia) {
        $filtro_ocorrencias .= " AND a.id_usuario_ocorrencia = '$id_usuario_ocorrencia'";

    } else if ($perfil_usuario != '20' && $perfil_usuario != '12' && $perfil_usuario != '18') {
        $filtro_ocorrencias .= " AND a.id_usuario_ocorrencia IN ('".join("','", $liderados)."')";

    } else {
        $filtro_ocorrencias = '';
    }

    if ($data_de) {
		$filtro_ocorrencias .= " AND a.data >= '".converteData($data_de)."'";
	}
	if ($data_ate) {
		$filtro_ocorrencias .= " AND a.data <= '".converteData($data_ate)."'";
    }
    
    if ($id_ocorrencia_tipo) {
		$filtro_ocorrencias .= " AND a.id_ocorrencia_tipo = '$id_ocorrencia_tipo'";
	}

    if ($classificacao) {
		$filtro_ocorrencias .= " AND a.classificacao = '$classificacao'";
	}
    
    $dados_ocorrencias = DBRead('','tb_ocorrencia a',"INNER JOIN tb_ocorrencia_tipo b ON a.id_ocorrencia_tipo = b.id_ocorrencia_tipo INNER JOIN tb_usuario c ON a.id_usuario_ocorrencia = c.id_usuario INNER JOIN tb_pessoa d ON c.id_pessoa = d.id_pessoa INNER JOIN tb_usuario e ON a.id_usuario_registro = e.id_usuario INNER JOIN tb_pessoa f ON e.id_pessoa = f.id_pessoa WHERE a.status = 1 $filtro_ocorrencias ORDER BY a.data ASC","a.*, b.descricao, d.nome AS 'nome_usuario_ocorrencia', f.nome AS 'nome_usuario_registro'");

    if($dados_ocorrencias){
        echo "<legend class=\"noprint\" style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong>Total de ocorrências:</strong> ".sizeof($dados_ocorrencias)."</span></legend>";
        foreach ($dados_ocorrencias as $conteudo) {
            if($conteudo['classificacao'] == '1'){
                $classificacao = 'Positivo';
            }elseif($conteudo['classificacao'] == '2'){
                $classificacao = 'Neutro';
            }else{
                $classificacao = 'Negativo';
            }
            echo "<div class='panel panel-default'>";
                echo "<div class='panel-heading'>";
                    echo "<div class='row'>";
                        echo "<div class='col-md-4'><strong>Funcionário: </strong>".$conteudo['nome_usuario_ocorrencia']."</div>";
                        echo "<div class='col-md-4'></div>";
                        echo "<div class='col-md-4'><strong>Data: </strong>".converteData($conteudo['data'])."</div>";
                    echo "</div>";
                echo "</div>";
                echo "<div class='panel-body'>";
                    echo nl2br($conteudo['comentario']);
                echo "</div>";
                echo "<div class='panel-footer'>";
                echo "<div class='row'>";
                    echo "<div class='col-md-4'>";
                        echo "<strong>Tipo: </strong>".$conteudo['descricao'];                    
                    echo "</div>";
                    echo "<div class='col-md-4'>";
                        echo "<strong>Classificação: </strong>".$classificacao;                    
                    echo "</div>";                   
                    echo "<div class='col-md-4'>";
                        echo "<strong>Registrado por: </strong>".$conteudo['nome_usuario_registro'];                    
                    echo "</div>";
                echo "</div>";
            echo "</div>";               
            echo "</div>";

        }
    }else{
        echo "<table class='table table-bordered'>";
            echo "<tbody>";
                echo "<tr>";
                    echo "<td class='text-center'><h4>Não foram encontrados resultados!</h4></td>";
                echo "</tr>";
            echo "</tbody>";
        echo "</table>";
    }
    echo "</div>";
}

function relatorio_sintetico($data_de, $data_ate, $id_usuario_ocorrencia){
    $data_hora = converteDataHora(getDataHora());
    $liderados = busca_liderados($_SESSION['id_usuario']);
    $dados_usuario = DBRead('', 'tb_usuario', "WHERE id_usuario = '".$_SESSION['id_usuario']."'");
    $perfil_usuario = $dados_usuario[0]['id_perfil_sistema'];
    $filtro_ocorrencias = '';
    $tipos_ocorrencias = array();

	if($data_de && $data_ate){
	    $periodo_amostra ="<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> De $data_de até $data_ate</span>";
	}elseif($data_de){
	    $periodo_amostra ="<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> A partir de $data_de</span>";
	}elseif($data_ate){
	    $periodo_amostra ="<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> Até $data_ate</span>";
	}else{
	    $periodo_amostra = "";
	}
	echo "<div class=\"col-md-8 col-md-offset-2\" style=\"padding: 0\">";
	echo "<legend style=\"text-align:center;\"><strong>Relatório de Ocorrências - Sintético</strong><br><span style=\"font-size: 14px;\"><strong>Gerado em:</strong> $data_hora</span></legend>";
    echo "<legend style=\"text-align:center;\">$periodo_amostra</legend>";


    if($data_de){
		$filtro_ocorrencias .= " AND data >= '".converteData($data_de)."'";
	}
	if($data_ate){
		$filtro_ocorrencias .= " AND data <= '".converteData($data_ate)."'";
    }
    $filtro_usuarios = '';
    if($id_usuario_ocorrencia){
        $filtro_usuarios .= " AND id_usuario = '$id_usuario_ocorrencia'";
    }else if($perfil_usuario != '20' && $perfil_usuario != '12'){
        $filtro_usuarios .= " AND id_usuario IN ('".join("','", $liderados)."')";
    }else{
        $filtro_usuarios = '';
    }
    
    $dados_usuarios = DBRead('','tb_usuario a',"INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.status = 1 $filtro_usuarios ORDER BY b.nome ASC","a.id_usuario, b.nome");
    if($dados_usuarios){
        $qtd_total = 0;
        $qtd_positivas = 0;
        $qtd_neutras = 0;
        $qtd_negativas = 0;
        echo "
            <table class=\"table table-hover dataTableClassificacao\"> 
                <thead> 
                    <tr> 
                        <th>Funcionário</th>
                        <th class='text-center'>Total ocorrências</th>
                        <th class='text-center'>Positivas</th>
                        <th class='text-center'>Neutras</th>
                        <th class='text-center'>Negativas</th>
                    </tr>
                </thead> 
                <tbody>"
        ;
        foreach ($dados_usuarios as $conteudo_usuarios) {
            $qtd_total_usuario = 0;
            $qtd_positivas_usuario = 0;
            $qtd_neutras_usuario = 0;
            $qtd_negativas_usuario = 0;
            $dados_ocorrencias = DBRead('','tb_ocorrencia',"WHERE status = 1 AND id_usuario_ocorrencia = '".$conteudo_usuarios['id_usuario']."' $filtro_ocorrencias");
            if($dados_ocorrencias){
                foreach ($dados_ocorrencias as $conteudo_ocorrencias) {
                    $tipos_ocorrencias[$conteudo_ocorrencias['id_ocorrencia_tipo']] += 1;
                    if($conteudo_ocorrencias['classificacao'] == 1){
                        $qtd_positivas_usuario++;
                    }else if($conteudo_ocorrencias['classificacao'] == 2){
                        $qtd_neutras_usuario++;
                    }else{
                        $qtd_negativas_usuario++;
                    }
                    $qtd_total_usuario++;
                }                
            }

            echo '
            <tr>
                <td>'.$conteudo_usuarios['nome'].'</td>
                <td class="text-center">'.$qtd_total_usuario.'</td>
                <td class="text-center" data-toggle="tooltip" data-placement="top" data-container="body" title="'.$qtd_positivas_usuario.'">'.sprintf("%01.2f", round($qtd_positivas_usuario*100/($qtd_total_usuario == 0 ? 1 : $qtd_total_usuario), 2)).'%</td>
                <td class="text-center" data-toggle="tooltip" data-placement="top" data-container="body" title="'.$qtd_neutras_usuario.'">'.sprintf("%01.2f", round($qtd_neutras_usuario*100/($qtd_total_usuario == 0 ? 1 : $qtd_total_usuario), 2)).'%</td>
                <td class="text-center" data-toggle="tooltip" data-placement="top" data-container="body" title="'.$qtd_negativas_usuario.'">'.sprintf("%01.2f", round($qtd_negativas_usuario*100/($qtd_total_usuario == 0 ? 1 : $qtd_total_usuario), 2)).'%</td>
            </tr>
            ';
            $qtd_total += $qtd_total_usuario;
            $qtd_positivas += $qtd_positivas_usuario;
            $qtd_neutras += $qtd_neutras_usuario;
            $qtd_negativas += $qtd_negativas_usuario;            
        }
            echo "</tbody>";
            echo "<tfoot>";
                echo '<tr>';                    
                    echo '<th><strong>Totais</strong></th>';	
                    echo '<th class="text-center">'.$qtd_total.'</th>';	
                    echo '<th class="text-center" data-toggle="tooltip" data-placement="top" data-container="body" title="'.$qtd_positivas.'">'.sprintf("%01.2f", round($qtd_positivas*100/($qtd_total == 0 ? 1 : $qtd_total), 2)).'%</th>';	
                    echo '<th class="text-center" data-toggle="tooltip" data-placement="top" data-container="body" title="'.$qtd_neutras.'">'.sprintf("%01.2f", round($qtd_neutras*100/($qtd_total == 0 ? 1 : $qtd_total), 2)).'%</th>';	
                    echo '<th class="text-center" data-toggle="tooltip" data-placement="top" data-container="body" title="'.$qtd_negativas.'">'.sprintf("%01.2f", round($qtd_negativas*100/($qtd_total == 0 ? 1 : $qtd_total), 2)).'%</th>';	
                echo '</tr>';
            echo "</tfoot> "; 
        echo "</table>";

        echo "
            <script>
                $(document).ready(function(){
                    $('.dataTableClassificacao').DataTable({
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

        echo"<hr>";

        echo "
            <table class=\"table table-hover dataTableTipos\"> 
                <thead> 
                    <tr> 
                        <th>Tipo</th>
                        <th class='text-center'>Total</th>
                    </tr>
                </thead> 
                <tbody>"
        ;
        foreach ($tipos_ocorrencias as $id_ocorrencia_tipo => $total) {
            $dados_ocorrencia_tipo = DBRead('','tb_ocorrencia_tipo',"WHERE id_ocorrencia_tipo = '$id_ocorrencia_tipo'");
            echo '
            <tr>
                <td>'.$dados_ocorrencia_tipo[0]['descricao'].'</td>
                <td class="text-center">'.$total.'</td>
            </tr>
            ';
        }
        echo "</tbody>";            
        echo "</table>";

        echo "
            <script>
                $(document).ready(function(){
                    $('.dataTableTipos').DataTable({
                        \"language\": {
                            \"url\": \"//cdn.datatables.net/plug-ins/1.10.19/i18n/Portuguese-Brasil.json\"
                        },
                        aaSorting: [[0, 'asc']],
                        \"searching\": false,
                        \"paging\":   false,
                        \"info\":     false
                    });
                });
            </script>			
        ";
    }else{
        echo "<table class='table table-bordered'>";
            echo "<tbody>";
                echo "<tr>";
                    echo "<td class='text-center'><h4>Não foram encontrados funcionários!</h4></td>";
                echo "</tr>";
            echo "</tbody>";
        echo "</table>";
    }    
}
?>