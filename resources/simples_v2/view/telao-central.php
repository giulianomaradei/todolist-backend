<?php

    $id_usuario = $_SESSION['id_usuario'];  
    $check = DBRead('', 'tb_telao_acesso_monitoramento', "WHERE id_usuario = $id_usuario");

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
    body {
        padding-top: 0 !important; 
    }
    .navbar{
        display: none !important;
    }
    td,th{
        padding: 0 !important;
    }
</style>
<div class="container-fluid" style="margin-right: 2px; padding-left: 2px;">

    <div class="row">
        <div class="col-lg-8" style="padding-right: 2px; padding-left: 0px;">
            <div class="row">
                <div class="col-lg-6" style="padding-right: 2px;">
                    <div class="row">
                        <div class="col-lg-12" id="entradas"></div>
                    </div>                    
                </div>
                <div class="col-lg-6" style="padding-left: 2px;">
                    <div class="row">
                        <div class="col-lg-12" id="espera"></div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12" id="saidas"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4" style="padding-right: 0px; padding-left: 2px;">            
            <div class="row">
                <div class="col-lg-12" id="agents" style="padding-right: 0px;"></div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">      
    var entrada = function(){
        $.ajax({
            cache: false,
            type: "POST",
            data: { tipo:'entrada', token: '<?= $request->token ?>'},
            url:'/api/ajax?class=TelaoCentral.php',
            success: function(data){
                $('#entradas').html(data);
            }
        });
        setTimeout(function(){ entrada(); },5000);
    };
    entrada();
    
    var agents = function(){
        $.ajax({
            cache: false,
            type: "POST",
            data: { tipo:'agents', token: '<?= $request->token ?>'},
            url:'/api/ajax?class=TelaoCentral.php',
            success: function(data){
                $('#agents').html(data);
            }
        });
        setTimeout(function(){ agents(); },5000);
    };
    agents();

    var saida = function(){
        $.ajax({
            cache: false,
            type: "POST",
            data: { tipo:'saida', token: '<?= $request->token ?>'},
            url:'/api/ajax?class=TelaoCentral.php',
            success: function(data){
                $('#saidas').html(data);
            }
        });
        setTimeout(function(){ saida(); },5000);
    };
    saida();

    var espera = function(){
        $.ajax({
            cache: false,
            type: "POST",
            data: { tipo:'espera', token: '<?= $request->token ?>'},
            url:'/api/ajax?class=TelaoCentral.php',
            success: function(data){
                $('#espera').html(data);
            }
        });
        setTimeout(function(){ espera(); },5000);
    };
    espera();     
</script>