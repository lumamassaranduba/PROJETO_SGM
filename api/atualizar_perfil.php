<?php
session_start();
require_once '../config/database.php'; // Sobe uma pasta para achar o config

// Garante que o usuário está logado
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
$nova_senha = $_POST['nova_senha'] ?? '';
$confirmar_senha = $_POST['confirmar_senha'] ?? '';

if (!$email) {
    header("Location: ../perfil.php?erro=" . urlencode("E-mail inválido."));
    exit;
}

// --- VERIFICAÇÃO DE E-MAIL ---
// Busca o e-mail atual do próprio usuário logado
$sql_busca = "SELECT email FROM usuarios WHERE id_usuario = ?";
$stmt_busca = $conn->prepare($sql_busca);
$stmt_busca->bind_param("i", $user_id);
$stmt_busca->execute();
$resultado_busca = $stmt_busca->get_result();
$usuario_atual = $resultado_busca->fetch_assoc();
$stmt_busca->close();

// Se o usuário mudou o e-mail no formulário...
if ($usuario_atual['email'] !== $email) {
    // Verifica se esse NOVO e-mail já pertence a OUTRO usuário no sistema
    $sql_check = "SELECT id_usuario FROM usuarios WHERE email = ? AND id_usuario != ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("si", $email, $user_id);
    $stmt_check->execute();
    $resultado_check = $stmt_check->get_result();
    
    if ($resultado_check->num_rows > 0) {
        $stmt_check->close();
        header("Location: ../perfil.php?erro=" . urlencode("Este e-mail já está sendo utilizado por outro usuário."));
        exit;
    }
    $stmt_check->close();

    // Se estiver disponível, atualiza o e-mail
    $sql_email = "UPDATE usuarios SET email = ? WHERE id_usuario = ?";
    $stmt_email = $conn->prepare($sql_email);
    if ($stmt_email) {
        $stmt_email->bind_param("si", $email, $user_id);
        $stmt_email->execute();
        $stmt_email->close();
    }
}

// --- PROCESSAMENTO DA NOVA SENHA ---
if (!empty($nova_senha)) {
    if ($nova_senha !== $confirmar_senha) {
        header("Location: ../perfil.php?erro=" . urlencode("As senhas não coincidem."));
        exit;
    }

    // Criar o hash seguro da nova senha
    $senha_hash = password_hash($nova_senha, PASSWORD_DEFAULT);

    $sql_senha = "UPDATE usuarios SET senha_hash = ? WHERE id_usuario = ?";
    $stmt_senha = $conn->prepare($sql_senha);
    if ($stmt_senha) {
        $stmt_senha->bind_param("si", $senha_hash, $user_id);
        $stmt_senha->execute();
        $stmt_senha->close();
    }
}

// --- PROCESSAMENTO DE UPLOAD DA FOTO ---
if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
    $fileTmpPath = $_FILES['foto']['tmp_name'];
    $fileName = $_FILES['foto']['name'];
    $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
    
    $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];

    if (in_array($fileExtension, $allowedExtensions)) {
        $newFileName = md5(time() . $user_id) . '.' . $fileExtension;
        $uploadFileDir = '../uploads/perfis/';
        
        if (!is_dir($uploadFileDir)) {
            mkdir($uploadFileDir, 0755, true);
        }

        $dest_path = $uploadFileDir . $newFileName;

        if (move_uploaded_file($fileTmpPath, $dest_path)) {
            $caminho_banco = 'uploads/perfis/' . $newFileName;
            
            $sql_foto = "UPDATE usuarios SET foto = ? WHERE id_usuario = ?";
            $stmt_foto = $conn->prepare($sql_foto);
            if ($stmt_foto) {
                $stmt_foto->bind_param("si", $caminho_banco, $user_id);
                $stmt_foto->execute();
                $stmt_foto->close();
            }
        }
    }
}

// Sucesso absoluto! Retorna com feedback positivo
header("Location: ../perfil.php?sucesso=1");
exit;