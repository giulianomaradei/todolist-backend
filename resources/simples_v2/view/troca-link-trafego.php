<?php require_once(__DIR__."/../class/System.php"); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    <h3 class="panel-title text-left pull-left" style="margin-top: 2px;">Troca de link - Trafegos:</h3>
                    <?php if (verificaSubmenu('troca-link-form', $perfil_usuario)) { ?>                   
                    <div class="panel-title text-right pull-right"><a href="/api/iframe?token=<?php echo $request->token ?>&view=troca-link-trafego-form&inserir"><button class="btn btn-xs btn-primary" id="inserir"><i class="fa fa-plus"></i> Novo</button></a></div>                
                    <?php } ?>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Busca :</label>
                                <input class="form-control input-sm" type="text" name="nome" id="nome" onKeyUp="call_busca_ajax();" placeholder="Informe o destino desejado" autocomplete="off" autofocus>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-12">
                            <div id="resultado_busca">
                            </div>
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
        if(pagina === undefined){
            pagina = 1;
        }
        var parametros = {
            'nome': nome,
            'pagina': pagina
        };
        busca_ajax('<?= $request->token ?>' , 'TrocaLinkTrafegoBusca', 'resultado_busca', parametros);
    }

    $(document).on('click', '.troca_pag', function () {
        call_busca_ajax($(this).attr('atr-pagina'));
    });

    call_busca_ajax();
</script>


<?php

#################################### SUBSTITUINDO O.S POR ATENDIMENTO #########################################


// ALTERA TEXTO OS
    $filtros_query = "WHERE (texto_os LIKE '%Posição de atendimeto%')";
    $dados = DBRead('', 'tb_atendimento_arvore', $filtros_query);

    foreach ($dados as $dado){
        $texto = str_replace("posição de atendimeto", "posição de atendimento", $dado['texto_os']);
        $texto_os = array(
            'texto_os' => $texto
        );
        DBUpdate('', 'tb_atendimento_arvore', $texto_os, "id_atendimento_arvore = ".$dado['id_atendimento_arvore']);

    }
    $dados2 = DBRead('', 'tb_atendimento_arvore', "WHERE (texto_os LIKE '%Posição de atendimento%')");
    var_dump($dados2);


// ALTERA TEXTO OPÇÃO PERGUNTA

// function altera_pergunta (){
    $filtros_query = "WHERE (nome LIKE '%Posição de atendimeto%')";
    $dados = DBRead('', 'tb_pergunta', $filtros_query);

    foreach ($dados as $dado){
        $texto = str_replace("posição de os", "posição de atendimeto", $dado['nome']);
        $pergunta = array(
            'nome' => $texto
        );
        DBUpdate('', 'tb_pergunta', $pergunta, "id_pergunta = ".$dado['id_pergunta']);
    }
    $dados2 = DBRead('', 'tb_pergunta');
    var_dump($dados2);
// }


?>