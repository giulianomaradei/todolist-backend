<div class="modal fade noprint" id="modal_softphone"  tabindex="-1" role="dialog">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Softphone</h4>
            </div>
            <div class="modal-body form" style="margin-bottom: 0px; padding-bottom: 0px;">  
                <div class="form-body">  
                    <div class="row" id="row-ligar-softphone" style="display: none;">
                        <div class="col-xs-12">
                            <div class="form-group">
                                <label>Ligar para número:</label>
                                <div class="input-group">
                                    <input class="form-control" name="telefone" id="phone-softphone" value="" type="text">
                                    <div class="input-group-btn">
                                        <button class="btn btn-success" id="call-softphone" type="button"><i class="fa fa-phone"></i></button>
                                    </div>
                                </div>
                            </div> 
                        </div>
                    </div>                                                  
                    <div class="row" id="row-pause-softphone" style="display: none;">
                        <div class="col-xs-12">
                            <div class="form-group">
                                <label>Pausar receptivo:</label>
                                <div class="input-group">
                                    <select class="form-control" name="tipo-pausa-softphone" id="tipo-pausa-softphone" required>
                                        <?php
                                        $dados_tipos_pausas = DBRead('snep','tipo_pausa',"WHERE status='1' ORDER BY nome ASC");
                                        if ($dados_tipos_pausas) {
                                            foreach ($dados_tipos_pausas as $conteudo) {
                                                $id_select = $conteudo['id'];
                                                $nome_select = $conteudo['nome'];
                                                $descricao_select = $conteudo['descricao'];;
                                                echo "<option value='$id_select' title='$descricao_select'>$nome_select</option>";
                                            }
                                        }
                                        ?>
                                    </select>
                                    <div class="input-group-btn">
                                        <button class="btn btn-danger" id="pause-softphone" type="button"><i class="fa fa-pause"></i></button>
                                    </div>
                                </div>
                            </div>                                    
                        </div>
                    </div>
                    <div class="row" id="row-unpause-softphone" style="display: none;">
                        <div class="col-xs-12">
                            <div class="alert alert-warning">
                                <div class="row">
                                    <div class="col-xs-10 text-left"><i id="exclamation_pausa_softphone" class="fa fa-exclamation-circle faa-flash animated" style="color: #b92c28; display: none;"></i> <span id="ultima-pausa-softphone"></span></div>
                                    <div class="col-xs-2 text-right"><button class="btn btn-success btn-xs" id="unpause-softphone" type="button">Voltar <i class="fa fa-play"></i></button></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row" id="row-login-softphone" style="display: none;">
                        <div class="col-xs-12">
                            <div class="form-group">
                                <label><i id="exclamation_login_softphone" class="fa fa-exclamation-circle faa-flash animated" style="color: #b92c28; display: none;"></i> Logar na PA (Nº no telefone):</label>
                                <div class="input-group">
                                    <input class="form-control number_int" name="ramal-login-softphone" id="ramal-login-softphone" value="" type="text">
                                    <div class="input-group-btn">
                                        <button class="btn btn-success" id="login-softphone" type="button"><i class="fa fa-sign-in"></i></button>
                                    </div>
                                </div>
                            </div>                                    
                        </div>
                    </div>
                    <div class="row" id="row-logoff-softphone" style="display: none;">
                        <div class="col-xs-12">
                            <div class="alert alert-info">
                                <div class="row">
                                    <div class="col-xs-10 text-left"><span id="ramal-logoff-softphone"></span></div>
                                    <div class="col-xs-2 text-right"><button class="btn btn-danger btn-xs" id="logoff-softphone" type="button"><i class="fa fa-sign-out"></i> Sair</button></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>                
            </div>
        </div>
    </div>
