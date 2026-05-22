<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>SGM - Gestão de Serviços</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<style>
    /* Identidade Visual Padronizada SGM */
    :root {
        --vinho-dark: #7a0101;
        --vinho-light: #990202;
    }
    body {
        background-color: #f0f2f5;
        font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
    }
    
    /* Navbar Limpa e Padronizada SGM ADMIN */
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

    /* Botões de Ação Mobile-Friendly */
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

    /* Ajustes de Responsividade Extremamente Otimizados para Celular */
    @media (max-width: 576px) {
        .header-servicos {
            flex-direction: column;
            text-align: center;
            gap: 16px;
        }
        .header-servicos a.btn-add {
            width: 100%;
            padding: 12px !important;
        }
        .btn-voltar-container {
            text-align: center;
        }
        
        /* Reduz o espaçamento das células para caber tudo na tela sem sumir com a lixeira */
        .table-modern thead th, 
        .table-modern tbody td {
            padding: 8px 4px !important;
            font-size: 0.85rem !important;
        }

        /* Oculta o texto descritivo dos botões e os torna quadrados compactos para o toque */
        .btn-text {
            display: none;
        }
        .btn-action {
            padding: 0;
            border-radius: 8px;
            width: 38px;
            height: 38px;
        }
        .btn-action i {
            font-size: 1.1rem;
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
    
    <div class="d-flex justify-content-between align-items-sm-center mb-4 header-servicos">
        <div>
            <h2 class="fw-bold text-dark m-0">Tipos de Serviço</h2>
            <p class="text-muted small m-0">Gerencie as categorias de manutenção do sistema</p>
        </div>
        <a href="gestor_adicionar_servicos.php" class="btn text-white rounded-pill px-4 py-2 fw-bold shadow-sm d-inline-flex align-items-center justify-content-center border-0 btn-add" style="background-color: var(--vinho-light);">
            <i class="bi bi-plus-lg me-2"></i> Adicionar Serviço
        </a>
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-5">
        <div class="table-responsive">
            <table class="table table-modern align-middle mb-0" style="min-width: 100%;">
                <thead>
                    <tr>
                        <th style="width: 10%">ID</th>
                        <th style="width: 25%">Nome</th>
                        <th style="width: 35%">Descrição</th>
                        <th style="width: 15%" class="text-center">Editar</th>
                        <th style="width: 15%" class="text-center">Excluir</th>
                    </tr>
                </thead>
                <tbody id="tabela-corpo">
                    </tbody>
            </table>
        </div>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
async function carregarTabela() {
    const res = await fetch('api/api_servicos.php');
    const json = await res.json();
    const lista = document.getElementById('tabela-corpo');
    lista.innerHTML = '';

    json.data.forEach(item => {
        lista.innerHTML += `
            <tr style="border-bottom: 1px solid #edf2f7;">
                <td class="text-muted fw-semibold small">#${item.id_tipo}</td>
                <td class="fw-bold text-dark">${item.nome}</td>
                <td class="text-secondary small">${item.descricao}</td>
                
                <td class="text-center">
                    <a href="./gestor_atualizar_servicos.php?id=${item.id_tipo}" class="text-decoration-none">
                        <button class="btn btn-action bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25 mx-auto">
                            <i class="bi bi-pencil-square"></i> <span class="btn-text">ATUALIZAR</span>
                        </button>
                    </a>
                </td>
                
                <td class="text-center">
                    <button class="btn btn-action text-white shadow-sm mx-auto" style="background-color: var(--vinho-light);" 
                    onclick="deletarItem(${item.id_tipo})"> 
                        <i class="bi bi-trash3"></i> <span class="btn-text">DELETAR</span>
                    </button>
                </td>
            </tr>`;
    });
}

async function deletarItem(id) {
    if(confirm("Tem certeza que deseja deletar este tipo de serviço?")) {
        const res = await fetch('api/api_servicos.php', {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ id_tipo: id })
        });
        const resultado = await res.json();
        alert(resultado.message);
        carregarTabela();
    }
}
document.addEventListener('DOMContentLoaded', carregarTabela);
</script>
</body>
</html>