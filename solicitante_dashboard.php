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
    <title>SGM - Painel do Solicitante</title>

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
        .main-card { 
            border: none; 
            border-radius: 16px; 
            box-shadow: 0 4px 12px rgba(0,0,0,0.03) !important; 
            background: white; 
        }
        .table-modern {
            margin-bottom: 0;
        }
        .table-modern thead th { 
            font-weight: 700; 
            text-transform: uppercase;
            font-size: 0.72rem;
            letter-spacing: 0.8px;
            color: #6c757d; 
            border-bottom: 2px solid #edf2f7; 
            padding: 16px;
        }
        .table-modern tbody td {
            padding: 16px;
            vertical-align: middle;
            color: #2d3748;
        }
        .badge-status { 
            font-weight: 700; 
            padding: 0.6em 1em; 
            border-radius: 50px; 
            text-transform: uppercase; 
            font-size: 0.68rem; 
            letter-spacing: 0.5px;
            display: inline-block;
        }
        .img-thumb-preview { 
            width: 48px; 
            height: 48px; 
            object-fit: cover; 
            border-radius: 12px; 
            transition: transform 0.2s, box-shadow 0.2s; 
            border: 1px solid #edf2f7; 
        }
        .img-thumb-preview:hover { 
            transform: scale(1.08); 
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            cursor: pointer; 
        }

        .modal-blur {
            backdrop-filter: blur(8px);
            background-color: rgba(0, 0, 0, 0.5);
        }
        .modal-evidencia .modal-content {
            background: #ffffff;
            border: none;
            box-shadow: 0 20px 40px rgba(0,0,0,0.2);
        }
        .modal-evidencia .modal-header {
            border-bottom: 1px solid #edf2f7;
            background-color: #fafafa;
        }
        .img-container-modal {
            background-color: #111;
            border-radius: 12px;
            padding: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            box-shadow: inset 0 0 20px rgba(0,0,0,0.6);
        }
        .img-fullscreen {
            max-width: 100%;
            max-height: 65vh;
            object-fit: contain;
            border-radius: 8px;
        }

        /* Estrutura de visualização em Cards para Mobile */
        .mobile-card-view {
            display: none;
        }
        .os-mobile-card {
            background: white;
            border-radius: 14px;
            border: none;
            border-left: 4px solid #dee2e6;
            box-shadow: 0 2px 6px rgba(0,0,0,0.02);
            transition: transform 0.2s;
        }
        .os-mobile-card:active {
            transform: scale(0.99);
        }

        /* Chaves de Media Query Otimizadas para Mobile-First */
        @media (max-width: 767.98px) {
            .desktop-table-view {
                display: none !important;
            }
            .mobile-card-view {
                display: block;
            }
            .header-solicitante {
                flex-direction: column;
                text-align: center;
                gap: 16px;
            }
            .header-solicitante .btn-nova-os {
                width: 100%;
                padding: 14px !important;
            }
        }
    </style>
</head>

<body>

<header>
    <nav class="navbar navbar-custom py-2 mb-4 shadow-sm">
        <div class="container d-flex justify-content-between align-items-center">
            <span class="navbar-brand text-white fw-bold m-0 fs-5">
                <i class="bi bi-person-workspace text-warning me-2"></i>SGM Solicitante
            </span>
            <div class="navbar-nav">
                <a href="api/logout.php" class="btn btn-outline-light btn-sm rounded-pill px-3 fw-semibold">
                    <i class="bi bi-box-arrow-right me-1"></i> Sair
                </a>
            </div>
        </div>
    </nav>
</header>

