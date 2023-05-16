<?php require_once(__DIR__."/System.php");

$imagens = DBRead('', 'tb_telao_marketing', "");

foreach ($imagens as $imagem){ ?>

    <img src="<?= $imagem['link_imagem'] ?>" id='foto'>

<?php } ?>
