<?php
require_once(__DIR__."/System.php");
require_once(__DIR__."/QuadroInformativoHistorico.php");
include_once (__DIR__."/integracoes/ixc/Usuarios.php");


$id_integracao = (!empty($_POST['id_integracao'])) ? $_POST['id_integracao'] : ''; //id do sistema de gestão que será integrado, esse campo da tabela tb_integracao faz parte da configuração fixa dos parâmetros de integração de cada sistema integrado ao Simples
$host = (!empty($_POST['host'])) ? $_POST['host'] : ''; //Configura a url de acesso a API de integração, esse campo faz parte da configuração fixa dos parâmetros de integração de cada sistema integrado ao Simples
$id_contrato_plano_pessoa  = (!empty($_POST['id_contrato_plano_pessoa'])) ? $_POST['id_contrato_plano_pessoa'] : ''; //O id do contrato é armazenado para configurar a integração para cada cliente

$id_integracao_parametro = (!empty($_POST['id_integracao_parametro'])) ? $_POST['id_integracao_parametro'] : array(); //Trás um array com todos os ids da tabela tb_integracao_parametro, responsável por armazenar as configurações dinâmicas dos parâmetros de cada integração com sistemas de gestão

$usuario_integrado = (!empty($_POST['usuario_integrado'])) ? $_POST['usuario_integrado'] : 0;

$valores = DBRead('', 'tb_integracao_parametro', "WHERE id_integracao = '".$id_integracao."'"); //Carrega os valores das configurações dinâmicas da integração de cada cliente
$valor = array(); //armazena todos os códigos de valores, cada código corresponde a uma configuração específica.
foreach($valores as $val){
    array_push($valor, (!empty($_POST[$val['codigo']])) ? $_POST[$val['codigo']] : array());
}

$id_proximo = (!empty((int) $_POST['id_proximo'])) ? (int) $_POST['id_proximo'] : 0;

$id_contrato = (!empty($_GET['id-contrato'])) ? $_GET['id-contrato'] : '';

//O nome de cada recurso
$nomes = (!empty($_POST['nome'])) ? $_POST['nome'] : array();

$reiniciar_conexao = (!empty($_POST['reiniciar_conexao'])) ? $_POST['reiniciar_conexao'] : 'desabilitado';
$diagnosticar_conexao = (!empty($_POST['diagnosticar_conexao'])) ? $_POST['diagnosticar_conexao'] : 'desabilitado';
$desbloquear_contrato = (!empty($_POST['desbloquear_contrato'])) ? $_POST['desbloquear_contrato'] : 'desabilitado';
$enviar_boleto = (!empty($_POST['enviar_boleto'])) ? $_POST['enviar_boleto'] : 'desabilitado';
$acesso_login = (!empty($_POST['acesso_login'])) ? $_POST['acesso_login'] : 'desabilitado';
$integracao_monitoramento = (!empty($_POST['integracao_monitoramento'])) ? $_POST['integracao_monitoramento'] : 'desabilitado';
$zerar_mac = (!empty($_POST['zerar_mac'])) ? $_POST['zerar_mac'] : 'desabilitado';
$sinal_rx = (!empty($_POST['sinal_rx'])) ? $_POST['sinal_rx'] : 'desabilitado';
$senha_wifi = (!empty($_POST['senha_wifi'])) ? $_POST['senha_wifi'] : 'desabilitado';
$desbloquear_vel_reduzida = (!empty($_POST['desbloquear_vel_reduzida'])) ? $_POST['desbloquear_vel_reduzida'] : 'desabilitado';

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//Campos do sistema de gestão requeridos na finalização de um atendimento mesmo sem a obrigatoriedade pelo sistema de gestão
$campos = (!empty($_POST['campo'])) ? $_POST['campo'] : array();
$tecnico_responsavel = (!empty($_POST['tecnico_responsavel'])) ? $_POST['tecnico_responsavel'] : 'nao';
$campo_processo = (!empty($_POST['campo_processo'])) ? $_POST['campo_processo'] : 'nao';
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//Campos para valores default
$id_integracao_campos_default = (!empty($_POST['id_integracao_campos_default'])) ? $_POST['id_integracao_campos_default'] : '';
$value_default = (!empty($_POST['value_default'])) ? $_POST['value_default'] : '';
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

