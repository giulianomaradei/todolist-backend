<?php
require_once(__DIR__."/../class/System.php");

?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    <h3 class="panel-title text-left pull-left" style="margin-top: 2px;">Catálogo de equipamentos:</h3>
                    <div class="panel-title text-right pull-right"><a href="/api/iframe?token=<?php echo $request->token ?>&view=catalogo-equipamento-form"><button class="btn btn-xs btn-primary"><i class="fa fa-plus"></i> Novo</button></a></div>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-9">
                            <div class="form-group has-feedback">
                            <label>Busca:</label>
                            <input class="form-control input-sm" type="text" name="nome" id="nome" onKeyUp="call_busca_ajax();" placeholder="Informe o modelo do equipamento..." autocomplete="off" autofocus>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group has-feedback">
                                <label>Marca:</label>
                                <select class="form-control input-sm" id="marca" name="marca" onchange="call_busca_ajax();">
                                <option value="">Todas</option>

                                    <?php
                                        $dados_catalogo_equipamento_marca = DBRead('', 'tb_catalogo_equipamento_marca', "WHERE status = 1 ORDER BY nome ASC");
                                        
                                        if ($dados_catalogo_equipamento_marca) {
                                            foreach ($dados_catalogo_equipamento_marca as $conteudo_catalogo_equipamento_marca) {
                                                echo "<option value='".$conteudo_catalogo_equipamento_marca['id_catalogo_equipamento_marca']."'>".$conteudo_catalogo_equipamento_marca['nome']."</option>";
                                            }
                                        }
                                    ?>
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
        var marca = $('#marca').val();

        if (nome.length < inicia_busca && nome.length >=1){
            return false;
        }
        if(pagina === undefined){
            pagina = 1;
        }
        var parametros = {
            'nome': nome,
            'marca': marca,
            'pagina': pagina
        };
        busca_ajax('<?= $request->token ?>' , 'CatalogoEquipamentoBusca', 'resultado_busca', parametros);
    }

    $(document).on('click', '.troca_pag', function () {
        call_busca_ajax($(this).attr('atr-pagina'));
    });

    call_busca_ajax();
</script>