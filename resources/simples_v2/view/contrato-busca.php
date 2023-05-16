<?php
require_once(__DIR__."/../class/System.php");

?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    <h3 class="panel-title text-left pull-left" style="margin-top: 2px;">Contratos:</h3>
                    <div class="panel-title text-right pull-right"><a href="/api/iframe?token=<?php echo $request->token ?>&view=contrato-form"><button class="btn btn-xs btn-primary"><i class="fa fa-plus"></i> Novo</button></a></div>
                </div>
               
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group has-feedback">
                                <label class="control-label">Cliente:</label>
                                <input class="form-control input-sm" type="text" name="nome" id="nome" onKeyUp="call_busca_ajax();" placeholder="Informe o nome do cliente..." autocomplete="off" autofocus>
                                <span class="glyphicon glyphicon-search form-control-feedback"></span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Serviço:</label>
                                <select id="cod_servico" name="cod_servico" onchange="call_busca_ajax()" class="form-control input-sm">
                                    <option value=''>Todos</option>
                                    <?php
                                        $dados_plano = DBRead('', 'tb_plano', "GROUP BY cod_servico ORDER BY cod_servico ASC","cod_servico");
                                        if ($dados_plano) {
                                            foreach ($dados_plano as $conteudo) {
                                                $servico_select = getNomeServico($conteudo['cod_servico']);
                                                echo "<option value='".$conteudo['cod_servico']."'>$servico_select</option>";
                                            }
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>                   
                        
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="status">Status:</label>
                                <select id="status" name="status" onchange="call_busca_ajax()" class="form-control input-sm">
                                    <option value='z'>Todos</option>
                                    <?php
                                    $sel_status[$status] = 'selected';
                                    echo "<option value='1'".$sel_status[1].">".getNomeStatusPlano(1)."</option>";
                                    echo "<option value='0'".$sel_status[0].">".getNomeStatusPlano(0)."</option>";
                                    echo "<option value='2'".$sel_status[2].">".getNomeStatusPlano(2)."</option>";
                                    echo "<option value='3'".$sel_status[3].">".getNomeStatusPlano(3)."</option>";
                                    echo "<option value='4'".$sel_status[4].">".getNomeStatusPlano(4)."</option>";
                                    echo "<option value='5'".$sel_status[5].">".getNomeStatusPlano(5)."</option>";
                                    echo "<option value='6'".$sel_status[6].">".getNomeStatusPlano(6)."</option>";
                                    echo "<option value='7'".$sel_status[7].">".getNomeStatusPlano(7)."</option>";
                                    ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Responsável pelo Relacionamento:</label>
                                <select id="id_responsavel" name="id_responsavel" onchange="call_busca_ajax()" class="form-control input-sm">
                                    <option value='z'>Todos</option>
                                    <?php
                                        $dados_responsavel = DBRead('', 'tb_perfil_sistema a', "INNER JOIN tb_usuario b ON a.id_perfil_sistema = b.id_perfil_sistema INNER JOIN tb_pessoa c ON b.id_pessoa = c.id_pessoa WHERE a.id_perfil_sistema = 11 AND b.status = 1 ORDER BY c.nome ASC","b.id_usuario, c.nome");
                                        
                                        if ($dados_responsavel) {
                                            foreach ($dados_responsavel as $conteudo_responsavel) {
                                                echo "<option value='".$conteudo_responsavel['id_usuario']."' >".$conteudo_responsavel['nome']."</option>";
                                            }
                                        }
                                    ?>
                                </select>   
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Responsável Técnico:</label>
                                <select id="id_tecnico" name="id_tecnico" onchange="call_busca_ajax()" class="form-control input-sm">
                                    <option value='z'>Todos</option>
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
                    </div>
                    <hr style="margin: 0px;">
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
        var id_responsavel = $('#id_responsavel').val();
        var id_tecnico = $('#id_tecnico').val();
        var cod_servico = $('#cod_servico').val();

        if (nome.length < inicia_busca && nome.length >=1){
            return false;
        }
        if(pagina === undefined){
            pagina = 1;
        }
        var parametros = {
            'nome': nome,
            'status': status,
            'id_responsavel': id_responsavel,
            'id_tecnico': id_tecnico,
            'cod_servico': cod_servico,
            'pagina': pagina,
        };
        busca_ajax('<?= $request->token ?>' , 'ContratoBusca', 'resultado_busca', parametros);
    }

    $(document).on('click', '.troca_pag', function () {
        call_busca_ajax($(this).attr('atr-pagina'));
    });

    call_busca_ajax();
</script>