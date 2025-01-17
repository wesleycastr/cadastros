<?php
include("config.php"); // Inclui a conexão com o banco de dados


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Verifica se todos os campos estão preenchidos
    if (!empty($_POST['nome']) && !empty($_POST['senha']) && !empty($_POST['confirma_senha']) &&
        !empty($_POST['email']) && !empty($_POST['telefone']) && !empty($_POST['data_nascimento']) &&
        !empty($_POST['matricula']) && !empty($_POST['cargo'])) {

        // Captura os valores do formulário
        $nome = $_POST['nome'];
        $senha = $_POST['senha'];
        $cargo = $_POST['cargo'];
        $matricula = $_POST['matricula'];
        $confirma_senha = $_POST['confirma_senha'];
        $email = $_POST['email'];
        $telefone = $_POST['telefone'];
        $data_nascimento = $_POST['data_nascimento'];

        if ($senha === $confirma_senha) {
            $senha_hash = password_hash($senha, PASSWORD_DEFAULT); // Hash da senha

            try {
                // Prepara a query SQL para inserir os dados
                $query = $conn->prepare("INSERT INTO funcionarios (nome, senha, cargo, matricula, email, telefone, data_nascimento) 
                                         VALUES (:nome, :senha, :cargo, :matricula, :email, :telefone, :data_nascimento)");

                // Vincula os parâmetros da query
                $query->bindParam(':nome', $nome);
                $query->bindParam(':senha', $senha_hash);
                $query->bindParam(':email', $email);
                $query->bindParam(':telefone', $telefone);
                $query->bindParam(':data_nascimento', $data_nascimento);
                $query->bindParam(':matricula', $matricula);
                $query->bindParam(':cargo', $cargo);

                // Executa a query e verifica se foi bem-sucedida
                if ($query->execute()) {
                    $mensagem = "Cadastro realizado com sucesso!";
                    $tipo_alerta = "success";
                } else {
                    $mensagem = "Erro ao cadastrar o funcionário.";
                    $tipo_alerta = "error";
                }
            } catch (PDOException $e) {
                $mensagem = "Erro ao cadastrar o funcionário: " . $e->getMessage();
                $tipo_alerta = "error";
            }
        } else {
            $mensagem = "As senhas não coincidem. Tente novamente.";
            $tipo_alerta = "error";
        }
    } else {
        $mensagem = "Por favor, preencha todos os campos.";
        $tipo_alerta = "error";
    }
}

