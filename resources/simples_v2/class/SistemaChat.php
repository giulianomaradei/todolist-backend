<?php
require_once(__DIR__."/System.php");
require_once(__DIR__."/QuadroInformativoHistorico.php");


$id_tipo_sistema_chat = (!empty($_POST['id_tipo_sistema_chat'])) ? $_POST['id_tipo_sistema_chat'] : '';
$link = (!empty($_POST['link'])) ? $_POST['link'] : '';
// $id_link_acesso = (!empty($_POST['id_link_acesso'])) ? $_POST['id_link_acesso'] : '';
$observacao = (!empty($_POST['observacao'])) ? $_POST['observacao'] : '';
$id_contrato_plano_pessoa  = (!empty($_POST['id_contrato_plano_pessoa'])) ? $_POST['id_contrato_plano_pessoa'] : '';
$usuario = (!empty($_POST['usuario'])) ? $_POST['usuario'] : '';
$senha = (!empty($_POST['senha'])) ? $_POST['senha'] : '';

$adicionar_sistema = (!empty($_POST['adicionar_sistema'])) ? $_POST['adicionar_sistema'] : 0;
$id_proximo = (!empty((int) $_POST['id_proximo'])) ? (int) $_POST['id_proximo'] : 0;

$ativacao = (!empty($_POST['ativacao'])) ? $_POST['ativacao'] : '';
$pular = (!empty($_POST['pular']) ? $_POST['pular'] : '');
$exclui_ativacao = (!empty($_GET['exclui-ativacao'])) ? $_GET['exclui-ativacao'] : 0;
$id_contrato = (!empty($_GET['id-contrato'])) ? $_GET['id-contrato'] : '';

if(!empty($_POST['inserir'])){

    $id = (int)$_POST['inserir'];
    
    $cont = 0;
    $dados_acesso = array();
    foreach($usuario as $conteudo){
        $dados_acesso[$cont]['usuario'] = $conteudo;
        $cont++;
    }
    $cont = 0;
    foreach($senha as $conteudo){
        $dados_acesso[$cont]['senha'] = $conteudo;
        $cont++;
    }

    inserir($id_tipo_sistema_chat, $link, $observacao, $id_contrato_plano_pessoa, $dados_acesso, $ativacao, $pular, $adicionar_sistema, $id_proximo);
  
}else if(!empty($_POST['alterar'])){

    $id = (int)$_POST['alterar'];

    $cont = 0;
    $dados_acesso = array();
    foreach($usuario as $conteudo){
        $dados_acesso[$cont]['usuario'] = $conteudo;
        $cont++;
    }
    $cont = 0;
    foreach($senha as $conteudo){
        $dados_acesso[$cont]['senha'] = $conteudo;
        $cont++;
    }

    alterar($id, $id_tipo_sistema_chat, $link, $observacao, $id_contrato_plano_pessoa, $dados_acesso, $ativacao, $pular, $adicionar_sistema, $id_proximo);

} else if(isset($_GET['excluir'])){

    $id = (int)$_GET['excluir'];
    excluir($id, $exclui_ativacao, $id_contrato);
}else{
    header("location: ../adm.php");
    exit;
}

