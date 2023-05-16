<?php
require_once(__DIR__."/System.php");


$id_pessoa = (!empty($_POST['id_pessoa'])) ? $_POST['id_pessoa'] : '';
$perfil = (!empty($_POST['perfil'])) ? $_POST['perfil'] : '';
$email = (!empty($_POST['email'])) ? $_POST['email'] : '';
$status = (!empty($_POST['status'])) ? $_POST['status'] : 0;
$senha = (!empty($_POST['senha'])) ? $_POST['senha'] : '';
$confirm_senha = (!empty($_POST['confirm_senha'])) ? $confirm_senha = $_POST['confirm_senha'] : '';
$id_asterisk = (!empty($_POST['id_asterisk'])) ? $_POST['id_asterisk'] : '';
$id_ponto = (!empty($_POST['id_ponto'])) ? $_POST['id_ponto'] : '';
$id_otrs = (!empty($_POST['id_otrs'])) ? $_POST['id_otrs'] : '';
$lider = (!empty($_POST['lider'])) ? $_POST['lider'] : 0 ;
$ips_permitidos = (!empty($_POST['ips_permitidos'])) ? $_POST['ips_permitidos'] : '';
$id_usuario = (!empty($_POST['id'])) ? $_POST['id'] : '';
$nota = (!empty($_POST['nota'])) ? $_POST['nota'] : '';

$id_infinity = (!empty($_POST['id_infinity'])) ? $_POST['id_infinity'] : '';


if(!empty($_POST['inserir'])){

    $dados = DBRead('', 'tb_usuario', "WHERE email = '".addslashes($email)."'");

    if(!$dados){
        if($senha == $confirm_senha){
            if((strlen($senha) >= 8) && (strlen($confirm_senha) >= 8)) {
                senha_samba($email,$senha);
                $senha_salt = sha1($senha.getSalt());
                inserir($id_pessoa, $perfil, $email, $status, $senha_salt, $id_asterisk, $id_ponto, $id_otrs, $lider, $ips_permitidos, $id_infinity);
            }else{
                $alert = ('A senha deve conter 8 ou mais caracteres!','d');
                header("location: /api/iframe?token=$request->token&view=usuario-form");
                exit;
            }
        }else{
            $alert = ('As senhas não coincidem!','d');
            header("location: /api/iframe?token=$request->token&view=usuario-form");
            exit;
        }
    } else {
        $alert = ('Já existe um usuário com este E-mail!','d');
        header("location: /api/iframe?token=$request->token&view=usuario-form");
        exit;
    }

} else if (!empty($_POST['alterar'])) {
    $id = (int)$_POST['alterar'];
    
    $dados = DBRead('', 'tb_usuario', "WHERE BINARY email = '".addslashes($email)."' AND id_usuario != '$id'");
    if (!$dados) {
        if($senha == $confirm_senha){
            if((strlen($senha) >= 8) && (strlen($confirm_senha) >= 8)) {
                senha_samba($email,$senha);
                $senha_salt = sha1($senha.getSalt());
                alterar($id, $id_pessoa, $perfil, $email, $status, $senha_salt, $id_asterisk, $id_ponto, $id_otrs, $lider, $ips_permitidos, $id_infinity);
            }else{
                if((strlen($senha) == 0) && (strlen($confirm_senha) == 0)) {
                    $dados = DBRead('', 'tb_usuario', "WHERE id_usuario = '$id'");
                    $senha = $dados[0]['senha'];
                    alterar($id, $id_pessoa, $perfil, $email, $status, $senha, $id_asterisk, $id_ponto, $id_otrs, $lider, $ips_permitidos, $id_infinity);
                }else{
                    $alert = ('A senha deve conter 8 ou mais caracteres!','d');
                    header("location: /api/iframe?token=$request->token&view=usuario-form");
                    exit;
                }
            }
        }else{
            $alert = ('As senhas não coincidem!','d');
            header("location: /api/iframe?token=$request->token&view=usuario-form&alterar=$id");
            exit;
        }
    } else {
        $alert = ('Já existe um usuário com este E-mail!','d');
        header("location: /api/iframe?token=$request->token&view=usuario-form&alterar=$id");
        exit;
    }
    

} else if (isset($_GET['excluir'])) {

    $id = (int)$_GET['excluir'];
    excluir($id);

}else if (isset($_GET['desativar'])) {

    $id = (int)$_GET['desativar'];
    desativar($id);

}else if (isset($_GET['ativar'])) {

    $id = (int)$_GET['ativar'];
    ativar($id);

}else if (!empty($_POST['alterar_senha'])) {
    $id = $_SESSION['id_usuario'];
    $dados_usuario = DBRead('','tb_usuario',"WHERE id_usuario = '$id'");
    $email = $dados_usuario[0]['email'];

    if($senha == $confirm_senha){
        if((strlen($senha) >= 8) && (strlen($confirm_senha) >= 8)) {
            senha_samba($email,$senha);
            $senha_salt = sha1($senha.getSalt());
            alterar_senha($id, $senha_salt);
        }else{
            $alert = ('A senha deve conter 8 ou mais caracteres!','d');
            header("location: /api/iframe?token=$request->token&view=usuario-senha&alterar=$id");
            exit;
        }
    }else{
        $alert = ('As senhas não coincidem!','d');
        header("location: /api/iframe?token=$request->token&view=usuario-senha&alterar=$id");
        exit;
    }

}else if (!empty($_POST['equipe'])){

    equipe($lider, $id_usuario);

}else if (!empty($_POST['inserir_nota'])){

    inserir_anotacoes($nota);

}else{
    header("location: ../adm.php");
    exit;
}

