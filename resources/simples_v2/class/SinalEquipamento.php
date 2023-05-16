<?php
require_once(__DIR__."/System.php");
require_once(__DIR__."/QuadroInformativoHistorico.php");


$id_contrato_plano_pessoa  = (!empty($_POST['id_contrato_plano_pessoa'])) ? $_POST['id_contrato_plano_pessoa'] : '';
$nome    = (!empty($_POST['nome'])) ? $_POST['nome'] : '';
$observacao    = (!empty($_POST['observacao'])) ? $_POST['observacao'] : '';
$intensidade_sinal      = (!empty($_POST['intensidade_sinal'])) ? $_POST['intensidade_sinal'] : '';

$ativacao = (!empty($_POST['ativacao'])) ? $_POST['ativacao'] : 0;
$pular = (!empty($_POST['pular'])) ? $_POST['pular'] : '';
$voltar = (!empty($_POST['voltar'])) ? $_POST['voltar'] : '';
$salvar = (!empty($_POST['salvar'])) ? $_POST['salvar'] : 0;
$exclui_ativacao = (!empty($_GET['exclui-ativacao'])) ? $_GET['exclui-ativacao'] : 0;
$id_contrato = (!empty($_GET['id-contrato'])) ? $_GET['id-contrato'] : '';

if(!empty($_POST['inserir'])){

    $cont = 0;
    $dados_sinal = array();
    foreach($nome as $conteudo){
        $dados_sinal[$cont]['nome'] = $conteudo;
        $cont++;
    }

    $cont = 0;
    foreach($intensidade_sinal as $conteudo){
        $dados_sinal[$cont]['intensidade_sinal'] = $conteudo;
        $cont++;
    }

    $cont = 0;
    foreach($observacao as $conteudo){
        $dados_sinal[$cont]['observacao'] = $conteudo;
        $cont++;
    }

    $verificacao_contrato = DBRead('', 'tb_sinal_equipamento_contrato', "WHERE id_contrato_plano_pessoa = '".$id_contrato_plano_pessoa."'");

    if(!$verificacao_contrato){
        inserir($id_contrato_plano_pessoa, $dados_sinal, $ativacao, $pular, $voltar, $salvar);
    }else{
        $alert = ('Item já existe na base de dados!');
        $alert_type = 'w';        header("location: /api/iframe?token=$request->token&view=sinal-equipamento-form");
        exit;
    }

}else if(!empty($_POST['alterar'])){
    $id = (int)$_POST['alterar'];

    $cont = 0;
    $dados_sinal = array();
    foreach($nome as $conteudo){
        $dados_sinal[$cont]['nome'] = $conteudo;
        $cont++;
    }

    $cont = 0;
    foreach($intensidade_sinal as $conteudo){
        $dados_sinal[$cont]['intensidade_sinal'] = $conteudo;
        $cont++;
    }

    $cont = 0;
    foreach($observacao as $conteudo){
        $dados_sinal[$cont]['observacao'] = $conteudo;
        $cont++;
    }

    $verificacao_contrato = DBRead('', 'tb_sinal_equipamento_contrato', "WHERE id_contrato_plano_pessoa = '".$id_contrato_plano_pessoa."' AND id_sinal_equipamento_contrato != ".$id);

    if(!$verificacao_contrato){
        alterar($id, $id_contrato_plano_pessoa, $dados_sinal, $ativacao, $pular, $voltar, $salvar);
    }else{
        $alert = ('Item já existe na base de dados!');
        $alert_type = 'w';        header("location: /api/iframe?token=$request->token&view=sinal-equipamento-form&alterar=$id");
        exit;
    }
    $alert = ('Item já existe na base de dados!','w');
    header("location: /api/iframe?token=$request->token&view=sinal-equipamento-form&alterar=$id");
    exit;
    
} else if(isset($_GET['excluir'])){

    $id = (int)$_GET['excluir'];
    excluir($id, $exclui_ativacao, $id_contrato);
}else{
    header("location: ../adm.php");
    exit;
}

