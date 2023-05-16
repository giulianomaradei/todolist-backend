<?php
require_once(__DIR__."/System.php");
require_once(__DIR__."/QuadroInformativoHistorico.php");


$id_contrato_plano_pessoa  = (!empty($_POST['id_contrato_plano_pessoa'])) ? $_POST['id_contrato_plano_pessoa'] : '';
$numero = (!empty($_POST['numero'])) ? $_POST['numero'] : '';
$descricao = (!empty($_POST['descricao'])) ? $_POST['descricao'] : '';

$ativacao = (!empty($_POST['ativacao'])) ? $_POST['ativacao'] : 0;
$pular = (!empty($_POST['pular'])) ? $_POST['pular'] : '';
$voltar = (!empty($_POST['voltar'])) ? $_POST['voltar'] : '';
$salvar = (!empty($_POST['salvar'])) ? $_POST['salvar'] : 0;
$exclui_ativacao = (!empty($_GET['exclui-ativacao'])) ? $_GET['exclui-ativacao'] : 0;
$id_contrato = (!empty($_GET['id-contrato'])) ? $_GET['id-contrato'] : '';

if(!empty($_POST['inserir'])){

    $cont = 0;
    $dados_ura = array();
    foreach($numero as $conteudo) {
        $dados_ura[$cont]['numero'] = $conteudo;
        $cont++;
    }

    $cont = 0;
    foreach($descricao as $conteudo) {
        $dados_ura[$cont]['descricao'] = $conteudo;
        $cont++;
    }

    $verificacao_contrato = DBRead('', 'tb_ura_contrato', "WHERE id_contrato_plano_pessoa = '".$id_contrato_plano_pessoa."'");

    if(!$verificacao_contrato){
        inserir($id_contrato_plano_pessoa, $dados_ura, $ativacao, $pular, $voltar, $salvar);
    }else{
        $alert = ('Item já existe na base de dados!');
        $alert_type = 'w';        header("location: /api/iframe?token=$request->token&view=ura-form");
        exit;
    }

}else if(!empty($_POST['alterar'])){
    $id = (int)$_POST['alterar'];

    $cont = 0;
    $dados_ura = array();
    foreach($numero as $conteudo){
        $dados_ura[$cont]['numero'] = $conteudo;
        $cont++;
    }
    $cont = 0;
    foreach($descricao as $conteudo){
        $dados_ura[$cont]['descricao'] = $conteudo;
        $cont++;
    }

    $verificacao_contrato = DBRead('', 'tb_ura_contrato', "WHERE id_contrato_plano_pessoa = '".$id_contrato_plano_pessoa."' AND id_ura_contrato != ".$id);

    if(!$verificacao_contrato){
        alterar($id, $id_contrato_plano_pessoa, $dados_ura, $ativacao, $pular, $voltar, $salvar);
    }else{
        $alert = ('Item já existe na base de dados!');
        $alert_type = 'w';        header("location: /api/iframe?token=$request->token&view=ura-form&alterar=$id");
        exit;
    }
    
} else if(isset($_GET['excluir'])){

    $id = (int)$_GET['excluir'];
    excluir($id, $exclui_ativacao, $id_contrato);
}else{
    header("location: ../adm.php");
    exit;
}

function inserir($id_contrato_plano_pessoa, $dados_ura, $ativacao, $pular, $voltar, $salvar){

    $dados = array(
        'id_contrato_plano_pessoa' => $id_contrato_plano_pessoa
    );

    $insertID = DBCreate('', 'tb_ura_contrato', $dados, true);
    registraLog('Inserção de nova ura.','i','tb_ura_contrato',$insertID,"id_contrato_plano_pessoa: $id_contrato_plano_pessoa");

    foreach($dados_ura as $conteudo){

        $numero = $conteudo['numero'];
        $descricao = $conteudo['descricao'];

        if($conteudo['numero'] && $conteudo['descricao']){
            $dadosUra = array(
                'numero' => $numero,
                'descricao' => $descricao,
                'id_ura_contrato' => $insertID
            );
            
            $insertUra = DBCreate('', 'tb_ura', $dadosUra);
            registraLog('Inserção de novo número.','i','tb_ura',$insertUra,"numero: $numero | descricao: $descricao | id_ura_contrato: $insertID");
            // QUADRO INFORMATIVO HISTORICO
            inserirHistorico($id_contrato_plano_pessoa, 1, "Inserção de novo número", "numero: $numero | descricao: $descricao", 17);
        }
    }
    
    $alert = ('Item inserido com sucesso!');
    $alert_type = 's';
    if($ativacao == 1){
        if($pular){
            header("location: /api/iframe?token=$request->token&view=manual-form&alterar=$pular&ativacao=1&dado_posterior=$insertID&id_contrato=$id_contrato_plano_pessoa");
        }else if($voltar && $salvar != 1){
            header("location: /api/iframe?token=$request->token&view=parametro-form&alterar=$voltar&ativacao=1&dado_posterior=$insertID&id_contrato=$id_contrato_plano_pessoa");
        }else{
            header("location: /api/iframe?token=$request->token&view=manual-form&ativacao=1&dado_posterior=$insertID&id_contrato=$id_contrato_plano_pessoa");
        }
    }else{
        header("location: /api/iframe?token=$request->token&view=ura-busca");
    }
    exit;
}

