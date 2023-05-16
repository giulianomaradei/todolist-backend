<?php
require_once(__DIR__."/../class/System.php");

$id = (int) $_GET['idtreinamento'];

$dados = DBRead('', 'tb_treinamento', "WHERE id_treinamento = $id");

$data_avaliacao = $dados[0]['avaliar_em'];

if ($dados[0]['objetivo'] == 1) {
    $objetivo = 'Qualificação';

} elseif ($dados[0]['objetivo'] == 2) {
    $objetivo = 'Reciclagem';
}

$responsaveis = DBRead('', 'tb_treinamento_responsavel a', "INNER JOIN tb_usuario b ON a.id_usuario = b.id_usuario INNER JOIN tb_pessoa c ON b.id_pessoa = c.id_pessoa WHERE id_treinamento = $id", 'a.id_usuario, c.nome');

$verifica_responsaveis = array();

$nomes_responsaveis = '';
foreach ($responsaveis as $conteudo) {
    $nomes_responsaveis .= $conteudo['nome'] . ';<br>';
    array_push($verifica_responsaveis, $conteudo['id_usuario']);
}

$setores = DBRead('', 'tb_treinamento_perfil_sistema a', "INNER JOIN tb_perfil_sistema b ON a.id_perfil_sistema = b.id_perfil_sistema WHERE id_treinamento = $id");

$nomes_setores = '';
foreach ($setores as $conteudo) {
    $nomes_setores .= $conteudo['nome'] . ';<br>';
}

$participantes = DBRead('', 'tb_treinamento_participante', "WHERE id_treinamento = '" . $id . "' ", 'id_usuario');
$verifica_participantes = array();

foreach ($participantes as $conteudo) {
    array_push($verifica_participantes, $conteudo['id_usuario']);
}

$cont = sizeof($participantes);

$usuarios = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.status = 1 ORDER BY b.nome ASC", "b.nome, a.id_usuario");

?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    <h3 class="panel-title text-left pull-left">Informações do treinamento:</h3>
                    <div class="panel-title text-right pull-right">
                        <a>
                            <button class="btn btn-xs btn-primary" data-toggle="modal" data-target="#modal_participante">
                                <i class="fa fa-plus"></i> Adicionar participante
                            </button>
                        </a>
                        <a>
                            <button class="btn btn-xs btn-primary" data-toggle="modal" data-target="#modal_responsavel">
                                <i class="fa fa-plus"></i> Adicionar responsável
                            </button>
                        </a>
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
                                        <td class="td-table"><strong>Objetivo:</strong></td>
                                        <td><?= $objetivo ?></td>
                                    </tr>
                                    <tr>
                                        <td class="td-table"><strong>Carga horária:</strong></td>
                                        <td><?= converteSegundosHoras($dados[0]['carga_horaria'] * 60) ?></td>
                                    </tr>
                                    <tr>
                                        <td class="td-table"><strong>Responsável(eis):</strong></td>
                                        <td><?= $nomes_responsaveis ?></td>
                                    </tr>
                                    <tr>
                                        <td class="td-table"><strong>Setor(res):</strong></td>
                                        <td><?= $nomes_setores ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div><!-- end col -->

                        <div class="col-md-6">
                            <table class="table table-striped">
                                <tbody><br>
                                    <tr>
                                        <td class="td-table"><strong>Descrição:</strong></td>
                                        <td><?= $dados[0]['descricao'] ?></td>
                                    </tr>
                                    <tr>
                                        <td class="td-table"><strong>Data início:</strong></td>
                                        <td><?= converteDataHora($dados[0]['data_inicio']) ?></td>
                                    </tr>
                                    <tr>
                                        <td class="td-table"><strong>Data fim:</strong></td>
                                        <td><?= converteDataHora($dados[0]['data_fim']) ?></td>
                                    </tr>
                                    <tr>
                                        <td class="td-table"><strong>Avaliar em:</strong></td>
                                        <td><?= converteData($dados[0]['avaliar_em']) ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div><!-- end col -->
                    </div><!-- end row -->
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
                                <label class="">Status:</label>
                                <select class="form-control" name="status" id="status" onChange="call_busca_ajax();">
                                    <option value="">Todos</option>
                                    <option value="1">Avaliados</option>
                                    <option value="2">Não avaliados</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group" style="margin-bottom: 1px;">
                                <label class="">Avaliação:</label>
                                <select class="form-control" name="avaliacao" id="avaliacao" onChange="call_busca_ajax();">
                                    <option value="">Todos</option>
                                    <option value="1">Eficaz</option>
                                    <option value="2">Ineficaz</option>
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
                                <strong>Quantidade de participantes:</strong>
                                <span class="label label-default" style="font-size: 15px;"><?= $cont ?></span>
                            </span>
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

