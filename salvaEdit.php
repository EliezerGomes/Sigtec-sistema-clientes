<?php
    include_once("./app/conexao.php");

    $cod = $_GET['cod'];

    if(isset($_POST['update'])){
        $validade = $_POST['date'];
        $hoje = date('Y-m-d');
        if($validade > $hoje) {
            $sqlUpdate = "UPDATE empresa SET validade_licenca = '$validade' WHERE codigo = '$cod'";
            $result = $mysqli->query($sqlUpdate);
            //$_SESSION['sucesso'] = true;
        }
    }
?>