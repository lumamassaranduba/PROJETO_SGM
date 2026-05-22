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
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>SGM - Gestão de Blocos</title>
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
        font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
    }
    
    /* Navbar Limpa e Padronizada */
    .navbar { 
        background: linear-gradient(135deg, var(--vinho-dark) 0%, var(--vinho-light) 100%); 
        border-bottom: 4px solid #ffc107; 
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

    /* Customização Avançada da Tabela */
    .table-modern thead th {
        font-weight: 700;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.8px;
        color: #6c757d;
        border-bottom: 2px solid #edf2f7;
        padding: 16px;
        background-color: white;
    }
    .table-modern tbody tr {
        transition: all 0.2s ease;
    }
    .table-modern tbody tr:hover {
        background-color: #f8f9fa !important;
    }
    .table-modern tbody td {
        padding: 16px;
        vertical-align: middle;
        color: #2d3436;
    }

    /* Botoes de Ação Ultra Responsivos */
    .btn-action {
        font-weight: 600;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
        padding: 8px 16px;
        border-radius: 50px;
        transition: all 0.2s;
        border: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
    }
    .btn-action:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 10px rgba(0,0,0,0.08);
    }

    /* Ajustes Finos de Responsividade */
    @media (max-width: 576px) {
        .header-blocos {
            flex-direction: column;
            text-align: center;
            gap: 16px;
        }
        .header-blocos a.btn-add {
            width: 100%;
            padding: 12px !important;
        }
        .btn-voltar-container {
            text-align: center;
        }
        .btn-text {
            display: none;
        }
        .btn-action {
            padding: 0;
            border-radius: 10px;
            width: 36px;
            height: 36px;
        }
        .btn-action i {
            font-size: 1.05rem;
            margin: 0 !important;
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
            
            <a href="api/logout.php" class="btn btn-outline-light btn-sm rounded-pill px-3 fw-semibold">Sair</a>
        </div>
    </nav>
</header>

<main class="container px-3">
    
    <div class="btn-voltar-container">
        <a href="gestor_dashboard.php" class="btn-voltar-link">
            <i class="bi bi-arrow-left"></i> Voltar ao Menu Principal
        </a>
    </div>
    
    <div class="d-flex justify-content-between align-items-sm-center mb-4 header-blocos">
        <div>
            <h2 class="fw-bold text-dark m-0">Blocos Cadastrados</h2>
            <p class="text-muted small m-0">Gerencie as estruturas físicas da instituição</p>
        </div>
        <a href="gestor_adicionar_blocos.php" class="btn text-white rounded-pill px-4 py-2 fw-bold shadow-sm d-inline-flex align-items-center justify-content-center border-0 btn-add" style="background-color: var(--vinho-light);">
            <i class="bi bi-plus-lg me-2"></i> Adicionar Bloco
        </a>
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-5">
        <div class="table-responsive">
            <table class="table table-modern align-middle mb-0" id="tabelaBlocos" style="min-width: 100%;">
                </table>
        </div>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
async function carregarBlocos() {
    const res = await fetch('api/api_blocos.php');
    const dados = await res.json();
    const tabela = document.getElementById('tabelaBlocos');

    tabela.innerHTML = `
    <thead>
        <tr>
            <th style="width: 15%">ID</th>
            <th style="width: 55%">Nome do Bloco</th>
            <th style="width: 15%" class="text-center">Editar</th>
            <th style="width: 15%" class="text-center">Excluir</th>
        </tr>
    </thead>
    <tbody>
        ${dados.data.map(b => `
        <tr style="border-bottom: 1px solid #edf2f7;">
            <td class="text-muted fw-semibold small">#${b.id_bloco}</td>
            <td class="fw-bold text-dark fs-6">${b.nome}</td>

            <td class="text-center">
                <a href="gestor_atualizar_blocos.php?id=${b.id_bloco}" class="text-decoration-none">
                    <button class="btn btn-action bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25 mx-auto">
                        <i class="bi bi-pencil-square"></i> <span class="btn-text">ATUALIZAR</span>
                    </button>
                </a>
            </td>

            <td class="text-center">
                <button class="btn btn-action text-white shadow-sm mx-auto"
                style="background-color: var(--vinho-light);"
                onclick="deletarBloco(${b.id_bloco})">
                    <i class="bi bi-trash3"></i> <span class="btn-text">DELETAR</span>
                </button>
            </td>
        </tr>
        `).join('')}
    </tbody>
    `;
}

function deletarBloco(id){
    if(!confirm("Deseja realmente deletar este bloco?")) return;

    fetch('api/api_blocos.php', {
        method:'DELETE',
        headers:{'Content-Type':'application/json'},
        body: JSON.stringify({id_bloco:id})
    })
    .then(r=>r.json())
    .then(d=>{
        alert(d.message);
        carregarBlocos();
    });
}

carregarBlocos();
</script>
</body>
</html>