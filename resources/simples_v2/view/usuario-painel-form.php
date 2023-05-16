<?php
require_once(__DIR__."/../class/System.php");

if (isset($_GET['alterar'])) {
	$tituloPainel = 'Alterar';
	$operacao = 'alterar';
	$id = (int) $_GET['alterar'];
    $dados = DBRead('', 'tb_usuario_painel', "WHERE id_usuario_painel = $id");
	$status = $dados[0]['status'];
	$email = $dados[0]['email'];
    $id_pessoa = $dados[0]['id_pessoa_cliente'];
    $id_pessoa_usuario = $dados[0]['id_pessoa_usuario'];
    $nivel = $dados[0]['nivel'];
    $dados_pessoa = DBRead('','tb_pessoa',"WHERE id_pessoa = '$id_pessoa'",'nome');
	$nome = $dados_pessoa[0]['nome'];
	$senha = '';
	$confirm_senha = '';
    $importante = '';
    $disabled = 'disabled';
} else {
	$tituloPainel = 'Inserir';
	$operacao = 'inserir';
	$id = 1;
	$status = '1';
	$email = '';
	$id_pessoa = '';
	$nome = '';
	$senha = '';
	$confirm_senha = '';
	$importante = 'required';
}
?>
<div class="row">
    <div class="col-md-8 col-md-offset-2">
        <div class="panel panel-default">
            <div class="panel-heading clearfix">
                <h3 class="panel-title text-left pull-left"><?=$tituloPainel?> usuário (Painel do Cliente):</h3>
                <?php if (isset($_GET['alterar'])) {echo "<div class=\"panel-title text-right pull-right\"><a  href=\"/api/ajax?class=UsuarioPainel.php?excluir= $id&token=". $request->token ."\" onclick=\"if (!confirm('Tem certeza que deseja excluir o registro?')) { return false; } else { modalAguarde(); }\"><button class=\"btn btn-xs btn-danger\"><i class=\"fa fa-trash\"></i> Excluir</button></a></div>";}?>
            </div>
            <form method="post" action="/api/ajax?class=UsuarioPainel.php" id="usuario_form" style="margin-bottom: 0;">
		        <input type="hidden" name="token" value="<?php echo $request->token ?>">
                <div class="panel-body" style="padding-bottom: 0;">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>*Pessoa (cliente):</label>
                                <div class="input-group">
                                    <input class="form-control input-sm ui-autocomplete-input" id="busca_pessoa" type="text" name="busca_pessoa" value="<?=$dados_pessoa[0]['nome']?>" placeholder="Informe o nome ou CPF/CNPJ..." autocomplete="off" readonly="">
                                    <div class="input-group-btn">
                                        <button class="btn btn-info btn-sm" id="habilita_busca_pessoa" name="habilita_busca_pessoa" type="button" title="Clique para selecionar a pessoa" style="height: 30px;" <?=$disabled?>><i class="fa fa-search"></i></button>
                                    </div>
                                </div>
                                <input type="hidden" name="id_pessoa" id="id_pessoa" value="<?=$id_pessoa?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label>Usuário</label>
                            <select class="form-control input-sm" id="usuario_vinculado" name="usuario_vinculado" require <?=$disabled?>>
                                <?php
                                    if($tituloPainel == "Alterar"){
                                        $dados = DBRead('', 'tb_usuario_painel a', "INNER JOIN tb_pessoa b ON a.id_pessoa_usuario = b.id_pessoa WHERE a.id_usuario_painel = '$id'", "b.nome, a.id_pessoa_usuario");
                                        foreach($dados as $conteudo){
                                            echo "<option value='".$conteudo['id_pessoa_usuario']."'>".$conteudo['nome']."</option>";
                                        }
                                    }
                                ?>
                            </select>
                            <input type="hidden" name="id_usuario_cliente" value="<?=$id_pessoa_usuario?>">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>*Email:</label>
                                <input name="email" type="email" class="form-control input-sm" value="<?=$email?>" autocomplete="off" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>*Status:</label>
                                <select class="form-control input-sm" name="status" required>
                                    <option value='1' <?php if ($status == 1) {echo 'selected';}?>>Ativo</option>
                                    <option value='0' <?php if ($status == 0) {echo 'selected';}?>>Inativo</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>*Nível:</label>
                                <select class="form-control input-sm" name="nivel" required>
                                    <option value="1" <?php if ($nivel == 1) {echo 'selected';}?>>Admin</option>
                                    <option value="2" <?php if ($nivel == 2) {echo 'selected';}?>>Usuário</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-5">
                            <div class="form-group">
                                <label>*Senha:</label>
                                <input name="senha" type="password" class="form-control input-sm" id="senha" <?php if ($operacao == 'alterar') {echo 'placeholder="(Deixe em branco p/ manter!)"';} else {echo 'placeholder="(Min. 8 caracteres!)"';} ?> value="" autocomplete="off" <?=$importante;?>>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>&nbsp;</label>
                                <div class="btn-group btn-group-justified" role="group">
                                    <div class="btn-group" role="group">
                                        <button class="btn btn-sm btn-info" id="gera_senha" type="button"><i class="fa fa-key"></i> Gerar senha</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="form-group">
                                <label>*Confirmação da Senha:</label>
                                <input name="confirm_senha" type="password" class="form-control input-sm" id="confirm_senha" <?php if ($operacao == 'alterar'){	echo 'placeholder="(Deixe em branco p/ manter!)"';} else {echo 'placeholder="(Min. 8 caracteres!)"';}?> value="" autocomplete="off" <?=$importante;?>>
                            </div>
                        </div>                        
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
<script>

    function geraSenha(){
        var pwchars = "abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWYXZ";
        var passwordlength = 8; 
        var passwd = '';
        var randomWords = new Int32Array(passwordlength); 
        for (var i = 0; i < passwordlength; i++) {
            randomWords[i] = Math.floor(Math.random() * pwchars.length);
        }  

        for (var i = 0; i < passwordlength; i++) {
            passwd += pwchars.charAt(Math.abs(randomWords[i]) % pwchars.length);
        }

       return passwd;
    }

    // Atribui evento e função para limpeza dos campos
    $('#busca_pessoa').on('input', limpaCamposPessoa);
    // Dispara o Autocomplete da pessoa a partir do segundo caracter
    $("#busca_pessoa").autocomplete({
            minLength: 2,
            source: function (request, response) {
                $.ajax({
                    url: "/api/ajax?class=PessoaAutocomplete.php",
                    dataType: "json",
                    data: {
                        acao: 'autocomplete',
                        parametros: { 
                            'nome' : $('#busca_pessoa').val(),
                            'atributo' : 'cliente',
                        },
                        token: '<?= $request->token ?>'
                    },
                    success: function (data) {
                        response(data);
                    }
                });
            },
            focus: function (event, ui) {
                $("#busca_pessoa").val(ui.item.nome);
                carregarDadosPessoa(ui.item.id_pessoa + "" + ui.item.nome_contrato);
                return false;
            },
            select: function (event, ui) {
                $("#busca_pessoa").val(ui.item.nome + "" + ui.item.nome_contrato);
                $('#busca_pessoa').attr("readonly", true);
                return false;
            }
        })
        .autocomplete("instance")._renderItem = function (ul, item) {
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

        return $("<li>").append("<a><strong>"+item.id_pessoa+" - "+ item.nome + "" +item.nome_contrato + " </strong><br>" +item.razao_social+ "<br>" +item.cpf_cnpj+ "</a><hr style='margin-bottom: 0px;'>").appendTo(ul);
    };

    // Função para carregar os dados da consulta nos respectivos campos
    function carregarDadosPessoa(id) {
        var busca = $('#busca_pessoa').val();

        if (busca != "" && busca.length >= 2) {
            $.ajax({
                url: "/api/ajax?class=PessoaAutocomplete.php",
                dataType: "json",
                data: {
                    acao: 'consulta',
                    parametros: { 
                        'id' : id,                            
                    },
                    token: '<?= $request->token ?>'
                },
                success: function (data) {
                    $('#id_pessoa').val(data[0].id_pessoa);

                    $.ajax({
                        url: "/api/ajax?class=SelectPessoasVinculadas.php",
                        dataType: "json",
                        data: {
                             'id_pessoa_pai': data[0].id_pessoa,
                             token: '<?= $request->token ?>'
                        },
                        success: function (data) {
                           $('#usuario_vinculado').html(data);
                        }
                    });
                }
            });
        }
    }

    // Função para limpar os campos caso a busca esteja vazia
    function limpaCamposPessoa() {
        var busca = $('#busca_pessoa').val();
        if (busca == "") {
            $('#id_pessoa').val('');
        }
    }

    $(document).on('click', '#habilita_busca_pessoa', function () {
        $('#id_pessoa').val('');
        $('#busca_pessoa').val('');
        $('#busca_pessoa').attr("readonly", false);
        $('#busca_pessoa').focus();
    });

    $(document).on('click', '#gera_senha', function(){
        var senha = geraSenha();
        $("#senha").val(senha);
        $("#confirm_senha").val(senha);
        alert('Senha gerada: '+senha);
    });

    $(document).on('submit', '#usuario_form', function () {
        var senhaUm = $("#senha").val();
        var senhaDois = $("#confirm_senha").val();
        var operacao = $("#operacao").attr('name');
        var id_pessoa = $("#id_pessoa").val();
        if (senhaUm != senhaDois) {
            alert("As senhas não coincidem!");
            $("#senha").val('');
            $("#confirm_senha").val('');
            return false;
        }
        if ((senhaUm.length < 8) && (senhaDois.length < 8)) {
            if ((senhaUm.length == 0) && (senhaDois.length == 0) && (operacao == 'alterar')) {
            } else {
                alert("A senha deve conter 8 ou mais caracteres!");
                $("#senha").val('');
                $("#confirm_senha").val('');
                return false;
            }
        }
        if(!id_pessoa){
            alert("Deve-se vincular uma pessoa válida ao usuário!");
            return false;
        }
        modalAguarde();
    });
</script>