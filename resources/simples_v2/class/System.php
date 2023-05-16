<?php
require_once ("Database.php");

function getSalt($tipo = null){
    if($tipo == 'secao'){
        return 's1mpl35b3llun0';
    }else{
        return 'b3llun0';
    }
}

function getIp(){
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        return $_SERVER['HTTP_CLIENT_IP'];
    } else if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        return $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        return $_SERVER['REMOTE_ADDR'];
    }
}

function getDataHora($tipo = null, $sigla_estado = null){

    $timezone = $sigla_estado ? getTimeZone($sigla_estado) : 'America/Sao_Paulo';
    $dateTime = new DateTime("now", new DateTimeZone("$timezone"));
    if($tipo == 'data'){
        return $dateTime->format("Y-m-d");
    }else if($tipo == 'hora'){
        return $dateTime->format("H:i:s");
    }else if($tipo == 'data_ontem'){
        $dateTime = new DateTime("yesterday", new DateTimeZone("$timezone"));
        return $dateTime->format("Y-m-d");
    }else{
        return $dateTime->format("Y-m-d H:i:s");
    }
}

function getTimeZone($sigla_estado){
    $timezone = array(
    'AC' => 'America/Rio_branco',   'AL' => 'America/Maceio',
    'AP' => 'America/Belem',        'AM' => 'America/Manaus',
    'BA' => 'America/Bahia',        'CE' => 'America/Fortaleza',
    'DF' => 'America/Sao_Paulo',    'ES' => 'America/Sao_Paulo',
    'GO' => 'America/Sao_Paulo',    'MA' => 'America/Fortaleza',
    'MT' => 'America/Cuiaba',       'MS' => 'America/Campo_Grande',
    'MG' => 'America/Sao_Paulo',    'PR' => 'America/Sao_Paulo',
    'PB' => 'America/Fortaleza',    'PA' => 'America/Belem',
    'PE' => 'America/Recife',       'PI' => 'America/Fortaleza',
    'RJ' => 'America/Sao_Paulo',    'RN' => 'America/Fortaleza',
    'RS' => 'America/Sao_Paulo',    'RO' => 'America/Porto_Velho',
    'RR' => 'America/Boa_Vista',    'SC' => 'America/Sao_Paulo',
    'SE' => 'America/Maceio',       'SP' => 'America/Sao_Paulo',
    'TO' => 'America/Araguaina',     'ND' => 'America/Sao_Paulo',
    );
    if($timezone){
        return $timezone["$sigla_estado"];
    }else{
        return 'America/Sao_Paulo';
    }

}

function getNomeServico($cod_servico){
    $nomeServico = array(
        'call_ativo' => 'Call Center - Ativo',
        'call_suporte' => 'Call Center - Suporte',
        'call_monitoramento' => 'Call Center - Monitoramento',
        'gestao_redes' => 'Gestão de Redes'
    );
    return $nomeServico[$cod_servico];
}

function getAcaoChamado($acao){
    $nomeAcao = array(
        'criacao' => 'Criação',
        'nota_geral' => 'Nota',
        'nota_interna' => 'Nota',
        'encerrar' => 'Encerrar',
        'encaminhar' => 'Troca de responsável',
        'desbloquear' => 'Desbloquear',
        'bloquear' => 'Bloquear',
        'reabrir' => 'Reabrir',
        'gerenciar' => 'Gerenciar envolvidos',
        'alterar' => 'Alteração',
        'pendencia' => 'Pendência',
        'assumir' => 'Assumiu responsabilidade',
        'alteracao_prazo_encerramento' => 'Alteração de prazo'
    );
    return $nomeAcao[$acao];
}

function getNomeStatusPlano($status){
    $nomeStatusPlano = array(
        '0' => 'Inativo',
        '1' => 'Ativo',
        '2' => 'Suspenso',
        '3' => 'Cancelado',
        '4' => 'Pausado',
        '5' => 'Em adesão',
        '6' => 'Bloqueado por falta de pagamento',
        '7' => 'Em ativação'
    );
    return $nomeStatusPlano[$status];
}

function getNomeTipoPlantaoRedes($tipo){

    $nomeTipoPlantaoRedes = array(
        '1' => '30 em 30',
        '2' => '60 em 60',
        '3' => '60 em 60 proporcional',
        '4' => 'Isento'
    );
    return $nomeTipoPlantaoRedes[$tipo];
}

function getNomeTipoHorarios($tipo){

    $nomeTipoHorario = array(
        '1' => 'Horários que a empresa está aberta',
        '2' => 'Horários que mandam as chamadas para a Belluno',
        '3' => 'Horários de atendimento dos técnicos de campo',
        '4' => 'Horários de monitoramento'
    );
    return $nomeTipoHorario[$tipo];
}

function registraLog($operacao, $tipo_operacao, $tb_alterada, $id_tb_alterada, $dados_tb_alterada){
    if(isset($_SESSION["id_usuario"])){
        $usuario = $_SESSION['id_usuario'];
    }else{
        $usuario = 0;
    }

    $data = getDataHora();
    $ip_origem = getIp();

    $dados = array (
        'id_usuario' => $usuario,
        'operacao' => $operacao,
        'tipo_operacao' => $tipo_operacao,
        'tb_alterada' => $tb_alterada,
        'id_tb_alterada' => $id_tb_alterada,
        'dados_tb_alterada' => $dados_tb_alterada,
        'sistema' => 'simples',
        'data' => $data,
        'ip_origem' => $ip_origem
    );
    DBCreate('', 'tb_log', $dados);
}

function registraLogTransaction($link, $operacao, $tipo_operacao, $tb_alterada, $id_tb_alterada, $dados_tb_alterada){
    if(isset($_SESSION["id_usuario"])){
        $usuario = $_SESSION['id_usuario'];
    }else{
        $usuario = 0;
    }

    $data = getDataHora();
    $ip_origem = getIp();

    $dados = array (
        'id_usuario' => $usuario,
        'operacao' => $operacao,
        'tipo_operacao' => $tipo_operacao,
        'tb_alterada' => $tb_alterada,
        'id_tb_alterada' => $id_tb_alterada,
        'dados_tb_alterada' => $dados_tb_alterada,
        'sistema' => 'simples',
        'data' => $data,
        'ip_origem' => $ip_origem
    );
    DBCreateTransaction($link, 'tb_log', $dados);
}

