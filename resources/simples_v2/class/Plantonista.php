<?php
require_once(__DIR__."/System.php");
require_once(__DIR__."/QuadroInformativoHistorico.php");


$tabela = (!empty($_POST['tabela'])) ? $_POST['tabela'] : '';
$id_contrato_plano_pessoa  = (!empty($_POST['id_contrato_plano_pessoa'])) ? $_POST['id_contrato_plano_pessoa'] : '';

$ativacao = (!empty($_POST['ativacao'])) ? $_POST['ativacao'] : 0;
$pular = (!empty($_POST['pular'])) ? $_POST['pular'] : '';
$voltar = (!empty($_POST['voltar'])) ? $_POST['voltar'] : '';
$salvar = (!empty($_POST['salvar'])) ? $_POST['salvar'] : 0;
$exclui_ativacao = (!empty($_GET['exclui-ativacao'])) ? $_GET['exclui-ativacao'] : 0;
$id_contrato = (!empty($_GET['id-contrato'])) ? $_GET['id-contrato'] : '';

if(!empty($_POST['inserir'])){

    $verificacao_contrato = DBRead('', 'tb_plantonista_contrato', "WHERE id_contrato_plano_pessoa = '".$id_contrato_plano_pessoa."'");

    if(!$verificacao_contrato){
        inserir($tabela, $id_contrato_plano_pessoa, $ativacao, $pular, $voltar, $salvar);
    }else if($tabela == ""){
        $alert = ('Tabela de plantonistas sem dados!','w');
        header("location: /api/iframe?token=$request->token&view=plantonista-form");
        exit;
    }else{
        $alert = ('Item já existe na base de dados!');
        $alert_type = 'w';        header("location: /api/iframe?token=$request->token&view=plantonista-form");
        exit;
    }

}else if(!empty($_POST['alterar'])){
    $id = (int)$_POST['alterar'];

    $verificacao_contrato = DBRead('', 'tb_plantonista_contrato', "WHERE id_contrato_plano_pessoa = '".$id_contrato_plano_pessoa."' AND id_plantonista_contrato != ".$id);

    if(!$verificacao_contrato){
        alterar($id, $tabela, $id_contrato_plano_pessoa, $ativacao, $pular, $voltar, $salvar);
    }else if($tabela == ""){
        $alert = ('Tabela de plantonistas sem dados!','w');
        header("location: /api/iframe?token=$request->token&view=plantonista-form");
        exit;
    }else{
        $alert = ('Item já existe na base de dados!');
        $alert_type = 'w';        header("location: /api/iframe?token=$request->token&view=plantonista-form&alterar=$id");
        exit;
    }

    $alert = ('Item já existe na base de dados!','w');
    header("location: /api/iframe?token=$request->token&view=plantonista-form&alterar=$id");
    exit;
    
} else if(isset($_GET['excluir'])){
    $id = (int)$_GET['excluir'];
    excluir($id, $exclui_ativacao, $id_contrato);
}else{
    header("location: ../adm.php");
    exit;
}

function inserir($tabela, $id_contrato_plano_pessoa, $ativacao, $pular, $voltar, $salvar){

    $dados = array(
        'tabela' => $tabela,
        'id_contrato_plano_pessoa' => $id_contrato_plano_pessoa
    );
    $insertID = DBCreate('', 'tb_plantonista_contrato', $dados, true);
    registraLog('Inserção de nova tabela de plantonistas.','i','tb_plantonista_contrato',$insertID,"tabela: $tabela | id_contrato_plano_pessoa: $id_contrato_plano_pessoa");

    // QUADRO INFORMATIVO HISTORICO
    inserirHistorico($id_contrato_plano_pessoa, 1, "Inserção de nova tabela de plantonistas", "tabela: $tabela", 11);

    $alert = ('Item inserido com sucesso!');
    $alert_type = 's';
    if($ativacao == 1){
        if($pular){
            header("location: /api/iframe?token=$request->token&view=horario-form&alterar=$pular&ativacao=1&dado_posterior=$insertID&id_contrato=$id_contrato_plano_pessoa&tela=1");
        }else if($voltar && $salvar != 1){
            header("location: /api/iframe?token=$request->token&view=localizacao-form&alterar=$voltar&ativacao=1&dado_posterior=$insertID&id_contrato=$id_contrato_plano_pessoa");
        }else{
            header("location: /api/iframe?token=$request->token&view=horario-form&ativacao=1&dado_posterior=$insertID&id_contrato=$id_contrato_plano_pessoa&tela=1");
        }
    }else{
        header("location: /api/iframe?token=$request->token&view=plantonista-busca");
    }
    
    exit;
}

