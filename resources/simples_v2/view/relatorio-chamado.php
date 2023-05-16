<?php
require_once(__DIR__."/../class/System.php");

$dados_usuarios = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa ORDER BY a.email ASC");

$data_hoje = getDataHora();
$data_hoje = explode(" ", $data_hoje);
$data_hoje = $data_hoje[0];
$primeiro_dia = "01/".$data_hoje[5].$data_hoje[6]."/".$data_hoje[0].$data_hoje[1].$data_hoje[2].$data_hoje[3];

$busca_contrato = (!empty($_POST['busca_contrato'])) ? $_POST['busca_contrato'] : '';
$id_contrato_plano_pessoa = (!empty($_POST['id_contrato_plano_pessoa'])) ? $_POST['id_contrato_plano_pessoa'] : '';

$gerar = (!empty($_POST['gerar'])) ? 1 : 0;

$id_usuario = $_SESSION['id_usuario'];
$dados = DBRead('', 'tb_usuario', "WHERE id_usuario = '$id_usuario'");
$perfil_sistema = $dados[0]['id_perfil_sistema'];
$id_asterisk_usuario = $dados[0]['id_asterisk'];

$tipo_relatorio = (!empty($_POST['tipo_relatorio'])) ? $_POST['tipo_relatorio'] : 2;

$data_de = (!empty($_POST['data_de'])) ? $_POST['data_de'] : $primeiro_dia;
$data_ate = (!empty($_POST['data_ate'])) ? $_POST['data_ate'] : converteData(getDataHora('data'));
$remetente = (!empty($_POST['remetente'])) ? $_POST['remetente'] : '';
$origem = (!empty($_POST['origem'])) ? $_POST['origem'] : '';
$status = (!empty($_POST['status'])) ? $_POST['status'] : '';
$id_categoria = (!empty($_POST['id_categoria'])) ? $_POST['id_categoria'] : '';
$id_visibilidade = (!empty($_POST['id_visibilidade'])) ? $_POST['id_visibilidade'] : '';
$id_responsavel = (!empty($_POST['id_responsavel'])) ? $_POST['id_responsavel'] : '';
$id_perfil_sistema = (!empty($_POST['id_perfil_sistema'])) ? $_POST['id_perfil_sistema'] : '';
$id_contrato_plano_pessoa = (!empty($_POST['id_contrato_plano_pessoa'])) ? $_POST['id_contrato_plano_pessoa'] : '';

$pessoa = (!empty($_POST['pessoa'])) ? $_POST['pessoa'] : '';

$usuarios = (!empty($_POST['usuarios'])) ? $_POST['usuarios'] : '';

$ticket = (!empty($_POST['ticket'])) ? $_POST['ticket'] : '';

if ($id_visibilidade == '1') {
    $display_row_usuarios = 'style="display:none;"';
}else if($id_visibilidade == '2'){
    $display_row_usuarios = '';
}else if($id_visibilidade == ''){
    $display_row_usuarios = 'style="display:none;"';
}

if ($tipo_relatorio == '1') {
    
    $display_row_origem = '';
    $display_row_visibilidade = '';
    $display_row_responsavel = '';
    $display_row_remetente = '';
    $display_row_status = '';
    $display_row_pessoa = 'style="display:none;"';
    $display_row_ticket = '';
    $display_row_categoria = '';
    $display_row_perfil = '';
    $display_row_contrato = '';

}else if($tipo_relatorio == '2'){

    $display_row_origem = 'style="display:none;"';
    $display_row_visibilidade = 'style="display:none;"';
    $display_row_responsavel = 'style="display:none;"';
    $display_row_remetente = 'style="display:none;"';
    $display_row_status = '';
    $display_row_categoria = '';
    $display_row_pessoa = '';
    $display_row_ticket = 'style="display:none;"';
    $display_row_perfil = 'style="display:none;"';
    $display_row_contrato = '';

}else if ($tipo_relatorio == '3') {

    $display_row_origem = '';
    $display_row_visibilidade = '';
    $display_row_responsavel = '';
    $display_row_remetente = '';
    $display_row_status = '';
    $display_row_pessoa = 'style="display:none;"';
    $display_row_ticket = '';
    $display_row_categoria = '';
    $display_row_perfil = 'style="display:none;"';
    $display_row_contrato = '';

}else if ($tipo_relatorio == '4') {

    $display_row_origem = 'style="display:none;"';
    $display_row_visibilidade = 'style="display:none;"';
    $display_row_responsavel = '';
    $display_row_remetente = '';
    $display_row_status = '';
    $display_row_pessoa = 'style="display:none;"';
    $display_row_ticket = '';
    $display_row_categoria = '';
    $display_row_perfil = 'style="display:none;"';
    $display_row_contrato = '';
}

if($gerar){
	$collapse = '';
	$collapse_icon = 'plus';
}else{
	$collapse = 'in';
	$collapse_icon = 'minus';
}

if($id_contrato_plano_pessoa){
	$dados_contrato = DBRead('', 'tb_contrato_plano_pessoa a', "INNER JOIN tb_plano b ON a.id_plano = b.id_plano INNER JOIN tb_pessoa c ON a.id_pessoa = c.id_pessoa WHERE a.id_contrato_plano_pessoa = '$id_contrato_plano_pessoa'", "a.*, b.cod_servico, b.nome AS 'plano', c.nome AS 'nome_pessoa'");

	    if($dados_contrato[0]['nome_contrato']){
	        $nome_contrato = " (".$dados_contrato[0]['nome_contrato'].") ";
	    }

	    $contrato = $dados_contrato[0]['nome_pessoa'] . " ". $nome_contrato ." - " . getNomeServico($dados_contrato[0]['cod_servico']) . " - " . $dados_contrato[0]['plano'] . " (" . $dados_contrato[0]['id_contrato_plano_pessoa'] . ")";
}

//Perfis de liderados
$perfil_lider = busca_perfis_liderados($perfil_sistema);
//Usuários liderados
$liderados = array();
$usuario_lider = busca_liderados($id_usuario, $liderados);