function notificacao($alteracao, $id_alterado, $operacao, $id_pessoa_alterou, $id_usuario, $dado_alterado){

    $dados = array(
        'alteracao' => $alteracao,
        'id_alterado' => $id_alterado,
        'operacao' => $operacao,
        'id_pessoa_alterou' => $id_pessoa_alterou,
        'dado_alterado' => $dado_alterado
    );

    $insertId = DBCreate('', 'tb_notificacao_alteracao', $dados, true);

    $dados_lido = array(
        'id_notificacao_alteracao' => $insertId,
        'id_usuario' => $id_usuario
    );

    $insertLido = DBCreate('', 'tb_notificacao_alteracao_lida', $dados_lido, true);

    registraLog('Inserção de notificação de alteração.', 'i', 'tb_notificacao_alteracao', $insertId, "alteracao: $alteracao | id_alterado: $id_alterado | operacao: $operacao | id_pessoa_alterou: $id_pessoa_alterou");

    registraLog('Inserção de notificação lida.', 'i', 'tb_notificacao_lida', $insertLido, "id_notificacao_alteracao_painel: $insertId | id_notificacao_alteracao: $insertId | id_usuario: $id_usuario");
}

function converteSegundosHoras($segundos) {
  $horas = sprintf('%02d',floor($segundos / 3600));
  $minutos = sprintf('%02d',floor(($segundos / 60) % 60));
  $segundos = sprintf('%02d', $segundos % 60);
  return "$horas:$minutos:$segundos";
}

function converteData($data){
    if (strstr($data, '/') and strlen($data) == 10) {
        list($d, $m, $a) = explode('/', $data);
        return "{$a}-{$m}-{$d}";
    } elseif (strstr($data, '-') and strlen($data) == 10) {
        list($a, $m, $d) = explode('-', $data);
        return "{$d}/{$m}/{$a}";
    } else {
        return '';
    }
}

function converteDataHora($data_hora){
    return converteData(substr($data_hora, 0, 10)).' '.substr($data_hora, 11, 5);
}

function converteHorasDecimal($horas){
    $horas = explode(":", $horas);
    return ($horas[0] + ($horas[1]/60) + ($horas[2]/3600));
}

function rangeDatas($data_de, $data_ate){
    $data_de = new DateTime($data_de);
    $data_ate = new DateTime($data_ate);
    $rangeDatas = array();
    while($data_de <= $data_ate){
        $rangeDatas[] = $data_de->format('Y-m-d');
        $data_de = $data_de->modify('+1day');
    }
    return $rangeDatas;
}

function validaData($dat){
    $data = explode("/","$dat");
    if((sizeof($data)==3)&&($data[0])&&($data[1])&&($data[2])){
        if((is_numeric($data[0]))&&(is_numeric($data[1]))&&(is_numeric($data[2]))){
            $d = $data[0];
            $m = $data[1];
            $a = $data[2];
        }else{
            return false;
        }
    }else{
        return false;
    }
    $res = checkdate($m,$d,$a);
    if ($res == 1){
        return true;
    } else {
        return false;
    }
}

function converteMoeda($num, $tipo = 'moeda'){
    if($tipo == 'moeda'){
        $num = number_format($num,2,',','.');
    }else if($tipo == 'banco'){
        $num = str_replace(".", "", $num);
        $num = str_replace(" ", "", $num);
        $num = str_replace(",", ".", $num);
    }else{
        return null;
    }
    return $num;
}

function valida_cpf( $cpf = false ) {
    if ( ! function_exists('calc_digitos_posicoes') )  {
        function calc_digitos_posicoes($digitos, $posicoes = 10, $soma_digitos = 0){
            for ( $i = 0; $i < strlen( $digitos ); $i++  ) {
                $soma_digitos = $soma_digitos + ($digitos[$i] * $posicoes);
                $posicoes--;
            }
            $soma_digitos = $soma_digitos % 11;
            if ( $soma_digitos < 2 ) {
                $soma_digitos = 0;
            } else {
                $soma_digitos = 11 - $soma_digitos;
            }
            $cpf = $digitos . $soma_digitos;
            return $cpf;
        }
    }
    if ( ! $cpf ) {
        return false;
    }
    $cpf = preg_replace( '/[^0-9]/is', '', $cpf );
    if ( strlen( $cpf ) != 11 ) {
        return false;
    }
    $digitos = substr($cpf, 0, 9);
    $novo_cpf = calc_digitos_posicoes( $digitos );
    $novo_cpf = calc_digitos_posicoes( $novo_cpf, 11);
    $iguais = false;
    if($cpf == "11111111111" || $cpf == "22222222222" || $cpf == "33333333333" || $cpf == "44444444444" || $cpf == "55555555555" || $cpf == "66666666666" || $cpf == "77777777777" || $cpf == "88888888888" || $cpf == "99999999999" || $cpf == "00000000000" ){
        $iguais = true;
    }
    if ( ($novo_cpf === $cpf) && (!$iguais) ) {
        return true;
    } else {
        return false;
    }
}

function valida_cnpj ( $cnpj ) {
    $cnpj = preg_replace( '/[^0-9]/', '', $cnpj );
    $cnpj = (string)$cnpj;
    $cnpj_original = $cnpj;
    $primeiros_numeros_cnpj = substr( $cnpj, 0, 12 );
    if ( ! function_exists('multiplica_cnpj') ) {
        function multiplica_cnpj( $cnpj, $posicao = 5 ){
            $calculo = 0;
            for( $i = 0; $i < strlen( $cnpj ); $i++ ){
                $calculo = $calculo + ( $cnpj[$i] * $posicao );
                $posicao--;
                if( $posicao < 2 ){
                    $posicao = 9;
                }
            }
            return $calculo;
        }
    }
    $primeiro_calculo = multiplica_cnpj( $primeiros_numeros_cnpj );
    $primeiro_digito = ( $primeiro_calculo % 11 ) < 2 ? 0 :  11 - ( $primeiro_calculo % 11 );
    $primeiros_numeros_cnpj .= $primeiro_digito;
    $segundo_calculo = multiplica_cnpj( $primeiros_numeros_cnpj, 6 );
    $segundo_digito = ( $segundo_calculo % 11 ) < 2 ? 0 :  11 - ( $segundo_calculo % 11 );
    $cnpj = $primeiros_numeros_cnpj . $segundo_digito;
    $iguais = false;
    if($cnpj_original == "11111111111111" || $cnpj_original == "22222222222222" || $cnpj_original == "33333333333333" || $cnpj_original == "44444444444444" || $cnpj_original == "55555555555555" || $cnpj_original == "66666666666666" || $cnpj_original == "77777777777777" || $cnpj_original == "88888888888888" || $cnpj_original == "99999999999999" || $cnpj_original == "00000000000000" ){
        $iguais = true;
    }
    if ( ($cnpj === $cnpj_original) && (!$iguais) ) {
        return true;
    } else {
        return false;
    }
}

