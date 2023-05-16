<?php
require_once(__DIR__."/System.php");
$titulo = (!empty($_POST['titulo'])) ? $_POST['titulo'] : '';
$visibilidade = (!empty($_POST['id_visibilidade'])) ? $_POST['id_visibilidade'] : '';
$id_categoria = (!empty($_POST['id_categoria'])) ? $_POST['id_categoria'] : '';
$descricao = (!empty($_POST['descricao'])) ? $_POST['descricao'] : '';
$descricao_comentario = (!empty($_POST['descricao_comentario'])) ? $_POST['descricao_comentario'] : '';
$tempo = (!empty($_POST['tempo'])) ? $_POST['tempo'] : '';
$id_responsavel = (!empty($_POST['id_responsavel'])) ? $_POST['id_responsavel'] : '';
$perfis = (!empty($_POST['perfil_sistema'])) ? $_POST['perfil_sistema'] : '';
$perfil_alteracao = (!empty($_POST['perfil_sistema'])) ? "'".join("','", $_POST['perfil_sistema'])."'" : '';
$usuarios = (!empty($_POST['usuarios'])) ? $_POST['usuarios'] : '';
$usuarios_alteracao = (!empty($_POST['usuarios'])) ? "'".join("','", $_POST['usuarios'])."'" : '';
$tipo = (!empty($_POST['tipo'])) ? $_POST['tipo'] : '';
$id_contrato_plano_pessoa = (!empty($_POST['id_contrato_plano_pessoa'])) ? $_POST['id_contrato_plano_pessoa'] : 0;
$id_chamado_origem = (!empty($_POST['id_origem'])) ? $_POST['id_origem'] : '';
$origem_chamado = (!empty($_POST['origem_chamado'])) ? $_POST['origem_chamado'] : '';

//campos referentes a prazo
$prazo_encerramento_data = (!empty($_POST['prazo_encerramento_data'])) ? $_POST['prazo_encerramento_data'] : '0000-00-00';
$prazo_encerramento_hora = (!empty($_POST['prazo_encerramento_hora'])) ? $_POST['prazo_encerramento_hora'] : '00:00:00';
$prazo_encerramento = converteData($prazo_encerramento_data) . " " . $prazo_encerramento_hora;

