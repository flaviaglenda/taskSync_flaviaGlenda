<!-- Flávia Glenda Guimarães Carvalho -->
<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/nav.css">
    <title>Início</title>
</head>
<body>
<nav>
    <div class="nav__header">
        <div class="nav__logo">
            <a href="#">
                <img src="./img/task_logoazul.png" alt="logo" />
            </a>
        </div>
        <div class="nav__menu__btn" id="menu-btn">
            <i class="ri-menu-3-line"></i>
        </div>
    </div>
    <ul class="nav__links" id="nav-links">
        <li><a href="./index.php">Início</a></li>
        <li><a href="./gerenciarTarefas.php">Gerenciar tarefas</a></li>
        <li><a href="./cadastro.php">Sair</a></li>
    </ul>

<div class="nav__btns">
    <div id="usuario">
        <?php
        $name = isset($_SESSION['name']) ? $_SESSION['name'] : 'Usuário';
        if (isset($_SESSION['logged']) && $_SESSION['logged'] == true) {
            echo '<img id="uso" src="./img/loginn.png" style="width: 70px;" alt="">';
            echo '<p>' . htmlspecialchars($name) . '</p>';
            
        } else {
            echo '<a href="./cadastro.php"><button class="btn">Conectar-se</button></a>';
        }
        ?>
    </div>
</div>
<p>ㅤ</p>
<p>ㅤ</p>
<p>ㅤ</p>
</nav>
<h1> Tela de início</h1>
</body>
</html>