<?php
session_start();
require_once 'config/database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_perfil'] !== 'tecnico') {
    header("Location: login.php");
    exit;
}

$id = (int)($_GET['id'] ?? 0);

// --- BUSCA A FOTO DIRETAMENTE DO BANCO DE DADOS AQUI (SEM DEPENDER DE API) ---
$foto_url = '';
$sql_anexo = "SELECT caminho_arquivo FROM chamados_anexos WHERE id_chamado = $id LIMIT 1";
$res_anexo = $conn->query($sql_anexo);
if ($res_anexo && $res_anexo->num_rows > 0) {
    $row_anexo = $res_anexo->fetch_assoc();
    $foto_url = $row_anexo['caminho_arquivo'];
}

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
        /* Identidade Visual Padronizada SGM */
        :root { 
            --vinho-dark: #7a0101; 
            --vinho-light: #990202; 
            --sgm-gold: #ffc107;
        }
        
        body { 
            background-color: #f0f2f5; 
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif; 
            overflow-x: hidden; 
        }
        
        /* Navbar Padrão SGM TÉCNICO */
        .navbar { 
            background: linear-gradient(135deg, var(--vinho-dark) 0%, var(--vinho-light) 100%); 
            border-bottom: 4px solid var(--sgm-gold); 
            height: 65px; 
        }
        
        /* Sidebar Adaptável (Desktop vs Mobile) */
        .card-menu { 
            border: none; 
            background: white; 
        }
        
        .perfil-section { 
            background: #fff4f4; 
            padding: 20px; 
            text-align: center; 
            border-bottom: 1px solid #dee2e6; 
        }
        
        .avatar-circle { 
            width: 55px; 
            height: 55px; 
            background: var(--vinho-light); 
            color: white; 
            border-radius: 50%; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            margin: 0 auto 8px; 
            font-size: 1.4rem; 
            font-weight: bold; 
            border: 3px solid white; 
            box-shadow: 0 3px 6px rgba(0,0,0,0.08); 
        }
        
        .nav-pills .nav-link { 
            color: #555; 
            font-weight: 600; 
            border-radius: 12px; 
            margin: 5px 10px; 
            padding: 12px 16px; 
            transition: 0.2s; 
            text-decoration: none; 
            display: flex;
            align-items: center;
        }
        .nav-pills .nav-link:hover { 
            background-color: #f8f9fa; 
            color: var(--vinho-light); 
        }
        
        /* Cartão OS Principal */
        .card-os { 
            border: none; 
            border-radius: 16px; 
            background: white; 
            border-top: 4px solid var(--sgm-gold);
            box-shadow: 0 4px 12px rgba(0,0,0,0.03) !important;
        }
        
        .info-label { 
            font-size: 0.72rem; 
            font-weight: 700; 
            color: #6c757d; 
            text-transform: uppercase; 
            display: block; 
            margin-bottom: 4px; 
            letter-spacing: 0.5px;
        }
        
        .info-value { 
            font-weight: 600; 
            color: #2d3436; 
            margin-bottom: 1.25rem; 
            font-size: 1.05rem;
        }
        
        .bg-descricao { 
            background-color: #fff4f4; 
            border-left: 4px solid var(--vinho-light); 
            padding: 16px; 
            border-radius: 12px; 
            font-size: 0.92rem; 
            line-height: 1.5;
        }
        
        .btn-voltar { 
            color: #2d3436; 
            transition: 0.2s; 
            text-decoration: none; 
            display: inline-flex; 
            align-items: center; 
        }
        .btn-voltar:hover { color: var(--vinho-light); }
        
        .btn-concluir { 
            background-color: #198754; 
            color: white; 
            font-weight: 700; 
            padding: 14px; 
            border-radius: 50px; 
            border: none; 
            transition: 0.2s; 
            font-size: 0.95rem;
        }
        .btn-concluir:hover { background-color: #146c43; }

        /* Contêiner de imagem seguro */
        .container-foto-segura {
            background-color: #1a1a1a;
            border-radius: 12px;
            padding: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            max-width: 100%;
            box-shadow: inset 0 0 15px rgba(0,0,0,0.5);
            margin-top: 6px;
        }
        
        .foto-preview-elemento {
            max-width: 100%;
            max-height: 280px;
            object-fit: contain;
            border-radius: 8px;
            cursor: pointer;
        }
        
        .modal-blur { backdrop-filter: blur(8px); background-color: rgba(0, 0, 0, 0.5); }
        .img-fullscreen { max-width: 100%; max-height: 65vh; object-fit: contain; border-radius: 8px; }

        /* Customização Responsiva para Dispositivos Móveis */
        @media (max-width: 767.98px) {
            .card-menu {
                min-height: auto !important;
                border-radius: 0 0 16px 16px;
                margin-bottom: 15px;
                box-shadow: 0 4px 10px rgba(0,0,0,0.05);
            }
            .sidebar-col {
                padding-right: calc(var(--bs-gutter-x) * .5) !important;
            }
            .perfil-section {
                display: flex;
                align-items: center;
                text-align: left;
                padding: 12px 15px;
                border-bottom: 1px solid #dee2e6;
            }
            .avatar-circle {
                margin: 0 12px 0 0;
                width: 45px;
                height: 45px;
                font-size: 1.1rem;
            }
            .nav-tabs-mobile {
                display: flex !important;
                flex-direction: row !important;
                justify-content: space-around;
                padding: 5px !important;
            }
            .nav-tabs-mobile .nav-link {
                margin: 0 !important;
                flex: 1;
                justify-content: center;
                font-size: 0.85rem;
                padding: 10px 5px;
                border-radius: 8px;
            }
            .info-value {
                margin-bottom: 0.9rem;
                font-size: 0.95rem;
            }
        }

        @media (min-width: 768px) {
            .card-menu {
                min-height: calc(100vh - 65px);
                border-radius: 0 20px 20px 0;
            }
            .sidebar-col {
                padding-right: 0;
            }
        }
    </style>
</head>
<body>

<nav class="navbar navbar-dark shadow-sm px-3">
    <div class="container-fluid d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center">
            <i class="bi bi-tools text-warning fs-4 me-2"></i>
            <span class="navbar-brand fw-bold mb-0 fs-5">SGM TÉCNICO</span>
        </div>
       <button type="button" class="btn btn-outline-light btn-sm rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#modalLogout">
    Sair
</button>
    </div>
</nav>

<div class="container-fluid p-0">
    <div class="row g-0">
        <div class="col-12 col-md-3 col-xl-2 sidebar-col">
            <div class="card card-menu shadow-sm">
                <div class="perfil-section">
                    <div class="avatar-circle">
                        <?= strtoupper(substr($_SESSION['user_nome'] ?? 'T', 0, 1)) ?>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-0 text-dark"><?= $_SESSION['user_nome'] ?? 'Técnico' ?></h6>
                        <small class="text-muted text-uppercase d-block" style="font-size: 0.65rem; letter-spacing: 0.5px;">
                            <i class="bi bi-shield-check text-success"></i> Autorizado
                        </small>
                    </div>
                </div>

                <div class="nav flex-column nav-pills py-2 nav-tabs-mobile">
                    <a href="tecnico_dashboard.php?aba=pendentes" class="nav-link">
                        <i class="bi bi-list-task me-1 me-md-2"></i> Pendentes
                    </a>
                    <a href="tecnico_dashboard.php?aba=concluidos" class="nav-link">
                        <i class="bi bi-check-circle-fill me-1 me-md-2"></i> Concluídos
                    </a>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-9 col-xl-10 p-3 p-md-4">
            <div class="row justify-content-center">
                <div class="col-12 col-xl-10 col-xxl-9">
                    <div class="card card-os p-3 p-md-4">
                        
                        <div class="d-flex align-items-center mb-3 mb-md-4">
                            <a href="tecnico_dashboard.php" class="btn-voltar fs-2 me-2" title="Voltar">
                                <i class="bi bi-arrow-left-short"></i>
                            </a>
                            <h5 class="fw-bold mb-0 text-dark fs-5">Chamado #<?= $id ?></h5>
                        </div>

                        <div class="row row-cols-1 row-cols-sm-2 g-1">
                            <div class="col">
                                <span class="info-label">Localização</span>
                                <div id="txtLocal" class="info-value text-primary text-truncate">...</div>
                            </div>
                            <div class="col">
                                <span class="info-label">Prioridade</span>
                                <div id="txtPrioridade" class="info-value text-uppercase">...</div>
                            </div>
                            <div class="col">
                                <span class="info-label">Solicitante</span>
                                <div id="txtSolicitante" class="info-value text-truncate">...</div>
                            </div>
                            <div class="col">
                                <span class="info-label">Status Atual</span>
                                <div id="txtStatus" class="info-value text-uppercase">...</div>
                            </div>
                        </div>

                        <div class="mb-3 mt-1">
                            <span class="info-label">Descrição do Problema</span>
                            <div id="txtDescricao" class="bg-descricao text-dark text-break">...</div>
                        </div>

                        <?php if (!empty($foto_url)): ?>
                            <div class="mb-4" id="blocoFotoEvidencia">
                                <span class="info-label">Evidência Anexada (Toque para ampliar)</span>
                                <div class="container-foto-segura">
                                    <img src="<?= $foto_url ?>" 
                                         class="foto-preview-elemento" 
                                         alt="Foto do problema" 
                                         onclick="ampliarFoto(this.src)"
                                         onerror="this.onerror=null; let altPath = this.src.replace('/assets/', '/'); if(this.src !== altPath){ this.src = altPath; } else { this.parentElement.innerHTML = '<div class=\'text-white small p-2 text-center fw-semibold\'><i class=\'bi bi-exclamation-octagon text-warning me-1\'></i> Imagem indisponível no servidor.</div>'; }">
                                </div>
                            </div>
                        <?php else: ?>
                            <div class="text-muted small mb-4 bg-light p-2 rounded-3 text-center"><i class="bi bi-image-alt me-1"></i> Nenhuma evidência fotográfica anexada.</div>
                        <?php endif; ?>

                        <hr class="my-3 text-muted opacity-25">

                        <h5 class="fw-bold my-3 text-dark fs-6" id="tituloForm">
                            <i class="bi bi-pencil-square me-2 text-secondary"></i>Atualizar Progresso
                        </h5>
                        
                        <form id="formFinalizar">
                            <div class="mb-3">
                                <label class="info-label text-primary">Status da Atividade</label>
                                <select id="status_atividade" class="form-select border-0 shadow-sm fw-bold p-2.5" style="background:#e6f2ff; color:#0d6efd; border-radius:10px;" onchange="ajustarCampos()">
                                    <option value="aberto">⏳ PENDENTE / EM ABERTO</option>
                                    <option value="em_execucao">🛠️ EM EXECUÇÃO (Comecei a consertar)</option>
                                    <option value="concluido">✅ CONCLUÍDO / CONSERTADO</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="info-label">Relatório / O que foi realizado?</label>
                                <textarea id="solucao" class="form-control border-0 shadow-sm p-3" rows="4" placeholder="Descreva de forma clara as ações técnicas tomadas..." style="background:#f8f9fa; border-radius:12px;"></textarea>
                            </div>
                            
                            <div class="row mb-4">
                                <div class="col-12 col-sm-6">
                                    <label class="info-label">Tempo Gasto (Em Minutos)</label>
                                    <input type="number" id="tempo" class="form-control border-0 shadow-sm p-2.5" placeholder="Ex: 45" style="background:#f8f9fa; border-radius:10px;">
                                </div>
                            </div>
                            
                            <button type="submit" id="btnSalvar" class="btn btn-concluir w-100 shadow-sm">
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
    <div class="modal-dialog modal-dialog-centered modal-lg p-2">
        <div class="modal-content rounded-4 border-0 shadow-lg overflow-hidden">
            <div class="modal-header border-0 bg-light px-3 py-2.5">
                <h6 class="modal-title fw-bold text-dark m-0"><i class="bi bi-image text-danger me-2"></i>Evidência Ampliada</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-3 bg-dark text-center d-flex justify-content-center align-items-center" style="box-shadow: inset 0 0 25px rgba(0,0,0,0.8);">
                <img id="imgModal" src="" class="img-fullscreen" alt="Evidência ampliada">
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
const ID = <?= $id ?>;

function ampliarFoto(url) {
    document.getElementById('imgModal').src = url;
    new bootstrap.Modal(document.getElementById('modalFoto')).show();
}

function ajustarCampos() {
    const status = document.getElementById('status_atividade').value;
    const btn = document.getElementById('btnSalvar');
    if(!btn) return;
    
    if (status === 'concluido') {
        btn.className = "btn btn-success w-100 shadow-sm fw-bold py-3 rounded-pill";
        btn.innerHTML = '<i class="bi bi-check-circle me-2"></i>CONCLUIR E FECHAR SERVIÇO';
        document.getElementById('solucao').required = true;
        document.getElementById('tempo').required = true;
    } else if (status === 'em_execucao') {
        btn.className = "btn btn-primary w-100 shadow-sm fw-bold py-3 rounded-pill";
        btn.innerHTML = '<i class="bi bi-save me-2"></i>SALVAR EM EXECUÇÃO';
        document.getElementById('solucao').required = false;
        document.getElementById('tempo').required = false;
    } else {
        btn.className = "btn btn-secondary w-100 shadow-sm fw-bold py-3 rounded-pill";
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
        document.getElementById('txtStatus').innerText = (c.status || 'aberto').toUpperCase().replace('_', ' ');
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