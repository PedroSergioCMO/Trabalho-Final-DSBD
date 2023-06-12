<?php 
require 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recupera os dados do formulário
    $taskId = $_POST["task_id"];
    $taskText = $_POST["task_text"];
    $taskDate = $_POST["task_date"];
    $assignValue = $_POST["assign_value"];
    $priorityValue = $_POST["priority_value"];
    $status = $_POST['status'];

    // Atualiza a tarefa no banco de dados
    $dados = $pdo->prepare("UPDATE tasks SET task_text = ?, task_date = ?, assign_value = ?, priority_value = ?, status = ? WHERE id = ?");
    $dados->execute([$taskText, $taskDate, $assignValue, $priorityValue, $status, $taskId]);

    // Redireciona para a página de listagem de tarefas
    header("Location: Tela_administrador.php");
    exit();
} else {
    // Obtém o ID da tarefa da URL
    $taskId = $_GET["id"];

    // Busca a tarefa no banco de dados
    $dados = $pdo->prepare("SELECT * FROM tasks WHERE id = ?");
    $dados->execute([$taskId]);
    $task = $dados->fetch(PDO::FETCH_ASSOC);

    // Busca todos os usuários no banco de dados
    $usuarios = $pdo->query("SELECT * FROM usuarios")->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Editar Tarefa</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h1>Editar Tarefa</h1>
        <form method="POST" action="editar_tarefa.php" class="mt-4">
            <input type="hidden" name="task_id" value="<?php echo $taskId; ?>">
            <div class="mb-3">
                <label for="task_text" class="form-label">Texto da Tarefa:</label>
                <input type="text" name="task_text" class="form-control" value="<?php echo $task["task_text"]; ?>">
            </div>
            <div class="mb-3">
                <label for="task_date" class="form-label">Data da Tarefa:</label>
                <input type="date" name="task_date" class="form-control" value="<?php echo $task["task_date"]; ?>">
            </div>
            <div class="mb-3">
                <label for="assign_value" class="form-label">Valor do Assign:</label>
                <select name="assign_value" class="form-select">
                    <?php foreach ($usuarios as $usuario) { ?>
                        <option value="<?php echo $usuario['id']; ?>" <?php if ($task["assign_value"] == $usuario['id']) echo "selected"; ?>>
                            <?php echo $usuario['nome']; ?>
                        </option>
                    <?php } ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="priority_value" class="form-label">Valor da Prioridade:</label>
                <select name="priority_value" class="form-select">
                    <option value="Alta" <?php if ($task["priority_value"] == "Alta") echo "selected"; ?>>Alta</option>
                    <option value="Baixa" <?php if ($task["priority_value"] == "Baixa") echo "selected"; ?>>Baixa</option>
                    <option value="Média" <?php if ($task["priority_value"] == "Média") echo "selected"; ?>>Média</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="status" class="form-label">Status da Tarefa:</label>
                <select name="status" class="form-select">
                    <option value="Para fazer" <?php if ($task["status"] == "Para fazer") echo "selected"; ?>>Para fazer</option>
                    <option value="Em andamento" <?php if ($task["status"] == "Em andamento") echo "selected"; ?>>Em andamento</option>
                    <option value="Concluída" <?php if ($task["status"] == "Concluída") echo "selected"; ?>>Concluída</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Salvar</button>
        </form>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>
</body>
</html>
