<?php
    require_once(__DIR__."/../class/System.php");

	$id_usuario = $_SESSION['id_usuario'];
	$dados = DBRead('', 'tb_usuario', "WHERE id_usuario = '$id_usuario'");

	$data_hoje = getDataHora();
	$data_hoje = explode(" ", $data_hoje);
	$data_hoje = $data_hoje[0];
	$primeiro_dia = "01/".$data_hoje[5].$data_hoje[6]."/".$data_hoje[0].$data_hoje[1].$data_hoje[2].$data_hoje[3];

	$data_de = (!empty($_POST['data_de'])) ? $_POST['data_de'] : $primeiro_dia;
	$data_ate = (!empty($_POST['data_ate'])) ? $_POST['data_ate'] : converteData(getDataHora('data'));

    $tipo_relatorio = (!empty($_POST['tipo_relatorio'])) ? $_POST['tipo_relatorio'] : '1';
    
	$idade = (!empty($_POST['idade'])) ? $_POST['idade'] : '';
	$setor = (!empty($_POST['setor'])) ? $_POST['setor'] : '';
	$cargo = (!empty($_POST['setor'])) ? $_POST['cargo'] : '';
	$escolaridade = (!empty($_POST['escolaridade'])) ? $_POST['escolaridade'] : '';
	$estudando = (!empty($_POST['estudando'])) ? $_POST['estudando'] : '';
	$disponibilidade_dias = (!empty($_POST['disponibilidade_dias'])) ? $_POST['disponibilidade_dias'] : '';
    $turnos = (!empty($_POST['turnos'])) ? "'".join("','", $_POST['turnos'])."'" : '';
    $contratacao = (!empty($_POST['contratacao'])) ? "'".join("','", $_POST['contratacao'])."'" : '';
    $compartilhar_dados = (!empty($_POST['compartilhar_dados'])) ? $_POST['compartilhar_dados'] : '';
    $busca_curriculos = (!empty($_POST['busca_curriculos'])) ? $_POST['busca_curriculos'] : '';
    $participou = (!empty($_POST['participou'])) ? $_POST['participou'] : '';
    $id_pessoa = (!empty($_POST['id_pessoa'])) ? $_POST['id_pessoa'] : '';
    $pcd = (!empty($_POST['pcd'])) ? $_POST['pcd'] : '';
    $possui_equipamento = (!empty($_POST['possui_equipamento'])) ? $_POST['possui_equipamento'] : '';
    $trabalha_empresa = (!empty($_POST['trabalha_empresa'])) ? $_POST['trabalha_empresa'] : '';
    $tag = (!empty($_POST['tag'])) ? $_POST['tag'] : '';
    $participando = (!empty($_POST['participando'])) ? $_POST['participando'] : '';
    $cidade = (!empty($_POST['cidade'])) ? $_POST['cidade'] : '';

    $id_pessoa_get = (!empty($_GET['id_pessoa'])) ? $_GET['id_pessoa'] : '';
    if($id_pessoa_get){
        $id_pessoa = $id_pessoa_get;
        $gerar_get = 1;
    }else{
        $gerar_get = 0;
    }
    
    if ($id_pessoa) {
        $nome_candidato = DBRead('', 'tb_pessoa', "WHERE id_pessoa = $id_pessoa", 'nome');
    }
    
	if ($tipo_relatorio == 1) {
		$display_pausa = 'none';
		$display = 'block';
		$display_perda = 'none';
		$display_row6 = 'none';
		$display_row7 = 'none';
	}

	$gerar = (!empty($_POST['gerar'])) ? 1 : $gerar_get;
	
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
        .noprint { 
            display: none;
        }
        #aviso {
            display: block !important;
        }
        footer {
            page-break-after: always !important;
        }
        body {
            font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
            padding-top: 0;
            padding: 0 !important;
            -webkit-print-color-adjust: exact !important;
            color-adjust: exact !important;
            visibility: visible !important;
        }
        #row-imagem{
            display: none !important;
        }
        #row-imagem-relatorio{
            display: block !important;
        }
        .jumbotron{
            padding-top: 10px !important;
            padding-left: 20px !important;
            padding-right: 4px !important;
            padding-bottom: 0 !important;
            font-size: 13px !important; 
            margin-left: -142px !important;
            margin-right: -142px !important;
            height: 100% !important;
            border: none !important;
            margin-bottom: -100px !important;
        }
        .row{
            padding-bottom: 7px !important;
        }
        .media{
            padding-bottom: 2px !important;
        }
        span{
            font-size: 12px !important;
        }
        h1{
            font-size: 20px !important;
        }
        h4{
            font-size: 14px !important;
        }
        #teste{
            background-color: #E6E6E6 !important;
        }
        .breadcrumb {
            background-color: #E6E6E6 !important;
        }
        #imagem{
            visibility: visible !important;
        }
        img {
            display: block;
            width: 100vw;
            height: 100vh;
            object-fit: cover;
            margin-left: 40px;
        }
    }
    .fonts-curriculo{
        font-size: 15px;
        color: black;
    }
    .hr-curriculo{
        border-top: 2px solid #BDBDBD;
    }
    .pd-curriculo{
        padding: 10px 0 10px 23px !important;
    }
    .pd-exp-prof{
        padding-left: 4px !important;
    }
    #imagem{
        -moz-background-size: 100% 100%;
        -webkit-background-size: 100% 100%;
        background-size: cover;
        height: 146px;
        width: 100%;
        resize: both;
        border-top-left-radius: 10px;
        border-top-right-radius: 10px;
        background-position: center;
    }
    #img-relatorio{
        border-radius: 80px;
        width: 146px;
        height: 146px;
        object-fit: cover;
    }
    img {
        image-orientation: from-image;
    }
    span{
        overflow-wrap: break-word;
    }
    .select2{
        width: 100% !important;
    }
</style>

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs/jszip-2.5.0/dt-1.10.18/b-1.5.4/b-colvis-1.5.4/b-flash-1.5.4/b-html5-1.5.4/b-print-1.5.4/r-2.2.2/datatables.min.css"/> 
<script type="text/javascript" src="https://cdn.datatables.net/v/bs/jszip-2.5.0/dt-1.10.18/b-1.5.4/b-colvis-1.5.4/b-flash-1.5.4/b-html5-1.5.4/b-print-1.5.4/r-2.2.2/datatables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/plug-ins/1.10.19/sorting/time.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/plug-ins/1.10.19/sorting/chinese-string.js"></script>

