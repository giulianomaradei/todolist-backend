<?php
require_once(__DIR__."/../class/System.php");

if (isset($_GET['alterar'])) {
	$tituloPainel = 'Alterar';
	$operacao = 'alterar';
	$id = (int) $_GET['alterar'];
	$dados = DBRead('', 'tb_usuario', "WHERE id_usuario = $id");
	$status = $dados[0]['status'];
	$email = $dados[0]['email'];
    $id_asterisk = $dados[0]['id_asterisk'];
    $id_ponto = $dados[0]['id_ponto'];
    $id_otrs = $dados[0]['id_otrs'];
	$id_pessoa = $dados[0]['id_pessoa'];
    $perfil = $dados[0]['id_perfil_sistema'];
	$dados_pessoa = DBRead('', 'tb_pessoa', "WHERE id_pessoa = '$id_pessoa'");
	$nome_pessoa = $dados_pessoa[0]['nome'];
    $lider = $dados[0]['lider_direto'];
    $ips_permitidos = $dados[0]['ips_permitidos'];
    
    $id_infinity = $dados[0]['id_infinity'];

	$senha = '';
	$confirm_senha = '';
	$importante = '';
} else {
	$tituloPainel = 'Inserir';
	$operacao = 'inserir';
	$id = 1;
	$status = '1';
	$email = '';
	$id_pessoa = '';
    $nome_pessoa = '';  
    $lider = '';
    $ips_permitidos = '';

    $id_infinity = '';

	$perfil = '';
	$senha = '';
	$confirm_senha = '';
	$importante = 'required';
    $id_asterisk = '';
    $id_ponto = '';
    $id_otrs = '';
}
?>

