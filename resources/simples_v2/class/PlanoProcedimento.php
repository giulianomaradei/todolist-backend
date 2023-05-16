<?php
require_once(__DIR__."/System.php");


$nome = (!empty($_POST['nome'])) ? $_POST['nome'] : '';
$cod_servico = (!empty($_POST['cod_servico'])) ? $_POST['cod_servico'] : '';
$descricao = (!empty($_POST['descricao'])) ? $_POST['descricao'] : '';
$pre_requisito = (!empty($_POST['pre_requisito'])) ? $_POST['pre_requisito'] : '';
$status = (!empty($_POST['status'])) ? $_POST['status'] : '0';

if (!empty($_POST['inserir'])) {  
    
    $dados = DBRead('', 'tb_plano_procedimento', "WHERE BINARY nome = '".addslashes($nome)."' AND cod_servico = '$cod_servico'");
    if (!$dados) {
        inserir($nome, $cod_servico, $descricao, $pre_requisito, $status);
    } else {        
        $alert = ('Item já existe na base de dados!');
        $alert_type = 'w';        header("location: /api/iframe?token=$request->token&view=plano-form");
        exit;
    }

} else if (!empty($_POST['alterar'])) {
    $id = (int)$_POST['alterar'];
       
    $dados = DBRead('', 'tb_plano_procedimento', "WHERE BINARY nome = '".addslashes($nome)."' AND cod_servico = '$cod_servico' AND id_plano_procedimento != '$id'");
    if (!$dados) {
        alterar($id, $nome, $cod_servico, $descricao, $pre_requisito, $status);
    } else {
        $alert = ('Item já existe na base de dados!');
        $alert_type = 'w';        header("location: /api/iframe?token=$request->token&view=plano-procedimento-form&alterar=$id");
        exit;
    }

} else if (isset($_GET['excluir'])) {

    $id = (int)$_GET['excluir'];
    $dados_plano_procedimento_plano = DBRead('', 'tb_plano_procedimento_plano',"WHERE id_plano_procedimento = '".$id."' LIMIT 1");
    
    $dados_plano_procedimento_historico = DBRead('', 'tb_plano_procedimento_historico',"WHERE id_plano_procedimento = '".$id."' LIMIT 1");

    if($dados_plano_procedimento_plano || $dados_plano_procedimento_historico){
        if($dados_plano_procedimento_plano){
            $alert = ('Este procedimento está sendo utilizado em algum plano!','w');
        }else{
            $alert = ('Este procedimento já foi utlizado em algum plano!','w');
        }
        header("location: /api/iframe?token=$request->token&view=plano-procedimento-busca");
        exit;
    }else{
        excluir($id);
    }

}else{
    header("location: ../adm.php");
    exit;
}

function inserir($nome, $cod_servico, $descricao, $pre_requisito, $status){
    $dados = array(
        'nome' => $nome,
        'cod_servico' => $cod_servico,
        'descricao' => $descricao,
        'pre_requisito' => $pre_requisito,
        'status' => $status
    );

    $insertID = DBCreate('', 'tb_plano_procedimento', $dados, true);
    registraLog('Inserção de plano proccedimento no sistema.','i','tb_plano_procedimento',$insertID,"nome: $nome | cod_servico: $cod_servico | descricao: $descricao | pre_requisito: $pre_requisito | status: $status");
    
    $alert = ('Item inserido com sucesso!');
    $alert_type = 's';    header("location: /api/iframe?token=$request->token&view=plano-procedimento-busca");
    exit;
}

function alterar($id, $nome, $cod_servico, $descricao, $pre_requisito, $status){

    $dados = array(
        'nome' => $nome,
        'cod_servico' => $cod_servico,
        'descricao' => $descricao,
        'pre_requisito' => $pre_requisito,
        'status' => $status
    );

    DBUpdate('', 'tb_plano_procedimento', $dados, "id_plano_procedimento = '$id'");
    registraLog('Alteração de plano procedimento.','a','tb_plano_procedimento',$id,"nome: $nome | cod_servico: $cod_servico | descricao: $descricao | pre_requisito: $pre_requisito | status: $status");
    
    $alert = ('Item alterado com sucesso!');
    $alert_type = 's';    header("location: /api/iframe?token=$request->token&view=plano-procedimento-busca");
    exit;
}

function excluir($id){
   
    $query = "DELETE FROM tb_plano_procedimento WHERE id_plano_procedimento = $id";
    $link = DBConnect('');
    $result = @mysqli_query($link, $query);
    DBClose($link);
    registraLog('Exclusão de plano procedimento do sistema.','e','tb_plano_procedimento',$id,'');
    if(!$result){
$alert = ('Erro ao excluir item!');
        $alert_type = 'd';    }else{
        $alert = ('Item excluído com sucesso!');
    $alert_type = 's';    }    
    header("location: /api/iframe?token=$request->token&view=plano-procedimento-busca");
    exit;
}

?>
