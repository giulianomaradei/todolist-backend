<?php
include (__DIR__."System.php");

class IntegracaoTipoSistema{

    //Método para a busca de assinante
    public function buscaAssinante($id_contrato_plano_pessoa, $nome_assinante, $id_assinante){

        $id_contrato_plano_pessoa = intval($id_contrato_plano_pessoa);
        $integra = DBRead('', 'tb_integracao_contrato', "WHERE id_contrato_plano_pessoa = $id_contrato_plano_pessoa");

        //ixc
        //Busca de assinante para o sistema IXCSoft
        if($integra[0]['id_integracao'] == 1){
            require "integracoes/ixc/Cliente.php";
            require "integracoes/ixc/Atendimento.php";
            require "integracoes/ixc/Usuarios.php";
            $cliente = new Integracao\Ixc\Cliente();

            $nome_assinante = trim($nome_assinante);

            if($id_assinante){
                $retorno = $cliente->get('cliente.id', $id_assinante, '=', '1', '1', 'cliente.id', 'desc', true, $id_contrato_plano_pessoa);
            }else{
                $retorno = $cliente->get('cliente.razao', $nome_assinante, 'L', '1', '20000', 'cliente.id', 'desc', true, $id_contrato_plano_pessoa);
            }
        }
        //Fim buscaAssinante IXCSoft

        return $retorno;
    }

    //Método para buscar a cidade do assinante
    public function buscaCidadeAssinante($id_contrato_plano_pessoa, $id_cidade){

        $id_contrato_plano_pessoa = intval($id_contrato_plano_pessoa);
        $integra = DBRead('', 'tb_integracao_contrato', "WHERE id_contrato_plano_pessoa = $id_contrato_plano_pessoa");

        //ixc
        //buscaCidadeAssinante para o sistema IXCSoft
        if($integra[0]['id_integracao'] == 1){
            require "integracoes/ixc/Cidade.php";
            $id_cidade = intval($id_cidade);
            $cidade = new Integracao\Ixc\Cidade();
            $retorno = $cidade->get('cidade.id', $id_cidade, '=', '1', '20000', 'cidade.id', 'desc', true, $id_contrato_plano_pessoa);
        }
        //Fim buscaCidadeAssinante IXCSoft

        return $retorno;
    }

    //Método para buscar informações de contrato do assinante
    public function buscaContratoCliente($id_contrato_plano_pessoa, $id_contrato_ixc){

        $id_contrato_plano_pessoa = intval($id_contrato_plano_pessoa);
        $integra = DBRead('', 'tb_integracao_contrato', "WHERE id_contrato_plano_pessoa = $id_contrato_plano_pessoa");

        //ixc
        //buscaContratoAssinante para o sistema IXCSoft
        if($integra[0]['id_integracao'] == 1){
            require "integracoes/ixc/ContratoCliente.php";
            $id_contrato_ixc = intval($id_contrato_ixc);
            $contrato = new Integracao\Ixc\ContratoCliente();
            $retorno = $contrato->get('cliente_contrato.id', $id_contrato_ixc, '=', '1', '20000', 'cliente_contrato.id', 'desc', true, $id_contrato_plano_pessoa);
        }

        return $retorno;
    }

    //Método para buscar informações de contrato do assinante
    public function buscaContratoClienteAssinante($id_contrato_plano_pessoa, $id_assinante){

        $id_contrato_plano_pessoa = intval($id_contrato_plano_pessoa);
        $integra = DBRead('', 'tb_integracao_contrato', "WHERE id_contrato_plano_pessoa = $id_contrato_plano_pessoa");

        //ixc
        //buscaContratoAssinante para o sistema IXCSoft
        if($integra[0]['id_integracao'] == 1){
            require "integracoes/ixc/ContratoCliente.php";
            $id_assinante = intval($id_assinante);
            $contrato = new Integracao\Ixc\ContratoCliente();
            $retorno = $contrato->get('cliente_contrato.id_cliente', $id_assinante, '=', '1', '20000', 'cliente_contrato.id', 'desc', true, $id_contrato_plano_pessoa);
        }

        return $retorno;
    }

    //Método para a geração automatica de um novo protocolo de atendimento
    public function geraProtocoloAtendimento($id_contrato_plano_pessoa){

        $id_contrato_plano_pessoa = intval($id_contrato_plano_pessoa);
        $integra = DBRead('', 'tb_integracao_contrato', "WHERE id_contrato_plano_pessoa = $id_contrato_plano_pessoa");

        //ixc
        //geraProtocolo para o sistema IXCSoft
        if($integra[0]['id_integracao'] == 1){
            require "integracoes/ixc/Atendimento.php";
            $protocolo = new Integracao\Ixc\Atendimento();
            $retorno = $protocolo->getProtocolo($id_contrato_plano_pessoa);
        }

        return $retorno;
    }

