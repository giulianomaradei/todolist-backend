<?php
require_once(__DIR__."/System.php");


$id_perfil_forum = (!empty($_POST['id_perfil_forum'])) ? $_POST['id_perfil_forum'] : '';
$id_perfil_sistema = (!empty($_POST['id_perfil_sistema'])) ? $_POST['id_perfil_sistema'] : '';
$id_forum = (!empty($_POST['id_forum'])) ? $_POST['id_forum'] : '';

if (!empty($_POST['inserir'])) {
    
    $dados = DBRead('', 'tb_perfil_forum', "WHERE id_perfil_sistema = '$id_perfil_sistema' AND id_forum='$id_forum'");
    if (!$dados) {
        inserir($id_perfil_sistema, $id_forum);
    } else {
        $alert = ('Já existe um vínculo entre as duas pessoas!','w');
        header("location: /api/iframe?token=$request->token&view=forum-form&vincular=$id_pessoa_pai");
        exit;
    }

} else if (!empty($_POST['alterar'])) {

    $id = (int)$_POST['alterar'];
   
    $dados = DBRead('', 'tb_vinculo_pessoa', "WHERE id_pessoa_pai = '$id_pessoa_pai' AND id_pessoa_filho='$id_pessoa_filho' AND id_vinculo_pessoa != '$id'");
    if (!$dados) {
        alterar($id, $id_pessoa_pai, $id_pessoa_filho, $tipos_vinculos);
    } else {
        $alert = ('Já existe um vínculo entre as duas pessoas!','w');
        header("location: /api/iframe?token=$request->token&view=vinculo-pessoa-form&vincular=$id_pessoa_pai");
        exit;
    }

} else if (isset($_GET['excluir'])) {

    $id = (int)$_GET['excluir'];
    excluir($id);

}else{
    header("location: ../adm.php");
    exit;
}

function inserir($id_pessoa_pai, $id_pessoa_filho, $tipos_vinculos){

    $dados = array(
        'id_pessoa_pai' => $id_pessoa_pai,
        'id_pessoa_filho' => $id_pessoa_filho 
    );
    $vinculo = DBCreate('', 'tb_vinculo_pessoa', $dados, true);
    registraLog('Inserção de vínculo de pessoa.','i','tb_vinculo_pessoa',$vinculo,"id_pessoa_pai: $id_pessoa_pai | id_pessoa_filho: $id_pessoa_filho");
    foreach ($tipos_vinculos as $id_vinculo_tipo) {
        $dados = array(
            'id_vinculo_tipo' => $id_vinculo_tipo,
            'id_vinculo_pessoa' => $vinculo,
        );
        $vinculo_tipo_pessoa = DBCreate('', 'tb_vinculo_tipo_pessoa', $dados, true);
        registraLog('Inserção de tipo de vínculo a pessoa.','i','tb_vinculo_tipo_pessoa',$vinculo_tipo_pessoa,"id_vinculo_tipo: $id_vinculo_tipo | id_vinculo_pessoa: $vinculo");
    }
    $alert = ('Vínculo inserido com sucesso!','s');
    header("location: /api/iframe?token=$request->token&view=pessoa-form&alterar=$id_pessoa_pai");
    exit;
}

function alterar($id, $id_pessoa_pai, $id_pessoa_filho, $tipos_vinculos){

    $dados = array(
        'id_pessoa_pai' => $id_pessoa_pai,
        'id_pessoa_filho' => $id_pessoa_filho 
    );
    DBUpdate('', 'tb_vinculo_pessoa', $dados, "id_vinculo_pessoa = $id");
    registraLog('Alteração de vínculo de pessoa.','a','tb_vinculo_pessoa',$id,"id_pessoa_pai: $id_pessoa_pai | id_pessoa_filho: $id_pessoa_filho");
    DBDelete('','tb_vinculo_tipo_pessoa',"id_vinculo_pessoa = '$id'"); 
    foreach ($tipos_vinculos as $id_vinculo_tipo) {
        $dados = array(
            'id_vinculo_tipo' => $id_vinculo_tipo,
            'id_vinculo_pessoa' => $id,
        );
        $vinculo_tipo_pessoa = DBCreate('', 'tb_vinculo_tipo_pessoa', $dados, true);
        registraLog('Inserção de tipo de vínculo a pessoa.','i','tb_vinculo_tipo_pessoa',$vinculo_tipo_pessoa,"id_vinculo_tipo: $id_vinculo_tipo | id_vinculo_pessoa: $id");
    }
    $alert = ('Vínculo alterado com sucesso!','s');
    header("location: /api/iframe?token=$request->token&view=pessoa-form&alterar=$id_pessoa_pai");
    exit;
}

function excluir($id){
    $dados = DBRead('','tb_vinculo_pessoa',"WHERE id_vinculo_pessoa = '$id'");
    $id_pessoa_pai = $dados[0]['id_pessoa_pai'];
    $query = "DELETE FROM tb_vinculo_pessoa WHERE id_vinculo_pessoa = $id";
    $link = DBConnect('');
    $result = @mysqli_query($link, $query);
    DBClose($link);
    registraLog('Exclusão de de vínculo de pessoa.','e','tb_vinculo_pessoa',$id,'');
    if(!$result){
        $alert = ('Erro ao excluir vínculo','d');
        header("location: /api/iframe?token=$request->token&view=pessoa-form&alterar=$id_pessoa_pai");
        exit;
    }else{
        $alert = ('Vínculo excluído com sucesso!','s');
        header("location: /api/iframe?token=$request->token&view=pessoa-form&alterar=$id_pessoa_pai");
        exit;
    }
}
?>