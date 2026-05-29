<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SGM - Atualização do Ambiente</title>
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
        
        /* Navbar Padrão SGM ADMIN */
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

        /* Card do Formulário Adaptável */
        .form-card { 
            border: none; 
            border-radius: 16px; 
            box-shadow: 0 4px 12px rgba(0,0,0,0.05); 
            background: white;
            padding: 2rem 1.5rem;
        }
        
        .form-label { 
            font-weight: 700; 
            color: #2d3436; 
            font-size: 0.85rem; 
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .form-select, .form-control { 
            border-color: #dee2e6; 
            border-radius: 10px; 
            padding: 12px;
            transition: all 0.2s;
        }
        
        .form-select:focus, .form-control:focus { 
            border-color: var(--vinho-light); 
            box-shadow: 0 0 0 0.25rem rgba(153, 2, 2, 0.1); 
        }

        /* Botões Mobile-Friendly */
        .btn-submit { 
            background-color: var(--vinho-light); 
            border: none; 
            font-weight: 700; 
            color: white; 
            border-radius: 50px; 
            transition: 0.2s; 
            padding: 14px;
        }
        .btn-submit:hover { 
            background-color: var(--vinho-dark); 
            transform: translateY(-1px); 
        }
        
        .btn-cancel {
            border-radius: 50px;
            padding: 10px;
            font-weight: 600;
        }

        /* Ícone de Destaque */
        .icon-circle {
            width: 60px;
            height: 60px;
            background-color: rgba(153, 2, 2, 0.08);
            color: var(--vinho-light);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            font-size: 1.5rem;
        }

        @media (max-width: 576px) {
            .btn-voltar-container {
                text-align: center;
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
            <button type="button" class="btn btn-outline-light btn-sm rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#modalLogout">
    Sair
</button>
        </div>
    </nav>
</header>

<main class="container px-3">
    <div class="row justify-content-center">
        <div class="col-12 col-sm-10 col-md-7 col-lg-5">
            
            <div class="btn-voltar-container">
                <a href="./gestor_ambientes.php" class="btn-voltar-link">
                    <i class="bi bi-arrow-left"></i> Voltar para Ambientes
                </a>
            </div>

            <div class="form-card">
                <div class="text-center mb-4">
                    <div class="icon-circle mb-2">
                        <i class="bi bi-pencil-square"></i>
                    </div>
                    <h4 class="fw-bold text-dark mb-1">Atualizar Ambiente</h4>
                    <p class="text-muted small">Modifique as informações necessárias abaixo para atualizar o ambiente.</p>
                </div>

                <form id="formChamado">
                    <div class="mb-3">
                        <label for="selectBloco" class="form-label">Bloco / Setor</label>
                        <select id="selectBloco" class="form-select" required></select>
                    </div>

                    <div class="mb-4">
                        <label for="descricao" class="form-label">Nome atualizado do ambiente</label>
                        <input type="text" id="descricao" class="form-control" required placeholder="Ex: Sala 102, Laboratório B...">
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-submit shadow-sm text-white">
                            ATUALIZAR AMBIENTE
                        </button>
                        <a href="gestor_ambientes.php" class="btn btn-light btn-cancel text-muted border">
                            Cancelar
                        </a>
                    </div>
                </form>
            </div>
            
            <p class="text-center mt-4 text-muted small">&copy; 2026 SGM Gestão de Manutenção</p>
        </div>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
const urlParams = new URLSearchParams(window.location.search);
const id = urlParams.get('id');

async function carregarBlocos() {
    const res = await fetch('api/api_blocos.php');
    const dados = await res.json();

    const select = document.getElementById('selectBloco');

    select.innerHTML = '<option value="">Selecione o bloco</option>' +
        dados.data.map(b => `
            <option value="${b.id_bloco}">${b.nome}</option>
        `).join('');
}

async function carregarAmbiente() {
    const res = await fetch('api/api_ambientes.php');
    const dados = await res.json();

    const ambiente = dados.data.find(a => a.id_ambiente == id);

    if (ambiente) {
        document.getElementById('descricao').value = ambiente.nome;
        document.getElementById('selectBloco').value = ambiente.id_bloco;
    }
}

document.getElementById('formChamado').addEventListener('submit', async function(e) {
    e.preventDefault();

    const nome = document.getElementById('descricao').value;
    const id_bloco = document.getElementById('selectBloco').value;

    const res = await fetch('api/api_ambientes.php', {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            id_ambiente: id,
            nome: nome,
            id_bloco: id_bloco
        })
    });

    const data = await res.json();
    alert(data.message);

    if (data.success) {
        window.location.href = 'gestor_ambientes.php';
    }
});

carregarBlocos().then(() => carregarAmbiente());
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