if(!empty($_POST['inserir'])){

    $dados_categoria = array();
    foreach($id_categoria as $conteudo){
        $dados_categoria[$cont]['id_categoria'] = $conteudo;
        $cont++;
    }
    
    if($id_categoria = ''){
        $alert = ('Informe no mínimo uma categoria!','w');
        header("location: /api/iframe?token=$request->token&view=chamado-form");
    }else{

        inserir($titulo, $tempo, $descricao, $visibilidade, $id_responsavel, $dados_categoria, $perfis, $usuarios, $id_contrato_plano_pessoa, $id_chamado_origem, $origem_chamado, $prazo_encerramento, $_FILES['anexo_inserir']);
    }

}else if(!empty($_POST['nota'])){

    $id = (int)$_POST['nota'];
    $descricao_nota = (!empty($_POST['descricao_nota'])) ? $_POST['descricao_nota'] : '';
    $tempo = (!empty($_POST['tempo_nota'])) ? $_POST['tempo_nota'] : '';
    inserirNota($id, $tempo, $descricao_nota, $tipo, $_FILES['anexo_nota']);
 
}else if(!empty($_POST['encerrar'])){

    $id = (int)$_POST['encerrar'];

    $dados = DBRead('','tb_chamado', "WHERE id_chamado = '$id'", 'id_usuario_remetente, id_usuario_responsavel, bloqueado, visibilidade, id_contrato_plano_pessoa');

    $id_contrato_plano_pessoa = (!empty($dados[0]['id_contrato_plano_pessoa'])) ? $dados[0]['id_contrato_plano_pessoa'] : 0;

    $remetente = $dados[0]['id_usuario_remetente'];
    $responsavel = $dados[0]['id_usuario_responsavel'];
    $bloqueado = $dados[0]['bloqueado'];
    $visibilidade = $dados[0]['visibilidade'];
    $usuario_acao = $_SESSION['id_usuario'];
    $descricao = (!empty($_POST['solucao'])) ? $_POST['solucao'] : '';
    $tempo = (!empty($_POST['tempo_encerramento'])) ? $_POST['tempo_encerramento'] : '';
    $id_chamado_status = (!empty($_POST['id_chamado_status'])) ? $_POST['id_chamado_status'] : '';
    
    if($bloqueado == '1'){
        if($usuario_acao == $remetente || $usuario_acao == $responsavel){
            encerrar($id, $tempo, $descricao, $id_chamado_status, $remetente, $responsavel, $usuario_acao, $id_contrato_plano_pessoa, $_FILES['anexo_encerrar']);
        }else{
            $alert = ('Você não tem permissão para encerrar este chamado!','w');
            header("location: /api/iframe?token=$request->token&view=chamado-informacoes&chamado=$id");
        }
    }//end if bloqueado = 1
    
    if($bloqueado == '2'){

        if($visibilidade =='1'){
            $id_perfil_sistema = DBRead('', 'tb_usuario', "WHERE id_usuario = $usuario_acao", "id_perfil_sistema");
            $envolvido = DBRead('', 'tb_chamado_perfil', "WHERE id_perfil_sistema = '$id_perfil_sistema'", "COUNT(*) as envolvido");
            if($envolvido > 0){
                encerrar($id, $tempo, $descricao, $id_chamado_status, $remetente, $responsavel, $usuario_acao, $id_contrato_plano_pessoa, $_FILES['anexo_encerrar']);
            }else{
                $alert = ('Você não tem permissão para encerrar este chamado!','w');
                header("location: /api/iframe?token=$request->token&view=chamado-informacoes&chamado=$id");
            }
        }//end else if visibilidade = 1

        else if($visibilidade == '2'){

            $envolvido = DBRead('', 'tb_chamado_usuario', "WHERE id_usuario = '$usuario_acao'", "COUNT(*) as envolvido");
            if($envolvido > 0){
                encerrar($id, $tempo, $descricao, $id_chamado_status, $remetente, $responsavel, $usuario_acao, $id_contrato_plano_pessoa, $_FILES['anexo_encerrar']);
            }else{
                $alert = ('Você não tem permissão para encerrar este chamado!','w');
                header("location: /api/iframe?token=$request->token&view=chamado-informacoes&chamado=$id");
            }
        }//end else if visibilidade = 2
    }// end if bloqueado = 2

}else if(!empty($_POST['reabrir'])){
    
    $id = (int)$_POST['reabrir'];
    $dados = DBRead('','tb_chamado', "WHERE id_chamado = $id", 'id_usuario_remetente, id_usuario_responsavel, id_contrato_plano_pessoa');
    $remetente = $dados[0]['id_usuario_remetente'];
    $responsavel = $dados[0]['id_usuario_responsavel'];
    $id_contrato_plano_pessoa = (!empty($dados[0]['id_contrato_plano_pessoa'])) ? $dados[0]['id_contrato_plano_pessoa'] : 0;
    $usuario_acao = $_SESSION['id_usuario'];

    $descricao = (!empty($_POST['solucao'])) ? $_POST['solucao'] : '';
    $tempo = (!empty($_POST['tempo_reabrir'])) ? $_POST['tempo_reabrir'] : '';
    reabrir($id, $tempo, $descricao, $responsavel, $usuario_acao, $id_contrato_plano_pessoa);

}else if(!empty($_POST['trocaResponsavel'])){
    
    $id = (int)$_POST['trocaResponsavel'];
    $dados = DBRead('','tb_chamado', "WHERE id_chamado = $id", 'id_usuario_remetente, id_usuario_responsavel, bloqueado, visibilidade, id_contrato_plano_pessoa');
    $remetente = $dados[0]['id_usuario_remetente'];
    $antigoResponsavel = $dados[0]['id_usuario_responsavel'];
    $bloqueado = $dados[0]['bloqueado'];
    $visibilidade = $dados[0]['visibilidade'];

    $id_contrato_plano_pessoa = (!empty($dados[0]['id_contrato_plano_pessoa'])) ? $dados[0]['id_contrato_plano_pessoa'] : 0;

    $usuario_acao = $_SESSION['id_usuario'];
    $justificativa = (!empty($_POST['justificativa'])) ? $_POST['justificativa'] : '';
    $tempo = (!empty($_POST['tempo'])) ? $_POST['tempo'] : '';
    $novoResponsavel = (!empty($_POST['id_responsavel_troca'])) ? $_POST['id_responsavel_troca'] : '';

    if($bloqueado == '1'){
        if($usuario_acao == $remetente || $usuario_acao == $antigoResponsavel){
            trocaResponsavel($id, $visibilidade, $novoResponsavel, $justificativa, $tempo, $usuario_acao, $bloqueado, $id_contrato_plano_pessoa, $remetente, $antigoResponsavel, $_FILES['anexo_troca_responsavel']);
        }else{
            $alert = ('Você não tem permissão para trocar o responsavel deste chamado!','w');
            header("location: /api/iframe?token=$request->token&view=chamado-busca");
        }
    }else if($bloqueado == '2'){
        trocaResponsavel($id, $visibilidade, $novoResponsavel, $justificativa, $tempo, $usuario_acao, $bloqueado, $id_contrato_plano_pessoa, $remetente, $antigoResponsavel, $_FILES['anexo_troca_responsavel']);
    }

}else if(!empty($_POST['assumirChamado'])){

    $id = (int)$_POST['assumirChamado'];

    $dados = DBRead('','tb_chamado', "WHERE id_chamado = $id", 'id_usuario_remetente, id_usuario_responsavel, bloqueado, visibilidade, id_contrato_plano_pessoa, id_contrato_plano_pessoa');
    $id_contrato_plano_pessoa = (!empty($dados[0]['id_contrato_plano_pessoa'])) ? $dados[0]['id_contrato_plano_pessoa'] : 0;
    
    $remetente = $dados[0]['id_usuario_remetente'];
    $antigoResponsavel = $dados[0]['id_usuario_responsavel'];
    $bloqueado = $dados[0]['bloqueado'];
    $visibilidade = $dados[0]['visibilidade'];

    $usuario_acao = $_SESSION['id_usuario'];
    $usuario_assumiu = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_usuario = '".$usuario_acao."'", "b.nome");
    $justificativa = $usuario_assumiu[0]['nome']." assumiu este chamado!";
    $tempo = '1';

    assumirChamado($id, $visibilidade, $justificativa, $tempo, $usuario_acao, $bloqueado, $id_contrato_plano_pessoa, $remetente, $antigoResponsavel);

}else if(!empty($_POST['gerenciar'])){

    $id = (int)$_POST['gerenciar'];
    $dados = DBRead('','tb_chamado', "WHERE id_chamado = $id", 'id_usuario_remetente, id_usuario_responsavel, bloqueado, visibilidade, id_chamado_status, id_contrato_plano_pessoa');
    $id_chamado_status = $dados[0]['id_chamado_status'];
    $remetente = $dados[0]['id_usuario_remetente'];
    $responsavel = $dados[0]['id_usuario_responsavel'];
    $bloqueado = $dados[0]['bloqueado'];
    $id_contrato_plano_pessoa = (!empty($dados[0]['id_contrato_plano_pessoa'])) ? $dados[0]['id_contrato_plano_pessoa'] : 0;
    $usuario_acao = $_SESSION['id_usuario'];
    $justificativa = (!empty($_POST['justificativa'])) ? $_POST['justificativa'] : '';
    $tempo = (!empty($_POST['tempo'])) ? $_POST['tempo'] : '';
    $id_responsavel = (!empty($_POST['responsavel_final'])) ? $_POST['responsavel_final'] : '';
    $visibilidade = (!empty($_POST['id_visibilidade'])) ? $_POST['id_visibilidade'] : '';

    if($bloqueado == '1'){
        if($usuario_acao == $remetente || $usuario_acao == $responsavel){
            gerenciar($id, $justificativa, $visibilidade, $tempo, $perfis, $usuarios, $usuario_acao, $id_chamado_status, $bloqueado, $responsavel, $id_contrato_plano_pessoa, $remetente);
        }else{
            $alert = ('Você não tem permissão para gerenciar este chamado!', 'w');
            header("location: /api/iframe?token=$request->token&view=chamado-busca");
        }
    }else if($bloqueado == '2'){
        gerenciar($id, $justificativa, $visibilidade, $tempo, $perfis, $usuarios, $usuario_acao, $id_chamado_status, $bloqueado, $responsavel, $id_contrato_plano_pessoa, $remetente);
    }

}else if(!empty($_POST['desbloquear'])){

    $id = (int)$_POST['desbloquear'];
    $dados = DBRead('','tb_chamado', "WHERE id_chamado = $id", 'id_usuario_remetente, id_usuario_responsavel, id_contrato_plano_pessoa');
    $remetente = $dados[0]['id_usuario_remetente'];
    $responsavel = $dados[0]['id_usuario_responsavel'];
    $id_contrato_plano_pessoa = (!empty($dados[0]['id_contrato_plano_pessoa'])) ? $dados[0]['id_contrato_plano_pessoa'] : 0;
    $usuario = $_SESSION['id_usuario'];

    $justificativa = (!empty($_POST['descricao_desbloquear'])) ? $_POST['descricao_desbloquear'] : '';
    $tempo = (!empty($_POST['tempo_desbloquear'])) ? $_POST['tempo_desbloquear'] : '';
    $visibilidade = (!empty($_POST['visibilidade'])) ? $_POST['visibilidade'] : '';
    desbloquear($id, $justificativa, $tempo, $visibilidade, $responsavel, $id_contrato_plano_pessoa);

}else if(!empty($_POST['bloquear'])){
    
    $id = (int)$_POST['bloquear'];
    $dados = DBRead('','tb_chamado', "WHERE id_chamado = $id", 'id_usuario_remetente, id_usuario_responsavel, id_contrato_plano_pessoa');
    $remetente = $dados[0]['id_usuario_remetente'];
    $responsavel = $dados[0]['id_usuario_responsavel'];
    $id_contrato_plano_pessoa = (!empty($dados[0]['id_contrato_plano_pessoa'])) ? $dados[0]['id_contrato_plano_pessoa'] : 0;
    $usuario_acao = $_SESSION['id_usuario'];
    $justificativa = (!empty($_POST['descricao_bloquear'])) ? $_POST['descricao_bloquear'] : '';
    $tempo = (!empty($_POST['tempo_bloquear'])) ? $_POST['tempo_bloquear'] : '';
    $id_responsavel = (!empty($_POST['id_responsavel'])) ? $_POST['id_responsavel'] : '';
    $visibilidade = (!empty($_POST['visibilidade'])) ? $_POST['visibilidade'] : '';
    bloquear($id, $justificativa, $tempo, $visibilidade, $responsavel, $id_contrato_plano_pessoa);

}else if(!empty($_POST['pendencia'])){
    
    $id = (int)$_POST['pendencia'];
    $dados = DBRead('','tb_chamado', "WHERE id_chamado = $id", 'visibilidade, bloqueado, id_usuario_responsavel, id_chamado_status, id_contrato_plano_pessoa');
    $visibilidade = $dados[0]['visibilidade'];
    $bloqueado = $dados[0]['bloqueado'];
    $responsavel = $dados[0]['id_usuario_responsavel'];
    $id_chamado_status = $dados[0]['id_chamado_status'];
    $id_contrato_plano_pessoa = (!empty($dados[0]['id_contrato_plano_pessoa'])) ? $dados[0]['id_contrato_plano_pessoa'] : 0;
    $data = (!empty($_POST['data'])) ? $_POST['data'] : '';
    $hora = (!empty($_POST['hora'])) ? $_POST['hora'] : '';
    $descricao = (!empty($_POST['descricao_pendencia'])) ? $_POST['descricao_pendencia'] : '';
    $tempo = (!empty($_POST['tempo_pendencia'])) ? $_POST['tempo_pendencia'] : '';
    $usuario_acao = $_SESSION['id_usuario'];

    pendencia($id, $data, $hora, $descricao, $tempo, $visibilidade, $bloqueado, $responsavel, $usuario_acao, $id_chamado_status, $id_contrato_plano_pessoa);

}else if(!empty($_POST['alterar'])){

    $id = (int)$_POST['alterar'];
    $dados = DBRead('','tb_chamado', "WHERE id_chamado = $id", 'id_usuario_remetente, id_usuario_responsavel, bloqueado, visibilidade, id_chamado_status, id_contrato_plano_pessoa');
    $id_chamado_status = $dados[0]['id_chamado_status'];
    $remetente = $dados[0]['id_usuario_remetente'];
    $responsavel = $dados[0]['id_usuario_responsavel'];
    $visibilidade = $dados[0]['visibilidade'];
    $bloqueado = $dados[0]['bloqueado'];
    $usuario_acao = $_SESSION['id_usuario'];
    $id_contrato_plano_pessoa = (!empty($_POST['id_contrato_plano_pessoa'])) ? $_POST['id_contrato_plano_pessoa'] : 0;
    $justificativa = (!empty($_POST['justificativa_alterar'])) ? $_POST['justificativa_alterar'] : '';
    $tempo = (!empty($_POST['tempo_alterar'])) ? $_POST['tempo_alterar'] : '';
    $id_origem = (!empty($_POST['id_origem'])) ? $_POST['id_origem'] : '';
    $id_categoria = (!empty($_POST['id_categoria'])) ? $_POST['id_categoria'] : '';

    $dados_categoria = array();
    foreach($id_categoria as $conteudo){
        $dados_categoria[$cont]['id_categoria'] = $conteudo;
        $cont++;
    }

    if($id_categoria = ''){
        $alert = ('Informe no mínimo uma categoria!','w');
        header("location: /api/iframe?token=$request->token&view=chamado-informacoes&chamado=$id");
    }

    if($bloqueado == '1'){
        if($usuario_acao == $remetente || $usuario_acao == $responsavel){
            //alterar($id, $justificativa, $visibilidade, $tempo, $usuario_acao, $id_chamado_status, $bloqueado, $responsavel, $id_contrato_plano_pessoa, $id_categoria, $id_origem);
            alterar($id, $justificativa, $visibilidade, $tempo, $usuario_acao, $id_chamado_status, $bloqueado, $responsavel, $id_contrato_plano_pessoa, $dados_categoria, $id_origem);
        }else{
            $alert = ('Você não tem permissão para alterar o chamado!','w');
            header("location: /api/iframe?token=$request->token&view=chamado-busca");
        }
    }else if($bloqueado == '2'){
        //alterar($id, $justificativa, $visibilidade, $tempo, $usuario_acao, $id_chamado_status, $bloqueado, $responsavel, $id_contrato_plano_pessoa, $id_categoria, $id_origem);
        alterar($id, $justificativa, $visibilidade, $tempo, $usuario_acao, $id_chamado_status, $bloqueado, $responsavel, $id_contrato_plano_pessoa, $dados_categoria, $id_origem);   
    }

}else if(isset($_GET['cancelar_envolvimento'])){

    $id = (int)$_GET['cancelar_envolvimento'];
    cancelar_envolvimento($id);

}else if(isset($_POST['alteracao_prazo'])){

    $prazo_encerramento_data = (!empty($_POST['prazo_encerramento_data'])) ? $_POST['prazo_encerramento_data'] : '';
    $prazo_encerramento_hora = (!empty($_POST['prazo_encerramento_hora'])) ? $_POST['prazo_encerramento_hora'] : '';
    $justificativa_alteracao = (!empty($_POST['justificativa_alteracao'])) ? $_POST['justificativa_alteracao'] : '';
    $tempo = (!empty($_POST['tempo'])) ? $_POST['tempo'] : '';
    $id_contrato_plano_pessoa = (!empty($_POST['id_contrato_plano_pessoa'])) ? $_POST['id_contrato_plano_pessoa'] : 0;

    alteracaoPrazo($tempo, $prazo_encerramento_data, $prazo_encerramento_hora, $justificativa_alteracao, $id_contrato_plano_pessoa);

}else if (isset($_GET['arq'])) {

    $id_chamado_acao = $_GET['arq'];
    downloadAnexo($id_chamado_acao);

}else{
    header("location: ../adm.php");
    exit;
}

