<?php
namespace Integracao\Ixc;

require_once "Parametros.php";
require_once "WebServiceClient.php";

require_once "Assunto.php";

use IXCsoft\WebserviceClient as WebserviceClient;
use Integracao\Ixc\Parametros as Parametros;
use Integracao\Ixc\Assunto as Assunto;

class OrdemServico{

    public function getAssunto($id){
        $assunto = new Assunto();
        $retorno_assunto = $assunto->get('su_oss_assunto.id', $id, false, $id_contrato_plano_pessoa);
        return $retorno_assunto;
    }

    public function getFinalizados($qtype = 'su_oss_chamado.id_cliente', $query = '', $oper = '=', $page = '1', $sortname = 'su_oss_chamado.id', $sortorder = 'desc', $tipoRetorno = true, $id_contrato_plano_pessoa){

        $parametros = new Parametros();
        $parametros->setParametros($id_contrato_plano_pessoa);
        $api = new WebserviceClient($parametros->getHost(), $parametros->getToken(), $parametros->getSelfSigned());
        $params = array(
            'qtype' => $qtype,
            'query' => $query,
            'oper' => $oper,
            'page' => $page,
            'rp' => 5,
            'sortname' => $sortname,
            'sortorder' => $sortorder,
            'grid_param' => json_encode(array(array('TB' => 'su_oss_chamado.status', 'OP' => '=', 'P' => 'F')))
        );
        $api->get('su_oss_chamado', $params);
        $retorno = $api->getRespostaConteudo($tipoRetorno);
        return $retorno;
    }

    public function get($qtype = 'su_oss_chamado.id_cliente', $query = '', $oper = '=', $page = '1', $rp = '20000', $sortname = 'su_oss_chamado.id', $sortorder = 'desc', $tipoRetorno = false, $id_contrato_plano_pessoa){
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
            'grid_param' => json_encode(array(array('TB' => 'su_oss_chamado.status', 'OP' => '!=', 'P' => 'F')))
        );
        $api->get('su_oss_chamado', $params);
        $retorno = $api->getRespostaConteudo($tipoRetorno);
        return $retorno;
    }

    public function post($tipo, $id_ticket, $protocolo, $assunto, $id_cliente, $id_estrutura, $filial, $login, $contrato, $origem_endereco, $origem_endereco_estrutura, $latitude, $longitude, $prioridade, $melhor_horario_agenda, $setor, $id_tecnico, $mensagem, $idx, $status, $gera_comissao, $liberado, $impresso, $id_su_diagnostico, $id_cidade, $bairro, $endereco, $data_abertura, $data_inicio, $data_hora_analise, $data_agenda, $data_hora_encaminhado, $data_hora_assumido, $data_hora_execucao, $data_final, $data_fechamento, $mensagem_resposta, $justificativa_sla_atrasado, $valor_total, $valor_outras_despesas, $valor_unit_comissao, $valor_total_comissao, $tipoRetorno = false, $id_contrato_plano_pessoa){

        $parametros = new Parametros();
        $parametros->setParametros($id_contrato_plano_pessoa);
        $api = new WebserviceClient($parametros->getHost(), $parametros->getToken(), $parametros->getSelfSigned());
        $dados = array(
            'tipo' => $tipo,
            'id_ticket' => $id_ticket,
            'protocolo' => $protocolo,
            'id_assunto' => $assunto,
            'id_cliente' => $id_cliente,
            'id_estrutura' => $id_estrutura,
            'id_filial' => $filial,
            'id_login' => $login,
            'id_contrato_kit' => $contrato,
            'origem_endereco' => $origem_endereco,
            'origem_endereco_estrutura' => $origem_endereco_estrutura,
            'latitude' => $latitude,
            'longitude' => $longitude,
            'prioridade' => $prioridade,
            'melhor_horario_agenda' => $melhor_horario_agenda,
            'setor' => $setor,
            'id_tecnico' => $id_tecnico,
            'mensagem' => $mensagem,
            'idx' => $idx,
            'status' => $status,
            'gera_comissao' => $gera_comissao,
            'liberado' => $liberado,
            'impresso' => $impresso,
            'id_su_diagnostico' => $id_su_diagnostico,
            'id_cidade' => $id_cidade,
            'bairro' => $bairro,
            'endereco' => $endereco,
            'data_abertura' => $data_abertura,
            'data_inicio' => $data_inicio,
            'data_hora_analise' => $data_hora_analise,
            'data_agenda' => $data_agenda,
            'data_hora_encaminhado' => $data_hora_encaminhado,
            'data_hora_assumido' => $data_hora_assumido,
            'data_hora_execucao' => $data_hora_execucao,
            'data_final' => $data_final,
            'data_fechamento' => $data_fechamento,
            'mensagem_resposta' => $mensagem_resposta,
            'justificativa_sla_atrasado' => $justificativa_sla_atrasado,
            'valor_total' => $valor_total,
            'valor_outras_despesas' => $valor_outras_despesas,
            'valor_unit_comissao' => $valor_unit_comissao,
            'valor_total_comissao' => $valor_total_comissao,
        );
        $api->post('su_oss_chamado', $dados);
        $retorno = $api->getRespostaConteudo($tipoRetorno);
        return $retorno;
    }
    
}