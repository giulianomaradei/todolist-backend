<?php
require_once(__DIR__."/System.php");

$nome = (!empty($_POST['nome_senha'])) ? $_POST['nome_senha'] : '';
$usuario = (!empty($_POST['usuario'])) ? $_POST['usuario'] : '';
$senha = (!empty($_POST['senha'])) ? $_POST['senha'] : '';
$link= (!empty($_POST['link'])) ? $_POST['link'] : '';
$id_usuario = $_SESSION['id_usuario'];
$dados_perfil = DBRead('','tb_usuario', "WHERE id_usuario = '".$_SESSION['id_usuario']."' ", 'id_perfil_sistema');
$id_perfil = $dados_perfil[0]['id_perfil_sistema'];
$tipo_senha= (!empty($_POST['tipo_senha'])) ? $_POST['tipo_senha'] : '';

if($senha){
    $senha = base64_encode($senha);
}

if (!empty($_POST['inserir'])) {
    
    $dados = DBRead('', 'tb_senha', "WHERE BINARY nome_senha = '".addslashes($nome)."'");

    if (!$dados) {
        inserir($id_usuario, $nome, $usuario, $senha, $link, $id_perfil, $tipo_senha);

    } else {        
        $alert = ('Item já existe na base de dados!');
        $alert_type = 'w';        header("location: /api/iframe?token=$request->token&view=gerenciar-senhas-form");

        exit;
    }


} else if (!empty($_POST['alterar'])) {
    $id = (int)$_POST['alterar'];

    $dados = DBRead('', 'tb_senha', "WHERE id_senha = $id");

    if ($dados) {
        alterar($id, $id_usuario, $nome, $usuario, $senha, $link, $id_perfil, $tipo_senha);

    } else {
        $alert = ('Item já existe na base de dados!');
        $alert_type = 'w';        header("location: /api/iframe?token=$request->token&view=gerenciar-senhas-form&alterar=$id");

        exit;
    }

} else if (isset($_GET['excluir'])) {

    $id = (int)$_GET['excluir'];
    excluir($id);

}else{
    header("location: ../adm.php");
    exit;
}
    

function inserir($id_usuario, $nome, $usuario, $senha, $link, $id_perfil, $tipo_senha){

    if($nome != "" | $usuario != "" | $senha != "" | $id_usuario != ""){
        
        $dados = array(
            'id_usuario' => $id_usuario,
            'nome_senha' => $nome,
            'usuario' => $usuario,
            'senha' => $senha,
            'link' => $link,
            'perfil' => $id_perfil,
            'tipo_senha' => $tipo_senha
        );

        $insertID = DBCreate('', 'tb_senha', $dados, true);
        registraLog('Inserção de Acesso.','i','tb_senha',$insertID,"nome: $nome");
        $alert = ('Item inserido com sucesso!');
    $alert_type = 's';        header("location: /api/iframe?token=$request->token&view=gerenciar-senhas-busca");

    }else{
            $alert = ('Não foi possível inserir o item!');
        $alert_type = 'w';                    header("location: /api/iframe?token=$request->token&view=gerenciar-senhas-form");
    }     
        exit;
}

function alterar($id, $id_usuario, $nome, $usuario, $senha, $link, $id_perfil, $tipo_senha){
  
    $dados = array(
        'id_usuario' => $id_usuario,
        'nome_senha' => $nome,
        'usuario' => $usuario,
        'senha' => $senha,
        'link' => $link,
        'perfil' => $id_perfil,
        'tipo_senha' => $tipo_senha
    );

    DBUpdate('', 'tb_senha', $dados, "id_senha = $id");
    registraLog('Alteração de acesso.','a','tb_acesso',$id,"nome_acesso: $nome");
    $alert = ('Item alterado com sucesso!');
    $alert_type = 's';    header("location: /api/iframe?token=$request->token&view=gerenciar-senhas-busca");

    
    exit;

}

function excluir($id){
        $query = "DELETE FROM tb_senha WHERE id_senha = $id";
        $link = DBConnect('');
        $result = @mysqli_query($link, $query);
        DBClose($link);
        registraLog('Exclusão da acesso.','e','tb_senha',$id,'');

        if(!$result){
            $alert = ('Erro ao excluir item!');
            $alert_type = 'd';
        }else{
            $alert = ('Item excluído com sucesso!');
    $alert_type = 's';        }
        header("location: /api/iframe?token=$request->token&view=gerenciar-senhas-busca");

        exit;

}
?>