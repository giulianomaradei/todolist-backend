<?php
require_once(__DIR__."/../class/System.php");


    $tituloPainel = 'Visualizar';
    $id = (int)$_GET['id'];
	$latitude = $_GET['lat'];
	$longitude = $_GET['lon'];
    $id_contrato_plano_pessoa = (int)$_GET['contrato'];
    $dados = DBRead('', 'tb_localizacao_contrato', "WHERE id_localizacao_contrato = $id");

    $dados_localizacao = DBRead('', 'tb_localizacao_contrato a', "INNER JOIN tb_localizacao b ON a.id_localizacao_contrato = b.id_localizacao_contrato WHERE a.id_contrato_plano_pessoa = '$id_contrato_plano_pessoa'");

    $dados_contrato_plano_pessoa = DBRead('', 'tb_contrato_plano_pessoa a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_contrato_plano_pessoa = '$id_contrato_plano_pessoa'");
    $nome_pessoa = $dados_contrato_plano_pessoa[0]['nome'];

?>
<style>
.navbar{
	display: none !important;
}
body{
    padding-top: 0 !important;
}
</style>
<script type="text/javascript">
    $(document).ready(function() {
        document.title = 'Simples V2 - Mapa';
    });
</script>
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    <h3 class="panel-title text-left pull-left"><?= $tituloPainel ?> localização: <?=$nome_pessoa?> - <?=$dados_localizacao[0]['endereco']?> 
                   
                    <?php
                        if($dados_localizacao[0]['ponto_referencia']){
                            echo "(".$dados_localizacao[0]['ponto_referencia'].")";
                        }
                    ?>

                    </h3>
                </div>
                    <div class="panel-body" style="padding-bottom: 0;">     
                        <input type="hidden" name="latitude" class="form-control input-sm" value="<?=$latitude?>" id="latitude" />
                        <input type="hidden" name="longitude" class="form-control input-sm" value="<?=$longitude?>" id="longitude" />
                        <div id="map" style="width: 100%; height: 600px;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA2MWfvsSfB00spwyWm-0WNSGlJvF4dajs&callback=initMap">
</script>

<script>

	var latitude = parseFloat($("#latitude").val());
	var longitude = parseFloat($("#longitude").val());

	function initMap() {
	  var coordenada = {lat: latitude, lng: longitude};
	  var map = new google.maps.Map(
	      document.getElementById('map'), {zoom: 18, center: coordenada});
	  var marker = new google.maps.Marker({position: coordenada, map: map});
	}

</script>