function alterar($id, $id_contrato_plano_pessoa, $dados_ura, $ativacao, $pular, $voltar, $salvar){

    $dados = array(
        'id_contrato_plano_pessoa' => $id_contrato_plano_pessoa
    );
    DBUpdate('', 'tb_ura_contrato', $dados, "id_ura_contrato = $id");

    DBDelete('', 'tb_ura', "id_ura_contrato = '$id'");
    foreach($dados_ura as $conteudo){

        $numero = $conteudo['numero'];
        $descricao = $conteudo['descricao'];

        if($conteudo['numero'] && $conteudo['descricao']){
            $dadosUra = array(
                'numero' => $numero,
                'descricao' => $descricao,
                'id_ura_contrato' => $id
            );
            $insertID = DBCreate('', 'tb_ura', $dadosUra);
            registraLog('Alteração de número.', 'i','tb_ura', $insertID, "numero: $numero | descricao: $descricao | id_ura_contrato: $id");

            // QUADRO INFORMATIVO HISTORICO
            inserirHistorico($id_contrato_plano_pessoa, 2, "Alteração de URA", "descricao: $descricao | descricao: $descricao", 17);
        }
    }

    registraLog('Alteração de ura.','a','tb_ura_contrato',$id,"id_contrato_plano_pessoa: $id_contrato_plano_pessoa");
    $alert = ('Item alterado com sucesso!');
    $alert_type = 's';
    if($ativacao == 1){
        if($pular){
            header("location: /api/iframe?token=$request->token&view=manual-form&alterar=$pular&ativacao=1&dado_posterior=$insertID&id_contrato=$id_contrato_plano_pessoa");
        }else if($voltar && $salvar != 1){
            header("location: /api/iframe?token=$request->token&view=parametro-form&alterar=$voltar&ativacao=1&dado_posterior=$insertID&id_contrato=$id_contrato_plano_pessoa");
        }else{
            header("location: /api/iframe?token=$request->token&view=manual-form&ativacao=1&dado_posterior=$insertID&id_contrato=$id_contrato_plano_pessoa");
        }
    }else{
        header("location: /api/iframe?token=$request->token&view=ura-busca");
    }
    exit;
}

function excluir($id, $exclui_ativacao, $id_contrato){

    if($exclui_ativacao == 1){

        $dados = DBRead('', 'tb_manual_contrato', "WHERE id_contrato_plano_pessoa = $id_contrato");

        $query = "DELETE FROM tb_ura_contrato WHERE id_ura_contrato = $id";
        $link = DBConnect('');
        $result = @mysqli_query($link, $query);
        DBClose($link);
        registraLog('Exclusão de ura.','e','tb_ura_contrato',$id,'');

        // QUADRO INFORMATIVO HISTORICO
        inserirHistorico($id_contrato, 3, "Exclusão de URA", "Excluiu dados", 17);
       
        if(!$result){
            $alert = ('Erro ao excluir item!');
            $alert_type = 'd';        }else{
            $alert = ('Item excluído com sucesso!');
    $alert_type = 's';        }

        if($dados){

            $dado_posterior = $dados[0]['id_manual_contrato'];
            header("location: /api/iframe?token=$request->token&view=manual-form&alterar=$dado_posterior&ativacao=1&id_contrato=$id_contrato");
        }else{

            header("location: /api/iframe?token=$request->token&view=manual-form&ativacao=1&id_contrato=$id_contrato");
        }
    }else{
        $id_contrato = DBRead('', 'tb_ura_contrato', "WHERE id_ura_contrato = ".$id."");
        $id_contrato = $id_contrato[0]['id_contrato_plano_pessoa'];
        
        $query = "DELETE FROM tb_ura_contrato WHERE id_ura_contrato = $id";
        $link = DBConnect('');
        $result = @mysqli_query($link, $query);
        DBClose($link);
        registraLog('Exclusão de ura.','e','tb_ura_contrato',$id,'');

        // QUADRO INFORMATIVO HISTORICO
        
        inserirHistorico($id_contrato, 3, "Exclusão de URA", "Excluiu dados", 17);

        if(!$result){
            $alert = ('Erro ao excluir item!');
            $alert_type = 'd';        }else{
            $alert = ('Item excluído com sucesso!');
    $alert_type = 's';        }
        header("location: /api/iframe?token=$request->token&view=ura-busca");
    }
    
    exit;
}

?>