<?php
require_once(__DIR__."/../class/System.php");

if (isset($_GET['alterar'])) {
    $tituloPainel = 'Alterar';
    $operacao = 'alterar';
    $id = (int)$_GET['alterar'];

    $dados = DBRead('', 'tb_natureza_financeira_agrupador', "WHERE id_natureza_financeira_agrupador = $id");
    $nome = $dados[0]['nome'];
    $status = $dados[0]['status'];
    $tipo = $dados[0]['tipo'];
    $disabled = 'disabled';
}else{
    $tituloPainel = 'Inserir';
    $operacao = 'inserir';
    $id = 1;
    $nome = '';
}
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    <h3 class="panel-title text-left pull-left"><?= $tituloPainel ?> Agrupador de Natureza Financeira:</h3>
                    <?php
                        if (isset($_GET['alterar'])) { echo "<div class=\"panel-title text-right pull-right\"><a  href=\"/api/ajax?class=NaturezaFinanceiraAgrupador.php?excluir= $id&token=". $request->token ."\" onclick=\"if (!confirm('Tem certeza que deseja excluir o registro?')) { return false; } else { modalAguarde(); }\"><button class=\"btn btn-xs btn-danger\"><i class=\"fa fa-trash\"></i> Excluir</button></a></div>";
                        } 

                    ?>
                </div>
                <form method="post" action="/api/ajax?class=NaturezaFinanceiraAgrupador.php" id="natureza_financeira_agrupador" style="margin-bottom: 0;">
		            <input type="hidden" name="token" value="<?php echo $request->token ?>">
                    <div class="panel-body" style="padding-bottom: 0;">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>*Nome:</label>
                                    <input name="nome" id="nome" type="text" class="form-control input-sm" value="<?=$nome;?>" autocomplete="off" required <?= $disabled ?>/>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>*Tipo:</label>
                                    <select name="tipo" class="form-control input-sm" <?= $disabled ?>>
                                        <option value="conta_receber" <?php if($tipo == 'conta_receber'){echo 'selected';}?>>Contas a Receber</option>
                                        <option value="conta_pagar" <?php if($tipo == 'conta_pagar'){echo 'selected';}?>>Contas a Pagar</option>
                                        <option value="transferencia" <?php if($tipo == 'transferencia'){echo 'selected';}?>>Transferência</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
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

    $(document).on('submit', '#natureza_financeira_agrupador', function () {
        modalAguarde();
    });

</script>