<?php
session_start();
if(!isset($_SESSION['user_id']) || $_SESSION['user_perfil'] !== 'solicitante'){
    header("Location:login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SGM - Abrir Solicitação</title>
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
                <a href="solicitante_dashboard.php" class="btn btn-link text-white p-0 me-3">
                    <i class="bi bi-arrow-left-circle fs-4"></i>
                </a>
                <span class="navbar-brand text-white fw-bold m-0">Novo Chamado</span>
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
                    <i class="bi bi-tools fs-1 text-secondary opacity-25"></i>
                    <h4 class="fw-bold text-dark mt-2">Registrar Solicitação</h4>
                    <p class="text-muted small">Preencha os detalhes abaixo para que a equipe técnica possa ajudar.</p>
                </div>

                <form id="formChamado">
                    <div class="mb-3">
                        <label class="form-label">Bloco / Setor</label>
                        <select id="selectBloco" class="form-select" required onchange="carregarAmbientes(this.value)">
                            <option value="">Selecione o bloco</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Ambiente / Sala</label>
                        <select id="selectAmbiente" class="form-select" required disabled>
                            <option value="">Selecione o bloco primeiro...</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Tipo de Serviço</label>
                        <select id="selectTipo" class="form-select" required>
                            <option value="">Selecione o tipo...</option> 
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Descrição Detalhada</label>
                        <textarea id="descricao" class="form-control" rows="4" required placeholder="Descreva o que aconteceu..."></textarea>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Foto da Ocorrência <span class="text-muted fw-normal">(Opcional)</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="bi bi-camera"></i></span>
                            <input type="file" id="foto" class="form-control border-start-0" accept="image/*">
                        </div>
                    </div>

                    <button type="submit" class="btn btn-submit w-100 py-3 mb-2 shadow-sm text-light"style="background-color: #990202;">
                        REGISTRAR SOLICITAÇÃO
                    </button>
                    
                    <a href="solicitante_dashboard.php" class="btn btn-light w-100 rounded-pill btn-sm text-muted">Cancelar</a>
                </form>
            </div>
            
            <p class="text-center mt-4 text-muted small">SGM - Sistema de Gestão de Manutenção &copy; 2026</p>
        </div>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
async function iniciar() {
    try {
        const resB = await fetch('api/localizacoes.php?acao=listar_blocos');
        const blocos = await resB.json();
        const selB = document.getElementById('selectBloco');
        blocos.forEach(b => {
            selB.innerHTML += `<option value="${b.id_bloco}">${b.nome}</option>`;
        });

        const resT = await fetch('api/localizacoes.php?acao=listar_tipos');
        const tipos = await resT.json();
        const selT = document.getElementById('selectTipo');
        tipos.forEach(t => {
            selT.innerHTML += `<option value="${t.id_tipo}">${t.nome}</option>`;
        });
    } catch (e) { console.error("Erro ao carregar dados iniciais:", e); }
}

async function carregarAmbientes(id_bloco) {
    const selA = document.getElementById('selectAmbiente');
    if (!id_bloco) {
        selA.disabled = true;
        selA.innerHTML = '<option value="">Selecione o bloco primeiro...</option>';
        return;
    }

    const res = await fetch(`api/localizacoes.php?acao=listar_ambientes&id_bloco=${id_bloco}`);
    const ambientes = await res.json();
    selA.innerHTML = '<option value="">Selecione a Sala...</option>';
    ambientes.forEach(a => {
        selA.innerHTML += `<option value="${a.id_ambiente}">${a.nome}</option>`;
    });
    selA.disabled = false;
}

document.getElementById('formChamado').addEventListener('submit', async (e) => {
    e.preventDefault();
    const btnSubmit = e.target.querySelector('button[type="submit"]');
    btnSubmit.disabled = true;
    btnSubmit.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Enviando...';

    const formData = new FormData();
    formData.append('id_ambiente', document.getElementById('selectAmbiente').value);
    formData.append('id_tipo', document.getElementById('selectTipo').value);
    formData.append('descricao', document.getElementById('descricao').value);

    const fotoFile = document.getElementById('foto').files[0];
    if (fotoFile) formData.append('foto', fotoFile);

    try {
        const response = await fetch('api/salvar_chamado.php', {
            method: 'POST',
            body: formData
        });
        const result = await response.json();

        if (result.success) {
            alert("Chamado aberto com sucesso!");
            window.location.href = 'solicitante_dashboard.php';
        } else {
            alert("Erro: " + result.message);
            btnSubmit.disabled = false;
            btnSubmit.innerText = 'REGISTRAR SOLICITAÇÃO';
        }
    } catch (err) {
        alert("Erro na conexão com o servidor.");
        btnSubmit.disabled = false;
    }
});

iniciar();
</script>

</body>
</html>