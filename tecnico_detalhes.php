<?php
session_start();
require_once 'config/database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_perfil'] !== 'tecnico') {
    header("Location: login.php");
    exit;
}

$id = (int)($_GET['id'] ?? 0);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['acao_finalizar'])) {
    header('Content-Type: application/json');
    
    $novo_status = $conn->real_escape_string($_POST['status_atividade'] ?? 'aberto');
    $solucao = $conn->real_escape_string($_POST['solucao_tecnica'] ?? '');
    $tempo = (int)($_POST['tempo_gasto'] ?? 0);
    
    $complemento_data = ($novo_status === 'concluido') ? ", data_fechamento = NOW()" : "";

    $sqlUp = "UPDATE chamados SET 
                status = '$novo_status', 
                solucao_tecnica = '$solucao', 
                tempo_gasto_minutos = $tempo 
                $complemento_data
              WHERE id_chamado = $id";

    if ($conn->query($sqlUp)) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "message" => $conn->error]);
    }
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SGM - Detalhes da Ordem de Serviço</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        :root { --vinho-dark: #7a0101; --vinho-light: #990202; }
        body { background-color: #f0f2f5; font-family: 'Segoe UI', sans-serif; overflow-x: hidden; }
        .navbar { background: linear-gradient(135deg, var(--vinho-dark) 0%, var(--vinho-light) 100%); border-bottom: 4px solid #ffc107; height: 65px; }
        
        .sidebar-col { padding-right: 0; }
        .card-menu { border: none; border-radius: 0 20px 20px 0; overflow: hidden; background: white; min-height: calc(100vh - 65px); }
        
        .perfil-section { background: #fff4f4; padding: 25px 20px; text-align: center; border-bottom: 1px solid #eee; }
        .avatar-circle { width: 65px; height: 65px; background: var(--vinho-light); color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 10px; font-size: 1.6rem; font-weight: bold; border: 3px solid white; box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
        .nav-pills .nav-link { color: #555; font-weight: 600; border-radius: 10px; margin: 8px 15px; padding: 12px 15px; transition: 0.2s; text-align: left; text-decoration: none; display: block; }
        .nav-pills .nav-link:hover { background-color: #f8f9fa; color: var(--vinho-light); }
        
        .card-os { border: none; border-radius: 15px; background: white; border-left: 6px solid #ffc107; }
        .info-label { font-size: 0.75rem; font-weight: 700; color: #6c757d; text-transform: uppercase; display: block; margin-bottom: 2px; }
        .info-value { font-weight: 600; color: #2d3436; margin-bottom: 1rem; }
        .bg-descricao { background-color: #fff4f4; border-left: 3px solid var(--vinho-light); padding: 15px; border-radius: 8px; font-size: 0.9rem; }
        
        .btn-voltar { color: #2d3436; transition: 0.2s; text-decoration: none; display: inline-flex; align-items: center; }
        .btn-voltar:hover { color: var(--vinho-light); transform: scale(1.1); }
        .btn-concluir { background-color: #198754; color: white; font-weight: 700; padding: 12px; border-radius: 10px; border: none; transition: 0.3s; }
        .btn-concluir:hover { background-color: #146c43; }

        /* --- ESTILIZAÇÃO DA FOTO DO ADM/SOLICITANTE --- */
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
        .img-evidencia-wrapper:hover { transform: scale(1.01); }
        .img-evidencia { max-width: 100%; max-height: 300px; object-fit: contain; border-radius: 6px; }
        .modal-blur { backdrop-filter: blur(8px); background-color: rgba(0, 0, 0, 0.4); }
        .img-fullscreen { max-width: 100%; max-height: 70vh; object-fit: contain; border-radius: 8px; }
    </style>
</head>
<body>

<nav class="navbar navbar-dark shadow-sm px-4">
    <div class="container-fluid d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center">
            <i class="bi bi-tools text-warning fs-3 me-2"></i>
            <span class="navbar-brand fw-bold mb-0">SGM TÉCNICO</span>
        </div>
       <button type="button" class="btn btn-outline-light btn-sm rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#modalLogout">
    Sair
</button>
    </div>
</nav>

<div class="container-fluid p-0">
    <div class="row g-0">
        <div class="col-xl-2 col-md-3 sidebar-col">
            <div class="card card-menu shadow-sm">
                <div class="perfil-section">
                    <div class="avatar-circle">
                        <?= strtoupper(substr($_SESSION['user_nome'] ?? 'T', 0, 1)) ?>
                    </div>
                    <h6 class="fw-bold mb-0 text-dark"><?= $_SESSION['user_nome'] ?? 'Técnico' ?></h6>
                    <small class="text-muted text-uppercase" style="font-size: 0.7rem; letter-spacing: 1px;">
                        <i class="bi bi-shield-check text-success"></i> Técnico Autorizado
                    </small>
                </div>

                <div class="nav flex-column nav-pills py-3 flex-grow-1">
                    <a href="tecnico_dashboard.php?aba=pendentes" class="nav-link">
                        <i class="bi bi-list-task me-2"></i> Pendentes
                    </a>
                    <a href="tecnico_dashboard.php?aba=concluidos" class="nav-link">
                        <i class="bi bi-check-circle-fill me-2"></i> Concluídos
                    </a>
                </div>
            </div>
        </div>

        <div class="col-xl-10 col-md-9 p-4">
            <div class="row justify-content-center">
                <div class="col-xxl-9 col-12">
                    <div class="card card-os shadow-sm p-4">
                        
                        <div class="d-flex align-items-center mb-4">
                            <a href="tecnico_dashboard.php" class="btn-voltar fs-3 me-3" title="Voltar">
                                <i class="bi bi-arrow-left-short"></i>
                            </a>
                            <h5 class="fw-bold mb-0 text-dark">Informações do Chamado #<?= $id ?></h5>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <span class="info-label">Localização</span>
                                <div id="txtLocal" class="info-value text-primary">...</div>
                            </div>
                            <div class="col-md-6">
                                <span class="info-label">Prioridade</span>
                                <div id="txtPrioridade" class="info-value text-uppercase">...</div>
                            </div>
                            <div class="col-md-6">
                                <span class="info-label">Solicitante</span>
                                <div id="txtSolicitante" class="info-value">...</div>
                            </div>
                            <div class="col-md-6">
                                <span class="info-label">Status Atual</span>
                                <div id="txtStatus" class="info-value text-uppercase">...</div>
                            </div>
                        </div>

                        <span class="info-label">Descrição do Problema</span>
                        <div id="txtDescricao" class="bg-descricao mb-4 text-dark">...</div>

                        <div id="containerFotoCarregada">
                            <div class="text-center py-2 text-muted small"><div class="spinner-border spinner-border-sm text-secondary me-2"></div> Carregando foto do problema...</div>
                        </div>

                        <hr class="my-4">

                        <h5 class="fw-bold my-4 text-dark" id="tituloForm">
                            <i class="bi bi-pencil-square me-2"></i>Atualizar Progresso do Atendimento
                        </h5>
                        
                        <form id="formFinalizar">
                            <div class="mb-3">
                                <label class="info-label text-primary">Status da Atividade</label>
                                <select id="status_atividade" class="form-select border-0 shadow-sm fw-bold mb-3" style="background:#e6f2ff; color:#0d6efd;" onchange="ajustarCampos()">
                                    <option value="aberto">⏳ PENDENTE / EM ABERTO</option>
                                    <option value="em_execucao">🛠️ JÁ COMECEI A CONSERTAR (Em Execução)</option>
                                    <option value="concluido">✅ CONSERTADO / CONCLUÍDO</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="info-label">Relatório / O que foi realizado?</label>
                                <textarea id="solucao" class="form-control border-0 shadow-sm" rows="4" placeholder="Descreva o andamento ou a solução técnica aplicada..." style="background:#f8f9fa;"></textarea>
                            </div>
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <label class="info-label">Tempo Gasto Atual (Minutos)</label>
                                    <input type="number" id="tempo" class="form-control border-0 shadow-sm" placeholder="Ex: 30" style="background:#f8f9fa;">
                                </div>
                            </div>
                            <button type="submit" id="btnSalvar" class="btn btn-concluir w-100 shadow">
                                <i class="bi bi-save me-2"></i>SALVAR ALTERAÇÕES
                            </button>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade modal-blur" id="modalFoto" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg p-3">
        <div class="modal-content rounded-4 border-0 shadow-lg overflow-hidden">
            <div class="modal-header border-0 bg-light px-4 py-3">
                <h5 class="modal-title fw-bold text-dark m-0"><i class="bi bi-image text-danger me-2"></i>Evidência do Chamado #<?= $id ?></h5>
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
const ID = <?= $id ?>;

// Função para disparar a abertura do modal da foto
function ampliarFoto(url) {
    document.getElementById('imgModal').src = url;
    new bootstrap.Modal(document.getElementById('modalFoto')).show();
}

function ajustarCampos() {
    const status = document.getElementById('status_atividade').value;
    const btn = document.getElementById('btnSalvar');
    
    if (status === 'concluido') {
        btn.className = "btn btn-success w-100 shadow";
        btn.innerHTML = '<i class="bi bi-check-circle me-2"></i>CONCLUIR E FECHAR SERVIÇO';
        document.getElementById('solucao').required = true;
        document.getElementById('tempo').required = true;
    } else if (status === 'em_execucao') {
        btn.className = "btn btn-primary w-100 shadow";
        btn.innerHTML = '<i class="bi bi-save me-2"></i>SALVAR EM EXECUÇÃO';
        document.getElementById('solucao').required = false;
        document.getElementById('tempo').required = false;
    } else {
        btn.className = "btn btn-secondary w-100 shadow";
        btn.innerHTML = '<i class="bi bi-pause-fill me-2"></i>SALVAR COMO PENDENTE';
        document.getElementById('solucao').required = false;
        document.getElementById('tempo').required = false;
    }
}

async function carregar() {
    try {
        const res = await fetch(`api/chamados.php?id=${ID}`);
        if (!res.ok) throw new Error("Erro ao ler dados do banco.");
        const c = await res.json();
        
        document.getElementById('txtLocal').innerText = `${c.bloco_nome || 'Geral'} - ${c.ambiente_nome || ''}`;
        document.getElementById('txtSolicitante').innerText = c.solicitante_nome || 'Não especificado';
        document.getElementById('txtStatus').innerText = (c.status || 'aberto').toUpperCase();
        document.getElementById('txtDescricao').innerText = c.descricao_problema;
        
        const prio = document.getElementById('txtPrioridade');
        prio.innerText = (c.prioridade || 'Média').toUpperCase();
        prio.className = 'info-value ' + (c.prioridade === 'urgente' ? 'text-danger' : 'text-primary');

        if (c.solucao_tecnica) document.getElementById('solucao').value = c.solucao_tecnica;
        if (c.tempo_gasto_minutos) document.getElementById('tempo').value = c.tempo_gasto_minutos;
        
        const statusLimpo = (c.status || 'aberto').toLowerCase().trim();
        if (statusLimpo === 'em_execucao') {
            document.getElementById('status_atividade').value = 'em_execucao';
        } else if (statusLimpo === 'concluido' || statusLimpo === 'fechado') {
            document.getElementById('status_atividade').value = 'concluido';
        } else {
            document.getElementById('status_atividade').value = 'aberto';
        }
        
        ajustarCampos();
        
        if (statusLimpo === 'concluido' || statusLimpo === 'fechado') {
            document.getElementById('solucao').disabled = true;
            document.getElementById('tempo').disabled = true;
            document.getElementById('status_atividade').disabled = true;
            
            document.getElementById('tituloForm').innerHTML = '<i class="bi bi-lock-fill text-secondary me-2"></i>Relatório Técnico (Somente Leitura)';
            document.getElementById('btnSalvar').remove(); 
        }

        // --- CHAMADA DO AJAX PARA BUSCAR A IMAGEM DO CHAMADO ---
        try {
            const resAnexos = await fetch(`api/anexos.php?id_chamado=${ID}`);
            if (resAnexos.ok) {
                const anexos = await resAnexos.json();
                const fotoDiv = document.getElementById('containerFotoCarregada');

                if (anexos && anexos.length > 0 && anexos[0].caminho_arquivo) {
                    const imgUrl = anexos[0].caminho_arquivo;
                    const imgAltUrl = anexos[0].caminho_alternativo;

                    fotoDiv.innerHTML = `
                        <div class="mt-3">
                            <span class="info-label">Evidência Fotográfica (Clique para ampliar)</span>
                            <div class="img-evidencia-wrapper" onclick="ampliarFoto('${imgUrl}')">
                                <img src="${imgUrl}" class="img-evidencia" alt="Evidência" id="imgTecnicoElemento">
                            </div>
                        </div>
                    `;

                    // Mecanismo inteligente de fallback idêntico ao do painel administrativo
                    const imgEl = document.getElementById('imgTecnicoElemento');
                    if (imgEl) {
                        imgEl.onerror = function() {
                            if (this.src.indexOf(imgAltUrl) === -1 && imgAltUrl) {
                                this.src = imgAltUrl;
                                this.parentElement.setAttribute('onclick', `ampliarFoto('${imgAltUrl}')`);
                            } else {
                                this.onerror = null;
                                this.parentElement.innerHTML = '<div class="p-3 text-center text-danger small fw-semibold"><i class="bi bi-exclamation-triangle-fill me-1"></i> Não foi possível abrir o arquivo da imagem nas pastas do servidor.</div>';
                            }
                        };
                    }
                } else {
                    // Limpa o spinner se o chamado não possuir anexos vinculados
                    fotoDiv.innerHTML = '';
                }
            }
        } catch (errFoto) {
            console.error("Erro ao carregar anexo:", errFoto);
        }

    } catch (e) {
        console.error(e);
    }
}

document.getElementById('formFinalizar').onsubmit = async (e) => {
    e.preventDefault();
    const btn = document.getElementById('btnSalvar');
    if(!btn) return;

    btn.disabled = true;
    btn.innerHTML = 'Processando...';

    try {
        const formData = new FormData();
        formData.append('acao_finalizar', '1');
        formData.append('status_atividade', document.getElementById('status_atividade').value);
        formData.append('solucao_tecnica', document.getElementById('solucao').value);
        formData.append('tempo_gasto', document.getElementById('tempo').value);

        const response = await fetch(window.location.href, {
            method: 'POST',
            body: formData
        });

        if (!response.ok) throw new Error("Servidor respondeu com erro.");
        
        const r = await response.json();
        if(r.success) {
            alert("Informações gravadas com sucesso!");
            window.location.href = 'tecnico_dashboard.php';
        } else {
            alert("Erro: " + r.message);
            btn.disabled = false;
            ajustarCampos();
        }
    } catch (error) {
        alert("Falha de comunicação.");
        btn.disabled = false;
        ajustarCampos();
    }
};

carregar();
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