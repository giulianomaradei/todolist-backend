<?php
require_once(__DIR__."/../class/System.php");
// echo "<div class='alert alert-warning' role='alert' style='text-align: center'><strong>Estamos enfrentando problemas. Desculpe pelo transtorno!!</strong></div>";

//envia_email('Teste', 'Mensagem de teste do belluno.company', 'matheus.desouza@belluno.company; matheus.dasilva@belluno.company; kendy.hayashi@belluno.company', '');


?>

<div id="resultado_busca"></div>
<input type="hidden" id="input-dash" value="<?=$_GET['dash']?>">
<script>

    $(document).on('click', '.botao-dash', function(){
        window.history.replaceState('', '', '/v2//api/iframe?token=<?=$request->token?>&view=home&dash='+$(this).attr('data-dash'));
        $('#btn-menu-home').attr('href','/api/iframe?token=<?=$request->token?>&view=home&dash='+$(this).attr('data-dash'));
        $('#input-dash').val($(this).attr('data-dash'));
    });

    $( document ).ready(function() {
        $('#btn-menu-home').attr('href','/api/iframe?token=<?=$request->token?>&view=home&dash='+$('#input-dash').val());
    });
    
    function call_busca_ajax(){

        if( ($("#myModal_aniversario").data('bs.modal') || {}).isShown ){
            $("#myModal_aniversario").modal('hide');

        }else if( ($("#myModal_emp_aniversario").data('bs.modal') || {}).isShown ){
            $("#myModal_emp_aniversario").modal('hide');

        }else if( ($("#myModal_reajuste").data('bs.modal') || {}).isShown ){
            $("#myModal_reajuste").modal('hide');

        }else{
            var panel_aberto = '';
            if($('#input-dash').val()){
                panel_aberto = $('#input-dash').val();
            }else{
                panel_aberto = $('.in').attr('id');
            }
            var parametros = {
                'panel_aberto': panel_aberto
            };
            busca_ajax('<?= $request->token ?>' , 'Home', 'resultado_busca', parametros);
            //setTimeout(function(){ call_busca_ajax(); }, 60000);
        }       
    }
    call_busca_ajax();
</script>