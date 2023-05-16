<?php
require_once(__DIR__."/System.php");
require_once(__DIR__."/QuadroInformativoHistorico.php");


$latitude = (!empty($_POST['latitude'])) ? $_POST['latitude'] : '';
$longitude = (!empty($_POST['longitude'])) ? $_POST['longitude'] : '';
$endereco = (!empty($_POST['endereco'])) ? $_POST['endereco'] : '';
$ponto_referencia = (!empty($_POST['ponto_referencia'])) ? $_POST['ponto_referencia'] : '';
$id_cidade = (!empty((int) $_POST['cidade'])) ? $_POST['cidade'] : '';
$id_estado = (!empty((int) $_POST['estado'])) ? $_POST['estado'] : '';

$telefones = (!empty($_POST['telefone'])) ? $_POST['telefone'] : '';
$observacoes_tel = (!empty($_POST['observacao_tel'])) ? $_POST['observacao_tel'] : '';
$id_localizacao = (!empty((int) $_POST['id_localizacao'])) ? (int) $_POST['id_localizacao'] : 0;

$id_contrato_plano_pessoa  = (!empty((int) $_POST['id_contrato_plano_pessoa'])) ? (int) $_POST['id_contrato_plano_pessoa'] : 0;

$ativacao = (!empty($_POST['ativacao'])) ? $_POST['ativacao'] : 0;
$pular = (!empty($_POST['pular'])) ? $_POST['pular'] : '';
$voltar = (!empty($_POST['voltar'])) ? $_POST['voltar'] : '';
$salvar = (!empty($_POST['salvar'])) ? $_POST['salvar'] : 0;

$adicionar_localizacao = (!empty($_POST['adicionar_localizacao'])) ? $_POST['adicionar_localizacao'] : 0;

$exclui_ativacao = (!empty($_GET['exclui_ativacao'])) ? $_GET['exclui_ativacao'] : 0;
$id_contrato = (!empty((int) $_GET['id_contrato'])) ? (int) $_GET['id_contrato'] : 0;
$id_proximo = (!empty((int) $_POST['id_proximo'])) ? (int) $_POST['id_proximo'] : 0;

if(!empty($_POST['inserir'])){

    $cont = 0;
    $dados_telefone = array();
    foreach($telefones as $conteudo){
        $dados_telefone[$cont]['telefone'] = $conteudo;
        $cont++;
    }
    $cont = 0;
    foreach($observacoes_tel as $conteudo){
        $dados_telefone[$cont]['observacao_tel'] = $conteudo;
        $cont++;
    }
    if($latitude != "" && $longitude != ""){
        inserir($id_contrato_plano_pessoa, $endereco, $ponto_referencia, $latitude, $longitude, $dados_telefone, $ativacao, $pular, $voltar, $salvar, $adicionar_localizacao, $id_proximo, $id_cidade, $id_estado);
    }else{
        $alert = ('Você deve clicar na localização correta!', 'w');
        if($adicionar_localizacao == 1 || $salvar == 1){
            header("location: /api/iframe?token=$request->token&view=localizacao-form&ativacao=1&id_contrato=$id_contrato_plano_pessoa");
        }else{
            header("location: /api/iframe?token=$request->token&view=localizacao-form");
        }
        exit;
    }

}else if(!empty($_POST['alterar'])){
    $id = (int)$_POST['alterar'];
    $cont = 0;
    $dados_telefone = array();
    foreach($telefones as $conteudo){
        $dados_telefone[$cont]['telefone'] = $conteudo;
        $cont++;
    }
    $cont = 0;
    foreach($observacoes_tel as $conteudo){
        $dados_telefone[$cont]['observacao_tel'] = $conteudo;
        $cont++;
    }
    alterar($id, $id_contrato_plano_pessoa, $endereco, $ponto_referencia, $latitude, $longitude, $dados_telefone, $ativacao, $pular, $voltar, $salvar, $adicionar_localizacao, $id_proximo, $id_cidade, $id_estado);
} else if(isset($_GET['excluir'])){
    $id = (int)$_GET['excluir'];
    excluir($id, $exclui_ativacao, $id_contrato);
}else{
    header("location: ../adm.php");
    exit;
}

