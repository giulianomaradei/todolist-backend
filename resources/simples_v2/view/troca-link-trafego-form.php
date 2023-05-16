<?php
require_once(__DIR__."/../class/System.php");

if (isset($_GET['alterar'])) {
    $operacao = 'alterar';
    $tituloPainel = 'alterar';
    $id = (int)$_GET['alterar'];
    $dados = DBRead('', 'tb_link_trafego', "WHERE id_link_trafego = $id");

    $nome = $dados[0]['nome'];
    $id_link_acesso = $dados[0]['id_link_acesso'];
    $ip = $dados[0]['ip_trafego'];

    $nome_link = '';
    
} else {
    $operacao = 'inserir';
    $tituloPainel = 'inserir';
    $id = 1;

    $nome = '';
    $id_link_acesso = '';
    $ip = '';
}
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <div class="panel panel-default">
                <div class="panel-heading clearfix">            
                    <h3 class="panel-title text-left pull-left">Troca de Link - Gerencia de Trafego <?= $tituloPainel ?> </h3>
                    <?php 
                        if (!empty($_GET['alterar'])){
                            echo '<div class="panel-title text-right pull-right">
                            <a href="/api/ajax?class=TrocaLinkTrafego.php?excluir='.$id.'&token='.$request->token.'" 
                            onclick="if (!confirm(\'Tem certeza que deseja excluir o registro?\')) { return false; 
                            }else{ modalAguarde(); }">
                                <button class="btn-xs btn-danger">
                                <i class="fa fa-trash"></i> Excluir
                                </button></a></div>';
                        }; 
                    ?>
                </div>
                <form method="post" action="/api/ajax?class=TrocaLinkTrafego.php" id="novo_trafego_form" style="margin-bottom: 0;">  
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
                        <div class="col-md-12">
                            <div class="form-group">
                                    <label>Link de Saída:</label>
                                        <select class="form-control" name="link" id="link"> 
                                            <option value="">Selecione o Link</option>
                                        <?php
                                            $dados_link = DBRead('', 'tb_link_acesso','ORDER BY nome ASC');
                                            foreach($dados_link as $dado){ 
                                                $selected = $id_link_acesso == $dado['id_link_acesso'] ? "selected" : "";
                                                ?>
                                                <option value="<?= $dado['id_link_acesso']?>" <?= $selected?> ><?= $dado['nome']?></option>
                                            <?php } ?>
                                        </select>
                            </div>
                        </div>
                    </div>
                </div>
                    <div class="panel-footer" style="margin-top: 2%;">
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