if (!empty($_POST['inserir'])) {

    habilitarRecurso($nomes, $ativo, $rota, 'inserir', $reiniciar_conexao, $diagnosticar_conexao, $desbloquear_contrato, $enviar_boleto, $acesso_login, $id_integracao, $zerar_mac, $sinal_rx, $senha_wifi, $desbloquear_vel_reduzida, $integracao_monitoramento, $id_contrato_plano_pessoa);

    camposRequeridos($campos, 'inserir', $tecnico_responsavel, $campo_processo, $id_integracao, $id_contrato_plano_pessoa);

    $id = (int)$_POST['inserir'];

    $cont = 0;
    $dados_parametros = array();
    foreach($id_integracao_parametro as $conteudo){
        $dados_parametros[$cont]['id_integracao_parametro'] = $conteudo;
        $cont++;
    }

    $cont = 0;
    foreach($valor as $conteudo){
        $dados_parametros[$cont]['valor'] = $conteudo;
        $cont++;
    }

    $cont = 0;
    $dados_default = array();
    foreach ($id_integracao_campos_default as $conteudo) {
        $dados_default[$cont]['id_integracao_campos_default'] = $conteudo;
        $cont++;
    }
    $cont = 0;
    foreach ($value_default as $conteudo) {
        $dados_default[$cont]['value_default'] = $conteudo;
        $cont++;
    }

    inserir($host, $id_integracao, $usuario_integrado, $id_contrato_plano_pessoa, $dados_parametros, $dados_default);

} else if(!empty($_POST['alterar'])) {

    $integracao_recursos = DBRead('', 'tb_integracao_recursos', "WHERE id_contrato_plano_pessoa = '$id_contrato_plano_pessoa'");

    if($integracao_recursos){
        habilitarRecurso($nomes, $ativo, $rota, 'alterar', $reiniciar_conexao, $diagnosticar_conexao, $desbloquear_contrato, $enviar_boleto, $acesso_login, $id_integracao, $zerar_mac, $sinal_rx, $senha_wifi, $desbloquear_vel_reduzida, $integracao_monitoramento, $id_contrato_plano_pessoa);
    }else{
        habilitarRecurso($nomes, $ativo, $rota, 'inserir', $reiniciar_conexao, $diagnosticar_conexao, $desbloquear_contrato, $enviar_boleto, $acesso_login, $id_integracao, $zerar_mac, $sinal_rx, $senha_wifi, $desbloquear_vel_reduzida, $integracao_monitoramento, $id_contrato_plano_pessoa);
    }

    $campos_requeridos = DBRead('', 'tb_integracao_campos_requeridos', "WHERE id_integracao = '$id_integracao' AND id_contrato_plano_pessoa = '$id_contrato_plano_pessoa'");
    if($campos_requeridos){
        camposRequeridos($campos, 'alterar', $tecnico_responsavel, $campo_processo, $id_integracao, $id_contrato_plano_pessoa);
    }else{
        camposRequeridos($campos, 'inserir', $tecnico_responsavel, $campo_processo, $id_integracao, $id_contrato_plano_pessoa);
    }

    $id = (int)$_POST['alterar'];

    habilitarRecurso($nomes, $ativo, $rota, 'alterar', $reiniciar_conexao, $diagnosticar_conexao, $desbloquear_contrato, $enviar_boleto, $acesso_login, $id_integracao, $zerar_mac, $sinal_rx, $senha_wifi, $desbloquear_vel_reduzida, $integracao_monitoramento, $id_contrato_plano_pessoa);
    //camposRequeridos($campos, 'alterar', $tecnico_responsavel, $campo_processo, $id_integracao, $id_contrato_plano_pessoa);

    //////////////////////////////////////////////////
    $cont = 0;
    $dados_parametros = array();
    foreach ($id_integracao_parametro as $conteudo) {
        $dados_parametros[$cont]['id_integracao_parametro'] = $conteudo;
        $cont++;
    }
    $cont = 0;
    foreach ($valor as $conteudo) {
        $dados_parametros[$cont]['valor'] = $conteudo;
        $cont++;
    }
    //////////////////////////////////////////////////
    $cont = 0;
    $dados_default = array();
    foreach ($id_integracao_campos_default as $conteudo) {
        $dados_default[$cont]['id_integracao_campos_default'] = $conteudo;
        $cont++;
    }
    $cont = 0;
    foreach ($value_default as $conteudo) {
        $dados_default[$cont]['value_default'] = $conteudo;
        $cont++;
    }
    alterar($id, $host, $id_integracao, $usuario_integrado, $id_contrato_plano_pessoa, $dados_parametros, $dados_default);
    
} else if (isset($_GET['excluir'])) {

    $id = (int)$_GET['excluir'];

    $id_contrato_plano_pessoa = (int)$_GET['id_contrato_plano_pessoa'];
    //habilitarRecurso('', '', '', 'excluir', '', '', '', '', '', '', '', '', $id_contrato);
    habilitarRecurso('', '', '', 'excluir', '', '', '', '', '', '', '', '', '', '', '', $id_contrato_plano_pessoa);

    excluir($id, $exclui_ativacao, $id_contrato, $id_contrato_plano_pessoa);
} else {
    header("location: ../adm.php");
    exit;
}

function inserir($host, $id_integracao, $usuario_integrado, $id_contrato_plano_pessoa, $dados_parametros, $dados_default){

    $dados = array(
        'host' => $host,
        'usuario_integrado' => $usuario_integrado,
        'id_integracao' => $id_integracao,
        'id_contrato_plano_pessoa' => $id_contrato_plano_pessoa
    );

    $insertID = DBCreate('', 'tb_integracao_contrato', $dados, true);
    registraLog('Inserção de nova integração.','i','tb_integracao_contrato',$insertID,"host: $host | usuario_integrado: $usuario_integrado | id_integracao: $id_integracao | id_contrato_plano_pessoa: $id_contrato_plano_pessoa");

    $sistema = DBRead('', 'tb_integracao', "WHERE id_integracao = $id_integracao");

    if ($usuario_integrado != '') {
        $usuarios = new Integracao\Ixc\Usuarios();
        $usuarios = $usuarios->get('usuarios.id', '', true, $id_contrato_plano_pessoa);
        $usuario_historico = $usuarios['registros'][$usuario_integrado];
    }
    
    //QUADRO INFORMATIVO HISTORICO
    inserirHistorico($id_contrato_plano_pessoa, 1, "Inserção de nova integração", "host: $host | sistema de integração: '".$sistema[0]['nome']."' | usuário integrado: $usuario_historico |", 6);

    foreach($dados_parametros as $conteudo){
        if($conteudo['valor']){

            $id_integracao_parametro = $conteudo['id_integracao_parametro'];
            $valor = $conteudo['valor'];

            $integracao_parametros = DBRead('', 'tb_integracao_parametro', "WHERE id_integracao = '$id_integracao' AND id_integracao_parametro = $id_integracao_parametro");

            $dadosParametros = array(
                'id_integracao_parametro' => $id_integracao_parametro,
                'valor' => $valor,
                'id_integracao_contrato' => $insertID
            );
            
            $insertParametro = DBCreate('', 'tb_integracao_contrato_parametro', $dadosParametros);
            registraLog('Inserção de novos parâmetros de integração.','i','tb_integracao_contrato_parametro',$insertParametro,"id_integracao_contrato_parametro: $id_integracao_parametro | valor: $valor | id_integracao_contrato: $insertID");

            // QUADRO INFORMATIVO HISTORICO
            inserirHistorico($id_contrato_plano_pessoa, 1, "Inserção de novos parâmetros de integração", "parametro: '".$integracao_parametros[0]['nome']."' | valor: $valor", 6);
        }
    }

    //Bloco que salva dados obrigatórios para a finalização de atendimentos com integração
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    if($id_integracao == 1){
        adicionaCamposObrigatoriosIxc($id_contrato_plano_pessoa);
    }

    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////  
    //Fim do bloco

    $alert = ('Item inserido com sucesso!', 's');
    header("location: /api/iframe?token=$request->token&view=integracao-busca");
    exit;
}