<div class="container-fluid">
	<form method="post" id="">
		<div class="row">
	        <div class="col-md-6 col-md-offset-3">
	        	<div class="panel panel-default noprint">
	                <div class="panel-heading clearfix">
	                    <h3 class="panel-title text-left pull-left" style="margin-top: 2px;">Relatório de currículos:</h3>
	                    <div class="panel-title text-right pull-right"><button data-toggle="collapse" data-target="#accordionRelatorio" class="btn btn-xs btn-info" type="button" title="Visualizar filtros"><i id="i_collapse" class="fa fa-<?=$collapse_icon?>"></i></button></div>
	                </div>
	                <div id="accordionRelatorio" class="panel-collapse collapse <?=$collapse?>">
	                	<div class="panel-body">
	                		<div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>*Tipo de Relatório:</label> <select
                                            name="tipo_relatorio" id="tipo_relatorio" class="form-control input-sm">
											<option value="1" <?php if($tipo_relatorio == '1'){echo 'selected';}?>>Candidatos</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Candidato:</label>
                                        <div class="input-group">
                                            <input class="form-control input-sm ui-autocomplete-input" id="busca_pessoa" type="text" name="busca_pessoa" value="<?=$nome_candidato[0]['nome']?>" placeholder="Informe o nome ou CPF/CNPJ..." autocomplete="off" readonly="">
                                            <div class="input-group-btn">
                                                <button class="btn btn-info btn-sm" id="habilita_busca_pessoa" name="habilita_busca_pessoa" type="button" title="Clique para selecionar a pessoa" style="height: 30px;"><i class="fa fa-search"></i></button>
                                            </div>
                                        </div>
                                        <input type="hidden" name="id_pessoa" id="id_pessoa" value="<?=$id_pessoa?>">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
	                			<div class="col-md-4">
	                				<div class="form-group">
										<label>Idade:</label>
										<select class="form-control input-sm" name="idade" id="idade">
                                            <option value="" <?=$idade == "" ? "selected" : "";?> >Qualquer</option>
                                            <option value="1" <?=$idade == "1" ? "selected" : "";?>>Maiores de 18 anos</option>
                                            <option value="2" <?=$idade == "2" ? "selected" : "";?>>Menores de 18 anos</option>
                                        </select>
									</div>
	                			</div>
	                			<div class="col-md-4">
	                				<div class="form-group">
										<label>Área de interesse (Setor):</label>
										<select class="form-control input-sm" name="setor" id="setor">
                                            <option value="">Qualquer</option>
                                            <?php
                                                $dados_setor = DBRead('', 'tb_setor', "ORDER BY descricao ASC");
                                                foreach ($dados_setor as $conteudo_setor) {
                                                    $selected = $setor == $conteudo_setor['id_setor'] ? "selected" : "";
                                            ?>
                                                    <option value="<?=$conteudo_setor['id_setor']?>" <?=$selected?> ><?=$conteudo_setor['descricao']?></option>
                                            <?php
                                                }
                                            ?>
                                        </select>
									</div>
	                			</div>
                                <div class="col-md-4">
	                				<div class="form-group">
										<label>Área de interesse (Cargo):</label>
                                        <select name="cargo" id="cargo" class="form-control input-sm">
                                            <?php 
                                                if ($cargo !='') {
                                                    $dados_cargos = DBRead('', 'tb_cargo', "WHERE id_setor = $setor");
                                                    foreach ($dados_cargos as $conteudo_cargo) {
                                                        $selected = $cargo == $conteudo_cargo['id_cargo'] ? "selected" : "";
                                            ?>
                                                     <option value="<?=$conteudo_cargo['id_cargo']?>" <?=$selected?>><?=$conteudo_cargo['descricao']?></option>
                                            <?php 
                                                    } 
                                                } else { ?>
                                                    <option value="">Selecione um setor</option>
                                            <?php } ?>
                                        </select>
									</div>
	                			</div>
	                		</div>
                            <div class="row">
	                			<div class="col-md-6">
	                				<div class="form-group">
										<label>Escolaridade:</label>
										<select name="escolaridade" id="escolaridade" class="form-control input-sm">
                                            <option value="" <?=$escolaridade == "" ? "selected" : "";?>>Qualquer</option>
                                            <option value="null" disabled="">----------</option>
                                            <option value="1" <?=$escolaridade == "1" ? "selected" : "";?>>Primeiro grau completo</option>
                                            <option value="2" <?=$escolaridade == "2" ? "selected" : "";?>>Primeiro grau incompleto</option>
                                            <option value="null" disabled="">----------</option>
                                            <option value="3" <?=$escolaridade == "3" ? "selected" : "";?>>Segundo grau completo</option>
                                            <option value="4" <?=$escolaridade == "4" ? "selected" : "";?>>Segundo grau incompleto</option>
                                            <option value="null" disabled="">----------</option>
                                            <option value="5" <?=$escolaridade == "5" ? "selected" : "";?>>Superior completo</option> 
                                            <option value="6" <?=$escolaridade == "6" ? "selected" : "";?>>Superior incompleto</option>
                                            <option value="null" disabled="">----------</option>
                                            <option value="7" <?=$escolaridade == "7" ? "selected" : "";?>>Pós-graduação completa</option> 
                                            <option value="8" <?=$escolaridade == "8" ? "selected" : "";?>>Pós-graduação incompleta</option>
                                            <option value="null" disabled="">----------</option>
                                            <option value="9" <?=$escolaridade == "9" ? "selected" : "";?>>Mestrado completo</option> 
                                            <option value="10" <?=$escolaridade == "10" ? "selected" : "";?>>Mestrado incompleto</option>
                                            <option value="null" disabled="">----------</option>
                                            <option value="11" <?=$escolaridade == "11" ? "selected" : "";?>>Doutorado completo</option> 
                                            <option value="12" <?=$escolaridade == "12" ? "selected" : "";?>>Doutorado incompleto</option> 
                                        </select>
									</div>
	                			</div>
	                			<div class="col-md-6">
	                				<div class="form-group">
										<label>Estudando:</label>
										<select name="estudando" id="estudando" class="form-control input-sm">
                                            <option value="" <?=$estudando == "" ? "selected" : "";?>>Qualquer</option>
                                            <option value="1" <?=$estudando == "1" ? "selected" : "";?>>Sim</option>
                                            <option value="2" <?=$estudando == "2" ? "selected" : "";?>>Não</option>
                                        </select>
									</div>
	                			</div>
	                		</div>
                            <div class="row">
	                			<div class="col-md-6">
	                				<div class="form-group">
										<label>Disponibilidade de dias:</label>
										<select name="disponibilidade_dias" id="disponibilidade_dias" class="form-control input-sm">
                                            <option value="" <?=$disponibilidade_dias == "" ? "selected" : "";?>>Qualquer</option>
                                            <option value="1" <?=$disponibilidade_dias == "1" ? "selected" : "";?>>De segunda a sexta-feira</option>
                                            <option value="2" <?=$disponibilidade_dias == "2" ? "selected" : "";?>>De segunda a sábado</option>
                                            <option value="3" <?=$disponibilidade_dias == "3" ? "selected" : "";?>>Todos os dias da semana (inclusive domingos e feriados)</option>
                                        </select>
									</div>
	                			</div>

                                <div class="col-md-6">
	                				<div class="form-group">
										<label>Cidade:</label>
                                        <select name="cidade" id="cidade" class="form-control input-sm">

                                            <option value="">Selecione uma cidade</option>
                                            <?php 
                                                $dados_cidade = DBRead('', 'tb_pessoa a', " INNER JOIN tb_cidade b ON a.id_cidade = b.id_cidade INNER JOIN tb_estado c ON b.id_estado = c.id_estado WHERE a.candidato = 1 GROUP BY b.id_cidade ORDER BY b.nome ASC","b.id_cidade, b.nome AS nome_cidade, c.id_estado, c.sigla");

                                                foreach ($dados_cidade as $conteudo_cidade) { 
                                                    $selected = $cidade == $conteudo_cidade['id_cidade'] ? "selected" : "";
                                            ?>

                                                    <option value="<?=$conteudo_cidade['id_cidade']?>" <?=$selected?>><?=$conteudo_cidade['nome_cidade']?> - <?=$conteudo_cidade['sigla']?></option>

                                            <?php 
                                                } 
                                            ?>
  
                                        </select>
									</div>
	                			</div>
	                		</div>
                            
                            <?php
                            if($dados_filas){
                                foreach ($dados_filas as $conteudo_filas) {
                                    if(preg_match('/'.$conteudo_filas['name'].'/i', $fila)){
                                        $sel_fila = 'selected';
                                    }else{
                                        $sel_fila = '';
                                    }
                                    echo "<option value='".$conteudo_filas['name']."' $sel_fila>".$conteudo_filas['name']."</option>";
                                }
                            }
                            ?>

                            <div class="row" id="row_fila">
								<div class="col-md-6">
									<div class="form-group">
								        <label for="">Disponibilidade de turnos:</label>
								        <select name="turnos[]" id="turnos" class="form-control input-sm" multiple="multiple" size="4">
                                            <?php 
                                                if(preg_match('/1/i', $turnos)){
                                                    $sel_turno = 'selected';
                                                }else{
                                                    $sel_turno = '';
                                                }

                                            ?>
								            <option value="1" <?=$sel_turno?>>Manhã</option>

                                            <?php 
                                                if(preg_match('/2/i', $turnos)){
                                                    $sel_turno = 'selected';
                                                }else{
                                                    $sel_turno = '';
                                                }
                                            ?>
								            <option value="2" <?=$sel_turno?>>Tarde</option>

                                            <?php 
                                                if(preg_match('/3/i', $turnos)){
                                                    $sel_turno = 'selected';
                                                }else{
                                                    $sel_turno = '';
                                                }
                                            ?>
								            <option value="3" <?=$sel_turno?>>Noite</option>

                                            <?php 
                                                if(preg_match('/4/i', $turnos)){
                                                    $sel_turno = 'selected';
                                                }else{
                                                    $sel_turno = '';
                                                }
                                            ?>
								            <option value="4" <?=$sel_turno?>>Madrugada</option>
                                        </select>
								    </div>
								</div>
                                <div class="col-md-6">
									<div class="form-group">
								        <label for="">Formato de contratação:</label>
								        <select name="contratacao[]" id="contratacao" class="form-control input-sm" multiple="multiple" size="4">
                                            <?php 
                                                if(preg_match('/1/i', $contratacao)){
                                                    $sel_formato = 'selected';
                                                }else{
                                                    $sel_formato = '';
                                                }

                                            ?>
								            <option value="1" <?=$sel_formato?>>Meio turno</option>

                                            <?php 
                                                if(preg_match('/2/i', $contratacao)){
                                                    $sel_formato = 'selected';
                                                }else{
                                                    $sel_formato = '';
                                                }
                                            ?>
								            <option value="2" <?=$sel_formato?>>Turno integral</option>

                                            <?php 
                                                if(preg_match('/3/i', $contratacao)){
                                                    $sel_formato = 'selected';
                                                }else{
                                                    $sel_formato = '';
                                                }
                                            ?>
								            <option value="3" <?=$sel_formato?>>Estágio</option>

                                            <?php 
                                                if(preg_match('/4/i', $contratacao)){
                                                    $sel_formato = 'selected';
                                                }else{
                                                    $sel_formato = '';
                                                }
                                            ?>
								            <option value="4" <?=$sel_formato?>>Prestação de serviço terceirizado</option>
                                        </select>
								    </div>
								</div>
							</div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="">Permite compartilhar dados:</label>
                                        <select class="form-control input-sm" name="compartilhar_dados" id="compartilhar_dados">
                                                <option value="" <?=$compartilhar_dados == "" ? "selected" : "";?>>Qualquer</option>
                                                <option value="1" <?=$compartilhar_dados == "1" ? "selected" : "";?>>Sim</option>
                                                <option value="2" <?=$compartilhar_dados == "2" ? "selected" : "";?>>Não</option>
                                        </select>
                                    </div>
                                </div>
                            </div>                           
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Busca por currículos:</label>
                                        <select class="form-control input-sm" name="busca_curriculos" id="busca_curriculos">
                                                <option value="1" <?=$busca_curriculos == "1" ? "selected" : "";?>>Por data</option>
                                                <option value="2" <?=$busca_curriculos == "2" ? "selected" : "";?>>Todos cadastrados (desconsidera a data)</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Já participou do processo seletivo:</label>
                                        <select class="form-control input-sm" name="participou" id="participou">
                                                <option value="" <?=$participou == "" ? "selected" : "";?>>Qualquer</option>
                                                <option value="1" <?=$participou == "1" ? "selected" : "";?>>Sim</option>
                                                <option value="2" <?=$participou == "2" ? "selected" : "";?>>Não</option>
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
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>PCD:</label>
                                        <select class="form-control input-sm" name="pcd" id="pcd">
                                                <option value="" <?=$pcd == "" ? "selected" : "";?>>Qualquer</option>
                                                <option value="1" <?=$pcd == "1" ? "selected" : "";?>>Sim</option>
                                                <option value="2" <?=$pcd == "2" ? "selected" : "";?>>Não</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Possui computador/notebook em boas condições:</label>
                                        <select class="form-control input-sm" name="possui_equipamento" id="possui_equipamento">
                                                <option value="" <?=$possui_equipamento == "" ? "selected" : "";?>>Qualquer</option>
                                                <option value="1" <?=$possui_equipamento == "1" ? "selected" : "";?>>Sim</option>
                                                <option value="2" <?=$possui_equipamento == "2" ? "selected" : "";?>>Não</option>
                                        </select>
                                    </div>
                                </div>
	                		</div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Trabalha na empresa:</label>
                                        <select class="form-control input-sm" name="trabalha_empresa" id="trabalha_empresa">
                                                <option value="" <?=$trabalha_empresa == "" ? "selected" : "";?>>Qualquer</option>
                                                <option value="1" <?=$trabalha_empresa == "" ? "selected" : "1";?>>Funcionário(a)</option>
                                                <option value="2" <?=$trabalha_empresa == "" ? "selected" : "2";?>>Ex-funcionário(a)</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <?php
                                            $tags = DBRead('', 'tb_tag', "ORDER BY descricao ASC");
                                        ?>
                                        <label>Tag:</label>
                                        <select class="form-control input-sm" name="tag" id="tag">
                                            <option value="">Qualquer</option>
                                            <?php foreach($tags as $conteudo) { 
                                                    $selected = $tag == $conteudo['id_tag'] ? "selected" : "";?>
                                                    <option value="<?=$conteudo['id_tag']?>" <?=$selected?>><?=$conteudo['descricao']?></option>

                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
	                		</div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Está em processo seletivo:</label>
                                        <select class="form-control input-sm" name="participando" id="participando">
                                                <option value="" <?=$participando == "" ? "selected" : "";?>>Qualquer</option>
                                                <option value="1" <?=$participando == "1" ? "selected" : "";?>>Sim</option>
                                                <option value="2" <?=$participando == "2" ? "selected" : "";?>>Não</option>
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
		<?php
			if($gerar){
	
				if($tipo_relatorio == 1){
					relatorio_candidatos($data_de, $data_ate, $idade, $setor, $cargo, $escolaridade, $estudando, $disponibilidade_dias, $turnos, $compartilhar_dados, $busca_curriculos, $contratacao, $participou, $id_pessoa, $pcd, $possui_equipamento, $trabalha_empresa, $tag, $participando, $cidade);
	            }
			}
		?>
	</div>
</div>

<script>

    $('#accordionRelatorio').on('shown.bs.collapse', function(){
       $("#i_collapse").removeClass("fa fa-plus").addClass("fa fa-minus");
    });

    $('#accordionRelatorio').on('hidden.bs.collapse', function () {
       $("#i_collapse").removeClass("fa fa-minus").addClass("fa fa-plus");
    });

	$('#tipo_relatorio').on('change', function(){
		tipo_relatorio = $(this).val();

		if(tipo_relatorio == 1){
			$('#row2').hide();
        }
	});

    // Atribui evento e função para limpeza dos campos
    $('#busca_pessoa').on('input', limpaCamposPessoa);

    // Dispara o Autocomplete da pessoa a partir do segundo caracter
    $("#busca_pessoa").autocomplete({
            minLength: 2,
            source: function(request, response){
                $.ajax({
                    url: "/api/ajax?class=PessoaAutocomplete.php",
                    dataType: "json",
                    data: {
                        acao: 'consulta_candidato',
                        parametros: {
                            'nome' : $('#busca_pessoa').val(),
                            'atributo' : $('#atributo').val(),
                        },
                        token: '<?= $request->token ?>'
                    },
                    success: function(data){
                        response(data);
                    }
                });
            },
            focus: function(event, ui){
                $("#busca_pessoa").val(ui.item.nome + " "+ ui.item.nome_contrato);
                carregarDadosPessoa(ui.item.id_pessoa);
                return false;
            },
            select: function(event, ui){
                $("#busca_pessoa").val(ui.item.nome + " "+ ui.item.nome_contrato);
                $('#busca_pessoa').attr("readonly", true);
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

        return $("<li>").append("<a><strong>"+item.id_pessoa+" - "+ item.nome + item.nome_contrato +" </strong><br>" +item.razao_social+ "<br>" +item.cpf_cnpj+ "</a><hr style='margin-bottom: 0px;'>").appendTo(ul);
    };

    // Função para carregar os dados da consulta nos respectivos campos
    function carregarDadosPessoa(id){
        var busca = $('#busca_pessoa').val();

        if(busca != "" && busca.length >= 2){
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
                success: function(data){
                    $('#id_pessoa').val(data[0].id_pessoa);
                    carrregaSelectVinculo(data[0].id_pessoa);
                }
            });
        }
    }

    // Função para limpar os campos caso a busca esteja vazia
    function limpaCamposPessoa(){
        var busca = $('#busca_pessoa').val();
        if (busca == "") {
            $('#id_pessoa').val('');
        }
    }

    $(document).on('click', '#habilita_busca_pessoa', function () {
        $('#id_pessoa').val('');
        $('#busca_pessoa').val('');
        $('#busca_pessoa').attr("readonly", false);
        $('#busca_pessoa').focus();;
    });

    function selectCargo(id_setor, id_cargo){        
        //$("select[name=setor]").html('<option value="">Carregando...</option>');
        $.post("/api/ajax?class=SelectCargo.php",
            {setor: id_setor,
            token: '<?= $request->token ?>'},
            function(valor){
                $("select[name=cargo]").html(valor);
                if(id_cargo != undefined){
                    $('#cargo').val(id_cargo);
                }
            }
        )        
    }

    $(document).on('change', 'select[name=setor]', function(){

		if ($(this).val() != 'null') {
			selectCargo($(this).val());
		} else{
			$("select[name=cargo]").empty();
			$("select[name=cargo]").html('<option value="">Selecione um setor</option>');
		}
    });

    $(document).ready(function(){
        $('.js-example-basic-multiple').select2();

        $(".js-example-basic-multiple").select2({
            tags: true
        });
    });
</script>

<?php 
function relatorio_candidatos($data_de, $data_ate, $idade, $setor, $cargo, $escolaridade, $estudando, $disponibilidade_dias, $turnos, $compartilhar_dados, $busca_curriculos, $contratacao, $participou, $id_pessoa, $pcd, $possui_equipamento, $trabalha_empresa, $tag, $participando, $cidade){

	$data_hora = converteDataHora(getDataHora());
    if ($id_pessoa) {
        $filtro_pessoa = "AND a.id_pessoa = $id_pessoa";

    } else {
        if ($data_de && $data_ate) {
            $periodo_amostra ="<span class=\"noprint\" style=\"font-size: 14px;\"><strong>Período da amostra:</strong> De $data_de até $data_ate</span>";

        } else if ($data_de) {
            $periodo_amostra ="<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> A partir de $data_de</span>";

        } else if ($data_ate) {
            $periodo_amostra ="<span style=\"font-size: 14px;\"><strong>Período da amostra:</strong> Até $data_ate</span>";

        } else {
            $periodo_amostra = "";
        }

        $legenda_periodo_amostra = "<legend class=\"noprint\" style=\"text-align:center;\"><span style=\"font-size: 14px;\">".$periodo_amostra."</legend>";
         
        if ($busca_curriculos == 1) {       
            if ($data_de && $data_ate) {
                $filtro_data = " AND ((b.data_cadastro >= '".converteData($data_de)." 00:00:00' AND b.data_cadastro <= '".converteData($data_ate)." 23:59:00') OR (b.data_atualizacao >= '".converteData($data_de)." 00:00:00' AND b.data_atualizacao <= '".converteData($data_ate)." 23:59:00'))";

            } else if ($data_de) {
                $filtro_data = " AND ((b.data_cadastro >= '".converteData($data_de)." 00:00:00') OR (b.data_atualizacao >= '".converteData($data_de)." 00:00:00'))";

            } else if ($data_ate) {
                $filtro_data = " AND ((b.data_cadastro <= '".converteData($data_ate)." 23:59:00') OR (b.data_atualizacao <= '".converteData($data_ate)." 23:59:00'))";

            } else {
                $filtro_data = "";
            }

        } else {
            $filtro_data = "";
        }

        if ($compartilhar_dados == 1) {
            $filtro_compart_dados = 'AND b.permite_compart_dados = 1';

        } else if ($compartilhar_dados == 2) {
            $filtro_compart_dados = 'AND b.permite_compart_dados = 2';
        }

        if ($setor) {
            $filtro_setor = " AND (select count(*) FROM tb_pessoa_rh_area_interesse c WHERE c.id_setor = '$setor' AND c.id_pessoa = a.id_pessoa) > 0";
        }

        if ($cargo) {
            $filtro_cargo = " AND (select count(*) FROM tb_pessoa_rh_area_interesse i WHERE i.id_cargo = '$cargo' AND i.id_pessoa = a.id_pessoa) > 0";
        }

        if ($escolaridade) {
            $filtro_escolaridade = " AND b.escolaridade = $escolaridade";
        }

        if ($disponibilidade_dias) {
            $filtro_disponibilidade_dias = " AND b.disponibilidade_dias = $disponibilidade_dias";
        }

        if ($idade) {

            $date = getDataHora();
            $data_busca = date("Y-m-d", strtotime(date("Y-m-d", strtotime($date)) . " - 18 years"));

            echo 'data resultado: '.$data_busca.'<br>';

            if ($idade == 1){
                //maiores que 18
                $filtro_idade = " AND a.data_nascimento <= '$data_busca'";

            } else {
                //menores que 18
                $filtro_idade = " AND a.data_nascimento > '$data_busca'";
            }
        }

        if ($estudando) {

            if ($estudando == 1) {
                $filtro_estudado = " AND (select count(*) FROM tb_pessoa_rh_formacao_curso d WHERE d.cursando = 1 AND d.id_pessoa = a.id_pessoa) > 0";
            } else {
                $filtro_estudado = " AND (select count(*) FROM tb_pessoa_rh_formacao_curso d WHERE d.cursando = 1 AND d.id_pessoa = a.id_pessoa) = 0";
            }
        }

        if ($turnos) {
            $filtro_turno = " AND (select count(*) FROM tb_pessoa_pessoa_rh_disponibilidade_turno e WHERE e.id_pessoa_rh_disponibilidade_turno IN ($turnos) AND e.id_pessoa = a.id_pessoa) > 0";
        }

        if ($contratacao) {
            $filtro_contratacao = " AND (select count(*) FROM tb_pessoa_pessoa_rh_formato_contratacao g WHERE g.id_pessoa_rh_formato_contratacao IN ($contratacao) AND g.id_pessoa = a.id_pessoa) > 0";
        }

        if ($participou == 1) {
            $filtro_participou = " AND (select count(*) FROM tb_selecao_candidato h WHERE h.id_pessoa_candidato = a.id_pessoa) > 0";
        } else if ($participou == 2) {
            $filtro_participou = " AND (select count(*) FROM tb_selecao_candidato h WHERE h.id_pessoa_candidato = a.id_pessoa) = 0";
        }

        if ($pcd) {
            $filtro_pcd =  "AND b.pcd = '".$pcd."' ";
        }

        if ($possui_equipamento) {
            $filtro_possui_equipamento =  "AND b.possui_equipamento = '".$possui_equipamento."' ";
        }

        if ($trabalha_empresa == 1) {
            $filtro_trabalha_empresa = "AND c.status = 1";

        } else if ($trabalha_empresa == 2) {
            $filtro_trabalha_empresa = "AND c.status = 0";
        }

        if ($tag) {
            $filtro_tag = " AND (select count(*) FROM tb_pessoa_rh_tag m WHERE m.id_tag = $tag AND m.id_pessoa = a.id_pessoa) > 0";
        }

        if ($cidade) {
            $filtro_cidade =  "AND a.id_cidade = '".$cidade."' ";
        }
    

        if ($participando == 1) {
            $filtro_participando = "AND (select count(*) FROM tb_selecao_candidato n INNER JOIN tb_selecao o ON n.id_selecao = o.id_selecao WHERE n.id_pessoa_candidato = a.id_pessoa AND o.status = 1) > 0";

        } else if ($participando == 2) {
            $filtro_participando = "AND (select count(*) FROM tb_selecao_candidato n INNER JOIN tb_selecao o ON n.id_selecao = o.id_selecao WHERE n.id_pessoa_candidato = a.id_pessoa AND o.status = 1) = 0";
        }
    }   
	
	echo "<div class=\"col-xs-12\" style=\"padding: 0\">";
	echo "<legend class=\"noprint\" style=\"text-align:center;\"><strong>Relatório de Candidatos</strong><br><span style=\"font-size: 14px;\"><strong>Gerado em:</strong> $data_hora</span></legend>";	
    echo $legenda_periodo_amostra;

    $dados = DBRead('', 'tb_pessoa a', "INNER JOIN tb_pessoa_rh_dados_pessoais b ON a.id_pessoa = b.id_pessoa LEFT JOIN tb_usuario c ON a.id_pessoa = c.id_pessoa WHERE a.id_pessoa $filtro_data $filtro_setor $filtro_cargo $filtro_escolaridade $filtro_disponibilidade_dias $filtro_idade $filtro_estudado $filtro_turno $filtro_compart_dados $filtro_pcd $filtro_contratacao $filtro_participou $filtro_pessoa $filtro_possui_equipamento $filtro_trabalha_empresa $filtro_tag $filtro_participando $filtro_cidade AND (select count(*) FROM tb_usuario_rh f WHERE f.id_pessoa_usuario = a.id_pessoa) > 0 ORDER BY a.nome ASC", "a.*, b.*, b.data_atualizacao AS 'data_atualizacao_curriculo', c.id_usuario, c.status");

	if($dados){
        
        $qtd = sizeof($dados);

        echo "<legend class=\"noprint\" style=\"text-align:center;\"><span style=\"font-size: 14px;\"><strong>Total de candidatos:</strong> $qtd</span></legend>";

        foreach ($dados as $conteudo) {
            
            $estado = DBRead('', 'tb_cidade a', "INNER JOIN tb_estado b ON a.id_estado = b.id_estado WHERE a.id_cidade = '".$conteudo['id_cidade']."' ", 'a.nome as nome_cidade, b.nome as nome_estado, b.sigla');

            $endereco = $conteudo['logradouro'].', '.$conteudo['numero'].', '.$conteudo['bairro'].', '.$estado[0]['nome_cidade'].', '.$estado[0]['sigla'];

            if ($conteudo['estado_civil'] == 1) {
                $estado_civil = 'Solteiro';
            } else if ($conteudo['estado_civil'] == 2) {
                $estado_civil = 'Casado';
            } else if ($conteudo['estado_civil'] == 3) {
                $estado_civil = 'Separado';
            } else if ($conteudo['estado_civil'] == 4) {
                $estado_civil = 'Divorciado';
            } else if ($conteudo['estado_civil'] == 5) {
                $estado_civil = 'Viúvo';
            }

            if ($conteudo['sexo'] == 'm') {
                $genero = 'Masculino';
            } else if ($conteudo['sexo'] == 'f') {
                $genero = 'Feminino';
            } else if ($conteudo['sexo'] == 'n') {
                $genero = 'Não me identifico com nenhuma das alternativas';
            }

            if ($conteudo['escolaridade'] == 1) {
                $escolaridade = 'Primeiro grau completo';
            } else if ($conteudo['escolaridade'] == 2) {
                $escolaridade = 'Primeiro grau incompleto';
            } else if ($conteudo['escolaridade'] == 3) {
                $escolaridade = 'Segundo grau completo';
            } else if ($conteudo['escolaridade'] == 4) {
                $escolaridade = 'Segundo grau incompleto';
            } else if ($conteudo['escolaridade'] == 5) {
                $escolaridade = 'Superior completo';
            } else if ($conteudo['escolaridade'] == 6) {
                $escolaridade = 'Superior incompleto';
            } else if ($conteudo['escolaridade'] == 7) {
                $escolaridade = 'Pós-graduação completa';
            } else if ($conteudo['escolaridade'] == 8) {
                $escolaridade = 'Pós-graduação incompleta';
            } else if ($conteudo['escolaridade'] == 9) {
                $escolaridade = 'Mestrado completo';
            } else if ($conteudo['escolaridade'] == 10) {
                $escolaridade = 'Mestrado incompleto';
            } else if ($conteudo['escolaridade'] == 11) {
                $escolaridade = 'Doutorado completo';
            } else if ($conteudo['escolaridade'] == 12) {
                $escolaridade = 'Doutorado incompleto';
            }

            if ($conteudo['disponibilidade_dias'] == 1) {
                $disponibilidade_dias = 'De segunda a sexta-feira';
            } else if ($conteudo['disponibilidade_dias'] == 2) {
                $disponibilidade_dias = 'De segunda a sábado';
            } else if ($conteudo['disponibilidade_dias'] == 3) {
                $disponibilidade_dias = 'Todos os dias da semana (inclusive domingos e feriados)';
            }

            if ($conteudo['pcd'] == 1) {
                $pcd = 'Sim';
            } else if ($conteudo['pcd'] == 2) {
                $pcd = 'Não';
            }

            if($conteudo['possui_equipamento'] == 1) {
                $possui_equipamento = 'Sim';
            }else if ($conteudo['possui_equipamento'] == 2) {
                $possui_equipamento = 'Não';
            }else{
                $possui_equipamento = 'Sem resposta';
            }

            if ($conteudo['permite_compart_dados'] == 1) {
                $permite_compart_dados = 'Sim';

            } else if ($conteudo['permite_compart_dados'] == 2) {
                $permite_compart_dados = 'Não';
            }

            $disponibilidade_turno = DBRead('', 'tb_pessoa_pessoa_rh_disponibilidade_turno a', "INNER JOIN tb_pessoa_rh_disponibilidade_turno b ON a.id_pessoa_rh_disponibilidade_turno = b.id_pessoa_rh_disponibilidade_turno WHERE a.id_pessoa = '".$conteudo['id_pessoa']."' ", 'b.descricao');

            $turnos = '';
            foreach ($disponibilidade_turno as $descricao) {
                $turnos .= $descricao['descricao'].', ';
            }
            $turnos = substr($turnos, 0, -2);

            $formato_contratacao = DBRead('', 'tb_pessoa_pessoa_rh_formato_contratacao a', "INNER JOIN tb_pessoa_rh_formato_contratacao b ON a.id_pessoa_rh_formato_contratacao = b.id_pessoa_rh_formato_contratacao WHERE a.id_pessoa = '".$conteudo['id_pessoa']."' ", 'b.descricao');

            $formato = '';
            foreach ($formato_contratacao as $descricao) {
                $formato .= $descricao['descricao'].', ';
            }
            $formato = substr($formato, 0, -2);

            $como_chegou = DBRead('', 'tb_pessoa_pessoa_rh_chegou_belluno a', "INNER JOIN tb_pessoa_rh_chegou_belluno b ON a.id_pessoa_rh_chegou_belluno = b.id_pessoa_rh_chegou_belluno WHERE a.id_pessoa = '".$conteudo['id_pessoa']."' ", 'b.descricao');

            $chegou = '';
            foreach ($como_chegou as $descricao) {
                $chegou .= $descricao['descricao'].', ';
            }
            $chegou = substr($chegou , 0, -2);

            $conhecidos_na_empresa = $conteudo['conhecidos_na_empresa'];

            if ($conteudo['id_usuario']) {
                if ($conteudo['status'] == 1) {
                    $legenda_funcionario = 'Funcionário(a)';
                } else {
                    $legenda_funcionario = 'Ex-funcionário(a)';
                }
            } else {
                $legenda_funcionario = '';
            }
    ?>
        
        <div class="container-fluid">
            <div class="row">
                
                <div class="col-xs-8 col-xs-offset-2 col-print-8">
                    <div class="jumbotron" style="border: 2px solid #A4A4A4; background-color: #FAFAFA; border-radius: 2px; padding: 40px; position: relative;">
                        <div class="row">
                            
                            <div class="row" id="row_tag_<?=$conteudo['id_pessoa']?>">
                                <div class="col-md-12">
                                    <?php
                                        $tags = DBRead('', 'tb_tag', "ORDER BY descricao ASC");
                                        $tags_pessoa = DBRead('', 'tb_pessoa_rh_tag a', "INNER JOIN tb_tag b ON a.id_tag = b.id_tag WHERE id_pessoa = '".$conteudo['id_pessoa']."' ");
                                    ?>

                                    <labe>Tags:</labe>
                                    <div class="input-group">
                                        <select class="js-example-basic-multiple form-control input-sm" id="<?=$conteudo['id_pessoa']?>" multiple="multiple">
                                            <?php 
                                                foreach ($tags as $tag) {
                                                    $selected = '';
                                                    foreach ($tags_pessoa as $tp) {
                                                        if ($tp['id_tag'] == $tag['id_tag']) {
                                                            $selected = 'selected';
                                                        }
                                                    }
                                                    echo '<option value="'.$tag['descricao'].'" '.$selected.' >'.$tag['descricao'].'</option>';
                                                }
                                            ?>

                                        </select><br><br>
                                        <span class="input-group-addon btn btn-xs" id="check" style="border: none; border-radius: 2px;" onclick="tags(<?= $conteudo['id_pessoa'] ?>)">
                                            <i class="fa fa-check" id="check2" aria-hidden="true"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xs-4" id="teste" style="background-color: #D8D8D8; height: 100%; border-radius: 10px;">

                                <div class="row" id="row-imagem-relatorio" style="display: block; padding-top: 10px;">
                                    <div class="col-md-12 text-center">                                        
                                        <img src="<?=$conteudo['foto']?>" class="center text-center" id="img-relatorio" alt="Image">
                                    </div>
                                </div>
                                <br>
                                <div class="row" style="padding-bottom: 11px;">
                                    <div class="col-md-12">
                                        <span class="fonts-curriculo"><strong>Telefone:</strong></span>
                                        <span class="fonts-curriculo phone"><?=$conteudo['fone1']?> <span>
                                    </div>
                                </div>
                                <div class="row" style="padding-bottom: 11px;">
                                    <div class="col-md-12">
                                        <span class="fonts-curriculo"><strong>Email:</strong></span>
                                        <span class="fonts-curriculo"> <?=mb_strtolower($conteudo['email1'])?> <span>
                                    </div>
                                </div>
                                <div class="row" style="padding-bottom: 11px;">
                                    <div class="col-md-12">
                                        <span class="fonts-curriculo"><strong>Skype:</strong></span>
                                        <span class="fonts-curriculo"> <?=mb_strtolower($conteudo['skype'])?> <span>
                                    </div>
                                </div>
                                <div class="row" style="padding-bottom: 11px;">
                                    <div class="col-md-12">
                                        <span class="fonts-curriculo"><strong>Twitter:</strong></span>
                                        <span class="fonts-curriculo"> <?=mb_strtolower($conteudo['twitter'])?> <span>
                                    </div>
                                </div>
                                <div class="row" style="padding-bottom: 11px;">
                                    <div class="col-md-12">
                                        <span class="fonts-curriculo"><strong>Instagram:</strong></span>
                                        <span class="fonts-curriculo"> <?=mb_strtolower($conteudo['instagram'])?> <span>
                                    </div>
                                </div>
                                <div class="row" style="padding-bottom: 11px;">
                                    <div class="col-md-12">
                                        <span class="fonts-curriculo"><strong>Linkedin:</strong></span>
                                        <span class="fonts-curriculo"> <?=mb_strtolower($conteudo['linkedin'])?> <span>
                                    </div>
                                </div>
                                <div class="row" style="padding-bottom: 11px;">
                                    <div class="col-md-12">
                                        <span class="fonts-curriculo"><strong>Facebook:</strong></span>
                                        <span class="fonts-curriculo"> <?=mb_strtolower($conteudo['facebook'])?> <span>
                                    </div>
                                </div>
                                <div class="row" style="padding-bottom: 11px;">
                                    <div class="col-md-12">
                                        <span class="fonts-curriculo"><strong>Endereço:</strong></span>
                                        <span class="fonts-curriculo"> <?=mb_strtolower($endereco)?><span>
                                    </div>
                                </div>
                                <div class="row" style="padding-bottom: 11px;">
                                    <div class="col-md-12">
                                        <?php 
                                            $date = getDataHora();
                                            
                                            $data1= date('Y-m-d H:i:s', strtotime("+0 days",strtotime($date)));
                                            $data2 = date('Y-m-d H:i:s', strtotime("+0 days",strtotime($conteudo['data_nascimento'])));

                                            if($data1 > $data2){
                                                $aux = $data1;
                                                $data1 = $data2;
                                                $data2 = $aux;
                                            }
                                            
                                            $idade = strtotime($data2) - strtotime($data1);

                                            $y = ($idade/(60*60*24*365));

                                            $auxiliar = explode ("E", $y);
                                            if($auxiliar[1]){
                                                $y=0;
                                            }
                                            $y = explode (".", $y);
                                            $y = $y[0];  
                                        ?>
                                        <span class="fonts-curriculo"><strong>Data de nascimento:</strong></span>
                                        <span class="fonts-curriculo"> <?=converteData($conteudo['data_nascimento'])?> (<?= $y ?> anos)<span>
                                    </div>
                                </div>
                                <div class="row" style="padding-bottom: 11px;">
                                    <div class="col-md-12">
                                        <span class="fonts-curriculo"><strong>CPF:</strong></span>
                                        <span class="fonts-curriculo"><?=formataCampo('cpf_cnpj',$conteudo['cpf_cnpj'])?><span>
                                    </div>
                                </div>
                                <div class="row" style="padding-bottom: 11px;">
                                    <div class="col-md-12">
                                        <span class="fonts-curriculo"><strong>RG:</strong></span>
                                        <span class="fonts-curriculo"> <?=$conteudo['inscricao_estadual']?> <span>
                                    </div>
                                </div>
                                <div class="row" style="padding-bottom: 11px;">
                                    <div class="col-md-12">
                                        <span class="fonts-curriculo"><strong>Estado civíl:</strong></span>
                                        <span class="fonts-curriculo"> <?=$estado_civil?> <span>
                                    </div>
                                </div>
                                <div class="row" style="padding-bottom: 11px;">
                                    <div class="col-md-12">
                                        <span class="fonts-curriculo"><strong>Gênero:</strong></span>
                                        <span class="fonts-curriculo"> <?=$genero?> <span>
                                    </div>
                                </div>
                                <div class="row" style="padding-bottom: 11px;">
                                    <div class="col-md-12">
                                        <span class="fonts-curriculo"><strong>Nome da Mãe:</strong></span>
                                        <span class="fonts-curriculo"><?=$conteudo['nome_mae']?> <span>
                                    </div>
                                </div>
                                <div class="row" style="padding-bottom: 11px;">
                                    <div class="col-md-12">
                                        <span class="fonts-curriculo"><strong>Nacionalidade:</strong></span>
                                        <span class="fonts-curriculo"><?=mb_strtolower($conteudo['nacionalidade'])?> <span>
                                    </div>
                                </div>
                                <div class="row" style="padding-bottom: 11px;">
                                    <div class="col-md-12">
                                        <span class="fonts-curriculo"><strong>Naturalidade:</strong></span>
                                        <span class="fonts-curriculo"> <?=mb_strtolower($conteudo['naturalidade'])?> <span>
                                    </div>
                                </div>
                                <div class="row" style="padding-bottom: 11px;">
                                    <div class="col-md-12">
                                        <span class="fonts-curriculo"><strong>Título de eleitor:</strong></span>
                                        <span class="fonts-curriculo"> <?=$conteudo['titulo_eleitor']?> <span>
                                    </div>
                                </div>
                                <div class="row" style="padding-bottom: 11px;">
                                    <div class="col-md-12">
                                        <span class="fonts-curriculo"><strong>Carteira de trabalho:</strong></span>
                                        <span class="fonts-curriculo"> <?=$conteudo['carteira_trabalho']?> <span>
                                    </div>
                                </div>
                                <div class="row" style="padding-bottom: 11px;">
                                    <div class="col-md-12">
                                        <span class="fonts-curriculo"><strong>Série da carteira de trabalho:</strong></span>
                                        <span class="fonts-curriculo"> <?=$conteudo['serie_carteira_trabalho']?> <span>
                                    </div>
                                </div>
                                <div class="row" style="padding-bottom: 11px;">
                                    <div class="col-md-12">
                                        <span class="fonts-curriculo"><strong>PIS/PASEP:</strong></span>
                                        <span class="fonts-curriculo"><?=$conteudo['pis_pasep']?> <span>
                                    </div>
                                </div>
                                <div class="row" style="padding-bottom: 11px;">
                                    <div class="col-md-12">
                                        <span class="fonts-curriculo"><strong>Escolaridade:</strong></span>
                                        <span class="fonts-curriculo"><?=$escolaridade?> <span>
                                    </div>
                                </div>
                                <div class="row" style="padding-bottom: 11px;">
                                    <div class="col-md-12">
                                        <span class="fonts-curriculo"><strong>Disponibilidade de dias:</strong></span>
                                        <span class="fonts-curriculo"> <?=$disponibilidade_dias?> <span>
                                    </div>
                                </div>
                                <div class="row" style="padding-bottom: 11px;">
                                    <div class="col-md-12">
                                        <span class="fonts-curriculo"><strong>PCD:</strong></span>
                                        <span class="fonts-curriculo"> <?=$pcd?> <span>
                                    </div>
                                </div>

                                <div class="row" style="padding-bottom: 11px;">
                                    <div class="col-md-12">
                                        <span class="fonts-curriculo"><strong>Possui computador/notebook em boas condições:</strong></span>
                                        <span class="fonts-curriculo"> <?=$possui_equipamento?> <span>
                                    </div>
                                </div>

                                <div class="row" style="padding-bottom: 11px;">
                                    <div class="col-md-12">
                                        <span class="fonts-curriculo"><strong>Pretensão salarial:</strong></span>
                                        <span class="fonts-curriculo"> R$ <?=converteMoeda($conteudo['pretensao_salarial'])?> <span>
                                    </div>
                                </div>
                                <div class="row" style="padding-bottom: 11px;">
                                    <div class="col-md-12">
                                        <span class="fonts-curriculo"><strong>Disponibilidade de turnos:</strong></span>
                                        <span class="fonts-curriculo"> <?=$turnos?> <span>
                                    </div>
                                </div>
                                <div class="row" style="padding-bottom: 11px;">
                                    <div class="col-md-12">
                                        <span class="fonts-curriculo"><strong>Formato de contratação disponível:</strong></span>
                                        <span class="fonts-curriculo"> <?=$formato?> <span>
                                    </div>
                                </div>
                                <div class="row" style="padding-bottom: 11px;">
                                    <div class="col-md-12">
                                        <span class="fonts-curriculo"><strong>Como chegou até a belluno:</strong></span>
                                        <span class="fonts-curriculo"> <?=$chegou?> <span>
                                    </div>
                                </div>
                                <div class="row" style="padding-bottom: 11px;">
                                    <div class="col-md-12">
                                        <span class="fonts-curriculo"><strong>Data do cadastro:</strong></span>
                                        <span class="fonts-curriculo"> <?=converteDataHora($conteudo['data_cadastro'])?> <span>
                                    </div>
                                </div>
                                <div class="row" style="padding-bottom: 11px;">
                                    <div class="col-md-12">
                                        <span class="fonts-curriculo"><strong>Data da última atualização:</strong></span>
                                        <span class="fonts-curriculo"> <?=converteDataHora($conteudo['data_atualizacao_curriculo'])?> <span>
                                    </div>
                                </div>
                                <div class="row" style="padding-bottom: 11px;">
                                    <div class="col-md-12">
                                        <span class="fonts-curriculo"><strong>Permite compartilhar dados:</strong></span>
                                        <span class="fonts-curriculo"> <?=$permite_compart_dados?> <span>
                                    </div>
                                </div>
                                <div class="row" style="padding-bottom: 11px;">
                                    <div class="col-md-12">
                                        <span class="fonts-curriculo"><strong>Conhece alguém na empresa:</strong></span>
                                        <span class="fonts-curriculo"><?= $conhecidos_na_empresa ?><span>
                                    </div>
                                </div>
                                <br>
                            </div>

                            <div class="col-xs-8" style="padding-left: 50px;">
                                <div class="row">
                                    <div class="col-md-12">
                                        <h1 class="text-center" style="font-size: 32px;">
                                            <strong><?=$conteudo['nome']?></strong>
                                        </h1>

                                        <?php
                                            $verifica = DBRead('', 'tb_selecao_candidato', "WHERE id_pessoa_candidato = '".$conteudo['id_pessoa']."' ", 'COUNT(*) as cont');

                                            if ($verifica[0]['cont'] > 0) {
                                        ?>

                                            <h5 class="text-center noprint" style="font-size: 12px;">
                                                <a class="" href="/api/iframe?token=<?php echo $request->token ?>&view=relatorio-selecao&tiporelatorio=2&id_pessoa=<?= $conteudo['id_pessoa'] ?>" target="blank">
                                                    <strong>(Já participou de processo seletivo)</strong>
                                                </a>
                                            </h5>

                                            <h5 class="text-center" id="aviso" style="font-size: 12px; display: none">
                                                <strong>(Já participou de processo seletivo)</strong>
                                            </h5>

                                        <?php 
                                            }

                                            if ($conteudo['id_usuario']) {
                                        ?>
                                                <h5 class="text-center noprint" style="font-size: 12px;">
                                                        <strong><?= $legenda_funcionario ?></strong>
                                                        <br>
                                                </h5>

                                        <?php 
                                            }
                                        ?>

                                        <hr class="hr-curriculo text-center">

                                        <ol class="breadcrumb" style="background-color: #D8D8D8"> 
                                            <li class="active" style="color: black"><strong>Educação/Curso</strong></li>
                                        </ol>

                                        <?php 
                                            $dados_formacao = DBRead('', 'tb_pessoa_rh_formacao_curso', "WHERE id_pessoa = '".$conteudo['id_pessoa']."' ");
                                            if ($dados_formacao) {
                                                foreach ($dados_formacao as $conteudo_formacao) {

                                                    if ($conteudo_formacao['tipo'] == 1) {
                                                        $tipo = 'Ensino fundamental';
                                                    } else if ($conteudo_formacao['tipo'] == 2) {
                                                        $tipo = 'Ensino médio';
                                                    } else if ($conteudo_formacao['tipo'] == 3) {
                                                        $tipo = 'Curso/Certificação';
                                                    } else if ($conteudo_formacao['tipo'] == 4) {
                                                        $tipo = 'Graduação';
                                                    } else if ($conteudo_formacao['tipo'] == 5) {
                                                        $tipo = 'Pós-graduacao';
                                                    } else if ($conteudo_formacao['tipo'] == 6) {
                                                        $tipo = 'Mestrado';
                                                    } else if ($conteudo_formacao['tipo'] == 7) {
                                                        $tipo = 'Doutorado';
                                                    } else if ($conteudo_formacao['tipo'] == 7) {
                                                        $tipo = 'Doutorado';
                                                    } else if ($conteudo_formacao['tipo'] == 7) {
                                                        $tipo = 'Doutorado';
                                                    } else if ($conteudo_formacao['tipo'] == 7) {
                                                        $tipo = 'Doutorado';
                                                    } else if ($conteudo_formacao['tipo'] == 7) {
                                                        $tipo = 'Doutorado';
                                                    }

                                                    if ($conteudo_formacao['turno'] == 1) {
                                                        $turno_formacao = 'Manhã';
                                                    } else if ($conteudo_formacao['turno'] == 2) {
                                                        $turno_formacao = 'Tarde';
                                                    } else if ($conteudo_formacao['turno'] == 3) {
                                                        $turno_formacao = 'Noite';
                                                    } else if ($conteudo_formacao['turno'] == 4) {
                                                        $turno_formacao = 'integral';
                                                    }
    
                                        ?>
                                                <div class="media" style="padding-bottom: 15px;">
                                                    <div class="media-left">
                                                        <i class="fas fa-graduation-cap" style="font-size: 16px;"></i>
                                                    </div>
                                                    <div class="media-body"> 
                                                        <h4 class="media-heading">
                                                            <strong>
                                                                <?=$conteudo_formacao['nome_curso']?> - <?=$conteudo_formacao['nome_instituicao']?>
                                                            </strong>
                                                        </h4> 
                                                        <span>
                                                            <strong>Tipo:</strong>
                                                            <?=$tipo?>
                                                        </span><br>
                                                        
                                                        <?php 
                                                            if ($conteudo_formacao['ano_conclusao'] != '') {
                                                        ?> 
                                                                <span>
                                                                    <strong>Ano de conclusão:</strong>
                                                                    <?=$conteudo_formacao['ano_conclusao']?>
                                                                </span><br>
                                                        <?php 
                                                            }
                                                        ?>
                                                        <span>
                                                            <strong>Turno:</strong>
                                                            <?=$turno_formacao?>
                                                        </span><br>
                                                    </div>
                                                </div>
                                        <?php 
                                                }
                                            } else {
                                        ?>
                                                <div class="text-center">
                                                    <span>Não há informações cadastradas!</span>
                                                </div>
                                        <?php        
                                            }
                                        ?>

                                        <br>
                                        <ol class="breadcrumb" style="background-color: #D8D8D8"> 
                                            <li class="active" style="color: black"><strong>Histórico profissional</strong></li>
                                        </ol>

                                        <?php
                                            $dados_profissional = DBRead('', 'tb_pessoa_rh_profissional a', " INNER JOIN tb_pessoa_rh_area_atuacao b ON a.area = b.id_pessoa_rh_area_atuacao WHERE id_pessoa = '".$conteudo['id_pessoa']."' ", 'a.*, b.descricao as area_atuacao');

                                            if ($dados_profissional) {

                                                foreach ($dados_profissional as $conteudo_profissional) {

                                                    if ($conteudo_profissional['data_inicio'] != '' && $conteudo_profissional['data_saida'] != '') {
                                                        $data_exp = 'De '.converteData($conteudo_profissional['data_inicio']).' a '.converteData($conteudo_profissional['data_saida']);
                                                    } else {
                                                        $data_exp = 'Iniciou em: '.converteData($conteudo_profissional['data_inicio']);
                                                    }

                                        ?>              
                                                    <div class="media" style="padding-bottom: 18px;">
                                                        <div class="media-left">
                                                            <i class="fas fa-suitcase" style="font-size: 16px;">&nbsp</i>
                                                        </div>
                                                        <div class="media-body"> 
                                                            <h4 class="media-heading">
                                                                <strong>
                                                                    <?=$data_exp?> - <?=$conteudo_profissional['nome_empresa']?>
                                                                </strong>
                                                            </h4>
                                                            <span>
                                                                <strong>Área:</strong> 
                                                                <?=$conteudo_profissional['area_atuacao']?>
                                                            </span><br>
                                                            <span>
                                                                <strong>Funções:</strong>
                                                                <?=nl2br(mb_strtolower($conteudo_profissional['funcoes']))?>
                                                            </span><br>       
                                                        </div>
                                                    </div>
                                        <?php
                                                }
                                            } else {
                                        ?>
                                                <div class="text-center">
                                                    <span>Não há informações cadastradas!</span>
                                                </div>
                                        <?php
                                            }
                                        ?>

                                        <br>
                                        <ol class="breadcrumb" style="background-color: #D8D8D8"> 
                                            <li class="active" style="color: black"><strong>Outros conhecimentos</strong></li>
                                        </ol>

                                        <?php
                                            $dados_conhecimentos = DBRead('', 'tb_pessoa_rh_outros_conhecimentos', "WHERE id_pessoa = '".$conteudo['id_pessoa']."'  ");

                                            if ($dados_conhecimentos) {
                                                
                                                foreach ($dados_conhecimentos as $conteudo_conhecimentos) {

                                                    if ($conteudo_conhecimentos['tipo'] == '1') {
                                                        $tipo_conhecimento = 'Línguas';

                                                        if ($conteudo_conhecimentos['idioma'] == '1') {
                                                            $idioma = 'Inglês';
                                                        } else if ($conteudo_conhecimentos['idioma'] == '2') {
                                                            $idioma = 'Espanhol';
                                                        }

                                                    } else  if ($conteudo_conhecimentos['tipo'] == '2') {
                                                        $tipo_conhecimento = 'Informática';
                                                    }

                                                    if ($conteudo_conhecimentos['nivel'] == '1') {
                                                        $nivel = 'Básico';
                                                    } else  if ($conteudo_conhecimentos['nivel'] == '2') {
                                                        $nivel = 'Intermediário';
                                                    } else  if ($conteudo_conhecimentos['nivel'] == '3') {
                                                        $nivel = 'Avançado';
                                                    } else  if ($conteudo_conhecimentos['nivel'] == '4') {
                                                        $nivel = 'Fluente';
                                                    }
                                        ?>
                                                    <div class="media" style="padding-bottom: 15px;">
                                                        <div class="media-left">
                                                            <i class="fas fa-graduation-cap" style="font-size: 16px;"></i>
                                                        </div>
                                                        <div class="media-body"> 
                                                            <h4 class="media-heading">
                                                                <strong><?=$tipo_conhecimento?></strong>
                                                            </h4> 

                                                            <?php 
                                                                if ($conteudo_conhecimentos['tipo'] == '1') {
                                                            ?>
                                                                <span >
                                                                    <strong>Idioma:</strong> <?=$idioma?>
                                                                </span><br>
                                                            <?php
                                                                }
                                                            ?>
                                                            
                                                            <span>
                                                                <strong>Nível:</strong> <?=$nivel?>
                                                            </span><br>   
                                                        </div>
                                                    </div>
                                        <?php
                                                }
                                            } else {
                                        ?>
                                                <div class="text-center">
                                                    <span>Não há informações cadastradas!</span>
                                                </div>
                                        <?php
                                            }
                                        ?>

                                        <br>
                                        <ol class="breadcrumb" style="background-color: #D8D8D8"> 
                                            <li class="active" style="color: black"><strong>Áreas de interesse</strong></li>
                                        </ol>

                                        <?php
                                            $areas_interesse = DBRead('', 'tb_pessoa_rh_area_interesse a', "INNER JOIN tb_setor b ON a.id_setor = b.id_setor WHERE id_pessoa = '".$conteudo['id_pessoa']."' ");


                                            if ($areas_interesse) {                                                
                                                
                                                foreach ($areas_interesse as $conteudo_interesse) {
                                                    $cargo = '';
                                                    if ($conteudo_interesse['id_cargo']) {
                                                        $dados_cargo = DBRead('', 'tb_cargo', "WHERE id_cargo = '".$conteudo_interesse['id_cargo']."' ");

                                                        if ($dados_cargo) {
                                                            $cargo = $dados_cargo[0]['descricao'];
                                                        }
                                                    }

                                        ?>
                                                    <div class="media" style="padding-bottom: 15px;">
                                                        <div class="media-left">
                                                            <i class="fas fa-suitcase" style="font-size: 16px;"></i>
                                                        </div>
                                                        <div class="media-body"> 
                                                            <h4 class="media-heading">
                                                                <strong><?=$conteudo_interesse['descricao']?> </strong>
                                                            </h4> 

                                                            <span>
                                                                <strong>Cargo:</strong> <?=$cargo?>
                                                            </span><br>
                                                            
                                                            <span>
                                                                <strong>Experiência:</strong> <?=$conteudo_interesse['experiencia']?>
                                                            </span><br>   
                                                        </div>
                                                    </div>
                                        <?php
                                                }
                                            } else {
                                        ?>
                                                <div class="text-center">
                                                    <span>Não há informações cadastradas!</span>
                                                </div>
                                        <?php
                                            }
                                        ?>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <br>
        <footer>
        </footer>

    <?php
        }
        
	} else {
        echo '<h4 class="text-center">Não foram encontrados resultados!</h4>';
    }
}

?>

<script>
    function tags(id){
        var tags = $('#'+id).val();

        $.get({
            url: "/api/ajax?class=Tag.php",
            dataType: "json",
            method: 'post',
            data: {
                acao: 'inserir',
                parametros: {                           
                    'id_pessoa' : id,
                    'tags' : tags                                  
                },
                token: '<?= $request->token ?>'
            },
            success: function (data) {
                if (data != false){
                    alert('Tags inseridas com sucesso!');
                    $('#'+id).val(data);
                    $('#'+id).trigger('change');

                } else {
                    alert('Não foi possivel inserir as tags!');
                }
            }
        });
    }
</script>