<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SGM - Atualização do ambiente</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>

<body>

<header>
    <nav class="navbar navbar-custom py-2 mb-5" style="background-color: #990202;">
        <div class="container">
            <div class="d-flex align-items-center">
                <a href="./gestor_ambientes.php" class="btn btn-link text-white p-0 me-3">
                    <i class="bi bi-arrow-left-circle fs-4"></i>
                </a>
                <span class="navbar-brand text-white fw-bold m-0">Atualizar Ambiente</span>
            </div>
            <a href="api/logout.php" class="btn btn-outline-light btn-sm rounded-pill px-3">Sair</a>
        </div>
    </nav>
</header>

<main class="container">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            
            <div class="form-card p-4 bg-white shadow rounded-4">
                <div class="text-center mb-4">
                   <i class="bi bi-upload"></i>
                    <h4 class="fw-bold text-dark mt-2">Atualizar Ambiente</h4>
                    <p class="text-muted small">Preencha os detalhes abaixo para a atualização do ambiente desejado.</p>
                </div>

                <form id="formChamado">
                    <div class="mb-3">
                        <label class="form-label">Bloco / Setor</label>
                        <select id="selectBloco" class="form-select" required></select>
                    </div>

                    <div class="mb-4">
                        <label class="form-label"> Nome atualizado do ambiente</label>
                        <textarea id="descricao" class="form-control" rows="4" required></textarea>
                    </div>

                    <button type="submit" class="btn w-100 py-3 mb-2 text-light" style="background-color: #990202;">
                        ATUALIZAR AMBIENTE
                    </button>
                    
                    <a href="gestor_ambientes.php" class="btn btn-light w-100 rounded-pill btn-sm text-muted">Cancelar</a>
                </form>
            </div>
        </div>
    </div>
</main>

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

</body>
</html>