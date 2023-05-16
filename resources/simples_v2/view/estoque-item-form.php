<?php
require_once(__DIR__."/../class/System.php");

if (isset($_GET['alterar'])) {
    $tituloPainel = 'Alterar';
    $operacao = 'alterar';
    $id = (int)$_GET['alterar'];
    $dados = DBRead('', 'tb_estoque_item', "WHERE id_estoque_item = $id");
    $nome = $dados[0]['nome'];  

    $valor_unitario = converteMoeda($dados[0]['valor_unitario'], "moeda");
    $informacao_adicional = $dados[0]['informacao_adicional'];  
    $quantidade_minima = $dados[0]['quantidade_minima'];  
    $quantidade_atual = $dados[0]['quantidade'];  
    $tamanho_coluna_row = 'col-md-6';
    $display_quantidade_atual = 'style="display:none;"';

    $id_estoque_localizacao = $dados[0]['id_estoque_localizacao'];  

}else{
    $tituloPainel = 'Inserir';
    $operacao = 'inserir';
    $id = 1;
    $nome = '';   

    $valor_unitario = "0,00";
    $informacao_adicional = '';  

    $quantidade_minima = 0; 
    $quantidade_atual = 0; 
    $tamanho_coluna_row = 'col-md-4';
    $display_quantidade_atual = '';

    $id_estoque_localizacao = '';

}

?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    <h3 class="panel-title text-left pull-left"><?= $tituloPainel ?> Estoque - Item:</h3>
                    <?php if($operacao == 'alterar'){?>
                    <div class="panel-title text-right pull-right">
                        <h3 class="panel-title text-rigth pull-rigth">Quantidade Total em Estoque: <?=$quantidade_atual?></h3>
                    </div>
                    <?php } ?>
                </div>
                <form method="post" action="/api/ajax?class=EstoqueItem.php" id="estoque_item_form" style="margin-bottom: 0;">
		            <input type="hidden" name="token" value="<?php echo $request->token ?>">
                    <div class="panel-body" style="padding-bottom: 0;">                        
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>*Nome:</label>
                                    <input name="nome" autofocus id="nome" type="text" class="form-control input-sm" value="<?= $nome; ?>" autocomplete="off" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Informação Adicional:</label>
                                    <input name="informacao_adicional" id="informacao_adicional" type="text" class="form-control input-sm" value="<?= $informacao_adicional; ?>" autocomplete="off" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group" >
                                    <label>Localização:</label>
                                    <select name="id_estoque_localizacao" id="id_estoque_localizacao" class="form-control input-sm">
                                        <option value="">Selecione uma localização...</option>
                                        <?php
                                        $dados_id_localizacao = DBRead('', 'tb_estoque_localizacao', "WHERE status = 1 ORDER BY nome");

                                        if ($dados_id_localizacao) {
                                            foreach ($dados_id_localizacao as $conteudo_id_localizacao) {
                                                $selected = $id_estoque_localizacao == $conteudo_id_localizacao['id_estoque_localizacao'] ? "selected" : "";
                                                echo "<option value='".$conteudo_id_localizacao['id_estoque_localizacao']."' ".$selected.">" . $conteudo_id_localizacao['nome']."</option>";
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
						</div>
                      
                        <div class="row">
                            <div class="<?=$tamanho_coluna_row?>">
                                <div class="form-group">
                                    <label>Valor Unitário:</label>
                                    <input class="form-control input-sm money" name="valor_unitario" id="valor_unitario" type="text" autocomplete="off" value="<?=$valor_unitario?>" required>
                                </div>
                            </div>

                            <div class="<?=$tamanho_coluna_row?>">
                                <div class="form-group">
                                    <label>Quantidade Mínima:</label>
                                    <input class="form-control input-sm" name="quantidade_minima" id="quantidade_minima" type="number" autocomplete="off" value="<?=$quantidade_minima?>" max="100000" min="0" required>
                                </div>
                            </div>

                            <div class="<?=$tamanho_coluna_row?>" <?=$display_quantidade_atual?>>
                                <div class="form-group">
                                    <label>Quantidade Atual no Estoque:</label>
                                    <input class="form-control input-sm" name="quantidade_atual" id="quantidade_atual" type="number" autocomplete="off" value="<?=$quantidade_atual?>" max="100000" required>
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
    $(document).on('submit', '#estoque_item_form', function () {
        var id_estoque_localizacao = $('#id_estoque_localizacao option:selected').val();

        if(!id_estoque_localizacao || id_estoque_localizacao == ''){
            alert("Você deve selecionar a localização!");
            return false;
        }
        modalAguarde();
    });
</script>