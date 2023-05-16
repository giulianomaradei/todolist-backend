<?php
namespace Integracao\Ixc;

require_once "Parametros.php";
require_once "WebServiceClient.php";

use IXCsoft\WebserviceClient as WebserviceClient;
use Integracao\Ixc\Parametros as Parametros;

class FinalizarOrdemServico{

    public function get($qtype = 'su_oss_chamado_fechar.id', $query = '', $oper = '=', $page = '1', $rp = '20000', $sortname = '', $sortorder = '', $tipoRetorno = false, $id_contrato_plano_pessoa){

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

        $api->get('su_oss_chamado_fechar', $params);
        $retorno = $api->getRespostaConteudo($tipoRetorno);
        
        return $retorno;
    }

    public function post($id_chamado, $id_tarefa_atual, $data_inicio, $data_final, $id_resposta, $mensagem, $id_tecnico, $id_equipe, $gera_comissao, $id_su_diagnostico, $finaliza_processo, $id_proxima_tarefa, $id_proxima_tarefa_aux, $id_contrato_plano_pessoa){

        $parametros = new Parametros();
        $parametros->setParametros($id_contrato_plano_pessoa);
        $api = new WebserviceClient($parametros->getHost(), $parametros->getToken(), $parametros->getSelfSigned());

        $dados = array(
            'id_chamado' => $id_chamado,
            'id_tarefa_atual' => $id_tarefa_atual,
            'data_inicio' => $data_inicio,
            'data_final' => $data_final,
            'id_resposta' => $id_resposta,
            'mensagem' => $mensagem,
            'id_tecnico' => $id_tecnico,
            'id_equipe' => $id_equipe,
            'gera_comissao' => $gera_comissao,
            'id_su_diagnostico' => $id_su_diagnostico,
            'finaliza_processo' => $finaliza_processo,
            'id_proxima_tarefa' => $id_proxima_tarefa,
            'id_proxima_tarefa_aux' => $id_proxima_tarefa_aux,
            'status' => 'F'
        );

        $api->post('su_oss_chamado_fechar', $dados);
        $retorno = $api->getRespostaConteudo(true);
        return $retorno;
    }
}