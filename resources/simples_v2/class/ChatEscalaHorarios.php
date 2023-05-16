<?php
require_once(__DIR__."/System.php");

$id_horarios_escala = (!empty($_POST['id_horarios_escala'])) ? $_POST['id_horarios_escala'] : NULL;
$id_horarios_especiais = (!empty($_POST['id_horarios_especiais'])) ? $_POST['id_horarios_especiais'] : NULL;

$inicial_seg_chat = (!empty($_POST['inicial_seg_chat'])) ? $_POST['inicial_seg_chat'] : NULL;
$final_seg_chat = (!empty($_POST['final_seg_chat'])) ? $_POST['final_seg_chat'] : NULL;

$inicial_sab_chat = (!empty($_POST['inicial_sab_chat'])) ? $_POST['inicial_sab_chat'] : NULL;
$final_sab_chat = (!empty($_POST['final_sab_chat'])) ? $_POST['final_sab_chat'] : NULL;

$inicial_dom_chat = (!empty($_POST['inicial_dom_chat'])) ? $_POST['inicial_dom_chat'] : NULL;
$final_dom_chat = (!empty($_POST['final_dom_chat'])) ? $_POST['final_dom_chat'] : NULL;

$inicial_especial_chat = (!empty($_POST['inicial_especial_chat'])) ? $_POST['inicial_especial_chat'] : '';
$final_especial_chat = (!empty($_POST['final_especial_chat'])) ? $_POST['final_especial_chat'] : '';

if(!empty($_POST['inserir'])) {
  $cont = 0;
  $dados_especial_chat = array();
  if($id_horarios_especiais){
      foreach($id_horarios_especiais as $conteudo_id_horarios_especiais) {
          $dados_especial_chat[$cont]['id_horarios_especiais'] = $conteudo_id_horarios_especiais;
          $cont++;
      }
  }
  
  $cont = 0;
  if($inicial_especial_chat){
      foreach($inicial_especial_chat as $conteudo_inicial_especial_chat) {
          $dados_especial_chat[$cont]['inicial_especial_chat'] = $conteudo_inicial_especial_chat;
          $cont++;
      }
  }
  
  $cont = 0;
  if($final_especial_chat){
      foreach($final_especial_chat as $conteudo_final_especial_chat) {
          $dados_especial_chat[$cont]['final_especial_chat'] = $conteudo_final_especial_chat;
          $cont++;
      }
  }
  inserir($id_horarios_escala, $inicial_seg_chat, $final_seg_chat, $inicial_sab_chat, $final_sab_chat, $inicial_dom_chat, $final_dom_chat, $dados_especial_chat);
              
}else if(!empty($_POST['redirect_data'])){
  
  $id_escala_horarios_data = (!empty($_POST['id_escala_horarios_data'])) ? $_POST['id_escala_horarios_data'] : '';
  header("location: /api/iframe?token=$request->token&view=chat-escala-editar-horarios&novo=$id_escala_horarios_data ");
  exit;

}else if(isset($_GET['excluir'])){
    
  $id = (int)$_GET['excluir'];
  excluir($id);

}else{
    header("location: ../adm.php");
    exit;
}

