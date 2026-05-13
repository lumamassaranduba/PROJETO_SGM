<?php
// Desativa exibição de erros que quebram o JSON, mas registra no log
error_reporting(E_ALL);
ini_set('display_errors', 0);

require_once '../config/database.php';
header('Content-Type: application/json');

// Limpa qualquer saída anterior
if (ob_get_length()) ob_clean();

try {
    $json = file_get_contents("php://input");
    $data = json_decode($json, true);

    if (!$data || !isset($data['id_chamado'])) {
        throw new Error("Dados não recebidos corretamente.");
    }

    $id = (int)$data['id_chamado'];
    $solucao = $conn->real_escape_string($data['solucao_tecnica']);
    $tempo = (int)$data['tempo_gasto'];

    $sql = "UPDATE chamados SET 
                status = 'concluido', 
                solucao_tecnica = '$solucao', 
                tempo_gasto = '$tempo',
                data_conclusao = NOW()
            WHERE id_chamado = $id";

    if ($conn->query($sql)) {
        echo json_encode(["success" => true]);
    } else {
        throw new Error($conn->error);
    }
} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
} catch (Error $e) {
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
}
exit;