<!-- Flávia Glenda Guimarães Carvalho -->
<?php
include './php/connection.php';
include './php/auth.php';

function getEnumValues($connection, $table, $column)
{
    $result = $connection->query("SHOW COLUMNS FROM $table LIKE '$column'");
    $row = $result->fetch_assoc();
    preg_match('/enum\((.*?)\)/', $row['Type'], $matches);
    return explode(',', str_replace("'", '', $matches[1]));
}

$setores = getEnumValues($connection, 'tarefas', 'setor_empresa');

$alert = null;

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add'])) {
    $desc = $_POST['descricao_tarefa'];
    $setor = $_POST['setor_empresa'];
    $prioridade = $_POST['prioridade'];
    $status = $_POST['status'];
    $data = date('Y-m-d H:i:s');

    $stmt = $connection->prepare("INSERT INTO tarefas (descricao_tarefa, setor_empresa, prioridade, status, data_cadastro) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $desc, $setor, $prioridade, $status, $data);
    $stmt->execute();
    $alert = ['type' => 'success', 'message' => 'Tarefa adicionada com sucesso!'];
}

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $connection->query("DELETE FROM tarefas WHERE id_tarefa = $id");
    $alert = ['type' => 'warning', 'message' => 'Tarefa excluída.'];
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update'])) {
    $id = $_POST['id_tarefa'];
    $desc = $_POST['descricao_tarefa'];
    $setor = $_POST['setor_empresa'];
    $prioridade = $_POST['prioridade'];
    $status = $_POST['status'];

    $stmt = $connection->prepare("UPDATE tarefas SET descricao_tarefa=?, setor_empresa=?, prioridade=?, status=? WHERE id_tarefa=?");
    $stmt->bind_param("ssssi", $desc, $setor, $prioridade, $status, $id);
    $stmt->execute();
    $alert = ['type' => 'info', 'message' => 'Tarefa atualizada com sucesso!'];
}

$tarefas = $connection->query("SELECT * FROM tarefas ORDER BY data_cadastro DESC");
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Gerenciar Tarefas</title>
    <link rel="stylesheet" href="./css/gerenciarTarefas.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <nav class="navbar">
        <img src="./img/task_logoazul.png" class="logo" alt="Logo">
        <a href="./cadastro.php">Sair</a>
    </nav>

    <div class="container">
        <h2>Adicionar Tarefa</h2>
        <form class="form" method="POST">
            <input type="text" name="descricao_tarefa" placeholder="Descrição" required>
            <select name="setor_empresa" required>
                <?php foreach ($setores as $setor): ?>
                    <option value="<?= $setor ?>"><?= $setor ?></option>
                <?php endforeach; ?>
            </select>
            <select name="prioridade">
                <option value="baixa">Baixa</option>
                <option value="média">Média</option>
                <option value="alta">Alta</option>
            </select>
            <select name="status">
                <option value="a fazer">A fazer</option>
                <option value="fazendo">Fazendo</option>
                <option value="concluído">Concluído</option>
            </select>
            <button type="submit" name="add">Adicionar</button>
        </form>

        <h2>Lista de Tarefas</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>Descrição</th>
                <th>Setor</th>
                <th>Prioridade</th>
                <th>Status</th>
                <th>Data</th>
                <th>Ações</th>
            </tr>
            <?php while ($row = $tarefas->fetch_assoc()): ?>
                <tr>
                    <form method="POST">
                        <td><?= $row['id_tarefa'] ?></td>
                        <td><input name="descricao_tarefa" value="<?= $row['descricao_tarefa'] ?>"></td>
                        <td>
                            <select name="setor_empresa">
                                <?php foreach ($setores as $setor): ?>
                                    <option value="<?= $setor ?>" <?= $row['setor_empresa'] == $setor ? 'selected' : '' ?>><?= $setor ?></option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                        <td>
                            <select name="prioridade">
                                <option <?= $row['prioridade'] == 'baixa' ? 'selected' : '' ?>>baixa</option>
                                <option <?= $row['prioridade'] == 'média' ? 'selected' : '' ?>>média</option>
                                <option <?= $row['prioridade'] == 'alta' ? 'selected' : '' ?>>alta</option>
                            </select>
                        </td>
                        <td>
                            <select name="status">
                                <option <?= $row['status'] == 'a fazer' ? 'selected' : '' ?>>a fazer</option>
                                <option <?= $row['status'] == 'fazendo' ? 'selected' : '' ?>>fazendo</option>
                                <option <?= $row['status'] == 'concluído' ? 'selected' : '' ?>>concluído</option>
                            </select>
                        </td>
                        <td><?= $row['data_cadastro'] ?></td>
                        <td>
                            <input type="hidden" name="id_tarefa" value="<?= $row['id_tarefa'] ?>">
                            <button type="submit" name="update">Salvar</button>
                            <a href="?delete=<?= $row['id_tarefa'] ?>" onclick="return confirm('Excluir tarefa?')">Excluir</a>
                        </td>
                    </form>
                </tr>
            <?php endwhile; ?>
        </table>
    </div>

    <?php if ($alert): ?>
        <script>
            Swal.fire({
                icon: '<?= $alert['type'] ?>',
                title: '<?= $alert['message'] ?>',
                showConfirmButton: false,
                timer: 1500
            });
            history.replaceState(null, null, location.pathname);
        </script>
    <?php endif; ?>
</body>

</html>