<?php
require_once(__DIR__."/System.php");
require_once(__DIR__."QuadroInformativoHistorico.php");

$observacao = (!empty($_POST['observacao'])) ? $_POST['observacao'] : '';
$id_contrato_plano_pessoa  = (!empty($_POST['id_contrato_plano_pessoa'])) ? $_POST['id_contrato_plano_pessoa'] : '';
$nome = (!empty($_POST['nome'])) ? $_POST['nome'] : '';
$configuracao = (!empty($_POST['configuracao'])) ? $_POST['configuracao'] : '';

$ativacao = (!empty($_POST['ativacao'])) ? $_POST['ativacao'] : 0;
$pular = (!empty($_POST['pular'])) ? $_POST['pular'] : '';
$voltar = (!empty($_POST['voltar'])) ? $_POST['voltar'] : '';
$salvar = (!empty($_POST['salvar'])) ? $_POST['salvar'] : 0;
$exclui_ativacao = (!empty($_GET['exclui-ativacao'])) ? $_GET['exclui-ativacao'] : 0;
$id_contrato = (!empty($_GET['id-contrato'])) ? $_GET['id-contrato'] : '';

if (!empty($_POST['inserir'])) {

    $cont = 0;
    $dados_configuracao = array();
    foreach($nome as $conteudo){
        $dados_configuracao[$cont]['nome'] = $conteudo;
        $cont++;
    }

    $cont = 0;
    foreach($configuracao as $conteudo){
        $dados_configuracao[$cont]['configuracao'] = $conteudo;
        $cont++;
    }

    $verificacao_contrato = DBRead('', 'tb_configuracao_roteadores_contrato', "WHERE id_contrato_plano_pessoa = '".$id_contrato_plano_pessoa."'");

    if(!$verificacao_contrato){
        inserir($observacao, $id_contrato_plano_pessoa, $dados_configuracao, $ativacao, $pular, $voltar, $salvar);
    }else{
        $alert = ('Item já existe na base de dados!');
        $alert_type = 'w';        header("location: /api/iframe?token=$request->token&view=configuracao-roteadores-form");
        exit;
    }

} else if (!empty($_POST['alterar'])) {
    $id = (int)$_POST['alterar'];

    $cont = 0;
    $dados_configuracao = array();
    foreach($nome as $conteudo){
        $dados_configuracao[$cont]['nome'] = $conteudo;
        $cont++;
    }
    $cont = 0;
    foreach($configuracao as $conteudo){
        $dados_configuracao[$cont]['configuracao'] = $conteudo;
        $cont++;
    }

    $verificacao_contrato = DBRead('', 'tb_configuracao_roteadores_contrato', "WHERE id_contrato_plano_pessoa = '".$id_contrato_plano_pessoa."' AND id_configuracao_roteadores_contrato != ".$id);

    if(!$verificacao_contrato){
        alterar($id, $observacao, $id_contrato_plano_pessoa, $dados_configuracao, $ativacao, $pular, $voltar, $salvar);
    }else{
        $alert = ('Item já existe na base de dados!');
        $alert_type = 'w';        header("location: /api/iframe?token=$request->token&view=configuracao-roteadores-form&alterar=$id");
        exit;
    }
    $alert = ('Item já existe na base de dados!','w');
    header("location: /api/iframe?token=$request->token&view=configuracao-roteadores-form&alterar=$id");
    exit;
    
} else if (isset($_GET['excluir'])) {

    $id = (int)$_GET['excluir'];
    excluir($id, $exclui_ativacao, $id_contrato);
} else {
    header("location: ../adm.php");
    exit;
}

function inserir($observacao, $id_contrato_plano_pessoa, $dados_configuracao, $ativacao, $pular, $voltar, $salvar){

    $dados = array(
        'observacao' => $observacao,
        'id_contrato_plano_pessoa' => $id_contrato_plano_pessoa
    );

    $insertID = DBCreate('', 'tb_configuracao_roteadores_contrato', $dados, true);
    registraLog('Inserção de nova configuração de roteadores contrato.','i','tb_configuracao_roteadores_contrato',$insertID,"observacao: $observacao | id_contrato_plano_pessoa: $id_contrato_plano_pessoa");

    // QUADRO INFORMATIVO HISTORICO
    inserirHistorico($id_contrato_plano_pessoa, 1, "Inserção de nova configuração de roteadores contrato", "observação: $observacao", 2);

    foreach($dados_configuracao as $conteudo){

        $nome = $conteudo['nome'];
        $configuracao = $conteudo['configuracao'];

        if($conteudo['nome'] && $conteudo['configuracao']){
            $dadosConfiguracao = array(
                'nome' => $nome,
                'configuracao' => $configuracao,
                'id_configuracao_roteadores_contrato' => $insertID
            );
            
            $insertConfiguracao = DBCreate('', 'tb_configuracao_roteadores', $dadosConfiguracao);
            registraLog('Inserção de nova configuração de roteador.','i','tb_configuracao_roteadores',$insertConfiguracao,"nome: $nome | configuracao: $configuracao | id_configuracao_roteadores_contrato: $insertID");

            // QUADRO INFORMATIVO HISTORICO
            inserirHistorico($id_contrato_plano_pessoa, 1, "Inserção de nova configuração de roteador", "nome: $nome | configuracao: $configuracao", 2);
        }
    }
    
    $alert = ('Item inserido com sucesso!');
    $alert_type = 's';
    if($ativacao == 1){
        if($pular){
            header("location: /api/iframe?token=$request->token&view=equipamento-form&alterar=$pular&ativacao=1&dado_posterior=$insertID&id_contrato=$id_contrato_plano_pessoa");
        }else if($voltar && $salvar != 1){
            header("location: /api/iframe?token=$request->token&view=prazo-retorno-form&alterar=$voltar&ativacao=1&dado_posterior=$insertID&id_contrato=$id_contrato_plano_pessoa");
        }else{
            header("location: /api/iframe?token=$request->token&view=equipamento-form&ativacao=1&dado_posterior=$insertID&id_contrato=$id_contrato_plano_pessoa");
        }
    }else{
        header("location: /api/iframe?token=$request->token&view=configuracao-roteadores-busca");
    }
    exit;
}

