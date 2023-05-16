<?php
require_once(__DIR__."/../class/System.php");

?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    <h3 class="panel-title text-left pull-left" style="margin-top: 2px;">Tópicos:</h3>
                    <div class="panel-title text-right pull-right"><a href="/api/iframe?token=<?php echo $request->token ?>&view=topico-form"><button class="btn btn-xs btn-primary"><i class="fa fa-plus"></i> Novo</button></a></div>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group has-feedback">
                                <label class="control-label">Título:</label>
                                <input class="form-control" type="text" name="titulo" id="titulo" onKeyUp="call_busca_ajax();" placeholder="Informe o título do tópico..." autocomplete="off" autofocus>
                                <span class="glyphicon glyphicon-search form-control-feedback"></span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Autor:</label>
                                <select class="form-control" name="id_usuario" id="id_usuario" onchange="call_busca_ajax();">
                                    <option value=''>Todos</option>
                                    <?php
                                        $dados_usuarios = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE b.status != 2 ORDER BY b.nome ASC","a.id_usuario, b.nome");
                                        if($dados_usuarios) {
                                            foreach($dados_usuarios as $conteudo_usuarios) {
                                                $idSelect = $conteudo_usuarios['id_usuario'];
                                                $nomeSelect = $conteudo_usuarios['nome'];
                                                echo "<option value='$idSelect'>$nomeSelect</option>";
                                            }
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Categoria:</label>
                                <select class="form-control" name="id_categoria" id="id_categoria" onchange="call_busca_ajax();">
                                    <option value=''>Todas</option>
                                    <?php
                                        $dados_categoria = DBRead('', 'tb_categoria', "WHERE exibe_topico = 1 ORDER BY nome ASC");
                                        if($dados_categoria) {
                                            foreach($dados_categoria as $categoria) {
                                                $idCategoria = $categoria['id_categoria'];
                                                $nomeSelect = $categoria['nome'];
                                                echo "<option value='$idCategoria'>$nomeSelect</option>";
                                            }
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Curtidas:</label>
                                <select class="form-control" name="visualizado" id="visualizado" onchange="call_busca_ajax();">
                                    <option value=''>Todas</option>
                                    <option value='1'>Curti</option>
                                    <option value='2'>Não curti</option>
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
        var titulo = $('#titulo').val();
        var id_categoria = $('#id_categoria').val();
        var visualizado = $('#visualizado').val();
        var id_usuario = $('#id_usuario').val();
        if (titulo.length < inicia_busca && titulo.length >=1){
            return false;
        }
        if(pagina === undefined){
            pagina = 1;
        }
        var parametros = {
            'titulo': titulo,
            'id_categoria': id_categoria,
            'visualizado': visualizado,
            'id_usuario': id_usuario,
            'pagina': pagina
        };
        busca_ajax('<?= $request->token ?>' , 'TopicoBusca', 'resultado_busca', parametros);
    }

    $(document).on('click', '.troca_pag', function () {
        call_busca_ajax($(this).attr('atr-pagina'));
    });

    call_busca_ajax();
</script>