<?php
require_once(__DIR__."/../class/System.php");
$dados_filas = DBRead('snep','queues',"ORDER BY name DESC");
if (isset($_GET['alterar'])) {
    $tituloPainel = 'Alterar';
    $operacao = 'alterar';
    $id = (int)$_GET['alterar'];
    $dados = DBRead('snep', 'empresas', "WHERE id = $id");
    $nome = $dados[0]['nome'];        
	$status = $dados[0]['status'];
	$tipo = $dados[0]['tipo'];
	$fila1 = $dados[0]['fila1'];
	$fila2 = $dados[0]['fila2'];
	$tempo_fila1 = $dados[0]['tempo_fila1'];
	$tempo_fila2 = $dados[0]['tempo_fila2'];
	$controle_automatico_fila = $dados[0]['controle_automatico_fila'];
	$tipo_fila_controle_automatico = $dados[0]['tipo_fila_controle_automatico'];
	$pesquisa = $dados[0]['pesquisa'];
	$aceita_ligacao = $dados[0]['aceita_ligacao'];
    $display_prefixo = '';
}else{
    $tituloPainel = 'Inserir';
    $operacao = 'inserir';
    $id = 1;
    $nome = '';    
	$status = 1;
    $tipo = 2;
    $fila1 = '';
	$fila2 = '';
	$tempo_fila1 = 0;
	$tempo_fila2 = 0;
	$controle_automatico_fila = 0;
	$tipo_fila_controle_automatico = 'interna';
	$pesquisa = 1;
	$aceita_ligacao = 1;
    $display_prefixo = 'style="display:none;"';
}
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    <h3 class="panel-title text-left pull-left"><?= $tituloPainel ?> prefixo:</h3>
                    <?php if (isset($_GET['alterar'])) { echo "<div class=\"panel-title text-right pull-right\"><a  href=\"/api/ajax?class=PrefixoCentral.php?excluir= $id&token=". $request->token ."\" onclick=\"if (!confirm('Tem certeza que deseja excluir o registro?')) { return false; } else { modalAguarde(); }\"><button class=\"btn btn-xs btn-danger\"><i class=\"fa fa-trash\"></i> Excluir</button></a></div>"; } ?>
                </div>
                <form method="post" action="/api/ajax?class=PrefixoCentral.php" id="prefixo_form" style="margin-bottom: 0;">
		            <input type="hidden" name="token" value="<?php echo $request->token ?>">
                    <div class="panel-body" style="padding-bottom: 0;">
                        <div class="row"<?= $display_prefixo; ?>>
                            <div class="col-md-12">
                                <div class="form-group">


                                <?php $dados_controle_fila = DBRead('', 'tb_parametros', "WHERE id_asterisk = $id");
                                      if($dados_controle_fila[0]['controle_fila'] == '0'){     ?>
                                        <div class="container-fluid text-center"><div class='alert alert-warning alert-dismissible' role='alert' style='text-align: center'><strong> EMPRESA SEM CONTROLE DE FILAS </strong></div></div>
                                <?php  }  ?>
                                      

                                    <label>Prefixo:</label>
                                    <input name="prefixo" type="text" class="form-control input-sm" value="<?= $id; ?>-" autocomplete="off" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label>*Nome:</label>
                                    <input name="nome" autofocus id="nome" type="text" class="form-control input-sm" value="<?= $nome; ?>" autocomplete="off" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>*Status:</label>
                                    <select class="form-control input-sm" name="status" required>
                                        <option value='1' <?php if ($status == 1) {echo 'selected';}?>>Ativo</option>
                                        <option value='0' <?php if ($status == 0) {echo 'selected';}?>>Inativo</option>
                                    </select>
                                </div>
                            </div>
						</div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>*Tipo:</label>
                                    <select class="form-control input-sm" name="tipo" required>
                                        <option value='1' <?php if ($tipo == 1) {echo 'selected';}?>>Belluno</option>
                                        <option value='2' <?php if ($tipo == 2) {echo 'selected';}?>>Cliente</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>*Aceita Ligações:</label>
                                    <select class="form-control input-sm" name="aceita_ligacao" required>
                                        <option value='1' <?php if ($aceita_ligacao == 1) {echo 'selected';}?>>Sim</option>
                                        <option value='0' <?php if ($aceita_ligacao == 0) {echo 'selected';}?>>Não</option>
                                    </select>
                                </div>
                            </div> 
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>*Pesquisa:</label>
                                    <select class="form-control input-sm" name="pesquisa" required>
                                        <option value='1' <?php if ($pesquisa == 1) {echo 'selected';}?>>Sim</option>
                                        <option value='0' <?php if ($pesquisa == 0) {echo 'selected';}?>>Não</option>
                                    </select>
                                </div>
                            </div>                           
                        </div>                                
                        <div class="row">

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>*Controle Automático de Filas:</label>
                                    <select class="form-control input-sm" name="controle_automatico_fila" required>

                                    <?php    if($dados_controle_fila[0]['controle_fila'] == '0'){     ?>
                                                <option value='0' selected disabled >Não</option>
                                    <?php   } else { ?>
                                                <option value='1' <?php if ($controle_automatico_fila == 1) {echo 'selected';}?>>Sim</option>
                                                <option value='0' <?php if ($controle_automatico_fila == 0) {echo 'selected';}?>>Não</option>
                                    <?php } ?>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>*Tipo Fila Controle Automático:</label>
                                    <select class="form-control input-sm" name="tipo_fila_controle_automatico" required>
                                        <option value='interna' <?php if ($tipo_fila_controle_automatico == 'interna') {echo 'selected';}?>>Interna</option>
                                        <option value='experiencia' <?php if ($tipo_fila_controle_automatico == 'experiencia') {echo 'selected';}?>>Experiência (EXP)</option>
                                        <option value='externa' <?php if ($tipo_fila_controle_automatico == 'externa') {echo 'selected';}?>>Externa (EXT)</option>
                                    </select>
                                </div>
                            </div>
                        </div>  
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">*Fila 1:</label>
                                    <select name="fila1" class="form-control input-sm" required>
                                        <option value=""></option>
                                        <?php
                                            if($dados_filas){
                                                foreach ($dados_filas as $conteudo_filas) {
                                                    $selected = $fila1 == $conteudo_filas['name'] ? "selected" : "";   
                                                    echo "<option value='".$conteudo_filas['name']."' ".$selected.">".$conteudo_filas['name']."</option>";
                                                }
                                            }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>*Tempo na Fila 1 (segundos):</label>
                                    <input name="tempo_fila1" type="number" class="form-control input-sm number_int" value="<?=$tempo_fila1;?>" autocomplete="off" required />
                                </div>
                            </div>
                        </div>      
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Fila 2:</label>
                                    <select name="fila2" class="form-control input-sm">
                                        <option value=""></option>
                                        <?php
                                            if($dados_filas){
                                                foreach ($dados_filas as $conteudo_filas) {
                                                    $selected = $fila2 == $conteudo_filas['name'] ? "selected" : "";   
                                                    echo "<option value='".$conteudo_filas['name']."' ".$selected.">".$conteudo_filas['name']."</option>";
                                                }
                                            }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Tempo na Fila 2 (segundos):</label>
                                    <input name="tempo_fila2" type="number" class="form-control input-sm number_int" value="<?=$tempo_fila2;?>" autocomplete="off" />
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
    $(document).on('submit', '#prefixo_form', function () {
        modalAguarde();
    });

    $('[name=controle_automatico_fila]').on('change', function(){
        if($(this).val() == 1){
            alert('Ao habilitar este campo as filas 1 e 2 irão mudar diariamente (às 02:00 da manhã) conforme a porcentagem diária utilizada pelo cliente!');
        }
    });
</script>