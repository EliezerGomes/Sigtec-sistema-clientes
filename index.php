<?php
    include('app/conexao.php');

    if(isset($_POST["cnpj"]) || isset($_POST["senha"])) {

        if(strlen($_POST["cnpj"]) == 0) {
            echo "Preencha o campo com seu cnpj";
        } else if (strlen($_POST["senha"]) == 0) {
            echo "Preencha o campo com sua senha";
        } else {

           $cnpj = $mysqli->real_escape_string($_POST['cnpj']);
           $senha = $mysqli->real_escape_string($_POST['senha']);

           $sql_code = "SELECT * FROM revenda WHERE cnpj = '$cnpj' AND senha = '$senha'";
           $sql_query = $mysqli->query($sql_code) or die("Falha na execução do código SQL: " . $mysqli->error);

           $quantidade = $sql_query->num_rows;

            if($quantidade == 1) {
            
                $usuario = $sql_query->fetch_assoc();

                if(!isset($_SESSION)) {
                    session_start();
                }

                $_SESSION['id'] = $usuario['ID'];
                $_SESSION['nome'] = $usuario['NOME'];

                header("Location: painel.php");
            
           } else {
            echo "<div class='message'>
                Falha ao logar! Email ou senha incorretos
            </div>";
            //echo "Falha ao logar! Email ou senha incorretos";
           }
        }
    }
?>

<!DOCTYPE html>
<html lang="pt-Br">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="./css/index.css">
    
    <title>Login</title>
</head>
<body>
    <div class="main">   
        <div class="container">
            <div class="left-container">
                <img class="left-image" src="./images/LOGO_QUADRADA.png" alt="">
            </div>

            <div class="right-container">
                <form class="card-login" method="POST">
                    <h1>LOGIN</h1>

                    <div class="textfield">
                        <label for="cnpj">CNPJ</label>
                        <input type="text" name="cnpj" placeholder="CNPJ" id="cnpj">
                    </div>

                    <div class="textfield">
                        <label for="senha">Senha</label>
                        <input type="password" name="senha" placeholder="Senha" id="senha">
                    </div>

                    <button class="btn-login">Login</button>
        
                </form>
            </div>
        </div>
    </div>
    
</body>
</html>