<?php
include('./app/conexao.php');

if(!isset($_SESSION)) {
    session_start();
}

$emp = $_SESSION['id'];
$cod = $_POST['cod'];

$empresa = "SELECT * FROM empresa WHERE id_revenda = '$emp' AND codigo = '$cod'";
$result = $mysqli->query($empresa);

if($result->num_rows > 0){
    while($user_data = mysqli_fetch_assoc($result)){
    echo "<div class='header-div'>";
        echo "<h4>EDITAR</h4>";
        echo "<button onclick='fechaModal()'><img src='./images/close-white.svg' alt='fechar'></button>";
    echo "</div>";

    echo "<div class='info'>";
        echo "<h5 class='info-esquerda'>Empresa: ".$user_data['razao']."</h5>";
        echo "<h5 class='info-direita'>Vencimento: ".$user_data['validade_licenca']."</h5>";
    echo "</div>";
            
    echo "<div class='form'>";
        echo "<form action='salvaEditData.php?cod=".$cod."' method='POST' class='form'>";
            echo "<label for='date'>Nova data de vencimento</label>";
            echo "<input type='date' id='date' name='date' value=".$user_data['validade_licenca'].">";
            echo "<button class='salva-edit' type='submit' name='update' id='update'>Salvar</button>";
        echo "</form>";

        echo "<form action='salvaEditSuspensao.php?cod=".$cod."' method='POST' class='select'>";
            echo "<h4>Suspender usuário</h4>";
            echo "<select id='dia' name='dia'>";
                echo "<option value='' selected>Selecione uma opção</option>";
                echo "<option value='1'> 1 dia</option>";
                echo "<option value='3'> 3 dias</option>";
                echo "<option value='7'> 7 dias</option>";
            echo "</select>";
            echo "<button type='submit' name='suspender' id='suspender'>Suspender</button>";
        echo "</form>";
    echo "</div>";
    }
    
} else {
    echo "<div>Codigo invalido</div>";
}
