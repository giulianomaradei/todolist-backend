<?php
require_once(__DIR__."/System.php");
require_once(__DIR__."/QuadroInformativoHistorico.php");


$id_contrato_plano_pessoa  = (!empty($_POST['id_contrato_plano_pessoa'])) ? $_POST['id_contrato_plano_pessoa'] : '';
$manual = (!empty($_POST['manual'])) ? $_POST['manual'] : '';

$ativacao = (!empty($_POST['ativacao'])) ? $_POST['ativacao'] : 0;
$pular = (!empty($_POST['pular'])) ? $_POST['pular'] : '';
$voltar = (!empty($_POST['voltar'])) ? $_POST['voltar'] : '';
$salvar = (!empty($_POST['salvar'])) ? $_POST['salvar'] : 0;
$exclui_ativacao = (!empty($_GET['exclui-ativacao'])) ? $_GET['exclui-ativacao'] : 0;
$id_contrato = (!empty($_GET['id-contrato'])) ? $_GET['id-contrato'] : '';
$modal_salvar = (!empty($_POST['modal_salvar'])) ? $_POST['modal_salvar'] : '';

if(!empty($_POST['inserir'])){

    $verificacao_contrato = DBRead('', 'tb_manual_contrato', "WHERE id_contrato_plano_pessoa = '".$id_contrato_plano_pessoa."'");

    if(!$verificacao_contrato){
        inserir($manual, $id_contrato_plano_pessoa, $ativacao, $pular, $voltar, $salvar, $modal_salvar);
    }else if($manual == ""){
        $alert = ('Manual sem dados!','w');
        header("location: /api/iframe?token=$request->token&view=manual-form");
        exit;
    }else{
        $alert = ('Item já existe na base de dados!');
        $alert_type = 'w';        header("location: /api/iframe?token=$request->token&view=manual-form");
        exit;
    }
    $alert = ('Item já existe na base de dados!','w');
    header("location: /api/iframe?token=$request->token&view=manual-form");
    exit;

}else if(!empty($_POST['alterar'])){
    $id = (int)$_POST['alterar'];

    $verificacao_contrato = DBRead('', 'tb_manual_contrato', "WHERE id_contrato_plano_pessoa = '".$id_contrato_plano_pessoa."' AND id_manual_contrato != ".$id);

    if(!$verificacao_contrato){
        alterar($id, $manual, $id_contrato_plano_pessoa, $ativacao, $pular, $voltar, $salvar, $modal_salvar);
    }else if($manual == ""){
        $alert = ('Manual sem dados!','w');
        header("location: /api/iframe?token=$request->token&view=manual-form");
        exit;
    }else{
        $alert = ('Item já existe na base de dados!');
        $alert_type = 'w';        header("location: /api/iframe?token=$request->token&view=manual-form&alterar=$id");
        exit;
    }

    $alert = ('Item já existe na base de dados!','w');
    header("location: /api/iframe?token=$request->token&view=manual-form&alterar=$id");
    exit;
    
} else if(isset($_GET['excluir'])){
    $id = (int)$_GET['excluir'];
    excluir($id, $exclui_ativacao, $id_contrato);
}else{
    header("location: ../adm.php");
    exit;
}

function inserir($manual, $id_contrato_plano_pessoa, $ativacao, $pular, $voltar, $salvar, $modal_salvar){

    $dados = array(
        'manual' => $manual,
        'id_contrato_plano_pessoa' => $id_contrato_plano_pessoa
    );
    $insertID = DBCreate('', 'tb_manual_contrato', $dados, true);
    registraLog('Inserção de novo manual.','i','tb_manual_contrato',$insertID,"manual: $manual | id_contrato_plano_pessoa: $id_contrato_plano_pessoa");
    $alert = ('Item inserido com sucesso!');
    $alert_type = 's';
    // QUADRO INFORMATIVO HISTORICO
    inserirHistorico($id_contrato_plano_pessoa, 1, "Inserção de novo manual", "manual: $manual", 8, 2);

    if($ativacao == 1){
        if($pular){
            header("location: /api/iframe?token=$request->token&view=plano-cliente-form&alterar=$pular&ativacao=1&dado_posterior=$insertID&id_contrato=$id_contrato_plano_pessoa");
        }else if($voltar && $salvar != 1){
            header("location: /api/iframe?token=$request->token&view=ura-form&alterar=$voltar&ativacao=1&dado_posterior=$insertID&id_contrato=$id_contrato_plano_pessoa");
        }else{
            header("location: /api/iframe?token=$request->token&view=plano-cliente-form&ativacao=1&dado_posterior=$insertID&id_contrato=$id_contrato_plano_pessoa");
        }
    }else if($modal_salvar != -1){
        header("location: /api/iframe?token=$request->token&view=manual-form&alterar=$insertID");
    }else{
        header("location: /api/iframe?token=$request->token&view=manual-busca");
    }
    exit;
}

