<?php
require_once(__DIR__."/../class/System.php");
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    <h3 class="panel-title text-left pull-left" style="margin-top: 2px;">FAQ:</h3>
                    <div class="panel-title text-right pull-right"><a href="/api/iframe?token=<?php echo $request->token ?>&view=faq-form"><button class="btn btn-xs btn-primary"><i class="fa fa-plus"></i> Nova</button></a></div>
                </div>

                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-9">
                            <div class="form-group has-feedback">
                                <label>Busca:</label>
                                <input class="form-control" type="text" name="nome" id="nome" onKeyUp="call_busca_ajax();" placeholder="Digite a palavra chave" autocomplete="off" autofocus>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group has-feedback">
                                <label>Categoria:</label>
                                <select class="form-control input" name="select_categoria" id="select_categoria" onchange="call_busca_ajax();">
                                <option value=''>Todas</option>
                                    <?php
                                        $dados_id_faq_categoria = DBRead('', 'tb_faq_categoria', " ORDER BY nome ASC");
                                        if ($dados_id_faq_categoria) {
                                            foreach ($dados_id_faq_categoria as $conteudo_id_faq_categoria) {
                                                echo "<option value='" . $conteudo_id_faq_categoria['id_faq_categoria'] . "'>" . $conteudo_id_faq_categoria['nome'] . "</option>";
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
<style>
    .conteudo-editor img{
        max-width: 100% !important;
        max-height: 200px !important;
        height: 100% !important;
    }
    
</style>

<script>
    function call_busca_ajax(pagina){
        var inicia_busca = 1;
        var nome = $('#nome').val();
        var select_categoria = $('#select_categoria').val();

        if (nome.length < inicia_busca && nome.length >=1){
            return false;
        }
        if(pagina === undefined){
            pagina = 1;
        }
        var parametros = {
            'nome': nome,
            'select_categoria': select_categoria,
            'pagina': pagina
        };
        busca_ajax('<?= $request->token ?>' , 'FaqBusca', 'resultado_busca', parametros);
    }

    $(document).on('click', '.troca_pag', function () {
        call_busca_ajax($(this).attr('atr-pagina'));
    });

    call_busca_ajax();
</script>