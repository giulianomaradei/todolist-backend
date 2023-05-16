<?php
include (__DIR__."IntegracaoTipoSistema.php");

$id_setor_default = (!empty($_GET['id_setor_default'])) ? $_GET['id_setor_default'] : '';

$id_contrato_plano_pessoa = (!empty($_GET['id_contrato_plano_pessoa'])) ? $_GET['id_contrato_plano_pessoa'] : '';
$acao = (!empty($_GET['acao'])) ? $_GET['acao'] : '';
//Nome do assinante no sistema IXC
$nome_assinante = (!empty($_GET['nome_assinante'])) ? $_GET['nome_assinante'] : '';
//id do assinante no sistema IXC
$id_assinante = (!empty($_GET['id_assinante'])) ? $_GET['id_assinante'] : '';
//id da cidade no sistema IXC
$id_cidade = (!empty($_GET['id_cidade'])) ? $_GET['id_cidade'] : '';
//id do atendimento no sistema IXC
$id_atendimento = (!empty($_GET['id_atendimento'])) ? $_GET['id_atendimento'] : '';
//id do assunto do atendimento ou da O.S. no sistema IXC
$id_assunto = (!empty($_GET['id_assunto'])) ? $_GET['id_assunto'] : '';
//id da filial do cliente cadastrado no sistema IXC
$id_filial = (!empty($_GET['id_filial'])) ? $_GET['id_filial'] : '';
//id do atendimento que será inserido uma ação de finalização no sistema IXC
$inserir_atendimento_existente = (!empty($_GET['inserir_atendimento_existente'])) ? $_GET['inserir_atendimento_existente'] : '0';
//id do contrato do cliente do provedor no sistema IXC
$id_contrato_ixc = (!empty($_GET['id_contrato'])) ? $_GET['id_contrato'] : '0';
$id_login = (!empty($_GET['id_login'])) ? $_GET['id_login'] : '0';

//Campos necessários para a persistencia de um atendimento ou os no sistema de gestão IXC, VERIFICAR!!!
$protocolo = (!empty($_GET['protocolo'])) ? $_GET['protocolo'] : '0';
$origem_endereco = (!empty($_GET['origem_endereco'])) ? $_GET['origem_endereco'] : 'C';
$prioridade = (!empty($_GET['prioridade'])) ? $_GET['prioridade'] : 'N';
$id_ticket_origem = (!empty($_GET['id_ticket_origem'])) ? $_GET['id_ticket_origem'] : '0';
$mensagem = (!empty($_GET['mensagem'])) ? $_GET['mensagem'] : '0';
$su_status = (!empty($_GET['su_status'])) ? $_GET['su_status'] : '0';
$status = (!empty($_GET['status'])) ? $_GET['status'] : '0';
$cadastro_os = (!empty($_GET['cadastro_os'])) ? $_GET['cadastro_os'] : '0';
//$id_ticket_setor = (!empty($_GET['id_ticket_setor'])) ? $_GET['id_ticket_setor'] : '0';
$id_setor = (!empty($_GET['id_setor'])) ? $_GET['id_setor'] : '0';
$id_setor2 = (!empty($_GET['id_setor2'])) ? $_GET['id_setor2'] : '0';
$id_assunto = (!empty($_GET['id_assunto'])) ? $_GET['id_assunto'] : '0';
$id_processo = (!empty($_GET['id_processo'])) ? $_GET['id_processo'] : '0';
$situacao = (!empty($_GET['situacao'])) ? $_GET['situacao'] : '0';
$plano_venda = (!empty($_GET['plano_venda'])) ? $_GET['plano_venda'] : 0;

$id_os = (!empty($_GET['id_os'])) ? $_GET['id_os'] : 0;
$id_atendimento = (!empty($_GET['id_atendimento'])) ? $_GET['id_atendimento'] : 0;

//titulo = descricao assunto
$titulo = (!empty($_GET['titulo'])) ? $_GET['titulo'] : '0';

$id_integracao_atendimento_ixc = (!empty($_GET['id_integracao_atendimento_ixc'])) ? $_GET['id_integracao_atendimento_ixc'] : '0';

$tipo_boleto = (!empty($_GET['tipo_boleto'])) ? $_GET['tipo_boleto'] : '';

$username = (!empty($_GET['username'])) ? $_GET['username'] : '';

