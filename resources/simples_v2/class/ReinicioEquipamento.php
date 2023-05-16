<?php
require_once(__DIR__."/System.php");
require_once(__DIR__."/QuadroInformativoHistorico.php");


$id_contrato_plano_pessoa  = (!empty($_POST['id_contrato_plano_pessoa'])) ? $_POST['id_contrato_plano_pessoa'] : '';
$equipamento = (!empty($_POST['equipamento'])) ? $_POST['equipamento'] : '';
$tempo = (!empty($_POST['tempo'])) ? $_POST['tempo'] : '';
$observacao = (!empty($_POST['observacao'])) ? $_POST['observacao'] : '';

$ativacao = (!empty($_POST['ativacao'])) ? $_POST['ativacao'] : 0;
$pular = (!empty($_POST['pular'])) ? $_POST['pular'] : '';
$voltar = (!empty($_POST['voltar'])) ? $_POST['voltar'] : '';
$salvar = (!empty($_POST['salvar'])) ? $_POST['salvar'] : 0;
$exclui_ativacao = (!empty($_GET['exclui-ativacao'])) ? $_GET['exclui-ativacao'] : 0;
$id_contrato = (!empty($_GET['id-contrato'])) ? $_GET['id-contrato'] : '';

if(!empty($_POST['inserir'])){

    $cont = 0;
    $dados_reinicio = array();
    foreach($equipamento as $conteudo){
        $dados_reinicio[$cont]['equipamento'] = $conteudo;
        $cont++;
    }

    $cont = 0;
    foreach($tempo as $conteudo) {
        $dados_reinicio[$cont]['tempo'] = $conteudo;
        $cont++;
    }

    $cont = 0;
    foreach($observacao as $conteudo) {
        $dados_reinicio[$cont]['observacao'] = $conteudo;
        $cont++;
    }

    $verificacao_contrato = DBRead('', 'tb_reinicio_equipamento_contrato', "WHERE id_contrato_plano_pessoa = '".$id_contrato_plano_pessoa."'");

    if(!$verificacao_contrato){
        inserir($id_contrato_plano_pessoa, $dados_reinicio, $ativacao, $pular, $voltar, $salvar);
    }else{
        $alert = ('Item já existe na base de dados!');
        $alert_type = 'w';        header("location: /api/iframe?token=$request->token&view=reinicio-equipamento-form");
        exit;
    }

    $alert = ('Item já existe na base de dados!','w');
    header("location: /api/iframe?token=$request->token&view=reinicio-equipamento-form");
    exit;


}else if(!empty($_POST['alterar'])){
    $id = (int)$_POST['alterar'];

    $cont = 0;
    $dados_reinicio = array();
    foreach($equipamento as $conteudo){
        $dados_reinicio[$cont]['equipamento'] = $conteudo;
        $cont++;
    }
    $cont = 0;
    foreach($tempo as $conteudo){
        $dados_reinicio[$cont]['tempo'] = $conteudo;
        $cont++;
    }
    $cont = 0;
    foreach($observacao as $conteudo){
        $dados_reinicio[$cont]['observacao'] = $conteudo;
        $cont++;
    }

    $verificacao_contrato = DBRead('', 'tb_reinicio_equipamento_contrato', "WHERE id_contrato_plano_pessoa = '".$id_contrato_plano_pessoa."' AND id_reinicio_equipamento_contrato != ".$id);

    if(!$verificacao_contrato){
        alterar($id, $id_contrato_plano_pessoa, $dados_reinicio, $ativacao, $pular, $voltar, $salvar);
    }else{
        $alert = ('Item já existe na base de dados!');
        $alert_type = 'w';        header("location: /api/iframe?token=$request->token&view=reinicio-equipamento-form");
        exit;
    }
    
    $alert = ('Item já existe na base de dados!','w');
    header("location: /api/iframe?token=$request->token&view=reinicio-equipamento-form");
    exit;
    
} else if(isset($_GET['excluir'])){

    $id = (int)$_GET['excluir'];
    excluir($id, $exclui_ativacao, $id_contrato);
}else{
    header("location: ../adm.php");
    exit;
}

