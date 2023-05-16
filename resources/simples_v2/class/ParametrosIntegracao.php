<?php
require_once(__DIR__."/System.php");


$id_integracao = (!empty($_POST['id_integracao'])) ? $_POST['id_integracao'] : '';
$codigo = (!empty($_POST['codigo'])) ? $_POST['codigo'] : '';
$nome = (!empty($_POST['nome'])) ? $_POST['nome'] : '';
$tipo = (!empty($_POST['tipo'])) ? $_POST['tipo'] : '';
$obrigatorio = (!empty($_POST['obrigatorio'])) ? $_POST['obrigatorio'] : '';

$titulo = (!empty($_POST['titulo'])) ? $_POST['titulo'] : array();

$valor = str_replace(" ", "_", removeAcentos($titulo));

$caracteres_especiais = array(".", ",");
$valor = str_replace($caracteres_especiais, "", removeAcentos($titulo));

if(!empty($_POST['inserir'])){

    $cont = 0;
    $dados_valor = array();
    foreach($titulo as $conteudo){
        $dados_valor[$cont]['titulo'] = $conteudo;
        $cont++;
    }

    $cont = 0;
    foreach($valor as $conteudo){
        $dados_valor[$cont]['valor'] = $conteudo;
        $cont++;
    }

    inserir($codigo, $nome, $tipo, $obrigatorio, $id_integracao, $dados_valor);

}else if(!empty($_POST['alterar'])){
    $id = (int)$_POST['alterar'];

    $cont = 0;
    $dados_valor = array();
    foreach($titulo as $conteudo){
        $dados_valor[$cont]['titulo'] = $conteudo;
        $cont++;
    }

    $cont = 0;
    foreach($valor as $conteudo){
        $dados_valor[$cont]['valor'] = $conteudo;
        $cont++;
    }

    alterar($id, $codigo, $nome, $tipo, $obrigatorio, $id_integracao, $dados_valor);
    
}else{
    header("location: ../adm.php");
    exit;
}

function inserir($codigo, $nome, $tipo, $obrigatorio, $id_integracao, $dados_valor){

    $dados = array(
        'codigo' => $codigo,
        'nome' => $nome,
        'tipo' => $tipo,
        'obrigatorio' => $obrigatorio,
        'id_integracao' => $id_integracao
    );

    if($dados){

        $insertID = DBCreate('', 'tb_integracao_parametro', $dados, true);
        registraLog('Inserção de novo campo de parâmetro de integração.','i','tb_integracao_parametro',$insertID,"codigo: $codigo | nome: $nome | tipo: $tipo | obrigatorio: $obrigatorio | id_integracao: $id_integracao");

        foreach($dados_valor as $conteudo){

            $titulo = $conteudo['titulo'];
            $valor = $conteudo['valor'];

            if($conteudo['valor'] && $id_integracao){
                $dadosValor = array(
                    'titulo' => $titulo,
                    'valor' => strtolower($valor),
                    'id_integracao_parametro' => $insertID
                );
                
                $insertValor = DBCreate('', 'tb_integracao_valores_tipo_parametro', $dadosValor);
                registraLog('Inserção de novos valores de parâmetros.','i','tb_integracao_valores_tipo_parametro',$insertValor,"titulo: $titulo | valor: $valor | id_integracao_parametros: $insertID");
            }
        }
        
        $alert = ('Item inserido com sucesso!');
        $alert_type = 's';            }
        
    header("location: /api/iframe?token=$request->token&view=parametros-integracao-busca");
    
    exit;
}

function alterar($id, $codigo, $nome, $tipo, $obrigatorio, $id_integracao, $dados_valor){

    $dados = array(
        'codigo' => $codigo,
        'nome' => $nome,
        'tipo' => $tipo,
        'obrigatorio' => $obrigatorio,
        'id_integracao' => $id_integracao
    );
    DBUpdate('', 'tb_integracao_parametro', $dados, "id_integracao_parametro = $id");

    DBDelete('', 'tb_integracao_valores_tipo_parametro', "id_integracao_parametro = '$id'");
    foreach($dados_valor as $conteudo){
        if($conteudo['valor'] && $id_integracao){

            $titulo = $conteudo['titulo'];
            $valor = $conteudo['valor'];
            
            $dadosValor = array(
                'titulo' => $titulo,
                'valor' => strtolower($valor),
                'id_integracao_parametro' => $id
            );
            
            $insertID = DBCreate('', 'tb_integracao_valores_tipo_parametro', $dadosValor);
            registraLog('Alteração de valores de parâmetros.','a','tb_integracao_valores_tipo_parametro',$insertID,"titulo: $titulo | valor: $valor | id_integracao_parametro: $id");
        }
    }

    registraLog('Alteração de campo de parâmetro de integração.','a','tb_integracao_parametro',$id,"codigo: $codigo | nome: $nome | tipo: $tipo | obrigatorio: $obrigatorio | id_integracao: $id_integracao");
    $alert = ('Item alterado com sucesso!');
    $alert_type = 's';    header("location: /api/iframe?token=$request->token&view=parametros-integracao-busca");
    exit;
}

?>