    //Método para salvar um novo atendimento
    public function salvaAtendimento($id_contrato_plano_pessoa, $protocolo, $id_cliente, $mensagem){

        $id_contrato_plano_pessoa = intval($id_contrato_plano_pessoa);
        $integra = DBRead('', 'tb_integracao_contrato', "WHERE id_contrato_plano_pessoa = $id_contrato_plano_pessoa");

        if($integra[0]['id_integracao'] == 1){

            //Comando que verifica nos parâmetros qual recurso utilizar para salvar uma O.S., isso é identificado em cada cliente no quadro informativo.
            $cadastroOS = DBRead('', 'tb_integracao_contrato_parametro', "WHERE id_contrato_plano_pessoa = '".$id_contrato_plano_pessoa."' AND (SELECT id_integracao_parametro from tb_integracao_parametro WHERE id_integracao = 1 AND codigo = 'cadastroOS')");

            //Condição para cadastro com o recurso atendimento.
            if($cadastroOS == 'atendimento'){
                require "integracoes/ixc/Atendimento.php";
                $atendimento = new Integracao\Ixc\Atendimento();
                $atendimento->post($protocolo, $id_cliente, $id_login, $id_contrato, $id_filial, $id_assunto, $titulo, $origem_endereco, $endereco, $latitude, $longitude, $id_wfl_processo, $id_ticket_setor, $id_responsavel_tecnico, $prioridade, $id_ticket_origem, $id_usuarios, $id_resposta, $mensagem, $su_status, $status, $id_su_diagnostico, $atualizar_cliente, $latitude_cli, $longitude_cli, $atualizar_login, $latitude_login, $longitude_login, $tipoRetorno);
                //a variavel aqui era $menssagem

            //Condição para cadastro com o recurso OrdemServico.
            }else if($cadastroOS == 'os'){
                require "integracoes/ixc/OrdemServico.php";
                $ordemServico = new Integracao\Ixc\OrdemServico();
                $ordemServico->post($tipo, $id_ticket, $protocolo, $assunto, $id_cliente, $id_estrutura, $id_filial, $login, $contrato, $origem_endereco, $origem_endereco_estrutura, $latitude, $longitude, $prioridade, $melhor_horario_agenda, $setor, $id_tecnico, $mensagem, $idx, $status, $gera_comissao, $liberado, $impresso, $id_su_diagnostico, $id_cidade, $bairro, $endereco, $data_abertura, $data_inicio, $data_hora_analise, $data_agenda, $data_hora_encaminhado, $data_hora_assumido, $data_hora_execucao, $data_final, $data_fechamento, $mensagem_resposta, $justificativa_sla_atrasado, $valor_total, $valor_outras_despesas, $valor_unit_comissao, $valor_total_comissao);

            }
        }
    }

    //Método que busca os atendimentos de determinado cliente do provedor
    public function buscaAtendimentos($id_contrato_plano_pessoa, $status_atendimento, $id_assinante){

        $id_contrato_plano_pessoa = intval($id_contrato_plano_pessoa);
        $integra = DBRead('', 'tb_integracao_contrato', "WHERE id_contrato_plano_pessoa = $id_contrato_plano_pessoa");
        //ixc

        //buscaAtendimento para o sistema IXCSoft
        if($integra[0]['id_integracao'] == 1){
            require "integracoes/ixc/Atendimento.php";
            $atendimento = new Integracao\Ixc\Atendimento();

            if($status_atendimento == "abertos"){
                $retorno = $atendimento->get('su_ticket.id_cliente', $id_assinante, '=', '1', '200000', 'su_ticket.id', 'asc', true, $id_contrato_plano_pessoa);

            }else if($status_atendimento == "fechados"){
                $retorno = $atendimento->getSolucionados('su_ticket.id_cliente', $id_assinante, '=', '1', 'su_ticket.id', 'asc', true, $id_contrato_plano_pessoa);//deixar operador =
            }
        }
        return $retorno;
    }

    //Método que busca ordens de serviço de determinado cliente do provedor
    public function buscaAtendimentosOS($id_contrato_plano_pessoa, $status_atendimento, $id_assinante){
        $id_contrato_plano_pessoa = intval($id_contrato_plano_pessoa);
        $integra = DBRead('', 'tb_integracao_contrato', "WHERE id_contrato_plano_pessoa = $id_contrato_plano_pessoa");
        //ixc
        //buscaAtendimentoOS para o sistema IXCSoft
        if($integra[0]['id_integracao'] == 1){
            require "integracoes/ixc/OrdemServico.php";
            $ordemServico = new Integracao\Ixc\OrdemServico();
            if($status_atendimento == "abertos"){
                $retorno = $ordemServico->get('su_oss_chamado.id_cliente', $id_assinante, '=', '1', '200000', 'su_oss_chamado.id', 'desc', true, $id_contrato_plano_pessoa);
                
            }else if($status_atendimento == "fechados"){
                $retorno = $ordemServico->getFinalizados('su_oss_chamado.id_cliente', $id_assinante, '=', '1', 'su_oss_chamado.id', 'desc', true, $id_contrato_plano_pessoa);
            }
        }
        return $retorno;
    }

    //Método que busca todos o assunto pertinente a um atendimento ou ordem de serviço
    public function buscaAssunto($id_contrato_plano_pessoa, $id_assunto){

        $id_assunto = (!empty($id_assunto)) ? $id_assunto : '';

        $id_contrato_plano_pessoa = intval($id_contrato_plano_pessoa);
        $integra = DBRead('', 'tb_integracao_contrato', "WHERE id_contrato_plano_pessoa = $id_contrato_plano_pessoa");

        //ixc
        //buscaAssunto para o sistema IXCSoft
        if($integra[0]['id_integracao'] == 1){
            require "integracoes/ixc/Assunto.php";
            $assunto = new Integracao\Ixc\Assunto();
            $retorno = $assunto->get('su_oss_assunto.id', $id_assunto, true, $id_contrato_plano_pessoa);
        }

        return $retorno;
    }

