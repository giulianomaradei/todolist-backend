<?php
require_once(__DIR__."/../class/System.php");
$id_contrato_plano_pessoa = $_GET['contrato'];

$dados = DBRead('', 'tb_quadro_informativo_historico a', "INNER JOIN tb_usuario b ON a.id_usuario = b.id_usuario INNER JOIN tb_pessoa c ON b.id_pessoa = c.id_pessoa INNER JOIN tb_quadro_informativo_modulo d ON a.id_quadro_informativo_modulo = d.id_quadro_informativo_modulo WHERE id_contrato_plano_pessoa = $id_contrato_plano_pessoa AND a.tipo = 2 ORDER BY id_quadro_informativo_historico DESC limit 1", "a.*, c.nome, d.nome as modulo");

if ($dados) {
  $ultima_alteracao = "feita por ".$dados[0]['nome']." na data de ".converteDataHora($dados[0]['data_hora'])." - ".$dados[0]['modulo'];

} else {
  $ultima_alteracao = "Não consta"; 
}


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
                    <div class="col-md-6">
                      <h3 class="panel-title pull-right col-md-12">Manual: <strong>
                      <?php echo $empresa[0]['nome'];
                      
                      if($empresa[0]['nome_contrato']){
                        echo " (". $empresa[0]['nome_contrato'] .")";
                      }

                      ?>
                      </strong></h3>
                    </div>
                    <div class="col-md-6">
                      <h3 class="panel-title pull-right">Última alteração: <?= $ultima_alteracao ?> </h3>
                    </div>
                  </div>
                </div>
                <div class="panel-body" style="padding-bottom: 0;">
                    <?php
                      $dados = DBRead('', 'tb_manual_contrato', "WHERE id_contrato_plano_pessoa = '".$id_contrato_plano_pessoa."'");
                    if($dados):
                    ?>                    
                    <?php                                
                    echo "<div class='row'>";
                        echo "<div class='col-md-12 conteudo-editor'>";
                            echo $dados[0]['manual'];
                        echo "</div>";
                    echo "</div>";
                    ?>
                    <?php 
                    endif; 
                    ?>
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
</script>