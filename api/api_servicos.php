<?php
session_start();
require_once '../config/database.php';
header('Content-Type: application/json');

if (!isset($_SESSION['user_id']) || $_SESSION['user_perfil'] !== 'gestor') {
    echo json_encode(["success" => false, "message" => "Acesso negado."]);
    exit;
}

$method = $_SERVER['REQUEST_METHOD'];

switch($method){
    case 'GET':
        if (isset($_GET['id'])) {
            $id = (int)$_GET['id'];
            $sql = "SELECT * FROM tipos_servico WHERE id_tipo = $id";
            $result = $conn->query($sql);
            echo json_encode(["success" => true, "data" => $result->fetch_assoc()]);
        } else {
            $sql = "SELECT * FROM tipos_servico";
            $result = $conn->query($sql);
            $tipos_servico = [];
            while ($row = $result->fetch_assoc()){
                $tipos_servico[] = $row;
            }
            echo json_encode(["success" => true, "data" => $tipos_servico]);
        }
    break;

    case 'POST':
        $data = json_decode(file_get_contents("php://input"));
        $nome = $conn->real_escape_string(trim($data->nome));
        $desc = $conn->real_escape_string(trim($data->descricao));
        $sql = "INSERT INTO tipos_servico (nome, descricao) VALUES ('$nome', '$desc')";
        if($conn->query($sql) === TRUE) {
            echo json_encode(["success" => true, "message" => "Criado com sucesso!"]);
        } else {
            echo json_encode(["success" => false, "message" => "Erro: " . $conn->error]);
        }
    break;

    case 'PUT':
        $data = json_decode(file_get_contents("php://input"));
        $id_tipo = (int)$data->id_tipo;
        $nome = $conn->real_escape_string(trim($data->nome));
        $desc = $conn->real_escape_string(trim($data->descricao));
        $sql = "UPDATE tipos_servico SET nome='$nome', descricao='$desc' WHERE id_tipo = $id_tipo";
        if($conn->query($sql) === TRUE) {
            echo json_encode(["success" => true, "message" => "Atualizado com sucesso!"]);
        } else {
            echo json_encode(["success" => false, "message" => "Erro: " . $conn->error]);
        }
    break;

    case 'DELETE':
        $data = json_decode(file_get_contents("php://input"));
        $id_tipo = (int)$data->id_tipo;
        $sql = "DELETE FROM tipos_servico WHERE id_tipo = $id_tipo";
        if($conn->query($sql) === TRUE) {
            echo json_encode(["success" => true, "message" => "Excluído com sucesso!"]);
        } else {
            echo json_encode(["success" => false, "message" => "Erro ao excluir."]);
        }
    break;
}