<?php
require_once(__DIR__."/System.php");
require_once(__DIR__."/QuadroInformativoHistorico.php");

$velocidade_reduzida = (!empty($_POST['descricao_velocidade_reduzida'])) ? $_POST['descricao_velocidade_reduzida'] : '';
$acesso_controladoras = (!empty($_POST['descricao_acesso_controladoras'])) ? $_POST['descricao_acesso_controladoras'] : '';
$roteadores = (!empty($_POST['roteadores'])) ? $_POST['roteadores'] : '';
$computadores = (!empty($_POST['computadores'])) ? $_POST['computadores'] : '';
$classificacao_atendimento_sistema_gestao = (!empty($_POST['classificacao_atendimento_sistema_gestao'])) ? $_POST['classificacao_atendimento_sistema_gestao'] : '';
$selecao_finalizacao_sistema_gestao = (!empty($_POST['selecao_finalizacao_sistema_gestao'])) ? $_POST['selecao_finalizacao_sistema_gestao'] : '';
$bloqueados = (!empty($_POST['bloqueados'])) ? $_POST['bloqueados'] : '';
$contratacao_servico = (!empty($_POST['contratacao_servico'])) ? $_POST['contratacao_servico'] : '';
$troca_plano = (!empty($_POST['troca_plano'])) ? $_POST['troca_plano'] : '';
$troca_endereco = (!empty($_POST['troca_endereco'])) ? $_POST['troca_endereco'] : '';
$cancelamentos = (!empty($_POST['cancelamentos'])) ? $_POST['cancelamentos'] : '';
$situacoes_adversas = (!empty($_POST['situacoes_adversas'])) ? $_POST['situacoes_adversas'] : '';
$informacoes_adicionais = (!empty($_POST['informacoes_adicionais'])) ? $_POST['informacoes_adicionais'] : '';
$troca_comodo = (!empty($_POST['troca_comodo'])) ? $_POST['troca_comodo'] : '';
$segunda_via = (!empty($_POST['segunda_via'])) ? $_POST['segunda_via'] : '';
$descontos = (!empty($_POST['descontos'])) ? $_POST['descontos'] : '';

//$servico_telefonia_tv_assinatura = (!empty($_POST['servico_telefonia_tv_assinatura'])) ? $_POST['servico_telefonia_tv_assinatura'] : '';
$servico_telefonia = (!empty($_POST['servico_telefonia'])) ? $_POST['servico_telefonia'] : '';
$tv_assinatura = (!empty($_POST['tv_assinatura'])) ? $_POST['tv_assinatura'] : '';
$servico_streaming = (!empty($_POST['servico_streaming'])) ? $_POST['servico_streaming'] : '';


$suporte_dispositivos_moveis = (!empty($_POST['suporte_dispositivos_moveis'])) ? $_POST['suporte_dispositivos_moveis'] : '';
$posicao_os = (!empty($_POST['posicao_os'])) ? $_POST['posicao_os'] : '';
$posicao_instalacao = (!empty($_POST['posicao_instalacao'])) ? $_POST['posicao_instalacao'] : '';
$tipo_os = (!empty($_POST['tipo_os'])) ? $_POST['tipo_os'] : '';
$confirmacao_cadastro_cliente = (!empty($_POST['confirmacao_cadastro_cliente'])) ? $_POST['confirmacao_cadastro_cliente'] : '';
$suporte_acesso_lento = (!empty($_POST['suporte_acesso_lento'])) ? $_POST['suporte_acesso_lento'] : '';
$nao_cliente = (!empty($_POST['nao_cliente'])) ? $_POST['nao_cliente'] : '';
$tipo_equipamento = (!empty($_POST['tipo_equipamento'])) ? $_POST['tipo_equipamento'] : '';

$monitoramento = (!empty($_POST['monitoramento'])) ? $_POST['monitoramento'] : '0';

$horarios_monitoramento = (!empty($_POST['horarios_monitoramento'])) ? $_POST['horarios_monitoramento'] : '';

