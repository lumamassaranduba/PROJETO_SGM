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

        /* Barra de Pesquisa Otimizada */
        .search-container {
            max-width: 500px;
        }
        .search-input {
            border-radius: 50px 0 0 50px !important;
            padding: 10px 20px;
            border-color: #dee2e6;
            border-right: none;
        }
        .search-input:focus {
            border-color: var(--vinho-light);
            box-shadow: 0 0 0 0.25rem rgba(153, 2, 2, 0.1);
        }
        .search-btn {
            border-radius: 0 50px 50px 0 !important;
            background-color: #ffffff;
            border: 1px solid #dee2e6;
            border-left: none;
            color: #6c757d;
            padding-right: 20px;
        }

        /* Cards de Usuário com a Borda Idêntica à Nav */
        .user-card {
            border: none;
            border-radius: 16px;
            background-color: #ffffff;
            transition: all 0.25s ease;
            box-shadow: 0 4px 12px rgba(0,0,0,0.03);
            /* Aplica a borda amarela apenas no topo para simular o efeito da nav */
            border-top: 4px solid var(--sgm-gold);
            overflow: hidden;
        }
        .user-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.08);
        }

        .avatar-circle {
            width: 65px;
            height: 65px;
            background-color: #f8f9fa;
            color: #a4b0be;
            border: 1px solid #edf2f7;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            font-size: 2rem;
        }

        /* Badges baseadas no perfil */
        .badge-perfil {
            font-weight: 700;
            font-size: 0.75rem;
            letter-spacing: 0.5px;
            padding: 6px 12px;
            border-radius: 50px;
        }

        /* Ajustes de Responsividade */
        @media (max-width: 576px) {
            .header-usuarios {
                flex-direction: column;
                text-align: center;
                gap: 16px;
            }
            .header-usuarios a.btn-add {
                width: 100%;
                padding: 12px !important;
            }
            .btn-voltar-container {
                text-align: center;
            }
            .search-container {
                max-width: 100%;
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
            <button type="button" class="btn btn-outline-light btn-sm rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#modalLogout">
    Sair
</button>
        </div>
    </nav>
</header>

<main class="container px-3">
    
    <div class="btn-voltar-container">
        <a href="gestor_dashboard.php" class="btn-voltar-link">
            <i class="bi bi-arrow-left"></i> Voltar ao Menu Principal
        </a>
    </div>

    <div class="d-flex justify-content-between align-items-sm-center mb-4 header-usuarios">
        <div>
            <h2 class="fw-bold text-dark m-0">Gestão de Usuários</h2>
            <p class="text-muted small m-0">Visualize e administre as contas cadastradas no sistema</p>
        </div>
        <a href="gestor_adicionar_usuario.php" class="btn text-white rounded-pill px-4 py-2 fw-bold shadow-sm d-inline-flex align-items-center justify-content-center border-0 btn-add" style="background-color: var(--vinho-light);">
            <i class="bi bi-plus-lg me-2"></i> Adicionar Usuário
        </a>
    </div>

    <div class="row mb-4 justify-content-center">
        <div class="col-12 search-container">
            <div class="input-group shadow-sm rounded-pill overflow-hidden">
                <input type="text" id="inputPesquisa" class="form-control search-input" placeholder="Pesquisar usuário por nome..." aria-label="Pesquisar usuário">
                <button class="btn search-btn" type="button" disabled>
                    <i class="bi bi-search"></i>
                </button>
            </div>
        </div>
    </div>

    <div id="mensagemVazia" class="text-center py-5 d-none">
        <i class="bi bi-person-x text-muted" style="font-size: 3rem;"></i>
        <p class="text-muted mt-2">Nenhum usuário encontrado com esse nome.</p>
    </div>

    <div class="row g-4 justify-content-center mb-5" id="gradeUsuarios">
        <?php while($usuario = mysqli_fetch_assoc($result)) { ?>
        <div class="col-12 col-sm-6 col-md-4 col-lg-3 card-usuario-item" data-nome="<?= strtolower(htmlspecialchars($usuario['nome'])) ?>">
            <div class="card h-100 user-card">
                <div class="card-body text-center p-4 d-flex flex-column justify-content-between">
                    <div>
                        <div class="mb-3 mt-2">
                            <div class="avatar-circle mx-auto shadow-sm">
                                <i class="bi bi-person"></i>
                            </div>
                        </div>
                        <h5 class="card-title fw-bold text-dark mb-2 nome-usuario-txt"><?= htmlspecialchars($usuario['nome']) ?></h5>
                        <div class="mb-4">
                            <span class="badge badge-perfil bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25">
                                <?= strtoupper(htmlspecialchars($usuario['perfil'])) ?>
                            </span>
                        </div>
                    </div>
                    
                    <div class="d-grid">
                        <a href="gestor_detalhes_usuarios.php?id=<?= $usuario['id_usuario'] ?>" class="text-decoration-none d-grid">
                            <button type="button" class="btn btn-sm py-2 rounded-pill bg-white text-secondary border border-2 fw-bold shadow-sm w-100">
                                <i class="bi bi-eye me-1"></i> VER DETALHES
                            </button>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <?php } ?>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // Filtro instantâneo sem recarregamento de página
    document.getElementById('inputPesquisa').addEventListener('input', function() {
        const termoBusca = this.value.toLowerCase().trim();
        const cards = document.querySelectorAll('.card-usuario-item');
        const mensagemVazia = document.getElementById('mensagemVazia');
        let encontrouQualquer = false;

        cards.forEach(card => {
            const nomeUsuario = card.getAttribute('data-nome');
            
            if (nomeUsuario.includes(termoBusca)) {
                card.classList.remove('d-none');
                encontrouQualquer = true;
            } else {
                card.classList.add('d-none');
            }
        });

        if (encontrouQualquer) {
            mensagemVazia.classList.add('d-none');
        } else {
            mensagemVazia.classList.remove('d-none');
        }
    });
</script>

<div class="modal fade" id="modalLogout" tabindex="-1" aria-labelledby="modalLogoutLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 400px;">
        <div class="modal-content border-0 shadow" style="border-radius: 15px;">
            <div class="modal-body text-center p-4">
                <div class="text-warning mb-3">
                    <i class="bi bi-exclamation-triangle-fill" style="font-size: 3rem;"></i>
                </div>
                <h5 class="fw-bold text-dark mb-2" id="modalLogoutLabel">Confirmar Saída</h5>
                <p class="text-muted small mb-4">Você tem certeza que deseja encerrar sua sessão atual no SGM?</p>
                <div class="d-flex gap-2 justify-content-center">
                    <button type="button" class="btn btn-light rounded-pill px-4 fw-semibold text-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <a href="api/logout.php" class="btn btn-danger rounded-pill px-4 fw-semibold" style="background-color: #990202; border: none;">Sim, Sair</a>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>