<?php
require_once(__DIR__."/System.php");


$titulo = (!empty($_POST['titulo'])) ? $_POST['titulo'] : '';
$conteudo = (!empty($_POST['conteudo'])) ? $_POST['conteudo'] : '';
$id_pai = (!empty($_POST['id_pai'])) ? $_POST['id_pai'] : 0;
$id_categoria = (!empty($_POST['id_categoria'])) ? $_POST['id_categoria'] : '';
$data_criacao = (!empty($_POST['data_criacao'])) ? $_POST['data_criacao'] : '';
$data_atualizacao = (!empty($_POST['data_atualizacao'])) ? $_POST['data_atualizacao'] : '';
$status = (!empty($_POST['status'])) ? $_POST['status'] : 0;

$perfis = (!empty($_POST['perfil_sistema'])) ? $_POST['perfil_sistema'] : '';

$origem_topico = (!empty($_POST['origem_topico'])) ? $_POST['origem_topico'] : '';

$permissao_comentario = (!empty($_POST['permissao_comentario'])) ? $_POST['permissao_comentario'] : '0';

if (!empty($_POST['inserir'])){

    inserir($titulo, $conteudo, $id_categoria, $perfis, $origem_topico, $permissao_comentario);

} else if(!empty($_POST['alterar'])){
    $id = (int)$_POST['alterar'];
   
    alterar($id, $titulo, $conteudo, $id_pai, $id_categoria, $perfis, $permissao_comentario);

} else if(isset($_GET['excluir'])){

    $id = (int)$_GET['excluir'];
    excluir($id);

}else if(!empty($_POST['inserirComentario'])){

    $id = (int)$_POST['inserirComentario'];
    inserirComentario($id, $conteudo, $id_pai, $id_categoria);
    header("location: /api/iframe?token=$request->token&view=topico-exibe&id=$id");
    exit;
}else if(isset($_GET['excluirComentario'])){
    $id = (int)$_GET['excluirComentario'];
    excluirComentario($id);
}else{
    header("location: ../adm.php");
    exit;
}

function inserir($titulo, $conteudo, $id_categoria, $perfis, $origem_topico, $permissao_comentario){

    $usuario = $_SESSION['id_usuario'];
    if($titulo != "" && $conteudo != "" && $id_categoria != "" && $perfis != ""){
        $data_criacao = getDataHora();
        $dados = array(
            'titulo' => $titulo,
            'conteudo' => $conteudo,
            'id_categoria' => $id_categoria,
            'id_pai' => 0,
            'id_usuario' => $usuario,
            'data_criacao' => $data_criacao,
            'status' => 1,
            'permissao_comentario' => $permissao_comentario
        );
        $insertID = DBCreate('', 'tb_topico', $dados, true);
        registraLog('Inserção de tópico.','i','tb_topico',$insertID,"titulo: $titulo | conteudo: $conteudo | id_categoria: $id_categoria | id_usuario: $usuario | data_criacao: $data_criacao | status: 1 | permissao_comentario: $permissao_comentario");

        $dados_visualizado = array(
            'id_topico' => $insertID,
            'data_visualizado' => getDataHora(),
            'data_lido' => getDataHora(),
            'id_usuario' => $usuario
        );
        DBCreate('', 'tb_topico_visualizado', $dados_visualizado);
        foreach($perfis as $perfil){
            $dados_perfil_topico = array(
                'id_perfil_sistema' => $perfil,
                'id_topico' => $insertID
            );
            DBCreate('', 'tb_perfil_topico', $dados_perfil_topico, true);
        }
        $alert = ('Item inserido com sucesso!');
    $alert_type = 's';        if($origem_topico == "1"){
            header("location: /api/iframe?token=$request->token&view=home&dash=topicos");
        }else{
            header("location: /api/iframe?token=$request->token&view=topico-busca");
        }
    }else{
        $alert = ('Não foi possível inserir o item!');
        $alert_type = 'w';                header("location: /api/iframe?token=$request->token&view=topico-form");
    }
    exit;
}