function alterar($id, $manual, $id_contrato_plano_pessoa, $ativacao, $pular, $voltar, $salvar, $modal_salvar){

    $dados = array(
        'manual' => $manual,
        'id_contrato_plano_pessoa' => $id_contrato_plano_pessoa
    );
    DBUpdate('', 'tb_manual_contrato', $dados, "id_manual_contrato = $id");
    registraLog('Alteração de manual.','a','tb_manual_contrato',$id,"manual: $manual | id_contrato_plano_pessoa: $id_contrato_plano_pessoa");

    // QUADRO INFORMATIVO HISTORICO
    inserirHistorico($id_contrato_plano_pessoa, 2, "Alteração de manual", "manual: $manual", 8, 2);

    $alert = ('Item alterado com sucesso!');
    $alert_type = 's';    if($ativacao == 1){
        if($pular){
            header("location: /api/iframe?token=$request->token&view=plano-cliente-form&alterar=$pular&ativacao=1&dado_posterior=$id&id_contrato=$id_contrato_plano_pessoa");
        }else if($voltar && $salvar != 1){
            header("location: /api/iframe?token=$request->token&view=ura-form&alterar=$voltar&ativacao=1&dado_posterior=$id&id_contrato=$id_contrato_plano_pessoa");
        }else{
            header("location: /api/iframe?token=$request->token&view=plano-cliente-form&ativacao=1&dado_posterior=$id&id_contrato=$id_contrato_plano_pessoa&tela=1");
        }
    }else if($modal_salvar != -1){
        header("location: /api/iframe?token=$request->token&view=manual-form&alterar=$id");
    }else{
        header("location: /api/iframe?token=$request->token&view=manual-busca");
    }
    exit;
}

function excluir($id, $exclui_ativacao, $id_contrato){

    if($exclui_ativacao == 1){

        $dados = DBRead('', 'tb_plano_cliente_contrato', "WHERE id_contrato_plano_pessoa = $id_contrato");

        $query = "DELETE FROM tb_manual_contrato WHERE id_manual_contrato = $id";
        $link = DBConnect('');
        $result = @mysqli_query($link, $query);
        DBClose($link);
        registraLog('Exclusão de manual.','e','tb_manual_contrato',$id,'');

        // QUADRO INFORMATIVO HISTORICO
        inserirHistorico($id_contrato, 3, "Exclusão de manual", "Excluiu dados", 8, 2);

        if(!$result){
            $alert = ('Erro ao excluir item!');
            $alert_type = 'd';        }else{
            $alert = ('Item excluído com sucesso!');
    $alert_type = 's';        }

        if($dados){

            $dado_posterior = $dados[0]['id_plano_cliente_contrato'];
            header("location: /api/iframe?token=$request->token&view=plano-cliente-form&alterar=$dado_posterior&ativacao=1&id_contrato=$id_contrato");
        }else{

            header("location: /api/iframe?token=$request->token&view=plano-cliente-form&ativacao=1&id_contrato=$id_contrato");
        }

    }else{
        $id_contrato = DBRead('', 'tb_manual_contrato', "WHERE id_manual_contrato = ".$id."");
        $id_contrato = $id_contrato[0]['id_contrato_plano_pessoa'];

        $query = "DELETE FROM tb_manual_contrato WHERE id_manual_contrato = $id";
        $link = DBConnect('');
        $result = @mysqli_query($link, $query);
        DBClose($link);
        registraLog('Exclusão de manual.','e','tb_manual_contrato',$id,'');

        // QUADRO INFORMATIVO HISTORICO
        inserirHistorico($id_contrato, 3, "Exclusão de manual", "Excluiu dados", 8, 2);


        if(!$result){
            $alert = ('Erro ao excluir item!');
            $alert_type = 'd';        }else{
            $alert = ('Item excluído com sucesso!');
    $alert_type = 's';        }
        header("location: /api/iframe?token=$request->token&view=manual-busca");
    }

    exit;
}

?>