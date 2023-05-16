<?php
require_once(__DIR__."/../class/System.php");

?>
<div class="container-fluid">
    <div class="row">
    <!-- <div class="col-md-6 col-md-offset-3"> -->
    <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    <h3 class="panel-title text-left pull-left" style="margin-top: 2px;">Gerenciar Patrimônio:</h3>
                    <div class="panel-title text-right pull-right">
                        <a href="/api/iframe?token=<?php echo $request->token ?>&view=patrimonio-form"><button class="btn btn-xs btn-primary"><i class="fa fa-plus"></i> Novo</button></a>
                    </div>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <!-- <div class="col-md-2">
                            <div class="form-group has-feedback">
                                <label>Busca:</label>
                                <input class="form-control" type="text" name="nome" id="nome" onKeyUp="call_busca_ajax();" placeholder="" autocomplete="off" autofocus>
                            </div>
                        </div> -->
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Item:</label>
                                <select name="id_patrimonio_item" id="id_patrimonio_item" class="form-control input-sm" onchange="call_busca_ajax();">
                                <option value="">Todos</option>
                                <?php
                                    $dados_patrimonio_item = DBRead('', 'tb_patrimonio_item', "WHERE status = 1 ORDER BY descricao ASC");

                                    if ($dados_patrimonio_item) {
                                        foreach ($dados_patrimonio_item as $conteudo_patrimonio_item) {
                                            echo "<option value='" . $conteudo_patrimonio_item['id_patrimonio_item'] . "'>" . $conteudo_patrimonio_item['descricao'] . "</option>";
                                        }
                                    }
                                ?>
                                </select>
                            </div>
                        </div>   
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Localização:</label>
                                <select name="id_patrimonio_localizacao" id="id_patrimonio_localizacao" class="form-control input-sm" onchange="call_busca_ajax();">
                                    <option value="">Todos</option>
                                    <?php
                                    $dados_patrimonio_localizacao = DBRead('', 'tb_patrimonio_localizacao', "WHERE status = 1 ORDER BY nome ASC");

                                    if ($dados_patrimonio_localizacao) {
                                        foreach ($dados_patrimonio_localizacao as $conteudo_patrimonio_localizacao) {
                                            echo "<option value='" . $conteudo_patrimonio_localizacao['id_patrimonio_localizacao'] . "'>" . $conteudo_patrimonio_localizacao['nome'] . "</option>";
                                        }
                                    }
                                ?>
                                </select>
                            </div>
                        </div> 
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Responsável:</label>
                                <select name="id_responsavel" id="id_responsavel" class="form-control input-sm" onchange="call_busca_ajax();">
                                    <option value="">Todos</option>
                                    <?php
                                        $dados_id_responsavel = DBRead('', 'tb_patrimonio a', "INNER JOIN tb_usuario b ON a.id_responsavel = b.id_usuario INNER JOIN tb_pessoa c ON b.id_pessoa = c.id_pessoa WHERE a.status != 6 GROUP BY a.id_responsavel ORDER BY c.nome ASC ","a.id_responsavel, c.nome");

                                        if ($dados_id_responsavel) {
                                            foreach ($dados_id_responsavel as $conteudo_id_responsavel) {
                                                echo "<option value='" . $conteudo_id_responsavel['id_responsavel'] . "'>" . $conteudo_id_responsavel['nome'] . "</option>";
                                            }
                                        }
                                    ?>
                                </select>
                            </div>
                        </div> 
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Fornencedor:</label>
                                <select name="id_fornecedor" id="id_fornecedor" class="form-control input-sm" onchange="call_busca_ajax();">
                                    <option value="">Todos</option>
                                    <?php
                                        $dados_id_fornecedor = DBRead('', 'tb_patrimonio a', "INNER JOIN tb_pessoa b ON a.id_fornecedor = b.id_pessoa WHERE a.status != 6 GROUP BY a.id_fornecedor  ORDER BY nome ASC ", "a.id_fornecedor, b.nome");

                                        if ($dados_id_fornecedor) {
                                            foreach ($dados_id_fornecedor as $conteudo_id_fornecedor) {
                                                echo "<option value='" . $conteudo_id_fornecedor['id_fornecedor'] . "'>" . $conteudo_id_fornecedor['nome'] . "</option>";
                                            }
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Status:</label>
                                <select name="status" id="status" class="form-control input-sm" onchange="call_busca_ajax();">
                                    <option value="">Todos</option>
                                    <option value="4">Descartado</option>
                                    <option value="5">Doado</option>
                                    <option value="2">Em Estoque</option>
                                    <option value="1">Em Uso</option>
                                    <option value="3">Vendido</option>
                                    <option value="7">Manutenção</option>

                                    
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <label class="control-label">Nº Patrimônio:</label>
                            <input class="form-control number_int input-sm" type="text" name="numero_patrimonio" id="numero_patrimonio" onKeyUp="call_busca_ajax();" placeholder="Digite o #" autocomplete="off" autofocus>
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
        //var nome = $('#nome').val();
        var id_patrimonio_item = $('#id_patrimonio_item').val();
        var id_patrimonio_localizacao = $('#id_patrimonio_localizacao').val();
        var id_responsavel = $('#id_responsavel').val();
        var id_fornecedor = $('#id_fornecedor').val();
        var status = $('#status').val();
        var numero_patrimonio = $('#numero_patrimonio').val();

        // if (nome.length < inicia_busca && nome.length >=1){
        //     return false;
        // }
        if(pagina === undefined){
            pagina = 1;
        }
        var parametros = {
            // 'nome': nome,
            'id_patrimonio_item': id_patrimonio_item,
            'id_patrimonio_localizacao': id_patrimonio_localizacao,
            'id_responsavel': id_responsavel,
            'id_fornecedor': id_fornecedor,
            'status': status,
            'pagina': pagina,
            'numero_patrimonio':numero_patrimonio
        };
        busca_ajax('<?= $request->token ?>' , 'PatrimonioBusca', 'resultado_busca', parametros);
    }

    $(document).on('click', '.troca_pag', function () {
        call_busca_ajax($(this).attr('atr-pagina'));
    });

    call_busca_ajax();
</script>