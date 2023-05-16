<?php
require_once(__DIR__."/System.php");
abreSessao();

if (!empty($_POST['entrar'])) {
    processaLogin();
} else if (!empty($_GET['sair'])) {
    processaLogout();
}else{
    header("location: ../adm.php");
    exit;
}

function processaLogin(){
    if(!empty($_POST['login']) && !empty($_POST['senha'])){

        $login = $_POST['login'];
        // $login = preg_replace("/[^0-9]/", "", $_POST['login']);

        $senha = $_POST['senha'];
        $senha_salt = sha1($senha.getSalt());
        if($login == 'instrutor'){
            $dados = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_usuario = '234' AND BINARY a.senha = '".addslashes($senha_salt)."' AND a.status != 2");

        }else if($login == 'telao'){
            $dados = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_usuario = '190' AND BINARY a.senha = '".addslashes($senha_salt)."' AND a.status != 2");

        }else{
            $dados = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE (BINARY b.cpf_cnpj = '".addslashes($login)."') AND BINARY a.senha = '".addslashes($senha_salt)."' AND a.status != 2"); 
            // $dados = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE (BINARY a.email = '".addslashes($login)."' OR BINARY b.cpf_cnpj = '".addslashes($login)."') AND BINARY a.senha = '".addslashes($senha_salt)."' AND a.status != 2");

        }

        $status = $dados[0]['status'];
        $ips_permitidos = $dados[0]['ips_permitidos'];
        $array_ips_permitidos = explode(';',$dados[0]['ips_permitidos']);

        if($dados){
            $id_usuario = $dados[0]['id_usuario'];
            if($status == 1) {  
                if(!$ips_permitidos || $ips_permitidos == '' || ($ips_permitidos && $ips_permitidos != '' && in_array(getIP(),$array_ips_permitidos))){
                    $_SESSION['id_usuario'] = $id_usuario;
                    $_SESSION['donoSessao'] = sha1(getSalt('secao').getIp());
                    $_SESSION['logado'] = 1;
                    $_SESSION['login'] = 1;
                    date_default_timezone_set("America/Sao_Paulo");
                    $_SESSION['horaRegistro'] = time();
                    registraLog('Login aceito.','la','',0,'');          
                    //para obrigar a trocar senha
                    $senhaFraca = sha1('12345678'.getSalt());
                    if($dados[0]['senha'] == $senhaFraca){
                        header("location: /api/iframe?token=$request->token&view=usuario-senha");
                        exit;
                    }else{
                        header("location: /api/iframe?token=$request->token&view=home");
                        exit;
                    }
                }else{
                    registraLog('Login negado(IP não permitido).','lnip','',0,'');
                    $alert = ('IP não permitido! Entre em contato com o setor de TI.','d');
                    header("location: ../index.php");
                    exit; 
                }                              
            }else{
                registraLog('Login negado(usuário desativado).','lnd','',0,'');
                $alert = ('Usuário desativado!','d');
                header("location: ../index.php");
                exit;
            }
        }else{
            registraLog('Login negado.','ln','',0,'');
            $alert = ('Usuário ou senha incorretos!','d');
            header("location: ../index.php");
            exit;
        }

    }else{
        registraLog('Login negado.','ln','',0,'');
        $alert = ('Usuário ou senha incorretos!','d');
        header("location: ../index.php");
        exit;
    }
}

function processaLogout(){
    $id_usuario = $_SESSION['id_usuario'];
    registraLog('Logout aceito.','loa','',0,'');
    session_destroy();
    header("location: ../index.php");
    exit;
}
?>
