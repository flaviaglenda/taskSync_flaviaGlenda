<!-- Flávia Glenda Guimarães Carvalho -->
<?php
session_start();
if (!isset($_SESSION['logged']) || $_SESSION['logged'] !== true) {
    header("Location: index.php");
    exit();
}
?>