function downloadAnexo($id_chamado_acao){
    $dados = DBRead('','tb_chamado_acao a', "INNER JOIN tb_chamado b ON a.id_chamado = b.id_chamado WHERE a.id_chamado_acao = '".$id_chamado_acao."' ", 'a.arquivo, a.id_chamado, b.visibilidade, b.id_usuario_remetente, b.id_usuario_responsavel');
    
    $dados_perfil = DBRead('','tb_usuario', "WHERE id_usuario = '".$_SESSION['id_usuario']."' ", 'id_perfil_sistema');
    $perfil_usuario = $dados_perfil[0]['id_perfil_sistema'];
    $id_chamado = $dados[0]['id_chamado'];

    $arquivo = $dados[0]['arquivo'];
    $arquivo_download = '../'.$arquivo;

    if($dados[0]['visibilidade'] == 1){
        $perfil = DBRead('', 'tb_chamado_perfil', "WHERE id_perfil_sistema = '".$perfil_usuario."' AND id_chamado = '".$id_chamado."' ");
        if(!$perfil && $dados[0]['id_usuario_remetente'] != $_SESSION['id_usuario'] && $dados[0]['id_usuario_responsavel'] != $_SESSION['id_usuario']){
            echo '<div class="alert alert-danger text-center"><strong>Você não possui permisssão para visualizar este chamado!</strong></div>';
            exit;
            
        }
    }else if($dados[0]['visibilidade'] == 2){
        $usuario = DBRead('', 'tb_chamado_usuario', "WHERE id_usuario = '".$_SESSION['id_usuario']."' AND id_chamado= '".$id_chamado."' ");
        if(!$usuario && $dados[0]['id_usuario_remetente'] != $_SESSION['id_usuario'] && $dados[0]['id_usuario_responsavel'] != $_SESSION['id_usuario']){
            echo '<div class="alert alert-danger text-center"><strong>Você não possui permisssão para visualizar este chamado!</strong></div>';
            exit;
        }
    }

    if(!file_exists($arquivo_download)){    
        //$alert = ('Não foi possível fazer o download do anexo!<br>'.$arquivo_download,'w');
        $alert = ('Não foi possível fazer o download do anexo!','w');
        header("location: /api/iframe?token=$request->token&view=chamado-informacoes&chamado=".$id_chamado."");

    }else{

        header('Cache-control: private');
        header('Content-Type: application/octet-stream');
        header('Content-Length: ' . filesize($arquivo_download));
        header('Content-Disposition: filename=' . $arquivo_download);
        header("Content-Disposition: attachment; filename=" . basename($arquivo_download));
        readfile($arquivo_download);

    }
}

function salvarAnexo($file){
    
    if (isset($file['name'])) {
        $file_name = $file['name'];
        $file_size = $file['size'];
        $arquivo_tmp = $file['tmp_name'];
        
    } else {
        $file_name = '';
        $file_size = '';
        $arquivo_tmp = '';

    }
    if ($file_name != '' && $file_size != '' && $arquivo_tmp != '') {

        if ($file_size > 10242880) {
            $alert = ('Tamanho do arquivo excede o tamanho limite!', 'd', 'AVISO!');
            header("location: /api/iframe?token=$request->token&view=home");
            exit;

        } else {

            // Pega a extensão
            $extensao = pathinfo($file_name, PATHINFO_EXTENSION);

            // Converte a extensão para minúsculo
            $extensao = strtolower($extensao);
            
            // Somente imagens, .jpg;.jpeg;.gif;.png
            // Aqui eu enfileiro as extensões permitidas e separo por ';'
            // Isso serve apenas para eu poder pesquisar dentro desta String
            if (strstr('.csv; .doc; .docx; .pdf; .ppt; .pptx; .rtf; .txt; .xls; .xlsx; .zip; .rar; .bmp; .jpg; .jpeg; .jpe; .tiff; .png; gif;', $extensao)) {
                // Cria um nome único para esta imagem
                // Evita que duplique as imagens no servidor.
                // Evita nomes com acentos, espaços e caracteres não alfanuméricos

                //$novoNome = uniqid ( time () ) . '.' . $extensao;
                $data = getDataHora();
                $data = str_replace(" ", "", $data);
                $data = str_replace("-", "", $data);
                $data = str_replace(":", "", $data);

                $novoNome = $data . $_SESSION['id_usuario'] . '.' . $extensao;

                // Concatena a pasta com o nome
                $destino = '../inc/upload-chamado/' . $novoNome;
                $arquivo_caminho = 'inc/upload-chamado/' . $novoNome;
                
                echo 'destino: '.$destino.'<br>';
                echo 'arquivo_tmp: '. $arquivo_tmp.'<br>';

                // tenta mover o arquivo para o destino
                if ( @move_uploaded_file ($arquivo_tmp, $destino) ) {
                    //echo 'Arquivo salvo com sucesso em : <strong>' . $destino . '</strong><br />';
                        
                    return $arquivo_caminho;
                } else {
                    return NULL;
                }
            } else {
                return NULL;
            }
        }
    } else {
        return null;
    }

}

function alteracaoPrazo($tempo, $prazo_encerramento_data, $prazo_encerramento_hora, $justificativa_alteracao, $id_contrato_plano_pessoa){

    $usuario_acao = $_SESSION['id_usuario'];
    $id = (int)$_POST['alteracao_prazo'];
    $dados = DBRead('','tb_chamado', "WHERE id_chamado = $id");

    $id_responsavel = $dados[0]['id_usuario_responsavel'];
    $visibilidade = $dados[0]['visibilidade'];
    $bloqueado = $dados[0]['bloqueado'];
    $id_chamado_status = $dados[0]['id_chamado_status'];
    $titulo = $dados[0]['titulo'];
    $data_criacao = $dados[0]['data_criacao'];
    $id_usuario_remetente = $dados[0]['id_usuario_remetente'];
    $id_chamado_origem = $dados[0]['id_chamado_origem'];
    $data_pendencia = $dados[0]['data_pendencia'];
    $prazo_encerramento = converteData($prazo_encerramento_data) . ' ' . $prazo_encerramento_hora;

    if(($usuario_acao != $id_responsavel && $usuario_acao != $id_usuario_remetente) && $bloqueado == 1){
        $alert = ('Você não tem permissão para alterar o prazo de encerramento deste chamado!', 'w');
        header("location: /api/iframe?token=$request->token&view=chamado-busca");
    }else{

        $data = getDataHora();

        $dados_acao = array(
            'data' => $data,
            'descricao' => $justificativa_alteracao,
            'visibilidade' => $visibilidade,
            'acao' => 'alteracao_prazo_encerramento',
            'bloqueado' => $bloqueado,
            'tempo' => $tempo,
            'prazo_encerramento' => $prazo_encerramento,
            'id_chamado' => $id,
            'id_usuario_responsavel' => $id_responsavel,
            'id_usuario_acao' => $usuario_acao,
            'id_chamado_status' => $id_chamado_status,
            'id_contrato_plano_pessoa' => $id_contrato_plano_pessoa
        );

        $link = DBConnect('');
        DBBegin($link);
        $insertID = DBCreateTransaction($link, 'tb_chamado_acao', $dados_acao, true);
        registraLogTransaction($link, 'Inserção de chamado ação.', 'i', 'tb_chamado_acao', $insertID, "data: $data | descricao: $justificativa_alteracao | visibilidade: $visibilidade | acao: alteracao_prazo_encerramento | bloqueado: $bloqueado | tempo: $tempo | prazo_encerramento: $prazo_encerramento | id_chamado: $id | id_usuario_responsavel: $id_responsavel | id_usuario_acao: $usuario_acao | id_chamado_status: $id_chamado_status | id_contrato_plano_pessoa: $id_contrato_plano_pessoa");

        $dados_array = array(
            'prazo_encerramento' => $prazo_encerramento
        );

        DBUpdateTransaction($link, 'tb_chamado', $dados_array, "id_chamado = '$id'");
        registraLogTransaction($link, 'Alteração de chamado.', 'i', 'tb_chamado', $id, "prazo_encerramento: $prazo_encerramento");

        DBCommit($link);

        $alert = ('Alteração de encerramento de chamado salvo com sucesso!', 's');
        header("location: /api/iframe?token=$request->token&view=chamado-informacoes&chamado=$id");
    }
}

