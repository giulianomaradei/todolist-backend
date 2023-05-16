<?php
require_once(__DIR__."/../class/System.php");

    $tituloPainel = 'Inserir';
    $operacao = 'inserir';
    $id = 1;
    $quantidade = 1;
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    <h3 class="panel-title text-left pull-left"><?= $tituloPainel ?> Estoque - Movimentação (Saídas):</h3>
                </div>
                <form method="post" action="/api/ajax?class=EstoqueMovimentacaoSaida.php" id="estoque_movimentacao_form" style="margin-bottom: 0;">
		            <input type="hidden" name="token" value="<?php echo $request->token ?>">
                    <div class="panel-body" style="padding-bottom: 0;">    
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label>*Item do Estoque:</label>
                                    <div class="input-group">
                                        <input class="form-control input-sm" id="busca_estoque_item" type="text" name="busca_estoque_item" placeholder="Informe o nome do item..." autocomplete="off" readonly required>
                                        <div class="input-group-btn">
                                            <button class="btn btn-info btn-sm" id="habilita_busca_estoque_item" name="habilita_busca_estoque_item" type="button" title="Clique para selecionar o item" style="height: 30px;"><i class="fa fa-search"></i></button>
                                        </div>
                                    </div>
                                    <input type="hidden" name="id_estoque_item" id="id_estoque_item">
                                    <input type="hidden" name="quantidade_minima" id="quantidade_minima">
                                    <input type="hidden" name="quantidade_atual" id="quantidade_atual">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>*Quantidade:</label>
                                    <input class="form-control input-sm number" name="quantidade" id="quantidade" type="number" autocomplete="off" value="1" max="100000" min="1" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label id='label'>*Solicitante:</label>
                                    <div class="input-group">
                                        <input class="form-control input-sm" id="busca_pessoa" type="text" name="busca_pessoa"  value="" placeholder="Informe o nome ou CPF/CNPJ..." autocomplete="off" readonly>
                                        <div class="input-group-btn">
                                            <button class="btn btn-info btn-sm" id="habilita_busca_pessoa" name="habilita_busca_pessoa" type="button" title="Clique para selecionar a pessoa" style="height: 30px;" ><i class="fa fa-search"></i></button>
                                        </div>
                                    </div>
                                    <input type="hidden" name="id_usuario" id="id_usuario">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group" >
                                    <label>*Setor Responsável:</label>
                                    <select name="id_setor" id="id_setor" class="form-control input-sm">
                                    <option value="">Nenhum</option>
                                    <?php

                                    $dados_id_setor = DBRead('', 'tb_setor', "WHERE status = 1 ORDER BY descricao ASC");

                                    if ($dados_id_setor) {
                                        foreach ($dados_id_setor as $conteudo_id_setor) {
                                            echo "<option value='" . $conteudo_id_setor['id_setor'] . "'>" . $conteudo_id_setor['descricao'] . "</option>";
                                        }
                                    }
                                    ?>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group" >
                                    <label>Localização:</label>
                                    <select name="id_localizacao" id="id_localizacao" class="form-control input-sm">
                                        <option value="">Selecione uma localização...</option>
                                        <?php

                                        $dados_id_localizacao = DBRead('', 'tb_estoque_localizacao', "WHERE status = 1 ORDER BY nome");

                                        if ($dados_id_localizacao) {
                                            foreach ($dados_id_localizacao as $conteudo_id_localizacao) {
                                                echo "<option value='" . $conteudo_id_localizacao['id_estoque_localizacao'] . "'>" . $conteudo_id_localizacao['nome'] . "</option>";
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>

                        </div>
                        <div class="row">

                            <div class="col-md-10">
                                <div class="form-group">
                                    <label>Observação:</label>
                                    <input class="form-control input-sm number" name="observacao" id="observacao" type="text" autocomplete="off">
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>&nbsp;</label>
                                    <button type="button" class='center-block btn btn-info btn-sm' style="min-width: 100%; display: inline-block;" id='adiciona_linha' role='button'><i class='fa fa-plus' aria-hidden='true'></i> Adicionar</button>
                                </div>
                            </div>

                        </div>                    
                        <div class="row">
                            <div class="col-md-12">
                                <div class='table-responsive'>
                                    <table class='table table-hover' style='font-size: 14px;'>
                                        <thead>
                                        <tr id="header_tabela" style="display: none">
                                                <th class='col-md-2'>Item</th>
                                                <th class='col-md-1'>Quantidade:</th>
                                                <th class='col-md-2'>Solicitante:</th>
                                                <th class='col-md-2'>Setor Responsável:</th>
                                                <th class='col-md-2'>Localização:</th>                                                
                                                <th class='col-md-2'>Observação:</th>
                                                <th class='col-md-1 text-center'>Ações:</th>
                                            </tr>
                                        </thead>
                                        <tbody class="nova_linha">   
                                            <tr class="alerta_inicio">
                                                <td colspan="7"><p class='alert alert-warning' style='text-align: center'> Ainda não foram inseridas movimentações de estoque!</p></td>
                                            </tr>
                                        </tbody>

                                    </table>
                                </div>
                            </div>	

						</div>
                    </div>
                    <div class="panel-footer">
                        <div class="row">
                            <div class="col-md-12" style="text-align: center">
                                <input type="hidden" id="operacao" value="<?= $id; ?>" name="<?= $operacao; ?>"/>
                                <button class="btn btn-primary" name="salvar" id="ok" type="submit"><i class="fa fa-floppy-o"></i> Salvar</button>
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

    $("#adiciona_linha").on('click', function(){

        var id_estoque_item = $('#id_estoque_item').val();
        var busca_estoque_item = $('#busca_estoque_item').val();
        var quantidade = $('#quantidade').val();

        var tipo_movimentacao = 'Saída';    
        var quantidade_minima = $('#quantidade_minima').val();
        var quantidade_atual = $('#quantidade_atual').val();

        var id_setor = $('#id_setor option:selected').val();
        var setor_nome = $('#id_setor option:selected').text();

        var id_localizacao = $('#id_localizacao option:selected').val();
        var localizacao_nome = $('#id_localizacao option:selected').text();

        var id_usuario = $('#id_usuario').val();
        var busca_pessoa = $('#busca_pessoa').val();

        var observacao = $('#observacao').val();

        if(!id_estoque_item){
            alert("Você deve selecionar o item antes de adicionar!");
            return false;
        }

        if(!id_usuario){
            alert("Você deve selecionar o solicitante antes de adicionar!");
            return false;
        }

        if(!id_localizacao){
            alert("Você deve selecionar a localização antes de adicionar!");
            return false;
        }

        var cont = 0;
        $("[name ='item_id[]']").each(function(){
            
            var id = $(this).val();
            
            if(id == id_estoque_item){
                cont = 1;
                return false;
            }
    
        });
        
        if(cont == 1){
            alert("Você já adicionou uma "+tipo_movimentacao+" para este item!");
            return false;
        }

        if($( ".alerta_inicio" )){
            $( ".alerta_inicio" ).remove();
            $( "#header_tabela" ).show();
        }
    
		$("tbody.nova_linha").append('<tr>    <td style="display: none"><input class="form-control input-sm" type="text" name="item_id[]" value="'+id_estoque_item+'"readonly required></td><td style="display: none"><input class="form-control input-sm" type="text" name="item_quantidade_minima[]" value="'+quantidade_minima+'"readonly required></td><td style="display: none"><input class="form-control input-sm" type="text" name="item_quantidade_atual[]" value="'+quantidade_atual+'"readonly required></td><td><input class="form-control input-sm" type="text" name="item_nome[]" value="'+busca_estoque_item+'"readonly required></td><td><input class="form-control input-sm number" name="item_quantidade[]" type="number" autocomplete="off" value="'+quantidade+'" readonly required></td><td style="display: none"><input class="form-control input-sm" type="text" name="id_solicitante[]" value="'+id_usuario+'"readonly required></td><td><input class="form-control input-sm" type="text" name="usuario_nome[]" value="'+busca_pessoa+'"readonly required></td><td style="display: none"><input class="form-control input-sm" type="text" name="id_setor[]" value="'+id_setor+'"readonly required></td><td><input class="form-control input-sm" type="text" name="setor_nome[]" value="'+setor_nome+'"readonly required></td><td style="display: none"><input class="form-control input-sm" type="text" name="id_localizacao[]" value="'+id_localizacao+'"readonly required></td><td><input class="form-control input-sm" type="text" name="localizacao_nome[]" value="'+localizacao_nome+'"readonly required></td><td><input class="form-control input-sm" name="item_observacao[]" type="text" autocomplete="off" value="'+observacao+'" readonly></td><td><button class="center-block btn btn-danger btn-sm remove_linha" role="button"><i class="fa fa-trash-o" aria-hidden="true"></i></button></td></tr>');

        $('#busca_estoque_item').val('');
        $('#id_estoque_item').val('');
        $('#quantidade').val('1');
        $("#id_setor option:contains('Nenhum')").attr('selected',true);
        $('#id_usuario').val('');
        $('#busca_pessoa').val('');
        $('#observacao').val('');
	});

	$(document).on('click', '.remove_linha', function(){

        $(this).parent().parent().remove();

        if(!$("[name ='item_id[]']").val()){
            $("tbody.nova_linha").append('<tr class="alerta_inicio"><td colspan="4"><p class="alert alert-warning" style="text-align: center"> Ainda não foram inseridas movimentações de estoque!</p></td></tr>');
            $( "#header_tabela" ).hide();
        }
	});			

    //BUSCA ESTOQUE
        // Atribui evento e função para limpeza dos campos
        $('#busca_estoque_item').on('input', limpaCamposEstoque);
        
        // Dispara o Autocomplete do estoque a partir do segundo caracter
        $("#busca_estoque_item").autocomplete({
                minLength: 2,
                source: function (request, response) {
                    $.ajax({
                        url: "/api/ajax?class=EstoqueItemAutocomplete.php",
                        dataType: "json",
                        data: {
                            acao: 'autocomplete',
                            parametros: { 
                                'nome' : $('#busca_estoque_item').val(),
                            },
                            token: '<?= $request->token ?>'
                        },
                        success: function (data) {
                            response(data);
                        }
                    });
                },
                focus: function (event, ui) {
                    $("#busca_estoque_item").val(ui.item.nome);
                    carregarDadosEstoque(ui.item.id_estoque_item);
                    return false;
                },
                select: function (event, ui) {
                    $("#busca_estoque_item").val(ui.item.nome+" ("+ui.item.informacao_adicional+")");
                    $('#busca_estoque_item').attr("readonly", true);
                    return false;
                }
            })
            .autocomplete("instance")._renderItem = function (ul, item) {

            return $("<li>").append("<a><strong>"+item.nome+" ("+item.informacao_adicional+")</a><hr style='margin-bottom: 0px;'>").appendTo(ul);
        };

        // Função para carregar os dados da consulta nos respectivos campos
        function carregarDadosEstoque(id) {
            var busca = $('#busca_estoque_item').val();

            if (busca != "" && busca.length >= 2) {
                $.ajax({
                    url: "/api/ajax?class=EstoqueItemAutocomplete.php",
                    dataType: "json",
                    data: {
                        acao: 'consulta',
                        parametros: { 
                            'id' : id,                            
                        },
                        token: '<?= $request->token ?>'
                    },
                    success: function (data) {
                        $('#id_estoque_item').val(data[0].id_estoque_item);
                        $('#quantidade_minima').val(data[0].quantidade_minima);
                        $('#quantidade_atual').val(data[0].quantidade);

                    }
                });
            }
        }

        // Função para limpar os campos caso a busca esteja vazia
        function limpaCamposEstoque() {
            var busca = $('#busca_estoque_item').val();
            if (busca == "") {
                $('#id_estoque_item').val('');
            }
        }

        $(document).on('click', '#habilita_busca_estoque_item', function () {
            $('#id_estoque_item').val('');
            $('#busca_estoque_item').val('');
            $('#busca_estoque_item').attr("readonly", false);
            $('#busca_estoque_item').focus();
        });

    //FIM BUSCA ESTOQUE

    //BUSCA USUARIO

	    // Atribui evento e função para limpeza dos campos
	    $('#busca_pessoa').on('input', limpaCamposPessoa);

	    // Dispara o Autocomplete da pessoa a partir do segundo caracter
	    $("#busca_pessoa").autocomplete({
	        minLength: 2,
	        source: function(request, response){
	            $.ajax({
	                url: "/api/ajax?class=UsuarioAutocomplete.php",
	                dataType: "json",
	                data: {
	                    acao: 'autocomplete',
	                    parametros: {
	                        'nome' : $('#busca_pessoa').val(),
	                    },
                        token: '<?= $request->token ?>'
	                },
	                success: function(data){
	                    response(data);
	                }
	            });
	        },
	        focus: function(event, ui){
	            $("#busca_pessoa").val(ui.item.nome);
	            carregarDadosPessoa(ui.item.id_pessoa);
	            return false;
	        },
	        select: function(event, ui){
	            $("#busca_pessoa").val(ui.item.nome);
	            $('#busca_pessoa').attr("readonly", true);
	            return false;
	        }
	    })
	    .autocomplete("instance")._renderItem = function(ul, item){
	        return $("<li>").append("<a><strong>"+item.id_pessoa+" - "+ item.nome + " </strong><br>" +item.razao_social+ "<br>" +item.cpf_cnpj+ "</a><hr style='margin-bottom: 0px;'>").appendTo(ul);
	    };

	    // Função para carregar os dados da consulta nos respectivos campos
	    function carregarDadosPessoa(id) {
	        var busca = $('#busca_pessoa').val();

	        if (busca != "" && busca.length >= 2) {
	            $.ajax({
	                url: "/api/ajax?class=UsuarioAutocomplete.php",
	                dataType: "json",
	                data: {
	                    acao: 'consulta',
	                    parametros: {
	                        'id' : id,
	                    },
                        token: '<?= $request->token ?>'
	                },
	                success: function (data) {
	                    $('#id_usuario').val(data[0].id_usuario);
	                }
	            });
	        }
	    }

	    // Função para limpar os campos caso a busca esteja vazia
	    function limpaCamposPessoa() {
	        var busca = $('#busca_pessoa').val();

	        if (busca == "") {
	            $('#id_usuario').val('');
	        }
	    }
	    
	    $(document).on('click', '#habilita_busca_pessoa', function () {
	        $('#id_usuario').val('');
	        $('#busca_pessoa').val('');
	        $('#busca_pessoa').attr("readonly", false);
	        $('#busca_pessoa').focus();
	    });

    //FIM BUSCA USUARIO

    $(document).on('submit', '#estoque_movimentacao_form', function () {
        var cont = 0;
        $("[name ='item_id[]']").each(function(){
            
            var item_quantidade = $(this).parent().parent().find("[name ='item_quantidade[]']").val();
            var item_quantidade_minima = $(this).parent().parent().find("[name ='item_quantidade_minima[]']").val();
            var item_quantidade_atual = $(this).parent().parent().find("[name ='item_quantidade_atual[]']").val();
            var item_nome = $(this).parent().parent().find("[name ='item_nome[]']").val();

            if(item_quantidade_atual < 0){
                item_quantidade = item_quantidade * -1;
                if((parseInt(item_quantidade) + parseInt(item_quantidade_atual)) < item_quantidade_minima){
                    if(!confirm('Depois desta movimentação a quantidade de itens no estoque do item '+item_nome+' ficará ainda menor do que a quantidade minima!')) {
                        cont = 1;
                        return false;
                    }
                }else if((parseInt(item_quantidade) + parseInt(item_quantidade_atual)) == item_quantidade_minima){
                    if(!confirm('Depois desta movimentação a quantidade de itens no estoque do item '+item_nome+' ficará igual a quantidade minima!')) {
                        cont = 1;
                        return false;
                    }
                }
            }else{
                if((parseInt(item_quantidade_atual) - parseInt(item_quantidade)) < item_quantidade_minima){
                    if(!confirm('Depois desta movimentação a quantidade de itens no estoque do item '+item_nome+' ficará menor que a quantidade minima!')) {
                        cont = 1;
                        return false;
                    }
                }else if((parseInt(item_quantidade_atual) - parseInt(item_quantidade)) == item_quantidade_minima){
                    if(!confirm('Depois desta movimentação a quantidade de itens no estoque do item '+item_nome+' ficará igual a quantidade minima!')) {
                        cont = 1;
                        return false;
                    }
                }
            }
        });

        if(cont == 1){
            return false;
        }

        modalAguarde();
    });
</script>