    //Método que busca o departamento do atendimento ou ordem de serviço
    public function buscaSetor($id_contrato_plano_pessoa, $id_setor = ""){

        $id_contrato_plano_pessoa = intval($id_contrato_plano_pessoa);
        $integra = DBRead('', 'tb_integracao_contrato', "WHERE id_contrato_plano_pessoa = $id_contrato_plano_pessoa");

        $id_setor = (!empty($id_setor)) ? $id_setor : '';

        //ixc
        //buscaAtendimentoOS para o sistema IXCSoft
        if($integra[0]['id_integracao'] == 1){
            require "integracoes/ixc/DepartamentoAtendimento.php";
            $setor = new Integracao\Ixc\DepartamentoAtendimento();
            $retorno = $setor->get('su_ticket_setor.id', $id_setor, true, $id_contrato_plano_pessoa);
        }

        return $retorno;
    }

    //Método temporário que busca o setor do atendimento ou ordem de serviço
    public function buscaSetor2($id_contrato_plano_pessoa, $id_setor2 = ""){

        $id_contrato_plano_pessoa = intval($id_contrato_plano_pessoa);
        $integra = DBRead('', 'tb_integracao_contrato', "WHERE id_contrato_plano_pessoa = $id_contrato_plano_pessoa");

        $id_setor2 = (!empty($id_setor2)) ? $id_setor2 : '';
        //ixc
        //buscaAtendimentoOS para o sistema IXCSoft
        if($integra[0]['id_integracao'] == 1){
            require "integracoes/ixc/Setor.php";
            $setor = new Integracao\Ixc\Setor();
            $retorno = $setor->get('empresa_setor.id', $id_setor2, '=', true, $id_contrato_plano_pessoa);
        }

        return $retorno;
    }

    //Método que busca a filial do provedor
    public function buscaFilial($id_contrato_plano_pessoa, $id_filial = ""){

        $id_contrato_plano_pessoa = intval($id_contrato_plano_pessoa);
        $integra = DBRead('', 'tb_integracao_contrato', "WHERE id_contrato_plano_pessoa = $id_contrato_plano_pessoa");

        $id_filial = (!empty($id_filial)) ? $id_filial : '';

        //ixc
        //buscaFilial para o sistema IXCSoft
        if($integra[0]['id_integracao'] == 1){
            require "integracoes/ixc/Filial.php";
            $filial = new Integracao\Ixc\Filial();
            $retorno = $filial->get('filial.id', $id_filial, "=", true, $id_contrato_plano_pessoa);
        }
        
        return $retorno;
    }

    //Método que busca os técnicos pertinentes ao atendimento ou ordem de serviço
    public function buscaTecnico($id_contrato_plano_pessoa, $id_tecnico = ""){

        $id_contrato_plano_pessoa = intval($id_contrato_plano_pessoa);
        $integra = DBRead('', 'tb_integracao_contrato', "WHERE id_contrato_plano_pessoa = $id_contrato_plano_pessoa");
        $id_tecnico = (!empty($id_tecnico)) ? $id_tecnico : '';

        //ixc
        //buscaFilial para o sistema IXCSoft
        if($integra[0]['id_integracao'] == 1){
            require "integracoes/ixc/Funcionarios.php";
            $tecnicos = new Integracao\Ixc\Funcionarios();
            $retorno = $tecnicos->get('funcionario_setor2.id', '', true, $id_contrato_plano_pessoa);
        }

        return $retorno;
    }

    //Método que busca os logins de cada contrado dos clientes do provedor
    public function buscaLogin($id_contrato_plano_pessoa, $id_contrato){

        $id_contrato_plano_pessoa = intval($id_contrato_plano_pessoa);
        $integra = DBRead('', 'tb_integracao_contrato', "WHERE id_contrato_plano_pessoa = $id_contrato_plano_pessoa");

        //ixc
        //buscaFilial para o sistema IXCSoft
        if($integra[0]['id_integracao'] == 1){
            require "integracoes/ixc/Login.php";
            $login = new Integracao\Ixc\Login();
            $retorno = $login->get('radusuarios.id_contrato', $id_contrato, '=', '1', '20000', 'radusuarios.id', 'desc', true, $id_contrato_plano_pessoa);
        }
        
        return $retorno;
    }

    //Utiliza a classe ConfiguracaoONU
    public function buscaSinal($id_contrato_plano_pessoa, $id_login){

        $id_contrato_plano_pessoa = intval($id_contrato_plano_pessoa);
        $integra = DBRead('', 'tb_integracao_contrato', "WHERE id_contrato_plano_pessoa = $id_contrato_plano_pessoa");

        //ixc
        //buscaSinal para o sistema IXCSoft
        if($integra[0]['id_integracao'] == 1){
            require "integracoes/ixc/ConfiguracaoONU.php";
            $sinal = new Integracao\Ixc\ConfiguracaoONU();
            $retorno = $sinal->get('radpop_radio_cliente_fibra.id_login', $id_login, '=', '1', '20000', 'radpop_radio_cliente_fibra.id', 'desc', true, $id_contrato_plano_pessoa);
        }
        
        return $retorno;
    }

