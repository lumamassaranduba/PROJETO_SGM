<?php
session_start();
require_once'../config/database.php';
header('Content-Type: application/json');
if(!isset($_SESSION['user_id']) || $_SESSION['user_perfil'] !== 'gestor'){
    echo json_encode(["success" => false, "message" => "Acesso negado."]);
    exit;
}
$sql = "SELECT id_chamado, usuarios.nome as solicitante, ambientes.nome as ambiente, tipos_servico.nome as tipo_servico, prioridade, status from chamados inner join usuarios on id_usuario = id_solicitante join ambientes on chamados.id_ambiente = ambientes.id_ambiente join tipos_servico on tipos_servico.id_tipo = chamados.id_tipo_servico";
$res = $conn->query($sql);
$dados = $res -> fetch_all(MYSQLI_ASSOC);
echo json_encode($dados);      