function inserir($id_contrato_plano_pessoa, $endereco, $ponto_referencia, $latitude, $longitude, $dados_telefone, $ativacao, $pular, $voltar, $salvar, $adicionar_localizacao, $id_proximo, $id_cidade, $id_estado){

    $dados = array(
        'id_contrato_plano_pessoa' => $id_contrato_plano_pessoa
    );

    $insertID = DBCreate('', 'tb_localizacao_contrato', $dados, true);
    registraLog('Inserção de nova localizacao.','i','tb_localizacao_contrato', $insertID, "id_contrato_plano_pessoa: $id_contrato_plano_pessoa");
    if($latitude && $longitude){
        $dadosLocal = array(
            'latitude' => $latitude,
            'longitude' => $longitude,
            'endereco' => $endereco,
            'ponto_referencia' => $ponto_referencia,
            'id_cidade' => $id_cidade,
            'id_estado' => $id_estado,
            'id_localizacao_contrato' => $insertID
        );
        $insertLocalizacao = DBCreate('', 'tb_localizacao', $dadosLocal, true);
        registraLog('Inserção de novo local.','i','tb_localizacao',$insertLocalizacao,"latitude: $latitude | longitude: $longitude | endereco: $endereco | ponto_referencia: $ponto_referencia | id_cidade: $id_cidade | id_estado: $id_estado");

        // QUADRO INFORMATIVO HISTORICO
        $dados_estado_historico = DBRead('','tb_estado', "WHERE id_estado = '".$id_estado."' LIMIT 1");
        $dados_cidade_historico = DBRead('','tb_cidade', "WHERE id_cidade = '".$id_cidade."' LIMIT 1");
        inserirHistorico($id_contrato_plano_pessoa, 1, "Inserção de nova localizacao", "latitude: $latitude | longitude: $longitude | endereco: $endereco | ponto referencia: $ponto_referencia | cidade: ".$dados_cidade_historico[0]['nome']." | estado: ".$dados_estado_historico[0]['nome']." ", 7);
    }

    foreach($dados_telefone as $conteudo){

        $telefone = $conteudo['telefone'];
        $observacao = $conteudo['observacao_tel'];

        if($conteudo['telefone']){
            $dadosTelefone = array(
                'telefone' => $telefone,
                'observacao' => $observacao,
                'id_localizacao' => $insertLocalizacao
            );
            $insertTelefone = DBCreate('', 'tb_localizacao_telefone', $dadosTelefone);
            registraLog('Inserção de novo telefone.','i','tb_localizacao_telefone',$insertTelefone,"telefone: $telefone | observacao: $observacao | id_localizacao: $insertLocalizacao");

            // QUADRO INFORMATIVO HISTORICO
            inserirHistorico($id_contrato_plano_pessoa, 1, "Inserção de novo telefone", "telefone: $telefone | observacao: $observacao", 7);
        }
    }
    $alert = ('Item inserido com sucesso!');
    $alert_type = 's';    if($ativacao == 1){
        if($pular && $adicionar_localizacao == 0){
            header("location: /api/iframe?token=$request->token&view=plantonista-form&alterar=$pular&ativacao=1&dado_posterior=$insertID&id_contrato=$id_contrato_plano_pessoa");
        }else if($voltar && $salvar != 1 && $adicionar_localizacao == 0){
            header("location: /api/iframe?token=$request->token&view=informacoes-gerais-form&alterar=$voltar&ativacao=1&dado_posterior=$insertID&id_contrato=$id_contrato_plano_pessoa");
        }else if($pular && $adicionar_localizacao == 1){
            header("location: /api/iframe?token=$request->token&view=localizacao-form&ativacao=1&id_contrato=$id_contrato_plano_pessoa");
        }else if($voltar && $adicionar_localizacao == 1){
            header("location: /api/iframe?token=$request->token&view=localizacao-form&ativacao=1&id_contrato=$id_contrato_plano_pessoa");
        }else if($id_proximo && $adicionar_localizacao == 1){
            header("location: /api/iframe?token=$request->token&view=localizacao-form&ativacao=1&id_contrato=$id_contrato_plano_pessoa");
        }else if($adicionar_localizacao == 1){
            header("location: /api/iframe?token=$request->token&view=localizacao-form&ativacao=1&dado_posterior=$insertID&id_contrato=$id_contrato_plano_pessoa");
        }else if($adicionar_localizacao == 0){
            header("location: /api/iframe?token=$request->token&view=plantonista-form&ativacao=1&dado_posterior=$insertID&id_contrato=$id_contrato_plano_pessoa");
        }
    }else{
        header("location: /api/iframe?token=$request->token&view=localizacao-busca");
    }
    exit;
}

