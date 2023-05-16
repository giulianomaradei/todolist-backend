<?php
    require_once(__DIR__."/../class/System.php");
    
    $origem_chamado = (int) $_GET['origem_chamado'];

    $dados_usuarios = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.status = 1 AND a.id_usuario != '".$id_usuario."' ORDER BY b.nome ASC");

    if (isset($_GET['alterar'])) {
        $tituloPainel = 'Alterar';
        $operacao = 'alterar';
        $id = (int)$_GET['alterar'];

        $dados = DBRead('', 'tb_chamado', "WHERE id_chamado = '$id'");
        $titulo = $dados[0]['titulo'];
        $visibilidade = $dados[0]['visibilidade'];
        $id_categoria = $dados[0]['id_categoria'];
        $responsavel = $dados[0]['id_usuario_responsavel'];
        $descricao = $dados[0]['descricao'];

        $dados_acao = DBRead('', 'tb_chamado_acao', "WHERE id_chamado = '$id'");
        $tempo = $dados_acao[0]['tempo'];

    }else{
        $tituloPainel = 'Inserir';
        $operacao = 'inserir';
        $id = 1;
        $titulo = '';
        $visibilidade = '';
        $id_categoria = '';
        $responsavel = '';
        $tempo = '';
        $descricao = '';
    }
?>

<style>
    .some-container .tooltip .tooltip-inner{
        width: 37em;
        max-width: 100%;
        white-space: pre-line;
    }
    .select2{
        width: 100% !important;
    }
</style>