    //Método utilitário que realiza o desbloqueio manual do cliente do provedor
    public function desbloqueioManual($id_contrato_plano_pessoa, $id_contrato){

        $id_contrato_plano_pessoa = intval($id_contrato_plano_pessoa);
        $integra = DBRead('', 'tb_integracao_contrato', "WHERE id_contrato_plano_pessoa = $id_contrato_plano_pessoa");

        //ixc
        //desbloqueioManual para o sistema IXCSoft - na documentação da API do IXC esse recurso está denominado de desbloqueio de confiança
        if($integra[0]['id_integracao'] == 1){

            require_once "integracoes/ixc/Parametros.php";
            require_once "integracoes/ixc/WebServiceClient.php";
            require_once "integracoes/ixc/ContratoCliente.php";
			
			$parametros = new Integracao\Ixc\Parametros();
			$parametros->setParametros($id_contrato_plano_pessoa);
            $api = new IXCsoft\WebserviceClient($parametros->getHost(), $parametros->getToken(), $parametros->getSelfSigned());
            
            $contrato = new Integracao\Ixc\ContratoCliente();
            $retorno_contrato = $contrato->get('cliente_contrato.id', $id_contrato, '=', '1', '20000', 'cliente_contrato.id', 'desc', true, $id_contrato_plano_pessoa);

            $params = array(
                'id' => $id_contrato
            );

            if($retorno_contrato['registros'][0]['status_internet'] == 'CM' || $retorno_contrato['registros'][0]['status_internet'] == 'CA'){
                $api->get('desbloqueio_confianca', $params);
                $retorno = $api->getRespostaConteudo(true);
            }else if($retorno_contrato['registros'][0]['status_internet'] == 'FA'){
                $api->get('cliente_contrato_btn_lib_temp_24722', $params);
                $retorno = $api->getRespostaConteudo(true);
            }
            
        }

        return $retorno;
    }

    //Método utilitário que realiza o desbloqueio manual do cliente do provedor
    /*public function LiberarTemporariamente($id_contrato_plano_pessoa, $id_contrato)
    {

        $id_contrato_plano_pessoa = intval($id_contrato_plano_pessoa);
        $integra = DBRead('', 'tb_integracao_contrato', "WHERE id_contrato_plano_pessoa = $id_contrato_plano_pessoa");

        //ixc
        //desbloqueioManual para o sistema IXCSoft - na documentação da API do IXC esse recurso está denominado de desbloqueio de confiança
        if ($integra[0]['id_integracao'] == 1) {

            require_once "integracoes/ixc/Parametros.php";
            require_once "integracoes/ixc/WebServiceClient.php";

            $parametros = new Integracao\Ixc\Parametros();
            $parametros->setParametros($id_contrato_plano_pessoa);
            $api = new IXCsoft\WebserviceClient($parametros->getHost(), $parametros->getToken(), $parametros->getSelfSigned());

            $params = array(
                'id' => $id_contrato
            );

            $api->get('cliente_contrato_btn_lib_temp_24722', $params);
            $retorno = $api->getRespostaConteudo(true);
        }

        return $retorno;
    }*/

    //Método para salvar os atendimentos ou ordens de serviço que por alguma razão não tenha sido salvas no fim do fluxo de atendimento.
    public function salvaAtendimentosPendentes($id_integracao_atendimento_ixc, $id_cliente, $descricao_assunto, $protocolo, $origem_endereco, $prioridade, $id_ticket_origem, $mensagem, $su_status, $status, $cadastroOS, $id_ticket_setor, $id_assunto, $id_contrato_plano_pessoa){

        $id_contrato_plano_pessoa = intval($id_contrato_plano_pessoa);
        $integra = DBRead('', 'tb_integracao_contrato', "WHERE id_contrato_plano_pessoa = $id_contrato_plano_pessoa");

        //ixc
        //salvaAtendimentosPendentes para o sistema IXCSoft
        if($integra[0]['id_integracao'] == 1){

            /////////////////////////////////////////// VERIFICAR //////////////////////////////////////////////////////////
            require_once "integracoes/ixc/Parametros.php";
			require_once "integracoes/ixc/WebServiceClient.php";
			
			$parametros = new Integracao\Ixc\Parametros();
			$parametros->setParametros($id_contrato_plano_pessoa);
			$api = new IXCsoft\WebserviceClient($parametros->getHost(), $parametros->getToken(), $parametros->getSelfSigned());

			$api->get('gerar_protocolo_atendimento');
			$retorno_protocolo = $api->getRespostaConteudo(true);
            ////////////////////////////////////////////////////////////////////////////////////////////////////////////////

            //Condição para cadastro com o recurso atendimento.
            if($cadastroOS == 'atendimento'){
                require "integracoes/ixc/Atendimento.php";
                $atendimento = new Integracao\Ixc\Atendimento();
                $retorno = $atendimento->post($protocolo, $id_cliente, $id_login, $id_contrato, $id_assunto, $descricao_assunto, $origem_endereco, $endereco, $latitude, $longitude, $id_wfl_processo, $id_ticket_setor, $id_responsavel_tecnico, $prioridade, $id_ticket_origem, $id_usuarios, $id_resposta, $mensagem, $su_status, $status, $id_su_diagnostico, $atualizar_cliente, $latitude_cli, $longitude_cli, $atualizar_login, $latitude_login, $longitude_login, $tipoRetorno, $id_contrato_plano_pessoa);


            //Condição para cadastro com o recurso OrdemServico.
            }else if($cadastroOS == 'os'){
                require "integracoes/ixc/OrdemServico.php";
                $ordemServico = new Integracao\Ixc\OrdemServico();
                $retorno = $ordemServico->post($tipo, $id_ticket, $protocolo, $assunto, $id_cliente, $id_estrutura, $filial, $login, $contrato, $origem_endereco, $origem_endereco_estrutura, $latitude, $longitude, $prioridade, $melhor_horario_agenda, $setor, $id_tecnico, $mensagem, $idx, $status, $gera_comissao, $liberado, $impresso, $id_su_diagnostico, $id_cidade, $bairro, $endereco, $data_abertura, $data_inicio, $data_hora_analise, $data_agenda, $data_hora_encaminhado, $data_hora_assumido, $data_hora_execucao, $data_final, $data_fechamento, $mensagem_resposta, $justificativa_sla_atrasado, $valor_total, $valor_outras_despesas, $valor_unit_comissao, $valor_total_comissao, $id_contrato_plano_pessoa);

            }

            $dados = array(
                'salvo' => '1'
            );

            DBUpdate('', 'tb_integracao_atendimento_ixc', $dados, "id_integracao_atendimento_ixc = $id_integracao_atendimento_ixc");
        }

        return $retorno;
    }

