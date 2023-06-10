<?php


require 'config.php';
session_start();

// Prepara a consulta SQL para obter apenas as tarefas do usuário atual
$consulta = $pdo->prepare("SELECT * FROM tasks WHERE assign_value = :userId");
$consulta->bindValue(':userId', $_SESSION['user_id'], PDO::PARAM_INT);
$consulta->execute();

?>

<!DOCTYPE html>
<html>
<head>
    <title>Tela do Colaborador</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h1>Tarefas do Colaborador</h1>

        <nav>
            <ul>
                <li><?php echo "Usuario Logado" . " - " . $_SESSION['user_name'] ?></li>
                <li><a href="Login.php">Sair</a></li>
            </ul>
        </nav>
        <table class="table table-striped">
            <thead class="thead-dark">
                <tr>
                    <th>Descrição da Tarefa</th>
                    <th>Data limite</th>
                    <th>Responsável</th>
                    <th>Prioridade</th>
                    <th>Status</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($dados = $consulta->fetch(PDO::FETCH_ASSOC)): ?>
                    <tr>
                        <td><?php echo $dados["task_text"]; ?></td>
                        <td><?php echo $dados["task_date"]; ?></td>
                        <td><?php echo $dados["assign_value"]; ?></td>
                        <td><?php echo $dados["priority_value"]; ?></td>
                        <td><?php echo $dados["status"]; ?></td>
                        <td>
                            <a href="editar_tarefa.php?id=<?php echo $dados['id']; ?>">Editar</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
