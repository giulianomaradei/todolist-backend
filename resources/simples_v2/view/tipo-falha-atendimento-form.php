<?php
require_once(__DIR__."/../class/System.php");

if (isset($_GET['alterar'])) {
    $tituloPainel = 'Alterar';
    $operacao = 'alterar';
    $id = (int)$_GET['alterar'];
    $dados = DBRead('', 'tb_tipo_falha_atendimento', "WHERE id_tipo_falha_atendimento = $id");
    $opcao = $dados[0]['opcao'];
    $texto_os = $dados[0]['texto_os'];
    $exibicao = $dados[0]['exibicao'];
    $faturar = $dados[0]['faturar'];
    $status = $dados[0]['status'];
    $resolvido = $dados[0]['resolvido'];
}else{
    $tituloPainel = 'Inserir';
    $operacao = 'inserir';
    $id = 1;
    $opcao = '';
    $texto_os = '';
    $exibicao = '';
    $faturar = '';
    $status = '';
    $resolvido = '';
}
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    <h3 class="panel-title text-left pull-left"><?= $tituloPainel ?> tipo de falha de atendimento:</h3>
                    <?php if (isset($_GET['alterar'])) { echo "<div class=\"panel-title text-right pull-right\"><a  href=\"/api/ajax?class=TipoFalhaAtendimento.php?excluir= $id&token=". $request->token ."\" onclick=\"if (!confirm('Tem certeza que deseja excluir o registro?')) { return false; } else { modalAguarde(); }\"><button class=\"btn btn-xs btn-danger\"><i class=\"fa fa-trash\"></i> Excluir</button></a></div>"; } ?>
                </div>
                <form method="post" action="/api/ajax?class=TipoFalhaAtendimento.php" id="area_problema_form" style="margin-bottom: 0;">
		            <input type="hidden" name="token" value="<?php echo $request->token ?>">
                    <div class="panel-body" style="padding-bottom: 0;">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>*Opção:</label>
                                    <input name="opcao" autofocus type="text" class="form-control input-sm" value="<?= $opcao; ?>" autocomplete="off" required>
                                </div><!-- end form group -->
                            </div><!-- end col -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>*Texto OS:</label>
                                    <input name="texto_os" autofocus type="text" class="form-control input-sm" value="<?= $texto_os; ?>" autocomplete="off" required>
                                </div><!-- end form group -->
                            </div><!-- end col -->               
                        </div><!-- end row -->
                         <div class="row">
                            <div class="col-md-3">                            
                                <div class="form-group">
                                    <label>*Exibição</label>
                                    <select name="exibicao" class="form-control input-sm" required>
                                        <option value></option>
                                        <option value='1' <?php if ($exibicao == '1') {echo 'selected';}?>>Início do atendimento</option>
                                        <option value='2' <?php if ($exibicao == '2') {echo 'selected';}?>>Durante o atendimento</option>
                                        <option value='3' <?php if ($exibicao == '3') {echo 'selected';}?>>Ambas as telas</option>
                                    </select>
                                </div><!-- end form group -->
                            </div><!-- end col -->
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>*Faturar</label>
                                    <select name="faturar" class="form-control input-sm" required>
                                        <option value></option>
                                        <option value="1" <?php if ($faturar == '1') {echo 'selected';}?>>Sim</option>
                                        <option value="0" <?php if ($faturar == '0') {echo 'selected';}?>>Não</option>
                                    </select>
                                </div><!-- end form group -->
                            </div><!-- end col -->
                            <div class="col-md-3">
                                <div class="form-group">
                                <label>*Resolvido:</label>
                                    <select class="form-control input-sm" name="resolvido" required>
                                        <option value=""></option>                                        
                                        <option value='1' <?php if ($resolvido == '1') {echo 'selected';}?>>Sim</option>
                                        <option value='2' <?php if ($resolvido == '2') {echo 'selected';}?>>Não</option>
                                        <option value='3' <?php if ($resolvido == '3') {echo 'selected';}?>>Diagnosticado</option>
                                    </select>
                                </div><!-- end form group -->
                            </div><!-- end col -->
                            <div class="col-md-3">                                
                                <div class="form-group">
                                   <label>*Status:</label>
                                    <select class="form-control input-sm" name="status" required>
                                        <option value></option>
                                        <option value='1' <?php if ($status == '1') {echo 'selected';}?>>Ativo</option>
                                        <option value='0' <?php if ($status == '0') {echo 'selected';}?>>Inativo</option>
                                    </select>
                                </div><!-- end form group -->
                            </div><!-- end col -->
                        </div><!-- end row -->
                    </div>
                    <div class="panel-footer">
                        <div class="row">
                            <div class="col-md-12" style="text-align: center">
                                <input type="hidden" id="operacao" value="<?= $id; ?>" name="<?= $operacao; ?>"/>
                                <button class="btn btn-primary" name="salvar" id="ok" type="submit"><i class="fa fa-floppy-o"></i> Salvar</button>
                            </div>
                        </div><!-- end row -->
                    </div><!-- end footer -->
                </form>
            </div>
        </div>
    </div>
</div>     
<script>
    $(document).on('submit', '#falha-form', function () {
        modalAguarde();
    });
</script>