<?php
require_once(__DIR__."/../class/System.php");

?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    <h3 class="panel-title text-left pull-left" style="margin-top: 2px;">Parâmetros:</h3>
                    <div class="panel-title text-right pull-right"><a href="/api/iframe?token=<?php echo $request->token ?>&view=parametro-form"><button class="btn btn-xs btn-primary"><i class="fa fa-plus"></i> Novo</button></a></div>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-9">
                            <div class="form-group has-feedback">
                                <label>Busca:</label>
                                <input class="form-control input-sm" type="text" name="nome" id="nome" onKeyUp="call_busca_ajax();" placeholder="Informe o nome do contrato..." autocomplete="off" autofocus>
                                <span class="glyphicon glyphicon-search form-control-feedback"></span>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Plano:</label>
                                <select class="form-control input-sm" id="plano" name="plano"  onChange="call_busca_ajax();">
                                    <option value="">Qualquer</option>
                                    <?php
                                    $dados_planos = DBRead('', 'tb_plano', "WHERE cod_servico = 'call_suporte' ORDER BY cod_servico ASC, nome ASC");
                                    foreach ($dados_planos as $conteudo) {
                                        $id_select = $conteudo['id_plano'];
                                        $nome_select = $conteudo['nome'];
                                        echo "<option value='$id_select'>$nome_select</option>";
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
        var plano = $('#plano').val();

        if (nome.length < inicia_busca && nome.length >=1){
            return false;
        }
        if(pagina === undefined){
            pagina = 1;
        }
        var parametros = {
            'nome': nome,
            'plano': plano,
            'pagina': pagina
        };
        busca_ajax('<?= $request->token ?>' , 'ParametroBusca', 'resultado_busca', parametros);
    }

    $(document).on('click', '.troca_pag', function(){
        call_busca_ajax($(this).attr('atr-pagina'));
    });

    call_busca_ajax();
</script>