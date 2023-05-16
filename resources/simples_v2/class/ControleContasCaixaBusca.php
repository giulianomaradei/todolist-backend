<?php
require_once(__DIR__."/System.php");
$parametros = (!empty($_POST['parametros'])) ? $_POST['parametros'] : '';
$letra = addslashes($parametros['nome']);
$id_caixa = addslashes($parametros['id_caixa']);
$agrupador = addslashes($parametros['agrupador']);
$tipo = addslashes($parametros['tipo']);
$origem = addslashes($parametros['origem']);

echo '
<style>
.body_caixa {
    display:block;
    height:345px;
    overflow:auto;
}
thead, tbody tr {
    display:table;
    width:100%;
    table-layout:fixed;
}

</style>';

if($agrupador){
    $filtro_agrupador = " AND c.id_natureza_financeira = '".$agrupador."' ";
}

if($tipo){
    $filtro_tipo = " AND a.tipo = '".$tipo."' ";
}

if($origem){
    $filtro_origem = " AND a.origem = '".$origem."' ";
}

if($parametros['data_de'] && $parametros['data_ate']){
    $data_de = converteData($parametros['data_de']);
    $data_ate = converteData($parametros['data_ate']);

    $data_de = $data_de.' 00:00:00';
    $data_ate = $data_ate.' 23:59:59';

    $filtro_data = "AND (a.data_movimentacao BETWEEN '$data_de' AND '$data_ate')";

}else if($parametros['data_de'] && !$parametros['data_ate']){
    $data_de = converteData($parametros['data_de']);

    $data_de = $data_de.' 00:00:00';

    $filtro_data = "AND (a.data_movimentacao >= '$data_de')";

}else if(!$parametros['data_de'] && $parametros['data_ate']){

    $data_ate = converteData($parametros['data_ate']);

    $data_ate = $data_ate.' 23:59:59';

    $filtro_data = "AND (a.data_movimentacao <= '$data_ate')";
}

// Informações da query
$filtros_query  = "INNER JOIN tb_natureza_financeira c ON a.id_natureza_financeira = c.id_natureza_financeira INNER JOIN tb_natureza_financeira_agrupador d ON c.id_natureza_financeira_agrupador = d.id_natureza_financeira_agrupador INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_caixa = '".$id_caixa."' AND (b.nome LIKE '%$letra%' OR b.razao_social LIKE '%$letra%') ".$filtro_data." ".$filtro_agrupador." ".$filtro_tipo." ".$filtro_origem." ORDER BY a.data_cadastro ASC";

###################################################################################
// INICIO DO CONTEÚDO
		  
		  
$soma_total_caixa = 0;
$total_entradas = 0;
$total_saidas = 0;


