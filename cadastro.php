<!-- Flávia Glenda Guimarães Carvalho -->
<?php
include './php/connection.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    if (isset($_POST['nome']) && isset($_POST['email']) && isset($_POST['senha'])) {
        $nome = trim($_POST['nome']);
        $email = trim($_POST['email']);
        $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);

        $query = "INSERT INTO usuarios (nome, email, senha) VALUES (?, ?, ?)";
        $statement = $connection->prepare($query);
        $statement->bind_param("sss", $nome, $email, $senha);
        $statement->execute();

        if ($connection->error) {
            die("Erro: {$connection->error}");
        }

        $_SESSION['logged'] = true;
        $_SESSION['name'] = $nome;

        header('Location: index.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/cadastro.css">
    <title>Formulário de cadastro</title>
    <script src="./js/script.js"></script>
</head>

<body>

    <form class="formularioCadastro" action="" method="post">
      <img class="logo" src="./img/task_logoazul.png"/>
        <section>
            <label for="nome">Nome: </label>
            <input class="inputFormulario" type="text" name="nome" id="nome" required>
        </section>

        <section>
            <label for="email">Email: </label>
            <input class="inputFormulario" type="email" name="email" id="email" required>
        </section>

        <section>
            <label class="inputFormulario" for="senha">Senha: </label>
            <input class="inputFormulario" type="password" name="senha" id="senha" required>
        </section>

        <button type="submit" class="botaoCadastrar">Cadastrar</button>
    </form>

</body>

</html>