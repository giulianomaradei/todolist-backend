<?php
require_once(__DIR__."/System.php");


$id_pessoa_pai = (!empty($_POST['id_pessoa_pai'])) ? $_POST['id_pessoa_pai'] : '';
$id_pessoa_filho = (!empty($_POST['id_pessoa_filho'])) ? $_POST['id_pessoa_filho'] : '';
$tipos_vinculos = (!empty($_POST['tipos_vinculos'])) ? $_POST['tipos_vinculos'] : '';
$tipo_de_pessoa = (!empty($_POST['tipo_de_pessoa'])) ? $_POST['tipo_de_pessoa'] : '';
$id_rd_conversao = (!empty($_POST['id_rd_conversao'])) ? $_POST['id_rd_conversao'] : '';

$id_usuario = $_SESSION['id_usuario'];
$id_pessoa = (!empty($_GET['pessoa'])) ? $_GET['pessoa'] : '';

$id_pessoa_alterou = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_usuario = '$id_usuario'");
$pessoa_alterou = $id_pessoa_alterou[0]['id_pessoa'];

if(!empty($_POST['inserir'])){
    $dados = DBRead('', 'tb_vinculo_pessoa', "WHERE id_pessoa_pai = '$id_pessoa_pai' AND id_pessoa_filho='$id_pessoa_filho'");
    if(!$dados){

        $pessoa_vinculada = DBRead('', 'tb_vinculo_pessoa a', "INNER JOIN tb_pessoa b ON a.id_pessoa_pai = b.id_pessoa WHERE id_pessoa_filho = '$id_pessoa_filho'", "b.nome");
        if(!$pessoa_vinculada){
            inserir($id_pessoa_pai, $id_pessoa_filho, $tipos_vinculos, $pessoa_alterou, $id_usuario, $tipo_de_pessoa, $id_rd_conversao);
        }else{
            $alert = ('Essa pessoa já está vinculada a outra!','w');
            inserir($id_pessoa_pai, $id_pessoa_filho, $tipos_vinculos, $pessoa_alterou, $id_usuario, $tipo_de_pessoa, $id_rd_conversao);
        }

    }else{
        $alert = ('Já existe um vínculo entre as duas pessoas!','w');
        header("location: /api/iframe?token=$request->token&view=vinculo-pessoa-form&vincular=$id_pessoa_pai");
        exit;
    }

} else if(!empty($_POST['alterar'])){
    $id = (int)$_POST['alterar'];
    $dados = DBRead('', 'tb_vinculo_pessoa', "WHERE id_pessoa_pai = '$id_pessoa_pai' AND id_pessoa_filho='$id_pessoa_filho' AND id_vinculo_pessoa != '$id'");
    if(!$dados){
        alterar($id, $id_pessoa_pai, $id_pessoa_filho, $tipos_vinculos, $pessoa_alterou, $id_usuario);
    }else{
        $alert = ('Já existe um vínculo entre as duas pessoas!','w');
        header("location: /api/iframe?token=$request->token&view=vinculo-pessoa-form&vincular=$id_pessoa_pai");
        exit;
    }

} else if (isset($_GET['excluir'])) {
    $id = (int)$_GET['excluir'];
    $operacao = "Excluir";
    
    excluir($id);
}else{
    header("location: ../adm.php");
    exit;
}

