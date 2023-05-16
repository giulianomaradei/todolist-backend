<?php
require_once(__DIR__."/System.php");

$escala_inicial_seg = (!empty($_POST['escala_inicial_seg'])) ? $_POST['escala_inicial_seg'] : NULL;
$escala_final_seg = (!empty($_POST['escala_final_seg'])) ? $_POST['escala_final_seg'] : NULL;

$escala_inicial_sab = (!empty($_POST['escala_inicial_sab'])) ? $_POST['escala_inicial_sab'] : NULL;
$escala_final_sab = (!empty($_POST['escala_final_sab'])) ? $_POST['escala_final_sab'] : NULL;

$escala_inicial_dom = (!empty($_POST['escala_inicial_dom'])) ? $_POST['escala_inicial_dom'] : NULL;
$escala_final_dom = (!empty($_POST['escala_final_dom'])) ? $_POST['escala_final_dom'] : NULL;

$folga = (!empty($_POST['folga'])) ? $_POST['folga'] : '';
$folga_domingo = (!empty($_POST['folga_domingo'])) ? $_POST['folga_domingo'] : '';

$id_usuario = (!empty($_POST['id_usuario'])) ? $_POST['id_usuario'] : '';

$mes = (!empty($_POST['mes'])) ? $_POST['mes'] : '';
$ano = (!empty($_POST['ano'])) ? $_POST['ano'] : '';

$especial = (!empty($_POST['especial'])) ? $_POST['especial'] : '';
$inicial_especial = (!empty($_POST['inicial_especial'])) ? $_POST['inicial_especial'] : '';
$final_especial = (!empty($_POST['final_especial'])) ? $_POST['final_especial'] : '';

$atendente = (!empty($_POST['atendente'])) ? $_POST['atendente'] : 0;
$chat = (!empty($_POST['chat'])) ? $_POST['chat'] : 0;

$data_existente = (!empty($_POST['data_existente'])) ? $_POST['data_existente'] : 0;
$data_replica = (!empty($_POST['data_replica'])) ? $_POST['data_replica'] : 0;
$dia_escala_referencia = (!empty($_POST['dia_escala_referencia'])) ? $_POST['dia_escala_referencia'] : 0;

$carga_horaria = (!empty($_POST['carga_horaria'])) ? $_POST['carga_horaria'] : NULL;

$tipo_intervalo_seg = (!empty($_POST['tipo_intervalo_seg'])) ? $_POST['tipo_intervalo_seg'] : 1;
$intervalo_inicial_seg = (!empty($_POST['intervalo_inicial_seg'])) ? $_POST['intervalo_inicial_seg'] : NULL;
$intervalo_final_seg = (!empty($_POST['intervalo_final_seg'])) ? $_POST['intervalo_final_seg'] : NULL;
$tempo_intervalo_seg = (!empty($_POST['tempo_intervalo_seg'])) ? $_POST['tempo_intervalo_seg'] : NULL;

$tipo_intervalo_sab = (!empty($_POST['tipo_intervalo_sab'])) ? $_POST['tipo_intervalo_sab'] : 1;
$intervalo_inicial_sab = (!empty($_POST['intervalo_inicial_sab'])) ? $_POST['intervalo_inicial_sab'] : NULL;
$intervalo_final_sab = (!empty($_POST['intervalo_final_sab'])) ? $_POST['intervalo_final_sab'] : NULL;
$tempo_intervalo_sab = (!empty($_POST['tempo_intervalo_sab'])) ? $_POST['tempo_intervalo_sab'] : NULL;

$tipo_intervalo_dom = (!empty($_POST['tipo_intervalo_dom'])) ? $_POST['tipo_intervalo_dom'] : 1;
$intervalo_inicial_dom = (!empty($_POST['intervalo_inicial_dom'])) ? $_POST['intervalo_inicial_dom'] : NULL;
$intervalo_final_dom = (!empty($_POST['intervalo_final_dom'])) ? $_POST['intervalo_final_dom'] : NULL;
$tempo_intervalo_dom = (!empty($_POST['tempo_intervalo_dom'])) ? $_POST['tempo_intervalo_dom'] : NULL;

$tempo_intervalo_especial = (!empty($_POST['tempo_intervalo_especial'])) ? $_POST['tempo_intervalo_especial'] : NULL;

// echo "<br>tipo_intervalo_seg - ".$tipo_intervalo_seg;
// echo "<br>intervalo_inicial_seg - ".$intervalo_inicial_seg;
// echo "<br>intervalo_final_seg - ".$intervalo_final_seg;
// echo "<br>tempo_intervalo_seg - ".$tempo_intervalo_seg;

// echo "<hr>";

// echo "<br>tipo_intervalo_sab - ".$tipo_intervalo_sab;
// echo "<br>intervalo_inicial_sab - ".$intervalo_inicial_sab;
// echo "<br>intervalo_final_sab - ".$intervalo_final_sab;
// echo "<br>tempo_intervalo_sab - ".$tempo_intervalo_sab;

// echo "<hr>";

// echo "<br>tipo_intervalo_dom - ".$tipo_intervalo_dom;
// echo "<br>intervalo_inicial_dom - ".$intervalo_inicial_dom;
// echo "<br>intervalo_final_dom - ".$intervalo_final_dom;
// echo "<br>tempo_intervalo_dom - ".$tempo_intervalo_dom;

// echo "<hr>";