function inserir($id_contrato_plano_pessoa, $dados_reinicio, $ativacao, $pular, $voltar, $salvar){

    $dados = array(
        'id_contrato_plano_pessoa' => $id_contrato_plano_pessoa
    );

    $insertID = DBCreate('', 'tb_reinicio_equipamento_contrato', $dados, true);
    registraLog('Inserção de novo tempo de reinicio equipamento pessoa.','i','tb_reinicio_equipamento_contrato',$insertID,"id_contrato_plano_pessoa: $id_contrato_plano_pessoa");

    foreach($dados_reinicio as $conteudo){

        $equipamento = $conteudo['equipamento'];
        $tempo = $conteudo['tempo'];
        $observacao = $conteudo['observacao'];

        if($conteudo['equipamento'] && $conteudo['tempo']){
            $dadosEquipamento = array(
                'equipamento' => $equipamento,
                'tempo' => $tempo,
                'observacao' => $observacao,
                'id_reinicio_equipamento_contrato' => $insertID
            );
            
            $insertReinicio = DBCreate('', 'tb_reinicio_equipamento', $dadosEquipamento);
            registraLog('Inserção de novo tempo de reinicio.','i','tb_reinicio_equipamento',$insertReinicio,"equipamento: $equipamento | tempo: $tempo | observacao: $observacao | id_reinicio_equipamento_contrato: $insertID");

            // QUADRO INFORMATIVO HISTORICO
            $dados_equipamento_historico = DBRead('','tb_tipo_equipamento', "WHERE id_tipo_equipamento = '".$equipamento."' LIMIT 1");

            inserirHistorico($id_contrato_plano_pessoa, 1, "Inserção de tempo de reinicio equipamento", "equipamento: ".$dados_equipamento_historico[0]['descricao']." | tempo: $tempo | observacao: $observacao", 16);

        }
    }
    
    $alert = ('Item inserido com sucesso!');
    $alert_type = 's';    if($ativacao == 1){
        if($pular){
            header("location: /api/iframe?token=$request->token&view=acesso-equipamento-form&alterar=$pular&ativacao=1&dado_posterior=$insertID&id_contrato=$id_contrato_plano_pessoa");
        }else if($voltar && $salvar != 1){
            header("location: /api/iframe?token=$request->token&view=configuracao-roteadores-form&alterar=$voltar&ativacao=1&dado_posterior=$insertID&id_contrato=$id_contrato_plano_pessoa");
        }else{
            header("location: /api/iframe?token=$request->token&view=acesso-equipamento-form&ativacao=1&dado_posterior=$insertID&id_contrato=$id_contrato_plano_pessoa");
        }
    }else{
        header("location: /api/iframe?token=$request->token&view=reinicio-equipamento-busca");
    }
    exit;
}

