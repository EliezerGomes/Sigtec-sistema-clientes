<?php
include('../app/conexao.php');

if(!isset($_SESSION)) {
    session_start();
}

$emp = $_SESSION['id'];

$data = $_POST['busca'];

if(!$data == ''){
    $empresa = $empresas = "SELECT * FROM empresa WHERE id_revenda = '$emp' AND isento = '1' AND razao LIKE '%$data%' OR cnpj LIKE '%$data%' ORDER BY codigo DESC";
} else {
    $empresa = $empresas = "SELECT * FROM empresa WHERE id_revenda = '$emp' AND isento = '1' ORDER BY codigo DESC";
}

$result = $mysqli->query($empresas);

$num = $result->num_rows;
if($num > 0) {
    $executou = true;
    while($user_data = mysqli_fetch_assoc($result) AND $executou){
        if($user_data['isento'] == 1){
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
         } // else {
//             echo "<tr>";
//             echo "<td colspan=8 style='text-align: center;'>Nenhum registro encontrado!</td>";
//             echo "</tr>";
//             $executou = false;
// }
} 
} else {
    echo "<tr>";
    echo "<td colspan=8 style='text-align: center;'>Nenhum registro encontrado!</td>";
    echo "</tr>";
}
?>