// altera os antigos
  // $escalas = DBRead('', 'tb_horarios_escala', "");
  // foreach ($escalas as $conteudo) {
      
  //   $data_inicio_compara = date('Y-m-d H:i:s', strtotime("+0 minutes",strtotime($conteudo['inicial_seg'])));
  //   $data_fim_compara = date('Y-m-d H:i:s', strtotime("+0 minutes",strtotime($conteudo['final_seg'])));

  //   if($data_inicio_compara > $data_fim_compara){
  //     $data_inicio_compara = date('Y-m-d H:i:s', strtotime("-1 days",strtotime($conteudo['inicial_seg'])));
  //   }

  //   $diferenca_datas = strtotime($data_fim_compara) - strtotime($data_inicio_compara);
    
  //   if($diferenca_datas <= 14400){
  // 		$carga_horaria = "meio";
  // 	}else{
  // 		$carga_horaria = "integral";
  //   }
    
  //   $dados = array(
  //     'carga_horaria' => $carga_horaria
  //   );
    
  //   DBUpdate('', 'tb_horarios_escala', $dados, "id_horarios_escala = '".$conteudo['id_horarios_escala']."' ");

  // }
// die();






if(!empty($_POST['inserir'])) {

  echo "escala_inicial_seg: ".$escala_inicial_seg."<hr>";
   echo "escala_final_seg: ".$escala_final_seg."<hr>";
    echo "escala_inicial_sab: ".$escala_inicial_sab."<hr>";
     echo "escala_final_sab: ".$escala_final_sab."<hr>";
      echo "escala_inicial_dom: ".$escala_inicial_dom."<hr>";
      echo "folga: ".$folga."<hr>";
      echo "carga_horaria: ".$carga_horaria."<hr>";
      if($folga_domingo){
        foreach ($folga_domingo as $dom) {
          echo "folga_domingo: ".$dom."<hr>";
        }
       }
       

       if($especial){
          foreach ($especial as $key => $esp) {
          //  echo "folga_especial: ".$esp."<hr>";
          //  echo "folga_inicial_especial: ".$inicial_especial[$key]."<hr>";
          //  echo "folga_final_especial: ".$final_especial[$key]."<hr>";

          $dia_semana = array('Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado');

          $data = converteData($esp);
          $anos = substr($data,0,4);

          $meses = substr($data,5,2);

          $dias = substr($data,8,2);
          echo "DATA:   ".$anos."-".$meses."-".$dias."<hr>";
          $diasemana = date("w", mktime(0,0,0,(int)$meses,(int)$dias,(int)$anos) );
          echo "diasemana: ".$diasemana."<hr>";
          echo "DIA DA SEMANA: ".$dia_semana[$diasemana]."<hr>";
          if($dia_semana[$diasemana] == $folga){
            echo "O feriado é no mesmo dia da folga, portanto não será salvo este horário<hr>";
          }
         }
          
       }
        
         echo "id_usuario: ".$id_usuario."<hr>";

          echo "mes: ".$mes."<hr>";
          echo "ano: ".$ano."<hr>";
          $data_inicial = "01-".$mes."-".$ano;
          echo "data_inicial: ".$data_inicial."<hr>";
 
          $cont2 = 0;
          $dados_dia_especial = array();
          if($especial){
            foreach($especial as $dias) {
                $dados_dia_especial[$cont2] = $dias;
                $cont2++;
              }
          }

          $cont = 0;
          $dados_domingo = array();
          if($folga_domingo){
            foreach($folga_domingo as $dom) {
              $dados_domingo[$cont] = $dom;
              $cont++;
            }
          }
          
          
          $cont4 = 0;
          $dados_inicial_especial = array();
          if($inicial_especial){
            foreach($inicial_especial as $dom) {
              $dados_inicial_especial[$cont4] = $dom;
              $cont4++;
            }
          }
          
          $cont5 = 0;
          $dados_final_especial = array();
          if($final_especial){
            foreach($final_especial as $dom) {
              $dados_final_especial[$cont5] = $dom;
              $cont5++;
            }
          }
          
          $cont6 = 0;
          $dados_dia_especial = array();
          if($especial){
            foreach($especial as $dom) {
              $dados_dia_especial[$cont6] = $dom;
              $cont6++;
            }
          }

          $cont7 = 0;
          $dados_dia_especial_intervalo = array();
          if($tempo_intervalo_especial){
            foreach($tempo_intervalo_especial as $intervalo) {
              $dados_dia_especial_intervalo[$cont7] = $intervalo;
              $cont7++;
            }
          }
          
          // var_dump($dados_dia_especial_intervalo);

          // die();

  inserir($id_usuario, $dados_domingo, $folga, $escala_inicial_seg, $escala_final_seg, $escala_inicial_sab, $escala_final_sab, $escala_inicial_dom, $escala_final_dom, $data_inicial, $dados_dia_especial, $dados_final_especial, $dados_inicial_especial, $atendente, $carga_horaria, $tipo_intervalo_seg, $intervalo_inicial_seg, $intervalo_final_seg, $tempo_intervalo_seg, $tipo_intervalo_sab, $intervalo_inicial_sab, $intervalo_final_sab, $tempo_intervalo_sab, $tipo_intervalo_dom, $intervalo_inicial_dom, $intervalo_final_dom, $tempo_intervalo_dom, $dados_dia_especial_intervalo, $chat);

}else if(isset($_GET['excluir'])){
    
    $id = (int)$_GET['excluir'];
    excluir($id);

}else if(!empty($_POST['clonar'])){

  clonar($ano, $mes, $data_existente);

}else if(!empty($_POST['replicar'])){

  replicar($data_existente, $data_replica, $dia_escala_referencia);

}else if(isset($_GET['ativar'])){
  
  $data = $_GET['ativar'];

  ativar($data);

}else if(isset($_GET['desativar'])){
  
  $data = $_GET['desativar'];

  desativar($data);

}else{
    header("location: ../adm.php");
    exit;
}
  

