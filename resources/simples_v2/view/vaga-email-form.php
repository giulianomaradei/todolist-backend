<?php
require_once(__DIR__."/../class/System.php");

if (isset($_GET['id_vaga'])) {

    $id = (int) $_GET['id_vaga'];      

    $dados_vaga = DBRead('', 'tb_vaga a', "INNER JOIN tb_cargo b ON a.id_cargo = b.id_cargo INNER JOIN tb_setor c ON c.id_setor = b.id_setor WHERE id_vaga = $id ", 'a.*, b.descricao as descricao_cargo, c.descricao as descricao_setor, c.id_setor');
}

?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    <h3 class="panel-title text-left pull-left">Divulgar vaga por email:</h3>
                </div>
                <form method="post" action="/api/ajax?class=VagaEmail.php" id="vaga_email_form" style="margin-bottom: 0;">
		            <input type="hidden" name="token" value="<?php echo $request->token ?>">
                    <div class="panel-body" style="padding-bottom: 0;">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Divulgar para:</label>
                                    <select class="form-control" name="candidatos" id="candidatos">
                                        <option value="">Selecione</option>
                                        <option value="1">Todos</option>
                                        <option value="2">Candidatos do setor</option>
                                        <option value="3">Candidatos do cargo</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="panel panel-info">
                                    <div class="panel-heading clearfix">
                                        <h3 class="panel-title text-left pull-left" style="margin-top: 2px;">
                                            <i class="fa fa-info"></i> Informações da vaga
                                        </h3>
                                        <div class="panel-title text-right pull-right">
                                            <button data-toggle="collapse" data-target="#accordionRedes" class="btn btn-xs btn-info" type="button" title="Visualizar informações" aria-expanded="true">
                                                <i id="i_collapseRedes" class="fa fa-plus"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div id="accordionRedes" class="panel-collapse collapse" aria-expanded="true"> 
                                        <div class="panel-body">	                		
                                            <div class="row">
                                                <div class="col-md-6" style="margin-left: -5px;">
                                                    <br><table class="table table-striped">
                                                        <tbody>
                                                            <tr>
                                                                <td class="td-table"><strong>Setor:</strong></td>
                                                                <td><?= $dados_vaga[0]['descricao_setor'] ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td class="td-table"><strong>Data início:</strong></td>
                                                                <td><?= converteData($dados_vaga[0]['data_inicio']) ?></td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <div class="col-md-6" style="margin-left: -5px;">
                                                    <br><table class="table table-striped">
                                                        <tbody>
                                                            <tr>
                                                                <td class="td-table"><strong>Cargo:</strong></td>
                                                                <td><?= $dados_vaga[0]['descricao_cargo'] ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td class="td-table"><strong>Data fim:</strong></td>
                                                                <td><?= converteData($dados_vaga[0]['data_fim']) ?></td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12" style="margin-left: -5px;">
                                                    <br><table class="table table-striped">
                                                        <tbody>
                                                                <tr>
                                                                    <td class="td-table"><strong>Descrição:</strong></td>
                                                                    <td></td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="td-table">
                                                                        <?= nl2br($dados_vaga[0]['descricao']) ?>
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel-footer">
                        <div class="row">
                            <div class="col-md-4">
                            </div>
                            <div class="col-md-4" style="text-align: center">
                                <input type="hidden" id="operacao" value="<?= $id; ?>" name="enviar_email" />
                                <button class="btn btn-primary" name="salvar" id="ok" type="submit"><i class="fas fa-envelope-open-text"></i> Enviar email</button>
                            </div>
                            <div class="col-md-4">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>

    $(document).on('submit', '#vaga_email_form', function() {

        var candidatos = $('#candidatos').val();

        if (candidatos == '') {
            alert('Informe para quem a vaga será divulgada!');
            return false;
        }

        modalAguarde();
    });

    $('#accordionRedes').on('shown.bs.collapse', function () {
       $("#i_collapseRedes").removeClass("fa fa-plus").addClass("fa fa-minus");
    });
    $('#accordionRedes').on('hidden.bs.collapse', function () {
       $("#i_collapseRedes").removeClass("fa fa-minus").addClass("fa fa-plus");
    });

</script>