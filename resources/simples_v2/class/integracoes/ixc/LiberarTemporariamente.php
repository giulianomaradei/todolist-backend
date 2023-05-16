<?php
namespace Integracao\Ixc;

require_once "Parametros.php";
require_once "WebServiceClient.php";

use IXCsoft\WebserviceClient as WebserviceClient;
use Integracao\Ixc\Parametros as Parametros;

class LiberarTemporariamente{

    public function get($id_contrato){

        $parametros = new Parametros();
        $api = new WebserviceClient($parametros->getHost(), $parametros->getToken(), $parametros->getSelfSigned());

        $params = array(
            'id' => $id_contrato
        );

        $api->get('cliente_contrato_btn_lib_temp_24722', $params);
        $retorno_liberar_temporariamente = $api->getRespostaConteudo(false);
    }
}