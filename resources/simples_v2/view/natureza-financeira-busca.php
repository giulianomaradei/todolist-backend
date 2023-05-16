<?php
require_once(__DIR__."/../class/System.php");

?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    <h3 class="panel-title text-left pull-left" style="margin-top: 2px;">Naturezas Financeiras:</h3>
                    <div class="panel-title text-right pull-right">
                        <a href="/api/iframe?token=<?php echo $request->token ?>&view=natureza-financeira-form"><button class="btn btn-xs btn-primary"><i class="fa fa-plus"></i> Nova</button></a>
                    </div>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Busca:</label>
                                <input class="form-control input-sm" type="text" name="nome" id="nome" onKeyUp="call_busca_ajax();" placeholder="" autocomplete="off" autofocus>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Tipo:</label>
                                <select class="form-control input-sm" name="tipo" id="tipo" onchange="call_busca_ajax();">
	                                <option value="">Todos</option>
	                                <option value="conta_receber">Conta a Receber</option>
	                                <option value="conta_pagar">Conta a Pagar</option>
	                                <option value="transferencia">Transferência</option>
                                </select>
                            </div>
                        </div>                        
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Agrupador:</label>
                                <select class="form-control input-sm" name="id_natureza_financeira_agrupador" id="id_natureza_financeira_agrupador" onchange="call_busca_ajax();">
                                    <option value="">Todos</option>
                                    <?php
                                        $dados_natureza_financeira_agrupador = DBRead('', 'tb_natureza_financeira_agrupador', "WHERE status = 1 ORDER BY nome ASC");

                                        if ($dados_natureza_financeira_agrupador) {
                                            foreach ($dados_natureza_financeira_agrupador as $conteudo_natureza_financeira_agrupador) {
                                                echo "<option value='".$conteudo_natureza_financeira_agrupador['id_natureza_financeira_agrupador']."'>".$conteudo_natureza_financeira_agrupador['nome']."</option>";
                                            }
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Status:</label>
                                <select class="form-control input-sm" name="status" id="status" onchange="call_busca_ajax();">
	                                <option value="">Todos</option>
	                                <option value="1">Ativo</option>
	                                <option value="0">Inativo</option>
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
    $(document).ready(function(){
      $('[data-toggle="tooltip"]').tooltip();   
    });

    function call_busca_ajax(pagina){
        var inicia_busca = 1;
        var nome = $('#nome').val();
        var status = $('#status').val();
        var tipo = $('#tipo').val();
        var id_natureza_financeira_agrupador = $('#id_natureza_financeira_agrupador').val();

        if (nome.length < inicia_busca && nome.length >=1){
            return false;
        }
        // if(pagina === undefined){
        //     pagina = 1;
        // }
        var parametros = {
            'nome': nome,
            // 'pagina': pagina,
            'tipo': tipo,
            'status': status,
            'id_natureza_financeira_agrupador': id_natureza_financeira_agrupador
        };
        busca_ajax('<?= $request->token ?>' , 'NaturezaFinanceiraBusca', 'resultado_busca', parametros);
    }

    // $(document).on('click', '.troca_pag', function () {
    //     call_busca_ajax($(this).attr('atr-pagina'));
    // });

    call_busca_ajax();
</script>