<?php
require_once(__DIR__."/../class/System.php");

if (isset($_GET['alterar'])) {
    $tituloPainel = 'Alterar';
    $operacao = 'alterar';
    $id = (int)$_GET['alterar'];
    $dados = DBRead('', 'tb_senha', "WHERE id_senha = $id");
    $nome = $dados[0]['nome_senha'];
    $usuario = $dados[0]['usuario'];
    $senha = $dados[0]['senha'];
    $senha = base64_decode($senha);
    $link = $dados[0]['link'];
    $tipo_senha = $dados[0]['tipo_senha'];
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
                    <h3 class="panel-title text-left pull-left"><?= $tituloPainel ?> acesso:</h3>
                    <?php if (isset($_GET['alterar'])) { echo "<div class=\"panel-title text-right pull-right\"><a href=\"/api/ajax?class=GerenciarSenhas.php?excluir= $id&token=". $request->token ."\" onclick=\"if (!confirm('Tem certeza que deseja excluir o registro?')) { return false; } else { modalAguarde(); }\"><button class=\"btn btn-xs btn-danger\"><i class=\"fa fa-trash\"></i> Excluir</button></a></div>"; } ?>
                </div>
                <form method="post" action="class/GerenciarSenhas.php" id="senha_form" style="margin-bottom: 0;">

                    <div class="panel-body" style="padding-bottom: 0;">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>*Nome:</label>
                                    <input name="nome_senha" autofocus type="text" class="form-control input-sm" value="<?= $nome; ?>" autocomplete="off" required>
                                </div>
                            </div>                     
                        </div>


                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>*Usuario:</label>
                                    <input name="usuario" autofocus type="text" class="form-control input-sm" value="<?= $usuario; ?>" autocomplete="off" required>
                                </div>
                            </div>    
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>*Senha:</label>
                                    <input name="senha" autofocus type="text" class="form-control input-sm" value="<?= $senha; ?>" autocomplete="off" required>
                                </div>
                            </div> 
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Link <small>(Opicional)</small>:</label>
                                    <input name="link" autofocus type="select" class="form-control input-sm" value="<?= $link; ?>" autocomplete="off">
                                </div>
                            </div>      
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>*Tipo de senha:</label>
                                    <select class="form-control input-sm" name="tipo_senha" id="tipo_senha" required>
                                        <option value="1" <?php if($tipo_senha == '1'){echo 'selected';}?> >Individual</option>
                                        <option value="2" <?php if($tipo_senha == '2'){echo 'selected';}?> >Compartilhada</option>
                                    </select>
                                </div>
                            </div>                        
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <center><h4>Gerador de senhas:</h4></center>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 center"></div>
                            <div class="col-md-4 center">
                               <center><div class="input-group">
                                    <div class="input-group-addon btn" id="gerarSenha"> <i class="fas fa-sync-alt"></i> Gerar </div>
                                        <input type="text" class="form-control" id="inputSenha" style="text-align: center;" >
                                    </div>
                                </center>
                            </div>
                            <div class="col-md-4 center"></div>
                        </div><br>
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
    $(document).on('submit', '#gerenciar-senhas_form', function () {
        modalAguarde();
    });

    $(document).on('click', '#gerarSenha', function () {
        
        $('#inputSenha').val('');

        var ma = "ABCDEFGHIJKLMNOPQRSTUVYXWZ";
        var mi = "abcdefghijklmnopqrstuvyxwz";
        var nu = "0123456789";
        // var si = "!@#$%¨&*()_+=";

        // if (maiusculas == '1'){senha += ma;}
        // if (minusculas == '1'){senha += mi;}
        // if (numeros == '1'){senha += nu;}
        // if (simbolos == '1'){senha += si;}

        var senha = '';
        senha += ma;
        senha += mi;
        senha += nu;
        // senha += si;

        var pwchars = senha;
        var passwordlength = 10; 
        var passwd = '';
        var randomWords = new Int32Array(passwordlength); 
        for (var i = 0; i < passwordlength; i++) {
            randomWords[i] = Math.floor(Math.random() * senha.length);
        }  

        for (var i = 0; i < passwordlength; i++) {
            passwd += senha.charAt(Math.abs(randomWords[i]) % senha.length);
        }
        
        $('#inputSenha').val(passwd);
        // alert(passwd);
        
    });

</script>