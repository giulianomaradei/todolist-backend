<?php
require_once(__DIR__."/../class/System.php");

?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    <h3 class="panel-title text-left pull-left" style="margin-top: 2px;">Acréscimos/Descontos:</h3>
                    <div class="panel-title text-right pull-right"><a href="/api/iframe?token=<?php echo $request->token ?>&view=acrescimo-desconto-form"><button class="btn btn-xs btn-primary"><i class="fa fa-plus"></i> Novo</button></a></div>
                </div>
                <div class="panel-body">
                    <!-- <div class="row">
                        <div class="col-md-9">
                            <div class="form-group has-feedback">
                            <label>Busca:</label>
                            <input class="form-control" type="text" name="nome" id="nome" onKeyUp="call_busca_ajax();" placeholder="Informe o nome da categoria..." autocomplete="off" autofocus>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group has-feedback">
                                <label>Exibição:</label>
                                <select class="form-control" name="exibicao" id="exibicao" onchange="call_busca_ajax();">
	                                <option value="">Todos</option>
	                                <option value="1">Exibe no Alerta</option>
	                                <option value="2">Exibe no Chamado</option>
	                                <option value="4">Exibe no Tópico</option>
                                </select>
                            </div>
                        </div>
                    </div> -->
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

        
        if(pagina === undefined){
            pagina = 1;
        }
        var parametros = {
            'pagina': pagina
        };
        busca_ajax('<?= $request->token ?>' , 'AcrescimoDescontoBusca', 'resultado_busca', parametros);
    }

    $(document).on('click', '.troca_pag', function () {
        call_busca_ajax($(this).attr('atr-pagina'));
    });

    call_busca_ajax();
</script>