$dados = DBRead('', 'tb_caixa_movimentacao a',$filtros_query, "a.*, b.nome, c.nome AS nome_natureza, d.nome AS nome_natureza_agrupador");
if(!$dados){
    echo "<p class='alert alert-warning' style='text-align: center'>";
    if(!$letra){
        echo "Não foram encontrados registros!";
    }else{
        echo "Nenhum resultado encontrado na busca por \"<strong>$letra</strong>\"";
    }
    echo "</p>";
    
    $total_registros = 0;

}else{
    echo "<div class='table' style='overflow: auto; height: 400px !important;'>";
	    echo "<table class='table table-condensed' style='font-size: 14px;'>";
		    echo "<thead>";
			    echo "<tr>";
				    echo "<th class='col-md-1'>#</th>";
				    echo "<th class='col-md-5'>Natureza Financeira</th>";
				    echo "<th class='col-md-2'>Data da Movimentação</th>";
				    echo "<th class='col-md-1'>Valor</th>";
				    echo "<th class='col-md-1'>Tipo</th>";
				    echo "<th class='col-md-1'>Origem</th>";
				    echo "<th class='text-center col-md-1'>Opções</th>";
			    echo "</tr>";
		    echo "</thead>";
		    echo "<tbody class='body_caixa'>";
		    
		    echo '<input type="hidden" id="id_antigo_tabela" value="'.$dados[0]['id_caixa_movimentacao'].'" >';
		    
		    $total_registros = sizeof($dados);

		    foreach ($dados as $conteudo) {
		        $id_caixa_movimentacao = $conteudo['id_caixa_movimentacao'];
		        
		        $nome = $conteudo['nome'];
		        $natureza = $conteudo['nome_natureza_agrupador']." (".$conteudo['nome_natureza'].")";
		        $nome_natureza = $conteudo['nome_natureza'];
		        
		        $data_movimentacao = converteData($conteudo['data_movimentacao']);

		        $valor = converteMoeda($conteudo['valor']);

		        if($conteudo['tipo'] == 'entrada'){
		        	$tipo = '<span class="label label-success" style="display: inline-block; min-width: 70px; font-size: 13px;"><i class="fas fa-arrow-circle-down" aria-hidden="true"></i> Entrada</span>';
		        	$tipo_modal = '<span class="label label-success" style="display: inline-block; min-width: 100px;"><i class="fas fa-arrow-circle-down" aria-hidden="true"></i> Entrada </span>';

		        	$soma_total_caixa += $conteudo['valor'];
		        	$total_entradas += $conteudo['valor'];
		        }else{
		        	$tipo = '<span class="label label-danger" style="display: inline-block; min-width: 70px; font-size: 13px;"><i class="fas fa-arrow-circle-up" aria-hidden="true"></i> Saída </span>';
		        	$tipo_modal = '<span class="label label-danger" style="display: inline-block; min-width: 100px;"><i class="fas fa-arrow-circle-up" aria-hidden="true"></i> Saída </span>';

		        	$soma_total_caixa -= $conteudo['valor'];
		        	$total_saidas += $conteudo['valor'];
		        }

		        if($conteudo['origem'] == 'conta_receber'){
		        	$origem = 'Conta a Receber';
		        }else if($conteudo['origem'] == 'transferencia'){
		        	$origem = 'Transferência';
		        }else{
		        	$origem = 'Conta a Pagar';
		        }

		        if($dados[0]['id_caixa_movimentacao'] == $conteudo['id_caixa_movimentacao']){
		        	echo "<tr class='info' id='tr_caixa' value='".$id_caixa_movimentacao."' >";
		        }else{
		        	echo "<tr class='default' id='tr_caixa' value='".$id_caixa_movimentacao."'>";
		        }
					echo "<td class='col-md-1 modal_caixas' name='td_nome[]' style='vertical-align: middle; cursor: pointer;' attr-id='$id_caixa_movimentacao'>".$id_caixa_movimentacao."</td>";
					
			        echo "<td class='col-md-5 modal_caixas' style='vertical-align: middle; cursor: pointer;' attr-id='$id_caixa_movimentacao'>
			        		  <i class='fas fa-donate'></i> ".$natureza."<br>
			        		  <i class='fa fa-address-card-o'></i> ".$nome."<br>";
			        		  if($conteudo['descricao']){
			        		  echo "<i class='fa fa-list-alt'></i> ".limitarTexto($conteudo['descricao'], 40);
			        		  }
					echo "</td>";
					
					echo "<td class='col-md-2  modal_caixas' style='vertical-align: middle; cursor: pointer;' attr-id='$id_caixa_movimentacao'>".$data_movimentacao."</td>";
					
					echo "<td class='col-md-1  modal_caixas' style='vertical-align: middle; cursor: pointer;' attr-id='$id_caixa_movimentacao'>R$ ".$valor."</td>";
					
					echo "<td class='col-md-1  modal_caixas' style='vertical-align: middle; cursor: pointer;' attr-id='$id_caixa_movimentacao'>".$tipo."</td>";
					
					echo "<td class='col-md-1  modal_caixas' style='vertical-align: middle; cursor: pointer;' attr-id='$id_caixa_movimentacao'>".$origem."</td>";
					
			        if($conteudo['origem'] == 'transferencia'){
        				echo "<td class='text-center col-md-1' style='vertical-align: middle;'><a href=\"class/ControleContas.php?excluir_transferencia=$id_caixa_movimentacao\" title='Excluir' onclick=\"if (!confirm('Excluir ".addslashes($origem)." de R$ ".addslashes($valor)."?')) { return false; } else { modalAguarde(); }\"><i class='fa fa-trash' style='color:#b92c28;'></i></a></td>";
			        }else{
        				echo "<td class='text-center col-md-1' style='vertical-align: middle;'><a href=\"class/ControleContas.php?excluir=$id_caixa_movimentacao\" title='Excluir' onclick=\"if (!confirm('Excluir ".addslashes($origem)." ".addslashes($nome)."?')) { return false; } else { modalAguarde(); }\"><i class='fa fa-trash' style='color:#b92c28;'></i></a></td>";
			        }	        

		        echo "</tr>";
		    }
		    $soma_total_caixa = converteMoeda($soma_total_caixa);
		    $total_entradas = converteMoeda($total_entradas);
		    $total_saidas = converteMoeda($total_saidas);
		    echo "</tbody>";
	    echo "</table>";
    echo "</div>";

}

?>

<script>
	
$(document).on('click', '#tr_caixa', function(){
	
	var id_caixa_movimentacao = $(this).find("[name ='td_nome[]']").text()
	var antigo_tabela = $('#id_antigo_tabela').val();

	$("[name ='td_nome[]']").each(function(){

        if($(this).text() == id_caixa_movimentacao){
			$(this).parent().removeClass('default');
			$(this).parent().addClass('info');
		}else if($(this).text() == antigo_tabela){
			$(this).parent().removeClass('info');
			$(this).parent().addClass('default');
		}
    });
    $('#id_antigo_tabela').val(id_caixa_movimentacao);
});

$('#total_registros').html('Total de Registros: <strong><?= $total_registros ?></strong>');
$('#soma_total_caixa').html('Total de Entradas: <strong>R$ <?= $total_entradas ?></strong> | Total de Saídas: <strong>R$ <?= $total_saidas ?></strong> | Saldo: <strong>R$ <?= $soma_total_caixa ?></strong>');

</script>