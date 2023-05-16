<?php
require_once(__DIR__."/System.php");


$descricao = (!empty($_POST['descricao'])) ? $_POST['descricao'] : '';
$posicao = (!empty($_POST['posicao'])) ? $_POST['posicao'] : '';
$resposta_pai = 0;
$observacao = (!empty($_POST['observacao'])) ? $_POST['observacao'] : '';
$id_pesquisa = (!empty($_POST['id_pesquisa'])) ? $_POST['id_pesquisa'] : '';
$id_tipo_resposta = (!empty($_POST['id_tipo_resposta_pesquisa'])) ? $_POST['id_tipo_resposta_pesquisa'] : 0;
$pergunta_a_ser_clonada = (!empty($_POST['pergunta_a_ser_clonada'])) ? $_POST['pergunta_a_ser_clonada'] : '';
$descricao_resposta = (!empty($_POST['descricao_resposta'])) ? $_POST['descricao_resposta'] : '';

$posicao_resposta = (!empty($_POST['posicao_resposta'])) ? $_POST['posicao_resposta'] : '';
$id_pergunta_pesquisa = (!empty($_POST['id_pergunta_pesquisa'])) ? $_POST['id_pergunta_pesquisa'] : '';
$id_resposta_pesquisa = (!empty($_POST['id_resposta_pesquisa'])) ? $_POST['id_resposta_pesquisa'] : '';

$escala_de = (!empty($_POST['escala_de'])) ? $_POST['escala_de'] : '';

$escala_ate = (!empty($_POST['escala_ate'])) ? $_POST['escala_ate'] : '';

if(!empty($_POST['inserir'])){

    $cont = 0;
    $dados_resposta = array();
    foreach($descricao_resposta as $conteudo){
        $dados_resposta[$cont]['descricao'] = $conteudo;
        $cont++;
    }

    $cont = 0;
    foreach($posicao_resposta as $conteudo){
        $dados_resposta[$cont]['posicao'] = $conteudo;
        $cont++;
    }

    $cont = 0;
    foreach($id_resposta_pesquisa as $conteudo){
        $dados_resposta[$cont]['id_resposta_pesquisa'] = $conteudo;
        $cont++;
    }

    $verifica_posicao = DBRead('', 'tb_pergunta_pesquisa', "WHERE posicao = '".$posicao."' AND id_pesquisa = '".$id_pesquisa."'");

    if($descricao != "" && !$verifica_posicao){
        if($posicao <= 0){
            $alert = ('Erro ao inserir item!','d');
            header("location: /api/iframe?token=$request->token&view=gerenciar-pesquisa-pergunta-form&id_pesquisa=$id_pesquisa");
            exit;
        }else{
            inserir($descricao, $posicao, $resposta_pai, $observacao, $id_pesquisa, $id_tipo_resposta, $dados_resposta, $escala_de, $escala_ate);
        }
    }else{
        $alert = ('Erro ao inserir item!','d');
        header("location: /api/iframe?token=$request->token&view=gerenciar-pesquisa-pergunta-form&id_pesquisa=$id_pesquisa");
        exit;
    }

//###################### INSERIR #############################################

}else if(!empty($_POST['clonar'])){

    $cont = 0;
    $dados_resposta = array();
    foreach($descricao_resposta as $conteudo){
        $dados_resposta[$cont]['descricao'] = $conteudo;
        $cont++;
    }

    $cont = 0;
    foreach($posicao_resposta as $conteudo){
        $dados_resposta[$cont]['posicao'] = $conteudo;
        $cont++;
    }

    $cont = 0;
    foreach($id_resposta_pesquisa as $conteudo){
        $dados_resposta[$cont]['id_resposta_pesquisa'] = $conteudo;
        $cont++;
    }

    $verifica_descricao = DBRead('', 'tb_pergunta_pesquisa', "WHERE descricao = '".$descricao."' AND id_pesquisa = '".$id_pesquisa."'");

    if($descricao != "" && !$verifica_descricao){
        if($posicao <= 0){
            $alert = ('Erro ao inserir item!','d');
            header("location: /api/iframe?token=$request->token&view=gerenciar-pesquisa-pergunta-form-clonar&id_pergunta_pesquisa=$pergunta_a_ser_clonada");
            exit;
        }else{
            inserir($descricao, $posicao, $resposta_pai, $observacao, $id_pesquisa, $id_tipo_resposta, $dados_resposta, $escala_de, $escala_ate);
        }
    }else{
        $alert = ('Erro ao inserir item!','d');
        header("location: /api/iframe?token=$request->token&view=gerenciar-pesquisa-pergunta-busca&alterar=$id_pesquisa");
        exit;
    }

}else if(!empty($_POST['alterar'])){
    $id = (int)$_POST['alterar'];

    $cont = 0;
    $dados_resposta = array();
    foreach($descricao_resposta as $conteudo){
        $dados_resposta[$cont]['descricao'] = $conteudo;
        $cont++;
    }
    $cont = 0;
    foreach($posicao_resposta as $conteudo){
        $dados_resposta[$cont]['posicao'] = $conteudo;
        $cont++;
    }
    $cont = 0;
    foreach($id_resposta_pesquisa as $conteudo){
        $dados_resposta[$cont]['id_resposta_pesquisa'] = $conteudo;
        $cont++;
    }

    $verifica_posicao = DBRead('', 'tb_pergunta_pesquisa', "WHERE posicao = '".$posicao."' AND id_pergunta_pesquisa != '".$id."' AND id_pesquisa = '".$id_pesquisa."'");

    if($descricao != "" && !$verifica_posicao){
        if($posicao <= 0){
            $alert = ('Erro ao alterar item!','d');
            header("location: /api/iframe?token=$request->token&view=gerenciar-pesquisa-pergunta-form&alterar=$id");
            exit;
        }else{
            alterar($id, $descricao, $posicao, $resposta_pai, $observacao, $id_pesquisa, $id_tipo_resposta, $dados_resposta, $escala_de, $escala_ate);
        }
    }else{
        $alert = ('Erro ao alterar item!','d');
        header("location: /api/iframe?token=$request->token&view=gerenciar-pesquisa-pergunta-form&alterar=$id");
        exit;
    }

}else if(isset($_GET['excluir'])){

    $id = (int)$_GET['excluir'];
    echo $id;

    $pesquisa = $_GET['id_pesquisa'];
    excluir($id, $pesquisa);

}else{
    header("location: ../adm.php");
    exit;
}

