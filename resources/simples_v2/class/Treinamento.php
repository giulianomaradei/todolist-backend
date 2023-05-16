<?php
require_once(__DIR__."/System.php");


$nome = (!empty($_POST['nome'])) ? $_POST['nome'] : '';
$objetivo = (!empty($_POST['objetivo'])) ? $_POST['objetivo'] : '';
$carga_horaria = (!empty($_POST['carga_horaria'])) ? $_POST['carga_horaria'] : 0;
$perfil_sistema = (!empty($_POST['perfil_sistema'])) ? $_POST['perfil_sistema'] : '';
$responsaveis = (!empty($_POST['responsaveis'])) ? $_POST['responsaveis'] : '';
$participantes = (!empty($_POST['participantes'])) ? $_POST['participantes'] : '';
$descricao = (!empty($_POST['descricao'])) ? $_POST['descricao'] : '';
$avaliar_em = (!empty($_POST['avaliar_em'])) ? $_POST['avaliar_em'] : '';
$data_inicio = (!empty($_POST['data_inicio'])) ? $_POST['data_inicio'] : '';
$data_fim = (!empty($_POST['data_fim'])) ? $_POST['data_fim'] : '';

if (!empty($_POST['inserir'])) {

    inserir($nome, $objetivo, $carga_horaria, $perfil_sistema, $responsaveis, $participantes, $descricao, $avaliar_em, $data_inicio, $data_fim);

} elseif (!empty($_POST['avaliar'])) {
    $id_treinamento_participante = (int)$_POST['avaliar'];
    $avaliacao = (!empty($_POST['avaliacao'])) ? $_POST['avaliacao'] : '';
    $id_treinamento = (!empty($_POST['id_treinamento'])) ? $_POST['id_treinamento'] : '';
    $plano_acao = (!empty($_POST['plano_acao'])) ? $_POST['plano_acao'] : '';
    $data_avaliacao = (!empty($_POST['data_avaliacao'])) ? $_POST['data_avaliacao'] : '';
    $id_usuario = $_SESSION['id_usuario'];

    $dados = DBRead('', 'tb_treinamento_responsavel', "WHERE id_usuario = $id_usuario AND id_treinamento = $id_treinamento");
    
    if ($dados) {
        $id_treinamento_responsavel = $dados[0]['id_treinamento_responsavel'];
        avaliar($id_treinamento_participante, $id_treinamento_responsavel, $avaliacao, $id_treinamento, $plano_acao, $data_avaliacao);
    } else {
        $alert = ('Você não é o responsável pela avaliação do treinamento!', 'w');
        header("location: /api/iframe?token=$request->token&view=treinamento-informacoes&idtreinamento=$id_treinamento");
    }

} elseif (!empty($_POST['adicionar_observacao'])) {

    $id_treinamento_participante = (int)$_POST['adicionar_observacao'];
    $obs = (!empty($_POST['obs'])) ? $_POST['obs'] : '';
    $id_treinamento = (!empty($_POST['id_treinamento_obs'])) ? $_POST['id_treinamento_obs'] : '';
    $id_usuario = $_SESSION['id_usuario'];

    $dados = DBRead('', 'tb_treinamento_responsavel', "WHERE id_usuario = $id_usuario AND id_treinamento = $id_treinamento");

    if ($dados) {
        adicionarObs($id_treinamento_participante, $obs, $id_treinamento);

    } else {
        $alert = ('Você não é o responsável pela avaliação do treinamento!', 'w');
        header("location: /api/iframe?token=$request->token&view=treinamento-informacoes&idtreinamento=$id_treinamento");
    }

} else if (!empty($_POST['alterar'])) {

    $id_treinamento = (int) $_POST['alterar'];

    $dados_responsaveis = DBRead('', 'tb_treinamento_responsavel', "WHERE id_treinamento = $id_treinamento", 'id_treinamento_responsavel');

    if ($dados_responsaveis) {

        $verifica = 0;
        foreach ($dados_responsaveis as $conteudo) {
            $dados = DBRead('', 'tb_treinamento_avaliacao', "WHERE id_treinamento_responsavel = '".$conteudo['id_treinamento_responsavel']."' ");

            if ($dados) {
                $verifica = 1;
            }
        }

        if ($verifica == 1) {
            $alert = ('Não foi possivel alterar o treinamento pois já houveram avaliações!', 'w');
            header("location: /api/iframe?token=$request->token&view=treinamento-informacoes&idtreinamento=$id_treinamento");
        } else {
            alterar($id_treinamento, $nome, $objetivo, $carga_horaria, $perfil_sistema, $responsaveis, $participantes, $descricao, $avaliar_em, $data_inicio, $data_fim);
        }

    } else {
        $alert = ('Não foi possivel alterar o treinamento!', 'w');
        header("location: /api/iframe?token=$request->token&view=treinamento-informacoes&idtreinamento=$id_treinamento");
    }
} else if (!empty($_POST['adicionar_responsavel'])) {

    $id_treinamento = (int) $_POST['adicionar_responsavel'];
    $responsavel = (!empty($_POST['responsavel'])) ? $_POST['responsavel'] : '';

    $verifica = DBRead('', 'tb_treinamento_responsavel', "WHERE id_usuario = $responsavel AND id_treinamento = $id_treinamento");

    if ($verifica) {
        $alert = ('Este usuário já é responsável neste treinamento!', 'w');
        header("location: /api/iframe?token=$request->token&view=treinamento-informacoes&idtreinamento=$id_treinamento");
    } else {
        adicionar_responsavel($id_treinamento, $responsavel);
    }

} else if (!empty($_POST['adicionar_participante'])) {

    $id_treinamento = (int) $_POST['adicionar_participante'];
    $participante = (!empty($_POST['participante'])) ? $_POST['participante'] : '';

    $verifica = DBRead('', 'tb_treinamento_participante', "WHERE id_usuario = $participante AND id_treinamento = $id_treinamento");

    if ($verifica) {
        $alert = ('Este usuário já está participando do treinamento!', 'w');
        header("location: /api/iframe?token=$request->token&view=treinamento-informacoes&idtreinamento=$id_treinamento");
    } else {
        adicionar_participante($id_treinamento, $participante);
    }
    
} else if (!empty($_GET['excluir'])) {

    $id_treinamento = (int) $_GET['excluir'];

    excluir($id_treinamento);
        
} else {
    header("location: ../adm.php");
    exit;
}

