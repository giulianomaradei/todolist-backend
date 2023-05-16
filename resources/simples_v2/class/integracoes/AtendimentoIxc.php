<?php
$inserir_mesmo_atendimento = (!empty($_POST['inserir_mesmo_atendimento'])) ? $_POST['inserir_mesmo_atendimento'] : '';

$protocolo = (!empty($_POST['protocolo_integracao_integracao'])) ? $_POST['protocolo_integracao_integracao'] : ''; //verificar a geração do protocolo
$id_cliente = (!empty($_POST['id_cliente_integracao'])) ? $_POST['id_cliente_integracao'] : 0; //atendimento-form.php | <input type="hidden" id="id_assinante" name="id_cliente_integracao" />
$id_assunto = (!empty($_POST['id_assunto'])) ? $_POST['id_assunto'] : 0; //integracoes/atendimento-form-arvore-ixc | <select class="form-control" name='id_assunto' id='assunto'>
$titulo = 'Atendimento Belluno'; //Capturar essa informação na opção clicada do passo 92 do fluxo no atendimento do sistema Simples
$origem_endereco = (!empty($_POST['origem_endereco'])) ? $_POST['origem_endereco'] : 'C'; //atendimento-form-arvore-ixc
$prioridade = (!empty($_POST['prioridade'])) ? $_POST['prioridade'] : 'N'; //atendimento-form-arvore-ixc

$mensagem = (!empty($_POST['os'])) ? $_POST['os'] : ''; //capturado do campo de script na finalização do atendimento juntamente com a situação para o atendimento selecionado pelo atendente
$clip_contato_int = (!empty($_POST['clip-contato-int'])) ? $_POST['clip-contato-int'] : '';
$clip_fone1_int = (!empty($_POST['clip-fone1-int'])) ? $_POST['clip-fone1-int'] : '';
$clip_fone2_int = (!empty($_POST['clip-fone2-int'])) ? $_POST['clip-fone2-int'] : '';

$clip_assinante_int = (!empty($_POST['clip-assinante-int'])) ? $_POST['clip-assinante-int'] : '';
$clip_cpf_int = (!empty($_POST['clip-cpf-int'])) ? $_POST['clip-cpf-int'] : '';
$clip_adicional_int = (!empty($_POST['clip-adicional-int'])) ? $_POST['clip-adicional-int'] : '';
$clip_protocolo_int = (!empty($_POST['clip-protocolo-int'])) ? $_POST['clip-protocolo-int'] : '';
$clip_atendente_int = (!empty($_POST['clip-atendente-int'])) ? $_POST['clip-atendente-int'] : '';

$mensagem = $clip_contato_int . "\n" . $clip_fone1_int . "\n" . $clip_fone2_int . "\n" . $clip_assinante_int . "\n" . $clip_cpf_int . "\n" . $clip_adicional_int . "\n" . $clip_protocolo_int . "\n" . $clip_atendente_int . "\n\n" . $mensagem;

$su_status = 'N'; //N => Status padrão para novo atendimento
$status = 'T'; //T => Status padrão para novo atendimento
$tipoRetorno = true;
//$salvo = (!empty($_POST['salvo'])) ? $_POST['salvo'] : '';
$id_contrato_plano_pessoa = (!empty($_POST['id_contrato_plano_pessoa'])) ? $_POST['id_contrato_plano_pessoa'] : 0;
$id_filial = (!empty($_POST['id_filial'])) ? $_POST['id_filial'] : 0; //atendimento-form-arvore-ixc
$id_tecnico = (!empty($_POST['tecnico_responsavel'])) ? $_POST['tecnico_responsavel'] : 0; //Técnico do provedor responsável pelo novo atendimento ou ordem de serviço; atendimento-form-arvore-ixc

$id_atendimento_sistema_gestao = (!empty($_POST['id_atendimento_sistema_gestao'])) ? $_POST['id_atendimento_sistema_gestao'] : 0;
$situacao = (!empty($_POST['situacao'])) ? $_POST['situacao'] : 0;
$data_inicio = (!empty($_POST['data_inicio'])) ? $_POST['data_inicio'] : '0000-00-00 00:00:00';
$data_final = getDataHora();
//em Atendimento esse dados é referente a DepartamentoAtendimento e em ordem de serviço e analisar ordem de serviço esse dado é referente a Setor

$id_setor = (!empty($_POST['id_setor'])) ? $_POST['id_setor'] : 0; //setor ou departamento | atendimento-form-arvore-ixc

