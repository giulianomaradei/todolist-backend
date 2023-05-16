<?php
require_once(__DIR__."/../class/System.php");

if (isset($_GET['alterar'])) {

    $pagina_origem = $_GET['pagina-origem'];
    $id_negocio = $_GET['id-negocio'];

	$tituloPainel = 'Alterar';
	$operacao = 'alterar';
	$id = (int) $_GET['alterar'];
	$dados = DBRead('', 'tb_pessoa', "WHERE id_pessoa = $id");
	$data_atualizacao = converteDataHora($dados[0]['data_atualizacao']);
	$candidato = ($dados[0]['candidato'] == 1) ? $candidato = 'checked' : '';
	$cliente = ($dados[0]['cliente'] == 1) ? $cliente = 'checked' : '';
	$fornecedor = ($dados[0]['fornecedor'] == 1) ? $fornecedor = 'checked' : '';
	$funcionario = ($dados[0]['funcionario'] == 1) ? $funcionario = 'checked' : '';
	$prospeccao = ($dados[0]['prospeccao'] == 1) ? $prospeccao = 'checked' : '';
	$nome = $dados[0]['nome'];
	$razao_social = $dados[0]['razao_social'];
	$tipo = $dados[0]['tipo'];
	$cpf_cnpj = formataCampo('cpf_cnpj',$dados[0]['cpf_cnpj']);
	$inscricao_estadual = $dados[0]['inscricao_estadual'];
	$data_nascimento = ($dados[0]['data_nascimento'] != '0000-00-00') ? converteData($dados[0]['data_nascimento']) : '';
	$sexo = $dados[0]['sexo'];
	$status = $dados[0]['status'];
	$fone1 = formataCampo('fone',$dados[0]['fone1']);
	$fone2 = formataCampo('fone',$dados[0]['fone2']);
	$fone3 = formataCampo('fone',$dados[0]['fone3']);
	$email1 = $dados[0]['email1'];
	$email2 = $dados[0]['email2'];
	$skype = $dados[0]['skype'];
	$facebook = $dados[0]['facebook'];
	$site = $dados[0]['site'];
	$obs_interna = $dados[0]['obs_interna'];
    $obs_externa = $dados[0]['obs_externa'];
	$logradouro = $dados[0]['logradouro'];
	$numero = $dados[0]['numero'];
	$bairro = $dados[0]['bairro'];
	$complemento = $dados[0]['complemento'];
	$cidade = $dados[0]['id_cidade'];
	$dados_cidade = DBRead('', 'tb_cidade', "WHERE id_cidade = '$cidade'");
	$estado = $dados_cidade[0]['id_estado'];
	$cep = formataCampo('cep',$dados[0]['cep']);
	$endereco_correspondencia = $dados[0]['endereco_correspondencia'];
	$linkedin = $dados[0]['linkedin'];
	$instagram = $dados[0]['instagram'];
    $twitter = $dados[0]['twitter'];

    $dados_candidato = DBRead('', 'tb_pessoa_rh_dados_pessoais', "WHERE id_pessoa = $id");

    $dados_vinculos = DBRead('', 'tb_vinculo_pessoa a', "INNER JOIN tb_pessoa b ON a.id_pessoa_pai = b.id_pessoa WHERE a.id_pessoa_filho = $id");

    $dados_prospeccao = DBRead('', 'tb_pessoa_prospeccao', "WHERE id_pessoa = $id");

    $dados_pessoa_indicacao = DBRead('', 'tb_pessoa', "WHERE id_pessoa = '".$dados_prospeccao[0]['id_pessoa_indicacao']."' ");
	$nome_pessoa_indicacao = $dados_pessoa_indicacao[0]['nome'];

    $id_lead_origem = $dados_prospeccao[0]['id_lead_origem'];
    $segmento = $dados_prospeccao[0]['segmento'];
    $quantidade_clientes = $dados_prospeccao[0]['quantidade_clientes'];
    $provedor = $dados_prospeccao[0]['provedor'];
    $estrutura_tres_niveis = $dados_prospeccao[0]['estrutura_tres_niveis'];
    $qtde_funcionarios_nivel_1 = $dados_prospeccao[0]['qtde_funcionarios_nivel_1'];
    $qtde_funcionarios_nivel_2 = $dados_prospeccao[0]['qtde_funcionarios_nivel_2'];
    $qtde_funcionarios_nivel_3 = $dados_prospeccao[0]['qtde_funcionarios_nivel_3'];
    $quantidade_funcionarios_tres_niveis = $dados_prospeccao[0]['quantidade_funcionarios_tres_niveis'];
    $central_telefonica = $dados_prospeccao[0]['central_telefonica'];
    $sistema_de_gestao = $dados_prospeccao[0]['sistema_de_gestao'];
    $horario_mais_ligacoes = $dados_prospeccao[0]['horario_mais_ligacoes'];
    $atendimento_fideliza_cliente = $dados_prospeccao[0]['atendimento_fideliza_cliente'];
    $terceirizacao_atendimento = $dados_prospeccao[0]['terceirizacao_atendimento'];
    $qualificacao_cliente = $dados_prospeccao[0]['qualificacao_cliente'];
    $concorrencia = $dados_prospeccao[0]['concorrencia'];
    $reclamacoes_redes_sociais = $dados_prospeccao[0]['reclamacoes_redes_sociais'];
    $pessoa_contato = $dados_prospeccao[0]['pessoa_contato'];
    $exp_outra_assessoria_redes = $dados_prospeccao[0]['exp_outra_assessoria_redes'];
    $exp_outra_qual = $dados_prospeccao[0]['exp_outra_qual'];
    $pq_nao_tem_mais = $dados_prospeccao[0]['pq_nao_tem_mais'];
    $obs_lead = $dados_prospeccao[0]['obs_lead'];
    $atendimento_chat = $dados_prospeccao[0]['atendimento_chat'];
    $qtd_atendimentos_simultaneos = $dados_prospeccao[0]['qtd_atendimentos_simultaneos'];
    $bina = $dados_prospeccao[0]['bina'];
    $obs_bina = $dados_prospeccao[0]['obs_bina'];
    $data_atualizacao_clientes = $dados_prospeccao[0]['data_atualizacao_clientes'];
    $id_pessoa_indicacao = $dados_prospeccao[0]['id_pessoa_indicacao'];
    $data_pessoa_indicacao = converteData($dados_prospeccao[0]['data_pessoa_indicacao']);

    if ($data_atualizacao_clientes) {
        $data_atualizacao_clientes = explode(' ',converteDataHora($data_atualizacao_clientes));
        $data_atualizacao_clientes = $data_atualizacao_clientes[0];

    } else {
        $data_atualizacao_clientes = 'N/D';
    }

    if ($atendimento_chat == 1) {
        $at_chat = '';
    } else {
        $at_chat = 'disabled';
    }

    if ($exp_outra_assessoria_redes == 1) {
        $disabled = '';
    } else {
        $disabled = 'disabled';
    }

    $dados_acesso_internet = DBRead('', 'tb_pessoa_prospeccao_tipo_equipamento', "WHERE id_pessoa_prospeccao = '".$dados_prospeccao[0]['id_pessoa_prospeccao']."' ", 'id_tipo_equipamento');

    $dados_equipamentos_redes = DBRead('', 'tb_pessoa_prospeccao_tipo_equipamento_redes', "WHERE id_pessoa_prospeccao = '".$dados_prospeccao[0]['id_pessoa_prospeccao']."' ", 'id_tipo_equipamento_redes, outro');

    $outros_equipamentos = '';
    if($dados_equipamentos_redes){
        foreach($dados_equipamentos_redes as $conteudo){
            if($conteudo['outro'] != ''){
                $outros_equipamentos = $outros_equipamentos.' '.$conteudo['outro'].';';
            }   
        }
        $outros_equipamentos = substr($outros_equipamentos, 0, -1);
    }

    if($dados_vinculos){
        $col = 'col-md-3';
        foreach($dados_vinculos as $conteudo){
<<<<<<< HEAD
            $vinculos .= "<i class=\"fa fa-link\"></i> <a style=\"color: #337ab7;\" href=\"/api/iframe?token=<?php echo $request->token ?>&view=pessoa-form&alterar=".$conteudo['id_pessoa_pai']."\">".$conteudo['nome']."</a> | ";
=======
            $vinculos .= "<i class=\"fa fa-link\"></i> <a style=\"color: #337ab7;\" href=\"/api/iframe?token=".$request->token."&view=pessoa-form&alterar=".$conteudo['id_pessoa_pai']."\">".$conteudo['nome']."</a> | ";
>>>>>>> 5cfe18e1256c22b2f7585786435771d80b84680b
        }
        $vinculos = substr($vinculos, 0, -3);
    }else{
        $col = 'col-md-4';
        $vinculos = '';
    }

} else {

    $id_rd_conversao = $_GET['id_rd_conversao'];
    $flag = $_GET['flag'];

    if ($id_rd_conversao && $flag) {
        $dados_rd_conversao = DBRead('', 'tb_rd_conversao', "WHERE id_rd_conversao = $id_rd_conversao");
        $nome = $dados_rd_conversao[0]['company'];
        $razao_social = $dados_rd_conversao[0]['razao'];
        $tipo = 'pj';
        $cpf_cnpj = $dados_rd_conversao[0]['cnpj'];
        $fone1 = $dados_rd_conversao[0]['telefone'];
        $email1 = $dados_rd_conversao[0]['email'];

    } else {
        $nome = '';
        $razao_social = '';
        $tipo = '';
        $cpf_cnpj = '';
        $fone1 = '';
        $email1 = '';
    }

    $pagina_origem = $_GET['pagina-origem'];
    
	$tituloPainel = 'Inserir';
	$operacao = 'inserir';
	$id = 1;
	$candidato = '';
	$cliente = '';
	$fornecedor = '';
	$funcionario = '';
	$prospeccao = '';
	$inscricao_estadual = '';
	$data_nascimento = '';
	$sexo = '';
	$status = '';
	$fone2 = '';
	$fone3 = '';
	$email2 = '';
	$skype = '';
	$facebook = '';
	$site = '';
    $obs_interna = '';
	$obs_externa = '';
	$logradouro = '';
	$numero = '';
	$bairro = '';
	$complemento = '';
	$cidade = '9999999';
	$estado = '99';
	$cep = '';
    $endereco_correspondencia = '';
    $linkedin = '';
	$instagram = '';
	$twitter = '';
    $col = 'col-md-12';
    $display = 'none';
    $at_chat = 'disabled';
    $nome_pessoa_indicacao = '';
    $id_pessoa_indicacao = '';
    $data_pessoa_indicacao = '';
}
?>

