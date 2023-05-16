<script>
/////////////////////////////////////  Funções Javascript para o sistema de gestão IXC  ///////////////////////////////////////////////////////////////////////////////////////////

/** "Utilizar dados" (atendimento-inicio-form.php) - Função que captura os dados de atendimentos anteriores e joga nos campos de input do inicio do atendimento no sistema Simples (AtendimentosRealizadosBusca.php) */

function utilizarDados(){
    $('.utilizar-dados').on('click', function(){
        $('#contato').val($(this).parent().parent().parent().find('.utiliza-contato').text());
        $('#fone1').unmask();
        $('#fone2').unmask();
        $('#fone1').val($(this).parent().parent().parent().find('.utiliza-fone1').text());
        $('#fone2').val($(this).parent().parent().parent().find('.utiliza-fone2').text());
        $('#solicitacao').val($(this).parent().parent().parent().find('.utiliza-dado_adicional').text());
        if($(this).parent().parent().parent().find('.utiliza-cpf_cnpj').text().length > 11){
            $('#tipo').val('cnpj');
            $('#label_cpf_cnpj').text('*CNPJ:');
            $('#cpf_cnpj').unmask();
            $('#cpf_cnpj').val($(this).parent().parent().parent().find('.utiliza-cpf_cnpj').text());
            $('#cpf_cnpj').mask('00.000.000/0000-00', {reverse: true, placeholder: '00.000.000/0000-00'});
        }else{
            $('#tipo').val('cpf');
            $('#label_cpf_cnpj').text('*CPF:');
            $('#cpf_cnpj').unmask();
            $('#cpf_cnpj').val($(this).parent().parent().parent().find('.utiliza-cpf_cnpj').text());
            $('#cpf_cnpj').mask('000.000.000-00', {reverse: true, placeholder: '000.000.000-00'});
        }

        sessionStorage.clear();

        $("#busca_assinante").autocomplete({
            minLength: 2,
            source: function(request, response){
                $.ajax({
                    url: "class/integracoes/AssinanteIxcAutocomplete.php",
                    dataType: "json",
                    data: {
                        acao: 'autocomplete',
                        parametros: {
                            //'nome' : $('#busca_assinante').val(),
                            'nome' : $('#busca_assinante').val(),
                            'id_contrato_plano_pessoa': <?= $id_contrato_plano_pessoa ?>
                        }
                    },
                    success: function(data){
                        response(data.registros);
                    }
                });
            },
            focus: function(event, ui){
                $("#busca_assinante").val(ui.item.razao);
                carregarDadosAssinante(ui.item.id);
                return false;
            },
            select: function(event, ui){
                $("#busca_assinante").val(ui.item.razao);
                carregarDadosAssinante(ui.item.id);
                return false;
            }
        })
        .autocomplete("instance")._renderItem = function(ul, item){
            if(!item.razao){
                item.razao = '';
            }
            if(!item.cpf_cnpj){
                item.cpf_cnpj = '';
            }
        return $("<li>").append("<a>ASSINANTE: "+ item.razao + " </strong></a><hr style='margin-bottom: 0px;'>").appendTo(ul);
    };

    function carregarDadosAssinante(id){
        var busca = $('#busca_assinante').val();
        if(busca != "" && busca.length >= 2){
            $.ajax({
                url: "class/integracoes/AssinanteIxcAutocomplete.php",
                dataType: "json",
                data: {
                    acao: 'consulta',
                    parametros: {
                        'id' : id,
                        'id_contrato_plano_pessoa': <?= $id_contrato_plano_pessoa ?>
                    }
                },
                success: function(data){
                    id_cidade = data.registros[0].cidade;
                    /* Aqui é utilizado o recurso de session storage do html5 pra armazenar temporariamente o id do assinante no sistema ixc para utilização posterior
                    no fluxo de atendimento do sistema Simples */
                    sessionStorage.setItem("id_assinante", data.registros[0].id);
                    sessionStorage.setItem("razao_social", data.registros[0].razao);
                    sessionStorage.setItem("cpf_cnpj", data.registros[0].cnpj_cpf);
                    sessionStorage.setItem("endereco", data.registros[0].endereco);
                    sessionStorage.setItem("bairro", data.registros[0].bairro);
                    sessionStorage.setItem("cep", data.registros[0].cep);
                    sessionStorage.setItem("observacao", data.registros[0].obs);
                    sessionStorage.setItem('data_inicial', '<?php echo getDataHora(); ?>');
                    sessionStorage.setItem("numero_endereco", data.registros[0].numero);

                    sessionStorage.setItem('email', data.registros[0].email);

                    $("#container-info-assinante").html(
                        `
                    <div class="row" style="margin: 10px 0px 0px 0px !important; padding: 10px 15px 10px 15px !important; background-color: #f2f2f2; border: 1px solid #d5d5d5;>
                        <div class="col-md-12">
                            <p><strong>Nome (Razão social):</strong> ` + data.registros[0].razao + `</p>
                            <p><strong>CPF/CNPJ:</strong> ` + data.registros[0].cnpj_cpf + `</p>
                            <p><strong>Endereço:</strong> ` + data.registros[0].endereco + ` `+data.registros[0].numero+`, <span class='cidade'></span></p>
                            <p><strong>Observação:</strong> ` + data.registros[0].obs + `</p>
                        </div>
                    </div>
                    `
                    );
                    $("#assinante").val(data.registros[0].razao);
                    $('#modal-lista-assinantes').modal('hide');
                },
                complete: function(){
                    //var id_cidade = $(".btn-assinante").attr("data_cidade");
                    //Busca a cidade do assinante
                    $.ajax({
                        url: "class/IntegracaoTipoSistemaAjax.php",
                        method: "GET",
                        dataType: "json",
                        data: {
                            acao: "busca_cidade",
                            id_cidade: id_cidade,
                            id_contrato_plano_pessoa: <?= $id_contrato_plano_pessoa ?>
                        },
                        success: function(data){
                            sessionStorage.setItem("cidade", data.registros[0].nome);
                            $(".cidade").text(data.registros[0].nome);
                        }
                    });
                }
            });
        }
    }
    });
}

