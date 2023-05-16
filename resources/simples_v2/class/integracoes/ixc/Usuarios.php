<?php
namespace Integracao\Ixc;

require_once "Parametros.php";
require_once "WebServiceClient.php";

use IXCsoft\WebserviceClient as WebserviceClient;
use Integracao\Ixc\Parametros as Parametros;

class Usuarios{

    public function get($qtype = 'usuarios.id', $query = '', $tipoRetorno = false, $id_contrato_plano_pessoa){

        $parametros = new Parametros();
        $parametros->setParametros($id_contrato_plano_pessoa);
        $api = new WebserviceClient($parametros->getHost(), $parametros->getToken(), $parametros->getSelfSigned());

        $params = array(
            'qtype' => $qtype,
            'query' => $query,
            'oper' => '=',
            'page' => '1',
            'rp' => '200000',
            'sortname' => 'usuarios.id',
            'sortorder' => 'desc'
        );
        $api->get('usuarios', $params);
        $retorno = $api->getRespostaConteudo($tipoRetorno);
        return $retorno;
    }
}