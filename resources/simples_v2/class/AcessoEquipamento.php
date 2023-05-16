<?php
require_once(__DIR__."System.php");
require_once(__DIR__."QuadroInformativoHistorico.php");

$observacao = (!empty($_POST['observacao'])) ? $_POST['observacao'] : '';
$id_contrato_plano_pessoa  = (!empty($_POST['id_contrato_plano_pessoa'])) ? $_POST['id_contrato_plano_pessoa'] : '';
$usuario = (!empty($_POST['usuario'])) ? $_POST['usuario'] : '';
$senha = (!empty($_POST['senha'])) ? $_POST['senha'] : '';

$ativacao = (!empty($_POST['ativacao'])) ? $_POST['ativacao'] : 0;
$pular = (!empty($_POST['pular'])) ? $_POST['pular'] : '';
$voltar = (!empty($_POST['voltar'])) ? $_POST['voltar'] : '';
$salvar = (!empty($_POST['salvar'])) ? $_POST['salvar'] : 0;
$exclui_ativacao = (!empty($_GET['exclui-ativacao'])) ? $_GET['exclui-ativacao'] : 0;
$id_contrato = (!empty($_GET['id-contrato'])) ? $_GET['id-contrato'] : '';

if (!empty($_POST['inserir'])) {

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

    $verificacao_contrato = DBRead('', 'tb_equipamento_contrato', "WHERE id_contrato_plano_pessoa = '".$id_contrato_plano_pessoa."'");

    if(!$verificacao_contrato){
        inserir($observacao, $id_contrato_plano_pessoa, $dados_acesso, $ativacao, $pular, $voltar, $salvar);
    }else{
        $alert = ('Item já existe na base de dados!');
        $alert_type = 'w';        
        header("location: /api/iframe?token=$request->token&view=acesso-equipamento-form&alert=$alert&alert_type=$alert_type");
        
        exit;
    }

} else if (!empty($_POST['alterar'])) {
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

    $verificacao_contrato = DBRead('', 'tb_equipamento_contrato', "WHERE id_contrato_plano_pessoa = '".$id_contrato_plano_pessoa."' AND id_equipamento_contrato != " . $id);

    if(!$verificacao_contrato){
        alterar($id, $observacao, $id_contrato_plano_pessoa, $dados_acesso, $ativacao, $pular, $voltar, $salvar);
    }else{
        $alert = ('Item já existe na base de dados!');
        $alert_type = 'w';
        header("location: /api/iframe?token=$request->token&view=acesso-equipamento-form&alterar=$id&alert=$alert&alert_type=$alert_type");
        exit;
    }
    
} else if (isset($_GET['excluir'])) {

    $id = (int)$_GET['excluir'];
    excluir($id, $exclui_ativacao, $id_contrato);
} else {
    header("location: /api/iframe?token=$request->token&view=acesso-equipamento-busca");
    exit;
}

function inserir($observacao, $id_contrato_plano_pessoa, $dados_acesso, $ativacao, $pular, $voltar, $salvar){

    $dados = array(
        'observacao' => $observacao,
        'id_contrato_plano_pessoa' => $id_contrato_plano_pessoa
    );
    
    $insertID = DBCreate('', 'tb_equipamento_contrato', $dados, true);
    registraLog('Inserção de novo equipamento acesso.','i','tb_equipamento_contrato',$insertID,"observacao: $observacao | id_contrato_plano_pessoa: $id_contrato_plano_pessoa");
    
    // QUADRO INFORMATIVO HISTORICO
    inserirHistorico($id_contrato_plano_pessoa, 1, "Inserção de novo equipamento acesso", "observação: $observacao", 1);

    if($dados){

        foreach($dados_acesso as $conteudo){

            $usuario = $conteudo['usuario'];
            $senha = $conteudo['senha'];

            if($conteudo['usuario'] && $conteudo['senha']){
                $dadosUsuario = array(
                    'usuario' => $usuario,
                    'senha' => $senha,
                    'id_equipamento_contrato' => $insertID
                );
                
                $insertUsuario = DBCreate('', 'tb_equipamento_acesso', $dadosUsuario);
                registraLog('Inserção de novo equipamento acesso.','i','tb_equipamento_acesso',$insertUsuario,"usuario: $usuario | senha: $senha | id_equipamento_contrato: $insertID");

                // QUADRO INFORMATIVO HISTORICO
                inserirHistorico($id_contrato_plano_pessoa, 1, "Inserção de novo equipamento acesso", "usuario: $usuario | senha: $senha", 1);

            }
        }
        $alert = ('Item inserido com sucesso!');
        $alert_type = 's';        

    }

    if($ativacao == 1){

        if($pular){
            //header("location: /api/iframe?token=$request->token&view=radio-sinal-form&alterar=$pular&ativacao=1&dado_posterior=$insertID&id_contrato=$id_contrato_plano_pessoa");
            header("location: /api/iframe?token=$request->token&view=acesso-equipamento-form&alterar=$pular&ativacao=1&dado_posterior=$insertID&id_contrato=$id_contrato_plano_pessoa&alert=$alert&alert_type=$alert_type");

        }else if($voltar && $salvar != 1){
            header("location: /api/iframe?token=$request->token&view=reinicio-equipamento-form&alterar=$voltar&ativacao=1&dado_posterior=$insertID&id_contrato=$id_contrato_plano_pessoa&alert=$alert&alert_type=$alert_type");

        }else{
            header("location: /api/iframe?token=$request->token&view=acesso-equipamento-form&ativacao=1&dado_posterior=$insertID&id_contrato=$id_contrato_plano_pessoa&alert=$alert&alert_type=$alert_type");
        }
    }else{
        
        header("location: /api/iframe?token=$request->token&view=acesso-equipamento-busca&alert=$alert&alert_type=$alert_type");
    }
    exit;
}

