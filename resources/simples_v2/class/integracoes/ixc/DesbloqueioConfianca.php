<?php
namespace Integracao\Ixc;

require_once "Parametros.php";
require_once "WebServiceClient.php";

use IXCsoft\WebserviceClient as WebserviceClient;
use Integracao\Ixc\Parametros as Parametros;

class DesbloqueioConfianca{

    public function get($id_contrato){

        $parametros = new Parametros();
        $api = new WebserviceClient($parametros->getHost(), $parametros->getToken(), $parametros->getSelfSigned());

        $params = array(
            'id' => $id_contrato
        );

        $api->get('desbloqueio_confianca', $params);
        $retorno_desbloqueio_confianca = $api->getRespostaConteudo(false);
    }
}