<?php
require_once(__DIR__."/../class/System.php");

if (isset($_GET['alterar'])) {
    $tituloPainel = 'Alterar';
    $operacao = 'alterar';
    $id = (int)$_GET['alterar'];

    $dados = DBRead('', 'tb_patrimonio', "WHERE id_patrimonio = $id");

    $valor_compra = converteMoeda($dados[0]['valor_compra']);
    $numero_patrimonio = $dados[0]['numero_patrimonio'];
    $id_patrimonio_item = $dados[0]['id_patrimonio_item'];
    $id_patrimonio_localizacao = $dados[0]['id_patrimonio_localizacao'];
    $id_responsavel = $dados[0]['id_responsavel'];
    $id_fornecedor = $dados[0]['id_fornecedor'];
    $data_compra = $dados[0]['data_compra'];
    $data_garantia = $dados[0]['data_garantia'];
    $status = $dados[0]['status'];
    $numero_nota_fiscal = $dados[0]['numero_nota_fiscal'];
    $observacao = $dados[0]['observacao'];
}else{
    $tituloPainel = 'Inserir';
    $operacao = 'inserir';
    $id = 1;
    $nome = '';
    
    
    $status = 4;
}
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    <h3 class="panel-title text-left pull-left"><?= $tituloPainel ?> Patrimônio:</h3>
                    <?php
                        if (isset($_GET['alterar'])) { 
                            if($dados[0]['data_atualizacao']){
                                $data_atualizacao = converteDataHora($dados[0]['data_atualizacao']);
                            }else{
                                $data_atualizacao = "N/D";
                            }
                            echo "<h3 class='panel-title text-center col-md-8'>Atualizado em $data_atualizacao</h3>";
                            echo "<div class=\"panel-title text-right pull-right\"><a  href=\"/api/ajax?class=Patrimonio.php?excluir= $id&token=". $request->token ."\" onclick=\"if (!confirm('Tem certeza que deseja excluir o patrimônio?')) { return false; } else { modalAguarde(); }\"><button class=\"btn btn-xs btn-danger\"><i class=\"fa fa-trash\"></i> Excluir</button></a></div>";
                            
                            // echo "<div class=\"panel-title text-right pull-right\"><a  href=\"class/Patrimonio.php?excluir= $id\" onclick=\"if (!confirm('Tem certeza que deseja excluir o patrimônio?')) { return false; } else { modalAguarde(); }\"><button class=\"btn btn-xs btn-danger\"><i class=\"fa fa-trash\"></i> Excluir</button></a></div>";
                        } 

                    ?>
                </div>
                <form method="post" action="/api/ajax?class=Patrimonio.php" id="patrimonio" style="margin-bottom: 0;">
		        <input type="hidden" name="token" value="<?php echo $request->token ?>">
                    <div class="panel-body" style="padding-bottom: 0;">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>*Valor da Compra:</label>
                                    <input name="valor_compra" id="valor_compra" type="text" class="form-control input-sm money" value="<?=$valor_compra;?>" autocomplete="off" required/>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>*Número do Patrimônio:</label>
                                    <input name="numero_patrimonio" id="numero_patrimonio" type="number" class="form-control input-sm" value="<?=$numero_patrimonio;?>" autocomplete="off" required/>
                                </div>
                            </div>
						</div>

                        <div class="row">                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>*Item:</label>
                                    <select name="id_patrimonio_item" class="form-control input-sm">
                                    <?php
                                        $dados_patrimonio_item = DBRead('', 'tb_patrimonio_item', "WHERE status = 1 ORDER BY descricao ASC");
                                        if ($dados_patrimonio_item) {
                                            foreach ($dados_patrimonio_item as $conteudo_patrimonio_item) {
                                                $selected = $id_patrimonio_item == $conteudo_patrimonio_item['id_patrimonio_item'] ? "selected" : "";
                                                echo "<option value='" . $conteudo_patrimonio_item['id_patrimonio_item'] . "' ".$selected.">" . $conteudo_patrimonio_item['descricao'] . "</option>";
                                            }
                                        }
									?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>*Localização:</label>
                                    <select name="id_patrimonio_localizacao" class="form-control input-sm">
                                    <?php

                                        $sel_patrimonio_localizacao[$id_patrimonio_localizacao] = 'selected';
                                        $dados_patrimonio_localizacao = DBRead('', 'tb_patrimonio_localizacao', "WHERE status = 1 ORDER BY nome ASC");

                                        if ($dados_patrimonio_localizacao) {
                                            foreach ($dados_patrimonio_localizacao as $conteudo_patrimonio_localizacao) {
                                                $selected = $id_patrimonio_localizacao == $conteudo_patrimonio_localizacao['id_patrimonio_localizacao'] ? "selected" : "";
                                                echo "<option value='" . $conteudo_patrimonio_localizacao['id_patrimonio_localizacao'] . "' ".$selected.">" . $conteudo_patrimonio_localizacao['nome'] . "</option>";
                                            }
                                        }
									?>
                                    </select>
                                </div>
                            </div>
						</div>
                        <div class="row">                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>*Responsável:</label>
                                    <select name="id_responsavel" class="form-control input-sm"> >
                                        <option value="">Não possui</option>
                                        <?php
                                            $dados_id_responsavel = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.status = 1 ORDER BY b.nome ASC", "a.id_usuario AS id_responsavel, b.nome");

                                            if ($dados_id_responsavel) {
                                                foreach ($dados_id_responsavel as $conteudo_id_responsavel) {
                                                    $selected = $id_responsavel == $conteudo_id_responsavel['id_responsavel'] ? "selected" : "";
                                                    echo "<option value='" . $conteudo_id_responsavel['id_responsavel'] . "' ".$selected.">" . $conteudo_id_responsavel['nome'] . "</option>";
                                                }
                                            }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>*Fornencedor:</label>
                                    <select name="id_fornecedor" class="form-control input-sm"> >
                                        <option value="">Não possui</option>
                                        <?php
                                            $dados_id_fornecedor = DBRead('', 'tb_pessoa', "WHERE fornecedor = 1 ORDER BY nome ASC", "id_pessoa AS id_fornecedor, nome");

                                            if ($dados_id_fornecedor) {
                                                foreach ($dados_id_fornecedor as $conteudo_id_fornecedor) {
                                                    $selected = $id_fornecedor == $conteudo_id_fornecedor['id_fornecedor'] ? "selected" : "";
                                                    echo "<option value='" . $conteudo_id_fornecedor['id_fornecedor'] . "' ".$selected.">" . $conteudo_id_fornecedor['nome'] . "</option>";
                                                }
                                            }
                                        ?>
                                    </select>
                                </div>
                            </div>
						</div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group" >
                                    <label>*Data da Compra:</label>
                                    <input type="text" class="form-control input-sm date calendar" name="data_compra" value="<?=converteDataHora($data_compra, 'data')?>" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Data da Garantia:</label>
                                    <input type="text" class="form-control input-sm date calendar" name="data_garantia" value="<?=converteDataHora($data_garantia, 'data')?>">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>*Status:</label>
                                    <select name="status" id="status" class="form-control input-sm">
                                        <option value="4" <?php if($status == '4'){ echo 'selected';}?>>Descartado</option>
                                        <option value="5" <?php if($status == '5'){ echo 'selected';}?>>Doado</option>
                                        <option value="2" <?php if($status == '2'){ echo 'selected';}?>>Em Estoque</option>
                                        <option value="1" <?php if($status == '1'){ echo 'selected';}?>>Em Uso</option>
                                        <option value="3" <?php if($status == '4'){ echo 'selected';}?>>Vendido</option>
                                        <option value="7" <?php if($status == '7'){ echo 'selected';}?>>Manutenção</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Número da Nota Fiscal:</label>
                                    <input name="numero_nota_fiscal" id="numero_nota_fiscal" type="number" class="form-control input-sm" value="<?=$numero_nota_fiscal;?>" autocomplete="off"/>
                                </div>
                            </div>
                        </div>  
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Observação:</label>
                                    <textarea rows="6" cols="50" class="form-control ckeditor conteudo" id="observacao" name="observacao"><?= $observacao ?></textarea>
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
<script>

    $(document).on('submit', '#patrimonio', function () {
        modalAguarde();
    });

</script>