<?php
require_once(__DIR__."/../class/System.php");

?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    <h3 class="panel-title text-left pull-left" style="margin-top: 2px;">Estoque - Movimentações (Entradas):</h3>
                    <div class="panel-title text-right pull-right">
                        <a href="/api/iframe?token=<?php echo $request->token ?>&view=estoque-movimentacao-entrada-form">
                            <button class="btn btn-xs btn-primary"><i class="fa fa-plus"></i> Novo</button>
                        </a>
                    </div>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Nome:</label>
                                <input class="form-control input-sm" type="text" name="nome" id="nome" onKeyUp="call_busca_ajax();" placeholder="Digite o nome do item ou a informação adicional" autocomplete="off" autofocus>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Fornecedor:</label>
                                <select name="id_fornecedor" id="id_fornecedor" class="form-control input-sm" onchange="call_busca_ajax();">
                                    <option value="">Todos</option>
                                    <?php

                                    $dados_id_fornecedor = DBRead('', 'tb_estoque_movimentacao_item a', "INNER JOIN tb_pessoa b ON a.id_fornecedor = b.id_pessoa GROUP BY a.id_fornecedor ORDER BY b.nome ASC", "a.id_fornecedor, b.nome");

                                    if ($dados_id_fornecedor) {
                                        foreach ($dados_id_fornecedor as $conteudo_id_fornecedor) {
                                            echo "<option value='" . $conteudo_id_fornecedor['id_fornecedor'] . "'>" . $conteudo_id_fornecedor['nome'] . "</option>";
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Cadastrada Por:</label>
                                <select name="id_usuario_cadastrou" id="id_usuario_cadastrou" class="form-control input-sm" onchange="call_busca_ajax();">
                                    <option value="">Qualquer</option>
                                    <?php

                                    $dados_id_usuario_cadastrou = DBRead('', 'tb_estoque_movimentacao a', "INNER JOIN tb_usuario b ON a.id_usuario = b.id_usuario INNER JOIN tb_pessoa c ON b.id_pessoa = c.id_pessoa GROUP BY a.id_usuario ORDER BY c.nome", "a.id_usuario, c.nome");

                                    if ($dados_id_usuario_cadastrou) {
                                        foreach ($dados_id_usuario_cadastrou as $conteudo_id_usuario_cadastrou) {
                                            echo "<option value='" . $conteudo_id_usuario_cadastrou['id_usuario'] . "'>" . $conteudo_id_usuario_cadastrou['nome'] . "</option>";
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
        var id_usuario_cadastrou = $('select[name=id_usuario_cadastrou]').val();
        var id_fornecedor = $('select[name=id_fornecedor]').val();

        if (nome.length < inicia_busca && nome.length >=1){
            return false;
        }
        if(pagina === undefined){
            pagina = 1;
        }
        var parametros = {
            'nome': nome,
            'id_usuario_cadastrou': id_usuario_cadastrou,
            'id_fornecedor': id_fornecedor,
            'pagina': pagina
        };
        busca_ajax('<?= $request->token ?>' , 'EstoqueMovimentacaoEntradaBusca', 'resultado_busca', parametros);
    }

    $(document).on('click', '.troca_pag', function () {
        call_busca_ajax($(this).attr('atr-pagina'));
    });

    call_busca_ajax();
</script>