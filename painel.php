<?php
    include('app/protect.php');
    include('app/conexao.php');

    if(!isset($_SESSION)) {
        session_start();
    }
    //Busca no banco de dados para exibir na tabela
    if(isset($_SESSION['sucesso'])){
        $sucesso = $_SESSION['sucesso'];
    }

    if(isset($_SESSION['suspenso'])){
        $suspenso = $_SESSION['suspenso'];
    }

    $emp = $_SESSION['id'];
    $empresas = "SELECT * FROM empresa WHERE id_revenda = '$emp' AND bloqueado = 'N' AND isento = '0' ORDER BY codigo DESC";
    $result = $mysqli->query($empresas);


    $men = "SELECT r.NOME, count(e.razao) as RAZAO, sum(r.MENSALIDADE) AS MENSALIDADE, sum(r.PRECO_SUGERIDO) AS BRUTO_APROX, (SUM(r.PRECO_SUGERIDO) - sum(r.MENSALIDADE)) AS LIQUIDO_APROX  FROM empresa e
    INNER JOIN revenda r
    ON e.id_revenda = r.ID
    WHERE e.bloqueado = 'N' and e.isento = 'N'
    group by r.nome";
    $r = $mysqli->query($men);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="./css/painel.css">
    <title>Painel</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.5.3/jspdf.debug.js" integrity="sha384-NaWTHo/8YCBYJ59830LTz/P4aQZK1sS0SneOgAvhsIl3zBu8r9RevNg5lHCHAuQ/" crossorigin="anonymous"></script>

