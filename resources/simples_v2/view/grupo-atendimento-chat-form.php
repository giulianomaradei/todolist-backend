<?php
require_once(__DIR__."/../class/System.php");

if (isset($_GET['alterar'])) {
    $tituloPainel = 'Alterar';
    $operacao = 'alterar';
    $id = (int)$_GET['alterar'];
    $dados = DBRead('', 'tb_grupo_atendimento_chat', "WHERE id_grupo_atendimento_chat = $id");
    $nome = $dados[0]['nome'];
    $cor = $dados[0]['cor'];
   
}else{
    $tituloPainel = 'Inserir';
    $operacao = 'inserir';
    $id = 'a';
    $nome = '';
}
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 ">
            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    <h3 class="panel-title text-left pull-left"><?= $tituloPainel ?> Grupo de Atendimento por Chat:</h3>
                    <?php if (isset($_GET['alterar'])) { echo "<div class=\"panel-title text-right pull-right\"><a  href=\"/api/ajax?class=GrupoAtendimentoChat.php?excluir= $id&token=". $request->token ."\" onclick=\"if (!confirm('Tem certeza que deseja excluir o registro?')) { return false; } else { modalAguarde(); }\"><button class=\"btn btn-xs btn-danger\"><i class=\"fa fa-trash\"></i> Excluir</button></a></div>"; } ?>
                </div>
                <form method="post" action="class/GrupoAtendimentoChat.php" id="categoria_form" style="margin-bottom: 0;">
                    <div class="panel-body" style="padding-bottom: 0;">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>*Nome:</label>
                                    <input name="nome" autofocus id="nome" type="text" class="form-control input-sm" value="<?= $nome; ?>" autocomplete="off" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>*Cor do grupo:</label>
                                    <input name="cor" autofocus id="nome" type="color" class="form-control input-sm" autocomplete="off" value="<?= $cor; ?>" required>
                                </div>
                            </div>
						</div>
                        <hr>
                        <div class="panel panel-default">
                            
                            <div class="panel-body" style="padding-bottom: 0; background-color: #f2f2f2;">
                                <div class="row" style="padding-bottom: 10px;">
                                    <div class="col-md-12">
                                        <div class="btn-group btn-group-justified" role="group">
                                            <?php
                                                $dados_planos = DBRead('','tb_plano', "WHERE cod_servico = 'call_suporte' AND status = '1' ORDER BY nome ASC");
                                                if($dados_planos){
                                                    foreach ($dados_planos as $conteudo_plano) {
                                                        if($conteudo_plano['cor'] == '#ffffff'){
                                                            $style_nome = 'style = "color:#000000"';
                                                        }else{
                                                            $style_nome = 'style = "color:#ffffff"';
                                                        }
                                                        echo '
                                                        <div class="btn-group" role="group">
                                                            <a class="btn" style="cursor: inherit; background-image: none !important; text-shadow: 0 0 0 !important; background-color: '.$conteudo_plano['cor'].' !important; font-size:13px; padding:0;">
                                                                <span '.$style_nome.'>'.$conteudo_plano['nome'].'</span>
                                                            </a>
                                                        </div>
                                                        ';
                                                    }
                                                }
                                            ?>
                                        </div>
                                    </div>
                                    <br>
                                    <hr>
                                    <div class="col-md-12">
                                        <div id="resultado_busca"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    
                    <div class="panel-footer">
                        <div class="row">
                            <div class="col-md-12" style="text-align: center">
                                <input type="hidden" id="operacao" value="<?= $id; ?>" name="<?= $operacao; ?>"/>
                                <button class="btn btn-primary" name="salvar" id="ok" type="submit"><i class="fa fa-floppy-o"></i> Salvar</button>
                            </div>
                        </div>
                    </div>
                </form>
                </div>
            </div>
        </div>
    </div>
</div>     
<script>
    // $(document).on('submit', '#categoria_form', function () {
        
    //     if(!$('#exibe_topico').is(':checked') && !$('#exibe_chamado').is(':checked') && !$('#exibe_alerta').is(':checked')){
    //         alert("Selecione pelo menos uma opção de exibição");
    //         return false;
    //     }

    //     if(!nome || nome == ""){
    //         alert("Deve-se descrever um nome!");
    //         return false;
    //     }
    //     modalAguarde();
    // });

    function call_busca_ajax(pagina){
        var operacao = '<?=$operacao?>';
        var identificador = '<?=$id?>';
       
        var parametros = {
            'operacao': operacao,
            'identificador': identificador
        };
        busca_ajax('<?= $request->token ?>' , 'GrupoAtendimentoChatFormBusca', 'resultado_busca', parametros);
    }

   

    call_busca_ajax();
</script>