<div class="row">
    <div class="col-md-8 col-md-offset-2">
        <div class="panel panel-default">
            <div class="panel-heading clearfix">
                <h3 class="panel-title text-left pull-left"><?=$tituloPainel?> usuário (V2):</h3>
                <?php if (isset($_GET['alterar'])) {echo "<div class=\"panel-title text-right pull-right\"><a  href=\"/api/ajax?class=Usuario.php?excluir= $id&token=". $request->token ."\" onclick=\"if (!confirm('Tem certeza que deseja excluir o registro?')) { return false; } else { modalAguarde(); }\"><button class=\"btn btn-xs btn-danger\"><i class=\"fa fa-trash\"></i> Excluir</button></a></div>";}?>
            </div>
            <form method="post" action="/api/ajax?class=Usuario.php" id="usuario_form" style="margin-bottom: 0;" enctype="multipart/form-data">
		        <input type="hidden" name="token" value="<?php echo $request->token ?>">
                <div class="panel-body" style="padding-bottom: 0;">

                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-group">
                            <?php
                            if (isset($_GET['alterar'])){
                                $arquivo = 'inc/upload-usuario/'.$id.'.jpg';

                                if (file_exists($arquivo)){
                                ?>
                                    <div class="row text-center">
                                        <img id="imagem" src="<?=$arquivo?>" alt="Imagem responsiva" class="img-thumbnail" style = "width:120px; height: 90px;">
                                    </div>
                                    <div class="row text-center">
                                        <small class="form-text text-muted text-center">Tamanho máximo 5MB!</small>
                                    </div>
                                    <div class="row text-center" style="margin-top: 5px;">
                                        <label class="btn btn-sm" style="background-color: #52abb7; color: white;">
                                            Alterar foto!
                                            <input type="file" id="foto" name="foto" accept=".png, .jpg, .jpeg" value="" style="display: none;">
                                        </label>

                                    </div>    
  
                                <?php                                
                                }else{
                                ?>
                                    <div class="row text-center">
                                        <img id="imagem" src="inc/img/avatar.png" alt="Imagem responsiva" class="img-thumbnail" style = "width:120px; height: 90px;">
                                    </div>
                                    <div class="row text-center">
                                        <small class="form-text text-muted text-center">Tamanho máximo 5MB!</small>
                                    </div>
                                    <div class="row text-center" style="margin-top: 5px;">
                                        <label class="btn btn-sm" style="background-color: #52abb7; color: white;">
                                            <span id='texto_foto'>Adicionar foto!</span>
                                            <input type="file" id="foto" name="foto" accept=".png, .jpg, .jpeg" value="" style="display: none;">
                                        </label>
                                    </div>
                                <?php  
                                }

                            ?>

                            <?php
                            }else{
                            ?>
                                <div class="row text-center">
                                    <img id="imagem" src="inc/img/avatar.png" alt="Imagem responsiva" class="img-thumbnail" style = "width:120px; height: 90px;">
                                </div>
                                <div class="row text-center" style="margin-top: 5px;">
                                    <label class="btn btn-sm" style="background-color: #52abb7; color: white;">
                                        Adicionar foto!
                                        <input type="file" id="foto" name="foto" accept=".png, .jpg, .jpeg" value="" style="display: none;">
                                    </label>
                                </div>
                            <?php
                            }
                            ?>
                                
                            </div>
                        </div>
                                        
                        <div class="col-md-10">
                            <div class="form-group">
                                <label>*Pessoa (funcionário):</label>
                                <div class="input-group">
                                    <input class="form-control input-sm" id="busca_pessoa" type="text" name="busca_pessoa"  value="<?=$nome_pessoa;?>" placeholder="Informe o nome ou CPF/CNPJ..." autocomplete="off" readonly required>
                                    <div class="input-group-btn">
                                        <button class="btn btn-info btn-sm" id="habilita_busca_pessoa" name="habilita_busca_pessoa" type="button" title="Clique para selecionar a pessoa" style="height: 30px;"><i class="fa fa-search"></i></button>
                                    </div>
                                </div>
                                <input type="hidden" name="id_pessoa" id="id_pessoa" value="<?=$id_pessoa;?>">
                                
                            </div>
                            <div class="form-group">
                                <label>*Email:</label>
                                <input name="email" type="email" class="form-control input-sm" value="<?=$email;?>" autocomplete="off" required>
                            </div>
                        </div>
                           
                    </div>
                    <div class="row">                       
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>*Senha:</label>
                                <input name="senha" type="password" class="form-control input-sm" id="senha" <?php if ($operacao == 'alterar') {echo 'placeholder="(Deixe em branco p/ manter!)"';} else {echo 'placeholder="(Min. 8 caracteres!)"';} ?> value="<?=$senha;?>" autocomplete="off" <?=$importante;?>>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>*Confirmação da Senha:</label>
                                <input name="confirm_senha" type="password" class="form-control input-sm" id="confirm_senha" <?php if ($operacao == 'alterar'){	echo 'placeholder="(Deixe em branco p/ manter!)"';} else {echo 'placeholder="(Min. 8 caracteres!)"';}?> value="<?=$confirm_senha;?>" autocomplete="off" <?=$importante;?>>
                            </div>
                        </div>                        
                    </div>
                    <div class="row">                       
                        <div class="col-md-6">
                            <div class="form-group">
                               <div class="form-group">
                                    <label>*Perfil:</label>
                                    <select class="form-control input-sm" name="perfil" required>
                                        <option></option>
                                        <?php
                                        $dados = DBRead('', 'tb_perfil_sistema', "ORDER BY nome ASC");
                                        if ($dados) {
                                            foreach ($dados as $conteudo) {
                                                $idPerfil = $conteudo['id_perfil_sistema'];
                                                $nomeSelect = $conteudo['nome'];
                                                $selected = $perfil == $idPerfil ? "selected" : "";
                                                echo "<option value='$idPerfil'".$selected.">$nomeSelect</option>";
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Líder Direto:</label>
                                <select class="form-control input-sm" name="lider">
                                    <option value = ''>Nenhum</option>
                                        <?php
                                        $dados = DBRead('', 'tb_usuario a', "INNER JOIN tb_pessoa b ON a.id_pessoa = b.id_pessoa WHERE a.status = 1 ORDER BY nome ASC");
                                        if ($dados) {
                                            foreach ($dados as $conteudo) {
                                                $idLider = $conteudo['id_usuario'];
                                                $nomeSelect = $conteudo['nome'];
                                                $selected = $lider == $idLider ? "selected" : "";
                                                echo "<option value='$idLider'".$selected.">$nomeSelect</option>";
                                            }
                                        }
                                        ?>
                                </select>
                            </div>
                        </div>                        
                    </div>
                    <div class="row"> 
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>ID Asterisk:</label>
                                <input name="id_asterisk" type="text" class="form-control input-sm" value="<?=$id_asterisk;?>" autocomplete="off">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>ID Ponto:</label>
                                <input name="id_ponto" type="text" class="form-control input-sm" value="<?=$id_ponto;?>" autocomplete="off">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>ID OTRS:</label>
                                <input name="id_otrs" type="text" class="form-control input-sm" value="<?=$id_otrs;?>" autocomplete="off">
                            </div>
                        </div>      
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>ID Infinity:</label>
                                <input name="id_infinity" type="text" class="form-control input-sm" value="<?=$id_infinity;?>" autocomplete="off">
                            </div>
                        </div>                        
                        
                    </div>  
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>*Status:</label>
                                <select class="form-control input-sm" name="status" required>
                                    <option value='1' <?php if ($status == 1) {echo 'selected';}?>>Ativo</option>
                                    <option value='0' <?php if ($status == 0) {echo 'selected';}?>>Inativo</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-9">
                            <div class="form-group">
                                <label>IPs Permitidos:</label>
                                <input name="ips_permitidos" type="text" class="form-control input-sm" value="<?=$ips_permitidos;?>" placeholder='(Deixe em branco para qualquer IP ou insira os IPs separados por ponto e vírgula. EX: 192.168.0.1;192.168.0.2)' autocomplete="off">
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

    $(function(){
        $('[data-toggle="tooltip"]').tooltip();
    });
    
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
                            'atributo' : 'funcionario',
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
                carregarDadosPessoa(ui.item.id_pessoa);
                return false;
            },
            select: function (event, ui) {
                $("#busca_pessoa").val(ui.item.nome);
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
        return $("<li>").append("<a><strong>"+item.id_pessoa+" - "+ item.nome + " </strong><br>" +item.razao_social+ "<br>" +item.cpf_cnpj+ "</a><hr style='margin-bottom: 0px;'>").appendTo(ul);
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

    $(document).ready( function() {
    	
		function readURL(input) {
		    if (input.files && input.files[0]) {
		        var reader = new FileReader();
		        
		        reader.onload = function (e) {
		            $('#imagem').attr('src', e.target.result);
		        }
		        
		        reader.readAsDataURL(input.files[0]);
                $('#texto_foto').text('Alterar foto!');

		    }
		}

		$("#foto").change(function(){
		    readURL(this);
		}); 	
	});
</script>