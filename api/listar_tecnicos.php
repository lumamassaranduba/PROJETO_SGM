<?php
session_start();
require_once '../config/database.php';

header('Content-Type: application/json');

// Apenas o gestor pode consultar a lista de técnicos para atribuição
if (!isset($_SESSION['user_id']) || $_SESSION['user_perfil'] !== 'gestor') {
    echo json_encode(["success" => false, "message" => "Acesso negado."]);
    exit;
}

// Busca apenas os usuários cadastrados com o perfil de técnico
$sql = "SELECT id_usuario, nome FROM usuarios WHERE perfil = 'tecnico' ORDER BY nome ASC";
$result = $conn->query($sql);

if ($result) {
    $tecnicos = $result->fetch_all(MYSQLI_ASSOC);
    echo json_encode(["success" => true, "tecnicos" => $tecnicos]);
} else {
    echo json_encode(["success" => false, "message" => "Erro ao buscar técnicos: " . $conn->error]);
}