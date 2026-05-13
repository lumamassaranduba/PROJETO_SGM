<?php
// Correção: Caminho correto para o arquivo de conexão
include('./config/database.php');

$sql = "SELECT * FROM usuarios";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SGM - Gestão de Usuários</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body class="bg-body-tertiary">

<header>
   <nav class="navbar navbar-expand-lg shadow-sm mb-5" style="background-color: #990202;">
    <div class="container py-1">
        <a href="gestor_dashboard.php" class="btn btn-link text-light text-decoration-none me-2">
            <i class="bi bi-arrow-left-circle-fill fs-4"></i>
        </a>
        <a class="navbar-brand text-light fw-bold" href="gestor_dashboard.php">SGM Admin</a>
        <div class="navbar-nav ms-auto gap-2">
            <a href="api/logout.php" class="btn btn-outline-light btn-sm ms-2 rounded-pill px-3">Sair</a>
        </div>
    </div>
</nav>
</header>

<main class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-dark m-0 p-3">Visão Geral dos Usuários</h2>
        <a href="gestor_adicionar_usuario.php" class="btn text-white rounded-pill px-4 fw-bold shadow-sm m-3" style="background-color: #990202;">
            <i class="bi bi-plus-lg me-1"></i> Adicionar novo usuário
        </a>
    </div>

    <div class="d-flex flex-wrap justify-content-center">
        <?php while($usuario = mysqli_fetch_assoc($result)) { ?>
        <div class="card m-3 shadow-sm" style="width: 18rem; border-radius: 15px;">
            <div class="card-body text-center">
                <div class="mb-3">
                    <i class="bi bi-person-circle fs-1 text-secondary"></i>
                </div>
                <h5 class="card-title fw-bold"><?= $usuario['nome'] ?></h5>
                <h6 class="card-subtitle mb-3 text-muted">
                    <span class="badge bg-secondary-subtle text-secondary border">
                        <?= strtoupper($usuario['perfil']) ?>
                    </span>
                </h6>
                
                <a href="gestor_detalhes_usuarios.php?id=<?= $usuario['id_usuario'] ?>" class="text-decoration-none">
                    <button class="btn btn-sm px-4 rounded-pill bg-white text-secondary border fw-bold shadow-sm">
                        <i class="bi bi-eye"></i> DETALHES
                    </button>
                </a>
            </div>
        </div>
        <?php } ?>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>