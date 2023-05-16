<?php
require_once(__DIR__."/../class/System.php");

$data_hoje = getDataHora();
$data_hoje = explode(" ", $data_hoje);
$data_hoje = $data_hoje[0];
$primeiro_dia = "01/".$data_hoje[5].$data_hoje[6]."/".$data_hoje[0].$data_hoje[1].$data_hoje[2].$data_hoje[3];

$tipo_relatorio = (!empty($_POST['tipo_relatorio'])) ? $_POST['tipo_relatorio'] : 1;
$data_de = (!empty($_POST['data_de'])) ? $_POST['data_de'] :$primeiro_dia;
$data_ate = (!empty($_POST['data_ate'])) ? $_POST['data_ate'] : converteData(getDataHora('data'));

$gerar = (!empty($_POST['gerar'])) ? 1 : 0;

$id_usuario = $_SESSION['id_usuario'];
$dados = DBRead('', 'tb_usuario', "WHERE id_usuario = '$id_usuario'");
$perfil_sistema = $dados[0]['id_perfil_sistema'];
$id_asterisk_usuario = $dados[0]['id_asterisk'];

$lider = (!empty($_POST['lider'])) ? $_POST['lider'] : '';
$turno = (!empty($_POST['turno'])) ? $_POST['turno'] : '';
$mostrar_filas = (!empty($_POST['mostrar_filas'])) ? $_POST['mostrar_filas'] : '1';

$responsavel_tecnico = (!empty($_POST['responsavel_tecnico'])) ? $_POST['responsavel_tecnico'] : '';

