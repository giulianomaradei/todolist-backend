<?php
require_once(__DIR__."/../class/System.php");

$gerar = (! empty($_POST['gerar'])) ? 1 : 0;

$id_usuario = (! empty($_POST['id_usuario'])) ? $_POST['id_usuario'] : '';

$data_hoje = getDataHora();
$data_hoje = explode(" ", $data_hoje);
$data_hoje = $data_hoje[0];
$primeiro_dia = "01/".$data_hoje[5].$data_hoje[6]."/".$data_hoje[0].$data_hoje[1].$data_hoje[2].$data_hoje[3];
$data_de = (!empty($_POST['data_de'])) ? $_POST['data_de'] : $primeiro_dia;
$data_ate = (!empty($_POST['data_ate'])) ? $_POST['data_ate'] : converteData(getDataHora('data'));

$id_sistema = (! empty($_POST['id_sistema'])) ? $_POST['id_sistema'] : '';

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
							style="margin-top: 2px;">Relatório de E-mails:</h3>
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
								        <label>Usuário:</label>
								        <select name="id_usuario" class="form-control input-sm">
									            <option value =''>Todos</option>
									            <?php
									            	$dados_usuarios = DBRead('','tb_usuario a',"INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.status = '1' ORDER BY b.nome ASC","a.id_usuario, b.nome");
									            	if($dados_usuarios){
									            		foreach ($dados_usuarios as $conteudo_usuarios) {
                                                            $selected = $id_usuario == $conteudo_usuarios['id_usuario'] ? "selected" : "";
									            			echo "<option value='".$conteudo_usuarios['id_usuario']."' ".$selected.">".$conteudo_usuarios['nome']."</option>";
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
										<label>*Data Inicial:</label>
										<input type="text" class="form-control date calendar input-sm" name="data_de" id="de" autocomplete="off" value="<?=$data_de?>" required>

									</div>
	                			</div>
	                			<div class="col-md-6">
	                				<div class="form-group">
										<label>*Data Final:</label>
										<input type="text" class="form-control date calendar input-sm" name="data_ate" id="ate" autocomplete="off" value="<?=$data_ate?>" required>
									</div>
	                			</div>
	                		</div>
                           
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Sistema:</label> 
                                        <select name="id_sistema" id="id_sistema" class="form-control input-sm">
                                            <option value="" <?php if($id_sistema == ''){echo 'selected';}?>>Todos</option> 
                                            <option value="simples" <?php if($id_sistema == 'simples'){echo 'selected';}?>>Simples V2</option> 
											<option value="painel" <?php if($id_sistema == 'painel'){echo 'selected';}?>>Painel do Cliente</option> 
											<option value="painel_rh" <?php if($id_sistema == 'painel_rh'){echo 'selected';}?>>Painel RH</option> 
                                        </select>
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
			relatorio_email($id_usuario, $id_sistema, $data_de, $data_ate);
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

    $(document).on('submit', 'form', function(){
        modalAguarde();
    });

</script>
<?php

function relatorio_email($id_usuario, $id_sistema, $data_de, $data_ate){

    if($id_usuario){
        $dados_id_usuario = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_usuario = '".$id_usuario."' ","a.id_usuario, b.nome");
        $legenda_id_usuario = $dados_id_usuario[0]['descricao'];
        $filtro_id_usuario = " AND id_usuario = '".$id_usuario."' ";
    }else{
        $legenda_id_usuario = "Todos";
        $filtro_id_usuario = "";
    }

    if($id_sistema){
        if($id_sistema == 'simples'){
            $legenda_id_sistema = "Simples V2";
        }else if($id_sistema == 'painel'){
            $legenda_id_sistema = "Painel do Cliente";
        }else if($id_sistema == 'painel_rh'){
            $legenda_id_sistema = "Painel RH";
        }
        $filtro_id_sistema = " AND sistema = '".$id_sistema."' ";
    }else{
        $legenda_id_sistema = "Todos";
        $filtro_id_sistema = "";
    }

    $filtro_data = " (AND data >= '".$data_de."' AND data <= '".$data_de."') ";

    $data_hoje = getDataHora();
    $data_hoje = converteDataHora($data_hoje);

    $periodo_amostra = "<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> de $data_de até $data_ate</span>";
    $gerado = "<span style=\"font-size: 14px;\"><strong>Gerado em: </strong> $data_hoje</span>";

    $data_de = converteData($data_de);
    $data_ate = converteData($data_ate);

    echo "<div class=\"col-md-12\" style=\"padding: 0\">";
    echo "<legend style=\"text-align:center;\"><strong>Relatório de E-mails</strong><br>$gerado</legend>";
    echo "<legend style=\"text-align:center;\">$periodo_amostra</legend>";
    echo "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong> Usuário - </strong>".$legenda_id_usuario.", <strong>Sistema - </strong>".$legenda_id_sistema."</legend>";
	
    $dados_email = DBRead('', 'tb_log', "WHERE tipo_operacao = 'email' ".$filtro_id_usuario." ".$filtro_id_sistema." " );
    
    if($dados_email){

		echo "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong> Total: </strong>".sizeof($dados_email)."</legend>";
        echo "<div class='table-responsive' style='border-radius: 15px;'>";
        echo "<table class='table table-bordered table-hover dataTable'>";
            echo "<thead>";
                echo "<tr>";
                    echo "<th class=\"col-md-3\" style='vertical-align: middle;'>Usuário</th>";
                    echo "<th class=\"col-md-3\" style='vertical-align: middle;'>Dados do E-mail</th>";
                    echo "<th class=\"col-md-3\" style='vertical-align: middle;'>Data Hora</th>";
                    echo "<th class=\"col-md-3\" style='vertical-align: middle;'>Sistema</th>";
                echo "</tr>";
            echo "</thead>";
            echo "<tbody>";
            foreach ($dados_email as $conteudo) {
                $dados_usuario = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_usuario = '".$conteudo['id_usuario']."' ","a.id_usuario, b.nome");
                $usuario = "(".$dados_usuario[0]['id_usuario'].") ".$dados_usuario[0]['nome'];
                
                $dados_tb_alterada = $conteudo['dados_tb_alterada'];
                $data = converteDataHora($conteudo['data']);

                $sistema = $conteudo['sistema'];
                if($conteudo['sistema'] == 'simples'){
                    $sistema = 'Simples V2';
                }else if($conteudo['sistema'] == 'painel'){
                    $sistema = 'Painel do Cliente';
                }else if($conteudo['sistema'] == 'painel_rh'){
                    $sistema = 'Painel RH';
                }
            
                echo "<tr>";    
                    echo "<td style='vertical-align: middle;'>".$usuario."</td>";
                    echo "<td style='vertical-align: middle;'>".nl2br($dados_tb_alterada)."</td>";
                    echo "<td style='vertical-align: middle;'>".$data."</td>";
                    echo "<td style='vertical-align: middle;'>".$sistema."</td>";
                echo "</tr>";
            }
            echo "</tbody>";
        echo "</table>";
    echo "</div>";

    echo "<script>
            $(document).ready(function(){
                var table = $('.dataTable').DataTable({
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
	echo "
    </div>";
}
?>