function buscaAssinante(){
    $(".span-assinante").html(sessionStorage.getItem("razao_social"));
    //Fecha o collapse que exibe dados do assinante para atualizar a listagem dos contratos, atendimentos e ordens de serviço
    $("#myModal").on("shown.bs.modal", function(){
        $(".collapse").collapse('hide');
    });

    /** Verifica se o passo está no id_arvore=92 para capturar o texto clicado em uma das opções desse passo e configurar o título do assunto que o cliente do provedor solicitou para o preenchimento automatico do título no atendimento do sistema de gestão em casos de inetegração. */
    if($('#id_arvore').val() == '92' || $('#id_arvore').val() == '15' || $('#id_arvore').val() == '2'){
        $( "button[name='atualizar']" ).on('click', function(){
            sessionStorage.setItem('assunto', $(this).text());
        });
    }else if($('#id_arvore').val() == '5650'){
        sessionStorage.setItem('assunto', "Cliente não confirmou o cadastro");
    }

    $("#busca_assinante").autocomplete({
        minLength: 2,
        source: function(request, response){
            $.ajax({
                url: "class/integracoes/AssinanteIxcAutocomplete.php",
                dataType: "json",
                data: {
                    acao: 'autocomplete',
                    parametros: {
                        'nome' : $('#busca_assinante').val(),
                        'id_contrato_plano_pessoa': <?= $id_contrato_plano_pessoa ?>
                    }
                },
                success: function(data){
                    response(data.registros);
                }
            });
        },
        focus: function(event, ui){
            /* $("#busca_assinante").val(ui.item.razao);
            carregarDadosAssinanteEditarDados(ui.item.id);
            return false; */
        },
        select: function(event, ui){
            $("#busca_assinante").val(ui.item.razao);
            carregarDadosAssinanteEditarDados(ui.item.id);
            return false;
        }
    })
    .autocomplete("instance")._renderItem = function(ul, item){
        ul.css({"z-index": "10000"});
        if(!item.razao){
            item.razao = '';
        }
        if(!item.cpf_cnpj){
            item.cpf_cnpj = '';
        }
        return $(`<li>`).append("<a><strong>ASSINANTE:</strong> "+item.razao+" </br><strong>CPF/CNPJ:</strong> "+item.cnpj_cpf+"<br><strong>ENDEREÇO: </strong>"+item.endereco+" "+item.numero+"</a><hr style='margin-bottom: 0px;'>").appendTo(ul);
    };

    function carregarDadosAssinanteEditarDados(id){
        var busca = $('#busca_assinante').val();
        if(busca != "" && busca.length >= 2){
            $.ajax({
                url: "class/integracoes/AssinanteIxcAutocomplete.php",
                dataType: "json",
                data: {
                    acao: 'consulta',
                    parametros: {
                        'id' : id,
                        'id_contrato_plano_pessoa': <?= $id_contrato_plano_pessoa ?>
                    }
                },
                success: function(data){
                    id_cidade = data.registros[0].cidade;
                    $("#salvar-alteracao-contato").on("click", function(){
                        /* session storage pra armazenar temporariamente o id do assinante no sistema ixc para utilização posterior
                        no fluxo de atendimento do sistema Simples */
                        //Dados de sessionStorage excluidos em 
                        sessionStorage.setItem("id_assinante", data.registros[0].id);
                        sessionStorage.setItem("razao_social", data.registros[0].razao);
                        sessionStorage.setItem("cpf_cnpj", data.registros[0].cnpj_cpf);
                        sessionStorage.setItem("endereco", data.registros[0].endereco);
                        sessionStorage.setItem("bairro", data.registros[0].bairro);
                        sessionStorage.setItem("cep", data.registros[0].cep);
                        sessionStorage.setItem("observacao", data.registros[0].obs);
                        sessionStorage.removeItem("contrato");
                        
                    });
                    
                    $("#container-info-assinante").html(
                    `
                    <div class="row" style="margin: 10px 0px 0px 0px !important; padding: 10px 0 0 0 !important; background-color: #f2f2f2; border: 1px solid #d5d5d5;">
                        <div class="col-md-12">
                            <p><strong>Nome(Razão social):</strong> <span id="editar_razao">` + data.registros[0].razao + `</span></p>
                            <p><strong>CPF/CNPJ:</strong> <span id="editar_cpf_cnpj">` + data.registros[0].cnpj_cpf + `</span></p>
                            <p><strong>Endereço:</strong> <span id="editar_endereco">` + data.registros[0].endereco + ` `+data.registros[0].numero+`, <span class='cidade'></span><span></p>
                            <p><strong>Observação:</strong> <span id="editar_observacao">` + data.registros[0].obs + `</span></p>
                        </div>
                    </div>
                    `
                    );
                    $("#assinante").val(data.registros[0].razao);
                    $('#modal-lista-assinantes').modal('hide');
                
                },
                complete: function(){
                    //Busca a cidade do assinante
                    $.ajax({
                        url: "class/IntegracaoTipoSistemaAjax.php",
                        method: "GET",
                        dataType: "json",
                        data: {
                            acao: "busca_cidade",
                            id_cidade: id_cidade,
                            id_contrato_plano_pessoa: <?= $id_contrato_plano_pessoa ?>
                        },
                        success: function(data) {
                            sessionStorage.setItem("cidade", data.registros[0].nome);
                            $(".cidade").text(data.registros[0].nome);
                        }
                    });
                }
            });
        }
    }

    //id do assinante é buscado da sessionStorage para armazenar esse id em um campo de formulario de html e ser enviado para o servidor na inicialização do atendimento no sistema Simples
    $("#id_assinante").val(sessionStorage.getItem("id_assinante"));
}