function inserir($descricao, $posicao, $resposta_pai, $observacao, $id_pesquisa, $id_tipo_resposta, $dados_resposta, $escala_de, $escala_ate){

    $dados = array(
        'descricao' => $descricao,
        'posicao' => $posicao,
        'resposta_pai' => $resposta_pai,
        'observacao' => $observacao,
        'id_pesquisa' => $id_pesquisa,
        'id_tipo_resposta_pesquisa' => $id_tipo_resposta
    );
    $insertID = DBCreate('', 'tb_pergunta_pesquisa', $dados, true);
    registraLog('Inserção de pergunta.','i','tb_pergunta_pesquisa',$insertID,"descricao: $descricao | posicao: $posicao | resposta_pai: $resposta_pai | observacao: $observacao | id_pesquisa: $id_pesquisa | id_tipo_resposta_pesquisa: $id_tipo_resposta");

    if(!$escala_de){
        $escala_de = '0';
    }

    //ESSE É IF DO ALTERAR
    if(($escala_de || $escala_de == 0) && $escala_ate && $id_tipo_resposta == '4'){

        for($i=$escala_de;$i<=$escala_ate;$i++){

            $dadosResposta = array(
                    'descricao' => $i,
                    'posicao' => $i,
                    'id_pergunta_pesquisa' => $insertID
                );
                
                $insertResposta = DBCreate('', 'tb_resposta_pesquisa', $dadosResposta);
                registraLog('Inserção de nova resposta.','i','tb_resposta_pesquisa',$insertResposta,"descricao: $descricao | posicao: $posicao | id_pergunta_pesquisa: $insertID");
        }

    }else{
        foreach($dados_resposta as $conteudo){
            if($conteudo['posicao'] && $conteudo['posicao'] >= 1){
                $dadosResposta = array(
                    'descricao' => $conteudo['descricao'],
                    'posicao' => $conteudo['posicao'],
                    'id_pergunta_pesquisa' => $insertID
                );
                        
                $insertResposta = DBCreate('', 'tb_resposta_pesquisa', $dadosResposta);
                registraLog('Inserção de nova resposta.','i','tb_resposta_pesquisa',$insertResposta,"descricao: $descricao | posicao: $posicao | id_pergunta_pesquisa: $insertID");
            }
        }
    }
    $alert = ('Item inserido com sucesso!');
    $alert_type = 's';    header("location: /api/iframe?token=$request->token&view=gerenciar-pesquisa-pergunta-busca&alterar=$id_pesquisa");
    exit;
}