function inserirComentario($id, $conteudo, $id_pai, $id_categoria){

    $data_criacao = getDataHora();
    $usuario = $_SESSION['id_usuario'];

    $dados = array(
        'conteudo' => $conteudo,
        'id_pai' => $id_pai,
        'id_categoria' => $id_categoria,
        'id_usuario' => $_SESSION['id_usuario'],
        'data_criacao' => getDataHora(),
        'status' => 1
    );

    if($conteudo != ""){
        $insertID = DBCreate('', 'tb_topico', $dados, true);

        $dados_visualizado = array(
            'id_topico' => $insertID,
            'data_visualizado' => getDataHora(),
            'id_usuario' => $usuario
        );
        DBCreate('', 'tb_topico_visualizado', $dados_visualizado);

        registraLog('Inserção de tópico.','i','tb_topico',$insertID,"conteudo: $conteudo | id_pai: $id_pai | id_categoria: $id_categoria | id_usuario: $usuario | status: 0");
        $alert = ('Item inserido com sucesso!');
    $alert_type = 's';        header("location: /api/iframe?token=$request->token&view=topico-exibe&id=$id");
    }else{
        $alert = ('Erro ao inserir item!','d');
        header("location: /api/iframe?token=$request->token&view=topico-exibe&id=$id");
    }

    exit;
}

function alterar($id, $titulo, $conteudo, $id_pai, $id_categoria, $perfis, $permissao_comentario){
    $dados_topico = DBRead('', 'tb_topico', "WHERE id_topico = '$id'");

    if($_SESSION['id_usuario'] == $dados_topico[0]['id_usuario']){

        if($titulo != "" && $conteudo != "" && $id_categoria != "" && $perfis != ""){

            $data_atualizacao = getDataHora();
            $dados = array(
                'titulo' => $titulo,
                'conteudo' => $conteudo,
                'id_pai' => $id_pai,
                'id_categoria' => $id_categoria,
                'permissao_comentario' => $permissao_comentario
            );
            DBUpdate('', 'tb_topico', $dados, "id_topico = $id");
            registraLog('Alteração de conteúdo do tópico.','a','tb_topico',$id,"titulo: $titulo | conteudo: $conteudo | id_pai: $id_pai | id_categoria: $id_categoria");

        }else{

            $alert = 'Não foi possível alterar o item!' ;
        $alert_type = 'w';                    header("location: /api/iframe?token=$request->token&view=topico-form&alterar=$id");
            exit;
        }

        DBDelete('','tb_perfil_topico',"id_topico = '$id'");
        foreach($perfis as $perfil){
            $dados_perfil_topico = array(
                'id_perfil_sistema' => $perfil,
                'id_topico' => $id
            );
            $perfil_topico = DBCreate('', 'tb_perfil_topico', $dados_perfil_topico, true);
            registraLog('Inserção de perfil de tópico.', 'i', 'tb_perfil_topico', $perfil_topico, "id_perfil_sistema: $perfil | id_topico: $id");
        }
        $alert = ('Item alterado com sucesso!');
    $alert_type = 's';        header("location: /api/iframe?token=$request->token&view=topico-busca");
        exit;
    }else{
        $alert = ('Você não possui permissão para alterar este tópico!','w');
        header("location: /api/iframe?token=$request->token&view=topico-busca");
        exit;
    }
}

function excluir($id){
    $dados = array(
        'status' => '2',
    );
    DBUpdate('', 'tb_topico', $dados, "id_topico = $id");
    registraLog('Exclusão de tópico do fórum.', 'e', 'tb_topico', $id, '');
    $alert = ('Item excluído com sucesso!', 's');
    header("location: /api/iframe?token=$request->token&view=topico-busca");
    exit;
}

function excluirComentario($id){
    $dados = array(
        'status' => '2',
    );
    $dados_comentario = DBRead('', 'tb_topico', "WHERE id_topico = '$id'");
    $id_pai = $dados_comentario[0]['id_pai'];
    DBUpdate('', 'tb_topico', $dados, "id_topico = $id");
    registraLog('Exclusão de tópico do fórum.', 'e', 'tb_topico', $id, '');
    $alert = ('Item excluído com sucesso!', 's');
    header("location: /api/iframe?token=$request->token&view=topico-exibe&id=$id_pai");
    exit;
}

?>