<html>
<body>
    <!-- HEADER -->
    <h3>Apagar uma Pagina</h3>

    <!--CRIAÇÃO DO FORM-->
    <form action="ApagarPagina.php" method="post">
        <p>ID Utilizador:<br><input type="number" name="userid"/></p>
        <p>Nome da Pagina:<br><input type="text" name="nomepagina"/></p>
        <p><input type="submit" value="Apagar"/></p>
    </form>

    <!--TRATAMENTO EM PHP DO INPUT-->
    <?php
    //SET DE VARIÁVEIS
    $givenid = $_REQUEST['userid'];
    $nomepagina= $_REQUEST['nomepagina'];;
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

            //VERIFICAR SE A PAGINA EXISTE
            $sql = "SELECT * FROM pagina WHERE nome = '$nomepagina' AND userid = $givenid AND ativa= TRUE;";
            $stmt = $db->query($sql);
            $objects = $stmt->fetchAll(PDO::FETCH_OBJ);
            if($objects == NULL)
            {
                die("ERRO: Essa pagina nao existe");
            }
            
            //CHAMADA DO STORED PROCEDURE
            $sql = "CALL elimina_pagina($givenid, '$nomepagina');";
            $stmt = $db->query($sql);
            if ($stmt)
            {
                echo "Pagina Eliminada";
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

