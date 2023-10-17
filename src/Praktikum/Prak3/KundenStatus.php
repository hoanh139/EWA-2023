<?php

require_once './Page.php';

class KundenStatus extends Page
{
    protected function __construct()
    {
        parent::__construct();
    }

    public function __destruct()
    {
        parent::__destruct();
    }

    protected function getViewData(): array
    {
        // $inputID = $_GET['orderedID'];
        // $escapeID = $this->_database->real_escape_string($inputID);
        // $query = "SELECT * FROM ordered_article WHERE ordered_article_id = '$escapeID'";

        /*$query = "SELECT * FROM ordered_article";
        $recordSet = $this->_database->query($query);
        if (!$recordSet) {
            throw new Exception("Kein Bestellung in der DatenBank");
        }

        $data = array();
        while ($row = $recordSet->fetch_assoc()) {
            $data[] = $row;
        }
        return $data;*/

        $pizzaArray = array(array());
        if (isset($_SESSION['User_ID'])) {
            $_SESSION["Status"] = "second_time";
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

    protected function generateView():void
    {
        header("Content-Type: application/json; charset=UTF-8");

        $data = $this->getViewData();
        $serializedData = json_encode($data);
        echo $serializedData;
    }

    protected function processReceivedData():void
    {
        parent::processReceivedData();
        session_start();
    }

    public static function main():void
    {
        try {
            $page = new KundenStatus();
            $page->processReceivedData();
            $page->generateView();
        } catch (Exception $e) {
            header("Content-Type: application/json; charset=UTF-8");
            echo $e->getMessage();
        }
    }
}

KundenStatus::main();