function alterar($id, $id_contrato_plano_pessoa, $endereco, $ponto_referencia, $latitude, $longitude, $dados_telefone, $ativacao, $pular, $voltar, $salvar, $adicionar_localizacao, $id_proximo, $id_cidade, $id_estado){

    $dados = array(
        'id_contrato_plano_pessoa' => $id_contrato_plano_pessoa
    );
    DBUpdate('', 'tb_localizacao_contrato', $dados, "id_localizacao_contrato = $id");
    echo '<hr>';
    DBDelete('', 'tb_localizacao', "id_localizacao_contrato = $id");
    if($latitude && $longitude){
        $dadosLocal = array(
            'latitude' => $latitude,
            'longitude' => $longitude,
            'endereco' => $endereco,
            'ponto_referencia' => $ponto_referencia,
            'id_cidade' => (int)$id_cidade,
            'id_estado' => (int)$id_estado,
            'id_localizacao_contrato' => $id
        );
        $insertID = DBCreate('', 'tb_localizacao', $dadosLocal, true);
        registraLog('Alteração de local.','a','tb_localizacao',$insertID,"latitude: $latitude | longitude: $longitude | endereco: $endereco | ponto_referencia: $ponto_referencia | id_cidade: $id_cidade | id_estado: $id_estado");

        // QUADRO INFORMATIVO HISTORICO
        $dados_estado_historico = DBRead('','tb_estado', "WHERE id_estado = '".$id_estado."' LIMIT 1");
        $dados_cidade_historico = DBRead('','tb_cidade', "WHERE id_cidade = '".$id_cidade."' LIMIT 1");
        inserirHistorico($id_contrato_plano_pessoa, 2, "Alteração de localizacao", "latitude: $latitude | longitude: $longitude | endereco: $endereco | ponto referencia: $ponto_referencia | cidade: ".$dados_cidade_historico[0]['nome']." | estado: ".$dados_estado_historico[0]['nome']." ", 7);
    }
    registraLog('Alteração de localizacao.','a','tb_localizacao_contrato',$id,"id_contrato_plano_pessoa: $id_contrato_plano_pessoa");
    foreach($dados_telefone as $conteudo){

        $telefone = $conteudo['telefone'];
        $observacao = $conteudo['observacao_tel'];

        if($conteudo['telefone']){
            $dadosTelefone = array(
                'telefone' => $telefone,
                'observacao' => $observacao,
                'id_localizacao' => $insertID
            );
            $insertTelefone = DBCreate('', 'tb_localizacao_telefone', $dadosTelefone);
            registraLog('Alteração de telefone.','a','tb_localizacao_telefone',$insertTelefone,"telefone: $telefone | observacao: $observacao | id_localizacao: $insertID");

            // QUADRO INFORMATIVO HISTORICO
            inserirHistorico($id_contrato_plano_pessoa, 2, "Alteração de telefone", "telefone: $telefone | observacao: $observacao", 7);
        }
    }
    $alert = ('Item alterado com sucesso!');
    $alert_type = 's';    if($ativacao == 1){
        if($pular && $adicionar_localizacao == 0){
            if($id_proximo && $adicionar_localizacao == 1){
                header("location: /api/iframe?token=$request->token&view=localizacao-form&alterar=$pular&ativacao=1&dado_posterior=$id&id_contrato=$id_contrato_plano_pessoa");
            }else if($id_proximo && $adicionar_localizacao == 0){
                header("location: /api/iframe?token=$request->token&view=localizacao-form&alterar=$pular&ativacao=1&dado_posterior=$id&id_contrato=$id_contrato_plano_pessoa");
            }else if($adicionar_localizacao == 0){
                header("location: /api/iframe?token=$request->token&view=plantonista-form&alterar=$pular&ativacao=1&dado_posterior=$id&id_contrato=$id_contrato_plano_pessoa");
            }
        }else if($voltar && $salvar != 1 && $adicionar_localizacao == 0){
            header("location: /api/iframe?token=$request->token&view=informacoes-gerais-form&alterar=$voltar&ativacao=1&dado_posterior=$id&id_contrato=$id_contrato_plano_pessoa");
        }else if($pular && $adicionar_localizacao == 1){
            header("location: /api/iframe?token=$request->token&view=localizacao-form&ativacao=1&id_contrato=$id_contrato_plano_pessoa");
        }else if($adicionar_localizacao == 1 && $voltar){
            header("location: /api/iframe?token=$request->token&view=localizacao-form&ativacao=1&id_contrato=$id_contrato_plano_pessoa");
        }else if($id_proximo){
            header("location: /api/iframe?token=$request->token&view=localizacao-form&ativacao=1&id_contrato=$id_contrato_plano_pessoa");
        }else if($adicionar_localizacao == 1){
            header("location: /api/iframe?token=$request->token&view=localizacao-form&ativacao=1&dado_posterior=$id&id_contrato=$id_contrato_plano_pessoa");
        }else if($adicionar_localizacao == 0){
            header("location: /api/iframe?token=$request->token&view=plantonista-form&ativacao=1&dado_posterior=$id&id_contrato=$id_contrato_plano_pessoa");
        }
    }else{
        header("location: /api/iframe?token=$request->token&view=localizacao-busca");
    }
    exit;
}

