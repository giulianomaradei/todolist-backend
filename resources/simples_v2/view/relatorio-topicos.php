<?php
require_once(__DIR__."/../class/System.php");

$id_pessoa = (!empty($_POST['id_pessoa'])) ? $_POST['id_pessoa'] : '';
$gerar = (!empty($_POST['gerar'])) ? 1 : 0;
$data_de = (!empty($_POST['data_de'])) ? $_POST['data_de'] : '';
$data_ate = (!empty($_POST['data_ate'])) ? $_POST['data_ate'] : '';
$id_categoria = (!empty($_POST['id_categoria'])) ? $_POST['id_categoria'] : '';
$id_topico = (!empty($_POST['id_topico'])) ? $_POST['id_topico'] : '';
$tipo_relatorio = (!empty($_POST['tipo_relatorio'])) ? $_POST['tipo_relatorio'] : '1';
$id_usuario = $_SESSION['id_usuario'];
$usuario = (! empty($_POST['usuario'])) ? $_POST['usuario'] : '';
$dados = DBRead('', 'tb_usuario', "WHERE id_usuario = '$id_usuario'");
$perfil_sistema = $dados[0]['id_perfil_sistema'];

?>
<style>
	.conteudo-editor img{
        max-width: 100% !important;
        max-height: 100% !important;
        height: 100% !important;
    }
</style>

<?php

if($gerar){
	$collapse = '';
	$collapse_icon = 'plus';
	$dados = DBRead('','tb_pessoa',"WHERE id_pessoa = '$id_pessoa'");
	if($dados){
		$nome_pessoa = $dados[0]['nome'];
		$pessoa_input = $id_pessoa . ' - ' . $nome_pessoa;
	}else{
		$nome_pessoa = '';
		$pessoa_input ='';
	}
}else{
	$collapse = 'in';
	$collapse_icon = 'minus';
	$nome_pessoa = '';
	$pessoa_input = '';
}

