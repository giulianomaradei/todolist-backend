<?php
require_once(__DIR__."/../class/System.php");

$id = (int) $_GET['idselecao'];

$dados = DBRead('', 'tb_selecao a', "INNER JOIN tb_setor b ON a.id_setor = b.id_setor INNER JOIN tb_cargo c ON a.id_cargo = c.id_cargo WHERE id_selecao = $id", 'a.*, a.descricao as descricao_selecao, b.descricao as descricao_setor, c.descricao as descricao_cargo');

if ($dados[0]['status'] == 1) {
    $status = 'Em andamento';
    $class = 'btn-success';
    $legenda = 'Encerrar seleção';
    $icone = 'fas fa-check';
    $selected = '';
    $style = "style='display: inline-block;'";
} else if ($dados[0]['status'] == 2) {
    $status = 'Encerrada';
    $class = "btn-warning";
    $legenda = 'Reabrir seleção';
    $icone = 'fas fa-circle-notch';
    $selected = 'selected';
    $style = "style='display: none;'";
}

$avaliadores = DBRead('', 'tb_selecao a', "INNER JOIN tb_selecao_etapa b ON a.id_selecao = b.id_selecao INNER JOIN tb_selecao_etapa_avaliador c ON b.id_selecao_etapa = c.id_selecao_etapa INNER JOIN tb_usuario d ON d.id_usuario = c.id_usuario_avaliador INNER JOIN tb_pessoa e ON d.id_pessoa = e.id_pessoa WHERE a.id_selecao = $id", 'e.nome, d.id_usuario');

$array_nomes = array();
foreach ($avaliadores as $conteudo) {
    if (!in_array($conteudo['nome'], $array_nomes)) {
        array_push($array_nomes, $conteudo['nome']);
    }
}

$envolvidos = '';
foreach ($array_nomes as $conteudo_nomes) {
    $envolvidos .= $conteudo_nomes . ';<br>';
}

$candidatos = DBRead('', 'tb_selecao_candidato', "WHERE id_selecao = '" . $id . "' ", 'COUNT(*) AS cont');

