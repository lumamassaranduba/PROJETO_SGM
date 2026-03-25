

<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>SGM - Dashboard</title>

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
            <a class="nav-link px-3 rounded-pill text-light bg-white bg-opacity-10" href="gestor_chamados.php">Chamados</a>
            <a class="nav-link px-3 text-light" href="gestor_dashboard.php">Home</a>
            <a href="api/logout.php" class="btn btn-outline-light btn-sm ms-2 rounded-pill px-3">Sair</a>
        </div>
    </div>
</nav>
</header>

<main class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-dark m-0 p-3">Visão Geral dos Usuários</h2>
        <a href="gestor_adicionar_usuario.php" class="btn text-white rounded-pill px-4 fw-bold shadow-sm m-3" style="background-color: #990202;">
            <i class="bi bi-plus-lg me-1"></i> Adicionar novo usuario
        </a>
    </div>

    <!-- CARDS DINÂMICOS -->
    <div class="d-flex flex-wrap">

        <?php while($usuario = mysqli_fetch_assoc($result)) { ?>

        <div class="card m-3" style="width: 18rem;">
            <div class="card-body">

                <h5 class="card-title"><?= $usuario['nome'] ?></h5>

                <h6 class="card-subtitle mb-2 text-body-secondary">
                    <?= strtoupper($usuario['perfil']) ?>
                </h6>

                <a href="gestor_usuario_detalhes.php?id=<?= $usuario['id'] ?>">
                    <button class="btn btn-sm px-3 rounded-pill bg-warning text-white shadow-sm"
                        style="font-size: 12px; font-weight: 600;">
                        <i class="bi bi-plus-lg me-1"></i> VER MAIS
                    </button>
                </a>

            </div>
        </div>

        <?php } ?>

    </div>

</main>

</body>
</html>