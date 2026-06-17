<?php
session_start();
require_once '../config/database.php'; // Ajuste o caminho se necessário

// Verifica se o técnico está logado
if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    exit;
}

$user_id = $_SESSION['user_id']; // ID do técnico logado na sessão

// A CONSULTA CORRETA: Traz tudo do chat deste técnico, seja enviado por ele ou pelo gestor
$query = "SELECT remetente, texto, data_envio FROM mensagens_chat 
          WHERE id_usuario = ? 
          ORDER BY data_envio ASC";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$resultado = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Devolve o histórico completo para o JavaScript do técnico
echo json_encode($resultado);
$stmt->close();
?>