function alterar($id, $host, $id_integracao, $usuario_integrado, $id_contrato_plano_pessoa, $dados_parametros, $dados_default){

    foreach($dados_default as $conteudo){

        $id_integracao_campos_default = $conteudo['id_integracao_campos_default'];
        $value_default = $conteudo['value_default'];

        $dadosDefault = array(
            'value_default' => $value_default,
            'descricao_campo' => '',
            'id_contrato_plano_pessoa' => $id_contrato_plano_pessoa,
            'id_integracao_campos_default' => $id_integracao_campos_default
        );

        $insertDefault = DBCreate('', 'tb_integracao_valores_default', $dadosDefault);
        registraLog('Inserção de valores default.','a','tb_integracao_valores_default',$insertDefault,"");
    }

    $dados = array(
        'host' => $host,
        'usuario_integrado' => $usuario_integrado,
        'id_integracao' => $id_integracao,
        'id_contrato_plano_pessoa' => $id_contrato_plano_pessoa
    );

    DBUpdate('', 'tb_integracao_contrato', $dados, "id_integracao_contrato = $id", true);
    registraLog('Alteração de integração.','a','tb_integracao_contrato', $id_integracao,"host: $host | usuario_integrado: $usuario_integrado | id_integracao: $id_integracao | id_contrato_plano_pessoa: $id_contrato_plano_pessoa");

    $sistema = DBRead('', 'tb_integracao', "WHERE id_integracao = $id_integracao");

    if ($usuario_integrado != '') {
        $usuarios = new Integracao\Ixc\Usuarios();
        $usuarios = $usuarios->get('usuarios.id', '', true, $id_contrato_plano_pessoa);
        $usuario_historico = $usuarios['registros'][$usuario_integrado];
    }

    //QUADRO INFORMATIVO HISTORICO
    inserirHistorico($id_contrato_plano_pessoa, 2, "Alteração de integração", "host: $host | sistema de integração: '".$sistema[0]['nome']."' | usuário integrado: $usuario_historico |", 6);

    if ($usuario_integrado != '') {
        include_once "integracoes/ixc/Usuarios.php";
        $usuarios = new Integracao\Ixc\Usuarios();
        $usuarios = $usuarios->get('usuarios.id', '', true, $id_contrato);
        $usuario_historico = $usuarios['registros'][$usuario_integrado];
    }
    
    //DBDelete('', 'tb_integracao_contrato_parametro', "id_integracao_contrato =  $id");

    foreach($dados_parametros as $conteudo){

        if($conteudo['valor']){

            $id_integracao_parametro = $conteudo['id_integracao_parametro'];
            $valor = $conteudo['valor'];

            $integracao_parametros = DBRead('', 'tb_integracao_parametro', "WHERE id_integracao = '$id_integracao' AND id_integracao_parametro = $id_integracao_parametro");

            $dadosIntegracao = array(
                'id_integracao_parametro' => $conteudo['id_integracao_parametro'],
                'valor' => $valor,
                'id_integracao_contrato' => $id
            );

            $insertIntegracao = DBCreate('', 'tb_integracao_contrato_parametro', $dadosIntegracao);
            registraLog('Alteração de parametros de integração.','a','tb_integracao_contrato_parametro',$insertIntegracao,"id_integracao_contrato_parametro: $id_integracao_parametro | valor: $valor | id_integracao_contrato: $id");

            // QUADRO INFORMATIVO HISTORICO
            inserirHistorico($id_contrato_plano_pessoa, 2, "Alteração de parâmetros de integração", "parametro: '".$integracao_parametros[0]['nome']."' | valor: $valor", 6);
        }
    }

    //Bloco que salva dados obrigatórios para a finalização de atendimentos com integração
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    if($id_integracao == 1){
        DBDelete('', 'tb_dados_obrigatorios_integracao', "id_contrato_plano_pessoa = '$id_contrato_plano_pessoa'");
        adicionaCamposObrigatoriosIxc($id_contrato_plano_pessoa);
    }

    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// 
    //Fim do bloco

    $alert = ('Item inserido com sucesso!', 's');
    header("location: /api/iframe?token=$request->token&view=integracao-busca");
    exit;
}

function excluir($id, $exclui_ativacao, $id_contrato, $id_contrato_plano_pessoa){

    $query = "DELETE FROM tb_integracao_contrato WHERE id_integracao_contrato = $id";
    $link = DBConnect('');
    $result = @mysqli_query($link, $query);
    DBClose($link);

    registraLog('Exclusão de integracao.', 'e', 'tb_integracao_contrato', $id, '');

    if(!$result){
        $alert = ('Erro ao excluir item!', 'd');
    }else{
        $alert = ('Item excluído com sucesso!', 's');
    }
    
    DBDelete('', 'tb_dados_obrigatorios_integracao', "id_contrato_plano_pessoa = '$id_contrato_plano_pessoa'");
    registraLog('Exclusão de dados obrigatórios integracao.', 'e', 'tb_integracao_contrato', $id, '');

    // QUADRO INFORMATIVO HISTORICO
    inserirHistorico($id_contrato_plano_pessoa, 3, "Exclusão de dados da integração", "Excluiu dados", 6);

    header("location: /api/iframe?token=$request->token&view=integracao-busca");
    
    exit;
    
}

