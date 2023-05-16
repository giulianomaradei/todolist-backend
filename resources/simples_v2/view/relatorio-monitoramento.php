<?php
require_once(__DIR__."/../class/System.php");

$data_hoje = getDataHora();
$data_hoje = explode(" ", $data_hoje);
$data_hoje = $data_hoje[0];
$primeiro_dia = "01/".$data_hoje[5].$data_hoje[6]."/".$data_hoje[0].$data_hoje[1].$data_hoje[2].$data_hoje[3];

$gerar = (!empty($_POST['gerar'])) ? 1 : 0;
$tipo_relatorio = (!empty($_POST['tipo_relatorio'])) ? $_POST['tipo_relatorio'] : 1;
$data_de = (!empty($_POST['data_de'])) ? $_POST['data_de'] :$primeiro_dia;
$data_ate = (!empty($_POST['data_ate'])) ? $_POST['data_ate'] : converteData(getDataHora('data'));
$operador = (!empty($_POST['operador'])) ? $_POST['operador'] : '';
$status = (!empty($_POST['status'])) ? $_POST['status'] : '';
$id_contrato_plano_pessoa = (!empty($_POST['id_contrato_plano_pessoa'])) ? $_POST['id_contrato_plano_pessoa'] : '';

if($gerar){
	$collapse = '';
	$collapse_icon = 'plus';
}else{
	$collapse = 'in';
	$collapse_icon = 'minus';
}

