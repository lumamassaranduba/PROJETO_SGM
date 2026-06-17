<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_perfil'] !== 'gestor') {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title>SGM - Gestão de Chamados</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

<style>
    /* Identidade Visual Padronizada SGM */
    :root {
        --vinho-dark: #7a0101;
        --vinho-light: #990202;
        --sgm-gold: #ffc107;
    }
    
    /* Navbar Padrão SGM ADMIN Exata da Foto */
    .navbar-sgm { 
        background-color: var(--vinho-light); 
        border-bottom: 4px solid var(--sgm-gold); 
        height: 65px; 
    }

    /* Estilização do Botão Voltar igual ao padrão da foto */
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
    }
</style>
</head>

<body class="bg-light" style="font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;">

<header>
    <nav class="navbar navbar-sgm shadow-sm mb-4 px-4">
        <div class="container-fluid d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <i class="bi bi-shield-lock-fill text-warning fs-3 me-2"></i>
                <a class="navbar-brand fw-bold mb-0 text-white" href="gestor_dashboard.php" style="letter-spacing: 0.5px;">SGM ADMIN</a>
            </div>
            <button type="button" class="btn btn-outline-light btn-sm rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#modalLogout">
                Sair
            </button>
        </div>
    </nav>
</header>

<main class="container px-3">
    <div class="mb-4">
        <a href="gestor_dashboard.php" class="btn btn-voltar-link">
            <i class="bi bi-arrow-left"></i> Voltar ao Menu Principal
        </a>
    </div>

    <div class="row align-items-center mb-4">
        <div class="col">
            <h2 class="fw-bold text-dark m-0">Todos os Chamados</h2>
            <p class="text-muted small">Gerencie e monitore o fluxo de manutenções</p>
        </div>
        <div class="col-auto">
            <div class="bg-white p-1 rounded-pill shadow-sm d-flex gap-1">
                <button class="btn btn-sm rounded-pill px-3 btn-dark" onclick="carregarChamados('')">Todos</button>
                <button class="btn btn-sm rounded-pill px-3 btn-outline-primary border-0" onclick="carregarChamados('aberto')">Abertos</button>
                <button class="btn btn-sm rounded-pill px-3 btn-outline-warning border-0" onclick="carregarChamados('em_execucao')">Execução</button>
                <button class="btn btn-sm rounded-pill px-3 btn-outline-success border-0" onclick="carregarChamados('concluido')">Concluídos</button>
                <button class="btn btn-sm rounded-pill px-3 btn-outline-secondary border-0" onclick="carregarChamados('fechado')">Fechados</button>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-white">
                    <tr class="text-muted small" style="border-bottom: 2px solid #f8f9fa;">
                        <th class="ps-4 py-3">ID</th>
                        <th>SOLICITANTE</th>
                        <th>LOCAL / TIPO</th>
                        <th>PRIORIDADE</th>
                        <th>TÉCNICO</th>
                        <th>STATUS</th>
                        <th class="text-end pe-4">AÇÕES</th>
                    </tr>
                </thead>
                <tbody id="tabelaGeral" class="border-top-0">
                </tbody>
            </table>
        </div>
    </div>
</main>

<div class="modal fade" id="modalFoto" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-body p-0 text-center bg-dark rounded-top overflow-hidden">
                <img src="" id="imgModal" class="img-fluid">
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-light rounded-pill px-4 shadow-sm" data-bs-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
const coresPrioridade = { 
    'urgente': 'text-danger', 
    'alta': 'text-warning', 
    'media': 'text-primary', 
    'baixa': 'text-secondary' 
};

const coresStatus = { 
    'aberto': 'bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25', 
    'em_execucao': 'bg-warning bg-opacity-10 text-dark border border-warning border-opacity-25', 
    'concluido': 'bg-success bg-opacity-10 text-success border border-success border-opacity-25', 
    'fechado': 'bg-dark bg-opacity-10 text-dark border border-dark border-opacity-25' 
};

async function carregarChamados(status = '') {
    // Se o filtro ativo for 'fechado', dizemos para a API trazer tudo para filtrarmos localmente no JS
    const statusFiltroApi = (status === 'fechado') ? '' : status;
    
    const res = await fetch(`api/gestor_chamados.php?status=${statusFiltroApi}`);
    let chamados = await res.json();
    
    // Regras de Filtragem no Front-end:
    if (status === '') {
        // Na aba "Todos", exibe os ativos e concluídos, mas oculta os arquivados (fechado)
        chamados = chamados.filter(c => c.status !== 'fechado');
    } else if (status === 'fechado') {
        // Na aba "Fechados", filtra para exibir unicamente quem tem o status 'fechado'
        chamados = chamados.filter(c => c.status === 'fechado');
    }
    
    // ORDENAÇÃO POR ID DESC: Organiza do maior ID para o menor
    chamados.sort((a, b) => parseInt(b.id_chamado) - parseInt(a.id_chamado));
    
    const body = document.getElementById('tabelaGeral');

    body.innerHTML = chamados.map(c => `
        <tr style="border-bottom: 1px solid #f8f9fa;">
            <td class="ps-4 text-muted fw-medium small">#${c.id_chamado}</td>
            <td class="fw-bold text-dark">${c.solicitante_nome}</td>
            <td>
                <div class="d-flex flex-column">
                    <span class="small text-muted mb-0">${c.bloco_nome}</span>
                    <span class="fw-semibold" style="color: #444;">${c.ambiente_nome}</span>
                </div>
            </td>
            <td>
                <span class="small fw-bold ${coresPrioridade[c.prioridade]}">
                    <i class="bi bi-circle-fill me-1" style="font-size: 8px;"></i> ${c.prioridade.toUpperCase()}
                </span>
            </td>
            <td>
                <span class="${!c.tecnico_nome ? 'fst-italic text-muted small' : 'text-dark fw-medium'}">
                    ${c.tecnico_nome || 'Aguardando...'}
                </span>
            </td>
            <td>
                <span class="badge rounded-pill px-3 py-2 ${coresStatus[c.status]}" style="font-size: 10px; letter-spacing: 0.5px;">
                    ${c.status.replace('_', ' ').toUpperCase()}
                </span>
            </td>
            <td class="text-end pe-4">
                <a href="gestor_detalhes.php?id=${c.id_chamado}" class="btn btn-sm px-3 rounded-pill text-white shadow-sm" style="background-color: #990202; font-size: 12px; font-weight: 600;">
                    <i class="bi bi-gear-fill me-1"></i> GERENCIAR
                </a>
            </td>
        </tr>
    `).join('');
}

// Inicializa a tela carregando a listagem padrão
carregarChamados();
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