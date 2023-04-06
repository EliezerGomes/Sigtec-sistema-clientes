<?php

$url = "https://sigdig-ativacao-clientes.vercel.app/revendas";
    
$curl = curl_init($url);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
$revendas = json_decode(curl_exec($curl));
echo "<pre>";
print_r($revendas);
exit;
    
?>