?>

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
	                    <h3 class="panel-title text-left pull-left" style="margin-top: 2px;">Relatório - Monitoramento:</h3>
	                    <div class="panel-title text-right pull-right"><button data-toggle="collapse" data-target="#accordionRelatorio" class="btn btn-xs btn-info" type="button" title="Visualizar filtros"><i id="i_collapse" class="fa fa-<?=$collapse_icon?>"></i></button></div>
	                </div>
	                <div id="accordionRelatorio" class="panel-collapse collapse <?=$collapse?>">
	                	<div class="panel-body">	                		
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Tipo de Relatório:</label> <select
                                            name="tipo_relatorio" id="tipo_relatorio" class="form-control input-sm">
                                            <option value="1" <?php if($tipo_relatorio == '1'){echo 'selected';}?>>Detalhado</option>
                                            <option value="2" <?php if($tipo_relatorio == '2'){echo 'selected';}?>>Contagem</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group" >
                                        <label>Data Inicial:</label>
                                        <input type="text" class="form-control input-sm date calendar" name="data_de" value="<?=$data_de?>" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Data Final:</label>
                                        <input type="text" class="form-control input-sm date calendar" name="data_ate" value="<?=$data_ate?>" required>
                                    </div>
                                </div>
                            </div>
                			
							<div class="row">
								<div class="col-md-12">
									<div class="form-group">
								        <label>Contato técnico:</label>
                                        <select class="form-control input-sm" id="status" name="status">
                                             <option value="0">Qualquer</option>
                                             <option value="1" <?php if($status == 1){echo 'selected';}?>>Sem Sucesso</option>
                                             <option value="2" <?php if($status == 2){echo 'selected';}?>>Com Sucesso</option>
                                        </select>
								    </div>
								</div>
							</div>
							<div class="row" id="row_visibilidade" <?=$display_row_visibilidade?>>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Contrato:</label>
                                        <select class="form-control input-sm" id="id_contrato_plano_pessoa" name="id_contrato_plano_pessoa">
                                            <option value="">Qualquer</option>
                                            <?php
                                                $dados_contrato = DBRead('', 'tb_contrato_plano_pessoa a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa INNER JOIN tb_plano c ON a.id_plano = c.id_plano LEFT JOIN tb_informacao_geral_contrato d ON a.id_contrato_plano_pessoa = d.id_contrato_plano_pessoa WHERE a.status = 1 AND (d.monitoramento = '1' OR c.cod_servico = 'call_monitoramento') GROUP BY a.id_contrato_plano_pessoa ORDER BY b.nome ASC", "a.id_contrato_plano_pessoa, a.id_plano, a.nome_contrato, b.nome AS 'nome_pessoa', c.cod_servico, c.nome AS 'plano'");


                                                if($dados_contrato){
                                                    foreach ($dados_contrato as $conteudo_contrato) {
                                                        if($conteudo_contrato['nome_contrato']){
                                                            $nome_contrato = " (".$conteudo_contrato['nome_contrato'].") ";
                                                        }else{
                                                            $nome_contrato = '';
                                                        }

                                                        $contrato = $conteudo_contrato['nome_pessoa'] . " ". $nome_contrato ." - " . getNomeServico($conteudo_contrato['cod_servico']) . " - " . $conteudo_contrato['plano'] . " (" . $conteudo_contrato['id_contrato_plano_pessoa'] . ")";

                                                        $selected = $id_contrato_plano_pessoa == $conteudo_contrato['id_contrato_plano_pessoa'] ? "selected" : ""; 

                                                        echo "<option value='".$conteudo_contrato['id_contrato_plano_pessoa']."' ".$selected.">".$contrato."</option>";
                                                    }
                                                }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row" id="row_operador">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="">Atendente:</label>
                                        <select name="operador" class="form-control input-sm">
                                            <option value="">Todos</option>
                                            <?php
                                                $dados_operadores = DBRead('','tb_usuario a',"INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_perfil_sistema = 3 AND a.status = 1 ORDER BY b.nome");
                                                if($dados_operadores){
                                                    foreach ($dados_operadores as $conteudo_operadores) {
                                                        $selected = $operador == $conteudo_operadores['id_usuario'] ? "selected" : "";
                                                        echo "<option value='".$conteudo_operadores['id_usuario']."' ".$selected.">".$conteudo_operadores['nome']."</option>";
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
				relatorio_monitoramento($data_de, $data_ate, $operador, $status, $id_contrato_plano_pessoa);
            }else if($tipo_relatorio == 2){
                relatorio_monitoramento_contagem($data_de, $data_ate, $tecnico, $status, $id_contrato_plano_pessoa);
            }
		}
		?>
	</div>
</div>
 
<script>	

    $('#accordionRelatorio').on('shown.bs.collapse', function(){
       $("#i_collapse").removeClass("fa fa-plus").addClass("fa fa-minus");
    });
    $('#accordionRelatorio').on('hidden.bs.collapse', function(){
       $("#i_collapse").removeClass("fa fa-minus").addClass("fa fa-plus");
    });

    $(document).ready(function(){
	    $('#aguarde').hide();
	    $('#resultado').show();
	    $("#gerar").prop("disabled", false);
	   	
	});  

</script>

<?php

function relatorio_monitoramento($data_de, $data_ate, $operador, $status, $id_contrato_plano_pessoa){

    $data_de_consulta = converteDataHora($data_de).' 00:00:01';
    $data_ate_consulta = converteDataHora($data_ate).' 23:59:59';

    if($operador){
        $filtro_operador = "AND a.id_usuario = '".$operador."'";
        $dados_atendente = DBRead('','tb_usuario a',"INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_usuario = '".$operador."'");
        $legenda_operador = $dados_atendente[0]['nome'];
    }else{
        $filtro_operador = "";
        $legenda_operador = 'Qualquer';
    }
    if($status){
        $filtro_status = "AND status_contato = '".$status."'";
        if($status == '1'){
            $legenda_status = 'Sem Sucesso';
        }else if($status == '2'){
            $legenda_status = 'Com Sucesso';
        }
    }else{
        $filtro_status = "";
        $legenda_status = 'Qualquer';
    }
    if($id_contrato_plano_pessoa){
        $filtro_contrato_plano_pessoa = " AND id_contrato_plano_pessoa = '".$id_contrato_plano_pessoa."'";
        $dados_nome_contrato = DBRead('','tb_contrato_plano_pessoa a',"INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE id_contrato_plano_pessoa = '".$id_contrato_plano_pessoa."'");
        $legenda_contrato = $dados_nome_contrato[0]['nome'];
    }else{
        $filtro_contrato_plano_pessoa = "";
        $legenda_contrato = 'Qualquer';
    }
    
	$data_hoje = getDataHora();
	$data_hoje = converteDataHora($data_hoje);

	$periodo_amostra = "<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> de $data_de até $data_ate</span>";
	$gerado = "<span style=\"font-size: 14px;\"><strong>Gerado em: </strong> $data_hoje</span>";

	echo "<div class=\"col-md-12\" style=\"padding: 0\">";
	echo "<legend style=\"text-align:center;\"><strong>Relatório de Monitoramento - Detalhado</strong><br>$gerado</legend>";
    echo "<legend style=\"text-align:center;\">$periodo_amostra</legend>";
	echo "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong> Atendente - </strong>".$legenda_operador.", <strong>Contato Técnico - </strong>".$legenda_status.", <strong> Contrato - </strong>".$legenda_contrato."";
	echo "</legend>";

	$data_de = converteData($data_de);
	$data_ate = converteData($data_ate);

    $dados_monitoramento = DBRead('', 'tb_monitoramento_queda a',"INNER JOIN tb_usuario b ON a.id_usuario = b.id_usuario INNER JOIN tb_pessoa c ON b.id_pessoa = c.id_pessoa WHERE a.data_registro BETWEEN '".$data_de_consulta."' AND '".$data_ate_consulta."' ".$filtro_status." ".$filtro_operador." ".$filtro_contrato_plano_pessoa."");

    if($dados_monitoramento){
        $contador_total = count($dados_monitoramento);
            echo "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong>Total: </strong> ".$contador_total."</span></legend>";
    }

    if($dados_monitoramento){


    echo '<table class="table table-hover dataTable" style="margin-bottom:0;">
		      <thead>
		        <tr>
                    <th class="text-left">Data do registro</th>
                    <th class="text-left">Data da queda</th>
		            <th class="text-left">Nome Técnico</th>
		            <th class="text-left">Telefone</th>
		            <th class="text-left">Status</th>
		            <th class="text-left">Informação</th>
                    <th class="text-left">Contrato</th>
                    <th class="text-left">Plano</th>
                    <th class="text-left">POPs</th>
                    <th class="text-left">Atendente</th>
		        </tr>
		      </thead>
		      <tbody>';      

        foreach ($dados_monitoramento as $monitoramento) { 
            if($monitoramento['status_contato'] == 1){
                $status_contato = 'Com Sucesso';
            }else{
                $status_contato = 'Sem Sucesso';
            }

            $dados_contrato = DBRead('', 'tb_contrato_plano_pessoa a', "INNER JOIN tb_plano b ON a.id_plano = b.id_plano INNER JOIN tb_pessoa c ON a.id_pessoa = c.id_pessoa WHERE id_contrato_plano_pessoa = '".$monitoramento['id_contrato_plano_pessoa']."'", "a.*, b.cod_servico, b.nome AS 'plano', c.nome AS 'nome_pessoa'");

            if($dados_contrato[0]['nome_contrato']){
                $nome_contrato = " (".$dados_contrato[0]['nome_contrato'].") ";
            }else{
                $nome_contrato = '';
            }

            if(!$monitoramento['nome_tecnico']){
                $nome_tecnico = 'N/D';
            }else{
                $nome_tecnico = $monitoramento['nome_tecnico'];
            }

            if(!$monitoramento['telefone']){
                $telefone_tecnico = 'N/D';
            }else{
                $telefone_tecnico = '<span class = "phone">'.$monitoramento['telefone'].'</span>';
            }

            if(!$monitoramento['informacao']){
                $informacao = 'N/D';
            }else{
                $informacao = $monitoramento['informacao'];
            }
                
            $contrato = $dados_contrato[0]['nome_pessoa'] . " ". $nome_contrato ;
            
            echo '<tr>
                    <td class="text-left">'.converteDataHora($monitoramento['data_registro']).'</td>
                    <td class="text-left">'.converteDataHora($monitoramento['data_queda']).'</td>
                    <td class="text-left">'.$nome_tecnico.'</td>
                    <td class="text-left">'.$telefone_tecnico.'</td>
                    <td class="text-left">'.$status_contato.'</td>
                    <td class="text-left">'.$informacao.'</td>
                    <td class="text-left">'.$contrato.'</td>
                    <td class="text-left">'.getNomeServico($dados_contrato[0]['cod_servico'])." - ".$dados_contrato[0]['plano'].'</td>';
                
                    $dados_pop_queda = DBRead('', 'tb_pop_queda',"WHERE id_monitoramento_queda = '".$monitoramento['id_monitoramento_queda']."'");
                    echo '<td class="text-left">';
                        foreach ($dados_pop_queda as $pop_queda) { 
                            echo $pop_queda['nome'].'<br>';
                        }
                        
                    echo '<td class="text-left">'.$monitoramento['nome'].'</td>';

                    echo '</td>';

            echo '</tr>'; 

        }
    	
    		echo '		
    		      </tbody>';
    		   
    		echo '</table>

    		<br><br><br>';

    		echo "<script>
                    $(document).ready(function(){
                        $('.dataTable').DataTable({
                            \"language\": {
                                \"url\": \"//cdn.datatables.net/plug-ins/1.10.19/i18n/Portuguese-Brasil.json\"
                            },
                            columnDefs: [
                                { type: 'chinese-string', targets: 6 },
                            ],                      
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
    		
}

function relatorio_monitoramento_contagem($data_de, $data_ate, $tecnico, $status, $id_contrato_plano_pessoa){

    $data_de_consulta = converteDataHora($data_de).' 00:00:01';
    $data_ate_consulta = converteDataHora($data_ate).' 23:59:59';

    if($tecnico){
        $filtro_tecnico = "AND nome_tecnico = '".$tecnico."'";
        $legenda_tecnico = $tecnico;
    }else{
        $filtro_tecnico = "";
        $legenda_tecnico = 'Qualquer';
    }
    if($status){
        $filtro_status = "AND status_contato = '".$status."'";
        if($status == '1'){
            $legenda_status = 'Sem Sucesso';
        }else if($status == '2'){
            $legenda_status = 'Com Sucesso';
        }
    }else{
        $filtro_status = "";
        $legenda_status = 'Qualquer';
    }
    if($id_contrato_plano_pessoa){
        $filtro_contrato_plano_pessoa = " AND id_contrato_plano_pessoa = '".$id_contrato_plano_pessoa."'";
        $dados_nome_contrato = DBRead('','tb_contrato_plano_pessoa a',"INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE id_contrato_plano_pessoa = '".$id_contrato_plano_pessoa."'");
        $legenda_contrato = $dados_nome_contrato[0]['nome'];
    }else{
        $filtro_contrato_plano_pessoa = "";
        $legenda_contrato = 'Qualquer';
    }
    
    $data_hoje = getDataHora();
    $data_hoje = converteDataHora($data_hoje);

    $periodo_amostra = "<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> de $data_de até $data_ate</span>";
    $gerado = "<span style=\"font-size: 14px;\"><strong>Gerado em: </strong> $data_hoje</span>";

    echo "<div class=\"col-md-10 col-md-offset-1\" style=\"padding: 0\">";
    echo "<legend style=\"text-align:center;\"><strong>Relatório de Monitoramento - Contagem</strong><br>$gerado</legend>";
    echo "<legend style=\"text-align:center;\">$periodo_amostra</legend>";
    echo "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong> Técnico - </strong>".$legenda_tecnico.", <strong>Contato Técnico - </strong>".$legenda_status.", <strong> Contrato - </strong>".$legenda_contrato."";
    echo "</legend>";

    $data_de = converteData($data_de);
    $data_ate = converteData($data_ate);

    $dados_monitoramento = DBRead('', 'tb_monitoramento_queda',"WHERE data_registro BETWEEN '".$data_de_consulta."' AND '".$data_ate_consulta."' ".$filtro_tecnico." ".$filtro_status." ".$filtro_contrato_plano_pessoa." GROUP BY id_contrato_plano_pessoa", "id_contrato_plano_pessoa");

    if($dados_monitoramento){
        echo '<table class="table table-hover dataTable" style="margin-bottom:0;">
              <thead>
                <tr>
                    <th class="text-left col-md-3">Contrato</th>
                    <th class="text-left col-md-3">Plano</th>
                    <th class="text-left col-md-2">Com Sucesso</th>
                    <th class="text-left col-md-2">Sem Sucesso</th>
                    <th class="text-left col-md-2">Total</th>
                </tr>
              </thead>
              <tbody>';              

    
        $contador_com = 0;
        $contador_sem = 0;
        $contador_tot = 0;

        foreach($dados_monitoramento as $monitoramento){
            
            $dados_contrato = DBRead('', 'tb_contrato_plano_pessoa a', "INNER JOIN tb_plano b ON a.id_plano = b.id_plano INNER JOIN tb_pessoa c ON a.id_pessoa = c.id_pessoa WHERE id_contrato_plano_pessoa = '".$monitoramento['id_contrato_plano_pessoa']."'", "a.*, b.cod_servico, b.nome AS 'plano', c.nome AS 'nome_pessoa'");

            if($dados_contrato[0]['nome_contrato']){
                $nome_contrato = " (".$dados_contrato[0]['nome_contrato'].") ";
            }else{
                $nome_contrato = '';
            }
            
            $dados_com_sucesso = DBRead('', 'tb_monitoramento_queda',"WHERE data_registro BETWEEN '".$data_de_consulta."' AND '".$data_ate_consulta."' ".$filtro_tecnico." AND id_contrato_plano_pessoa = '".$monitoramento['id_contrato_plano_pessoa']."' AND status_contato = '1' GROUP BY id_contrato_plano_pessoa", "id_contrato_plano_pessoa, COUNT(id_contrato_plano_pessoa) as cont");

            $dados_sem_sucesso = DBRead('', 'tb_monitoramento_queda',"WHERE data_registro BETWEEN '".$data_de_consulta."' AND '".$data_ate_consulta."' ".$filtro_tecnico." AND id_contrato_plano_pessoa = '".$monitoramento['id_contrato_plano_pessoa']."' AND status_contato = '2' GROUP BY id_contrato_plano_pessoa", "id_contrato_plano_pessoa, COUNT(id_contrato_plano_pessoa) as cont");

            $com_sucesso = $dados_com_sucesso[0]['cont'] ? $dados_com_sucesso[0]['cont'] : 0;
            $sem_sucesso = $dados_sem_sucesso[0]['cont'] ? $dados_sem_sucesso[0]['cont'] : 0;
            $contador_total = $dados_sem_sucesso[0]['cont'] + $dados_com_sucesso[0]['cont'];
                
            $contrato = $dados_contrato[0]['nome_pessoa'] . " ". $nome_contrato ;

            echo '<tr>
                    <td class="text-left">'.$contrato.'</td>                
                    <td class="text-left">'.getNomeServico($dados_contrato[0]['cod_servico'])." - ".$dados_contrato[0]['plano'].'</td>                
                    <td class="text-left">'.$com_sucesso.'</td>
                    <td class="text-left">'.$sem_sucesso.'</td>
                    <td class="text-left">'.$contador_total.'</td>
                </tr>'; 
                $contador_com = $contador_com + $dados_com_sucesso[0]['cont'];
                $contador_sem = $contador_sem + $dados_sem_sucesso[0]['cont'];
                $contador_tot = $contador_tot + $contador_total;
        }

        echo '      
              </tbody>';
               echo "<tfoot>";
                    
                    echo '<tr>';
                        echo '<th>Totais</th>';
                        echo '<th></th>';
                        echo '<th>'.$contador_com.'</th>';
                        echo '<th>'.$contador_sem.'</th>';            
                        echo '<th>'.$contador_tot.'</th>';            
                    echo '</tr>';

                echo "</tfoot> ";
        echo '</table>

        <br><br><br>';

        echo "<script>
                    $(document).ready(function(){
                        $('.dataTable').DataTable({
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