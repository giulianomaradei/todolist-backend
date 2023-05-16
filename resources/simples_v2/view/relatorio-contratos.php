<?php
require_once(__DIR__."/../class/System.php");


$gerar = (!empty($_POST['gerar'])) ? 1 : 0;

$tipo_relatorio = (!empty($_POST['tipo_relatorio'])) ? $_POST['tipo_relatorio'] : 1;

$data_de = (!empty($_POST['data_de'])) ? $_POST['data_de'] :'';
$data_ate = (!empty($_POST['data_ate'])) ? $_POST['data_ate'] : '';

$primeiro_dia = new DateTime(getDataHora('data'));
$primeiro_dia->modify('first day of this month');
$data_de_fim = (!empty($_POST['data_de_fim'])) ? $_POST['data_de_fim'] : $primeiro_dia->format('d/m/Y');

$primeiro_dia->modify('last day of this month');
$ultimo_dia = $primeiro_dia->format('d/m/Y');

$data_ate_fim = (!empty($_POST['data_ate_fim'])) ? $_POST['data_ate_fim'] : $ultimo_dia;

$status = (!empty($_POST['status'])) ? $_POST['status'] : '';
$plano = (!empty($_POST['plano'])) ? $_POST['plano'] : '';
$servico = (!empty($_POST['servico'])) ? $_POST['servico'] : '';
$id_contrato_plano_pessoa = (!empty($_POST['id_contrato_plano_pessoa'])) ? $_POST['id_contrato_plano_pessoa'] : '';
$id_responsavel = (!empty($_POST['id_responsavel'])) ? $_POST['id_responsavel'] : '';
$id_responsavel_tecnico = (!empty($_POST['id_responsavel_tecnico'])) ? $_POST['id_responsavel_tecnico'] : '';

$diferente = (!empty($_POST['diferente'])) ? $_POST['diferente'] : '';

$indice_reajuste = (!empty($_POST['indice_reajuste'])) ? $_POST['indice_reajuste'] : '';
$realiza_cobranca = (!empty($_POST['realiza_cobranca'])) ? $_POST['realiza_cobranca'] : '';
$desconsidera_notificacao = (!empty($_POST['desconsidera_notificacao'])) ? $_POST['desconsidera_notificacao'] : '';
$tipo_cobranca = (!empty($_POST['tipo_cobranca'])) ? $_POST['tipo_cobranca'] : '';

$data_de_recarga = (!empty($_POST['data_de_recarga'])) ? $_POST['data_de_recarga'] : $primeiro_dia->format('d/m/Y');
$data_ate_recarga = (!empty($_POST['data_ate_recarga'])) ? $_POST['data_ate_recarga'] : $ultimo_dia;

$data_de_cadastro = (!empty($_POST['data_de_cadastro'])) ? $_POST['data_de_cadastro'] : $data_de_recarga;
$data_ate_cadastro = (!empty($_POST['data_ate_cadastro'])) ? $_POST['data_ate_cadastro'] : $ultimo_dia;

if ($id_contrato_plano_pessoa) {
	$dados_contrato = DBRead('', 'tb_contrato_plano_pessoa a', "INNER JOIN tb_plano b ON a.id_plano = b.id_plano INNER JOIN tb_pessoa c ON a.id_pessoa = c.id_pessoa WHERE a.id_contrato_plano_pessoa = '$id_contrato_plano_pessoa'", "a.*, b.cod_servico, b.nome AS 'plano', c.nome AS 'nome_pessoa'");

	if ($dados_contrato[0]['nome_contrato']) {
		$nome_contrato = " (" . $dados_contrato[0]['nome_contrato'] . ") ";
	}

	$contrato = $dados_contrato[0]['nome_pessoa'] . " " . $nome_contrato . " - " . getNomeServico($dados_contrato[0]['cod_servico']) . " - " . $dados_contrato[0]['plano'] . " (" . $dados_contrato[0]['id_contrato_plano_pessoa'] . ")";
}

if($gerar){
	$collapse = '';
	$collapse_icon = 'plus';
}else{
	$collapse = 'in';
	$collapse_icon = 'minus';
}

