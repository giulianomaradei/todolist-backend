<?php
require_once(__DIR__."/System.php");


function notificaChamado($id_contrato_plano_pessoa, $percentual_usado){

    $dados_contrato = DBRead('', 'tb_contrato_plano_pessoa', "WHERE id_contrato_plano_pessoa = $id_contrato_plano_pessoa", 'id_responsavel');
    $responsavel = $dados_contrato[0]['id_responsavel'];

    $perfil = DBRead('', 'tb_usuario', "WHERE id_usuario = $responsavel", 'id_perfil_sistema');
    $perfil = $perfil[0]['id_perfil_sistema'];

    $nome_contrato = getContrato($id_contrato_plano_pessoa);

    $data_criacao = getDataHora();

    $prazo_encerramento = date('Y-m-d H:m', strtotime($data_criacao. ' + 1 days'));

    $titulo = 'Atendimentos do contrato pré-pago chegando ao fim';

    $descricao = 'O contrato pré-pago da '.$nome_contrato.' atingiu o percentual de '.$percentual_usado.'% de atendimentos realizados.';

    $remetente = $responsavel;
    $id_chamado_status = '1';
    $bloqueado = '1';
    $visibilidade = '1';
    $id_chamado_origem = '3';

    $dados = array(
        'data_criacao' => $data_criacao,
        'titulo' => $titulo,
        'descricao' => $descricao,
        'bloqueado' => $bloqueado,
        'id_usuario_remetente' => $remetente,
        'id_chamado_status' => $id_chamado_status,
        'visibilidade' => $visibilidade,
        'id_usuario_responsavel' => $responsavel,
        'id_contrato_plano_pessoa' => $id_contrato_plano_pessoa,
        'id_chamado_origem' => $id_chamado_origem,
        'prazo_encerramento' => $prazo_encerramento,
        'id_chamado_setor' => 1
    );

    $link = DBConnect('');
    DBBegin($link);

    $insertIDchamado = DBCreateTransaction($link, 'tb_chamado', $dados, true);
    registraLogTransaction($link, 'Inserção de chamado.', 'i', 'tb_chamado', $insertIDchamado, "data_criacao: $data_criacao | titulo: $titulo | descricao: $descricao | bloqueado: $bloqueado | id_usuario_remetente: $remetente | status: $id_chamado_status | visibilidade: $visibilidade | id_usuario_responsavel: $responsavel | id_contrato_plano_pessoa: $id_contrato_plano_pessoa | id_chamado_origem: $id_chamado_origem | prazo_encerramento: $prazo_encerramento | id_chamado_setor: 1");
        
    $acao = "criacao";

    $id_categoria = '28';

    $dadosCategoria = array(
        'id_categoria' => $id_categoria,
        'id_chamado' => $insertIDchamado,
    );
    
    $insertCategoria = DBCreateTransaction($link, 'tb_chamado_categoria', $dadosCategoria, true);
    registraLogTransaction($link, 'Inserção de categoria chamado.','i','tb_chamado_categoria',$insertCategoria,"id_categoria: $id_categoria | id_chamado: $insertIDchamado");
    
    $tempo = 1;
    $id_usuario_acao = $responsavel;

    $dados_acao = array(
        'data' => $data_criacao,
        'descricao' => $descricao,
        'id_chamado_status' => $id_chamado_status,
        'visibilidade' => $visibilidade,
        'acao' => $acao,
        'tempo' => $tempo,
        'id_chamado' => $insertIDchamado,
        'id_usuario_responsavel' => $responsavel,
        'id_usuario_acao' => $id_usuario_acao,
        'bloqueado' => $bloqueado,
        'id_contrato_plano_pessoa' => $id_contrato_plano_pessoa
    );

    $insertAcao = DBCreateTransaction($link, 'tb_chamado_acao', $dados_acao, true);
    registraLogTransaction($link, 'Inserção de ação.','i','tb_chamado_acao',$insertAcao,"data: $data_criacao | descricao: $descricao | id_chamado_status: $id_chamado_status | visibilidade: $visibilidade | acao: $acao | tempo: $tempo | id_chamado: $insertIDchamado | id_usuario_responsavel: $responsavel | id_usuario_acao: $id_usuario_acao | bloqueado: $bloqueado | id_contrato_plano_pessoa: $id_contrato_plano_pessoa");

    //nota com visibilidade para o painel do cliente
    $dados_acao = array(
        'data' => $data_criacao,
        'descricao' => 'Seu contrato atingiu o percentual de '.$percentual_usado.'% da quantidade total de atendimentos contratados. Para continuar usufruindo do serviço, efetue uma recarga!',
        'id_chamado_status' => $id_chamado_status,
        'visibilidade' => $visibilidade,
        'acao' => 'nota_geral',
        'tempo' => $tempo,
        'id_chamado' => $insertIDchamado,
        'id_usuario_responsavel' => $responsavel,
        'id_usuario_acao' => $id_usuario_acao,
        'bloqueado' => $bloqueado,
        'id_contrato_plano_pessoa' => $id_contrato_plano_pessoa
    );

    $insertAcao = DBCreateTransaction($link, 'tb_chamado_acao', $dados_acao, true);
    registraLogTransaction($link, 'Inserção de ação.','i','tb_chamado_acao',$insertAcao,"data: $data_criacao | descricao: $descricao | id_chamado_status: $id_chamado_status | visibilidade: $visibilidade | acao: $acao | tempo: $tempo | id_chamado: $insertIDchamado | id_usuario_responsavel: $responsavel | id_usuario_acao: $id_usuario_acao | bloqueado: $bloqueado | id_contrato_plano_pessoa: $id_contrato_plano_pessoa");
    
    $perfis = [$perfil, '2']; //perfil 2 (desenvolvimento) só para teste

    foreach($perfis as $perfil){
        $dados = array(
            'id_chamado' => $insertIDchamado,
            'id_perfil_sistema' => $perfil
        );

        $insertChamadoPerfil = DBCreateTransaction($link, 'tb_chamado_perfil', $dados, true);
        registraLogTransaction($link, 'Inserção de chamado perfil.','i','tb_topico',$insertChamadoPerfil,"id_chamado: $insertIDchamado | id_perfil_sistema: $perfil");
    }

    DBCommit($link);

}


$dateTime = new DateTime($data_hoje);
$dateTime->modify('first day of this month');
$data_de = $dateTime->format("Y-m-d");

$dateTime = new DateTime($data_hoje);
$dateTime->modify('last day of this month');
$data_ate = $dateTime->format("Y-m-d");

var_dump($data_de, $data_ate);