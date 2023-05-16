<?php
require_once(__DIR__."/../class/System.php");

?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-4 col-md-offset-4">
            <?php
                $id_usuario = $_SESSION['id_usuario'];
                $dados = DBRead('', 'tb_usuario', "WHERE id_usuario = '$id_usuario'");
                $perfil_sistema = $dados[0]['id_perfil_sistema'];           
            ?>
            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    <h3 class="panel-title text-left pull-left" style="margin-top: 2px;">Horários:</h3>
                        <div class="panel-title text-right pull-right">
                            <a href="/api/iframe?token=<?php echo $request->token ?>&view=escala-horarios-replicar">
                                <button class="btn btn-xs btn-primary"><i class="fa fa-share"></i> Replicar Horários Especiais</button>
                            </a>
                            <a href="/api/iframe?token=<?php echo $request->token ?>&view=clonar-escala-horarios">
                                <button class="btn btn-xs btn-warning"><i class="fa fa-clone"></i> Clonar Escalas</button>
                            </a>
                        </div>
                </div>
                <div class="panel-body">
                    <?php
                        if($perfil_usuario != 3){
                    ?>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group has-feedback">
                                <label class="control-label sr-only">Hidden label</label>
                                <input class="form-control" type="text" name="titulo" id="titulo" onKeyUp="call_busca_ajax();" placeholder="Informe o nome..." autocomplete="off" autofocus>
                                <span class="glyphicon glyphicon-search form-control-feedback"></span>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-12">
                            <div id="resultado_busca"></div>
                        </div>
                    </div>
                    <?php
                        }else{
                    ?>
                    <input type="hidden" id="titulo">
                    <div id="resultado_busca"></div>
                    <?php
                        }
                    ?>
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
        busca_ajax('<?= $request->token ?>' , 'EscalaBuscaHorarios', 'resultado_busca', parametros);
    }

    $(document).on('click', '.troca_pag', function () {
        call_busca_ajax($(this).attr('atr-pagina'));
    });

    call_busca_ajax();
</script>