<main class="container px-3">
    <div class="d-flex justify-content-between align-items-sm-center mb-4 header-solicitante">
        <div>
            <h3 class="fw-bold text-dark m-0">Minhas Solicitações</h3>
            <p class="text-muted small m-0">Acompanhe o andamento dos seus chamados em tempo real</p>
        </div>
        <a href="solicitante_abrir_chamado.php" class="btn text-white rounded-pill px-4 py-2.5 fw-bold shadow-sm d-inline-flex align-items-center justify-content-center border-0 btn-nova-os" style="background-color: var(--vinho-light);">
            <i class="bi bi-plus-lg me-2"></i> Nova Solicitação
        </a>
    </div>

    <div class="main-card p-2 p-md-3 mb-5 desktop-table-view">
        <div class="table-responsive">
            <table class="table table-hover table-modern align-middle mb-0" id="tabelaChamados">
                <thead>
                    <tr>
                        <th class="ps-3" style="width: 8%">ID</th>
                        <th style="width: 10%">Foto</th>
                        <th style="width: 25%">Localização</th>
                        <th style="width: 35%">Descrição do Problema</th>
                        <th style="width: 12%">Data</th>
                        <th class="text-center" style="width: 10%">Status</th>
                    </tr>
                </thead>
                <tbody class="border-top-0">
                    </tbody>
            </table>
        </div>
    </div>

    <div class="mobile-card-view id-container-mobile mb-5" id="containerCardsMobile">
        </div>
</main>

<div class="modal fade modal-blur" id="modalFoto" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-evidencia p-3">
        <div class="modal-content rounded-4 overflow-hidden">
            <div class="modal-header px-3 py-2.5">
                <div class="d-flex align-items-center">
                    <i class="bi bi-image text-danger fs-5 me-2"></i>
                    <h6 class="modal-title fw-bold text-dark m-0" id="modalTituloInfo">Evidência Anexada</h6>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-3 bg-light">
                <div class="img-container-modal">
                    <img id="imgModal" src="" class="img-fullscreen" alt="Evidência do chamado">
                </div>
            </div>
            <div class="modal-footer bg-white border-0 py-2 px-3 justify-content-start">
                <small class="text-muted"><i class="bi bi-info-circle me-1"></i> Toque fora para retornar à lista principal.</small>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
function verFoto(url, idChamado) {
    document.getElementById('imgModal').src = url;
    document.getElementById('modalTituloInfo').innerText = `Evidência - Chamado #${idChamado}`;
    new bootstrap.Modal(document.getElementById('modalFoto')).show();
}

function lidarComErroImagem(imgElement, caminhoAlternativo, idChamado) {
    if (imgElement.src.indexOf(caminhoAlternativo) === -1 && caminhoAlternativo) {
        imgElement.src = caminhoAlternativo;
        imgElement.setAttribute('onclick', `verFoto('${caminhoAlternativo}', ${idChamado})`);
    } else {
        imgElement.onerror = null;
        const container = imgElement.parentElement;
        container.innerHTML = `<div class="bg-light rounded-3 text-center d-flex align-items-center justify-content-center border" style="width:48px; height:48px;"><i class="bi bi-exclamation-triangle text-danger fs-5"></i></div>`;
    }
}

