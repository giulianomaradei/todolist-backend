<?php
require_once(__DIR__."/System.php");


$netapas = (!empty($_POST['netapas'])) ? $_POST['netapas'] : '';
$id_selecao = (!empty($_POST['id_selecao'])) ? $_POST['id_selecao'] : '';

if ($id_selecao != '' && ($netapas != '' && $netapas != 0)) {
    inserir($id_selecao, $netapas);
}

function inserir($id_selecao, $netapas){

    $dados = array();

    for ($i = 1; $i <= $netapas; $i++) {

        $avaliadoretapa = 'avaliadoretapa'.$i;
        $avaliarnota = 'avaliarnotaetapa'.$i;
        $darparecer = 'darpareceretapa'.$i;
        $tipo = 'tipoetapa'.$i;
        $descricao = 'descricaoetapa'.$i;

        $dados[$i][$avaliadoretapa] = (!empty($_POST[$avaliadoretapa])) ? $_POST[$avaliadoretapa] : '';
        $dados[$i][$avaliarnota] = (!empty($_POST[$avaliarnota])) ? $_POST[$avaliarnota] : '';
        $dados[$i][$darparecer] = (!empty($_POST[$darparecer])) ? $_POST[$darparecer] : '';
        $dados[$i][$tipo] = (!empty($_POST[$tipo])) ? $_POST[$tipo] : '';
        $dados[$i][$descricao] = (!empty($_POST[$descricao])) ? $_POST[$descricao] : '';
    }

    $cont_etapa = 1;
    $dados_etapa = array();
    $aray_avaliador_etapa = array();
    foreach ($dados as $conteudo_etapa ) {
        $cont_avaliador = 0;
        foreach ($conteudo_etapa['avaliadoretapa'.$cont_etapa] as $conteudo) {

            $aray_avaliador_etapa[$cont_avaliador]['id_avaliador'] = $conteudo;

            if (!in_array($cont_etapa, $dados_etapa)) { 
                $dados_etapa[$cont_etapa]['n_etapa'] = $cont_etapa;
                $dados_etapa[$cont_etapa]['avaliarnotaetapa'] = $conteudo_etapa['avaliarnotaetapa'.$cont_etapa];
                $dados_etapa[$cont_etapa]['darpareceretapa'] = $conteudo_etapa['darpareceretapa'.$cont_etapa];
                $dados_etapa[$cont_etapa]['tipoetapa'] = $conteudo_etapa['tipoetapa'.$cont_etapa];
                $dados_etapa[$cont_etapa]['descricaoetapa'] = $conteudo_etapa['descricaoetapa'.$cont_etapa];
            }

            $cont_avaliador++;
        }

        $cont_a = 1;
        foreach ($aray_avaliador_etapa as $conteudo) {

            //echo 'etapa: '.$cont_etapa.'- id_avaliador: '.$conteudo['id_avaliador'].'<hr>';
            $dados_etapa[$cont_etapa]['avaliadores'][$cont_a] = $conteudo['id_avaliador'];
            $cont_a++;
        }

        unset($aray_avaliador_etapa);
        $cont_etapa++;
    }

    $link = DBConnect('');
    DBBegin($link);

    $cont_a = 1;
    foreach ($dados_etapa as $conteudo_dados) {
    
        $num_etapa = $conteudo_dados['n_etapa'];
        $precisa_nota = $conteudo_dados['avaliarnotaetapa'];
        $precisa_parecer = $conteudo_dados['darpareceretapa'];
        $tipo = $conteudo_dados['tipoetapa'];
        $descricao = $conteudo_dados['descricaoetapa'];

        $dados_etapa = array(
            'num_etapa' => $num_etapa,
            'descricao' => $descricao,
            'precisa_nota' => $precisa_nota,
            'precisa_parecer' => $precisa_parecer,
            'tipo' => $tipo,
            'id_selecao' => $id_selecao
        );

        $insertID_etapa = DBCreateTransaction($link, 'tb_selecao_etapa', $dados_etapa, true);
        registraLogTransaction($link, 'Inserção de selecao etapa.', 'i', 'tb_selecao_etapa', $insertID_etapa, "num_etapa: $num_etapa | descricao: $descricao | precisa_nota: $precisa_nota | precisa_parecer: $precisa_parecer | tipo: $tipo | id_selecao: $id_selecao");

        foreach ($conteudo_dados['avaliadores'] as $key => $value) {
            $id_usuario_avaliador = $value;

            $dados_etapa = array(
                'id_selecao_etapa' => $insertID_etapa,
                'id_usuario_avaliador' => $id_usuario_avaliador
            );

            $insertID = DBCreateTransaction($link, 'tb_selecao_etapa_avaliador', $dados_etapa, true);
            registraLogTransaction($link, 'Inserção de selecao etapa avaliador.', 'i', 'tb_selecao_etapa', $insertID, "id_selecao_etapa: $insertID_etapa | id_usuario_avaliador: $id_usuario_avaliador");
        }
    }

    $dados_array = array(
        'status' => 1,
    );

    DBUpdateTransaction($link, 'tb_selecao', $dados_array, "id_selecao = '$id_selecao'");
    registraLogTransaction($link, 'Alteração de status selecao.', 'a', 'tb_selecao', $id_selecao, "status: 1");

    DBCommit($link);

    header("location: /api/iframe?token=$request->token&view=selecao-candidato-form&idselecao=".$id_selecao."");
    exit;
}

