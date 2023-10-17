<?php
$language = "de";
$title = "Pizza Fahrer";
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
        height: 150px;
        width: 500px;
        margin: 0 auto;
    }
    .myTable {
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
    
    <h1>Fahrer</h1>
    <form action="https://echo.fbi.h-da.de/" id="BackerInfos" method="post" lang=$language accept-charset="UTF-8">
EOT;

//test array
$kunden = array(
    array(
        "id" => 1,
        "status" => "fertig",
        "orders" => "Margherita, Hawaii",
        "price" => 8.5,
        "address" => "Manh Tan Doan, Nieder-Ramstädter Str. 191, 64285 Darmstadt",
    ),
    array(
        "id" => 2,
        "status" => "unterwegs",
        "orders" => "Hawaii, Hawaii, Salami",
        "price" => 12.5,
        "address" => "Quang Minh Phan Ho, Feldbergstr. 38, 64293 Darmstadt",
    ),
    array(
        "id" => 3,
        "status" => "geliefert",
        "orders" => "Margherita, Margherita",
        "price" => 8,
        "address" => "Johannes Link, Tenilo Park 1, 64283 Darmstadt",
    )
);

//html for every parameter from $kunden
foreach ($kunden as $kunde) {
    $idFertig="fertig" . "$kunde[id]";
    $idUnterwegs="unterwegs" . "$kunde[id]";
    $idGeliefert="geliefert" . "$kunde[id]";

    $checkStatusArray = array(0 => "", 1 => "", 2 => "");

    if($kunde['status'] == "fertig") {
        $checkStatusArray[0] = "checked";
    }
    elseif ($kunde['status'] == "unterwegs") {
        $checkStatusArray[1] = "checked";
    }
    else {
        $checkStatusArray[2] = "checked";
    }

    echo <<<EOT
        <div class="myDiv">
            <h3 style="text-align: center">Bestellung $kunde[id]</h3>
            <table class="myTable">
                <tr>
                 <td colspan="2">$kunde[address]</td>
                </tr>
                <tr>
                    <td>$kunde[orders]</td>
                    <td>$kunde[price]€</td>
                </tr>
                <tr>
                    <td colspan="2">
                        <table>
                            <tr>
                                <td>
                                    <input type="radio" id="$idFertig" name="$kunde[id]" value="Fertig" $checkStatusArray[0]>
                                    <label for="$idFertig">Fertig</label><br>
                                </td>
                                <td>
                                    <input type="radio" id="$idUnterwegs" name="$kunde[id]" value="Unterwegs" $checkStatusArray[1]>
                                    <label for="$idUnterwegs">Unterwegs</label><br>
                                </td>
                                <td>
                                    <input type="radio" id="$idGeliefert" name="$kunde[id]" value="Geliefert" $checkStatusArray[2]>
                                    <label for="$idGeliefert">Geliefert</label><br>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </div>
EOT;
}
echo <<<EOT
        <input type="submit" id="submit" value="Bestätigen">
    </form>
</body>
</html>
EOT;
