<?php
include "../../classes/Controle.php";

$id = $_POST['id'];
$respostaEmail = $_POST['editor-content'];
$email = $_POST['email'];



$Controle = new Controle();

if ($Controle->resposta($id, $respostaEmail, $email)) {
    echo '<script>alert("Resposta enviada com sucesso!");
            window.location="../../faculdade";</script>';
} else {
    echo '<script>alert("Erro ao enviar resposta ");
            window.location="../../faculdade";</script>';
}