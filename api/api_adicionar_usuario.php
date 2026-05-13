<?php
session_start();

// Conexão com banco
require_once '../config/database.php';

// Apenas gestores podem acessar
if (!isset($_SESSION['user_id']) || $_SESSION['user_perfil'] !== 'gestor') {

    header("Location: /2025/PROJETO_SGM/login.php");
    exit;
}

// Verifica se veio do formulário
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Recebe os dados
    $nome = filter_input(
        INPUT_POST,
        'nome',
        FILTER_SANITIZE_SPECIAL_CHARS
    );

    $email = filter_input(
        INPUT_POST,
        'email',
        FILTER_VALIDATE_EMAIL
    );

    $senha = $_POST['senha'];

    $perfil = $_POST['perfil'];

    // Validação básica
    if (!$nome || !$email || !$senha || !$perfil) {

        echo "
            <script>
                alert('Preencha todos os campos!');
                window.history.back();
            </script>
        ";

        exit;
    }

    // Perfis permitidos
    $perfisPermitidos = [
        'gestor',
        'tecnico',
        'solicitante'
    ];

    if (!in_array($perfil, $perfisPermitidos)) {

        die("Perfil inválido.");
    }

    try {

        // Verifica se email já existe
        $check = $conn->prepare("
            SELECT id_usuario
            FROM usuarios
            WHERE email = ?
        ");

        $check->bind_param("s", $email);

        $check->execute();

        $resultado = $check->get_result();

        if ($resultado->num_rows > 0) {

            echo "
                <script>
                    alert('Este e-mail já está cadastrado!');
                    window.history.back();
                </script>
            ";

            exit;
        }

        // Criptografa senha
        $senhaHash = password_hash(
            $senha,
            PASSWORD_DEFAULT
        );

        // Inserção
        $sql = "
            INSERT INTO usuarios
            (nome, email, senha_hash, perfil)
            VALUES (?, ?, ?, ?)
        ";

        $stmt = $conn->prepare($sql);

        $stmt->bind_param(
            "ssss",
            $nome,
            $email,
            $senhaHash,
            $perfil
        );

        // Executa
        if ($stmt->execute()) {

            header("Location: /2025/PROJETO_SGM/gestor_usuarios.php?msg=sucesso");
            exit;

        } else {

            echo "Erro ao cadastrar usuário.";

        }

    } catch (Exception $e) {

        die("Erro no sistema: " . $e->getMessage());

    }

}
?>