<!-- Modal responsavel -->
<div class="modal fade" id="modal_responsavel" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Adicionar responsável</h4>
            </div>
            <form method="post" action="/api/ajax?class=Treinamento.php" id="form_responsavel" style="margin-bottom: 0;">
		        <input type="hidden" name="token" value="<?php echo $request->token ?>">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="responsavel">*Responsável:</label>
                                <select class="form-control" name="responsavel" id="responsavel">
                                    <option value="">Selecione</option>
                                    <?php foreach ($usuarios as $conteudo) : ?>

                                        <?php if (!in_array($conteudo['id_usuario'], $verifica_responsaveis)) : ?>
                                            <option value="<?= $conteudo['id_usuario'] ?>"><?= $conteudo['nome'] ?></option>
                                        <?php endif ?>

                                    <?php endforeach ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" id="<?= $id ?>" value="<?= $id ?>" name="adicionar_responsavel" />
                    <button type="submit" class="btn btn-primary">Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal particpante -->
<div class="modal fade" id="modal_participante" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Adicionar particpante</h4>
            </div>
            <form method="post" action="/api/ajax?class=Treinamento.php" id="form_participante" style="margin-bottom: 0;">
		        <input type="hidden" name="token" value="<?php echo $request->token ?>">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="participante">*Participante:</label>
                                <select class="form-control" name="participante" id="participante">
                                    <option value="">Selecione</option>
                                    <?php foreach ($usuarios as $conteudo) : ?>
                                        <?php if (!in_array($conteudo['id_usuario'], $verifica_participantes)) : ?>
                                            <option value="<?= $conteudo['id_usuario'] ?>"><?= $conteudo['nome'] ?></option>
                                        <?php endif ?>
                                    <?php endforeach ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" id="" value="<?= $id ?>" name="adicionar_participante" />
                    <button type="submit" class="btn btn-primary">Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function call_busca_ajax(pagina) {
        var inicia_busca = 1;
        var nome = $('#nome').val();
        var status = $('#status').val();
        var avaliacao = $('#avaliacao').val();
        var idselecao = '<?php echo $id ?>';
        var avaliar_em = '<?php echo $data_avaliacao ?>';

        if (pagina === undefined) {
            pagina = 1;
        }
        var parametros = {
            'nome': nome,
            'pagina': pagina,
            'idtreinamento': idselecao,
            'status': status,
            'avaliar_em': avaliar_em,
            'avaliacao': avaliacao
        };
        busca_ajax('<?= $request->token ?>' , 'TreinamentoParticipanteBusca', 'resultado_busca', parametros);
    }

    $(document).on('click', '.troca_pag', function() {
        call_busca_ajax($(this).attr('atr-pagina'));
    });

    call_busca_ajax();

    $('#btn-excluir').on('click', function() {

        var id_treinamento = '<?php echo $id ?>';

        if (confirm('Deseja excluir esta seleção?')) {
            $.ajax({
                cache: false,
                type: "POST",
                url: '/api/ajax?class=TreinamentoAjax.php',
                data: {
                    acao: 'excluir',
                    parametros: {
                        id_treinamento: id_treinamento
                    },
                    token: '<?= $request->token ?>'
                },
                success: function(data) {
                    window.location.replace("/api/iframe?token=<?php echo $request->token ?>&view=treinamento-busca");
                }
            });
        }
    });

    $(document).on('submit', '#form_participante', function() {
        var participante = $('#participante').val();

        if (participante == '') {
            alert('Selecione um participante!');
            return false;
        }

        modalAguarde();
    });

    $(document).on('submit', '#form_responsavel', function() {
        var responsavel = $('#responsavel').val();

        if (responsavel == '') {
            alert('Selecione um responsável!');
            return false;
        }

        modalAguarde();
    });
</script>