<?php
session_start();
require_once '../config/database.php';

header('Content-Type: application/json');

// Verifica se está logado
if (!isset($_SESSION['user_id'])) {
    echo json_encode(["success" => false, "message" => "Acesso negado."]);
    exit;
}

$user_id = $_SESSION['user_id'];
$perfil = $_SESSION['user_perfil'];
$id_chamado = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// --- BUSCA DETALHADA (Para a página tecnico_detalhes.php) ---
if ($id_chamado > 0) {
    $sql = "SELECT c.*, a.nome as ambiente_nome, b.nome as bloco_nome, u.nome 
            as solicitante_nome, t.nome as tipo_nome
            FROM chamados c
            JOIN ambientes a ON c.id_ambiente = a.id_ambiente
            JOIN blocos b ON a.id_bloco = b.id_bloco
            JOIN usuarios u ON c.id_solicitante = u.id_usuario
            JOIN tipos_servico t ON c.id_tipo_servico = t.id_tipo
            WHERE c.id_chamado = $id_chamado";
    
    $result = $conn->query($sql);
    if ($result && $result->num_rows > 0) {
        echo json_encode($result->fetch_assoc());
    } else {
        echo json_encode(["success" => false, "message" => "Chamado não encontrado."]);
    }
    exit;
}

// --- LISTAGEM GERAL (Para o tecnico_dashboard.php) ---

// Definindo o filtro baseado no perfil
if ($perfil === 'solicitante') {
    // Solicitante só vê os dele
    $where = "WHERE c.id_solicitante = $user_id";
} else {
    // Técnico e Gestor veem tudo que não está FECHADO
    // (Mudei aqui para não filtrar por ID de técnico específico e aparecer tudo para você)
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