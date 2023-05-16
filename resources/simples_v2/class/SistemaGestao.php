<?php
require_once(__DIR__."/System.php");
require_once(__DIR__."/QuadroInformativoHistorico.php");


$id_tipo_sistema_gestao = (!empty($_POST['id_tipo_sistema_gestao'])) ? $_POST['id_tipo_sistema_gestao'] : '';
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

    //$verificacao_contrato = DBRead('', 'tb_sistema_gestao_contrato', "WHERE id_contrato_plano_pessoa = '".$id_contrato_plano_pessoa."'");

    //if(!$verificacao_contrato){
        inserir($id_tipo_sistema_gestao, $link, $observacao, $id_contrato_plano_pessoa, $dados_acesso, $ativacao, $pular, $adicionar_sistema, $id_proximo);
    /*}else{
        $alert = ('Item já existe na base de dados!');
        $alert_type = 'w';        if($ativacao == 1 && $adicionar_sistema == 1){
            header("location: /api/iframe?token=$request->token&view=sistema-gestao-form&ativacao=1&id_contrato=$id_contrato_plano_pessoa");
        }else{
            header("location: /api/iframe?token=$request->token&view=sistema-gestao-form");
        }
        exit;
    }*/

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

    //$verificacao_contrato = DBRead('', 'tb_sistema_gestao_contrato', "WHERE id_contrato_plano_pessoa = '".$id_contrato_plano_pessoa."' AND id_sistema_gestao_contrato != ".$id);
    
    //if(!$verificacao_contrato){
        alterar($id, $id_tipo_sistema_gestao, $link, $observacao, $id_contrato_plano_pessoa, $dados_acesso, $ativacao, $pular, $adicionar_sistema, $id_proximo);
    /*}else{
        $alert = ('Item já existe na base de dados!');
        $alert_type = 'w';        if($ativacao == 1){
            header("location: /api/iframe?token=$request->token&view=sistema-gestao-form&alterar=$id&ativacao=1&id_contrato=$id_contrato_plano_pessoa");
        }else{
            header("location: /api/iframe?token=$request->token&view=sistema-gestao-form&alterar=$id");
        }
        exit;
    }*/
    
} else if(isset($_GET['excluir'])){

    $id = (int)$_GET['excluir'];
    excluir($id, $exclui_ativacao, $id_contrato);
}else{
    header("location: ../adm.php");
    exit;
}

