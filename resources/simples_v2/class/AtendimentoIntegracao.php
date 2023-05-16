<?php

class AtendimentoIntegracao{

    private $protocolo;
    private $id_cliente;
    private $id_assunto;
    private $titulo;
    private $origem_endereco;
    private $prioridade;
    private $mensagem;
    private $su_status;
    private $status;
    private $tipoRetorno;
    private $id_contrato_plano_pessoa;
    private $id_filial;
    private $id_tecnico;
    private $data_inicio;
    private $data_final;
    private $id_setor;
    private $id_atendimento;
    private $situacao;
    private $id_login;
    private $id_contrato_ixc;
    private $operacao;
    private $id_integracao_atendimento_ixc;
    private $nomeAssinante;
    private $processo;

    public function __construct($protocolo, $id_cliente, $id_assunto, $titulo, $origem_endereco, $prioridade, $mensagem, $su_status, $status, $tipoRetorno, $id_contrato_plano_pessoa, $id_filial, $id_tecnico, $data_inicio, $data_final, $id_setor, $id_atendimento, $id_monitoramento_belluno, $situacao, $id_login, $id_contrato_ixc, $operacao, $id_integracao_atendimento_ixc, $nomeAssinante, $processo){

        $this->protocolo = $protocolo;
        $this->id_cliente = $id_cliente;
        $this->nome_assinante = $nomeAssinante;
        $this->id_assunto = $id_assunto;
        $this->titulo = $titulo;
        $this->origem_endereco = $origem_endereco;
        $this->prioridade = $prioridade;
        $this->mensagem = $mensagem;
        $this->su_status = $su_status;
        $this->status = $status;
        $this->tipoRetorno = $tipoRetorno;
        $this->id_contrato_plano_pessoa = $id_contrato_plano_pessoa;
        $this->id_filial = $id_filial;
        $this->id_tecnico = $id_tecnico;
        $this->data_inicio = $data_inicio;
        $this->data_final = $data_final;
        $this->id_setor = $id_setor;
        $this->id_atendimento = $id_atendimento;
        $this->id_monitoramento_belluno = $id_monitoramento_belluno;
        $this->situacao = $situacao;
        $this->id_login = $id_login;
        $this->id_contrato_ixc = $id_contrato_ixc;
        $this->operacao = $operacao;
        $this->id_integracao_atendimento_ixc = $id_integracao_atendimento_ixc;
        $this->nomeAssinante = $nomeAssinante;
        $this->processo = $processo;
    }

    /** Função de inserção de atendimento na base do sistema IXC */
    //$id_tecnico_acao - Parâmetro para reinserir o id do técnico responsável em caso de inserção de ação no atendimento
    public function post(){

        //Valida se campos obrigatórios estão configurados
        //if($this->id_cliente && $this->id_assunto && $this->origem_endereco && $this->id_setor && $this->mensagem){

            $salva_recurso = DBRead('', 'tb_integracao_parametro a', "INNER JOIN tb_integracao_valores_tipo_parametro b ON a.id_integracao_parametro = b.id_integracao_parametro INNER JOIN tb_integracao_contrato_parametro c ON b.id_integracao_parametro = c.id_integracao_parametro INNER JOIN tb_integracao_contrato d ON c.id_integracao_contrato = d.id_integracao_contrato WHERE a.codigo = 'encerramento' AND d.id_contrato_plano_pessoa = '".$this->id_contrato_plano_pessoa."'", "c.valor, d.usuario_integrado");

            include_once "integracoes/ixc/Atendimento.php";
            $atendimento = new Integracao\Ixc\Atendimento();
            //post da entidade Atendimento para salva os dados no recurso que corresponte a aba Atendimento do IXC
            $retorno = $atendimento->post($this->protocolo, $this->id_cliente, $this->id_login, $this->id_contrato_ixc, $this->id_filial, $this->id_assunto, $this->titulo, $this->origem_endereco, $this->endereco = "", $this->latitude = "", $this->longitude = "", $this->processo, $this->id_setor, $this->id_tecnico, $this->prioridade, $this->id_ticket_origem = 'I', $salva_recurso[0]['usuario_integrado'], $this->id_resposta = 0, $this->mensagem, $this->su_status, $this->status, $this->id_su_diagnostico = 0, $this->atualizar_cliente = 'S', $this->latitude_cli = "", $this->longitude_cli = "", $this->atualizar_login = 'S', $this->latitude_login = "", $this->longitude_login = "", $this->tipoRetorno, $this->id_contrato_plano_pessoa);
            
            if($salva_recurso[0]['valor'] == 'sim' && $this->situacao == 3){
                $retorno = $this->postMensagem($retorno['id'], $this->id_cliente, $this->mensagem, $this->id_contrato_plano_pessoa, 'S');
            }
        //}

        $this->status($retorno, $cadastroOS);

        return $retorno;
    }

