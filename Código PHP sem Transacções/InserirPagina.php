<html>
<body>
    <!-- HEADER -->
    <h3>Inserir nova Pagina</h3>

    <!--CRIAÇÃO DO FORM-->
    <form action="InserirPagina.php" method="post">
        <p>ID Utilizador:<br><input type="number" name="userid"/></p>
        <p>Nome da nova pagina:<br><input type="text" name="nomepagina"/></p>
        <p><input type="submit" value="Inserir"/></p>
    </form>

    <!--TRATAMENTO EM PHP DO INPUT-->
    <?php
    //SET DE VARIÁVEIS
    $userid=$_REQUEST['userid'];
    $nomepagina= $_REQUEST['nomepagina'];
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

            //VERIFICAR SE JA FOI INSERIDO UM TIPO
            $sql = "SELECT * FROM pagina WHERE nome = '$nomepagina' AND userid = '$userid' AND ativa = 1;";
            $stmt = $db->query($sql);
            $objects = $stmt->fetchAll(PDO::FETCH_OBJ);
            if($objects != NULL)
            {
                die("ERRO: Ja existe uma pagina com este nome para este utilizador.");
            }

            //CHAMADA DO STORED PROCEDURE
            $sql = "CALL cria_pagina($userid, '$nomepagina');";
            $stmt = $db->query($sql);
            if ($stmt)
            {
                echo "Pagina Criada";
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

                echo("ERRO: Argumentos invalidos.</p>");
            }
        }
    }
    ?>
    <form action="Menu.html">
    <input type="submit" value="Voltar">
</body>
</html>
