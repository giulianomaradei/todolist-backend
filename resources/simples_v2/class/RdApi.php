<?php
require_once(__DIR__."/System.php");


if ($_GET['code']) {
    $code = $_GET['code'];
    $result = checkCode($code);

    if ($result['alert'] == 'success') {
        $alert = ('Dados da integração atualizados! Já é possível continuar com o precesso de sinalização e tags','s');
        header("location: /api/iframe?token=$request->token&view=lead-negocio-perdido-ganho");
        exit;

    } else {
        $alert = ('OPS! Algo de errado aconteceu!'.$result['message'],'d');
        header("location: /api/iframe?token=$request->token&view=lead-negocio-perdido-ganho");
        exit;
    }
} 

function inserirRdConfiguracao($request_token) //salva as configurações da integraçao no banco
{
    $token = $request_token['access_token'];
    $token_refresh = $request_token['refresh_token'];
    $code = $request_token['code'];
    $data = getDataHora();

    $dados = array(
        'code' => $code,
        'token' => $token,
        'token_refresh' => $token_refresh,
        'json' => json_encode($request_token),
        'data_atualizacao' => $data
    );

    $insertID = DBCreate('', 'tb_rd_configuracao', $dados, true);
    registraLog('Inserção de configuracao RD.','i','tb_rd_configuracao',$insertID,"code: $code | token: $token | token_refresh: $token_refresh | json: $request_token | data_atualizacao: $data");
}

function alterarRdConfiguracao($request_token, $dados_configuracao) //atualiza as configurações da integraçao no banco
{
    $id_rd_configuracao = $dados_configuracao[0]['id_rd_configuracao'];
    $token = $request_token['access_token'];
    $token_refresh = $request_token['refresh_token'];
    $code = $request_token['code'];
    $data = getDataHora();

    $dados = array(
        'code' => $code,
        'token' => $token,
        'token_refresh' => $token_refresh,
        'json' => json_encode($request_token),
        'data_atualizacao' => $data
    );

    DBUpdate('', 'tb_rd_configuracao', $dados, "id_rd_configuracao = $id_rd_configuracao");
    registraLog('Alteração de configuracao RD.','a','tb_rd_configuracao',$id_rd_configuracao,"code: $code | token: $token | token_refresh: $token_refresh | json: $request_token | data_atualizacao: $data");
}

function updateLead($uuid, $parametros) //altera o lead la no RD
{   
    $dados_configuracao = DBRead('', 'tb_rd_configuracao');
    $token = $dados_configuracao[0]['token'];

    $teste = json_encode($parametros);

    if ($token && $uuid && $parametros) {

        //$patch = troca_dados_curl('https://testrd.free.beeceptor.com', $parametros, array('Content-Type: application/json', 'Authorization: '.$token), 'PATCH');

        $patch = troca_dados_curl('https://api.rd.services/platform/contacts/'.$uuid.'', $parametros, array('Content-Type: application/json', 'Authorization: '.$token), 'PATCH');

        if ($patch['uuid']) {

            $result = array(
                'alert' => 'success',
                'message' => 'Lead alterado com sucesso! (function updateLead)',
                'dados' => $patch
            );

            return $result;

        } else if ($patch['errors']['error_message'] == 'Invalid token.') {

            $token = refreshToken();

            $patch = troca_dados_curl('https://api.rd.services/platform/contacts/'.$uuid.'', $parametros, array('Content-Type: application/json', 'Authorization: '.$token['token']), 'PATCH');

            $result = array(
                'alert' => 'success',
                'message' => 'Lead encontrado! (function updateLead)',
                'dados' => $patch
            );

            return $result;
            
        } else {

            $result = array(
                'alert' => 'danger',
                'message' => 'Não foi possivel alterar o lead! (function updateLead)',
                'token' => null
            );

            return $result;
        }

    } else {
        
        $result = array(
            'alert' => 'danger',
            'message' => 'Token e uuid são obrigatórios!',
            'dados' => null
        );

        return $result;
    }
}

