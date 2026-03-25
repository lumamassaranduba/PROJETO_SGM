<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SGM - Atualização de serviços</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body { background-color: #f8f9fa; font-family: 'Inter', sans-serif; }
        .navbar-custom { background-color: #990202; }
        .form-card { border: none; border-radius: 15px; box-shadow: 0 10px 25px rgba(0,0,0,0.05); background: white; padding: 2rem; }
        .form-label { font-weight: 600; color: #495057; font-size: 0.9rem; }
        .form-control { border-color: #dee2e6; border-radius: 8px; }
        .form-control:focus { border-color: #990202; box-shadow: 0 0 0 0.25rem rgba(153, 2, 2, 0.1); }
        .btn-submit { background-color: #990202; border: none; font-weight: 700; color: white; border-radius: 8px; transition: 0.3s; }
    </style>
</head>
<body>

<header>
    <nav class="navbar navbar-custom py-2 mb-5">
        <div class="container">
            <div class="d-flex align-items-center">
                <a href="./gestor_servicos.php" class="btn btn-link text-white p-0 me-3">
                    <i class="bi bi-arrow-left-circle fs-4"></i>
                </a>
                <span class="navbar-brand text-white fw-bold m-0">Atualizar Tipos de Serviços</span>
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
                   <i class="bi bi-upload fs-1 text-danger"></i>
                    <h4 class="fw-bold text-dark mt-2">Atualizar Tipos de Serviços</h4>
                    <p class="text-muted small">Preencha os detalhes abaixo para a atualização.</p>
                </div>

                <input type="hidden" id="id_tipo">

                <div class="mb-4">
                    <label class="form-label"> Nome do serviço</label>
                    <input type="text" id="nome" class="form-control" required>
                </div>

                <div class="mb-4">
                    <label class="form-label"> Descrição atualizada</label>
                    <textarea id="descricao" class="form-control" rows="4" required></textarea>
                </div>

                <button onclick="salvarAlteracoes()" class="btn btn-submit w-100 py-3 mb-2 shadow-sm">
                    ATUALIZAR TIPO DE SERVIÇO
                </button>
                
                <a href="gestor_servicos.php" class="btn btn-light w-100 rounded-pill btn-sm text-muted">Cancelar</a>
            </div>
        </div>
    </div>
</main>

<script>
// Carrega dados ao abrir a página
document.addEventListener('DOMContentLoaded', async () => {
    const id = new URLSearchParams(window.location.search).get('id');
    const res = await fetch(`api/api_servicos.php?id=${id}`);
    const json = await res.json();

    if(json.success) {
        document.getElementById('id_tipo').value = json.data.id_tipo;
        document.getElementById('nome').value = json.data.nome;
        document.getElementById('descricao').value = json.data.descricao;
    }
});

// Envia atualização
async function salvarAlteracoes() {
    const dados = {
        id_tipo: document.getElementById('id_tipo').value,
        nome: document.getElementById('nome').value,
        descricao: document.getElementById('descricao').value
    };

    const res = await fetch('api/api_servicos.php', {
        method: 'PUT',
        body: JSON.stringify(dados)
    });
    const resultado = await res.json();
    alert(resultado.message);
    if(resultado.success) window.location.href = 'gestor_servicos.php';
}
</script>
</body>
</html>