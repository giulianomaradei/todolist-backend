<?php
require_once("System.php");
verificaSessao("../");

$id_resposta = (!empty($_POST['id_resposta'])) ? $_POST['id_resposta'] : '';
$busca_resposta = (!empty($_POST['busca_resposta'])) ? $_POST['busca_resposta'] : '';
$id_texto_os = (!empty($_POST['id_texto_os'])) ? $_POST['id_texto_os'] : '';
$busca_texto_os = (!empty($_POST['busca_texto_os'])) ? $_POST['busca_texto_os'] : '';
$id_pergunta = (!empty($_POST['id_pergunta'])) ? $_POST['id_pergunta'] : '';
$busca_pergunta = (!empty($_POST['busca_pergunta'])) ? $_POST['busca_pergunta'] : '';
$id_subarea_problema = (!empty($_POST['id_subarea_problema'])) ? $_POST['id_subarea_problema'] : '';
$resolvido = (!empty($_POST['resolvido'])) ? $_POST['resolvido'] : '';
$contrato = (!empty($_POST['contrato'])) ? $_POST['contrato'] : Array();
$exibe_texto_os = (!empty($_POST['exibe_texto_os'])) ? $_POST['exibe_texto_os'] : Array();
$complemento = (!empty($_POST['complemento'])) ? $_POST['complemento'] : '';
$anotacao_padrao = (!empty($_POST['anotacao_padrao'])) ? $_POST['anotacao_padrao'] : '';
$id_inicio = (!empty($_POST['id_inicio'])) ? $_POST['id_inicio'] : 1;
$nivel_limite = (!empty($_POST['nivel_limite'])) ? $_POST['nivel_limite'] : 0;
$contrato_select = (!empty($_POST['contrato_select'])) ? $_POST['contrato_select'] : '';

$informacoes_qi = (!empty($_POST['informacoes_qi'])) ? $_POST['informacoes_qi'] : Array();

$clone = (!empty($_POST['clone'])) ? $_POST['clone'] : '';
$id_contrato_plano_pessoa = (!empty($_POST['id_contrato_plano_pessoa'])) ? $_POST['id_contrato_plano_pessoa'] : '';


$tag = (!empty($_POST['tag'])) ? $_POST['tag'] : '';