function removeDadosAoGravar(){
    $('#gravar').on('click', function(){
        sessionStorage.clear();
    });
}

function editarDados(){
    <?php
    if($integra && $integra[0]['id_integracao'] == "1"): 
    ?>
    $("#id_assinante").val(sessionStorage.getItem("id_assinante"));
    $(".span-assinante").html(sessionStorage.getItem("razao_social"));
    //Fecha o collapse que exibe dados do assinante para atualizar a listagem dos contratos, atendimentos e ordens de serviço
    $("#myModal").on("shown.bs.modal", function(){
        $(".collapse").collapse('hide');
    });
    /** Verifica se o passo está no id_arvore=92 para capturar o texto clicado em uma das opções desse passo e configurar o título do assunto que o cliente do provedor solicitou para o preenchimento automatico do título no atendimento do sistema de gestão em casos de inetegração. */
    if($('#id_arvore').val() == '92' || $('#id_arvore').val() == '15' || $('#id_arvore').val() == '2'){
        $( "button[name='atualizar']" ).on('click', function(){
            sessionStorage.setItem('assunto', $(this).text());
        });
    }else if($('#id_arvore').val() == '5650'){
        sessionStorage.setItem('assunto', "Cliente não confirmou o cadastro");
    }

    //////////////////////////// Teste ///////////////////////////////////////////

    $("#busca_assinante").autocomplete({
        minLength: 2,
        source: function(request, response){
            $.ajax({
                url: "class/integracoes/AssinanteIxcAutocomplete.php",
                dataType: "json",
                data: {
                    acao: 'autocomplete',
                    parametros: {
                        'nome' : $('#busca_assinante').val(),
                        'id_contrato_plano_pessoa': <?= $id_contrato_plano_pessoa ?>
                    }
                },
                success: function(data){
                    response(data.registros);
                }
            });
        },
        focus: function(event, ui){
            $("#busca_assinante").val(ui.item.razao);
            carregarDadosAssinante(ui.item.id);
            return false;
        },
        select: function(event, ui){
            $("#busca_assinante").val(ui.item.razao);
            carregarDadosAssinante(ui.item.id);
            return false;
        }
    })
    .autocomplete("instance")._renderItem = function(ul, item){
        ul.css({"z-index": "10000"});
        if(!item.razao){
            item.razao = '';
        }
        if(!item.cpf_cnpj){
            item.cpf_cnpj = '';
        }
        return $("<li style='padding-top: 15px;padding-left: 8px'>").append("<a><strong>ASSINANTE: "+ item.razao + " </strong><br><strong>CPF/CNPJ: " + item.cnpj_cpf + "</strong></a><hr style='margin-bottom: 0px;'>").appendTo(ul);
    };

    function carregarDadosAssinante(id){
        var busca = $('#busca_assinante').val();
        if(busca != "" && busca.length >= 2){
            $.ajax({
                url: "class/integracoes/AssinanteIxcAutocomplete.php",
                dataType: "json",
                data: {
                    acao: 'consulta',
                    parametros: {
                        'id' : id,
                        'id_contrato_plano_pessoa': <?= $id_contrato_plano_pessoa ?>
                    }
                },
                success: function(data){
                    id_cidade = data.registros[0].cidade;
                    /*utilizado session storage pra armazenar temporariamente o id do assinante no sistema ixc para utilização posterior
                    no fluxo de atendimento do sistema Simples */
                    //Dados de sessionStorage excluidos em 
                    if(typeof(Storage) !== "undefined"){
                        sessionStorage.setItem("id_assinante", data.registros[0].id);
                        sessionStorage.setItem("razao_social", data.registros[0].razao);
                        sessionStorage.setItem("cpf_cnpj", data.registros[0].cnpj_cpf);
                        sessionStorage.setItem("endereco", data.registros[0].endereco);
                        sessionStorage.setItem("bairro", data.registros[0].bairro);
                        sessionStorage.setItem("cep", data.registros[0].cep);
                        sessionStorage.setItem("observacao", data.registros[0].obs);
                        $("#id_assinante").val(sessionStorage.getItem("id_assinante"));
                    }
                    $("#container-info-assinante").html(
                        `
                    <div class="row" style="margin: 10px 0px 0px 0px !important; padding: 10px 15px 10px 15px !important; background-color: #f2f2f2; border: 1px solid #d5d5d5;>
                        <div class="col-md-6">
                            <p><strong>Nome (Razão social):</strong> ` + data.registros[0].razao + `</p>
                            <p><strong>CPF/CNPJ:</strong> ` + data.registros[0].cnpj_cpf + `</p>
                            <p><strong>Endereço:</strong> ` + data.registros[0].endereco + `, `+data.registros[0].numero+` <span class='cidade'></span></p>
                            <p><strong>Observação:</strong> ` + data.registros[0].obs + `</p>
                        </div>
                    </div>
                    `
                    );
                    $("#assinante").val(data.registros[0].razao);
                    $('#modal-lista-assinantes').modal('hide');
                },
                complete: function(){
                    //Busca a cidade do assinante
                    $.ajax({
                        url: "class/IntegracaoTipoSistemaAjax.php",
                        method: "GET",
                        dataType: "json",
                        data: {
                            acao: "busca_cidade",
                            id_cidade: id_cidade,
                            id_contrato_plano_pessoa: <?= $id_contrato_plano_pessoa ?>
                        },
                        success: function(data) {
                            sessionStorage.setItem("cidade", data.registros[0].nome);
                            $(".cidade").text(data.registros[0].nome);
                        }
                    });
                }
            });
        }
    }

    //Validação javascript para saber se o nome descrito no input de assinante é igual ao selecionado no autocomplete
    $("#busca_assinante").on("focusout", function(){
        if($(this).val() != sessionStorage.getItem("razao_social")){
            alert("Nome do assinante não corresponde!");
            $("#salvar-alteracao-contato").addClass("disabled");
            $("#salvar-alteracao-contato").removeClass("salvar-alteracao-contato");
            return false;
        }else if($(this).val() == sessionStorage.getItem("razao_social")){
            $("#salvar-alteracao-contato").removeClass("disabled");
            $("#salvar-alteracao-contato").addClass("salvar-alteracao-contato");
        }
    });

    $(".btn-opcao").on("click", function(){
        if($("#busca_assinante").val() != sessionStorage.getItem('razao_social')){
            $.ajax({
                type: "GET",
                url: "class/AtualizaContatoAtendimento.php",
                dataType: "json",
                data: {
                    id_atendimento: <?php echo $id_atendimento ?>,
                    contato: $('.contato').val(),
                    fone1: $('.fone1').val(),
                    fone2: $('.fone2').val(),
                    assinante: sessionStorage.getItem("razao_social"),
                    cpf_cnpj: sessionStorage.getItem("cpf_cnpj"),
                    dado_adicional: $('.dado_adicional').val(),
                },
                success: function(data){
                    
                    $('.span-contato').text(data.contato);
                    $('.span-fone1').text(data.fone1);
                    if($('.fone2').val()){
                        $('#div-fone2').html("<span id='clip_fone2'><strong>Fone 2:</strong> <span class='span-fone2'>"+$('.fone2').val()+"</span></span><br />");
                    }else{
                        $('#div-fone2').html("");
                    }
                    $('.span-fone2').text(data.fone2);
                    $('.span-assinante').text(data.assinante);
                    $('.span-cpf_cnpj').text(data.cpf_cnpj);
                    $('.span-dado_adicional').text(data.dado_adicional);
                    verifica_fone2();
                    $('#myModal').modal('hide');
                }
            });
        }
    });
    <?php
    endif;
    ?>
}