function formataCampo ($tipo = "", $string){
    $string = preg_replace("/[^0-9]/", "", $string);
    $size = strlen($string);

    if($tipo == 'fone'){
        if($size === 10){
            $string = '(' . substr($string, 0, 2) . ') ' . substr($string, 2, 4) . '-' . substr($string, 6);
        }else
            if($size === 11){
            $string = '(' . substr($string, 0, 2) . ') ' . substr($string, 2, 5) . '-' . substr($string, 7);
        }
    }else if($tipo == 'cep'){
        $string = substr($string, 0, 5) . '-' . substr($string, 5, 3);
    }else if($tipo == 'cpf_cnpj'){
        if($size <= 11){
            $string = substr($string, 0, 3) . '.' . substr($string, 3, 3) . '.' . substr($string, 6, 3) . '-' . substr($string, 9, 2);
        }else{
            $string = substr($string, 0, 2) . '.' . substr($string, 2, 3) . '.' . substr($string, 5, 3) . '/' . substr($string, 8, 4) . '-' . substr($string, 12, 2);
        }
    }

    return $string;
}

function limitarTexto($texto, $limite, $token = ''){
    $contador = strlen($texto);
    if($token != '' && $contador >= $limite){
        $texto = substr($texto, 0, $limite).'...';
        // $texto = substr($texto, 0, strrpos(substr($texto, 0, $limite), ' ')) . '...';
        return $texto;
    }else if ( $contador >= $limite ) {
        $texto = substr($texto, 0, strrpos(substr($texto, 0, $limite), ' ')) . '...';
        return $texto;
    }else{
        return $texto;
    }
}

function troca_dados_curl($url = null, $dados = array(), $http_header = array(), $tipo = 'POST', $decode = true){

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $tipo);

    if($tipo == "POST"){
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dados);
    }
    if($tipo == "PATCH"){
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dados);
    }

	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $http_header);
    $result = curl_exec($ch);
    $err = curl_error($ch);
    curl_close($ch);

    if ($err) {
        return "cURL Error #:".$err;
    } else {

        if($decode){
            return json_decode($result, true);
        }else{
            return $result;
        }
    }
}

function array_orderby(){
    $args = func_get_args();
    $data = array_shift($args);
    foreach ($args as $n => $field) {
        if (is_string($field)) {
            $tmp = array();
            foreach ($data as $key => $row)
                $tmp[$key] = $row[$field];
            $args[$n] = $tmp;
            }
    }
    $args[] = &$data;
    call_user_func_array('array_multisort', $args);
    return array_pop($args);
}

function envia_email($assunto, $mensagem, $destino, $conta = null){
    require_once realpath($_SERVER["DOCUMENT_ROOT"]).'/v2/inc/php-mailer/Exception.php';
    require_once realpath($_SERVER["DOCUMENT_ROOT"]).'/v2/inc/php-mailer/PHPMailer.php';
    require_once realpath($_SERVER["DOCUMENT_ROOT"]).'/v2/inc/php-mailer/SMTP.php';

    $parametro = $conta=='financeiro' ? 'email_financeiro' : 'email_noreply';
    $dados_email = getConfig($parametro);

    $nome = $dados_email['EMAIL_NAME'];
    $usuario = $dados_email['EMAIL_USER'];
    $senha = $dados_email['EMAIL_PASSWORD'];

    $destino = str_replace(' ', '', $destino);
    $destino = str_replace(',', ';', $destino);
    $array_destino = explode(';',$destino);

    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP ();
        $mail->Host = "vps.bellunotec.com.br";
        $mail->Port = 465;
        $mail->SMTPSecure = "ssl";
        $mail->SMTPAuth = true;
        $mail->Username = $usuario;
        $mail->Password = $senha;
        $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );
        //$mail->SMTPDebug = 2;
        $mail->CharSet = 'UTF-8';
        $mail->AddReplyTo($usuario, $nome);
        $mail->setFrom($usuario, $nome);
        foreach($array_destino as $conteudo_destino){
            $mail->addAddress($conteudo_destino);
        }
        $mail->isHTML(true);
        $mail->Subject = $assunto;
        $mail->Body = $mensagem;
        $mail->send();

    } catch (Exception $e) {
        echo "Erro ao enviar e-mail. Mailer Error: {$mail->ErrorInfo}";
    }

}

function calcula_idade_data($data1, $data2){

    $data1= date('Y-m-d H:i:s', strtotime("+0 days",strtotime($data1)));
    $data2 = date('Y-m-d H:i:s', strtotime("+0 days",strtotime($data2)));

    if($data1 > $data2){
        $aux = $data1;
        $data1 = $data2;
        $data2 = $aux;
    }

    $idade = strtotime($data2) - strtotime($data1);

    $y = ($idade/(60*60*24*365));
    $d = ($idade/(60*60*24))%365;
    $h = ($idade/(60*60))%24;
    $m = ($idade/60)%60;
    $auxiliar = explode ("E", $y);
    if($auxiliar[1]){
        $y=0;
    }
    $y = explode (".", $y);
    $y = $y[0];

    $idade = '';

    if($y > 0){
        $idade = $idade."".$y." a ";
    }
    if($d > 0){
        $idade = $idade."".$d." d ";
    }
    if($h > 0){
        $idade = $idade."".$h." h ";
    }
    $idade = $idade."".$m." m ";
    return $idade;
}

function removeAcentos($string){
    return preg_replace(array("/(á|à|ã|â|ä)/","/(Á|À|Ã|Â|Ä)/","/(é|è|ê|ë)/","/(É|È|Ê|Ë)/","/(í|ì|î|ï)/","/(Í|Ì|Î|Ï)/","/(ó|ò|õ|ô|ö)/","/(Ó|Ò|Õ|Ô|Ö)/","/(ú|ù|û|ü)/","/(Ú|Ù|Û|Ü)/","/(ñ)/","/(Ñ)/","/(ç)/","/(Ç)/"),explode(" ","a A e E i I o O u U n N c C"),$string);
}

function busca_liderados($id_lider, $liderados = array()){
    $dados_liderados = DBRead('','tb_usuario',"WHERE status = 1 AND lider_direto = '$id_lider'");
    if($id_lider && $dados_liderados){
        foreach($dados_liderados as $conteudo_liderados){
            array_push($liderados, $conteudo_liderados['id_usuario']);
            $liderados = busca_liderados($conteudo_liderados['id_usuario'], $liderados);
        }
        return $liderados;
    }else{
        return $liderados;
    }
}