$velocidade_reduzida_bool = (!empty($_POST['velocidade_reduzida'])) ? $_POST['velocidade_reduzida'] : 0;
$acesso_controladoras_bool = (!empty($_POST['acesso_controladoras'])) ? $_POST['acesso_controladoras'] : 0;
$roteadores_bool = (!empty($_POST['manutencao_roteadores'])) ? $_POST['manutencao_roteadores'] : 0;
$computadores_bool = (!empty($_POST['manutencao_computadores'])) ? $_POST['manutencao_computadores'] : 0;
$suporte_dispositivos_moveis_bool = (!empty($_POST['suporte_dispositivos'])) ? $_POST['suporte_dispositivos'] : 0;
$servico_telefonia_bool = (!empty($_POST['servico_telefonia_bool'])) ? $_POST['servico_telefonia_bool'] : 0;
$tv_assinatura_bool = (!empty($_POST['tv_assinatura_bool'])) ? $_POST['tv_assinatura_bool'] : 0;
$servico_streaming_bool = (!empty($_POST['servico_streaming_bool'])) ? $_POST['servico_streaming_bool'] : 0;


$id_contrato_plano_pessoa  = (!empty($_POST['id_contrato_plano_pessoa'])) ? $_POST['id_contrato_plano_pessoa'] : '';

$ativacao = (!empty($_POST['ativacao'])) ? $_POST['ativacao'] : 0;
$pular = (!empty($_POST['pular'])) ? $_POST['pular'] : '';
$voltar = (!empty($_POST['voltar'])) ? $_POST['voltar'] : '';
$salvar = (!empty($_POST['salvar'])) ? $_POST['salvar'] : 0;
$exclui_ativacao = (!empty($_GET['exclui-ativacao'])) ? $_GET['exclui-ativacao'] : 0;
$id_contrato = (!empty($_GET['id-contrato'])) ? $_GET['id-contrato'] : '';

$inativo_cancelado = (!empty($_POST['inativo_cancelado'])) ? $_POST['inativo_cancelado'] : '';

if(!empty($_POST['inserir'])){

    $verificacao_contrato = DBRead('', 'tb_informacao_geral_contrato', "WHERE id_contrato_plano_pessoa = '".$id_contrato_plano_pessoa."'");

    if(!$verificacao_contrato){
        inserir($velocidade_reduzida, $acesso_controladoras, $roteadores, $computadores, $classificacao_atendimento_sistema_gestao, $selecao_finalizacao_sistema_gestao, $bloqueados, $contratacao_servico, $troca_plano, $troca_endereco, $cancelamentos, $situacoes_adversas, $informacoes_adicionais, $troca_comodo, $segunda_via, $descontos, $servico_telefonia, $tv_assinatura, $suporte_dispositivos_moveis, $posicao_os, $posicao_instalacao, $tipo_os, $confirmacao_cadastro_cliente, $suporte_acesso_lento, $nao_cliente, $tipo_equipamento, $monitoramento, $horarios_monitoramento, $velocidade_reduzida_bool, $acesso_controladoras_bool, $roteadores_bool, $computadores_bool, $suporte_dispositivos_moveis_bool, $servico_telefonia_bool, $tv_assinatura_bool, $id_contrato_plano_pessoa, $ativacao, $pular, $voltar, $salvar, $inativo_cancelado, $servico_streaming, $servico_streaming_bool);

    }else{
        $alert = ('Item já existe na base de dados!');
        $alert_type = 'w';        if($ativacao == 1){
            header("location: /api/iframe?token=$request->token&view=informacoes-gerais-form&ativacao=1&id_contrato=$id_contrato_plano_pessoa");
        }else{
            header("location: /api/iframe?token=$request->token&view=informacoes-gerais-form");
        }
        exit;
    }

}else if(!empty($_POST['alterar'])){
    $id = (int)$_POST['alterar'];

    $verificacao_contrato = DBRead('', 'tb_informacao_geral_contrato', "WHERE id_contrato_plano_pessoa = '".$id_contrato_plano_pessoa."' AND id_informacao_geral_contrato != ".$id);

    if(!$verificacao_contrato){
        alterar($id, $velocidade_reduzida, $acesso_controladoras, $roteadores, $computadores, $classificacao_atendimento_sistema_gestao, $selecao_finalizacao_sistema_gestao, $bloqueados, $contratacao_servico, $troca_plano, $troca_endereco, $cancelamentos, $situacoes_adversas, $informacoes_adicionais, $troca_comodo, $segunda_via, $descontos, $servico_telefonia, $tv_assinatura, $suporte_dispositivos_moveis, $posicao_os, $posicao_instalacao, $tipo_os, $confirmacao_cadastro_cliente, $suporte_acesso_lento, $nao_cliente, $tipo_equipamento, $monitoramento, $horarios_monitoramento, $velocidade_reduzida_bool, $acesso_controladoras_bool, $roteadores_bool, $computadores_bool, $suporte_dispositivos_moveis_bool, $servico_telefonia_bool, $tv_assinatura_bool, $id_contrato_plano_pessoa, $ativacao, $pular, $voltar, $salvar, $inativo_cancelado, $servico_streaming, $servico_streaming_bool);
    }else{
        $alert = ('Item já existe na base de dados!', 'w');
        if($ativacao == 1){
            header("location: /api/iframe?token=$request->token&view=informacoes-gerais-form&alterar=$id&ativacao=1&id_contrato=$id_contrato_plano_pessoa");
        }else{
            header("location: /api/iframe?token=$request->token&view=informacoes-gerais-form&alterar=$id");
        }
        exit;
    }
    
}else if(isset($_GET['excluir'])){

    $id = (int)$_GET['excluir'];
    excluir($id, $exclui_ativacao, $id_contrato);
}else{
    header("location: ../adm.php");
    exit;
}