    /** Função de inserção de Mensagens de atendimento na base do sistema IXC */
    public function postMensagem($id_atendimento, $id_cliente, $mensagem, $id_contrato_plano_pessoa, $su_status){
        include_once "integracoes/ixc/Atendimento.php";
        $atendimento = new Integracao\Ixc\Atendimento();

        //e inserido em uma iteração desse mesmo atendimento que altera o status para 'solucionado'
        $retorno = $atendimento->interacaoAtendimento($id_atendimento, $id_cliente, $mensagem, '', $id_contrato_plano_pessoa, true, $su_status);

        $this->status($retorno, $cadastroOS);

        return $retorno;
    }

    public function put($id_atendimento_acao, $tecnico_responsavel){
        include_once "integracoes/ixc/Atendimento.php";
        $atendimento = new Integracao\Ixc\Atendimento();
        //$tecnico_responsavel = $atendimento->get('su_ticket.id', $id_atendimento_acao, '=', '1', '20000', 'su_ticket.id', 'desc', true, $id_contrato_plano_pessoa);
        //atualiza atendimento para reinserir o id do técnico responsável
        $teste = $atendimento->put($this->protocolo, $this->id_cliente, $this->id_login, $this->id_contrato_ixc, $this->id_filial, $this->id_assunto, $this->titulo, $this->origem_endereco, $this->endereco = "", $this->latitude = "", $this->longitude = "", $this->id_wfl_processo, $this->id_setor, $tecnico_responsavel, $this->prioridade, $id_ticket_origem = 'I', $salva_recurso[0]['usuario_integrado'], $this->id_resposta = 0, $this->mensagem, $this->su_status, $this->status, $this->id_su_diagnostico = 0, $this->atualizar_cliente = 'S', $this->latitude_cli = "", $this->longitude_cli = "", $this->atualizar_login = 'S', $this->latitude_login = "", $this->longitude_login = "", $this->tipoRetorno, $this->id_contrato_plano_pessoa, $id_atendimento_acao);
        return $this;
    }

    //Configura os parâmetros 'retorno_api', 'salvo' depois chama 'salvaTabelaLocal
    private function status($atendimento){

        $retorno_api = "Type: ".$atendimento['type']." | Message: ".$atendimento['message'];
        $id = $atendimento['id'];

        if($atendimento['type'] == "success"){
            $salvo = 1;

        } else if ((!$atendimento || $atendimento['type'] != "success") && $this->operacao == 'alterar'){
            return;

        } else {
            $salvo = 0;
        }
        $this->salvaTabelaLocal($salvo, $retorno_api, $id);
        return $this;
    }