function alterar($id, $tabela, $id_contrato_plano_pessoa, $ativacao, $pular, $voltar, $salvar){

    $dados = array(
        'tabela' => $tabela,
        'id_contrato_plano_pessoa' => $id_contrato_plano_pessoa
    );
    DBUpdate('', 'tb_plantonista_contrato', $dados, "id_plantonista_contrato = $id");
    registraLog('Alteração de tabela de plantonistas.','a','tb_plantonista_contrato',$id,"tabela: $tabela | id_contrato_plano_pessoa: $id_contrato_plano_pessoa");

    // QUADRO INFORMATIVO HISTORICO
    inserirHistorico($id_contrato_plano_pessoa, 2, "Alteração de tabela de plantonistas", "tabela: $tabela", 11);

    $alert = ('Item alterado com sucesso!');
    $alert_type = 's';
    if($ativacao == 1){
        if($pular){
            header("location: /api/iframe?token=$request->token&view=horario-form&alterar=$pular&ativacao=1&dado_posterior=$id&id_contrato=$id_contrato_plano_pessoa&tela=1");
        }else if($voltar && $salvar != 1){
            header("location: /api/iframe?token=$request->token&view=localizacao-form&alterar=$voltar&ativacao=1&dado_posterior=$id&id_contrato=$id_contrato_plano_pessoa");
        }else{
            header("location: /api/iframe?token=$request->token&view=horario-form&ativacao=1&dado_posterior=$id&id_contrato=$id_contrato_plano_pessoa&tela=1");
        }
    }else{
        header("location: /api/iframe?token=$request->token&view=plantonista-busca");
    }
    exit;
}

function excluir($id, $exclui_ativacao, $id_contrato){

    if($exclui_ativacao == 1){

        $dados = DBRead('', 'tb_horario_contrato', "WHERE id_contrato_plano_pessoa = '$id_contrato' AND tipo = 1");

        $query = "DELETE FROM tb_plantonista_contrato WHERE id_plantonista_contrato = $id";
        $link = DBConnect('');
        $result = @mysqli_query($link, $query);
        DBClose($link);
        registraLog('Exclusão de tabela de plantonistas.','e','tb_plantonista_contrato',$id,'');
        if(!$result){
            $alert = ('Erro ao excluir item!');
            $alert_type = 'd';        }else{
            $alert = ('Item excluído com sucesso!');
    $alert_type = 's';        }

        if($dados){
            $dado_posterior = $dados[0]['id_horario_contrato'];
            header("location: /api/iframe?token=$request->token&view=horario-form&alterar=$dado_posterior&ativacao=1&id_contrato=$id_contrato&tela=1");
        }else{
            header("location: /api/iframe?token=$request->token&view=horario-form&ativacao=1&id_contrato=$id_contrato&tela=1");
        }

    }else{
        $id_contrato = DBRead('', 'tb_plantonista_contrato', "WHERE id_plantonista_contrato = ".$id."");
        $id_contrato = $id_contrato[0]['id_contrato_plano_pessoa'];

        $query = "DELETE FROM tb_plantonista_contrato WHERE id_plantonista_contrato = $id";
        $link = DBConnect('');
        $result = @mysqli_query($link, $query);
        DBClose($link);
        registraLog('Exclusão de tabela de plantonistas.','e','tb_plantonista_contrato',$id,'');

        // QUADRO INFORMATIVO HISTORICO
        inserirHistorico($id_contrato, 3, "Exclusão de tabela de plantonistas", "Excluiu dados", 11);

        if(!$result){
            $alert = ('Erro ao excluir item!');
            $alert_type = 'd';        }else{
            $alert = ('Item excluído com sucesso!');
    $alert_type = 's';        }

        header("location: /api/iframe?token=$request->token&view=plantonista-busca");
    }

    // QUADRO INFORMATIVO HISTORICO
    inserirHistorico($id_contrato, 3, "Exclusão de tabela de plantonistas", "Excluiu dados", 11);

    exit;
}

?>