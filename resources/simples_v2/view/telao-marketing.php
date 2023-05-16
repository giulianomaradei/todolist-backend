<style>
    body{
        display: flex !important;
        padding-top: 0 !important; 
        justify-content: center;
        align-items: center;
        margin: 0;
    }

    .navbar{
        display: none !important;
    }

    .carrousel{
        overflow: hidden;
        width: 100vw;
        height: 100%;
    }  

    .container{
        display: flex;
        transition: transform 1s;
        transition-duration: 2s;
        width: 100vw;
        height: 100vh;
    }
    img{
        object-fit: cover;
        width: 100vw;
        height: calc(100vw / 1.50);  /* deixar 1.77 para a quando as fotos já estiverem em 16x9 */
        transform: translateX(0);
    }
    button {
    position:absolute;
    top: 1px;
    margin: 8vh;
    border-radius: 250px !important;
    opacity: 0;
    transition: 0.3s linear; 
    }

    button:hover {
    opacity: 0.7;
    }
    
    a {
        text-decoration: nome;
        padding: 8vh;
    }
</style>


<div class="carrossel">
    <div class="container" id="img"></div>
</div>
<?php if (verificaSubmenu('telao-marketing-busca', $perfil_usuario)) { ?>
        <button class="btn btn-outline-light"><a href="/api/iframe?token=<?php echo $request->token ?>&view=telao-marketing-busca"><i class="fa fa-arrow-left"></i> Voltar</a></button>
    <?php } else {?>
        <button class="btn btn-outline-light"><a href="/api/iframe?token=<?php echo $request->token ?>&view=home"><i class="fa fa-arrow-left"></i> Voltar</a></button>
    <?php }?>

<script>
    onload = carrossel();
    setInterval(carrossel, 3600000);

    function carrossel(){
        $.ajax({
            type: "GET",
            url:'/api/ajax?class=TelaoMarketing.php',
            data: {token: '<?= $request->token ?>'},
            success: function(dados){
                $("#img").html(dados);
            }
        });
    };
    
    setInterval(slider, 5000);    /* deixar 15000 conforme a Tais pediu */
    let total = document.getElementById("foto");
    let idx = 0;

    function slider(){
        idx++;
        if(idx > foto.length -1){
            idx = 0;
        }
        document.getElementById("img").style.transform = `translatex(${idx * -100}vw)`;
    }
</script>