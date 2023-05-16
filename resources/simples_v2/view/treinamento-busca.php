<?php
require_once(__DIR__."/../class/System.php");

$setor = DBRead('', 'tb_perfil_sistema', "ORDER BY nome ASC");

?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    <h3 class="panel-title text-left pull-left" style="margin-top: 2px;">Treinamento:</h3>
                    <div class="panel-title text-right pull-right"><a href="/api/iframe?token=<?php echo $request->token ?>&view=treinamento-form"><button class="btn btn-xs btn-primary"><i class="fa fa-plus"></i> Novo</button></a></div>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-group has-feedback">
                                <label class="control-label">Busca:</label>
                                <input class="form-control" type="text" name="nome" id="nome" onKeyUp="call_busca_ajax();" placeholder="Informe a palavra chave..." autocomplete="off" autofocus>
                                <span class="glyphicon glyphicon-search form-control-feedback"></span>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Setor:</label>
                                <select class="form-control" name="setor" id="setor" onChange="call_busca_ajax();">
                                    <option value="">Todos</option>
                                    <?php
                                        foreach($setor as $conteudo){
                                    ?>
                                        <option value="<?=$conteudo['id_perfil_sistema']?>"><?=$conteudo['nome']?></option>
                                    <?php
                                        }
                                    ?> 
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Objetivo:</label>
                                <select class="form-control" name="objetivo" id="objetivo" onChange="call_busca_ajax();">
                                    <option value="">Todos</option>
                                    <option value="1">Qualificação</option>
                                    <option value="2">Reciclagem</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Avaliações em aberto:</label>
                                <select class="form-control" name="avaliacao" id="avaliacao" onChange="call_busca_ajax();">
                                    <option value="">Todos</option>
                                    <option value="1">Sim</option>
                                    <option value="2">Não</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Data de:</label>
                                <input class="form-control date calendar hasDatePicker hasDatepicker" type="text" name="data_de" onchange="call_busca_ajax();" placeholder="dd/mm/aaaa" autocomplete="off" maxlength="10" id="date_de" >
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Data ate:</label>
                                <input class="form-control date calendar hasDatePicker hasDatepicker" type="text" name="data_ate" onchange="call_busca_ajax();" placeholder="dd/mm/aaaa" autocomplete="off" maxlength="10" id="data_ate">
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
    function call_busca_ajax(pagina) {
        var inicia_busca = 1;
        var nome = $('#nome').val();
        var setor = $('#setor').val();
        var objetivo = $('#objetivo').val();
        var avaliacao = $('#avaliacao').val();
        var data_de = $('input[name="data_de"]').val();
        var data_ate = $('input[name="data_ate"]').val();
    
        if (nome.length < inicia_busca && nome.length >= 1) {
            return false;
        }
        if (pagina === undefined) {
            pagina = 1;
        }
        var parametros = {
            'nome': nome,
            'setor': setor,
            'objetivo': objetivo,
            'avaliacao': avaliacao,
            'data_de': data_de,
            'data_ate': data_ate 
        };
        busca_ajax('<?= $request->token ?>' , 'TreinamentoBusca', 'resultado_busca', parametros);
    }

    $(document).on('click', '.troca_pag', function() {
        call_busca_ajax($(this).attr('atr-pagina'));
    });

    call_busca_ajax();
</script>