if(!empty($_POST['inserir'])){

    $id = (int)$_POST['alterar'];
    $dados_informacoes_qi = join("|", $informacoes_qi);

    $id_pai = (int)$_POST['inserir'];

    if($id_resposta == ''){
        $dados = DBRead('', 'tb_resposta', "WHERE BINARY nome = '".addslashes($busca_resposta)."'");
        if($dados){
            $id_resposta = $dados[0]['id_resposta'];
        }else{
            $dados = array(
                'nome' => $busca_resposta
            );
            $id_resposta = DBCreate('', 'tb_resposta', $dados, true);
            registraLog('Inserção de resposta.','i','tb_resposta',$id_resposta,"nome: $nome");
        }
    }
    if($id_texto_os == ''){
        $dados = DBRead('', 'tb_texto_os', "WHERE BINARY nome = '".addslashes($busca_texto_os)."'");
        if($dados){
            $id_texto_os = $dados[0]['id_texto_os'];
        }else{
            $dados = array(
                'nome' => $busca_texto_os
            );
            $id_texto_os = DBCreate('', 'tb_texto_os', $dados, true);
            registraLog('Inserção de texto de OS.','i','tb_texto_os',$id_texto_os,"nome: $nome");
        }        
    }
    if($id_pergunta == ''){
        $dados = DBRead('', 'tb_pergunta', "WHERE BINARY nome = '".addslashes($busca_pergunta)."'");
        if($dados){
            $id_pergunta = $dados[0]['id_pergunta'];
        }else{
            $dados = array(
                'nome' => $busca_pergunta
            );
            $id_pergunta = DBCreate('', 'tb_pergunta', $dados, true);
            registraLog('Inserção de pergunta.','i','tb_pergunta',$id_pergunta,"nome: $nome");
        }
        
    }

    inserir($id_pai, $id_resposta, $id_texto_os, $id_pergunta, $id_subarea_problema, $resolvido, $contrato, $exibe_texto_os, $complemento, $id_inicio, $nivel_limite, $dados_informacoes_qi, $anotacao_padrao, $tag, $contrato_select);

} else if(!empty($_POST['alterar'])){

    $id = (int)$_POST['alterar'];
    $dados_informacoes_qi = join("|", $informacoes_qi);

    if($id_resposta == ''){
        $dados = DBRead('', 'tb_resposta', "WHERE BINARY nome = '".addslashes($busca_resposta)."'");
        if($dados){
            $id_resposta = $dados[0]['id_resposta'];
        }else{        
            $dados = array(
                'nome' => $busca_resposta
            );
            $id_resposta = DBCreate('', 'tb_resposta', $dados, true);
            registraLog('Inserção de resposta.','i','tb_resposta',$id_resposta,"nome: $nome");
        }        
    }
    if($id_texto_os == ''){
        $dados = DBRead('', 'tb_texto_os', "WHERE BINARY nome = '".addslashes($busca_texto_os)."'");
        if($dados){
            $id_texto_os = $dados[0]['id_texto_os'];
        }else{
            $dados = array(
                'nome' => $busca_texto_os
            );
            $id_texto_os = DBCreate('', 'tb_texto_os', $dados, true);
            registraLog('Inserção de texto de OS.','i','tb_texto_os',$id_texto_os,"nome: $nome");
        }        
    }
    if($id_pergunta == ''){
        $dados = DBRead('', 'tb_pergunta', "WHERE BINARY nome = '".addslashes($busca_pergunta)."'");
        if($dados){
            $id_pergunta = $dados[0]['id_pergunta'];
        }else{
            $dados = array(
                'nome' => $busca_pergunta
            );
            $id_pergunta = DBCreate('', 'tb_pergunta', $dados, true);
            registraLog('Inserção de pergunta.','i','tb_pergunta',$id_pergunta,"nome: $nome");
        }
        
    }

    alterar($id, $id_resposta, $id_texto_os, $id_pergunta, $id_subarea_problema, $resolvido, $contrato, $exibe_texto_os, $complemento, $id_inicio, $nivel_limite, $dados_informacoes_qi, $anotacao_padrao, $tag, $contrato_select);

}else if(!empty($_POST['clonar'])){

    $id = (int)$_POST['clonar'];
    $id_destino = (!empty($_POST['id_destino'])) ? $_POST['id_destino'] : '';

    clonar($id, $id_destino, $id_inicio, $nivel_limite, $contrato_select);

} else if(!empty($_POST['mover'])){

    $id = (int)$_POST['mover'];
    $id_destino = (!empty($_POST['id_destino'])) ? $_POST['id_destino'] : '';

    mover($id, $id_destino, $id_inicio, $nivel_limite, $contrato_select);

} else if (isset($_GET['excluir'])) {

    $id = (int)$_GET['excluir'];
    $id_inicio = (isset($_GET['id_inicio'])) ? $_GET['id_inicio'] : 1;
    $nivel_limite = (isset($_GET['nivel_limite'])) ? $_GET['nivel_limite'] : 0;
    $contrato_select = (isset($_GET['contrato_select'])) ? $_GET['contrato_select'] : '';

    excluir($id, $id_inicio, $nivel_limite, $contrato_select);

}else if(isset($_GET['excluir_arvore'])){

    $id = (int) $_GET['excluir_arvore'];

    excluirArvoreContrato($id);

}else if(!empty($_POST['clonar_arvore'])){
    
    clonarArvoreContrato($clone, $id_contrato_plano_pessoa);

}else{
    header("location: ../adm.php");
    exit;
}

