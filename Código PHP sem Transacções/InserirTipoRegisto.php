<html>
<body>
    <!-- HEADER -->
    <h3>Inserir um Tipo de Registo</h3>

    <!--CRIAÇÃO DO FORM-->
    <form action="InserirTipoRegisto.php" method="post">
        <p>ID Utilizador:<br><input type="number" name="userid"/></p>
        <p>Nome do Novo Tipo:<br><input type="text" name="nometipo"/></p>
        <p><input type="submit" value="Inserir"/></p>
    </form>

    <!--TRATAMENTO EM PHP DO INPUT-->
    <?php
    //SET DE VARIÁVEIS
    $userid=$_REQUEST['userid'];
    $nometipo= $_REQUEST['nometipo'];
    if (isset ($nometipo))
    {
        try
        {
            //CONEXAO
            $host = "db.ist.utl.pt";
            $user ="ist165865";
            $password = "agnu5517";
            $dbname = $user;
            $db = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            //VERIFICAR SE JA FOI INSERIDO UM TIPO
            $sql = "SELECT * FROM tipo_registo WHERE nome = '$nometipo' AND userid = '$userid' AND ativo = 1;";
            $stmt = $db->query($sql);
            $objects = $stmt->fetchAll(PDO::FETCH_OBJ);
            if($objects != NULL)
            {
                die("ERRO: Ja existe um Tipo com este nome para este utilizador.");
            }

            //CHAMADA DO STORED PROCEDURE
            $sql = "CALL cria_tipo_registo($userid, '$nometipo');";
            $stmt = $db->query($sql);

            if ($stmt)
            {
                echo "Tipo criado";
            }
            $db = null;

        }

        catch (PDOException $e)
        {
            switch ($e->getcode()) 
            {
                case 23000:
                echo ("<p>ERRO: Esse utilizador nao existe.</p>");
                break;
                case 42000:
                echo("<p>ERRO: Argumentos invalidos.</p>");
            }
        }
    }
    ?>
    <form action="Menu.html">
    <input type="submit" value="Voltar">
</body>
</html>
