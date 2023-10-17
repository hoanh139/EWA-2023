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

  <h1>Bestellung</h1>

  <section>
    <h2>Speisekarte</h2>
    <figure>
      <img src="colorful-round-tasty-pizza_1284-10219.jpg" alt="" width="100" height="100">
      <figcaption data-preis="4.0">Pizza Margherita</figcaption>
    </figure>
    <figure>
      <img src="colorful-round-tasty-pizza_1284-10219.jpg" alt="" width="100" height="100">
      <figcaption data-preis="4.0">Pizza Salami</figcaption>
    </figure>
    <figure>
      <img src="colorful-round-tasty-pizza_1284-10219.jpg" alt="" width="100" height="100">
      <figcaption data-preis="4.0">Pizza Hawai</figcaption>
    </figure>
  </section>


  <form action="https://echo.fbi.h-da.de/" id="BestellungInfos" method="post" lang=$language accept-charset="UTF-8">
    <article>
      <h2>Warenkorb</h2>
      <select name="top4[]" size="3" multiple>
        <option selected>Hawai</option>
        <option>Margherita</option>
        <option>Salami</option>
      </select>
      <br>
      <p>Summe: 25€</p>
    </article>
    <article>
      <h3>Personal Infos</h3>
      <input type="radio" id="gender_m" name="gender" value="gender_m">
      <label for="gender_m">m</label>
      <input type="radio" id="gender_f" name="gender" value="gender_f">
      <label for="gender_f">w</label>
      <input type="radio" id="gender_d" name="gender" value="gender_d" checked>
      <label for="gender_d">d</label>
      <br>
      <label>Nachname:
        <input type="text" name="first_name" value="" placeholder="Ihr Vorname" required>
      </label>
      <br>
      <label>Vorname:
        <input type="text" name="last_name" value="" placeholder="Ihr Nachname" required>
      </label>
      <br>
      <label>
        Ihre Adresse:
      <input type="text" name="adresse" value="" placeholder="Ihre Adresse" required>
      </label>
      <br>
      <label>E-Mail:
        <input type="email" name="mail" value="" placeholder="Ihre E-Mail" required>
      </label>
      <br>
      <label>Telefon:
        <input type="text" name="phone" value="" placeholder="Ihre Telefonnummer" pattern="[0-9]*" required>
      </label>
    </article>
    <div>
      <input type="reset" value="Alle Löschen">
      <input type="reset" value="Auswahl Löschen">
      <input type="submit" value="Bestellen">
    </div>
  </form>
</body>
</html>
EOT;