function inserir($id_pai, $id_resposta, $id_texto_os, $id_pergunta, $id_subarea_problema, $resolvido, $contrato, $exibe_texto_os, $complemento, $id_inicio, $nivel_limite, $dados_informacoes_qi, $anotacao_padrao, $tag, $contrato_select){

    $dados = array(
        'id_pai' => $id_pai,
        'id_resposta' => $id_resposta,
        'id_texto_os' => $id_texto_os,
        'id_pergunta' => $id_pergunta,
        'id_subarea_problema' => $id_subarea_problema,
        'resolvido' => $resolvido,
        'complemento' => $complemento,
        'anotacao_padrao' => $anotacao_padrao,
        'cliques' => '0',
        'quadro_informativo' => $dados_informacoes_qi,
        'tag' => $tag
    );
    $insertID = DBCreate('', 'tb_arvore', $dados, true);    
    registraLog('Inserção opção da árvore.','i','tb_arvore',$insertID,"id_pai: $id_pai | id_resposta: $id_resposta | id_texto_os: $id_texto_os | id_pergunta: $id_pergunta | id_subarea_problema: $id_subarea_problema | resolvido: $resolvido | complemento: $complemento | cliques: 0 | quadro_informativo: $dados_informacoes_qi | anotacao_padrao: $anotacao_padrao | tag: $tag");

    foreach($contrato as $id_contrato_plano_pessoa){
        $exibe_texto_os_contrato = 0;
        foreach($exibe_texto_os as $id_contrato_exibe_texto_os){
            if($id_contrato_exibe_texto_os == $id_contrato_plano_pessoa){
                $exibe_texto_os_contrato = 1;
            }
        }

        $dados = array(
            'id_arvore' => $insertID,
            'id_contrato_plano_pessoa' => $id_contrato_plano_pessoa,
            'exibe_texto_os' => $exibe_texto_os_contrato
        );
        $arvore_contrato = DBCreate('', 'tb_arvore_contrato', $dados, true);
        registraLog('Inserção de opção de árvore a contrato.','i','tb_arvore_pessoa', $arvore_contrato, "id_arvore: $insertID | id_contrato_plano_pessoa: $id_contrato_plano_pessoa | exibe_texto_os: $exibe_texto_os_contrato");
    }

    $alert = ('Item inserido com sucesso!');
    $alert_type = 's';    header("location: /api/iframe?token=$request->token&view=arvore-exibe&id=$id_inicio&nivel_limite=$nivel_limite&id_contrato_plano_pessoa=$contrato_select");
    exit;

}

function alterar($id, $id_resposta, $id_texto_os, $id_pergunta, $id_subarea_problema, $resolvido, $contrato, $exibe_texto_os, $complemento, $id_inicio, $nivel_limite, $dados_informacoes_qi, $anotacao_padrao, $tag, $contrato_select){

    $dados = array(        
        'id_resposta' => $id_resposta,
        'id_texto_os' => $id_texto_os,
        'id_pergunta' => $id_pergunta,
        'id_subarea_problema' => $id_subarea_problema,
        'resolvido' => $resolvido,
        'complemento' => $complemento,
        'anotacao_padrao' => $anotacao_padrao,
        'quadro_informativo' => $dados_informacoes_qi,
        'tag' => $tag
    );

    DBUpdate('', 'tb_arvore', $dados, "id_arvore = '$id'");
    registraLog('Alteração opção da árvore.','a','tb_arvore',$id,"id_resposta: $id_resposta | id_texto_os: $id_texto_os | id_pergunta: $id_pergunta | id_subarea_problema: $id_subarea_problema | resolvido: $resolvido | complemento: $complemento | anotacao_padrao: $anotacao_padrao | tag: $tag");

    $dados_contrato_bd = DBRead('','tb_arvore_contrato',"WHERE id_arvore = '$id'");
    
    if($dados_contrato_bd){
        foreach ($dados_contrato_bd as $conteudo_contrato_bd) {
            $flag = 0;
            foreach ($contrato as $id_contrato_plano_pessoa) {
                if($id_contrato_plano_pessoa == $conteudo_contrato_bd['id_contrato_plano_pessoa']){
                    $flag = 1;
                }
            }
            if(!$flag){
                DBDelete('','tb_arvore_contrato',"id_arvore = '$id' AND id_contrato_plano_pessoa = '".$conteudo_contrato_bd['id_contrato_plano_pessoa']."'");
            }
        }
    }
    foreach ($contrato as $id_contrato_plano_pessoa) {
        $exibe_texto_os_contrato = 0;
        foreach ($exibe_texto_os as $id_contrato_exibe_texto_os){
            if($id_contrato_exibe_texto_os == $id_contrato_plano_pessoa){
                $exibe_texto_os_contrato = 1;
            }
        }
        $dados_contrato_bd = DBRead('','tb_arvore_contrato',"WHERE id_arvore = '$id' AND id_contrato_plano_pessoa = '$id_contrato_plano_pessoa'");
        if($dados_contrato_bd){
            $dados = array(                
                'exibe_texto_os' => $exibe_texto_os_contrato
            );
            DBUpdate('', 'tb_arvore_contrato', $dados, "id_arvore = '$id' AND id_contrato_plano_pessoa = '$id_contrato_plano_pessoa'");
        }else{
            $dados = array(
                'id_arvore' => $id,
                'id_contrato_plano_pessoa' => $id_contrato_plano_pessoa,
                'exibe_texto_os' => $exibe_texto_os_contrato
            );            
            $arvore_contrato = DBCreate('', 'tb_arvore_contrato', $dados, true);
        }
    } 

    $alert = ('Item alterado com sucesso!');
    $alert_type = 's';    header("location: /api/iframe?token=$request->token&view=arvore-exibe&id=$id_inicio&nivel_limite=$nivel_limite&id_contrato_plano_pessoa=$contrato_select");
    exit;

}

