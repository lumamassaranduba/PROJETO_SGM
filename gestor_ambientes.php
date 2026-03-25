<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title>SGM - Gestão de Ambientes</title>
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
            <a class="nav-link px-3 text-light" href="gestor_dashboard.php">Home</a>
            <a href="api/logout.php" class="btn btn-outline-light btn-sm ms-2 rounded-pill px-3">Sair</a>
        </div>
    </div>
</nav>

<main class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-dark m-0 p-3">AMBIENTES</h2>
        <a href="gestor_adicionar_ambientes.php" class="btn text-white rounded-pill px-4 fw-bold shadow-sm m-3" style="background-color: #990202;">
            <i class="bi bi-plus-lg me-1"></i> Adicionar ambiente</a>
    </div>

<div class="card border-0 shadow-sm rounded-4 overflow-hidden p-4">
        <div class="table-responsive">
            <table class="table" id="tabelaGeral"></table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
async function carregarAmbientes() {
    const res = await fetch(`api/api_ambientes.php`);
    const ambientes = await res.json();
    const tabela = document.getElementById('tabelaGeral');

    tabela.innerHTML = `
        <thead>
            <tr>
                <th>ID</th>
                <th>BLOCO</th>
                <th>AMBIENTE</th>
                <th>GERENCIAR</th>
                <th>DELETAR</th>
            </tr>
        </thead>
        <tbody>
            ${ambientes.data.map(a => `
                <tr>
                    <td>${a.id_ambiente}</td>
                    <td>${a.nome_bloco}</td>
                    <td>${a.nome}</td>

                    <td>
                        <a href="./gestor_atualizar__ambientes.php?id=${a.id_ambiente}">
                            <button class="btn btn-sm px-3 rounded-pill bg-warning text-white shadow-sm"
                            style="font-size: 12px; font-weight: 600;">
                                <i class="bi bi-upload"></i> ATUALIZAR
                            </button>
                        </a>
                    </td>

                    <td>
                        <button class="btn btn-sm px-3 rounded-pill text-white shadow-sm"
                        style="background-color: #990202; font-size: 12px; font-weight: 600;"
                        onclick="deletarAmbiente(${a.id_ambiente})">
                            <i class="bi bi-trash3"></i> DELETAR
                        </button>
                    </td>
                </tr>
            `).join('')}
        </tbody>
    `;
}

function deletarAmbiente(id) {
    if (!confirm("Tem certeza que deseja deletar?")) return;

    fetch('api/api_ambientes.php', {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ id_ambiente: id })
    })
    .then(res => res.json())
    .then(data => {
        alert(data.message);
        carregarAmbientes();
    })
    .catch(err => console.error(err));
}

carregarAmbientes();
</script>
</body>
</html>