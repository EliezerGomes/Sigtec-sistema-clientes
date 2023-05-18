<?php
    include('app/protect.php');
    include('app/conexao.php');

    $emp = $_SESSION['id'];
    $h = date("Y-m-d");
    $empresas = "SELECT * FROM empresa WHERE id_revenda = '$emp' AND validade_licenca < '$h' ORDER BY codigo DESC";
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
</head>
<body>
    <header>
        <div>
            <img src="./images/logo_horizontal.png" alt="">
            <h3>Bem vindo, <?php echo $_SESSION['nome']; ?></h3>
        </div>

        <div>
            <a href="logout.php">
                <img src="./images/box-arrow-right.svg" alt="">
                Sair
            </a>
        </div>
    </header>

    <main class="main">
        <section>
            <div class="menu">
                <a href="painel.php">Empresas ativas</a>
                <!-- <a href="empresas_inativas.php">Empresas inativas</a> -->
                <a href="empresas_bloqueadas.php">Empresas bloqueadas</a>
                <a href="empresas_isentas.php">Empresas isentas</a>
                <a class="ativo" href="empresas_vencidas.php">Empresas vencidas</a>
            </div>

            <div class="busca">
                <input type="search" placeholder="Buscar" id="busca">
                <!-- <button onclick="searchData()">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                        <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/>
                    </svg>
                </button> -->
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
                </tr>
            </thead>
            
            <tbody class="tabela" id="result">
                <?php
                    $num = $result->num_rows;
                    if($num > 0) {
                        $hoje = date("Y-m-d");
                        while($user_data = mysqli_fetch_assoc($result)){
                            $vencimento = $user_data['validade_licenca'];
                            if (strtotime($hoje) > strtotime($vencimento)) {
                                echo "<tr>";
                                echo "<td>".$user_data['cnpj']."</td>";
                                echo "<td>".$user_data['razao']."</td>";
                                echo "<td>".$user_data['endereco']."</td>";
                                echo "<td>".$user_data['cidade']."</td>";
                                echo "<td>".$user_data['bairro']."</td>";
                                echo "<td>".$user_data['cep']."</td>";
                                echo "<td>".$user_data['uf']."</td>";
                                echo "<td>".$user_data['fone']."</td>";
                                echo "</tr>"; 
                            // } else {
                            //     echo "<tr>";
                            //     echo "<td colspan=8 style='text-align: center;'>Nenhum registro encontrado!</td>";
                            //     echo "</tr>";
                            //     $executou = false;
                            // }
                        } }
                    } else {
                        echo "<tr>";
                        echo "<td colspan=8 style='text-align: center;'>Nenhum registro encontrado!</td>";
                        echo "</tr>";
                    }
                ?>
            </tbody>
            
        </table>
    </main>

    <!-- <footer>
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
    </footer> -->
</body>

<script>
    $("#busca").keyup(function() {
        var busca = $("#busca").val()
        $.post('./busca/busca_vencidas.php', {busca: busca}, function(data) {
            $("#result").html(data)
        })

    })
</script>

</html>