function inserir($id_usuario, $dados_domingo, $folga, $escala_inicial_seg, $escala_final_seg, $escala_inicial_sab, $escala_final_sab, $escala_inicial_dom, $escala_final_dom, $data_inicial, $dados_dia_especial, $dados_final_especial, $dados_inicial_especial, $atendente, $carga_horaria, $tipo_intervalo_seg, $intervalo_inicial_seg, $intervalo_final_seg, $tempo_intervalo_seg, $tipo_intervalo_sab, $intervalo_inicial_sab, $intervalo_final_sab, $tempo_intervalo_sab, $tipo_intervalo_dom, $intervalo_inicial_dom, $intervalo_final_dom, $tempo_intervalo_dom, $dados_dia_especial_intervalo, $chat){

  $data_inicial = converteData($data_inicial);
  $escalas = DBRead('', 'tb_horarios_escala', "WHERE id_usuario = '".$id_usuario."' AND data_inicial = '".$data_inicial."'");

  if($escalas){
    $id_horarios_escala = $escalas[0]['id_horarios_escala'];
    $dados = array(
      'folga_seg' => $folga,
      'inicial_seg' => $escala_inicial_seg,
      'inicial_sab' => $escala_inicial_sab,
      'inicial_dom' => $escala_inicial_dom,
      'final_seg' => $escala_final_seg,
      'final_sab' => $escala_final_sab,
      'final_dom' => $escala_final_dom,
      'data_inicial' => $data_inicial,
      'data_lido' => null,
      'atendente' => $atendente,
      'carga_horaria' => $carga_horaria,
      'chat' => $chat
    );
    
    DBUpdate('', 'tb_horarios_escala', $dados, "id_usuario = '".$id_usuario."' AND data_inicial = '".$data_inicial."'");
    registraLog('Alteração de horarios da escala.','a','tb_horarios_escala', $id_horarios_escala,"folga_seg: $folga | inicial_seg: $escala_inicial_seg  | inicial_sab: $escala_inicial_sab  | inicial_dom: $escala_inicial_dom  | final_seg: $escala_final_seg  | final_sab: $escala_final_sab  | final_dom: $escala_final_dom final | data_inicial: $data_inicial | atendente: $atendente | carga_horaria: $carga_horaria | chat: $chat");

    $id_horarios_escala = $escalas[0]['id_horarios_escala'];
    
    $query = "DELETE FROM tb_horarios_escala_intervalo WHERE id_horarios_escala = '".$id_horarios_escala."'";
    $link = DBConnect('');
    $result = @mysqli_query($link, $query);
    DBClose($link);
    
    $dados_intervalo_seg = array(
      'tipo_intervalo' => $tipo_intervalo_seg,
      'intervalo_inicial' => $intervalo_inicial_seg,
      'intervalo_final' => $intervalo_final_seg,
      'tempo_intervalo' => $tempo_intervalo_seg,
      'dia' => 'seg',
      'id_horarios_escala' => $id_horarios_escala
    );
    DBCreate('', 'tb_horarios_escala_intervalo', $dados_intervalo_seg, true);  
    registraLog('Criação tb_horarios_escala_intervalo segunda a sexta','a','tb_horarios_escala_intervalo',$id_horarios_escala,"tipo_intervalo: $tipo_intervalo_seg | intervalo_inicial: $intervalo_inicial_seg | intervalo_final: $intervalo_final_seg | tempo_intervalo: $tempo_intervalo_seg | dia: seg");
    
    if(($carga_horaria != 'jovem' && $carga_horaria != 'estagio') || ($carga_horaria == 'estagio' && $escala_inicial_sab)){
    
      $dados_intervalo_sab = array(
        'tipo_intervalo' => $tipo_intervalo_sab,
        'intervalo_inicial' => $intervalo_inicial_sab,
        'intervalo_final' => $intervalo_final_sab,
        'tempo_intervalo' => $tempo_intervalo_sab,
        'dia' => 'sab',
        'id_horarios_escala' => $id_horarios_escala
      );
      DBCreate('', 'tb_horarios_escala_intervalo', $dados_intervalo_sab, true);  
      registraLog('Criação tb_horarios_escala_intervalo sabado','a','tb_horarios_escala_intervalo',$id_horarios_escala,"tipo_intervalo: $tipo_intervalo_sab | intervalo_inicial: $intervalo_inicial_sab | intervalo_final: $intervalo_final_sab | tempo_intervalo: $tempo_intervalo_sab | dia: sab");

     
    }

    if($carga_horaria != 'jovem' && $carga_horaria != 'estagio'){

      $dados_intervalo_dom = array(
        'tipo_intervalo' => $tipo_intervalo_dom,
        'intervalo_inicial' => $intervalo_inicial_dom,
        'intervalo_final' => $intervalo_final_dom,
        'tempo_intervalo' => $tempo_intervalo_dom,
        'dia' => 'dom',
        'id_horarios_escala' => $id_horarios_escala
      );
      DBCreate('', 'tb_horarios_escala_intervalo', $dados_intervalo_dom, true);  
      registraLog('Criação tb_horarios_escala_intervalo segunda a sexta','a','tb_horarios_escala_intervalo',$id_horarios_escala,"tipo_intervalo: $tipo_intervalo_dom | intervalo_inicial: $intervalo_inicial_dom | intervalo_final: $intervalo_final_dom | tempo_intervalo: $tempo_intervalo_dom | dia: dom");

    }  
    


    // }else{
    //   $dados_intervalo = array(
    //     'tipo_intervalo' => $tipo_intervalo,
    //     'intervalo_inicial' => $intervalo_inicial,
    //     'intervalo_final' => $intervalo_final,
    //     'id_horarios_escala' => $id_horarios_escala
    //   );
    //   DBCreate('', 'tb_horarios_escala_intervalo', $dados_intervalo, true);  
    //   registraLog('Criação tb_horarios_escala_intervalo','a','tb_horarios_escala_intervalo',$id_horarios_escala,"tipo_intervalo: $tipo_intervalo | intervalo_inicial: $intervalo_inicial | intervalo_final: $intervalo_final");
    // }

    $query = "DELETE FROM tb_folgas_dom WHERE id_horarios_escala = '".$id_horarios_escala."'";
    $link = DBConnect('');
    $result = @mysqli_query($link, $query);
    DBClose($link);
    registraLog('Exclusão de dados','e','tb_folgas_dom',$id_horarios_escala,'');

    if($dados_domingo[0] != ''){
      foreach ($dados_domingo as $dom) {
        $dom = converteData($dom);
        if($dom){
          $dados = array(
              'dia' => $dom,
              'id_horarios_escala' => $id_horarios_escala
            );     
          DBCreate('', 'tb_folgas_dom', $dados, true);  
          registraLog('Criação tb_folgas_dom','a','tb_folgas_dom',$id_horarios_escala,"dia: $dom | id_horarios_escala: $id_horarios_escala");
        }
        
      }
    }
    
     
    //Folgas especiais

    $query = "DELETE FROM tb_horarios_especiais WHERE id_horarios_escala = '".$id_horarios_escala."'";
    $link = DBConnect('');
    $result = @mysqli_query($link, $query);
    DBClose($link);
    registraLog('Exclusão de dados','e','tb_horarios_especiais',$id_horarios_escala,'');
    if($dados_final_especial[0] != '' && $dados_inicial_especial[0] != '' && $dados_dia_especial != ''){
      $alerta_data = '!';
        $cont = 0;
        foreach ($dados_dia_especial as $ref) {
          
          $dia_semana = array('Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado');
          $data = converteData($ref);
          $anos = substr($data,0,4);
          $meses = substr($data,5,2);
          $dias = substr($data,8,2);
          $diasemana = date("w", mktime(0,0,0,$meses,$dias,$anos) );

            if($dia_semana[$diasemana] == $folga){
              // $alerta_data = ", o feriado é no mesmo dia da folga, portanto não será salvo este horário";
            }

          $final_especial = $dados_final_especial[$cont];
          $inicial_especial = $dados_inicial_especial[$cont];
          $tempo_intervalo = $dados_dia_especial_intervalo[$cont];

          $dias = converteData($ref);
          $dados = array(
            'dia' => $dias,
            'inicial_especial' => $inicial_especial,
            'final_especial' => $final_especial,
            'id_horarios_escala' => $id_horarios_escala,
            'tempo_intervalo' => $tempo_intervalo
          );     
          DBCreate('', 'tb_horarios_especiais', $dados, true);  
          registraLog('Criação tb_horarios_especiais','a','tb_horarios_especiais',$id_horarios_escala,"dia: $dias | inicial_especial: $inicial_especial | final_especial: $final_especial | id_horarios_escala: $id_horarios_escala | tempo_intervalo: $tempo_intervalo");
          $cont++;
        }
    }else{
      $alerta_data = ', sem nunhum horário especial!';
    }
  }else{
    //VERIFIVA SE TEM ALGUÉM LIBERADO

    $escalas = DBRead('', 'tb_horarios_escala', "WHERE data_inicial = '".$data_inicial."' AND liberado = '1'");

    if($escalas){
      $liberado = '1';
    }else{
      $liberado = '0';
    }
    $dados = array(
      'folga_seg' => $folga,
      'inicial_seg' => $escala_inicial_seg,
      'inicial_sab' => $escala_inicial_sab,
      'inicial_dom' => $escala_inicial_dom,
      'final_seg' => $escala_final_seg,
      'final_sab' => $escala_final_sab,
      'final_dom' => $escala_final_dom,
      'id_usuario' => $id_usuario,
      'data_inicial' => $data_inicial,
      'atendente' => $atendente,
      'liberado' => $liberado,
      'carga_horaria' => $carga_horaria,
      'chat' => $chat,
    );

    $insertID = DBCreate('', 'tb_horarios_escala', $dados, true);
    registraLog('Inserção de horarios da escala.','a','tb_horarios_escala',$insertID,"folga_seg: $folga | inicial_seg: $escala_inicial_seg  | inicial_sab: $escala_inicial_sab  | inicial_dom: $escala_inicial_dom  | final_seg: $escala_final_seg  | final_sab: $escala_final_sab  | final_dom: $escala_final_dom | atendente: $atendente | liberado: $liberado | carga_horaria: $carga_horaria | chat: $chat");
    
    $dados_intervalo_seg = array(
      'tipo_intervalo' => $tipo_intervalo_seg,
      'intervalo_inicial' => $intervalo_inicial_seg,
      'intervalo_final' => $intervalo_final_seg,
      'tempo_intervalo' => $tempo_intervalo_seg,
      'dia' => 'seg',
      'id_horarios_escala' => $insertID
    );
    DBCreate('', 'tb_horarios_escala_intervalo', $dados_intervalo_seg, true);  
    registraLog('Criação tb_horarios_escala_intervalo segunda a sexta','a','tb_horarios_escala_intervalo',$insertID,"tipo_intervalo: $tipo_intervalo_seg | intervalo_inicial: $intervalo_inicial_seg | intervalo_final: $intervalo_final_seg | tempo_intervalo: $tempo_intervalo_seg | dia: seg");

    if(($carga_horaria != 'jovem' && $carga_horaria != 'estagio') || ($carga_horaria == 'estagio' && $escala_inicial_sab)){
      $dados_intervalo_sab = array(
        'tipo_intervalo' => $tipo_intervalo_sab,
        'intervalo_inicial' => $intervalo_inicial_sab,
        'intervalo_final' => $intervalo_final_sab,
        'tempo_intervalo' => $tempo_intervalo_sab,
        'dia' => 'sab',
        'id_horarios_escala' => $insertID
      );
      DBCreate('', 'tb_horarios_escala_intervalo', $dados_intervalo_sab, true);  
      registraLog('Criação tb_horarios_escala_intervalo sabado','a','tb_horarios_escala_intervalo',$insertID,"tipo_intervalo: $tipo_intervalo_sab | intervalo_inicial: $intervalo_inicial_sab | intervalo_final: $intervalo_final_sab | tempo_intervalo: $tempo_intervalo_sab | dia: sab");
    }

    if($carga_horaria != 'jovem' && $carga_horaria != 'estagio'){
      $dados_intervalo_dom = array(
        'tipo_intervalo' => $tipo_intervalo_dom,
        'intervalo_inicial' => $intervalo_inicial_dom,
        'intervalo_final' => $intervalo_final_dom,
        'tempo_intervalo' => $tempo_intervalo_dom,
        'dia' => 'dom',
        'id_horarios_escala' => $insertID
      );
      DBCreate('', 'tb_horarios_escala_intervalo', $dados_intervalo_dom, true);  
      registraLog('Criação tb_horarios_escala_intervalo segunda a sexta','a','tb_horarios_escala_intervalo',$insertID,"tipo_intervalo: $tipo_intervalo_dom | intervalo_inicial: $intervalo_inicial_dom | intervalo_final: $intervalo_final_dom | tempo_intervalo: $tempo_intervalo_dom | dia: dom");
    }
    
    if($dados_domingo[0] != ''){
      foreach ($dados_domingo as $dom) {
        $dom = converteData($dom);
        $dados = array(
                'dia' => $dom,
                'id_horarios_escala' => $insertID
            );     
        DBCreate('', 'tb_folgas_dom', $dados, true);  
      }
    }

    if($dados_final_especial[0] != '' && $dados_inicial_especial[0] != '' && $dados_dia_especial != ''){
      $alerta_data = '!';
        $cont = 0;
        foreach ($dados_dia_especial as $ref) {

          $dia_semana = array('Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado');
          $data = converteData($ref);
          $anos = substr($data,0,4);
          $meses = substr($data,5,2);
          $dias = substr($data,8,2);
          $diasemana = date("w", mktime(0,0,0,$meses,$dias,$anos) );

          if($dia_semana[$diasemana] == $folga){
            // $alerta_data = ", o feriado é no mesmo dia da folga, portanto não será salvo este horário";
          }

          $final_especial = $dados_final_especial[$cont];
          $inicial_especial = $dados_inicial_especial[$cont];
          $tempo_intervalo = $dados_dia_especial_intervalo[$cont];

          $dias = converteData($ref);
          $dados = array(
            'dia' => $dias,
            'inicial_especial' => $inicial_especial,
            'final_especial' => $final_especial,
            'id_horarios_escala' => $insertID,
            'tempo_intervalo' => $tempo_intervalo
          );     

          DBCreate('', 'tb_horarios_especiais', $dados, true);  
          registraLog('Criação tb_horarios_especiais','a','tb_horarios_especiais',$insertID,"dia: $dias | inicial_especial: $inicial_especial | final_especial: $final_especial | id_horarios_escala: $insertID | tempo_intervalo: $tempo_intervalo");
          $cont++;
        }
    }else{
      $alerta_data = ', sem nunhum horário especial!';
    }
  }
        header("location: /api/iframe?token=$request->token&view=escala-horarios");

        //header("location: /api/iframe?token=$request->token&view=escala-editar-horarios&alterar=217&mes=10&ano=2019");
        $alert = ('Item inserido com sucesso'.$alerta_data.'','s');
        exit;
}

