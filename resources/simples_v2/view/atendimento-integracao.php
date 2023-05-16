<?php
/*require_once(__DIR__."/../class/System.php");
require_once(__DIR__."/class/AtendimentoIntegracao.php");

error_reporting(E_ALL);
ini_set("display_errors", 1);*/

?>
<style>
.tabela-clientes-ixc{
    cursor: pointer !important;
}
</style>
<!--<div class="container-fluid">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="row">
                <div class="col-md-12">
                    <div id="resultado_busca"></div>
                </div>
            </div>
        </div>
    </div>
</div>-->
<!--<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs/dt-1.10.18/r-2.2.2/datatables.min.css"/>
<script type="text/javascript" src="https://cdn.datatables.net/v/bs/dt-1.10.18/r-2.2.2/datatables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/plug-ins/1.10.19/sorting/time.js"></script>-->
<script>
/*$(document).ready(function(){
    $('.table').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.19/i18n/Portuguese-Brasil.json"
        }
    });
});*/
</script>



<?php
require_once(__DIR__."/../class/System.php");


?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    <h3 class="panel-title text-left pull-left" style="margin-top: 2px;">Clientes:</h3>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group has-feedback">
                                <label class="control-label sr-only">Hidden label</label>
                                <input class="form-control" type="text" name="nome" id="nome" placeholder="Informe o nome do usuário" autocomplete="off" autofocus>
                                <span class="glyphicon glyphicon-search form-control-feedback"></span>
                                <button id='btn-procurar' onclick='call_busca_ajax();' class='btn btn-primary'>Procurar</button>
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
        busca_ajax(token: '<?= $request->token ?>', 'AtendimentoIntegracao', 'resultado_busca', parametros);
    }

    $(document).on('click', '.troca_pag', function(){
        call_busca_ajax($(this).attr('atr-pagina'));
    });
    call_busca_ajax();
</script>