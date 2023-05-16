<?php
require_once(__DIR__."/../class/System.php");

?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    <h3 class="panel-title text-left pull-left" style="margin-top: 2px;">Classificação de atendente:</h3>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group has-feedback">
                                <label class="control-label">Nome:</label>
                                <input class="form-control" type="text" name="nome" id="nome" onKeyUp="call_busca_ajax();" placeholder="Informe o nome do usuário" autocomplete="off" autofocus>
                                <span class="glyphicon glyphicon-search form-control-feedback"></span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="classificacao">Classificação</label>
                                <select class="form-control" name="classificacao" id="classificacao" onChange="call_busca_ajax();">
                                    <option value="">Todos</option>
                                    <option value="1">Em treinamento</option>
                                    <option value="2">Período de experiência</option>
                                    <option value="3">Efetivado</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="canal_atendimento">Canal de atendimento</label>
                                <select class="form-control" name="canal_atendimento" id="canal_atendimento" onChange="call_busca_ajax();">
                                    <option value="">Todos</option>
                                    <option value="1">Telefone</option>
                                    <option value="2">Texto</option>
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
    function call_busca_ajax(pagina){
        var inicia_busca = 1;
        var nome = $('#nome').val();
        var classificacao = $('#classificacao').val();
        var canal_atendimento = $('#canal_atendimento').val();

        if (nome.length < inicia_busca && nome.length >=1){
            return false;
        }
        if(pagina === undefined){
            pagina = 1;
        }
        var parametros = {
            'nome': nome,
            'classificacao': classificacao,
            'canal_atendimento': canal_atendimento,
            'pagina': pagina
        };
        busca_ajax('<?= $request->token ?>' , 'MonitoriaClassificacaoAtendenteBusca', 'resultado_busca', parametros);
    }

    $(document).on('click', '.troca_pag', function () {
        call_busca_ajax($(this).attr('atr-pagina'));
    });

    call_busca_ajax();
</script>