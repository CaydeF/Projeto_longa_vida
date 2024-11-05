<?php
// Conexão com o banco de dados
include 'db.php';  // Arquivo de conexão

// Adicionar um novo associado
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_associado'])) {
    $nome = $_POST['nome'];
    $endereco = $_POST['endereco'];
    $cidade = $_POST['cidade'];
    $estado = $_POST['estado'];
    $cep = $_POST['cep'];
    $email = $_POST['email'];
    $plano_id = $_POST['plano_id'];

    // Inserir o associado no banco de dados
    $sql = "INSERT INTO associado (nome, endereco, cidade, estado, cep, email, plano_id) 
            VALUES ('$nome', '$endereco', '$cidade', '$estado', '$cep', '$email', '$plano_id')";
    
    if ($conn->query($sql) === TRUE) {
        echo "<p>Associado adicionado com sucesso!</p>";
    } else {
        echo "<p>Erro ao adicionar o associado: " . $conn->error . "</p>";
    }
}

// Editar um associado
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit_associado'])) {
    $id_associado = $_POST['id_associado'];
    $nome = $_POST['nome'];
    $endereco = $_POST['endereco'];
    $cidade = $_POST['cidade'];
    $estado = $_POST['estado'];
    $cep = $_POST['cep'];
    $email = $_POST['email'];
    $plano_id = $_POST['plano_id'];

    // Atualizar os dados do associado no banco de dados
    $sql_update = "UPDATE associado 
                   SET nome = '$nome', endereco = '$endereco', cidade = '$cidade', estado = '$estado', 
                       cep = '$cep', email = '$email', plano_id = '$plano_id'
                   WHERE id_associado = $id_associado";

    if ($conn->query($sql_update) === TRUE) {
        echo "<p>Associado atualizado com sucesso!</p>";
    } else {
        echo "<p>Erro ao atualizar o associado: " . $conn->error . "</p>";
    }
}

// Excluir um associado
if (isset($_GET['delete_id'])) {
    $id_associado = $_GET['delete_id'];
    
    // Deletar o associado
    $sql_delete = "DELETE FROM associado WHERE id_associado = $id_associado";
    
    if ($conn->query($sql_delete) === TRUE) {
        echo "<p>Associado excluído com sucesso!</p>";
    } else {
        echo "<p>Erro ao excluir o associado: " . $conn->error . "</p>";
    }
}

// Listar os planos para o campo de seleção
$sql_planos = "SELECT id_plano, numero, descricao FROM plano";
$result_planos = $conn->query($sql_planos);

// Listar associados
$sql_associados = "SELECT * FROM associado";
$result_associados = $conn->query($sql_associados);

// Se o ID de edição estiver presente, busca os dados do associado
$edit_associado = null;
if (isset($_GET['edit_id'])) {
    $edit_id = $_GET['edit_id'];
    $sql_edit = "SELECT * FROM associado WHERE id_associado = $edit_id";
    $result_edit = $conn->query($sql_edit);
    if ($result_edit->num_rows > 0) {
        $edit_associado = $result_edit->fetch_assoc();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciamento de Associados</title>
    <link rel="stylesheet" href="css/style.css">
    <script>
        // Função de confirmação para exclusão
        function confirmarExclusao() {
            return confirm('Tem certeza que deseja excluir este associado?');
        }
    </script>
</head>
<body>
    <header>
        <h1>Gerenciamento de Associados</h1>
    </header>

    <section>
        <!-- Formulário para adicionar ou editar associados -->
        <h2><?php echo $edit_associado ? "Editar Associado" : "Adicionar Novo Associado"; ?></h2>
        <form action="associado.php" method="POST">
            <?php if ($edit_associado): ?>
                <input type="hidden" name="id_associado" value="<?php echo $edit_associado['id_associado']; ?>">
            <?php endif; ?>
            <label for="nome">Nome:</label>
            <input type="text" name="nome" id="nome" value="<?php echo $edit_associado ? $edit_associado['nome'] : ''; ?>" required><br>

            <label for="endereco">Endereço:</label>
            <input type="text" name="endereco" id="endereco" value="<?php echo $edit_associado ? $edit_associado['endereco'] : ''; ?>" required><br>

            <label for="cidade">Cidade:</label>
            <input type="text" name="cidade" id="cidade" value="<?php echo $edit_associado ? $edit_associado['cidade'] : ''; ?>" required><br>

            <label for="estado">Estado:</label>
            <input type="text" name="estado" id="estado" value="<?php echo $edit_associado ? $edit_associado['estado'] : ''; ?>" required><br>

            <label for="cep">CEP:</label>
            <input type="text" name="cep" id="cep" value="<?php echo $edit_associado ? $edit_associado['cep'] : ''; ?>" required><br>

            <label for="email">Email:</label>
            <input type="email" name="email" id="email" value="<?php echo $edit_associado ? $edit_associado['email'] : ''; ?>" required><br>

            <label for="plano_id">Plano:</label>
            <select name="plano_id" id="plano_id" required>
                <?php while ($row_plano = $result_planos->fetch_assoc()): ?>
                    <option value="<?php echo $row_plano['id_plano']; ?>" <?php echo ($edit_associado && $edit_associado['plano_id'] == $row_plano['id_plano']) ? 'selected' : ''; ?>>
                        <?php echo $row_plano['numero'] . " - " . $row_plano['descricao']; ?>
                    </option>
                <?php endwhile; ?>
            </select><br>

            <button type="submit" name="<?php echo $edit_associado ? 'edit_associado' : 'add_associado'; ?>">
                <?php echo $edit_associado ? 'Atualizar Associado' : 'Adicionar Associado'; ?>
            </button>
        </form>

        <!-- Botão de voltar para a página inicial -->
        <p><a href="http://localhost/projeto_longa_vida">Voltar para a Página Inicial</a></p>
    </section>

    <section>
        <!-- Tabela com os associados cadastrados -->
        <h2>Associados Cadastrados</h2>
        <?php if ($result_associados->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Email</th>
                        <th>Plano</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row_associado = $result_associados->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row_associado['id_associado']; ?></td>
                            <td><?php echo $row_associado['nome']; ?></td>
                            <td><?php echo $row_associado['email']; ?></td>
                            <td>
                                <?php
                                // Exibir o nome do plano
                                $plano_id = $row_associado['plano_id'];
                                $sql_plano = "SELECT numero, descricao FROM plano WHERE id_plano = $plano_id";
                                $result_plano = $conn->query($sql_plano);
                                if ($result_plano->num_rows > 0) {
                                    $plano = $result_plano->fetch_assoc();
                                    echo $plano['numero'] . " - " . $plano['descricao'];
                                }
                                ?>
                            </td>
                            <td>
                                <!-- Link para editar o associado -->
                                <a href="associado.php?edit_id=<?php echo $row_associado['id_associado']; ?>">Editar</a> |
                                <!-- Link para excluir o associado -->
                                <a href="associado.php?delete_id=<?php echo $row_associado['id_associado']; ?>" onclick="return confirmarExclusao();">Excluir</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Nenhum associado cadastrado.</p>
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

