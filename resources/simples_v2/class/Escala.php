<?php
require_once(__DIR__."/System.php");

$escala_seg = (!empty($_POST['escala_seg'])) ? $_POST['escala_seg'] : '';
$escala_sab = (!empty($_POST['escala_sab'])) ? $_POST['escala_sab'] : '';
$escala_dom = (!empty($_POST['escala_dom'])) ? $_POST['escala_dom'] : '';

$folga = (!empty($_POST['folga'])) ? $_POST['folga'] : NULL;
$folga_domingo = (!empty($_POST['folga_domingo'])) ? $_POST['folga_domingo'] : NULL;

$justificativa = (!empty($_POST['justificativa'])) ? $_POST['justificativa'] : NULL;
$id_usuario = (!empty($_POST['id_usuario'])) ? $_POST['id_usuario'] : '';

$carga_horaria = (!empty($_POST['carga_horaria'])) ? $_POST['carga_horaria'] : '';


if(!empty($_POST['inserir'])) {

    if(!$id_usuario){
      $id_usuario = $_SESSION['id_usuario'];
    }
    
    if($escala_seg && $escala_sab && $escala_dom){
    
      inserir($escala_seg, $escala_sab, $escala_dom, $id_usuario, $justificativa, $folga, $folga_domingo, $carga_horaria);
    }else{
        $alert = ('Erro ao inserir dados!','w');
        header("location: /api/iframe?token=$request->token&view=exibe-escala");
        exit;
    }
} else if (isset($_GET['opcao'])) {

    $opcao = (int)$_GET['opcao'];
    habilitar($opcao);
}else{
    header("location: ../adm.php");
    exit;
}
  


function inserir($escala_seg, $escala_sab, $escala_dom, $id_usuario, $justificativa_indisponibilidade, $folga, $folga_domingo, $carga_horaria){

  $escalas = DBRead('', 'tb_disponibilidade_escala', "WHERE id_usuario = '".$id_usuario."'");
  
  if($escalas){
   
    $query = "DELETE FROM tb_horarios_disponibilidade WHERE id_disponibilidade_escala = '".$escalas[0]['id_disponibilidade_escala']."'";
    $link = DBConnect('');
    $result = @mysqli_query($link, $query);
    DBClose($link);
    registraLog('Exclusão de horarios de disponibilidade de escala.','e','tb_horarios_disponibilidade',$escalas[0]['id_disponibilidade_escala'],'');

    $dados = array(
        'justificativa_indisponibilidade' => $justificativa_indisponibilidade,
        'folga' => $folga,
        'folga_dom' => $folga_domingo,
        'carga_horaria' => $carga_horaria
    );

    DBUpdate('', 'tb_disponibilidade_escala', $dados, "id_disponibilidade_escala = '".$escalas[0]['id_disponibilidade_escala']."'");
    registraLog('Alteração de disponibilidade da escala.','a','tb_disponibilidade_escala',$escalas[0]['id_disponibilidade_escala'],"justificativa_indisponibilidade: $justificativa_indisponibilidade | folga: $folga | folga_domingo: $folga_domingo | carga_horaria: $carga_horaria");

    $insertID = $escalas[0]['id_disponibilidade_escala'];

  }else{
    $dados = array(
        'id_usuario' => $id_usuario,
        'justificativa_indisponibilidade' => $justificativa_indisponibilidade,
        'folga' => $folga,
        'folga_dom' => $folga_domingo,
        'carga_horaria' => $carga_horaria
    );


      $insertID = DBCreate('', 'tb_disponibilidade_escala', $dados, true);
      registraLog('Inserção de nova escala.','i','tb_disponibilidade_escala',$insertID,"id_usuario: $id_usuario | justificativa_indisponibilidade: $justificativa_indisponibilidade| folga: $folga| folga_domingo: $folga_domingo | carga_horaria: $carga_horaria");
  }


    

      $cont = 0;
        foreach($escala_seg as $opc){

          $dadosEscala = array(
            'horario' => $cont,
            'disponibilidade' => $opc,
            'periodo' => "seg a sex",
            'id_disponibilidade_escala' => $insertID
          );
                
          $insertUsuario = DBCreate('', 'tb_horarios_disponibilidade', $dadosEscala);
               
          $cont++;
        }

      $cont = 0;
        foreach($escala_sab as $opc){

          $dadosEscala = array(
            'horario' => $cont,
            'disponibilidade' => $opc,
            'periodo' => "sabado",
            'id_disponibilidade_escala' => $insertID
          );
                
          $insertUsuario = DBCreate('', 'tb_horarios_disponibilidade', $dadosEscala);
               
          $cont++;
        }

      $cont = 0;
        foreach($escala_dom as $opc){

          $dadosEscala = array(
            'horario' => $cont,
            'disponibilidade' => $opc,
            'periodo' => "domingo",
            'id_disponibilidade_escala' => $insertID
          );
                
          $insertUsuario = DBCreate('', 'tb_horarios_disponibilidade', $dadosEscala);
               
          $cont++;
        }  
        registraLog('Inserção de nova escala.','i','tb_horarios_disponibilidade',$insertID,"horario: $cont | disponibilidade: $opc| periodo: domingo| id_disponibilidade_escala: $insertID");
        
        header("location: /api/iframe?token=$request->token&view=gerenciar-escala");
        $alert = ('Item inserido com sucesso!');
    $alert_type = 's';        exit;
        
   
}

function habilitar($opcao){

  if($opcao == 1){
    
    $condicao = DBRead('', 'tb_pagina_sistema', "where nome_view = 'escala-editar'");
    $dados = array(
    'id_pagina_sistema' => $condicao[0]['id_pagina_sistema'],
    'id_perfil_sistema' => '3'
  );
  
  $insertID = DBCreate('', 'tb_pagina_sistema_perfil', $dados, true);

  }else if($opcao == 0){
    
    $condicao = DBRead('', 'tb_perfil_sistema a', "INNER JOIN tb_pagina_sistema_perfil b ON a.id_perfil_sistema = b.id_perfil_sistema INNER JOIN tb_pagina_sistema c ON b.id_pagina_sistema = c.id_pagina_sistema WHERE a.nome = 'Call Center - Atendente' AND c.nome_view = 'escala-editar'");
    $condicao[0]['id_pagina_sistema'];
    
    $query = "DELETE FROM tb_pagina_sistema_perfil WHERE id_pagina_sistema = '".$condicao[0]['id_pagina_sistema']."' AND (id_perfil_sistema = '3')";
    $link = DBConnect('');
    $result = @mysqli_query($link, $query);
    DBClose($link);
        
  }
        
        header("location: /api/iframe?token=$request->token&view=gerenciar-escala");  
        exit;
        
}

?>