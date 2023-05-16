<?php
require_once(__DIR__."/../class/System.php");
abreSessao();
if(isset($_SESSION['logado'])){
    $horaRegistro = $_SESSION['horaRegistro'];
    $tempoLimite = 14400; //4 horas    //7200 -> 2Horas
    $segundos = time() - $horaRegistro;
    if($segundos<$tempoLimite){
        $_SESSION['horaRegistro'] = time();
        $tokenUser = sha1(getSalt('secao').getIp());
        if($_SESSION['donoSessao']  == $tokenUser){
            $id_usuario = $_SESSION['id_usuario'];
            $dados = DBRead('', 'tb_usuario',"WHERE id_usuario = '$id_usuario' AND status = 1");
            if($dados){                
                header("location: /api/iframe?token=<?php echo $request->token ?>&view=home");
                exit;
            }
        }
    }
}
?>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simples V2</title>
    <link rel="shortcut icon" href="inc/img/icon.png" type="image/x-icon"/>
    <link rel="stylesheet" href="inc/css/bootstrap.min.css" media="screen">
    <link rel="stylesheet" href="inc/css/bootstrap-theme.min.css" media="screen">
    <link rel="stylesheet" href="inc/css/login.css">
    <link href="inc/font-awesome/css/all.min.css" rel="stylesheet">
    <link href="inc/font-awesome/css/v4-shims.min.css" rel="stylesheet">

    <script src="inc/js/jquery-2.2.1.min.js"></script>
    <script src="inc/js/bootstrap.min.js"></script>
    <script src="inc/js/jquery.mask.min.js"></script>
    <script src="inc/js/cpf_cnpj.js"></script>

</head>
<body>
    <div class = "container">

        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <br><br>
                <div class="alert alert-warning text-center" style="font-size: 19px;">
                    <i class="fa fa-exclamation-triangle" aria-hidden="true">
                    </i> Favor bater o ponto, não esqueça!!
                </div>
            </div>
        </div>

        <div class="wrapper">
            <form action="class/Login.php" method="post" class="form-signin" style="margin-bottom: 0; border-radius: 50px;  border: 1px ridge #337ab7 !important; box-shadow:0 10px 16px 0 rgba(0,0,0,0.5),0 6px 20px 0 rgba(0,0,0,0.19) !important;" id="post_login">
                <p class="logo" style="padding-bottom: 25px; padding-top: 25px;"><img class="img-fluid" src="inc/img/logo.png" style="width: 280px;"></p>
                <p class="form-signin-heading" style="padding-bottom: 25px; "><strong>Simples V2</strong></p>

                <?php exibeAlerta(); ?>

                <input class="form-control input-sm cpf_cnpj" type="login" name="login" id="login" placeholder="CPF" autocomplete="off" required="" style="border-radius: 10px; margin-bottom: 5px;">

                <div class="input-group">
                    <input class="form-control input-sm" type="password" id="senha" name="senha" placeholder="Senha" autocomplete="off" required="" style="border-top-left-radius: 10px; border-bottom-left-radius: 10px;">
                        <span class="input-group-addon" type="button" id="btn_pass" style="cursor: pointer; border-top-right-radius: 10px; border-bottom-right-radius: 10px;">
                            <span type="button" id="btn_eye" value="1"><i class="far fa-eye-slash" ></i></span>
                        </span>
                </div>
                
                <input type="hidden" value="1" name="entrar">
                <button class="btn btn-lg btn-primary btn-block" type="Submit" style="margin-bottom: 15px; margin-top: 25px; border-radius: 10px;">Entrar</button>
                <center> <a href="https://app.pontomaisweb.com.br/#/acessar" target="_blank"><img src="inc/img/ponto_mais.png" height="4%"  ></a> 
                <div class="powered" style="padding-bottom: 25px;">
                    <a href="/inc/img/ponto_mais" target="_blank"><p>© Desenvolvido por Belluno Tecnologia</p></a>
                </div>              
            </form>
        </div>
    </div>
</body>


<script>
// $(document).ready(function(){
//     $('.cpf_cnpj').mask('000.000.000-00', {reverse: true, placeholder: '000.000.000-00'});
// });

// $(document).on('submit', '#post_login', function(){
//     var cpf_cnpj = $("#login").val();
//     if (!valida_cpf(cpf_cnpj)){
//         alert('CPF inválido!');
//         $('#login').focus();
//         return false;
//     }
// });
$(document).on('click', '#btn_pass', function () {
    if($('#btn_eye').val() == 1){
        $('#btn_eye').val(2);
        $('#btn_eye').html('<i class="far fa-eye"></i>');
        $('#senha').prop('type', 'text');
    }else{
        $('#btn_eye').val(1);
        $('#btn_eye').html('<i class="far fa-eye-slash"></i>');
        $('#senha').prop('type', 'password');

    }
});
</script>