function inserir($id_horarios_escala, $inicial_seg_chat, $final_seg_chat, $inicial_sab_chat, $final_sab_chat, $inicial_dom_chat, $final_dom_chat, $dados_especial_chat){

    $id_chat_horarios_escala = DBRead('', 'tb_chat_horarios_escala', "WHERE id_horarios_escala = '".$id_horarios_escala."' ", 'id_chat_horarios_escala');
    $id_chat_horarios_escala = $id_chat_horarios_escala[0]['id_chat_horarios_escala'];
    if($id_chat_horarios_escala){
        $dados = array(
            'inicial_seg' => $inicial_seg_chat,
            'inicial_sab' => $inicial_sab_chat,
            'inicial_dom' => $inicial_dom_chat,
            'final_seg' => $final_seg_chat,
            'final_sab' => $final_sab_chat,
            'final_dom' => $final_dom_chat
        );

        DBUpdate('', 'tb_chat_horarios_escala', $dados, "id_chat_horarios_escala = '".$id_chat_horarios_escala."'");
        registraLog('Alteração de horarios da escala de chat.','a','tb_chat_horarios_escala', $id_chat_horarios_escala,"inicial_seg: $inicial_seg_chat | inicial_sab: $inicial_sab_chat | inicial_dom: $inicial_dom_chat | final_seg: $final_seg_chat | final_sab: $final_sab_chat | final_dom: $final_dom_chat");
        //Horarios especiais

       
        if($dados_especial_chat){
            foreach ($dados_especial_chat as $conteudo_especial_chat) {
                $id_horarios_especiais = $conteudo_especial_chat['id_horarios_especiais'];

                //Exclui os antigos horarios
                $query = "DELETE FROM tb_chat_horarios_especiais WHERE id_horarios_especiais = '".$id_horarios_especiais."'";
                $link = DBConnect('');
                $result = @mysqli_query($link, $query);
                DBClose($link);
                registraLog('Exclusão de dados','e','tb_chat_horarios_especiais',$id_horarios_especiais,'');
                
                //Insere os novos horarios
                $inicial_especial = $conteudo_especial_chat['inicial_especial_chat'];
                $final_especial = $conteudo_especial_chat['final_especial_chat'];
                $dados = array(
                    'inicial_especial' => $inicial_especial,
                    'final_especial' => $final_especial,
                    'id_horarios_especiais' => $id_horarios_especiais
                );     
               
                DBCreate('', 'tb_chat_horarios_especiais', $dados, true);  
                registraLog('Criação tb_horarios_especiais de chat','a','tb_chat_horarios_especiais',$id_horarios_especiais,"inicial_especial: $inicial_especial | final_especial: $final_especial | id_horarios_especiais: $id_horarios_especiais");
            }
        }
   
    }else{
        $dados = array(
            'inicial_seg' => $inicial_seg_chat,
            'inicial_sab' => $inicial_sab_chat,
            'inicial_dom' => $inicial_dom_chat,
            'final_seg' => $final_seg_chat,
            'final_sab' => $final_sab_chat,
            'final_dom' => $final_dom_chat,
            'id_horarios_escala' => $id_horarios_escala,
        );

        $insertID = DBCreate('', 'tb_chat_horarios_escala', $dados, true);
        registraLog('Inserção de horarios da escala de chat.','a','tb_chat_horarios_escala',$insertID,"inicial_seg: $inicial_seg_chat | inicial_sab: $inicial_sab_chat | inicial_dom: $inicial_dom_chat | final_seg: $final_seg_chat | final_sab: $final_sab_chat | final_dom: $final_dom_chat | id_horarios_escala: $id_horarios_escala");

        foreach ($dados_especial_chat as $conteudo_especial_chat) {
            $id_horarios_especiais = $conteudo_especial_chat['id_horarios_especiais'];
            
            //Insere os novos horarios
            $inicial_especial = $conteudo_especial_chat['inicial_especial_chat'];
            $final_especial = $conteudo_especial_chat['final_especial_chat'];
            $dados = array(
                'inicial_especial' => $inicial_especial,
                'final_especial' => $final_especial,
                'id_horarios_especiais' => $id_horarios_especiais
            );     
            DBCreate('', 'tb_chat_horarios_especiais', $dados, true);  
            registraLog('Criação tb_horarios_especiais de chat','a','tb_chat_horarios_especiais',$id_horarios_especiais,"inicial_especial: $inicial_especial | final_especial: $final_especial | id_horarios_especiais: $id_horarios_especiais");
        }
    
    }
    header("location: /api/iframe?token=$request->token&view=chat-escala-horarios");
    $alert = ('Item inserido com sucesso!');
    $alert_type = 's';    exit;
}

function excluir($id){
  $query = "DELETE FROM tb_chat_horarios_escala WHERE id_horarios_escala = ".$id;
  $link = DBConnect('');
  $result = @mysqli_query($link, $query);
  DBClose($link);
  registraLog('Exclusão de escala de chat.', 'e', 'tb_chat_horarios_escala', $id, '');

  $query = "DELETE FROM tb_chat_horarios_especiais WHERE id_horarios_escala = ".$id;
  $link = DBConnect('');
  $result2 = @mysqli_query($link, $query);
  DBClose($link);
  registraLog('Exclusão de escala de chat.', 'e', 'tb_chat_horarios_especiais', $id, '');

  $alert = ('Item excluído com sucesso!', 's');
  header("location: /api/iframe?token=$request->token&view=chat-escala-horarios");
  exit;
}

?>