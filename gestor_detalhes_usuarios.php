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
<title>Detalhes do Usuário</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-body-tertiary">

<div class="container mt-5">

    <h2 class="mb-4">Detalhes do Usuário</h2>

    <div class="card p-4">

        <p><strong>ID:</strong> <?= $usuario['id'] ?></p>
        <p><strong>Nome:</strong> <?= $usuario['nome'] ?></p>
        <p><strong>Email:</strong> <?= $usuario['email'] ?></p>
        <p><strong>Perfil:</strong> <?= strtoupper($usuario['perfil']) ?></p>
        <p><strong>Status:</strong> <?= $usuario['status'] ?></p>

        <div class="mt-3">

            <a href="gestor_editar_usuario.php?id=<?= $usuario['id'] ?>" 
               class="btn btn-primary">
               Atualizar
            </a>

            <a href="api/deletar_usuario.php?id=<?= $usuario['id'] ?>" 
               class="btn btn-danger"
               onclick="return confirm('Deseja deletar este usuário?')">
               Deletar
            </a>

        </div>

    </div>

</div>

</body>
</html>