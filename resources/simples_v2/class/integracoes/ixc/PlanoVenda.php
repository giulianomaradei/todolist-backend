<?php
namespace Integracao\Ixc;

require_once "Parametros.php";
require_once "WebServiceClient.php";

use IXCsoft\WebserviceClient as WebserviceClient;
use Integracao\Ixc\Parametros as Parametros;

class PlanoVenda{

    public function get($qtype = 'vd_contratos.id', $query = '', $tipoRetorno = false, $id_contrato_plano_pessoa){
        
        $parametros = new Parametros();
        $parametros->setParametros($id_contrato_plano_pessoa);
        $api = new WebserviceClient($parametros->getHost(), $parametros->getToken(), $parametros->getSelfSigned());
        
        $params = array(
            'qtype' => $qtype,//campo de filtro
            'query' => $query,//valor para consultar
            'oper' => '=',//operador da consulta
            'page' => '1',//página a ser mostrada
            'rp' => '20',//quantidade de registros por página
            'sortname' => 'vd_contratos.id',//campo para ordenar a consulta
            'sortorder' => 'desc'//ordenação (asc= crescente | desc=decrescente)
        );

        $api->get('vd_contratos', $params);
        $retorno = $api->getRespostaConteudo($tipoRetorno);// false para json | true para array

        return $retorno;
    }
}