function adicionaCamposObrigatoriosIxc($id_contrato_plano_pessoa){

    require_once "integracoes/ixc/Assunto.php";
    require_once "integracoes/ixc/DepartamentoAtendimento.php";
    require_once "integracoes/ixc/Filial.php";
    require_once "integracoes/ixc/Setor.php";
    require_once "integracoes/ixc/ContratoCliente.php";
    require_once "integracoes/ixc/Funcionarios.php";
    require_once "integracoes/ixc/Login.php";

    $id_integracao = 1;

    $assunto = new Integracao\Ixc\Assunto();
    $retorno_assunto = $assunto->get('su_oss_assunto.id', '', true, $id_contrato_plano_pessoa);

    if ($retorno_assunto) {
        foreach ($retorno_assunto['registros'] as $retorno) {
            $dados = array(
                "chave" => "assunto",
                "valor_id" => $retorno['id'],
                "valor_descricao" => $retorno['assunto'],
                "id_integracao" => $id_integracao,
                'id_contrato_plano_pessoa' => $id_contrato_plano_pessoa
            );
            DBCreate('', 'tb_dados_obrigatorios_integracao', $dados, false);
        }
    }

    $departamento = new Integracao\Ixc\DepartamentoAtendimento();
    $retorno_departamento = $departamento->get('su_ticket_setor.id', '', true, $id_contrato_plano_pessoa);

    if ($retorno_departamento) {
        foreach ($retorno_departamento['registros'] as $retorno) {
            $dados = array(
                "chave" => "departamento",
                "valor_id" => $retorno['id'],
                "valor_descricao" => $retorno['setor'],
                "id_integracao" => $id_integracao,
                'id_contrato_plano_pessoa' => $id_contrato_plano_pessoa
            );
            DBCreate('', 'tb_dados_obrigatorios_integracao', $dados, false);
        }
    }

    $filial = new Integracao\Ixc\Filial();
    $retorno_filial = $filial->get('filial.id', '', '=', true, $id_contrato_plano_pessoa);

    if ($retorno_filial) {
        foreach ($retorno_filial['registros'] as $retorno) {
            $dados = array(
                "chave" => "filial",
                "valor_id" => $retorno['id'],
                "valor_descricao" => $retorno['razao'],
                "id_integracao" => $id_integracao,
                'id_contrato_plano_pessoa' => $id_contrato_plano_pessoa
            );

            DBCreate('', 'tb_dados_obrigatorios_integracao', $dados, false);
        }
    }

    $setor = new Integracao\Ixc\Setor();
    $retorno_setor = $setor->get('empresa_setor.id', '', '=', true, $id_contrato_plano_pessoa);

    if ($retorno_setor) {
        foreach ($retorno_setor['registros'] as $retorno) {
            $dados = array(
                "chave" => "setor",
                "valor_id" => $retorno['id'],
                "valor_descricao" => $retorno['setor'],
                "id_integracao" => $id_integracao,
                'id_contrato_plano_pessoa' => $id_contrato_plano_pessoa
            );
            DBCreate('', 'tb_dados_obrigatorios_integracao', $dados, false);
        }
    }

    $tecnico = new Integracao\Ixc\Funcionarios();
    $retorno_tecnico = $tecnico->get('funcionarios.id', '', true, $id_contrato_plano_pessoa);

    if ($retorno_tecnico) {

        $retorno_tecnico = unique_multidim_array($retorno_tecnico['registros'], 'usuario');

        foreach ($retorno_tecnico as $retorno) {

            //$dados_obrigatorios = DBRead('', 'tb_dados_obrigatorios_integracao', "WHERE id_integracao = 1 AND id_contrato_plano_pessoa = '$id_contrato_plano_pessoa' AND chave = 'funcionario'");

            //if($dados_obrigatorios){
                //foreach($dados_obrigatorios as $conteudo){
                    //if($dados_obrigatorios['valor_id'] != $retorno['usuario']){
                        $dados = array(
                            "chave" => "funcionario",
                            "valor_id" => $retorno['usuario'],
                            "valor_descricao" => $retorno['funcionario'],
                            "id_integracao" => $id_integracao,
                            'id_contrato_plano_pessoa' => $id_contrato_plano_pessoa
                        );
                        DBCreate('', 'tb_dados_obrigatorios_integracao', $dados, false);

                   // }
                //}
            //}
        }
    }
}

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function habilitarRecurso($nomes, $ativo, $rota, $operacao, $reiniciar_conexao, $diagnosticar_conexao, $desbloquear_contrato, $enviar_boleto, $acesso_login, $id_integracao, $zerar_mac, $sinal_rx, $senha_wifi, $desbloquear_vel_reduzida, $integracao_monitoramento, $id_contrato_plano_pessoa){

    if($operacao == 'inserir'){
        foreach($nomes as $key => $nome){

            if($nomes[$key] == "reiniciar_conexao"){
                $nome = $nomes[$key];
                $ativo = $reiniciar_conexao == 'habilitado' ? '1' : '0';
                $dados_reiniciar = array(
                    'nome' => $nome,
                    'ativo' => $ativo,
                    'rota' => '',
                    'id_contrato_plano_pessoa' => $id_contrato_plano_pessoa,
                    'id_integracao' => $id_integracao
                );
                
                $insertParametro = DBCreate('', 'tb_integracao_recursos', $dados_reiniciar);
                registraLog('Inserção de novos integracao recursos.','i','tb_integracao_recursos',$dados_reiniciar,"nome: $nome | ativo: $reiniciar_conexao | rota:  | id_contrato_plano_pessoa: $id_contrato_plano_pessoa | id_integracao: $insertParametro");

                // QUADRO INFORMATIVO HISTORICO
                inserirHistorico($id_contrato_plano_pessoa, 1, "Inserção de novos recursos na integração", "$nome: $reiniciar_conexao", 6);
            }

            if($nomes[$key] == "diagnosticar_conexao"){
                $nome = $nomes[$key];
                $ativo = $diagnosticar_conexao == 'habilitado' ? '1' : '0';
                $dados_diagnosticar = array(
                    'nome' => $nome,
                    'ativo' => $ativo,
                    'rota' => '',
                    'id_contrato_plano_pessoa' => $id_contrato_plano_pessoa,
                    'id_integracao' => $id_integracao
                );
                $insertParametro = DBCreate('', 'tb_integracao_recursos', $dados_diagnosticar);
                registraLog('Inserção de novos integracao recursos.','i','tb_integracao_recursos',$dados_diagnosticar,"nome: $nome | ativo: $diagnosticar_conexao | rota:  | id_contrato_plano_pessoa: $id_contrato_plano_pessoa | id_integracao: $insertParametro");

                // QUADRO INFORMATIVO HISTORICO
                inserirHistorico($id_contrato_plano_pessoa, 1, "Inserção de novos recursos na integração", "$nome: $diagnosticar_conexao", 6);
            }

            if($nomes[$key] == "desbloquear_contrato"){
                $nome = $nomes[$key];
                $ativo = $desbloquear_contrato == 'habilitado' ? '1' : '0';
                $dados_desbloquear = array(
                    'nome' => $nome,
                    'ativo' => $ativo,
                    'rota' => '',
                    'id_contrato_plano_pessoa' => $id_contrato_plano_pessoa,
                    'id_integracao' => $id_integracao
                );
                $insertParametro = DBCreate('', 'tb_integracao_recursos', $dados_desbloquear);
                registraLog('Inserção de novos integracao recursos.','i','tb_integracao_recursos',$dados_desbloquear,"nome: $nome | ativo: $desbloquear_contrato | rota:  | id_contrato_plano_pessoa: $id_contrato_plano_pessoa | id_integracao: $insertParametro");

                // QUADRO INFORMATIVO HISTORICO
                inserirHistorico($id_contrato_plano_pessoa, 1, "Inserção de novos recursos na integração", "$nome: $desbloquear_contrato", 6);
            }

            if($nomes[$key] == "enviar_boleto"){
                $nome = $nomes[$key];
                $ativo = $enviar_boleto == 'habilitado' ? '1' : '0';
                $dados_boleto = array(
                    'nome' => $nome,
                    'ativo' => $ativo,
                    'rota' => '',
                    'id_contrato_plano_pessoa' => $id_contrato_plano_pessoa,
                    'id_integracao' => $id_integracao
                );
                $insertParametro = DBCreate('', 'tb_integracao_recursos', $dados_boleto);
                registraLog('Inserção de novos integracao recursos.','i','tb_integracao_recursos',$dados_boleto,"nome: $nome | ativo: $enviar_boleto | rota:  | id_contrato_plano_pessoa: $id_contrato_plano_pessoa | id_integracao: $insertParametro");

                // QUADRO INFORMATIVO HISTORICO
                inserirHistorico($id_contrato_plano_pessoa, 1, "Inserção de novos recursos na integração", "$nome: $enviar_boleto", 6);
            }

            if($nomes[$key] == "acesso_login"){
                $nome = $nomes[$key];
                $ativo = $acesso_login == 'habilitado' ? '1' : '0';
                $dados_login = array(
                    'nome' => $nome,
                    'ativo' => $ativo,
                    'rota' => '',
                    'id_contrato_plano_pessoa' => $id_contrato_plano_pessoa,
                    'id_integracao' => $id_integracao
                );
                $insertParametro = DBCreate('', 'tb_integracao_recursos', $dados_login);
                registraLog('Inserção de novos integracao recursos.','i','tb_integracao_recursos',$dados_login,"nome: $nome | ativo: $acesso_login | rota:  | id_contrato_plano_pessoa: $id_contrato_plano_pessoa | id_integracao: $insertParametro");

                // QUADRO INFORMATIVO HISTORICO
                inserirHistorico($id_contrato_plano_pessoa, 1, "Inserção de novos recursos na integração", "$nome: $acesso_login", 6);
            }

            if($nomes[$key] == "integracao_monitoramento"){
                $nome = $nomes[$key];
                $ativo = $integracao_monitoramento == 'habilitado' ? '1' : '0';
                $dados_monitoramento = array(
                    'nome' => $nome,
                    'ativo' => $ativo,
                    'rota' => '',
                    'id_contrato_plano_pessoa' => $id_contrato_plano_pessoa,
                    'id_integracao' => $id_integracao
                );
                $insertParametro = DBCreate('', 'tb_integracao_recursos', $dados_monitoramento);
                registraLog('Inserção de novos integracao recursos.','i','tb_integracao_recursos',$dados_monitoramento,"nome: $nome | ativo: $integracao_monitoramento | rota:  | id_contrato_plano_pessoa: $id_contrato_plano_pessoa | id_integracao: $insertParametro");

                // QUADRO INFORMATIVO HISTORICO
                inserirHistorico($id_contrato_plano_pessoa, 1, "Inserção de novos recursos na integração", "$nome: $integracao_monitoramento", 6);
            }

            if($nomes[$key] == "zerar_mac"){
                $nome = $nomes[$key];
                $ativo = $zerar_mac == 'habilitado' ? '1' : '0';
                $dados_zerar = array(
                    'nome' => $nome,
                    'ativo' => $ativo,
                    'rota' => '',
                    'id_contrato_plano_pessoa' => $id_contrato_plano_pessoa,
                    'id_integracao' => $id_integracao
                );
                $insertParametro = DBCreate('', 'tb_integracao_recursos', $dados_zerar);
                registraLog('Inserção de novos integracao recursos.','i','tb_integracao_recursos',$dados_zerar,"nome: $nome | ativo: $zerar_mac | rota:  | id_contrato_plano_pessoa: $id_contrato_plano_pessoa | id_integracao: $insertParametro");

                // QUADRO INFORMATIVO HISTORICO
                inserirHistorico($id_contrato_plano_pessoa, 1, "Inserção de novos recursos na integração", "$nome: $zerar_mac", 6);
            }

            if($nomes[$key] == "sinal_rx"){
                $nome = $nomes[$key];
                $ativo = $sinal_rx == 'habilitado' ? '1' : '0';
                $dados_sinal_rx = array(
                    'nome' => $nome,
                    'ativo' => $ativo,
                    'rota' => '',
                    'id_contrato_plano_pessoa' => $id_contrato_plano_pessoa,
                    'id_integracao' => $id_integracao
                );
                $insertParametro = DBCreate('', 'tb_integracao_recursos', $dados_sinal_rx);
                registraLog('Inserção de novos integracao recursos.','i','tb_integracao_recursos',$dados_sinal_rx,"nome: $nome | ativo: $sinal_rx | rota:  | id_contrato_plano_pessoa: $id_contrato_plano_pessoa | id_integracao: $insertParametro");

                // QUADRO INFORMATIVO HISTORICO
                inserirHistorico($id_contrato_plano_pessoa, 1, "Inserção de novos recursos na integração", "$nome: $sinal_rx", 6);
            }

            if($nomes[$key] == "senha_wifi"){
                $nome = $nomes[$key];
                $ativo = $senha_wifi == 'habilitado' ? '1' : '0';
                $dados_senha_wifi = array(
                    'nome' => $nome,
                    'ativo' => $ativo,
                    'rota' => '',
                    'id_contrato_plano_pessoa' => $id_contrato_plano_pessoa,
                    'id_integracao' => $id_integracao
                );
                $insertParametro = DBCreate('', 'tb_integracao_recursos', $dados_senha_wifi);
                registraLog('Inserção de novos integracao recursos.','i','tb_integracao_recursos',$dados_senha_wifi,"nome: $nome | ativo: $senha_wifi | rota:  | id_contrato_plano_pessoa: $id_contrato_plano_pessoa | id_integracao: $insertParametro");

                // QUADRO INFORMATIVO HISTORICO
                inserirHistorico($id_contrato_plano_pessoa, 1, "Inserção de novos recursos na integração", "$nome: $senha_wifi", 6);
                
            }
            if($nomes[$key] == "desbloquear_vel_reduzida"){

                $nome = $nomes[$key];
                $ativo = $desbloquear_vel_reduzida == 'habilitado' ? '1' : '0';
                $dados_desbloquear_vel_reduzida = array(
                    'nome' => $nome,
                    'ativo' => $ativo,
                    'rota' => '',
                    'id_contrato_plano_pessoa' => $id_contrato_plano_pessoa,
                    'id_integracao' => $id_integracao
                );
                $insertParametro = DBCreate('', 'tb_integracao_recursos', $dados_desbloquear_vel_reduzida);
                registraLog('Inserção de novos integracao recursos.','i','tb_integracao_recursos',$dados_desbloquear_vel_reduzida,"nome: $nome | ativo: $desbloquear_vel_reduzida | rota:  | id_contrato_plano_pessoa: $id_contrato_plano_pessoa | id_integracao: $insertParametro");

                // QUADRO INFORMATIVO HISTORICO
                inserirHistorico($id_contrato_plano_pessoa, 1, "Inserção de novos recursos na integração", "$nome: $desbloquear_vel_reduzida", 6);
            }
        }
    }else if($operacao == 'alterar'){

        foreach($nomes as $key => $nome){

            if($nomes[$key] == "reiniciar_conexao"){
                $nome = $nomes[$key];
                $ativo = $reiniciar_conexao == 'habilitado' ? '1' : '0';
                $dados_reiniciar = array(
                    'nome' => $nome,
                    'ativo' => $ativo,
                    'rota' => '',
                    'id_contrato_plano_pessoa' => $id_contrato_plano_pessoa,
                    'id_integracao' => $id_integracao
                );
                DBUpdate('', 'tb_integracao_recursos', $dados_reiniciar, "id_contrato_plano_pessoa = '$id_contrato_plano_pessoa' AND nome = '$nome'", true);
                registraLog('Alteração integracao recursos.','a','tb_integracao_recursos',$id_contrato_plano_pessoa,"nome: $nome | ativo: $ativo | rota:  | id_contrato_plano_pessoa: $id_contrato_plano_pessoa | id_integracao: $insertParametro");

                // QUADRO INFORMATIVO HISTORICO
                inserirHistorico($id_contrato_plano_pessoa, 2, "Alteração de recursos na integração", "$nome: $reiniciar_conexao", 6);
            }

            if($nomes[$key] == "diagnosticar_conexao"){
                $nome = $nomes[$key];
                $ativo = $diagnosticar_conexao == 'habilitado' ? '1' : '0';
                $dados_diagnosticar = array(
                    'nome' => $nome,
                    'ativo' => $ativo,
                    'rota' => '',
                    'id_contrato_plano_pessoa' => $id_contrato_plano_pessoa,
                    'id_integracao' => $id_integracao
                );
                DBUpdate('', 'tb_integracao_recursos', $dados_diagnosticar, "id_contrato_plano_pessoa = '$id_contrato_plano_pessoa' AND nome = '$nome'", true);
                registraLog('Alteração integracao recursos.','a','tb_integracao_recursos',$id_contrato_plano_pessoa,"nome: $nome | ativo: $ativo | rota:  | id_contrato_plano_pessoa: $id_contrato_plano_pessoa | id_integracao: $insertParametro");

                // QUADRO INFORMATIVO HISTORICO
                inserirHistorico($id_contrato_plano_pessoa, 2, "Alteração de recursos na integração", "$nome: $diagnosticar_conexao", 6);
            }

            if($nomes[$key] == "desbloquear_contrato"){
                $nome = $nomes[$key];
                $ativo = $desbloquear_contrato == 'habilitado' ? '1' : '0';
                $dados_desbloquear = array(
                    'nome' => $nome,
                    'ativo' => $ativo,
                    'rota' => '',
                    'id_contrato_plano_pessoa' => $id_contrato_plano_pessoa,
                    'id_integracao' => $id_integracao
                );
                DBUpdate('', 'tb_integracao_recursos', $dados_desbloquear, "id_contrato_plano_pessoa = '$id_contrato_plano_pessoa' AND nome = '$nome'", true);
                registraLog('Alteração integracao recursos.','a','tb_integracao_recursos',$id_contrato_plano_pessoa,"nome: $nome | ativo: $ativo | rota:  | id_contrato_plano_pessoa: $id_contrato_plano_pessoa | id_integracao: $insertParametro");

                // QUADRO INFORMATIVO HISTORICO
                inserirHistorico($id_contrato_plano_pessoa, 2, "Alteração de recursos na integração", "$nome: $desbloquear_contrato", 6);
            }

            if($nomes[$key] == "enviar_boleto"){
                $nome = $nomes[$key];
                $ativo = $enviar_boleto == 'habilitado' ? '1' : '0';
                $dados_boleto = array(
                    'nome' => $nome,
                    'ativo' => $ativo,
                    'rota' => '',
                    'id_contrato_plano_pessoa' => $id_contrato_plano_pessoa,
                    'id_integracao' => $id_integracao
                );
                DBUpdate('', 'tb_integracao_recursos', $dados_boleto, "id_contrato_plano_pessoa = '$id_contrato_plano_pessoa' AND nome = '$nome'", true);
                registraLog('Alteração integracao recursos.','a','tb_integracao_recursos',$id_contrato_plano_pessoa,"nome: $nome | ativo: $ativo | rota:  | id_contrato_plano_pessoa: $id_contrato_plano_pessoa | id_integracao: $insertParametro");

                // QUADRO INFORMATIVO HISTORICO
                inserirHistorico($id_contrato_plano_pessoa, 2, "Alteração de recursos na integração", "$nome: $enviar_boleto", 6);
            }

            if($nomes[$key] == "acesso_login"){
                $nome = $nomes[$key];
                $ativo = $acesso_login == 'habilitado' ? '1' : '0';
                $dados_login = array(
                    'nome' => $nome,
                    'ativo' => $ativo,
                    'rota' => '',
                    'id_contrato_plano_pessoa' => $id_contrato_plano_pessoa,
                    'id_integracao' => $id_integracao
                );
                DBUpdate('', 'tb_integracao_recursos', $dados_login, "id_contrato_plano_pessoa = '$id_contrato_plano_pessoa' AND nome = '$nome'", true);
                registraLog('Alteração integracao recursos.','a','tb_integracao_recursos',$id_contrato_plano_pessoa,"nome: $nome | ativo: $ativo | rota:  | id_contrato_plano_pessoa: $id_contrato_plano_pessoa | id_integracao: $insertParametro"); 

                // QUADRO INFORMATIVO HISTORICO
                inserirHistorico($id_contrato_plano_pessoa, 2, "Alteração de recursos na integração", "$nome: $acesso_login", 6);
            }

            if($nomes[$key] == "integracao_monitoramento"){
                $nome = $nomes[$key];
                $ativo = $integracao_monitoramento == 'habilitado' ? '1' : '0';
                $dados_monitoramento = array(
                    'nome' => $nome,
                    'ativo' => $ativo,
                    'rota' => '',
                    'id_contrato_plano_pessoa' => $id_contrato_plano_pessoa,
                    'id_integracao' => $id_integracao
                );
                DBUpdate('', 'tb_integracao_recursos', $dados_monitoramento, "id_contrato_plano_pessoa = '$id_contrato_plano_pessoa' AND nome = '$nome'", true);
                registraLog('Alteração integracao recursos.','a','tb_integracao_recursos',$id_contrato_plano_pessoa,"nome: $nome | ativo: $ativo | rota:  | id_contrato_plano_pessoa: $id_contrato_plano_pessoa | id_integracao: $insertParametro"); 

                // QUADRO INFORMATIVO HISTORICO
                inserirHistorico($id_contrato_plano_pessoa, 2, "Alteração de recursos na integração", "$nome: $integracao_monitoramento", 6);
            }

            if($nomes[$key] == "zerar_mac"){
                $nome = $nomes[$key];
                $ativo = $zerar_mac == 'habilitado' ? '1' : '0';
                $dados_zerar = array(
                    'nome' => $nome,
                    'ativo' => $ativo,
                    'rota' => '',
                    'id_contrato_plano_pessoa' => $id_contrato_plano_pessoa,
                    'id_integracao' => $id_integracao
                );
                DBUpdate('', 'tb_integracao_recursos', $dados_zerar, "id_contrato_plano_pessoa = '$id_contrato_plano_pessoa' AND nome = '$nome'", true);
                registraLog('Alteração integracao recursos.','a','tb_integracao_recursos',$id_contrato_plano_pessoa,"nome: $nome | ativo: $ativo | rota:  | id_contrato_plano_pessoa: $id_contrato_plano_pessoa | id_integracao: $insertParametro"); 

                // QUADRO INFORMATIVO HISTORICO
                inserirHistorico($id_contrato_plano_pessoa, 2, "Alteração de recursos na integração", "$nome: $zerar_mac", 6);
            }

            if($nomes[$key] == "sinal_rx"){
                $nome = $nomes[$key];
                $ativo = $sinal_rx == 'habilitado' ? '1' : '0';
                $dados_sinal_rx = array(
                    'nome' => $nome,
                    'ativo' => $ativo,
                    'rota' => '',
                    'id_contrato_plano_pessoa' => $id_contrato_plano_pessoa,
                    'id_integracao' => $id_integracao
                );
                DBUpdate('', 'tb_integracao_recursos', $dados_sinal_rx, "id_contrato_plano_pessoa = '$id_contrato_plano_pessoa' AND nome = '$nome'", true);
                registraLog('Alteração integracao recursos.','a','tb_integracao_recursos',$id_contrato_plano_pessoa,"nome: $nome | ativo: $ativo | rota:  | id_contrato_plano_pessoa: $id_contrato_plano_pessoa | id_integracao: $insertParametro"); 

                // QUADRO INFORMATIVO HISTORICO
                inserirHistorico($id_contrato_plano_pessoa, 2, "Alteração de recursos na integração", "$nome: $sinal_rx", 6);
            }
            
            if($nomes[$key] == "senha_wifi"){
                $nome = $nomes[$key];
                $ativo = $senha_wifi == 'habilitado' ? '1' : '0';
                $dados_senha_wifi = array(
                    'nome' => $nome,
                    'ativo' => $ativo,
                    'rota' => '',
                    'id_contrato_plano_pessoa' => $id_contrato_plano_pessoa,
                    'id_integracao' => $id_integracao
                );
                DBUpdate('', 'tb_integracao_recursos', $dados_senha_wifi, "id_contrato_plano_pessoa = '$id_contrato_plano_pessoa' AND nome = '$nome'", true);
                registraLog('Alteração integracao recursos.','a','tb_integracao_recursos',$id_contrato_plano_pessoa,"nome: $nome | ativo: $ativo | rota:  | id_contrato_plano_pessoa: $id_contrato_plano_pessoa | id_integracao: $insertParametro"); 

                // QUADRO INFORMATIVO HISTORICO
                inserirHistorico($id_contrato_plano_pessoa, 2, "Alteração de recursos na integração", "$nome: $senha_wifi", 6);
            }

            if($nomes[$key] == "desbloquear_vel_reduzida"){

                $nome = $nomes[$key];
                $ativo = $desbloquear_vel_reduzida == 'habilitado' ? '1' : '0';
                $dados_desbloquear_vel_reduzida = array(
                    'nome' => $nome,
                    'ativo' => $ativo,
                    'rota' => '',
                    'id_contrato_plano_pessoa' => $id_contrato_plano_pessoa,
                    'id_integracao' => $id_integracao
                );

                $check = DBRead('', 'tb_integracao_recursos', "WHERE id_contrato_plano_pessoa = '$id_contrato_plano_pessoa' AND nome = '$nome'");

                if ($check) {
                    DBUpdate('', 'tb_integracao_recursos', $dados_desbloquear_vel_reduzida, "id_contrato_plano_pessoa = '$id_contrato_plano_pessoa' AND nome = '$nome'", true);
                    registraLog('Alteração integracao recursos.','a','tb_integracao_recursos',$id_contrato_plano_pessoa,"nome: $nome | ativo: $ativo | rota:  | id_contrato_plano_pessoa: $id_contrato_plano_pessoa | id_integracao: $insertParametro");

                    // QUADRO INFORMATIVO HISTORICO
                    inserirHistorico($id_contrato_plano_pessoa, 2, "Alteração de recursos na integração", "$nome: $desbloquear_vel_reduzida", 6);

                } else {
                    $insertParametro = DBCreate('', 'tb_integracao_recursos', $dados_desbloquear_vel_reduzida);
                    registraLog('Inserção de novos integracao recursos.','i','tb_integracao_recursos',$dados_desbloquear_vel_reduzida,"nome: $nome | ativo: $desbloquear_vel_reduzida | rota:  | id_contrato_plano_pessoa: $id_contrato_plano_pessoa | id_integracao: $insertParametro");

                    // QUADRO INFORMATIVO HISTORICO
                    inserirHistorico($id_contrato_plano_pessoa, 1, "inserção de recursos na integração", "$nome: $desbloquear_vel_reduzida", 6);
                }
            }
        }

    } else if($operacao == 'excluir') {

        $query = "DELETE FROM tb_integracao_recursos WHERE id_contrato_plano_pessoa = $id_contrato_plano_pessoa";
        $link = DBConnect('');
        $result = @mysqli_query($link, $query);
        DBClose($link);
        registraLog('Exclusão de integracao recursos.', 'e', 'tb_integracao_recursos', $id_contrato_plano_pessoa, '');

        // QUADRO INFORMATIVO HISTORICO
        inserirHistorico($id_contrato_plano_pessoa, 3, "Exclusão de recurso na integração", "Excluiu", 6);
    }
}

