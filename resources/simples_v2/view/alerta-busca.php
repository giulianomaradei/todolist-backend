<?php
require_once(__DIR__."/../class/System.php");

?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    <h3 class="panel-title text-left pull-left" style="margin-top: 2px;">Alertas:</h3>
                    <div class="panel-title text-right pull-right"><a href="/api/iframe?token=<?php echo $request->token ?>&view=alerta-form"><button class="btn btn-xs btn-primary"><i class="fa fa-plus"></i> Novo</button></a></div>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>*Cliente - Contrato:</label>
                                <div class="input-group">
<<<<<<< HEAD
                                    <input class="form-control input-sm" id="busca_contrato" type="text" name="busca_contrato"  value="<?=isset($plano) ? $plano : ''?>" placeholder="Informe o nome ou CNPJ..." autocomplete="off" readonly required />
=======
                                    <input class="form-control input-sm" id="busca_contrato" type="text" name="busca_contrato" placeholder="Informe o nome ou CNPJ..." autocomplete="off" readonly required />
>>>>>>> 5cfe18e1256c22b2f7585786435771d80b84680b
                                    <div class="input-group-btn">
                                        <button class="btn btn-info btn-sm" id="habilita_busca_contrato" name="habilita_busca_contrato" type="button" title="Clique para selecionar o contrato" style="height: 30px;"><i class="fa fa-search"></i></button>
                                    </div>
                                </div>
<<<<<<< HEAD
                                <input type="hidden" name="id_contrato_plano_pessoa" id="id_contrato_plano_pessoa" value="<?=isset($id_contrato_plano_pessoa) ? $id_contrato_plano_pessoa : ''?>" />
=======
                                <input type="hidden" name="id_contrato_plano_pessoa" id="id_contrato_plano_pessoa" />
>>>>>>> 5cfe18e1256c22b2f7585786435771d80b84680b
                            </div>
                        </div>
                        <div class="col-md-2">
                        <div class="form-group">
                            <label>Exibição:</label>
                            <select class="form-control input-sm" name="exibicao" id="exibicao" onchange="call_busca_ajax()"> 
                                <option value=''>Todas</option>
                                <option value='1'>Atendimento - Todo</option>
                                <option value='2'>Atendimento - Somente na finalização</option>
                                <option value='3'>Atendimento - Somente no início</option>
                                <option value='4'>Monitoramento - Todo</option>"
                            </select>
                        </div>
                        </div>                        
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="data_inicio">Data de:</label>
                                <input type="text" class="form-control input-sm date calendar" onchange="call_busca_ajax()" name="data_inicio" id="data_inicio">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="data_fim">Data até:</label>
                                <input type="text" class="form-control input-sm date calendar" onchange="call_busca_ajax()" name="data_fim" id="data_fim">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="vencido">Vencido:</label>
                                <select id="vencido" name="vencido" onchange="call_busca_ajax()" class="form-control input-sm">
                                    <option value=''>Todos</option>
                                    <option value='sim'>Sim</option>
                                    <option value='nao'>Não</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Tipo:</label>
                                <select id="tipo" name="tipo" onchange="call_busca_ajax()" class="form-control input-sm">
                                    <option value=''>Todos</option>
                                    <option value='contrato'>Contrato</option>
                                    <option value='geral'>Alerta Geral</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-12">
                            <div id="resultado_busca"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
// Atribui evento e função para limpeza dos campos
    $('#busca_contrato').on('input', limpaCamposContrato);
    // Dispara o Autocomplete da pessoa a partir do segundo caracter
    $("#busca_contrato").autocomplete({
            minLength: 2,
            source: function(request, response){
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
            focus: function(event, ui){
                $("#busca_contrato").val(ui.item.nome + " " + ui.item.nome_contrato +" - " + ui.item.servico + " - " + ui.item.plano + " (" + ui.item.id_contrato_plano_pessoa + ")");
                carregarDadosContrato(ui.item.id_contrato_plano_pessoa);
                return false;
            },
            select: function(event, ui){
                $("#busca_contrato").val(ui.item.nome + " "+ ui.item.nome_contrato + " - " + ui.item.servico + " - " + ui.item.plano + " (" + ui.item.id_contrato_plano_pessoa + ")");
                $('#busca_contrato').attr("readonly", true);
                call_busca_ajax();
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

        return $("<li>").append("<a><strong>"+item.id_contrato_plano_pessoa + " - " + item.nome + ""+item.nome_contrato+"</strong><br>" +item.razao_social+ "<br>" +item.cpf_cnpj+ "<br>" + item.servico + " - " + item.plano + " (" + item.id_contrato_plano_pessoa + ")" + "</a><hr style='margin-bottom: 0px;'>").appendTo(ul);
    };
    // Função para carregar os dados da consulta nos respectivos campos
    function carregarDadosContrato(id) {
        var busca = $('#busca_contrato').val();

        if (busca != "" && busca.length >= 2) {
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
    function limpaCamposContrato(){
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

    function call_busca_ajax(pagina){
        var inicia_busca = 1;
        var id_contrato =   $('#id_contrato_plano_pessoa').val();
        var data_inicio = $("[name=data_inicio]").val();
        var data_fim =    $("[name=data_fim]").val();
        var vencido =     $('#vencido').val();
        var exibicao =     $('#exibicao').val();
        var tipo =     $('#tipo').val();
        if(pagina === undefined){
            pagina = 1;
        }
        var parametros = {
            'id_contrato_plano_pessoa': id_contrato,
            'data_inicio': data_inicio,
            'data_fim': data_fim,
            'vencido': vencido,
            'exibicao': exibicao,
            'tipo': tipo,
            'pagina': pagina
        };
        busca_ajax('<?= $request->token ?>', 'AlertaBusca', 'resultado_busca', parametros);
    }

    $(document).on('click', '.troca_pag', function () {
        call_busca_ajax($(this).attr('atr-pagina'));
    });

    call_busca_ajax();
</script>