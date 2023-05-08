<?php
include('app/conexao.php');

require __DIR__ . '/vendor/autoload.php';
use Dompdf\Dompdf;

//Instancia do dompdf, para gerar pdfs
$dompdf = new Dompdf(['enable_remote' => true]);
$data = date("d/m/Y");

//buscar no banco de dados
if(!isset($_SESSION)) {
    session_start();
}

$emp = $_SESSION['id'];
$empresas = "SELECT * FROM empresa WHERE id_revenda = '$emp' ORDER BY codigo DESC";
$result = $mysqli->query($empresas);

$men = "SELECT r.NOME, count(e.razao) as RAZAO, sum(r.MENSALIDADE) AS MENSALIDADE, sum(r.PRECO_SUGERIDO) AS BRUTO_APROX, (SUM(r.PRECO_SUGERIDO) - sum(r.MENSALIDADE)) AS LIQUIDO_APROX  FROM empresa e
    INNER JOIN revenda r
    ON e.id_revenda = r.ID
    WHERE e.bloqueado = 'N' and e.isento = 'N'
    group by r.nome";
    $r = $mysqli->query($men);

//HTML
$dados = "<!DOCTYPE html>";
$dados .= "<html lang='pt-br'>";
$dados .= "<head>";
$dados .= "<meta charset='UTF-8'>";
$dados .= "<title>Relatório</title>";
$dados .= "<link rel='stylesheet' href='http://localhost/sigtec/css/pdf.css'>";
$dados .= "</head>";
$dados .= "<body>";
// $dados .= "<header>";
$dados .= "<img src='http://localhost/sigtec/images/logo_horizontal.png' alt='logo'>";
$dados .= "<h4>data: ".$data."</h4>";
// $dados .= "</header>";
$dados .= "<h2>Relatório</h2>";
$dados .= "<div class='resumo'>";
$dados .=   "<table>";
$dados .=       "<thead>";
$dados .=           "<tr>";
$dados .=               "<td>N° de Razão</td>";
$dados .=               "<td>Total da mensalidade</td>";
$dados .=               "<td>Total do valor bruto</td>";
$dados .=               "<td>Total do valor liquido</td>";
$dados .=           "</tr>";
$dados .=       "</thead>";
$dados .=    "<tbody>";
while($data = mysqli_fetch_assoc($r)){
    if($data['NOME'] == $_SESSION['nome']){
        $dados .= "<tr>";
        $dados .= "<td>".$data['RAZAO']."</td>";
        $dados .= "<td>".$data['MENSALIDADE']." R$"."</td>";
        $dados .= "<td>".$data['BRUTO_APROX']." R$"."</td>";
        $dados .= "<td>".$data['LIQUIDO_APROX']." R$"."</td>";
        $dados .= "</tr>";
        };
    } 
$dados .=    "</tbody>";
$dados .= "</table>";  
$dados .= "</div>";

$var = mysqli_fetch_assoc($result);
//empresas ativas

$dados .= "<div class='main'>";
$dados .= "<h3>Empresas ativas</h3>";
if($var['bloqueado'] == "N"){
$dados .=   "<table";  
    $dados .= "<thead>";
        $dados .= "<th>CNPJ</th>";
        $dados .= "<th>Razão</th>";
        $dados .= "<th>Enderço</th>";
        $dados .= "<th>Cidade</th>";
        $dados .= "<th>Bairro</th>";
        $dados .= "<th>Cep</th>";
        $dados .= "<th>UF</th>";
        $dados .= "<th>Fone</th>";
    $dados .= "</thead>";
   
    while($user_data = mysqli_fetch_assoc($result)){
        $cod = $user_data['codigo'];
        $dados .= "<tbody>";
        $dados .=   "<tr>";
        $dados .=       "<td>".$user_data['cnpj']."</td>";
        $dados .=       "<td>".$user_data['razao']."</td>";
        $dados .=       "<td>".$user_data['endereco']."</td>";
        $dados .=       "<td>".$user_data['cidade']."</td>";
        $dados .=       "<td>".$user_data['bairro']."</td>";
        $dados .=       "<td>".$user_data['cep']."</td>";
        $dados .=       "<td>".$user_data['uf']."</td>";
        $dados .=       "<td>".$user_data['fone']."</td>";
        $dados .=   "</tr>"; 
        $dados .= "</tbody>"; 
    }
} else {
$dados .=   "<table>";
$dados .=       "<thead>";
$dados .=           "<th>CNPJ</th>";
$dados .=           "<th>Razão</th>";
$dados .=           "<th>Enderço</th>";
$dados .=           "<th>Cidade</th>";
$dados .=           "<th>Bairro</th>";
$dados .=           "<th>Cep</th>";
$dados .=           "<th>UF</th>";
$dados .=           "<th>Fone</th>";
$dados .=        "</thead>"; 

$dados .= "<tbody>";
$dados .=   "<tr>";
$dados .=       "<td>0</td>";
$dados .=       "<td>0</td>";
$dados .=       "<td>0</td>";
$dados .=       "<td>0</td>";
$dados .=       "<td>0</td>";
$dados .=       "<td>0</td>";
$dados .=       "<td>0</td>";
$dados .=       "<td>0</td>";
$dados .=   "</tr>"; 
$dados .= "</tbody>";
}
$dados .=  "</table>";
$dados .= "</div>";