function inserir($nome, $objetivo, $carga_horaria, $perfil_sistema, $responsaveis, $participantes, $descricao, $avaliar_em, $data_inicio, $data_fim)
{
    if ($nome != '' && $objetivo !='' && $carga_horaria != 0 && $perfil_sistema !='' && $responsaveis !='' && $participantes !='' && $descricao !='' && $avaliar_em !='' && $data_inicio !='' && $data_fim !='') {

        $data_inicio = converteData($data_inicio);
        $data_fim = converteData($data_fim);
        $avaliar_em = converteData($avaliar_em);

        $dados = array(
            'nome' => $nome,
            'descricao' => $descricao,
            'data_inicio' => $data_inicio,
            'data_fim' => $data_fim,
            'objetivo' => $objetivo,
            'carga_horaria' => $carga_horaria,
            'avaliar_em' => $avaliar_em
        );

        $link = DBConnect('');
        DBBegin($link);

        $id_treinamento = DBCreateTransaction($link, 'tb_treinamento', $dados, true);
        registraLogTransaction($link, 'Inserção de treinamento.', 'i', 'tb_treinamento', $id_treinamento, "nome: $nome | descricao: $descricao | data_inicio: $data_inicio | data_fim: $data_fim | objetivo: $objetivo | carga_horaria: $carga_horaria | avaliar_em: $avaliar_em");

        foreach ($perfil_sistema as $conteudo_perfil_sistema) {

            $dados_perfl_sistema = array(
                'id_treinamento' => $id_treinamento,
                'id_perfil_sistema' => $conteudo_perfil_sistema
            );

            $insertID = DBCreateTransaction($link, 'tb_treinamento_perfil_sistema', $dados_perfl_sistema);
            registraLogTransaction($link, 'Inserção de treinamento perfil sistema.', 'i', 'tb_treinamento_perfil_sistema', $insertID, "id_treinamento: $id_treinamento | id_perfil_sistema: $conteudo_perfil_sistema");
        }

        foreach ($responsaveis as $conteudo_responsavel) {

            $dados = array(
                'id_treinamento' => $id_treinamento,
                'id_usuario' => $conteudo_responsavel
            );

            $insertID = DBCreateTransaction($link, 'tb_treinamento_responsavel', $dados);
            registraLogTransaction($link, 'Inserção de treinamento responsavel.', 'i', 'tb_treinamento_responsavel', $insertID, "id_treinamento: $id_treinamento | id_usuario: $conteudo_responsavel");
        }

        foreach ($participantes as $conteudo_participante) {

            $dados = array(
                'id_treinamento' => $id_treinamento,
                'id_usuario' => $conteudo_participante
            );

            $insertID = DBCreateTransaction($link, 'tb_treinamento_participante', $dados);
            registraLogTransaction($link, 'Inserção de treinamento participante.', 'i', 'tb_treinamento_participante', $insertID, "id_treinamento: $id_treinamento | id_usuario: $conteudo_participante");
        }

        DBCommit($link);
        $alert = ('Treinamento inserido com sucesso!', 's');
        header("location: /api/iframe?token=$request->token&view=treinamento-busca");
    } else {
        $alert = ('Não foi possível inserir o treinamento!', 'w');
        header("location: /api/iframe?token=$request->token&view=treinamento-busca");
    }
}

