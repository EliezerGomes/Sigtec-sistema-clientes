<?php
include_once("./app/conexao.php");

$cod = $_GET['cod'];
$hoje = date("Y-m-d");
if(isset($_POST['dia'])){
    $valor = $_POST['dia'];
    $suspender = date("Y-m-d", strtotime("+".$valor." days", strtotime($hoje)));
    $sqlSuspender = "UPDATE empresa SET suspensao = '$suspender' WHERE codigo = '$cod'";
    $result = $mysqli->query($sqlSuspender);

    if(!isset($_SESSION)) {
        session_start();
    }

    $_SESSION['suspenso'] = "true";

    header("Location: painel.php?cod=".$cod."");
}
?>