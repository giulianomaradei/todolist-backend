<?php
require_once(__DIR__."/System.php");
require_once(__DIR__."/QuadroInformativoHistorico.php");


$tipo_equipamento = (!empty($_POST['tipo_equipamento'])) ? $_POST['tipo_equipamento'] : '';
$id_tipo_plano_cliente = (!empty($_POST['id_tipo_plano_cliente'])) ? $_POST['id_tipo_plano_cliente'] : '';
$porcentagem = (!empty($_POST['porcentagem'])) ? $_POST['porcentagem'] : '';
$tipo_transferencia = (!empty($_POST['tipo_transferencia'])) ? $_POST['tipo_transferencia'] : '';
$observacao = (!empty($_POST['observacao'])) ? $_POST['observacao'] : '';
$id_contrato_plano_pessoa  = (!empty($_POST['id_contrato_plano_pessoa'])) ? $_POST['id_contrato_plano_pessoa'] : '';

$ativacao = (!empty($_POST['ativacao'])) ? $_POST['ativacao'] : 0;
$pular = (!empty($_POST['pular'])) ? $_POST['pular'] : '';
$voltar = (!empty($_POST['voltar'])) ? $_POST['voltar'] : '';
$salvar = (!empty($_POST['salvar'])) ? $_POST['salvar'] : 0;
$exclui_ativacao = (!empty($_GET['exclui-ativacao'])) ? $_GET['exclui-ativacao'] : 0;
$id_contrato = (!empty($_GET['id-contrato'])) ? $_GET['id-contrato'] : '';

if(!empty($_POST['inserir'])){

    $cont = 0;
    $dados_velocidade = array();
    foreach($tipo_equipamento as $conteudo){
        $dados_velocidade[$cont]['tipo_equipamento'] = $conteudo;
        $cont++;
    }

    $cont = 0;
    foreach($id_tipo_plano_cliente as $conteudo) {
        $dados_velocidade[$cont]['id_tipo_plano_cliente'] = $conteudo;
        $cont++;
    }

    $cont = 0;
    foreach($porcentagem as $conteudo){
        $dados_velocidade[$cont]['porcentagem'] = $conteudo;
        $cont++;
    }

    $cont = 0;
    foreach($tipo_transferencia as $conteudo){
        $dados_velocidade[$cont]['tipo_transferencia'] = $conteudo;
        $cont++;
    }

    $contrato_existe = DBRead('', 'tb_velocidade_minima_encaminhar_contrato', "WHERE id_contrato_plano_pessoa = ".$id_contrato_plano_pessoa);

    if(!$contrato_existe){
        inserir($id_contrato_plano_pessoa, $observacao, $dados_velocidade, $ativacao, $pular, $voltar, $salvar);
    }else{
        $alert = ('Item já existe na base de dados!');
        $alert_type = 'w';        header("location: /api/iframe?token=$request->token&view=velocidade-minima-encaminhar-form");
        exit;
    }

    $alert = ('Item já existe na base de dados!','w');
    header("location: /api/iframe?token=$request->token&view=velocidade-minima-encaminhar-form");
    exit;

}else if(!empty($_POST['alterar'])){
    $id = (int)$_POST['alterar'];

    $cont = 0;
    $dados_velocidade = array();
    foreach($tipo_equipamento as $conteudo){
        $dados_velocidade[$cont]['tipo_equipamento'] = $conteudo;
        $cont++;
    }

    $cont = 0;
    foreach($id_tipo_plano_cliente as $conteudo){
        $dados_velocidade[$cont]['id_tipo_plano_cliente'] = $conteudo;
        $cont++;
    }

    $cont = 0;
    foreach($porcentagem as $conteudo){
        $dados_velocidade[$cont]['porcentagem'] = $conteudo;
        $cont++;
    }

    $cont = 0;
    foreach($tipo_transferencia as $conteudo){
        $dados_velocidade[$cont]['tipo_transferencia'] = $conteudo;
        $cont++;
    }

    $contrato_existe = DBRead('', 'tb_velocidade_minima_encaminhar_contrato', "WHERE id_contrato_plano_pessoa = ".$id_contrato_plano_pessoa." AND id_velocidade_minima_encaminhar_contrato != ".$id);
    if(!$contrato_existe){
        alterar($id, $id_contrato_plano_pessoa, $observacao, $dados_velocidade, $ativacao, $pular, $voltar, $salvar);
    }else{
        $alert = ('Item já existe na base de dados!');
        $alert_type = 'w';        header("location: /api/iframe?token=$request->token&view=velocidade-minima-encaminhar-form&alterar=$id");
        exit;
    }
    $alert = ('Item já existe na base de dados!','w');
    header("location: /api/iframe?token=$request->token&view=velocidade-minima-encaminhar-form&alterar=$id");
    exit;
    
} else if(isset($_GET['excluir'])){

    $id = (int)$_GET['excluir'];
    excluir($id, $exclui_ativacao, $id_contrato);
}else{
    header("location: ../adm.php");
    exit;
}