function clonaFilhos($id, $id_destino, $clonados){
    $dados = DBRead('','tb_arvore',"WHERE id_pai = '$id'");
    if($dados){
        foreach($dados as $conteudo){
            $flag_cloado = 0;
            foreach($clonados as $conteudo_clonados){
                if($conteudo['id_arvore'] == $conteudo_clonados){
                    $flag_cloado = 1;
                }
            }
            if(!$flag_cloado){
                $dados = array(
                    'id_pai' => $id_destino,
                    'id_resposta' => $conteudo['id_resposta'],
                    'id_pergunta' => $conteudo['id_pergunta'],
                    'id_texto_os' => $conteudo['id_texto_os'],
                    'id_subarea_problema' => $conteudo['id_subarea_problema'],
                    'resolvido' => $conteudo['resolvido'],
                    'complemento' => $conteudo['complemento'],
                    'anotacao_padrao' => $conteudo['anotacao_padrao'],
                    'quadro_informativo' => $conteudo['quadro_informativo'],
                    'cliques' => $conteudo['cliques'],
                    'tag' => $conteudo['tag']

                );
                $insertID = DBCreate('', 'tb_arvore', $dados, true);    
                registraLog('Clone de opção da árvore.','i','tb_arvore',$insertID,"id: ".$conteudo['id_arvore']." | id_destino: $insertID");

                $dados_arvore_contrato = DBRead('','tb_arvore_contrato',"WHERE id_arvore = '".$conteudo['id_arvore']."'");
                if($dados_arvore_contrato){
                    foreach($dados_arvore_contrato as $conteudo_arvore_contrato){
                        $dados_novos = array(
                            'id_arvore' => $insertID,
                            'id_contrato_plano_pessoa' => $conteudo_arvore_contrato['id_contrato_plano_pessoa'],
                            'exibe_texto_os' => $conteudo_arvore_contrato['exibe_texto_os']
                        );
                        DBCreate('', 'tb_arvore_contrato', $dados_novos);
                    }
                }

                $clonados[] = $insertID;
                clonaFilhos($conteudo['id_arvore'], $insertID, $clonados);
            }         
        }
    }
}

function clonar($id, $id_destino, $id_inicio, $nivel_limite, $contrato_select){

    $clonados = array();

    $dados = DBRead('','tb_arvore',"WHERE id_arvore = '$id'");   
    $dados_novos = array(
        'id_pai' => $id_destino,
        'id_resposta' => $dados[0]['id_resposta'],
        'id_pergunta' => $dados[0]['id_pergunta'],
        'id_texto_os' => $dados[0]['id_texto_os'],
        'id_subarea_problema' => $dados[0]['id_subarea_problema'],
        'resolvido' => $dados[0]['resolvido'],
        'complemento' => $dados[0]['complemmento'],
        'anotacao_padrao' => $dados[0]['anotacao_padrao'],
        'quadro_informativo' => $dados[0]['quadro_informativo'],
        'cliques' => $dados[0]['cliques'],
        'tag' => $dados[0]['tag']
    );    
    $insertID = DBCreate('', 'tb_arvore', $dados_novos, true);    
    registraLog('Clone de opção da árvore.','i','tb_arvore',$insertID,"id: $id | id_destino: $insertID");

    $dados_arvore_contrato = DBRead('','tb_arvore_contrato',"WHERE id_arvore = '$id'");
    if($dados_arvore_contrato){
        foreach ($dados_arvore_contrato as $conteudo_arvore_contrato) {
            $dados_novos = array(
                'id_arvore' => $insertID,
                'id_contrato_plano_pessoa' => $conteudo_arvore_contrato['id_contrato_plano_pessoa'],
                'exibe_texto_os' => $conteudo_arvore_contrato['exibe_texto_os']
            );
            DBCreate('', 'tb_arvore_contrato', $dados_novos);
        }
    }

    $clonados[] = $insertID;

    clonaFilhos($id, $insertID, $clonados);

    $alert = ('Item clonado com sucesso!','s');
    header("location: /api/iframe?token=$request->token&view=arvore-exibe&id=$id_inicio&nivel_limite=$nivel_limite&id_contrato_plano_pessoa=$contrato_select");
    exit;
}

