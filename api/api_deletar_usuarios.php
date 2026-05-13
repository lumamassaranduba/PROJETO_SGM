<?php
session_start();

require_once '../config/database.php';

// Proteção: apenas gestores
if (!isset($_SESSION['user_id']) || $_SESSION['user_perfil'] !== 'gestor') {

    header("Location: ../login.php");
    exit;
}

// Verifica ID
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($id <= 0) {

    die("ID inválido.");
}

try {

    // Impede excluir o próprio usuário logado
    if ($id == $_SESSION['user_id']) {

        echo "
            <script>
                alert('Você não pode excluir sua própria conta!');
                window.history.back();
            </script>
        ";

        exit;
    }

    // Deleta usuário
    $sql = "DELETE FROM usuarios WHERE id_usuario = ?";

    $stmt = $conn->prepare($sql);

    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {

        header("Location: ../gestor_usuarios.php?msg=excluido");

        exit;

    } else {

        echo "Erro ao excluir usuário.";

    }

} catch (Exception $e) {

    die("Erro: " . $e->getMessage());

}
?>