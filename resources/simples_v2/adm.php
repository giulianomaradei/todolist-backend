<?php
require_once(__DIR__."/class/System.php");

function verificaPagina($view, $perfil){
	$dados = DBRead('', 'tb_pagina_sistema', "WHERE nome_view = '$view'");
	if($dados){
		$dados = DBRead('', 'tb_pagina_sistema_perfil a', "INNER JOIN tb_pagina_sistema b ON a.id_pagina_sistema = b.id_pagina_sistema WHERE b.nome_view = '$view' AND a.id_perfil_sistema = '$perfil'");
		if($dados){
			return 3;
		} else {
			return 2;
		}
	} else {
		return 1;
	}
}

$view = (isset($_GET['view'])) ? $view = $_GET['view'] : '';
$id_usuario = $request->user()->id_usuario;
$dados = DBRead('', 'tb_usuario', "WHERE id_usuario = '$id_usuario'");

$email_usuario = $dados[0]['email'];
$perfil_usuario = $dados[0]['id_perfil_sistema'];

$iframe = (isset($_GET['iframe'])) ? 1 : 0;

?>
<!DOCTYPE html>
<html lang="pt-br">
	<head>
    	<meta charset="utf-8">
    	<meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Simples V2</title>
        <link rel="shortcut icon" href="/inc/img/icon.png" type="image/x-icon">
		<link rel="stylesheet" href="/inc/css/bootstrap.min.css" media="all">
		<link rel="stylesheet" href="/inc/css/bootstrap-theme.min.css" media="all">
		<link rel="stylesheet" href="/inc/css/dropdown-submenu.css" media="all">
        <link href="/inc/font-awesome/css/all.min.css" rel="stylesheet">
        <link href="/inc/font-awesome/css/v4-shims.min.css" rel="stylesheet">
        <link rel="stylesheet" href="/inc/font-awesome/css/font-awesome-animation.min.css" media="all">
		<link rel="stylesheet" href="/inc/css/jquery-ui/jquery-ui.min.css" media="all">
		<link rel="stylesheet" href="/inc/css/system.css" media="all">
		<link rel="stylesheet" href="/inc/css/select2.min.css" media="all">

		<script src="/inc/js/jquery-2.2.1.min.js"></script>
		<script src="/inc/js/jquery-ui.min.js"></script>
		<script src="/inc/js/jquery.mask.min.js"></script>
		<script src="/inc/js/jquery.maskMoney.min.js"></script>
		<script src="/inc/js/bootstrap.min.js"></script>
		<script src="/inc/js/dropdown-submenu.js"></script>
		<script src="/inc/js/system.js"></script>
		<script src="/inc/js/select2.min.js"></script>
		<style>
			body {
				padding-top: 0px !important;
			}
		</style>
  </head>
    <body>
        <?php

		echo '<div class="container-fluid">';
		$verifica_pagina = verificaPagina($view, $perfil_usuario);
		if ($verifica_pagina != 2) {
			if ($verifica_pagina != 1) {
				if(file_exists(__DIR__."/view/$view.php")){
                    include_once (__DIR__."/view/$view.php");
                } else {
                    echo "<div class=\"container-fluid text-center\"><div class=\"alert alert-warning\"><i class=\"fa fa-exclamation-triangle\" aria-hidden=\"true\"></i> Ops! Arquivo da página não encontrado!</div></div>";
                }
			} else {
				echo "<div class=\"container-fluid text-center\"><div class=\"alert alert-warning\"><i class=\"fa fa-exclamation-triangle\" aria-hidden=\"true\"></i> Ops! Página não encontrada!</div></div>";
			}
		} else {
			echo "<div class=\"container-fluid text-center\"><div class=\"alert alert-danger\"><i class=\"fa fa-ban\" aria-hidden=\"true\"></i> Ops! Você não tem permissão de acesso!</div></div>";
		}
		echo '</div>';
		?>
		<script>
            $(document).ready(function() {
                configuraDatepicker();
                configuraMascaras();
                $('.js-select2').select2();
            });
            $(document).on('click','.a_modalAguarde',function(){
                modalAguarde();
            });
            <?php

            if(!$iframe){
				$primeiro_dia = new DateTime(getDataHora('data'));
    			$primeiro_dia->modify('first day of this month');

				$dados = DBRead('', 'tb_horarios_escala', "WHERE id_usuario = '$id_usuario' AND data_inicial = '".$primeiro_dia->format('Y-m-d')."' ");

				if($dados){
				?>

					var verifica_liberacao_intervalo = function(){
						$.ajax({
							cache: false,
							type: "POST",
							data: {
								acao: 'verifica_liberacao',
								token: '<?php echo $request->bearerToken(); ?>',
								class: 'Intervalo.php'
							},
							url:'/api/ajax/',
							success: function(data){
								if(data == 1){
                                    $('body').append('<div class="modal fade" id="modal_intervalo" tabindex="-1" role="dialog"><div class="modal-dialog" role="document"><div class="modal-content"><div class="modal-header"><h4 class="modal-title"><i class="fa fa-coffee"></i> Intervalo!</h4></div><div class="modal-body"><span>UHUULLL <i class="fa fa-child" aria-hidden="true"></i><br>Após concluir as suas atividades você está liberado para o intervalo!!!<br>Bom descanso!!!</span></div><div class="modal-footer"><button class="btn btn-primary" type="button" data-dismiss="modal">Ok</button></div></div></div></div>');
                                    $('#modal_intervalo').modal({backdrop: 'static', keyboard: false});
                                    $('#modal_intervalo').modal('show');
								}
							}
						});
						setTimeout(function(){ verifica_liberacao_intervalo(); },5000);
					};
					verifica_liberacao_intervalo();

			<?php
                }
            }
            ?>
        </script>
	</body>
</html>