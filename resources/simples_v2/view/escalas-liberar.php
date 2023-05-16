<?php
require_once(__DIR__."/../class/System.php");

?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-4 col-md-offset-4">
            <?php
                $id_usuario = $_SESSION['id_usuario'];
                $dados = DBRead('', 'tb_usuario', "WHERE id_usuario = '$id_usuario'");
                $perfil_usuario = $dados[0]['id_perfil_sistema'];           
            ?>
            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    <h3 class="panel-title text-left pull-left" style="margin-top: 2px;">Liberar/Bloquear Escalas:</h3>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div id="resultado_busca"></div>
                        </div>
                    </div>
                    <input type="hidden" id="titulo">
                    <div id="resultado_busca"></div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function call_busca_ajax(pagina){
        var inicia_busca = 1;
        var titulo = $('#titulo').val();
        if (titulo.length < inicia_busca && titulo.length >=1){
            return false;
        }
        if(pagina === undefined){
            pagina = 1;
        }
        var parametros = {
            'titulo': titulo,
            'pagina': pagina
        };
        busca_ajax('<?= $request->token ?>' , 'EscalasLiberar', 'resultado_busca', parametros);
    }

    $(document).on('click', '.troca_pag', function () {
        call_busca_ajax($(this).attr('atr-pagina'));
    });

    call_busca_ajax();
</script>