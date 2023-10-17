<?php declare(strict_types=1);

require_once './Page.php';

class BackerSeite extends Page
{
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
    protected function getViewData(): array
    {
        // to do: fetch data for this view from the database
        // to do: return array containing data
        $SQLAbfrage = "SELECT oa.ordered_article_id, oa.ordering_id, a.name, oa.status, o.ordering_time FROM article a NATURAL JOIN ordered_article oa NATURAL JOIN ordering o WHERE oa.status < 3 ORDER BY o.ordering_time, oa.ordered_article_id;";
        $RecordSet = $this->_database->query($SQLAbfrage);
        if (!$RecordSet) {
            throw new Exception("Keine Bestellung in der Datenbank");
        }
        $BestellungArray = array(array());
        $Record = $RecordSet->fetch_assoc();
        $count = 0;
        while ($Record) {
            $BestellungArray[$count]["OrderedArticleID"] = $Record["ordered_article_id"];
            $BestellungArray[$count]["OrderingID"] = $Record["ordering_id"];
            $BestellungArray[$count]["Name"] = $Record["name"];
            $BestellungArray[$count]["Status"] = $Record["status"];
            $BestellungArray[$count]["OrderingTime"] = $Record["ordering_time"];
            $Record = $RecordSet->fetch_assoc();
            $count = $count + 1;
        }
        $RecordSet->free();

        return $BestellungArray;
    }

    private function fillOrderInfo(string $FormID, string $OrderedArticleID, string $OrderingID, string $Name, string $Status, string $OrderingTime): void
    {
        $idBestellt = "ordered" . "$OrderedArticleID";
        $idImOfen = "ofen" . "$OrderedArticleID";
        $idFertig = "fertig" . "$OrderedArticleID";
        $checkStatusArray = array(0 => "", 1 => "", 2 => "", 3 => "", 4 => "");

        if ($Status == "0") {
            $checkStatusArray[0] = "checked";
        } elseif ($Status == "1") {
            $checkStatusArray[1] = "checked";
        } else {
            $checkStatusArray[2] = "checked";
        }
        echo <<<EOT
        <div class="myDiv">
            <h2>$OrderedArticleID $Name </h2>
            <p>Customer: $OrderingID</p>
            <p>$OrderingTime </p>
            <div>
                <input form="$FormID" type="radio" name="pizzaStatus" value="0" onclick="document.forms['$FormID'].submit();" $checkStatusArray[0]>
                <label for="$idBestellt">Bestellt</label><br>
                <input form="$FormID" type="radio" name="pizzaStatus" value="1" onclick="document.forms['$FormID'].submit();" $checkStatusArray[1]>
                <label for="$idImOfen">Im Ofen</label><br>
                <input form="$FormID" type="radio" name="pizzaStatus" value="2" onclick="document.forms['$FormID'].submit();" $checkStatusArray[2]>
                <label for="$idFertig">Fertig</label><br>
                <input form="$FormID" type="hidden" name="pizzaID" value=$OrderedArticleID>
            </div>
        </div>

EOT;
    }

    private function showPizzaList(array $data): void
    {
        foreach ($data as $bestellung){
            $OrderedArticleID = htmlspecialchars($bestellung["OrderedArticleID"], ENT_QUOTES, 'UTF-8');
            $OrderingID = htmlspecialchars($bestellung["OrderingID"], ENT_QUOTES, 'UTF-8');
            $Name = htmlspecialchars($bestellung["Name"], ENT_QUOTES, 'UTF-8');
            $FormID = "Pizza" . $OrderedArticleID;
            $Status = htmlspecialchars($bestellung["Status"], ENT_QUOTES, 'UTF-8');
            $OrderingTime = htmlspecialchars($bestellung["OrderingTime"], ENT_QUOTES, 'UTF-8');
            echo <<< EOT
    <form id="$FormID" action="BakerySeite.php" method="post" lang="de" accept-charset="UTF-8"></form>

EOT;
            $this->fillOrderInfo($FormID, $OrderedArticleID, $OrderingID, $Name, $Status, $OrderingTime);
        }
    }

    /**
     * First the required data is fetched and then the HTML is
     * assembled for output. i.e. the header is generated, the content
     * of the page ("view") is inserted and -if available- the content of
     * all views contained is generated.
     * Finally, the footer is added.
     * @return void
     */
    protected function generateView(): void
    {
        $data = $this->getViewData();

        $sec = "10";
        $page = $_SERVER['PHP_SELF'];
        header("Refresh: $sec; url=$page");

        $this->generatePageHeader('Backer');
        // to do: output view of this page
        echo <<<EOT
    <h1>Bäcker</h1>

EOT;
    $this->showPizzaList($data);
    $this->generatePageFooter();
    }

    /**
     * Processes the data that comes via GET or POST.
     * If this page is supposed to do something with submitted
     * data do it here.
     * @return void
     */
    protected function processReceivedData(): void
    {
        parent::processReceivedData();
        // to do: call processReceivedData() for all members

        if(count($_POST)){
            if(isset($_POST["pizzaID"])&&isset($_POST["pizzaStatus"])) {
                $OrderedArticleID = $this->_database->real_escape_string($_POST["pizzaID"]);
                $Status = $this->_database->real_escape_string($_POST["pizzaStatus"]);
                // Doppeleintrag verhindern:
                $SQLabfrage = "SELECT * FROM ordered_article WHERE ordered_article_id = '$OrderedArticleID';";
                $Recordset = $this->_database->query($SQLabfrage);

                if ($Recordset->num_rows == 0) {
                    $Recordset->free();
                    throw new Exception("Keine Pizza Bestellung gefunden!");
                } else { // update status
                    $SQLabfrage = "UPDATE ordered_article SET status = $Status WHERE ordered_article_id = $OrderedArticleID;";
                    $this->_database->query($SQLabfrage);
                }
            }
        }
    }

    public static function main(): void
    {
        try {
            $page = new BackerSeite();
            $page->processReceivedData();
            $page->generateView();
        } catch (Exception $e) {
            //header("Content-type: text/plain; charset=UTF-8");
            header("Content-type: text/html; charset=UTF-8");
            echo $e->getMessage();
        }
    }
}

BackerSeite::main();
