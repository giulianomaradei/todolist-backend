<?php
namespace Integracao\Ixc;

require_once "Parametros.php";
require_once "WebServiceClient.php";

use IXCsoft\WebserviceClient as WebserviceClient;
use Integracao\Ixc\Parametros as Parametros;

class Setor{

    public function get($qtype = 'empresa_setor.id', $query = '', $oper = '=', $tipoRetorno = false, $id_contrato_plano_pessoa){

        $parametros = new Parametros();
        $parametros->setParametros($id_contrato_plano_pessoa);
        $api = new WebserviceClient($parametros->getHost(), $parametros->getToken(), $parametros->getSelfSigned());

        $params = array(
            'qtype' => $qtype,
            'query' => $query,
            'oper' => $oper,
            'page' => '1',
            'rp' => '2000000',
            'sortname' => 'empresa_setor.id',
            'sortorder' => 'desc'
        );
        $api->get('empresa_setor', $params);
        $retorno = $api->getRespostaConteudo($tipoRetorno);
        
        return $retorno;
    }
}