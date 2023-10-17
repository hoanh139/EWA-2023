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