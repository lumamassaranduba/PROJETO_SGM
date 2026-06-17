<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    exit;
}

$dados = json_decode(file_get_contents("php://input"), true);
$texto = trim($dados['mensagem'] ?? '');

// Se for gestor enviando, ele manda o ID do técnico no JSON, senão o técnico usa o ID da própria sessão
$user_id = ($_SESSION['user_perfil'] === 'tecnico') ? $_SESSION['user_id'] : intval($dados['id_usuario'] ?? 0);
$remetente = $_SESSION['user_perfil']; 

if (empty($texto) || empty($user_id)) {
    http_response_code(400);
    exit;
}

$query = "INSERT INTO mensagens_chat (id_usuario, remetente, texto) VALUES (?, ?, ?)";
$stmt = $conn->prepare($query);
$stmt->bind_param("iss", $user_id, $remetente, $texto);
echo json_encode(['sucesso' => $stmt->execute()]);
$stmt->close();
?>