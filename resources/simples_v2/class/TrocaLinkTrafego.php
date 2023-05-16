<?php
    require_once(__DIR__."/System.php");
    

    $nome = (!empty($_POST['nome'])) ? $_POST['nome'] : '';
    $ip = (!empty($_POST['ip'])) ? $_POST['ip'] : '';
    $link = (!empty($_POST['link'])) ? $_POST['link'] : '';


    if (!empty($_POST['inserir'])) {
        if ($nome && $ip && $link) {

            inserir($nome, $ip, $link);
            
        }
        else{
            $alert = ('Não foi possivel inserir o item!','d');
            header("location: /api/iframe?token=$request->token&view=troca-link-trafego");
        }
        
    }

    else if (!empty($_POST['alterar'])){

        $id = ($_POST['alterar']);

        if ($nome && $ip && $link && $id) {

            alterar($nome, $ip, $link, $id);
            
        }
        else{
            $alert = ('Não foi possivel alterar o item!','d');
            header("location: /api/iframe?token=$request->token&view=troca-link-trafego");
        }
    }

    else if (!empty($_GET['excluir'])){

        $id = ($_GET['excluir']);
        excluir($id);
    }

    function inserir($nome, $ip, $link){
        
        $dados = array(       
            'nome' => $nome,
            'ip_trafego' => $ip,
            'id_link_acesso' => $link,
        );
    
        $insertID = DBCreate('', 'tb_link_trafego', $dados, true);
        registraLog('Inserção de novo Trafego.','i','tb_link_trafego',$insertID,"nome: $nome | ip_trafego: $ip | id_link_acesso: $link");
        $alert = ('Trafego inserido com sucesso!','s');
        header("location: /api/iframe?token=$request->token&view=troca-link-trafego"); 
    }

    function alterar($nome, $ip, $link, $id){

        $dados = array(       
            'nome' => $nome,
            'ip_trafego' => $ip,
            'id_link_acesso' => $link,
        );
        
        $insertID = DBUpdate('', 'tb_link_trafego', $dados, "id_link_trafego = $id");
        registraLog('Alteracao de Trafego.','a','tb_link_trafego',$insertID,"nome: $nome | ip_trafego: $ip | id_link_acesso: $link");
        $alert = ('Trafego alterado com sucesso!','s');
        header("location: /api/iframe?token=$request->token&view=troca-link-trafego"); 

    }

    function excluir ($id){

        DBDelete('', 'tb_link_trafego', "id_link_trafego = $id");
        registraLog('Remoção de Trafego.','e','tb_link_trafego', $id, '');
        $alert = ('Trafego excluido com sucesso!','s');
        header("location: /api/iframe?token=$request->token&view=troca-link-trafego"); 
    }
?>

