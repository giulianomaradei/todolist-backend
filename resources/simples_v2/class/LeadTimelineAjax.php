<?php
    require_once(__DIR__."/System.php");
    

    $parametros = (!empty($_POST['parametros'])) ? $_POST['parametros'] : '';
    $acao = (!empty($_POST['acao'])) ? $_POST['acao'] : '';
    $item_timeline = $parametros['item_timeline'];

    if ($acao == 'finalizar') {
        $conteudo = DBRead('', 'tb_lead_timeline', "WHERE id_lead_timeline = '$item_timeline'", 'finalizado');

        if($conteudo[0]['finalizado'] == ''){
            $finalizado = getdatahora();
            $id_usuario = $_SESSION['id_usuario'];
    
            $dados = array(
                'finalizado' => $finalizado,
                'id_usuario_finalizou' => $id_usuario
            );
    
            DBUpdate('', 'tb_lead_timeline', $dados, "id_lead_timeline = '$item_timeline'");
            registraLog('Alteração item timeline lead.','a','tb_lead_timeline',$item_timeline,"finalizado: $finalizado | id_usuario_finalizou: $id_usuario");
            
            $usuario_finalizou = DBRead('', 'tb_pessoa a', "INNER JOIN tb_usuario b ON a.id_pessoa = b.id_pessoa WHERE b.id_usuario = '$id_usuario' ", 'a.nome');

            $dados = array(
              'nome_usuario' => $usuario_finalizou[0]['nome'],
              'data' => converteDataHora($finalizado)
            );

            echo json_encode($dados);
        }
        else{
          
            $dados = array(
                'finalizado' => '',
                'id_usuario_finalizou' => ''
            );
    
            DBUpdate('', 'tb_lead_timeline', $dados, "id_lead_timeline = '$item_timeline'");
            registraLog('Alteração item timeline lead.','a','tb_lead_timeline',$item_timeline,"finalizado: '' | id_usuario_finalizou: '' ");
    
            $dados = 0;
            echo json_encode($dados);
        }
    }//end if finalizar

    if ($acao == 'excluir') {
        
        $reuniao = DBRead('', 'tb_lead_reuniao', "WHERE id_lead_timeline = '$item_timeline'");

        $participantes = DBRead('', 'tb_lead_usuario_reuniao', "WHERE id_lead_reuniao = '".$reuniao[0]['id_lead_reuniao']."'");

        $link = DBConnect('');
        DBBegin($link);

        if($reuniao){
            foreach ($participantes as $conteudo) {
                DBDeleteTransaction($link, 'tb_lead_usuario_reuniao', "id_lead_usuario_reuniao = '".$conteudo['id_usuario_lead_reuniao']."'");
            }

            $id_evento_google = $reuniao[0]['id_evento_google'];

            DBDeleteTransaction($link, 'tb_lead_reuniao', "id_lead_reuniao = '".$reuniao[0]['id_lead_reuniao']."'");
        }else{
            $id_evento_google = 'nd';
        }

        DBDeleteTransaction($link, 'tb_lead_timeline', "id_lead_timeline = '$item_timeline'");

        DBCommit($link);

        echo json_encode($id_evento_google);
    }//end if excluir
    
    if ($acao == 'editar') {

        $dados_timeline = DBRead('', 'tb_lead_timeline', "WHERE id_lead_timeline = '$item_timeline' ");

        $dados_reuniao = DBRead('', 'tb_lead_reuniao', "WHERE id_lead_timeline = '$item_timeline' ");

        $dados_participantes = DBRead('', 'tb_lead_usuario_reuniao', "WHERE id_lead_reuniao = '".$dados_reuniao[0]['id_lead_reuniao']."' ");

        $nome_lead = DBRead('', 'tb_lead_timeline a', "LEFT JOIN tb_lead_negocio b ON a.id_lead_negocio = b.id_lead_negocio INNER JOIN tb_pessoa c ON b.id_pessoa = c.id_pessoa WHERE id_lead_timeline = $item_timeline");

        $contato_checked = '';
        if($dados_timeline[0]['contato_realizado'] == 1){
          $contato_checked = 'checked';
        }

        if($dados_participantes){
          $teste = array();
          foreach ($dados_participantes as $conteudo) {
              array_push($teste, $conteudo['id_usuario']);
          }
        }

        if($dados_reuniao){
          $tarefa_checked = 'sim';
          $display_rows = 'block';
        }else{
          $tarefa_checked = 'nao';
          $display_rows = 'none';
        }
        
        if($dados_timeline[0]['id_lead_tipo_item_timeline'] == 2){
          $display = "block";
        }else{
          $display = "none";
        }

        $data = explode(" ", $dados_reuniao[0]['data']);
        $data1 = substr($data[1], 0, -3);
    ?>
    
        <div class="modal-body">
            <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                    <label>Tipo:</label>
                    <select class="form-control" id="tipo_item_timeline_editar" >
                      <option></option>
                      <?php
                        $sel_tipo[$dados_timeline[0]['id_lead_tipo_item_timeline']] = 'selected';
                        $dados_tipo_item_timeline = DBRead('', 'tb_lead_tipo_item_timeline', "WHERE exibe = 1 ORDER BY nome ASC");

                        foreach($dados_tipo_item_timeline as $conteudo_tipo){
                      ?>
                        <option value="<?=$conteudo_tipo['id_lead_tipo_item_timeline']?>" <?=$sel_tipo[$conteudo_tipo['id_lead_tipo_item_timeline']]?>><?=$conteudo_tipo['nome']?></option>
                      <?php
                        }
                      ?>
                    </select>
                </div>
              </div>
            </div>
            <div class="row" style="margin-bottom: 7px; display: <?=$display?>;" id="row-contato-realizado_editar">
              <div class="col-md-12">
                  <div class="form-group">
                    <input class="input-contato" type="checkbox" value="1" name="contato_realizado_editar" id="contato_realizado_editar" <?=$contato_checked?>>
                    <label class="label-contato" >Contato realizado com sucesso!</label>
                  </div>
              </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label>Descrição:</label>
                        <textarea class="form-control" rows="10" name="descricao_editar" id="descricao_editar" required><?= $dados_timeline[0]['descricao'] ?></textarea>
                    </div>
                </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                  <label>Agendar tarefa?</label>
                  <?php
                      $sel_tarefa[$tarefa_checked] = 'selected';
                  ?>
                  <select class="form-control" id="agendar-tarefa_editar" name="agendar-tarefa">
                    <option value="nao" <?=$sel_tarefa['nao']?>>Não</option>
                    <option value="sim" <?=$sel_tarefa['sim']?>>Sim</option>
                  </select>
                </div>
              </div>
            </div>
            <div class="row" id="row-data-tarefa_editar" style="display: <?=$display_rows?> ;">
                <div class="col-md-6">
                  <div class="form-group">
                      <label>Data</label>
                      <input type="text" class="form-control" name="data_reuniao_editar" value="<?= converteData($data[0]) ?>"  id="data_reuniao_editar" autocomplete="off" placeholder="dd/mm/aaaa" maxlength="10">
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                      <div class="form-group">
                          <label>Hora:</label>
                          <input type="time" class="form-control" id="hora_reuniao_editar" value="<?=$data1?>" name="hora_reuniao_editar" autocomplete="off">
                          <input type="hidden" id="getdatahora_editar" value="<?=getdatahora();?>">
                      </div>
                  </div>
                </div>
            </div>
            <div class="row" id="row-usuarios-tarefa_editar" style="display: <?=$display_rows?>;">
                <div class="col-md-12">
                  <div class="form-group">
                      <label>Usuários:</label>
                      <select class="js-example-basic-multiple" id="usuarios_editar" name="usuarios_editar[]" multiple="multiple">

                      <?php
                          $usuarios = DBRead('', 'tb_pessoa a', "INNER JOIN tb_usuario b ON a.id_pessoa = b.id_pessoa WHERE (id_perfil_sistema = 22 OR id_perfil_sistema = 11 OR id_perfil_sistema = 8 OR id_perfil_sistema = 7 OR id_perfil_sistema = 29) AND b.status = 1 ORDER BY a.nome ASC", 'b.id_usuario, a.nome, b.email');

                          /* $usuarios = DBRead('', 'tb_pessoa a', "INNER JOIN tb_usuario b ON a.id_pessoa = b.id_pessoa WHERE id_perfil_sistema = 2 AND b.status = 1 ORDER BY a.nome ASC", 'b.id_usuario, a.nome, b.email'); */

                          if($usuarios){
                              foreach($usuarios as $conteudo){
                                  $id_usuario = $conteudo['id_usuario'];
                                  $nomeSelect = $conteudo['nome'];
                                  echo "<option value='$id_usuario'>$nomeSelect</option>";
                              }
                          }
                      ?>
        
                      </select>
                  </div>
                </div>
            </div>
            <div class="row" id="row-convidado_editar" style="display: <?=$display_rows?>;">
              <div class="col-md-12">
                <div class="form-group">
                  <label>Email do convidado:</label>
                  <input type="text" class="form-control" name="convidado_editar" id="convidado_editar" value="<?=$dados_timeline[0]['convidado']?>"/>
                </div>
              </div>
            </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
          <button class="btn btn-primary" name="inserir_item_timeline" value="<?= $item_timeline ?>" id="ok_editar" type="button"><i class="fa fa-floppy-o"></i> Salvar</button>
        </div>
        
        <script>

          $(document).ready(function(){
            $('.js-example-basic-multiple').select2();
            
            $('#data_reuniao_editar').datepicker({
                autoclose: true,
                dateFormat: 'dd/mm/yy',
                timeFormat: 'HH:mm:ss',
            });
        
            $('#usuarios_editar').val(<?= json_encode($teste) ?>);
            
            $('#usuarios_editar').trigger('change');

          });

          $('#tipo_item_timeline_editar').on('change', function(){
            if($(this).val() == 2){
              $('#row-contato-realizado_editar').css('display', 'block');
            }else{
              $('#row-contato-realizado_editar').css('display', 'none');
            }
          });

          $('#agendar-tarefa_editar').on('change', function(){
            if($(this).val() == 'sim'){
              $('#row-data-tarefa_editar').css('display', 'block');
              $('#row-usuarios-tarefa_editar').css('display', 'block');
              $('#row-convidado_editar').css('display', 'block');
              $('#data_reuniao_editar').addClass('date calendar hasDatePicker');
            }else{
              $('#row-data-tarefa_editar').css('display', 'none');
              $('#row-usuarios-tarefa_editar').css('display', 'none');
              $('#row-convidado_editar').css('display', 'none');
              $('#data_reuniao_editar').removeClass('date calendar hasDatePicker');
            }
          });

          function start() {

            $parametro = 'api_rd';
	          $dados_api_rd = getConfig($parametro);

            var CLIENT_ID = $dados_api_rd['CLIENT_ID'];
            var API_KEY = $dados_api_rd['CLIENT_SECRET'];
            var DISCOVERY_DOCS = ["https://www.googleapis.com/discovery/v1/apis/calendar/v3/rest"];
            var SCOPES = "https://www.googleapis.com/auth/calendar";


            // 2. Initialize the JavaScript client library.
            gapi.client.init({
              clientId: CLIENT_ID,
              discoveryDocs: DISCOVERY_DOCS,
              scope: SCOPES
            }).then(function () {
              // Listen for sign-in state changes.
              gapi.auth2.getAuthInstance().isSignedIn.listen(updateSigninStatus);
              // Handle the initial sign-in state.
              updateSigninStatus(gapi.auth2.getAuthInstance().isSignedIn.get());
              //authorizeButton.onclick = handleAuthClick;
              //signoutButton.onclick = handleSignoutClick;
            }, function(error) {
              appendPre(JSON.stringify(error, null, 2));

            });

            function updateSigninStatus(isSignedIn) {
              if (isSignedIn) {
                UpdateEvents();
                DeleteEvents();

              } else {
                $('#alert-login').css('display', 'block');
                $('#conteudo-painel').css('display', 'none');
              }
            }

            function UpdateEvents(e){

              $('#ok_editar').on('click', function(e){
                
                var descricao = $('#descricao_editar').val();
                if(descricao == ''){
                  alert('Preencha o campo descrição!');
                  return false;
                }

                var tipo = $('#tipo_item_timeline_editar').val();
                var descricao_tipo = $('#tipo_item_timeline_editar option:selected').text();
                if(tipo == ''){
                  alert('Informe o tipo!');
                  return false;
                }

                var marcar_reuniao = $('#agendar-tarefa_editar').val();
                var contato_realizado = 0;

                if($('#contato_realizado_editar').is(':checked')){
                  contato_realizado = '1';
                }
                
                var id_item_timeline = <?php echo $item_timeline ?>;
                var data_reuniao = $("input[name='data_reuniao_editar']").val();
                var hora_reuniao = $('#hora_reuniao_editar').val();
                var usuarios = $('#usuarios_editar').val();
                var id_lead_negocio = <?php echo $dados_timeline[0]['id_lead_negocio'] ?>;
                var nome_lead_prospeccao = '<?php echo $nome_lead[0]['nome'] ?>';
                var id_evento_google = '<?php echo $dados_reuniao[0]['id_evento_google'] ?>';
                var convidado = $('#convidado_editar').val();

                if(id_lead_negocio == ''){
                    id_lead_negocio = 0;
                }

                if(nome_lead_prospeccao == ''){
                    nome_lead_prospeccao = 'Não defindo';
                }

                if(marcar_reuniao == 'sim'){
                  
                  if(data_reuniao == ''){
                    alert('Informe a Data!');
                    return false;
                  }

                  var d = new Date();

                  var data = (d.getDate()<10 ? '0' : '') + d.getDate() + '/' + (d.getMonth()<10 ? '0' : '') + (parseInt(d.getMonth()) + parseInt(1)) + '/' + d.getFullYear();

                  if(data_reuniao < data){
                    alert('Data da conclusão já passou!');
                    $('input[name="data_conclusao"]').val('');
                  }

                  if(hora_reuniao == ''){
                    alert('Informe a Hora!');
                    return false;
                  }

                  if(usuarios == null){
                    alert('Informe os usuários envolvidos!');
                    return false;
                  }

                  var parts = data_reuniao.split('/');
                  var date_calendar = parts[2] + '-' + parts[1] + '-' + parts[0];
                  date_calendar = date_calendar+'T'+hora_reuniao+':00-03:00';

                  $.get({
                    url: "class/LeadTimeline.php",
                    dataType: "json",
                    method: 'GET',
                    data: {
                      acao: 'busca_emails',
                      parametros: {                           
                          'usuarios' : usuarios                                  
                      }
                    },
                    beforeSend: function(){
                      modalAguarde(); 
                    },
                    success: function (data) {
                      if(data != false){

                        if (convidado != '') {
                          email_convidado = {'email': convidado};
                          data.push(email_convidado);
                        }
                        
                        var resource = {
                          "summary": descricao_tipo+" ("+nome_lead_prospeccao+")",
                          "location": "Caçapava do Sul",
                          'description': descricao,
                          "start": {
                              "dateTime": date_calendar
                          },
                          "end": {
                              "dateTime": date_calendar
                          },
                          'params': {
                              'sendNotifications': true
                          },
                          'attendees': data,
                          'reminders': {
                              'useDefault': false,
                              'overrides': [
                                  {'method': 'email', 'minutes': 24*60},
                                  {'method': 'popup', 'minutes': 10}
                              ]
                          },
                          'conferenceData': {
                              'createRequest': {
                                  'requestId': "sample123",
                                  'conferenceSolutionKey': { 
                                    'type': "hangoutsMeet" 
                                  },
                              },
                          },
                        };

                        if(id_evento_google == '' || id_evento_google == null || id_evento_google === undefined){
                          var request = gapi.client.calendar.events.insert({
                            'calendarId': 'noreply@belluno.company',
                            'conferenceDataVersion': 1,
                            'resource': resource,
                            'sendNotifications': true
                          });
                        }else{
                          var request = gapi.client.calendar.events.update({
                            'calendarId': 'noreply@belluno.company',
                            'conferenceDataVersion': 1,
                            'eventId': id_evento_google,
                            'resource': resource
                          });
                        }
                      
                        request.execute(function(resp) {
                          
                          if(resp.code == 400 || resp.code == 404){
                            $('#myModal_editar').modal('hide');
                            $('#div-alert-successo').css("display", "none");
                            $('#div-alert-alterado').css("display", "none");
                            $('#div-alert-excluido').css("display", "none");
                            $('#div-alert-erro').css("display", "block");
                            return false;
                          }

                          $.get({
                            url: "class/LeadTimeline.php",
                            dataType: "json",
                            method: 'GET',
                            data: {
                              acao: 'editar_persiste_BD',
                              parametros: { 
                                'tipo' : tipo,
                                'descricao' : descricao,                            
                                'marcar_reuniao' : marcar_reuniao,                            
                                'data_reuniao' : data_reuniao,                            
                                'hora_reuniao' : hora_reuniao,
                                'usuarios' : usuarios,                            
                                'id_lead_negocio' : id_lead_negocio,                         
                                'id_evento_google' : resp.id,
                                'id_item_timeline' : id_item_timeline,
                                'contato_realizado': contato_realizado,
                                'convidado': convidado                       
                              }
                            },
                            success: function (data) {
                              if(data != false){
                                
                                $('#myModal_editar').modal('hide');
                                $('#div-alert-successo').css("display", "none");
                                $('#div-alert-alterado').css("display", "block");
                                $('#div-alert-excluido').css("display", "none");
                                $('#div-alert-erro').css("display", "none");
                                $("#row-negocio").load(location.href+" #row-negocio>");
                                $("#row-timeline").load(location.href+" #row-timeline>",function(responseText, textStatus, XMLHttpRequest){
                                  UpdateEvents();
                                  DeleteEvents();
                                });

                                $("#refresh").load(location.href+" #refresh>",function(responseText, textStatus, XMLHttpRequest){
                                  DeleteEvents();
                                });

                                var parametros = {
                                  'nome': '',
                                  'usuario': '',
                                  'data_item': ''
                                };

                                busca_ajax('class/LeadTimelineBusca', 'resultado_busca', parametros);
                          
                                $('#modal_aguarde').modal('hide');
                                e.preventDefault();
                              }
                              else{
                                $('#div-alert-successo').css("display", "none");
                                $('#div-alert-alterado').css("display", "none");
                                $('#div-alert-excluido').css("display", "none");
                                $('#div-alert-erro').css("display", "block");
                                $('#myModal_editar').modal('hide');
                              }
                            }
                          }); 
                        });
                      }
                    }
                  }); 

                }//end marcar reuniao
                else{

                  $.get({
                    url: "class/LeadTimeline.php",
                    dataType: "json",
                    method: 'GET',
                    data: {
                      acao: 'editar_persiste_BD',
                      parametros: { 
                        'tipo' : tipo,
                        'descricao' : descricao,                            
                        'marcar_reuniao' : marcar_reuniao,                            
                        'data_reuniao' : data_reuniao,                            
                        'hora_reuniao' : hora_reuniao,
                        'usuarios' : usuarios,                            
                        'id_lead_negocio' : id_lead_negocio,                         
                        'id_item_timeline' : id_item_timeline,
                        'contato_realizado': contato_realizado,
                        'excluir_reuniao' : 'sim'                        
                      }
                    },
                    beforeSend: function(){
                      modalAguarde();
                    },
                    success: function (data) {
                      if(data != false){
                          
                        var request = gapi.client.calendar.events.delete({
                          'calendarId': 'noreply@belluno.company',
                          'eventId': id_evento_google
                        });

                        request.execute(function(resp) {
                          
                          if(resp.code == 400 || resp.code == 404){
                            $('#myModal_editar').modal('hide');
                            $('#div-alert-successo').css("display", "none");
                            $('#div-alert-alterado').css("display", "none");
                            $('#div-alert-excluido').css("display", "none");
                            $('#div-alert-erro').css("display", "block");
                            return false;
                          }

                          var parametros = {
                            'nome': '',
                            'usuario': '',
                            'data_item': ''
                          };

                          $('#myModal_editar').modal('hide');
                          $('#div-alert-successo').css("display", "none");
                          $('#div-alert-alterado').css("display", "block");
                          $('#div-alert-excluido').css("display", "none");
                          $('#div-alert-erro').css("display", "none");
                          $("#row-negocio").load(location.href+" #row-negocio>");
                          $("#row-timeline").load(location.href+" #row-timeline>",function(responseText, textStatus, XMLHttpRequest){
                            UpdateEvents();
                            DeleteEvents();
                          });

                          $("#refresh").load(location.href+" #refresh>",function(responseText, textStatus, XMLHttpRequest){
                              DeleteEvents();
                          });

                          busca_ajax('class/LeadTimelineBusca', 'resultado_busca', parametros);
                          
                          $('#modal_aguarde').modal('hide');
                          e.preventDefault();
  
                        }); 
                      }
                      else{
                        $('#myModal_editar').modal('hide');
                        $('#div-alert-successo').css("display", "none");
                        $('#div-alert-alterado').css("display", "none");
                        $('#div-alert-excluido').css("display", "none");
                        $('#div-alert-erro').css("display", "block");
                        $('#modal_aguarde').modal('hide');
                      }
                    }
                  });
                }//end nao marcar reuniao
              });
            }

            function DeleteEvents(){
              
              $('.excluir').on('click', function(e){
                var confirm1 = confirm('Deseja excluir este item?');

                if (confirm1) {
                  
                  var item_timeline = $(this).attr('conteudo-id');

                  $.ajax({
                      url: "class/LeadTimelineAjax.php",
                      dataType: "json",
                      method: 'POST',
                      data: {
                        acao: 'excluir',
                        parametros: {                       
                          'item_timeline' : item_timeline,                            
                        }
                      },
                      success: function (data) {
                        if(data != 'nd'){

                          var request = gapi.client.calendar.events.delete({
                            'calendarId': 'noreply@belluno.company',
                            'eventId': data
                          });

                          request.execute(function(resp) {

                            if(resp.code == 400 || resp.code == 404){
                              $('#div-alert-successo').css("display", "none");
                              $('#div-alert-alterado').css("display", "none");
                              $('#div-alert-excluido').css("display", "none");
                              $('#div-alert-erro').css("display", "block");
                              return false;
                            }

                          });
                          
                          $('#div-alert-successo').css("display", "none");
                          $('#div-alert-alterado').css("display", "none");
                          $('#div-alert-excluido').css("display", "block");
                          $('#div-alert-erro').css("display", "none");
                          
                          $("#row-negocio").load(location.href+" #row-negocio>");
                          $("#row-timeline").load(location.href+" #row-timeline>",function(responseText, textStatus, XMLHttpRequest){
                            UpdateEvents();
                            DeleteEvents();
                          });

                          $("#refresh").load(location.href+" #refresh>",function(responseText, textStatus, XMLHttpRequest){
                              DeleteEvents();
                          });

                          var parametros = {
                            'nome': '',
                            'usuario': '',
                            'data_item': ''
                          };

                          busca_ajax('class/LeadTimelineBusca', 'resultado_busca', parametros);

                          $('#modal_aguarde').modal('hide');
                          e.preventDefault();
                        }
                        else{
                          $('#div-alert-successo').css("display", "none");
                          $('#div-alert-alterado').css("display", "none");
                          $('#div-alert-excluido').css("display", "block");
                          $('#div-alert-erro').css("display", "none");

                          $("#refresh").load(location.href+" #refresh>",function(responseText, textStatus, XMLHttpRequest){
                            DeleteEvents();
                          });
                          
                          $("#row-timeline").load(location.href+" #row-timeline>",function(responseText, textStatus, XMLHttpRequest){
                            DeleteEvents();
                          });

                          var parametros = {
                            'nome': '',
                            'usuario': '',
                            'data_item': ''
                          };

                          busca_ajax('class/LeadTimelineBusca', 'resultado_busca', parametros);

                          $('#modal_aguarde').modal('hide');
                          e.preventDefault();
                        } 
                      }
                  });

                }
                else{
                  return false;
                }
              });
            } 

          };//end function start

          gapi.load('client', start);

        </script>
    <?php
       
    }//end if editar

    if ($acao == 'troca_status') {

      $id_status = $parametros['id_status'];
      $id_negocio = $parametros['id_lead_negocio'];

      $link = DBConnect('');
      DBBegin($link);

      $data_troca = getdatahora();
      $id_usuario = $_SESSION['id_usuario'];

      $dados_negocio = DBRead('', 'tb_lead_negocio', "WHERE id_lead_negocio = $id_negocio");

      if($dados_negocio[0]['id_lead_status'] != $id_status){
        
        $dados_status = array(
          'data_troca' => $data_troca,
          'id_lead_status' => $id_status,
          'id_usuario' => $id_usuario,
          'id_lead_negocio' => $id_negocio
        );

        $insertAcao = DBCreateTransaction($link, 'tb_lead_status_historico', $dados_status, true);
        registraLogTransaction($link, 'Inserção de lead status historico.', 'i', 'tb_lead_status_historico', $insertAcao,"data_troca: $data_troca | id_lead_status: $id_lead_status_troca | id_usuario: $id_usuario");
      }

      $dados = array(
        'id_lead_status' => $id_status,
      );

      DBUpdateTransaction($link, 'tb_lead_negocio', $dados, "id_lead_negocio = $id_negocio");
      registraLogTransaction($link, 'Alteração lead Negócio','a','tb_lead_negocio', $id_negocio,"id_lead_status: $id_status");

      DBCommit($link);
 
      $result = 1;
      echo json_encode($result);
    } //end if troca status

    if ($acao == 'negociacao_pausada') {

      $id_lead_negocio = $parametros['id_lead_negocio'];
      $id_status = $parametros['id_status'];
      $id_lead_pausa_motivo = $parametros['id_lead_pausa_motivo'];
      $observacao = $parametros['observacao'];
      $lembrete = $parametros['lembrete'];

      $link = DBConnect('');
      DBBegin($link);

      $data_troca = getdatahora();
      $id_usuario = $_SESSION['id_usuario'];

      $dados_status = array(
        'data_troca' => $data_troca,
        'id_lead_status' => $id_status,
        'id_usuario' => $id_usuario,
        'id_lead_negocio' => $id_lead_negocio
      );

      $insertAcao = DBCreateTransaction($link, 'tb_lead_status_historico', $dados_status, true);
      registraLogTransaction($link, 'Inserção de lead status historico.', 'i', 'tb_lead_status_historico', $insertAcao,"data_troca: $data_troca | id_lead_status: $id_lead_status_troca | id_usuario: $id_usuario");

      $dados = array(
        'id_lead_status' => $id_status,
      );

      DBUpdateTransaction($link, 'tb_lead_negocio', $dados, "id_lead_negocio = $id_lead_negocio");
      registraLogTransaction($link, 'Alteração lead Negócio','a','tb_lead_negocio', $id_lead_negocio,"id_lead_status: $id_status");

      $data_pausa = getdatahora();
      $lembrete = convertedata($lembrete);

      $dados = array(
        'id_lead_negocio' => $id_lead_negocio,
        'id_lead_motivo_pausa' => $id_lead_pausa_motivo,
        'observacao' => $observacao,
        'data_pausa' => $data_pausa,
        'data_lembrete' => $lembrete
      );

      $insertID = DBCreateTransaction($link, 'tb_lead_negocio_pausado', $dados, true);
      registraLogTransaction($link, 'Inserção lead negocio pausado','i','tb_lead_negocio_pausado', $insertID,"id_lead_negocio: $id_lead_negocio | id_lead_motivo_pausa: $id_lead_pausa_motivo | observacao: $observacao | data_pausa: $data_pausa | data_lembrete: $lembrete");

      DBCommit($link);

      if($insertID != ''){
        $result = 1;
        echo json_encode($result);
      }else{
        $result = 0;
        echo json_encode($result);
      }
      
    }//end if negociacao pausada

    if ($acao == 'negocio-andamento') {

      $andamento = $parametros['andamento'];
      $id_lead_negocio = $parametros['id_lead_negocio'];
      $data_conclusao = $parametros['data_conclusao'];
      $data_conclusao = convertedata($data_conclusao);

      $dados_negocio = DBRead('', 'tb_lead_negocio', "WHERE id_lead_negocio = ' $id_lead_negocio'");

      if($dados_negocio){      
        
        if($andamento == 2){

          $id_lead_motivo_perda = $parametros['id_lead_motivo_perda'];
          $observacao = $parametros['observacao'];
          $data_lembrete = $parametros['data_lembrete'];
          $data_lembrete = convertedata($data_lembrete);
          
          if ($data_lembrete == '') {
              $data_lembrete = NULL;
          }

          $data = getdatahora();

          $link = DBConnect('');
          DBBegin($link);

          $dados = array(
            'id_lead_motivo_perda' => $id_lead_motivo_perda,
            'observacao' => $observacao,
            'id_lead_negocio' => $id_lead_negocio,
            'data_perda' => $data,
            'data_lembrete' => $data_lembrete
          );

          $insertID = DBCreateTransaction($link, 'tb_lead_negocio_perdido', $dados, true);
          registraLogTransaction($link, 'Inserção de lead negocio perdido.', 'i', 'tb_lead_negocio_perdido',$insertID,"id_lead_motivo_perda: $id_lead_motivo_perda | observacao: $observacao | id_lead_negocio: $id_lead_negocio | data_perda: $data_perda | data_lembrete: $data_lembrete");

          $dados = array(
            'andamento' => $andamento,
            'data_conclusao' => $data_conclusao
          );

          DBUpdateTransaction($link, 'tb_lead_negocio', $dados, "id_lead_negocio = $id_lead_negocio");
          registraLogTransaction($link, 'Alteração lead Negócio','a','tb_lead_negocio', $id_lead_negocio,"andamento: $andamento | data_conclusao: $data_conclusao");

          DBCommit($link);

          $result = 1;
          echo json_encode($result);

        }else if($andamento == 1){

          $fechado_com = $parametros['fechado_com'];
          $obs_ganhou = $parametros['obs_ganhou'];

          if($dados_negocio[0]['id_plano'] != 0){

            if($fechado_com == 'NULL'){
              $fechado_com = NULL;
            }

            $dados = array(
              'andamento' => $andamento,
              'id_pessoa_fechado_com' => $fechado_com,
              'data_conclusao' => $data_conclusao,
              'obs_ganhou' => $obs_ganhou
            );

            DBUpdate('', 'tb_lead_negocio', $dados, "id_lead_negocio = $id_lead_negocio");
            registraLog('Alteração lead Negócio','a','tb_lead_negocio', $id_lead_negocio,"andamento: $andamento | id_pessoa_fechado_com: $fechado_com | data_conclusao: $data_conclusao | obs_ganhou: $obs_ganhou");

            $result = 1;
            echo json_encode($result);
            
          }else{
            $result = 2;
            echo json_encode($result);
          }

        }else{
          
          $fechado_com = NULL;

          $dados = array(
            'andamento' => $andamento,
            'id_pessoa_fechado_com' => $fechado_com 
          );

          DBUpdate('', 'tb_lead_negocio', $dados, "id_lead_negocio = $id_lead_negocio");
          registraLog('Alteração lead Negócio','a','tb_lead_negocio', $id_lead_negocio,"andamento: $andamento | id_pessoa_fechado_com: $fechado_com");

          $result = 1;
          echo json_encode($result);
        }
      }
    }//end if negocio-andamento

    if ($acao == 'verifica_evento') {

      $id_evento_google = $parametros['id_evento_google'];

      $dados_timeline = DBRead('', 'tb_lead_reuniao a', "INNER JOIN tb_lead_timeline b ON a.id_lead_timeline = b.id_lead_timeline WHERE a.id_evento_google = '$id_evento_google'", 'a.id_lead_timeline, b.id_lead_negocio');

      $data = array(
        'id_lead_timeline' => $dados_timeline[0]['id_lead_timeline'],
        'id_lead_negocio' => $dados_timeline[0]['id_lead_negocio'],
      );

      if($dados_timeline){
        echo json_encode($data);
      }else{
        $data = false;
        echo json_encode($data);
      }
  
    }//end if verifica_evento

    if ($acao == 'vincular') {

      $id_lead_timeline = $parametros['id_lead_timeline'];
      $id_lead_negocio = $parametros['id_lead_negocio'];

      if($id_lead_timeline !='' && $id_lead_negocio !=''){
        
        $dados = array(
          'id_lead_negocio' => $id_lead_negocio,
        );

        DBUpdate('', 'tb_lead_timeline', $dados, "id_lead_timeline = $id_lead_timeline");
        registraLog('Alteração lead timeline','a','tb_lead_timeline', $id_lead_timeline,"id_lead_negocio: $id_lead_negocio");
        
        $result = 1;
        echo json_encode($result);
      }else{
        $result = 0;
        echo json_encode($result);
      }
    }//end if vincular

    if ($acao == 'editar_lembrete') {
      
      $id_lead_negocio_perdido = $parametros['id_lead_negocio_perdido'];
      $id_lead_motivo_perda = $parametros['id_lead_motivo_perda'];
      $observacao = $parametros['observacao'];
      $data_lembrete = convertedata($parametros['data_lembrete']);

      if ($data_lembrete == '') {
          $data_lembrete = NULL;
      }

      if ($id_lead_negocio_perdido != '' && $id_lead_motivo_perda != '' && $observacao != '') {
        
        $dados = array(
          'id_lead_motivo_perda' => $id_lead_motivo_perda,
          'observacao' => $observacao,
          'data_lembrete' => $data_lembrete
        );
  
        DBUpdate('', 'tb_lead_negocio_perdido', $dados, "id_lead_negocio_perdido = '$id_lead_negocio_perdido'");
        
        registraLog('Alteração lead negocio perdido.','a','tb_lead_negocio_perdido',$id_lead_negocio_perdido,"id_lead_motivo_perda: $id_lead_motivo_perda | observacao: $id_usuario | data_lembrete: $data_lembrete");
  
        $result = 1;
        echo json_encode($result);

      } else {
        $result = 2;
        echo json_encode($result);
      }
    }

    if ($acao == 'excluir_lembrete') {

      $id_lead_negocio_perdido = $parametros['id_lead_negocio_perdido'];

      if ($id_lead_negocio_perdido) {

          DBDelete('', 'tb_lead_negocio_perdido_visualizado',   "id_lead_negocio_perdido = '".$id_lead_negocio_perdido."'");

          DBDelete('', 'tb_lead_negocio_perdido',   "id_lead_negocio_perdido = '".$id_lead_negocio_perdido."'");

          $result = 1;
          echo json_encode($result);

      } else {
        $result = 2;
        echo json_encode($result);
      }

    }

    if ($acao == 'sinalizar_RD') {

      $id_lead_negocio = $parametros['id_lead_negocio'];

      $dados_negocio = DBRead('', 'tb_lead_negocio', "WHERE id_lead_negocio = $id_lead_negocio");

      if($dados_negocio[0]['sinalizacao_rd'] == 1){
        $sinalizacao = 0;
        
      } else {
        $sinalizacao = 1;
      }

      $dados = array(
        'sinalizacao_rd' => $sinalizacao,
      );

      DBUpdate('', 'tb_lead_negocio', $dados, "id_lead_negocio = $id_lead_negocio");
      registraLog('Alteração sinalizacao RD lead Negócio','a','tb_lead_negocio', $id_lead_negocio,"sinalizacao_rd: $sinalizacao");
 
      echo json_encode($sinalizacao);
    } //end if troca status
?>