async function carregarChamados() {
    try {
        const response = await fetch('api/chamados.php');
        const chamados = await response.json();
        
        const tbody = document.querySelector('#tabelaChamados tbody');
        const containerMobile = document.getElementById('containerCardsMobile');

        const coresStatus = {
            'aberto': 'bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25',
            'em_execucao': 'bg-warning bg-opacity-10 text-dark border border-warning border-opacity-25',
            'concluido': 'bg-success bg-opacity-10 text-success border border-success border-opacity-25',
            'fechado': 'bg-dark bg-opacity-10 text-dark border border-dark border-opacity-25'
        };

        const coresBordaCard = {
            'aberto': '#6c757d',
            'em_execucao': '#ffc107',
            'concluido': '#198754',
            'fechado': '#212529'
        };

        let linhasDesktop = '';
        let cardsMobile = '';

        for (let c of chamados) {
            const statusAtual = (c.status && c.status.trim() !== '') ? c.status : 'aberto';
            const nomeAmbiente = c.ambiente_nome || c.ambiente || 'Ambiente Geral';
            const dataFormatada = c.data_abertura ? new Date(c.data_abertura).toLocaleDateString('pt-BR') : '';
            const badgeClass = coresStatus[statusAtual] || 'bg-secondary';
            const textoStatus = statusAtual.replace('_', ' ');

            let thumbHtml = `<div class="bg-light rounded-3 text-center d-flex align-items-center justify-content-center border" style="width:48px; height:48px;"><i class="bi bi-image text-muted opacity-50 fs-5"></i></div>`;
            let fotoUrlOriginal = '';

            try {
                const anexosResponse = await fetch(`api/anexos.php?id_chamado=${c.id_chamado}`);
                if (anexosResponse.ok) {
                    const anexos = await anexosResponse.json();
                    if (anexos && anexos.length > 0 && anexos[0].caminho_arquivo) {
                        fotoUrlOriginal = anexos[0].caminho_arquivo;
                        const altCaminho = anexos[0].caminho_alternativo || '';
                        
                        thumbHtml = `
                            <div class="d-inline-block position-relative">
                                <img src="${fotoUrlOriginal}" 
                                     class="img-thumb-preview shadow-sm" 
                                     onclick="verFoto('${fotoUrlOriginal}', ${c.id_chamado})" 
                                     onerror="lidarComErroImagem(this, '${altCaminho}', ${c.id_chamado})">
                            </div>
                        `;
                    }
                }
            } catch (erroAnexo) {
                console.error(erroAnexo);
            }

            // 1. Renderização Desktop (Tabela)
            linhasDesktop += `
                <tr style="border-bottom: 1px solid #edf2f7;">
                    <td class="ps-3 text-muted fw-semibold small">#${c.id_chamado}</td>
                    <td>${thumbHtml}</td>
                    <td>
                        <div class="fw-bold text-dark fs-6">${c.bloco_nome || 'Bloco'}</div>
                        <div class="small text-muted fw-medium">${nomeAmbiente}</div>
                    </td>
                    <td class="text-truncate" style="max-width: 280px;">${c.descricao_problema || ''}</td>
                    <td class="text-muted small fw-medium">${dataFormatada}</td>
                    <td class="text-center">
                        <span class="badge badge-status ${badgeClass}">${textoStatus}</span>
                    </td>
                </tr>
            `;

            // 2. Renderização Mobile (Cards Modernos)
            const acaoFotoMobile = fotoUrlOriginal ? `onclick="verFoto('${fotoUrlOriginal}', ${c.id_chamado})"` : '';
            cardsMobile += `
                <div class="card os-mobile-card p-3 mb-3" style="border-left-color: ${coresBordaCard[statusAtual] || '#6c757d'}">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div>
                            <span class="text-muted fw-bold small">#${c.id_chamado}</span>
                            <h6 class="fw-bold text-dark m-0 mt-1">${c.bloco_nome || 'Bloco'} - <span class="fw-normal text-muted">${nomeAmbiente}</span></h6>
                        </div>
                        <span class="badge badge-status ${badgeClass}">${textoStatus}</span>
                    </div>
                    
                    <p class="text-secondary small text-break my-2" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                        ${c.descricao_problema || 'Sem descrição.'}
                    </p>

                    <div class="d-flex justify-content-between align-items-center mt-2 pt-2 border-top border-light">
                        <span class="text-muted small fw-medium"><i class="bi bi-calendar3 me-1"></i>${dataFormatada}</span>
                        ${fotoUrlOriginal ? `
                            <button class="btn btn-sm btn-light rounded-pill px-3 fw-semibold border text-dark" ${acaoFotoMobile}>
                                <i class="bi bi-image me-1 text-danger"></i> Ver Evidência
                            </button>
                        ` : `
                            <span class="text-muted small opacity-50"><i class="bi bi-image-alt me-1"></i> Sem foto</span>
                        `}
                    </div>
                </div>
            `;
        }

        tbody.innerHTML = linhasDesktop;
        containerMobile.innerHTML = cardsMobile || '<div class="text-center text-muted py-4 bg-white rounded-3">Nenhum chamado encontrado.</div>';
        
    } catch (erro) {
        console.error("Erro geral no carregamento da fila:", erro);
    }
}

carregarChamados();
</script>

</body>
</html>