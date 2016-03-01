<html>
<body>
    <!-- HEADER -->
    <h3>Inserir um Registo com Valores</h3>

    <!--CRIAÇÃO DO FORM-->
    

    <!--TRATAMENTO EM PHP DO INPUT-->
    <?php
    //SET DE VARIÁVEIS
    $nomeregisto = $_REQUEST[nomeregisto];
    $givenid = $_REQUEST[userid];
    $type = $_REQUEST[typecount];
    $numfields = $_REQUEST[nrFields];
    print_r($_REQUEST);

    if (isset ($nomeregisto))
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
            
            //CRIAR UM NOVO REGISTO DO TIPO DADO
            $sql = "CALL cria_registo($givenid, '$nomeregisto', $type);";
            $db->query($sql);
            //PARA CADA CAMPO, ATRIBUIR OS VALORES RECEBIDOS AO NOVO REGISTO
            $it = 0;
            foreach($_REQUEST as $key=>$value)
            {
                if($it < $numfields)
                {
                    $sql = "CALL cria_valor($givenid, '$nomeregisto', $type, '$key', '$_REQUEST[$key]');";
                    $db->query($sql);
                    $it++;
                }
            }
            echo " Registo $nomeregisto foi criado com os valores respectivos.";
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