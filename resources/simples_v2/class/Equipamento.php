<?php
require_once(__DIR__."/System.php");
require_once(__DIR__."/QuadroInformativoHistorico.php");

$id_catalogo_equipamento = (!empty($_POST['id_catalogo_equipamento'])) ? $_POST['id_catalogo_equipamento'] : '';
$id_contrato_plano_pessoa  = (!empty($_POST['id_contrato_plano_pessoa'])) ? $_POST['id_contrato_plano_pessoa'] : '';

$ativacao = (!empty($_POST['ativacao'])) ? $_POST['ativacao'] : 0;
$pular = (!empty($_POST['pular'])) ? $_POST['pular'] : '';
$voltar = (!empty($_POST['voltar'])) ? $_POST['voltar'] : '';
$salvar = (!empty($_POST['salvar'])) ? $_POST['salvar'] : 0;
$exclui_ativacao = (!empty($_GET['exclui-ativacao'])) ? $_GET['exclui-ativacao'] : 0;
$id_contrato = (!empty($_GET['id-contrato'])) ? $_GET['id-contrato'] : '';

if (!empty($_POST['inserir'])) {
    $verificacao_contrato = DBRead('', 'tb_catalogo_equipamento_qi_contrato', "WHERE id_contrato_plano_pessoa = '".$id_contrato_plano_pessoa."'");

    if(!$verificacao_contrato){
        inserir($id_catalogo_equipamento, $id_contrato_plano_pessoa, $ativacao, $pular, $voltar, $salvar);
    }else{
        $alert = ('Item já existe na base de dados!');
        $alert_type = 'w';        header("location: /api/iframe?token=$request->token&view=equipamento-form");
        exit;
    }

} else if(!empty($_POST['alterar'])) {
    $id = (int)$_POST['alterar'];

    $verificacao_contrato = DBRead('', 'tb_catalogo_equipamento_qi_contrato', "WHERE id_contrato_plano_pessoa = '".$id_contrato_plano_pessoa."' AND id_catalogo_equipamento_qi_contrato != ".$id);
    if(!$verificacao_contrato){
        alterar($id, $id_catalogo_equipamento, $id_contrato_plano_pessoa, $ativacao, $pular, $voltar, $salvar);
    }else{
        $alert = ('Item já existe na base de dados!');
        $alert_type = 'w';        header("location: /api/iframe?token=$request->token&view=equipamento-form&alterar=$id");
        exit;
    }
    $alert = ('Item já existe na base de dados!','w');
    header("location: /api/iframe?token=$request->token&view=equipamento-form&alterar=$id");
    exit;
    
} else if(isset($_GET['excluir'])) {

    $id = (int)$_GET['excluir'];
    excluir($id, $exclui_ativacao, $id_contrato);
} else {
    header("location: ../adm.php");
    exit;
}

function inserir($id_catalogo_equipamento, $id_contrato_plano_pessoa, $ativacao, $pular, $voltar, $salvar){

    $dados = array(
        'id_contrato_plano_pessoa' => $id_contrato_plano_pessoa
    );

    $insertID = DBCreate('', 'tb_catalogo_equipamento_qi_contrato', $dados, true);
    registraLog('Inserção de novo equipamento no qi pessoa.','i','tb_catalogo_equipamento_qi_contrato',$insertID,"id_contrato_plano_pessoa: $id_contrato_plano_pessoa");
    
    foreach($id_catalogo_equipamento as $conteudo){

        $dados = array(
            'id_catalogo_equipamento' => $conteudo,
            'id_catalogo_equipamento_qi_contrato' => $insertID
        );

        $insertEquipamento = DBCreate('', 'tb_catalogo_equipamento_qi', $dados, true);
        registraLog('Inserção de novo equipamento no qi.','i','tb_catalogo_equipamento_qi',$insertEquipamento,"id_catalogo_equipamento: $conteudo | id_catalogo_equipamento_qi_contrato: $insertID");

        $modelo = DBRead('', 'tb_catalogo_equipamento', "WHERE id_catalogo_equipamento = $conteudo");

        // QUADRO INFORMATIVO HISTORICO
        inserirHistorico($id_contrato_plano_pessoa, 1, "Inserção de novo equipamento", "modelo: ".$modelo[0]['modelo']." ", 3);
    }

    $alert = ('Item inserido com sucesso!');
    $alert_type = 's';
    if($ativacao == 1){
        if($pular){
            header("location: /api/iframe?token=$request->token&view=reinicio-equipamento-form&alterar=$pular&ativacao=1&dado_posterior=$insertID&id_contrato=$id_contrato_plano_pessoa");
        }else if($voltar && $salvar != 1){
            header("location: /api/iframe?token=$request->token&view=configuracao-roteadores-form&alterar=$voltar&ativacao=1&dado_posterior=$insertID&id_contrato=$id_contrato_plano_pessoa");
        }else{
            header("location: /api/iframe?token=$request->token&view=reinicio-equipamento-form&ativacao=1&dado_posterior=$insertID&id_contrato=$id_contrato_plano_pessoa");
        }
    }else{
        header("location: /api/iframe?token=$request->token&view=equipamento-busca");
    }
    exit;
}

