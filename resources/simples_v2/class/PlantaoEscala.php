<?php
require_once(__DIR__."/System.php");


$plantonistas  = (!empty($_POST['plantonistas'])) ? $_POST['plantonistas'] : '';
$dia  = (!empty($_POST['dia'])) ? $_POST['dia'] : '';
$data_referencia  = (!empty($_POST['data_referencia'])) ? $_POST['data_referencia'] : '';
$valor_diaria  = (!empty($_POST['valor_diaria'])) ? converteMoeda($_POST['valor_diaria'],'banco') : 0;
$porcentagem_comissao  = (!empty($_POST['porcentagem_comissao'])) ? $_POST['porcentagem_comissao'] : 0;


$dados_plantonista = Array();
$cont = 0;
foreach ($plantonistas as $conteudo_plantonista) {
    $dados_plantonista[$cont]['id_usuario'] = $conteudo_plantonista;
    $dados_plantonista[$cont]['data'] = $dia[$cont];
    
    $dia_separado = explode('-', $dia[$cont]);
    $dia_separado = $dia_separado[2];

    $dados_plantonista[$cont]['dia'] = $dia_separado;

    $cont++;
}

echo $valor_diaria."<hr>";
echo $data_referencia."<hr>";


if(!empty($_POST['inserir'])){
    
    $dados_referencia = DBRead('', 'tb_plantonista_redes_mes', "WHERE data_referencia = '".$data_referencia."' ");
    if($dados_referencia){
        $alert = ('Já existe escala para a data de referência!','w');
        header("location: /api/iframe?token=$request->token&view=plantao-busca");
    
        exit;
    }else{
        inserir($dados_plantonista, $valor_diaria, $data_referencia, $porcentagem_comissao);
    }
}else if(!empty($_POST['alterar'])){

    $id = (int)$_POST['alterar'];
    alterar($id, $dados_plantonista, $valor_diaria, $porcentagem_comissao);

}else if(isset($_GET['excluir'])){

    $id = (int)$_GET['excluir'];
    excluir($id);

}else{
    header("location: ../adm.php");
    exit;
}


function inserir($dados_plantonista, $valor_diaria, $data_referencia, $porcentagem_comissao){

        $dados_plantonista_redes_mes = array(
            'data_referencia' => $data_referencia,
            'valor_diaria' => $valor_diaria,
            'porcentagem_comissao' => $porcentagem_comissao
        );
       
        $insertID = DBCreate('', 'tb_plantonista_redes_mes', $dados_plantonista_redes_mes, true);
        registraLog('Inserção de plantonista redes mes.','i','tb_plantonista_redes_mes',$insertID,"data_referencia: $data_referencia | valor_diaria: $valor_diaria | porcentagem_comissao: $porcentagem_comissao");

        foreach ($dados_plantonista as $conteudo_plantonista) {
            $dia = $conteudo_plantonista['dia'];
            $data = $conteudo_plantonista['data'];
            $id_usuario = $conteudo_plantonista['id_usuario'];

            $dados_plantonista_redes_mes_dia = array(
                'id_plantonista_redes_mes' => $insertID,
                'dia' => $dia,
                'data' => $data,
                'id_usuario' => $id_usuario
            );
           
            $insertID2 = DBCreate('', 'tb_plantonista_redes_mes_dia', $dados_plantonista_redes_mes_dia, true);
            registraLog('Inserção de plantonista redes mes dia.','i','tb_plantonista_redes_mes_dia',$insertID2,"id_plantonista_redes_mes: $insertID | dia: $dia | data: $data | id_usuario: $id_usuario");
        }
   

    $alert = ('Item inserido com sucesso!');
    $alert_type = 's';    header("location: /api/iframe?token=$request->token&view=plantao-escala-busca");
    
    exit;
}

function alterar($id, $dados_plantonista, $valor_diaria, $porcentagem_comissao){

    $dados_plantonista_redes_mes = array(
        'valor_diaria' => $valor_diaria,
        'porcentagem_comissao' => $porcentagem_comissao
    );

    DBUpdate('', 'tb_plantonista_redes_mes', $dados_plantonista_redes_mes, "id_plantonista_redes_mes = $id");
    registraLog('Alteração de plantonista redes mes.','a','tb_plantonista_redes_mes',$id,"valor_diaria: $valor_diaria | porcentagem_comissao: $porcentagem_comissao");

    foreach ($dados_plantonista as $conteudo_plantonista) {

        $data = $conteudo_plantonista['data'];
        $id_usuario = $conteudo_plantonista['id_usuario'];

        $dados_plantonista_redes_mes_dia = DBRead('', 'tb_plantonista_redes_mes_dia', "WHERE id_plantonista_redes_mes = '".$id."' AND data = '".$data."' " );
        $id_plantonista_redes_mes_dia = $dados_plantonista_redes_mes_dia[0]['id_plantonista_redes_mes_dia'];

        if($id_usuario != $dados_plantonista_redes_mes_dia[0]['id_usuario']){
            $dados_plantonista_redes_mes_dia = array(
                'id_usuario' => $id_usuario
            );
    
            DBUpdate('', 'tb_plantonista_redes_mes_dia', $dados_plantonista_redes_mes_dia, "id_plantonista_redes_mes_dia = $id_plantonista_redes_mes_dia");
            registraLog('Alteração de plantonista redes mes dia.','a','tb_plantonista_redes_mes_dia',$id_plantonista_redes_mes_dia,"id_usuario: $id_usuario");
        }
    }

    $alert = ('Item alterado com sucesso!');
    $alert_type = 's';    header("location: /api/iframe?token=$request->token&view=plantao-escala-busca");

    exit;
}

function excluir($id){
    
    $query = "DELETE FROM tb_plantonista_redes_mes WHERE id_plantonista_redes_mes = $id";
    $link = DBConnect('');
    $result = @mysqli_query($link, $query);
    DBClose($link);

    if(!$result){
$alert = ('Erro ao excluir item!');
        $alert_type = 'd';    }else{
        $alert = ('Item excluído com sucesso!');
    $alert_type = 's';    } 
    header("location: /api/iframe?token=$request->token&view=plantao-escala-busca");
    exit;

}

?>