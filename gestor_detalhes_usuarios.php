<?php
// config/database.php contém a conexão com o banco
include 'config/database.php'; 

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$usuario = null;

if ($id > 0) {
    // Busca usando a coluna correta: id_usuario
    $sql = "SELECT * FROM usuarios WHERE id_usuario = $id"; 
    $result = mysqli_query($conn, $sql);
    $usuario = mysqli_fetch_assoc($result);
}

if (!$usuario) {
    echo "<div class='container mt-5 d-flex justify-content-center'><div class='alert alert-danger'>Usuário não encontrado. <a href='gestor_usuarios.php'>Voltar</a></div></div>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SGM - Detalhes do Usuário</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <style>
        :root { --vinho-sgm: #990202; }
        body { background-color: #f8f9fa; font-family: 'Inter', system-ui, -apple-system, sans-serif; }
        .navbar-custom { background-color: var(--vinho-sgm); }
        .card-detalhes { border: none; border-radius: 15px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
        .info-label { font-size: 0.75rem; text-uppercase; font-weight: 700; color: #6c757d; letter-spacing: 0.5px; }
        .info-value { font-weight: 600; color: #2d3436; margin-bottom: 0; }
        .btn-vinho { background-color: var(--vinho-sgm); color: white; border-radius: 50px; font-weight: 600; transition: 0.3s; }
        .btn-vinho:hover { background-color: #7a0202; color: white; transform: translateY(-2px); }
        .badge-perfil { font-size: 0.85rem; padding: 0.5em 1em; border-radius: 50px; }
    </style>
</head>
<body>

<header>
    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom shadow-sm mb-4">
        <div class="container py-1">
            <a href="gestor_usuarios.php" class="btn btn-link text-light text-decoration-none me-2">
                <i class="bi bi-arrow-left-circle-fill fs-4"></i>
            </a>
            <a class="navbar-brand fw-bold" href="gestor_dashboard.php">SGM Admin</a>
            <div class="navbar-nav ms-auto">
                <a href="api/logout.php" class="btn btn-outline-light btn-sm rounded-pill px-3">Sair</a>
            </div>
        </div>
    </nav>
</header>

<main class="container">
    <div class="row justify-content-center">
        <div class="col-lg-6 col-md-8">
            
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="fw-bold text-dark m-0">Perfil do Usuário</h3>
                <span class="badge <?= $usuario['ativo'] == 1 ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' ?> border px-3 py-2 rounded-pill">
                    <i class="bi bi-circle-fill me-1" style="font-size: 8px;"></i>
                    <?= $usuario['ativo'] == 1 ? 'ATIVO' : 'INATIVO' ?>
                </span>
            </div>

            <div class="card card-detalhes p-4 mb-4">
                <div class="text-center mb-4">
                    <div class="d-inline-flex align-items-center justify-content-center bg-light rounded-circle shadow-sm" style="width: 80px; height: 80px;">
                        <i class="bi bi-person-fill text-secondary" style="font-size: 2.5rem;"></i>
                    </div>
                </div>

                <div class="row g-3">
                    <div class="col-12 border-bottom pb-2">
                        <label class="info-label">Nome Completo</label>
                        <p class="info-value"><?= htmlspecialchars($usuario['nome']) ?></p>
                    </div>

                    <div class="col-12 border-bottom pb-2">
                        <label class="info-label">E-mail de Acesso</label>
                        <p class="info-value"><?= htmlspecialchars($usuario['email']) ?></p>
                    </div>

                    <div class="col-md-6 border-bottom pb-2">
                        <label class="info-label">Perfil de Sistema</label>
                        <p class="mt-1">
                            <span class="badge bg-secondary-subtle text-secondary border badge-perfil">
                                <?= strtoupper($usuario['perfil']) ?>
                            </span>
                        </p>
                    </div>

                    <div class="col-md-6 border-bottom pb-2">
                        <label class="info-label">ID Identificador</label>
                        <p class="info-value">#<?= $usuario['id_usuario'] ?></p>
                    </div>
                </div>

                <div class="row mt-4 pt-2 g-2">
                    <div class="col-6">
                        <a href="gestor_atualizar_usuarios.php?id=<?= $usuario['id_usuario'] ?>" class="btn btn-warning w-100 rounded-pill fw-bold text-dark shadow-sm">
                            <i class="bi bi-pencil-square me-1"></i> EDITAR
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="api/api_deletar_usuarios.php?id=<?= $usuario['id_usuario'] ?>"
   class="btn btn-outline-danger w-100 rounded-pill fw-bold shadow-sm"
   onclick="return confirm('ATENÇÃO: Deseja realmente excluir este usuário?')">

    <i class="bi bi-trash me-1"></i>
    EXCLUIR

</a>
                    </div>
                </div>
            </div>

            <div class="text-center">
                <a href="gestor_usuarios.php" class="text-decoration-none text-secondary small fw-bold">
                    <i class="bi bi-chevron-left"></i> VOLTAR PARA A LISTA
                </a>
            </div>

        </div>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>