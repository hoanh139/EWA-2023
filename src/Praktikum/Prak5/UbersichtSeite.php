<?php declare(strict_types=1);

require_once './Page.php';

class UbersichtSeite extends Page
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
        return array();
    }

    protected function generateView(): void
    {
        $data = $this->getViewData();
        $this->generatePageHeader('Backer');
        echo <<<EOT
    <h1>Pizza Service</h1>
        <nav class="vertical_nav">
            <ul>
                <li class="vertical_li"><a href="UbersichtSeite.php">Übersicht</a></li>
                <li class="vertical_li"><a href="BestellungSeite.php">Bestellung</a></li>
                <li class="vertical_li"><a href="KundeSeite.php">Kunde</a></li>
                <li class="vertical_li"><a href="BakerySeite.php">Bäcker</a></li>
                <li class="vertical_li"><a href="FahrerSeite.php">Fahrer</a></li>
            </ul>
        </nav>
</body>
</html>
EOT;
        $this->generatePageFooter();
    }

    protected function processReceivedData(): void
    {
        parent::processReceivedData();
    }
    public static function main(): void
    {
        try {
            $page = new UbersichtSeite();
            $page->processReceivedData();
            $page->generateView();
        } catch (Exception $e) {
            header("Content-type: text/html; charset=UTF-8");
            echo $e->getMessage();
        }
    }
}

UbersichtSeite::main();