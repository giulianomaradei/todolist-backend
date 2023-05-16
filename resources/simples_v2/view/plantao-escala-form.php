<?php
require_once(__DIR__."/../class/System.php");
$mes = (!empty($_POST['mes'])) ? $_POST['mes'] : '';
$ano = (!empty($_POST['ano'])) ? $_POST['ano'] : '';

$id_plantonista_redes_mes = (!empty($_POST['id_plantonista_redes_mes'])) ? $_POST['id_plantonista_redes_mes'] : '';

if($id_plantonista_redes_mes){
    $dados_id_plantonista_redes_mes = DBRead('', 'tb_plantonista_redes_mes', "WHERE id_plantonista_redes_mes = '".$id_plantonista_redes_mes."' ");
    $data_referencia = explode('-', $dados_id_plantonista_redes_mes[0]['data_referencia']);
    $mes = $data_referencia[1];
    $ano = $data_referencia[0];

}

$flag_retorno = 0;
if(!$mes){
   $alert = ('Ops! Ocorreu um erro insesperado!','d');
   $flag_retorno = 1;
   $mes = 01;
   $ano = 2000;
}

$primeiro_data = new DateTime($ano."-".$mes."-01");
$primeiro_data->modify('first day of this month');
$primeiro_data = $primeiro_data->format('Y-m-d');

$ultimo_data = new DateTime($ano."-".$mes."-01");
$ultimo_data->modify('last day of this month');
$ultimo_data = $ultimo_data->format('Y-m-d');

//echo $primeiro_data."______".$ultimo_data."<br>";

$diasemana = array(
    'Domingo', 
    'Segunda', 
    'Terça', 
    'Quarta', 
    'Quinta', 
    'Sexta', 
    'Sábado');

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

// Varivel que recebe o dia da semana (0 = Domingo, 1 = Segunda ...)
$diasemana_numero_primeiro_data = date('w', strtotime($primeiro_data));
$diasemana_numero_ultimo_data = date('w', strtotime($ultimo_data));

//echo $diasemana[$diasemana_numero_primeiro_data]."______".$diasemana[$diasemana_numero_ultimo_data]."<hr>";

$primeiro_dia = explode('-', $primeiro_data);
$primeiro_dia = $primeiro_dia[2];
//echo $primeiro_dia."<br>";

$ultimo_dia = explode('-', $ultimo_data);
$ultimo_dia = $ultimo_dia[2];
//echo $ultimo_dia."<hr>";

$dia = $primeiro_dia;
$numero_dia = 0;
$flag_dia = 0;


