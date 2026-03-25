<?php
include 'conexao.php';

$id = $_GET['id'];

$sql = "SELECT * FROM usuarios WHERE id = $id";
$result = mysqli_query($conn, $sql);
$usuario = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title>Editar Usuário</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="container mt-5">

<h2>Editar Usuário</h2>

<form action="api/atualizar_usuario.php" method="POST">

<input type="hidden" name="id" value="<?= $usuario['id'] ?>">

<input type="text" name="nome" value="<?= $usuario['nome'] ?>" class="form-control mb-2">

<input type="email" name="email" value="<?= $usuario['email'] ?>" class="form-control mb-2">

<input type="text" name="perfil" value="<?= $usuario['perfil'] ?>" class="form-control mb-2">

<input type="text" name="status" value="<?= $usuario['status'] ?>" class="form-control mb-2">

<button class="btn btn-success">Salvar</button>

</form>

</body>
</html>