<?php
include "../../classes/Controle.php";

$name = $_POST['name'];
$email = $_POST['email'];
$password = $_POST['senha'];

if ($name == "" || $name == null  || $email == "" || $email == null || $password == "" || $password == null) {
    echo '<script>alert("Campos usuário e senha devem ser preenchidos!");
        window.location="../../cadastro-usuario";</script>';
} else {

    $password = password_hash($_POST['senha'], PASSWORD_DEFAULT);

    $Controle = new Controle();

    if ($Controle->cadastrarUsuario($name, $email, $password)) {
        echo '<script>alert("Usuário cadastrado com sucesso!");
            window.location="../../usuarios";</script>';
    } else {
        echo '<script>alert("Nome de usuário já existe!");
            window.location="../../cadastro-usuario";</script>';
    }
}