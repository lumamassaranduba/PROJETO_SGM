<?php
include '../conexao.php';

$id = $_GET['id'];

$sql = "DELETE FROM usuarios WHERE id = $id";
mysqli_query($conn, $sql);

header("Location: ../gestor_usuarios.php");
?>