<?php
session_start();

// Proteção de acesso
if (!isset($_SESSION['user_id']) || $_SESSION['user_perfil'] !== 'gestor') {
    header("Location: /2025/PROJETO_SGM/login.php");
    exit;
}

require_once 'config/database.php';

// Recebe ID
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($id <= 0) {
    die("ID inválido.");
}

// Busca usuário
$sql = "SELECT * FROM usuarios WHERE id_usuario = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();

$result = $stmt->get_result();

$usuario = $result->fetch_assoc();

if (!$usuario) {
    die("Usuário não encontrado.");
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SGM - Editar Usuário</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        :root {
            --vinho-sgm: #990202;
        }

        body {
            background-color: #f8f9fa;
            font-family: 'Inter', sans-serif;
        }

        .navbar-custom {
            background-color: var(--vinho-sgm);
        }

        .card-form {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        .form-control,
        .form-select {
            border: 1px solid #dee2e6;
            padding: 0.75rem 1rem;
            border-radius: 10px;
            background-color: #fcfcfc;
        }

        .form-control:focus {
            border-color: var(--vinho-sgm);
            box-shadow: 0 0 0 0.25rem rgba(153, 2, 2, 0.1);
        }

        .btn-save {
            background-color: var(--vinho-sgm);
            color: white;
            border-radius: 50px;
            font-weight: 600;
            padding: 0.75rem;
            transition: 0.3s;
        }

        .btn-save:hover {
            background-color: #7a0202;
            color: white;
            transform: translateY(-2px);
        }

        .label-custom {
            font-size: 0.85rem;
            font-weight: 700;
            color: #495057;
            margin-bottom: 5px;
            text-transform: uppercase;
        }
    </style>
</head>

<body>

    <header>

        <nav class="navbar navbar-expand-lg navbar-dark navbar-custom shadow-sm mb-5">

            <div class="container py-1">

                <a href="/2025/PROJETO_SGM/gestor_detalhes_usuarios.php?id=<?= $id ?>"
                    class="btn btn-link text-light text-decoration-none me-2">

                    <i class="bi bi-arrow-left-circle-fill fs-4"></i>

                </a>

                <a class="navbar-brand fw-bold"
                    href="/2025/PROJETO_SGM/dashboard_gestor.php">

                    SGM Admin

                </a>

                <div class="navbar-nav ms-auto">

                    <span class="nav-link active small">
                        Editar Perfil
                    </span>

                </div>

            </div>

        </nav>

    </header>

    <main class="container">

        <div class="row justify-content-center">

            <div class="col-lg-5 col-md-7">

                <div class="text-center mb-4">

                    <h3 class="fw-bold">
                        Alterar Dados
                    </h3>

                    <p class="text-muted small">
                        ID do Usuário:
                        #<?= $usuario['id_usuario'] ?>
                    </p>

                </div>

                <div class="card card-form p-4">

                    <form action="/2025/PROJETO_SGM/api/api_atualizar_usuarios.php"
                        method="POST">

                        <input type="hidden"
                            name="id_usuario"
                            value="<?= $usuario['id_usuario'] ?>">

                        <div class="mb-3">

                            <label class="label-custom">
                                Nome Completo
                            </label>

                            <input type="text"
                                name="nome"
                                class="form-control"
                                value="<?= htmlspecialchars($usuario['nome']) ?>"
                                required>

                        </div>

                        <div class="mb-3">

                            <label class="label-custom">
                                E-mail
                            </label>

                            <input type="email"
                                name="email"
                                class="form-control"
                                value="<?= htmlspecialchars($usuario['email']) ?>"
                                required>

                        </div>

                        <div class="row">

                            <div class="col-md-6 mb-3">

                                <label class="label-custom">
                                    Perfil
                                </label>

                                <select name="perfil"
                                    class="form-select">

                                    <option value="solicitante"
                                        <?= $usuario['perfil'] == 'solicitante' ? 'selected' : '' ?>>

                                        Solicitante

                                    </option>

                                    <option value="tecnico"
                                        <?= $usuario['perfil'] == 'tecnico' ? 'selected' : '' ?>>

                                        Técnico

                                    </option>

                                    <option value="gestor"
                                        <?= $usuario['perfil'] == 'gestor' ? 'selected' : '' ?>>

                                        Gestor

                                    </option>

                                </select>

                            </div>

                            <div class="col-md-6 mb-4">

                                <label class="label-custom">
                                    Status
                                </label>

                                <select name="ativo"
                                    class="form-select">

                                    <option value="1"
                                        <?= $usuario['ativo'] == 1 ? 'selected' : '' ?>>

                                        Ativo

                                    </option>

                                    <option value="0"
                                        <?= $usuario['ativo'] == 0 ? 'selected' : '' ?>>

                                        Inativo

                                    </option>

                                </select>

                            </div>

                        </div>

                        <button type="submit"
                            class="btn btn-save w-100 shadow-sm">

                            <i class="bi bi-check-lg me-2"></i>

                            SALVAR ALTERAÇÕES

                        </button>

                    </form>

                </div>

                <div class="text-center mt-4">

                    <a href="/2025/PROJETO_SGM/gestor_usuarios.php"
                        class="text-decoration-none text-secondary small">

                        Cancelar e voltar

                    </a>

                </div>

            </div>

        </div>

    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>