<?php

require 'config.php';
// Obtém o ID da tarefa da URL
$taskId = $_GET["id"];

// Exclui a tarefa do banco de dados
$dados = $pdo->prepare("DELETE FROM tasks WHERE id = ?");
$dados->execute([$taskId]);

// Redireciona para a página de listagem de tarefas
header("Location: Tela_administrador.php");
exit();
?>
