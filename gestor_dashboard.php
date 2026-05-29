<?php
session_start();
require_once 'config/database.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Consulta segura para trazer os dados do usuário logado
$query_user = "SELECT nome, foto FROM usuarios WHERE id_usuario = ?";
$stmt_user = $conn->prepare($query_user);
$stmt_user->bind_param("i", $user_id);
$stmt_user->execute();
$res_user = $stmt_user->get_result()->fetch_assoc();
$stmt_user->close();

$nome_usuario = $res_user['nome'] ?? $_SESSION['user_nome'] ?? 'Gestor';
$tem_foto = !empty($res_user['foto']) && file_exists($res_user['foto']);
$foto_caminho = $tem_foto ? $res_user['foto'] : '';
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>SGM - Dashboard</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<style>
    :root { 
        --vinho-dark: #7a0101; 
        --vinho-light: #990202; 
        --sgm-gold: #ffc107;
    }
    
    body {
        background-color: #f0f2f5;
        font-family: 'Segoe UI', sans-serif;
    }

    .navbar { 
        background: linear-gradient(135deg, var(--vinho-dark) 0%, var(--vinho-light) 100%); 
        border-bottom: 4px solid var(--sgm-gold); 
        height: 65px; 
    }

    /* BOTÃO HAMBURGUER DA NAVBAR */
    .btn-menu-toggle {
        background: transparent;
        border: none;
        color: white;
        font-size: 1.5rem;
        margin-right: 15px;
        transition: 0.2s;
    }
    .btn-menu-toggle:hover {
        color: var(--sgm-gold);
    }

    /* CUSTOMIZAÇÃO DA SIDEBAR RETRÁTIL (OFFCANVAS) */
    .offcanvas {
        background-color: #ffffff;
        border-right: 3px solid var(--sgm-gold) !important;
        width: 300px !important;
    }

    .offcanvas-header {
        background: linear-gradient(135deg, var(--vinho-dark) 0%, var(--vinho-light) 100%);
        color: white;
    }

    /* CONTAINER DA FOTO DENTRO DO MENU */
    .avatar-sidebar-container {
        text-align: center;
        padding: 20px 10px;
        background-color: #fff8f8;
        border-bottom: 1px solid #eee;
        margin-bottom: 15px;
    }

    .avatar-circle-menu { 
        width: 85px; 
        height: 85px; 
        background: var(--vinho-light); 
        color: white; 
        border-radius: 50%; 
        display: flex; 
        align-items: center; 
        justify-content: center; 
        font-size: 2rem; 
        font-weight: bold; 
        border: 3px solid var(--sgm-gold); 
        box-shadow: 0 4px 10px rgba(0,0,0,0.15); 
        overflow: hidden;
        margin: 0 auto 10px;
    }

    .avatar-circle-menu img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    /* LINK EDITAR MEU PERFIL (MUITO MAIS APARENTE) */
    .link-editar-menu {
        display: inline-block;
        margin-top: 5px;
        background-color: var(--vinho-light);
        color: white !important;
        font-weight: 700;
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 6px 16px;
        border-radius: 50px;
        text-decoration: none;
        box-shadow: 0 2px 5px rgba(153,2,2,0.3);
        transition: all 0.2s ease;
    }

    .link-editar-menu:hover {
        background-color: var(--vinho-dark);
        border-color: var(--sgm-gold);
        color: var(--sgm-gold) !important;
        transform: translateY(-1px);
    }

    /* LINKS DE GERENCIAMENTO INTERNOS DO MENU */
    .menu-sidebar-item {
        display: flex;
        align-items: center;
        padding: 14px 20px;
        color: #495057;
        font-weight: 600;
        text-decoration: none;
        border-radius: 10px;
        margin: 4px 10px;
        transition: 0.2s;
    }

    .menu-sidebar-item i {
        font-size: 1.25rem;
        margin-right: 15px;
        color: var(--vinho-light);
    }

    .menu-sidebar-item:hover {
        background-color: #fff0f1;
        color: var(--vinho-light);
    }

    /* CONTAINER DOS BOTÕES CENTRAIS (MANTIDO SEU PADRÃO) */
    .menu-container {
        border-radius: 16px;
        padding: 16px;
        background-color: white;
    }

    .btn-responsive {
        transition: all 0.2s ease;
    }

    @media (max-width: 768px) {
        .btn-responsive {
            width: 100%;
            margin-bottom: 4px;
            text-align: left;
            padding: 12px 20px !important;
        }
        .btn-responsive i { margin-right: 12px !important; }
        .menu-container { gap: 10px !important; }
        .display-mobile-spacing { margin-bottom: 2rem !important; }
    }

    @media (min-width: 769px) {
        .menu-container {
            border-radius: 50px;
            padding: 10px 20px !important;
        }
    }
