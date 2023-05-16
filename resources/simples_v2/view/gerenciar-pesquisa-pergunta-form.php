<?php
require_once(__DIR__."/../class/System.php");

if(isset($_GET['alterar'])){

    $tituloPainel = 'Alterar';
    $operacao = 'alterar';
    $id = (int)$_GET['alterar'];
    $id_pergunta = (int)$_GET['alterar'];

    $dados = DBRead('', 'tb_pergunta_pesquisa', "WHERE id_pergunta_pesquisa = $id");

    $descricao = $dados[0]['descricao'];
    $posicao = $dados[0]['posicao'];
    $observacao = $dados[0]['observacao'];
    $id_pesquisa = $dados[0]['id_pesquisa'];

}else{
    $tituloPainel = 'Inserir';
    $operacao = 'inserir';
    $descricao = '';
    $posicao = '';
    $observacao = '';
    $id_pesquisa = $_GET['id_pesquisa'];
    $id = 'inserir';
}
?>
<style>
    #pergunta-lista{
        font-weight: bolder;
        color: #fff;
        background-color: #595959;
        margin-right: 100px;
    }
</style>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    <h3 class="panel-title text-left pull-left"><?= $tituloPainel ?> pergunta:</h3>
                    <?php if (isset($_GET['alterar'])) { echo "<div class=\"panel-title text-right pull-right\"><a  href=\"/api/ajax?class=PesquisaPergunta.php?excluir=$id&id_pesquisa=$id_pesquisa&token=". $request->token ."\" onclick=\"if (!confirm('Tem certeza que deseja excluir o registro?')) { return false; } else { modalAguarde(); }\"><button class=\"btn btn-xs btn-danger\"><i class=\"fa fa-trash\"></i> Excluir</button></a></div>"; } ?>
                </div>
                <form method="post" action="/api/ajax?class=PesquisaPergunta.php" id="pesquisa_form" style="margin-bottom: 0;">
		            <input type="hidden" name="token" value="<?php echo $request->token ?>">
                    <div class="panel-body" style="padding-bottom: 0;">
                        <input type="hidden" name="id_pesquisa" value="<?= $id_pesquisa ?>">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>*Descrição:</label>
                                    <textarea name="descricao" id="descricao" autofocus class="form-control" required><?= $descricao; ?></textarea>
                                </div>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>*Posição:</label>
                                    <input name="posicao" id="posicao" type="number" min="1" class="form-control input-sm" value="<?= $posicao; ?>" autocomplete="off" required>
                                </div>
                            </div>

                            <?php
                                if($tituloPainel == "Inserir"){
                                    $ultima_posicao = DBRead('', 'tb_pergunta_pesquisa', "WHERE id_pesquisa = '".$id_pesquisa."' ORDER BY posicao DESC LIMIT 1");
                                    $ultima_posicao = $ultima_posicao[0]['posicao'];

                                    ?>
                                    <input type="hidden" id='ultima_posicao' value="<?=$ultima_posicao?>">
                                    <script>
                                        var proxima_posicao = Number($('#ultima_posicao').val()) + 1;
                                        $('#posicao').val(proxima_posicao);
                                    </script>
                                    <?php
                                }
                            ?>
                            
                            
                            <div class="col-md-6">
                                <label>*Tipo de resposta:</label>
                                <select class="form-control input-sm" name="id_tipo_resposta_pesquisa" id="id_tipo_resposta_pesquisa">
                                    <?php

                                        $tipos_selecionado = DBRead('', 'tb_pergunta_pesquisa', "WHERE id_pergunta_pesquisa='".$id."'");
                                        $id_tipo_resposta_pesquisa = $tipos_selecionado[0]['id_tipo_resposta_pesquisa'];
                                        $tipos = DBRead('', 'tb_tipo_resposta_pesquisa');

                                        foreach($tipos as $tipo){
                                            $selected = $id_tipo_resposta_pesquisa == $tipo['id_tipo_resposta_pesquisa'] ? "selected" : "";
                                            echo "<option value='".$tipo['id_tipo_resposta_pesquisa']."' ".$selected." >".$tipo['nome']."</option>";
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Observação</label>
                                    <textarea class="form-control" id="observacao" name="observacao" rows="3"><?=$observacao?></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row" id="escala_linear">
                             <div class="col-md-12">
                                    <div class="form-group">
                                        <div class="row">
                                            <?php
                                                
                                                if($operacao == "alterar"){

                                                    $dados_resposta = DBRead('', 'tb_resposta_pesquisa', "WHERE id_pergunta_pesquisa = '$id'");

                                                    $escala_de = $dados_resposta[0]['posicao'];
                                                    $invertido = array_reverse($dados_resposta);
                                                    $escala_ate = $invertido[0]['posicao'];

                                                }
                                            ?>

                                            <div class="col-md-6">
                                                <label for='escala_de'>Inicio</label>
                                                <select class="form-control" name='escala_de' id='escala_de'>
                                                    <option value = '0'<?php if($escala_de == '0'){ echo 'selected';}?>>0</option>
                                                    <option value = '1'<?php if($escala_de == '1'){ echo 'selected';}?>>1</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label for='escala_ate'>Fim</label>

                                                <select class="form-control" name='escala_ate' id='escala_ate'>
                                                    <option value = '2'<?php if($escala_ate == '2'){ echo 'selected';}?>>2</option>
                                                    <option value = '3'<?php if($escala_ate == '3'){ echo 'selected';}?>>3</option>
                                                    <option value = '4'<?php if($escala_ate == '4'){ echo 'selected';}?>>4</option>
                                                    <option value = '5'<?php if($escala_ate == '5'){ echo 'selected';}?>>5</option>
                                                    <option value = '6'<?php if($escala_ate == '6'){ echo 'selected';}?>>6</option>
                                                    <option value = '7'<?php if($escala_ate == '7'){ echo 'selected';}?>>7</option>
                                                    <option value = '8'<?php if($escala_ate == '8'){ echo 'selected';}?>>8</option>
                                                    <option value = '9'<?php if($escala_ate == '9'){ echo 'selected';}?>>9</option>
                                                    <option value = '10'<?php if($escala_ate == '10'){ echo 'selected';}?>>10</option>
                                                   
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                             </div>
                        </div>
                        <div class="row" id="dados_resposta">
                            <div class="col-md-12">
                                
                                <div class="table-responsive">
                                    <table class="table table-bordered" style='font-size: 14px;'>
                                        <thead>
                                            <tr>
                                                <th class="col-md-8">*Descrição da resposta</th>
                                                <th class="col-md-3">*Posição da resposta</th>
                                                <th class="col-md-1">Ação</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            if($operacao == "alterar"){
                                                $dados_resposta = DBRead('', 'tb_resposta_pesquisa', "WHERE id_pergunta_pesquisa = '$id'");
                                                
                                                foreach($dados_resposta as $dado){
                                                    echo "<tr class='linha_resposta'>";
                                                        echo "<input type='hidden' name='id_resposta_pesquisa[]' value='".$dado['id_resposta_pesquisa']."' />";
                                                        echo "<td><textarea name='descricao_resposta[]' class='form-control descricao_resposta'>".$dado['descricao']."</textarea></td>";
                                                        echo "<td><input type='number' min='0' name='posicao_resposta[]' class='form-control posicao_resposta' value='".$dado['posicao']."' /></td>";
                                                        echo "<td><button type='button' class='center-block btn btn-danger btn-sm removeLinha' role='button'><i class='fa fa-trash-o' aria-hidden='true'></i></button></td>";
                                                    echo "</tr>";
                                                }
                                            }
                                            ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td></td>
                                                <td></td>
                                                <td><button type="button" class='center-block btn btn-warning btn-sm' id='adiciona-resposta' role='button'><i class='fa fa-plus' aria-hidden='true'></i></button></td>
                                            </tr>
                                        </tfoot>
                                    </table>
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

    if($('#id_tipo_resposta_pesquisa').val() == 2 || $('#id_tipo_resposta_pesquisa').val() == 3){
        $('#dados_resposta').show();
        $('#escala_linear').hide();
    }else if($('#id_tipo_resposta_pesquisa').val() == 1){
        $('#dados_resposta').hide();
        $('#escala_linear').hide();
    }else if($('#id_tipo_resposta_pesquisa').val() == 4){
        $('#dados_resposta').hide();
        $('#escala_linear').show();
    }

    $('#id_tipo_resposta_pesquisa').on('change', function(){

        if($(this).val() == '2' || $(this).val() == '3'){
            $('#dados_resposta').fadeIn();
            $('#escala_linear').fadeOut();
        }else if($(this).val() == '1'){
            $('#dados_resposta').fadeOut();
            $('#escala_linear').fadeOut();
        }else if($(this).val() == '4'){
            $('#dados_resposta').fadeOut();
            $('#escala_linear').fadeIn();
        }
    });

    $("#adiciona-resposta").on('click', function(){

            var tamanho = 0;

        $('.posicao_resposta').each(function(){
            
            if(parseInt(tamanho) < $(this).val()){
                tamanho = $(this).val();
            }
        }); 

        if(!$("tr.linha_resposta").length){
            $("tbody").append("<tr class='linha_resposta'><td><textarea class='form-control descricao_resposta' name='descricao_resposta[]'></textarea></td><td><input class='form-control input-sm posicao_resposta' name='posicao_resposta[]' type='number' min='1' value='1' /></td><td><button class='center-block btn btn-danger btn-sm removeLinha' role='button'><i class='fa fa-trash-o' aria-hidden='true'></i></button></td></tr>");   
        }else{
            var tamanho = parseInt(tamanho)+1;
            $("tbody").append("<tr class='linha_resposta'><td><textarea class='form-control descricao_resposta' name='descricao_resposta[]'></textarea></td><td><input class='form-control input-sm posicao_resposta' name='posicao_resposta[]' type='number' min='1' value='"+tamanho+"'/></td><td><button class='center-block btn btn-danger btn-sm removeLinha' role='button'><i class='fa fa-trash-o' aria-hidden='true'></i></button></td></tr>");  
        }
    });

    $(document).on('click', '.removeLinha', function(){
        if(confirm('Deseja excluir a resposta?')){
            $(this).parent().parent().remove();
        }
        return false;
    });

    $(document).on('submit', '#pesquisa_form', function(){

        var naoSalva = 0;

        if($("#id_tipo_resposta_pesquisa").find(":selected").val() == 2){
            var contador = 0;
                $('.posicao_resposta').each(function(){
                    var posicao = $(this).parent().parent().find('.descricao_resposta').val();
                    var descricao = $(this).val();
                    if((posicao && !descricao) || (!posicao && descricao)){
                        contador++;
                    }
                });
            if(contador != 0){
                alert("Deve se inserir a posicao e a descricao da resposta!");
                return false;
            }
        }

        if($("#id_tipo_resposta_pesquisa").find(":selected").val() != 1 && $("#id_tipo_resposta_pesquisa").find(":selected").val() != 4){
            if(!$("tr.linha_resposta").length){
                alert("Deve haver pelo menos uma resposta configurada!");
                return false;
            }
        }

        if(naoSalva >= 1){
            alert("Não é possível inserir dois ou mais respostas com a mesma posição!");
            return false;
        }

        if($(".posicao_resposta").val() < 0 || $("#posicao").val() <= 0){
            alert("Deve-se inserir um valor maior que zero na posição!");
            return false;
        }

         modalAguarde();
    });
    
</script>