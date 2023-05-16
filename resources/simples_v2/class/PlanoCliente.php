<?php
require_once(__DIR__."/System.php");
require_once(__DIR__."/QuadroInformativoHistorico.php");


$id_contrato_plano_pessoa  = (!empty($_POST['id_contrato_plano_pessoa'])) ? $_POST['id_contrato_plano_pessoa'] : '';

$download = (!empty($_POST['download'])) ? $_POST['download'] : '';
$upload = (!empty($_POST['upload'])) ? $_POST['upload'] : '';
$observacao = (!empty($_POST['observacao'])) ? $_POST['observacao'] : '';
$descricao = (!empty($_POST['descricao'])) ? $_POST['descricao'] : '';

$ativacao = (!empty($_POST['ativacao'])) ? $_POST['ativacao'] : 0;
$pular = (!empty($_POST['pular'])) ? $_POST['pular'] : '';
$voltar = (!empty($_POST['voltar'])) ? $_POST['voltar'] : '';
$salvar = (!empty($_POST['salvar'])) ? $_POST['salvar'] : 0;
$exclui_ativacao = (!empty($_GET['exclui-ativacao'])) ? $_GET['exclui-ativacao'] : 0;
$id_contrato = (!empty($_GET['id-contrato'])) ? $_GET['id-contrato'] : '';

if(!empty($_POST['inserir'])){

    $cont = 0;
    $dados_plano = array();
    foreach($download as $conteudo){
        $dados_plano[$cont]['download'] = $conteudo;
        $cont++;
    }

    $cont = 0;
    foreach($upload as $conteudo){
        $dados_plano[$cont]['upload'] = $conteudo;
        $cont++;
    }

    $cont = 0;
    foreach($porcentagem as $conteudo){
        $dados_plano[$cont]['porcentagem'] = $conteudo;
        $cont++;
    }

    $cont = 0;
    foreach($observacao as $conteudo){
        $dados_plano[$cont]['observacao'] = $conteudo;
        $cont++;
    }

    $cont = 0;
    foreach($descricao as $conteudo){
        $dados_plano[$cont]['descricao'] = $conteudo;
        $cont++;
    }

    $verificacao_contrato = DBRead('', 'tb_plano_cliente_contrato', "WHERE id_contrato_plano_pessoa = '".$id_contrato_plano_pessoa."'");

    if(!$verificacao_contrato){
        inserir($dados_plano, $id_contrato_plano_pessoa, $ativacao, $pular, $voltar, $salvar);
    }else{
        $alert = ('Item já existe na base de dados!');
        $alert_type = 'w';        header("location: /api/iframe?token=$request->token&view=plano-cliente-form");
        exit;
    }
    $alert = ('Item já existe na base de dados!','w');
    header("location: /api/iframe?token=$request->token&view=plano-cliente-form");
    exit;

}else if(!empty($_POST['alterar'])){
    $id = (int)$_POST['alterar'];

    $cont = 0;
    $dados_plano = array();
    foreach($download as $conteudo){
        $dados_plano[$cont]['download'] = $conteudo;
        $cont++;
    }

    $cont = 0;
    foreach($upload as $conteudo) {
        $dados_plano[$cont]['upload'] = $conteudo;
        $cont++;
    }

    $cont = 0;
    foreach($porcentagem as $conteudo) {
        $dados_plano[$cont]['porcentagem'] = $conteudo;
        $cont++;
    }

    $cont = 0;
    foreach($observacao as $conteudo) {
        $dados_plano[$cont]['observacao'] = $conteudo;
        $cont++;
    }

    $cont = 0;
    foreach($descricao as $conteudo) {
        $dados_plano[$cont]['descricao'] = $conteudo;
        $cont++;
    }

    $verificacao_contrato = DBRead('', 'tb_plano_cliente_contrato', "WHERE id_contrato_plano_pessoa = '".$id_contrato_plano_pessoa."' AND id_plano_cliente_contrato != ".$id);

    if(!$verificacao_contrato){
        alterar($id, $dados_plano, $id_contrato_plano_pessoa, $ativacao, $pular, $voltar, $salvar);
    }else{
        $alert = ('Item já existe na base de dados!');
        $alert_type = 'w';        header("location: /api/iframe?token=$request->token&view=plano-cliente-form&alterar=$id");
        exit;
    }

    $alert = ('Item já existe na base de dados!','w');
    header("location: /api/iframe?token=$request->token&view=plano-cliente-form&alterar=$id");
    exit;
    
} else if(isset($_GET['excluir'])){
    $id = (int)$_GET['excluir'];
    excluir($id, $exclui_ativacao, $id_contrato);
}else{
    header("location: ../adm.php");
    exit;
}