if ($tipo_relatorio == 3) {

    $display_row_data = 'style="display:none;"';
    $display_row_data_fim = 'style="display:none;"';
    $display_row_contrato = 'style="display:none;"';
    $display_row_diferente = 'style="display:none;"';
    $display_row_status = '';
    $display_row_servico = '';
    $display_row_plano = '';
    $display_row_responsavel = '';
    $display_row_responsavel_tecnico = '';
    $display_row_tipo_cobranca = 'style="display:none;"';
    $display_row_data_recarga = 'style="display:none;"';
    $display_row_data_cadastro = 'style="display:none;"';
    
} else if ($tipo_relatorio == 6) {
    $display_row_data = 'style="display:none;"';
    $display_row_data_fim = 'style="display:none;"';
    $display_row_contrato = 'style="display:none;"';
    $display_row_diferente = 'style="display:none;"';
    $display_row_indice_reajuste = 'style="display:none;"';
    $display_row_realiza_cobranca = 'style="display:none;"';
    $display_row_desconsidera_notificacao = 'style="display:none;"';
    $display_row_status = 'style="display:none;"';
    $display_row_servico = 'style="display:none;"';
    $display_row_plano = 'style="display:none;"';
    $display_row_responsavel = 'style="display:none;"';
    $display_row_responsavel_tecnico = 'style="display:none;"';
    $display_row_tipo_cobranca = 'style="display:none;"';
    $display_row_data_recarga = 'style="display:none;"';
    $display_row_data_cadastro = 'style="display:none;"';

} else if ($tipo_relatorio == 7) {
    $display_row_data = 'style="display:none;"';
    $display_row_data_fim = '';
    $display_row_contrato = 'style="display:none;"';
    $display_row_diferente = 'style="display:none;"';
    $display_row_indice_reajuste = 'style="display:none;"';
    $display_row_realiza_cobranca = 'style="display:none;"';
    $display_row_desconsidera_notificacao = 'style="display:none;"';
    $display_row_status = 'style="display:none;"';
    $display_row_servico = '';
    $display_row_plano = '';
    $display_row_responsavel = '';
    $display_row_responsavel_tecnico = '';
    $display_row_tipo_cobranca = 'style="display:none;"';
    $display_row_data_recarga = 'style="display:none;"';
    $display_row_data_cadastro = 'style="display:none;"';

} else if ($tipo_relatorio == 8) {
    $display_row_data = 'style="display:none;"';
    $display_row_data_fim = 'style="display:none;"';
    $display_row_contrato = '';
    $display_row_diferente = 'style="display:none;"';
    $display_row_indice_reajuste = 'style="display:none;"';
    $display_row_realiza_cobranca = 'style="display:none;"';
    $display_row_desconsidera_notificacao = 'style="display:none;"';
    $display_row_status = 'style="display:none;"';
    $display_row_servico = 'style="display:none;"';
    $display_row_plano = 'style="display:none;"';
    $display_row_responsavel = 'style="display:none;"';
    $display_row_responsavel_tecnico = 'style="display:none;"';
    $display_row_tipo_cobranca = 'style="display:none;"';
    $display_row_data_recarga = '';
    $display_row_data_cadastro = '';

} else {

    $display_row_status = '';
    $display_row_servico = '';
    $display_row_plano = '';
    $display_row_responsavel = '';
    $display_row_responsavel_tecnico = '';

    if($servico == 'call_suporte'){
        $display_row_diferente = '';
    }else{
        $display_row_diferente = 'style="display:none;"';
    }

    $display_row_data = '';
    $display_row_data_fim = 'style="display:none;"';
    $display_row_contrato = '';
    if ($tipo_relatorio == 1 || $tipo_relatorio == 4) {
        $display_row_indice_reajuste = '';
        $display_row_realiza_cobranca = '';
        $display_row_desconsidera_notificacao = '';
        $display_row_tipo_cobranca = '';
    }else{
        $display_row_indice_reajuste = 'style="display:none;"';
        $display_row_realiza_cobranca = 'style="display:none;"';
        $display_row_desconsidera_notificacao = 'style="display:none;"';
        $display_row_tipo_cobranca = 'style="display:none;"';
    }
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
	                    <h3 class="panel-title text-left pull-left" style="margin-top: 2px;">Relatório - Contratos:</h3>
	                    <div class="panel-title text-right pull-right"><button data-toggle="collapse" data-target="#accordionRelatorio" class="btn btn-xs btn-info" type="button" title="Visualizar filtros"><i id="i_collapse" class="fa fa-<?=$collapse_icon?>"></i></button></div>
	                </div>
	                <div id="accordionRelatorio" class="panel-collapse collapse <?=$collapse?>">
	                	<div class="panel-body">	
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Tipo de Relatório:</label> 
                                        <select name="tipo_relatorio" id="tipo_relatorio" class="form-control input-sm">
                                            <option value="7"<?php if($tipo_relatorio == '7'){echo 'selected';}?>>Cancelamentos</option>
                                            <option value="1"<?php if($tipo_relatorio == '1'){echo 'selected';}?>>Completo</option>
                                            <option value="4"<?php if($tipo_relatorio == '4'){echo 'selected';}?>>Contratos - Tabela</option>
                                            <option value="3"<?php if($tipo_relatorio == '3'){echo 'selected';}?>>Encarteiramento de Clientes</option>
                                            <option value="2"<?php if($tipo_relatorio == '2'){echo 'selected';}?>>Quantidade de Clientes</option>
                                            <option value="5"<?php if($tipo_relatorio == '5'){echo 'selected';}?>>Tempo de Contrato</option>
                                            <option value="6"<?php if($tipo_relatorio == '6'){echo 'selected';}?>>Prorietários</option>
                                            <option value="8"<?php if($tipo_relatorio == '8'){echo 'selected';}?>>Contratos pré pagos (Recargas)</option>
                                        </select>
                                    </div>
                                </div>
                            </div>                		
                            <div class="row" id="row_data" <?=$display_row_data?>>
                                <div class="col-md-6">
                                    <div class="form-group" >
                                        <label>Data Inicial (Início do Cont.):</label>
                                        <input type="text" class="form-control input-sm date calendar" name="data_de" value="<?=$data_de?>">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Data Final (Início do Cont.):</label>
                                        <input type="text" class="form-control input-sm date calendar" name="data_ate" value="<?=$data_ate?>">
                                    </div>
                                </div>
                            </div>

                            <div class="row" id="row_data_fim" <?=$display_row_data_fim?>>
                                <div class="col-md-6">
                                    <div class="form-group" >
                                        <label>Data Inicial (Final da Cobrança):</label>
                                        <input type="text" class="form-control input-sm date calendar" name="data_de_fim" value="<?=$data_de_fim?>">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Data Final (Final da Cobrança):</label>
                                        <input type="text" class="form-control input-sm date calendar" name="data_ate_fim" value="<?=$data_ate_fim?>">
                                    </div>
                                </div>
                            </div>

                            <div class="row" id="row_data_recarga" <?=$display_row_data_recarga?>>
                                <div class="col-md-6">
                                    <div class="form-group" >
                                        <label>Data Inicial (Recarga):</label>
                                        <input type="text" class="form-control input-sm date calendar" name="data_de_recarga" value="<?=$data_de_recarga?>">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Data Final (Recarga):</label>
                                        <input type="text" class="form-control input-sm date calendar" name="data_ate_recarga" value="<?=$data_ate_recarga?>">
                                    </div>
                                </div>
                            </div>

                            <div class="row" id="row_data_cadastro" <?=$display_row_data_cadastro?>>
                                <div class="col-md-6">
                                    <div class="form-group" >
                                        <label>Data Inicial (Cadastro):</label>
                                        <input type="text" class="form-control input-sm date calendar" name="data_de_cadastro" value="<?=$data_de_cadastro?>">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Data Final (Cadastro):</label>
                                        <input type="text" class="form-control input-sm date calendar" name="data_ate_cadastro" value="<?=$data_ate_cadastro?>">
                                    </div>
                                </div>
                            </div>

                			<div class="row" id="row_status" <?=$display_row_status?>>
								<div class="col-md-12">
									<div class="form-group">
							        <label>Status:</label>
                                            <select class="form-control input-sm" id="status" name="status">
                                                <option value="todos">Todos</option>
                                                <?php
                                                    echo "<option value='1'".$status == "1" ? "selected" : "".">".getNomeStatusPlano(1)."</option>";
                                                    echo "<option value='0'".$status == "0" ? "selected" : "".">".getNomeStatusPlano(0)."</option>";
                                                    echo "<option value='2'".$status == "2" ? "selected" : "".">".getNomeStatusPlano(2)."</option>";
                                                    echo "<option value='3'".$status == "3" ? "selected" : "".">".getNomeStatusPlano(3)."</option>";
                                                    echo "<option value='4'".$status == "4" ? "selected" : "".">".getNomeStatusPlano(4)."</option>";
                                                    echo "<option value='5'".$status == "5" ? "selected" : "".">".getNomeStatusPlano(5)."</option>";
                                                    echo "<option value='6'".$status == "6" ? "selected" : "".">".getNomeStatusPlano(6)."</option>";
                                                    echo "<option value='7'".$status == "7" ? "selected" : "".">".getNomeStatusPlano(7)."</option>";
                                                    echo "<option value='8'".$status == "8" ? "selected" : "".">".getNomeStatusPlano(8)."</option>";
                                                ?>
                                            </select>
								    </div>
								</div>
							</div>
							<div class="row" id="row_servico" <?=$display_row_servico?>>
								<div class="col-md-12">
									<div class="form-group">
								        <label>Serviço:</label>
								        <select class="form-control input-sm" id="servico" name="servico">
                                        <option value="">Todos</option>
                                            <?php
                                                $dados_servico = DBRead('', 'tb_plano','GROUP BY cod_servico ORDER BY cod_servico', 'cod_servico');
                                                if($dados_servico){
                                                    foreach ($dados_servico as $conteudo_servico) {
                                                        $selected = $servico == $conteudo_servico['cod_servico'] ? "selected" : "";
                                                        echo "<option value='".$conteudo_servico['cod_servico']."' ".$selected.">".getNomeServico($conteudo_servico['cod_servico'])."</option>";
                                                    }
                                                }
                                            ?>
                                        </select>

								    </div>
								</div>
							</div>
                            <div class="row" id="row_plano" <?=$display_row_plano?>>
								<div class="col-md-12">
									<div class="form-group">
							        <label>Plano:</label>
                                        <select class="form-control input-sm" id="plano" name="plano">
                                             <option value="">Todos</option>
                                            <?php
                                                $dados_plano = DBRead('', 'tb_plano',"ORDER BY nome");
                                                if($dados_plano){
                                                    foreach ($dados_plano as $conteudo_plano) {
                                                        $selected = $plano == $conteudo_plano['id_plano'] ? "selected" : "";
                                                        echo "<option value='".$conteudo_plano['id_plano']."' ".$selected.">".$conteudo_plano['nome']."</option>";
                                                    }
                                                }
                                            ?>
                                            
                                        </select>
								    </div>
								</div>
							</div>							
                            <div class="row" id="row_contrato" <?=$display_row_contrato?>>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>*Contrato (cliente):</label>
                                        <div class="input-group">
                                            <input class="form-control input-sm" id="busca_contrato" type="text" name="busca_contrato" value="<?=$contrato?>" placeholder="Informe o nome ou CNPJ..." autocomplete="off" readonly/>
                                            <div class="input-group-btn">
                                                <button class="btn btn-info btn-sm" id="habilita_busca_contrato" name="habilita_busca_contrato" type="button" title="Clique para selecionar o contrato" style="height: 30px;"><i class="fa fa-search"></i></button>
                                                <?php echo "<input type='hidden' name='id_contrato_plano_pessoa' id='id_contrato_plano_pessoa' value='$id_contrato_plano_pessoa' />";?>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>  
                            <div class="row" id="row_responsavel" <?=$display_row_responsavel?>>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Responsável pelo Relacionamento:</label>
                                        <select class="form-control input-sm" id="id_responsavel" name="id_responsavel">
                                            <option value=''>Qualquer</option>
                                            <?php
                                                $dados_responsavel = DBRead('', 'tb_perfil_sistema a', "INNER JOIN tb_usuario b ON a.id_perfil_sistema = b.id_perfil_sistema INNER JOIN tb_pessoa c ON b.id_pessoa = c.id_pessoa WHERE a.id_perfil_sistema = 11 AND b.status = 1 ORDER BY c.nome ASC","b.id_usuario, c.nome");
                                                
                                                if ($dados_responsavel) {
                                                    foreach ($dados_responsavel as $conteudo_responsavel) {
                                                        $selected = $id_responsavel == $conteudo_responsavel['id_usuario'] ? "selected" : "";
                                                        echo "<option value='".$conteudo_responsavel['id_usuario']."' ".$selected.">".$conteudo_responsavel['nome']."</option>";
                                                    }
                                                }
                                            ?>
                                        </select>   
                                    </div>
                                </div> 
                            </div> 
                            <div class="row" id="row_responsavel_tecnico" <?=$display_row_responsavel_tecnico?>>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Responsável Técnico:</label>
                                        <select class="form-control input-sm" id="id_responsavel_tecnico" name="id_responsavel_tecnico">
                                            <option value=''>Qualquer</option>
                                            <?php
                                                $dados_responsavel_tecnico = DBRead('', 'tb_perfil_sistema a', "INNER JOIN tb_usuario b ON a.id_perfil_sistema = b.id_perfil_sistema INNER JOIN tb_pessoa c ON b.id_pessoa = c.id_pessoa WHERE a.id_perfil_sistema = 4 AND b.status = 1 ORDER BY c.nome ASC","b.id_usuario, c.nome");
                                                
                                                if ($dados_responsavel_tecnico) {
                                                    foreach ($dados_responsavel_tecnico as $conteudo_responsavel_tecnico) {
                                                        $selected = $id_responsavel_tecnico == $conteudo_responsavel_tecnico['id_usuario'] ? "selected" : "";
                                                        echo "<option value='".$conteudo_responsavel_tecnico['id_usuario']."' ".$selected.">".$conteudo_responsavel_tecnico['nome']."</option>";
                                                    }
                                                }
                                            ?>
                                        </select>   
                                    </div>
                                </div> 
                            </div>                                 
                            <div class="row" id="row_diferente" <?=$display_row_diferente?>>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Mostrar Contratos com Valores Diferentes para Texto e Voz:</label>
                                        <select class="form-control input-sm" id="diferente" name="diferente">
                                            <option value="">Todos</option>
                                                <?php
                                                echo "<option value='1'".$diferente == "1" ? "selected" : "".">Apenas Diferentes</option>";
                                                echo "<option value='2'".$diferente == "2" ? "selected" : "".">Não Mostrar Diferentes</option>";
                                                ?>
                                        </select>   
                                    </div>
                                </div> 
                            </div>       
                            <div class="row" id="row_indice_reajuste" <?=$display_row_indice_reajuste?>>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>*Índice de Reajuste:</label>
                                        <select name="indice_reajuste" id="indice_reajuste" class="form-control input-sm">
                                            <option value="">Todos</option>
                                            <?php
                                            echo "<option value='IPCA' ".$indice_reajuste == "IPCA" ? "selected" : "".">2% + IPCA</option>";
                                            echo "<option value='10%' ".$indice_reajuste == "10%" ? "selected" : "".">10%</option>";
                                            echo "<option value='15%' ".$indice_reajuste == "15" ? "selected" : "".">15%</option>";
                                            echo "<option value='IGPM' ".$indice_reajuste == "IGPM" ? "selected" : "".">IGPM</option>";
                                            echo "<option value='INPC' ".$indice_reajuste == "INPC" ? "selected" : "".">INPC</option>";
                                            echo "<option value='ND' ".$indice_reajuste == "ND" ? "selected" : "".">Não Definido</option>";
                                            ?>
                                        </select> 
                                    </div>
                                </div> 
                            </div>  
                            <div class="row" id="row_realiza_cobranca" <?=$display_row_realiza_cobranca?>>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>*Realiza Cobrança:</label>
                                        <select name="realiza_cobranca" id="realiza_cobranca" class="form-control input-sm">
                                            <option value="">Todos</option>
                                            <?php
                                                echo "<option value='1'".$realiza_cobranca == "1" ? "selected" : "".">Sim</option>";
                                                echo "<option value='2'".$realiza_cobranca == "2" ? "selected" : "".">Não</option>";
                                            ?>
                                        </select> 
                                    </div>
                                </div> 
                            </div> 

                            <div class="row" id="row_desconsidera_notificacao" <?=$display_row_desconsidera_notificacao?>>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>*Cobrar Menor Valor nas Notificações de Parada:</label>
                                        <select name="desconsidera_notificacao" id="desconsidera_notificacao" class="form-control input-sm">
                                            <option value="">Todos</option>
                                            <?php
                                                echo "<option value='1'".$desconsidera_notificacao == "1" ? "selected" : "".">Sim</option>";
                                                echo "<option value='0'".$desconsidera_notificacao == "0" ? "selected" : "".">Não</option>";
                                            ?>
                                        </select> 
                                    </div>
                                </div> 
                            </div> 
                            
                            <div class="row" id="row_tipo_cobranca" <?=$display_row_tipo_cobranca?>>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>*Tipo de Cobrança:</label>
                                        <select name="tipo_cobranca" id="tipo_cobranca" class="form-control input-sm">
                                            <option value="">Qualquer</option>
                                            <?php
                                            echo "<option value='x_cliente_base'".$tipo_cobranca == "x_cliente_base" ? "selected" : ""." id = 'option_x_cliente_base'>Até X Clientes na Base</option>";
                                            echo "<option value='mensal'".$tipo_cobranca == "mensal" ? "selected" : ""." id = 'option_mensal'>Mensal</option>";
                                            echo "<option value='mensal_desafogo'".$tipo_cobranca == "mensal_desafogo" ? "selected" : ""." id = 'option_mensal_desafogo'>Mensal com Desafogo</option>";
                                            echo "<option value='unitario'".$tipo_cobranca == "unitario" ? "selected" : ""." id = 'option_unitario'>Unitário</option>";
                                            echo "<option value='prepago'".$tipo_cobranca == "prepago" ? "selected" : ""." id = 'option_mensal'>Pré=Pago</option>";
                                            
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
		if ($gerar) {
			if ($tipo_relatorio == 1) {
				relatorio_contratos($status, $plano, $servico, $id_contrato_plano_pessoa, $data_de, $data_ate, $id_responsavel, $id_responsavel_tecnico, $diferente, $indice_reajuste, $realiza_cobranca, $tipo_cobranca, $desconsidera_notificacao);

            } else if ($tipo_relatorio == 2) {
                relatorio_quantidade_clientes($status, $plano, $servico, $id_contrato_plano_pessoa, $data_de, $data_ate, $id_responsavel, $id_responsavel_tecnico, $diferente);

            } else if ($tipo_relatorio == 3)
            {
                relatorio_encarteiramento_clientes($status, $plano, $servico, $id_responsavel, $id_responsavel_tecnico);
            } else if($tipo_relatorio == 4) {
                relatorio_contratos_tabela($status, $plano, $servico, $id_contrato_plano_pessoa, $data_de, $data_ate, $id_responsavel, $id_responsavel_tecnico, $diferente, $indice_reajuste, $realiza_cobranca, $tipo_cobranca, $desconsidera_notificacao);

            } else if ($tipo_relatorio == 5) {
                relatorio_tempo_contrato($status, $plano, $servico, $id_contrato_plano_pessoa, $data_de, $data_ate, $id_responsavel, $id_responsavel_tecnico, $diferente, $indice_reajuste, $realiza_cobranca);

            } else if ($tipo_relatorio == 6) {
                relatorio_proprietarios();
           
            } else if ($tipo_relatorio == 7) {
                relatorio_cancelamentos($plano, $servico, $data_de_fim, $data_ate_fim, $id_responsavel, $id_responsavel_tecnico);
            
            } else if ($tipo_relatorio == 8) {
                relatorio_pre_pago_recarga($id_contrato_plano_pessoa, $data_de_recarga, $data_ate_recarga, $data_de_cadastro, $data_ate_cadastro);
            }
		}
		?>
	</div>
</div>
 
<script>	
    $('#tipo_relatorio').on('change',function(){

        var tipo_relatorio = $(this).val();
        var servico = $('#servico').val();

        if (tipo_relatorio == 3) {
            
            $('#row_data').hide();
            $('#row_data_fim').hide();
            $('#row_contrato').hide();
            $('#row_diferente').hide();
            $('#row_indice_reajuste').show();
            $('#row_status').show();
            $('#row_responsavel').show();
            $('#row_responsavel_tecnico').show();
            $('#row_servico').show();
            $('#row_status').show();
            $('#row_plano').show();
            $('#row_realiza_cobranca').show();
            $('#row_desconsidera_notificacao').show();
            $('#row_tipo_cobranca').hide();
            $('#row_data_recarga').hide();
            $('#row_data_cadastro').hide();
            
        } else if (tipo_relatorio == 6) {
            $('#row_data').hide();
            $('#row_data_fim').hide();
            $('#row_contrato').hide();
            $('#row_diferente').hide();
            $('#row_indice_reajuste').hide();
            $('#row_status').hide();
            $('#row_responsavel').hide();
            $('#row_responsavel_tecnico').hide();
            $('#row_servico').hide();
            $('#row_status').hide();
            $('#row_plano').hide();
            $('#row_realiza_cobranca').hide();
            $('#row_desconsidera_notificacao').hide();
            $('#row_tipo_cobranca').hide();
            $('#row_data_recarga').hide();
            $('#row_data_cadastro').hide();
        
        } else if (tipo_relatorio == 7) {
            $('#row_data').hide();
            $('#row_data_fim').show();
            $('#row_contrato').hide();
            $('#row_diferente').hide();
            $('#row_indice_reajuste').hide();
            $('#row_status').hide();
            $('#row_responsavel').show();
            $('#row_responsavel_tecnico').show();
            $('#row_servico').show();
            $('#row_status').hide();
            $('#row_plano').show();
            $('#row_realiza_cobranca').hide();
            $('#row_desconsidera_notificacao').hide();
            $('#row_tipo_cobranca').hide();
            $('#row_data_recarga').hide();
            $('#row_data_cadastro').hide();
        
        } else if (tipo_relatorio == 8) {
            $('#row_data').hide();
            $('#row_data_fim').hide();
            $('#row_contrato').show();
            $('#row_diferente').hide();
            $('#row_indice_reajuste').hide();
            $('#row_status').show();
            $('#row_responsavel').hide();
            $('#row_responsavel_tecnico').hide();
            $('#row_servico').hide();
            $('#row_status').hide();
            $('#row_plano').hide();
            $('#row_realiza_cobranca').hide();
            $('#row_desconsidera_notificacao').hide();
            $('#row_tipo_cobranca').hide();
            $('#row_data_recarga').show();
            $('#row_data_cadastro').show();
        
        }else {
                    
            $('#row_data').show();
            $('#row_data_fim').hide();
            $('#row_contrato').show();
            $('#row_indice_reajuste').show();
            $('#row_status').show();
            $('#row_responsavel').show();
            $('#row_responsavel_tecnico').show();
            $('#row_servico').show();
            $('#row_status').show();
            $('#row_plano').show();
            $('#row_data_recarga').hide();
            $('#row_data_cadastro').hide();

            if(servico == 'call_suporte'){
                $('#row_diferente').show();
            }else{
                $('#row_diferente').hide();
            }

            if (tipo_relatorio == 1 || tipo_relatorio == 4) {
                $('#row_indice_reajuste').show();
                $('#row_realiza_cobranca').show();
                $('#row_desconsidera_notificacao').show();
                $('#row_tipo_cobranca').show();
                $('#row_data_recarga').hide();
                $('#row_data_cadastro').hide();

            } else {
                $('#row_indice_reajuste').hide();
                $('#row_realiza_cobranca').hide();
                $('#row_desconsidera_notificacao').hide();
                $('#row_tipo_cobranca').hide();
                $('#row_data_recarga').hide();
                $('#row_data_cadastro').hide();
            }
        }

    });

    $('#servico').on('change',function(){
        var servico = $(this).val();
        var tipo_relatorio = $('#tipo_relatorio').val();
        if((servico == 'call_suporte') && tipo_relatorio != 3 ){
            $('#row_diferente').show();
        }else{
            $('#row_diferente').hide();
        }
    });

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

    $(document).on('submit', '#informacoes_form', function(){

        var id_contrato_plano_pessoa = $("#id_contrato_plano_pessoa").val();
        if(!id_contrato_plano_pessoa || id_contrato_plano_pessoa == 0){
            alert("Deve-se selecionar um contrato válido!");
            return false;
        }

        modalAguarde();
    });
</script>

<?php

function relatorio_cancelamentos($plano, $servico, $data_de, $data_ate, $id_responsavel, $id_responsavel_tecnico){

    $data_de_consulta = converteData($data_de);
    $data_ate_consulta = converteData($data_ate);

    $data_hoje = getDataHora();
	$data_hoje = converteDataHora($data_hoje);

    if ($plano) {
        $filtro_plano = "AND c.id_plano = '".$plano."'";
        $dados_plano = DBRead('', 'tb_plano',"WHERE id_plano = '".$plano."' ORDER BY nome");
        $legenda_plano = $dados_plano[0]['nome'];

    } else {
        $filtro_plano = "";
        $legenda_plano = 'Todos';
    }

    if ($servico) {
        $filtro_servico = "AND c.cod_servico = '".$servico."'";
        $dados_servico = DBRead('', 'tb_plano', "WHERE cod_servico = '".$servico."'", 'cod_servico');
        $legenda_servico = getNomeServico($dados_servico[0]['cod_servico']);
        
    } else {
        $filtro_servico = "";
        $legenda_servico = 'Todos';
    }

    if ($data_de && $data_ate) {
    	$filtro_data = " AND data_final_cobranca <= '".$data_ate_consulta."' AND data_final_cobranca >= '".$data_de_consulta."'";
    	$periodo_amostra = "<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> de ".$data_de." até ".$data_ate."</span>";

    } else if ($data_ate) {
    	$filtro_data = " AND data_final_cobranca <= '".$data_ate_consulta."'";
    	$periodo_amostra = "<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> até ".$data_ate."</span>";

    } else if ($data_de) {
    	$filtro_data = " AND data_final_cobranca >= '".$data_de_consulta."'";
    	$periodo_amostra = "<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> a partir de ".$data_de."</span>";

    } else {
    	$filtro_data = "";
    	$periodo_amostra = "<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> Todos</span>";	
    }

    if ($id_responsavel) {
        $filtro_responsavel = "AND a.id_responsavel = '".$id_responsavel."'";
        $dados_responsavel = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_usuario = '".$id_responsavel."' ","b.nome");
        $legenda_responsavel = $dados_responsavel[0]['nome'];

    } else {
        $filtro_responsavel = "";
        $legenda_responsavel = 'Qualquer';
    }

    if ($id_responsavel_tecnico) {
        $filtro_responsavel_tecnico = "AND a.id_responsavel_tecnico = '".$id_responsavel_tecnico."'";
        $dados_responsavel_tecnico = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_usuario = '".$id_responsavel_tecnico."' ","b.nome");
        $legenda_responsavel_tecnico = $dados_responsavel_tecnico[0]['nome'];

    } else {
        $filtro_responsavel_tecnico = "";
        $legenda_responsavel_tecnico = 'Qualquer';
    }
  
	$gerado = "<span style=\"font-size: 14px;\"><strong>Gerado em: </strong> $data_hoje</span>";

	echo "<div class=\"col-md-12\" style=\"padding: 0\">";
	echo "<legend style=\"text-align:center;\"><strong>Relatório de Cancelamentos</strong><br>$gerado</legend>";
    echo "<legend style=\"text-align:center;\">$periodo_amostra</legend>";
	echo "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong>Plano - </strong>".$legenda_plano.", <strong> Serviço - </strong>".$legenda_servico.", <strong> Responsável pelo Relacionamento - </strong>".$legenda_responsavel.", <strong> Responsável Técnico - </strong>".$legenda_responsavel_tecnico."";
	echo "</legend>";

	$data_de = converteData($data_de);
	$data_ate = converteData($data_ate);

	$dados  =  DBRead('','tb_contrato_plano_pessoa a ', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa INNER JOIN tb_plano c ON a.id_plano = c.id_plano WHERE a.id_contrato_plano_pessoa ".$filtro_plano." ".$filtro_servico." ".$filtro_data." ".$filtro_responsavel." ".$filtro_responsavel_tecnico." AND b.nome not like '%ZZ - Belluno%' ORDER BY b.nome ASC, c.cod_servico ASC, a.data_inicio_contrato DESC","a.*, b.nome AS 'nome_pessoa', b.razao_social, c.nome AS 'nome_plano', c.cod_servico, c.cor");
  
    if($dados){     

        echo '
        <table class="table table-hover dataTable" style="margin-bottom:0;">
            <thead>
                <tr>
                    <th>Contrato</th>
                    <th>Razão Social</th>
                    <th>Data Final da Cobrança</th>
                    <th>Plano</th>
                    <th>Tipo de Cobrança</th>
                    <th>Dia do Pagamento</th>
                    <th>Valor Contrato</th>
                    <th>Valor Inicial</th>
                    <th>Valor Unitário do Contrato (Via Telefone)</th>
                    <th>Valor Excedente do Contrato (Via Telefone)</th>
                    <th>Qtd Contratada (Via Telefone)</th>
                    <th>Valor Unitário do Contrato (Via Texto)</th>
                    <th>Valor Excedente do Contrato (Via Texto)</th>
                    <th>Qtd Contratada (Via Texto)</th>
                    <th>Qtd Clientes</th>
                    <th>Qtd Contratada (Clientes)</th>
                </tr>
            </thead>
            <tbody>
    ';               
    
    
    $contador_valor_contrato = 0;
    $contador_qtd_contrato = 0;
    $contador_qtd_contratada_texto = 0;

    foreach($dados as $dado_consulta){
        
        $dados_consulta_filho = DBRead('','tb_faturamento_contrato',"WHERE (contrato_pai = '0' OR contrato_pai IS NULL) AND id_faturamento = '".$dado_consulta['id_faturamento']."' ");

        //NOME DO CONTRATO
        if($dado_consulta['nome_contrato']){
            $nome_contrato = " (".$dado_consulta['nome_contrato'].") ";
        }else{
            $nome_contrato = '';
        }
        
        $texto_filho = '';
        
        if($dados_consulta_filho){
            //TEM FILHO
            foreach ($dados_consulta_filho as $conteudo_consulta_filho) {
                $dados_filho = DBRead('','tb_contrato_plano_pessoa a',"INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE id_contrato_plano_pessoa = '".$conteudo_consulta_filho['id_contrato_plano_pessoa']."' ");
                $texto_filho .= '<a tabindex="0" data-toggle="tooltip" title="'.$dados_filho[0]['nome'].'"> <i class="fa fa-question-circle"></i></a>';
            }
        }

        $contrato = $dado_consulta['nome_pessoa']." ".$nome_contrato." ".$texto_filho;

        if($dado_consulta['tipo_cobranca'] == 'mensal_desafogo'){
            $tipo_cobranca = "Mensal com Desafogo (".$dado_consulta['desafogo']."%)";
        }else if($dado_consulta['tipo_cobranca'] == 'unitario'){
            $tipo_cobranca = "Unitário";
        }else if($dado_consulta['tipo_cobranca'] == 'cliente_base'){
            $tipo_cobranca = "Clientes na Base";
        }else if($dado_consulta['tipo_cobranca'] == 'cliente_ativo'){
            $tipo_cobranca = "Clientes Ativos";
        }else if($dado_consulta['tipo_cobranca'] == 'x_cliente_base'){
            $tipo_cobranca = "Até X Clientes na Base";
        }else if($dado_consulta['tipo_cobranca'] == 'prepago'){
            $tipo_cobranca = "Pré-pago";
        }else{
            $tipo_cobranca = ucfirst($dado_consulta['tipo_cobranca']);
        }

           echo "<tr>";

            echo "<td style='vertical-align: middle;'>".$contrato."</td>";
            echo "<td style='vertical-align: middle;'>".$dado_consulta['razao_social']."</td>";
            echo "<td style='vertical-align: middle;'>".converteData($dado_consulta['data_final_cobranca'])."</td>";
            echo "<td style='vertical-align: middle;'>".$dado_consulta['nome_plano']."</td>";
            echo "<td style='vertical-align: middle;'>".$tipo_cobranca."</td>";
            echo "<td style='vertical-align: middle;'>".$dado_consulta['dia_pagamento']."</td>";
            echo "<td style='vertical-align: middle;' data-order='".$dado_consulta['valor_total']."'>R$ ".converteMoeda($dado_consulta['valor_total'],'moeda')."</td>";
            echo "<td style='vertical-align: middle;' data-order='".$dado_consulta['valor_inicial']."'>R$ ".converteMoeda($dado_consulta['valor_inicial'],'moeda')."</td>";
            echo "<td style='vertical-align: middle;' data-order='".$dado_consulta['valor_unitario']."'>R$ ".converteMoeda($dado_consulta['valor_unitario'],'moeda')."</td>";
            echo "<td style='vertical-align: middle;' data-order='".$dado_consulta['valor_excedente']."'>R$ ".converteMoeda($dado_consulta['valor_excedente'],'moeda')."</td>";
            echo "<td style='vertical-align: middle;'>".$dado_consulta['qtd_contratada']."</td>";
            echo "<td style='vertical-align: middle;'>R$ ".converteMoeda($dado_consulta['valor_unitario_texto'], 'moeda')."</td>";
            echo "<td style='vertical-align: middle;'>R$ ".converteMoeda($dado_consulta['valor_excedente_texto'], 'moeda')."</td>";
            echo "<td style='vertical-align: middle;'>".$dado_consulta['qtd_contratada_texto']."</td>";
            echo "<td style='vertical-align: middle;'>".$dado_consulta['qtd_clientes']."</td>";
            echo "<td style='vertical-align: middle;'>".$dado_consulta['qtd_clientes_teto']."</td>";
            
        echo "</tr>";
        $contador_qtd_contratada_texto = $dado_consulta['qtd_contratada_texto'] + $contador_qtd_contratada_texto;
        $contador_valor_contrato = $dado_consulta['valor_total'] + $contador_valor_contrato;
        $contador_qtd_contrato = $dado_consulta['qtd_contratada'] + $contador_qtd_contrato;
                        
    }

    echo '		
        </tbody>';
        echo "<tfoot>";
                echo '<tr>';
                    echo '<th style="vertical-align: middle;">Totais</th>';
                    echo '<th style="vertical-align: middle;"></th>';
                    echo '<th style="vertical-align: middle;"></th>';
                    echo '<th style="vertical-align: middle;"></th>';
                    echo '<th style="vertical-align: middle;"></th>';
                    echo '<th style="vertical-align: middle;"></th>';
                    echo '<th style="vertical-align: middle;">R$ '.converteMoeda($contador_valor_contrato,'moeda').'</th>';
                    echo '<th style="vertical-align: middle;"></th>';
                    echo '<th style="vertical-align: middle;"></th>';
                    echo '<th style="vertical-align: middle;"></th>';
                    echo '<th style="vertical-align: middle;">'.$contador_qtd_contrato.'</th>';
                    echo '<th style="vertical-align: middle;"></th>'; 
                    echo '<th style="vertical-align: middle;"></th>'; 
                    echo '<th style="vertical-align: middle;">'.$contador_qtd_contratada_texto.'</th>'; 
                    echo '<th style="vertical-align: middle;"></th>'; 
                    echo '<th style="vertical-align: middle;"></th>'; 
                echo '</tr>';
            echo "</tfoot> ";
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
                            filename: 'relatorio_cancelados',
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

function relatorio_contratos_tabela($status, $plano, $servico, $id_contrato_plano_pessoa, $data_de, $data_ate, $id_responsavel, $id_responsavel_tecnico, $diferente, $indice_reajuste, $realiza_cobranca, $tipo_cobranca, $desconsidera_notificacao){

    $data_de_consulta = converteData($data_de);
    $data_ate_consulta = converteData($data_ate);

    $data_hoje = getDataHora();
	$data_hoje = converteDataHora($data_hoje);

	if ($status && $status != 'todos') {
       	$filtro_status = "AND a.status = '".$status."'";
        $legenda_status = getNomeStatusPlano($status);

    } else if ($status == 'todos'){
    	$filtro_status = "";
        $legenda_status = 'Todos';

    } else {
       	$filtro_status = "AND a.status = '0'";
        $legenda_status = getNomeStatusPlano(0);
    }

    if ($plano) {
        $filtro_plano = "AND c.id_plano = '".$plano."'";
        $dados_plano = DBRead('', 'tb_plano',"WHERE id_plano = '".$plano."' ORDER BY nome");
        $legenda_plano = $dados_plano[0]['nome'];

    } else {
        $filtro_plano = "";
        $legenda_plano = 'Todos';
    }

    if ($servico) {
        $filtro_servico = "AND c.cod_servico = '".$servico."'";
        $dados_servico = DBRead('', 'tb_plano', "WHERE cod_servico = '".$servico."'", 'cod_servico');
        $legenda_servico = getNomeServico($dados_servico[0]['cod_servico']);

        if ($servico == 'call_suporte') {
            if ($diferente) {
                if ($diferente == 1) {
                    $filtro_diferente = "AND a.valor_diferente_texto = '1' ";
                    $legenda_diferente = "Apenas Diferentes";

                } else {
                    $filtro_diferente = "AND a.valor_diferente_texto != '1' ";
                    $legenda_diferente = "Não Mostrar Diferentes";
                }
            } else {
                $filtro_diferente = "";
                $legenda_diferente = 'Todos';
            }

            $legenda_completa_valor_diferente = "<strong>, Mostrar Contratos com Valores Diferentes para Texto e Voz - </strong>".$legenda_diferente;

        } else {
            $legenda_completa_valor_diferente = "";
        }
        
    } else {
        $filtro_servico = "";
        $legenda_servico = 'Todos';
    }

    if ($id_contrato_plano_pessoa) {
        $filtro_contrato_plano_pessoa = "AND a.id_contrato_plano_pessoa = '".$id_contrato_plano_pessoa."'";
        $dados_contrato  =  DBRead('','tb_contrato_plano_pessoa a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_contrato_plano_pessoa = '".$id_contrato_plano_pessoa."'", "b.nome");
        $legenda_contrato = $dados_contrato[0]['nome'];

    } else {
        $filtro_contrato_plano_pessoa = "";
        $legenda_contrato = 'Todos';
    }

    if ($data_de && $data_ate) {
    	$filtro_data = " AND data_inicio_contrato <= '".$data_ate_consulta."' AND data_inicio_contrato >= '".$data_de_consulta."'";
    	$periodo_amostra = "<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> de ".$data_de." até ".$data_ate."</span>";

    } else if ($data_ate) {
    	$filtro_data = " AND data_inicio_contrato <= '".$data_ate_consulta."'";
    	$periodo_amostra = "<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> até ".$data_ate."</span>";

    } else if ($data_de) {
    	$filtro_data = " AND data_inicio_contrato >= '".$data_de_consulta."'";
    	$periodo_amostra = "<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> a partir de ".$data_de."</span>";

    } else {
    	$filtro_data = "";
    	$periodo_amostra = "<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> Todos</span>";	
    }

    if ($id_responsavel) {
        $filtro_responsavel = "AND a.id_responsavel = '".$id_responsavel."'";
        $dados_responsavel = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_usuario = '".$id_responsavel."' ","b.nome");
        $legenda_responsavel = $dados_responsavel[0]['nome'];

    } else {
        $filtro_responsavel = "";
        $legenda_responsavel = 'Qualquer';
    }

    if ($id_responsavel_tecnico) {
        $filtro_responsavel_tecnico = "AND a.id_responsavel_tecnico = '".$id_responsavel_tecnico."'";
        $dados_responsavel_tecnico = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_usuario = '".$id_responsavel_tecnico."' ","b.nome");
        $legenda_responsavel_tecnico = $dados_responsavel_tecnico[0]['nome'];

    } else {
        $filtro_responsavel_tecnico = "";
        $legenda_responsavel_tecnico = 'Qualquer';
    }

    if ($indice_reajuste) {
        $filtro_indice_reajuste = "AND a.indice_reajuste = '".$indice_reajuste."'";

        if ($indice_reajuste == 'IPCA') {
            $legenda_indice_reajuste = '2% + IPCA';

        } else if ($indice_reajuste == 'ND') {
            $legenda_indice_reajuste = 'Não Definido';

        } else {
            $legenda_indice_reajuste = $indice_reajuste;
        } 

    } else {
        $filtro_indice_reajuste = "";
        $legenda_indice_reajuste = 'Qualquer';
    }

    if ($realiza_cobranca) {
        if ($realiza_cobranca == '1') {
            $legenda_realiza_cobranca = 'Sim';
            $filtro_realiza_cobranca = "AND a.realiza_cobranca = '1' ";

        } else if ($realiza_cobranca == '2'){
            $legenda_realiza_cobranca = 'Não'; 
            $filtro_realiza_cobranca = "AND a.realiza_cobranca = '0' ";
        }

    } else {
        $filtro_realiza_cobranca = "";
        $legenda_realiza_cobranca = 'Qualquer';
    }

    if ($desconsidera_notificacao) {
        if ($desconsidera_notificacao == '1') {
            $legenda_desconsidera_notificacao = 'Sim';
            $filtro_desconsidera_notificacao = "AND a.desconsidera_notificacao = '1' ";

        } else if ($desconsidera_notificacao == '2'){
            $legenda_desconsidera_notificacao = 'Não'; 
            $filtro_desconsidera_notificacao = "AND a.desconsidera_notificacao = '0' ";
        }

    } else {
        $filtro_desconsidera_notificacao = "";
        $legenda_desconsidera_notificacao = 'Qualquer';
    }

    if($tipo_cobranca){
        if($tipo_cobranca == 'x_cliente_base'){
            $legenda_tipo_cobranca = 'Até X Clientes na Base';
            $filtro_tipo_cobranca = "AND a.tipo_cobranca = 'x_cliente_base' ";
        }else if($tipo_cobranca == 'mensal'){
            $legenda_tipo_cobranca = 'Mensal'; 
            $filtro_tipo_cobranca = "AND a.tipo_cobranca = 'mensal' ";
        }else if($tipo_cobranca == 'mensal_desafogo'){
            $legenda_tipo_cobranca = 'Mensal com Desafogo'; 
            $filtro_tipo_cobranca = "AND a.tipo_cobranca = 'mensal_desafogo' ";
        }else if($tipo_cobranca == 'prepago'){
            $legenda_tipo_cobranca = 'Pré-Pago'; 
            $filtro_tipo_cobranca = "AND a.tipo_cobranca = 'prepago' ";
        }else if($tipo_cobranca == 'unitario'){
            $legenda_tipo_cobranca = 'Unitário'; 
            $filtro_tipo_cobranca = "AND a.tipo_cobranca = 'unitario' ";
        }
    }else{
        $filtro_tipo_cobranca = "";
        $legenda_tipo_cobranca = 'Qualquer';
    }

	$gerado = "<span style=\"font-size: 14px;\"><strong>Gerado em: </strong> $data_hoje</span>";

	echo "<div class=\"col-md-12\" style=\"padding: 0\">";
	echo "<legend style=\"text-align:center;\"><strong>Relatório de Contratos - Tabela</strong><br>$gerado</legend>";
    echo "<legend style=\"text-align:center;\">$periodo_amostra</legend>";
	echo "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong> Contrato - </strong>".$legenda_contrato.", <strong> Status - </strong>".$legenda_status.", <strong>Plano - </strong>".$legenda_plano.", <strong> Serviço - </strong>".$legenda_servico.", <strong> Responsável pelo Relacionamento - </strong>".$legenda_responsavel.", <strong> Responsável Técnico - </strong>".$legenda_responsavel_tecnico.", <strong> Índice de Reajuste - </strong>".$legenda_indice_reajuste.", <strong> Realiza Cobrança - </strong>".$legenda_realiza_cobranca.", <strong> Cobrar Menor Valor nas Notificações de Parada - </strong>".$legenda_desconsidera_notificacao.", <strong> Tipo de Cobrança - </strong>".$legenda_tipo_cobranca." ".$legenda_completa_valor_diferente." ";
	echo "</legend>";

	$data_de = converteData($data_de);
	$data_ate = converteData($data_ate);

	$dados  =  DBRead('','tb_contrato_plano_pessoa a ', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa INNER JOIN tb_plano c ON a.id_plano = c.id_plano WHERE a.id_contrato_plano_pessoa ".$filtro_plano." ".$filtro_servico." ".$filtro_contrato_plano_pessoa." ".$filtro_status." ".$filtro_data." ".$filtro_responsavel." ".$filtro_responsavel_tecnico." ".$filtro_diferente." ".$filtro_indice_reajuste." ".$filtro_realiza_cobranca."  ".$filtro_desconsidera_notificacao." ".$filtro_tipo_cobranca." AND b.nome not like '%ZZ - Belluno%' ORDER BY b.nome ASC, c.cod_servico ASC, a.data_inicio_contrato DESC","a.*, b.nome AS 'nome_pessoa', b.razao_social, c.nome AS 'nome_plano', c.cod_servico, c.cor");
  
    if($dados){     

        echo '
        <table class="table table-hover dataTable" style="margin-bottom:0;">
            <thead>
                <tr>
                    <th>Contrato</th>
                    <th>Razão Social</th>
                    <th>Plano</th>
                    <th>Tipo de Cobrança</th>
                    <th>Dia do Pagamento</th>
                    <th>Valor Contrato</th>
                    <th>Valor Inicial</th>
                    <th>Valor Unitário do Contrato (Via Telefone)</th>
                    <th>Valor Excedente do Contrato (Via Telefone)</th>
                    <th>Qtd Contratada (Via Telefone)</th>
                    <th>Valor Unitário do Contrato (Via Texto)</th>
                    <th>Valor Excedente do Contrato (Via Texto)</th>
                    <th>Qtd Contratada (Via Texto)</th>
                    <th>Qtd Clientes</th>
                    <th>Qtd Contratada (Clientes)</th>
                </tr>
            </thead>
            <tbody>
    ';               
    
    $contador_valor_contrato = 0;
    $contador_qtd_contrato = 0;
    $contador_qtd_contratada_texto = 0;

    foreach($dados as $dado_consulta){
        
        $dados_consulta_filho = DBRead('','tb_faturamento_contrato',"WHERE (contrato_pai = '0' OR contrato_pai IS NULL) AND id_faturamento = '".$dado_consulta['id_faturamento']."' ");

        //NOME DO CONTRATO
        if($dado_consulta['nome_contrato']){
            $nome_contrato = " (".$dado_consulta['nome_contrato'].") ";
        }else{
            $nome_contrato = '';
        }
        
        $texto_filho = '';
        
        if($dados_consulta_filho){
            //TEM FILHO
            foreach ($dados_consulta_filho as $conteudo_consulta_filho) {

                $dados_filho = DBRead('','tb_contrato_plano_pessoa a',"INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE id_contrato_plano_pessoa = '".$conteudo_consulta_filho['id_contrato_plano_pessoa']."' ");
                
                $texto_filho .= '<a tabindex="0" data-toggle="tooltip" title="'.$dados_filho[0]['nome'].'"> <i class="fa fa-question-circle"></i></a>';
            }
        }

        $contrato = $dado_consulta['nome_pessoa']." ".$nome_contrato." ".$texto_filho;

        if($dado_consulta['tipo_cobranca'] == 'mensal_desafogo'){
            $tipo_cobranca = "Mensal com Desafogo (".$dado_consulta['desafogo']."%)";
        }else if($dado_consulta['tipo_cobranca'] == 'unitario'){
            $tipo_cobranca = "Unitário";
        }else if($dado_consulta['tipo_cobranca'] == 'cliente_base'){
            $tipo_cobranca = "Clientes na Base";
        }else if($dado_consulta['tipo_cobranca'] == 'cliente_ativo'){
            $tipo_cobranca = "Clientes Ativos";
        }else if($dado_consulta['tipo_cobranca'] == 'x_cliente_base'){
            $tipo_cobranca = "Até X Clientes na Base";
        }else if($dado_consulta['tipo_cobranca'] == 'prepago'){
            $tipo_cobranca = "Pré-pago";
        }else{
            $tipo_cobranca = ucfirst($dado_consulta['tipo_cobranca']);
        }

           echo "<tr>";
            echo "<td style='vertical-align: middle;'>".$contrato."</td>";
            echo "<td style='vertical-align: middle;'>".$dado_consulta['razao_social']."</td>";
            echo "<td style='vertical-align: middle;'>".$dado_consulta['nome_plano']."</td>";
            echo "<td style='vertical-align: middle;'>".$tipo_cobranca."</td>";
            echo "<td style='vertical-align: middle;'>".$dado_consulta['dia_pagamento']."</td>";
            echo "<td style='vertical-align: middle;' data-order='".$dado_consulta['valor_total']."'>R$ ".converteMoeda($dado_consulta['valor_total'],'moeda')."</td>";
            echo "<td style='vertical-align: middle;' data-order='".$dado_consulta['valor_inicial']."'>R$ ".converteMoeda($dado_consulta['valor_inicial'],'moeda')."</td>";
            echo "<td style='vertical-align: middle;' data-order='".$dado_consulta['valor_unitario']."'>R$ ".converteMoeda($dado_consulta['valor_unitario'],'moeda')."</td>";
            echo "<td style='vertical-align: middle;' data-order='".$dado_consulta['valor_excedente']."'>R$ ".converteMoeda($dado_consulta['valor_excedente'],'moeda')."</td>";
            echo "<td style='vertical-align: middle;'>".$dado_consulta['qtd_contratada']."</td>";
            echo "<td style='vertical-align: middle;'>R$ ".converteMoeda($dado_consulta['valor_unitario_texto'], 'moeda')."</td>";
            echo "<td style='vertical-align: middle;'>R$ ".converteMoeda($dado_consulta['valor_excedente_texto'], 'moeda')."</td>";
            echo "<td style='vertical-align: middle;'>".$dado_consulta['qtd_contratada_texto']."</td>";
            echo "<td style='vertical-align: middle;'>".$dado_consulta['qtd_clientes']."</td>";
            echo "<td style='vertical-align: middle;'>".$dado_consulta['qtd_clientes_teto']."</td>";
        echo "</tr>";
        $contador_qtd_contratada_texto = $dado_consulta['qtd_contratada_texto'] + $contador_qtd_contratada_texto;
        $contador_valor_contrato = $dado_consulta['valor_total'] + $contador_valor_contrato;
        $contador_qtd_contrato = $dado_consulta['qtd_contratada'] + $contador_qtd_contrato;
    }

    echo '		
        </tbody>';
        echo "<tfoot>";
                echo '<tr>';
                    echo '<th style="vertical-align: middle;">Totais</th>';
                    echo '<th style="vertical-align: middle;"></th>';
                    echo '<th style="vertical-align: middle;"></th>';
                    echo '<th style="vertical-align: middle;"></th>';
                    echo '<th style="vertical-align: middle;"></th>';
                    echo '<th style="vertical-align: middle;">R$ '.converteMoeda($contador_valor_contrato,'moeda').'</th>';
                    echo '<th style="vertical-align: middle;"></th>';
                    echo '<th style="vertical-align: middle;"></th>';
                    echo '<th style="vertical-align: middle;"></th>';
                    echo '<th style="vertical-align: middle;">'.$contador_qtd_contrato.'</th>';
                    echo '<th style="vertical-align: middle;"></th>'; 
                    echo '<th style="vertical-align: middle;"></th>'; 
                    echo '<th style="vertical-align: middle;">'.$contador_qtd_contratada_texto.'</th>'; 
                    echo '<th style="vertical-align: middle;"></th>'; 
                    echo '<th style="vertical-align: middle;"></th>'; 
                echo '</tr>';
            echo "</tfoot> ";
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
                            filename: 'relatorio_contratos_tabela',
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

function relatorio_contratos($status, $plano, $servico, $id_contrato_plano_pessoa, $data_de, $data_ate, $id_responsavel, $id_responsavel_tecnico, $diferente, $indice_reajuste, $realiza_cobranca, $tipo_cobranca, $desconsidera_notificacao){

    $data_de_consulta = converteData($data_de);
    $data_ate_consulta = converteData($data_ate);

    $data_hoje = getDataHora();
	$data_hoje = converteDataHora($data_hoje);

	if($status && $status != 'todos'){
       	$filtro_status = "AND a.status = '".$status."'";
        $legenda_status = getNomeStatusPlano($status);
    }else if($status == 'todos'){
    	$filtro_status = "";
        $legenda_status = 'Todos';
    }else{
       	$filtro_status = "AND a.status = '0'";
        $legenda_status = getNomeStatusPlano(0);
    }
    if($plano){
        $filtro_plano = "AND c.id_plano = '".$plano."'";
        $dados_plano = DBRead('', 'tb_plano',"WHERE id_plano = '".$plano."' ORDER BY nome");
        $legenda_plano = $dados_plano[0]['nome'];
    }else{
        $filtro_plano = "";
        $legenda_plano = 'Todos';
    }
    if($servico){
        $filtro_servico = "AND c.cod_servico = '".$servico."'";
        $dados_servico = DBRead('', 'tb_plano', "WHERE cod_servico = '".$servico."'", 'cod_servico');
        $legenda_servico = getNomeServico($dados_servico[0]['cod_servico']);
        if($servico == 'call_suporte'){
            if($diferente){
                if($diferente == 1){
                    $filtro_diferente = "AND a.valor_diferente_texto = '1' ";
                    $legenda_diferente = "Apenas Diferentes";
                }else{
                    $filtro_diferente = "AND a.valor_diferente_texto != '1' ";
                    $legenda_diferente = "Não Mostrar Diferentes";
                }
            }else{
                $filtro_diferente = "";
                $legenda_diferente = 'Todos';
            }
            $legenda_completa_valor_diferente = "<strong>, Mostrar Contratos com Valores Diferentes para Texto e Voz - </strong>".$legenda_diferente;

        }else{
            $legenda_completa_valor_diferente = "";
        }
        
    }else{
        $filtro_servico = "";
        $legenda_servico = 'Todos';
    }
    if($id_contrato_plano_pessoa){
        $filtro_contrato_plano_pessoa = "AND a.id_contrato_plano_pessoa = '".$id_contrato_plano_pessoa."'";
        $dados_contrato  =  DBRead('','tb_contrato_plano_pessoa a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_contrato_plano_pessoa = '".$id_contrato_plano_pessoa."'", "b.nome");
        $legenda_contrato = $dados_contrato[0]['nome'];
    }else{
        $filtro_contrato_plano_pessoa = "";
        $legenda_contrato = 'Todos';
    }
    if($data_de && $data_ate){
    	$filtro_data = " AND data_inicio_contrato <= '".$data_ate_consulta."' AND data_inicio_contrato >= '".$data_de_consulta."'";
    	$periodo_amostra = "<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> de ".$data_de." até ".$data_ate."</span>";
    }else if($data_ate){
    	$filtro_data = " AND data_inicio_contrato <= '".$data_ate_consulta."'";
    	$periodo_amostra = "<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> até ".$data_ate."</span>";
    }else if($data_de){
    	$filtro_data = " AND data_inicio_contrato >= '".$data_de_consulta."'";
    	$periodo_amostra = "<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> a partir de ".$data_de."</span>";
    }else{
    	$filtro_data = "";
    	$periodo_amostra = "<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> Todos</span>";	
    }

    if($id_responsavel){
        $filtro_responsavel = "AND a.id_responsavel = '".$id_responsavel."'";
        $dados_responsavel = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_usuario = '".$id_responsavel."' ","b.nome");
        $legenda_responsavel = $dados_responsavel[0]['nome'];
    }else{
        $filtro_responsavel = "";
        $legenda_responsavel = 'Qualquer';
    }

    if($id_responsavel_tecnico){
        $filtro_responsavel_tecnico = "AND a.id_responsavel_tecnico = '".$id_responsavel_tecnico."'";
        $dados_responsavel_tecnico = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_usuario = '".$id_responsavel_tecnico."' ","b.nome");
        $legenda_responsavel_tecnico = $dados_responsavel_tecnico[0]['nome'];
    }else{
        $filtro_responsavel_tecnico = "";
        $legenda_responsavel_tecnico = 'Qualquer';
    }

    if($indice_reajuste){
        $filtro_indice_reajuste = "AND a.indice_reajuste = '".$indice_reajuste."'";
        if($indice_reajuste == 'IPCA'){
            $legenda_indice_reajuste = '2% + IPCA';
        }else if($indice_reajuste == 'ND'){
            $legenda_indice_reajuste = 'Não Definido'; 
        }else{
            $legenda_indice_reajuste = $indice_reajuste;
        }
    }else{
        $filtro_indice_reajuste = "";
        $legenda_indice_reajuste = 'Qualquer';
    }

    if($realiza_cobranca){
        if($realiza_cobranca == '1'){
            $legenda_realiza_cobranca = 'Sim';
            $filtro_realiza_cobranca = "AND a.realiza_cobranca = '1' ";
        }else if($realiza_cobranca == '2'){
            $legenda_realiza_cobranca = 'Não'; 
            $filtro_realiza_cobranca = "AND a.realiza_cobranca = '0' ";
        }
    }else{
        $filtro_realiza_cobranca = "";
        $legenda_realiza_cobranca = 'Qualquer';
    }

    if($desconsidera_notificacao){
        if($desconsidera_notificacao == '1'){
            $legenda_desconsidera_notificacao = 'Sim';
            $filtro_desconsidera_notificacao = "AND a.desconsidera_notificacao = '1' ";
        }else if($desconsidera_notificacao == '2'){
            $legenda_desconsidera_notificacao = 'Não'; 
            $filtro_desconsidera_notificacao = "AND a.desconsidera_notificacao = '0' ";
        }
    }else{
        $filtro_desconsidera_notificacao = "";
        $legenda_desconsidera_notificacao = 'Qualquer';
    }

    if($tipo_cobranca){
        if($tipo_cobranca == 'x_cliente_base'){
            $legenda_tipo_cobranca = 'Até X Clientes na Base';
            $filtro_tipo_cobranca = "AND a.tipo_cobranca = 'x_cliente_base' ";
        }else if($tipo_cobranca == 'mensal'){
            $legenda_tipo_cobranca = 'Mensal'; 
            $filtro_tipo_cobranca = "AND a.tipo_cobranca = 'mensal' ";
        }else if($tipo_cobranca == 'mensal_desafogo'){
            $legenda_tipo_cobranca = 'Mensal com Desafogo'; 
            $filtro_tipo_cobranca = "AND a.tipo_cobranca = 'mensal_desafogo' ";
        }else if($tipo_cobranca == 'prepago'){
            $legenda_tipo_cobranca = 'Pré-Pago'; 
            $filtro_tipo_cobranca = "AND a.tipo_cobranca = 'prepago' ";
        }else if($tipo_cobranca == 'unitario'){
            $legenda_tipo_cobranca = 'Unitário'; 
            $filtro_tipo_cobranca = "AND a.tipo_cobranca = 'unitario' ";
        }
    }else{
        $filtro_tipo_cobranca = "";
        $legenda_tipo_cobranca = 'Qualquer';
    }

	$gerado = "<span style=\"font-size: 14px;\"><strong>Gerado em: </strong> $data_hoje</span>";

	echo "<div class=\"col-md-10 col-md-offset-1\" style=\"padding: 0\">";
	echo "<legend style=\"text-align:center;\"><strong>Relatório de Contratos</strong><br>$gerado</legend>";
    echo "<legend style=\"text-align:center;\">$periodo_amostra</legend>";
	echo "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong> Contrato - </strong>".$legenda_contrato.", <strong> Status - </strong>".$legenda_status.", <strong>Plano - </strong>".$legenda_plano.", <strong> Serviço - </strong>".$legenda_servico.", <strong> Responsável pelo Relacionamento - </strong>".$legenda_responsavel.", <strong> Responsável Técnico - </strong>".$legenda_responsavel_tecnico.", <strong> Índice de Reajuste - </strong>".$legenda_indice_reajuste.", <strong> Realiza Cobrança - </strong>".$legenda_realiza_cobranca.", <strong> Cobrar Menor Valor nas Notificações de Parada - </strong>".$legenda_desconsidera_notificacao.", <strong> Tipo de Cobrança - </strong>".$legenda_tipo_cobranca." ".$legenda_completa_valor_diferente." ";
	echo "</legend>";

	$data_de = converteData($data_de);
	$data_ate = converteData($data_ate);

	$dados  =  DBRead('','tb_contrato_plano_pessoa a ', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa INNER JOIN tb_plano c ON a.id_plano = c.id_plano WHERE a.id_contrato_plano_pessoa ".$filtro_plano." ".$filtro_servico." ".$filtro_contrato_plano_pessoa." ".$filtro_status." ".$filtro_data." ".$filtro_responsavel." ".$filtro_responsavel_tecnico." ".$filtro_diferente." ".$filtro_indice_reajuste." ".$filtro_realiza_cobranca."  ".$filtro_desconsidera_notificacao." ".$filtro_tipo_cobranca." ORDER BY b.nome ASC, c.cod_servico ASC, a.data_inicio_contrato DESC","a.*, b.nome AS 'nome_pessoa', b.razao_social, c.nome AS 'nome_plano', c.cod_servico, c.cor");

    if($dados){     
        echo "<p class='text-center'><strong>Total de resultados: </strong>".sizeof($dados)."</p>";
        foreach ($dados as $conteudo) { 

            $id = $conteudo['id_contrato_plano_pessoa'];
	        $nome_pessoa = $conteudo['nome_pessoa'];
	        $razao_social = $conteudo['razao_social'];
	        $nome_plano = $conteudo['nome_plano'];
	        $cod_servico = $conteudo['cod_servico'];
	        $servico = getNomeServico($cod_servico);
	        $data_inicio_contrato = converteData($conteudo['data_inicio_contrato']);

	        if($conteudo['status'] == '' || $conteudo['status'] == '0'){
	        	$conteudo['status'] == '0';
	        }
	        $status = getNomeStatusPlano($conteudo['status']).' desde: '.converteData($conteudo['data_status']);
	        $nome_contrato = $conteudo['nome_contrato'];
                
            $dados_contrato_filho  =  DBRead('','tb_contrato_plano_pessoa a', "INNER JOIN tb_pessoa b On a.id_pessoa = b.id_pessoa WHERE a.contrato_pai = '".$id."' ");

            if($dados_contrato_filho){
                $marcador_filho = '*';
            }else{
                $marcador_filho = '';
            }

            echo '
            <div class="panel panel-primary">
                <div class="panel-heading clearfix">
                    <div class="row">
                        <h3 class="panel-title text-left col-md-6"><strong>Cliente: </strong>'.$nome_pessoa.' - '.$razao_social.' '.$marcador_filho.'</h3>
                        <h3 class="panel-title text-right col-md-6"><strong># </strong>'.$id.'</h3>
                    </div>
                </div>
            <div>';
           
            echo '<div class="panel-body painel-body">';                        
                echo '<div class="row">'; 
                    echo '<div class="col-md-3">';
                        echo '<span><strong>Plano: </strong>'.getNomeServico($conteudo['cod_servico']).' - '.$conteudo['nome_plano'].'</span>';
                    echo "</div>";
                    echo '<div class="col-md-3">';
                    if($nome_contrato){
                        echo '<span><strong>Nome do Contrato: </strong>'.$nome_contrato.'</span>';
                    }else{
                        echo '<span><strong>Nome do Contrato: </strong>N/D</span>';
                    }
                    echo "</div>";
                    echo '<div class="col-md-3">';
                        echo '<span><strong>Última Atualização: </strong>'.converteDataHora($conteudo['data_atualizacao']).'</span>';
                    echo "</div>";                            
                    echo '<div class="col-md-3">';
                        echo '<span><strong>Status: </strong>'.$status.'</span>';
                    echo "</div>";
                echo '</div>';
                echo '<div class="row">';
                    echo '<div class="col-md-3">';
                        echo '<span><strong>Qtd. Contratada: </strong>'.$conteudo['qtd_contratada'].'</span>';
                    echo "</div>";

                    if($conteudo['tipo_cobranca'] == 'x_cliente_base' && $conteudo['cod_servico'] == 'call_suporte'){
                        echo '<div class="col-md-3">';
                            echo '<span><strong>Qtd. Contratada (Clientes): </strong>'.$conteudo['qtd_clientes_teto'].'</span>';
                        echo "</div>";
                    }
                    
                    if($conteudo['valor_diferente_texto'] == 1){
                        echo '<div class="col-md-3">';
                            echo '<span><strong>Qtd. Contratada do Texto: </strong>'.$conteudo['qtd_contratada_texto'].'</span>';
                        echo "</div>";
                    }

                    echo '<div class="col-md-3">';
                        echo '<span><strong>Valor Unitário: </strong>R$ '.converteMoeda($conteudo['valor_unitario']).'</span>';
                    echo "</div>";
                    
                    if($conteudo['valor_diferente_texto'] == 1){
                        echo '<div class="col-md-3">';
                            echo '<span><strong>Valor Unitário do Texto: </strong>R$ '.converteMoeda($conteudo['valor_unitario_texto']).'</span>';
                        echo "</div>";
                    }

                    echo '<div class="col-md-3">';
                        echo '<span><strong>Valor Total: </strong>R$ '.converteMoeda($conteudo['valor_total']).'</span>';
                    echo "</div>";
                    echo '<div class="col-md-3"';
                        echo '<span><strong>Valor Inicial: </strong>R$ '.converteMoeda($conteudo['valor_inicial']).'</span>';
                    echo '</div>';
                echo '</div>';
                echo '<div class="row">';
                    echo '<div class="col-md-3">';
                        echo '<span><strong>Valor Excedente (Unt): </strong>R$ '.converteMoeda($conteudo['valor_excedente']).'</span>';
                    echo "</div>";
                   
                    if($conteudo['valor_diferente_texto'] == 1){
                        echo '<div class="col-md-3">';
                            echo '<span><strong>Valor Excedente do Texto (Unt): </strong>R$ '.converteMoeda($conteudo['valor_excedente_texto']).'</span>';
                        echo "</div>";
                    }
                    if($cod_servico == 'gestao_redes'){
                        echo '<div class="col-md-3">';
                            echo '<span><strong>Valor Plantão (Unt): </strong>R$ '.converteMoeda($conteudo['valor_plantao']).'</span>';
                        echo "</div>";

                        if($conteudo['tipo_plantao'] == '0'){
                            $tipo_plantao = "N/D";
                        }else{
                            if($conteudo['tipo_plantao'] == '1'){
                                $tipo_plantao = "30 em 30";
                            }else if($conteudo['tipo_plantao'] == '2'){
                                $tipo_plantao = "60 em 60";
                            }else if($conteudo['tipo_plantao'] == '3'){
                                $tipo_plantao = "60 em 60 proporcional";
                            }else{
                                $tipo_plantao = "Isento";
                            }
                        }

                        echo '<div class="col-md-3">';
                            echo '<span><strong>Tipo de Plantão: </strong>'.$tipo_plantao.'</span>';
                        echo "</div>";
                    }
                    
                    if($conteudo['indice_reajuste'] == 'IPCA'){
                        $exibe_indice_reajuste = '2% + IPCA';
                    }else if($conteudo['indice_reajuste'] == 'ND'){
                        $exibe_indice_reajuste = 'Não Definido';
                    }else{
                        $exibe_indice_reajuste = $conteudo['indice_reajuste'];
                    }

                    echo '<div class="col-md-3">';
                        echo '<span><strong>Índice de Reajuste: </strong>'.$exibe_indice_reajuste.'</span>';
                    echo "</div>";
                    echo '<div class="col-md-3">';
                        echo '<span><strong>Data do Próximo Reajuste: </strong>'.converteData($conteudo['data_ajuste']).'</span>';
                    echo "</div>";

                    if($conteudo['tipo_cobranca'] == 'mensal_desafogo'){
                        $tipo_cobranca = "Mensal com Desafogo";
                    }else if($conteudo['tipo_cobranca'] == 'cliente_base'){
                        $tipo_cobranca = "Clientes na Base";
                    }else if($conteudo['tipo_cobranca'] == 'cliente_ativo'){
                        $tipo_cobranca = "Clientes Ativos";
                    }else if($conteudo['tipo_cobranca'] == 'x_cliente_base'){
                        $tipo_cobranca = "Até X Clientes na Base";
                    }else if($conteudo['tipo_cobranca'] == 'prepago'){
                        $tipo_cobranca = "Pré-pago";
                    }else{
                        $tipo_cobranca = ucfirst($conteudo['tipo_cobranca']);
                    }
                    echo '<div class="col-md-3">';
                        echo '<span><strong>Tipo de Cobrança: </strong>'.$tipo_cobranca.'</span>';
                    echo '</div>';

                    if($conteudo['tipo_cobranca'] == 'mensal_desafogo'){
                        echo '<div class="col-md-3">';
                            echo '<span><strong>Desafogo: </strong>'.$conteudo['desafogo'].'%</span>';
                        echo '</div>';

                        if($conteudo['valor_diferente_texto'] == 1){
                            echo '<div class="col-md-3">';
                                echo '<span><strong>Desafogo do Texto: </strong>'.$conteudo['desafogo_texto'].'%</span>';
                            echo '</div>';
                        }
                    }

                echo '</div>';
                echo '<div class="row">';
                    echo '<div class="col-md-3">';
                        echo '<span><strong>Dia de Pagamento: </strong>'.$conteudo['dia_pagamento'].'</span>';
                    echo "</div>";
                    echo '<div class="col-md-3">';
                        echo '<span><strong>Início: </strong>'.converteData($conteudo['data_inicio_contrato']).'</span>';
                    echo "</div>";
                    echo '<div class="col-md-3">';
                        echo '<span><strong>Período de Contrato: </strong>'.$conteudo['periodo_contrato'].' meses</span>';
                    echo "</div>";
                echo '</div>';

                    if($conteudo['cod_servico'] == 'call_suporte'){
                        echo '<div class="row">';
                        if($conteudo['remove_duplicados'] == 1){
                            echo '<div class="col-md-3">';
                                echo '<span><strong>Remove Duplicados: </strong>Sim</span>';
                            echo "</div>";
                        }else{
                            echo '<div class="col-md-3">';
                                echo '<span><strong>Remove Duplicados: </strong>Não</span>';
                            echo "</div>";
                        }

                        if($conteudo['realiza_cobranca'] == 1){
                            echo '<div class="col-md-3">';
                                echo '<span><strong>Realiza Cobrança: </strong>Sim</span>';
                            echo "</div>";
                        }else{
                            echo '<div class="col-md-3">';
                                echo '<span><strong>Realiza Cobrança: </strong>Não</span>';
                            echo "</div>";
                        }

                        if($conteudo['recebe_ligacoes'] == 1){
                            echo '<div class="col-md-3">';
                                echo '<span><strong>Recebe Ligações: </strong>Sim</span>';
                            echo "</div>";
                        }else{
                            echo '<div class="col-md-3">';
                                echo '<span><strong>Recebe Ligações: </strong>Não</span>';
                            echo "</div>";
                        }

                        if($conteudo['desconsidera_notificacao'] == 1){
                            echo '<div class="col-md-3">';
                                echo '<span><strong>Cobrar Menor Valor nas Notificações de Parada: </strong>Sim</span>';
                            echo "</div>";
                            echo '<div class="col-md-3">';
                                echo '<span><strong>Valor: </strong>R$ '.converteMoeda($conteudo['valor_desconsidera_notificacao']).'</span>';
                            echo "</div>";
                        }else{
                            echo '<div class="col-md-3">';
                                echo '<span><strong>Cobrar Menor Valor nas Notificações de Parada: </strong>Não</span>';
                            echo "</div>";
                        }

                        echo '<div class="col-md-3">';
                                echo '<span><strong>Quantidade de Clientes: </strong>'.$conteudo['qtd_clientes'].'</span>';
                            echo "</div>";
                        echo '</div>';
                    }                    

                if($conteudo['obs']){
                    echo '<div class="row">';
                        echo '<div class="col-md-12">';
                            echo '<span><strong>Obs: </strong>'.$conteudo['obs'].'</span>';
                        echo "</div>";
                    echo '</div>';
                }      

                $dados_contrato_filho = DBRead('', 'tb_contrato_plano_pessoa a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_contrato_plano_pessoa = '".$conteudo['contrato_pai']."' ");
                $dados_contrato_separar = DBRead('', 'tb_contrato_plano_pessoa a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_contrato_plano_pessoa = '".$conteudo['separar_contrato']."' ");

                if($dados_contrato_filho || $dados_contrato_separar){
                    echo '<div class="row">';

                    if($dados_contrato_filho){
                        echo '<div class="col-md-3">';
                            echo "<strong>Contrato Vinculado:</strong> ".$dados_contrato_filho[0]['nome'];
                        echo '</div>';
                    }

                    if($dados_contrato_separar){
                        echo '<div class="col-md-3">';
                            echo "<strong>Contrato Separado com:</strong> ".$dados_contrato_separar[0]['nome'];
                        echo '</div>';
                    }
                    echo '</div>';
                }
                                
                $dados_responsavel = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_usuario = '".$conteudo['id_responsavel']."' ","b.nome");
                $nome_responsavel = $dados_responsavel[0]['nome'];

                $dados_responsavel_tecnico = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_usuario = '".$conteudo['id_responsavel_tecnico']."' ","b.nome");
                $nome_responsavel_tecnico = $dados_responsavel_tecnico[0]['nome'];
               
                echo '<div class="row">';
                    echo '<div class="col-md-3">';
                        echo '<span><strong>Responsável pelo Relacionamento: </strong>'.$nome_responsavel.'</span>';
                    echo "</div>";
                    echo '<div class="col-md-3">';
                        echo '<span><strong>Responsável Técnico: </strong>'.$nome_responsavel_tecnico.'</span>';
                    echo "</div>";
                    if($conteudo['email_nf']){
                        echo '<div class="col-md-6">';
                            echo '<span><strong>E-mail NFS-e: </strong>'.$conteudo['email_nf'].'</span>';
                        echo "</div>";
                    }
                    
                echo '</div>';

                echo '<div class="row">';
                    echo '<div class="col-md-3">';
                        echo '<span><strong>Data Inicial da Cobrança: </strong>'.converteData($conteudo['data_inicial_cobranca']).'</span>';
                    echo "</div>";
                    echo '<div class="col-md-3">';
                        echo '<span><strong>Data Final da Cobrança: </strong>'.converteData($conteudo['data_final_cobranca']).'</span>';
                    echo "</div>";
                    echo '<div class="col-md-3">';
                        echo '<span><strong>Tempo de Fidelidade (meses): </strong>'.$conteudo['tempo_fidelidade'].'</span>';
                    echo "</div>";
                echo '</div>';
            echo '</div>';
        echo '</div>';            
    echo '</div>';   
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
}

function relatorio_quantidade_clientes($status, $plano, $servico, $id_contrato_plano_pessoa, $data_de, $data_ate, $id_responsavel, $id_responsavel_tecnico, $diferente){

    $data_de_consulta = converteData($data_de);
    $data_ate_consulta = converteData($data_ate);

    $data_hoje = getDataHora();
    $data_hoje = converteDataHora($data_hoje);

    if($status && $status != 'todos'){
        $filtro_status = "AND a.status = '".$status."'";
        $legenda_status = getNomeStatusPlano($status);
    }else if($status == 'todos'){
        $filtro_status = "";
        $legenda_status = 'Todos';
    }else{
        $filtro_status = "AND a.status = '0'";
        $legenda_status = getNomeStatusPlano(0);
    }
    if($plano){
        $filtro_plano = "AND c.id_plano = '".$plano."'";
        $dados_plano = DBRead('', 'tb_plano',"WHERE id_plano = '".$plano."' ORDER BY nome");
        $legenda_plano = $dados_plano[0]['nome'];
    }else{
        $filtro_plano = "";
        $legenda_plano = 'Todos';
    }
    if($servico){
        $filtro_servico = "AND c.cod_servico = '".$servico."'";
        $dados_servico = DBRead('', 'tb_plano', "WHERE cod_servico = '".$servico."'", 'cod_servico');
        $legenda_servico = getNomeServico($dados_servico[0]['cod_servico']);
       
        if($servico == 'call_suporte'){
            if($diferente){
                if($diferente == 1){
                    $filtro_diferente = "AND a.valor_diferente_texto = '1' ";
                    $legenda_diferente = "Apenas Diferentes";
                }else{
                    $filtro_diferente = "AND a.valor_diferente_texto != '1' ";
                    $legenda_diferente = "Não Mostrar Diferentes";
                }
            }else{
                $filtro_diferente = "";
                $legenda_diferente = 'Todos';
            }
            $legenda_completa_valor_diferente = "<strong>, Mostrar Contratos com Valores Diferentes para Texto e Voz - </strong>".$legenda_diferente;

        }else{
            $legenda_completa_valor_diferente = "";
        }
       
    }else{
        $filtro_servico = "";
        $legenda_servico = 'Todos';
    }
    if($id_contrato_plano_pessoa){
        $filtro_contrato_plano_pessoa = "AND a.id_contrato_plano_pessoa = '".$id_contrato_plano_pessoa."'";
        $dados_contrato  =  DBRead('','tb_contrato_plano_pessoa a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_contrato_plano_pessoa = '".$id_contrato_plano_pessoa."'", "b.nome");
        $legenda_contrato = $dados_contrato[0]['nome'];
    }else{
        $filtro_contrato_plano_pessoa = "";
        $legenda_contrato = 'Todos';
    }
    if($data_de && $data_ate){
        $filtro_data = " AND data_inicio_contrato <= '".$data_ate_consulta."' AND data_inicio_contrato >= '".$data_de_consulta."'";
        $periodo_amostra = "<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> de ".$data_de." até ".$data_ate."</span>";
    }else if($data_ate){
        $filtro_data = " AND data_inicio_contrato <= '".$data_ate_consulta."'";
        $periodo_amostra = "<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> até ".$data_ate."</span>";
    }else if($data_de){
        $filtro_data = " AND data_inicio_contrato >= '".$data_de_consulta."'";
        $periodo_amostra = "<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> a partir de ".$data_de."</span>";
    }else{
        $filtro_data = "";
        $periodo_amostra = "<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> Todos</span>";    
    }

    if($id_responsavel){
        $filtro_responsavel = "AND a.id_responsavel = '".$id_responsavel."'";
        $dados_responsavel = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_usuario = '".$id_responsavel."' ","b.nome");
        $legenda_responsavel = $dados_responsavel[0]['nome'];
    }else{
        $filtro_responsavel = "";
        $legenda_responsavel = 'Qualquer';
    }

    if($id_responsavel_tecnico){
        $filtro_responsavel_tecnico = "AND a.id_responsavel_tecnico = '".$id_responsavel_tecnico."'";
        $dados_responsavel_tecnico = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_usuario = '".$id_responsavel_tecnico."' ","b.nome");
        $legenda_responsavel_tecnico = $dados_responsavel_tecnico[0]['nome'];
    }else{
        $filtro_responsavel_tecnico = "";
        $legenda_responsavel_tecnico = 'Qualquer';
    }

    $gerado = "<span style=\"font-size: 14px;\"><strong>Gerado em: </strong> $data_hoje</span>";

    echo "<div class=\"col-md-12\" style=\"padding: 0\">";
    echo "<legend style=\"text-align:center;\"><strong>Relatório de Quantidade de Clientes</strong><br>$gerado</legend>";
    echo "<legend style=\"text-align:center;\">$periodo_amostra</legend>";
    echo "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong> Contrato - </strong>".$legenda_contrato.", <strong> Status - </strong>".$legenda_status.", <strong>Plano - </strong>".$legenda_plano.", <strong> Serviço - </strong>".$legenda_servico.", <strong> Responsável pelo Relacionamento - </strong>".$legenda_responsavel.", <strong> Responsável Técnico - </strong>".$legenda_responsavel_tecnico."".$legenda_completa_valor_diferente." ".$legenda_diferente."";
    echo "</legend>";

    $data_de = converteData($data_de);
    $data_ate = converteData($data_ate);

    $dados  =  DBRead('','tb_contrato_plano_pessoa a ', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa INNER JOIN tb_plano c ON a.id_plano = c.id_plano WHERE a.id_contrato_plano_pessoa ".$filtro_plano." ".$filtro_servico." ".$filtro_contrato_plano_pessoa." ".$filtro_status." ".$filtro_data." ".$filtro_responsavel." ".$filtro_responsavel_tecnico." ".$filtro_diferente." ORDER BY b.nome ASC, c.cod_servico ASC, a.data_inicio_contrato DESC","a.*, b.nome AS 'nome_pessoa', b.razao_social, c.nome AS 'nome_plano', c.cod_servico, c.cor");

    if($dados){  
        echo "<p class='text-center'><strong>Total de resultados: </strong>".sizeof($dados)."</p>";
        echo '
            <table class="table table-hover dataTable" style="margin-bottom:0;">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Contrato</th>
                        <th>Serviço</th>
                        <th>Plano</th>
                        <th>Qtd de Clientes</th>
                        <th>Responsável pelo Relacionamento</th>
                        <th>Responsável Técnico</th>
                    </tr>
                </thead>
                <tbody>
        ';          

        foreach ($dados as $conteudo) { 

            $id = $conteudo['id_contrato_plano_pessoa'];
            $nome_pessoa = $conteudo['nome_pessoa'];
            if($conteudo['nome_contrato']){
                $nome_contrato = ' ('.$conteudo['nome_contrato'].')';
            }else{
                $nome_contrato = '';
            }
            $nome_plano = $conteudo['nome_plano'];
            $cod_servico = $conteudo['cod_servico'];
            $servico = getNomeServico($cod_servico);

            $dados_contrato_filho  =  DBRead('','tb_contrato_plano_pessoa a', "INNER JOIN tb_pessoa b On a.id_pessoa = b.id_pessoa WHERE a.contrato_pai = '".$id."' ");

            if($dados_contrato_filho){
                $marcador_filho = '<strong>*</strong>';
            }else{
                $marcador_filho = '';
            }
            
            $dados_responsavel = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_usuario = '".$conteudo['id_responsavel']."' ","b.nome");
            $dados_responsavel_tecnico = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_usuario = '".$conteudo['id_responsavel_tecnico']."' ","b.nome");
            
            echo '<tr>
                    <td class="text-left">'.$id.'</td>                
                    <td class="text-left">'.$nome_pessoa.''.$nome_contrato.' '.$marcador_filho.'</td>                
                    <td class="text-left">'.$servico.'</td>                
                    <td class="text-left">'.$nome_plano.'</td>
                    <td class="text-left">'.$conteudo['qtd_clientes'].'</td>
                    <td class="text-left">'.$dados_responsavel[0]['nome'].'</td>
                    <td class="text-left">'.$dados_responsavel_tecnico[0]['nome'].'</td>
                </tr>';     
        }
        echo '
            </tbody>
        </table>';
        
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
                var table = $('.dataTable').DataTable({
                    \"language\": {
                        \"url\": \"//cdn.datatables.net/plug-ins/1.10.19/i18n/Portuguese-Brasil.json\"
                    },
                    columnDefs: [
                        { type: 'num', targets: 0 },
                        { type: 'chinese-string', targets: 1 },
                    ],   
                    \"order\": [[ 1, \"asc\" ]],
                    \"searching\": false,
                    \"paging\":   false,
                    \"info\":     false
                });

                var buttons = new $.fn.dataTable.Buttons(table, {
                    buttons: [
                        {
                            extend: 'excelHtml5',
                            text: '<i class=\"fa fa-file-excel-o\" aria-hidden=\"true\"></i> Exportar',
                            filename: 'relatorio_contratos_quantidade',
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
}

function relatorio_encarteiramento_clientes($status, $plano, $servico, $id_responsavel, $id_responsavel_tecnico){

    $data_hoje = getDataHora();
    $data_hoje = converteDataHora($data_hoje);

    if ($status && $status != 'todos') {
        $filtro_status = "AND a.status = '".$status."'";
        $legenda_status = getNomeStatusPlano($status);

    } else if ($status == 'todos') {
        $filtro_status = "";
        $legenda_status = 'Todos';

    } else {
        $filtro_status = "AND a.status = '0'";
        $legenda_status = getNomeStatusPlano(0);
    }

    if ($plano) {
        $filtro_plano = "AND c.id_plano = '".$plano."'";
        $dados_plano = DBRead('', 'tb_plano',"WHERE id_plano = '".$plano."' ORDER BY nome");
        $legenda_plano = $dados_plano[0]['nome'];

    } else {
        $filtro_plano = "";
        $legenda_plano = 'Todos';
    }

    if ($servico) {
        $filtro_servico = "AND c.cod_servico = '".$servico."'";
        $dados_servico = DBRead('', 'tb_plano', "WHERE cod_servico = '".$servico."'", 'cod_servico');
        $legenda_servico = getNomeServico($dados_servico[0]['cod_servico']);

    } else {
        $filtro_servico = "";
        $legenda_servico = 'Todos';
    }

    if ($id_responsavel) {
        $filtro_responsavel = "AND a.id_responsavel = '".$id_responsavel."'";
        $dados_responsavel = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_usuario = '".$id_responsavel."' ","b.nome");
        $legenda_responsavel = $dados_responsavel[0]['nome'];

    } else {
        $filtro_responsavel = "";
        $legenda_responsavel = 'Qualquer';
    }

    if ($id_responsavel_tecnico) {
        $filtro_responsavel_tecnico = "AND a.id_responsavel_tecnico = '".$id_responsavel_tecnico."'";
        $dados_responsavel_tecnico = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_usuario = '".$id_responsavel_tecnico."' ","b.nome");
        $legenda_responsavel_tecnico = $dados_responsavel_tecnico[0]['nome'];

    } else {
        $filtro_responsavel_tecnico = "";
        $legenda_responsavel_tecnico = 'Qualquer';
    }

    $gerado = "<span style=\"font-size: 14px;\"><strong>Gerado em: </strong> $data_hoje</span>";

    echo "<div class=\"col-md-12\" style=\"padding: 0\">";
    echo "<legend style=\"text-align:center;\"><strong>Relatório de Encarteiramento de Clientes</strong><br>$gerado</legend>";
    echo "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong> Status - </strong>".$legenda_status.", <strong>Plano - </strong>".$legenda_plano.", <strong> Serviço - </strong>".$legenda_servico.", <strong> Responsável pelo Relacionamento - </strong>".$legenda_responsavel.", <strong> Responsável Técnico - </strong>".$legenda_responsavel_tecnico." ";
    echo "</legend>";

    $dados  =  DBRead('','tb_contrato_plano_pessoa a ', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa INNER JOIN tb_plano c ON a.id_plano = c.id_plano WHERE a.id_contrato_plano_pessoa ".$filtro_plano." ".$filtro_servico." ".$filtro_status." ".$filtro_responsavel." ".$filtro_responsavel_tecnico." ORDER BY b.nome ASC, c.cod_servico ASC, a.data_inicio_contrato DESC","a.*, b.nome AS 'nome_pessoa', b.razao_social, c.nome AS 'nome_plano', c.cod_servico, c.cor");

    if($dados){  
        echo "<p class='text-center'><strong>Total de resultados: </strong>".sizeof($dados)."</p>";

        echo '
            <table class="table table-hover dataTable" style="margin-bottom:0;">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Contrato</th>
                        <th>Serviço</th>
                        <th>Plano</th>
                        <th>Status</th>
                        <th>Responsável pelo Relacionamento</th>
                        <th>Responsável Técnico</th>
                    </tr>
                </thead>
                <tbody>
        ';      

        $total_call_suporte = array();
        $total_gestao_redes = array();
        $total_call_ativo = array();
        $totais = array();

        $total_call_suporte_tecnico = array();
        $total_gestao_redes_tecnico = array();
        $total_call_ativo_tecnico = array();
        $totais_tecnico = array();

        foreach ($dados as $conteudo) { 

            $id = $conteudo['id_contrato_plano_pessoa'];
            $nome_pessoa = $conteudo['nome_pessoa'];
            if($conteudo['nome_contrato']){
                $nome_contrato = ' ('.$conteudo['nome_contrato'].')';
            }else{
                $nome_contrato = '';
            }
            $nome_plano = $conteudo['nome_plano'];
            $cod_servico = $conteudo['cod_servico'];
            $servico = getNomeServico($cod_servico);

            $dados_contrato_filho  =  DBRead('','tb_contrato_plano_pessoa a', "INNER JOIN tb_pessoa b On a.id_pessoa = b.id_pessoa WHERE a.contrato_pai = '".$conteudo['id_contrato_plano_pessoa']."' ");

            if($dados_contrato_filho){
                $marcador_filho = '<strong>*</strong>';
            }else{
                $marcador_filho = '';
            }

            $status = getNomeStatusPlano($conteudo['status']);
                
            $dados_responsavel = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_usuario = '".$conteudo['id_responsavel']."' ","b.nome");
            $dados_responsavel_tecnico = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_usuario = '".$conteudo['id_responsavel_tecnico']."' ","b.nome");
            
            echo '<tr>
                <td class="text-left">'.$id.'</td>                
                <td class="text-left">'.$nome_pessoa.''.$nome_contrato.' '.$marcador_filho.'</td>                
                <td class="text-left">'.$servico.'</td>                
                    <td class="text-left">'.$nome_plano.'</td>
                    <td class="text-left">'.$status.'</td>
                    <td class="text-left">'.$dados_responsavel[0]['nome'].'</td>
                    <td class="text-left">'.$dados_responsavel_tecnico[0]['nome'].'</td>
                </tr>'; 

            if($cod_servico == 'call_suporte'){
                $total_call_suporte[$dados_responsavel[0]['nome']] += 1;
                $total_call_suporte_tecnico[$dados_responsavel_tecnico[0]['nome']] += 1;
            }else if($cod_servico == 'gestao_redes'){
                $total_gestao_redes[$dados_responsavel[0]['nome']] += 1;
                $total_gestao_redes_tecnico[$dados_responsavel_tecnico[0]['nome']] += 1;
            }else if($cod_servico == 'call_ativo'){
                $total_call_ativo[$dados_responsavel[0]['nome']] += 1;
                $total_call_ativo_tecnico[$dados_responsavel_tecnico[0]['nome']] += 1;
            }
            
            $totais[$dados_responsavel[0]['nome']] += 1;
            $totais_tecnico[$dados_responsavel_tecnico[0]['nome']] += 1;
        }

        echo '
                </tbody>
            </table>';
        
        echo "<hr>"; 
        
        echo '<div class="panel panel-default">';
            echo '
            <div class="panel-heading clearfix">
                <div class="row">
                    <h3 class="panel-title pull-center text-center">Responsáveis pelo Relacionamento</h3>
                </div>
            </div>';
            echo '<div class="panel-body">';
            if($total_call_suporte){
                echo '
                <table class="table table-hover dataTable" style="margin-bottom:0;">
                        <thead>
                            <tr>
                                <th class="text-left col-md-8">Call Center - Suporte</th>
                                <th class="text-left col-md-4">Quantidade</th>
                            </tr>
                        </thead>
                        <tbody>';  

                        $aux_plano = 0;

                        arsort($total_call_suporte);   
                        foreach ($total_call_suporte as $plano => $qtd) {
                            echo '<tr>';
                            echo '<td>'.$plano.'</td>';
                            echo '<td>'.$qtd.'</td>';
                            echo '</tr>';  
                            $aux_plano = $aux_plano + (int)$qtd;    
                        }
                        echo '</tbody>';                            
                echo '</table>
                <hr>';
            }
            
            if($total_gestao_redes){
                echo '
                <table class="table table-hover dataTable" style="margin-bottom:0;">
                        <thead>
                            <tr>
                                <th class="text-left col-md-8">Gestão de Redes</th>
                                <th class="text-left col-md-4">Quantidade</th>
                            </tr>
                        </thead>
                        <tbody>';  

                        $aux_plano = 0;

                        arsort($total_gestao_redes);   
                        foreach ($total_gestao_redes as $plano => $qtd) {
                            echo '<tr>';
                            echo '<td>'.$plano.'</td>';
                            echo '<td>'.$qtd.'</td>';
                            echo '</tr>';  
                            $aux_plano = $aux_plano + (int)$qtd;    
                        }
                        echo '</tbody>';                            
                echo '</table>
                <hr>';
            }

            if($total_call_ativo){
                echo '
                <table class="table table-hover dataTable" style="margin-bottom:0;">
                        <thead>
                            <tr>
                                <th class="text-left col-md-8">Call Center - Ativo</th>
                                <th class="text-left col-md-4">Quantidade</th>
                            </tr>
                        </thead>
                        <tbody>';  

                        $aux_plano = 0;

                        arsort($total_call_ativo);   
                        foreach ($total_call_ativo as $plano => $qtd) {
                            echo '<tr>';
                            echo '<td>'.$plano.'</td>';
                            echo '<td>'.$qtd.'</td>';
                            echo '</tr>';  
                            $aux_plano = $aux_plano + (int)$qtd;    
                        }
                        echo '</tbody>';                            
                echo '</table>
                <hr>';
            }

            echo '
            <table class="table table-hover dataTable" style="margin-bottom:0;">
                <thead>
                <tr>
                    <th class="text-left col-md-8">Totais</th>
                    <th class="text-left col-md-4">Quantidade</th>
                </tr>
                </thead>
                <tbody>';  

                $aux_total = 0;

                arsort($totais);   
                foreach ($totais as $total => $qtd) {
                echo '<tr>';
                echo '<td>'.$total.'</td>';
                echo '<td>'.$qtd.'</td>';
                echo '</tr>';  
                $aux_total = $aux_total + (int)$qtd;    
                }
                echo '
                </tbody>';                            
            echo '
            </table>
            <hr>'; 
        echo "</div>
        </div>";

        echo '<div class="panel panel-default">';
            echo '
            <div class="panel-heading clearfix">
                <div class="row">
                    <h3 class="panel-title pull-center text-center">Responsáveis Técnicos</h3>
                </div>
            </div>';
            echo '<div class="panel-body">';
            if($total_call_suporte_tecnico){
                echo '
                <table class="table table-hover dataTable" style="margin-bottom:0;">
                        <thead>
                            <tr>
                                <th class="text-left col-md-8">Call Center - Suporte</th>
                                <th class="text-left col-md-4">Quantidade</th>
                            </tr>
                        </thead>
                        <tbody>';  

                        $aux_plano = 0;

                        arsort($total_call_suporte_tecnico);   
                        foreach ($total_call_suporte_tecnico as $plano => $qtd) {
                            echo '<tr>';
                            echo '<td>'.$plano.'</td>';
                            echo '<td>'.$qtd.'</td>';
                            echo '</tr>';  
                            $aux_plano = $aux_plano + (int)$qtd;    
                        }
                        echo '</tbody>';                            
                echo '</table>
                <hr>';
            }
            
            if($total_gestao_redes_tecnico){
                echo '
                <table class="table table-hover dataTable" style="margin-bottom:0;">
                        <thead>
                            <tr>
                                <th class="text-left col-md-8">Gestão de Redes</th>
                                <th class="text-left col-md-4">Quantidade</th>
                            </tr>
                        </thead>
                        <tbody>';  

                        $aux_plano = 0;

                        arsort($total_gestao_redes_tecnico);   
                        foreach ($total_gestao_redes_tecnico as $plano => $qtd) {
                            echo '<tr>';
                            echo '<td>'.$plano.'</td>';
                            echo '<td>'.$qtd.'</td>';
                            echo '</tr>';  
                            $aux_plano = $aux_plano + (int)$qtd;    
                        }
                        echo '</tbody>';                            
                echo '</table>
                <hr>';
            }

            if($total_call_ativo_tecnico){
                echo '
                <table class="table table-hover dataTable" style="margin-bottom:0;">
                        <thead>
                            <tr>
                                <th class="text-left col-md-8">Call Center - Ativo</th>
                                <th class="text-left col-md-4">Quantidade</th>
                            </tr>
                        </thead>
                        <tbody>';  

                        $aux_plano = 0;

                        arsort($total_call_ativo_tecnico);   
                        foreach ($total_call_ativo_tecnico as $plano => $qtd) {
                            echo '<tr>';
                            echo '<td>'.$plano.'</td>';
                            echo '<td>'.$qtd.'</td>';
                            echo '</tr>';  
                            $aux_plano = $aux_plano + (int)$qtd;    
                        }
                        echo '</tbody>';                            
                echo '</table>
                <hr>';
            }

            echo '
            <table class="table table-hover dataTable" style="margin-bottom:0;">
                <thead>
                <tr>
                    <th class="text-left col-md-8">Totais</th>
                    <th class="text-left col-md-4">Quantidade</th>
                </tr>
                </thead>
                <tbody>';  

                $aux_total = 0;

                arsort($totais_tecnico);   
                foreach ($totais_tecnico as $total => $qtd) {
                echo '<tr>';
                echo '<td>'.$total.'</td>';
                echo '<td>'.$qtd.'</td>';
                echo '</tr>';  
                $aux_total = $aux_total + (int)$qtd;    
                }
                echo '
                </tbody>';                            
            echo '
            </table>
            <hr>'; 
        echo "</div>
        </div>";
    
    } else {
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
            var table = $('.dataTable').DataTable({
                \"language\": {
                    \"url\": \"//cdn.datatables.net/plug-ins/1.10.19/i18n/Portuguese-Brasil.json\"
                },
                columnDefs: [
                    { type: 'num', targets: 0 },
                    { type: 'chinese-string', targets: 1 },
                ],   
                \"order\": [[ 1, \"asc\" ]],
                \"searching\": false,
                \"paging\":   false,
                \"info\":     false
            });

            var buttons = new $.fn.dataTable.Buttons(table, {
                buttons: [
                    {
                        extend: 'excelHtml5',
                        text: '<i class=\"fa fa-file-excel-o\" aria-hidden=\"true\"></i> Exportar',
                        filename: 'relatorio_contratos_encarteiramento',
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
}         

function relatorio_tempo_contrato($status, $plano, $servico, $id_contrato_plano_pessoa, $data_de, $data_ate, $id_responsavel, $id_responsavel_tecnico, $diferente, $indice_reajuste, $realiza_cobranca){

    $data_de_consulta = converteData($data_de);
    $data_ate_consulta = converteData($data_ate);

    $data_hoje = getDataHora();
	$data_hoje = converteDataHora($data_hoje);

	if ($status && $status != 'todos') {
       	$filtro_status = "AND a.status = '".$status."'";
        $legenda_status = getNomeStatusPlano($status);

    } else if ($status == 'todos'){
    	$filtro_status = "";
        $legenda_status = 'Todos';

    } else {
       	$filtro_status = "AND a.status = '0'";
        $legenda_status = getNomeStatusPlano(0);
    }

    if ($plano) {
        $filtro_plano = "AND c.id_plano = '".$plano."'";
        $dados_plano = DBRead('', 'tb_plano',"WHERE id_plano = '".$plano."' ORDER BY nome");
        $legenda_plano = $dados_plano[0]['nome'];

    } else {
        $filtro_plano = "";
        $legenda_plano = 'Todos';
    }

    if ($servico) {
        $filtro_servico = "AND c.cod_servico = '".$servico."'";
        $dados_servico = DBRead('', 'tb_plano', "WHERE cod_servico = '".$servico."'", 'cod_servico');
        $legenda_servico = getNomeServico($dados_servico[0]['cod_servico']);

        if ($servico == 'call_suporte') {
            if ($diferente) {
                if ($diferente == 1) {
                    $filtro_diferente = "AND a.valor_diferente_texto = '1' ";
                    $legenda_diferente = "Apenas Diferentes";

                } else {
                    $filtro_diferente = "AND a.valor_diferente_texto != '1' ";
                    $legenda_diferente = "Não Mostrar Diferentes";
                }
            } else {
                $filtro_diferente = "";
                $legenda_diferente = 'Todos';
            }

            $legenda_completa_valor_diferente = "<strong>, Mostrar Contratos com Valores Diferentes para Texto e Voz - </strong>".$legenda_diferente;

        } else {
            $legenda_completa_valor_diferente = "";
        }
        
    } else {
        $filtro_servico = "";
        $legenda_servico = 'Todos';
    }

    if ($id_contrato_plano_pessoa) {
        $filtro_contrato_plano_pessoa = "AND a.id_contrato_plano_pessoa = '".$id_contrato_plano_pessoa."'";
        $dados_contrato  =  DBRead('','tb_contrato_plano_pessoa a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_contrato_plano_pessoa = '".$id_contrato_plano_pessoa."'", "b.nome");
        $legenda_contrato = $dados_contrato[0]['nome'];

    } else {
        $filtro_contrato_plano_pessoa = "";
        $legenda_contrato = 'Todos';
    }

    if ($data_de && $data_ate) {
    	$filtro_data = " AND data_inicio_contrato <= '".$data_ate_consulta."' AND data_inicio_contrato >= '".$data_de_consulta."'";
    	$periodo_amostra = "<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> de ".$data_de." até ".$data_ate."</span>";

    } else if ($data_ate) {
    	$filtro_data = " AND data_inicio_contrato <= '".$data_ate_consulta."'";
    	$periodo_amostra = "<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> até ".$data_ate."</span>";

    } else if ($data_de) {
    	$filtro_data = " AND data_inicio_contrato >= '".$data_de_consulta."'";
    	$periodo_amostra = "<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> a partir de ".$data_de."</span>";

    } else {
    	$filtro_data = "";
    	$periodo_amostra = "<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> Todos</span>";	
    }

    if ($id_responsavel) {
        $filtro_responsavel = "AND a.id_responsavel = '".$id_responsavel."'";
        $dados_responsavel = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_usuario = '".$id_responsavel."' ","b.nome");
        $legenda_responsavel = $dados_responsavel[0]['nome'];

    } else {
        $filtro_responsavel = "";
        $legenda_responsavel = 'Qualquer';
    }

    if ($id_responsavel_tecnico) {
        $filtro_responsavel_tecnico = "AND a.id_responsavel_tecnico = '".$id_responsavel_tecnico."'";
        $dados_responsavel_tecnico = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_usuario = '".$id_responsavel_tecnico."' ","b.nome");
        $legenda_responsavel_tecnico = $dados_responsavel_tecnico[0]['nome'];

    } else {
        $filtro_responsavel_tecnico = "";
        $legenda_responsavel_tecnico = 'Qualquer';
    }

    if ($realiza_cobranca) {
        if ($realiza_cobranca == '1') {
            $legenda_realiza_cobranca = 'Sim';
            $filtro_realiza_cobranca = "AND a.realiza_cobranca = '1' ";

        } else if ($realiza_cobranca == '2'){
            $legenda_realiza_cobranca = 'Não'; 
            $filtro_realiza_cobranca = "AND a.realiza_cobranca = '0' ";
        }

    } else {
        $filtro_realiza_cobranca = "";
        $legenda_realiza_cobranca = 'Qualquer';
    }

	$gerado = "<span style=\"font-size: 14px;\"><strong>Gerado em: </strong> $data_hoje</span>";

	echo "<div class=\"col-md-12\" style=\"padding: 0\">";
	echo "<legend style=\"text-align:center;\"><strong>Relatório de Contratos - Tabela</strong><br>$gerado</legend>";
    echo "<legend style=\"text-align:center;\">$periodo_amostra</legend>";
	echo "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong> Contrato - </strong>".$legenda_contrato.", <strong> Status - </strong>".$legenda_status.", <strong>Plano - </strong>".$legenda_plano.", <strong> Serviço - </strong>".$legenda_servico.", <strong> Responsável pelo Relacionamento - </strong>".$legenda_responsavel.", <strong> Responsável Técnico - </strong>".$legenda_responsavel_tecnico.", <strong> Realiza Cobrança - </strong>".$legenda_realiza_cobranca." ".$legenda_completa_valor_diferente." ";
	echo "</legend>";

	$data_de = converteData($data_de);
	$data_ate = converteData($data_ate);

	$dados  =  DBRead('','tb_contrato_plano_pessoa a ', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa INNER JOIN tb_plano c ON a.id_plano = c.id_plano WHERE a.id_contrato_plano_pessoa ".$filtro_plano." ".$filtro_servico." ".$filtro_contrato_plano_pessoa." ".$filtro_status." ".$filtro_data." ".$filtro_responsavel." ".$filtro_responsavel_tecnico." ".$filtro_diferente." ".$filtro_realiza_cobranca." AND b.nome not like '%ZZ - Belluno%' ORDER BY b.nome ASC, c.cod_servico ASC, a.data_inicio_contrato DESC","a.data_inicio_contrato, b.nome");

    if ($dados) {
        echo "<div class='row'>";
        	echo "<div class='col-md-10 col-md-offset-1'>";
            echo "<table class='table table-hover dataTable' style='font-size='14px'>";
			echo "<thead>";
				echo "<tr>";
					echo "<th>Contrato</th>";
					echo "<th>Data de início</th>";
					echo "<th>Tempo (em meses)</th>";
					echo "<th>Tempo (Anos / Meses)</th>";
				echo "</tr>";
			echo "</thead>";
			echo "<tbody>";

		$total_meses = 0;
		$legenda_total = 0;
		$contador_contrato = 0;
        
		foreach($dados as $conteudo){

			$data_hoje = getDataHora();
			$data_hoje = explode(' ', $data_hoje);

			$data_inicio = new DateTime($conteudo['data_inicio_contrato']);
			$data_fim = new DateTime($data_hoje[0]);

			$dateInterval = $data_inicio->diff($data_fim);
			$tempo_meses = $dateInterval->days/30;
			$anos = $dateInterval->y;
			$meses = $dateInterval->m;

			$total_meses += $tempo_meses;
			
			if ($anos > 0 && $meses > 0) {
				$legenda = $anos.' a '.$meses.' m';
                $legenda_total = ($anos * 12) + $meses + $legenda_total;

            } else if ($anos > 0 && $meses <= 0) {
                $legenda = $anos.' a';
                $legenda_total = ($anos * 12) + $meses + $legenda_total;

			} else {
				$legenda = $meses.' m';
				$legenda_total = $meses + $legenda_total;
			}

			$contador_contrato++;
		
			echo "<tr>";
				echo "<td>".$conteudo['nome']."</td>";
				echo "<td>".converteData($conteudo['data_inicio_contrato'])."</td>";
				echo "<td>".sprintf("%01.1f", $tempo_meses)."</td>";
				echo "<td>".$legenda."</td>";
			echo "</tr>";
		}

		$total_media_meses = sprintf("%01.1f", $total_meses/$contador_contrato);

        if ($total_media_meses >= 12) {

            $total_meses = sprintf("%01.1f", $total_media_meses/12);

            $explode = explode('.', $total_meses);

            if($explode[0] > 0 && $explode[1] > 0){
                $legenda_media = $explode[0].' a '.$explode[1].' m';

            } else if ($explode[0] > 0 && $explode[1] <= 0) {
                $legenda_media = $explode[0].' a';
            }

        } else {
            $explode = explode('.', $total_media_meses);
            $legenda_media = $explode[0].' m';
        }

			echo "</tbody>";
			echo "<tfoot>";
				echo "<tr>";
					echo "<th>Médias:</th>";
					echo "<th></th>";
					echo "<th>".$total_media_meses."</th>";
					echo "<th>".$legenda_media."</th>";
				echo "</tr>";
			echo "</tfoot>";
		echo "</table>";
		echo "</div>";
		echo "</div>";

		echo "
		<script>
			$(document).ready(function(){
			    var table = $('.dataTable').DataTable({
				    \"language\": {
			            \"url\": \"//cdn.datatables.net/plug-ins/1.10.19/i18n/Portuguese-Brasil.json\"
			        },
			        columnDefs: [
				    	{ type: 'time-uni', targets: 1 },
				    ],
			        \"searching\": false,
			        \"paging\":   false,
			        \"info\":     false
                });
			});
		</script>			
		";

    } else {
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

function relatorio_proprietarios(){

    $data_hoje = getDataHora();
	$data_hoje = converteDataHora($data_hoje);

	$gerado = "<span style=\"font-size: 14px;\"><strong>Gerado em: </strong> $data_hoje</span>";

	echo "<div class=\"col-md-12\" style=\"padding: 0\">";
	echo "<legend style=\"text-align:center;\"><strong>Relatório de proprietários</strong><br>$gerado</legend>";
	echo "</legend>";

	$dados  =  DBRead('','tb_contrato_plano_pessoa a ', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa INNER JOIN tb_vinculo_pessoa c ON c.id_pessoa_pai = a.id_pessoa INNER JOIN tb_pessoa d ON c.id_pessoa_filho = d.id_pessoa INNER JOIN tb_vinculo_tipo_pessoa f ON f.id_vinculo_pessoa = c.id_vinculo_pessoa INNER JOIN tb_vinculo_tipo g ON f.id_vinculo_tipo = g.id_vinculo_tipo WHERE g.id_vinculo_tipo = 3 AND a.status = 1 AND b.id_pessoa != 2 ORDER BY b.nome ASC", "b.nome as empresa, d.nome as proprietario, d.email1, g.nome");

    if ($dados) {
        echo "<div class='row'>";
        	echo "<div class='col-md-10 col-md-offset-1'>";
            echo "<table class='table table-hover dataTable' style='font-size='14px'>";
			echo "<thead>";
				echo "<tr>";
					echo "<th>Empresa</th>";
					echo "<th>Proprietário</th>";
					echo "<th>Email</th>";
				echo "</tr>";
			echo "</thead>";
			echo "<tbody>";
        
            foreach($dados as $conteudo){

                if ($conteudo['email1'] != '') {
                    $email = $conteudo['email1'];
                } else {
                    $email = 'Não cadastrado';
                }

                echo "<tr>";
                    echo "<td>".$conteudo['empresa']."</td>";
                    echo "<td>".$conteudo['proprietario']."</td>";
                    echo "<td>".$email."</td>";
                echo "</tr>";
            }

		echo "</tbody>";
		echo "</table>";
		echo "</div>";
		echo "</div>";

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
                            filename: 'relatorio_contratos_ativos',
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

    } else {
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

function relatorio_pre_pago_recarga($id_contrato_plano_pessoa, $data_de_recarga, $data_ate_recarga, $data_de_cadastro, $data_ate_cadastro){

    
    if($id_contrato_plano_pessoa){
        $empresa_filtro  =  DBRead('','tb_contrato_plano_pessoa a ', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_contrato_plano_pessoa = $id_contrato_plano_pessoa","a.*, b.*"); 
        $filtro_empresa = "AND a.id_contrato_plano_pessoa = $id_contrato_plano_pessoa";
        $legenda_empresa = $empresa_filtro['0']['nome'];
    } else {
        $filtro_empresa = '';
        $legenda_empresa = 'Todas';
    }

    if ($data_de_recarga && $data_ate_recarga) {
    	$filtro_data_recarga = " AND data_recarga >= '".converteData($data_de_recarga)."' AND data_recarga <= '".converteData($data_ate_recarga)."'";
    	$legenda_data_recarga = "De ".$data_de_recarga." Até ".$data_ate_recarga;

    } else if ($data_ate_recarga) {
    	$filtro_data_recarga = " AND data_recarga <= '".converteData($data_ate_recarga)."'";
    	$legenda_data_recarga = "Até ".$data_ate_recarga;

    } else if ($data_de_recarga) {
    	$filtro_data_recarga = " AND data_final_cobranca >= '".converteData($data_de_recarga)."'";
    	$legenda_data_recarga = "De ".$data_de_recarga;

    } else {
        $filtro_data_recarga = '';
        $legenda_data_recarga = 'Qualquer';
    }

    if ($data_de_cadastro && $data_ate_cadastro) {
    	$filtro_data_cadastro = " AND data_cadastro >= '".converteData($data_de_cadastro)."' AND data_cadastro <= '".converteData($data_ate_cadastro)."'";
    	$legenda_data_cadastro = "De ".$data_de_cadastro." Até ".$data_ate_cadastro;

    } else if ($data_ate_cadastro) {
    	$filtro_data_cadastro = " AND data_cadastro <= '".converteData($data_ate_cadastro)."'";
    	$legenda_data_cadastro = "Até ".$data_ate_cadastro;

    } else if ($data_de_recarga) {
    	$filtro_data_cadastro = " AND data_final_cadastro >= '".converteData($data_de_cadastro)."'";
    	$legenda_data_cadastro = "De ".$data_de_cadastro;

    } else {
        $filtro_data_cadastro = '';
        $legenda_data_cadastro = 'Qualquer';
    }

    $dados  =  DBRead('','tb_contrato_plano_pessoa a ', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa INNER JOIN tb_contrato_recarga c ON a.id_contrato_plano_pessoa = c.id_contrato_plano_pessoa INNER JOIN tb_plano d ON a.id_plano = d.id_plano WHERE c.status = 1 $filtro_empresa $filtro_data_recarga $filtro_data_cadastro","a.*, b.*, c.*, d.cod_servico, d.nome AS 'nome_plano'"); 

    $data_hoje = getDataHora();
	$data_hoje = converteDataHora($data_hoje);
    $gerado = "<span style=\"font-size: 14px;\"><strong>Gerado em: </strong> $data_hoje</span>"; ?>

    <div class="col-md-12">
    <legend style="text-align:center"><strong>Relatório de Contratos Pré Pagos (Recarga)</strong><br><?= $gerado ?></legend>

    <legend style="text-align:center">
    <span style="font-size: 14px">
        <strong>Empresa - </strong><?= $legenda_empresa ?>, 
        <strong>Data de recarga - </strong><?= $legenda_data_recarga ?>, 
        <strong>Data de cadastro - </strong><?= $legenda_data_cadastro ?>.
    </legend>

    <?php if($dados){ ?>
    <div class='col-md-12 center'>
        <table class='table table-hover dataTable'>
            <tr>
                <th class="col-md-2">Empresa</th>
                <th class="col-md-3">Plano</th>
                <th class="col-md-2">Atendimento Mensais</th>
                <th class="col-md-1">Data de recarga</th>
                <th class="col-md-2">Data de cadastro</th>
                <th class="col-md-2">Quantidade da recarga</th>
            </tr>
                <?php foreach($dados as $conteudo){
                $nome = $conteudo['nome'];
                $data_recarga = $conteudo['data_recarga'];
                $data_recarga = converteDataHora($data_recarga);
                $data_cadastro = $conteudo['data_cadastro'];
                $data_cadastro = converteDataHora($data_cadastro);
                $qnt_atendimentos = $conteudo['quantidade_atendimentos'];
                $saldo_total = $conteudo['qtd_contratada']; ?>
            <tr>
                <td><?= $nome ?></td>
                <td><?= getNomeServico($conteudo['cod_servico']).' - '.$conteudo['nome_plano'] ?></td>
                <td><?= $saldo_total ?> Atendimentos</td>
                <td><?= $data_recarga ?></td>
                <td><?= $data_cadastro ?></td>
                <td><?= $qnt_atendimentos ?> Atendimentos</td>
            </tr>
            <?php } ?>
        </table> 
    </div>    
    <?php } else { ?>
        <table class='table table-bordered'>
            <tbody>
                <tr>
                    <td class='text-center'><h4>Não foram encontrados resultados!</h4></td>
                </tr>
            </tbody>
        </table>
    <?php }
    
}
