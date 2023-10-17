<?php
$language = "de";
$title = "Pizza Kunde";
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
<style>
    .myDiv {
        border: 5px outset black;
        background-color: lightgoldenrodyellow;
        height: 200px;
        width: 400px;
        margin: 0 auto;
    }
</style>
<body>
    <header>
        <a href=$linkUbersicht>Übersicht</a>
        <a href=$linkBestellung>Bestellung</a>
        <a href=$linkKunde>Kunde</a>
        <a href=$linkBackery>Bäcker</a>
        <a href=$linkFahrer>Fahrer</a>
    </header>
    
    <h1>Kunde</h1>
    <form action="https://echo.fbi.h-da.de/" id="BackerInfos" method="post" lang=$language accept-charset="UTF-8">
EOT;

//test array
$kunden = array(
    array(
        "id" => 1,
        "status" => "bestellt",
        "type" => "Margherita",
    ),
    array(
        "id" => 2,
        "status" => "geliefert",
        "type" => "Hawaii",
    ),
    array(
        "id" => 3,
        "status" => "fertig",
        "type" => "Salami",
    ),
    array(
        "id" => 4,
        "status" => "unterwegs",
        "type" => "Hawaii",
    ),
    array(
        "id" => 5,
        "status" => "im ofen",
        "type" => "Salami",
    )
);

//html for every parameter from $kunden
foreach ($kunden as $kunde) {
    $idBestellt="bestellt" . "$kunde[id]";
    $idImOfen="imOfen" . "$kunde[id]";
    $idFertig="fertig" . "$kunde[id]";
    $idUnterwegs="unterwegs" . "$kunde[id]";
    $idGeliefert="geliefert" . "$kunde[id]";

    $checkStatusArray = array(0 => "", 1 => "", 2 => "", 3 => "", 4 => "");

    if($kunde['status'] == "bestellt") {
        $checkStatusArray[0] = "checked";
    }
    elseif ($kunde['status'] == "im ofen") {
        $checkStatusArray[1] = "checked";
    }
    elseif ($kunde['status'] == "fertig") {
        $checkStatusArray[2] = "checked";
    }
    elseif ($kunde['status'] == "unterwegs") {
        $checkStatusArray[3] = "checked";
    }
    else {
        $checkStatusArray[4] = "checked";
    }

    echo <<<EOT
        <div class="myDiv">
            <h2 style="text-align: center">$kunde[type]</h2>
            <div style="margin-left: 150px">
                <input type="radio" id="$idBestellt" name="$kunde[id]" value="Bestellt" $checkStatusArray[0]>
                <label for="$idBestellt">Bestellt</label><br>
                <input type="radio" id="$idImOfen" name="$kunde[id]" value="Im Ofen" $checkStatusArray[1]>
                <label for="$idImOfen">Im Ofen</label><br>
                <input type="radio" id="$idFertig" name="$kunde[id]" value="Fertig" $checkStatusArray[2]>
                <label for="$idFertig">Fertig</label><br>
                <input type="radio" id="$idUnterwegs" name="$kunde[id]" value="Unterwegs" $checkStatusArray[3]>
                <label for="$idUnterwegs">Unterwegs</label><br>
                <input type="radio" id="$idGeliefert" name="$kunde[id]" value="Geliefert" $checkStatusArray[4]>
                <label for="$idGeliefert">Geliefert</label><br>
            </div>
        </div>
EOT;
}
echo <<<EOT
        <input type="submit" id="submit" value="Bestätigen">
    </form>
</body>
</html>
EOT;

