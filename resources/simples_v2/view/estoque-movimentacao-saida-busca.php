<?php
require_once(__DIR__."/../class/System.php");

?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    <h3 class="panel-title text-left pull-left" style="margin-top: 2px;">Estoque - Movimentações (Saídas):</h3>
                    <div class="panel-title text-right pull-right">
                        <a href="/api/iframe?token=<?php echo $request->token ?>&view=estoque-movimentacao-saida-form">
                            <button class="btn btn-xs btn-primary"><i class="fa fa-plus"></i> Novo</button>
                        </a>
                    </div>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Nome:</label>
                                <input class="form-control input-sm" type="text" name="nome" id="nome" onKeyUp="call_busca_ajax();" placeholder="Digite o nome do item ou a informação adicional" autocomplete="off" autofocus>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Setor Responsável:</label>
                                <select name="id_setor" id="id_setor" class="form-control input-sm" onchange="call_busca_ajax();">
                                    <option value="">Todos</option>
                                    <?php

                                    $dados_id_setor = DBRead('', 'tb_setor', "WHERE status = 1 ORDER BY descricao ASC");

                                    if ($dados_id_setor) {
                                        foreach ($dados_id_setor as $conteudo_id_setor) {
                                            echo "<option value='" . $conteudo_id_setor['id_setor'] . "'>" . $conteudo_id_setor['descricao'] . "</option>";
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-2">
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
                        
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Solicitante:</label>
                                <select name="id_solicitante" id="id_solicitante" class="form-control input-sm" onchange="call_busca_ajax();">
                                    <option value="">Todos</option>
                                    <?php

                                    $dados_id_solicitante = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.status = '1' AND b.id_pessoa != '2' ORDER BY b.nome ASC", 'a.id_usuario, b.nome');

                                    if ($dados_id_solicitante) {
                                        foreach ($dados_id_solicitante as $conteudo_id_solicitante) {
                                            echo "<option value='" . $conteudo_id_solicitante['id_usuario'] . "'>" . $conteudo_id_solicitante['nome'] . "</option>";
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Localização:</label>
                                <select name="id_localizacao" id="id_localizacao" class="form-control input-sm" onchange="call_busca_ajax();">
                                    <option value="">Todas</option>
                                    <?php

                                    $dados_id_localizacao = DBRead('', 'tb_estoque_localizacao', "WHERE status = 1 ORDER BY nome");

                                    if ($dados_id_localizacao) {
                                        foreach ($dados_id_localizacao as $conteudo_id_localizacao) {
                                            echo "<option value='" . $conteudo_id_localizacao['id_estoque_localizacao'] . "'>" . $conteudo_id_localizacao['nome'] . "</option>";
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
        var id_setor = $('select[name=id_setor]').val();
        var id_usuario_cadastrou = $('select[name=id_usuario_cadastrou]').val();
        var id_solicitante = $('select[name=id_solicitante]').val();
        var id_localizacao = $('select[name=id_localizacao]').val();

        if (nome.length < inicia_busca && nome.length >=1){
            return false;
        }
        if(pagina === undefined){
            pagina = 1;
        }
        var parametros = {
            'nome': nome,
            'id_setor': id_setor,
            'id_usuario_cadastrou': id_usuario_cadastrou,
            'id_solicitante': id_solicitante,
            'id_localizacao': id_localizacao,
            'pagina': pagina
        };
        busca_ajax('<?= $request->token ?>' , 'EstoqueMovimentacaoSaidaBusca', 'resultado_busca', parametros);
    }

    $(document).on('click', '.troca_pag', function () {
        call_busca_ajax($(this).attr('atr-pagina'));
    });

    call_busca_ajax();
</script>