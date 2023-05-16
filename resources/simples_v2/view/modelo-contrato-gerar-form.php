
<?php
require_once(__DIR__."/../class/System.php");
$gerar = (!empty($_POST['gerar'])) ? 1 : 0;
$id = (int)$_GET['modelo'];
if($gerar){
	$collapse = '';
	$collapse_icon = 'plus';
}else{
	$collapse = 'in';
	$collapse_icon = 'minus';
}

$dados = DBRead('', 'tb_contrato_configuracao', "WHERE id_contrato_configuracao = '".$id."' AND status = '1'");

if(!$dados){
    echo "<div class=\"container-fluid text-center\"><div class=\"alert alert-danger\"><i class=\"fa fa-ban\" aria-hidden=\"true\"></i> Ops! Você não pode usar um modelo desativado!</div></div>";
    exit;
}

$dados_variaveis = array_unique(getContents($dados[0]['contrato_descricao'], '{@inicio}', '{@fim}'));

if($gerar){
    foreach ($dados_variaveis as $variavel) {	
        $var = 'var_'.$variavel;
        $post_variaveis = (!empty($_POST[$var])) ? $_POST[$var] : '';
        $dados_conteudo_variaveis[$variavel] = $post_variaveis;
    }
}

$class_mascara = array(
    'texto' => '',
    'numero_inteiro' => 'number_int',
    'numero_flutuante' => 'number_float',
    'percentual' => 'percent',
    'dinheiro' => 'money',
    'data' => 'date calendar',
    'hora' => 'hour',
    'telefone' => 'phone',
    'cpf' => 'cpf',
    'cnpj' => 'cnpj',
    'cep' => 'cep'
)

?>
<style>
    @media print {
        .noprint { display:none; }
        body {
            font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
            padding-top: 0;
        }
    }
</style>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs/jszip-2.5.0/dt-1.10.18/b-1.5.4/b-colvis-1.5.4/b-flash-1.5.4/b-html5-1.5.4/b-print-1.5.4/r-2.2.2/datatables.min.css"/> 
 <script type="text/javascript" src="https://cdn.datatables.net/v/bs/jszip-2.5.0/dt-1.10.18/b-1.5.4/b-colvis-1.5.4/b-flash-1.5.4/b-html5-1.5.4/b-print-1.5.4/r-2.2.2/datatables.min.js"></script>
 <script type="text/javascript" src="https://cdn.datatables.net/plug-ins/1.10.19/sorting/time.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/plug-ins/1.10.19/sorting/chinese-string.js"></script>

<div class="container-fluid">
	<form method="post" action="">
		<input type="hidden" name="token" value="<?php echo $request->token ?>">
	    <div class="row">
	        <div class="col-md-4 col-md-offset-4">
	            <div class="panel panel-default noprint">
	                <div class="panel-heading clearfix">
	                    <h3 class="panel-title text-left pull-left" style="margin-top: 2px;">Gerar contrato:</h3>
	                    <div class="panel-title text-right pull-right"><button data-toggle="collapse" data-target="#accordionRelatorio" class="btn btn-xs btn-info" type="button" title="Visualizar filtros"><i id="i_collapse" class="fa fa-<?=$collapse_icon?>"></i></button></div>
	                </div>
	                <div id="accordionRelatorio" class="panel-collapse collapse <?=$collapse?>">
	                	<div class="panel-body">	            
                            <?php 			
                            foreach ($dados_variaveis as $variavel) {
                                $exp_variavel = explode('|',$variavel);                                              			
                                echo "<div class='row'>	
                                    <div class='col-md-12'>
                                        <div class='form-group'>
                                            <label>*".str_replace('_', ' ', $exp_variavel[0]).":</label>
                                            <input type='text' class='form-control input-sm ".$class_mascara[$exp_variavel[1]]."' name='var_".htmlentities($variavel)."' value='".$dados_conteudo_variaveis[$variavel]."' autocomplete='off' required>
                                        </div>
                                    </div>
                                </div>";
                            }
                            ?>								
		                </div>
	            	</div>
	                <div class="panel-footer">
                        <div class="row">
                            <div id="panel_buttons" class="col-md-12" style="text-align: center">
                                <button class="btn btn-primary" name="gerar" id="gerar" value="1" type="submit" disabled><i class="fa fa-refresh"></i> Gerar</button>
                                <button class="btn btn-warning" name="imprimir" type="button" onclick="window.print();"><i class="fa fa-print"></i> Imprimir</button>
                            </div>
                        </div>
                    </div>
	            </div>
	        </div>
	    </div>
	</form>
	<div id="aguarde" class="alert alert-info text-center">Aguarde, gerando relatório... <i class="fa fa-spinner faa-spin animated"></i></div>	
	<div id="resultado" class="row" style="display:none;">	
		<?php 
		if($gerar){
            $texto_final = $dados[0]['contrato_descricao'];
		  	foreach ($dados_variaveis as $variavel) {		  		
                if($dados_conteudo_variaveis[$variavel]){
                    $texto_final = str_replace('{@inicio}'.$variavel.'{@fim}', $dados_conteudo_variaveis[$variavel], $texto_final);
                }	
		  	}
			resultado_final($texto_final);
		}
		?>
	</div>
</div>
<script>

	$('#accordionRelatorio').on('shown.bs.collapse', function(){
       $("#i_collapse").removeClass("fa fa-plus").addClass("fa fa-minus");
    });
    $('#accordionRelatorio').on('hidden.bs.collapse', function(){
       $("#i_collapse").removeClass("fa fa-minus").addClass("fa fa-plus");
    });
	$(document).on('submit', 'form', function () {       
        modalAguarde();
    });
	$(document).ready(function(){
	    $('#aguarde').hide();
	    $('#resultado').show();
	    $("#gerar").prop("disabled", false);
	});
	
</script>

<?php 

function getContents($str, $startDelimiter, $endDelimiter) {
	$contents = array();
	$startDelimiterLength = strlen($startDelimiter);
	$endDelimiterLength = strlen($endDelimiter);
	$startFrom = $contentStart = $contentEnd = 0;
	
	while (false !== ($contentStart = strpos($str, $startDelimiter, $startFrom))) {
		$contentStart += $startDelimiterLength;
		$contentEnd = strpos($str, $endDelimiter, $contentStart);
		
		if (false === $contentEnd) {
			break;
		}
		
		$contents[] = substr($str, $contentStart, $contentEnd - $contentStart);
		$startFrom = $contentEnd + $endDelimiterLength;
	}
	return $contents;
}

function resultado_final($texto_final){

    echo '<div class="container-fluid">
        <div class="row">
            <div class="col-md-8 col-md-offset-2"><br><br>
            '.$texto_final.'
            </div>
        </div>
    </div>';

}

?>