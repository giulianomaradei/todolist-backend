<?php
require_once(__DIR__."/../class/System.php");

?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    <h3 class="panel-title text-left pull-left" style="margin-top: 2px;">Seleção:</h3>
                    <div class="panel-title text-right pull-right"><a href="/api/iframe?token=<?php echo $request->token ?>&view=selecao-form"><button class="btn btn-xs btn-primary"><i class="fa fa-plus"></i> Novo</button></a></div>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group has-feedback">
                                <label>Busca:</label>
                                <input class="form-control" type="text" name="nome" id="nome" onKeyUp="call_busca_ajax();" placeholder="Informe a palavra chave..." autocomplete="off" autofocus>
                                <span class="glyphicon glyphicon-search form-control-feedback"></span>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group has-feedback">
                                <label>Status:</label>
                                <select class="form-control" name="status" id="status" onChange="call_busca_ajax();">
                                    <option value="">Todos</option>
                                    <option value="1">Em andamento</option>
                                    <option value="2">Encerrada</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group has-feedback">
                                <label>Setor:</label>
                                <select class="form-control" name="setor" id="setor" onChange="call_busca_ajax();">
                                    <option value="">Todos</option>

                                    <?php
                                        $dados_setor = DBRead('', 'tb_setor', "ORDER BY descricao ASC");
                                        foreach ($dados_setor as $conteudo) {
                                    ?>
                                            <option value="<?=$conteudo['id_setor']?>"><?=$conteudo['descricao']?></option>
                                    <?php
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group has-feedback">
                                <label>Cargo:</label>
                                <select class="form-control" name="cargo" id="cargo" onChange="call_busca_ajax();">
                                    <option value="">Todos</option>

                                    <?php
                                        $dados_cargo = DBRead('', 'tb_cargo', "ORDER BY descricao ASC");
                                        foreach ($dados_cargo as $conteudo_cargo) {
                                    ?>
                                            <option value="<?=$conteudo_cargo['id_cargo']?>"><?=$conteudo_cargo['descricao']?></option>
                                    <?php
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group has-feedback">
                                <label>Data:</label>
                                <input class="form-control date calendar hasDatePicker hasDatepicker" type="text" name="data_selecao" id="data_selecao" onChange="call_busca_ajax();">
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
        var status = $('#status').val();
        var setor = $('#setor').val();
        var cargo = $('#cargo').val();
        var data = $('input[name="data_selecao"]').val();

        if (nome.length < inicia_busca && nome.length >=1){
            return false;
        }
        if(pagina === undefined){
            pagina = 1;
        }
        var parametros = {
            'nome': nome,
            'pagina': pagina,
            'status': status,
            'setor': setor,
            'cargo': cargo,
            'data': data
        };
        busca_ajax('<?= $request->token ?>' , 'SelecaoBusca', 'resultado_busca', parametros);
    }

    $(document).on('click', '.troca_pag', function(){
        call_busca_ajax($(this).attr('atr-pagina'));
    });

    call_busca_ajax();
</script>