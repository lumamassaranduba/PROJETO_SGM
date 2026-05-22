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
        /* Identidade Visual Padronizada SGM */
        :root {
            --vinho-dark: #7a0101;
            --vinho-light: #990202;
            --sgm-gold: #ffc107;
        }
        body { 
            background-color: #f0f2f5; 
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif; 
        }
        
        /* Navbar Padrão SGM ADMIN */
        .navbar { 
            background: linear-gradient(135deg, var(--vinho-dark) 0%, var(--vinho-light) 100%); 
            border-bottom: 4px solid var(--sgm-gold); 
            height: 65px; 
        }

        /* Botão Voltar Destacado (Mobile-First) */
        .btn-voltar-container {
            margin-bottom: 1rem;
        }
        .btn-voltar-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: #6c757d;
            font-weight: 600;
            font-size: 0.9rem;
            text-decoration: none;
            padding: 6px 16px;
            background-color: #ffffff;
            border: 1px solid #dee2e6;
            border-radius: 50px;
            transition: all 0.2s ease;
            box-shadow: 0 2px 4px rgba(0,0,0,0.02);
        }
        .btn-voltar-link:hover {
            color: var(--vinho-light);
            border-color: var(--vinho-light);
            background-color: rgba(153, 2, 2, 0.03);
            transform: translateX(-3px);
        }

        /* Card de Detalhes com a Borda da Nav */
        .card-detalhes { 
            border: none; 
            border-radius: 16px; 
            box-shadow: 0 4px 12px rgba(0,0,0,0.05); 
            background: white;
            padding: 2rem 1.5rem;
            border-top: 4px solid var(--sgm-gold);
        }
        
        .info-label { 
            font-size: 0.75rem; 
            text-transform: uppercase; 
            font-weight: 700; 
            color: #2d3436; 
            letter-spacing: 0.5px; 
        }
        .info-value { 
            font-weight: 600; 
            color: #57606f; 
            margin-bottom: 0; 
            font-size: 1.05rem;
        }
        
        /* Badges e Avatares */
        .badge-perfil { 
            font-size: 0.8rem; 
            font-weight: 700;
            padding: 6px 14px; 
            border-radius: 50px; 
        }
        
        .avatar-circle {
            width: 75px;
            height: 75px;
            background-color: #f8f9fa;
            color: #a4b0be;
            border: 1px solid #edf2f7;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            font-size: 2.2rem;
        }

        /* Botões Mobile-Friendly */
        .btn-action-edit {
            border-radius: 50px;
            padding: 12px;
            font-weight: 700;
            font-size: 0.9rem;
        }
        .btn-action-delete {
            border-radius: 50px;
            padding: 12px;
            font-weight: 700;
            font-size: 0.9rem;
        }

        @media (max-width: 576px) {
            .btn-voltar-container {
                text-align: center;
            }
            .header-detalhes {
                flex-direction: column;
                gap: 10px;
                text-align: center;
            }
        }
    </style>
</head>
<body>

<header>
    <nav class="navbar navbar-dark shadow-sm mb-4 px-4">
        <div class="container-fluid d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <i class="bi bi-shield-lock-fill text-warning fs-3 me-2"></i>
                <a class="navbar-brand fw-bold mb-0 text-white" href="gestor_dashboard.php">SGM ADMIN</a>
            </div>
            <a href="api/logout.php" class="btn btn-outline-light btn-sm rounded-pill px-3 fw-semibold">Sair</a>
        </div>
    </nav>
</header>

<main class="container px-3">
    <div class="row justify-content-center">
        <div class="col-12 col-sm-10 col-md-8 col-lg-6">
            
            <div class="btn-voltar-container">
                <a href="gestor_usuarios.php" class="btn-voltar-link">
                    <i class="bi bi-arrow-left"></i> Voltar para Usuários
                </a>
            </div>

            <div class="d-flex justify-content-between align-items-center mb-4 header-detalhes">
                <h3 class="fw-bold text-dark m-0">Perfil do Usuário</h3>
                <span class="badge <?= $usuario['ativo'] == 1 ? 'bg-success bg-opacity-10 text-success border-success border-opacity-25' : 'bg-danger bg-opacity-10 text-danger border-danger border-opacity-25' ?> border px-3 py-2 rounded-pill fw-bold">
                    <i class="bi bi-circle-fill me-1" style="font-size: 8px;"></i>
                    <?= $usuario['ativo'] == 1 ? 'ATIVO' : 'INATIVO' ?>
                </span>
            </div>

            <div class="card card-detalhes mb-5">
                <div class="text-center mb-4">
                    <div class="avatar-circle shadow-sm">
                        <i class="bi bi-person"></i>
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

                    <div class="col-6 border-bottom pb-2">
                        <label class="info-label">Perfil de Sistema</label>
                        <div class="mt-1">
                            <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25 badge-perfil">
                                <?= strtoupper(htmlspecialchars($usuario['perfil'])) ?>
                            </span>
                        </div>
                    </div>

                    <div class="col-6 border-bottom pb-2">
                        <label class="info-label">ID Identificador</label>
                        <p class="info-value">#<?= $usuario['id_usuario'] ?></p>
                    </div>
                </div>

                <div class="row mt-4 pt-2 g-2">
                    <div class="col-6">
                        <a href="gestor_atualizar_usuarios.php?id=<?= $usuario['id_usuario'] ?>" class="btn btn-warning btn-action-edit w-100 text-dark shadow-sm">
                            <i class="bi bi-pencil-square me-1"></i> EDITAR
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="api/api_deletar_usuarios.php?id=<?= $usuario['id_usuario'] ?>"
                           class="btn btn-outline-danger btn-action-delete w-100 shadow-sm"
                           onclick="return confirm('ATENÇÃO: Deseja realmente excluir este usuário?')">
                            <i class="bi bi-trash me-1"></i> EXCLUIR
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>