$status_atendimento = (!empty($_GET['status_atendimento'])) ? $_GET['status_atendimento'] : 'abertos';

$classificacao = (!empty($_GET['classificacao'])) ? $_GET['classificacao'] : '0';

$integracaoTipoSistema = new IntegracaoTipoSistema();

//Verifica a configuração dos recursos permitidos
$reiniciar_conexao = 0;
$diagnosticar_conexao = 0;
$desbloquear_contrato = 0;
$enviar_boleto = 0;
$acesso_login = 0;
$desbloquear_vel_reduzida = 0;

$integracao_recursos = DBRead('', 'tb_integracao_recursos', "WHERE id_contrato_plano_pessoa = '$id_contrato_plano_pessoa'");
if($integracao_recursos){
    foreach($integracao_recursos as $recurso){
        if($recurso['nome'] == 'reiniciar_conexao'){
            $reiniciar_conexao = $recurso['ativo'];
        }
        if($recurso['nome'] == 'diagnosticar_conexao'){
            $diagnosticar_conexao = $recurso['ativo'];
        }
        if($recurso['nome'] == 'desbloquear_contrato'){
            $desbloquear_contrato = $recurso['ativo'];
        }
        if($recurso['nome'] == 'enviar_boleto'){
            $enviar_boleto = $recurso['ativo'];
        }
        if($recurso['nome'] == 'acesso_login'){
            $acesso_login = $recurso['ativo'];
        }
        if($recurso['nome'] == 'desbloquear_vel_reduzida'){
            $desbloquear_vel_reduzida = $recurso['ativo'];
        }
    }
}

//ixc
if($acao == "buscar_assinante"){
    $retorno = $integracaoTipoSistema->buscaAssinante($id_contrato_plano_pessoa, $nome_assinante, $id_assinante);
    echo json_encode($retorno);
}

//ixc
if($acao == "busca_cidade"){
    $retorno = $integracaoTipoSistema->buscaCidadeAssinante($id_contrato_plano_pessoa, $id_cidade);
    echo json_encode($retorno);
}

//ixc
if($acao == "busca_contrato_cliente"){
    $retorno = $integracaoTipoSistema->buscaContratoCliente($id_contrato_plano_pessoa, $id_contrato_ixc);
    echo json_encode($retorno);
}

//ixc
if($acao == "busca_contrato_cliente_assinante"){
    $retorno = $integracaoTipoSistema->buscaContratoClienteAssinante($id_contrato_plano_pessoa, $id_assinante);
    echo json_encode($retorno);
}

//ixc
if($acao == "gerar_protocolo_atendimento"){
    $retorno = $integracaoTipoSistema->geraProtocoloAtendimento($id_contrato_plano_pessoa);
    echo json_encode($retorno);
}

//ixc
if($acao == 'busca_atendimentos'){
    $retorno = $integracaoTipoSistema->buscaAtendimentos($id_contrato_plano_pessoa, $status_atendimento, $id_assinante);
    echo json_encode($retorno);
}

//ixc
if($acao == 'busca_os'){
    $retorno = $integracaoTipoSistema->buscaAtendimentosOS($id_contrato_plano_pessoa, $status_atendimento, $id_assinante);
    echo json_encode($retorno);
}

//ixc
if($acao == 'busca_assunto'){
    $retorno = $integracaoTipoSistema->buscaAssunto($id_contrato_plano_pessoa, $id_assunto);
    echo json_encode($retorno);
}

//ixc
//busca departamentos
if($acao == 'busca_setor'){
    $retorno = $integracaoTipoSistema->buscaSetor($id_contrato_plano_pessoa, $id_setor);
    echo json_encode($retorno);
}

//ixc
//busca setores
if($acao == 'busca_setor2'){
    $retorno = $integracaoTipoSistema->buscaSetor2($id_contrato_plano_pessoa, $id_setor);
    echo json_encode($retorno);
}

//ixc
if($acao == 'busca_filial'){
    $retorno = $integracaoTipoSistema->buscaFilial($id_contrato_plano_pessoa, $id_filial);
    echo json_encode($retorno);
}

//ixc
if($acao == 'busca_tecnico'){
    $retorno = $integracaoTipoSistema->buscaTecnico($id_contrato_plano_pessoa, $id_tecnico);
    echo json_encode($retorno);
}