// Consulta para buscar os registros
$sql = "SELECT id, nome, email, cargo, matricula, telefone, data_nascimento FROM funcionarios";
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Funcionário</title>
    <style>
        body {
            font-family: "Open Sans", sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 1000px;
            margin: 50px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 0px;
            box-shadow: 0 0 25px rgba(0, 0, 0, 0.1);
            margin-top: 1300px;
            margin-left: -750px;
            margin-right: 200px;
            margin-bottom: 130px;
        }

        .container h2 {
            background-color: #5aa2b0;
            color: white;
            text-align: center;
            padding: 15px 0;
            border-radius: 0px 0px 0 0;
            box-shadow: 50 10 15px rgba(0, 0, 0, 0.1);
            margin-top: -20px;
            width: calc(100% + 40px); /* Ajustado para largura total */
            margin-left: -20px;
            height: 40px;
            margin-bottom: 15px;

            font-family: 'Arial', sans-serif; /* Fonte personalizada */
            font-size: 35px; /* Tamanho da fonte */
            line-height: 40px; /* Alinhamento vertical do texto */
            font-weight: bold; /* Estilo em negrito */
            font-weight: 300; /* Letra mais fina */
            letter-spacing: 1px; /* Espaçamento entre letras */
        }


        form {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            padding: 20px;
        }

        label {
            font-weight: bold;
        }

        input[type="password"] {
            width: 70%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #5aa2b0;
            border-radius: 12px;
            box-sizing: border-box; /* Para garantir que o padding não aumente a largura total */
            min-height: 20px; /* Altura mínima para todos os campos */
            margin-left: 100px;
            font-size: 10px; 
        }

        input#confirma_senha {
            width: 70%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #5aa2b0;
            border-radius: 12px;
            box-sizing: border-box; /* Para garantir que o padding não aumente a largura total */
            min-height: 20px; /* Altura mínima para todos os campos */
            margin-left: 5px;
            font-size: 10px; 
        }

        input[type="date"] {
            width: 70%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #5aa2b0;
            border-radius: 12px;
            box-sizing: border-box; /* Para garantir que o padding não aumente a largura total */
            min-height: 20px; /* Altura mínima para todos os campos */
            margin-left: 5px;
            font-size: 10px; 
        }

        input[type="tel"] {
            width: 70%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #5aa2b0;
            border-radius: 12px;
            box-sizing: border-box; /* Para garantir que o padding não aumente a largura total */
            min-height: 20px; /* Altura mínima para todos os campos */
            margin-left: 100px;
            font-size: 10px; 
        }

        label[for="cargo"] {
            font-weight: bold;
            font-size: 16px;
            color: #000000; /* Exemplo de cor personalizada */
            margin-left: 110px; /* Ajuste para alinhamento */
            font-size: 14px;
       }

        #cargo {
            border: 1px solid #5aa2b0; /* Borda do campo "Cargo" */
            width: 70%;
            padding: 10px;
            margin-top: 5px;
            border-radius: 12px;
            box-sizing: border-box; /* Para garantir que o padding não aumente a largura total */
            min-height: 20px; /* Altura mínima para todos os campos */
            margin-left: 100px;
            font-size: 10px; 
        }

        label[for="matricula"] {
            font-weight: bold;
            font-size: 16px;
            color: #000000; /* Exemplo de cor personalizada */
            margin-left: 15px; /* Ajuste para alinhamento */
            font-size: 14px; 
         }

        #matricula {
            width: 70%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #5aa2b0;
            border-radius: 12px;
            box-sizing: border-box; /* Para garantir que o padding não aumente a largura total */
            min-height: 20px; /* Altura mínima para todos os campos */
            margin-left: 5px;
            font-size: 10px; 
        }

          
        input[type="email"] {
            width: 150%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #5aa2b0;
            border-radius: 12px;
            box-sizing: border-box; /* Para garantir que o padding não aumente a largura total */
            min-height: 20px; /* Altura mínima para todos os campos */
            margin-left: 100px;
            font-size: 10px; 
        }
        
        input[type="text"] {
            width: 150%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #5aa2b0;
            border-radius: 12px;
            box-sizing: border-box; /* Para garantir que o padding não aumente a largura total */
            min-height: 20px; /* Altura mínima para todos os campos */
            margin-left: 100px;
            font-size: 10px; 
        }

        label[for="nome"] {
            font-weight: bold;
            font-size: 16px;
            color: #000000; /* Exemplo de cor personalizada */
            margin-left: 110px; /* Ajuste para alinhamento */
            font-size: 14px; 
        }

        label[for="email"] {
            font-weight: bold;
            font-size: 16px;
            color: #000000; /* Exemplo de cor personalizada */
            margin-left: 110px; /* Ajuste para alinhamento */
            font-size: 14px; 
        }

        label[for="telefone"] {
            font-weight: bold;
            font-size: 16px;
            color: #000000; /* Exemplo de cor personalizada */
            margin-left: 110px; /* Ajuste para alinhamento */
            font-size: 14px; 
        }

        label[for="senha"] {
            font-weight: bold;
            font-size: 16px;
            color: #000000; /* Exemplo de cor personalizada */
            margin-left: 110px; /* Ajuste para alinhamento */
            font-size: 14px; 
        }

        label[for="confirma_senha"] {
            font-weight: bold;
            font-size: 16px;
            color: #000000; /* Exemplo de cor personalizada */
            margin-left: 15px; /* Ajuste para alinhamento */
            font-size: 14px; 
        }

        label[for="data_nascimento"] {
            font-weight: bold;
            font-size: 16px;
            color: #000000; /* Exemplo de cor personalizada */
            margin-left: 15px; /* Ajuste para alinhamento */
            font-size: 14px; 
        }

        .full-width {
            grid-column: span 2;
        }

        button {
            grid-column: span 2;
            padding: 15px;
            background-color: #174650;
            color: white;
            border: none;
            border-radius: 30px;
            box-shadow: 0 10px 10px rgba(0, 0, 0, 0.1);
            font-size: 18px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            width: 100%; /* Largura total para ocupar espaço */
            max-width: 200px; /* Tamanho máximo do botão */
            margin: 20px auto; /* Centralizando o botão */
            height: 45px;
            margin-top: 30px;
            margin-bottom: -30px;
        }

        button:hover {
            background-color: #5aa2b0;
        }

        .footer {
            text-align: center;
            margin-top: 30px;
            font-size: 12px;
            color: #555;
        }

        @media (max-width: 600px) {
            form {
                grid-template-columns: 1fr; /* Coluna única em telas menores */
            }

            button {
                width: 100%; /* Botão ocupa a largura total */
            }
        }

        .error {
            color: red; /* Cor do texto de erro */
            margin-top: 5px; /* Espaçamento acima */
            font-size: 14px; /* Tamanho da fonte de erro */
        }

        .header-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 20px;
            background-color: #ffffff;
            position: relative;
            max-height: 45px;
            height: auto;
            margin-bottom: 50px;
            margin-top: -8px;
            width: 1500px;
            margin-left: -5px;
        }

        .logo img {
            max-width: 105px;
            height: auto;
            margin-top: 7px;
            margin-right: 20px;
        }

        /* Menu visível por padrão */
        .menu {
            margin-top: -7px;
            display: flex;
            flex-direction: row;
            gap: 15px;
            position: static;
            background-color: transparent;
            margin-left: auto; /* Para alinhar o menu à direita */
            font-family: "Open Sans", sans-serif;
            text-decoration: none; /* Remove o sublinhado dos links */;

        }

        .menu a {
            text-decoration: none;
            padding: 0 15px;
            display: block;
            text-decoration: none; /* Remove sublinhado padrão */
            color: rgb(129, 121, 121); /* Cor do texto */
            text-decoration: none; /* Remove o sublinhado dos links */; 
            font-size: 17px; /* Tamanho da fonte */
            font-weight: normal;
            transition: 0.3s ease; /* Efeito suave ao passar o mouse */
            position: relative;
            margin: 0 15px;
            font-family: 'Open-sans', sans-serif;
        }

        /* Container para o menu e botão de login */
        .menu-login-container {
            display: flex;
            align-items: center;
            margin-left: auto; /* Para alinhar à direita */
        }

        /* Estilo responsivo */
        @media (max-width: 768px) {
            .nav menu {
                display: none; /* Oculta o menu em telas menores */
                flex-direction: column;
                position: absolute;
                top: 60px;
                left: 0;
                width: 100%;
                background-color: #fff;
                padding: 10px;
                box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
                margin-left: 0; /* Remove o margin-left em telas menores */
            }

            .menu-icon {
                display: block; /* Exibe o ícone do menu em telas menores */
                color: #2f2c73;
            }

            .menu a {
                padding: 10px;
            }
        }
        h1 {
            color: #007bff;
            margin-left: -600px;

        }
        table {
            width: 300px;
            border-collapse: collapse;
            margin-bottom: 20px;
            border: 2px solid #007bff;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            margin-right: -100px;
            margin-left: -600px ;
        }
        th, td {
            padding: 20px;
            text-align: left;
            border: 1px solid #007bff;
            transition: background-color 0.3s;
        }
        th {
            background-color: #007bff;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        tr:hover {
            background-color: #e9ecef;
        }
        /* Estilo para os modais */
        #confirmModal, #successModal, #errorModal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.7);
        }
        .modal-content {
            position: absolute;
            top: 80%;
            margin-left: 1000px;
            transform: translate(-50%, -50%);
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
            max-width: 400px;
            width: 90%;
        }
        h3 {
            margin-top: 0;
        }
        /* Estilos para os botões */
        .modal-button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            margin: 5px;
            transition: background-color 0.3s, transform 0.2s;
            font-size: 16px;
        }
        .modal-button:hover {
            background-color: #0056b3;
            transform: scale(1.05);
        }
        .modal-button.cancel {
            background-color: #6c757d;
        }
        .modal-button.cancel:hover {
            background-color: #5a6268;
        }
        /* Estilo para os textos dentro do modal */
        .modal-text {
            font-size: 16px;
            margin: 10px 0;
        }
        /* Estilo para os links de ações */
        .action-link {
            color: #007bff;
            text-decoration: none;
            font-weight: bold;
            margin: 0 5px;
        }
        .action-link:hover {
            text-decoration: underline;
        }
        /* Estilo dos ícones */
        .fa {
            font-size: 18px;
            margin: 0 5px;
        }
        /* Estilo do formulário de edição */
        .edit-form {
            display: none; /* Inicialmente oculto */
            margin: 10px 0;
            padding: 10px;
            border: 1px solid #007bff;
            border-radius: 5px;
            background-color: #f8f9fa;
        }
        /* Estilo do campo de senha */
        .password-container {
            position: relative;
        }
        .toggle-password {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #007bff;
        }
    </style>
