<?php
require_once(__DIR__."/System.php");

$id_categoria = (!empty($_POST['id_categoria'])) ? $_POST['id_categoria'] : '';
$descricao = (!empty($_POST['descricao'])) ? $_POST['descricao'] : '';

if (!empty($_POST['inserir'])) {
    
    $dados = DBRead('', 'tb_chamado_script', "WHERE id_categoria = $id_categoria AND status = 1");

    if (!$dados) {
        inserir($id_categoria, $descricao);

    } else {
        $alert = ('Já existe um script com esta categoria!','w');
        header("location: /api/iframe?token=$request->token&view=chamado-script-form");
    }
    


} else if (!empty($_POST['alterar'])) {
    $id = (int)$_POST['alterar'];
    
    alterar($id, $id_categoria, $descricao);
   

} else if (isset($_GET['excluir'])) {

    $id = (int)$_GET['excluir'];
    excluir($id);

} else if (isset($_POST['busca_script'])) {

    $id_categoria = (!empty($_POST['id_categoria'])) ? $_POST['id_categoria'] : '';
    buscaScript($id_categoria);

} else {
    header("location: ../adm.php");
    exit;
}

function inserir ($id_categoria, $descricao) {
    
    if ($id_categoria != '' && $descricao !='') {

        $dados = array(
            'id_categoria' => $id_categoria,
            'descricao' => $descricao,
        );
    
        $insertID = DBCreate('', 'tb_chamado_script', $dados, true);
        registraLog('Inserção de novo chamado script.','i','tb_chamado_script',$insertID,"id_categoria: $id_categoria | descricao: $descricao");

        $alert = ('Item inserido com sucesso!');
    $alert_type = 's';        header("location: /api/iframe?token=$request->token&view=chamado-script-busca");

    }else{
        $alert = ('Não foi possível inserir o item!');
        $alert_type = 'w';                header("location: /api/iframe?token=$request->token&view=chamado-script-form");
    }
    exit;
}

function alterar ($id, $id_categoria, $descricao) {

    if ($id && $id_categoria != '' && $descricao !='') {

        $dados = array(
            'id_categoria' => $id_categoria,
            'descricao' => $descricao,
        );

        DBUpdate('', 'tb_chamado_script', $dados, "id_chamado_script = $id");
        registraLog('Alteração de chamado script.','a','tb_chamado_script',$id,"id_categoria: $id_categoria | descricao: $descricao");

        $alert = ('Item alterado com sucesso!');
    $alert_type = 's';        header("location: /api/iframe?token=$request->token&view=chamado-script-busca");
    }else{
        $alert = 'Não foi possível alterar o item!' ;
        $alert_type = 'w';                header("location: /api/iframe?token=$request->token&view=chamado-script-form&alterar=$id");
    }
    
    exit;
}

function excluir ($id) {
    
    if ($id) {

        $dados = array(
            'status' => 2,
        );

        DBUpdate('', 'tb_chamado_script', $dados, "id_chamado_script = $id");
        registraLog('Alteração de chamado script.','a','tb_chamado_script',$id,"status: 2");

        $alert = ('Item excluído com sucesso!');
    $alert_type = 's';        header("location: /api/iframe?token=$request->token&view=chamado-script-busca");

    } else {
$alert = ('Erro ao excluir item!');
        $alert_type = 'd';        header("location: /api/iframe?token=$request->token&view=chamado-script-busca");
    }
    exit;
}

function buscaScript ($id_categoria) {

    $dados = DBRead('', 'tb_chamado_script', "WHERE id_categoria = $id_categoria AND status = 1");

    $script = $dados[0]['descricao'];

    echo json_encode($script);
}

?>