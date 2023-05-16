<?php

use PhpMyAdmin\Scripts;

require_once(__DIR__."/../class/System.php");

$id_usuario = $_SESSION['id_usuario'];

$dados = DBRead('', 'tb_usuario', "WHERE id_usuario = '$id_usuario'");
$id_perfil_sistema = $dados[0]['id_perfil_sistema'];
 
if (isset($_GET['alterar'])) {
	$tituloPainel = 'Alterar';
	$operacao = 'alterar';
	$id = (int) $_GET['alterar'];
	$dados = DBRead('', 'tb_contrato_plano_pessoa a ', "INNER JOIN tb_plano b ON a.id_plano = b.id_plano WHERE a.id_contrato_plano_pessoa = $id", "a.*,b.*, a.status AS status_contrato");
    $valor_unitario = converteMoeda($dados[0]['valor_unitario']);
	$valor_total = converteMoeda($dados[0]['valor_total']);
    $valor_excedente = converteMoeda($dados[0]['valor_excedente']);
    $valor_inicial = converteMoeda($dados[0]['valor_inicial']);
    $valor_plantao = converteMoeda($dados[0]['valor_plantao']);
    $data_inicio_contrato = ($dados[0]['data_inicio_contrato'] != '0000-00-00') ? converteData($dados[0]['data_inicio_contrato']) : '';
    $periodo_contrato = $dados[0]['periodo_contrato'];
    $qtd_contratada = $dados[0]['qtd_contratada'];

    $qtd_clientes_teto = $dados[0]['qtd_clientes_teto'];

    $id_plano = $dados[0]['id_plano'];
    $id_pessoa = $dados[0]['id_pessoa'];
    $status = $dados[0]['status_contrato'];
    $indice_reajuste = $dados[0]['indice_reajuste'];
    $dia_pagamento = $dados[0]['dia_pagamento'];
    $obs = $dados[0]['obs'];
    $tipo_cobranca = $dados[0]['tipo_cobranca'];
    $tipo_plantao = $dados[0]['tipo_plantao'];

    $data_inicial_cobranca = ($dados[0]['data_inicial_cobranca'] != '0000-00-00') ? converteData($dados[0]['data_inicial_cobranca']) : '';
    $data_final_cobranca = ($dados[0]['data_final_cobranca'] != '0000-00-00') ? converteData($dados[0]['data_final_cobranca']) : '';
    
    $tempo_fidelidade = $dados[0]['tempo_fidelidade'];
   
    //_____________________NOTA_________________________
        $dados_pessoa = DBRead('', 'tb_pessoa a', "INNER JOIN tb_cidade b ON a.id_cidade = b.id_cidade WHERE a.id_pessoa = '".$id_pessoa."' ", "a.*, b.id_cidade, b.nome AS 'nome_cidade'");
        $nome_pessoa = $dados_pessoa[0]['nome'];

        $cliente_id_cidade = $dados_pessoa[0]['id_cidade'];
        $cliente_logradouro = $dados_pessoa[0]['logradouro'];
        $cliente_numero = $dados_pessoa[0]['numero'];
        $cliente_bairro = $dados_pessoa[0]['bairro'];
        $cliente_cep = $dados_pessoa[0]['cep'];
        $cliente_razao_social = $dados_pessoa[0]['razao_social'];
        $cliente_cpf_cnpj = $dados_pessoa[0]['cpf_cnpj'];

    //__________________________________________________

    $data_status = converteData($dados[0]['data_status']);
    $data_atualizacao = converteDataHora($dados[0]['data_atualizacao']);
    $nome_status = getNomeStatusPlano($status);
    $nome_contrato = $dados[0]['nome_contrato'];
    $id_responsavel = $dados[0]['id_responsavel'];
    $id_responsavel_tecnico = $dados[0]['id_responsavel_tecnico'];
    
    $id_usuario_historico = $dados[0]['id_usuario'];
    $dados_usuario_atualizacao = DBRead('', 'tb_usuario a ', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_usuario = $id_usuario_historico");
    $nome_usuario_atualizacao = $dados_usuario_atualizacao[0]['nome'];

    $realiza_cobranca = $dados[0]['realiza_cobranca'];
    $recebe_ligacao = $dados[0]['recebe_ligacao'];
    $desafogo = $dados[0]['desafogo'];
    $remove_duplicados = $dados[0]['remove_duplicados'];
    $minutos_duplicados = $dados[0]['minutos_duplicados'];
    $desconsidera_notificacao = $dados[0]['desconsidera_notificacao'];
    $valor_desconsidera_notificacao = $dados[0]['valor_desconsidera_notificacao'];
    $cod_servico = $dados[0]['cod_servico'];
    $botao_pessoa = 'disabled';

    $data_ajuste = ($dados[0]['data_ajuste'] != '0000-00-00') ? converteData($dados[0]['data_ajuste']) : '';

    $qtd_clientes = $dados[0]['qtd_clientes'];
    
    $email_nf = $dados[0]['email_nf'];
    
    $reter_cofins = $dados[0]['reter_cofins'];
    $reter_csll = $dados[0]['reter_csll'];
    $reter_ir = $dados[0]['reter_ir'];
    $reter_pis = $dados[0]['reter_pis'];

    $pdf_contrato = $dados[0]['pdf_contrato'];
    
    $valor_adesao = converteMoeda($dados[0]['valor_adesao']);
        
    //-----------------------------------------------------------------------------------------------------------

        $id_contrato = $dados[0]['contrato_pai'];
        if($id_contrato){
           
            $contrato_pai = '1';
           
            $dados_contrato = DBRead('', 'tb_contrato_plano_pessoa a', "INNER JOIN tb_plano b ON a.id_plano = b.id_plano INNER JOIN tb_pessoa c ON a.id_pessoa = c.id_pessoa WHERE id_contrato_plano_pessoa = '$id_contrato'", "a.*, b.cod_servico, b.nome AS 'plano', c.nome AS 'nome_pessoa'");
            
            if($dados_contrato[0]['nome_contrato']){
                $nome_contrato_pai = " (".$dados_contrato[0]['nome_contrato'].") ";
            }

            $contrato = $dados_contrato[0]['nome_pessoa'] . " ". $nome_contrato_pai ." - " . getNomeServico($dados_contrato[0]['cod_servico']) . " - " . $dados_contrato[0]['plano'] . " (" . $dados_contrato[0]['id_contrato_plano_pessoa'] . ")";
        }else{
            $contrato_pai = '0';  
        }

    //-----------------------------------------------------------------------------------------------------------

    //-----------------------------------------------------------------------------------------------------------

    $id_contrato_separar = $dados[0]['separar_contrato'];
    if($id_contrato_separar){
       
        $separar_contrato = '1';
       
        $dados_contrato_separar = DBRead('', 'tb_contrato_plano_pessoa a', "INNER JOIN tb_plano b ON a.id_plano = b.id_plano INNER JOIN tb_pessoa c ON a.id_pessoa = c.id_pessoa WHERE id_contrato_plano_pessoa = '$id_contrato_separar'", "a.*, b.cod_servico, b.nome AS 'plano', c.nome AS 'nome_pessoa'");

        if($dados_contrato_separar[0]['nome_contrato']){
            $nome_contrato_separar = " (".$dados_contrato_separar[0]['nome_contrato'].") ";
        }

        $contrato_separar = $dados_contrato_separar[0]['nome_pessoa'] . " ". $nome_contrato__separar ." - " . getNomeServico($dados_contrato_separar[0]['cod_servico']) . " - " . $dados_contrato_separar[0]['plano'] . " (" . $dados_contrato_separar[0]['id_contrato_plano_pessoa'] . ")";
    }else{
        $separar_contrato = '0';  
    }

    $valor_diferente_texto = $dados[0]['valor_diferente_texto'];
    $qtd_contratada_texto = $dados[0]['qtd_contratada_texto'];
    $valor_unitario_texto = converteMoeda($dados[0]['valor_unitario_texto']);
    $valor_excedente_texto = converteMoeda($dados[0]['valor_excedente_texto']);

    $desafogo_texto = $dados[0]['desafogo_texto'];  
    $versao = $dados[0]['plano_versao'];
    $personalizado = $dados[0]['personalizado'];

    // $verifica_personalizado = explode('p', $versao);
    // if($verifica_personalizado[1]){
    //     $personalizado = 1;
    // }else{
    //     $personalizado = 0;
    // }

    $col = 'col-md-4';

    $dados = DBRead('', 'tb_contrato_plano_pessoa_historico a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa INNER JOIN tb_plano c ON a.id_plano = c.id_plano WHERE a.id_contrato_plano_pessoa = '$id' ORDER BY a.data_atualizacao DESC", "a.*, b.nome AS 'nome_pessoa', c.nome AS 'nome_plano', c.cod_servico");
    if($id_plano == 6){
        $disabled = 'disabled';
        $valor_unitario = 0;
    }

    //notifica se o faturamento ainda não foi gerado
    $data_faturamento = new DateTime(getDataHora('data'));
    $data_faturamento->modify('first day of last month');
    $dados_verificacao_faturamento = DBRead('', 'tb_faturamento a', "INNER JOIN tb_plano b ON a.id_plano = b.id_plano WHERE b.cod_servico = '".$cod_servico."' AND a.data_referencia = '".$data_faturamento->format('Y-m-d')."'");
 
    if($dados_verificacao_faturamento){
        $notifica_faturamento = 0;
    }else{
        $notifica_faturamento = 1;
    }

    $div_personalizado_display = "style='display: none'";
    $div_versao_display = "";

} else {
	$tituloPainel = 'Inserir';
	$operacao = 'inserir';
	$id = 1;
	$valor_unitario = '';
    $valor_excedente = '';
    $valor_plantao = '';
    $data_inicio_contrato = '';
    $periodo_contrato = '';
    $qtd_contratada = '';

    $qtd_clientes_teto = '';

    $data_inicial_cobranca = '';
    $data_final_cobranca = '';
    $tempo_fidelidade = '';

    $id_plano = '';
    $id_pessoa = '';
    $nome_pessoa = '';
    $status = '5';
    $indice_reajuste = '';
    $dia_pagamento = '';
    $obs = '';
    $tipo_cobranca = '';
    $tipo_plantao = '';
    $valor_inicial = '';
    $nome_contrato = '';    
    $col = 'col-md-12';
    $botao_pessoa = '';
    $data_ajuste = '';
    $notifica_faturamento = 0;
    $qtd_clientes = '';
    $id_responsavel = '';
    $id_responsavel_tecnico = '';
    $email_nf = '';
    $reter_cofins = '2';
    $reter_csll = '2';
    $reter_ir = '2';
    $reter_pis = '2';

    $div_personalizado_display = "style='display: none'";
    $div_versao_display = "style='display: none'";


}

if($id_perfil_sistema == 2 || $id_perfil_sistema == 10 || $id_perfil_sistema == 24 ){

    $flag_perfil = 1;

}
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    <div class="row">
                        <h3 class="panel-title pull-left text-left <?= $col ?>"><?=$tituloPainel?> contrato:</h3>
                        <?php
                            if (isset($_GET['alterar'])) {
                                echo "<h3 class='panel-title text-center col-md-4'>Atualizado em $data_atualizacao, por $nome_usuario_atualizacao</h3>";
                                echo "<h3 class='panel-title text-right col-md-4'>$nome_status desde: $data_status</h3>";
                            }
                        ?>
                    </div>
                </div>
                <form method="post" action="/api/ajax?class/Contrato.php" id="contrato_form" style="margin-bottom: 0;" enctype="multipart/form-data">
		            <input type="hidden" name="token" value="<?php echo $request->token ?>">
                    <div class="panel-body" style="padding-bottom: 0;">
                        <ul class="nav nav-tabs">
                            <li class="active"><a data-toggle="tab" href="#tab_dados">Dados</a></li>
                            <?php

                                if(isset($_GET['alterar']) && $dados){ 
                                    echo '<li><a data-toggle="tab" href="#tab_historico">Histórico</a></li>';
                                }

                                if($flag_perfil == 1){ 
                                    echo '<li><a data-toggle="tab" href="#tab_nfs">NFS-e</a></li>';
                                }

                                if($flag_perfil == 1){ 
                                    // echo '<li><a data-toggle="tab" href="#tab_faturamento">Faturamento</a></li>';
                                }

                                if(isset($_GET['alterar']) && $dados){ 
                                    echo '<li><a data-toggle="tab" href="#tab_procedimento">Procedimentos</a></li>';
                                }

                                if(isset($_GET['alterar'])){ 
                                    echo '<li><a data-toggle="tab" href="#tab_pdf">PDF do contrato</a></li>';
                                }
                            ?>
                        </ul>

                        <input type="hidden" name="cliente_id_cidade" id="cliente_id_cidade" value="<?=$cliente_id_cidade;?>">
                        <input type="hidden" name="cliente_logradouro" id="cliente_logradouro" value="<?=$cliente_logradouro;?>">
                        <input type="hidden" name="cliente_numero" id="cliente_numero" value="<?=$cliente_numero;?>">
                        <input type="hidden" name="cliente_bairro" id="cliente_bairro" value="<?=$cliente_bairro;?>">
                        <input type="hidden" name="cliente_cep" id="cliente_cep" value="<?=$cliente_cep;?>">
                        <input type="hidden" name="cliente_razao_social" id="cliente_razao_social" value="<?=$cliente_razao_social;?>">
                        <input type="hidden" name="cliente_cpf_cnpj" id="cliente_cpf_cnpj" value="<?=$cliente_cpf_cnpj;?>">

                        <div class="tab-content">
                            <div class="tab-pane fade in active" id="tab_dados" style="padding-top: 10px;">
                                <div class="row"> 
                                    <div class="col-md-8">
                                        <h4 style="margin-bottom: 10px;" id="h4_1">Dados Gerais:</h4>
                                    </div>
                                    <div class="col-md-4" id="div_status">
                                        <div class="input-group" style="margin-bottom: 10px;">
                                            <span class="input-group-addon" style="font-weight: 700; color: #333; background-color: white; border: 0px solid;">Status:</span>
                                            <?php
                                            if($id_perfil_sistema == '10' || $id_perfil_sistema == '24' || $id_perfil_sistema == '18'){
                                                echo '<select class="form-control input-sm" id="status" name="status" style="border-radius: 4px;">';
                                                if (isset($_GET['alterar'])) {
                                                    $sel_status[$status] = 'selected';
                                                    echo "<option value='1'".$sel_status[1].">".getNomeStatusPlano(1)."</option>";
                                                    echo "<option value='0'".$sel_status[0].">".getNomeStatusPlano(0)."</option>";
                                                    echo "<option value='2'".$sel_status[2].">".getNomeStatusPlano(2)."</option>";
                                                    echo "<option value='3'".$sel_status[3].">".getNomeStatusPlano(3)."</option>";
                                                    echo "<option value='4'".$sel_status[4].">".getNomeStatusPlano(4)."</option>";
                                                    echo "<option value='5'".$sel_status[5].">".getNomeStatusPlano(5)."</option>";
                                                    echo "<option value='6'".$sel_status[6].">".getNomeStatusPlano(6)."</option>";
                                                    echo "<option value='7'".$sel_status[7].">".getNomeStatusPlano(7)."</option>";
                                                }else{
                                                    if($id_perfil_sistema == '18'){
                                                        echo "<option value='1'>".getNomeStatusPlano(1)."</option>";
                                                        echo "<option value='0'>".getNomeStatusPlano(0)."</option>";
                                                        echo "<option value='2'>".getNomeStatusPlano(2)."</option>";
                                                        echo "<option value='3'>".getNomeStatusPlano(3)."</option>";
                                                        echo "<option value='4'>".getNomeStatusPlano(4)."</option>";
                                                        echo "<option value='5'>".getNomeStatusPlano(5)."</option>";
                                                        echo "<option value='6'>".getNomeStatusPlano(6)."</option>";
                                                        echo "<option value='7'>".getNomeStatusPlano(7)."</option>";
                                                    }else{
                                                        echo "<option value='5'>".getNomeStatusPlano(5)."</option>";
                                                    }
                                                }
                                            }else{
                                                echo '<input class="form-control input-sm" type="text" value="'.getNomeStatusPlano($status).'" disabled readonly>';
                                                echo '<input name="status" id="status" class="form-control input-sm" type="hidden" value="'.$status.'">';

                                            }
                                            
                                            ?>
                                            </select>
                                        </div>
                                        
                                    </div>                           
                                </div>
                                
                                <hr style="margin-top: 0px; margin-bottom: 20px;" id="hr_1">

                                <div class="row"> 
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>*Pessoa (cliente):</label>
                                            <div class="input-group">
                                                <input class="form-control input-sm" id="busca_pessoa" type="text" name="busca_pessoa"  value="<?=$nome_pessoa;?>" placeholder="Informe o nome ou CPF/CNPJ..." autocomplete="off" readonly required>
                                                <div class="input-group-btn">
                                                    <button class="btn btn-info btn-sm" id="habilita_busca_pessoa" name="habilita_busca_pessoa" type="button" title="Clique para selecionar a pessoa" style="height: 30px;" <?=$botao_pessoa;?>><i class="fa fa-search"></i></button>
                                                </div>
                                            </div>
                                            <input type="hidden" name="id_pessoa" id="id_pessoa" value="<?=$id_pessoa;?>">
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Nome do contrato:</label>
                                            <input name="nome_contrato" id="nome_contrato" class="form-control input-sm" type="text"  value="<?=$nome_contrato;?>" autocomplete="off">
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>*Responsável pelo Relacionamento:</label>
                                            <select class="form-control input-sm" id="id_responsavel" name="id_responsavel">
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

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>*Responsável Técnico:</label>
                                            <select class="form-control input-sm" id="id_responsavel_tecnico" name="id_responsavel_tecnico">
                                                <?php
                                                    $dados_responsavel_tecnico = DBRead('', 'tb_perfil_sistema a', "INNER JOIN tb_usuario b ON a.id_perfil_sistema = b.id_perfil_sistema INNER JOIN tb_pessoa c ON b.id_pessoa = c.id_pessoa WHERE a.id_perfil_sistema = 4 AND b.status = 1 ORDER BY c.nome ASC","b.id_usuario, c.nome");
                                                    
                                                    if ($dados_responsavel_tecnico) {
                                                        foreach ($dados_responsavel_tecnico as $conteudo_responsavel_tecnico) {
                                                            $selected = $id_responsavel_tecnico == $conteudo_responsavel['id_usuario'] ? "selected" : ""; 
                                                            echo "<option value='".$conteudo_responsavel_tecnico['id_usuario']."' ".$selected.">".$conteudo_responsavel_tecnico['nome']."</option>";
                                                        }
                                                    }
                                                ?>
                                            </select>   
                                        </div>
                                    </div>
                                    
                                </div>
                                <div class="row"> 
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>*Serviço:</label>
                                            <select class="form-control input-sm" id="servico" name="servico">
                                                <?php
                                                if (isset($_GET['alterar'])) {
                                                    $dados_plano = DBRead('', 'tb_plano', "WHERE cod_servico = '$cod_servico' GROUP BY cod_servico ORDER BY cod_servico ASC","cod_servico");
                                                    
                                                    if ($dados_plano) {
                                                        $sel_servico[$cod_servico] = 'selected';
                                                        foreach ($dados_plano as $conteudo) {
                                                            $selected = $cod_servico == $conteudo['cod_servico'] ? "selected" : ""; 
                                                            $servico_select = getNomeServico($conteudo['cod_servico']);
                                                            echo "<option value='".$conteudo['cod_servico']."' ".$selected.">$servico_select</option>";
                                                        }
                                                    }

                                                }else{
                                                    $dados_plano = DBRead('', 'tb_plano', "WHERE cod_servico != 'gestao_redes' GROUP BY cod_servico ORDER BY cod_servico ASC","cod_servico");
                                                    if ($dados_plano) {
                                                        echo "<option value=''></option>";
                                                        foreach ($dados_plano as $conteudo) {
                                                            $selected = $cod_servico == $conteudo['cod_servico'] ? "selected" : ""; 
                                                            $servico_select = getNomeServico($conteudo['cod_servico']);
                                                            echo "<option value='".$conteudo['cod_servico']."' ".$sel_servico[$conteudo['cod_servico']].">$servico_select</option>";
                                                        }
                                                    }
                                                }
                                                
                                                ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>*Plano:</label>
                                            <select class="form-control input-sm" id="id_plano" name="id_plano" required>
                                                <?php
                                                     echo "<option value='' disabled selected>Selecione um serviço!</option>";
                                                ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-3" id="div_tipo_cobranca">
                                        <div class="form-group">
                                            <label>*Tipo de Cobrança:</label>
                                            <select name="tipo_cobranca" id="tipo_cobranca" class="form-control input-sm">
                                                <option></option>
                                                <?php
                                                $sel_tipo_cobranca[$tipo_cobranca] = 'selected';
                                                echo "<option value='x_cliente_base'".$sel_tipo_cobranca['x_cliente_base']." id = 'option_x_cliente_base'>Até X Clientes na Base</option>";
                                                // echo "<option value='cliente_ativo'".$sel_tipo_cobranca['cliente_ativo']." id = 'option_cliente_ativo'>Clientes Ativos</option>";
                                                // echo "<option value='cliente_base'".$sel_tipo_cobranca['cliente_base']." id = 'option_cliente_base'>Clientes na Base</option>";
                                                // echo "<option value='horas'".$sel_tipo_cobranca['horas']." id = 'option_horas'>Horas</option>";
                                                // echo "<option value='ilimitado'".$sel_tipo_cobranca['ilimitado']." id = 'option_ilimitado'>Ilimitado</option>";
                                                echo "<option value='mensal'".$sel_tipo_cobranca['mensal']." id = 'option_mensal'>Mensal</option>";
                                                echo "<option value='mensal_desafogo'".$sel_tipo_cobranca['mensal_desafogo']." id = 'option_mensal_desafogo'>Mensal com Desafogo</option>";
                                                echo "<option value='unitario'".$sel_tipo_cobranca['unitario']." id = 'option_unitario'>Unitário</option>";
                                                echo "<option value='prepago'".$sel_tipo_cobranca['prepago']." id = 'option_prepago'>Pré-pago</option>";
                                                ?>
                                            </select>
                                        </div>  
                                    </div>

                                    <div class="col-md-3" id="div_tipo_plantao">
                                        <div class="form-group">
                                            <label>*Tipo de Plantão:</label>
                                            <select name="tipo_plantao" id="tipo_plantao" class="form-control input-sm">
                                                <option></option>
                                                <?php
                                                $sel_tipo_plantao[$tipo_plantao] = 'selected';
                                                echo "<option value='1'".$sel_tipo_plantao['1'].">30 em 30</option>";
                                                echo "<option value='2'".$sel_tipo_plantao['2'].">60 em 60</option>";
                                                echo "<option value='3'".$sel_tipo_plantao['3'].">60 em 60 proporcional</option>";
                                                echo "<option value='4'".$sel_tipo_plantao['4'].">Isento</option>";
                                                ?>
                                            </select>
                                        </div>  
                                    </div>

                                    
                                    <div class="col-md-3" id="div_versao" <?=$div_versao_display?>>
                                        <div class="form-group">
                                            <label>Versão (Plano):</label>
                                            <input type="text" name="versao" id="versao" class="form-control input-sm value="<?=$versao;?>" disabled> 
                                        </div>  
                                    </div>
                                
                                    <div class="col-md-3" <?=$div_personalizado_display?> id="div_personalizado">
                                        <div class="form-group">
                                            <label>*Versão Personalizada:</label>
                                            <select class="form-control input-sm" id="personalizado" name="personalizado" required>
                                                <?php
                                                
                                                echo "<option value='0'"; if($personalizado == '0'){ echo 'selected';} echo " id='procedimento_nao'>Não</option>";
                                                echo "<option value='1'"; if($personalizado == '1'){ echo 'selected';} echo " id='procedimento_sim'>Sim</option>";
                                                
                                                ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div id="resultado_busca"></div>
                                    

                                   

                                </div>

                                <h4 style="margin-bottom: 10px;" id="h4_2">Valores/Quantidades:</h4>
                                <hr style="margin-top: 0px; margin-bottom: 20px;" id="hr_2">

                                <div class="row">

                                    <div class="col-md-3" id="div_valor_adesao">
                                        <div class="form-group">
                                            <label>*Valor da Adesão:</label>
                                            <input name="valor_adesao" type="text" id="valor_adesao" class="form-control input-sm money" value="<?=$valor_adesao;?>" autocomplete="off" <?=$disabled_valor_adesao?>>
                                        </div>
                                    </div> 

                                    <div class="col-md-3" id="div_valor_diferente_texto">
                                        <div class="form-group">
                                            <label>*Valor Diferente para Texto:</label>
                                            <select class="form-control input-sm" id="valor_diferente_texto" name="valor_diferente_texto">
                                                <option value="0" <?php if($valor_diferente_texto == '0'){echo 'selected';}?>>Não</option>
                                                <option value="1" <?php if($valor_diferente_texto == '1'){echo 'selected';}?>>Sim</option>
                                            </select>
                                        </div>
                                    </div>
                               
                                    <div class="col-md-3" id="div_qtd_contratada">
                                        <div class="form-group">
                                            <label>*Qtd. Contratada (Atendimentos):</label>
                                            <input name="qtd_contratada" id="qtd_contratada" type="number" class="form-control input-sm number_int" value="<?=$qtd_contratada;?>" autocomplete="off" required />
                                        </div>
                                    </div>

                                    <div class="col-md-3" id="div_qtd_clientes_teto">
                                        <div class="form-group">
                                            <label>*Qtd. Contratada (Clientes):</label>
                                            <input name="qtd_clientes_teto" id="qtd_clientes_teto" type="number" class="form-control input-sm number_int" value="<?=$qtd_clientes_teto;?>" autocomplete="off" />
                                        </div>
                                    </div>

                                    <div class="col-md-3" id="div_qtd_contratada_texto">
                                        <div class="form-group">
                                            <label>*Qtd. Contratada do Texto:</label>
                                            <input name="qtd_contratada_texto" id="qtd_contratada_texto" type="number" class="form-control input-sm number_int" value="<?=$qtd_contratada_texto;?>" autocomplete="off"/>
                                        </div>
                                    </div>

                                    <div class="col-md-3" id="div_valor_unitario">
                                        <div class="form-group">
                                            <label>*Valor Unitário:</label>
                                            <input name="valor_unitario" id="valor_unitario" type="text" class="form-control input-sm money" value="<?=$valor_unitario;?>" autocomplete="off">
                                        </div>
                                    </div>

                                    <div class="col-md-3" id="div_valor_unitario_texto">
                                        <div class="form-group">
                                            <label>*Valor Unitário do Texto:</label>
                                            <input name="valor_unitario_texto" id="valor_unitario_texto" type="text" class="form-control input-sm money" value="<?=$valor_unitario_texto;?>" autocomplete="off">
                                        </div>
                                    </div>

                                    <div class="col-md-3" id="div_valor_total">
                                        <div class="form-group">
                                            <label>*Valor Total:</label>
                                            <input name="valor_total" id="valor_total" type="text" class="form-control input-sm money" value="<?=$valor_total;?>" autocomplete="off" required>
                                        </div>
                                    </div>

                                    <div class="col-md-3" id="div_valor_inicial">
                                        <div class="form-group">
                                            <label>*Valor Inicial:</label>
                                            <input type="text" name="valor_inicial" id="valor_inicial" class="form-control input-sm money" value="<?=$valor_inicial?>" autocomplete="off">
                                        </div>
                                    </div>

                                    <div class="col-md-3" id="div_valor_excedente">
                                        <div class="form-group">
                                            <label>*Valor Excedente (Unt):</label>
                                            <input name="valor_excedente" id="valor_excedente" type="text" class="form-control input-sm money" value="<?=$valor_excedente;?>" autocomplete="off">
                                        </div>
                                    </div>

                                    <div class="col-md-3" id="div_valor_excedente_texto">
                                        <div class="form-group">
                                            <label>*Valor Excedente do Texto (Unt):</label>
                                            <input name="valor_excedente_texto" id="valor_excedente_texto" type="text" class="form-control input-sm money" value="<?=$valor_excedente_texto;?>" autocomplete="off">
                                        </div>
                                    </div>

                                    <div class="col-md-3" id="div_valor_plantao" <?=$display_div_plantao?>>
                                        <div class="form-group">
                                            <label>*Valor Plantão (Unt):</label>
                                            <input name="valor_plantao" type="text" id="valor_plantao" class="form-control input-sm money" value="<?=$valor_plantao;?>" autocomplete="off">
                                        </div>
                                    </div>

                                    <div class="col-md-3" id="div_qtd_clientes">
                                        <div class="form-group">
                                            <label>*Quantidade de Clientes:</label>
                                            <input name="qtd_clientes" type="text" class="form-control input-sm number_int" value="<?=$qtd_clientes;?>" autocomplete="off" required>
                                        </div>
                                    </div>

                                </div>

                                <h4 style="margin-bottom: 10px;" id="h4_3">Dados Contratuais:</h4>
                                <hr style="margin-top: 0px; margin-bottom: 20px;" id="hr_3">

                                <div class="row">

                                    <div class="col-md-3" id="div_indice_reajuste">
                                        <div class="form-group">
                                            <label>*Índice de Reajuste:</label>
                                            <select name="indice_reajuste" id="indice_reajuste" class="form-control input-sm">
                                                <?php
                                                $sel_indice_reajuste[$indice_reajuste] = 'selected';
                                                echo "<option value='IPCA' ".$sel_indice_reajuste['IPCA'].">2% + IPCA</option>";
                                                echo "<option value='10%' ".$sel_indice_reajuste['10%'].">10%</option>";
                                                echo "<option value='15%' ".$sel_indice_reajuste['15%'].">15%</option>";
                                                echo "<option value='IGPM' ".$sel_indice_reajuste['IGPM'].">IGPM</option>";
                                                echo "<option value='INPC' ".$sel_indice_reajuste['INPC'].">INPC</option>";
                                                echo "<option value='IPCA_sem' ".$sel_indice_reajuste['IPCA_sem'].">IPCA</option>";
                                                echo "<option value='ND' ".$sel_indice_reajuste['ND'].">Não Definido</option>";
                                                ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-3" id="div_desafogo">
                                        <div class="form-group">
                                            <label>*Desafogo (%):</label>
                                            <select name="desafogo" id="desafogo" class="form-control input-sm">
                                                <?php
                                                $sel_desafogo[$desafogo] = 'selected';
                                                echo "<option value='20' ".$sel_desafogo['20'].">20%</option>";
                                                echo "<option value='40' ".$sel_desafogo['40'].">40%</option>";
                                                echo "<option value='50' ".$sel_desafogo['50'].">50%</option>";
                                                ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-3" id="div_desafogo_texto">
                                        <div class="form-group">
                                            <label>*Desafogo do Texto (%):</label>
                                            <select name="desafogo_texto" id="desafogo_texto" class="form-control input-sm">
                                                <?php
                                                $sel_desafogo_texto[$desafogo_texto] = 'selected';
                                                echo "<option value='20' ".$sel_desafogo_texto['20'].">20%</option>";
                                                echo "<option value='40' ".$sel_desafogo_texto['40'].">40%</option>";
                                                echo "<option value='50' ".$sel_desafogo_texto['50'].">50%</option>";
                                                ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-3" id="div_pagamento">
                                        <div class="form-group">
                                            <label>*Dia de Pagamento:</label>
                                            <input name="dia_pagamento" id="dia_pagamento" type="number" min="1" max="31" class="form-control input-sm number_int" value="<?=$dia_pagamento;?>" autocomplete="off" required>
                                        </div>
                                    </div>

                                    <div class="col-md-3 div_inicio" id="div_inicio">
                                        <div class="form-group">
                                            <label>*Data de Início do Contrato:
                                                <a tabindex="0" role="button" style="cursor:pointer;" data-toggle="tooltip" data-placement="right" title="Quando o contrato entrou em adesão!">
                                                    <i class="fa fa-question-circle"></i>
                                                </a>
                                            </label> 
                                            <input name="data_inicio_contrato" type="text" class="form-control data_inicio_contrato input-sm date calendar" value="<?=$data_inicio_contrato;?>" autocomplete="off" required>
                                        </div>
                                    </div>
                                    
                                    <!-- <div class="col-md-3" id="div_data_final_cobranca">
                                        <div class="form-group">
                                            <label style="color: red;">*Data Final da Cobrança:</label>
                                            <input name="data_final_cobranca" type="text" class="form-control data_final_cobranca input-sm date calendar" value="<?=$data_final_cobranca;?>" autocomplete="off" required>
                                        </div>
                                    </div> -->

                                    <div class="col-md-3" id="div_data_reajuste">
                                        <div class="form-group">
                                            <label>*Data do Próximo Reajuste:</label>
                                            <input name="data_ajuste" type="text" class="form-control data_ajuste input-sm date calendar" value="<?=$data_ajuste;?>" autocomplete="off" required>
                                        </div>
                                    </div>

                                    <div class="col-md-3" id="div_periodo">
                                        <div class="form-group">
                                            <label>*Período de Contrato (meses):</label>
                                            <input name="periodo_contrato" id="periodo_contrato" type="number" min="1" class="form-control input-sm number_int" value="<?=$periodo_contrato;?>" autocomplete="off" required>
                                        </div>
                                    </div>         

                                   
                                
                                </div>

                                <div class="row"> 
                                        <div class="col-md-4 div_inicio">
                                            <div class="form-group">
                                                <label>*Data Inicial da Cobrança:</label>
                                                <input name="data_inicial_cobranca" type="text" class="form-control input-sm date calendar data_inicial_cobranca" value="<?=$data_inicial_cobranca;?>" autocomplete="off">
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-4 div_inicio">
                                            <div class="form-group">
                                                <label>*Data Final da Cobrança:</label>
                                                <input name="data_final_cobranca" type="text" class="form-control input-sm date calendar data_final_cobranca" value="<?=$data_final_cobranca;?>" autocomplete="off">
                                            </div>
                                        </div>

                                        <div class="col-md-4 div_inicio">
                                            <div class="form-group">
                                                <label>*Tempo de Fidelidade (meses):</label>
                                                <input name="tempo_fidelidade" id="tempo_fidelidade" type="number" min="1" class="form-control input-sm number_int" value="<?=$tempo_fidelidade;?>" autocomplete="off">
                                            </div>
                                        </div>  

                                    </div>  

                                <h4 style="margin-bottom: 10px;" id="h4_4">Dados de Cobrança:</h4>
                                <hr style="margin-top: 0px; margin-bottom: 20px;" id="hr_4">

                                <div class="row">

                                    <div class="col-md-3" id="div_realiza_cobranca">
                                        <div class="form-group">
                                            <label>*Realiza Cobrança:</label>
                                            <select class="form-control input-sm" id="realiza_cobranca" name="realiza_cobranca">
                                                <option value="0" <?php if($realiza_cobranca == '0'){echo 'selected';}?>>Não</option>
                                                <option value="1" <?php if($realiza_cobranca == '1'){echo 'selected';}?>>Sim</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-3" id="div_recebe_ligacao">
                                        <div class="form-group">
                                            <label>*Recebe Ligações:</label>
                                            <select class="form-control input-sm" id="recebe_ligacao" name="recebe_ligacao">
                                                <option value="0" <?php if($recebe_ligacao == '0'){echo 'selected';}?>>Não</option>
                                                <option value="1" <?php if($recebe_ligacao == '1'){echo 'selected';}?>>Sim</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-3" id="div_remove_duplicado">
                                        <div class="form-group">
                                            <label>*Remove Duplicados:</label>
                                            <select class="form-control input-sm" id="remove_duplicados" name="remove_duplicados">
                                                <option value="0" <?php if($remove_duplicados == '0'){echo 'selected';}?>>Não</option>
                                                <option value="1" <?php if($remove_duplicados == '1'){echo 'selected';}?>>Sim</option>
                                            </select>
                                        </div>
                                    </div> 

                                    <div class="col-md-3" id="div_minutos_duplicados">
                                        <div class="form-group">
                                            <label>*Tempo Duplicados (minutos):</label>
                                            <select name="minutos_duplicados" id="minutos_duplicados" class="form-control input-sm">
                                                <?php
                                                $sel_minutos_duplicados[$minutos_duplicados] = 'selected';
                                                echo "<option value='15'".$sel_minutos_duplicados['15']." id = 'option_minutos_duplicados'>15</option>";
                                                ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-3" id="div_desconsidera_notificacao">
                                        <div class="form-group">
                                            <label>*Cobrar Menor Valor nas Notificações de Parada:</label>
                                            <select class="form-control input-sm" id="desconsidera_notificacao" name="desconsidera_notificacao">
                                                <option value="0" <?php if($desconsidera_notificacao == '0'){echo 'selected';}?>>Não</option>
                                                <option value="1" <?php if($desconsidera_notificacao == '1'){echo 'selected';}?>>Sim</option>
                                            </select>
                                        </div>
                                    </div> 

                                    <div class="col-md-3" id="div_valor_desconsidera_notificacao">
                                        <div class="form-group">
                                            <label>*Valor:</label>
                                            <input name="valor_desconsidera_notificacao" type="text" id="valor_desconsidera_notificacao" class="form-control input-sm money" value="<?=$valor_desconsidera_notificacao;?>" autocomplete="off">
                                        </div>
                                    </div> 


                                </div>
                                
                                <h4 style="margin-bottom: 10px;" id="h4_5">Vínculos:</h4>
                                <hr style="margin-top: 0px; margin-bottom: 20px;" id="hr_5">
                                
                                <div class="row">
                                    <div class="col-md-3" id="div_contrato_pai">
                                        <div class="form-group">
                                            <label>*Vincular Contrato:</label>
                                            <select class="form-control input-sm" id="contrato_pai" name="contrato_pai">
                                                <option value="0" <?php if($contrato_pai == '0'){echo 'selected';}?>>Não</option>
                                                <option value="1" <?php if($contrato_pai == '1'){echo 'selected';}?>>Sim</option>
                                            </select>
                                        </div>
                                    </div>    


                                    <div class="col-md-3" id="div_id_contrato_pai">
                                        <div class="form-group">
                                            <label>*Contrato Vinculado a:</label>
                                            <div class="input-group">
                                                <input class="form-control input-sm" id="busca_contrato" type="text" name="busca_contrato"  value="<?=$contrato?>" placeholder="Informe o nome ou CNPJ..." autocomplete="off" readonly required />
                                                <div class="input-group-btn">
                                                    <button class="btn btn-info btn-sm" id="habilita_busca_contrato" name="habilita_busca_contrato" type="button" title="Clique para selecionar o contrato" style="height: 30px;"><i class="fa fa-search"></i></button>
                                                </div>
                                            </div>

                                            <?php
                                            
                                            if($operacao == 'alterar'){
                                                echo "<input type='hidden' name='id_contrato_plano_pessoa' id='id_contrato_plano_pessoa' value='$id_contrato' />";
                                            }else{
                                                echo "<input type='hidden' name='id_contrato_plano_pessoa' id='id_contrato_plano_pessoa' value='$id_contrato_plano_pessoa' />";
                                            }
                                            ?>
                                        </div>
                                    </div>
                                    
                                </div>

                                <div class="row">
                                    <div class="col-md-3" id="div_separar_contrato">
                                        <div class="form-group">
                                            <label>*Separar Contrato:</label>
                                            <select class="form-control input-sm" id="separar_contrato" name="separar_contrato">
                                                <option value="0" <?php if($separar_contrato == '0'){echo 'selected';}?>>Não</option>
                                                <option value="1" <?php if($separar_contrato == '1'){echo 'selected';}?>>Sim</option>
                                            </select>
                                        </div>
                                    </div>    


                                    <div class="col-md-3" id="div_id_separar_contrato">
                                        <div class="form-group">
                                            <label>*Separar com a:</label>
                                            <div class="input-group">
                                                <input class="form-control input-sm" id="busca_contrato_separar" type="text" name="busca_contrato_separar"  value="<?=$contrato_separar?>" placeholder="Informe o nome ou CNPJ..." autocomplete="off" readonly required />
                                                <div class="input-group-btn">
                                                    <button class="btn btn-info btn-sm" id="habilita_busca_contrato_separar" name="habilita_busca_contrato_separar" type="button" title="Clique para selecionar o contrato" style="height: 30px;"><i class="fa fa-search"></i></button>
                                                </div>
                                            </div>

                                            <?php
                                            
                                            if($operacao == 'alterar'){
                                                echo "<input type='hidden' name='id_contrato_plano_pessoa_separar' id='id_contrato_plano_pessoa_separar' value='$id_contrato_separar' />";
                                            }else{
                                                echo "<input type='hidden' name='id_contrato_plano_pessoa_separar' id='id_contrato_plano_pessoa_separar' value='$id_contrato_plano_pessoa_separar' />";
                                            }
                                            ?>
                                        </div>
                                    </div>
                                    
                                </div>

                                <h4 style="margin-bottom: 10px;" id="h4_6">Observações:</h4>
                                <hr style="margin-top: 0px; margin-bottom: 20px;" id="hr_6">

                                <div class="row" id="row_obs">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Obs:</label>
                                            <textarea class="form-control" name="obs" style="resize: vertical; height: 100px;"><?=$obs?></textarea>
                                        </div>
                                    </div>
                                </div>
                        </div>
                            <?php 

                            if(isset($_GET['alterar'])){
                                if($dados){
                                    echo '<div class="tab-pane fade" id="tab_historico" style="padding-top: 10px;">';
                                    $cont_id = 0;

                                    foreach($dados as $conteudo){
                                        $status = getNomeStatusPlano($conteudo['status']);
                                        echo '<div class="panel panel-default">';
                                            echo '<div class="panel-body">';
                                                echo '
                                                <h5 style="margin-bottom: 10px;"><strong>Status:</strong></h5>
                                                <hr style="margin-top: 0px; margin-bottom: 2px;">';
                            
                                                echo '<div class="row">'; 
                                                
                                                    echo '<div class="col-md-3">';
                                                        echo '<span>Status: '.$status.'</span>';
                                                    echo "</div>"; 
                                                    
                                                    $dados_usuario_atualizacao_historico = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_usuario = '".$conteudo['id_usuario']."' ","b.nome");
                                                    $nome_usuario_atualizacao_historico = $dados_usuario_atualizacao_historico[0]['nome'];

                                                    echo '<div class="col-md-3">';
                                                        echo '<span>Atualização: '.converteDataHora($conteudo['data_atualizacao']).' ('.$nome_usuario_atualizacao_historico.')</span>';
                                                    echo "</div>";                            
                                            
                                                echo "</div>";
                                        
                                            echo '
                                            <h5 style="margin-bottom: 10px; margin-top: 20px;"><strong>Dados Gerais:</strong></h5>
                                            <hr style="margin-top: 0px; margin-bottom: 2px;">';
                            
                                            echo '<div class="row">'; 
                                            
                                                $dados_responsavel = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_usuario = '".$conteudo['id_responsavel']."' ","b.nome");
                                                $nome_responsavel = $dados_responsavel[0]['nome'];

                                                $dados_responsavel_tecnico = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_usuario = '".$conteudo['id_responsavel_tecnico']."' ","b.nome");
                                                $nome_responsavel_tecnico = $dados_responsavel_tecnico[0]['nome'];
                                            
                                                echo '<div class="col-md-3">';
                                                    echo '<span>Responsável pelo Relacionamento: '.$nome_responsavel.'</span>';
                                                echo "</div>";

                                                echo '<div class="col-md-3">';
                                                    echo '<span>Responsável Técnico: '.$nome_responsavel_tecnico.'</span>';
                                                echo "</div>";
                            
                                                echo '<div class="col-md-3">';
                                                    echo '<span>Serviço: '.getNomeServico($conteudo['cod_servico']).'</span>';
                                                echo "</div>";
                            
                                                echo '<div class="col-md-3">';
                                                    echo '<span>Plano: '.$conteudo['nome_plano'].'</span>';
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
                                                    echo '<span>Tipo de Cobrança: '.$tipo_cobranca.'</span>';
                                                echo '</div>';
                            
                                                if($cod_servico == 'gestao_redes'){
                                                    
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
                                                        echo '<span>Tipo de Plantão: '.$tipo_plantao.'</span>';
                                                    echo "</div>";
                                                }
                            
                                                if($conteudo['personalizado'] == 1){
                                                    $personalizado_historico = "Sim";
                                                }else{
                                                    $personalizado_historico = "Não";
                                                }
                                                echo '<div class="col-md-3">';
                                                    echo '<span>Versão Personalizada: '.$personalizado_historico.'</span>';
                                                echo "</div>";
                                                
                                            echo '</div>';
                            
                                            echo '
                                            <h5 style="margin-bottom: 10px; margin-top: 20px;"><strong>Valores/Quantidades:</strong></h5>
                                            <hr style="margin-top: 0px; margin-bottom: 2px;">';
                            
                                            echo '<div class="row">';
                            
                                                echo '<div class="col-md-3">';
                                                    echo '<span>Valor da Adesão:  R$ '.converteMoeda($conteudo['valor_adesao']).'</span>';
                                                echo "</div>";
                            
                                                if($conteudo['valor_diferente_texto'] == 1){
                                                    $valor_diferente_texto = "Sim";
                                                }else{
                                                    $valor_diferente_texto = "Não";
                                                }
                                                echo '<div class="col-md-3">';
                                                    echo '<span>Valor Diferente para Texto: '.$valor_diferente_texto.'</span>';
                                                echo "</div>";
                            
                                                echo '<div class="col-md-3">';
                                                    echo '<span>Qtd. Contratada: '.$conteudo['qtd_contratada'].'</span>';
                                                echo "</div>";

                                                if($conteudo['tipo_cobranca'] == 'x_cliente_base' && $cod_servico == 'call_suporte'){
                                                    echo '<div class="col-md-3">';
                                                        echo '<span>Qtd. Contratada (Clientes): '.$conteudo['qtd_clientes_teto'].'</span>';
                                                    echo "</div>";
                                                }
                                                
                                                if($conteudo['valor_diferente_texto'] == 1){
                                                    echo '<div class="col-md-3">';
                                                        echo '<span>Qtd. Contratada do Texto: '.$conteudo['qtd_contratada_texto'].'</span>';
                                                    echo "</div>";
                                                }
                            
                                                echo '<div class="col-md-3">';
                                                    echo '<span>Valor Unitário: R$ '.converteMoeda($conteudo['valor_unitario']).'</span>';
                                                echo "</div>";
                                                
                                                if($conteudo['valor_diferente_texto'] == 1){
                                                    echo '<div class="col-md-3">';
                                                        echo '<span>Valor Unitário do Texto: R$ '.converteMoeda($conteudo['valor_unitario_texto']).'</span>';
                                                    echo "</div>";
                                                }
                            
                                                echo '<div class="col-md-3">';
                                                    echo '<span>Valor Total: R$ '.converteMoeda($conteudo['valor_total']).'</span>';
                                                echo "</div>";
                            
                                                echo '<div class="col-md-3"';
                                                    echo '<span>Valor Inicial: R$ '.converteMoeda($conteudo['valor_inicial']).'</span>';
                                                echo '</div>';
                            
                                                echo '<div class="col-md-3">';
                                                    echo '<span>Valor Excedente (Unt): R$ '.converteMoeda($conteudo['valor_excedente']).'</span>';
                                                echo "</div>";
                                            
                                                if($conteudo['valor_diferente_texto'] == 1){
                                                    echo '<div class="col-md-3">';
                                                        echo '<span>Valor Excedente do Texto (Unt): R$ '.converteMoeda($conteudo['valor_excedente_texto']).'</span>';
                                                    echo "</div>";
                                                }
                            
                                                if($cod_servico == 'gestao_redes'){
                                                    echo '<div class="col-md-3">';
                                                        echo '<span>Valor Plantão (Unt): R$ '.converteMoeda($conteudo['valor_plantao']).'</span>';
                                                    echo "</div>";                        
                                                }

                                                echo '<div class="col-md-3">';
                                                    echo '<span>Quantidade de Clientes: '.$conteudo['qtd_clientes'].'</span>';
                                                echo "</div>";
                            
                                            echo '</div>';
                            
                                            echo '
                                            <h5 style="margin-bottom: 10px; margin-top: 20px;"><strong>Dados Contratuais:</strong></h5>
                                            <hr style="margin-top: 0px; margin-bottom: 2px;">';
                            
                                            echo '<div class="row">';
                                                
                                                echo '<div class="col-md-3">';
                                                    echo '<span>Índice de Reajuste: '.$conteudo['indice_reajuste'].'</span>';
                                                echo "</div>";
                            
                                                if($conteudo['tipo_cobranca'] == 'mensal_desafogo'){
                                                    echo '<div class="col-md-3">';
                                                        echo '<span>Desafogo: '.$conteudo['desafogo'].'%</span>';
                                                    echo '</div>';
                            
                                                    if($conteudo['valor_diferente_texto'] == 1){
                                                        echo '<div class="col-md-3">';
                                                            echo '<span>Desafogo do Texto: '.$conteudo['desafogo_texto'].'%</span>';
                                                        echo '</div>';
                                                    }
                                                    
                                                }
                            
                                                echo '<div class="col-md-3">';
                                                    echo '<span>Dia de Pagamento: '.$conteudo['dia_pagamento'].'</span>';
                                                echo "</div>";
                            
                                                echo '<div class="col-md-3">';
                                                    echo '<span>Data de Início do Contrato: '.converteData($conteudo['data_inicio_contrato']).'</span>';
                                                echo "</div>";
                            
                                                echo '<div class="col-md-3">';
                                                    echo '<span>Data do Próximo Reajuste: '.converteData($conteudo['data_ajuste']).'</span>';
                                                echo "</div>";
                            
                                                echo '<div class="col-md-3">';
                                                    echo '<span>Período de Contrato: '.$conteudo['periodo_contrato'].' meses</span>';
                                                echo "</div>";
                            
                                            echo '</div>';

                                            echo '<div class="row">';
                                               
                                                echo '<div class="col-md-3">';
                                                    echo '<span>Data Inicial da Cobrança: '.converteData($conteudo['data_inicial_cobranca']).'</span>';
                                                echo "</div>";
                            
                                                echo '<div class="col-md-3">';
                                                    echo '<span>Data Final da Cobrança: '.converteData($conteudo['data_final_cobranca']).'</span>';
                                                echo "</div>";
                            
                                                echo '<div class="col-md-3">';
                                                    echo '<span>Tempo de Fidelidade (meses): '.$conteudo['tempo_fidelidade'].'</span>';
                                                echo "</div>";
                            
                                            echo '</div>';
                                            
                                            if($conteudo['cod_servico'] == 'call_suporte'){
                                        
                                                echo '<h5 style="margin-bottom: 10px; margin-top: 20px;"><strong>Dados de Cobrança:</strong></h5>
                                                    <hr style="margin-top: 0px; margin-bottom: 2px;">';
                                        
                                                echo '<div class="row">';
                                                    if($conteudo['remove_duplicados'] == 1){
                                                        echo '<div class="col-md-3">';
                                                            echo '<span>Remove Duplicados: Sim</span>';
                                                        echo "</div>";
                                                    }else{
                                                        echo '<div class="col-md-3">';
                                                            echo '<span>Remove Duplicados: Não</span>';
                                                        echo "</div>";
                                                    }
                            
                                                    if($conteudo['realiza_cobranca'] == 1){
                                                        echo '<div class="col-md-3">';
                                                            echo '<span>Realiza Cobrança: Sim</span>';
                                                        echo "</div>";
                                                    }else{
                                                        echo '<div class="col-md-3">';
                                                            echo '<span>Realiza Cobrança: Não</span>';
                                                        echo "</div>";
                                                    }
                            
                                                    if($conteudo['recebe_ligacoes'] == 1){
                                                        echo '<div class="col-md-3">';
                                                            echo '<span>Recebe Ligações: Sim</span>';
                                                        echo "</div>";
                                                    }else{
                                                        echo '<div class="col-md-3">';
                                                            echo '<span>Recebe Ligações: Não</span>';
                                                        echo "</div>";
                                                    }

                                                    if($conteudo['desconsidera_notificacao'] == 1){
                                                        echo '<div class="col-md-3">';
                                                            echo '<span>Cobrar Menor Valor nas Notificações de Parada: Sim</span>';
                                                        echo "</div>";
                                                        echo '<div class="col-md-3">';
                                                            echo '<span>Valor: R$ '.converteMoeda($conteudo['valor_desconsidera_notificacao']).'</span>';
                                                        echo "</div>";
                                                    }else{
                                                        echo '<div class="col-md-3">';
                                                            echo '<span>Cobrar Menor Valor nas Notificações de Parada: Não</span>';
                                                        echo "</div>";
                                                    }

                                                echo '</div>';
                                            }     
                                            
                                                echo '
                                                    <h5 style="margin-bottom: 10px; margin-top: 20px;"><strong>Vínculos:</strong></h5>
                                                    <hr style="margin-top: 0px; margin-bottom: 2px;">';

                                                    echo '<div class="row">';
                                                    $dados_contrato_filho = DBRead('', 'tb_contrato_plano_pessoa a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_contrato_plano_pessoa = '".$conteudo['contrato_pai']."' ");

                                                    if($dados_contrato_filho){
                                                        echo '<div class="col-md-3">';
                                                            echo '<span>Vincular Contrato: Sim</span>';
                                                        echo "</div>";
                                                        echo '<div class="col-md-3">';
                                                            echo '<span>Contrato Vinculado a: '.$dados_contrato_filho[0]['nome'].'</span>';
                                                        echo "</div>";
                                                    }else{
                                                        echo '<div class="col-md-3">';
                                                            echo '<span>Vincular Contrato: Não</span>';
                                                        echo "</div>";
                                                    }      
                                                    echo '</div>';
                                                    
                                                    echo '<div class="row">';
                                                    $dados_contrato_separar = DBRead('', 'tb_contrato_plano_pessoa a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_contrato_plano_pessoa = '".$conteudo['separar_contrato']."' ");

                                                    if($dados_contrato_separar){
                                                        echo '<div class="col-md-3">';
                                                            echo '<span>Separar Contrato: Sim</span>';
                                                        echo "</div>";
                                                        echo '<div class="col-md-3">';
                                                            echo '<span>Separar com a: '.$dados_contrato_separar[0]['nome'].'</span>';
                                                        echo "</div>";
                                                    }else{
                                                        echo '<div class="col-md-3">';
                                                            echo '<span>Separar Contrato: Não</span>';
                                                        echo "</div>";

                                                    }     
                                                    echo '</div>';

                                            if($conteudo['obs']){
                                                echo '<h5 style="margin-bottom: 10px; margin-top: 20px;"><strong>Observações:</strong></h5>
                                                    <hr style="margin-top: 0px; margin-bottom: 2px;">';
                                                echo '<div class="row">';
                                                    echo '<div class="col-md-12">';
                                                        echo '<span>Obs: '.$conteudo['obs'].'</span>';
                                                    echo "</div>";
                                                echo '</div>';
                                            }  

                                            $dados_historico_procedimento = DBRead('', 'tb_plano_procedimento_historico a', "INNER JOIN tb_plano_procedimento b ON a.id_plano_procedimento = b.id_plano_procedimento WHERE a.id_plano = '".$conteudo['id_plano']."' AND a.versao = '".$conteudo['plano_versao']."' ");
    

                                                if($dados_historico_procedimento){
                                                    echo '<h5 style="margin-bottom: 10px; margin-top: 20px;"><strong>Procedimentos:</strong></h5>';

                                                    echo '<div class="row">';
                                                        echo '<div class="col-md-12">';
                                                        
                                                            ?>

                                                        
                                                        <div class="panel" style="border: 1px solid #eee;">
                                                            <div class="panel-heading clearfix">
                                                                <div class="row">
                                                                    <div class="col-md-10">
                                                                        <div class="form-group">
                                                                            <h3 class="panel-title text-left pull-left" style="margin-top: 2px;">Versão (Plano): <?=$conteudo['plano_versao']?> </h3>
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-md-2">
                                                                        <div class="form-group">
                                                                            <div class="panel-title text-right pull-right">
                                                                                <button data-toggle="collapse" data-target="#accordionPlano_<?=$cont_id?>" class="btn btn-xs btn-info" type="button" title="Visualizar filtros"><i id="i_collapse_<?=$cont_id?>" class="fa fa-plus"></i></button>
                                                                            </div>

                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div id="accordionPlano_<?=$cont_id?>" class="panel-collapse collapse accordionPlano">
                                                                <div class="panel-body">	
                                                                    <div class="table-responsive" style="max-height: 365px; overflow-y:auto;">
                                                                        <table class="table table-hover table_paginas" style="font-size: 14px;">
                                                                            <thead>
                                                                                <tr>
                                                                                    <th class="col-md-4">Procedimento</th>
                                                                                    <th class="col-md-4">Descrição</th>
                                                                                    <th class="col-md-4">Pré-Requisito</th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                            <?php


                                                                            foreach($dados_historico_procedimento as $conteudo_procedimento){

                                                                                
                                                                                echo '
                                                                                    <tr>
                                                                                        <td>'.$conteudo_procedimento['nome'].'asdas</td>
                                                                                        <td>'.$conteudo_procedimento['descricao'].'</td>
                                                                                        <td>'.$conteudo_procedimento['pre_requisito'].'</td>
                                                                                    <tr>
                                                                                    ';
                                                                            }
                                                                            ?>
                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                    
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <?php
                                                        $cont_id++;

                                                        echo "</div>";
                                                    echo '</div>';
                                                }else{
                                                    echo '<h5 style="margin-bottom: 10px; margin-top: 20px;"><strong>Procedimentos:</strong></h5>';
                                                    echo "<p class='alert alert-warning' style='text-align: center'>";
                                                        echo "Não foram cadastrados procedimentos!";
                                                    echo "</p>"; 
                                                }  
                                        
                                            echo '</div>';
                                        echo '</div>';
                                    }
                                    echo '</div>';
                                }

                                echo '<div class="tab-pane fade" id="tab_procedimento" style="padding-top: 10px;">';
                                    echo '<div class="row"> 
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Plano:</label>
                                                    <input name="plano_procedimento" id="plano_procedimento" class="form-control input-sm" type="text" value="" disabled>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Versão (Plano):</label>
                                                    <input name="versao_procedimento" id="versao_procedimento" class="form-control input-sm" type="text" value="" disabled>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Versão Personalizada:</label>
                                                    <select class="form-control input-sm" id="personalizado_procedimento" name="personalizado_procedimento">
                                                        <option value="0" id="personalizado_procedimento_nao">Não</option>
                                                        <option value="1" id="personalizado_procedimento_sim">Sim</option>
                                                    </select>
                                                </div>
                                            </div>
                                            ';
                                            
                                        echo '<div id="resultado_procedimento"></div>';

                                    echo '</div>';
                                echo '</div>';?>

                                <div class="tab-pane fade in" id="tab_pdf" style="padding-top: 10px;">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <?php if($pdf_contrato){ ?>
                                                    <label id='btn_pdf_contrato' class="btn btn-sm" style="margin: 2rem; background-color: #3e8f3e; color: white;">
                                                    <span id='texto_pdf_contrato'>Substituir PDF</span>
                                                <?php } else { ?>
                                                    <label id='btn_pdf_contrato' class="btn btn-sm" style="margin: 2rem; background-color: #52abb7; color: white;">
                                                        <span id='texto_pdf_contrato'>Adicionar PDF</span>
                                                <?php } ?>
                                                        <input type="file" id="pdf_contrato" name="pdf_contrato" accept=".pdf" style="display: none;">
                                                    </label>
                                                <?php if($pdf_contrato){ ?>    
                                                    <a href='class/Contrato.php?download=<?=$id?>' title='Download'"><i class='fa fa-download'></i> Baixar contrato atual</a>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>



                           <?php }    ?>
                        
                            <?php 
                            if($flag_perfil == 1){ 
                            ?>
                                <input type="hidden" name="flag_perfil" id="flag_perfil" value="<?=$flag_perfil;?>">
                                <div class="tab-pane fade" id="tab_nfs" style="padding-top: 10px;">
                                    <div class="row"> 
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>E-mail NFS-e:</label>
                                                <input name="email_nf" id="email_nf" class="form-control input-sm" type="text" value="<?=$email_nf;?>" autocomplete="off" placeholder="exemplo@bellunotec.com.br">
                                            </div>
                                        </div>
                                   
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label>*Reter COFINS:</label>
                                                <select name="reter_cofins" id="reter_cofins" class="form-control input-sm">
                                                    <option value="2" <?php if($reter_cofins == '2'){echo 'selected';}?>>Não</option>                                          
                                                    <option value="1" <?php if($reter_cofins == '1'){echo 'selected';}?>>Sim</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label>*Reter CSLL:</label>
                                                <select name="reter_csll" id="reter_csll" class="form-control input-sm">
                                                    <option value="2" <?php if($reter_csll == '2'){echo 'selected';}?>>Não</option>                                          
                                                    <option value="1" <?php if($reter_csll == '1'){echo 'selected';}?>>Sim</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label>*Reter IR:</label>
                                                <select name="reter_ir" id="reter_ir" class="form-control input-sm">
                                                    <option value="2" <?php if($reter_ir == '2'){echo 'selected';}?>>Não</option>                                          
                                                    <option value="1" <?php if($reter_ir == '1'){echo 'selected';}?>>Sim</option>
                                                </select>                                            
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label>*Reter PIS:</label>
                                                <select name="reter_pis" id="reter_pis" class="form-control input-sm">
                                                    <option value="2" <?php if($reter_pis == '2'){echo 'selected';}?>>Não</option>                                          
                                                    <option value="1" <?php if($reter_pis == '1'){echo 'selected';}?>>Sim</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>    
                                </div>  
                            <?php
                            }
                            ?>

                            <?php 
                            if($flag_perfil == 1){ 
                            ?>
                                <!-- <input type="hidden" name="flag_perfil" id="flag_perfil" value="<?=$flag_perfil;?>">
                                <div class="tab-pane fade" id="tab_faturamento" style="padding-top: 10px;">
                                    <div class="row"> 
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>*Data Inicial da Cobrança:</label>
                                                <input name="data_inicial_cobranca" type="text" class="form-control input-sm date calendar data_inicial_cobranca" value="<?=$data_inicial_cobranca;?>" autocomplete="off">
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>*Data Final da Cobrança:</label>
                                                <input name="data_final_cobranca" type="text" class="form-control input-sm date calendar data_final_cobranca" value="<?=$data_final_cobranca;?>" autocomplete="off">
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>*Tempo de Fidelidade (meses):</label>
                                                <input name="tempo_fidelidade" id="tempo_fidelidade" type="number" min="1" class="form-control input-sm number_int" value="<?=$periodo_contrato;?>" autocomplete="off">
                                            </div>
                                        </div>  

                                    </div>    
                                </div>   -->
                            <?php
                            }
                            ?>
                        </div>                                              
                    </div> 
                    <div class="panel-footer">
                        <div class="row">
                            <div class="col-md-12" style="text-align: center">
                                <input type="hidden" id="notifica_faturamento" value="<?=$notifica_faturamento;?>" name="notifica_faturamento"/>
                                <input type="hidden" id="operacao" value="<?=$id;?>" name="<?=$operacao;?>"/>
                                <button class="btn btn-primary" name="salvar" id="ok" type="submit"><i class="fa fa-floppy-o"></i> Salvar</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

                                   
<script>
$(function () {
  $('[data-toggle="tooltip"]').tooltip()
})
    // Atribui evento e função para limpeza dos campos
    $('#busca_pessoa').on('input', limpaCamposPessoa);
    // Dispara o Autocomplete da pessoa a partir do segundo caracter
    $("#busca_pessoa").autocomplete({
            minLength: 2,
            source: function (request, response) {
                $.ajax({
                    url: "/api/ajax?class=PessoaAutocomplete.php",
                    dataType: "json",
                    data: {
                        acao: 'autocomplete',
                        parametros: { 
                            'nome' : $('#busca_pessoa').val(),
                            'atributo' : 'cliente',
                        },
                        token: '<?= $request->token ?>'
                    },
                    success: function (data) {
                        response(data);
                    }
                });
            },
            focus: function (event, ui) {
                $("#busca_pessoa").val(ui.item.nome);
                carregarDadosPessoa(ui.item.id_pessoa + "" + ui.item.nome_contrato);
                return false;
            },
            select: function (event, ui) {
                $("#busca_pessoa").val(ui.item.nome + "" + ui.item.nome_contrato);
                $('#busca_pessoa').attr("readonly", true);
                return false;
            }
        })
        .autocomplete("instance")._renderItem = function (ul, item) {
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

        return $("<li>").append("<a><strong>"+item.id_pessoa+" - "+ item.nome + "" +item.nome_contrato + " </strong><br>" +item.razao_social+ "<br>" +item.cpf_cnpj+ "</a><hr style='margin-bottom: 0px;'>").appendTo(ul);
    };

    // Função para carregar os dados da consulta nos respectivos campos
    function carregarDadosPessoa(id) {
        var busca = $('#busca_pessoa').val();

        if (busca != "" && busca.length >= 2) {
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
                success: function (data) {
                    $('#id_pessoa').val(data[0].id_pessoa);

                    $('#cliente_id_cidade').val(data[0].id_cidade);
                    $('#cliente_logradouro').val(data[0].logradouro);
                    $('#cliente_numero').val(data[0].numero);
                    $('#cliente_bairro').val(data[0].bairro);
                    $('#cliente_cep').val(data[0].cep);
                    $('#cliente_razao_social').val(data[0].razao_social);
                    $('#cliente_cpf_cnpj').val(data[0].cpf_cnpj);
                }
            });
        }
    }

    // Função para limpar os campos caso a busca esteja vazia
    function limpaCamposPessoa() {
        var busca = $('#busca_pessoa').val();
        if (busca == "") {
            $('#id_pessoa').val('');
        }
    }

    $(document).on('click', '#habilita_busca_pessoa', function () {
        $('#id_pessoa').val('');
        $('#busca_pessoa').val('');
        $('#busca_pessoa').attr("readonly", false);
        $('#busca_pessoa').focus();
    });


    //------------------------------------------
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
            }
            if(!item.nome_contrato){
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


    //------------------------------------------
    //SEPARAR CONTRATO
    // Atribui evento e função para limpeza dos campos
    $('#busca_contrato_separar').on('input', limpaCamposContratoSeparar);
    // Dispara o Autocomplete da pessoa a partir do segundo caracter
    $("#busca_contrato_separar").autocomplete({
            minLength: 2,
            source: function (request, response) {
                $.ajax({
                    url: "/api/ajax?class=ContratoAutocomplete.php",
                    dataType: "json",
                    data: {
                        acao: 'autocomplete',
                        parametros: { 
                            'nome' : $('#busca_contrato_separar').val(),
                        },
                        token: '<?= $request->token ?>'
                    },
                    success: function (data) {
                        response(data);
                    }
                });
            },
            focus: function (event, ui) {
                $("#busca_contrato_separar").val(ui.item.nome + " " + ui.item.nome_contrato +" - " + ui.item.servico + " - " + ui.item.plano + " (" + ui.item.id_contrato_plano_pessoa + ")");
                carregarDadosContratoSeparar(ui.item.id_contrato_plano_pessoa);
                return false;
            },
            select: function (event, ui) {
                $("#busca_contrato_separar").val(ui.item.nome + " "+ ui.item.nome_contrato + " - " + ui.item.servico + " - " + ui.item.plano + " (" + ui.item.id_contrato_plano_pessoa + ")");
                $('#busca_contrato_separar').attr("readonly", true);
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
    function carregarDadosContratoSeparar(id) {
        var busca = $('#busca_contrato_separar').val();
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
                    $('#id_contrato_plano_pessoa_separar').val(data[0].id_contrato_plano_pessoa);
                }
            });
        }
    }
    // Função para limpar os campos caso a busca esteja vazia
    function limpaCamposContratoSeparar() {
        var busca = $('#busca_contrato_separar').val();
        if (busca == "") {
            $('#id_contrato_plano_pessoa_separar').val('');
        }
    }
    $(document).on('click', '#habilita_busca_contrato_separar', function () {
        $('#id_contrato_plano_pessoa_separar').val('');
        $('#busca_contrato_separar').val('');
        $('#busca_contrato_separar').attr("readonly", false);
        $('#busca_contrato_separar').focus();
    });
    //------------------------------------------



    $(document).ready(function() {

        var contrato_pai = '<?=$contrato_pai?>';

        if(contrato_pai == 0){
            $('#div_id_contrato_pai').hide();
        }else{
            $('#div_id_contrato_pai').show();
        }

        var separar_contrato = '<?=$separar_contrato?>';

        if(separar_contrato == 0){
            $('#div_id_separar_contrato').hide();
        }else{
            $('#div_id_separar_contrato').show();
        }       

        var remove_duplicados = $('#remove_duplicados').val();
        
        if(remove_duplicados == 0){
            $('select[name=minutos_duplicados]').val(0)
            $('#div_minutos_duplicados').hide();
        }else{
            $('select[name=minutos_duplicados]').val("<?=$minutos_duplicados?>");
            $('#div_minutos_duplicados').show();
        }

        var desconsidera_notificacao = $('#desconsidera_notificacao').val();
        
        if(desconsidera_notificacao == 0){
            $('#valor_desconsidera_notificacao').val(0)
            $('#div_valor_desconsidera_notificacao').hide();
        }else{
            $('#valor_desconsidera_notificacao').val("<?=$valor_desconsidera_notificacao?>");
            $('#div_valor_desconsidera_notificacao').show();
        }

        var servico = $('#servico').val();
        if(servico == 'call_suporte' || servico == 'call_monitoramento'){
            var tipo_cobranca = $('#tipo_cobranca').val();

            if(tipo_cobranca == 'x_cliente_base'){
                $('#div_qtd_contratada').show();
                $('#div_valor_unitario').hide();
                $('#valor_unitario').val(0);
                $('#div_valor_total').show();       
                $('#div_valor_inicial').hide();  
                $('#div_valor_excedente').show();
                $('#div_valor_plantao').hide();
                $('#div_indice_reajuste').show();
               
                $('#div_valor_inicial').hide();       
            
                $('#desafogo').val(0);
                $('#div_desafogo').hide();

                $('#div_recebe_ligacao').show();
                $('#div_realiza_cobranca').show();  
                $('#div_remove_duplicado').hide();  
                $('#div_desconsidera_notificacao').show();  
                
                $('#div_minutos_duplicados').hide();

                $('#div_pagamento').show();  
                $('.div_inicio').show();  
                $('#div_data_final_cobranca').show();  
                $('#div_periodo').show();  
                $('#div_status').show();

                $('#row_obs').show();  
                $('#ok').show(); 

                $('#option_cliente_ativo').prop("disabled", true);
                $('#option_cliente_base').prop("disabled", true);
                $('#option_horas').prop("disabled", true);
                $('#option_ilimitado').prop("disabled", true);
                $('#option_mensal_desafogo').prop("disabled", false);
                $('#option_mensal').prop("disabled", false);
                $('#option_unitario').prop("disabled", false);

                selectplano($('select[name=servico]').val());
                selectversao("<?=$id_plano?>");
            
                $('#div_contrato_pai').show();
                $('#div_separar_contrato').show();
                
                $('#div_data_reajuste').show();
                $('#div_qtd_clientes').show();
                $('#div_valor_adesao').show();
                $('#div_tipo_plantao').hide();  
            
                if(servico != 'call_monitoramento'){
                    //OI
                    $('#option_x_cliente_base').prop("disabled", false);
                    $('#div_valor_diferente_texto').hide();  

                    $('#div_qtd_contratada_texto').hide();
                    $('#div_valor_unitario_texto').hide();
                    $('#div_valor_excedente_texto').hide();
                    $('#div_desafogo_texto').hide();

                    $('#qtd_contratada_texto').prop('required',false);
                    $('#valor_unitario_texto').prop('required',false);
                    $('#valor_excedente_texto').prop('required',false);
                    
                    $('#div_recebe_ligacao').show();
                    $('#div_remove_duplicado').hide();  
                }

                $('#h4_2').show();
                $('#h4_3').show();
                $('#h4_4').show();
                $('#h4_5').show();
                $('#h4_6').show();

                $('#hr_2').show();
                $('#hr_3').show();
                $('#hr_4').show();
                $('#hr_5').show();
                $('#hr_6').show();

                $('#div_tipo_cobranca').show();

                $('#div_qtd_clientes_teto').show();
                $('#qtd_clientes_teto').prop('required',true);

            }else{
                $('#div_qtd_contratada').show();
                $('#div_valor_unitario').show();
                $('#div_valor_total').show();       
                $('#div_valor_inicial').hide();  
                $('#div_valor_excedente').show();
                $('#div_valor_plantao').hide();
                $('#div_indice_reajuste').show();
                
                var tipo_cobranca = $('#tipo_cobranca').val();
                if(tipo_cobranca == 'unitario'){
                    $('#div_valor_inicial').show();       
                }else{
                    $('#div_valor_inicial').hide();       
                }
                if(tipo_cobranca == 'mensal_desafogo'){
                    if('<?=$desafogo?>' != '0' && '<?=$desafogo?>'){
                        $('#desafogo').val('<?=$desafogo?>');
                    }else{
                        $('#desafogo').val(20);
                    }
                    $('#div_desafogo').show();

                }else{
                    $('#desafogo').val(0);
                    $('#div_desafogo').hide();
                } 

                $('#div_recebe_ligacao').show();
                $('#div_realiza_cobranca').show();  
                $('#div_remove_duplicado').show();  
                $('#div_desconsidera_notificacao').show();  

                
                if(remove_duplicados == 1){
                    $('#div_minutos_duplicados').show();
                }else{
                    $('#div_minutos_duplicados').hide();
                }
        
                if(desconsidera_notificacao == 0){
                    $('#div_valor_desconsidera_notificacao').hide();
                }else{
                    $('#div_valor_desconsidera_notificacao').show();
                }

                $('#div_pagamento').show();  
                $('.div_inicio').show();  
                $('#div_data_final_cobranca').show();  
                $('#div_periodo').show();  
                $('#div_status').show();

                $('#row_obs').show();  
                $('#ok').show(); 

                $('#option_cliente_ativo').prop("disabled", true);
                $('#option_cliente_base').prop("disabled", true);
                $('#option_horas').prop("disabled", true);
                $('#option_ilimitado').prop("disabled", true);
                $('#option_mensal_desafogo').prop("disabled", false);
                $('#option_mensal').prop("disabled", false);
                $('#option_unitario').prop("disabled", false);

                selectplano($('select[name=servico]').val());
                selectversao("<?=$id_plano?>");
            
                $('#div_contrato_pai').show();
                $('#div_separar_contrato').show();

                $('#div_data_reajuste').show();
                $('#div_qtd_clientes').show();
                $('#div_valor_adesao').show();
                $('#div_tipo_plantao').hide();  
            
                if(servico != 'call_monitoramento'){
                    //OI
                    $('#option_x_cliente_base').prop("disabled", false);

                    $('#div_valor_diferente_texto').show();  
                    var valor_diferente_texto = $('#valor_diferente_texto').val();
                    if(valor_diferente_texto == 1){
                        $('#div_qtd_contratada_texto').show();
                        $('#div_valor_unitario_texto').show();
                        $('#div_valor_excedente_texto').show();

                        $('#qtd_contratada_texto').prop('required',true);
                        $('#valor_unitario_texto').prop('required',true);
                        $('#valor_excedente_texto').prop('required',true);


                    }else{
                        $('#div_qtd_contratada_texto').hide();
                        $('#div_valor_unitario_texto').hide();
                        $('#div_valor_excedente_texto').hide();
                        $('#div_desafogo_texto').hide();

                        $('#qtd_contratada_texto').prop('required',false);
                        $('#valor_unitario_texto').prop('required',false);
                        $('#valor_excedente_texto').prop('required',false);
                    }
                }else{
                    //OI
                    $('#option_x_cliente_base').prop("disabled", true);

                    $('#div_valor_diferente_texto').hide();  

                    $('#div_qtd_contratada_texto').hide();
                    $('#div_valor_unitario_texto').hide();
                    $('#div_valor_excedente_texto').hide();
                    $('#div_desafogo_texto').hide();

                    $('#qtd_contratada_texto').prop('required',false);
                    $('#valor_unitario_texto').prop('required',false);
                    $('#valor_excedente_texto').prop('required',false);
                    
                    $('#div_recebe_ligacao').hide();
                    $('#div_remove_duplicado').hide();  
                }

                $('#h4_2').show();
                $('#h4_3').show();
                $('#h4_4').show();
                $('#h4_5').show();
                $('#h4_6').show();

                $('#hr_2').show();
                $('#hr_3').show();
                $('#hr_4').show();
                $('#hr_5').show();
                $('#hr_6').show();

                $('#div_tipo_cobranca').show();

                $('#div_qtd_clientes_teto').hide();
                $('#qtd_clientes_teto').prop('required',false);
            }
            
        }else if(servico == 'call_ativo'){
            $('#div_qtd_contratada').show();
            $('#div_valor_unitario').show();
            $('#div_valor_total').show();       
            $('#div_valor_inicial').hide();  
            $('#div_valor_excedente').hide();
            $('#div_valor_plantao').hide();
            $('#div_indice_reajuste').show();
            $('#div_desafogo').hide();

            $('#div_realiza_cobranca').show();  
            $('#div_recebe_ligacao').hide();  
            $('#div_remove_duplicado').hide();  
            $('#div_minutos_duplicados').hide(); 
            $('#div_desconsidera_notificacao').show();  


            $('#div_pagamento').show();  
            $('.div_inicio').show();  
            $('#div_data_final_cobranca').show();  
            $('#div_periodo').show();  
            $('#div_status').show();  

            $('#row_obs').show();  
            $('#ok').show();     

            $('#option_x_cliente_base').prop("disabled", true);
            $('#option_cliente_ativo').prop("disabled", true);
            $('#option_cliente_base').prop("disabled", true);
            $('#option_horas').prop("disabled", true);
            $('#option_ilimitado').prop("disabled", true);
            $('#option_mensal_desafogo').prop("disabled", true);
            $('#option_mensal').prop("disabled", false);
            $('#option_unitario').prop("disabled", false);


            selectplano($('select[name=servico]').val());
            selectversao("<?=$id_plano?>");

            $('#div_contrato_pai').show();
            $('#div_separar_contrato').show();
                
            $('#div_data_reajuste').show();
            $('#div_qtd_clientes').show();
            $('#div_valor_adesao').show();
            $('#div_tipo_plantao').hide();  

            $('#div_valor_diferente_texto').hide();  
            $('#div_qtd_contratada_texto').hide();
            $('#div_valor_unitario_texto').hide();
            $('#div_valor_excedente_texto').hide();
            $('#div_desafogo_texto').hide();
            
            $('#qtd_contratada_texto').prop('required',false);
            $('#valor_unitario_texto').prop('required',false);
            $('#valor_excedente_texto').prop('required',false);

            $('#valor_diferente_texto').val(0); 

            $('#h4_2').show();
            $('#h4_3').show();
            $('#h4_4').show();
            $('#h4_5').show();
            $('#h4_6').show();

            $('#hr_2').show();
            $('#hr_3').show();
            $('#hr_4').show();
            $('#hr_5').show();
            $('#hr_6').show();

            $('#div_tipo_cobranca').show();

            $('#div_qtd_clientes_teto').hide();
            $('#qtd_clientes_teto').prop('required',false);
          
        }else if(servico == 'gestao_redes'){
            $('#div_qtd_contratada').show();
            $('#div_valor_unitario').show();
            $('#div_valor_total').show();       
            $('#div_valor_inicial').show();  
            $('#div_valor_excedente').show();
            $('#div_valor_plantao').show();
            $('#div_indice_reajuste').show();
            $('#div_desafogo').hide();  

            $('#div_realiza_cobranca').show();  
            $('#div_recebe_ligacao').hide();  
            $('#div_remove_duplicado').hide();  
            $('#div_minutos_duplicados').hide();  
            $('#div_desconsidera_notificacao').show();  
   
            
            $('#div_pagamento').show();  
            $('.div_inicio').show();  
            $('#div_data_final_cobranca').show();  
            $('#div_periodo').show();  
            $('#div_status').show();  

            $('#row_obs').show();  
            $('#ok').show(); 

            $('#option_x_cliente_base').prop("disabled", false);
            $('#option_cliente_ativo').prop("disabled", false);
            $('#option_cliente_base').prop("disabled", false);
            $('#option_horas').prop("disabled", false);
            $('#option_ilimitado').prop("disabled", false);
            $('#option_mensal_desafogo').prop("disabled", true);
            $('#option_mensal').prop("disabled", true);
            $('#option_unitario').prop("disabled", true);

            selectplano($('select[name=servico]').val());
            selectversao("<?=$id_plano?>");

            $('#div_contrato_pai').show();
            $('#div_separar_contrato').show();
                
            $('#div_data_reajuste').show();

            $('#div_qtd_clientes').show();

            $('#div_valor_adesao').show();
            
            $('#div_tipo_plantao').show();  

            
            $('#div_valor_diferente_texto').hide();  
            $('#div_qtd_contratada_texto').hide();
            $('#div_valor_unitario_texto').hide();
            $('#div_valor_excedente_texto').hide();
            $('#div_desafogo_texto').hide();
            
            $('#qtd_contratada_texto').prop('required',false);
            $('#valor_unitario_texto').prop('required',false);
            $('#valor_excedente_texto').prop('required',false);

            $('#valor_diferente_texto').val(0); 

            $('#h4_2').show();
            $('#h4_3').show();
            $('#h4_4').show();
            $('#h4_5').show();
            $('#h4_6').show();

            $('#hr_2').show();
            $('#hr_3').show();
            $('#hr_4').show();
            $('#hr_5').show();
            $('#hr_6').show();

            $('#div_tipo_cobranca').show();

            $('#div_qtd_clientes_teto').hide();
            $('#qtd_clientes_teto').prop('required',false);

        }else{
            $('#div_qtd_contratada').hide();
            $('#div_valor_unitario').hide();
            $('#div_valor_total').hide();       
            $('#div_valor_inicial').hide();  
            $('#div_valor_excedente').hide();
            $('#div_valor_plantao').hide();
            $('#div_indice_reajuste').hide();
            $('#div_desafogo').hide();     

            $('#div_realiza_cobranca').hide();  
            $('#div_recebe_ligacao').hide();  
            $('#div_remove_duplicado').hide();  
            $('#div_minutos_duplicados').hide();  
            $('#div_desconsidera_notificacao').hide();  

            
            $('#div_pagamento').hide();  
            $('.div_inicio').hide();  
            $('#div_data_final_cobranca').hide();  
            $('#div_periodo').hide();  
            // $('#div_status').hide();

            $('#row_obs').hide();  
            $('#ok').hide();  

            $('#option_x_cliente_base').prop("disabled", true);
            $('#option_cliente_ativo').prop("disabled", true);
            $('#option_cliente_base').prop("disabled", true);
            $('#option_horas').prop("disabled", true);
            $('#option_ilimitado').prop("disabled", true);
            $('#option_mensal_desafogo').prop("disabled", true);
            $('#option_mensal').prop("disabled", true);
            $('#option_unitario').prop("disabled", true);

            $('#div_contrato_pai').hide();
            $('#div_separar_contrato').hide();
                
            $('#div_data_reajuste').hide();

            $('#div_qtd_clientes').hide();

            $('#div_valor_adesao').hide();
            
            $('#div_tipo_plantao').hide();  

            
            $('#div_valor_diferente_texto').hide();  
            $('#div_qtd_contratada_texto').hide();
            $('#div_valor_unitario_texto').hide();
            $('#div_valor_excedente_texto').hide();
            $('#div_desafogo_texto').hide();
            
            $('#qtd_contratada_texto').prop('required',false);
            $('#valor_unitario_texto').prop('required',false);
            $('#valor_excedente_texto').prop('required',false);

            $('#valor_diferente_texto').val(0); 

            $('#h4_2').hide();
            $('#h4_3').hide();
            $('#h4_4').hide();
            $('#h4_5').hide();
            $('#h4_6').hide();

            $('#hr_2').hide();
            $('#hr_3').hide();
            $('#hr_4').hide();
            $('#hr_5').hide();
            $('#hr_6').hide();

            $('#div_tipo_cobranca').hide();

            $('#div_qtd_clientes_teto').hide();
            $('#qtd_clientes_teto').prop('required',false);

        }       
    });

    $(document).on('submit', '#contrato_form', function () {

        var id_pessoa = $("#id_pessoa").val();
        var id_plano = $("#id_plano").val();
        var qtd_contratada = $("#qtd_contratada").val();
        var valor_unitario = $("#valor_unitario").val();
        var valor_total = $("#valor_total").val();
        var valor_inicial = $("#valor_inicial").val();
        var valor_excedente = $("#valor_excedente").val();
        var valor_plantao = $("#valor_plantao").val();
        var indice_reajuste = $("#indice_reajuste").val();
        var tipo_cobranca = $("#tipo_cobranca").val();
        var dia_pagamento = $("#dia_pagamento").val();
        var data_inicio_contrato = $(".data_inicio_contrato").val();
        var data_final_cobranca = $(".data_final_cobranca").val();
        var periodo_contrato = $("#periodo_contrato").val();
        var status = $("#status").val();

        var realiza_cobranca = $("#realiza_cobranca").val();
        var recebe_ligacao = $("#recebe_ligacao").val();
        var desafogo = $("#desafogo").val();
        var remove_duplicados = $("#remove_duplicados").val();
        var minutos_duplicados = $("#minutos_duplicados").val();
        var desconsidera_notificacao = $("#desconsidera_notificacao").val();

        

        var contrato_pai = $("#contrato_pai").val();
        var id_contrato_plano_pessoa = $("#id_contrato_plano_pessoa").val();

        var notifica_faturamento = $("#notifica_faturamento").val();
        
        //________________Nota_________________________________
        
        var cliente_id_cidade = $('#cliente_id_cidade').val();
        var cliente_logradouro = $('#cliente_logradouro').val();
        var cliente_numero = $('#cliente_numero').val();
        var cliente_bairro = $('#cliente_bairro').val();
        var cliente_cep = $('#cliente_cep').val();
        var cliente_razao_social = $('#cliente_razao_social').val();
        var cliente_cpf_cnpj = $('#cliente_cpf_cnpj').val();
        
        //________________Nota_________________________________
        
        var tipo_plantao = $('#tipo_plantao').val();
        var servico = $('#servico').val();

        if(servico == 'gestao_redes' && !tipo_plantao){
            alert("Deve-se selecionar um tipo de plantão!");
            return false;
        }

        if(contrato_pai == 1 && !id_contrato_plano_pessoa){
            alert("Deve-se selecionar um contrato vinculado!");
            return false;
        }

        if(!id_pessoa){
            alert("Deve-se selecionar uma pessoa válida!");
            return false;
        }
        if(!id_plano){
            alert("Deve-se selecionar um plano válido!");
            return false;
        }
        if(!qtd_contratada){
            alert("Deve-se selecionar uma quantidade contratada válida!");
            return false;
        }
        if(!valor_total){
            alert("Deve-se selecionar um valor total válido!");
            return false;
        }
        if(!indice_reajuste){
            alert("Deve-se selecionar um índice de reajuste válido!");
            return false;
        }
        if(!tipo_cobranca){
            alert("Deve-se selecionar um tipo de cobrança válido!");
            return false;
        }
        if(!dia_pagamento){
            alert("Deve-se selecionar um dia de pagamento válido!");
            return false;
        }
        if(dia_pagamento > 31){
            alert("Deve-se selecionar um dia de pagamento válido!");
            return false;
        }
        if(!data_inicio_contrato){
            alert("Deve-se selecionar uma data de início do contrato válida!");
            return false;
        }
        // if(!data_final_cobranca){
        //     alert("Deve-se selecionar uma data final de cobrança válida!");
        //     return false;
        // }
        if(!periodo_contrato){
            alert("Deve-se selecionar um período válido!");
            return false;
        }
        if(!status){
            alert("Deve-se selecionar um status válido!");
            return false;
        }
        
        if(notifica_faturamento == 1 && !confirm('O faturamento referente ao mês anterior ainda não foi gerado. Alterações de valores impactarão no faturamento pendente!')){ 
            return false;
        }

        //________________Nota_________________________________

        var alerta_nfs  = 'Para a emissão da NFS-e da pessoa selecionada, faltam os seguintes dados:\n';
        if(!cliente_id_cidade || cliente_id_cidade == '9999999'){
            alerta_nfs  = alerta_nfs+' - Cidade\n';
        }
        if(!cliente_logradouro){
            alerta_nfs  = alerta_nfs+' - Logradouro\n';
        }
        if(!cliente_numero){
            alerta_nfs  = alerta_nfs+' - Número do Logradouro\n';
        }
        if(!cliente_bairro){
            alerta_nfs  = alerta_nfs+' - Bairro\n';
        }
        if(!cliente_cep){
            alerta_nfs  = alerta_nfs+' - CEP\n';
        }
        if(!cliente_razao_social){
            alerta_nfs  = alerta_nfs+' - Razão Social\n';
        }
        if(!cliente_cpf_cnpj){
            alerta_nfs  = alerta_nfs+' - CPF/CNPJ\n';
        }

        if(!cliente_logradouro || !cliente_numero || !cliente_bairro || !cliente_cep || !cliente_razao_social || !cliente_cpf_cnpj || !cliente_id_cidade || cliente_id_cidade == '9999999'){
            alert(alerta_nfs.substring(0, alerta_nfs.length - 1));
        }

        //________________Nota_________________________________

        var valor_diferente_texto = $('#valor_diferente_texto').val();
        if(valor_diferente_texto == 0){
            $('#qtd_contratada_texto').val(0);
            $('#valor_unitario_texto').val('');
            $('#valor_excedente_texto').val('');
        }            
       
        modalAguarde();

    });

    $('#id_plano').on('change',function(){
        id_plano = $(this).val();
        tipo_cobranca = $('#tipo_cobranca').val();

        if(id_plano == 6 || (tipo_cobranca == 'x_cliente_base' && $('#servico').val() == 'call_suporte')){
            $('#valor_unitario').val(0);
            $('#div_valor_unitario').hide();            

            $('#div_qtd_clientes_teto').show();
                $('#qtd_clientes_teto').prop('required',true);
        }else{
            $('#valor_unitario').val('<?=$valor_unitario?>');
            $('#div_valor_unitario').show();

            $('#div_qtd_clientes_teto').hide();
                $('#qtd_clientes_teto').prop('required',false);
        }

        if(tipo_cobranca == 'mensal_desafogo' && ($('#servico').val() == 'call_suporte' || $('#servico').val() == 'call_monitoramento')){
            if('<?=$desafogo?>' != '0' && '<?=$desafogo?>'){
                $('#desafogo').val('<?=$desafogo?>');
            }else{
                $('#desafogo').val(20);
            }

            $('#desafogo').attr("disabled", false);
            $('#div_desafogo').show();
        }else{
            $('#desafogo').val(0);
            $('#desafogo').attr("disabled", true);
            $('#div_desafogo').hide();

        }  

        if(tipo_cobranca == 'unitario' && ($('#servico').val() == 'call_suporte' || $('#servico').val() == 'call_monitoramento')){
            $('#div_valor_inicial').val('<?=$valor_inicial?>');
            $('#div_valor_inicial').show();

        }else{
            $('#div_valor_inicial').val(0);
            $('#div_valor_inicial').hide();
        } 
        
        selectversao($("select[name=id_plano]").val());      

    });  

    $('#servico').on('change',function(){
        servico = $(this).val();
        $('#tipo_cobranca').val('');
        if(servico == 'call_suporte' || servico == 'call_monitoramento'){
            $('#div_qtd_contratada').show();
            $('#div_valor_unitario').show();
            $('#div_valor_total').show();       
            $('#div_valor_inicial').show();  
            $('#div_valor_excedente').show();
            $('#div_valor_plantao').hide();
            $('#div_indice_reajuste').show();

            $('#div_valor_diferente_texto').hide();  
            $('#div_qtd_contratada_texto').hide();
            $('#div_valor_unitario_texto').hide();
            $('#div_valor_excedente_texto').hide();
            $('#div_desafogo_texto').hide();
            
            $('#qtd_contratada_texto').prop('required',false);
            $('#valor_unitario_texto').prop('required',false);
            $('#valor_excedente_texto').prop('required',false);
            
            var tipo_cobranca = $('#tipo_cobranca').val();

            if(tipo_cobranca == 'unitario'){
                $('#div_valor_inicial').show();       
            }else{
                $('#div_valor_inicial').hide();       
            }
            if(tipo_cobranca == 'mensal_desafogo'){
                $('#div_desafogo').show();

                if(servico == 'call_suporte' && $('#valor_diferente_texto').val() == 1){
                    $('#div_desafogo_texto').show();
                    $('#desafogo_texto').prop('disabled',false);
                }else{
                    $('#div_desafogo_texto').hide();
                }


            }else{
                $('#div_desafogo').hide();

                $('#div_desafogo_texto').hide();

            } 
            var valor_diferente_texto = '<?=$valor_diferente_texto?>';

            if(valor_diferente_texto == 1){
                $('#valor_diferente_texto').val(1); 
            } else{
                $('#valor_diferente_texto').val(0); 
            }

            $('#div_realiza_cobranca').show();  
            $('#div_desconsidera_notificacao').show();  

            
            if(remove_duplicados == 1){
                $('#div_minutos_duplicados').show();
            }else{
                $('#div_minutos_duplicados').hide();
            }

            $('#div_pagamento').show();  
            $('.div_inicio').show();  
            $('#div_data_final_cobranca').show();  
            $('#div_periodo').show();  
            $('#div_status').show();

            $('#row_obs').show();  
            $('#ok').show(); 

            $('#option_cliente_ativo').prop("disabled", true);
            $('#option_cliente_base').prop("disabled", true);
            $('#option_horas').prop("disabled", true);
            $('#option_ilimitado').prop("disabled", true);
            $('#option_mensal_desafogo').prop("disabled", false);
            $('#option_mensal').prop("disabled", false);
            $('#option_unitario').prop("disabled", false);

            $('#div_contrato_pai').show();
            $('#div_separar_contrato').show();
                
            $('#div_data_reajuste').show();

            $('#div_qtd_clientes').show();

            $('#div_valor_adesao').show();
            
            $('#div_tipo_plantao').hide();  

            if(servico == 'call_suporte'){
                $('#option_x_cliente_base').prop("disabled", false);
                $('#div_valor_diferente_texto').show();  
                $('#div_recebe_ligacao').show();  
                $('#div_remove_duplicado').show();  
            }else{
                $('#option_x_cliente_base').prop("disabled", true);
                $('#div_valor_diferente_texto').hide();  
                $('#div_recebe_ligacao').hide();  
                $('#div_remove_duplicado').hide();  
            }

            $('#h4_2').show();
            $('#h4_3').show();
            $('#h4_4').show();
            $('#h4_5').show();
            $('#h4_6').show();

            $('#hr_2').show();
            $('#hr_3').show();
            $('#hr_4').show();
            $('#hr_5').show();
            $('#hr_6').show();

            $('#div_tipo_cobranca').show();

        }else if(servico == 'call_ativo'){
            $('#div_qtd_contratada').show();
            $('#div_valor_unitario').show();
            $('#div_valor_total').show();       
            $('#div_valor_inicial').hide();  
            $('#div_valor_excedente').hide();
            $('#div_valor_plantao').hide();
            $('#div_indice_reajuste').show();
            $('#div_desafogo').hide();

            $('#div_realiza_cobranca').show();  
            $('#div_recebe_ligacao').hide();  
            $('#div_remove_duplicado').hide();  
            $('#div_minutos_duplicados').hide(); 
            $('#div_desconsidera_notificacao').show(); 
            

            $('#div_pagamento').show();  
            $('.div_inicio').show();  
            $('#div_data_final_cobranca').show();  
            $('#div_periodo').show();  
            $('#div_status').show();  

            $('#row_obs').show();  
            $('#ok').show();     

            $('#option_x_cliente_base').prop("disabled", true);
            $('#option_cliente_ativo').prop("disabled", true);
            $('#option_cliente_base').prop("disabled", true);
            $('#option_horas').prop("disabled", true);
            $('#option_ilimitado').prop("disabled", true);
            $('#option_mensal_desafogo').prop("disabled", true);
            $('#option_mensal').prop("disabled", false);
            $('#option_unitario').prop("disabled", false);

            $('#div_contrato_pai').show();
            $('#div_separar_contrato').show();
                
            $('#div_data_reajuste').show();

            $('#div_qtd_clientes').show();

            $('#div_valor_adesao').show();
            
            $('#div_tipo_plantao').hide();  

            
            $('#div_valor_diferente_texto').hide(); 
            $('#valor_diferente_texto').val(0);  

            $('#h4_2').show();
            $('#h4_3').show();
            $('#h4_4').show();
            $('#h4_5').show();
            $('#h4_6').show();

            $('#hr_2').show();
            $('#hr_3').show();
            $('#hr_4').show();
            $('#hr_5').show();
            $('#hr_6').show();

            $('#div_tipo_cobranca').show();
         
        }else if(servico == 'gestao_redes'){

            $('#div_qtd_contratada').show();
            $('#div_valor_unitario').show();
            $('#div_valor_total').show();       
            $('#div_valor_inicial').show();  
            $('#div_valor_excedente').show();
            $('#div_valor_plantao').show();
            $('#div_indice_reajuste').show();
            $('#div_desafogo').hide();  

            $('#div_realiza_cobranca').show();  
            $('#div_recebe_ligacao').hide();  
            $('#div_remove_duplicado').hide();  
            $('#div_minutos_duplicados').hide();  
            $('#div_desconsidera_notificacao').show(); 
   
            
            $('#div_pagamento').show();  
            $('.div_inicio').show();  
            $('#div_data_final_cobranca').show();  
            $('#div_periodo').show();  
            $('#div_status').show();  

            $('#row_obs').show();  
            $('#ok').show(); 

            $('#option_x_cliente_base').prop("disabled", false);
            $('#option_cliente_ativo').prop("disabled", false);
            $('#option_cliente_base').prop("disabled", false);
            $('#option_horas').prop("disabled", false);
            $('#option_ilimitado').prop("disabled", false);
            $('#option_mensal_desafogo').prop("disabled", true);
            $('#option_mensal').prop("disabled", true);
            $('#option_unitario').prop("disabled", true);

            $('#div_contrato_pai').show();
            $('#div_separar_contrato').show();
                
            $('#div_data_reajuste').show();

            $('#div_qtd_clientes').show();

            $('#div_valor_adesao').show();
            
            $('#div_tipo_plantao').show(); 

            
            $('#div_valor_diferente_texto').hide();  
            $('#valor_diferente_texto').val(0);  

            $('#h4_2').show();
            $('#h4_3').show();
            $('#h4_4').show();
            $('#h4_5').show();
            $('#h4_6').show();

            $('#hr_2').show();
            $('#hr_3').show();
            $('#hr_4').show();
            $('#hr_5').show();
            $('#hr_6').show();

            $('#div_tipo_cobranca').show();
          
        }else{
            $('#div_qtd_contratada').hide();
            $('#div_valor_unitario').hide();
            $('#div_valor_total').hide();       
            $('#div_valor_inicial').hide();  
            $('#div_valor_excedente').hide();
            $('#div_valor_plantao').hide();
            $('#div_indice_reajuste').hide();
            $('#div_desafogo').hide();     

            $('#div_realiza_cobranca').hide();  
            $('#div_recebe_ligacao').hide();  
            $('#div_remove_duplicado').hide();  
            $('#div_minutos_duplicados').hide(); 
            $('#div_desconsidera_notificacao').hide(); 
 
            
            $('#div_pagamento').hide();  
            $('.div_inicio').hide();  
            $('#div_data_final_cobranca').hide();  
            $('#div_periodo').hide();  
            // $('#div_status').hide();

            $('#row_obs').hide();  
            $('#ok').hide();  

            $('#option_x_cliente_base').prop("disabled", true);
            $('#option_cliente_ativo').prop("disabled", true);
            $('#option_cliente_base').prop("disabled", true);
            $('#option_horas').prop("disabled", true);
            $('#option_ilimitado').prop("disabled", true);
            $('#option_mensal_desafogo').prop("disabled", true);
            $('#option_mensal').prop("disabled", true);
            $('#option_unitario').prop("disabled", true);

            $('#div_contrato_pai').hide();
            $('#div_separar_contrato').hide();
                
            $('#div_data_reajuste').hide();

            $('#div_qtd_clientes').hide();

            $('#div_valor_adesao').hide();
            
            $('#div_tipo_plantao').hide();  

            
            $('#div_valor_diferente_texto').hide(); 
            $('#valor_diferente_texto').val(0);  

            $('#h4_2').hide();
            $('#h4_3').hide();
            $('#h4_4').hide();
            $('#h4_5').hide();
            $('#h4_6').hide();

            $('#hr_2').hide();
            $('#hr_3').hide();
            $('#hr_4').hide();
            $('#hr_5').hide();
            $('#hr_6').hide();

            $('#div_tipo_cobranca').hide();
            
           }
    });

    $('#tipo_cobranca').on('change',function(){
        
        var id_plano = $('select[name=id_plano]').val();
        var tipo_cobranca = $(this).val();

        if(tipo_cobranca == 'x_cliente_base' && $('#servico').val() == 'call_suporte'){
            $('#div_qtd_contratada').show();
            $('#div_valor_unitario').hide();
            $('#valor_unitario').val(0);
            $('#div_valor_total').show();       
            $('#div_valor_inicial').hide();  
            $('#div_valor_excedente').show();
            $('#div_valor_plantao').hide();
            $('#div_indice_reajuste').show();
            
            $('#div_valor_inicial').hide();       
        
            $('#desafogo').val(0);
            $('#div_desafogo').hide();

            $('#div_recebe_ligacao').show();
            $('#div_realiza_cobranca').show();  
            $('#div_remove_duplicado').hide();  
            $('#div_desconsidera_notificacao').show(); 

            $('#div_minutos_duplicados').hide();

            $('#div_pagamento').show();  
            $('.div_inicio').show();  
            $('#div_data_final_cobranca').show();  
            $('#div_periodo').show();  
            $('#div_status').show();

            $('#row_obs').show();  
            $('#ok').show(); 

            $('#option_cliente_ativo').prop("disabled", true);
            $('#option_cliente_base').prop("disabled", true);
            $('#option_horas').prop("disabled", true);
            $('#option_ilimitado').prop("disabled", true);
            $('#option_mensal_desafogo').prop("disabled", false);
            $('#option_mensal').prop("disabled", false);
            $('#option_unitario').prop("disabled", false);

            // selectplano($('select[name=servico]').val());
            // selectversao("<?=$id_plano?>");
        
            $('#div_contrato_pai').show();
            $('#div_separar_contrato').show();
                
            $('#div_data_reajuste').show();
            $('#div_qtd_clientes').show();
            $('#div_valor_adesao').show();
            $('#div_tipo_plantao').hide();  
        
            //OI
            $('#option_x_cliente_base').prop("disabled", false);
            $('#div_valor_diferente_texto').hide();  

            $('#div_qtd_contratada_texto').hide();
            $('#div_valor_unitario_texto').hide();
            $('#div_valor_excedente_texto').hide();
            $('#div_desafogo_texto').hide();

            $('#qtd_contratada_texto').prop('required',false);
            $('#valor_unitario_texto').prop('required',false);
            $('#valor_excedente_texto').prop('required',false);
            
            $('#div_recebe_ligacao').show();
            $('#div_remove_duplicado').hide();  

            $('#h4_2').show();
            $('#h4_3').show();
            $('#h4_4').show();
            $('#h4_5').show();
            $('#h4_6').show();

            $('#hr_2').show();
            $('#hr_3').show();
            $('#hr_4').show();
            $('#hr_5').show();
            $('#hr_6').show();

            $('#div_tipo_cobranca').show();       

            $('#div_qtd_clientes_teto').show();
            $('#qtd_clientes_teto').prop('required',true);
        }else{
            $('#div_qtd_contratada').show();
            $('#div_valor_unitario').show();
            $('#div_valor_total').show();       
            $('#div_valor_inicial').hide();  
            $('#div_valor_excedente').show();
            $('#div_valor_plantao').hide();
            $('#div_indice_reajuste').show();
            
            var tipo_cobranca = $('#tipo_cobranca').val();
            if(tipo_cobranca == 'unitario'){
                $('#div_valor_inicial').show();       
            }else{
                $('#div_valor_inicial').hide();       
            }
            if(tipo_cobranca == 'mensal_desafogo'){
                if('<?=$desafogo?>' != '0' && '<?=$desafogo?>'){
                    $('#desafogo').val('<?=$desafogo?>');
                }else{
                    $('#desafogo').val(20);
                }
                $('#div_desafogo').show();

            }else{
                $('#desafogo').val(0);
                $('#div_desafogo').hide();
            } 

            $('#div_recebe_ligacao').show();
            $('#div_realiza_cobranca').show();  
            $('#div_remove_duplicado').show();  
            $('#div_desconsidera_notificacao').show(); 

            
            if(remove_duplicados == 1){
                $('#div_minutos_duplicados').show();
            }else{
                $('#div_minutos_duplicados').hide();
            }

            $('#div_pagamento').show();  
            $('.div_inicio').show();  
            $('#div_data_final_cobranca').show();  
            $('#div_periodo').show();  
            $('#div_status').show();

            $('#row_obs').show();  
            $('#ok').show(); 

            $('#option_cliente_ativo').prop("disabled", true);
            $('#option_cliente_base').prop("disabled", true);
            $('#option_horas').prop("disabled", true);
            $('#option_ilimitado').prop("disabled", true);
            $('#option_mensal_desafogo').prop("disabled", false);
            $('#option_mensal').prop("disabled", false);
            $('#option_unitario').prop("disabled", false);

            // selectplano($('select[name=servico]').val());
            // selectversao("<?=$id_plano?>");
        
            $('#div_contrato_pai').show();
            $('#div_separar_contrato').show();
                
            $('#div_data_reajuste').show();
            $('#div_qtd_clientes').show();
            $('#div_valor_adesao').show();
            $('#div_tipo_plantao').hide();  
        
            if(servico != 'call_monitoramento'){

                $('#option_x_cliente_base').prop("disabled", false);

                $('#div_valor_diferente_texto').show();  
                var valor_diferente_texto = $('#valor_diferente_texto').val();
                if(valor_diferente_texto == 1){
                    $('#div_qtd_contratada_texto').show();
                    $('#div_valor_unitario_texto').show();
                    $('#div_valor_excedente_texto').show();

                    $('#qtd_contratada_texto').prop('required',true);
                    $('#valor_unitario_texto').prop('required',true);
                    $('#valor_excedente_texto').prop('required',true);


                }else{
                    $('#div_qtd_contratada_texto').hide();
                    $('#div_valor_unitario_texto').hide();
                    $('#div_valor_excedente_texto').hide();
                    $('#div_desafogo_texto').hide();

                    $('#qtd_contratada_texto').prop('required',false);
                    $('#valor_unitario_texto').prop('required',false);
                    $('#valor_excedente_texto').prop('required',false);
                }
            }else{

                $('#option_x_cliente_base').prop("disabled", true);

                $('#div_valor_diferente_texto').hide();  

                $('#div_qtd_contratada_texto').hide();
                $('#div_valor_unitario_texto').hide();
                $('#div_valor_excedente_texto').hide();
                $('#div_desafogo_texto').hide();

                $('#qtd_contratada_texto').prop('required',false);
                $('#valor_unitario_texto').prop('required',false);
                $('#valor_excedente_texto').prop('required',false);
                
                $('#div_recebe_ligacao').hide();
                $('#div_remove_duplicado').hide();  
            }

            $('#h4_2').show();
            $('#h4_3').show();
            $('#h4_4').show();
            $('#h4_5').show();
            $('#h4_6').show();

            $('#hr_2').show();
            $('#hr_3').show();
            $('#hr_4').show();
            $('#hr_5').show();
            $('#hr_6').show();

            $('#div_tipo_cobranca').show();       

            $('#div_qtd_clientes_teto').hide();
            $('#qtd_clientes_teto').prop('required',false);
        }

        if(tipo_cobranca == 'mensal_desafogo' && ($('#servico').val() == 'call_suporte' || $('#servico').val() == 'call_monitoramento')){
            if('<?=$desafogo?>' != '0' && '<?=$desafogo?>'){
                $('#desafogo').val('<?=$desafogo?>');
            }else{
                $('#desafogo').val(20);
            }
            var valor_diferente_texto = $('#valor_diferente_texto').val();
            if ($('#servico').val() == 'call_suporte' && valor_diferente_texto == 1){

                if('<?=$desafogo_texto?>' != '0' && '<?=$desafogo_texto?>'){
                    $('#desafogo_texto').val('<?=$desafogo_texto?>');
                }else{
                    $('#desafogo_texto').val(20);
                } 
                $('#desafogo_texto').attr("disabled", false);
                $('#div_desafogo_texto').show();
            }else{
                $('#desafogo_texto').val(0);
                $('#desafogo_texto').attr("disabled", true);
                $('#div_desafogo_texto').hide();
            }

            
            $('#desafogo').attr("disabled", false);
            $('#div_desafogo').show();

        }else{
            $('#desafogo').val(0);
            $('#desafogo').attr("disabled", true);
            $('#div_desafogo').hide();

            $('#desafogo_texto').val(0);
            $('#desafogo_texto').attr("disabled", true);
            $('#div_desafogo_texto').hide();
        }  

        if(tipo_cobranca == 'unitario' && ($('#servico').val() == 'call_suporte' || $('#servico').val() == 'call_monitoramento')){
            $('#div_valor_inicial').val('<?=$valor_inicial?>');
            $('#div_valor_inicial').show();

        }else{
            $('#div_valor_inicial').val(0);
            $('#div_valor_inicial').hide();
        } 

       
    });  

    $('#remove_duplicados').on('change',function(){
        remove_duplicados = $(this).val();
        if(remove_duplicados == 0){
            $('#minutos_duplicados').val(0);
            $('#div_minutos_duplicados').hide();  

        }else{
            if("<?=$minutos_duplicados?>" != '0' && "<?=$minutos_duplicados?>"){
                $("#minutos_duplicados").val("<?=$minutos_duplicados?>");
            }else{
                $("#minutos_duplicados").val(15);
            }
            $('#div_minutos_duplicados').show();  
        }
    }); 

    $('#desconsidera_notificacao').on('change',function(){
        desconsidera_notificacao = $(this).val();
        if(desconsidera_notificacao == 0){
            $('#valor_desconsidera_notificacao').val(0)
            $('#div_valor_desconsidera_notificacao').hide();

        }else{
            if("<?=$valor_desconsidera_notificacao?>" != '0' && "<?=$valor_desconsidera_notificacao?>"){
                $("#valor_desconsidera_notificacao").val("<?=$valor_desconsidera_notificacao?>");
            }else{
                $("#valor_desconsidera_notificacao").val(0);
            }
            $('#div_valor_desconsidera_notificacao').show();  
        }
    }); 

      

    $('#contrato_pai').on('change',function(){
        contrato_pai = $(this).val();

        if(contrato_pai == 0){
            $('#div_id_contrato_pai').hide();
        }else{
            $('#div_id_contrato_pai').show();
        } 
    });
    
    $('#separar_contrato').on('change',function(){
        separar_contrato = $(this).val();

        if(separar_contrato == 0){
            $('#div_id_separar_contrato').hide();
        }else{
            $('#div_id_separar_contrato').show();
        } 
    });

    function selectplano(cod_servico, id_plano){        
        var id_plano  = '<?=$id_plano?>';
        var operacao = '<?=$operacao?>';
        var id = '<?=$id?>';
// NAO SEI
        $("select[name=id_plano]").html('<option value="">Carregando...</option>');
        $.post("/api/ajax?class=SelectPlano.php",
            {
                cod_servico: cod_servico,
                id_plano: id_plano,
                operacao: operacao,
                id: id,
                token: '<?= $request->token ?>'
            },
            function(valor){
                $("select[name=id_plano]").html(valor);
                if(id_plano == 6){
                    $('#div_valor_unitario').hide();
                }
                selectversao($("select[name=id_plano]").val());

            }
        )        
    }

    function selectversao(id_plano){        
        var versao_atual = '<?=$versao?>';
        
        var id_plano_antigo = '<?=$id_plano?>';
        var personalizado_antigo = '<?=$personalizado?>';

        var personalizado = $('#personalizado').val();        
        
        if('<?=isset($_GET["alterar"])?>'){
            if(id_plano == id_plano_antigo){
                if(personalizado_antigo == 1){
                    $('#personalizado_procedimento').val(1);
                    $('#personalizado').val(1);
                    // $('#personalizado_procedimento_sim').attr('selected', true);
                    // $('#personalizado_procedimento_nao').attr('selected', false);
                }else{
                    $('#personalizado_procedimento').val(0);
                    $('#personalizado').val(0);

                    // $('#personalizado_procedimento_sim').attr('selected', false);
                    // $('#personalizado_procedimento_nao').attr('selected', true);
                }

                $('#personalizado_procedimento').attr('disabled', true);
                $('#personalizado').attr('personalizado', true);

            }else{
                $('#personalizado_procedimento').val(0);
                $('#personalizado').val(0);

                $('#personalizado_procedimento').attr('disabled', false);
                $('#personalizado').attr('personalizado', false);
                // $('#personalizado_procedimento_sim').attr('selected', false);
                // $('#personalizado_procedimento_nao').attr('selected', true);
            }

        }
        
        // alert($("#personalizado").val());

        $.post("/api/ajax?class=SelectVersao.php",
            {
                id_plano: id_plano,
                versao_atual: versao_atual,
                token: '<?= $request->token ?>'
            },
            function(valor){
                $("#versao").val(valor);
                call_busca_ajax(valor, id_plano);
            }
        )    
    }

    function call_busca_ajax(versao, id_plano, procedimento = 0){   
        var id_plano_antigo = '<?=$id_plano?>';
        var personalizado_antigo = '<?=$personalizado?>';

        var personalizado = $('#personalizado').val();        
        
        var disabled = '';
        if('<?=isset($_GET["alterar"])?>'){
            if(procedimento == 0){
                if(id_plano == id_plano_antigo){
                    if(personalizado_antigo == 1){
                        $('#personalizado_procedimento').val(1);
                        $('#personalizado').val(1);
                    }else{
                        $('#personalizado_procedimento').val(0);
                        $('#personalizado').val(0);
                    }

                    $('#personalizado_procedimento').attr('disabled', true);
                    $('#personalizado').attr('personalizado', true);

                    disabled = '1';
                }else{
                    $('#personalizado_procedimento').val(0);
                    $('#personalizado').val(0);

                    $('#personalizado_procedimento').attr('disabled', false);
                    $('#personalizado').attr('personalizado', false);

                }

            }

            
            var personalizado_procedimento = $('#personalizado_procedimento').val();
            $('#personalizado').val(personalizado_procedimento);

            
            $('#versao_procedimento').val(versao);
            $('#plano_procedimento').val($("#id_plano option:selected").text());

                var parametros = {
                    'versao': versao,
                    'id_plano': id_plano,
                    'personalizado_procedimento': personalizado_procedimento,
                    'disabled': disabled
                };
                busca_ajax('<?= $request->token ?>' , 'PlanoProcedimentoContratoBusca', 'resultado_procedimento', parametros);



        }else{
            if(!id_plano_antigo){

                if(personalizado == 1 && id_plano != id_plano_antigo){
                    var personalizado_procedimento = 1;
                    var parametros = {
                        'versao': versao,
                        'id_plano': id_plano,
                        'personalizado_procedimento': personalizado_procedimento
                    };
                    busca_ajax('<?= $request->token ?>' , 'PlanoProcedimentoContratoBusca', 'resultado_busca', parametros);

                }else{
                    $("#resultado_busca").html('');
                }

                if(id_plano){
                    $('#div_personalizado').show();
                    $('#div_versao').show();
                }else{
                    $('#div_personalizado').hide();
                    $('#div_versao').hide();
                }

                }else{
                $("#resultado_busca").html('');

                if(id_plano){
                    $('#div_personalizado').hide();
                    $('#div_versao').show();
                }else{
                    $('#div_personalizado').hide();
                    $('#div_versao').hide();
                }
            }
        }
    }

    
    $('.accordionPlano').on('shown.bs.collapse', function(){
        var i_collapse_ = $(this).attr('id').split("_");
        i_collapse_ = '#i_collapse_'+i_collapse_[1];
        $(i_collapse_).removeClass("fa fa-plus").addClass("fa fa-minus");
    });
    $('.accordionPlano').on('hidden.bs.collapse', function(){
        var i_collapse_ = $(this).attr('id').split("_");
        i_collapse_ = '#i_collapse_'+i_collapse_[1];
        $(i_collapse_).removeClass("fa fa-minus").addClass("fa fa-plus");
    });

    $(document).on('change', 'select[name=personalizado_procedimento]', function(){
        call_busca_ajax($("#versao").val(), $("#id_plano").val(), '1');
    });

    $(document).on('change', 'select[name=personalizado]', function(){
        selectversao($('select[name=id_plano]').val());
    });

    $(document).on('change', 'select[name=servico]', function(){
        selectplano($(this).val());
    });

    $(document).on('change', 'select[name=id_plano]', function(){
        selectversao($(this).val());
    });

    $('#valor_diferente_texto').on('change',function(){
        var valor_diferente_texto = $(this).val();
        if(valor_diferente_texto == 0){

            $('#div_qtd_contratada_texto').hide();
            $('#div_valor_unitario_texto').hide();
            $('#div_valor_excedente_texto').hide();
            $('#div_desafogo_texto').hide();
            
        }else{

            $('#div_qtd_contratada_texto').show();
            $('#div_valor_unitario_texto').show();
            $('#div_valor_excedente_texto').show();
           
            var tipo_cobranca = $('#tipo_cobranca').val();
            if(tipo_cobranca == 'mensal_desafogo'){
                $('#div_desafogo_texto').show();
                $('#desafogo_texto').prop('disabled',false);
                if('<?=$desafogo_texto?>' != '0' && '<?=$desafogo_texto?>'){
                    $('#desafogo_texto').val('<?=$desafogo_texto?>');
                }else{
                    $('#desafogo_texto').val(20);
                } 
            }else{
                $('#div_desafogo_texto').hide();
            }

        }
    }); 
    
    function readURL(input) {
            if (input.files && input.files[0]) {
		        var reader = new FileReader();
		        
		        reader.onload = function (e) {
		            $('#pdf_contrato').attr('src', e.target.result);
		        }
		        
		        reader.readAsDataURL(input.files[0]);
                $('#texto_pdf_contrato').text('Substituir PDF');
                $('#btn_pdf_contrato').css("background-color", '#3e8f3e');
		    }		    
		}
        
        $("#pdf_contrato").change(function(){
		    readURL(this);
		});

</script>