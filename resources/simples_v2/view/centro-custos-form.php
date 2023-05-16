<?php
require_once(__DIR__."/../class/System.php");

if (isset($_GET['alterar'])) {
    $tituloPainel = 'Alterar';
    $operacao = 'alterar';
    $id = (int)$_GET['alterar'];

    $dados = DBRead('', 'tb_centro_custos', "WHERE id_centro_custos = $id");
    $nome = $dados[0]['nome'];
    $id_usuario_responsavel = $dados[0]['id_usuario_responsavel'];
    $status = $dados[0]['status'];
    $disabled = 'disabled';
}else{
    $tituloPainel = 'Inserir';
    $operacao = 'inserir';
    $id = 1;
    $nome = '';
    $id_usuario_responsavel = '';
    $status = '';
}
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    <h3 class="panel-title text-left pull-left"><?= $tituloPainel ?> Centro de Custo:</h3>
                    <?php
                        if (isset($_GET['alterar'])) { echo "<div class=\"panel-title text-right pull-right\"><a href=\"/api/ajax?class=CentroCustos.php?excluir= $id&token=". $request->token ."\" onclick=\"if (!confirm('Tem certeza que deseja excluir o registro?')) { return false; } else { modalAguarde(); }\"><button class=\"btn btn-xs btn-danger\"><i class=\"fa fa-trash\"></i> Excluir</button></a></div>";
                        } 

                    ?>
                </div>
                <form method="post" action="/api/ajax?class=CentroCustos.php" id="centro_custo_form" style="margin-bottom: 0;">
                    <input type="hidden" name="token" value="<?php echo $request->token ?>">
                    <div class="panel-body" style="padding-bottom: 0;">
						<div class="row">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label>*Nome:</label>
                                    <input name="nome" id="nome" type="text" class="form-control input-sm" value="<?=$nome;?>" autocomplete="off" required <?= $disabled ?> />
                                </div>
                            </div>
							<div class="col-md-4">
                                <div class="form-group">
                                    <label>*Responsável:</label>
                                    <select name="id_usuario_responsavel" class="form-control input-sm">
                                        <option value=""></option>
                                            <?php
                                            $sel_usuario[$id_usuario_responsavel] = 'selected';
                                            $dados_usuarios = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.status = '1' ORDER BY b.nome ASC", "a.id_usuario, b.nome");
                                            if ($dados_usuarios) {
                                                foreach ($dados_usuarios as $conteudo_usuarios) {
                                                    $selected = $id_usuario_responsavel == $conteudo_usuarios['id_usuario'] ? "selected" : "";
                                                    echo "<option value='" . $conteudo_usuarios['id_usuario'] . "' " . $selected . ">" . $conteudo_usuarios['nome'] . "</option>";
                                                }
                                            }
                                            ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group" >
                                    <label>*Status:</label>
                                    <select name="status" class="form-control input-sm">
                                        <option value="1" <?php if($status == '1'){echo 'selected';}?>>Ativo</option>
                                        <option value="0" <?php if($status == '0'){echo 'selected';}?>>Inativo</option>
                                    </select>
                                </div>
                            </div>
						</div>
                    </div>
                    <div class="panel-footer">
                        <div class="row">
                            <div class="col-md-12" style="text-align: center">
                                <input type="hidden" id="operacao" value="<?= $id; ?>" name="<?= $operacao; ?>"/>
                                <button class="btn btn-primary" name="salvar" id="ok" type="submit"><i class="fa fa-floppy-o"></i> Salvar</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>     
<script>

    $(document).on('submit', '#centro_custo_form', function () {
        modalAguarde();
    });

</script>