    /** Função responsável por receber a situação selecionada pelo atendente no fim do fluxo de atendimento no sistema Simples e retornar os dados de setor ou departamento de acordo
     * com o que está configurado na tabela 'tb_integracao_contrato_parametro' que pode variar de empresa para empresa de acordo com o seu procedimento no sistema IXC
     */
    public function verificaSituacao($classificacao, $situacao, $id_contrato_plano_pessoa, $id_setor_default){

        //Trás os dados obrigatório de select
        $dados_obrigatorios = DBRead('', 'tb_dados_obrigatorios_integracao', "WHERE id_integracao = 1 AND id_contrato_plano_pessoa = '$id_contrato_plano_pessoa'");

        //Busca o default de setor ou departamento
        /*$setor_default = DBRead('', 'tb_integracao_valores_default', "WHERE id_contrato_plano_pessoa = '$id_contrato_plano_pessoa' AND id_integracao_campos_default = '7'");
        $departamento_default = DBRead('', 'tb_integracao_valores_default', "WHERE id_contrato_plano_pessoa = '$id_contrato_plano_pessoa' AND id_integracao_campos_default = '8'");*/

        $retorno = "<option value='0'></option>";

        //Se situação = 3 - 'ATENDIMENTO ENCERRADO.'
        if($situacao == "3" || $situacao == "4"){

            //se valor = 'atendimento' deve-se configurar o select setor com os dados de departamento
            if($classificacao == "2"){
                //Envia DepartamentoAtendimento (id_ticket_setor)
                foreach($dados_obrigatorios as $conteudo){

                    if($conteudo['chave'] == "departamento"){
                        //Pegar o value correspondente, não somente o primeiro que encontra
                        /*foreach($departamento_default as $key => $departamento){
                            if ($departamento_default[$key]['value_default'] == $conteudo['valor_id']) {
                                $retorno .= "<option selected='selected' value='" . $conteudo['valor_id'] . "'>" . $conteudo['valor_descricao'] . "</option>";
                            } else {
                                $retorno .= "<option value='" . $conteudo['valor_id'] . "'>" . $conteudo['valor_descricao'] . "</option>";
                            }
                        }*/
                        if ($id_setor_default == $conteudo['valor_id']) {
                            $retorno .= "<option selected='selected' value='" . $conteudo['valor_id'] . "'>" . $conteudo['valor_descricao'] . "</option>";
                        } else {
                            $retorno .= "<option value='" . $conteudo['valor_id'] . "'>" . $conteudo['valor_descricao'] . "</option>";
                        }
                    }
                }

            //se valor = 'atendimento' deve-se configurar o select setor com os dados de setor
            }else if($classificacao == "1"){
                //Envia Setor (id_setor)
                foreach($dados_obrigatorios as $conteudo){

                    if($conteudo['chave'] == "setor"){
                        //Pegar o value correspondente, não somente o primeiro que encontra
                        /*foreach($setor_default as $key => $setor){
                            if ($setor_default[$key]['value_default'] == $conteudo['valor_id']) {
                                $retorno .= "<option selected='selected' value='" . $conteudo['valor_id'] . "'>" . $conteudo['valor_descricao'] . "</option>";
                            } else {
                                $retorno .= "<option value='" . $conteudo['valor_id'] . "'>" . $conteudo['valor_descricao'] . "</option>";
                            }
                        }*/
                        if ($id_setor_default == $conteudo['valor_id']) {
                            $retorno .= "<option selected='selected' value='" . $conteudo['valor_id'] . "'>" . $conteudo['valor_descricao'] . "</option>";
                        } else {
                            $retorno .= "<option value='" . $conteudo['valor_id'] . "'>" . $conteudo['valor_descricao'] . "</option>";
                        }
                    }
                }

            //se não houver valor possivelmente é utilizado algum dos recursos de status de ordem de serviço, ex.: FinalizarOrdemServico ou AnalisarOrdemServico, no caso de AnalisarOrdemServico é utilizado setor, no caso de FinalizarOrdemServico não é necessário enviar esse dado; Se for necessário criar recursos no sistema Simples para a configuração de um novo status de ordem de serviço é preciso verificar na documentação da API de integração do sistema IXC se é usado essa informação na persistência do novo status, se sim, se é utilizado setor ou departamento.
            }else{
                //Envia Setor (id_setor) | utilizado em AnalisarOrdemServico
                foreach($dados_obrigatorios as $conteudo){
                    
                    if($conteudo['chave'] == "setor"){
                        $retorno .= "<option value='".$conteudo['valor_id']."'>".$conteudo['valor_descricao']."</option>";
                    }
                }
            }

        }else if($situacao == 5 || $situacao == 4){ //Se situação = 5 - 'ATENDIMENTO ENCAMINHADO AO SETOR RESPONSÁVEL.' ou se situação = 4 - 'ATENDIMENTO ENCAMINHADO AO SUPORTE AVANÇADO.'
            
            //Trás o valor da tabela 'tb_integracao_contrato_parametro' de acordo com o código 'cadastroAtendimentoEncaminhado' na tabela 'tb_integracao_parametro'
            //$valor = DBRead('', 'tb_integracao_parametro a', "INNER JOIN tb_integracao_contrato_parametro b ON a.id_integracao_parametro = b.id_integracao_parametro INNER JOIN tb_integracao_contrato c ON b.id_integracao_contrato = c.id_integracao_contrato WHERE codigo = 'cadastroAtendimentoEncaminhado' AND a.id_integracao = 1 AND c.id_contrato_plano_pessoa = '$id_contrato_plano_pessoa'", "b.valor AS valor");

            //se valor = 'atendimento' deve-se configurar o select setor com os dados de departamento
            if($classificacao == "2"){
                //Envia DepartamentoAtendimento (id_ticket_setor)
                foreach($dados_obrigatorios as $conteudo){

                    if($conteudo['chave'] == "departamento"){
                        $retorno .= "<option value='".$conteudo['valor_id']."'>".$conteudo['valor_descricao']."</option>";
                    }
                }

            //se valor = 'atendimento' deve-se configurar o select setor com os dados de setor
            }else if($classificacao == "1"){
                //Envia Setor (id_setor)
                foreach($dados_obrigatorios as $conteudo){

                    if($conteudo['chave'] == "setor"){
                        $retorno .= "<option value='".$conteudo['valor_id']."'>".$conteudo['valor_descricao']."</option>";
                    }
                }

            //se não houver valor possivelmente é utilizado algum dos recursos de status de ordem de serviço, ex.: FinalizarOrdemServico ou AnalisarOrdemServico, no caso de AnalisarOrdemServico é utilizado setor, no caso de FinalizarOrdemServico não é necessário enviar esse dado; Se for necessário criar recursos no sistema Simples para a configuração de um novo status de ordem de serviço é preciso verificar na documentação da API de integração do sistema IXC se é usado essa informação na persistência do novo status, se sim, se é utilizado setor ou departamento.
            }else{ 
                //Envia Setor (id_setor) | utilizado em AnalisarOrdemServico
                foreach($dados_obrigatorios as $conteudo){
                    
                    if($conteudo['chave'] == "setor"){
                        $retorno .= "<option value='".$conteudo['valor_id']."'>".$conteudo['valor_descricao']."</option>";
                    }
                }

            }

        }else if($situacao == 7){ ////Se situação = 7 - 'ATENDIMENTO VINCULADO A OS JÁ EXISTENTE.'
            
            //Trás o valor da tabela 'tb_integracao_contrato_parametro' de acordo com o código 'cadastroAtendimentoVinculado' na tabela 'tb_integracao_parametro'
            //$valor = DBRead('', 'tb_integracao_parametro a', "INNER JOIN tb_integracao_contrato_parametro b ON a.id_integracao_parametro = b.id_integracao_parametro INNER JOIN tb_integracao_contrato c ON b.id_integracao_contrato = c.id_integracao_contrato WHERE codigo = 'cadastroAtendimentoVinculado' AND a.id_integracao = 1 AND c.id_contrato_plano_pessoa = '$id_contrato_plano_pessoa'", "b.valor AS valor");

            //se valor = 'atendimento' deve-se configurar o select setor com os dados de departamento
            if($classificacao == "2"){
                //Envia DepartamentoAtendimento (id_ticket_setor)
                foreach($dados_obrigatorios as $conteudo){

                    if($conteudo['chave'] == "departamento"){
                        $retorno .= "<option value='".$conteudo['valor_id']."'>".$conteudo['valor_descricao']."</option>";
                    }
                }

            //se valor = 'atendimento' deve-se configurar o select setor com os dados de setor
            }else if($classificacao == "2"){
                //Envia Setor (id_setor)
                foreach($dados_obrigatorios as $conteudo){

                    if($conteudo['chave'] == "setor"){
                        $retorno .= "<option value='".$conteudo['valor_id']."'>".$conteudo['valor_descricao']."</option>";
                    }
                }

            //se não houver valor possivelmente é utilizado algum dos recursos de status de ordem de serviço, ex.: FinalizarOrdemServico ou AnalisarOrdemServico, no caso de AnalisarOrdemServico é utilizado setor, no caso de FinalizarOrdemServico não é necessário enviar esse dado; Se for necessário criar recursos no sistema Simples para a configuração de um novo status de ordem de serviço é preciso verificar na documentação da API de integração do sistema IXC se é usado essa informação na persistência do novo status, se sim, se é utilizado setor ou departamento.
            }else{
                //Envia Setor (id_setor) | utilizado em AnalisarOrdemServico
                foreach($dados_obrigatorios as $conteudo){
                    
                    if($conteudo['chave'] == "setor"){
                        $retorno .= "<option value='".$conteudo['valor_id']."'>".$conteudo['valor_descricao']."</option>";
                    }
                }

            }

        } //fim do else que verifica a situação
        return $retorno;
    }

