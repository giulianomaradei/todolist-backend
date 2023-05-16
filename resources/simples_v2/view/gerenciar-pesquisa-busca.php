<?php
require_once(__DIR__."/../class/System.php");

?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    <h3 class="panel-title text-left pull-left" style="margin-top: 2px;">Gerenciar Pesquisas</h3>
                    <div class="panel-title text-right pull-right">
                        <a href="/api/iframe?token=<?php echo $request->token ?>&view=gerenciar-pesquisa-form-clonar"><button class="btn btn-xs btn-warning"><i class="fa fa-clone"></i> Clonar</button></a>
                        <a href="/api/iframe?token=<?php echo $request->token ?>&view=gerenciar-pesquisa-form"><button class="btn btn-xs btn-primary"><i class="fa fa-plus"></i> Novo</button></a>
                    </div>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group has-feedback">
                                <label class="control-label">Busca:</label>
                                <input class="form-control input-sm" type="text" name="titulo" id="titulo" onKeyUp="call_busca_ajax();" placeholder="Digite a palavra chave..." autocomplete="off" autofocus>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group has-feedback">
                                <label class="control-label">Status:</label>
                                <select class="form-control input-sm" name="status" id="status" onchange="call_busca_ajax();">
                                    <option></option>
                                    <option value="1">Ativo</option>
                                    <option value="-1">Concluído</option>
                                    <option value="3">Pausado</option>
                                    <option value="4">Pausado automaticamente</option>
                                    <option value="5">Liberado</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group has-feedback">
                                <label class="control-label">Data de:</label>
                                <input class="form-control date calendar hasDatePicker hasDatepicker input-sm" type="text" name="data_de" onchange="call_busca_ajax();" placeholder="dd/mm/aaaa" autocomplete="off" autofocus="" id=""  maxlength="10">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group has-feedback">
                                <label class="control-label">Data até:</label>
                                <input class="form-control date calendar hasDatePicker hasDatepicker input-sm" type="text" name="data_ate" onchange="call_busca_ajax();" placeholder="dd/mm/aaaa" autocomplete="off" autofocus="" id=""  maxlength="10">
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
        var titulo = $('#titulo').val();
        var status = $('#status').val();
        var data_de = $('input[name="data_de"]').val();
        var data_ate = $('input[name="data_ate"]').val();

        if(titulo.length < inicia_busca && titulo.length >=1){
            return false;
        }

        if(pagina === undefined){
            pagina = 1;
        }

        var parametros = {
            'titulo': titulo,
            'pagina': pagina,
            'status': status,
            'data_de': data_de,
            'data_ate': data_ate
        };

        busca_ajax('<?= $request->token ?>' , 'PesquisaBusca', 'resultado_busca', parametros);
    }

    $(document).on('click', '.troca_pag', function () {
        call_busca_ajax($(this).attr('atr-pagina'));
    });

    call_busca_ajax();
</script>