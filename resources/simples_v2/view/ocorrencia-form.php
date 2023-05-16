<?php
require_once(__DIR__."/../class/System.php");
$liderados = busca_liderados($_SESSION['id_usuario']);

if (isset($_GET['alterar'])) {
    $tituloPainel = 'Alterar';
    $operacao = 'alterar';
    $id = (int)$_GET['alterar'];
    $dados = DBRead('', 'tb_ocorrencia', "WHERE id_ocorrencia = $id");
    $id_usuario_ocorrencia = $dados[0]['id_usuario_ocorrencia'];
    $data = converteData($dados[0]['data']);
    $id_ocorrencia_tipo = $dados[0]['id_ocorrencia_tipo'];
    $classificacao = $dados[0]['classificacao'];
    $comentario = $dados[0]['comentario'];
    $id_usuario_registro = $dados[0]['id_usuario_registro'];
    
    $dados_usuario_registro = DBRead('','tb_usuario a',"INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_usuario = '$id_usuario_registro'");
    if(!in_array($id_usuario_ocorrencia,$liderados) && $perfil_usuario != '20' && $perfil_usuario != '12'){
        echo "<div class=\"container-fluid text-center\"><div class=\"alert alert-danger\"><i class=\"fa fa-ban\" aria-hidden=\"true\"></i> Ops! Você não tem permissão de acesso!</div></div>";
        exit;
    }

}else{
    $tituloPainel = 'Inserir';
    $operacao = 'inserir';
    $id = 1;
    $id_usuario_ocorrencia = '';
    $data = '';
    $id_ocorrencia_tipo = '';
    $classificacao = '';
    $comentario = '';
}
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    <div class="row">
                    
                    <h3 class="panel-title text-left pull-left col-md-3"><?= $tituloPainel ?> ocorrência:</h3>
                    <?php 
                    if (isset($_GET['alterar'])) {        
                        echo "<h3 class=\"panel-title text-center col-md-6\">Registrado por: ".$dados_usuario_registro[0]['nome']."</h3> ";
                        echo "<div class=\"panel-title text-right pull-right col-md-3\"><a  href=\"/api/ajax?class=Ocorrencia.php?excluir= $id&token=". $request->token ."\" onclick=\"if (!confirm('Tem certeza que deseja excluir o registro?')) { return false; } else { modalAguarde(); }\"><button class=\"btn btn-xs btn-danger\"><i class=\"fa fa-trash\"></i> Excluir</button></a></div>"; 
                    } 
                    ?>
                    </div>
                </div>
                <form method="post" action="/api/ajax?class=Ocorrencia.php" id="ocorrencia_form" style="margin-bottom: 0;">
		            <input type="hidden" name="token" value="<?php echo $request->token ?>">
                    <div class="panel-body" style="padding-bottom: 0;">
                        <div class="row">                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="id_usuario_ocorrencia">Funcionário:</label>
                                    <select name="id_usuario_ocorrencia" class="form-control input-sm" id='id_usuario_ocorrencia' required>
                                        <option value=""></option>
                                        <?php
                                            if($perfil_usuario != '20' && $perfil_usuario != '12'){
                                                $filtro_permissao = " AND a.id_usuario IN ('".join("','", $liderados)."')";
                                            }else{
                                                $filtro_permissao = '';
                                            }
                                            
                                            $dados_usuarios = DBRead('','tb_usuario a',"INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE (a.status = 1 OR a.status = 0) $filtro_permissao ORDER BY b.nome ASC","a.id_usuario, b.nome");
                                            if($dados_usuarios){
                                                foreach ($dados_usuarios as $conteudo_usuarios) {
                                                    $selected = $id_usuario_ocorrencia == $conteudo_usuarios['id_usuario'] ? "selected" : "";
                                                    echo "<option value='".$conteudo_usuarios['id_usuario']."' ".$selected.">".$conteudo_usuarios['nome']."</option>";
                                                }
                                            }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>*Data:</label>
                                    <input type="text" class="form-control date calendar" name="data" value="<?=$data?>" required>
                                </div>
                            </div>             
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="id_ocorrencia_tipo">*Tipo:</label>
                                    <select name="id_ocorrencia_tipo" class="form-control input-sm" id='id_ocorrencia_tipo' required>
                                        <option value=""></option>
                                        <?php
                                            $dados_ocorrencia_tipo = DBRead('','tb_ocorrencia_tipo',"WHERE status = 1 ORDER BY descricao ASC");
                                            if($dados_ocorrencia_tipo){
                                                foreach ($dados_ocorrencia_tipo as $conteudo_ocorrencia_tipo) {
                                                    $selected = $id_ocorrencia_tipo == $conteudo_ocorrencia_tipo['id_ocorrencia_tipo'] ? "selected" : "";
                                                    echo "<option value='".$conteudo_ocorrencia_tipo['id_ocorrencia_tipo']."' ".$selected.">".$conteudo_ocorrencia_tipo['descricao']."</option>";
                                                }
                                            }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="classificacao">*Classificação:</label>
                                    <select name="classificacao" class="form-control input-sm" id="classificacao" required>
                                        <option value=""></option>
                                        <option value="1" <?php if($classificacao == '1'){ echo 'selected';}?>>Positivo</option>
                                        <option value="2" <?php if($classificacao == '2'){ echo 'selected';}?>>Neutro</option>
                                        <option value="3" <?php if($classificacao == '3'){ echo 'selected';}?>>Negativo</option>
                                    </select>
                                </div>
                            </div>             
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>*Comentário:</label>
                                    <textarea class="form-control " name="comentario" style="resize: vertical; height: 150px;" required><?=$comentario;?></textarea>
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
    $(document).on('submit', '#ocorrencia_form', function () {
        modalAguarde();
    });
</script>