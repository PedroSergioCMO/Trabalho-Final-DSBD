<?php


require 'config.php';

$tarefa = $_POST['tarefa'];
$data = $_POST['data'];
$usuario = $_POST['usuario'];
$prioridade = $_POST['prioridade'];
$status = $_POST['status'];


$sql = $pdo->prepare("INSERT INTO `tasks`(`task_text`, `task_date`, `assign_value`, `priority_value`, `status`) VALUES (?, ?, ?, ?, ?)");
$sql->bindValue(1, $tarefa);
$sql->bindValue(2, $data);
$sql->bindValue(3, $usuario);
$sql->bindValue(4, $prioridade);
$sql->bindValue(5, $status);
$sql->execute();

if($sql) {
    header("Location:Tela_administrador.php");

    exit;
}

