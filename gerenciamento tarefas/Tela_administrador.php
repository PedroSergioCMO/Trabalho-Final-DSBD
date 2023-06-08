<?php

require 'config.php';
require 'config_usuarios.php';

?>



<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Gerenciamento de Tarefas</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.css">
    <style>
        .completed {
            text-decoration: line-through;
        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <h1 class="mb-4">Sistema de Gerenciamento de Tarefas</h1>

        <ul class="nav nav-tabs mb-4">
            <li class="nav-item">
                <a class="nav-link active" id="taskTab" data-bs-toggle="tab" href="#tasks">Tarefas</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="calendarTab" data-bs-toggle="tab" href="#calendar">Calendário</a>
            </li>
        </ul>

        <div class="tab-content">
            <div class="tab-pane fade show active" id="tasks">
                <form action="cadastrar.php" method="POST" id="taskForm">
                    <div class="row">
                        <div class="col-md-9">
                            <div class="input-group">
                                <input name="tarefa" type="text" id="taskInput" class="form-control"
                                    placeholder="Digite uma nova tarefa" required>
                                <input name="data" type="date" id="taskDateInput" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                        <select name="usuario" id="assignSelect" class="form-control">
                                <option value="self">Eu mesmo</option>
                                <?php
                                // Consulta para obter a lista de usuários colaboradores existentes
                                $stmt = $pdo2->prepare("SELECT * FROM usuarios WHERE tipo = 'colaborador'");
                                $stmt->execute();
                                while ($usuario = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                    echo '<option value="' . $usuario['id'] . '">' . $usuario['nome'] . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select name="prioridade" id="prioritySelect" class="form-control">
                                <option value="Baixa">Baixa</option>
                                <option value="Média">Média</option>
                                <option value="Alta">Alta</option>
                            </select>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Adicionar</button>
                </form>

                <div class="row mt-3">
                    <div class="col-md-3">
                        <select id="filterSelect" class="form-control">
                            <option value="all">Todas as Tarefas</option>
                            <option value="completed">Tarefas Concluídas</option>
                            <option value="pending">Tarefas Pendentes</option>
                        </select>
                    </div>
                </div>

                <ul id="taskList" class="list-group mt-3"></ul>
            </div>

            <div class="container">
                <table class="table table-striped">
                    <thead class="thead-dark">
                        <tr>
                            <th>Descrição da Tarefa</th>
                            <th>Data limite</th>
                            <th>Responsável</th>
                            <th>Prioridade</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $lista2 = $pdo2->prepare("SELECT * FROM usuarios");
                        $lista2->execute();
                        $lista = $pdo->prepare("SELECT * FROM tasks");
                        $lista->execute();

                        while ($dados = $lista->fetch(PDO::FETCH_ASSOC) and $dados2 = $lista2->fetch(PDO::FETCH_ASSOC)):
                            ?>
                            <tr>
                                <td>
                                    <?php echo $dados["task_text"]; ?>
                                </td>
                                <td>
                                    <?php echo $dados["task_date"]; ?>
                                </td>
                                <td>
                                    <?php echo $dados2["nome"]; ?>
                                </td>
                                <td>
                                    <?php echo $dados["priority_value"]; ?>
                                </td>
                                <td>
                                    <a href="editar_tarefa.php?id=<?php echo $dados['id']; ?>">Editar</a>
                                    <a href="excluir_tarefa.php?id=<?php echo $dados['id']; ?>">Excluir</a>
                                </td>
                            </tr>
                            <?php

                        endwhile;
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.js"></script>
    <!-- <script src="script.js"></script> -->
</body>

</html>