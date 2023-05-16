<?php
require_once(__DIR__."/../class/System.php");
$gerar = (!empty($_POST['gerar'])) ? 1 : 0;
$id_plano = (!empty($_POST['id_plano'])) ? $_POST['id_plano'] : '';
$cod_servico = (!empty($_POST['cod_servico'])) ? $_POST['cod_servico'] : '';
$id_estado = (!empty($_POST['id_estado'])) ? $_POST['id_estado'] : '';

if($gerar){
    $collapse = '';
	$collapse_icon = 'plus';
    $mostra_mapa = 1;  
}else{
    $mostra_mapa = 0;
    $collapse = 'in';
	$collapse_icon = 'minus';
}
$filtro = '';
if($id_plano){
    $filtro .= " AND a.id_plano = '$id_plano'";
}
if($cod_servico){
    $filtro .= " AND e.cod_servico = '$cod_servico'";
}
if($id_estado){
    $filtro .= " AND d.id_estado = '$id_estado'";
}

$dados_contrato = DBRead('','tb_contrato_plano_pessoa a',"INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa INNER JOIN tb_cidade c ON b.id_cidade = c.id_cidade INNER JOIN tb_estado d ON c.id_estado = d.id_estado INNER JOIN tb_plano e ON a.id_plano = e.id_plano WHERE a.status = '1' AND b.nome NOT LIKE '%belluno%' $filtro GROUP BY a.id_pessoa ORDER BY b.nome ASC", "a.id_pessoa, b.nome AS 'nome_cliente', b.cep, b.numero, b.logradouro, b.bairro, c.nome AS 'nome_cidade', d.sigla AS 'estado'");
/* echo '<pre>';
var_dump($dados_contrato);
echo '</pre>'; */
?>
<div class="container-fluid">
	<form method="post" action="">
	    <div class="row">
	        <div class="col-md-4 col-md-offset-4">
	            <div class="panel panel-default noprint">
	                <div class="panel-heading clearfix">
	                    <h3 class="panel-title text-left pull-left" style="margin-top: 2px;">Mapa de clientes:</h3>
	                    <div class="panel-title text-right pull-right"><button data-toggle="collapse" data-target="#accordionRelatorio" class="btn btn-xs btn-info" type="button" title="Visualizar filtros"><i id="i_collapse" class="fa fa-<?=$collapse_icon?>"></i></button></div>
	                </div>
	                <div id="accordionRelatorio" class="panel-collapse collapse <?=$collapse?>">
	                	<div class="panel-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Serviço:</label>
                                        <select class="form-control input-sm" id="cod_servico" name="cod_servico">
                                            <?php                                            
                                            $dados_plano = DBRead('', 'tb_plano', "GROUP BY cod_servico ORDER BY cod_servico ASC","cod_servico");
                                            if ($dados_plano) {
                                                echo "<option value=''>Todos</option>";
                                                foreach ($dados_plano as $conteudo) {
                                                    $selected = $cod_servico == $conteudo['cod_servico'] ? "selected" : "";
                                                    $servico_select = getNomeServico($conteudo['cod_servico']);
                                                    echo "<option value='".$conteudo['cod_servico']."' ".$selected.">$servico_select</option>";
                                                }
                                            }                                            
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Plano:</label>
                                        <select class="form-control input-sm" id="id_plano" name="id_plano">
                                            <option value=''>Todos</option>
                                            <?php
                                            $dados_plano = DBRead('', 'tb_plano', "WHERE cod_servico = '$cod_servico' ORDER BY cod_servico ASC, nome ASC");
                                            if ($dados_plano) {
                                                foreach ($dados_plano as $conteudo) {
                                                    $id_select = $conteudo['id_plano'];
                                                    $nome_select = $conteudo['nome'];
                                                    $selected = $id_plano == $id_select ? "selected" : "";

                                                    echo "<option value='$id_select'".$selected.">$nome_select</option>";
                                                }
                                            }                                      
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Estado:</label>
                                        <select class="form-control input-sm" id="id_estado" name="id_estado">
                                            <option value=''>Todos</option>
                                            <?php
                                            $dados_estado = DBRead('', 'tb_estado', "WHERE id_estado != '99' ORDER BY sigla ASC");
                                            if ($dados_estado) {
                                                foreach ($dados_estado as $conteudo) {
                                                    $id_select = $conteudo['id_estado'];
                                                    $nome_select = $conteudo['sigla'];
                                                    $selected = $id_estado == $id_select ? "selected" : "";
                                                    echo "<option value='$id_select'".$selected.">$nome_select</option>";
                                                }
                                            }                                      
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>            		
                		</div>
	            	</div>
	                <div class="panel-footer">
                        <div class="row">
                            <div id="panel_buttons" class="col-md-12" style="text-align: center">
                                <button class="btn btn-primary" name="gerar" id="gerar" value="1" type="submit" disabled><i class="fa fa-check"></i> Ok</button>
                                <input type="hidden" id="mostra_mapa" name="mostra_mapa" value="<?=$mostra_mapa?>">
                            </div>
                        </div>
                    </div>
	            </div>
	        </div>
	    </div>
	</form>
