<?php
require 'config.php';
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    // Redireciona para a página de login ou exibe uma mensagem de erro
    header("Location: login.php");
    exit;
}

// Obtém o ID do usuário logado
$userId = $_SESSION['user_id'];

// Verifica a permissão do usuário
require 'config.php';
$consulta = $pdo->prepare("SELECT tipo FROM usuarios WHERE id = :userId");
$consulta->bindValue(':userId', $userId, PDO::PARAM_INT);
$consulta->execute();

// Obtém o tipo de permissão do usuário
$tipoUsuario = $consulta->fetchColumn();

// Verifica se o usuário tem permissão para acessar a tela
if ($tipoUsuario != 'administrador') {
    header("Location: Tela_colaborador.php");
    exit;
}

$activeTabTasks = "";
$activeTabBuscar = "";

if (isset($_POST['form'])) {
    if ($_POST['form'] === 'taskForm') {
        $activeTabTasks = "active";
    } elseif ($_POST['form'] === 'buscar') {
        $activeTabBuscar = "active";
    }
}

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Gerenciamento de Tarefas</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/css/bootstrap.min.css">
    <style>
        .completed {
            text-decoration: line-through;
            -webkit-text-decoration: line-through;
            -moz-text-decoration: line-through;
        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <h1 class="mb-4">Sistema de Gerenciamento de Tarefas</h1>
        <nav>
            <ul class="nav nav-tabs mb-4">
                <li class="nav-item">
                    <a class="nav-link" id="taskTab" data-bs-toggle="tab" href="#tasks">Tarefas</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="buscarTab" data-bs-toggle="tab" href="#buscar">Buscar</a>
                </li>
                <li class="nav-item ms-auto">
                    <a href="Login.php" class="nav-link">Sair</a>
                </li>
                <li class="nav-item mt-2">
                    <span class="text-primary">
                        <?php echo "Usuario Logado - " . $_SESSION['user_name']; ?>
                    </span>
                </li>
            </ul>
        </nav>

        <div class="tab-content">
            <div class="tab-pane fade show active" id="tasks">
                <form action="cadastrar.php" method="POST" id="taskForm" name="form">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="taskInput" class="form-label">Nova Tarefa:</label>
                                <input name="tarefa" type="text" id="taskInput" class="form-control"
                                    placeholder="Digite uma nova tarefa" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="taskDateInput" class="form-label">Data:</label>
                                <input name="data" type="date" id="taskDateInput" class="form-control" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="assignSelect" class="form-label">Usuário:</label>
                                <select name="usuario" id="assignSelect" class="form-control">
                                    <option value="self">Selecione uma opção</option>
                                    <?php
                                    // Consulta para obter a lista de usuários colaboradores existentes
                                    $stmt = $pdo->prepare("SELECT * FROM usuarios");
                                    $stmt->execute();
                                    while ($usuario = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                        echo '<option value="' . $usuario['id'] . '">' . $usuario['nome'] . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="prioritySelect" class="form-label">Prioridade:</label>
                                        <select name="prioridade" id="prioritySelect" class="form-control">
                                            <option value="Baixa">Baixa</option>
                                            <option value="Média">Média</option>
                                            <option value="Alta">Alta</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="statusSelect" class="form-label">Status:</label>
                                        <select name="status" id="statusSelect" class="form-control">
                                            <option value="Para fazer">Para fazer</option>
                                            <option value="Em andamento">Em andamento</option>
                                            <option value="Concluída">Concluída</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Adicionar</button>
                </form>
                <br>
                <div class="container">
                    <table class="table table-striped">
                        <thead class="thead-dark thead-dark border">
                            <tr>
                                <th>Descrição da Tarefa</th>
                                <th>Data limite</th>
                                <th>Responsável</th>
                                <th>Prioridade</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql = "SELECT tasks.id, tasks.task_text, tasks.task_date, usuarios.nome AS assigned_user, tasks.priority_value, tasks.status
                                    FROM tasks
                                    JOIN usuarios ON tasks.assign_value = usuarios.id";

                            $result = $pdo->query($sql);

                            while ($dados = $result->fetch(PDO::FETCH_ASSOC)):
                                ?>
                                <tr>
                                    <td>
                                        <?php echo $dados["task_text"]; ?>
                                    </td>
                                    <td>
                                        <?php echo $dados["task_date"]; ?>
                                    </td>
                                    <td>
                                        <?php echo $dados["assigned_user"]; ?>
                                    </td>
                                    <td>
                                        <?php echo $dados["priority_value"]; ?>
                                    </td>
                                    <td>
                                        <?php echo $dados["status"]; ?>
                                    </td>
                                    <td>
                                        <a href="editar_tarefa.php?id=<?php echo $dados['id']; ?>">Editar</a>
                                        <a href="excluir_tarefa.php?id=<?php echo $dados['id']; ?>"
                                            class="text-danger">Excluir</a>
                                    </td>
                                </tr>
                                <?php
                            endwhile;
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>



            <div class="tab-pane fade" id="buscar">

                <h2>Buscar</h2>
                <form method="POST" action="Tela_administrador.php" id="buscar" name="form">
                    <div class="mb-3">
                        <label for="statusFilter" class="form-label"><b>Filtrar por Status:</b></label>
                        <select class="form-select" name="statusFilter" id="statusFilter">
                            <option value="all">Todos</option>
                            <option value="Para fazer">Para fazer</option>
                            <option value="Em andamento">Em andamento</option>
                            <option value="Concluída">Concluída</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary" name="filterBtn">Filtrar</button>
                </form>

                <?php
                // Incluir arquivo de configuração
                require 'config.php';

                // Verificar se o filtro de status foi enviado via método POST
                if (isset($_POST['statusFilter'])) {
                    $statusFilter = $_POST['statusFilter'];
                } else {
                    // Caso contrário, definir o valor padrão do filtro como "Para fazer"
                    $statusFilter = "Para fazer";
                }

                // Montar a consulta SQL baseada no valor do filtro de status
                $sql = "SELECT tasks.id, tasks.task_text, tasks.task_date, usuarios.nome AS assign_user, tasks.priority_value, tasks.status
                FROM tasks
                INNER JOIN usuarios ON tasks.assign_value = usuarios.id";

                if ($statusFilter != 'all') {
                    $sql .= " WHERE tasks.status = :statusFilter";
                }

                // Preparar a consulta SQL
                $stmt = $pdo->prepare($sql);

                // Bind do parâmetro do filtro de status
                if ($statusFilter != 'all') {
                    $stmt->bindParam(':statusFilter', $statusFilter);
                }

                // Executar a consulta
                $stmt->execute();
                ?>

                <?php
                // Verificar se há resultados
                if ($stmt->rowCount() > 0) {
                    // Cabeçalho da tabela
                    echo "<table class='table table-striped'>
                <thead class='thead-dark'>
                <tr>
                <th>ID</th>
                <th>Tarefa</th>
                <th>Data</th>
                <th>Usuário</th>
                <th>Prioridade</th>
                <th>Status</th>
                </tr>
                </thead>
                <tbody>";

                    // Dados das linhas
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        echo "<tr>
                <td>" . $row["id"] . "</td>
                <td>" . $row["task_text"] . "</td>
                <td>" . $row["task_date"] . "</td>
                <td>" . $row["assign_user"] . "</td>
                <td>" . $row["priority_value"] . "</td>
                <td>" . $row["status"] . "</td>
                </tr>";
                    }

                    // Fechamento da tabela
                    echo "</tbody>
                </table>";
                } else {
                    echo "Nenhum resultado encontrado.";
                }
                ?>

                <form method="POST" action="Tela_administrador.php" id="buscar_usuario" name="form">
                    <div class="mb-3">
                        <label for="userFilter" class="form-label"><b>Filtrar por Usuário:</b></label>
                        <select class="form-select" name="userFilter" id="userFilter">
                            <option value="all">Todos</option>

                            <?php
                            // Consulta para obter os usuários do banco de dados
                            $sql = "SELECT id, nome FROM usuarios";
                            $stmt = $pdo->prepare($sql);
                            $stmt->execute();

                            // Iterar sobre os resultados e criar as opções do campo de seleção
                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                $userId = $row['id'];
                                $userName = $row['nome'];
                                echo "<option value=\"$userId\">$userName</option>";
                            }
                            ?>

                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary" name="filterBtn">Filtrar</button>
                </form>

                <?php
                // Incluir arquivo de configuração
                require 'config.php';

                // Verificar se o filtro de usuário foi enviado via método POST
                if (isset($_POST['userFilter'])) {
                    $userFilter = $_POST['userFilter'];
                } else {
                    // Caso contrário, definir o valor padrão do filtro como "all" (todos os usuários)
                    $userFilter = "all";
                }

                // Montar a consulta SQL baseada no valor do filtro de usuário
                $sql = "SELECT tasks.id, tasks.task_text, tasks.task_date, usuarios.nome AS assign_user, tasks.priority_value, tasks.status
    FROM tasks
    INNER JOIN usuarios ON tasks.assign_value = usuarios.id";

                if ($userFilter != 'all') {
                    $sql .= " WHERE usuarios.id = :userFilter";
                }

                // Preparar a consulta SQL
                $stmt = $pdo->prepare($sql);

                // Bind do parâmetro do filtro de usuário
                if ($userFilter != 'all') {
                    $stmt->bindParam(':userFilter', $userFilter);
                }

                // Executar a consulta
                $stmt->execute();
                ?>

                <?php
                // Verificar se há resultados
                if ($stmt->rowCount() > 0) {
                    // Cabeçalho da tabela
                    echo "<table class='table table-striped'>
        <thead class='thead-dark'>
        <tr>
        <th>ID</th>
        <th>Tarefa</th>
        <th>Data</th>
        <th>Usuário</th>
        <th>Prioridade</th>
        <th>Status</th>
        </tr>
        </thead>
        <tbody>";

                    // Dados das linhas
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        echo "<tr>
        <td>" . $row["id"] . "</td>
        <td>" . $row["task_text"] . "</td>
        <td>" . $row["task_date"] . "</td>
        <td>" . $row["assign_user"] . "</td>
        <td>" . $row["priority_value"] . "</td>
        <td>" . $row["status"] . "</td>
        </tr>";
                    }

                    // Fechamento da tabela
                    echo "</tbody>
        </table>";
                } else {
                    echo "Nenhum resultado encontrado.";
                }
                ?>

            </div>

        </div>


    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>
</body>

</html>