function inserir($id_tipo_sistema_chat, $link, $observacao, $id_contrato_plano_pessoa, $dados_acesso, $ativacao, $pular, $adicionar_sistema, $id_proximo){

    $dados = array(
        'id_tipo_sistema_chat' => $id_tipo_sistema_chat,
        'link' => $link,
        'observacao' => $observacao,
        'id_contrato_plano_pessoa' => $id_contrato_plano_pessoa,
    );

    $insertID = DBCreate('', 'tb_sistema_chat_contrato', $dados, true);
    registraLog('Inserção de novo sistema de chat.','i','tb_sistema_chat_contrato',$insertID,"id_tipo_sistema_chat: $id_tipo_sistema_chat | link: $link | observacao: $observacao | id_contrato_plano_pessoa: $id_contrato_plano_pessoa");

    // QUADRO INFORMATIVO HISTORICO
    $dados_tipo_sistema_chat_historico = DBRead('','tb_tipo_sistema_chat', "WHERE id_tipo_sistema_chat = '".$id_tipo_sistema_chat."' LIMIT 1");
    inserirHistorico($id_contrato_plano_pessoa, 1, "Inserção de sistema de chat", "observação: $observacao | tipo_sistema_chat: ".$dados_tipo_sistema_chat_historico[0]['nome']." | link: $link | observacao: $observacao", 15);

    foreach($dados_acesso as $conteudo){
        if($conteudo['usuario'] && $conteudo['senha']){

            $usuario = $conteudo['usuario'];
            $senha = $conteudo['senha'];

            $dadosUsuario = array(
                'usuario' => $usuario,
                'senha' => $senha,
                'id_sistema_chat_contrato' => $insertID
            );
            
            $insertUsuario = DBCreate('', 'tb_sistema_chat_acesso', $dadosUsuario);
            registraLog('Inserção de novo usuário de sistema de chat.','i','tb_sistema_chat_acesso',$insertUsuario,"usuario: $usuario | senha: $senha | id_sistema_chat_contrato: $insertID");

            // QUADRO INFORMATIVO HISTORICO
            inserirHistorico($id_contrato_plano_pessoa, 1, "Inserção de sistema de chat", "usuario: $usuario | senha: $senha", 15);
        }
    }

    if($ativacao == 1){

        if($pular && $adicionar_sistema == 0){
            header("location: /api/iframe?token=$request->token&view=informacoes-gerais-form&alterar=$pular&ativacao=1&dado_posterior=$insertID&id_contrato=$id_contrato_plano_pessoa");
        }else if($pular && $adicionar_sistema == 1){
            header("location: /api/iframe?token=$request->token&view=sistema-chat-form&ativacao=1&id_contrato=$id_contrato_plano_pessoa");
        }else if($id_proximo && $adicionar_sistema == 1){
            header("location: /api/iframe?token=$request->token&view=sistema-chat-form&ativacao=1&id_contrato=$id_contrato_plano_pessoa");
        }else if($adicionar_sistema == 1){
            header("location: /api/iframe?token=$request->token&view=sistema-chat-form&ativacao=1&dado_posterior=$insertID&id_contrato=$id_contrato_plano_pessoa");
        }else if($adicionar_sistema == 0){
            header("location: /api/iframe?token=$request->token&view=informacoes-gerais-form&ativacao=1&dado_posterior=$insertID&id_contrato=$id_contrato_plano_pessoa");
        }

        exit;
    }else{
        $alert = ('Item inserido com sucesso!');
    $alert_type = 's';        header("location: /api/iframe?token=$request->token&view=sistema-chat-busca");
        exit;
    }
}

