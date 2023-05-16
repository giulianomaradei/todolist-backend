<?php
require_once(__DIR__."/../class/System.php");

$tituloPainel = 'Alterar';
$operacao = 'alterar_senha';
$id = $_SESSION['id_usuario'];
$senha = '';
$confirm_senha = '';
?>
<div class="row">
    <div class="col-md-6 col-md-offset-3">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><?= $tituloPainel ?> senha:</h3>
            </div>
            <form method="post" action="class/Usuario.php" style="margin-bottom: 0;">
                <div class="panel-body">                            
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>*Senha:</label>
                                <input name="senha" type="password" class="form-control input-sm" id="senha" placeholder="(Min. 8 caracteres!)" value="<?= $senha; ?>" autocomplete="off" required autofocus>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>*Confirmação da Senha:</label>
                                <input name="confirm_senha" type="password" class="form-control input-sm" id="confirm_senha" placeholder="(Min. 8 caracteres!)" value="<?= $confirm_senha; ?>" autocomplete="off" required>
                            </div>
                        </div>
                    </div>                        
                </div>
                <div class="panel-footer">
                    <div class="row">
                        <div class="col-md-12" style="text-align: center">
                            <input type="hidden" id="operacao" value="1" name="<?= $operacao; ?>"/>
                            <button class="btn btn-primary" name="salvar" id="ok" type="submit"><i class="fa fa-floppy-o"></i> Salvar</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<script>   
    $(document).on('submit', 'form', function () {
        var senhaUm = $("#senha").val();
        var senhaDois = $("#confirm_senha").val();
        if(senhaUm != senhaDois){
            alert("As senhas não coincidem!");
            $("#senha").val('').focus();
            $("#confirm_senha").val('');
            return false;
        }
        if((senhaUm.length < 8) && (senhaDois.length < 8)){
            alert("A senha deve conter 8 ou mais caracteres!");
            $("#senha").val('').focus();
            $("#confirm_senha").val('');
            return false;
        }
        $('#modal_aguarde').modal('show');
    });
</script>