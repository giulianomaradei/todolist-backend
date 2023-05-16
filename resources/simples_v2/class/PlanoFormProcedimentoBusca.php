
<?php
require_once(__DIR__."/System.php");

$parametros = (!empty($_POST['parametros'])) ? $_POST['parametros'] : '';
$cod_servico = addslashes($parametros['cod_servico']);
$operacao = addslashes($parametros['operacao']);
$id = addslashes($parametros['id']);

?>
<div class="col-md-12">
    <div class='table-responsive' style="max-height: 365px; overflow-y:auto;">
               
                <?php
                if($operacao == 'alterar'){
                    $dados_plano = DBRead('', 'tb_plano', 'WHERE id_plano = "'.$id.'" ');
                    $dados = DBRead('', 'tb_plano_procedimento', 'WHERE status = "1" AND cod_servico = "'.$dados_plano[0]['cod_servico'].'" ORDER BY nome ASC');
                }else{
                    $dados = DBRead('', 'tb_plano_procedimento', 'WHERE status = "1" AND cod_servico = "'.$cod_servico.'" ORDER BY nome ASC');
                }
                if($dados){
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
                        <td><input type="checkbox" id="checkTodos" name="checkTodos"></td>
                        <td>Todos</td>
                        <td></td>
                        <td></td>
                    </tr>';
                    foreach($dados as $conteudo){
                        $id_plano_procedimento = $conteudo['id_plano_procedimento'];
                        $nome = $conteudo['nome'];
                        $descricao = $conteudo['descricao'];                                                                
                        $pre_requisito = $conteudo['pre_requisito'];                                                                
                        $ckecked = '';
                        if($operacao == 'alterar'){
                            $dados_plano_procedimento_plano = DBRead('', 'tb_plano_procedimento_plano', "WHERE id_plano = '$id' AND id_plano_procedimento = '$id_plano_procedimento'");
                            if($dados_plano_procedimento_plano){
                                $ckecked = 'checked';
                            }
                            
                        }
                        echo "<tr>
                                <td style='vertical-align: middle;'><input name=\"permissoes[]\" class='".$conteudo['cod_servico']."' type=\"checkbox\" value=\"$id_plano_procedimento\" id=\"$id_plano_procedimento\" $ckecked></td> 
                                <td style='vertical-align: middle;'>$nome</td> 
                                <td style='vertical-align: middle;'><span data-toggle=\"tooltip\" title=\"".limitarTexto($descricao, 500)."\">".limitarTexto($descricao, 30)."</span></td>
                                <td style='vertical-align: middle;'><span data-toggle=\"tooltip\" title=\"".limitarTexto($pre_requisito, 500)."\">".limitarTexto($pre_requisito, 30)."</span></td>
                            </tr>";
                    }
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