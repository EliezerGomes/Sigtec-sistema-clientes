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
$ativas = "SELECT * FROM empresa WHERE id_revenda = '$emp' AND bloqueado = 'N' AND isento = '0' ORDER BY codigo DESC";
$bloqueadas = "SELECT * FROM empresa WHERE id_revenda = '$emp' AND bloqueado = 'S' ORDER BY codigo DESC";
$isentas = "SELECT * FROM empresa WHERE id_revenda = '$emp' AND isento = '1' ORDER BY codigo DESC";
$h = date("Y-m-d");
$vencidas = "SELECT * FROM empresa WHERE id_revenda = '$emp' AND validade_licenca < '$h' ORDER BY codigo DESC";

$a = $mysqli->query($ativas);
$b = $mysqli->query($bloqueadas);
$i = $mysqli->query($isentas);
$v = $mysqli->query($vencidas);

$men = "SELECT r.NOME, count(e.razao) as RAZAO, sum(r.MENSALIDADE) AS MENSALIDADE, sum(r.PRECO_SUGERIDO) AS BRUTO_APROX, (SUM(r.PRECO_SUGERIDO) - sum(r.MENSALIDADE)) AS LIQUIDO_APROX  FROM empresa e
    INNER JOIN revenda r
    ON e.id_revenda = r.ID
    WHERE e.bloqueado = 'N' and e.isento = 'N'
    group by r.nome";
$r = $mysqli->query($men);

$busca = "SELECT * from revenda WHERE ID = $emp";
$cnpj = $mysqli->query($busca);

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

$cnpj_revenda = mysqli_fetch_assoc($cnpj);

$dados .= "<div class='info'>";
$dados .=   "<p class='paragrafo-empresa'><strong>Empresa:</strong> ".$_SESSION['nome']."<p/>";
$dados .=   "<p class='paragrafo-cnpj'><strong>Cnpj revenda:</strong> ".$cnpj_revenda['CNPJ']."</p>";
$dados .= "</div>";

//empresas ativas
$dados .= "<div class='main'>";
$dados .= "<h3>Empresas ativas</h3>";
$ativas = 0;
$dados .=   "<table";  
    $dados .= "<thead>";
        $dados .= "<th>CNPJ</th>";
        $dados .= "<th>Razão</th>";
        $dados .= "<th>Endereço</th>";
        $dados .= "<th>Cidade</th>";
        $dados .= "<th>Bairro</th>";
        $dados .= "<th>Cep</th>";
        $dados .= "<th>UF</th>";
        //$dados .= "<th>Fone</th>";
    $dados .= "</thead>";
   $num = $a->num_rows;
   if($num > 0){
    while($user_data = mysqli_fetch_assoc($a)){
        if($user_data['bloqueado'] == "N" && $user_data['isento'] == '0'){
        $cod = $user_data['codigo'];
        $ativas++;
        $dados .= "<tbody>";
        $dados .=   "<tr>";
        $dados .=       "<td>".$user_data['cnpj']."</td>";
        $dados .=       "<td>".$user_data['razao']."</td>";
        $dados .=       "<td>".$user_data['endereco']."</td>";
        $dados .=       "<td>".$user_data['cidade']."</td>";
        $dados .=       "<td>".$user_data['bairro']."</td>";
        $dados .=       "<td>".$user_data['cep']."</td>";
        $dados .=       "<td>".$user_data['uf']."</td>";
        // $dados .=       "<td>".$user_data['fone']."</td>";
        $dados .=   "</tr>"; 
        $dados .= "</tbody>";
        } 
}} else {
$dados .=   "<table>";
$dados .=       "<thead>";
$dados .=           "<th>CNPJ</th>";
$dados .=           "<th>Razão</th>";
$dados .=           "<th>Endereço</th>";
$dados .=           "<th>Cidade</th>";
$dados .=           "<th>Bairro</th>";
$dados .=           "<th>Cep</th>";
$dados .=           "<th>UF</th>";
$dados .=           "<th>Fone</th>";
$dados .=        "</thead>"; 

$dados .= "<tbody>";
$dados .=   "<tr colspan=8 style='text-align: center;'>";
$dados .=       "<td>Sem registro</td>";
$dados .=   "</tr>"; 
$dados .= "</tbody>";
}
$dados .=  "</table>";
$dados .= "</div>";