function avaliar($id_treinamento_participante, $id_treinamento_responsavel, $avaliacao, $id_treinamento, $plano_acao, $data_avaliacao)
{

    if ($id_treinamento_participante !='' && $id_treinamento_responsavel !='' && $avaliacao !='' && $id_treinamento !='' && $data_avaliacao != '') {

        $data_avaliacao = converteData($data_avaliacao);

        if ($avaliacao == 1) {
            $avaliacao = 'Eficaz';
        } elseif ($avaliacao == 2) {
            $avaliacao = 'Ineficaz';
        } elseif ($avaliacao == 3) {
            $avaliacao = 'Não se aplica';
        }

        $dados = array(
            'id_treinamento_participante' => $id_treinamento_participante,
            'id_treinamento_responsavel' => $id_treinamento_responsavel,
            'eficaz' => $avaliacao,
            'data_avaliacao' => $data_avaliacao,
            'plano_acao' => $plano_acao
        );

        $insertID = DBCreate('', 'tb_treinamento_avaliacao', $dados, true);
        registraLog('Inserção de treinamento avaliacao.', 'i', 'tb_treinamento_avaliacao', $insertID, "id_treinamento_participante: $id_treinamento_participante | id_treinamento_responsavel: $id_treinamento_responsavel | eficaz: $avaliacao | data_avaliacao: $data_avaliacao | plano_acao: $plano_acao");

        $alert = ('Participante avaliado com sucesso!', 's');
        header("location: /api/iframe?token=$request->token&view=treinamento-informacoes&idtreinamento=$id_treinamento");

    } else {
        $alert = ('Não foi possivel realizar a avaliação!', 'd');
        header("location: /api/iframe?token=$request->token&view=treinamento-informacoes&idtreinamento=$id_treinamento");
    }

}

function alterar($id_treinamento, $nome, $objetivo, $carga_horaria, $perfil_sistema, $responsaveis, $participantes, $descricao, $avaliar_em, $data_inicio, $data_fim)
{
    if ($id_treinamento !='' && $nome != '' && $objetivo != '' && $carga_horaria != 0 && $perfil_sistema != '' && $descricao != '' && $avaliar_em != '' && $data_inicio != '' && $data_fim != '') {

        $data_inicio = converteData($data_inicio);
        $data_fim = converteData($data_fim);
        $avaliar_em = converteData($avaliar_em);

        $dados = array(
            'nome' => $nome,
            'descricao' => $descricao,
            'data_inicio' => $data_inicio,
            'data_fim' => $data_fim,
            'objetivo' => $objetivo,
            'carga_horaria' => $carga_horaria,
            'avaliar_em' => $avaliar_em
        );

        $link = DBConnect('');
        DBBegin($link);

        DBUpdateTransaction($link, 'tb_treinamento', $dados, "id_treinamento = $id_treinamento");
        registraLogTransaction($link, 'Alteracao de treinamento.', 'a', 'tb_treinamento', $id_treinamento, "nome: $nome | descricao: $descricao | data_inicio: $data_inicio | data_fim: $data_fim | objetivo: $objetivo | carga_horaria: $carga_horaria | avaliar_em: $avaliar_em");

        DBDeleteTransaction($link, 'tb_treinamento_perfil_sistema', "id_treinamento = $id_treinamento");
        registraLogTransaction($link, 'Exclusão de treinamento perfil sistema.', 'e', 'tb_treinamento_perfil_sistema', $id_treinamento, "id_treinamento: $id_treinamento");

        foreach ($perfil_sistema as $conteudo_perfil_sistema) {

            $dados_perfil_sistema = array(
                'id_treinamento' => $id_treinamento,
                'id_perfil_sistema' => $conteudo_perfil_sistema
            );

            $insertID = DBCreateTransaction($link, 'tb_treinamento_perfil_sistema', $dados_perfil_sistema);
            registraLogTransaction($link, 'Inserção de treinamento perfil sistema.', 'i', 'tb_treinamento_perfil_sistema', $insertID, "id_treinamento: $id_treinamento | id_perfil_sistema: $conteudo_perfil_sistema");
        }

        /*DBDeleteTransaction($link, 'tb_treinamento_responsavel', "id_treinamento = $id_treinamento");
        registraLogTransaction($link, 'Exclusão de treinamento responsavel.', 'e', 'tb_treinamento_responsavel', $id_treinamento, "id_treinamento: $id_treinamento");

        foreach ($responsaveis as $conteudo_responsavel) {

            $dados = array(
                'id_treinamento' => $id_treinamento,
                'id_usuario' => $conteudo_responsavel
            );

            $insertID = DBCreateTransaction($link, 'tb_treinamento_responsavel', $dados);
            registraLogTransaction($link, 'Inserção de treinamento responsavel.', 'i', 'tb_treinamento_responsavel', $insertID, "id_treinamento: $id_treinamento | id_usuario: $conteudo_responsavel");
        }

        DBDeleteTransaction($link, 'tb_treinamento_participante', "id_treinamento = $id_treinamento");
        registraLogTransaction($link, 'Exclusão de treinamento responsavel.', 'e', 'tb_treinamento_participante', $id_treinamento, "id_treinamento: $id_treinamento");

        foreach ($participantes as $conteudo_participante) {

            $dados = array(
                'id_treinamento' => $id_treinamento,
                'id_usuario' => $conteudo_participante
            );

            $insertID = DBCreateTransaction($link, 'tb_treinamento_participante', $dados);
            registraLogTransaction($link, 'Inserção de treinamento participante.', 'i', 'tb_treinamento_participante', $insertID, "id_treinamento: $id_treinamento | id_usuario: $conteudo_participante");
        }*/

        DBCommit($link);
        $alert = ('Treinamento alterado com sucesso!', 's');
        header("location: /api/iframe?token=$request->token&view=treinamento-informacoes&idtreinamento=$id_treinamento");
    } else {
        $alert = ('Não foi possível alterar o treinamento!', 'w');
        header("location: /api/iframe?token=$request->token&view=treinamento-informacoes&idtreinamento=$id_treinamento");
    }

}

