<!-- Flávia Glenda Guimarães Carvalho -->
<?php 
$connected = true;

$connectionconfig = include 'connectionconfig.php';

$connection = new mysqli(
    $connectionconfig['hostname'],
    $connectionconfig['username'],
    $connectionconfig['password'],
    $connectionconfig['database']
);

if ($connection->connect_error) {
    $connected = false;
    die('Connection failed: ' . $connection->connect_error);
}

unset($connectionconfig);