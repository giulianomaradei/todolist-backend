<?php

class OrdemServicoIntegracao{

    private $titulo;
    private $nomeAssinante;
    private $tipo;
    private $id_ticket;
    private $protocolo;
    private $assunto;
    private $id_cliente;
    private $id_estrutura;
    private $filial;
    private $login;
    private $contrato;
    private $origem_endereco;
    private $origem_endereco_estrutura;
    private $latitude; 
    private $longitude;
    private $prioridade;
    private $melhor_horario_agenda;
    private $setor;
    private $id_tecnico;
    private $mensagem;
    private $idx;
    private $status;
    private $gera_comissao;
    private $liberado;
    private $impresso;
    private $id_su_diagnostico;
    private $id_cidade;
    private $bairro;
    private $endereco;
    private $data_abertura;
    private $data_inicio;
    private $data_hora_analise;
    private $data_agenda;
    private $data_hora_encaminhado;
    private $data_hora_assumido;
    private $data_hora_execucao;
    private $data_final;
    private $data_fechamento;
    private $mensagem_resposta;
    private $justificativa_sla_atrasado;
    private $valor_total;
    private $valor_outras_despesas;
    private $valor_unit_comissao;
    private $valor_total_comissao;
    private $tipoRetorno;
    private $id_contrato_plano_pessoa;
    private $situacao;
    private $operacao;
    private $id_integracao_atendimento_ixc;
    private $id_atendimento;
    private $id_monitoramento_belluno;
    private $id_atendimento_sistema_gestao;

    public function __construct($titulo, $nomeAssinante, $tipo, $id_ticket, $protocolo, $assunto, $id_cliente, $id_estrutura, $filial, $login, $contrato, $origem_endereco, $origem_endereco_estrutura, $latitude, $longitude, $prioridade, $melhor_horario_agenda, $setor, $id_tecnico, $mensagem, $idx, $status, $gera_comissao, $liberado, $impresso, $id_su_diagnostico, $id_cidade, $bairro, $endereco, $data_abertura, $data_inicio, $data_hora_analise, $data_agenda, $data_hora_encaminhado, $data_hora_assumido, $data_hora_execucao, $data_final, $data_fechamento, $mensagem_resposta, $justificativa_sla_atrasado, $valor_total, $valor_outras_despesas, $valor_unit_comissao, $valor_total_comissao, $tipoRetorno, $id_contrato_plano_pessoa, $situacao, $operacao, $id_integracao_atendimento_ixc, $id_atendimento, $id_monitoramento_belluno, $id_atendimento_sistema_gestao){
    
        $this->titulo = $titulo;
        $this->nomeAssinante = $nomeAssinante;
        $this->tipo = $tipo;
        $this->id_ticket = $id_ticket;
        $this->protocolo = $protocolo;
        $this->assunto = $assunto;
        $this->id_cliente = $id_cliente;
        $this->id_estrutura = $id_estrutura;
        $this->filial = $filial;
        $this->login = $login;
        $this->contrato = $contrato;
        $this->origem_endereco = $origem_endereco;
        $this->origem_endereco_estrutura = $origem_endereco_estrutura;
        $this->latitude = $latitude; 
        $this->longitude = $longitude; 
        $this->prioridade = $prioridade;
        $this->melhor_horario_agenda = $melhor_horario_agenda;
        $this->setor = $setor;
        $this->id_tecnico = $id_tecnico;
        $this->mensagem = $mensagem;
        $this->idx = $idx;
        $this->status = $status;
        $this->gera_comissao = $gera_comissao;
        $this->liberado = $liberado;
        $this->impresso = $impresso;
        $this->id_su_diagnostico = $id_su_diagnostico;
        $this->id_cidade = $id_cidade;
        $this->bairro = $bairro;
        $this->endereco = $endereco;
        $this->data_abertura = $data_abertura;
        $this->data_inicio = $data_inicio;
        $this->data_hora_analise = $data_hora_analise;
        $this->data_agenda = $data_agenda;
        $this->data_hora_encaminhado = $data_hora_encaminhado;
        $this->data_hora_assumido = $data_hora_assumido;
        $this->data_hora_execucao = $data_hora_execucao;
        $this->data_final = $data_final;
        $this->data_fechamento = $data_fechamento;
        $this->mensagem_resposta = $mensagem_resposta;
        $this->justificativa_sla_atrasado = $justificativa_sla_atrasado;
        $this->valor_total = $valor_total;
        $this->valor_outras_despesas = $valor_outras_despesas;
        $this->valor_unit_comissao = $valor_unit_comissao;
        $this->valor_total_comissao = $valor_total_comissao;
        $this->tipoRetorno = $tipoRetorno;
        $this->id_contrato_plano_pessoa = $id_contrato_plano_pessoa;
        $this->situacao = $situacao;
        $this->operacao = $operacao;
        $this->id_integracao_atendimento_ixc = $id_integracao_atendimento_ixc;
        $this->id_atendimento = $id_atendimento;
        $this->id_monitoramento_belluno = $id_monitoramento_belluno;
        $this->id_atendimento_sistema_gestao = $id_atendimento_sistema_gestao;
    }

