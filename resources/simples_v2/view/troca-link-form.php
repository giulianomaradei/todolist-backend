<?php
require_once(__DIR__."/../class/System.php");

if (isset($_GET['alterar'])) {
    $operacao = 'alterar';
    $tituloPainel = 'alterar';
    $id = (int)$_GET['alterar'];
    $dados = DBRead('', 'tb_link_acesso', "WHERE id_link_acesso = $id");

    $nome = $dados[0]['nome'];
    $user = $dados[0]['user'];
    $senha = $dados[0]['senha'];
    $ip = $dados[0]['ip'];
    
} else {
    $operacao = 'inserir';
    $tituloPainel = 'inserir';
    $id = 1;

    $nome = '';
    $user = '';
    $senha = '';
    $ip = '';
}
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <div class="panel panel-default">
                <div class="panel-heading clearfix">            
                    <h3 class="panel-title text-left pull-left">Troca de Link - Gerencia de Link <?= $tituloPainel ?> </h3>
                    <?php 
                        if (!empty($_GET['alterar'])){
                            echo '<div class="panel-title text-right pull-right">
                            <a href="/api/ajax?class=TrocaLink.php?excluir='.$id.'&token='.$request->token.'" 
                            onclick="if (!confirm(\'Tem certeza que deseja excluir o registro?\')) { return false; 
                            }else{ modalAguarde(); }">
                                <button class="btn-xs btn-danger">
                                <i class="fa fa-trash"></i> Excluir
                                </button></a></div>';
                        }; 
                    ?>
                </div>
                <form method="post" action="/api/ajax?class=TrocaLink.php" id="novo_link_form" style="margin-bottom: 0;">
		            <input type="hidden" name="token" value="<?php echo $request->token ?>">  
                <div class="panel-body" style="padding-bottom: 0;">                     
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Nome:</label>
                                    <input name="nome" id="nome" type="text" class="form-control input-sm" autocomplete="off" required value="<?= $nome ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>IP:</label>
                                    <input name="ip" id="ip" type="text" class="form-control input-sm" autocomplete="off" required value="<?= $ip ?>"> 
                                </div>
                            </div>
                    </div>
                    <div class="row">   
                        <div class="col-md-6">
                                <div class="form-group">
                                    <label>Usuario:</label>
                                    <input name="user" id="user" type="text" class="form-control input-sm" autocomplete="off" required value="<?= $user ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Senha:</label>
                                    <input name="senha" id="senha" type="text" class="form-control input-sm" autocomplete="off" required value="<?= $senha ?>">
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