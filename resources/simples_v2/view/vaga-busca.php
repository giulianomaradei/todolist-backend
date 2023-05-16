<?php
require_once(__DIR__."/../class/System.php");

?>
<div class="row">
    <div class="col-md-10 col-md-offset-1">
        <div class="panel panel-default">
            <div class="panel-heading clearfix">
                <h3 class="panel-title text-left pull-left" style="margin-top: 2px;">Vagas:</h3>
                <div class="panel-title text-right pull-right"><a href="/api/iframe?token=<?php echo $request->token ?>&view=vaga-form"><button class="btn btn-xs btn-primary"><i class="fa fa-plus"></i> Novo</button></a></div>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group has-feedback">
                            <label class="control-label">Busca:</label>
                            <input class="form-control" type="text" name="nome" id="nome" onKeyUp="call_busca_ajax();" placeholder="Informe a descrição da vaga" autocomplete="off" autofocus>
                            <span class="glyphicon glyphicon-search form-control-feedback"></span>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group has-feedback">
                            <label class="control-label">Tipo:</label>
                            <select class="form-control" name="tipo" id="tipo" onChange="call_busca_ajax();">
                                <option value="">Todos</option>
                                <option value="1">Efetivo</option>
                                <option value="2">Estágio</option>
                                <option value="3">Jovem Aprendiz</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group has-feedback">
                            <label class="control-label">Cargo:</label>
                            <select class="form-control" name="cargo" id="cargo" onChange="call_busca_ajax();">
                                <option value="">Todos</option>
                                <?php
                                    $dados_cargo = DBRead('', 'tb_cargo', "ORDER BY descricao ASC");
                                    foreach ($dados_cargo as $conteudo) {
                                ?>
                                        <option value="<?= $conteudo['id_cargo']?>"><?= $conteudo['descricao']?></option>
                                <?php
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group has-feedback">
                            <label>Data de:</label>
                            <input class="form-control date calendar hasDatePicker" type="text" name="data_de" id="data_de" onChange="call_busca_ajax();" placeholder="Informe o nome..." autocomplete="off" autofocus>
                            <span class="glyphicon glyphicon-search form-control-feedback"></span>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group has-feedback">
                            <label>Data até:</label>
                            <input class="form-control date calendar hasDatePicker" type="text" name="data_ate" id="data_ate" onChange="call_busca_ajax();" placeholder="Informe o nome..." autocomplete="off" autofocus>
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
            </div>
        </div>
    </div>
</div>
<script>
    function call_busca_ajax(pagina){
        var inicia_busca = 1;
        var nome = $('#nome').val();
        var tipo = $('#tipo').val();
        var cargo = $('#cargo').val();
        var data_de = $('input[name="data_de"]').val();
        var data_ate = $('input[name="data_ate"]').val();

        if (nome.length < inicia_busca && nome.length >=1){
            return false;
        }

        if (pagina === undefined) {
            pagina = 1;
        }

        var parametros = {
            'nome': nome,
            'pagina': pagina,
            'tipo': tipo,
            'cargo': cargo,
            'data_de': data_de,
            'data_ate': data_ate
        };
        
        busca_ajax('<?= $request->token ?>' , 'VagaBusca', 'resultado_busca', parametros);
    }

    $(document).on('click', '.troca_pag', function () {
        call_busca_ajax($(this).attr('atr-pagina'));
    });

    call_busca_ajax();
</script>