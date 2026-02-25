<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_perfil'] !== 'gestor') {
    header("Location: login.php"); exit;
}
$id = $_GET['id'] ?? 0;
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>SGM - Detalhes do Chamado</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        .thumb-img { width: 100%; height: 80px; cursor: pointer; object-fit: cover; border-radius: 8px; transition: 0.3s; border: 2px solid #f8f9fa; }
        .thumb-img:hover { transform: scale(1.05); border-color: #990202; }
        .info-label { font-size: 0.75rem; text-uppercase; font-weight: 700; color: #6c757d; letter-spacing: 0.5px; }
        .info-value { font-weight: 600; color: #2d3436; margin-bottom: 0.5rem; }
        .card { border: none; border-radius: 15px; }
    </style>
</head>
<body class="bg-body-tertiary">

<nav class="navbar navbar-expand-lg shadow-sm mb-5" style="background-color: #990202;">
    <div class="container py-1">
        <a href="gestor_chamados.php" class="btn btn-link text-light text-decoration-none me-2">
            <i class="bi bi-arrow-left-circle-fill fs-4"></i>
        </a>
        <a class="navbar-brand text-light fw-bold" href="gestor_dashboard.php">SGM Admin</a>
        
        <div class="navbar-nav ms-auto gap-2">
            <a class="nav-link px-3 rounded-pill text-light bg-white bg-opacity-10" href="gestor_chamados.php">Chamados</a>
            <a class="nav-link px-3 text-light" href="gestor_locais.php">Locais</a>
            <a href="api/logout.php" class="btn btn-outline-light btn-sm ms-2 rounded-pill px-3">Sair</a>
        </div>
    </div>
</nav>

<div class="container pb-5">
    <div class="row g-4">
        <div class="col-md-7">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 fw-bold text-light p-3" style="background-color: #990202;">Dados da Solicitação</h6>
                </div>
                <div id="detalhesChamado" class="card-body">
                    <div class="text-center p-4">Carregando informações...</div>
                </div>
            </div>
            <div id="areaFechamento"></div>
        </div>

        <div class="col-md-5">
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 fw-bold text-light p-3" style="background-color: #990202;">Triagem e Atribuição</h6>
                </div>
                <div class="card-body">
                    <form id="formAtribuir">
                        <input type="hidden" id="id_chamado" value="<?= $id ?>">
                        <div class="mb-3">
                            <label class="info-label">Técnico Responsável</label>
                            <select id="selectTecnico" class="form-select border-light-subtle" required></select>
                        </div>
                        <div class="row g-2">
                            <div class="col-6 mb-3">
                                <label class="info-label">Prioridade</label>
                                <select id="prioridade" class="form-select border-light-subtle">
                                    <option value="baixa">Baixa</option>
                                    <option value="media">Média</option>
                                    <option value="alta">Alta</option>
                                    <option value="urgente">Urgente</option>
                                </select>
                            </div>
                            <div class="col-6 mb-3">
                                <label class="info-label">Data Prevista</label>
                                <input type="date" id="data_prevista" class="form-control border-light-subtle" required>
                            </div>
                        </div>
                        <button type="submit" class="btn w-100 text-light rounded-pill fw-bold" style="background-color: #990202;">
                            Confirmar Atribuição
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalFoto" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content bg-dark border-0 overflow-hidden rounded-4">
            <div class="modal-body p-0 text-center">
                <img src="" id="imgModal" class="img-fluid">
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-outline-light rounded-pill px-4" data-bs-dismiss="modal">Fechar</button>
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

    async function carregarDados() {
        // Carrega Técnicos
        const resTec = await fetch('api/usuarios.php');
        const tecnicos = await resTec.json();
        const select = document.getElementById('selectTecnico');
        select.innerHTML = '<option value="">Selecione um técnico...</option>';
        tecnicos.forEach(t => select.innerHTML += `<option value="${t.id_usuario}">${t.nome}</option>`);

        // Carrega Chamado
        const c = await (await fetch(`api/chamados.php?id=<?= $id ?>`)).json();
        
        const statusClass = {
            'aberto': 'bg-danger text-white',
            'em_execucao': 'bg-warning text-dark',
            'concluido': 'bg-success text-white',
            'fechado': 'bg-secondary text-white'
        };

        document.getElementById('detalhesChamado').innerHTML = `
            <div class="row">
                <div class="col-12 mb-3">
                    <span class="badge rounded-pill ${statusClass[c.status] || 'bg-secondary'}">${c.status.toUpperCase()}</span>
                </div>
                <div class="col-md-6">
                    <div class="info-label">Local</div>
                    <div class="info-value">${c.bloco_nome} - ${c.ambiente_nome}</div>
                </div>
                <div class="col-md-6">
                    <div class="info-label">Solicitante</div>
                    <div class="info-value">${c.solicitante_nome}</div>
                </div>
                <div class="col-12">
                    <div class="info-label">Descrição</div>
                    <div class="info-value text-dark bg-light p-3 rounded-3" style="font-weight: 400;">${c.descricao_problema}</div>
                </div>
                <div class="col-12 mt-2">
                    <div class="info-label">Abertura</div>
                    <div class="info-value small text-muted">${new Date(c.data_abertura).toLocaleString()}</div>
                </div>
                <div id="fotosContainer" class="col-12 mt-3"></div>
            </div>
        `;

        if(c.id_tecnico) document.getElementById('selectTecnico').value = c.id_tecnico;
        if(c.prioridade) document.getElementById('prioridade').value = c.prioridade;
        if(c.data_previsao_conclusao) document.getElementById('data_prevista').value = c.data_previsao_conclusao;

        // Carrega Fotos
        const anexos = await (await fetch(`api/anexos.php?id_chamado=<?= $id ?>`)).json();
        if(anexos.length > 0) {
            let htmlFotos = '<div class="info-label mb-2">Evidências:</div><div class="row g-2">';
            anexos.forEach(arq => {
                const path = `./assets/uploads/${arq.caminho_arquivo}`;
                htmlFotos += `
                    <div class="col-3 text-center mb-2">
                        <img src="${path}" class="thumb-img" onclick="verFoto('${path}')">
                        <div class="text-muted" style="font-size: 10px;">${arq.tipo_anexo === 'abertura' ? 'ABERTURA' : 'FECHAMENTO'}</div>
                    </div>`;
            });
            document.getElementById('fotosContainer').innerHTML = htmlFotos + '</div>';
        }

        // Botões de Status
        const area = document.getElementById('areaFechamento');
        if (c.status === 'concluido') {
            area.innerHTML = `
                <div class="card border-0 shadow-sm p-4" style="background-color: #d4edda; border-radius: 15px;">
                    <h6 class="fw-bold text-success">Parecer Técnico:</h6>
                    <p class="text-dark">${c.solucao_tecnica || 'Sem descrição'}</p>
                    <button onclick="alterarStatusOS(<?= $id ?>, 'fechar')" class="btn btn-success w-100 rounded-pill fw-bold">Fechar O.S.</button>
                </div>`;
        } else if (c.status === 'fechado') {
            area.innerHTML = `<button onclick="alterarStatusOS(<?= $id ?>, 'reabrir')" class="btn btn-warning w-100 rounded-pill fw-bold">Reabrir Chamado</button>`;
        }
    }

    async function alterarStatusOS(id, acao) {
        if(!confirm("Confirmar alteração de status?")) return;
        const res = await fetch('api/gestor_acoes.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({ id_chamado: id, acao: acao })
        });
        const data = await res.json();
        if(data.success) location.reload();
    }

    document.getElementById('formAtribuir').onsubmit = async (e) => {
        e.preventDefault();
        const res = await fetch('api/atribuir_chamado.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({
                id_chamado: <?= $id ?>,
                id_tecnico: document.getElementById('selectTecnico').value,
                prioridade: document.getElementById('prioridade').value,
                data_prevista: document.getElementById('data_prevista').value
            })
        });
        const data = await res.json();
        if(data.success) window.location.href = 'gestor_chamados.php';
    };

    carregarDados();
</script>
</body>
</html>