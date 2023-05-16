<?php

require_once(__DIR__."/System.php");
require_once(__DIR__."/QuadroInformativoHistorico.php");


$id_tipo_plano_cliente = (!empty($_POST['id_tipo_plano_cliente'])) ? $_POST['id_tipo_plano_cliente'] : '';
$tipo_tempo = (!empty($_POST['tipo_tempo'])) ? $_POST['tipo_tempo'] : '';
$tempo = (!empty($_POST['tempo'])) ? $_POST['tempo'] : 0;
$observacao_prazo = (!empty($_POST['observacao_prazo'])) ? $_POST['observacao_prazo'] : '';
$observacao = (!empty($_POST['observacao'])) ? $_POST['observacao'] : '';
$id_contrato_plano_pessoa  = (!empty($_POST['id_contrato_plano_pessoa'])) ? $_POST['id_contrato_plano_pessoa'] : '';
$tipo = (!empty($_POST['tipo'])) ? $_POST['tipo'] : '';

$ativacao = (!empty($_POST['ativacao'])) ? $_POST['ativacao'] : 0;
$pular = (!empty($_POST['pular'])) ? $_POST['pular'] : '';
$voltar = (!empty($_POST['voltar'])) ? $_POST['voltar'] : '';
$salvar = (!empty($_POST['salvar'])) ? $_POST['salvar'] : 0;
$exclui_ativacao = (!empty($_GET['exclui-ativacao'])) ? $_GET['exclui-ativacao'] : 0;
$id_contrato = (!empty($_GET['id-contrato'])) ? $_GET['id-contrato'] : '';

$excluir_por_tipo = (!empty($_GET['excluir-por-tipo'])) ? $_GET['excluir-por-tipo'] : 0;

if(!empty($_POST['inserir'])){

    $cont = 0;
    $dados_prazo = array();
    foreach($id_tipo_plano_cliente as $conteudo){
        $dados_prazo[$cont]['id_tipo_plano_cliente'] = $conteudo;
        $cont++;
    }

    $cont = 0;
    foreach($tipo_tempo as $conteudo){
        $dados_prazo[$cont]['tipo_tempo'] = $conteudo;
        $cont++;
    }

    $cont = 0;
    foreach($tempo as $conteudo){
        $dados_prazo[$cont]['tempo'] = $conteudo;
        $cont++;
    }

    $cont = 0;
    foreach($observacao_prazo as $conteudo){
        $dados_prazo[$cont]['observacao_prazo'] = $conteudo;
        $cont++;
    }

    $verificacao_contrato_tecnico = DBRead('', 'tb_prazo_retorno_contrato', "WHERE id_contrato_plano_pessoa = '".$id_contrato_plano_pessoa."' AND tipo = 1");
    $verificacao_contrato_comercial = DBRead('', 'tb_prazo_retorno_contrato', "WHERE id_contrato_plano_pessoa = '".$id_contrato_plano_pessoa."' AND tipo = 2");
    $verificacao_contrato_financeiro = DBRead('', 'tb_prazo_retorno_contrato', "WHERE id_contrato_plano_pessoa = '".$id_contrato_plano_pessoa."' AND tipo = 3");

    if(!$verificacao_contrato_tecnico || !$verificacao_contrato_comercial || !$verificacao_contrato_financeiro){
        inserir($observacao, $id_contrato_plano_pessoa, $dados_prazo, $ativacao, $pular, $voltar, $salvar, $tipo);
    }else{
        $alert = ('Item já existe na base de dados!');
        $alert_type = 'w';        header("location: /api/iframe?token=$request->token&view=prazo-retorno-form");
        exit;
    }

}else if(!empty($_POST['alterar'])){
    $id = (int)$_POST['alterar'];

    $cont = 0;
    $dados_prazo = array();
    foreach($id_tipo_plano_cliente as $conteudo){
        $dados_prazo[$cont]['id_tipo_plano_cliente'] = $conteudo;
        $cont++;
    }

    $cont = 0;
    foreach($tipo_tempo as $conteudo){
        $dados_prazo[$cont]['tipo_tempo'] = $conteudo;
        $cont++;
    }

    $cont = 0;
    foreach($tempo as $conteudo){
        $dados_prazo[$cont]['tempo'] = $conteudo;
        $cont++;
    }

    $cont = 0;
    foreach($observacao_prazo as $conteudo){
        $dados_prazo[$cont]['observacao_prazo'] = $conteudo;
        $cont++;
    }

    //$verificacao_contrato = DBRead('', 'tb_prazo_retorno_contrato', "WHERE id_contrato_plano_pessoa = '".$id_contrato_plano_pessoa."' AND id_prazo_retorno_contrato != ".$id);

    //if(!$verificacao_contrato){
        alterar($id, $observacao, $id_contrato_plano_pessoa, $dados_prazo, $ativacao, $pular, $voltar, $salvar, $tipo);
    /*}else{
        $alert = ('Item já existe na base de dados!');
        $alert_type = 'w';        header("location: /api/iframe?token=$request->token&view=prazo-retorno-form&alterar=$id");
        exit;
    }*/
    
} else if(isset($_GET['excluir'])){

    $id = (int)$_GET['excluir'];
    excluir($id, $exclui_ativacao, $id_contrato, $excluir_por_tipo);
}else{
    header("location: ../adm.php");
    exit;
}