function busca_perfis_liderados($id_perfil_lider, $perfis = array()){
    $dados_perfis_liderados = DBRead('','tb_perfil_sistema',"WHERE status = 1 AND id_perfil_sistema_superior = '$id_perfil_lider'");

    if($id_perfil_lider && $dados_perfis_liderados){

        foreach($dados_perfis_liderados as $conteudo_perfis_liderados){

            array_push($perfis, $conteudo_perfis_liderados['id_perfil_sistema']);
            $perfis = busca_perfis_liderados($conteudo_perfis_liderados['id_perfil_sistema'], $perfis);
        }
        return $perfis;
    }else{
        return $perfis;
    }
}

function getDadosApiNfs($dado){
    if($dado == 'apiKey'){

        $parametro = 'token_api_nfs';
        $dados_api_nfs = getConfig($parametro);

        return $dados_api_nfs['TOKEN'];

    }else if($dado == 'empresaId'){
        /*
            Belluno: D96096EF-F04B-41BF-A623-F9B13A3D0500
            Empresa teste: 1FBA82ED-46B8-4932-85AD-A667383D0500
        */
        return 'D96096EF-F04B-41BF-A623-F9B13A3D0500';
    }
}

function getDadosApiBoletos($dado){
    if($dado == 'link'){
        /*
            Produção: https://plugboleto.com.br
            Homologação: http://homologacao.plugboleto.com.br
        */
        return 'https://plugboleto.com.br';
    }else if($dado == 'token-sh'){

        $parametro = 'token_api_boleto';
        $dados_api_boleto = getConfig($parametro);
        return $dados_api_boleto['TOKEN'];
    }else if($dado == 'cnpj-sh'){
        return '12165644000119';
    }else if($dado == 'cnpj-cedente'){
        return '12165644000119';
    }
}

function getDataRemessaBoleto($data){
    $data = explode('-',$data);
    $mes = (int)$data[1];
    $dia = $data[2];
    if($mes == 10){
        $mes = 'O';
    }else if($mes == 11){
        $mes = 'N';
    }else if($mes == 12){
        $mes = 'D';
    }
    return $mes.$dia;
}

function unique_multidim_array($array, $key) {
    $temp_array = array();
    $i = 0;
    $key_array = array();

    foreach($array as $val) {
        if (!in_array($val[$key], $key_array)) {
            $key_array[$i] = $val[$key];
            $temp_array[$i] = $val;
        }
        $i++;
    }
    return $temp_array;
}

function getDadosApiBotmaker($dado){
    if($dado == 'access-token'){
        //return 'eyJhbGciOiJIUzUxMiJ9.eyJidXNpbmVzc0lkIjoiYmVsbHVub3Byb2QiLCJuYW1lIjoiQXVndXN0byBDYXJkb3NvIiwiYXBpIjp0cnVlLCJpZCI6Ims0VnJlSjB3UjdXUHlEenRvc2hidEtoblRKZzEiLCJleHAiOjE3NTc1Mjk5NDIsImp0aSI6Ims0VnJlSjB3UjdXUHlEenRvc2hidEtoblRKZzEifQ.1UDurrf-_tNYmWtl5kKbyncoyskq-eMyOdoviElVUExpbLmnJueej0NDjE2G0huHCQTdiHsdGQ7GjG4gnFVUIw';

        return 'eyJhbGciOiJIUzUxMiJ9.eyJidXNpbmVzc0lkIjoiYmVsbHVub3Byb2QiLCJuYW1lIjoiS2VuZHkgSGF5YXNoaSIsImFwaSI6dHJ1ZSwiaWQiOiJGM2NXcXZtVjc3YUdWNXhqMkpGQlF5b3NOMEIyIiwiZXhwIjoxNzczNjkyOTMwLCJqdGkiOiJGM2NXcXZtVjc3YUdWNXhqMkpGQlF5b3NOMEIyIn0.XSOQTM_94IwRg4m1OdzYfh4fWzUdgugcuTjFWyrQZRFnKAnep0VqezW_FJO7RSrhwgqjMVTw_XpGtoudAsUx1Q';


    }else if($dado == 'link'){
        return 'https://go.botmaker.com/api/v1.0';
    }
}

