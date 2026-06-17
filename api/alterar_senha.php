<?php
session_start();
require_once '../config/database.php';

header('Content-Type: application/json');

// SEGURANÇA: Bloqueia se quem estiver tentando acessar não for um gestor ou admin logado
if (!isset($_SESSION['user_perfil']) || ($_SESSION['user_perfil'] !== 'admin' && $_SESSION['user_perfil'] !== 'gestor')) {
    echo json_encode(["success" => false, "message" => "Acesso não autorizado."]);
    exit;
}

$dadosBrutos = file_get_contents("php://input");
$data = json_decode($dadosBrutos);

if (!$data || !isset($data->id_usuario) || !isset($data->nova_senha)) {
    echo json_encode(["success" => false, "message" => "Dados incompletos."]);
    exit;
}

$id_usuario = (int)$data->id_usuario;
$nova_senha = trim($data->nova_senha);

if (strlen($nova_senha) < 4) { // Validação simples de tamanho de senha
    echo json_encode(["success" => false, "message" => "A senha deve ter pelo menos 4 caracteres."]);
    exit;
}

// CRUCIAL: Cria o hash seguro para ser compatível com o seu sistema de login atual
$nova_senha_hash = password_hash($nova_senha, PASSWORD_DEFAULT);

// Atualiza a senha no banco de dados
$sql = "UPDATE usuarios SET senha_hash = ? WHERE id_usuario = ?";
$stmt = $conn->prepare($sql);

if ($stmt) {
    $stmt->bind_param("si", $nova_senha_hash, $id_usuario);
    
    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Senha atualizada com sucesso!"]);
    } else {
        echo json_encode(["success" => false, "message" => "Erro ao atualizar banco de dados."]);
    }
    
    $stmt->close();
} else {
    echo json_encode(["success" => false, "message" => "Erro na preparação da consulta."]);
}

$conn->close();