function excluir($id){
  $query = "DELETE FROM tb_folgas_dom WHERE id_horarios_escala = ".$id;
  $link = DBConnect('');
  $result = @mysqli_query($link, $query);
  DBClose($link);
  registraLog('Exclusão de escala.', 'e', 'tb_folgas_dom', $id, '');

  $query = "DELETE FROM tb_horarios_especiais WHERE id_horarios_escala = ".$id;
  $link = DBConnect('');
  $result2 = @mysqli_query($link, $query);
  DBClose($link);
  registraLog('Exclusão de escala.', 'e', 'tb_horarios_especiais', $id, '');

  $query = "DELETE FROM tb_horarios_escala WHERE id_horarios_escala = ".$id;
  $link = DBConnect('');
  $result3 = @mysqli_query($link, $query);
  DBClose($link);


  registraLog('Exclusão de escala.', 'e', 'tb_horarios_escala', $id, '');
  if(!$result || !$result2 || !$result3){
      $alert = ('Erro ao excluir item!', 'd');
  }else{
      $alert = ('Item excluído com sucesso!', 's');
  }
  header("location: /api/iframe?token=$request->token&view=escala-horarios");
  exit;
}

function clonar($ano, $mes, $data_existente){

  $cont_sim = 0;
  $cont_nao = 0;

  $escalas_existentes = DBRead('', 'tb_horarios_escala a', "INNER JOIN tb_usuario b on a.id_usuario = b.id_usuario WHERE a.data_inicial = '".$data_existente."' AND b.status = 1 AND (b.id_perfil_sistema = 3 OR b.id_perfil_sistema = 15 OR b.id_perfil_sistema = 13) ");
   
  if($escalas_existentes){

    $resultado = '';


    foreach ($escalas_existentes as $conteudo_escalas_existentes) {
      $id_horarios_escala = $conteudo_escalas_existentes['id_horarios_escala'];
      $folga_seg = $conteudo_escalas_existentes['folga_seg'];
      $inicial_seg = $conteudo_escalas_existentes['inicial_seg'];
      $inicial_sab = $conteudo_escalas_existentes['inicial_sab'];
      $inicial_dom = $conteudo_escalas_existentes['inicial_dom'];
      $final_seg = $conteudo_escalas_existentes['final_seg'];
      $final_sab = $conteudo_escalas_existentes['final_sab'];
      $final_dom = $conteudo_escalas_existentes['final_dom'];
      $id_usuario = $conteudo_escalas_existentes['id_usuario'];
      $atendente = $conteudo_escalas_existentes['atendente'];
      $carga_horaria = $conteudo_escalas_existentes['carga_horaria'];
      $chat = $conteudo_escalas_existentes['chat'];

      $data_inicial = $ano."-".$mes."-01";
      
      $escalas_usuario = DBRead('', 'tb_horarios_escala', "WHERE data_inicial = '".$data_inicial."' AND id_usuario = '".$conteudo_escalas_existentes['id_usuario']."' ");
  
      $domingo_2 = array();

      $data_de_existente = new DateTime($conteudo_escalas_existentes['data_inicial']);
      $data_de_existente->modify('first day of this month');
      $data_ate_existente = new DateTime($conteudo_escalas_existentes['data_inicial']);
      $data_ate_existente->modify('last day of this month');    
      
      $data_de_existente = $data_de_existente->format('Y-m-d');
      $data_ate_existente = $data_ate_existente->format('Y-m-d');

      $data_de_nova = new DateTime($data_inicial);
      $data_de_nova->modify('first day of this month');
      $data_ate_nova = new DateTime($data_inicial);
      $data_ate_nova->modify('last day of this month');    
      
      $data_de_nova = $data_de_nova->format('Y-m-d');
      $data_ate_nova = $data_ate_nova->format('Y-m-d');
      
      $cont_domingo_2 = 0;

      foreach (rangeDatas($data_de_nova, $data_ate_nova) as $data) {    
      
        $diasemana_numero = date('w', strtotime($data));
        if($diasemana_numero == 0){
          $cont_domingo_2 ++;
          $domingo_2[] = $data;
        }
      }  

      if(!$escalas_usuario){
        $dados = array(
          'folga_seg' => $folga_seg,
          'inicial_seg' => $inicial_seg,
          'inicial_sab' => $inicial_sab,
          'inicial_dom' => $inicial_dom,
          'final_seg' => $final_seg,
          'final_sab' => $final_sab,
          'final_dom' => $final_dom,
          'id_usuario' => $id_usuario,
          'data_inicial' => $data_inicial,
          'atendente' => $atendente,
          'carga_horaria' => $carga_horaria,
          'chat' => $chat
        );  

       

        $insertID = DBCreate('', 'tb_horarios_escala', $dados, true);
        registraLog('Criação tb_horarios_escala clonar','a','tb_horarios_escala',$insertID,"folga_seg: $folga_seg | inicial_seg: $inicial_seg | inicial_sab: $inicial_sab | inicial_dom: $inicial_dom | final_seg: $final_seg | final_dom: $final_dom | id_usuario: $id_usuario | data_inicial: $data_inicial | atendente: $atendente | carga_horaria: $carga_horaria | chat: $chat");

        $horarios_escala_intervalo_seg = DBRead('', 'tb_horarios_escala_intervalo', "WHERE id_horarios_escala = '".$id_horarios_escala."' AND dia = 'seg' ");
        if($horarios_escala_intervalo_seg){
          $tipo_intervalo_seg = $horarios_escala_intervalo_seg[0]['tipo_intervalo'];
          $intervalo_inicial_seg = $horarios_escala_intervalo_seg[0]['intervalo_inicial'];
          $intervalo_final_seg = $horarios_escala_intervalo_seg[0]['intervalo_final'];
          $tempo_intervalo_seg = $horarios_escala_intervalo_seg[0]['tempo_intervalo'];

          $dados_intervalo_seg = array(
            'tipo_intervalo' => $tipo_intervalo_seg,
            'intervalo_inicial' => $intervalo_inicial_seg,
            'intervalo_final' => $intervalo_final_seg,
            'tempo_intervalo' => $tempo_intervalo_seg,
            'dia' => 'seg',
            'id_horarios_escala' => $insertID
          );
          DBCreate('', 'tb_horarios_escala_intervalo', $dados_intervalo_seg, true);  
          registraLog('Criação tb_horarios_escala_intervalo segunda a sexta','a','tb_horarios_escala_intervalo',$insertID,"tipo_intervalo: $tipo_intervalo_seg | intervalo_inicial: $intervalo_inicial_seg | intervalo_final: $intervalo_final_seg | tempo_intervalo: $tempo_intervalo_seg | dia: seg");
        }
        
        $horarios_escala_intervalo_sab = DBRead('', 'tb_horarios_escala_intervalo', "WHERE id_horarios_escala = '".$id_horarios_escala."' AND dia = 'sab' ");
        if($horarios_escala_intervalo_sab){
          $tipo_intervalo_sab = $horarios_escala_intervalo_sab[0]['tipo_intervalo'];
          $intervalo_inicial_sab = $horarios_escala_intervalo_sab[0]['intervalo_inicial'];
          $intervalo_final_sab = $horarios_escala_intervalo_sab[0]['intervalo_final'];
          $tempo_intervalo_sab = $horarios_escala_intervalo_sab[0]['tempo_intervalo'];
          
          if(($carga_horaria != 'jovem' && $carga_horaria != 'estagio') || ($carga_horaria == 'estagio' && $tempo_intervalo_sab)){
            $dados_intervalo_sab = array(
              'tipo_intervalo' => $tipo_intervalo_sab,
              'intervalo_inicial' => $intervalo_inicial_sab,
              'intervalo_final' => $intervalo_final_sab,
              'tempo_intervalo' => $tempo_intervalo_sab,
              'dia' => 'sab',
              'id_horarios_escala' => $insertID
            );
            DBCreate('', 'tb_horarios_escala_intervalo', $dados_intervalo_sab, true);  
            registraLog('Criação tb_horarios_escala_intervalo sabado','a','tb_horarios_escala_intervalo',$insertID,"tipo_intervalo: $tipo_intervalo_sab | intervalo_inicial: $intervalo_inicial_sab | intervalo_final: $intervalo_final_sab | tempo_intervalo: $tempo_intervalo_sab | dia: sab");
          }
        }
        
        $horarios_escala_intervalo_dom = DBRead('', 'tb_horarios_escala_intervalo', "WHERE id_horarios_escala = '".$id_horarios_escala."' AND dia = 'dom' ");
        if($horarios_escala_intervalo_dom){
          $tipo_intervalo_dom = $horarios_escala_intervalo_dom[0]['tipo_intervalo'];
          $intervalo_inicial_dom = $horarios_escala_intervalo_dom[0]['intervalo_inicial'];
          $intervalo_final_dom = $horarios_escala_intervalo_dom[0]['intervalo_final'];
          $tempo_intervalo_dom = $horarios_escala_intervalo_dom[0]['tempo_intervalo'];

          if($carga_horaria != 'jovem' && $carga_horaria != 'estagio'){
            $dados_intervalo_dom = array(
              'tipo_intervalo' => $tipo_intervalo_dom,
              'intervalo_inicial' => $intervalo_inicial_dom,
              'intervalo_final' => $intervalo_final_dom,
              'tempo_intervalo' => $tempo_intervalo_dom,
              'dia' => 'dom',
              'id_horarios_escala' => $insertID
            );
            DBCreate('', 'tb_horarios_escala_intervalo', $dados_intervalo_dom, true);  
            registraLog('Criação tb_horarios_escala_intervalo segunda a sexta','a','tb_horarios_escala_intervalo',$insertID,"tipo_intervalo: $tipo_intervalo_dom | intervalo_inicial: $intervalo_inicial_dom | intervalo_final: $intervalo_final_dom | tempo_intervalo: $tempo_intervalo_dom | dia: dom");
          }
        }        

        $folgas_domingo = DBRead('', 'tb_folgas_dom', "WHERE id_horarios_escala = '".$conteudo_escalas_existentes['id_horarios_escala']."' ");
        
        if($folgas_domingo){

           foreach ($folgas_domingo as $conteudo_folga_domingo) {
            $domingo_1 = array();

            $cont_domingo_1 = 0;

            foreach (rangeDatas($data_de_existente, $data_ate_existente) as $data) {    
              $diasemana_numero = date('w', strtotime($data));
              if($diasemana_numero == 0){
                  if($data == $conteudo_folga_domingo['dia']){
                    if($cont_domingo_1 == 4 && !$domingo_2[4]){
                      
                      $dia = $domingo_2[3];
                      $teste = $conteudo_folga_domingo['dia'];
                    }else{
                      $teste = $conteudo_folga_domingo['dia'];
                      $dia = $domingo_2[$cont_domingo_1];
                    }
                  }
                $cont_domingo_1 ++;

                $domingo_1[] = $data;
              }
            }  

            $dados_folgas = array(
              'dia' => $dia,
              'id_horarios_escala' => $insertID
            );  
            
            $insertIDfolgas = DBCreate('', 'tb_folgas_dom', $dados_folgas, true);

          }   
        }
        $cont_sim++;
      }else{
        
        $cont_nao++;
       
      }
    }
  }
  header("location: /api/iframe?token=$request->token&view=escala-horarios");
  $alert = ($cont_sim.' clonados e '.$cont_nao.' já possuem escala neste período!','s');
  exit;
}

