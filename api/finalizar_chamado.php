<?php
// Desativa a exibição de erros textuais que quebram o JSON na tela do usuário
ini_set('display_errors', 0);
error_reporting(E_ALL);

ob_start();
header('Content-Type: application/json; charset=utf-8');

// Caminho do banco de dados mudando de diretório
$path_db = __DIR__ . '/../config/database.php';

if (!file_exists($path_db)) {
    ob_clean();
    echo json_encode(["success" => false, "message" => "Arquivo database.php não encontrado."]);
    exit;
}

require_once $path_db;

// Captura as informações JSON do JavaScript
$json = file_get_contents("php://input");
$data = json_decode($json, true);

if (!$data || !isset($data['id_chamado'])) {
    ob_clean();
    echo json_encode(["success" => false, "message" => "Dados inválidos ou vazios recebidos."]);
    exit;
}

$id = (int)$data['id_chamado'];
$solucao = $conn->real_escape_string($data['solucao_tecnica']);
$tempo = (int)$data['tempo_gasto'];

/**
 * BLINDAGEM AUTOMÁTICA DO BANCO DE DADOS
 * Este bloco verifica se as colunas necessárias existem. Se não existirem, ele cria na hora!
 */
$colunas_necessarias = [
    'solucao_tecnica' => "TEXT NULL",
    'tempo_gasto'      => "INT NULL DEFAULT 0",
    'data_conclusao'   => "DATETIME NULL"
];

foreach ($colunas_necessarias as $coluna => $definicao) {
    $verificar = $conn->query("SHOW COLUMNS FROM `chamados` LIKE '$coluna'");
    if ($verificar && $verificar->num_rows == 0) {
        // A coluna não existe, vamos injetá-la dinamicamente
        $conn->query("ALTER TABLE `chamados` ADD `$coluna` $definicao");
    }
}

// Executa a Query Oficial de Fechamento da Ordem de Serviço
$sql = "UPDATE chamados SET 
            status = 'concluido', 
            solucao_tecnica = '$solucao', 
            tempo_gasto = $tempo, 
            data_conclusao = NOW() 
        WHERE id_chamado = $id";

if ($conn->query($sql)) {
    ob_clean();
    echo json_encode(["success" => true]);
} else {
    // Se der erro de SQL, captura e exibe de maneira tratada e legível dentro do JSON
    $erro_banco = $conn->error;
    ob_clean();
    echo json_encode(["success" => false, "message" => "Erro estrutural no banco de dados: " . $erro_banco]);
}
exit;