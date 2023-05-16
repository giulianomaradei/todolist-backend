<?php
    require_once(__DIR__."/System.php");
    

    $descricao = (!empty($_POST['descricao'])) ? $_POST['descricao'] : '';

    if (!empty($_POST['inserir'])) {

        $dados = DBRead('', 'tb_lead_tag', "WHERE BINARY descricao = '".addslashes($descricao)."'");
        if (!$dados) {
            inserir($descricao);
        } else {        
            $alert = ('Item já existe na base de dados!');
        $alert_type = 'w';            header("location: /api/iframe?token=$request->token&view=lead-tag-busca");
            exit;
        } 

    } else if (!empty($_POST['alterar'])) {
        $id = (int)$_POST['alterar'];

        $dados = DBRead('', 'tb_lead_tag', "WHERE BINARY descricao = '".addslashes($descricao)."'");
        if (!$dados) {
            alterar($id, $descricao);
        } else {
            $alert = ('Item já existe na base de dados!');
        $alert_type = 'w';            header("location: /api/iframe?token=$request->token&view=lead-tag-busca");
            exit;
        }

    } else if (!empty($_GET['excluir'])) {
        $id = (int)$_GET['excluir'];
        excluir($id);
    }

    function inserir ($descricao) {
        
        if($descricao !=''){

            $dados = array(
                'descricao' => $descricao,
                'status' => 1
            );

            $insertID = DBCreate('', 'tb_lead_tag', $dados, true);
            registraLog('Inserção de lead tag.', 'i', 'tb_lead_tag', $insertID, "descricao: $descricao | status: 1");

            $alert = ('Item criado com sucesso!','s');
            header("location: /api/iframe?token=$request->token&view=lead-tag-busca");
            
        }else{
            $alert = ('Não foi possivel criar item!','d');
            header("location: /api/iframe?token=$request->token&view=lead-tag-busca");
        }
    }

    function alterar($id, $descricao) {
        if($descricao !='' && $id !=''){

            $dados = array(
                'descricao' => $descricao,
            );

            DBUpdate('', 'tb_lead_tag', $dados, "id_lead_tag = '$id'");
            registraLog('Alteração de lead tag.', 'a', 'tb_lead_tag', $insertID, "descricao: $descricao");

            $alert = ('Item alterado com sucesso!');
    $alert_type = 's';            header("location: /api/iframe?token=$request->token&view=lead-tag-busca");
            
        }else{
            $alert = ('Não foi possivel alterar item!','d');
            header("location: /api/iframe?token=$request->token&view=lead-tag-busca");
        }
    }

    function excluir ($id) {
        if($id !=''){

            DBDelete('', 'tb_lead_tag', "id_lead_tag = '$id'");
            registraLog('Exclusão de lead tag.', 'e', 'tb_lead_tag', $id, "id: $id");

            $alert = ('Item excluído com sucesso!');
    $alert_type = 's';            header("location: /api/iframe?token=$request->token&view=lead-tag-busca");
            
        }else{
            $alert = ('Não foi possivel excluir item!','d');
            header("location: /api/iframe?token=$request->token&view=lead-tag-busca");
        }
    }

?>