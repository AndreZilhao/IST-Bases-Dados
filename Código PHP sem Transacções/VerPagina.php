<html>
<body>
    <!-- HEADER -->
    <h3>Visualizar uma Pagina</h3>

    <!--CRIAÇÃO DO FORM-->
    <form action="VerPagina.php" method="post">
        <p>ID Utilizador:<br><input type="number" name="userid"/></p>
        <p>Nome da Pagina a Visualizar:<br><input type="text" name="nometipo"/></p>
        <p><input type="submit" value="Listar Campos"/></p>
    </form>

    <!--TRATAMENTO EM PHP DO INPUT-->
    <?php
    //SET DE VARIÁVEIS
    $nomepagina = $_REQUEST['nometipo'];
    $givenid = $_REQUEST['userid'];

    
    if (isset ($nomepagina))
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
            $typecount = "SELECT * FROM pagina WHERE nome ='$nomepagina' AND userid =$givenid;";
            $stmt = $db->query($typecount);
            $objects = $stmt->fetchAll(PDO::FETCH_OBJ);
            foreach ($objects as $obj) {
                $type = $obj->typecnt;
            };

            $sql = "SELECT * FROM reg_pag where userid = $givenid and pageid = $type;";

            $result = $db->query($sql);

            echo("<table border=\"0\" cellspacing=\"5\">\n");
            foreach($result as $row)
            {
                echo("<tr>\n");
                echo("<td>{$row['nome']}</td>\n");
                echo("<td><a href=\"CampoApagado.php?userid=$givenid&nometipo='$nometipo'&nomecampo={$row['nome']}\">Apagar</a></td>\n");
                echo("</tr>\n");
            }
            echo("</table>\n");



        }

        catch (PDOException $e)
        {
            $db->query("rollback;");
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
    
</form>
</body>
</html>

