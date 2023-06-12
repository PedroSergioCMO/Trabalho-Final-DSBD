<?php

require 'config.php';

// Função para cadastrar um novo usuário
function cadastrarUsuario($nome, $email, $senha, $tipo) {
    global $pdo;
    // Verificar se o email já está em uso
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($user) {
        return "O email fornecido já está em uso.";
    }
    // Cadastrar o novo usuário
    $hashSenha = password_hash($senha, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT INTO usuarios (nome, email, senha, tipo) VALUES (:nome, :email, :senha, :tipo)");
    $stmt->bindParam(':nome', $nome);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':senha', $hashSenha);
    $stmt->bindParam(':tipo', $tipo);
    $stmt->execute();
    return null; // Sem erros, retorna null
}

// Verificar se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST["nome"];
    $email = $_POST["email"];
    $senha = $_POST["senha"];
    $tipo = $_POST["tipo"]; // Obter o valor selecionado do tipo de usuário

    $error = cadastrarUsuario($nome, $email, $senha, $tipo);

    if (!$error) {
        // Cadastro bem-sucedido, redirecionar para a página de login
        header("Location: login.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Tela de Cadastro</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f5f5f5;
        }
        .register-form {
            width: 350px;
            margin: 0 auto;
            margin-top: 100px;
        }
        .register-form form {
            margin-bottom: 15px;
            background: #fff;
            border: 1px solid #f3f3f3;
            border-radius: 5px;
            padding: 30px;
        }
        .register-form h2 {
            margin: 0 0 15px;
        }
        .form-control, .btn {
            min-height: 38px;
            border-radius: 2px;
        }
        .btn {
            font-size: 15px;
            font-weight: bold;
        }
        .error-message {
            color: red;
        }
    </style>
</head>
<body>
    <div class="register-form">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
            <h2 class="text-center">Cadastro</h2>
            <?php if (isset($error)) { ?>
                <p class="error-message"><?php echo $error; ?></p>
            <?php } ?>
            <div class="form-group">
                <input type="text" class="form-control" name="nome" placeholder="Nome" required="required">
            </div>
            <div class="form-group">
                <input type="email" class="form-control" name="email" placeholder="E-mail" required="required">
            </div>
            <div class="form-group">
                <input type="password" class="form-control" name="senha" placeholder="Senha" required="required">
            </div>
            <div class="form-group">
                <select class="form-control" name="tipo">
                    <option value="administrador">Administrador</option>
                    <option value="colaborador">Colaborador</option>
                </select>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary btn-block">Cadastrar</button>
            </div>
        </form>
        <p class="text-center">Já possui uma conta? <a href="login.php">Faça login</a></p>
    </div>
</body>
</html>
