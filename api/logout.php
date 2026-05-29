<?php
// 1. Inicia a sessão para ter acesso aos dados atuais
session_start();

// 2. Limpa todas as variáveis de sessão salvos na memória
$_SESSION = array();

// 3. Se o sistema utiliza cookies de sessão (padrão do PHP), destrói o cookie no navegador
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(), 
        '', 
        time() - 42000,
        $params["path"], 
        $params["domain"], 
        $params["secure"], 
        $params["httponly"]
    );
}

// 4. Destrói a sessão no servidor definitivamente
session_destroy();

// 5. Redireciona o usuário de volta para a tela de login que está na raiz do projeto
header("Location: ../login.php");
exit;
?>