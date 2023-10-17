<?php declare(strict_types=1);

require_once './Page.php';

class BestellungSeite extends Page
{
    // to do: declare reference variables for members
    // representing substructures/blocks

    /**
     * Instantiates members (to be defined above).
     * Calls the constructor of the parent i.e. page class.
     * So, the database connection is established.
     * @throws Exception
     */
    protected function __construct()
    {
        parent::__construct();
        // to do: instantiate members representing substructures/blocks
    }

    /**
     * Cleans up whatever is needed.
     * Calls the destructor of the parent i.e. page class.
     * So, the database connection is closed.
     */
    public function __destruct()
    {
        parent::__destruct();
    }

    /**
     * Fetch all data that is necessary for later output.
     * Data is returned in an array e.g. as associative array.
     * @return array An array containing the requested data.
     * This may be a normal array, an empty array or an associative array.
     */
    protected function getViewData():array
    {
        // to do: fetch data for this view from the database
        // to do: return array containing data
        $SQLAbfrage = "SELECT * FROM article;";
        $RecordSet = $this->_database->query($SQLAbfrage);
        if (!$RecordSet) {
            throw new Exception("Keine Pizza in der Datenbank");
        }
        $PizzaArray = array(array());
        $Record = $RecordSet->fetch_assoc();
        $count = 0;
        while ($Record) {
            $PizzaArray[$count]["article_id"] = $Record["article_id"];
            $PizzaArray[$count]["name"] = $Record["name"];
            $PizzaArray[$count]["picture"] = $Record["picture"];
            $PizzaArray[$count]["price"] = $Record["price"];
            $Record = $RecordSet->fetch_assoc();
            $count = $count +1;
        }
        $RecordSet->free();

        return $PizzaArray;
    }

    /**
     * First the required data is fetched and then the HTML is
     * assembled for output. i.e. the header is generated, the content
     * of the page ("view") is inserted and -if available- the content of
     * all views contained is generated.
     * Finally, the footer is added.
     * @return void
     */
    private function generatePizzaListView(array $pizzaArray):void
    {
        foreach($pizzaArray as $pizza) {
            echo <<< EOT
    <div>

EOT;
            $pizzaID = htmlspecialchars($pizza["article_id"], ENT_QUOTES, 'UTF-8');
            $pizzaName = htmlspecialchars($pizza["name"], ENT_QUOTES, 'UTF-8');
            $pizzaSrc = "../Resource/Pizza.jpg";
            $pizzaPrice = htmlspecialchars($pizza["price"], ENT_QUOTES, 'UTF-8');
            echo <<< EOT
    <figure>
      <img class="pizza-image" src="$pizzaSrc" alt="">
      <figcaption data-price="$pizzaPrice", data-name="$pizzaName">$pizzaID. $pizzaName $pizzaPrice</figcaption>
    </figure>
    </div>

EOT;
        }
    }

    private function generateAllPizzaOption(array $pizzaArray): void
    {
        $selected = false;
        foreach ($pizzaArray as $pizza){
            $pizzaName = htmlspecialchars($pizza["name"], ENT_QUOTES, 'UTF-8');
            if($selected){
                echo <<< EOT
    <option>$pizzaName</option>

EOT;
            }
            else{
                $selected = true;
                echo <<< EOT
    <option selected>$pizzaName</option>

EOT;
            }
        }
    }

    protected function generateView():void
    {
        $data = $this->getViewData();
        $this->generatePageHeader('Bestellung');
        // to do: output view of this page
        echo <<<EOT
<h1>Bestellung</h1>
<div class="order-section">
  <section class="pizza-section">
    <h2>Speisekarte</h2>

EOT;
        $this->generatePizzaListView($data);
        echo <<<EOT
  </section>
  
  <form action="BestellungSeite.php" id="BestellungInfos" method="post" lang="de" accept-charset="UTF-8">
    <div class="warenkorb-section">
    <article>
      <h2>Warenkorb</h2>
      <div id="cart-container"></div>
      <br>
      <p id="total-price">Total: </p>
      <button onclick="deleteAllCartItems()">Löschen alle</button>
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
      <input type="text" id="address" name="adresse" value="" placeholder="Ihre Adresse" required oninput="checkOrderButton()">
      </label>
      <br>
      <label>E-Mail:
        <input type="email" name="mail" value="" placeholder="Ihre E-Mail">
      </label>
      <br>
      <label>Telefon:
        <input type="text" name="phone" value="" placeholder="Ihre Telefonnummer" pattern="[0-9]*">
      </label>
    </article>
    <div>
      <input type="reset" value="Alle Löschen">
      <input type="reset" value="Auswahl Löschen">
      <input type="submit" id="order-button" value="Bestellen" disabled="true" onclick="sendData()">
    </div>
    </div>
  </form>
</div>

EOT;
        $this->generatePageFooter();
    }