$id_atendimento = (!empty($_POST['id_atendimento'])) ? $_POST['id_atendimento'] : 0; //Busca o id do atendimento no sistema Simples pela variável post da url do navegador
$id_login = (!empty($_POST['id_login'])) ? $_POST['id_login'] : 0; //Busca o id do login do cliente no sistema Simples pela variável get da url do navegador
$id_contrato_ixc = (!empty($_POST['id_contrato'])) ? $_POST['id_contrato'] : 0;
$nome_assinante = (!empty($_POST['assinante'])) ? $_POST['assinante'] : '';

$classificacao = (!empty($_POST['classificacao'])) ? $_POST['classificacao'] : 0; // Campo que determina com qual recurso o sistema vai salvar o fluxo, se em atendimento ou ordem de serviço | 1 = Ordem de serviço; 2 = Atendimento
$classificacao_evento = (!empty($_POST['classificacao_evento'])) ? $_POST['classificacao_evento'] : 0;

$operacao = (!empty($_POST['operacao'])) ? $_POST['operacao'] : ''; //Flag que indica se atendimento ou o.s. deve ser salva no caso dos dados vierem do fluxo de atendimento, ou se deve ser atualizado no caso em que os dados vem da tela de correção dos atendimentos pendentes que por alguma razão não pode ser salva no atendimento.
$id_integracao_atendimento_ixc = (!empty($_POST['id_integracao_atendimento_ixc'])) ? $_POST['id_integracao_atendimento_ixc'] : '';

$evento = (!empty($_POST['evento'])) ? $_POST['evento'] : ''; //Sinaliza qual recurso utilizar quando o atendimento for vinculado à um atendimento já existente, se deve-se utilizar AnalisarOrdemServico ou FinalizarOrdemServico (recursos suportados somente por ordens de serviço)

$processo = (!empty($_POST['processo'])) ? $_POST['processo'] : ''; //Campo processo é utilizado se configurado em tb_integracao_campos_requeridos com o valor 1, somente usado em 'Atendimento'

$atendimento_pendente = (!empty($_POST['atendimento_pendente'])) ? $_POST['atendimento_pendente'] : '';

//Trás campos requeridos como obrigatórios mesmo não sendo obrigatórios para o ixc
$campos_requeridos = DBRead('', 'tb_integracao_campos_requeridos', "WHERE id_contrato_plano_pessoa = '".$id_contrato_plano_pessoa."'");

//Verifica se o campo técnico responsavel é obrigatório e se for e não vier o id_tecnico corretamente o atendimento é salvo em pendentes
if($campos_requeridos[0]['nome'] == "tecnico_responsavel" && $campos_requeridos[0]['requerido'] == "1" && $id_tecnico == 0){
    $sem_tecnico = "1";
}

//////////////////// Teste para verificar qual recurso utilizar no fim do fluxo //////////////////////
$parametros = DBRead('', 'tb_integracao_valores_tipo_parametro a', "INNER JOIN tb_integracao_parametro b ON a.id_integracao_parametro = b.id_integracao_parametro INNER JOIN tb_integracao_contrato_parametro c ON b.id_integracao_parametro = c.id_integracao_parametro INNER JOIN tb_integracao_contrato d ON c.id_integracao_contrato = d.id_integracao_contrato WHERE d.id_contrato_plano_pessoa = '$id_contrato_plano_pessoa'");

$salvaEmOS = "'nao'";
if($parametros){
    foreach($parametros as $parametro){
        if($parametro['codigo'] == "cadastroAtendimentoVinculado" && $parametro['valor'] == "sim"){
            $salvaEmOS = "'sim'";
        }
    }
}
/////////////////////////////////////////////////////////////////////////////////////////////////////
$descricao_situacao = array(
    4 => "ATENDIMENTO ENCAMINHADO AO SETOR RESPONSÁVEL.",
    3 => "ATENDIMENTO ENCERRADO.",
    7 => "ATENDIMENTO VINCULADO A OS JÁ EXISTENTE."
);
$mensagem = $mensagem . "\n" . $descricao_situacao[$situacao];

//Objeto Atendimento de integração
$atendimentoIntegracao = new AtendimentoIntegracao($protocolo, $id_cliente, $id_assunto, $titulo, $origem_endereco, $prioridade, $mensagem, $su_status, $status, $tipoRetorno, $id_contrato_plano_pessoa, $id_filial, $id_tecnico, $data_inicio, $data_final, $id_setor, $id_atendimento, $id_monitoramento_belluno, $situacao, $id_login, $id_contrato_ixc, $operacao, $id_integracao_atendimento_ixc, $nome_assinante, $processo);

