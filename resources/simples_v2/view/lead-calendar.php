<?php
    require_once(__DIR__."/../class/System.php");
?>

<script src="https://apis.google.com/js/api.js"></script>
<script async>

    $(document).ready(function() {

        var funcaoTimer = setInterval(function(){
            window.location.reload();
        }, 180000);
        
        function start() {
        
            var CLIENT_ID = '457230174695-o0mgbj8r51hd2gqj4qulhcak63rg2fbb.apps.googleusercontent.com';
            var API_KEY = 'AIzaSyBiTx3GNhlPCJPJKAFicEnZ-DJo0R1djlg';
            var DISCOVERY_DOCS = ["https://www.googleapis.com/discovery/v1/apis/calendar/v3/rest"];
            var SCOPES = "https://www.googleapis.com/auth/calendar";

            // 2. Initialize the JavaScript client library.
            gapi.client.init({
                clientId: CLIENT_ID,
                discoveryDocs: DISCOVERY_DOCS,
                scope: SCOPES
            }).then(function () {
               /*  // Listen for sign-in state changes.
                gapi.auth2.getAuthInstance().isSignedIn.listen(updateSigninStatus);
                // Handle the initial sign-in state.
                updateSigninStatus(gapi.auth2.getAuthInstance().isSignedIn.get());
                //authorizeButton.onclick = handleAuthClick;
                //signoutButton.onclick = handleSignoutClick; */

                gapi.auth.checkSessionState({session_state: null}, function(isUserNotLoggedIn){
                    if(isUserNotLoggedIn) {

                        $('#alert-login').css('display', 'block');

                        /* const authInstance = window.gapi.auth2.getAuthInstance();
                        
                        authInstance.grantOfflineAccess().then((res) => {
                            console.log(res);
                            //this.data.refreshToken = res.code;
                            location.reload();
                            modalAguarde();
                        });

                        updateSigninStatus(authInstance.isSignedIn.get()); */
                        //location.reload();

                    }else{

                        /* auth2 = gapi.auth2.getAuthInstance();
                        if(auth2.isSignedIn.get()) {
                            var profile = auth2.currentUser.get().getBasicProfile();
                            console.log('ID: ' + profile.getId());
                            console.log('Full Name: ' + profile.getName());
                            console.log('Image URL: ' + profile.getImageUrl());
                            $("#img-user-google").attr("src", profile.getImageUrl());
                            $("#img-user-google").css("display", "inline");
                        } */
                        
                        $('#alert-login').css('display', 'none');
                        $('#btn-sair').css('display', 'inline');
                        listUpcomingEvents();
                    }
                });

                //updateSigninStatus(gapi.auth2.getAuthInstance().isSignedIn.get());
                //authorizeButton.onclick = handleAuthClick;
                //signoutButton.onclick = handleSignoutClick;

            }, function(error) {
                appendPre(JSON.stringify(error, null, 2));
            });

            function updateSigninStatus(isSignedIn) {
                if (isSignedIn) {
                    listUpcomingEvents();
                }
            }

            function listUpcomingEvents() {
                modalAguarde();

                var d = new Date();
                mes = d.getMonth();

                if(d.getMonth()+3 < 10){
                    var mes = '0'+(d.getMonth()+parseInt(3));
                }

				timeMax =  d.getFullYear() + '-' + mes + '-' + d.getDate() + 'T00:00:00-03:00';

                if(d.getMonth() < 10){
                    var mes = '0'+d.getMonth();
                }

                timeMin =  d.getFullYear() + '-' + mes + '-' + d.getDate() + 'T00:00:00-03:00';
            
                var request = gapi.client.calendar.events.list({
                    'calendarId': 'primary',
                    'timeMin': timeMin,
                    'timeMax': timeMax,
                    'showDeleted': false,
                    'singleEvents': true,
                    'maxResults': 30,
                    'orderBy': 'startTime'
                });

                request.execute(function(resp) {
                    var eventos = resp.items;

                    console.log(eventos);

                    var dados = [];

                    for (i = 0; i < eventos.length; i++) { 

                        var obj = {
                            title: eventos[i].summary,
                            description: eventos[i].description,
                            start: eventos[i].start.dateTime,
                            end: eventos[i].end.dateTime,
                            creator: eventos[i].creator,
                            attendees: eventos[i].attendees,
                            htmlLink: eventos[i].htmlLink,
                            id: eventos[i].id
                            //url: eventos[i].htmlLink
                        }
                        dados.push(obj);
                    }
                    
                    var calendarEl = document.getElementById('calendar');

                    var calendar = new FullCalendar.Calendar(calendarEl, {

                        theme: true,

                        plugins: [ 'interaction', 'dayGrid', 'timeGrid', 'list', 'googleCalendar' ],

                        defaultView: 'timeGridWeek',
                        minTime: "07:00",
                        maxTime: "23:00",
                        //scrollTime: '23:59',

                        header: {
                            left: 'prev,next today',
                            center: 'title',
                            right: 'dayGridMonth, timeGridWeek, listYear'
                        },

                        eventSources: [
                            dados
                        ],

                        /* eventClick: function(arg) {
                            // opens events in a popup window
                            window.open(arg.event.url, 'google-calendar-event', 'width=700,height=600');

                            arg.jsEvent.preventDefault() // don't navigate in main tab
                        }, */

                        eventClick: function(arg) {

                            console.log(arg);
                            
                            var emails = '';

                            //nome e email dos participantes
                                for(i=0; i < arg.event.extendedProps.attendees.length; i++){
                                    nome = arg.event.extendedProps.attendees[i].email.split("@");
                                    nome = arg.event.extendedProps.attendees[i].email.split(".");

                                    if(arg.event.extendedProps.attendees[i].email != arg.event.extendedProps.creator.email){
                                        nome_letra_maiuscula = nome[0].toLowerCase().replace(/(?:^|\s)\S/g, function(a) { return a.toUpperCase(); });

                                        if(arg.event.extendedProps.attendees[i].responseStatus == 'needsAction'){
                                        status = '(<span style="color: #36648B;">Pendente</span>)';
                                        }
                                        if(arg.event.extendedProps.attendees[i].responseStatus == 'declined'){
                                            status = '(<span style="color: red;">Recusou</span>)';
                                        }
                                        if(arg.event.extendedProps.attendees[i].responseStatus == 'tentative'){
                                            status = '(<span style="color: #FF8247">Talvez</span>)';
                                        }
                                        if(arg.event.extendedProps.attendees[i].responseStatus == 'accepted'){
                                            status = '(<span style="color: green;">Confirmado</span>)';
                                        }

                                        emails = emails + '<strong>' + nome_letra_maiuscula + '</strong> ' + status + ' | ' + arg.event.extendedProps.attendees[i].email + '<br>';
                                        nome = '';
                                        nome_letra_maiuscula ='';
                                        status = '';
                                    }
                                } 
                            //end nome e email dos participantes

                            //nome e email do criador do evento
                                nome = arg.event.extendedProps.creator.email.split("@");
                                nome = arg.event.extendedProps.creator.email.split(".");

                                nome_letra_maiuscula = nome[0].toLowerCase().replace(/(?:^|\s)\S/g, function(a) { return a.toUpperCase(); });

                                criado_por = '<strong>' + nome_letra_maiuscula + '</strong> | '+ arg.event.extendedProps.creator.email;
                            // end nome e email do criador do evento

                            //formatar data
                                function convert(data) {
                                    var date = new Date(data),
                                    mnth = ("0" + (date.getMonth()+1)).slice(-2),
                                    day  = ("0" + date.getDate()).slice(-2);

                                    var weekday = new Array(7);
                                    weekday[1]="Segunda";
                                    weekday[2]="Terça";
                                    weekday[3]="Quarta";
                                    weekday[4]="Quinta";
                                    weekday[5]="Sexta";
                                    weekday[6]="Sábado";
                                    weekday[7]="Domingo";
                                    //console.log("Hoje é " + weekday[date.getDay()]);

                                    var month = new Array(12);
                                    month[0]="Janeiro";
                                    month[1]="Fevereiro";
                                    month[2]="Março";
                                    month[3]="Abril";
                                    month[4]="Maio";
                                    month[5]="Junho";
                                    month[6]="Julho";
                                    month[7]="Agosto";
                                    month[8]="Setembro";
                                    month[9]="Outubro";
                                    month[10]="Novembro";
                                    month[11]="Dezembro";
                                    //console.log("Do mês: " + month[date.getMonth()]);
                                    //return [ day, mnth, date.getFullYear() ].join("/");

                                    var minutes = date.getMinutes();

                                    if(minutes == '0'){
                                        minutes = '00';
                                    }

                                    date = weekday[date.getDay()] +', '+ day + ' de '+ month[date.getMonth()] + ' de ' + date.getFullYear() + ', às '+ date.getHours() + ':' + minutes + ' horas';

                                    return date;
                                }
                            //end formatar data

                            $('#evento_data').text(convert(arg.event.start));
                            $('#evento_titulo').text(arg.event.title);

                            if(arg.event.extendedProps.description === undefined || arg.event.extendedProps.description === null){
                                $('#evento_descricao').text('N/D');
                                
                            }else{
                                $('#evento_descricao').html(arg.event.extendedProps.description);  
                            }

                            if(emails === undefined || emails === null || emails == ''){
                                $('#evento_participantes').text('N/D');
                                
                            }else{
                                $('#evento_participantes').html(emails);   
                            }

                            btn_link = '<a class="btn btn-primary btn-sm" href="'+arg.event.extendedProps.htmlLink+'" target="_blank"><i class="fa fa-google" aria-hidden="true"></i> | Visualizar no Google Calendar</a>';

                            $('#evento_criado_por').html(criado_por);
                            $('#evento_url').html(btn_link);

                            $.get({
                                url: "/api/ajax?class=LeadTimelineAjax.php",
                                dataType: "json",
                                method: 'POST',
                                data: {
                                    acao: 'verifica_evento',
                                    parametros: { 
                                        'id_evento_google':  arg.event.id                     
                                    }
                                },
                                success: function (data) {
                                    
                                    if(data != false){
<<<<<<< HEAD
                                        btn_link_timeline = '<a class="btn btn-primary btn-sm" href="/api/iframe?token=<?php echo $request->token ?>&view=lead-negocio-informacoes&lead='+data.id_lead_negocio+'&item-timeline='+data.id_lead_timeline+'"><i class="fa fa-bars" aria-hidden="true"></i>  Visualizar na timeline </a>';
=======
                                        btn_link_timeline = '<a class="btn btn-primary btn-sm" href="/api/iframe?token=<?=$request->token?>&view=lead-negocio-informacoes&lead='+data.id_lead_negocio+'&item-timeline='+data.id_lead_timeline+'"><i class="fa fa-bars" aria-hidden="true"></i>  Visualizar na timeline </a>';
>>>>>>> 5cfe18e1256c22b2f7585786435771d80b84680b
                                        $('#evento_url').append(' '+btn_link_timeline);
                                    }
                                }
                            });

                            console.log(arg.event.url);

                            $('#myModal').modal('show');
                        },
        
                        loading: function(bool) {
                            document.getElementById('loading').style.display =
                            bool ? 'block' : 'none';
                        }

                    });
                    calendar.setOption('locale', 'pt-br');
                    calendar.render();
                    
                });

                $('#modal_aguarde').modal('hide');
            }
        };
    
        gapi.load('client:auth2', start);
    });
   
