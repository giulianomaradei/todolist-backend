<?php

$dados_obrigatorios = DBRead('', 'tb_dados_obrigatorios_integracao', "WHERE id_integracao = 1 AND id_contrato_plano_pessoa = '$id_contrato_plano_pessoa'");

/** Bloco que gera o protocolo do atendimento no sistema ixc */
//Criar código que gera protocolo na tela de envio de atendimentos que não tenham sido enviados automaticamente
require_once "class/integracoes/ixc/Parametros.php";
require_once "class/integracoes/ixc/WebServiceClient.php";
require_once "class/integracoes/ixc/Cliente.php";
require_once "class/integracoes/ixc/Atendimento.php";
require_once "class/integracoes/ixc/Usuarios.php";

$parametros_ixc = new Integracao\Ixc\Parametros();
$parametros_ixc->setParametros($id_contrato_plano_pessoa);
$api = new IXCsoft\WebserviceClient($parametros_ixc->getHost(), $parametros_ixc->getToken(), $parametros_ixc->getSelfSigned());

//gera protocolo
$api->get('gerar_protocolo_atendimento');
$retorno_protocolo = $api->getRespostaConteudo(true);

//Busca id de não clientes para armazenar em id_cliente
$nao_cliente = DBRead('', 'tb_informacao_geral_contrato', "WHERE id_contrato_plano_pessoa = '$id_contrato_plano_pessoa'", "nao_cliente");
$nome_assinante = $nao_cliente[0]['nao_cliente'];

$cliente = new Integracao\Ixc\Cliente();
$retorno_cliente = $cliente->get('cliente.razao', $nome_assinante, 'L', '1', '20000', 'cliente.id', 'desc', true, $id_contrato_plano_pessoa);

?>

<style>
    .span-container-radio {
        font-size: 18px;
    }

    .container-radio {
        display: block;
        position: relative;
        padding-left: 35px;
        margin-bottom: 12px;
        cursor: pointer;
        font-size: 22px;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
    }

    .container-radio input {
        position: absolute;
        opacity: 0;
        cursor: pointer;
        height: 0;
        width: 0;
    }

    .checkmark {
        position: absolute;
        top: 0;
        left: 0;
        height: 22px;
        width: 22px;
        background-color: #fff;
        border: 1px solid #cccccc;
        border-radius: 50%;
        margin-top: 6px;
    }

    .container-radio:hover input~.checkmark {
        background-color: #ccc;
    }

    .container-radio input:checked~.checkmark {
        background-color: #818181;
    }

    .checkmark:after {
        content: "";
        position: absolute;
        display: none;
    }

    .container-radio input:checked~.checkmark:after {
        display: block;
    }

    .container-radio .checkmark:after {
        top: 7px;
        left: 7px;
        width: 7px;
        height: 7px;
        border-radius: 50%;
        background: white;
    }

    .help-block {
        color: #a94442;
    }
</style>

