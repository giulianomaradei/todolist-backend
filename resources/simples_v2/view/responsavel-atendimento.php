<?php
require_once(__DIR__."/../class/System.php");

?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    <h3 class="panel-title text-left pull-left" style="margin-top: 2px;">Responsáveis pelos Atendimentos:</h3>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group has-feedback">
                                <label class="control-label sr-only">Hidden label</label>
                                <input class="form-control" type="text" name="nome" id="nome" onKeyUp="call_busca_ajax();" placeholder="Informe o nome usuário..." autocomplete="off" autofocus>
                                <span class="glyphicon glyphicon-search form-control-feedback"></span>
                            </div>
                        </div> 
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-12">
                            <div id="resultado_busca"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
function call_busca_ajax(pagina){
    var inicia_busca = 1;
    var nome = $('#nome').val();
    if (nome.length < inicia_busca && nome.length >=1){
        return false;
    }
    
    var parametros = {
        'nome': nome,
    };
    busca_ajax('<?= $request->token ?>' , 'ResponsavelAtendimentoBusca', 'resultado_busca', parametros);
}

call_busca_ajax();

$(document).ready(function(){

    $.ajax({
        url: "/api/ajax?class=ResponsavelAtendimento.php",
        dataType: "json",
        method: 'POST',
        data: {
        acao: 'verifica_antigo',
        token: '<?= $request->token ?>'
    },
        success: function (data) {

            if(data == 1){
                location.reload(true);
            }
        }
    });

});

$(document).on('click', '.botao_voz', function(){

    var botao = $(this);
    var texto_botao = botao.html().split(" ")[0];
    var id_usuario = $(this).attr("id").split("id_")[1];
    var nome_usuario = $(this).parent().parent().find('.nome_atendente').text();
    var botao_agendamento = $('#ag_'+id_usuario+'');            

    if(texto_botao == "Tornar"){
        
        $.ajax({
        url: "class/ResponsavelAtendimento.php",
        dataType: "json",
        method: 'POST',
        data: {
            acao: 'verificar',
            parametros : {
                'id_usuario': id_usuario
            },
        },
    
            success: function (data) {

                if(data == 1){
                    alert('Este usuário já é responsável!');
                    botao.html("Deixar de ser Responsável");
                    cor = botao.attr('cor');
                    botao.removeClass(cor);
                    botao.addClass('btn-warning');
                    botao.attr('cor','btn-warning');

                }else{
                    
                    if (!confirm('Você deseja tornar o(a) '+nome_usuario+' responsável pelos atendimentos via telefone?')){
                        return false; 
                    }else{
                        $.ajax({
                        url: "class/ResponsavelAtendimento.php",
                        dataType: "json",
                        method: 'POST',
                        data: {
                            acao: 'inserir_responsavel',
                            parametros : {
                                'id_usuario': id_usuario
                            },
                        },
                        });
                        botao.html("Deixar de ser Responsável");
                        cor = botao.attr('cor');
                        botao.removeClass(cor);
                        botao.addClass('btn-warning');
                        botao.attr('cor','btn-warning');
                    }
                }
            }
        });
            
    }else{

        $.ajax({
        url: "class/ResponsavelAtendimento.php",
        dataType: "json",
        method: 'POST',
        data: {
            acao: 'verificar',
            parametros : {
                'id_usuario': id_usuario
            },
        },
    
            success: function (data) {

                if(data == 1){
                    if (!confirm('Você deseja remover a responsabilidade dos atendimentos via telefone de '+nome_usuario+'?')){
                        return false; 
                    }

                    $.ajax({
                    url: "class/ResponsavelAtendimento.php",
                    dataType: "json",
                    method: 'POST',
                    data: {
                        acao: 'remover_responsavel',
                        parametros : {
                            'id_usuario': id_usuario
                        },
                    },
                    });
                    botao.html("Tornar Responsável");
                    cor = botao.attr('cor');
                    botao.removeClass(cor);
                    botao.addClass('btn-info');
                    botao.attr('cor','btn-info');

                }else if (data == 0){
                    alert('Este usuário não está como responsável!');
                    botao.html("Tornar Responsável");
                    cor = botao.attr('cor');
                    botao.removeClass(cor);
                    botao.addClass('btn-info');
                    botao.attr('cor','btn-info');

                }
            }
        });     
    }
});

$(document).on('click', "#deixa_responsavel_texto", function(){

var id_usuario = $(this).attr("iddeixa_").split("iddeixa_")[1];

var nome_usuario = $('#nome_atendente_'+id_usuario).text();
if (!confirm('Você deseja remover a responsabilidade do(s) atendimento(s) via texto de '+nome_usuario+'?')){
    return false; 
}

modalAguarde();

$.ajax({
    url: "class/ResponsavelAtendimento.php",
    dataType: "json",
    method: 'POST',
    data: {
        acao: 'remover_responsavel_texto',
        parametros : {
            'id_usuario': id_usuario
        },
    },

    success: function (data) {
        $(".modal").modal('hide');
        var botao = $('.class_'+id_usuario);
        botao.html("Tornar Responsável");
        cor = botao.attr('cor');
        botao.removeClass(cor);
        botao.addClass('btn-info');
        botao.attr('cor','btn-info');

        $('#idmodal_'+id_usuario).html('');

        $(".checktexto_"+id_usuario).each(function(){
            $(this).attr('checked', false);
        });

        $("#panel_buttons_"+id_usuario).html('<button class="btn btn-info tornar_atualizar_texto" name="tornar_texto" id="tornar_texto" value="inserir" type="button" idtexto_="idtexto_'+id_usuario+'" disabled><i class="fa fa-check"></i> Tornar Responsável</button>');

        $('.tornar_atualizar_texto').attr("disabled", true);
    }
});
});


