<?php
require_once(__DIR__."/System.php");
require_once(__DIR__."/QuadroInformativoHistorico.php");


$tipo = (!empty($_POST['tipo'])) ? $_POST['tipo'] : '';
$observacao = (!empty($_POST['observacao'])) ? $_POST['observacao'] : '';
$dia = (!empty($_POST['dia'])) ? $_POST['dia'] : '';
$id_contrato_plano_pessoa  = (!empty($_POST['id_contrato_plano_pessoa'])) ? $_POST['id_contrato_plano_pessoa'] : '';
$hora_inicio = (!empty($_POST['hora_inicio'])) ? $_POST['hora_inicio'] : '';
$hora_fim = (!empty($_POST['hora_fim'])) ? $_POST['hora_fim'] : '';

$ativacao = (!empty($_POST['ativacao'])) ? $_POST['ativacao'] : 0;
$pular = (!empty($_POST['pular'])) ? $_POST['pular'] : '';
$voltar = (!empty($_POST['voltar'])) ? $_POST['voltar'] : '';
$salvar = (!empty($_POST['salvar'])) ? $_POST['salvar'] : 0;
$exclui_ativacao = (!empty($_GET['exclui-ativacao'])) ? $_GET['exclui-ativacao'] : 0;
$id_contrato = (!empty($_GET['id-contrato'])) ? $_GET['id-contrato'] : '';
$excluir_por_tipo = (!empty($_GET['excluir-por-tipo'])) ? $_GET['excluir-por-tipo'] : 0;

if (!empty($_POST['inserir'])) {

    $cont = 0;
    $dados_horario = array();
    foreach($hora_inicio as $conteudo){
        $dados_horario[$cont]['hora_inicio'] = $conteudo;
        $cont++;
    }

    $cont = 0;
    foreach($hora_fim as $conteudo){
        $dados_horario[$cont]['hora_fim'] = $conteudo;
        $cont++;
    }

    $cont = 0;
    foreach($dia as $conteudo){
        $dados_horario[$cont]['dia'] = $conteudo;
        $cont++;
    }

    $tipo_existe = DBRead('', 'tb_horario_contrato', "WHERE id_contrato_plano_pessoa = ".$id_contrato_plano_pessoa." AND tipo = ".addslashes($tipo)."");

    if(!$tipo_existe){
        inserir($id_contrato_plano_pessoa, $tipo, $observacao, $dados_horario, $ativacao, $pular, $voltar, $salvar);
    }else{
        $alert = ('Item já existe na base de dados!');
        $alert_type = 'w';        header("location: /api/iframe?token=$request->token&view=horario-form");
        exit;
    }

} else if (!empty($_POST['alterar'])) {
    $id = (int)$_POST['alterar'];

    $cont = 0;
    $dados_horario = array();
    foreach($hora_inicio as $conteudo){
        $dados_horario[$cont]['hora_inicio'] = $conteudo;
        $cont++;
    }

    $cont = 0;
    foreach($hora_fim as $conteudo) {
        $dados_horario[$cont]['hora_fim'] = $conteudo;
        $cont++;
    }

    $cont = 0;
    foreach($dia as $conteudo) {
        $dados_horario[$cont]['dia'] = $conteudo;
        $cont++;
    }

    $tipo_existe = DBRead('', 'tb_horario_contrato', "WHERE id_contrato_plano_pessoa = ".$id_contrato_plano_pessoa." AND tipo = ".addslashes($tipo)." AND id_horario_contrato != '$id'");

    if(!$tipo_existe){
        alterar($id, $id_contrato_plano_pessoa, $tipo, $observacao, $dados_horario, $ativacao, $pular, $voltar, $salvar);
    }else{
        $alert = ('Item já existe na base de dados!');
        $alert_type = 'w';        header("location: /api/iframe?token=$request->token&view=horario-form&alterar=$id");
        exit;
    }
    
} else if (isset($_GET['excluir'])) {

    $id = (int)$_GET['excluir'];
    excluir($id, $exclui_ativacao, $id_contrato, $excluir_por_tipo);
} else {
    header("location: ../adm.php");
    exit;
}

