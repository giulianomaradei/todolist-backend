
<?php
require_once(__DIR__."/../class/System.php");

$tipo_relatorio = (! empty($_POST['tipo_relatorio'])) ? $_POST['tipo_relatorio'] : 1;
$gerar = (! empty($_POST['gerar'])) ? 1 : 0;
$lider = (! empty($_POST['lider'])) ? $_POST['lider'] : '';

$id_usuario = $_SESSION['id_usuario'];
$dados = DBRead('', 'tb_usuario', "WHERE id_usuario = '$id_usuario'");
$perfil_sistema = $dados[0]['id_perfil_sistema'];

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
							style="margin-top: 2px;">Relatório de Equipes:</h3>
						<div class="panel-title text-right pull-right">
							<button data-toggle="collapse" data-target="#accordionRelatorio"
								class="btn btn-xs btn-info" type="button"
								title="Visualizar filtros">
								<i id="i_collapse" class="fa fa-<?=$collapse_icon?>"></i>
							</button>
						</div>
					</div>
					<div id="accordionRelatorio"
						class="panel-collapse collapse <?=$collapse?>">
						<div class="panel-body">
							<div class="row">
								<div class="col-md-12">
									<div class="form-group">
										<label>Tipo de Relatório:</label> <select
											name="tipo_relatorio" id="tipo_relatorio" class="form-control input-sm">
											<option value="1"
												<?php if($tipo_relatorio == '1'){echo 'selected';}?>>Listagem</option>
											<option value="2"
												<?php if($tipo_relatorio == '2'){echo 'selected';}?>>Contagem</option>
										</select>
									</div>
								</div>
							</div>
							
							<div class="row" id="row_operador">
								<div class="col-md-12">
									<div class="form-group">
										<label for="">Líder Direto:</label>
										<select name="lider" class="form-control input-sm">
											<option value="">Todos</option>
												<?php
												$dados_lider = DBRead('', 'tb_usuario a', "INNER JOIN tb_usuario b ON a.lider_direto = b.id_usuario INNER JOIN tb_pessoa c ON b.id_pessoa = c.id_pessoa WHERE a.lider_direto AND a.status = '1' GROUP BY a.lider_direto, c.nome ORDER BY c.nome ASC", "a.lider_direto, c.nome, c.razao_social AS nome_lider_direto");

												if ($dados_lider) {
													foreach ($dados_lider as $conteudo_lider) {
														$selected = $lider == $conteudo_lider['lider_direto'] ? "selected" : "";
														echo "<option value='" . $conteudo_lider['lider_direto'] . "' ".$selected.">" . $conteudo_lider['nome_lider_direto'] . "</option>";
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
			if ($perfil_sistema == '3') {
				$usuario = $id_usuario;
			}
			if ($tipo_relatorio == 1) {
				relatorio_listagem($lider);
			} else if ($tipo_relatorio == 2) {
				relatorio_contagem($lider);
			}
		}
	?>
	</div>
</div>
<script>
    $(document).on('submit', 'form', function(){       
        modalAguarde();
    });
    $('#accordionRelatorio').on('shown.bs.collapse', function () {
       $("#i_collapse").removeClass("fa fa-plus").addClass("fa fa-minus");
    });
    $('#accordionRelatorio').on('hidden.bs.collapse', function () {
       $("#i_collapse").removeClass("fa fa-minus").addClass("fa fa-plus");
    });
    $(document).on('submit', 'form', function () {       
        modalAguarde();
    });
    $(document).ready(function(){
	    $('#aguarde').hide();
	    $('#resultado').show();
	    $("#gerar").prop("disabled", false);
	});
    $(document).on('submit', 'form', function(){
        modalAguarde();
    });
</script>
<?php

function relatorio_listagem($lider){
	
	if($lider){
		$dados_lider = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa d ON a.id_pessoa = d.id_pessoa INNER JOIN tb_usuario b ON a.lider_direto = b.id_usuario INNER JOIN tb_pessoa c ON b.id_pessoa = c.id_pessoa WHERE a.lider_direto = '".$lider."' AND a.status = '1' ORDER BY d.nome ASC", "a.lider_direto, c.nome, c.razao_social AS nome_lider, d.nome, d.razao_social");
		$legenda_lider = $dados_lider[0]['nome_lider'];
	}else{
		$dados_lider = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa d ON a.id_pessoa = d.id_pessoa INNER JOIN tb_usuario b ON a.lider_direto = b.id_usuario INNER JOIN tb_pessoa c ON b.id_pessoa = c.id_pessoa WHERE a.lider_direto AND a.status = '1' ORDER BY d.nome ASC", "a.lider_direto, c.nome, c.razao_social AS nome_lider, d.nome, d.razao_social");
		$legenda_lider = 'Todos';
	}

	$data_hora = converteDataHora(getDataHora());
	echo "<div class=\"col-md-6 col-md-offset-3\" style=\"padding: 0\">";

	echo "<legend style=\"text-align:center;\"><strong>Listagem de Equipes</strong><br><span style=\"font-size: 14px;\"><strong>Gerado em:</strong> $data_hora</span></legend>";
	echo "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong> Líder Direto - </strong>".$legenda_lider."";
	echo "</legend>";

	echo '<table class="table table-hover dataTable" style="margin-bottom:0;">
		      <thead>
		        <tr>
		            <th class="text-left  col-md-2">Usuário</th>
		            <th class="text-left  col-md-2">Líder Direto</th>
		        </tr>
		      </thead>
		      <tbody>';    
		      $cont = 0;
		foreach ($dados_lider as $conteudo_lider) {
			echo "<tr>";
				echo "<td>".$conteudo_lider['razao_social']."</td>";
				echo "<td>".$conteudo_lider['nome_lider']."</td>";
			echo "</tr>";
			$cont++;
		}
		echo "</tbody>";
		if($lider){
			echo "<tfoot>";		
				echo '<tr>';
					echo '<th>Total de participantes: '.$cont.'</th>';
					echo '<th></th>';
				echo '</tr>';
			echo "</tfoot> ";
		}
	echo "</table>";
			
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
function relatorio_contagem($lider){
	
	if($lider){
		$dados_lider = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa d ON a.id_pessoa = d.id_pessoa INNER JOIN tb_usuario b ON a.lider_direto = b.id_usuario INNER JOIN tb_pessoa c ON b.id_pessoa = c.id_pessoa where a.lider_direto = '".$lider."' AND a.status = '1' GROUP BY c.nome ORDER BY c.nome ASC", "c.nome, c.razao_social AS nome_lider, COUNT(*) AS cont");
		$legenda_lider = $dados_lider[0]['nome_lider'];

	}else{
		$dados_lider = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa d ON a.id_pessoa = d.id_pessoa INNER JOIN tb_usuario b ON a.lider_direto = b.id_usuario INNER JOIN tb_pessoa c ON b.id_pessoa = c.id_pessoa where a.lider_direto AND a.status = '1' GROUP BY c.nome ORDER BY c.nome ASC", "c.nome, c.razao_social AS nome_lider, COUNT(*) AS cont");
		$legenda_lider = "Todos";
	}

	$data_hora = converteDataHora(getDataHora());
	echo "<div class=\"col-md-6 col-md-offset-3\" style=\"padding: 0\">";

	echo "<legend style=\"text-align:center;\"><strong>Contagem de Equipes</strong><br><span style=\"font-size: 14px;\"><strong>Gerado em:</strong> $data_hora</span></legend>";
	echo "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong> Líder Direto - </strong>".$legenda_lider."";
	echo "</legend>";

	echo '<table class="table table-hover dataTable" style="margin-bottom:0;">
		      <thead>
		        <tr>
		            <th class="text-left  col-md-2">Líder Direto</th>
		            <th class="text-left  col-md-2">Participantes</th>
		        </tr>
		      </thead>
		      <tbody>';    

		foreach ($dados_lider as $conteudo_lider) {
			echo "<tr>";
				echo "<td>".$conteudo_lider['nome_lider']."</td>";
				echo "<td>".$conteudo_lider['cont']."</td>";

			echo "</tr>";
		}
		echo "</tbody>";
	echo "</table>";
			
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