function inserir($id_contrato_plano_pessoa, $dados_sinal, $ativacao, $pular, $voltar, $salvar){

    $dados = array(
        'id_contrato_plano_pessoa' => $id_contrato_plano_pessoa
    );

    $insertID = DBCreate('', 'tb_sinal_equipamento_contrato', $dados, true);
    registraLog('Inserção de novo sinal equipamento pessoa.','i','tb_sinal_equipamento_contrato',$insertID,"id_contrato_plano_pessoa: $id_contrato_plano_pessoa");

    foreach($dados_sinal as $conteudo){

        $nome = $conteudo['nome'];
        $intensidade_sinal = $conteudo['intensidade_sinal'];
        $observacao = $conteudo['observacao'];

        if($conteudo['nome'] && $conteudo['intensidade_sinal']){
            $dadosSinal = array(
                'nome' => $nome,
                'intensidade_sinal' => $intensidade_sinal,
                'observacao' => $observacao,
                'id_sinal_equipamento_contrato' => $insertID
            );
            
            $insertSinal = DBCreate('', 'tb_sinal_equipamento', $dadosSinal);
            registraLog('Inserção de novo sinal equipamento.','i','tb_sinal_equipamento',$insertSinal,"nome: $nome | intensidade_sinal: $intensidade_sinal | id_sinal_equipamento_contrato: $insertID | observacao: $observacao");

            // QUADRO INFORMATIVO HISTORICO
            inserirHistorico($id_contrato_plano_pessoa, 1, "Inserção de sinal equipamento", "nome: $nome | intensidade sinal: $intensidade_sinal | observacao: $observacao", 13);
        }
    }
    
    $alert = ('Item inserido com sucesso!');
    $alert_type = 's';    if($ativacao == 1){
        if($pular){
            header("location: /api/iframe?token=$request->token&view=velocidade-minima-encaminhar-form&alterar=$pular&ativacao=1&dado_posterior=$insertID&id_contrato=$id_contrato_plano_pessoa");
        }else if($voltar && $salvar != 1){
            header("location: /api/iframe?token=$request->token&view=acesso-equipamento-form&alterar=$voltar&ativacao=1&dado_posterior=$insertID&id_contrato=$id_contrato_plano_pessoa");
        }else{
            header("location: /api/iframe?token=$request->token&view=velocidade-minima-encaminhar-form&ativacao=1&dado_posterior=$insertID&id_contrato=$id_contrato_plano_pessoa");
        }
    }else{
        header("location: /api/iframe?token=$request->token&view=sinal-equipamento-busca");
    }
    exit;
}

