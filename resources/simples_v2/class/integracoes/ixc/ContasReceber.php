<?php
namespace Integracao\Ixc;

require_once "Parametros.php";
require_once "WebServiceClient.php";

use IXCsoft\WebserviceClient as WebserviceClient;
use Integracao\Ixc\Parametros as Parametros;

class ContasReceber{

    public function get($qtype = 'fn_areceber.id', $query = '', $oper = '=', $page = '1', $rp = '20000', $sortname = 'fn_areceber.id', $sortorder = 'asc', $tipoRetorno = false, $id_contrato_plano_pessoa){

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
            'grid_param' => json_encode(array(array('TB' => 'fn_areceber.status', 'OP' => '=', 'P' => 'A')))
        );
        
        $api->get('fn_areceber', $params);
        $retorno = $api->getRespostaConteudo($tipoRetorno);
        return $retorno;
    }
}