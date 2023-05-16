<?php
namespace Integracao\Ixc;

require_once "Parametros.php";
require_once "WebServiceClient.php";
require_once "Cidade.php";

use IXCsoft\WebserviceClient as WebserviceClient;
use Integracao\Ixc\Parametros as Parametros;
use Integracao\Ixc\Cidade as Cidade;

class Cliente{

    public function get($qtype = 'cliente.id', $query = '', $oper = '=', $page = '1', $rp = '20000', $sortname = 'cliente.id', $sortorder = 'desc', $tipoRetorno = false, $id_contrato_plano_pessoa){

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
        
        $api->get('cliente', $params);
        $retorno = $api->getRespostaConteudo($tipoRetorno);
        return $retorno;
    }
}