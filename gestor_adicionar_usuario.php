<?php
session_start();
// Proteção de acesso: apenas gestores podem criar usuários
if (!isset($_SESSION['user_id']) || $_SESSION['user_perfil'] !== 'gestor') {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SGM - Adicionar Usuário</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <style>
        :root { --vinho-sgm: #990202; }
        body { background-color: #f8f9fa; font-family: 'Inter', sans-serif; }
        .navbar-custom { background-color: var(--vinho-sgm); }
        .card-form { border: none; border-radius: 15px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
        .form-control, .form-select { border: 1px solid #dee2e6; padding: 0.75rem 1rem; border-radius: 10px; background-color: #fcfcfc; }
        .form-control:focus { border-color: var(--vinho-sgm); box-shadow: 0 0 0 0.25rem rgba(153, 2, 2, 0.1); }
        .btn-save { background-color: var(--vinho-sgm); color: white; border-radius: 50px; font-weight: 600; padding: 0.75rem; transition: 0.3s; border: none; }
        .btn-save:hover { background-color: #7a0202; color: white; transform: translateY(-2px); }
        .label-custom { font-size: 0.85rem; font-weight: 700; color: #495057; margin-bottom: 5px; text-transform: uppercase; }
        .alert-info-password { font-size: 0.8rem; color: #6c757d; border-left: 3px solid var(--vinho-sgm); padding-left: 10px; }
    </style>
</head>
<body>

<header>
    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom shadow-sm mb-5">
        <div class="container py-1">
            <a href="gestor_usuarios.php" class="btn btn-link text-light text-decoration-none me-2">
                <i class="bi bi-arrow-left-circle-fill fs-4"></i>
            </a>
            <a class="navbar-brand fw-bold" href="gestor_dashboard.php">SGM Admin</a>
            <div class="navbar-nav ms-auto">
                <span class="nav-link active small">Novo Cadastro</span>
            </div>
        </div>
    </nav>
</header>

<main class="container">
    <div class="row justify-content-center">
        <div class="col-lg-5 col-md-7">
            
            <div class="text-center mb-4">
                <h3 class="fw-bold">Criar Novo Usuário</h3>
                <p class="text-muted small">Preencha os dados abaixo para liberar o acesso ao sistema.</p>
            </div>

            <div class="card card-form p-4">
               <form action="api/api_adicionar_usuario.php" method="POST">
                    
                    <div class="mb-3">
                        <label class="label-custom">Nome Completo</label>
                        <input type="text" name="nome" class="form-control" placeholder="Ex: João Silva" required>
                    </div>

                    <div class="mb-3">
                        <label class="label-custom">E-mail Corporativo</label>
                        <input type="email" name="email" class="form-control" placeholder="joao@empresa.com" required>
                    </div>

                    <div class="mb-3">
                        <label class="label-custom">Senha Inicial</label>
                        <input type="password" name="senha" class="form-control" placeholder="••••••••" required>
                        <div class="alert-info-password mt-2">
                            Defina uma senha temporária. O usuário poderá alterá-la depois.
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="label-custom">Perfil de Acesso</label>
                        <select name="perfil" class="form-select" required>
                            <option value="" selected disabled>Selecione um perfil...</option>
                            <option value="solicitante">Solicitante </option>
                            <option value="tecnico">Técnico </option>
                            <option value="gestor">Gestor</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-save w-100 shadow-sm">
                        <i class="bi bi-person-plus-fill me-2"></i>CADASTRAR USUÁRIO
                    </button>
                </form>
            </div>

            <div class="text-center mt-4 mb-5">
                <a href="gestor_usuarios.php" class="text-decoration-none text-secondary small">
                    Cancelar e voltar para a lista
                </a>
            </div>

        </div>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>