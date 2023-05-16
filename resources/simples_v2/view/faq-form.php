<?php
require_once(__DIR__."/../class/System.php");

if (isset($_GET['alterar'])) {
    $tituloPainel = 'Alterar';
    $operacao = 'alterar';
    $id = (int)$_GET['alterar'];
    $dados = DBRead('', 'tb_faq', "WHERE id_faq = $id");
    $pergunta = $dados[0]['pergunta'];  
    $resposta = $dados[0]['resposta'];  
    $id_faq_categoria = $dados[0]['id_faq_categoria'];  
}else{
    $tituloPainel = 'Inserir';
    $operacao = 'inserir';
    $id = 1;
    $nome = '';   
    $resposta = ''; 
}

?>

<script src="inc/ckeditor/ckeditor.js"></script>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    <h3 class="panel-title text-left pull-left"><?= $tituloPainel ?> FAQ:</h3>
                    <?php if (isset($_GET['alterar'])) { echo "<div class=\"panel-title text-right pull-right\"><a  href=\"/api/ajax?class=Faq.php?excluir= $id&token=". $request->token ."\" onclick=\"if (!confirm('Tem certeza que deseja excluir o registro?')) { return false; } else { modalAguarde(); }\"><button class=\"btn btn-xs btn-danger\"><i class=\"fa fa-trash\"></i> Excluir</button></a></div>"; } ?>
                </div>
                <form method="post" action="/api/ajax?class=Faq.php" id="faq_form" style="margin-bottom: 0;">
		            <input type="hidden" name="token" value="<?php echo $request->token ?>">
                    <div class="panel-body" style="padding-bottom: 0;">                  
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>*Categoria:</label>
                                    <select class="form-control input-sm" name="id_faq_categoria" id="id_faq_categoria" autofocus>
                                    <?php
                                        if(!$id_faq_categoria){
                                            echo "<option value=''>Selecione uma Categoria...</option>";
                                        }
                                        $sel_id_faq_categoria[$id_faq_categoria] = 'selected';
                                        $dados_faq_categoria = DBRead('', 'tb_faq_categoria', "ORDER BY nome ASC");
                                        if ($dados_faq_categoria) {
                                            foreach ($dados_faq_categoria as $conteudo_faq_categoria) {
                                                $selected = $id_faq_categoria == $conteudo_faq_categoria['id_faq_categoria'] ? "selected" : "";
                                                echo "<option value='" . $conteudo_faq_categoria['id_faq_categoria'] . "' " . $selected . ">" . $conteudo_faq_categoria['nome'] . "</option>";
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>  
                        </div>    
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>*Pergunta:</label>
                                    <textarea name="pergunta" id="pergunta" class="form-control" rows="5" required ><?= $pergunta; ?></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>*Resposta:</label>
                                    <textarea rows="5"  required name="resposta" class="form-control ckeditor resposta" id="resposta"><?= $resposta ?></textarea>

                                    <!-- <textarea name="resposta" id="resposta" class="form-control" rows="5" required ><?= $resposta; ?></textarea> -->
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
    $(document).on('submit', '#faq_form', function () {
        var id_faq_categoria = $('#id_faq_categoria').val();
        if(!id_faq_categoria || id_faq_categoria == ''){
            alert('Você deve selecionar uma categoria!');
            return false;
        }
        modalAguarde();
    });
</script>