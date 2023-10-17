<?php
$language = "de";
$title = "Pizza Bestellung";
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
  <h1>Übersicht</h1>
  <table class="myTable">
    <tr>
      <th scope="col">Übersicht</th>
    </tr>
    <tr>
      <td>Übersicht</td>
    </tr>
    <tr>
      <td>Bestellung</td>
    </tr>
    <tr>
      <td>Kunde</td>
    </tr>
    <tr>
      <td>Bäcker</td>
    </tr>
    <tr>
      <td>Fahrer</td>
    </tr>
  </table>
</body>
</html>
EOT;