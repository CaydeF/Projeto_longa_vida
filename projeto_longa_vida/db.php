<?php
$servername = "localhost"; // Host do banco de dados
$username = "root";        // Usuário do banco de dados
$password = "";            // Senha do banco de dados
$dbname = "longa_vida";    // Nome do banco de dados

// Criando a conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificando se houve erro na conexão
if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}
?>
