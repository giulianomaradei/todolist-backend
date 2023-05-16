<?php
    require_once(__DIR__."/System.php");
    

    $descricao = (!empty($_POST['descricao'])) ? $_POST['descricao'] : '';
    $data = getDataHora();
    $id_usuario = $_SESSION['id_usuario'];
    $id_pessoa_lead = (!empty($_POST['inserir_item_timeline'])) ? $_POST['inserir_item_timeline'] : '';
    $data_reuniao = (!empty($_POST['data_reuniao'])) ? $_POST['data_reuniao'] : '';
    $hora_reuniao = (!empty($_POST['hora_reuniao'])) ? $_POST['hora_reuniao'] : '';
    $marcar_reuniao = (!empty($_POST['marcar_reuniao'])) ? $_POST['marcar_reuniao'] : '';
    $usuarios = (!empty($_POST['usuarios'])) ? $_POST['usuarios'] : '';
    
    echo 'Descrição: '.$descricao."<br>";
    echo 'Get data hora: '.$data."<br>";
    echo 'Id usuario post: '.$id_usuario."<br>";
    echo 'Id pessoa lead: '.$id_pessoa_lead."<br>";
    echo 'Data reuniao: '.$data_reuniao."<br>";
    echo 'Data reuniao: '.$hora_reuniao."<br>";

    $format_data_reuniao = converteData($data_reuniao).' '.$hora_reuniao;

    echo 'Data banco: '.$format_data_reuniao;

    $date_calendar = converteData($data_reuniao).'T'.$hora_reuniao.':00.000-03:00';

    $dados_usuarios = array();
    foreach ($usuarios as $conteudo => $value) {
        $dados = DBRead('', 'tb_usuario', "WHERE id_usuario = '".$value."' ", 'id_usuario, email');

        $dados_usuarios[$value] = array(
            'id_usuario' => $dados[0]['id_usuario'],
            'email' => $dados[0]['email']
        );

    }//end foreach

    if (!empty($_POST['inserir_item_timeline'])) {
        inserir_item_timeline($descricao, $data, $id_usuario, $id_pessoa_lead, $format_data_reuniao, $dados_usuarios);
    }

    function inserir_item_timeline($descricao, $data, $id_usuario, $id_pessoa_lead, $format_data_reuniao, $dados_usuarios){
        
        if($descricao !='' && $data !='' && $id_usuario !='' && $id_pessoa_lead !=''){

            $link = DBConnect('');
            DBBegin($link);

            $dados = array(
                'descricao' => $descricao,
                'data' => $data,
                'id_usuario' => $id_usuario,
                'id_pessoa_lead' => $id_pessoa_lead,
            );

            $insertID = DBCreateTransaction($link, 'tb_lead_timeline', $dados, true);
            registraLogTransaction($link, 'Inserção de lead item timeline.', 'i', 'tb_lead_timeline', $insertID, "descricao: $descricao | data: $data | id_usuario: $id_usuario | id_pessoa_lead: $id_pessoa_lead");

            $dados = array(
                'data' => $format_data_reuniao,
            );

            $insertID = DBCreateTransaction($link, 'tb_lead_reuniao', $dados, true);
            registraLogTransaction($link, 'Inserção de reuniao lead.', 'i', 'tb_lead_reuniao', $insertID, "data: $format_data_reuniao");

            foreach ($dados_usuarios as $conteudo) {
                
                $email = $conteudo['email'];
                $id_usuario = $conteudo['id_usuario'];

                $dados = array(
                    'email' => $email,
                    'id_lead_reuniao' => $insertID,
                    'id_usuario' => $id_usuario,
                    'id_pessoa_lead' => $id_pessoa_lead
                );

                $insertID2 = DBCreateTransaction($link, 'tb_lead_usuario_reuniao', $dados, true);
                registraLogTransaction($link, 'Inserção de usuario reuniao lead.', 'i', 'tb_lead_usuario_reuniao', $insertID, "email: $format_data_reuniao | id_lead_reuniao: $insertID2 | id_usuario: $id_usuario | id_pessoa_lead: $id_pessoa_lead");
            }

            //die();
            DBCommit($link);

            $alert = ('Item criado com sucesso!','s');
            

            //die('<script type="text/javascript">window.location=\''.$url.'\';</script‌​>');
            //header("location: /api/iframe?token=$request->token&view=lead-negocio-informacoes&lead=$id_pessoa_lead");
            
        }else{
            $alert = ('Não foi possivel criar item!','d');
            header("location: /api/iframe?token=$request->token&view=lead-negocio-informacoes&lead=$id_pessoa_lead");
        }
    }

?>