<?php
// Evita que erros de PHP "sujem" a saída JSON
error_reporting(0); 
session_start();
header('Content-Type: application/json; charset=utf-8');

require_once '../config/database.php';

// 1. Verificação de Sessão
if (!isset($_SESSION['user_id']) || $_SESSION['user_perfil'] !== 'gestor') {
    echo json_encode(["success" => false, "message" => "Sessão expirada ou acesso negado."]);
    exit;
}

// 2. Captura do JSON
$input = file_get_contents("php://input");
$data = json_decode($input, true);

if (!$data) {
    echo json_encode(["success" => false, "message" => "Dados inválidos."]);
    exit;
}

$id_chamado   = $data['id_chamado'] ?? null;
$id_tecnico   = $data['id_tecnico'] ?? null;
$prioridade   = $data['prioridade'] ?? null;
$data_prevista = $data['data_prevista'] ?? null;

if (!$id_chamado || !$id_tecnico) {
    echo json_encode(["success" => false, "message" => "Preencha todos os campos obrigatórios."]);
    exit;
}

try {
    // 3. Preparação do SQL (Verifique se os nomes das colunas batem com seu banco)
    $sql = "UPDATE chamados 
            SET id_tecnico = ?, 
                prioridade = ?, 
                data_previsao_conclusao = ?, 
                status = 'em_andamento' 
            WHERE id_chamado = ?";

    $stmt = $conn->prepare($sql);
    
    if (!$stmt) {
        throw new Exception("Falha na preparação do banco: " . $conn->error);
    }

    // "issi" -> id_tecnico (int), prioridade (str), data (str), id_chamado (int)
    $stmt->bind_param("issi", $id_tecnico, $prioridade, $data_prevista, $id_chamado);

    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            echo json_encode(["success" => true, "message" => "Atribuído com sucesso!"]);
        } else {
            echo json_encode(["success" => false, "message" => "Nenhuma alteração feita ou ID não encontrado."]);
        }
    } else {
        throw new Exception($stmt->error);
    }

} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => "Erro interno: " . $e->getMessage()]);
}

exit;