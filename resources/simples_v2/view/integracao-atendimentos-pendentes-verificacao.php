<?php
$id_integracao_atendimento_ixc = $_GET['id_integracao_atendimento_ixc'];
$nome_provedor = $_GET['nome_provedor'];

$atendimentos_pendentes = DBRead('', "tb_integracao_atendimento_ixc a", "WHERE salvo = 0 AND a.id_integracao_atendimento_ixc = $id_integracao_atendimento_ixc");

//var_dump($atendimentos_pendentes);

$id_contrato_plano_pessoa = $atendimentos_pendentes[0]['id_contrato_plano_pessoa'];

$dados_obrigatorios = DBRead('', 'tb_dados_obrigatorios_integracao', "WHERE id_integracao = 1 AND id_contrato_plano_pessoa = '$id_contrato_plano_pessoa'");

/** Bloco que gera o protocolo do atendimento no sistema ixc */
//Criar código que gera protocolo na tela de envio de atendimentos que não tenham sido enviados automaticamente
require_once __DIR__."/../class/integracoes/ixc/Parametros.php";
require_once __DIR__."/../class/integracoes/ixc/WebServiceClient.php";
$parametros = new Integracao\Ixc\Parametros();
$parametros->setParametros($id_contrato_plano_pessoa);
$api = new IXCsoft\WebserviceClient($parametros->getHost(), $parametros->getToken(), $parametros->getSelfSigned());
$api->get('gerar_protocolo_atendimento');
$retorno_protocolo = $api->getRespostaConteudo(true);
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////

$retorno_protocolo = $retorno_protocolo ? $retorno_protocolo : "";

$sistema = "";
if (!$retorno_protocolo) {
    $sistema = "Sistema de gestão offline";
    $alert = "alert-warning";
} else {
    $sistema = "Sistema de gestão online";
    $alert = "alert-success";
}

$nome_assinante = $atendimentos_pendentes[0]['nome_assinante'];
$id_assinante = $atendimentos_pendentes[0]['id_cliente'];
$contrato = $atendimentos_pendentes[0]['id_contrato'];
$script_atendimento = $atendimentos_pendentes[0]['mensagem'];
$titulo = $atendimentos_pendentes[0]['titulo'] ? $atendimentos_pendentes[0]['titulo'] : "";
$id_integracao_atendimento_ixc = $atendimentos_pendentes[0]['id_integracao_atendimento_ixc'];
$situacao = $atendimentos_pendentes[0]['situacao'] ? $atendimentos_pendentes[0]['situacao'] : "";
$id_atendimento = $atendimentos_pendentes[0]["id_atendimento_belluno"] ? $atendimentos_pendentes[0]["id_atendimento_belluno"] : '';

$id_atendente_simples = $atendimentos_pendentes[0]['id_atendente_simples'] ? $atendimentos_pendentes[0]['id_atendente_simples'] : "";
$retorno_api = $atendimentos_pendentes[0]['retorno_api'];

$retorno_assunto = array();
$retorno_departamento = array();
$retorno_filial = array();
$retorno_setor = array();
$retorno_tecnico = array();
if ($dados_obrigatorios) {
    foreach ($dados_obrigatorios as $key => $conteudo) {
        if ($conteudo['chave'] == "assunto") {
            $retorno_assunto['registros'][$key]['id'] = $conteudo['valor_id'];
            $retorno_assunto['registros'][$key]['assunto'] = $conteudo['valor_descricao'];
        }
        if ($conteudo['chave'] == "departamento") {
            $retorno_departamento['registros'][$key]["id"] = $conteudo['valor_id'];
            $retorno_departamento['registros'][$key]["setor"] = $conteudo['valor_descricao'];
        }
        if ($conteudo['chave'] == "filial") {
            $retorno_filial['registros'][$key]["id"] = $conteudo['valor_id'];
            $retorno_filial['registros'][$key]["razao"] = $conteudo['valor_descricao'];
        }
        if ($conteudo['chave'] == "setor") {
            $retorno_setor['registros'][$key]["id"] = $conteudo['valor_id'];
            $retorno_setor['registros'][$key]["setor"] = $conteudo['valor_descricao'];
        }
        if ($conteudo['chave'] == "funcionario") {
            $retorno_tecnico['registros'][$key]["id"] = $conteudo['valor_id'];
            $retorno_tecnico['registros'][$key]["funcionario"] = $conteudo['valor_descricao'];
        }
    }
    $retorno_assunto = json_encode($retorno_assunto);
    $retorno_departamento = json_encode($retorno_departamento);
    $retorno_filial = json_encode($retorno_filial);
    $retorno_setor = json_encode($retorno_setor);
    $retorno_tecnico = json_encode($retorno_tecnico);
}

?>


<style>
    .help-block {
        color: #a94442;
    }
