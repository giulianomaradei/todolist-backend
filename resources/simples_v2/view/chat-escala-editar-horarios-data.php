<?php
require_once(__DIR__."/../class/System.php");

if (isset($_GET['novo'])) {
    $id_usuario = (int)$_GET['novo'];
	$nome = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.id_usuario = '".$id_usuario."' ");
	
    $dados_horarios_escala = DBRead('', 'tb_horarios_escala', "WHERE id_usuario = '".$id_usuario."' AND id_horarios_escala NOT IN (SELECT id_horarios_escala FROM tb_chat_horarios_escala) ORDER BY data_inicial DESC ");

    $operacao = 'novo';
    
    $dados_meses = array(
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

}else{
    echo "<p class='alert alert-danger' style='text-align: center'>";
        echo "Nenhum usuário selecionado!";
    echo "</p>";
    die();
}

?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    <h3 class="panel-title text-left pull-left">Selecione a escala do(a) <?= $nome[0]['nome'] ?>:</h3>
                </div>
                <form method="post" action="/api/ajax?class=ChatEscalaHorarios.php" id="escala_horarios_data_form" style="margin-bottom: 0;">
                    <input type="hidden" name="token" value="<?php echo $request->token ?>">

                    <div class="panel-body" style="padding-bottom: 0;">                  
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Escala Mês/Ano:</label>
                                    <select class="form-control input-sm" name="id_escala_horarios_data" id="id_escala_horarios_data" autofocus>
                                    <?php
                                        if ($dados_horarios_escala) {
                                            $cont = 0;
                                            foreach ($dados_horarios_escala as $conteudo_horarios_escala) {
                                                $sel_horarios_escala = '';
                                                if($cont == 0){
                                                    $sel_horarios_escala = 'selected';
                                                }
                                                $mes = explode('-', $conteudo_horarios_escala['data_inicial']);
                                                $ano = $mes[0];
                                                $mes = $mes[1];
                                                echo "<option value='".$conteudo_horarios_escala['id_horarios_escala']."' ".$sel_horarios_escala.">".$dados_meses[$mes]."/".$ano."</option>";
                                                $cont++;
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>  
                        </div>    

                    </div>
                    <div class="panel-footer">
                        <div class="row">
                            <div class="col-md-12" style="text-align: center">
                                <input type="hidden" id="redirect_data" value="redirect_data" name="redirect_data" />
                                <button class="btn btn-primary" id="ok" type="submit"><i class="fa fa-floppy-o"></i> Salvar</button>
                            </div>
                        </div>
                    </div>
                </form>
                </div>
            </div>
        </div>
    </div>
</div>     
<script>
    $(document).on('submit', '#escala_horarios_data_form', function () {
        modalAguarde();
    });
</script>