<?php
require_once(__DIR__."/../class/System.php");

if (isset($_GET['alterar']) || isset($_GET['clonar'])) {
    
    if(isset($_GET['alterar'])){
        $tituloPainel = 'Alterar';
        $operacao = 'alterar';
        $id = (int) $_GET['alterar'];
    }else{
        $tituloPainel = 'Clonar';
        $operacao = 'clonar';
        $id = (int) $_GET['clonar'];       
    }
    
	$dados = DBRead('', 'tb_meta', "WHERE id_meta = $id");
    $id_metas_individuais = $dados[0]['id_meta'];
    $nome = $dados[0]['nome'];
    $qtd_ligacoes_hora = $dados[0]['qtd_ligacoes_hora'];
    $data_de = $dados[0]['data_de'];
    $data_ate = $dados[0]['data_ate'];
    $status = $dados[0]['status'];
    $tipo = $dados[0]['tipo'];
    $ligacoes_ativas = $dados[0]['ligacoes_ativas'];
    $tempo_medio_atendimento = $dados[0]['tempo_medio_atendimento'];
    $nota_media = $dados[0]['nota_media'];
    $porcentagem_ligacao_nota = $dados[0]['porcentagem_ligacao_nota'];
    $erros_reclamacoes = $dados[0]['erros_reclamacoes'];
    $faltas_justificadas = $dados[0]['faltas_justificadas'];
    $absenteismo = $dados[0]['absenteismo'];
    $pausa_registro = $dados[0]['pausa_registro'];
    $monitoria = $dados[0]['monitoria'];
    $resolucao = $dados[0]['resolucao'];
    $atendimentos_hora = $dados[0]['atendimentos_hora'];

    $total_atendimentos = $dados[0]['total_atendimentos'];
    $qtd_ajudas = $dados[0]['qtd_ajudas'];
    $total_atendimentos_meio_turno = $dados[0]['total_atendimentos_meio_turno'];
    $qtd_ajudas_meio_turno = $dados[0]['qtd_ajudas_meio_turno'];

    if($dados[0]['lider_direto']){
        $lider_direto = $dados[0]['lider_direto'];
    }

    if($dados[0]['status_tempo_medio_atendimento'] == 1){
        $checked_tma = 'checked="true"';
        $status_tempo_medio_atendimento = "value = '1'";
    }else{
        $disabled_tma = 'disabled';
        $tempo_medio_atendimento = '';
    }

    if($dados[0]['status_nota_media'] == 1){
        $checked_nota = 'checked="true"';
        $status_nota_media = "value = '1'";
    }else{
        $disabled_nota = 'disabled';
        $nota_media = '';
    }

    if($dados[0]['status_porcentagem_ligacao_nota'] == 1){
        $checked_porcentagem = 'checked="true"';
        $status_porcentagem_ligacao_nota = "value = '1'";
    }else{
        $disabled_porcentagem = 'disabled';
        $porcentagem_ligacao_nota = '';
    }

    if($dados[0]['status_erros_reclamacoes'] == 1){
        $checked_erros_reclamacoes = 'checked="true"';
        $status_erros_reclamacoes = "value = '1'";
    }else{
        $disabled_erros_reclamacoes = 'disabled';
        $erros_reclamacoes = '';
    }

    if($dados[0]['status_faltas_justificadas'] == 1){
        $checked_faltas = 'checked="true"';
        $status_faltas_justificadas = "value = '1'";
    }else{
        $disabled_faltas = 'disabled';
        $faltas_justificadas = '';
    }

    if($dados[0]['status_absenteismo'] == 1){
        $checked_absenteismo = 'checked="true"';
        $status_absenteismo = "value = '1'";
    }else{
        $disabled_absenteismo = 'disabled';
        $absenteismo = '';
    }

    if($dados[0]['status_pausa_registro'] == 1){
        $checked_pausa_registro = 'checked="true"';
        $status_pausa_registro = "value = '1'";
    }else{
        $disabled_pausa_registro = 'disabled';
        $pausa_registro = '';
    }

    if($dados[0]['status_monitoria'] == 1){
        $checked_monitoria = 'checked="true"';
        $status_monitoria = "value = '1'";
    }else{
        $disabled_monitoria = 'disabled';
        $monitoria = '';
    }

    if($dados[0]['status_resolucao'] == 1){
        $checked_resolucao = 'checked="true"';
        $status_resolucao = "value = '1'";
    }else{
        $disabled_resolucao = 'disabled';
        $resolucao = '';
    }

    if($dados[0]['status_atendimentos_hora'] == 1){
        $checked_atendimentos_hora = 'checked="true"';
        $status_atendimentos_hora = "value = '1'";
    }else{
        $disabled_atendimentos_hora = 'disabled';
        $atendimentos_hora = '';
    }

    if($dados[0]['status_total_atendimentos'] == 1){
        $checked_total_atendimentos = 'checked="true"';
        $status_total_atendimentos = "value = '1'";
    }else{
        $disabled_total_atendimentos = 'disabled';
        $total_atendimentos = '';
    }

    if($dados[0]['status_qtd_ajudas'] == 1){
        $checked_qtd_ajudas = 'checked="true"';
        $status_qtd_ajudas = "value = '1'";
    }else{
        $disabled_qtd_ajudas = 'disabled';
        $qtd_ajudas = '';
    }

    if($dados[0]['status_total_atendimentos_meio_turno'] == 1){
        $checked_total_atendimentos_meio_turno = 'checked="true"';
        $status_total_atendimentos_meio_turno = "value = '1'";
    }else{
        $disabled_total_atendimentos_meio_turno = 'disabled';
        $total_atendimentos_meio_turno = '';
    }

    if($dados[0]['status_qtd_ajudas_meio_turno'] == 1){
        $checked_qtd_ajudas_meio_turno = 'checked="true"';
        $status_qtd_ajudas_meio_turno = "value = '1'";
    }else{
        $disabled_qtd_ajudas_meio_turno = 'disabled';
        $qtd_ajudas_meio_turno = '';
    }

}else {
	$tituloPainel = 'Inserir';
	$operacao = 'inserir';
	$id = 1;
	$nome = '';
    $qtd_contratada = '';
    $tempo_medio_atendimento = '';
    $nota_media = '';
    $porcentagem_ligacao_nota = '';
    $ligacoes_ativas = '';
    $erros_reclamacoes = '';
    $faltas_justificadas = '';
    $absenteismo = '';
    $pausa_registro = '';
    $monitoria = '';
    $resolucao = '';
    $atendimentos_hora = '';
    $tipo = 1;
    $status = 1;

    $total_atendimentos = '';
    $qtd_ajudas = '';
    $total_atendimentos_meio_turno = '';
    $qtd_ajudas_meio_turno = '';

    $checked_tma = '';
    $checked_nota = '';
    $checked_porcentagem = '';
    $checked_erros_reclamacoes = '';
    $checked_faltas = '';
    $checked_absenteismo = '';
    $checked_pausa_registro = '';
    $checked_monitoria = '';
    $checked_resolucao = '';
    $checked_atendimentos_hora = '';

    $checked_total_atendimentos = '';
    $checked_qtd_ajudas = '';
    $checked_total_atendimentos_meio_turno = '';
    $checked_qtd_ajudas_meio_turno = '';

    $disabled_tma = 'disabled';
    $disabled_nota = 'disabled';
    $disabled_porcentagem = 'disabled';
    $disabled_erros_reclamacoes = 'disabled';
    $disabled_faltas = 'disabled';
    $disabled_absenteismo = 'disabled';
    $disabled_pausa_registro = 'disabled';
    $disabled_monitoria = 'disabled';
    $disabled_resolucao = 'disabled';
    $disabled_atendimentos_hora = 'disabled';    

    $disabled_total_atendimentos = 'disabled';    
    $disabled_qtd_ajudas = 'disabled';    
    $disabled_total_atendimentos_meio_turno = 'disabled';    
    $disabled_qtd_ajudas_meio_turno = 'disabled';    
}

