<?php
session_start();
require_once 'config/database.php'; 

if (!isset($_SESSION['user_id']) || $_SESSION['user_perfil'] !== 'gestor') {
    header("Location: login.php");
    exit;
}
$id = (int)($_GET['id'] ?? 0);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SGM - Detalhes do Chamado</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        :root {
            --vinho-dark: #7a0101;
            --vinho-light: #990202;
        }
        body { 
            background-color: #f8f9fa; 
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif; 
        }
        .navbar-custom { 
            background: linear-gradient(135deg, var(--vinho-dark) 0%, var(--vinho-light) 100%);
            border-bottom: 4px solid #ffc107;
        }
        .info-label { font-size: 0.75rem; text-transform: uppercase; font-weight: 700; color: #6c757d; letter-spacing: 0.5px; }
        .info-value { font-weight: 600; color: #2d3436; margin-bottom: 0.75rem; }
        .card { border: none; border-radius: 16px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
        .card-header-custom {
            font-weight: 700;
            text-transform: uppercase;
            font-size: 0.8rem;
            letter-spacing: 1px;
            background-color: #fafafa;
            border-bottom: 1px solid #edf2f7;
            padding: 16px;
        }
        .img-evidencia-wrapper {
            background-color: #111;
            border-radius: 12px;
            padding: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            box-shadow: inset 0 0 15px rgba(0,0,0,0.5);
            max-width: 100%;
            cursor: pointer;
            transition: transform 0.2s;
        }
        .img-evidencia-wrapper:hover {
            transform: scale(1.01);
        }
        .img-evidencia {
            max-width: 100%;
            max-height: 350px;
            object-fit: contain;
            border-radius: 6px;
        }
        .modal-blur {
            backdrop-filter: blur(8px);
            background-color: rgba(0, 0, 0, 0.4);
        }
        .img-fullscreen {
            max-width: 100%;
            max-height: 70vh;
            object-fit: contain;
            border-radius: 8px;
        }
    </style>
</head>
<body>

<script>
    const CHAMADO_ID = <?= json_encode($id) ?>;
</script>

<nav class="navbar navbar-custom shadow-sm mb-4 mb-md-5 py-2">
    <div class="container d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center">
            <a href="gestor_chamados.php" class="btn btn-link text-light text-decoration-none p-0 me-3">
                <i class="bi bi-arrow-left-circle-fill fs-4"></i>
            </a>
            <a class="navbar-brand text-light fw-bold m-0 fs-5" href="gestor_dashboard.php">SGM Admin</a>
        </div>
    </div>
</nav>

<div class="container pb-5 px-3">
    <div class="row g-4">
        <div class="col-md-7">
            <div class="card mb-4">
                <div class="card-header-custom text-dark d-flex align-items-center">
                    <i class="bi bi-file-earmark-text text-danger me-2 fs-5"></i> Dados da Solicitação
                </div>
                <div id="detalhesChamado" class="card-body p-4">
                    <div class="text-center p-4"><div class="spinner-border text-danger" role="status"></div><br><span class="text-muted small mt-2 d-inline-block">Carregando...</span></div>
                </div>
            </div>
        </div>

        <div class="col-md-5">
            <div class="card" id="cardAcaoDireita">
                <div class="card-header-custom text-dark d-flex align-items-center" id="tituloAcao">
                    <i class="bi bi-sliders text-danger me-2 fs-5"></i> Triagem e Atribuição
                </div>
                <div class="card-body p-4" id="conteudoAcao">
                    <div class="text-center text-muted py-3">Carregando opções...</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade modal-blur" id="modalFoto" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg p-3">
        <div class="modal-content rounded-4 border-0 shadow-lg overflow-hidden">
            <div class="modal-header border-0 bg-light px-4 py-3">
                <h5 class="modal-title fw-bold text-dark m-0" id="modalTituloTexto"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4 bg-dark text-center d-flex justify-content-center align-items-center" style="box-shadow: inset 0 0 30px rgba(0,0,0,0.8);">
                <img id="imgModal" src="" class="img-fullscreen" alt="Evidência ampliada">
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
function ampliarFoto(url) {
    document.getElementById('imgModal').src = url;
    // Corrigido usando crases de interpolação no título do modal
    document.getElementById('modalTituloTexto').innerHTML = `<i class="bi bi-image text-danger me-2"></i>Evidência do Chamado #${CHAMADO_ID}`;
    new bootstrap.Modal(document.getElementById('modalFoto')).show();
}

async function finalizarChamado(acao) {
    if(!confirm(`Deseja ${acao} este chamado?`)) return;
    const res = await fetch('api/gestor_acoes.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({ id_chamado: CHAMADO_ID, acao: acao })
    });
    const data = await res.json();
    if(data.success) window.location.href = 'gestor_chamados.php';
}

async function carregarDados() {
    try {
        const [resTec, resChamado] = await Promise.all([
            fetch('api/usuarios.php'),
            fetch(`api/chamados.php?id=${CHAMADO_ID}`)
        ]);

        const tecnicos = await resTec.json();
        const c = await resChamado.json();

        const coresStatus = {
            'aberto': 'bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25',
            'em_execucao': 'bg-warning bg-opacity-10 text-dark border border-warning border-opacity-25',
            'concluido': 'bg-success bg-opacity-10 text-success border border-success border-opacity-25',
            'fechado': 'bg-dark bg-opacity-10 text-dark border border-dark border-opacity-25'
        };
        const statusTexto = c.status ? c.status.replace('_', ' ') : 'aberto';

        document.getElementById('detalhesChamado').innerHTML = `
            <div class="mb-3">
                <span class="badge ${coresStatus[c.status] || 'bg-secondary'} rounded-pill px-3 py-2 fw-bold text-uppercase" style="font-size:0.7rem; letter-spacing:0.5px;">
                    ${statusTexto}
                </span>
            </div>
            <div class="mb-3">
                <div class="info-label">Localização</div>
                <div class="info-value fs-5 text-dark fw-bold">${c.bloco_nome || 'Bloco'} <span class="text-muted fw-normal">|</span> ${c.ambiente_nome || 'Geral'}</div>
            </div>
            <div class="mb-3">
                <div class="info-label">Solicitante</div>
                <div class="info-value fw-semibold text-secondary">${c.solicitante_nome || 'Não identificado'}</div>
            </div>
            <div class="mb-1">
                <div class="info-label">Descrição do Problema</div>
                <div class="info-value bg-light p-3 rounded-3 fw-medium text-dark border" style="white-space: pre-wrap; font-size: 0.95rem; line-height: 1.5;">${c.descricao_problema}</div>
            </div>
            <div id="containerFotoCarregada">
                <div class="text-center py-3 text-muted small"><div class="spinner-border spinner-border-sm text-secondary me-2"></div> Buscando foto...</div>
            </div>
        `;

        const divAcao = document.getElementById('conteudoAcao');

        if (c.status === 'concluido') {
            document.getElementById('tituloAcao').innerHTML = `<i class="bi bi-check2-circle text-success me-2 fs-5"></i> Validar Finalização`;
            divAcao.innerHTML = `
                <p class="text-muted small mb-4 fw-medium">O técnico marcou este serviço como finalizado. Analise a evidência ao lado para aprovar o fechamento ou solicitar retrabalho.</p>
                <button onclick="finalizarChamado('fechar')" class="btn btn-success w-100 mb-3 py-2 fw-bold rounded-pill shadow-sm d-flex align-items-center justify-content-center gap-2">
                    <i class="bi bi-lock-fill"></i> Fechar Chamado
                </button>
                <button onclick="finalizarChamado('reabrir')" class="btn btn-outline-danger w-100 py-2 fw-bold rounded-pill d-flex align-items-center justify-content-center gap-2">
                    <i class="bi bi-arrow-counterclockwise"></i> Reabrir Chamado (Retrabalho)
                </button>
            `;
        } else {
            let options = tecnicos.map(t => `<option value="${t.id_usuario}">${t.nome}</option>`).join('');
            divAcao.innerHTML = `
                <form id="formAtribuir">
                    <div class="mb-3">
                        <label class="info-label mb-2">Responsável Técnico</label>
                        <select id="selectTecnico" class="form-select rounded-3 p-2 fw-medium" required>
                            <option value="">Selecione um técnico...</option>
                            ${options}
                        </select>
                    </div>
                    <div class="row g-3">
                        <div class="col-6">
                            <label class="info-label mb-2">Nível de Prioridade</label>
                            <select id="prioridade" class="form-select rounded-3 p-2 fw-medium">
                                <option value="baixa">Baixa</option>
                                <option value="media" selected>Média</option>
                                <option value="alta">Alta</option>
                                <option value="urgente">Urgente</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="info-label mb-2">Previsão de Entrega</label>
                            <input type="date" id="data_prevista" class="form-control rounded-3 p-2 fw-medium" required>
                        </div>
                    </div>
                    <button type="submit" class="btn w-100 text-white fw-bold mt-4 py-2 rounded-pill shadow-sm border-0" style="background-color: var(--vinho-light);">
                        <i class="bi bi-check-lg me-1"></i> Confirmar Atribuição
                    </button>
                </form>
            `;
            
            document.getElementById('formAtribuir').onsubmit = async (e) => {
                e.preventDefault();
                const payload = {
                    id_chamado: CHAMADO_ID,
                    id_tecnico: document.getElementById('selectTecnico').value,
                    prioridade: document.getElementById('prioridade').value,
                    data_prevista: document.getElementById('data_prevista').value
                };
                const res = await fetch('api/atribuir_chamado.php', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify(payload)
                });
                const data = await res.json();
                if(data.success) { 
                    alert("Chamado atribuído com sucesso!"); 
                    window.location.href = 'gestor_chamados.php'; 
                }
            };
        }

        try {
            const resAnexos = await fetch(`api/anexos.php?id_chamado=${CHAMADO_ID}`);
            if (resAnexos.ok) {
                const anexos = await resAnexos.json();
                const fotoDiv = document.getElementById('containerFotoCarregada');

                if (anexos && anexos.length > 0 && anexos[0].caminho_arquivo) {
                    const imgUrl = anexos[0].caminho_arquivo;
                    const imgAltUrl = anexos[0].caminho_alternativo;

                    fotoDiv.innerHTML = `
                        <div class="mt-4">
                            <div class="info-label mb-2">Evidência Anexada (Clique para ampliar)</div>
                            <div class="img-evidencia-wrapper" onclick="ampliarFoto('${imgUrl}')">
                                <img src="${imgUrl}" class="img-evidencia" alt="Foto do problema" id="imagemEvidenciaElemento">
                            </div>
                        </div>
                    `;

                    // Sistema inteligente de fallback: Se der erro no caminho normal, tenta o alternativo
                    const imgEl = document.getElementById('imagemEvidenciaElemento');
                    if (imgEl) {
                        imgEl.onerror = function() {
                            if (this.src.indexOf(imgAltUrl) === -1) {
                                this.src = imgAltUrl;
                                // Atualiza o clique do modal também
                                this.parentElement.setAttribute('onclick', `ampliarFoto('${imgAltUrl}')`);
                            } else {
                                this.onerror = null;
                                this.parentElement.innerHTML = '<div class="p-3 text-center text-danger small fw-semibold"><i class="bi bi-exclamation-triangle-fill me-1"></i> Erro ao carregar arquivo de imagem em ambas as pastas.</div>';
                            }
                        };
                    }
                } else {
                    fotoDiv.innerHTML = `
                        <div class="mt-4">
                            <div class="info-label mb-2">Evidência Anexada</div>
                            <div class="bg-light p-3 rounded-3 text-center border">
                                <i class="bi bi-image text-muted opacity-50 fs-4 d-block mb-1"></i>
                                <span class="text-muted small fw-medium">Nenhuma foto foi anexada a este chamado.</span>
                            </div>
                        </div>
                    `;
                }
            }
        } catch (errFoto) {
            console.error("Erro ao carregar os anexos:", errFoto);
        }

    } catch (err) { 
        console.error("Erro fatal no carregamento dos detalhes:", err); 
    }
}

carregarDados();
</script>
</body>
</html>