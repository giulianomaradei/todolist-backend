<?php
require_once(__DIR__."/System.php");

$nome_grupo    = (!empty($_POST['nome_grupo'])) ? $_POST['nome_grupo'] : '';
$nome    = (!empty($_POST['nome'])) ? $_POST['nome'] : '';
$link    = (!empty($_POST['link'])) ? $_POST['link'] : '';
$observacao      = (!empty($_POST['observacao'])) ? $_POST['observacao'] : '';

if(!empty($_POST['inserir'])){    
    $dados_itens = array();

    $cont = 0;
    foreach($nome as $conteudo){
        $dados_itens[$cont]['nome'] = $conteudo;
        $cont++;
    }

    $cont = 0;
    foreach($link as $conteudo){
        $dados_itens[$cont]['link'] = $conteudo;
        $cont++;
    }

    $cont = 0;
    foreach($observacao as $conteudo){
        $dados_itens[$cont]['observacao'] = $conteudo;
        $cont++;
    }

    $verificacao = DBRead('', 'tb_grupo_ferramenta', "WHERE nome = '".$nome_grupo."'");

    if(!$verificacao){
        inserir($nome_grupo, $dados_itens);
    }else{
        $alert = ('Item já existe na base de dados!');
        $alert_type = 'w';        header("location: /api/iframe?token=$request->token&view=ferramenta-form");
        exit;
    }

}else if(!empty($_POST['alterar'])){
    $id = (int)$_POST['alterar'];
    $dados_itens = array();

    $cont = 0;
    foreach($nome as $conteudo){
        $dados_itens[$cont]['nome'] = $conteudo;
        $cont++;
    }

    $cont = 0;
    foreach($link as $conteudo){
        $dados_itens[$cont]['link'] = $conteudo;
        $cont++;
    }

    $cont = 0;
    foreach($observacao as $conteudo){
        $dados_itens[$cont]['observacao'] = $conteudo;
        $cont++;
    }

    $verificacao = DBRead('', 'tb_grupo_ferramenta', "WHERE nome = '".$nome_grupo."' AND id_grupo_ferramenta != ".$id);

    if(!$verificacao){
        alterar($id, $nome_grupo, $dados_itens);
    }else{
        $alert = ('Item já existe na base de dados!');
        $alert_type = 'w';        header("location: /api/iframe?token=$request->token&view=ferramenta-form&alterar=$id");
        exit;
    }
    
} else if(isset($_GET['excluir'])){

    $id = (int)$_GET['excluir'];
    excluir($id);
}else{
    header("location: ../adm.php");
    exit;
}

function inserir($nome_grupo, $dados_itens){

    $dados = array(
        'nome' => $nome_grupo
    );

    $insertID = DBCreate('', 'tb_grupo_ferramenta', $dados, true);
    registraLog('Inserção de novo grupo de ferramentas.','i','tb_grupo_ferramenta',$insertID,"nome: $nome_grupo");

    foreach($dados_itens as $conteudo){

        $nome = $conteudo['nome'];
        $link = $conteudo['link'];
        $observacao = $conteudo['observacao'];

        $dados_item = array(
            'nome' => $nome,
            'link' => $link,
            'observacao' => $observacao,
            'id_grupo_ferramenta' => $insertID
        );
        
        $insertItem = DBCreate('', 'tb_grupo_ferramenta_item', $dados_item);
        registraLog('Inserção de novo item de grupo de ferramentas.','i','tb_grupo_ferramenta_item',$insertItem,"nome: $nome | link: $link | observacao: $observacao | id_grupo_ferramenta: $insertID");
    }
    
    $alert = ('Item inserido com sucesso!');
    $alert_type = 's';    header("location: /api/iframe?token=$request->token&view=ferramenta-busca");
    exit;
}

function alterar($id, $nome_grupo, $dados_itens){

    $dados = array(
        'nome' => $nome_grupo
    );
    DBUpdate('', 'tb_grupo_ferramenta', $dados, "id_grupo_ferramenta = $id");
    registraLog('Alteração de grupo de ferramentas.','a','tb_grupo_ferramenta',$id,"nome: $nome_grupo");

    DBDelete('', 'tb_grupo_ferramenta_item', "id_grupo_ferramenta = '$id'");
    foreach($dados_itens as $conteudo){
        $nome = $conteudo['nome'];
        $link = $conteudo['link'];
        $observacao = $conteudo['observacao'];

        $dados_item = array(
            'nome' => $nome,
            'link' => $link,
            'observacao' => $observacao,
            'id_grupo_ferramenta' => $id
        );
        
        $insertItem = DBCreate('', 'tb_grupo_ferramenta_item', $dados_item);

        registraLog('Alteração de item de grupo de ferramentas.','a','tb_grupo_ferramenta_item',$insertItem,"nome: $nome | link: $link | observacao: $observacao | id_grupo_ferramenta: $id");
    }

    
    $alert = ('Item alterado com sucesso!');
    $alert_type = 's';    header("location: /api/iframe?token=$request->token&view=ferramenta-busca");
    exit;
}

function excluir($id){
    $query = "DELETE FROM tb_grupo_ferramenta WHERE id_grupo_ferramenta = $id";
    $link = DBConnect('');
    $result = @mysqli_query($link, $query);
    DBClose($link);
    registraLog('Exclusão de Grupo de Ferramentas.','e','tb_grupo_ferramenta',$id,'');
    if(!$result){
$alert = ('Erro ao excluir item!');
        $alert_type = 'd';    }else{
        $alert = ('Item excluído com sucesso!');
    $alert_type = 's';    }
    header("location: /api/iframe?token=$request->token&view=ferramenta-busca");
    exit;
}

?>