function inserir($id_pessoa, $perfil, $email, $status, $senha, $id_asterisk, $id_ponto, $id_otrs, $lider, $ips_permitidos, $id_infinity){

    if($id_asterisk){
        $dados = array('id_asterisk' => '');
        DBUpdate('', 'tb_usuario', $dados, "id_asterisk = $id_asterisk");
    }

    if($id_ponto){
        $dados = array('id_ponto' => '');
        DBUpdate('', 'tb_usuario', $dados, "id_ponto = $id_ponto");
    }

    if($id_otrs){
        $dados = array('id_otrs' => '');
        DBUpdate('', 'tb_usuario', $dados, "id_otrs = $id_otrs");
    }

    if($id_infinity){
        $dados = array('id_infinity' => '');
        DBUpdate('', 'tb_usuario', $dados, "id_infinity = $id_infinity");
    }

    $dados = array(
        'status' => $status,
        'email' => $email,
        'senha' => $senha,
        'id_asterisk' => $id_asterisk,
        'id_ponto' => $id_ponto,
        'id_otrs' => $id_otrs,
        'id_pessoa' => $id_pessoa,
        'id_perfil_sistema' => $perfil,
        'lider_direto' => $lider,
        'ips_permitidos' => $ips_permitidos,
        'id_infinity' => $id_infinity
    );
    $isertID = DBCreate('', 'tb_usuario', $dados, true);
    registraLog('Inserção de usuário.','i','tb_usuario',$isertID,"email: $email | senha: $senha | status: $status | id_asterisk: $id_asterisk | id_ponto: $id_ponto | id_otrs: $id_otrs | id_pessoa: $id_pessoa | id_perfil_sistema: $perfil | lider_direto: $lider | ips_permitidos: $ips_permitidos | id_infinity: $id_infinity");
    perfil_topico_lido($isertID, $perfil);
    chamado_visualizacao($isertID, $perfil);

    //foto
    if(isset($_FILES['foto']['name']) && $_FILES['foto']['error'] == 0 ) {
                
        if ($_FILES[ 'foto' ][ 'size' ] > 5242880) {
            $alert = ('Tamanho da imagem excede o tamanho limite!', 'd', 'AVISO!');
            header("location: /api/iframe?token=$request->token&view=usuario-form&alterar=".$isertID);
            exit;
        }else{

            $arquivo_tmp = $_FILES['foto']['tmp_name'];
            $nome = $_FILES['foto']['name'];
        
            // Pega a extensão
            $extensao = pathinfo($nome, PATHINFO_EXTENSION);
            // Converte a extensão para minúsculo
            $extensao = strtolower($extensao);
        
            // Somente imagens, .jpg;.jpeg;.gif;.png
            if (strstr('.jpg;.jpeg;.png', $extensao)) {
        
                // Concatena a pasta com o nome
                $destino = '../inc/upload-usuario/'.$isertID.'.jpg';
                //$foto_caminho = 'inc/upload-usuario/'.$novoNome;
                
                unlink('../inc/upload-usuario/'.$isertID.'.jpg');

                echo 'destino: '.$destino.'<br>';
                echo 'arquivo_tmp: '. $arquivo_tmp.'<br>';


                //tenta mover o arquivo para o destino
                if(@move_uploaded_file ($arquivo_tmp, $destino)){
                    // $alert = ('Arquivo salvo com sucesso em : <strong>' . $destino . '</strong><br /> < img src = "' . $destino . '" />', 's', 'AVISO!');
                    // header("location: /api/iframe?token=$request->token&view=usuario-busca");
                    // exit;                    
                }else{
                    $alert = ('Erro ao salvar a imagem!', 'd', 'AVISO!');
                    header("location: /api/iframe?token=$request->token&view=usuario-form&alterar=".$isertID);

                    exit;
                }
            }else{
                $alert = ('Você poderá enviar apenas imagens "*.jpg;*.jpeg;*.png"!', 'd', 'AVISO!');
                header("location: /api/iframe?token=$request->token&view=usuario-form&alterar=".$isertID);
                exit;
            }
        }
    }
    //foto

    $alert = ('Item inserido com sucesso!');
    $alert_type = 's';    header("location: /api/iframe?token=$request->token&view=usuario-busca");
    exit;
}