function ativar($data){

  $data_separada = explode("-", $data);
  $ano_hoje = $data_separada[0];
      
  if($data_separada[1] == "01"){
    $mes = "Janeiro";
    }else if($data_separada[1] == "02"){
    $mes = "Fevereiro";
    }else if($data_separada[1] == "03"){
    $mes = "Março";
    }else if($data_separada[1] == "04"){
    $mes = "Abril";
    }else if($data_separada[1] == "05"){
    $mes = "Maio";
    }else if($data_separada[1] == "06"){
    $mes = "Junho";
    }else if($data_separada[1] == "07"){
    $mes = "Julho";
    }else if($data_separada[1] == "08"){
    $mes = "Agosto";
    }else if($data_separada[1] == "09"){
    $mes = "Setembro";
    }else if($data_separada[1] == "10"){
    $mes = "Outubro";
    }else if($data_separada[1] == "11"){
    $mes = "Novembro";
    }else if($data_separada[1] == "12"){
    $mes = "Dezembro";
  }

  $dados = array(
      'liberado' => 1,
  );

    DBUpdate('', 'tb_horarios_escala', $dados, "data_inicial = '".$data."'");
    //registraLog('Alteração liberacao de escala.','a','tb_horarios_escala',$data,"liberado: 1");
    $alert = ($mes."/".$ano_hoje.' Liberado com sucesso! ','s');
    header("location: /api/iframe?token=$request->token&view=escalas-liberar");


  exit;
}