function inserir($id_contrato_plano_pessoa, $tipo, $observacao, $dados_horario, $ativacao, $pular, $voltar, $salvar){

        $dados = array(
            'id_contrato_plano_pessoa' => $id_contrato_plano_pessoa,
            'tipo' => $tipo,
            'observacao' => $observacao
        );

        $insertID = DBCreate('', 'tb_horario_contrato', $dados, true);
        registraLog('Inserção de novo horário.','i','tb_horario_contrato',$insertID,"id_contrato_plano_pessoa: $id_contrato_plano_pessoa | tipo: $tipo | observacao: $observacao");

        $array_tipos = array(
            1 => 'Horários que a empresa está aberta.',
            3 => 'Horários de atendimento empresarial dos técnicos de campo.',
            5 => 'Horários de atendimento doméstico dos técnicos de campo.',
            6 => 'Horários de atendimento dedicado dos técnicos de campo.',
            7 => 'Horários gerais de atendimento dos técnicos de campo.',
            8 => 'Horários que mandam as chamadas para a Belluno.',
            9 => 'Horários de atendimento via texto pela Belluno.', 
            10 => 'Horários de retorno telefônico do provedor.'
        );

        // QUADRO INFORMATIVO HISTORICO
        inserirHistorico($id_contrato_plano_pessoa, 1, "Inserção de novo tipo de horário", "tipo: ".$array_tipos[$tipo]." ", 4);

        foreach($dados_horario as $conteudo){
            if($conteudo['dia'] && $conteudo['hora_inicio'] && $conteudo['hora_fim']){

                $hora_inicio = $conteudo['hora_inicio'];
                $hora_fim = $conteudo['hora_fim'];
                $dia = $conteudo['dia'];

                $dadosHorario = array(
                    'hora_inicio' => $hora_inicio,
                    'hora_fim' => $hora_fim,
                    'dia' => $dia,
                    'id_horario_contrato' => $insertID
                );
                
                $insertHorarios = DBCreate('', 'tb_horario', $dadosHorario);
                registraLog('Inserção de novo horário.','i','tb_horario',$insertHorarios,"hora_inicio: $hora_inicio | hora_fim: $hora_fim | id_horario_contrato: $insertID | dia: $dia");

                $array_dias = array(
                    1 => 'Seg. a Dom.',
                    2 => 'Seg. a Sab.',
                    3 => 'Seg. a Sex.',
                    4 => 'Dom. e Feriados',
                    5 => 'Feriados', 
                    6 => 'Domingo',
                    7 => 'Segunda',
                    8 => 'Terça',
                    9 => 'Quarta',
                    10 => 'Quinta',
                    11 => 'Sexta',
                    12 => 'Sábado',
                    13 => 'Seg. a Qui.'
                );

                // QUADRO INFORMATIVO HISTORICO
                inserirHistorico($id_contrato_plano_pessoa, 1, "Inserção de novo horário", "hora inicio: $hora_inicio | hora fim: $hora_fim | dia: ".$array_dias[$dia]." ", 4);
            }
        }
        $alert = ('Item inserido com sucesso!');
        $alert_type = 's';        
        if($ativacao == 1){
            if($pular){

                if($tipo == 1){

                    $tela = DBRead('', 'tb_horario_contrato', "WHERE id_contrato_plano_pessoa = $id_contrato_plano_pessoa AND tipo = 7");

                    $id_tela = $tela[0]['id_horario_contrato'];

                    if($tela){
                        header("location: /api/iframe?token=$request->token&view=horario-form&alterar=$id_tela&ativacao=1&dado_posterior=$insertID&id_contrato=$id_contrato_plano_pessoa&tela=7");
                    }else{
                        header("location: /api/iframe?token=$request->token&view=horario-form&ativacao=1&dado_posterior=$insertID&id_contrato=$id_contrato_plano_pessoa&tela=7");
                    }

                }else if($tipo == 7){

                    $tela = DBRead('', 'tb_horario_contrato', "WHERE id_contrato_plano_pessoa = $id_contrato_plano_pessoa AND tipo = 3");

                    $id_tela = $tela[0]['id_horario_contrato'];

                    if($tela){
                        header("location: /api/iframe?token=$request->token&view=horario-form&alterar=$id_tela&ativacao=1&dado_posterior=$insertID&id_contrato=$id_contrato_plano_pessoa&tela=3");
                    }else{
                        header("location: /api/iframe?token=$request->token&view=horario-form&ativacao=1&dado_posterior=$insertID&id_contrato=$id_contrato_plano_pessoa&tela=3");
                    }
                }else if($tipo == 3){

                    $tela = DBRead('', 'tb_horario_contrato', "WHERE id_contrato_plano_pessoa = $id_contrato_plano_pessoa AND tipo = 5");

                    $id_tela = $tela[0]['id_horario_contrato'];

                    if($tela){
                        header("location: /api/iframe?token=$request->token&view=horario-form&alterar=$id_tela&ativacao=1&dado_posterior=$insertID&id_contrato=$id_contrato_plano_pessoa&tela=5");
                    }else{
                        header("location: /api/iframe?token=$request->token&view=horario-form&ativacao=1&dado_posterior=$insertID&id_contrato=$id_contrato_plano_pessoa&tela=5");
                    }
                }else if($tipo == 5){

                    $tela = DBRead('', 'tb_horario_contrato', "WHERE id_contrato_plano_pessoa = $id_contrato_plano_pessoa AND tipo = 6");

                    $id_tela = $tela[0]['id_horario_contrato'];

                    if($tela){
                        header("location: /api/iframe?token=$request->token&view=horario-form&alterar=$id_tela&ativacao=1&dado_posterior=$insertID&id_contrato=$id_contrato_plano_pessoa&tela=6");
                    }else{
                        header("location: /api/iframe?token=$request->token&view=horario-form&ativacao=1&dado_posterior=$insertID&id_contrato=$id_contrato_plano_pessoa&tela=6");
                    }
                }else if($tipo == 6){

                    $tela = DBRead('', 'tb_horario_contrato', "WHERE id_contrato_plano_pessoa = $id_contrato_plano_pessoa AND tipo = 8");

                    $id_tela = $tela[0]['id_horario_contrato'];

                    if($tela){
                        header("location: /api/iframe?token=$request->token&view=horario-form&alterar=$id_tela&ativacao=1&dado_posterior=$insertID&id_contrato=$id_contrato_plano_pessoa&tela=8");
                    }else{
                        header("location: /api/iframe?token=$request->token&view=horario-form&ativacao=1&dado_posterior=$insertID&id_contrato=$id_contrato_plano_pessoa&tela=8");
                    }
                }else if($tipo == 8){

                    $tela = DBRead('', 'tb_horario_contrato', "WHERE id_contrato_plano_pessoa = $id_contrato_plano_pessoa AND tipo = 9");

                    $id_tela = $tela[0]['id_horario_contrato'];

                    if($tela){
                        header("location: /api/iframe?token=$request->token&view=horario-form&alterar=$id_tela&ativacao=1&dado_posterior=$insertID&id_contrato=$id_contrato_plano_pessoa&tela=9");
                    }else{
                        header("location: /api/iframe?token=$request->token&view=horario-form&ativacao=1&dado_posterior=$insertID&id_contrato=$id_contrato_plano_pessoa&tela=9");
                    }
                }else{

                    $tela = DBRead('', 'tb_prazo_retorno_contrato', "WHERE id_contrato_plano_pessoa = $id_contrato_plano_pessoa");
                    $id_tela = $tela[0]['id_prazo_retorno_contrato'];
                    if($tela){
                        header("location: /api/iframe?token=$request->token&view=prazo-retorno-form&alterar=$id_tela&ativacao=1&dado_posterior=$insertID&id_contrato=$id_contrato_plano_pessoa&tela=1");
                    }else{
                        header("location: /api/iframe?token=$request->token&view=prazo-retorno-form&ativacao=1&dado_posterior=$insertID&id_contrato=$id_contrato_plano_pessoa&tela=1");
                    }
                }

            }else if($voltar && $salvar != 1){
                header("location: /api/iframe?token=$request->token&view=plantonista-form&alterar=$voltar&ativacao=1&dado_posterior=$insertID&id_contrato=$id_contrato_plano_pessoa");
            }else{

                if($tipo == 1){
                    header("location: /api/iframe?token=$request->token&view=horario-form&ativacao=1&dado_posterior=$insertID&id_contrato=$id_contrato_plano_pessoa&tela=7");
                }else if($tipo == 7){
                    header("location: /api/iframe?token=$request->token&view=horario-form&ativacao=1&dado_posterior=$insertID&id_contrato=$id_contrato_plano_pessoa&tela=3");
                }else if($tipo == 3){
                    header("location: /api/iframe?token=$request->token&view=horario-form&ativacao=1&dado_posterior=$insertID&id_contrato=$id_contrato_plano_pessoa&tela=5");
                }else if($tipo == 5){
                    header("location: /api/iframe?token=$request->token&view=horario-form&ativacao=1&dado_posterior=$insertID&id_contrato=$id_contrato_plano_pessoa&tela=6");
                }else if($tipo == 6){
                    header("location: /api/iframe?token=$request->token&view=horario-form&ativacao=1&dado_posterior=$insertID&id_contrato=$id_contrato_plano_pessoa&tela=8");
                }else if($tipo == 8){
                    header("location: /api/iframe?token=$request->token&view=horario-form&ativacao=1&dado_posterior=$insertID&id_contrato=$id_contrato_plano_pessoa&tela=9");
                }else{

                    $tela = DBRead('', 'tb_prazo_retorno_contrato', "WHERE id_contrato_plano_pessoa = $id_contrato_plano_pessoa");
                    $id_tela = $tela[0]['id_prazo_retorno_contrato'];
                    if($tela){
                        header("location: /api/iframe?token=$request->token&view=prazo-retorno-form&alterar=$id_tela&ativacao=1&dado_posterior=$insertID&id_contrato=$id_contrato_plano_pessoa");
                    }else{
                        header("location: /api/iframe?token=$request->token&view=prazo-retorno-form&ativacao=1&dado_posterior=$insertID&id_contrato=$id_contrato_plano_pessoa");
                    }
                }
            }
        }else{
            header("location: /api/iframe?token=$request->token&view=horario-busca");
        }
        exit;
}