function alterar($id, $id_contrato_plano_pessoa, $dados_sinal, $ativacao, $pular, $voltar, $salvar){

    $dados = array(
        'id_contrato_plano_pessoa' => $id_contrato_plano_pessoa
    );
    DBUpdate('', 'tb_sinal_equipamento_contrato', $dados, "id_sinal_equipamento_contrato = $id");

    DBDelete('', 'tb_sinal_equipamento', "id_sinal_equipamento_contrato = '$id'");
    foreach($dados_sinal as $conteudo){
        if($conteudo['nome'] && $conteudo['intensidade_sinal']){

            $nome = $conteudo['nome'];
            $intensidade_sinal = $conteudo['intensidade_sinal'];
            $observacao = $conteudo['observacao'];

            $dadosSinal = array(
                'nome' => $nome,
                'intensidade_sinal' => $intensidade_sinal,
                'observacao' => $observacao,
                'id_sinal_equipamento_contrato' => $id
            );
            $insertID = DBCreate('', 'tb_sinal_equipamento', $dadosSinal);
            registraLog('Alteração de sinal equipamento.','a','tb_sinal_equipamento',$insertID,"nome: $nome | intensidade_sinal: $intensidade_sinal | id_sinal_equipamento_contrato: $id | observacao: $observacao");

            // QUADRO INFORMATIVO HISTORICO
            inserirHistorico($id_contrato_plano_pessoa, 2, "Alteração de sinal equipamento", "nome: $nome | intensidade sinal: $intensidade_sinal | observacao: $observacao", 13);
        }
    }

    registraLog('Alteração de sinal equipamento pessoa.','a','tb_sinal_equipamento_contrato',$id,"id_contrato_plano_pessoa: $id_contrato_plano_pessoa");
    $alert = ('Item alterado com sucesso!');
    $alert_type = 's';    if($ativacao == 1){
        if($pular){
            header("location: /api/iframe?token=$request->token&view=velocidade-minima-encaminhar-form&alterar=$pular&ativacao=1&dado_posterior=$insertID&id_contrato=$id_contrato_plano_pessoa");
        }else if($voltar && $salvar != 1){
            header("location: /api/iframe?token=$request->token&view=acesso-equipamento-form&alterar=$voltar&ativacao=1&dado_posterior=$insertID&id_contrato=$id_contrato_plano_pessoa");
        }else{
            header("location: /api/iframe?token=$request->token&view=velocidade-minima-encaminhar-form&ativacao=1&dado_posterior=$insertID&id_contrato=$id_contrato_plano_pessoa");
        }
    }else{
        header("location: /api/iframe?token=$request->token&view=sinal-equipamento-busca");
    }
    exit;
}

function excluir($id, $exclui_ativacao, $id_contrato){

    if($exclui_ativacao == 1){

        $dados = DBRead('', 'tb_velocidade_minima_encaminhar_contrato', "WHERE id_contrato_plano_pessoa = $id_contrato");

        $query = "DELETE FROM tb_sinal_equipamento_contrato WHERE id_sinal_equipamento_contrato = $id";
        $link = DBConnect('');
        $result = @mysqli_query($link, $query);
        DBClose($link);
        registraLog('Exclusão de sinal equipamento.','e','tb_sinal_equipamento_contrato',$id,'');

        // QUADRO INFORMATIVO HISTORICO
        inserirHistorico($id_contrato, 3, "Exclusão de sinal equipamento", "Excluiu dados", 13);

        if(!$result){
            $alert = ('Erro ao excluir item!');
            $alert_type = 'd';        }else{
            $alert = ('Item excluído com sucesso!');
    $alert_type = 's';        }
        
        if($dados){
            $dado_posterior = $dados[0]['id_velocidade_minima_encaminhar_contrato'];
            header("location: /api/iframe?token=$request->token&view=velocidade-minima-encaminhar-form&alterar=$dado_posterior&ativacao=1&id_contrato=$id_contrato");
        }else{
            header("location: /api/iframe?token=$request->token&view=velocidade-minima-encaminhar-form&ativacao=1&id_contrato=$id_contrato");
        }

    }else{
        $id_contrato = DBRead('', 'tb_sinal_equipamento_contrato', "WHERE id_sinal_equipamento_contrato = ".$id."");
        $id_contrato = $id_contrato[0]['id_contrato_plano_pessoa'];
        
        $query = "DELETE FROM tb_sinal_equipamento_contrato WHERE id_sinal_equipamento_contrato = $id";
        $link = DBConnect('');
        $result = @mysqli_query($link, $query);
        DBClose($link);
        registraLog('Exclusão de sinal equipamento.','e','tb_sinal_equipamento_contrato',$id,'');

        // QUADRO INFORMATIVO HISTORICO
        inserirHistorico($id_contrato, 3, "Exclusão de sinal equipamento", "Excluiu dados", 13);

        if(!$result){
            $alert = ('Erro ao excluir item!');
            $alert_type = 'd';        }else{
            $alert = ('Item excluído com sucesso!');
    $alert_type = 's';        }
        header("location: /api/iframe?token=$request->token&view=sinal-equipamento-busca");
    }
    
    exit;
}

?>