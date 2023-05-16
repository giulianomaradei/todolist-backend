<?php
require_once(__DIR__."/../class/System.php");


if (isset($_GET['alterar'])) {
    $tituloPainel = 'Alterar';
    $operacao = 'alterar';
    $id = (int)$_GET['alterar'];
    $dados = DBRead('', 'tb_contrato_configuracao', "WHERE id_contrato_configuracao = $id");
    $nome_contrato = $dados[0]['nome_contrato'];
    $configuracao_cadastro = $dados[0]['contrato_descricao'];
}else{
    $tituloPainel = 'Inserir';
    $operacao = 'inserir';
    $id = 1;
    $nome = '';
}
?>
<style>
    .popover{
        max-width: 100%;
    }
</style>
<script src="inc/ckeditor/ckeditor.js"></script>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    <h3 class="panel-title text-left pull-left" style="margin-top: 2px;"><?=$tituloPainel?> modelo de contrato:</h3>
                    <div class="panel-title text-right pull-right">
                        <a tabindex="0" role="button" style="cursor:pointer;" data-toggle="popover" data-html="true" data-placement="left" title="Inofrmações de uso:" data-content="As palavras que serão mudadas <strong>devem</strong>:<br>- Estar entre as tags <strong>{@inicio} {@fim}</strong> onde deve conter<br> o <strong>nome da variável</strong> seguido do separador <strong>|</strong> seguido do <strong>tipo do dado</strong>.<br>- <strong>NÃO</strong> conter espaços. (usar underline)<br><hr>Tipos de dados:<br><strong>texto</strong>: abcdef<br><strong>numero_inteiro</strong>: 00000<br><strong>numero_flutuante</strong>: 0.00<br><strong>percentual</strong>: 0,00<br><strong>dinheiro</strong>: 1.000,00<br><strong>data</strong>: 00/00/0000<br><strong>hora</strong>: 00:00<br><strong>telefone</strong>: (00) 00000-0000<br><strong>cpf</strong>: 000.000.000-00<br><strong>cnpj</strong>: 00.000.000/0000-00<br><strong>cep</strong>: 00000-000<hr>Exemplos:<br><strong>{@inicio}Nome_do_proprietário|texto{@fim}<br>{@inicio}Telefone_de_contato|telefone{@fim}<br>{@inicio}Valor_de_adesão|dinheiro{@fim}</strong>"><i class="fa fa-question-circle"></i></a>
                        
                        <?php if (isset($_GET['alterar'])) {

                        echo "
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href=\"/api/ajax?class=ModeloContrato.php?excluir=$id&token=". $request->token ."\" title='Excluir' onclick=\"if (!confirm('Excluir contrato ".addslashes($nome_contrato)."?')) { return false; } else { modalAguarde(); }\">
                                    <button class=\"btn btn-xs btn-danger\"><i class=\"fa fa-trash\"></i> Excluir</button>
                                </a>
                            ";
                        }?>
                        
                    </div>
                </div>
                <form method="post" action="/api/ajax?class=ModeloContrato.php" id="contrato_form" style="margin-bottom: 0;">
		            <input type="hidden" name="token" value="<?php echo $request->token ?>">
	                <div class="panel-body" style="padding-bottom: 0;">
                        <div class="row"> 
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Nome:</label>
                                    <input name="nome_contrato" id="nome_contrato" class="form-control input-sm" type="text"  value="<?=$nome_contrato;?>" autocomplete="off">
                                </div>
                            </div>
                        </div>
						<div class="row"> 
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>*Descrição:</label>
                                    <textarea rows="12" cols="50" name="configuracao_cadastro" class="form-control ckeditor descricao" id="configuracao_cadastro" required> <?= $configuracao_cadastro ?></textarea>

                                </div>
                            </div>
	                    </div>
	                </div>
                    <div class="panel-footer">
                        <div class="row">
                            <div class="col-md-12" style="text-align: center">
                                <input type="hidden" id="operacao" value="<?=$id;?>" name="<?=$operacao;?>"/>
                                <button class="btn btn-primary" name="salvar" id="ok" type="submit"><i class="fa fa-floppy-o"></i> Salvar</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    
$(function(){
        $('[data-toggle="popover"]').popover({
            container: 'body'
        });
    })

</script>
