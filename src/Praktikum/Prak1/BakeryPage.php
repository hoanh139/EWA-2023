<?php
$language = "de";
$title = "Pizza Bäcker";
$linkUbersicht = "http://localhost/Praktikum/Prak1/UbersichtSeite.php";
$linkKunde = "http://localhost/Praktikum/Prak1/KundeSeite.php";
$linkBestellung = "http://localhost/Praktikum/Prak1/BestellungSeite.php";
$linkBackery = "http://localhost/Praktikum/Prak1/BakeryPage.php";
$linkFahrer = "http://localhost/Praktikum/Prak1/FahrerSeite.php";

echo <<<EOT
<!DOCTYPE html>
<html lang=$language>
<head>
    <meta charset="UTF-8">
    <title>$title</title>
</head>
<body>
    <header>
        <a href=$linkUbersicht>Übersicht</a>
        <a href=$linkBestellung>Bestellung</a>
        <a href=$linkKunde>Kunde</a>
        <a href=$linkBackery>Bäcker</a>
        <a href=$linkFahrer>Fahrer</a>
  </header>
    <h1>Bäcker</h1>
    <form action="https://echo.fbi.h-da.de/" id="BackerInfos" method="post" lang=$language accept-charset="UTF-8">
        <table>
            <tr>
                <th></th>
                <th>bestellt</th>
                <th>Ofen</th>
                <th>fertig</th>
            </tr>
EOT;

//test array
$bestellungs = array(
    array(
        "id" => 1,
        "status" => "bestellt",
        "type" => "Margherita",
    ),
    array(
        "id" => 2,
        "status" => "bestellt",
        "type" => "Hawai",
    ),
    array(
        "id" => 3,
        "status" => "fertig",
        "type" => "Salami",
    ),
    array(
        "id" => 4,
        "status" => "bestellt",
        "type" => "Hawai",
    ),
    array(
        "id" => 5,
        "status" => "oven",
        "type" => "Salami",
    )
);


//html for every parameter from $orders
foreach ($bestellungs as $bestellung) {
    $idBestellt="ordered" . "$bestellung[id]";
    $idOfen="ofen" . "$bestellung[id]";
    $idfertig="fertig" . "$bestellung[id]";

    $checkstatusarray = array(0 => "", 1 => "", 2 => "");

    if($bestellung['status'] == "fertig"){
        $checkstatusarray[2] = "checked";
    }
    elseif ($bestellung['status'] == "oven"){
        $checkstatusarray[1] = "checked";
    }
    else{
        $checkstatusarray[0] = "checked";
    }

    echo <<<EOT
        <tr>
        <td>$bestellung[type]</td>
        <td><label> <input type="radio" id="$idBestellt" name = "$bestellung[id]"   value="bestellt" $checkstatusarray[0]></label></td>
        <td><label> <input type="radio" id="$idOfen" name = "$bestellung[id]"   value="oven" $checkstatusarray[1]></label></td>
        <td><label> <input type="radio" id="$idfertig" name = "$bestellung[id]"   value="fertig" $checkstatusarray[2]></label></td>
        </tr>    
EOT;
}
echo <<<EOT
        </table>
        <input type="submit" id="submit" value="Bestätigen">
        </form>
    </section>
</body>
</html>
EOT;