//empresas bloqueadas 
$dados .= "<div class='main'>";
$dados .= "<h3>Empresas bloquedas</h3>";
if($var['bloqueado'] == "S"){
$dados .=   "<table";  
    $dados .= "<thead>";
        $dados .= "<th>CNPJ</th>";
        $dados .= "<th>Razão</th>";
        $dados .= "<th>Enderço</th>";
        $dados .= "<th>Cidade</th>";
        $dados .= "<th>Bairro</th>";
        $dados .= "<th>Cep</th>";
        $dados .= "<th>UF</th>";
        $dados .= "<th>Fone</th>";
    $dados .= "</thead>";
       
    while($user_data = mysqli_fetch_assoc($result)){
        $cod = $user_data['codigo'];
        $dados .= "<tr>";
        $dados .= "<td>".$user_data['cnpj']."</td>";
        $dados .= "<td>".$user_data['razao']."</td>";
        $dados .= "<td>".$user_data['endereco']."</td>";
        $dados .= "<td>".$user_data['cidade']."</td>";
        $dados .= "<td>".$user_data['bairro']."</td>";
        $dados .= "<td>".$user_data['cep']."</td>";
        $dados .= "<td>".$user_data['uf']."</td>";
        $dados .= "<td>".$user_data['fone']."</td>";
        $dados .= "</tr>";  
    }
} else {
    $dados .=   "<table>";
    $dados .=       "<thead>";
    $dados .=           "<th>CNPJ</th>";
    $dados .=           "<th>Razão</th>";
    $dados .=           "<th>Enderço</th>";
    $dados .=           "<th>Cidade</th>";
    $dados .=           "<th>Bairro</th>";
    $dados .=           "<th>Cep</th>";
    $dados .=           "<th>UF</th>";
    $dados .=           "<th>Fone</th>";
    $dados .=        "</thead>"; 
    
    $dados .= "<tbody>";
    $dados .=   "<tr>";
    $dados .=       "<td>0</td>";
    $dados .=       "<td>0</td>";
    $dados .=       "<td>0</td>";
    $dados .=       "<td>0</td>";
    $dados .=       "<td>0</td>";
    $dados .=       "<td>0</td>";
    $dados .=       "<td>0</td>";
    $dados .=       "<td>0</td>";
    $dados .=   "</tr>"; 
    $dados .= "</tbody>";
    }
$dados .=  "</table>";
$dados .= "</div>";

