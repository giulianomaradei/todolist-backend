<?php
require_once(__DIR__."/System.php");

$parametros = (!empty($_POST['parametros'])) ? $_POST['parametros'] : '';
$letra = addslashes($parametros['nome']);

$dados = DBRead('', 'tb_contrato_plano_pessoa a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa INNER JOIN tb_plano c ON a.id_plano = c.id_plano LEFT JOIN tb_informacao_geral_contrato d ON a.id_contrato_plano_pessoa = d.id_contrato_plano_pessoa WHERE a.status = 1 AND (d.monitoramento = '1' OR c.cod_servico = 'call_monitoramento') AND (b.nome LIKE '%$letra%' OR a.nome_contrato LIKE '%$letra%') ORDER BY b.nome ASC", "a.id_contrato_plano_pessoa, a.id_plano, a.id_pessoa, a.nome_contrato, b.nome, c.cor, c.cod_servico");
if(!$dados){
    echo "<p class='alert alert-warning' style='text-align: center'>";
    if(!$letra){
        echo "Não foram encontrados registros!";
    } else {
        echo "Nenhum resultado encontrado na busca por \"<strong>$letra</strong>\"";
    }
    echo "</p>";
}else{

    echo "<div class='row'>";
    
    foreach($dados as $conteudo){
    
        echo "<div class='col-lg-3 col-md-4' style = 'padding-bottom: 15px;'>";
            echo '<div class="btn-group btn-group-justified" role="group" aria-label="...">';
			    echo '<div class="btn-group" role="group">';
                    echo '<a href="/api/iframe?token=<?php echo $request->token ?>&view=monitoramento-form&contrato='.$conteudo['id_contrato_plano_pessoa'].'" class="btn btn-default" style="border-left: 20px solid '.$conteudo['cor'].'; padding-top: 16px; padding-bottom: 16px; text-shadow: 0px 0px 0px !important; background-image: none !important; background-color: rgb(217, 217, 217) !important; color: rgb(0, 0, 0);">';
                        echo '<span style="font-size: 13px; display: inline;" class="pull-left">'.$conteudo['id_contrato_plano_pessoa'].'</span>';
                        echo '<span style="font-size: 16px;" >';
                        echo $conteudo['nome'];
                        if($conteudo['nome_contrato']){
                            echo " (".$conteudo['nome_contrato'].")";
                        }
                        echo '</span>';
                    echo '</a>';
                echo "</div>";  
            echo "</div>";
        echo "</div>";
    
    }    

    echo "</div>";

}
?>