function camposRequeridos($campos, $operacao, $tecnico_responsavel, $campo_processo, $id_integracao, $id_contrato_plano_pessoa){

    if ($operacao == 'inserir') {
        foreach($campos as $key => $campo){
            if($campos[$key] == "tecnico_responsavel"){
                $nome = $campos[$key];
                $requerido = $tecnico_responsavel == 'sim' ? '1' : '0';
                $dados_tecnico = array(
                    'nome' => $nome,
                    'requerido' => $requerido,
                    'id_contrato_plano_pessoa' => $id_contrato_plano_pessoa,
                    'id_integracao' => $id_integracao
                );

                $insertParametro = DBCreate('', 'tb_integracao_campos_requeridos', $dados_tecnico);
                registraLog('Inserção de novos campos requeridos integracao.','i','tb_integracao_campos_requeridos',$dados_tecnico,"nome: $nome | requerido: $requerido | id_contrato_plano_pessoa: $id_contrato_plano_pessoa | id_integracao: $insertParametro");

                // QUADRO INFORMATIVO HISTORICO
                inserirHistorico($id_contrato_plano_pessoa, 1, "Inserção de novos campos requeridos integracao", "tecnico responsavel: $tecnico_responsavel", 6);
            }
            if($campos[$key] == "campo_processo"){
                $nome = $campos[$key];
                $requerido = $campo_processo == 'sim' ? '1' : '0';
                $dados_processo = array(
                    'nome' => $nome,
                    'requerido' => $requerido,
                    'id_contrato_plano_pessoa' => $id_contrato_plano_pessoa,
                    'id_integracao' => $id_integracao
                );
                $insertParametro = DBCreate('', 'tb_integracao_campos_requeridos', $dados_processo);
                registraLog('Inserção de novos campos requeridos integracao.','i','tb_integracao_campos_requeridos',$dados_processo,"nome: $nome | requerido: $requerido | id_contrato_plano_pessoa: $id_contrato_plano_pessoa | id_integracao: $insertParametro");

                // QUADRO INFORMATIVO HISTORICO
                inserirHistorico($id_contrato_plano_pessoa, 1, "Inserção de novos campos requeridos integracao", "processo: $campo_processo", 6);
            }
        }
    } else if($operacao == 'alterar') {

        //Verifica parametros já existentes
        $parametros = DBRead('', 'tb_integracao_campos_requeridos', "WHERE id_contrato_plano_pessoa = '$id_contrato_plano_pessoa'");

        $query = "DELETE FROM tb_integracao_campos_requeridos WHERE id_contrato_plano_pessoa = '$id_contrato_plano_pessoa'";
        $link = DBConnect('');
        $result = @mysqli_query($link, $query);
        DBClose($link);

        /*foreach($parametros as $key => $parametro){
            $nome = $parametros[$key]['nome'];
            $query = "DELETE FROM tb_integracao_campos_requeridos WHERE nome = '$nome' AND id_contrato_plano_pessoa = '$id_contrato_plano_pessoa'";
            $link = DBConnect('');
            $result = @mysqli_query($link, $query);
            DBClose($link);
        }*/

        foreach($campos as $key => $campo){
            if($campos[$key] == "tecnico_responsavel"){
                $nome = $campos[$key];
                $requerido = $tecnico_responsavel == 'sim' ? '1' : '0';
                $dados_tecnico = array(
                    'nome' => $nome,
                    'requerido' => $requerido,
                    'id_contrato_plano_pessoa' => $id_contrato_plano_pessoa,
                    'id_integracao' => $id_integracao
                );
                $insertParametro = DBCreate('', 'tb_integracao_campos_requeridos', $dados_tecnico);
                registraLog('Alteração de campos requeridos integracao.','i','tb_integracao_campos_requeridos',$dados_tecnico,"nome: $nome | requerido: $requerido | id_contrato_plano_pessoa: $id_contrato_plano_pessoa | id_integracao: $insertParametro");

                echo "$id_contrato_plano_pessoa, 1, 'Alteração de campos requeridos integracao', tecnico responsavel: $tecnico_responsavel, 6<br><br>";

                // QUADRO INFORMATIVO HISTORICO
                inserirHistorico($id_contrato_plano_pessoa, 2, "Alteração de campos requeridos integracao", "tecnico responsavel: $tecnico_responsavel", 6);
                
            }
            if($campos[$key] == "campo_processo"){
                $nome = $campos[$key];
                $requerido = $campo_processo == 'sim' ? '1' : '0';
                $dados_processo = array(
                    'nome' => $nome,
                    'requerido' => $requerido,
                    'id_contrato_plano_pessoa' => $id_contrato_plano_pessoa,
                    'id_integracao' => $id_integracao
                );
                $insertParametro = DBCreate('', 'tb_integracao_campos_requeridos', $dados_processo);
                registraLog('Alteração de campos requeridos integracao.','i','tb_integracao_campos_requeridos',$dados_processo,"nome: $nome | requerido: $requerido | id_contrato_plano_pessoa: $id_contrato_plano_pessoa | id_integracao: $insertParametro");

                // QUADRO INFORMATIVO HISTORICO
                inserirHistorico($id_contrato_plano_pessoa, 2, "Alteração de campos requeridos integracao", "processo: $campo_processo", 6);
            }
        }
        
        //die();

            //registraLog('Exclusão de integracao recursos.', 'e', 'tb_integracao_recursos', $id_contrato_plano_pessoa, '');
        //}

        /*foreach($campos as $key => $campo){
            if($campos[$key] == "tecnico_responsavel"){
                $nome = $campos[$key];
                $requerido = $tecnico_responsavel == 'sim' ? '1' : '0';
                $dados_tecnico = array(
                    'nome' => $nome,
                    'requerido' => $requerido,
                    'id_contrato_plano_pessoa' => $id_contrato_plano_pessoa,
                    'id_integracao' => $id_integracao
                );

                foreach($parametros as $parametro){
                    if($parametro['nome'] == "tecnico_responsavel"){
                        DBUpdate('', 'tb_integracao_campos_requeridos', $dados_tecnico, "id_contrato_plano_pessoa = '$id_contrato_plano_pessoa' AND nome = '$nome'", true);
                        registraLog('Alteração integracao campos requeridos.','a','tb_integracao_campos_requeridos',$id,"nome: $nome | requerido: $requerido | id_contrato_plano_pessoa: $id_contrato_plano_pessoa | id_integracao: $insertParametro");
                    }else{
                        $insertParametro = DBCreate('', 'tb_integracao_campos_requeridos', $dados_tecnico);
                        registraLog('Inserção de novos integracao campos requeridos.','i','tb_integracao_campos_requeridos',$dados_tecnico,"nome: $nome | requerido: $requerido | id_contrato_plano_pessoa: $id_contrato_plano_pessoa | id_integracao: $insertParametro");
                    }
                }
                
            }
            if($campos[$key] == "campo_processo"){
                $nome = $campos[$key];
                $requerido = $campo_processo == 'sim' ? '1' : '0';
                $dados_processo = array(
                    'nome' => $nome,
                    'requerido' => $requerido,
                    'id_contrato_plano_pessoa' => $id_contrato_plano_pessoa,
                    'id_integracao' => $id_integracao
                );

                foreach($parametros as $parametro){

                    if($parametro['nome'] == "campo_processo"){
                        DBUpdate('', 'tb_integracao_campos_requeridos', $dados_processo, "id_contrato_plano_pessoa = '$id_contrato_plano_pessoa' AND nome = '$nome'", true);
                        registraLog('Alteração integracao campos requeridos.','a','tb_integracao_campos_requeridos',$id,"nome: $nome | requerido: $requerido | id_contrato_plano_pessoa: $id_contrato_plano_pessoa | id_integracao: $insertParametro");
                    }else{
                        $insertParametro = DBCreate('', 'tb_integracao_campos_requeridos', $dados_processo);
                        registraLog('Inserção de novos integracao campos requeridos.','i','tb_integracao_campos_requeridos',$dados_processo,"nome: $nome | requerido: $requerido | id_contrato_plano_pessoa: $id_contrato_plano_pessoa | id_integracao: $insertParametro");
                    }
                }
            }
        }*/
    }
}

?>