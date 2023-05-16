<?php
require_once(__DIR__."/../class/System.php");

?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    <h3 class="panel-title text-left pull-left">Time Belluno:</h3>
                </div>
                    <div class="panel-body" style="padding-bottom: 0;">
                        <div class="row">
                            <div class="com-md-12">
                                <iframe src="https://belluno.company/nossa-equipe/" style="height:850px; width:100%;" title="Time Belluno"></iframe>
                            </div>
                        </div>
                        <!-- <div class="row">                            
                            <div class="col-md-12">
                                <div class="form-group has-feedback">
                                    <label class="control-label sr-only">Hidden label</label>
                                    <input class="form-control" type="text" name="nome" id="nome" onKeyUp="call_busca_ajax();" placeholder="Informe o nome..." autocomplete="off" autofocus>
                                    <span class="glyphicon glyphicon-search form-control-feedback"></span>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row"> 
                            <div class="col-md-12">
                                <div id="resultado_busca"></div>
                            </div>                            
						</div> -->
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

        if (nome.length < inicia_busca && nome.length >=1){
            return false;
        }
        if(pagina === undefined){
            pagina = 1;
        }
        var parametros = {
            'nome': nome,
            'pagina': pagina
        };
        busca_ajax('<?= $request->token ?>' , 'UsuarioFotoBusca', 'resultado_busca', parametros);
    }

    $(document).on('click', '.troca_pag', function () {
        call_busca_ajax($(this).attr('atr-pagina'));
    });

    call_busca_ajax();
</script>