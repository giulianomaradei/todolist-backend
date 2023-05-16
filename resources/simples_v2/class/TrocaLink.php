<?php
    require_once(__DIR__."/System.php");
    

    $nome = (!empty($_POST['nome'])) ? $_POST['nome'] : '';
    $ip = (!empty($_POST['ip'])) ? $_POST['ip'] : '';
    $user = (!empty($_POST['user'])) ? $_POST['user'] : '';
    $senha = (!empty($_POST['senha'])) ? $_POST['senha'] : '';

    if (!empty($_POST['inserir'])) {
        if ($nome && $ip && $user && $senha) {

            inserir($nome, $ip, $user, $senha);
            
        }
        else{
            $alert = ('Não foi possivel inserir o item!','d');
            header("location: /api/iframe?token=$request->token&view=troca-link");
        }
        
    }

    else if (!empty($_POST['alterar'])){

        $id = ($_POST['alterar']);

        if ($nome && $ip && $user && $senha && $id) {

            alterar($nome, $ip, $user, $senha, $id);
            
        }
        else{
            $alert = ('Não foi possivel alterar o item!','d');
            header("location: /api/iframe?token=$request->token&view=troca-link");
        }
    }

    else if (!empty($_GET['excluir'])){

        $id = ($_GET['excluir']);
        excluir($nome, $ip, $user, $senha, $id);
    }

    function inserir($nome, $ip, $user, $senha){
        
        $dados = array(        
            'nome' => $nome,
            'ip' => $ip,
            'user' => $user,
            'senha' => $senha,
        );
    
        $insertID = DBCreate('', 'tb_link_acesso', $dados, true);
        registraLog('Inserção de novo Link.','i','tb_link_acesso',$insertID,"nome: $nome | ip: $ip | user: $user | senha: $senha");
        $alert = ('Link inserido com sucesso!','s');
        header("location: /api/iframe?token=$request->token&view=troca-link"); 
    }

    function alterar($nome, $ip, $user, $senha, $id){

        $dados = array(       
            'nome' => $nome,
            'ip' => $ip,
            'user' => $user,
            'senha' => $senha,
        );
        
        $insertID = DBUpdate('', 'tb_link_acesso', $dados, "id_link_acesso = $id");
        registraLog('Alteracao de Link.','a','tb_link_acesso',$insertID,"nome: $nome | ip: $ip | user: $user | senha: $senha");
        $alert = ('Link alterado com sucesso!','s');
        header("location: /api/iframe?token=$request->token&view=troca-link"); 

    }

    function excluir ($nome, $ip, $user, $senha, $id){

        DBDelete('', 'tb_link_acesso', "id_link_acesso = $id");
        registraLog('Remoção de Link.','e','tb_link_acesso', $id, '');
        $alert = ('Link excluido com sucesso!','s');
        header("location: /api/iframe?token=$request->token&view=troca-link"); 
    }
?>