</style>
</head>

<body>

<header>
    <nav class="navbar navbar-dark shadow-sm mb-4 mb-md-5 px-4">
        <div class="container-fluid d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <button class="btn-menu-toggle" type="button" data-bs-toggle="offcanvas" data-bs-target="#menuLateralAdmin" aria-controls="menuLateralAdmin">
                    <i class="bi bi-list fs-2"></i>
                </button>
                <i class="bi bi-shield-lock-fill text-warning fs-3 me-2"></i>
                <a class="navbar-brand fw-bold mb-0 text-white" href="gestor_dashboard.php">SGM ADMIN</a>
            </div>
            <button type="button" class="btn btn-outline-light btn-sm rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#modalLogout">
                Sair
            </button>            
        </div>
    </nav>
</header>

<div class="offcanvas offcanvas-start" tabindex="-1" id="menuLateralAdmin" aria-labelledby="menuLateralAdminLabel">
    <div class="offcanvas-header shadow-sm">
        <h5 class="offcanvas-title fw-bold" id="menuLateralAdminLabel">
            <i class="bi bi-sliders me-2 text-warning"></i>Painel de Controle
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body p-0">
        
        <div class="avatar-sidebar-container">
            <div class="avatar-circle-menu">
                <?php if ($tem_foto): ?>
                    <img src="<?= htmlspecialchars($foto_caminho) ?>" alt="Foto de Perfil">
                <?php else: ?>
                    <?= strtoupper(substr($nome_usuario, 0, 1)) ?>
                <?php endif; ?>
            </div>
            <h6 class="fw-bold mb-1 text-dark"><?= htmlspecialchars($nome_usuario) ?></h6>
            <span class="badge bg-dark-subtle text-dark-emphasis text-uppercase rounded-pill px-2 py-1 mb-2" style="font-size:0.65rem;">Administrador</span>
            <br>
            <a href="perfil.php" class="link-editar-menu">
                <i class="bi bi-pencil-square me-1"></i>Editar Perfil
            </a>
        </div>

        <div class="py-2">
            <a href="./gestor_chamados.php" class="menu-sidebar-item">
                <i class="bi bi-list-ul"></i> Todos os Chamados
            </a>
            <a href="./gestor_servicos.php" class="menu-sidebar-item">
                <i class="bi bi-wrench-adjustable-circle"></i> Tipos de Serviços
            </a>
            <a href="./gestor_usuarios.php" class="menu-sidebar-item">
                <i class="bi bi-people"></i> Gerenciar Usuários
            </a>
            <a href="./gestor_blocos.php" class="menu-sidebar-item">
                <i class="bi bi-building"></i> Configurar Blocos
            </a>
            <a href="./gestor_ambientes.php" class="menu-sidebar-item">
                <i class="bi bi-geo-alt"></i> Configurar Ambientes
            </a>
        </div>

    </div>
</div>