function inserir($titulo, $tempo, $descricao, $visibilidade, $responsavel, $dados_categoria, $perfis, $usuarios, $id_contrato_plano_pessoa, $id_chamado_origem, $origem_chamado, $prazo_encerramento, $file = NULL){
    
    $data_criacao = getDataHora();
    $remetente = $_SESSION['id_usuario'];
    $id_usuario_acao = $_SESSION['id_usuario'];
    $id_chamado_status = '1';
    $bloqueado = '1';

    if($titulo != "" && $descricao != "" && $visibilidade != "" && $responsavel != "" && $tempo != ""){

        $dados = array(
            'data_criacao' => $data_criacao,
            'titulo' => $titulo,
            'descricao' => $descricao,
            'bloqueado' => $bloqueado,
            'id_usuario_remetente' => $remetente,
            'id_chamado_status' => $id_chamado_status,
            'visibilidade' => $visibilidade,
            'prazo_encerramento' => $prazo_encerramento,
            'id_usuario_responsavel' => $responsavel,
            'id_contrato_plano_pessoa' => $id_contrato_plano_pessoa,
            'id_chamado_origem' => $id_chamado_origem
        );

        $link = DBConnect('');
        DBBegin($link);

        $insertID = DBCreateTransaction($link, 'tb_chamado', $dados, true);
        registraLogTransaction($link, 'Inserção de chamado.', 'i', 'tb_chamado', $insertID, "data_criacao: $data_criacao | titulo: $titulo | descricao: $descricao | bloqueado: $bloqueado | id_usuario_remetente: $remetente | status: $id_chamado_status | visibilidade: $visibilidade | prazo_encerramento: $prazo_encerramento | id_usuario_responsavel: $responsavel | id_contrato_plano_pessoa: $id_contrato_plano_pessoa | id_chamado_origem: $id_chamado_origem");
        
        $acao = "criacao";

        foreach($dados_categoria as $conteudo){

            $id_categoria = $conteudo['id_categoria'];
    
            $dadosCategoria = array(
                'id_categoria' => $id_categoria,
                'id_chamado' => $insertID,
            );
            
            $insertCategoria = DBCreateTransaction($link, 'tb_chamado_categoria', $dadosCategoria, true);
            registraLogTransaction($link, 'Inserção de categoria chamado.','i','tb_chamado_categoria',$insertCategoria,"id_categoria: $id_categoria | id_chamado: $insertID");
            
        }

        if($file && $file['name'] != '' && $file['tmp_name'] != ''){
            $arquivo = salvarAnexo($file);
            if($arquivo == NULL){
                $alert = ('Você pode enviar apenas arquivos ".csv, .doc, .docx, .pdf, .ppt, .pptx, .rtf, .txt, .xls, .xlsx, .zip ou .rar!"', 'd', 'AVISO!');
                header("location: /api/iframe?token=$request->token&view=chamado-form");
                exit;
            }
        }else{
            $arquivo = NULL;
        }

        $dados_acao = array(
            'data' => $data_criacao,
            'descricao' => $descricao,
            'id_chamado_status' => $id_chamado_status,
            'visibilidade' => $visibilidade,
            'acao' => $acao,
            'tempo' => $tempo,
            'prazo_encerramento' => $prazo_encerramento,
            'id_chamado' => $insertID,
            'id_usuario_responsavel' => $responsavel,
            'id_usuario_acao' => $id_usuario_acao,
            'bloqueado' => $bloqueado,
            'id_contrato_plano_pessoa' => $id_contrato_plano_pessoa,
            'arquivo' => $arquivo
        );

        $insertAcao = DBCreateTransaction($link, 'tb_chamado_acao', $dados_acao, true);
        registraLogTransaction($link, 'Inserção de ação.','i','tb_chamado_acao',$insertAcao,"data: $data_criacao | descricao: $descricao | id_chamado_status: $id_chamado_status | visibilidade: $visibilidade | acao: $acao | tempo: $tempo | prazo_encerramento: $prazo_encerramento | id_chamado: $insertID | id_usuario_responsavel: $responsavel | id_usuario_acao: $id_usuario_acao | bloqueado: $bloqueado | id_contrato_plano_pessoa: $id_contrato_plano_pessoa | arquivo: $arquivo");
        
        if($visibilidade == '1'){
            /*$tema = DBReadTransaction($link, 'tb_usuario', "WHERE id_usuario = $remetente", "id_perfil_sistema");

            if(!in_array($id_perfil_sistema[0]['id_perfil_sistema'], $perfis)){
                array_push($perfis, $id_perfil_sistema[0]['id_perfil_sistema']);
            }*/

            foreach($perfis as $perfil){
                $dados = array(
                    'id_chamado' => $insertID,
                    'id_perfil_sistema' => $perfil
                );
                $insertChamadoPerfil = DBCreateTransaction($link, 'tb_chamado_perfil', $dados, true);
                registraLogTransaction($link, 'Inserção de chamado perfil.','i', 'tb_chamado_perfil',$insertChamadoPerfil,"id_chamado: $insertID | id_perfil_sistema: $perfil");
            }
        }

        if($visibilidade == '2'){
            array_push($usuarios, $remetente);
            foreach($usuarios as $usuario){
                $id_perfil_sistema = DBReadTransaction($link, 'tb_usuario', "WHERE id_usuario = $usuario", "id_perfil_sistema");
                $dados = array(
                    'id_chamado' => $insertID,
                    'id_usuario' => $usuario,
                    'id_perfil_sistema' => $id_perfil_sistema[0]['id_perfil_sistema']
                );
                $insertChamadoUsuario = DBCreateTransaction($link, 'tb_chamado_usuario', $dados, true);
                registraLogTransaction($link,'Inserção de chamado usuário.','i','tb_chamado_usuario',$insertChamadoUsuario,"id_chamado: $insertID | id_usuario: $usuario | id_perfil_sistema: '".$id_perfil_sistema[0]['id_perfil_sistema']."'");
            }
        }
  
        $dadosVisualizado = array(
            'data' => getDataHora(),
            'id_usuario' => $_SESSION['id_usuario'],
            'id_chamado_acao' => $insertAcao,
            'id_chamado' => $insertID
        );

        $idVisualizado = DBCreateTransaction($link, 'tb_chamado_visualizacao', $dadosVisualizado, true);
        registraLogTransaction($link,'Inserção de visualização de chamado.','i','tb_chamado_visualizacao', $idVisualizado, "data_visualizado: ".getDataHora()." | id_usuario: ".$_SESSION['id_usuario']." | id_chamado_acao: $insertAcao ");

        DBCommit($link);

        $alert = ('Chamado criado com sucesso!','s');
        if($origem_chamado == '1'){
            header("location: /api/iframe?token=$request->token&view=home&dash=chamados");

        }else{
            header("location: /api/iframe?token=$request->token&view=chamado-busca");
        }
        
    }else{
        $alert = ('Não foi possível criar o chamado!','w');
        if($origem_chamado == '1'){
            header("location: /api/iframe?token=$request->token&view=home&dash=chamados");

        }else{
            header("location: /api/iframe?token=$request->token&view=chamado-busca");
        }
    }
    exit;
}

function inserirNota($id, $tempo, $descricao_nota, $tipo, $file = NULL){
    $acao = "";
    if($tipo == 'interno'){
        $acao = 'nota_interna';
    }else if($tipo == 'geral'){
        $acao = 'nota_geral';
    }  

    if($tempo != "" && $descricao_nota != ""){
        
        $link = DBConnect('');
        DBBegin($link);

        $id_chamado_status = '2';
        $data_hora = getDataHora();

        $dados = DBReadTransaction($link,'tb_chamado', "WHERE id_chamado = $id");

        if($dados[0]['id_chamado_status'] == 3 || $dados[0]['id_chamado_status'] == 4){
            $alert = ('Não é possível inserir ações em um chamado encerrado!','d');
            header("location: /api/iframe?token=$request->token&view=chamado-informacoes&chamado=$id");
            exit;
        }

        if($dados[0]['data_pendencia'] != ''){
            $dados_array = array(
                'id_chamado_status' => $id_chamado_status,
                'data_pendencia' => $data_hora
            );
        }else{
            $dados_array = array(
                'id_chamado_status' => $id_chamado_status
            );
        }

        $insertID = DBUpdateTransaction($link, 'tb_chamado', $dados_array, "id_chamado = $id");
        registraLogTransaction($link, 'Alteração de chamado.', 'i', 'tb_chamado', $insertID, "id_chamado_status: $id_chamado_status | data_pendencia: $data_hora");

        $visibilidade = $dados[0]['visibilidade'];
        $id_usuario_responsavel = $dados[0]['id_usuario_responsavel'];
        $bloqueado = $dados[0]['bloqueado'];
        $id_contrato_plano_pessoa = $dados[0]['id_contrato_plano_pessoa'];
        $data_acao = getDataHora();
        $usuario_acao = $_SESSION['id_usuario'];
        $id_chamado_status = '2';

        if($file && $file['name'] != '' && $file['tmp_name'] != ''){
            $arquivo = salvarAnexo($file);
            if($arquivo == NULL){
                $alert = ('Você pode enviar apenas arquivos ".csv, .doc, .docx, .pdf, .ppt, .pptx, .rtf, .txt, .xls, .xlsx, .zip ou .rar!"', 'd', 'AVISO!');
                header("location: /api/iframe?token=$request->token&view=chamado-informacoes&chamado=$id");
                exit;
            }
        }else{
            $arquivo = NULL;
        }

        $dados = array(
            "data" => $data_acao,
            "descricao" => $descricao_nota,
            "id_chamado_status" => $id_chamado_status,
            "visibilidade" => $visibilidade,
            "acao" => $acao,
            "tempo" => $tempo,
            "id_chamado" => $id,
            "id_usuario_responsavel" => $id_usuario_responsavel,
            "id_usuario_acao" => $usuario_acao,
            'bloqueado' => $bloqueado,
            'id_contrato_plano_pessoa' => $id_contrato_plano_pessoa,
            'arquivo' => $arquivo
        );


        $insertID = DBCreateTransaction($link, 'tb_chamado_acao', $dados, true);
        registraLogTransaction($link, 'Inserção de nota chamado.','i','tb_chamado_acao',$insertID,"id_chamado: $id | data: $data_acao | descricao: $descricao_nota | id_chamado_status: $id_chamado_status| visibilidade: $visibilidade | acao: $acao | tempo: $tempo | id_usuario_responsavel: $id_usuario_responsavel | id_usuario_acao: $usuario_acao | bloqueado: $bloqueado | id_contrato_plano_pessoa: $id_contrato_plano_pessoa | arquivo: $arquivo");

        DBCommit($link);
        $alert = ('Nota inserida com sucesso!','s');
        header("location: /api/iframe?token=$request->token&view=chamado-informacoes&chamado=$id");
    }else{
        $alert = ('Não foi possível inserir a nota!','w');
        header("location: /api/iframe?token=$request->token&view=chamado-informacoes&chamado=$id");
    }
}