function alterar($id, $id_catalogo_equipamento, $id_contrato_plano_pessoa, $ativacao, $pular, $voltar, $salvar){

    $dados = array(
        'id_contrato_plano_pessoa' => $id_contrato_plano_pessoa
    );
    DBUpdate('', 'tb_catalogo_equipamento_qi_contrato', $dados, "id_catalogo_equipamento_qi_contrato = $id");

    DBDelete('', 'tb_catalogo_equipamento_qi', "id_catalogo_equipamento_qi_contrato = '$id'");
    
    foreach($id_catalogo_equipamento as $conteudo){
       
        $dados = array(
            'id_catalogo_equipamento' => $conteudo,
            'id_catalogo_equipamento_qi_contrato' => $id
        );

        $insertID = DBCreate('', 'tb_catalogo_equipamento_qi', $dados, true);
        registraLog('Alteração de equipamento no qi.','a','tb_catalogo_equipamento_qi',$insertID,"id_catalogo_equipamento: $conteudo | id_catalogo_equipamento_qi_contrato: $id");

        $modelo = DBRead('', 'tb_catalogo_equipamento', "WHERE id_catalogo_equipamento = $conteudo");

        // QUADRO INFORMATIVO HISTORICO
        inserirHistorico($id_contrato_plano_pessoa, 2, "Alteração de equipamento", "modelo: ".$modelo[0]['modelo']." ", 3);
       
    }
    registraLog('Alteração de equipamento no qi.','a','tb_catalogo_equipamento_qi',$id,"id_contrato_plano_pessoa: $id_contrato_plano_pessoa");
    $alert = ('Item alterado com sucesso!');
    $alert_type = 's';
    if($ativacao == 1){
        if($pular){
            header("location: /api/iframe?token=$request->token&view=reinicio-equipamento-form&alterar=$pular&ativacao=1&dado_posterior=$insertID&id_contrato=$id_contrato_plano_pessoa");
        }else if($voltar && $salvar != 1){
            header("location: /api/iframe?token=$request->token&view=configuracao-roteadores-form&alterar=$voltar&ativacao=1&dado_posterior=$insertID&id_contrato=$id_contrato_plano_pessoa");
        }else{
            header("location: /api/iframe?token=$request->token&view=reinicio-equipamento-form&ativacao=1&dado_posterior=$insertID&id_contrato=$id_contrato_plano_pessoa");
        }
    }else{
        header("location: /api/iframe?token=$request->token&view=equipamento-busca");
    }
    exit;
}

function excluir($id, $exclui_ativacao, $id_contrato){

    if($exclui_ativacao == 1){

        $dados = DBRead('', 'tb_reinicio_equipamento', "WHERE id_contrato_plano_pessoa = $id_contrato");

        $query = "DELETE FROM tb_catalogo_equipamento_qi_contrato WHERE id_catalogo_equipamento_qi_contrato = $id";
        $link = DBConnect('');
        $result = @mysqli_query($link, $query);
        DBClose($link);

        registraLog('Exclusão de equipamento.','e','tb_catalogo_equipamento_qi_contrato',$id,'');

        // QUADRO INFORMATIVO HISTORICO
        inserirHistorico($id_contrato, 3, "Exclusão de equipamento", "Excluiu dados", 3);

        if(!$result){
            $alert = ('Erro ao excluir item!', 'd');
        }else{
            $alert = ('Item excluído com sucesso!', 's');
        }

        if($dados){
            $dado_posterior = $dados[0]['id_reinicio_equipamento'];
            header("location: /api/iframe?token=$request->token&view=reinicio-equipamento-form&alterar=$dado_posterior&ativacao=1&id_contrato=$id_contrato");
        }else{
            header("location: /api/iframe?token=$request->token&view=reinicio-equipamento-form&ativacao=1&id_contrato=$id_contrato");
        }

    }else{
        $id_contrato = DBRead('', 'tb_velocidade_minima_encaminhar_contrato', "WHERE id_velocidade_minima_encaminhar_contrato = ".$id."");
        $id_contrato = $id_contrato[0]['id_contrato_plano_pessoa'];
        
        $query = "DELETE FROM tb_catalogo_equipamento_qi_contrato WHERE id_catalogo_equipamento_qi_contrato = $id";
        $link = DBConnect('');
        $result = @mysqli_query($link, $query);
        DBClose($link);

        registraLog('Exclusão de equipamento do qi.', 'e', 'tb_catalogo_equipamento_qi_contrato', $id, '');

        // QUADRO INFORMATIVO HISTORICO
        inserirHistorico($id_contrato, 3, "Exclusão de equipamento", "Excluiu dados", 3);

        if(!$result){
            $alert = ('Erro ao excluir item!', 'd');
        }else{
            $alert = ('Item excluído com sucesso!', 's');
        }
        header("location: /api/iframe?token=$request->token&view=equipamento-busca");
    }
    exit;
}

?>