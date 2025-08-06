<?php
header('Content-Type: application/json'); // Define o cabeçalho para JSON

// Configurações do banco de dados
$servername = "localhost"; // Geralmente 'localhost'
$username = "root"; // Altere para seu usuário do MySQL
$password = "";   // Altere para sua senha do MySQL
$dbname = "farmacia_db";

// Cria a conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica a conexão
if ($conn->connect_error) {
    // Retorna um erro JSON se a conexão falhar
    echo json_encode(['error' => 'Falha na conexão com o banco de dados: ' . $conn->connect_error]);
    exit();
}

// Consulta SQL para selecionar todos os registros da tabela VendasMedicamentos
$sql = "SELECT * FROM VendasMedicamentos";
$result = $conn->query($sql);

$vendas = [];

if ($result->num_rows > 0) {
    // Pega cada linha e adiciona ao array de vendas
    while($row = $result->fetch_assoc()) {
        $vendas[] = $row;
    }
}

// Fecha a conexão com o banco de dados
$conn->close();

// Retorna os dados como JSON
echo json_encode($vendas);

?>