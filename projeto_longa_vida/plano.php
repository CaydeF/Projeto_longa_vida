<?php
// Conexão com o banco de dados
include 'db.php';  // Arquivo de conexão

// Adicionar um novo plano
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_plano'])) {
    $numero = $_POST['numero'];
    $descricao = $_POST['descricao'];
    $valor = $_POST['valor'];

    // Inserir o plano no banco de dados
    $sql = "INSERT INTO plano (numero, descricao, valor) 
            VALUES ('$numero', '$descricao', '$valor')";
    
    if ($conn->query($sql) === TRUE) {
        echo "<p>Plano adicionado com sucesso!</p>";
    } else {
        echo "<p>Erro ao adicionar o plano: " . $conn->error . "</p>";
    }
}

// Editar plano
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit_plano'])) {
    $id_plano = $_POST['id_plano'];
    $numero = $_POST['numero'];
    $descricao = $_POST['descricao'];
    $valor = $_POST['valor'];

    // Atualizar o plano no banco de dados
    $sql_update = "UPDATE plano SET numero='$numero', descricao='$descricao', valor='$valor' 
                   WHERE id_plano = $id_plano";
    
    if ($conn->query($sql_update) === TRUE) {
        echo "<p>Plano atualizado com sucesso!</p>";
    } else {
        echo "<p>Erro ao atualizar o plano: " . $conn->error . "</p>";
    }
}

// Excluir plano
if (isset($_GET['delete_id'])) {
    $id_plano = $_GET['delete_id'];
    $sql_delete = "DELETE FROM plano WHERE id_plano = $id_plano";
    
    if ($conn->query($sql_delete) === TRUE) {
        echo "<p>Plano excluído com sucesso!</p>";
    } else {
        echo "<p>Erro ao excluir o plano: " . $conn->error . "</p>";
    }
}

// Listar os planos cadastrados
$sql = "SELECT * FROM plano";
$result = $conn->query($sql);

// Se o ID de edição estiver presente, busca o plano para edição
$edit_plano = null;
if (isset($_GET['edit_id'])) {
    $edit_id = $_GET['edit_id'];
    $sql_edit = "SELECT * FROM plano WHERE id_plano = $edit_id";
    $result_edit = $conn->query($sql_edit);
    if ($result_edit->num_rows > 0) {
        $edit_plano = $result_edit->fetch_assoc();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciamento de Planos</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
        <h1>Gerenciamento de Planos</h1>
    </header>

    <section>
        <!-- Formulário para adicionar ou editar planos -->
        <h2><?php echo $edit_plano ? "Editar Plano" : "Adicionar Novo Plano"; ?></h2>
        <form action="plano.php" method="POST">
            <?php if ($edit_plano): ?>
                <input type="hidden" name="id_plano" value="<?php echo $edit_plano['id_plano']; ?>">
            <?php endif; ?>
            <label for="numero">Número do Plano:</label>
            <input type="text" name="numero" id="numero" value="<?php echo $edit_plano ? $edit_plano['numero'] : ''; ?>" required><br>

            <label for="descricao">Descrição do Plano:</label>
            <textarea name="descricao" id="descricao" required><?php echo $edit_plano ? $edit_plano['descricao'] : ''; ?></textarea><br>

            <label for="valor">Valor do Plano:</label>
            <input type="number" name="valor" id="valor" value="<?php echo $edit_plano ? $edit_plano['valor'] : ''; ?>" step="0.01" required><br>

            <button type="submit" name="<?php echo $edit_plano ? 'edit_plano' : 'add_plano'; ?>">
                <?php echo $edit_plano ? 'Atualizar Plano' : 'Adicionar Plano'; ?>
            </button>
        </form>

        <!-- Botão de voltar para a página inicial -->
        <p><a href="http://localhost/projeto_longa_vida">Voltar para a Página Inicial</a></p>
    </section>

    <section>
        <!-- Tabela com os planos cadastrados -->
        <h2>Planos Cadastrados</h2>
        <?php if ($result->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Número do Plano</th>
                        <th>Descrição</th>
                        <th>Valor</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['id_plano']; ?></td>
                            <td><?php echo $row['numero']; ?></td>
                            <td><?php echo $row['descricao']; ?></td>
                            <td>R$ <?php echo number_format($row['valor'], 2, ',', '.'); ?></td>
                            <td>
                                <!-- Botões de exclusão e edição -->
                                <a href="plano.php?edit_id=<?php echo $row['id_plano']; ?>">Editar</a> |
                                <a href="plano.php?delete_id=<?php echo $row['id_plano']; ?>" onclick="return confirm('Tem certeza que deseja excluir este plano?')">Excluir</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Nenhum plano cadastrado.</p>
        <?php endif; ?>
    </section>

    <footer>
        <p>&copy; </p>
    </footer>
</body>
</html>

<?php
// Fechar a conexão com o banco de dados
$conn->close();
?>