function corAvatar($dado){
    if(removeAcentos(strtoupper($dado[0])) == 'A'){
        $cor_background_letra = "color:#fff; background-color: #3D3247;";
    }if(removeAcentos(strtoupper($dado[0])) == 'B'){
        $cor_background_letra = "color:#fff; background-color: #4E6821;";
    }if(removeAcentos(strtoupper($dado[0])) == 'C'){
        $cor_background_letra = "color:#fff; background-color: #8E7B77;";
    }if(removeAcentos(strtoupper($dado[0])) == 'D'){
        $cor_background_letra = "color:#fff; background-color: #AEC258;";
    }if(removeAcentos(strtoupper($dado[0])) == 'E'){
        $cor_background_letra = "color:#fff; background-color: #FA9247;";
    }if(removeAcentos(strtoupper($dado[0])) == 'F'){
        $cor_background_letra = "color:#fff; background-color: #DD675B;";
    }if(removeAcentos(strtoupper($dado[0])) == 'G'){
        $cor_background_letra = "color:#fff; background-color: #247884;";
    }if(removeAcentos(strtoupper($dado[0])) == 'H'){
        $cor_background_letra = "color:#fff; background-color: #CDDD7C;";
    }if(removeAcentos(strtoupper($dado[0])) == 'I'){
        $cor_background_letra = "color:#fff; background-color: #1E1B2A;";
    }if(removeAcentos(strtoupper($dado[0])) == 'J'){
        $cor_background_letra = "color:rgba(0, 0, 0, 0.87); background-color: #F9BC0C;";
    }if(removeAcentos(strtoupper($dado[0])) == 'K'){
        $cor_background_letra = "color:#fff; background-color: #9A4125;";
    }if(removeAcentos(strtoupper($dado[0])) == 'L'){
        $cor_background_letra = "color:#fff; background-color: #18757A;";
    }if(removeAcentos(strtoupper($dado[0])) == 'M'){
        $cor_background_letra = "color:#fff; background-color: #DB3461;";
    }if(removeAcentos(strtoupper($dado[0])) == 'N'){
        $cor_background_letra = "color:#fff; background-color: #B33D4D;";
    }if(removeAcentos(strtoupper($dado[0])) == 'O'){
        $cor_background_letra = "color:#fff; background-color: #E98865;";
    }if(removeAcentos(strtoupper($dado[0])) == 'P'){
        $cor_background_letra = "color:#fff; background-color: #08AFA7;";
    }if(removeAcentos(strtoupper($dado[0])) == 'Q'){
        $cor_background_letra = "color:#fff; background-color: #0C3230;";
    }if(removeAcentos(strtoupper($dado[0])) == 'R'){
        $cor_background_letra = "color:#fff; background-color: #EE621F;";
    }if(removeAcentos(strtoupper($dado[0])) == 'S'){
        $cor_background_letra = "color:#fff; background-color: #79814E;";
    }if(removeAcentos(strtoupper($dado[0])) == 'T'){
        $cor_background_letra = "color:#fff; background-color: #C2B541;";
    }if(removeAcentos(strtoupper($dado[0])) == 'U'){
        $cor_background_letra = "color:#fff; background-color: #AD1B12;";
    }if(removeAcentos(strtoupper($dado[0])) == 'V'){
        $cor_background_letra = "color:rgba(0, 0, 0, 0.87); background-color: #92A9BD;";
    }if(removeAcentos(strtoupper($dado[0])) == 'X'){
        $cor_background_letra = "color:rgba(0, 0, 0, 0.87); background-color: #CD947A;";
    }if(removeAcentos(strtoupper($dado[0])) == 'Z'){
        $cor_background_letra = "color:rgba(0, 0, 0, 0.87); background-color: #B6A75D;";
    }if(removeAcentos(strtoupper($dado[0])) == 'W'){
        $cor_background_letra = "color:#fff; background-color: #436261;";
    }if(removeAcentos(strtoupper($dado[0])) == 'Y'){
        $cor_background_letra = "color:rgba(0, 0, 0, 0.87); background-color: #D9C7C2;";
    }if(removeAcentos(strtoupper($dado[0])) == '0'){
        $cor_background_letra = "color:rgba(0, 0, 0, 0.87); background-color: #DBE061;";
    }if(removeAcentos(strtoupper($dado[0])) == '1'){
        $cor_background_letra = "color:#fff; background-color: #941F1D;";
    }if(removeAcentos(strtoupper($dado[0])) == '2'){
        $cor_background_letra = "color:rgba(0, 0, 0, 0.87); background-color: #ADD3A2;";
    }if(removeAcentos(strtoupper($dado[0])) == '3'){
        $cor_background_letra = "color:#fff; background-color: #E34E24;";
    }if(removeAcentos(strtoupper($dado[0])) == '4'){
        $cor_background_letra = "color:#fff; background-color: #35B988;";
    }if(removeAcentos(strtoupper($dado[0])) == '5'){
        $cor_background_letra = "color:#fff; background-color: #523770;";
    }if(removeAcentos(strtoupper($dado[0])) == '6'){
        $cor_background_letra = "color:#fff; background-color: #8B3371;";
    }if(removeAcentos(strtoupper($dado[0])) == '7'){
        $cor_background_letra = "color:#fff; background-color: #8D6670;";
    }if(removeAcentos(strtoupper($dado[0])) == '8'){
        $cor_background_letra = "color:rgba(0, 0, 0, 0.87); background-color: #FCB5A6;";
    }if(removeAcentos(strtoupper($dado[0])) == '9'){
        $cor_background_letra = "color:#fff; background-color: #41D090;";
    }

    return $cor_background_letra;
}

function verificaVinculo($id_perfil_sistema, $var){
    if ($id_perfil_sistema !='' && $var !='') {

        $cont = 0;

        if (is_array($var)) {
            foreach ($var as $id) {

                $check = DBRead('','tb_perfil_sistema_vinculo',"WHERE id_perfil_sistema = $id_perfil_sistema AND id_perfil_sistema_vinculado = $id");

                if ($check) {
                    $cont++;
                }
            }
        } else {
            $check = DBRead('','tb_perfil_sistema_vinculo',"WHERE id_perfil_sistema = $id_perfil_sistema AND id_perfil_sistema_vinculado = $var");

            if ($check) {
                $cont++;
            }
        }

        if ($cont != 0) {
            return true;

        } else {
            return false;
        }

    }
}

function getVinculos($id_perfil_sistema){
    if ($id_perfil_sistema !=''){

        $perfis = DBRead('','tb_perfil_sistema_vinculo a',"INNER JOIN tb_perfil_sistema b ON a.id_perfil_sistema_vinculado = b.id_perfil_sistema WHERE a.id_perfil_sistema = $id_perfil_sistema", "b.id_perfil_sistema, b.nome");

        return $perfis;
    }
}

function mudaVinculoArray($var) {
    if ($var != '') {
        $array = array();

        foreach($var as $key => $conteudo) {
            array_push($array, $conteudo['id_perfil_sistema']);
        }

        return $array;
    }
}

function getPessoa($id_usuario) {

    $pessoa = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_usuario = $id_usuario");

    return $pessoa;
}