function inserir($id_tipo_sistema_gestao, $link, $observacao, $id_contrato_plano_pessoa, $dados_acesso, $ativacao, $pular, $adicionar_sistema, $id_proximo){

    $dados = array(
        'id_tipo_sistema_gestao' => $id_tipo_sistema_gestao,
        'link' => $link,
        'observacao' => $observacao,
        'id_contrato_plano_pessoa' => $id_contrato_plano_pessoa,

    );

    $insertID = DBCreate('', 'tb_sistema_gestao_contrato', $dados, true);
    registraLog('Inserção de novo sistema de gestão.','i','tb_sistema_gestao_contrato',$insertID,"id_tipo_sistema_gestao: $id_tipo_sistema_gestao | link: $link | observacao: $observacao | id_contrato_plano_pessoa: $id_contrato_plano_pessoa");

    // QUADRO INFORMATIVO HISTORICO
    $dados_tipo_sistema_gestao_historico = DBRead('','tb_tipo_sistema_gestao', "WHERE id_tipo_sistema_gestao = '".$id_tipo_sistema_gestao."' LIMIT 1");
    inserirHistorico($id_contrato_plano_pessoa, 1, "Inserção de sistema de gestão", "tipo_sistema_gestao: ".$dados_tipo_sistema_gestao_historico[0]['nome']." | link: $link | observacao: $observacao", 14);

    foreach($dados_acesso as $conteudo){
        if($conteudo['usuario'] && $conteudo['senha']){

            $dados_sistema_maior = DBRead('', 'tb_sistema_gestao_contrato a', "INNER JOIN tb_sistema_gestao_acesso b ON a.id_sistema_gestao_contrato = b.id_sistema_gestao_contrato WHERE a.id_contrato_plano_pessoa = '".$id_contrato_plano_pessoa."' ORDER BY b.contador DESC LIMIT 1");
            if($dados_sistema_maior){
                $contador = $dados_sistema_maior[0]['contador']+1;
            }else{
                $contador = 1;
            }

            $usuario = $conteudo['usuario'];
            $senha = $conteudo['senha'];

            $dadosUsuario = array(
                'usuario' => $usuario,
                'senha' => $senha,
                'contador' => $contador,
                'id_sistema_gestao_contrato' => $insertID
            );
            
            $insertUsuario = DBCreate('', 'tb_sistema_gestao_acesso', $dadosUsuario);
            registraLog('Inserção de novo usuário de sistema de gestão.','i','tb_sistema_gestao_acesso',$insertUsuario,"usuario: $usuario | senha: $senha | id_sistema_gestao_contrato: $insertID | contador: $contador");

            // QUADRO INFORMATIVO HISTORICO
            inserirHistorico($id_contrato_plano_pessoa, 1, "Inserção de sistema de gestão", "usuario: $usuario | senha: $senha", 14);
        }
    }

    if($ativacao == 1){

        if($pular && $adicionar_sistema == 0){
            header("location: /api/iframe?token=$request->token&view=sistema-chat-form&alterar=$pular&ativacao=1&dado_posterior=$insertID&id_contrato=$id_contrato_plano_pessoa");
        }else if($pular && $adicionar_sistema == 1){
            header("location: /api/iframe?token=$request->token&view=sistema-gestao-form&ativacao=1&id_contrato=$id_contrato_plano_pessoa");
        }else if($id_proximo && $adicionar_sistema == 1){
            header("location: /api/iframe?token=$request->token&view=sistema-gestao-form&ativacao=1&id_contrato=$id_contrato_plano_pessoa");
        }else if($adicionar_sistema == 1){
            header("location: /api/iframe?token=$request->token&view=sistema-gestao-form&ativacao=1&dado_posterior=$insertID&id_contrato=$id_contrato_plano_pessoa");
        }else if($adicionar_sistema == 0){
            header("location: /api/iframe?token=$request->token&view=sistema-chat-form&ativacao=1&dado_posterior=$insertID&id_contrato=$id_contrato_plano_pessoa");
        }

        /*if($pular){
            header("location: /api/iframe?token=$request->token&view=informacoes-gerais-form&alterar=$pular&ativacao=1&dado_posterior=$insertID&id_contrato=$id_contrato_plano_pessoa");
        }else{
            header("location: /api/iframe?token=$request->token&view=informacoes-gerais-form&ativacao=1&id_contrato=$id_contrato_plano_pessoa");
        }*/
        exit;
    }else{
        $alert = ('Item inserido com sucesso!');
    $alert_type = 's';        header("location: /api/iframe?token=$request->token&view=sistema-gestao-busca");
        exit;
    }
}