function inserir($id_pessoa_pai, $id_pessoa_filho, $tipos_vinculos, $pessoa_alterou, $id_usuario, $tipo_de_pessoa, $id_rd_conversao)
{
    if ($tipo_de_pessoa == 'nova') {

        $nome = (!empty($_POST['nome'])) ? $_POST['nome'] : '';
        $telefone = (!empty($_POST['telefone'])) ? preg_replace("/[^0-9]/", "", $_POST['telefone']) : '';
        $email = (!empty($_POST['email'])) ? $_POST['email'] : '';

        $link = DBConnect('');
	    DBBegin($link);

        $data_atualizacao = getDataHora();
        $status = 1;

        $dados = array(
            'nome' => $nome,
            'fone1' => $telefone,
            'email1' => $email,
            'status' => $status,
            'data_atualizacao' => $data_atualizacao,
            'id_cidade' => 9999999
        );

        $insertIDPessoa = DBCreateTransaction($link, 'tb_pessoa', $dados, true);
        registraLogTransaction($link, 'Inserção de pessoa.', 'i', 'tb_pessoa', $insertIDPessoa, "nome: $nome |fone1: $telefone | email1: $email | status: $status | data_atualizacao: $data_atualizacao | id_cidade: 9999999");

        $dado_alterado = "Vínculo(s) - ";

        $dados = array(
            'id_pessoa_pai'   => $id_pessoa_pai,
            'id_pessoa_filho' => $insertIDPessoa
        );

        $vinculo = DBCreateTransaction($link, 'tb_vinculo_pessoa', $dados, true);
        registraLogTransaction($link, 'Inserção de vínculo de pessoa.','i','tb_vinculo_pessoa',$vinculo,"id_pessoa_pai: $id_pessoa_pai | id_pessoa_filho: $insertIDPessoa");

        if ($id_rd_conversao) {

            $dados_conversao = array(
                'id_pessoa' => $id_pessoa_pai
            );
            DBUpdateTransaction($link, 'tb_rd_conversao', $dados_conversao, "id_rd_conversao = $id_rd_conversao");
            registraLogTransaction($link, 'Alteração de RD lead conversao.', 'a', 'tb_rd_conversao', $id_rd_conversao, " id_pessoa: $id_pessoa_pai");
        }

        foreach($tipos_vinculos as $id_vinculo_tipo){

            $dados = array(
                'id_vinculo_tipo' => $id_vinculo_tipo,
                'id_vinculo_pessoa' => $vinculo,
            );

            $vinculo_tipo_pessoa = DBCreateTransaction($link, 'tb_vinculo_tipo_pessoa', $dados, true);
            registraLogTransaction($link, 'Inserção de tipo de vínculo a pessoa.','i','tb_vinculo_tipo_pessoa',$vinculo_tipo_pessoa,"id_vinculo_tipo: $id_vinculo_tipo | id_vinculo_pessoa: $vinculo");

            $nome_tipo = DBReadTransaction($link, 'tb_vinculo_tipo');
        }

        foreach($tipos_vinculos as $dado){
            $nome_tipo = DBReadTransaction($link, 'tb_vinculo_tipo',"WHERE id_vinculo_tipo = '$dado'");
            $dado_alterado .= $nome_tipo[0]['nome'] . ", ";
        }

        DBCommit($link);

        $alterado_sem_ultimo = substr($dado_alterado, 0, -2);
        $alteracao = "Pessoa";
        $operacao = "Inserir";

        notificacao($alteracao, $insertIDPessoa, $operacao, $pessoa_alterou, $id_usuario, $alterado_sem_ultimo);

        $alert = ('Vínculo inserido com sucesso!','s');

        if ($id_rd_conversao != '') {
            header("location: /api/iframe?token=$request->token&view=lead-negocio-form&pessoa=$id_pessoa_pai&id_rd_conversao=$id_rd_conversao");
            exit;
            
        } else {
            header("location: /api/iframe?token=$request->token&view=pessoa-form&alterar=$id_pessoa_pai");
            exit;
        }
        
    } else{
        $dado_alterado = "Vínculo(s) - ";

        $dados = array(
            'id_pessoa_pai'   => $id_pessoa_pai,
            'id_pessoa_filho' => $id_pessoa_filho
        );

        $vinculo = DBCreate('', 'tb_vinculo_pessoa', $dados, true);
        registraLog('Inserção de vínculo de pessoa.','i','tb_vinculo_pessoa',$vinculo,"id_pessoa_pai: $id_pessoa_pai | id_pessoa_filho: $id_pessoa_filho");

        foreach($tipos_vinculos as $id_vinculo_tipo){
            $dados = array(
                'id_vinculo_tipo' => $id_vinculo_tipo,
                'id_vinculo_pessoa' => $vinculo,
            );
            $vinculo_tipo_pessoa = DBCreate('', 'tb_vinculo_tipo_pessoa', $dados, true);
            registraLog('Inserção de tipo de vínculo a pessoa.','i','tb_vinculo_tipo_pessoa',$vinculo_tipo_pessoa,"id_vinculo_tipo: $id_vinculo_tipo | id_vinculo_pessoa: $vinculo");

            $nome_tipo = DBRead('', 'tb_vinculo_tipo');
        }

        foreach($tipos_vinculos as $dado){
            $nome_tipo = DBRead('', 'tb_vinculo_tipo',"WHERE id_vinculo_tipo = '$dado'");
            $dado_alterado .= $nome_tipo[0]['nome'] . ", ";
        }
        
        $alterado_sem_ultimo = substr($dado_alterado, 0, -2);
        $alteracao = "Pessoa";
        $operacao = "Inserir";

        notificacao($alteracao, $id_pessoa_filho, $operacao, $pessoa_alterou, $id_usuario, $alterado_sem_ultimo);

        $alert = ('Vínculo inserido com sucesso!','s');
        
        header("location: /api/iframe?token=$request->token&view=pessoa-form&alterar=$id_pessoa_pai");
        exit;
    }
}

function alterar($id, $id_pessoa_pai, $id_pessoa_filho, $tipos_vinculos, $pessoa_alterou, $id_usuario){

    $dados = array(
        'id_pessoa_pai' => $id_pessoa_pai,
        'id_pessoa_filho' => $id_pessoa_filho 
    );

    DBUpdate('', 'tb_vinculo_pessoa', $dados, "id_vinculo_pessoa = $id");
    registraLog('Alteração de vínculo de pessoa.','a','tb_vinculo_pessoa',$id,"id_pessoa_pai: $id_pessoa_pai |id_pessoa_filho: $id_pessoa_filho");

    DBDelete('','tb_vinculo_tipo_pessoa',"id_vinculo_pessoa = '$id'"); 

    foreach ($tipos_vinculos as $id_vinculo_tipo) {

        $dados = array(
            'id_vinculo_tipo' => $id_vinculo_tipo,
            'id_vinculo_pessoa' => $id,
        );

        $vinculo_tipo_pessoa = DBCreate('', 'tb_vinculo_tipo_pessoa', $dados, true);
        registraLog('Inserção de tipo de vínculo a pessoa.','i','tb_vinculo_tipo_pessoa',$vinculo_tipo_pessoa,"id_vinculo_tipo: $id_vinculo_tipo | id_vinculo_pessoa: $id");
    }

    foreach($tipos_vinculos as $dado){

        $nome_tipo = DBRead('', 'tb_vinculo_tipo',"WHERE id_vinculo_tipo = '$dado'");
        $dado_alterado .= $nome_tipo[0]['nome'].", ";
    }

    $alterado_sem_ultimo = substr($dado_alterado, 0, -2);

    $operacao = "Alterar";
    $alteracao = "Pessoa";
    $dado_alterado = "";

    if(!$alterado_sem_ultimo){
        $alterado_sem_ultimo = 'Vínculo removido';   
    }
    
    notificacao($alteracao, $id_pessoa_filho, $operacao, $pessoa_alterou, $id_usuario, $alterado_sem_ultimo);

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
