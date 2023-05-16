<?php
require_once(__DIR__."/../class/System.php");


$id_pesquisa = (!empty($_GET['id_pesquisa'])) ? $_GET['id_pesquisa'] : '';
$id_empresa = DBRead('', 'tb_pessoa a', "INNER JOIN tb_contrato_plano_pessoa b ON a.id_pessoa = b.id_pessoa INNER JOIN tb_pesquisa c ON b.id_contrato_plano_pessoa = c.id_contrato_plano_pessoa WHERE c.id_pesquisa = '".$id_pesquisa."'");
$empresa = $id_empresa[0]['nome'];

$dados_pesquisa = DBRead('', 'tb_pesquisa', "WHERE id_pesquisa = $id_pesquisa");

$dado1 = $dados_pesquisa[0]['dado1'];
$dado2 = $dados_pesquisa[0]['dado2'];
$dado3 = $dados_pesquisa[0]['dado3'];

if (isset($_GET['alterar'])) {
    $tituloPainel = 'Alterar';
    $operacao = 'alterar';
    $id = (int)$_GET['alterar'];
    $dados = DBRead('', 'tb_contatos_pesquisa', "WHERE id_contatos_pesquisa = $id");
    $nome = $dados[0]['nome'];
    $telefone = $dados[0]['telefone'];
    $observacao = $dados[0]['observacao'];
	$inclusao_contato = $dados[0]['inclusao_contato'];

	$label1 = $dados[0]['label1'];
	$label2 = $dados[0]['label2'];
	$label3 = $dados[0]['label3'];
	$dado_contato1 = $dados[0]['dado1'];
	$dado_contato2 = $dados[0]['dado2'];
	$dado_contato3 = $dados[0]['dado3'];
	
}else{
    $tituloPainel = 'Inserir';
    $operacao = 'inserir';
    $id = 1;
    $nome = '';
    $telefone = '';
	$observacao = '';

	$label1 = '';
	$label2 = '';
	$label3 = '';
	$dado_contato1 = '';
	$dado_contato2 = '';
	$dado_contato3 = '';
}
?>
<script src="inc/ckeditor/ckeditor.js"></script>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-10  col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading clearfix">

                	<?php
                		$servico = DBRead('', 'tb_contrato_plano_pessoa a', "INNER JOIN tb_plano b ON a.id_plano = b.id_plano WHERE a.id_pessoa = '".$id_empresa[0]['id_pessoa']."' AND cod_servico = 'call_ativo'", "b.nome AS nome_plano, b.cod_servico");
                	?>

                    <h3 class="panel-title text-left pull-left"><?=$empresa?> - <?= getNomeServico($servico[0]['cod_servico']) . " - " . $servico[0]['nome_plano'] ?></h3>

					<div class="panel-title text-right pull-right">
                        <a href="/api/iframe?token=<?php echo $request->token ?>&view=importar-csv-pesquisa-contato-form&id_pesquisa=<?=$id_pesquisa?>"><button class="btn btn-xs btn-warning"><i class="fa fa-plus"></i> Importar CSV</button></a>
                    </div>
                   
                </div>

						<div class="container-fluid">
							<div class="panel-body" style="padding-bottom: 0;">
						    <div class="row">
						        <div class="col-md-12">
						            <div class="panel panel-default">
						                <div class="panel-heading clearfix">
						                    <h3 class="panel-title text-left pull-left">Inserir Contato:</h3>
						                </div>
						                <form method="post" action="/api/ajax?class=PesquisaContato.php" id="gerenciar_pesquisa_contato" style="margin-bottom: 0;">
											<input type="hidden" name="token" value="<?php echo $request->token ?>">
						                    <div class="panel-body" style="padding-bottom: 0;">
						                        <div class="row">
						                            <div class="col-md-6">
						                                <div class="form-group">
						                                    <label>*Nome:</label>
						                                    <input name="nome" type="text" class="form-control input-sm nome" id='nome' value="<?= $nome; ?>" required>
						                                </div>
						                            </div>

						                            <div class="col-md-6">
						                                <div class="form-group">
						                                    <label>*Telefone:</label>
						                                    <input name="telefone" type="text" class="form-control input-sm phone" id = 'telefone' value="<?= $telefone; ?>" required>
						                                </div>
						                            </div>

						                            <input type="hidden" name="id_pesquisa" id="id_pesquisa" value="<?=$id_pesquisa?>">

						                        </div>
						                                                 
						                        <div class="row">

													<?php
													if($dado1){
														echo "<div class='col-md-4'>";
															echo "<div class='form-group'>";
																echo "<label>".$dado1.":</label>";
																echo "<input name='dado_contato1' value='$dado_contato1' type='text' class='form-control input-sm' />";
															echo "</div>";
														echo "</div>";
													}
													if($dado2){
														echo "<div class='col-md-4'>";
															echo "<div class='form-group'>";
																echo "<label>".$dado2.":</label>";
																echo "<input name='dado_contato2' value='$dado_contato1' class='form-control input-sm' />";
															echo "</div>";
														echo "</div>";
													}
													if($dado3){
														echo "<div class='col-md-4'>";
															echo "<div class='form-group'>";
																echo "<label>".$dado3.":</label>";
																echo "<input name='dado_contato3' value='$dado_contato3' class='form-control input-sm' />";
															echo "</div>";
														echo "</div>";
													}
													?>

						                            

						                        </div>
												
						                    </div>
						                    <div class="panel-footer">
						                        <div class="row">
						                            <div class="col-md-12" style="text-align: center">
						                                <input type="hidden" id="operacao" value=<?= $id; ?> name="<?= $operacao; ?>"/>
						                                <button class="btn btn-primary" name="salvar" id="ok" type="submit"><i class="fa fa-floppy-o"></i> Gravar</button>
						                            </div>
						                        </div>
						                    </div>
						                </form>
						            </div>
						        </div>
						    </div>

						</div> 
						<div class="container-fluid">
						    <div class="row">
						        <div class="col-md-12">
						            <div class="panel panel-default">
						                <div class="panel-heading clearfix">
						                    <h3 class="panel-title text-left pull-left" style="margin-top: 2px;">Contatos:</h3>
						                    <div class="panel-title text-right pull-right">
												<?php
													$dados = DBRead('', 'tb_contatos_pesquisa', "WHERE id_pesquisa = '".$id_pesquisa."' AND status_pesquisa = 0 AND qtd_tentativas_cliente = 0");
													if($dados){
														?>
														<a href=" /api/ajax?class=PesquisaContato.php?excluir_contatos=<?=$id_pesquisa?>&token=<?=$request->token?>" title="Excluir" onclick="if (!confirm('Tem certeza que deseja excluir todos os contatos?')) { return false; } else { modalAguarde(); }"><button class="btn btn-xs btn-danger"><i class="fa fa-trash"></i> Excluir Todos os Contatos</button></a>
														<?php
													}
												?>
											</div>
						                </div>
						                <div class="panel-body">
						                    <div class="row">
						                        <div class="col-md-12">
						                            <div class="form-group has-feedback">
						                                <label class="control-label sr-only">Hidden label</label>
						                                <input class="form-control" type="text" name="contato" id="contato" onKeyUp="call_busca_ajax();" placeholder="Informe o contato" autocomplete="off" autofocus>
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
            </div>
        </div>
    </div>
