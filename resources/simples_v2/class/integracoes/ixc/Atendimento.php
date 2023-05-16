<?php
namespace Integracao\Ixc;

require_once "Parametros.php";
require_once "WebServiceClient.php";

//require_once "Assunto.php";

use IXCsoft\WebserviceClient as WebserviceClient;
use Integracao\Ixc\Parametros as Parametros;
use Integracao\Ixc\Assunto as Assunto;

class Atendimento{

    //fechados
    public function getSolucionados($qtype = 'su_ticket.id', $query = '', $oper = '=', $page = '1', $sortname = 'su_ticket.id', $sortorder = 'desc', $tipoRetorno = true, $id_contrato_plano_pessoa){

        $parametros = new Parametros();
        $parametros->setParametros($id_contrato_plano_pessoa);
        $api = new WebserviceClient($parametros->getHost(), $parametros->getToken(), $parametros->getSelfSigned());
        $params = array(
            'qtype' => $qtype,
            'query' => $query,
            'oper' => $oper,
            'page' => $page,
            'rp' => 2000000,
            'sortname' => $sortname,
            'sortorder' => $sortorder,
            'grid_param' => json_encode(array(array('TB' => 'su_ticket.su_status', 'OP' => '=', 'P' => 'S')))
        );
        
        $api->get('su_ticket', $params);
        $retorno = $api->getRespostaConteudo($tipoRetorno);
        return $retorno;
    }

    //abertos
    public function get($qtype = 'su_ticket.id', $query = '', $oper = '=', $page = '1', $rp = '20000', $sortname = 'su_ticket.id', $sortorder = 'desc', $tipoRetorno = true, $id_contrato_plano_pessoa){
        $parametros = new Parametros();
        $parametros->setParametros($id_contrato_plano_pessoa);
        $api = new WebserviceClient($parametros->getHost(), $parametros->getToken(), $parametros->getSelfSigned());
        $params = array(
            'qtype' => $qtype,
            'query' => $query,
            'oper' => $oper,
            'page' => $page,
            'rp' => $rp,
            'sortname' => $sortname,
            'sortorder' => $sortorder,
            'grid_param' => json_encode(array(array('TB' => 'su_ticket.su_status', 'OP' => '!=', 'P' => 'S'), array('TB' => 'su_ticket.su_status', 'OP' => '!=', 'P' => 'C')))
        );
        $api->get('su_ticket', $params);
        $retorno = $api->getRespostaConteudo($tipoRetorno);
        return $retorno;
    }

    public function post($protocolo, $id_cliente, $id_login, $id_contrato, $id_filial, $id_assunto, $titulo, $origem_endereco = 'M', $endereco, $latitude, $longitude, $id_wfl_processo, $id_ticket_setor, $id_responsavel_tecnico, $prioridade, $id_ticket_origem = 'I', $id_usuarios, $id_resposta, $menssagem, $su_status = 'N', $status = 'T', $id_su_diagnostico, $atualizar_cliente = 'S', $latitude_cli, $longitude_cli, $atualizar_login = 'S', $latitude_login, $longitude_login, $tipoRetorno = false, $id_contrato_plano_pessoa){

        $parametros = new Parametros();
        $parametros->setParametros($id_contrato_plano_pessoa);
        $api = new WebserviceClient($parametros->getHost(), $parametros->getToken(), $parametros->getSelfSigned());

        $dados = array(
            'protocolo' => $protocolo,
            'id_circuito' => '',
            'id_cliente' => $id_cliente,
            'id_login' => $id_login,
            'id_contrato' => $id_contrato,
            'id_filial' => $id_filial,
            'id_assunto' => $id_assunto,
            'titulo' => $titulo,
            'origem_endereco' => $origem_endereco,
            'endereco' => $endereco,
            'latitude' => $latitude,
            'longitude' => $longitude,
            'id_wfl_processo' => $id_wfl_processo,
            'id_ticket_setor' => $id_ticket_setor,
            'id_responsavel_tecnico' => $id_responsavel_tecnico,
            'prioridade' => $prioridade,
            'id_ticket_origem' => $id_ticket_origem,
            'id_usuarios' => $id_usuarios,
            'id_resposta' => $id_resposta,
            'menssagem' => $menssagem,
            'interacao_pendente' => 'N',
            'su_status' => $su_status,
            'id_evento_status_processo' => '',
            'status' => $status,
            'id_su_diagnostico' => $id_su_diagnostico,
            'atualizar_cliente' => $atualizar_cliente,
            'latitude_cli' => $latitude_cli,
            'longitude_cli' => $longitude_cli,
            'atualizar_login' => $atualizar_login,
            'latitude_login' => $latitude_login,
            'longitude_login' => $longitude_login,
            'tipo' => 'C'
        );

        $api->post('su_ticket', $dados);
        $retorno = $api->getRespostaConteudo($tipoRetorno);

        return $retorno;
    }

