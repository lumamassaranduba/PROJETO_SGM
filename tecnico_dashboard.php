<?php
session_start();
require_once 'config/database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_perfil'] !== 'tecnico') {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$aba = isset($_GET['aba']) ? $_GET['aba'] : 'pendentes';

// Busca a foto e dados atualizados do técnico direto do banco
$query_user = "SELECT nome, foto FROM usuarios WHERE id_usuario = ?";
$stmt_user = $conn->prepare($query_user);
$stmt_user->bind_param("i", $user_id);
$stmt_user->execute();
$res_user = $stmt_user->get_result()->fetch_assoc();
$stmt_user->close();

$nome_usuario = $res_user['nome'] ?? $_SESSION['user_nome'] ?? 'Técnico';

// Verifica se tem foto cadastrada, senão usa a inicial como fallback estável
$tem_foto = !empty($res_user['foto']) && file_exists($res_user['foto']);
$foto_caminho = $tem_foto ? $res_user['foto'] : '';
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SGM - Painel do Técnico</title>
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
        }
        
        .navbar { 
            background: linear-gradient(135deg, var(--vinho-dark) 0%, var(--vinho-light) 100%); 
            border-bottom: 4px solid var(--sgm-gold); 
            height: 65px;
        }

        /* Menu mais fino (290px) sem alterar nenhum estilo */
        .offcanvas {
            width: 290px !important;
            border-right: 4px solid var(--sgm-gold);
        }
        
        /* Estilos dos Cards de Chamados */
        .card-chamado { 
            border: none; 
            border-radius: 16px; 
            border-left: 6px solid #dee2e6; 
            background: white; 
            box-shadow: 0 4px 10px rgba(0,0,0,0.03) !important;
        }
        
        .border-prio-urgente { border-left-color: #dc3545 !important; }
        .border-prio-alta { border-left-color: #fd7e14 !important; }
        .border-prio-media { border-left-color: #0d6efd !important; }
        .border-prio-baixa { border-left-color: #17a2b8 !important; }
        
        .status-badge { 
            padding: 6px 12px; 
            border-radius: 50px; 
            font-size: 0.7rem; 
            font-weight: 700; 
            text-transform: uppercase; 
        }
        
        .prio-urgente { background-color: rgba(220, 53, 69, 0.1); color: #dc3545; }
        .prio-alta { background-color: rgba(253, 126, 20, 0.1); color: #fd7e14; }
        .prio-media { background-color: rgba(13, 110, 253, 0.1); color: #0d6efd; }
        .prio-baixa { background-color: rgba(23, 162, 184, 0.1); color: #17a2b8; }
        
        .status-em-execucao { background-color: #fff3cd; color: #856404; border: 1px solid #ffeeba; }
        .status-aberto { background-color: #e2e3e5; color: #383d41; border: 1px solid #d6d8db; }
        .status-fechado { background-color: #e2e3e5; color: #6c757d; border: 1px solid #dee2e6; }
        
        .icon-circle { 
            width: 36px; 
            height: 36px; 
            background: #fff4f4; 
            color: var(--vinho-light); 
            border-radius: 50%; 
            display: inline-flex; 
            align-items: center; 
            justify-content: center; 
        }
        
        .btn-atender { 
            background-color: var(--vinho-light); 
            color: white; 
            border-radius: 50px; 
            font-weight: 700; 
            border: none; 
            padding: 12px; 
            transition: 0.2s;
        }
        .btn-atender:hover { background-color: var(--vinho-dark); }

        .filtro-wrapper { 
            background: white; 
            border-radius: 16px; 
            padding: 16px; 
            margin-bottom: 20px; 
            border-top: 4px solid var(--sgm-gold);
        }

        .offcanvas-header {
            background-color: var(--vinho-dark);
            color: white;
        }
        .avatar-sidebar-container {
            padding: 20px;
            text-align: center;
            border-bottom: 1px solid #dee2e6;
        }
        .avatar-circle-menu {
            width: 80px;
            height: 80px;
            background-color: var(--vinho-light);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            font-weight: bold;
            margin: 0 auto 10px auto;
            border: 2px solid var(--sgm-gold);
            overflow: hidden;
        }
        .avatar-circle-menu img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .menu-sidebar-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px 20px;
            color: #333;
            text-decoration: none;
            font-weight: 500;
            border-left: 4px solid transparent;
        }
        .menu-sidebar-item:hover {
            background-color: #f8f9fa;
            color: var(--vinho-light);
        }
        .menu-sidebar-item.active {
            background-color: #fff4f4;
            color: var(--vinho-light);
            font-weight: bold;
            border-left-color: var(--vinho-light);
        }
        .link-editar-menu {
            color: var(--vinho-light);
            font-size: 0.85rem;
            font-weight: 600;
            text-decoration: none;
        }
        .link-editar-menu:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-dark shadow-sm px-3">
    <div class="container-fluid d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center">
            <button class="btn btn-link text-white p-0 me-3 shadow-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#menuLateralTecnico" aria-controls="menuLateralTecnico">
                <i class="bi bi-list fs-1"></i>
            </button>
            <i class="bi bi-tools text-warning fs-4 me-2"></i>
            <span class="navbar-brand fw-bold mb-0 fs-5">SGM TÉCNICO</span>
        </div>
        <button type="button" class="btn btn-outline-light btn-sm rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#modalLogout">
            Sair
        </button>
    </div>
</nav>

<div class="offcanvas offcanvas-start" tabindex="-1" id="menuLateralTecnico" aria-labelledby="menuLateralTecnicoLabel">
    <div class="offcanvas-header shadow-sm">
        <h5 class="offcanvas-title fw-bold" id="menuLateralTecnicoLabel">
            <i class="bi bi-sliders me-2 text-warning"></i>Painel de Controle
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body p-0">
        
        <div class="avatar-sidebar-container">
            <div class="avatar-circle-menu">
                <?php if ($tem_foto): ?>
                    <img src="<?= htmlspecialchars($foto_caminho) ?>" alt="Foto de Perfil">
                <?php else: ?>
                    <?= strtoupper(substr($nome_usuario, 0, 1)) ?>
                <?php endif; ?>
            </div>
            <h6 class="fw-bold mb-1 text-dark"><?= htmlspecialchars($nome_usuario) ?></h6>
            <span class="badge bg-dark-subtle text-dark-emphasis text-uppercase rounded-pill px-2 py-1 mb-2" style="font-size:0.65rem;">Técnico</span>
            <br>
            <a href="perfil.php" class="link-editar-menu">
                <i class="bi bi-pencil-square me-1"></i>Editar Perfil
            </a>
        </div>

        <div class="py-2">
            <a href="?aba=pendentes" class="menu-sidebar-item <?= $aba === 'pendentes' ? 'active' : '' ?>">
                <i class="bi bi-list-ul"></i> Chamados Pendentes
            </a>
            <a href="?aba=concluidos" class="menu-sidebar-item <?= $aba === 'concluidos' ? 'active' : '' ?>">
                <i class="bi bi-check-circle"></i> Histórico Concluído
            </a>
            <a href="?aba=fechados" class="menu-sidebar-item <?= $aba === 'fechados' ? 'active' : '' ?>">
                <i class="bi bi-archive"></i> Chamados Fechados
            </a>
        </div>

    </div>
</div>

<div class="container-fluid p-3 p-md-4">
    <div class="filtro-wrapper">
        <div class="row g-2 align-items-center">
            <div class="col-12 col-lg-3 text-center text-lg-start">
                <h5 class="fw-bold mb-0 text-dark fs-5">
                    <?php 
                        if ($aba === 'pendentes') echo 'Tarefas Pendentes';
                        elseif ($aba === 'concluidos') echo 'Histórico Concluído';
                        else echo 'Chamados Fechados';
                    ?>
                </h5>
            </div>
            <div class="col-6 col-lg-3">
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-light text-muted d-none d-sm-flex"><i class="bi bi-exclamation-triangle"></i></span>
                    <select id="filtroImportancia" class="form-select py-2" onchange="filtrarEExibir()">
                        <option value="">Importâncias</option>
                        <option value="urgente">Urgente</option>
                        <option value="alta">Alta</option>
                        <option value="media">Média</option>
                        <option value="baixa">Baixa</option>
                    </select>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-light text-muted d-none d-sm-flex"><i class="bi bi-calendar-event"></i></span>
                    <input type="date" id="filtroData" class="form-control py-2" onchange="filtrarEExibir()">
                </div>
            </div>
            <div class="col-12 col-lg-3 text-center text-lg-end mt-2 mt-lg-0">
                <button onclick="limparFiltros()" class="btn btn-sm btn-outline-secondary rounded-pill w-100 w-lg-auto px-3 py-2">
                    <i class="bi bi-arrow-clockwise"></i> Limpar Filtros
                </button>
            </div>
        </div>
    </div>

    <div id="listaChamados" class="row g-3"></div>
</div>

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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
const ABA_ATUAL = '<?= $aba ?>';
let cacheChamados = []; 

async function carregarTarefas() {
    try {
        const res = await fetch(`api/chamados.php`);
        const todosChamados = await res.json();
        
        cacheChamados = todosChamados.filter(c => {
            const st = (c.status || 'aberto').toLowerCase().trim();
            if (ABA_ATUAL === 'pendentes') {
                return st === 'aberto' || st === 'em_execucao';
            } else if (ABA_ATUAL === 'concluidos') {
                return st === 'concluido';
            } else {
                return st === 'fechado';
            }
        });

        filtrarEExibir();
    } catch (e) { 
        console.error("Erro ao carregar os chamados:", e); 
    }
}

function filtrarEExibir() {
    const lista = document.getElementById('listaChamados');
    const impSelecionada = document.getElementById('filtroImportancia').value.toLowerCase();
    const dataSelecionada = document.getElementById('filtroData').value;

    let chamadosFiltrados = cacheChamados.filter(c => {
        const prioridadeChamado = (c.prioridade || 'media').toLowerCase().trim();
        
        let dataLimiteChamado = '';
        if (c.data_previsao_conclusao) {
            dataLimiteChamado = c.data_previsao_conclusao.split(' ')[0].trim();
        }

        const bateImportancia = impSelecionada === "" || prioridadeChamado === impSelecionada;
        const bateData = dataSelecionada === "" || dataLimiteChamado === dataSelecionada;

        return bateImportancia && bateData;
    });

    if (chamadosFiltrados.length === 0) {
        lista.innerHTML = `
            <div class="col-12 text-center mt-2 py-5 bg-white rounded-4 shadow-sm px-3">
                <i class="bi bi-funnel text-muted mb-2" style="font-size: 2.5rem;"></i>
                <p class="text-muted small m-0 fw-bold">Nenhum chamado corresponde aos filtros aplicados.</p>
            </div>`;
        return;
    }

    lista.innerHTML = chamadosFiltrados.map(c => {
        const prioridadeLimpa = (c.prioridade || 'media').toLowerCase().trim();
        
        let dataFormatada = "Não definida";
        if(c.data_previsao_conclusao) {
            const partes = c.data_previsao_conclusao.split(' ')[0].split('-');
            if(partes.length === 3) dataFormatada = `${partes[2]}/${partes[1]}/${partes[0]}`;
        }

        const statusLimpo = (c.status || 'aberto').toLowerCase().trim();
        let badgeStatusClasse = 'status-aberto';
        if (statusLimpo === 'em_execucao') badgeStatusClasse = 'status-em-execucao';
        else if (statusLimpo === 'fechado') badgeStatusClasse = 'status-fechado';
        else if (statusLimpo === 'concluido') badgeStatusClasse = 'bg-success text-white';

        const textoBotao = ABA_ATUAL === 'pendentes' ? `Atender #${c.id_chamado}` : `Ver Detalhes`;
        const btnClasse = ABA_ATUAL === 'pendentes' ? `btn-atender` : `btn-outline-secondary rounded-pill py-2`;

        // Verifica sinalizador de chamado reaberto (verifica se campo existe e é verdadeiro ou string indicativa)
        const ehReaberto = (c.reaberto == 1 || c.reaberto == 'true' || (c.historico_status && c.historico_status.toLowerCase().includes('reaberto')));
        const badgeReaberto = ehReaberto ? `<span class="badge bg-warning text-dark px-2 py-1 text-uppercase fw-bold shadow-sm" style="font-size: 0.65rem;"><i class="bi bi-arrow-counterclockwise"></i> Reaberto</span>` : '';

        return `
        <div class="col-12 col-md-6 col-xxl-4">
            <div class="card card-chamado border-prio-${prioridadeLimpa} h-100">
                <div class="card-body d-flex flex-column p-3">
                    <div class="d-flex justify-content-between mb-2 align-items-center flex-wrap gap-1">
                        <span class="status-badge ${badgeStatusClasse}">${(c.status || 'aberto').replace('_', ' ')}</span>
                        <div class="d-flex gap-1 align-items-center">
                            ${badgeReaberto}
                            <span class="status-badge prio-${prioridadeLimpa}"><i class="bi bi-exclamation-circle me-1"></i>${c.prioridade || 'Média'}</span>
                        </div>
                    </div>
                    
                    <div class="d-flex align-items-center mb-2 mt-1">
                        <div class="icon-circle me-2 flex-shrink-0"><i class="bi bi-geo-alt-fill"></i></div>
                        <div class="overflow-hidden">
                            <h6 class="fw-bold mb-0 text-dark text-truncate small" style="font-size:0.95rem;">${c.bloco_nome || 'Geral'}</h6>
                            <small class="text-muted d-block text-truncate">${c.ambiente_nome || ''}</small>
                        </div>
                    </div>

                    <div class="bg-light p-2 rounded-3 mb-3 flex-grow-1 border-start border-3 border-secondary">
                        <p class="small mb-0 text-dark text-break" style="display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden; line-height:1.4;">
                            ${c.descricao_problema}
                        </p>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-3 px-1">
                        <span class="text-muted style-text" style="font-size:0.8rem;"><i class="bi bi-clock-history me-1"></i>Prazo Limite:</span>
                        <span class="badge bg-light text-dark border fw-bold px-2 py-1">${dataFormatada}</span>
                    </div>

                    <button onclick="location.href='tecnico_minhas_tarefas.php?id=${c.id_chamado}'" class="btn ${btnClasse} w-100 shadow-sm">
                        ${textoBotao}
                    </button>
                </div>
            </div>
        </div>`;
    }).join('');
}

function limparFiltros() {
    document.getElementById('filtroImportancia').value = "";
    document.getElementById('filtroData').value = "";
    filtrarEExibir();
}

carregarTarefas();
</script>
</body>
</html>