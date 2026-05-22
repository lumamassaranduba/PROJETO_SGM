<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>SGM - Dashboard</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<style>
    /* Identidade Visual Padronizada */
    :root { 
        --vinho-dark: #7a0101; 
        --vinho-light: #990202; 
    }
    
    body {
        background-color: #f0f2f5;
        font-family: 'Segoe UI', sans-serif;
    }

    .navbar { 
        background: linear-gradient(135deg, var(--vinho-dark) 0%, var(--vinho-light) 100%); 
        border-bottom: 4px solid #ffc107; 
        height: 65px; 
    }

    /* CONTAINER DOS BOTÕES RESPONSIVO */
    .menu-container {
        border-radius: 16px; /* Formato de cartão no celular */
        padding: 16px;
        background-color: white;
    }

    /* BOTÕES ADAPTÁVEIS */
    .btn-responsive {
        transition: all 0.2s ease;
    }

    /* Ajustes específicos para telas de celulares e tablets */
    @media (max-width: 768px) {
        .btn-responsive {
            width: 100%;
            margin-bottom: 4px;
            text-align: left; /* Alinhado à esquerda no celular fica estilo "lista de app" */
            padding: 12px 20px !important;
        }
        .btn-responsive i {
            margin-right: 12px !important;
        }
        .menu-container {
            border-radius: 16px !important; /* Força o formato de bloco plano no celular */
            gap: 10px !important;
        }
        .display-mobile-spacing {
            margin-bottom: 2rem !important;
        }
    }

    /* Ajustes para computadores */
    @media (min-width: 769px) {
        .menu-container {
            border-radius: 50px; /* Vira cápsula somente no computador */
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
                <i class="bi bi-shield-lock-fill text-warning fs-3 me-2"></i>
                <a class="navbar-brand fw-bold mb-0 text-white" href="gestor_dashboard.php">SGM ADMIN</a>
            </div>
            
            <a href="api/logout.php" class="btn btn-outline-light btn-sm rounded-pill px-3">Sair</a>
        </div>
    </nav>
</header>

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
    <p class="mb-0">&copy; 2026 SMG Gestão de Manutenção - Luma Massaranduba</p>
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

</body>
</html>