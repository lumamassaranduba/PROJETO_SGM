<?php
session_start();
require_once 'config/database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_perfil'] !== 'tecnico') {
    header("Location: login.php");
    exit;
}

$aba = isset($_GET['aba']) ? $_GET['aba'] : 'pendentes';
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SGM - Painel do Técnico</title>
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
            overflow-x: hidden; 
        }
        
        /* Navbar Padrão SGM TÉCNICO */
        .navbar { 
            background: linear-gradient(135deg, var(--vinho-dark) 0%, var(--vinho-light) 100%); 
            border-bottom: 4px solid var(--sgm-gold); 
            height: 65px;
        }
        
        /* Sidebar Adaptável (Desktop vs Mobile) */
        .card-menu { 
            border: none; 
            background: white; 
        }
        
        .perfil-section { 
            background: #fff4f4; 
            padding: 20px; 
            text-align: center; 
            border-bottom: 1px solid #dee2e6; 
        }
        
        .avatar-circle { 
            width: 55px; 
            height: 55px; 
            background: var(--vinho-light); 
            color: white; 
            border-radius: 50%; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            margin: 0 auto 8px; 
            font-size: 1.4rem; 
            font-weight: bold; 
            border: 3px solid white; 
            box-shadow: 0 3px 6px rgba(0,0,0,0.08); 
        }
        
        /* Links de Navegação com Toque Ergonômico */
        .nav-pills .nav-link { 
            color: #555; 
            font-weight: 600; 
            border-radius: 12px; 
            margin: 5px 10px; 
            padding: 12px 16px; 
            transition: 0.2s; 
            text-decoration: none; 
            display: flex;
            align-items: center;
        }
        .nav-pills .nav-link.active { 
            background-color: var(--vinho-light); 
            color: white; 
        }
        .nav-pills .nav-link:hover:not(.active) { 
            background-color: #f8f9fa; 
            color: var(--vinho-light); 
        }
        
        /* Cards de Chamados Mobile-First */
        .card-chamado { 
            border: none; 
            border-radius: 16px; 
            transition: all 0.2s ease; 
            border-left: 6px solid #dee2e6; 
            background: white; 
            box-shadow: 0 4px 10px rgba(0,0,0,0.03) !important;
        }
        
        /* Mudança de cor da borda baseado na prioridade */
        .border-prio-urgente { border-left-color: #dc3545 !important; }
        .border-prio-alta { border-left-color: #fd7e14 !important; }
        .border-prio-media { border-left-color: #0d6efd !important; }
        .border-prio-baixa { border-left-color: #17a2b8 !important; }
        
        .status-badge { 
            padding: 6px 12px; 
            border-radius: 50px; 
            font-size: 0.7rem; 
            font-weight: 700; 
            text-transform: uppercase; 
            letter-spacing: 0.5px;
        }
        
        .prio-urgente { background-color: rgba(220, 53, 69, 0.1); color: #dc3545; }
        .prio-alta { background-color: rgba(253, 126, 20, 0.1); color: #fd7e14; }
        .prio-media { background-color: rgba(13, 110, 253, 0.1); color: #0d6efd; }
        .prio-baixa { background-color: rgba(23, 162, 184, 0.1); color: #17a2b8; }
        
        .status-em-execucao { background-color: #fff3cd; color: #856404; border: 1px solid #ffeeba; }
        .status-aberto { background-color: #e2e3e5; color: #383d41; border: 1px solid #d6d8db; }
        
        .icon-circle { 
            width: 36px; 
            height: 36px; 
            background: #fff4f4; 
            color: var(--vinho-light); 
            border-radius: 50%; 
            display: inline-flex; 
            align-items: center; 
            justify-content: center; 
        }
        
        .btn-atender { 
            background-color: var(--vinho-light); 
            color: white; 
            border-radius: 50px; 
            font-weight: 700; 
            border: none; 
            padding: 12px; 
            font-size: 0.9rem;
            transition: 0.2s;
        }
        .btn-atender:hover { 
            background-color: var(--vinho-dark); 
            color: white; 
        }

        .filtro-wrapper { 
            background: white; 
            border-radius: 16px; 
            padding: 16px; 
            margin-bottom: 20px; 
            box-shadow: 0 4px 10px rgba(0,0,0,0.02); 
            border-top: 4px solid var(--sgm-gold);
        }

        /* customização responsiva para Mobile */
        @media (max-width: 767.98px) {
            .card-menu {
                min-height: auto !important;
                border-radius: 0 0 16px 16px;
                margin-bottom: 15px;
                box-shadow: 0 4px 10px rgba(0,0,0,0.05);
            }
            .sidebar-col {
                padding-right: calc(var(--bs-gutter-x) * .5) !important;
            }
            .perfil-section {
                display: flex;
                align-items: center;
                text-align: left;
                padding: 12px 15px;
            }
            .avatar-circle {
                margin: 0 12px 0 0;
                width: 45px;
                height: 45px;
                font-size: 1.1rem;
            }
            .nav-tabs-mobile {
                display: flex !important;
                flex-direction: row !important;
                justify-content: space-around;
                padding: 5px !important;
            }
            .nav-tabs-mobile .nav-link {
                margin: 0 !important;
                flex: 1;
                justify-content: center;
                font-size: 0.85rem;
                padding: 10px 5px;
                border-radius: 8px;
            }
            .contador-box {
                border-top: none !important;
                border-left: 1px solid #dee2e6;
                padding: 0 15px !important;
                display: flex;
                flex-direction: column;
                justify-content: center;
                margin-left: auto;
            }
            .contador-box small { font-size: 0.6rem; }
            .contador-box span { font-size: 1.2rem !important; }
        }

        @media (min-width: 768px) {
            .card-menu {
                min-height: calc(100vh - 65px);
                border-radius: 0 20px 20px 0;
            }
            .sidebar-col {
                padding-right: 0;
            }
        }
    </style>
</head>
<body>

<nav class="navbar navbar-dark shadow-sm px-3">
    <div class="container-fluid d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center">
            <i class="bi bi-tools text-warning fs-4 me-2"></i>
            <span class="navbar-brand fw-bold mb-0 fs-5">SGM TÉCNICO</span>
        </div>
        <a href="api/logout.php" class="btn btn-sm btn-outline-light rounded-pill px-3 fw-semibold">Sair</a>
    </div>
</nav>

<div class="container-fluid p-0">
    <div class="row g-0">
        <div class="col-12 col-md-3 col-xl-2 sidebar-col">
            <div class="card card-menu shadow-sm">
                <div class="perfil-section">
                    <div class="avatar-circle">
                        <?= strtoupper(substr($_SESSION['user_nome'] ?? 'T', 0, 1)) ?>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-0 text-dark small-mobile-text"><?= $_SESSION['user_nome'] ?? 'Técnico' ?></h6>
                        <small class="text-muted text-uppercase d-block" style="font-size: 0.65rem; letter-spacing: 0.5px;">
                            <i class="bi bi-shield-check text-success"></i> Autorizado
                        </small>
                    </div>
                    
                    <div class="contador-box p-3 bg-light text-center border-top mt-2 d-none d-md-block">
                        <small class="text-muted d-block mb-1">Chamados listados:</small>
                        <span id="contador" class="h4 fw-bold text-dark">0</span>
                    </div>
                </div>

                <div class="nav flex-column nav-pills py-2 nav-tabs-mobile">
                    <a href="?aba=pendentes" class="nav-link <?= $aba === 'pendentes' ? 'active' : '' ?>">
                        <i class="bi bi-list-task me-1 me-md-2"></i> Pendentes
                    </a>
                    <a href="?aba=concluidos" class="nav-link <?= $aba === 'concluidos' ? 'active' : '' ?>">
                        <i class="bi bi-check-circle-fill me-1 me-md-2"></i> Concluídos
                    </a>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-9 col-xl-10 p-3 p-md-4">
            
            <div class="filtro-wrapper">
                <div class="row g-2 align-items-center">
                    <div class="col-12 col-lg-3 text-center text-lg-start">
                        <h5 class="fw-bold mb-0 text-dark fs-5">
                            <?= $aba === 'pendentes' ? 'Tarefas Pendentes' : 'Histórico Concluído' ?>
                        </h5>
                    </div>
                    <div class="col-6 col-lg-3">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-light text-muted d-none d-sm-flex"><i class="bi bi-exclamation-triangle"></i></span>
                            <select id="filtroImportancia" class="form-select py-2" onchange="filtrarEExibir()">
                                <option value="">Importâncias</option>
                                <option value="urgente">Urgente</option>
                                <option value="alta">Alta</option>
                                <option value="media">Média</option>
                                <option value="baixa">Baixa</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-6 col-lg-3">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-light text-muted d-none d-sm-flex"><i class="bi bi-calendar-event"></i></span>
                            <input type="date" id="filtroData" class="form-control py-2" onchange="filtrarEExibir()">
                        </div>
                    </div>
                    <div class="col-12 col-lg-3 text-center text-lg-end mt-2 mt-lg-0">
                        <button onclick="limparFiltros()" class="btn btn-sm btn-outline-secondary rounded-pill w-100 w-lg-auto px-3 py-2">
                            <i class="bi bi-arrow-clockwise"></i> Limpar Filtros
                        </button>
                    </div>
                </div>
            </div>

            <div id="listaChamados" class="row g-3">
                 </div>
        </div>
    </div>
</div>

<script>
const ABA_ATUAL = '<?= $aba ?>';
let cacheChamados = []; 

async function carregarTarefas() {
    try {
        const res = await fetch(`api/chamados.php`);
        const todosChamados = await res.json();
        
        cacheChamados = todosChamados.filter(c => {
            const st = (c.status || 'aberto').toLowerCase().trim();
            if (ABA_ATUAL === 'pendentes') {
                return st !== 'concluido' && st !== 'fechado';
            } else {
                return st === 'concluido' || st === 'fechado';
            }
        });

        filtrarEExibir();
    } catch (e) { 
        console.error("Erro ao carregar os chamados:", e); 
    }
}

function filtrarEExibir() {
    const lista = document.getElementById('listaChamados');
    const impSelecionada = document.getElementById('filtroImportancia').value.toLowerCase();
    const dataSelecionada = document.getElementById('filtroData').value;

    let chamadosFiltrados = cacheChamados.filter(c => {
        const prioridadeChamado = (c.prioridade || 'media').toLowerCase().trim();
        
        let dataLimiteChamado = '';
        if (c.data_previsao_conclusao) {
            dataLimiteChamado = c.data_previsao_conclusao.split(' ')[0].trim();
        }

        const bateImportancia = impSelecionada === "" || prioridadeChamado === impSelecionada;
        const bateData = dataSelecionada === "" || dataLimiteChamado === dataSelecionada;

        return bateImportancia && bateData;
    });

    const numContador = document.getElementById('contador');
    if(numContador) numContador.innerText = chamadosFiltrados.length;

    if (chamadosFiltrados.length === 0) {
        lista.innerHTML = `
            <div class="col-12 text-center mt-2 py-5 bg-white rounded-4 shadow-sm px-3">
                <i class="bi bi-funnel text-muted mb-2" style="font-size: 2.5rem;"></i>
                <p class="text-muted small m-0 fw-bold">Nenhum chamado corresponde aos filtros aplicados.</p>
            </div>`;
        return;
    }

    lista.innerHTML = chamadosFiltrados.map(c => {
        const prioridadeLimpa = (c.prioridade || 'media').toLowerCase().trim();
        
        let dataFormatada = "Não definida";
        if(c.data_previsao_conclusao) {
            const partes = c.data_previsao_conclusao.split(' ')[0].split('-');
            if(partes.length === 3) dataFormatada = `${partes[2]}/${partes[1]}/${partes[0]}`;
        }

        const statusLimpo = (c.status || 'aberto').toLowerCase().trim();
        let badgeStatusClasse = 'status-aberto';
        if (statusLimpo === 'em_execucao') badgeStatusClasse = 'status-em-execucao';
        else if (statusLimpo === 'concluido' || statusLimpo === 'fechado') badgeStatusClasse = 'bg-success text-white';

        const textoBotao = ABA_ATUAL === 'pendentes' ? `Atender #${c.id_chamado}` : `Ver Detalhes`;
        const btnClasse = ABA_ATUAL === 'pendentes' ? `btn-atender` : `btn-outline-secondary rounded-pill py-2`;

        return `
        <div class="col-12 col-md-6 col-xxl-4">
            <div class="card card-chamado border-prio-${prioridadeLimpa} h-100">
                <div class="card-body d-flex flex-column p-3">
                    <div class="d-flex justify-content-between mb-3 align-items-center">
                        <span class="status-badge ${badgeStatusClasse}">${(c.status || 'aberto').replace('_', ' ')}</span>
                        <span class="status-badge prio-${prioridadeLimpa}"><i class="bi bi-exclamation-circle me-1"></i>${c.prioridade || 'Média'}</span>
                    </div>
                    
                    <div class="d-flex align-items-center mb-2">
                        <div class="icon-circle me-2 flex-shrink-0"><i class="bi bi-geo-alt-fill"></i></div>
                        <div class="overflow-hidden">
                            <h6 class="fw-bold mb-0 text-dark text-truncate small" style="font-size:0.95rem;">${c.bloco_nome || 'Geral'}</h6>
                            <small class="text-muted d-block text-truncate">${c.ambiente_nome || ''}</small>
                        </div>
                    </div>

                    <div class="bg-light p-2 rounded-3 mb-3 flex-grow-1 border-start border-3 border-secondary">
                        <p class="small mb-0 text-dark text-break" style="display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden; line-height:1.4;">
                            ${c.descricao_problema}
                        </p>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-3 px-1">
                        <span class="text-muted style-text" style="font-size:0.8rem;"><i class="bi bi-clock-history me-1"></i>Prazo Limite:</span>
                        <span class="badge bg-light text-dark border fw-bold px-2 py-1">${dataFormatada}</span>
                    </div>

                    <button onclick="location.href='tecnico_minhas_tarefas.php?id=${c.id_chamado}'" class="btn ${btnClasse} w-100 shadow-sm">
                        ${textoBotao}
                    </button>
                </div>
            </div>
        </div>`;
    }).join('');
}

function limparFiltros() {
    document.getElementById('filtroImportancia').value = "";
    document.getElementById('filtroData').value = "";
    filtrarEExibir();
}

carregarTarefas();
</script>
</body>
</html>