//empresas bloqueadas 
$dados .= "<div class='main'>";
$dados .= "<h3>Empresas bloquedas</h3>";
$bloqueadas = 0;
$dados .=   "<table>";  
    $dados .= "<thead>";
        $dados .= "<th>CNPJ</th>";
        $dados .= "<th>Razão</th>";
        $dados .= "<th>Endereço</th>";
        $dados .= "<th>Cidade</th>";
        $dados .= "<th>Bairro</th>";
        $dados .= "<th>Cep</th>";
        $dados .= "<th>UF</th>";
        $dados .= "<th>Fone</th>";
    $dados .= "</thead>";

    $num = $b->num_rows;
    if($num > 0) {  
        while($user_data = mysqli_fetch_assoc($b)){
            if($user_data['bloqueadas'] == 'S'){
                $cod = $user_data['codigo'];
                $bloqueadas++;
                $dados .= "<tbody>";
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
                $dados .= "</tbody>";
            }   
        }
} else {
    $bloqueadas = 0;
    $dados .= "<tbody>";
    $dados .=   "<tr>";
    $dados .=       "<td colspan=8 style='text-align: center;'>Sem registro</td>";
    $dados .=   "</tr>"; 
    $dados .= "</tbody>";
    }
    $dados .=  "</table>";
    $dados .= "</div>";

//empresas isentas 
$dados .= "<div class='main'>";
$dados .= "<h3>Empresas isentas</h3>";
$isentas = 0;
$dados .=   "<table";  
    $dados .= "<thead>";
        $dados .= "<th>CNPJ</th>";
        $dados .= "<th>Razão</th>";
        $dados .= "<th>Endereço</th>";
        $dados .= "<th>Cidade</th>";
        $dados .= "<th>Bairro</th>";
        $dados .= "<th>Cep</th>";
        $dados .= "<th>UF</th>";
        $dados .= "<th>Fone</th>";
    $dados .= "</thead>";
    $num = $i->num_rows;
    if($num > 0){
        while($user_data = mysqli_fetch_assoc($i)){
            if($user_data['isento'] == '1'){
                $cod = $user_data['codigo'];
                $isentas++;
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
             
    }} else {
        $isentas = 0;    
        $dados .= "<tbody>";
        $dados .=   "<tr>";
        $dados .=       "<td colspan=8 style='text-align: center;'>Sem registro</td>";
        $dados .=   "</tr>"; 
        $dados .= "</tbody>";
}
$dados .=  "</table>";
$dados .= "</div>";

//empresas vencidas

$hoje = date('Y-m-d');
$dados .= "<div class='main'>";
$dados .= "<h3>Empresas Vencidas</h3>";

$vencidas = 0;
$dados .=   "<table";  
    $dados .= "<thead>";
        $dados .= "<th>CNPJ</th>";
        $dados .= "<th>Razão</th>";
        $dados .= "<th>Endereço</th>";
        $dados .= "<th>Cidade</th>";
        $dados .= "<th>Bairro</th>";
        $dados .= "<th>Cep</th>";
        $dados .= "<th>UF</th>";
        $dados .= "<th>Fone</th>";
    $dados .= "</thead>";

    $num= $v->num_rows;
    if($num > 0){  
    while($user_data = mysqli_fetch_assoc($v)){
        $vencimento = $user_data['validade_licenca'];
        if(strtotime($hoje) > strtotime($vencimento)){
        $cod = $user_data['codigo'];
        $vencidas++;
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
    }
} else {
    $vencidas = 0;
    $dados .= "<tbody>";
    $dados .=   "<tr>";
    $dados .=       "<td colspan=8 style='text-align: center;'>Sem registro</td>";
    $dados .=   "</tr>"; 
    $dados .= "</tbody>";
    }
$dados .=  "</table>";
$dados .= "</div>";

//Resumo
$dados .= "<div class='resumo'>";
$dados .=   "<table>";
$dados .=       "<thead>";
$dados .=           "<tr>";
$dados .=               "<td><strong>Total de empresas ativas</strong></td>";
$dados .=               "<td><strong>Total de empresas bloqueadas</strong></td>";
$dados .=               "<td><strong>Total de empresas isentas</strong></td>";
$dados .=               "<td><strong>Total de empresas vencidas</strong></td>";
$dados .=           "</tr>";
$dados .=       "</thead>";
$dados .=    "<tbody>";
while($data = mysqli_fetch_assoc($r)){
    if($data['NOME'] == $_SESSION['nome']){
        $total_mensalidade = $data['MENSALIDADE'];
        $dados .= "<tr>";
        $dados .= "<td>".$data['RAZAO']."</td>";
        $dados .= "<td>".$bloqueadas."</td>";
        $dados .= "<td>".$isentas."</td>";
        $dados .= "<td>".$vencidas."</td>";
        $dados .= "</tr>";
        };
    } 
$dados .=    "</tbody>";
$dados .= "</table>"; 
$dados .= "</div>";

//Total mensalidade
$dados .= "<div class='footer'>";
$dados .=   "<table>";
$dados .=       "<thead>";
$dados .=           "<tr>";
$dados .=               "<td style='text-align: center;'><strong>Total da mensalidade</strong></td>";
$dados .=               "<td style='text-align: center;'>".$total_mensalidade." R$</td>";
$dados .=           "</tr>";
$dados .=       "</thead>";
$dados .=   "</table>";
$dados .= "</div>";

$dados .= "</body>";

$dompdf->loadHtml($dados);
$dompdf->render();
header('Content-type: application/pdf');
echo $dompdf->output();