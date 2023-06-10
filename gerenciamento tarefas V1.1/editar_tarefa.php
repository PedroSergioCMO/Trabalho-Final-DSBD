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

}
?>

<!-- Formulário de edição da tarefa -->
<form method="POST" action="editar_tarefa.php">
    <input type="hidden" name="task_id" value="<?php echo $taskId; ?>">
    <label for="task_text">Texto da Tarefa:</label>
    <input type="text" name="task_text" value="<?php echo $task["task_text"]; ?>"><br>
    <label for="task_date">Data da Tarefa:</label>
    <input type="date" name="task_date" value="<?php echo $task["task_date"]; ?>"><br>
    <label for="assign_value">Valor do Assign:</label>
    <input type="text" name="assign_value" value="<?php echo $task["assign_value"]; ?>"><br>
    <label for="priority_value">Valor da Prioridade:</label>
    <select name="priority_value">
        <option value="Alta" <?php if ($task["priority_value"] == "Alta") echo "selected"; ?>>Alta</option>
        <option value="Baixa" <?php if ($task["priority_value"] == "Baixa") echo "selected"; ?>>Baixa</option>
        <option value="Média" <?php if ($task["priority_value"] == "Média") echo "selected"; ?>>Média</option>
    </select><br>
    <label for="status">Status da tarefa: </label>
    <select name="status">
        <option value="Para fazer" <?php if ($task["status"] == "Para fazer") echo "selected"; ?>>Para fazer</option>
        <option value="Em andamento" <?php if ($task["status"] == "Em andamento") echo "selected"; ?>>Em andamento</option>
        <option value="Concluida" <?php if ($task["status"] == "Concluida") echo "selected"; ?>>Concluida</option>
    </select><br>
    <button type="submit">Salvar</button>
</form>
