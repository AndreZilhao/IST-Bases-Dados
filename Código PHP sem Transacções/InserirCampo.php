<html>
<body>
    <!-- HEADER -->
    <h3>Inserir um Campo</h3>

    <!--CRIAÇÃO DO FORM-->
    <form action="InserirCampo.php" method="post">
        <p>ID Utilizador:<br><input type="number" name="userid"/></p>
        <p>Nome do novo campo:<br><input type="text" name="nomecampo"/></p>
        <p>Nome do Tipo a associar:<br><input type="text" name="nometipo"/></p>
        <p><input type="submit" value="Inserir"/></p>
    </form>

    <!--TRATAMENTO EM PHP DO INPUT-->
    <?php
    //SET DE VARIÁVEIS
    $givenid = $_REQUEST['userid'];
    $nomecampo= $_REQUEST['nomecampo'];
    $nometipo= $_REQUEST['nometipo'];
    if (isset ($nomecampo))
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

            //VERIFICAR SE JA FOI INSERIDO UM TIPO. IMPEDIR CRIAÇÃO CASO SE VERIFIQUE.
            $typecount = "SELECT * FROM tipo_registo WHERE nome = '$nometipo' AND userid = $givenid;";
            $stmt = $db->query($typecount);
            $objects = $stmt->fetchAll(PDO::FETCH_OBJ);
            foreach ($objects as $obj) {
                $typecount = $obj->typecnt;
            };
            $sql = "SELECT * FROM campo WHERE nome = '$nomecampo' AND ativo = 1 AND userid = $givenid AND typecnt = $typecount;";
            $stmt = $db->query($sql);
            $objects = $stmt->fetchAll(PDO::FETCH_OBJ);
            if($objects != NULL)
            {
                die("ERRO: Ja existe um campo para este tipo com este nome para este utilizador.");
            }
            

            //CHAMADA DO STORED PROCEDURE
            $sql = "CALL cria_campo($givenid, '$nomecampo', $typecount);";
            $stmt = $db->query($sql);.
            if ($stmt)
            {
                echo "Campo criado";
            }
            $db = null;

        }

        catch (PDOException $e)
        {
            $db->query("rollback;"); // <-- CASO A TRANSACÇÃO FALHE, FAZ ROLLBACK.
            switch ($e->getcode()) 
            {
                case 23000:
                echo ("<p>ERRO: Esse utilizador ou tipo nao existem.</p>");
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

