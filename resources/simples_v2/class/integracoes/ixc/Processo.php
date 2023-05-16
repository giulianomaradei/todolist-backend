<?php
namespace Integracao\Ixc;

require_once "Parametros.php";
require_once "WebServiceClient.php";

use IXCsoft\WebserviceClient as WebserviceClient;
use Integracao\Ixc\Parametros as Parametros;

class Processo{

    public function get($qtype = 'wfl_processo.id', $query = '', $oper = '=', $tipoRetorno = false, $id_contrato_plano_pessoa){

        $parametros = new Parametros();
        $parametros->setParametros($id_contrato_plano_pessoa);
        $api = new WebserviceClient($parametros->getHost(), $parametros->getToken(), $parametros->getSelfSigned());

        $params = array(
            'qtype' => $qtype,
            'query' => $query,
            'oper' => $oper,
            'page' => '1',
            'rp' => '2000000',
            'sortname' => 'wfl_processo.descricao',
            'sortorder' => 'asc',
            //'grid_param' => json_encode(array(array('TB' => 'wfl_processo.ativo', 'OP' => '=', 'P' => 'S')))
        );
        $api->get('wfl_processo', $params);
        $retorno = $api->getRespostaConteudo($tipoRetorno);
        
        return $retorno;
    }
}