function encerrar($id, $tempo, $descricao, $id_chamado_status, $remetente, $responsavel, $usuario_acao, $id_contrato_plano_pessoa, $file = NULL){

    if($tempo != "" && $descricao != "" && $id_chamado_status != "" && $remetente != "" && $responsavel != ""){
        
        $link = DBConnect('');
        DBBegin($link);
        
        $dados = DBReadTransaction($link,'tb_chamado', "WHERE id_chamado = $id");
        $visibilidade = $dados[0]['visibilidade'];
        $bloqueado = $dados[0]['bloqueado'];
        $status = $dados[0]['id_chamado_status'];
        $data_pendencia = $dados[0]['data_pendencia'];
        $data_acao = getDataHora();
        $acao = 'encerrar';

        if($status == 3 || $status == 4){
            $alert = ('Chamado já encerrado!','w');
            header("location: /api/iframe?token=$request->token&view=chamado-busca");
            exit();
        }

        if($dados[0]['data_pendencia'] != ''){
            $dados_array = array(
                'id_chamado_status' => $id_chamado_status,
                'data_pendencia' => $data_acao
            );
        }else{
            $dados_array = array(
                'id_chamado_status' => $id_chamado_status
            );
        }

        $insertID = DBUpdateTransaction($link, 'tb_chamado', $dados_array, "id_chamado = $id");
        registraLogTransaction($link, 'Alteração de chamado.', 'i', 'tb_chamado', $insertID, "id_chamado_status: $id_chamado_status | data_pendencia: $data_acao");

        if($file && $file['name'] != '' && $file['tmp_name'] != ''){
            $arquivo = salvarAnexo($file);
            if($arquivo == NULL){
                $alert = ('Você pode enviar apenas arquivos ".csv, .doc, .docx, .pdf, .ppt, .pptx, .rtf, .txt, .xls, .xlsx, .zip ou .rar!"', 'd', 'AVISO!');
                header("location: /api/iframe?token=$request->token&view=chamado-informacoes&chamado=$id");
                exit;
            }
        }else{
            $arquivo = NULL;
        }

        $dados = array(
            'data' => $data_acao,
            'descricao' => $descricao,
            'visibilidade' => $visibilidade,
            'acao' => $acao,
            'tempo' => $tempo,
            'id_chamado' => $id,
            'id_usuario_responsavel' => $responsavel,
            'id_usuario_acao' => $usuario_acao,
            'id_chamado_status' => $id_chamado_status,
            'bloqueado' => $bloqueado,
            'id_contrato_plano_pessoa' => $id_contrato_plano_pessoa,
            'arquivo' => $arquivo
        );

        $insertID = DBCreateTransaction($link, 'tb_chamado_acao', $dados, true);
        registraLogTransaction($link,'Inserção de encerramento de chamado.','i','tb_chamado_acao',$insertID,"data: $data_acao | descricao: $descricao | visibilidade: $visibilidade | acao: encerrar | tempo: $tempo | id_chamado: $id | id_usuario_responsavel: $responsavel | id_usuario_acao: $usuario_acao, id_chamado_status: $id_chamado_status | bloqueado: $bloqueado | id_contrato_plano_pessoa: $id_contrato_plano_pessoa | arquivo: $arquivo");

        DBCommit($link);
        $alert = ('Chamado encerrado com sucesso!','s');
        header("location: /api/iframe?token=$request->token&view=chamado-informacoes&chamado=$id");
    }else{
        $alert = ('Não foi possível encerrar o chamado!','w');
        header("location: /api/iframe?token=$request->token&view=chamado-informacoes&chamado=$id");
    }
}

function reabrir($id, $tempo, $descricao, $responsavel, $usuario_acao, $id_contrato_plano_pessoa){

    $status_chamado = DBRead('', 'tb_chamado', "WHERE id_chamado = '$id'", "id_chamado_status");

    if($tempo != "" && $descricao != "" && $responsavel != "" && $usuario_acao != ""){

        if($status_chamado[0]['id_chamado_status'] != 1 && $status_chamado[0]['id_chamado_status'] != 2 && $status_chamado[0]['id_chamado_status'] != 5){

            $link = DBConnect('');
            DBBegin($link);
            $dados = DBReadTransaction($link,'tb_chamado', "WHERE id_chamado = $id");
            $visibilidade = $dados[0]['visibilidade'];
            $bloqueado = $dados[0]['bloqueado'];
            $data_acao = getDataHora();
            $acao = 'reabrir';
            $id_chamado_status = 5;

            if($status_chamado[0]['id_chamado_status'] == 5){
                $alert = ('Chamado já reaberto!','w');
                header("location: /api/iframe?token=$request->token&view=chamado-informacoes&chamado=$id");
                exit();
            }

            if($dados[0]['data_pendencia'] != ''){
                $dados_array = array(
                    'id_chamado_status' => $id_chamado_status,
                    'data_pendencia' => $data_acao
                );
            }else{
                $dados_array = array(
                    'id_chamado_status' => $id_chamado_status
                );
            }

            $insertID = DBUpdateTransaction($link, 'tb_chamado', $dados_array, "id_chamado = $id");
            registraLogTransaction($link, 'Alteração de chamado.', 'i', 'tb_chamado', $insertID, "id_chamado_status: $id_chamado_status | data_pendencia: $data_acao");

            $dados = array(
                'data' => $data_acao,
                'descricao' => $descricao,
                'visibilidade' => $visibilidade,
                'acao' => $acao,
                'tempo' => $tempo,
                'id_chamado' => $id,
                'id_usuario_responsavel' => $responsavel,
                'id_usuario_acao' => $usuario_acao,
                'id_chamado_status' => $id_chamado_status,
                'bloqueado' => $bloqueado,
                'id_contrato_plano_pessoa' => $id_contrato_plano_pessoa
            );

            $insertID = DBCreateTransaction($link, 'tb_chamado_acao', $dados, true);
            registraLogTransaction($link, 'Inserção de reabertura de chamado.','i','tb_chamado_acao',$insertID,"data: $data_acao | descricao: $descricao | visibilidade: $visibilidade | acao: encerrar | tempo: $tempo | id_chamado: $id | id_usuario_responsavel: $responsavel | id_usuario_acao: $usuario_acao, id_chamado_status: $id_chamado_status | bloqueado: $bloqueado | id_contrato_plano_pessoa: $id_contrato_plano_pessoa");
            
            DBCommit($link);
            $alert = ('Chamado reaberto com sucesso!','s');
            header("location: /api/iframe?token=$request->token&view=chamado-informacoes&chamado=$id");
        }else{

            $alert = ('Chamado já foi reaberto!','w');
            header("location: /api/iframe?token=$request->token&view=chamado-informacoes&chamado=$id");
        }
        
    }else{
        $alert = ('Não foi possível reabrir o chamado!','w');
        header("location: /api/iframe?token=$request->token&view=chamado-informacoes&chamado=$id");
    }
}

function trocaResponsavel($id, $visibilidade, $novoResponsavel, $justificativa, $tempo, $usuario_acao, $bloqueado, $id_contrato_plano_pessoa, $remetente, $antigoResponsavel, $file = NULL){

    if($visibilidade != "" && $novoResponsavel != "" && $justificativa != "" && $tempo != "" && $remetente != "" && $antigoResponsavel != ""){

        $link = DBConnect('');
        DBBegin($link);

        $data = getDataHora();
        $acao = 'encaminhar';
        $id_chamado_status = 2;

        $dados = DBReadTransaction($link,'tb_chamado', "WHERE id_chamado = $id");

        if($dados[0]['id_chamado_status'] == 3 || $dados[0]['id_chamado_status'] == 4){
            $alert = ('Não é possível inserir ações em um chamado encerrado!','d');
            header("location: /api/iframe?token=$request->token&view=chamado-informacoes&chamado=$id");
            exit;
        }

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
        registraLogTransaction($link, 'Alteração de chamado.', 'i', 'tb_chamado', $insertID, "visibilidade: $visibilidade | id_usuario_responsavel: $novoResponsavel | data_pendencia: $data");
        
        if($file && $file['name'] != '' && $file['tmp_name'] != ''){
            $arquivo = salvarAnexo($file);
            if($arquivo == NULL){
                $alert = ('Você pode enviar apenas arquivos ".csv, .doc, .docx, .pdf, .ppt, .pptx, .rtf, .txt, .xls, .xlsx, .zip ou .rar!"', 'd', 'AVISO!');
                header("location: /api/iframe?token=$request->token&view=chamado-informacoes&chamado=$id");
                exit;
            }
        }else{
            $arquivo = NULL;
        }
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
        /* removido temporáriamente
        $n_vezes = DBReadTransaction($link, 'tb_chamado_acao', "WHERE id_chamado = '$id' AND acao = 'encaminhar'", 'count(*) as n_vezes');

        $usuarios_envolvidos = array();

        if($n_vezes[0]['n_vezes'] == '1'){
            if($visibilidade == '1'){
                $perfis_envolvidos = DBReadTransaction($link, 'tb_chamado_perfil', "WHERE id_chamado = '$id'", 'id_perfil_sistema');

                foreach($perfis_envolvidos as $perfis){
                    $var = DBReadTransaction($link, 'tb_usuario', "WHERE id_perfil_sistema = '".$perfis['id_perfil_sistema']."' AND id_usuario != '$novoResponsavel' AND id_usuario != '$remetente'", 'id_usuario');

                    foreach($var as $v){
                        array_push($usuarios_envolvidos, $v['id_usuario']);
                    }
                }

                foreach($usuarios_envolvidos as $usuarios){

                    $id_usuario = $usuarios;

                    $dados = array(
                        'id_usuario' => $id_usuario,
                        'id_chamado' => $id
                    );

                    $insertUsuarioIgnora = DBCreateTransaction($link, 'tb_chamado_ignora', $dados, true);
                    registraLogTransaction($link,'Inserção de chamado ignora.','i','tb_chamado_ignora',$insertUsuarioIgnora,"id_chamado: $id | id_usuario: $id_usuario");
                }
            }//end if visibilidade = 1

            if($visibilidade == '2'){
                $usuarios_envolvidos = DBReadTransaction($link, 'tb_chamado_usuario', "WHERE id_chamado = '$id' AND id_usuario != '$novoResponsavel' AND id_usuario != '$remetente' ", 'id_usuario');

                foreach($usuarios_envolvidos as $usuarios){

                    $id_usuario = $usuarios['id_usuario'];
                    echo $id_usuario."<br>";

                    $dados = array(
                        'id_usuario' => $id_usuario,
                        'id_chamado' => $id
                    );
                    
                    $insertUsuarioIgnora = DBCreateTransaction($link, 'tb_chamado_ignora', $dados, true);
                    registraLogTransaction($link,'Inserção de chamado ignora.','i','tb_chamado_ignora',$insertUsuarioIgnora,"id_chamado: $id | id_usuario: $id_usuario");
                }
            }//end if visibilidade = 2
        }//end if $n_vezes[0]['n_vezes'] == '1'
        else{
           //ativar novo responsavel - desativar antigo responsanvel
           
            DBDeleteTransaction($link, 'tb_chamado_ignora', "id_usuario = '$novoResponsavel' AND id_chamado = '$id'");
            registraLogTransaction($link,'Exclusão de chamado ignora.','e','tb_chamado_ignora',$novoResponsavel,"id_chamado: $id | id_usuario: $novoResponsavel");

            $dados = array(
                'id_usuario' => $antigoResponsavel,
                'id_chamado' => $id
            );

            $insertUsuarioIgnora = DBCreateTransaction($link, 'tb_chamado_ignora', $dados, true);
            registraLogTransaction($link,'Inserção de chamado ignora.','i','tb_chamado_ignora',$insertUsuarioIgnora,"id_chamado: $id | id_usuario: $antigoResponsavel");
        }
        */

        DBCommit($link);
        $alert = ('Troca de responsável realizada com sucesso!','s');
        header("location: /api/iframe?token=$request->token&view=chamado-informacoes&chamado=$id");
    }else{
        $alert = ('Não foi possível trocar o responsável do chamado!','w');
        header("location: /api/iframe?token=$request->token&view=chamado-informacoes&chamado=$id");
    }
}