if($id_plantonista_redes_mes){

    $tituloPainel = 'Alterar';
	$operacao = 'alterar';
	$id = $id_plantonista_redes_mes;    
	$valor_diaria = $dados_id_plantonista_redes_mes[0]['valor_diaria'];    
	$porcentagem_comissao = $dados_id_plantonista_redes_mes[0]['porcentagem_comissao'];    
	
}else{

    $dados_referencia = DBRead('', 'tb_plantonista_redes_mes', "WHERE data_referencia = '".$primeiro_data."' ");
    if($dados_referencia){
        $alert = ('Ops! Já existe escala para a data de referência!','d');
        $flag_retorno = 1;
    }

	$tituloPainel = 'Inserir';
	$operacao = 'inserir';
    $id = 1;
	$valor_diaria = '';
	$porcentagem_comissao = '';
}
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    <h3 class="panel-title text-left pull-left"><?=$tituloPainel?> Escalas Referente a <?=$meses[$mes]?> de <?=$ano?>:</h3>
                    <?php 
                    if($id_plantonista_redes_mes){
                        
                        echo "<div class=\"panel-title text-right pull-right\"><a href=\"/api/ajax?class=PlantaoEscala.php?excluir=$id_plantonista_redes_mes&token=". $request->token ."\" onclick=\"if(!confirm('Tem certeza que deseja excluir o registro?')){ return false; }else{ modalAguarde(); }\"><button class=\"btn btn-xs btn-danger\"><i class=\"fa fa-trash\"></i> Excluir</button></a></div>";
                    }
                        
                    ?>
                </div>
                <form method="post" action="/api/ajax?class=PlantaoEscala.php" id="plantao_escala_form" style="margin-bottom: 0;">
		            <input type="hidden" name="token" value="<?php echo $request->token ?>">
                    <div class="panel-body">
                    <input type="hidden" name="data_referencia" id="data_referencia" value="<?=$primeiro_data?>" />

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>*Valor da Diária (R$):</label>
                                <input class="form-control input-sm money" name="valor_diaria" id="valor_diaria" required value="<?=$valor_diaria?>" type="text" autocomplete="off"/>                                    
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>*Porcentagem da Comissão de Atendimentos (%):</label>
                                <input class="form-control input-sm number_int" name="porcentagem_comissao" id="porcentagem_comissao" required value="<?=$porcentagem_comissao?>" type="text" autocomplete="off"/>                                    
                            </div>
                        </div>
                    </div>
                        <?php
                            echo '
                            <div class="table-responsive">
                                <table class="table table-bordered" style="margin-bottom:0;">
                                    <thead>
                                        <tr style="vertical-align: middle;">
                                            <th class="text-center">Domingo</th>
                                            <th class="text-center">Segunda-feira</th>
                                            <th class="text-center">Terça-feira</th>	
                                            <th class="text-center">Quarta-feira</th>
                                            <th class="text-center">Quinta-feira</th>
                                            <th class="text-center">Sexta-feira</th>
                                            <th class="text-center">Sábado</th>			
                                        </tr>
                                    </thead>
                                    <tbody>';
                                while ($dia <= $ultimo_dia){
                                    $diasemana_dia = date('w', strtotime($ano."-".$mes."-".$dia));
                                    if($diasemana_dia == 0){
                                        echo '<tr>';
                                    }
                                    if($numero_dia != $diasemana_dia && $flag_dia == 0){
                                        echo 
                                        '<td style="background-color: #f5f5f5;">
                                            <div class="form-group">
                                                
                                                <label></label>
                                            
                                            </div>
                                        </td>';
                                        $numero_dia++;
                                    }else{
                                        $flag_dia = 1;

                                        $dados_usuario = DBRead('', 'tb_perfil_sistema a', "INNER JOIN tb_usuario b ON a.id_perfil_sistema = b.id_perfil_sistema INNER JOIN tb_pessoa c ON b.id_pessoa = c.id_pessoa WHERE (a.id_perfil_sistema = 6 OR a.id_perfil_sistema = 26) AND b.status = 1 ORDER BY c.nome ASC","b.id_usuario, c.nome");

                                        echo '<input type="hidden" name="dia[]" value="'.$ano.'-'.$mes.'-'.sprintf('%02d', $dia).'" />';
                                        // <label>'.sprintf('%02d', $dia).'/'.$mes.'/'.$ano.'<br>'.$diasemana[$diasemana_dia].'</label>

                                        echo 
                                        '<td>
                                            <div class="form-group text-center">
                                                
                                                <label>'.sprintf('%02d', $dia).'/'.$mes.'/'.$ano.'</label>

                                                <select name="plantonistas[]" class="form-control" size = "'.sizeof($dados_usuario).'" required>';
                                                    
                                                                                
                                                    if ($dados_usuario) {
                                                        
                                                        $dados_select_usuario = DBRead('', 'tb_plantonista_redes_mes_dia', "WHERE id_plantonista_redes_mes = '".$id_plantonista_redes_mes."' AND data = '".$ano."-".$mes."-".sprintf('%02d', $dia)."' ");

                                                        $sel_usuario[$dados_select_usuario[0]['id_usuario']] = 'selected';
                                                        foreach ($dados_usuario as $conteudo_usuario) {
                                                            echo "<option value='".$conteudo_usuario['id_usuario']."' ".$sel_usuario[$conteudo_usuario['id_usuario']].">".$conteudo_usuario['nome']."</option>";
                                                        }
                                                        
                                                        unset($sel_usuario);
                                                    }

                                                echo
                                                '</select>
                                            </div>
                                        </td>';

                                        $dia++;
                                    
                                        if($diasemana_dia == 6){
                                            echo '</tr">';
                                        }
                                    }
                                }
                                $flag_ultimo_dia = $diasemana_numero_ultimo_data;
                                if($flag_ultimo_dia != 6){
                                    while ($flag_ultimo_dia < 6){
                                        echo 
                                        '<td style="background-color: #f5f5f5;">
                                            <div class="form-group">
                                                
                                                <label></label>
                                            
                                            </div>
                                        </td>';
                                        $flag_ultimo_dia++;
                                    }   
                                    echo '</tr">';
                                }

                                echo '<tbody>
                                </table>
                            </div>';
                        ?>
                                            
                    </div>
                    <div class="panel-footer">
                        <div class="row">
                            <div class="col-md-12" style="text-align: center">
                                <input type="hidden" id="operacao" value="<?=$id;?>" name="<?=$operacao;?>" />
                                    <button class='btn btn-primary' name='salvar' id='ok' type='submit'><i class='fa fa-floppy-o'></i> Salvar</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        var flag_retorno = <?=$flag_retorno?>;
        if(flag_retorno == 1){
            window.location.href = "/api/iframe?token=<?php echo $request->token ?>&view=plantao-escala-busca";
        }
    });

    $(document).on('submit', '#plantao_escala_form', function(){
        
        // return false;

        modalAguarde();
    });
</script>