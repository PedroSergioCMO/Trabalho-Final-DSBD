<?php

require 'config.php';


// Verificar se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Consulta para verificar as credenciais do usuário
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = :email");
    $stmt->bindParam(':email', $username);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Verificar a senha
        if (password_verify($password, $user['senha'])) {
            // Autenticação bem-sucedida
            session_start();
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_type'] = $user['tipo'];
            $_SESSION['user_name'] = $user['nome'];

            // Verificar o tipo de usuário e redirecionar para a página correta
            if ($user['tipo'] == 'administrador') {
                header("Location: Tela_administrador.php");
            } elseif ($user['tipo'] == 'colaborador') {
                header("Location: Tela_colaborador.php");
            }
            exit();
        } else {
            // Senha incorreta
            $error_message = "Senha incorreta. Tente novamente.";
        }
    } else {
        // Usuário não encontrado
        $error_message = "Usuário não encontrado. Tente novamente.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Tela de Login</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f5f5f5;
        }
        .login-form {
            width: 350px;
            margin: 0 auto;
            margin-top: 100px;
        }
        .login-form form {
            margin-bottom: 15px;
            background: #fff;
            border: 1px solid #f3f3f3;
            border-radius: 5px;
            padding: 30px;
        }
        .login-form h2 {
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
    <div class="login-form">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
            <h2 class="text-center">Login</h2>
            <?php if (isset($error_message)) { ?>
                <p class="error-message"><?php echo $error_message; ?></p>
            <?php } ?>
            <div class="form-group">
                <input type="text" class="form-control" name="username" placeholder="Email" required="required">
            </div>
            <div class="form-group">
                <input type="password" class="form-control" name="password" placeholder="Senha" required="required">
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary btn-block">Entrar</button>
            </div>
        </form>
        <p class="text-center">Ainda não possui uma conta? <a href="cadastro.php">Cadastre-se</a></p>
    </div>
</body>
</html>
