<?php

    $usuario = "sigdig33_clientes";
    $senha = "jesuscristoeosenhor";
    $database = "sigdig33_ativacao_clientes";
    $host = "162.241.62.247";

    $mysqli = new mysqli($host, $usuario, $senha, $database);

    if($mysqli->error){
        die("Falha ao conectar ao banco de dados");
    }
?>