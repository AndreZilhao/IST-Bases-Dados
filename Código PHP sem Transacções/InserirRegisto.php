<html>
<body>
    <!-- HEADER -->
    <h3>Inserir um Registo com Valores</h3>

    <!--CRIAÇÃO DO FORM-->
    <form action="InserirRegisto.php" method="post">
        <p>ID Utilizador:<br><input type="number" name="userid"/></p>
        <p>Nome do Novo Registo:<br><input type="text" name="nomeregisto"/></p>
        <p>Tipo do Registo a qual quer adicionar um Registo:<br><input type="text" name="nometipo"/></p>
        <p><input type="submit" value="Ver Campos"/></p>
    </form>

    <!--TRATAMENTO EM PHP DO INPUT-->
    <?php
    //SET DE VARIÁVEIS
    $givenid = $_REQUEST['userid'];
    $nometipo= $_REQUEST['nometipo'];
    $nomeregisto= $_REQUEST['nomeregisto'];
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

            //DEVOLVER LISTA DE CAMPOS DE UM UTILIZADOR PARA UM TIPO
            $typecount = "SELECT * FROM tipo_registo WHERE nome = '$nometipo' AND userid = $givenid;";
            $stmt = $db->query($typecount);
            $objects = $stmt->fetchAll(PDO::FETCH_OBJ);
            foreach ($objects as $obj) {
                $typecount = $obj->typecnt;
            };

            $sql = "SELECT * FROM campo WHERE typecnt = $typecount AND ativo = TRUE AND userid = $givenid ORDER BY idseq;";

            $result = $db->query($sql);
            $incvar = 0;
            echo("<h4>Tipo: $nometipo</h4>");
            echo("<h4>Nome do Registo: $nomeregisto</h4>");
            echo("<form action=InserirRegistoAction.php>");
            echo("<table border=\"0\" cellspacing=\"5\">\n");
            echo("<h5>Listagem dos Campos</h5>");
            foreach($result as $row)
            {
                echo("<tr>\n");
                echo("<td>{$row['nome']}:</td>\n");
                echo("<td><input type=text name={$row['nome']}></td>\n");
                echo("</tr>\n");
                $incvar++;
            }
            echo("</table>\n");
            echo("<input type=hidden name=userid value=$givenid>");
            echo("<input type=hidden name=typecount value=$typecount>");
            echo("<input type=hidden name=nomeregisto value='$nomeregisto'>");
            echo("<input type=hidden name=nrFields value=$incvar>");
            echo("<input type=submit value='Criar Novo Registo'/>");
            echo("</form>");
            echo("<br>");

        }

        catch (PDOException $e)
        {
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

