<!-- <?php
// Incluir arquivo de configuração
require 'config.php';

// Obter o valor do filtro de status
$statusFilter = $_POST['statusFilter'];

// Montar a consulta SQL baseada no valor do filtro de status
$sql = "SELECT tasks.id, tasks.task_text, tasks.task_date, usuarios.nome AS assign_user, tasks.priority_value, tasks.status
        FROM tasks
        INNER JOIN usuarios ON tasks.assign_value = usuarios.id";

if ($statusFilter != 'todas') {
    $sql .= " WHERE tasks.status = :statusFilter";
}

// Preparar a consulta SQL
$stmt = $pdo->prepare($sql);

// Bind do parâmetro do filtro de status
if ($statusFilter != 'todas') {
    $stmt->bindParam(':statusFilter', $statusFilter);
}

// Executar a consulta
$stmt->execute();
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Gerenciamento de Tarefas</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/css/bootstrap.min.css">
</head>

<body>
    <div class="container mt-5">
        <h1 class="mb-4">Sistema de Gerenciamento de Tarefas</h1>

        <ul class="nav nav-tabs mb-4">
            
            <li class="nav-item ms-left">
                <a href="Tela_administrador.php" class="nav-link">Voltar</a>
            </li>
            <li class="nav-item ms-auto">
                <a href="Login.php" class="nav-link">Sair</a>
            </li>
        </ul>

        <form method="POST" action="buscar.php">
            <div class="mb-3">
                <label for="statusFilter" class="form-label">Filtrar por Status:</label>
                <select class="form-select" name="statusFilter" id="statusFilter">
                    <option value="todas">Todas</option>
                    <option value="Para fazer">Para fazer</option>
                    <option value="Em andamento">Em andamento</option>
                    <option value="Concluída">Concluída</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary" name="filterBtn">Filtrar</button>
        </form>

        <br>

        <?php
        // // Verificar se há resultados
        // if ($stmt->rowCount() > 0) {
        //     // Cabeçalho da tabela
        //     echo "<table class='table table-striped'>
        //             <thead class='thead-dark'>
        //                 <tr>
        //                     <th>ID</th>
        //                     <th>Tarefa</th>
        //                     <th>Data</th>
        //                     <th>Usuário</th>
        //                     <th>Prioridade</th>
        //                     <th>Status</th>
        //                 </tr>
        //             </thead>
        //             <tbody>";

        //     // Dados das linhas
        //     while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        //         echo "<tr>
        //                 <td>".$row["id"]."</td>
        //                 <td>".$row["task_text"]."</td>
        //                 <td>".$row["task_date"]."</td>
        //                 <td>".$row["assign_user"]."</td>
        //                 <td>".$row["priority_value"]."</td>
        //                 <td>".$row["status"]."</td>
        //             </tr>";
        //     }

        //     // Fechamento da tabela
        //     echo "</tbody>
        //         </table>";
        // } else {
        //     echo "Nenhum resultado encontrado.";
        // }
        if($stmt) {
            header("Location:Tela_administrador.php");
        
            exit;
        }
        
        ?>

    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>
</body>

</html> -->
