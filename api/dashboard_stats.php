<?php
session_start();
require_once '../config/database.php'; // Verifique se o caminho está correto
header('Content-Type: application/json');

if (!isset($_SESSION['user_id']) || $_SESSION['user_perfil'] !== 'gestor') {
    echo json_encode(["success" => false, "message" => "Acesso negado."]);
    exit;
}

try {
    // Query para contar os chamados por status e prioridade
    $sql = "SELECT 
                SUM(CASE WHEN status = 'aberto' THEN 1 ELSE 0 END) as novos,
                SUM(CASE WHEN status = 'em_execucao' THEN 1 ELSE 0 END) as atendimento,
                SUM(CASE WHEN prioridade = 'urgente' AND status != 'fechado' THEN 1 ELSE 0 END) as criticos
            FROM chamados";
            
    $res = $conn->query($sql);
    $dados = $res->fetch_assoc();

    // Garante que se vier nulo (banco vazio), retorne 0
    echo json_encode([
        "success" => true,
        "dados" => [
            "novos" => $dados['novos'] ?? 0,
            "atendimento" => $dados['atendimento'] ?? 0,
            "criticos" => $dados['criticos'] ?? 0
        ]
    ]);

} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
}