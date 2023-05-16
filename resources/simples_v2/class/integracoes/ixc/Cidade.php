<?php
namespace Integracao\Ixc;

require_once "Parametros.php";
require_once "WebServiceClient.php";

use IXCsoft\WebserviceClient as WebserviceClient;
use Integracao\Ixc\Parametros as Parametros;

class Cidade{

    private $id;
    private $nome;
    private $uf;
    private $regiao;
    private $cod_ibge;
    private $cod_siafi;
    private $cod_cidade_nfse_forquilhinha_sc;

    private $qtype;
    private $query;

    public function get($qtype = 'cidade.id', $query = '', $oper = '=', $page = '1', $rp = '20000', $sortname = 'cidade.id', $sortorder = 'desc', $tipoRetorno = false, $id_contrato_plano_pessoa){

        /*$parametros = new Parametros();
        $api = new WebserviceClient($parametros->getHost(), $parametros->getToken(), $parametros->getSelfSigned());*/

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
        $api->get('cidade', $params);
        $retorno = $api->getRespostaConteudo($tipoRetorno);
        
        return $retorno;
    }
}