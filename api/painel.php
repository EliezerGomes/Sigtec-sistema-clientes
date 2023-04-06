<?php
    include('app/protect.php');
    include('app/conexao.php');

    $emp = $_SESSION['id'];
    $empresas = "SELECT * FROM empresa WHERE id_revenda = '$emp' ORDER BY codigo DESC";
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
        <h1>Empresas</h1>

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
            
            <tbody>
                <?php
                    while($user_data = mysqli_fetch_assoc($result)){
                        echo "<tr class=".$user_data['bloqueado'].">";
                        echo "<td>".$user_data['cnpj']."</td>";
                        echo "<td>".$user_data['razao']."</td>";
                        echo "<td>".$user_data['endereco']."</td>";
                        echo "<td>".$user_data['cidade']."</td>";
                        echo "<td>".$user_data['bairro']."</td>";
                        echo "<td>".$user_data['cep']."</td>";
                        echo "<td>".$user_data['uf']."</td>";
                        echo "<td>".$user_data['fone']."</td>";
                        echo "</tr>";
                    }
                ?>
            </tbody>
            
        </table>
    </main>

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
</html>