if($id_contrato_plano_pessoa){
    $dados_contrato = DBRead('', 'tb_contrato_plano_pessoa a', "INNER JOIN tb_plano b ON a.id_plano = b.id_plano INNER JOIN tb_pessoa c ON a.id_pessoa = c.id_pessoa WHERE a.id_contrato_plano_pessoa = '$id_contrato_plano_pessoa'", "a.*, b.cod_servico, b.nome AS 'plano', c.nome AS 'nome_pessoa'");
    if($dados_contrato[0]['nome_contrato']){
        $nome_contrato = " (".$dados_contrato[0]['nome_contrato'].") ";
    }
    $contrato = $dados_contrato[0]['nome_pessoa'] . " ". $nome_contrato ." - " . getNomeServico($dados_contrato[0]['cod_servico']) . " - " . $dados_contrato[0]['plano'] . " (" . $dados_contrato[0]['id_contrato_plano_pessoa'] . ")";
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
	<form method="post" action="">
	    <div class="row">
	        <div class="col-md-4 col-md-offset-4">
	            <div class="panel panel-default noprint">
	                <div class="panel-heading clearfix">
	                    <h3 class="panel-title text-left pull-left" style="margin-top: 2px;">Relatório - Chamado:</h3>
	                    <div class="panel-title text-right pull-right"><button data-toggle="collapse" data-target="#accordionRelatorio" class="btn btn-xs btn-info" type="button" title="Visualizar filtros"><i id="i_collapse" class="fa fa-<?=$collapse_icon?>"></i></button></div>
	                </div>
	                <div id="accordionRelatorio" class="panel-collapse collapse <?=$collapse?>">
                        <div class="panel-body">	                		
                        <?php 
                        //if($perfil_sistema != '3'){ 
                        ?>
                			<div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Tipo de Relatório:</label> 
                                        <select name="tipo_relatorio" id="tipo_relatorio" class="form-control input-sm">
                                            <option value="2" <?php if($tipo_relatorio == '2'){echo 'selected';}?>>Pessoas</option>
                                            <option value="1" <?php if($tipo_relatorio == '1'){echo 'selected';}?>>Chamados</option>
                                            <option value="3" <?php if($tipo_relatorio == '3'){echo 'selected';}?>>Detalhado</option>
                                            <option value="4" <?php if($tipo_relatorio == '4'){echo 'selected';}?>>Prazo</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                        <?php
                        //} 
                        
                        ?>

                            <div class="row" id="row_contrato" <?=$display_row_contrato?>>
								<div class="col-md-12">
									<div class="form-group">
							        <label>Contrato (cliente):</label>
                                        <div class="input-group">
		                                    <input class="form-control input-sm" id="busca_contrato" type="text" name="busca_contrato"  value="<?=$contrato?>" placeholder="Informe o nome ou CNPJ..." autocomplete="off" readonly />
		                                    <div class="input-group-btn">
		                                        <button class="btn btn-info btn-sm" id="habilita_busca_contrato" name="habilita_busca_contrato" type="button" title="Clique para selecionar o contrato" style="height: 30px;"><i class="fa fa-search"></i></button>
		                                    </div>
		                                </div>
		                                <input type="hidden" name="id_contrato_plano_pessoa" id="id_contrato_plano_pessoa" value="<?=$id_contrato_plano_pessoa;?>" />
								    </div>
								</div>
							</div>

                			<div class="row" id="row_origem" <?=$display_row_origem?>>
								<div class="col-md-12">
									<div class="form-group">
							        <label>Origem:</label>
                                        <select class="form-control input-sm" id="origem" name="origem" required>
                                             <option value="0">Qualquer</option>
                                            <?php
                                                $dados_origem = DBRead('', 'tb_chamado_origem',"ORDER BY descricao ASC"); 
                                                if($dados_origem){
                                                    foreach($dados_origem as $conteudo_origem){ 
                                                        $selected = $origem == $conteudo_origem['id_chamado_origem'] ? "selected" : "";
                                                        echo "<option value='".$conteudo_origem['id_chamado_origem']."' ".$selected.">".$conteudo_origem['descricao']."</option>";
                                                    }
                                                }
                                            ?>
                                            
                                        </select>
								    </div>
								</div>
							</div>

							<div class="row" id="row_status" <?=$display_row_status?>>
								<div class="col-md-12">
									<div class="form-group">
								        <label>Status:</label>
                                        <select class="form-control input-sm" id="status" name="status">
                                             <option value="0">Qualquer</option>
                                            <?php
                                                $dados_status = DBRead('', 'tb_chamado_status',"ORDER BY descricao ASC"); 
                                                if($dados_status){
                                                    foreach ($dados_status as $conteudo_status) { 
                                                        $selected = $status == $conteudo_status['id_chamado_status'] ? "selected" : "";
                                                        echo "<option value='".$conteudo_status['id_chamado_status']."' ".$selected.">".$conteudo_status['descricao']."</option>";
                                                    }
                                                }
                                            ?>
                                        </select>
								    </div>
								</div>
							</div>

							<div class="row" id="row_periodo">
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

                            <div class="row" id="row_visibilidade" <?=$display_row_visibilidade?>>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Visibilidade:</label>
                                        <select class="form-control input-sm" id="id_visibilidade" name="id_visibilidade">
                                            <option value="">Qualquer</option>
                                            <option value='1' <?php if($id_visibilidade == 1){echo 'selected';}?>>Público</option>
                                            <option value='2' <?php if($id_visibilidade == 2){echo 'selected';}?>>Privado</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row" id="row_responsavel" <?=$display_row_responsavel?>>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Responsável:</label>
                                        <select class="form-control input-sm" id="id_responsavel" name="id_responsavel">
                                            <option value="0">Qualquer</option>
                                            <?php
                                                $dados_reponsavel = DBRead('','tb_usuario a',"INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa ORDER BY b.nome ASC");
                                                if($dados_reponsavel){
                                                    foreach ($dados_reponsavel as $key => $conteudo_reponsavel) {
                                                        $selected = $id_responsavel == $conteudo_reponsavel['id_usuario'] ? "selected" : "";

                                                        $visualiza_responsavel = "false";
                                                        if($perfil_lider){
                                                            foreach($perfil_lider as $perfil){
                                                                if($dados_reponsavel[$key]['id_perfil_sistema'] == $perfil){
                                                                    $visualiza_responsavel = "true";
                                                                }
                                                            }
                                                        }
                                                        if($usuario_lider){
                                                            foreach($usuario_lider as $usuario){
                                                                if($dados_reponsavel[$key]['id_usuario'] == $usuario){
                                                                    $visualiza_responsavel = "true";
                                                                }
                                                            }
                                                        }
                                                        if($dados_reponsavel[$key]['id_usuario'] == $id_usuario){
                                                            $visualiza_responsavel = "true";
                                                        }

                                                        if($visualiza_responsavel == "true"){
                                                            echo "<option value='".$conteudo_reponsavel['id_usuario']."' ".$selected.">".$conteudo_reponsavel['nome']."</option>";
                                                        }
                                                        
                                                    }
                                                }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row" id="row_remetente" <?=$display_row_remetente?>>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="">Remetente:</label>
                                        <select name="remetente" class="form-control input-sm">
                                            <option value="0">Qualquer</option>
                                            <?php
                                                $dados_remetente = DBRead('','tb_usuario a',"INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa ORDER BY b.nome ASC");

                                                if($dados_remetente){
                                                    foreach ($dados_remetente as $key => $conteudo_remetente) {
                                                        $selected = $remetente == $conteudo_remetente['id_usuario'] ? "selected" : "";

                                                        $visualiza_remetente = "false";
                                                        if($perfil_lider){
                                                            foreach($perfil_lider as $perfil){
                                                                if($dados_remetente[$key]['id_perfil_sistema'] == $perfil){
                                                                    $visualiza_remetente = "true";
                                                                }
                                                            }
                                                        }
                                                        if($usuario_lider){
                                                            foreach($usuario_lider as $usuario){
                                                                if($dados_remetente[$key]['id_usuario'] == $usuario){
                                                                    $visualiza_remetente = "true";
                                                                }
                                                            }
                                                        }
                                                        if($dados_remetente[$key]['id_usuario'] == $id_usuario){
                                                            $visualiza_remetente = "true";
                                                        }

                                                        if($visualiza_remetente == "true"){
                                                            echo "<option value='".$conteudo_remetente['id_usuario']."' ".$selected.">".$conteudo_remetente['nome']."</option>";
                                                        }
                                                    }
                                                }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row" id="row_perfil" <?=$display_row_perfil?>>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="">Perfil:</label>
                                        <select name="id_perfil_sistema" class="form-control input-sm">
                                            <option value="0">Qualquer</option>
                                            <?php
                                                $dados_perfil = DBRead('','tb_perfil_sistema a', "INNER JOIN tb_usuario b ON a.id_perfil_sistema = b.id_perfil_sistema GROUP BY a.id_perfil_sistema ORDER BY nome ASC", "a.id_perfil_sistema, a.nome");

                                                if($dados_perfil){
                                                    foreach($dados_perfil as $key => $conteudo_perfil){ 
                                                        $selected = $id_perfil_sistema == $conteudo_perfil['id_perfil_sistema'] ? "selected" : "";

                                                        $visualiza_perfil = "false";
                                                        if($perfil_lider){
                                                            foreach($perfil_lider as $perfil){
                                                                if($dados_perfil[$key]['id_perfil_sistema'] == $perfil){
                                                                    $visualiza_perfil = "true";
                                                                }
                                                            }
                                                        }

                                                        if($dados_perfil[$key]['id_perfil_sistema'] == $perfil_sistema){
                                                            $visualiza_perfil = "true";
                                                        }

                                                        if($visualiza_perfil == "true"){
                                                            echo "<option value='".$conteudo_perfil['id_perfil_sistema']."' ".$selected.">".$conteudo_perfil['nome']."</option>";
                                                        }
                                                    }
                                                }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row" id="row_pessoa" <?=$display_row_pessoa?>>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="">Pessoa:</label>
                                        <select name="pessoa" id="pessoa" class="form-control input-sm">
                                            <option value="">Selecione a pessoa</option>
                                            <?php
                                                $dados_remetente = DBRead('','tb_usuario a',"INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa ORDER BY b.nome ASC");
                                                
                                                if($dados_remetente){
                                                    foreach ($dados_remetente as $key => $conteudo_remetente){ 
                                                        $selected = $pessoa == $conteudo_remetente['id_usuario'] ? "selected" : "";

                                                        $visualiza_pessoa = "false";
                                                        if($perfil_lider){
                                                            foreach($perfil_lider as $perfil){
                                                                if($dados_remetente[$key]['id_perfil_sistema'] == $perfil){
                                                                    $visualiza_pessoa = "true";
                                                                }
                                                            }
                                                        }
                                                        if($usuario_lider){
                                                            foreach($usuario_lider as $usuario){
                                                                if($dados_remetente[$key]['id_usuario'] == $usuario){
                                                                    $visualiza_pessoa = "true";
                                                                }
                                                            }
                                                        }
                                                        if($dados_remetente[$key]['id_usuario'] == $id_usuario){
                                                            $visualiza_pessoa = "true";
                                                        }
                                                        if($visualiza_pessoa == "true"){
                                                            echo "<option value='".$conteudo_remetente['id_usuario']."' ".$selected.">".$conteudo_remetente['nome']."</option>";
                                                        }
                                                    }
                                                }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>

							<div class="row" id="row_categoria" <?=$display_row_categoria?>>
								<div class="col-md-12">
									<div class="form-group">
								        <label>Categoria:</label>
                                        <select class="form-control input-sm" id="id_categoria" name="id_categoria">
                                            <option value="0">Qualquer</option>
                                            <?php
                                                $dados_categoria = DBRead('', 'tb_categoria', "ORDER BY nome ASC");
                                                if($dados_categoria){
                                                    foreach($dados_categoria as $conteudo_categoria){ 
                                                        $selected = $id_categoria == $conteudo_categoria['id_categoria'] ? "selected" : "";
                                                        echo "<option value='".$conteudo_categoria['id_categoria']."' ".$selected.">".$conteudo_categoria['nome']."</option>";
                                                    }
                                                }
                                            ?>
                                        </select>
								    </div>
								</div>
							</div>

                            <div class="row" id="row_ticket" <?=$display_row_ticket?>>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Ticket:</label>
                                            <input type="number" class="form-control input-sm" name="ticket" value="<?=$ticket?>" placeholder="Digite o número do Ticket">

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
				relatorio_chamado($origem, $data_de, $data_ate, $remetente, $status, $id_categoria, $id_visibilidade, $id_responsavel, $ticket, $id_perfil_sistema, $id_contrato_plano_pessoa);
            }else if($tipo_relatorio == 2){
                relatorio_pessoa($data_de, $data_ate, $id_categoria, $pessoa, $status, $id_contrato_plano_pessoa);
            }else if($tipo_relatorio == 3){
                relatorio_detalhado($origem, $data_de, $data_ate, $remetente, $status, $id_categoria, $id_visibilidade, $id_responsavel, $ticket, $id_contrato_plano_pessoa);
            }else if($tipo_relatorio == 4){
                relatorio_prazo($remetente, $id_responsavel, $data_de, $data_ate, $status, $id_categoria, $ticket, $id_contrato_plano_pessoa);
            }
		}
		?>

	</div>
</div>

<!-- Modal visualizar acao -->
<div class="modal fade" id="myModal3" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title" id="myModalLabel">Tempo do Chamado</h4>
        </div>
        <div class="modal-body">
          <div class="row">
                <div class="col-md-12" style="margin-left: -6px;">
                    <div id="conteudo">
                    </div>
                </div><!-- end col -->
            </div><!-- end row -->
        </div>
      <div class="modal-footer">
         <button type="button" class="btn btn-primary" data-dismiss="modal" aria-hidden="true">
         <i class="fa fa-close"></i> Fechar</button>
      </div>
    </div>
  </div>
</div>
<!-- end modal --> 

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
                            'cod_servico' : 'call_suporte'
                        },
                        token: '<?= $request->token ?>'
                    },
                    success: function (data) {
                        response(data);
                    }
                });
            },
            focus: function (event, ui) {
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
            }if(!item.nome_contrato){
                item.nome_contrato = '';
            }else{
                item.nome_contrato = ' ('+item.nome_contrato+') '; 
            }
            return $("<li>").append("<a><strong>"+item.id_contrato_plano_pessoa + " - " + item.nome + ""+item.nome_contrato+" </strong><br>" +item.razao_social+ "<br>" +item.cpf_cnpj+ "<br>" + item.servico + " - " + item.plano + " (" + item.id_contrato_plano_pessoa + ")" + "</a><hr style='margin-bottom: 0px;'>").appendTo(ul);
    	};
    // Função para carregar os dados da consulta nos respectivos campos
    function carregarDadosContrato(id) {
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
                    seleciona_contrato(data[0].id_contrato_plano_pessoa);
                }
            });
        }
    }
    // Função para limpar os campos caso a busca esteja vazia
    function limpaCamposContrato(){
        var busca = $('#busca_contrato').val();
        if (busca == "") {
            $('#id_contrato_plano_pessoa').val('');
        }
    }
    $(document).on('click', '#habilita_busca_contrato', function(){
        $('#id_contrato_plano_pessoa').val('');
        $('#busca_contrato').val('');
        $('#busca_contrato').attr("readonly", false);
        $('#busca_contrato').focus();
    });

    function seleciona_contrato(id_contrato_plano_pessoa){
        $.ajax({
            type: "GET",
            url: "/api/ajax?class=ArvoreContratoBusca.php",
            dataType: "json",
            data: {
                id_contrato_plano_pessoa: id_contrato_plano_pessoa,
                token: '<?= $request->token ?>'
            },
           
        });
    };


    $('#accordionRelatorio').on('shown.bs.collapse', function(){
       $("#i_collapse").removeClass("fa fa-plus").addClass("fa fa-minus");
    });

    $('#accordionRelatorio').on('hidden.bs.collapse', function(){
       $("#i_collapse").removeClass("fa fa-minus").addClass("fa fa-plus");
    });

    $(document).on('submit', 'form', function(){
        var perfil_sistema = "<?=$perfil_sistema?>";
        var pessoa = $("#pessoa").val();
        tipo_relatorio = $('#tipo_relatorio').val();
        
        if(tipo_relatorio == 2){
            if(perfil_sistema == 2 || perfil_sistema == 20){
                $('#row_pessoa').show();
            }
            if(!pessoa){
                if(perfil_sistema == 2 || perfil_sistema == 20){
                    alert("Deve-se selecionar alguma pessoa!");
                    return false;
                }
            
            }
            
        }
        
        modalAguarde();
        
    });

    $(document).ready(function(){
	    $('#aguarde').hide();
	    $('#resultado').show();
	    $("#gerar").prop("disabled", false);
	   	
	});  

    function preencheModal(id){

       function call_busca_ajax(id){
           busca_ajax('<?= $request->token ?>' , 'ChamadoTempoRelatorio', 'conteudo', id);
       }

       call_busca_ajax(id);

       $('#myModal3').modal('show');
    };

    $('#tipo_relatorio').on('change',function(){
        tipo_relatorio = $(this).val();
        perfil_sistema = "<?=$perfil_sistema?>";

        if(tipo_relatorio == 1){
            $('#row_origem').show();
            $('#row_visibilidade').show();
            $('#row_responsavel').show();
            $('#row_remetente').show();
            $('#row_status').show();
            $('#row_pessoa').hide();
            $('#row_ticket').show();
            $('#row_categoria').show();
            $('#row_perfil').show();
            $('#row_contrato').show();

        }else if(tipo_relatorio == 2){
            $('#row_origem').hide();
            $('#row_visibilidade').hide();
            $('#row_responsavel').hide();
            $('#row_remetente').hide();
            $('#row_status').show();
            $('#row_categoria').show();
            $('#row_pessoa').show();
            $('#row_ticket').hide();
            $('#row_perfil').hide();
            $('#row_contrato').show();

        }else if(tipo_relatorio == 3){
            $('#row_origem').show();
            $('#row_visibilidade').show();
            $('#row_responsavel').show();
            $('#row_remetente').show();
            $('#row_status').show();
            $('#row_pessoa').hide();
            $('#row_ticket').show();
            $('#row_categoria').show();
            $('#row_perfil').hide();
            $('#row_contrato').show();
        }else if(tipo_relatorio == 4){
            $('#row_origem').hide();
            $('#row_visibilidade').hide();
            $('#row_responsavel').show();
            $('#row_remetente').show();
            $('#row_status').show();
            $('#row_pessoa').hide();
            $('#row_ticket').show();
            $('#row_categoria').show();
            $('#row_perfil').hide();
            $('#row_contrato').show();
        }
    });   