    //Função responsável por utilizar o recurso de envio de boleto por e-mail ou sms para o cliente. Utilitário do sistema de gestão
    public function enviaBoleto($id_contrato_plano_pessoa, $id_assinante, $tipo_boleto){

        $id_contrato_plano_pessoa = intval($id_contrato_plano_pessoa);
        $integra = DBRead('', 'tb_integracao_contrato', "WHERE id_contrato_plano_pessoa = $id_contrato_plano_pessoa");

        //ixc
        //enviaBoleto para o sistema IXCSoft
        if($integra[0]['id_integracao'] == 1){

            require_once "integracoes/ixc/Parametros.php";
            require_once "integracoes/ixc/WebServiceClient.php";
            require_once "integracoes/ixc/ContasReceber.php";
			
			$parametros = new Integracao\Ixc\Parametros();
			$parametros->setParametros($id_contrato_plano_pessoa);
            $api = new IXCsoft\WebserviceClient($parametros->getHost(), $parametros->getToken(), $parametros->getSelfSigned());
            
            $contaReceber = new Integracao\Ixc\ContasReceber();
            $parcela = $contaReceber->get('fn_areceber.id_cliente', $id_assinante, '=', '1', '20000', 'fn_areceber.id', 'asc', true, $id_contrato_plano_pessoa);

            $params = array(
                'boletos' => $parcela['registros'][0]['id'],
                'juro' => 'N',
                'multa' => 'N',
                'atualiza_boleto' => 'N',
                'tipo_boleto' => $tipo_boleto
            );

            $api->get('get_boleto',$params);
            $retorno = $api->getRespostaConteudo(true);

            return $retorno;
        }
    }

