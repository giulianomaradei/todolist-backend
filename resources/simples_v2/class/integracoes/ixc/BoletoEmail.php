<?php
namespace Integracao\Ixc;

require_once "Parametros.php";
require_once "WebServiceClient.php";

use IXCsoft\WebserviceClient as WebserviceClient;
use Integracao\Ixc\Parametros as Parametros;

class BoletoEmail{

    public function get($tipo_tipo = 'mail'){

        $parametros = new Parametros();
        $api = new WebserviceClient($parametros->getHost(), $parametros->getToken(), $parametros->getSelfSigned());

        $params = array(
            'boletos' => $_POST['PARCELA'], //id da fatura
            'juro' => 'N', //'S'->SIM e 'N'->NÃO para cálculo de júro
            'multa' => 'N', //'S'->SIM e 'N'->NÃO para cálculo de multa
            'atualiza_boleto' => 'N', //'S'->SIM e 'N'->NÃO para atualizar o boleto
            'tipo_boleto' => $tipo_tipo //tipo de método que será executado - 'mail' para E-Mail, 'sms' para SMS
        );
        $api->get('get_boleto', $params);
        $retorno = $api->getRespostaConteudo(false);
        
        return $retorno;
    }
}