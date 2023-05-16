<?php

/**
 * Busca os dados de finalização para um atendimento com integração no banco de dados do Simples, a tabela 'tb_dados_obrigatorios_integracao' é populada
 * no momento em que são salvas as configurações de integração no quadro informativo e alteradas no inicio do atendimento se e somente se o sistema verificar
 * a existencia de algum elemento a menos ou a mais na tabela em comparação ao que retorna do recurso da API.
 */
$dados_obrigatorios = DBRead('', 'tb_dados_obrigatorios_integracao', "WHERE id_integracao = 1 AND id_contrato_plano_pessoa = '$id_contrato_plano_pessoa'");

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
<script>
    function buscaAssinante() {
        $(".span-assinante").html(sessionStorage.getItem("razao_social"));
        //Fecha o collapse que exibe dados do assinante para atualizar a listagem dos contratos, atendimentos e ordens de serviço
        $("#myModal").on("shown.bs.modal", function() {
            $(".collapse").collapse('hide');
        });

        /** Verifica se o passo está no id_arvore=92 para capturar o texto clicado em uma das opções desse passo e configurar o título do assunto que o cliente do provedor solicitou para o preenchimento automatico do título no atendimento do sistema de gestão em casos de inetegração. */
        if ($('#id_arvore').val() == '92' || $('#id_arvore').val() == '15' || $('#id_arvore').val() == '2') {
            $("button[name='atualizar']").on('click', function() {
                sessionStorage.setItem('assunto', $(this).text());
            });
        } else if ($('#id_arvore').val() == '5650') {
            sessionStorage.setItem('assunto', "Cliente não confirmou o cadastro");
        }

        //Status referente ao assinante(cliente)
        status_assinante = {
            'S': {
                'nome': 'Ativo',
                'cor': 'text-success'
            },
            'N': {
                'nome': 'Inativo',
                'cor': 'text-danger'
            }
        };

        $("#busca_assinante").autocomplete({
                minLength: 2,
                source: function(request, response) {
                    $.ajax({
                        url: "class/integracoes/AssinanteIxcAutocomplete.php",
                        dataType: "json",
                        data: {
                            acao: 'autocomplete',
                            parametros: {
                                'nome': $('#busca_assinante').val(),
                                'id_contrato_plano_pessoa': <?= $id_contrato_plano_pessoa ?>
                            }
                        },
                        success: function(data) {
                            $('#loading_assinante').html('');
                            if (data) {
                                if (data.registros) {
                                    response(data.registros);
                                    $("#erro-busca-assinante").html("");
                                } else if (!data.registros) {
                                    if (!data.registros && data.total == 0) {
                                        $("#erro-busca-assinante").html("<p class='text-danger'>Assinante não encontrado!</p>");
                                    } else {
                                        $("#erro-busca-assinante").html("<p class='text-danger'>Assinante não encontrado!</p>");
                                        console.error("Problemas ao carregar os dados!");
                                    }
                                }
                            }
                        },
                        beforeSend: function() {
                            $('#loading_assinante').html(' <i class="fa fa-spinner faa-spin animated"></i> carregando...');
                        }
                    });
                },
                select: function(event, ui) {
                    sessionStorage.setItem("nome", status_assinante[ui.item.ativo]['nome']);
                    sessionStorage.setItem("cor", status_assinante[ui.item.ativo]['cor']);
                    $("#busca_assinante").val(ui.item.razao);
                    carregarDadosAssinanteEditarDados(ui.item.id);
                    return false;
                }
            })
            .autocomplete("instance")._renderItem = function(ul, item) {
                ul.css({
                    "z-index": "10000"
                });
                if (!item.razao) {
                    item.razao = '';
                }
                if (!item.cpf_cnpj) {
                    item.cpf_cnpj = '';
                }

                sessionStorage.setItem("nome", status_assinante[item.ativo]['nome']);
                sessionStorage.setItem("cor", status_assinante[item.ativo]['cor']);
                return $("<li style='padding-top: 15px;padding-left: 8px'>").append("<a><span class='" + status_assinante[item.ativo]['cor'] + "'><i class='fas fa-circle'></i> (" + status_assinante[item.ativo]['nome'] + ")</span><br><strong>ASSINANTE: </strong>" + item.razao + " <br><strong>CPF/CNPJ: </strong>" + item.cnpj_cpf + "<br><strong>ENDEREÇO: </strong>" + item.endereco + ", " + item.numero + "</a><hr style='margin-bottom: 0px;'>").appendTo(ul);
            };

        function carregarDadosAssinanteEditarDados(id) {
            var busca = $('#busca_assinante').val();
            if (busca != "" && busca.length >= 2) {
                $.ajax({
                    url: "class/integracoes/AssinanteIxcAutocomplete.php",
                    dataType: "json",
                    data: {
                        acao: 'consulta',
                        parametros: {
                            'id': id,
                            'id_contrato_plano_pessoa': <?= $id_contrato_plano_pessoa ?>
                        }
                    },
                    success: function(data) {
                        id_cidade = data.registros[0].cidade;
                        $("#salvar-alteracao-contato").on("click", function() {
                            /* session storage pra armazenar temporariamente o id do assinante no sistema ixc para utilização posterior
                            no fluxo de atendimento do sistema Simples */
                            //Dados de sessionStorage excluidos em 
                            sessionStorage.setItem("id_assinante", data.registros[0].id);
                            sessionStorage.setItem("razao_social", data.registros[0].razao);
                            sessionStorage.setItem("cpf_cnpj", data.registros[0].cnpj_cpf);
                            sessionStorage.setItem("endereco", data.registros[0].endereco);
                            sessionStorage.setItem("complemento", data.registros[0].complemento);
                            sessionStorage.setItem("bairro", data.registros[0].bairro);
                            sessionStorage.setItem("cep", data.registros[0].cep);
                            sessionStorage.setItem("observacao", data.registros[0].obs);
                            sessionStorage.removeItem("contrato");

                        });
                        $("#container-info-assinante").html(
                            `
                    <div class="row" style="margin: 10px 0px 0px 0px !important; padding: 10px 0 0 0 !important; background-color: #f2f2f2; border: 1px solid #d5d5d5;">
                        <div class="col-md-12">
                            <p><span class='` + sessionStorage.getItem("cor") + `'><i class='fas fa-circle'></i> (` + sessionStorage.getItem("nome") + `)</span></p>
                            <p><strong>Nome(Razão social):</strong> <span id="editar_razao">` + data.registros[0].razao + `</span></p>
                            <p><strong>CPF/CNPJ:</strong> <span id="editar_cpf_cnpj">` + data.registros[0].cnpj_cpf + `</span></p>
                            <p><strong>Endereço:</strong> <span id="editar_endereco">` + data.registros[0].endereco + `, ` + data.registros[0].numero + `, Bairro: ` + data.registros[0].bairro + ` - Cidade: <span class='cidade'></span><span></p>
                            <p><strong>Complemento:</strong> <span id="editar_complemento">` + data.registros[0].complemento + `</span></p>
                            <p><strong>Observação:</strong> <span id="editar_observacao">` + data.registros[0].obs + `</span></p>
                        </div>
                    </div>
                    `
                        );
                        $("#assinante").val(data.registros[0].razao);
                        $('#modal-lista-assinantes').modal('hide');
                    },
                    complete: function() {
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

    function removeSessionStorageAoSalvar() {
        //Verificar e passar para um arquivo externo
        $('#gravar').on('click', function() {
            sessionStorage.clear();
        });
    }

    function alertaIntegracao() {
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
            success: function(data) {
                if (data != null) {
                    console.log("Sistema de gestão no ar!");
                } else {
                    console.log("Sistema de gestão indisponível!");
                    $(".alerta-sistema-gestao-integracao").html("Sistema de gestão indisponível!");
                    $(".opcoes-sistema-gestao").css("display", "none");
                    $(".btn-integra").attr("disabled", "disabled");
                }
            },
            error: function() {
                console.log("Sistema de gestão indisponível!");
                $(".alerta-sistema-gestao-integracao").html("Sistema de gestão indisponível!");
                $(".opcoes-sistema-gestao").css("display", "none");
                $(".btn-integra").attr("disabled", "disabled");
            }
        });
    }
</script>