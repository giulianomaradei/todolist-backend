<?php
require_once(__DIR__."/System.php");
$nome = (!empty($_POST['nome'])) ? $_POST['nome'] : '';
$id_subarea_problema = (!empty($_POST['id_subarea_problema'])) ? $_POST['id_subarea_problema'] : '';
$descricao = (!empty($_POST['descricao'])) ? $_POST['descricao'] : '';


if (!empty($_POST['inserir'])) {

    $cont = 0;
    $dados_subarea = array();
    foreach($descricao as $conteudo){
        $dados_subarea[$cont]['descricao'] = $conteudo;
        $cont++;
    }
    
    $dados = DBRead('', 'tb_area_problema', "WHERE BINARY nome = '".addslashes($nome)."'");
    if (!$dados) {
        inserir($nome, $dados_subarea, $request);
    } else {        
        $alert = 'Item já existe na base de dados!';
        $alert_type = 'w';
        header("location: /api/iframe?token=$request->token&view=area-problema-form&alert=$alert&alert_type=$alert_type");
        exit;
    }

} else if (!empty($_POST['alterar'])) {
    $id = (int)$_POST['alterar'];

    $cont = 0;
    $dados_subarea = array();
    foreach($id_subarea_problema as $conteudo){
        $dados_subarea[$cont]['id_subarea_problema'] = $conteudo;
        $cont++;
    }
    $cont = 0;
    foreach($descricao as $conteudo){
        $dados_subarea[$cont]['descricao'] = $conteudo;
        $cont++;
    }
   
    $dados = DBRead('', 'tb_area_problema', "WHERE BINARY nome = '".addslashes($nome)."' AND id_area_problema != '$id'");
    if (!$dados) {
        alterar($id, $nome, $dados_subarea, $request);
    } else {
        $alert = 'Item já existe na base de dados!';
        header("location: /api/iframe?token=".$request->token."&view=area-problema-form&alterar=$id&alert=".$alert."&alert_type=w");
        exit;
    }

} else if (isset($_GET['excluir'])) {

    $id = (int)$_GET['excluir'];
    excluir($id, $request);

} else if (!empty($_POST['excluir_subarea'])) {

    $id = (int)$_POST['excluir_subarea'];
    excluir_subarea($id);

}else{
    header("location: /api/iframe?token=$request->token&view=area-problema-busca");
    exit;
}

function inserir($nome, $dados_subarea, $request){

if($nome != ""){

    $dados = array(
        'nome' => $nome
    );
    $insertID = DBCreate('', 'tb_area_problema', $dados, true);
    registraLog('Inserção de área de problema.','i','tb_area_problema',$insertID,"nome: $nome");

    foreach($dados_subarea as $conteudo){
        if($conteudo['descricao']){
            $descricao = $conteudo['descricao'];
            $dadosSubarea = array(
                'descricao' => $descricao,
                'id_area_problema' => $insertID
            );
                
            $insertSubarea = DBCreate('', 'tb_subarea_problema', $dadosSubarea);
            registraLog('Inserção de nova subarea.','i','tb_subarea_problema',$insertSubarea,"descricao: $descricao | id_area_problema: $insertID");
        }
    }

    $alert = 'Item inserido com sucesso!';
    $alert_type = 's';
    header("location: /api/iframe?token=$request->token&view=area-problema-busca&alert=$alert&alert_type=$alert_type");

}else{

        $alert = 'Não foi possível inserir o item!';
        $alert_type = 'w';
        header("location: /api/iframe?token=$request->token&view=area-problema-form&alert=$alert&alert_type=$alert_type");

}
    
    exit;

}

function alterar($id, $nome, $dados_subarea, $request){

    if($nome != "" && $nome){
        $dados = array(
            'nome' => $nome
        );
        DBUpdate('', 'tb_area_problema', $dados, "id_area_problema = $id");
        registraLog('Alteração de área de problema.','a','tb_area_problema',$id,"nome: $nome");

        foreach($dados_subarea as $conteudo){
            if($conteudo['descricao']){
                $descricao = $conteudo['descricao'];
                if($conteudo['id_subarea_problema'] != -1){
                    $dadosSubarea = array(
                        'id_subarea_problema' => $conteudo['id_subarea_problema'],
                        'descricao' => $descricao,
                        'id_area_problema' => $id
                    );
                    $insertID = DBUpdate('', 'tb_subarea_problema', $dadosSubarea, "id_subarea_problema = '".$conteudo['id_subarea_problema']."'");
                    registraLog('Inserção de subárea.','a','tb_subarea_problema',$insertID,"descricao: $descricao | id_area_problema: $id");
                }else{
                    $dadosSubarea = array(
                        'descricao' => $descricao,
                        'id_area_problema' => $id
                    );
                    $insertID = DBCreate('', 'tb_subarea_problema', $dadosSubarea);
                    registraLog('Alteração de subárea.','a','tb_subarea_problema',$insertID,"descricao: $descricao | id_area_problema: $id");
                }
            }
        }

        $alert = 'Item alterado com sucesso!';
        $alert_type = 's';
        header("location: /api/iframe?token=$request->token&view=area-problema-busca&alert=$alert&alert_type=$alert_type");

    }else{

            $alert = 'Não foi possível alterar o item!';
            $alert_type = 'w';
            header("location: /api/iframe?token=$request->token&view=area-problema-form&alterar=$id&alert=$alert&alert_type=$alert_type");
    }
        
        exit;

    }

function excluir_subarea($id){
    $query = "DELETE FROM tb_subarea_problema WHERE id_subarea_problema = $id";
    $link = DBConnect('');
    $result = @mysqli_query($link, $query);
    DBClose($link);
    registraLog('Exclusão de subárea de problema.','e','tb_subarea_problema',$id,'');
    if(!$result){
        echo '0';
    }else{
        echo '1';
    }
}

function excluir($id, $request){
    
    $query = "DELETE FROM tb_area_problema WHERE id_area_problema = $id";
    $link = DBConnect('');
    $result = @mysqli_query($link, $query);
    DBClose($link);

    registraLog('Exclusão de área de problema.','e','tb_area_problema',$id,'');
    if(!$result){
       $alert = 'Erro ao excluir item!';
       $alert_type = 'd';
    }else{
       $alert = 'Item excluído com sucesso!';
       $alert_type = 's';
    }
    header("location: /api/iframe?token=$request->token&view=area-problema-busca&alert=$alert&alert_type=$alert_type");
    exit;

}

?>