</script>

<?php

function relatorio_chamado($origem, $data_de, $data_ate, $remetente, $status, $id_categoria, $visibilidade, $id_responsavel, $ticket, $id_perfil_sistema, $id_contrato_plano_pessoa){

    if($origem){
        $filtro_origem = "AND a.id_chamado_origem = '".$origem."'";
        $dados_origem = DBRead('','tb_chamado_origem',"WHERE id_chamado_origem = '".$origem."'");
        $status_legenda_origem = $dados_origem[0]['descricao'];
    }else{
        $status_legenda_origem = 'Qualquer';
    }
    if($visibilidade){
        $filtro_visibilidade = "AND a.visibilidade = '".$visibilidade."'";
        if($visibilidade == 1){
            $visibilidade = "Público";
        }else if($visibilidade == 2){
            $visibilidade = "Privado";
        }
    }else{
        $visibilidade = "Qualquer";
    }
    if($id_categoria){
        $filtro_categoria = "AND c.id_categoria = '".$id_categoria."'";
        $inner_categoria = "INNER JOIN tb_chamado_categoria c ON a.id_chamado = c.id_chamado";
        $dados_categoria = DBRead('','tb_categoria',"WHERE id_categoria = '".$id_categoria."'");
        $categoria = $dados_categoria[0]['nome'];
    }else{
        $categoria = 'Qualquer';
    }
    if($remetente){
        $filtro_remetente = "AND a.id_usuario_remetente = '".$remetente."'";
        $dados_remetente = DBRead('','tb_usuario a',"INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_usuario = '".$remetente."'");
        $remetente_nome = $dados_remetente[0]['nome'];
    }else{
        $remetente_nome = 'Qualquer';
    }
    if($status){
        $filtro_status = "AND a.id_chamado_status = '".$status."'";
        $status_legenda = DBRead('', 'tb_chamado_status', "WHERE id_chamado_status = '".$status."'");
        $status_legenda_descricao = $status_legenda[0]['descricao'];
    }else{
        $status_legenda_descricao = "Qualquer";
    }
    if($id_responsavel){
        $filtro_responsavel = "AND a.id_usuario_responsavel = '".$id_responsavel."'";
        $dados_responsavel = DBRead('','tb_usuario a',"INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_usuario = '".$id_responsavel."'");
        $responsavel_legenda_descricao = $dados_responsavel[0]['nome'];
    }else{
        $responsavel_legenda_descricao = 'Qualquer';
    }
    if(!$id_contrato_plano_pessoa){
        $status_legenda_contrato = "Qualquer";
    }else{
        $dados_contrato = DBRead('', 'tb_contrato_plano_pessoa a', "INNER JOIN tb_plano b ON a.id_plano = b.id_plano INNER JOIN tb_pessoa c ON a.id_pessoa = c.id_pessoa WHERE a.id_contrato_plano_pessoa = '$id_contrato_plano_pessoa'", "a.*, b.cod_servico, b.nome AS 'plano', c.nome AS 'nome_pessoa'");
        $nome_contrato = $dados_contrato[0]['nome_pessoa'];
        if($dados_contrato[0]['nome_contrato'] && $dados_contrato[0]['nome_contrato'] != ''){
            $nome_contrato .= " (".$dados_contrato[0]['nome_contrato'].")";
        }
        $status_legenda_contrato = $nome_contrato ." - " . getNomeServico($dados_contrato[0]['cod_servico']) . " - " . $dados_contrato[0]['plano'] . " (" . $dados_contrato[0]['id_contrato_plano_pessoa'] . ")";
        $filtro_contrato = "AND a.id_contrato_plano_pessoa = '".$id_contrato_plano_pessoa."'";
    }

    if($id_perfil_sistema){
        $usuarios = DBRead('',  'tb_usuario', "WHERE id_perfil_sistema = '".$id_perfil_sistema."'");
        $filtro_perfil = "";
        foreach($usuarios as $key => $value){
            if($key == 0){
                $filtro_perfil = "AND (a.id_usuario_responsavel = '".$usuarios[0]['id_usuario']."' OR a.id_usuario_remetente = '".$usuarios[0]['id_usuario']."' OR d.id_perfil_sistema = '".$usuarios[0]['id_perfil_sistema']."'";
            }else if($key != 0){
                $filtro_perfil .= " OR a.id_usuario_responsavel = '".$usuarios[$key]['id_usuario']."' OR a.id_usuario_remetente = '".$usuarios[$key]['id_usuario']."'";
            }
        }
        $nome_perfil = DBRead('', 'tb_perfil_sistema', "WHERE id_perfil_sistema = '$id_perfil_sistema'");
        $perfil_legenda_descricao = $nome_perfil[0]['nome'];
    }else{
        $perfil_legenda_descricao = 'Qualquer';
    }

	$data_hoje = getDataHora();
	$data_hoje = converteDataHora($data_hoje);

	$periodo_amostra = "<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> de $data_de até $data_ate</span>";
	$gerado = "<span style=\"font-size: 14px;\"><strong>Gerado em: </strong> $data_hoje</span>";

    if($ticket){
        $dados_chamado = DBRead('', 'tb_chamado a',"INNER JOIN tb_chamado_origem b ON a.id_chamado_origem = b.id_chamado_origem WHERE a.id_chamado = '".$ticket."'", "b.descricao AS origem, a.*");

        echo "<div class=\"col-md-12\" style=\"padding: 0\">";
        echo "<legend style=\"text-align:center;\"><strong>Relatório de Chamados</strong><br>$gerado</legend>";
        echo "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong> Ticket - </strong>#".$ticket."";
        echo "</legend>";
    }else{
        $data_de = converteData($data_de);
        $data_ate = converteData($data_ate);

        if($filtro_perfil){
            $dados_chamado = DBRead('', 'tb_chamado a',"INNER JOIN tb_chamado_origem b ON a.id_chamado_origem = b.id_chamado_origem $inner_categoria INNER JOIN tb_chamado_perfil d ON a.id_chamado = d.id_chamado WHERE a.data_criacao >= '".$data_de." 00:00:00' AND a.data_criacao <= '".$data_ate." 23:59:59' ".$filtro_remetente." ".$filtro_responsavel." ".$filtro_status." ".$filtro_origem." ".$filtro_visibilidade." ".$filtro_categoria." ".$filtro_contrato." ".$filtro_perfil.") GROUP BY a.id_chamado", "b.descricao AS origem, a.*");
        }else{
            $dados_chamado = DBRead('', 'tb_chamado a',"INNER JOIN tb_chamado_origem b ON a.id_chamado_origem = b.id_chamado_origem $inner_categoria WHERE a.data_criacao >= '".$data_de." 00:00:00' AND a.data_criacao <= '".$data_ate." 23:59:59' ".$filtro_remetente." ".$filtro_responsavel." ".$filtro_status." ".$filtro_origem." ".$filtro_visibilidade." ".$filtro_categoria." ".$filtro_contrato." GROUP BY a.id_chamado", "b.descricao AS origem, a.*");
        }

        echo "<div class=\"col-md-12\" style=\"padding: 0\">";
        echo "<legend style=\"text-align:center;\"><strong>Relatório de Chamados</strong><br>$gerado</legend>";
        echo "<legend style=\"text-align:center;\">$periodo_amostra</legend>";
        echo "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong>Contrato - </strong>".$status_legenda_contrato.", <strong> Origem - </strong>".$status_legenda_origem.", <strong>Status - </strong>".$status_legenda_descricao.", <strong> Visibilidade - </strong>".$visibilidade.", <strong>Remetente - </strong>".$remetente_nome.", <strong>Responsável - </strong>".$responsavel_legenda_descricao.", <strong>Perfil - </strong>".$perfil_legenda_descricao.", <strong>Categoria - </strong>".$categoria."";
        echo "</legend>";
    }

    if($dados_chamado){
    
        $total = 0;

        echo '<table class="table table-hover dataTable" style="margin-bottom:0;">
                <thead>
                    <tr>
                        <th class="text-left col-md-1">#</th>
                        <th class="text-left col-md-1">Idade</th>
                        <th class="text-left col-md-1">Categoria</th>
                        <th class="text-left col-md-1">Origem</th>
                        <th class="text-left col-md-2">Título</th>
                        <th class="text-left col-md-1">Status</th>
                        <th class="text-left col-md-1">Remetente</th>
                        <th class="text-left col-md-1">Responsável</th>
                        <th class="text-left col-md-1">Data/Hora</th>
                        <th class="text-left col-md-1">Contrato</th>
                        <th class="text-left col-md-1">Tempo</th>
                    </tr>
                </thead>
                <tbody>';

        foreach ($dados_chamado as $chamado){

            $dados_responsavel = DBRead('', 'tb_usuario a',"INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_usuario = '".$chamado['id_usuario_responsavel']."'");

            if($chamado['id_chamado_origem'] == '4'){
                $dados_remetente = DBRead('','tb_usuario_painel a',"INNER JOIN tb_pessoa b ON a.id_pessoa_usuario = b.id_pessoa WHERE a.id_usuario_painel = '".$chamado['id_usuario_remetente']."'");
            }else{
                $dados_remetente = DBRead('','tb_usuario a',"INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_usuario = '".$chamado['id_usuario_remetente']."'");
            }

            $dados_status = DBRead('', 'tb_chamado_acao a',"INNER JOIN tb_chamado_status b ON a.id_chamado_status = b.id_chamado_status WHERE a.id_chamado = '".$chamado['id_chamado']."' ORDER BY data DESC LIMIT 1");
            
            if($dados_status[0]['id_chamado_status'] == '1'){
                $dados_fim = getDataHora();
            }else{
                $dados_fim = $dados_status[0]['data'];
            }

            $data_inicio = $chamado['data_criacao'];

            $idade = calcula_idade_data($data_inicio, $dados_fim);

            $tempo_gasto = DBRead('', 'tb_chamado_acao', "WHERE id_chamado = '".$chamado['id_chamado']."'", "SUM(tempo) AS tempo_gasto");
            if($tempo_gasto[0]['tempo_gasto'] == '1'){
                $tempo = $tempo_gasto[0]['tempo_gasto'].' Minuto';
            }else{
                $tempo = $tempo_gasto[0]['tempo_gasto'].' Minutos';
            }
            
            $categorias = DBRead('', 'tb_chamado_categoria a', "INNER JOIN tb_categoria b ON a.id_categoria = b.id_categoria WHERE a.id_chamado = '".$chamado['id_chamado']."'");

            $aux_categoria = '';
            $cont_categoria = '0';
            foreach($categorias as $conteudo){
            $aux_categoria .= $conteudo['nome']."; <br>";
            $cont_categoria++;
            }
            $aux_categoria = substr($aux_categoria, 0, -6);

            ////////////// Bloco de validação de perfis e usuários liderados ////////////////
            $visualiza = "false";
            
            $dados_usuario_logado = DBRead('', 'tb_usuario', "WHERE id_usuario = '".$_SESSION['id_usuario']."'");

            $dados_usuarios_envolvidos = DBRead('', 'tb_chamado_usuario', "WHERE id_chamado = '".$chamado['id_chamado']."'");
            $liderados = busca_liderados($_SESSION['id_usuario']);
            //Verifica se estou envolvido no chamado
            foreach($dados_usuarios_envolvidos as $envolvido){
                if($_SESSION['id_usuario'] == $envolvido['id_usuario'] && $chamado['visibilidade'] == 2){
                    $visualiza = "true";
                }elseif(in_array($envolvido['id_usuario'], $liderados)){
                    $visualiza = "true";
                }
            }

            //REGRAS PARA CHAMADOS PÚBLICOS
            $dados_perfis_envolvidos = DBRead('', 'tb_chamado_perfil', "WHERE id_chamado = '".$chamado['id_chamado']."'");
            $perfis_liderados = busca_perfis_liderados($dados_usuario_logado[0]['id_perfil_sistema']);
            //Verifica se meu perfil envolvido no chamado
            foreach($dados_perfis_envolvidos as $envolvido){
                if($dados_usuario_logado[0]['id_perfil_sistema'] == $envolvido['id_perfil_sistema'] && $chamado['visibilidade'] == 1){
                    $visualiza = "true";
                }elseif(in_array($envolvido['id_perfil_sistema'], $perfis_liderados)){
                    $visualiza = "true";
                }
            }

            if(($_SESSION['id_usuario'] == $chamado['id_usuario_remetente'] && $chamado['id_chamado_origem'] != '4') || ($_SESSION['id_usuario'] == $chamado['id_usuario_responsavel'] && $chamado['id_chamado_origem'] != '4')){
                $visualiza = "true";
            }elseif(in_array($chamado['id_usuario_remetente'], $liderados) || in_array($chamado['id_usuario_responsavel'], $liderados)){
                $visualiza = "true";
            }
            ////////////////////////////////////////////////////////////////////////////////////////////
            //echo $chamado['id_chamado'].' - '.$visualiza.'<br>';
            if($visualiza == "true"){

                if($chamado['id_contrato_plano_pessoa'] != 0){
                    $dados_contrato2 = DBRead('', 'tb_contrato_plano_pessoa a', "INNER JOIN tb_plano b ON a.id_plano = b.id_plano INNER JOIN tb_pessoa c ON a.id_pessoa = c.id_pessoa WHERE a.id_contrato_plano_pessoa = '".$chamado['id_contrato_plano_pessoa']."' ", "a.*, b.cod_servico, b.nome AS 'plano', c.nome AS 'nome_pessoa'");
                    $nome_contrato2 = $dados_contrato2[0]['nome_pessoa'];
                    if($dados_contrato2[0]['nome_contrato'] && $dados_contrato2[0]['nome_contrato'] != ''){
                        $nome_contrato2 .= " (".$dados_contrato2[0]['nome_contrato'].")";
                    }
                    $nome_contrato_tabela = $nome_contrato2 ." - " . getNomeServico($dados_contrato2[0]['cod_servico']) . " - " . $dados_contrato2[0]['plano'] . " (" . $dados_contrato2[0]['id_contrato_plano_pessoa'] . ")";
                }else{
                    $nome_contrato_tabela = "";
                }
                echo '<tr>
                    <td class="text-left" style="vertical-align: middle;">'.$chamado['id_chamado'].'</td>
                    <td class="text-left" style="vertical-align: middle;">'.$idade.'</td>
                    <td class="text-left" style="vertical-align: middle;">'.$aux_categoria.'</td>
                    <td class="text-left" style="vertical-align: middle;">'.$chamado['origem'].'</td>
                    <td class="text-left" style="vertical-align: middle;">'.$chamado['titulo'].'</td>
                    <td class="text-left" style="vertical-align: middle;">'.$dados_status[0]['descricao'].'</td>
                    <td class="text-left" style="vertical-align: middle;">'.$dados_remetente[0]['nome'].'</td>
                    <td class="text-left" style="vertical-align: middle;">'.$dados_responsavel[0]['nome'].'</td>
                    <td class="text-left" style="vertical-align: middle;">'.converteDataHora($dados_status[0]['data']).'</td>
                    <td class="text-left" style="vertical-align: middle;">'.$nome_contrato_tabela.'</td>
                    <td class="text-left" style="vertical-align: middle;"><a class="text-center"><a href="#" data-toggle="modal modal" onclick="preencheModal('.$chamado['id_chamado'].')" title="Visualizar">'.$tempo.'        <i class="fa fa-eye"></i></a></td>
                </tr>';
                $total = $total + $tempo;
            }
        }
            
        echo '
        </tbody>
        <tfoot>
                <tr>
                <th>Totais</th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th>'.$total.' minutos ('.converteSegundosHoras($total*60).')</th>
                </tr>
        </tfoot>';
        
        echo '</table>

        <br><br><br>';

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

function relatorio_pessoa($data_de, $data_ate, $id_categoria, $pessoa, $status, $id_contrato_plano_pessoa){

    if(!$pessoa){
        $pessoa = $_SESSION['id_usuario'];
    }
        $pessoa_nome = DBRead('','tb_usuario a',"INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_usuario = '".$pessoa."'");
        $pessoa_nome = $pessoa_nome[0]['nome'];
        $filtro_pessoa = "AND a.id_usuario_acao = '".$pessoa."'";
    
    if(!$id_categoria){
        $categoria = 'Todas';
    }else{
        $dados_categoria = DBRead('','tb_categoria',"WHERE id_categoria = '".$id_categoria."'");
        $categoria = $dados_categoria[0]['nome'];
        $inner_categoria = "INNER JOIN tb_chamado_categoria c on a.id_chamado = c.id_chamado";
        $filtro_categoria = "AND c.id_categoria = '".$id_categoria."'";
    }
    if(!$status){
        $status_legenda_descricao = "Todos";
    }else{
        $status_legenda = DBRead('', 'tb_chamado_status', "WHERE id_chamado_status = '".$status."'");
        $status_legenda_descricao = $status_legenda[0]['descricao'];
        $filtro_status = "AND b.id_chamado_status = '".$status."'";
    }
    if(!$id_contrato_plano_pessoa){
        $status_legenda_contrato = "Qualquer";
    }else{
        $dados_contrato = DBRead('', 'tb_contrato_plano_pessoa a', "INNER JOIN tb_plano b ON a.id_plano = b.id_plano INNER JOIN tb_pessoa c ON a.id_pessoa = c.id_pessoa WHERE a.id_contrato_plano_pessoa = '$id_contrato_plano_pessoa'", "a.*, b.cod_servico, b.nome AS 'plano', c.nome AS 'nome_pessoa'");
        $nome_contrato = $dados_contrato[0]['nome_pessoa'];
        if($dados_contrato[0]['nome_contrato'] && $dados_contrato[0]['nome_contrato'] != ''){
            $nome_contrato .= " (".$dados_contrato[0]['nome_contrato'].")";
        }
        $status_legenda_contrato = $nome_contrato ." - " . getNomeServico($dados_contrato[0]['cod_servico']) . " - " . $dados_contrato[0]['plano'] . " (" . $dados_contrato[0]['id_contrato_plano_pessoa'] . ")";
        $filtro_contrato = "AND b.id_contrato_plano_pessoa = '".$id_contrato_plano_pessoa."'";
    }

    $data_hoje = getDataHora();
    $data_hoje = converteDataHora($data_hoje);

    $periodo_amostra = "<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> de $data_de até $data_ate</span>";
    $gerado = "<span style=\"font-size: 14px;\"><strong>Gerado em: </strong> $data_hoje</span>";

    echo "<div class=\"col-md-12\" style=\"padding: 0\">";
    echo "<legend style=\"text-align:center;\"><strong>Relatório de Chamados - Pessoas</strong><br>$gerado</legend>";
    echo "<legend style=\"text-align:center;\">$periodo_amostra</legend>";
    echo "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong>Contrato - </strong>".$status_legenda_contrato.", <strong>Pessoa - </strong>".$pessoa_nome.",<strong> Categoria - </strong>".$categoria.", <strong>Status - </strong>".$status_legenda_descricao."";
    echo "</legend>";

    $data_de = converteData($data_de);
    $data_ate = converteData($data_ate);

    $dados_chamado = DBRead('', 'tb_chamado_acao a',"INNER JOIN tb_chamado b on a.id_chamado = b.id_chamado $inner_categoria INNER JOIN tb_chamado_status d on b.id_chamado_status = d.id_chamado_status WHERE a.data >= '".$data_de." 00:00:00' AND a.data <= '".$data_ate." 23:59:59' $filtro_status $filtro_categoria $filtro_pessoa $filtro_contrato"," b.*, a.*, d.descricao AS descricao_status, a.data AS data_acao");

    if($dados_chamado){
    echo '<div class="col-md-12">';
        echo '<table class="table table-hover dataTable" style="margin-bottom:0;">
                  <thead>
                    <tr>
                        <th class="text-left col-md-1">#</th>
                        <th class="text-left col-md-1">Título do Chamado</th>
                        <th class="text-left col-md-1">Categoria do Chamado</th>
                        <th class="text-left col-md-2">Status Atual</th>
                        <th class="text-left col-md-2">Ação Realizada</th>
                        <th class="text-left col-md-1">Data/Hora da Ação Realizada</th>
                        <th class="text-left col-md-1">Contrato</th>
                        <th class="text-left col-md-1">Tempo do Usuário na Ação</th>
                    </tr>
                  </thead>
                  <tbody>';

        $array_chamados = array();
        $total_tempo = 0;
        $total_acoes = 0;
        foreach ($dados_chamado as $chamado) {           
            
            $categorias = DBRead('', 'tb_chamado_categoria a', "INNER JOIN tb_categoria b ON a.id_categoria = b.id_categoria WHERE a.id_chamado = '".$chamado['id_chamado']."'");

            $aux_categoria = '';

            foreach($categorias as $conteudo){
                $aux_categoria .= $conteudo['nome'].", <br>";
            }
            $aux_categoria = substr($aux_categoria, 0, -6);
            
            if($chamado['tempo'] == 1){
                $tempo = $chamado['tempo']." minuto";
            }else{
                $tempo = $chamado['tempo']." minutos";
            }

             if($chamado['acao'] == 'nota_interna'){
                $acao_descricao = "Nota interna adicionada";
            }else if($chamado['acao'] == 'encerrar'){
                $acao_descricao = "Chamado encerrado";
            }else if($chamado['acao'] == 'encaminhar'){
                $acao_descricao = "Troca de responsável";
            }else if($chamado['acao'] == 'desbloquear'){
                $acao_descricao = "Chamado desbloqueado";
            }else if($chamado['acao'] == 'bloquear'){
                $acao_descricao = "Chamado bloqueado";
            }else if($chamado['acao'] == 'reabrir'){
                $acao_descricao = "Chamado reaberto";
            }else if($chamado['acao'] == 'gerenciar'){
                $acao_descricao = "Gerenciamento dos envolvidos";
            }else if($chamado['acao'] == 'nota_geral'){
                $acao_descricao = "Nota adicionada";
            }else if($chamado['acao'] == 'alterar'){
                $acao_descricao = "Alteração do Chamado";
            }else{
                $acao_descricao = "Criação do chamado";
            }     

            ////////////// Bloco de validação de perfis e usuários liderados ////////////////
            $visualiza = "false";
            
            $dados_usuario_remetente = DBRead('','tb_usuario a',"INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_usuario = '".$chamado['id_usuario_remetente']."'");
            $dados_usuario_logado = DBRead('', 'tb_usuario', "WHERE id_usuario = '".$_SESSION['id_usuario']."'");
            $dados_usuario_responsavel = DBRead('', 'tb_usuario', "WHERE id_usuario = '".$chamado['id_usuario_responsavel']."'");
            
            //REGRAS PARA CHAMADOS PRIVADOS
            $dados_usuarios_envolvidos = DBRead('', 'tb_chamado_usuario', "WHERE id_chamado = '".$chamado['id_chamado']."'");

            //Verifica se estou envolvido no chamado
            foreach($dados_usuarios_envolvidos as $key => $envolvido){
                if($_SESSION['id_usuario'] == $dados_usuarios_envolvidos[$key]['id_usuario'] && $chamado['visibilidade'] == 2){
                    $visualiza = "true";
                }
            }

            //Verifica usuários que tem ação no chamado
            if($_SESSION['id_usuario'] == $chamado['id_usuario_acao']){
                $visualiza = "true";
            }
            
            //REGRAS PARA CHAMADOS PÚBLICOS
            $perfil_lider = busca_perfis_liderados($dados_usuario_logado[0]['id_perfil_sistema']);
            //Obtenho todos os perfis abaixo do usuário logado
            foreach($perfil_lider as $conteudo){
                //Deve verificar o perfil do remetente ou responsável, e não se  perfil subordinado é igual ao id do remetente ou responsavel e visibilidade publica
                if((intval($conteudo) == intval($dados_usuario_remetente[0]['id_perfil_sistema']) || intval($conteudo) == intval($dados_usuario_responsavel[0]['id_perfil_sistema'])) && $chamado['visibilidade'] == 1){
                    $visualiza = "true";
                }
            }

            $liderados = array();
            
            $usuario_lider = busca_liderados($_SESSION['id_usuario'], $liderados);
            foreach($usuario_lider as $conteudo){
                //Deve verificar o perfil do remetente ou responsável, e não se perfil subordinado é igual ao id do remetente ou responsavel e visibilidade publica
                if((intval($conteudo) == intval($chamado['id_usuario_remetente']) || intval($conteudo) == intval($chamado['id_usuario_responsavel'])) && $chamado['visibilidade'] == 1){
                    $visualiza = "true";
                }
            }
            ////////////////////////////////////////////////////////////////////////////////////////////
            if($visualiza == "true"){
                if(!in_array($chamado['id_chamado'],$array_chamados)){
                    $array_chamados[] = $chamado['id_chamado'];
                }

                if($chamado['id_contrato_plano_pessoa'] != 0){
                    $dados_contrato2 = DBRead('', 'tb_contrato_plano_pessoa a', "INNER JOIN tb_plano b ON a.id_plano = b.id_plano INNER JOIN tb_pessoa c ON a.id_pessoa = c.id_pessoa WHERE a.id_contrato_plano_pessoa = '".$chamado['id_contrato_plano_pessoa']."' ", "a.*, b.cod_servico, b.nome AS 'plano', c.nome AS 'nome_pessoa'");
                    $nome_contrato2 = $dados_contrato2[0]['nome_pessoa'];
                    if($dados_contrato2[0]['nome_contrato'] && $dados_contrato2[0]['nome_contrato'] != ''){
                        $nome_contrato2 .= " (".$dados_contrato2[0]['nome_contrato'].")";
                    }
                    $nome_contrato_tabela = $nome_contrato2 ." - " . getNomeServico($dados_contrato2[0]['cod_servico']) . " - " . $dados_contrato2[0]['plano'] . " (" . $dados_contrato2[0]['id_contrato_plano_pessoa'] . ")";
                }else{
                    $nome_contrato_tabela = "";
                }
                
                echo '<tr>
                          <td class="text-left" style="vertical-align: middle;">'.$chamado['id_chamado'].'</td>
                          <td class="text-left" style="vertical-align: middle;">'.$chamado['titulo'].'</td>
                          <td class="text-left" style="vertical-align: middle;">'.$aux_categoria.'</td>
                          <td class="text-left" style="vertical-align: middle;">'.$chamado['descricao_status'].'</td>
                          <td class="text-left" style="vertical-align: middle;">'.$acao_descricao.'</td>
                          <td class="text-left" style="vertical-align: middle;">'.converteDataHora($chamado['data_acao']).'</td>
                          <td class="text-left" style="vertical-align: middle;">'.$nome_contrato_tabela.'</td>
                          <td class="text-left" style="vertical-align: middle;">'.$tempo.'</td>
                      </tr>';
                $total_acoes ++;
                $total_tempo += $tempo;
            }
                    
        }
            
                echo '      
                      </tbody>';
                       echo "<tfoot>";
                            echo '<tr>'; 
                                echo '<th class="text-right" colspan="8"> Tempo total trabalhado: '.$total_tempo.' minutos ('.converteSegundosHoras($total_tempo*60).')</th>';
                            echo '</tr>';
                            echo '<tr>';   
                                echo '<th class="text-right" colspan="8">Total de ações realizadas: '.$total_acoes.'</th>';
                            echo '</tr>';
                            echo '<tr>';      
                                echo '<th class="text-right" colspan="8">Total de chamados envolvidos: '.sizeof($array_chamados).'</th>';
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
                                \"searching\": false,
                                \"paging\":   false,
                                \"info\":     false
                            });
                        });
                    </script>           
                    ";
    echo '</div>';
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

