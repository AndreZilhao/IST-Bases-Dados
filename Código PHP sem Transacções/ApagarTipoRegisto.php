<html>
<body>
    <!-- HEADER -->
    <h3>Apagar um Tipo de Registo</h3>

    <!--CRIAÇÃO DO FORM-->
    <form action="ApagarTipoRegisto.php" method="post">
        <p>ID Utilizador:<br><input type="number" name="userid"/></p>
        <p>Nome da Pagina:<br><input type="text" name="nometipo"/></p>
        <p><input type="submit" value="Apagar"/></p>
    </form>

    <!--TRATAMENTO EM PHP DO INPUT-->
    <?php
    //SET DE VARIÁVEIS
    $givenid = $_REQUEST['userid'];
    $nometipo= $_REQUEST['nometipo'];;
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
            

            //VERIFICAR SE O TIPO DE REGISTO EXISTE
            $sql = "SELECT * FROM tipo_registo WHERE nome = '$nometipo' AND userid = $givenid AND ativo = TRUE;";
            $stmt = $db->query($sql);
            $objects = $stmt->fetchAll(PDO::FETCH_OBJ);
            if($objects == NULL)
            {
                die("ERRO: Esse tipo de registo nao existe.");
            }
            
            //CHAMADA DO STORED PROCEDURE
            $db->query("start transaction;");  // <-- INICIO DA TRANSACÇÃO*
            $sql = "CALL elimina_tipo_registo($givenid, '$nometipo');";
            $stmt = $db->query($sql);
            if ($stmt)
            {
                echo "Tipo de Registo Eliminado";
            }
            $db->query("commit;"); // <-- CASO A TRANSACÇÃO CONCLUA, FAZ COMMIT.*
            $db = null;

        }

        catch (PDOException $e)
        {
            $db->query("rollback;");  // <-- CASO A TRANSACÇÃO FALHE, REVERTE AS MUDANÇAS.*
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

