<?php
namespace Integracao\Ixc;

require_once "Parametros.php";
require_once "WebServiceClient.php";

use IXCsoft\WebserviceClient as WebserviceClient;
use Integracao\Ixc\Parametros as Parametros;

class DepartamentoAtendimento{

    public function get($qtype = 'su_ticket_setor.id', $query = '', $tipoRetorno = false, $id_contrato_plano_pessoa){

        $parametros = new Parametros();
        $parametros->setParametros($id_contrato_plano_pessoa);
        $api = new WebserviceClient($parametros->getHost(), $parametros->getToken(), $parametros->getSelfSigned());

        $params = array(
            'qtype' => $qtype,
            'query' => $query,
            'oper' => '=',
            'page' => '1',
            'rp' => '20000',
            'sortname' => 'su_ticket_setor.id',
            'sortorder' => 'desc'
        );
        $api->get('su_ticket_setor', $params);
        $retorno = $api->getRespostaConteudo($tipoRetorno);
        
        return $retorno;
    }
}