function alterar($id, $id_pessoa, $perfil, $email, $status, $senha, $id_asterisk, $id_ponto, $id_otrs, $lider, $ips_permitidos, $id_infinity){

    $dados_usuario = DBRead('', 'tb_usuario', "WHERE id_usuario = '$id'");
    $perfil_antigo = $dados_usuario[0]['id_perfil_sistema'];

    if($id_asterisk){
        $dados = array('id_asterisk' => '');
        DBUpdate('', 'tb_usuario', $dados, "id_asterisk = $id_asterisk");
    }

    if($id_ponto){
        $dados = array('id_ponto' => '');
        DBUpdate('', 'tb_usuario', $dados, "id_ponto = $id_ponto");
    }

    if($id_otrs){
        $dados = array('id_otrs' => '');
        DBUpdate('', 'tb_usuario', $dados, "id_otrs = $id_otrs");
    }

    if($id_infinity){
        $dados = array('id_infinity' => '');
        DBUpdate('', 'tb_usuario', $dados, "id_infinity = $id_infinity");
    }
    
    $dados = array(
        'status' => $status,
        'email' => $email,
        'senha' => $senha,
        'id_asterisk' => $id_asterisk,
        'id_ponto' => $id_ponto,
        'id_otrs' => $id_otrs,
        'id_pessoa' => $id_pessoa,
        'id_perfil_sistema' => $perfil,
        'lider_direto' => $lider,
        'ips_permitidos' => $ips_permitidos,
        'id_infinity' => $id_infinity
    );
    DBUpdate('', 'tb_usuario',$dados,"id_usuario = $id");
    registraLog('Alteração de usuário.','a','tb_usuario',$id,"email: $email | senha: $senha | status: $status | id_asterisk: $id_asterisk | id_ponto: $id_ponto | id_otrs: $id_otrs | id_pessoa: $id_pessoa | id_perfil_sistema: $perfil | lider_direto: $lider | ips_permitidos: $ips_permitidos | id_infinity: $id_infinity");

    if($perfil != $perfil_antigo){
        perfil_topico_lido($id, $perfil);
        chamado_visualizacao($id, $perfil);
    }

    //foto
    if(isset($_FILES['foto'][ 'name' ] ) && $_FILES[ 'foto' ][ 'error' ] == 0 ) {
                
        if ($_FILES[ 'foto' ][ 'size' ] > 5242880) {
            $alert = ('Tamanho da imagem excede o tamanho limite!', 'd', 'AVISO!');
            header("location: /api/iframe?token=$request->token&view=usuario-form&alterar=".$id);
            exit;
        }else{

            $arquivo_tmp = $_FILES['foto']['tmp_name'];
            $nome = $_FILES['foto']['name'];
        
            // Pega a extensão
            $extensao = pathinfo($nome, PATHINFO_EXTENSION);
            // Converte a extensão para minúsculo
            $extensao = strtolower($extensao);
        
            // Somente imagens, .jpg;.jpeg;.gif;.png
            if (strstr('.jpg;.jpeg;.png', $extensao)) {
        
                // Concatena a pasta com o nome
                $destino = '../inc/upload-usuario/'.$id.'.jpg';
                //$foto_caminho = 'inc/upload-usuario/'.$novoNome;
                
                unlink('../inc/upload-usuario/'.$id.'.jpg');

                echo 'destino: '.$destino.'<br>';
                echo 'arquivo_tmp: '. $arquivo_tmp.'<br>';


                //tenta mover o arquivo para o destino
                if(@move_uploaded_file ($arquivo_tmp, $destino)){
                    // $alert = ('Arquivo salvo com sucesso em : <strong>' . $destino . '</strong><br /> < img src = "' . $destino . '" />', 's', 'AVISO!');
                    // header("location: /api/iframe?token=$request->token&view=usuario-busca");
                    // exit;                    
                }else{
                    $alert = ('Erro ao salvar a imagem!', 'd', 'AVISO!');
                    header("location: /api/iframe?token=$request->token&view=usuario-form&alterar=".$id);
                    exit;
                }
            }else{
                $alert = ('Você poderá enviar apenas imagens "*.jpg;*.jpeg;*.png"!', 'd', 'AVISO!');
                header("location: /api/iframe?token=$request->token&view=usuario-form&alterar=".$id);
                exit;
            }
        }
    }
    //foto
    
    $alert = ('Item alterado com sucesso!');
    $alert_type = 's';    header("location: /api/iframe?token=$request->token&view=usuario-busca");
    exit;
}