function inserir($velocidade_reduzida, $acesso_controladoras, $roteadores, $computadores, $classificacao_atendimento_sistema_gestao, $selecao_finalizacao_sistema_gestao, $bloqueados, $contratacao_servico, $troca_plano, $troca_endereco, $cancelamentos, $situacoes_adversas, $informacoes_adicionais, $troca_comodo, $segunda_via, $descontos, $servico_telefonia, $tv_assinatura, $suporte_dispositivos_moveis, $posicao_os, $posicao_instalacao, $tipo_os, $confirmacao_cadastro_cliente, $suporte_acesso_lento, $nao_cliente, $tipo_equipamento, $monitoramento, $horarios_monitoramento, $velocidade_reduzida_bool, $acesso_controladoras_bool, $roteadores_bool, $computadores_bool, $suporte_dispositivos_moveis_bool, $servico_telefonia_bool, $tv_assinatura_bool, $id_contrato_plano_pessoa, $ativacao, $pular, $voltar, $salvar, $inativo_cancelado, $servico_streaming, $servico_streaming_bool){

    $dados = array(
        'velocidade_reduzida' => $velocidade_reduzida,
        'acesso_controladoras' => $acesso_controladoras,
        'roteadores' => $roteadores,
        'computadores' => $computadores,
        'classificacao_atendimento_sistema_gestao' => $classificacao_atendimento_sistema_gestao,
        'selecao_finalizacao_sistema_gestao' => $selecao_finalizacao_sistema_gestao,
        'bloqueados' => $bloqueados,
        'contratacao_servico' => $contratacao_servico,
        'troca_plano' => $troca_plano,
        'troca_endereco' => $troca_endereco,
        'cancelamentos' => $cancelamentos,
        'situacoes_adversas' => $situacoes_adversas,
        'informacoes_adicionais' => $informacoes_adicionais,
        'troca_comodo' => $troca_comodo,
        'segunda_via' => $segunda_via,
        'descontos' => $descontos,
        'servico_telefonia' => $servico_telefonia,
        'tv_assinatura' => $tv_assinatura,
        'suporte_dispositivos_moveis' => $suporte_dispositivos_moveis,
        'posicao_os' => $posicao_os,
        'posicao_instalacao' => $posicao_instalacao,
        'tipo_os' => $tipo_os,
        'confirmacao_cadastro_cliente' => $confirmacao_cadastro_cliente,
        'suporte_acesso_lento' => $suporte_acesso_lento,
        'nao_cliente' => $nao_cliente,
        'tipo_equipamento' => $tipo_equipamento,
        'monitoramento' => $monitoramento,
        'horarios_monitoramento' => $horarios_monitoramento,
        'velocidade_reduzida_bool' => $velocidade_reduzida_bool,
        'acesso_controladoras_bool' => $acesso_controladoras_bool,
        'roteadores_bool' => $roteadores_bool,
        'computadores_bool' => $computadores_bool,
        'suporte_dispositivos_moveis_bool' => $suporte_dispositivos_moveis_bool,
        'servico_telefonia_bool' => $servico_telefonia_bool,
        'tv_assinatura_bool' => $tv_assinatura_bool,
        'id_contrato_plano_pessoa' => $id_contrato_plano_pessoa,
        'inativo_cancelado' => $inativo_cancelado,
        'servico_streaming' => $servico_streaming,
        'servico_streaming_bool' => $servico_streaming_bool
    );

    $insertID = DBCreate('', 'tb_informacao_geral_contrato', $dados, true);
    
    registraLog('Inserção de nova informação pessoa.','i','tb_informacao_geral_contrato', $insertID, "velocidade_reduzida: $velocidade_reduzida | acesso_controladoras: $acesso_controladoras | roteadores: $roteadores | computadores: $computadores | classificacao_atendimento_sistema_gestao: $classificacao_atendimento_sistema_gestao | selecao_finalizacao_sistema_gestao: $selecao_finalizacao_sistema_gestao | bloqueados: $bloqueados | contratacao_servico: $contratacao_servico | troca_plano: $troca_plano | troca_endereco: $troca_endereco | cancelamentos: $cancelamentos | situacoes_adversas: $situacoes_adversas | informacoes_adicionais: $informacoes_adicionais | troca_comodo: $troca_comodo | segunda_via: $segunda_via | descontos: $descontos | servico_telefonia: $servico_telefonia | tv_assinatura: $tv_assinatura | suporte_dispositivos_moveis: $suporte_dispositivos_moveis | posicao_os: $posicao_os | posicao_instalacao: $posicao_instalacao | tipo_os: $tipo_os | confirmacao_cadastro_cliente: $confirmacao_cadastro_cliente | suporte_acesso_lento: $suporte_acesso_lento | nao_cliente: $nao_cliente | tipo_equipamento: $tipo_equipamento | monitoramento: $monitoramento | horarios_monitoramento: $horarios_monitoramento | velocidade_reduzida_bool: $velocidade_reduzida_bool | acesso_controladoras_bool: $acesso_controladoras_bool | roteadores_bool: $roteadores_bool | computadores_bool: $computadores_bool | suporte_dispositivos_moveis_bool: $suporte_dispositivos_moveis_bool | servico_telefonia_bool: $servico_telefonia_bool | tv_assinatura_bool: $tv_assinatura_bool | id_contrato_plano_pessoa: $id_contrato_plano_pessoa | inativo_cancelado: $inativo_cancelado |servico_streaming: $servico_streaming | servico_streaming_bool: $servico_streaming_bool");

    $opcoes_array = array(
        '0' => 'Não',
        '1' => 'Sim',
        '2' => 'Não informado'
    );

    // QUADRO INFORMATIVO HISTORICO
    inserirHistorico($id_contrato_plano_pessoa, 1, "Inserção de informações gerais.","velocidade_reduzida: $velocidade_reduzida | acesso_controladoras: $acesso_controladoras | roteadores: $roteadores | computadores: $computadores | classificacao_atendimento_sistema_gestao: $classificacao_atendimento_sistema_gestao | selecao_finalizacao_sistema_gestao: $selecao_finalizacao_sistema_gestao | bloqueados: $bloqueados | contratacao_servico: $contratacao_servico | troca_plano: $troca_plano | troca_endereco: $troca_endereco | cancelamentos: $cancelamentos | situacoes_adversas: $situacoes_adversas | informacoes_adicionais: $informacoes_adicionais | troca_comodo: $troca_comodo | segunda_via: $segunda_via | descontos: $descontos | servico_telefonia: $servico_telefonia | tv_assinatura: $tv_assinatura | suporte_dispositivos_moveis: $suporte_dispositivos_moveis | posicao_os: $posicao_os | posicao_instalacao: $posicao_instalacao | tipo_os: $tipo_os | confirmacao_cadastro_cliente: $confirmacao_cadastro_cliente | suporte_acesso_lento: $suporte_acesso_lento | nao_cliente: $nao_cliente | tipo_equipamento: $tipo_equipamento | monitoramento: $monitoramento | horarios_monitoramento: $horarios_monitoramento | velocidade_reduzida_bool: '".$opcoes_array[$velocidade_reduzida_bool]."' | acesso_controladoras_bool: '".$opcoes_array[$acesso_controladoras_bool]."' | roteadores_bool: '".$opcoes_array[$roteadores_bool]."' | computadores_bool: '".$opcoes_array[$computadores_bool]."' | suporte_dispositivos_moveis_bool: '".$opcoes_array[$suporte_dispositivos_moveis_bool]."' | servico_telefonia_bool: '".$opcoes_array[$servico_telefonia_bool]."' | tv_assinatura_bool: '".$opcoes_array[$tv_assinatura_bool]."' | inativo_cancelado: $inativo_cancelado |servico_streaming: $servico_streaming | servico_streaming_bool: '".$opcoes_array[$servico_streaming_bool]."'", 5);
    
    if($ativacao == 1){
        if($pular){
            header("location: /api/iframe?token=$request->token&view=localizacao-form&alterar=$pular&ativacao=1&dado_posterior=$insertID&id_contrato=$id_contrato_plano_pessoa");
        }else if($voltar && $salvar != 1){
            header("location: /api/iframe?token=$request->token&view=sistema-gestao-form&alterar=$voltar&ativacao=1&dado_posterior=$insertID&id_contrato=$id_contrato_plano_pessoa");
        }else{
            header("location: /api/iframe?token=$request->token&view=localizacao-form&ativacao=1&dado_posterior=$insertID&id_contrato=$id_contrato_plano_pessoa");
        }
        exit;
    }else{
        $alert = ('Item inserido com sucesso!');
        $alert_type = 's';                header("location: /api/iframe?token=$request->token&view=informacoes-gerais-busca");
        exit;
    }
}