function alterar($id, $id_contrato_plano_pessoa, $tipo, $observacao, $dados_horario, $ativacao, $pular, $voltar, $salvar){

    $dados = array(
        'id_contrato_plano_pessoa' => $id_contrato_plano_pessoa,
        'tipo' => $tipo,
        'observacao' => $observacao
    );

    DBUpdate('', 'tb_horario_contrato', $dados, "id_horario_contrato = $id");
    registraLog('Alteração de horário contrato.','a','tb_horario_contrato',$id,"id_contrato_plano_pessoa: $id_contrato_plano_pessoa | tipo: $tipo | observacao: $observacao");

    $array_tipos = array(
        1 => 'Horários que a empresa está aberta.',
        3 => 'Horários de atendimento empresarial dos técnicos de campo.',
        5 => 'Horários de atendimento doméstico dos técnicos de campo.',
        6 => 'Horários de atendimento dedicado dos técnicos de campo.',
        7 => 'Horários gerais de atendimento dos técnicos de campo.',
        8 => 'Horários que mandam as chamadas para a Belluno.',
        9 => 'Horários de atendimento via texto pela Belluno.', 
        10 => 'Horários de retorno telefônico do provedor.'
    );

    // QUADRO INFORMATIVO HISTORICO
    inserirHistorico($id_contrato_plano_pessoa, 2, "Alterção de tipo de horário", "tipo: ".$array_tipos[$tipo]." ", 4);

    DBDelete('', 'tb_horario', "id_horario_contrato = '$id'");

    foreach($dados_horario as $conteudo){
        if($conteudo['dia'] && $conteudo['hora_inicio'] && $conteudo['hora_fim']){

            $hora_inicio = $conteudo['hora_inicio'];
            $hora_fim = $conteudo['hora_fim'];
            $dia = $conteudo['dia'];

            $dadosHorario = array(
                'hora_inicio' => $hora_inicio,
                'hora_fim' => $hora_fim,
                'dia' => $dia,
                'id_horario_contrato' => $id
            );
            $insertID = DBCreate('', 'tb_horario', $dadosHorario);
            registraLog('Alteração de horário.','a','tb_horario',$insertID,"hora_inicio: $hora_inicio | hora_fim: $hora_fim | id_horario_contrato: $id | dia: $dia");

            $array_dias = array(
                1 => 'Seg. a Dom.',
                2 => 'Seg. a Sab.',
                3 => 'Seg. a Sex.',
                4 => 'Dom. e Feriados',
                5 => 'Feriados', 
                6 => 'Domingo',
                7 => 'Segunda',
                8 => 'Terça',
                9 => 'Quarta',
                10 => 'Quinta',
                11 => 'Sexta',
                12 => 'Sábado',
                13 => 'Seg. a Qui.'
            );

            // QUADRO INFORMATIVO HISTORICO
            inserirHistorico($id_contrato_plano_pessoa, 2, "Alteração de horário", "hora inicio: $hora_inicio | hora fim: $hora_fim | dia: ".$array_dias[$dia]." ", 4);
        }
    }

    registraLog('Alteração de horário.','a','tb_horario_contrato',$id,"id_contrato_plano_pessoa: $id_contrato_plano_pessoa| tipo: $tipo");
    $alert = ('Item alterado com sucesso!');
    $alert_type = 's';    if($ativacao == 1){
        if($pular){

            if($tipo == 1){

                $tela = DBRead('', 'tb_horario_contrato', "WHERE id_contrato_plano_pessoa = '$id_contrato_plano_pessoa' AND tipo = 7");
                $id_tela = $tela[0]['id_horario_contrato'];
                header("location: /api/iframe?token=$request->token&view=horario-form&alterar=$id_tela&ativacao=1&dado_posterior=$insertID&id_contrato=$id_contrato_plano_pessoa&tela=7");

            }else if($tipo == 7){

                $tela = DBRead('', 'tb_horario_contrato', "WHERE id_contrato_plano_pessoa = $id_contrato_plano_pessoa AND tipo = 3");
                $id_tela = $tela[0]['id_horario_contrato'];
                header("location: /api/iframe?token=$request->token&view=horario-form&alterar=$id_tela&ativacao=1&dado_posterior=$insertID&id_contrato=$id_contrato_plano_pessoa&tela=3");

            }else if($tipo == 3){

                $tela = DBRead('', 'tb_horario_contrato', "WHERE id_contrato_plano_pessoa = $id_contrato_plano_pessoa AND tipo = 5");
                $id_tela = $tela[0]['id_horario_contrato'];
                header("location: /api/iframe?token=$request->token&view=horario-form&alterar=$id_tela&ativacao=1&dado_posterior=$insertID&id_contrato=$id_contrato_plano_pessoa&tela=5");

            }else if($tipo == 5){

                $tela = DBRead('', 'tb_horario_contrato', "WHERE id_contrato_plano_pessoa = $id_contrato_plano_pessoa AND tipo = 6");
                $id_tela = $tela[0]['id_horario_contrato'];
                header("location: /api/iframe?token=$request->token&view=horario-form&alterar=$id_tela&ativacao=1&dado_posterior=$insertID&id_contrato=$id_contrato_plano_pessoa&tela=6");

            }else if($tipo == 6){

                $tela = DBRead('', 'tb_horario_contrato', "WHERE id_contrato_plano_pessoa = $id_contrato_plano_pessoa AND tipo = 8");
                $id_tela = $tela[0]['id_horario_contrato'];
                header("location: /api/iframe?token=$request->token&view=horario-form&alterar=$id_tela&ativacao=1&dado_posterior=$insertID&id_contrato=$id_contrato_plano_pessoa&tela=8");

            }else{

                header("location: /api/iframe?token=$request->token&view=prazo-retorno-form&alterar=$pular&ativacao=1&dado_posterior=$id&id_contrato=$id_contrato_plano_pessoa&tela=1");

            }

        }else if($voltar && $salvar != 1){
            header("location: /api/iframe?token=$request->token&view=plantonista-form&alterar=$voltar&ativacao=1&dado_posterior=$id&id_contrato=$id_contrato_plano_pessoa");
        }else{

            if($tipo == 1){
                $tela = DBRead('', 'tb_horario_contrato', "WHERE id_contrato_plano_pessoa = $id_contrato_plano_pessoa AND tipo = 7");
                $id_tela = $tela[0]['id_horario_contrato'];
                if($tela){
                    header("location: /api/iframe?token=$request->token&view=horario-form&alterar=$id_tela&ativacao=1&dado_posterior=$insertID&id_contrato=$id_contrato_plano_pessoa&tela=7");
                }else{
                    header("location: /api/iframe?token=$request->token&view=horario-form&ativacao=1&dado_posterior=$insertID&id_contrato=$id_contrato_plano_pessoa&tela=7");
                }
            }else if($tipo == 7){
                $tela = DBRead('', 'tb_horario_contrato', "WHERE id_contrato_plano_pessoa = $id_contrato_plano_pessoa AND tipo = 3");
                $id_tela = $tela[0]['id_horario_contrato'];
                if($tela){
                    header("location: /api/iframe?token=$request->token&view=horario-form&alterar=$id_tela&ativacao=1&dado_posterior=$insertID&id_contrato=$id_contrato_plano_pessoa&tela=3");
                }else{
                    header("location: /api/iframe?token=$request->token&view=horario-form&ativacao=1&dado_posterior=$insertID&id_contrato=$id_contrato_plano_pessoa&tela=3");
                }
                
            }else if($tipo == 3){
                $tela = DBRead('', 'tb_horario_contrato', "WHERE id_contrato_plano_pessoa = $id_contrato_plano_pessoa AND tipo = 5");
                $id_tela = $tela[0]['id_horario_contrato'];
                if($tela){
                    header("location: /api/iframe?token=$request->token&view=horario-form&alterar=$id_tela&ativacao=1&dado_posterior=$insertID&id_contrato=$id_contrato_plano_pessoa&tela=5");
                }else{
                    header("location: /api/iframe?token=$request->token&view=horario-form&ativacao=1&dado_posterior=$insertID&id_contrato=$id_contrato_plano_pessoa&tela=5");
                }
                
            }else if($tipo == 5){
                $tela = DBRead('', 'tb_horario_contrato', "WHERE id_contrato_plano_pessoa = $id_contrato_plano_pessoa AND tipo = 6");
                $id_tela = $tela[0]['id_horario_contrato'];
                if($tela){
                    header("location: /api/iframe?token=$request->token&view=horario-form&alterar=$id_tela&ativacao=1&dado_posterior=$insertID&id_contrato=$id_contrato_plano_pessoa&tela=6");
                }else{
                    header("location: /api/iframe?token=$request->token&view=horario-form&ativacao=1&dado_posterior=$insertID&id_contrato=$id_contrato_plano_pessoa&tela=6");
                }
                
            }else if($tipo == 6){
                $tela = DBRead('', 'tb_horario_contrato', "WHERE id_contrato_plano_pessoa = $id_contrato_plano_pessoa AND tipo = 8");
                $id_tela = $tela[0]['id_horario_contrato'];
                if($tela){
                    header("location: /api/iframe?token=$request->token&view=horario-form&alterar=$id_tela&ativacao=1&dado_posterior=$insertID&id_contrato=$id_contrato_plano_pessoa&tela=8");
                }else{
                    header("location: /api/iframe?token=$request->token&view=horario-form&ativacao=1&dado_posterior=$insertID&id_contrato=$id_contrato_plano_pessoa&tela=8");
                }
                
            }else{
                $tela = DBRead('', 'tb_prazo_retorno_contrato', "WHERE id_contrato_plano_pessoa = $id_contrato_plano_pessoa");
                $id_tela = $tela[0]['id_prazo_retorno_contrato'];
                if($tela){
                    header("location: /api/iframe?token=$request->token&view=prazo-retorno-form&alterar=$id_tela&ativacao=1&dado_posterior=$id&id_contrato=$id_contrato_plano_pessoa&tela=1");
                }else{
                    header("location: /api/iframe?token=$request->token&view=prazo-retorno-form&ativacao=1&dado_posterior=$id&id_contrato=$id_contrato_plano_pessoa&tela=1");
                }
                
            }
            
        }
    }else{
        header("location: /api/iframe?token=$request->token&view=horario-busca");
    }
    exit;
}

