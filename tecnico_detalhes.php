<?php
session_start();
require_once 'config/database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_perfil'] !== 'tecnico') {
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
    <title>SGM - Executar Serviço</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        :root { --vinho-dark: #7a0101; --vinho-light: #990202; }
        body { background-color: #f0f2f5; font-family: 'Segoe UI', sans-serif; }
        .navbar { background: linear-gradient(135deg, var(--vinho-dark) 0%, var(--vinho-light) 100%); border-bottom: 4px solid #ffc107; }
        .card-os { border: none; border-radius: 15px; background: white; border-left: 6px solid #ffc107; }
        .info-label { font-size: 0.75rem; font-weight: 700; color: #6c757d; text-transform: uppercase; }
        .info-value { font-weight: 600; color: #2d3436; margin-bottom: 1rem; }
        .bg-descricao { background-color: #fff4f4; border-left: 3px solid var(--vinho-light); padding: 15px; border-radius: 8px; }
        .btn-concluir { background-color: #198754; color: white; font-weight: 700; padding: 12px; border-radius: 10px; border: none; transition: 0.3s; }
        .btn-concluir:hover { background-color: #146c43; transform: translateY(-2px); }
    </style>
</head>
<body>

<nav class="navbar navbar-dark shadow-sm mb-4">
    <div class="container text-center">
        <a href="tecnico_dashboard.php" class="text-white float-start fs-4"><i class="bi bi-arrow-left-circle"></i></a>
        <span class="navbar-brand fw-bold">ORDEM DE SERVIÇO #<?= $id ?></span>
    </div>
</nav>

<div class="container pb-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card card-os shadow-sm mb-4 p-4">
                <h5 class="fw-bold mb-4">Informações do Chamado</h5>
                <div class="row">
                    <div class="col-md-6">
                        <span class="info-label">Localização</span>
                        <div id="txtLocal" class="info-value text-primary">Carregando...</div>
                    </div>
                    <div class="col-md-6">
                        <span class="info-label">Prioridade</span>
                        <div id="txtPrioridade" class="info-value">Carregando...</div>
                    </div>
                    <div class="col-md-6">
                        <span class="info-label">Solicitante</span>
                        <div id="txtSolicitante" class="info-value">Carregando...</div>
                    </div>
                    <div class="col-md-6">
                        <span class="info-label">Status Atual</span>
                        <div id="txtStatus" class="info-value">Carregando...</div>
                    </div>
                </div>

                <span class="info-label">Descrição do Problema</span>
                <div id="txtDescricao" class="bg-descricao mb-4 text-dark small">Carregando...</div>

                <hr>

                <h5 class="fw-bold my-4"><i class="bi bi-pencil-square me-2"></i>Relatório Técnico</h5>
                <form id="formFinalizar">
                    <div class="mb-3">
                        <label class="info-label">O que foi realizado?</label>
                        <textarea id="solucao" class="form-control border-0 shadow-sm" rows="4" placeholder="Descreva a solução técnica aplicada..." required style="background:#f8f9fa;"></textarea>
                    </div>
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="info-label">Tempo Gasto (Minutos)</label>
                            <input type="number" id="tempo" class="form-control border-0 shadow-sm" placeholder="Ex: 30" required style="background:#f8f9fa;">
                        </div>
                    </div>
                    <button type="submit" id="btnSalvar" class="btn btn-concluir w-100 shadow">
                        <i class="bi bi-check-circle me-2"></i>CONCLUIR SERVIÇO
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
const ID = <?= $id ?>;

async function carregar() {
    try {
        const res = await fetch(`api/chamados.php?id=${ID}`);
        const c = await res.json();
        
        document.getElementById('txtLocal').innerText = `${c.bloco_nome} - ${c.ambiente_nome}`;
        document.getElementById('txtSolicitante').innerText = c.solicitante_nome;
        document.getElementById('txtStatus').innerText = c.status.toUpperCase();
        document.getElementById('txtDescricao').innerText = c.descricao_problema;
        
        const prio = document.getElementById('txtPrioridade');
        prio.innerText = c.prioridade.toUpperCase();
        prio.className = 'info-value ' + (c.prioridade === 'urgente' ? 'text-danger' : 'text-primary');
        
    } catch (e) { console.error("Erro ao carregar:", e); }
}

document.getElementById('formFinalizar').onsubmit = async (e) => {
    e.preventDefault();
    const btn = document.getElementById('btnSalvar');
    
    // Inicia o estado de carregamento
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Processando...';

    try {
        const response = await fetch('api/finalizar_chamado.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({
                id_chamado: ID,
                solucao_tecnica: document.getElementById('solucao').value,
                tempo_gasto: document.getElementById('tempo').value
            })
        });

        // Verificamos se a resposta do servidor foi ok
        if (!response.ok) throw new Error('Erro no servidor');

        const r = await response.json();

        if(r.success) {
            // MENSAGEM DE SUCESSO QUE VOCÊ PEDIU
            alert("Serviço concluído com sucesso!");
            window.location.href = 'tecnico_dashboard.php';
        } else {
            alert("Erro ao salvar: " + (r.message || "Tente novamente."));
            btn.disabled = false;
            btn.innerHTML = '<i class="bi bi-check-circle me-2"></i>CONCLUIR SERVIÇO';
        }
    } catch (error) {
        alert("Erro de conexão. Verifique se o arquivo api/finalizar_chamado.php existe.");
        btn.disabled = false;
        btn.innerHTML = '<i class="bi bi-check-circle me-2"></i>CONCLUIR SERVIÇO';
    }
};

carregar();
</script>
</body>
</html>