<style>
    .select2{
        width: 100% !important;
    }
</style>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    <div class="row">
                    <h3 class="panel-title text-left <?= $col ?>"><?= $tituloPainel ?> pessoa:</h3>
                    <?php 
                    if(isset($_GET['alterar'])){
                        if($vinculos){
                            echo "<h3 class='$col panel-title text-center'>$vinculos</h3>";
                        }
                        echo "<h3 class=\"panel-title text-center $col\">Atualizado em: $data_atualizacao</h3> ";
                        
                        if($id == 2){
                            $disabled = "style='pointer-events:none; opacity: 0.4;'";
                        }

                        echo "<div class=\"panel-title text-right $col\"><a id='exclui-pessoa' $disabled href=\"/api/ajax?class=Pessoa.php?excluir= $id&token=". $request->token ."\" onclick=\"if (!confirm('Tem certeza que deseja excluir o registro?')) { return false; } else { modalAguarde(); }\"><button class=\"btn btn-xs btn-danger\"><i class=\"fa fa-trash\"></i> Excluir</button></a></div>";
                    }
                    ?>
                    </div>
                </div>
                <form method="post" action="/api/ajax?class=Pessoa.php" id="pessoa_form" style="margin-bottom: 0;">
		            <input type="hidden" name="token" value="<?php echo $request->token ?>">
                    <input type="hidden" name="id_usuario" value="<?=$_SESSION['id_usuario']?>">
                    <input type="hidden" name="pagina_origem" value="<?=$pagina_origem?>">
                    <input type="hidden" name="id_negocio" value="<?=$id_negocio?>">
                    <input type="hidden" name="flag" value="<?=$flag?>">
                    <input type="hidden" name="id_rd_conversao" value="<?=$id_rd_conversao?>">
                    <div class="panel-body" style="padding-bottom: 0;">

                        <!-- nav tabs -->
                        <ul class="nav nav-tabs">
                            <li class="aba1 active">
                                <a data-toggle="tab" href="#tab1">Dados pessoais</a>
                            </li>
                            <li class="aba2">
                                <a data-toggle="tab" href="#tab2">Dados prospecção</a>
                            </li>
                            <li class="aba3" style="display: <?=$display?>">
                                <a data-toggle="tab" href="#tab3">Vínculos</a>
                            </li>
                        </ul>
                        <!-- end nav tabs -->

                         <div class="tab-content">

                            <!-- tab 1 Dados pessoais  -->
                            <div id="tab1" class="tab-pane fade in active">
                                <br>
                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="panel panel-default">
                                            <div class="panel-heading">
                                                <div class="row">

                                                    <?php 
                                                    if($dados_candidato){
                                                    ?>
                                                        <h3 class="panel-title text-left col-md-6">Atributo(s): </h3>
                                                        <div class="panel-title text-right col-md-6">
                                                            <a href="/api/iframe?token=<?php echo $request->token ?>&view=relatorio-pessoa-rh&id_pessoa=<?=$id?>" target="blank">
                                                                <span class="btn btn-xs btn-primary"><i class="fas fa-address-book"></i> Currículo</span>
                                                            </a>
                                                        </div>
                                                    <?php
                                                    }else{
                                                    ?>
                                                        <h3 class="panel-title text-left col-md-12">Atributo(s): </h3>
                                                    <?php
                                                    }
                                                    ?>
                                                    
                                                </div>
                                            </div>
                                            <div class="panel-body" style="max-height: 171px; overflow-y:auto;">
                                                <div class="row" style="margin-top: 7px;">
                                                    <div class="col-md-12">
                                                        <input name="candidato" id="candidato" type="checkbox" value="1" <?=$candidato;?>>
                                                        <label for="candidato">Candidato (RH)</label>
                                                    </div>
                                                </div>
                                                <div class="row" style="margin-top: 7px;">
                                                    <div class="col-md-12" style="margin-top: 1px;">
                                                        <input name="cliente" id="cliente" type="checkbox" value="1" <?=$cliente;?>>
                                                        <label for="cliente">Cliente</label>
                                                    </div>
                                                </div>
                                                <div class="row" style="margin-top: 7px;">
                                                    <div class="col-md-12" style="margin-top: 1px;">
                                                        <input name="fornecedor" id="fornecedor" type="checkbox" value="1" <?=$fornecedor;?>>
                                                        <label for="fornecedor">Fornecedor</label>
                                                    </div>
                                                </div>
                                                <div class="row" style="margin-top: 7px;">
                                                    <div class="col-md-12" style="margin-top: 1px;">
                                                        <input name="funcionario" id="funcionario" type="checkbox" value="1" <?=$funcionario;?>>
                                                        <label for="funcionario">Funcionário</label>
                                                    </div>
                                                </div>
                                             <div class="row">
                                                    <div class="col-md-12" style="margin-top: 7px;">
                                                        <input name="prospeccao" id="prospeccao" type="checkbox" value="1" <?=$prospeccao;?>>
                                                        <label for="prospeccao">Prospecção</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-10">
                                        <div class="panel panel-default">
                                            <div class="panel-heading">
                                                <h3 class="panel-title">Dados pessoais:</h3>
                                            </div>
                                            <div class="panel-body">
                                                <div class="row">
                                                    <div class="col-md-5">
                                                        <div class="form-group">
                                                            <label>*Nome:</label>
                                                            <input name="nome" type="text" class="form-control input-sm" id="nome" value="<?=$nome;?>" autocomplete="off" autofocus>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-5">
                                                        <div class="form-group">
                                                            <label>Razão Social:</label>
                                                            <input name="razao_social" type="text" class="form-control input-sm" value="<?=$razao_social;?>" autocomplete="off">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                            <label>Tipo:</label>
                                                            <select class="form-control input-sm" name="tipo">
                                                                <option value='pf' <?php if ($tipo == 'pf') {echo 'selected';}?>>PF</option>
                                                                <option value='pj' <?php if ($tipo == 'pj') {echo 'selected';}?>>PJ</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label id="label_cpf_cnpj">CPF/CNPJ:</label>
                                                            <input name="cpf_cnpj" type="text" class="form-control input-sm cpf_cnpj" id="input_cpf_cnpj" value="<?=$cpf_cnpj;?>" autocomplete="off" />
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label>Insc. Estadual/RG:</label>
                                                            <input name="inscricao_estadual" type="text" class="form-control input-sm" value="<?=$inscricao_estadual;?>" autocomplete="off" />
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                            <label>Nascimento:</label>
                                                            <input name="data_nascimento" type="text" class="form-control input-sm date calendar" value="<?=$data_nascimento;?>" autocomplete="off" />
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                            <label>Sexo:</label>
                                                            <select class="form-control input-sm" name="sexo">
                                                                <option value='n'>ND</option>
                                                                <option value='' disabled>----------</option>
                                                                <option value='f' <?php if ($sexo == 'f') {echo 'selected';}?>>F</option>
                                                                <option value='m' <?php if ($sexo == 'm') {echo 'selected';}?>>M</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                            <label>Status:</label>
                                                            <select class="form-control input-sm" name="status">
                                                                <option value='1' <?php if ($status == '1') {echo 'selected';}?>>Ativo</option>
                                                                <option value='0' <?php if ($status == '0') {echo 'selected';}?>>Inativo</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="panel panel-default">
                                            <div class="panel-heading">
                                                <h3 class="panel-title">Contato:</h3>
                                            </div>
                                            <div class="panel-body">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>Fone(1):</label>
                                                            <input name="fone1" type="text" class="form-control input-sm phone" value="<?=$fone1;?>" autocomplete="off">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>Fone(2):</label>
                                                            <input name="fone2" type="text" class="form-control input-sm phone" value="<?=$fone2;?>" autocomplete="off">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>Fone(3):</label>
                                                            <input name="fone3" type="text" class="form-control input-sm phone" value="<?=$fone3;?>" autocomplete="off">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>E-Mail(1):</label>
                                                            <input name="email1" type="email" class="form-control input-sm" value="<?=$email1;?>" autocomplete="off">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>E-Mail(2):</label>
                                                            <input name="email2" type="email" class="form-control input-sm" value="<?=$email2;?>" autocomplete="off">
                                                        </div>
                                                    </div>
                                                   
                                                </div>
                                                <div class="row">

                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Instagram:</label>
                                                            <input name="instagram" type="text" class="form-control input-sm" value="<?=$instagram;?>" autocomplete="off">
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Skype:</label>
                                                            <input name="skype" type="text" class="form-control input-sm" value="<?=$skype;?>" autocomplete="off">
                                                        </div>
                                                    </div>
                                                    
                                                   
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Linkedin:</label>
                                                            <input name="linkedin" type="text" class="form-control input-sm" value="<?=$linkedin;?>" autocomplete="off">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Facebook:</label>
                                                            <input name="facebook" type="text" class="form-control input-sm" value="<?=$facebook;?>" autocomplete="off">
                                                        </div>
                                                    </div>
                                                    
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Twitter:</label>
                                                            <input name="twitter" type="twitter" class="form-control input-sm" value="<?=$twitter;?>" autocomplete="off">
                                                        </div>
                                                    </div>
                                                   
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Site:</label>
                                                            <input name="site" type="text" class="form-control input-sm" value="<?=$site;?>" autocomplete="off">
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="panel panel-default"><!--inicio do painel-->
                                            <div class="panel-heading">
                                                <h3 class="panel-title">Endereço:</h3>
                                            </div>
                                            <div class="panel-body">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>CEP:</label>
                                                            <input name="cep" id="cep" pattern="[0-9]{5}-[0-9]{3}" type="text" class="form-control input-sm cep" value="<?=$cep;?>" autocomplete="off">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Nº:</label>
                                                            <input name="numero" type="text" class="form-control input-sm" value="<?=$numero;?>" autocomplete="off">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label>Logradouro:</label>
                                                            <input name="logradouro" id="logradouro" type="text" class="form-control input-sm" value="<?=$logradouro;?>" autocomplete="off">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Bairro:</label>
                                                            <input name="bairro" id="bairro" type="text" class="form-control input-sm" value="<?=$bairro;?>" autocomplete="off">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Complemento:</label>
                                                            <input name="complemento" type="text" class="form-control input-sm" value="<?=$complemento;?>" autocomplete="off" maxlength="29">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label>UF:</label>
                                                            <select class="form-control input-sm" name="estado" id="estado" required>
                                                                <option value='99'>ND</option>
                                                                <option value='' disabled>----------</option>
                                                                <?php
                                                                $dados = DBRead('', 'tb_estado', "WHERE id_estado != '99' ORDER BY sigla ASC");
                                                                if($dados){
                                                                    foreach($dados as $conteudo){
                                                                        $idSelect = $conteudo['id_estado'];
                                                                        $estadoSelect = $conteudo['sigla'];
                                                                        $selected = $estado == $idSelect ? "selected" : "";
                                                                        echo "<option value='$idSelect'".$selected.">$estadoSelect</option>";
                                                                    }
                                                                }
                                                                ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-9">
                                                        <div class="form-group">
                                                            <label>Cidade:</label>
                                                            <select class="form-control input-sm" id="cidade" name="cidade" required>
                                                                <?php
                                                                if($estado){
                                                                    $dados = DBRead('', 'tb_cidade', "WHERE id_estado = '$estado' ORDER BY nome ASC");
                                                                    if($dados){
                                                                        foreach($dados as $conteudo){
                                                                            $idSelect = $conteudo['id_cidade'];
                                                                            $cidadeSelect = $conteudo['nome'];
                                                                            $selected = $cidade == $idSelect ? "selected" : "";
                                                                            echo "<option value='$idSelect'".$selected.">$cidadeSelect</option>";
                                                                        }
                                                                    }
                                                                }
                                                                ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label>Endereço de correspondência:</label>
                                                            <input name="endereco_correspondencia" type="text" class="form-control input-sm" value="<?=$endereco_correspondencia;?>" autocomplete="off">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="panel panel-default">
                                            <div class="panel-heading">
                                                <h3 class="panel-title">Observações:</h3>
                                            </div>
                                            <div class="panel-body">
                                                   
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Obs. Interna (Só Belluno pode ver):</label>
                                                            <textarea class="form-control " name="obs_interna" style="resize: vertical; height: 100px;"><?=$obs_interna;?></textarea>
                                                        </div>                                                
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Obs. Interna (Belluno e painel podem ver):</label>
                                                            <textarea class="form-control " name="obs_externa" style="resize: vertical; height: 100px;"><?=$obs_externa;?></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                </div>
                            </div>
                            <!-- end tab 1 Dados pessoais -->

                            <!-- tab 2 Dados prospecção  -->
                            <div id="tab2" class="tab-pane fade in">
                                <br>
                                <div class="row" id="row-lead">
                                    <div class="col-md-12">
                                        <div class="panel panel-default">
                                            <div class="panel-heading">
                                                <div class="row">
                                                    <h3 class="panel-title text-left col-md-6">Lead: </h3>
                                                    <?php 
                                                    if(isset($_GET['alterar'])){
                                                    ?>
                                                        <div class="panel-title text-right col-md-6">
                                                            <a class="btn btn-xs btn-primary" href="/api/iframe?token=<?php echo $request->token ?>&view=lead-negocio-form&pessoa=<?=$id?>" style="color: white;">
                                                                <i class="fa fa-plus"></i> Novo Negócio
                                                            </a>
                                                        </div>
                                                    <?php
                                                    }
                                                    ?>
                                                </div>
                                            </div>
                                            <div class="panel-body">
                                                <div class="row" style="margin-bottom: 15px;">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>Origem:</label>
                                                            <select name="origem" class="form-control input-sm" id="origem">
                                                            <option value="0"> --- </option>
                                                            <?php
                                                                $dados_origem = DBRead('','tb_lead_origem',"ORDER BY descricao");
                                                                if($dados_origem){
                                                                    foreach ($dados_origem as $conteudo_origem) {
                                                                        $selected = $id_lead_origem == $conteudo_origem['id_lead_origem'] ? "selected" : "";
                                                                        echo "<option value='".$conteudo_origem['id_lead_origem']."' ".$selected.">".ucwords(strtolower($conteudo_origem['descricao']))	."</option>";
                                                                    }
                                                                }
                                                            ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>Segmento:</label>
                                                            <select class="form-control input-sm" name="segmento" id="segmento">
                                                                <option value="0">---</option>
                                                                <?php
                                                                    $dados_segmento = DBRead('', 'tb_lead_segmento', "ORDER BY nome");
                                                                    foreach($dados_segmento as $conteudo){
                                                                        $selected = $segmento == $conteudo['id_lead_segmento'] ? "selected" : "";
                                                                        ?>
                                                                        <option value="<?=$conteudo['id_lead_segmento']?>" <?=$selected?>><?=$conteudo['nome']?></option>
                                                                <?php
                                                                    }
                                                                ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>Quantidade de clientes: (Atualizado em: <?=  $data_atualizacao_clientes ?>)</label>
                                                            <input type="text" class="form-control input-sm number_int" autocomplete="off" name="quantidade_clientes" id="quantidade_clientes" value="<?= $quantidade_clientes ?>">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row" style="margin-bottom: 15px;">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Pessoa (indicação):</label>
                                                            <div class="input-group">
                                                                <input class="form-control input-sm ui-autocomplete-input" id="busca_pessoa" type="text" name="busca_pessoa" value="<?=$nome_pessoa_indicacao ?>" placeholder="Informe o nome ou CPF/CNPJ..." autocomplete="off" readonly>
                                                                <div class="input-group-btn">
                                                                    <button class="btn btn-info btn-sm" id="habilita_busca_pessoa" name="habilita_busca_pessoa" type="button" title="Clique para selecionar a pessoa" style="height: 30px;"><i class="fa fa-search"></i></button>
                                                                </div>
                                                            </div>
                                                            <input type="hidden" name="id_pessoa_indicacao" id="id_pessoa_indicacao" value="<?= $id_pessoa_indicacao ?>">  
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Data (indicação):</label>
                                                            <input class="form-control input-sm date calendar hasDatepicker" name="data_pessoa_indicacao" value="<?= $data_pessoa_indicacao ?>"  autocomplete="off" placeholder="dd/mm/aaaa" maxlength="10"> 
                                                        </div> 
                                                    </div>
                                                </div>
                                                
                                                <!-- Perguntas gestao de redes -->
                                                <div class="panel panel-default">
                                                    <div class="panel-heading clearfix">
                                                        <h3 class="panel-title text-left pull-left" style="margin-top: 2px;">Gestão de Redes</h3>
                                                        <div class="panel-title text-right pull-right">
                                                            <button data-toggle="collapse" data-target="#accordionRedes" class="btn btn-xs btn-info" type="button" title="Visualizar filtros" aria-expanded="false">
                                                                <i id="i_collapseRedes" class="fa fa-plus"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <div id="accordionRedes" class="panel-collapse collapse" aria-expanded="true">
                                                        <div class="panel-body">	                		
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label>Qual equipamento trabalha?</label>
                                                                        <select class="js-example-basic-multiple input-sm equipamento select2-hidden-accessible" id="equipamento_redes" name="equipamento_redes[]" multiple=""  tabindex="-1" aria-hidden="true">

                                                                        <?php 
                                                                            $dados_equipamentos = DBRead('', 'tb_tipo_equipamento_redes', "ORDER BY descricao ASC");

                                                                            foreach($dados_equipamentos as $conteudo){
                                                                        ?>
                                                                                <option value="<?=$conteudo['id_tipo_equipamento_redes']?>"><?=$conteudo['descricao']?></option>
                                                                        <?php
                                                                            }
                                                                        ?>
                                                                       
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group" id="div-equipamento">
                                                                        <label>Qual outro equipamento?</label>
                                                                        <input type="text" class="form-control input-sm" name="outro_equipamento" autocomplete="off" id="outro_equipamento" disabled value="<?=$outros_equipamentos?>">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label>Quem é o contato? (técnico ou dono)</label>
                                                                        <input type="text" class="form-control input-sm" name="contato" id="contato" value="<?=$pessoa_contato?>">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label>Já teve experiência com outra assessoria em redes?</label>
                                                                        <?php
                                                                            $sel_exp_assesoria[$exp_outra_assessoria_redes = $dados_prospeccao[0]['exp_outra_assessoria_redes']] = 'selected';
                                                                        ?>
                                                                        <select class="form-control input-sm" name="exp_assessoria_redes" id="exp_assessoria_redes">
                                                                            <option value="" <?=$sel_exp_assesoria['']?>>---</option>
                                                                            <option value="1" <?=$sel_exp_assesoria['1']?>>Sim</option>
                                                                            <option value="2" <?=$sel_exp_assesoria['2']?>>Não</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4">
                                                                   <div class="form-group">
                                                                        <label>Qual?</label>
                                                                        <input type="text" class="form-control input-sm" name="qual_assessoria" autocomplete="off" id="qual_assessoria" value="<?=$dados_prospeccao[0]['exp_outra_qual']?>" <?=$disabled?>>   
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label>Por que não tem mais?</label>
                                                                        <textarea type="text" class="form-control input-sm" name="pq_nao_tem_mais" id="pq_nao_tem_mais" rows="5" <?=$disabled?>><?= nl2br($dados_prospeccao[0]['pq_nao_tem_mais']) ?></textarea>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- end Perguntas gestao de redes -->

                                                <!-- <div class="panel panel-default">
                                                    <div class="panel-heading clearfix">
                                                        <h3 class="panel-title text-left pull-left" style="margin-top: 2px;">Call Center - Pesquisa</h3>
                                                        <div class="panel-title text-right pull-right">
                                                            <button data-toggle="collapse" data-target="#accordionPesquisa" class="btn btn-xs btn-info" type="button" title="Visualizar filtros" aria-expanded="false">
                                                                <i id="i_collapsePesquisa" class="fa fa-plus"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <div id="accordionPesquisa" class="panel-collapse collapse" aria-expanded="true" style="">
                                                        <div class="panel-body">	                		
                                                            
                                                        </div>
                                                    </div>
                                                </div> -->
                                                                            
                                                <!-- Perguntas call center -->                           
                                                <div class="panel panel-default">
                                                    <div class="panel-heading clearfix">
                                                        <h3 class="panel-title text-left pull-left" style="margin-top: 2px;">Call Center - Suporte</h3>
                                                        <div class="panel-title text-right pull-right">
                                                            <button data-toggle="collapse" data-target="#accordionSuporte" class="btn btn-xs btn-info" type="button" title="Call Center - Suporte" aria-expanded="false">
                                                                <i id="i_collapseSuporte" class="fa fa-plus"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <div id="accordionSuporte" class="panel-collapse collapse" aria-expanded="true">
                                                        <div class="panel-body">	                		
                                                            <div class="row">
                                                                <div class="col-md-3">
                                                                    <div class="form-group">
                                                                        <label>Sabe o que é a estrutura em três níveis: </label>
                                                                        <?php
                                                                            $sel_estrutura_tres_niveis[$estrutura_tres_niveis] = 'selected';
                                                                        ?>
                                                                        <select type="text" class="form-control input-sm" autocomplete="off" name="estrutura_tres_niveis" id="estrutura_tres_niveis">
                                                                            <option value="0" <?= $sel_estrutura_tres_niveis[0]?>> --- </option>
                                                                            <option value="1" <?= $sel_estrutura_tres_niveis[1]?>> Sim</option>
                                                                            <option value="2" <?= $sel_estrutura_tres_niveis[2]?>> Não</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <div class="form-group">
                                                                        <label>Quantos funcionários no nível 1?</label>
                                                                        <input type="text" class="form-control input-sm number_int" autocomplete="off" name="quantidade_funcionarios_nivel_1" id="quantidade_funcionarios_nivel_1" value="<?= $qtde_funcionarios_nivel_1 ?>">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <div class="form-group">
                                                                        <label>Quantos funcionários no nível 2?</label>
                                                                        <input type="text" class="form-control input-sm number_int" autocomplete="off" name="quantidade_funcionarios_nivel_2" id="quantidade_funcionarios_nivel_2" value="<?= $qtde_funcionarios_nivel_2 ?>">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <div class="form-group">
                                                                        <label>Quantos funcionários no nível 3?</label>
                                                                        <input type="text" class="form-control input-sm number_int" autocomplete="off" name="quantidade_funcionarios_nivel_3" id="quantidade_funcionarios_nivel_3" value="<?= $qtde_funcionarios_nivel_3 ?>">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-3">
                                                                    <div class="form-group">
                                                                        <label>Central telefônica:</label>
                                                                        <select name="central_telefonica" id="central_telefonica" class="form-control input-sm">
                                                                            <option value='0'>---</option>
                                                                            <?php
                                                                                $tipo_central = DBRead('', 'tb_tipo_central_telefonica', "WHERE id_tipo_central_telefonica != 1 ORDER BY descricao ASC");
                                                                                foreach($tipo_central as $conteudo){
                                                                                    $idCentral = $conteudo['id_tipo_central_telefonica'];
                                                                                    $selected = $central_telefonica == $idCentral ? "selected" : "";
                                                                                    echo "<option value='".$conteudo['id_tipo_central_telefonica']."' ".$selected.">".$conteudo['descricao']."</option>";
                                                                                }
                                                                            ?>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <div class="form-group">
                                                                        <label>Sistema de gestão:</label>
                                                                        <select name='sistema_de_gestao' id="sistema_de_gestao" class="form-control input-sm">
                                                                        <?php
                                                                            $sistema_gestao = DBRead('', 'tb_tipo_sistema_gestao', "ORDER BY nome ASC");
                                                                            if($sistema_gestao){
                                                                                echo "<option value='0'>---</option>";
                                                                                foreach($sistema_gestao as $conteudo){
                                                                                    $idSistema = $conteudo['id_tipo_sistema_gestao'];
                                                                                    $nomeSistema = $conteudo['nome'];
                                                                                    $selected = $sistema_de_gestao == $idSistema ? "selected" : "";
                                                                                    echo "<option value='".$idSistema."' ".$selected.">".$nomeSistema."</option>";
                                                                                }
                                                                            }
                                                                        ?>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <div class="form-group">
                                                                        <label>Quais os meios de acesso à internet: </label>
                                                                        <select class="js-example-basic-multiple input-sm acesso_internet" id="acesso_internet" name="acesso_internet[]" multiple="multiple">
                                                                        <?php

                                                                        $tipo_equipamento = DBRead('', 'tb_tipo_equipamento');
                                                                        foreach($tipo_equipamento as $conteudoEquipamento){
                                                                            echo "<option value='".$conteudoEquipamento['id_tipo_equipamento']."' $selected>".$conteudoEquipamento['descricao']."</option>";
                                                                        }
                                                                        ?>
                                                                        </select>
                                                                       
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <div class="form-group">
                                                                        <label>Atendimento de qualidade fideliza cliente?</label>
                                                                        <select class="form-control input-sm" name="atendimento_fideliza_cliente" id="atendimento_fideliza_cliente">
                                                                            <?php 
                                                                                $sel_atend_fideliza[$atendimento_fideliza_cliente] = 'selected';
                                                                            ?>
                                                                            <option value="" <?=$sel_atend_fideliza['null']?>>---</option>
                                                                            <option value="0" <?=$sel_atend_fideliza[0]?>>0</option>
                                                                            <option value="1"<?=$sel_atend_fideliza[1]?>>1</option>
                                                                            <option value="2" <?=$sel_atend_fideliza[2]?>>2</option>
                                                                            <option value="3" <?=$sel_atend_fideliza[3]?>>3</option>
                                                                            <option value="4" <?=$sel_atend_fideliza[4]?>>4</option>
                                                                            <option value="5" <?=$sel_atend_fideliza[5]?>>5</option>
                                                                            <option value="6" <?=$sel_atend_fideliza[6]?>>6</option>
                                                                            <option value="7" <?=$sel_atend_fideliza[7]?>>7</option>
                                                                            <option value="8" <?=$sel_atend_fideliza[8]?>>8</option>
                                                                            <option value="9" <?=$sel_atend_fideliza[9]?>>9</option>
                                                                            <option value="10" <?=$sel_atend_fideliza[10]?>>10</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-3">
                                                                    <div class="form-group">
                                                                        <label>Por que procura a terceirização do atendimento?</label>
                                                                        <select class="form-control input-sm" name="terceirizacao_atendimento" id="terceirizacao_atendimento">
                                                                            <?php
                                                                                $sel_terce_atend[$terceirizacao_atendimento] = 'selected';
                                                                            ?>
                                                                            <option value="0" <?= $sel_terce_atend[0]?>>---</option>
                                                                            <option value="6" <?= $sel_terce_atend[6]?>>É mais barato terceirizar</option>
                                                                            <option value="4" <?= $sel_terce_atend[4]?>>Falta de pessoas qualificadas para fazer internamente</option>
                                                                            <option value="5" <?= $sel_terce_atend[5]?>>Falta de tempo para gerir equipe interna</option>
                                                                            <option value="2" <?= $sel_terce_atend[2]?>>Melhorar qualidade</option>
                                                                            <option value="3" <?= $sel_terce_atend[3]?>>Outros</option>
                                                                            <option value="1" <?= $sel_terce_atend[1]?>>Redução de custo</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <div class="form-group">
                                                                        <label>Satisfação dos clientes em relação ao provedor?</label>
                                                                        <select class="form-control input-sm" name="qualificacao_cliente" id="qualificacao_cliente">
                                                                        <?php
                                                                            $sel_quali_cliente[$qualificacao_cliente] = 'selected';
                                                                        ?>
                                                                            <option value="" <?=$sel_quali_cliente['null']?>>---</option>
                                                                            <option value="0" <?=$sel_quali_cliente[0]?>>0</option>
                                                                            <option value="1" <?=$sel_quali_cliente[1]?>>1</option>
                                                                            <option value="2" <?=$sel_quali_cliente[2]?>>2</option>
                                                                            <option value="3" <?=$sel_quali_cliente[3]?>>3</option>
                                                                            <option value="4" <?=$sel_quali_cliente[4]?>>4</option>
                                                                            <option value="5" <?=$sel_quali_cliente[5]?>>5</option>
                                                                            <option value="6" <?=$sel_quali_cliente[6]?>>6</option>
                                                                            <option value="7" <?=$sel_quali_cliente[7]?>>7</option>
                                                                            <option value="8" <?=$sel_quali_cliente[8]?>>8</option>
                                                                            <option value="9" <?=$sel_quali_cliente[9]?>>9</option>
                                                                            <option value="10" <?=$sel_quali_cliente[10]?>>10</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <div class="form-group">
                                                                        <label>Comparação do atendimento em relação a concorrência?</label>
                                                                        <select class="form-control input-sm" name="concorrencia" id="concorrencia">
                                                                            <?php
                                                                                $sel_concorrencia[$concorrencia] = 'selected';
                                                                            ?>
                                                                            <option value="0" <?=$sel_concorrencia[0]?>>---</option>
                                                                            <option value="1" <?=$sel_concorrencia[1]?>>Igual</option>
                                                                            <option value="2" <?=$sel_concorrencia[2]?>>Melhor</option>
                                                                            <option value="3" <?=$sel_concorrencia[3]?>>Não Sei</option>
                                                                            <option value="4" <?=$sel_concorrencia[4]?>>Pior</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <div class="form-group">
                                                                        <label>Empresa tem reclamações nas Redes Sociais?</label>
                                                                        <select class="form-control input-sm" name="reclamacoes_redes_sociais" id="reclamacoes_redes_sociais">

                                                                        <?php
                                                                            $sel_reclamacoes[$reclamacoes_redes_sociais] = 'selected';
                                                                        ?>
                                                                            <option value="" <?=$sel_reclamacoes['null']?>>---</option>
                                                                            <option value="0" <?=$sel_reclamacoes[0]?>>0</option>
                                                                            <option value="1" <?=$sel_reclamacoes[1]?>>1</option>
                                                                            <option value="2" <?=$sel_reclamacoes[2]?>>2</option>
                                                                            <option value="3" <?=$sel_reclamacoes[3]?>>3</option>
                                                                            <option value="4" <?=$sel_reclamacoes[4]?>>4</option>
                                                                            <option value="5" <?=$sel_reclamacoes[5]?>>5</option>
                                                                            <option value="6" <?=$sel_reclamacoes[6]?>>6</option>
                                                                            <option value="7" <?=$sel_reclamacoes[7]?>>7</option>
                                                                            <option value="8" <?=$sel_reclamacoes[8]?>>8</option>
                                                                            <option value="9" <?=$sel_reclamacoes[9]?>>9</option>
                                                                            <option value="10" <?=$sel_reclamacoes[10]?>>10</option>
                                                                        </select>
                                                                    </div>
                                                                </div> 
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-3">
                                                                    <div class="form-group">
                                                                        <label>Tem atendimento por chat?</label>
                                                                            <?php
                                                                                $sel_atendimento_chat[$atendimento_chat] = 'selected';
                                                                            ?>                                     <select class="form-control input-sm" name="atendimento_chat" id="atendimento_chat" onChange="atendimentoChat();">
                                                                            <option value="" <?= $sel_atendimento_chat[''] ?>>---</option>
                                                                            <option value="1" <?= $sel_atendimento_chat[1] ?>>Sim</option>
                                                                            <option value="2" <?= $sel_atendimento_chat[2] ?>>Não</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <div class="form-group">
                                                                        <label>Quantidade de atendimentos simultâneos:</label>   
                                                                        <input class="form-control input-sm number_int" name="qtd_atendimentos_simultaneos" id="qtd_atendimentos_simultaneos" value="<?= $qtd_atendimentos_simultaneos ?>" <?= $at_chat?>>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <div class="form-group">
                                                                        <?php
                                                                                $sel_bina[$bina] = 'selected';
                                                                            ?>
                                                                        <label>Possui bina: </label>
                                                                        <select class="form-control input-sm" name="possui_bina" id="possui_bina">
                                                                            <option value="" <?= $sel_bina[''] ?>>---</option>
                                                                            <option value="1" <?= $sel_bina['1'] ?>>Sim</option>
                                                                            <option value="2" <?= $sel_bina['2'] ?>>Não</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <div class="form-group">
                                                                        <label>Observações bina: </label>
                                                                        <textarea class="form-control" name="obs_bina" id="obs_bina" rows="2"><?= $obs_bina ?></textarea>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-3">
                                                                    <div class="form-group">
                                                                        <label>Horário que tem mais ligações: </label> <textarea class="form-control" name="horario_mais_ligacoes" id="horario_mais_ligacoes" rows="2"><?= $horario_mais_ligacoes ?></textarea>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- end Perguntas call center -->

                                                <div class="row" style="margin-bottom: 15px;">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label>Observações da Prospecção:</label>
                                                            <textarea class="form-control" name="obs_lead" style="resize: vertical; height: 100px;"><?=$obs_lead?></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- end tab 2 Dados prospecção -->

                            <!-- tab 3 Vínculos  -->
                            <div id="tab3" class="tab-pane fade in">
                                <?php if ($operacao == 'alterar') {?>
                                    <br>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="panel-group" id="accordionVinculos" role='tablist'>
                                                <div class="panel panel-default">
                                                    <div class="panel-heading clearfix">
                                                        <h3 class="panel-title text-left pull-left">Pessoas vinculadas</h3>
                                                        <div class="panel-title text-right pull-right">
                                                            <a aria-expanded="false" href="/api/iframe?token=<?php echo $request->token ?>&view=vinculo-pessoa-form&vincular=<?=$id?>" onclick="if (!confirm('Se houve alterações não salvas no formulário de pessoa, serão perdidas ao recarregar a página.')) { return false; } else { modalAguarde(); }">
                                                                <button class="btn btn-xs btn-default" type="button"><i class="fa fa-plus"></i> Novo</button>
                                                            </a>
                                                        </div>
                                                    </div>
                                                    <div class="panel-body">
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="form-group has-feedback">
                                                                    <label class="control-label sr-only">Hidden label</label>
                                                                    <input class="form-control" type="text" name="nome_busca" id="nome_busca" onKeyUp="call_busca_ajax();" placeholder="Informe o nome da pessoa..." autocomplete="off" autofocus>
                                                                    <span class="glyphicon glyphicon-search form-control-feedback"></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <hr>
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div id="resultado_busca"></div>
                                                            </div>
                                                        </div>
                                                        
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php }?>
                            </div>
                            <!-- end tab 3 Vínculos -->

                        </div>
                    </div>
                    <div class="panel-footer">
                        <div class="row">
                            <div class="col-md-12" style="text-align: center">
                                <input type="hidden" id="operacao" value="<?=$id;?>" name="<?=$operacao;?>"/>
                                
                                <button type='button' class='btn btn-primary' data-toggle='modal' data-target='#modalSalvar'><i class='fa fa-floppy-o'></i> Salvar</button>

                                <div class="modal fade bs-example-modal-sm" id="modalSalvar" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
                                        <div class="modal-dialog  modal-sm" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                    <h4 class="modal-title" id="myModalLabel">Salvar</h4>
                                                    
                                                </div>
                                                <div class="modal-body">
                                                    Você deseja permanecer no cadastro desta pessoa?
                                                </div>
                                                <div class="modal-footer">
                                                    <div id="panel_buttons" class="col-md-12" style="text-align: center">
                                                        <button class='btn btn-primary ok' value='-1' name='modal_salvar' type='submit'>Não</button>
                                                        <button class='btn btn-primary ok' value='1' name='modal_salvar' id='ok' type='submit'>Sim</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                <!-- <button class="btn btn-primary" name="salvar" id="ok" type="submit"><i class="fa fa-floppy-o"></i> Salvar</button> -->
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    .ui-autocomplete{
        z-index:10000 !important;
    }
    /* .modal-dialog {
        top: 35%;
    } */