function desbloquear($id, $justificativa, $tempo, $visibilidade, $responsavel, $id_contrato_plano_pessoa){

    if($justificativa != "" && $tempo != "" && $visibilidade != "" && $responsavel != ""){

        $link = DBConnect('');
        DBBegin($link);
        $data = getDataHora();
        $acao = 'desbloquear';
        $id_usuario = $_SESSION['id_usuario'];
        $id_chamado_status = 2;
        $desbloquear = '2';

        $dados = DBReadTransaction($link,'tb_chamado', "WHERE id_chamado = $id");

        if($dados[0]['id_chamado_status'] == 3 || $dados[0]['id_chamado_status'] == 4){
            $alert = ('Não é possível inserir ações em um chamado encerrado!','d');
            header("location: /api/iframe?token=$request->token&view=chamado-informacoes&chamado=$id");
            exit;
        }

        if($dados[0]['data_pendencia'] != ''){
            $dados_array = array(
                'bloqueado' => $desbloquear,
                'id_chamado_status' => $id_chamado_status,
                'data_pendencia' => $data
            );
        }else{
            $dados_array = array(
                'bloqueado' => $desbloquear,
                'id_chamado_status' => $id_chamado_status,
            );
        }
       
        $updateID = DBUpdateTransaction($link, 'tb_chamado', $dados_array, "id_chamado = $id");
        registraLogTransaction($link, 'Alteração de chamado.', 'a', 'tb_chamado', $updateID, "bloqueado: $desbloquear | id_chamado: $updateID | data_pendencia: $data");

        $dados = array(
            'data' => $data,
            'descricao' => $justificativa,
            'visibilidade' => $visibilidade,
            'acao' => $acao,
            'tempo' => $tempo,
            'id_chamado' => $id,
            'id_usuario_responsavel' => $responsavel,
            'id_usuario_acao' => $id_usuario,
            'id_chamado_status' => $id_chamado_status,
            'bloqueado' => $desbloquear,
            'id_contrato_plano_pessoa' => $id_contrato_plano_pessoa
        );

        $insertID = DBCreateTransaction($link, 'tb_chamado_acao', $dados, true);
        registraLogTransaction($link, 'Inserção de chamado ação.', 'i', 'tb_chamado_acao', $insertID, "data: $data | descricao: $justificativa | visibilidade: $visibilidade | acao: $acao | tempo: $tempo | id_chamado: $id | id_usuario_responsavel: $responsavel | id_usuario_acao: $id_usuario | id_chamado_status: $id_chamado_status | bloqueado: $desbloquear | id_contrato_plano_pessoa: $id_contrato_plano_pessoa");

        DBCommit($link);
        $alert = ('Chamado desbloqueado com sucesso!','s');
        header("location: /api/iframe?token=$request->token&view=chamado-informacoes&chamado=$id");
    }else{
        $alert = ('Não foi possível desbloquear o chamado!','w');
        header("location: /api/iframe?token=$request->token&view=chamado-informacoes&chamado=$id");
    }
}

function bloquear($id, $descricao, $tempo, $visibilidade, $responsavel, $id_contrato_plano_pessoa){
    if($descricao != "" && $tempo != "" && $visibilidade != "" && $responsavel != ""){
        
        $link = DBConnect('');
        DBBegin($link);
        $data = getDataHora();
        $acao = 'bloquear';
        $id_usuario = $_SESSION['id_usuario'];
        $id_chamado_status = 2;
        $bloquear = '1';

        $dados = DBReadTransaction($link,'tb_chamado', "WHERE id_chamado = $id");

        if($dados[0]['id_chamado_status'] == 3 || $dados[0]['id_chamado_status'] == 4){
            $alert = ('Não é possível inserir ações em um chamado encerrado!','d');
            header("location: /api/iframe?token=$request->token&view=chamado-informacoes&chamado=$id");
            exit;
        }

        if($dados[0]['data_pendencia'] != ''){
            $dados_array = array(
                'bloqueado' => $bloquear,
                'id_chamado_status' => $id_chamado_status,
                'data_pendencia' => $data
            );
        }else{
            $dados_array = array(
                'bloqueado' => $bloquear,
                'id_chamado_status' => $id_chamado_status
            );
        }

        $updateID = DBUpdateTransaction($link, 'tb_chamado', $dados_array, "id_chamado = $id");
        registraLogTransaction($link, 'Alteração de chamado.', 'a', 'tb_chamado', $updateID, "bloqueado: $bloquear | id_chamado: $updateID | data_pendencia: $data");

        $dados = array(
            'data' => $data,
            'descricao' => $descricao,
            'visibilidade' => $visibilidade,
            'acao' => $acao,
            'tempo' => $tempo,
            'id_chamado' => $id,
            'id_usuario_responsavel' => $responsavel,
            'id_usuario_acao' => $id_usuario,
            'id_chamado_status' => $id_chamado_status,
            'bloqueado' => $bloquear,
            'id_contrato_plano_pessoa' => $id_contrato_plano_pessoa
        );

        $insertID = DBCreateTransaction($link, 'tb_chamado_acao', $dados, true);
        registraLogTransaction($link, 'Inserção de chamado ação.', 'i', 'tb_chamado_acao', $insertID, "data: $data | descricao: $descricao | visibilidade: $visibilidade | acao: $acao | tempo: $tempo | id_chamado: $id | id_usuario_responsavel: $responsavel | id_usuario_acao: $id_usuario | id_chamado_status: $id_chamado_status | bloqueado: $bloquear | id_contrato_plano_pessoa: $id_contrato_plano_pessoa");

        DBCommit($link);
        $alert = ('Chamado bloqueado com sucesso!','s');
        header("location: /api/iframe?token=$request->token&view=chamado-informacoes&chamado=$id");
    }else{
        $alert = ('Não foi possível bloquear o chamado!', 'w');
        header("location: /api/iframe?token=$request->token&view=chamado-informacoes&chamado=$id");
    }
}