function alterar($id, $observacao, $id_contrato_plano_pessoa, $dados_configuracao, $ativacao, $pular, $voltar, $salvar){

    $dados = array(
        'observacao' => $observacao,
        'id_contrato_plano_pessoa' => $id_contrato_plano_pessoa
    );

    DBUpdate('', 'tb_configuracao_roteadores_contrato', $dados, "id_configuracao_roteadores_contrato = $id");
    registraLog('Alteração de configuração de roteador contrato.','a','tb_configuracao_roteadores',$id,"observacao: $observacao | id_contrato_plano_pessoa: $id_contrato_plano_pessoa");

    // QUADRO INFORMATIVO HISTORICO
    inserirHistorico($id_contrato_plano_pessoa, 2, "Alteração de configuração de roteadores contrato", "observação: $observacao", 2);

    echo '<hr>';
    DBDelete('', 'tb_configuracao_roteadores', "id_configuracao_roteadores_contrato = '$id'");
    foreach($dados_configuracao as $conteudo){
        if($conteudo['nome'] && $conteudo['configuracao']){

            $nome = $conteudo['nome'];
            $configuracao = $conteudo['configuracao'];

            $dadosConfiguracao = array(
                'nome' => $nome,
                'configuracao' => $configuracao,
                'id_configuracao_roteadores_contrato' => $id
            );
            $insertID = DBCreate('', 'tb_configuracao_roteadores', $dadosConfiguracao);
            registraLog('Alteração de configuração de roteador.','a','tb_configuracao_roteadores',$insertID,"nome: $nome | configuracao: $configuracao | id_configuracao_roteadores_contrato: $id");

            // QUADRO INFORMATIVO HISTORICO
            inserirHistorico($id_contrato_plano_pessoa, 2, "Alteração de configuração de roteador", "nome: $nome | configuracao: $configuracao", 2);
        }
    }
    
    $alert = ('Item alterado com sucesso!');
    $alert_type = 's';
    if($ativacao == 1){
        if($pular){
            header("location: /api/iframe?token=$request->token&view=equipamento-form&alterar=$pular&ativacao=1&dado_posterior=$insertID&id_contrato=$id_contrato_plano_pessoa");
        }else if($voltar && $salvar != 1){
            header("location: /api/iframe?token=$request->token&view=prazo-retorno-form&alterar=$voltar&ativacao=1&dado_posterior=$insertID&id_contrato=$id_contrato_plano_pessoa");
        }else{
            header("location: /api/iframe?token=$request->token&view=equipamento-form&ativacao=1&dado_posterior=$insertID&id_contrato=$id_contrato_plano_pessoa");
        }
    }else{
        header("location: /api/iframe?token=$request->token&view=configuracao-roteadores-busca");
    }

    exit;
}

function excluir($id, $exclui_ativacao, $id_contrato){

    if($exclui_ativacao == 1){

        $dados = DBRead('', 'tb_catalogo_equipamento_qi', "WHERE id_contrato_plano_pessoa = $id_contrato");

        $query = "DELETE FROM tb_configuracao_roteadores_contrato WHERE id_configuracao_roteadores_contrato = $id";
        $link = DBConnect('');
        $result = @mysqli_query($link, $query);
        DBClose($link);

        registraLog('Exclusão de configuração de roteador.', 'e', 'tb_configuracao_roteadores_contrato', $id, '');

        // QUADRO INFORMATIVO HISTORICO
        inserirHistorico($id_contrato, 3, "Exclusão de configuração de roteador", "Excluiu dados", 2);

        if(!$result){
            $alert = ('Erro ao excluir item!', 'd');
        }else{
            $alert = ('Item excluído com sucesso!', 's');
        }

        if($dados){
            $dado_posterior = $dados[0]['id_catalogo_equipamento_qi'];
            header("location: /api/iframe?token=$request->token&view=equipamento-form&alterar=$dado_posterior&ativacao=1&id_contrato=$id_contrato");
        }else{
            header("location: /api/iframe?token=$request->token&view=equipamento-form&ativacao=1&id_contrato=$id_contrato");
        }
        
    }else{
        $id_contrato = DBRead('', 'tb_configuracao_roteadores_contrato', "WHERE id_configuracao_roteadores_contrato = ".$id."");
        $id_contrato = $id_contrato[0]['id_contrato_plano_pessoa'];
      
        $query = "DELETE FROM tb_configuracao_roteadores_contrato WHERE id_configuracao_roteadores_contrato = $id";
        $link = DBConnect('');
        $result = @mysqli_query($link, $query);
        DBClose($link);

        registraLog('Exclusão de configuração de roteador.', 'e', 'tb_configuracao_roteadores_contrato', $id, '');

        // QUADRO INFORMATIVO HISTORICO
        inserirHistorico($id_contrato, 3, "Exclusão de configuração de roteador", "Excluiu dados", 2);

        if(!$result){
            $alert = ('Erro ao excluir item!', 'd');
        }else{
            $alert = ('Item excluído com sucesso!', 's');
        }
        header("location: /api/iframe?token=$request->token&view=configuracao-roteadores-busca");
    }

    exit;
}

?>