function adicionar_responsavel($id_treinamento, $responsavel)
{

    if ($id_treinamento !='' && $responsavel !='') {

        $dados = array(
            'id_usuario' => $responsavel,
            'id_treinamento' => $id_treinamento
        );

        $insertID = DBCreate('', 'tb_treinamento_responsavel', $dados, true);
        registraLog('Inserção de treinamento responsavel.', 'i', 'tb_treinamento_responsavel', $insertID, "id_usuario: $responsavel | id_treinamento: $id_treinamento");

        $alert = ('Responsável pelo treinamento adicionado com sucesso!', 's');
        header("location: /api/iframe?token=$request->token&view=treinamento-informacoes&idtreinamento=$id_treinamento");

    } else {
        $alert = ('Não foi possível adicionar o responsável pelo treinamento!', 'w');
        header("location: /api/iframe?token=$request->token&view=treinamento-informacoes&idtreinamento=$id_treinamento");
    }
}

function adicionar_participante($id_treinamento, $participante)
{

    if ($id_treinamento != '' && $participante != '') {

        $dados = array(
            'id_usuario' => $participante,
            'id_treinamento' => $id_treinamento
        );

        $insertID = DBCreate('', 'tb_treinamento_participante', $dados, true);
        registraLog('Inserção de treinamento participante.', 'i', 'tb_treinamento_participante', $insertID, "id_usuario: $participante | id_treinamento: $id_treinamento");

        $alert = ('Participante adicionado com sucesso!', 's');
        header("location: /api/iframe?token=$request->token&view=treinamento-informacoes&idtreinamento=$id_treinamento");
    } else {
        $alert = ('Não foi possível adicionar o participante no treinamento!', 'w');
        header("location: /api/iframe?token=$request->token&view=treinamento-informacoes&idtreinamento=$id_treinamento");
    }
}

function excluir($id_treinamento)
{   
    if ($id_treinamento != '') {
        
        $dados = array(
            'status' => 2
        );

        DBUpdate('', 'tb_treinamento', $dados, "id_treinamento = $id_treinamento");
        registraLog('Alteração de status do treinamento', 'a', 'tb_treinamento', $id_treinamento, "status: 2");

        $alert = ('Trainamento excluído com sucesso!', 's');
        header("location: /api/iframe?token=$request->token&view=treinamento-busca");
    } else {
        $alert = ('Não foi possível excluir o treinamento!', 's');
        header("location: /api/iframe?token=$request->token&view=treinamento-busca");
    }
    
}

function adicionarObs($id_treinamento_participante, $obs, $id_treinamento)
{

    if ($id_treinamento_participante !='' && $id_treinamento != '') {

        $dados = array(
            'obs' => $obs
        );

        DBUpdate('', 'tb_treinamento_participante', $dados, "id_treinamento_participante = $id_treinamento_participante");
        registraLog('Alteracao de treinamento participante.', 'a', 'tb_treinamento_participante', $id_treinamento_participante, "obs: $obs");

        $alert = ('Observação inserida com sucesso!', 's');
        header("location: /api/iframe?token=$request->token&view=treinamento-informacoes&idtreinamento=$id_treinamento");

    } else {

        $alert = ('Não foi possivel inserir a observação!', 'd');
        header("location: /api/iframe?token=$request->token&view=treinamento-informacoes&idtreinamento=$id_treinamento");
    }
}