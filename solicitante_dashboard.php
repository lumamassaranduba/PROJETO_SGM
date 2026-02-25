<?php
session_start();
if(!isset($_SESSION['user_id']) || $_SESSION['user_perfil'] !== 'solicitante'){
    header("Location:login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SGM - Painel do Solicitante</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body { background-color: #f8f9fa; font-family: 'Inter', system-ui, sans-serif; }
        /* Navbar seguindo o padrão sólido das imagens */
        .navbar-custom { background-color: #990202; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        /* Estilo para a tabela e container */
        .main-card { border: none; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); background: white; }
        .table thead { background-color: #fcfcfc; }
        .table th { font-weight: 600; color: #495057; border-bottom: 2px solid #f1f1f1; }
        .badge-status { font-weight: 600; padding: 0.5em 0.8em; border-radius: 6px; text-transform: uppercase; font-size: 0.75rem; }
        .img-thumb-preview { width: 45px; height: 45px; object-fit: cover; border-radius: 6px; transition: transform 0.2s; border: 1px solid #eee; }
        .img-thumb-preview:hover { transform: scale(1.1); cursor: pointer; }
    </style>
</head>

<body>

<header>
    <nav class="navbar navbar-custom py-2 mb-4">
        <div class="container">
            <span class="navbar-brand text-white fw-bold">
                SMG | Painel do Solicitante
            </span>
            <div class="d-flex align-items-center">
                <a href="api/logout.php" class="btn btn-outline-light btn-sm rounded-pill px-3">
                    <i class="bi bi-box-arrow-right me-1"></i> Sair
                </a>
            </div>
        </div>
    </nav>
</header>

<main class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-dark m-0">Minha fila de tarefas</h2>
        <a href="solicitante_abrir_chamado.php" class="btn text-white rounded-pill px-4 fw-bold shadow-sm" style="background-color: #990202;">
            <i class="bi bi-plus-lg me-1"></i> Nova Solicitação
        </a>
    </div>

    <div class="main-card p-3">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="tabelaChamados">
                <thead>
                    <tr>
                        <th class="ps-3">ID</th>
                        <th>Foto</th>
                        <th>Local</th>
                        <th>Descrição</th>
                        <th>Data</th>
                        <th class="text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="border-top-0">
                    </tbody>
            </table>
        </div>
    </div>
</main>

<div class="modal fade" id="modalFoto" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold text-dark">Evidência anexada</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center p-4">
                <img id="imgModal" src="./uploads" class="img-fluid rounded-3 shadow-sm">
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
function verFoto(url) {
    document.getElementById('imgModal').src = url;
    new bootstrap.Modal(document.getElementById('modalFoto')).show();
}

async function carregarChamados() {
    try {
        const response = await fetch('api/chamados.php');
        const chamados = await response.json();
        const tbody = document.querySelector('#tabelaChamados tbody');

        const cores = {
            'aberto': 'bg-secondary bg-opacity-75',
            'em_execucao': 'bg-warning text-dark',
            'concluido': 'bg-success',
            'fechado': 'bg-dark'
        };

        let linhas = '';

        for (let c of chamados) {
            const anexosResponse = await fetch(`api/anexos.php?id_chamado=${c.id_chamado}`);
            const anexos = await anexosResponse.json();

            const thumbHtml = anexos.length > 0
                ? `<img src="${anexos[0].caminho_arquivo}" class="img-thumb-preview shadow-sm" onclick="verFoto('${anexos[0].caminho_arquivo}')">`
                : '<div class="bg-light rounded text-center d-flex align-items-center justify-content-center" style="width:45px; height:45px;"><i class="bi bi-image text-muted opacity-50"></i></div>';

            linhas += `
                <tr>
                    <td class="ps-3 text-muted fw-bold">#${c.id_chamado}</td>
                    <td>${thumbHtml}</td>
                    <td>
                        <div class="fw-bold text-dark">${c.bloco_nome}</div>
                        <div class="small text-muted">${c.ambiente || 'Ambiente não definido'}</div>
                    </td>
                    <td class="text-truncate" style="max-width: 250px;">
                        ${c.descricao_problema}
                    </td>
                    <td class="text-muted small">
                        ${new Date(c.data_abertura).toLocaleDateString('pt-BR')}
                    </td>
                    <td class="text-center">
                        <span class="badge badge-status ${cores[c.status] || 'bg-secondary'}">
                            ${c.status.replace('_', ' ')}
                        </span>
                    </td>
                </tr>
            `;
        }
        tbody.innerHTML = linhas;
    } catch (erro) {
        console.error("Erro ao carregar chamados:", erro);
    }
}

carregarChamados();
</script>

</body>
</html>