<?php
require_once(__DIR__."/System.php");
$parametros = (!empty($_POST['parametros'])) ? $_POST['parametros'] : '';
$data_inicial = addslashes($parametros['inicial']);
$id_usuario = addslashes($parametros['usuario']);
$id_usuario_sessao = $_SESSION['id_usuario'];
$dados = DBRead('', 'tb_usuario', "WHERE id_usuario = '$id_usuario_sessao'");
$perfil_usuario = $dados[0]['id_perfil_sistema'];

// Informações da query
$filtros_query  = "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE b.status != 2 AND a.status = 1 AND b.nome LIKE '%$letra%' AND (a.id_perfil_sistema = '3') ORDER BY b.nome ASC";

if($id_usuario == $id_usuario_sessao || $perfil_usuario == 2 || $perfil_usuario == 4){

	if(!$id_usuario){
		$id_usuario = $_SESSION['id_usuario'];
		$nome = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_usuario = $id_usuario");
		
	}else{
		$nome = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_usuario = $id_usuario");
		
	}
	$dados = DBRead('', 'tb_horarios_escala', "WHERE id_usuario = '".$id_usuario."' ORDER BY data_inicial DESC LIMIT 1");

	$dados_especiais = DBRead('', 'tb_horarios_especiais', "WHERE id_horarios_escala = '".$dados[0]['id_horarios_escala']."'");
	
	$dados_dom = DBRead('', 'tb_folgas_dom', "WHERE id_horarios_escala = '".$dados[0]['id_horarios_escala']."'");
	
}

//Primeiro dia mes (Ex: 2019-02-01) e ultimo dia do mes (Ex: 2019-02-28)
$dia_meses = explode("-", $data_inicial);
$ultimo_dia = date("t", mktime(0,0,0,$dia_meses[1],'01',$dia_meses[0]));
$data_inicial_mes = $data_inicial;
$data_final_mes = $dia_meses[0].'-'.$dia_meses[1].'-'.$ultimo_dia;

	$dados = DBRead('', 'tb_horarios_escala', "WHERE id_usuario = '".$id_usuario."' AND data_inicial = '".$data_inicial."' AND liberado = 1 ORDER BY data_inicial DESC LIMIT 1");

	$dados_especiais = DBRead('', 'tb_horarios_especiais', "WHERE id_horarios_escala = '".$dados[0]['id_horarios_escala']."'");
	
	$dados_dom = DBRead('', 'tb_folgas_dom', "WHERE id_horarios_escala = '".$dados[0]['id_horarios_escala']."'");

	$dados_ferias = DBRead('', 'tb_ferias', "WHERE id_usuario = '".$id_usuario."' AND (data_de BETWEEN '".$data_inicial_mes."' AND '".$data_final_mes."' OR data_ate BETWEEN '".$data_inicial_mes."' AND '".$data_final_mes."') ORDER BY data_de");

	$inicial_seg = $dados[0]['inicial_seg'];
	$final_seg = $dados[0]['final_seg'];
	if(!$dados[0]['inicial_sab']){
		$inicial_sab = 'N/D';
		$final_sab = 'N/D';
	}else{
		$inicial_sab = $dados[0]['inicial_sab'];
		$final_sab = $dados[0]['final_sab'];
	}

	if(!$dados[0]['inicial_dom']){
		$inicial_dom = 'N/D';
		$final_dom = 'N/D';
	}else{
		$inicial_dom = $dados[0]['inicial_dom'];
		$final_dom = $dados[0]['final_dom'];
	}	
	
	$data_lido = $dados[0]['data_lido'];

	if($dados_dom){
		if($dados_especiais){
			$tamanho_col = ' class="col-md-4"';
		}else{
			$tamanho_col = ' class="col-md-6"';
		}
	}else if($dados_especiais){
			$tamanho_col = ' class="col-md-6"';
	}else{
			$tamanho_col = ' class="col-md-12"';
	}

