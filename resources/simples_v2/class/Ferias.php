<?php
require_once(__DIR__."/System.php");

$usuario = (!empty($_POST['usuario'])) ? $_POST['usuario'] : '';
$data_de = (!empty($_POST['data_de'])) ? $_POST['data_de'] : '';
$data_ate = (!empty($_POST['data_ate'])) ? $_POST['data_ate'] : '';
$id_ferias = (!empty($_POST['alterar'])) ? $_POST['alterar'] : '';

$dados = DBRead('', 'tb_ferias', "WHERE id_usuario = '$usuario' ");

//echo "Dados De: ".$dados[0]['data_de']."<br>Dados Até: ".$dados[0]['data_ate'];
//echo "<hr>";
//echo "De: ".$data_de."<br>Até: ".$data_ate;

$dados = DBRead('', 'tb_ferias', "WHERE id_usuario = '".$usuario."' AND ((data_de BETWEEN '".converteDataHora($data_de)."' AND '".converteDataHora($data_ate)."') OR (data_ate BETWEEN '".converteDataHora($data_de)."' AND '".converteDataHora($data_ate)."') OR (data_de <= '".converteDataHora($data_de)."' AND data_ate >= '".converteDataHora($data_ate)."')) ");

$partes_data_de = explode("/", $data_de);
$partes_data_de = $partes_data_de[2]."".$partes_data_de[1]."".$partes_data_de[0];
$partes_data_ate = explode("/", $data_ate);
$partes_data_ate = $partes_data_ate[2]."".$partes_data_ate[1]."".$partes_data_ate[0];

if (!empty($_POST['inserir'])) {
    
    if($partes_data_de <= $partes_data_ate){
        
        $dados = DBRead('', 'tb_ferias', "WHERE id_usuario = '".$usuario."' AND ((data_de BETWEEN '".converteDataHora($data_de)."' AND '".converteDataHora($data_ate)."') OR (data_ate BETWEEN '".converteDataHora($data_de)."' AND '".converteDataHora($data_ate)."') OR (data_de <= '".converteDataHora($data_de)."' AND data_ate >= '".converteDataHora($data_ate)."')) ");
       
        if (!$dados) {
            inserir($usuario, $data_de, $data_ate);
        }else{
            $alert = ('Usuário já possui férias nesta data!','w');
            header("location: /api/iframe?token=$request->token&view=ferias-form");
            exit;       
        }
    }else{
        $alert = ('A data inicial é maior que a data final!','w');
        header("location: /api/iframe?token=$request->token&view=ferias-form");
        exit;       
    }

} else if (!empty($_POST['alterar'])) {
    
    if($partes_data_de <= $partes_data_ate){
        
        $dados = DBRead('', 'tb_ferias', "WHERE id_usuario = '".$usuario."' AND ((data_de BETWEEN '".converteDataHora($data_de)."' AND '".converteDataHora($data_ate)."') OR (data_ate BETWEEN '".converteDataHora($data_de)."' AND '".converteDataHora($data_ate)."') OR (data_de <= '".converteDataHora($data_de)."' AND data_ate >= '".converteDataHora($data_ate)."')) AND id_ferias != '".$id_ferias."'");

        if (!$dados) {
            alterar($usuario, $data_de, $data_ate, $id_ferias);
        }else{
            $alert = ('Usuário já possui férias nesta data!','w');
            header("location: /api/iframe?token=$request->token&view=ferias-form&alterar=$id_ferias");
            exit;        
        }
    }else{
        $alert = ('A data inicial é menor a data final!','w');
        header("location: /api/iframe?token=$request->token&view=ferias-form&alterar=$id_ferias");
        exit;      
    }
   
} else if (isset($_GET['excluir'])) {

    $id_ferias = (int)$_GET['excluir'];
    excluir($id_ferias);

}else{
    header("location: ../adm.php");
    exit;
}

function inserir($usuario, $data_de, $data_ate){
    $data_de = converteDataHora($data_de);
    $data_ate = converteDataHora($data_ate);

        $dados = array(
            'id_usuario' => $usuario,
            'data_de' => $data_de,
            'data_ate' => $data_ate
        );

        $insertID = DBCreate('', 'tb_ferias', $dados, true);
        registraLog('Inserção de ferias.','i','tb_ferias',$insertID,"id_usuario: $usuario | data_de: $data_de | data_ate: $data_ate");
        $alert = ('Item inserido com sucesso!');
    $alert_type = 's';        header("location: /api/iframe?token=$request->token&view=ferias");
     
    exit;

}

function alterar($usuario, $data_de, $data_ate, $id_ferias){
    $data_de = converteDataHora($data_de);
    $data_ate = converteDataHora($data_ate);
    
        $dados = array(
            'id_usuario' => $usuario,
            'data_de' => $data_de,
            'data_ate' => $data_ate
        );

        DBUpdate('', 'tb_ferias', $dados, "id_ferias = '$id_ferias'");
        registraLog('Alteração de ferias.','a','tb_ferias',$id_ferias,"id_usuario: $usuario | data_de: $data_de | data_ate: $data_ate");
        $alert = ('Item alterado com sucesso!');
    $alert_type = 's';        header("location: /api/iframe?token=$request->token&view=ferias");
    
    exit;
}

function excluir($id_ferias){
    
    $query = "DELETE FROM tb_ferias WHERE id_ferias = $id_ferias";
    $link = DBConnect('');
    $result = @mysqli_query($link, $query);
    DBClose($link);
    registraLog('Exclusão de ferias.','e','tb_ferias',$id_ferias,'');
    if(!$result){
$alert = ('Erro ao excluir item!');
        $alert_type = 'd';    }else{
        $alert = ('Item excluído com sucesso!');
    $alert_type = 's';    }
    header("location: /api/iframe?token=$request->token&view=ferias");
    exit;
}

?>
