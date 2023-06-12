<?php
require 'config.php';
session_start();

if (isset($_POST['status_filter'])) {
    $statusFilter = $_POST['status_filter'];

    // Prepara a consulta SQL para obter apenas as tarefas do usuário atual com o status selecionado
    $consulta = $pdo->prepare("SELECT * FROM tasks WHERE assign_value = :userId AND status = :statusFilter");
    $consulta->bindValue(':userId', $_SESSION['user_id'], PDO::PARAM_INT);
    $consulta->bindValue(':statusFilter', $statusFilter, PDO::PARAM_STR);
    $consulta->execute();
} else {
    // Prepara a consulta SQL para obter todas as tarefas do usuário atual
    $consulta = $pdo->prepare("SELECT * FROM tasks WHERE assign_value = :userId");
    $consulta->bindValue(':userId', $_SESSION['user_id'], PDO::PARAM_INT);
    $consulta->execute();
}

// Consulta SQL para obter todas as tarefas do usuário atual
$consultaTodas = $pdo->prepare("SELECT * FROM tasks WHERE assign_value = :userId");
$consultaTodas->bindValue(':userId', $_SESSION['user_id'], PDO::PARAM_INT);
$consultaTodas->execute();
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
            <ul class="nav nav-tabs mb-4">
                <li class="nav-item">
                    <a class="nav-link active" id="taskTab" data-bs-toggle="tab" href="#tasks">Tarefas</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="buscarTab" data-bs-toggle="tab" href="#buscar">Buscar</a>
                </li>
                <li class="nav-item ms-auto">
                    <a href="Login.php" class="nav-link">Sair</a>
                </li>
                <li class="nav-item mt-2">
                    <span class="text-primary">
                        <?php echo "Usuário Logado - " . $_SESSION['user_name']; ?>
                    </span>
                </li>
            </ul>
        </nav>
        <div class="tab-content">
            <div class="tab-pane fade show active" id="tasks">
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
                        <?php while ($dados = $consultaTodas->fetch(PDO::FETCH_ASSOC)): ?>
                            <tr>
                                <td><?php echo $dados["task_text"]; ?></td>
                                <td><?php echo $dados["task_date"]; ?></td>
                                <td><?php echo $dados["assign_value"]; ?></td>
                                <td><?php echo $dados["priority_value"]; ?></td>
                                <td><?php echo $dados["status"]; ?></td>
                                <td><a href="editar_tarefa_colaborador.php?id=<?php echo $dados['id']; ?>">Editar</a></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
            <div class="tab-pane fade" id="buscar">
                <form method="post" class="mb-4">
                    <div class="form-group">
                        <label for="statusFilter">Filtrar por status:</label>
                        <select name="status_filter" id="statusFilter" class="form-control">
                            <option value="">Todos</option>
                            <option value="Concluído">Concluído</option>
                            <option value="Pendente">Pendente</option>
                            <option value="Em andamento">Em andamento</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Filtrar</button>
                </form>
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
                                <td><a href="editar_tarefa_colaborador.php?id=<?php echo $dados['id']; ?>">Editar</a></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>
</body>
</html>