<link href='inc/ckeditor/css/select2.min.css' />
<script src="inc/ckeditor/ckeditor.js"></script>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    <h3 class="panel-title text-left pull-left"><?= $tituloPainel ?> chamado:</h3>
                    <?php if(isset($_GET['alterar'])){ echo "<div class=\"panel-title text-right pull-right\"><a href=\"/api/ajax?class=Topico.php?excluir= $id&token=".$request->token."\" onclick=\"if(!confirm('Tem certeza que deseja excluir o registro?')){ return false; } else { modalAguarde(); }\"></a></div>"; } ?>
                </div>
                <form enctype='multipart/form-data' id="chamado_form" action='/api/ajax?class=Chamado.php' method='POST' style="margin-bottom: 0;">
                    <input type="hidden" name="token" value="<?php echo $request->token ?>">

                    <input type="hidden" id='responsavel' name="responsavel_final" value="<?=$responsavel?>">
                    <input type="hidden" id='origem_chamado' name="origem_chamado" value="<?=$origem_chamado?>">
                    <div class="panel-body" style="padding-bottom: 0;">
                        <div class="row">

                            <div class='col-md-2'>
                                <div class="form-group some-container">
                                    <label>*Visibilidade:</label>
                                    <?php
                                        $sel_visibilidade[$visibilidade] = 'selected';
                                    ?>
                                    <select class="form-control input-sm" id="id_visibilidade" name="id_visibilidade" data-toggle="tooltip" data-placement="top" title="Público - Todas pessoas do(s) setor(es) escolhido(s) terão acesso ao chamado. &#013; Privado - Somente a(s) pessoa(s) escolhida(s) terá(ão) acesso ao chamado." required />
                                        <option value='1' <?=isset($sel_visibilidade[1])?>>Público</option>
                                        <option value='2' <?=isset($sel_visibilidade[2])?>>Privado</option>
                                    </select>
                                </div>
                            </div>
                            <div class='col-md-10'>
                            
                                <div class="form-group">
                                    <div id='container-perfil'>
                                        <label>*Selecionar:</label><br />
                                        <select class="js-example-basic-multiple chamado_perfil" name="perfil_sistema[]" multiple="multiple">
                                            <?php
                                            if($perfil_usuario == '3' ){
                                                $dados_perfil_sistema = DBRead('', 'tb_perfil_sistema'," WHERE status = 1 AND id_perfil_sistema != 19 AND id_perfil_sistema != 3 ORDER BY nome ASC");
                                            }else{
                                                $dados_perfil_sistema = DBRead('', 'tb_perfil_sistema'," WHERE status = 1 AND  id_perfil_sistema != 19 ORDER BY nome ASC");
                                            }
                                                if($dados_perfil_sistema){
                                                    foreach($dados_perfil_sistema as $perfil_sistema){
                                                        $id_perfil_sistema = $perfil_sistema['id_perfil_sistema'];
                                                        $nome = $perfil_sistema['nome'];
                                                        $ckecked = '';
                                                        if($operacao == 'alterar'){
                                                            $dados = DBRead('', 'tb_chamado_perfil', "WHERE status = 1 AND  id_perfil_sistema = '$id_perfil_sistema' AND id_chamado = '$id'");
                                                            if($dados){
                                                                $ckecked = 'checked';
                                                            }
                                                        }
                                                        echo "<option value='$id_perfil_sistema'>$nome</option>";
                                                        
                                                    }
                                                }
                                                ?>
                                        </select>
                                    </div>
                                
                                    <div id='container-usuarios' style='display: none;'>
                                        <label>*Selecionar:</label><br />
                                        <select class="js-example-basic-multiple chamado_usuario" name="usuarios[]" multiple="multiple">
                                            <?php
                                            
                                                if($dados_usuarios){
                                                    foreach($dados_usuarios as $conteudo){
                                                        $id_usuario = $conteudo['id_usuario'];
                                                        $nome = $conteudo['nome'];
                                                        $ckecked = '';
                                                        
                                                        echo "<option value='$id_usuario'>$nome</option>";
                                                       
                                                    }
                                                }
                                                ?>
                                        </select>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class='row'>
                            <div class='col-lg-3' id="altera_responsavel">
                                <?php
                                    $nome_responsavel = DBRead('', 'tb_pessoa a', "INNER JOIN tb_usuario b ON a.id_pessoa = b.id_pessoa WHERE b.id_usuario = '$responsavel'");
                                    $nome_responsavel = $nome_responsavel[0]['nome'];
                                ?>
                                
                                <div>
                                <label>*Responsável:</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control input-sm" name="id_responsavel2" id="id_responsavel2" disabled value="<?=$nome_responsavel?>" />
                                        <span class="input-group-addon btn btn-sm" id="altera-responsavel" style="border-radius: 2px;"><i class="fa fa-pencil" aria-hidden="true"></i></span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3" id='bloco-altera-responsavel' style="display: none;">
                                <label>*Responsável:</label>
                                <div class="input-group">
                                    <select class="form-control input-sm" id="id_responsavel" name="id_responsavel">
                                        <option value=""></option>
                                    </select>
                                    <span class="input-group-addon btn btn-sm" id="check" style="border-radius: 2px;">
                                        <i class="fa fa-check" id="check2" aria-hidden="true"></i>
                                    </span>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>*Título:</label>
                                    <input name="titulo" autofocus type="text" id="titulo" class="form-control input-sm" value="<?= $titulo; ?>" autocomplete="off" autocomplete="off" required />
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label>*Categoria:</label>

                                <select class="js-example-basic-multiple" id="id_categoria" name="id_categoria[]" required onchange="scriptReclamacao();">
                                    <?php
                                    $dados_categoria = DBRead('', 'tb_categoria', "WHERE exibe_chamado = 1 ORDER BY nome ASC");
                                    if($dados_categoria){
                                        foreach($dados_categoria as $categoria){
                                            $idCategoria = $categoria['id_categoria'];
                                            $nomeSelect = $categoria['nome'];
                                            echo "<option value='$idCategoria'>$nomeSelect</option>";
                                        }
                                    }
                                    ?>
                                </select>

                            </div>
                        </div>

                        <div class='row'>
                            <div class='col-md-2'>
                                <div class="form-group">
                                    <label>*Origem:</label>
                                    <select class="form-control input-sm" id="id_origem" name="id_origem" required>
                                        <option value=""></option>
                                        <?php
                                            $dados_origem = DBRead('', 'tb_chamado_origem', "WHERE id_chamado_origem != 1 AND id_chamado_origem != 4 ORDER BY descricao ASC");
                                            if($dados_origem){
                                                foreach($dados_origem as $origem){
                                                    $idOrigem = $origem['id_chamado_origem'];
                                                    $nomeSelect = $origem['descricao'];
                                                    $selected = $id_origem == $idOrigem ? "selected" : "";
                                                    echo "<option value='$idOrigem'".$selected.">$nomeSelect</option>";
                                                }
                                            }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class='col-md-2'>
                                <div class="form-group">
                                    <label for="tempo">*Tempo (minutos):</label>
                                    <input class="form-control input-sm number_int" id="tempo" name="tempo" value="<?=$tempo?>" autocomplete="off" required>
                                </div>
                            </div>
                            <div class='col-md-4'>
                                <div class="form-group">
                                    <label id='id_contrato'>Contrato (cliente):</label>
                                    <div class="input-group">
                                        <input class="form-control input-sm ui-autocomplete-input" id="busca_contrato" type="text" name="busca_contrato" value="" placeholder="Informe o nome ou CNPJ..." autocomplete="off" readonly="">

                                        <div class="input-group-btn">
                                            <button class="btn btn-info btn-sm" id="habilita_busca_contrato" name="habilita_busca_contrato" type="button" title="Clique para selecionar o contrato" style="height: 30px;"><i class="fa fa-search"></i></button>
                                        </div>
                                    </div>
                                    <input type="hidden" name="id_contrato_plano_pessoa" id="id_contrato_plano_pessoa" value="">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label id='id_prazo_data'>Prazo (data):</label>
                                    <input name="prazo_encerramento_data" id="id_prazo_data" type="text" class="form-control input-sm date calendar id_prazo_data" value="<?= $prazo_encerramento_data; ?>" autocomplete="off" />
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label id='id_prazo_hora'>Prazo (hora):</label>
                                    <input name="prazo_encerramento_hora" id="id_prazo_hora" type="time" class="form-control input-sm" value="<?= $prazo_encerramento_hora; ?>" autocomplete="off" />
                                </div>
                            </div>
                        </div>

                        <div class='row'>
                            <div class='col-md-4 col-md-offset-4'>
                                <div class="form-group">
                                    <label>                       
                                        <div id="responsaveis"></div>
                                    </label>
                                </div>
                            </div>
                        </div>



                        <div class='row'>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>*Descrição:</label>
                                    <textarea required name="descricao" id="descricao" class="form-control ckeditor conteudo"><?=$descricao?></textarea>
                                </div>
                            </div>
                        </div>
                        <!-- <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                <label>Anexar arquivo:</label>
                                        <div class="form-group">
                                        <input size='50' type='file' id="anexo_inserir" name='anexo_inserir' accept=".csv, .doc, .docx, .pdf, .ppt, .pptx, .rtf, .txt, .xls, .xlsx, .zip, .rar, .bmp, .jpg, .jpeg, .jpe, .tiff, .png">
                                    </div>
                                </div>
                            </div>
                        </div> -->
                    </div>
        
                    <div class="panel-footer">
                        <div class="row">
                            <div class="col-md-4">
                            </div>
                                <div class="col-md-4" style="text-align: center">
                                    <input type="hidden" id="operacao" value="<?= $id; ?>" name="<?= $operacao; ?>" />
                                    <button class="btn btn-primary" name="salvar" id="ok" type="submit"><i class="fa fa-floppy-o"></i> Salvar</button>
                                </div>
                                <div class="col-md-4">
                            </div>
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

    $(document).ready(function(){
        $('.js-example-basic-multiple').select2();
    });

    //verifica quais estão marcados
    $exampleMulti = $(".js-example-basic-multiple").select2();

    $(function(){
        $('[data-toggle="tooltip"]').tooltip({ boundary: 'window' })
    });
    
    $("#altera-responsavel").on('click', function(){
        $("#bloco-altera-responsavel").show();
        $("#altera_responsavel").hide();
        $('#check').css('background-color', '#eee');
        $('#check2').css('color', '#555 ');
    });

    $("#check").on("click", function(){
        var usuario = $("#id_responsavel2").val();
        var id_responsavel = $("#id_responsavel option:selected").val();
        var nome_responsavel = $("#id_responsavel option:selected").text();
        $("#responsavel").val(id_responsavel);
        $("#id_responsavel2").val(nome_responsavel);
        $("#bloco-altera-responsavel").hide();
        $("#altera_responsavel").show();
    });

    CKEDITOR.replace('descricao', {
        height: 315
    });

    $('a[data-toggle="tab"]').on('click', function(){
        $href = $(this).attr('href');
        $('#titulo').val('');
        call_busca_ajax(1, $href);
    });

    $(document).on('submit', '#chamado_form', function(){
        var tempo = $("#tempo").val();
        var display1 = $('#bloco-altera-responsavel').css('display');
        var display2 = $('#altera_responsavel').css('display');
        var descricao = $('#descricao').val();
        var visibilidade = $('#id_visibilidade').val();
        var id_contrato_plano_pessoa = $('#id_contrato_plano_pessoa').val();
        var nome_contrato = $('#busca_contrato').val();
        if(id_contrato_plano_pessoa != ""){
            if(!confirm('Contrato escolhido: '+nome_contrato+'. Deseja continuar?')) {
                return false;
            }
        }
        if(visibilidade == 1){
            $('.chamado_perfil').each(function(){
                if($(this).filter(':checked').val()){
                    cont++;
                }
            });
        }
        if(visibilidade == 2){
            $('.chamado_usuario').each(function(){
                if($(this).filter(':checked').val()){
                    cont++;
                }
            });
        }
        if(cont == 0){
            alert('Selecione pelo menos 1 setor/usuario!');
            return false;
        }
        if(descricao == ""){
            alert('Informe uma descrição!');
            return false;
        }
        $('#check').css('background-color', '#337ab7');
        $('#check2').css('color', 'white ');
        if(display1 == 'block' && display2 == 'none'){
            alert('Confirme o responsável!');
            return false;
        }
        if(!tempo || tempo == '' || tempo <= 0 || tempo > 99999){
            alert("Deve-se inserir um tempo válido! (Max - 99999/ Min - 0)");
            return false;
        }

        modalAguarde();
    });

    $("#container-usuarios").hide();
    $("#id_visibilidade").on('change', function(){
        var selecionado = $('#id_visibilidade').find(":selected").val();
        if(selecionado == 1){
            $("#container-perfil").show();
            $("#container-usuarios").hide();
            $("#id_responsavel_altera").empty();
            $("#id_responsavel").empty();
            $('.js-example-basic-multiple').val(null).trigger('change');
            $('.chamado_perfil').each(function(){
                if ($(this).is(':checked')){
                  $('input:checkbox').prop("checked", false);
                }
            });
        }else if(selecionado == 2){
            $("#container-usuarios").show();
            $("#container-perfil").hide();
            $("#id_responsavel").empty();
            $("#id_responsavel_altera").empty();
            $('.js-example-basic-multiple').val(null).trigger('change');
            $('.chamado_usuario').each(function(){
                if ($(this).is(':checked')){
                   $('input:checkbox').prop("checked", false);
                }
            });
        }
    });

    if($("#id_visibilidade option:selected").val() == 2){
        $("#container-usuarios").show();
        $("#container-perfil").hide();
    }

    $(document).on('select2:select', '.chamado_perfil', function(e){
        buscaPorPerfil(e.params.data.id);
        $("#bloco-altera-responsavel").show();
        $("#altera_responsavel").hide();
    });

    $(document).on('select2:select', '.chamado_usuario', function(e){
        buscaPorPerfil(e.params.data.id);
        $("#bloco-altera-responsavel").show();
        $("#altera_responsavel").hide();
    });

    $(document).on('select2:unselect', '.chamado_perfil', function(e){
        buscaPorPerfil(e.params.data.id);
    });

    $(document).on('select2:unselect', '.chamado_usuario', function(e){
        buscaPorPerfil(e.params.data.id);
    });

    function buscaPorPerfil(e){
        cont = 0;
        ids_perfis = [];
        visibilidade = $("#id_visibilidade option:selected").val();
        if(visibilidade == 1){
            $('.chamado_perfil').each(function(){
                ids_perfis.push($(this).val());
                cont++;
            });
        }
        if(visibilidade == 2){
            $('.chamado_usuario').each(function(){
                ids_perfis.push($(this).val());
                cont++;
            });
        }
        if(cont == 0){
            $("#id_responsavel").empty();
        }else{
            $("#id_responsavel").empty();
            pagina = 'chamado-form';
            $.ajax({
                type: "POST",
                url: "/api/ajax?class=SelectUsuarios.php",
                dataType: "json",
                data: {
                    pagina: pagina,
                    ids_perfis: ids_perfis,
                    visibilidade: visibilidade,
                    token: '<?= $request->token ?>'
                },
                success: function(data){
                    $("#id_responsavel").html(data['dados']);
                }
            });
        }
    }

    buscaPorPerfil();
    
    // Atribui evento e função para limpeza dos campos
    $('#busca_contrato').on('input', limpaCamposContrato);
    // Dispara o Autocomplete da pessoa a partir do segundo caracter
    $("#busca_contrato").autocomplete({
            minLength: 2,
            source: function(request, response){
                $.ajax({
                    url: "class/ContratoAutocomplete.php",
                    dataType: "json",
                    data: {
                        acao: 'autocompleteresponsavelcontrato',
                        parametros: { 
                            'nome' : $('#busca_contrato').val(),
                            'pagina' : 'chamado-form'
                        },
                        token: '<?= $request->token ?>'
                    },
                    success: function (data) {
                        response(data);

                    }
                });
            },
            focus: function (event, ui) {
                $("#busca_contrato").val(ui.item.nome + " " + ui.item.nome_contrato +" - " + ui.item.servico + " - " + ui.item.plano + " (" + ui.item.id_contrato_plano_pessoa + ")");
                carregarDadosContrato(ui.item.id_contrato_plano_pessoa);
                return false;
            },
            select: function (event, ui) {
                $("#busca_contrato").val(ui.item.nome + " "+ ui.item.nome_contrato + " - " + ui.item.servico + " - " + ui.item.plano + " (" + ui.item.id_contrato_plano_pessoa + ")");
                $('#busca_contrato').attr("readonly", true);

                $("#responsaveis").html('Responsável pelo Relacionamento: '+ui.item.nome_responsavel+'<br>Responsável Técnico: '+ui.item.nome_responsavel_tecnico);

                return false;
            }
    })
    .autocomplete("instance")._renderItem = function(ul, item){
        if(!item.razao_social){
            item.razao_social = '';
        }
        if(!item.cpf_cnpj){
            item.cpf_cnpj = '';
        }
        if(!item.nome_contrato){
            item.nome_contrato = '';
        }else{
            item.nome_contrato = ' ('+item.nome_contrato+') '; 
        }
        return $("<li>").append("<a><strong>"+item.id_contrato_plano_pessoa + " - " + item.nome + ""+item.nome_contrato+"</strong><br>" +item.razao_social+ "<br>" +item.cpf_cnpj+ "<br>" + item.servico + " - " + item.plano + " (" + item.id_contrato_plano_pessoa + ")" + "</a><hr style='margin-bottom: 0px;'>").appendTo(ul);
    };

    // Função para carregar os dados da consulta nos respectivos campos
    function carregarDadosContrato(id){
        var busca = $('#busca_contrato').val();
        if(busca != "" && busca.length >= 2){
            $.ajax({
                url: "/api/ajax?class=ContratoAutocomplete.php",
                dataType: "json",
                data: {
                    acao: 'consulta',
                    parametros: {
                        'id' : id,
                    },
                    token: '<?= $request->token ?>'
                },
                success: function(data){
                    $('#id_contrato_plano_pessoa').val(data[0].id_contrato_plano_pessoa);
                }
            });
        }

    }

    // Função para limpar os campos caso a busca esteja vazia
    function limpaCamposContrato(){
        var busca = $('#busca_contrato').val();
        if(busca == ""){
            $('#id_contrato_plano_pessoa').val('');
        }
    }

    function scriptReclamacao() {

        //alert('chamou a função');

        id_categoria = $('#id_categoria').val();

        //alert(id_categoria);

        if (id_categoria != null ) {

            $.ajax({
                type: "POST",
                url: "/api/ajax?class=ChamadoScript.php",
                dataType: "json",
                data: {
                    busca_script: 'busca_script',
                    id_categoria: id_categoria,
                    token: '<?= $request->token ?>'
                },
                success: function(data){

                    if (data != null) {
                        conteudo = CKEDITOR.instances['descricao'].getData();
            
                        CKEDITOR.instances['descricao'].setData(data + '*' + conteudo);

                    } else {

                        conteudo = CKEDITOR.instances['descricao'].getData();

                        if (conteudo != '') {
                            var resultado = confirm("Ao trocar a categoria, o texto digitado na descrição será apagado! Deseja continuar?");

                            if (resultado == true) {
                                conteudo = CKEDITOR.instances['descricao'].getData();

                                conteudo = conteudo.substring(conteudo.indexOf("*")+1);

                                CKEDITOR.instances['descricao'].setData(conteudo);    
                            }
                            else{
                                alert("Você desistiu de excluir a descrição!");
                            }
                        }
                    }
                }
            });
        } 
    }

    $(document).on('click', '#habilita_busca_contrato', function(){
        $('#id_contrato_plano_pessoa').val('');
        $('#busca_contrato').val('');
        $('#busca_contrato').attr("readonly", false);
        $('#busca_contrato').focus();
    });
</script>