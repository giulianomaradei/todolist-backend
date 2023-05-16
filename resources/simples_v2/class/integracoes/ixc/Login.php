<?php
namespace Integracao\Ixc;

require_once "Parametros.php";
require_once "WebServiceClient.php";

use IXCsoft\WebserviceClient as WebserviceClient;
use Integracao\Ixc\Parametros as Parametros;

class Login{

    public function get($qtype = 'radusuarios.id', $query = '', $oper = '=', $page = '1', $rp = '20000', $sortname = 'radusuarios.id', $sortorder = 'desc', $tipoRetorno = false, $id_contrato_plano_pessoa){

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

        $api->get('radusuarios', $params);
        $retorno = $api->getRespostaConteudo($tipoRetorno);

        return $retorno;
    }

    public function put($id_contrato_plano_pessoa, $id_login){

        $parametros = new Parametros();
        $parametros->setParametros($id_contrato_plano_pessoa);
        $api = new WebserviceClient($parametros->getHost(), $parametros->getToken(), $parametros->getSelfSigned());

        $logins = $this->get('radusuarios.id', $id_login, '=', '1', '20000', 'radusuarios.id', 'desc', true, $id_contrato_plano_pessoa);

        //return $logins['registros'][0]['ativo'];

        $dados = array(
            'autenticacao' => $logins['registros'][0]['autenticacao'],
            'tipo_conexao_mapa' => $logins['registros'][0]['tipo_conexao_mapa'],
            'id_cliente' => $logins['registros'][0]['id_cliente'],
            'id_contrato' => $logins['registros'][0]['id_contrato'],
            'id_filial' => $logins['registros'][0]['id_filial'],
            'id_grupo' => $logins['registros'][0]['id_grupo'],
            'login' => $logins['registros'][0]['login'],
            'login_simultaneo' => $logins['registros'][0]['login_simultaneo'],
            'agent_circuit_id' => $logins['registros'][0]['agent_circuit_id'],
            'senha_md5' => $logins['registros'][0]['senha_md5'],
            'senha' => $logins['registros'][0]['senha'],
            'usuario_router1' => $logins['registros'][0]['usuario_router1'],
            'senha_router1' => $logins['registros'][0]['senha_router1'],
            'senha_router2' => $logins['registros'][0]['senha_router2'],
            'senha_rede_sem_fio' => $logins['registros'][0]['senha_rede_sem_fio'],
            'ssid_router_wifi' => $logins['registros'][0]['ssid_router_wifi'],
            'ativo' => $logins['registros'][0]['ativo'],
            'online' => $logins['registros'][0]['online'],
            'ip' => $logins['registros'][0]['ip'],
            'auto_preencher_ip' => $logins['registros'][0]['auto_preencher_ip'],
            'fixar_ip' => $logins['registros'][0]['fixar_ip'],
            'relacionar_ip_ao_login' => $logins['registros'][0]['relacionar_ip_ao_login'],
            'pd_ipv6' => $logins['registros'][0]['pd_ipv6'],
            'auto_preencher_ipv6' => $logins['registros'][0]['auto_preencher_ipv6'],
            'fixar_ipv6' => $logins['registros'][0]['fixar_ipv6'],
            'relacionar_ipv6_ao_login' => $logins['registros'][0]['relacionar_ipv6_ao_login'],
            'mac' => '',
            'autenticacao_por_mac' => $logins['registros'][0]['autenticacao_por_mac'],
            'autenticacao_wpa' => $logins['registros'][0]['autenticacao_wpa'],
            'id_porta_transmissor' => $logins['registros'][0]['id_porta_transmissor'],
            'auto_preencher_mac' => $logins['registros'][0]['auto_preencher_mac'],
            'relacionar_mac_ao_login' => $logins['registros'][0]['relacionar_mac_ao_login'],
            'relacionar_concentrador_ao_login' => $logins['registros'][0]['relacionar_concentrador_ao_login'],
            'id_concentrador' => $logins['registros'][0]['id_concentrador'],
            'ip_concentrador' => $logins['registros'][0]['ip_concentrador'],
            'interface' => $logins['registros'][0]['interface'],
            'pool_radius' => $logins['registros'][0]['pool_radius'],
            'id_rad_dns' => $logins['registros'][0]['id_rad_dns'],
            'vlan' => $logins['registros'][0]['vlan'],
            'vlan_ip_rede' => $logins['registros'][0]['vlan_ip_rede'],
            'gw_vlan' => $logins['registros'][0]['gw_vlan'],
            'concentrador' => $logins['registros'][0]['concentrador'],
            'conexao' => $logins['registros'][0]['conexao'],
            'tipo_conexao' => $logins['registros'][0]['tipo_conexao'],
            'tipo_vinculo_plano' => $logins['registros'][0]['tipo_vinculo_plano'],
            'cliente_tem_a_senha' => $logins['registros'][0]['cliente_tem_a_senha'],
            'autenticacao_wps' => $logins['registros'][0]['autenticacao_wps'],
            'autenticacao_mac' => $logins['registros'][0]['autenticacao_mac'],
            'porta_http' => $logins['registros'][0]['porta_http'],
            'porta_router2' => $logins['registros'][0]['porta_router2'],
            'ip_aux' => $logins['registros'][0]['ip_aux'],
            'porta_aux' => $logins['registros'][0]['porta_aux'],
            'ultima_conexao_inicial' => $logins['registros'][0]['ultima_conexao_inicial'],
            'ultima_conexao_final' => $logins['registros'][0]['ultima_conexao_final'],
            'motivo_desconexao' => $logins['registros'][0]['motivo_desconexao'],
            'count_desconexao' => $logins['registros'][0]['count_desconexao'],
            'tempo_conexao' => $logins['registros'][0]['tempo_conexao'],
            'tempo_conectado' => $logins['registros'][0]['tempo_conectado'],
            'download_atual' => $logins['registros'][0]['download_atual'],
            'upload_atual' => $logins['registros'][0]['upload_atual'],
            'franquia_maximo' => $logins['registros'][0]['franquia_maximo'],
            'franquia_consumo' => $logins['registros'][0]['franquia_consumo'],
            'franquia_consumo_up' => $logins['registros'][0]['franquia_consumo_up'],
            'franquia_atingida' => $logins['registros'][0]['franquia_atingida'],
            'id_df_projeto' => $logins['registros'][0]['id_df_projeto'],
            'id_transmissor' => $logins['registros'][0]['id_transmissor'],
            'interface_transmissao' => $logins['registros'][0]['interface_transmissao'],
            'interface_transmissao_fibra' => $logins['registros'][0]['interface_transmissao_fibra'],
            'id_caixa_ftth' => $logins['registros'][0]['id_caixa_ftth'],
            'ftth_porta' => $logins['registros'][0]['ftth_porta'],
            'tronco' => $logins['registros'][0]['tronco'],
            'splitter' => $logins['registros'][0]['splitter'],
            'onu_mac' => $logins['registros'][0]['onu_mac'],
            'sinal_ultimo_atendimento' => $logins['registros'][0]['sinal_ultimo_atendimento'],
            'id_hardware' => $logins['registros'][0]['id_hardware'],
            'tipo_equipamento' => $logins['registros'][0]['tipo_equipamento'],
            'metragem_interna' => $logins['registros'][0]['metragem_interna'],
            'metragem_externa' => $logins['registros'][0]['metragem_externa'],
            'endereco_padrao_cliente' => $logins['registros'][0]['endereco_padrao_cliente'],
            'id_condominio' => $logins['registros'][0]['id_condominio'],
            'condominio_novo' => $logins['registros'][0]['condominio_novo'],
            'bloco' => $logins['registros'][0]['bloco'],
            'bloco_novo' => $logins['registros'][0]['bloco_novo'],
            'apartamento' => $logins['registros'][0]['apartamento'],
            'apartamento_novo' => $logins['registros'][0]['apartamento_novo'],
            'cep' => $logins['registros'][0]['cep'],
            'cep_novo' => $logins['registros'][0]['cep_novo'],
            'endereco' => $logins['registros'][0]['endereco'],
            'endereco_novo' => $logins['registros'][0]['endereco_novo'],
            'numero' => $logins['registros'][0]['numero'],
            'numero_novo' => $logins['registros'][0]['numero_novo'],
            'bairro' => $logins['registros'][0]['bairro'],
            'bairro_novo' => $logins['registros'][0]['bairro_novo'],
            'cidade' => $logins['registros'][0]['cidade'],
            'cidade_novo' => $logins['registros'][0]['cidade_novo'],
            'referencia' => $logins['registros'][0]['referencia'],
            'referencia_novo' => $logins['registros'][0]['referencia_novo'],
            'complemento' => $logins['registros'][0]['complemento'],
            'complemento_novo' => $logins['registros'][0]['complemento_novo'],
            'latitude' => $logins['registros'][0]['latitude'],
            'latitude_novo' => $logins['registros'][0]['latitude_novo'],
            'longitude' => $logins['registros'][0]['longitude'],
            'longitude_novo' => $logins['registros'][0]['longitude_novo'],
            'obs' => $logins['registros'][0]['obs'],
        );
        $registro = $logins['registros'][0]['id'];
        $api->put('radusuarios', $dados, $registro);
        $retorno = $api->getRespostaConteudo(true);

        /*$params = array(
            'id' => $logins['registros'][0]['id']
        );
        $api->get('desconectar_clientes', $params);*/

        return $retorno;
    }
}