</style>
<div class="container">
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="row">
                <div class="col-md-6">
                    <h3 class="panel-title text-left">Verificar:</h3>
                </div>
                <div class="col-md-6">
                    <a href="/api/iframe?token=<?php echo $request->token ?>&view=exibe-quadro-informativo&contrato=<?= $id_contrato_plano_pessoa ?>" target="_blank" class="pull-right btn btn-xs btn-primary">Quadro informativo</a>
                </div>
            </div>
        </div>
        <div class="panel-body">
            <div class="alert <?= $alert ?>" style="text-align: center" id="alerta-sistema-gestao"><?= $sistema ?></div>

            <?php 
                if ($perfil_usuario == 2 || $perfil_usuario == 15 || $perfil_usuario == 13) {
                    echo '<span>Retorno IXC:</span>';
                    echo '<div class="alert alert-danger" style="text-align: center" id="alerta-sistema-gestao">'.$retorno_api.'</div>';
                }
            ?>
            
            <div class="row">
                <div class="col-md-12">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th width='50%'>Classificação de atendimento no sistema de gestão:</th>
                                <th width='50%'>Seleção de finalização no sistema de gestão:</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $quadro_informativo = DBRead('', 'tb_informacao_geral_contrato', "WHERE id_contrato_plano_pessoa = '$id_contrato_plano_pessoa'", "classificacao_atendimento_sistema_gestao, selecao_finalizacao_sistema_gestao");
                            if ($quadro_informativo) :
                                foreach ($quadro_informativo as $conteudo) :
                            ?>
                                    <tr>
                                        <td><?= nl2br($conteudo['classificacao_atendimento_sistema_gestao']) ?></td>
                                        <td><?= nl2br($conteudo['selecao_finalizacao_sistema_gestao']) ?></td>
                                    </tr>
                            <?php
                                endforeach;
                            endif;
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <form method="post" action="/api/ajax?class=IntegracaoAtendimentosPendentes.php" id="pendentes_form" style="margin-bottom: 0;">
		        <input type="hidden" name="token" value="<?php echo $request->token ?>">

                <div class="row">
                    <div class="col-md-12" style="margin-bottom: 10px;">
                        <label for="situacao">*Situação:</label>
                        <select class="form-control" id="situacao" name="situacao">
                            <option <?= $situacao == 4 ? 'selected' : '' ?> value="4">ATENDIMENTO ENCAMINHADO AO SETOR RESPONSÁVEL.</option>
                            <option <?= $situacao == 3 ? 'selected' : '' ?> value="3">ATENDIMENTO ENCERRADO.</option>
                            <option <?= $situacao == 7 ? 'selected' : '' ?> value="7">ATENDIMENTO VINCULADO A OS JÁ EXISTENTE.</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Empresa:</label>
                            <input type="text" disabled value="<?= $nome_provedor ?>" class="form-control" />
                        </div>
                    </div>
                </div>
                <div id="parametros-integracao">
                    <input type="hidden" id="id_atendimento" name="id_atendimento" value="<?= $id_atendimento ?>" />
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Assinante:</label>
                                <input class="form-control" id="nome_assinante" type="text" disabled value="<?= $nome_assinante ?>" />
                                <input name="assinante" type="hidden" value="<?= $nome_assinante ?>" />
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Protocolo:</label>
                                <input class="form-control" type="text" disabled value="<?= $retorno_protocolo ?>" />
                                <input name="protocolo_integracao_integracao" type="hidden" value="<?= $retorno_protocolo ?>" />
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>*Classificação:</label>
                                <select name="classificacao" id="classificacao" required class="form-control">
                                    <option value="0"></option>
                                    <option value="1">Ordem de serviço</option>
                                    <option value="2">Atendimento</option>
                                </select>
                                <span id="alerta" class="text-danger">Classificação obrigatório!</span>
                            </div>
                        </div>
                    </div>

                    <div id="bloco-integracao" style="display: none;">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Assunto:</label>
                                    <select name="id_assunto" id="id_assunto" class="form-control">
                                        <option></option>
                                        <?php
                                        foreach ($dados_obrigatorios as $conteudo) {
                                            if ($conteudo['chave'] == "assunto") {
                                                echo "<option value='" . $conteudo['valor_id'] . "'>" . $conteudo['valor_descricao'] . "</option>";
                                            }
                                        }
                                        ?>
                                    </select>
                                    <!-- <span id="alerta-assunto" class="text-danger">Assunto obrigatório!</span> -->
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>*Prioridade:</label>
                                    <select name="prioridade" id="prioridade" required class="form-control">
                                        <option></option>
                                        <option value="B">Baixa</option>
                                        <option value="N">Normal</option>
                                        <option value="A">Alta</option>
                                        <option value="C">Crítica</option>
                                    </select>
                                    <!-- <span id="help-prioridade" class="help-block">Prioridade obrigatório!</span> -->
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group" id="bloco-setor-departamento">
                                    <label id="setor-departamento">*Setor:</label>
                                    <select class="form-control setor" id="id_setor" name='id_setor'></select>
                                    <!-- <span id="help-departamento" class="help-block">Setor obrigatório!</span> -->
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Filial:</label>
                                    <select name="id_filial" id="id_filial" class="form-control">
                                        <option></option>
                                        <?php
                                        foreach ($dados_obrigatorios as $conteudo) {
                                            if ($conteudo['chave'] == "filial") {
                                                echo "<option value='" . $conteudo['valor_id'] . "'>" . $conteudo['valor_descricao'] . "</option>";
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Técnico responsável:</label>
                                    <?php
                                    $requer_tecnico = DBRead('', 'tb_integracao_campos_requeridos', "WHERE nome = 'tecnico_responsavel' AND id_contrato_plano_pessoa = '$id_contrato_plano_pessoa' AND requerido = 1");

                                    $required_tecnico = $requer_tecnico ? 'required' : '';
                                    ?>
                                    <select name="tecnico_responsavel" id="tecnico" class="form-control">
                                        <option></option>
                                        <?php
                                        foreach ($dados_obrigatorios as $conteudo) {
                                            if ($conteudo['chave'] == "funcionario") {
                                                echo "<option value='" . $conteudo['valor_id'] . "'>" . $conteudo['valor_descricao'] . "</option>";
                                            }
                                        }
                                        ?>
                                    </select>
                                    <?php

                                    if ($requer_tecnico) {
                                        //echo "<span class='help-block'>Técnico responsável é obrigatório!</span>";
                                    }
                                    ?>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>*Origem do endereço:</label>
                                    <select name="origem_endereco" id="origem" required class="form-control">
                                        <option value="0"></option>
                                        <option value='C'>Cliente</option>
                                        <!-- Se selecionado Login deve-se selecionar o login -->
                                        <option value='L'>Login</option>
                                        <!-- Quando selecionado o contrato deve-se selecionar o contrato -->
                                        <option value='CC'>Contrato</option>
                                        <option value='M'>Manual</option>
                                    </select>
                                    <!-- <span id="help-origem" class="help-block">Origem do endereço obrigatório!</span> -->
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label><span id="asterisco-contrato">*</span>Contrato:</label>
                                    <select name="id_contrato" id="select_contrato" class="form-control">
                                        <option value="0"></option>
                                    </select>
                                </div>
                                <!-- <span id="alerta-contrato" class="text-danger">O campo Contrato é obrigatório!</span> -->
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label><span id="asterisco-login">*</span>Login:</label>
                                    <select name="id_login" id="login" class="form-control">
                                        <option></option>
                                    </select>
                                </div>
                                <!-- <span id="alerta-login" class="text-danger">O campo Login é obrigatório!</span> -->
                            </div>

                            <?php
                            $requer_processo = DBRead('', 'tb_integracao_campos_requeridos', "WHERE nome = 'campo_processo' AND id_contrato_plano_pessoa = '$id_contrato_plano_pessoa' AND requerido = 1");
                            if ($requer_processo) :
                            ?>
                                <div class="col-md-4" id="bloco-processos">
                                    <div class="form-group">
                                        <label id="processo">*Processo:</label>
                                        <select class="form-control processo" name='processo' aria-describedby="help-processo"></select>
                                       <!--  <span id="help-processo" class="help-block">Processo obrigatório!</span> -->
                                    </div>
                                </div>
                            <?php
                            endif;
                            ?>

                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div id="alerta_status_status_internet"></div>
                            </div>
                        </div>

                    </div>
                </div>

                <div id="bloco-evento-integracao" class="row" style="display: none;">
                    <div class="col-md-12" style="margin-bottom: 10px;">
                        <label for="evento">*Evento:</label>
                        <select class="form-control" id="evento" name="evento"></select>
                        <!-- <span id="alerta" class="text-danger">Evento obrigatório!</span> -->
                    </div>
                    <ul class="list-group" style="padding-bottom: 15px;">
                        <div id="bloco-evento-integracao-atendimentos"></div>
                        <div id="bloco-evento-integracao-os"></div>
                    </ul>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <textarea class="form-control" id="textarea-os" disabled required rows="10"><?= $script_atendimento ?></textarea>
                            <input type="hidden" name="os" value="<?= $script_atendimento ?>" />
                        </div>
                    </div>
                </div>

                <input type="hidden" name="id_contrato_plano_pessoa" value="<?= $id_contrato_plano_pessoa ?>" />
                <input type="hidden" name="id_cliente_integracao" id="id_cliente_integracao" value="<?= $id_assinante ?>" />
                <input type="hidden" name="titulo" value="Atendimento Belluno" />
                <input type="hidden" name="id_integracao_atendimento_ixc" value="<?= $id_integracao_atendimento_ixc ?>" />
                <input type="hidden" name="operacao" value="alterar" />

        </div>
        <div class="panel-footer">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <button class="btn btn-primary pull-right" type="submit" id="enviar">Enviar</button>
                    </div>
                </div>
            </div>
        </div>
        </form>
    </div>
</div>

<script>

    $.ajax({
        type: "GET",
        url: "/api/ajax?class=IntegracaoTipoSistemaAjax.php",
        dataType: "json",
        data: {
            acao: "busca_processo",
            id_contrato_plano_pessoa: <?= $id_contrato_plano_pessoa ?>,
            token: '<?= $request->token ?>'
        },
        success: function(data) {

            var processos = `<option value='0'></option>`;
            $.each(data.registros, function(i) {
                processos += `<option value='` + data.registros[i].id + `'>` + data.registros[i].descricao + `</option>`;
            });

            $(".processo").html(processos);
        }
    });

    //Verifica qual a classificação para configurar o código da opção normal em prioridade; em atendimentos deve ser 'M' e em ordens de serviço de ser 'N'
    $("#bloco-processos").hide();
    $("#classificacao").on('change', function() {
        if ($(this).val() == 1) {
            $("#prioridade").html("<option value='0'></option><option value='B'>Baixa</option><option value='N' selected>Normal</option><option value='A'>Alta</option><option value='C'>Crítica</option>");
            $("#bloco-processos").hide();
        } else if ($(this).val() == 2) {
            $("#prioridade").html("<option value='0'></option><option value='B'>Baixa</option><option value='M' selected>Normal</option><option value='A'>Alta</option><option value='C'>Crítica</option>");

            $("#bloco-processos").show();
        }
    });

    //Verifica se o contrato selecionado está com o status inativo e o status da internet está desativado para exibir o alerta ou não
    $("#select_contrato").on("change", function() {
        $('#alerta_status_status_internet').html(``);
        if ($(this).val()) {
            $.ajax({
                type: "GET",
                url: "/api/ajax?class=IntegracaoTipoSistemaAjax.php",
                dataType: "json",
                data: {
                    acao: "busca_contrato_cliente",
                    id_contrato_plano_pessoa: <?= $id_contrato_plano_pessoa ?>,
                    id_contrato: $(this).val(),
                    token: '<?= $request->token ?>'
                },
                success: function(data) {
                    $.each(data.registros, function(i) {
                        if (data.registros[i].status == 'I' && data.registros[i].status_internet == 'D') {
                            $('#alerta_status_status_internet').html(`<div class="alert alert-danger" role="alert">Status do contrato inativo e status da internet desativado, este atendimento não será salvo no sistema de gestão. (selecione outro contrato ou peça ajuda!)</div>`);
                        } else {
                            $('#alerta_status_status_internet').html(``);
                        }
                    });
                }
            });
        } else {
            $('#alerta_status_status_internet').html(``);
        }
    });

    $("#alerta-assunto").hide();

    situacao = <?= $situacao ? $situacao : "4" ?>;
    if (situacao == 4 || situacao == 3) {
        $("#bloco-evento-integracao").hide();
        $("#bloco-integracao").show();
    } else if (situacao == 7) {
        $("#bloco-evento-integracao").show();
        $("#bloco-integracao").hide();
    }

    $("#situacao").on("change", function() {
        if ($(this).val() == 4 || $(this).val() == 3) {
            $("#bloco-evento-integracao").hide();
            $("#bloco-integracao").show();
            situacao = $(this).val();
        } else if ($(this).val() == 7) {
            $("#bloco-evento-integracao").show();
            $("#bloco-integracao").hide();
            situacao = $(this).val();
        }
    });

    $("#classificacao").on("change", function() {
        //ordem de servico
        if ($(this).val() == 1) {
            $("#evento").html("<option value='analise'>Análise</option><option value='finalizacao'>Finalização</option>");
            $("#alerta-assunto").show();
            abreBlocoEventoIntegracao('os');
        } else if ($(this).val() == 2) { //atendimento
            $("#alerta-assunto").hide();
            $("#evento").html("<option value='EP'>Em progresso</option><option value='P'>Pendente</option><option value='S'>Solucionado</option>");
            abreBlocoEventoIntegracao('atendimento');
        }
    });

    //Função responsável por abrir os elementos corretos para cada tipo de classificação, se nos parâmetros estiver configurado que atendimentos vinculados a os já existente for 'sim'
    function abreBlocoEventoIntegracao(classificacao) {

        if (classificacao == 'os') {
            $("#bloco-evento-integracao-atendimentos").html("");
            //Bloco de atendimentos em aberto da aba O.S.
            //Armazena Assuntos
            var assuntos = <?php echo $retorno_assunto ?>;
            $("#evento").html("<option></option><option value='analise'>Análise</option><option value='finalizacao'>Finalização</option>");
            //busca ordens de serviços abertas para a inserção do novo fluxo como uma ação de O.S.
            $.ajax({
                type: "GET",
                url: "/api/ajax?class=IntegracaoTipoSistemaAjax.php",
                dataType: "json",
                data: {
                    acao: "busca_os",
                    id_contrato_plano_pessoa: <?= $id_contrato_plano_pessoa ?>,
                    id_assinante: <?= $id_assinante ?>,
                    token: '<?= $request->token ?>'
                },
                success: function(data) {
                    console.log(data);
                    var os = "";
                    var tem_os = 0;
                    $.each(data.registros, function(i) {
                        if (data.registros[i].status != 'F') {
                            tem_os = 1;
                        }
                    });
                    if (tem_os == 1) {
                        //mini template para montar a ação de O.S. necessária, com seus campos obrigatórios de setor, técnico responsável
                        os = `
                        <div class='col-md-6' style="padding-bottom: 15px;">
                            <label id="setor-departamento">*Setor:</label>
                            <select class='form-control id_setor' name='id_setor'>
                                <option value='0'></option>
                                <?php
                                foreach ($dados_obrigatorios as $conteudo) {
                                    if ($conteudo['chave'] == "setor") {
                                        echo "<option value='" . $conteudo['valor_id'] . "'>" . $conteudo['valor_descricao'] . "</option>";
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <div class='col-md-6' style="padding-bottom: 20px;">
                            <label>*Técnico responsável:</label>
                            <select class='form-control tecnico-responsavel' name='tecnico_responsavel'>
                                <option value='0'></option>
                                <?php
                                foreach ($dados_obrigatorios as $conteudo) {
                                    if ($conteudo['chave'] == "funcionario") {
                                        echo "<option value='" . $conteudo['valor_id'] . "'>" . $conteudo['valor_descricao'] . "</option>";
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    `;
                    } else {
                        os = "Não há ordens de serviço pendentes!";
                        $("#bloco-evento-integracao").css("display", "none");
                    }
                    var status = {
                        "A": "Aberto",
                        "AN": "Análise",
                        "EN": "Encaminhada",
                        "AS": "Assumida",
                        "AG": "Agendado",
                        "EX": "Execução",
                        "F": "Finalizado"
                    }
                    //mini template com todos os atendimentos com status diferente de 'Finalizado' para a vinculação
                    tabela_os = `<div class='row'><div class='col-md-12'><table class="table table-bordered" style="width: 96%;margin: 0 auto;"><thead><tr><th colspan="4">Ordens de serviço pendentes</th></tr></thead><tbody>`;
                    $.each(data.registros, function(i) {
                        if (data.registros[i].status == 'A' || data.registros[i].status == 'AN' || data.registros[i].status == 'EN' || data.registros[i].status == 'AS' || data.registros[i].status == 'AG' || data.registros[i].status == 'EX') {
                            //Itera sobre todos os assuntos cadastrados em Assuntos e compara com o id_assunto de OrdemServico
                            $.each(assuntos.registros, function(indice) {
                                if (assuntos.registros[indice].id == data.registros[i].id_assunto) {
                                    assunto = assuntos.registros[indice].assunto;
                                }
                            });
                            os += `
                            <tr>
                                <td><label style="display: block !important;padding-top: 6px;"><strong>Data abertura:</strong> ` + converteDataHora(data.registros[i].data_abertura) + `</label></td>
                                <td><label style="display: block !important;padding-top: 6px;"><strong>Assunto:</strong> ` + assunto + `</label></td>
                                <td>
                                    <label class='container-radio' style="top: -1px;left: 20px;">
                                        <input type='radio' name='id_atendimento' id='optionsRadios1' value='` + data.registros[i].id + `'>
                                        <span class='checkmark'></span>
                                    </label>
                                </td>
                                <td>
                                    <a tabindex="0" class="btn btn-sm btn-default" role="button" data-toggle="popover" data-trigger="focus" title="Dados sobre a O.S." data-content="<strong>Protocolo:</strong> ` + data.registros[i].protocolo + `<br><strong>Status:</strong> ` + status[data.registros[i].status] + `<br><strong>Mensagem:</strong> ` + nl2br(data.registros[i].mensagem) + `"><i class="fa fa-info" aria-hidden="true"></i></a>
                                </td>
                            </tr>
                        `;
                        }
                        template = `Protocolo:<br>Status:`;
                    });
                    $("#bloco-evento-integracao-os").html(tabela_os + os + "</tbody></table></div></div>");
                    $('[data-toggle="popover"]').popover({
                        html: true,
                        trigger: 'hover'
                    });
                }
            });

        } else if (classificacao == 'atendimento') {
            $("#bloco-evento-integracao-os").html("");
            //Bloco de atendimentos em aberto da aba Atendimento
            //Armazena Assuntos
            var assuntos = <?php echo $retorno_assunto ?>;
            $("#evento").html("<option></option><option value='EP'>Em progresso</option><option value='P'>Pendente</option><option value='S'>Solucionado</option>");
            //busca atendimento
            $.ajax({
                type: "GET",
                url: "/api/ajax?class=IntegracaoTipoSistemaAjax.php",
                dataType: "json",
                data: {
                    acao: "busca_atendimentos",
                    id_contrato_plano_pessoa: <?= $id_contrato_plano_pessoa ?>,
                    id_assinante: <?= $id_assinante ?>,
                    token: '<?= $request->token ?>'
                },
                success: function(data) {
                    var atendimento = "";
                    var tem_atendimento = 0;
                    $.each(data.registros, function(i) {
                        if (data.registros[i].status != 'S' || data.registros[i].status != 'C') {
                            tem_atendimento = 1;
                        }
                    });
                    if (tem_atendimento == 1) {
                        atendimento = `
                        <!--<div class='col-md-6' style="padding-bottom: 15px;">
                            <label>*Departamento:</label>
                            <select class='form-control id_setor' required name='id_setor'>
                                <option value='0'></option>
                                <?php
                                foreach ($dados_obrigatorios as $conteudo) {
                                    if ($conteudo['chave'] == "setor") {
                                        echo "<option value='" . $conteudo['valor_id'] . "'>" . $conteudo['valor_descricao'] . "</option>";
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <div class='col-md-6' style="padding-left: 30px !important;padding-bottom: 20px;">
                            <label>*Técnico responsável:</label>
                            <select class='form-control tecnico-responsavel' name='tecnico_responsavel'>
                                <option value='0'></option>
                                <?php
                                foreach ($dados_obrigatorios as $conteudo) {
                                    if ($conteudo['chave'] == "funcionario") {
                                        echo "<option value='" . $conteudo['valor_id'] . "'>" . $conteudo['valor_descricao'] . "</option>";
                                    }
                                }
                                ?>
                            </select>
                        </div>-->
                    </div>
                    `;
                    } else {
                        atendimento = "Não há atendimentos pendentes!";
                        $("#bloco-evento-integracao").css("display", "none");
                    }
                    var su_status = {
                        "N": "Novo",
                        "P": "Pendente",
                        "EP": "Em progresso",
                        "S": "Solucionado",
                        "C": "Cancelado"
                    }
                    tabela_atendimento = `<div class='row'><div class='col-md-12'><table class="table table-bordered" style="width: 96%;margin: 0 auto;"><thead><tr><th colspan="4">Ordens de serviço pendentes</th></tr></thead><tbody>`;
                    $.each(data.registros, function(i) {
                        if (data.registros[i].su_status == 'N' || data.registros[i].su_status == 'P' || data.registros[i].su_status == 'EP') {
                            //Itera sobre todos os assuntos cadastrados em Assuntos e compara com o id_assunto de OrdemServico
                            $.each(assuntos.registros, function(indice) {
                                if (assuntos.registros[indice].id == data.registros[i].id_assunto && data.registros[i].id_assunto != 0) {
                                    assunto = assuntos.registros[indice].assunto;
                                } else {
                                    assunto = '';
                                }
                            });
                            atendimento += `
                            <tr>
                                <td><label style="display: block !important;padding-top: 6px;"><strong>Data abertura:</strong> ` + converteDataHora(data.registros[i].data_criacao) + `</label></td>
                                <td><label style="display: block !important;padding-top: 6px;"><strong>Assunto:</strong> ` + assunto + `</label></td>
                                <td>
                                    <label class='container-radio' style="top: -1px;left: 20px;">
                                        <input type='radio' name='id_atendimento_sistema_gestao' id='optionsRadios1' value='` + data.registros[i].id + `'>
                                        <span class='checkmark'></span>
                                    </label>
                                </td>
                                <td>
                                    <a tabindex="0" class="btn btn-sm btn-default" role="button" data-toggle="popover" data-trigger="focus" title="Dados sobre a O.S." data-content="<strong>Protocolo:</strong> ` + data.registros[i].protocolo + `<br><strong>Status:</strong> ` + su_status[data.registros[i].su_status] + `<br><strong>Mensagem:</strong> ` + nl2br(data.registros[i].menssagem) + `"><i class="fa fa-info" aria-hidden="true"></i></a>
                                </td>
                            </tr>
                        `;
                        }
                        template = `Protocolo:<br>Status:`;
                    });
                    $("#bloco-evento-integracao-atendimentos").html(tabela_atendimento + atendimento + "</tbody></table></div></div>");
                    $('[data-toggle="popover"]').popover({
                        html: true,
                        trigger: 'hover'
                    });
                }
            });
            //Fim do bloco de atendimentos em aberto da aba Atendimento
        }
    }

    //buscaAssinante();

    //$("#alerta-contrato").hide();
    $("#alerta-login").hide();
    //$("#asterisco-login").hide();
    //$("#asterisco-contrato").hide();
    $("#origem").on("change", function() {
        if ($(this).val() == "CC") {
            //$("#alerta-contrato").show();
            $("#alerta-login").hide();
            //$("#asterisco-contrato").show();
            //$("#asterisco-login").hide();
        } else if ($(this).val() == "L") {
            $("#alerta-login").show();
            //$("#alerta-contrato").show();
            //$("#asterisco-contrato").show();
            //$("#asterisco-login").show();
        } else {
            //$("#alerta-contrato").hide();
            $("#alerta-login").hide();
            //$("#asterisco-login").hide();
            //$("#asterisco-contrato").hide();
        }
    });

    //Verifica qual a classificação para inserir o label correto em Setor
    $("#classificacao").on("change", function() {
        if ($(this).val() == 1) {
            $("#setor-departamento").html("*Setor:");
        } else if ($(this).val() == 2) {
            $("#setor-departamento").html("*Departamento:");
        }
    });

    function obterIdAssinante(nome_assinante) {
        $.ajax({
            type: "GET",
            url: "/api/ajax?class=IntegracaoTipoSistemaAjax.php",
            dataType: "json",
            async: false,
            data: {
                acao: "buscar_assinante",
                id_contrato_plano_pessoa: <?= $id_contrato_plano_pessoa ?>,
                nome_assinante: nome_assinante,
                token: '<?= $request->token ?>'
            },
            success: function(data) {
                console.log(data);
                id_assinante = data.registros[0].id;
            }
        });
        return id_assinante;
    }

    //Busca o contrato do cliente
    var id_assinante = "<?php echo $id_assinante ? $id_assinante : '0' ?>";

    //alert(id_assinante);

    //Se por alguma razão o id_assinante for 0 ou inexistente é feito uma nova requisição utilizando-se o nome do assinante para obter o seu id
    if (id_assinante == 0 || !id_assinante || id_assinante == "") {
        id_assinante = obterIdAssinante($("#nome_assinante").val());
        $("#id_cliente_integracao").val(id_assinante);
    }

    $.ajax({
        type: "GET",
        url: "/api/ajax?class=IntegracaoTipoSistemaAjax.php",
        dataType: "json",
        data: {
            acao: "busca_contrato_cliente_assinante",
            id_contrato_plano_pessoa: <?= $id_contrato_plano_pessoa ?>,
            id_assinante: id_assinante,
            token: '<?= $request->token ?>'
        },
        success: function(data) {
            if (data.registros) {
                var html = '<option value="0"></option>';
                $.each(data.registros, function(i) {
                    select = data.registros[i].id == <?php echo $contrato ? $contrato : '0' ?> ? 'selected' : '';
                    html += "<option " + select + " value='" + data.registros[i].id + "'>" + data.registros[i].contrato + "</option>";
                });
                $('#select_contrato').html(html);
            }
        },
        complete: function() {

            //Verifica se o contrato está com o status inativo e o status da internet está desativado para exibir o alerta ou não
            $.ajax({
                type: "GET",
                url: "/api/ajax?class=IntegracaoTipoSistemaAjax.php",
                dataType: "json",
                data: {
                    acao: "busca_contrato_cliente",
                    id_contrato_plano_pessoa: <?= $id_contrato_plano_pessoa ?>,
                    id_contrato: <?= $contrato ?>,
                    token: '<?= $request->token ?>'
                },
                success: function(data) {
                    $.each(data.registros, function(i) {
                        if (data.registros[i].status == 'I' && data.registros[i].status_internet == 'D') {
                            $('#alerta_status_status_internet').html(`<div class="alert alert-danger" role="alert">Status do contrato inativo e status da internet desativado, este atendimento não será salvo no sistema de gestão. (selecione outro contrato ou peça ajuda!)</div>`);
                        }
                    });
                }
            });

        }
    });

    //Carrega o login de acordo com o contrato
    carregaLogin(<?php echo $contrato ? $contrato : '0' ?>);
    $("#select_contrato").on("change", function() {
        carregaLogin($(this).val());
    });

    function carregaLogin(id_contrato_ixc) {
        $.ajax({
            type: "GET",
            url: "/api/ajax?class=IntegracaoTipoSistemaAjax.php",
            dataType: "json",
            data: {
                acao: "busca_login",
                id_contrato_plano_pessoa: <?= $id_contrato_plano_pessoa ?>,
                id_contrato: id_contrato_ixc,
                token: '<?= $request->token ?>'
            },
            success: function(data) {

                var html = '<option value="0"></option>';
                $.each(data.registros, function(i) {
                    html += "<option value='" + data.registros[i].id + "'>" + data.registros[i].login + "</option>";
                });
                $('#login').html(html);
            }
        });
    }

    $("#data_inicio").val();

    $("#classificacao").on("change", function() {
        if ($(this).val() == "1") {
            $("#bloco-setor-departamento").html("");
            os = `<label id="setor-departamento">Setor:</label>
                <select class='form-control id_setor' name='id_setor'>
                    <option value='0'></option>
                    <?php
                    foreach ($dados_obrigatorios as $conteudo) {
                        if ($conteudo['chave'] == "setor") {
                            echo "<option value='" . $conteudo['valor_id'] . "'>" . $conteudo['valor_descricao'] . "</option>";
                        }
                    }
                    ?>
                </select>`;
            $("#bloco-setor-departamento").html(os);
        } else if ($(this).val() == "2") {
            $("#bloco-setor-departamento").html("");
            atendimento = `<label id="setor-departamento">*Departamento:</label>
                <select class='form-control id_setor' name='id_setor'>
                    <option value='0'></option>
                    <?php
                    foreach ($dados_obrigatorios as $conteudo) {
                        if ($conteudo['chave'] == "departamento") {
                            echo "<option value='" . $conteudo['valor_id'] . "'>" . $conteudo['valor_descricao'] . "</option>";
                        }
                    }
                    ?>
                </select>`;
            $("#bloco-setor-departamento").html(atendimento);
        }
    });
</script>

<script>
    /* $('#pendentes_form').on('submit', function() {
     
        modalAguarde();

        classificacao = $( "#classificacao option:selected" ).text();
        assunto = $( "#id_assunto option:selected" ).text();
        prioridade = $( "#prioridade option:selected" ).text();
        setor = $( "select[name=id_setor] option:selected" ).text();
        filial = $( "#filial option:selected" ).text();
        tecnico = $( "#tecnico option:selected" ).text();
        origem = $( "#origem option:selected" ).text();
        contrato = $( "#select_contrato option:selected" ).text();
        login = $( "#login option:selected" ).text();
        processo = $( ".processo option:selected" ).text();

        //atendimento vinculado a OS ja existente
        evento = $( "#evento option:selected" ).text();
        //classificacao_evento = $( "#classificacao_evento option:selected" ).text();
        tecnico_responsavel = $( "select[name=tecnico_responsavel] option:selected" ).text();
        id_os = $('input[name=id_atendimento]:checked', '#atendimento_form').val();

        id_atendimento = '<?php echo $id_atendimento ?>';

        if (classificacao != '' || assunto !='' || prioridade !='' || setor !='' || filial !='' || tecnico !='' || origem !='' || contrato !='' || login !='' || processo !='' || classificacao_evento !='' || evento !='' || tecnico_responsavel !='' || id_os != '') {

            $.ajax({
                type: "GET",
                url: "class/IntegracaoCamposDefault.php",
                dataType: "json",
                data: {
                    acao: "salva_campos_atendimento",
                    classificacao: classificacao,
                    assunto: assunto,
                    setor: setor,
                    filial: filial,
                    tecnico: tecnico,
                    processo: processo,
                    prioridade: prioridade,
                    origem: origem,
                    contrato: contrato,
                    login: login, 
                    classificacao_evento: classificacao_evento,
                    evento: evento,
                    tecnico_responsavel: tecnico_responsavel,
                    id_os: id_os,
                    id_atendimento: id_atendimento,
                    flag_pendencia: 1
                },
                success: function(data) {
                    console.log(data);
                }
            });
        }
		
    }); */

    $("#enviar").on("click", function(){

        modalAguarde();

        classificacao = $( "#classificacao option:selected" ).text();
        assunto = $( "#id_assunto option:selected" ).text();
        prioridade = $( "#prioridade option:selected" ).text();
        setor = $( "select[name=id_setor] option:selected" ).text();
        filial = $( "#id_filial option:selected" ).text();
        tecnico = $( "#tecnico option:selected" ).text();
        origem = $( "#origem option:selected" ).text();
        contrato = $( "#select_contrato option:selected" ).text();
        login = $( "#login option:selected" ).text();
        processo = $( ".processo option:selected" ).text();

        //atendimento vinculado a OS ja existente
        evento = $( "#evento option:selected" ).text();
        tecnico_responsavel = $( "select[name=tecnico_responsavel] option:selected" ).text();
        id_os = $('input[name=id_atendimento]:checked', '#pendentes_form').val();

        id_atendimento = '<?php echo $id_atendimento ?>';

        if (classificacao != '' || assunto !='' || prioridade !='' || setor !='' || filial !='' || tecnico !='' || origem !='' || contrato !='' || login !='' || processo !='' || evento !='' || tecnico_responsavel !='' || id_os != '') {

            $.ajax({
                type: "GET",
                url: "/api/ajax?class=IntegracaoCamposDefault.php",
                dataType: "json",
                data: {
                    acao: "salva_campos_atendimento",
                    classificacao: classificacao,
                    assunto: assunto,
                    setor: setor,
                    filial: filial,
                    tecnico: tecnico,
                    processo: processo,
                    prioridade: prioridade,
                    origem: origem,
                    contrato: contrato,
                    login: login, 
                    evento: evento,
                    tecnico_responsavel: tecnico_responsavel,
                    id_os: id_os,
                    id_atendimento: id_atendimento,
                    flag_pendencia: 1,
                    token: '<?= $request->token ?>'
                },
                success: function(data) {
                    console.log(data);
                }
            });
        }
        
    });
</script>

<style>
    .popover {
        width: 550px;
    }
</style>