function alterar($id, $id_tipo_sistema_gestao, $link, $observacao, $id_contrato_plano_pessoa, $dados_acesso, $ativacao, $pular, $adicionar_sistema, $id_proximo){

    $dados = array(
        'id_tipo_sistema_gestao' => $id_tipo_sistema_gestao,
        'link' => $link,
        'observacao' => $observacao,
        'id_contrato_plano_pessoa' => $id_contrato_plano_pessoa,
    );
    DBUpdate('', 'tb_sistema_gestao_contrato', $dados, "id_sistema_gestao_contrato = $id");

    registraLog('Alteração de contrato de sistema de gestão.','a','tb_sistema_gestao_contrato',$id_tipo_sistema_gestao,"observacao: $observacao | id_contrato_plano_pessoa: $id_contrato_plano_pessoa");
    // QUADRO INFORMATIVO HISTORICO
    inserirHistorico($id_contrato_plano_pessoa, 2, "Alteração de sistema de gestão", "observacao: $observacao | id_contrato_plano_pessoa: $id_contrato_plano_pessoa", 14);

    DBDelete('', 'tb_sistema_gestao_acesso', "id_sistema_gestao_contrato = '$id'");
    foreach($dados_acesso as $conteudo){
        if($conteudo['usuario'] && $conteudo['senha']){
            
            $dados_sistema_maior = DBRead('', 'tb_sistema_gestao_contrato a', "INNER JOIN tb_sistema_gestao_acesso b ON a.id_sistema_gestao_contrato = b.id_sistema_gestao_contrato WHERE a.id_contrato_plano_pessoa = '".$id_contrato_plano_pessoa."' ORDER BY b.contador DESC LIMIT 1");
            if($dados_sistema_maior){
                $contador = $dados_sistema_maior[0]['contador']+1;
            }else{
                $contador = 1;
            }

            $usuario = $conteudo['usuario'];
            $senha = $conteudo['senha'];

            $dadosUsuario = array(
                'usuario' => $usuario,
                'senha' => $senha,
                'contador' => $contador,
                'id_sistema_gestao_contrato' => $id
            );
            
            $insertUsuario = DBCreate('', 'tb_sistema_gestao_acesso', $dadosUsuario);
            registraLog('Alteração de usuário de sistema de gestão.','a','tb_sistema_gestao_acesso',$insertUsuario,"usuario: $usuario | senha: $senha | id_sistema_gestao_contrato: $id | contador: $contador");
            // QUADRO INFORMATIVO HISTORICO
            inserirHistorico($id_contrato_plano_pessoa, 2, "Alteração de sistema de gestão", "usuario: $usuario | senha: $senha", 14);
        }
    }

    registraLog('Alteração de sistema de gestão.','a','tb_sistema_gestao_contrato',$id,"id_tipo_sistema_gestao: $id_tipo_sistema_gestao | link: $link | observacao: $observacao | id_contrato_plano_pessoa: $id_contrato_plano_pessoa| contador: 1");

    if($ativacao == 1){
        if($pular && $adicionar_sistema == 0){
            if($id_proximo && $adicionar_sistema == 1){
                header("location: /api/iframe?token=$request->token&view=sistema-gestao-form&alterar=$pular&ativacao=1&dado_posterior=$id&id_contrato=$id_contrato_plano_pessoa");
            }else if($id_proximo && $adicionar_sistema == 0){
                header("location: /api/iframe?token=$request->token&view=sistema-gestao-form&alterar=$pular&ativacao=1&dado_posterior=$id&id_contrato=$id_contrato_plano_pessoa");
            }else if($adicionar_sistema == 0){
                header("location: /api/iframe?token=$request->token&view=sistema-chat-form&alterar=$pular&ativacao=1&dado_posterior=$id&id_contrato=$id_contrato_plano_pessoa");
            }
        }else if($pular && $adicionar_sistema == 1){
            header("location: /api/iframe?token=$request->token&view=sistema-gestao-form&ativacao=1&id_contrato=$id_contrato_plano_pessoa");
        }else if($id_proximo){
            header("location: /api/iframe?token=$request->token&view=sistema-gestao-form&ativacao=1&id_contrato=$id_contrato_plano_pessoa");
        }else if($adicionar_sistema == 1){
            header("location: /api/iframe?token=$request->token&view=sistema-gestao-form&ativacao=1&dado_posterior=$id&id_contrato=$id_contrato_plano_pessoa");
        }else if($adicionar_sistema == 0){
            header("location: /api/iframe?token=$request->token&view=sistema-chat-form&ativacao=1&dado_posterior=$id&id_contrato=$id_contrato_plano_pessoa");
        }
    }else{
        $alert = ('Item alterado com sucesso!');
    $alert_type = 's';        header("location: /api/iframe?token=$request->token&view=sistema-gestao-busca");
        exit;
    }

    /*if($ativacao == 1){

        if($pular){
            header("location: /api/iframe?token=$request->token&view=informacoes-gerais-form&alterar=$pular&ativacao=1&dado_posterior=$id&id_contrato=$id_contrato_plano_pessoa");
        }else{
            header("location: /api/iframe?token=$request->token&view=informacoes-gerais-form&ativacao=1&dado_posterior=$id&id_contrato=$id_contrato_plano_pessoa");
        }
        exit;
    }else{
        $alert = ('Item alterado com sucesso!');
    $alert_type = 's';        header("location: /api/iframe?token=$request->token&view=sistema-gestao-busca");
        exit;
    }*/
}