function excluir($id, $exclui_ativacao, $id_contrato, $excluir_por_tipo){

    if($exclui_ativacao == 1){

        $query = "DELETE FROM tb_horario_contrato WHERE id_horario_contrato = $id";
        $link = DBConnect('');
        $result = @mysqli_query($link, $query);
        DBClose($link);

        registraLog('Exclusão de horário contrato.','e','tb_horario_contrato',$id,'');

        // QUADRO INFORMATIVO HISTORICO
        inserirHistorico($id_contrato, 3, "Exclusão de horários", "Excluiu dados", 4);

        if (!$result) {
            $alert = ('Erro ao excluir item!');
            $alert_type = 'd';        } else {
            $alert = ('Item excluído com sucesso!');
    $alert_type = 's';        }

        if ($excluir_por_tipo == 1) {
            $tela = DBRead('', 'tb_horario_contrato', "WHERE tipo=7 AND id_contrato_plano_pessoa = '$id_contrato'");
            $id_tela = $tela[0]['id_horario_contrato'];

            if ($tela) {
                header("location: /api/iframe?token=$request->token&view=horario-form&alterar=$id_tela&ativacao=1&dado_posterior=$id&id_contrato=$id_contrato&tela=7");
            } else {
                header("location: /api/iframe?token=$request->token&view=horario-form&ativacao=1&dado_posterior=$id&id_contrato=$id_contrato&tela=7");
            }
            
        } else if ($excluir_por_tipo == 7) {
            $tela = DBRead('', 'tb_horario_contrato', "WHERE tipo=3 AND id_contrato_plano_pessoa = '$id_contrato'");
            $id_tela = $tela[0]['id_horario_contrato'];

            if ($tela) {
                header("location: /api/iframe?token=$request->token&view=horario-form&alterar=$id_tela&ativacao=1&dado_posterior=$id&id_contrato=$id_contrato&tela=3");
            } else {
                header("location: /api/iframe?token=$request->token&view=horario-form&ativacao=1&dado_posterior=$id&id_contrato=$id_contrato&tela=3");
            }

        } else if ($excluir_por_tipo == 3) {
            $tela = DBRead('', 'tb_horario_contrato', "WHERE tipo=5 AND id_contrato_plano_pessoa = '$id_contrato'");
            $id_tela = $tela[0]['id_horario_contrato'];

            if ($tela) {
                header("location: /api/iframe?token=$request->token&view=horario-form&alterar=$id_tela&ativacao=1&dado_posterior=$id&id_contrato=$id_contrato&tela=5");
            } else {
                header("location: /api/iframe?token=$request->token&view=horario-form&ativacao=1&dado_posterior=$id&id_contrato=$id_contrato&tela=5");
            }

        } else if ($excluir_por_tipo == 5) {
            $tela = DBRead('', 'tb_horario_contrato', "WHERE tipo=6 AND id_contrato_plano_pessoa = '$id_contrato'");
            $id_tela = $tela[0]['id_horario_contrato'];

            if ($tela) {
                header("location: /api/iframe?token=$request->token&view=horario-form&alterar=$id_tela&ativacao=1&dado_posterior=$id&id_contrato=$id_contrato&tela=6");
            }else{
                header("location: /api/iframe?token=$request->token&view=horario-form&ativacao=1&dado_posterior=$id&id_contrato=$id_contrato&tela=6");
            }

        }/*else if($excluir_por_tipo == 6){
            $tela = DBRead('', 'tb_horario_contrato', "WHERE tipo=4 AND id_contrato_plano_pessoa = '$id_contrato'");
            $id_tela = $tela[0]['id_horario_contrato'];
            if($tela){
                header("location: /api/iframe?token=$request->token&view=horario-form&alterar=$id_tela&ativacao=1&dado_posterior=$id&id_contrato=$id_contrato&tela=4");
            }else{
                header("location: /api/iframe?token=$request->token&view=horario-form&ativacao=1&dado_posterior=$id&id_contrato=$id_contrato&tela=4");
            }
        }*/else {

            $dados = DBRead('', 'tb_prazo_retorno_contrato', "WHERE id_contrato_plano_pessoa = $id_contrato");

            if($dados){
                $dado_posterior = $dados[0]['id_prazo_retorno_contrato'];
                header("location: /api/iframe?token=$request->token&view=prazo-retorno-form&alterar=$dado_posterior&ativacao=1&id_contrato=$id_contrato&tela=1");
            }else{
                header("location: /api/iframe?token=$request->token&view=prazo-retorno-form&ativacao=1&id_contrato=$id_contrato&tela=1");
            }
        }
        
    } else {
        $id_contrato = DBRead('', 'tb_horario_contrato', "WHERE id_horario_contrato = ".$id."");
        $id_contrato = $id_contrato[0]['id_contrato_plano_pessoa'];
        
        $query = "DELETE FROM tb_horario_contrato WHERE id_horario_contrato = $id";
        $link = DBConnect('');
        $result = @mysqli_query($link, $query);
        DBClose($link);

        registraLog('Exclusão de horário contrato.','e','tb_horario_contrato', $id, '');
        

       // QUADRO INFORMATIVO HISTORICO
       inserirHistorico($id_contrato, 3, "Exclusão de horários", "Excluiu dados", 4);

        if(!$result){
            $alert = ('Erro ao excluir item!');
            $alert_type = 'd';        }else{
            $alert = ('Item excluído com sucesso!');
    $alert_type = 's';        }
        header("location: /api/iframe?token=$request->token&view=horario-busca");
    }
    exit;
}

?>