<?php
require_once(__DIR__."/System.php");


//clarissa id_usuario = 181
//ivori id_usuario = 161

//novo responsável
$novoResponsavel = 161;

//Responsável atual
$usuario_acao = 181; 

//alterar usuario_responsavel
//$chamados = DBRead('', 'tb_chamado', "WHERE id_usuario_responsavel = 181 AND id_chamado_status != 3 AND id_chamado_status != 4");

if ($chamados) {

    foreach ($chamados as $conteudo) {

        $id = $conteudo['id_chamado'];
        $link = DBConnect('');
        DBBegin($link);

        $data = getDataHora();
        $acao = 'encaminhar';
        $id_chamado_status = 2;

        $dados = DBReadTransaction($link,'tb_chamado', "WHERE id_chamado = $id");

        if($dados[0]['data_pendencia'] != ''){
            $dados_array = array(
                'id_usuario_responsavel' => $novoResponsavel,
                'id_chamado_status' => $id_chamado_status,
                'data_pendencia' => $data
            );
        }else{
            $dados_array = array(
                'id_usuario_responsavel' => $novoResponsavel,
                'id_chamado_status' => $id_chamado_status
            );
        }
        
        $insertID = DBUpdateTransaction($link, 'tb_chamado', $dados_array, "id_chamado = $id");
        registraLogTransaction($link, 'Alteração de chamado.', 'i', 'tb_chamado', $insertID, "id_usuario_responsavel: $novoResponsavel | data_pendencia: $data");
        
        $justificativa = "Troca de responsável devido ao usuário responsável atual não faz mais parte da empresa.";
        $visibilidade = $conteudo['visibilidade'];
        $bloqueado = $conteudo['bloqueado'];
        $id_contrato_plano_pessoa = $conteudo['id_contrato_plano_pessoa'];
        $arquivo = null;
        $tempo = 2;

        //Insere em chamado ação
        $dados = array(
            'data' => $data,
            'descricao' => $justificativa,
            'visibilidade' => $visibilidade,
            'acao' => $acao,
            'tempo' => $tempo,
            'id_chamado' => $id,
            'id_usuario_responsavel' => $novoResponsavel,
            'id_usuario_acao' => $usuario_acao,
            'id_chamado_status' => $id_chamado_status,
            'bloqueado' => $bloqueado,
            'id_contrato_plano_pessoa' => $id_contrato_plano_pessoa,
            'arquivo' => $arquivo
        );

        $insertID = DBCreateTransaction($link, 'tb_chamado_acao', $dados, true);
        registraLogTransaction($link, 'Inserção de chamado ação.', 'i', 'tb_chamado_acao', $insertID, "data: $data | descricao: $justificativa | visibilidade: $visibilidade | acao: $acao | tempo: $tempo | id_chamado: $id | id_usuario_responsavel: $novoResponsavel | id_usuario_acao: $usuario_acao | id_chamado_status: $id_chamado_status | bloqueado: $bloqueado | id_contrato_plano_pessoa: $id_contrato_plano_pessoa | arquivo: $arquivo");

        if($visibilidade == '1'){
            $id_perfil_sistema = DBReadTransaction($link, 'tb_usuario', "WHERE id_usuario = $novoResponsavel", "id_perfil_sistema");

            $envolvidos = DBReadTransaction($link, 'tb_chamado_perfil', "WHERE id_chamado = '$id' AND id_perfil_sistema = '".$id_perfil_sistema[0]['id_perfil_sistema']."' ");

            if(!$envolvidos){

                $dados = array(
                    'id_chamado' => $id,
                    'id_perfil_sistema' => $id_perfil_sistema[0]['id_perfil_sistema']
                );

                $insertChamadoPerfil = DBCreateTransaction($link, 'tb_chamado_perfil', $dados, true);
                registraLogTransaction($link, 'Inserção de chamado perfil.', 'i', 'tb_topico', $insertChamadoPerfil, "id_chamado: $id | id_perfil_sistema: '".$id_perfil_sistema[0]['id_perfil_sistema']."'");
            }
        }
        if($visibilidade == '2'){
            $envolvidos = DBReadTransaction($link, 'tb_chamado_usuario', "WHERE id_chamado = '$id' AND id_usuario = '$novoResponsavel' ");
            
            if(!$envolvidos){
                $id_perfil_sistema = DBReadTransaction($link, 'tb_usuario', "WHERE id_usuario = $novoResponsavel", "id_perfil_sistema");
                
                $dados = array(
                    'id_chamado' => $id,
                    'id_usuario' => $novoResponsavel,
                    'id_perfil_sistema' => $id_perfil_sistema[0]['id_perfil_sistema']
                );

                $insertChamadoUsuario = DBCreateTransaction($link, 'tb_chamado_usuario', $dados, true);
                registraLogTransaction($link,'Inserção de chamado usuário.','i','tb_chamado_usuario',$insertChamadoUsuario,"id_chamado: $id | id_usuario: $novoResponsavel | id_perfil_sistema: '".$id_perfil_sistema[0]['id_perfil_sistema']."'");
            }
        }

        DBCommit($link);
    }

    echo 'Fim';
}