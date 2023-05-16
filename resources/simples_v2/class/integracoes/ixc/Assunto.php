<?php
namespace Integracao\Ixc;

require_once "Parametros.php";
require_once "WebServiceClient.php";

use IXCsoft\WebserviceClient as WebserviceClient;
use Integracao\Ixc\Parametros as Parametros;

class Assunto{

    public function get($qtype = 'su_oss_assunto.id', $query = '', $tipoRetorno = false, $id_contrato_plano_pessoa){

        $parametros = new Parametros();
        $parametros->setParametros($id_contrato_plano_pessoa);
        $api = new WebserviceClient($parametros->getHost(), $parametros->getToken(), $parametros->getSelfSigned());

        $params = array(
            'qtype' => $qtype,
            'query' => $query,
            'oper' => '=',
            'page' => '1',
            'rp' => '200000',
            'sortname' => 'su_oss_assunto.assunto',
            'sortorder' => 'asc',
            'grid_param' => json_encode(array(array('TB' => 'su_oss_assunto.ativo', 'OP' => '=', 'P' => 'S')))
        );
        $api->get('su_oss_assunto', $params);
        $retorno = $api->getRespostaConteudo($tipoRetorno);
        
        return $retorno;
    }
}