    /** Função de inserção de ordem de serviço na base do sistema IXC */
    public function post(){

        $salva_recurso = DBRead('', 'tb_integracao_parametro a', "INNER JOIN tb_integracao_valores_tipo_parametro b ON a.id_integracao_parametro = b.id_integracao_parametro INNER JOIN tb_integracao_contrato_parametro c ON b.id_integracao_parametro = c.id_integracao_parametro INNER JOIN tb_integracao_contrato d ON c.id_integracao_contrato = d.id_integracao_contrato WHERE a.codigo = 'encerramento' AND d.id_contrato_plano_pessoa = '".$this->id_contrato_plano_pessoa."'", "c.valor");

        //die($this->id_atendimento_sistema_gestao);

        if(!$this->id_atendimento_sistema_gestao || $this->id_atendimento_sistema_gestao == ''){

            include_once "integracoes/ixc/OrdemServico.php";
            $ordemServico = new Integracao\Ixc\OrdemServico();
            //post da entidade OrdemServico para salva os dados no recurso que corresponte a aba O.S. do IXC
            $retorno = $ordemServico->post($this->tipo = 'C', $this->id_ticket = '', $this->protocolo, $this->assunto, $this->id_cliente, $this->id_estrutura = '', $this->filial, $this->login, $this->contrato, $this->origem_endereco, $this->origem_endereco_estrutura = 'E', $this->latitude = '', $this->longitude = '', $this->prioridade, $this->melhor_horario_agenda = 'Q', $this->setor, $this->id_tecnico, $this->mensagem, $this->idx = '', $this->status, $this->gera_comissao, $this->liberado, $this->impresso, $this->id_su_diagnostico, $this->id_cidade, $this->bairro, $this->endereco, $this->data_abertura, $this->data_inicio, $this->data_hora_analise, $this->data_agenda, $this->data_hora_encaminhado, $this->data_hora_assumido, $this->data_hora_execucao, $this->data_final, $this->data_fechamento, $this->mensagem_resposta, $this->justificativa_sla_atrasado, $this->valor_total, $this->valor_outras_despesas, $this->valor_unit_comissao, $this->valor_total_comissao, true, $this->id_contrato_plano_pessoa);

            if($salva_recurso[0]['valor'] == 'sim' && $this->situacao == 3){
                $this->postAcao('finalizacao', $retorno['id']);
            }

            $cadastroOS = "os";
            $this->status($retorno, $cadastroOS, $retorno['id']);

        }else if($salva_recurso[0]['valor'] == 'sim' && $this->situacao == 3){
            
            $retorno = $this->postAcao('finalizacao', $this->id_atendimento_sistema_gestao);

            /*echo "<pre>";
            var_dump($retorno);
            echo "</pre>";
            die();*/
            
        }

        return $retorno;
            
    }

