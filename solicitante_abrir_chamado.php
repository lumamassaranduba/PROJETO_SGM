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
        :root {
            --vinho-dark: #7a0101;
            --vinho-light: #990202;
            --sgm-gold: #ffc107;
        }
        body { 
            background-color: #f0f2f5; 
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif; 
        }
        .navbar-custom { 
            background: linear-gradient(135deg, var(--vinho-dark) 0%, var(--vinho-light) 100%);
            border-bottom: 4px solid var(--sgm-gold);
            height: 65px;
        }
        .form-card { 
            border: none; 
            border-radius: 20px; 
            box-shadow: 0 4px 16px rgba(0,0,0,0.04); 
            background: white;
        }
        .form-label { 
            font-weight: 700; 
            color: #344054; 
            font-size: 0.88rem; 
            margin-bottom: 6px;
        }
        .form-select, .form-control { 
            border-color: #d0d5dd; 
            border-radius: 10px;
            padding: 10px 14px;
            font-size: 0.95rem;
            color: #1d2939;
        }
        .form-select:focus, .form-control:focus { 
            border-color: var(--vinho-light); 
            box-shadow: 0 0 0 4px rgba(153, 2, 2, 0.12); 
        }
        .btn-submit { 
            background: linear-gradient(135deg, var(--vinho-light) 0%, var(--vinho-dark) 100%);
            border: none; 
            font-weight: 700; 
            color: white; 
            border-radius: 12px; 
            transition: all 0.2s ease;
            letter-spacing: 0.5px;
        }
        .btn-submit:hover:not(:disabled) { 
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(153, 2, 2, 0.2);
            color: white;
        }
        .preview-container {
            display: none;
            position: relative;
            border-radius: 12px;
            overflow: hidden;
            border: 1px solid #d0d5dd;
            background: #f8f9fa;
        }
        .preview-img {
            width: 100%;
            max-height: 200px;
            object-fit: contain;
        }
        .btn-remove-preview {
            position: absolute;
            top: 8px;
            right: 8px;
            background: rgba(255, 255, 255, 0.9);
            border: none;
            border-radius: 50px;
            padding: 4px 8px;
            font-size: 0.8rem;
            font-weight: 600;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        @media (max-width: 575.98px) {
            .form-card {
                padding: 1.5rem !important;
                border-radius: 16px;
            }
            .btn-submit {
                padding: 14px !important;
            }
        }
    </style>
</head>

<body>

<header>
    <nav class="navbar navbar-custom py-2 mb-4 shadow-sm">
        <div class="container d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <a href="solicitante_dashboard.php" class="btn btn-link text-white p-0 me-3" title="Voltar ao Painel">
                    <i class="bi bi-chevron-left fs-4"></i>
                </a>
                <span class="navbar-brand text-white fw-bold m-0 fs-5">Nova Solicitação</span>
            </div>
            <button type="button" class="btn btn-outline-light btn-sm rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#modalLogout">
    Sair
</button>
        </div>
    </nav>
</header>

<main class="container px-3">
    <div class="row justify-content-center">
        <div class="col-100 col-sm-10 col-md-8 col-lg-5">
            
            <div class="form-card p-4 p-md-5">
                <div class="text-center mb-4">
                    <div class="d-inline-flex p-3 bg-light rounded-circle mb-2 text-muted">
                        <i class="bi bi-file-earmark-plus fs-3" style="color: var(--vinho-light);"></i>
                    </div>
                    <h4 class="fw-bold text-dark m-0">Registrar Problema</h4>
                    <p class="text-muted small mt-1">Informe a localização exata do dano para o atendimento.</p>
                </div>

                <div id="alertFeedback" class="alert d-none" role="alert"></div>

                <form id="formChamado" novalidate>
                    <div class="mb-3">
                        <label class="form-label">Bloco / Setor</label>
                        <select id="selectBloco" class="form-select form-select-lg" required onchange="carregarAmbientes(this.value)">
                            <option value="">Selecione o bloco</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Ambiente / Sala</label>
                        <select id="selectAmbiente" class="form-select form-select-lg" required disabled>
                            <option value="">Selecione o bloco primeiro...</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Tipo de Serviço</label>
                        <select id="selectTipo" class="form-select form-select-lg" required>
                            <option value="">Selecione o tipo de manutenção...</option> 
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Descrição Detalhada</label>
                        <textarea id="descricao" class="form-control" rows="4" required placeholder="Ex: Lâmpada piscando, infiltração na parede esquerda, porta emperrada..."></textarea>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Foto da Ocorrência <span class="text-muted fw-normal">(Opcional)</span></label>
                        <div class="input-group">
                            <label class="input-group-text bg-white text-secondary border-end-0" style="border-radius: 10px 0 0 10px;" for="foto">
                                <i class="bi bi-camera-fill text-muted fs-5"></i>
                            </label>
                            <input type="file" id="foto" class="form-control" style="border-radius: 0 10px 10px 0;" accept="image/*" capture="environment" onchange="previewImage(event)">
                        </div>
                        
                        <div class="preview-container mt-3" id="previewArea">
                            <button type="button" class="btn-remove-preview text-danger" onclick="removerFoto()"><i class="bi bi-trash3-fill"></i> Remover</button>
                            <img src="" id="imgPreview" class="preview-img" alt="Preview da Ocorrência">
                        </div>
                    </div>

                    <button type="submit" class="btn btn-submit w-100 py-3 mb-3 text-white shadow-sm">
                        ENVIAR SOLICITAÇÃO
                    </button>
                    
                    <a href="solicitante_dashboard.php" class="btn btn-light w-100 rounded-3 py-2 text-muted fw-medium border-0">Cancelar</a>
                </form>
            </div>
            
            <p class="text-center mt-4 text-muted small">SGM - Sistema de Gestão de Manutenção &copy; 2026</p>
        </div>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
function exibirFeedback(mensagem, tipo = 'danger') {
    const box = document.getElementById('alertFeedback');
    box.className = `alert alert-${tipo} text-start small border-0 shadow-sm mb-3 d-block`;
    box.innerHTML = `<i class="bi ${tipo === 'success' ? 'bi-check-circle-fill' : 'bi-exclamation-triangle-fill'} me-2"></i> ${mensagem}`;
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

function previewImage(event) {
    const input = event.target;
    const previewArea = document.getElementById('previewArea');
    const imgPreview = document.getElementById('imgPreview');
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            imgPreview.src = e.target.result;
            previewArea.style.display = 'block';
        }
        reader.readAsDataURL(input.files[0]);
    }
}

