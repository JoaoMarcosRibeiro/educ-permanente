<?php session_start();
include "../../classes/Controle.php";
$email = $_POST['email'];
$password = $_POST['password'];

$Controle = new Controle();

if ($email == "" || $email == null) {
    echo '<script>alert("Campo usuário deve ser preenchido!");
        window.location="../../";</script>';

} else if ($Controle->autenticarFaculdade($email, $password)) {
    header("Location:../../faculdade-index");
} else {
    echo '<script>alert("E-mail ou senha incorreta!");
    window.location="../../login-faculdade";</script>';
}