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
        $sql = "SELECT * FROM blocos";

        $result = $conn->query($sql);
        $blocos = [];

        if($result){
            while ($row = $result->fetch_assoc()){
                $blocos[] = $row;
            }
        }
        echo json_encode(["success" => true, "data" => $blocos]);
    break;

    case 'POST':
        $data = json_decode(file_get_contents("php://input"));

        if(!isset($data->nome)){
            echo json_encode(["success" => false, "message" => "Dados incompletos. Informe nome."]);
            exit;
        }

        $nome = $conn->real_escape_string(trim($data->nome));
        $desc = $conn->real_escape_string(trim($data->descricao));

        $sql="INSERT INTO blocos (nome, descricao) VALUES ('$nome', '$desc')";

        if($conn->query($sql) === TRUE){
            echo json_encode(["success" => true, "message" => "Bloco criado com sucesso!"]);
        }else{
            echo json_encode(["success" => false, "message" => "Erro ao criar bloco:" .$conn->error]);
        }
    break;

    case 'PUT':
        $data = json_decode(file_get_contents("php://input"));

        if(!isset($data->id_bloco) || !isset($data->nome)){
            echo json_encode(["success" => false, "message" => "Dados incompletos para atualização."]);
            exit;
        }

        $id_bloco = (int)$data->id_bloco;
        $nome = $conn->real_escape_string(trim($data->nome));
        $desc = $conn->real_escape_string(trim($data->descricao));

        $sql = "UPDATE blocos SET nome='$nome', descricao='$desc' WHERE id_bloco = $id_bloco";

        if($conn->query($sql) === TRUE){
            echo json_encode(["success" => true, "message" => "Bloco atualizado com sucesso!"]);
        } else {
            echo json_encode(["success" => false, "message" => "Erro ao atualizar bloco: " . $conn->error]);
        }
    break;

    case 'DELETE':
        $data= json_decode(file_get_contents("php://input"));

        if(!isset($data->id_bloco)){
            echo json_encode(["success" => false, "message" => "ID do bloco não fornecido."]);
            exit;
        }

        $id_bloco = (int)$data->id_bloco;
        $sql = "DELETE FROM blocos WHERE id_bloco = $id_bloco";

        if($conn->query($sql) === TRUE){
            echo json_encode(["success" => true, "message" => "Bloco excluído com sucesso!"]);
        }else{
            echo json_encode(["success" => false, "message"=> 
            "Erro ao excluir: pode haver dados vinculados a este bloco."]); 
        }
    break;

    default:
        echo json_encode(["success"=>false, "message" => "Método HTTP não suportado"]);
    break;
}
?>