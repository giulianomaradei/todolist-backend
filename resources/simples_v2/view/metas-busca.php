<?php
require_once(__DIR__."/../class/System.php");

?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    <h3 class="panel-title text-left pull-left" style="margin-top: 2px;">Metas:</h3>
                    <div class="panel-title text-right pull-right"><a href="/api/iframe?token=<?php echo $request->token ?>&view=metas-form"><button class="btn btn-xs btn-primary"><i class="fa fa-plus"></i> Nova</button></a></div>
                </div>
                
                      <div class="panel-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group has-feedback">
                                <label class="control-label">Nome:</label>
                                <input class="form-control" type="text" name="nome" id="nome" onKeyUp="call_busca_ajax();" placeholder="Informe a meta..." autocomplete="off" autofocus>
                                <span class="glyphicon glyphicon-search form-control-feedback"></span>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Tipo:</label>
                                <select class="form-control" name="tipo" id="tipo" onchange="call_busca_ajax();">
                                    <option value=''>Todos</option>
                                    <option value='1'>Individual</option>
                                    <option value='2'>Equipe</option>
                                    <option value='3'>Geral</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Data inicial:</label>
                                <input class="campos-agendar form-control date calendar" name="data_de" id="data_de" placeholder="Informe a data de início..." type="text" autocomplete="off" onchange="call_busca_ajax();"/>      
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Data final:</label>
                                <input class="campos-agendar form-control date calendar" name="data_ate" id="data_ate" placeholder="Informe a data de início..." type="text" autocomplete="off" onchange="call_busca_ajax();"/>
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
        var tipo = $('#tipo').val();
        var data_de = $( "input[name='data_de']" ).val();
        var data_ate = $( "input[name='data_ate']" ).val();
        if (nome.length < inicia_busca && nome.length >=1){
            return false;
        }
        if(pagina === undefined){
            pagina = 1;
        }

        var parametros = {
            'nome': nome,
            'tipo': tipo,
            'data_de': data_de,
            'data_ate': data_ate,
            'pagina': pagina
        };
        busca_ajax('<?= $request->token ?>' , 'MetasBusca', 'resultado_busca', parametros);
    }

    $(document).on('click', '.troca_pag', function () {
        call_busca_ajax($(this).attr('atr-pagina'));
    });

    call_busca_ajax();
</script>