function excluir($id){

    $dados = array(
        'status' => '2'
    );
    DBUpdate('', 'tb_usuario',$dados,"id_usuario = $id");
    registraLog('Exclusão de usuário.','e','tb_usuario',$id,'');
    $alert = ('Item excluído com sucesso!');
    $alert_type = 's';    header("location: /api/iframe?token=$request->token&view=usuario-busca");
    exit;
}

function desativar($id){

    $dados = array(
        'status' => '0',
        'lider_direto' => '0'
    );
    DBUpdate('', 'tb_usuario',$dados,"id_usuario = $id");
    registraLog('Desativação de usuário.','e','tb_usuario',$id,'');
    $alert = ('Usuário desativado com sucesso!','s');
    header("location: /api/iframe?token=$request->token&view=usuario-busca");
    exit;
}
function ativar($id){

    $dados = array(
        'status' => '1'
    );
    DBUpdate('', 'tb_usuario',$dados,"id_usuario = $id");
    registraLog('Ativação de usuário.','e','tb_usuario',$id,'');
    $alert = ('Usuário ativado com sucesso!','s');
    header("location: /api/iframe?token=$request->token&view=usuario-busca");
    exit;
}

function alterar_senha($id, $senha){

    $dados = array(
        'senha' => $senha
    );
    DBUpdate('', 'tb_usuario',$dados,"id_usuario = $id");
    registraLog('Alteração de senha.','a','tb_usuario',$id,"senha: $senha");
    $alert = ('Senha do sistema alterada com sucesso!','i');
    header("location: /api/iframe?token=$request->token&view=home");
    exit;
}

function senha_samba($email, $senha){
    $usuario = explode('@', $email);
    $usuario = $usuario[0];
    $dados_usuario = array('usuario' => $usuario, 'senha' => $senha);
    $retorno = troca_dados_curl("http://192.168.197.205/samba_simples/altera_senha.php",$dados_usuario);
    registraLog('Alteração de senha samba.','a','',0,"usuario: $usuario");
}

