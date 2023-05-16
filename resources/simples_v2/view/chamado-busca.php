<?php
require_once(__DIR__."/../class/System.php");
/*
<div class="col-md-2">
    <div class="form-group">
        <label>Remetente:</label>
        <select class="form-control" id="id_remetente" onchange="call_busca_ajax();">
            <option value=''>Todos</option>

                $dados_usuarios = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.status = 1 ORDER BY b.nome ASC","a.id_usuario, b.nome");
                if($dados_usuarios){
                    foreach($dados_usuarios as $conteudo_usuarios){
                        $idSelect = $conteudo_usuarios['id_usuario'];
                        $nomeSelect = $conteudo_usuarios['nome'];
                        echo "<option value='$idSelect'>$nomeSelect</option>";
                    }
                }

        </select>
    </div>
</div>
*/
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    <h3 class="panel-title text-left pull-left" style="margin-top: 2px;">Chamados:</h3>
                    <div class="panel-title text-right pull-right"><a href="/api/iframe?token=<?php echo $request->token ?>&view=chamado-form"><button class="btn btn-xs btn-primary"><i class="fa fa-plus"></i> Novo</button></a></div>
                </div>
                <div class="panel-body">

                    <div class="row">

                        <div class="col-md-1">
                            <label class="control-label">#:</label>
                            <input class="form-control input-sm number_int" type="text" name="id_chamado" id="id_chamado" onKeyUp="call_busca_ajax();" placeholder="Digite o #" autocomplete="off" autofocus>
                        </div>

                        <div class="col-md-2">
                            <label class="control-label">Título:</label>
                            <input class="form-control input-sm" type="text" name="titulo" id="titulo" onKeyUp="call_busca_ajax();" placeholder="Digite a palavra chave..." autocomplete="off" autofocus>
                        </div>

                        <div class="col-md-2" id='class_select_remetente'>
                            <div class="form-group">
                                <label>Remetente:</label>
                                <select class="form-control input-sm" name="tipo_remetente" id="tipo_remetente" onchange="call_busca_ajax();">
                                    <option value=''>Qualquer</option>
                                    <option value='2'>Belluno</option>
                                    <option value='1'>Painel do Cliente</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-2" style='display:none;' id='display_remetente'>
                            <label class="control-label">Remetente:</label>
                            <input class="form-control input-sm" type="text" name="busca_remetente" id="busca_remetente" onKeyUp="call_busca_ajax();" placeholder="Digite o nome do remetente..." autocomplete="off" autofocus>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Responsável:</label>
                                <select class="form-control input-sm" name="id_responsavel" id="responsavel" onchange="call_busca_ajax();">
                                    <option value=''>Qualquer</option>
                                    <?php
                                    $pessoa_responsavel = DBRead('', 'tb_pessoa a', "INNER JOIN tb_usuario b ON a.id_pessoa = b.id_pessoa WHERE a.funcionario = 1 AND a.status = 1 AND a.tipo = 'pf' ORDER BY nome ASC");
                                    foreach($pessoa_responsavel as $conteudo){
                                        echo "<option value='".$conteudo['id_usuario']."'>".$conteudo['nome']."</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Responsável Técnico:</label>
                                <select class="form-control input-sm" name="responsavel_tecnico" id="responsavel_tecnico" onchange="call_busca_ajax();">
                                    <option value=''>Qualquer</option>
                                    <?php
                                        $dados_tecnico = DBRead('', 'tb_perfil_sistema a', "INNER JOIN tb_usuario b ON a.id_perfil_sistema = b.id_perfil_sistema INNER JOIN tb_pessoa c ON b.id_pessoa = c.id_pessoa WHERE a.id_perfil_sistema = 4 AND b.status = 1 ORDER BY c.nome ASC","b.id_usuario, c.nome");
                                        
                                        if ($dados_tecnico) {
                                            foreach ($dados_tecnico as $conteudo_tecnico) {
                                                echo "<option value='".$conteudo_tecnico['id_usuario']."' >".$conteudo_tecnico['nome']."</option>";
                                            }
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <label class="control-label">Contrato:</label>
                            <input class="form-control input-sm" type="text" name="busca_contrato" id="busca_contrato" onKeyUp="call_busca_ajax();" placeholder="Digite o contrato..." autocomplete="off" autofocus>
                        </div>

                    </div>

                    <div class="row">
                        
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Status:</label>
                                <select class="form-control input-sm" name="id_status" id="id_status" onchange="call_busca_ajax();">
                                    <option value=''>Todos</option>
                                    <option value='-1'>Todos a serem respondidos</option>
                                    <option value='-2'>Todos encerrados</option>
                                    <option value='-3'>Todos com pendência</option>
                                    <option disabled>--------------------------------------</option>
                                    <?php
                                        $dados_status = DBRead('', 'tb_chamado_status', "ORDER BY descricao ASC");
                                        if($dados_status){
                                            foreach($dados_status as $status){
                                                $idStatus = $status['id_chamado_status'];
                                                $nomeSelect = $status['descricao'];
                                                echo "<option value='$idStatus'>$nomeSelect</option>";
                                            }
                                        }
                                    ?>
                                    
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Categoria:</label>
                                <select class="form-control input-sm" name="id_categoria" id="id_categoria" onchange="call_busca_ajax();">
                                    <option value=''>Todas</option>
                                    <?php
                                        $dados_categoria = DBRead('', 'tb_categoria', "WHERE exibe_chamado = 1 ORDER BY nome ASC");
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
                                <label>Origem:</label>
                                <select class="form-control input-sm" name="id_chamado_origem" id="id_chamado_origem" onchange="call_busca_ajax();">
                                <option value=''>Todas</option>
                                    <?php
                                        $dados_origem = DBRead('', 'tb_chamado_origem', "ORDER BY descricao ASC");
                                        if($dados_origem) {
                                            foreach($dados_origem as $origem) {
                                                $idOrigem = $origem['id_chamado_origem'];
                                                $nomeSelect = $origem['descricao'];
                                                echo "<option value='$idOrigem'>$nomeSelect</option>";
                                            }
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Visibilidade:</label>
                                <select class="form-control input-sm" name="visibilidade" id="visibilidade" onchange="call_busca_ajax();">
                                    <option value=''>Todas</option>
                                    <option value='1'>Público</option>
                                    <option value='2'>Privado</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Setor Envolvido:</label>
                                <select class="form-control input-sm" id="id_perfil" onchange="call_busca_ajax();">
                                    <option value=''>Todos</option>
                                    <?php
                                        $dados_perfis = DBRead('', 'tb_perfil_sistema', "WHERE status = 1 ORDER BY nome ASC");
                                        if($dados_perfis){
                                            foreach($dados_perfis as $conteudo_perfis){
                                                echo "<option value='".$conteudo_perfis['id_perfil_sistema']."'>".$conteudo_perfis['nome']."</option>";
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
        var titulo = $('#titulo').val();
        var id_categoria = $('#id_categoria').val();
        var id_chamado = $('#id_chamado').val();
        var visibilidade = $('#visibilidade').val();

        var id_chamado_status = $('#id_status').val();
        var id_perfil = $('#id_perfil').val();
        var id_responsavel = $("#responsavel").val();
        var responsavel_tecnico = $("#responsavel_tecnico").val();
        
        var id_chamado_origem = $("#id_chamado_origem").val();
        var busca_remetente = $("#busca_remetente").val();
        var busca_contrato = $("#busca_contrato").val();
        
        var tipo_remetente = $("#tipo_remetente").val();

        if (titulo.length < inicia_busca && titulo.length >=1){
            return false;
        }
        if(pagina === undefined){
            pagina = 1;
        }

        var parametros = {
            'titulo': titulo,
            'id_categoria': id_categoria,
            'id_chamado': id_chamado,
            'visibilidade': visibilidade,
            'id_perfil': id_perfil,
            'id_status': id_chamado_status,
            'id_responsavel': id_responsavel,
            'busca_remetente': busca_remetente,
            'id_chamado_origem': id_chamado_origem,
            'busca_contrato': busca_contrato,
            'tipo_remetente': tipo_remetente,
            'responsavel_tecnico': responsavel_tecnico,
            'pagina': pagina
        };
        busca_ajax('<?= $request->token ?>' , 'ChamadoBusca', 'resultado_busca', parametros);
    }

    $(document).on('click', '.troca_pag', function(){
        call_busca_ajax($(this).attr('atr-pagina'));
    });

    $(document).on('change', '#tipo_remetente', function(){
        if($(this).val() == 1 || $(this).val() == 2){
            $('#display_remetente').show();

            $('#class_select_remetente').removeClass('col-md-4');
            $('#class_select_remetente').removeClass('col-md-2');
            $('#class_select_remetente').addClass('col-md-2');
        }else{
            $('#display_remetente').hide();

            $('#class_select_remetente').removeClass('col-md-4');
            $('#class_select_remetente').removeClass('col-md-2');
            $('#class_select_remetente').addClass('col-md-4');
        }
    });

    call_busca_ajax();
</script>