</div>
<script>
    function ligar_softphone(telefone){
        if(telefone){
            $.ajax({
                url: "/api/ajax?class=AsteriskAgent.php",
                dataType: "json",
                method: "POST",
                data: { comando:'call', numero:telefone, token: '<?= $request->token ?>' },
                success: function (data) {                    
                    $('#call-softphone').prop("disabled", false);
                    $('#phone-softphone').prop("disabled", false);
                    if(data['sucesso'] && data['dados'] == 'Error'){
                        console.log('Ligação não realizada!');
                    }else if(data['sucesso'] && data['dados'] == 'Success'){
                        console.log('Ligação realizada para ' + telefone +'!');
                    }else if(!data['sucesso']){
                        alert('Existe algum problema em seu login na central telefônica, atualize a página(F5)!');
                    }
                },
                beforeSend: function(){
                    $('#call-softphone').prop("disabled", true);
                    $('#phone-softphone').prop("disabled", true);
                }
            });
        }else{
            alert('Digite um número válido!');
        }
    }

    function login_softphone(ramal){
        if(ramal){
            $.ajax({
                url: "/api/ajax?class=AsteriskAgent.php",
                dataType: "json",
                method: "POST",
                data: { comando:'login', ramal:ramal, token: '<?= $request->token ?>' },
                success: function (data) {
                    if(data['sucesso'] && data['dados'] == 'Error'){
                        alert('Falha no login!');
                        $('#login-softphone').prop("disabled", false);
                        $('#ramal-login-softphone').prop("disabled", false);
                    }else if(data['sucesso'] && data['dados'] == 'Success'){
                        verifica_login_softphone(15);
                    }else if(!data['sucesso'] && !data['dados']){
                        alert('PA ou operador em uso!');
                        $('#login-softphone').prop("disabled", false);
                        $('#ramal-login-softphone').prop("disabled", false);
                    }
                },
                beforeSend: function(){
                    $('#login-softphone').prop("disabled", true);
                    $('#ramal-login-softphone').prop("disabled", true);
                }
            });
        }else{
            alert('Digite uma PA!');
        }
    }

    function verifica_login_softphone(cont){
        $.ajax({
            url: "/api/ajax?class=AsteriskAgent.php",
            dataType: "json",
            method: "POST",
            data: { comando:'verificaLogin', token: '<?= $request->token ?>' },
            success: function (data) {  
                if(cont > 0 && !data['logado']){
                    setTimeout(function(){ verifica_login_softphone(cont-1); },1000);
                }else if(data['logado']){
                    $("#exclamation_login_softphone").hide();
                    $("#exclamation_softphone").hide();
                    $("#row-login-softphone").hide();
                    $("#row-logoff-softphone").show();
                    $("#row-ligar-softphone").show();
                    $("#ramal-logoff-softphone").html("<strong>Logado na PA:</strong> "+data['sip']);
                    $('#login-softphone').prop("disabled", false);
                    $('#ramal-login-softphone').prop("disabled", false);                    
                    verifica_pause_softphone(true);
                }else{
                    $("#exclamation_login_softphone").show();
                    $("#exclamation_softphone").show();
                    $("#row-login-softphone").show();
                    $("#row-logoff-softphone").hide();                    
                    $("#row-ligar-softphone").hide();                    
                    $("#row-pause-softphone").hide();
                    $("#row-unpause-softphone").hide();                     
                    console.log('Login não realizado na central!');
                    $('#login-softphone').prop("disabled", false);
                    $('#ramal-login-softphone').prop("disabled", false);
                }
            }
        });    
    }

    function logoff_softphone(){
        $.ajax({
            url: "/api/ajax?class=AsteriskAgent.php",
            dataType: "json",
            method: "POST",
            data: { comando:'logoff', token: '<?= $request->token ?>' },
            success: function (data) {  
                if(data['sucesso'] && data['dados'] == 'Error'){
                    alert('Falha no logoff!');
                    $('#logoff-softphone').prop("disabled", false);
                }else if(data['sucesso'] && data['dados'] == 'Success'){
                    verifica_logoff_softphone(10);
                }else if(!data['sucesso'] && !data['dados']){
                    alert('Você já efetuou logoff!');
                    $('#logoff-softphone').prop("disabled", false);
                }
            },
            beforeSend: function(){
                $('#logoff-softphone').prop("disabled", true);
            }
        });
    }

    function verifica_logoff_softphone(cont){
        $.ajax({
            url: "/api/ajax?class=AsteriskAgent.php",
            dataType: "json",
            method: "POST",
            data: { comando:'verificaLogoff', token: '<?= $request->token ?>' },
            success: function (data) {
               if(cont > 0 && data['logado']){
                    setTimeout(function(){ verifica_logoff_softphone(cont-1); },1000);                    
                }else if(!data['logado']){
                    $("#exclamation_login_softphone").show();
                    $("#exclamation_softphone").show();
                    $("#row-login-softphone").show();
                    $("#row-logoff-softphone").hide();
                    $("#row-pause-softphone").hide();
                    $("#row-unpause-softphone").hide();
                    $("#row-ligar-softphone").hide();
                    console.log('Logoff da central realizado!');
                    $('#logoff-softphone').prop("disabled", false);
                }
            }
        });    
    }

    function pause_softphone(tipo_pausa){
        if(tipo_pausa){
            $.ajax({
                url: "/api/ajax?class=AsteriskAgent.php",
                dataType: "json",
                method: "POST",
                data: { comando:'pause', tipo_pausa:tipo_pausa , token: '<?= $request->token ?>' },
                success: function (data) {
                    if(data['sucesso']){
                        verifica_pause_softphone(true);
                        console.log('Receptivo pausado!');                        
                    }else{
                        alert('Existe algum problema em seu login na central telefônica, atualize a página(F5)!');
                    }
                    $('#pause-softphone').prop("disabled", false);
                    $('#tipo-pausa-softphone').prop("disabled", false);
                },
                beforeSend: function(){
                    $('#pause-softphone').prop("disabled", true);
                    $('#tipo-pausa-softphone').prop("disabled", true);
                }
            });
        }else{
            alert('Selecione uma pausa!');
        }
    }

    function verifica_pause_softphone(exclamation_softphone){
        $.ajax({
            url: "/api/ajax?class=AsteriskAgent.php",
            dataType: "json",
            method: "POST",
            data: { comando:'verificaPause', token: '<?= $request->token ?>' },
            success: function (data) {
                if(data['pausa']){
                    $("#ultima-pausa-softphone").html('<strong>Em pausa:</strong> '+data['nome']+" - "+data['data_pause']);
                    $("#row-pause-softphone").hide();
                    $("#row-unpause-softphone").show();
                    $("#exclamation_pausa_softphone").show();
                    if(exclamation_softphone){
                        $("#exclamation_softphone").show();    
                    }                     
                }else{
                    $("#ultima-pausa-softphone").html('');
                    $("#row-pause-softphone").show();
                    $("#row-unpause-softphone").hide();                    
                    $("#exclamation_pausa_softphone").hide();
                    if(exclamation_softphone){    
                        $("#exclamation_softphone").hide(); 
                    }   
                }
            }
        });
    }

    function unpause_softphone(){
        $.ajax({
            url: "/api/ajax?class=AsteriskAgent.php",
            dataType: "json",
            method: "POST",
            data: { comando:'unpause', token: '<?= $request->token ?>' },
            success: function (data) {
                $('#unpause-softphone').prop("disabled", false);  
                if(data['sucesso']){
                    verifica_pause_softphone(true);
                }else{
                    alert('Não foi possivel continuar, atualize a página(F5)!');
                }                
            },
            beforeSend: function(){
                $('#unpause-softphone').prop("disabled", true);
            }
        });
    }
    
    $(document).ready(function(){
        verifica_login_softphone(1);
        $("#li-softphone").on("click",function(){
            verifica_login_softphone(1);           
        });

        $("#call-softphone").on("click",function(){
            ligar_softphone($('#phone-softphone').val());           
        });
        $('#phone-softphone').keyup(function(event) {
            if (event.keyCode == '13') {
                ligar_softphone($('#phone-softphone').val());
            }
        });

        $("#pause-softphone").on("click",function(){
            pause_softphone($('#tipo-pausa-softphone').val());
        });

        $("#unpause-softphone").on("click",function(){
            unpause_softphone();
        });

        $("#login-softphone").on("click",function(){
            login_softphone($('#ramal-login-softphone').val());
        });
        $('#ramal-login-softphone').keyup(function(event) {
            if (event.keyCode == '13') {
                login_softphone($('#ramal-login-softphone').val());
            }
        });

        $("#logoff-softphone").on("click",function(){
            logoff_softphone($('#ramal-login-softphone').val());
        });
    });
</script>