<div class="alert opcoes-sistema-gestao" role="alert" style="margin-top: 20px; background-color: #f5f5f5; border: 1px solid #ccc; color: #000; padding-bottom: 75px;">
    <fieldset>
        <legend style="color: #000">
            <h4>Opções do sistema de gestão</h4>
        </legend>
        <div class="quadro-sistema-gestao">

            <div id="parametros-integracao">

                <input type="hidden" name="data_inicio" id="data-inicial" />
                <input type="hidden" name="id_cliente_integracao" value="<?= $retorno_cliente['registros'][0]['id'] ?>" />
                <input type="hidden" name="assinante" value="<?= $retorno_cliente['registros'][0]['razao'] ?>" />

                <div class="alert alert-warning" role="alert"><span>Obs.:<br> - Classificação corresponde se o atendimento deve ser salvo na aba Atendimento ou O.S. (Ordem de serviço);<br> - Para selecionar o login, antes é necessário selecionar o contrato.</span></div>

                <input type="hidden" name="atendimento_pendente" value="1" />

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Protocolo integração:</label>
                            <input type="text" disabled class="form-control" value="<?= $retorno_protocolo ?>" id="protocolo_integracao" />
                            <input type="hidden" value="<?= $retorno_protocolo ?>" name="protocolo_integracao_integracao" />
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label>*Classificação:</label>
                            <select class="form-control" name='classificacao' id='classificacao' aria-describedby="help-classificacao" required>
                                <option value="0"></option>
                                <option value="1">Ordem de serviço</option>
                                <option value="2">Atendimento</option>
                            </select>
                            <!--<span id="alerta" class="text-danger">Classificação obrigatório!</span>-->
                            <span id="help-classificacao" class="help-block">Classificação obrigatório!</span>
                        </div>
                    </div>

                    <?php
                    /*echo "<pre>";
                        var_dump($dados_obrigatorios);
                        echo "</pre>";*/
                    ?>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label><span id="asterisco-assunto">*</span>Assunto:</label>
                            <select class="form-control" name='id_assunto' id='assunto'>
                                <option value="0"></option>
                                <?php
                                foreach ($dados_obrigatorios as $conteudo) {

                                    if ($conteudo['chave'] == "assunto") {
                                        echo "<option value='" . $conteudo['valor_id'] . "'>" . $conteudo['valor_id'] . " - " . $conteudo['valor_descricao'] . "</option>";
                                    }
                                }
                                ?>
                            </select>
                            <span id="alerta-assunto" class="text-danger">Assunto obrigatório!</span>
                        </div>
                    </div>
                </div>

                <div class="row">

                    <div class="col-md-4">
                        <div class="form-group">
                            <label id="setor-departamento">*Setor:</label>
                            <select class="form-control setor" name='id_setor' aria-describedby="help-departamento"></select>
                            <span id="help-departamento" class="help-block">Setor obrigatório!</span>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Filial:</label>
                            <select class="form-control" name='id_filial' id='filial'>
                                <option value="0"></option>
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
                            <select class="form-control" name="tecnico_responsavel" id='tecnico'>
                                <option value="0"></option>
                                <?php
                                foreach ($dados_obrigatorios as $conteudo) {
                                    if ($conteudo['chave'] == "funcionario") {
                                        echo "<option value='" . $conteudo['valor_id'] . "'>" . $conteudo['valor_descricao'] . "</option>";
                                    }
                                }
                                ?>
                            </select>
                            <?php
                            $requer_tecnico = DBRead('', 'tb_integracao_campos_requeridos', "WHERE nome = 'tecnico_responsavel' AND id_contrato_plano_pessoa = '$id_contrato_plano_pessoa' AND requerido = 1");
                            if ($requer_tecnico) {
                                echo "<span class='help-block'>Técnico responsável é obrigatório!</span>";
                            }
                            ?>
                        </div>
                    </div>

                </div>

                <div class="row">

                    <div class="col-md-4">
                        <div class="form-group">
                            <label>*Origem do endereço:</label>
                            <select class="form-control" name='origem_endereco' id='origem' aria-describedby="help-origem">
                                <option value="0"></option>
                                <option value='C'>Cliente</option>
                                <option value='L'>Login</option>
                                <option value='CC'>Contrato</option>
                                <option value='M'>Manual</option>
                            </select>
                            <span id="help-origem" class="help-block">Origem do endereço obrigatório!</span>
                        </div>
                    </div>

                    <?php
                    $requer_processo = DBRead('', 'tb_integracao_campos_requeridos', "WHERE nome = 'campo_processo' AND id_contrato_plano_pessoa = '$id_contrato_plano_pessoa' AND requerido = 1");
                    if ($requer_processo) :
                    ?>
                        <div id="requer-processo"></div>
                    <?php
                    endif;
                    ?>

                </div>

            </div>

        </div>
    </fieldset>
</div>