function alterar($id, $id_contrato_plano_pessoa, $dados_reinicio, $ativacao, $pular, $voltar, $salvar){

    $dados = array(
        'id_contrato_plano_pessoa' => $id_contrato_plano_pessoa
    );
    DBUpdate('', 'tb_reinicio_equipamento_contrato', $dados, "id_reinicio_equipamento_contrato = $id");

    DBDelete('', 'tb_reinicio_equipamento', "id_reinicio_equipamento_contrato = '$id'");
    foreach($dados_reinicio as $conteudo){

        $equipamento = $conteudo['equipamento'];
        $tempo = $conteudo['tempo'];
        $observacao = $conteudo['observacao'];
        
        if($conteudo['equipamento'] && $conteudo['tempo']){
            $dadosReinicio = array(
                'equipamento' => $equipamento,
                'tempo' => $tempo,
                'observacao' => $observacao,
                'id_reinicio_equipamento_contrato' => $id
            );
            $insertID = DBCreate('', 'tb_reinicio_equipamento', $dadosReinicio);
            registraLog('Inserção de novo tempo de reinicio.','i','tb_reinicio_equipamento',$insertID,"equipamento: $equipamento | tempo: $tempo | observacao: $observacao | id_reinicio_equipamento_contrato: $id");
            // QUADRO INFORMATIVO HISTORICO
            $dados_equipamento_historico = DBRead('','tb_tipo_equipamento', "WHERE id_tipo_equipamento = '".$equipamento."' LIMIT 1");
            inserirHistorico($id_contrato_plano_pessoa, 2, "Alteração de tempo de reinicio equipamento", "equipamento: ".$dados_equipamento_historico[0]['descricao']." | tempo: $tempo | observacao: $observacao", 16);
        }
    }

    registraLog('Alteração de tempo reinicio pessoa.','a','tb_reinicio_equipamento_contrato',$id,"id_contrato_plano_pessoa: $id_contrato_plano_pessoa");
    $alert = ('Item alterado com sucesso!');
    $alert_type = 's';    if($ativacao == 1){
        if($pular){
            header("location: /api/iframe?token=$request->token&view=acesso-equipamento-form&alterar=$pular&ativacao=1&dado_posterior=$insertID&id_contrato=$id_contrato_plano_pessoa");
        }else if($voltar && $salvar != 1){
            header("location: /api/iframe?token=$request->token&view=configuracao-roteadores-form&alterar=$voltar&ativacao=1&dado_posterior=$insertID&id_contrato=$id_contrato_plano_pessoa");
        }else{
            header("location: /api/iframe?token=$request->token&view=acesso-equipamento-form&ativacao=1&dado_posterior=$insertID&id_contrato=$id_contrato_plano_pessoa");
        }
    }else{
        header("location: /api/iframe?token=$request->token&view=reinicio-equipamento-busca");
    }
    exit;
}

function excluir($id, $exclui_ativacao, $id_contrato){

    if($exclui_ativacao == 1){

        $dados = DBRead('', 'tb_equipamento_contrato', "WHERE id_contrato_plano_pessoa = $id_contrato");

        $query = "DELETE FROM tb_reinicio_equipamento_contrato WHERE id_reinicio_equipamento_contrato = $id";
        $link = DBConnect('');
        $result = @mysqli_query($link, $query);
        DBClose($link);
        registraLog('Exclusão de tempo de reinicio de equipamento.','e','tb_reinicio_equipamento_contrato',$id,'');

        // QUADRO INFORMATIVO HISTORICO
        inserirHistorico($id_contrato, 3, "Exclusão de tempo de reinicio de equipamento", "Excluiu dados", 16);

        if(!$result){
            $alert = ('Erro ao excluir item!');
            $alert_type = 'd';        }else{
            $alert = ('Item excluído com sucesso!');
    $alert_type = 's';        }

        if($dados){
            $dado_posterior = $dados[0]['id_equipamento_contrato'];
            header("location: /api/iframe?token=$request->token&view=acesso-equipamento-form&alterar=$dado_posterior&ativacao=1&id_contrato=$id_contrato");
        }else{
            header("location: /api/iframe?token=$request->token&view=acesso-equipamento-form&ativacao=1&id_contrato=$id_contrato");
        }
        
    }else{
        $id_contrato = DBRead('', 'tb_reinicio_equipamento_contrato', "WHERE id_reinicio_equipamento_contrato = ".$id."");
        $id_contrato = $id_contrato[0]['id_contrato_plano_pessoa'];

        $query = "DELETE FROM tb_reinicio_equipamento_contrato WHERE id_reinicio_equipamento_contrato = $id";
        $link = DBConnect('');
        $result = @mysqli_query($link, $query);
        DBClose($link);
        registraLog('Exclusão de tempo de reinicio de equipamento.','e','tb_reinicio_equipamento_contrato',$id,'');

        // QUADRO INFORMATIVO HISTORICO
        inserirHistorico($id_contrato, 3, "Exclusão de tempo de reinicio de equipamento", "Excluiu dados", 16);
       
        if(!$result){
            $alert = ('Erro ao excluir item!');
            $alert_type = 'd';        }else{
            $alert = ('Item excluído com sucesso!');
    $alert_type = 's';        }
        header("location: /api/iframe?token=$request->token&view=reinicio-equipamento-busca");
    }
    
    exit;
}

?>