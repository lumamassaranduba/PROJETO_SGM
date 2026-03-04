<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SGM - Novo bloco</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body { background-color: #f8f9fa; font-family: 'Inter', sans-serif; }
        .navbar-custom { background-color: #990202; }
        .form-card { 
            border: none; 
            border-radius: 15px; 
            box-shadow: 0 10px 25px rgba(0,0,0,0.05); 
            background: white;
            padding: 2rem;
        }
        .form-label { font-weight: 600; color: #495057; font-size: 0.9rem; }
        .form-select, .form-control { border-color: #dee2e6; border-radius: 8px; }
        .form-select:focus, .form-control:focus { border-color: #990202; box-shadow: 0 0 0 0.25rem rgba(153, 2, 2, 0.1); }
        .btn-submit { background-color: #ffc107; border: none; font-weight: 700; color: #212529; border-radius: 8px; transition: 0.3s; }
        .btn-submit:hover { background-color: #e0a800; transform: translateY(-1px); }
    </style>
</head>

<body>

<header>
    <nav class="navbar navbar-custom py-2 mb-5">
        <div class="container">
            <div class="d-flex align-items-center">
                <a href="./gestor_blocos.php" class="btn btn-link text-white p-0 me-3">
                    <i class="bi bi-arrow-left-circle fs-4"></i>
                </a>
                <span class="navbar-brand text-white fw-bold m-0">Novo Bloco</span>
            </div>
            <a href="api/logout.php" class="btn btn-outline-light btn-sm rounded-pill px-3">Sair</a>
        </div>
    </nav>
</header>

<main class="container">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            
            <div class="form-card">
                <div class="text-center mb-4">
                    <i class="bi bi-plus-circle"></i>
                    <h4 class="fw-bold text-dark mt-2">Registrar Bloco</h4>
                    <p class="text-muted small">Preencha os detalhes abaixo para que a criação de um novo bloco.</p>
                </div>

                <form id="formChamado">
    
                    <div class="mb-4">
                        <label class="form-label"> Nome do bloco que deseja adicionar</label>
                        <textarea id="descricao" class="form-control" rows="4" required placeholder="Digite aqui..."></textarea>
                    </div>

                    <div class="mb-4">
                        <label class="form-label"> Descrição</label>
                        <textarea id="descricao" class="form-control" rows="4" required placeholder="..."></textarea>
                    </div>

                    <button type="submit" class="btn btn-submit w-100 py-3 mb-2 shadow-sm text-light"style="background-color: #990202;">
                        REGISTRAR NOVO BLOCO
                    </button>
                    
                    <a href="./gestor_blocos.php" class="btn btn-light w-100 rounded-pill btn-sm text-muted">Cancelar</a>
                </form>
            </div>
            
            <p class="text-center mt-4 text-muted small">SGM - Sistema de Gestão de Manutenção &copy; 2026</p>
        </div>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>


</body>
</html>