function alterar($id, $velocidade_reduzida, $acesso_controladoras, $roteadores, $computadores, $classificacao_atendimento_sistema_gestao, $selecao_finalizacao_sistema_gestao, $bloqueados, $contratacao_servico, $troca_plano, $troca_endereco, $cancelamentos, $situacoes_adversas, $informacoes_adicionais, $troca_comodo, $segunda_via, $descontos, $servico_telefonia, $tv_assinatura, $suporte_dispositivos_moveis, $posicao_os, $posicao_instalacao, $tipo_os, $confirmacao_cadastro_cliente, $suporte_acesso_lento, $nao_cliente, $tipo_equipamento, $monitoramento, $horarios_monitoramento, $velocidade_reduzida_bool, $acesso_controladoras_bool, $roteadores_bool, $computadores_bool, $suporte_dispositivos_moveis_bool, $servico_telefonia_bool, $tv_assinatura_bool, $id_contrato_plano_pessoa, $ativacao, $pular, $voltar, $salvar, $inativo_cancelado, $servico_streaming, $servico_streaming_bool){

    $dados = array(
        'velocidade_reduzida' => $velocidade_reduzida,
        'acesso_controladoras' => $acesso_controladoras,
        'roteadores' => $roteadores,
        'computadores' => $computadores,
        'classificacao_atendimento_sistema_gestao' => $classificacao_atendimento_sistema_gestao,
        'selecao_finalizacao_sistema_gestao' => $selecao_finalizacao_sistema_gestao,
        'bloqueados' => $bloqueados,
        'contratacao_servico' => $contratacao_servico,
        'troca_plano' => $troca_plano,
        'troca_endereco' => $troca_endereco,
        'cancelamentos' => $cancelamentos,
        'situacoes_adversas' => $situacoes_adversas,
        'informacoes_adicionais' => $informacoes_adicionais,
        'troca_comodo' => $troca_comodo,
        'segunda_via' => $segunda_via,
        'descontos' => $descontos,
        'servico_telefonia' => $servico_telefonia,
        'tv_assinatura' => $tv_assinatura,
        'suporte_dispositivos_moveis' => $suporte_dispositivos_moveis,
        'posicao_os' => $posicao_os,
        'posicao_instalacao' => $posicao_instalacao,
        'tipo_os' => $tipo_os,
        'confirmacao_cadastro_cliente' => $confirmacao_cadastro_cliente,
        'suporte_acesso_lento' => $suporte_acesso_lento,
        'nao_cliente' => $nao_cliente,
        'tipo_equipamento' => $tipo_equipamento,
        'monitoramento' => $monitoramento,
        'horarios_monitoramento' => $horarios_monitoramento,
        'velocidade_reduzida_bool' => $velocidade_reduzida_bool,
        'acesso_controladoras_bool' => $acesso_controladoras_bool,
        'roteadores_bool' => $roteadores_bool,
        'computadores_bool' => $computadores_bool,
        'suporte_dispositivos_moveis_bool' => $suporte_dispositivos_moveis_bool,
        'servico_telefonia_bool' => $servico_telefonia_bool,
        'tv_assinatura_bool' => $tv_assinatura_bool,
        'id_contrato_plano_pessoa' => $id_contrato_plano_pessoa,
        'inativo_cancelado' => $inativo_cancelado,
        'servico_streaming' => $servico_streaming,
        'servico_streaming_bool' => $servico_streaming_bool

    );

    DBUpdate('', 'tb_informacao_geral_contrato', $dados, "id_informacao_geral_contrato = $id");

    registraLog('Alteração de informacao geral pessoa.','a','tb_informacao_geral_contrato', $id, "velocidade_reduzida: $velocidade_reduzida, acesso_controladoras: $acesso_controladoras | roteadores: $roteadores | computadores: $computadores | classificacao_atendimento_sistema_gestao: $classificacao_atendimento_sistema_gestao | selecao_finalizacao_sistema_gestao: $selecao_finalizacao_sistema_gestao | bloqueados: $bloqueados | contratacao_servico: $contratacao_servico | troca_plano: $troca_plano | troca_endereco: $troca_endereco | cancelamentos: $cancelamentos | situacoes_adversas: $situacoes_adversas | informacoes_adicionais: $informacoes_adicionais | troca_comodo: $troca_comodo | segunda_via: $segunda_via | descontos: $descontos | servico_telefonia: $servico_telefonia | tv_assinatura: $tv_assinatura | suporte_dispositivos_moveis: $suporte_dispositivos_moveis | posicao_os: $posicao_os | posicao_instalacao: $posicao_instalacao | tipo_os: $tipo_os | confirmacao_cadastro_cliente: $confirmacao_cadastro_cliente | suporte_acesso_lento: $suporte_acesso_lento | nao_cliente: $nao_cliente | tipo_equipamento: $tipo_equipamento | monitoramento: $monitoramento | horarios_monitoramento: $horarios_monitoramento | velocidade_reduzida_bool: $velocidade_reduzida_bool | acesso_controladoras_bool: $acesso_controladoras_bool | roteadores_bool: $roteadores_bool | computadores_bool: $computadores_bool | suporte_dispositivos_moveis_bool: $suporte_dispositivos_moveis_bool | servico_telefonia_bool: $servico_telefonia_bool | tv_assinatura_bool: $tv_assinatura_bool | id_contrato_plano_pessoa: $id_contrato_plano_pessoa | inativo_cancelado: $inativo_cancelado |servico_streaming: $servico_streaming | servico_streaming_bool: $servico_streaming_bool");

    $opcoes_array = array(
        '0' => 'Não',
        '1' => 'Sim',
        '2' => 'Não informado'
    );

    // QUADRO INFORMATIVO HISTORICO
    inserirHistorico($id_contrato_plano_pessoa, 2, "Alteração de informações gerais.","velocidade_reduzida: $velocidade_reduzida | acesso_controladoras: $acesso_controladoras | roteadores: $roteadores | computadores: $computadores | classificacao_atendimento_sistema_gestao: $classificacao_atendimento_sistema_gestao | selecao_finalizacao_sistema_gestao: $selecao_finalizacao_sistema_gestao | bloqueados: $bloqueados | contratacao_servico: $contratacao_servico | troca_plano: $troca_plano | troca_endereco: $troca_endereco | cancelamentos: $cancelamentos | situacoes_adversas: $situacoes_adversas | informacoes_adicionais: $informacoes_adicionais | troca_comodo: $troca_comodo | segunda_via: $segunda_via | descontos: $descontos | servico_telefonia: $servico_telefonia | tv_assinatura: $tv_assinatura | suporte_dispositivos_moveis: $suporte_dispositivos_moveis | posicao_os: $posicao_os | posicao_instalacao: $posicao_instalacao | tipo_os: $tipo_os | confirmacao_cadastro_cliente: $confirmacao_cadastro_cliente | suporte_acesso_lento: $suporte_acesso_lento | nao_cliente: $nao_cliente | tipo_equipamento: $tipo_equipamento | monitoramento: $monitoramento | horarios_monitoramento: $horarios_monitoramento | velocidade_reduzida_bool: '".$opcoes_array[$velocidade_reduzida_bool]."' | acesso_controladoras_bool: '".$opcoes_array[$acesso_controladoras_bool]."' | roteadores_bool: '".$opcoes_array[$roteadores_bool]."' | computadores_bool: '".$opcoes_array[$computadores_bool]."' | suporte_dispositivos_moveis_bool: '".$opcoes_array[$suporte_dispositivos_moveis_bool]."' | servico_telefonia_bool: '".$opcoes_array[$servico_telefonia_bool]."' | tv_assinatura_bool: '".$opcoes_array[$tv_assinatura_bool]."' | inativo_cancelado: $inativo_cancelado |servico_streaming: $servico_streaming | servico_streaming_bool: '".$opcoes_array[$servico_streaming_bool]."'", 5);

    if($ativacao == 1){
        if($pular){
            header("location: /api/iframe?token=$request->token&view=localizacao-form&alterar=$pular&ativacao=1&dado_posterior=$id&id_contrato=$id_contrato_plano_pessoa");
        }else if($voltar && $salvar != 1){
            header("location: /api/iframe?token=$request->token&view=sistema-gestao-form&alterar=$voltar&ativacao=1&dado_posterior=$id&id_contrato=$id_contrato_plano_pessoa");
        }else{
            header("location: /api/iframe?token=$request->token&view=localizacao-form&ativacao=1&dado_posterior=$id&id_contrato=$id_contrato_plano_pessoa");
        }
        exit;
    }else{
        $alert = ('Item alterado com sucesso!', 's');
        header("location: /api/iframe?token=$request->token&view=informacoes-gerais-busca");
        exit;
    }
}

