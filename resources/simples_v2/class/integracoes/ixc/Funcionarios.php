<?php
namespace Integracao\Ixc;

require_once "Parametros.php";
require_once "WebServiceClient.php";

use IXCsoft\WebserviceClient as WebserviceClient;
use Integracao\Ixc\Parametros as Parametros;

class Funcionarios{

    public function get($qtype = 'funcionarios.funcionario', $query = '', $tipoRetorno = false, $id_contrato_plano_pessoa){
        ///////////////////////////////////////////////////////////////////////////////////////////////////////
        /*$params = array(
            'qtype' => 'funcionarios.funcionario',
            'query' => '',
            'oper' => '=',
            'page' => '1',
            'rp' => '20',
            'sortname' => 'view_funcionarios_setor.ativo',
            'sortorder' => 'desc'
        );
        $api->get('funcionario_setor2', $params);
        $retorno = $api->getRespostaConteudo(false);*/
        ///////////////////////////////////////////////////////////////////////////////////////////////////////
        $parametros = new Parametros();
        $parametros->setParametros($id_contrato_plano_pessoa);
        $api = new WebserviceClient($parametros->getHost(), $parametros->getToken(), $parametros->getSelfSigned());

        $params = array(
            'qtype' => $qtype,
            'query' => $query,
            'oper' => '!=',
            'page' => '1',
            'rp' => '2000000',
            'sortname' => 'view_funcionarios_setor.ativo',
            'sortorder' => 'desc',
            'grid_param' => json_encode(array(array('TB' => 'view_funcionarios_setor.ativo', 'OP' => '=', 'P' => 'S')))
        );
        $api->get('funcionario_setor2', $params);
        $retorno = $api->getRespostaConteudo($tipoRetorno);
        
        return $retorno;

    }

    /*public function get($qtype = 'funcionarios.id', $query = '', $tipoRetorno = false, $id_contrato_plano_pessoa){

        $parametros = new Parametros();
        $parametros->setParametros($id_contrato_plano_pessoa);
        $api = new WebserviceClient($parametros->getHost(), $parametros->getToken(), $parametros->getSelfSigned());

        $params = array(
            'qtype' => $qtype,
            'query' => $query,
            'oper' => '=',
            'page' => '1',
            'rp' => '20',
            'sortname' => 'funcionario.id',
            'sortorder' => 'desc'
        );
        $api->get('funcionario', $params);
        $retorno = $api->getRespostaConteudo($tipoRetorno);
        
        return $retorno;

    }*/
}