if($tipo == '1'){
    $display_row_lider = 'style="display:none;"';  
    $display_row_total_atendimentos_integral = '';  
    $display_row_total_atendimentos_meio_turno = '';  
    $display_row_qtd_ajuda_integral = '';  

    $class_row_total_atendimentos_integral = 'col-lg-6';
}else if($tipo == '2'){
    $display_row_lider = '';
    $display_row_total_atendimentos_integral = '';  
    $display_row_total_atendimentos_meio_turno = 'style="display:none;"';   
    $display_row_qtd_ajuda_integral = 'style="display:none;"';  

    $class_row_total_atendimentos_integral = 'col-lg-12';
}else if($tipo == '3'){
    $display_row_lider = 'style="display:none;"';
    $display_row_total_atendimentos_integral = '';  
    $display_row_total_atendimentos_meio_turno = 'style="display:none;"';   
    $display_row_qtd_ajuda_integral = 'style="display:none;"';  

    $class_row_total_atendimentos_integral = 'col-lg-12';
}
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    <h3 class="panel-title pull-left text-left"><?=$tituloPainel?> meta:</h3>
                </div>
                <form method="post" action="/api/ajax?class=Metas.php" id="metas_individuais_form" style="margin-bottom: 0;">
		            <input type="hidden" name="token" value="<?php echo $request->token ?>">
                    <div class="panel-body" style="padding-bottom: 0;">
                        <div class="tab-content">
                            <div class="row"> 
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="nome">*Nome:</label>
                                        <select id="nome" name="nome" class="form-control">
                                            <?php
                                            $nomes = array(
                                                "Bronze" => "Bronze",
                                                "Silver" => "Silver",
                                                "Gold" => "Gold",
                                                "Diamond" => "Diamond"                                                
                                            );
                                            foreach ($nomes as $num => $valor) {
                                                $selected = $nome == $num ? "selected" : "";
                                                echo "<option value='".$num."'".$selected.">".$valor."</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>*Tipo:</label>
                                        <select class="form-control" id="tipo" name="tipo">
                                            <!--<option value=''></option>-->
                                            <option value='1' <?php if ($tipo == '1') {echo 'selected';}?>>Individual</option>
                                            <option value='2' <?php if ($tipo == '2') {echo 'selected';}?>>Equipe</option>
                                            <option value='3' <?php if ($tipo == '3') {echo 'selected';}?>>Geral</option>
                                        </select>
                                    </div>
                                </div>                               
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>*Status:</label>
                                        <select class="form-control" id="status" name="status">
                                            <option value='1' <?php if ($status == '1') {echo 'selected';}?>>Ativo</option>
                                            <option value='0' <?php if ($status == '0') {echo 'selected';}?>>Inativo</option>
                                        </select>
                                    </div>
                                </div>
                            </div><!-- /.row -->

                            <div class="row" id="row_lider" <?=$display_row_lider?>>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>*Líder Direto:</label>
                                        <select name="lider_direto" class="form-control">
                                                <?php
                                                // $dados_lider = DBRead('', 'tb_usuario a', "INNER JOIN tb_usuario b ON a.lider_direto = b.id_usuario INNER JOIN tb_pessoa c ON b.id_pessoa = c.id_pessoa WHERE a.lider_direto AND a.status = '1' AND (b.id_perfil_sistema = '23' OR b.id_perfil_sistema = '13' OR b.id_perfil_sistema = '12') GROUP BY  a.lider_direto, c.nome ORDER BY c.nome ASC", "a.lider_direto, c.nome");
                                                $dados_lider = DBRead('', 'tb_usuario a', "INNER JOIN tb_usuario b ON a.lider_direto = b.id_usuario INNER JOIN tb_pessoa c ON b.id_pessoa = c.id_pessoa WHERE a.lider_direto AND a.status = '1' AND (b.id_perfil_sistema = '15') GROUP BY a.lider_direto, c.nome ORDER BY c.nome ASC", "a.lider_direto, c.nome");
                                                if ($dados_lider) {
                                                    foreach ($dados_lider as $conteudo_lider) {
                                                        $selected = $lider_direto == $conteudo_lider['lider_direto'] ? "selected" : "";
                                                        echo "<option value='" . $conteudo_lider['lider_direto'] . "' ".$selected.">" . $conteudo_lider['nome'] . "</option>";
                                                    }
                                                }
                                                ?>
                                        </select>
                                    </div>
                                </div>
                            </div><!-- /.row -->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>*Data inicial:</label>
                                        <input class="campos-agendar form-control date calendar" name="data_de" id="data_de" required value="<?= converteData($data_de) ?>" type="text" autocomplete="off"/>                                    
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>*Data final:</label>
                                        <input class="campos-agendar form-control date calendar" name="data_ate" required id="data_ate" value="<?= converteData($data_ate) ?>" type="text" autocomplete="off"/>                                    
                                    </div>
                                </div>
                            </div><!-- /.row -->

                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label>TMA (segundos):</label>
                                        <div class="input-group">
                                        <span class="input-group-addon">
                                            <input <?= $checked_tma?> type="checkbox" name="status_tempo_medio_atendimento" id="status_tempo_medio_atendimento" <?= $status_tempo_medio_atendimento?>>
                                        </span>
                                        <input name="tempo_medio_atendimento" <?= $disabled_tma?> id="tempo_medio_atendimento" type="text" class="form-control number_int" aria-label="..." value="<?=$tempo_medio_atendimento;?>">

                                        </div><!-- /input-group -->
                                    </div>
                                </div><!-- /.col-lg-4 -->
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label>Porcentagem de ligações com nota:</label>
                                        <div class="input-group">
                                        <span class="input-group-addon">
                                            <input <?= $checked_porcentagem?> type="checkbox" name="status_porcentagem_ligacao_nota" id="status_porcentagem_ligacao_nota" <?= $status_porcentagem_ligacao_nota?>>
                                        </span>
                                        <input name="porcentagem_ligacao_nota" <?= $disabled_porcentagem?> id="porcentagem_ligacao_nota" type="text" class="form-control number_float" aria-label="..." value="<?=$porcentagem_ligacao_nota;?>">
                                        </div><!-- /input-group -->
                                    </div>
                                </div><!-- /.col-lg-4 -->
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label>Nota média:</label>
                                        <div class="input-group">
                                        <span class="input-group-addon">
                                            <input <?= $checked_nota?> type="checkbox" name="status_nota_media" id="status_nota_media" <?= $status_nota_media?>>
                                        </span>
                                        <input name="nota_media" <?= $disabled_nota?> id="nota_media" type="text" class="form-control number_float" aria-label="..." value="<?=$nota_media;?>">
                                        </div><!-- /input-group -->
                                    </div>
                                </div><!-- /.col-lg-4 -->
                            </div><!-- /.row -->

                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label>Erros/Reclamações:</label>
                                        <div class="input-group">
                                        <span class="input-group-addon">
                                            <input <?= $checked_erros_reclamacoes?> type="checkbox" name="status_erros_reclamacoes" id="status_erros_reclamacoes" <?= $status_erros_reclamacoes?>>
                                        </span>
                                        <input name="erros_reclamacoes" <?= $disabled_erros_reclamacoes?> id="erros_reclamacoes" type="text" class="form-control number_int" aria-label="..." value="<?=$erros_reclamacoes;?>">
                                        </div><!-- /input-group -->
                                    </div>
                                </div><!-- /.col-lg-4 -->
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label>Monitoria:</label>
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <input <?= $checked_monitoria?> type="checkbox" name="status_monitoria" id="status_monitoria" <?= $status_monitoria?>>
                                            </span>
                                            <input name="monitoria" <?= $disabled_monitoria?> id="monitoria" type="text" class="form-control number_float" aria-label="..." value="<?=$monitoria;?>">
                                        </div><!-- /input-group -->
                                    </div>
                                </div><!-- /.col-lg-4 -->
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label>Pausa registro (minutos):</label>
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <input <?= $checked_pausa_registro?> type="checkbox" name="status_pausa_registro" id="status_pausa_registro" <?= $status_pausa_registro?>>
                                            </span>
                                            <input name="pausa_registro" <?= $disabled_pausa_registro?> id="pausa_registro" type="text" class="form-control number_int" aria-label="..." value="<?=$pausa_registro;?>">
                                        </div><!-- /input-group -->
                                    </div>
                                </div><!-- /.col-lg-4 -->                                
                            </div><!-- /.row -->

                            <div class="row">                               
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>Resolução (Diagnosticado + Resolvido):</label>
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <input <?= $checked_resolucao?> type="checkbox" name="status_resolucao" id="status_resolucao" <?= $status_resolucao?>>
                                            </span>
                                            <input name="resolucao" <?= $disabled_resolucao?> id="resolucao" type="text" class="form-control number_float" aria-label="..." value="<?=$resolucao;?>">
                                        </div><!-- /input-group -->
                                    </div>
                                </div><!-- /.col-lg-6 -->
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>Atendimentos por Hora:</label>
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <input <?= $checked_atendimentos_hora?> type="checkbox" name="status_atendimentos_hora" id="status_atendimentos_hora" <?= $status_atendimentos_hora?>>
                                            </span>
                                            <input name="atendimentos_hora" <?= $disabled_atendimentos_hora?> id="atendimentos_hora" type="text" class="form-control number_float" aria-label="..." value="<?=$atendimentos_hora;?>">
                                        </div><!-- /input-group -->
                                    </div>
                                </div><!-- /.col-lg-6 -->
                            </div><!-- /.row -->

                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>Faltas justificadas:</label>
                                        <div class="input-group">
                                        <span class="input-group-addon">
                                            <input <?= $checked_faltas?> type="checkbox" name="status_faltas_justificadas" id="status_faltas_justificadas" <?= $status_faltas_justificadas?>>
                                        </span>
                                        <input name="faltas_justificadas" <?= $disabled_faltas?> id="faltas_justificadas" type="text" class="form-control number_int" aria-label="..." value="<?=$faltas_justificadas;?>">
                                        </div><!-- /input-group -->
                                    </div>
                                </div><!-- /.col-lg-6 -->
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>Pontualidade:</label>
                                        <div class="input-group">
                                        <span class="input-group-addon">
                                            <input <?= $checked_absenteismo?> type="checkbox" name="status_absenteismo" id="status_absenteismo" <?= $status_absenteismo?>>
                                        </span>
                                        <input name="absenteismo" <?= $disabled_absenteismo?> id="absenteismo" type="text" class="form-control number_float" aria-label="..." value="<?=$absenteismo;?>">
                                        </div><!-- /input-group -->
                                    </div>
                                </div><!-- /.col-lg-6 -->
                            </div><!-- /.row -->

                            <div class="row" >
                                <div class="<?=$class_row_total_atendimentos_integral?>" id="row_total_atendimentos_integral" <?=$display_row_total_atendimentos_integral?>>
                                    <div class="form-group">
                                        <label>Total de atendimentos:</label>
                                        <div class="input-group">
                                        <span class="input-group-addon">
                                            <input <?= $checked_total_atendimentos?> type="checkbox" name="status_total_atendimentos" id="status_total_atendimentos" <?= $status_total_atendimentos?>>
                                        </span>
                                        <input name="total_atendimentos" <?= $disabled_total_atendimentos?> id="total_atendimentos" type="text" class="form-control number_int" aria-label="..." value="<?=$total_atendimentos;?>">
                                        </div><!-- /input-group -->
                                    </div>
                                </div><!-- /.col-lg-6 -->
                                <div class="col-lg-6" id="row_qtd_ajuda_integral" <?=$display_row_qtd_ajuda_integral?>>
                                    <div class="form-group">
                                        <label>Quantidade de Ajudas:</label>
                                        <div class="input-group">
                                        <span class="input-group-addon">
                                            <input <?= $checked_qtd_ajudas?> type="checkbox" name="status_qtd_ajudas" id="status_qtd_ajudas" <?= $status_qtd_ajudas?>>
                                        </span>
                                        <input name="qtd_ajudas" <?= $disabled_qtd_ajudas?> id="qtd_ajudas" type="text" class="form-control number_int" aria-label="..." value="<?=$qtd_ajudas;?>">
                                        </div><!-- /input-group -->
                                    </div>
                                </div><!-- /.col-lg-6 -->
                            </div><!-- /.row -->

                            <hr>

                            <div class="row" id="row_total_atendimentos_meio_turno" <?=$display_row_total_atendimentos_meio_turno?>>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>Meio turno:</label>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>Total de atendimentos:</label>
                                        <div class="input-group">
                                        <span class="input-group-addon">
                                            <input <?= $checked_total_atendimentos_meio_turno?> type="checkbox" name="status_total_atendimentos_meio_turno" id="status_total_atendimentos_meio_turno" <?= $status_total_atendimentos_meio_turno?>>
                                        </span>
                                        <input name="total_atendimentos_meio_turno" <?= $disabled_total_atendimentos_meio_turno?> id="total_atendimentos_meio_turno" type="text" class="form-control number_int" aria-label="..." value="<?=$total_atendimentos_meio_turno;?>">
                                        </div><!-- /input-group -->
                                    </div>
                                </div><!-- /.col-lg-6 -->
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>Quantidade de Ajudas:</label>
                                        <div class="input-group">
                                        <span class="input-group-addon">
                                            <input <?= $checked_qtd_ajudas_meio_turno?> type="checkbox" name="status_qtd_ajudas_meio_turno" id="status_qtd_ajudas_meio_turno" <?= $status_qtd_ajudas_meio_turno?>>
                                        </span>
                                        <input name="qtd_ajudas_meio_turno" <?= $disabled_qtd_ajudas_meio_turno?> id="qtd_ajudas_meio_turno" type="text" class="form-control number_int" aria-label="..." value="<?=$qtd_ajudas_meio_turno;?>">
                                        </div><!-- /input-group -->
                                    </div>
                                </div><!-- /.col-lg-6 -->
                            </div><!-- /.row -->

                        </div>     
                    </div> 
                    <div class="panel-footer">
                        <div class="row">
                            <div class="col-md-12" style="text-align: center">
                                <input type="hidden" id="operacao" value="<?=$id;?>" name="<?=$operacao;?>"/>
                                <button class="btn btn-primary" name="salvar" id="ok" type="submit"><i class="fa fa-floppy-o"></i> Salvar</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>

    $('#tipo').on('change',function(){
        tipo = $(this).val();
        if(tipo == 1){            
            $('#row_lider').hide();            
            $('#row_total_atendimentos_integral').show();            
            $('#row_total_atendimentos_meio_turno').show();    
            $('#row_qtd_ajuda_integral').show();  
            
            $("#row_total_atendimentos_integral").removeClass("col-lg-6");
            $("#row_total_atendimentos_integral").removeClass("col-lg-12");
            $("#row_total_atendimentos_integral").addClass("col-lg-6");
                    
        }else if(tipo == 2){
            $('#row_lider').show();            
            $('#row_total_atendimentos_integral').show();            
            $('#row_total_atendimentos_meio_turno').hide();    
            $('#row_qtd_ajuda_integral').hide();     
            
            $("#row_total_atendimentos_integral").removeClass("col-lg-6");
            $("#row_total_atendimentos_integral").removeClass("col-lg-12");
            $("#row_total_atendimentos_integral").addClass("col-lg-12");
 
        }else if(tipo == 3){
            $('#row_lider').hide();
            $('#row_total_atendimentos_integral').show();            
            $('#row_total_atendimentos_meio_turno').hide();      
            $('#row_qtd_ajuda_integral').hide();    

            $("#row_total_atendimentos_integral").removeClass("col-lg-6");
            $("#row_total_atendimentos_integral").removeClass("col-lg-12");
            $("#row_total_atendimentos_integral").addClass("col-lg-12");
        }
    });   

    $(document).on('click', '#status_tempo_medio_atendimento', function(){
        if( $('#status_tempo_medio_atendimento').is(':checked') == true ){
            $("#status_tempo_medio_atendimento").val(1);
            $("#tempo_medio_atendimento").prop('disabled', false);
            $("#tempo_medio_atendimento").val("<?=$tempo_medio_atendimento?>");
        }else{
            $("#tempo_medio_atendimento").prop('disabled', true);
            $("#tempo_medio_atendimento").val('');
            $("#status_tempo_medio_atendimento").val(2);
        }
    });

    $(document).on('click', '#status_porcentagem_ligacao_nota', function(){
        if( $('#status_porcentagem_ligacao_nota').is(':checked') == true ){
            $("#status_porcentagem_ligacao_nota").val(1);
            $("#porcentagem_ligacao_nota").prop('disabled', false);
            $("#porcentagem_ligacao_nota").val("<?=$porcentagem_ligacao_nota?>");
        }else{
            $("#porcentagem_ligacao_nota").prop('disabled', true);
            $("#porcentagem_ligacao_nota").val('');
            $("#status_porcentagem_ligacao_nota").val(2);
        }
    });

    $(document).on('click', '#status_nota_media', function(){
        if( $('#status_nota_media').is(':checked') == true ){
            $("#status_nota_media").val(1);
            $("#nota_media").prop('disabled', false);
            $("#nota_media").val("<?=$nota_media?>");
        }else{
            $("#nota_media").prop('disabled', true);
            $("#nota_media").val('');
            $("#status_nota_media").val(2);
        }
    });

    $(document).on('click', '#status_erros_reclamacoes', function(){
        if( $('#status_erros_reclamacoes').is(':checked') == true ){
            $("#status_erros_reclamacoes").val(1);
            $("#erros_reclamacoes").prop('disabled', false);
            $("#erros_reclamacoes").val("<?=$erros_reclamacoes?>");
        }else{
            $("#erros_reclamacoes").prop('disabled', true);
            $("#erros_reclamacoes").val('');
            $("#status_erros_reclamacoes").val(2);
        }
    });

    $(document).on('click', '#status_faltas_justificadas', function(){
        if( $('#status_faltas_justificadas').is(':checked') == true ){
            $("#status_faltas_justificadas").val(1);
            $("#faltas_justificadas").prop('disabled', false);
            $("#faltas_justificadas").val("<?=$faltas_justificadas?>");
        }else{
            $("#faltas_justificadas").prop('disabled', true);
            $("#faltas_justificadas").val('');
            $("#status_faltas_justificadas").val(2);
        }
    });
    
    $(document).on('click', '#status_absenteismo', function(){
        if( $('#status_absenteismo').is(':checked') == true ){
            $("#status_absenteismo").val(1);
            $("#absenteismo").prop('disabled', false);
            $("#absenteismo").val("<?=$absenteismo?>");
        }else{
            $("#absenteismo").prop('disabled', true);
            $("#absenteismo").val('');
            $("#status_absenteismo").val(2);
        }
    });

    $(document).on('click', '#status_pausa_registro', function(){
        if( $('#status_pausa_registro').is(':checked') == true ){
            $("#status_pausa_registro").val(1);
            $("#pausa_registro").prop('disabled', false);
            $("#pausa_registro").val("<?=$pausa_registro?>");
        }else{
            $("#pausa_registro").prop('disabled', true);
            $("#pausa_registro").val('');
            $("#status_pausa_registro").val(2);
        }
    });

    $(document).on('click', '#status_monitoria', function(){
        if( $('#status_monitoria').is(':checked') == true ){
            $("#status_monitoria").val(1);
            $("#monitoria").prop('disabled', false);
            $("#monitoria").val("<?=$monitoria?>");
        }else{
            $("#monitoria").prop('disabled', true);
            $("#monitoria").val('');
            $("#status_monitoria").val(2);
        }
    });

    $(document).on('click', '#status_resolucao', function(){
        if( $('#status_resolucao').is(':checked') == true ){
            $("#status_resolucao").val(1);
            $("#resolucao").prop('disabled', false);
            $("#resolucao").val("<?=$resolucao?>");
        }else{
            $("#resolucao").prop('disabled', true);
            $("#resolucao").val('');
            $("#status_resolucao").val(2);
        }
    });

    $(document).on('click', '#status_atendimentos_hora', function(){
        if( $('#status_atendimentos_hora').is(':checked') == true ){
            $("#status_atendimentos_hora").val(1);
            $("#atendimentos_hora").prop('disabled', false);
            $("#atendimentos_hora").val("<?=$atendimentos_hora?>");
        }else{
            $("#atendimentos_hora").prop('disabled', true);
            $("#atendimentos_hora").val('');
            $("#status_resolucao").val(2);
        }
    });


    $(document).on('click', '#status_total_atendimentos', function(){
        if( $('#status_total_atendimentos').is(':checked') == true ){
            $("#status_total_atendimentos").val(1);
            $("#total_atendimentos").prop('disabled', false);
            $("#total_atendimentos").val("<?=$total_atendimentos?>");
        }else{
            $("#total_atendimentos").prop('disabled', true);
            $("#total_atendimentos").val('');
            $("#status_total_atendimentos").val(2);
        }
    });

    $(document).on('click', '#status_qtd_ajudas', function(){
        if( $('#status_qtd_ajudas').is(':checked') == true ){
            $("#status_qtd_ajudas").val(1);
            $("#qtd_ajudas").prop('disabled', false);
            $("#qtd_ajudas").val("<?=$qtd_ajudas?>");
        }else{
            $("#qtd_ajudas").prop('disabled', true);
            $("#qtd_ajudas").val('');
            $("#status_qtd_ajudas").val(2);
        }
    });

    $(document).on('click', '#status_total_atendimentos_meio_turno', function(){
        if( $('#status_total_atendimentos_meio_turno').is(':checked') == true ){
            $("#status_total_atendimentos_meio_turno").val(1);
            $("#total_atendimentos_meio_turno").prop('disabled', false);
            $("#total_atendimentos_meio_turno").val("<?=$total_atendimentos_meio_turno?>");
        }else{
            $("#total_atendimentos_meio_turno").prop('disabled', true);
            $("#total_atendimentos_meio_turno").val('');
            $("#status_total_atendimentos_meio_turno").val(2);
        }
    });

    $(document).on('click', '#status_qtd_ajudas_meio_turno', function(){
        if( $('#status_qtd_ajudas_meio_turno').is(':checked') == true ){
            $("#status_qtd_ajudas_meio_turno").val(1);
            $("#qtd_ajudas_meio_turno").prop('disabled', false);
            $("#qtd_ajudas_meio_turno").val("<?=$qtd_ajudas?>");
        }else{
            $("#qtd_ajudas_meio_turno").prop('disabled', true);
            $("#qtd_ajudas_meio_turno").val('');
            $("#status_qtd_ajudas_meio_turno").val(2);
        }
    });

    $(document).on('click', '#ok', function(){
        
        var nome = $('#nome').val();
        var tipo = $('#tipo').val();
        var status = $('#status').val();
        var tempo_medio_atendimento = $('#tempo_medio_atendimento').val();
        var porcentagem_ligacao_nota = $('#porcentagem_ligacao_nota').val();
        var nota_media = $('#nota_media').val();
        var erros_reclamacoes = $('#erros_reclamacoes').val();
        var data_de = $( "input[name='data_de']" ).val();
        var data_ate = $( "input[name='data_ate']" ).val();
        var compara1 = parseInt(data_de.split("/")[2].toString() + data_de.split("/")[1].toString() + data_de.split("/")[0].toString());
        var compara2 = parseInt(data_ate.split("/")[2].toString() + data_ate.split("/")[1].toString() + data_ate.split("/")[0].toString());
        
        if(tipo != 1){
            $("#faltas_justificadas").val('');
            $("#status_faltas_justificadas").val(2);
            // $("#absenteismo").val('');
            // $("#status_absenteismo").val(2);


            // $("#total_atendimentos").val('');
            // $("#status_total_atendimentos").val(2);
            $("#qtd_ajudas").val('');
            $("#status_qtd_ajudas").val(2);
            $("#total_atendimentos_meio_turno").val('');
            $("#status_total_atendimentos_meio_turno").val(2);
            $("#qtd_ajudas_meio_turno").val('');
            $("#status_qtd_ajudas_meio_turno").val(2);
        }

        if(!nome){
            alert("Deve-se selecionar um nome!");
            return false;
        }
        if(!status){
            alert("Deve-se selecionar um status!");
            return false;
        }
        if(!tipo){
            alert("Deve-se selecionar o tipo!");
            return false;
        }

        if(!data_de){
            alert("Deve-se incluir uma data inicial válida!");
            return false;
        }
        if(!data_ate){
            alert("Deve-se incluir uma data final válida!");
            return false;
        }
        if (compara1 >= compara2){
            alert("Data final não pode ser menor ou igual a data inicial");        
            return false;
        }

        if($("#status_tempo_medio_atendimento").val() == 1 && !$("#tempo_medio_atendimento").val()){
            alert("Deve-se inserir um tempo medio de atendimento válido!"); 
            return false;
        }
        if($("#status_porcentagem_ligacao_nota").val() == 1 && !$("#porcentagem_ligacao_nota").val()){
            alert("Deve-se inserir uma porcentagem de ligacao com nota válida!"); 
            return false;
        }
        if($("#status_nota_media").val() == 1 && !$("#nota_media").val()){
            alert("Deve-se inserir uma nota media válida!"); 
            return false;
        }
        if($("#status_erros_reclamacoes").val() == 1 && !$("#erros_reclamacoes").val()){
            alert("Deve-se inserir um número de reclamacoes válido!"); 
            return false;
        }
        if($("#status_faltas_justificadas").val() == 1 && !$("#faltas_justificadas").val()){
            alert("Deve-se inserir um número de faltas válido!"); 
            return false;
        }
        if($("#status_absenteismo").val() == 1 && !$("#absenteismo").val()){
            alert("Deve-se inserir um número de absenteismo válido!"); 
            return false;
        }

        if(tipo == 1){
            if($("#status_total_atendimentos").val() == 1 && !$("#total_atendimentos").val()){
                alert("Deve-se inserir um total de atendimentos válido!"); 
                return false;
            }

            if($("#status_qtd_ajudas").val() == 1 && !$("#qtd_ajudas").val()){
                alert("Deve-se inserir um número de quantidade de ajudas válido!"); 
                return false;
            }

            if($("#status_total_atendimentos_meio_turno").val() == 1 && !$("#total_atendimentos_meio_turno").val()){
                alert("Deve-se inserir um total de atendimentos para meio turno válido!"); 
                return false;
            }

            if($("#status_qtd_ajudas_meio_turno").val() == 1 && !$("#qtd_ajudas_meio_turno").val()){
                alert("Deve-se inserir um número de quantidade de ajudas para meio turno válido!"); 
                return false;
            }
        }
        
        modalAguarde();
    });

</script>