//Objeto Ordem de serviço de integração
$ordemServicoIntegracao = new OrdemServicoIntegracao($titulo, $nome_assinante, $tipo, $id_ticket, $protocolo, $id_assunto, $id_cliente, $id_estrutura, $id_filial, $id_login, $id_contrato_ixc, $origem_endereco, $origem_endereco_estrutura, $latitude, $longitude, $prioridade, $melhor_horario_agenda, $id_setor, $id_tecnico, $mensagem, $idx, $status, $gera_comissao, $liberado, $impresso, $id_su_diagnostico, $id_cidade, $bairro, $endereco, $data_abertura, $data_inicio, $data_hora_analise, $data_agenda, $data_hora_encaminhado, $data_hora_assumido, $data_hora_execucao,$data_final, $data_fechamento, $mensagem_resposta, $justificativa_sla_atrasado, $valor_total, $valor_outras_despesas, $valor_unit_comissao, $valor_total_comissao, $tipoRetorno, $id_contrato_plano_pessoa, $situacao, $operacao, $id_integracao_atendimento_ixc, $id_atendimento, $id_monitoramento_belluno, $id_atendimento_sistema_gestao);

//////////////////////////// verifica sistema de gestao
require "ixc/Cliente.php";
require "ixc/Atendimento.php";
require "ixc/Usuarios.php";

$cliente = new Integracao\Ixc\Cliente();

$nome_assinante = trim($nome_assinante);

$retorno = $cliente->get('cliente.razao', $nome_assinante, 'L', '1', '20000', 'cliente.id', 'desc', true, $id_contrato_plano_pessoa);
////////////////////////// verifica sistema de gestao