    protected function checkIfPostIsset(): bool
    {
        $issetBool = isset($_POST["gender"])&&isset($_POST["first_name"])
            &&isset($_POST["last_name"])&&isset($_POST["adresse"]);
        if($issetBool){
            return true;
        }
        return false;
    }

    protected function getPizzaID(string $name): ?string
    {
        $SQLabfrage = "SELECT article_id FROM article WHERE article.name = '$name';";
        $Recordset = $this->_database->query($SQLabfrage);

        if ($Recordset == TRUE) {
            $articleid = $Recordset->fetch_assoc();
            return $articleid["article_id"];
        } else {
            echo "Error: " . $SQLabfrage . "<br>" . $this->_database->error;
            return null;
        }
    }

    protected function getData() {
        $data = $_POST['cartData'];
        return json_decode($data, true);
    }

    /**
     * Processes the data that comes via GET or POST.
     * If this page is supposed to do something with submitted
     * data do it here.
     * @return void
     */
    protected function processReceivedData():void
    {
        parent::processReceivedData();
        // to do: call processReceivedData() for all members
        if(count($_POST)){
            if($this->checkIfPostIsset()){
                session_start();
                $adresse = $this->_database->real_escape_string($_POST["adresse"]);
                $date = $this->_database->real_escape_string(date('Y-m-d H:i:s'));
                $bestellungList = $this->getData();
                $SQLabfrage = "INSERT INTO ordering(address, ordering_time) VALUES ('$adresse', '$date');";
                $RecordsetFromOrdereing = $this->_database->query($SQLabfrage);

                if ($RecordsetFromOrdereing == TRUE) {
                    $lastid = $this->_database->insert_id;
                } else {
                    echo "Error: " . $SQLabfrage . "<br>" . $this->_database->error;
                    return;
                }

                $_SESSION["User_ID"] = $lastid;
                //$_SESSION["Status"] = "first_time";

                foreach ($bestellungList as $bestellung){
                    $pizzastatus = 0;

                    if($this->getPizzaID($bestellung) != null){
                        $articleid = intval($this->getPizzaID($bestellung));
                    }
                    else
                        return;

                    $SQLabfrage = "INSERT INTO ordered_article (ordering_id, article_id, status) VALUES ($lastid, $articleid, $pizzastatus);";
                    $Recordset = $this->_database->query($SQLabfrage);

                    if ($Recordset != TRUE) {
                        echo "Error: " . $SQLabfrage . "<br>" . $this->_database->error;
                        return;
                    }
                }
                //PRG-Pattern
                header("HTTP/1.1 303 See Other");
                header("Location: http://localhost/Praktikum/Prak4/KundeSeite.php");
                die();
            }
        }
    }

    /**
     * This main-function has the only purpose to create an instance
     * of the class and to get all the things going.
     * I.e. the operations of the class are called to produce
     * the output of the HTML-file.
     * The name "main" is no keyword for php. It is just used to
     * indicate that function as the central starting point.
     * To make it simpler this is a static function. That is you can simply
     * call it without first creating an instance of the class.
     * @return void
     */
    public static function main():void
    {
        try {
            $page = new BestellungSeite();
            $page->processReceivedData();
            $page->generateView();
        } catch (Exception $e) {
            //header("Content-type: text/plain; charset=UTF-8");
            header("Content-type: text/html; charset=UTF-8");
            echo $e->getMessage();
        }
    }
}

BestellungSeite::main();