function inserir($observacao, $id_contrato_plano_pessoa, $dados_prazo, $ativacao, $pular, $voltar, $salvar, $tipo){

    $dados = array(
        'observacao' => $observacao,
        'id_contrato_plano_pessoa' => $id_contrato_plano_pessoa,
        'tipo' => $tipo
    );

    $insertID = DBCreate('', 'tb_prazo_retorno_contrato', $dados, true);
    registraLog('Inserção de novo prazo pessoa.','i','tb_prazo_retorno_contrato',$insertID,"observacao: $observacao | id_contrato_plano_pessoa: $id_contrato_plano_pessoa | tipo: $tipo");

    // QUADRO INFORMATIVO HISTORICO
    $tipo_historico = "Suporte Técnico";
    if($tipo == 2){
        $tipo_historico = "Suporte Comercial";
    }else if($tipo == 3){
        $tipo_historico = "Suporte Financeiro";
    }
    inserirHistorico($id_contrato_plano_pessoa, 1, "Inserção de novo prazo pessoa", "tipo: $tipo_historico | observação: $observacao", 12);

    foreach($dados_prazo as $conteudo){

        $id_tipo_plano_cliente = $conteudo['id_tipo_plano_cliente'];
        $tipo_tempo =  $conteudo['tipo_tempo'];
        $tempo = $conteudo['tempo'];
        $observacao_prazo = $conteudo['observacao_prazo'];

        if($conteudo['id_tipo_plano_cliente'] && $conteudo['tipo_tempo']){
            $dadosPrazo = array(
                'id_tipo_plano_cliente' => $id_tipo_plano_cliente,
                'tipo_tempo' => $tipo_tempo,
                'tempo' => $tempo,
                'observacao_prazo' => $observacao_prazo,
                'id_prazo_retorno_contrato' => $insertID
            );
            
            $insertPrazo = DBCreate('', 'tb_prazo_retorno', $dadosPrazo, true);
            registraLog('Inserção de novo prazo.','i','tb_prazo_retorno', $insertPrazo, "id_tipo_plano_cliente: $id_tipo_plano_cliente | tipo_tempo: $tipo_tempo | tempo: $tempo | observacao_prazo: $observacao_prazo | id_prazo_retorno_contrato: $insertID");

            // QUADRO INFORMATIVO HISTORICO
            $dados_tipo_plano_cliente_historico = DBRead('','tb_tipo_plano_cliente', "WHERE id_tipo_plano_cliente = '".$id_tipo_plano_cliente."' LIMIT 1");
            $tipo_tempo_historico = "Úteis";
            if($tipo_tempo == 2){
                $tipo_tempo_historico = "Corridas";
            }

            $dados_tipo_plano_cliente_historico = DBRead('','tb_tipo_plano_cliente', "WHERE id_tipo_plano_cliente = '".$id_tipo_plano_cliente."' LIMIT 1");
            inserirHistorico($id_contrato_plano_pessoa, 1, "Inserção de novo prazo", "tipo plano cliente: ".$dados_tipo_plano_cliente_historico[0]['descricao']." | tipo tempo: $tipo_tempo_historico | tempo: $tempo | observacao prazo: $observacao_prazo", 12);

        }
    }
    
    $alert = ('Item inserido com sucesso!');
    $alert_type = 's';
    if($ativacao == 1){
        if($pular){

            if($tipo == 1){

                /*$tela = DBRead('', 'tb_horario_contrato', "WHERE id_contrato_plano_pessoa = $id_contrato_plano_pessoa AND tipo = 7");
                $id_tela = $tela[0]['id_horario_contrato'];*/
                $tela = DBRead('', 'tb_prazo_retorno_contrato', "WHERE id_contrato_plano_pessoa = $id_contrato_plano_pessoa AND tipo = 2");
                $id_tela = $tela[0]['id_prazo_retorno_contrato'];

                if($tela){
                    header("location: /api/iframe?token=$request->token&view=prazo-retorno-form&alterar=$id_tela&ativacao=1&dado_posterior=$insertID&id_contrato=$id_contrato_plano_pessoa&tela=2");
                }else{
                    header("location: /api/iframe?token=$request->token&view=prazo-retorno-form&ativacao=1&dado_posterior=$insertID&id_contrato=$id_contrato_plano_pessoa&tela=2");
                }
                //header("location: /api/iframe?token=$request->token&view=configuracao-roteadores-form&alterar=$pular&ativacao=1&dado_posterior=$insertID&id_contrato=$id_contrato_plano_pessoa");
            }else if($tipo == 2){
                
                $tela = DBRead('', 'tb_prazo_retorno_contrato', "WHERE id_contrato_plano_pessoa = $id_contrato_plano_pessoa AND tipo = 3");
                $id_tela = $tela[0]['id_prazo_retorno_contrato'];

                if($tela){
                    header("location: /api/iframe?token=$request->token&view=prazo-retorno-form&alterar=$id_tela&ativacao=1&dado_posterior=$insertID&id_contrato=$id_contrato_plano_pessoa&tela=3");
                }else{
                    header("location: /api/iframe?token=$request->token&view=prazo-retorno-form&ativacao=1&dado_posterior=$insertID&id_contrato=$id_contrato_plano_pessoa&tela=3");
                }
            }else{

                header("location: /api/iframe?token=$request->token&view=configuracao-roteadores-form&alterar=$pular&ativacao=1&dado_posterior=$insertID&id_contrato=$id_contrato_plano_pessoa");
            }

        }else if($voltar && $salvar != 1){
            header("location: /api/iframe?token=$request->token&view=horario-form&alterar=$voltar&ativacao=1&dado_posterior=$insertID&id_contrato=$id_contrato_plano_pessoa&tela=6");
        }else{

            if($tipo == 1){
                header("location: /api/iframe?token=$request->token&view=prazo-retorno-form&ativacao=1&dado_posterior=$insertID&id_contrato=$id_contrato_plano_pessoa&tela=2");
            }else if($tipo == 2){
                header("location: /api/iframe?token=$request->token&view=prazo-retorno-form&ativacao=1&dado_posterior=$insertID&id_contrato=$id_contrato_plano_pessoa&tela=3");
            }else{
                header("location: /api/iframe?token=$request->token&view=configuracao-roteadores-form&ativacao=1&dado_posterior=$insertID&id_contrato=$id_contrato_plano_pessoa");
            }
        }
    }else{
        header("location: /api/iframe?token=$request->token&view=prazo-retorno-busca");
    }
    exit;
}