function removeSessionStorageAoSalvar(){
    //Verificar e passar para um arquivo externo
    $('#gravar').on('click', function(){
        sessionStorage.clear();
    });
}

function alertaIntegracao(){

    nome_assinante = $("span.span-assinante").text();
    $.ajax({
        url: "class/IntegracaoTipoSistemaAjax.php",
        method: "GET",
        dataType: "json",
        data: {
            acao: "buscar_assinante",
            nome_assinante: nome_assinante,
            id_contrato_plano_pessoa: <?= $id_contrato_plano_pessoa ?>
        },
        success: function(data){
            if(data != null){
                console.log("Sistema de gestão no ar!");
            }else{
                console.log("Sistema de gestão indisponível!");
                $(".alerta-sistema-gestao-integracao").html("Sistema de gestão indisponível!");
                //$(".btn-opcao-fluxo").attr("disabled", "disabled");
                $(".opcoes-sistema-gestao").css("display", "none");
                $(".btn-integra").attr("disabled", "disabled");
            }
        },
        error: function(){
            console.log("Sistema de gestão indisponível!");
            $(".alerta-sistema-gestao-integracao").html("Sistema de gestão indisponível!");
            //$(".btn-opcao-fluxo").attr("disabled", "disabled");
            $(".opcoes-sistema-gestao").css("display", "none");
            $(".btn-integra").attr("disabled", "disabled");
        }
    });
}

function verificaSistemaGestao() {
    nome_assinante = $("span.span-assinante").text();
    $.ajax({
        url: "class/IntegracaoTipoSistemaAjax.php",
        method: "GET",
        dataType: "json",
        data: {
            acao: "buscar_assinante",
            nome_assinante: nome_assinante,
            id_contrato_plano_pessoa: <?= $id_contrato_plano_pessoa ?>
        },
        success: function(data){
            if(data != null){
                console.log("Sistema de gestão no ar!");
                return true;
            }else{
                console.log("Sistema de gestão indisponível!");
                return false;
            }
        },
        error: function(){
            console.log("Sistema de gestão indisponível!");
            return false;
        }
    });
}

</script>