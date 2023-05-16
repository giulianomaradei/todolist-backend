<?php
require_once(__DIR__."/../class/System.php");

if (isset($_GET['alterar'])) {
    $tituloPainel = 'Alterar';
    $operacao = 'alterar';
    $id = (int)$_GET['alterar'];

    $operadores = DBRead('', 'tb_grupo_atendimento_chat_operador', 'WHERE id_grupo_atendimento_chat = '.$id);
}else{
    $tituloPainel = 'Inserir';
    $operacao = 'inserir';
    $id = 'a';
    $nome = '';
}

?>
<style>
.select2{
    width: 100% !important;
}
</style>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    <h3 class="panel-title text-left pull-left"><?= $tituloPainel ?> grupo de atendimento chat - Vincular Operadores:</h3>
                </div>
                <form method="post" action="/api/ajax?class=GrupoAtendimentoChatOperador.php" id="" style="margin-bottom: 0;">
		            <input type="hidden" name="token" value="<?php echo $request->token ?>">
                    <div class="panel-body" style="padding-bottom: 0;">
                        <input type="hidden" name="grupo" value="<?= $id ?>">
                        <div class='row'>
                            <div class='col-md-12'>
                                <div class="form-group">
                                    <div class='table-responsive' style="max-height: 520px; overflow-y:auto;">

                                        <label>*Operador(es):</label>
                                        <select class="js-example-basic-multiple" id="operadores" name="operadores[]" multiple="multiple">
                                            <?php
                                            //Busca do KEndy
                                            // $dados_operadores = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE (a.id_perfil_sistema = 3 OR a.id_perfil_sistema = 15 OR a.id_perfil_sistema = 28) AND a.status = 1 ORDER BY b.nome");
                                           
                                            //Busca do RIcalde                                           
                                            $dados_operadores = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa INNER JOIN tb_horarios_escala c ON a.id_usuario = c.id_usuario WHERE (c.data_inicial = '2022-06-01' OR c.data_inicial = '2022-07-01') AND c.chat = 1 AND a.status = 1 ORDER BY b.nome");

                                            if($dados_operadores){
                                                foreach($dados_operadores as $conteudo){
                                                    $id_usuario = $conteudo['id_usuario'];
                                                    $nome = $conteudo['nome'];
    
                                                    echo "<option value='$id_usuario'>$nome</option>";
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>                                    
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
    select2Perfis = $('.js-example-basic-multiple').select2();

    function inserePerfis(){
        //verifica quais estão marcados
        dadosCampoPerfisJson = <?php echo json_encode($operadores) ?>;
        dadosCampoPerfisArray = [];
        dadosCampoPerfisJson.forEach(function(i){
            dadosCampoPerfisArray.push(i.id_usuario);
        });
        select2Perfis.val(dadosCampoPerfisArray).trigger("change");
    }

    <?php
        if($operacao == 'alterar'){
            echo "inserePerfis();";
        }
    ?>
</script>