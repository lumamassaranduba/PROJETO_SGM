<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SGM - Novo Bloco</title>
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
        
        .form-control { 
            border-color: #dee2e6; 
            border-radius: 10px; 
            padding: 12px;
            transition: all 0.2s;
        }
        
        .form-control:focus { 
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
    </style>
</head>

<body>

<header>
    <nav class="navbar navbar-dark shadow-sm mb-4 mb-md-5 px-4">
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
    <div class="row justify-content-center">
        <div class="col-12 col-sm-10 col-md-7 col-lg-5">
            
            <div class="form-card">
                <div class="text-center mb-4">
                    <div class="icon-circle mb-2">
                        <i class="bi bi-building-add"></i>
                    </div>
                    <h4 class="fw-bold text-dark mb-1">Registrar Bloco</h4>
                    <p class="text-muted small">Preencha os campos para adicionar a nova estrutura física.</p>
                </div>

                <form id="formBloco">
                    <div class="mb-3">
                        <label for="nome" class="form-label">Nome do Bloco</label>
                        <input type="text" id="nome" class="form-control" required placeholder="Ex: Bloco Administrativo, Bloco C...">
                    </div>

                    <div class="mb-4">
                        <label for="descricao" class="form-label">Descrição</label>
                        <textarea id="descricao" class="form-control" rows="3" required placeholder="Breve descrição sobre o bloco ou setores inseridos nele..."></textarea>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-submit shadow-sm text-white">
                            REGISTRAR NOVO BLOCO
                        </button>
                        <a href="./gestor_blocos.php" class="btn btn-light btn-cancel text-muted border">
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
document.getElementById('formBloco').addEventListener('submit', async function(e){
    e.preventDefault();

    const nome = document.getElementById('nome').value;
    const descricao = document.getElementById('descricao').value;

    try {
        const res = await fetch('api/api_blocos.php',{
            method:'POST',
            headers:{'Content-Type':'application/json'},
            body: JSON.stringify({
                nome: nome,
                descricao: descricao
            })
        });

        const data = await res.json();
        alert(data.message);

        if(data.success){
            window.location.href = 'gestor_blocos.php';
        }
    } catch (error) {
        console.error("Erro ao registrar bloco:", error);
        alert("Ocorreu um erro ao tentar salvar o bloco.");
    }
});
</script>

</body>
</html>