function gerenciar($id, $justificativa, $visibilidade, $tempo, $perfis, $usuarios, $usuario_acao, $id_chamado_status, $bloqueado, $responsavel, $id_contrato_plano_pessoa, $remetente){ 

    if($usuario_acao != "" && $justificativa != "" && $tempo != "" && $id_chamado_status != "" && $visibilidade != ""){

        $link = DBConnect('');
        DBBegin($link);

        $data = getDataHora();
        $acao = 'gerenciar';
        $id_chamado_status = 2;

        $dados = DBReadTransaction($link,'tb_chamado', "WHERE id_chamado = $id");

        if($dados[0]['id_chamado_status'] == 3 || $dados[0]['id_chamado_status'] == 4){
            $alert = ('Não é possível inserir ações em um chamado encerrado!','d');
            header("location: /api/iframe?token=$request->token&view=chamado-informacoes&chamado=$id");
            exit;
        }

        if($dados[0]['data_pendencia'] != ''){
            $dados_array = array(
                'visibilidade' => $visibilidade,
                'id_chamado_status' => $id_chamado_status,
                'data_pendencia' => $data
            );
        }else{
            $dados_array = array(
                'visibilidade' => $visibilidade,
                'id_chamado_status' => $id_chamado_status
            );
        }

        DBUpdateTransaction($link, 'tb_chamado', $dados_array, "id_chamado = $id");
        
        registraLogTransaction($link, 'Alteração de chamado.', 'a', 'tb_chamado', $id, "id_chamado_status: $id_chamado_status | visibilidade: $visibilidade | data_pendencia: $data");

        //Insere em chamado ação
        $dados = array(
            'data' => $data,
            'descricao' => $justificativa,
            'visibilidade' => $visibilidade,
            'acao' => $acao,
            'tempo' => $tempo,
            'id_chamado' => $id,
            'id_usuario_responsavel' => $responsavel,
            'id_usuario_acao' => $usuario_acao,
            'id_chamado_status' => $id_chamado_status,
            'bloqueado' => $bloqueado,
            'id_contrato_plano_pessoa' => $id_contrato_plano_pessoa
        );
        $insertID = DBCreateTransaction($link, 'tb_chamado_acao', $dados, true);
        
        registraLogTransaction($link, 'Inserção de chamado ação.', 'i', 'tb_chamado_acao', $insertID, "data: $data | descricao: $justificativa | visibilidade: $visibilidade | acao: $acao | tempo: $tempo | id_chamado: $id | id_usuario_responsavel: $responsavel | id_usuario_acao: $usuario_acao | id_chamado_status: $id_chamado_status | bloqueado: $bloqueado | id_contrato_plano_pessoa: $id_contrato_plano_pessoa");
        
        if($visibilidade == 1){
            
            DBDeleteTransaction($link, 'tb_chamado_perfil', "id_chamado = '$id'");
            DBDeleteTransaction($link, 'tb_chamado_usuario', "id_chamado = '$id'");
            /*
            $id_perfil_sistema_responsavel = DBReadTransaction($link, 'tb_usuario', "WHERE id_usuario = $responsavel ", "id_perfil_sistema");

            $perfil_usuario = DBReadTransaction($link, 'tb_usuario', "WHERE id_usuario = '".$_SESSION['id_usuario']."'");

            if($perfis == ""){
                
                $perfis = array();

                if(!in_array($id_perfil_sistema_responsavel[0]['id_perfil_sistema'], $perfis)){
                    array_push($perfis, $id_perfil_sistema_responsavel[0]['id_perfil_sistema']);
                }
     
                if(!in_array($perfil_usuario[0]['id_perfil_sistema'], $perfis)){
                    array_push($perfis, $perfil_usuario[0]['id_perfil_sistema']);
                }

            }else{

                if(!in_array($id_perfil_sistema_responsavel[0]['id_perfil_sistema'], $perfis)){
                    array_push($perfis, $id_perfil_sistema_responsavel[0]['id_perfil_sistema']);
                }
       
                if(!in_array($perfil_usuario[0]['id_perfil_sistema'], $perfis)){
                    array_push($perfis, $perfil_usuario[0]['id_perfil_sistema']);
                }
            }
            */

            //Reinsere os perfis de acordo com o que vem da requisição, que possuem visibilidade no chamado
            foreach($perfis as $perfil){
                $dados = array(
                    'id_chamado' => $id,
                    'id_perfil_sistema' => $perfil
                );
                $insertChamadoPerfil = DBCreateTransaction($link, 'tb_chamado_perfil', $dados, true);
                registraLogTransaction($link, 'Inserção de chamado perfil.', 'i', 'tb_chamado_perfil', $insertChamadoPerfil, "id_chamado: $id | id_perfil_sistema: $perfil");
            }
    
        }else if($visibilidade == 2){

            DBDeleteTransaction($link, 'tb_chamado_perfil', "id_chamado = '$id'");
            DBDeleteTransaction($link, 'tb_chamado_usuario', "id_chamado = '$id'");

            if($usuarios == ""){
                $usuarios = array();
                if(!in_array($_SESSION['id_usuario'], $usuarios)){
                    array_push($usuarios, $_SESSION['id_usuario']);
                }
    
                if(!in_array($remetente, $usuarios)){
                    array_push($usuarios, $remetente);
                }
    
                if(!in_array($responsavel, $usuarios)){
                    array_push($usuarios, $responsavel);
                }
            }else{
                if(!in_array($_SESSION['id_usuario'], $usuarios)){
                    array_push($usuarios, $_SESSION['id_usuario']);
                }
    
                if(!in_array($remetente, $usuarios)){
                    array_push($usuarios, $remetente);
                }
    
                if(!in_array($responsavel, $usuarios)){
                    array_push($usuarios, $responsavel);
                }
            }
            
            //Reinsere os usuarios de acordo com o que vem da requisição, que possuem visibilidade no chamado
            foreach($usuarios as $usuario){
               
                $id_perfil_sistema = DBReadTransaction($link, 'tb_usuario', "WHERE id_usuario = $usuario", "id_perfil_sistema");
                
                $dados = array(
                    'id_chamado' => $id,
                    'id_usuario' => $usuario,
                    'id_perfil_sistema' => $id_perfil_sistema[0]['id_perfil_sistema']
                );

                $insertChamadoUsuario = DBCreateTransaction($link, 'tb_chamado_usuario', $dados, true);
                registraLogTransaction($link,'Inserção de chamado usuário.','i','tb_chamado_usuario',$insertChamadoUsuario,"id_chamado: $id | id_usuario: $usuario | id_perfil_sistema: $id_perfil_sistema");
            }
        }

        DBCommit($link);
        $alert = ('Chamado gerenciado com sucesso!','s');
        header("location: /api/iframe?token=$request->token&view=chamado-informacoes&chamado=$id");
    }
    else{
        $alert = ('Não foi possivel gerenciar o chamado!','w');
        header("location: /api/iframe?token=$request->token&view=chamado-informacoes&chamado=$id");
    }
}

function pendencia($id, $data, $hora, $descricao, $tempo, $visibilidade, $bloqueado, $responsavel, $usuario_acao, $id_chamado_status, $id_contrato_plano_pessoa){
    
    if($data != "" || $hora != "" || $descricao != "" || $tempo != "" || $visibilidade != "" || $bloqueado != "" || $responsavel != "" || $usuario_acao != "" || $id_chamado_status != "" || $id_contrato_plano_pessoa != "" ){

        $data_pendencia = $data." ".$hora;

        $data_acao = getDataHora();
        $acao = 'pendencia';
        
        $link = DBConnect('');
        DBBegin($link);

        $dados = DBReadTransaction($link,'tb_chamado', "WHERE id_chamado = $id");

        if($dados[0]['id_chamado_status'] == 3 || $dados[0]['id_chamado_status'] == 4){
            $alert = ('Não é possível inserir ações em um chamado encerrado!','d');
            header("location: /api/iframe?token=$request->token&view=chamado-informacoes&chamado=$id");
            exit;
        }

        //Insere em chamado ação
        $dados = array(
            'data' => $data_acao,
            'descricao' => $descricao,
            'visibilidade' => $visibilidade,
            'acao' => $acao,
            'tempo' => $tempo,
            'id_chamado' => $id,
            'id_usuario_responsavel' => $responsavel,
            'id_usuario_acao' => $usuario_acao,
            'id_chamado_status' => $id_chamado_status,
            'bloqueado' => $bloqueado,
            'id_contrato_plano_pessoa' => $id_contrato_plano_pessoa
        );

        $id_chamado_acao = DBCreateTransaction($link, 'tb_chamado_acao', $dados, true);

        registraLogTransaction($link, 'Inserção de chamado ação.', 'i', 'tb_chamado_acao', $id_chamado_acao, "data: $data_acao | descricao: $descricao | visibilidade: $visibilidade | acao: $acao | tempo: $tempo | id_chamado: $id | id_usuario_responsavel: $responsavel | id_usuario_acao: $usuario_acao | id_chamado_status: $id_chamado_status | bloqueado: $bloqueado | id_contrato_plano_pessoa: $id_contrato_plano_pessoa");

        $dados = array(
            'data' => converteDataHora($data_pendencia),
            'id_chamado' => $id,
            'id_chamado_acao' => $id_chamado_acao
        );

        $insertID = DBCreateTransaction($link, 'tb_chamado_pendencia', $dados, true);
        registraLogTransaction($link, 'Inserção de pendencia no chamado.', 'i', 'tb_chamado_pendencia', $insertID, "data: '".converteDataHora($data_pendencia)."' | id_chamado: $id | id_chamado_acao: $id_chamado_acao'");

        $dados = array(
            'data_pendencia' => converteDataHora($data_pendencia)
        );

        DBUpdateTransaction($link, 'tb_chamado', $dados, "id_chamado = '$id'");
        registraLogTransaction($link, 'Alteração de chamado.', 'a', 'tb_chamado_a', $id, "data_pendencia: '".converteDataHora($data_pendencia)."' ");
 
        DBCommit($link);
        $alert = ('Pendência criada com sucesso!','s');
        header("location: /api/iframe?token=$request->token&view=chamado-informacoes&chamado=$id");
    }else{
        $alert = ('Não foi possivel criar a pendência!','w');
        header("location: /api/iframe?token=$request->token&view=chamado-informacoes&chamado=$id");
    }
}

function alterar($id, $justificativa, $visibilidade, $tempo, $usuario_acao, $id_chamado_status, $bloqueado, $responsavel, $id_contrato_plano_pessoa, $dados_categoria, $id_origem){

    if($tempo != "" && $justificativa != "" && $usuario_acao != "" && $id_origem != ""){
        if($id_origem != 1){

            $link = DBConnect('');
            DBBegin($link);

            $data_acao = getDataHora();
            $acao = 'alterar';

            if($id_chamado_status == 3 && $id_chamado_status == 4){
                $alert = ('Chamado já foi encerrado!','w');
                header("location: /api/iframe?token=$request->token&view=chamado-informacoes&chamado=$id");
                exit();
            }

            $dados = DBReadTransaction($link,'tb_chamado', "WHERE id_chamado = $id");

            if($dados[0]['id_chamado_status'] == 3 || $dados[0]['id_chamado_status'] == 4){
                $alert = ('Não é possível inserir ações em um chamado encerrado!','d');
                header("location: /api/iframe?token=$request->token&view=chamado-informacoes&chamado=$id");
                exit;
            }

            if($dados[0]['data_pendencia'] != ''){
                $dados_array = array(
                    //'id_categoria' => $id_categoria,
                    'id_chamado_origem' => $id_origem,
                    'id_contrato_plano_pessoa' => $id_contrato_plano_pessoa,
                    'data_pendencia' => $data_acao
                );
            }else{
                $dados_array = array(
                    //'id_categoria' => $id_categoria,
                    'id_chamado_origem' => $id_origem,
                    'id_contrato_plano_pessoa' => $id_contrato_plano_pessoa
                );
            }

            DBUpdateTransaction($link, 'tb_chamado', $dados_array, "id_chamado = '$id'");
            registraLogTransaction($link, 'Alteração de chamado.', 'i', 'tb_chamado', $id, "id_chamado_origem: $id_origem | id_contrato_plano_pessoa: $id_contrato_plano_pessoa | data_pendencia: $data_acao");

            DBDeleteTransaction($link, 'tb_chamado_categoria', "id_chamado = '$id'");

            foreach($dados_categoria as $conteudo){
                $id_categoria = $conteudo['id_categoria'];
                $dadosCategoria = array(
                    'id_categoria' => $id_categoria,
                    'id_chamado' => $id,
                );
                $insertCategoria = DBCreateTransaction($link, 'tb_chamado_categoria', $dadosCategoria, true);
                registraLogTransaction($link, 'Inserção de categoria no chamado.','i','tb_chamado_categoria',$insertCategoria,"id_categoria: $id_categoria | id_chamado: $id");
            }

            $dados = array(
                'data' => $data_acao,
                'descricao' => $justificativa,
                'visibilidade' => $visibilidade,
                'acao' => $acao,
                'tempo' => $tempo,
                'id_chamado' => $id,
                'id_usuario_responsavel' => $responsavel,
                'id_usuario_acao' => $usuario_acao,
                'id_chamado_status' => $id_chamado_status,
                'bloqueado' => $bloqueado,
                'id_contrato_plano_pessoa' => $id_contrato_plano_pessoa
            );

            $insertID = DBCreateTransaction($link, 'tb_chamado_acao', $dados, true);
            registraLogTransaction($link, 'Inserção de reabertura de chamado.','i','tb_chamado_acao',$insertID,"data: $data_acao | descricao: $justificativa | visibilidade: $visibilidade | acao: encerrar | tempo: $tempo | id_chamado: $id | id_usuario_responsavel: $responsavel | id_usuario_acao: $usuario_acao, id_chamado_status: $id_chamado_status | bloqueado: $bloqueado | id_contrato_plano_pessoa: $id_contrato_plano_pessoa");
            
            DBCommit($link);
            $alert = ('Chamado alterado com sucesso!','s');
            header("location: /api/iframe?token=$request->token&view=chamado-informacoes&chamado=$id");
        }else{
            $alert = ('Chamado não pode ser alterado!','w');
            header("location: /api/iframe?token=$request->token&view=chamado-informacoes&chamado=$id");
        }
    }else{
        $alert = ('Não foi possível alterar o chamado!','w');
        header("location: /api/iframe?token=$request->token&view=chamado-informacoes&chamado=$id");
    }
}

