<?php
namespace Integracao\Ixc;

require_once "Parametros.php";
require_once "WebServiceClient.php";

use IXCsoft\WebserviceClient as WebserviceClient;
use Integracao\Ixc\Parametros as Parametros;

class DesconectarLogin{

    public function desconectarLogin($id_contrato_plano_pessoa, $id_login){

        $parametros = new Parametros();
        $parametros->setParametros($id_contrato_plano_pessoa);
        $api = new WebserviceClient($parametros->getHost(), $parametros->getToken(), $parametros->getSelfSigned());

        $params = array(
            'id' => $id_login
        );
        
        $api->get('desconectar_clientes', $params);
        $retorno = $api->getRespostaConteudo(true);
        return $retorno;
    }
}