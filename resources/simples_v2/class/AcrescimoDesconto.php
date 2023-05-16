<?php
require_once(__DIR__."/System.php");

$id_contrato_plano_pessoa = (!empty($_POST['id_contrato_plano_pessoa'])) ? $_POST['id_contrato_plano_pessoa'] : '';
$tipo = (!empty($_POST['tipo'])) ? $_POST['tipo'] : '';
$mes_referencia = (!empty($_POST['mes_referencia'])) ? $_POST['mes_referencia'] : '';
$ano_referencia = (!empty($_POST['ano_referencia'])) ? $_POST['ano_referencia'] : '';
$valor = (!empty($_POST['valor'])) ? converteMoeda($_POST['valor'],'banco') : 0;
$descricao = (!empty($_POST['descricao'])) ? $_POST['descricao'] : '';

if (!empty($_POST['inserir'])) {
    
    inserir($id_contrato_plano_pessoa, $tipo, $mes_referencia, $ano_referencia, $valor, $descricao);
  
} else if (!empty($_POST['alterar'])) {
    $id = (int)$_POST['alterar'];
   
    alterar($id, $id_contrato_plano_pessoa, $tipo, $mes_referencia, $ano_referencia, $valor, $descricao);

} else if (isset($_GET['excluir'])) {

    $id = (int)$_GET['excluir'];
    excluir($id);

}else{
    header("location: ../adm.php");
    exit;
}

function inserir($id_contrato_plano_pessoa, $tipo, $mes_referencia, $ano_referencia, $valor, $descricao){
    $id_usuario = $_SESSION['id_usuario'];
    if($mes_referencia && $ano_referencia){
        $data_referencia = $ano_referencia."-".$mes_referencia."-01";
    }

    if($id_contrato_plano_pessoa && $tipo && $data_referencia && $valor){
        $dados = array(
            'id_contrato_plano_pessoa' => $id_contrato_plano_pessoa,
            'tipo' => $tipo,
            'data_referencia' =>  $data_referencia,
            'valor' =>  $valor,
            'id_usuario' =>  $id_usuario,
            'descricao' =>  $descricao,
        );
    
        $insertID = DBCreate('', 'tb_acrescimo_desconto', $dados, true);
        registraLog('Inserção de novo acrescimo/desconto.','i','tb_acrescimo_desconto',$insertID,"id_contrato_plano_pessoa: $id_contrato_plano_pessoa | tipo: $tipo | data_referencia: $data_referencia | valor: $valor | id_usuario: $id_usuario | descricao: $descricao");
        
        $alert = ('Item inserido com sucesso!');
    $alert_type = 's';        header("location: /api/iframe?token=$request->token&view=acrescimo-desconto-busca");
    }else{
        $alert = ('Não foi possível inserir o dado!','w');
        header("location: /api/iframe?token=$request->token&view=acrescimo-desconto-form");
    }

    exit;
}

function alterar($id, $id_contrato_plano_pessoa, $tipo, $mes_referencia, $ano_referencia, $valor, $descricao){
    $id_usuario = $_SESSION['id_usuario'];

    if($mes_referencia && $ano_referencia){
        $data_referencia = $ano_referencia."-".$mes_referencia."-01";
    }

    if($id_contrato_plano_pessoa && $tipo && $data_referencia && $valor){
        $dados = array(
            'id_contrato_plano_pessoa' => $id_contrato_plano_pessoa,
            'tipo' => $tipo,
            'data_referencia' =>  $data_referencia,
            'valor' =>  $valor,
            'id_usuario' =>  $id_usuario,
            'descricao' =>  $descricao,
        );

        DBUpdate('', 'tb_acrescimo_desconto', $dados, "id_acrescimo_desconto = $id");
        registraLog('Alteração de acrescimo/desconto.','a','tb_acrescimo_desconto',$id,"id_contrato_plano_pessoa: $id_contrato_plano_pessoa | tipo: $tipo | data_referencia: $data_referencia | valor: $valor | id_usuario: $id_usuario | descricao: $descricao");
        $alert = ('Item inserido com sucesso!');
    $alert_type = 's';        header("location: /api/iframe?token=$request->token&view=acrescimo-desconto-busca");
    }else{
        $alert = ('Não foi possível alterar o dado!','w');
        header("location: /api/iframe?token=$request->token&view=acrescimo-desconto-busca&alterar=$id");
    }   
    exit;
}

function excluir($id){
    $query = "DELETE FROM tb_acrescimo_desconto WHERE id_acrescimo_desconto = $id";
    $link = DBConnect('');
    $result = @mysqli_query($link, $query);
    DBClose($link);
    registraLog('Exclusão de acrescimo/desconto.','e','tb_acrescimo_desconto',$id,'');
    if(!$result){
$alert = ('Erro ao excluir item!');
        $alert_type = 'd';    }else{
        $alert = ('Item excluído com sucesso!');
    $alert_type = 's';    }
    header("location: /api/iframe?token=$request->token&view=acrescimo-desconto-busca");
    exit;
}

?>