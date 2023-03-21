<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php
        function test_input($datatest)
        {
            $datatest = trim($datatest);
            $datatest = striplashes($datatest);
            $datatest = htmlspecialchars($datatest);
            return $datatest;
        }

        #0a komt de gebruiker van het juiste scherm
        if (! isset($_POST["add"]))
        {
            echo "<h2>U bent niet op de juiste manier hier gekomen</h2>";
            header('refresh:5; url=frm-insertBrewer.php');
            exit();
        }
        #1 verbinding met de database
        require "connectbieren.php";

        #1A vraag de gegevens voor toevoegen op
        $brewid = test_input($_POST["brewid"]);
        $brewname = test_input($_POST["brewname"]);
        $brewcountry - test_input($_POST["brewcountry"]);

        #1B controleren van ingevulde gegevens
        #1B-1 komt de brouwer-id voor in de database

        try
        {
            $chkBrewNr = $db->prepare("SELECT brouwcode FROM brouwer
            WHERE brouwcode = :brewid");
            $chkBrewNr->bindValue("Brewid",$brewid);
            $chkBrewNr->execute();
        }
        catch(PDOException $e)
        {
            $sMsg = '<p>
            Regelnummer: '.$e->getLine().'<br />
            Bestand: '.$e->getFile() '<br />
            Foutmelding: '.$e->getMessage().'
            </p>';

            trigger_error($sMsg);

            die("Fout bij verbinden met database: ".$e->getMessage());
        }

        if ($chkBrewNr->RowCount()>0)
        {
            $maxBrewNr = $db->prepare("SELECT MAX(brouwcode) AS 'Maxnr' FROM brouwer");
            $maxBrewNr->execute();

            $result=$maxBrewNr->fetch(PDO::FETCH_ASSOC);
            $maxNr = $result["Maxnr"] + 1;

            echo "<h2>Het opgegeven brouwernummer komt al voor!</h2>";
            echo "<p>Probeer eens met $maxNr of hoger</p>";
            header('refresh:7; url=frm-insertBrewer.php');
            exit();
        }

        #1B-2 komt de brouwer-naam voor in de database
        try
        {
            $chkBrewNm = $db->prepare("SELECT naam FROM brouwer WHERE brouwer.naam = :brewname");
            $chkBrewNm->bindValue("brewname", $brewname);
            $chkBrewNm->execute();

            if ($chkBrewNm->RowCount()>0)
            {
                echo "<h2>De opgegeven brouwernaam komt al voor!</h2>";
                header('refresh:5; url=frm-insertBrewer.php');
            }
        }
        catch(PDOException $e)
        {
            die("Fout bij het verbinden met de database: ".$e->getMessage());
        }

        if (strlen($brewcountry)>4)
        {
            echo "<h2>Het opgegeven land heeft meer dan 4 tekens!</h2>";
            header('refresh:5; url=frm-insertBrewer.php');
            exit();
        }
        #2 De gegevens tonen in form voor bevestiging
    ?> <!--stoppen met PHP om form op te bouwen-->

    <h2>Controlee</h2>
</body>
</html>