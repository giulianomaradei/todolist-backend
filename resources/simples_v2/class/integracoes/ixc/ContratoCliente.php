<?php
namespace Integracao\Ixc;

require_once "Parametros.php";
require_once "WebServiceClient.php";

use IXCsoft\WebserviceClient as WebserviceClient;
use Integracao\Ixc\Parametros as Parametros;

class ContratoCliente{

    public function get($qtype = 'cliente_contrato.id', $query = '', $oper = '=', $page = '1', $rp = '20000', $sortname = 'cliente_contrato.id', $sortorder = 'desc', $tipoRetorno = false, $id_contrato_plano_pessoa){

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
            'sortorder' => $sortorder,
            'grid_param' => json_encode(array(array('TB' => 'cliente_contrato.status', 'OP' => '!=', 'P' => 'I')))
        );

        $api->get('cliente_contrato', $params);
        $retorno = $api->getRespostaConteudo($tipoRetorno);
        return $retorno;
    }


    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public function put($liberacao_bloqueio_manual, $id_contrato, $tipoRetorno = false, $id_contrato_plano_pessoa){

        $parametros = new Parametros();
        $parametros->setParametros($id_contrato_plano_pessoa);
        $api = new WebserviceClient($parametros->getHost(), $parametros->getToken(), $parametros->getSelfSigned());

        $cliente = $this->get('cliente_contrato.id', $id_contrato, '=', '1', '20000', 'cliente_contrato.id', 'desc', true, $id_contrato_plano_pessoa);

        $dados = array(
            "apartamento" => $cliente['registros'][0]['apartamento'],
            "assinatura_digital" => $cliente['registros'][0]['assinatura_digital'],
            "ativacao_numero_parcelas" => $cliente['registros'][0]['ativacao_numero_parcelas'],
            "ativacao_valor_parcela" => $cliente['registros'][0]['ativacao_valor_parcela'],
            "ativacao_vencimentos" => $cliente['registros'][0]['ativacao_vencimentos'],
            "avalista_1" => $cliente['registros'][0]['avalista_1'],
            //"avalista_2" => $cliente['registros'][0]['avalista_2'],
            "avalista_2" => "2",
            "aviso_atraso" => $cliente['registros'][0]['aviso_atraso'],
            "bairro" => $cliente['registros'][0]['bairro'],
            "bloco" => $cliente['registros'][0]['bloco'],
            "bloqueio_automatico" => $cliente['registros'][0]['bloqueio_automatico'],
            "cc_previsao" => $cliente['registros'][0]['cc_previsao'],
            "cep" => $cliente['registros'][0]['cep'],
            "cidade" => $cliente['registros'][0]['cidade'],
            "comissao" => $cliente['registros'][0]['comissao'],
            "complemento" => $cliente['registros'][0]['complemento'],
            "contrato" => $cliente['registros'][0]['contrato'],
            "credit_card_recorrente_bandeira_cartao" => $cliente['registros'][0]['credit_card_recorrente_bandeira_cartao'],
            "credit_card_recorrente_token" => $cliente['registros'][0]['credit_card_recorrente_token'],
            "data" => $cliente['registros'][0]['data'],
            "data_ativacao" => $cliente['registros'][0]['data_ativacao'],
            "data_cadastro_sistema" => $cliente['registros'][0]['data_cadastro_sistema'],
            "data_cancelamento" => $cliente['registros'][0]['data_cancelamento'],
            "data_negativacao" => $cliente['registros'][0]['data_negativacao'],
            "data_renovacao" => $cliente['registros'][0]['data_renovacao'],
            "desbloqueio_confianca" => $cliente['registros'][0]['desbloqueio_confianca'],
            "desbloqueio_confianca_ativo" => $cliente['registros'][0]['desbloqueio_confianca_ativo'],
            "desconto_fidelidade" => $cliente['registros'][0]['desconto_fidelidade'],
            "descricao_aux_plano_venda" => $cliente['registros'][0]['descricao_aux_plano_venda'],
            "dt_ult_ativacao" => $cliente['registros'][0]['dt_ult_ativacao'],
            "dt_ult_bloq_auto" => $cliente['registros'][0]['dt_ult_bloq_auto'],
            "dt_ult_bloq_manual" => $cliente['registros'][0]['dt_ult_bloq_manual'],
            "dt_ult_des_bloq_conf" => $cliente['registros'][0]['dt_ult_des_bloq_conf'],
            "dt_ult_desiste" => $cliente['registros'][0]['dt_ult_desiste'],
            "dt_ult_finan_atraso" => $cliente['registros'][0]['dt_ult_finan_atraso'],
            "dt_utl_negativacao" => $cliente['registros'][0]['dt_utl_negativacao'],
            "endereco" => $cliente['registros'][0]['endereco'],
            "endereco_padrao_cliente" => $cliente['registros'][0]['endereco_padrao_cliente'],
            "fidelidade" => $cliente['registros'][0]['fidelidade'],
            "gerar_finan_assin_digital_contrato" => $cliente['registros'][0]['gerar_finan_assin_digital_contrato'],
            "id_carteira_cobranca" => $cliente['registros'][0]['id_carteira_cobranca'],
            "id_cliente" => $cliente['registros'][0]['id_cliente'],
            "id_cond_pag_ativ" => $cliente['registros'][0]['id_cond_pag_ativ'],
            "id_condominio" => $cliente['registros'][0]['id_condominio'],
            "id_contrato_principal" => $cliente['registros'][0]['id_contrato_principal'],
            "id_filial" => $cliente['registros'][0]['id_filial'],
            "id_instalador" => $cliente['registros'][0]['id_instalador'],
            "id_modelo" => $cliente['registros'][0]['id_modelo'],
            "id_produto_ativ" => $cliente['registros'][0]['id_produto_ativ'],
            "id_responsavel" => $cliente['registros'][0]['id_responsavel'],
            "id_tipo_contrato" => $cliente['registros'][0]['id_tipo_contrato'],
            "id_tipo_doc_ativ" => $cliente['registros'][0]['id_tipo_doc_ativ'],
            "id_tipo_documento" => $cliente['registros'][0]['id_tipo_documento'],
            "id_vd_contrato" => $cliente['registros'][0]['id_vd_contrato'],
            "id_vendedor" => $cliente['registros'][0]['id_vendedor'],
            "id_vendedor_ativ" => $cliente['registros'][0]['id_vendedor_ativ'],
            "imp_bkp" => $cliente['registros'][0]['imp_bkp'],
            "imp_carteira" => $cliente['registros'][0]['imp_carteira'],
            "imp_final" => $cliente['registros'][0]['imp_final'],
            "imp_importacao" => $cliente['registros'][0]['imp_importacao'],
            "imp_inicial" => $cliente['registros'][0]['imp_inicial'],
            "imp_motivo" => $cliente['registros'][0]['imp_motivo'],
            "imp_obs" => $cliente['registros'][0]['imp_obs'],
            "imp_realizado" => $cliente['registros'][0]['imp_realizado'],
            "imp_rede" => $cliente['registros'][0]['imp_rede'],
            "imp_status" => $cliente['registros'][0]['imp_status'],
            "imp_treinamento" => $cliente['registros'][0]['imp_treinamento'],
            "indicacao_contrato_id" => $cliente['registros'][0]['indicacao_contrato_id'],
            "latitude" => $cliente['registros'][0]['latitude'],

            "liberacao_bloqueio_manual" => $liberacao_bloqueio_manual,

            "longitude" => $cliente['registros'][0]['longitude'],
            "motivo_cancelamento" => $cliente['registros'][0]['motivo_cancelamento'],
            "motivo_inclusao" => $cliente['registros'][0]['motivo_inclusao'],
            "nao_avisar_ate" => $cliente['registros'][0]['nao_avisar_ate'],
            "nao_bloquear_ate" => $cliente['registros'][0]['nao_bloquear_ate'],
            "nf_info_adicionais" => $cliente['registros'][0]['nf_info_adicionais'],
            "num_parcelas_atraso" => $cliente['registros'][0]['num_parcelas_atraso'],
            "numero" => $cliente['registros'][0]['numero'],
            "obs" => $cliente['registros'][0]['obs'],
            "obs_cancelamento" => $cliente['registros'][0]['obs_cancelamento'],
            "pago_ate_data" => $cliente['registros'][0]['pago_ate_data'],
            "protocolo_negativacao" => $cliente['registros'][0]['protocolo_negativacao'],
            "referencia" => $cliente['registros'][0]['referencia'],
            "renovacao_automatica" => $cliente['registros'][0]['renovacao_automatica'],
            "status" => $cliente['registros'][0]['status'],
            "status_internet" => $cliente['registros'][0]['status_internet'],
            "status_velocidade" => $cliente['registros'][0]['status_velocidade'],
            "taxa_improdutiva" => $cliente['registros'][0]['taxa_improdutiva'],
            "taxa_instalacao" => $cliente['registros'][0]['taxa_instalacao'],
            "tipo" => $cliente['registros'][0]['tipo'],
            "tipo_cobranca" => $cliente['registros'][0]['tipo_cobranca'],
            "tipo_doc_opc" => $cliente['registros'][0]['tipo_doc_opc'],
            "tipo_doc_opc2" => $cliente['registros'][0]['tipo_doc_opc2'],
            "tipo_doc_opc3" => $cliente['registros'][0]['tipo_doc_opc3'],
            "tipo_doc_opc4" => $cliente['registros'][0]['tipo_doc_opc4'],
            "tipo_produtos_plano" => $cliente['registros'][0]['tipo_produtos_plano']



            /*'tipo' => $cliente['registros'][0]['tipo'],
            'id_cliente' => $cliente['registros'][0]['id_cliente'],
            'id_vd_contrato' => $cliente['registros'][0]['id_vd_contrato'],
            'descricao_aux_plano_venda' => $cliente['registros'][0]['descricao_aux_plano_venda'],
            'contrato' => $cliente['registros'][0]['contrato'],
            'id_tipo_contrato' => $cliente['registros'][0]['id_tipo_contrato'],
            'id_modelo' => $cliente['registros'][0]['id_modelo'],
            'assinatura_digital' => $cliente['registros'][0]['assinatura_digital'],
            
            'liberacao_bloqueio_manual' => 'S',
            
            'id_filial' => $cliente['registros'][0]['id_filial'],
            'indicacao_contrato_id' => $cliente['registros'][0]['indicacao_contrato_id'],
            'data_ativacao' => $cliente['registros'][0]['data_ativacao'],
            'data' => $cliente['registros'][0]['data'],
            'data_renovacao' => $cliente['registros'][0]['data_renovacao'],
            'pago_ate_data' => $cliente['registros'][0]['pago_ate_data'],
            'status' => $cliente['registros'][0]['status'],
            'status_internet' => $cliente['registros'][0]['status_internet'],
            'status_velocidade' => $cliente['registros'][0]['status_velocidade'],
            'data_cadastro_sistema' => $cliente['registros'][0]['data_cadastro_sistema'],
            'id_tipo_documento' => $cliente['registros'][0]['id_tipo_documento'],
            'id_carteira_cobranca' => $cliente['registros'][0]['id_carteira_cobranca'],
            'id_vendedor' => $cliente['registros'][0]['id_vendedor'],
            'comissao' => $cliente['registros'][0]['comissao'],
            'cc_previsao' => $cliente['registros'][0]['cc_previsao'],
            'tipo_cobranca' => $cliente['registros'][0]['tipo_cobranca'],
            'renovacao_automatica' => $cliente['registros'][0]['renovacao_automatica'],
            'gerar_finan_assin_digital_contrato' => $cliente['registros'][0]['gerar_finan_assin_digital_contrato'],
            'id_contrato_principal' => $cliente['registros'][0]['id_contrato_principal'],
            'num_parcelas_atraso' => $cliente['registros'][0]['num_parcelas_atraso'],
            'nf_info_adicionais' => $cliente['registros'][0]['nf_info_adicionais'],
            'credit_card_recorrente_bandeira_cartao' => $cliente['registros'][0]['credit_card_recorrente_bandeira_cartao'],
            'credit_card_recorrente_token' => $cliente['registros'][0]['credit_card_recorrente_token'],
            'tipo_doc_opc' => $cliente['registros'][0]['tipo_doc_opc'],
            'tipo_doc_opc2' => $cliente['registros'][0]['tipo_doc_opc2'],
            'tipo_doc_opc3' => $cliente['registros'][0]['tipo_doc_opc3'],
            'tipo_doc_opc4' => $cliente['registros'][0]['tipo_doc_opc4'],
            'id_tipo_doc_ativ' => $cliente['registros'][0]['id_tipo_doc_ativ'],
            'id_produto_ativ' => $cliente['registros'][0]['id_produto_ativ'],
            'taxa_instalacao' => $cliente['registros'][0]['taxa_instalacao'],
            'id_cond_pag_ativ' => $cliente['registros'][0]['id_cond_pag_ativ'],
            'id_responsavel' => $cliente['registros'][0]['id_responsavel'],
            'id_vendedor_ativ' => $cliente['registros'][0]['id_vendedor_ativ'],
            'ativacao_numero_parcelas' => $cliente['registros'][0]['ativacao_numero_parcelas'],
            'ativacao_vencimentos' => $cliente['registros'][0]['ativacao_vencimentos'],
            'ativacao_valor_parcela' => $cliente['registros'][0]['ativacao_valor_parcela'],
            'id_instalador' => $cliente['registros'][0]['id_instalador'],
            'fidelidade' => $cliente['registros'][0]['fidelidade'],
            'desconto_fidelidade' => $cliente['registros'][0]['desconto_fidelidade'],
            'taxa_improdutiva' => $cliente['registros'][0]['taxa_improdutiva'],
            'bloqueio_automatico' => $cliente['registros'][0]['bloqueio_automatico'],
            'nao_bloquear_ate' => $cliente['registros'][0]['nao_bloquear_ate'],
            'aviso_atraso' => $cliente['registros'][0]['aviso_atraso'],
            'nao_avisar_ate' => $cliente['registros'][0]['nao_avisar_ate'],
            'desbloqueio_confianca' => $cliente['registros'][0]['desbloqueio_confianca'],
            'desbloqueio_confianca_ativo' => $cliente['registros'][0]['desbloqueio_confianca_ativo'],
            'obs' => $cliente['registros'][0]['obs'],
            'motivo_inclusao' => $cliente['registros'][0]['motivo_inclusao'],
            'data_cancelamento' => $cliente['registros'][0]['data_cancelamento'],
            'motivo_cancelamento' => $cliente['registros'][0]['motivo_cancelamento'],
            'obs_cancelamento' => $cliente['registros'][0]['obs_cancelamento'],
            'data_negativacao' => $cliente['registros'][0]['data_negativacao'],
            'protocolo_negativacao' => $cliente['registros'][0]['protocolo_negativacao'],
            'avalista_1' => $cliente['registros'][0]['avalista_1'],
            //'avalista_2' => $cliente['registros'][0]['avalista_2'],
            'avalista_2' => "2",
            'dt_ult_bloq_auto' => $cliente['registros'][0]['dt_ult_bloq_auto'],
            'dt_ult_bloq_manual' => $cliente['registros'][0]['dt_ult_bloq_manual'],
            'dt_ult_finan_atraso' => $cliente['registros'][0]['dt_ult_finan_atraso'],
            'dt_ult_des_bloq_conf' => $cliente['registros'][0]['dt_ult_des_bloq_conf'],
            'dt_ult_ativacao' => $cliente['registros'][0]['dt_ult_ativacao'],
            'dt_utl_negativacao' => $cliente['registros'][0]['dt_utl_negativacao'],
            'dt_ult_desiste' => $cliente['registros'][0]['dt_ult_desiste'],
            'imp_realizado' => $cliente['registros'][0]['imp_realizado'],
            'imp_inicial' => $cliente['registros'][0]['imp_inicial'],
            'imp_carteira' => $cliente['registros'][0]['imp_carteira'],
            'imp_importacao' => $cliente['registros'][0]['imp_importacao'],
            'imp_treinamento' => $cliente['registros'][0]['imp_treinamento'],
            'imp_rede' => $cliente['registros'][0]['imp_rede'],
            'imp_bkp' => $cliente['registros'][0]['imp_bkp'],
            'imp_obs' => $cliente['registros'][0]['imp_obs'],
            'imp_final' => $cliente['registros'][0]['imp_final'],
            'imp_status' => $cliente['registros'][0]['imp_status'],
            'imp_motivo' => $cliente['registros'][0]['imp_motivo'],
            'endereco_padrao_cliente' => $cliente['registros'][0]['endereco_padrao_cliente'],
            'id_condominio' => $cliente['registros'][0]['id_condominio'],
            'bloco' => $cliente['registros'][0]['bloco'],
            'apartamento' => $cliente['registros'][0]['apartamento'],
            'cep' => $cliente['registros'][0]['cep'],
            'endereco' => $cliente['registros'][0]['endereco'],
            'numero' => $cliente['registros'][0]['numero'],
            'bairro' => $cliente['registros'][0]['bairro'],
            'cidade' => $cliente['registros'][0]['cidade'],
            'complemento' => $cliente['registros'][0]['complemento'],
            'referencia' => $cliente['registros'][0]['referencia'],
            'latitude' => $cliente['registros'][0]['latitude'],
            'longitude' => $cliente['registros'][0]['longitude']*/
        );

        $registro = $id_contrato;
        $api->put('cliente_contrato', $dados, $registro);
        $retorno = $api->getRespostaConteudo($tipoRetorno);
        return $retorno;
    }
}
