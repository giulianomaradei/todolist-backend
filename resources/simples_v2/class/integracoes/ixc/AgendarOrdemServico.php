<?php
namespace Integracao\Ixc;

require_once "Parametros.php";
require_once "WebServiceClient.php";

use IXCsoft\WebserviceClient as WebserviceClient;
use Integracao\Ixc\Parametros as Parametros;

class AgendarOrdemServico{

    public function get($qtype = 'su_oss_chamado_reagendar.id', $query = '', $oper = '=', $page = '1', $rp = '20000', $sortname = '', $sortorder = '', $tipoRetorno = false, $id_contrato_plano_pessoa){

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

        $api->get('su_oss_chamado_reagendar', $params);
        $retorno = $api->getRespostaConteudo($tipoRetorno);
        
        return $retorno;
    }
}