//empresas isentas 
$dados .= "<div class='main'>";
$dados .= "<h3>Empresas isentas</h3>";
if($var['isento'] == 1){
$dados .=   "<table";  
    $dados .= "<thead>";
        $dados .= "<th>CNPJ</th>";
        $dados .= "<th>Razão</th>";
        $dados .= "<th>Enderço</th>";
        $dados .= "<th>Cidade</th>";
        $dados .= "<th>Bairro</th>";
        $dados .= "<th>Cep</th>";
        $dados .= "<th>UF</th>";
        $dados .= "<th>Fone</th>";
    $dados .= "</thead>";
       
    while($user_data = mysqli_fetch_assoc($result)){
        $cod = $user_data['codigo'];
        $dados .= "<tr>";
        $dados .= "<td>".$user_data['cnpj']."</td>";
        $dados .= "<td>".$user_data['razao']."</td>";
        $dados .= "<td>".$user_data['endereco']."</td>";
        $dados .= "<td>".$user_data['cidade']."</td>";
        $dados .= "<td>".$user_data['bairro']."</td>";
        $dados .= "<td>".$user_data['cep']."</td>";
        $dados .= "<td>".$user_data['uf']."</td>";
        $dados .= "<td>".$user_data['fone']."</td>";
        $dados .= "</tr>";  
    }
} else {
    $dados .=   "<table>";
    $dados .=       "<thead>";
    $dados .=           "<th>CNPJ</th>";
    $dados .=           "<th>Razão</th>";
    $dados .=           "<th>Enderço</th>";
    $dados .=           "<th>Cidade</th>";
    $dados .=           "<th>Bairro</th>";
    $dados .=           "<th>Cep</th>";
    $dados .=           "<th>UF</th>";
    $dados .=           "<th>Fone</th>";
    $dados .=        "</thead>"; 
    
    $dados .= "<tbody>";
    $dados .=   "<tr>";
    $dados .=       "<td>0</td>";
    $dados .=       "<td>0</td>";
    $dados .=       "<td>0</td>";
    $dados .=       "<td>0</td>";
    $dados .=       "<td>0</td>";
    $dados .=       "<td>0</td>";
    $dados .=       "<td>0</td>";
    $dados .=       "<td>0</td>";
    $dados .=   "</tr>"; 
    $dados .= "</tbody>";
    }
$dados .=  "</table>";
$dados .= "</div>";

//empresas vencidas
$vencimento = $var['validade_licenca'];
$hoje = date('Y-m-d');
$dados .= "<div class='main'>";
$dados .= "<h3>Empresas isentas</h3>";
if(strtotime($hoje) > strtotime($vencimento)){
$dados .=   "<table";  
    $dados .= "<thead>";
        $dados .= "<th>CNPJ</th>";
        $dados .= "<th>Razão</th>";
        $dados .= "<th>Enderço</th>";
        $dados .= "<th>Cidade</th>";
        $dados .= "<th>Bairro</th>";
        $dados .= "<th>Cep</th>";
        $dados .= "<th>UF</th>";
        $dados .= "<th>Fone</th>";
    $dados .= "</thead>";
       
    while($user_data = mysqli_fetch_assoc($result)){
        $cod = $user_data['codigo'];
        $dados .= "<tr>";
        $dados .= "<td>".$user_data['cnpj']."</td>";
        $dados .= "<td>".$user_data['razao']."</td>";
        $dados .= "<td>".$user_data['endereco']."</td>";
        $dados .= "<td>".$user_data['cidade']."</td>";
        $dados .= "<td>".$user_data['bairro']."</td>";
        $dados .= "<td>".$user_data['cep']."</td>";
        $dados .= "<td>".$user_data['uf']."</td>";
        $dados .= "<td>".$user_data['fone']."</td>";
        $dados .= "</tr>";  
    }
} else {
    $dados .=   "<table>";
    $dados .=       "<thead>";
    $dados .=           "<th>CNPJ</th>";
    $dados .=           "<th>Razão</th>";
    $dados .=           "<th>Enderço</th>";
    $dados .=           "<th>Cidade</th>";
    $dados .=           "<th>Bairro</th>";
    $dados .=           "<th>Cep</th>";
    $dados .=           "<th>UF</th>";
    $dados .=           "<th>Fone</th>";
    $dados .=        "</thead>"; 
    
    $dados .= "<tbody>";
    $dados .=   "<tr>";
    $dados .=       "<td>0</td>";
    $dados .=       "<td>0</td>";
    $dados .=       "<td>0</td>";
    $dados .=       "<td>0</td>";
    $dados .=       "<td>0</td>";
    $dados .=       "<td>0</td>";
    $dados .=       "<td>0</td>";
    $dados .=       "<td>0</td>";
    $dados .=   "</tr>"; 
    $dados .= "</tbody>";
    }
$dados .=  "</table>";
$dados .= "</div>";
$dados .= "</body>";

$dompdf->loadHtml($dados);
$dompdf->render();
header('Content-type: application/pdf');
echo $dompdf->output();