function excluir($id, $exclui_ativacao, $id_contrato){

    if($exclui_ativacao == 1){
        $dados_localizacao = DBRead('', 'tb_localizacao_contrato', "WHERE id_contrato_plano_pessoa = $id_contrato AND id_localizacao_contrato > $id");
        if($dados_localizacao){
            $dados = DBRead('', 'tb_localizacao_contrato', "WHERE id_contrato_plano_pessoa = $id_contrato AND id_localizacao_contrato > $id");
        }else{
            $dados = DBRead('', 'tb_plantonista_contrato', "WHERE id_contrato_plano_pessoa = $id_contrato");
        }
        $query = "DELETE FROM tb_localizacao_contrato WHERE id_localizacao_contrato = $id";
        $link = DBConnect('');
        $result = @mysqli_query($link, $query);
        DBClose($link);
        registraLog('Exclusão de localizacao.', 'e', 'tb_localizacao_contrato', $id, '');
        
        // QUADRO INFORMATIVO HISTORICO
        inserirHistorico($id_contrato, 3, "Exclusão de localizacao", "Excluiu dados", 7);

        if(!$result){
            $alert = ('Erro ao excluir item!', 'd');
        }else{
            $alert = ('Item excluído com sucesso!', 's');
        }
        if($dados){
            if($dados_localizacao){
                $dado_posterior = $dados[0]['id_localizacao_contrato'];
                header("location: /api/iframe?token=$request->token&view=localizacao-form&alterar=$dado_posterior&ativacao=1&id_contrato=$id_contrato");
            }else{
                $dado_posterior = $dados[0]['id_plantonista_contrato'];
                header("location: /api/iframe?token=$request->token&view=plantonista-form&alterar=$dado_posterior&ativacao=1&id_contrato=$id_contrato");
            }
        }else{
            header("location: /api/iframe?token=$request->token&view=plantonista-form&ativacao=1&id_contrato=$id_contrato");
        }
    }else{
        $id_contrato = DBRead('', 'tb_localizacao_contrato', "WHERE id_localizacao_contrato = ".$id."");
        $id_contrato = $id_contrato[0]['id_contrato_plano_pessoa'];
        
        $query = "DELETE FROM tb_localizacao_contrato WHERE id_localizacao_contrato = $id";
        $link = DBConnect('');
        $result = @mysqli_query($link, $query);
        DBClose($link);
        registraLog('Exclusão de localizacao.','e','tb_localizacao_contrato',$id,'');

        // QUADRO INFORMATIVO HISTORICO
        inserirHistorico($id_contrato, 3, "Exclusão de localizacao", "Excluiu dados", 7);

        if(!$result){
            $alert = ('Erro ao excluir item!');
            $alert_type = 'd';        }else{
            $alert = ('Item excluído com sucesso!');
    $alert_type = 's';        }
        header("location: /api/iframe?token=$request->token&view=localizacao-busca");
    }

    
    exit;
}

?>