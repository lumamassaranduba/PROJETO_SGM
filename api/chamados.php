<?php
session_start();
require_once '../config/database.php';

header('Content-Type: application/json');

// Verifica se está logado
if (!isset($_SESSION['user_id'])) {
    echo json_encode(["success" => false, "message" => "Acesso negado."]);
    exit;
}

$user_id = (int)$_SESSION['user_id'];
$perfil = $_SESSION['user_perfil'];
$id_chamado = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// --- BUSCA DETALHADA (Para a página tecnico_detalhes.php) ---
if ($id_chamado > 0) {
    // Segurança extra: Se for técnico, garante que ele só veja os detalhes se o chamado for dele
    $filtro_tecnico_detalhe = "";
    if ($perfil === 'tecnico') {
        $filtro_tecnico_detalhe = " AND c.id_tecnico = $user_id";
    }

    $sql = "SELECT c.*, a.nome as ambiente_nome, b.nome as bloco_nome, u.nome 
            as solicitante_nome, t.nome as tipo_nome
            FROM chamados c
            JOIN ambientes a ON c.id_ambiente = a.id_ambiente
            JOIN blocos b ON a.id_bloco = b.id_bloco
            JOIN usuarios u ON c.id_solicitante = u.id_usuario
            JOIN tipos_servico t ON c.id_tipo_servico = t.id_tipo
            WHERE c.id_chamado = $id_chamado $filtro_tecnico_detalhe";
    
    $result = $conn->query($sql);
    if ($result && $result->num_rows > 0) {
        echo json_encode($result->fetch_assoc());
    } else {
        echo json_encode(["success" => false, "message" => "Chamado não encontrado ou você não tem permissão."]);
    }
    exit;
}

// --- LISTAGEM GERAL (Para o tecnico_dashboard.php e solicitante_dashboard) ---

// Aplicando estritamente a nova regra de negócio baseada no perfil
if ($perfil === 'solicitante') {
    // Solicitante só vê os chamados criados por ele mesmo
    $where = "WHERE c.id_solicitante = $user_id";
} elseif ($perfil === 'tecnico') {
    // REGRA DO PROFESSOR: Técnico só vê o chamado se o Gestor vinculou ao ID dele e não está fechado
    $where = "WHERE c.id_tecnico = $user_id AND c.status != 'fechado'";
} else {
    // Gestor vê absolutamente tudo que não está FECHADO para poder delegar e gerenciar
    $where = "WHERE c.status != 'fechado'";
}

$sql = "SELECT c.id_chamado, c.descricao_problema, c.status, c.prioridade, c.data_abertura, 
               a.nome as ambiente_nome, b.nome as bloco_nome
        FROM chamados c
        JOIN ambientes a ON c.id_ambiente = a.id_ambiente
        JOIN blocos b ON a.id_bloco = b.id_bloco
        $where
        ORDER BY FIELD(c.prioridade, 'urgente', 'alta', 'media', 'baixa'), c.data_abertura DESC";

$result = $conn->query($sql);

if ($result) {
    $chamados = $result->fetch_all(MYSQLI_ASSOC);
    echo json_encode($chamados);
} else {
    echo json_encode(["success" => false, "message" => "Erro na consulta: " . $conn->error]);
}