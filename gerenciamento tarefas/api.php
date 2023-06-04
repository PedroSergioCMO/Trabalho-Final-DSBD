<?php
$host = 'localhost';
$user = 'root';
$password = '12345';
$database = 'tarefas';

$connection = new mysqli($host, $user, $password, $database);

if ($connection->connect_error) {
  die('Erro na conexão com o banco de dados: ' . $connection->connect_error);
}

$action = $_GET['action'];

if ($action === 'addTask') {
  $requestData = json_decode(file_get_contents('php://input'), true);

  $taskText = $requestData['texto'];
  $taskDate = $requestData['data_conclusao'];
  $assign = $requestData['assign'];
  $priority = $requestData['priority'];

  $sql = "INSERT INTO tasks (texto, data_conclusao, assign, priority) VALUES ('$taskText', '$taskDate', '$assign', '$priority')";

  if ($connection->query($sql) === TRUE) {
    $response = ['success' => true, 'message' => 'Tarefa adicionada com sucesso'];
  } else {
    $response = ['success' => false, 'message' => 'Erro ao adicionar a tarefa: ' . $connection->error];
  }

  echo json_encode($response);
} elseif ($action === 'deleteTask') {
  $requestData = json_decode(file_get_contents('php://input'), true);

  $taskText = $requestData['texto'];

  $sql = "DELETE FROM tasks WHERE texto='$taskText'";

  if ($connection->query($sql) === TRUE) {
    $response = ['success' => true, 'message' => 'Tarefa excluída com sucesso'];
  } else {
    $response = ['success' => false, 'message' => 'Erro ao excluir a tarefa: ' . $connection->error];
  }

  echo json_encode($response);
} elseif ($action === 'getTasks') {
  $sql = "SELECT * FROM tasks";

  $result = $connection->query($sql);

  $tasks = [];

  if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
      $tasks[] = [
        'texto' => $row['texto'],
        'data_conclusao' => $row['data_conclusao'],
        'assign' => $row['assign'],
        'priority' => $row['priority']
      ];
    }
  }

  echo json_encode($tasks);
}

$connection->close();
?>