function getContrato($id_contrato_plano_pessoa) {
    $empresa = DBRead('', 'tb_contrato_plano_pessoa a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa INNER JOIN tb_plano c ON a.id_plano = c.id_plano WHERE id_contrato_plano_pessoa = $id_contrato_plano_pessoa", "a.*, a.nome_contrato, b.nome, c.nome AS plano, c.cod_servico");

    $contrato = $empresa[0]['nome'];

    if($empresa[0]['nome_contrato']){
        $contrato .= " (".$empresa[0]['nome_contrato'].") ";
    }

    $contrato .= " - " . getNomeServico($empresa[0]['cod_servico']) . " - " . $empresa[0]['plano'] . " (" . $empresa[0]['id_contrato_plano_pessoa'] . ")";

    return $contrato;
}

function verificaEscala ($id_usuario, $data_hora_verifica) {

    //operador não tá de férias
    $data_da_consulta_dia = explode(' ', $data_hora_verifica);
    $dados_horario = DBRead('', 'tb_usuario',"WHERE id_usuario = '".$id_usuario."' AND id_usuario NOT IN (SELECT id_usuario FROM tb_ferias WHERE data_de <= '".$data_da_consulta_dia[0]."' AND data_ate >= '".$data_da_consulta_dia[0]."')");

    if (!$dados_horario) {
        return false;

    } else {

        $diasemana = array(
            "0" => "Domingo",
            "1" => "Segunda",
            "2" => "Terça",
            "3" => "Quarta",
            "4" => "Quinta",
            "5" => "Sexta",
            "6" => "Sábado",
        );

        $data_da_consulta = explode('-', $data_hora_verifica);

        $horas = $data_da_consulta[2];
        $horas = explode(' ', $horas);
        $horas = explode(':', $horas[1]);
        $minutos_da_consulta = $horas[1];
        $hora_da_consulta = $horas[0];
        //$hora_agora = date('H:i');
        $diasemana_numero = date('w', strtotime($data_hora_verifica));
        $diasemana_numero = $diasemana[$diasemana_numero];
        $data_de_hoje = explode(' ', getDataHora());
        $data_de_hoje = $data_de_hoje[0];

        $data_da_consulta = $data_da_consulta[0].'-'.$data_da_consulta[1].'-01';

        if($diasemana_numero == 'Domingo'){
            $chave_nome_inicial = 'inicial_dom';
            $chave_nome_final = 'final_dom';

        }else if($diasemana_numero == 'Sábado'){
            $chave_nome_inicial = 'inicial_sab';
            $chave_nome_final = 'final_sab';

        }else{
            $chave_nome_inicial = 'inicial_seg';
            $chave_nome_final = 'final_seg';
        }

        $diasemana_numero = date('w', strtotime($data_hora_verifica));
        $data_hora = date('Y-m-d H:i:s');
        $diasemana_numero = $diasemana[$diasemana_numero];

        $data_ontem = new DateTime($data_hora_verifica);
        $data_ontem->modify('-1 day');
        $data_ontem = $data_ontem->format("Y-m-d");

        $diasemana_numero_ontem = date('w', strtotime($data_ontem));
        $diasemana_numero_ontem = $diasemana[$diasemana_numero_ontem];

        if($diasemana_numero_ontem == 'Domingo'){
            $chave_nome_inicial_ontem = 'inicial_dom';
            $chave_nome_final_ontem = 'final_dom';

        }else if($diasemana_numero_ontem == 'Sábado'){
            $chave_nome_inicial_ontem = 'inicial_sab';
            $chave_nome_final_ontem = 'final_sab';

        }else{
            $chave_nome_inicial_ontem = 'inicial_seg';
            $chave_nome_final_ontem = 'final_seg';
        }

        $dados = DBRead('', 'tb_usuario a', "INNER JOIN tb_horarios_escala c ON a.id_usuario = c.id_usuario INNER JOIN tb_chat_horarios_escala d ON c.id_horarios_escala = d.id_horarios_escala WHERE a.id_perfil_sistema = '3' AND a.status = 1 AND c.data_inicial = '".$data_da_consulta."' AND c.chat = 1 AND c.id_usuario = '".$id_usuario."'" , "a.id_usuario, c.*, d.*");

        if ($dados) {
            //Boneco tem horario especial hoje
            $dados_horario_especial = DBRead('', 'tb_horarios_especiais a',"INNER JOIN tb_chat_horarios_especiais b ON a.id_horarios_especiais = b.id_horarios_especiais WHERE a.dia = '".$data_hora_verifica."' AND a.id_horarios_escala = '".$dados[0]['id_horarios_escala']."' ", "b.*");

            if ($dados_horario_especial) {
                $horario_inicial_chat = explode(':', $dados_horario_especial[0]['inicial_especial']);
                $horario_minutos_inicial_chat = $horario_inicial_chat[1];
                $horario_inicial_chat = $horario_inicial_chat[0];

                $horario_final_chat = explode(':', $dados_horario_especial[0]['final_especial']);
                $horario_minutos_final_chat = $horario_final_chat[1];
                $horario_final_chat = $horario_final_chat[0];

                if ($horario_inicial_chat < $horario_final_chat) {
                    if ($horario_inicial_chat <= $hora_da_consulta && $horario_final_chat >= $hora_da_consulta) {
                        if ($horario_inicial_chat == $hora_da_consulta) {
                            if ($horario_minutos_inicial_chat <= $minutos_da_consulta) {
                                return true;
                            } else {
                                return false;
                            }

                        } else if ($horario_final_chat == $hora_da_consulta) {
                            if ($horario_minutos_final_chat >= $minutos_da_consulta) {
                                return true;
                            } else {
                                return false;
                            }
                        } else {
                            return true;
                        }
                    } else {
                        return false;
                    }
                } else {
                    if ($horario_inicial_chat <= $hora_da_consulta) {
                        if ($horario_inicial_chat == $hora_da_consulta) {
                            if($horario_minutos_inicial_chat <= $minutos_da_consulta){
                                return true;
                            }else{
                                return false;
                            }

                        }else if($horario_final_chat == $hora_da_consulta){
                            if($horario_minutos_final_chat >= $minutos_da_consulta){
                                return true;
                            }else{
                                return false;
                            }
                        }else{
                            return true;
                        }
                    } else{

                        $dados_horario_ontem = DBRead('', 'tb_usuario',"WHERE id_usuario = '".$id_usuario."' AND id_usuario NOT IN (SELECT id_usuario FROM tb_ferias WHERE data_de <= '".$data_ontem."' AND data_ate >= '".$data_ontem."')");

                        $dados_horario_especial_ontem = DBRead('', 'tb_horarios_especiais a',"INNER JOIN tb_chat_horarios_especiais b ON a.id_horarios_especiais = b.id_horarios_especiais WHERE a.dia = '".$data_ontem."' AND a.id_horarios_escala = '".$dados[0]['id_horarios_escala']."' ", "b.*");

                        if($dados_horario_ontem){

                            if($dados_horario_especial_ontem){
                                $horario_inicial_chat_ontem = explode(':', $dados_horario_especial_ontem[0]['inicial_especial']);
                                $horario_minutos_inicial_chat_ontem = $horario_inicial_chat_ontem[1];
                                $horario_inicial_chat_ontem = $horario_inicial_chat_ontem[0];

                                $horario_final_chat_ontem = explode(':', $dados_horario_especial_ontem[0]['final_especial']);
                                $horario_minutos_final_chat_ontem = $horario_final_chat_ontem[1];
                                $horario_final_chat_ontem = $horario_final_chat_ontem[0];

                                if($horario_inicial_chat_ontem >= $horario_final_chat_ontem){
                                    if($horario_final_chat_ontem >= $hora_da_consulta){
                                        if($horario_inicial_chat == $hora_da_consulta){
                                            if($horario_minutos_inicial_chat >= $minutos_da_consulta){
                                                return true;
                                            }else{
                                                return false;
                                            }

                                        }else{
                                            return true;
                                        }
                                    }else{
                                        return false;
                                    }
                                }


                            }else if($diasemana_numero_ontem == 'Domingo'){
                                $dados_folga_domingo_ontem = DBRead('', 'tb_folgas_dom',"WHERE id_horarios_escala = '".$dados[0]['id_horarios_escala']."' AND dia = '".$data_ontem."'");

                                if(!$dados_folga_domingo_ontem){
                                    $horario_inicial_chat_ontem = explode(':', $dados[0][$chave_nome_inicial_ontem]);
                                    $horario_inicial_chat_ontem = $horario_inicial_chat_ontem[0];

                                    $horario_final_chat_ontem = explode(':', $dados[0][$chave_nome_final_ontem]);
                                    $horario_final_chat_ontem = $horario_final_chat_ontem[0];

                                    if($horario_inicial_chat_ontem >= $horario_final_chat_ontem){
                                        if($horario_final_chat_ontem >= $hora_da_consulta){
                                            return true;
                                        }else{
                                            return false;
                                        }
                                    }

                                }
                            }else if($dados[0]['folga_seg'] != $diasemana_numero_ontem){

                                $horario_inicial_chat_ontem = explode(':', $dados[0][$chave_nome_inicial_ontem]);
                                $horario_minutos_inicial_chat_ontem = $horario_inicial_chat_ontem[1];
                                $horario_inicial_chat_ontem = $horario_inicial_chat_ontem[0];

                                $horario_final_chat_ontem = explode(':', $dados[0][$chave_nome_final_ontem]);
                                $horario_minutos_final_chat_ontem = $horario_final_chat_ontem[1];
                                $horario_final_chat_ontem = $horario_final_chat_ontem[0];

                                if($horario_inicial_chat_ontem >= $horario_final_chat_ontem){
                                    if($horario_final_chat_ontem >= $hora_da_consulta){
                                        if($horario_final_chat_ontem == $hora_da_consulta){
                                            if($horario_minutos_final_chat_ontem >= $minutos_da_consulta){
                                                return true;
                                            }else{
                                                return false;
                                            }
                                        }else{
                                            return true;
                                        }
                                    }else{
                                        return false;
                                    }
                                }

                            }
                        }
                    }
                }

            }else if($diasemana_numero == 'Domingo'){
                $dados_folga_domingo_hoje = DBRead('', 'tb_folgas_dom',"WHERE id_horarios_escala = '".$dados[0]['id_horarios_escala']."' AND dia = '".$data_da_consulta."'");

                if(!$dados_folga_domingo_hoje){
                    $horario_inicial_chat = explode(':', $dados[0][$chave_nome_inicial]);
                    $horario_minutos_inicial_chat = $horario_inicial_chat[1];
                    $horario_inicial_chat = $horario_inicial_chat[0];

                    $horario_final_chat = explode(':', $dados[0][$chave_nome_final]);

                    $horario_minutos_final_chat = $horario_final_chat[1];
                    $horario_final_chat = $horario_final_chat[0];

                    if($horario_inicial_chat < $horario_final_chat){
                        if($horario_inicial_chat <= $hora_da_consulta && $horario_final_chat >= $hora_da_consulta){
                            if($horario_inicial_chat == $hora_da_consulta){
                                if($horario_minutos_inicial_chat <= $minutos_da_consulta){
                                    return true;
                                }else{
                                    return false;
                                }

                            }else if($horario_final_chat == $hora_da_consulta){
                                if($horario_minutos_final_chat >= $minutos_da_consulta){
                                    return true;
                                }else{
                                    return false;
                                }
                            }else{
                                return true;
                            }
                        }else{
                            return false;
                        }
                    }else{
                        if($horario_inicial_chat <= $hora_da_consulta){
                            if($horario_inicial_chat == $hora_da_consulta){
                                if($horario_minutos_inicial_chat <= $minutos_da_consulta){
                                    return true;
                                }else{
                                    return false;
                                }

                            }else if($horario_final_chat == $hora_da_consulta){
                                if($horario_minutos_final_chat >= $minutos_da_consulta){
                                    return true;
                                }else{
                                    return false;
                                }
                            }else{
                                return true;
                            }
                        }else{

                            $dados_horario_ontem = DBRead('', 'tb_usuario',"WHERE id_usuario = '".$id_usuario."' AND id_usuario NOT IN (SELECT id_usuario FROM tb_ferias WHERE data_de <= '".$data_ontem."' AND data_ate >= '".$data_ontem."')");

                            $dados_horario_especial_ontem = DBRead('', 'tb_horarios_especiais a',"INNER JOIN tb_chat_horarios_especiais b ON a.id_horarios_especiais = b.id_horarios_especiais WHERE a.dia = '".$data_ontem."' AND a.id_horarios_escala = '".$dados[0]['id_horarios_escala']."' ", "b.*");

                            if($dados_horario_ontem){

                                if($dados_horario_especial_ontem){
                                    $horario_inicial_chat_ontem = explode(':', $dados_horario_especial_ontem[0]['inicial_especial']);
                                    $horario_minutos_inicial_chat_ontem = $horario_inicial_chat_ontem[1];
                                    $horario_inicial_chat_ontem = $horario_inicial_chat_ontem[0];

                                    $horario_final_chat_ontem = explode(':', $dados_horario_especial_ontem[0]['final_especial']);
                                    $horario_minutos_final_chat_ontem = $horario_final_chat_ontem[1];
                                    $horario_final_chat_ontem = $horario_final_chat_ontem[0];

                                    if($horario_inicial_chat_ontem >= $horario_final_chat_ontem){
                                        if($horario_final_chat_ontem >= $hora_da_consulta){
                                            if($horario_inicial_chat == $hora_da_consulta){
                                                if($horario_minutos_inicial_chat >= $minutos_da_consulta){
                                                    return true;
                                                }else{
                                                    return false;
                                                }

                                            }else{
                                                return true;
                                            }
                                        }else{
                                            return false;
                                        }
                                    }


                                }else if($dados[0]['folga_seg'] != $diasemana_numero_ontem){

                                    $horario_inicial_chat_ontem = explode(':', $dados[0][$chave_nome_inicial_ontem]);
                                    $horario_minutos_inicial_chat_ontem = $horario_inicial_chat_ontem[1];
                                    $horario_inicial_chat_ontem = $horario_inicial_chat_ontem[0];

                                    $horario_final_chat_ontem = explode(':', $dados[0][$chave_nome_final_ontem]);
                                    $horario_minutos_final_chat_ontem = $horario_final_chat_ontem[1];
                                    $horario_final_chat_ontem = $horario_final_chat_ontem[0];

                                    if($horario_inicial_chat_ontem >= $horario_final_chat_ontem){
                                        if($horario_final_chat_ontem >= $hora_da_consulta){
                                            if($horario_final_chat_ontem == $hora_da_consulta){
                                                if($horario_minutos_final_chat_ontem >= $minutos_da_consulta){
                                                    return true;
                                                }else{
                                                    return false;
                                                }
                                            }else{
                                                return true;
                                            }
                                        }else{
                                            return false;
                                        }
                                    }

                                }
                            }
                        }
                    }

                }
            }else if($dados[0]['folga_seg'] != $diasemana_numero){

                $horario_inicial_chat = explode(':', $dados[0][$chave_nome_inicial]);
                $horario_minutos_inicial_chat = $horario_inicial_chat[1];
                $horario_inicial_chat = $horario_inicial_chat[0];

                $horario_final_chat = explode(':', $dados[0][$chave_nome_final]);
                $horario_minutos_final_chat = $horario_final_chat[1];
                $horario_final_chat = $horario_final_chat[0];

                //VERIFICA SE É ao CONTRARIO
                if($horario_inicial_chat < $horario_final_chat){
                    if ($horario_inicial_chat <= $hora_da_consulta && $horario_final_chat >= $hora_da_consulta) {
                        if ($horario_inicial_chat == $hora_da_consulta) {
                            if ($horario_minutos_inicial_chat <= $minutos_da_consulta) {
                                return true;
                            } else {
                                return false;
                            }

                        } else if ($horario_final_chat == $hora_da_consulta) {
                            if ($horario_minutos_final_chat >= $minutos_da_consulta) {
                                return true;
                            } else {
                                return false;
                            }
                        } else {
                            return true;
                        }
                    } else {
                        return false;
                    }
                }else{

                    if ($horario_inicial_chat <= $hora_da_consulta) {
                        if ($horario_inicial_chat == $hora_da_consulta) {
                            if($horario_minutos_inicial_chat <= $minutos_da_consulta){
                                return true;
                            }else{
                                return false;
                            }

                        }else if($horario_final_chat == $hora_da_consulta){
                            if($horario_minutos_final_chat >= $minutos_da_consulta){
                                return true;
                            }else{
                                return false;
                            }
                        }else{
                            return true;
                        }
                    }else{

                        $dados_horario_ontem = DBRead('', 'tb_usuario',"WHERE id_usuario = '".$id_usuario."' AND id_usuario NOT IN (SELECT id_usuario FROM tb_ferias WHERE data_de <= '".$data_ontem."' AND data_ate >= '".$data_ontem."')");

                        $dados_horario_especial_ontem = DBRead('', 'tb_horarios_especiais a',"INNER JOIN tb_chat_horarios_especiais b ON a.id_horarios_especiais = b.id_horarios_especiais WHERE a.dia = '".$data_ontem."' AND a.id_horarios_escala = '".$dados[0]['id_horarios_escala']."' ", "b.*");

                        if($dados_horario_ontem){

                            if($dados_horario_especial_ontem){
                                $horario_inicial_chat_ontem = explode(':', $dados_horario_especial_ontem[0]['inicial_especial']);
                                $horario_minutos_inicial_chat_ontem = $horario_inicial_chat_ontem[1];
                                $horario_inicial_chat_ontem = $horario_inicial_chat_ontem[0];

                                $horario_final_chat_ontem = explode(':', $dados_horario_especial_ontem[0]['final_especial']);
                                $horario_minutos_final_chat_ontem = $horario_final_chat_ontem[1];
                                $horario_final_chat_ontem = $horario_final_chat_ontem[0];

                                if($horario_inicial_chat_ontem >= $horario_final_chat_ontem){
                                    if($horario_final_chat_ontem >= $hora_da_consulta){
                                        if($horario_inicial_chat == $hora_da_consulta){
                                            if($horario_minutos_inicial_chat >= $minutos_da_consulta){
                                                return true;
                                            }else{
                                                return false;
                                            }

                                        }else{
                                            return true;
                                        }
                                    }else{
                                        return false;
                                    }
                                }


                            }else if($diasemana_numero_ontem == 'Domingo'){
                                $dados_folga_domingo_ontem = DBRead('', 'tb_folgas_dom',"WHERE id_horarios_escala = '".$dados[0]['id_horarios_escala']."' AND dia = '".$data_ontem."'");

                                if(!$dados_folga_domingo_ontem){
                                    $horario_inicial_chat_ontem = explode(':', $dados[0][$chave_nome_inicial_ontem]);
                                    $horario_inicial_chat_ontem = $horario_inicial_chat_ontem[0];

                                    $horario_final_chat_ontem = explode(':', $dados[0][$chave_nome_final_ontem]);
                                    $horario_final_chat_ontem = $horario_final_chat_ontem[0];

                                    if($horario_inicial_chat_ontem >= $horario_final_chat_ontem){
                                        if($horario_final_chat_ontem >= $hora_da_consulta){
                                            return true;
                                        }else{
                                            return false;
                                        }
                                    }

                                }
                            }else if($dados[0]['folga_seg'] != $diasemana_numero_ontem){

                                $horario_inicial_chat_ontem = explode(':', $dados[0][$chave_nome_inicial_ontem]);
                                $horario_minutos_inicial_chat_ontem = $horario_inicial_chat_ontem[1];
                                $horario_inicial_chat_ontem = $horario_inicial_chat_ontem[0];

                                $horario_final_chat_ontem = explode(':', $dados[0][$chave_nome_final_ontem]);
                                $horario_minutos_final_chat_ontem = $horario_final_chat_ontem[1];
                                $horario_final_chat_ontem = $horario_final_chat_ontem[0];

                                if($horario_inicial_chat_ontem >= $horario_final_chat_ontem){
                                    if($horario_final_chat_ontem >= $hora_da_consulta){
                                        if($horario_final_chat_ontem == $hora_da_consulta){
                                            if($horario_minutos_final_chat_ontem >= $minutos_da_consulta){
                                                return true;
                                            }else{
                                                return false;
                                            }
                                        }else{
                                            return true;
                                        }
                                    }else{
                                        return false;
                                    }
                                }

                            }
                        }
                    }

                }

            }
        } else {
            return false;
        }

    }
}

function verificaGrupo ($id_usuario) {

    $dados_grupo_chat = DBRead('', 'tb_grupo_atendimento_chat_operador a',"INNER JOIN tb_grupo_atendimento_chat b ON a.id_grupo_atendimento_chat = b.id_grupo_atendimento_chat WHERE a.id_usuario = '".$id_usuario."' ");

    if($dados_grupo_chat){
        return $dados_grupo_chat;
    }else{
        return false;
    }

}

function getConfig($parametro){

    include( __DIR__ . '/../config.php');

    if($parametro == 'banco_simples'){
        return $banco_simples;
    }elseif($parametro == 'banco_snep'){
        return $banco_snep;
    }elseif($parametro == 'banco_otrs'){
        return $banco_otrs;
    }elseif($parametro == 'api_rd'){
        return $api_rd;
    }elseif($parametro == 'api_google'){
        return $api_google;
    }elseif($parametro == 'email_financeiro'){
        return $email_financeiro;
    }elseif($parametro == 'email_noreply'){
        return $email_noreply;
    }elseif($parametro == 'token_api_boleto'){
        return $token_api_boleto;
    }elseif($parametro == 'token_api_nfs'){
        return $token_api_nfs;
    }else{
        return false;
    }
}
?>