function assumirChamado($id, $visibilidade, $justificativa, $tempo, $usuario_acao, $bloqueado, $id_contrato_plano_pessoa, $remetente, $antigoResponsavel){

    if($visibilidade != "" && $justificativa != "" && $tempo != "" && $remetente !="" && $antigoResponsavel != ""){

        $link = DBConnect('');
        DBBegin($link);

        $data = getDataHora();
        $acao = 'assumir';
        $id_chamado_status = 2;

        $dados = DBReadTransaction($link,'tb_chamado', "WHERE id_chamado = $id");

        if($dados[0]['id_chamado_status'] == 3 || $dados[0]['id_chamado_status'] == 4){
            $alert = ('Não é possível inserir ações em um chamado encerrado!','d');
            header("location: /api/iframe?token=$request->token&view=chamado-informacoes&chamado=$id");
            exit;
        }

        if($dados[0]['data_pendencia'] != ''){
            $dados_array = array(
                'id_usuario_responsavel' => $usuario_acao,
                'id_chamado_status' => $id_chamado_status,
                'data_pendencia' => $data
            );
        }else{
            $dados_array = array(
                'id_usuario_responsavel' => $usuario_acao,
                'id_chamado_status' => $id_chamado_status
            );
        }

        $insertID = DBUpdateTransaction($link, 'tb_chamado', $dados_array, "id_chamado = $id");
        registraLogTransaction($link, 'Alteração de chamado.', 'i', 'tb_chamado', $insertID, "id_usuario_responsavel: $usuario_acao | data_pendencia: $data");
        
        //Insere em chamado ação
        $dados = array(
            'data' => $data,
            'descricao' => $justificativa,
            'visibilidade' => $visibilidade,
            'acao' => $acao,
            'tempo' => $tempo,
            'id_chamado' => $id,
            'id_usuario_responsavel' => $usuario_acao,
            'id_usuario_acao' => $usuario_acao,
            'id_chamado_status' => $id_chamado_status,
            'bloqueado' => $bloqueado,
            'id_contrato_plano_pessoa' => $id_contrato_plano_pessoa
        );

        $insertID = DBCreateTransaction($link, 'tb_chamado_acao', $dados, true);
        registraLogTransaction($link, 'Inserção de chamado ação.', 'i', 'tb_chamado_acao', $insertID, "data: $data | descricao: $justificativa | visibilidade: $visibilidade | acao: $acao | tempo: $tempo | id_chamado: $id | id_usuario_responsavel: $usuario_acao | id_usuario_acao: $usuario_acao | id_chamado_status: $id_chamado_status | bloqueado: $bloqueado | id_contrato_plano_pessoa: $id_contrato_plano_pessoa");
        /* removido temporáriamente
        $n_vezes = DBReadTransaction($link, 'tb_chamado_acao', "WHERE id_chamado = '$id' AND acao = 'encaminhar'", 'count(*) as n_vezes');

        $usuarios_envolvidos = array();

        if($n_vezes[0]['n_vezes'] == '1'){
            if($visibilidade == '1'){
                $perfis_envolvidos = DBReadTransaction($link, 'tb_chamado_perfil', "WHERE id_chamado = '$id'", 'id_perfil_sistema');

                foreach($perfis_envolvidos as $perfis){
                    $var = DBReadTransaction($link, 'tb_usuario', "WHERE id_perfil_sistema = '".$perfis['id_perfil_sistema']."' AND id_usuario != '$usuario_acao' AND id_usuario != '$remetente'", 'id_usuario');
                    foreach($var as $v){
                        array_push($usuarios_envolvidos, $v['id_usuario']);
                    }
                }
                foreach($usuarios_envolvidos as $usuarios){
                    $id_usuario = $usuarios;
                    $dados = array(
                        'id_usuario' => $id_usuario,
                        'id_chamado' => $id
                    );
                    $insertUsuarioIgnora = DBCreateTransaction($link, 'tb_chamado_ignora', $dados, true);
                    registraLogTransaction($link,'Inserção de chamado ignora.','i','tb_chamado_ignora',$insertUsuarioIgnora,"id_chamado: $id | id_usuario: $id_usuario");
                }
            }//end if visibilidade = 1

            if($visibilidade == '2'){
                $usuarios_envolvidos = DBReadTransaction($link, 'tb_chamado_usuario', "WHERE id_chamado = '$id' AND id_usuario != '$usuario_acao' AND id_usuario != '$remetente' ", 'id_usuario');

                foreach($usuarios_envolvidos as $usuarios){

                    $id_usuario = $usuarios['id_usuario'];

                    $dados = array(
                        'id_usuario' => $id_usuario,
                        'id_chamado' => $id
                    );
                    
                    $insertUsuarioIgnora = DBCreateTransaction($link, 'tb_chamado_ignora', $dados, true);
                    registraLogTransaction($link,'Inserção de chamado ignora.','i','tb_chamado_ignora',$insertUsuarioIgnora,"id_chamado: $id | id_usuario: $id_usuario");
                }
            }//end if visibilidade = 2
        }//end if $n_vezes[0]['n_vezes'] == '1'
        else{
            //ativar novo responsavel - desativar antigo responsanvel
           
            DBDeleteTransaction($link, 'tb_chamado_ignora', "id_usuario = '$usuario_acao' AND id_chamado = '$id'");
            registraLogTransaction($link,'Exclusão de chamado ignora.','e','tb_chamado_ignora',$usuario_acao,"id_chamado: $id | id_usuario: $usuario_acao");

            $dados = array(
                'id_usuario' => $antigoResponsavel,
                'id_chamado' => $id
            );

            $insertUsuarioIgnora = DBCreateTransaction($link, 'tb_chamado_ignora', $dados, true);
            registraLogTransaction($link,'Inserção de chamado ignora.','i','tb_chamado_ignora',$insertUsuarioIgnora,"id_chamado: $id | id_usuario: $antigoResponsavel");
        }
        */

        DBCommit($link);
        $alert = ('Troca de responsável realizada com sucesso!','s');
        header("location: /api/iframe?token=$request->token&view=chamado-informacoes&chamado=$id");
    }else{
        $alert = ('Não foi possível trocar o responsável do chamado!','w');
        header("location: /api/iframe?token=$request->token&view=chamado-informacoes&chamado=$id");
    }
    exit;
}

function cancelar_envolvimento($id){

    $id_usuario = $_SESSION["id_usuario"];
    $dados_usuario = DBRead('','tb_usuario',"WHERE id_usuario = '$id_usuario'");
    $perfil_usuario = $dados_usuario[0]['id_perfil_sistema'];

    $dados_chamado = DBRead('','tb_chamado',"WHERE id_chamado = '$id'");

   

    if($perfil_usuario == 20){

        $dados = array(
            'data' => getDataHora(),
            'descricao' => '<p>Remoção de envolvido.</p>',
            'visibilidade' => $dados_chamado[0]['visibilidade'],
            'acao' => 'gerenciar',
            'tempo' => '1',
            'id_chamado' => $id,
            'id_usuario_responsavel' => $dados_chamado[0]['id_usuario_responsavel'],
            'id_usuario_acao' => $id_usuario,
            'id_chamado_status' => $dados_chamado[0]['id_chamado_status'],
            'bloqueado' => $dados_chamado[0]['bloqueado'],
            'id_contrato_plano_pessoa' => $dados_chamado[0]['id_contrato_plano_pessoa']
        );
        DBCreate('', 'tb_chamado_acao', $dados);

        DBDelete('','tb_chamado_perfil',"id_chamado = '$id' AND id_perfil_sistema = '$perfil_usuario'");
        DBDelete('','tb_chamado_usuario',"id_chamado = '$id' AND id_usuario = '$id_usuario'");

        $alert = ('Operação realizada com sucesso!','s');
        header("location: /api/iframe?token=$request->token&view=home&dash=chamados");
    }else{
        $alert = ('Você não tem permissão para realizar essa operação!','d');
        header("location: /api/iframe?token=$request->token&view=chamado-informacoes&chamado=$id");
    }
    exit;
}

?>