<main class="container px-3">

    <div class="text-center mb-4 mb-md-5 display-mobile-spacing">
        <h2 class="fw-bold text-dark">Visão Geral</h2>
        <p class="text-muted small mb-0">Status das operações em tempo real</p>
    </div>

    <div class="row justify-content-center g-3">
        <div class="col-12 col-sm-6 col-md-4">
            <div class="card border-0 shadow-sm text-white h-100" style="background-color: #1b7a4d; border-radius: 12px;">
                <div class="card-body d-flex align-items-center p-3">
                    <div class="bg-white bg-opacity-25 rounded-3 p-3 me-3">
                        <i class="bi bi-bell-fill fs-3"></i>
                    </div>
                    <div>
                        <h6 class="text-uppercase mb-0 small opacity-75 fw-bold">Novas</h6>
                        <h2 class="fw-bold m-0" id="count-novos">--</h2>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-md-4">
            <div class="card border-0 shadow-sm text-white h-100" style="background-color: #f1b40e; border-radius: 12px;">
                <div class="card-body d-flex align-items-center p-3">
                    <div class="bg-white bg-opacity-25 rounded-3 p-3 me-3">
                        <i class="bi bi-tools fs-3"></i>
                    </div>
                    <div>
                        <h6 class="text-uppercase mb-0 small opacity-75 fw-bold">Atendimento</h6>
                        <h2 class="fw-bold m-0" id="count-atendimento">--</h2>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-12 col-md-4">
            <div class="card border-0 shadow-sm text-white h-100" style="background-color: #2d3436; border-radius: 12px;">
                <div class="card-body d-flex align-items-center p-3">
                    <div class="bg-white bg-opacity-25 rounded-3 p-3 me-3 text-danger">
                        <i class="bi bi-exclamation-triangle-fill fs-3"></i>
                    </div>
                    <div>
                        <h6 class="text-uppercase mb-0 small opacity-75 fw-bold">Críticos</h6>
                        <h2 class="fw-bold m-0" id="count-criticos">--</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row justify-content-center mt-4 mt-md-5">
        <div class="col-12 col-lg-11">
            <div class="menu-container shadow-sm border d-flex gap-2 flex-wrap justify-content-center align-items-center">
                
                <a href="./gestor_chamados.php" class="btn btn-danger px-4 rounded-pill fw-bold shadow-sm btn-responsive" style="background-color: #990202; border: none;">
                    <i class="bi bi-list-ul me-2"></i> Gerenciar todos os chamados
                </a>
                
                <a href="#" class="btn btn-light px-4 rounded-pill fw-bold text-secondary border btn-responsive" data-bs-toggle="modal" data-bs-target="#exampleModal">
                    <i class="bi bi-geo-alt me-2"></i> Gerenciar locais
                </a>
                
                <a href="./gestor_servicos.php" class="btn btn-danger px-4 rounded-pill fw-bold shadow-sm btn-responsive" style="background-color: #990202; border: none;">
                    <i class="bi bi-wrench-adjustable-circle-fill me-2"></i> Gerenciar tipos de serviços
                </a>
                
                <a href="./gestor_usuarios.php" class="btn btn-light px-4 rounded-pill fw-bold text-secondary border btn-responsive">
                    <i class="bi bi-people me-2"></i> Gerenciar usuários
                </a>
                
            </div>
        </div>
    </div>

</main>

<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm modal-dialog-centered px-3">
    <div class="modal-content border-0 shadow rounded-4">
      <div class="modal-header border-0 bg-light rounded-top-4">
        <h1 class="modal-title fs-6 fw-bold text-dark" id="exampleModalLabel"><i class="bi bi-geo-alt-fill text-danger me-1"></i> Gerenciar Locais</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body text-center py-4">
        <p class="text-muted small">Qual categoria você deseja modificar?</p>
        <div class="d-grid gap-2">
            <a href="./gestor_blocos.php" class="btn text-white fw-bold py-2 rounded-3" style="background-color: #990202;">Blocos</a>
            <a href="./gestor_ambientes.php" class="btn text-white fw-bold py-2 rounded-3" style="background-color: #990202;">Ambientes</a>
        </div>
      </div>
    </div>
  </div>
</div>

<footer class="text-center mt-5 py-4 text-muted small">
    <p class="mb-0">&copy; 2026 SGM Gestão de Manutenção - Luma Massaranduba</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
async function carregarIndicadores() {
    try {
        const response = await fetch('api/dashboard_stats.php');
        const resultado = await response.json();

        if (resultado.success) {
            document.getElementById('count-novos').innerText = resultado.dados.novos;
            document.getElementById('count-atendimento').innerText = resultado.dados.atendimento;
            document.getElementById('count-criticos').innerText = resultado.dados.criticos;
        }
    } catch (error) {
        console.error("Erro ao buscar indicadores:", error);
        document.getElementById('count-novos').innerText = '0';
        document.getElementById('count-atendimento').innerText = '0';
        document.getElementById('count-criticos').innerText = '0';
    }
}

document.addEventListener('DOMContentLoaded', carregarIndicadores);
setInterval(carregarIndicadores, 60000);
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