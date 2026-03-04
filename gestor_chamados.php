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
</head>

<body class="bg-light" style="font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;">

<nav class="navbar navbar-expand-lg shadow-sm mb-5" style="background-color: #990202;">
    <div class="container py-1">
        <a href="gestor_dashboard.php" class="btn btn-link text-light text-decoration-none me-2">
            <i class="bi bi-arrow-left-circle-fill fs-4"></i>
        </a>
        <a class="navbar-brand text-light fw-bold" href="gestor_dashboard.php">SGM Admin</a>
        
        <div class="navbar-nav ms-auto gap-2">
            <a class="nav-link px-3 rounded-pill text-light bg-white bg-opacity-10" href="gestor_chamados.php">Chamados</a>
            <a class="nav-link px-3 text-light" href="./gestor_dashboard.php">Home</a>
            <a href="api/logout.php" class="btn btn-outline-light btn-sm ms-2 rounded-pill px-3">Sair</a>
        </div>
    </div>
</nav>

<div class="container">
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
</div>

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
    const res = await fetch(`api/gestor_chamados.php?status=${status}`);
    const chamados = await res.json();
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

carregarChamados();
</script>
</body>
</html>