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
            <a href="gestor_dashboard.php" class="btn btn-link text-light text-decoration-none me-2"></a>
            <a class="navbar-brand text-light fw-bold" href="gestor_dashboard.php">SGM Admin</a>
            
            <div class="navbar-nav ms-auto gap-2">
                <a href="api/logout.php" class="btn btn-outline-light btn-sm ms-2 rounded-pill px-3">Sair</a>
            </div>
        </div>
    </nav>
</header>

<main class="container">

    <div class="text-center mb-5">
        <h2 class="fw-bold text-dark">Visão Geral</h2>
        <p class="text-muted small">Status das operações em tempo real</p>
    </div>

    <div class="row justify-content-center g-3">

        <div class="col-md-4">
            <div class="card border-0 shadow-sm text-white" style="background-color: #1b7a4d; border-radius: 12px;">
                <div class="card-body d-flex align-items-center p-3">
                    <div class="bg-white bg-opacity-25 rounded-3 p-3 me-3">
                        <i class="bi bi-bell-fill fs-3"></i>
                    </div>
                    <div>
                        <h6 class="text-uppercase mb-0 small opacity-75 fw-bold">Novas</h6>
                        <h2 class="fw-bold m-0">0</h2>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm text-white" style="background-color: #f1b40e; border-radius: 12px;">
                <div class="card-body d-flex align-items-center p-3">
                    <div class="bg-white bg-opacity-25 rounded-3 p-3 me-3">
                        <i class="bi bi-tools fs-3"></i>
                    </div>
                    <div>
                        <h6 class="text-uppercase mb-0 small opacity-75 fw-bold">Atendimento</h6>
                        <h2 class="fw-bold m-0">0</h2>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm text-white" style="background-color: #2d3436; border-radius: 12px;">
                <div class="card-body d-flex align-items-center p-3">
                    <div class="bg-white bg-opacity-25 rounded-3 p-3 me-3 text-danger">
                        <i class="bi bi-exclamation-triangle-fill fs-3"></i>
                    </div>
                    <div>
                        <h6 class="text-uppercase mb-0 small opacity-75 fw-bold">Críticos</h6>
                        <h2 class="fw-bold m-0">0</h2>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="row justify-content-center mt-5">
        <div class="col-auto">
            <div class="bg-white p-2 rounded-pill shadow-sm border d-flex gap-2">
                <a href="./gestor_chamados.php" class="btn btn-danger px-4 rounded-pill fw-bold shadow-sm" style="background-color: #990202; border: none;">
                    <i class="bi bi-list-ul me-2"></i> Gerenciar todos os chamados.</a>
                <a href="#" class="btn btn-light px-4 rounded-pill fw-bold text-secondary border">
                    <i class="bi bi-geo-alt me-2"></i> Gerenciar locais.</a>
            </div>
        </div>
    </div>

</main>

<footer class="text-center mt-5 py-4 text-muted small">
    <p>&copy; 2026 SMG Gestão de Manutenção - Luma Massaranduba</p>
</footer>

</body>
</html>