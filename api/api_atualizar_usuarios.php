<?php
include '../conexao.php';

$id = $_POST['id'];
$nome = $_POST['nome'];
$email = $_POST['email'];
$perfil = $_POST['perfil'];
$status = $_POST['status'];

$sql = "UPDATE usuarios 
        SET nome='$nome', email='$email', perfil='$perfil', status='$status'
        WHERE id=$id";

mysqli_query($conn, $sql);

header("Location: ../gestor_usuarios.php");
?>