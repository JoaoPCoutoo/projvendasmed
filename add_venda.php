<?php
header('Content-Type: application/json');
 
// Configurações do banco de dados
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "farmacia_db";
 
// Cria a conexão
$conn = new mysqli($servername, $username, $password, $dbname);
 
// Verifica a conexão
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Falha na conexão com o banco de dados: ' . $conn->connect_error]);
    exit;
}
 
// Verifica se a requisição é do tipo POST e se os dados foram enviados
if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST)) {
    // Sanitiza e pega os dados do POST
    $data_venda = $_POST['DataVenda'] ?? '';
    $produto = $conn->real_escape_string($_POST['Produto'] ?? '');
    $categoria = $conn->real_escape_string($_POST['Categoria'] ?? '');
    $quantidade = intval($_POST['Quantidade'] ?? 0);
    $preco_unitario = floatval($_POST['PrecoUnitario'] ?? 0);
    $valor_total = $quantidade * $preco_unitario;
    $cliente = $conn->real_escape_string($_POST['Cliente'] ?? '');
    $metodo_pagamento = $conn->real_escape_string($_POST['MetodoPagamento'] ?? '');
    $status = $conn->real_escape_string($_POST['Status'] ?? 'Concluída'); // Valor padrão
 
    // Validação básica dos dados
    if (empty($data_venda) || empty($produto) || $quantidade <= 0 || $preco_unitario <= 0) {
        echo json_encode(['success' => false, 'message' => 'Campos obrigatórios estão faltando ou são inválidos.']);
        $conn->close();
        exit;
    }
 
    // Usando prepared statement para evitar SQL Injection
    $stmt = $conn->prepare("INSERT INTO VendasMedicamentos (DataVenda, Produto, Categoria, Quantidade, PrecoUnitario, ValorTotal, Cliente, MetodoPagamento, Status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssiddsss", $data_venda, $produto, $categoria, $quantidade, $preco_unitario, $valor_total, $cliente, $metodo_pagamento, $status);
 
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Venda adicionada com sucesso!', 'id' => $conn->insert_id]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Erro ao adicionar a venda: ' . $stmt->error]);
    }
 
    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Método de requisição inválido ou dados ausentes.']);
}
 
$conn->close();
?>
 