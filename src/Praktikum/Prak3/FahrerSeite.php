<?php declare(strict_types=1);

require_once './Page.php';

class Fahrer extends Page
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
        $SQLabfrage = "SELECT * FROM ordering";
        $recordSet = $this->_database->query($SQLabfrage);
        if (!$recordSet) {
            throw new Exception("Keine Bestellung in der Datenbank");
        }
        $bestellungArray = array();
        $count = 0;

        $record = $recordSet->fetch_assoc();
        while ($record) {
            $bestellungArray[$count]["OrderingID"] = $record["ordering_id"];
            $bestellungArray[$count]["Address"]= $record["address"];
            $bestellungArray[$count]["OrderingTime"] = $record["ordering_time"];

            $OrderingID = $record["ordering_id"];

            $pizzaAbfrage = "SELECT oa.ordering_id, a.name, oa.status, a.price FROM ordered_article oa NATURAL JOIN article a WHERE oa.ordering_id = $OrderingID ORDER BY oa.ordering_id";
            $pizzaRecord = $this->_database->query($pizzaAbfrage);
            $record1 = $pizzaRecord->fetch_assoc();

            $TotalPrice = 0;
            $ListOfPizza = "";
            $AllStatus = array();

            while ($record1) {
                if ($record1["ordering_id"] == $record["ordering_id"]) {
                    $TotalPrice += $record1["price"];
                    array_push($AllStatus, $record1["status"]);

                    if ($ListOfPizza == "") {
                        $ListOfPizza = $record1["name"];
                    }
                    else {
                        $ListOfPizza = $ListOfPizza.",  ".$record1["name"];
                    }
                }
                $record1 = $pizzaRecord->fetch_assoc();
            }
            $pizzaRecord->free();

            $bestellungArray[$count]["TotalPrice"] = (string)$TotalPrice.'€';
            $bestellungArray[$count]["ListOfPizza"] = $ListOfPizza;
            $bestellungArray[$count]["Status"] = (string) min($AllStatus);

            $record = $recordSet->fetch_assoc();
            $count++;
        }
        $recordSet->free();

        return $bestellungArray;
    }

    private function fillStatusInfo (string $OrderingID = "", string $Address = "", string $OrderingTime = "", string $TotalPrice = "", string $ListOfPizza = "", string  $Status = "")
    {
        $idFertig="fertig" . "$OrderingID";
        $idUnterwegs="unterwegs" . "$OrderingID";
        $idGeliefert="geliefert" . "$OrderingID";

        $checkStatusArray = array(0 => "", 1 => "", 2 => "");

        //Disable button if status < 2
        if ($Status == "2") {
            $checkStatusArray[0] = "checked";
        }
        elseif ($Status == "3") {
            $checkStatusArray[1] = "checked";
        }
        elseif ($Status == "4") {
            $checkStatusArray[2] = "checked";
        }
        else{
            $checkStatusArray[0] = "disabled";
            $checkStatusArray[1] = "disabled";
            $checkStatusArray[2] = "disabled";
        }

        $formID = "ID" . $OrderingID;

        echo <<<EOT
        <div class="myDiv">
            <h2 style="text-align: center">Bestellung $OrderingID</h2>
            <table style="margin: 0 auto">
                <tr>
                    <td>$Address</td>
                </tr>
                <tr>
                    <td>$OrderingTime</td>
                </tr>
                <tr>
                    <td>$ListOfPizza:   $TotalPrice</td>
                </tr>
                <tr>
                    <td colspan="2">
                        <form id="$formID" action="FahrerSeite.php" method="post">
                            <table>
                                <tr>
                                    <td>
                                        <input type="radio" id="$idFertig" name="$OrderingID" value="Fertig" onclick="document.forms['$formID'].submit();" $checkStatusArray[0]>
                                        <label for="$idFertig">Fertig</label><br>
                                    </td>
                                    <td>
                                        <input type="radio" id="$idUnterwegs" name="$OrderingID" value="Unterwegs" onclick="document.forms['$formID'].submit();" $checkStatusArray[1]>
                                        <label for="$idUnterwegs">Unterwegs</label><br>
                                    </td>
                                    <td>
                                        <input type="radio" id="$idGeliefert" name="$OrderingID" value="Geliefert" onclick="document.forms['$formID'].submit();" $checkStatusArray[2]>
                                        <label for="$idGeliefert">Geliefert</label><br>
                                    </td>
                                </tr>
                            </table>
                        </form>
                    </td>
                </tr>
            </table>
        </div>
        EOT;
    }

    protected function generateView():void
    {
        $data = $this->getViewData();

        $sec = "10";
        $page = $_SERVER['PHP_SELF'];
        header("Refresh: $sec; url=$page");

        $this->generatePageHeader('Fahrer');

        echo <<<EOT
        <h1>Fahrer</h1>
        EOT;

        foreach ($data as $bestellung) {
            $OrderingID = htmlspecialchars($bestellung["OrderingID"]);
            $Address = htmlspecialchars($bestellung["Address"]);
            $OrderingTime = htmlspecialchars($bestellung["OrderingTime"]);
            $TotalPrice = htmlspecialchars($bestellung["TotalPrice"]);
            $ListOfPizza = htmlspecialchars($bestellung["ListOfPizza"]);
            $Status = htmlspecialchars($bestellung["Status"]);
            $this->fillStatusInfo($OrderingID, $Address, $OrderingTime, $TotalPrice, $ListOfPizza, $Status);
        }

        echo <<<EOT
    </body>
</html>
EOT;

        $this->generatePageFooter();
    }

    protected function processReceivedData(): void
    {
        parent::processReceivedData();

        if (count($_POST)) {
            if (isset($_POST)) {
                foreach ($_POST as $OrderingID => $Status) {
                    $SQLabfrage = "SELECT * FROM ordering WHERE ordering_id = $OrderingID";
                    $RecordSet = $this->_database->query($SQLabfrage);

                    if ($RecordSet->num_rows == 0) {
                        $RecordSet->free();
                        throw new Exception("Keine Bestellung gefunden!");
                    }
                    else {
                        if ($Status == "Fertig") {
                            $Status = "2";
                        }
                        elseif ($Status == "Unterwegs") {
                            $Status = "3";
                        }
                        else {
                            $Status = "4";
                        }

                        $SQLabfrage = "UPDATE ordered_article SET status = $Status WHERE ordering_id = $OrderingID";
                        $this->_database->query($SQLabfrage);
                    }
                }
            }
        }
    }

    public static function main():void
    {
        try {
            $page = new Fahrer();
            $page->processReceivedData();
            $page->generateView();
        } catch (Exception $e) {
            header("Content-type: text/html; charset=UTF-8");
            echo $e->getMessage();
        }
    }
}

Fahrer::main();