//ixc
if($acao == 'busca_login'){
    $retorno = $integracaoTipoSistema->buscaLogin($id_contrato_plano_pessoa, $id_contrato_ixc);
    echo json_encode($retorno);
}

//ixc
if($acao == 'busca_sinal'){
    $retorno = $integracaoTipoSistema->buscaSinal($id_contrato_plano_pessoa, $id_login);
    echo json_encode($retorno);
}

//ixc
if($acao == 'desbloqueio_manual' && $desbloquear_contrato == 1){

    $retorno = $integracaoTipoSistema->desbloqueioManual($id_contrato_plano_pessoa, $id_contrato_ixc);
    echo json_encode($retorno);
}

//ixc
/*if ($acao == 'liberar_temporariamente' && $desbloquear_contrato == 1) {
    $retorno = $integracaoTipoSistema->LiberarTemporariamente($id_contrato_plano_pessoa, $id_contrato_ixc);
    echo json_encode($retorno);
}*/

//ixc
if($acao == 'busca_historico_conexao'){
    $retorno = $integracaoTipoSistema->buscaHistoricoConexao($id_cliente, $id_contrato_plano_pessoa);
    echo json_encode($retorno);
}

//ixc
if($acao == 'verifica_situacao'){
    $retorno = $integracaoTipoSistema->verificaSituacao($classificacao, $situacao, $id_contrato_plano_pessoa, $id_setor_default);
    echo json_encode($retorno);
}

//ixc
if($acao == 'envia_boleto' && $enviar_boleto == 1){
    $retorno = $integracaoTipoSistema->enviaBoleto($id_contrato_plano_pessoa, $id_assinante, $tipo_boleto);
    echo json_encode($retorno);
}

//ixc
if($acao == 'busca_planos_velocidade'){
    $retorno = $integracaoTipoSistema->buscaPlanosVelocidade($id_contrato_plano_pessoa, $id_contrato_ixc, $plano_venda);
    echo json_encode($retorno);
}

//ixc
if($acao == 'busca_evento_analisar'){
    $retorno = $integracaoTipoSistema->buscaEventoAnalisar($id_contrato_plano_pessoa, $id_os);
    echo json_encode($retorno);
}

//ixc
if($acao == 'busca_evento_agendar'){
    $retorno = $integracaoTipoSistema->buscaEventoAgendar($id_contrato_plano_pessoa, $id_os);
    echo json_encode($retorno);
}

//ixc
if($acao == 'busca_interacao_atendimento'){
    $retorno = $integracaoTipoSistema->buscaInteracaoAtendimento($id_contrato_plano_pessoa, $id_atendimento);
    echo json_encode($retorno);
}

//ixc
if($acao == 'desconectar_login' && $reiniciar_conexao == 1){
    $retorno = $integracaoTipoSistema->desconectarLogin($id_contrato_plano_pessoa, $id_login);
    echo json_encode($retorno);
}

//ixc
if($acao == 'gerar_diagnostico' && $diagnosticar_conexao == 1){
    $retorno = $integracaoTipoSistema->gerarDiagnostico($id_contrato_plano_pessoa, $username);
    echo json_encode($retorno);
}

//ixc
if($acao == 'zerar_mac'){
    $retorno = $integracaoTipoSistema->zerarMac($id_contrato_plano_pessoa, $id_login);
    echo json_encode($retorno);
}

if($acao == 'busca_processo'){
    $retorno = $integracaoTipoSistema->buscaProcesso($id_contrato_plano_pessoa, $id_processo);
    echo json_encode($retorno);
}

if($acao == 'busca_contas_receber'){
    $retorno = $integracaoTipoSistema->buscaContasReceber($id_contrato_plano_pessoa, $id_assinante);
    echo json_encode($retorno);
}

//ixc
if($acao == 'desbloquear_vel_reduzida' && $desbloquear_vel_reduzida == 1){

    $retorno = $integracaoTipoSistema->liberarReducaoVelocidade($id_contrato_plano_pessoa, $id_contrato_ixc);
    echo json_encode($retorno);
}
//ixc
/*if($acao == 'busca_evento_finalizar'){
    $retorno = $integracaoTipoSistema->buscaEventoFinalizar($id_contrato_plano_pessoa, $id_os);
    echo json_encode($retorno);
}*/
//////////////////////////////////////////////////////////////////////////////////////////////////////////////