function alterar($id, $observacao, $id_contrato_plano_pessoa, $dados_prazo, $ativacao, $pular, $voltar, $salvar, $tipo){

    $dados = array(
        'observacao' => $observacao,
        'id_contrato_plano_pessoa' => $id_contrato_plano_pessoa,
        'tipo' => $tipo
    );
    DBUpdate('', 'tb_prazo_retorno_contrato', $dados, "id_prazo_retorno_contrato = $id");
    
    // QUADRO INFORMATIVO HISTORICO
    $tipo_historico = "Suporte Técnico";
    if($tipo == 2){
        $tipo_historico = "Comercial/financeiro";
    }else if($tipo == 3){
        $tipo_historico = "Suporte Financeiro";
    }
    inserirHistorico($id_contrato_plano_pessoa, 2, "Alteração de prazo pessoa", "observação: $observacao | tipo: $tipo_historico", 12);

    DBDelete('', 'tb_prazo_retorno', "id_prazo_retorno_contrato = '$id'");
    foreach($dados_prazo as $conteudo){

        $id_tipo_plano_cliente = $conteudo['id_tipo_plano_cliente'];
        $tipo_tempo =  $conteudo['tipo_tempo'];
        $tempo = $conteudo['tempo'];
        $observacao_prazo = $conteudo['observacao_prazo'];

        if($conteudo['id_tipo_plano_cliente'] && $conteudo['tipo_tempo']){
            $dadosPrazo = array(
                'id_tipo_plano_cliente' => $id_tipo_plano_cliente,
                'tipo_tempo' => $tipo_tempo,
                'tempo' => $tempo,
                'observacao_prazo' => $observacao_prazo,
                'id_prazo_retorno_contrato' => $id
            );
            $insertID = DBCreate('', 'tb_prazo_retorno', $dadosPrazo);
            registraLog('Alteração de prazo.','a','tb_prazo_retorno',$insertID,"id_tipo_plano_cliente: $id_tipo_plano_cliente | tipo_tempo: $tipo_tempo | tempo: $tempo | observacao_prazo: $observacao_prazo | id_prazo_retorno_contrato: $id");

            // QUADRO INFORMATIVO HISTORICO
            $dados_tipo_plano_cliente_historico = DBRead('','tb_tipo_plano_cliente', "WHERE id_tipo_plano_cliente = '".$id_tipo_plano_cliente."' LIMIT 1");
            $tipo_tempo_historico = "Úteis";
            if($tipo_tempo == 2){
                $tipo_tempo_historico = "Corridas";
            }
            inserirHistorico($id_contrato_plano_pessoa, 2, "Alteração de prazo", "tipo plano cliente: ".$dados_tipo_plano_cliente_historico[0]['descricao']." | tipo tempo: $tipo_tempo_historico | tempo: $tempo | observacao prazo: $observacao_prazo", 12);
        }
    }

    registraLog('Alteração de prazo pessoa.','a','tb_prazo_retorno_contrato',$id,"observacao: $observacao | id_contrato_plano_pessoa: $id_contrato_plano_pessoa | tipo: $tipo");
    $alert = ('Item alterado com sucesso!');
    $alert_type = 's';    if($ativacao == 1){
        if($pular){

            if($tipo == 1){

                $tela = DBRead('', 'tb_prazo_retorno_contrato', "WHERE id_contrato_plano_pessoa = $id_contrato_plano_pessoa AND tipo = 2");
                $id_tela = $tela[0]['id_prazo_retorno_contrato'];

                if($tela){
                    header("location: /api/iframe?token=$request->token&view=prazo-retorno-form&alterar=$id_tela&ativacao=1&dado_posterior=$insertID&id_contrato=$id_contrato_plano_pessoa&tela=2");
                }else{
                    header("location: /api/iframe?token=$request->token&view=prazo-retorno-form&ativacao=1&dado_posterior=$insertID&id_contrato=$id_contrato_plano_pessoa&tela=2");
                }
            }else if($tipo == 2){
                
                $tela = DBRead('', 'tb_prazo_retorno_contrato', "WHERE id_contrato_plano_pessoa = $id_contrato_plano_pessoa AND tipo = 3");
                $id_tela = $tela[0]['id_prazo_retorno_contrato'];

                if($tela){
                    header("location: /api/iframe?token=$request->token&view=prazo-retorno-form&alterar=$id_tela&ativacao=1&dado_posterior=$insertID&id_contrato=$id_contrato_plano_pessoa&tela=3");
                }else{
                    header("location: /api/iframe?token=$request->token&view=prazo-retorno-form&ativacao=1&dado_posterior=$insertID&id_contrato=$id_contrato_plano_pessoa&tela=3");
                }
            }else{
                header("location: /api/iframe?token=$request->token&view=configuracao-roteadores-form&alterar=$pular&ativacao=1&dado_posterior=$insertID&id_contrato=$id_contrato_plano_pessoa");
            }
        }
    }else{
        header("location: /api/iframe?token=$request->token&view=prazo-retorno-busca");
    }
    exit;
}

