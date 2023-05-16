<?php
require_once(__DIR__."/System.php");
$parametros = (!empty($_POST['parametros'])) ? $_POST['parametros'] : '';

$operacao = addslashes($parametros['operacao']);
$identificador = addslashes($parametros['identificador']);

###################################################################################
// INICIO DO CONTEÚDO
// 
$dados = DBRead('', 'tb_contrato_plano_pessoa a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa INNER JOIN tb_plano c ON a.id_plano = c.id_plano INNER JOIN tb_parametros d ON a.id_contrato_plano_pessoa = d.id_contrato_plano_pessoa WHERE c.cod_servico = 'call_suporte' AND (a.status = 1 OR a.status = 7) AND (b.nome LIKE '%$letra%' OR a.nome_contrato LIKE '%$letra%') AND d.atendimento_via_texto = '1' ORDER BY b.nome ASC", "a.id_contrato_plano_pessoa, a.id_plano, a.id_pessoa, a.nome_contrato, b.nome, c.cor, c.cod_servico");
if (!$dados) {
    echo "<p class='alert alert-warning' style='text-align: center'>";
    if (!$letra) {
        echo "Não foram encontrados registros!";
    } else {
        echo "Nenhum resultado encontrado na busca por \"<strong>$letra</strong>\"";
    }
    echo "</p>";
} else {

    echo "
    <div class='form-group'>
        <div class='row'>";
        foreach ($dados as $conteudo) {
            $id = $conteudo['id_grupo_atendimento_chat'];
            $nome = $conteudo['nome'];
            $nome_contrato = $conteudo['nome'];
            if($conteudo['nome_contrato']){
                $nome_contrato.= " (".$conteudo['nome_contrato'].")";
            }

            $dados_grupo_atendimento_chat_contrato = DBRead('', 'tb_grupo_atendimento_chat_contrato', "WHERE id_contrato_plano_pessoa = '".$conteudo['id_contrato_plano_pessoa']."' AND id_grupo_atendimento_chat = '".$identificador."'");
            if($dados_grupo_atendimento_chat_contrato){
                // if($dados_grupo_atendimento_chat_contrato[0]['id_grupo_atendimento_chat'] == $identificador){
                    $checked_input = 'checked';
                    // $disabled_input = '';
                // }else{
                //     $checked_input = '';
                //     // $disabled_input = 'disabled';
                // }
            }else{
                $checked_input = '';
                // $disabled_input = '';
            }
        
            echo '
            <div class="col-md-4" style="padding-bottom: 15px;">
                <div class="input-group">
                    <span class="input-group-addon" style="background-color: rgb(217, 217, 217) !important;">
                        <input '.$checked_input.' type="checkbox" name="contrato_grupo[]" value="'.$conteudo['id_contrato_plano_pessoa'].'" '.$disabled_input.'>
                    </span>
                    <input type="text" class="form-control mensagem" disabled value="'.$nome_contrato.'" style="cursor: context-menu; text-shadow: 0px 0px 0px !important; background-image: none !important; background-color: rgb(217, 217, 217) !important; color: rgb(0, 0, 0); border-left: 0px solid; border-top: 0px solid; border-bottom: 0px solid; border-right: 0px solid;">
                    <span class="input-group-addon" style="font-size: 11px; cursor: context-menu; border-right: 20px solid '.$conteudo['cor'].'; text-shadow: 0px 0px 0px !important; background-image: none !important; background-color: rgb(217, 217, 217) !important; color: rgb(0, 0, 0); border-left: 0px solid; border-top: 0px solid; border-bottom: 0px solid;">'.$conteudo['id_contrato_plano_pessoa'].'</span>
                </div>
            </div>';
                        
        }
        echo "
        </div>";
    echo "
    </div>";
    
}


?>