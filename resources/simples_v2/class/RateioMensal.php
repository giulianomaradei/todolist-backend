<?php
require_once(__DIR__."/System.php");


$centro_custos = (!empty($_POST['centro_custos'])) ? $_POST['centro_custos'] : '';

$data_periodo_mes = (!empty($_POST['data_periodo_mes'])) ? $_POST['data_periodo_mes'] : '';
$data_periodo_ano = (!empty($_POST['data_periodo_ano'])) ? $_POST['data_periodo_ano'] : '';

$id_centro_custos = (!empty($_POST['id_centro_custos'])) ? $_POST['id_centro_custos'] : '';
$porcentagem_rateio_centro_custos = (!empty($_POST['porcentagem_rateio_centro_custos'])) ? $_POST['porcentagem_rateio_centro_custos'] : '';

if(!empty($_POST['inserir'])){

    $data_referencia = $data_periodo_ano."-".$data_periodo_mes."-01";
    
    $dados = DBRead('', 'tb_centro_custos_rateio', "WHERE id_centro_custos_principal = '$centro_custos' AND data_referencia = '".$data_referencia."' ");

    if(!$dados){

        $dados_centro_custo_secundario = array();
    
        $cont = 0;
        foreach ($id_centro_custos as $conteudo_id_centro_custos) {
            if($porcentagem_rateio_centro_custos[$cont]){
                $dados_centro_custo_secundario[$cont]['id_centro_custos'] = $conteudo_id_centro_custos;
                $dados_centro_custo_secundario[$cont]['porcentagem_rateio_centro_custos'] = $porcentagem_rateio_centro_custos[$cont];
            }
            $cont++;
        }
        
        inserir($centro_custos, $data_referencia, $dados_centro_custo_secundario);

    }else{
        
        $alert = ('Erro ao inserir item! Conflito de datas.','d');
        header("location: /api/iframe?token=$request->token&view=rateio-mensal-form");
        exit;
    }
    
}else if(!empty($_POST['alterar'])){
    
    $id = (int)$_POST['alterar'];

    $data_referencia = $data_periodo_ano."-".$data_periodo_mes."-01";

    $dados = DBRead('', 'tb_centro_custos_rateio', "WHERE id_centro_custos_principal = '$centro_custos' AND data_referencia = '".$data_referencia."' AND id_centro_custos_rateio != '".$id."' ");

    if(!$dados){
        $dados_centro_custo_secundario = array();
    
        $cont = 0;
        foreach ($id_centro_custos as $conteudo_id_centro_custos) {
            if($porcentagem_rateio_centro_custos[$cont]){
                $dados_centro_custo_secundario[$cont]['id_centro_custos'] = $conteudo_id_centro_custos;
                $dados_centro_custo_secundario[$cont]['porcentagem_rateio_centro_custos'] = $porcentagem_rateio_centro_custos[$cont];
            }
            $cont++;
        }    
        alterar($id, $centro_custos, $data_referencia, $dados_centro_custo_secundario);
        
    }else{
        
        $alert = ('Erro ao inserir item! Conflito de datas.','d');
        header("location: /api/iframe?token=$request->token&view=rateio-mensal-form&alterar=".$id);
        exit;
    }
   
}else if(isset($_GET['excluir'])){
    $id = (int)$_GET['excluir'];
    excluir($id);
}else{
    header("location: ../adm.php");
    exit;
}

function inserir($id_centro_custos_principal, $data_referencia, $dados_centro_custo_secundario){

    $dados_principal = array(
        'id_centro_custos_principal' => $id_centro_custos_principal,
        'data_referencia' => $data_referencia
    );

    $insertID = DBCreate('', 'tb_centro_custos_rateio', $dados_principal, true);

    registraLog('Inserção de Centro de Custos Rateio.','i','tb_centro_custos_rateio',$insertID,"id_centro_custos_principal: $id_centro_custos_principal | data_referencia: $data_referencia");

    foreach ($dados_centro_custo_secundario as $conteudo_centro_custo_secundario) {
        $id_centro_custos = $conteudo_centro_custo_secundario['id_centro_custos'];
        $porcentagem = $conteudo_centro_custo_secundario['porcentagem_rateio_centro_custos'];
        $dados_secundario = array(
            'id_centro_custos' => $id_centro_custos,
            'id_centro_custos_rateio' => $insertID,
            'porcentagem' => $porcentagem
        );
    
        $insertID2 = DBCreate('', 'tb_centro_custos_rateio_centro_custos', $dados_secundario, true);
    
        registraLog('Inserção de Centro de Custos Rateio no Centro de Custos.','i','tb_centro_custos_rateio_centro_custos',$insertID2,"id_centro_custos: $id_centro_custos | id_centro_custos_rateio: $insertID | porcentagem: $porcentagem");
    
    }
    $alert = ('Item inserido com sucesso!');
    $alert_type = 's';    header("location: /api/iframe?token=$request->token&view=rateio-mensal-busca");
    exit;
}

function alterar($id, $id_centro_custos_principal, $data_referencia, $dados_centro_custo_secundario){

    $dados_principal = array(
        'id_centro_custos_principal' => $id_centro_custos_principal,
        'data_referencia' => $data_referencia
    );

    DBUpdate('', 'tb_centro_custos_rateio', $dados_principal, "id_centro_custos_rateio = '$id'");

    registraLog('Alteração de de Centro de Custos Rateio.','a','tb_centro_custos_rateio',$id,"id_centro_custos_principal: $id_centro_custos_principal | data_referencia: $data_referencia");

    $query = "DELETE FROM tb_centro_custos_rateio_centro_custos WHERE id_centro_custos_rateio = $id";
    $link = DBConnect('');
    $result = @mysqli_query($link, $query);
    DBClose($link);

    foreach ($dados_centro_custo_secundario as $conteudo_centro_custo_secundario) {
        $id_centro_custos = $conteudo_centro_custo_secundario['id_centro_custos'];
        $porcentagem = $conteudo_centro_custo_secundario['porcentagem_rateio_centro_custos'];
        $dados_secundario = array(
            'id_centro_custos' => $id_centro_custos,
            'id_centro_custos_rateio' => $id,
            'porcentagem' => $porcentagem
        );
    
        $insertID2 = DBCreate('', 'tb_centro_custos_rateio_centro_custos', $dados_secundario, true);
    
        registraLog('Alteração de Centro de Custos Rateio no Centro de Custos.','i','tb_centro_custos_rateio_centro_custos',$insertID2,"id_centro_custos: $id_centro_custos | id_centro_custos_rateio: $id | porcentagem: $porcentagem");
    }
    
        
    $alert = ('Item alterado com sucesso!');
    $alert_type = 's';    header("location: /api/iframe?token=$request->token&view=rateio-mensal-busca");
    exit;
}

function excluir($id){

    $query = "DELETE FROM tb_centro_custos_rateio WHERE id_centro_custos_rateio = $id";
    $link = DBConnect('');
    $result = @mysqli_query($link, $query);
    DBClose($link);

    if(!$result){
$alert = ('Erro ao excluir item!');
        $alert_type = 'd';    }else{
        $alert = ('Item excluído com sucesso!');
    $alert_type = 's';    } 
    header("location: /api/iframe?token=$request->token&view=rateio-mensal-busca");
    exit;
}
?>
