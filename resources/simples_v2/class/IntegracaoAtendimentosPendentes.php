<?php
require_once(__DIR__."/System.php");
require_once (__DIR__."/AtendimentoIntegracao.php");
require_once (__DIR__."/OrdemServicoIntegracao.php");

$inserir_mesmo_atendimento = (!empty($_POST['inserir_mesmo_atendimento'])) ? $_POST['inserir_mesmo_atendimento'] : '';

$protocolo = (!empty($_POST['protocolo_integracao_integracao'])) ? $_POST['protocolo_integracao_integracao'] : ''; //verificar a geração do protocolo
$id_cliente = (!empty($_POST['id_cliente_integracao'])) ? $_POST['id_cliente_integracao'] : 0; //atendimento-form.php | <input type="hidden" id="id_assinante" name="id_cliente_integracao" />
$id_assunto = (!empty($_POST['id_assunto'])) ? $_POST['id_assunto'] : 0; //integracoes/atendimento-form-arvore-ixc | <select class="form-control" name='id_assunto' id='assunto'>
$titulo = (!empty($_POST['titulo'])) ? $_POST['titulo'] : ''; //Capturar essa informação na opção clicada do passo 92 do fluxo no atendimento do sistema Simples
$origem_endereco = (!empty($_POST['origem_endereco'])) ? $_POST['origem_endereco'] : 'C'; //atendimento-form-arvore-ixc
//$id_ticket_setor = (!empty($_POST['id_ticket_setor'])) ? $_POST['id_ticket_setor'] : ''; //atendimento-form-arvore-ixc
$prioridade = (!empty($_POST['prioridade'])) ? $_POST['prioridade'] : 'N'; //atendimento-form-arvore-ixc
$mensagem = (!empty($_POST['os'])) ? $_POST['os'] : ''; //capturado do campo de script na finalização do atendimento juntamente com a situação para o atendimento selecionado pelo atendente
$su_status = 'N';//N => Status padrão para novo atendimento
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

$processo = (!empty($_POST['processo'])) ? $_POST['processo'] : '';

$classificacao = (!empty($_POST['classificacao'])) ? $_POST['classificacao'] : 0; // Campo que determina com qual recurso o sistema vai salvar o fluxo, se em atendimento ou ordem de serviço | 1 = Ordem de serviço; 2 = Atendimento
$classificacao_evento = (!empty($_POST['classificacao_evento'])) ? $_POST['classificacao_evento'] : 0;

$operacao = (!empty($_POST['operacao'])) ? $_POST['operacao'] : ''; //Flag que indica se atendimento ou o.s. deve ser salva no caso dos dados vierem do fluxo de atendimento, ou se deve ser atualizado no caso em que os dados vem da tela de correção dos atendimentos pendentes que por alguma razão não pode ser salva no atendimento.
$id_integracao_atendimento_ixc = (!empty($_POST['id_integracao_atendimento_ixc'])) ? $_POST['id_integracao_atendimento_ixc'] : '';

$evento = (!empty($_POST['evento'])) ? $_POST['evento'] : ''; //Sinaliza qual recurso utilizar quando o atendimento for vinculado à um atendimento já existente, se deve-se utilizar AnalisarOrdemServico ou FinalizarOrdemServico (recursos suportados somente por ordens de serviço)

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

//Objeto Atendimento de integração
$atendimentoIntegracao = new AtendimentoIntegracao($protocolo, $id_cliente, $id_assunto, $titulo, $origem_endereco, $prioridade, $mensagem, $su_status, $status, $tipoRetorno, $id_contrato_plano_pessoa, $id_filial, $id_tecnico, $data_inicio, $data_final, $id_setor, $id_atendimento, $id_monitoramento_belluno, $situacao, $id_login, $id_contrato_ixc, $operacao, $id_integracao_atendimento_ixc, $nome_assinante, $processo);


//Objeto Ordem de serviço de integração
$ordemServicoIntegracao = new OrdemServicoIntegracao($titulo, $nome_assinante, $tipo, $id_ticket, $protocolo, $id_assunto, $id_cliente, $id_estrutura, $id_filial, $id_login, $id_contrato_ixc, $origem_endereco, $origem_endereco_estrutura, $latitude, $longitude, $prioridade, $melhor_horario_agenda, $id_setor, $id_tecnico, $mensagem, $idx, $status, $gera_comissao, $liberado, $impresso, $id_su_diagnostico, $id_cidade, $bairro, $endereco, $data_abertura, $data_inicio, $data_hora_analise, $data_agenda, $data_hora_encaminhado, $data_hora_assumido, $data_hora_execucao,$data_final, $data_fechamento, $mensagem_resposta, $justificativa_sla_atrasado, $valor_total, $valor_outras_despesas, $valor_unit_comissao, $valor_total_comissao, $tipoRetorno, $id_contrato_plano_pessoa, $situacao, $operacao, $id_integracao_atendimento_ixc, $id_atendimento, $id_monitoramento_belluno, $id_atendimento_sistema_gestao);

//Atualiza a situação na tabela de atendimento do sistema Simples
$data = array(
    'id_situacao' => $situacao
);
DBUpdate('', 'tb_situacao_atendimento', $data, "id_atendimento = $id_atendimento", false);

if($situacao == 7 && $salvaEmOS == "'sim'"){

    if($classificacao_evento == 1 || $classificacao == 1){
        $retorno = $ordemServicoIntegracao->postAcao($evento, $id_atendimento_sistema_gestao);

    }else if($classificacao_evento == 2 || $classificacao == 2){
        $retorno = $atendimentoIntegracao->postMensagem($id_atendimento_sistema_gestao, $id_cliente, $mensagem, $id_contrato_plano_pessoa, $evento);
    }
}else{
    if($classificacao == 2){
        $retorno = $atendimentoIntegracao->post();
        
    }else if($classificacao == 1){
        $retorno = $ordemServicoIntegracao->post();
    }
}

if($_SESSION['id_usuario'] == 2){
    var_dump($retorno);
    die();
}

$alert = ('Atendimento enviado com sucesso!', 's');
header("location: /api/iframe?token=$request->token&view=integracao-atendimentos-pendentes");
exit;