<?php
require_once(__DIR__."/../class/System.php");

	$id = (int) $_GET['id_funcionario'];
	$dados = DBRead('', 'tb_funcionario a', "INNER JOIN tb_usuario b ON a.id_usuario = b.id_usuario INNER JOIN tb_pessoa c ON b.id_pessoa = c.id_pessoa WHERE a.id_funcionario = $id", 'a.id_usuario, c.razao_social');

    $nome_pessoa = $dados[0]['razao_social'];

    $dados_periodo = DBRead('', 'tb_funcionario_periodo', "WHERE id_funcionario = $id ORDER BY id_funcionario_periodo DESC");

    $check = 0;
    if ($dados_periodo) {
        foreach ($dados_periodo as $conteudo) {
            if (!$conteudo['data_fim']) {
                $check++;
            }
        }
    }

?>
<div class="row">
    <div class="col-md-8 col-md-offset-2">
        <div class="panel panel-default">
            <div class="panel-heading clearfix">
                <h3 class="panel-title text-left pull-left">Funcionário:</h3>
                <div class="panel-title text-right pull-right">
                    <?php if ($check == 0) { ?>
                    <a href="/api/iframe?token=<?php echo $request->token ?>&view=funcionario-periodo-form&id_funcionario=<?=$id?>&tipo=1">
                        <button class="btn btn-xs btn-success"><i class="fas fa-sign-in-alt"></i> Admissão</button>
                    </a>
                    <?php } ?>
                </div>
            </div>
            <form method="post" action="/api/ajax?class=MonitoriaClassificacaoAtendente.php" id="classificacao_atendente_form" style="margin-bottom: 0;">
		        <input type="hidden" name="token" value="<?php echo $request->token ?>">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>*Nome:</label>
                                <input class="form-control input-sm" id="busca_pessoa" type="text" name="busca_pessoa"  value="<?=$nome_pessoa;?>" autocomplete="off" readonly required>
                                <input type="hidden" name="id_usuario" id="id_usuario" value="<?=$id;?>">
                            </div>
                        </div>
                    </div><br>

                    <div class="row">
                        <div class="col-md-12">
                            <?php if ($dados_periodo) { ?>
                                <div class="table-responsive">
                                    <table class="table table-hover" style="font-size: 14px;">
                                        <thead>
                                            <tr>
                                                <th>Data Início</th>
                                                <th>Data Fim</th>
                                                <th>Formato</th>
                                                <th>Escolaridade</th>
                                                <th>Demissão</th>
                                                <th>Motivo</th>
                                                <th class="col-md-1 text-center">Opções</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                                foreach ($dados_periodo as $conteudo) { 

                                                    $class_tr = '';
                                                    if ($conteudo['data_fim'] == '') {
                                                        $data_fim = '<strong>N/D</strong>';
                                                        $class_tr = 'warning';

                                                    } else {
                                                        $data_fim = converteData($conteudo['data_fim']);
                                                    }

                                                    if ($conteudo['motivo'] == '') {
                                                        $motivo = 'N/D';

                                                    } else {
                                                        $motivo = $conteudo['motivo'];
                                                    }

                                                    if ($conteudo['formato'] == 1) {
                                                        $formato = 'Efetivo';

                                                    } else if ($conteudo['formato'] == 2) {
                                                        $formato = 'Estágio';

                                                    } else if ($conteudo['formato'] == 3) {
                                                        $formato = 'Jovem aprendiz';

                                                    } else if ($conteudo['formato'] == 4) {
                                                        $formato = 'PCD';

                                                    } else if ($conteudo['formato'] == 5) {
                                                        $formato = 'Terceirizado';

                                                    } else if ($conteudo['formato'] == 6) {
                                                        $formato = 'Estágio PCD';
                                                    }

                                                    if ($conteudo['escolaridade'] == 1) {
                                                        $escolaridade = 'Primeiro grau completo';

                                                    } else if ($conteudo['escolaridade'] == 2) {
                                                        $escolaridade = 'Primeiro grau incompleto';

                                                    } else if ($conteudo['escolaridade'] == 3) {
                                                        $escolaridade = 'Segundo grau completo';

                                                    } else if ($conteudo['escolaridade'] == 4) {
                                                        $escolaridade = 'Segundo grau incompleto';

                                                    } else if ($conteudo['escolaridade'] == 5) {
                                                        $escolaridade = 'Superior completo';

                                                    } else if ($conteudo['escolaridade'] == 6) {
                                                        $escolaridade = 'Superior incompleto';

                                                    } else if ($conteudo['escolaridade'] == 7) {
                                                        $escolaridade = 'Pós-graduação em andamento';

                                                    } else if ($conteudo['escolaridade'] == 8) {
                                                        $escolaridade = 'Pós-graduação completa';

                                                    } else if ($conteudo['escolaridade'] == 8) {
                                                        $escolaridade = 'Mestrando';

                                                    } else if ($conteudo['escolaridade'] == 10) {
                                                        $escolaridade = 'Mestre';

                                                    } else if ($conteudo['escolaridade'] == 11) {
                                                        $escolaridade = 'Doutorando';

                                                    }  else if ($conteudo['escolaridade'] == 12) {
                                                        $escolaridade = 'Doutor';

                                                    } else {
                                                        $escolaridade = 'N/D';
                                                    }

                                                    if ($conteudo['demissao'] == 1) {
                                                        $demissao = 'Dispensado';

                                                    } else if ($conteudo['demissao'] == 2) {
                                                        $demissao = 'Pedido';

                                                    } else if ($conteudo['demissao'] == 3) {
                                                        $demissao = 'Fim de contrato';

                                                    } else {
                                                        $demissao = 'N/D';
                                                    }
                                            ?>
                                            <tr class="<?=$class_tr?>">
                                                <td><?= converteData($conteudo['data_inicio']) ?></td>
                                                <td><?= $data_fim ?></td>
                                                <td><?= $formato ?></td>
                                                <td><?= $escolaridade ?></td>
                                                <td><?= $demissao ?></td>
                                                <td><?= $motivo ?></td>
                                                <td class="text-center">
                                                    <a href="/api/iframe?token=<?php echo $request->token ?>&view=funcionario-periodo-form&alterar=<?=$conteudo['id_funcionario_periodo']?>" title="alterar"><i class="fa fa-pencil"></i>
                                                    </a>&nbsp&nbsp
                                                    <a href="class/Funcionario.php?excluir_periodo=<?=$conteudo['id_funcionario_periodo']?>&id_funcionario=<?=$id?>" title='Excluir' onclick="if (!confirm('Excluir?')) { return false; } else { modalAguarde(); }">
                                                        <i class='fa fa-trash' style='color:#b92c28;'></i>
                                                    </a>
                                                </td>
                                            </tr>
                                            <?php 
                                                }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php } else { ?>
                                <p class='alert alert-warning' style='text-align: center'>
                                    Não foram encontrados registros!
                                </p>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <div class="panel-footer">
                </div>
            </form>
        </div>
    </div>
</div>