    public function put($protocolo, $id_cliente, $id_login, $id_contrato, $id_filial, $id_assunto, $titulo, $origem_endereco = 'M', $endereco, $latitude, $longitude, $id_wfl_processo, $id_ticket_setor, $id_responsavel_tecnico, $prioridade, $id_ticket_origem = 'I', $id_usuarios, $id_resposta, $menssagem, $su_status = 'N', $status = 'T', $id_su_diagnostico, $atualizar_cliente = 'S', $latitude_cli, $longitude_cli, $atualizar_login = 'S', $latitude_login, $longitude_login, $tipoRetorno = false, $id_contrato_plano_pessoa, $id_atendimento_sistema_gestao){

        $parametros = new Parametros();
        $parametros->setParametros($id_contrato_plano_pessoa);
        $api = new WebserviceClient($parametros->getHost(), $parametros->getToken(), $parametros->getSelfSigned());

        $dados = array(
            'protocolo' => $protocolo,
            'id_circuito' => '',
            'id_cliente' => $id_cliente,
            'id_login' => $id_login,
            'id_contrato' => $id_contrato,
            'id_filial' => $id_filial,
            'id_assunto' => $id_assunto,
            'titulo' => $titulo,
            'origem_endereco' => $origem_endereco,
            'endereco' => $endereco,
            'latitude' => $latitude,
            'longitude' => $longitude,
            'id_wfl_processo' => $id_wfl_processo,
            'id_ticket_setor' => $id_ticket_setor,
            'id_responsavel_tecnico' => $id_responsavel_tecnico,
            'prioridade' => $prioridade,
            'id_ticket_origem' => $id_ticket_origem,
            'id_usuarios' => $id_usuarios,
            'id_resposta' => $id_resposta,
            'menssagem' => $menssagem,
            'interacao_pendente' => 'N',
            'su_status' => $su_status,
            'id_evento_status_processo' => '',
            'status' => $status,
            'id_su_diagnostico' => $id_su_diagnostico,
            'atualizar_cliente' => $atualizar_cliente,
            'latitude_cli' => $latitude_cli,
            'longitude_cli' => $longitude_cli,
            'atualizar_login' => $atualizar_login,
            'latitude_login' => $latitude_login,
            'longitude_login' => $longitude_login
        );

        $registro = $id_atendimento_sistema_gestao;//registro a ser editado
        $api->put('su_ticket', $dados, $registro);
        $retorno = $api->getRespostaConteudo($tipoRetorno);

        return $retorno;
    }

    /** Método chamado para inserir uma interação de 'Solucionado' em atendimentos que são encerrados e usam esse procedimento de acordo com o quadro informativo de cada empresa. */
    public function interacaoAtendimento($id_atendimento, $id_cliente, $mensagem, $operador = '', $id_contrato_plano_pessoa, $tipoRetorno = false, $su_status = 'S'){

        $parametros = new Parametros();
        $parametros->setParametros($id_contrato_plano_pessoa);
        $api = new WebserviceClient($parametros->getHost(), $parametros->getToken(), $parametros->getSelfSigned());

        if($su_status == 'S'){
            $status = 'F';
        }else{
            $status = 'T';
        }

        $dados = array(
            'id_ticket' => $id_atendimento, // id do atendimento
            'status' => $status, // status
            'id_cliente' => $id_cliente, // cliente
            'mensagem' => $mensagem, // mensagem a ser inserida na interação
            'operador' => $operador, // operador responsável
            'su_status' => $su_status, // novo status
            'existe_pendencia_externa' => 'N',  // pendência
        );

        $api->post('su_mensagens', $dados);
        $retorno = $api->getRespostaConteudo($tipoRetorno);// false para json | true para array
        return $retorno;
    }

    public function getInteracaoAtendimento($qtype = 'su_mensagens.id', $query = '', $oper = '=', $page = '1', $rp = '20000', $sortname = 'su_mensagens.id', $sortorder = 'desc', $tipoRetorno = false, $id_contrato_plano_pessoa){

        $parametros = new Parametros();
        $parametros->setParametros($id_contrato_plano_pessoa);
        $api = new WebserviceClient($parametros->getHost(), $parametros->getToken(), $parametros->getSelfSigned());

        $params = array(
            'qtype' => $qtype,
            'query' => $query,
            'oper' => $oper,
            'page' => $page,
            'rp' => $rp,
            'sortname' => $sortname,
            'sortorder' => $sortorder
        );

        $api->get('su_mensagens', $params);
        $retorno = $api->getRespostaConteudo($tipoRetorno);
        return $retorno;
    }
}