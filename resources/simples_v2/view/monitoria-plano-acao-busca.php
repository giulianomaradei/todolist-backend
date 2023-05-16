<?php
require_once(__DIR__."/../class/System.php");

?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    <h3 class="panel-title text-left pull-left" style="margin-top: 2px;">Plano de ação:</h3>
                    <div class="panel-title text-right pull-right">
                        <!-- <a href="/api/iframe?token=<?php echo $request->token ?>&view=monitoria-resultado-form">
                            <button class="btn btn-xs btn-primary">
                                <i class="fa fa-plus"></i> Novo
                            </button>
                        </a> -->
                    </div>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Mês:</label>
                                <select class="form-control input-sm" id="mes" onChange="call_busca_ajax();">
                                    <option value="">Todos</option>
                                    <option value="01">Janeiro</option>
                                    <option value="02">Fevereiro</option>
                                    <option value="03">Março</option>
                                    <option value="04">Abril</option>
                                    <option value="05">Maio</option>
                                    <option value="06">Junho</option>
                                    <option value="07">Julho</option>
                                    <option value="08">Agosto</option>
                                    <option value="09">Setembro</option>
                                    <option value="10">Outubro</option>
                                    <option value="11">Novembro</option>
                                    <option value="12">Dezembro</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Ano:</label>
                                <select class="form-control input-sm" id="ano" onChange="call_busca_ajax();">
                                    <option value="2019">2019</option>
                                    <option value="2020">2020</option>
                                    <option value="2021"selected>2021</option>
                                    <option value="2022">2022</option>
                                    <option value="2023">2023</option>
                                    <option value="2024">2024</option>
                                    <option value="2025">2025</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Canal de atendimento:</label>
                                <select class="form-control input-sm" id="tipo" onchange="call_busca_ajax();">
                                    <option value="">Todos</option>
                                    <option value="1">Telefone</option>
                                    <option value="2">Texto</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Classificação de atendente:</label>
                                <select class="form-control input-sm" id="classificacao" onchange="call_busca_ajax();">
                                    <option value="">Todos</option>
                                    <option value="1">Em treinamento</option>
                                    <option value="2">Período de experiência</option>
                                    <option value="3">Efetivado</option>
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

        var mes = $('#mes').val();
        var ano = $('#ano').val();
        var tipo = $('#tipo').val();
        var classificacao = $('#classificacao').val();

        if(pagina === undefined){
            pagina = 1;
        }
        var parametros = {
            'mes': mes,
            'ano': ano,
            'tipo': tipo,
            'classificacao': classificacao,
            'pagina': pagina
        };
        busca_ajax('<?= $request->token ?>' , 'MonitoriaPlanoAcaoBusca', 'resultado_busca', parametros);
    }

    $(document).on('click', '.troca_pag', function () {
        call_busca_ajax($(this).attr('atr-pagina'));
    });

    call_busca_ajax();
</script>