function alterar($id, $observacao, $id_contrato_plano_pessoa, $dados_acesso, $ativacao, $pular, $voltar, $salvar){

    $dados = array(
        'observacao' => $observacao,
        'id_contrato_plano_pessoa' => $id_contrato_plano_pessoa
    );

    DBUpdate('', 'tb_equipamento_contrato', $dados, "id_equipamento_contrato = $id");
    registraLog('Alteração de acesso.','a','tb_equipamento_contrato',$id,"observacao: $observacao | id_contrato_plano_pessoa: $id_contrato_plano_pessoa");

    // QUADRO INFORMATIVO HISTORICO
    inserirHistorico($id_contrato_plano_pessoa, 2, "Alteração de equipamento acesso", "observação: $observacao", 1);

    DBDelete('', 'tb_equipamento_acesso', "id_equipamento_contrato = '$id'");
    foreach($dados_acesso as $conteudo){
        if($conteudo['usuario'] && $conteudo['senha']){

            $usuario = $conteudo['usuario'];
            $senha = $conteudo['senha'];
            
            $dadosUsuario = array(
                'usuario' => $usuario,
                'senha' => $senha,
                'id_equipamento_contrato' => $id
            );
            
            $insertID = DBCreate('', 'tb_equipamento_acesso', $dadosUsuario);
            registraLog('Alteração de usuário de acesso.','a','tb_equipamento_acesso',$insertID,"usuario: $usuario | senha: $senha");

            // QUADRO INFORMATIVO HISTORICO
            inserirHistorico($id_contrato_plano_pessoa, 2, "Alteração de equipamento acesso", "usuario: $usuario | senha: $senha", 1);
        }
    }

    $alert = ('Item alterado com sucesso!');
    $alert_type = 's';
    if($ativacao == 1){
        if($pular){
            header("location: /api/iframe?token=$request->token&view=sinal-equipamento-form&alterar=$pular&ativacao=1&dado_posterior=$insertID&id_contrato=$id_contrato_plano_pessoa&alert=$alert&alert_type=$alert_type");
        }else if($voltar && $salvar != 1){
            header("location: /api/iframe?token=$request->token&view=reinicio-equipamento-form&alterar=$voltar&ativacao=1&dado_posterior=$insertID&id_contrato=$id_contrato_plano_pessoa&alert=$alert&alert_type=$alert_type");
        }else{
            header("location: /api/iframe?token=$request->token&view=sinal-equipamento-form&ativacao=1&dado_posterior=$insertID&id_contrato=$id_contrato_plano_pessoa");
        }
    }else{
        header("location: /api/iframe?token=$request->token&view=acesso-equipamento-busca&alert=$alert&alert_type=$alert_type");
    }
    exit;
}

function excluir($id, $exclui_ativacao, $id_contrato){

    if($exclui_ativacao == 1){

        $dados = DBRead('', 'tb_sinal_equipamento_contrato', "WHERE id_contrato_plano_pessoa = $id_contrato");

        $query = "DELETE FROM tb_equipamento_contrato WHERE id_equipamento_contrato = $id";
        $link = DBConnect('');
        $result = @mysqli_query($link, $query);
        DBClose($link);

        registraLog('Exclusão de equipamento acesso.','e','tb_equipamento_contrato',$id,'');
        
        // QUADRO INFORMATIVO HISTORICO
        inserirHistorico($id_contrato, 3, "Exclusão de equipamento acesso", "Excluiu dados", 1);

        if (!$result) {
            $alert = ('Erro ao excluir item!');
            $alert_type = 'd';        
        } else {
            $alert = ('Item excluído com sucesso!');
            $alert_type = 's';        
        }

        if($dados){
            $dado_posterior = $dados[0]['id_equipamento_contrato'];
            header("location: /api/iframe?token=$request->token&view=sinal-equipamento-form&alterar=$dado_posterior&ativacao=1&id_contrato=$id_contrato&alert=$alert&alert_type=$alert_type");
        }else{
            header("location: /api/iframe?token=$request->token&view=sinal-equipamento-form&ativacao=1&id_contrato=$id_contrato&alert=$alert&alert_type=$alert_type");
        }

    }else{
        $id_contrato = DBRead('', 'tb_equipamento_contrato', "WHERE id_equipamento_contrato = ".$id."");
        $id_contrato = $id_contrato[0]['id_contrato_plano_pessoa'];
      
        $query = "DELETE FROM tb_equipamento_contrato WHERE id_equipamento_contrato = $id";
        $link = DBConnect('');
        $result = @mysqli_query($link, $query);
        DBClose($link);

        registraLog('Exclusão de equipamento acesso.','e','tb_equipamento_contrato',$id,'');
        
        // QUADRO INFORMATIVO HISTORICO
        inserirHistorico($id_contrato, 3, "Exclusão de equipamento acesso", "Excluiu dados", 1);

        if(!$result){
            $alert = ('Erro ao excluir item!');
            $alert_type = 'd';        
        }else{
            $alert = ('Item excluído com sucesso!');
            $alert_type = 's';        
        }
        header("location: /api/iframe?token=$request->token&view=acesso-equipamento-busca&alert=$alert&alert_type=$alert_type");
    }
    exit;
}

?>