<?php
    $id_usuario = $_SESSION['id_usuario'];
    $dados = DBRead('', 'tb_usuario', "WHERE id_usuario = '$id_usuario'");
    $perfil_sistema = $dados[0]['id_perfil_sistema'];    

    /* $check = DBRead('', 'tb_telao_acesso_atendimento', "WHERE id_usuario = $id_usuario");

    if ($check == false && $perfil_usuario != 19) {
        echo '<div class="container-fluid text-center">
            <div class="alert alert-warning">
                <i class="fa fa-exclamation-triangle" aria-hidden="true"></i> Ops! Você não tem permissão de acesso!
            </div>
        </div>';
        die();
    } */
    
?>

    <style>
        body{
            padding-top: 0 !important; 
        }
        .navbar{
            display: none !important;
        }    
        td,th{
            padding: 0 !important;
        }
        #div-parametros {
            overflow-x: scroll;
            overflow-y: hidden;
            white-space: nowrap;
        }
    </style>

    <div class="row">
        <br>
    </div>
    <div class="row">
        <div class="col-lg-12" id="div-parametro">
            <div class="row" id="">
                <table class="table table-bordered" id="table_operadores" style="margin-bottom: 0;"> 
                    <tr>
                        <th class="text-center primary">Grupos</th>
                    </tr>
                </table>
            </div>
            <div class="row" id="">
                <div class="col-lg-12" id="grupos"></div>
            </div>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-lg-8">
            <div class="row" id="">
                <div class="col-lg-12" id="operadores"></div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="row" id="">
                <div class="col-lg-12" id="proximos_operadores"></div>
            </div>
        </div>
    </div>
    
    <script>

        function animatethis(targetElement, speed) {
            var width = $(targetElement).width();
            $(targetElement).animate({ marginLeft: "-="+width},
            {
                duration: speed,
                complete: function ()
                {
                    targetElement.animate({ marginLeft: "+="+width },
                    {
                        duration: speed,
                        complete: function ()
                        {
                            animatethis(targetElement, speed);
                        }
                    });
                }
            });
        };
        animatethis($('#grupos'), 50000);

        var grupos = function(){
            $.ajax({
                cache: false,
                type: "POST",
                data: { tipo:'grupos', token: '<?= $request->token ?>'},
                url:'/api/ajax?class=TelaoAtendimentoChat.php',
                success: function(data){
                    $('#grupos').html(data);
                }
            });
            setTimeout(function(){ grupos(); },5000);
        };
        // if(perfil_sistema == 18 || perfil_sistema == 19){
            grupos();
        // }

        var operadores = function(){
            $.ajax({
                cache: false,
                type: "POST",
                data: { tipo:'operadores', token: '<?= $request->token ?>'},
                url:'/api/ajax?class=TelaoAtendimentoChat.php',
                success: function(data){
                    $('#operadores').html(data);
                }
            });
            setTimeout(function(){ operadores(); },5000);
        };
        // if(perfil_sistema == 18 || perfil_sistema == 19){
            operadores();
        // }

        var proximos_operadores = function(){
            $.ajax({
                cache: false,
                type: "POST",
                data: { tipo:'proximos_operadores', token: '<?= $request->token ?>'},
                url:'/api/ajax?class=TelaoAtendimentoChat.php',
                success: function(data){
                    $('#proximos_operadores').html(data);
                }
            });
            setTimeout(function(){ proximos_operadores(); },5000);
        };
        // if(perfil_sistema == 18 || perfil_sistema == 19){
            proximos_operadores();
        // }
    
        var velocidade_scroll = 0;

        var scroll_operadores = function() {
            velocidade_scroll = $('#table_operadores tr').length * 500;
            if(velocidade_scroll <= 5000){
                velocidade_scroll = 5000;
            }
            var direction = $('#operadores').scrollTop() != 0 ? 0 : $('#table_operadores').height() - $('#operadores').height();
            $('#operadores').animate({ scrollTop: direction }, velocidade_scroll, 'linear');
            setTimeout(function(){ scroll_operadores(); }, velocidade_scroll + 3000);
        };
        // if(perfil_sistema == 18 || perfil_sistema == 19){
            scroll_operadores();
        // }

        var scroll_proximos_operadores = function() {
            velocidade_scroll = $('#table_proximos_operadores tr').length * 500;
            if(velocidade_scroll <= 5000){
                velocidade_scroll = 5000;
            }
            var direction = $('#proximos_operadores').scrollTop() != 0 ? 0 : $('#table_proximos_operadores').height() - $('#proximos_operadores').height();
            $('#proximos_operadores').animate({ scrollTop: direction }, velocidade_scroll, 'linear');
            setTimeout(function(){ scroll_proximos_operadores(); }, velocidade_scroll + 3000);
        };
        // if(perfil_sistema == 18 || perfil_sistema == 19){
            scroll_proximos_operadores();
        // }
    
        $(document).on('click', 'body', function () {
            $("#ativa_audio").hide();
            $("#audio_ajuda").prop('muted', false);
            $("#audio_alerta").prop('muted', false);
        });

    </script>