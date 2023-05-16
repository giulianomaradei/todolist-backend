<?php
namespace Integracao\Ixc;

require_once "Parametros.php";
require_once "WebServiceClient.php";

use IXCsoft\WebserviceClient as WebserviceClient;
use Integracao\Ixc\Parametros as Parametros;

class AnalisarOrdemServico{

    //Método que trás todas as interações de ações de ordens de serviço independete se o status é A(aberto), AN(análise), AG(agendamento) e etc.
    public function get($qtype = 'id', $query = '', $oper = '=', $page = '1', $rp = '20000', $sortname = '', $sortorder = '', $tipoRetorno = false, $id_contrato_plano_pessoa){

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

        $api->get('su_oss_chamado_analisar', $params);
        $retorno = $api->getRespostaConteudo($tipoRetorno);
        
        return $retorno;
    }

    public function post($id_chamado, $id_setor, $id_tecnico, $mensagem, $data_inicio, $data_final, $id_contrato_plano_pessoa){

        $parametros = new Parametros();
        $parametros->setParametros($id_contrato_plano_pessoa);
        $api = new WebserviceClient($parametros->getHost(), $parametros->getToken(), $parametros->getSelfSigned());

        $dados = array(
            'id_chamado' => $id_chamado,
            'id_setor' => $id_setor,
            'id_tecnico' => $id_tecnico,
            'mensagem' => $mensagem,
            'data_inicio' => $data_inicio,
            'data_final' => $data_final,
            'status' => 'AN'
        );

        $api->post('su_oss_chamado_analisar', $dados);
        $retorno = $api->getRespostaConteudo(true);
        return $retorno;
    }
}