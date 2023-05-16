<?php
require_once(__DIR__."/../class/System.php");

$checked_alerta="";
$checked_chamado="";
$checked_topico="";

if (isset($_GET['alterar'])) {
    $tituloPainel = 'Alterar';
    $operacao = 'alterar';
    $id = (int)$_GET['alterar'];
    $dados = DBRead('', 'tb_categoria', "WHERE id_categoria = $id");
    $nome = $dados[0]['nome'];
    $exibe_topico = $dados[0]['exibe_topico'];
    $exibe_chamado = $dados[0]['exibe_chamado'];
    $exibe_alerta = $dados[0]['exibe_alerta'];
    if($exibe_topico == '1'){
        $checked_topico = 'checked';
    }

    if($exibe_chamado == '1'){
        $checked_chamado = 'checked';
    }

    if($exibe_alerta == '1'){
        $checked_alerta = 'checked';
    }

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
                    <h3 class="panel-title text-left pull-left"><?= $tituloPainel ?> categoria:</h3>
                    <?php if (isset($_GET['alterar'])) { echo "<div class=\"panel-title text-right pull-right\"><a  href=\"/api/ajax?class=Categoria.php?excluir= $id&token=". $request->token ."\" onclick=\"if (!confirm('Tem certeza que deseja excluir o registro?')) { return false; } else { modalAguarde(); }\"><button class=\"btn btn-xs btn-danger\"><i class=\"fa fa-trash\"></i> Excluir</button></a></div>"; } ?>
                </div>
                <form method="post" action="/api/ajax?class=Categoria.php" id="categoria_form" style="margin-bottom: 0;">
                    <input type="hidden" name="token" value="<?php echo $request->token ?>">
                    <div class="panel-body" style="padding-bottom: 0;">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>*Nome:</label>
                                    <input name="nome" autofocus id="nome" type="text" class="form-control input-sm" value="<?= $nome; ?>" autocomplete="off" required>
                                </div>
                            </div>
						</div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Exibir em:</label>
                                    <div class="row">
                                        <div class="col-lg-4">
                                            <div class="input-group">
                                            <span class="input-group-addon">
                                                <input <?= $checked_alerta?> type="checkbox" name="exibe_alerta" id="exibe_alerta" value="1">
                                            </span>
                                            <input type="text" class="form-control mensagem" aria-label="..." disabled value="Alerta" style="cursor: context-menu; background-color: white;">
                                            </div><!-- /input-group -->
                                        </div><!-- /.col-lg-6 -->
                                        <div class="col-lg-4">
                                            <div class="input-group">
                                            <span class="input-group-addon">
                                                <input <?= $checked_chamado?> type="checkbox" name="exibe_chamado" id="exibe_chamado" value="1">
                                            </span>
                                            <input type="text" class="form-control mensagem" aria-label="..." disabled value="Chamado" style="cursor: context-menu; background-color: white;">
                                            </div><!-- /input-group -->
                                        </div><!-- /.col-lg-6 -->
                                        <div class="col-lg-4">
                                            <div class="input-group">
                                            <span class="input-group-addon">
                                                <input <?= $checked_topico?> type="checkbox" name="exibe_topico" id="exibe_topico" value="1">
                                            </span>
                                            <input type="text" class="form-control mensagem" aria-label="..." disabled value="Tópico" style="cursor: context-menu; background-color: white;">
                                            </div><!-- /input-group -->
                                        </div><!-- /.col-lg-6 -->
                                    </div><!-- /.row -->
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
</div>
<script>
    $(document).on('submit', '#categoria_form', function () {
        var nome = $("#nome").val();

        if(!$('#exibe_topico').is(':checked') && !$('#exibe_chamado').is(':checked') && !$('#exibe_alerta').is(':checked')){
            alert("Selecione pelo menos uma opção de exibição");
            return false;
        }

        if(!nome || nome == ""){
            alert("Deve-se descrever um nome!");
            return false;
        }
        modalAguarde();
    });
</script>