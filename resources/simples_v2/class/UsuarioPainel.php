<?php
require_once(__DIR__."/System.php");


$id_pessoa = (!empty($_POST['id_pessoa'])) ? $_POST['id_pessoa'] : '';
$id_usuario_vinculado = (!empty($_POST['usuario_vinculado'])) ? $_POST['usuario_vinculado'] : '';
$nivel = (!empty($_POST['nivel'])) ? $_POST['nivel'] : '';
$email = (!empty($_POST['email'])) ? $_POST['email'] : '';
$status = (!empty($_POST['status'])) ? $_POST['status'] : 0;
$senha = (!empty($_POST['senha'])) ? $_POST['senha'] : '';
$confirm_senha = (!empty($_POST['confirm_senha'])) ? $confirm_senha = $_POST['confirm_senha'] : '';


if(!empty($_POST['inserir'])){
   
    $dados = DBRead('', 'tb_usuario_painel', "WHERE email = '".addslashes($email)."' AND status != '2'");

    if(!$dados){
        if($senha == $confirm_senha){
            if((strlen($senha) >= 8) && (strlen($confirm_senha) >= 8)){
                $senha = sha1($senha.getSalt());
                inserir($id_pessoa, $email, $status, $senha, $id_usuario_vinculado, $nivel);
            }else{
                $alert = ('A senha deve conter 8 ou mais caracteres!','d');
                header("location: /api/iframe?token=$request->token&view=usuario-painel-form");
                exit;
            }
        }else{
            $alert = ('As senhas não coincidem!','d');
            header("location: /api/iframe?token=$request->token&view=usuario-painel-form");
            exit;
        }
    }else{
        $alert = ('Já existe um usuário com este E-mail!','d');
        header("location: /api/iframe?token=$request->token&view=usuario-painel-form");
        exit;
    }

}else if(!empty($_POST['alterar'])){
    $id = (int)$_POST['alterar'];
    $id_usuario_vinculado = (!empty($_POST['id_usuario_cliente'])) ? $_POST['id_usuario_cliente'] : '';
 
    $dados = DBRead('', 'tb_usuario_painel', "WHERE BINARY email = '".addslashes($email)."' AND id_usuario_painel != '$id' AND status != '2'");
    if (!$dados){
        if($senha == $confirm_senha){
            if((strlen($senha) >= 8) && (strlen($confirm_senha) >= 8)){
                $senha = sha1($senha.getSalt());
                alterar($id, $id_pessoa, $email, $status, $senha, $id_usuario_vinculado, $nivel);
            }else{
                if((strlen($senha) == 0) && (strlen($confirm_senha) == 0)){
                    $dados = DBRead('', 'tb_usuario_painel', "WHERE id_usuario_painel = '$id'");
                    $senha = $dados[0]['senha'];
                    alterar($id, $id_pessoa, $email, $status, $senha, $id_usuario_vinculado, $nivel);
                }else{
                    $alert = ('A senha deve conter 8 ou mais caracteres!','d');
                    header("location: /api/iframe?token=$request->token&view=usuario-painel-form");
                    exit;
                }
            }
        }else{
            $alert = ('As senhas não coincidem!','d');
            header("location: /api/iframe?token=$request->token&view=usuario-painel-form&alterar=$id");
            exit;
        }
    } else{
        $alert = ('Já existe um usuário com este E-mail!','d');
        header("location: /api/iframe?token=$request->token&view=usuario-painel-form&alterar=$id");
        exit;
    }
    

}else if(isset($_GET['excluir'])){

    $id = (int)$_GET['excluir'];
    excluir($id);

}else{
    header("location: ../adm.php");
    exit;
}

function inserir($id_pessoa, $email, $status, $senha, $id_usuario_vinculado, $nivel){

    $usuario = DBRead('', 'tb_usuario_painel', "WHERE id_pessoa_usuario = '$id_usuario_vinculado' AND id_pessoa_cliente = '$id_pessoa' AND status != 2");

    if($usuario){
        $alert = ('Usuário já cadastrado no sistema!','w');
        header("location: /api/iframe?token=$request->token&view=usuario-painel-busca");
        exit;
    }else{
        $dados = array(
            'status' => $status,
            'email' => $email,
            'senha' => $senha,
            'nivel' => $nivel,
            'id_pessoa_cliente' => $id_pessoa,
            'id_pessoa_usuario' => $id_usuario_vinculado
        );
    
        $isertID = DBCreate('', 'tb_usuario_painel', $dados, true);
        registraLog('Inserção de usuário do painel.','i','tb_usuario_painel',$isertID,"email: $email | senha: $senha | status: $status | nivel: $nivel | id_pessoa_cliente: $id_pessoa | id_pessoa_usuario: $id_usuario_vinculado");
        $alert = ('Item inserido com sucesso!');
    $alert_type = 's';        header("location: /api/iframe?token=$request->token&view=usuario-painel-busca");
        exit;
    }
} 
 
function alterar($id, $id_pessoa, $email, $status, $senha, $id_usuario_vinculado, $nivel){

    $usuario = DBRead('', 'tb_usuario_painel', "WHERE id_pessoa_usuario = '$id_usuario_vinculado' AND id_pessoa_cliente = '$id_pessoa' AND id_usuario_painel != '$id'");

    if($usuario){
        $alert = ('Usuário já cadastrado no sistema!','w');
        header("location: /api/iframe?token=$request->token&view=usuario-painel-busca");
    }else{
        $dados = array(
            'status' => $status,
            'email' => $email,
            'senha' => $senha,
            'nivel' => $nivel,
            'id_pessoa_cliente' => $id_pessoa,
            'id_pessoa_usuario' => $id_usuario_vinculado
        );

        DBUpdate('', 'tb_usuario_painel',$dados,"id_usuario_painel = $id");
        registraLog('Alteração de usuário do painel.','a','tb_usuario_painel',$id,"email: $email | senha: $senha | status: $status | nivel: $nivel | id_pessoa_cliente: $id_pessoa | id_pessoa_usuario: $id_usuario_vinculado");
        $alert = ('Item alterado com sucesso!');
    $alert_type = 's';        header("location: /api/iframe?token=$request->token&view=usuario-painel-busca");
        exit;
    }
}

function excluir($id){    

    $dados = array(
        'status' => '2'
    );
    DBUpdate('', 'tb_usuario_painel',$dados,"id_usuario_painel = $id");
    registraLog('Exclusão de usuário do painel.','e','tb_usuario_painel',$id,'');
    $alert = ('Item excluído com sucesso!');
    $alert_type = 's';    header("location: /api/iframe?token=$request->token&view=usuario-painel-busca");
    exit;

} 

?>
