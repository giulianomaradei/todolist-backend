<?php
require_once(__DIR__."/System.php");
$retorno_entradas = troca_dados_curl("http://172.31.18.211/central_simples/retorna_entradas.php");
$prefix = '';
$id_asterisk = '';
$sip_usuario = '';
$ponte = '';
$bina = '';
$id_usuario = $_SESSION["id_usuario"];
$dados_usuario = DBRead('','tb_usuario',"WHERE id_usuario = '$id_usuario'");
if($dados_usuario){
    $id_asterisk = $dados_usuario[0]['id_asterisk'];
}
$dados_usuario_central = DBRead('snep','queue_agents',"WHERE codigo = '$id_asterisk'");
if($dados_usuario_central){
    $sip_usuario = explode("/", $dados_usuario_central[0]['interface_logged']);
    $sip_usuario = $sip_usuario[1];
}
foreach ($retorno_entradas['dados'] as $conteudo_entradas) {
    if($conteudo_entradas['local'] == '(Outgoing Line)' && $conteudo_entradas['sip'] == $sip_usuario){
        $ponte = $conteudo_entradas['ponte'];
        break;
    }
}
foreach ($retorno_entradas['dados'] as $conteudo_entradas) {
    if($conteudo_entradas['local'] != '(Outgoing Line)' && $conteudo_entradas['ponte'] == $ponte){
        $prefix = explode("-", $conteudo_entradas['callerid']);
        $prefix = $prefix[0];
        $numero_atendimento = explode('-', $conteudo_entradas['callerid']);
        if(is_numeric(end($numero_atendimento)) && strlen(end($numero_atendimento)) >= 10){
            $bina = ltrim(end($numero_atendimento), '0');
        }
        break;
    }
}

if($prefix && $id_asterisk && $sip_usuario && $ponte){
    $dados_parametros = DBRead('','tb_parametros',"WHERE id_asterisk = '$prefix'");
    if($dados_parametros){
        $id_contrato_plano_pessoa = $dados_parametros[0]['id_contrato_plano_pessoa'];
        if($bina){
            $bina = '&bina='.$bina;
        }
        header("location: /api/iframe?token=$request->token&view=atendimento-inicio-form&contrato=$id_contrato_plano_pessoa".$bina);
    }else{
        $alert = ('Não foi possível localizar o contrato automaticamente!','w');
        header("location: /api/iframe?token=$request->token&view=atendimento-busca");
    }
}else{
    $alert = ('Não foi possível localizar o contrato automaticamente!','w');
    header("location: /api/iframe?token=$request->token&view=atendimento-busca");
}
exit;
?>