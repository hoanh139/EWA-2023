<?php declare(strict_types=1);
// UTF-8 marker äöüÄÖÜß€

require_once './Page.php';

class Kunde extends Page
{
    protected function __construct()
    {
        parent::__construct();
    }

    public function __destruct()
    {
        parent::__destruct();
    }

    protected function getViewData():array
    {
        $pizzaArray = array(array());
        if (isset($_SESSION['User_ID'])) {
            //$_SESSION["Status"] = "second_time";
            $orderingID = $_SESSION["User_ID"];
            $SQLabfrage = "SELECT oa.ordered_article_id, a.name, oa.status FROM ordered_article oa NATURAL JOIN article a WHERE oa.ordering_id = $orderingID ORDER BY oa.ordered_article_id;";
            $recordSet = $this->_database->query($SQLabfrage);
            if (!$recordSet) {
                throw new Exception("Kein Kunde in der Datenbank");
            }

            $count = 0;
            $record = $recordSet->fetch_assoc();
            while ($record) {
                $pizzaArray[$count]["OrderedArticleID"] = $record["ordered_article_id"];
                $pizzaArray[$count]["Name"] = $record["name"];
                $pizzaArray[$count]["Status"] = $record["status"];
                $record = $recordSet->fetch_assoc();
                $count++;
            }
            $recordSet->free();
        }
        return $pizzaArray;
    }

    private function fillStatusInfo(string $OrderedArticleID = "", string $Name = "", string $Status = ""):void
    {

        $idBestellt="bestellt" . "$OrderedArticleID";
        $idImOfen="imOfen" . "$OrderedArticleID";
        $idFertig="fertig" . "$OrderedArticleID";
        $idUnterwegs="unterwegs" . "$OrderedArticleID";
        $idGeliefert="geliefert" . "$OrderedArticleID";

        $checkStatusArray = array(0 => "disabled", 1 => "disabled", 2 => "disabled", 3 => "disabled", 4 => "disabled");

        if ($Status == "0") {
            $checkStatusArray[0] = "checked disabled";
        }
        elseif ($Status == "1") {
            $checkStatusArray[1] = "checked disabled";
        }
        elseif ($Status == "2") {
            $checkStatusArray[2] = "checked disabled";
        }
        elseif ($Status == "3") {
            $checkStatusArray[3] = "checked disabled";
        }
        else {
            $checkStatusArray[4] = "checked disabled";
        }

        echo <<<EOT
        
        <section className="cus_Order" class="myDiv">
            <h2>$Name</h2>
            <h4>Customer ID: $OrderedArticleID</h4>
            <input type="hidden" name="pizza_id" value="$OrderedArticleID">
            <div>
                <input type="radio" id="$idBestellt" name="$OrderedArticleID" value="Bestellt" $checkStatusArray[0]>
                <label for="$idBestellt">Bestellt</label><br>
                <input type="radio" id="$idImOfen" name="$OrderedArticleID" value="Im Ofen" $checkStatusArray[1]>
                <label for="$idImOfen">Im Ofen</label><br>
                <input type="radio" id="$idFertig" name="$OrderedArticleID" value="Fertig" $checkStatusArray[2]>
                <label for="$idFertig">Fertig</label><br>
                <input type="radio" id="$idUnterwegs" name="$OrderedArticleID" value="Unterwegs" $checkStatusArray[3]>
                <label for="$idUnterwegs">Unterwegs</label><br>
                <input type="radio" id="$idGeliefert" name="$OrderedArticleID" value="Geliefert" $checkStatusArray[4]>
                <label for="$idGeliefert">Geliefert</label><br>
            </div>
        </section>
        </br>
        
EOT;
    }

    protected function generateView():void
    {
        $data = $this->getViewData();
        $this->generatePageHeader('Kunde');

        echo <<<EOT
        <script src="StatusUpdate.js"></script>
        <h1>Kunde</h1>
        <div id="customer-container">
EOT;
        foreach ($data as $pizza) {
            $OrderedArticleID = htmlspecialchars($pizza["OrderedArticleID"]);
            $Name = htmlspecialchars($pizza["Name"]);
            $Status = htmlspecialchars($pizza["Status"]);
            $this->fillStatusInfo($OrderedArticleID, $Name, $Status);
        }

        echo <<<EOT
        </div>
</body>
</html>
EOT;
        $this->generatePageFooter();
    }

    protected function processReceivedData():void
    {
        parent::processReceivedData();
        session_start();

        /*if (isset($_SESSION["Status"]) && $_SESSION["Status"] === "second_time"){
            session_destroy();
        }*/
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
            $page = new Kunde();
            $page->processReceivedData();
            $page->generateView();
        } catch (Exception $e) {
            //header("Content-type: text/plain; charset=UTF-8");
            header("Content-type: text/html; charset=UTF-8");
            echo $e->getMessage();
        }
    }
}

// This call is starting the creation of the page.
// That is input is processed and output is created.
Kunde::main();

// Zend standard does not like closing php-tag!
// PHP doesn't require the closing tag (it is assumed when the file ends).
// Not specifying the closing ? >  helps to prevent accidents
// like additional whitespace which will cause session
// initialization to fail ("headers already sent").
//? >
