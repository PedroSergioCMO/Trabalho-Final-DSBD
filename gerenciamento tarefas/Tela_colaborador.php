<?php

require 'config.php';
require 'config_usuarios.php';

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Gerenciamento de Tarefas - Colaborador</title>
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
        <h1 class="mb-4">Sistema de Gerenciamento de Tarefas - Colaborador</h1>

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
                <ul id="taskList" class="list-group mt-3">
                    <?php
                    $userId = $_SESSION['id']; // Supondo que você tenha armazenado o ID do usuário na variável $_SESSION['id']

                    // Consulta para obter as tarefas atribuídas ao colaborador atual
                    $stmt = $pdo->prepare("SELECT * FROM tasks WHERE assign_user_id = :userId");
                    $stmt->bindValue(':userId', $userId);
                    $stmt->execute();

                    while ($dados = $stmt->fetch(PDO::FETCH_ASSOC)):
                    ?>
                    <li class="list-group-item">
                        <!-- Exibir detalhes da tarefa... -->
                    </li>
                    <?php
                    endwhile;
                    ?>
                </ul>
            </div>

            <!-- Resto do código HTML... -->
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.js"></script>
    <!-- <script src="script.js"></script> -->
</body>

</html>
