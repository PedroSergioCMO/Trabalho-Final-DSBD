<?php 
require 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recupera os dados do formulário
    $taskId = $_POST['task_id'];
    $status = $_POST['status'];

    // Atualiza a tarefa no banco de dados
    $dados = $pdo->prepare("UPDATE tasks SET status = ? WHERE id = ?");
    $dados->execute([$status, $taskId]);

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
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Editar Tarefa do Colaborador</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h1>Editar Tarefa do Colaborador</h1>
        <form method="POST" action="editar_tarefa_colaborador.php" class="mt-4">
            <input type="hidden" name="task_id" value="<?php echo $taskId; ?>">
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