function perfil_topico_lido($id_usuario, $perfil_usuario){

    //$data_antigos = date('Y-m-d', strtotime('-36 days', strtotime(getDataHora('data'))));

    //topicos
    $dados_topicos = DBRead('','tb_topico a',"INNER JOIN tb_perfil_topico b ON a.id_topico = b.id_topico WHERE b.id_perfil_sistema = '$perfil_usuario' AND a.status != 2 AND a.id_pai = 0 AND b.id_topico NOT IN (SELECT c.id_topico FROM tb_topico_visualizado c WHERE c.id_usuario = '$id_usuario' AND c.data_lido IS NOT NULL) GROUP BY b.id_topico",'b.id_topico');    

    if($dados_topicos){
        foreach($dados_topicos as $conteudo_topico){
            $dados_visualizado = array(
                'id_topico' => $conteudo_topico['id_topico'],
                'data_visualizado' => getDataHora(),
                'data_lido' => getDataHora(),
                'id_usuario' => $id_usuario
            );
            DBCreate('', 'tb_topico_visualizado', $dados_visualizado);
        }
    }

    //comentarios
    if($dados_topicos){
        foreach($dados_topicos as $conteudo_topico){
            $dados_comentarios = DBRead('', 'tb_topico', "WHERE id_pai = '".$conteudo_topico['id_topico']."' AND status = 1");
            if($dados_comentarios){
                foreach($dados_comentarios as $conteudo_comentario){
                    $dados_visualizado = array(
                        'id_topico' => $conteudo_comentario['id_topico'],
                        'data_visualizado' => getDataHora(),
                        'data_lido' => getDataHora(),
                        'id_usuario' => $id_usuario
                    );
                    DBCreate('', 'tb_topico_visualizado', $dados_visualizado);
                }
            }
        }
    }
}

function chamado_visualizacao($id_usuario, $perfil_usuario){
    $dados_chamados = DBRead('', 'tb_chamado a', "WHERE ((EXISTS (SELECT id_perfil_sistema FROM tb_chamado_perfil WHERE id_perfil_sistema = '$perfil_usuario' AND id_chamado = a.id_chamado) OR EXISTS (SELECT id_usuario FROM tb_chamado_usuario WHERE id_usuario = '$id_usuario' AND id_chamado = a.id_chamado) ) OR a.id_usuario_remetente = '$id_usuario' OR a.id_usuario_responsavel = '$id_usuario') GROUP BY a.id_chamado ORDER BY a.id_chamado DESC", 'a.id_chamado');
    if($dados_chamados){
        foreach ($dados_chamados as $conteudo_chamado) {
            $ultima_acao = DBRead('','tb_chamado_acao', "WHERE id_chamado = '".$conteudo_chamado['id_chamado']."' AND acao != 'pendencia' ORDER BY id_chamado_acao DESC LIMIT 1");
            if($ultima_acao){
                $existe = DBRead('','tb_chamado_visualizacao', "WHERE id_chamado = '".$conteudo_chamado['id_chamado']."' AND id_usuario = '$id_usuario' LIMIT 1");
                if(!$existe){
                    $dados = array(
                        'id_chamado' => $conteudo_chamado['id_chamado'],
                        'id_chamado_acao' => $ultima_acao[0]['id_chamado_acao'],
                        'id_usuario' => $id_usuario,
                        'data' => getDataHora()
                    );
                    DBCreate('','tb_chamado_visualizacao', $dados);
                }                    
            }                
        }
    }
}

function equipe($lider, $id_usuario){
    
    $dados = array(
        'lider_direto' => $lider
    );
    DBUpdate('', 'tb_usuario', $dados, "id_usuario = $id_usuario");
    registraLog('Alteração de lider_direto.','a','tb_usuario',$id_usuario,"lider_direto: $lider");
    $alert = ('Equipe alterada com sucesso!','s');
    header("location: /api/iframe?token=$request->token&view=equipe-busca");
    exit;
}

function inserir_anotacoes($nota){
    
    $id_usuario = $_SESSION['id_usuario'];

    $dados = array(
        'anotacoes' => $nota
    );

    DBUpdate('', 'tb_usuario', $dados, "id_usuario = $id_usuario");
    registraLog('Alteração de anotações.','a','tb_usuario', $id_usuario,"anotaçoes: $nota ");
    $alert = ('Anotações salvas com sucesso!','s');
    header("location: /api/iframe?token=$request->token&view=anotacoes-form");
}
?>
