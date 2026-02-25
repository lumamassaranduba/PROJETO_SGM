<?php
session_start();
require_once'../config/database.php';
header('Content-Type:application/json');



if (!isset($_SESSION['user_id'])) {
    echo json_encode(["success" => false, "message" => "Sessão expirada."]);
    exit;
}

$sql = "SELECT id_usuario, nome FROM usuarios WHERE perfil = 'tecnico' AND ativo = 1 ORDER BY nome ASC";
$result = $conn->query($sql);
$tecnicos = $result->fetch_all(MYSQLI_ASSOC);

echo json_encode($tecnicos);