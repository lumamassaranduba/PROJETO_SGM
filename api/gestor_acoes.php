<?php
session_start();
require_once '../config/database.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);

if (!$data || !isset($data['id_chamado']) || !isset($data['acao'])) {
    echo json_encode(["success" => false, "message" => "Dados inválidos."]);
    exit;
}

$id = (int)$data['id_chamado'];
$acao = $data['acao'];
$novo_status = ($acao === 'fechar') ? 'fechado' : 'em_execucao';

// Se reabrir, podemos limpar a data de conclusão ou apenas voltar o status
$sql = "UPDATE chamados SET status = '$novo_status' WHERE id_chamado = $id";

if ($conn->query($sql)) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "message" => $conn->error]);
}