?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    <h3 class="panel-title text-left pull-left">Informações da seleção:</h3>
                    <div class="panel-title text-right pull-right">
                        <a href="/api/iframe?token=<?php echo $request->token ?>&view=selecao-candidato-form&idselecao=<?= $id ?>">
                            <button class="btn btn-xs btn-primary" <?= $style ?>>
                                <i class="fa fa-plus"></i> Adicionar candidatos
                            </button>
                        </a>
                        <a href="/api/iframe?token=<?php echo $request->token ?>&view=selecao-avaliador-form&idselecao=<?= $id ?>">
                            <button class="btn btn-xs btn-info" <?= $style ?>>
                                <i class="fa fa-plus"></i> Adicionar avaliadores
                            </button>
                        </a>
                        <button class="btn btn-xs <?= $class ?>" id="encerrar_selecao">
                            <i class="<?= $icone ?>"></i> <?= $legenda ?>
                        </button>
                        <button class="btn btn-xs btn-danger" id="btn-excluir">
                            <i class="fa fa-close"></i> Excluir
                        </button>
                    </div>
                </div>
                <div class="panel-body" style="padding-bottom: 0;">
                    <div class="row">
                        <div class="col-md-6" style="margin-left: -5px;">
                            <table class="table table-striped">
                                <tbody><br>
                                    <tr>
                                        <td class="td-table"><strong>Nome:</strong></td>
                                        <td><?= $dados[0]['nome'] ?></td>
                                    </tr>
                                    <tr>
                                        <td class="td-table"><strong>Setor:</strong></td>
                                        <td><?= $dados[0]['descricao_setor'] ?></td>
                                    </tr>
                                    <tr>
                                        <td class="td-table"><strong>Número de etapas:</strong></td>
                                        <td><?= $dados[0]['n_etapas'] ?></td>
                                    </tr>
                                    <tr>
                                        <td class="td-table"><strong>Avaliadores:</strong></td>
                                        <td><?= $envolvidos ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div><!-- end col -->

                        <div class="col-md-6">
                            <table class="table table-striped">
                                <tbody><br>
                                    <tr>
                                        <td class="td-table"><strong>Descrição:</strong></td>
                                        <td><?= $dados[0]['descricao_selecao'] ?></td>
                                    </tr>
                                    <tr>
                                        <td class="td-table"><strong>Data:</strong></td>
                                        <td><?= converteDataHora($dados[0]['data']) ?></td>
                                    </tr>
                                    <tr>
                                        <td class="td-table"><strong>Cargo:</strong></td>
                                        <td><?= $dados[0]['descricao_cargo'] ?></td>
                                    </tr>
                                    <tr>
                                        <td class="td-table"><strong>Número de vagas:</strong></td>
                                        <td><?= $dados[0]['n_vagas'] ?></td>
                                    </tr>
                                    <tr>
                                        <td class="td-table"><strong>Status:</strong></td>
                                        <td><?= $status ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div><!-- end col -->
                    </div><!-- end row -->
                    <hr>
                    
                    <?php if ($dados[0]['id_vaga'] != null) { 
                            
                            $dados_vaga = DBRead('', 'tb_vaga a', "INNER JOIN tb_cargo b ON a.id_cargo = b.id_cargo INNER JOIN tb_setor c ON b.id_setor = c.id_setor WHERE id_vaga = ".$dados[0]['id_vaga']." ", 'a.*, a.descricao as descricao_vaga,  b.descricao as cargo, c.descricao as setor');

                            $tipo = $dados_vaga[0]['tipo'];

                            if ($tipo == 1) {
                                $formato = 'Efetivo';
                    
                            } else if ($tipo == 2) {
                                $formato = 'Estágio';
                            
                            } else if ($tipo == 3) {
                                $formato = 'Jovem aprendiz';
                                
                            } else if ($tipo == 4) {
                                $formato = 'PCD';
                    
                            } else if ($tipo == 5) {
                                $formato = 'Terceirizado';
                    
                            } else if ($tipo == 4) {
                                $formato = 'Estágio PCD';
                            }

                            if ($dados_vaga[0]['divulgado'] == 1) {
                                $divulgado = "Sim";

                            } else {
                                $divulgado = "Não";
                            }
                    ?>
                        <div class="panel panel-info">
                            <div class="panel-heading clearfix">
                                <h3 class="panel-title text-left pull-left" style="margin-top: 2px;">
                                    <i class="fa fa-info-circle"></i> Informações da vaga
                                </h3>
                                <div class="panel-title text-right pull-right">
                                    <button data-toggle="collapse" data-target="#accordionVaga" class="btn btn-xs btn-info" type="button" title="Visualizar filtros" aria-expanded="false">
                                        <i id="i_collapseRedes" class="fa fa-plus"></i>
                                    </button>
                                </div>
                            </div>
                            <div id="accordionVaga" class="panel-collapse collapse" aria-expanded="true">
                                <div class="panel-body">	                		
                                   <div class="row">
                                        <div class="col-md-6" style="margin-left: -5px;"><br>
                                            <table class="table table-striped">
                                                <tbody>
                                                    <tr>
                                                        <td class="td-table"><strong>Setor:</strong></td>
                                                        <td><?= $dados_vaga[0]['setor'] ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="td-table"><strong>Data início:</strong></td>
                                                        <td><?= converteData($dados_vaga[0]['data_inicio']) ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="td-table"><strong>Formato:</strong></td>
                                                        <td><?= $formato ?></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="col-md-6" style="margin-left: -5px;"><br>
                                            <table class="table table-striped">
                                                <tbody>
                                                    <tr>
                                                        <td class="td-table"><strong>Cargo:</strong></td>
                                                        <td><?= $dados_vaga[0]['cargo'] ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="td-table"><strong>Data fim:</strong></td>
                                                        <td><?= converteData($dados_vaga[0]['data_fim']) ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="td-table"><strong>Divulgado por email:</strong></td>
                                                        <td><?= $divulgado ?></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                   </div>
                                   <div class="row">
                                       <div class="col-md-12">
                                           <span><?= nl2br($dados_vaga[0]['descricao_vaga']) ?></span>
                                       </div>
                                   </div>
                                </div>
                            </div>
                        </div>
                        
                    <?php } ?>

                    <hr>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group has-feedback" style="margin-bottom: 1px;">
                                <label class="">Nome:</label>
                                <input class="form-control" type="text" name="nome" id="nome" onKeyUp="call_busca_ajax();" placeholder="Informe o nome do candidato" autocomplete="off" autofocus>
                                <span class="glyphicon glyphicon-search form-control-feedback"></span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group" style="margin-bottom: 1px;">
                                <label class="">Etapa:</label>
                                <select class="form-control" name="etapa" id="etapa" onChange="call_busca_ajax();">
                                    <option value="">Todas</option>
                                    <?php
                                    for ($i = 1; $i <= $dados[0]['n_etapas']; $i++) {
                                    ?>
                                        <option value="<?= $i ?>"><?= $i ?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group" style="margin-bottom: 1px;">
                                <label class="">Status:</label>
                                <select class="form-control" name="status" id="status" onChange="call_busca_ajax();">
                                    <option value="">Todos</option>
                                    <option value="2" <?= $selected ?>>Aprovado</option>
                                    <option value="1">Em seleção</option>
                                    <option value="4">Não compareceu</option>
                                    <option value="5">Pré-aprovado</option>
                                    <option value="3">Reprovado</option>
                                </select>
                            </div>
                        </div>
                        <style>
                            .parent {
                                height: 40px !important;
                            }

                            .child {
                                line-height: 80px !important;
                                vertical-align: middle !important;
                            }
                        </style>
                        <div class="col-md-3 text-center parent" style="display: table;">
                            <span class="child" style="font-size: 15px;">
                                <strong>Quantidade de candidatos:</strong> 
                                <span class="label label-default" style="font-size: 15px;"><?= $candidatos[0]['cont'] ?></span>
                            </span>
                        </div>
                    </div>
                    <hr>

                    <div class="row">
                        <div class="col-md-12">
                            <div id="resultado_busca"></div>
                        </div>
                    </div>

                    <hr>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function call_busca_ajax(pagina) {
        var inicia_busca = 1;
        var nome = $('#nome').val();
        var etapa = $('#etapa').val();
        var status = $('#status').val();
        var idselecao = '<?php echo $id ?>';

        if (pagina === undefined) {
            pagina = 1;
        }
        var parametros = {
            'nome': nome,
            'pagina': pagina,
            'idselecao': idselecao,
            'etapa': etapa,
            'status': status
        };
        busca_ajax('<?= $request->token ?>' , 'SelecaoCandidatoBusca', 'resultado_busca', parametros);
    }

    $(document).on('click', '.troca_pag', function() {
        call_busca_ajax($(this).attr('atr-pagina'));
    });

    call_busca_ajax();

    $('#encerrar_selecao').on('click', function() {
        btn = $(this);

        $.ajax({
            cache: false,
            type: "POST",
            data: {
                acao: 'encerrar_selecao',
                parametros: {
                    id_selecao: '<?php echo $id ?>'
                },
                token: '<?= $request->token ?>'
            },
            url: '/api/ajax?class=SelecaoAjax.php',
            success: function(data) {
                location.reload();
            }
        });
    });

    $('#btn-excluir').on('click', function() {

        if (confirm('Deseja excluir esta seleção?')) {
            $.ajax({
                cache: false,
                type: "POST",
                url: '/api/ajax?class=SelecaoAjax.php',
                data: {
                    acao: 'excluir_selecao',
                    parametros: {
                        id_selecao: '<?php echo $id ?>'
                    },
                    token: '<?= $request->token ?>'
                },
                success: function(data) {
<<<<<<< HEAD
                    window.location.replace("https://homologa.bellunotec.com.br/v2//api/iframe?token=<?php echo $request->token ?>&view=selecao-busca");
=======
                   window.location.href = "/api/iframe?token=<?php echo $request->token ?>&view=selecao-busca";
>>>>>>> 5cfe18e1256c22b2f7585786435771d80b84680b
                }
            });
        }
    });

    $('#accordionVaga').on('shown.bs.collapse', function () {
       $("#i_collapseVaga").removeClass("fa fa-plus").addClass("fa fa-minus");
    });
    $('#accordionVaga').on('hidden.bs.collapse', function () {
       $("#i_collapseVaga").removeClass("fa fa-minus").addClass("fa fa-plus");
    });
</script>