</head>
<body>
    <header>
        <div>
            
            <img src="./images/logo_horizontal.png" alt="">
            <h3>Bem vindo, <?php echo $_SESSION['nome']; ?></h3>
        </div>

        <div>
            <a href="logout.php">
                <!-- <img src="./images/box-arrow-right.svg" alt=""> -->
                Sair
            </a>
        </div>
    </header>

    <main class="main">
        <?php 
            if(isset($sucesso)){
                if($sucesso == "true"){
                    echo "<h4 id='msg' style='background: #00FF7F; width: 100vw; heiht: 10vh; margin-top: -10px; text-align: center;'>Data de vencimento alterado com sucesso</h4>";
                    $_SESSION['sucesso'] = "";
                } else if($sucesso == "false"){
                    echo "<h4 id='msg' style='background: #FA8072; width: 100vw; heiht: 10vh; margin-top: -10px; text-align: center;'>Erro ao alterar data de vencimento! Data inserida é menor que a data atual</h4>";
                    $_SESSION['sucesso'] = "";
                }
            }

            //

            if(isset($suspenso) == "true"){
                $hoje = date("Y-m-d");
                if(isset($_GET['cod'])){
                    $cod = $_GET['cod'];
                    $pegarData = "SELECT * FROM empresa WHERE id_revenda = '$emp' AND bloqueado = 'N' AND isento = '0' AND codigo = '$cod'";
                    $dates = $mysqli->query($pegarData);
                    $data = mysqli_fetch_assoc($dates);
                    //calcular data
                    $suspensao = $data['suspensao'];
                    $time1 = strtotime($hoje);
                    $time2 = strtotime($suspensao);
                    $diferença_segundos = $time2 - $time1;
                    $dias = $diferença_segundos / (60 * 60 * 24);
                    if($suspenso == "true"){
                        echo "<h4 id='msg' style='background: #F4A460; width: 100vw; heiht: 10vh; margin-top: -10px; text-align: center;'>O sistema será suspenso em ".$dias." dia(s)</h4>";
                        $_SESSION['suspenso'] = "";
                    }
                } 
            } 
        ?>
        <section>
            <div class="menu">
                <a class="ativo" href="painel.php">Empresas ativas</a>
                <!-- <a href="empresas_inativas.php">Empresas inativas</a> -->
                <a href="empresas_bloqueadas.php">Empresas bloqueadas</a>
                <a href="empresas_isentas.php">Empresas isentas</a>
                <a href="empresas_vencidas.php">Empresas vencidas</a>
            </div>

            <div class="busca">
                <input type="search" placeholder="Buscar" id="busca">
                <button onclick="gerarPdf()"> <img class="pdf" src="./images/pdf.png" alt="PDF"></button>
            </div>
            
        </section>

        <table>
            <thead>
                <tr>
                    <th>CNPJ</th>
                    <th>Razão</th>
                    <th>Enderço</th>
                    <th>Cidade</th>
                    <th>Bairro</th>
                    <th>Cep</th>
                    <th>UF</th>
                    <th>Fone</th>
                    <th>Ações</th>
                </tr>
            </thead>
            
            <tbody class="tabela" id="result">
                
                <?php
                    $num = $result->num_rows;
                    if($num > 0) {
                        $executou = true;
                        while($user_data = mysqli_fetch_assoc($result) AND $executou){
                            if($user_data['bloqueado'] == "N" && $user_data['isento'] == '0'){
                                $cod = $user_data['codigo'];
                                echo "<tr>";
                                echo "<td>".$user_data['cnpj']."</td>";
                                echo "<td>".$user_data['razao']."</td>";
                                echo "<td>".$user_data['endereco']."</td>";
                                echo "<td>".$user_data['cidade']."</td>";
                                echo "<td>".$user_data['bairro']."</td>";
                                echo "<td>".$user_data['cep']."</td>";
                                echo "<td>".$user_data['uf']."</td>";
                                echo "<td>".$user_data['fone']."</td>";
                                echo "<td class='edit'><button onclick='editarUsuario($cod)' id=".$cod." ><img src='./images/lapis.svg' alt='lapis'></button></td>";
                                echo "</tr>";
                             }// else {
                            //     echo "entrou no else do segundo if";
                            //     echo "<tr>";
                            //     echo "<td colspan=9 style='text-align: center;'> Nenhum registro encontrado! </td>";
                            //     echo "</tr>";
                            //     $executou = false;
                            // }  
                        } 
                    }  else {
                        echo "entrou no else do segundo if";
                        echo "<tr>";
                        echo "<td colspan=9 style='text-align: center;'> Nenhum registro encontrado! </td>";
                        echo "</tr>";
                    }       
                ?>
            </tbody>
            
        </table>
    </main>

    <div id="modal">
        
    </div>

    <footer>
        <h2>Resumo</h2>
    
        <table>
            <thead>
                <tr>
                    <td>N° de Razão</td>
                    <td>Total da mensalidade</td>
                    <td>Total do valor bruto</td>
                    <td>Total do valor liquido</td>
                </tr>
            </thead>

            <tbody>
                <?php
                    while($data = mysqli_fetch_assoc($r)){
                        if($data['NOME'] == $_SESSION['nome']){
                            echo "<tr>";
                            echo "<td>".$data['RAZAO']."</td>";
                            echo "<td>".$data['MENSALIDADE']." R$"."</td>";
                            echo "<td>".$data['BRUTO_APROX']." R$"."</td>";
                            echo "<td>".$data['LIQUIDO_APROX']." R$"."</td>";
                            echo "</tr>";
                        };
                    }   
                ?>
            </tbody>
        </table>
    </footer>
</body>

<script>
    //Logico para busca automatica
    $("#busca").keyup(function() {
        var busca = $("#busca").val()
        $.post('./busca/busca_painel.php', {busca: busca}, function(data) {
            $("#result").html(data)
        })
    })


    //Logica para editar informações
    async function editarUsuario(cod, e) {
        var modal = document.getElementById("modal")
        var display = modal.style.display;
        if(modal.style.display === "none" || modal.style.display === ""){ 
            modal.style.display = "flex"

            $.post("edit.php", {display: display, cod: cod}, function(data){
            $("#modal").html(data)
        })
        } 
    }

    //Fecha painel de editar
    function fechaModal() {
        var modal = document.getElementById("modal")
        if(modal.style.display === "flex"){
            modal.style.display = "none"
        }
    }

    //Gerar Pdf
    function gerarPdf() {
        window.location.href= "gerarPdf.php"
    }

    //Desaparecer mensagem 
    $(document).ready(function(){
        $("#msg").delay(4000).fadeOut("slow")
    })

</script>
</html>