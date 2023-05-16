<?php
require_once(__DIR__."/../class/System.php");

	$tituloPainel = 'Alterar';
	$id = (int) $_GET['alterar'];
	$dados = DBRead('', 'tb_usuario', "WHERE id_usuario = $id");
	$id_pessoa = $dados[0]['id_pessoa'];
	$id_perfil_sistema = $dados[0]['id_perfil_sistema'];
	$dados_pessoa = DBRead('', 'tb_pessoa', "WHERE id_pessoa = '$id_pessoa'");
    $nome_pessoa = $dados_pessoa[0]['nome'];

    $dados_classificacao = DBRead('', 'tb_monitoria_classificacao_usuario', "WHERE id_usuario = $id");

    if ($dados_classificacao) {
        $id_monitoria_classificacao_usuario = $dados_classificacao[0]['id_monitoria_classificacao_usuario'];
        $classificacao = $dados_classificacao[0]['tipo_classificacao'];
        $voz = $dados_classificacao[0]['voz'];
        $texto = $dados_classificacao[0]['texto'];
        $analista_telefone = $dados_classificacao[0]['id_analista_telefone'];
        $analista_texto = $dados_classificacao[0]['id_analista_texto'];

        $operacao = 'alterar';
        
    } else {
        $operacao = 'inserir';
        $id_monitoria_classificacao_usuario = 1;
    }

    $dados_analistas = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.status = 1 AND a.id_perfil_sistema = 14 ORDER BY nome ASC", "a.id_usuario, b.nome");

?>
<div class="row">
    <div class="col-md-6 col-md-offset-3">
        <div class="panel panel-default">
            <div class="panel-heading clearfix">
                <h3 class="panel-title text-left pull-left"><?=$tituloPainel?> Classificação Atendente:</h3>
            </div>
            <form method="post" action="/api/ajax?class=MonitoriaClassificacaoAtendente.php" id="classificacao_atendente_form" style="margin-bottom: 0;">
		        <input type="hidden" name="token" value="<?php echo $request->token ?>">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>*Atendente:</label>
                                <input class="form-control input-sm" id="busca_pessoa" type="text" name="busca_pessoa"  value="<?=$nome_pessoa;?>" placeholder="Informe o nome ou CPF/CNPJ..." autocomplete="off" readonly required>
                                <input type="hidden" name="id_usuario" id="id_usuario" value="<?=$id;?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>*Classificação:</label>
                                <select class="form-control input-sm" name="classificacao" id="classificacao">
                                    <?php 
                                        if ($id_perfil_sistema == 15) {
                                            $sel_classificacao[3] = 'selected';
                                    ?>
                                            <option value="3" <?= $sel_classificacao['3']?>>Efetivado</option>
                                    <?php 
                                        } else {
                                            $sel_classificacao[$classificacao] = 'selected';
                                    ?>
                                            <option value="" <?= $sel_classificacao['']?>>Selecione</option>
                                            <option value="1" <?= $sel_classificacao['1']?>>Em treinamento</option>
                                            <option value="2" <?= $sel_classificacao['2']?>>Período de experiência</option>
                                            <option value="3" <?= $sel_classificacao['3']?>>Efetivado</option>
                                    <?php 
                                        }
                                    ?>
                                   
                                </select>
                            </div>
                        </div>   
                    </div>
                    <label>Monitorar atendimento via:</label>
                    <div class="row">
                        <div class="col-md-6">
                            <?php if ($id_perfil_sistema != 15) { ?>
                            <div class="input-group">
                                <?php
                                    if ($voz == 1) {
                                        $voz_checked = 'checked';
                                    } else {
                                        $voz_checked = '';
                                    }
                                ?>
                                <span class="input-group-addon">
                                    <input type="checkbox" name="voz" id="voz" value="1" <?= $voz_checked ?>>
                                </span>
                                <input type="text" class="form-control" aria-label="..." disabled="" value="Telefone" style="cursor: context-menu; background-color: white;" >
                            </div>
                            <?php } else { ?>
                                <div class="input-group">
                                    <span class="input-group-addon" style="min-width: 40px;">
                                        
                                    </span>
                                    <input type="text" class="form-control" aria-label="..." disabled="" value="Telefone" style="cursor: context-menu; background-color: white;" >
                                </div>
                            <?php } ?>
                        </div>
                        <div class="col-md-6">
                            <div class="input-group">
                                <?php
                                    if ($texto == 1) {
                                        $texto_checked = 'checked';
                                    } else {
                                        $texto_checked = '';
                                    }
                                ?>
                                <span class="input-group-addon">
                                    <input type="checkbox" name="texto" id="texto" value="1" <?= $texto_checked ?>>
                                </span>
                                <input type="text" class="form-control" aria-label="..." disabled="" value="Texto" style="cursor: context-menu; background-color: white;" >
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="row">                 
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>*Analista para Telefone:</label>
                                <select class="form-control input-sm" id="analista_telefone" name="analista_telefone" require="">
                                    <option value="">Selecione</option>
                                    <?php
                                        if ($dados_analistas) {
                                            foreach ($dados_analistas as $conteudo_analista) {
                                                $selected = $analista_telefone == $conteudo_analista['id_usuario'] ? "selected" : "";
                                                echo "<option value='" . $conteudo_analista['id_usuario'] . "' ".$selected.">" . $conteudo_analista['nome'] . "</option>";
                                            }
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>*Analista para Texto:</label>
                                <select class="form-control input-sm" name="analista_texto" id="analista_texto" require="">
                                    <option value="">Selecione</option>
                                    <?php
                                        if ($dados_analistas) {
                                            foreach ($dados_analistas as $conteudo_analista) {
                                                $selected = $analista_texto == $conteudo_analista['id_usuario'] ? "selected" : "";
                                                echo "<option value='" . $conteudo_analista['id_usuario'] . "' ".$selected.">" . $conteudo_analista['nome'] . "</option>";
                                            }
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div><br>
                <div class="panel-footer">
                    <div class="row">
                        <div class="col-md-12" style="text-align: center">
                            <input type="hidden" id="operacao" value="<?= $id_monitoria_classificacao_usuario; ?>" name="<?= $operacao; ?>"/>
                            <button class="btn btn-primary" name="salvar" id="ok" type="submit"><i class="fa fa-floppy-o"></i> Salvar</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).on('submit', '#classificacao_atendente_form', function () {
        
        var classificacao = $('#classificacao').val();
        if (classificacao == '') {
            alert('Selecione uma classificação!');
            return false;
        }

        /* var analista_telefone = $('#analista_telefone').val();
        if (analista_telefone == '') {
            alert('Selecione um analista para telefone!');
            return false;
        }

        var analista_texto = $('#analista_texto').val();
        if (analista_texto == '') {
            alert('Selecione um analista para texto!');
            return false;
        } */
        
        modalAguarde();
    });
</script>