</div>
</div>    

<script>
    function call_busca_ajax(pagina){
        var inicia_busca = 1;
        var contato = $('#contato').val();
        var id_pesquisa = $('#id_pesquisa').val();
        if (contato.length < inicia_busca && contato.length >=1){
            return false;
        }
        if(pagina === undefined){
            pagina = 1;
        }
        var parametros = {
            'contato': contato,
            'id_pesquisa': id_pesquisa,
            'pagina': pagina
        };
        busca_ajax('<?= $request->token ?>' , 'PesquisaContatoBusca', 'resultado_busca', parametros);
    }

    $(document).on('click', '.troca_pag', function(){
        call_busca_ajax($(this).attr('atr-pagina'));
    });

    call_busca_ajax();

    $(document).on('submit', '#gerenciar_pesquisa_contato', function(){
        var nome = $('#nome').val();
        var telefone = $('#telefone').val();
        var id_pesquisa = $('#id_pesquisa').val();
     
        if(!nome){
            alert("Deve-se descrever um nome!");
            return false;
        }
        if(!telefone){
            alert("Deve-se inserir um telefone válido!");
            return false;
        }
        if(!id_pesquisa){
        	alert("Erro ao inserir item!");
            return false;
        }
        modalAguarde();
    });

    $('#alterar_contato').on('click', function(){

    	var botao_alterar = $(this).val();
    	alert(botao_alterar);
    });

    $('#excluir_contato').on('click', function(){
    	var botao_excluir = $(this);
    	alert(botao_excluir);
    });
</script>