<?php

// 1. Configurações de acesso ao banco local
$host = 'localhost';
$db = 'sistema_os';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

// 2. Construção do DSN
$dns = "mysql:host=$host;dbname=$db;charset=$charset";

// 3. Opções de segurança e comportamento do PDO 
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];


try {
    // 4. Instancia a conexão centralizado na variável $pdo
    $pdo = new PDO($dns, $user, $pass, $options);
} catch (PDOException $e) {
    // Em ambiente de desenvolvimento, mata a execução e mostra a mensagem de erro
    die("Erro ao conectar ao banco de dados: " . $e->getMessage());
}
