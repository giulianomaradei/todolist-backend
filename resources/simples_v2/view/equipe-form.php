<?php
require_once(__DIR__."/../class/System.php");

	$tituloPainel = 'Alterar';
	$operacao = 'alterar';
	$id = (int) $_GET['alterar'];
	$dados = DBRead('', 'tb_usuario', "WHERE id_usuario = $id");
	$id_pessoa = $dados[0]['id_pessoa'];
	$dados_pessoa = DBRead('', 'tb_pessoa', "WHERE id_pessoa = '$id_pessoa'");
	$nome_pessoa = $dados_pessoa[0]['nome'];
    $lider = $dados[0]['lider_direto'];
?>
<div class="row">
    <div class="col-md-6 col-md-offset-3">
        <div class="panel panel-default">
            <div class="panel-heading clearfix">
                <h3 class="panel-title text-left pull-left"><?=$tituloPainel?> Líder Direto:</h3>
            </div>
            <form method="post" action="/api/ajax?class=Usuario.php" id="usuario_form" style="margin-bottom: 0;">
                <input type="hidden" name="token" value="<?php echo $request->token ?>">

                <div class="panel-body" style="padding-bottom: 0;">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>*Pessoa (funcionário):</label>
                                    <input class="form-control input-sm" id="busca_pessoa" type="text" name="busca_pessoa"  value="<?=$nome_pessoa;?>" placeholder="Informe o nome ou CPF/CNPJ..." autocomplete="off" readonly required>
                                    
                                <input type="hidden" name="id" id="id" value="<?=$id;?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Líder Direto:</label>
                                <select class="form-control input-sm" name="lider">
                                    <option value = ''>Nenhum</option>
                                        <?php
                                        $dados = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.status = 1 ORDER BY nome ASC");
                                        if ($dados) {
                                            foreach ($dados as $conteudo) {
                                                $idLider = $conteudo['id_usuario'];
                                                $nomeSelect = $conteudo['nome'];
                                                $selected = $lider == $idLider ? "selected" : "";

                                                echo "<option value='$idLider'".$selected.">$nomeSelect</option>";
                                            }
                                        }
                                        ?>
                                </select>
                            </div>
                        </div>   
                    </div>
                </div>
                <div class="panel-footer">
                    <div class="row">
                        <div class="col-md-12" style="text-align: center">
                            <input type="hidden" id="equipe" value="<?=$id;?>" name="equipe"/>
                            <button class="btn btn-primary" name="salvar" id="ok" type="submit"><i class="fa fa-floppy-o"></i> Salvar</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>