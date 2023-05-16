<?php
require_once(__DIR__."/../class/System.php");

$id_usuario = $_GET['visualizar'];

$id_usuario_sessao = $_SESSION['id_usuario'];
$dados = DBRead('', 'tb_usuario', "WHERE id_usuario = '$id_usuario_sessao'");
$perfil_sistema = $dados[0]['id_perfil_sistema'];

if($id_usuario == $id_usuario_sessao || ($perfil_sistema != 3)){

	if(!$id_usuario){
		$id_usuario = $_SESSION['id_usuario'];
		$nome = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_usuario = $id_usuario");
		
	}else{
		$nome = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_usuario = $id_usuario");
		
	}
	$dados = DBRead('', 'tb_horarios_escala', "WHERE id_usuario = '".$id_usuario."' AND liberado = 1 ORDER BY data_inicial DESC LIMIT 1");

	$dados_especiais = DBRead('', 'tb_horarios_especiais', "WHERE id_horarios_escala = '".$dados[0]['id_horarios_escala']."'");
	
	$dados_dom = DBRead('', 'tb_folgas_dom', "WHERE id_horarios_escala = '".$dados[0]['id_horarios_escala']."'");

	if($dados_dom){
		if($dados_especiais){
			$tamanho_col = ' class="col-md-4"';
		}else{
			$tamanho_col = ' class="col-md-6"';
		}
	}else if($dados_especiais){
			$tamanho_col = ' class="col-md-6"';
	}else{
			$tamanho_col = ' class="col-md-12 col-md-offset-4"';
	}
	
	if(!$dados){
	echo "<div class=\"container-fluid text-center\"><div class=\"alert alert-warning\"><i aria-hidden=\"true\"></i> Ops! Você ainda não tem horários cadastrados!</div></div>";
	}else{

	$anos = array(
		"2018" => "18",
		"2019" => "19",
		"2020" => "20",
		"2021" => "21",
		"2022" => "22",
		"2023" => "23",
		"2024" => "24",
		"2025" => "25",
	);
	
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

if($ultimo_ano){
	$ultima_data = explode("-", $dados[0]['data_inicial']);
	$ultimo_mes = $ultima_data[1];
	$ultimo_ano = $ultima_data[0];
}else{
	$ultima_data = explode("-", $dados[0]['data_inicial']);
	$ultimo_mes = $ultima_data[1];
	$ultimo_ano = $ultima_data[0];
}

?>

<div class="container-fluid">
	<div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading clearfix">
            	<div class="col-md-4" style="text-align: left">
					 <p><h3 class="panel-title text-center pull-left"><?php echo "Horários e folgas da(o) <strong>".$nome[0]['nome']."</strong>";?>
                	</h3></p>
				</div>
                <div class="col-md-4" style="text-align: center" id= 'div_botao'>
				
				</div>
               	<div class="col-md-4" style="text-align: right;">
               		<div class="panel-title text-right pull-right">
	                  	<div class="col-md-6">
		                	<select name="mes" id="mes" class="form-control" style='width:auto;'>
								<?php
								foreach ($meses as $num => $mes) {
									$selected = $ultimo_mes == $num ? "selected" : "";
									echo "<option value='".sprintf('%02d', $num)."' ".$selected.">".$mes."</option>";
								}
								?>													
							</select>
						</div>
						<div class="col-md-6">
							<select name="ano" id="ano" class="form-control" style='width:auto;'>
								<?php
								foreach ($anos as $num => $ano) {
									$selected = $ultimo_ano == $num ? "selected" : "";
									echo "<option value='".$num."' ".$selected.">".$num."</option>";
								}
								?>													
							</select>
						</div>
					</div>
				</div>
            </div>
		<div class="panel-body">

	    <div class="row">
	        <div class="col-md-12">
	            <div id="resultado_busca"></div>
	        </div>
	    </div>
	</div>
			
<?php
}
}else{
	echo "<div class=\"container-fluid text-center\"><div class=\"alert alert-danger\"><i class=\"fa fa-ban\" aria-hidden=\"true\"></i> Ops! Não existem horários cadastrados!</div></div>";
}
?>
	    
</div>

<script>
	
	$( document ).ready(function() {
		call_busca_ajax();
	});

	$('#mes').on('change', function() {

		call_busca_ajax();

		var data_lido = '<?=$data_lido; ?>'; 

		//alert('data_lido: '+data_lido);

	});
	
	$('#ano').on('change', function() {

		call_busca_ajax();
	})

    $(document).on('click', '#lido', function(){

    	if(confirm('Ao clicar em OK você estará ciente da escala proposta!')){
    		var obj = $(this);
	        var usuario = '<?=$id_usuario; ?>'; 
	        var id_horario = "<?=$dados[0]['id_horarios_escala']; ?>"; 
	        var meses = $('#mes').val();
	        var anos = $('#ano').val();
	        var agora = "<?=converteDataHora(getDataHora()); ?>";

	        $.ajax({
	            type: "GET",
	            url: "/api/ajax?class=LidoHorarios.php",
	            dataType: "json",
	            data: {
	                id_horario: obj.attr('dt-lido'),
					token: '<?= $request->token ?>'
	            },
	            success: function(data){
	                
	                $('#div_botao').html("<p><h3 class='panel-title text-center pull-center'>Ciente desde: "+agora+"</h3></p>");

	            }	
	        });
    	}        

    });

    function call_busca_ajax(){

        var mes = $('#mes').val();
        var ano = $('#ano').val();
        var usuario = '<?=$id_usuario; ?>'; 
        var inicial = ano+'-'+mes+'-01';

        var parametros = {
            'inicial': inicial,
            'usuario': usuario,
        };
        busca_ajax('<?= $request->token ?>' , 'BuscaExibeEscalaHorarios', 'resultado_busca', parametros);
    }

    call_busca_ajax();

</script>