    public function buscaPlanosVelocidade($id_contrato_plano_pessoa, $id_contrato_ixc, $plano_venda){

        $id_contrato_plano_pessoa = intval($id_contrato_plano_pessoa);
        $integra = DBRead('', 'tb_integracao_contrato', "WHERE id_contrato_plano_pessoa = $id_contrato_plano_pessoa");

        //ixc
        //buscaPlanosVelocidade para o sistema IXCSoft
        if($integra[0]['id_integracao'] == 1){

            //Recursos utilizados diretamente devido a demanda urgente; AJUSTAR
            require_once "integracoes/ixc/Parametros.php";
            require_once "integracoes/ixc/WebServiceClient.php";

            $parametros = new Integracao\Ixc\Parametros();
            $parametros->setParametros($id_contrato_plano_pessoa);
            $api = new IXCsoft\WebserviceClient($parametros->getHost(), $parametros->getToken(), $parametros->getSelfSigned());

            $params = array(
                'qtype' => 'cliente_contrato.id',
                'query' => $id_contrato_ixc,
                'oper' => '=',
                'page' => '1',
                'rp' => '20',
                'sortname' => 'cliente_contrato.id',
                'sortorder' => 'desc'
            );
            $api->get('cliente_contrato', $params);
            $retorno = $api->getRespostaConteudo(true);

            $params2 = array(
                'qtype' => 'vd_contratos_produtos.id_vd_contrato',
                'query' => $retorno['registros'][0]['id_vd_contrato'],
                'oper' => '=',
                'page' => '1',
                'rp' => '20',
                'sortname' => 'vd_contratos_produtos.id',
                'sortorder' => 'desc',
                'grid_param' => json_encode(array(array('TB' => 'vd_contratos_produtos.id_plano', 'OP' => '!=', 'P' => '0'))), 
            );
            $api->get('vd_contratos_produtos', $params2);
            $retorno2 = $api->getRespostaConteudo(true);

            if ($retorno2['type'] == 'error') {

                $retorno3 = [
                    "registros" => [
                        "0" => [
                            "download" => "Não disponivel",
                            "upload" => "Não disponivel"
                        ]
                    ]
                ];

                return $retorno3;

            } else {

                $params3 = array(
                    'qtype' => 'radgrupos.id',
                    'query' => $retorno2['registros'][0]['id_plano'],
                    'oper' => '=',
                    'page' => '1',
                    'rp' => '20',
                    'sortname' => 'radgrupos.id',
                    'sortorder' => 'desc'
                );
                $api->get('radgrupos', $params3);
                $retorno3 = $api->getRespostaConteudo(true);// false para json | true para array
                
                return $retorno3;
            }
        }
    }

    //Métodos para buscar todas as ações efetuadas em uma ordem de serviço
    /*public function buscaEventoAbertura($id_contrato_plano_pessoa, $id_os){

        
    }*/

    public function buscaEventoAnalisar($id_contrato_plano_pessoa, $id_os){
        $id_contrato_plano_pessoa = intval($id_contrato_plano_pessoa);
        $integra = DBRead('', 'tb_integracao_contrato', "WHERE id_contrato_plano_pessoa = $id_contrato_plano_pessoa");
        if($integra[0]['id_integracao'] == 1){
            require_once "integracoes/ixc/AnalisarOrdemServico.php";
            $analisar = new Integracao\Ixc\AnalisarOrdemServico();
            $retorno = $analisar->get('id_chamado', $id_os, '=', '1', '20000', '', '', true, $id_contrato_plano_pessoa);
        }
        return $retorno;
    }

    public function buscaEventoAgendar($id_contrato_plano_pessoa, $id_os){
        $id_contrato_plano_pessoa = intval($id_contrato_plano_pessoa);
        $id_os = intval($id_os);
        $integra = DBRead('', 'tb_integracao_contrato', "WHERE id_contrato_plano_pessoa = $id_contrato_plano_pessoa");
        if($integra[0]['id_integracao'] == 1){
            require_once "integracoes/ixc/AgendarOrdemServico.php";
            $agendar = new Integracao\Ixc\AgendarOrdemServico();
            $retorno = $agendar->get('id', '', '=', '1', '20000', '', '', true, $id_contrato_plano_pessoa);
        }
        return $retorno;
    }