function desativar($data){

  $data_separada = explode("-", $data);
  $ano_hoje = $data_separada[0];
      
  if($data_separada[1] == "01"){
    $mes = "Janeiro";
    }else if($data_separada[1] == "02"){
    $mes = "Fevereiro";
    }else if($data_separada[1] == "03"){
    $mes = "Março";
    }else if($data_separada[1] == "04"){
    $mes = "Abril";
    }else if($data_separada[1] == "05"){
    $mes = "Maio";
    }else if($data_separada[1] == "06"){
    $mes = "Junho";
    }else if($data_separada[1] == "07"){
    $mes = "Julho";
    }else if($data_separada[1] == "08"){
    $mes = "Agosto";
    }else if($data_separada[1] == "09"){
    $mes = "Setembro";
    }else if($data_separada[1] == "10"){
    $mes = "Outubro";
    }else if($data_separada[1] == "11"){
    $mes = "Novembro";
    }else if($data_separada[1] == "12"){
    $mes = "Dezembro";
  }

  $dados = array(
      'liberado' => 0,
  );

    DBUpdate('', 'tb_horarios_escala', $dados, "data_inicial = '".$data."'");
    //registraLog('Alteração liberacao de escala.','a','tb_horarios_escala',$data,"liberado: 1");
    $alert = ($mes."/".$ano_hoje.' Bloqueado com sucesso! ','s');
    header("location: /api/iframe?token=$request->token&view=escalas-liberar");


  exit;
}