?>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs/jszip-2.5.0/dt-1.10.18/b-1.5.4/b-colvis-1.5.4/b-flash-1.5.4/b-html5-1.5.4/b-print-1.5.4/r-2.2.2/datatables.min.css"/> 
<script type="text/javascript" src="https://cdn.datatables.net/v/bs/jszip-2.5.0/dt-1.10.18/b-1.5.4/b-colvis-1.5.4/b-flash-1.5.4/b-html5-1.5.4/b-print-1.5.4/r-2.2.2/datatables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/plug-ins/1.10.19/sorting/time.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/plug-ins/1.10.19/sorting/chinese-string.js"></script>
<div class="container-fluid">
	<form method="post">
		<div class="row">
	        <div class="col-md-4 col-md-offset-4">
	        	<div class="panel panel-default noprint">
	                <div class="panel-heading clearfix">
	                    <h3 class="panel-title text-left pull-left" style="margin-top: 2px;">Relatório de Tópicos:</h3>
	                    <div class="panel-title text-right pull-right">
	                    	<button data-toggle="collapse" data-target="#accordionRelatorio" class="btn btn-xs btn-info" type="button" title="Visualizar filtros"><i id="i_collapse" class="fa fa-<?=$collapse_icon?>"></i>
	                    	</button>
	                    </div>
	                </div>
	                <div id="accordionRelatorio" class="panel-collapse collapse <?=$collapse?>">
	                	<div class="panel-body">
	                		<?php if($perfil_sistema == '2'){?>
	                		<div class="row">
								<div class="col-md-12">
									<div class="form-group">
										<label>Tipo de Relatório:</label> <select
											name="tipo_relatorio" id="tipo_relatorio"
											class="form-control input-sm">
											<option value="1"
												<?php if($tipo_relatorio == '1'){echo 'selected';}?>>Visualizações</option>
											<option value="2"
												<?php if($tipo_relatorio == '2'){echo 'selected';}?>>Detalhado</option>
										</select>
									</div>
								</div>
							</div>
						<?php } ?>
	                		<div class="row">
	                			<div class="col-md-6">
	                				<div class="form-group">
										<label>Data inicial:</label>
										<input type="text" name="data_de" class="form-control  input-sm date calendar data_de filtro" id= "data_de" value="<?=$data_de?>">
									</div>
	                			</div>
	                			<div class="col-md-6">
	                				<div class="form-group">
										<label>Data final:</label>
										<input type="text" name="data_ate" class="form-control input-sm date calendar data_ate filtro" id= "data_ate" value="<?=$data_ate?>">
									</div>
	                			</div>
	                		</div>
	                		<div class="row">
	                			<div class="col-md-12">
	                				<div class="form-group">
										<label>Categoria:</label>
										<select class='form-control input-sm filtro' name='id_categoria' id='id_categoria'>
											<option value="">Todas</option>
											<?php
												$dados = DBRead('', 'tb_categoria ORDER BY nome ASC');
												foreach($dados as $dado){
													$selected = $id_categoria == $dado['id_categoria'] ? "selected" : "";
													echo "<option value='".$dado['id_categoria']."' ".$selected.">".$dado['nome']."</option>";
												}
											?>
										</select>
									</div>
	                			</div>
	                		</div>

							<div class="row">
	                			<div class="col-md-12">
	                				<div class="form-group">
			                            <label>Tópico:</label>
			                            <select class='form-control input-sm' name='id_topico' id='id_topico'>
											<option value="">Todos</option>
			                            	<?php 
			                            	$dados = DBRead('','tb_topico a', "WHERE titulo IS NOT NULL AND status != 2");
												foreach ($dados as $dados_topico) {
													$selected = $id_topico == $dados_topico['id_topico'] ? "selected" : "";
													echo "<option value='".$dados_topico['id_topico']."' ".$selected.">".$dados_topico['titulo']."</option>";
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
	<div class="col-md-8 col-md-offset-2" style="padding: 0">
		<?php 
			if($gerar){
				if($tipo_relatorio == 1){
					relatorio_visualizado($id_topico, $data_de, $data_ate, $id_categoria);
				}else if($tipo_relatorio == 2){
					relatorio($id_topico, $data_de, $data_ate, $id_categoria);
				}
			} 
		?>
	</div>
</div>

<script>

    function selectTopico(data_de, data_ate, id_categoria){
        $("select[name=id_topico]").html('<option value="">Carregando...</option>');
        $.post("/api/ajax?class=SelectTopico.php",
            {
            	data_de: data_de,
            	data_ate: data_ate,
            	id_categoria: id_categoria,
				token: '<?= $request->token ?>'
            },
            function(valor){
                $("select[name=id_topico]").html(valor);
                if(id_topico != undefined){
                    $('#id_topico').val();
                }
            }
        )
    }

    $(".filtro").on('change', function(){
    	data_de = $('.data_de').val();
    	data_ate = $('.data_ate').val();
    	id_categoria = $('#id_categoria').val();
        selectTopico(data_de, data_ate, id_categoria);
    });



    $('#accordionRelatorio').on('shown.bs.collapse', function(){
       $("#i_collapse").removeClass("fa fa-plus").addClass("fa fa-minus");
    });

    $('#accordionRelatorio').on('hidden.bs.collapse', function () {
       $("#i_collapse").removeClass("fa fa-minus").addClass("fa fa-plus");
    });

    $(document).on('submit', 'form', function(){
        modalAguarde();
    });

    $(document).ready(function() {
    	console.log( "ready!" );
    	var id_categoria = $('#id_categoria').val();
    	var data_de = $('#data_de').val();
    	var data_ate = $('#data_ate').val();
    	
    	if(data_de != '' && data_ate != ''){
    		selectTopico(data_de, data_ate, id_categoria);
    	}

	});

</script>

<?php
function relatorio($id_topico, $data_de, $data_ate, $id_categoria){

	$data_hora = converteDataHora(getDataHora());

	if(!$id_topico){

		$topico_legend = ', <strong>Tópico - </strong>Todos';

		if($data_de && $data_ate){
		    $periodo_amostra =" <legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> De $data_de até $data_ate</span></legend>";
		    $data_de = converteData($data_de);
			$data_ate = converteData($data_ate);

		    $filtro_data = " AND data_criacao >= '".$data_de." 00:00:00' AND data_criacao <= '".$data_ate." 23:59:00'";
		}elseif($data_de){
			$periodo_amostra =" <legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> A partir de $data_de</span></legend>";
			$data_de = converteData($data_de);
			$data_ate = converteData($data_ate);

		    $filtro_data = " AND data_criacao >= '".$data_de." 00:00:00'";
		}elseif($data_ate){
		    $periodo_amostra ="<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> Até $data_ate</span></legend>";
		    $data_de = converteData($data_de);
			$data_ate = converteData($data_ate);

		    $filtro_data = " AND data_criacao <= '".$data_ate." 23:59:00'";
		}else{
			$data_de = converteData($data_de);
			$data_ate = converteData($data_ate);
		    $filtro_data = "";
		    $periodo_amostra = "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> Todos</span></legend>";
		}

		if($id_categoria){
			$filtro_categoria = "AND id_categoria = '".$id_categoria."'";
			$dados_categoria = DBRead('', 'tb_categoria',"WHERE id_categoria = '".$id_categoria."'");
			$categoria_legend = "<strong> Categoria - </strong>".$dados_categoria[0]['nome'];
		}else{
			$legenda = "";
			$categoria_legend = "<strong> Categoria - </strong>Todos";
		}	

	}else{
		$filtro_topico = "AND id_topico = '".$id_topico."'";
		$dados_topico = DBRead('', 'tb_topico', "WHERE id_topico = '".$id_topico."'");
		$topico_legend = "<strong>Tópico - </strong>".$dados_topico[0]['titulo'];
	}


	$gerado = "<span style=\"font-size: 14px;\"><strong>Gerado em: </strong> $data_hora</span>";

   echo "<div class=\"col-md-12\" style=\"padding: 0\">";
	echo "<legend style=\"text-align:center;\"><strong>Relatório de Tópicos (Detalhado)</strong><br>$gerado</legend>";
    echo "$periodo_amostra";
	echo "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\">".$categoria_legend."".$topico_legend."";
	echo "</legend>";

	$dados = DBRead('', 'tb_topico', "WHERE status != 2 AND id_pai = '0' ".$filtro_categoria." ".$filtro_data." ".$filtro_topico."");

	if($dados){

		foreach($dados as $dado){

			$dado_usuario = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_usuario = ".$dado['id_usuario']);

			$dado_like_topico = DBRead('', 'tb_likes', "WHERE id_topico = '".$dado['id_topico']."' ","COUNT(*) as cont");

			?>
			<div class="panel panel-primary">
	  			<div class="panel-heading clearfix">
		  			<div class="row">
		  			 	<h3 class="panel-title text-left col-md-4"><strong>Título:</strong> <?= $dado['titulo'] ?></h3>
		  				<h3 class="panel-title text-right col-md-4"><strong>Criado por:</strong> <?= $dado_usuario[0]['nome'] ?></h3>
		  				<h3 class="panel-title text-right col-md-4"><strong>Data de Criação:</strong> <?= converteDataHora($dado['data_criacao']) ?></h3>
					</div>
	  			</div>
				<div class="panel-body painel-body">

					<div class="panel panel-info">
						<div class="panel-heading">
							<strong>Tópico:</strong>
						</div>
						<div class="panel-body">
					    	<table class="table table-hover col-md-12">
					    	
							    <tbody>
							        <tr >
							            <td class="col-md-11" style= "border-top: 0 !important"><strong>Descrição:</strong></td>
							            <td class="col-md-1" style= "border-top: 0 !important"><strong>Curtidas:</strong> <?= $dado_like_topico[0]['cont'] ?></td>
							        </tr>
							        <tr>
							            <td class="col-md-12 conteudo-editor" colspan="2" style= "border-top: 0 !important"><br><?= $dado['conteudo'] ?></td>
							        </tr>
							    </tbody>
					   
							</table>
				  		</div>
				  	</div>
					<?php
					$dados_comentario = DBRead('', 'tb_topico', "WHERE status != 2 AND id_pai = '".$dado['id_topico']."'");

					if($dados_comentario){
					?>

					<div class="panel panel-warning">
						<div class="panel-heading">
							<strong>Comentários:</strong>
						</div>
						<div class="panel-body">
					    	<table class="table table-hover" style="margin-bottom:0;">
								<tbody>
									<?php
									$cont = 0;
									foreach ($dados_comentario as $dado_comentario) {
										if($cont < 1){
											$borda = 'style= "border-top: 0 !important"';
										}else{
											$borda = '';
										}

										$dado_usuario = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_usuario = ".$dado_comentario['id_usuario']);

										$dado_like_comentario = DBRead('', 'tb_likes', "WHERE id_topico = '".$dados_comentario['id_topico']."' ","COUNT(*) as cont");
									?>								    
							        <tr>
							            <td class="col-md-5 conteudo-editor" <?= $borda ?>><strong>Comentário:</strong></td>
							            <td class="col-md-6" <?= $borda ?>><strong>Nome:</strong> <?= $dado_usuario[0]['nome'] ?></td>
							            <td class="col-md-1" <?= $borda ?>><strong>Curtidas: </strong> <?= $dado_like_comentario[0]['cont'] ?></td>
							        </tr>
							        <tr>
							            <td class="col-md-12 conteudo-editor" colspan="3" style= "border-top: 0 !important"><br> <?= $dado_comentario['conteudo'] ?></td>
							        </tr>
					    	
					   				<?php
									$cont++;
									}
									?>
								</tbody>
							</table>
					  	</div>
		     		</div>
		     		<?php
					}
					?>
				</div>
			</div>
		<?php
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
}

function relatorio_visualizado($id_topico, $data_de, $data_ate, $id_categoria){
	
	$data_hora = converteDataHora(getDataHora());

	if(!$id_topico){

		$topico_legend = ', <strong>Tópico - </strong>Todos';

		if($data_de && $data_ate){
		    $periodo_amostra =" <legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> De $data_de até $data_ate</span></legend>";
		    $data_de = converteData($data_de);
			$data_ate = converteData($data_ate);

		    $filtro_data = " AND data_criacao >= '".$data_de." 00:00:00' AND data_criacao <= '".$data_ate." 23:59:00'";
		}elseif($data_de){
			$periodo_amostra =" <legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> A partir de $data_de</span></legend>";
			$data_de = converteData($data_de);
			$data_ate = converteData($data_ate);

		    $filtro_data = " AND data_criacao >= '".$data_de." 00:00:00'";
		}elseif($data_ate){
		    $periodo_amostra ="<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> Até $data_ate</span></legend>";
		    $data_de = converteData($data_de);
			$data_ate = converteData($data_ate);

		    $filtro_data = " AND data_criacao <= '".$data_ate." 23:59:00'";
		}else{
			$data_de = converteData($data_de);
			$data_ate = converteData($data_ate);
		    $filtro_data = "";
		    $periodo_amostra = "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> Todos</span></legend>";
		}

		if($id_categoria){
			$filtro_categoria = "AND id_categoria = '".$id_categoria."'";
			$dados_categoria = DBRead('', 'tb_categoria',"WHERE id_categoria = '".$id_categoria."'");
			$categoria_legend = "<strong> Categoria - </strong>".$dados_categoria[0]['nome'];
		}else{
			$legenda = "";
			$categoria_legend = "<strong> Categoria - </strong>Todos";
		}	

	}else{
		$filtro_topico = "AND id_topico = '".$id_topico."'";
		$dados_topico = DBRead('', 'tb_topico', "WHERE id_topico = '".$id_topico."'");
		$topico_legend = "<strong>Tópico - </strong>".$dados_topico[0]['titulo'];
	}


	$gerado = "<span style=\"font-size: 14px;\"><strong>Gerado em: </strong> $data_hora</span>";

   echo "<div class=\"col-md-10 col-md-offset-1\" style=\"padding: 0\">";
	echo "<legend style=\"text-align:center;\"><strong>Relatório de Tópicos (Visualizações)</strong><br>$gerado</legend>";
    echo "$periodo_amostra";
	echo "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\">".$categoria_legend."".$topico_legend."";
	echo "</legend>";

	$dados = DBRead('', 'tb_topico', "WHERE status != 2 AND id_pai = '0' ".$filtro_categoria." ".$filtro_data." ".$filtro_topico."");

	if($dados){
		foreach($dados as $dado){

			$dado_usuario = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_usuario = ".$dado['id_usuario']);

			?>
			<div class="panel panel-primary">
				<div class="panel-heading clearfix">
					<div class="row">
						<h3 class="panel-title text-left col-md-4"><strong>Título:</strong> <?= $dado['titulo'] ?></h3>
						<h3 class="panel-title text-right col-md-4"><strong>Criado por:</strong> <?= $dado_usuario[0]['nome'] ?></h3>
						<h3 class="panel-title text-right col-md-4"><strong>Data de Criação:</strong> <?= converteDataHora($dado['data_criacao']) ?></h3>
					</div>
				</div>

					
						<div class="panel-body">
							<?php 
							echo '<table class="table table-hover dataTable" style="margin-bottom:0;">
										<thead>
										<tr>
											<th class="text-left  col-md-6">Usuário</th>
											<th class="text-left  col-md-3">Data visualizado</th>
											<th class="text-left  col-md-3">Data lido</th>
										</tr>
										</thead>
										<tbody>';  

										$dados_lido = DBRead('', 'tb_topico_visualizado a', "INNER JOIN tb_topico b ON a.id_topico = b.id_topico INNER JOIN tb_usuario c ON b.id_usuario = c.id_usuario WHERE  b.status != 2 AND b.id_topico = '".$dado['id_topico']."' " , "a.id_usuario AS usuario, a.data_visualizado, a.data_lido, b.titulo, b.data_criacao");

										foreach ($dados_lido as $lido) {

											$dado_usuario_lido = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_usuario = '".$lido['usuario']."'");

											echo "<tr>";
												echo "<td>".$dado_usuario_lido[0]['nome']."</td>";
												echo "<td>".converteDataHora($lido['data_visualizado'])."</td>";
												if(!$lido['data_lido']){
													echo "<td>Não registrado como lido ainda!</td>";
												}else{
													echo "<td>".converteDataHora($lido['data_lido'])."</td>";
												}

											echo "</tr>";
										}
										
									?>
									
								</tbody>
						
							</table>
				</div>
			</div>
			<?php
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

}


?>

