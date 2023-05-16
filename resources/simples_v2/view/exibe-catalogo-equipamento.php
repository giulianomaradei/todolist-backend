<?php
require_once(__DIR__."/../class/System.php");
$id_contrato_plano_pessoa = $_GET['contrato'];
// $id_contrato_plano_pessoa = 267;
?>
<style>
.conteudo-editor img{
    max-width: 100% !important;
    max-height: 100% !important;
}
#myBtn {
    display: none;
    position: fixed;
    bottom: 20px;
    right: 30px;
    z-index: 99;
    font-size: 15px;
    border: none;
    outline: none;
    color: white;
    cursor: pointer;
    padding: 15px;
    border-radius: 4px;
}
</style>
<script type="text/javascript">
    $(document).ready(function() {
        document.title = 'Simples V2 - Manual';
    });
</script>
<div class="container-fluid">
	<div class="row">
        <div class="col-md-12">
            <div class="panel panel-primary painel-quadro-informativo">
            	<?php 
                    $empresa = DBRead('', 'tb_contrato_plano_pessoa a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_contrato_plano_pessoa = '".$id_contrato_plano_pessoa."'");
                ?>
                <div class="panel-heading clearfix">
            		<div class="row">
	                    <h3 class="panel-title pull-center text-center col-md-12">Catálogo de Equipamentos: <strong>
						<?php echo $empresa[0]['nome'];
						
						if($empresa[0]['nome_contrato']){
							echo " (". $empresa[0]['nome_contrato'] .")";
						}

						?>
						</strong></h3>
	                </div>
                </div>
                <div class="panel-body" style="padding-bottom: 0;">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group has-feedback">
                                <label class="control-label sr-only">Hidden label</label>
                                <input class="form-control" type="text" name="nome" id="nome" onKeyUp="call_busca_ajax();" placeholder="Informe o equipamento..." autocomplete="off" autofocus>
                                <span class="glyphicon glyphicon-search form-control-feedback"></span>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-12">
                            <div id="resultado_busca"></div>
                        </div>
                    </div>
                    
				</div>
            </div>
        </div>
    </div>
</div>

<button class="btn btn-primary" onclick="topFunction()" id="myBtn" title="Voltar para o ínicio">
	<i class="fa fa-arrow-up"></i>	Voltar para o início
</button>

<script>
// When the user scrolls down 20px from the top of the document, show the button
window.onscroll = function() {scrollFunction()};

function scrollFunction() {
  if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
    document.getElementById("myBtn").style.display = "block";
  } else {
    document.getElementById("myBtn").style.display = "none";
  }
}

// When the user clicks on the button, scroll to the top of the document
function topFunction() {
  //document.body.scrollTop = 0;
  //document.documentElement.scrollTop = 0;

  $("html, body").animate({scrollTop: 0}, 300);
}

function call_busca_ajax(pagina){
        var inicia_busca = 1;
        var nome = $('#nome').val();
        if (nome.length < inicia_busca && nome.length >=1){
            return false;
        }
        
        var parametros = {
            'nome': nome,
            'id_contrato_plano_pessoa': '<?=$id_contrato_plano_pessoa?>'
        };
        busca_ajax('<?= $request->token ?>' , 'ExibeCatalogoEquipamentoBusca', 'resultado_busca', parametros);
    }

    $(document).on('click', '.troca_pag', function() {
        call_busca_ajax($(this).attr('atr-pagina'));
    });

    call_busca_ajax();
</script>