function replicar($data_existente, $data_replica, $dia_escala_referencia){

  $dias_semana = array('Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado');
  $numero_dia_semana = date('w', strtotime(converteData($data_replica)));
  $escalas = DBRead('', 'tb_horarios_escala', "WHERE data_inicial = '".$data_existente."' AND folga_seg != '".$dias_semana[$numero_dia_semana]."' ");

  $cont_sim = 0;
  $cont_nao = 0;
  $nomes_nao = '';
  $nomes_nao_intervalo = '';
  if($escalas){

    foreach ($escalas as $escala) {

      $horarios_especiais = DBRead('', 'tb_horarios_especiais', "WHERE id_horarios_escala = '".$escala['id_horarios_escala']."' AND dia = '".converteData($data_replica)."' ");

      if(!$horarios_especiais){

        if($dia_escala_referencia == 'sab'){

          $consulta_inicial_banco = 'inicial_sab';
          $consulta_final_banco = 'final_sab';

          $dia_intervalo = 'sab';

        }else if($dia_escala_referencia == 'dom'){

          $consulta_inicial_banco = 'inicial_dom';
          $consulta_final_banco = 'final_dom';

          $dia_intervalo = 'dom';

        }else if($dia_escala_referencia == 'seg'){

          $consulta_inicial_banco = 'inicial_seg';
          $consulta_final_banco = 'final_seg';

          $dia_intervalo = 'seg';

        }

        $dia = converteData($data_replica);
        $inicial_especial = $escala[$consulta_inicial_banco];
        $final_especial = $escala[$consulta_final_banco];
        $id_horarios_escala = $escala['id_horarios_escala'];
        
        $horarios_escala_intervalo = DBRead('', 'tb_horarios_escala_intervalo', "WHERE id_horarios_escala = '".$escala['id_horarios_escala']."' AND tipo_intervalo = 1 AND dia = '".$dia_intervalo."' ");
        if($horarios_escala_intervalo){
          $tempo_intervalo = $horarios_escala_intervalo[0]['tempo_intervalo'];
          $dados = array(
            'dia' => $dia,
            'inicial_especial' => $inicial_especial,
            'final_especial' => $final_especial,
            'id_horarios_escala' => $id_horarios_escala,
            'tempo_intervalo' => $tempo_intervalo
          ); 
  
          DBCreate('', 'tb_horarios_especiais', $dados, true);  
          registraLog('Criação tb_horarios_especiais','a','tb_horarios_especiais',$id_horarios_escala,"dia: $dia | inicial_especial: $inicial_especial | final_especial: $final_especial | tempo_intervalo: $tempo_intervalo");
          $cont_sim++;
        }else{
          $dados = array(
            'dia' => $dia,
            'inicial_especial' => $inicial_especial,
            'final_especial' => $final_especial,
            'id_horarios_escala' => $id_horarios_escala
          ); 
  
          DBCreate('', 'tb_horarios_especiais', $dados, true);  
          registraLog('Criação tb_horarios_especiais','a','tb_horarios_especiais',$id_horarios_escala,"dia: $dia | inicial_especial: $inicial_especial | final_especial: $final_especial");
          $cont_sim++;

          $escalas_nome_intervalo = DBRead('', 'tb_horarios_escala a', "INNER JOIN tb_usuario b ON a.id_usuario = b.id_usuario INNER JOIN tb_pessoa c ON b.id_pessoa = c.id_pessoa WHERE a.id_horarios_escala = '".$id_horarios_escala."' AND a.data_inicial = '".$data_existente."' ", "c.nome");
          if($nomes_nao_intervalo != ''){
            $nomes_nao_intervalo = $nomes_nao_intervalo.', ';
          }
            $nomes_nao_intervalo = $nomes_nao_intervalo.''.$escalas_nome_intervalo[0]['nome'];
          }
        
      }else{
        $cont_nao++;
        $escalas_nome = DBRead('', 'tb_horarios_escala a', "INNER JOIN tb_usuario b ON a.id_usuario = b.id_usuario INNER JOIN tb_pessoa c ON b.id_pessoa = c.id_pessoa WHERE a.id_horarios_escala = '".$escala['id_horarios_escala']."' AND a.data_inicial = '".$data_existente."' ", "c.nome");
        if($nomes_nao != ''){
          $nomes_nao = $nomes_nao.', ';
        }
        $nomes_nao = $nomes_nao.''.$escalas_nome[0]['nome'];
      }
    }  
  }

  header("location: /api/iframe?token=$request->token&view=escala-horarios");
  if($cont_sim == 0 && $cont_nao == 0){
    $alert = ('Nenhum horário especial replicado!','w');
  }else if($cont_nao != 0){
    if($cont_nao == 1){
      $texto_nao = $cont_nao.' já possui horário especial neste período! ('.$nomes_nao.')';
    }else{
      $texto_nao = $cont_nao.' já possuem horários especiais neste período! ('.$nomes_nao.')';
    }

    if($cont_sim == 1){
      $texto_sim = $cont_sim.' horário replicado e ';
    }else{
      $texto_sim = $cont_sim.' horários replicados e ';
    }
    if($nomes_nao_intervalo != ''){
      $texto_nao_intervalo = ' e '. $nomes_nao_intervalo.' não possuem intervalos.';
    }
    $alert = ($texto_sim.''.$texto_nao.''.$texto_nao_intervalo.'','w');
  }else{
    if($cont_sim == 1){
      $texto_sim = $cont_sim.' horário replicado e 0 já possuem horários especiais neste período!';
    }else{
      $texto_sim = $cont_sim.' horários replicados e 0 já possuem horários especiais neste período! ';
    }
    if($nomes_nao_intervalo != ''){
      $texto_nao_intervalo = ' e '. $nomes_nao_intervalo.' não possuem intervalos.';
    }

      $alert = ($texto_sim.''.$texto_nao_intervalo.'','w');

  }

  exit;

}
?>