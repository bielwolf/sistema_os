<?php 

session_start();

// Remove todas as variáveis de sessão
session_unset();

// Destrói a sessãp
session_destroy();

// Redireciona para a página de login
header("Location: ../views/login.php");
exit();

?>