function inserir($dados_plano, $id_contrato_plano_pessoa, $ativacao, $pular, $voltar, $salvar){

    $dados = array(
        'id_contrato_plano_pessoa' => $id_contrato_plano_pessoa
    );
    $insertID = DBCreate('', 'tb_plano_cliente_contrato', $dados, true);
    registraLog('Inserção de novo plano cliente.','i','tb_plano_cliente_contrato',$insertID,"id_contrato_plano_pessoa: $id_contrato_plano_pessoa");

    foreach($dados_plano as $conteudo){

        $download = $conteudo['download'];
        $upload = $conteudo['upload'];
        $descricao = $conteudo['descricao'];
        $observacao = $conteudo['observacao'];
        
        if($conteudo['download'] && $conteudo['descricao']){
            $dadosPlano = array(
                'download' => $download,
                    'upload' => $upload,
                    'descricao' => $descricao,
                    'observacao' => $observacao,
                    'id_plano_cliente_contrato' => $insertID
                );
                $insertPlanos = DBCreate('', 'tb_plano_cliente', $dadosPlano);
                registraLog('Inserção de novos atributos plano cliente.','i','tb_plano_cliente',$insertPlanos,"download: $download | upload: $upload | descricao: $descricao | observacao: $observacao");

                // QUADRO INFORMATIVO HISTORICO
                inserirHistorico($id_contrato_plano_pessoa, 1, "Inserção de novos atributos plano cliente", "download: $download | upload: $upload | descricao: $descricao | observacao: $observacao", 10);

            }
    }

    $alert = ('Item inserido com sucesso!');
    $alert_type = 's';
    if($ativacao == 1){
        if($pular){
            header("location: /api/iframe?token=$request->token&view=quadro-informativo");
        }else if($voltar && $salvar != 1){
            header("location: /api/iframe?token=$request->token&view=manual-form&alterar=$voltar&ativacao=1&dado_posterior=$insertID&id_contrato=$id_contrato_plano_pessoa");
        }else{
            header("location: /api/iframe?token=$request->token&view=quadro-informativo");
        }
    }else{
        header("location: /api/iframe?token=$request->token&view=plano-cliente-busca");
    }
    exit;
}

function alterar($id, $dados_plano, $id_contrato_plano_pessoa, $ativacao, $pular, $voltar, $salvar){

    $dados = array(
        'id_contrato_plano_pessoa' => $id_contrato_plano_pessoa
    );
    DBUpdate('', 'tb_plano_cliente_contrato', $dados, "id_plano_cliente_contrato = $id");
    registraLog('Alteração de plano cliente.','a','tb_plano_cliente_contrato',$id,"id_contrato_plano_pessoa: $id_contrato_plano_pessoa");

    DBDelete('', 'tb_plano_cliente', "id_plano_cliente_contrato = '$id'");

    foreach($dados_plano as $conteudo){

        $download = $conteudo['download'];
        $upload = $conteudo['upload'];
        $descricao = $conteudo['descricao'];
        $observacao = $conteudo['observacao'];

        if($conteudo['download'] && $conteudo['descricao']){
            $dadosPlano = array(
                'download' => $download,
                'upload' => $upload,
                'descricao' => $descricao,
                'observacao' => $observacao,
                'id_plano_cliente_contrato' => $id
            );
            $insertID = DBCreate('', 'tb_plano_cliente', $dadosPlano);
            registraLog('Alteração de atributos plano cliente.','a','tb_plano_cliente',$insertID,"download: $download | upload: $upload | descricao: $descricao | observacao: $observacao");

            // QUADRO INFORMATIVO HISTORICO
            inserirHistorico($id_contrato_plano_pessoa, 2, "Alteração de atributos plano cliente", "download: $download | upload: $upload | descricao: $descricao | observacao: $observacao", 10);

        }
    }

    $alert = ('Item alterado com sucesso!');
    $alert_type = 's';    if($ativacao == 1){
        if($pular){
            header("location: /api/iframe?token=$request->token&view=quadro-informativo");
        }else if($voltar && $salvar != 1){
            header("location: /api/iframe?token=$request->token&view=ura-form&alterar=$voltar&ativacao=1&dado_posterior=$insertID&id_contrato=$id_contrato_plano_pessoa");
        }else{
            header("location: /api/iframe?token=$request->token&view=quadro-informativo");
        }
    }else{
        header("location: /api/iframe?token=$request->token&view=plano-cliente-busca");
    }
    exit;
}

function excluir($id, $exclui_ativacao, $id_contrato){

    if($exclui_ativacao == 1){

        $query = "DELETE FROM tb_plano_cliente_contrato WHERE id_plano_cliente_contrato = $id";
        $link = DBConnect('');
        $result = @mysqli_query($link, $query);
        DBClose($link);
        registraLog('Exclusão de plano cliente.','e','tb_plano_cliente_contrato',$id,'');

        // QUADRO INFORMATIVO HISTORICO
        inserirHistorico($id_contrato, 3, "Exclusão de de plano cliente", "Excluiu dados", 10);

        if(!$result){
            $alert = ('Erro ao excluir item!');
            $alert_type = 'd';        }else{
            $alert = ('Item excluído com sucesso!');
    $alert_type = 's';        }

        header("location: /api/iframe?token=$request->token&view=quadro-informativo");

    }else{
        $id_contrato = DBRead('', 'tb_plano_cliente_contrato', "WHERE id_plano_cliente_contrato = ".$id."");
        $id_contrato = $id_contrato[0]['id_contrato_plano_pessoa'];

        $query = "DELETE FROM tb_plano_cliente_contrato WHERE id_plano_cliente_contrato = $id";
        $link = DBConnect('');
        $result = @mysqli_query($link, $query);
        DBClose($link);
        registraLog('Exclusão de plano cliente.','e','tb_plano_cliente_contrato',$id,'');
       
        // QUADRO INFORMATIVO HISTORICO
        inserirHistorico($id_contrato, 3, "Exclusão de de plano cliente", "Excluiu dados", 10);

        if(!$result){
            $alert = ('Erro ao excluir item!');
            $alert_type = 'd';        }else{
            $alert = ('Item excluído com sucesso!');
    $alert_type = 's';        }
        header("location: /api/iframe?token=$request->token&view=plano-cliente-busca");
    }

    
    exit;
}

?>