    /** Função de inserção de Ações de finalização ou de análise de uma ordem de serviço na base do sistema IXC */
    public function postAcao($evento, $id_atendimento_sistema_gestao){

        //$id_atendimento_sistema_gestao = intval($id_atendimento_sistema_gestao);

        if($evento == "finalizacao"){

            include_once "integracoes/ixc/FinalizarOrdemServico.php";
            $finalizarOrdemServico = new Integracao\Ixc\FinalizarOrdemServico();

            //recurso post de FinalizarOrdemServico para o sistema IXC
            $retorno_acao = $finalizarOrdemServico->post($id_atendimento_sistema_gestao, $this->id_tarefa_atual, $this->data_inicio, $this->data_final, $this->id_resposta, $this->mensagem, $this->id_tecnico, $this->id_equipe, $this->gera_comissao, $this->id_su_diagnostico, $this->finaliza_processo, $this->id_proxima_tarefa, $this->id_proxima_tarefa_aux, $this->id_contrato_plano_pessoa);
            //$retorno_acao = $finalizarOrdemServico->post($id_atendimento_sistema_gestao, $this->data_inicio, $this->data_final, $this->mensagem, $this->id_tecnico, $this->id_contrato_plano_pessoa);
            $cadastroOS = "acao_finalizar_os";

           /*  echo "<pre>";
            var_dump($retorno_acao);
            echo "</pre>";
            die(); */
            
            $this->status($retorno_acao, $cadastroOS, $id_atendimento_sistema_gestao);

        }else if($evento == "analise"){

            include_once "integracoes/ixc/AnalisarOrdemServico.php";
            $analisarOrdemServico = new Integracao\Ixc\AnalisarOrdemServico();

            //recurso post de AnalisarOrdemServico para o sistema IXC
            $retorno_acao = $analisarOrdemServico->post($id_atendimento_sistema_gestao, $this->setor, $this->id_tecnico, $this->mensagem, $this->data_inicio, $this->data_final, $this->id_contrato_plano_pessoa);
            $cadastroOS = 'acao_analisar_os';

            $this->status($retorno_acao, $cadastroOS, $id_atendimento_sistema_gestao);

        }
        
        return $retorno_acao;
    }

    //Configura os parâmetros 'retorno_api', 'salvo' depois chama 'salvaTabelaLocal
    private function status($ordemServico, $cadastroOS, $id_acoes){

        /*echo "<pre>";
        var_dump($ordemServico);
        echo "</pre>";
        die();*/
        
        $retorno_api = "Type: ".$ordemServico['type']." | Message: ".$ordemServico['message'];
       // $id = $ordemServico['id'];

        if($ordemServico['type'] == "success"){
            $salvo = 1;
            $this->salvaTabelaLocal($salvo, $retorno_api, $id_acoes, $cadastroOS);
        }else if((!$ordemServico || $ordemServico['type'] != "success") && $this->operacao == 'alterar'){
            return;
        }else{
            $salvo = 0;
            $this->salvaTabelaLocal($salvo, $retorno_api, $id_acoes, $cadastroOS);
        }
        
        return $this;
    }