</head>
<body>
<header class="header-container">
        <div class="logo">
            <img src="./img/Slide S.P.M. (13).png" alt="Logotipo">
        </div>

        <div class="menu-login-container">
            <!-- Menu de navegação -->
            <nav class="menu" id="menu">
                <a href="./te.php">Início</a>
                <a href="#">Lista</a>
                <a href="#">Cadastros</a>
                <a href="#">Relatórios</a>
                <a href="#">Configuração</a>
            </nav>
<div>
    <div class="container">
        <h2 class="titulo-principal">Cadastrar funcionário</h2>
        <form id="cadastroForm" action="" method="post">
            <div>
                <label for="nome">Nome Completo</label>
                <input type="text" id="nome" name="nome" placeholder="Nome Completo"  value="<?php echo isset($_POST['nome']) ? $_POST['nome'] : ''; ?>" required>
                <div id="nomeError" class="error"></div> <!-- Mensagem de erro -->
            </div>
            <br>
            <div>
                <label for="email">E-mail</label>
                <input type="email" id="email" name="email" placeholder="E-mail" value="<?php echo isset($_POST['email']) ? $_POST['email'] : ''; ?>" required>
                <div id="emailError" class="error"></div> <!-- Mensagem de erro -->
            </div>
            <br>
            <div>
                <label for="telefone">Telefone</label>
                <input type="tel" id="telefone" name="telefone" placeholder="( ) 00000-0000" value="<?php echo isset($_POST['telefone']) ? $_POST['telefone'] : ''; ?>" required>
                <div id="telefoneError" class="error"></div> <!-- Mensagem de erro -->
            </div>
            <div>
                <label for="data_nascimento">Data de Nascimento</label>
                <input type="date" id="data_nascimento" name="data_nascimento" value="<?php echo isset($_POST['data_nascimento']) ? $_POST['data_nascimento'] : ''; ?>" required>
                <div id="dataError" class="error"></div> <!-- Mensagem de erro -->
            </div>
            <div>
                <label for="cargo">Cargo</label>
                <input type="text" id="cargo" name="cargo" placeholder="Cargo" value="<?php echo isset($_POST['cargo']) ? $_POST['cargo'] : ''; ?>" required>
                <div id="cargoError" class="error"></div> <!-- Mensagem de erro -->
            </div>
            <div>
                <label for="matricula">Matrícula</label>
                <br>
                <input type="text" id="matricula" name="matricula" placeholder="Matrícula" value="<?php echo isset($_POST['matricula']) ? $_POST['matricula'] : ''; ?>" required>
                <div id="matriculaError" class="error"></div> <!-- Mensagem de erro -->
            </div>
            <div>
                <label for="senha">Senha</label>
                <input type="password" id="senha" name="senha" placeholder="Digite sua senha" required autocomplete="new-password">
                <div id="senhaError" class="error"></div> <!-- Mensagem de erro -->
            </div>
            <div>
                <label for="confirma_senha">Confirme sua senha</label>
                <input type="password" id="confirma_senha" name="confirma_senha" placeholder="Confirme sua senha" required>
                <div id="confirmaSenhaError" class="error"></div> <!-- Mensagem de erro -->
            </div>
            <button type="submit">Cadastrar</button>
        </form>
    </div>

   
    <!-- Passa as variáveis PHP para o JavaScript -->
    <script>
        var mensagem = "<?php echo $mensagem; ?>";
        var tipoAlerta = "<?php echo $tipo_alerta; ?>";
    </script>

<div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Mostra a mensagem de sucesso ou erro quando o formulário for submetido
        document.addEventListener("DOMContentLoaded", function() {
            if (mensagem && tipoAlerta) {
                Swal.fire({
                    icon: tipoAlerta,
                    title: mensagem,
                    showConfirmButton: true
                });
                <?php if ($tipo_alerta === 'success'): ?>
            // Limpa o formulário após o sucesso
            document.getElementById('cadastroForm').reset();
            <?php endif; ?>
                }
        });
    </script>
    <div>

    <h1>Lista de Funcionários </h1>
    <div>
    <table>
        <thead>
            <tr>
                <th>Nome</th>
                <th>Email</th>
                <th>Cargo</th>
                <th>Matrícula</th>
                <th>Telefone</th>
                <th>Data Nascimento</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($result as $row): ?>
                <tr>
                    <td><?= htmlspecialchars($row['nome']) ?></td>
                    <td><?= htmlspecialchars($row['email']) ?></td>
                    <td><?= htmlspecialchars($row['cargo']) ?></td>
                    <td><?= htmlspecialchars($row['matricula']) ?></td>
                    <td><?= htmlspecialchars($row['telefone']) ?></td>
                    <td><?= htmlspecialchars($row['data_nascimento']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>