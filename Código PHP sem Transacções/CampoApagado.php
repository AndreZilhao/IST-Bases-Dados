<html>
<body>
    <!-- HEADER -->
    <h3>Apagar um Campo de um Registo</h3>

    <!--CRIAÇÃO DO FORM-->
    

    <!--TRATAMENTO EM PHP DO INPUT-->
    <?php
    //SET DE VARIÁVEIS
    $nomecampo = $_REQUEST['nomecampo'];
    $givenid = $_REQUEST[userid];
    $nometipo = $_REQUEST['nometipo'];
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
            $type = "SELECT * FROM tipo_registo WHERE nome = $nometipo AND userid = $givenid;";
            $stmt = $db->query($type);
            $objects = $stmt->fetchAll(PDO::FETCH_OBJ);
            foreach ($objects as $obj) {
                $type = $obj->typecnt;
            };

            //CHAMADA DO STORED PROCEDURE
            $sql = "CALL elimina_campo($givenid, $type, '$nomecampo');";
            $stmt = $db->query($sql);
            if ($stmt)
            {
                echo " Campo  $nomecampo foi eliminado";
            }

            //DEVOLVER NOVA LISTA DE CAMPOS

            $sql = "SELECT * FROM campo WHERE typecnt = $type AND ativo = TRUE AND userid = $givenid ORDER BY idseq;";
            $result = $db->query($sql);
            echo("<table border=\"0\" cellspacing=\"5\">\n");
            foreach($result as $row)
            {
                echo("<tr>\n");
                echo("<td>{$row['nome']}</td>\n");
                echo("<td><a href=\"CampoApagado.php?userid=$givenid&nometipo=$nometipo&nomecampo={$row[nome]}\">Apagar</a></td>\n");
                echo("</tr>\n");
            }
            echo("</table>\n");
            $db = null;
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