function inserir($id_contrato_plano_pessoa, $observacao, $dados_velocidade, $ativacao, $pular, $voltar, $salvar){

    $dados = array(
        'id_contrato_plano_pessoa' => $id_contrato_plano_pessoa,
        'observacao' => $observacao
    );

    $insertID = DBCreate('', 'tb_velocidade_minima_encaminhar_contrato', $dados, true);
    registraLog('Inserção de novo velocidade.','i','tb_velocidade_minima_encaminhar_contrato',$insertID,"id_contrato_plano_pessoa: $id_contrato_plano_pessoa | observacao: $observacao");

    // QUADRO INFORMATIVO HISTORICO
    inserirHistorico($id_contrato_plano_pessoa, 1, "Inserção de velocidade mínima", "observação: $observacao", 18);

    foreach($dados_velocidade as $conteudo){

        $tipo_equipamento = $conteudo['tipo_equipamento'];
        $id_tipo_plano_cliente = $conteudo['id_tipo_plano_cliente'];
        $porcentagem = $conteudo['porcentagem'];
        $tipo_transferencia = $conteudo['tipo_transferencia'];

        if($conteudo['tipo_equipamento'] && $conteudo['id_tipo_plano_cliente'] && $conteudo['porcentagem']){
            $dadosVelocidade = array(
                'tipo_equipamento' => $tipo_equipamento,
                'id_tipo_plano_cliente' => $id_tipo_plano_cliente,
                'porcentagem' => $porcentagem,
                'tipo_transferencia' => $tipo_transferencia,
                'id_velocidade_minima_encaminhar_contrato' => $insertID
            );
            
            $insertVelocidades = DBCreate('', 'tb_velocidade_minima_encaminhar', $dadosVelocidade, true);
            registraLog('Inserção de nova velocidade mínima.','i','tb_velocidade_minima_encaminhar',$insertVelocidades,"tipo_equipamento: $tipo_equipamento | id_tipo_plano_cliente: $id_tipo_plano_cliente | id_velocidade_minima_encaminhar_contrato: $insertID | porcentagem: $porcentagem | tipo_transferencia: $tipo_transferencia");

            // QUADRO INFORMATIVO HISTORICO
            $dados_tipo_equipamento_historico = DBRead('','tb_tipo_equipamento', "WHERE id_tipo_equipamento = '".$tipo_equipamento."' LIMIT 1");
            $dados_tipo_plano_cliente_historico = DBRead('','tb_tipo_plano_cliente', "WHERE id_tipo_plano_cliente = '".$id_tipo_plano_cliente."' LIMIT 1");

            inserirHistorico($id_contrato_plano_pessoa, 1, "Inserção de velocidade mínima", "tipo de equipamento: ".$dados_tipo_equipamento_historico[0]['descricao']." | tipo plano cliente: ".$dados_tipo_plano_cliente_historico[0]['descricao']." | porcentagem: $porcentagem | tipo transferencia: $tipo_transferencia", 18);
        }
    }
    
    $alert = ('Item inserido com sucesso!');
    $alert_type = 's';
    if($ativacao == 1){
        if($pular){
            header("location: /api/iframe?token=$request->token&view=parametro-form&alterar=$pular&ativacao=1&dado_posterior=$insertID&id_contrato=$id_contrato_plano_pessoa");
        }else if($voltar && $salvar != 1){
            header("location: /api/iframe?token=$request->token&view=radio-sinal-form&alterar=$voltar&ativacao=1&dado_posterior=$insertID&id_contrato=$id_contrato_plano_pessoa");
        }else{
            header("location: /api/iframe?token=$request->token&view=parametro-form&ativacao=1&dado_posterior=$insertID&id_contrato=$id_contrato_plano_pessoa");
        }
    }else{
        header("location: /api/iframe?token=$request->token&view=velocidade-minima-encaminhar-busca");
    }
    exit;
}