if(!$dados){
	echo "<div class=\"container-fluid text-center\"><div class=\"alert alert-warning\"><i aria-hidden=\"true\"></i> Ops! Você ainda não tem horários cadastrados para esta data!</div></div>";
}else{


	$dados_intervalo_seg = DBRead('', 'tb_horarios_escala_intervalo', "WHERE id_horarios_escala = '".$dados[0]['id_horarios_escala']."' AND dia = 'seg' LIMIT 1");
	$dados_intervalo_sab = DBRead('', 'tb_horarios_escala_intervalo', "WHERE id_horarios_escala = '".$dados[0]['id_horarios_escala']."' AND dia = 'sab' LIMIT 1");
	$dados_intervalo_dom = DBRead('', 'tb_horarios_escala_intervalo', "WHERE id_horarios_escala = '".$dados[0]['id_horarios_escala']."' AND dia = 'dom' LIMIT 1");


?>

<div class="row">
    <div class="col-md-4">
        <div class="panel panel-default">
            <div class="panel-heading clearfix">
                <h3 class="panel-title text-center pull-center">Segundas a Sextas</strong></h3>
            </div>	
            <div class="panel-body" style="padding-bottom: 0;">
				 <div class="row">
					<div class='col-md-6'>
						<div class="form-group" style='text-align: center'>
							<strong><span>Início</span></strong>
						</div>
							<div class='alert alert-success' role='alert' style='text-align: center'><?=$inicial_seg?></div>
					</div>
					<div class='col-md-6'>
						<div class="form-group" style='text-align: center'>
							<strong><span>Fim</span></strong>
						</div>
							<div class='alert alert-success' role='alert' style='text-align: center'><?=$final_seg?></div>
					</div>
				</div>

				<?php
				if($dados_intervalo_seg){
					if($dados_intervalo_seg[0]['tipo_intervalo'] == 1){
						echo "
						<div class='row'>
							<div class='col-md-12'>
								<div class='form-group' style='text-align: center'>
									<strong>
										<span>Tempo de Intervalo</span>
									</strong>
								</div>
								<div class='alert alert-info' role='alert' style='text-align: center'>".$dados_intervalo_seg[0]['tempo_intervalo']."</div>
							</div>
						</div>";
					}else{
						echo "
						<div class='row'>
							<div class='col-md-12'>
								<div class='form-group' style='text-align: center'>
									<strong>
										<span>Intervalo</span>
									</strong>
								</div>

								<div class='row'>
									<div class='col-md-6'>
										<div class='form-group' style='text-align: center'>
											<strong><span>Início</span></strong>
										</div>
											<div class='alert alert-info' role='alert' style='text-align: center'>".$dados_intervalo_seg[0]['intervalo_inicial']."</div>
									</div>
									<div class='col-md-6'>
										<div class='form-group' style='text-align: center'>
											<strong><span>Fim</span></strong>
										</div>
											<div class='alert alert-info' role='alert' style='text-align: center'>".$dados_intervalo_seg[0]['intervalo_final']."</div>
									</div>
								</div>

							</div>
						</div>";
					}
				}

				?>
				
			</div>
		</div>
	</div>

	<div class="col-md-4">
        <div class="panel panel-default">
            <div class="panel-heading clearfix">
                <h3 class="panel-title text-center pull-center">Sábados</strong></h3>
            </div>	
            <div class="panel-body" style="padding-bottom: 0;">
				<div class="row">
					<div class='col-md-6'>
						<div class="form-group" style='text-align: center'>
							<strong><span>Início</span></strong>
						</div>
							<div class='alert alert-success' role='alert' style='text-align: center'><?=$inicial_sab?></div>
					</div>
					<div class='col-md-6'>
						<div class="form-group" style='text-align: center'>
							<strong><span>Fim</span></strong>
						</div>
							<div class='alert alert-success' role='alert' style='text-align: center'><?=$final_sab?></div>
					</div>
				</div>

				<?php
				if($dados_intervalo_sab){
					if($dados_intervalo_sab[0]['tipo_intervalo'] == 1){
						echo "
						<div class='row'>
							<div class='col-md-12'>
								<div class='form-group' style='text-align: center'>
									<strong>
										<span>Tempo de Intervalo</span>
									</strong>
								</div>
								<div class='alert alert-info' role='alert' style='text-align: center'>".$dados_intervalo_sab[0]['tempo_intervalo']."</div>
							</div>
						</div>";
					}else{
						echo "
						<div class='row'>
							<div class='col-md-12'>
								<div class='form-group' style='text-align: center'>
									<strong>
										<span>Intervalo</span>
									</strong>
								</div>

								<div class='row'>
									<div class='col-md-6'>
										<div class='form-group' style='text-align: center'>
											<strong><span>Início</span></strong>
										</div>
											<div class='alert alert-info' role='alert' style='text-align: center'>".$dados_intervalo_sab[0]['intervalo_inicial']."</div>
									</div>
									<div class='col-md-6'>
										<div class='form-group' style='text-align: center'>
											<strong><span>Fim</span></strong>
										</div>
											<div class='alert alert-info' role='alert' style='text-align: center'>".$dados_intervalo_sab[0]['intervalo_final']."</div>
									</div>
								</div>

							</div>
						</div>";
					}
				}

				?>
			</div>
		</div>
	</div>
	<div class="col-md-4">
        <div class="panel panel-default">
            <div class="panel-heading clearfix">
                <h3 class="panel-title text-center pull-center">Domingos</strong></h3>
            </div>	
            <div class="panel-body" style="padding-bottom: 0;">
				<div class="row">
					<div class='col-md-6'>
						<div class="form-group" style='text-align: center'>
							<strong><span>Início</span></strong>
						</div>
							<div class='alert alert-success' role='alert' style='text-align: center'><?=$inicial_dom?></div>
					</div>
					<div class='col-md-6'>
						<div class="form-group" style='text-align: center'>
							<strong><span>Fim</span></strong>
						</div>
							<div class='alert alert-success' role='alert' style='text-align: center'><?=$final_dom?></div>
					</div>
				</div>

				<?php
				if($dados_intervalo_dom){
					if($dados_intervalo_dom[0]['tipo_intervalo'] == 1){
						echo "
						<div class='row'>
							<div class='col-md-12'>
								<div class='form-group' style='text-align: center'>
									<strong>
										<span>Tempo de Intervalo</span>
									</strong>
								</div>
								<div class='alert alert-info' role='alert' style='text-align: center'>".$dados_intervalo_dom[0]['tempo_intervalo']."</div>
							</div>
						</div>";
					}else{
						echo "
						<div class='row'>
							<div class='col-md-12'>
								

								<div class='row'>
									<div class='col-md-6'>
										<div class='form-group' style='text-align: center'>
											<strong><span>Início do Intervalo</span></strong>
										</div>
											<div class='alert alert-info' role='alert' style='text-align: center'>".$dados_intervalo_dom[0]['intervalo_inicial']."</div>
									</div>
									<div class='col-md-6'>
										<div class='form-group' style='text-align: center'>
											<strong><span>Fim do Intervalo</span></strong>
										</div>
											<div class='alert alert-info' role='alert' style='text-align: center'>".$dados_intervalo_dom[0]['intervalo_final']."</div>
									</div>
								</div>

							</div>
						</div>";
					}
				}

				?>

			</div>
		</div>
	</div>
</div>

<div class="row">
	<div <?= $tamanho_col ?>>
        <div class="panel panel-default">
            <div class="panel-heading clearfix">
                <h3 class="panel-title text-center pull-center">Folgas</strong></h3>
            </div>	
            <div class="panel-body" style="padding-bottom: 0;">
				 <div class="row">
					<div class='col-md-12'>
						<div class="form-group" style='text-align: center'>
							<strong><span>Dias</span></strong>
						</div>
							<div class='alert alert-info' role='alert' style='text-align: center'><?=$dados[0]['folga_seg']?>
							</div>	
					</div>
				</div>	
			</div>
		</div>
	</div>
	<?php

	if($dados_dom){
		$contador = sizeof($dados_dom);
		$tamanho_domingo = 12/($contador == 0 ? 1 : $contador);
		$tamanho_domingo = 'class = "col-md-'.$tamanho_domingo.'"';
	?>
		<div <?= $tamanho_col ?>>
            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    <h3 class="panel-title text-center pull-center">Domingo(s) de Folga(s)</strong></h3>
                </div>
					<div class="panel-body" style="padding-bottom: 0;">
						<div class="row">
						<?php

                		foreach ($dados_dom as $folga_domingo) {
					?>		
					
						<div <?= $tamanho_domingo?>>
							<div class="form-group" style='text-align: center'>
								<strong><span>Dia</span></strong>
							</div>
							<div class='alert alert-warning' role='alert' style='text-align: center'><?= converteData($folga_domingo['dia']) ?>
							</div>
						</div>         		
						<?php
						}	
						?>
        			</div>
				</div>
			</div>
		</div>
	<?php
	}
	?>
	<?php 
		$cont = 0;
		if($dados_especiais){
			foreach ($dados_especiais as $especial) {
				$cont++;
			}
		}
	
	if($dados_especiais){
	?>		
	<div <?= $tamanho_col ?>>
        <div class="panel panel-default">
            <div class="panel-heading clearfix">
                <h3 class="panel-title text-center pull-center">Horários Especiais (Feriados e etc)</strong></h3>
            </div>
			<div class="panel-body" style="padding-bottom: 0;">
				<?php
				foreach ($dados_especiais as $especial) {
				?>		
				 <div class="row">
					<div class="col-md-4">
						<div class="form-group" style='text-align: center'>
							<strong><span>Dia</span></strong>
						</div>
						<div class='alert alert-danger' role='alert' style='text-align: center'><?= converteData($especial['dia']) ?>
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group" style='text-align: center'>
							<strong><span>Início</span></strong>
						</div>
							<div class='alert alert-danger' role='alert' style='text-align: center'><?= $especial['inicial_especial'] ?>
							</div>
					</div>
					<div class="col-md-4">
						<div class="form-group" style='text-align: center'>
							<strong><span>Fim</span></strong>
						</div>
							<div class='alert alert-danger' role='alert' style='text-align: center'><?= $especial['final_especial'] ?>
							</div>
					</div>
				</div>
				<?php
				}	
				?>				
			</div>
		</div>
	</div>
	<?php
	}
	?>
	</div>	
	<?php if($dados_ferias){ 

		$tamanho_col_ferias = 12/sizeof($dados_ferias);
		$tamanho_col_ferias = "class='col-md-".$tamanho_col_ferias."'";

		foreach ($dados_ferias as $conteudo_ferias) {

		?>
			<div class="row">
			    <div <?=$tamanho_col_ferias?>>
			        <div class="panel panel-default">
			            <div class="panel-heading clearfix">
			                <h3 class="panel-title text-center pull-center">Férias ou Afastamentos</strong></h3>
			            </div>	
			            <div class="panel-body" style="padding-bottom: 0;">
							 <div class="row">
								<div class='col-md-6'>
									<div class="form-group" style='text-align: center'>
										<strong><span>Data Inicial</span></strong>
									</div>
										<div class='alert alert-success' role='alert' style='text-align: center'><?= converteData($conteudo_ferias['data_de']) ?></div>
								</div>
								<div class='col-md-6'>
									<div class="form-group" style='text-align: center'>
										<strong><span>Data Final</span></strong>
									</div>
										<div class='alert alert-success' role='alert' style='text-align: center'><?= converteData($conteudo_ferias['data_ate']) ?></div>
								</div>
							</div>
						</div>
				</div>
			</div>
	<?php 
		}
	}?>
	</div>
<?php
}
?>	
<script>
	
	$( document ).ready(function() {
		
		var id_horarios_escala = "<?=$dados[0]['id_horarios_escala'];?>";
		var data_lido = "<?=$dados[0]['data_lido'];?>";
		var id_usuario = "<?=$id_usuario;?>";
		var id_usuario_sessao = "<?=$id_usuario_sessao;?>";
		var perfil_usuario = "<?=$perfil_usuario;?>";
		var inicial_sab = "<?=$inicial_sab;?>";

   		if(id_usuario == id_usuario_sessao || perfil_usuario == 2){
	   		if(id_horarios_escala && !data_lido){
	   			var data_lido = "<?=converteDataHora($dados[0]['data_lido']);?>";
				$('#div_botao').html("<button class='btn btn-success pull-center' id='lido' dt-lido="+id_horarios_escala+"><i class='fa fa-check' aria-hidden='true'></i> Concordo!</button>");

		   	}else if(id_horarios_escala && data_lido){
		   		var data_lido = "<?=converteDataHora($dados[0]['data_lido']);?>";
	            $('#div_botao').html("<p><h3 class='panel-title text-center pull-center'>Ciente desde: "+data_lido+"</h3></p>");

		   	}else if(!id_horarios_escala && !data_lido){
		   		$('#div_botao').html("");
		   	}
	    }else{
	    	if(id_horarios_escala && data_lido){
		   		var data_lido = "<?=converteDataHora($dados[0]['data_lido']);?>";
	            $('#div_botao').html("<p><h3 class='panel-title text-center pull-center'>Ciente desde: "+data_lido+"</h3></p>");

		   	}else if(!inicial_sab){
	            $('#div_botao').html("");
		   	}else{
	            $('#div_botao').html("<p><h3 class='panel-title text-center pull-center'>Não ciente</h3></p>");
		   	}
	    }
	   	
	});

</script>