function relatorio_detalhado($origem, $data_de, $data_ate, $remetente, $status, $id_categoria, $visibilidade, $id_responsavel, $ticket, $id_contrato_plano_pessoa){

    if($origem){
        $filtro_origem = "AND a.id_chamado_origem = '".$origem."'";
        $dados_origem = DBRead('','tb_chamado_origem',"WHERE id_chamado_origem = '".$origem."'");
        $status_legenda_origem = $dados_origem[0]['descricao'];
    }else{
        $status_legenda_origem = 'Qualquer';
    }

    if($visibilidade){
        $filtro_visibilidade = "AND a.visibilidade = '".$visibilidade."'";
        if($visibilidade == 1){
            $visibilidade = "Público";
        }else if($visibilidade == 2){
            $visibilidade = "Privado";
        }
    }else{
        $visibilidade = "Qualquer";
    }

    if($id_categoria){
        $inner_categoria = "INNER JOIN tb_chamado_categoria d ON a.id_chamado = d.id_chamado";
        $filtro_categoria = "AND d.id_categoria = '".$id_categoria."'";
        $dados_categoria = DBRead('','tb_categoria',"WHERE id_categoria = '".$id_categoria."'");
        $categoria = $dados_categoria[0]['nome'];

    }else{
        $categoria = 'Qualquer';
    }

    if($remetente){
        $filtro_remetente = "AND a.id_usuario_remetente = '".$remetente."'";
        $dados_remetente = DBRead('','tb_usuario a',"INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_usuario = '".$remetente."'");
        $remetente_nome = $dados_remetente[0]['nome'];
    }else{
        $remetente_nome = 'Qualquer';
    }

    if($status){
        $filtro_status = "AND a.id_chamado_status = '".$status."'";
        $status_legenda = DBRead('', 'tb_chamado_status', "WHERE id_chamado_status = '".$status."'");
        $status_legenda_descricao = $status_legenda[0]['descricao'];
    }else{
        $status_legenda_descricao = "Qualquer";
    }

    if($id_responsavel){
        $filtro_responsavel = "AND a.id_usuario_responsavel = '".$id_responsavel."'";
        $dados_responsavel = DBRead('','tb_usuario a',"INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_usuario = '".$id_responsavel."'");
        $responsavel_legenda_descricao = $dados_responsavel[0]['nome'];
    }else{
        $responsavel_legenda_descricao = 'Qualquer';
    }

    if(!$id_contrato_plano_pessoa){
        $status_legenda_contrato = "Qualquer";
    }else{
        $dados_contrato = DBRead('', 'tb_contrato_plano_pessoa a', "INNER JOIN tb_plano b ON a.id_plano = b.id_plano INNER JOIN tb_pessoa c ON a.id_pessoa = c.id_pessoa WHERE a.id_contrato_plano_pessoa = '$id_contrato_plano_pessoa'", "a.*, b.cod_servico, b.nome AS 'plano', c.nome AS 'nome_pessoa'");
        $nome_contrato = $dados_contrato[0]['nome_pessoa'];
        if($dados_contrato[0]['nome_contrato'] && $dados_contrato[0]['nome_contrato'] != ''){
            $nome_contrato .= " (".$dados_contrato[0]['nome_contrato'].")";
        }
        $status_legenda_contrato = $nome_contrato ." - " . getNomeServico($dados_contrato[0]['cod_servico']) . " - " . $dados_contrato[0]['plano'] . " (" . $dados_contrato[0]['id_contrato_plano_pessoa'] . ")";
        $filtro_contrato = "AND a.id_contrato_plano_pessoa = '".$id_contrato_plano_pessoa."'";
    }

    $data_hoje = getDataHora();
    $data_hoje = converteDataHora($data_hoje);

    $periodo_amostra = "<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> de $data_de até $data_ate</span>";
    $gerado = "<span style=\"font-size: 14px;\"><strong>Gerado em: </strong> $data_hoje</span>";

    $data_de = converteData($data_de);
    $data_ate = converteData($data_ate);

    if($ticket){
        $dados_chamado = DBRead('', 'tb_chamado a',"INNER JOIN tb_chamado_origem b ON a.id_chamado_origem = b.id_chamado_origem INNER JOIN tb_chamado_status c ON a.id_chamado_status = c.id_chamado_status INNER JOIN tb_usuario e ON a.id_usuario_responsavel = e.id_usuario INNER JOIN tb_pessoa f ON e.id_pessoa = f.id_pessoa WHERE a.id_chamado = '".$ticket."'", "b.descricao AS descricao_origem, c.descricao AS descricao_status, f.nome AS nome_responsavel, a.*");

        echo "<div class=\"col-md-8 col-md-offset-2\" style=\"padding: 0\">";
        echo "<legend style=\"text-align:center;\"><strong>Relatório de Chamados Detalhados</strong><br>$gerado</legend>";
        echo "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong> Ticket - </strong>#".$ticket;
        echo "</legend>";

    }else{
       $dados_chamado = DBRead('', 'tb_chamado a',"INNER JOIN tb_chamado_origem b ON a.id_chamado_origem = b.id_chamado_origem INNER JOIN tb_chamado_status c ON a.id_chamado_status = c.id_chamado_status $inner_categoria INNER JOIN tb_usuario e ON a.id_usuario_responsavel = e.id_usuario INNER JOIN tb_pessoa f ON e.id_pessoa = f.id_pessoa WHERE a.data_criacao >= '".$data_de." 00:00:00' AND a.data_criacao <= '".$data_ate." 23:59:59' ".$filtro_origem." ".$filtro_status." ".$filtro_visibilidade." ".$filtro_responsavel." ".$filtro_remetente." ".$filtro_categoria." ".$filtro_contrato." ORDER BY a.id_chamado DESC", "b.descricao AS descricao_origem, c.descricao AS descricao_status, f.nome AS nome_responsavel, a.*"); 
      
       echo "<div class=\"col-md-8 col-md-offset-2\" style=\"padding: 0\">";
       echo "<legend style=\"text-align:center;\"><strong>Relatório de Chamados Detalhados</strong><br>$gerado</legend>";
       echo "<legend style=\"text-align:center;\">$periodo_amostra</legend>";
       echo "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong>Contrato - </strong>".$status_legenda_contrato.",<strong> Origem - </strong>".$status_legenda_origem.", <strong>Status - </strong>".$status_legenda_descricao.", <strong> Visibilidade - </strong>".$visibilidade.", <strong>Remetente - </strong>".$remetente_nome.", <strong>Responsável - </strong>".$responsavel_legenda_descricao.", <strong>Categoria - </strong>".$categoria."";
        echo "</legend>";
    }

    if($dados_chamado){
        
        foreach($dados_chamado as $dado){

            if($dado['id_chamado_origem'] == '4'){
                $dados_usuario_remetente = DBRead('','tb_usuario_painel a',"INNER JOIN tb_pessoa b ON a.id_pessoa_usuario = b.id_pessoa WHERE a.id_usuario_painel = '".$dado['id_usuario_remetente']."'");
            }else{
                $dados_usuario_remetente = DBRead('','tb_usuario a',"INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_usuario = '".$dado['id_usuario_remetente']."'");
            }
            
            ////////////// Bloco de validação de perfis e usuários liderados ////////////////
            $visualiza = "false";
            
            $dados_usuario_logado = DBRead('', 'tb_usuario', "WHERE id_usuario = '".$_SESSION['id_usuario']."'");

            $dados_usuarios_envolvidos = DBRead('', 'tb_chamado_usuario', "WHERE id_chamado = '".$dado['id_chamado']."'");
            $liderados = busca_liderados($_SESSION['id_usuario']);
            //Verifica se estou envolvido no chamado
            foreach($dados_usuarios_envolvidos as $envolvido){
                if($_SESSION['id_usuario'] == $envolvido['id_usuario'] && $dado['visibilidade'] == 2){
                    $visualiza = "true";
                }elseif(in_array($envolvido['id_usuario'], $liderados)){
                    $visualiza = "true";
                }
            }

            //REGRAS PARA CHAMADOS PÚBLICOS
            $dados_perfis_envolvidos = DBRead('', 'tb_chamado_perfil', "WHERE id_chamado = '".$dado['id_chamado']."'");
            $perfis_liderados = busca_perfis_liderados($dados_usuario_logado[0]['id_perfil_sistema']);
            //Verifica se meu perfil envolvido no chamado
            foreach($dados_perfis_envolvidos as $envolvido){
                if($dados_usuario_logado[0]['id_perfil_sistema'] == $envolvido['id_perfil_sistema'] && $dado['visibilidade'] == 1){
                    $visualiza = "true";
                }elseif(in_array($envolvido['id_perfil_sistema'], $perfis_liderados)){
                    $visualiza = "true";
                }
            }

            if(($_SESSION['id_usuario'] == $dado['id_usuario_remetente'] && $dado['id_chamado_origem'] != '4') || $_SESSION['id_usuario'] == $dado['id_usuario_responsavel']){
                $visualiza = "true";
            }elseif((in_array($dado['id_usuario_remetente'], $liderados) && $dado['id_chamado_origem'] != '4') || in_array($dado['id_usuario_responsavel'], $liderados)){
                $visualiza = "true";
            }
            ////////////////////////////////////////////////////////////////////////////////////////////
            
            //Verifica se o chamado é de um perfil abaixo do perfil do usuário logado
            if($visualiza == "true"):
            ?>
            <div class="panel panel-primary">
                <div class="panel-heading clearfix">
                    <div class="row">
                        <h3 class="panel-title text-left col-md-1"><strong>#</strong> <?= $dado['id_chamado'] ?></h3>
                        <h3 class="panel-title text-left col-md-3"><strong>Título:</strong> <?= $dado['titulo'] ?></h3>
                        <h3 class="panel-title text-right col-md-4"><strong>Remetente:</strong> <?= $dados_usuario_remetente[0]['nome'] ?></h3>
                        <h3 class="panel-title text-right col-md-4"><strong>Data de Criação:</strong> <?= converteDataHora($dado['data_criacao']) ?></h3>
                    </div>
                </div>
                <div class="panel-body painel-body">
                        
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            <strong>Chamado:</strong>
                        </div>
                        <div class="panel-body">
                           <div class="row">
                            <div class="col-md-4" style="margin-left: -5px;">
                                <table class="table table-hover">
                                    <tbody>
                                        <tr>
                                            <td class="td-table col-md-4 text-left" style= "border-top: 0 !important"><strong>Origem:</strong> <?=$dado['descricao_origem']?></td>
                                        </tr>
                                        <tr>
                                            <td class="td-table col-md-4 text-left" style= "border-top: 0 !important"><strong>Status:</strong> <?=$dado['descricao_status']?></td>
                                        </tr>
                                        <tr>
                                          <?php
                                            $visibilidade = array(
                                              "1" => "Público",
                                              "2" => "Privado"
                                            );
                                            ?>
                                            <td class="td-table col-md-4 text-left" style= "border-top: 0 !important"><strong>Visibilidade:</strong> <?=$visibilidade[$dado['visibilidade']]?></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div><!-- end col -->

                            <div class="col-md-4" style="margin-left: -5px;">
                                <table class="table table-hover">
                                    <tbody>
                                        <tr>
                                            <td class="td-table" style= "border-top: 0 !important"><strong>Responsável:</strong> <?=$dado['nome_responsavel']?></td>
                                        </tr>
                                        <tr>
                                            <?php
                                                $categorias = DBRead('', 'tb_chamado_categoria a', "INNER JOIN tb_categoria b ON a.id_categoria = b.id_categoria WHERE a.id_chamado = '".$dado['id_chamado']."'");

                                                $aux_categoria = '';
                                                $cont_categoria = '0';
                                                foreach($categorias as $conteudo){
                                                   $aux_categoria .= $conteudo['nome']."; <br>";
                                                   $cont_categoria++;
                                                }
                                                
                                            ?>
                                            <td class="td-table" style= "border-top: 0 !important"><strong>Categoria:</strong> <?=$aux_categoria?></td>
                                        </tr>
                                        <tr>
                                            <?php 
                                            $tempo_gasto = DBRead('', 'tb_chamado_acao', "WHERE id_chamado = '".$dado['id_chamado']."'", "SUM(tempo) AS tempo_gasto"); 
                                            $tempo = explode(':',converteSegundosHoras($tempo_gasto[0]['tempo_gasto'] * 60));
                                            $horas = intval($tempo[0]);
                                            $minutos = intval($tempo[1]);
                                            
                                            if($horas < '1'){
                                                $tempo_total = $minutos." m";
                                            }else{
                                                $tempo_total = $horas." h ".$minutos." m";
                                            }
                                            ?>
                                            <td class='td-table' style= "border-top: 0 !important"><strong>Tempo total:</strong> <?= $tempo_total?></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div><!-- end col -->

                            <div class="col-md-4" >
                                <table class="table table-hover">
                                    <tbody>                                       
                                        <tr>
                                          <?php
                                          if($dado['visibilidade'] == 1){

                                            $envolvidos = DBRead('', 'tb_chamado_perfil a', "INNER JOIN tb_perfil_sistema b ON a.id_perfil_sistema = b.id_perfil_sistema WHERE a.id_chamado = '".$dado['id_chamado']."'");

                                            $aux_envolvidos = '';
                                            $cont_envolvidos = '0';
                                            foreach($envolvidos as $conteudo){
                                               $aux_envolvidos .= $conteudo['nome']."; <br>";
                                               $cont_envolvidos++;
                                            }
                                            if($cont_envolvidos == '1'){
                                                $aux_nome_envolvidos = 'Setor Envolvido';
                                            }else{
                                                $aux_nome_envolvidos = 'Setores Envolvidos';
                                            }
                                          }else{

                                            $envolvidos = DBRead('', 'tb_chamado_usuario a', "INNER JOIN tb_usuario b ON a.id_usuario = b.id_usuario INNER JOIN tb_pessoa c ON b.id_pessoa = c.id_pessoa WHERE a.id_chamado = '".$dado['id_chamado']."'");

                                            $aux_envolvidos = '';
                                            $cont_envolvidos = '0';
                                            foreach($envolvidos as $conteudo){
                                               $aux_envolvidos .= $conteudo['nome']."; <br>";
                                               $cont_envolvidos++;
                                            }
                                            $aux_envolvidos = substr_replace($aux_envolvidos, '', -6);

                                            if($cont_envolvidos == '1'){
                                                $aux_nome_envolvidos = 'Usuário Envolvido';
                                            }else{
                                                $aux_nome_envolvidos = 'Usuários Envolvidos';
                                            }                                          }
                                          ?>
                                            <td class="td-table" style= "border-top: 0 !important"><strong><?= $aux_nome_envolvidos?>:</strong> <?= $aux_envolvidos?></td>
                                        </tr>

                                        <?php
                                        if($dado['id_contrato_plano_pessoa'] != 0){
                                            $dados_contrato2 = DBRead('', 'tb_contrato_plano_pessoa a', "INNER JOIN tb_plano b ON a.id_plano = b.id_plano INNER JOIN tb_pessoa c ON a.id_pessoa = c.id_pessoa WHERE a.id_contrato_plano_pessoa = '".$dado['id_contrato_plano_pessoa']."' ", "a.*, b.cod_servico, b.nome AS 'plano', c.nome AS 'nome_pessoa'");
                                            $nome_contrato2 = $dados_contrato2[0]['nome_pessoa'];
                                            if($dados_contrato2[0]['nome_contrato'] && $dados_contrato2[0]['nome_contrato'] != ''){
                                                $nome_contrato2 .= " (".$dados_contrato2[0]['nome_contrato'].")";
                                            }
                                            $nome_contrato_tabela = $nome_contrato2 ." - " . getNomeServico($dados_contrato2[0]['cod_servico']) . " - " . $dados_contrato2[0]['plano'] . " (" . $dados_contrato2[0]['id_contrato_plano_pessoa'] . ")";
                                            
                                            echo '
                                                <tr>
                                                    <td class="td-table" style= "border-top: 0 !important"><strong>Contrato: </strong>'.$nome_contrato_tabela.'</td>
                                                </tr>
                                            ';
                                        }
                                        ?>

                                    </tbody>
                                </table>

                            </div><!-- end col -->
                            <div class="col-md-12" style="margin-left: -5px;">
                                <table class="table table-hover">
                                    <tbody>
                                        <tr>
                                            <td class="td-table col-md-3 conteudo-editor"><strong>Descrição: </strong> <?=$dado['descricao']?></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div><!-- end col -->

                        </div><!-- end row -->
                        <hr>

                        </div>
                    </div>
                    <?php
                    //$dados_comentario = DBRead('', 'tb_topico', "WHERE status != 2 AND id_pai = '".$dado['id_topico']."'");

                    //if($dados_comentario){
                    
                    $chamados_acao = DBRead('', 'tb_chamado_acao a', "INNER JOIN tb_chamado_status b ON a.id_chamado_status = b.id_chamado_status INNER JOIN tb_usuario c ON a.id_usuario_responsavel = c.id_usuario INNER JOIN tb_pessoa d ON c.id_pessoa = d.id_pessoa INNER JOIN tb_usuario e ON a.id_usuario_acao = e.id_usuario INNER JOIN tb_pessoa f ON e.id_pessoa = f.id_pessoa WHERE id_chamado = '".$dado['id_chamado']."' AND a.acao != 'criacao' ORDER BY a.data ASC", "b.descricao AS descricao_status, d.nome AS nome_responsavel, f.nome AS nome_usuario_acao, a.*");
                    if($chamados_acao){

                    ?>
                         
                    <div class="panel panel-warning">
                        <div class="panel-heading">
                            <strong>Ações do Chamado:</strong>
                        </div>
                        <div class="panel-body">
                            <?php
                                
                              $contador = 1;
                              foreach($chamados_acao as $conteudo):                                      
                                if($conteudo['acao'] == "criacao"){
                                    $acao = "Criação do chamado";
                                    $icone = "<i class='fa fa-tag' aria-hidden='true'></i>";
                                    
                                    $css = 'border-left: 5px solid #265a88 !important;';
                                }else if($conteudo['acao'] == "encerrar"){
                                    $acao = "Chamado encerrado";
                                    if($conteudo['id_chamado_status'] == 3){
                                        $icone = "<i class='fa fa-check' aria-hidden='true'></i>";

                                        $css = 'border-left: 5px solid #59ba1f !important;';
                                    }
                                    if($conteudo['id_chamado_status'] == 4){
                                        $icone = "<i class='fa fa-check' aria-hidden='true'></i>";

                                        $css = 'border-left: 5px solid #ba1f1f !important;';
                                    }
                                }else if($conteudo['acao'] == "encaminhar"){
                                    $acao = "Troca de responsável";
                                    $icone = "<i class='fa fa-exchange' aria-hidden='true'></i>";

                                    $css = 'border-left: 5px solid #FFC125 !important;';
                                }else if($conteudo['acao'] == "nota_geral"){
                                    $acao = "Nota adicionada";
                                    $icone = "<i class='fa fa-file' aria-hidden='true'></i>";

                                    $css = 'border-left: 5px solid #5bc0de !important;';
                                }else if($conteudo['acao'] == "nota_interna"){
                                    $acao = "Nota interna adicionada";
                                    $icone = "<i class='fa fa-file' aria-hidden='true'></i>";

                                    $css = 'border-left: 5px solid #363636 !important;';
                                }else if($conteudo['acao'] == "desbloquear"){
                                    $acao = "Chamado desbloqueado";
                                    $icone = "<i class='fa fa-unlock' aria-hidden='true'></i>";

                                    $css = 'border-left: 5px solid #DF7401 !important;';
                                }else if($conteudo['acao'] == "bloquear"){
                                    $acao = "Chamado bloqueado";
                                    $icone = "<i class='fa fa-lock' aria-hidden='true'></i>";

                                    $css = 'border-left: 5px solid #DF7401 !important;';
                                }else if($conteudo['acao'] == "reabrir"){
                                    $acao = "Chamado reaberto";
                                    $icone = "<i class='fa fa-undo' aria-hidden='true'></i>";

                                    $css = 'border-left: 5px solid #265a88 !important;';
                                }else if($conteudo['acao'] == "gerenciar"){
                                    $acao = "Gerenciamento dos envolvidos";
                                    $icone = "<i class='fa fa-cog' aria-hidden='true'></i>";
                                    
                                    $css = 'border-left: 5px solid #20B2AA !important;';
                                }else if($conteudo['acao'] == "alterar"){
                                    $acao = "Alteração do chamado";
                                    $icone = "<i class='fa fa-edit' aria-hidden='true'></i>";
                                    
                                    $css = 'border-left: 5px solid #9370DB
                                    !important;';
                                }else if($conteudo['acao'] == "assumir"){
                                    $acao = "Assumiu responsabilidade";
                                    $icone = "<i class='fa fa-exchange' aria-hidden='true'></i>";

                                    $css = 'border-left: 5px solid #FFC125 !important;';
                                }else if($conteudo['acao'] == "pendencia"){
                                    $data_pendencia =  DBRead('', 'tb_chamado_pendencia', "WHERE id_chamado_acao = '".$conteudo['id_chamado_acao']."'");

                                    $acao = "Adicionada uma pendência (".converteDataHora($data_pendencia[0]['data']).")";
                                    $icone = "<i class='fa fa-calendar-minus-o' aria-hidden='true'></i>";

                                    $css = 'border-left: 5px solid #EE8262 !important;';
                                }
                                
                                echo '<span class="timeline-title" style="font-size: 15px; "><strong>&nbsp;&nbsp;&nbsp;&nbsp; '.$contador.'</strong> - <strong>' .$acao.'</strong>  '.$icone.' </span>';

                                    ?>
                                    <div class="panel panel" style="<?= $css?>">
                                        <div class="panel-body">
                                            <div class="row">
                                                <div class="col-md-12" style="margin-left: -5px;">
                                                    <table class="table table-hover">
                                                        <tbody>
                                                            <tr>
                                                                <td class="td-table col-md-3" style ="border-top: 0 !important"><strong>Ação realizada por:</strong> <?=$conteudo['nome_usuario_acao']?></td>
                                                            
                                                                <td class="td-table col-md-3" style ="border-top: 0 !important"><strong>Status:</strong> <?=$conteudo['descricao_status']?></td>
                                                            
                                                                <?php
                                                                $visibilidade = array(
                                                                    "1" => "Público",
                                                                    "2" => "Privado"
                                                                );
                                                                ?>
                                                                <td class="td-table col-md-3" style ="border-top: 0 !important"><strong>Visibilidade:</strong> <?=$visibilidade[$conteudo['visibilidade']]?></td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div><!-- end col -->
                                                <div class="col-md-12" style="margin-left: -5px;">
                                                    <table class="table table-hover">
                                                        <tbody>
                                                            <tr>
                                                                <td class="td-table col-md-3" style ="border-top: 0 !important"><strong>Responsável:</strong> <?=$conteudo['nome_responsavel']?></td>
                                                            
                                                                <td class="td-table col-md-3" style ="border-top: 0 !important"><strong>Data:</strong> <?=converteDataHora($conteudo['data'])?></td>
                                                                <?php
                                                                $tempo = explode(':',converteSegundosHoras($conteudo['tempo'] * 60));
                                                                $horas = intval($tempo[0]);
                                                                $minutos = intval($tempo[1]);
                                                                
                                                                if($horas < '1'){
                                                                    $tempo_total = $minutos." m";
                                                                }else{
                                                                    $tempo_total = $horas." h ".$minutos." m";
                                                                }
                                                                ?>
                                                                <td class="td-table col-md-3" style ="border-top: 0 !important"><strong>Tempo da ação:</strong> <?=$tempo_total?></td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div><!-- end col -->
                                                <div class="col-md-12" style="margin-left: -5px;">
                                                    <table class="table table-hover">
                                                        <tbody>
                                                            <tr>
                                                                <td class="td-table col-md-3 conteudo-editor"><strong>Descrição: </strong> <?=$conteudo['descricao']?></td>
                                                            
                                                                
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div><!-- end col -->
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            <?php
                                $contador ++;
                            endforeach;
                            ?>
                            
                        </div>
                    </div>
                    <?php  }?>
                </div>
            </div>

        <?php
            endif; //fim do if de perfis
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
        ?>
        <!-- div da legend -->
        </div>

    <?php
}

function relatorio_prazo($remetente, $id_responsavel, $data_de, $data_ate, $status, $id_categoria, $ticket, $id_contrato_plano_pessoa){

    $data_de = converteDataHora($data_de)."00:00:00";
    $data_ate = converteDataHora($data_ate)."23:59:59";

    $buscaPorRemetente = "";
    $buscaPorResponsavel = "";
    $buscaPorStatus = "";

    //Busca por remetente ou responsável se selecionado
    if($remetente){
        $buscaPorRemetente = "AND id_usuario_remetente = '$remetente'";
        $dados_remetente = DBRead('','tb_usuario a',"INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_usuario = '".$remetente."'");
        $remetente_nome = $dados_remetente[0]['nome'];
    }else{
        $remetente_nome = "Qualquer";
    }
    if($id_responsavel){
        $buscaPorResponsavel = "AND id_usuario_responsavel = '$id_responsavel'";
        $dados_responsavel = DBRead('','tb_usuario a',"INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_usuario = '".$id_responsavel."'");
        $responsavel_legenda_descricao = $dados_responsavel[0]['nome'];
    }else{
        $responsavel_legenda_descricao = "Qualquer";
    }
    //Busca por status
    if($status){
        $buscaPorStatus = "AND id_chamado_status = '$status'";
        $status_legenda = DBRead('', 'tb_chamado_status', "WHERE id_chamado_status = '".$status."'");
        $status_legenda_descricao = $status_legenda[0]['descricao'];
    }else{
        $status_legenda_descricao = "Qualquer";
    }
    if($id_categoria){
        $filtro_categoria = "AND f.id_categoria = '".$id_categoria."'";
        $dados_categoria = DBRead('','tb_categoria',"WHERE id_categoria = '".$id_categoria."'");
        $categoria = $dados_categoria[0]['nome'];
    }else{
        $categoria = 'Qualquer';
    }

    if(!$id_contrato_plano_pessoa){
        $status_legenda_contrato = "Qualquer";
    }else{
        $dados_contrato = DBRead('', 'tb_contrato_plano_pessoa a', "INNER JOIN tb_plano b ON a.id_plano = b.id_plano INNER JOIN tb_pessoa c ON a.id_pessoa = c.id_pessoa WHERE a.id_contrato_plano_pessoa = '$id_contrato_plano_pessoa'", "a.*, b.cod_servico, b.nome AS 'plano', c.nome AS 'nome_pessoa'");
        $nome_contrato = $dados_contrato[0]['nome_pessoa'];
        if($dados_contrato[0]['nome_contrato'] && $dados_contrato[0]['nome_contrato'] != ''){
            $nome_contrato .= " (".$dados_contrato[0]['nome_contrato'].")";
        }
        $status_legenda_contrato = $nome_contrato ." - " . getNomeServico($dados_contrato[0]['cod_servico']) . " - " . $dados_contrato[0]['plano'] . " (" . $dados_contrato[0]['id_contrato_plano_pessoa'] . ")";
        $filtro_contrato = "AND a.id_contrato_plano_pessoa = '".$id_contrato_plano_pessoa."'";
    }

    $data_hoje = getDataHora();
	$data_hoje = converteDataHora($data_hoje);

	$periodo_amostra = "<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> de ".converteDataHora($data_de)." até ".converteDataHora($data_ate)."</span>";
	$gerado = "<span style=\"font-size: 14px;\"><strong>Gerado em: </strong> $data_hoje</span>";

    if($ticket){
        $chamados = DBRead('', 'tb_chamado a', "INNER JOIN tb_usuario b ON a.id_usuario_remetente = b.id_usuario INNER JOIN tb_pessoa c ON b.id_pessoa = c.id_pessoa INNER JOIN tb_usuario d ON a.id_usuario_responsavel = d.id_usuario INNER JOIN tb_pessoa e ON d.id_pessoa = e.id_pessoa INNER JOIN tb_chamado_categoria f ON a.id_chamado = f.id_chamado WHERE a.data_criacao >= '$data_de' AND a.data_criacao <= '$data_ate' $buscaPorRemetente $buscaPorResponsavel $buscaPorStatus $filtro_contrato $filtro_categoria AND a.id_chamado = $ticket ORDER BY id_chamado DESC", "a.*, c.nome AS nome_remetente, e.nome AS nome_responsavel, f.id_categoria");

        echo "<div class=\"col-md-12\" style=\"padding: 0\">";
        echo "<legend style=\"text-align:center;\"><strong>Relatório de Chamados</strong><br>$gerado</legend>";
        echo "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong> Ticket - </strong>#".$ticket."";
        echo "</legend>";

    }else{
        $chamados = DBRead('', 'tb_chamado a', "INNER JOIN tb_usuario b ON a.id_usuario_remetente = b.id_usuario INNER JOIN tb_pessoa c ON b.id_pessoa = c.id_pessoa INNER JOIN tb_usuario d ON a.id_usuario_responsavel = d.id_usuario INNER JOIN tb_pessoa e ON d.id_pessoa = e.id_pessoa INNER JOIN tb_chamado_categoria f ON a.id_chamado = f.id_chamado WHERE a.data_criacao >= '$data_de' AND a.data_criacao <= '$data_ate' $buscaPorRemetente $buscaPorResponsavel $buscaPorStatus $filtro_contrato $filtro_categoria ORDER BY id_chamado DESC", "a.*, c.nome AS nome_remetente, e.nome AS nome_responsavel, f.id_categoria");

        echo "<div class=\"col-md-12\" style=\"padding: 0\">";
        echo "<legend style=\"text-align:center;\"><strong>Relatório de Prazos</strong><br>$gerado</legend>";

        echo "<legend style=\"text-align:center;\">$periodo_amostra</legend>";

        echo "<legend style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong>Contrato - </strong>".$status_legenda_contrato.", <strong>Status - </strong>".$status_legenda_descricao.", <strong>Remetente - </strong>".$remetente_nome.", <strong>Responsável - </strong>".$responsavel_legenda_descricao.", <strong>Categoria - </strong>".$categoria."";

        echo "</legend>";

    }

    if($chamados){
    echo '<div class="panel panel" style="">';
        echo '<div class="panel-body">';
            echo '<div class="row">';
                echo '<div class="col-md-12" style="margin-left: -5px;">';
                    echo '<table class="table table-hover dataTable">';
                        echo '<thead>';
                            echo '<tr>';
                                echo '<th class="col-md-1">#</th>';
                                echo '<th class="col-md-2">Título do chamado</th>';
                                echo '<th class="col-md-2">Remetente</th>';
                                echo '<th class="col-md-1">Responsável</th>';
                                echo '<th class="col-md-1">Categoria</th>';
                                echo '<th class="col-md-1">Prazo de conclusão atual</th>';
                                echo '<th class="col-md-1">Prazo de conclusão definido na abertura</th>';
                                echo '<th class="col-md-1">Data de conclusão</th>';
                                echo '<th class="col-md-1">Contrato</th>';
                                echo '<th class="col-md-1">Saldo</th>';
                            echo '</tr>';
                        echo '</thead>';
                        echo '<tbody>';

                        foreach($chamados as $key => $chamado){

                            $id_chamado = $chamados[$key]['id_chamado'];
                            $status_chamado = $chamados[$key]['id_chamado_status'];
                            $visibilidade = $chamados[$key]['visibilidade'];
                            $id_categoria_chamado = $chamados[$key]['id_categoria'];

                            $nome_categoria = DBRead('', 'tb_categoria', "WHERE id_categoria = '$id_categoria_chamado'");

                            //prazos detrminado para o chamado
                            $interacoes_chamado = DBRead('', 'tb_chamado_acao', "WHERE id_chamado = '$id_chamado' AND (acao = 'alteracao_prazo_encerramento' OR acao = 'criacao') ORDER BY data DESC", "prazo_encerramento, tempo, acao, data, id_chamado");

                            //Data de encerramento do chamado
                            $chamado_encerramento = DBRead('', 'tb_chamado_acao', "WHERE id_chamado = '$id_chamado' AND (acao = 'encerrar') ORDER BY data DESC", "prazo_encerramento, tempo, acao, data, id_chamado");

                            $titulo = $chamados[$key]['titulo'];
                            $remetente = $chamados[$key]['id_usuario_remetente'];
                            $responsavel = $chamados[$key]['id_usuario_responsavel'];
                            $prazo_conclusao = $chamados[$key]['prazo_encerramento'];
                            $nome_remetente = $chamados[$key]['nome_remetente'];
                            $nome_responsavel = $chamados[$key]['nome_responsavel'];

                            foreach($interacoes_chamado as $key => $interacao){
                                if($interacoes_chamado[$key]['prazo_encerramento']){
                                    $prazo_conclusao_inicial = $interacoes_chamado[$key]['prazo_encerramento'];
                                    $prazo = strtotime($prazo_conclusao);
                                }else{
                                    $prazo_conclusao_inicial = '';
                                    $prazo = strtotime($prazo_conclusao);
                                }

                                if($status_chamado == 3 || $status_chamado == 4){

                                    if($chamado_encerramento[0]['data']){
                                        $data_conclusao = $chamado_encerramento[0]['data'];
                                    }
                                    $conclusao = strtotime($data_conclusao);

                                }else{
                                    $data_conclusao = '';
                                    $conclusao = strtotime(getDataHora());
                                }

                                if($conclusao && $conclusao < $prazo){
                                    $status_prazo = "class='success'";
                                }else if(!$prazo_conclusao_inicial && $prazo_conclusao){
                                    $prazo_conclusao_inicial = $prazo_conclusao;
                                }else if((!$prazo_conclusao || $prazo_conclusao == '0000-00-00 00:00:00') && (!$prazo_conclusao_inicial || $prazo_conclusao_inicial == '0000-00-00 00:00:00')){
                                    $status_prazo = "class='warning'";
                                }else{
                                    $status_prazo = "class='danger'";
                                }
                            }

                            ////////////// Bloco de validação de perfis e usuários liderados ////////////////
                            $visualiza = "false";
                            
                            $dados_usuario_remetente = DBRead('','tb_usuario a',"INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_usuario = '".$remetente."'");
                            $dados_usuario_logado = DBRead('', 'tb_usuario', "WHERE id_usuario = '".$_SESSION['id_usuario']."'");
                            $dados_usuario_responsavel = DBRead('', 'tb_usuario', "WHERE id_usuario = '".$responsavel."'");
                            
                            //REGRAS PARA CHAMADOS PRIVADOS
                            $dados_usuarios_envolvidos = DBRead('', 'tb_chamado_usuario', "WHERE id_chamado = '".$id_chamado."'");
                            
                            //Verifica se estou envolvido no chamado
                            foreach($dados_usuarios_envolvidos as $key => $envolvido){
                                if($_SESSION['id_usuario'] == $dados_usuarios_envolvidos[$key]['id_usuario'] && $visibilidade == 2){
                                    $visualiza = "true";
                                }
                            }

                            //REGRAS PARA CHAMADOS PÚBLICOS
                            $dados_perfis_envolvidos = DBRead('', 'tb_chamado_perfil', "WHERE id_chamado = '".$id_chamado."'");
                            foreach($dados_perfis_envolvidos as $key => $envolvido){
                                if($dados_usuario_logado[0]['id_perfil_sistema'] == $dados_perfis_envolvidos[$key]['id_perfil_sistema'] && $visibilidade == 1){
                                    $visualiza = "true";
                                }
                            }
                            
                            $perfil_lider = busca_perfis_liderados($dados_usuario_logado[0]['id_perfil_sistema']);
                            foreach($perfil_lider as $conteudo){
                                //Deve verificar o perfil do remetente ou responsável, e não se  perfil subordinado é igual ao id do remetente ou responsavel e visibilidade publica
                                if((intval($conteudo) == intval($dados_usuario_remetente[0]['id_perfil_sistema']) || intval($conteudo) == intval($dados_usuario_responsavel[0]['id_perfil_sistema'])) && ($visibilidade == 1 || $visibilidade == 2)){
                                    $visualiza = "true";
                                }
                            }
                            $liderados = array();
                            $usuario_lider = busca_liderados($_SESSION['id_usuario'], $liderados);
                            foreach($usuario_lider as $conteudo){
                                //Deve verificar o perfil do remetente ou responsável, e não se perfil subordinado é igual ao id do remetente ou responsavel e visibilidade publica
                                if((intval($conteudo) == intval($remetente) || intval($conteudo) == intval($responsavel)) && ($visibilidade == 1 || $visibilidade == 2)){
                                    $visualiza = "true";
                                }
                            }
                            ///////////////////////////////////////////////////////////////////////////////////////////

                            if($visualiza == "true"){

                                if($chamado['id_contrato_plano_pessoa'] != 0){
                                    $dados_contrato2 = DBRead('', 'tb_contrato_plano_pessoa a', "INNER JOIN tb_plano b ON a.id_plano = b.id_plano INNER JOIN tb_pessoa c ON a.id_pessoa = c.id_pessoa WHERE a.id_contrato_plano_pessoa = '".$chamado['id_contrato_plano_pessoa']."' ", "a.*, b.cod_servico, b.nome AS 'plano', c.nome AS 'nome_pessoa'");
                                    $nome_contrato2 = $dados_contrato2[0]['nome_pessoa'];
                                    if($dados_contrato2[0]['nome_contrato'] && $dados_contrato2[0]['nome_contrato'] != ''){
                                        $nome_contrato2 .= " (".$dados_contrato2[0]['nome_contrato'].")";
                                    }
                                    $nome_contrato_tabela = $nome_contrato2 ." - " . getNomeServico($dados_contrato2[0]['cod_servico']) . " - " . $dados_contrato2[0]['plano'] . " (" . $dados_contrato2[0]['id_contrato_plano_pessoa'] . ")";
                                }else{
                                    $nome_contrato_tabela = "";
                                }

                                echo '<tr>';
                                    echo '<td style="vertical-align: middle;">'.$id_chamado.'</td>';
                                    echo '<td style="vertical-align: middle;">'.$titulo.'</td>';
                                    echo '<td style="vertical-align: middle;">'.$nome_remetente.'</td>';
                                    echo '<td style="vertical-align: middle;">'.$nome_responsavel.'</td>';
                                    echo '<td style="vertical-align: middle;">'.$nome_categoria[0]['nome'].'</td>';
                                    if($prazo_conclusao != '0000-00-00 00:00:00'){
                                        echo '<td style="vertical-align: middle;">'.converteDataHora($prazo_conclusao).'</td>';
                                    }else{
                                        echo '<td style="vertical-align: middle;"></td>';
                                    }
                                    if($prazo_conclusao_inicial != '0000-00-00 00:00:00'){
                                        echo '<td style="vertical-align: middle;">'.converteDataHora($prazo_conclusao_inicial).'</td>';
                                    }else{
                                        echo '<td style="vertical-align: middle;"></td>';
                                    }
                                    echo '<td style="vertical-align: middle;">'.converteDataHora($data_conclusao).'</td>';
                                    
                                    echo '<td class="text-left" style="vertical-align: middle;">'.$nome_contrato_tabela.'</td>';
                                  
                                    if((!$prazo_conclusao || $prazo_conclusao == '0000-00-00 00:00:00') && (!$prazo_conclusao_inicial || $prazo_conclusao_inicial == '0000-00-00 00:00:00')){
                                        echo '<td '.$status_prazo.' style="vertical-align: middle;">Não há um prazo!</td>';
                                    }else if($status_chamado != 3 || $status_chamado != 4){
                                        echo '<td '.$status_prazo.' style="vertical-align: middle;">'.number_format((($prazo - $conclusao) / 3600), 2).' Horas</td>';
                                    }else{
                                        echo '<td '.$status_prazo.' style="vertical-align: middle;">'.number_format((($prazo - $conclusao) / 3600), 2).' Horas</td>';
                                    }

                                echo '</tr>';
                            }
                        }

                        echo '</tbody>';
                    echo '</table>';
                echo '</div>';
            echo '</div>';
        echo '</div>';
    echo '</div>';
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