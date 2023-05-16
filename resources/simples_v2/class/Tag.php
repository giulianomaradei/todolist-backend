<?php
require_once "System.php";

$acao = (isset($_POST['acao'])) ? $_POST['acao'] : '';
$parametros = (isset($_POST['parametros'])) ? $_POST['parametros'] : '';
$id_pessoa = $parametros['id_pessoa'];
$tags = $parametros['tags'];

if ($acao == 'inserir') {

    inserir($id_pessoa, $tags);
}

function inserir($id_pessoa, $tags)
{
    if ($id_pessoa != '' && $tags != '') {

        $array_tags = array();
        $arry_descricao = array();
        foreach ($tags as $conteudo) {
            
            $descricao = strtolower($conteudo);
            array_push($arry_descricao, $descricao);

            $check = DBRead('', 'tb_tag', "WHERE descricao = '".$descricao."'");

            if ($check) {
                array_push($array_tags, $check[0]['id_tag']);

            } else {

                $dados = array(
                    'descricao' => $descricao
                );

                $insertID = DBCreate('', 'tb_tag', $dados, true);
                registraLog('Inserção de nova tag.','i','tb_tag',$insertID,"descricao: $descricao");

                array_push($array_tags, $insertID);
            }
        }

        DBDelete('', 'tb_pessoa_rh_tag', "id_pessoa = $id_pessoa");

        foreach ($array_tags as $conteudo) {

            $dados = array(
                'id_pessoa' => $id_pessoa,
                'id_tag' => $conteudo
            );

            $insertID = DBCreate('', 'tb_pessoa_rh_tag', $dados, true);
            registraLog('Inserção de nova pessoa rh tag.','i','tb_pessoa_rh_tag', $insertID,"id_pessoa: $id_pessoa | id_tag: $conteudo");
        }

        echo json_encode($arry_descricao);

    } else {
       echo  json_encode(false);
    }
}