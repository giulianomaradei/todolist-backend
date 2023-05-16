<?php
    $id_usuario = $_SESSION['id_usuario'];
    $dados = DBRead('', 'tb_usuario', "WHERE id_usuario = '$id_usuario'");
    $perfil_sistema = $dados[0]['id_perfil_sistema'];    

    $check = DBRead('', 'tb_telao_acesso_atendimento', "WHERE id_usuario = $id_usuario");

    if ($check == false && $perfil_usuario != 19) {
        echo '<div class="container-fluid text-center">
            <div class="alert alert-warning">
                <i class="fa fa-exclamation-triangle" aria-hidden="true"></i> Ops! Você não tem permissão de acesso!
            </div>
        </div>';
        die();
    }
    
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
    </style>

    <div class="row">
        <div class="col-lg-12">
            <div class="alert alert-danger faa-horizontal animated text-center" id='ativa_audio'><strong>Clique</strong> em qualquer lugar da tela para ativar os áudios!</div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-8" id="div-parametro">
            <div class="row">
                <div class="col-lg-12" id="pesquisas"></div>
            </div>
            <div class="row">
                <div class="col-lg-12" id="atendimentos_pendentes"></div>
            </div>
            <div class="row">
                <div class="col-lg-12" id="totais_mes"></div>
            </div>                
            <div class="row">
                <div class="col-lg-12" id="totais_ontem"></div>
            </div>                
            <div class="row">
                <div class="col-lg-12" id="totais_hoje"></div>
            </div>                
        </div>
        <div class="col-lg-4">
            <div class="row">
                <div class="col-lg-12" id="alerta_painel"></div>
                <audio id="audio_alerta" preload="auto" muted>
                    <source src="inc/audio/painel_alerta.mp3" type="audio/mp3" />
                </audio>
            </div>
            <div class="row">
                <div class="col-lg-12" id="solicitacoes_ajuda"></div>
                <audio id="audio_ajuda" preload="auto" muted>
                    <source src="inc/audio/painel_ajuda.mp3" type="audio/mp3" />
                </audio>
            </div>
            <div class="row" id="">
                <div class="col-lg-12" id="resultado_metas_mes"></div>
                <div class="col-lg-12" id="resultado_metas" style="max-height: 405px; overflow-y:hidden;"></div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12" id="metas"></div>
    </div>
    
    <script>

        var perfil_sistema = <?=$perfil_sistema?>;
        var id_usuario = <?=$id_usuario?>;
        
        var totais_hoje = function(){
            $.ajax({
                cache: false,
                type: "POST",
                data: { tipo:'totais_hoje', token: '<?= $request->token ?>'}, 
                url:'/api/ajax?class=TelaoAtendimento.php',
                success: function(data){
                    $('#totais_hoje').html(data);
                }
            });
            setTimeout(function(){ totais_hoje(); },600000);
            //era 10000 agora é 600000 (5 minutos)
        };
        totais_hoje();

        var totais_ontem = function(){
            $.ajax({
                cache: false,
                type: "POST",
                data: { tipo:'totais_ontem', token: '<?= $request->token ?>'}, 
                url:'/api/ajax?class=TelaoAtendimento.php',
                success: function(data){
                    $('#totais_ontem').html(data);
                },
            });
        };
        // if(perfil_sistema == 18 || perfil_sistema == 19 || perfil_sistema == 14 || id_usuario == 77){
            totais_ontem();
        // }

        //antes era 600000
        var totais_mes = function(){
            $.ajax({
                cache: false,
                type: "POST",
                data: { tipo:'totais_mes', token: '<?= $request->token ?>'}, 
                url:'/api/ajax?class=TelaoAtendimento.php',
                success: function(data){
                    $('#totais_mes').html(data);
                },
                // timeout: 3600000
            });
            // setTimeout(function(){ totais_mes(); },3600000);
        };
        // if(perfil_sistema == 18 || perfil_sistema == 19 || perfil_sistema == 14 || id_usuario == 77){
            totais_mes();
        // }
        
        var metas = function(){
            $.ajax({
                cache: false,
                type: "POST",
                data: { tipo:'metas', token: '<?= $request->token ?>'}, 
                url:'/api/ajax?class=TelaoAtendimento.php',
                success: function(data){
                    $('#metas').html(data);
                }
            });
            setTimeout(function(){ metas(); },5000);
        };
        // if(perfil_sistema == 18 || perfil_sistema == 19){
            metas();        
        // }

        var resultado_metas = function(){
            $.ajax({
                cache: false,
                type: "POST",
                data: { tipo:'resultado_metas', token: '<?= $request->token ?>'},
                url:'/api/ajax?class=TelaoAtendimento.php',
                success: function(data){
                    $('#resultado_metas').html(data);
                }
            });
            setTimeout(function(){ resultado_metas(); },5000);
        };
        // if(perfil_sistema == 18 || perfil_sistema == 19){
            resultado_metas();
        // }

        var resultado_metas_mes = function(){
            $.ajax({
                cache: false,
                type: "POST",
                data: { tipo:'resultado_metas_mes', token: '<?= $request->token ?>'},
                url:'/api/ajax?class=TelaoAtendimento.php',
                success: function(data){
                    $('#resultado_metas_mes').html(data);
                }
            });
            setTimeout(function(){ resultado_metas_mes(); },5000);
        };
        // if(perfil_sistema == 18 || perfil_sistema == 19){
            resultado_metas_mes();
        // }

        var velocidade_scroll = 0;

        var scroll_resultado_metas = function() {
            velocidade_scroll = $('#table_resultado_metas tr').length * 500;
            if(velocidade_scroll <= 5000){
                velocidade_scroll = 5000;
            }
            var direction = $('#resultado_metas').scrollTop() != 0 ? 0 : $('#table_resultado_metas').height() - $('#resultado_metas').height();
            $('#resultado_metas').animate({ scrollTop: direction }, velocidade_scroll, 'linear');
            setTimeout(function(){ scroll_resultado_metas(); }, velocidade_scroll + 3000);
        };
        // if(perfil_sistema == 18 || perfil_sistema == 19){
            scroll_resultado_metas();
        // }

        var pesquisas = function(){
            $.ajax({
                cache: false,
                type: "POST",
                data: { tipo:'pesquisas', token: '<?= $request->token ?>'}, 
                url:'/api/ajax?class=TelaoAtendimento.php',
                success: function(data){
                    $('#pesquisas').html(data);
                }
            });
            setTimeout(function(){ pesquisas(); },5000);
        };
        pesquisas();

        var atendimentos_pendentes = function(){
            $.ajax({
                cache: false,
                type: "POST",
                data: { tipo:'atendimentos_pendentes', token: '<?= $request->token ?>'}, 
                url:'/api/ajax?class=TelaoAtendimento.php',
                success: function(data){
                    $('#atendimentos_pendentes').html(data);
                }
            });
            setTimeout(function(){ atendimentos_pendentes(); },5000);
        };
        atendimentos_pendentes();
        
        var solicitacoes_ajuda = function(){
            $.ajax({
                cache: false,
                type: "POST",
                data: { tipo:'solicitacoes_ajuda', token: '<?= $request->token ?>'},
                url:'/api/ajax?class=TelaoAtendimento.php',
                success: function(data){
                    $('#solicitacoes_ajuda').html(data);
                }
            });
            setTimeout(function(){ solicitacoes_ajuda(); },1000);
        };
        solicitacoes_ajuda();

        var solicitacoes_ajuda_audio = function(){
            $.ajax({
                cache: false,
                type: "POST",
                data: { tipo:'solicitacoes_ajuda_audio', token: '<?= $request->token ?>'},
                url:'/api/ajax?class=TelaoAtendimento.php',
                success: function(data){
                    if (data == 1){
                        $('#audio_ajuda').trigger('play');
                    }
                }
            });
            setTimeout(function(){ solicitacoes_ajuda_audio(); },15000);
        };
        solicitacoes_ajuda_audio();

        var alerta_painel = function(){
            $.ajax({
                cache: false,
                type: "POST",
                data: { tipo:'alerta_painel', token: '<?= $request->token ?>'},
                url:'/api/ajax?class=TelaoAtendimento.php',
                success: function(data){
                    if(data){
                        $('#alerta_painel').html(data);
                    }else{
                        $('#alerta_painel').html('<table class="table table-bordered" style="margin-bottom: 25px;"><thead><tr style="font-size: 20px;"><th class="text-center"><span style="font-weight: 300;">Notificação de parada </span>(0)</th></tr><tr style="font-size: 16px;"><th class="text-center" style="font-weight: 300;">Alerta</th></tr></thead><tbody style="font-size: 34px;"></tbody></table>');
                    }
                }
            });
            setTimeout(function(){ alerta_painel(); },1000);//15000
        };
        alerta_painel();

        var alerta_painel_audio = function(){
            $.ajax({
                cache: false,
                type: "POST",
                data: { tipo:'alerta_painel_audio', token: '<?= $request->token ?>'},
                url:'/api/ajax?class=TelaoAtendimento.php',
                success: function(data){
                    if (data == 1){                    
                        $('#audio_alerta').trigger('play');
                    }
                }
            });
            setTimeout(function(){ alerta_painel_audio(); },15000);
        };
        setTimeout(alerta_painel_audio, 2000);

        var contador_ajuda_alerta = function(){
            $.ajax({
                cache: false,
                type: "POST",
                data: { tipo:'contador_ajuda_alerta', token: '<?= $request->token ?>'},
                url:'/api/ajax?class=TelaoAtendimento.php',
                success: function(data1){
                    if (data1 > 0) {
                        $('#metas').hide();
                    } else {
                        $('#metas').show();
                    }
                }
            });
            setTimeout(function(){ contador_ajuda_alerta(); },1000);
        };
        contador_ajuda_alerta();

        $(document).on('click', 'body', function () {
            $("#ativa_audio").hide();
            $("#audio_ajuda").prop('muted', false);
            $("#audio_alerta").prop('muted', false);
        });

    </script>