    //Função responsável por salvar os principais dados na tabela 'tb_integracao_atendimento_ixc' do Simples
    private function salvaTabelaLocal($salvo, $retorno_api, $id){

        if($this->operacao == 'alterar'){

            $id_atendente_simples = DBRead('', 'tb_integracao_atendimento_ixc', "WHERE id_integracao_atendimento_ixc = '".$this->id_integracao_atendimento_ixc."'", "id_atendente_simples");
            $id_atendente_simples = $id_atendente_simples[0]['id_atendente_simples'];

            $dados = array(
                'protocolo' => $this->protocolo,
                'id_cliente' => $this->id_cliente,
                'nome_assinante' => $this->nomeAssinante,
                'id_login' => $this->id_login,
                'id_contrato' => $this->id_contrato_ixc,
                'id_assunto' => $this->id_assunto,
                'titulo' => $this->titulo,
                'origem_endereco' => $this->origem_endereco,
                'prioridade' => $this->prioridade,
                'mensagem' => $this->mensagem,
                'su_status' => $this->su_status,
                'status' => $this->status,
                'id_filial' => $this->id_filial,
                'setor' => $this->id_setor,
                'id_tecnico' => $this->id_tecnico,
                'data_inicio' => $this->data_inicio,
                'data_final' => $this->data_final,
                'id_ticket_setor' => $this->id_setor,
                'id_atendimento_belluno' => $this->id_atendimento,
                'id_monitoramento_belluno' => $this->id_monitoramento_belluno,
                'id_atendimento_sistema_gestao' => $id,
                'cadastroOS' => "atendimento",
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

            //registraLog('Inserção de atendimento integrado ao ixc.','i','tb_integracao_atendimento_ixc',$insertID,"protocolo: $this->protocolo | id_cliente: $this->id_cliente | nome_assinante: $this->nomeAssinante | id_login: $this->id_login | id_contrato: $this->id_contrato_ixc | id_assunto: $this->id_assunto | titulo: $this->titulo | origem_endereco: $this->origem_endereco | prioridade: $this->prioridade | mensagem: $this->mensagem | su_status: $this->su_status | status: $this->status | id_filial: $this->id_filial | setor: $this->id_setor | id_atendimento_belluno: $this->id_atendimento | id_monitoramento_belluno: $this->id_monitoramento_belluno | id_atendimento_sistema_gestao: $id | cadastroOS: atendimento | salvo: $salvo | retorno_api: $retorno_api | situacao: $this->situacao | id_atendente_simples: $id_usuario | contato: $this->contato | id_contrato_plano_pessoa: $this->id_contrato_plano_pessoa");

        }else{

            $dados = array(
                'protocolo' => $this->protocolo,
                'id_cliente' => $this->id_cliente,
                'nome_assinante' => $this->nomeAssinante,
                'id_login' => $this->id_login,
                'id_contrato' => $this->id_contrato_ixc,
                'id_assunto' => $this->id_assunto,
                'titulo' => $this->titulo,
                'origem_endereco' => $this->origem_endereco,
                'prioridade' => $this->prioridade,
                'mensagem' => $this->mensagem,
                'su_status' => $this->su_status,
                'status' => $this->status,
                'id_filial' => $this->id_filial,
                'setor' => $this->id_setor,
                'id_tecnico' => $this->id_tecnico,
                'data_inicio' => $this->data_inicio,
                'data_final' => $this->data_final,
                'id_ticket_setor' => $this->id_setor,
                'id_atendimento_belluno' => $this->id_atendimento,
                'id_monitoramento_belluno' => $this->id_monitoramento_belluno,
                'id_atendimento_sistema_gestao' => $id,
                'cadastroOS' => "atendimento",
                'salvo' => $salvo,
                'retorno_api' => $retorno_api,
                'situacao' => $this->situacao,
                'id_atendente_simples' => $_SESSION['id_usuario'],
                'contato' => $this->contato,
                'id_contrato_plano_pessoa' => $this->id_contrato_plano_pessoa
            );

            $insertID = DBCreate('', 'tb_integracao_atendimento_ixc', $dados, true);

            //registraLog('Inserção de atendimento integrado ao ixc.','i','tb_integracao_atendimento_ixc',$insertID,"protocolo: $this->protocolo | id_cliente: $this->id_cliente | nome_assinante: $this->nomeAssinante | id_login: $this->id_login | id_contrato: $this->id_contrato_ixc | id_assunto: $this->id_assunto | titulo: $this->titulo | origem_endereco: $this->origem_endereco | prioridade: $this->prioridade | mensagem: $this->mensagem | su_status: $this->su_status | status: $this->status | id_filial: $this->id_filial | setor: $this->id_setor | id_atendimento_belluno: $this->id_atendimento | id_monitoramento_belluno: $this->id_monitoramento_belluno | id_atendimento_sistema_gestao: $id | cadastroOS: atendimento | salvo: $salvo | retorno_api: $retorno_api | situacao: $this->situacao | id_atendente_simples: $id_usuario | contato: $this->contato | id_contrato_plano_pessoa: $this->id_contrato_plano_pessoa");
        }
        return $this;
    }
}