function alterar($id, $descricao, $posicao, $resposta_pai, $observacao, $id_pesquisa, $id_tipo_resposta, $dados_resposta, $escala_de, $escala_ate){

    $dados = array(
        'descricao' => $descricao,
        'posicao' => $posicao,
        'resposta_pai' => $resposta_pai,
        'observacao' => $observacao,
        'id_pesquisa' => $id_pesquisa,
        'id_tipo_resposta_pesquisa' => $id_tipo_resposta,
        'status' => '1'

    );
    DBUpdate('', 'tb_pergunta_pesquisa', $dados, "id_pergunta_pesquisa = $id");
    DBDelete('', 'tb_resposta_pesquisa', "id_pergunta_pesquisa = '$id'");
    registraLog('Alteração de pergunta.','a','tb_pergunta_pesquisa',$id,"descricao: $descricao | posicao: $posicao | resposta_pai: $resposta_pai | observacao: $observacao | id_pesquisa: $id_pesquisa | id_tipo_resposta_pesquisa: $id_tipo_resposta");

    if(!$escala_de){
        $escala_de = 0;
    }

    if(($escala_de || $escala_de == 0) && $escala_ate && $id_tipo_resposta == '4'){

        for($i=$escala_de;$i<=$escala_ate;$i++){

            $dadosResposta = array(
                    'descricao' => $i,
                    'posicao' => $i,
                    'id_pergunta_pesquisa' => $id
                );
                   
                $insertResposta = DBCreate('', 'tb_resposta_pesquisa', $dadosResposta);
                registraLog('Inserção de nova resposta.','i','tb_resposta_pesquisa',$insertResposta,"descricao: $descricao | posicao: $posicao | id_pergunta_pesquisa: $id");
        }
    $alert = ('Item alterado com sucesso!');
    $alert_type = 's';    header("location: /api/iframe?token=$request->token&view=gerenciar-pesquisa-pergunta-busca&alterar=$id_pesquisa");
        exit;


    }else{

        foreach($dados_resposta as $conteudo){
            if($conteudo['posicao'] && $conteudo['posicao'] >= 1){

                if($conteudo['id_resposta_pesquisa']){
                    $dadosResposta = array(
                        'id_resposta_pesquisa' => $conteudo['id_resposta_pesquisa'],
                        'descricao' => $conteudo['descricao'],
                        'posicao' => $conteudo['posicao'],
                        'id_pergunta_pesquisa' => $id
                    );
                }else{
                    $dadosResposta = array(
                        'descricao' => $conteudo['descricao'],
                        'posicao' => $conteudo['posicao'],
                        'id_pergunta_pesquisa' => $id
                    );
                }

                $insertResposta = DBCreate('', 'tb_resposta_pesquisa', $dadosResposta);
                registraLog('Inserção de nova resposta.','i','tb_resposta_pesquisa',$insertResposta,"descricao: $descricao | posicao: $posicao | id_pergunta_pesquisa: $id");
            }
        }
    }
    $alert = ('Item alterado com sucesso!');
    $alert_type = 's';    header("location: /api/iframe?token=$request->token&view=gerenciar-pesquisa-pergunta-busca&alterar=$id_pesquisa");
    exit;

}

function excluir($id, $id_pesquisa){

    $dados = array(
        
        'posicao' => '0',
        'status' => '0'

    );
    DBUpdate('', 'tb_pergunta_pesquisa', $dados, "id_pergunta_pesquisa = $id");
    $alert = ('Item excluído com sucesso!');
    $alert_type = 's';    header("location: /api/iframe?token=$request->token&view=gerenciar-pesquisa-pergunta-busca&alterar=$id_pesquisa");
        exit;


}

?>