function checkCode($code) //verifica o codigo necessario para a integração funcionar
{
    $parametro = 'api_rd';
	$dados_api_rd = getConfig($parametro);

    $parametros = '
        {
            "client_id": '.$dados_api_rd['CLIENT_ID'].',
            "client_secret": '.$dados_api_rd['CLIENT_SECRET'].',
            "code": "'.$code.'"
        }
    ';  

    $request_token = troca_dados_curl('https://api.rd.services/auth/token', $parametros, array('Content-Type: application/json'));

    //var_dump($request_token);

    if ($request_token['errors'][0]['error_type'] == 'UNAUTHORIZED') {

        $result = array(
            'alert' => 'danger',
            'message' => 'UNAUTHORIZED - Invalid token! (function checkCode)',
            'token' => null
        );
        
        return $result;
    
    } else if ($request_token['errors'][0]['error_type'] == 'ACCESS_DENIED'){

        $result = array(
            'alert' => 'danger',
            'message' => 'ACCESS_DENIED - Wrong credentials provided! (function checkCode)',
            'token' => null
        );

        return $result;
    
    } else if ($request_token['errors'][0]['error_type'] == 'EXPIRED_CODE_GRANT'){

        $result = array(
            'alert' => 'danger',
            'message' => 'He authorization code grant has expired! (function checkCode)',
            'token' => null
        );

        return $result;
    
    } else if ($request_token['errors'][0]['error_type'] == 'INVALID_REFRESH_TOKEN'){
        
        $refresh_token = refreshToken();

        if ($refresh_token['alert'] == 'success') {

            $result = array(
                'alert' => 'success',
                'message' => 'TOKEN REQUEST SUCCESSFULL! (function checkCode)',
                'token' => $refresh_token['token']
            );

            return $result;
        
        } else {
            $result = array(
                'alert' => 'success',
                'message' => $refresh_token['message'].'! (function checkCode)',
                'token' => null
            );

            return $result;
        }
    
    } else if ($request_token['access_token']) {

        $dados_configuracao = DBRead('', 'tb_rd_configuracao');

        if ($dados_configuracao) {
            alterarRdConfiguracao($request_token, $dados_configuracao);

        } else {
            inserirRdConfiguracao($request_token);
        }

        $result = array(
            'alert' => 'success',
            'message' => 'TOKEN REQUEST SUCCESSFULL! (function checkCode)',
            'token' => $request_token['access_token']
        );

        return $result;
    
    } else {

        $result = array(
            'alert' => 'danger',
            'message' => 'OPS! Algo de errado aconteceu! (function checkCode)',
            'token' => null
        );

        return $result;
    }
}

function getLead($uuid) //busca o lead la no RD
{
    $dados_configuracao = DBRead('', 'tb_rd_configuracao');
    $token = $dados_configuracao[0]['token'];

    if ($token && $uuid) {
        $dados_lead = troca_dados_curl('https://api.rd.services/platform/contacts/'.$uuid.'', '', array('Content-Type: application/json', 'Authorization: '.$token), 'GET');

        if ($dados_lead['errors']['error_message'] == 'Invalid token.') {

            $refreshToken = refreshToken();

            $dados_lead = troca_dados_curl('https://api.rd.services/platform/contacts/'.$uuid.'', '', array('Content-Type: application/json', 'Authorization: '.$refreshToken['token']), 'GET');

            $result = array(
                'alert' => 'success',
                'message' => 'Lead encontrado! (function getLead)',
                'dados' => $dados_lead
            );
    
            return $result;

        } else {
            $result = array(
                'alert' => 'success',
                'message' => 'Lead encontrado! (function getLead)',
                'dados' => $dados_lead
            );
    
            return $result;
        }

    } else {
        
        $result = array(
            'alert' => 'danger',
            'message' => 'Token e uuid são obrigatórios! (function getLead)',
            'dados' => null
        );

        return $result;
    }   
}

function redirectURL() //url para gerar um novo "code"
{
    header('Location: https://api.rd.services/auth/dialog?client_id=71262ca5-ab4f-4ea3-89cc-6255dd087ce6&redirect_uri=https://'.(strpos($_SERVER['HTTP_HOST'],'homologa') !== FALSE ? 'homologa' : 'simples' ).'.bellunotec.com.br/v2/class/RdApi.php');
}

function refreshToken() //processo para obter um novo token
{   
    $dados_configuracao = DBRead('', 'tb_rd_configuracao');
    $token_refresh = $dados_configuracao[0]['token_refresh'];

    $parametros = '
            {
                "client_id": "71262ca5-ab4f-4ea3-89cc-6255dd087ce6",
                "client_secret": "f1b251c8f24843bdb8f9ef33983694b5",
                "refresh_token": "'.$token_refresh.'"
            }
        ';  
    
    $request_token = troca_dados_curl('https://api.rd.services/auth/token', $parametros, array('Content-Type: application/json'));

    if ($request_token['access_token']) {

        if ($dados_configuracao) {
            alterarRdConfiguracao($request_token, $dados_configuracao);

        } else {
            inserirRdConfiguracao($request_token);
        }

        $result = array(
            'alert' => 'success',
            'message' => 'TOKEN REQUEST SUCCESSFULL! (function refreshToken)',
            'token' => $request_token['access_token']
        );

        return $result;

    } else {
        $result = array(
            'alert' => 'danger',
            'message' => $request_token['message'].'! (function refreshToken)',
            'token' => null
        );

        return $result;
    }
}

?>