function excluir($id, $exclui_ativacao, $id_contrato){

    if($exclui_ativacao == 1){

        $dados = DBRead('', 'tb_localizacao_contrato', "WHERE id_contrato_plano_pessoa = $id_contrato");

        $query = "DELETE FROM tb_informacao_geral_contrato WHERE id_informacao_geral_contrato = $id";
        $link = DBConnect('');
        $result = @mysqli_query($link, $query);
        DBClose($link);

        registraLog('Exclusão de informações gerais.','e','tb_informacao_geral_contrato',$id,'');

        // QUADRO INFORMATIVO HISTORICO
        inserirHistorico($id_contrato, 3, "Exclusão de informações gerais", "Excluiu dados", 5);

        if(!$result){
            $alert = ('Erro ao excluir item!');
            $alert_type = 'd';        }else{
            $alert = ('Item excluído com sucesso!');
    $alert_type = 's';        }

        if($dados){
            $dado_posterior = $dados[0]['id_localizacao_contrato'];
            header("location: /api/iframe?token=$request->token&view=localizacao-form&alterar=$dado_posterior&ativacao=1&id_contrato=$id_contrato");
        }else{
            header("location: /api/iframe?token=$request->token&view=localizacao-form&ativacao=1&id_contrato=$id_contrato");
        }
        exit;
    }else{

        $id_contrato = DBRead('', 'tb_informacao_geral_contrato', "WHERE id_informacao_geral_contrato = ".$id."");
        $id_contrato = $id_contrato[0]['id_contrato_plano_pessoa'];
        
        $query = "DELETE FROM tb_informacao_geral_contrato WHERE id_informacao_geral_contrato = $id";
        $link = DBConnect('');
        $result = @mysqli_query($link, $query);
        DBClose($link);

        registraLog('Exclusão de informacao geral.','e','tb_informacao_geral_contrato',$id,'');
        // QUADRO INFORMATIVO HISTORICO
        inserirHistorico($id_contrato, 3, "Exclusão de informações gerais", "Excluiu dados", 5);

        if(!$result){
            $alert = ('Erro ao excluir item!');
            $alert_type = 'd';        }else{
            $alert = ('Item excluído com sucesso!');
    $alert_type = 's';        }

        header("location: /api/iframe?token=$request->token&view=informacoes-gerais-busca");
        exit;
    }
}