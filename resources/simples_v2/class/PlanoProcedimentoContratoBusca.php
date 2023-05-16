
<?php
require_once(__DIR__."/System.php");

$parametros = (!empty($_POST['parametros'])) ? $_POST['parametros'] : '';
$id_plano = addslashes($parametros['id_plano']);
$versao = addslashes($parametros['versao']);
$personalizado_procedimento = addslashes($parametros['personalizado_procedimento']);
$disabled_mesmo = addslashes($parametros['disabled']);

if($personalizado_procedimento == 1){
    $disabled_check = '';
}else{
    $disabled_check = 'disabled';
}

if($disabled_mesmo == 1){
    $disabled_check = 'disabled';
}
 
?>
<div class="col-md-12">
    <div class='table-responsive' style="max-height: 365px; overflow-y:auto;">
        
                
                <?php 

$dados_procedimentos = DBRead('', 'tb_plano_procedimento', "ORDER BY nome ASC");

$dados_plano = DBRead('', 'tb_plano_procedimento_historico', "WHERE versao = '".$versao."' AND id_plano = '".$id_plano."' ");
                

                // $dados_procedimento_historico = DBRead('', 'tb_plano_procedimento_historico a', 'INNER JOIN tb_plano_procedimento b ON a.id_plano_procedimento = b.id_plano_procedimento WHERE a.id_plano = "'.$id_plano.'" AND a.versao = "'.$versao.'" ORDER BY b.id_plano_procedimento ASC');

                if($dados_procedimentos){
                    echo 
                    '<table class="table table-hover table_paginas" style="font-size: 14px;">
                    <thead>
                        <tr>
                            <th class="col-md-1">Permissão</th>
                            <th class="col-md-3">Procedimento</th>
                            <th class="col-md-4">Descrição</th>
                            <th class="col-md-4">Pré-Requisito</th>
                        </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td><input type="checkbox" id="checkTodos" name="checkTodos" '.$disabled_check.'></td>
                        <td>Todos</td>
                        <td></td>
                        <td></td>
                    </tr>';

                    foreach ($dados_procedimentos as $conteudo) { 

                        $cont = 0;
                        
                        foreach ($dados_plano as $key => $procedimentos) {

                            if ($conteudo['id_plano_procedimento'] == $procedimentos['id_plano_procedimento']) {
                                $cont++;
                            }
                        }
                    ?>
                    <tr>

                        <?php if ($cont != 0 ) {
                            echo "<tr>
                                <td><input name=\"procedimentos_selecionados[]\" type=\"checkbox\" value='".$conteudo['id_plano_procedimento']."' id=".$conteudo['id_plano_procedimento']." checked $disabled_check></td> 
                                <td>".$conteudo['nome']."</td> 
                                <td><span data-toggle=\"tooltip\" title=\"".limitarTexto($conteudo['descricao'], 500)."\">".limitarTexto($conteudo['descricao'], 30)."</span></td>
                                <td><span data-toggle=\"tooltip\" title=\"".limitarTexto($conteudo['pre_requisito'], 500)."\">".limitarTexto($conteudo['pre_requisito'], 30)."</span></td>
                            </tr>";
                            } else {
                             echo "<tr>
                                <td><input name=\"procedimentos_selecionados[]\" type=\"checkbox\" value=".$conteudo['id_plano_procedimento']." id=".$conteudo['id_plano_procedimento']."  $disabled_check></td> 
                                <td>".$conteudo['nome']."</td> 
                                <td><span data-toggle=\"tooltip\" title=\"".limitarTexto($conteudo['descricao'], 500)."\">".limitarTexto($conteudo['descricao'], 30)."</span></td>
                                <td><span data-toggle=\"tooltip\" title=\"".limitarTexto($conteudo['pre_requisito'], 500)."\">".limitarTexto($conteudo['pre_requisito'], 30)."</span></td>
                            </tr>";
                         } ?>
                    </tr>
                    <?php }
                    
                    // foreach($procedimentos as $conteudo){
                    //     $id_plano_procedimento = $conteudo['id_plano_procedimento'];
                    //     $nome = $conteudo['nome'];
                    //     $descricao = $conteudo['descricao'];     
                    //     $pre_requisito = $conteudo['pre_requisito'];     
                        
                    //     $ckecked = '';
                            
                       
                    //     if($dados_procedimento_historico_checked){
                    //         $ckecked = 'checked';
                    //     }
                            
                        
                    // }
                }else{
                    echo "<p class='alert alert-warning' style='text-align: center'>";
                        echo "Não foram encontrados procedimentos para este serviço!";
                    echo "</p>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<script>


$(document).on('click', '#checkTodos', function () {
    if ( $(this).is(':checked') ){
        $('input:checkbox').prop("checked", true);
    }else{
        $('input:checkbox').prop("checked", false);
    }
});

$(function () {
  $('[data-toggle="tooltip"]').tooltip()
})

</script>