    public function buscaInteracaoAtendimento($id_contrato_plano_pessoa, $id_atendimento){
        $id_contrato_plano_pessoa = intval($id_contrato_plano_pessoa);
        $id_atendimento = intval($id_atendimento);
        $integra = DBRead('', 'tb_integracao_contrato', "WHERE id_contrato_plano_pessoa = $id_contrato_plano_pessoa");
        if($integra[0]['id_integracao'] == 1){

            require_once "integracoes/ixc/Atendimento.php";
            $mensagem = new Integracao\Ixc\Atendimento();
            $retorno = $mensagem->getInteracaoAtendimento('su_mensagens.id_ticket', $id_atendimento, '=', '1', '20000', 'su_mensagens.id', 'desc', true, $id_contrato_plano_pessoa);

        }
        return $retorno;
    }

    public function desconectarLogin($id_contrato_plano_pessoa, $id_login){
        $id_contrato_plano_pessoa = intval($id_contrato_plano_pessoa);
        $id_login = intval($id_login);
        $integra = DBRead('', 'tb_integracao_contrato', "WHERE id_contrato_plano_pessoa = $id_contrato_plano_pessoa");
        if($integra[0]['id_integracao'] == 1){
            require_once "integracoes/ixc/DesconectarLogin.php";
            $desconectar = new Integracao\Ixc\DesconectarLogin();
            $retorno = $desconectar->desconectarLogin($id_contrato_plano_pessoa, $id_login);
        }
        return $retorno;
    }

    public function gerarDiagnostico($id_contrato_plano_pessoa, $username){
        $id_contrato_plano_pessoa = intval($id_contrato_plano_pessoa);
        $integra = DBRead('', 'tb_integracao_contrato', "WHERE id_contrato_plano_pessoa = $id_contrato_plano_pessoa");
        if($integra[0]['id_integracao'] == 1){
            require_once "integracoes/ixc/Radacct.php";
            $diagnostico = new Integracao\Ixc\Radacct();
            //$retorno = $diagnostico->diagnostico($id_contrato_plano_pessoa, $username);
            $retorno = $diagnostico->diagnostico($id_contrato_plano_pessoa, $username);
        }
        return $retorno;
    }

    public function zerarMac($id_contrato_plano_pessoa, $id_login){
        $id_contrato_plano_pessoa = intval($id_contrato_plano_pessoa);
        $integra = DBRead('', 'tb_integracao_contrato', "WHERE id_contrato_plano_pessoa = $id_contrato_plano_pessoa");
        if($integra[0]['id_integracao'] == 1){
            require_once "integracoes/ixc/Login.php";
            $login = new Integracao\Ixc\Login();
            $retorno = $login->put($id_contrato_plano_pessoa, $id_login);
        }
        return $retorno;
    }

    public function buscaProcesso($id_contrato_plano_pessoa,  $id_processo = ""){

        $id_contrato_plano_pessoa = intval($id_contrato_plano_pessoa);
        $id_processo = (!empty($id_processo)) ? $id_processo : '';

        $integra = DBRead('', 'tb_integracao_contrato', "WHERE id_contrato_plano_pessoa = $id_contrato_plano_pessoa");
        if($integra[0]['id_integracao'] == 1){
            require_once "integracoes/ixc/Processo.php";
            $processo = new Integracao\Ixc\Processo();
            $retorno = $processo->get('wfl_processo.id', $id_processo, '=', true, $id_contrato_plano_pessoa);
        }
        return $retorno;
    }

    public function buscaContasReceber($id_contrato_plano_pessoa, $id_assinante){

        $id_contrato_plano_pessoa = intval($id_contrato_plano_pessoa);
        $integra = DBRead('', 'tb_integracao_contrato', "WHERE id_contrato_plano_pessoa = $id_contrato_plano_pessoa");

        //ixc
        //enviaBoleto para o sistema IXCSoft
        if($integra[0]['id_integracao'] == 1){

            require_once "integracoes/ixc/ContasReceber.php";
            $contasReceber = new Integracao\Ixc\ContasReceber();
            $retorno = $contasReceber->get('fn_areceber.id_cliente',  $id_assinante, '=', '1', '20000', 'fn_areceber.id', 'asc', true, $id_contrato_plano_pessoa);
        }
        return $retorno;
    }

    //Método utilitário que realiza a liberacao de reducao de velocidade do cliente do provedor
    public function liberarReducaoVelocidade($id_contrato_plano_pessoa, $id_contrato){

        $id_contrato_plano_pessoa = intval($id_contrato_plano_pessoa);
        $integra = DBRead('', 'tb_integracao_contrato', "WHERE id_contrato_plano_pessoa = $id_contrato_plano_pessoa");

        //ixc
        if($integra[0]['id_integracao'] == 1){

            require_once "integracoes/ixc/Parametros.php";
            require_once "integracoes/ixc/WebServiceClient.php";
            require_once "integracoes/ixc/ContratoCliente.php";
			
			$parametros = new Integracao\Ixc\Parametros();
			$parametros->setParametros($id_contrato_plano_pessoa);
            $api = new IXCsoft\WebserviceClient($parametros->getHost(), $parametros->getToken(), $parametros->getSelfSigned());
        
            $params = array(
                'get_id' => $id_contrato,
            );

            $api->get('liberacao_reducao_contrato_29157',$params);
            $retorno = $api->getRespostaConteudo(true);
        }

        return $retorno;
    }
}