function excluir($id, $exclui_ativacao, $id_contrato){

    /*if($exclui_ativacao == 1){

        $dados = DBRead('', 'tb_informacao_geral_contrato', "WHERE id_contrato_plano_pessoa = $id_contrato");

        $query = "DELETE FROM tb_sistema_gestao_contrato WHERE id_sistema_gestao_contrato = $id";
        $link = DBConnect('');
        $result = @mysqli_query($link, $query);
        DBClose($link);
        registraLog('Exclusão de sistema de gestão.','e','tb_sistema_gestao_contrato',$id,'');
        if(!$result){
            $alert = ('Erro ao excluir item!');
            $alert_type = 'd';        }else{
            $alert = ('Item excluído com sucesso!');
    $alert_type = 's';        }

        if($dados){
            $dado_posterior = $dados[0]['id_informacao_geral_contrato'];
            header("location: /api/iframe?token=$request->token&view=informacoes-gerais-form&alterar=$dado_posterior&ativacao=1&id_contrato=$id_contrato");
        }else{
            header("location: /api/iframe?token=$request->token&view=informacoes-gerais-form&ativacao=1&id_contrato=$id_contrato");
        }
        exit;
    }else{

        $query = "DELETE FROM tb_sistema_gestao_contrato WHERE id_sistema_gestao_contrato = $id";
        $link = DBConnect('');
        $result = @mysqli_query($link, $query);
        DBClose($link);
        registraLog('Exclusão de sistema de gestão.','e','tb_sistema_gestao_contrato',$id,'');
        if(!$result){
            $alert = ('Erro ao excluir item!');
            $alert_type = 'd';        }else{
            $alert = ('Item excluído com sucesso!');
    $alert_type = 's';        }
        header("location: /api/iframe?token=$request->token&view=sistema-gestao-busca");
        exit;
    }*/

    if($exclui_ativacao == 1){
        $dados_sistema = DBRead('', 'tb_sistema_gestao_contrato', "WHERE id_contrato_plano_pessoa = $id_contrato AND id_sistema_gestao_contrato > $id");
        if($dados_sistema){
            $dados = DBRead('', 'tb_sistema_gestao_contrato', "WHERE id_contrato_plano_pessoa = $id_contrato AND id_sistema_gestao_contrato > $id");
        }else{
            $dados = DBRead('', 'tb_sistema_chat_contrato', "WHERE id_contrato_plano_pessoa = $id_contrato");
        }
        $query = "DELETE FROM tb_sistema_gestao_contrato WHERE id_sistema_gestao_contrato = $id";
        $link = DBConnect('');
        $result = @mysqli_query($link, $query);
        DBClose($link);
        registraLog('Exclusão de sistema de gestão.', 'e', 'tb_sistema_gestao_contrato', $id, '');

        // QUADRO INFORMATIVO HISTORICO
        inserirHistorico($id_contrato, 3, "Exclusão de sistema de gestão", "Excluiu dados", 14);

        if(!$result){
            $alert = ('Erro ao excluir item!', 'd');
        }else{
            $alert = ('Item excluído com sucesso!', 's');
        }
        if($dados){
            if($dados_sistema){
                $dado_posterior = $dados[0]['id_sistema_gestao_contrato'];
                header("location: /api/iframe?token=$request->token&view=sistema-gestao-form&alterar=$dado_posterior&ativacao=1&id_contrato=$id_contrato");
            }else{
                $dado_posterior = $dados[0]['id_sistema_chat_contrato'];
                header("location: /api/iframe?token=$request->token&view=sistema-chat-form&alterar=$dado_posterior&ativacao=1&id_contrato=$id_contrato");
            }
        }else{
            header("location: /api/iframe?token=$request->token&view=sistema-chat-form&ativacao=1&id_contrato=$id_contrato");
        }
    }else{
        $id_contrato = DBRead('', 'tb_sistema_gestao_contrato', "WHERE id_sistema_gestao_contrato = ".$id."");
        $id_contrato = $id_contrato[0]['id_contrato_plano_pessoa'];

        $query = "DELETE FROM tb_sistema_gestao_contrato WHERE id_sistema_gestao_contrato = $id";
        $link = DBConnect('');
        $result = @mysqli_query($link, $query);
        DBClose($link);
        registraLog('Exclusão de sistema de gestão.','e','tb_sistema_gestao_contrato',$id,'');

        // QUADRO INFORMATIVO HISTORICO
        inserirHistorico($id_contrato, 3, "Exclusão de sistema de gestão", "Excluiu dados", 14);

        if(!$result){
            $alert = ('Erro ao excluir item!');
            $alert_type = 'd';        }else{
            $alert = ('Item excluído com sucesso!');
    $alert_type = 's';        }
        header("location: /api/iframe?token=$request->token&view=sistema-gestao-busca");
    }

    

    exit;



}

?>