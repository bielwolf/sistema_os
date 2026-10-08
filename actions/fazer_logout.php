<?php 

session_start();

// Esvazia todas as variáveis salvas no array $_SESSION
$_SESSION = array();

// Se o cookie da sessão existir, força a expiração dele no navegador do usuário
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Destrói a sessão
session_destroy();

// Redireciona para a página de login
header("Location: ../views/login.php");
exit();

?>