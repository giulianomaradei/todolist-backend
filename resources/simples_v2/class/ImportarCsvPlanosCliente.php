<?php
require_once(__DIR__."/System.php");

$id_contrato = (int)$_POST['id_contrato'];
$ativacao = (int)$_POST['ativacao'];
$id_plano = (int)(!empty($_POST['id_plano'])) ? $_POST['id_plano'] : -1;
importar($id_contrato, $id_plano);

function importar($id_contrato, $id_plano){

    $cont_erro = 0;
    $cont_sucesso = 0;

    $ext = substr($_FILES['filename']['name'], strrpos($_FILES['filename']['name'], '.'));

    if(is_uploaded_file($_FILES['filename']['tmp_name']) && $ext == ".csv"){
        
        $handle = fopen($_FILES['filename']['tmp_name'], "r");

        $dados_plano_contrato = array(
            'id_contrato_plano_pessoa' => $id_contrato
        );

        $contrato_existe = DBRead('', 'tb_plano_cliente_contrato', "WHERE id_contrato_plano_pessoa = '$id_contrato' ");

        if($id_plano == -1){
            if(!$contrato_existe){
                $insertPlano = DBCreate('', 'tb_plano_cliente_contrato', $dados_plano_contrato, true);
                registraLog('Inserção de novo plano cliente contrato.', 'i', 'tb_plano_cliente_contrato', $insertPlano, "id_contrato_plano_pessoa: $id_contrato");

            }else{
                $alert = ('Item já existe na base de dados!');
        $alert_type = 'w';                header("location: /api/iframe?token=$request->token&view=plano-cliente-busca");
                exit;
            }
        }

        while(($data = fgetcsv($handle, 1000, ';')) !== FALSE){

            $data = implode(",", $data);
            $data = str_replace(',', ';', $data);
            $data = explode(";", $data);

            $download = $data[0];
            $upload = $data[1];
            $descricao = $data[2];
            $observacao = $data[3];
            $id_contrato_plano_pessoa = $id_contrato;

            //Variável que verifica se existe um contato com status_pesquisa = 0, caso sim, não permite importar o mesmo contato novamente.
            $contato_repetido = DBRead('', 'tb_plano_cliente', "WHERE download = '$download' AND upload = '$upload' AND descricao = '$descricao' AND observacao = '$observacao'");
            //$contato_repetido = true;
            $duplicado = 0;
            if($contato_repetido){
                $duplicado = 1;
            }

            if(!$download || !$upload || !$descricao || $duplicado == 1){
                $cont_erro++;
            }else{

                if($id_plano != -1){

                    $dados = array(
                        'download' => $download,
                        'upload' => $upload,
                        'descricao' => $descricao,
                        'observacao' => $observacao,
                        'id_plano_cliente_contrato' => $id_plano
                    );

                    $insertID = DBCreate('', 'tb_plano_cliente', $dados, true);
                    registraLog('Inserção de novo plano cliente.', 'i', 'tb_plano_cliente', $insertID, "download: $download | upload: $upload | descricao: $descricao | observacao: $observacao | id_contrato_plano_pessoa: $id_contrato_plano_pessoa");
                }else{
                    $dados = array(
                        'download' => $download,
                        'upload' => $upload,
                        'descricao' => $descricao,
                        'observacao' => $observacao,
                        'id_plano_cliente_contrato' => $insertPlano
                    );

                    $insertID = DBCreate('', 'tb_plano_cliente', $dados, true);
                    registraLog('Inserção de novo plano cliente.', 'i', 'tb_plano_cliente', $insertID, "download: $download | upload: $upload | descricao: $descricao | observacao: $observacao | id_contrato_plano_pessoa: $id_contrato_plano_pessoa");
                }
                
                $cont_sucesso++;
            }
        }

        fclose($handle);
        if($cont_sucesso && !$cont_erro){
            $alert = ("$cont_sucesso itens inseridos com sucesso e nenhum erro!", 's');
        }else if($cont_sucesso && $cont_erro){
            $alert = ("$cont_sucesso itens inseridos com sucesso, porém houve $cont_erro erros!", 'w');
        }else if(!$cont_sucesso && $cont_erro){
            $alert = ("Nenhum contato foi importado, houve $cont_erro erros!", 'd');
        }else{
            $alert = ("Nenhum contato foi importado!", 'd');
        }
        if($id_plano == -1){
            header("location: /api/iframe?token=$request->token&view=plano-cliente-form&alterar=$insertPlano");
        }else{
            header("location: /api/iframe?token=$request->token&view=plano-cliente-form&alterar=$id_plano");
        }
        
        exit;
        
    }else{
        $alert = ('Não foi possível inserir os itens!', 'w');
        header("location: /api/iframe?token=$request->token&view=gerenciar-pesquisa-contato-form&alterar=$insertPlano");
        exit;
    }
}

?>