</div>
<div <?php if(!$gerar){echo 'style="display:none;"';}?>>
    <hr>
    <div id="map" style="width: 100%; height: 550px;"></div>
    <hr>
    <div class="row">
        <div class="col-md-12">            
            <div class="text-center" id="erros" style="color:red;"></div>
        </div>
    </div>
    <div class="row" id="row_clientes" style="display:none;">
        <div class="col-md-4 col-md-offset-4">            
            <table class="table table-hover">
                <thead> 
                    <tr> 
                        <th class='col-md-11'>Cliente</th>
                        <th class='text-center col-md-1'>Visualizar</th>
                    </tr>
                </thead>
                <tbody id='clientes'></tbody>
            </table>
        </div>
    </div>    
</div>

<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA2MWfvsSfB00spwyWm-0WNSGlJvF4dajs&callback=initMap">
</script>

<script>
     $('#accordionRelatorio').on('shown.bs.collapse', function(){
       $("#i_collapse").removeClass("fa fa-plus").addClass("fa fa-minus");
    });
    $('#accordionRelatorio').on('hidden.bs.collapse', function(){
       $("#i_collapse").removeClass("fa fa-minus").addClass("fa fa-plus");
    });

    function selectplano(cod_servico, id_plano){        
        id_plano  = '<?=$id_plano?>';
        $("select[name=id_plano]").html('<option value="">Carregando...</option>');
        $.post("/api/ajax?class=SelectPlano.php",
            {cod_servico: cod_servico,
            id_plano: id_plano,
            token: '<?= $request->token ?>'},
            function(valor){
                if(valor == '<option value="">Selecione um servi&ccedil;o!</option>'){                    
                    $("select[name=id_plano]").html('<option value="">Todos</option>');
                }else{
                    $("select[name=id_plano]").html('<option value="">Todos</option>'+valor);
                }
            }
        )        
    }

    $(document).on('change', 'select[name=cod_servico]', function(){
        selectplano($(this).val());
    });

    var contratos = <?=json_encode($dados_contrato)?>;
    var resultsMap = '';
    var geocoder = '';
    var cont = 0;
    var tempo = 1000;
    var mostra_mapa = $('#mostra_mapa').val();

	function initMap() {
        if(mostra_mapa == '1'){
            resultsMap = new google.maps.Map(document.getElementById('map'), {zoom: 4, center: {lat: -12.9922531, lng: -50.3483875}});
            geocoder = new google.maps.Geocoder();
            modalAguarde();
            chamaMarcaMapa();
        }else{
            $("#gerar").prop("disabled", false);
        }        
	}


    function marcaMapa(contrato, proximoChamaMarcaMapa){        
        var address = contrato.logradouro+', '+contrato.numero+', '+contrato.bairro+', '+contrato.nome_cidade+', '+contrato.estado+', '+contrato.cep;
        geocoder.geocode({'address': address}, function(results, status) {
            if (status === 'OK') {
                var marker = new google.maps.Marker({
                map: resultsMap,
                position: results[0].geometry.location,
                label: {
                    color: 'black',
                    fontWeight: 'bold',
                    text: contrato.nome_cliente,
                }
                });
                $("#clientes").append('<tr><td><a href="/api/iframe?token=<?php echo $request->token ?>&view=pessoa-form&alterar='+contrato.id_pessoa+'" target="_blank">'+contrato.nome_cliente+'</a></td><td class="text-center"><a href="#" onclick="mostraPonto'+results[0].geometry.location+'"><i class="fa fa-map-marker"></i></a></td></tr>');
                $("#row_clientes").show();
            } else if (status === 'OVER_QUERY_LIMIT') {
                cont--;
                tempo+=1000;
            } else {
                $("#erros").append('<p><strong>Não foi possível localizar <a href="/api/iframe?token=<?php echo $request->token ?>&view=pessoa-form&alterar='+contrato.id_pessoa+'" target="_blank">' + contrato.nome_cliente + '</a>, verifique o endereço!</strong></p>');
            }
        });
        proximoChamaMarcaMapa();
    }    

    function chamaMarcaMapa(){
        if(cont < contratos.length){  
            setTimeout('marcaMapa(contratos['+cont+'],chamaMarcaMapa)', tempo);
            cont++;
        }else{
            $('#modal_aguarde').modal('hide');
            $("#gerar").prop("disabled", false);            
        }
    }

    function mostraPonto(lat,lng){
        resultsMap.setCenter({lat:lat,lng:lng});
        resultsMap.setZoom(16);
    }    
</script>