$(document).on('click', "[name ='contratos[]']", function(){
var cont = 0;
var verificador = '';
var verificador_contrato = '';

$("."+$(this).attr("class")+"").each(function(){
    if ($(this).is(':checked')){
        cont++;
        verificador = verificador+','+$(this).val();
        verificador_contrato = verificador_contrato+','+$(this).attr("nome_contrato");
        $("#auxiliar").val(verificador);
        $("#auxiliar_contrato").val(verificador_contrato);
    }
});
if(cont == 0){
    $('.tornar_atualizar_texto').attr("disabled", true);
}else{
    $('.tornar_atualizar_texto').attr("disabled", false);
}

});

$(document).on('click', '.tornar_atualizar_texto', function(){

var id_usuario = $(this).attr("idtexto_").split("idtexto_")[1];

if($(this).val() == 'inserir'){
    var nome_usuario = $('#nome_atendente_'+id_usuario).text();
    if (!confirm('Você deseja tornar o(a) '+nome_usuario+' responsável pelo(s) atendimento(s) via texto?')){
        return false; 
    }
    modalAguarde();
    $(".checktexto_"+id_usuario).each(function(){
        if ($(this).is(':checked')){
            var grupo_atendimento_chat = $(this).val();

            $.ajax({
                url: "class/ResponsavelAtendimento.php",
                dataType: "json",
                method: 'POST',
                data: {
                    acao: 'inserir_responsavel_texto',
                    parametros : {
                        'id_usuario': id_usuario,
                        'grupo_atendimento_chat': grupo_atendimento_chat,
                    },
                },

                success: function (data) {
                    $(".modal").modal('hide');
                    var botao = $('.class_'+id_usuario);
                    texto = $("#auxiliar_contrato").val();
                    botao.html(' Responsável por: '+texto.substr(1)+'');
                    cor = botao.attr('cor');
                    botao.removeClass(cor);
                    botao.addClass('btn-warning');
                    botao.attr('cor','btn-warning');

                    $('#idmodal_'+id_usuario).html('<button class="btn btn-xs btn-warning" id="deixa_responsavel_texto" iddeixa_="iddeixa_'+id_usuario+'"> Deixar de ser Responsável</button>');

                    $("#panel_buttons_"+id_usuario).html('<button class="btn btn-primary tornar_atualizar_texto" name="tornar_texto" id="tornar_texto" value="alterar" type="button" idtexto_="idtexto_'+id_usuario+'" disabled><i class="fa fa-refresh"></i> Atualizar</button>');
                    
                    $('.tornar_atualizar_texto').attr("disabled", true);
                }
            });
        }
    });	

}else if($(this).val() == 'alterar'){

    var nome_usuario = $('#nome_atendente_'+id_usuario).text();
    if (!confirm('Você deseja alterar a responsabilidade do(s) atendimento(s) via texto de '+nome_usuario+'?')){
        return false; 
    }
    modalAguarde();
    var verificador = '';
    $(".checktexto_"+id_usuario).each(function(){
        if ($(this).is(':checked')){

            var grupo_atendimento_chat = $(this).val();
            $.ajax({
                url: "class/ResponsavelAtendimento.php",
                dataType: "json",
                method: 'POST',
                data: {
                    acao: 'alterar_responsavel_texto',
                    parametros : {
                        'id_usuario': id_usuario,
                        'grupo_atendimento_chat': grupo_atendimento_chat,
                    },
                },

                success: function (data) {
                    $(".modal").modal('hide');
                    var botao = $('.class_'+id_usuario);
                    texto = $("#auxiliar_contrato").val();
                    botao.html(' Responsável por: '+texto.substr(1)+'');
                    $("#panel_buttons_"+id_usuario).html('<button class="btn btn-primary tornar_atualizar_texto" name="tornar_texto" id="tornar_texto" value="alterar" type="button" idtexto_="idtexto_'+id_usuario+'" disabled><i class="fa fa-refresh"></i> Atualizar</button>');

                    $('.tornar_atualizar_texto').attr("disabled", true);
                }
                
            });
        }
    });

    //VERIFICA se foi removido algum
    var auxiliar = $("#auxiliar").val();
    $.ajax({
        url: "class/ResponsavelAtendimento.php",
        dataType: "json",
        method: 'POST',
        data: {
            acao: 'verifica_removido_texto',
            parametros : {
                'id_usuario': id_usuario,
                'auxiliar': auxiliar
            },
        },
    });
}

});

</script>