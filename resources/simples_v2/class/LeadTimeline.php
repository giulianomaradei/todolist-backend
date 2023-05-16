<?php
    require_once(__DIR__."/System.php");
    

    $parametros = (!empty($_GET['parametros'])) ? $_GET['parametros'] : '';
    $acao = (!empty($_GET['acao'])) ? $_GET['acao'] : '';

    if($acao == 'busca_emails'){
    
        $usuarios = $parametros['usuarios'];

        $dados_usuarios = array();
        if($usuarios){
            foreach ($usuarios as $conteudo => $value) {
                $dados = DBRead('', 'tb_usuario', "WHERE id_usuario = '".$value."' ", 'id_usuario, email');
        
                $dados_usuarios[$value] = array(
                    'id_usuario' => $dados[0]['id_usuario'],
                    'email' => $dados[0]['email']
                );
            }//end foreach
        }

        foreach ($dados_usuarios as $conteudo) {
                    
            $email = $conteudo['email'];
            $id_usuario = $conteudo['id_usuario'];
            
            $emails_lista[] = array(
                'email' => $email,
            );
        }

        echo json_encode($emails_lista);
    }

    if($acao == 'persiste_BD'){

        $tipo = $parametros['tipo'];
        $descricao = $parametros['descricao'];
        $data_reuniao = $parametros['data_reuniao'];
        $hora_reuniao = $parametros['hora_reuniao'];
        $id_evento_google = $parametros['id_evento_google'];
        $data = getDataHora();
        $id_usuario = $_SESSION['id_usuario'];
        $id_lead_negocio = $parametros['id_lead_negocio'];
        $contato_realizado = $parametros['contato_realizado'];
        $marcar_reuniao = $parametros['marcar_reuniao'];
        $usuarios = $parametros['usuarios'];
        $convidado = $parametros['convidado'];
        $format_data_reuniao = converteData($data_reuniao).' '.$hora_reuniao;

        if ($id_lead_negocio == '') {
            $id_lead_negocio = 0;
        }

        if ($convidado == '') {
            $convidado = NULL;
        }

        $dados_usuarios = array();
        if ($usuarios) {
            foreach ($usuarios as $conteudo => $value) {
                $dados = DBRead('', 'tb_usuario', "WHERE id_usuario = '".$value."' ", 'id_usuario, email');
        
                $dados_usuarios[$value] = array(
                    'id_usuario' => $dados[0]['id_usuario'],
                    'email' => $dados[0]['email']
                );
            }//end foreach
        }

        if($descricao !='' && $data !='' && $id_usuario !=''){

            $link = DBConnect('');
            DBBegin($link);

            $dados = array(
                'descricao' => $descricao,
                'data' => $data,
                'id_usuario' => $id_usuario,
                'id_lead_negocio' => $id_lead_negocio,
                'contato_realizado' => $contato_realizado,
                'id_lead_tipo_item_timeline' => $tipo,
                'convidado' => $convidado
            );

            $insertID_timeline = DBCreateTransaction($link, 'tb_lead_timeline', $dados, true);
            registraLogTransaction($link, 'Inserção de lead item timeline.', 'i', 'tb_lead_timeline', $insertID_timeline, "descricao: $descricao | data: $data | id_usuario: $id_usuario | id_lead_negocio: $id_lead_negocio | id_lead_tipo_item_timeline: $tipo | convidado: $convidado");

            if($marcar_reuniao == 'sim'){

                $dados = array(
                    'data' => $format_data_reuniao,
                    'id_lead_timeline' => $insertID_timeline,
                    'id_evento_google' => $id_evento_google
                );
        
                $insertID = DBCreateTransaction($link, 'tb_lead_reuniao', $dados, true);
                registraLogTransaction($link, 'Inserção de reuniao lead.', 'i', 'tb_lead_reuniao', $insertID, "data: $format_data_reuniao | id_lead_timeline: $insertID_timeline");

                foreach ($dados_usuarios as $conteudo) {
                    
                    $email = $conteudo['email'];
                    $id_usuario = $conteudo['id_usuario'];
        
                    $dados = array(
                        'email' => $email,
                        'id_lead_reuniao' => $insertID,
                        'id_usuario' => $id_usuario,
                        'id_lead_negocio' => $id_lead_negocio
                    );
        
                    $insertID2 = DBCreateTransaction($link, 'tb_lead_usuario_reuniao', $dados, true);
                    registraLogTransaction($link, 'Inserção de usuario reuniao lead.', 'i', 'tb_lead_usuario_reuniao', $insertID, "email: $email | id_lead_reuniao: $insertID2 | id_usuario: $id_usuario | id_lead_negocio: $id_lead_negocio");
                }
            }
        
            DBCommit($link);

            $result = true;
            echo json_encode($result);
        }
        else{
            $result = false;
            echo json_encode($result);
        }
    }

    if($acao == 'editar_persiste_BD'){

        $tipo = $parametros['tipo'];
        $descricao = $parametros['descricao'];
        $data_reuniao = $parametros['data_reuniao'];
        $hora_reuniao = $parametros['hora_reuniao'];
        $id_evento_google = $parametros['id_evento_google'];
        $data = getDataHora();
        $id_usuario = $_SESSION['id_usuario'];
        $id_lead_negocio = $parametros['id_lead_negocio'];
        $id_item_timeline = $parametros['id_item_timeline'];                                            
        $contato_realizado = $parametros['contato_realizado'];    
        $excluir_reuniao = $parametros['excluir_reuniao'];                                       
        
        $marcar_reuniao = $parametros['marcar_reuniao'];
        $usuarios = $parametros['usuarios'];
        $format_data_reuniao = converteData($data_reuniao).' '.$hora_reuniao;
        $convidado = $parametros['convidado'];

        if($id_lead_negocio == ''){
            $id_lead_negocio = 0;
        }

        if ($convidado == '') {
            $convidado = NULL;
        }
        
        $dados_usuarios = array();
        if($usuarios){
            foreach ($usuarios as $conteudo => $value) {
                $dados = DBRead('', 'tb_usuario', "WHERE id_usuario = '".$value."' ", 'id_usuario, email');
        
                $dados_usuarios[$value] = array(
                    'id_usuario' => $dados[0]['id_usuario'],
                    'email' => $dados[0]['email']
                );
            }
        }

        if($descricao !='' && $data !='' && $id_usuario !=''){

            $link = DBConnect('');
            DBBegin($link);

            $dados = array(
                'descricao' => $descricao,
                'data' => $data,
                'id_usuario' => $id_usuario,
                'id_lead_negocio' => $id_lead_negocio,
                'contato_realizado' => $contato_realizado,
                'id_lead_tipo_item_timeline' => $tipo,
                'convidado' => $convidado
            ); 

            //$insertID_timeline = DBCreateTransaction($link, 'tb_lead_timeline', $dados, true);
            DBUpdate($link, 'tb_lead_timeline', $dados, "id_lead_timeline = '$id_item_timeline'");
            registraLogTransaction($link, 'Alteração de lead item timeline.', 'a', 'tb_lead_timeline', $id_item_timeline, "descricao: $descricao | data: $data | id_usuario: $id_usuario | id_lead_negocio: $id_lead_negocio | id_lead_tipo_item_timeline: $tipo | convidado: $convidado");

            $reuniao = DBRead($link, 'tb_lead_reuniao', "WHERE id_lead_timeline = $id_item_timeline");
            $id_lead_reuniao = $reuniao[0]['id_lead_reuniao'];

            if($marcar_reuniao == 'sim'){

                $dados = array(
                    'data' => $format_data_reuniao
                );
                
                if($reuniao){
                    //$insertID = DBCreateTransaction($link, 'tb_lead_reuniao', $dados, true);
                    DBUpdate($link, 'tb_lead_reuniao', $dados, "id_lead_reuniao = '$id_lead_reuniao' ");
                    registraLogTransaction($link, 'alteração de reuniao lead.', 'a', 'tb_lead_reuniao', $id_lead_reuniao, "data: $format_data_reuniao");

                    DBDelete($link, 'tb_lead_usuario_reuniao', "id_lead_reuniao = $id_lead_reuniao");

                    foreach ($dados_usuarios as $conteudo) {
                        
                        $email = $conteudo['email'];
                        $id_usuario = $conteudo['id_usuario'];
            
                        $dados = array(
                            'email' => $email,
                            'id_lead_reuniao' => $id_lead_reuniao,
                            'id_usuario' => $id_usuario,
                            'id_lead_negocio' => $id_lead_negocio
                        );

                        $insertID2 = DBCreateTransaction($link, 'tb_lead_usuario_reuniao', $dados, true);
                        registraLogTransaction($link, 'Inserção de usuario reuniao lead.', 'i', 'tb_lead_usuario_reuniao', $insertID2, "email: $email | id_lead_reuniao: $id_lead_reuniao | id_usuario: $id_usuario | id_lead_negocio: $id_lead_negocio");
                    }
                }else{
                    
                    $dados_reuniao = array(
                        'data' => $format_data_reuniao,
                        'id_evento_google' => $id_evento_google,
                        'id_lead_timeline' =>  $id_item_timeline
                    );

                    $insertID = DBCreateTransaction($link, 'tb_lead_reuniao', $dados_reuniao, true);

                    registraLogTransaction($link, 'Inserção de reuniao lead.', 'i', 'tb_lead_reuniao', $insertID, "data: $format_data_reuniao | id_evento_google: $id_evento_google | id_lead_timeline: $id_lead_timeline");

                    foreach ($dados_usuarios as $conteudo) {
                        
                        $email = $conteudo['email'];
                        $id_usuario = $conteudo['id_usuario'];
            
                        $dados = array(
                            'email' => $email,
                            'id_lead_reuniao' => $insertID,
                            'id_usuario' => $id_usuario,
                            'id_lead_negocio' => $id_lead_negocio
                        );

                        $insertID2 = DBCreateTransaction($link, 'tb_lead_usuario_reuniao', $dados, true);
                        registraLogTransaction($link, 'Inserção de usuario reuniao lead.', 'i', 'tb_lead_usuario_reuniao', $insertID, "email: $format_data_reuniao | id_lead_reuniao: $insertID2 | id_usuario: $id_usuario | id_lead_negocio: $id_lead_negocio");
                    }
                }
            }

            if($excluir_reuniao == 'sim'){
                $participantes = DBReadTransaction($link, 'tb_lead_usuario_reuniao', "WHERE id_lead_reuniao = '$id_lead_reuniao'");

                if($reuniao){
                    foreach ($participantes as $conteudo) {
                        DBDeleteTransaction($link, 'tb_lead_usuario_reuniao', "id_lead_usuario_reuniao = '$id_lead_reuniao'");
                    }

                    DBDeleteTransaction($link, 'tb_lead_reuniao', "id_lead_reuniao = '$id_lead_reuniao'");
                }
            }   
        
            DBCommit($link);

            $result = true;
            echo json_encode($result);
        }
        else{
            $result = false;
            echo json_encode($result);
        } 
    }
?>
 
