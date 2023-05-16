<?php
require_once(__DIR__."/../class/System.php");

if (isset($_GET['alterar'])) {
    $tituloPainel = 'Alterar';
    $operacao = 'alterar';
    $id = (int)$_GET['alterar'];

    $dados = DBRead('', 'tb_natureza_financeira', "WHERE id_natureza_financeira = $id");
    $nome = $dados[0]['nome'];
    $status = $dados[0]['status'];
    $tipo = $dados[0]['tipo'];
    $id_natureza_financeira_agrupador = $dados[0]['id_natureza_financeira_agrupador'];
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
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    <h3 class="panel-title text-left pull-left"><?= $tituloPainel ?> Natureza Financeira:</h3>
                    <?php
                        if (isset($_GET['alterar'])) { echo "<div class=\"panel-title text-right pull-right\"><a  href=\"/api/ajax?class=NaturezaFinanceira.php?excluir= $id&token=". $request->token ."\" onclick=\"if (!confirm('Tem certeza que deseja excluir o registro?')) { return false; } else { modalAguarde(); }\"><button class=\"btn btn-xs btn-danger\"><i class=\"fa fa-trash\"></i> Excluir</button></a></div>";
                        } 

                    ?>
                </div>
                <form method="post" action="/api/ajax?class=NaturezaFinanceira.php" id="natureza_financeira" style="margin-bottom: 0;">
		            <input type="hidden" name="token" value="<?php echo $request->token ?>">
                    <div class="panel-body" style="padding-bottom: 0;">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>*Nome:</label>
                                    <input name="nome" id="nome" type="text" class="form-control input-sm" value="<?=$nome;?>" autocomplete="off" required <?= $disabled ?> />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>*Tipo:</label>
                                    <select name="tipo" class="form-control input-sm" <?= $disabled ?> >
                                        <option value="conta_receber" <?php if($tipo == 'conta_receber'){echo 'selected';}?>>Contas a Receber</option>
                                        <option value="conta_pagar" <?php if($tipo == 'conta_pagar'){echo 'selected';}?>>Contas a Pagar</option>
                                        <option value="transferencia" <?php if($tipo == 'transferencia'){echo 'selected';}?>>Transferência</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>*Agrupador:</label>
                                    <select name="id_natureza_financeira_agrupador" class="form-control input-sm" <?= $disabled ?> >
                                    <?php
                                    if($operacao == 'inserir'){?>
                                        <option value="">Selecione primeiro o tipo...</option>
                                    <?php
                                    }else{
                                        $sel_natureza_financeira_agrupador[$id_natureza_financeira_agrupador] = 'selected';
                                        $dados_natureza_financeira_agrupador = DBRead('', 'tb_natureza_financeira_agrupador', "WHERE status = 1 ORDER BY nome ASC");

                                        if ($dados_natureza_financeira_agrupador) {
                                            foreach ($dados_natureza_financeira_agrupador as $conteudo_natureza_financeira_agrupador) {
                                                echo "<option value='" . $conteudo_natureza_financeira_agrupador['id_natureza_financeira_agrupador'] . "' " . $sel_natureza_financeira_agrupador[$conteudo_natureza_financeira_agrupador['id_natureza_financeira_agrupador']] . ">" . $conteudo_natureza_financeira_agrupador['nome'] . "</option>";
                                            }
                                        }
                                    }
									?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
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

    $(document).on('submit', '#natureza_financeira', function () {        
        modalAguarde();
    });

    function selectagrupador(tipo){        
        $("select[name=id_natureza_financeira_agrupador]").html('<option value="">Carregando...</option>');
        $.post("/api/ajax?class=SelectTipoAgrupador.php",
            {tipo: tipo, token: '<?= $request->token ?>'},
            function(valor){
                $("select[name=id_natureza_financeira_agrupador]").html(valor);
            }
        )
    }

    $(document).on('change', 'select[name=tipo]', function(){
        selectagrupador($(this).val());
    });

    selectagrupador($('select[name=tipo]').val());


</script>