if($gerar){
	$collapse = '';
	$collapse_icon = 'plus';
}else{
	$collapse = 'in';
	$collapse_icon = 'minus';
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
	                    <h3 class="panel-title text-left pull-left" style="margin-top: 2px;">Relatório - Contagens do Call Center:</h3>
	                    <div class="panel-title text-right pull-right"><button data-toggle="collapse" data-target="#accordionRelatorio" class="btn btn-xs btn-info" type="button" title="Visualizar filtros"><i id="i_collapse" class="fa fa-<?=$collapse_icon?>"></i></button></div>
	                </div>
	                <div id="accordionRelatorio" class="panel-collapse collapse <?=$collapse?>">
	                	<div class="panel-body">	                		
                			<div class="row">
                				<div class="col-md-12">
                					<div class="form-group">
								        <label for="">Tipo de Relatório:</label>
								        <select name="tipo_relatorio" id="tipo_relatorio" class="form-control input-sm">
								        	<option value="1" <?php if($tipo_relatorio == '1'){ echo 'selected';}?>>Call Center - Suporte</option>
								        </select>
								    </div>
                				</div>
                			</div>
							<div class="row" id="row_periodo">
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
							<div class="row">
								<div class="col-md-12">
									<div class="form-group">
										<label for="">Líder Direto:</label>
										<select name="lider" class="form-control input-sm">
											<option value="" <?php if($lider == ''){ echo 'selected';}?>>Todos</option>
											<?php
											$dados_lider = DBRead('', 'tb_usuario a', "INNER JOIN tb_usuario b ON a.lider_direto = b.id_usuario INNER JOIN tb_pessoa c ON b.id_pessoa = c.id_pessoa WHERE a.lider_direto AND a.status = '1' AND b.id_perfil_sistema = '13' GROUP BY  a.lider_direto, c.nome ORDER BY c.nome ASC", "a.lider_direto, c.nome");
											// AND b.id_perfil_sistema = '13'
											if ($dados_lider) {
												foreach ($dados_lider as $conteudo_lider) {
													$selected = $lider == $conteudo_lider['lider_direto'] ? "selected" : "";
													echo "<option value='" . $conteudo_lider['lider_direto'] . "' ".$selected.">" . $conteudo_lider['nome'] . "</option>";
												}
											}
											?>
										</select>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12">
                                    <div class="form-group">
                                        <label>Turno/Horário:</label> 
                                        <select name="turno" id="turno" class="form-control input-sm">
                                            <?php
                                            $turnos = array(
                                                "" => "Todos",
                                                "integral" => "Integral",
                                                "meio" => "Meio Turno",
                                                "jovem" => "Jovem Aprendiz",
                                                "estagio" => "Estágio"
                                            );
                                            foreach ($turnos as $value => $tur) {
												$selected = $turno == $value ? "selected" : "";
                                                echo "<option value='".$value."'".$selected.">".$tur."</option>";
                                            }
                                            ?>      
                                        </select>                                   
                                    </div>
								</div>       
							</div>  
							
							<div class="row">
                				<div class="col-md-12">
                					<div class="form-group">
								        <label>Mostrar Filas:</label>
								        <select name="mostrar_filas" id="mostrar_filas" class="form-control input-sm">
											<option value="1" <?php if($mostrar_filas == '1'){ echo 'selected';}?>>Sim</option>
											<option value="2" <?php if($mostrar_filas == '2'){ echo 'selected';}?>>Não</option>
								        </select>
								    </div>
                				</div>
                			</div>

							<div class="row">
								<div class="col-md-12">
									<div class="form-group">
										<label>Responsável Técnico:</label>
										<select id="responsavel_tecnico" name="responsavel_tecnico" class="form-control input-sm">
											<option value=''>Todos</option>
											<?php
												$dados_tecnico = DBRead('', 'tb_perfil_sistema a', "INNER JOIN tb_usuario b ON a.id_perfil_sistema = b.id_perfil_sistema INNER JOIN tb_pessoa c ON b.id_pessoa = c.id_pessoa WHERE a.id_perfil_sistema = 4 AND b.status = 1 ORDER BY c.nome ASC","b.id_usuario, c.nome");
												if ($dados_tecnico) {
													foreach ($dados_tecnico as $conteudo_tecnico) {
														$selected = $responsavel_tecnico == $conteudo_tecnico['id_usuario'] ? "selected" : "";
														echo "<option value='".$conteudo_tecnico['id_usuario']."' ".$selected.">".$conteudo_tecnico['nome']."</option>";
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
			if($perfil_sistema == '3'){
				$tipo_relatorio = 1;
				$operador = 'AGENT/'.$id_asterisk_usuario;
			}else{
				if ($tipo_relatorio == 1) {
					relatorio_contagem($data_de, $data_ate, $lider, $turno, $mostrar_filas, $responsavel_tecnico);
				}
			}			
		}
		?>
	</div>
</div>
<script>	
	
	$(function () {
	  $('[data-toggle="tooltip"]').tooltip()
	})

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

</script>
<?php

function relatorio_contagem($data_de, $data_ate, $lider, $turno, $mostrar_filas, $responsavel_tecnico){

	$data_hoje = getDataHora();
    $data_hoje = converteDataHora($data_hoje);

	$periodo_amostra = "<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> de $data_de até $data_ate</span>";
	$gerado = "<span style=\"font-size: 14px;\"><strong>Gerado em: </strong> $data_hoje</span>";

	$data_de = converteData($data_de);
	$data_ate = converteData($data_ate);		

    $data_de_dias = new DateTime($data_de);
    $data_de_dias->modify('last day of this month');
    $qtd_dias_mes = $data_de_dias->format('d');
	$data_de_dias->modify('first day of this month');
	$referencia_escala = $data_de_dias->format('Y-m-d');

    
    $datetime1 = new DateTime($data_de);
    $datetime2 = new DateTime($data_ate);
    $interval = $datetime1->diff($datetime2);
    $diferenca_dias = $interval->days + 1;

	if($lider){
		$filtro_lider = "AND b.lider_direto = '".$lider."'";
		$inner_join_lider = "INNER JOIN tb_usuario b ON a.id_usuario = b.id_usuario";
		$dados_lider = DBRead('','tb_usuario a',"INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_usuario = '".$lider."'");
		$lider_nome = $dados_lider[0]['nome'];
	}else{
		$lider_nome = 'Qualquer';
		$filtro_lider = "";
		$inner_join_lider = "";
	}

	if($turno){
		$filtro_turno = "AND c.data_inicial = '".$referencia_escala."' AND c.carga_horaria = '".$turno."' ";
		$inner_join_turno = "INNER JOIN tb_horarios_escala c ON a.id_usuario = c.id_usuario";
		if($turno == "integral"){
			$turno_nome = "Integral";
		}else if($turno == "meio"){
			$turno_nome = "Meio Turno";
		}else if($turno == "jovem"){
			$turno_nome = "Jovem Aprendiz";
		}else if($turno == "estagio"){
			$turno_nome = "Estágio";
		}
		
	}else{
		$turno_nome = 'Qualquer';
		$filtro_turno = "";
		$inner_join_turno = "";
	}

	if($mostrar_filas == 1){
		$mostrar_filas_nome = 'Sim';
	}else{
		$mostrar_filas_nome = 'Não';
	}

	if($responsavel_tecnico){
		$filtro_responsavel_tecnico = "AND a.id_responsavel_tecnico = '".$responsavel_tecnico."'";
		$dados_responsavel_tecnico = DBRead('','tb_usuario a',"INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_usuario = '".$responsavel_tecnico."'");
		$responsavel_tecnico_nome = $dados_responsavel_tecnico[0]['nome'];
	}else{
		$responsavel_tecnico_nome = 'Qualquer';
		$filtro_responsavel_tecnico = "";
	}


	echo "<div class=\"col-md-12\" style=\"padding: 0\">";
	echo "<legend style=\"text-align:center;\"><strong>Relatório - Contagens do Call Center</strong><br>$gerado</legend>";
	echo "<legend style=\"text-align:center;\">$periodo_amostra</legend>";
	echo "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong>Líder - </strong>".$lider_nome.", <strong>Turno/Horário - </strong>".$turno_nome.", <strong>Mostrar Filas - </strong>".$mostrar_filas_nome.", <strong>Responsável Técnico - </strong>".$responsavel_tecnico_nome." </legend>";

	$dados_consulta = DBRead('','tb_contrato_plano_pessoa a',"INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa INNER JOIN tb_plano c ON a.id_plano = c.id_plano WHERE c.cod_servico = 'call_suporte' ".$filtro_responsavel_tecnico." AND (a.contrato_pai IS NULL OR a.contrato_pai = '0') AND a.status != 5 AND b.nome NOT LIKE '%belluno%' ORDER BY b.nome ASC", "a.*, b.*, a.status AS status_contrato");  
 
	if($dados_consulta){
		echo '
            <table class="table table-hover dataTable" style="margin-bottom:0;">
                <thead>
                    <tr>
                        <th>Contrato</th>
                        <th>Plano</th>
                        <th>Tipo de Cobrança</th>
                        <th>Qtd Contratada</th>
                        <th>Qtd de Excedentes</th>
                        <th>Qtd de Desafogos</th>
                        <th>Qtd Duplicados</th>
                        <th>Qtd Efetuada</th>
                        <th>Qtd Contratada / Proporcional</th>
                        <th>% Utilizada / Proporcional</th>';
                        if($mostrar_filas == 1){
							echo '<th>Fila Atual</th>';
						}
						echo'
                    </tr>
                </thead>
                <tbody>
        ';                     
	
		$contador_fat = 0;
		$contador_monitoramento = 0;
        $contador_excedente = 0;
        
		foreach($dados_consulta as $dado_consulta){
            $cont_faturado = 0;
            $contador_duplicados = 0;
            $cont_faturado_filho = 0;
            $contador_duplicados_filho = 0;
            $filas = '';
			$exibir = 0;
			if($dado_consulta['status_contrato'] == '1' || $dado_consulta['data_status'] >= $data_de){
				$exibir = 1;
			}
			if($exibir != 0){

                //FILAS
				if($mostrar_filas == 1){
					$dados_parametros = DBRead('','tb_parametros',"WHERE id_contrato_plano_pessoa = '".$dado_consulta['id_contrato_plano_pessoa']."'", "id_asterisk");
					if($dados_parametros){
						$dados_fila = DBRead('snep', 'empresas', "WHERE id='".$dados_parametros[0]['id_asterisk']."' LIMIT 1", "fila1");
						if($dados_fila){
							$filas .= $dados_fila[0]['fila1'].'<br>';
						}
					}      
				}

				$dados_consulta_filho = DBRead('','tb_contrato_plano_pessoa',"WHERE contrato_pai = '".$dado_consulta['id_contrato_plano_pessoa']."' ", "id_contrato_plano_pessoa");
				
				$texto_filho = '';
				
				if($dados_consulta_filho){

					foreach ($dados_consulta_filho as $conteudo_consulta_filho) {

                        //FILAS
						if($mostrar_filas == 1){
							$dados_parametros = DBRead('','tb_parametros',"WHERE id_contrato_plano_pessoa = '".$conteudo_consulta_filho['id_contrato_plano_pessoa']."'");
							if($dados_parametros){
								$dados_fila = DBRead('snep', 'empresas', "WHERE id='".$dados_parametros[0]['id_asterisk']."' LIMIT 1", "fila1");
								if($dados_fila){
									$filas .= $dados_fila[0]['fila1'].'<br>';
								}
							}       
						}

						$dados_filho = DBRead('','tb_contrato_plano_pessoa a',"INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE id_contrato_plano_pessoa = '".$conteudo_consulta_filho['id_contrato_plano_pessoa']."' ", "nome, id_contrato_plano_pessoa");
						
						$texto_filho .= '<a tabindex="0" data-toggle="tooltip" title="'.$dados_filho[0]['nome'].'"> <i class="fa fa-question-circle"></i></a>';
						
						$dados_faturado_filho = DBRead('','tb_atendimento a', $inner_join_lider." ".$inner_join_turno." WHERE a.gravado = '1' AND a.falha != 2 AND a.data_inicio BETWEEN '".$data_de." 00:00:00' AND '".$data_ate." 23:59:59' AND a.id_contrato_plano_pessoa = '".$dados_filho[0]['id_contrato_plano_pessoa']."' ".$filtro_lider." ".$filtro_turno." ", "a.data_inicio, a.cpf_cnpj, a.id_atendimento");
												
						if($dado_consulta['remove_duplicados'] == '1'){
						
							if($dados_faturado_filho){
								foreach($dados_faturado_filho as $conteudo_faturado_filho){

									$data_fim_filho = date('Y-m-d H:i:s', strtotime("-".$dado_consulta['minutos_duplicados']." minutes",strtotime($conteudo_faturado_filho['data_inicio'])));

									if(valida_cpf($conteudo_faturado_filho['cpf_cnpj']) || valida_cnpj($conteudo_faturado_filho['cpf_cnpj'])){

										$dados_duplicado = DBRead('','tb_atendimento a', $inner_join_lider." ".$inner_join_turno." WHERE a.gravado = '1' AND a.falha != 2 AND a.data_inicio <= '".$conteudo_faturado_filho['data_inicio']."' AND a.data_inicio >= '".$data_fim_filho."' AND a.id_contrato_plano_pessoa = '".$dados_filho[0]['id_contrato_plano_pessoa']."' AND a.cpf_cnpj = '".$conteudo_faturado_filho['cpf_cnpj']."' AND a.id_atendimento != '".$conteudo_faturado_filho['id_atendimento']."' ".$filtro_lider." ".$filtro_turno." ", "a.id_atendimento");
									
										if(!$dados_duplicado){
											$cont_faturado_filho++;
										}else{
											$contador_duplicados_filho++;
										}

									}else{
										$cont_faturado_filho++;
									}
								}
							}

						}else{

							$cont_dados_faturado_filho = DBRead('','tb_atendimento a', $inner_join_lider." ".$inner_join_turno." WHERE a.gravado = '1' AND a.falha != 2 AND a.data_inicio BETWEEN '".$data_de." 00:00:00' AND '".$data_ate." 23:59:59' AND a.id_contrato_plano_pessoa = '".$dados_filho[0]['id_contrato_plano_pessoa']."' ".$filtro_lider." ".$filtro_turno." ","COUNT(a.id_atendimento) AS cont");
						
							$cont_faturado_filho = $cont_dados_faturado_filho[0]['cont'];
						}
						
						$dados_monitoramento_filho = DBRead('', 'tb_monitoramento_queda a', $inner_join_lider." ".$inner_join_turno." WHERE a.id_contrato_plano_pessoa = '".$dados_filho[0]['id_contrato_plano_pessoa']."' AND a.data_registro BETWEEN '".$data_de." 00:00:00' AND '".$data_ate." 23:59:59' ".$filtro_lider." ".$filtro_turno." GROUP BY a.id_contrato_plano_pessoa", "a.id_contrato_plano_pessoa, COUNT(a.id_contrato_plano_pessoa) as cont");
						
						$cont_monitoramento_filho = $dados_monitoramento_filho[0]['cont'] ? $dados_monitoramento_filho[0]['cont'] : 0;
					}

				}else{

					$cont_monitoramento_filho = 0;
					$cont_faturado_filho = 0;
					$contador_duplicados_filho = 0;
				}

				if($dado_consulta['remove_duplicados'] == '1'){

					$dados_faturado = DBRead('','tb_atendimento a', $inner_join_lider." ".$inner_join_turno." WHERE a.gravado = '1' AND a.falha != 2 AND a.data_inicio BETWEEN '".$data_de." 00:00:00' AND '".$data_ate." 23:59:59' AND a.id_contrato_plano_pessoa = '".$dado_consulta['id_contrato_plano_pessoa']."' ".$filtro_lider." ".$filtro_turno." ", "a.data_inicio, a.cpf_cnpj, a.id_atendimento");

					if($dados_faturado){
						foreach($dados_faturado as $conteudo_faturado){

							$data_fim = date('Y-m-d H:i:s', strtotime("-".$dado_consulta['minutos_duplicados']." minutes",strtotime($conteudo_faturado['data_inicio'])));

							if(valida_cpf($conteudo_faturado['cpf_cnpj']) || valida_cnpj($conteudo_faturado['cpf_cnpj'])){

								$dados_duplicado = DBRead('','tb_atendimento a', $inner_join_lider." ".$inner_join_turno." WHERE a.gravado = '1' AND a.falha != 2 AND a.data_inicio <= '".$conteudo_faturado['data_inicio']."' AND a.data_inicio >= '".$data_fim."' AND a.id_contrato_plano_pessoa = '".$dado_consulta['id_contrato_plano_pessoa']."' AND a.cpf_cnpj = '".$conteudo_faturado['cpf_cnpj']."' AND a.id_atendimento != '".$conteudo_faturado['id_atendimento']."' ".$filtro_lider." ".$filtro_turno." ", "a.id_atendimento");
							
								if(!$dados_duplicado){
									$cont_faturado++;
								}else{
									$contador_duplicados++;
								}

							}else{
								$cont_faturado++;
							}
						}
					}

				}else{
				
					$cont_dados_faturado = DBRead('','tb_atendimento a', $inner_join_lider." ".$inner_join_turno." WHERE a.gravado = '1' AND a.falha != 2 AND a.data_inicio BETWEEN '".$data_de." 00:00:00' AND '".$data_ate." 23:59:59' AND a.id_contrato_plano_pessoa = '".$dado_consulta['id_contrato_plano_pessoa']."' ".$filtro_lider." ".$filtro_turno." ","COUNT(a.id_atendimento) AS cont");

					$cont_faturado = $cont_dados_faturado[0]['cont'];
				}
				
				$dados_monitoramento = DBRead('', 'tb_monitoramento_queda a', $inner_join_lider." ".$inner_join_turno." WHERE a.id_contrato_plano_pessoa = '".$dado_consulta['id_contrato_plano_pessoa']."' AND a.data_registro BETWEEN '".$data_de." 00:00:00' AND '".$data_ate." 23:59:59' ".$filtro_lider." ".$filtro_turno." GROUP BY a.id_contrato_plano_pessoa", "a.id_contrato_plano_pessoa, COUNT(a.id_contrato_plano_pessoa) as cont");

				$cont_monitoramento = $dados_monitoramento[0]['cont'] ? $dados_monitoramento[0]['cont'] : 0;
				
				if($dado_consulta['nome_contrato']){
	                $nome_contrato = " (".$dado_consulta['nome_contrato'].") ";
	            }else{
	                $nome_contrato = '';
	            }


	            $contrato = $dado_consulta['nome'] . " ". $nome_contrato."".$texto_filho ;

	           	$dados_planos = DBRead('','tb_plano',"WHERE id_plano = '".$dado_consulta['id_plano']."'", "nome, cod_servico");

				$qtd_efetuada = $cont_faturado + $cont_monitoramento + $cont_faturado_filho + $cont_monitoramento_filho;

				//CONTADOR EXCEDENTE
					$cont_excedente = ($qtd_efetuada) - $dado_consulta['qtd_contratada'];
					
					if($cont_excedente <= 0){
						$cont_excedente = 0;
					}

					if($dado_consulta['tipo_cobranca'] == 'unitario'){
						$desafogo_realizado = 0;
						$excedente_realizado = 0;

						// $valor_excedente_realizado = 0;
						// $valor_total_desafogo = 0;

						// $valor_cobranca = $dado_consulta['valor_inicial'] + ($cont_excedente * $dado_consulta['valor_unitario']);

					}else{
						if($dado_consulta['tipo_cobranca'] == 'mensal_desafogo'){
							
							$qtd_desafogo = $dado_consulta['qtd_contratada']*($dado_consulta['desafogo']/100);
							
							//SE FOR MAIOR DO QUE 5 ELE ARREDONDA PRA CIMA, SENÃO PRA BAIXO
							$qtd_desafogo = round($qtd_desafogo);

							//CONTAGEM DESAFOGO
							if(($cont_excedente - $qtd_desafogo) > 0){
								$desafogo_realizado = $qtd_desafogo;
								$excedente_realizado = $cont_excedente - $qtd_desafogo;
							}else if(($cont_excedente - $qtd_desafogo) == 0){
								$desafogo_realizado = $qtd_desafogo;
								$excedente_realizado = 0;

							}else if(($cont_excedente - $qtd_desafogo) < 0){

								if($cont_excedente == 0){
									$desafogo_realizado = 0;
									$excedente_realizado = 0;
								}else{
									$desafogo_realizado = $cont_excedente;
									$excedente_realizado = 0;
								}
							}				
						}else{
							$desafogo_realizado = 0;
							$excedente_realizado = $cont_excedente;
						}

					
					}	

					$qtd_duplicados = $contador_duplicados + $contador_duplicados_filho;

                if($dado_consulta['tipo_cobranca'] == 'mensal_desafogo'){
                    $tipo_cobranca = "Mensal com Desafogo (".$dado_consulta['desafogo']."%)";
                }else if($dado_consulta['tipo_cobranca'] == 'unitario'){
                    $tipo_cobranca = "Unitário";
                }else if($dado_consulta['tipo_cobranca'] == 'x_cliente_base'){
					$tipo_cobranca = "Até X Clientes na Base";
				}else if($dado_consulta['tipo_cobranca'] == 'prepago'){
					$tipo_cobranca = "Pré-pago";
				}else{
                    $tipo_cobranca = ucfirst($dado_consulta['tipo_cobranca']);
                }

                if($dado_consulta['tipo_cobranca'] != 'unitario'){
                    $qtd_contratada_dia = sprintf("%01.2f", round($dado_consulta['qtd_contratada']/$qtd_dias_mes*$diferenca_dias, 2));
                    $qtd_utilizada_dia = sprintf("%01.2f", round(($qtd_efetuada*100)/($qtd_contratada_dia == 0 ? 1 : $qtd_contratada_dia), 2));
                }else{
                    $qtd_contratada_dia = '0.00';
                    $qtd_utilizada_dia = '0.00';
                }
                
                echo "<tr>";

                    echo "<td>".$contrato."</td>";
                    echo "<td>".getNomeServico($dados_planos[0]['cod_servico'])." - ".$dados_planos[0]['nome']."</td>";
                    echo "<td>".$tipo_cobranca."</td>";
                    echo "<td>".$dado_consulta['qtd_contratada']."</td>";
                    echo "<td>".$excedente_realizado."</td>";
                    echo "<td>".$desafogo_realizado."</td>";
                    echo "<td>".$qtd_duplicados."</td>";
                    echo "<td>".$qtd_efetuada."</td>";
                    echo "<td>".$qtd_contratada_dia."</td>";
                    echo "<td>".$qtd_utilizada_dia."%</td>";
					if($mostrar_filas == 1){
						echo "<td>".substr($filas, 0, -4)."</td>";
					}
                echo "</tr>";      

				$contador_fat = $contador_fat + $cont_faturado;
				$contador_monitoramento = $contador_monitoramento + $cont_monitoramento;
				$contador_excedente = $contador_excedente + $cont_excedente;
			}
		}

		echo '		
			</tbody>';
        echo '</table>';
        echo "<br><br><br>";

		echo "<script>
				$(document).ready(function(){
					var table = $('.dataTable').DataTable({
						\"language\": {
							\"url\": \"//cdn.datatables.net/plug-ins/1.10.19/i18n/Portuguese-Brasil.json\"
						},
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
								filename: 'relatorio_financeiro_callcenter',
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

}

?>