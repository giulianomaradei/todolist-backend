<?php
require_once(__DIR__."/../class/System.php");

if (isset($_GET['alterar'])) {
    $tituloPainel = 'Alterar';
    $operacao = 'alterar';
    $id = (int)$_GET['alterar'];
    $dados = DBRead('snep', 'queue_agents', "WHERE uniqueid = $id");
    $nome = $dados[0]['membername'];
    $codigo = $dados[0]['codigo'];
    $filas = $dados[0]['queue_name'];        
	$status = $dados[0]['status'];
    $disabled_codigo = 'disabled';
}else{
    $tituloPainel = 'Inserir';
    $operacao = 'inserir';
    $id = 1;
    $nome = '';
    $codigo = '';
    $filas = '';        
	$status = 1;
    $disabled_codigo = '';
}
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    <h3 class="panel-title text-left pull-left"><?= $tituloPainel ?> atendente:</h3>
                    <?php if (isset($_GET['alterar'])) { echo "<div class=\"panel-title text-right pull-right\"><a  href=\"/api/ajax?class=AtendenteCentral.php?excluir= $id&token=". $request->token ."\" onclick=\"if (!confirm('Tem certeza que deseja excluir o registro?')) { return false; } else { modalAguarde(); }\"><button class=\"btn btn-xs btn-danger\"><i class=\"fa fa-trash\"></i> Excluir</button></a></div>"; } ?>
                </div>
                <form method="post" action="/api/ajax?class=AtendenteCentral.php" id="atendente_form" style="margin-bottom: 0;">
                    <input type="hidden" name="token" value="<?php echo $request->token ?>">
                    <div class="panel-body" style="padding-bottom: 0;">      
                        <div class="row"><div class="col-md-12"><div class="alert alert-warning">Certifique-se de que o atendente não está logado antes de alterar as filas!</div></div></div>                  
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>*Nome:</label>
                                    <input name="nome" autofocus id="nome" type="text" class="form-control input-sm" value="<?= $nome; ?>" autocomplete="off" required>
                                </div>
                            </div>
						</div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>*Codigo:</label>
                                    <select name="codigo" class="form-control" <?= $disabled_codigo; ?>>
                                        <?php 
                                            if(isset($_GET['alterar'])){
                                                echo "<option value='$codigo'>$codigo</option>";
                                            }else{
                                                $cont = 6001;
                                                while($cont <= 6399){
                                                    $dados_codigo = DBRead('snep','queue_agents',"WHERE codigo = '$cont'");
                                                    if(!$dados_codigo){
                                                        echo "<option value='$cont'>$cont</option>";
                                                    }
                                                    $cont++;
                                                }
                                            }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>*Status:</label>
                                    <select class="form-control input-sm" name="status" required>
                                        <option value='1' <?php if($status == 1){echo 'selected';}?>>Ativo</option>
                                        <option value='0' <?php if($status == 0){echo 'selected';}?>>Inativo</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="">Filas:</label>
                                    <select name="filas[]" class="form-control" multiple="multiple" size=15>
                                        <?php 
                                            $dados_filas = DBRead('snep','queues',"ORDER BY name DESC");
                                            if($dados_filas){
                                                foreach ($dados_filas as $conteudo_filas) {
                                                    if(preg_match('/'.$conteudo_filas['id'].'/i', $filas)){
                                                        $selected = 'selected';
                                                    }else{
                                                        $selected = '';
                                                    }
                                                    echo "<option value='".$conteudo_filas['id']."' $selected>".$conteudo_filas['name']."</option>";
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
    $(document).on('submit', '#atendente_form', function () {
        modalAguarde();
    });
</script>