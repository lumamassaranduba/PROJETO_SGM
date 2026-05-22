<?php
ob_clean();
header('Content-Type: application/json; charset=utf-8');
session_start();

require_once __DIR__ . '/../config/database.php';

$db = null;
if (isset($conn)) { $db = $conn; }
elseif (isset($conexao)) { $db = $conexao; }
elseif (isset($pdo)) { $db = $pdo; }

if (!$db) {
    echo json_encode(["error" => "Conexão não encontrada."]);
    exit;
}

$id_chamado = isset($_GET['id_chamado']) ? (int)$_GET['id_chamado'] : (isset($_GET['id']) ? (int)$_GET['id'] : 0);

if ($id_chamado === 0) {
    echo json_encode([]);
    exit;
}

try {
    $sql = "SELECT caminho_arquivo FROM chamados_anexos WHERE id_chamado = ? LIMIT 1";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("i", $id_chamado);
    $stmt->execute();
    
    $resultado = $stmt->get_result();
    $linha = $resultado->fetch_assoc();

    if ($linha && !empty($linha['caminho_arquivo'])) {
        $nome_arquivo = basename($linha['caminho_arquivo']);
        
        // Retornamos duas opções de caminho para blindar o front-end contra erros de pasta
        echo json_encode([
            [
                "caminho_arquivo" => "uploads/" . $nome_arquivo,
                "caminho_alternativo" => "assets/uploads/" . $nome_arquivo
            ]
        ]);
    } else {
        echo json_encode([]);
    }

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["error" => $e->getMessage()]);
}