function alterar($id, $id_tipo_sistema_chat, $link, $observacao, $id_contrato_plano_pessoa, $dados_acesso, $ativacao, $pular, $adicionar_sistema, $id_proximo){

    $dados = array(
        'id_tipo_sistema_chat' => $id_tipo_sistema_chat,
        'link' => $link,
        'observacao' => $observacao,
        'id_contrato_plano_pessoa' => $id_contrato_plano_pessoa,
    );
    DBUpdate('', 'tb_sistema_chat_contrato', $dados, "id_sistema_chat_contrato = $id");
   
    registraLog('Alteração de sistema de chat.','a','tb_sistema_chat_contrato',$id,"id_tipo_sistema_chat: $id_tipo_sistema_chat | link: $link | observacao: $observacao | id_contrato_plano_pessoa: $id_contrato_plano_pessoa");
    // QUADRO INFORMATIVO HISTORICO
    $dados_tipo_sistema_chat_historico = DBRead('','tb_tipo_sistema_chat', "WHERE id_tipo_sistema_chat = '".$id_tipo_sistema_chat."' LIMIT 1");
    
    inserirHistorico($id_contrato_plano_pessoa, 2, "Alteração de sistema de chat", "observação: $observacao | tipo_sistema_chat: ".$dados_tipo_sistema_chat_historico[0]['nome']." | link: $link | observacao: $observacao", 15);

    DBDelete('', 'tb_sistema_chat_acesso', "id_sistema_chat_contrato = '$id'");
    foreach($dados_acesso as $conteudo){
        if($conteudo['usuario'] && $conteudo['senha']){
            
            $usuario = $conteudo['usuario'];
            $senha = $conteudo['senha'];

            $dadosUsuario = array(
                'usuario' => $usuario,
                'senha' => $senha,
                'id_sistema_chat_contrato' => $id
            );
            
            $insertUsuario = DBCreate('', 'tb_sistema_chat_acesso', $dadosUsuario);
            registraLog('Alteração de usuário de sistema de chat.','a','tb_sistema_chat_acesso',$insertUsuario,"usuario: $usuario | senha: $senha | id_sistema_chat_contrato: $id");
            // QUADRO INFORMATIVO HISTORICO
            inserirHistorico($id_contrato_plano_pessoa, 2, "Alteração de sistema de chat", "usuario: $usuario | senha: $senha", 15);
        }
    }

    if($ativacao == 1){
        if($pular && $adicionar_sistema == 0){
            if($id_proximo && $adicionar_sistema == 1){
                header("location: /api/iframe?token=$request->token&view=sistema-chat-form&alterar=$pular&ativacao=1&dado_posterior=$id&id_contrato=$id_contrato_plano_pessoa");
            }else if($id_proximo && $adicionar_sistema == 0){
                header("location: /api/iframe?token=$request->token&view=sistema-chat-form&alterar=$pular&ativacao=1&dado_posterior=$id&id_contrato=$id_contrato_plano_pessoa");
            }else if($adicionar_sistema == 0){
                header("location: /api/iframe?token=$request->token&view=informacoes-gerais-form&alterar=$pular&ativacao=1&dado_posterior=$id&id_contrato=$id_contrato_plano_pessoa");
            }
        }else if($pular && $adicionar_sistema == 1){
            header("location: /api/iframe?token=$request->token&view=sistema-chat-form&ativacao=1&id_contrato=$id_contrato_plano_pessoa");
        }else if($id_proximo){
            header("location: /api/iframe?token=$request->token&view=sistema-chat-form&ativacao=1&id_contrato=$id_contrato_plano_pessoa");
        }else if($adicionar_sistema == 1){
            header("location: /api/iframe?token=$request->token&view=sistema-chat-form&ativacao=1&dado_posterior=$id&id_contrato=$id_contrato_plano_pessoa");
        }else if($adicionar_sistema == 0){
            header("location: /api/iframe?token=$request->token&view=informacoes-gerais-form&ativacao=1&dado_posterior=$id&id_contrato=$id_contrato_plano_pessoa");
        }
    }else{
        $alert = ('Item alterado com sucesso!');
    $alert_type = 's';        header("location: /api/iframe?token=$request->token&view=sistema-chat-busca");
        exit;
    }

}

function excluir($id, $exclui_ativacao, $id_contrato){

    if($exclui_ativacao == 1){
       
        $query = "DELETE FROM tb_sistema_chat_contrato WHERE id_sistema_chat_contrato = $id";
        $link = DBConnect('');
        $result = @mysqli_query($link, $query);
        DBClose($link);
        registraLog('Exclusão de sistema de chat.', 'e', 'tb_sistema_chat_contrato', $id, '');

        // QUADRO INFORMATIVO HISTORICO
        inserirHistorico($id_contrato, 3, "Exclusão de sistema de chat", "Excluiu dados", 15);

        if(!$result){
            $alert = ('Erro ao excluir item!', 'd');
        }else{
            $alert = ('Item excluído com sucesso!', 's');
        }

        $dados = DBRead('', 'tb_informacao_geral_contrato', "WHERE id_contrato_plano_pessoa = $id_contrato");

        $dado_posterior = $dados[0]['id_informacao_geral_contrato'];
        header("location: /api/iframe?token=$request->token&view=informacoes-gerais-form&alterar=$dado_posterior&ativacao=1&id_contrato=$id_contrato");
        
    }else{
        $id_contrato = DBRead('', 'tb_sistema_chat_contrato', "WHERE id_sistema_chat_contrato = ".$id."");
        $id_contrato = $id_contrato[0]['id_contrato_plano_pessoa'];
        
        $query = "DELETE FROM tb_sistema_chat_contrato WHERE id_sistema_chat_contrato = $id";
        $link = DBConnect('');
        $result = @mysqli_query($link, $query);
        DBClose($link);
        registraLog('Exclusão de sistema de chat.','e','tb_sistema_chat_contrato',$id,'');

        // QUADRO INFORMATIVO HISTORICO
        inserirHistorico($id_contrato, 3, "Exclusão de sistema de chat", "Excluiu dados", 15);

        if(!$result){
            $alert = ('Erro ao excluir item!');
            $alert_type = 'd';        }else{
            $alert = ('Item excluído com sucesso!');
    $alert_type = 's';        }
        header("location: /api/iframe?token=$request->token&view=sistema-chat-busca");
    }

    

    exit;



}

?>