function mover($id, $id_destino, $id_inicio, $nivel_limite, $contrato_select){

    $dados = array(
        'id_pai' => $id_destino
    );    
    DBUpdate('', 'tb_arvore', $dados, "id_arvore = $id");    
    registraLog('Movido opção da árvore.','i','tb_arvore',$id,"id: $id | id_destino: $id_destino");
    $alert = ('Item movido com sucesso!','s');
    header("location: /api/iframe?token=$request->token&view=arvore-exibe&id=$id_inicio&nivel_limite=$nivel_limite&id_contrato_plano_pessoa=$contrato_select");
    exit;
}

function excluiArvoreFilhos($id_pai){
    $dados = DBRead('','tb_arvore',"WHERE id_pai = '$id_pai'");
    if($dados){
        foreach ($dados as $conteudo) {
            excluiArvoreFilhos($conteudo['id_arvore']);
            DBDelete('','tb_arvore',"id_arvore = '".$conteudo['id_arvore']."'");
            registraLog('Exclusão de arvore filho.', 'e', 'tb_arvore', $id_pai, '');
        }
    }
}

function excluir($id, $id_inicio, $nivel_limite, $contrato_select){

    if($id == $id_inicio){
        $dados = DBRead('','tb_arvore',"WHERE id_arvore = '$id'");
        $id_inicio = $dados[0]['id_pai'];

    }

    excluiArvoreFilhos($id);

    DBDelete('','tb_arvore',"id_arvore = '$id'");
    registraLog('Exclusão de passo.', 'e', 'tb_arvore', $id, '');
    $alert = ('Item excluído com sucesso!');
    $alert_type = 's';    header("location: /api/iframe?token=$request->token&view=arvore-exibe&id=$id_inicio&nivel_limite=$nivel_limite&id_contrato_plano_pessoa=$contrato_select");
    exit;

}

function excluirArvoreContrato($id){

    DBDelete('','tb_arvore_contrato',"id_contrato_plano_pessoa = '$id'");
    registraLog('Exclusão de arvore contrato.', 'e', 'tb_arvore_contrato', $id, '');
    $alert = ('Item excluído com sucesso!');
    $alert_type = 's';    header("location: /api/iframe?token=$request->token&view=arvore-fluxo-busca");
    
    exit;

}

function clonarArvoreContrato($clone, $id_contrato_plano_pessoa){

    $arvores = DBRead('', 'tb_arvore_contrato', "WHERE id_contrato_plano_pessoa = '".$clone."'");
    foreach ($arvores as $arvore) {  
        $id_arvore = $arvore['id_arvore']; 
        $exibe_texto_os =  $arvore['exibe_texto_os'];
        $dados = array(
            'id_arvore' => $id_arvore,
            'id_contrato_plano_pessoa' => $id_contrato_plano_pessoa,
            'exibe_texto_os' => $exibe_texto_os
        );
        $insertID = DBCreate('', 'tb_arvore_contrato', $dados, true);
        registraLog('Clonar arvore contrato.','i','tb_arvore_contrato',$insertID,"id_arvore: $id_arvore | id_contrato_plano_pessoa: $id_contrato_plano_pessoa | exibe_texto_os: $exibe_texto_os");


    }  
    $alert = ('Árvore clonada com sucesso!','s');
    header("location: /api/iframe?token=$request->token&view=arvore-fluxo-busca");
    exit;
}

?>
