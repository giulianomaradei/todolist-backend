    <?php
    $dados_obrigatorios = DBRead('', 'tb_dados_obrigatorios_integracao', "WHERE id_integracao = 1 AND id_contrato_plano_pessoa = '$id_contrato_plano_pessoa'");

    //echo 'atendimento-form-arvore-ixc';
    //var_dump($dados_obrigatorios);
    /** Bloco que gera o protocolo do atendimento no sistema ixc */
    //Criar código que gera protocolo na tela de envio de atendimentos que não tenham sido enviados automaticamente
    require_once "class/integracoes/ixc/Parametros.php";
    require_once "class/integracoes/ixc/WebServiceClient.php";

    $parametros = new Integracao\Ixc\Parametros();
    $parametros->setParametros($id_contrato_plano_pessoa);
    $api = new IXCsoft\WebserviceClient($parametros->getHost(), $parametros->getToken(), $parametros->getSelfSigned());

    $api->get('gerar_protocolo_atendimento');
    $retorno_protocolo = $api->getRespostaConteudo(true);

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
    <hr>

    <hr>

    <div id="parametros-integracao" style="display: none;">

        <input type="hidden" name="data_inicio" id="data-inicial" />

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

            <div class="col-md-4">
                <div class="form-group">
                    <label><span id="asterisco-assunto">*</span>Assunto:</label>
                    <select class="form-control" name='id_assunto' id='assunto'>
                        <option></option>
                        <?php
                        foreach ($dados_obrigatorios as $conteudo) {
                            if ($conteudo['chave'] == "assunto") {
                                echo "<option value='" . $conteudo['valor_id'] . "'>" . $conteudo['valor_id'] . " - " . $conteudo['valor_descricao'] . "</option>";
                            }
                        }
                        ?>
                    </select>
                    <!-- <span id="alerta-assunto" class="text-danger">Assunto obrigatório!</span> -->
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label>*Prioridade:</label>
                    <select class="form-control" name='prioridade' id='prioridade' aria-describedby="help-prioridade">
                        <option value=""></option>
                        <option value="B">Baixa</option>
                        <option value="N">Normal</option>
                        <option value="A">Alta</option>
                        <option value="C">Crítica</option>
                    </select>
                    <!-- <span id="help-prioridade" class="help-block">Prioridade obrigatório!</span> -->
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    <label id="setor-departamento">*Setor:</label>
                    <select class="form-control setor" name='id_setor' aria-describedby="help-departamento"></select>
                    <!-- <span id="help-departamento" class="help-block">Setor obrigatório!</span> -->
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    <label>Filial:</label>
                    <select class="form-control" name='id_filial' id='filial'>
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
        </div>

        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label>Técnico responsável:</label>
                    <select class="form-control" name="tecnico_responsavel" id='tecnico'>
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
                    $requer_tecnico = DBRead('', 'tb_integracao_campos_requeridos', "WHERE nome = 'tecnico_responsavel' AND id_contrato_plano_pessoa = '$id_contrato_plano_pessoa' AND requerido = 1");
                    if ($requer_tecnico) {
                        //echo "<span class='help-block'>Técnico responsável é obrigatório!</span>";
                    }
                    ?>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label>*Origem do endereço:</label>
                    <select class="form-control" name='origem_endereco' id='origem' aria-describedby="help-origem">
                        <option value=""></option>
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
            <!-- Buscar direto da API -->
            <div class="col-md-4" id="bloco-contrato-ixc">
                <div class="form-group">
                    <label><span id="asterisco-contrato">*</span>Contrato:</label>
                    <select class="form-control" name="id_contrato" id='select_contrato' aria-describedby="help-contrato">
                        <option></option>
                    </select>
                    <!-- <span id="alerta-contrato" class="text-danger">Contrato obrigatório!</span> -->
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Buscar direto da API -->
            <div class="col-md-4" id="bloco-login">
                <div class="form-group">
                    <label><span id="asterisco-login">*</span>Login:</label>
                    <select class="form-control" name="id_login" id='login' aria-describedby="help-login">
                        <option value="0"></option>
                    </select>
                    <!-- <span id="alerta-login" class="text-danger">Login obrigatório!</span><br> -->
                    <span id="alerta-login" class="text-warning">Para listar login é necessário selecionar o contrato antes!</span>
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

        <div id="alerta_status_status_internet"></div>

        <?php
        if (!$dados_arvore) {
            $id_subarea_problema = $dados_valida_arvore[0]['id_subarea_problema'];
        }
        ?>

    </div>

    <script>
        <?php
        if ($requer_processo) :
        ?>
            var requer_processo = `
            <div class="col-md-4">
                <div class="form-group">
                    <label id="processo">*Processo:</label>
                    <select class="form-control processo" id='processo' name='processo' aria-describedby="help-processo"></select>
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

                    var processos = `<option></option>`;
                    $.each(data.registros, function(i) {
                        processos += `<option value='` + data.registros[i].id + `'>` + data.registros[i].descricao + `</option>`;
                    });
                    $(".processo").html(processos);
                }
            });
        <?php
        endif;
        ?>

        //Função que altera o valor de Normal em prioridade para N em ordem de serviço, M em atendimento e mostra os processos caso a classificação seja atendimento
        function trocaClassificacao(classificacao) {

            if (classificacao == 1) {
                $("#prioridade").html("<option value=''></option><option value='B'>Baixa</option><option value='N'>Normal</option><option value='A'>Alta</option><option value='C'>Crítica</option>");
                $("#requer-processo").children().hide();

                $("#setor-departamento").html("*Setor:");
                //$("#asterisco-assunto").show();
                //$("#alerta-assunto").show();

                if (sessionStorage.getItem("prioridade_status")) {
                    $("#prioridade").val(sessionStorage.getItem("prioridade_status"));
                    sessionStorage.removeItem('prioridade_status');
                }

            } else if (classificacao == 2) {
                $("#prioridade").html("<option value=''></option><option value='B'>Baixa</option><option value='M'>Normal</option><option value='A'>Alta</option><option value='C'>Crítica</option>");
                $("#requer-processo").children().show();

                $("#setor-departamento").html("*Departamento:");
                //$("#asterisco-assunto").hide();
                //$("#alerta-assunto").hide();

                if (sessionStorage.getItem("prioridade_status")) {
                    $("#prioridade").val(sessionStorage.getItem("prioridade_status"));
                    sessionStorage.removeItem('prioridade_status');
                }

            } else {
                //$("#asterisco-assunto").hide();
                //$("#alerta-assunto").hide();
            }
        }

        <?php
        if ($id_subarea_problema) :
        ?>
            // teste default
            $("#select-situacao").on('change', function() {
                let situacao = $(this).val();
                $.ajax({
                    type: "GET",
                    url: "class/IntegracaoCamposDefault.php",
                    dataType: "json",
                    data: {
                        acao: "busca_valores",
                        id_contrato_plano_pessoa: <?= $id_contrato_plano_pessoa ?>,
                        id_subarea_problema: <?= $id_subarea_problema ?>
                    },
                    success: function(data) {
                        $.each(data, function(i) {
                            if (data[i].codigo_campo == 'classificacao') {
                                let classificacao = data[i].value_default;
                                $("#classificacao").val(classificacao);
                                trocaClassificacao(classificacao);
                                verificaSituacao(classificacao, situacao);
                            }
                            if (data[i].codigo_campo == 'assunto') {
                                $("#assunto").val(data[i].value_default);
                            }
                            if (data[i].codigo_campo == 'setor') {
                                sessionStorage.setItem("id_setor_default", data[i].value_default);
                            }
                            if (data[i].codigo_campo == 'filial') {
                                $("#filial").val(data[i].value_default);
                            }
                            if (data[i].codigo_campo == 'tecnico') {
                                $("#tecnico").val(data[i].value_default);
                            }
                            if (data[i].codigo_campo == 'departamento') {
                                sessionStorage.setItem("id_setor_default", data[i].value_default);
                            }
                            if (data[i].codigo_campo == 'processo') {
                                $(".processo").val(data[i].value_default);
                            }
                            if (data[i].codigo_campo == 'prioridade') {
                                sessionStorage.setItem("prioridade_status", data[i].value_default);
                            }
                            if (data[i].codigo_campo == 'origem') {
                                $("#origem").val(data[i].value_default);
                            }
                        });
                    }
                });
            });
        <?php endif; ?>

        //Esconde processo em onload da página
        $("#requer-processo").children().hide();
        //Verifica qual a classificação para configurar o código da opção normal em prioridade; em atendimentos deve ser 'M' e em ordens de serviço de ser 'N'
        $("#classificacao").on('change', function() {
            let classificacao = $(this).val();
            trocaClassificacao(classificacao);
        });

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
    </script>

    <!-- Bloco utilizado somente quando a situação for atendimento vinculado a os já existente e estiver configurado esse recurso no quadro informativo. -->
    <div class="col-md-6 classificacao-evento" style="margin-bottom: 10px;display: none;">
        <label for="classificacao_evento">*Classificação:</label>
        <select class="form-control" id="classificacao_evento" name="classificacao_evento">
            <option value="0"></option>
            <option value="1">Ordem de serviço</option>
            <option value="2">Atendimento</option>
        </select>
        <span id="alerta" class="text-danger">Classificação obrigatório!</span>
    </div>
    <div id="bloco-evento-integracao" style="display: none;">
        <div class="col-md-6" style="margin-bottom: 10px;">
            <label for="evento">*Evento:</label>
            <select class="form-control" id="evento" name="evento"></select>
            <!-- <span id="alerta" class="text-danger">Evento obrigatório!</span> -->
        </div>
        <ul class="list-group" style="padding-bottom: 15px;">
            <div id="bloco-evento-integracao-atendimentos"></div>
            <div id="bloco-evento-integracao-os"></div>
        </ul>
    </div>
    </div>

    <script>
        $("#titulo").val(sessionStorage.getItem("assunto"));
        $("#data-inicial").val(sessionStorage.getItem("data_inicial"));

        //$("#asterisco-assunto").hide();
        //$("#alerta-assunto").hide();
        //Função responsável por trocar a label, se classificação for ordem de serviço é necessário inserir setor e o mesmo se torna obrigatório, se a classificação for atendimento é necessário o departamento não sendo obrigatório o seu preenchimento
        /*$("#classificacao").on("change", function() {
            if ($(this).val() == 1) {

            } else if ($(this).val() == 2) {

            } else {
                
            }
        });*/

        //Busca todos os contratos do cliente que está sendo atendimento e lista os mesmos no select de contrato
        $.ajax({
            type: "GET",
            url: "class/IntegracaoTipoSistemaAjax.php",
            dataType: "json",
            data: {
                acao: "busca_contrato_cliente_assinante",
                id_contrato_plano_pessoa: <?= $id_contrato_plano_pessoa ?>,
                id_assinante: sessionStorage.getItem('id_assinante')
            },
            success: function(data) {
                var html = '<option></option>';
                $.each(data.registros, function(i) {
                    select = data.registros[i].id == sessionStorage.getItem('contrato') ? 'selected' : '';
                    html += "<option " + select + " value='" + data.registros[i].id + "'>" + data.registros[i].contrato + "</option>";
                });
                $('#select_contrato').html(html);
            }
        });

        //Função que carrega o(s) login(s) de cada contrato a cada alteração do mesmo no select de contrato
        if (sessionStorage.getItem('contrato') != '' && sessionStorage.getItem('contrato') != '0') {
            carregaLogin(sessionStorage.getItem('contrato'));
        }

        $.ajax({
            type: "GET",
            url: "class/IntegracaoTipoSistemaAjax.php",
            dataType: "json",
            data: {
                acao: "busca_contrato_cliente",
                id_contrato_plano_pessoa: <?= $id_contrato_plano_pessoa ?>,
                id_contrato: sessionStorage.getItem('contrato')
            },
            success: function(data) {
                $.each(data.registros, function(i) {
                    if (data.registros[i].status == 'I' && data.registros[i].status_internet == 'D') {
                        $('#alerta_status_status_internet').html(`<div class="alert alert-danger" role="alert">Status do contrato inativo e status da internet desativado, este atendimento não será salvo no sistema de gestão. (selecione outro contrato ou peça ajuda!)</div>`);
                    }
                });
            }
        });
        $("#select_contrato").on("change", function() {

            if ($(this).val()) {
                $.ajax({
                    type: "GET",
                    url: "class/IntegracaoTipoSistemaAjax.php",
                    dataType: "json",
                    data: {
                        acao: "busca_contrato_cliente",
                        id_contrato_plano_pessoa: <?= $id_contrato_plano_pessoa ?>,
                        id_contrato: $(this).val()
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

            carregaLogin($(this).val());
        });

        // Busca o(s) login(s) para os preencher no select de logins - Obs: isso somente após a seleção do contrato
        function carregaLogin(id_contrato_ixc) {

            if (id_contrato_ixc) {
                $.ajax({
                    type: "GET",
                    url: "class/IntegracaoTipoSistemaAjax.php",
                    dataType: "json",
                    data: {
                        acao: "busca_login",
                        id_contrato_plano_pessoa: <?= $id_contrato_plano_pessoa ?>,
                        id_contrato: id_contrato_ixc
                    },
                    success: function(data) {
                        var html = '<option></option>';
                        $.each(data.registros, function(i) {
                            select = data.registros[i].login == sessionStorage.getItem('login') ? 'selected' : '';
                            html += "<option " + select + " value='" + data.registros[i].id + "'>" + data.registros[i].login + "</option>";
                        });
                        $('#login').html(html);
                    }
                });
            }

        }

        $("#data_inicio").val(sessionStorage.getItem("data_inicial"));

        $("#select-situacao").on("change", function() {
            let situacao = $(this).val();
            listaDepartamentoSetor(situacao);
        });

        function verificaSituacao(classificacao, situacao) {
            $.ajax({
                type: "GET",
                url: "class/IntegracaoTipoSistemaAjax.php",
                dataType: "json",
                data: {
                    acao: "verifica_situacao",
                    id_contrato_plano_pessoa: <?= $id_contrato_plano_pessoa ?>,
                    classificacao: classificacao,
                    situacao: situacao,
                    id_setor_default: sessionStorage.getItem('id_setor_default')
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
        }

        function listaDepartamentoSetor(situacao) {
            //Seleciona a situação e verifica se campo select com classe setor irá apresentar os dados do recurso Setor ou os dados do recurso DepartamentoAtendimento
            $(".setor option").remove();
            //situacao = $(this).val();
            $(".opcoes-sistema-gestao").show();
            $("#classificacao").on("change", function() {
                $(".setor option").remove();
                classificacao = $(this).val();
                verificaSituacao(classificacao, situacao);
            });
        }

        <?php

        //Verifica nos parâmetros do quadro informativo se determinado provedor está configurado para inserir ação de interação em casos de ordens de serviços que se tratem do mesmo assunto de uma ordem de serviço anterior ou não (Atendimento vinculado a OS já existente), se for selecionado a opção ordem de serviço em classificação;
        //Da mesma forma é verificado se é necessário adicionar uma mensagem e sua respectiva alteração de status, se for selecionado atendimento em classificação
        //Com essa consulta também é verificado o que o sistema deve fazer no caso em que a situação seja "Atendimento encerrado", nesse caso o sistema vai abrir uma nova ordem de serviço e inserir uma ação de finalização logo em seguida ou inserir um atendimento e inserir uma mensagem de solucionado em seguida, dependendo do que foi selecionado em classificação, mas isso não muda nada na finalização de um atendimento comum
        $parametros = DBRead('', 'tb_integracao_valores_tipo_parametro a', "INNER JOIN tb_integracao_parametro b ON a.id_integracao_parametro = b.id_integracao_parametro INNER JOIN tb_integracao_contrato_parametro c ON b.id_integracao_parametro = c.id_integracao_parametro INNER JOIN tb_integracao_contrato d ON c.id_integracao_contrato = d.id_integracao_contrato WHERE d.id_contrato_plano_pessoa = '$id_contrato_plano_pessoa'");

        //Variável que indica se um atendimento ou ordem de serviço está configurado para o atendente inserir uma interação de atendimento ou ordem de serviço em casos de atendimentos vinculados a os já existente
        $salvaEmOS = "'nao'"; //É inserido um novo atendimento ou nova O.S.
        if ($parametros) {
            foreach ($parametros as $parametro) {
                if ($parametro['codigo'] == "cadastroAtendimentoVinculado" && $parametro['valor'] == "sim") {
                    $salvaEmOS = "'sim'"; //É inserido uma interação de atendimento ou O.S., em atendimento é inserido uma mensagem, em O.S. é inserido uma ação de O.S.
                }
            }
        }
        ?>

        salvaEmOS = <?= $salvaEmOS ?>;

        $("#select-situacao").on("change", function() {

            //Opção 7 - ATENDIMENTO VINCULADO A OS JÁ EXISTENTE
            if ($(this).val() == 7 && salvaEmOS == 'sim') {
                $("#bloco-evento-integracao").css("display", "block");
                $(".classificacao-evento").css("display", "block");
                $("#parametros-integracao").css("display", "none");

                $("#classificacao_evento").on("change", function() {
                    if ($(this).val() == 1) {
                        abreBlocoEventoIntegracao('os');
                        $("#setor-departamento").html("*Setor:");
                        $(".classificacao-evento").css("display", "block");
                        $("#bloco-evento-integracao").css("display", "block");
                    } else if ($(this).val() == 2) {
                        abreBlocoEventoIntegracao('atendimento');
                        $(".classificacao-evento").css("display", "block");
                        $("#bloco-evento-integracao").css("display", "block");
                    }
                });

            } else if ($(this).val() == 4 || $(this).val() == 3 || ($(this).val() == 7 && salvaEmOS == 'nao')) {
                $("#bloco-evento-integracao").css("display", "none");
                $(".classificacao-evento").css("display", "none");
                $("#parametros-integracao").css("display", "block");
            } else {
                $('#novo-atendimento').css("display", "none");
                $('#atendimento-ja-existente').css("display", "none");
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
                    url: "class/IntegracaoTipoSistemaAjax.php",
                    dataType: "json",
                    data: {
                        acao: "busca_os",
                        id_contrato_plano_pessoa: <?= $id_contrato_plano_pessoa ?>,
                        id_assinante: sessionStorage.getItem('id_assinante')
                    },
                    success: function(data) {
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
                                <option></option>
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
                                <option></option>
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
                        tabela_os = `<div class='row'><div class='col-md-12'><table class="table table-bordered" style="width: 96%; margin: 0 auto;"><thead><tr><th colspan="4">Ordens de serviço pendentes</th></tr></thead><tbody>`;
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
                                        <input type='radio' name='id_atendimento_sistema_gestao' id='optionsRadios1' value='` + data.registros[i].id + `'>
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
                    url: "class/IntegracaoTipoSistemaAjax.php",
                    dataType: "json",
                    data: {
                        acao: "busca_atendimentos",
                        id_contrato_plano_pessoa: <?= $id_contrato_plano_pessoa ?>,
                        id_assinante: sessionStorage.getItem('id_assinante')
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
                            <select class='form-control id_setor' name='id_setor'>
                                <option></option>
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
                                <option></option>
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
    </script>
    <style>
        .popover {
            width: 550px;
        }
    </style>