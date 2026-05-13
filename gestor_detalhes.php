<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_perfil'] !== 'gestor') {
    header("Location: login.php");
    exit;
}
$id = (int)($_GET['id'] ?? 0);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>SGM - Detalhes do Chamado</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        .info-label { font-size: 0.75rem; text-transform: uppercase; font-weight: 700; color: #6c757d; }
        .info-value { font-weight: 600; color: #2d3436; margin-bottom: 0.5rem; }
        .card { border: none; border-radius: 15px; }
    </style>
</head>
<body class="bg-body-tertiary">

<script>
    const CHAMADO_ID = <?= json_encode($id) ?>;
</script>

<nav class="navbar navbar-expand-lg shadow-sm mb-5" style="background-color:#990202;">
    <div class="container">
        <a href="gestor_chamados.php" class="btn btn-link text-light me-2"><i class="bi bi-arrow-left-circle-fill fs-4"></i></a>
        <a class="navbar-brand text-light fw-bold" href="gestor_dashboard.php">SGM Admin</a>
    </div>
</nav>

<div class="container pb-5">
    <div class="row g-4">
        <!-- Detalhes -->
        <div class="col-md-7">
            <div class="card shadow-sm mb-4">
                <div class="card-header text-white" style="background:#990202;">Dados da Solicitação</div>
                <div id="detalhesChamado" class="card-body">
                    <div class="text-center p-4">Carregando...</div>
                </div>
            </div>
        </div>

        <!-- Atribuição -->
        <div class="col-md-5">
            <div class="card shadow-sm">
                <div class="card-header text-white" style="background:#990202;">Triagem e Atribuição</div>
                <div class="card-body">
                    <form id="formAtribuir">
                        <div class="mb-3">
                            <label class="info-label">Técnico</label>
                            <select id="selectTecnico" class="form-select" required>
                                <option value="">Carregando técnicos...</option>
                            </select>
                        </div>
                        <div class="row g-2">
                            <div class="col-6">
                                <label class="info-label">Prioridade</label>
                                <select id="prioridade" class="form-select">
                                    <option value="baixa">Baixa</option>
                                    <option value="media">Média</option>
                                    <option value="alta">Alta</option>
                                    <option value="urgente">Urgente</option>
                                </select>
                            </div>
                            <div class="col-6">
                                <label class="info-label">Data Prevista</label>
                                <input type="date" id="data_prevista" class="form-control" required>
                            </div>
                        </div>
                        <button type="submit" id="btnConfirmar" class="btn w-100 text-white fw-bold mt-3" style="background:#990202;">
                            Confirmar Atribuição
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
async function carregarDados() {
    try {
        const [resTec, resChamado] = await Promise.all([
            fetch('api/usuarios.php'),
            fetch(`api/chamados.php?id=${CHAMADO_ID}`)
        ]);

        const tecnicos = await resTec.json();
        const c = await resChamado.json();

        // Preencher Técnicos
        const select = document.getElementById('selectTecnico');
        select.innerHTML = '<option value="">Selecione um técnico</option>';
        tecnicos.forEach(t => {
            select.innerHTML += `<option value="${t.id_usuario}">${t.nome}</option>`;
        });

        // Preencher Detalhes
        document.getElementById('detalhesChamado').innerHTML = `
            <div class="mb-2"><span class="badge bg-primary">${c.status.toUpperCase()}</span></div>
            <div class="mb-2"><div class="info-label">Local</div><div class="info-value">${c.bloco_nome} - ${c.ambiente_nome}</div></div>
            <div class="mb-2"><div class="info-label">Solicitante</div><div class="info-value">${c.solicitante_nome}</div></div>
            <div class="mb-2"><div class="info-label">Descrição</div><div class="info-value bg-light p-3 rounded">${c.descricao_problema}</div></div>
        `;

        if (c.id_tecnico) select.value = c.id_tecnico;
        if (c.prioridade) document.getElementById('prioridade').value = c.prioridade;
        if (c.data_previsao_conclusao) document.getElementById('data_prevista').value = c.data_previsao_conclusao;

    } catch (err) {
        console.error(err);
        document.getElementById('detalhesChamado').innerHTML = "Erro ao carregar dados.";
    }
}

document.getElementById('formAtribuir').addEventListener('submit', async (e) => {
    e.preventDefault();
    const btn = document.getElementById('btnConfirmar');
    btn.disabled = true;
    btn.innerText = "Processando...";

    const payload = {
        id_chamado: CHAMADO_ID,
        id_tecnico: parseInt(document.getElementById('selectTecnico').value),
        prioridade: document.getElementById('prioridade').value,
        data_prevista: document.getElementById('data_prevista').value
    };

    try {
        const res = await fetch('api/atribuir_chamado.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify(payload)
        });

        const data = await res.json();

        if (data.success) {
            alert("Sucesso!");
            window.location.href = 'gestor_chamados.php';
        } else {
            alert("Erro: " + data.message);
            btn.disabled = false;
            btn.innerText = "Confirmar Atribuição";
        }
    } catch (err) {
        console.error(err);
        alert("Erro crítico na requisição. Verifique o console.");
        btn.disabled = false;
        btn.innerText = "Confirmar Atribuição";
    }
});

carregarDados();
</script>
</body>
</html>