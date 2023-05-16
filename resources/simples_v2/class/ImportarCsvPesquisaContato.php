<?php
require_once(__DIR__."/System.php");

$id_pesquisa = (int)$_POST['id_pesquisa'];
importar($id_pesquisa);

function valida_telefone($telefone){
    $telefone = ltrim(preg_replace("/[^0-9]/", "", $telefone), "0");
    if((strlen($telefone) == 11) || (strlen($telefone) == 10)){
        $telefone = substr($telefone,2,9);
        if((strlen($telefone) == 9 && $telefone[0] >= 6) || (strlen($telefone) == 8 && $telefone[0] <= 5)){
            return true;
        }else{
            return false;
        }
    }else{
        return false;
    }	
}

function importar($id_pesquisa){

    $linha = 0;
    $linhas_erros = '';

    $dados_pesquisa = DBRead('', 'tb_pesquisa', "WHERE id_pesquisa = '$id_pesquisa'");
    $label1 = $dados_pesquisa[0]['dado1'];
    $label2 = $dados_pesquisa[0]['dado2'];
    $label3 = $dados_pesquisa[0]['dado3'];

    $cont_erro = 0;
    $cont_sucesso = 0;

    $ext = substr($_FILES['filename']['name'], strrpos($_FILES['filename']['name'], '.'));

    if(is_uploaded_file($_FILES['filename']['tmp_name']) && $ext == ".csv"){
        
        $handle = fopen($_FILES['filename']['tmp_name'], "r");

        while(($data = fgetcsv($handle, 1000, ';')) !== FALSE){
            $linha ++;

            $data = implode(",", $data);
            $data = str_replace(',', ';', $data);
            $data = explode(";", $data);
            $nome = $data[0];
            $telefone = $data[1];
            $dado1 = $data[2];
            $dado2 = $data[3];
            $dado3 = $data[4];
            if(!mb_detect_encoding($nome, 'UTF-8', true)){
                $nome = utf8_encode($data[0]);
                $telefone = utf8_encode($data[1]);
                $dado1 = utf8_encode($data[2]);
                $dado2 = utf8_encode($data[3]);
                $dado3 = utf8_encode($data[4]);
            }

            $telefone = ltrim(preg_replace("/[^0-9]/", "", $telefone), "0");
            $status_pesquisa = 0;

            //Variável que verifica se existe um contato com status_pesquisa = 0, caso sim, não permite importar o mesmo contato novamente.
            $contato_repetido = DBRead('', 'tb_contatos_pesquisa', "WHERE status_pesquisa = 0 AND id_pesquisa = $id_pesquisa AND telefone = '".addslashes($telefone)."'");
            $duplicado = 0;            
            if($contato_repetido){
                $duplicado = 1;
            }

            if(!$nome || !$telefone || $duplicado == 1 || !valida_telefone($telefone)){

                $cont_erro++; 
                $linhas_erros = $linhas_erros." - ".$linha;

                if(!$nome){
                    $descricao_erro  = "Nome";
                }else if(!$telefone || !valida_telefone($telefone)){
                    $descricao_erro  = "Telefone";
                }else{
                    $descricao_erro  = "Duplicado";
                }

                $todos_erros[] = array(
                    'linha' => $linha,
                    'descricao_erro' => $descricao_erro
                );

            }else{
                $data_inclusao = getDataHora();
                $dados = array(
                    'nome' => addslashes($nome),
                    'telefone' => addslashes($telefone),
                    'data_inclusao' => $data_inclusao,
                    'status_pesquisa' => $status_pesquisa,
                    'dado1' => addslashes($dado1),
                    'dado2' => addslashes($dado2),
                    'dado3' => addslashes($dado3),
                    'label1' => $label1,
                    'label2' => $label2,
                    'label3' => $label3,
                    'id_pesquisa' => $id_pesquisa,
                    'qtd_tentativas_cliente' => 0
                );

                $insertID = DBCreate('', 'tb_contatos_pesquisa', $dados);
                registraLog('Inserção de novo contato.', 'i', 'tb_contatos_pesquisa', $insertID, "nome: $nome | telefone: $telefone | data_inclusao: $data_inclusao | dado1: $dado1 | dado2: $dado2 | dado3: $dado3 | label1: $label1 | label2: $label2 | label3: $label3 | qtd_tentativas_cliente: 1 | data_ultimo_contato: 0000-00-00 00:00:00 | status_pesquisa: 1 | id_pesquisa: $id_pesquisa");
                $cont_sucesso++;
            }
        }
        $erro_linhas_nome = '';
        $erro_linhas_telefone = '';
        $erro_linhas_duplicado = '';
        foreach ($todos_erros as $total_erro) {
            if($total_erro['descricao_erro'] == "Nome"){
                if(!$erro_linhas_nome){
                    $erro_linhas_nome  = ", nome na(s) linha(s)";
                }
                $erro_linhas_nome  .= " - ".$total_erro['linha'];
            }else if($total_erro['descricao_erro'] == "Telefone"){
                if(!$erro_linhas_telefone){
                    $erro_linhas_telefone  = ", telefone na(s) linha(s)";
                }
                $erro_linhas_telefone  .= " - ".$total_erro['linha'];
            }else{
                if(!$erro_linhas_duplicado){
                    $erro_linhas_duplicado  = ", duplicado na(s) linha(s)";
                }
                $erro_linhas_duplicado  .= " - ".$total_erro['linha'];
            }
        }


        fclose($handle);
        if($cont_sucesso && !$cont_erro){
            $alert = ("$cont_sucesso itens inseridos com sucesso e nenhum erro!", 's');
        }else if($cont_sucesso && $cont_erro){
            $alert = ("$cont_sucesso itens inseridos com sucesso, porém ocorreram $cont_erro erros$erro_linhas_nome$erro_linhas_telefone$erro_linhas_duplicado!", 'w');
        }else if(!$cont_sucesso && $cont_erro){
            $alert = ("Nenhum contato foi importado, houve $cont_erro erros!", 'd');
        }else{
            $alert = ("Nenhum contato foi importado!", 'd');
        }
        
        header("location: /api/iframe?token=$request->token&view=gerenciar-pesquisa-contato-form&id_pesquisa=$id_pesquisa");
        exit;
        
    }else{
        $alert = ('Não foi possível inserir os itens!', 'w');
        header("location: /api/iframe?token=$request->token&view=gerenciar-pesquisa-contato-form&id_pesquisa=$id_pesquisa");
        exit;
    }
}

?>