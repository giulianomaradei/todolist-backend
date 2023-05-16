<?php
require_once(__DIR__."/System.php");
$parametros = (!empty($_POST['parametros'])) ? $_POST['parametros'] : '';
$ano_mes = addslashes($parametros['ano_mes']);
$tipo_remessa = addslashes($parametros['tipo_remessa']);

if($tipo_remessa == 1){
    $dados_boleto = DBRead('', 'tb_boleto', 'WHERE titulo_data_vencimento LIKE "%'.$ano_mes.'%" AND situacao = "EMITIDO" ');
}else if($tipo_remessa == 2){
    $dados_boleto = DBRead('', 'tb_boleto', 'WHERE titulo_data_vencimento LIKE "%'.$ano_mes.'%" AND situacao = "BAIXA PENDENTE" ');
}else if($tipo_remessa == 3){
    $dados_boleto = DBRead('', 'tb_boleto', 'WHERE titulo_data_vencimento LIKE "%'.$ano_mes.'%" AND situacao = "ALTERACAO VENCIMENTO PENDENTE" ');
}

         
$meses = array(
    "01" => "Janeiro",
    "02" => "Fevereiro",
    "03" => "Março",
    "04" => "Abril",
    "05" => "Maio",
    "06" => "Junho",
    "07" => "Julho",
    "08" => "Agosto",
    "09" => "Setembro",
    "10" => "Outubro",
    "11" => "Novembro",
    "12" => "Dezembro",
);

$mes_data = explode("-", $ano_mes);
$ano_data = $mes_data[0];
$mes_data = $mes_data[1];
$texto_remessa = $meses[$mes_data]." de ".$ano_data;

if($ano_mes == -1){
	echo "<div class=\"container-fluid text-center\"><div class=\"alert alert-warning col-md-10 col-md-offset-1\"><i aria-hidden=\"true\"></i> Ops! Não existem boletos disponíveis para remessa!</div></div>";
}else if(!$dados_boleto){
    echo "<div class=\"container-fluid text-center\"><div class=\"alert alert-warning col-md-10 col-md-offset-1\"><i aria-hidden=\"true\"></i> Ops! Não existem boletos disponíveis para remessa nesta data de vencimento!</div></div>";
}else{

?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    <h3 class="panel-title text-center pull-center">Boletos com Vencimento em <strong><?= $texto_remessa ?></strong></h3>
                </div>
                <div class="panel-body" style="padding-bottom: 0;">
                                   
                    <div class="row">
                        <div class="col-md-12">
                            <div class='table-responsive' style="max-height: 365px; overflow-y:auto;">
                                <table class='table table-hover table_paginas' style='font-size: 14px;'>
                                    <thead>
                                        <tr>
                                            <th class='col-md-1'>#</th>
                                            <th class='col-md-3'>Cliente</th>
                                            <th class='col-md-2 text-center'>Valor</th>
                                            <th class='col-md-2 text-center'>Data de Emissão</th>
                                            <th class='col-md-2 text-center'>Data de Vencimento</th>
                                            <th class='col-md-2'>Situação</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
										if($dados_boleto){
                                            foreach($dados_boleto as $conteudo_boleto){
                                                
                                                $id_boleto = $conteudo_boleto['id_boleto'];
                                                $sacado_nome = $conteudo_boleto['sacado_nome'];
									            $titulo_data_vencimento = convertedata($conteudo_boleto['titulo_data_vencimento']);
									            $titulo_data_emissao = convertedata($conteudo_boleto['titulo_data_emissao']);
									            $titulo_valor = converteMoeda($conteudo_boleto['titulo_valor']);
                                                                                        
                                                $situacao = $conteudo_boleto['situacao'];
                                                if($tipo_remessa == 1){
                                                    $dados_boleto_remessa = DBRead('', 'tb_remessa_bancaria_boleto', 'WHERE id_boleto = "'.$conteudo_boleto['id_boleto'].'" ');
                                                }else if($tipo_remessa == 2){
                                                    $dados_boleto_remessa = DBRead('', 'tb_remessa_bancaria_boleto a', 'INNER JOIN tb_remessa_bancaria b ON a.id_remessa_bancaria = b.id_remessa_bancaria WHERE a.id_boleto = "'.$conteudo_boleto['id_boleto'].'" AND b.tipo = "baixa" ');
                                                }else if($tipo_remessa == 3){
                                                    $dados_boleto_remessa = DBRead('', 'tb_remessa_bancaria_boleto a', "INNER JOIN tb_remessa_bancaria b ON a.id_remessa_bancaria = b.id_remessa_bancaria WHERE a.id_boleto = '".$conteudo_boleto['id_boleto']."' AND b.tipo = 'alteracao_vencimento' AND a.titulo_data_vencimento = '".$conteudo_boleto['titulo_data_vencimento']."'");
                                                }else if($tipo_remessa == 4){
                                                    $dados_boleto_remessa = DBRead('', 'tb_remessa_bancaria_boleto a', 'INNER JOIN tb_remessa_bancaria b ON a.id_remessa_bancaria = b.id_remessa_bancaria WHERE a.id_boleto = "'.$conteudo_boleto['id_boleto'].'" AND b.tipo = "alteracao_valor" ');
                                                }
									        	
									        	if(!$dados_boleto_remessa){
									        		echo 
										            	"<tr>
                                                            <td>".$id_boleto."</td>
                                                            <td>".$sacado_nome."</td>
                                                            <td class = 'text-center'>R$ ".$titulo_valor."</td>
                                                            <td class = 'text-center'>".$titulo_data_emissao."</td>
										            		<td class = 'text-center'>".$titulo_data_vencimento."</td>
                                                            <td>".$situacao."</td>
										            	</tr>"
										            ;
									        	}
                                            }
										}
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
}
?>