<?php
require_once(__DIR__."/../class/System.php");

?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    <h3 class="panel-title text-left pull-left" style="margin-top: 2px;">Sistemas de chat:</h3>
                    <div class="panel-title text-right pull-right"><a href="/api/iframe?token=<?php echo $request->token ?>&view=sistema-chat-form"><button class="btn btn-xs btn-primary"><i class="fa fa-plus"></i> Novo</button></a></div>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group has-feedback">
                                <label>Busca:</label>
                                <input class="form-control" type="text" name="nome" id="nome" onKeyUp="call_busca_ajax();" placeholder="Informe a palavra chave..." autocomplete="off" autofocus>
                                <span class="glyphicon glyphicon-search form-control-feedback"></span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group has-feedback">
                                <label>Sistema de chat:</label>
                                <select class="form-control" name="sistema_chat" id="sistema_chat" onChange="call_busca_ajax();">
                                    <option value="">Todos</option>
                                    <?php
                                        $sistema_chat = DBRead('', 'tb_tipo_sistema_chat', "ORDER BY nome ASC");
                                        foreach($sistema_chat as $conteudo){
                                    ?>
                                        <option value="<?=$conteudo['id_tipo_sistema_chat']?>"><?=$conteudo['nome']?></option>
                                    <?php
                                        }
                                    ?>
                               </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group has-feedback">
                                <label>Link de acesso</label>
                                <select class="form-control" name="id_link_acesso" id="id_link_acesso" onChange="call_busca_ajax();">
                                    <option value=""></option>
                                    <?php
                                        $dados_link_acesso = DBRead('', 'tb_link_acesso', "ORDER BY nome ASC");
                                        foreach($dados_link_acesso as $conteudo){
                                    ?>
                                        <option value="<?=$conteudo['id_link_acesso']?>"><?=$conteudo['nome']?></option>
                                    <?php
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
        var sistema_chat = $('#sistema_chat').val();
        var id_link_acesso = $('#id_link_acesso').val();

        if (nome.length < inicia_busca && nome.length >=1){
            return false;
        }
        if(pagina === undefined){
            pagina = 1;
        }
        var parametros = {
            'nome': nome,
            'pagina': pagina,
            'sistema_chat': sistema_chat,
            'id_link_acesso': id_link_acesso
        };
        busca_ajax('<?= $request->token ?>' , 'SistemaChatBusca', 'resultado_busca', parametros);
    }

    $(document).on('click', '.troca_pag', function() {
        call_busca_ajax($(this).attr('atr-pagina'));
    });

    call_busca_ajax();
</script>