<?php
//Classe responsável por gerar o relatório de diagnóstico de conexão do cliente

namespace Integracao\Ixc;

require_once "Parametros.php";
require_once "WebServiceClient.php";

use IXCsoft\WebserviceClient as WebserviceClient;
use Integracao\Ixc\Parametros as Parametros;

class Radacct{

    public function diagnostico($id_contrato_plano_pessoa, $username){

        $parametros = new Parametros();
        $parametros->setParametros($id_contrato_plano_pessoa);
        $api = new WebserviceClient($parametros->getHost(), $parametros->getToken(), $parametros->getSelfSigned());

        $params = array(
            'qtype' => 'radacct.username',
            'query' => $username,
            'oper' => '=',
            'page' => '1',
            'rp' => '10',
            'sortname' => 'radacct.radacctid',
            'sortorder' => 'desc'
        );

        $api->get('radacct', $params);
        $retorno = $api->getRespostaConteudo(true);
        return $retorno;
    }
}