if ($situacao == 7 && $salvaEmOS == "'sim'") {
    
    //verificar
    //if (($classificacao_evento == 1 || $classificacao == 1) && $sem_tecnico != "1") { original
    if (($classificacao_evento == 1 || $classificacao == 1)) {
        $ordemServicoIntegracao->postAcao($evento, $id_atendimento_sistema_gestao);
        
    } else if (($classificacao_evento == 2 || $classificacao == 2)) {

        include_once "ixc/Atendimento.php";
        $atendimento = new Integracao\Ixc\Atendimento();

        $atendimentoIntegracao->postMensagem($id_atendimento_sistema_gestao, $id_cliente, $mensagem, $id_contrato_plano_pessoa, $evento);

    } else {

        $dados = array(
            'protocolo' => $protocolo,
            'id_cliente' => $id_cliente,
            'nome_assinante' => $nome_assinante,
            'id_login' => $id_login,
            'id_contrato' => $id_contrato_ixc,
            'id_assunto' => $id_assunto,
            'titulo' => $titulo,
            'origem_endereco' => $origem_endereco,
            'prioridade' => $prioridade,
            'mensagem' => $mensagem,
            'su_status' => $su_status,
            'status' => $status,
            'id_filial' => $id_filial,
            'setor' => $id_setor,
            'id_tecnico' => $id_tecnico,
            'data_inicio' => $data_inicio,
            'data_final' => $data_final,
            'id_ticket_setor' => $id_setor,
            'id_atendimento_belluno' => $id_atendimento,
            'id_atendimento_sistema_gestao' => $id,
            'cadastroOS' => "atendimento",
            'salvo' => 0,
            'retorno_api' => 'Campo classificação não foi selecionado!', //$retorno_api
            'situacao' => $situacao,
            'id_atendente_simples' => $_SESSION['id_usuario'],
            'contato' => $contato,
            'id_contrato_plano_pessoa' => $id_contrato_plano_pessoa
        );

        $retorno = DBCreate('', 'tb_integracao_atendimento_ixc', $dados, true);

        registraLog('Inserção de atendimento integrado ao ixc.','i','tb_integracao_atendimento_ixc', $retorno,"protocolo: $protocolo | id_cliente: $id_cliente | nome_assinante: $nome_assinante | id_login: $id_login | id_contrato: $id_contrato_ixc | id_assunto: $id_assunto | titulo: $titulo | origem_endereco: $origem_endereco | prioridade: $prioridade | mensagem: $mensagem | su_status: $su_status | status: $status | id_filial: $id_filial | setor: $id_setor | id_atendimento_belluno: $id_atendimento | id_monitoramento_belluno: $id_monitoramento_belluno | id_atendimento_sistema_gestao: $id | cadastroOS:  | salvo: 0 | retorno_api: $retorno_api | situacao: $situacao | id_atendente_simples: $id_usuario | contato: $contato | id_contrato_plano_pessoa: $id_contrato_plano_pessoa");
    }
    
} else {

    $id_usuario = $_SESSION['id_usuario'];

    //if ($classificacao == 2 && $id_setor != '' && $sem_tecnico != "1") { original
    if ($classificacao == 2) {
        $retorno = $atendimentoIntegracao->post();

    } //else if ($classificacao == 1  && $sem_tecnico != "1") {original
        else if ($classificacao == 1) {

        $ordemServicoIntegracao->post();

    } else if (!$classificacao) {

        $dados = array(
            'protocolo' => $protocolo,
            'id_cliente' => $id_cliente,
            'nome_assinante' => $nome_assinante,
            'id_login' => $id_login,
            'id_contrato' => $id_contrato_ixc,
            'id_assunto' => $id_assunto,
            'titulo' => $titulo,
            'origem_endereco' => $origem_endereco,
            'prioridade' => $prioridade,
            'mensagem' => $mensagem,
            'su_status' => $su_status,
            'status' => $status,
            'id_filial' => $id_filial,
            'setor' => $id_setor,
            'id_tecnico' => $id_tecnico,
            'data_inicio' => $data_inicio,
            'data_final' => $data_final,
            'id_ticket_setor' => $id_setor,
            'id_atendimento_belluno' => $id_atendimento,
            'id_atendimento_sistema_gestao' => $id,
            'cadastroOS' => "atendimento",
            'salvo' => 0,
            'retorno_api' => 'Campo classificação não foi selecionado!', //$retorno_api
            'situacao' => $situacao,
            'id_atendente_simples' => $_SESSION['id_usuario'],
            'contato' => $contato,
            'id_contrato_plano_pessoa' => $id_contrato_plano_pessoa
        );

        $retorno = DBCreate('', 'tb_integracao_atendimento_ixc', $dados, true);

        registraLog('Inserção de atendimento integrado ao ixc.','i','tb_integracao_atendimento_ixc', $retorno,"protocolo: $protocolo | id_cliente: $id_cliente | nome_assinante: $nome_assinante | id_login: $id_login | id_contrato: $id_contrato_ixc | id_assunto: $id_assunto | titulo: $titulo | origem_endereco: $origem_endereco | prioridade: $prioridade | mensagem: $mensagem | su_status: $su_status | status: $status | id_filial: $id_filial | setor: $id_setor | id_atendimento_belluno: $id_atendimento | id_monitoramento_belluno: $id_monitoramento_belluno | id_atendimento_sistema_gestao: $id | cadastroOS:  | salvo: 0 | retorno_api: $retorno_api | situacao: $situacao | id_atendente_simples: $id_usuario | contato: $contato | id_contrato_plano_pessoa: $id_contrato_plano_pessoa");

    } else {

        $dados = array(
            'protocolo' => $protocolo,
            'id_cliente' => $id_cliente,
            'nome_assinante' => $nome_assinante,
            'id_login' => $id_login,
            'id_contrato' => $id_contrato_ixc,
            'id_assunto' => $id_assunto,
            'titulo' => $titulo,
            'origem_endereco' => $origem_endereco,
            'prioridade' => $prioridade,
            'mensagem' => $mensagem,
            'su_status' => $su_status,
            'status' => $status,
            'id_filial' => $id_filial,
            'setor' => $id_setor,
            'id_tecnico' => $id_tecnico,
            'data_inicio' => $data_inicio,
            'data_final' => $data_final,
            'id_ticket_setor' => $id_setor,
            'id_atendimento_belluno' => $id_atendimento,
            'id_monitoramento_belluno' => $id_monitoramento_belluno,
            'id_atendimento_sistema_gestao' => $id,
            'cadastroOS' => "atendimento",
            'salvo' => 0,
            'retorno_api' => 'Não foi possível integrar por falta de dados!', //$retorno_api
            'situacao' => $situacao,
            'id_atendente_simples' => $_SESSION['id_usuario'],
            'contato' => $contato,
            'id_contrato_plano_pessoa' => $id_contrato_plano_pessoa
        );

        $retorno = DBCreate('', 'tb_integracao_atendimento_ixc', $dados, true);

        registraLog('Inserção de atendimento integrado ao ixc.','i','tb_integracao_atendimento_ixc', $retorno,"protocolo: $protocolo | id_cliente: $id_cliente | nome_assinante: $nome_assinante | id_login: $id_login | id_contrato: $id_contrato_ixc | id_assunto: $id_assunto | titulo: $titulo | origem_endereco: $origem_endereco | prioridade: $prioridade | mensagem: $mensagem | su_status: $su_status | status: $status | id_filial: $id_filial | setor: $id_setor | id_atendimento_belluno: $id_atendimento | id_monitoramento_belluno: $id_monitoramento_belluno | id_atendimento_sistema_gestao: $id | cadastroOS:  | salvo: 0 | retorno_api: $retorno_api | situacao: $situacao | id_atendente_simples: $id_usuario | contato: $contato | id_contrato_plano_pessoa: $id_contrato_plano_pessoa");

    }
    
}