<script>
    <?php
    if ($requer_processo) :
    ?>

        var requer_processo = `
            <div class="col-md-4">
                <div class="form-group">
                    <label id="processo">*Processo:</label>
                    <select class="form-control processo" name='processo' aria-describedby="help-processo"></select>
                    <span id="help-processo" class="help-block">Processo obrigatório!</span>
                </div>
            </div>`;

        $("#requer-processo").html(requer_processo);

        $.ajax({
            type: "GET",
            url: "class/IntegracaoTipoSistemaAjax.php",
            dataType: "json",
            data: {
                acao: "busca_processo",
                id_contrato_plano_pessoa: <?= $id_contrato_plano_pessoa ?>
            },
            success: function(data) {

                var processos = `<option value='0'></option>`;
                $.each(data.registros, function() {
                    processos += `<option value='` + data.registros[0].id + `'>` + data.registros[0].descricao + `</option>`;
                });

                $(".processo").html(processos);
            }
        });
    <?php
    endif;
    ?>



    $("#classificacao").on('change', function() {

        let classificacao = $(this).val();

        $("#assunto").val(0);
        $(".setor").val(0);
        $("#filial").val(0);
        $("#tecnico").val(0);
        $("#origem").val(0);
        $(".processo").val(0);
        $(".setor").val(0);

        $.ajax({
            type: "GET",
            url: "class/IntegracaoCamposDefault.php",
            dataType: "json",
            data: {
                acao: "busca_valores",
                id_contrato_plano_pessoa: <?= $id_contrato_plano_pessoa ?>,
                classificacao: classificacao
            },
            success: function(data) {
                $.each(data, function(i) {
                    if (data[i].codigo_campo == 'assunto') {
                        $("#assunto").val(data[i].value_default);
                    }
                    if (data[i].codigo_campo == 'departamento') {
                        $(".setor").val(data[i].value_default);
                    }
                    if (data[i].codigo_campo == 'filial') {
                        $("#filial").val(data[i].value_default);
                    }
                    if (data[i].codigo_campo == 'tecnico') {
                        $("#tecnico").val(data[i].value_default);
                    }
                    if (data[i].codigo_campo == 'origem') {
                        $("#origem").val(data[i].value_default);
                    }
                    if (data[i].codigo_campo == 'processo') {
                        $(".processo").val(data[i].value_default);
                    }
                    if (data[i].codigo_campo == 'setor') {
                        $(".setor").val(data[i].value_default);
                    }
                });
            }
        });

        if ($(this).val() == 1) {
            $("#prioridade").html("<option value='0'></option><option value='B'>Baixa</option><option value='N' selected>Normal</option><option value='A'>Alta</option><option value='C'>Crítica</option>");

            $("#requer-processo").children().hide();

        } else if ($(this).val() == 2) {
            $("#prioridade").html("<option value='0'></option><option value='B'>Baixa</option><option value='M' selected>Normal</option><option value='A'>Alta</option><option value='C'>Crítica</option>");

            $("#requer-processo").children().show();
        }
    });

    $("#alerta-contrato").hide();
    $("#alerta-login").hide();
    $("#asterisco-login").hide();
    $("#asterisco-contrato").hide();
    $("#origem").on("change", function() {
        if ($(this).val() == "CC") {
            $("#alerta-contrato").show();
            $("#alerta-login").hide();
            $("#asterisco-contrato").show();
            $("#asterisco-login").hide();
        } else if ($(this).val() == "L") {
            $("#alerta-login").show();
            $("#alerta-contrato").show();
            $("#asterisco-contrato").show();
            $("#asterisco-login").show();
        } else {
            $("#alerta-contrato").hide();
            $("#alerta-login").hide();
            $("#asterisco-login").hide();
            $("#asterisco-contrato").hide();
        }
    });
    //Função responsável por trocar a label, se classificação for ordem de serviço é necessário inserir setor e o mesmo se torna obrigatório, se a classificação for atendimento é necessário o departamento não sendo obrigatório o seu preenchimento
    $("#classificacao").on("change", function() {
        if ($(this).val() == 1) {
            $("#setor-departamento").html("*Setor:");
            $("#asterisco-assunto").show();
            $("#alerta-assunto").show();
        } else if ($(this).val() == 2) {
            $("#setor-departamento").html("*Departamento:");
            $("#asterisco-assunto").hide();
            $("#alerta-assunto").hide();
        } else {
            $("#asterisco-assunto").hide();
            $("#alerta-assunto").hide();
        }
    });

    //Lista setor/departamento de acordo com a classificação - A situação é sempre encaminhado
    $("#classificacao").on("change", function() {
        $(".setor option").remove();
        classificacao = $(this).val();
        $.ajax({
            type: "GET",
            url: "class/IntegracaoTipoSistemaAjax.php",
            dataType: "json",
            data: {
                acao: "verifica_situacao",
                id_contrato_plano_pessoa: <?= $id_contrato_plano_pessoa ?>,
                classificacao: classificacao,
                situacao: 4
            },
            success: function(data) {
                $(".setor").append(data);
            },
            error: function() {
                $(".opcoes-sistema-gestao").css({
                    "display": "none"
                });
            }
        });
    });
</script>