<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ExcelController extends AbstractController
{
    public function __construct(private IPLocationController $ipLocationController)
    {
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

            if (strlen($split[0]) <= 16 && strlen($split[1]) <= 16) {
                $this->ipLocationController->createIpLocation($split[0], $split[1], $split[5]);                
            }

            $i++;

            if ($i % 1000 == 0) {
                echo "Row $i" . PHP_EOL;
                $this->ipLocationController->clear();
                echo (memory_get_usage(true) / 1048576) . 'MB' . PHP_EOL;
            }
        }

        echo "Read $i rows and got {$this->ipLocationController->countLocations()} locations" . PHP_EOL;
        fclose($handle);
    }
}