function excluir($id, $exclui_ativacao, $id_contrato, $excluir_por_tipo){

    if($exclui_ativacao == 1){

        $query = "DELETE FROM tb_prazo_retorno_contrato WHERE id_prazo_retorno_contrato = '$id' AND tipo = '$excluir_por_tipo'";
        $link = DBConnect('');
        $result = @mysqli_query($link, $query);
        DBClose($link);
        registraLog('Exclusão de prazo.','e','tb_prazo_retorno_contrato', $id, '');
        
        // QUADRO INFORMATIVO HISTORICO
        inserirHistorico($id_contrato, 3, "Exclusão de prazo", "Excluiu dados", 12);

        if(!$result){
            $alert = ('Erro ao excluir item!');
            $alert_type = 'd';        }else{
            $alert = ('Item excluído com sucesso!');
    $alert_type = 's';        }

        if($excluir_por_tipo == 1){
            $tela = DBRead('', 'tb_prazo_retorno_contrato', "WHERE tipo=2 AND id_contrato_plano_pessoa = '$id_contrato'");
            $id_tela = $tela[0]['id_prazo_retorno_contrato'];
            if($tela){
                header("location: /api/iframe?token=$request->token&view=prazo-retorno-form&alterar=$id_tela&ativacao=1&dado_posterior=$id&id_contrato=$id_contrato&tela=2");
            }else{
                header("location: /api/iframe?token=$request->token&view=prazo-retorno-form&ativacao=1&dado_posterior=$id&id_contrato=$id_contrato&tela=2");
            }
        }else if($excluir_por_tipo == 2){
            $tela = DBRead('', 'tb_horario_contrato', "WHERE tipo=3 AND id_contrato_plano_pessoa = '$id_contrato'");
            $id_tela = $tela[0]['id_horario_contrato'];
            if($tela){
                header("location: /api/iframe?token=$request->token&view=prazo-retorno-form&alterar=$id_tela&ativacao=1&dado_posterior=$id&id_contrato=$id_contrato&tela=3");
            }else{
                header("location: /api/iframe?token=$request->token&view=prazo-retorno-form&ativacao=1&dado_posterior=$id&id_contrato=$id_contrato&tela=3");
            }
        }else{
            //$tela = DBRead('', 'tb_horario_contrato', "WHERE tipo=3 AND id_contrato_plano_pessoa = '$id_contrato'");
            $tela = DBRead('', 'tb_configuracao_roteadores_contrato', "WHERE id_contrato_plano_pessoa = $id_contrato");
            //$id_tela = $tela[0]['id_horario_contrato'];
            if($tela){
                $dado_posterior = $tela[0]['id_configuracao_roteadores_contrato'];
                header("location: /api/iframe?token=$request->token&view=configuracao-roteadores-form&alterar=$dado_posterior&ativacao=1&id_contrato=$id_contrato");
            }else{
                header("location: /api/iframe?token=$request->token&view=configuracao-roteadores-form&ativacao=1&id_contrato=$id_contrato");
            }
        }

    }else{
        $id_contrato = DBRead('', 'tb_prazo_retorno_contrato', "WHERE id_prazo_retorno_contrato = ".$id."");
        $id_contrato = $id_contrato[0]['id_contrato_plano_pessoa'];
        
        $query = "DELETE FROM tb_prazo_retorno_contrato WHERE id_prazo_retorno_contrato = $id";
        $link = DBConnect('');
        $result = @mysqli_query($link, $query);
        DBClose($link);
        registraLog('Exclusão de prazo.','e','tb_prazo_retorno_contrato',$id,'');

        // QUADRO INFORMATIVO HISTORICO
        inserirHistorico($id_contrato, 3, "Exclusão de prazo", "Excluiu dados", 12);

        if(!$result){
            $alert = ('Erro ao excluir item!');
            $alert_type = 'd';        }else{
            $alert = ('Item excluído com sucesso!');
    $alert_type = 's';        }
        header("location: /api/iframe?token=$request->token&view=prazo-retorno-busca");
    }

    

    exit;
}

?>