function removerFoto() {
    document.getElementById('foto').value = "";
    document.getElementById('previewArea').style.display = 'none';
    document.getElementById('imgPreview').src = "";
}

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
    } catch (e) { 
        console.error("Erro ao inicializar dados estruturais:", e); 
    }
}

async function carregarAmbientes(id_bloco) {
    const selA = document.getElementById('selectAmbiente');
    if (!id_bloco) {
        selA.disabled = true;
        selA.innerHTML = '<option value="">Selecione o bloco primeiro...</option>';
        return;
    }

    try {
        const res = await fetch(`api/localizacoes.php?acao=listar_ambientes&id_bloco=${id_bloco}`);
        const ambientes = await res.json();
        selA.innerHTML = '<option value="">Selecione a Sala / Local...</option>';
        ambientes.forEach(a => {
            selA.innerHTML += `<option value="${a.id_ambiente}">${a.nome}</option>`;
        });
        selA.disabled = false;
    } catch (err) {
        console.error(err);
    }
}

document.getElementById('formChamado').addEventListener('submit', async (e) => {
    e.preventDefault();
    
    const form = e.target;
    if(!form.checkValidity()) {
        exibirFeedback("Por favor, preencha todos os campos obrigatórios.");
        return;
    }

    const btnSubmit = form.querySelector('button[type="submit"]');
    btnSubmit.disabled = true;
    btnSubmit.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Enviando para a triagem...';

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
            exibirFeedback("Chamado registrado com sucesso! Redirecionando...", "success");
            setTimeout(() => {
                window.location.href = 'solicitante_dashboard.php';
            }, 1500);
        } else {
            exibirFeedback("Não foi possível salvar: " + result.message);
            btnSubmit.disabled = false;
            btnSubmit.innerText = 'ENVIAR SOLICITAÇÃO';
        }
    } catch (err) {
        exibirFeedback("Erro crítico na comunicação com o servidor. Verifique a internet.");
        btnSubmit.disabled = false;
        btnSubmit.innerText = 'ENVIAR SOLICITAÇÃO';
    }
});

iniciar();
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