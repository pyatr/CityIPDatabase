<?php

namespace App\Controller;

use PhpOffice\PhpSpreadsheet\Reader\Csv;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ExcelController extends AbstractController
{
    public function __construct(private IPLocationController $ipLocationController)
    {
    }

    public function parseBuildingsTable(string $filename)
    {
        //Still not enough!
        ini_set('memory_limit', '4096M');

        $csvReader = new Csv();
        $csvReader->setDelimiter(',');
        $spreadSheet = $csvReader->load($filename);
        $activeSheet = $spreadSheet->getActiveSheet();

        for ($i = 0; $i < $activeSheet->getHighestRow() && $i < 1; $i++) {
            $ipRangeFrom = $activeSheet->getCell([0, $i]);
            $ipRangeTo = $activeSheet->getCell([1, $i]);
            $cityName = $activeSheet->getCell([5, $i]);

            // echo $ipRangeFrom . '' . $ipRangeTo . '/' . $cityName . '/' . PHP_EOL;
            $this->ipLocationController->createIpLocation($ipRangeFrom, $ipRangeTo, $cityName);
        }
    }

    public function parseBuildingsTableRaw(string $filename)
    {
        $memoryLimit = 2048;
        ini_set('memory_limit', "{$memoryLimit}M");

        $handle = fopen($filename, 'r');

        if (!$handle) {
            echo 'No handle!' . PHP_EOL;

            return;
        }

        $i = 0;
        $split = '';

        while (!feof($handle)) {
            $buffer = fgets($handle, 4096);
            $split = explode(',', $buffer);

            // echo $ipRangeFrom . '/' . $ipRangeTo . '/' . $cityName . '/' . PHP_EOL;
            $this->ipLocationController->createIpLocation($split[0], $split[1], $split[5]);
            $i++;

            if ($i % 1000 == 0) {
                echo "Row $i" . PHP_EOL;
                // gc_collect_cycles();
                $this->ipLocationController->clear();
                echo (memory_get_usage(true) / 1048576) . 'MB' . PHP_EOL;
            }
        }

        echo "Read $i rows and got {$this->ipLocationController->countLocations()} locations" . PHP_EOL;
        fclose($handle);
    }
}