</style>

<script src="inc/js/cpf_cnpj.js"></script>
<script>

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
                            'atributo' : '',
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
                    $('#id_pessoa_indicacao').val(data[0].id_pessoa);
                }
            });
        }
    }
    // Função para limpar os campos caso a busca esteja vazia
    function limpaCamposPessoa() {
        var busca = $('#busca_pessoa').val();

        if (busca == "") {
            $('#id_pessoa_indicacao').val('');
        }
    }
    
    $(document).on('click', '#habilita_busca_pessoa', function () {
        $('#id_pessoa_indicacao').val('');
        $('#busca_pessoa').val('');
        $('#busca_pessoa').attr("readonly", false);
        $('#busca_pessoa').focus();
    });

    function call_busca_ajax(pagina){
        var inicia_busca = 1;
        var nome = $('#nome_busca').val();
        var id_pai = $('#operacao').val();
        if (nome.length < inicia_busca && nome.length >=1){
            return false;
        }
        if(pagina === undefined){
            pagina = 1;
        }
        var parametros = {
            'nome': nome,
            'id_pai': id_pai,
            'pagina': pagina
        };
        busca_ajax('<?= $request->token ?>' , 'VinculoPessoaBusca', 'resultado_busca', parametros);
    }

    $(document).on('click', '.troca_pag', function(){
        call_busca_ajax($(this).attr('atr-pagina'));
    });

    $(document).ready(function(){
        
        $('.js-example-basic-multiple').select2();
        
        if($("#accordionVinculos").length){
            call_busca_ajax();
        }    

        /* seleciona opcoes meios de acesso a internet */
            select2AcessoInternet = $('#acesso_internet').select2();
            //verifica quais estão marcados
            dadosCampoJson = <?php echo json_encode($dados_acesso_internet) ?>;

            if(dadosCampoJson != null && dadosCampoJson != false){
                dadosCampoAcessoArray = [];
                dadosCampoJson.forEach(function(i){
                    console.log(i.id_tipo_equipamento);
                    dadosCampoAcessoArray.push(i.id_tipo_equipamento);
                });
                select2AcessoInternet.val(dadosCampoAcessoArray);
                select2AcessoInternet.trigger('change');
            }
        /* end seleciona opcoes meios de acesso a internet */

        /* seleciona opcoes equal equipamento trabalha */
            select2Equip_redes = $('#equipamento_redes').select2();
            //verifica quais estão marcados
            dadosCampoJson2 = <?php echo json_encode($dados_equipamentos_redes) ?>;

            if(dadosCampoJson2 != null && dadosCampoJson2 != false){
                dadosCampoAcessoArray2 = [];
                dadosCampoJson2.forEach(function(i){
                    console.log(i.id_tipo_equipamento_redes);
                    dadosCampoAcessoArray2.push(i.id_tipo_equipamento_redes);
                });
                select2Equip_redes.val(dadosCampoAcessoArray2);
                select2Equip_redes.trigger('change');
            }
        /* end seleciona opcoes equal equipamento trabalha */
    
        /* var prospeccao = '<?php echo $prospeccao ?>';

        if(prospeccao != ''){
            $('#row-lead').css('display', 'block');
        } */
    });
    
    function troca_cpf_cnpj(tipo){
        if(tipo == 'pf'){
            $('#label_cpf_cnpj').text('CPF:');
            $('.cpf_cnpj').mask('000.000.000-00', {reverse: true, placeholder: '000.000.000-00'});
        }else{
            $('#label_cpf_cnpj').text('CNPJ:');
            $('.cpf_cnpj').mask('00.000.000/0000-00', {reverse: true, placeholder: '00.000.000/0000-00'});
        }
    }

    function atendimentoChat(){
        var atendimento_chat = $('#atendimento_chat').val();

        if (atendimento_chat == 1) {
            $('#qtd_atendimentos_simultaneos').prop( "disabled", false);
        } else {
            $('#qtd_atendimentos_simultaneos').prop( "disabled", true);
            $('#qtd_atendimentos_simultaneos').val(0);
        }
    }

    troca_cpf_cnpj($("select[name=tipo]").val());

    $(document).on('change', 'select[name=tipo]', function(){
        troca_cpf_cnpj($(this).val());
    });

    function selectUfEstado(id_estado, id_cidade){        
        $("select[name=cidade]").html('<option value="">Carregando...</option>');
        $.post("/api/ajax?class=SelectUfCidade.php",
            {estado: id_estado,
            token: '<?= $request->token ?>'},
            function(valor){
                $("select[name=cidade]").html(valor);
                if(id_cidade != undefined){
                    $('#cidade').val(id_cidade);
                }
            }
        )        
    }

    $(document).on('change', 'select[name=estado]', function(){
        selectUfEstado($(this).val());
    });

    $(document).on('submit', '#pessoa_form', function(){
        var tipo = $("select[name=tipo]").val();
        var cpf_cnpj = $("#input_cpf_cnpj").val();
        var nome = $("#nome").val();
        var origem = $("#origem").val();
        var segmento = $("#segmento").val();
        var quantidade_clientes = $("#quantidade_clientes").val();
        var estrutura_tres_niveis = $("#estrutura_tres_niveis").val();
        var quantidade_funcionarios_nivel_1 = $("#quantidade_funcionarios_nivel_1").val();
        var quantidade_funcionarios_nivel_2 = $("#quantidade_funcionarios_nivel_2").val();
        var quantidade_funcionarios_nivel_3 = $("#quantidade_funcionarios_nivel_3").val();
        var atendimento_fideliza_cliente = $("#atendimento_fideliza_cliente").val();
        var central_telefonica = $("#central_telefonica").val();
        var sistema_de_gestao = $("#sistema_de_gestao").val();
        var acesso_internet = $("#acesso_internet").val();
        var horario_mais_ligacoes = $("#horario_mais_ligacoes").val();
        var qualificacao_cliente = $("#qualificacao_cliente").val();
        var concorrencia = $("#concorrencia").val();
        var reclamacoes_redes_sociais = $("#reclamacoes_redes_sociais").val();
        var terceirizacao_atendimento = $("#terceirizacao_atendimento").val();
        var equipamento_redes = $("#equipamento_redes").val();
        var contato = $("#contato").val();
        var exp_assessoria_redes = $("#exp_assessoria_redes").val();
        var id_pessoa_indicacao = $("#id_pessoa_indicacao").val();
        var bina = $("#possui_bina").val();
        var data_pessoa_indicacao = $("input[name=data_pessoa_indicacao]").val();

        if (id_pessoa_indicacao != '' && data_pessoa_indicacao == '') {
            alert('Informe a data da indicação!');
            $('a[href="#tab2"]').click();
            return false;
        }

        if(origem == 0 && (segmento != 0 || quantidade_clientes != '' || estrutura_tres_niveis != 0 || quantidade_funcionarios_nivel_1 != '' || quantidade_funcionarios_nivel_2 != '' || quantidade_funcionarios_nivel_3 != '' || central_telefonica != 0 || sistema_de_gestao != 0 || acesso_internet != null || atendimento_fideliza_cliente != '' || terceirizacao_atendimento != 0 || qualificacao_cliente != '' || concorrencia != 0 || reclamacoes_redes_sociais != '' || horario_mais_ligacoes != '' || equipamento_redes != null || contato != '' || exp_assessoria_redes !='' || bina !='')){

            alert('O campo origem deve ser preenchido!');
            $('a[href="#tab2"]').click();
            return false;
        }

        if(nome == ''){
            alert('O nome da pessoa deve ser preenchido!');
            $('a[href="#tab1"]').click();
            $("#nome").focus();
            return false;
        }

        if(cpf_cnpj.length > 0){
            if(tipo == 'pf'){
                if (!valida_cpf(cpf_cnpj)){
                    alert('CPF inválido!');
                    $('#input_cpf_cnpj').focus();
                    return false;
                }
            }else{
                if(!valida_cnpj(cpf_cnpj)){
                    alert('CNPJ inválido!');
                    $('#input_cpf_cnpj').focus();
                    return false;
                }
            }
        }

        modalAguarde();
    });

    $('#equipamento_redes').on('change', function(){
        var equipamentos = $(this).val();
        var cont = 0;

        if(equipamentos != null){

            for(i=0; i<equipamentos.length; i++){
                if(equipamentos[i] == 5){
                    cont = 1;
                    $('#outro_equipamento').prop( "disabled", false);
                }
            }

            if(cont == 0){
                $('#outro_equipamento').prop( "disabled", true);
            }

        }
        if(equipamentos == null){
            $('#outro_equipamento').prop( "disabled", true);
        }   
    });

    $('#exp_assessoria_redes').on('change', function(){
        var exp_assessoria_redes = $(this).val();

        if(exp_assessoria_redes == 1){
            $('#qual_assessoria').prop( "disabled", false);
            $('#pq_nao_tem_mais').prop( "disabled", false);
        }else{
            $('#qual_assessoria').prop( "disabled", true);
            $('#pq_nao_tem_mais').prop( "disabled", true);
        }
    });

    $('#accordionRedes').on('shown.bs.collapse', function () {
       $("#i_collapseRedes").removeClass("fa fa-plus").addClass("fa fa-minus");
    });
    $('#accordionRedes').on('hidden.bs.collapse', function () {
       $("#i_collapseRedes").removeClass("fa fa-minus").addClass("fa fa-plus");
    });

    $('#accordionPesquisa').on('shown.bs.collapse', function () {
       $("#i_collapsePesquisa").removeClass("fa fa-plus").addClass("fa fa-minus");
    });
    $('#accordionPesquisa').on('hidden.bs.collapse', function () {
       $("#i_collapsePesquisa").removeClass("fa fa-minus").addClass("fa fa-plus");
    });

    $('#accordionSuporte').on('shown.bs.collapse', function () {
       $("#i_collapseSuporte").removeClass("fa fa-plus").addClass("fa fa-minus");
    });
    $('#accordionSuporte').on('hidden.bs.collapse', function () {
       $("#i_collapseSuporte").removeClass("fa fa-minus").addClass("fa fa-plus");
    });
</script>