<?php
require_once(__DIR__."/../class/System.php");

    $operacao = 'alterar';
    $id = (int)$_GET['alterar'];

    $dados = DBRead('', 'tb_contrato_plano_pessoa a ', "INNER JOIN tb_plano b ON a.id_plano = b.id_plano WHERE a.id_contrato_plano_pessoa = $id", "a.*,b.*, a.status AS status_contrato");

    $id_responsavel = $dados[0]['id_responsavel'];
    $id_responsavel_tecnico = $dados[0]['id_responsavel_tecnico'];

    $dados_pessoa = DBRead('', 'tb_pessoa', " WHERE id_pessoa = '".$dados[0]['id_pessoa']."' ");
    $nome_pessoa = $dados_pessoa[0]['nome'];

?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <div class="panel panel-default">
                <div class="panel-heading clearfix"> 
                    <h3 class="panel-title text-left pull-left">Alterar Responsáveis de <?= $nome_pessoa ?>:</h3>
                </div>
                <form method="post" action="/api/ajax?class=ResponsavelContrato.php" id="responsavel_contrato_form" style="margin-bottom: 0;">
		            <input type="hidden" name="token" value="<?php echo $request->token ?>">
                    <div class="panel-body" style="padding-bottom: 0;">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>*Responsável pelo Relacionamento:</label>
                                    <select class="form-control input-sm" id="id_responsavel" name="id_responsavel">
                                        <?php
                                            $dados_responsavel = DBRead('', 'tb_perfil_sistema a', "INNER JOIN tb_usuario b ON a.id_perfil_sistema = b.id_perfil_sistema INNER JOIN tb_pessoa c ON b.id_pessoa = c.id_pessoa WHERE a.id_perfil_sistema = 11 AND b.status = 1 ORDER BY c.nome ASC","b.id_usuario, c.nome");
                                            
                                            if ($dados_responsavel) {
                                                foreach ($dados_responsavel as $conteudo_responsavel) {
                                                    $selected = $id_responsavel == $conteudo_responsavel['id_usuario'] ? "selected" : "";
                                                    echo "<option value='".$conteudo_responsavel['id_usuario']."' ".$selected.">".$conteudo_responsavel['nome']."</option>";
                                                }
                                            }
                                        ?>
                                    </select>   
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>*Responsável Técnico:</label>
                                    <select class="form-control input-sm" id="id_responsavel_tecnico" name="id_responsavel_tecnico">
                                        <?php
                                            $dados_responsavel_tecnico = DBRead('', 'tb_perfil_sistema a', "INNER JOIN tb_usuario b ON a.id_perfil_sistema = b.id_perfil_sistema INNER JOIN tb_pessoa c ON b.id_pessoa = c.id_pessoa WHERE a.id_perfil_sistema = 4 AND b.status = 1 ORDER BY c.nome ASC","b.id_usuario, c.nome");
                                            
                                            if ($dados_responsavel_tecnico) {
                                                $sel_responsavel_tecnico[$id_responsavel_tecnico] = 'selected';
                                                foreach ($dados_responsavel_tecnico as $conteudo_responsavel_tecnico) {
                                                    $selected = $id_responsavel_tecnico == $conteudo_responsavel_tecnico['id_usuario'] ? "selected" : "";
                                                    echo "<option value='".$conteudo_responsavel_tecnico['id_usuario']."' ".$selected.">".$conteudo_responsavel_tecnico['nome']."</option>";
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
<script>
    $(document).on('submit', '#responsavel_contrato_form', function () {
        modalAguarde();
    });
</script>