function alterar($id, $id_contrato_plano_pessoa, $observacao, $dados_velocidade, $ativacao, $pular, $voltar, $salvar){

    $dados = array(
        'id_contrato_plano_pessoa' => $id_contrato_plano_pessoa,
        'observacao' => $observacao
    );
    DBUpdate('', 'tb_velocidade_minima_encaminhar_contrato', $dados, "id_velocidade_minima_encaminhar_contrato = $id");
   
    // QUADRO INFORMATIVO HISTORICO
    inserirHistorico($id_contrato_plano_pessoa, 2, "Alteração de velocidade mínima", "observação: $observacao", 18);
    
    DBDelete('', 'tb_velocidade_minima_encaminhar', "id_velocidade_minima_encaminhar_contrato = '$id'");

    foreach($dados_velocidade as $conteudo){

        $tipo_equipamento = $conteudo['tipo_equipamento'];
        $id_tipo_plano_cliente = $conteudo['id_tipo_plano_cliente'];
        $porcentagem = $conteudo['porcentagem'];
        $tipo_transferencia = (int)$conteudo['tipo_transferencia'];

        if($conteudo['tipo_equipamento'] && $conteudo['id_tipo_plano_cliente'] && $conteudo['porcentagem']){
            $dadosVelocidade = array(
                'tipo_equipamento' => $tipo_equipamento,
                'id_tipo_plano_cliente' => $id_tipo_plano_cliente,
                'porcentagem' => $porcentagem,
                'tipo_transferencia' => $tipo_transferencia,
                'id_velocidade_minima_encaminhar_contrato' => $id
            );
            $insertID = DBCreate('', 'tb_velocidade_minima_encaminhar', $dadosVelocidade, true);
            registraLog('Alteração de nova velocidade.','a','tb_velocidade_minima_encaminhar',$insertID,"tipo_equipamento: $tipo_equipamento | id_tipo_plano_cliente: $id_tipo_plano_cliente | porcentagem: $porcentagem | tipo_transferencia: $tipo_transferencia");
            // QUADRO INFORMATIVO HISTORICO
            $dados_tipo_equipamento_historico = DBRead('','tb_tipo_equipamento', "WHERE id_tipo_equipamento = '".$tipo_equipamento."' LIMIT 1");
            $dados_tipo_plano_cliente_historico = DBRead('','tb_tipo_plano_cliente', "WHERE id_tipo_plano_cliente = '".$id_tipo_plano_cliente."' LIMIT 1");
            inserirHistorico($id_contrato_plano_pessoa, 2, "Alteração de velocidade mínima", "tipo equipamento: ".$dados_tipo_equipamento_historico[0]['descricao']." | tipo plano cliente: ".$dados_tipo_plano_cliente_historico[0]['descricao']." | porcentagem: $porcentagem | tipo transferencia: $tipo_transferencia", 18);
        }
    }

    registraLog('Alteração de nova velocidade mínima.','a','tb_velocidade_minima_encaminhar_contrato',$id,"id_contrato_plano_pessoa: $id_contrato_plano_pessoa| observacao: $observacao");
    $alert = ('Item alterado com sucesso!');
    $alert_type = 's';
    if($ativacao == 1){
        if($pular){
            header("location: /api/iframe?token=$request->token&view=parametro-form&alterar=$pular&ativacao=1&dado_posterior=$insertID&id_contrato=$id_contrato_plano_pessoa");
        }else if($voltar && $salvar != 1){
            header("location: /api/iframe?token=$request->token&view=radio-sinal-form&alterar=$voltar&ativacao=1&dado_posterior=$insertID&id_contrato=$id_contrato_plano_pessoa");
        }else{
            header("location: /api/iframe?token=$request->token&view=parametro-form&ativacao=1&dado_posterior=$insertID&id_contrato=$id_contrato_plano_pessoa");
        }
    }else{
        header("location: /api/iframe?token=$request->token&view=velocidade-minima-encaminhar-busca");
    }
    exit;
}

function excluir($id, $exclui_ativacao, $id_contrato){

    if($exclui_ativacao == 1){

        $dados = DBRead('', 'tb_parametros', "WHERE id_contrato_plano_pessoa = $id_contrato");

        $query = "DELETE FROM tb_velocidade_minima_encaminhar_contrato WHERE id_velocidade_minima_encaminhar_contrato = $id";
        $link = DBConnect('');
        $result = @mysqli_query($link, $query);
        DBClose($link);
        registraLog('Exclusão de horário.','e','tb_velocidade_minima_encaminhar_contrato', $id, '');
        
        // QUADRO INFORMATIVO HISTORICO
        inserirHistorico($id_contrato, 3, "Exclusão de velocidade mínima", "Excluiu dados", 18);

        if(!$result){
            $alert = ('Erro ao excluir item!');
            $alert_type = 'd';        }else{
            $alert = ('Item excluído com sucesso!');
    $alert_type = 's';        }

        if($dados){
            $dado_posterior = $dados[0]['id_parametros'];
            header("location: /api/iframe?token=$request->token&view=parametro-form&alterar=$dado_posterior&ativacao=1&id_contrato=$id_contrato");
        }else{
            header("location: /api/iframe?token=$request->token&view=parametro-form&ativacao=1&id_contrato=$id_contrato");
        }
        
    }else{
        $id_contrato = DBRead('', 'tb_velocidade_minima_encaminhar_contrato', "WHERE id_velocidade_minima_encaminhar_contrato = ".$id."");
        $id_contrato = $id_contrato[0]['id_contrato_plano_pessoa'];
        
        $query = "DELETE FROM tb_velocidade_minima_encaminhar_contrato WHERE id_velocidade_minima_encaminhar_contrato = $id";
        $link = DBConnect('');
        $result = @mysqli_query($link, $query);
        DBClose($link);
        registraLog('Exclusão de horário.','e','tb_velocidade_minima_encaminhar_contrato', $id, '');

        // QUADRO INFORMATIVO HISTORICO
        inserirHistorico($id_contrato, 3, "Exclusão de velocidade mínima", "Excluiu dados", 18);


        if(!$result){
            $alert = ('Erro ao excluir item!');
            $alert_type = 'd';        }else{
            $alert = ('Item excluído com sucesso!');
    $alert_type = 's';        }
        header("location: /api/iframe?token=$request->token&view=velocidade-minima-encaminhar-busca");
    }

    
    exit;
}

?>