    //Função responsável por salvar os principais dados na tabela 'tb_integracao_atendimento_ixc' do Simples
    private function salvaTabelaLocal($salvo, $retorno_api, $id, $cadastroOS){

        if($this->operacao == 'alterar'){

            $id_atendente_simples = DBRead('', 'tb_integracao_atendimento_ixc', "WHERE id_integracao_atendimento_ixc = '".$this->id_integracao_atendimento_ixc."'", "id_atendente_simples");
            $id_atendente_simples = $id_atendente_simples[0]['id_atendente_simples'];

            $dados = array(
                'protocolo' => $this->protocolo,
                'id_cliente' => $this->id_cliente,
                'nome_assinante' => $this->nomeAssinante,
                'id_login' => $this->login,
                'id_contrato' => $this->contrato,
                'id_assunto' => $this->assunto,
                'titulo' => $this->titulo,
                'origem_endereco' => $this->origem_endereco,
                'prioridade' => $this->prioridade,
                'mensagem' => $this->mensagem,
                'su_status' => $this->su_status,
                'status' => $this->status,
                'id_filial' => $this->filial,
                'setor' => $this->setor,
                'id_tecnico' => $this->id_tecnico,
                'data_inicio' => $this->data_inicio,
                'data_final' => $this->data_final,
                'id_ticket_setor' => $this->setor,
                'id_atendimento_belluno' => $this->id_atendimento,
                'id_monitoramento_belluno' => $this->id_monitoramento_belluno,
                'id_atendimento_sistema_gestao' => $id,
                'cadastroOS' => $cadastroOS,
                'salvo' => $salvo,
                'retorno_api' => $retorno_api,
                'situacao' => $this->situacao,
                'id_atendente_simples' => $id_atendente_simples,
                'contato' => $this->contato,
                'id_contrato_plano_pessoa' => $this->id_contrato_plano_pessoa
            );
            
            $insertID = DBUpdate('', 'tb_integracao_atendimento_ixc', $dados, "id_integracao_atendimento_ixc = '".$this->id_integracao_atendimento_ixc."'", true);
            /*DBDelete('', 'tb_integracao_atendimento_ixc', "id_integracao_atendimento_ixc = '".$this->id_integracao_atendimento_ixc."'");
            $insertID = DBCreate('', 'tb_integracao_atendimento_ixc', $dados, true);*/

            registraLog('Inserção de ordem de serviço integrado ao ixc.', 'i','tb_integracao_atendimento_ixc',$insertID,"protocolo: ".$this->protocolo." | id_cliente: ".$this->id_cliente." | nome_assinante: ".$this->nomeAssinante." | id_login: ".$this->id_login." | id_contrato: ".$this->id_contrato_ixc." | id_assunto: ".$this->id_assunto." | titulo: ".$this->titulo." | origem_endereco: ".$this->origem_endereco." | prioridade: ".$this->prioridade." | mensagem: ".$this->mensagem." | su_status: ".$this->su_status." | status: ".$this->status." | id_filial: ".$this->filial." | setor: ".$this->setor." | id_atendimento_belluno: ".$this->id_atendimento." | id_monitoramento_belluno: ".$this->id_monitoramento_belluno." | id_atendimento_sistema_gestao: ".$id." | cadastroOS: atendimento | salvo: ".$salvo." | retorno_api: ".$retorno_api." | situacao: ".$this->situacao." | id_atendente_simples: ".$_SESSION['id_usuario']." | contato: ".$this->contato." | id_contrato_plano_pessoa: ".$this->id_contrato_plano_pessoa."");
        }else{

            $dados = array(
                'protocolo' => $this->protocolo,
                'id_cliente' => $this->id_cliente,
                'nome_assinante' => $this->nomeAssinante,
                'id_login' => $this->login,
                'id_contrato' => $this->contrato,
                'id_assunto' => $this->assunto,
                'titulo' => $this->titulo,
                'origem_endereco' => $this->origem_endereco,
                'prioridade' => $this->prioridade,
                'mensagem' => $this->mensagem,
                'su_status' => $this->su_status,
                'status' => $this->status,
                'id_filial' => $this->filial,
                'setor' => $this->setor,
                'id_tecnico' => $this->id_tecnico,
                'data_inicio' => $this->data_inicio,
                'data_final' => $this->data_final,
                'id_ticket_setor' => $this->setor,
                'id_atendimento_belluno' => $this->id_atendimento,
                'id_monitoramento_belluno' => $this->id_monitoramento_belluno,
                'id_atendimento_sistema_gestao' => $id,
                'cadastroOS' => $cadastroOS,
                'salvo' => $salvo,
                'retorno_api' => $retorno_api,
                'situacao' => $this->situacao,
                'id_atendente_simples' => $_SESSION['id_usuario'],
                'contato' => $this->contato,
                'id_contrato_plano_pessoa' => $this->id_contrato_plano_pessoa
            );

            $insertID = DBCreate('', 'tb_integracao_atendimento_ixc', $dados, true);
            
            registraLog('Inserção de ordem de serviço integrado ao ixc.', 'i','tb_integracao_atendimento_ixc',$insertID,"protocolo: ".$this->protocolo." | id_cliente: ".$this->id_cliente." | nome_assinante: ".$this->nomeAssinante." | id_login: ".$this->id_login." | id_contrato: ".$this->id_contrato_ixc." | id_assunto: ".$this->id_assunto." | titulo: ".$this->titulo." | origem_endereco: ".$this->origem_endereco." | prioridade: ".$this->prioridade." | mensagem: ".$this->mensagem." | su_status: ".$this->su_status." | status: ".$this->status." | id_filial: ".$this->filial." | setor: ".$this->setor." | id_atendimento_belluno: ".$this->id_atendimento." | id_monitoramento_belluno: ".$this->id_monitoramento_belluno." | id_atendimento_sistema_gestao: ".$id." | cadastroOS: atendimento | salvo: ".$salvo." | retorno_api: ".$retorno_api." | situacao: ".$this->situacao." | id_atendente_simples: ".$_SESSION['id_usuario']." | contato: ".$this->contato." | id_contrato_plano_pessoa: ".$this->id_contrato_plano_pessoa."");
        }
        return $this;
    }
}