</script>

<style>
  body {
    /* margin: 40px 10px;
    padding: 0; */
    font-family: Arial, Helvetica Neue, Helvetica, sans-serif;
    font-size: 14px;
  }
  #loading {
    display: none;
    position: absolute;
    top: 10px;
    right: 10px;
  }
  #calendar {
    max-width: 1200px;
    margin: 0 auto;
  }
  thead {
      background-color: #e8e8e8;
  }
  .fc-prev-button, .fc-next-button, .fc-today-button, .fc-dayGridMonth-button, .fc-listYear-button, .fc-timeGridWeek-button{
      background-color: #337ab7;
      border-color: #337ab7;
  }
  .fc-prev-button:hover, .fc-next-button:hover, .fc-today-button:hover, .fc-dayGridMonth-button:hover, .fc-listYear-button:hover, .fc-timeGridWeek-button:hover {
      background-color: #265a88;
      border-color: #265a88;
  }
  .fc-button-active{
      background-color: #265a88 !important;
      border-color: #265a88 !important;
  }
  .fc-unthemed td.fc-today{
      background-color: #F2F2F2;
  }
  .fc-day-number{
       font-weight: 550;
  }
   
</style>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    <h3 class="panel-title text-left pull-left" style="margin-top: 2px;">Calendário | &nbsp</h3> 
                    <!-- <img id="img-user-google" src="" alt="" height="22" width="22" style="border-radius: 25px; display: none;"> -->

                   <!--  <a class="btn btn-primary btn-xs" onclick="LogarContaGoogle();"> Login</a> -->
                    <a class="btn btn-primary btn-xs" id="btn-sair" onclick="SairContaGoogle();" style="display: none;"> <i class="fa fa-google"></i> | Sair</a>
                    <div class="panel-title text-right pull-right">
                        <a class="btn btn-xs btn-primary" href="/api/iframe?token=<?php echo $request->token ?>&view=lead-negocios-busca" style="color: white;">
                            <i class="fa fa-usd"></i> Negócios
                        </a>
                        <a class="btn btn-xs btn-primary" href="/api/iframe?token=<?php echo $request->token ?>&view=lead-timeline" style="color: white;">
                            <i class="fa fa-bars"></i> Timeline
                        </a>
                        <a class="btn btn-xs btn-primary" href="/api/iframe?token=<?php echo $request->token ?>&view=pessoa-form&pagina-origem=lead-negocios-busca" style="color: white;">
                            <i class="fa fa-plus"></i> Nova Empresa/Pessoa
                        </a>
                        <a class="btn btn-xs btn-primary" href="/api/iframe?token=<?php echo $request->token ?>&view=lead-negocio-form" style="color: white;">
                            <i class="fa fa-plus"></i> Novo Negócio
                        </a>
                    </div>
                </div>
                <div class="panel-body calendar-container">
                    
                    <br>
                    <p class="alert alert-info" id="alert-login" style="text-align: center; z-index: 20; position: relative; margin-left: 10px; margin-right: 10px; margin-top: -10px; display: none;"> É necessário fazer login na sua conta Google! <br> 
                        <a class="btn btn-primary btn-xs" onclick="LogarContaGoogle();" style="margin-top: 10px;"> 
                            <i class="fa fa-google"></i> | Login
                        </a>
                    </p>
                    <br>

                    <div id='calendar'></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Informações sobre o evento -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Informações sobre o evento</h4>
      </div>
      
        <div class="modal-body">
            <div class="row">
                <div class='col-md-12'>
                    <table class="table table-striped">
                        <tbody><br> 
                            <tr>
                                <td class="td-table"><strong>Título:</strong></td>
                                <td id="evento_titulo"></td>
                            </tr>
                            <tr id="descricao">
                                <td class="td-table"><strong>Descrição:</strong></td>
                                <td id="evento_descricao"></td>
                            </tr>
                            <tr>
                                <td class="td-table"><strong>Data:</strong></td>
                                <td id="evento_data"></td>
                            </tr>
                            <tr>
                                <td class="td-table"><strong>Criado por:</strong></td>
                                <td id="evento_criado_por"></td>
                            </tr>
                            <tr>
                                <td class="td-table"><strong>Participantes:</strong></td>
                                <td id="evento_participantes" style="white-space: pre-wrap;"></td>
                            </tr>
                            <tr>
                                <td class="td-table"><strong>Link:</strong></td>
                                <td id="evento_url" style="white-space: pre-wrap;"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
  </div>
</div>
<!-- End Modal -->

<script>
    function LogarContaGoogle(){
        var auth2 = gapi.auth2.getAuthInstance();
        auth2.signOut().then(function () {
        });
        auth2.disconnect();

        const authInstance = window.gapi.auth2.getAuthInstance();

        authInstance.grantOfflineAccess().then((res) => {
            console.log(res);
            //this.data.refreshToken = res.code;
            modalAguarde();
            location.reload();
        });
    }

    function SairContaGoogle(){
        var auth2 = gapi.auth2.getAuthInstance();
        auth2.signOut().then(function () {
        });
        auth2.disconnect();

        var myWindow = window.open('https://mail.google.com/mail/u/0/?logout&hl=en/','Google', "status=no, height=500, width=500, resizable=yes, left=40%, top=0%, screenX=2000, screenY=0,toolbar=no, menubar=no, scrollbars=no, location=no, directories=no");

        //var myWindow = window.open("https://mail.google.com/mail/u/0/?logout&hl=en/", "Google", "width=500, position=fixed, height=500, top=50%, left=50%");

        var close = setInterval(function(){
            myWindow.close();
            location.reload();
        }, 4000);
    }
</script>




