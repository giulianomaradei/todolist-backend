<?php
require_once(__DIR__."/System.php");
require_once(__DIR__."/RdApi.php");


$id_lead_negocio = (!empty($_POST['id_lead_negocio'])) ? $_POST['id_lead_negocio'] : '';
$tags = (!empty($_POST['tags'])) ? $_POST['tags'] : '';
$negocio_sinalizado = (!empty($_POST['negocio_sinalizado'])) ? $_POST['negocio_sinalizado'] : '0';

if ($id_lead_negocio) {
    $dados_conversao = DBRead('', 'tb_rd_conversao', "WHERE id_lead_negocio = $id_lead_negocio");

    if ($dados_conversao) {

        $uuid = $dados_conversao[0]['uuid'];
        //$uuid = '90b5864c-d40f-46f8-829a-6b9fa3ee3737';

        if (!$uuid) {
            $alert = ('Lead não possui UUID (identificador RD) cadastrado em nossa base!','d');
            header("location: /api/iframe?token=$request->token&view=lead-negocio-perdido-ganho");
            exit;
        
        } else {

            $array_tags = '[';
            foreach($tags as $key => $conteudo) {

                if ($key === array_key_last($tags)) {
                    $array_tags .= '"'.$conteudo.'"';
                } else {
                    $array_tags .= '"'.$conteudo.'", ';
                }
            }
            $array_tags .= ']';

            $parametros = '
                            {
                                "tags": '.$array_tags.'
                            }
                        ';

            $result = updateLead($uuid, $parametros);

            if ($result['alert'] == 'success') {

                $dados = array(
                    'sinalizacao_rd' => $negocio_sinalizado,
                );

                DBUpdate('', 'tb_lead_negocio', $dados, "id_lead_negocio = $id_lead_negocio");
                registraLog('Alteração sinalizacao RD lead Negócio','a','tb_lead_negocio', $id_lead_negocio,"sinalizacao_rd: $negocio_sinalizado");

                $alert = ('Tags inseridas com sucesso e negócio sinalizado!','s');
                header("location: /api/iframe?token=$request->token&view=lead-conversao-busca");
                exit;

            } else {
                $alert = ('Erro ao inserir tags!','d');
                header("location: /api/iframe?token=$request->token&view=lead-conversao-busca");
                exit;
            } //end else result success

        }//end else uuid

    } else {
        $alert = ('Não foi encontrado lead para este negócio!','w');
        header("location: /api/iframe?token=$request->token&view=lead-conversao-busca");
        exit;

    } //end else dados conversao
}


